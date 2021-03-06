<?php
/**
 * AffiliateWP Admin Notices class
 *
 * @since 1.0
 */
class Affiliate_WP_Admin_Notices {

	/**
	 * Current AffiliateWP version.
	 *
	 * @since 2.0
	 * @var string
	 */
	private $version;

	/**
	 * Whether to display notices.
	 *
	 * Used primarily for unit testing expected output.
	 *
	 * @since 2.1
	 * @var bool Default true.
	 */
	private $display_notices = true;

	/**
	 * Notices registry.
	 *
	 * @since 2.4
	 * @var   \AffWP\Admin\Notices_Registry
	 */
	private static $registry;

	/**
	 * Sets up the notices API.
	 *
	 * Core notices are registered against the affwp_notices_registry_init hook, which
	 * grants local access to a canonical instance of the Notices_Registry class.
	 * The init() method of the Notices_Registry class is likewise hooked to admin_init,
	 * thereby effectively registering admin notices on admin_init.
	 *
	 * Since all core notices are registered on the one hook, a similar system can also
	 * be employed by any third-parties (including add-ons) wanting to hook into the core
	 * admin notices API for various purposes.
	 *
	 * Example:
	 *
	 *     add_action( 'affwp_notices_registry_init', function( $registry ) {
	 *         $registry->add_notice( 'example-notice', array(
	 *             'class'   => 'error',
	 *             'message' => 'There was an error with {component}.',
	 *         ) );
	 *     }, 11 );
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$registry = new \AffWP\Admin\Notices_Registry;

		add_action( 'affwp_notices_registry_init', array( $this,     'register_notices' ) );
		add_action( 'admin_init',                  array( $registry, 'init'             ) );
		add_action( 'admin_notices',               array( $this,     'show_notices'     ) );
		add_action( 'affwp_dismiss_notices',       array( $this,     'dismiss_notices'  ) );
	}

	/**
	 * Sets the registry for use by the class.
	 *
	 * @since 2.4
	 *
	 * @param \AffWP\Admin\Notices_Registry $registry Registry instance.
	 */
	private static function set_registry( $registry ) {
		self::$registry = $registry;
	}

	/**
	 * Registers the admin notices.
	 *
	 * @since 2.4
	 *
	 * @param \AffWP\Admin\Notices_Registry $registry Registry instance.
	 */
	public function register_notices( $registry ) {
		// Set up local access of the single registry instance.
		self::set_registry( $registry );

		$this->affiliate_notices();
		$this->consumer_notices();
		$this->creative_notices();
		$this->payout_notices();
		$this->referral_notices();

		$this->integration_notices();
		$this->license_notices();
		$this->settings_notices();
		$this->upgrade_notices();
	}

	/**
	 * Outputs general admin notices.
	 *
	 * @since 1.0
	 * @since 1.8.3 Notices are hidden for users lacking the 'manage_affiliates' capability
	 *
	 * @return string|void Output if `$display_notices` is false, otherwise void.
	 */
	public function show_notices() {
		$affwp_message = $notice_id = $output = '';

		// Handle displaying registered notices triggered via the 'affwp_notice' query arg in the URL.
		if ( ! empty( $_REQUEST['affwp_notice'] ) ) {
			$notice_id = urldecode( sanitize_text_field( $_REQUEST['affwp_notice'] ) );

			$output .= self::show_notice( $notice_id, false );
		}

		// Handle displaying the settings-updated notice.
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] && isset( $_GET['page'] ) && $_GET['page'] == 'affiliate-wp-settings' ) {
			$output .= self::show_notice( 'settings-updated', false );
		}

		$integrations = affiliate_wp()->integrations->get_enabled_integrations();

		if ( empty( $integrations ) && ! get_user_meta( get_current_user_id(), '_affwp_no_integrations_dismissed', true ) ) {
			$output .= self::show_notice( 'no_integrations', false );
		}

		// Payouts Service.
		if ( in_array( affwp_get_current_screen(), array( 'affiliate-wp-referrals', 'affiliate-wp-payouts'), true ) ) {
			$vendor_id  = affiliate_wp()->settings->get( 'payouts_service_vendor_id', 0 );
			$access_key = affiliate_wp()->settings->get( 'payouts_service_access_key', '' );

			if ( ! ( $vendor_id && $access_key ) && false === get_transient( 'affwp_payouts_service_notice' ) ) {
				$output .= self::show_notice( 'payouts_service', false );
			}
		}

		// Don't display other types of notices for users who can't manage affiliates.
		if ( current_user_can( 'manage_affiliates' ) ) {
			// Compat for displaying notices defined via the 'affwp_message' query arg.
			if ( ! empty( $_REQUEST['affwp_message'] ) ) {
				$affwp_message = urldecode( sanitize_text_field( $_REQUEST['affwp_message'] ) );

				if ( ! empty( $_REQUEST['affwp_success'] ) && 'no' === $_REQUEST['affwp_success'] ) {
					$class = 'error';
				} else {
					$class = 'updated';
				}

				$output .= self::prepare_message_for_output( $affwp_message, $class );
			}
		}

		if ( true === $this->display_notices ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Registers affiliate notices.
	 *
	 * @since 2.4
	 */
	private function affiliate_notices() {
		$this->add_notice( 'affiliate_added', array(
			'message' => function() {
				require_once AFFILIATEWP_PLUGIN_DIR . 'includes/admin/tools/class-migrate-users.php';

				$total_affiliates = (int) Affiliate_WP_Migrate_Users::get_items_total( 'affwp_migrate_users_current_count' );

				/*
				 * If $total_affiliates is 0 and we know 'affiliate_added' has been fired,
				 * it was a manual addition, and therefore 1 affiliate was added.
				 */
				if ( 0 === $total_affiliates ) {
					$total_affiliates = 1;
				}

				$message = sprintf( _n(
					'%d affiliate was added successfully.',
					'%d affiliates were added successfully',
					$total_affiliates,
					'affiliate-wp'
				), number_format_i18n( $total_affiliates ) );

				Affiliate_WP_Migrate_Users::clear_items_total( 'affwp_migrate_users_current_count' );

				return $message;
			},
		) );

		$this->add_notice( 'affiliate_added_failed', array(
			'class'   => 'error',
			'message' => __( 'Affiliate wasn&#8217;t added, please try again.', 'affiliate-wp' ),
		) );

		$this->add_notice( 'affiliate_updated', array(
			'message' => function() {
				$message =  __( 'Affiliate updated successfully', 'affiliate-wp' );
				$message .= '<p>'. sprintf( __( '<a href="%s">Back to Affiliates</a>.', 'affiliate-wp' ), esc_url( affwp_admin_url( 'affiliates' ) ) ) .'</p>';

				return $message;
			},
		) );

		$this->add_notice( 'affiiate_update_failed', array(
			'class'   => 'error',
			'message' => __( 'Affiliate update failed, please try again', 'affiliate-wp' ),
		) );

		$this->add_notice( 'affiliate_deleted', array(
			'message' => __( 'Affiliate account(s) deleted successfully', 'affiliate-wp' ),
		) );

		$this->add_notice( 'affiliate_delete_failed', array(
			'class'   => 'error',
			'message' => __( 'Affiliate deletion failed, please try again', 'affiliate-wp' ),
		) );

		$this->add_notice( 'affiliate_activated', array(
			'message' => __( 'Affiliate account activated', 'affiliate-wp' ),
		) );

		$this->add_notice( 'affiliate_deactivated', array(
			'message' => __( 'Affiliate account deactivated', 'affiliate-wp' ),
		) );

		$this->add_notice( 'affiliate_accepted', array(
			'message' => __( 'Affiliate request was accepted', 'affiliate-wp' ),
		) );

		$this->add_notice( 'affiliate_rejected', array(
			'message' => __( 'Affiliate request was rejected', 'affiliate-wp' ),
		) );
	}

	/**
	 * Registers API consumer admin notices.
	 *
	 * @since 2.4
	 */
	private function consumer_notices() {
		$this->add_notice( 'api_key_generated', array(
			'message' => __( 'The API keys were successfully generated.', 'affiliate-wp' ),
		) );

		$this->add_notice( 'api_key_failed', array(
			'class'   => 'error',
			'message' => __( 'The API keys could not be generated.', 'affiliate-wp' ),
		) );

		$this->add_notice( 'api_key_regenerated', array(
			'message' => __( 'The API keys were successfully regenerated.', 'affiliate-wp' ),
		) );

		$this->add_notice( 'api_key_revoked', array(
			'message' => __( 'The API keys were successfully revoked.', 'affiliate-wp' ),
		) );
	}

	/**
	 * Registers creative admin notices.
	 *
	 * @since 2.4
	 */
	private function creative_notices() {
		$this->add_notice( 'creative_updated', array(
			'message' => function() {
				$message =  __( 'Creative updated successfully', 'affiliate-wp' );
				$message .= '<p>'. sprintf( __( '<a href="%s">Back to Creatives</a>', 'affiliate-wp' ), esc_url( affwp_admin_url( 'creatives' ) ) ) .'</p>';

				return $message;
			},
		) );

		$this->add_notice( 'creative_added', array(
			'message' => __( 'Creative added successfully', 'affiliate-wp' ),
		) );

		$this->add_notice( 'creative_deleted', arraY(
			'message' => __( 'Creative deleted successfully', 'affiliate-wp' ),
		) );

		$this->add_notice( 'creative_activated', array(
			'message' => __( 'Creative activated', 'affiliate-wp' ),
		) );

		$this->add_notice( 'creative_deactivated', array(
			'message' => __( 'Creative deactivated', 'affiliate-wp' ),
		) );
	}

	/**
	 * Registers payout admin notices.
	 *
	 * @since 2.4
	 */
	public function payout_notices() {
		$this->add_notice( 'payout_created', array(
			'message' => __( 'A payout has been created.', 'affiliate-wp' ),
		) );

		$this->add_notice( 'payout_deleted', array(
			'message' => __( 'Payout deleted successfully.', 'affiliate-wp' ),
		) );

		$this->add_notice( 'payout_delete_failed', array(
			'message' => __( 'Payout deletion failed, please try again.', 'affiliate-wp' ),
		) );

		// Payouts service notices.
		$this->add_notice( 'payouts_service_site_connected', array(
			'message' => __( 'Website connected to the AffiliateWP Payouts Service.', 'affiliate-wp' ),
		) );

		$this->add_notice( 'payouts_service_site_disconnected', array(
			'message' => __( 'Website disconnected from the AffiliateWP Payouts Service.', 'affiliate-wp' ),
		) );

		$this->add_notice( 'payouts_service_site_reconnected', array(
			'message' => __( 'Website reconnected to the AffiliateWP Payouts Service.', 'affiliate-wp' ),
		) );

		// Payouts Service.
		$message = '<p><strong>' . __( 'Effortlessly pay your affiliates', 'affiliate-wp' ) . '</strong></p>';

		$message .= sprintf(
			__( 'With the Payouts Service provided by AffiliateWP, you can easily pay affiliates in 31 countries using any debit or credit card. Learn more at <a href="%s" target="_blank">payouts.sandhillsdev.com</a>.', 'affiliate-wp' ),
			'https://payouts.sandhillsdev.com'
		);

		$added = $this->add_notice( 'payouts_service', array(
			'class'         => 'updated',
			'message'       => $message,
			'dismissible'   => true,
			'dismiss_label' => _x( 'Maybe later', 'payouts service', 'affiliate-wp' ),
		) );
	}

	/**
	 * Registers referral admin notices.
	 *
	 * @since 2.4
	 */
	private function referral_notices() {
		$this->add_notice( 'referral_added', array(
			'message' => __( 'Referral added successfully', 'affiliate-wp' ),
		) );

		$this->add_notice( 'referral_add_failed', array(
			'class'   => 'error',
			'message' => __( 'Referral wasn&#8217;t created, please try again.', 'affiliate-wp' ),
		) );

		$this->add_notice( 'referral_add_invalid_affiliate', array(
			'class'   => 'error',
			'message' => __( 'Referral not created because affiliate is invalid', 'affiliate-wp' ),
		) );

		$this->add_notice( 'referral_updated', array(
			'message' => __( 'Referral updated successfully', 'affiliate-wp' ),
		) );

		$this->add_notice( 'referral_update_failed', array(
			'message' => __( 'Referral update failed, please try again', 'affiliate-wp' ),
		) );

		$this->add_notice( 'referral_deleted', array(
			'message' => __( 'Referral deleted successfully', 'affiliate-wp' ),
		) );

		$this->add_notice( 'referral_delete_failed', array(
			'class'   => 'error',
			'message' => __( 'Referral deletion failed, please try again', 'affiliate-wp' ),
		) );
	}

	/**
	 * Displays upgrade notices.
	 *
	 * @since 2.0
	 */
	public function upgrade_notices() {
		if ( true === version_compare( AFFILIATEWP_VERSION, '2.0', '<' ) || false === affwp_has_upgrade_completed( 'upgrade_v20_recount_unpaid_earnings' ) ) :
			self::show_notice( function() {
				ob_start();

				// Enqueue admin JS for the batch processor.
				affwp_enqueue_admin_js();
				?>
				<div class="notice notice-info is-dismissible">
					<p><?php _e( 'Your database needs to be upgraded following the latest AffiliateWP update.', 'affiliate-wp' ); ?></p>
					<form method="post" class="affwp-batch-form" data-batch_id="recount-affiliate-stats-upgrade" data-nonce="<?php echo esc_attr( wp_create_nonce( 'recount-affiliate-stats-upgrade_step_nonce' ) ); ?>">
						<p>
							<?php submit_button( __( 'Upgrade Database', 'affiliate-wp' ), 'secondary', 'v20-recount-unpaid-earnings', false ); ?>
						</p>
					</form>
				</div>
				<?php

				return ob_get_clean();
			} );
		endif;

		if ( false === affwp_has_upgrade_completed( 'upgrade_v22_create_customer_records' ) ) :
			self::show_notice( function() {
				ob_start();

				// Enqueue admin JS for the batch processor.
				affwp_enqueue_admin_js();
				?>
				<div class="notice notice-info is-dismissible">
					<p><?php _e( 'Your database needs to be upgraded following the latest AffiliateWP update. Depending on the size of your database, this upgrade could take some time.', 'affiliate-wp' ); ?></p>
					<form method="post" class="affwp-batch-form" data-batch_id="create-customers-upgrade" data-nonce="<?php echo esc_attr( wp_create_nonce( 'create-customers-upgrade_step_nonce' ) ); ?>">
						<p>
							<?php submit_button( __( 'Upgrade Database', 'affiliate-wp' ), 'secondary', 'v22-create-customers', false ); ?>
						</p>
					</form>
				</div>
				<?php

				return ob_get_clean();
			} );
 		endif;
	}

	/**
	 * Display admin notices related to integrations.
	 *
	 * @since 2.1
	 * @since 2.4 Refactored to leverage the notices registry
	 *
	 * @return string|void Output if `$display_notices` is false, otherwise void.
	 */
	public function integration_notices() {
		$this->add_notice( 'no_integrations', array(
			'class'   => 'error',
			'message' => function() {
				$message =  sprintf( __( 'There are currently no AffiliateWP <a href="%s">integrations</a> enabled. If you are using AffiliateWP without any integrations, you may disregard this message.', 'affiliate-wp' ), affwp_admin_url( 'settings', array( 'tab' => 'integrations' ) ) ) . '</p>';
				$message .= '<p><a href="' . wp_nonce_url( add_query_arg( array( 'affwp_action' => 'dismiss_notices', 'affwp_notice' => 'no_integrations' ) ), 'affwp_dismiss_notice', 'affwp_dismiss_notice_nonce' ) . '">' . _x( 'Dismiss Notice', 'Integrations', 'affiliate-wp' ) . '</a>';

				return $message;
			},
		) );
	}

	/**
	 * Display admin notices related to licenses.
	 *
	 * @since 2.1
	 * @since 2.4 Refactored to leverage the notices registry
	 *
	 * @return string|void Output if `$display_notices` is false, otherwise void.
	 */
	public function license_notices() {
		$license = affiliate_wp()->settings->check_license();

		if ( is_object( $license ) && ! is_wp_error( $license ) ) {
			$this->add_notice( 'license-expired', array(
				'class'   => 'error',
				'message' => function() use ( $license ) {
					$license_key = \Affiliate_WP_Settings::get_license_key();

					return sprintf(
						__( 'Your license key expired on %s. Please <a href="%s" target="_blank">renew your license key</a>.', 'affiliate-wp' ),
						affwp_date_i18n( strtotime( $license->expires, current_time( 'timestamp' ) ) ),
						'https://affiliatewp.com/checkout/?edd_license_key=' . $license_key . '&utm_campaign=admin&utm_source=licenses&utm_medium=expired'
					);
				},
			) );
		}
		$this->add_notice( 'license-revoked', array(
			'class'   => 'error',
			'message' => sprintf(
				__( 'Your license key has been disabled. Please <a href="%s" target="_blank">contact support</a> for more information.', 'affiliate-wp' ),
				'https://affiliatewp.com/support?utm_campaign=admin&utm_source=licenses&utm_medium=revoked'
			),
		) );

		$this->add_notice( 'license-missing', array(
			'class'   => 'error',
			'message' => sprintf(
				__( 'Invalid license. Please <a href="%s" target="_blank">visit your account page</a> and verify it.', 'affiliate-wp' ),
				'https://affiliatewp.com/account/?utm_campaign=admin&utm_source=licenses&utm_medium=missing'
			),
		) );

		$this->add_notice( 'license-invalid', array(
			'class'   => 'error',
			'alias'   => 'license-site_inactive',
			'message' => sprintf(
				__( 'Your license key is not active for this URL. Please <a href="%s" target="_blank">visit your account page</a> to manage your license key URLs.', 'affiliate-wp' ),
				'https://affiliatewp.com/account/?utm_campaign=admin&utm_source=licenses&utm_medium=invalid'
			),
		) );

		$this->add_notice( 'license-item_name_mismatch', array(
			'class'   => 'error',
			'message' => __( 'This appears to be an invalid license key.', 'affiliate-wp' ),
		) );

		$this->add_notice( 'license-no_activations_left', array(
			'class'   => 'error',
			'message' => sprintf(
				__( 'Your license key has reached its activation limit. <a href="%s">View possible upgrades</a> now.', 'affiliate-wp' ),
				'https://affiliatewp.com/account/?utm_campaign=admin&utm_source=licenses&utm_medium=missing'
			),
		) );

		$this->add_notice( 'expired_license', array(
			'class'   => array( 'error', 'info' ),
			'message' => function() {
				$notice_query_args = array(
					'affwp_action' => 'dismiss_notices',
					'affwp_notice' => 'expired_license',
				);

				$message =  __( 'Your license key for AffiliateWP has expired. Please renew your license to re-enable automatic updates.', 'affiliate-wp' ) . '</p>';
				$message .= '<p><a href="' . wp_nonce_url( add_query_arg( $notice_query_args ), 'affwp_dismiss_notice', 'affwp_dismiss_notice_nonce' ) . '">' . _x( 'Dismiss Notice', 'License', 'affiliate-wp' ) . '</a>';

				return $message;
			},
		) );

		$this->add_notice( 'invalid_license', array(
			'class'   => array( 'notice', 'notice-info' ),
			'message' => function() {
				$notice_query_args = array(
					'affwp_action' => 'dismiss_notices',
					'affwp_notice' => 'invalid_license',
				);

				$message = sprintf( __( 'Please <a href="%s">enter and activate</a> your license key for AffiliateWP to enable automatic updates.', 'affiliate-wp' ), esc_url( affwp_admin_url( 'settings' ) ) ) . '</p>';
				$message .= '<p><a href="' . wp_nonce_url( add_query_arg( $notice_query_args ), 'affwp_dismiss_notice', 'affwp_dismiss_notice_nonce' ) . '">' . _x( 'Dismiss Notice', 'License', 'affiliate-wp' ) . '</a>';

				return $message;
			},
		) );

		if ( ! is_wp_error( $license ) && false === get_transient( 'affwp_license_notice' ) ) {

			// Base query args.
			$notice_query_args = array(
				'affwp_action' => 'dismiss_notices'
			);

			if ( is_object( $license ) ) {
				$status = $license->license;
			} else {
				$status = $license;
			}

			// Bail if there's no status.
			if ( empty( $status ) ) {
				return;
			}

			if ( 'expired' === $status ) {
				self::show_notice( 'expired_license', false === $this->display_notices );
			} elseif ( 'valid' !== $status ) {
				self::show_notice( 'invalid_license', false === $this->display_notices );
			}
		}
	}

	/**
	 * Registers settings admin notices.
	 *
	 * @since 2.4
	 */
	private function settings_notices() {
		$this->add_notice( 'settings-updated', array(
			'message' => __( 'Settings updated.', 'affiliate-wp' ),
		) );

		$this->add_notice( 'affiliates_migrated', array(
			'message' => function() {
				if ( ! class_exists( 'Affiliate_WP_Migrate_WP_Affiliate' ) ) {
					require_once AFFILIATEWP_PLUGIN_DIR . 'includes/admin/tools/class-migrate-wp-affiliate.php';
				}

				$total_affiliates = (int) Affiliate_WP_Migrate_WP_Affiliate::get_items_total( 'affwp_migrate_affiliates_total_count' );

				$message = sprintf( _n(
					'%d affiliate from WP Affiliate was added successfully.',
					'%d affiliates from WP Affiliate were added successfully',
					$total_affiliates,
					'affiliate-wp'
				), number_format_i18n( $total_affiliates ) );

				Affiliate_WP_Migrate_WP_Affiliate::clear_items_total( 'affwp_migrate_affiliates_total_count' );

				return $message;
			},
		) );

		$this->add_notice( 'affiliates_pro_migrated', array(
			'message' => function() {
				if ( ! class_exists( 'Affiliate_WP_Migrate_Affiliates_Pro' ) ) {
					require_once AFFILIATEWP_PLUGIN_DIR . 'includes/admin/tools/class-migrate-affiliates-pro.php';
				}

				$total_affiliates = (int) Affiliate_WP_Migrate_Affiliates_Pro::get_items_total( 'affwp_migrate_affiliates_pro_total_count' );

				$message = sprintf( _n(
					'%d affiliate from Affiliates Pro was added successfully.',
					'%d affiliates from Affiliates Pro were added successfully',
					$total_affiliates,
					'affiliate-wp'
				), number_format_i18n( $total_affiliates ) );

				Affiliate_WP_Migrate_Affiliates_Pro::clear_items_total( 'affwp_migrate_affiliates_pro_total_count' );

				return $message;
			},
		) );

		$this->add_notice( 'stats_recounted', array(
			'message' => __( 'Affiliate stats have been recounted!', 'affiliate-wp' ),
		) );

		$this->add_notice( 'settings-imported', array(
			'message' => __( 'Settings successfully imported', 'affiliate-wp' ),
		) );
	}

	/**
	 * Processes message data for output as admin notices.
	 *
	 * @since 2.1
	 * @since 2.4 Refactored to handle for multiple classes and for message to be a callable
	 *
	 * @param string|callable $message      Notice message.
	 * @param string|array    $class        Notice class or array of classes.
	 * @param string          $extra_output Optional. Extra output to append to the end of the message.
	 *                                      Default empty.
	 * @return string Notice markup or empty string if `$message` is empty.
	 */
	public static function prepare_message_for_output( $message, $class, $extra_output = '' ) {
		if ( ! empty( $message ) ) {
			if ( is_array( $class ) ) {
				$classes = implode( ' ', $class );
			} else {
				$classes = $class;
			}

			if ( is_callable( $message ) ) {
				$message = call_user_func( $message );
			}

			if ( ! empty( $extra_output ) ) {
				$message .= $extra_output;
			}

			$message = wpautop( $message, false );

			// wpautop() pads the end.
			$message = str_replace( "\n", '', $message );

			$output = sprintf( '<div class="%1$s">%2$s</div>',
				esc_attr( $classes ),
				$message
			);
		} else {
			$output = '';
		}

		return $output;
	}

	/**
	 * Prepares notice dismissal markup.
	 *
	 * @since 2.4
	 *
	 * @param string $notice_id Notice ID.
	 * @param string $label     Label.
	 * @return string HTML dismissal markup.
	 */
	public static function prepare_dismiss_output( $notice_id, $label ) {
		$notice_query_args = array(
			'affwp_action' => 'dismiss_notices',
			'affwp_notice' => $notice_id,
		);

		$url = wp_nonce_url( add_query_arg( $notice_query_args ), 'affwp_dismiss_notice', 'affwp_dismiss_notice_nonce' );

		return sprintf( '<p><a href="%1$s">%2$s</a></p>', esc_url( $url ), $label );
	}

	/**
	 * Dismisses admin notices when Dismiss links are clicked.
	 *
	 * @since 1.7.5
	 */
	public function dismiss_notices() {
		if( ! isset( $_GET['affwp_dismiss_notice_nonce'] ) || ! wp_verify_nonce( $_GET['affwp_dismiss_notice_nonce'], 'affwp_dismiss_notice') ) {
			wp_die( __( 'Security check failed', 'affiliate-wp' ), __( 'Error', 'affiliate-wp' ), array( 'response' => 403 ) );
		}

		if ( isset( $_GET['affwp_notice'] ) ) {

			$notice = sanitize_key( $_GET['affwp_notice'] );

			switch( $notice ) {
				case 'no_integrations':
					update_user_meta( get_current_user_id(), "_affwp_{$notice}_dismissed", 1 );
					break;
				case 'expired_license':
				case 'invalid_license':
					set_transient( 'affwp_license_notice', true, 2 * WEEK_IN_SECONDS );
					break;
				case 'payouts_service':
					set_transient( 'affwp_payouts_service_notice', true, 2 * WEEK_IN_SECONDS );
					break;
				default:
					/**
					 * Fires once a notice has been flagged for dismissal.
					 *
					 * @since 1.8 as 'affwp_dismiss_notices'
					 * @since 2.0.4 Renamed to 'affwp_dismiss_notices_default' to avoid a dynamic hook conflict.
					 *
					 * @param string $notice Notice value via $_GET['affwp_notice'].
					 */
					do_action( 'affwp_dismiss_notices_default', $notice );
					break;
			}

			wp_redirect( remove_query_arg( array( 'affwp_action', 'affwp_notice' ) ) );
			exit;
		}
	}

	/**
	 * Helper method to add a notice to the registry.
	 *
	 * @since 2.4
	 *
	 * @param string $notice_id Notice ID.
	 * @param array  $notice_args Notice attributes.
	 * @return true|WP_Error True if successful, otherwise a WP_Error object.
	 */
	public function add_notice( $notice_id, $notice_args ) {
		return self::$registry->add_notice( $notice_id, $notice_args );
	}

	/**
	 * Renders a registered admin notice.
	 *
	 * @since 2.4
	 *
	 * @see Affiliate_WP_Admin_Notices::prepare_message_for_output()
	 *
	 * @param string|callable|array $notice Notice ID, callback to generate output on the fly, or an
	 *                                      array with on-the-fly notice 'message' and 'class' keys.
	 *                                      If passing a callable, the method assumes you've handled
	 *                                      preparation of the message on your own.
	 *                                      See {@see Affiliate_WP_Admin_Notices::prepare_message_for_output()}.
	 * @param bool                  $echo   Optional. Whether to echo the notice. Default true.
	 * @return void|string Void if `$echo` is true, otherwise a string.
	 */
	public static function show_notice( $notice, $echo = true ) {
		$output = '';

		if ( is_callable( $notice ) ) {
			$output = call_user_func( $notice );
		} else {
			// If a notice ID was passed, get its attributes.
			if ( is_string( $notice ) ) {
				$notice_id = $notice;
				$notice    = self::$registry->get( $notice );

				if ( false !== $notice ) {
					$notice['notice_id'] = $notice_id;
				}
			}

			$capability = empty( $notice['capability'] ) ? 'manage_affiliates' : $notice['capability'];

			if ( false !== $notice && current_user_can( $capability )
				&& ! empty( $notice['message'] ) && ! empty( $notice['class'] )
			) {
				if ( isset( $notice['dismissible'] ) && true === $notice['dismissible'] ) {
					$label = empty( $notice['dismiss_label'] ) ? _x( 'Dismiss', 'admin notice', 'affiliate-wp' ) : $notice['dismiss_label'];

					$extra_output = self::prepare_dismiss_output( $notice_id, $label );
				} else {
					$extra_output = '';
				}

				$output .= self::prepare_message_for_output( $notice['message'], $notice['class'], $extra_output );
			}
		}

		if ( true === $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Sets the display_notices property for unit testing purposes.
	 *
	 * If set to false, notice output will be returned rather than echoed.
	 *
	 * @since 2.1
	 *
	 * @param bool $display Whether to display notice output.
	 */
	public function set_display_notices( $display ) {
		$this->display_notices = (bool) $display;
	}

	/**
	 * Helper to retrieve a single notice from the registry.
	 *
	 * @since 2.4
	 *
	 * @param string $notice_id Notice ID.
	 * @return array|false Array of notice attributes if it exists, otherwise false.
	 */
	public function get_notice( $notice_id ) {
		return self::$registry->get( $notice_id );
	}

	/**
	 * Helper to retrieve all notices from the registry.
	 *
	 * @since 2.4
	 *
	 * @param bool $keys_only Optional. Whether to retrieve the notice IDs only. Default false.
	 * @return array Array of notices and their attributes. If `$keys_only` is true, an array of notice IDs.
	 */
	public function get_all_notices( $keys_only = false ) {
		$notices = self::$registry->get_items();

		if ( false !== $notices ) {
			$notices = array_keys( $notices );
		}

		return $notices;
	}

	/**
	 * Retrieves the current instance of the notices registry.
	 *
	 * @return \AffWP\Admin\Notices_Registry
	 */
	public function get_registry() {
		return self::$registry;
	}

}
new Affiliate_WP_Admin_Notices;

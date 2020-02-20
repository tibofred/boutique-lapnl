<?php
/**
 * Plugin Name: Connector for WooCommerce and Zoho CRM
 * Description: Automatically add WooCommerce customers as contacts and/or leads in Zoho CRM.
 * Version: 1.1.0
 * Author: Potent Plugins
 * Author URI: http://potentplugins.com/?utm_source=connector-for-woocommerce-and-zoho-crm&utm_medium=link&utm_campaign=wp-plugin-author-uri
 * License: GNU General Public License version 3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */
 
/*
Connector for WooCommerce and Zoho CRM plugin
Copyright (C) 2019  Aspen Grove Studios

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.

========

Credits:

This plugin includes code based on parts of WordPress, released under GPLv2+,
licensed under GPLv3+ (see wp-license.txt in the license directory for the copyright,
license, and additional credits applicable to WordPress, and the license.txt file in
the license directory for GPLv3 text).

*/

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'pp_wczc_action_links');
function pp_wczc_action_links($links) {
	array_unshift($links, '<a href="'.esc_url(get_admin_url(null, 'admin.php?page=pp_wczc')).'">Settings</a>');
	return $links;
}
 

add_action('admin_menu', 'pp_wczc_admin_menu');
function pp_wczc_admin_menu() {
	add_submenu_page('woocommerce', 'Zoho CRM Integration', 'Zoho CRM Integration', 'manage_woocommerce', 'pp_wczc', 'pp_wczc_page');
}


function pp_wczc_page() {
	if (!class_exists('PP_Zoho_API'))
		require_once(__DIR__.'/PP_Zoho_API.class.php');
			
	// Print header
	echo('
		<div class="wrap">
			<h2>Connector for WooCommerce and Zoho CRM</h2>
	');
	
	// Check for WooCommerce
	if (!class_exists('WooCommerce')) {
		echo('<div class="error"><p>This plugin requires that WooCommerce is installed and activated.</p></div></div>');
		return;
	} else if (!function_exists('wc_get_order_types')) {
		echo('<div class="error"><p>This plugin requires WooCommerce 2.2 or higher. Please update your WooCommerce install.</p></div></div>');
		return;
	}
	
	if ( !empty($_GET['code']) ) {

		try {
			new PP_Zoho_API();
			@PP_Zoho_API::getApiToken($_GET['code']);
			delete_option('pp_wczc_zoho_api_token'); // API v1 token
			
			// Refresh all the things
			@pp_wczc_get_fields_zoho('Contacts', true);
			@pp_wczc_get_fields_zoho('Leads', true);
		} catch (Exception $ex1) {
			$hasTokenError = true;
		} catch (Error $ex2) {
			$hasTokenError = true;
		}

		
	}
	
	if (get_option('pp_wczc_zoho_api2_token', false) === false) {
		echo('<div class="error"><p>You haven\'t connected your Zoho CRM account yet.</p></div>');
	}
	
	// Handle other settings submission
	if (!empty($_POST) && check_admin_referer('pp_wczc_save_settings')) {
	
		if (empty($_POST['pp_wczc_zoho_host'])) {
			delete_option('pp_wczc_zoho_host');
		} else {
			update_option('pp_wczc_zoho_host', untrailingslashit($_POST['pp_wczc_zoho_host']));
		}
	
		if (!empty($_POST['pp_wczc_zoho_disconnect'])) {
			delete_option('pp_wczc_zoho_api2_token');
			delete_option('pp_wczc_zoho_client_id');
			delete_option('pp_wczc_zoho_client_secret');
		} else if (!empty($_POST['pp_wczc_zoho_email']) && !empty($_POST['pp_wczc_zoho_client_id']) && !empty($_POST['pp_wczc_zoho_client_secret']) ) {
			update_option('pp_wczc_zoho_email', $_POST['pp_wczc_zoho_email']);
			update_option('pp_wczc_zoho_client_id', $_POST['pp_wczc_zoho_client_id']);
			update_option('pp_wczc_zoho_client_secret', $_POST['pp_wczc_zoho_client_secret']);
		}
		
		if (empty($_POST['pp_wczc_no_ssl_verify'])) {
			delete_option('pp_wczc_no_ssl_verify');
		} else {
			update_option('pp_wczc_no_ssl_verify', 1);
		}
	
		update_option('pp_wczc_add_contacts', empty($_POST['pp_wczc_add_contacts']) ? 0 : 1);
		update_option('pp_wczc_update_contacts', empty($_POST['pp_wczc_update_contacts']) ? 0 : 1);
		update_option('pp_wczc_contacts_lead_source', empty($_POST['pp_wczc_contacts_lead_source']) ? 0 : 1);
		update_option('pp_wczc_add_leads', empty($_POST['pp_wczc_add_leads']) ? 0 : 1);
		update_option('pp_wczc_update_leads', empty($_POST['pp_wczc_update_leads']) ? 0 : 1);
		update_option('pp_wczc_leads_lead_source', empty($_POST['pp_wczc_leads_lead_source']) ? 0 : 1);
		
		echo('<div class="updated"><p>Your settings have been saved.</p></div>');
	}
	
	$hasCredentials = get_option('pp_wczc_zoho_client_secret', false) !== false;
	
	echo('<form action="" method="post" style="margin-bottom: 30px;">');
	wp_nonce_field('pp_wczc_save_settings');
	echo('<div id="poststuff">
			<div id="post-body" class="columns-2">
				<div id="post-body-content" style="position: relative;">
					<form action="#hm_sbp_table" method="post">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="pp_wczc_zoho_host_field">Step 1 - Zoho CRM URL:</label>
				</th>
				<td>
					<p style="margin-bottom: 10px;">Enter the first part of the URL that appears when you are logged in to Zoho CRM, including https://, up to (not including) the first slash.</p>
					<p style="margin-bottom: 10px;">If you change this setting, please click the <em>Save Settings</em> button below before proceeding to steps 2 and 3.</p>
					<input type="url" id="pp_wczc_zoho_host_field" name="pp_wczc_zoho_host" value="'.esc_attr( get_option('pp_wczc_zoho_host', 'https://crm.zoho.com') ).'" required>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label>Step 2 - Zoho CRM Account:</label>
				</th>
				<td>
	');
	if ($hasCredentials) {
		echo('
			<p style="margin-bottom: 10px;">
				You have already entered Zoho CRM API credentials. If you would like to enter new credentials, please check the box below.
			</p>
			<div>
				<label><input type="checkbox" name="pp_wczc_zoho_disconnect" value="1" /> Disconnect Zoho CRM account</label>
			</div>
		');
	} else {


		echo('
				
				<p>Go to the <a href="'.esc_url( PP_Zoho_API::getRegionalUrl('https://accounts.zoho.com/developerconsole') ).'" target="_blank">Zoho Developer Console</a> and click <em>Add Client ID</em> to create a client ID and client secret for this site. Enter the following settings for the client ID:</p>
				<ul>
					<li><strong>Client Name:</strong> Any valid value you can use to identify this client ID</li>
					<li><strong>Client Domain:</strong> Your site\'s domain/subdomain name</li>
					<li><strong>Authorized redirect URIs:</strong> '.esc_url( admin_url('admin.php?page=pp_wczc') ).'</li>
					<li><strong>Client Type:</strong> WEB Based</li>
				</ul>
				<p style="margin-bottom: 10px;">After creating the client ID, enter your account email address, the client ID, and the client secret below.</p>
				<div style="margin-bottom: 10px;">
					<label style="display: inline-block; width: 160px;">Account Email:</label>
					<input type="text" name="pp_wczc_zoho_email" value="'.esc_attr(get_option('pp_wczc_zoho_email')).'" />
				</div>
				<div style="margin-bottom: 10px;">
					<label style="display: inline-block; width: 160px;">Client ID:</label>
					<input type="text" name="pp_wczc_zoho_client_id" value="'.esc_attr(get_option('pp_wczc_zoho_client_id')).'" />
				</div>
				<div style="margin-bottom: 10px;">
					<label style="display: inline-block; width: 160px;">Client Secret:</label>
					<input type="password" name="pp_wczc_zoho_client_secret" />
				</div>
		');
		
	}
	echo('
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="pp_wczc_zoho_host_field">Step 3 - Connect:</label>
			</th>
			<td>');

	if ($hasCredentials) {
		
		if ( !empty($hasTokenError) ) {
			echo('
				<p style="margin-bottom: 10px;">
					<strong>Something went wrong while connecting the plugin to Zoho CRM.</strong>
				</p>
			');
		}


		if (get_option('pp_wczc_zoho_api2_token', false) !== false) {
			echo('<p style="margin-bottom: 10px;">You have already connected this plugin to Zoho CRM. You can re-establish the connection using the button below.</p>');
		}
		echo('<p style="margin-bottom: 10px;"><a href="'.esc_attr( PP_Zoho_API::getAuthUrl() ).'" class="button-primary">Connect to Zoho CRM</a></p>');
	} else {
		echo('<p style="margin-bottom: 10px;">Please complete step 2 and click <em>Save Settings</em> below to continue.</p>');
	}
	echo('
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label>Connection Settings:</label>
			</th>
			<td>
				<div>
					<label>
						<input type="checkbox" name="pp_wczc_no_ssl_verify"'.(get_option('pp_wczc_no_ssl_verify') ? ' checked="checked"' : '').' /> Disable SSL certificate verification (not recommended)
					</label>
					<p class="description">Disabling verification is <strong>not</strong> recommended for security reasons. If the connection to Zoho CRM is not working, you may be able to fix the problem by selecting this option; however, if at all possible, it would be better to fix the root cause as described <a href="https://snippets.webaware.com.au/howto/stop-turning-off-curlopt_ssl_verifypeer-and-fix-your-php-config/" target="_blank">here</a> (if PHP cURL is enabled on your server) or by setting one of the options described <a href="http://php.net/manual/en/openssl.configuration.php" target="_blank">here</a> to an up to date Certificate Authority file (if PHP cURL is not enabled on your server).</p>
				</div>
			</td>
		</tr>
			<tr valign="top">
				<th scope="row">
					<label>Contacts:</label>
				</th>
				<td>
					<div style="margin-bottom: 5px;">
						<label>
							<input type="checkbox" id="pp_wczc_add_contacts" name="pp_wczc_add_contacts"'.(get_option('pp_wczc_add_contacts', 1) ? ' checked="checked"' : '').' />
							Add new WooCommerce customers as Zoho CRM contacts
						</label>
					</div>
					<div style="margin-bottom: 5px; margin-left: 20px;">
						<label>
							<input type="checkbox" id="pp_wczc_update_contacts" name="pp_wczc_update_contacts"'.(get_option('pp_wczc_update_contacts', 0) ? ' checked="checked"' : '').' />
							If a contact already exists for the customer, update it
						</label>
					</div>
					<div style="margin-bottom: 5px; margin-left: 20px;">
						<label>
							<input type="checkbox" id="pp_wczc_contacts_lead_source" name="pp_wczc_contacts_lead_source"'.(get_option('pp_wczc_contacts_lead_source', 0) ? ' checked="checked"' : '').' />
							Set Lead Source field (will overwrite existing value)
						</label>
					</div>
					<div style="margin-left: 20px;">
						<label>
							<input type="checkbox" disabled="disabled" />
							Add a note to the contact with order details (names and quantities of products ordered) <sup style="color: #f00;">PRO</sup>
						</label>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label>Leads:</label>
				</th>
				<td>
					<div style="margin-bottom: 5px;">
						<label>
							<input type="checkbox" id="pp_wczc_add_leads" name="pp_wczc_add_leads"'.(get_option('pp_wczc_add_leads', 0) ? ' checked="checked"' : '').' />
							Add new WooCommerce customers as Zoho CRM leads
						</label>
					</div>
					<div style="margin-bottom: 5px; margin-left: 20px;">
						<label>
							<input type="checkbox" id="pp_wczc_update_leads" name="pp_wczc_update_leads"'.(get_option('pp_wczc_update_leads', 0) ? ' checked="checked"' : '').' />
							If a lead already exists for the customer, update it
						</label>
					</div>
					<div style="margin-bottom: 5px; margin-left: 20px;">
						<label>
							<input type="checkbox" id="pp_wczc_leads_lead_source" name="pp_wczc_leads_lead_source"'.(get_option('pp_wczc_leads_lead_source', 0) ? ' checked="checked"' : '').' />
							Set Lead Source field (will overwrite existing value)
						</label>
					</div>
					<div style="margin-left: 20px;">
						<label>
							<input type="checkbox" disabled="disabled" />
							Add a note to the lead with order details (names and quantities of products ordered) <sup style="color: #f00;">PRO</sup>
						</label>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label>Potentials:</label>
				</th>
				<td>
					<div>
						<label>
							<input type="checkbox" disabled="disabled" />
							Create a potential for the order <sup style="color: #f00;">PRO</sup>
						</label>
						<p class="description">If the option to add the customer as a contact is enabled, the potential will be associated with that contact.<br />The account name will be the billing company (if specified) or the billing name.</p>
					</div>
				</td>
			</tr>
		</table>
		<button type="submit" class="button-primary">Save Settings</button>
		</form>
		</div> <!-- /post-body-content -->
			
		<div id="postbox-container-1" class="postbox-container">
			<div id="side-sortables" class="meta-box-sortables">
			
				<div class="postbox">
					<h2><a href="https://potentplugins.com/downloads/woocommerce-zoho-crm-connector-pro-plugin/?utm_source=connector-for-woocommerce-and-zoho-crm&amp;utm_medium=link&amp;utm_campaign=wp-plugin-upgrade-link" target="_blank">Upgrade to Pro</a></h2>
					<div class="inside">
						<p><strong>Upgrade to <a href="https://potentplugins.com/downloads/woocommerce-zoho-crm-connector-pro-plugin/?utm_source=connector-for-woocommerce-and-zoho-crm&amp;utm_medium=link&amp;utm_campaign=wp-plugin-upgrade-link" target="_blank">WooCommerce and Zoho CRM Connector Pro</a> for the following additional features:</strong></p>
						<ul style="list-style-type: disc; padding-left: 1.5em;">
<li>Add order details (product names and quantities) as a note to the contact and/or lead corresponding to the customer.</li>
<li>Create a potential based on the order and linked to the customer’s contact record (if one was found or created).</li>
<li>Manually send individual orders to Zoho CRM from the Order Actions menu on the Edit Order page.</li>
<li>Manually send orders to Zoho CRM individually or in bulk from the order list.</li>
						</ul>
						<p>
							<a href="https://potentplugins.com/downloads/woocommerce-zoho-crm-connector-pro-plugin/?utm_source=connector-for-woocommerce-and-zoho-crm&amp;utm_medium=link&amp;utm_campaign=wp-plugin-upgrade-link" target="_blank">Buy Now &gt;</a>
						</p>
					</div>
				</div>
				
			</div> <!-- /side-sortables-->
		</div><!-- /postbox-container-1 -->
		
		</div> <!-- /post-body -->
		<br class="clear" />
		</div> <!-- /poststuff -->
		<script>
			jQuery(\'#pp_wczc_add_contacts\').change(function() {
				if (jQuery(this).is(\':checked\')) {
					jQuery(\'#pp_wczc_update_contacts\').attr(\'disabled\', false);
				} else {
					jQuery(\'#pp_wczc_update_contacts\').attr(\'checked\', false).attr(\'disabled\', true);
				}
			});
			jQuery(\'#pp_wczc_add_contacts\').change();
			jQuery(\'#pp_wczc_add_leads\').change(function() {
				if (jQuery(this).is(\':checked\')) {
					jQuery(\'#pp_wczc_update_leads\').attr(\'disabled\', false);
				} else {
					jQuery(\'#pp_wczc_update_leads\').attr(\'checked\', false).attr(\'disabled\', true);
				}
			});
			jQuery(\'#pp_wczc_add_leads\').change();
		</script>
	');
	$potent_slug = 'connector-for-woocommerce-and-zoho-crm';
	include(__DIR__.'/plugin-credit.php');
	echo('</div>'); // /wrap
}

add_action('woocommerce_checkout_update_order_meta', 'pp_wczc_process_order');
function pp_wczc_process_order($orderId) {
	try { // this try catch block created from scratch

		global $woocommerce;
		$zohoApiToken = get_option('pp_wczc_zoho_api2_token');
		if (empty($zohoApiToken))
			return;
		$order = $woocommerce->order_factory->get_order($orderId);
		
		if (empty($order))
			return;
		
		if (!class_exists('PP_Zoho_API'))
			require_once(__DIR__.'/PP_Zoho_API.class.php');
		$zoho = new PP_Zoho_API($zohoApiToken);
		
		if (get_option('pp_wczc_add_contacts', 1)) {
			$updateContacts = get_option('pp_wczc_update_contacts', 0);
			$contactData = array(
				'First Name' => $order->billing_first_name,
				'Last Name' => $order->billing_last_name,
				'Phone' => $order->billing_phone,
				'Email' => $order->billing_email,
				'Mailing Street' => $order->billing_address_1.(empty($order->billing_address_2) ? '' : ' '.$order->billing_address_2),
				'Mailing City' => $order->billing_city,
				'Mailing State' => $order->billing_state,
				'Mailing Zip' => $order->billing_postcode,
				'Mailing Country' => $order->billing_country
			);
			if (get_option('pp_wczc_contacts_lead_source', 0))
				$contactData['Lead Source'] = 'OnlineStore';
			$zoho->addContact($contactData, !empty($updateContacts));
		}
		
		if (get_option('pp_wczc_add_leads', 0)) {
			$updateLeads = get_option('pp_wczc_update_leads', 0);
			$leadData = array(
				'First Name' => $order->billing_first_name,
				'Last Name' => $order->billing_last_name,
				'Company' => (empty($order->billing_company) ? 'Individual' : $order->billing_company),
				'Phone' => $order->billing_phone,
				'Email' => $order->billing_email,
				'Street' => $order->billing_address_1.(empty($order->billing_address_2) ? '' : ' '.$order->billing_address_2),
				'City' => $order->billing_city,
				'State' => $order->billing_state,
				'Zip Code' => $order->billing_postcode,
				'Country' => $order->billing_country
			);
			if (get_option('pp_wczc_leads_lead_source', 0))
				$leadData['Lead Source'] = 'OnlineStore';
			$zoho->addLead($leadData, !empty($updateLeads));
		}
		
	} catch (Exception $ex1) {
	} catch (Error $ex2) {
	}
	
}

function pp_wczc_get_fields_zoho($type, $refresh=false) {
	$fields = ($refresh ? false : get_transient('pp_wczc_zoho_fields_'.$type));
	if ($fields === false) {
	
		if (!class_exists('PP_Zoho_API'))
			require_once(__DIR__.'/PP_Zoho_API.class.php');
			
		$zohoApiToken = get_option('pp_wczc_zoho_api2_token');
		if (empty($zohoApiToken))
			return false;
		
		$zoho = new PP_Zoho_API($zohoApiToken);
		$fields = $zoho->getModuleFields($type);
		
		set_transient('pp_wczc_zoho_fields_'.$type, $fields, 86400);
	}
	return $fields;
}

/*
Review/donate notice - copied from hm_wcdon

Parts of the following code (here to end of file) are based on WordPress; see credits comment near the top of this file for copyright and licensing information
Last modified by Jonathan Hall 2019-12-13
*/


if (is_admin() && get_option('pp_wczc_up_notice_hidden') != 1) {
	add_action('admin_notices', 'pp_wczc_rd_notice');
	add_action('wp_ajax_pp_wczc_rd_notice_hide', 'pp_wczc_rd_notice_hide');
}
function pp_wczc_rd_notice() {
	$pre = 'pp_wczc';
	if (current_user_can('manage_woocommerce'))
		echo('
			<div id="'.$pre.'_rd_notice" class="notice notice-warning is-dismissible">
				<p>
					<strong>Connector for WooCommerce and Zoho CRM</strong> is using version 1 of the Zoho CRM API, which will be discontinued on December 31, 2019.
					An update is coming soon to integrate version 2 of the API with the plugin. Please be sure to update the plugin before December 31, 2019 and complete the new authentication process on the plugin settings page.
				</p>
			</div>
			<script>jQuery(document).ready(function($){$(\'#'.$pre.'_rd_notice\').on(\'click\', \'.notice-dismiss\', function(){jQuery.post(ajaxurl, {action:\'pp_wczc_rd_notice_hide\'})});});</script>
		');
}
function pp_wczc_rd_notice_hide() {
	$pre = 'pp_wczc';
	update_option($pre.'_up_notice_hidden', 1);
}

?>

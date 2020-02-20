<?php
	
	if ( ! class_exists( 'ben_plug_restrict_usernames_emails_characters_mu_bp' ) ) :
	
	class ben_plug_restrict_usernames_emails_characters_mu_bp extends ben_plug_restrict_usernames_emails_characters_CHARS {
		public function __construct(  ) {
			parent::__construct();
		}
		
		function wpmubp__ben( $result ){
		    global $wpdb;
			
			$dis_all_symbs = $this->options('all_symbs');
			
			$lang = $this->options('lang');
			
			if (! is_wp_error($result['errors'])) {
				return $result;
			}
			
			$__username = !$this->is__signup() && $this->mubp() && !$this->options('start_end_space') ? 'orig_username' : 'user_name';
			//$__username = $this->mu() ? 'orig_username' : 'user_name';
			$username = $result[$__username];
			$email = $result['user_email'];
			
			$allow = $this->options('p_num');
			/*
				$valid_name = $this->func_illegal_user_logins( false, $username );
				$valid_num = $this->func_limit_username_NUM( false, $username );
				$valid_space = $this->func_no_space_registration( false, $username );
				$valid_invalidname = $this->func_spc_cars_user_logins( false, $username );
			*/
			
			$this->func_validation( false, $username );
			
			//$valid_email = $this->func_limit_username_EMAIL( false, false, $email );
			$this->user__email( $email );
			
			$original_error = $result['errors'];
			$new_error = new WP_Error();
			//$names_limit_partial = $this->opts['option']['names_limit_partial'];
			$min_length = $this->options('min_length');
			$max_length = $this->options('max_length');
			$p_space = $this->options('p_space');
			
			$er_name = $this->options_Tw('err_mp_names_limit') ? $this->options_Tw('err_mp_names_limit'): __( 'This username is not allowed, choose another please.','restrict-usernames-emails-characters' );	 
			$er_min = $this->options_Tw('err_mp_min_length') ? __($this->options_Tw('err_mp_min_length'),'restrict-usernames-emails-characters') : __( "Username must be at least %min% characters.",'restrict-usernames-emails-characters' ) ;
			$filter_err_min_length = apply_filters( 'err_mp_min_length_mubp_BENrueeg_RUE',$er_min );
			$er_max = $this->options_Tw('err_mp_min_length') ? __($this->options_Tw('err_mp_max_length'),'restrict-usernames-emails-characters') : __( "Username may not be longer than %max% characters.",'restrict-usernames-emails-characters' ) ;
			$filter_err_max_length = apply_filters( 'err_mp_max_length_mubp_BENrueeg_RUE',$er_max );
			$er_digits_less = $this->options_Tw('err_mp_digits_less') ? $this->options_Tw('err_mp_digits_less'): __( "The digits must be less than the characters in username.",'restrict-usernames-emails-characters' );	 
			$er_space = $this->options_Tw('err_mp_spaces') ? $this->options_Tw('err_mp_spaces'): __( "It's not allowed to use spaces in username.",'restrict-usernames-emails-characters' );	 
			$er_just_num = $this->options_Tw('err_mp_names_num') ? $this->options_Tw('err_mp_names_num'): __( "You can't register with just numbers.",'restrict-usernames-emails-characters' );	 
			$er_illegal_name = $this->options_Tw('err_mp_spc_cars') ? $this->options_Tw('err_mp_spc_cars'): __( 'This username is invalid because it uses illegal characters. Please enter a valid username.','restrict-usernames-emails-characters' );	 
			$er_name_not_email = $this->options_Tw('err_mp_name_not_email') ? $this->options_Tw('err_mp_name_not_email'): __( 'Do not allow usernames that are email addresses.','restrict-usernames-emails-characters' );	 
			$er_uppercase = $this->options_Tw('err_mp_uppercase') ? $this->options_Tw('err_mp_uppercase'): __( 'No uppercase (A-Z) in username.','restrict-usernames-emails-characters' );	 
			$er_start_end_space = $this->options_Tw('err_mp_start_end_space') ? $this->options_Tw('err_mp_start_end_space'): __( 'is not allowed to use multi whitespace or whitespace at the beginning or the end of the username.','restrict-usernames-emails-characters' );
			$er_username_empty = $this->options_Tw('err_mp_empty') ? $this->options_Tw('err_mp_empty'): __( 'Please enter a username.','restrict-usernames-emails-characters' );	 
			$er_exist_login = $this->options_Tw('err_mp_exist_login') ? $this->options_Tw('err_mp_exist_login'): __( 'This username is already registered. Please choose another one.','restrict-usernames-emails-characters' );	 
			
			$er_email = $this->options_Tw('err_mp_emails_limit') ? $this->options_Tw('err_mp_emails_limit'): __( 'This email is not allowed, choose another please.','restrict-usernames-emails-characters' );	 
			
			$pr = $this->options_Tw('err_mp_partial') ? __($this->options_Tw('err_mp_partial'),'restrict-usernames-emails-characters') : __( "This part <font color='#FF0000'>%part%</font> is not allowed in username.",'restrict-usernames-emails-characters' ) ;
			
			if ( $this->ben_username_empty($username) )
			$new_error->add('user_name', __($er_username_empty,'restrict-usernames-emails-characters'));
			
			if ( username_exists( $username ) && $this->func_space_s_e_m($username) || $this->func_s($username) && !$this->ben_username_empty($username) ) {
				if (!$this->can_create())
				$new_error->add('user_name', __($er_start_end_space, 'restrict-usernames-emails-characters'));
			}
			
			$replogin = $this->func_replogin($username);
			if ( $this->ben_username_exists($username) && !$this->func_space_s_e_m($username) || $this->ben_username_exists($replogin) && !$this->func_space_s_e_m($username) ) 
			$new_error->add('user_name',  __($er_exist_login,'restrict-usernames-emails-characters'));
			
			$signup = $wpdb->get_row( $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'signups WHERE user_login = %s', $username) );
			if ( $signup != null ) {
				$registered_at =  mysql2date('U', $signup->registered);
				$now = current_time( 'timestamp', true );
				$diff = $now - $registered_at;
				
				if ( $diff <= 2 * DAY_IN_SECONDS )
				$new_error->add('user_name', apply_filters( 'username_reserved_filter_BENrueeg_RUE',__('That username is currently reserved but may be available in a couple of days.') ));
			}
			
			if ( !validate_username( $username ) && !$this->ben_username_empty($username) || $this->valid_charts && !$this->can_create() || $this->invalid_chars_allow && !$this->can_create() )
			$new_error->add('user_name', __($er_illegal_name, 'restrict-usernames-emails-characters'));
			
			if ( !username_exists( $username ) && !$this->ben_username_empty($username) ){
				if ( $this->func_space_s_e_m($username) && !$this->can_create() )
				$new_error->add('user_name', __($er_start_end_space, 'restrict-usernames-emails-characters'));
			}
			
			/*
				$pp = false;
				$least_mg = $this->is__signup() ? __( 'Username must be at least 4 characters.' ) : __( 'Username must be at least 4 characters', 'buddypress' );
				if ( mb_strlen( $username ) < 4 && empty($min_length) && !$this->ben_username_empty($username) ) {
				$pp = true;	  
				$new_error->add('user_name', $least_mg);
				}
			*/
			/*
				if ( mb_strlen( $username ) < 4 && empty($min_length) && !$this->func_space_s_e_m($username) && !$this->ben_username_empty($username) ) {
				$least_mg = $this->is__signup() ? __( 'Username must be at least 4 characters.' ) : __( 'Username must be at least 4 characters', 'buddypress' );
				if ($this->mubp()) {		  
				$new_error->add('user_name', $least_mg);
				} else if ($this->mu()) {
				$new_error->add('user_name', __( 'Username must be at least 4 characters.' ));
				} else {
				$new_error->add('user_name', __( 'Username must be at least 4 characters', 'buddypress' ));
				} 
				}
			*/
			if ( $this->valid_partial ) 
			$new_error->add( 'user_name', str_replace("%part%", $this->func__part($username), $pr) );
			
			if ( $this->name_not__email )
			$new_error->add('user_name', __($er_name_not_email, 'restrict-usernames-emails-characters'));
			
			if ( $this->invalid_names )
			$new_error->add('user_name', __($er_name, 'restrict-usernames-emails-characters'));
			
			if ( $this->length_min && !$this->ben_username_empty($username) )
			$new_error->add('user_name', str_replace("%min%", $min_length, $filter_err_min_length));
			
			if ( $this->length_max )
			$new_error->add('user_name', str_replace("%max%", $max_length, $filter_err_max_length));
			
			if ( $this->valid_num_less && !preg_match( '/^\+?\d+$/', $username ) )
			$new_error->add('user_name', __($er_digits_less, 'restrict-usernames-emails-characters'));
			
			if ( $this->space ) {
				$new_error->add('user_name', __($er_space, 'restrict-usernames-emails-characters'));
				} elseif (!$this->space && preg_match('/ /', $username)) {
				$this->_unset( $original_error,'user_name' );
			} 
			
			if ( $this->uppercase_names )
			$new_error->add('user_name', __($er_uppercase, 'restrict-usernames-emails-characters'));
			
			if ( $this->restricted_emails || $this->restricted_domain_emails )
			$new_error->add('user_email', __($er_email, 'restrict-usernames-emails-characters'));
			
			if ($this->bp()) :
			
			$match_ = array();
			preg_match( '/[0-9]*/', $username, $match_ );
			if ( $match_[0] == $username && $this->options('p_num') && !$this->mubp() ||
			preg_match( '/^\+?\d+$/', $username ) && $this->options('p_num') && !$this->mubp() ) {
				if (!$this->can_create())
				$new_error->add('user_name', __($er_just_num, 'restrict-usernames-emails-characters'));
				} else if ( $match_[0] == $username && !$this->options('p_num') ) {
				$this->_unset( $original_error,'user_name' );
			}
			
			/*
				if ( mb_strlen( $username ) < 4 && empty($min_length) ) {
				$least_mg = $this->is__signup() ? __( 'Username must be at least 4 characters.' ) : __( 'Username must be at least 4 characters', 'buddypress' );
				if ($this->mubp())		  
				$new_error->add('user_name', $least_mg);
				else
				$new_error->add('user_name', __('Please enter a username', 'buddypress'));
				}
			*/
			
			if ( false !== strpos( $username, '_' ) && !$this->mubp() )
			$this->_unset( $original_error,'user_name' );
			
			/*
				$list_chars = array_map('trim', explode(PHP_EOL, $this->opts['option']['allow_spc_cars']));
				$list__chars = implode($list_chars);
				if ( preg_match('/[+]/', $username ) && false === strpos( ' ' . $list__chars, '+' ) ) {
				$new_error->add('user_name', __($er_illegal_name));
				} 
			*/
			
			endif; // $this->bp()
			
			$r_ = $this->lang__mu();
			
			if ($lang == 'default_lang' && !$dis_all_symbs) {
				$pattern = $r_[0];
				} else if ($lang == 'default_lang' && $dis_all_symbs) {
				$pattern = $r_[1];
				} else if ($lang == 'all_lang' && !$dis_all_symbs) {
				$pattern = $r_[2];
				} else if ($lang == 'all_lang' && $dis_all_symbs) {
				$pattern = $r_[3];
				} else if ($lang == 'arab_lang' && !$dis_all_symbs) {
				$pattern = $r_[4];
				} else if ($lang == 'arab_lang' && $dis_all_symbs) {
				$pattern = $r_[5];
				} else if ($lang == 'cyr_lang' && !$dis_all_symbs) {
				$pattern = $r_[6];
				} else if ($lang == 'cyr_lang' && $dis_all_symbs) {
				$pattern = $r_[7];
				} else if ($lang == 'arab_cyr_lang' && !$dis_all_symbs) {
				$pattern = $r_[8];
				} else if ($lang == 'arab_cyr_lang' && $dis_all_symbs) {
				$pattern = $r_[9];
				} else if ($lang == 'select_lang' && !$dis_all_symbs) {
				$pattern = $r_[10];
				} else if ($lang == 'select_lang' && $dis_all_symbs) {
				$pattern = $r_[11];
			}
			
			preg_match( $pattern, $username, $match );
			
			$matchCount = preg_match( $pattern, $username, $match );
			$match__s = $matchCount > 0 ? $match[0] : '' ;
			
			foreach( $original_error->get_error_codes() as $code ){
				$get_messages = $result['errors']->get_error_messages($code);
				foreach(  $get_messages as $message ){
					if ( $code != 'user_email' && $this->mu() && !preg_match( '/^\+?\d+$/', $username ) ) {
						
						if ( $username != $match__s ) {
							$ok_chars = __($er_illegal_name, 'restrict-usernames-emails-characters');
							$new_error->add('user_name', $ok_chars);
							} elseif ( preg_match( '/[^a-z0-9]/', $username ) || strlen( $username ) < 4 || strlen( $username ) > 60 ) {
							$this->_unset( $original_error,'user_name' );
							} else {
							$new_error->add($code, $message);
						}
						} else if ( $code == 'user_email' ) {
						$new_error->add($code, $message);	
					} 
					
				}
			}
			
			$match_ = array();
			preg_match( '/[0-9]*/', $username, $match_ );
			if ( $match_[0] == $username && !$this->options('p_num') && $this->mu() ||
			preg_match( '/^[0 -9]+$/i', $username ) && !$this->options('p_num') && $this->mu() && $this->options('p_space') == 'on' ||
			preg_match( '/^\+?\d+$/', $username ) && !$this->options('p_num') && $this->mu() ) {
				if (!$this->can_create())
				$new_error->add('user_name', __($er_just_num, 'restrict-usernames-emails-characters'));
			} 
			
			if ( $this->ben_username_empty($username) && $this->mu() )
			$new_error->add('user_name',  __( 'Please enter a username.' ));
			
			$result['errors'] = $new_error;
			
			return $result;
		}
		
		/*  
			prevent wordpress remove space from user name when is inserted in database
		*/
		function signup__finished( $user, $user_email, $key, $meta ){
			global $wpdb;
			
			if ( ! $this->mu() || $this->options('p_space') != 'on' || $this->mubp() && !$this->is__signup() ) return;
			if ( !preg_match('/ /', $_POST['user_name']) ) return;
			
			$wpdb->update( $wpdb->prefix . 'signups', array( 'user_login' => $_POST['user_name'] ), array( 'user_email' => $user_email ) );
		}
		
		function signupfinished( $meta, $update, $id ) {
			global $wpdb;
			
			if( $update || ! $this->mu() || $this->options('p_space') != 'on' ) return $meta;
			$is = $wpdb->get_var( 'SELECT user_login FROM ' . $wpdb->prefix . 'signups WHERE user_email = "' . $meta['user_email'] . '" ');	
			if( $is == false || !preg_match('/ /', $is) ) return $meta;
			$str = utf8_encode(rawurlencode(str_replace(' ', '-', $is)));
			$meta['user_login'] = $is;
			$meta['user_nicename'] = strtolower($str);
			$meta['display_name'] = $is;
			
			return $meta;
		}		
		
		function signup_meta( $meta, $user, $update ) {
			
			if( $update || ! $this->mu() || $this->options('p_space') != 'on' ) return $meta;
			if ( !preg_match('/ /', $user->user_login) ) return $meta;
			
			$meta['nickname'] = $user->user_login;
			return $meta;
		}
		
		/*
			function signup___finished($user_id){
			global $wpdb;
			
			if ( ! $this->mu() || $this->options('p_space') != 'on' ) return;
			if (! strpos( $_SERVER[ 'PHP_SELF' ], apply_filters( 'wp_activate_mu_filter_BENrueeg_RUE','wp-activate.php' ) )) return;
			
			$is = $wpdb->get_var( 'SELECT user_login FROM ' . $wpdb->prefix . 'users WHERE ID = "' . $user_id . '" ');
			$update = $wpdb->update( $wpdb->usermeta, array( 'meta_value' => $is ), array( 'user_id' => $user_id, 'meta_key' => 'nickname' ) );
			return $update;
			}
		*/
		
		function bp_reg() {
			global $wpdb;
			
			if ( ! $this->bp() ) return;
			$username = $_POST['signup_username'];
			if ( $this->options('p_space') != 'on' && $this->mubp() || $this->options('p_space') == 'on' && !$this->mubp() ) return;
			if ( !preg_match('/ /', $username) ) return;
			
			//$str = utf8_encode(rawurlencode(str_replace(' ', '-', $_POST['signup_username'])));
			$meta = $wpdb->update( $wpdb->prefix . 'signups', array( 'user_login' => $_POST['signup_username'] ), array( 'user_email' => $_POST['signup_email'] ) );
			$meta .= $wpdb->update( $wpdb->prefix . 'users', array( 'user_login' => $_POST['signup_username'], 'user_nicename' => $_POST['signup_username'] ), array( 'user_email' => $_POST['signup_email'] ) );
			if ( bp_registration_needs_activation() )
			return $meta;
			return;
		}
		
		function head_reg() {
			if ( ! $this->bp() || ($this->bp() && $this->is__signup()) ) return;
			
			$signup_username = apply_filters( 'benrueeg_rue_filter_bp_signup_username', $this->signup_username );
			$signup_name = apply_filters( 'benrueeg_rue_filter_bp_signup_name', $this->signup_name );
			$signup_name_display = $this->bp_field(true, false);
			$signup_section_display = $this->bp_field(false, true);
			
			echo"
			<style type='text/css'>
			.editfield.field_1 { $signup_name_display }
			#profile-details-section { $signup_section_display }
			</style>
			
			<script type='text/javascript'>
			var url = document.location.href;
			jQuery(document).ready(function($) {
			//copy profile username to account name during registration
			//if (url.indexOf('register/') >= 0) {
			if (BENrueeg_RUE_js_Params.is_field_name_removed) {
			$('$signup_username').blur(function(){
			$('$signup_name').val($('$signup_username').val());
			});
			}
			//}
			});
			</script>
			";
		}
		
		
	}
	
	endif;

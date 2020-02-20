<?php
	
	if ( ! class_exists( 'ben_plug_restrict_usernames_emails_characters_errors' ) ) :
	
	class ben_plug_restrict_usernames_emails_characters_errors extends ben_plug_restrict_usernames_emails_characters_mu_bp {
		public function __construct(  ) {
			parent::__construct();
		}
		
		public function func_errors( $errors, $login ) {
			
			$list_chars_a = array_map('trim', explode(PHP_EOL, $this->options('allow_spc_cars')));
			$list_chars_allow = implode($list_chars_a);
			
			if ( $this->invalid || $this->invalid_chars_allow || $this->valid_charts ){
				if ($this->options_Tw('err_spc_cars')) { 	
					$errors->errors['invalid_username'][0] = __($this->options_Tw('err_spc_cars'),'restrict-usernames-emails-characters'); 
					} else {
					$errors->errors['invalid_username'][0] = __('<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.','restrict-usernames-emails-characters');
				}
			}
			
			if ( $this->B___name ) {
				if ($this->options_Tw('err_empty')) 
				$errors->errors['invalid_username'][0] = __($this->options_Tw('err_empty'),'restrict-usernames-emails-characters');
				else
				$errors->errors['invalid_username'][0] = __('<strong>ERROR</strong>: Please enter a username.','restrict-usernames-emails-characters');
			}
			
			if ( $this->space_start_end_multi ){
				if ($this->options_Tw('err_start_end_space')) 	
				$errors->errors['invalid_username'][0] = __($this->options_Tw('err_start_end_space'),'restrict-usernames-emails-characters');
				else
				$errors->errors['invalid_username'][0] = __('<strong>ERROR</strong>: is not allowed to use multi whitespace or whitespace at the beginning or the end of the username.','restrict-usernames-emails-characters');
			}
			
			if ( $this->exist__login ) {
				if ($this->options_Tw('err_exist_login')) 
				$errors->errors['invalid_username'][0] = __($this->options_Tw('err_exist_login'),'restrict-usernames-emails-characters');
				else
				$errors->errors['invalid_username'][0] = __('<strong>ERROR</strong>: This username is already registered. Please choose another one.','restrict-usernames-emails-characters');
			}
			
			if ( $this->space ){
				if ($this->options_Tw('err_spaces')) 	
				$errors->errors['invalid_username'][0] = __($this->options_Tw('err_spaces'),'restrict-usernames-emails-characters');
				else
				$errors->errors['invalid_username'][0] = __("<strong>ERROR</strong>: It's not allowed to use spaces in username.",'restrict-usernames-emails-characters');
			} 
			
			if ( $this->invalid_names ){
				if ($this->options_Tw('err_names_limit')) { 	
					$errors->errors['invalid_username'][0] = __($this->options_Tw('err_names_limit'),'restrict-usernames-emails-characters'); 
					} else {
					$errors->errors['invalid_username'][0] = __('<strong>ERROR</strong>: This username is not allowed, choose another please.','restrict-usernames-emails-characters'); 
				}
			}
			
			if ( $this->uppercase_names ) {
				if ($this->options_Tw('err_uppercase')) 
				$errors->errors['invalid_username'][0] = __($this->options_Tw('err_uppercase'),'restrict-usernames-emails-characters');
				else
				$errors->errors['invalid_username'][0] = __('<strong>ERROR</strong>: No uppercase (A-Z) in username.','restrict-usernames-emails-characters');
			}
			
			if ( $this->name_not__email ) {
				if ($this->options_Tw('err_name_not_email')) 
				$errors->errors['invalid_username'][0] = __($this->options_Tw('err_name_not_email'),'restrict-usernames-emails-characters');
				else
				$errors->errors['invalid_username'][0] = __('<strong>ERROR</strong>: Do not allow usernames that are email addresses.', 'restrict-usernames-emails-characters');
			}
			
			$pr = $this->options_Tw('err_partial') ? __($this->options_Tw('err_partial'),'restrict-usernames-emails-characters') : __( "<strong>ERROR</strong>: This part <font color='#FF0000'>%part%</font> is not allowed in username.",'restrict-usernames-emails-characters' ) ;
			$u_login = apply_filters( 'benrueeg_rue_filter_field_user_login', 'user_login');
			if ( $this->valid_partial )
			$errors->errors['invalid_username'][0] = str_replace("%part%", $this->func__part($_POST[$u_login]), $pr); 
			
			
			$min = $this->options_Tw('err_min_length') ? __($this->options_Tw('err_min_length'),'restrict-usernames-emails-characters') : __( "<strong>ERROR</strong>: Username must be at least %min% characters.",'restrict-usernames-emails-characters' ) ;
			$filter_err_min_length_BENrueeg_RUE = apply_filters( 'err_min_length_BENrueeg_RUE',$min );
			$min_length = $this->options('min_length');
			if( $this->length_min ) {
				$errors->errors['invalid_username'][0] = str_replace("%min%", $min_length, do_shortcode($filter_err_min_length_BENrueeg_RUE));
			}
			
			$max = $this->options_Tw('err_min_length') ? __($this->options_Tw('err_max_length'),'restrict-usernames-emails-characters') : __( "<strong>ERROR</strong>: Username may not be longer than %max% characters.",'restrict-usernames-emails-characters' ) ;
			$filter_err_max_length_BENrueeg_RUE = apply_filters( 'err_max_length_BENrueeg_RUE',$max );
			$max_length = $this->options('max_length');
			if( $this->length_max ) {
				$errors->errors['invalid_username'][0] = str_replace("%max%", $max_length, do_shortcode($filter_err_max_length_BENrueeg_RUE));
			}
			
			if ( $this->valid_num ) {
				if ($this->options_Tw('err_names_num'))
				$errors->errors['invalid_username'][0] = __($this->options_Tw('err_names_num'),'restrict-usernames-emails-characters');
				else
				$errors->errors['invalid_username'][0] = __("<strong>ERROR</strong>: You can't register with just numbers.",'restrict-usernames-emails-characters');
			}
			
			if ( $this->valid_num_less ) {
				if ($this->options_Tw('err_digits_less'))
				$errors->errors['invalid_username'][0] = __($this->options_Tw('err_digits_less'),'restrict-usernames-emails-characters');
				else
				$errors->errors['invalid_username'][0] = __("<strong>ERROR</strong>: The digits must be less than the characters in username.",'restrict-usernames-emails-characters');
			}
			
			if ( $this->restricted_emails ){
				if ($this->options_Tw('err_emails_limit')) {	
					$errors->add( 'empty_username', __($this->options_Tw('err_emails_limit'),'restrict-usernames-emails-characters') );
					} else {
					$errors->add( 'empty_username', __('<strong>ERROR</strong>: This email is not allowed, choose another please.','restrict-usernames-emails-characters') );
				}
			}
			
			if ( $this->restricted_domain_emails ) {
				if ($this->options_Tw('err_emails_limit'))	
				$errors->add( 'empty_username', __($this->options_Tw('err_emails_limit'),'restrict-usernames-emails-characters') );
				else
				$errors->add( 'empty_username', __('<strong>ERROR</strong>: This email is not allowed, choose another please.','restrict-usernames-emails-characters') );
			}
			
			return $errors;
		}
		
	}
	
	endif;
	

<?php
	
	if ( ! class_exists( 'ben_plug_restrict_usernames_emails_characters_options' ) ) :
	
	class ben_plug_restrict_usernames_emails_characters_options extends ben_plug_restrict_usernames_emails_characters_errors {
		
		public function __construct(  ) {
			parent::__construct();
		}
		
		function func__settings() {
			$main_site = apply_filters( 'main_site_cap_BENrueeg_RUE','yes' );
			if ($this->mu() && $main_site != 'no' && !is_main_site()) return;
			
			$page_title = 'Restrict Usernames Emails Characters Admin Page';
			$menu_title = __( 'Restrict Usernames Emails Characters ...', 'restrict-usernames-emails-characters' );
			$capability = apply_filters( 'manage_setts_cap_BENrueeg_RUE','manage_options' );
			$menu_slug = BENrueeg_RUE;
			$function = array( $this, 'BENrueeg_RUE_options_page' );
			
			add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function );
		}
		
		function settings__init(  ) { 
			
			register_setting( 'group_on', $this->opt );
			register_setting('group_tw', $this->opt_Tw);
			
			add_settings_section(
			'BENrueeg_RUE_Page_section_one', 
			null, //__( 'Your section description', 'restrict-usernames-emails-characters' ), 
			null, 
			'group_on'
			);
			
			// add_settings_field section 1
			
			add_settings_field( 
			'enable', 
			_x( 'Enable', 'label_settings_field', 'restrict-usernames-emails-characters' ), 
			array( $this, 'func__chec_enable' ), 
			'group_on', 
			'BENrueeg_RUE_Page_section_one',
			array(
			'enable',
			_x( 'Enable the plugin', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'<br /><div class="BENrueeg_RUE_to-tri"></div>'
			)
			);
			
			$fields_checkbox = array(
			array(
			'uid' => 'p_space',
			'label' => !$this->mu() ? _x( 'Not allow spaces in usernames', 'label_settings_field', 'restrict-usernames-emails-characters' ) :
			_x( 'Allow spaces in usernames', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => !$this->mu() ?  _x( 'Not allow use the spaces between words or characters in the user name', 'label_settings_field', 'restrict-usernames-emails-characters' ) :
			_x( 'Allow use the spaces between words or characters in the user name', 'label_settings_field', 'restrict-usernames-emails-characters' )
			),
			array(
			'uid' => 'start_end_space',
			'label' => _x( "Allow multi whitespace and space at the beginning or the end of the username (It's not recommended to enable it)", 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => !$this->mu() ?  _x( 'enable this if option (Allow spaces in usernames) is disabled', 'label_settings_field', 'restrict-usernames-emails-characters' ) :
			_x( 'enable this if option (Allow spaces in usernames) is enabled', 'label_settings_field', 'restrict-usernames-emails-characters' )
			),
			array(
			'uid' => 'p_num',
			'label' => !$this->mu() ? _x( 'Not allow use only numbers in the username', 'label_settings_field', 'restrict-usernames-emails-characters' ) :
			_x( 'allow use only digits (numbers) in the username', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => !$this->mu() ? _x( 'Not allow use only numbers, for example: 4752442 or +4752442', 'label_settings_field', 'restrict-usernames-emails-characters' ) :
			_x( 'allow use only numbers, for example: 4752442 or +4752442', 'label_settings_field', 'restrict-usernames-emails-characters' )
			),
			array(
			'uid' => 'digits_less',
			'label' => _x( 'The digits must be less than the characters in username', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( 'The digits (numbers) must be less than the characters in username.', 'label_settings_field', 'restrict-usernames-emails-characters' )
			),
			array(
			'uid' => 'uppercase',
			'label' => !$this->mu() ? _x( 'No uppercase in username', 'label_settings_field', 'restrict-usernames-emails-characters' ) :
			_x( 'Use uppercase (if latin is enabled)', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( '', 'label_settings_field', 'restrict-usernames-emails-characters' )
			),
			array(
			'uid' => 'name_not__email',
			'label' => _x( 'Do not allow usernames that are email addresses', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( '', 'label_settings_field', 'restrict-usernames-emails-characters' )
			),
			array(
			'uid' => 'all_symbs',
			'label' => _x( 'Prevent the use of all Symbols and letters accented in the username', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => ''
			)
			);
			foreach( $fields_checkbox as $field_ch ){
			if ($field_ch['uid'] != 'start_end_space') {
				add_settings_field( 
				$field_ch['uid'], 
				$field_ch['label'], 
				array( $this, 'func__chec' ), 
				'group_on', 
				'BENrueeg_RUE_Page_section_one',
				array($field_ch['uid'],$field_ch['label-em'])
				);
			}
			}
			
			add_settings_field( 
			'lang', 
			__( 'Choose language (characters) in username.', 'restrict-usernames-emails-characters' ), 
			array( $this, 'func__rad_lang' ), 
			'group_on', 
			'BENrueeg_RUE_Page_section_one',
			array(
			'lang',
			__( 'Default language by wordpress (Latin)', 'restrict-usernames-emails-characters' ),
			__( 'All languages (all letters and numbers and accented as: é û)', 'restrict-usernames-emails-characters' ),
			__( 'Arabic', 'restrict-usernames-emails-characters' ),
			__( 'Cyrillic', 'restrict-usernames-emails-characters' ),
			__( 'Arabic and Cyrillic', 'restrict-usernames-emails-characters' ),
			__( 'Enter another language below', 'restrict-usernames-emails-characters' )
			)
			);
			
			add_settings_field( 
			'selectedLanguage', 
			null, 
			array( $this, 'func__rad_selectedLanguage' ), 
			'group_on', 
			'BENrueeg_RUE_Page_section_one',
			array(
			'selectedLanguage',
			'width:300px;',
			'<br /><em><label for="selectedLanguage">'. __( 'copy your language from <a target="_blank" href="http://benaceur-php.com/?p=2281">this page</a>', 'restrict-usernames-emails-characters' ) .'</label></em>',
			'<br /><em><label for="selectedLanguage">'. __( 'Separate between language by commas, for example: Hebrew,Greek,Ethiopic', 'restrict-usernames-emails-characters' ) .'</label></em>'
			)
			);
			
			add_settings_field( 
			'langWlatin', 
			null, //__( 'Choose language (characters) in username.', 'restrict-usernames-emails-characters' ), 
			array( $this, 'func__rad_langWlatin' ), 
			'group_on', 
			'BENrueeg_RUE_Page_section_one',
			array(
			'langWlatin',
			__( 'with latin', 'restrict-usernames-emails-characters' ),
			__( 'only', 'restrict-usernames-emails-characters' )
			)
			);
			
			$fields_text = array(
			/*
				array(
				'uid' => 'disallow_spc_cars',
				'label' => _x( 'Disallow specific characters (Symbols)', 'label_settings_field', 'restrict-usernames-emails-characters' ),
				'label-em' => _x( 'Separate between characters by |, for example: -|+|@', 'label_settings_field', 'restrict-usernames-emails-characters' )
				),
				
				array(
				'uid' => 'BENrueeg_RUE_checkbox_field_2',
				'label' => _x( 'Settings field description 2x', 'label_settings_field', 'restrict-usernames-emails-characters' ),
				'label-em' => _x( 'ppppppppppppp3x', 'label_settings_field', 'restrict-usernames-emails-characters' )
				)
			*/
			);
			foreach( $fields_text as $field_t ){
				add_settings_field( 
				$field_t['uid'], 
				$field_t['label'], 
				array( $this, 'func__text' ), 
				'group_on', 
				'BENrueeg_RUE_Page_section_one',
				array($field_t['uid'],$field_t['label-em'])
				);
			}
			
			$fields_textarea = array(
			array(
			'uid' => 'disallow_spc_cars',
			'label' => _x( 'Prevent the use of characters (Symbols) permitted by wordpress', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( 'Symbols permitted by wordpress is: _ . - @<br />Place each character in one line, for example: <br />@<br />.<br />-', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'dir' => 'direction:ltr;',
			'siz' => 'font-weight:bold;',
			'remv_lines1' => 'remv_lines1'
			),
			array(
			'uid' => 'allow_spc_cars',
			'label' => __( 'Allow this characters (Symbols or characters accented as é û)','restrict-usernames-emails-characters' ),
			'label-em' => _x( '<span style="color:red; font-style:normal;">The following three symbols ( &#39; &#34; &#92; ) have been blocked<br />Because allowing these symbols can cause problems when the registration</span><br />Place each character in one line, for example: <br />(<br />+<br />é', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'dir' => 'direction:ltr;',
			'siz' => 'font-weight:bold;',
			'remv_lines2' => 'remv_lines2'
			),
			array(
			'uid' => 'emails_limit',
			'label' => _x( 'Not allow these mails', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( 'Place each email in one line, for example: <br />emailA@yahoo.com<br />emailB@gmail.com', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'dir' => 'direction:ltr;',
			'siz' => '',
			'remv_lines3' => 'remv_lines3'
			),
			array(
			'uid' => 'names_limit',
			'label' => _x( 'Not allow these names', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( 'Place each name in one line, for example: <br />nameA<br />nameB<br />nameC', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'dir' => '',
			'siz' => '',
			'remv_lines4' => 'remv_lines4'
			),
			array(
			'uid' => 'names_limit_partial',
			'label' => _x( 'Do not allow any name contains this part', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( 'For example, if we enter "ben", any name containing this part will be blocked as "benaceur"<br />Place each word in one line', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'dir' => '',
			'siz' => '',
			'remv_lines4_2' => 'remv_lines4_2'
			),
			array(
			'uid' => 'email_domain',
			'label' => _x( 'Not allow these emails domain', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( 'Place each domain name in one line, for example: <br />domainNameA.com<br />domainNameB.com<br />domainNameC.net', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'dir' => 'direction:ltr;',
			'siz' => '',
			'remv_lines5' => 'remv_lines5'
			)
			);
			foreach( $fields_textarea as $field_ttarea ){
				
				$dir = isset($field_ttarea['dir']) && !empty($field_ttarea['dir']) ? $field_ttarea['dir'] : '' ;
				$siz = isset($field_ttarea['siz']) && !empty($field_ttarea['siz']) ? $field_ttarea['siz'] : '' ;
				$remv_lines1 = isset($field_ttarea['remv_lines1']) && !empty($field_ttarea['remv_lines1']) ? $field_ttarea['remv_lines1'] : '' ;
				$remv_lines2 = isset($field_ttarea['remv_lines2']) && !empty($field_ttarea['remv_lines2']) ? $field_ttarea['remv_lines2'] : '' ;
				$remv_lines3 = isset($field_ttarea['remv_lines3']) && !empty($field_ttarea['remv_lines3']) ? $field_ttarea['remv_lines3'] : '' ;
				$remv_lines4 = isset($field_ttarea['remv_lines4']) && !empty($field_ttarea['remv_lines4']) ? $field_ttarea['remv_lines4'] : '' ;
				$remv_lines4_2 = isset($field_ttarea['remv_lines4_2']) && !empty($field_ttarea['remv_lines4_2']) ? $field_ttarea['remv_lines4_2'] : '' ;
				$remv_lines5 = isset($field_ttarea['remv_lines5']) && !empty($field_ttarea['remv_lines5']) ? $field_ttarea['remv_lines5'] : '' ;
				
				if ( $field_ttarea['uid'] != 'disallow_spc_cars' && $this->mu() ) {	
					add_settings_field( 
					$field_ttarea['uid'], 
					$field_ttarea['label'], 
					array( $this, 'func__texta' ), 
					'group_on', 
					'BENrueeg_RUE_Page_section_one',
					array($field_ttarea['uid'],$field_ttarea['label-em'],$dir,$siz,$remv_lines1,$remv_lines2,$remv_lines3,$remv_lines4,$remv_lines4_2,$remv_lines5)
					);
					} else if ( !$this->mu() ) {
					add_settings_field( 
					$field_ttarea['uid'], 
					$field_ttarea['label'], 
					array( $this, 'func__texta' ), 
					'group_on', 
					'BENrueeg_RUE_Page_section_one',
					array($field_ttarea['uid'],$field_ttarea['label-em'],$dir,$siz,$remv_lines1,$remv_lines2,$remv_lines3,$remv_lines4,$remv_lines4_2,$remv_lines5)
					);
				}
			}
			
			$fields_text_length = array(
			array(
			'uid' => 'min_length',
			'label' => _x( 'min length of the username', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-l-input' => 'width:60px;'
			),
			array(
			'uid' => 'max_length',
			'label' => _x( 'max length of the username', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-l-input' => 'width:60px;'
			)
			);
			foreach( $fields_text_length as $f_text_length ){
				add_settings_field( 
				$f_text_length['uid'], 
				$f_text_length['label'], 
				array( $this, 'func__text_length' ), 
				'group_on', 
				'BENrueeg_RUE_Page_section_one',
				array($f_text_length['uid'],$f_text_length['sty-w-l-input'])
				);
			}
			
			$tr = '<br /><div class="BENrueeg_RUE_to-tri"></div>';
			$fields_ch_length = array(
			array(
			'uid' => 'length_space',
			'label' => _x( 'length with space', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( 'take account the space in the username', 'label_settings_field', 'restrict-usernames-emails-characters' ) . $tr,
			),
			array(
			'uid' => 'remove_bp_field_name',
			'label' => __( 'Remove the name field from the form of registration', 'restrict-usernames-emails-characters' ),
			'label-em' => null,
			),
			array(
			'uid' => 'hide_bp_profile_section',
			'label' => __( 'Hide the entire section of the profile in the form of registration', 'restrict-usernames-emails-characters' ),
			'label-em' => __( 'But if you want to add other fields, do not check this box', 'restrict-usernames-emails-characters' ),
			)
			);
			foreach( $fields_ch_length as $f_ch_length ){
				if ( $f_ch_length['uid'] != 'remove_bp_field_name' && $f_ch_length['uid'] != 'hide_bp_profile_section' && !$this->bp() ) {	
				add_settings_field( 
				$f_ch_length['uid'], 
				$f_ch_length['label'], 
				array( $this, 'func__chec_length' ), 
				'group_on', 
				'BENrueeg_RUE_Page_section_one',
				array($f_ch_length['uid'],$f_ch_length['label-em'])
				);
				} elseif ($this->bp()) {
				add_settings_field( 
				$f_ch_length['uid'], 
				$f_ch_length['label'], 
				array( $this, 'func__chec_length' ), 
				'group_on', 
				'BENrueeg_RUE_Page_section_one',
				array($f_ch_length['uid'],$f_ch_length['label-em'])
				);
				}
			}
			
			add_settings_field( 
			'txt_form', 
			__( 'Add text (notice) to the registration form', 'restrict-usernames-emails-characters' ), 
			array( $this, 'func__texta_txtform' ), 
			'group_on', 
			'BENrueeg_RUE_Page_section_one',
			array(
			'txt_form',
			__( 'You can use HTML, as for example:<br />', 'restrict-usernames-emails-characters' ) . '<p style="direction:ltr;width:98%;">&lt;p style="font-family:your font here; color:your color here;" class="">your text&lt;/p></p>'
			)
			);
			
			add_settings_field( 
			'del_all_opts', 
			'<br /><br />' . __( 'Delete all data and settings for plugin of the database.', 'restrict-usernames-emails-characters' ), 
			array( $this, 'func__rad' ), 
			'group_on', 
			'BENrueeg_RUE_Page_section_one',
			array(
			'del_all_opts',
			'delete_opts',
			'no_delete_opts',
			__( 'Remove all settings and data of the plugin from database when the plugin is disabled', 'restrict-usernames-emails-characters' ),
			__( 'Do not delete', 'restrict-usernames-emails-characters' )
			)
			);
			
			
			// add_settings_field section 1
			
			add_settings_section(
			'BENrueeg_RUE_Page_section_tw', 
			__( '', 'restrict-usernames-emails-characters' ), 
			null, 
			'group_tw'
			);
			
			$fields_text_s2 = array(
			array(
			'uid' => $this->mu_bp() ? 'err_mp_empty' : 'err_empty',
			'label' => _x( 'Error: Enter a username', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( '', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-input' => 'border: 1px solid red;'
			),
			array(
			'uid' => $this->mu_bp() ? 'err_mp_exist_login' : 'err_exist_login',
			'label' => _x( 'Error: username exist', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( '', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-input' => 'border: 1px solid red;'
			),
			array(
			'uid' => $this->mu_bp() ? 'err_mp_spaces' : 'err_spaces',
			'label' => _x( 'Error: space in the username', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( '', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-input' => 'border: 1px solid red;'
			),
			array(
			'uid' => $this->mu_bp() ? 'err_mp_start_end_space' : 'err_start_end_space',
			'label' => _x( 'Error: multi whitespace and at the beginning or the end of the username', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( '', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-input' => 'border: 1px solid red;'
			),
			array(
			'uid' => $this->mu_bp() ? 'err_mp_names_num' : 'err_names_num',
			'label' => _x( 'Error: only numbers in the username', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( '', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-input' => 'border: 1px solid red;'
			),
			array(
			'uid' => $this->mu_bp() ? 'err_mp_spc_cars' : 'err_spc_cars',
			'label' => _x( 'Error: Characters (Symbols) in the username', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( '', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-input' => 'border: 1px solid red;'
			),
			array(
			'uid' => $this->mu_bp() ? 'err_mp_emails_limit' : 'err_emails_limit',
			'label' => _x( 'Error: restricted emails', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( '', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-input' => 'border: 1px solid red;'
			),
			array(
			'uid' => $this->mu_bp() ? 'err_mp_names_limit' : 'err_names_limit',
			'label' => _x( 'Error: restricted usernames', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( '', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-input' => 'border: 1px solid red;'
			),
			array(
			'uid' => $this->mu_bp() ? 'err_mp_min_length' : 'err_min_length',
			'label' => _x( 'Error: min length of the username', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( "use %min% to change the value automatically", 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-input' => 'border: 1px solid red;'
			),
			array(
			'uid' => $this->mu_bp() ? 'err_mp_max_length' : 'err_max_length',
			'label' => _x( 'Error: max length of the username', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( "use %max% to change the value automatically", 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-input' => 'border: 1px solid red;'
			),
			array(
			'uid' => $this->mu_bp() ? 'err_mp_partial' : 'err_partial',
			'label' => _x( 'Error: part of the username', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( "use %part% to change the value automatically", 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-input' => 'border: 1px solid red;'
			),
			array(
			'uid' => $this->mu_bp() ? 'err_mp_digits_less' : 'err_digits_less',
			'label' => _x( 'Error: The digits less than characters.', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( 'The digits (numbers) less than characters.', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-input' => 'border: 1px solid red;'
			),
			array(
			'uid' => $this->mu_bp() ? 'err_mp_name_not_email' : 'err_name_not_email',
			'label' => _x( 'Error: usernames that are email addresses', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( "", 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-input' => 'border: 1px solid red;'
			),
			array(
			'uid' => $this->mu_bp() ? 'err_mp_uppercase' : 'err_uppercase',
			'label' => _x( 'Error: No uppercase (A-Z) in username.', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'label-em' => _x( 'Do not allow use the uppercase (A-Z) in username.', 'label_settings_field', 'restrict-usernames-emails-characters' ),
			'sty-w-input' => 'border: 1px solid red;'
			)
			);
			foreach( $fields_text_s2 as $field_t_s2 ){
				add_settings_field( 
				$field_t_s2['uid'], 
				$field_t_s2['label'], 
				array( $this, 'func__text_s2' ), 
				'group_tw', 
				'BENrueeg_RUE_Page_section_tw',
				array($field_t_s2['uid'],$field_t_s2['label-em'],$field_t_s2['sty-w-input'])
				);
			}
			
			
		}
		
		function func__chec_enable( $args) { 
			
			printf(
			'<div class="checkboxT-BENrueeg_RUE">
			<input value="" name="%4$s[%1$s]" type="hidden">
			<input value="on" type="checkbox" id="%1$s" name="%4$s[%1$s]" %2$s />
			<label for="%1$s"></label>
			</div><em><label for="%1$s"> %3$s</label></em>%5$s',
			$args[0],
			checked ( $this->opts['option'][$args[0]], 'on', false ),
			//checked ( 'on', $this->opts['option'][$args[0]], false ),
			//checked( isset( $this->opts['option'][$args[0]] ), true, false ),
			$args[1],
			$this->opt,
			$args[2]
			);
			
		}
		
		function func__chec( $args) { 
			
			printf(
			'<label class="switch-BENrueeg_RUE">
			<input value="" name="%4$s[%1$s]" type="hidden">
			<input value="on" type="checkbox" class="switch-input-BENrueeg_RUE" id="%1$s" name="%4$s[%1$s]" %2$s />
			<span class="switch-label-BENrueeg_RUE" data-on="On" data-off="Off"></span>
			<span class="switch-handlel-BENrueeg_RUE"></span>
			</label>
			<br /><em><label for="%1$s"> %3$s</label></em>',
			$args[0],
			checked ( $this->opts['option'][$args[0]], 'on', false ),
			//checked ( 'on', $this->opts['option'][$args[0]], false ),
			//checked( isset( $this->opts['option'][$args[0]] ), true, false ),
			$args[1],
			$this->opt
			);
			
		}
		
		function func__text( $args) { 
			
			//$options = wp_parse_args( get_option($this->opt, $this->dflt_options()), $this->dflt_options() );
			
			$html = '<input type="text" id="'  . $args[0] . '" name="'.$this->opt.'['  . $args[0] . ']" value="' . $this->opts['option'][''  . $args[0] . ''] . '">';
			$html .= '<br /><em><label for="'  . $args[0] . '"> '  . $args[1] . '</label></em>';
			
			echo $html;
		}
		
		function func__texta( $args) { 
			
			//$options = wp_parse_args( get_option($this->opt, $this->dflt_options()), $this->dflt_options() );
			$ertex = $this->opts['option'][$args[0]];  
			
			$html ='<textarea id="'  . $args[4].$args[5].$args[6].$args[7].$args[8].$args[9] . '" style="'.$args[2].$args[3].'" cols="35%" rows="7" name="'.$this->opt.'['  . $args[0] . ']" >' . $ertex . '</textarea>';
			//$html = '<input type="text" id="'  . $args[0] . '" name="BENrueeg_RUE_settings['  . $args[0] . ']" value="' . $options[''  . $args[0] . ''] . '">';
			$html .= '<br /><em><label for="'  . $args[0] . '"> '  . $args[1] . '</label></em>';
			
			echo $html;
		}
		
		function func__texta_txtform( $args) { 
			
			$ertex = $this->opts['option'][$args[0]];  
			
			$html ='<textarea id="'  . $args[0] . '" style="height: 100px; min-width:98%;" cols="35%" rows="7" name="'.$this->opt.'['  . $args[0] . ']" >' . $ertex . '</textarea>';
			$html .= '<br /><em><label for="'  . $args[0] . '"> '  . $args[1] . '</label></em>';
			
			echo $html;
		}
		
		function func__rad( $args) { 
			
			//$options = wp_parse_args( get_option($this->opt, $this->dflt_options()), $this->dflt_options() );
			$html = '<div class="BENrueeg_RUE_to-tri"></div><br /><label for="'  . $args[0] . '"><input type="radio" id="'  . $args[0] . '" name="'.$this->opt.'['  . $args[0] . ']" value="'  . $args[1] . '" '  . checked($args[1], $this->opts['option'][''  . $args[0] . ''], false) . '> '  . $args[3] . '</label>';
			$html .= '<br /><label for="'  . $args[0] . '-2"><input type="radio" id="'  . $args[0] . '-2" name="'.$this->opt.'['  . $args[0] . ']" value="'  . $args[2] . '" '  . checked($args[2], $this->opts['option'][''  . $args[0] . ''], false) . '> '  . $args[4] . '</label>';
			
			echo $html;
		}
		
		function func__text_length( $args) { 
			
			//$options = wp_parse_args( get_option($this->opt, $this->dflt_options()), $this->dflt_options() );
			
			$html = '<input type="text" style="'  . $args[1] . ' text-align:center;" id="text_s2" name="'.$this->opt.'['  . $args[0] . ']" value="' . $this->opts['option'][''  . $args[0] . ''] . '">';
			//$html .= '<br /><em><label for="'  . $args[0] . '"> '  . $args[1] . '</label></em>';
			
			echo $html;
		}
		
		function func__chec_length( $args) { 
			
			printf(
			'<label class="switch-BENrueeg_RUE">
			<input value="" name="%4$s[%1$s]" type="hidden">
			<input value="on" type="checkbox" class="switch-input-BENrueeg_RUE" id="%1$s" name="%4$s[%1$s]" %2$s />
			<span class="switch-label-BENrueeg_RUE" data-on="On" data-off="Off"></span>
			<span class="switch-handlel-BENrueeg_RUE"></span>
			</label>
			<br /><em><label for="%1$s"> %3$s</label></em>',
			$args[0],
			checked ( $this->opts['option'][$args[0]], 'on', false ),
			//checked ( 'on', $this->opts['option'][$args[0]], false ),
			//checked( isset( $this->opts['option'][$args[0]] ), true, false ),
			$args[1],
			$this->opt
			);
			
		}
		
		function func__rad_lang( $args) { 
			
			//$options = wp_parse_args( get_option($this->opt, $this->dflt_options()), $this->dflt_options() );
			
			$html = '<div class="BENrueeg_RUE_to-tri"></div><br />';
			$html .= '<select id="BENrueeg_RUE_showelemselect" class="BENrueeg_RUE-blockSelect" style="text-align:center;" name="'.$this->opt.'['  . $args[0] . ']">';
			$html .= '<option value="default_lang" '.selected( $this->opts['option'][$args[0]], 'default_lang', false ).'> '  . $args[1] . '</option>';
			$html .= '<option value="all_lang" '.selected( $this->opts['option'][$args[0]], 'all_lang', false ).'> '  . $args[2] . '</option>';
			$html .= '<option value="arab_lang" '.selected( $this->opts['option'][$args[0]], 'arab_lang', false ).'> '  . $args[3] . '</option>';
			$html .= '<option value="cyr_lang" '.selected( $this->opts['option'][$args[0]], 'cyr_lang', false ).'> '  . $args[4] . '</option>';
			$html .= '<option value="arab_cyr_lang" '.selected( $this->opts['option'][$args[0]], 'arab_cyr_lang', false ).'> '  . $args[5] . '</option>';
			$html .= '<option value="select_lang" '.selected( $this->opts['option'][$args[0]], 'select_lang', false ).'> '  . $args[6] . '</option>';
			$html .= '</select>';
			
			echo $html;
		}
		
		function func__rad_selectedLanguage( $args) { 
			
			//$options = wp_parse_args( get_option($this->opt, $this->dflt_options()), $this->dflt_options() );
			
			$html = '<span id="BENrueeg_RUE_showdiv2">';
			$html .= '<input type="text" style="'  . $args[1] . '" id="text_s3" name="'.$this->opt.'['  . $args[0] . ']" value="' . $this->opts['option'][''  . $args[0] . ''] . '">';
			$html .= $args[2];
			$html .= $args[3];
			$html .= '</span>';
			
			echo $html;
		}
		
		function func__rad_langWlatin( $args) { 
			
			//$options = wp_parse_args( get_option($this->opt, $this->dflt_options()), $this->dflt_options() );
			
			$html = '<span id="BENrueeg_RUE_showdiv">';
			$html .= '<select class="BENrueeg_RUE-blockSelect" style="text-align:center;" name="'.$this->opt.'['  . $args[0] . ']">';
			$html .= '<option value="w_latin_lang" '.selected( $this->opts['option'][$args[0]], 'w_latin_lang', false ).'> '  . $args[1] . '</option>';
			$html .= '<option value="only_lang" '.selected( $this->opts['option'][$args[0]], 'only_lang', false ).'> '  . $args[2] . '</option>';
			$html .= '</select>';
			$html .= '</span>';
			$html .= '<div class="BENrueeg_RUE_to-tri"></div><br />';
			
			echo $html;
		}
		
		function func__text_s2( $args) { 
			
			//$options = wp_parse_args( get_option($this->opt_Tw, $this->dflt_options()), $this->dflt_options() );
			
			$html = '<input type="text" style="'  . $args[2] . '" id="text_s2" name="'.$this->opt_Tw.'['  . $args[0] . ']" value="' . $this->opts['option_Tw'][''  . $args[0] . ''] . '">';
			$html .= '<br /><em><label for="'  . $args[0] . '"> '  . $args[1] . '</label></em>';
			
			echo $html;
		}
		
		function dir_css() {
			
			$l = is_rtl() ? 'left' : 'right';
			$r = is_rtl() ? 'right' : 'left';
			$lang = $this->options('lang');
			
			echo "
			<style type='text/css'>
			@media only screen and (min-width: 783px) { .nav-tab-wrapper.BENrueeg_RUE-ntw  {margin-$l:19px;} }
			@media only screen and (max-width: 782px) { .nav-tab-wrapper.BENrueeg_RUE-ntw  {margin-$l:13px;} }
			#message.updated {margin-$r: 25%;}
			#BENrueeg_RUE_dashicons-menu {float:$l;}
			.BENrueeg_RUE_successModal { $l:30%; }
			.wrap.BENrueeg_RUE_wrap_red { border-$r: 3px solid red; }
			.wrap.BENrueeg_RUE_wrap_bott { border-$r: 3px solid #00A0D2; }
			.BENrueeg_RUE-mm411112 #BENrueeg_RUE-mm411112-divtoBlink{border-$r:3px solid #E5E504;}
			</style>
			";
			if ($lang == 'default_lang' || $lang == 'all_lang') {
				echo "<style type='text/css'>#BENrueeg_RUE_showdiv{display:none;}</style>";
			} 
			if ($lang != 'select_lang') {
				echo "<style type='text/css'>#BENrueeg_RUE_showdiv2{display:none;}
				span#BENrueeg_RUE_showdiv{position:absolute; margin-top:-50px;}
				</style>";
			}
		}
		
		function BENrueeg_RUE_options_page(  ) {
		
		$r = 'restrict_usernames_emails_characters&tab';   
		
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general_settings';
		if( isset( $_GET[ 'tab' ] ) ) {
		$active_tab = $_GET[ 'tab' ];
		} // end if
		
		if (isset($_GET['settings-updated']) && $GLOBALS['pagenow'] == BENrueeg_O_G && $_GET['page'] == BENrueeg_RUE){ 
		?>
		<style>#setting-error-settings_updated {display:none;}</style>
		<span id="message" class="updated" >
        <p style="opacity:1; font-size:15px;"><?php _e( 'Settings saved successfully.', 'restrict-usernames-emails-characters' ); ?></p>
        </span>
		<?php } 
		
		if( $active_tab == 'general_settings' ) { ?>
		<div style='display:none;' class='BENrueeg_RUE-mm4111172p'><?php $this->VerPlugUp();  ?></div>
		<?php } ?>
		
        <h2>Restrict Usernames Emails Characters V <?php echo $this->BENrueeg_RUE_version(); ?></h2>
		
        <h2 class="nav-tab-wrapper BENrueeg_RUE-ntw">
		<a href="?page=<?php echo $r ?>=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General Settings', 'restrict-usernames-emails-characters' ); ?></a>
		<a href="?page=<?php echo $r ?>=error_messages" class="nav-tab <?php echo $active_tab == 'error_messages' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Error Messages', 'restrict-usernames-emails-characters' ); ?></a>
		<a href="?page=<?php echo $r ?>=important" class="nav-tab <?php echo $active_tab == 'important' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Important!', 'restrict-usernames-emails-characters' ); ?></a>
		<a href="?page=<?php echo $r ?>=extentions" class="nav-tab <?php echo $active_tab == 'extentions' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Extentions', 'restrict-usernames-emails-characters' ); ?></a>
        <span id="BENrueeg_RUE_dashicons-menu" style="margin-top:16px;" class="dashicons dashicons-menu"></span>
		</h2>
		
		<?php
		
		if( $this->mu() && !is_plugin_active_for_network(BENrueeg_NTP)) {
		if( $active_tab == 'general_settings' || $active_tab == 'error_messages' ) {
		$href = network_admin_url('plugins.php?plugin_status=all');
		$url = '<a target="_blank" href="'.$href.'">'. __( 'network', 'restrict-usernames-emails-characters' ) .'</a>';
		printf( '<div style="background:#f7b2b2; border: none; border-radius:4px; margin:10px;" class="notice notice-error is-dismissible"><p>%1$s %2$s %3$s</p></div>',
		__( "The plugin must be enabled on the", 'restrict-usernames-emails-characters' ),
		$url,
		__( "to work without any problem", 'restrict-usernames-emails-characters' )
		);
		}
		}
		
		$settings = $active_tab == 'general_settings' ? 'General Settings' : 'Error Messages' ;
		if( $active_tab != 'extentions' && $active_tab != 'important' ) { ?>
		<form id="form-BENrueeg_RUE_1" action='options.php' method='post'>
		<div class="wrap rue">
		<h2 id="benSett-themes"><span style="margin-top:6px;" class="dashicons dashicons-editor-spellcheck"></span> <?php _e($settings,'restrict-usernames-emails-characters'); ?></h2>
		<div>	
		<?php 
			if( $active_tab == 'general_settings' ) {  
		settings_fields( 'group_on' );
		do_settings_sections( 'group_on' );
		
		echo '<input value="" name="'.$this->opt.'[start_end_space]" type="hidden" />';
		
		if ($this->mu())
		echo '<textarea style="display:none;" name="'.$this->opt.'[disallow_spc_cars]" ></textarea>';
	
		if (!$this->bp()) {
		echo '<input value="" name="'.$this->opt.'[remove_bp_field_name]" type="hidden" />';
		echo '<input value="" name="'.$this->opt.'[hide_bp_profile_section]" type="hidden" />';
		}
		?>          
		<p class="submit">
		<input name="Submit" id="submit-ftb1-BENrueeg_RUE" class="button-BENrueeg_RUE" type="submit" value="<?php _e( 'Save Changes', 'restrict-usernames-emails-characters' ); ?>" onclick="BENrueeg_RUE_remv_lines()"/>
		</p>
		<?php
		} else if( $active_tab == 'error_messages' ) {
		settings_fields( 'group_tw' );
		do_settings_sections( 'group_tw' );
		
		if ($this->mu_bp() ) {
		foreach($this->array_tw_word() as $err) {
		echo '<input value="' . $this->options_Tw($err) . '" name="'.$this->opt_Tw.'[' . $err . ']" type="hidden" />';
		}
		} else {
		foreach($this->array_tw_mubp() as $err_mp) {
		echo '<input value="' . $this->options_Tw($err_mp) . '" name="'.$this->opt_Tw.'[' . $err_mp . ']" type="hidden" />';
		}
		}
		?>          
		<p class="submit">
		<input name="Submit" id="submit-ftb1-BENrueeg_RUE" class="button-BENrueeg_RUE" type="submit" value="<?php _e( 'Save Changes', 'restrict-usernames-emails-characters' ); ?>"/>
		</p>
		<?php
		} 
		?>          
		</div>
		</div>
		
		</form>
		<?php } ?>
		
		<?php if( $active_tab == 'general_settings' ) { ?>
		<div class="wrap"><div class="postbox"><div class="inside">
		<p id="BENrueeg_RUE_wrap_t"><span class="dashicons dashicons-yes"></span><?php _e('Reset default settings', 'restrict-usernames-emails-characters');?></p>
		<form id="form-BENrueeg_RUE_2" method="post" action="options.php">
		<?php
		global $BENrueeg_RUE_reset_general_opt;
		wp_nonce_field('update-options') ?> 
		<input type="hidden"  name="BENrueeg_RUE_reset_general_opt" value="1" <?php if(empty($BENrueeg_RUE_reset_general_opt) ) { echo 'checked="checked"'; } ?>/>
		<input type="submit" id="submit-ftb2-BENrueeg_RUE" value="<?php _e('Reset general option', 'restrict-usernames-emails-characters');?>" class="button-secondary" />
		<input type="hidden" name="page_options" value="BENrueeg_RUE_reset_general_opt" />
		<input type="hidden" name="action" value="update" />
		</form>
		</div></div></div>
		<div id="BENrueeg_RUE_saveResult"></div>
		<?php
		echo '<script>
		var elem_BENrueeg_RUE = document.getElementById("BENrueeg_RUE_showelemselect");
		elem_BENrueeg_RUE.onchange = function(){
		var hiddenDiv_BENrueeg_RUE = document.getElementById("BENrueeg_RUE_showdiv");
		hiddenDiv_BENrueeg_RUE.style.display = (this.value == "default_lang" || this.value == "all_lang" ) ? "none":"block";
		};
		</script>';
		
		echo "<script>
		jQuery(function() {
		
		jQuery('#BENrueeg_RUE_showelemselect').change(function(){
		if(jQuery('#BENrueeg_RUE_showelemselect').val() == 'select_lang') {
		jQuery('#BENrueeg_RUE_showdiv2').css('display', 'inline-block');
		jQuery('#BENrueeg_RUE_showdiv').css('position', 'relative');
		jQuery('#BENrueeg_RUE_showdiv').css('margin-top', '0px');
		} else {
		jQuery('#BENrueeg_RUE_showdiv2').css('display', 'none');
		jQuery('#BENrueeg_RUE_showdiv').css('position', 'absolute');
		jQuery('#BENrueeg_RUE_showdiv').css('margin-top', '-50px');
		}
		});
		});
		</script>";
		
		} else if( $active_tab == 'error_messages' ) { ?>
		<div class="wrap"><div class="postbox"><div class="inside">
		<p id="BENrueeg_RUE_wrap_t"><span class="dashicons dashicons-yes"></span><?php _e('Reset default settings', 'restrict-usernames-emails-characters');?></p>
		<form id="form-BENrueeg_RUE_3" method="post" action="options.php">
		<?php
		global $BENrueeg_RUE_reset_err_mgs;
		wp_nonce_field('update-options') ?> 
		<input type="hidden"  name="BENrueeg_RUE_reset_err_mgs" value="1" <?php if(empty($BENrueeg_RUE_reset_err_mgs) ) { echo 'checked="checked"'; } ?>/>
		<input type="submit" id="submit-ftb3-BENrueeg_RUE" value="<?php _e('Reset error_messages', 'restrict-usernames-emails-characters');?>" class="button-secondary" />
		<input type="hidden" name="page_options" value="BENrueeg_RUE_reset_err_mgs" />
		<input type="hidden" name="action" value="update" />
		</form>
		</div></div></div>
		<div id="BENrueeg_RUE_saveResult"></div>
		
		<?php } ?>
		
		<?php if( $active_tab == 'general_settings' || $active_tab == 'error_messages' ) { ?>
		<!-- import export -->
		<div class="wrap"><div class="postbox"><div class="inside">
		
		<h3><span><?php _e('Export Settings', 'restrict-usernames-emails-characters'); ?></span></h3>
		<div class="inside">
		<p><?php _e( 'Export the plugin settings as a .json file. This allows you to easily import the configuration.', 'restrict-usernames-emails-characters' ); ?></p>
		<form method="post">
		<p><input type="hidden" name="BENrueeg_RUE_action" value="export_settings" /></p>
		<p>
		<?php wp_nonce_field( 'BENrueeg_RUE_export_nonce', 'BENrueeg_RUE_export_nonce' ); ?>
		<?php submit_button( __( 'Export' ), 'secondary', 'submit', false ); ?>
		</p>
		</form>
		</div><!-- .inside -->
		</div></div></div>
		
		<div class="wrap"><div class="postbox"><div class="inside">
		<h3><span><?php _e('Import Settings', 'restrict-usernames-emails-characters'); ?></span></h3>
		<div class="inside">
		<p><?php _e( 'Import the plugin settings from the saved .json file.', 'restrict-usernames-emails-characters' ); ?></p>
		<form id="BENrueeg_RUE_export__file" method="post" enctype="multipart/form-data">
		<p>
		<input type="file" name="import_file" id="BENrueeg_RUE_jsonfileToUpload" />
		</p>
		<p>
		<input type="hidden" name="BENrueeg_RUE_action" value="import_settings" />
		<?php wp_nonce_field( 'BENrueeg_RUE_import_nonce', 'BENrueeg_RUE_import_nonce' ); ?>
		<input type="submit" id="BENrueeg_RUE_export__file-sub" value="<?php _e( 'Import' );?>" class="button-secondary" />
		</p>
		</form>
		<div style="display:none;" id="BENrueeg_RUE_export-loading-div-background">
		<?php _e('Importing parameter files is in progress, wait ...', 'restrict-usernames-emails-characters') ?>  
		</div>
		<div style="display:none;" class="BENrueeg_RUE_export__file">
        <p><?php _e('The parameters file was imported successfully.', 'restrict-usernames-emails-characters') ?></p>
		</div>
		</div><!-- .inside -->
		</div></div></div><!-- .metabox-holder -->
		<!-- import export -->
		<?php } ?>
		
		<?php	if( $active_tab == 'important' ) { ?>
		<div class="wrap BENrueeg_RUE_wrap_padd"><div class="postbox"><div style="height:16px;" class="inside">
		<p style="padding-top:2px;" id="BENrueeg_RUE_wrap_t"><span style="margin-top:2px;" class="dashicons dashicons-megaphone"></span> <?php _e('Important to read', 'restrict-usernames-emails-characters');?></p>
		</div></div></div>
		
		<div class="wrap BENrueeg_RUE_wrap_red"><div class="postbox"><div class="inside">
		<p id="BENrueeg_RUE_wrap_ttext"><?php _e('- If you use login_errors hook then you know that it affects the error messages in registration also, it is best to use authenticate hook if you want to eg change the connection error messages it is best to use authenticate hook, if you want help about the modification of connection (log in) error messages contact me by creating a new post with your question on: <a target="_blank" href="http://benaceur-php.com/general-support/">general-support</a>', 'restrict-usernames-emails-characters');?></p>
		</div></div></div>
		<?php	} ?>
		
		<?php	if( $active_tab == 'extentions' ) { ?>
		<div class="wrap BENrueeg_RUE_wrap_padd"><div class="postbox"><div style="height:16px;" class="inside">
		<p style="padding-top:2px;" id="BENrueeg_RUE_wrap_t"><span class="dashicons dashicons-admin-plugins"></span> <?php _e('Other plugins of my development', 'restrict-usernames-emails-characters');?></p>
		</div></div></div>
		
		<?php
		
		include_once('admin/my-plugins.php'); 
		
		} ?>
		
		<div class="wrap BENrueeg_RUE_wrap_bott"><div class="postbox"><div class="inside">
		<p id="BENrueeg_RUE_wrap_t"><span class="dashicons dashicons-star-filled"></span> <?php _e("The evaluation of the plugin is important for continuity, If you're finding this plugin useful, please rate it on: <a target='_blank' href='https://wordpress.org/support/plugin/restrict-usernames-emails-characters/reviews/?filter=5'>this link</a>", 'restrict-usernames-emails-characters');?></p>
		</div></div></div>
		<?php
		
		printf(
		'<div class="BENrueeg_RUE_bottom">%1$s %2$s</div>',
		'<div >'. __('© Copyright 2016 - '.date('Y').' <a target="_blank" href="http://benaceur-php.com/">benaceur php</a> ', 'restrict-usernames-emails-characters') .'</div>',
		'<div>'. __('This is the support of the plugin: <a target="_blank" href="http://benaceur-php.com/?p=2268">support</a>', 'restrict-usernames-emails-characters') .'</div>'
		);
		
		$this->dir_css();
		
		}
		
		}
		new ben_plug_restrict_usernames_emails_characters_options();
		
		endif;				
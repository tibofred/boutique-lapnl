<?php
	
	if ( ! class_exists( 'ben_plug_restrict_usernames_emails_characters_CHARS' ) ) :
	
	class ben_plug_restrict_usernames_emails_characters_CHARS extends ben_plug_restrict_usernames_emails_characters_validation {
		public function __construct(  ) {
			parent::__construct();
		}
		
		public function func__CHARS( $username, $raw_username, $strict ) {
			
			$dis_all_symbs = $this->options('all_symbs');
			
			$lang = $this->options('lang');
			
			//foreach ($preg_ as $preg) 
			$allow_spc_cars = $this->options('allow_spc_cars');
			//$list_chars_ = array_map('trim', explode(PHP_EOL, $allow_spc_cars));
			//+++$list_chars_ = array_filter(explode("\n", trim($allow_spc_cars)));
			$list_chars_ = array_filter(explode("\n", $allow_spc_cars));
			//$text = implode("\n", $lines);
			//if ($options['allow_spc_cars'] == '') {
			//$list_chars = $allow_spc_cars;
			//} else {
			$list_chars = implode('\\', $list_chars_);
			
			
			//$list_chars = preg_split('/[\r\n]+/', $list_chars7, -1, PREG_SPLIT_NO_EMPTY);
			//}
			//$list_chars_e = preg_replace('/\n+/', "\n", trim($_POST[$list_chars]));
			
			//Strip HTML Tags
			$username = $this->ben_wp_strip_all_tags ($raw_username);
			
			if ( empty($allow_spc_cars) && $lang != 'all_lang' || $lang == 'all_lang' && $dis_all_symbs )
			$username = remove_accents ($username);
			// Kill octets
			$username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
			$username = preg_replace( '/&.+?;/', '', $username ); // Kill entities
			
			if ($strict)
			{
				
				$r_ = $this->lang__($username);
				
				if ($lang == 'default_lang' && !$dis_all_symbs) {
					$username = $r_[0];
					} else if ($lang == 'default_lang' && $dis_all_symbs) {
					$username = $r_[1];
					} else if ($lang == 'all_lang' && !$dis_all_symbs) {
					$username = $r_[2];
					} else if ($lang == 'all_lang' && $dis_all_symbs) {
					$username = $r_[3];
				} else if ($lang == 'arab_lang' && !$dis_all_symbs) {
				$username = $r_[4];
				} else if ($lang == 'arab_lang' && $dis_all_symbs) {
				$username = $r_[5];
				} else if ($lang == 'cyr_lang' && !$dis_all_symbs) {
				$username = $r_[6];
				} else if ($lang == 'cyr_lang' && $dis_all_symbs) {
				$username = $r_[7];
				} else if ($lang == 'arab_cyr_lang' && !$dis_all_symbs) {
				$username = $r_[8];
				} else if ($lang == 'arab_cyr_lang' && $dis_all_symbs) {
				$username = $r_[9];
				} else if ($lang == 'select_lang' && !$dis_all_symbs) {
				$username = $r_[10];
				} else if ($lang == 'select_lang' && $dis_all_symbs) {
				$username = $r_[11];
				}
				
				}
				
				$username = $username;
				
				return $username;
				}
				
				}
				
				endif;
								
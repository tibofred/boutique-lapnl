<?php
	/** 
		Plugin Name: Restrict Usernames Emails Characters
		Plugin URI: https://benaceur-php.com/?p=2268
		Description: Restrict the usernames, email addresses, characters and symbols or email from specific domain names or language in registration ...
		Version: 2.7.3
		Author: benaceur
		Author URI: https://benaceur-php.com/
		License: GPL2
	*/
	
	if ( ! defined( 'ABSPATH' ) ) exit;
	
	if ( ! class_exists( 'ben_plug_restrict_usernames_emails_characters_glob' ) ) :
	class ben_plug_restrict_usernames_emails_characters_glob {
		
		protected $BENrueeg_ver = '1.1';
		protected $opt = 'BENrueeg_RUE_settings';
		protected $opt_Tw = 'BENrueeg_RUE_settings_Tw';
		protected $TRT = 'restrict-usernames-emails-characters';
		protected $ntb = 'news-ticker-benaceur';
		protected $mntb = 'month-name-translation-benaceur';
		protected $nmib = 'notification-msg-interface-benaceur';
		protected $napb = 'notification-admin-panel-benaceur';
		protected $signup_username = '#signup_username';
		protected $signup_name = '#field_1';
		
		protected $valid_partial = false;
		protected $valid_charts = false;
		protected $valid_num = false;
		protected $valid_num_less = false;
		protected $preg = false;
		protected $empty__user_email = false;
		protected $invalid__user_email = false;
		protected $exist__user_email = false;
		protected $exist__login = false;
		protected $opts;
		protected $B_name = false;
		protected $invalid__name = false;
		protected $invalid_names = false;
		protected $invalid = false;
		protected $uppercase_names = false;
		protected $name_not__email = false;
		protected $space_start_end_multi = false;
		protected $B___name = false;
		protected $space = false;
		protected $length_min = false;
		protected $length_max = false;
		protected $restricted_emails = false;
		protected $restricted_domain_emails = false;
		protected $invalid_chars_allow = false;
		
		public function __construct(  ) {
			
			$this->opts = array('option' => get_option( $this->opt ), 'option_Tw' => get_option( $this->opt_Tw ));
			add_action( 'admin_init', array( $this, 'val' ) );
			
			add_action('admin_menu', array($this, 'func__settings'));
			add_action('admin_enqueue_scripts', array($this, 'style_admin'));
			add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'setts_link'));
			add_action( 'admin_notices', array($this, 'admin_notice__registre') );
			add_filter('plugin_row_meta', array($this, 'row_meta'), 10, 2);
			
			$funcs_ =  array('settings__init','BENrueeg_RUE_activ_redir','_exp','imp');
			foreach ( $funcs_ as $function_ ) {
				add_action( 'admin_init', array( $this, $function_ ) );
			}
			
			add_action('wp_enqueue_scripts', array($this, 'scripts'));
            add_shortcode( 'ruec_sc', array($this, 'shortcode_msg_errs') );
		    register_activation_hook( __FILE__, array($this, 'BENrueeg_RUE_activated'));
			register_deactivation_hook( __FILE__, array($this, 'BENrueeg_RUE_deactivated'));
			add_action( 'plugins_loaded', array($this, 'BENrueeg_RUE_textdomain') );
			
		    $this->add();
		}
		
		function is_options_page() {
			
			if ($GLOBALS['pagenow'] == BENrueeg_O_G && isset($_GET['page']) && $_GET['page'] == BENrueeg_RUE)
			return true;
			return false;
		}
		
		function BENrueeg_redirect() {
			return wp_safe_redirect( admin_url( 'options-general.php?page='.BENrueeg_RUE.'' ) );
		}
		
		function ben_parse_args($option, $get_option, $new_options) {
			
			$ops_merged = wp_parse_args($get_option, $new_options);
			return update_option($option, $ops_merged);
		}
		
		function options($value){ // $this->options('enable')
			
			$opts = get_option( $this->opt );
			$opt_s = isset($opts[$value]) && !empty($opts[$value]) ? $opts[$value] : '' ;
			
			return $opt_s;
		}
		
		function options_Tw($value){ // $this->options_Tw('err_spaces')
			
			$opts = get_option( $this->opt_Tw );
			$opt_s = isset($opts[$value]) && !empty($opts[$value]) ? $opts[$value] : '' ;
			
			return $opt_s;
		}
		
		function plug_last_v($plugin){
			
			if( ! function_exists( 'plugins_api' ) ) {
				include_once ABSPATH . '/wp-admin/includes/plugin-install.php'; 
			}
			$api = plugins_api( 'plugin_information', array(
			'slug' => $plugin,
			'fields' => array( 'version' => true )
			) );
			
			if( is_wp_error( $api ) ) return;
			
			return $api->version;
		}
		
		function wp__less_than($ver) {
			if ( version_compare( get_bloginfo('version'), "$ver", '<') ) return true;
			return false;		
		}
	    
		protected function ben_username_empty($username) {
			
			$wout_sp =  preg_replace( '/\s+/', '', $username );
			if ( empty( $wout_sp ) ) return true;
			return false;
		}
	    
		protected function ben_username_exists($username) {
			global $wpdb;
			
			$user__login = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $wpdb->users WHERE user_login = %s", $username) );
			if ( $user__login != null ) return true;
			return false;
		}
		
		function validation($valid, $name) {
			
			if ($valid && $this->ben_username_empty($name) ){
				$valid = false;
				$this->B_name = true;
			} 
			if ( !$valid ){
				$valid = false;
				$this->invalid__name = true;
			} 
			
			return $valid;
		}
		
		function add() {
			if ( $this->options('enable') == '' ) return;
			
			add_action( 'after_signup_user', array($this, 'signup__finished'), 10, 4 );
			add_filter( 'wp_pre_insert_user_data', array($this, 'signupfinished'), 10, 3 );
			//add_action( 'wpmu_activate_user', array($this, 'signup___finished'), 10, 1 );
			add_filter( 'insert_user_meta', array($this, 'signup_meta' ), 10, 3 );
			add_action( 'bp_after_registration_confirmed', array($this, 'bp_reg') );
			add_action( 'wp_head', array($this, 'head_reg' ) );
			add_action('register_form', array($this, 'to_register_form'), 99);
			add_filter('bp_nouveau_feedback_messages', array($this, 'to__register_form'));
			
			//if ( !is_admin() ) {
			add_filter('validate_username', array($this,'validation'), 10, 2);
			$this->foreac();
			add_filter( 'gettext', array($this, 'trans_errors'), 10, 3 );
			//}
			
			//if ( !$this->mu() ) 
			add_filter ('sanitize_user', array($this, 'func__CHARS'), 10, 3);
			if ( $this->mu_bp() ) 
			add_filter('wpmu_validate_user_signup', array($this, 'wpmubp__ben'));
		}
		
		function ver_base() {
			$data = get_option(BENrueeg_RUE_ver_b);
			return $data;
		}
		
		function BENrueeg_RUE_version() {
			$plugin_data = get_plugin_data( __FILE__ );
			$plugin_version = $plugin_data['Version'];
			return $plugin_version;
		}
		
		public function BENrueeg_RUE_activated(){
		if ( $this->wp__less_than('3.0') )  { 
		deactivate_plugins( basename( __FILE__ ) ); 
		die(__('<strong>Core Control:</strong> Sorry, This plugin requires WordPress 3.0+', BENrueeg_NT));
		} elseif (version_compare( PHP_VERSION, '5.1.0', '<' )) {
		deactivate_plugins( basename( __FILE__ ) ); 
		die(__('<strong>Core Control:</strong> Sorry, This plugin requires PHP 5.1.0+', BENrueeg_NT));
		} else {
		add_option('BENrueeg_RUE_do_activation_redi', true);	 
		}
		}
		
		public function BENrueeg_RUE_activ_redir() {
		if (get_option('BENrueeg_RUE_do_activation_redi', false)) {
		delete_option('BENrueeg_RUE_do_activation_redi');
		if(!isset($_GET['activate-multi'])) {
		$this->BENrueeg_redirect(); exit;
		}
		}
		}
		
		public function BENrueeg_RUE_deactivated(){
		
		if ($this->options('del_all_opts') == 'delete_opts') {
		delete_option($this->opt);
		delete_option($this->opt_Tw);
		delete_option(BENrueeg_RUE_ver_b);
		}
		}
		
		public function BENrueeg_RUE_textdomain() {
		load_plugin_textdomain( 'restrict-usernames-emails-characters', false, basename( dirname( __FILE__ ) ) . '/lang/' );
		}
		
		function setts_link($link){
		$link[] = '<a href="'.get_admin_url(null, ''.BENrueeg_O_G.'?page='.BENrueeg_RUE.'').'">'.__("Settings", 'restrict-usernames-emails-characters').'</a>';
		return $link;
		}
		
		function row_meta($links, $file) {
		
		if ( strpos( $file, 'restrict-usernames-emails-characters' ) !== false ) {
		$new_links = array(
		//'donate' => '<a href="http://benaceur-php.com/" target="_blank">'.__('Donate','restrict-usernames-emails-characters').'</a>',
		'support' => '<a href="https://benaceur-php.com/?p=2268" target="_blank">'.__('Support','restrict-usernames-emails-characters').'</a>'
		);
		
		$links = array_merge( $links, $new_links );
		}
		
		return $links;
		}
		
		function shortcode_msg_errs($err){
		
		$min_length = $this->options('min_length');
		$max_length = $this->options('max_length');
		
		extract(shortcode_atts(array(
		'err' => 'err'
		), $err));
		
		switch ($err) {
		case 'min-length':
		return $min_length;
		break;
		case 'max-length':
		return $max_length;
		break;
		}
		}
		
		// v def
		// isset( $no_val[$rr]) of checkbox 
		function home_url() {
		$homeUrl_ = get_home_url();
		$find = array( 'http://', 'https://', 'www.' );
		$replace = '';
		$homeUrl = str_replace( $find, $replace, $homeUrl_ );
		return $homeUrl;
		}
		
		function old_options() {
		
		$o = array (
		'enable' => 'on',
		'p_space' => '',
		'start_end_space' => '',
		'p_num' => '',
		'digits_less' => '',
		'uppercase' => '',
		'name_not__email' => '',
		'all_symbs' => '',
		'lang' => 'default_lang',
		'langWlatin' => 'w_latin_lang',
		'selectedLanguage' => '',
		'disallow_spc_cars' => '',
		'allow_spc_cars' => '',
		'emails_limit' => '',
		'names_limit' => '',
		'names_limit_partial' => '',
		'email_domain' => $this->home_url(),
		'min_length' => '',
		'max_length' => '',
		'length_space' => '',
		'remove_bp_field_name' => '',
		'hide_bp_profile_section' => '',
		'txt_form' => '',
		'del_all_opts' => 'no_delete_opts'
		);
		return $o;
		}
		
		function new_options() {
		
		$o = array (
		'enable' => 'on',
		'p_space' => '',
		'start_end_space' => '',
		'p_num' => '',
		'digits_less' => '',
		'uppercase' => '',
		'name_not__email' => '',
		'all_symbs' => '',
		'length_space' => '',
		'remove_bp_field_name' => '',
		'hide_bp_profile_section' => '',
		'txt_form' => ''
		);
		return $o;
		}
		
		function old_options_tw_word() {
		
		$o = array (
		'err_spaces' => "<strong>ERROR</strong>: It's not allowed to use spaces in username.",
		'err_names_num' => "<strong>ERROR</strong>: You can't register with just numbers.",
		'err_spc_cars' => '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.',
		'err_emails_limit' => '<strong>ERROR</strong>: This email is not allowed, choose another please.',
		'err_names_limit' => '<strong>ERROR</strong>: This username is not allowed, choose another please.',
		'err_min_length' => "<strong>ERROR</strong>: Username must be at least %min% characters.",
		'err_max_length' => "<strong>ERROR</strong>: Username may not be longer than %max% characters.",
		'err_partial' => "<strong>ERROR</strong>: This part <font color='#FF0000'>%part%</font> is not allowed in username.",
		'err_digits_less' => "<strong>ERROR</strong>: The digits must be less than the characters in username.",
		'err_name_not_email' => '<strong>ERROR</strong>: Do not allow usernames that are email addresses.',
		'err_uppercase' => '<strong>ERROR</strong>: No uppercase (A-Z) in username.',
		'err_start_end_space' => '<strong>ERROR</strong>: is not allowed to use multi whitespace or whitespace at the beginning or the end of the username.',
		'err_empty' => '<strong>ERROR</strong>: Please enter a username.',
		'err_exist_login' => '<strong>ERROR</strong>: This username is already registered. Please choose another one.'
		);
		return $o;
		}
		
		function old_options_tw_mupb() {
		
		$o = array (
		'err_mp_spaces' => "It's not allowed to use spaces in username.",
		'err_mp_names_num' => "You can't register with just numbers.",
		'err_mp_spc_cars' => 'This username is invalid because it uses illegal characters. Please enter a valid username.',
		'err_mp_emails_limit' => 'This email is not allowed, choose another please.',
		'err_mp_names_limit' => 'This username is not allowed, choose another please.',
		'err_mp_min_length' => "Username must be at least %min% characters.",
		'err_mp_max_length' => "Username may not be longer than %max% characters.",
		'err_mp_partial' => "This part <font color='#FF0000'>%part%</font> is not allowed in username.",
		'err_mp_digits_less' => "The digits must be less than the characters in username.",
		'err_mp_name_not_email' => 'Do not allow usernames that are email addresses.',
		'err_mp_uppercase' => 'No uppercase (A-Z) in username.',
		'err_mp_start_end_space' => 'is not allowed to use multi whitespace or whitespace at the beginning or the end of the username.',
		'err_mp_empty' => 'Please enter a username.',
		'err_mp_exist_login' => 'This username is already registered. Please choose another one.'
		);
		return $o;
		}
		
		function old_options_tw() {
		
		return array_merge($this->old_options_tw_word(),$this->old_options_tw_mupb());
		}
		
		function new_options_tw() {
		
		$o = array (
		'err_spaces' => "<strong>ERROR</strong>: It's not allowed to use spaces in username.",
		'err_min_length' => "<strong>ERROR</strong>: Username must be at least %min% characters.",
		'err_max_length' => "<strong>ERROR</strong>: Username may not be longer than %max% characters.",
		'err_partial' => "<strong>ERROR</strong>: This part <font color='#FF0000'>%part%</font> is not allowed in username.",
		'err_digits_less' => "<strong>ERROR</strong>: The digits must be less than the characters in username.",
		'err_name_not_email' => '<strong>ERROR</strong>: Do not allow usernames that are email addresses.',
		'err_uppercase' => '<strong>ERROR</strong>: No uppercase (A-Z) in username.',
		'err_start_end_space' => '<strong>ERROR</strong>: is not allowed to use multi whitespace or whitespace at the beginning or the end of the username.',
		'err_empty' => '<strong>ERROR</strong>: Please enter a username.',
		'err_exist_login' => '<strong>ERROR</strong>: This username is already registered. Please choose another one.',
		'err_mp_spaces' => "It's not allowed to use spaces in username.",
		'err_mp_names_num' => "You can't register with just numbers.",
		'err_mp_spc_cars' => 'This username is invalid because it uses illegal characters. Please enter a valid username.',
		'err_mp_emails_limit' => 'This email is not allowed, choose another please.',
		'err_mp_names_limit' => 'This username is not allowed, choose another please.',
		'err_mp_min_length' => "Username must be at least %min% characters.",
		'err_mp_max_length' => "Username may not be longer than %max% characters.",
		'err_mp_partial' => "This part <font color='#FF0000'>%part%</font> is not allowed in username.",
		'err_mp_digits_less' => "The digits must be less than the characters in username.",
		'err_mp_name_not_email' => 'Do not allow usernames that are email addresses.',
		'err_mp_uppercase' => 'No uppercase (A-Z) in username.',
		'err_mp_start_end_space' => 'is not allowed to use multi whitespace or whitespace at the beginning or the end of the username.',
		'err_mp_empty' => 'Please enter a username.',
		'err_mp_exist_login' => 'This username is already registered. Please choose another one.'
		);
		return $o;
		}
		
		function val() {
		
		global $BENrueeg_RUE_reset_general_opt,$BENrueeg_RUE_reset_err_mgs;  
		$BENrueeg_RUE_reset_general_opt = get_option('BENrueeg_RUE_reset_general_opt');
		$BENrueeg_RUE_reset_err_mgs = get_option('BENrueeg_RUE_reset_err_mgs');
		
		$no_val = get_option($this->opt);
		$no_val_Tw = get_option($this->opt_Tw);
		
		if ($this->ver_base() === false ) {
		
		add_option($this->opt, $this->old_options());
		add_option($this->opt_Tw, $this->old_options_tw());
		add_option( BENrueeg_RUE_ver_b, $this->BENrueeg_ver);
		
		if ($this->is_options_page()) {
		$this->BENrueeg_redirect(); exit;
		}
		
		} else if ( $this->BENrueeg_ver != $this->ver_base() ) {
		
		$no_val['start_end_space'] = ''; // set 'start_end_space' empty in get_option($this->opt) because I don't' use it anymore
		
		$this->ben_parse_args($this->opt, $no_val, $this->new_options());
		
		$this->ben_parse_args($this->opt_Tw, $no_val_Tw, $this->new_options_tw());
		
		update_option( BENrueeg_RUE_ver_b, $this->BENrueeg_ver);
		
		if ($this->is_options_page()) {
		$this->BENrueeg_redirect(); exit;
		}
		
		}
		
		if ( $BENrueeg_RUE_reset_general_opt ) {
		update_option($this->opt, $this->old_options());
		
		delete_option('BENrueeg_RUE_reset_general_opt');
		}
		
		if ( $BENrueeg_RUE_reset_err_mgs ) {
		if ($this->mu_bp())
		update_option($this->opt_Tw, $this->update_tw_mubp());
		else
		update_option($this->opt_Tw, $this->update_tw_word());
		
		delete_option('BENrueeg_RUE_reset_err_mgs');
		}
		
		}
		// v def
		
		function style_admin() { 
		if( $this->is_options_page() ){
		wp_enqueue_style('admin_css',plugin_dir_url( __FILE__ ).'/admin/style.css','',$this->BENrueeg_RUE_version());
		wp_enqueue_script('BENrueeg_RUE-admin_js',plugin_dir_url( __FILE__ ).'/admin/js.js','',$this->BENrueeg_RUE_version());
		wp_enqueue_script( 'BENrueeg_RUE-admin_js' );
		$BENrueeg_RUE_select_params = array(
		'wait_a_little'     => _x( 'Wait a little ...', 'params_js_o', 'restrict-usernames-emails-characters' ),
		'reset_succ'     => _x( 'Settings reset successfully', 'params_js_o', 'restrict-usernames-emails-characters' ),
		'msg_valid_json' => __( 'Please upload a valid .json file', 'restrict-usernames-emails-characters' ),
		'is_mu' => $this->mu() ? true : false
		);
		wp_localize_script( 'BENrueeg_RUE-admin_js', 'BENrueeg_RUE_jsParams', $BENrueeg_RUE_select_params );
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'jquery-form' );
		}
		}
		
		function scripts() { 
		wp_register_script('BENrueeg_RUE-not_file_js',false);
		wp_enqueue_script( 'BENrueeg_RUE-not_file_js' );
		
		$BENrueeg_RUE__params = array(
		'is_field_name_removed' => $this->options('remove_bp_field_name') == 'on' ? true : false
		);
		wp_localize_script( 'BENrueeg_RUE-not_file_js', 'BENrueeg_RUE_js_Params', $BENrueeg_RUE__params );
		}
		
		function to_register_form(){
		if ($this->options('txt_form') == '') return;
		$txt = $this->options('txt_form');
		echo $txt;
		}
		
		function to__register_form($txt){
		if ($this->options('txt_form') == '') return $txt;
		$txt['request-details']['message'] = $this->options('txt_form');
		return $txt;
		}	
		
		function bp_field($signup_name = true, $signup_section = true) {
		
		$display = 'display:none;';
		
		if ( $this->options('remove_bp_field_name') == 'on' && $signup_name || $this->options('hide_bp_profile_section') == 'on' && $signup_section )
		return $display;
		
		return '';
		}
		
		function VerPlugUp(){
		$enable = true;
		if (apply_filters( 'benrueeg_rue_filter_msg_old_ver_plug', $enable ) === false) return;
		if ( !current_user_can(apply_filters( 'benrueeg_rue_filter_mu_cap', 'update_plugins' ))) return;
		
		$n_plugin = "".BENrueeg_NAME."/".BENrueeg_NAME.".php";
		$v = $this->BENrueeg_RUE_version();		
		$update_file = $n_plugin;
		$url = wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=' . $update_file), 'upgrade-plugin_' . $update_file);
		if ($v < $this->plug_last_v(BENrueeg_NAME)) {
		echo '<br />';	
		echo "<div class='BENrueeg_RUE-mm411112'><div id='BENrueeg_RUE-mm411112-divtoBlink'>". __("You are using Version",'restrict-usernames-emails-characters').' '.$v.", ". __("There is a newer version, it's recommended to",'restrict-usernames-emails-characters')." <a href=".$url.">". __("update now",'restrict-usernames-emails-characters')."</a>.</div></div>";
		echo "
		<script>
		jQuery(document).ready(function(){
		jQuery('.BENrueeg_RUE-mm4111172p').delay(400).slideToggle('slow');
		}); 
		</script>";
		}
		}
		
		function admin_notice__registre() {
		if ( !current_user_can(apply_filters( 'benrueeg_rue_filter_admin_cap', 'update_core' ))) return;
		if (!$this->is_options_page() || get_option('users_can_register') == '1') return;
		
		$class = 'notice notice-error is-dismissible';
		$href = is_multisite() ? network_admin_url( 'settings.php' ) : admin_url('options-general.php');
		$url = '<a target="_blank" href="'.$href.'">'. __( 'here', 'restrict-usernames-emails-characters' ) .'</a>';
		$message = __( 'Registration is currently closed! open it:', 'restrict-usernames-emails-characters' );
		
		printf( '<div class="%1$s"><p>%2$s %3$s</p></div>', esc_attr( $class ), esc_html( $message ), $url ); 
		}
		
		function _exp() {
		if( empty( $_POST['BENrueeg_RUE_action'] ) || 'export_settings' != $_POST['BENrueeg_RUE_action'] )
		return;
		if( ! wp_verify_nonce( $_POST['BENrueeg_RUE_export_nonce'], 'BENrueeg_RUE_export_nonce' ) )
		return;
		if( ! current_user_can( 'manage_options' ) )
		return;
		
		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		$filename = 'restrict-usernames-emails-characters-settings-export-' . date("d-M-Y__H-i", current_time( 'timestamp', 0 )) . '.json';
		header( 'Content-Disposition: attachment; filename='.$filename );
		// cache
		header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
		header("Pragma: no-cache"); // HTTP 1.0
		header("Expires: 0"); // Proxies
		
		$AllOptions_BENrueeg_RUE = array( $this->opt, $this->opt_Tw );
		foreach($AllOptions_BENrueeg_RUE as $optionN_BENrueeg_RUE) {
		
		$options = array($optionN_BENrueeg_RUE => get_option($optionN_BENrueeg_RUE));
		foreach ($options as $key => $value) {
		$value = maybe_unserialize($value);
		$need_options[$key] = $value;
		}
		$need__options = version_compare( PHP_VERSION, '5.4.0', '>=' ) ? json_encode($need_options, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : json_encode($need_options);
		$json_file = $need__options;
		}
		echo $json_file;
		exit;
		}
		
		/**
		* Process a settings import from a json file
		*/
		function imp() {
		
		if( empty( $_POST['BENrueeg_RUE_action'] ) || 'import_settings' != $_POST['BENrueeg_RUE_action'] ) return;
		if( ! wp_verify_nonce( $_POST['BENrueeg_RUE_import_nonce'], 'BENrueeg_RUE_import_nonce' ) )	return;
		if( ! current_user_can( 'manage_options' ) ) return;
		
		$import_file = $_FILES['import_file'];
		$extension = end( explode( '.', $import_file['name'] ) );
		if( $extension != 'json' ) {
		wp_die( __( 'Please upload a valid .json file', 'restrict-usernames-emails-characters' ) );
		}
		
		// Retrieve the settings from the file and convert the json object to an array.
		$file_impor = file_get_contents($import_file['tmp_name']);
		$options = json_decode($file_impor, true);
		foreach ($options as $key => $value) {
		update_option($key, $value);
		}
		$this->BENrueeg_redirect(); exit;
		}
		
		function foreac() {
		add_filter( 'registration_errors', array( $this, 'func_errors' ), 10, 2 );
		if (!$this->mu_bp()) {
		add_filter('validate_username', array($this,'func_validation'), 10, 2);
		add_filter( 'user_registration_email', array( $this, 'user__email' ) );
		}
		}
		
		function user__email( $email ) {
		if ( $email == '' ) $this->empty__user_email = true;
		if ( ! is_email( $email ) ) $this->invalid__user_email = true;
		if ( email_exists( $email ) ) $this->exist__user_email = true;
		
		$list_emails = array_map('trim', explode(PHP_EOL, $this->options('emails_limit')));
		if ( in_array( $email, $list_emails ) && $email != '' && !email_exists( $email ) ){
		$this->restricted_emails = true;
		}
		
		$ListDomainEmails = array_map('trim', explode(PHP_EOL, $this->options('email_domain')));
		$BlackList = $ListDomainEmails;
		$scts = explode('@', $email);
		$domains = $scts[count($scts)-1];
		
		if ( in_array( $domains, $BlackList ) && $email != '' && !email_exists( $email ) ) {
		$this->restricted_domain_emails = true;
		}
		
		return $email;		
		}
		
		protected function _unset($errors, $name ) {
		$errors->remove( "$name" );
		unset( $errors->errors["$name"] );
		}
		
		function trans_errors ( $translations, $text, $domain ) {
		
		if ( $domain == 'default' ) {
		if ( $text == '(Must be at least 4 characters, letters and numbers only.)' && $this->mu() )
		$translations = $this->options('txt_form');
		
		if ( $text == '<strong>ERROR</strong>: Please enter a username.' && $this->options_Tw('err_empty') && !$this->mu_bp() )
		$translations = __($this->options_Tw('err_empty'),'restrict-usernames-emails-characters');
		}
		
		return $translations;
		}
		
		function ben_wp_strip_all_tags($string, $remove_breaks = false) {
		$string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
		$string = strip_tags($string);
		
		if ( $remove_breaks )
		$string = preg_replace('/[\r\n\t ]+/', ' ', $string);
		
		return $string;
		}
		
		function lang__($username) {
		
		$allow_spc_cars = $this->options('allow_spc_cars');
		$list_chars_ = array_filter(explode("\n", $allow_spc_cars));
		$list_chars = implode('\\', $list_chars_);
		
		$list_selt_lang_ = $this->options('selectedLanguage');
		$list_selt_lang_b = explode( ',', $list_selt_lang_ ); 
		foreach ($list_selt_lang_b as &$value){
		$value = '\p{' . trim($value) . '}';
		}
		$list_selt_lang = ! empty($list_selt_lang_) ? implode(',', $list_selt_lang_b) : null ;
		
		
		$wLatin = $this->options('langWlatin') == 'w_latin_lang' ? 'A-Za-z' : null ;
		
		//$username = remove_accents( $username );
		
		$default_lang_AS = $allow_spc_cars ? preg_replace('|[^A-Za-z0-9 _.\-@\\\\\\'.$list_chars.']|u', '', $username) : preg_replace('|[^A-Za-z0-9 _.\-@]|u', '', $username);
		$default_lang_DS = preg_replace('|[^A-Za-z0-9 ]|u', '', $username);
		
		$all_lang_AS = $allow_spc_cars ? preg_replace('|[^\p{L}0-9 _.\-@\\\\\\'.$list_chars.'\x80-\xFF]|u', '', $username) : preg_replace('|[^\p{L}0-9 _.\-@\x80-\xFF]|u', '', $username);
		$all_lang_DS = preg_replace('|[^\p{L}0-9 _.\-@\x80-\xFF]|u', '', $username);
		
		$arab_lang_AS = $allow_spc_cars ? preg_replace('|[^'.$wLatin.'\p{Arabic}0-9 _.\-@\\\\\\'.$list_chars.']|u', '', $username) : preg_replace('|[^'.$wLatin.'\p{Arabic}0-9 _.\-@]|u', '', $username);
		$arab_lang_DS = preg_replace('|[^'.$wLatin.'\p{Arabic}0-9 ]|u', '', $username);
		
		$cyr_lang_AS = $allow_spc_cars ? preg_replace('|[^'.$wLatin.'\p{Cyrillic}0-9 _.\-@\\\\\\'.$list_chars.']|u', '', $username) : preg_replace('|[^'.$wLatin.'\p{Cyrillic}0-9 _.\-@]|u', '', $username);
		$cyr_lang_DS = preg_replace('|[^'.$wLatin.'\p{Cyrillic}0-9 ]|u', '', $username);
		
		$arab_cyr_lang_AS = $allow_spc_cars ? preg_replace('|[^'.$wLatin.'\p{Arabic}\p{Cyrillic}0-9 _.\-@\\\\\\'.$list_chars.']|u', '', $username) : preg_replace('|[^'.$wLatin.'\p{Arabic}\p{Cyrillic}0-9 _.\-@]|u', '', $username);
		$arab_cyr_lang_DS = preg_replace('|[^'.$wLatin.'\p{Arabic}\p{Cyrillic}0-9 ]|u', '', $username);
		
		$selected_lang_AS = $allow_spc_cars ? preg_replace('|[^'.$wLatin . $list_selt_lang.'0-9 _.\-@\\\\\\'.$list_chars.']|u', '', $username) : preg_replace('|[^'.$wLatin . $list_selt_lang.'0-9 _.\-@]|u', '', $username);
		$selected_lang_DS = preg_replace('|[^'.$wLatin . $list_selt_lang.'0-9 ]|u', '', $username);
		
		return array($default_lang_AS,$default_lang_DS,$all_lang_AS,$all_lang_DS,$arab_lang_AS,$arab_lang_DS,$cyr_lang_AS,$cyr_lang_DS,
		$arab_cyr_lang_AS,$arab_cyr_lang_DS,$selected_lang_AS,$selected_lang_DS
		);
		}
		
		function lang__mu() {
		
		$allow_spc_cars = $this->options('allow_spc_cars');
		$list_chars_ = array_filter(explode("\n", $allow_spc_cars));
		$list_chars = implode('\\', $list_chars_);
		
		$list_selt_lang_ = $this->options('selectedLanguage');
		$list_selt_lang_b = explode( ',', $list_selt_lang_ ); 
		foreach ($list_selt_lang_b as &$value){
		$value = '\p{' . trim($value) . '}';
		}
		$list_selt_lang = ! empty($list_selt_lang_) ? implode(',', $list_selt_lang_b) : null ;
		
		
		$wLatin = $this->options('langWlatin') == 'w_latin_lang' ? 'A-Za-z' : null ;
		
		$default_lang_AS = $allow_spc_cars ? '/^[A-Za-z0-9\\'.$list_chars.'\s]+$/u' : '/^[A-Za-z0-9\s]+$/u';
		$default_lang_DS = '/^[A-Za-z0-9\s]+$/u';
		
		$all_lang_AS = $allow_spc_cars ? '/^[\p{L}0-9\\'.$list_chars.'\x80-\xFF\s]+$/u' : '/^[\p{L}0-9\x80-\xFF\s]+$/u';
		$all_lang_DS = '/^[\p{L}0-9\x80-\xFF\s]+$/u';
		
		$arab_lang_AS = $allow_spc_cars ? '/^['.$wLatin.'0-9\p{Arabic}\\'.$list_chars.'\s]+$/u' : '/^['.$wLatin.'0-9\p{Arabic}\s]+$/u';
		$arab_lang_DS = '/^['.$wLatin.'0-9\p{Arabic}\s]+$/u';
		
		$cyr_lang_AS = $allow_spc_cars ? '/^['.$wLatin.'0-9\p{Cyrillic}\\'.$list_chars.'\s]+$/u' : '/^['.$wLatin.'0-9\p{Cyrillic}\s]+$/u';
		$cyr_lang_DS = '/^['.$wLatin.'0-9\p{Cyrillic}\s]+$/u';
		
		$arab_cyr_lang_AS = $allow_spc_cars ? '/^['.$wLatin.'0-9\p{Arabic}\p{Cyrillic}\\'.$list_chars.'\s]+$/u' : '/^['.$wLatin.'0-9\p{Arabic}\p{Cyrillic}\s]+$/u';
		$arab_cyr_lang_DS = '/^['.$wLatin.'0-9\p{Arabic}\p{Cyrillic}\s]+$/u';
		
		$selected_lang_AS = $allow_spc_cars ? '/^['.$wLatin . $list_selt_lang.'0-9\\'.$list_chars.'\s]+$/u' : '/^['.$wLatin . $list_selt_lang.'0-9\s]+$/u';
		$selected_lang_DS = '/^['.$wLatin . $list_selt_lang.'0-9\s]+$/u';
		
		return array($default_lang_AS,$default_lang_DS,$all_lang_AS,$all_lang_DS,$arab_lang_AS,$arab_lang_DS,$cyr_lang_AS,$cyr_lang_DS,
		$arab_cyr_lang_AS,$arab_cyr_lang_DS,$selected_lang_AS,$selected_lang_DS
		);
		}
		
		public function mu() {
		return is_multisite();	
		}
		public function bp() {
		return function_exists('bp_is_active');	
		}
		public function mu_bp() {
		return (is_multisite() || function_exists('bp_is_active') );
		}
		public function mubp() {
		return (is_multisite() && function_exists('bp_is_active') );
		}
		public function is__signup() {
		if ( strpos( $_SERVER[ 'PHP_SELF' ], apply_filters( 'wp_signup_mu_filter_BENrueeg_RUE','wp-signup.php' ) ) )
		return true;
		return false;
		}
		
		public function can_create() {
		return current_user_can('create_users');	
		}
		
		function array_tw_word() {
		
		$k = $this->old_options_tw_word();
		return array_keys($k);
		}
		
		function array_tw_mubp() {
		
		$k = $this->old_options_tw_mupb();
		return array_keys($k);
		}
		
		function update_tw_mubp() {
		
		$val = get_option($this->opt_Tw);
		
		$arr = array_diff_key( $val, array_flip($this->array_tw_mubp()) );
		$arr_updated = apply_filters( 'old_options_tw_mupb_filter_BENrueeg_RUE',$this->old_options_tw_mupb() );
		$array = array_merge($arr, $arr_updated);
		return $array;
		}
		
		function update_tw_word() {
		
		$val = get_option($this->opt_Tw);
		
		$arr = array_diff_key( $val, array_flip($this->array_tw_word()) );
		$arr_updated = apply_filters( 'old_options_tw_word_filter_BENrueeg_RUE',$this->old_options_tw_word() );
		$array = array_merge($arr_updated, $arr);
		return $array;
		}
		
		/*
		ex for the filter:
		add_filter( 'old_options_tw_word_filter_BENrueeg_RUE', 'tw_word' );
		function tw_word($args){
		$args['err_names_num'] = 'message here';
		return $args;
		}
		*/
		
		}
		endif; 
		
		if ( ! class_exists( 'ben_plug_restrict_usernames_emails_characters_glob_files' ) ) :
		class ben_plug_restrict_usernames_emails_characters_glob_files {
		
		function define_() {
		if ( ! defined( 'BENrueeg_DIR_CLASSES' ) ) 
		define( 'BENrueeg_DIR_CLASSES', 'classes/' );
		
		if ( ! defined( 'BENrueeg_EXT' ) ) 
		define( 'BENrueeg_EXT', '.php' );
		
		if (!defined('BENrueeg_RUE')) 
		define("BENrueeg_RUE", "restrict_usernames_emails_characters");
		
		if (!defined('BENrueeg_RUE_ver_b')) 
		define("BENrueeg_RUE_ver_b", "restrict_usernames_emails_characters_ver_base");
		
		if (!defined('BENrueeg_O_G')) 
		define("BENrueeg_O_G", "options-general.php");
		
		if (!defined('BENrueeg_NAME'))
		define('BENrueeg_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
		
		if (!defined('BENrueeg_URL'))
		define('BENrueeg_URL', WP_PLUGIN_URL . '/' . BENrueeg_NAME);
		
		if ( ! defined( 'BENrueeg_DIR' ) ) 
		define( 'BENrueeg_DIR', plugin_dir_path( __FILE__ ) );  // 
		
		if ( ! defined( 'BENrueeg_NT' ) ) 
		define( 'BENrueeg_NT', 'restrict-usernames-emails-characters' );
		
		if ( ! defined( 'BENrueeg_NTP' ) ) 
		define( 'BENrueeg_NTP', BENrueeg_NT . '/restrict-usernames-emails-characters.php' );
		
		}
		
		function load_files($fileName) {
		
		$files = array(
		'validation' => 'classe_val'.BENrueeg_EXT,
		'chars' => 'classe_chars'.BENrueeg_EXT,
		'mubp' => 'classe_mubp'.BENrueeg_EXT,
		'errors' => 'classe_errors'.BENrueeg_EXT,
		'page_setts' => 'page-setts'.BENrueeg_EXT
		);
		
		foreach ( $files as $x => $x_value ) {
		$x = $fileName;	
		}
		$cls = $x == 'page_setts' ? '' : BENrueeg_DIR_CLASSES ;
		require_once( $cls.$files[$fileName] );
		
		return $fileName; 
		}
		
		function plugin() {
		
		$this->load_files('validation');
		$this->load_files('chars');
		$this->load_files('mubp');
		$this->load_files('errors');
		$this->load_files('page_setts');
		}
		
		}
		endif; 
		
		
		$ben_plug_RUEC_glob_files = new ben_plug_restrict_usernames_emails_characters_glob_files();
		$ben_plug_RUEC_glob_files->define_();
		$ben_plug_RUEC_glob_files->plugin();
				
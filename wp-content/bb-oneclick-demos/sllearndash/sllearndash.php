<?php
/* Social Learner (LearnDash)
 * Export Plug for BuddyBoss Importer
 **/

class buddyboss_importer_package_sllearndash extends buddyboss_importer_package {


    function __construct() {
        $this->hooks();  // if any hooks needs to add
    }

    /*
     * If need any hook for small plugin support.
     **/
    function hooks() {

    }

    /*
     * return the array of plugins data to import.
     **/
    function supported_plugins() {

       return array();

    }


    /*
     * Required Plugins.
     **/
    function required_plugins(){
        $plugins = array();

        # BuddyPress
        $plugins["buddypress"] = array(
            "name"=> __("BuddyPress","bb-oneclick"),
            "slug"=>"buddypress",
            "hosting"=>"wordpress", //wordpress/buddyboss
            "premium" => false,
            "url" => "https://wordpress.org/plugins/buddypress/",
            "plugin_path" => "buddypress/bp-loader.php" //plugin path where we can check if its active/available or not.
            );

        # bbPress
        $plugins["bbpress"] = array(
            "name"=> __("bbPress","bb-oneclick"),
            "slug"=>"bbpress",
            "hosting"=>"wordpress", //wordpress/buddyboss
            "premium" => false,
            "url" => "https://wordpress.org/plugins/bbpress/",
            "plugin_path" => "bbpress/bbpress.php" //plugin path where we can check if its active/available or not.
            );

        # LearnDash LMS
        $plugins["sfwd-lms"] = array(
            "name"=> __("LearnDash LMS","bb-oneclick"),
            "slug"=>"sfwd-lms",
            "hosting"=>"learndash", //wordpress/buddyboss
            "premium" => true,
            "url" => "https://getdpd.com/cart/hoplink/14394?referrer=56whuq1wlakosg0k4",
            "plugin_path" => "sfwd-lms/sfwd_lms.php" //plugin path where we can check if its active/available or not.
            );

        # BuddyPress for LearnDash
        $plugins["buddypress-learndash"] = array(
            "name"=> __("BuddyPress for LearnDash","bb-oneclick"),
            "slug"=>"buddypress-learndash",
            "hosting"=>"wordpress", //wordpress/buddyboss
            "premium" => false,
            "url" => "https://wordpress.org/plugins/buddypress-learndash/",
            "plugin_path" => "buddypress-learndash/buddypress-learndash.php" //plugin path where we can check if its active/available or not.
            );

        # Boss for LearnDash
        $plugins["boss-learndash"] = array(
            "name"=> __("Boss for LearnDash","bb-oneclick"),
            "slug"=>"boss-learndash",
            "hosting"=>"buddyboss", //wordpress/buddyboss
            "premium" => true,
            "url" => "https://www.buddyboss.com/product/social-learner-learndash/",
            "plugin_path" => "boss-learndash/boss-learndash.php" //plugin path where we can check if its active/available or not.
            );

        # BadgeOS
        $plugins["badgeos"] = array(
            "name"=> __("BadgeOS","bb-oneclick"),
            "slug"=>"badgeos",
            "hosting"=>"wordpress", //wordpress/buddyboss
            "premium" => false,
            "url" => "https://wordpress.org/plugins/badgeos/",
            "plugin_path" => "badgeos/badgeos.php" //plugin path where we can check if its active/available or not.
            );

        # BuddyPress Global Search
        $plugins["buddypress-global-search"] = array(
            "name"=> __("BuddyPress Global Search","bb-oneclick"),
            "slug"=>"buddypress-global-search",
            "hosting"=>"wordpress", //wordpress/buddyboss
            "premium" => false,
            "url" => "https://wordpress.org/plugins/buddypress-global-search/",
            "plugin_path" => "buddypress-global-search/buddypress-global-search.php" //plugin path where we can check if its active/available or not.
        );

        # WooCommerce
        $plugins["woocommerce"] = array(
            "name"=> __("WooCommerce","bb-oneclick"),
            "slug"=>"woocommerce",
            "hosting"=>"wordpress", //wordpress/buddyboss
            "premium" => false,
            "url" => "https://wordpress.org/plugins/woocommerce/",
            "plugin_path" => "woocommerce/woocommerce.php" //plugin path where we can check if its active/available or not.
            );

        # WPBakery Visual Composer
        /**
         * Installed by BuddyBoss if available with solution.
         */
        $plugins["js_composer"] = array(
            "name"=> __("WPBakery Visual Composer","bb-oneclick"),
            "slug"=>"js_composer",
            "hosting"=>"buddyboss", //wordpress/buddyboss
            "premium" => true,
            "url" => "http://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431",
            "plugin_path" => "js_composer/js_composer.php" //plugin path where we can check if its active/available or not.
            );
        
        # BuddyBoss Updater
        $plugins["buddyboss-updater"] = array(
            "name"=> __("BuddyBoss Updater","bb-oneclick"),
            "slug"=>"buddyboss-updater",
            "hosting"=>"buddyboss", //wordpress/buddyboss
            "premium" => false,
            "url" => "https://www.buddyboss.com/tutorials/buddyboss-updater-plugin/",
            "plugin_path" => "buddyboss-updater/buddyboss-updater.php" //plugin path where we can check if its active/available or not.
            );
        

        return $plugins;
    }

    /*
     * Optional Plugins
     **/
    function optional_plugins() {
        $plugins = array();

        # LearnDash Pro Panel
        $plugins["learndash-propanel"] = array(
            "name"=> __("LearnDash Pro Panel","bb-oneclick"),
            "slug"=>"learndash-propanel",
            "hosting"=>"learndash", //wordpress/buddyboss
            "premium" => true,
            "url" => "http://www.learndash.com/propanel-by-learndash/",
            "plugin_path" => "learndash-propanel/learndash_propanel.php" //plugin path where we can check if its active/available or not.
            );

        # LearnDash WooCommerce Integration
        $plugins["learndash_woocommerce"] = array(
            "name"=> __("LearnDash for WooCommerce","bb-oneclick"),
            "slug"=>"learndash_woocommerce",
            "hosting"=>"learndash", //wordpress/buddyboss
            "premium" => true,
            "url" => "http://www.learndash.com/work/woocommerce/",
            "plugin_path" => "learndash_woocommerce/learndash_woocommerce.php" //plugin path where we can check if its active/available or not.
            );

        # BP Remove Profile Links
        $plugins["bp-remove-profile-links-master"] = array(
            "name"=> __("BP Remove Profile Links","bb-oneclick"),
            "slug"=>"bp-remove-profile-links-master",
            "hosting"=>"buddyboss", //wordpress/buddyboss
            "premium" => false,
            "url" => "https://github.com/bphelp/bp-remove-profile-links",
            "plugin_path" => "bp-remove-profile-links-master/loader.php" //plugin path where we can check if its active/available or not.
            );

        # BuddyBoss Wall
        $plugins["buddyboss-wall"] = array(
            "name"=> __("BuddyBoss Wall","bb-oneclick"),
            "slug"=>"buddyboss-wall",
            "hosting"=>"buddyboss", //wordpress/buddyboss
            "premium" => true,
            "url" => "https://www.buddyboss.com/product/buddyboss-wall/",
            "plugin_path" => "buddyboss-wall/buddyboss-wall.php" //plugin path where we can check if its active/available or not.
            );

        # BuddyBoss Media
        $plugins["buddyboss-media"] = array(
            "name"=> __("BuddyBoss Media","bb-oneclick"),
            "slug"=>"buddyboss-media",
            "hosting"=>"buddyboss", //wordpress/buddyboss
            "premium" => true,
            "url" => "https://www.buddyboss.com/product/buddyboss-media/",
            "plugin_path" => "buddyboss-media/buddyboss-media.php" //plugin path where we can check if its active/available or not.
            );

        # BadgeOS Community Add-on
        $plugins["badgeos-community-add-on"] = array(
            "name"=> __("BadgeOS Community Add-on","bb-oneclick"),
            "slug"=>"badgeos-community-add-on",
            "hosting"=>"wordpress", //wordpress/buddyboss
            "premium" => false,
            "url" => "https://wordpress.org/plugins/badgeos-community-add-on/",
            "plugin_path" => "badgeos-community-add-on/badgeos-community.php" //plugin path where we can check if its active/available or not.
            );
        
        return $plugins;

    }

    /**
     * Required theme
     * */

    function required_theme() {
        $themes =  array();

        $themes["boss"] = array(
            "name"=> __("Boss Theme","bb-oneclick"),
            "slug"=>"boss",
            "hosting"=>"buddyboss",
            "premium" => true,
            "url" => "https://www.buddyboss.com/product/boss-theme/",
          );

        $themes["boss-child"] = array(
            "name"=> __("Boss Child Theme","bb-oneclick"),
            "slug"=>"boss-child",
            "hosting"=>"buddyboss",
            "premium" => true,
            "url" => "https://www.buddyboss.com/product/boss-theme/",
          );

        return $themes;
    }

    function theme() {
        return 'boss-child';
    }
}

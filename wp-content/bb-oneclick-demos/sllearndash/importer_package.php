<?php
/*
 * Parent Class
 * Child Class Name Must be "buddyboss_impoter_package_{package_name}"
 **/
class buddyboss_importer_package {

    function supported_plugins() {
        return array();
    }


    /*
     * sub array format should be.
     * array(
     *  "name"=> "" //name of plugin
     *  "slug"=>"" //refer to the plugin unique slug on wordpress repo.
        "hosting"=>"" //wordpress/buddyboss
        "premium" => "" //true false
        "url" => "" //url to the product
        "version" => ""//preferable version to download.
        "plugin_path" => "" //plugin path where we can check if its active/available or not.
        );
     *
     **/

    function required_plugins() {
        return array();
    }

    function optional_plugins() {
        return array();
    }
    /**
     * Theme will be always one always remember
     * */
    function required_theme() {
        return array();
    }
    /**
     * Theme that need to be active.
     */
    function theme() {
        return '';
    }
}

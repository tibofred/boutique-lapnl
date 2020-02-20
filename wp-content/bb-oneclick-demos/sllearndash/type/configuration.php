<?php
/*
 * Importer Configuration.
 **/

class buddyboss_importer_configuration {

    private $log = "";
    private $process = "configuration";
    private $limit  = 1;
    function __construct() {

    }

    /*
     * Will check if there is any content left.
     **/
    function has_content() {
        global $bb_importer_process,$wpdb;

        // if empty then its initial request.
        if(empty($bb_importer_process[$this->process])) {

            $tasks = array();

            $tasks[] = array("task"=>"add_current_user","label"=>__("Adding Logged in User","bb-oneclick"));
            $tasks[] = array("task"=>"add_options","label"=>__("Adding Required Options","bb-oneclick"));
            $tasks[] = array("task"=>"configure_table","label"=>__("Configuring Tables","bb-oneclick")); //must be last step

            $bb_importer_process[$this->process]["left_tasks"] = $tasks;
            $bb_importer_process[$this->process]["left"] = (int) count($tasks);
            $bb_importer_process[$this->process]["offset"] = 0;
            $bb_importer_process[$this->process]["done"] = 0;

        }

        if($bb_importer_process[$this->process]["left"] > 0) {
            return true;
        }

        return false;

    }

    /*
     * Configure if available
     **/

    function import(){
        global $bb_importer_process,$wpdb;


        $bb_importer_process[$this->process]["done"] = $bb_importer_process[$this->process]["done"]+$this->limit;
        $bb_importer_process[$this->process]["process_number"] = $bb_importer_process[$this->process]["process_number"]+1;

        usleep(1000);


        foreach($bb_importer_process[$this->process]["left_tasks"] as $k => $v) {

            $task = $bb_importer_process[$this->process]["left_tasks"][$k]["task"];
            $task_label = $bb_importer_process[$this->process]["left_tasks"][$k]["label"];

            $status = $this->{$task}();


            if($status === false) {
                buddyboss_importer_return_final_error($this->log);
                break;
            }

            unset($bb_importer_process[$this->process]["left_tasks"][$k]);
            $bb_importer_process[$this->process]["left"] = $bb_importer_process[$this->process]["left"]-$this->limit;

            $this->log = sprintf(__("%s - Success","bb-oneclick"),$task_label);

            break; // only allow one.
        }

    }

    function add_current_user() {

         global $bb_importer_process,$wpdb;

         $working_prefix = "bboc_".$wpdb->prefix;

         $current_user_id = get_current_user_id();

         $get_current_user = $wpdb->get_row("SELECT *FROM {$wpdb->prefix}users WHERE ID='{$current_user_id}'",ARRAY_A);

         if(empty($get_current_user)) {
            $this->log = __("There is an error while adding current user.","bb-oneclick");
            return false;
         }

         $get_current_user_metas = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}usermeta WHERE user_id='{$current_user_id}'",ARRAY_A);

         unset($get_current_user["ID"]);
         $old_username = $get_current_user["user_login"];
         $get_current_user["user_login"] = $this->user_unique_login($get_current_user["user_login"]);

         // Mark it for showing later.
         if($old_username != $get_current_user["user_login"]) {
           $bb_importer_process["user_login_changed"] = $get_current_user["user_login"];
         }

         $wpdb->insert($working_prefix."users",$get_current_user);

         $bb_importer_process["logged_in_user_new_id"] = $wpdb->insert_id;


         foreach($get_current_user_metas as $k => $v) {
            $v["user_id"] = $bb_importer_process["logged_in_user_new_id"]; //change user ID.
            $wpdb->insert($working_prefix."usermeta",$v);
         }

    }


    /**
     * Return the unqiue user name on based of given one.
     * @param  userlogin $user_login
     * @return string
     */
    function user_unique_login($user_login) {
        global $wpdb,$bb_importer_process;

        $working_prefix = "bboc_".$wpdb->prefix;

        $query = $wpdb->prepare("SELECT ID FROM {$working_prefix}users where user_login=%s",$user_login);
        $result = $wpdb->get_results($query);
        $available = (empty($result))?false:true;
        $i = 1;

        while($available) {
            $user_login2 = $user_login.$i;
            $query = $wpdb->prepare("SELECT ID FROM {$working_prefix}users where user_login=%s",$user_login2);
            $result = $wpdb->get_results($query);
            $available = (empty($result))?false:true;

            if(!$available) { // Its not available !
                $user_login = $user_login2;
                break;
            }

            $i++;
        }

        return $user_login;
    }


    function add_options() {
        global $bb_importer_process,$wpdb;


        $working_prefix = "bboc_".$wpdb->prefix;

        $wpdb->update($working_prefix."options",array(
                                                "option_value" => get_option("admin_email"),
                                                ),
                                                array(
                                                "option_name" => "admin_email"
                                                )
                                                );
        $wpdb->update($working_prefix."options",array(
                                                "option_value" => get_option("blogname"),
                                                ),
                                                array(
                                                "option_name" => "blogname"
                                                )
                                                );
        $wpdb->update($working_prefix."options",array(
                                                "option_value" => get_option("blogdescription"),
                                                ),
                                                array(
                                                "option_name" => "blogdescription"
                                                )
                                                );

        $value = maybe_serialize( get_option("active_plugins") );
        $wpdb->update($working_prefix."options",array(
                                                "option_value" => $value,
                                                ),
                                                array(
                                                "option_name" => "active_plugins"
                                                )
                                                );

        $value = maybe_serialize( get_option("current_theme") );
        $wpdb->update($working_prefix."options",array(
                                                "option_value" => $value,
                                                ),
                                                array(
                                                "option_name" => "current_theme"
                                                )
                                                );


        $value = maybe_serialize( get_option("stylesheet") );
        $wpdb->update($working_prefix."options",array(
                                                "option_value" => $value,
                                                ),
                                                array(
                                                "option_name" => "stylesheet"
                                                )
                                                );
        
        //add licenses info
        $value = maybe_serialize( get_option( "_bboneclick_license_details" ) );
        $wpdb->insert( 
            $working_prefix . "options", 
            array(
                "option_name"   => "_bboneclick_license_details",
                "option_value"  => $value,
            ),
            array( '%s', '%s' )
        );
    }

    /**
     * Create tables
     * @return void
     */
    function configure_table() {
        global $bb_importer_process,$wpdb;

        $create_tables = $bb_importer_process["settings"]["create_table"];

        $working_prefix = "bboc_".$wpdb->prefix;
        $bb_importer_process["working_prefix"] = $working_prefix;

        // Rename all tables to original.
        $status = true;
        foreach($create_tables as $table => $query) {

            $new_table_name = $working_prefix.$table;
            $old_table_name = $wpdb->prefix.$table;

            $table_exists = $wpdb->get_row("SHOW TABLES LIKE '{$old_table_name}'");

            if(!empty($table_exists)){
                // Drop
                $wpdb->query("DROP TABLE `{$old_table_name}`"); //drop if exists
            }

            // Rename
            $wpdb->query("ALTER TABLE `{$new_table_name}` RENAME TO `{$old_table_name}`");

        }

        // FLUSH PERMALINKS
        global $wp_rewrite;
        @$wp_rewrite->flush_rules( true );

        $_SESSION["buddyboss_oneclick_force_login"] = $bb_importer_process["logged_in_user_new_id"];

        if(!$status) {
            return false;
        }

    }


    /*
     * Return the percentage of process.
     **/
    function get_percentage() {
        global $bb_importer_process;

        $total = ($bb_importer_process[$this->process]["left"])+($bb_importer_process[$this->process]["done"]);
        $done =  ($bb_importer_process[$this->process]["done"]);
        $percentage =  ($done / $total) * 100;
        return $percentage;
    }

    /*
     * Return the Log
     **/

    function log() {

        return $this->log;

    }


}


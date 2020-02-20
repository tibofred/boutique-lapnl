<?php
/*
 * Importer Configuration.
 **/

class buddyboss_importer_initialization {

    private $log = "";
    private $process = "initialization";
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

            $tasks = array(
                "create_tables" => __("Create Tables Structure","bb-oneclick"),
                "files_config" => __("Files Directory Configuration","bb-oneclick"),
            );

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


        foreach($bb_importer_process[$this->process]["left_tasks"] as $task => $task_label) {

            $status = $this->{$task}();


            if($status === false) {
                buddyboss_importer_return_final_error($this->log);
                break;
            }

            unset($bb_importer_process[$this->process]["left_tasks"][$task]);
            $bb_importer_process[$this->process]["left"] = $bb_importer_process[$this->process]["left"]-$this->limit;

            $this->log = sprintf(__("%s - Success","bb-oneclick"),$task_label);

            break; // only allow one.
        }

    }

    /**
     * Create tables
     * @return void
     */
    function create_tables() {
        global $bb_importer_process,$wpdb;

        $create_tables = $bb_importer_process["settings"]["create_table"];

        $working_prefix = "bboc_".$wpdb->prefix;
        $bb_importer_process["working_prefix"] = $working_prefix;

        // Remove and add all Working tables.
        $status = true;
        foreach($create_tables as $table => $query) {

            $new_table_name = $working_prefix.$table;

            $query  = str_replace("{TABLE_NAME}",$new_table_name,$query);

            $table_exists = $wpdb->get_row("SHOW TABLES LIKE '{$new_table_name}'");

            if(!empty($table_exists)){
                // Drop
                $wpdb->query("DROP TABLE `{$new_table_name}`"); //drop if exists
            }

            // Create
            $wpdb->query($query); //Create Table.

            $table_exists = $wpdb->get_row("SHOW TABLES LIKE '{$new_table_name}'");

            if(empty($table_exists)) {
                $this->log = __("There is an error while creating tables on your server.","bb-oneclick");
                $status = false;
                break;
            }

        }

        if(!$status) {
            return false;
        }

    }

    function files_config(){

        global $bb_importer_process;

        $upload_dir = wp_upload_dir();

        $backup_dir = trailingslashit( dirname($upload_dir["basedir"]) )."uploads-backup-".date ("Y-m-d H:i:s");

        $bb_importer_process["upload_backup_dir"] = $backup_dir;

        rename($upload_dir["basedir"],$backup_dir);

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


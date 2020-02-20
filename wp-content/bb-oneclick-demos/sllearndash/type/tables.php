<?php
/*
 * Importer Class for Tables.
 **/

class buddyboss_importer_tables {

    private $log = "";
    private $process = "tables";
    private $table_name = "";
    private $table_label = "";

    function __construct() {

    }

    /**
     * this will tell how many files to load on each request
     */
    function load_limits() {

        if($this->table_name == "postmeta") {
            return 50;
        }

        return 100;
    }

    function set_params($parameters) {
        $this->table_name = $parameters[0];
        $this->process = "tables_".$this->table_name; // set unique process.
        $this->table_label = buddyboss_importer_package_installer::table_label($this->table_name);
    }

    /*
     * Will check if there is any content left.
     **/
    function has_content() {
        global $bb_importer_process;

        // if empty then its initial request.
        if(empty($bb_importer_process[$this->process])) {

            //check how many files are there to import.
            $working_dir = $bb_importer_process["working_dir"];

            $files = glob($working_dir.'/data/tables_'.$this->table_name.'/*.json');

            $bb_importer_process[$this->process]["left"] = $files; //left files
            $bb_importer_process[$this->process]["done"] = array(); //done files

        }

        if(count($bb_importer_process[$this->process]["left"]) > 0) {
            return true;
        }

        return false;

    }

    /*
     * Import the last file if available.
     **/

    function import(){
        global $bb_importer_process,$wpdb;


        //get last file.
        $file = "";
        $file_key = 0;
        foreach($bb_importer_process[$this->process]["left"] as $key => $val) {
            $file = $val;
            $file_key = $key;
            break;
        }

        // all import process goes here.
        $datasets = file_get_contents($file);

        $datasets = json_decode($datasets,true);

        $hints = array();

        $prefix = $bb_importer_process["settings"]["prefix"];

        foreach($datasets as $key => $data) {

            $itemdata = $data["data"];


            $itemdata = buddyboss_importer_replace_domain($itemdata); // Smart Replace.

            // fix the serialize things
            /**
             * NO need to fix serialization now, as it is already taken care of, in search and replace routine.
             */
            /*foreach($itemdata as $k => $v) {
                if(is_serialized($itemdata[$k])){
                    $check = @unserialize($itemdata[$k]);
                    if(empty($check)){
                        $itemdata[$k] = $this->__recalcserializedlengths($itemdata[$k]);
                        $itemdata[$k] = serialize($itemdata[$k]);
                    }
                }
            }*/

            $itemdata = apply_filters( "buddyboss_importer_table_data", $itemdata, $this->table_name );

            $wpdb->insert($bb_importer_process["working_prefix"].$this->table_name,$itemdata);

            $bb_importer_process[$this->process]["imported_rows"]++;

        }

        $this->log = sprintf(__("%s %s imported.","bb-oneclick"),$bb_importer_process[$this->process]["imported_rows"],$this->table_label);

        //add to done list
        $bb_importer_process[$this->process]["done"][] = $bb_importer_process[$this->process]["left"][$file_key];

        $bb_importer_process[$this->process]["left"][$file_key] = null;
        unset($bb_importer_process[$this->process]["left"][$file_key]);


    }

    /*
     * Return the percentage of process.
     **/
    function get_percentage() {
        global $bb_importer_process;

        $total = count($bb_importer_process[$this->process]["left"])+count($bb_importer_process[$this->process]["done"]);
        $done =  count($bb_importer_process[$this->process]["done"]);
        $percentage =  ($done / $total) * 100;
        return $percentage;
    }

    /*
     * Return the Log
     **/

    function log() {

        return $this->log;

    }

    function __recalcserializedlengths($sObject) {

       $__ret =preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $sObject );

       $val = unserialize($__ret);
       return stripslashes_deep($val);
       //return $__ret;
    }


}


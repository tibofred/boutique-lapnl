<?php
/*
 * Importer Class for Files.
 **/

class buddyboss_importer_files{

        private $log = "";
        private $process = "files";
        function __construct() {

        }

        /**
         * this will tell how many files to load on each request
         */
        function load_limits() {
            return 10;
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

                $files = glob($working_dir.'/data/files/*.json');

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


            //$start = microtime(true);
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

            $files = $datasets["files"]; //holds information of files to move.
            $files_location = $datasets["files"];

            $bb_importer_process[$this->process]["imported_posts"] += count($files);


            //copy all attachments
            $this->copy_files($files,$files_location);

            //$time_elapsed_secs = microtime(true) - $start;

            $this->log = sprintf(__("%s files imported. {$time_elapsed_secs}","bb-oneclick"),$bb_importer_process[$this->process]["imported_posts"],$bb_importer_process[$this->process]["imported_metas"],count($files));

            //add to done list
            $bb_importer_process[$this->process]["done"][] = $bb_importer_process[$this->process]["left"][$file_key];

            $bb_importer_process[$this->process]["left"][$file_key] = null;
            unset($bb_importer_process[$this->process]["left"][$file_key]);



        }

        /*
         * Copy all files.
         */
        function copy_files($files,$files_location) {

            global $bb_importer_process;

            $upload_dir = wp_upload_dir();

            $data_directory = trailingslashit($bb_importer_process["working_dir"]).'data/files/';

            foreach($files as $key => $value) {

                $source = $files_location[$key]; // get the saved file name.
                $source = trailingslashit($data_directory).$source;
                $destination = trailingslashit($upload_dir["basedir"]).$key;
                mkdir(dirname($destination),0777,true); //create dir recursive before copy.

                // copy($source,$destination);
                rename($source,$destination); //Will be more faster

            }

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


}


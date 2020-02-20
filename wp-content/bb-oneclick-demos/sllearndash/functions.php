<?php
/*
 * Declare the buddyboss importer variables.
 **/
function buddyboss_importer_variables_restore() {
    global $bb_importer_process;
    @session_start();
    $bb_importer_process = $_SESSION["bb_importer_process"];
}

function buddyboss_importer_variables_save() {
    global $bb_importer_process;
    $_SESSION["bb_importer_process"] = $bb_importer_process;
}
/*
 * Allow to add multiple rows.
 * Credit.
 * Source: https://github.com/mirzazeyrek/wp-multiple-insert
 **/
function wp_insert_rows($row_arrays = array(), $wp_table_name) {
    global $wpdb;
    $wp_table_name = esc_sql($wp_table_name);
    // Setup arrays for Actual Values, and Placeholders
    $values = array();
    $place_holders = array();
    $query = "";
    $query_columns = "";

    $query .= "INSERT INTO {$wp_table_name} (";

            foreach($row_arrays as $count => $row_array)
            {

                foreach($row_array as $key => $value) {

                    if($count == 0) {
                        if($query_columns) {
                        $query_columns .= ",".$key."";
                        } else {
                        $query_columns .= "".$key."";
                        }
                    }

                    $values[] =  $value;

                    if(is_numeric($value)) {
                        if(isset($place_holders[$count])) {
                        $place_holders[$count] .= ", '%d'";
                        } else {
                        $place_holders[$count] .= "( '%d'";
                        }
                    } else {
                        if(isset($place_holders[$count])) {
                        $place_holders[$count] .= ", '%s'";
                        } else {
                        $place_holders[$count] .= "( '%s'";
                        }
                    }
                }
                        // mind closing the GAP
                        $place_holders[$count] .= ")";
            }

    $query .= " $query_columns ) VALUES ";

    $query .= implode(', ', $place_holders);

    if($wpdb->query($wpdb->prepare($query, $values))){
        return true;
    } else {
        return false;
    }

}

/*
 * this function will return the new id
 **/
function buddyboss_importer_get_id($old_id,$table,$type="") {
    global $wpdb;

    if(!empty($type)) {
        $row = $wpdb->get_row($wpdb->prepare("SELECT new_id FROM {$wpdb->prefix}bb_importer WHERE old_id=%s AND table_name=%s AND type=%s",$old_id,$table,$type));
    } else {
        $row = $wpdb->get_row($wpdb->prepare("SELECT new_id FROM {$wpdb->prefix}bb_importer WHERE old_id=%s AND table_name=%s",$old_id,$table
            ));
    }

    if(!empty($row)) {
        return $row->new_id;
    }

    return 0;
}

/*
 * add unique id on the file.
 **/
function buddyboss_importer_unique_filename($path) {
    $filename = basename($path);
    if(strpos($path,"/") === false) { //its already a file.
        return uniqid()."-".$filename;
    }
    return dirname($path)."/".uniqid()."-".$filename;
}

function buddyboss_importer_unique_post_name($value) {
        global $wpdb;

        if($value == "inherit") {   return $value;  } // we don't want to make this text to unqiue.
        if($value == "") {   return $value;  } // don't want to give something unqiue to empty value.

        $query = $wpdb->prepare("SELECT ID FROM {$wpdb->prefix}posts where post_name=%s",$value);
        $result = $wpdb->get_results($query);
        $available = (empty($result))?false:true;
        $i = 1;

        while($available) {
            $value2 = $value.$i;
            $query = $wpdb->prepare("SELECT ID FROM {$wpdb->prefix}posts where post_name=%s",$value2);
            $result = $wpdb->get_results($query);
            $available = (empty($result))?false:true;

            if(!$available) { // Its not available !
                $value = $value2;
                break;
            }

            $i++;
        }

        return $value;
    }

function buddyboss_importer_get_plugin_dir() {
    $dir = wp_upload_dir();
    $dir = trailingslashit(dirname($dir["basedir"]))."plugins";
    $dir = trailingslashit($dir);
    return $dir;
}
function buddyboss_importer_get_themes_dir() {
    $dir = wp_upload_dir();
    $dir = trailingslashit(dirname($dir["basedir"]))."themes";
    $dir = trailingslashit($dir);
    return $dir;
}

function buddyboss_importer_is_json_string($string) {
 json_decode($string);
 return (json_last_error() == JSON_ERROR_NONE);
}

/**
 * Replaces the demo url with real
 * @param  string $string
 * @return [type]
 */
function buddyboss_importer_replace_domain($string) {
    global $bb_importer_process;

    $url = $bb_importer_process["settings"]["url"];
    $url = str_replace(array("https://","http://","www."),'',$url);
    $home_url = home_url();
    $home_url = str_replace(array("https://","http://","www."),'',$home_url);

    $replacer = new Better_SnReplace();
    
    return $replacer->recursive_unserialize_replace( $url, $home_url, $string );
}

if( !class_exists( 'Better_SnReplace' ) ){
/**
 * A helper class for recursively doing search replace through arrays, objects and serialized strings.
 */
class Better_SnReplace {
    /**
	 * Take a serialised array and unserialise it replacing elements as needed and
	 * unserialising any subordinate arrays and performing the replace on those too.
	 *
	 * @param string $from       String we're looking to replace.
	 * @param string $to         What we want it to be replaced with
	 * @param array  $data       Used to pass any subordinate arrays back to in.
	 * @param bool   $serialised Does the array passed via $data need serialising.
	 *
	 * @return array	The original array with all elements replaced as needed.
	 */
	public function recursive_unserialize_replace( $from = '', $to = '', $data = '', $serialised = false ) {

		// some unserialised data cannot be re-serialised eg. SimpleXMLElements
		try {

            if ( is_string( $data ) && is_serialized( $data ) ) {
                $unserialized = maybe_unserialize( $data );
				$data = $this->recursive_unserialize_replace( $from, $to, $unserialized, true );
			}

			elseif ( is_array( $data ) ) {
				$_tmp = array( );
				foreach ( $data as $key => $value ) {
					$_tmp[ $key ] = $this->recursive_unserialize_replace( $from, $to, $value, false );
				}

				$data = $_tmp;
				unset( $_tmp );
			}

			// Submitted by Tina Matter
			elseif ( is_object( $data ) ) {
				// $data_class = get_class( $data );
				$_tmp = $data; // new $data_class( );
				$props = get_object_vars( $data );
				foreach ( $props as $key => $value ) {
					$_tmp->$key = $this->recursive_unserialize_replace( $from, $to, $value, false );
				}

				$data = $_tmp;
				unset( $_tmp );
			}

			else {
				if ( is_string( $data ) ) {
					$data = $this->str_replace( $from, $to, $data );

				}
			}

			if ( $serialised ){
                return serialize( $data );
            }

		} catch( Exception $error ) {
		}

		return $data;
	}
    
    /**
	 * Wrapper for regex/non regex search & replace
	 *
	 * @param string $search
	 * @param string $replace
	 * @param string $string
	 * @param int $count
	 *
	 * @return string
	 */
	public function str_replace( $search, $replace, $string, &$count = 0 ) {
		if( function_exists( 'mb_split' ) ) {
			return self::mb_str_replace( $search, $replace, $string, $count );
		} else {
			return str_replace( $search, $replace, $string, $count );
		}
	}
    
    /**
	 * Replace all occurrences of the search string with the replacement string.
	 *
	 * @author Sean Murphy <sean@iamseanmurphy.com>
	 * @copyright Copyright 2012 Sean Murphy. All rights reserved.
	 * @license http://creativecommons.org/publicdomain/zero/1.0/
	 * @link http://php.net/manual/function.str-replace.php
	 *
	 * @param mixed $search
	 * @param mixed $replace
	 * @param mixed $subject
	 * @param int $count
	 * @return mixed
	 */
	public static function mb_str_replace( $search, $replace, $subject, &$count = 0 ) {
		if ( ! is_array( $subject ) ) {
			// Normalize $search and $replace so they are both arrays of the same length
			$searches = is_array( $search ) ? array_values( $search ) : array( $search );
			$replacements = is_array( $replace ) ? array_values( $replace ) : array( $replace );
			$replacements = array_pad( $replacements, count( $searches ), '' );

			foreach ( $searches as $key => $search ) {
				$parts = mb_split( preg_quote( $search ), $subject );
				$count += count( $parts ) - 1;
				$subject = implode( $replacements[ $key ], $parts );
			}
		} else {
			// Call mb_str_replace for each subject in array, recursively
			foreach ( $subject as $key => $value ) {
				$subject[ $key ] = self::mb_str_replace( $search, $replace, $value, $count );
			}
		}

		return $subject;
	}
}
}

function buddyboss_array_earch($array, $key, $value)
{
    $results = array();

    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, buddyboss_array_earch($subarray, $key, $value));
        }
    }

    return $results;
}

/**
 * When its final error
 */
function buddyboss_importer_return_final_error($error) {
    $GLOBALS["bb_importer_process"] = null;
    unset($GLOBALS["bb_importer_process"]);
    wp_send_json_error($error);
}

/**
* @credit http://www.thecodify.com/php/repair-a-serialized-array/
* Extract what remains from an unintentionally truncated serialized string
*
* Example Usage:
*
* the native unserialize() function returns false on failure
* $data = @unserialize($serialized); // @ silences the default PHP failure notice
* if ($data === false) // could not unserialize
* {
*   $data = repairSerializedArray($serialized); // salvage what we can
* }
*
* $data contains your original array (or what remains of it).

* @param string The serialized array
*/

function buddyboss_repairSerializedArray($serialized)
{
    $tmp = preg_replace('/^a:\d+:\{/', '', $serialized);
    return buddyboss_repairSerializedArray_R($tmp); // operates on and whittles down the actual argument
}

/**
* The recursive function that does all of the heavy lifing. Do not call directly.
* @param string The broken serialzized array
* @return string Returns the repaired string
*/
 function buddyboss_repairSerializedArray_R(&$broken)
{
    // array and string length can be ignored
    // sample serialized data
    // a:0:{}
    // s:4:"four";
    // i:1;
    // b:0;
    // N;
    $data       = array();
    $index      = null;
    $len        = strlen($broken);
    $i          = 0;

    while(strlen($broken))
    {
        $i++;
        if ($i > $len)
        {
            break;
        }

        if (substr($broken, 0, 1) == '}') // end of array
        {
            $broken = substr($broken, 1);
            return $data;
        }
        else
        {
            $bite = substr($broken, 0, 2);
            switch($bite)
            {
                case 's:': // key or value
                    $re = '/^s:\d+:"([^"]*)";/';
                    if (preg_match($re, $broken, $m))
                    {
                        if ($index === null)
                        {
                            $index = $m[1];
                        }
                        else
                        {
                            $data[$index] = $m[1];
                            $index = null;
                        }
                        $broken = preg_replace($re, '', $broken);
                    }
                break;

                case 'i:': // key or value
                    $re = '/^i:(\d+);/';
                    if (preg_match($re, $broken, $m))
                    {
                        if ($index === null)
                        {
                            $index = (int) $m[1];
                        }
                        else
                        {
                            $data[$index] = (int) $m[1];
                            $index = null;
                        }
                        $broken = preg_replace($re, '', $broken);
                    }
                break;

                case 'b:': // value only
                    $re = '/^b:[01];/';
                    if (preg_match($re, $broken, $m))
                    {
                        $data[$index] = (bool) $m[1];
                        $index = null;
                        $broken = preg_replace($re, '', $broken);
                    }
                break;

                case 'a:': // value only
                    $re = '/^a:\d+:\{/';
                    if (preg_match($re, $broken, $m))
                    {
                        $broken         = preg_replace('/^a:\d+:\{/', '', $broken);
                        $data[$index]   = buddyboss_repairSerializedArray_R($broken);
                        $index = null;
                    }
                break;

                case 'N;': // value only
                    $broken = substr($broken, 2);
                    $data[$index]   = null;
                    $index = null;
                break;
            }
        }
    }

    return $data;
}

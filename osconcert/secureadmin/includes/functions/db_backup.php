<?php
/*
  $Id: db_backup.php,v 1.4.1 2012/03/15 JanZ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2008 osCommerce

  Released under the GNU General Public License
*/


/**
 * Returns a string that represents the mysql field type
 *
 * @param mysqli_resource $result The result resource that is being evaluated. This result comes from a call to mysql_query().
 * @param integer $field_offset The numerical field offset. The field_offset starts at 0. If field_offset does not exist, an error of level E_WARNING is also issued.
 */
function mysqli_field_type( $result , $field_offset ) {
    static $types;

    $type_id = mysqli_fetch_field_direct($result,$field_offset)->type;

    if (!isset($types))
    {
        $types = array();
        $constants = get_defined_constants(true);
        foreach ($constants['mysqli'] as $c => $n) if (preg_match('/^MYSQLI_TYPE_(.*)/', $c, $m)) $types[$n] = $m[1];
    }

    return array_key_exists($type_id, $types)? $types[$type_id] : NULL;
}

/**
 * Returns a string that represents the mysql field flags
 *
 * @param mysqli_resource $result The result resource that is being evaluated. This result comes from a call to mysql_query().
 * @param integer $field_offset The numerical field offset. The field_offset starts at 0. If field_offset does not exist, an error of level E_WARNING is also issued.
 */
function mysqli_field_flags( $result , $field_offset ) {
    static $flags;

    // Get the field directly
    $flags_num = mysqli_fetch_field_direct($result,$field_offset)->flags;

    if (!isset($flags))
    {
        $flags = array();
        $constants = get_defined_constants(true);
        foreach ($constants['mysqli'] as $c => $n) if (preg_match('/MYSQLI_(.*)_FLAG$/', $c, $m)) if (!array_key_exists($n, $flags)) $flags[$n] = $m[1];
    }

    $result = array();
    foreach ($flags as $n => $t) if ($flags_num & $n) $result[] = $t;

    $return = implode(' ', $result);
    $return = str_replace('PRI_KEY','PRIMARY_KEY',$return);
    $return = strtolower($return);

    return $return;
}

function mysqli_field_name($result, $field_offset)
{
    $properties = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($properties) ? $properties->name : false;
}


function osc_microtime_float() {
    list($usec, $sec) = explode(" ",microtime());
    $unix_time = (float)$usec + (float)$sec;
    return $unix_time;
}

// from phpMyAdmin, named PMA_backquote there
function osc_backquote($a_name, $do_it = true) {
    if (! $do_it) {
        return $a_name;
    }

    if (is_array($a_name)) {
         $result = array();
         foreach ($a_name as $key => $val) {
             $result[$key] = osc_backquote($val);
         }
         return $result;
    }

    // '0' is also empty for php :-(
    if (strlen($a_name) && $a_name !== '*') {
        return '`' . str_replace('`', '``', $a_name) . '`';
    } else {
        return $a_name;
    }
} // end of the 'osc_backquote()' function

function osc_unbackquote($quoted_string) {

    if (is_array($quoted_string)) {
         $result = array();
         foreach ($quoted_string as $key => $val) {
             $result[$key] = osC_unbackquote($val);
         }
         return $result;
    }

        if (substr($quoted_string, 0, 1) === '`' && substr($quoted_string, -1, 1) === '`') {
             $unquoted_string = substr($quoted_string, 1, -1);
             return $unquoted_string;
         }
    return $quoted_string;
}

// function for selecting tables to backup
  function tep_select_multi_table($select_array) {
    $string = '';
    for ($i=0; $i < sizeof($select_array); $i++) {
      ($i == 0 ? $break = '' : $break = '<br />'. "\n");
      $string .= $break . '<input type="checkbox" name="dbtable[]" id="dbtable' . $i . '" value="' . trim($select_array[$i]) . '"';
      if (trim($select_array[$i]) == TABLE_SESSIONS || trim($select_array[$i]) == TABLE_WHOS_ONLINE) {
        $string .= ' checked="checked" /> <span style="color:red; border-bottom:1px dotted red;"><acronym title="' . TEXT_INFO_TABLE_STRUCTURE_ONLY . '">' . $select_array[$i] . '</acronym></span>';      
      } else {
        $string .= ' checked="checked" /> ' . $select_array[$i];
      }
    } 
    return $string;
  }

// function for selecting tables to restore
  function tep_select_multi_table_restore($select_array) {
    $string = '';
    for ($i=0; $i < sizeof($select_array); $i++) {
      ($i == 0 ? $break = '' : $break = '<br />'. "\n");
      $string .= $break . '<input type="checkbox" name="dbtable[]" id="dbtable' . $i . '" value="' . trim($select_array[$i]) . '"';
        $string .= ' checked="checked" /> ' . $select_array[$i];
    } 
    return $string;
  }

function osc_gzip($directory, $file_in, $delete_file = false, $level = 6) {
  $in_file = $directory . $file_in;
  $out_file = $directory . $file_in . '.gz';
  if (!file_exists ($in_file) || !is_readable ($in_file)) {
    return false;
  }
  if (file_exists($out_file)) {
    return false;
  }
  $fin_file = fopen($in_file, "rb");
  if (!$fout_file = gzopen($out_file, "wb".$level)) {
    return false;
  }

  while (!feof ($fin_file)) {
   $buffer = fread($fin_file, 8192); // 8 kB is maximum value
   gzwrite($fout_file, $buffer, 8192);
  }

  fclose($fin_file);
  gzclose($fout_file);
  if ($delete_file == true) {
    unlink($in_file);
  }
  return true;
}

function osc_gunzip($directory, $file_in, $delete_file = false) {
  $in_file = $directory . $file_in;
  $out_file = substr($in_file, 0, -3);
  if (!file_exists ($in_file) || !is_readable ($in_file)) {
    return false;
  }
  if (file_exists($out_file)) {
    return false;
  }
  $fin_file = gzopen($in_file, "rb");
  if (!$fout_file = fopen($out_file, "wb")) {
    return false;
  }

  while (!gzeof ($fin_file)) {
   $buffer = gzgets($fin_file, 8192);
   fputs($fout_file, $buffer, 8192);
  }

  gzclose($fin_file);
  fclose($fout_file);
  if ($delete_file == true) {
    unlink($in_file);
  }
  return true;
}

function osc_get_rows_in_table($table) {
// could have used a query for table status and look for 'Rows' here but for ISAM db's it
// makes no difference and for InnoDB tables it is warned by MySQL it can be very inaccurate
// so a count query is the safest
  if (tep_not_null($table)) {
  $status_rows_query = tep_db_query("select count(*) as total from " . osc_backquote($table) . "");
  $status_rows = tep_db_fetch_array($status_rows_query);
  return $status_rows['total'];
  } else {
    return false;
  }
}

function osc_rebuild_sess_whos($read_from) {
// this function first gets the structure of the tables sessions and whos_online, 
// drops them and then recreates them
// after a restore you don't want old shopping carts being revived
  $create_table_query = tep_db_query("show create table " . osc_backquote(TABLE_WHOS_ONLINE));
  $create_table_result = tep_db_fetch_array($create_table_query);
  $create_table = $create_table_result[1];
  $create_table_str_length = strlen($create_table);
  $pos_closing_bracket = strrpos($create_table, ')');
// remove " ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1" like stuff
  $create_table = substr($create_table, 0, $pos_closing_bracket+1);
  $create_table .= ';';
  tep_db_query("drop table if exists `" . TABLE_WHOS_ONLINE . "`");
  tep_db_query($create_table);
  
// now table sessions
  $create_table_query = tep_db_query("show create table " . osc_backquote(TABLE_SESSIONS));
  $create_table_result = tep_db_fetch_array($create_table_query);
  $create_table = $create_table_result[1];
  $create_table_str_length = strlen($create_table);
  $pos_closing_bracket = strrpos($create_table, ')');
// remove " ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1" like stuff
  $create_table = substr($create_table, 0, $pos_closing_bracket+1);
  $create_table .= ';';
  tep_db_query("drop table if exists `" . TABLE_SESSIONS . "`");
  tep_db_query($create_table);

  tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'DB_LAST_RESTORE'");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " values (NULL, 'Last Database Restore', 'DB_LAST_RESTORE', '" . $read_from . "', 'Last database restore file', '6', '', '', now(), '', '')");
}

// code for osc_restore_from_file() largely taken from bigdump.php
// http://www.ozerov.de/bigdump.php
function osc_restore_from_file($file = '', $file_offset = 0, $time_to_stop_restore, $tables_to_restore = '', $restore_all_tables = 1, $skip_query, $remove_table_from_tables_to_restore = '') {
    define ('DATA_CHUNK_LENGTH',16384);  // How many characters are read per time
    $query = '';
    $queries = 0;
// Allowed comment delimiters: lines starting with these strings will be dropped
    $comment[] = '#';           // Standard comment lines are dropped by default
    $comment[] = '-- ';
    $comment[] = '/*!';
    $in_parentheses = false;
    $error = false;
    $stop_restore = false; // after all tables requested have been restored we can quit
    if ($restore_all_tables == 1) {
      $skip_query = false;
     } else {
// no queries to restore tables until the first drop table statement is encountered
// in which a table in the array $tables_to_restore is found but not in the middle
// of a table restore of course ($offset > 0)
       if ($file_offset == 0) {
         $skip_query = true;
       } 
     }
    $time_now = osc_microtime_float();

    if (!$fd = @fopen($file, 'rb')) {
      return array('status' => 'error', 'description' => sprintf(ERROR_FILE_CANNOT_BE_OPENED_FOR_READING, $file), 'file_offset' => $file_offset);
    }
// go to end of file to see what the maximum offset is
    if (@fseek($fd, 0, SEEK_END) == 0) { // on success returns zero
      $filesize = ftell($fd);
    } else {
      return array('status' => 'error', 'description' => sprintf(ERROR_FILE_SEEKING_FILESIZE, $file), 'file_offset' => $file_offset);
      $error = true;
    }
    if ($file_offset > $filesize) {
      return array('status' => 'error', 'description' => sprintf(ERROR_FILEOFFSET_LARGER_THAN_FILESIZE, $file), 'file_offset' => $file_offset);
      $error = true;
    }
// set the file pointer back to where we want to start
    fseek($fd, $file_offset);
    while (($time_now < $time_to_stop_restore && $stop_restore == false) || $query != "") {

// Read the whole next line

      $dumpline = "";
      while (!feof($fd) && substr ($dumpline, -1) != "\n") {
          $dumpline .= fgets($fd, DATA_CHUNK_LENGTH);
      }
      if ($dumpline === "") break;
      
// Skip comments and blank lines only if NOT in parentheses

      if (!$in_parentheses) {
        $skipline = false;
        reset($comment);
        foreach ($comment as $comment_value) {
          if (!$in_parentheses && (trim($dumpline) == "" || strpos($dumpline, $comment_value) === 0)) {
            $skipline = true;
            break;
          }
        }
        if ($skipline) {
          continue;
        }
      }
// Remove double back-slashes from the dumpline prior to count the quotes ('\\' can only be within strings)
      
      $dumpline_deslashed = str_replace("\\\\","", $dumpline);

// Count ' and \' in the dumpline to avoid query break within a text field ending by ;
// Please don't use double quotes ('"')to surround strings, it wont work

      $parentheses = substr_count($dumpline_deslashed, "'") - substr_count($dumpline_deslashed, "\\'");
      if ($parentheses % 2 != 0) {
        $in_parentheses = !$in_parentheses;
      }

// Add the line to query

      $query .= $dumpline;

// Execute query if end of query detected (; as last character) AND NOT in parentheses

      if (preg_match("/;$/",trim($dumpline)) && !$in_parentheses) {	
      
      if ($restore_all_tables == 0) {
// case insensitive search to see if the query starts with DROP TABLE or drop table
// we look for the start of the sql for one table: drop table [if exists] name_of_table;
// where name_of_table can be in backticks like `name_of_table` or not
        if (preg_match('/^drop table/i', $query)) {
          if (isset($remove_table_from_tables_to_restore) && tep_not_null($remove_table_from_tables_to_restore)) {
            $key_of_table_name = array_search($remove_table_from_tables_to_restore, $tables_to_restore);
            unset($tables_to_restore[$key_of_table_name]);
            if (sizeof($tables_to_restore) == 0) {
// found the next drop table, no more tables to restore, set file pointer to end
// because we are finished
              $stop_restore = true;
            }
          }
          $find_table_name = trim($query); // remove line endings
          $find_table_name = preg_replace('/^drop table/i', '', $find_table_name); //str_ireplace is PHP5 only
          $find_table_name = preg_replace('/if exists/i', '', $find_table_name);
          $find_table_name = trim($find_table_name); // remove spaces
          $find_table_name = substr($find_table_name, 0, -1); // remove ; at the end
          $find_table_name = osc_unbackquote($find_table_name); // remove backticks if present
          if (in_array($find_table_name, $tables_to_restore)) {
            $skip_query = false;
            $remove_table_from_tables_to_restore = $find_table_name; // delete from $tables_to_restore
            // on next find of drop table
          } else {
            $skip_query = true;
          }
        } // end if (preg_match('/^drop table/i', $query))
      } // end if ($restore_all_tables == 0)

        if ($skip_query == false) {
          $result = tep_db_query(trim($query));
            if ($result == false) {
            echo '<pre>';
            print_r($query);
            return array('status' => 'error', 'description' => ERROR_DATABASE_RESTORE_QUERY, 'file_offset' => $file_offset);
            $error = true;
            break;
            }
          } // end if ($skip_query == false)
        $queries++;
          if ($queries%25 == 0) {
            $time_now = osc_microtime_float(); // only check the time once every 25 queries
          }
        $query="";
      } // end if (preg_match(";$",trim($dumpline)) && !$in_parentheses)
    } // end while ($time_now < $time_to_stop_restore etc.
    
// Get the current file position

  if ($error == false) {
      $file_offset = ftell($fd);
    if (!$file_offset) {
      return array('status' => 'error', 'description' => ERROR_CANNOT_READ_FILE_POINTER, 'file_offset' => $file_offset);
      $error = true;
    }
  }

    fclose($fd);
      if ($file_offset == $filesize || $stop_restore == true) {
        return array('status' => 'success', 'description' => SUCCESS_DATABASE_RESTORED, 'file_offset' => $file_offset);
      } else {
      return array('status' => 'partial', 'description' => sprintf(TEXT_PROGRESS_OF_RESTORE,  round(($file_offset * 100/$filesize),1)), 'file_offset' => $file_offset, 'tables_to_restore' => $tables_to_restore, 'restore_all' => $restore_all_tables, 'skip_query' => (int)$skip_query, 'remove_table_from_tables_to_restore' => $remove_table_from_tables_to_restore);
      }
}

    function sanitize_name($filename) {
      if (tep_not_null($filename)) {
// replace spaces with underscores
        $filename = str_replace(" ","_", $filename); 
// only very simple file names (to give no problems with url's)
        $filename = preg_replace("/[^_A-Za-z0-9-\.]/i", '', $filename);
        return $filename;
      } else {
        return false;
      }
    }


    function osc_CreateString($p_iChrs) {
      if (!is_Numeric($p_iChrs)) {
        $p_iChrs = 10;
      }

// initialising the values
      $sReturn = ''; // initialise the string.
      $aInteger = range('0','9'); // array 0-9
      $aAlpha = range('a','z'); // array a-z
      $aAlphaC = range('A','Z'); // array A-Z
    
// make a new array with numbers, lower and upper case letters
      $aChrs = array_merge($aInteger, $aAlpha, $aAlphaC);
       
// generate the random string
      for ($cnt=0; $cnt<$p_iChrs; $cnt++) {
        $sReturn .= $aChrs[rand(0,count($aChrs)-1)];
      }    
      return $sReturn;
    }
    
    function randomize_name($prefix = 'zzz', $filename = '', $no_of_random_chars = 4) {
      if (tep_not_null($filename)) {
        $random_string = osc_CreateString($no_of_random_chars);
        // db_osc_20080218212206.sql becomes something like upl_r8We_db_osc_20080218212206.sql
        $filename = $prefix . '_' . $random_string . '_' . $filename;
        return $filename;
      } else {
        return false;
      }
    }

// http://www.php.net/manual/en/function.ini-get.php    
  function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val{strlen($val)-1});
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }
    return $val;
  }

  function osc_is_disabled($function) {
    $disabled_functions = explode(',', ini_get('disable_functions'));
    foreach ($disabled_functions as $key => $disabled_function) {
      if (trim(strtolower($disabled_function)) == $function) {
        return true;
      }
    }
    return false;
  }
?>
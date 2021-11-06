<?php

/*

  

  Released under the GNU General Public License

    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	
//osc code
	
      if (isset($_GET['action']) && $_GET['action'] == 'download')
      {
        $extension = substr($_GET['file'], -3);

        if ( ($extension == 'zip') || ($extension == '.gz') || ($extension == 'sql') || ($extension == 'log') ) 
        {
			 require ('includes/configure.php');           
          if ($fp = @fopen(DIR_FS_BACKUP . $_GET['file'], 'rb')) 
          {
            $buffer = fread($fp, filesize(DIR_FS_BACKUP . $_GET['file']));
            fclose($fp);

            if ($extension == 'log') header('content-type: text/plain; charset: utf-8');
            else header('Content-type: application/x-octet-stream');
            header('Content-disposition: attachment; filename=' . $_GET['file']);

            echo $buffer;
			 }
			 else 
			 {
			 	print '<script>newwin = window.open("","","height=100,width=400");newwin.document.writeln("<h3>Error<br>Could not open file for reading</h3>");</script>'; 	
          }
          exit; // must exit here as includes above
        } 
      }
//end
  require('includes/application_top.php');
  require_once(DIR_WS_FUNCTIONS . 'db_backup.php');


  
 // $server_date = getServerDate(true);	
 if(empty($_GET['listing'])){
 	$listing = '';}
	else
	{ $listing = $_GET['listing'];}
	
  $listing_link = '';
    if (isset($listing) && tep_not_null($listing)) {
      $listing_link = '&listing=' . $listing;
    }

$startTime = osc_microtime_float();

$safe_mode_setting = (@ini_get('safe_mode') == 'On' || (@ini_get('safe_mode') === 1)) ? true : false;
// $safe_mode_setting = true; // for testing

// exec is used with the gzip and zip functions, if exec is disabled you still have a problem
if ($safe_mode_setting == false) {
  if (osc_is_disabled('exec') == true) {
    $safe_mode_setting = true;
  }
}
// Rarely max_execution_time falsely returns 0 (cli confusion?) which will cause failure, so set to default then
if ($max_script_runtime = (ini_get('max_execution_time'))) ; else $max_script_runtime = 30;

$safety_margin = 5; // margin to be kept till end of max_execution_time
// this should be more than the time it takes to write 500 rows to file and display the reload page
$comma_linefeed = ',' . "\n";

function showsize($bytes) {
  	$sz = ' KMGTP';
  	$factor = floor((strlen($bytes) - 1) / 3);
  	return round($bytes / pow(1024, $factor ), ($factor > 1 ? 2 : 0) ) . ' ' . ($factor ? @$sz[$factor] . 'B' : 'Bytes');
}  
  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) 
    {
      case 'email':
      	if (isset($_GET['file']) && tep_not_null($_GET['file']))
      	{
				$filename = $_GET['file'];         	
         	require(DIR_WS_INCLUDES . 'mail_backups.php');
         	if (send_backup ($filename)) $messageStack->add(sprintf(SUCCESS_EMAIL, $filename), 'success');
         	else $messageStack->add(sprintf(ERROR_EMAIL_FAILED, $filename), 'error');
			}
			//echo showsize(memory_get_peak_usage(true));
        break;    	
      case 'forget':
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'DB_LAST_RESTORE'");

        $messageStack->add_session(SUCCESS_LAST_RESTORE_CLEARED, 'success');

        tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
        break;
      case 'backupnow':
// if not a single table was selected stop here
        if (!isset($_POST['dbtable'])) {
          $messageStack->add_session(ERROR_BACKUP_NO_TABLES_SELECTED, 'error');

          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          break; // just in case
        }
// keep track of already backed up tables
        if (isset($_POST['prevtable'])) {
          $backed_up_tables_array = $_POST['prevtable'];
        }
        $counter_of_tables_backed_up_now = 0;
            // from phpMyAdmin and other sources 
            $search = array("\x00", "\x0a", "\x0d", "\x1a"); //\x08\\x09, not required
            $replace = array('\0', '\n', '\r', '\Z');

//        tep_set_time_limit(0); // let's call that with a positive number when we need it 
        $tables = array();
        $tables_query = tep_db_query('show tables');
        while ($tables_result = tep_db_fetch_array($tables_query)) {
        // results are returned as [Tables_in_name_of_database] => name_of_table
           foreach($tables_result as $tables_result_name) {
            $tables[] = $tables_result_name;
           }
        }
        tep_db_free_result($tables_query);

        if (isset($_POST['skipheader']) && $_POST['skipheader'] == 'yes') {
          if (isset($_GET['file']) && file_exists(DIR_FS_BACKUP . $_GET['file']) && is_writable(DIR_FS_BACKUP . $_GET['file']) && substr($_GET['file'], -3 == 'sql')) {
            $backup_file = $_GET['file'];

            if (!$fp = fopen(DIR_FS_BACKUP . $backup_file, 'a')) {
             die ("Couldn't open backup file " . $backup_file . ", sorry.");
            }
          } else {
            $messageStack->add_session(ERROR_BACKUP_NO_BACKUP_FILE, 'error');

            tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          }
        } // end if (isset($_POST['skip_header']) ...
        else { // not a resume of backup but a start of backup
        $no_of_tables = count($tables);
        $no_of_tables_for_backup = count($_POST['dbtable']);
        if ((int)$no_of_tables_for_backup == 1 && tep_not_null($_POST['dbtable'][0])) {
          $prefix_file_name = trim($_POST['dbtable'][0]);
          $prefix_file_name = (string)$prefix_file_name . '_'; // convert to ASCII here?
        } elseif ($no_of_tables > $no_of_tables_for_backup ) {
            $prefix_file_name = 'partial_db_';
        } else {
            $prefix_file_name = 'db_';       
        }
        $backup_file = $prefix_file_name . DB_DATABASE . '-' . date('YmdHi') . '.sql';
        $fp = fopen(DIR_FS_BACKUP . $backup_file, 'w');

        $schema = '# osConcert: Thanks to: osCommerce, Open Source E-Commerce Solutions' . "\n" .
                  '# http://www.oscommerce.com' . "\n" .
                  '#' . "\n" .
                  '# Database Backup For ' . STORE_NAME . "\n" .
                  '# Copyright (c) ' . date('Y') . ' ' . STORE_OWNER . "\n" .
                  '#' . "\n" .
                  '# Database: ' . DB_DATABASE . "\n" .
                  '# Database Server: ' . DB_SERVER . "\n" .
                  '#' . "\n" .
                  '# Backup Date: ' . date(PHP_DATE_TIME_FORMAT) . "\n" .
// add which tables are in this backup
                  '# Backed up tables: ' . implode(', ', $_POST['dbtable']) . "\n\n";
        fputs($fp, $schema);
        } // end if else (isset($_POST['skip_header'])


        foreach ($tables as $table) {
// if $table was not selected do not add it
          if (!in_array($table, $_POST['dbtable'])) continue;
          $startSaveTable = (float)osc_microtime_float();
          
          $table_list = array();
          $fields_query = tep_db_query("show fields from " . osc_backquote($table));
          while ($fields = tep_db_fetch_array($fields_query)) {
            $table_list[] = $fields['Field'];
            }

        if (isset($_POST['current_table']) && $table == $_POST['current_table'] ) {
         // do not add the create schema, need to append insert into lines to file only
        } else {
		  //carts united
		  if($table == 'carts_united'){
		  $schema = 'drop view if exists ' . osc_backquote($table) . ';' . "\n";
		  if (!fp) { die ("No file handle open."); }
          fputs($fp, $schema);
		  }else{
          $schema = 'drop table if exists ' . osc_backquote($table) . ';' . "\n";
          $create_table_query = tep_db_query("show create table " . osc_backquote($table));
          $create_table_result = tep_db_fetch_array($create_table_query); 
		  if(array_key_exists( 'Create Table',$create_table_result)){
				$create_table = $create_table_result['Create Table'];
				$create_table_str_length = strlen($create_table);
				$pos_closing_bracket = strrpos($create_table, ')');
				$create_table = substr($create_table, 0, $pos_closing_bracket+1);
				
				$schema .= $create_table . ';' . "\n\n"; }
					if (!$fp) { die ("No file handle open."); }
					fputs($fp, $schema);
		  
		  }//end carts united exception
        } // end if/else (isset($_POST['current_table'])...

// dump the data
          if ( ($table != TABLE_SESSIONS ) && ($table != TABLE_WHOS_ONLINE) && ($table != 'carts_united')) {
            $limit = '';
            if (isset($_POST['current_table']) && $table == $_POST['current_table']) {
            $offset = 0;
            $max_rows = (int)osc_get_rows_in_table($table); // false => 0
            $row_offset_table = 'row_offset_' . $table;
              if (isset($_POST[$row_offset_table])) {
                $offset = (int)$_POST[$row_offset_table];
              }
              $limit = " limit " . $offset . ", " . $max_rows;
            }
            $rows_query = tep_db_query("select " . implode(',', osc_backquote($table_list)) . " from " . osc_backquote($table) . $limit);
            $number_of_rows = tep_db_num_rows($rows_query);
            // Checks whether the field is an integer or not
            $field_num = array();
            $fields_cnt = mysqli_num_fields($rows_query);
            
               for ($j = 0; $j < $fields_cnt; $j++) {
                 $field_set[$j] = osc_backquote(mysqli_field_name($rows_query, $j));
                 $type = mysqli_field_type($rows_query, $j);
                 if ($type == 'tinyint' || $type == 'smallint' || 
                   $type == 'mediumint' || $type == 'int' ||
                   $type == 'bigint') {
                   $field_num[$table_list[$j]] = true;
                 } else {
                   $field_num[$table_list[$j]] = false;
                 }
               } // end for ($j = 0; $j < $fields_cnt; $j++)

            $insert_into = '';
            if ($number_of_rows > 0) {
            $schema = 'insert into ' . osc_backquote($table) . ' (' . implode(', ', osc_backquote($table_list)) . ') values ';
            $insert_into = $schema;
            fputs($fp, $schema); // add the first line
            $counter = 0;
/* $insert_counter keeps track of number of inserts. When it becomes 25
   500 rows have been dumped ($counter * $insert_counter) and then we check
   if we are running out of time. Another check is at the end of dumping a complete table 
*/
            $insert_counter = 0;
            $resume_dump = false;
            $schema = ''; // empty now
    
            while ($rows = tep_db_fetch_array($rows_query)) {
              if ($counter%20 == 0 && $counter > 0) { // start again with insert into after 20 rows
                $schema = $insert_into;
              }
              $schema .= '(';

              reset($table_list);
              while (list(,$i) = each($table_list)) {
                if (!isset($rows[$i]) || is_null($rows[$i])) { // is_null PHP >= PHP4.0.4
                  $schema .= 'NULL,';
                } elseif (tep_not_null($rows[$i])) {
                  if ($field_num[$i] && tep_not_null($rows[$i])) { // numeric field, filled
                    $schema .= $rows[$i] . ',';
                    } else {
                      $row = addslashes($rows[$i]);
                      $row = str_replace("\n#", "\n".'\#', $row);
                      $row = str_replace($search, $replace, $row);
                      $schema .= '\'' . $row . '\',';
                  }
                } else {
                  $schema .= '\'\',';
                }
              }

// replace last comma with a bracket
              $schema = substr_replace($schema,")",-1,1);
              $schema_array[] = $schema;
              $schema = ''; // empty now
              $counter++;
              if ($counter%20 == 0) { // when 20 rows have been retrieved add them to the file
                $to_add_lines = '';
                $to_add_lines = implode($comma_linefeed, $schema_array);
              if ($counter < $number_of_rows) {
                $to_add_lines .= ';' . "\n"; // add semi-colon and a line-break at end
              }
              fputs($fp, $to_add_lines);
              unset($schema_array); // empty the array
              $insert_counter++;
                if ($insert_counter%25 == 0) { // when 500 rows have been retrieved check time
                  $time_now = (float)osc_microtime_float();
                  $time_elapsed = $time_now - $startTime;
                  if (($time_elapsed + $safety_margin) > $max_script_runtime) {
                    if ($safe_mode_setting == true) {
                      $resume_dump = true;
/* later checked if the variable $halt_after_row is set
   if so it means the dump was halted while backing up a table
   it will hold the new offset for the next selection of rows
*/
                      $row_offset_table = 'row_offset_' . $table;
                      $current_table_bak_time_table = 'current_table_bak_time_' . $table;
                      if (isset($_POST[$row_offset_table])) {
                        $halt_after_row = $counter + (int)$_POST[$row_offset_table];
                      } else {
                        $halt_after_row = $counter;
                      } 
                      if (isset($_POST[$current_table_bak_time_table])) {
                        $backuptime[$table] = round($time_now - $startSaveTable + (float)$_POST[$current_table_bak_time_table],2);
                      } else {
                        $backuptime[$table] = round($time_now - $startSaveTable, 2);
                      }
                      // show progress in the top of the page
                      $progress = $table . ' : ' . round($halt_after_row/(isset($max_rows) ? $max_rows/100 : $number_of_rows/100),2) . '% - ' . $backuptime[$table] . ' (s)';
                      $messageStack->add(sprintf(WARNING_BACKUP_TABLE_UNDERWAY, $progress), 'warning');
                      break; // stop the retrieving of rows, resume dump later
                    } else {
                      tep_set_time_limit($max_script_runtime);
                      $startTime = osc_microtime_float(); // start counting again
                    }
                  }
                } // end if ($insert_counter%25 == 0)
              } // end if ($counter %20 == 0)
            } // end while ($rows = tep_db_fetch_array($rows_query))
            tep_db_free_result($rows_query);
              if ($resume_dump == false) {
// now add remaining rows to the backup file if counter hasn't reached 20 yet 
// and dump not interrupted
                $to_add_lines = '';
                if (count($schema_array) > 0) {
                  $to_add_lines = implode($comma_linefeed, $schema_array);
                  unset($schema_array); // empty the array
                } // end if (count($schema_array) > 0)
                $to_add_lines .= ';' . "\n\n"; // add semi-colon and two line-breaks at end
                fputs($fp, $to_add_lines);
              } // end ($resume_dump == false)
            } // end if (tep_db_num_rows($rows_query) > 0
          } // end if ( ($table != TABLE_SESSIONS ) && ($table != TABLE_WHOS_ONLINE) )

// if the table was empty the structure is still added to the backup
          if ($resume_dump == false) {
            $backed_up_tables_array[] = $table;
            $counter_of_tables_backed_up_now++;
            $time_now = (float)osc_microtime_float();
            $backuptime[$table] = $time_now - $startSaveTable;
// if the last table was dumped in several screens:
            if (isset($_POST['current_table']) && $table == $_POST['current_table']) {
              $current_table_bak_time_table = 'current_table_bak_time_' . $table;
              $backuptime[$table] += (float)$_POST[$current_table_bak_time_table];
            }
            $time_elapsed = (float)osc_microtime_float() - $startTime;
            if (($time_elapsed + $safety_margin > $max_script_runtime) && $counter_of_tables_backed_up_now < count($_POST['dbtable'])) {
// running out of time and still tables to backup
              $resume_dump = true;
            }
          } // end if ($resume_dump == false)
            if ($resume_dump == true && $safe_mode_setting == true) { // in safe mode: no set_time_limit available
            fclose($fp);
// now show HTML that will resume backup
// require(DIR_WS_INCLUDES . 'template_top.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<script language="JavaScript" type="text/javascript">
<!--
function submitForm()  {


frm = document.forms['backup'];
frm.submit();
return true;
}

function showCountDown(x){
  x--;

 if ( x > 0 ) {
   document.getElementById('countdown').innerHTML = x;
   window.setTimeout("showCountDown(" + x + ", '1')", 1000);
     if (x == 0 ) {
       submitForm();
     }
  } else { 
 toggle("continue_in_x_sec");
 toggle("submit_button");
 toggle("refresh_in_x_sec");
 showCountDown2("<?php echo $max_script_runtime - $safety_margin; ?>");
 submitForm();
 return true;
 }
}
function showCountDown2(x){
   document.getElementById('countdown2').innerHTML = x;
}

function toggle( targetId ){
  if (document.getElementById){
     target = document.getElementById( targetId );
        if (target.style.display == "none"){
          target.style.display = "";
        } else {
          target.style.display = "none";
       }
   }
}
//-->
</script>
<!-- body //-->

<div style="margin-left: 20px;">
<h1 class="pageHeading"><?php echo HEADING_TITLE; ?></h1>
<div id="continue_in_x_sec" style="display: block">
<p class="main" style="color: red; font-weight: bold;"><?php echo TEXT_CONTINUE_BACKUP_IN_X_SECONDS; ?></p>
</div>
<div id="refresh_in_x_sec" style="display: none">
<p class="main" style="color: #727272; font-weight: bold;"><?php echo TEXT_REFRESH_IN_X_SECONDS; ?></p>
</div>
<?php
echo tep_draw_form('backup', FILENAME_BACKUP, 'action=backupnow' . $listing_link .'&file=' . $backup_file). "\n";
echo tep_draw_hidden_field('compress', (isset($_POST['compress']) ? $_POST['compress'] : 'no')) . "\n";
echo tep_draw_hidden_field('download', (isset($_POST['download']) && $_POST['download'] == 'yes' ? $_POST['download'] : 'no')) . "\n";
echo tep_draw_hidden_field('skipheader', 'yes') . "\n";
if (isset($halt_after_row)) {
echo tep_draw_hidden_field('current_table', $table) . "\n";
echo tep_draw_hidden_field('row_offset_' . $table, $halt_after_row) . "\n";
echo '<input type="hidden" id="current_table_bak_time_' . $table . '" name="current_table_bak_time_' . $table . '" value="' . round($backuptime[$table],2) . '" />' . "\n";
}
?>
<table border="0">
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent"></td>
    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TABLE_NAME; ?></td>
    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TIME_USED; ?></td>
  </tr>
<?php
if (isset($backed_up_tables_array)) {
  foreach ($backed_up_tables_array as $key => $table_name) {
  echo '<tr>' . "\n";
  echo '<td style="margin: 1px;">' . tep_image(DIR_WS_ICONS . 'small_tick.gif') .'</td>' . "\n";
  echo '<td class="dataTableContent">' . $table_name . '</td>' . "\n";
  echo tep_draw_hidden_field('prevtable[]', $table_name) . "\n";
  echo '<td class="dataTableContent" align="right">';
  $time_variable = 'bak_time_' . $table_name;
  if (isset($backuptime[$table_name])) {
    echo round($backuptime[$table_name],2) . '</td>' . "\n";
    echo '<input type="hidden" id="bak_time_' . $table_name . '" name="bak_time_' . $table_name . '" value="'. round($backuptime[$table_name],2) . '" />' . "\n";
  } elseif (isset($_POST[$time_variable])) {
    echo $_POST[$time_variable] . '</td>' . "\n";
    echo '<input type="hidden" id="' . $time_variable . '" name="' . $time_variable . '" value="'. $_POST[$time_variable] . '" />' . "\n";
  } else {
    echo '&#160</td>' . "\n";
  }
  echo '</tr>' . "\n";
  }
}
if (isset($_POST['dbtable'])) {
  foreach ($_POST['dbtable'] as $key => $post_table) {
    if (is_array($backed_up_tables_array) && in_array($post_table, $backed_up_tables_array)) { 
      continue;
    } else {
      echo '<tr>' . "\n";
      echo '<td>' . tep_draw_separator('pixel_trans.gif', '11', '11') . '</td>' . "\n";
      echo '<td class="dataTableContent">' . $post_table . '</td>' . "\n";
      echo tep_draw_hidden_field('dbtable[]', $post_table) . "\n";
      echo '<td class="dataTableContent">&#160;</td>' . "\n";
      echo '</tr>' . "\n";
    }
  }
}
?>
</table>
<div id="submit_button" style="display:none">
<?php // if JavaScript submission fails: a fall-back
      echo '<p style="padding-top: 20px; margin-left: 200px;">' . tep_image_submit('button_submit.gif', IMAGE_SUBMIT) . '</p>' . "\n";
?>
</div>
</form>
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');
          exit; // otherwise the script will stay running
// end of HTML for restarting the backup (dump) procedure
            } elseif ($resume_dump == true && $safe_mode_setting == false) { // no safe mode settings: we can do a call for more time 
              tep_set_time_limit($max_script_runtime);
              $startTime = osc_microtime_float(); // start counting again
            } 
        } // end foreach ($table)
///////////////////////////////////////////////////////////////////////
		  //new code carts united must go at end of file or at least after the customers_basket tables
		  
		  
		  
		  
		  
$schema="DROP VIEW IF EXISTS `carts_united`;
CREATE ALGORITHM=UNDEFINED DEFINER=CURRENT_USER SQL SECURITY DEFINER VIEW `carts_united` AS select `cb`.`customers_basket_id` AS `customers_basket_id`,`cb`.`products_id` AS `products_id`,`cb`.`customers_id` AS `customers_id`,`cb`.`customers_basket_date_added` AS `customers_basket_date_added`,`cb`.`customers_basket_quantity` AS `customers_basket_quantity`,`cb`.`discount_id` AS `discount_id` from `customers_basket` `cb` union select `tb`.`customers_basket_id` AS `customers_basket_id`,`tb`.`products_id` AS `products_id`,`tb`.`customers_id` AS `customers_id`,`tb`.`customers_basket_date_added` AS `customers_basket_date_added`,`tb`.`customers_basket_quantity` AS `customers_basket_quantity`,`tb`.`discount_id` AS `discount_id` from `customers_temp_basket` `tb`;";


fputs($fp, $schema);
////////////////////////////////////////////////////////////////////////
        fclose($fp);
// determine total time used for backup
  $totalTime = 0.00;
  if (isset($backed_up_tables_array) && is_array($backed_up_tables_array)) {
    foreach ($backed_up_tables_array as $key => $table_name) {
    $time_variable = 'bak_time_' . $table_name;
      if (isset($_POST[$time_variable])) {
        $totalTime += $_POST[$time_variable];
      }
    }
  } // end if (isset($backed_up_tables_array) && is_array($backed_up_tables_array))
  if (isset($backuptime) && is_array($backuptime)) {
    foreach ($backuptime as $table_name => $time) {
        $totalTime += $time;
    }
  } // end if (isset($backuptime) && is_array($backuptime))
  $startTime = osc_microtime_float(); // starting point before compression

  if ($safe_mode_setting == false) {
        if (isset($_POST['download']) && ($_POST['download'] == 'yes')) {
        // download can take a long time
        tep_set_time_limit(0);
          switch ($_POST['compress']) {
            case 'gzip':
              if (@file_exists(LOCAL_EXE_GZIP) && $safe_mode_setting == false) {
                exec(LOCAL_EXE_GZIP . ' ' . DIR_FS_BACKUP . $backup_file);
                $backup_file .= '.gz';
              } elseif (@function_exists('gzwrite')) {
                $gzip_result = osc_gzip (DIR_FS_BACKUP, $backup_file, true);
                $backup_file .= '.gz';
                if (!$gzip_result) {
                  $messageStack->add_session(ERROR_ON_GZIP, 'error');
                }
              }
              break;
            case 'zip':
              exec(LOCAL_EXE_ZIP . ' -j ' . DIR_FS_BACKUP . $backup_file . '.zip ' . DIR_FS_BACKUP . $backup_file);
              unlink(DIR_FS_BACKUP . $backup_file);
              $backup_file .= '.zip';
          }
          header('Content-type: application/x-octet-stream');
          header('Content-disposition: attachment; filename=' . $backup_file);

          readfile(DIR_FS_BACKUP . $backup_file);
          unlink(DIR_FS_BACKUP . $backup_file);

          exit;
        } else {
// compress is pretty fast, about 25-35 times faster than making the backup itself, using PHP
          tep_set_time_limit($max_script_runtime);
          switch ($_POST['compress']) {
            case 'gzip':
              if (@file_exists(LOCAL_EXE_GZIP) && $safe_mode_setting == false) {
                exec(LOCAL_EXE_GZIP . ' ' . DIR_FS_BACKUP . $backup_file);
              } elseif (@function_exists('gzwrite')) {
                $gzip_result = osc_gzip (DIR_FS_BACKUP, $backup_file, true);
                  if (!$gzip_result) {
                    $messageStack->add_session(ERROR_ON_GZIP, 'error');
                  } 
              }
              break;
            case 'zip':
              exec(LOCAL_EXE_ZIP . ' -j ' . DIR_FS_BACKUP . $backup_file . '.zip ' . DIR_FS_BACKUP . $backup_file);
              unlink(DIR_FS_BACKUP . $backup_file);
              break;
          }
          $time_elapsed = (float)osc_microtime_float() - $startTime + $totalTime;
          $messageStack->add_session(SUCCESS_DATABASE_SAVED . ' - ' . round($time_elapsed, 2) . ' (s)', 'success');
        }
        // $listing_link contains as first character & => remove
        $listing_link = substr($listing_link, 1, strlen($listing_link) -1);
        tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
  } else { // $safe_mode_setting == true
          switch ($_POST['compress']) {
            case 'gzip':
              $messageStack->add_session(SUCCESS_DATABASE_SAVED . ' - ' . round($totalTime, 2) . ' (s)', 'success');
              tep_redirect(tep_href_link(FILENAME_BACKUP, 'action=gzip&file=' . $backup_file . '&download=' . $_POST['download'] . '&delete_file=true' . $listing_link));
            break;
          } // end switch ($_POST['compress'])
          $messageStack->add_session(SUCCESS_DATABASE_SAVED . ' - ' . round($totalTime, 2) . ' (s)', 'success');
          $listing_link = substr($listing_link, 1, strlen($listing_link) -1);
          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
  }
        break;
      case 'restorenow':
        @tep_set_time_limit(0); // not really needed

          $read_from = $_GET['file'];

          if (file_exists(DIR_FS_BACKUP . $_GET['file'])) {
            $restore_file = DIR_FS_BACKUP . $_GET['file'];
            $extension = substr($_GET['file'], -3);

            if ( ($extension == 'sql') || ($extension == '.gz') || ($extension == 'zip') ) {
// $listing_link contains as first character & => remove
              $listing_link = substr($listing_link, 1, strlen($listing_link) -1);
              switch ($extension) {
                case 'sql':
                  $restore_from = $restore_file;
                  if (isset($_GET['rm_all']) && $_GET['rm_all'] == 1) {
                  // in case of an uploaded file
                    $remove_raw = true;
                  } else {
                  $remove_raw = false;
                  }
                  break;
                case '.gz':
                  $restore_from = substr($restore_file, 0, -3);
                  if (file_exists($restore_from)) {
                    $messageStack->add_session(sprintf(ERROR_UNCOMPRESSED_FILE_ALREADY_EXISTS, $restore_from), 'error');
                    tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
                  } else {
                    if (@file_exists(LOCAL_EXE_GUNZIP) && $safe_mode_setting == false) {
                      exec(LOCAL_EXE_GUNZIP . ' ' . $restore_file . ' -c > ' . $restore_from);
                    } elseif (@function_exists('gzgets')) {
                      $gunzip_result = osc_gunzip ('', $restore_file, false);
                      if (!$gunzip_result) {
                        $messageStack->add_session(ERROR_ON_GUNZIP, 'error');
                      } 
                    }
                  } // end if/else (file_exists($restore_from))
                  if (isset($_GET['rm_all']) && $_GET['rm_all'] == 1) {
                  // in case of an uploaded file
                    unlink($restore_file);
                  }
                  $remove_raw = true;
                  break;
                case 'zip':
                  $restore_from = substr($restore_file, 0, -4);
                  if (file_exists($restore_from)) {
                    $messageStack->add_session(sprintf(ERROR_UNCOMPRESSED_FILE_ALREADY_EXISTS, $restore_from), 'error');
                    tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
                  } else {
                    exec(LOCAL_EXE_UNZIP . ' ' . $restore_file . ' -d ' . DIR_FS_BACKUP);
                    $remove_raw = true;
                  } // end if/else (file_exists($restore_from))
                  if (isset($_GET['rm_all']) && $_GET['rm_all'] == 1) {
                  // in case of an uploaded file
                    unlink($restore_file);
                  }
               } // end switch ($extension)

              if (isset($restore_from) && file_exists($restore_from)) {
                if (isset($_GET['file_offset'])) {
                  $file_offset = (int)$_GET['file_offset'];
                } else {
                  $file_offset = 0;
                }
                if (isset($_POST['rm_raw'])) {
                  $remove_raw = (int)$_POST['rm_raw']; // keep initial setting - true: 1, false: 0
                }
                if (isset($_REQUEST['restore_all'])) { // can be either POST or GET (from restorelocal)
                  $restore_all_tables = (int)$_REQUEST['restore_all'];
                }               
                if (isset($_POST['sess_whos'])) {
                  $sess_whos = 1; // at first a checkbox: on = isset, else not set
                }
                if (isset($_POST['skip_query']) && $_POST['skip_query'] == 1) {
                  $skip_query = $_POST['skip_query'];
                } else {
                  $skip_query = 0;
                }
                if (isset($_POST['remove_table_from_tables_to_restore'])) {
                  $remove_table_from_tables_to_restore = $_POST['remove_table_from_tables_to_restore'];
                } else {
                  $remove_table_from_tables_to_restore = '';
                }

// determine which tables to restore from backup
                 $tables_to_restore = array();
                if (!isset($restore_all_tables) || $restore_all_tables == 0) { 
                  if (!isset($_POST['dbtable'])) {
// no tables selected in the list with checkboxes, try again ;-)
                  $messageStack->add_session(ERROR_RESTORE_NO_TABLES_SELECTED, 'error');
                  tep_redirect(tep_href_link(FILENAME_BACKUP, 'action=restore&file=' . $read_from . '&' . $listing_link));
                  } else {
                    if (isset($_POST['no_of_tables']) && $_POST['no_of_tables'] == count($_POST['dbtable'])) {
                      $restore_all_tables = 1;
					  
                    } else {
                      $restore_all_tables = 0;
                      foreach ($_POST['dbtable'] as $key => $table) {
                      $tables_to_restore[] = trim($table);
                      }
                    }
                  } // end if/else (!isset($_POST['dbtable']))
                } // end if/else (!isset($restore_all_tables))
                  $time_to_stop_restore = $startTime + ($max_script_runtime - $safety_margin);
                  $restore_result = osc_restore_from_file($restore_from, $file_offset, $time_to_stop_restore, $tables_to_restore, $restore_all_tables, $skip_query, $remove_table_from_tables_to_restore);
                  if (is_array($restore_result) && $restore_result['status'] == 'error') {
                    $messageStack->add_session($restore_result['description'], 'error');
                    tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
                  } elseif (is_array($restore_result) && $restore_result['status'] == 'partial') {
                    $filename = str_replace(DIR_FS_BACKUP, '', $restore_from);
                    if (isset($restore_result['tables_to_restore'])) {
                    $tables_left_for_restore = $restore_result['tables_to_restore'];
                    }
                    if (isset($restore_result['restore_all'])) {
                      $restore_all_tables = $restore_result['restore_all'];
                    }
                    if (isset($restore_result['skip_query'])) {
                      $skip_query = $restore_result['skip_query'];
                    }
                    if (isset($restore_result['remove_table_from_tables_to_restore'])) {
                      $remove_table_from_tables_to_restore = $restore_result['remove_table_from_tables_to_restore'];
                    }
                    $messageStack->add($restore_result['description'], 'warning');
// now show HTML that does the reload with file offset to continue restore
 // require(DIR_WS_INCLUDES . 'template_top.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<script language="JavaScript" type="text/javascript">

 //   window.jQuery || document.write("<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'>\x3C/script>");
	
	
function submitForm()  {
//alert('ready?');
frm = document.forms['restore'];
frm.submit();
return true;
}

function showCountDown(x){
  x--;

 if ( x > 0 ) {
   document.getElementById('countdown').innerHTML = x;
   window.setTimeout("showCountDown(" + x + ", '1')", 1000);
     if (x == 0 ) {
       submitForm();
     }
  } else { 
 toggle("continue_in_x_sec");
 toggle("refresh_in_x_sec");
 toggle("submit_button");
 showCountDown2("<?php echo $max_script_runtime - $safety_margin; ?>");
 submitForm();
 return true;
 }
}
function showCountDown2(x){
   document.getElementById('countdown2').innerHTML = x;
}

function toggle( targetId ){
  if (document.getElementById){
     target = document.getElementById( targetId );
        if (target.style.display == "none"){
          target.style.display = "";
        } else {
          target.style.display = "none";
       }
   }
}
//-->
</script>

<div style="margin-left: 20px;">
<h1 class="pageHeading"><?php echo HEADING_TITLE; ?></h1>
<div id="continue_in_x_sec" style="display: block">
<p class="main" style="color: red; font-weight: bold;"><?php echo TEXT_CONTINUE_RESTORE_IN_X_SECONDS; ?></p>
</div>
<div id="refresh_in_x_sec" style="display: none">
<p class="main" style="color: #727272; font-weight: bold;"><?php echo TEXT_REFRESH_IN_X_SECONDS; ?></p>
</div>
<?php
echo tep_draw_form('restore', FILENAME_BACKUP, 'file=' . $filename . '&file_offset=' . (int)$restore_result['file_offset'] . '&action=restorenow&' . $listing_link). "\n";
$time_now = (float)osc_microtime_float();
$time_elapsed = $time_now - $startTime;
if (isset($_POST['time_used']) && tep_not_null($_POST['time_used'])) {
  $time_used_so_far = (float)$_POST['time_used'] + $time_elapsed;
  $time_used_so_far = round($time_used_so_far,2);
} else {
  $time_used_so_far = round($time_elapsed,2);
}

echo '<p class="pageHeading">Time used for restore now: ' . $time_used_so_far . '</p>';
$remove_raw = (int)$remove_raw;
$remove_raw = (string)$remove_raw; // otherwise no value will be echo'ed for the hidden value
// if it is zero. tep_not_null finds zero null if it is not a string
echo tep_draw_hidden_field('rm_raw', $remove_raw) . "\n";
if (isset($sess_whos)) {
  echo tep_draw_hidden_field('sess_whos', $sess_whos) . "\n";
}
echo '<input type="hidden" id="time_used" name="time_used" value="'. $time_used_so_far . '" />' . "\n";
if (isset($tables_left_for_restore) && is_array($tables_left_for_restore)) {
  foreach($tables_left_for_restore as $key => $table_name) {
    echo tep_draw_hidden_field('dbtable[]', $table_name). "\n";
  }
}
if (isset($restore_all_tables)) {
  $restore_all_tables = (string)$restore_all_tables; // get 0 displayed in a hidden field
  echo tep_draw_hidden_field('restore_all', $restore_all_tables);
}
if (isset($skip_query)) {
  $skip_query = (int)$skip_query;
  $skip_query = (string)$skip_query; // get 0 displayed in a hidden field
  echo tep_draw_hidden_field('skip_query', $skip_query);
}
if (isset($remove_table_from_tables_to_restore)) {
  echo tep_draw_hidden_field('remove_table_from_tables_to_restore', $remove_table_from_tables_to_restore);
}
// if JavaScript submission fails: a fall-back
?>
<div id="submit_button" style="display:none">
<?php      echo '<p style="padding-top: 20px; text-align: center;">' . tep_image_submit('button_submit.gif', IMAGE_SUBMIT,'id="click_me"') . '</p>' . "\n";
?>
</div>
</form>
<script type="text/javascript">  
      submitForm();
</script>
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');
          exit; // otherwise the script will stay running
// end of HTML for restarting the restore procedure
                  } elseif (is_array($restore_result) && $restore_result['status'] == 'success') {
                      $time_now = (float)osc_microtime_float();
                      $time_elapsed = $time_now - $startTime;
                      if (isset($_POST['time_used']) && tep_not_null($_POST['time_used'])) {
                        $time_used_so_far = (float)$_POST['time_used'] + $time_elapsed;
                      } else {
                        $time_used_so_far = $time_elapsed;
                      }

                    $messageStack->add_session($restore_result['description'], 'success');
                    $messageStack->add_session(sprintf(TEXT_TIME_NEEDED_FOR_RESTORE, round($time_used_so_far,2)), 'success');

//                    if (isset($sess_whos)) {
//                      tep_session_close();
//// if the table structures of sessions and whos_online were in the backup this should be superfluous
//// but since they may not have been in the backup better leave this in
//// if it is not set, then even with emptying the table sessions the admin does not lose the session
//// ???
//                      osc_rebuild_sess_whos($read_from);
//                    }

                    if (isset($remove_raw) && ($remove_raw == true)) {
                      unlink($restore_from);
                    }
                    tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
                  } // end elseif (is_array($restore_result) && $restore_result['status'] == 'success')
              } else {
                $messageStack->add_session(sprintf(ERROR_PROBLEM_WITH_RESTORE_FILE, $restore_from), 'error');
                tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
              } // end if/else (isset($restore_from) && file_exists($restore_from))
            } // end if ( ($extension == 'sql') || ...
          } else {
            $messageStack->add_session(sprintf(ERROR_FILE_DOES_NOT_EXIST, $_GET['file']), 'error');
            tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          }
          break;
      case 'restorelocalnow':
// $listing_link contains as first character & => remove
        $listing_link = substr($listing_link, 1, strlen($listing_link) -1);
        if (is_uploaded_file($_FILES['sql_file']['tmp_name']) && ($_FILES['sql_file']['error']) == 0) { 
          $uploaded_filename = sanitize_name($_FILES['sql_file']['name']);
          $uploaded_filename = randomize_name('upl', $uploaded_filename, 4);

          if (file_exists($uploaded_filename)) { 
// remote chance with the addition of 4 random characters to the file name...
            $messageStack->add_session(sprintf(ERROR_FILE_ALREADY_EXISTS, $uploaded_filename), 'error');
            tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          } else if (!preg_match("/\.(sql|gz|zip)$/i", $uploaded_filename)) {
	    $messageStack->add_session(ERROR_FILE_EXTENSION_NOT_SQL_GZ_ZIP, 'error');
            tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          } else if (!@move_uploaded_file($_FILES['sql_file']['tmp_name'], DIR_FS_BACKUP . $uploaded_filename)) {
            $messageStack->add_session(sprintf(ERROR_FILE_CANNOT_BE_MOVED, $_FILES['sql_file']['name']), 'error');
          } else {
// succesful upload redirect to restore
            tep_redirect(tep_href_link(FILENAME_BACKUP, 'action=restorenow&file=' . $uploaded_filename . '&rm_all=1&restore_all=1&' . $listing_link));
          }
        } else { // an error occured
        if (isset($_FILES['sql_file']['error'])) {
          if ($_FILES['sql_file']['error'] > 0 && $_FILES['sql_file']['error'] <= 8) {
            $error_msg = constant(PHP_FILE_UPLOAD_ERROR_ . $_FILES['sql_file']['error']);
            $messageStack->add_session($error_msg, 'error');
            tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          } elseif ($_FILES['sql_file']['error'] === 0) {
            $error_msg = PHP_FILE_UPLOAD_ERROR_0 . ' ' . PHP_FILE_UPLOAD_ERROR_UNKNOWN;
            $messageStack->add_session($error_msg, 'error');
            tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          }
        } else { 
          $messageStack->add_session(PHP_FILE_UPLOAD_ERROR_UNKNOWN, 'error');
          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
        } // end if/else (isset($_FILES['sql_file']['error']))
      } // end else { // an error occured
      tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link)); // for good measure
      break;
      case 'download':
        $extension = substr($_GET['file'], -3);

        if (!( ($extension == 'zip') || ($extension == '.gz') || ($extension == 'sql') || ($extension == 'log') ) ) {
          
					$messageStack->add(ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE, 'error');
        }
        break;
      case 'deleteconfirm':
        if (strstr($_GET['file'], '..')) tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));

        tep_remove(DIR_FS_BACKUP . '/' . $_GET['file']);

        if (!$tep_remove_error) {
          
					$messageStack->add_session(strstr($_GET['file'], '.log') ? SUCCESS_LOG_DELETED : SUCCESS_BACKUP_DELETED, 'success');
        // $listing_link contains as first character & => remove
          $listing_link = substr($listing_link, 1, strlen($listing_link) -1);
          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
        }
        break;
      case 'gzip':
      // $listing_link contains as first character & => remove
        $listing_link = substr($listing_link, 1, strlen($listing_link) -1);
        if (is_object($messageStack) && isset($_GET['download'])) { // add message from database save
           foreach($messageStack->errors as $key => $error) {
           $split_error = explode('">&nbsp;', $error['text']);
           if (strstr($error['params'], "Success")) { // class="messageStackSuccess"
             $mS_result = 'success';
           } elseif (strstr($error['params'], "Warning")) {  // class="messageStackWarning"
             $mS_result = 'warning';
           } else {
             $mS_result = 'error';
           }
            $messageStack->add_session($split_error[1], $mS_result);
           }
        }

        if (!file_exists(DIR_FS_BACKUP . $_GET['file'])) {
          $messageStack->add_session(sprintf(ERROR_FILE_DOES_NOT_EXIST, $_GET['file']), 'error');
          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          exit;
        } elseif (substr($_GET['file'], -3) == '.gz') {
          $messageStack->add_session(sprintf(ERROR_COMPRESSED_FILE_ALREADY_EXISTS, $_GET['file']), 'error');
          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          exit;
        } elseif (file_exists(DIR_FS_BACKUP . $_GET['file'] . '.gz')) {
          $messageStack->add_session(sprintf(ERROR_FILE_ALREADY_EXISTS, $_GET['file'] . '.gz'), 'error');
          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          exit;
        } else {
        $startTime = (float)osc_microtime_float();
        if (@file_exists(LOCAL_EXE_GZIP) && $safe_mode_setting == false) {
                exec(LOCAL_EXE_GZIP . ' ' . DIR_FS_BACKUP . $_GET['file']);
              } elseif (@function_exists('gzwrite')) {
                $gzip_result = osc_gzip (DIR_FS_BACKUP, $_GET['file'], false);
                if (!$gzip_result) {
                $messageStack->add_session(ERROR_ON_GZIP, 'error');
                tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
                exit;
                } 
              } else {
                 $messageStack->add_session(ERROR_NO_GZIP_AVAILABLE, 'error');
                 tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
                 exit;
              }
// compression was succesfull
        if (isset($_GET['delete_file']) && $_GET['delete_file'] == 'true') {
          unlink(DIR_FS_BACKUP . $_GET['file']);
        }
          if (isset($_GET['download']) && $_GET['download'] == 'yes') {
            tep_redirect(tep_href_link(FILENAME_BACKUP, 'action=download&file=' . $_GET['file'] . '.gz&delete_file=true&' . $listing_link));
            exit;
          } else {
            $time_elapsed = (float)osc_microtime_float() - $startTime;
            $messageStack->add_session(sprintf(SUCCESS_GZIP_COMPRESS, $_GET['file']) . ' - ' . round($time_elapsed, 2) . ' (s)', 'success');
            tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
            exit;
          }
        } // end if/else (!file_exists(DIR_FS_BACKUP . $_GET['file']))
        break;
      case 'gunzip':
// $listing_link contains as first character & => remove
        $listing_link = substr($listing_link, 1, strlen($listing_link) -1);
        if (!file_exists(DIR_FS_BACKUP . $_GET['file'])) {
          $messageStack->add_session(sprintf(ERROR_FILE_DOES_NOT_EXIST, $_GET['file']), 'error');
          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          exit;
        } elseif (substr($_GET['file'], -3) != '.gz') {
          $messageStack->add_session(sprintf(ERROR_GZIP_FILE_NOT_VALID, $_GET['file']), 'error');
          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          exit;
        } elseif (file_exists(DIR_FS_BACKUP . substr($_GET['file'], 0, -3))) {
          $messageStack->add_session(sprintf(ERROR_FILE_ALREADY_EXISTS, substr($_GET['file'], 0, -3)), 'error');
          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          exit;
        } else {
        $startTime = (float)osc_microtime_float();
          $gunziped_file = substr($_GET['file'], 0, -3);
          if (@file_exists(LOCAL_EXE_GUNZIP) && $safe_mode_setting == false) {
             exec(LOCAL_EXE_GUNZIP . ' ' . DIR_FS_BACKUP . $_GET['file'] . ' -c > ' . DIR_FS_BACKUP . $gunziped_file);
          } elseif (@function_exists('gzgets')) {
            $gunzip_result = osc_gunzip (DIR_FS_BACKUP, $_GET['file'], false);
              if (!$gunzip_result) {
                 $messageStack->add_session(ERROR_ON_GUNZIP, 'error');
                  tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
                  exit;
               } 
           } else {
             $messageStack->add_session(ERROR_NO_GUNZIP_AVAILABLE, 'error');
                  tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
                  exit;
           }
           $time_elapsed = (float)osc_microtime_float() - $startTime;
           $messageStack->add_session(sprintf(SUCCESS_GUNZIP, $_GET['file']) . ' - ' . round($time_elapsed, 2) . ' (s)', 'success');
           tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
           exit;
        }
      break;
      case 'zip':
// $listing_link contains as first character & => remove
        $listing_link = substr($listing_link, 1, strlen($listing_link) -1);
        if (!file_exists(DIR_FS_BACKUP . $_GET['file'])) {
          $messageStack->add_session(sprintf(ERROR_FILE_DOES_NOT_EXIST, $_GET['file']), 'error');
          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          exit;
        } elseif (substr($_GET['file'], -4) == '.zip') {
          $messageStack->add_session(sprintf(ERROR_COMPRESSED_FILE_ALREADY_EXISTS, $_GET['file']), 'error');
          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          exit;
        } elseif (file_exists(DIR_FS_BACKUP . $_GET['file'] . '.zip')) {
          $messageStack->add_session(sprintf(ERROR_FILE_ALREADY_EXISTS, $_GET['file'] . '.zip'), 'error');
          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          exit;
        } else {
        $startTime = (float)osc_microtime_float();
        if (@file_exists(LOCAL_EXE_ZIP) && $safe_mode_setting == false) {
                exec(LOCAL_EXE_ZIP . ' -j ' . DIR_FS_BACKUP . $_GET['file'] . '.zip ' . DIR_FS_BACKUP . $_GET['file']);
                // unlink(DIR_FS_BACKUP . $_GET['file']);
                $time_elapsed = (float)osc_microtime_float() - $startTime;
                $messageStack->add_session(sprintf(SUCCESS_GZIP_COMPRESS, $_GET['file']) . ' - ' . round($time_elapsed, 2) . ' (s)', 'success');
                tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
                exit;
              } else {
                 $messageStack->add_session(ERROR_NO_ZIP_AVAILABLE, 'error');
                 tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
                 exit;
              }
        } // end if/else (!file_exists(DIR_FS_BACKUP . $_GET['file']))
        break;
      case 'unzip':
// $listing_link contains as first character & => remove
        $listing_link = substr($listing_link, 1, strlen($listing_link) -1);
        if (!file_exists(DIR_FS_BACKUP . $_GET['file'])) {
          $messageStack->add_session(sprintf(ERROR_FILE_DOES_NOT_EXIST, $_GET['file']), 'error');
          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          exit;
        } elseif (substr($_GET['file'], -4) != '.zip') {
          $messageStack->add_session(sprintf(ERROR_ZIP_FILE_NOT_VALID, $_GET['file']), 'error');
          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          exit;
        } elseif (file_exists(DIR_FS_BACKUP . substr($_GET['file'], 0, -4))) {
          $messageStack->add_session(sprintf(ERROR_FILE_ALREADY_EXISTS, substr($_GET['file'], 0, -4)), 'error');
          tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
          exit;
        } else {
        $startTime = (float)osc_microtime_float();
          if (@file_exists(LOCAL_EXE_UNZIP) && $safe_mode_setting == false) {
             exec(LOCAL_EXE_UNZIP . ' ' . DIR_FS_BACKUP . $_GET['file'] . ' -d ' . DIR_FS_BACKUP);
          } else {
             $messageStack->add_session(ERROR_NO_UNZIP_AVAILABLE, 'error');
             tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
             exit;
           }
           $time_elapsed = (float)osc_microtime_float() - $startTime;
           $messageStack->add_session(sprintf(SUCCESS_UNZIP, $_GET['file']) . ' - ' . round($time_elapsed, 2) . ' (s)', 'success');
           tep_redirect(tep_href_link(FILENAME_BACKUP, $listing_link));
           exit;
        } // end if/else (!file_exists(DIR_FS_BACKUP . $_GET['file']))
      break;
    } // end switch ($action)
  } // end if (tep_not_null($action))

// check if the backup directory exists
  $dir_ok = false;
  if (is_dir(DIR_FS_BACKUP)) {
    if (is_writeable(DIR_FS_BACKUP)) {
      $dir_ok = true;
    } else {
      $messageStack->add(ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE, 'error');
    }
  } else {
    
		$messageStack->add(ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
//  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<script language="JavaScript" type="text/javascript">
function flagCheckboxes(element) {
  var elementForm = element.form;
  var i = 0;

  for (i = 0; i < elementForm.length; i++) {
    if (elementForm[i].type == 'checkbox' && ( elementForm[i].name == 'dbtable[]' || elementForm[i].name == 'batchFlag')) {
      elementForm[i].checked = element.checked;
    }
  }
}
</script>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>

<!-- body_text //-->

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE ; ?></td>
            <td class="pageHeading" align="right" valign="middle"><!--<?php if(tep_not_null(FILENAME_STORE_BACKUP) && file_exists(FILENAME_STORE_BACKUP)) echo  '<a href="' . tep_href_link(FILENAME_STORE_BACKUP) . '" class="button_class">' . BOX_TOOLS_STORE_BACKUP . '</a><br />';  ?>--></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="headerBar">
                <td class="dataTableHeadingContent"><a href="<?php echo tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('listing','action', 'file')) . 'listing=name-asc'); ?>"><?php echo tep_image(DIR_WS_IMAGES . 'icon_up.gif', SORT_BY_NAME); ?></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('listing','action', 'file')) . 'listing=name-desc'); ?>"><?php echo tep_image(DIR_WS_IMAGES . 'icon_down.gif', SORT_BY_NAME_DESC); ?></a></td>
                <td class="dataTableHeadingContent" align="center"><a href="<?php echo tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('listing','action', 'file')) . 'listing=date-asc'); ?>"><?php echo tep_image(DIR_WS_IMAGES . 'icon_up.gif', SORT_BY_DATE); ?></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('listing','action', 'file')) . 'listing=date-desc'); ?>"><?php echo tep_image(DIR_WS_IMAGES . 'icon_down.gif', SORT_BY_DATE_DESC); ?></a></td>
                <td class="dataTableHeadingContent" align="right"><a href="<?php echo tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('listing','action', 'file')) . 'listing=size-asc'); ?>"><?php echo tep_image(DIR_WS_IMAGES . 'icon_up.gif', SORT_BY_SIZE); ?></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('listing','action', 'file')) . 'listing=size-desc'); ?>"><?php echo tep_image(DIR_WS_IMAGES . 'icon_down.gif', SORT_BY_SIZE_DESC); ?></a></td>
                <td class="dataTableHeadingContent" align="right" valign="bottom">&nbsp;</td>
              </tr>
               <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TITLE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_FILE_DATE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_FILE_SIZE; ?></td>
                <td class="dataTableHeadingContent" align="right" valign="bottom"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  if ($dir_ok == true) {
    $dir = dir(DIR_FS_BACKUP);
    $contents = array();
    $contents_date = array();
    while ($file = $dir->read()) {
      if (!is_dir(DIR_FS_BACKUP . $file) && in_array(substr($file, -3), array('zip', 'sql', '.gz')))  {
        $contents[] = array('file' => $file, 'date' => filemtime(DIR_FS_BACKUP . $file), 
        'size' => filesize(DIR_FS_BACKUP . $file));
      }
    }

    foreach ($contents as $key => $filedata) {
      $contents_size[$key] = $filedata['size'];
      $contents_file[$key] = $filedata['file'];
      $contents_date[$key] = $filedata['date'];
    }
    
// sort files as requested, by name, file name, date ascending or descending
    switch ($listing) {
      case "name-asc":
        array_multisort($contents_file, SORT_ASC, $contents);
      break;
      case "name-desc":
        array_multisort($contents_file, SORT_DESC, $contents);
      break;
      case "date-asc":
        array_multisort($contents_date, SORT_ASC, $contents);
      break;
      case "date-desc":
        array_multisort($contents_date, SORT_DESC, $contents);
      break;
      case "size-asc":
        array_multisort($contents_size, SORT_ASC, $contents);
      break;
      case "size-desc":
        array_multisort($contents_size, SORT_DESC, $contents);
      break;
      default:
// the old behaviour: new files last
        array_multisort($contents_date, SORT_ASC, $contents);
      break;
    }

    for ($i=0, $n=count($contents); $i<$n; $i++) {
      $entry = $contents[$i];

      $check = 0;

      if ((!isset($_GET['file']) || (isset($_GET['file']) && ($_GET['file'] == $entry['file']))) && !isset($buInfo) && ($action != 'backup') && ($action != 'restorelocal')) {
        $file_array['file'] = $entry['file'];
        $file_array['date'] = date(PHP_DATE_TIME_FORMAT, $entry['date']);
        
        $entry_filesize = $entry['size']; // filesize(DIR_FS_BACKUP . $entry);
// the number of bytes in Megabyte is ambiguous: see http://en.wikipedia.org/wiki/Megabyte
// 1024 * 1024 used here instead of 1000 * 1024
       $file_array['size'] = showsize($entry['size']);
       $file_array['table_list'] = TEXT_INFO_NO_INFORMATION;
        switch (substr($entry['file'], -3)) {
          case 'zip': $file_array['compression'] = 'ZIP'; break;
          case '.gz': $file_array['compression'] = 'GZIP'; 
          if ($fp = gzopen(DIR_FS_BACKUP . $entry['file'], 'r')) {
            while ( ! gzeof($fp) ) {
              $line = gzgets($fp, 4096);
              if (substr($line, 0, 1) != "#") break; // backed up tables are in head of file
              if (substr($line, 0, 19) == "# Backed up tables:") {
                $file_array['table_list'] = trim(substr($line, 19));
              }
            }
          } // if ($fp = gzopen($entry['file'], 'r')
          break;
          default: $file_array['compression'] = TEXT_NO_EXTENSION; 
          if ($fp = fopen(DIR_FS_BACKUP . $entry['file'], 'r')) {
            while ( ! feof($fp) ) {
              $line = fgets($fp, 4096);
              if (substr($line, 0, 1) != "#") break; // backed up tables are in head of file
              if (substr($line, 0, 19) == "# Backed up tables:") {
                $file_array['table_list'] = trim(substr($line, 19));
              }
            }
          } // if ($fp = fopen($entry['file'], 'r')
          break;
        }

        $buInfo = new objectInfo($file_array);
      }

      if (isset($buInfo) && is_object($buInfo) && ($entry['file'] == $buInfo->file)) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
      }
      $onclick_link = 'file=' . $entry['file'] . $listing_link;
      $flink = tep_href_link(FILENAME_BACKUP, $onclick_link);
?>
                <td class="dataTableContent" onClick="document.location.href='<?php echo  $flink; ?>'"><?php echo '<a href="' . tep_href_link(FILENAME_BACKUP, 'action=download&file=' . $entry['file'] . $listing_link) . '">' . tep_image(DIR_WS_ICONS . 'file_download.gif', ICON_FILE_DOWNLOAD) . '</a>&nbsp;' . $entry['file']; ?></td>
                <td class="dataTableContent" align="center" onClick="document.location.href='<?php echo  $flink; ?>'"><?php echo date(PHP_DATE_TIME_FORMAT, $entry['date']); ?></td>
                <td class="dataTableContent" align="right" onClick="document.location.href='<?php echo  $flink; ?>'">
                 <?php echo showsize($entry['size']); ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($buInfo) && is_object($buInfo) && ($entry['file'] == $buInfo->file)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $entry['file'] . $listing_link) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
    $dir->close();
  }

  if ($dir_ok == true) {
    $dir = dir(DIR_FS_BACKUP);
    $logs = array();
    while ($file = $dir->read()) {
      if (!is_dir(DIR_FS_BACKUP . $file) && in_array(substr($file, -3), array('log')))  {
        $logs[] = array('file' => $file, 'date' => filemtime(DIR_FS_BACKUP . $file), 
        'size' => filesize(DIR_FS_BACKUP . $file));
      }
    }
    if (sizeof($logs)) 
    {
?>    
					<tr class="headerBar">
                <td class="dataTableHeadingContent" colspan="4"><strong><span style="font-size: larger;">Logfiles</span></strong></td>
              </tr>	
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">Filename</td>
                <td class="dataTableHeadingContent" align="center">Date</td>
                <td class="dataTableHeadingContent" align="right">Size</td>
                <td class="dataTableHeadingContent" align="right">Delete</td>
              </tr>	
<?php 
	 	foreach($logs as $entry) {
	 		echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)"><td class="dataTableContent">' . '<a href="' . tep_href_link(FILENAME_BACKUP, 'action=download&file=' . $entry['file'] . $listing_link) . '" title="Download &amp; View Log">' . tep_image(DIR_WS_ICONS . 'file_download.gif', ICON_FILE_DOWNLOAD) . '&nbsp;' . $entry['file'] . '</a></td><td class="dataTableContent" align="center">' . date(PHP_DATE_TIME_FORMAT, $entry['date']) . '</td><td class="dataTableContent" align="right" >' . showsize($entry['size']) . '</td><td align="right"><a href="' . tep_href_link(FILENAME_BACKUP, 'action=deleteconfirm&file=' . $entry['file'] . $listing_link) . '">' . tep_image(DIR_WS_IMAGES . 'icons/cross.gif', 'Delete Log File') . '</a>&nbsp;</td></tr>';
	 	}
	 }		
    $dir->close();
  }  
  $test = '';
?>
              <tr>
                <td class="smallText" colspan="3"><?php echo TEXT_BACKUP_DIRECTORY . ' ' . DIR_FS_BACKUP; ?></td>
                <td align="right" class="smallText"><?php if ( ($action != 'backup') && (isset($dir)) ) echo $test . '<a href="' . tep_href_link(FILENAME_BACKUP, 'action=backup' . $listing_link) . '">' . tep_image_button('button_backup.gif', IMAGE_BACKUP) . '</a>'; if ( ($action != 'restorelocal') && isset($dir) ) echo '&nbsp;&nbsp;<br><br><a href="' . tep_href_link(FILENAME_BACKUP, 'action=restorelocal' . $listing_link) . '">' . tep_image_button('button_restore.gif', IMAGE_RESTORE) . '</a>'; ?></td>
              </tr>
<?php
  if (defined('DB_LAST_RESTORE')) {
?>
              <tr>
                <td class="smallText" colspan="4"><?php echo TEXT_LAST_RESTORATION . ' ' . DB_LAST_RESTORE . ' <a href="' . tep_href_link(FILENAME_BACKUP, 'action=forget' . $listing_link) . '">' . TEXT_FORGET . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  $info_heading = '<b>Backup Dated: ' . $buInfo->date . ' '. ($buInfo->table_list != TEXT_INFO_NO_INFORMATION ? ' (' . (substr_count($buInfo->table_list,',')+1) . ' tables)' : ''). '</b>';

  switch ($action) {
    case 'backup':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_BACKUP . '</b>');

      $contents = array('form' => tep_draw_form('backup', FILENAME_BACKUP, 'action=backupnow' . $listing_link));
      $contents[] = array('text' => TEXT_INFO_NEW_BACKUP);

      $contents[] = array('text' => '<br>' . tep_draw_radio_field('compress', 'no', true) . ' ' . TEXT_INFO_USE_NO_COMPRESSION);
      if ((@file_exists(LOCAL_EXE_GZIP) && $safe_mode_setting == false) || function_exists('gzwrite')) { 
      $contents[] = array('text' => '<br>' . tep_draw_radio_field('compress', 'gzip') . ' ' . TEXT_INFO_USE_GZIP);
      }
      if (@file_exists(LOCAL_EXE_ZIP) && $safe_mode_setting == false) {
      $contents[] = array('text' => tep_draw_radio_field('compress', 'zip') . ' ' . TEXT_INFO_USE_ZIP);
      }
      if ($dir_ok == true) {
        $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('download', 'yes') . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br><br>*' . TEXT_INFO_BEST_THROUGH_HTTPS);
      } else {
        $contents[] = array('text' => '<br>' . tep_draw_radio_field('download', 'yes', true) . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br><br>*' . TEXT_INFO_BEST_THROUGH_HTTPS);
      }
// get tables from db to select here
      $tables_query = tep_db_query('show tables');
      while ($tables_result = tep_db_fetch_array($tables_query)) {
        foreach ($tables_result as $table_results_name) {
        $tables_list_array[] = $table_results_name;
        }
      }

      $contents[] = array('align' => 'left', 'text' => '<input type="checkbox" name="batchFlag" id="batchFlag" checked="checked" onclick="flagCheckboxes(this);" />' . SELECT_DESELECT_ALL .'');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_backup.gif', IMAGE_BACKUP) . '&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP) . '">' . tep_image_button_cancel('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      $contents[] = array('align' => 'left', 'text' => tep_select_multi_table($tables_list_array));
      $contents[] = array('text' => '<hr />'."\n".'');
      $contents[] = array('align' => 'left', 'text' => '<input type="checkbox" name="batchFlag" id="batchFlag" checked="checked" onclick="flagCheckboxes(this);" />' . SELECT_DESELECT_ALL .'');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_backup.gif', IMAGE_BACKUP) . '&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP) . '">' . tep_image_button_cancel('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'restore':
      $heading[] = array('text' => $info_heading);

      $contents = array('form' => tep_draw_form('restore', FILENAME_BACKUP, 'action=restorenow&file=' . $buInfo->file . $listing_link));
      $contents[] = array('text' => tep_break_string(sprintf(TEXT_INFO_RESTORE, DIR_FS_BACKUP . (($buInfo->compression != TEXT_NO_EXTENSION) ? substr($buInfo->file, 0, strrpos($buInfo->file, '.')) : $buInfo->file), ($buInfo->compression != TEXT_NO_EXTENSION) ? TEXT_INFO_UNPACK : ''), 35, ' '));
      
      $contents[] = array('text' =>  '<br><input type="checkbox" name="sess_whos" id="sess_whos"' . (substr($buInfo->file, 0, 3) == 'db_' ? ' checked="checked"' : '') . ' /> ' . TEXT_INFO_EMPTY_SESSIONS_WHOSONLINE);
      if ($buInfo->table_list != TEXT_INFO_NO_INFORMATION) {
        $to_restore_tables_array = explode(',', $buInfo->table_list);
        $no_of_tables = count($to_restore_tables_array);
        $contents[] = array('align' => 'left', 'text' => '<br /><input type="checkbox" name="batchFlag" id="batchFlag" checked="checked" onclick="flagCheckboxes(this);" />' . SELECT_DESELECT_ALL . "\n" . '<hr />');
		      $contents[] = array('align' => 'center', 'text' => '<br>' .  tep_image_submit('button_restore.gif', IMAGE_RESTORE) . '</a>&nbsp;<br><a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file) . $listing_link .'">' . tep_image_button_cancel('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        $contents[] = array('align' => 'left', 'text' => tep_select_multi_table_restore($to_restore_tables_array));
        $contents[] = array('text' => '<hr />'."\n".'');
        $contents[] = array('align' => 'left', 'text' => '<input type="checkbox" name="batchFlag" id="batchFlag" checked="checked" onclick="flagCheckboxes(this);" />' . SELECT_DESELECT_ALL . tep_draw_hidden_field('no_of_tables', $no_of_tables) . '');
      } else {
        $contents[] = array('text' => tep_draw_hidden_field('restore_all', '1'));
      }
      // end if ($buInfo->table_list != TEXT_INFO_NO_INFORMATION)
      $contents[] = array('align' => 'center', 'text' => '<br>' .  tep_image_submit('button_restore.gif', IMAGE_RESTORE) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file) . $listing_link .'">' . tep_image_button_cancel('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'restorelocal':
      $post_max_size = return_bytes(ini_get('post_max_size')); // in bytes
      $upload_max_filesize = return_bytes(ini_get('upload_max_filesize')); // in bytes
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_RESTORE_LOCAL . '</b>');

      $contents = array('form' => tep_draw_form('restore', FILENAME_BACKUP, 'action=restorelocalnow' . $listing_link, 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_INFO_RESTORE_LOCAL . '<br><br>' . TEXT_INFO_BEST_THROUGH_HTTPS . tep_draw_hidden_field('MAX_FILE_SIZE', min($post_max_size, $upload_max_filesize)));
      $contents[] = array('text' => '<br>' . TEXT_INFO_MAX_FILE_SIZE_FOR_UPLOAD . '<b>' . ($upload_max_filesize < $post_max_size ? ini_get('upload_max_filesize') : ini_get('post_max_size')) . '</b>');
      $contents[] = array('text' => '<br>' . tep_draw_file_field('sql_file'));
      $contents[] = array('text' => TEXT_INFO_RESTORE_LOCAL_FILE);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_restore.gif', IMAGE_RESTORE) . '&nbsp;<br><a href="' . tep_href_link(FILENAME_BACKUP, $listing_link) . '">' . tep_image_button_cancel('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => $info_heading);

      $contents = array('form' => tep_draw_form('delete', FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=deleteconfirm' . $listing_link));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $buInfo->file . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . $listing_link) .'">' . tep_image_button_cancel('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($buInfo) && is_object($buInfo)) {
        $heading[] = array('text' => $info_heading);
        
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=restore' . $listing_link) . '">' . tep_image_button('button_restore.gif', IMAGE_RESTORE) . '</a><br><br> <a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=delete' . $listing_link) . '">' . tep_image_button_delete('button_delete.gif', IMAGE_DELETE) . '</a>');

        $gzip_available = false;
        $gunzip_available = false;
        $zip_available = false;
        $unzip_available = false;
        if ((@file_exists(LOCAL_EXE_GZIP) && $safe_mode_setting == false) || @function_exists('gzwrite')) {
          $gzip_available = true;
        }
        if ((@file_exists(LOCAL_EXE_GUNZIP) && $safe_mode_setting == false) || @function_exists('gzgets')) {
          $gunzip_available = true;
        }
        if (@file_exists(LOCAL_EXE_ZIP) && $safe_mode_setting == false) {
          $zip_available = true;
        }
        if (@file_exists(LOCAL_EXE_UNZIP) && $safe_mode_setting == false) {
          $unzip_available = true;
        }
        $default_text = '';
        if ($buInfo->compression == 'GZIP' && $gunzip_available == true) {
          $default_text .= ' <a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=gunzip' . $listing_link) . '">' . tep_image_button('button_gunzip.gif', IMAGE_GUNZIP) . '</a>';
        } elseif ($buInfo->compression == TEXT_NO_EXTENSION && $gzip_available == true) {
          $default_text .= ' <a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=gzip' . $listing_link) . '">' . tep_image_button('button_gzip.gif', IMAGE_GZIP) . '</a>';
        } elseif ($buInfo->compression == 'ZIP' && $unzip_available == true) {
          $default_text .= ' <a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=unzip' . $listing_link) . '">' . tep_image_button('button_unzip.gif', IMAGE_UNZIP) . '</a>';
        }
        if ($buInfo->compression == TEXT_NO_EXTENSION && $zip_available == true) {
          $default_text .= ' <a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=zip' . $listing_link) . '">' . tep_image_button('button_zip.gif', IMAGE_ZIP) . '</a>';
        }
        if (@file_exists(DIR_WS_INCLUDES . 'mail_backups.php')) {
          $default_text .= '<br /><br /><a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=email' . $listing_link) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL . ' ' . $buInfo->file) . '</a>';
        }
        $contents[] = array('align' => 'center', 'text' => $default_text);
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE . ' ' . $buInfo->date);
        $contents[] = array('text' => TEXT_INFO_SIZE . ' ' . $buInfo->size);
        $contents[] = array('text' => '<br>' . TEXT_INFO_COMPRESSION . ' ' . $buInfo->compression);
        $contents[] = array('text' => TEXT_INFO_TABLES_IN_BACKUP . ($buInfo->table_list != TEXT_INFO_NO_INFORMATION ? substr_count($buInfo->table_list,',')+1 : '') . '<br>' . $buInfo->table_list);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

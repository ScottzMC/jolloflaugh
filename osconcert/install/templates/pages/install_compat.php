<?php
/* 
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	http://www.osconcert.com

  	Copyright (c) 2020 osConcert 
	
	Released under the GNU General Public License 
*/ 
?>
<?php
  $www_location=isset($HTTP_POST_VARS['HTTP_WWW_ADDRESS'])?$HTTP_POST_VARS['HTTP_WWW_ADDRESS']:'';
  $web_service=isset($HTTP_POST_VARS['webservice'])?$HTTP_POST_VARS['webservice']:'0';
  if ($www_location==""){
	  $www_location = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER["SCRIPT_NAME"];
	  $www_location = substr($www_location, 0, strpos($www_location, 'install'));
  }
  
  $script_filename = getenv('PATH_TRANSLATED');
  if (empty($script_filename)) {
    $script_filename = getenv('SCRIPT_FILENAME');
  }
  $DB_SERVER=$DB_SERVER_USERNAME=$DB_SERVER_PASSWORD='';
  $action=(isset($HTTP_GET_VARS["action"])?$HTTP_GET_VARS["action"]:'');
  if (empty($script_filename)) {
	$script_filename = $_SERVER["SCRIPT_FILENAME"];
  }
  $script_filename = str_replace('\\', '/', $script_filename);
  $script_filename = str_replace('//', '/', $script_filename);

  $dir_fs_www_root_array = explode('/', dirname($script_filename));
  $dir_fs_www_root = array();
  for ($i=0, $n=sizeof($dir_fs_www_root_array)-1; $i<$n; $i++) {
    $dir_fs_www_root[] = $dir_fs_www_root_array[$i];
  }
  $dir_fs_www_root = implode('/', $dir_fs_www_root) . '/';
  
  	$last_test="";
	//$mysql_text='<b>MySQL Not detected at %s. Enter your MySQL server details.</b>';
	$mysql_text='<b>you do not have rights to view if MySQLi is installed or MySQLi may not be present at Localhost.</b>';
  	if ($action=="testemail"){
		$send_email=$HTTP_POST_VARS["email_address"];
		
		$subject = STORE_NAME . " Install - Test Email";
		$content = "Installer Compatibility Test Created on " . date('Y-m-d H:i:s') . "\n\n";
		$headers = "From: webmaster@osconcert.com\n";
		$headers .= "Return-Path: webmaster@osconcert.com\n";
		$headers .= "X-Mailer: osConcert \n"; 
		$sent=@mail($send_email, $subject, $content, $headers);
		if ($sent && trim($send_email)!=""){
			$last_test="<font color='blue'>Test Email Sent succesfully to ". $send_email ."</font>";
		} else {
			$last_test="<font color='red'>Failed to Send Test Email to '" . $send_email. "'</font>";
		}
    }
	$DB_SERVER=isset($HTTP_POST_VARS["DB_SERVER"])?$HTTP_POST_VARS["DB_SERVER"]:'localhost';
	$DB_SERVER_USERNAME=isset($HTTP_POST_VARS["DB_SERVER_USERNAME"])?$HTTP_POST_VARS["DB_SERVER_USERNAME"]:'';
	$DB_SERVER_PASSWORD=isset($HTTP_POST_VARS["DB_SERVER_PASSWORD"])?$HTTP_POST_VARS["DB_SERVER_PASSWORD"]:'';
	$server_exists=false;
	
	
	if (function_exists("mysqli_connect")){
		$connect=@mysqli_connect($DB_SERVER,$DB_SERVER_USERNAME,$DB_SERVER_PASSWORD);
		if ($connect) {
			$mysql_text='<b>MySqli detected </b>';
			$server_exists=true;
			mysqli_close($connect);
		} else {
			$mysql_text=sprintf($mysql_text,$DB_SERVER);
		}
	}
	$db_check=true;

	$show_db_params=true;


  $compat_link="install.php?mode=compat_test&action=testemail";
  $current_link="install.php?mode=compat_test";
  $compat_mysql_link="install.php?mode=compat_test&action=testdb";
  $show_continue=true;
  $require_load=true;
  $display_mode="exdir";
  require("compat_test_info.php");
 ?>		
<?php
/* 
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	https://www.osconcert.com

  	Copyright (c) 2020 osConcert
	
	osConcert Visual Seat Reservation
    Copyright (c) 2009 cartZone UK 
	
	Released under the GNU General Public License 
*/ 


// Set the level of error reporting
  //error_reporting(E_ALL & ~E_NOTICE);
  error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
  $script_filename = $_SERVER["SCRIPT_FILENAME"];

  $script_filename = str_replace('\\', '/', $script_filename);
  $script_filename = str_replace('//', '/', $script_filename);
  $dir_fs_www_root = dirname($script_filename) . "/";
  $dir_fs_www_root_top=str_replace("/install/","",$dir_fs_www_root);

// Check if register_globals is enabled.
// Since this is a temporary measure this message is hardcoded. The requirement will be removed before 2.2 is finalized.
 	if (!function_exists('ini_get')) {
  		exit('Installer Startup failed: function ini_get does not exist!');	
  	}
	//if (version_compare(phpversion(), "4.5.0", ">")===true && (strtolower(ini_get("register_long_arrays"))!="on" || strtolower(ini_get("register_long_arrays"))!="1")){
		if (version_compare(phpversion(), "4.5.0", ">")===true)
		{
		$HTTP_GET_VARS=$_GET;
		$HTTP_POST_VARS=$_POST;
		$HTTP_SERVER_VARS=$_SERVER;
		$HTTP_POST_FILES=$_FILES;
		$HTTP_ENV_VARS=$_ENV;
		if (isset($_COOKIE)) $HTTP_COOKIE_VARS=$_COOKIE;
		}
   if (!isset($_SERVER)) $_SERVER = &$HTTP_SERVER_VARS;
   
   $open_basedir=$include_path='';
      
   if (ini_get("open_basedir")) $open_basedir=";" . str_replace(":",";",ini_get("open_basedir")) .";";

   $include_path=";" . str_replace(":",";",ini_get("include_path")) . ";";
     
   if ($include_path && strpos($include_path,";.;")===false){
	//	exit('Installer Startup failed: current dir not found in include_path. Please add it!');
   }
  require('includes/english.php');
  require('includes/functions/general.php');
  require('includes/functions/database.php');
  require('includes/functions/html_output.php');
?>
<?php
/*
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	osCommRes, Services Oline 
	http://www.oscommres.com 
	Copyright (c) 2005 osCommRes 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License
*/


// Set flag that this is a parent file
	define( '_FEXEC', 1 );

  include('includes/application_top.php');
  
// Check download.php was called with proper GET parameters
  $order_id=tep_db_prepare_input($FREQUEST->getvalue('order'));
  
  if ($FREQUEST->getvalue('id')=="") 
  {
    die;
  }
  
  $order_status_query = tep_db_query("select o.orders_status from " . TABLE_ORDERS. "  o, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd where o.orders_id=opd.orders_id and o.orders_id ='" . tep_db_input($order_id) . "'");
  if(tep_db_num_rows($order_status_query)>0) {
	  $order_status = tep_db_fetch_array($order_status_query); 
	  if(($order_status['orders_status'])<=1){
			echo 'Invalid Download : Order is in Pending status';
			exit;
	  } 
  }
	
// Check that order_id, customer_id and filename match
  $downloads_query = tep_db_query("select date_format(o.date_purchased, '%Y-%m-%d') as date_purchased_day, opd.download_maxdays, opd.download_count, opd.orders_products_filename from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd where  o.orders_id = opd.orders_id and op.orders_products_status>1 and o.orders_id = op.orders_id and op.orders_products_id = opd.orders_products_id and opd.orders_products_download_id = '" . (tep_db_input($FREQUEST->getvalue('id'))) . "' and opd.orders_products_filename != ''");
  if (!tep_db_num_rows($downloads_query)) die;
  $downloads = tep_db_fetch_array($downloads_query);
// MySQL 3.22 does not have INTERVAL
  list($dt_year, $dt_month, $dt_day) = explode('-', $downloads['date_purchased_day']);
  $download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads['download_maxdays'], $dt_year);

// Die if time expired (maxdays = 0 means no time limit)
  if (($downloads['download_maxdays'] != 0) && ($download_timestamp <= time())) die;
// Die if remaining count is <=0
  if ($downloads['download_count'] <= 0) die;
// Die if file is not there
  if (!file_exists(DIR_FS_DOWNLOAD . $downloads['orders_products_filename'])) die;
  
// Now decrement counter
  tep_db_query("update " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " set download_count = download_count-1 where orders_products_download_id = '" . (tep_db_input($FREQUEST->getvalue('id'))) . "'");

// Returns a random name, 16 to 20 characters long
// There are more than 10^28 combinations
// The directory is "hidden", i.e. starts with '.'
function tep_random_name()
{
  $letters = 'abcdefghijklmnopqrstuvwxyz';
  $dirname = '.';
  $length = floor(tep_rand(16,20));
  for ($i = 1; $i <= $length; $i++) {
   $q = floor(tep_rand(1,26));
   $dirname .= $letters[$q];
  }
  return $dirname;
}

// Unlinks all subdirectories and files in $dir
// Works only on one subdir level, will not recurse
function tep_unlink_temp_dir($dir)
{
  $h1 = opendir($dir);
  while ($subdir = readdir($h1)) {
// Ignore non directories
    if (!is_dir($dir . $subdir)) continue;
// Ignore . and .. and CVS
    if ($subdir == '.' || $subdir == '..' || $subdir == 'CVS') continue;
// Loop and unlink files in subdirectory
    $h2 = opendir($dir . $subdir);
    while ($file = readdir($h2)) {
      if ($file == '.' || $file == '..') continue;
      @unlink($dir . $subdir . '/' . $file);
    }
    closedir($h2); 
    @rmdir($dir . $subdir);
  }
  closedir($h1);
}


// Now send the file with header() magic
 /* header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
  header("Last-Modified: " . gmdate("D,d M Y H:i:s") . " GMT");
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
  header("Content-Type: Application/octet-stream");
  header("Content-disposition: attachment; filename=" . $downloads['orders_products_filename']);*/
  
  $size=filesize(DIR_FS_DOWNLOAD . $downloads['orders_products_filename']);
		$content=@file_get_contents(DIR_FS_DOWNLOAD . $downloads['orders_products_filename']);

  // Start sending headers 
		header("Pragma: public"); // required 
		header("Expires: 0"); 
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Cache-Control: private",false); // required for certain browsers 
		header("Content-Transfer-Encoding: binary"); 
		header("Content-Type: application/octet-stream"); 
		header("Content-Length: " . $size); 
		header("Content-Disposition: attachment; filename=\"" . $downloads['orders_products_filename'] . "\";" );


 /* if (DOWNLOAD_BY_REDIRECT == 'true') {
// This will work only on Unix/Linux hosts
    tep_unlink_temp_dir(DIR_FS_DOWNLOAD_PUBLIC);
    $tempdir = tep_random_name();
    umask(0000);
    mkdir(DIR_FS_DOWNLOAD_PUBLIC . $tempdir, 0777);
    symlink(DIR_FS_DOWNLOAD . $downloads['orders_products_filename'], DIR_FS_DOWNLOAD_PUBLIC . $tempdir . '/' . $downloads['orders_products_filename']);
    tep_redirect(DIR_WS_DOWNLOAD_PUBLIC . $tempdir . '/' . $downloads['orders_products_filename']);
  } else {*/
// This will work on all systems, but will need considerable resources
// We could also loop with fread($fp, 4096) to save memory
  //  readfile(DIR_FS_DOWNLOAD . $downloads['orders_products_filename']);
  echo $content;
  //}
?>
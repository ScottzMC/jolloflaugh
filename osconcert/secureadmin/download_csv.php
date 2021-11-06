<?php

/*
osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 

Freeway eCommerce 
http://www.zac-ware.com/freeway 
Copyright (c) 2007 ZacWare

Released under the GNU General Public License 
*/
// Set flag that this is a parent file
	defined('_FEXEC') or die();
if ($FGET['saveas']) {
		$savename= $FGET['saveas'] . ".csv";
		}
		else $savename='unknown.csv';
  $filename = 'temp.csv';
  if (file_exists($filename)){
  header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
  header("Last-Modified: " . gmdate('D,d M Y H:i:s') . ' GMT');
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
  header("Content-Type: Application/octet-stream");
  header("Content-Disposition: attachment; filename=$savename");
  readfile($filename);
  }
  else echo FILE_NOT_FOUND;
?>
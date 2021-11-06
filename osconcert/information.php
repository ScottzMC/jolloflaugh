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
	
require('includes/application_top.php');

require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_INFORMATION_PAGE);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_INFORMATION_PAGE, '', 'SSL'));

$filename = DIR_FS_HTTP_CATALOG.'includes/languages/'.$FSESSION->language .'/';
$filename .= tep_db_prepare_input($FREQUEST->getvalue('page','string','info_shipping').'.php');
$head_txt = tep_db_prepare_input($FREQUEST->getvalue('page'));
 if (file_exists($filename) ) {
			$new_file = fopen($filename, 'rw');
            $con = fread($new_file,filesize($filename)); 
            fclose($new_file);
}
  $content = CONTENT_INFORMATION_PAGE;
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');

?>

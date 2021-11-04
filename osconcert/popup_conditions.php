<?php
/*
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	http://www.osconcert.com

  	Copyright (c) 2020 osConcert

	Released under the GNU General Public License
*/


// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	
  require('includes/application_top.php');

  //$navigation->remove_current_page();

  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_POPUP_CONDITIONS);

  
  $body_attributes = ' marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10"';
  $content = CONTENT_POPUP_CONDITIONS;
  //require(DIR_WS_CONTENT . '/conditions.tpl.php');
  require(DIR_WS_TEMPLATES . TEMPLATENAME_POPUP);

  require('includes/application_bottom.php');
?>


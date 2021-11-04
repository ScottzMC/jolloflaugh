<?php
/*
	osCommRes, Services Oline 
	http://www.oscommres.com 
	Copyright (c) 2005 osCommRes 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	https://www.osconcert.com

	Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_COOKIE_USAGE);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_COOKIE_USAGE));

  $content = CONTENT_COOKIE_USAGE;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>

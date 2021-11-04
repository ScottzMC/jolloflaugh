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

	Released under the GNU General Public License
*/


// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	
  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_FEATURED_PRODUCTS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FEATURED_PRODUCTS, '', 'NONSSL'));

  $content = CONTENT_FEATURED_PRODUCTS;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>

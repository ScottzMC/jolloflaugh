<?php
/*osCommerce, Open Source E-Commerce Solutions http://www.oscommerce.com Copyright (c) 2003 osCommerce  
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	https://www.osconcert.com

  	Copyright (c) 2020 osConcert

	Released under the GNU   General Public License
*/  
// Set flag that this is a parent file
define( '_FEXEC', 1 );
	require('includes/application_top.php'); 
	
// if the customer is not logged on, redirect them to the login page
if (!$FSESSION->is_registered('customer_id')) 
{
$navigation->set_snapshot();
tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}
 
require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_ACCOUNT);  
$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ACCOUNT, '', 'SSL')); 
$content = CONTENT_ACCOUNT;  $javascript = $content . '.js'; 
//require(DIR_WS_INCLUDES.'http.js');
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);  
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
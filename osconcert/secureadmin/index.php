<?php
/*
osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 NOTHING NEEDED HERE

Freeway eCommerce 
http://www.openfreeway.org
Copyright (c) 2007 ZacWare

Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );

  require('includes/application_top.php');
  
  tep_redirect(tep_href_link(FILENAME_CONFIGURATION, '', 'SSL'));
 ?>

<?php
/*
  osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 

    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare

Released under the GNU General Public License 

*/

// WebMakers.com Added: Login redirect to last page

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

  if (sizeof($navigation->snapshot) > 0) 
  {
    $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array($FSESSION->name)), $navigation->snapshot['mode']);
    $navigation->clear_snapshot();
    $link = $origin_href;
  } else {
    $link = tep_href_link(FILENAME_DEFAULT);
  }
?>

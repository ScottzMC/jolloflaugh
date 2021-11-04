<?php
/*
  	Copyright (c) 2020 osConcert

	Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	
  require('includes/application_top.php');

  $navigation->remove_current_page();

  $products_query = tep_db_query("select pd.products_name, p.products_image_1,p.products_title_1 from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where p.products_status = '1' and p.products_id = '" . tep_db_input($FREQUEST->getvalue('pID','int')) . "' and pd.language_id = '" . (int)$FSESSION->languages_id . "'");
  $products = tep_db_fetch_array($products_query);

  $content = CONTENT_POPUP_IMAGE;
  $javascript = $content . '.js';
  $body_attributes = ' onload="resize();"';

//  require(DIR_WS_TEMPLATES . 'content/' .  TEMPLATENAME_POPUP);

  require(DIR_WS_CONTENT . '/popup_image.tpl.php');

  require('includes/application_bottom.php');
?>
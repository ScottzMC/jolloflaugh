<?php
/*
  osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 

	1Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
Released under the GNU General Public License 
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

?>
<!-- default_specials //-->

<table width="100%" cellpadding="2">
          <tr>
<?php
$info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => sprintf(TABLE_HEADING_DEFAULT_SPECIALS, strftime('%B')));

  new contentBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_SPECIALS));

	$customer_group=tep_get_customers_groups_id();
$new = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image_1,p.products_title_1,s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' and s.status = '1' and ((s.customers_id = '" . (int)$FSESSION->customer_id . "' and " . (int)$FSESSION->customer_id .">0) or (s.customers_groups_id = '" . (int)$customer_group . "' and " . (int)$customer_group . ">0) or (s.customers_id = '0' and s.customers_groups_id = '0')) order by s.specials_date_added desc limit " . MAX_DISPLAY_SPECIAL_PRODUCTS);
    
 $info_box_contents = array();
  $row = 0;
  $col = 0;
  while ($default_specials = tep_db_fetch_array($new)) {
    $default_specials['products_name'] = tep_get_products_name($default_specials['products_id']);
    $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $default_specials["products_id"]) . '">' . tep_product_small_image($default_specials['products_image_1'], $default_specials['products_title_1']) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $default_specials['products_id']) . '">' . $default_specials['products_name'] . '</a><br><s>' . $currencies->display_price($default_specials['products_price'], tep_get_tax_rate($default_specials['products_tax_class_id']),1,true) . '</s><br><span class="productSpecialPrice">' . $currencies->display_price($default_specials['specials_new_products_price'], tep_get_tax_rate($default_specials['products_tax_class_id'])) . '</span>');
    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }
  new contentBox($info_box_contents);

if (MAIN_TABLE_BORDER == 'yes'){
$info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
  new infoboxFooter($info_box_contents, true, true);
}
?>

<!-- default_specials_eof //-->

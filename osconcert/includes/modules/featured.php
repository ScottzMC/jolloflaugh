<?php
/*
  osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
Released under the GNU General Public License 
  
  Featured Products V1.1
  Displays a list of featured products, selected from admin
  For use as an Infobox instead of the "New Products" Infobox  
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();


?>
<!-- featured_products //-->
<?php
 if(SHOW_MAIN_FEATURED_PRODUCTS == true)
 {
	 
	 //echo "HELLO";
  $featured_products_category_id = $new_products_category_id;
  $cat_name_query = tep_db_query("select categories_name from categories_description where categories_id = '" . (int)$featured_products_category_id . "' limit 1");
  $cat_name_fetch = tep_db_fetch_array($cat_name_query);
  $cat_name = $cat_name_fetch['categories_name'];
  $info_box_contents = array();

  if ( (!isset($featured_products_category_id)) || ($featured_products_category_id == '0') ) {
    $info_box_contents[] = array('align' => 'left', 'text' => TABLE_HEADING_FEATURED_PRODUCTS);
    $featured_products_query = tep_db_query("select p.products_id, p.products_image_1, p.products_title_1,p.products_tax_class_id, p.products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_status = '1' and f.status = '1' order by rand() DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS);
  } else {
    $info_box_contents[] = array('align' => 'left', 'text' => sprintf(TABLE_HEADING_FEATURED_PRODUCTS_CATEGORY, $cat_name));
    $featured_products_query = tep_db_query("select distinct p.products_id, p.products_image_1,p.products_title_1,p.products_tax_class_id, p.products_price from " . TABLE_CATEGORIES  . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS . " p left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . (int)$featured_products_category_id . "' and p.products_status = '1' and f.status = '1' order by rand() DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS);
  }

  $row = 0;
  $col = 0; 
  $num = 0;
  while ($featured_products = tep_db_fetch_array($featured_products_query)) {
    $num ++; if ($num == 1) { new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_FEATURED_PRODUCTS));}
    $featured_products['products_name'] = tep_get_products_name($featured_products['products_id']);
	$featured_products['specials_new_products_price']=tep_get_products_special_price($featured_products['products_id']);
    if($featured_products['specials_new_products_price']) {
      $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . tep_product_small_image($featured_products['products_image_1'], $featured_products['products_title_1']) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . $featured_products['products_name'] . '</a><br><s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id']),1,true) . '</s><br><span class="productSpecialPrice">' . 
                                           $currencies->display_price($featured_products['specials_new_products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>');
    } else {
      $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . tep_product_small_image($featured_products['products_image_1'], $featured_products['products_title_1']) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . $featured_products['products_name'] . '</a><br>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])),1,true);
    }    
    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }
  if($num) 
  {
      
      new contentBox($info_box_contents);
if (MAIN_TABLE_BORDER == 'yes'){
$info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
  new contentboxFooter($info_box_contents, true, true);
}
  }
 } else // If it's disabled, then include the original New Products box
 {
   include (DIR_WS_MODULES . FILENAME_NEW_PRODUCTS);
 }

?>
<!-- featured_products_eof //-->

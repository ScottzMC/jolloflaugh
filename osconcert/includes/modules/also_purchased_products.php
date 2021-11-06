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

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();


  if ($FREQUEST->getvalue('products_id')) {
    $orders_query = tep_db_query("select p.products_id, p.products_image_1,p.products_title_1 from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p where opa.products_id = '" . $FREQUEST->getvalue('products_id','int') . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . $FREQUEST->getvalue('products_id','int') . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = '1'" . $check_restrict_customer . " group by p.products_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED);
    $num_products_ordered = tep_db_num_rows($orders_query);
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
?>
<!-- also_purchased_products //-->
<tr>
<td>
<?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => TEXT_ALSO_PURCHASED_PRODUCTS);
	
      //new infoBoxHeading($info_box_contents,false, false);
	  new infoBoxHeading($info_box_contents,true, true);

      $row = 0;
      $col = 0;
      $info_box_contents = array();
      while ($orders = tep_db_fetch_array($orders_query)) {
        $orders['products_title_1'] = tep_get_products_name($orders['products_id']);
        $info_box_contents[$row][$col] = array('align' => 'center',
                                               'params' => 'class="smallText" width="33%" valign="top"',
                                               'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . tep_product_small_image($orders['products_image_1'], $orders['products_title_1']) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . stripslashes($orders['products_title_1']) . '</a>');

        $col ++;
        if ($col > 2) {
          $col = 0;
          $row ++;
        }
      }

      new contentBox($info_box_contents);
if (MAIN_TABLE_BORDER == 'yes'){
$info_box_contents = array();
$info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
  new infoboxFooter($info_box_contents, true, true);
}
?>
<!-- also_purchased_products_eof //-->
<?php
    }
  }
?>
</td>
</tr>
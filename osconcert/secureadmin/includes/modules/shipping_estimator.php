<?php
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
/*
  $Id: shipping_estimator.php,v 1.1.1.1 2003/09/18 19:04:52 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 Edwin Bekaert (edwin@ednique.com)
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License

  Customized by: Linda McGrath osCommerce@WebMakers.com
  * This now handles Free Shipping for orders over $total as defined in the Admin
  * This now shows Free Shipping on Virtual products
  * Everything is contained in an infobox for easier placement.

  Released under the GNU General Public License

  http://forums.oscommerce.com/viewtopic.php?t=38411

  http://www.oscommerce.com/community/contributions,1094
*/   

global $cart,$order,$total_weight,$FSESSION; 
     
	  require(DIR_WS_CLASSES . 'order.php');
      $order = new order;  
	 
	  require(DIR_WS_CLASSES . 'shipping.php');
      $shipping_modules = new shipping;
      	  
?>
<!-- shipping_estimator //-->
<?php if ($cart->count_contents() > 0) { 
	$modules_count=tep_count_shipping_modules();
?>
      <tr>
	  <td><label style="color:314C9B;font-size:13px;" for="disable_shipping"><?php echo tep_draw_checkbox_field('disable_shipping','1',($modules_count>0?false:true),'','onClick="javascript: shipping_status();" ' . ($modules_count<=0?' disabled ':'')); ?>Direct Delivery</label></td>
	  </tr>
<?php } ?>
      <tr>
            <td align="<?php echo (strstr($PHP_SELF,'shipping_estimator.php') ? 'right' : 'center'); ?>">
              <table id="shipping_estimator_id" width="100%" border="0" cellpadding="0" cellspacing="0">
			  
<?php  
// Only do when something is in the cart
if ($cart->count_contents() > 0 && $modules_count>0) {
// Could be placed in english.php
// shopping cart quotes
  define('SHIPPING_OPTIONS', 'Shipping Options:');
  if (strstr($PHP_SELF,'shopping_cart.php')) {
    define('SHIPPING_OPTIONS_LOGIN', 'Please <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><u><font color="white">Log In</font></u></a>, to display your personal shipping costs.');
  } else {
    define('SHIPPING_OPTIONS_LOGIN', 'Please Log In, to display your personal shipping costs.');
  }
  define('SHIPPING_METHOD_TEXT','Shipping Methods:');
  define('SHIPPING_METHOD_RATES','Rates:');
  define('SHIPPING_METHOD_TO','Ship to: ');
  define('SHIPPING_METHOD_TO_NOLOGIN', 'Ship to: <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><u>Log In</u></a>');
  define('SHIPPING_METHOD_FREE_TEXT','Free Shipping');
  define('SHIPPING_METHOD_ALL_DOWNLOADS','- Downloads');
  define('SHIPPING_METHOD_RECALCULATE','Recalculate');
  define('SHIPPING_METHOD_ZIP_REQUIRED','true');
  define('SHIPPING_METHOD_ADDRESS','Address:');
  define('TEXT_KG','kg');

  // shipping cost
  require('includes/classes/http_client.php'); // shipping in basket
 if($cart->get_content_type() !== 'virtual') { 
    if ($FSESSION->is_registered('customer_id')) {
      // user is logged in
        $sendto = $customer_default_address_id;
      // set session now
         $cart_address_id = $sendto;
		 $FSESSION->set('cart_address_id',$sendto);
//      tep_session_register('cart_address_id');
      // include the order class (uses the sendto !)
/*      require(DIR_WS_CLASSES . 'order.php');
      $order = new order;  
*/    }



    // weight and count needed for shipping !
    $total_weight = $cart->show_weight();
    $total_count = $cart->count_contents();

    
	$quotes = $shipping_modules->quote();
    $cheapest = $shipping_modules->cheapest();
    // set selections for displaying
    $selected_country = $order->delivery['country']['id'];
    $selected_zip = $order->delivery['postcode'];
    $selected_address = $sendto;
  }
  $extra="attributes-odd";
    // eo shipping cost
	
	 $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
     if ($products[$i]['element_type']=='P'){
	 $prd = tep_db_query("select product_mode from products where products_id='" .$products[$i]['id']."'");
	 $prd_values = tep_db_fetch_array($prd);
	 if ($prd_values['product_mode']!='V'){

   tep_content_title_top(SHIPPING_OPTIONS);

?>
	
	<tr>
		<td valign="top">
<?php 
 
	 
	  // check free shipping based on order $total
  if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
    switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
      case 'national':
        if ($order->delivery['country_id'] == STORE_COUNTRY) $pass = true; break;
      case 'international':
        if ($order->delivery['country_id'] != STORE_COUNTRY) $pass = true; break;
      case 'both':
        $pass = true; break;
      default:
        $pass = false; break;
    }
    $free_shipping = false;
    if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
      $free_shipping = true;
      include(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/order_total/ot_shipping.php');
    }
  } else {
    $free_shipping = false;
  }
// end free shipping based on order total
 

//  $ShipTxt= tep_draw_form('estimator', tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'), 'post'); //'onSubmit="return check_form();"'
  $ShipTxt= ''; tep_draw_form('estimator', tep_href_link(FILENAME_CREATE_ORDER_NEW, '', 'NONSSL'), 'post'); //'onSubmit="return check_form();"'
  $ShipTxt.='<table border="0" cellpadding="5" cellspacing="0" width="100%">';

  if(sizeof($quotes)) {
      // logged in
      $addresses_query = tep_db_query("select address_book_id, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$FSESSION->customer_id . "'");
      while ($addresses = tep_db_fetch_array($addresses_query)) {
        $addresses_array[] = array('id' => $addresses['address_book_id'], 'text' => tep_address_format(tep_get_address_format_id($addresses['country_id']), $addresses, 0, ' ', ' '));
      }
      $ShipTxt.='<tr><td colspan="3" class="main">' . ($total_count == 1 ? ' Item: ' : ' Items: ') . $total_count . '&nbsp;-&nbsp;Weight: ' . tep_get_display_weight($total_weight) . '</td></tr>';
      $ShipTxt.='<tr><td colspan="3" class="main" nowrap>' .
                SHIPPING_METHOD_ADDRESS .'&nbsp;'. tep_draw_pull_down_menu('address_id', $addresses_array, $selected_address, 'onchange="document.estimator.submit();return false;"').'</td></tr>';
      $ShipTxt.='<tr valign="top"><td class="main" width=2% nowrap>' . SHIPPING_METHOD_TO .'</td><td colspan="2" class="main">'. tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>') . '</td></tr>';
    if ($free_shipping==1) {
      // order $total is free
      $ShipTxt.='<tr><td colspan="3" class="main">'.tep_draw_separator().'</td></tr>';
      $ShipTxt.='<tr><td>&nbsp;</td><td class="main">' . sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . '</td><td>&nbsp;</td></tr>';
    }else{ 
      // shipping display
      $ShipTxt.='<tr class="infoBox"><td></td><td class="main" align="left"><b>' . SHIPPING_METHOD_TEXT . '</b></td><td class="main" align="center"><b>' . SHIPPING_METHOD_RATES . '</b></td></tr>';
      //$ShipTxt.='<tr><td colspan="3" class="main">'.tep_draw_separator().'</td></tr>';
      for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
        if(sizeof($quotes[$i]['methods'])==1){
          // simple shipping method
          $thisquoteid = $quotes[$i]['id'].'_'.$quotes[$i]['methods'][0]['id'];
          $ShipTxt.= '<tr class="'.$extra.'">';
          $ShipTxt.='<td class="main">'.$quotes[$i]['icon'].'&nbsp;</td>';
          if($quotes[$i]['error']){
            $ShipTxt.='<td colspan="2" class="main">'.$quotes[$i]['module'].'&nbsp;';
            $ShipTxt.= '('.$quotes[$i]['error'].')</td></tr>';
          }else{
            if($cheapest['id'] == $thisquoteid){
              $ShipTxt.='<td class="main"><b>'.$quotes[$i]['module'].'&nbsp;';
              $ShipTxt.= '('.$quotes[$i]['methods'][0]['title'].')</b></td><td align="right" class="main"><b><span class="PriceText">'.$currencies->format(tep_add_tax($quotes[$i]['methods'][0]['cost'], $quotes[$i]['tax'])).'</span><b></td></tr>';
            }else{ 
              $ShipTxt.='<td class="main">'.$quotes[$i]['module'].'&nbsp;';
              $ShipTxt.= '('.$quotes[$i]['methods'][0]['title'].')</td><td align="right" class="main"><span class="PriceText">'.$currencies->format(tep_add_tax($quotes[$i]['methods'][0]['cost'], $quotes[$i]['tax'])).'</span></td></tr>';
            }
          }
        } else {
          // shipping method with sub methods (multipickup)
          for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) { 
            $thisquoteid = $quotes[$i]['id'].'_'.$quotes[$i]['methods'][$j]['id'];
            $ShipTxt.= '<tr class="'.$extra.'">';
            $ShipTxt.='<td class="main">'.$quotes[$i]['icon'].'&nbsp;</td>';
            if($quotes[$i]['error']){
              $ShipTxt.='<td colspan="2" class="main">'.$quotes[$i]['module'].'&nbsp;';
              $ShipTxt.= '('.$quotes[$i]['error'].')</td></tr>';
            }else{
              if($cheapest['id'] == $thisquoteid){ 
                $ShipTxt.='<td class="main"><b>'.$quotes[$i]['module'].'&nbsp;';
                $ShipTxt.= '('.$quotes[$i]['methods'][$j]['title'].')</b></td><td align="right" class="main"><b>'.$currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])).'</b></td></tr>';
              }else{
                $ShipTxt.='<td class="main">'.$quotes[$i]['module'].'&nbsp;';
                $ShipTxt.= '('.$quotes[$i]['methods'][$j]['title'].')</td><td align="right" class="main">'.$currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])).'</td></tr>';
              }
            }
          }
        }
      }
    }
  } else { 
    // virtual product/download
    $ShipTxt.='<tr><td class="main">' . SHIPPING_METHOD_FREE_TEXT . ' ' . SHIPPING_METHOD_ALL_DOWNLOADS . '</td></tr>';
  }

  $ShipTxt.= '</table></form>';

	echo $ShipTxt;

} // Only do when something is in the cart
?>
			</td>
			</tr>
			<?php tep_content_title_bottom();
				 }
	 }
	 }

			?>
			</table>
            </td>
          </tr>
<!-- shipping_estimator_eof //-->

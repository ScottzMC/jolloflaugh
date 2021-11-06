
<?php
/*
  $Id: shipping_estimator.php,v 1.1.1.1 2003/09/18 19:04:52 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 Edwin Bekaert (edwin@ednique.com)

  Customized by: Linda McGrath osCommerce@WebMakers.com
  * This now handles Free Shipping for orders over $total as defined in the Admin
  * This now shows Free Shipping on Virtual products
  * Everything is contained in an infobox for easier placement.

  Released under the GNU General Public License

  http://forums.oscommerce.com/viewtopic.php?t=38411

  http://www.oscommerce.com/community/contributions,1094
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();


?>
<!-- shipping_estimator //-->
          <tr>
            <td align="<?php echo (strstr($PHP_SELF,'shipping_estimator.php') ? 'right' : 'center'); ?>">
              <table width="100%">
<?php
//cartzone move this to languages please
// Only do when something is in the cart
if ($cart->count_contents() > 0) {
// Could be placed in english.php
// shopping cart quotes
  define('SHIPPING_OPTIONS', 'Delivery Options:');
  if (strstr($PHP_SELF,'shopping_cart.php')) {
    define('SHIPPING_OPTIONS_LOGIN', 'Please <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><u><font color="white">Log In</font></u></a>, to display your personal shipping costs.');
  } else {
    define('SHIPPING_OPTIONS_LOGIN', 'Please Log In, to display your personal shipping costs.');
  }
  define('SHIPPING_METHOD_TEXT','Delivery Methods:');
  define('SHIPPING_METHOD_RATES','Rates:');
  define('SHIPPING_METHOD_TO','Deliver to: ');
  define('SHIPPING_METHOD_TO_NOLOGIN', 'Delivery to: <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><u>Log In</u></a>');
  define('SHIPPING_METHOD_FREE_TEXT','Free Delivery');
  define('SHIPPING_METHOD_NO_SHIPPING_TEXT','No Delivery Available');
  define('SHIPPING_METHOD_ALL_DOWNLOADS','- Downloads');
  define('SHIPPING_METHOD_RECALCULATE','Recalculate');
  define('SHIPPING_METHOD_ZIP_REQUIRED','true');
  define('SHIPPING_METHOD_ADDRESS','Address:');

  // shipping cost
  require(DIR_WS_INCLUDES.'classes/http_client.php'); // shipping in basket

  if($cart->get_content_type() !== 'virtual') {
    if ($FSESSION->is_registered('customer_id')) {
      // user is logged in
      if ($FREQUEST->postvalue('address_id')!=''){
        // user changed address
        $FSESSION->set('sendto',$FREQUEST->postvalue('address_id'));
      }elseif ($FSESSION->is_registered('cart_address_id')){
        // user once changed address
		$FSESSION->set('sendto',$FSESSION->cart_address_id);
      }else{
        // first timer
		$FSESSION->set('sendto',$FSESSION->customer_default_address_id);
      }
      // set session now
	  $FSESSION->set('cart_address_id',$FSESSION->sendto);
      // include the order class (uses the sendto !)
      require(DIR_WS_CLASSES . 'order.php');
      $order = new order;
    }else{
      // user not logged in !
      if ($FREQUEST->postvalue('country_id')!=''){
        // country is selected
        $country_info = tep_get_countries($FREQUEST->postvalue('country_id'),true);
        $order->delivery = array('postcode' => $FREQUEST->postvalue('zip_code'),
                                 'country' => array('id' => $FREQUEST->postvalue('country_id'), 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
                                 'country_id' => $FREQUEST->postvalue('country_id'),
                                 'format_id' => tep_get_address_format_id($FREQUEST->postvalue('country_id')));
        $cart_country_id = $FREQUEST->postvalue('country_id');
        $FSESSION->set('cart_country_id',$cart_country_id);
        $cart_zip_code = $FREQUEST->postvalue('zip_code');
        $FSESSION->set('cart_zip_code',$cart_zip_code);
      }elseif ($FSESSION->is_registered('cart_country_id')){
        // session is available
        $country_info = tep_get_countries($FSESSION->cart_country_id,true);
        $order->delivery = array('postcode' => $FSESSION->cart_zip_code,
                                 'country' => array('id' => $FSESSION->cart_country_id, 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
                                 'country_id' => $FSESSION->cart_country_id,
                                 'format_id' => tep_get_address_format_id($FSESSION->cart_country_id));
      } else {
        // first timer
        $FSESSION->set('cart_country_id',STORE_COUNTRY);
// WebMakers.com Added: changes
// changed from STORE_ORIGIN_ZIP to SHIPPING_ORIGIN_ZIP
        $cart_zip_code = SHIPPING_ORIGIN_ZIP;
        $country_info = tep_get_countries(STORE_COUNTRY,true);
        $FSESSION->set('cart_zip_code',$cart_zip_code);
        $order->delivery = array('postcode' => SHIPPING_ORIGIN_ZIP,
                                 'country' => array('id' => STORE_COUNTRY, 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
                                 'country_id' => STORE_COUNTRY,
                                 'format_id' => tep_get_address_format_id($FREQUEST->postvalue('country_id')));
      }
      // set the cost to be able to calvculate free shipping
      $order->info = array('total' => $cart->show_total()); // TAX ????
    }
    // weight and count needed for shipping !
    $total_weight = $cart->show_weight();
    $total_count = $cart->count_contents();
    require(DIR_WS_CLASSES . 'shipping.php');
    $shipping_modules = new shipping;
    $quotes = $shipping_modules->quote();
    $cheapest = $shipping_modules->cheapest();
    // set selections for displaying
    $selected_country = $order->delivery['country']['id'];
    $selected_zip = $order->delivery['postcode'];
    $selected_address = $FSESSION->sendto;
  }
    // eo shipping cost

?>
	<tr>
		<td valign="top" class="availability">
		<table width="100%" cellpadding="5">
			<tr>
				<td class="availability_text"><?php echo SHIPPING_OPTIONS;?></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td valign="top" class="tab_strip">
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
    $FSESSION->set('free_shipping',false);
    if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
      $FSESSION->set('free_shipping',true);
      include(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/order_total/ot_shipping.php');
    }
  } else {
    $FSESSION->set('free_shipping',false);
  }
// end free shipping based on order total
//  $ShipTxt= tep_draw_form('estimator', tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'), 'post'); //'onSubmit="return check_form();"'
  $ShipTxt= tep_draw_form('estimator', tep_href_link(basename($PHP_SELF), '', 'NONSSL'), 'post'); //'onSubmit="return check_form();"'
  $ShipTxt.='<table width="100%">';
  if(sizeof($quotes)) {
    if ($FSESSION->is_registered('customer_id')) {
      // logged in
      $addresses_query = tep_db_query("select address_book_id, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$FSESSION->customer_id . "'");
      while ($addresses = tep_db_fetch_array($addresses_query)) {
        $addresses_array[] = array('id' => $addresses['address_book_id'], 'text' => tep_address_format(tep_get_address_format_id($addresses['country_id']), $addresses, 0, ' ', ' '));
      }
      $ShipTxt.='<tr><td colspan="3" class="main">' . ($total_count == 1 ? ' Item: ' : ' Items: ') . $total_count . '&nbsp;-&nbsp;Weight: ' . tep_get_display_weight($total_weight) . '</td></tr>';
      $ShipTxt.='<tr><td colspan="3" class="main" nowrap>' .
                SHIPPING_METHOD_ADDRESS .'&nbsp;'. tep_draw_pull_down_menu('address_id', $addresses_array, $selected_address, 'onchange="document.estimator.submit();return false;"').'</td></tr>';
      $ShipTxt.='<tr valign="top"><td class="main">' . SHIPPING_METHOD_TO .'</td><td colspan="2" class="main">'. tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>') . '</td></tr>';
    } else {
      // not logged in
      $ShipTxt.='<tr><td colspan="3">' .tep_output_warning(SHIPPING_OPTIONS_LOGIN) . '</td></tr>';
      $ShipTxt.='<tr><td colspan="3" class="main">' . ($total_count == 1 ? ' Item: ' : ' Items: ') . $total_count . '&nbsp;-&nbsp;Weight: ' . tep_get_display_weight($total_weight) . '</td></tr>';
      $ShipTxt.='<tr><td colspan="3" class="main" nowrap>' .
                ENTRY_COUNTRY .'&nbsp;'. tep_get_country_list('country_id', $selected_country,'style="width=200;"');
      if(SHIPPING_METHOD_ZIP_REQUIRED == "true"){
        $ShipTxt.='</td></tr>          <tr>
            <td colspan="3" class="main" nowrap>' . tep_draw_separator('pixel_trans.gif', '100%', '10') . '</td>
          </tr><tr><td colspan="3" class="main" nowrap>'.ENTRY_POST_CODE .'&nbsp;'. tep_draw_input_field('zip_code', $selected_zip, 'size="10"');
      }
      $ShipTxt.='&nbsp;<a href="_" onclick="document.estimator.submit();return false;">' . SHIPPING_METHOD_RECALCULATE.'</a></td></tr>';
    }
    if ($FSESSION->free_shipping==1) {
      // order $total is free
      $ShipTxt.='<tr><td colspan="3" class="main">'.tep_draw_separator().'</td></tr>';
      $ShipTxt.='<tr><td>&nbsp;</td><td class="main">' . sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . '</td><td>&nbsp;</td></tr>';
    }else{ 
      // shipping display
      $ShipTxt.='<tr><td></td><td class="main" align="left"><b>' . SHIPPING_METHOD_TEXT . '</b></td><td class="main" align="center"><b>' . SHIPPING_METHOD_RATES . '</b></td></tr>';
      $ShipTxt.='<tr><td colspan="3" class="main">'.tep_draw_separator().'</td></tr>';
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
    //$ShipTxt.='<tr><td class="main">' . SHIPPING_METHOD_FREE_TEXT . ' ' . SHIPPING_METHOD_ALL_DOWNLOADS . '</td></tr>';
	$ShipTxt.='<tr><td class="main" align="center">' . SHIPPING_METHOD_NO_SHIPPING_TEXT . '</td></tr>';
  }

  $ShipTxt.= '</table></form>';

	echo $ShipTxt;

} // Only do when something is in the cart
?>
			</td>
			</tr>
			</table>
            </td>
          </tr>
<!-- shipping_estimator_eof //-->

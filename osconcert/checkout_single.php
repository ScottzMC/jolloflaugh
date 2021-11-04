<?php
/*
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
  require('includes/classes/http_client.php');
  require(DIR_WS_CLASSES . 'order.php');
  require(DIR_WS_CLASSES . 'shipping.php');
  require(DIR_WS_CLASSES . 'order_total.php');
  require(DIR_WS_CLASSES . 'payment.php');
  
  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_CHECKOUT_SINGLE);
  
  require(DIR_WS_CLASSES . 'customerAccount.php');
  
  $customerAccount=new customerAccount();
  $form_name='billing_address';
   $ACCOUNT=array();

   $fieldsDesc=array();

   $customer_query=tep_db_query("select a.*,c.* from " . TABLE_CUSTOMERS ." c, " . TABLE_ADDRESS_BOOK . " a  where c.customers_id='" . $FSESSION->customer_id . "' and c.customers_default_address_id=a.address_book_id and c.customers_id=a.customers_id");
   $customer_result=tep_db_fetch_array($customer_query);
   $query=tep_db_query("SELECT cif.*,cifd.label_text,cifd.input_description,cifd.error_text,cifd.input_title from ".  TABLE_CUSTOMERS_INFO_FIELDS . " cif, " . TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION . " cifd where cif.info_id=cifd.info_id and cifd.languages_id=" . $FSESSION->languages_id. " and cif.display_page like '%A%' and cif.active='Y' order by cif.sort_order");
     $icnt=0;
     while($fieldsDesc[$icnt]=tep_db_fetch_array($query)){
          $fieldDesc=&$fieldsDesc[$icnt];
          if (strpos($fieldDesc['error_text'],"==")!==false){
             $fieldDesc['error_text']=str_replace(array("==MIN==","==MAX==","==DATE_FORMAT==","==EX_DATE=="),array($fieldDesc['textbox_min_length'],$fieldDesc['textbox_max_length'],EVENTS_DATE_FORMAT,format_date('1970-05-20')),$fieldDesc['error_text']);
          }
          if (strpos($fieldDesc['input_description'],"==")!==false){
             $fieldDesc['input_description']=str_replace(array("==MIN==","==MAX==","==DATE_FORMAT==","==EX_DATE=="),array($fieldDesc['textbox_min_length'],$fieldDesc['textbox_max_length'],EVENTS_DATE_FORMAT,format_date('1970-05-20')),$fieldDesc['input_description']);
          }
          $icnt++;
          //  if ($action=="process" || $action=="update") continue;
          if (method_exists($customerAccount,"getdb__" . $fieldDesc['uniquename'])){
              $customerAccount->{"getdb__" . $fieldDesc['uniquename']}($customer_result);
          } else {
              $customerAccount->commonEntries($fieldDesc['uniquename'],$customer_result);
          }
    }
    unset($fieldsDesc[$icnt]);
  
$currencies=new currencies();


  //ajax start
  $command=$FREQUEST->getvalue('command');

/*
  $command=$FREQUEST->getvalue('command','string','show_state');
  $FREQUEST->setvalue('country_id','13','GET');

    $FREQUEST->setvalue('t', '1230882695240', 'GET');
*/

	$format_id=0;
  if($command!='')
  {
	  switch($command)
	  {
	  case 'show_state':
 		 $country=$FREQUEST->getvalue('country_id');
		  $zone_ids="";
		  $zone_name="";
		  $zones_query = tep_db_query("select zone_id,zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' order by zone_name");
		  while ($zones_values = tep_db_fetch_array($zones_query)) {
			 $zone_ids.=$zones_values['zone_id']."{}";
			 $zone_name.=$zones_values['zone_name']."{}";
		  }
	  	echo 'show_state^'.substr($zone_ids,0,-2)."^".substr($zone_name,0,-2)."^".$zone;
       	break;
		case 'billing_address_submit':
			$messageStack->reset();
			$shipping=&$FSESSION->getobject("shipping");
			tep_billing_address_submit('P');
			if($messageStack->size('checkout_address')>0) //error occurs
				echo 'command{}billing_address_error||' . $messageStack->output('checkout_address');
			elseif(is_array($shipping) || $shipping) //shipping included
			{
               	echo 'command{}show_shipping_address||';
               echo '{}billing_address_display||' . tep_address_format($format_id, $FSESSION->getobject("billto_array"), true, ' ', '<br>');
			}
			else //skip to payment method
			{
				echo 'command{}show_payment_method||';
				echo '{}billing_address_display||' . tep_address_format($format_id, $FSESSION->getobject("billto_array"), true, ' ', '<br>');
			}
			break;
		case 'shipping_address_submit':
			$messageStack->reset();
			tep_billing_address_submit('S');
			if($messageStack->size('checkout_shipping_address')>0) //error occurs
				echo 'command{}shipping_address_error||' . $messageStack->output('checkout_shipping_address');
			else
			{
				echo 'command{}show_shipping_method';
				echo '{}shipping_address_display||' . tep_address_format($format_id, $FSESSION->getobject("sendto_array"), true, ' ', '<br>');
			}
			break;
		case 'shipping_method_submit':
			$messageStack->reset();
			$order = new order;
			 $total_weight = $cart->show_weight();
			$shipping_modules = new shipping;
			$quotes_available = $shipping_modules->quote();
			$FSESSION->set('free_shipping',$FREQUEST->postvalue('free_shipping'));
			if ((tep_count_shipping_modules() > 0) || ($FSESSION->get('free_shipping') == true) ) {
				 if ( ($FREQUEST->postvalue('shipping')) && (strpos($FREQUEST->postvalue('shipping'), '_')) ) {
					$shipping_id = $FREQUEST->postvalue('shipping');
					list($module, $method) = explode('_', $shipping_id);
					if ( is_object($$module) || ($shipping_id == 'free_free') ) {
					  if ($shipping_id == 'free_free') {
						$quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
						$quote[0]['methods'][0]['cost'] = '0';
					  } else {
						$quote = $shipping_modules->quote($method, $module);
					  }
					  if (isset($quote['error'])) {
						$messageStack->add('shipping_method_error',$quote['error']);
						//$FSESSION->remove('shipping');
					  } else {
						if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
						    $shipping = array('id' => $shipping_id,
											'title' => (($FSESSION->get('free_shipping') == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
											'cost' => $quote[0]['methods'][0]['cost']);
							 $FSESSION->set('shipping',$shipping);	
						}
					  }
					} 
				  }
				} else {
				  $FSESSION->set('shipping',false);
				} 
			$shipping=&$FSESSION->getobject("shipping");
			if(!is_array($shipping) && !($FSESSION->free_shipping == true))
				 $messageStack->add('shipping_method_error',TEXT_CHOOSE_SHIPPING_METHOD);
			if($messageStack->size('shipping_method_error')>0)
				echo 'command{}shipping_method_error||' . $messageStack->output('shipping_method_error');
			else
			{
				echo 'command{}show_payment_method';
				echo '{}shipping_method_display||' . $shipping['title'];
			}
			break;
		case 'payment_method_submit':
			$GLOBALS["EXECUTE_PAYMENT"]=1;
			 echo 'command{}';
		 	 include('templates/content/checkout_single_confirm.tpl.php');
			break;
		case 'get_counpon_details':
 			$FREQUEST->setvalue('coupon',$FREQUEST->getvalue('coupon'),'POST');
 			$FREQUEST->setvalue('gv_redeem_code',$FREQUEST->getvalue('gv_redeem_code'),'POST');
			$FREQUEST->setvalue('cot_gv',$FREQUEST->getvalue('cot_gv'),'POST');
			if ($FREQUEST->postvalue('cot_gv')) $FSESSION->set('cot_gv',tep_db_prepare_input($FREQUEST->postvalue('cot_gv')));
			if ($credit_covers) $payment=''; //ICW added for CREDIT CLASS
			$payment_modules = new payment($payment);
		  //ICW ADDED FOR CREDIT CLASS SYSTEM
		   $order = new order;
		  //$payment_modules->update_status();
		  //ICW ADDED FOR CREDIT CLASS SYSTEM
		   $order_total_modules = new order_total;
		  //ICW ADDED FOR CREDIT CLASS SYSTEM
		  $order_total_modules->collect_posts_forajax();
		  //ICW ADDED FOR CREDIT CLASS SYSTEM
			$order_total_modules->process();
						
			//print_r($GLOBALS);
			echo 'command{}coupon_details||';
			if(sizeof($GLOBALS['ot_coupon'])){
				$coupon=$GLOBALS['ot_coupon'];				
				$error="";
				$error=$FSESSION->get('coupon_error');
				if($coupon->code=='ot_coupon' && $error=="" && $coupon->output[0]['text']){
				//cartzone dont
					echo '<table width="90%" cellspacing="3" cellpadding="3" class="formArea" align="center">' .
							'<tr><td></td>' .
							'   <td class="eventTitle">'.TEXT_REDEEM_CODE . (($FSESSION->get('email_redeem_code'))?$FSESSION->get('email_redeem_code'): $coupon->coupon_code).'</td>' .
							'   <td class="eventTitle">'.TEXT_VALUE. $coupon->output[0]['text'].'</td>' .
							'</tr>' . 
							'<tr><td></td>' .
							'   <td class="eventTitle">'.TEXT_VALID_UTIL. $coupon->expire_date.'</td>' .
							'   <td class="eventTitle">'.(($coupon->uses_per_user!=0)?TEXT_USES_REMAINING .$coupon->uses_per_user:'').'</td>' .
							'<td rowspan="2" valign="middle" align="center">'.tep_template_image_button_dont('button_dont_redeem.gif',IMAGE_DONT_REDEEM_VOUCHER,'style="cursor:pointer;cursor:hand" onclick="javascript:submitFunction(1);"').'</td>'.
							'</tr>' .   					
							'</table>';
				} else {
					echo '<table width="90%" align="center"><tr align="center"><td align="left" class="smalltext"><span class="errortext">'.$error.'</span></td></tr></table>';
				}
			} 
		break;
	  }
	  exit;
  }
  
	// modified for wallet payment -start
	if ($FSESSION->is_registered("payment_page") && $FSESSION->get('payment_page')=="wallet" && ($FREQUEST->getvalue('error')!='' || $FREQUEST->getvalue('payment_error')!='')){
		tep_redirect(tep_href_link(FILENAME_WALLET_CHECKOUT_PAYMENT,tep_get_all_input_params() . '&error=' . $FREQUEST->getvalue('error') .'&validID=' . $FSESSION->get('checkID'),'SSL'));
	}
	// modified for wallet payment -end
  
 //ajax end
  if (!$FSESSION->is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  if(tep_check_is_blocked_customer()){
	tep_redirect(tep_href_link(FILENAME_ACCOUNT,'isblocked=yes'));
  }
  if(tep_check_is_suspended_customer()){
	tep_redirect(tep_href_link(FILENAME_ACCOUNT,'suspended=yes'));
  } 
// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }
  $valid_to_checkout= true;
  $cart->get_products(true);


  // Stock Check
  if ( (STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true') ) {
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
     if ($products[$i]['elementType']=='P'){
      if (tep_check_stock($products[$i]['id'], $products[$i]['quantity'])) {
        tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
        break;
      }
     }
    }
  }
  //===========
  $FSESSION->set('shipping',true);
  if (!$FSESSION->is_registered('sendto')) {
    $FSESSION->set('sendto',$FSESSION->get('customer_default_address_id'));
  } else {
// verify the selected shipping address
    $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$FSESSION->customer_id . "' and address_book_id = '" . (int)$FSESSION->sendto . "'");
    $check_address = tep_db_fetch_array($check_address_query);
    if ($check_address['total'] != '1') {
      $FSESSION->set('sendto',$FSESSION->get('customer_default_address_id'));
      //if ($FSESSION->is_registered('shipping')) $FSESSION->remove('shipping');
    }
  }
    

 if (!$FSESSION->is_registered('billto')) {
    $FSESSION->set('billto',$FSESSION->get('customer_default_address_id'));
  } else {
// verify the selected billing address
    $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$FSESSION->customer_id . "' and address_book_id = '" . (int)$FSESSION->billto . "'");
    $check_address = tep_db_fetch_array($check_address_query);

    if ($check_address['total'] != '1') {
      $FSESSION->set('billto',$FSESSION->get('customer_default_address_id'));
      if ($FSESSION->is_registered('payment')) $FSESSION->remove('payment');
    }
  }

  $cartID = $cart->cartID;
  $FSESSION->set('cartID',$cartID);
  if (NO_CHECKOUT_ZERO_PRICE=="1" && $cart->is_free_checkout()){
	tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS_FREE, '', 'SSL'));
  }
  $order = new order;
  if (($order->content_type == 'virtual') || ($order->content_type == 'virtual_weight') ) {
  	 $FSESSION->set('shipping',false);
   	 $FSESSION->set('sendto',false);
 }
 $FSESSION->set('billto_array',array());
 if($FSESSION->get('shipping'))
 {
  $FSESSION->set('sendto_array',array());
  tep_check_country_differ();
  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();
  // ------load all enabled shipping modules===================
  $shipping_modules = new shipping;
  $quotes_available = $shipping_modules->quote();
  if(sizeof($quotes_available)<=0){
  	tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
  }
  if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
    $pass = false;
    switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
      case 'national':
        if ($order->delivery['country_id'] == STORE_COUNTRY) {
          $pass = true;
        }
        break;
      case 'international':
        if ($order->delivery['country_id'] != STORE_COUNTRY) {
          $pass = true;
        }
        break;
      case 'both':
        $pass = true;
        break;
    }
    $FSESSION->free_shipping = false;
    if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
      $FSESSION->set('free_shipping',true);
      include(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/order_total/ot_shipping.php');
    }
  } else {
    $FSESSION->set('free_shipping',false);
  }
  //$FSESSION->free_shipping = true;
   //include(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/order_total/ot_shipping.php');
  $quotes = $shipping_modules->quote();
   if ($FSESSION->get('shipping') == true && tep_count_shipping_modules() > 1) $FSESSION->set('shipping',$shipping_modules->cheapest());
 }
//=================================shipping end==================================
//=============payment start=========================
  $order_timestamp=time();
 $referenceID= substr(strtolower($order->customer['firstname']),0,3) . substr(strtolower($order->customer['lastname']),0,3) . $order_timestamp ;
 if (!$FSESSION->is_registered('referenceID')) $FSESSION->set('referenceID',$referenceID);	
  $payment_modules = new payment;
  
  $FSESSION->set('payment_page','default');
//==========payment end==============================



 $JS_FUNCS[]="do_init_action('" . $FSESSION->billto . "','" . $FSESSION->sendto . "')";
 /*$JS_FUNCS['page']='do_init_action()';
 				<Script>
                   	 do_init_action('<?php echo $FSESSION->billto;?>','<?php echo $FSESSION->sendto;?>');
				</Script>*/

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_SINGLE, '', 'SSL'));

  $content = CONTENT_CHECKOUT_SINGLE;
  $javascript = CONTENT_CHECKOUT_SINGLE . '.js.php';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  require(DIR_WS_INCLUDES . 'application_bottom.php');
  echo '<script> pageLoaded(); </script>';
  function tep_billing_address_submit($mode)
  {
  	global $FREQUEST,$messageStack,$format_id,$FSESSION,$fieldsDesc,$customerAccount,$ACCOUNT,$CUSTOMER,$ADDRESS;
	if($mode=='P')  //payment
	{
  	    $FSESSION->set('billto',tep_db_prepare_input($FREQUEST->postvalue('address','int')));
		$delivery_query = tep_db_query("select  c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3 from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " c on (ab.entry_country_id = c.countries_id) where ab.customers_id = '" . (int)$FSESSION->get('customer_id') . "' and ab.address_book_id = '" . (int)$FSESSION->get('billto') . "'");
		$format_id=$FREQUEST->postvalue('format' . $FSESSION->get('billto'));
		$stack_class='checkout_address';
		$session_array='billto_array';
    }
	else
	{
  	    $FSESSION->set('sendto',tep_db_prepare_input($FREQUEST->postvalue('address','int')));
		$delivery_query = tep_db_query("select  c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3 from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " c on (ab.entry_country_id = c.countries_id) where ab.customers_id = '" . (int)$FSESSION->get('customer_id') . "' and ab.address_book_id = '" . (int)$FSESSION->get('sendto') . "'");
		$format_id=$FREQUEST->postvalue('format' . $FSESSION->get('sendto'));
		$stack_class='checkout_shipping_address';
		$session_array='sendto_array';
    }
   
   
 
	$pass=true;
	$FREQUEST->setvalue("customers_id",$FSESSION->customer_id,"POST");
     $POST_=$FREQUEST->getRef("POST");
  
	if (count($POST_)>0){
		reset($POST_);
		//while(list($key,)=each($POST_)){
		foreach (array_keys($POST_) as $key)
			{
			$ACCOUNT[$key]=$FREQUEST->postvalue($key);
		}
	} 
  
    for ($icnt=0,$n=count($fieldsDesc);$icnt<$n;$icnt++){
		$fieldDesc=&$fieldsDesc[$icnt];
       	if (method_exists($customerAccount,"check__" . $fieldDesc['uniquename'])){
            $pass&=$customerAccount->{"check__" . $fieldDesc['uniquename']}($fieldDesc);
		} else {
            $pass&=$customerAccount->commonCheck($fieldDesc);
		}
	}

    for ($icnt=0,$n=count($customerAccount->errors);$icnt<$n;$icnt++){
        $error=true;
        $messageStack->add($stack_class,$customerAccount->errors[$icnt]);
   	}
 
     $address_array=array();

     if($error==false) {
         //while(list($key,$value)=each($ADDRESS)) {
		foreach (array_keys($ADDRESS) as $key =>$value)
			{	
            if(strpos($key,'entry')!==false) {
               $key1=str_replace('entry_','',$key);
            }
            else {
               $key1=$key;
            }
          $address_array[$key1]=$value;
        }
         $address_array['format_id']=$format_id;
         $address_array['customers_id']= $FSESSION->get('customer_id');
     }
 
   /*   if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($FREQUEST->postvalue('gender'));
      if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($FREQUEST->postvalue('company'));
      $firstname = tep_db_prepare_input($FREQUEST->postvalue('firstname'));
      $lastname = tep_db_prepare_input($FREQUEST->postvalue('lastname'));
      $street_address = tep_db_prepare_input($FREQUEST->postvalue('street_address'));
      if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($FREQUEST->postvalue('suburb'));
      $postcode = tep_db_prepare_input($FREQUEST->postvalue('postcode'));
      $city = tep_db_prepare_input($FREQUEST->postvalue('city'));
      $country = tep_db_prepare_input($FREQUEST->postvalue('country'));
      if (ACCOUNT_STATE == 'true') {
        if ($FREQUEST->postvalue('zone_id')) {
          $zone_id = tep_db_prepare_input($FREQUEST->postvalue('zone_id'));
        } else {
          $zone_id = false;
        }
       	$state = tep_db_prepare_input($FREQUEST->postvalue('state1'));
		if($state=='')
	        $state = tep_db_prepare_input($FREQUEST->postvalue('state'));
      }
      if (ACCOUNT_GENDER == 'true') {
        if ( ($gender != 'm') && ($gender != 'f') ) {
          $error = true;
          $messageStack->add($stack_class, ENTRY_GENDER_ERROR);
        }
      }
      if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
        $error = true;
        $messageStack->add($stack_class, ENTRY_FIRST_NAME_ERROR);
      }
      if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
        $error = true;
        $messageStack->add($stack_class, ENTRY_LAST_NAME_ERROR);
      }
      if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
        $error = true;
        $messageStack->add($stack_class, ENTRY_STREET_ADDRESS_ERROR);
      }
      if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
        $error = true;
        $messageStack->add($stack_class, ENTRY_POST_CODE_ERROR);
      }
      if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
        $error = true;
        $messageStack->add($stack_class, ENTRY_CITY_ERROR);
      }
      if (ACCOUNT_STATE == 'true') {
        $zone_id = 0;
        $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
        $check = tep_db_fetch_array($check_query);
        $entry_state_has_zones = ($check['total'] > 0);
        if ($entry_state_has_zones == true) {
          $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name like '" . tep_db_input($state) . "%' or zone_code like '%" . tep_db_input($state) . "%')");
          if (tep_db_num_rows($zone_query) == 1) {
            $zone = tep_db_fetch_array($zone_query);
            $zone_id = $zone['zone_id'];
          } else {
            $error = true;
            $messageStack->add($stack_class, ENTRY_STATE_ERROR_SELECT);
          }
        } else {
          if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
            $error = true;
            $messageStack->add($stack_class, ENTRY_STATE_ERROR);
          }
        }
      }
      if ( (is_numeric($country) == false) || ($country < 1) ) {
        $error = true;
        $messageStack->add($stack_class, ENTRY_COUNTRY_ERROR);
      } */
	 /* if ($error == false) {
	 	$delivery_result=tep_db_fetch_array($delivery_query);
        $sessions_array = array('customers_id' => $FSESSION->get('customer_id'),
								'format_id'=>$format_id,
                                'firstname' => $firstname,
                                'lastname' => $lastname,
                                'street_address' => $street_address,
                                'postcode' => $postcode,
                                'city' => $city,
								'country' => array('id' => $delivery_result['countries_id'], 'title' => $delivery_result['countries_name'], 'iso_code_2' => $delivery_result['countries_iso_code_2'], 'iso_code_3' => $delivery_result['countries_iso_code_3']),
                                'country_id' => $country);

        if (ACCOUNT_GENDER == 'true') $sessions_array['gender'] = $gender;
        if (ACCOUNT_COMPANY == 'true') $sessions_array['company'] = $company;
        if (ACCOUNT_SUBURB == 'true') $sessions_array['suburb'] = $suburb;
        if (ACCOUNT_STATE == 'true') {
          if ($zone_id > 0) {
            $sessions_array['zone_id'] = $zone_id;
            $sessions_array['state'] = $state;
          } else {
            $sessions_array['zone_id'] = '0';
            $sessions_array['state'] = $state;
          }
        }
	  $$session_array=$sessions_array; */
     if ($error == false) {
         $delivery_result=tep_db_fetch_array($delivery_query);
         $address_array['country']=array('id' => $delivery_result['countries_id'], 'title' => $delivery_result['countries_name'], 'iso_code_2' => $delivery_result['countries_iso_code_2'], 'iso_code_3' => $delivery_result['countries_iso_code_3']);
         $$address_array=$address_array;
     }
   
	if($mode=='P') {
		$FSESSION->set('billto_array',$address_array);
    }
	else {
		$FSESSION->set('sendto_array',$address_array);
    }
  }
 
?>

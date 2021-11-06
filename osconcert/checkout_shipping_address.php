<?php
/*
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
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
  
  	
	$country_check=tep_db_prepare_input($FREQUEST->postvalue('country'));
	if ($country_check == 999){
	    $cart->reset(true);
		$FSESSION->set('error_count',0);
		$FSESSION->remove('box_office_refund');
		$FSESSION->remove('BoxOffice');
		$FSESSION->remove('sendto');
		$FSESSION->remove('billto');
		$FSESSION->remove('shipping');
		$FSESSION->remove('payment');
		$FSESSION->remove('comments');
		$FSESSION->remove('order_timestamp');
		$FSESSION->remove('receiptNo');
		$FSESSION->remove('transactionNr');
		$FSESSION->remove('coupon');
		$FSESSION->remove('ccno');
		$FSESSION->remove('gv_redeem_code');
		$FSESSION->remove('billto_array');
		$FSESSION->remove('sendto_array');
		$FSESSION->remove('paypal_ipn_started');
		$FSESSION->remove('other');
		$FSESSION->remove('field_1'); 
		$FSESSION->remove('field_2');
		$FSESSION->remove('field_3');
		$FSESSION->remove('field_4');
		$FSESSION->remove('credit_covers');
	    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
	}


// if the customer is not logged on, redirect them to the login page
  if (!$FSESSION->is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  // needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_CHECKOUT_SHIPPING_ADDRESS);

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed

  $error = false;
  $process = false;
  if ($FREQUEST->postvalue('action') == 'submit') {
// process a new shipping/delivery address

    if (tep_not_null($FREQUEST->postvalue('firstname')) && tep_not_null($FREQUEST->postvalue('lastname')) && tep_not_null($FREQUEST->postvalue('street_address'))) {
      $process = true;

      if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($FREQUEST->postvalue('gender'));
      if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($FREQUEST->postvalue('company'));
      $firstname = tep_db_prepare_input($FREQUEST->postvalue('firstname'));
      $lastname = tep_db_prepare_input($FREQUEST->postvalue('lastname'));
      $street_address = tep_db_prepare_input($FREQUEST->postvalue('street_address'));
      if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($FREQUEST->postvalue('suburb'));
	 // if (ACCOUNT_CUSTOMER_EMAIL == 'true') $customer_email = tep_db_prepare_input($FREQUEST->postvalue('customer_email'));
      $postcode = tep_db_prepare_input($FREQUEST->postvalue('postcode'));
      $city = tep_db_prepare_input($FREQUEST->postvalue('city'));
      $country = tep_db_prepare_input($FREQUEST->postvalue('country'));
      if (ACCOUNT_STATE == 'true') {
        if (tep_not_null($FREQUEST->postvalue('state'))) {
          $zone_id = tep_db_prepare_input($FREQUEST->postvalue('state'));
        } else {
          $zone_id = false;
        }
        $state = tep_db_prepare_input($FREQUEST->postvalue('state1'));
      }
		
      // if (ACCOUNT_GENDER == 'true') {
        // if ( ($gender != 'm') && ($gender != 'f') ) {
          // $error = true;

          // $messageStack->add('checkout_address', ENTRY_GENDER_ERROR);
        // }
      // }

      if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_FIRST_NAME_ERROR);
      }

      if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_LAST_NAME_ERROR);
      }
	  // if (strlen($customer_email) < 5) {
        // $error = true;

        // $messageStack->add('checkout_address', ENTRY_EMAIL_ADDRESS_ERROR);
      // }

      if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_STREET_ADDRESS_ERROR);
      }

      if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_POST_CODE_ERROR);
      }

      if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_CITY_ERROR);
      }

      if (ACCOUNT_STATE == 'true') {
      //  $zone_id = 0;
		$state=tep_get_zone_name($country,$zone_id,$state);
        $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
        $check = tep_db_fetch_array($check_query);
        $entry_state_has_zones = ($check['total'] > 0);
        if ($entry_state_has_zones == true) {
          $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name like '" . tep_db_input($state) . "%' or zone_code like '%" . tep_db_input($state) . "%')");
          if (tep_db_num_rows($zone_query) == 1) {
            $zone = tep_db_fetch_array($zone_query);
            $zone_id = $zone['zone_id'];
          } else {
            $error = false;//true;

            $messageStack->add('checkout_address', ENTRY_STATE_ERROR_SELECT);
          }
        } else {
          if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
            $error = true;

            $messageStack->add('checkout_address', ENTRY_STATE_ERROR);
          }
        }
      }

      if ( (is_numeric($country) == false) || ($country < 1) ) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_COUNTRY_ERROR);
      }

      if ($error == false) {
        $sql_data_array = array('customers_id' => $FSESSION->customer_id,
                                'entry_firstname' => $firstname,
                                'entry_lastname' => $lastname,
                                'entry_street_address' => $street_address,
                                'entry_postcode' => $postcode,
                                'entry_city' => $city,
                                'entry_country_id' => $country);

        if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
        if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
		if (ACCOUNT_CUSTOMER_EMAIL == 'true') $sql_data_array['entry_customer_email'] = $customer_email;//new added 14-08-17
        if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
        if (ACCOUNT_STATE == 'true') {
          if ($zone_id > 0) {
            $sql_data_array['entry_zone_id'] = $zone_id;
            $sql_data_array['entry_state'] = '';
          } else {
            $sql_data_array['entry_zone_id'] = '0';
            $sql_data_array['entry_state'] = $state;
          }
        }

        if (!$FSESSION->is_registered('sendto')) $FSESSION->set('sendto',0);

        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

        //$FSESSION->sendto = tep_db_insert_id();
        $FSESSION->set('sendto',tep_db_insert_id());
        if ($FSESSION->is_registered('shipping')) $FSESSION->remove('shipping');

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
      }
// process the selected shipping destination
    } elseif ($FREQUEST->postvalue('address')!='') {
      $reset_shipping = false;
      if ($FSESSION->is_registered('sendto')) {
        if ($FSESSION->get('sendto') != tep_db_prepare_input($FREQUEST->postvalue('address'))) {
          if ($FSESSION->is_registered('shipping')) {
            $reset_shipping = true;
          }
        }
      } else {
        $FSESSION->set('sendto',0);
      }

      $FSESSION->set('sendto',tep_db_prepare_input($FREQUEST->postvalue('address')));
//line with sendto functions well
      $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$FSESSION->get('customer_id') . "' and address_book_id = '" . (int)$FSESSION->get('sendto') . "'");
      $check_address = tep_db_fetch_array($check_address_query);

      if ($check_address['total'] == '1') {
        if ($reset_shipping == true) $FSESSION->remove('shipping');
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
      } else {
        $FSESSION->remove('sendto');
      }
    } else {
      if (!$FSESSION->is_registered('sendto')) $FSESSION->set('sendto',$FSESSION->get('customer_default_address_id'));
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }


  if ($order->content_type == 'virtual') {
    if (!$FSESSION->is_registered('shipping')) $FSESSION->set('shipping',false);
    if (!$FSESSION->is_registered('sendto')) $FSESSION->set('sendto',false);
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }
// if no shipping destination address was selected, use their own address as default
  if (!$FSESSION->is_registered('sendto')) {
    $FSESSION->set('sendto',$FSESSION->get('customer_default_address_id'));
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL'));

  $addresses_count = tep_count_customer_address_book_entries();

  $content = CONTENT_CHECKOUT_SHIPPING_ADDRESS;
  $javascript = $content . '.js.php';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>

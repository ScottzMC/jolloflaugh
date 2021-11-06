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

  	Copyright (c) 2009-2017 osConcert

	Released under the GNU General Public License
*/

// Set flag that this is a parent file
	define( '_FEXEC', 1 );

$update_address = (isset($_POST['update_address']) && (bool)$_POST['update_address'] === true);
$getID = (int)( (isset($_POST['id']) && (int)$_POST['id']>0) ? $_POST['id'] : 0 );	

  require('includes/application_top.php');

// var_dump($FSESSION);

	
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
  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_CHECKOUT_PAYMENT_ADDRESS);

  $error = false;
  $process = false;
 // print_r ($FREQUEST);
  if ($FREQUEST->postvalue('action') == 'submit') {
// process a new billing address
    if (tep_not_null($FREQUEST->postvalue('firstname')) && tep_not_null($FREQUEST->postvalue('lastname'))/* && tep_not_null($FREQUEST->postvalue('street_address'))*/) {
      $process = true;
	
     
	  if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($FREQUEST->postvalue('gender'));
      if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($FREQUEST->postvalue('company'));
      $firstname = tep_db_prepare_input($FREQUEST->postvalue('firstname'));
      $lastname = tep_db_prepare_input($FREQUEST->postvalue('lastname'));
	 // $customer_telephone = tep_db_prepare_input($FREQUEST->postvalue('customer_telephone'));
	  $email = tep_db_prepare_input($FREQUEST->postvalue('email'));
      $street_address = tep_db_prepare_input($FREQUEST->postvalue('street_address'));
      if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($FREQUEST->postvalue('suburb'));
	  if (ACCOUNT_CUSTOMER_EMAIL == 'true') 
	  $customer_email = tep_db_prepare_input($FREQUEST->postvalue('customer_email'));
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
/*
      if (ACCOUNT_GENDER == 'true') {
        if ( ($gender != 'm') && ($gender != 'f') ) {
          $error = true;

          $messageStack->add('checkout_address', ENTRY_GENDER_ERROR);
        }
      }
*/
      if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_FIRST_NAME_ERROR);
      }

      if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_LAST_NAME_ERROR);
      }
	  
	  //email validation here
	    $error = false; // reset error flag
		
		if($customer_email!=''){
		if (!tep_validate_email($customer_email)) {
		$error = true;
		$messageStack->add('checkout_address', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
		}
		}
	  
	  // if (strlen($customer_email) < 5) {
        // $error = true;

        // $messageStack->add('checkout_address', ENTRY_EMAIL_ADDRESS_ERROR);
      // }
/*
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
*/

      if (ACCOUNT_STATE == 'true') {
       // $zone_id = 0;
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
            $error = false; //cartzone set from true to false to stop dropdown menu error NOv 2010

            $messageStack->add('checkout_address', ENTRY_STATE_ERROR_SELECT2);
          }
        } else {
          if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
            $error = true;

            $messageStack->add('checkout_address', ENTRY_STATE_ERROR);
          }
        }
      }
	
/*
      if ( (is_numeric($country) == false) || ($country < 1) ) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_COUNTRY_ERROR);
      }
*/
      if ($error == false) {
		  
		  
		//IMPORTANT: Either we set the customer_id of the new Billing Address Customer to the Box Office User Customer ID or we set to zero. Zero setting does not add tax May2018. I'm not sure if it's a bad thing!
		//Box Office owns the Billing Name and Address??
		
		  
        //$sql_data_array = array('customers_id' => $FSESSION->customer_id,
		$sql_data_array = array('customers_id' => (isset($_SESSION['BoxOffice']) && $_SESSION['BoxOffice']== 999 ? 0 : $FSESSION->customer_id),
                                'entry_firstname' => $firstname,
                                'entry_lastname' => $lastname,
                                'entry_street_address' => $street_address,
                                'entry_postcode' => $postcode,
                                'entry_city' => $city,
                                'entry_country_id' => $country);

        if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
        if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
        if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
		if (ACCOUNT_CUSTOMER_EMAIL == 'true') $sql_data_array['entry_customer_email'] = $customer_email;
        if (ACCOUNT_STATE == 'true') {
          if ($zone_id > 0) {
            $sql_data_array['entry_zone_id'] = $zone_id;
            $sql_data_array['entry_state'] = $state;
          } else {
            $sql_data_array['entry_zone_id'] = '0';
            $sql_data_array['entry_state'] = $state;
          }
        }
        // if (ACCOUNT_TELEPHONE == 'true') {
			// if (strlen($customer_telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
			// $error = true;
			// $entry_telephone_error = true;
			// } else {
			// $entry_telephone_error = false;
			// }
			// }
		if($update_address && $getID > 0)
		{
			tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', 'address_book_id='.$getID);
			$FSESSION->set('billto',$getID);
			if (($FREQUEST->cookievalue('customer_is_guest'))){
				tep_redirect(tep_href_link('checkout_payment.php', '', 'SSL'));
			}else{
				tep_redirect(tep_href_link('checkout_payment_address.php', '', 'SSL'));
			}
		}
		else
		{		
			tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
			if (!$FSESSION->is_registered('billto')) $FSESSION->set('billto',0);
			$FSESSION->set('billto',tep_db_insert_id());
			if ($FSESSION->is_registered('payment')) $FSESSION->remove('payment');
	
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
		}
      }
// process the selected billing destination
    } elseif ($FREQUEST->postvalue('address')!='') {
      $reset_payment = false;
      if ($FSESSION->is_registered('billto')) {
        if ($FSESSION->billto != $FREQUEST->postvalue('address')) {
          
		  $FSESSION->set('billto_default',$FREQUEST->postvalue('address'));
		  $FSESSION->set('billto',$FREQUEST->postvalue('address'));
	//	  $FSESSION->set('sendto',$FREQUEST->postvalue('address'));
		  
		  if ($FSESSION->is_registered('payment')) {
            $reset_payment = true;
          }
        }
      } else {
        $FSESSION->set('billto',0);
      }

      $billto = $FREQUEST->postvalue('address');
	  $FSESSION->set('billto',$billto);
     
	// var_dump(tep_db_input((int)$FSESSION->billto));
	 
	 //$check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$FSESSION->customer_id . "' and address_book_id = '" . tep_db_input($FSESSION->billto) . "'");
	 $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . tep_db_input((int)$FSESSION->billto) . "'");
      $check_address = tep_db_fetch_array($check_address_query);

      if ($check_address['total'] == '1') {
        if ($reset_payment == true) $FSESSION->remove('payment');
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
      } else {
        $FSESSION->remove('billto');
      }
// no addresses to select from - customer decided to keep the current assigned address
    } else {
      if (!$FSESSION->is_registered('billto')) $FSESSION->set('billto',0);
	  //else
      //$FSESSION->set('billto',$FSESSION->customer_default_address_id);

      tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
    }
  }

// if no billing destination address was selected, use their own address as default
  if (!$FSESSION->is_registered('billto')) {
    //$FSESSION->set('billto',$FSESSION->customer_default_address_id);
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL'));

  $addresses_count = tep_count_customer_address_book_entries();

  $content = CONTENT_CHECKOUT_PAYMENT_ADDRESS;
  $javascript = $content . '.js.php';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
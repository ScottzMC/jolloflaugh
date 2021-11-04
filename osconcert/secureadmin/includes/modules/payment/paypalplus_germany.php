<?php
/*
 osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	osConcert
Copyright (c) 2021 osConcert.com
	Released under the GNU General Public License 
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();


  class paypalplus_germany 
  {
    var $code, $title, $description, $enabled, $notify_url, $curl, $add_shipping_to_amount, $add_tax_to_amount, $update_stock_before_payment, $allowed_currencies, $default_currency, $test_mode;

// class constructor
	function __construct() 
	{
		global $order;
		
		$this->code = 'paypalplus_germany';
		$name = "Paypal+ (Germany)";
		$image = "";
		if(MODULE_PAYMENT_PAYPALPLUS_DE_DISPLAY_NAME != "MODULE_PAYMENT_PAYPALPLUS_DE_DISPLAY_NAME")$name = MODULE_PAYMENT_PAYPALPLUS_DE_DISPLAY_NAME;
		if(MODULE_PAYMENT_PAYPALPLUS_DE_IMAGE != "MODULE_PAYMENT_PAYPALPLUS_DE_IMAGE")
		$image = MODULE_PAYMENT_PAYPALPLUS_DE_IMAGE;
		if(DIR_WS_ADMIN != "DIR_WS_ADMIN" && DIR_WS_ADMIN != "")$path = "../";
		if($image != "" && file_exists($path . DIR_WS_IMAGES . $image))
		{
			$image = '<img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_WS_IMAGES . $image . '" height="33">';
		}else
		{
			$image_array = array('.png','.jpg','.jpeg','.gif');
			$image_check = true;
			for($i=0;$i<sizeof($image_array);$i++)
			{
				if($image_check && $image != "" && file_exists($path . DIR_WS_IMAGES . $image . $image_array[$i]))
				{
					$image = '<img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_WS_IMAGES . $image . $image_array[$i] . '" width="103" height="33">';
					$image_check = false;
				}
			}
			if($image_check)$image = $path;
		}
		define('MODULE_PAYMENT_PAYPALPLUS_DE_TEXT_TITLE', $name . '&nbsp;&nbsp;' . $image);
		define('MODULE_PAYMENT_PAYPALPLUS_DE_TEXT_TEXT_TITLE', $name);
		$this->title = MODULE_PAYMENT_PAYPALPLUS_DE_TEXT_TITLE;
		$this->text_title = MODULE_PAYMENT_PAYPALPLUS_DE_TEXT_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_PAYPALPLUS_DE_TEXT_DESCRIPTION;
		$this->sort_order = MODULE_PAYMENT_PAYPALPLUS_DE_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_PAYPALPLUS_DE_STATUS == 'True') ? true : false);
		$this->notify_url = MODULE_PAYMENT_PAYPALPLUS_DE_NOTIFY_URL;

		$this->test_mode = ((MODULE_PAYMENT_PAYPALPLUS_DE_TEST_MODE == 'True') ? true : false);
		$this->barred=false;
		if ((int)MODULE_PAYMENT_PAYPALPLUS_DE_ORDER_STATUS_ID > 0) {
			$this->order_status = MODULE_PAYMENT_PAYPALPLUS_DE_ORDER_STATUS_ID;
		}
		
		if (is_object($order)) $this->update_status();
		
		$this->form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS,'','SSL');
		
		// set timezone
			//date_default_timezone_set('UCT');
			date_default_timezone_set(STORE_TIMEZONE);
		// build API Credentials
			define('API_CREDENTIALS', MODULE_PAYMENT_PAYPALPLUS_DE_ID.':'.MODULE_PAYMENT_PAYPALPLUS_DE_SECRET);
		//sandbox or live url
				    $this->data_url='admin/logs/data_live.json';
					$this->ppurl   ='api.paypal.com';
			      if (MODULE_PAYMENT_PAYPALPLUS_DE_TEST_MODE=='True') {
				    $this->data_url='admin/logs/data_test.json';
					$this->ppurl   ='api.sandbox.paypal.com';
					   }
		if(ENABLE_SSL_CATALOG=='true' || ENABLE_SSL_CATALOG==1)
		{$this->store_url=HTTPS_CATALOG_SERVER.DIR_WS_CATALOG;}
		else
		{$this->store_url=HTTP_SERVER.DIR_WS_CATALOG;}
	}

// class methods
    function update_status() 
    {
      global $order;

  	  tep_check_module_status($this,MODULE_PAYMENT_PAYPALPLUS_DE_ZONE,trim(MODULE_PAYMENT_PAYPALPLUS_DE_EXCEPT_ZONE),trim(MODULE_PAYMENT_PAYPALPLUS_DE_EXCEPT_COUNTRY));	
  	  $this->barred=tep_check_payment_barred(trim(MODULE_PAYMENT_PAYPALPLUS_DE_EXCEPT_COUNTRY));
	// if (MODULE_PAYMENT_PAYPALPLUS_DE_ID != OWNER_IDENTIFICATION) 
	//	  {
	//		tep_mail('Security Monitor ', STORE_OWNER_EMAIL_ADDRESS, "PayPal Hack Alert", 'The Pay Pal account has been changed to '. MODULE_PAYMENT_PAYPAL_API_ID . '.' . "\n\n" . 'This could indicate you site has been hacked.' . "\n\n" . 'The PayPal module has been deactivated', STORE_OWNER.":", STORE_OWNER_EMAIL_ADDRESS);
	//		$this->enabled = false;
	//		}
    }

    function javascript_validation() 
	{
      return false;
    }

    function selection() 
	{
	global $FSESSION;

		//if a customer has paid at PayPal and NOT returned to the store then
	//this session may still be active - kill it here for simplicity.
    if (isset($_SESSION['paypalplus_de_cart']))
    {
               $FSESSION->remove('paypalplus_de_cart');
    }
	
		tep_db_query("CREATE TABLE IF NOT EXISTS `customers_away_basket` LIKE `customers_basket`");
		tep_db_query("DELETE FROM `customers_away_basket` where `customers_id`='".$_SESSION['customer_id']."'");
	//
      return array('id' => $this->code,
	  				'barred'=>$this->barred,
                   'module' => $this->title);
    }

    function pre_confirmation_check() 
    {
      return false;
    }

    function confirmation()
    {
        global $FREQUEST, $FSESSION;
		
		$this -> GetApprovalURL();
		 

		if (MODULE_PAYMENT_PAYPALPLUS_DE_TEST_MODE=='True') 
		{
		$explanation='TEST MODE<br>Paypal Sandbox in operation.';
		}else
		{$explanation = '';
		}

		 
        $confirmation = array('title' => $this->title,
        'fields' => array(array('title' => MODULE_PAYMENT_PAYPALPLUS_DE_TEXT_CONFIRNATION ,
        						'field' => $explanation . '<div id="ppplus"></div>')));


        
        return $confirmation;
    }

    function process_button() 
    {
      return false;
    }

    function before_process()
    {   global $FSESSION;

		
	    $FSESSION->remove('paypalplus_de_cart');
		$FSESSION->remove('paypalplus_de_return');
		$FSESSION->remove('paypalplus_de_id');

       	//copy basket contents in case of return
        tep_db_query("DELETE  from `customers_away_basket` where `customers_id`='".$_SESSION['customer_id']."'");

		tep_db_query("INSERT IGNORE `customers_away_basket` SELECT * FROM `customers_basket` where `customers_id`='".$_SESSION['customer_id']."'");
		
		
		try{
		$this->GetAccessToken();
			}
		catch (Exception $e) 
		{
			$error = $e->getMessage(); 
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $error, 'SSL'));
		}
	
		if(empty($_SESSION['webprofilID']) || (empty($_SESSION['access_token']))|| (empty($_SESSION['patch_url'])))
		{
				$error = MODULE_PAYMENT_PAYPALPLUS_DE_GENERAL_ERROR;
                tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $error, 'SSL'));
		}
	
		//create the order
        return false;
    }


    function before_email() 
    {
	
	  global $order, $insert_id, $order_total_modules, $currencies, $FSESSION, $fields, $merge_details, $send_details, $_POST;
	  $FSESSION->remove('paypalplus_de_return');
	// check to see that we have the access token and experience for PayPal - if not then cancel the order and refill basket
			if(empty($_SESSION['webprofilID']) || (empty($_SESSION['access_token']))){
				$error = MODULE_PAYMENT_PAYPALPLUS_DE_POST_ERROR;
                		$this->repopulate_cart($insert_id,'','',MODULE_PAYMENT_PAYPALPLUS_DE_POST_ERROR);
						exit();
	     	}
	// pop order_id into session
	
	// fill the email data table
	$sql_data_array = array(
				'order_id' => $insert_id,
				'customer_id' => $_SESSION['customer_id'],
				'send_data' => base64_encode( serialize($send_details)),
				'merge_data' => base64_encode( serialize($merge_details))
				);
	tep_db_perform('email_data', $sql_data_array);
				 
	########################################
	#patch the sale with the invoice number
	######################################
	$url = $FSESSION ->get('patch_url');
	$FSESSION ->remove('patch_url');
		
		$JSONrequest = 	
		'[
            {
              "op": "replace",
              "path": "/transactions/0/invoice_number",
              "value": "'.$insert_id.'"
            }
          ]';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$_SESSION['access_token']
		));
		
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $JSONrequest);

	$result = curl_exec($ch); 

	if(curl_error($ch))	
	{ 
	 //even though we have a success PayPal will often return an error 'NSS: client certificate not found (nickname not specified)'
	 //on some servers - this should not be allowed to trip the error here as
	 //the sale can still go ahead
	if (curl_error($ch) != "NSS: client certificate not found (nickname not specified)")
	{
	$this->repopulate_cart($insert_id,'','',curl_error($ch).' Patch: '.MODULE_PAYMENT_PAYPAL_API_POST_ERROR); 
	}
	}
	 curl_close ($ch);
	 $resultGetApprovalURL = json_decode($result,true);
	 
/* 	 echo '<br>';
	 
	 echo $resultGetApprovalURL['name'];
	 	 echo '<br>';
	 echo $resultGetApprovalURL['details'][0]['field'];
	 	 echo '<br>';
	 echo $resultGetApprovalURL['details'][0]['issue'];
	 	 echo '<br>';
	 exit('<pre>'.var_dump(($resultGetApprovalURL))); */
	 
	 //invalid zip code? Test here and reset cart'payment_error=' . $this->code . '&error=' . $error
	 if (empty($resultGetApprovalURL['id']))
	 {
		 $this->repopulate_cart($insert_id,'','payment_error=' . $this->code . '&error=Error: '.$resultGetApprovalURL['details'][0]['issue'] .': '. $resultGetApprovalURL['details'][0]['field'],MODULE_PAYMENT_PAYPALPLUS_DE_POST_ERROR .' Error: '.$resultGetApprovalURL['details'][0]['issue'] .': '. $resultGetApprovalURL['details'][0]['field']); 
	 }
	 tep_db_query("update orders set payment_return1 = '".$resultGetApprovalURL['id']."' where orders_id = '".$insert_id."'");
	 
	 	// paypal token - may be needed for the cancel??? This is POORLY DOCUMENTED by PayPal 
		// i.e. NO documentation at all. It is important that an order cannot be cancelled by subterfuge so we do need some 
		// sort of security check
		$string_to_cut = $resultGetApprovalURL['links'][2]['href'];
		$token = substr($string_to_cut, strrpos($string_to_cut, '=') + 1);
		$FSESSION->set('paypalplus_de_token',$token); 
		
		
		tep_db_query("update orders set payment_return2 = '" . $token . "' where orders_id = '" . $insert_id . "'");
		         // swap out baskets and send to PayPal
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$FSESSION->customer_id . "'");


        $FSESSION->set('error_count', 0);
        $FSESSION->set('paypalplus_de_id', $insert_id);
        $FSESSION->set('paypalplus_de_cart', $_SESSION['cart']);
        $FSESSION->set('cart', '');
        $FSESSION->set('merge_details', $merge_details);
        $FSESSION->set('send_details', $send_details);
        $FSESSION->remove('expiry');
        $FSESSION->remove('expires_in');

        $FSESSION->remove('app_id');
        $FSESSION->remove('token_type');
     sleep(1);
		?>
			<html>
			<head>
			</head>
			<body>
			<script src="https://www.paypalobjects.com/webstatic/ppplus/ppplus.min.js" type="text/javascript">
		</script>
		<script>
			PAYPAL.apps.PPP.doCheckout();
		</script>
			</body>
			</html>
	<?php
				
	  exit();
      return false;
	 
    }
    function after_process() 
    {
	//used in paypal webhook not used in main checkout flow
	 global $FSESSION;
	
		try{
		$this->GetAccessToken();
		//$this->GetApprovalURL();
			}
		catch (Exception $e) 
		{
			$error = $e->getMessage(); 
		   //tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $error, 'SSL'));
		}
      return $resultGetAccessToken;
    }

    function output_error() 
    {
      return false;
    }
	function get_error() 
	{
      global $FREQUEST;
	  
	  $response_text=$FREQUEST->getvalue('response_text');
	  $error=$FREQUEST->getvalue('error');

      $msg = "";
      if (stripslashes(urldecode($response_text)) != "")
        $msg = stripslashes(urldecode($response_text));

      else if (stripslashes(urldecode($error)) != "") 
        $msg = stripslashes(urldecode($error));
      $error = array('title' => '',
	                 'error' => $msg);

      return $error;
    }
    function check() 
    {
      if (!isset($this->_check)) 
      {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPALPLUS_DE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }
	
    function install() 
    {
      global $FSESSION;


        // OK lets add in a new Customer PayPal  - Cancelled
        $check_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_lastname = 'PayPal+  - cancelled' limit 1");
        if (tep_db_num_rows($check_query) < 1) 
		{
            $sql_data_array = array('customers_lastname' => 'PayPal+  - cancelled',
            );
            tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);
            $cust_id = tep_db_insert_id();
        } else 
		{
            $check = tep_db_fetch_array($check_query);
            
            $cust_id = $check['customers_id'];
        }
		// OK lets addin a new order status fpr preparing
	        $check_query = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'Preparing [PayPal+]' limit 1");

      if (tep_db_num_rows($check_query) < 1) 
      {
        $status_query = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
        $status = tep_db_fetch_array($status_query);

        $status_id = $status['status_id']+1;

        $languages = tep_get_languages();

        foreach ($languages as $lang) 
		{
          tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id . "', '" . $lang['id'] . "', 'Preparing [PayPal+]')");
        }
      } else 
	  {
        $check = tep_db_fetch_array($check_query);

        $status_id = $check['orders_status_id'];
      }
	  
	          // OK lets add in a new order status for cancelled
        $check_query1 = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'PayPal+  Error/Cancelled' limit 1");
        
        if (tep_db_num_rows($check_query1) < 1) 
		{
            $status_query1 = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
            $status1 = tep_db_fetch_array($status_query1);
            $status_id1 = $status1['status_id']+1;
            $languages = tep_get_languages();
            foreach($languages as $lang) 
			{
                tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id1 . "', '" . $lang['id'] . "', 'PayPal+  Error/Cancelled')");
                $status_id1= tep_db_insert_id();
            }
        } else 
		{
            $check1 = tep_db_fetch_array($check_query1);
            
            $status_id1 = $check1['orders_status_id'];
        }

		//new database table
        tep_db_query("CREATE TABLE IF NOT EXISTS `email_data` (
				  `email_id` int(11) NOT NULL auto_increment,
				  `customer_id` int(11) NOT NULL,
				  `order_id` int(11) NOT NULL,
				  `send_data` longtext NOT NULL,
				  `merge_data` longtext NOT NULL,
				  PRIMARY KEY  (`email_id`)
				)  AUTO_INCREMENT=1 ;");
				 
	if(ENABLE_SSL_CATALOG=='true' || ENABLE_SSL_CATALOG==1)
	{$url=HTTPS_CATALOG_SERVER.DIR_WS_CATALOG;}
	else
	{$url=HTTP_SERVER.DIR_WS_CATALOG;}

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use PayPal+?', 'MODULE_PAYMENT_PAYPALPLUS_DE_STATUS', 'True', 'Do you want to accept PayPal Plus payments and notifications?', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Client ID', 'MODULE_PAYMENT_PAYPALPLUS_DE_ID', '', 'Your REST API Client ID', '6', '20', now())");
	  
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Secret', 'MODULE_PAYMENT_PAYPALPLUS_DE_SECRET', '', 'Your REST API Secret', '6', '22', now())");
	  
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Sandbox (Test) Mode?', 'MODULE_PAYMENT_PAYPALPLUS_DE_TEST_MODE','False', 'Run in TEST MODE? ', '6', '40', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('PayPal+ Payment Zone', 'MODULE_PAYMENT_PAYPALPLUS_DE_ZONE', '2', 'If a zone is selected, only enable this payment method for that zone.', '6', '42', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
	  
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Paypal+ Exclude these Countries', 'MODULE_PAYMENT_PAYPALPLUS_DE_EXCEPT_COUNTRY', '', 'If countries are selected, disable this payment method for that countries.', '6', '43', 'tep_cfg_pull_down_zone_except_countries(MODULE_PAYMENT_PAYPALPLUS_DE_ZONE,', 'tep_get_zone_except_country', now())");
	  
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Paypal+ exclude these Zones', 'MODULE_PAYMENT_PAYPALPLUS_DE_EXCEPT_ZONE', '', 'If a zone is selected, disable this payment method for that zone.', '6', '44', 'tep_cfg_pull_down_zone_classes(','tep_get_zone_class_title', now())");
	//  order status
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Successful Order Status', 'MODULE_PAYMENT_PAYPALPLUS_DE_COMP_ORDER_STATUS_ID', '3', 'Set the status of successful orders made with this payment module to this value. Delivered = download tickets.', '6', '45', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
	//pending  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('PayPal+ Set Order Status', 'MODULE_PAYMENT_PAYPALPLUS_DE_ORDER_STATUS_ID', '".$status_id."', 'Set the status of pending orders made with this payment module to this value - <b>do not use</b> Delivered! ', '6', '46', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
	//cancelled
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Failed/Error Order Status', 'MODULE_PAYMENT_PAYPALPLUS_DE_CANX_ORDER_STATUS_ID', '".$status_id1."', 'Set the status of failed/error orders made with this payment module to this value. Suggested value is PayPal  Error/Cancelled - <b>do not use</b> Delivered! ', '6', '61', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
			//////

				  
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order,  date_added) values ('PayPal+  Cancelled Customer ID', 'MODULE_PAYMENT_PAYPALPLUS_DE_DUMMY_CUSTOMER_ID', '".$cust_id."', 'Customer id number for failed/cancelled orders - should not need editing', '6', '63', now())");	  
		  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Display Name', 'MODULE_PAYMENT_PAYPALPLUS_DE_DISPLAY_NAME', 'PayPal+ / Credit Cards', 'Set the Display name to payment module', '6', '70', now())");
	  

	  
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PayPal+ Sort order of display.', 'MODULE_PAYMENT_PAYPALPLUS_DE_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', '6', '11', now())");
		
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Logo to display at PayPal', 'MODULE_PAYMENT_PAYPALPLUS_DE_LOGO', '', 'URL (https) to a logo - leave blank if not available', '6', '111', now())");
					
		 tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Name to display at PayPal', 'MODULE_PAYMENT_PAYPALPLUS_DE_NAME', '".STORE_NAME."', 'Name to display at PayPal', '6', '111', now())");
		 
		//tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Log API calls', 'MODULE_PAYMENT_PAYPALPLUS_DE_LOGGING', 'False', 'Do you want to log API data. ?', '6', '200', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		
		//tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Debug emails?', 'MODULE_PAYMENT_PAYPALPLUS_DE_DEBUG_EMAIL', '', 'Do you want to have API data emailed?(empty = no)', '6', '200',  now())");
	  
		 tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Add rounding amount at PayPal?', 'MODULE_PAYMENT_PAYPALPLUS_DE_ROUNDING','False', ' ', '6', '115', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	
	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Paypal Image', 'MODULE_PAYMENT_PAYPAL_API_IMAGE', 'paypalcards.png', 'Paypal Image', '6', '119', now())");

	//tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Log API calls', 'MODULE_PAYMENT_PAYPALPLUS_DE_LOGGING', 'False', 'Do you want to log API data. ?', '6', '200', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	}

    function remove() 
    {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() 
    {
      return array(
      'MODULE_PAYMENT_PAYPALPLUS_DE_STATUS', //
	  'MODULE_PAYMENT_PAYPALPLUS_DE_ID',//
	  'MODULE_PAYMENT_PAYPALPLUS_DE_SECRET',//
	   'MODULE_PAYMENT_PAYPALPLUS_DE_SORT_ORDER',
	  'MODULE_PAYMENT_PAYPALPLUS_DE_TEST_MODE', //
	  'MODULE_PAYMENT_PAYPALPLUS_DE_ZONE', //
	  'MODULE_PAYMENT_PAYPALPLUS_DE_EXCEPT_ZONE', //
	  'MODULE_PAYMENT_PAYPALPLUS_DE_EXCEPT_COUNTRY', //
	  'MODULE_PAYMENT_PAYPALPLUS_DE_ORDER_STATUS_ID', //
	  'MODULE_PAYMENT_PAYPALPLUS_DE_SORT_ORDER',
	  'MODULE_PAYMENT_PAYPALPLUS_DE_DISPLAY_NAME',//
	  'MODULE_PAYMENT_PAYPALPLUS_DE_COMP_ORDER_STATUS_ID',//
	  'MODULE_PAYMENT_PAYPALPLUS_DE_IMAGE',
	 // 'MODULE_PAYMENT_PAYPALPLUS_DE_LOGGING',
	 //'MODULE_PAYMENT_PAYPALPLUS_DE_DEBUG_EMAIL',
	  'MODULE_PAYMENT_PAYPALPLUS_DE_CANX_ORDER_STATUS_ID',//
	 
	 'MODULE_PAYMENT_PAYPALPLUS_DE_NAME', 
	 'MODULE_PAYMENT_PAYPALPLUS_DE_LOGO', //
	 'MODULE_PAYMENT_PAYPALPLUS_DE_ROUNDING',
	  'MODULE_PAYMENT_PAYPALPLUS_DE_DUMMY_CUSTOMER_ID');//
    }
	
	//paypal functions
	function GetAccessToken()
	{
	global $FSESSION;
	$FSESSION->remove('access_token');
	$FSESSION->remove('webprofilID');
	$FSESSION->remove('JSONrequest');



//Get Access Token
if(empty($_SESSION['access_token']))
{
# check for valid access token
	$ts_now = time();
	$jsonDATA = (array) json_decode(file_get_contents( $this->data_url, true));
	if (!empty($jsonDATA)) {
		$_SESSION['expiry'] = $jsonDATA['expiry'];
		$_SESSION['access_token'] = $jsonDATA['access_token'];
		$_SESSION['app_id'] = $jsonDATA['app_id'];
		$_SESSION['token_type'] = $jsonDATA['token_type'];
		$_SESSION['webprofilID'] = $jsonDATA['webprofilID'];
		} else { $jsonDATA['expiry'] = 0;
		} 

	if ( $ts_now > $jsonDATA['expiry'] ) 
	{
		$url='https://'.$this->ppurl.'/v1/oauth2/token';
		$JSONrequest= 'grant_type=client_credentials';

		$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			//curl_setopt($ch, CURLOPT_SSLCERT, $sslcertpath);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Accept: application/json',
				'Accept-Language: de_DE'
				));
			curl_setopt($ch, CURLOPT_USERPWD, API_CREDENTIALS);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $JSONrequest);

		$result = curl_exec($ch); 
		$resultGetAccessToken = json_decode($result,true);

		curl_close ($ch);
		$_SESSION['expiry'] = time() + $resultGetAccessToken['expires_in'];
		$_SESSION['access_token'] = $resultGetAccessToken['access_token'];
		$_SESSION['app_id'] = $resultGetAccessToken['app_id'];
		$_SESSION['token_type'] = $resultGetAccessToken['token_type'];
		$jsonSTRING = '{ "expiry":"'.$resultGetAccessToken['expires_in'].'" , "access_token":"'.$_SESSION['access_token'].'" , "app_id":"'.$_SESSION['app_id'].'","token_type":"'.$_SESSION['token_type'].'","webprofilID":"'.$_SESSION['webprofilID'].'"}';
		file_put_contents($this->data_url, $jsonSTRING);
	} 
}

if(empty($_SESSION['webprofilID']))
{
	$url='https://'.$this->ppurl.'/v1/payment-experience/web-profiles';
	$JSONrequest= '{"name": "'.STORE_NAME.rand(0,10000).'"
	,"presentation": {
	 "brand_name": "'.MODULE_PAYMENT_PAYPALPLUS_DE_NAME.'"

	}
	,"input_fields": {
		"allow_note": false,
		"no_shipping": 2,
		"address_override": 1
	}}';
	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_SSLCERT, $sslcertpath);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer '.$_SESSION['access_token']
			));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $JSONrequest);

	$result = curl_exec($ch); 
	$resultGetExpreienceProfile = json_decode($result,true);

	curl_close ($ch);
	$_SESSION['webprofilID'] = $resultGetExpreienceProfile['id'];
	$jsonSTRING = '{ "expiry":"'.$_SESSION['expiry'].'" , "access_token":"'.$_SESSION['access_token'].'" , "app_id":"'.$_SESSION['app_id'].'","token_type":"'.$_SESSION['token_type'].'","webprofilID":"'.$_SESSION['webprofilID'].'"}';
	file_put_contents($this->data_url, $jsonSTRING);
	
}

}

###################################
# repopulate cart
###################################
	function repopulate_cart($order_id,$redirect_page=FILENAME_CHECKOUT_PAYMENT,$extras='', $note='')
	{
    global $FSESSION;

    // if we have the order_id then do stuff
		if (tep_not_null($order_id)) 
		{
        //grab customers name from order
        $cust_query = tep_db_query("select customers_name from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
        if (tep_db_num_rows($cust_query) ) {
            $cust_query_result = tep_db_fetch_array($cust_query);
            $cust_name = $cust_query_result['customers_name'];
        }
        //change the order status
        $sql_data_array = array('orders_status' => MODULE_PAYMENT_PAYPALPLUS_DE_CANX_ORDER_STATUS_ID,
        'customers_id'  => MODULE_PAYMENT_PAYPALPLUS_DE_DUMMY_CUSTOMER_ID,
        'customers_name'=> 'PayPal+ -cancelled::'.$cust_name)
        ;
        tep_db_perform(TABLE_ORDERS, $sql_data_array, "update", "orders_id='".$order_id."'");

        
        $sql_data_array = array('orders_id' => $order_id,
        'orders_status_id' => MODULE_PAYMENT_PAYPALPLUS_DE_CANX_ORDER_STATUS_ID,
        'date_added' => 'now()',
        'customer_notified' => 0,
        'comments' => $note );
        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
      
		include_once('includes/functions/ga_tickets.php');

        $order_query = tep_db_query("select products_id, products_quantity, events_type from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $order_id. "'");
        while ($order = tep_db_fetch_array($order_query)) 
		{
            tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order['products_quantity'] . ", products_ordered = products_ordered - " . $order['products_quantity'] . ", products_status='1' where products_id = '" . (int)$order['products_id'] . "'");

			if (function_exists('ga_check_process_restock'))
			{
		  
			ga_check_process_restock((int)$order['products_id'], $order['products_quantity'], $order['events_type']);	
			}													

        }

        tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $order_id. "'");
        tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set products_quantity = '0' where orders_id = '" . $order_id . "'");
        tep_db_query("insert into " . TABLE_ORDERS_TOTAL. " (orders_id, title, text, value, class, sort_order) values ('" . $order_id . "', 'Total', '0.00', '0','ot_total', '99')");
		
		
        
         
	
	    	$FSESSION->remove('paypalplus_de_temp_id');
		
	
			if ($FSESSION->is_registered('paypalplus_de_cart')) 
			{
				$FSESSION->set('cart',$_SESSION['paypalplus_de_cart']);
				$FSESSION->remove('paypalplus_de_cart');
		
			tep_db_query("DELETE FROM `customers_basket` where `customers_id`='".$_SESSION['customer_id']."'");
			tep_db_query("INSERT IGNORE `customers_basket` SELECT * FROM `customers_away_basket` where `customers_id`='".$_SESSION['customer_id']."'");
			tep_db_query("DELETE FROM `customers_away_basket` where `customers_id`='".$_SESSION['customer_id']."'");
		}
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT,$extras, 'SSL'));
        exit();
        return false;
		}
	}

####################################
# Payment URL
####################################
function GetApprovalURL()
{
	global  $FSESSION, $order_total_modules, $order_totals, $order;
	$FSESSION ->remove('patch_url');
	$FSESSION ->remove('paypalplus_de_token');

	$this -> GetAccessToken();
	//$order_totals_pp=$order_total_modules->process();
	//////////////Fix to remove duplicates//////////////
	// $ids = array_column($order_totals_pp, 'code');
	// $ids = array_unique($ids);
	// $order_totals_pp = array_filter($order_totals_pp, function ($key, $value) use ($ids) {
		// return in_array($value, array_keys($ids));
	// }, ARRAY_FILTER_USE_BOTH);
	///////////////////////////
	//$order_totals_pp = array_map("unserialize", array_unique(array_map("serialize", $order_totals_pp))); 



	//build the item list and totals
     $price_diff = 0;
     $item_list = '"items": [';
	 $subtotal = $tax = $shipping = 0;
	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) 
	{
    $item_price_array = $this->format_raw($order->products[$i]['final_price']) ;
    $item_price = $item_price_array[0];
    $price_diff += $item_price_array[1];
	$subtotal = $subtotal + ($item_price * $order->products[$i]['qty']);
		$item_list .= '
		{
		"quantity": "'.$order->products[$i]['qty'].'",
		"name": "'.$order->products[$i]['name'].'",
		"price": "'.$item_price.'",
		"currency": "'.$order->info['currency'].'"
		},';
	}
	
    if ($price_diff > 0 && MODULE_PAYMENT_PAYPAL_API_ROUNDING == "True")
	{
            $price_diff_array = $this->format_raw($price_diff) ;
            $price_diff = ($price_diff_array[0] * -1);
        $subtotal = $subtotal + ($price_diff);
        $item_list .= '
		{
		"quantity": "1",
		"name": "'.MODULE_PAYMENT_PAYPAL_API_PRICE_DIFF.'",
		"price": "'.$price_diff.'",
		"currency": "'.$order->info['currency'].'"
		},';
    }

  
// new code 2020
// the original osCommerce foundation code has linked order and order_total classes
// via their process() methods and the two cannot easily be split withour wrecking
// gift vouchers etc
// net result is that the order_total_modules object ends up with multiple instances of
// each method - the following will strip these out 

// (1) pass the stdclass object to an array then build 
	    $working_array = array ($order_total_modules);
		$working_array = json_decode(json_encode($order_total_modules), true);
		
        //while (list(, $value) = each($working_array['modules'])) { 
		foreach($working_array['modules'] as $key => $value)
		{
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
           // $GLOBALS[$class]->process();
            for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) 
			{
              if (tep_not_null($GLOBALS[$class]->output[$i]['title']) && tep_not_null($GLOBALS[$class]->output[$i]['text'])) 
			  {
			
                $order_total_array[] = array('code' => $GLOBALS[$class]->code,
											 'class' => $GLOBALS[$class]->credit_class,
                                             'title' => $GLOBALS[$class]->output[$i]['title'],
                                             'text' => $GLOBALS[$class]->output[$i]['text'],
                                             'value' => $GLOBALS[$class]->output[$i]['value'],
                                             'net_value' => $GLOBALS[$class]->output[$i]['net_value'],
                                             'sort_order' => $GLOBALS[$class]->sort_order);
              }
            }
          }
        }
	  $order_totals_pp = $order_total_array;
	  
	
	  
	  
//(2) strip duplicates keeping the first 'code' field https://stackoverflow.com/questions/42577258/php-remove-duplicates-from-multidimensional-array-by-value
	 $order_totals_pp = array_reverse(array_values(array_column(
			array_reverse($order_total_array),
			null,
			'code'
		)));
	
	  
// (3) original PayPal code recommences

	for ($i=0, $n=sizeof($order_totals_pp); $i<$n; $i++) 
	{
	      //ignore total and subtotal
		  if( $order_totals_pp[$i]['code'] != 'ot_total' &&
		      $order_totals_pp[$i]['code'] != 'ot_subtotal' &&
			  $order_totals_pp[$i]['code'] != 'ot_tax'  &&
			  $order_totals_pp[$i]['code'] != 'ot_shipping' &&
			  $order_totals_pp[$i]['code'] != '')
			  {
				  
		
		//ot_coupon needs to be negative else PayPal will ADD it to the order total
			 if($order_totals_pp[$i]['code'] == 'ot_coupon' || $order_totals_pp[$i]['code'] == 'ot_qty_discount' ||$order_totals_pp[$i]['code'] == 'ot_gv')
			 {
				if (PHP_VERSION >= 5.3)
				{
				//$amount = (number_format(round($order_totals_pp[$i]['value'],2,PHP_ROUND_HALF_EVEN),2) * -1);
				$amount = (number_format(round($order_totals_pp[$i]['value'],2,PHP_ROUND_HALF_EVEN),2) );
				}else{
				//$amount = (number_format( floor($order_totals_pp[$i]['value'] * 100) / 100,2) * -1);
				$amount = (number_format( floor($order_totals_pp[$i]['value'] * 100) / 100,2));
				}
				$subtotal = $subtotal + $amount;
				$item_list .= '
				{
				"quantity": "1",
				"name": "'.$order_totals_pp[$i]['title'].'",
				"price": "'.$amount.'",
				"currency": "'.$order->info['currency'].'"
				},';
///////////////////

////////////////////
			    }else
				{
				//2021 paypal needs net values these are added in the ot module
                //currently only ot_service_ff and ot_loworderfee
                 $amount_to_use = $order_totals_pp[$i]['value'];
                 
                 if(isset($order_totals_pp[$i]['net_value']))
				 {
                     $amount_to_use = $order_totals_pp[$i]['net_value'];
                 }
                
					if (PHP_VERSION >= 5.3)
					{
			          $amount = (number_format(round($amount_to_use,2,PHP_ROUND_HALF_EVEN),2));
					   }else
					   {
					   $amount = (number_format( floor($amount_to_use * 100) / 100,2) );
					   }
					   $subtotal = $subtotal + $amount;
				//missing section
			 
			    $item_list .= '
					{
						"quantity": "1",
						"name": "'.$order_totals_pp[$i]['title'].'",
						"price": "'.$amount.'",
						"currency": "'.$order->info['currency'].'"
					},';
				}
  
			}// end of != section
		//add in tax and shipping here
			if( $order_totals_pp[$i]['code'] == 'ot_tax'  )
			  {
				  if (PHP_VERSION >= 5.3)
					{
			          $tax = (number_format(round($order_totals_pp[$i]['value'],2,PHP_ROUND_HALF_EVEN),2));
					   }else
					   {
					   $tax = (number_format( floor($order_totals_pp[$i]['value'] * 100) / 100,2) );
					   }
				  
				  
			  }

			if( $order_totals_pp[$i]['code'] == 'ot_shipping'  )
			  {
				  if (PHP_VERSION >= 5.3)
					{
			          $shipping = (number_format(round($order_totals_pp[$i]['value'],2,PHP_ROUND_HALF_EVEN),2));
					   }else
					   {
					   $shipping = (number_format( floor($order_totals_pp[$i]['value'] * 100) / 100,2) );
					   }
				  
				  
			  }		
		//end tax and shipping
	}// end size of order totals
	//exit ($item_list);
	//paypal requires different states codes from the orders table for certain countries
	//wee bit for America
	if($order->delivery['country']['iso_code_2'] == 'US')
	{
	$state_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '223' and zone_name = '" . $state . "' limit 1");
		if (tep_db_num_rows($state_query))
		{
		$state_values = tep_db_fetch_array($state_query);
		$order->delivery['state'] = $state_values['zone_code'];
		}   
	}
	//////////////////////////////////////////// PWA
	// the $state_query below uses a $state that (as far as I can see) has not been assigned within this function so you may need:
	$state=$order->delivery['state'];
	// however given that it seems to work without that then maybe the whole bit marked


	////////////////////////////////////////// More PWA
		// If Pay Pal wants a state and a zip and a city then you need to test each and make some sort of dummy entry
	foreach ($order->delivery as $key=>$value) 
	{

		if($key == 'city' && !tep_not_null($value))
		{
			$order->delivery['city'] = 'city';
		}
	
		if($key == 'postcode' && !tep_not_null($value))
		{
		$order->delivery['postcode'] = 'zipcode';
		}
		
		if($key == 'street_address' && !tep_not_null($value))
		{
		$order->delivery['street_address'] = 'street address';
		}
	
	}

	//ends
	//remove trailing comma
	//problems with PayPal totals mean that we have to make sure that they all add up 
	//
	$total = $order->info['total']; 
	$dif = ($total -($tax+$shipping+$subtotal));

	//echo " INE 715 total: $total   tax : $tax   shipping:$shipping sub:$subtotal";
	// echo "dif :$dif";
	if ($dif != 0){$total = $total - $dif;}


    $item_list = substr($item_list, 0, -1);
	//$item_list .= '],';

	$url='https://'.$this->ppurl.'/v1/payments/payment';
	$JSONrequest='{
		"intent": "sale",
		"payer": { "payment_method": "paypal" },
		
		"transactions": [ {
				"amount": {
					"currency": "'.$order->info['currency'].'",
					"total": "'.$total.'",
					"details": {
						"subtotal": "'.$subtotal.'",
						"tax": "'.$tax.'",
						"shipping": "'.$shipping.'"
					}
				},
				"invoice_number":"xxxx",
				"item_list": {
						'.$item_list.'
					],
					"shipping_address": {
						"recipient_name": "'.$order->delivery['firstname'].' '.$order->delivery['lastname'].'",
						"line1": "'.$order->delivery['street_address'].'",
						"line2": "'.$order->delivery['suburb'].'",
						"city": "'.$order->delivery['city'].'",
						"state": "'.$order->delivery['state'].'",
						"postal_code": "'.$order->delivery['postcode'].'",
						"country_code": "'.$order->delivery['country']['iso_code_2'].'"
					}

			}
		}],
		
	"redirect_urls": {
		"return_url": "'.$this->store_url.'paypalplus_return.php",
		"cancel_url":  "'.$this->store_url.'paypalplus_cancel.php"
	},
	"experience_profile_id":"'.$_SESSION['webprofilID'].'"
	}';
 
	//exit ($JSONrequest);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$_SESSION['access_token']
		));
		
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $JSONrequest);

	$result = curl_exec($ch); 
	$resultGetApprovalURL = json_decode($result,true);
	curl_close ($ch);
	##################################
    //echo $resultGetApprovalURL['links'][1]['href']. '<br>'.$this->data_url.'<hr> result :'; print_r($resultGetApprovalURL);
	
	
	##################################
	$extras = $resultGetApprovalURL['name']; 
	##################################
	# $extras will hold a PayPal error
	##################################
		
	if (tep_not_null($extras))
	{

	if($extras == "VALIDATION_ERROR")
	{
		$extras = MODULE_PAYMENT_PAYPAL_API_GET_ERROR.MODULE_PAYMENT_PAYPAL_API_VAL_ERROR;
	}

	echo "<script>
		window.location = '".tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $extras, 'SSL')."';
		</script>'";
	exit('error');

	}
        if ($resultGetApprovalURL['state'] != 'created')
		{
			$extras = MODULE_PAYMENT_PAYPAL_API_GET_ERROR;
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT,$extras, 'SSL'));
			exit('error');
		}else
		{
		$sandbox='"mode": "live",';
		if (MODULE_PAYMENT_PAYPALPLUS_DE_TEST_MODE=='True') {
			$sandbox='"mode": "sandbox",';
		}
		   $FSESSION ->set('patch_url', $resultGetApprovalURL['links'][0]['href']);
echo '	<script src="https://www.paypalobjects.com/webstatic/ppplus/ppplus.min.js" type="text/javascript">
</script>';
	echo '
	<style>
		td{vertical-align:top}
	</style>
	<script type="application/javascript">
	   // add an id to the form {
		var x = document.getElementsByName("checkout_confirmation");
		var i;
		for (i = 0; i < x.length; i++) {
			x[i].setAttribute("id", "pp_checkout_form");
		}
		var ppp = PAYPAL.apps.PPP({
		"approvalUrl": "'.$resultGetApprovalURL['links'][1]['href'].'",
		"placeholder": "ppplus",
		'.$sandbox.'
		"country": "DE",
		"onContinue":function(){document.getElementById(\'pp_checkout_form\').submit();},
		"buttonLocation": "outside"
		});
	</script>';

		}

	
}

	####################################number_format(round($price,2,PHP_ROUND_HALF_EVEN),2)

// format prices without currency formatting
    function format_raw($number, $currency_code = '', $currency_value = '') 
    {
      global $currencies, $currency, $FSESSION, $order;
          $currency_code = $order->info['currency'];
          $currency_value = $order->info['currency_value'];


		  $rounded = number_format(tep_round($number * $currency_value, $currencies->currencies[$currency_code]['decimal_places']), $currencies->currencies[$currency_code]['decimal_places'], '.', '');
          
            $original = $number * $currency_value;
            $diff = tep_round(($rounded - $original),4);
            
            $result = (array($rounded,$diff));
            
            return $result;
		}
}
  
?>
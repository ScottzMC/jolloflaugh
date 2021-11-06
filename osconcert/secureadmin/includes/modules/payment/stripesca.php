<?php
/*
file includes/modules/payment/stripesca.php 

Some code copyright (c) 2003-2014 osCommerce Released under the GNU General Public License 
Some code copyright 2013 osConcert

*/
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

class stripesca
{
    var $code, $title, $description, $enabled;
    // class constructor
    function __construct() {
        global $order;

        $this->code = 'stripesca';
		$name = "Stripe secure payments [SCA]";
		$image = "";
		$path = "";
		if(MODULE_PAYMENT_STRIPESCA_DISPLAY_NAME != "MODULE_PAYMENT_STRIPESCA_DISPLAY_NAME")$name = MODULE_PAYMENT_STRIPESCA_DISPLAY_NAME;
		if(MODULE_PAYMENT_STRIPESCA_IMAGE != "MODULE_PAYMENT_STRIPESCA_IMAGE")$image = MODULE_PAYMENT_STRIPESCA_IMAGE;
		if(DIR_WS_ADMIN != "DIR_WS_ADMIN" && DIR_WS_ADMIN != "")$path = "../";
		if($image != "" && file_exists($path . DIR_WS_IMAGES . $image)){
			$image = '<img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $image . '" height="50">';
		}else{
			$image_array = array('.gif','.jpg','.jpeg','.png');
			$image_check = true;
			for($i=0;$i<sizeof($image_array);$i++){
				if($image_check && $image != "" && file_exists($path . DIR_WS_IMAGES . $image . $image_array[$i])){
					$image = '<img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $image . $image_array[$i] . '" width="103" height="33">';
					$image_check = false;
				}
			}
			if($image_check)$image = $path;
		}
		
        $this->api_version     = 'Stripe Payments';
		define('MODULE_PAYMENT_STRIPESCA_TEXT_TITLE', $name . '&nbsp;&nbsp;' . $image);
		define('MODULE_PAYMENT_STRIPESCA_TEXT_TEXT_TITLE', $name);
        $this->title           = MODULE_PAYMENT_STRIPESCA_TEXT_TITLE;
		$this->text_title      = MODULE_PAYMENT_STRIPESCA_TEXT_TEXT_TITLE;
        $this->description     = MODULE_PAYMENT_STRIPESCA_TEXT_DESCRIPTION;
        $this->sort_order      = MODULE_PAYMENT_STRIPESCA_SORT_ORDER;
        $this->enabled         = ((MODULE_PAYMENT_STRIPESCA_STATUS == 'True') ? true : false);
        $this->form_action_url = '';
		$this->publishable_key = ((MODULE_PAYMENT_STRIPESCA_TESTMODE == 'Test') ? MODULE_PAYMENT_STRIPESCA_TESTING_PUBLISHABLE_KEY : MODULE_PAYMENT_STRIPESCA_LIVE_PUBLISHABLE_KEY);
		$this->secret = ((MODULE_PAYMENT_STRIPESCA_TESTMODE == 'Test') ? MODULE_PAYMENT_STRIPESCA_TESTING_SECRET_KEY : MODULE_PAYMENT_STRIPESCA_LIVE_SECRET_KEY); 
        if ((int) MODULE_PAYMENT_STRIPESCA_ORDER_STATUS_ID > 0) {
            $this->order_status = MODULE_PAYMENT_STRIPESCA_ORDER_STATUS_ID;
        } 
        if (is_object($order))
            $this->update_status();
        
    }
    // class methods
    function update_status() {
      global $order;

  	  tep_check_module_status($this,MODULE_PAYMENT_STRIPESCA_ZONE,trim(MODULE_PAYMENT_STRIPESCA_EXCEPT_ZONE),trim(MODULE_PAYMENT_STRIPESCA_EXCEPT_COUNTRY));	
  	  $this->barred=tep_check_payment_barred(trim(MODULE_PAYMENT_STRIPESCA_EXCEPT_COUNTRY));
    }
    function javascript_validation()
    {
        return false;
    }
	
    function selection() {
		
		tep_db_query("CREATE TABLE IF NOT EXISTS `email_data` (
	  `email_id` int(11) NOT NULL auto_increment,
	  `customer_id` int(11) NOT NULL,
	  `order_id` int(11) NOT NULL,
	  `send_data` longtext NOT NULL,
	  `merge_data` longtext NOT NULL,
	  PRIMARY KEY  (`email_id`)
	)  AUTO_INCREMENT=1 ;");	
	
	tep_db_query("CREATE TABLE IF NOT EXISTS `customers_away_basket` LIKE `customers_basket`");
	tep_db_query("DELETE FROM `customers_away_basket` where `customers_id`='".(int)$FSESSION->customer_id."'");
	
      return array('id' => $this->code,
	  				'barred'=>$this->barred,
                   'module' => $this->title);
    }

    function pre_confirmation_check()
	
    {
        return false;        
    }
    function confirmation()
    {   global $order, $customer_id, $currency, $FSESSION;
		//remove any residual sessions
		$FSESSION->remove('stripe_id');
		$FSESSION->remove('stripe_cart');
		$FSESSION->remove('stripe_order_id');
		
		//first work out the store url
		if(ENABLE_SSL_CATALOG=='true' || ENABLE_SSL_CATALOG==1)
		  {$store_url=HTTPS_CATALOG_SERVER.DIR_WS_CATALOG;}
		  else
		  {$store_url=HTTP_SERVER.DIR_WS_CATALOG;}
	  
	    //testing on localhost without SSL you need this
		if (strpos($store_url, '127.0.0.1') !== false || HTTP_SERVER == '')
			{
				 $store_url = 'https://127.0.0.1/osconcert';
		   }
	
        //Stripe get the test/production state		
		//grab a session value from Stripe
		require_once('./ext/modules/payment/stripesca/init.php');
		 
		
	
			\Stripe\Stripe::setApiKey($this->secret);

			$session = \Stripe\Checkout\Session::create([
			  'billing_address_collection' => 'auto',
			  'customer_email' => $order->customer['email_address'],
			  'payment_method_types' => ['card'],
			  'line_items' => [[
				'name' => MODULE_PAYMENT_STRIPESCA_DESC_TITLE,
				'description' => MODULE_PAYMENT_STRIPESCA_DESC,
				'images' => [ tep_href_link( DIR_WS_TEMPLATES . DEFAULT_TEMPLATE . '/' . DIR_WS_IMAGES . COMPANY_LOGO )],
				'amount' => $this->format_raw($order->info['total']) * $this->bt_currency_multiplyer($order->info['currency']),
				'currency' => $order->info['currency'],
				'quantity' => 1,
			  ]],
			  'success_url' => $store_url. "stripesca_success.php"."?c_id=".$_SESSION['customer_id'],
			  'cancel_url' =>  $store_url. "stripesca_failure.php"."?c_id=".$_SESSION['customer_id'],
			]);
			
  

		    $FSESSION->set('stripe_id', $session->id);
		
		  if (MODULE_PAYMENT_STRIPESCA_TESTMODE == 'Test') {
		    $confirmation['title'] .= '<h3>' . MODULE_PAYMENT_STRIPESCA_TEXT_TITLE . '</h3>';
            $confirmation['title'] .= '<div class="messageStackError" style="margin:10px">Stripe Payments Test Mode
								 <br>Use test card number 4000000000003220 for 3D Secure
                                 <br>Use test card number 4242424242424242 or see https://stripe.com/docs/testing
                                 <br>Use any expiry date in the future';
								
            $confirmation['title'] .= '<br>Use any CVV number<br>';
            $confirmation['title'] .= '</div>';

          } //MODULE_PAYMENT_STRIPESCA_TESTMODE == 'Test'


        return $confirmation;
    }
    function process_button()
    {
        return false;
    }
    function before_process()
    {
        //double check for stripe session value, if empty or missing then return to payment selection
		
		if (!isset($_SESSION['stripe_id']) || empty($_SESSION['stripe_id'])){
			$error = MODULE_PAYMENT_STRIPESCA_GENERIC_ERROR;
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $error, 'SSL'));
		}
		
        return false;
    }
	
	function before_email(){
		
	global $insert_id,  $FSESSION, $merge_details, $send_details;
	
	require_once('./ext/modules/payment/stripesca/init.php');
	\Stripe\Stripe::setApiKey($this->secret);
	$result = \Stripe\Checkout\Session::retrieve($FSESSION->stripe_id ); 
	
    \Stripe\PaymentIntent::update($result["payment_intent"],
		[
			'metadata' => [substr(MODULE_PAYMENT_STRIPESCA_METADATA,0,30) => $insert_id],
			
	  ]
		);


	 
	    $FSESSION->set('stripe_order_id',$insert_id);
		$FSESSION->set('stripe_cart',$_SESSION['cart']);
		$FSESSION->set('cart','');
		//save the stripe session value
		tep_db_query("UPDATE `orders` set `payment_return1` = '".$FSESSION->stripe_id."', `payment_return2` = '".$result["payment_intent"]."'where `orders_id`='".(int)$insert_id ."'");

		tep_db_query("DELETE  from `customers_away_basket` where `customers_id`='".(int)$FSESSION->customer_id ."'");

		tep_db_query("INSERT IGNORE `customers_away_basket` SELECT * FROM `customers_basket` where `customers_id`='".(int)$FSESSION->customer_id ."'");
				
		tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$FSESSION->customer_id . "'");
		//tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$FSESSION->customer_id . "'");
	###############################save emails #############################################

				$sql_data_array = array(
								'order_id' => $insert_id,
								'customer_id' => $_SESSION['customer_id'],
								'send_data' => base64_encode( serialize($send_details)),
								'merge_data' => base64_encode( serialize($merge_details))
									);
		         tep_db_perform('email_data', $sql_data_array);
	
	###### redirect to new page so that we can use the stripe.js to further redirect ########
				
	header("Location: stripesca_redirect.php");
	
    ##### previously had the redirect code here but this would fail to handle connection errors #####
	
	  
	   exit(MODULE_PAYMENT_STRIPESCA_WAIT);
	   return false;
	}
	
    function after_process()
    {
       exit('Should not be here');
	   return false;
    }
    function get_error()
    {
        global $_GET;
        $error = array(
            'title' => MODULE_PAYMENT_STRIPESCA_ERROR_TITLE,
            'error' => stripslashes($_GET['error'])
        );
        return $error;
    }
    function check()
    {
        if (!isset($this->_check)) {
            $check_query  = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_STRIPESCA_STATUS'");
            $this->_check = tep_db_num_rows($check_query);
        } //!isset($this->_check)
        return $this->_check;
    }
    function install()
    {
        
        // OK lets add in a new order statuses  Stripe - failures
        $check_query = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'CVV error, payment taken' limit 1");
        if (tep_db_num_rows($check_query) < 1) {
            $status_query = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
            $status       = tep_db_fetch_array($status_query);
            $status_id    = $status['status_id'] + 1;
            $languages    = tep_get_languages();
            foreach ($languages as $lang) {
                tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id . "', '" . $lang['id'] . "', 'CVV error, payment taken')");
            } //$languages as $lang
        } //tep_db_num_rows($check_query) < 1
        else {
            $check     = tep_db_fetch_array($check_query);
            $status_id = $check['orders_status_id'];
        }
        $check_query2 = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'AVS unsuccessful, payment taken' limit 1");
        if (tep_db_num_rows($check_query2) < 1) {
            $status_query2 = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
            $status2       = tep_db_fetch_array($status_query2);
            $status_id2    = $status2['status_id'] + 1;
            $languages     = tep_get_languages();
            foreach ($languages as $lang) {
                tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id2 . "', '" . $lang['id'] . "', 'AVS unsuccessful, payment taken')");
            } //$languages as $lang
        } //tep_db_num_rows($check_query2) < 1
        else {
            $check2     = tep_db_fetch_array($check_query2);
            $status_id2 = $check2['orders_status_id'];
        }
        //Now for 'unchecked
        
        // OK lets add in a new order statuses  Stripe - failures
        $check_query3 = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'CVV - not checked, payment taken' limit 1");
        if (tep_db_num_rows($check_query3) < 1) {
            $status_query3 = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
            $status3       = tep_db_fetch_array($status_query3);
            $status_id3    = $status3['status_id'] + 1;
            $languages     = tep_get_languages();
            foreach ($languages as $lang) {
                tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id3 . "', '" . $lang['id'] . "', 'CVV - not checked, payment taken')");
            } //$languages as $lang
        } //tep_db_num_rows($check_query) < 1
        else {
            $check3     = tep_db_fetch_array($check_query3);
            $status_id3 = $check3['orders_status_id'];
        }
        $check_query4 = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'AVS not checked, payment taken' limit 1");
        if (tep_db_num_rows($check_query4) < 1) {
            $status_query4 = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
            $status4       = tep_db_fetch_array($status_query4);
            $status_id4    = $status4['status_id'] + 1;
            $languages     = tep_get_languages();
            foreach ($languages as $lang) {
                tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id4 . "', '" . $lang['id'] . "', 'AVS not checked, payment taken')");
            } //$languages as $lang
        } //tep_db_num_rows($check_query2) < 1
        else {
            $check4     = tep_db_fetch_array($check_query4);
            $status_id4 = $check4['orders_status_id'];
        }
		
		
		###
		$check_query5 = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'Stripe:cancelled' limit 1");
        if (tep_db_num_rows($check_query5) < 1) {
            $status_query5 = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
            $status5     = tep_db_fetch_array($status_query5);
            $status_id5    = $status5['status_id'] + 1;
            $languages     = tep_get_languages();
            foreach ($languages as $lang) {
                tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id5 . "', '" . $lang['id'] . "', 'Stripe:cancelled')");
            } //$languages as $lang
        } //tep_db_num_rows($check_query2) < 1
        else {
            $check5     = tep_db_fetch_array($check_query5);
            $status_id5 = $check5['orders_status_id'];
        }
        //duplicate the remove function to get any extra keys out of database e.g. if install fails part way through
		        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
		//config options
        
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Stripe Payments', 'MODULE_PAYMENT_STRIPESCA_STATUS', 'True', 'Do you want to accept Stripe payments?', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_STRIPESCA_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '20', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
		
	  
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Exclude these Countries', 'MODULE_PAYMENT_STRIPESCA_EXCEPT_COUNTRY', '', 'If countries are selected, disable this payment method for that countries.', '6', '21', 'tep_cfg_pull_down_zone_except_countries(MODULE_PAYMENT_STRIPESCA_ZONE,', 'tep_get_zone_except_country', now())");
	  
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Exclude these Zones', 'MODULE_PAYMENT_STRIPESCA_EXCEPT_ZONE', '', 'If a zone is selected, disable this payment method for that zone.', '6', '22', 'tep_cfg_pull_down_zone_classes(','tep_get_zone_class_title', now())");
		
		
		
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_STRIPESCA_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '30', now())");
		
		//pending orders 
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Initial Order Status', 'MODULE_PAYMENT_STRIPESCA_ORDER_STATUS_ID', '1', 'Set the initial status of orders made with this payment module to this value - do not use Delivered', '6', '40', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
		
		//failed or cancelled orders
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Failed Order Status', 'MODULE_PAYMENT_STRIPESCA_CANX_ORDER_STATUS_ID', '".$status_id5."', 'Set the status of failed/cancelled orders made with this payment module to this value', '1', '40', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");

		//pending orders
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Completed Order Status', 'MODULE_PAYMENT_STRIPESCA_COMP_ORDER_STATUS_ID', '3', 'Set the status of succcesful orders made with this payment module to this value', '1', '40', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");

        // test or production?
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_STRIPESCA_TESTMODE', 'Test', 'Transaction mode used for processing orders', '6', '50', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ', now())");
		

        //API keys
        //Testing Secret Key
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Testing Secret Key', 'MODULE_PAYMENT_STRIPESCA_TESTING_SECRET_KEY', '', 'Testing Secret Key - obtainable in your Stripe dashboard.', '6', '61', now())");
        //Testing Publishable Key
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Testing Publishable Key', 'MODULE_PAYMENT_STRIPESCA_TESTING_PUBLISHABLE_KEY', '', 'Testing Publishable Key  - obtainable in your Stripe dashboard.', '6', '60', now())");
        //Live Secret key
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Live Secret key', 'MODULE_PAYMENT_STRIPESCA_LIVE_SECRET_KEY', '', 'Live Secret key  - obtainable in your Stripe dashboard.', '6', '63', now())");
        //Live Publishable key    
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Live Publishable key', 'MODULE_PAYMENT_STRIPESCA_LIVE_PUBLISHABLE_KEY', '', 'Live Publishable key  - obtainable in your Stripe dashboard.', '6', '62', now())");
		
		//webhook key
	    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Webhook key', 'MODULE_PAYMENT_STRIPESCA_WEBHOOK_KEY', '', 'Webhook key  - obtainable in your Stripe dashboard.', '6', '64', now())");	
 
  

	   tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Image', 'MODULE_PAYMENT_STRIPESCA_IMAGE', 'stripesca.png', 'Set the Image of payment module', '6', '70', 'tep_cfg_file_field(', now())");
	   
	   tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Display Name', 'MODULE_PAYMENT_STRIPESCA_DISPLAY_NAME', 'Stripe Secure Payments', 'Set the Display name to payment module', '6', '80', now())");
						
						

    }
    function remove()
    {
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
        
    }
    function keys()
    {
        return array(
            'MODULE_PAYMENT_STRIPESCA_STATUS',
            'MODULE_PAYMENT_STRIPESCA_ZONE',
            'MODULE_PAYMENT_STRIPESCA_ORDER_STATUS_ID',
            'MODULE_PAYMENT_STRIPESCA_SORT_ORDER',
            'MODULE_PAYMENT_STRIPESCA_TESTMODE',
			'MODULE_PAYMENT_STRIPESCA_TESTING_SECRET_KEY',
            'MODULE_PAYMENT_STRIPESCA_TESTING_PUBLISHABLE_KEY',
            'MODULE_PAYMENT_STRIPESCA_LIVE_SECRET_KEY',
            'MODULE_PAYMENT_STRIPESCA_LIVE_PUBLISHABLE_KEY',
			'MODULE_PAYMENT_STRIPESCA_IMAGE',
			'MODULE_PAYMENT_STRIPESCA_DISPLAY_NAME',
			'MODULE_PAYMENT_STRIPESCA_EXCEPT_COUNTRY',
			'MODULE_PAYMENT_STRIPESCA_EXCEPT_ZONE',
			'MODULE_PAYMENT_STRIPESCA_CANX_ORDER_STATUS_ID',
			'MODULE_PAYMENT_STRIPESCA_COMP_ORDER_STATUS_ID',
			'MODULE_PAYMENT_STRIPESCA_WEBHOOK_KEY',
		
            
        );
    }
    // format prices without currency formatting

	
	    function format_raw($number, $currency_code = '', $currency_value = '') {
      global $currencies,$FSESSION;
	  $currency=$FSESSION->currency;

      if (empty($currency_code) || !$this->is_set($currency_code)) {
        $currency_code = $currency;
      }

      if (empty($currency_value) || !is_numeric($currency_value)) {
        $currency_value = $currencies->currencies[$currency_code]['value'];
      }

      return number_format(tep_round($number * $currency_value, $currencies->currencies[$currency_code]['decimal_places']), $currencies->currencies[$currency_code]['decimal_places'], '.', '');
    }
    
    //function to get the currency multiplyer to reduce currency to base units - the default is 100 (e.g. USD, GBP,EUR) but we have a list here of other currencies to be checked 
    function bt_currency_multiplyer($order_currency)
    {
        //array currency code => mutiplyer
        $exceptions = array(
            'BIF' => 1,
            'BYR' => 1,
            'CLF' => 1,
            'CLP' => 1,
            'CVE' => 1,
            'DJF' => 1,
            'GNF' => 1,
            'IDR' => 1,
            'IQD' => 1,
            'IRR' => 1,
            'ISK' => 1,
            'JPY' => 1,
            'KMF' => 1,
            'KPW' => 1,
            'KRW' => 1,
            'LAK' => 1,
            'LBP' => 1,
            'MMK' => 1,
            'PYG' => 1,
            'RWF' => 1,
            'SLL' => 1,
            'STD' => 1,
            'UYI' => 1,
            'VND' => 1,
            'VUV' => 1,
            'XAF' => 1,
            'XOF' => 1,
            'XPF' => 1,
            'MOP' => 10,
            'BHD' => 1000,
            'JOD' => 1000,
            'KWD' => 1000,
            'LYD' => 1000,
            'OMR' => 1000,
            'TND' => 1000
        );
        $multiplyer = 100; //default value
        foreach ($exceptions as $key => $value) {
            if (($order_currency == $key)) {
                $multiplyer = $value;
                break;
            }
            
            
        }
        return $multiplyer;
        
    }
    
	function repopulate_cart($order_id,$redirect_page=FILENAME_CHECKOUT_PAYMENT,$extras='', $note='')
{
    global $FSESSION;

    // if we have the order_id then do stuff
    if (tep_not_null($order_id)) {
        //grab customers name from order
        $cust_query = tep_db_query("select customers_name from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
        if (tep_db_num_rows($cust_query) ) {
            $cust_query_result = tep_db_fetch_array($cust_query);
            $cust_name = $cust_query_result['customers_name'];
        }
        //change the order status
        $sql_data_array = array('orders_status' => MODULE_PAYMENT_STRIPESCA_CANX_ORDER_STATUS_ID,
               'customers_name'=> MODULE_PAYMENT_STRIPESCA_ERROR_CANX_LABEL.$cust_name)
        ;
        tep_db_perform(TABLE_ORDERS, $sql_data_array, "update", "orders_id='".$order_id."'");
        
        $sql_data_array = array('orders_id' => $order_id,
        'orders_status_id' => MODULE_PAYMENT_STRIPESCA_CANX_ORDER_STATUS_ID,
        'date_added' => date('Y-m-d H:i:s',getServerDate(false)),
        'customer_notified' => 0,
        'comments' => $note );
        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
      
		include_once('includes/functions/ga_tickets.php');

        $order_query = tep_db_query("select products_id, products_quantity, events_type from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $order_id. "'");
        while ($order = tep_db_fetch_array($order_query)) {
            tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order['products_quantity'] . ", products_ordered = products_ordered - " . $order['products_quantity'] . ", products_status='1' where products_id = '" . (int)$order['products_id'] . "'");

		if (function_exists('ga_check_process_restock')){
		  
			ga_check_process_restock((int)$order['products_id'], $order['products_quantity'], $order['events_type']);	
			}													

        }
		tep_db_query("delete from email_data where order_id = '" . $order_id. "'");
        tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $order_id. "'");
        tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set products_quantity = '0' where orders_id = '" . $order_id . "'");
        tep_db_query("insert into " . TABLE_ORDERS_TOTAL. " (orders_id, title, text, value, class, sort_order) values ('" . $order_id . "', 'Total', '0.00', '0','ot_total', '99')");
		
		if ($FSESSION->is_registered('stripe_cart')) {
				$FSESSION->set('cart',$_SESSION['stripe_cart']);
				$FSESSION->remove('stripe_cart');
		
			tep_db_query("DELETE FROM `customers_basket` where `customers_id`='".$_SESSION['customer_id']."'");
			tep_db_query("INSERT IGNORE `customers_basket` SELECT * FROM `customers_away_basket` where `customers_id`='".$_SESSION['customer_id']."'");
			tep_db_query("DELETE FROM `customers_away_basket` where `customers_id`='".$_SESSION['customer_id']."'");			

		}
		
		$FSESSION->remove('stripe_id');
		$FSESSION->remove('stripe_cart');
		$FSESSION->remove('stripe_order_id');
		
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT,$extras, 'SSL'));
        exit();
      
    }
}

}//end class

?>
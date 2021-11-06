<?php
/*
  $Id: paypal_payflow.php 1827 2008-01-22 15:45:32Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2008 osCommerce

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/

  class paypal_payflow {
    var $code, $title, $description, $enabled;

// class constructor
    function __construct() {
      global $order;

      $this->signature = 'paypal|paypal_payflow|1.0|2.2';

      $this->code = 'paypal_payflow';
      $name = "PayPal PayFlow";
      $image = "";
      if(MODULE_PAYMENT_PAYPAL_PAYFLOW_DISPLAY_NAME != "MODULE_PAYMENT_PAYPAL_PAYFLOW_DISPLAY_NAME") $name = MODULE_PAYMENT_PAYPAL_PAYFLOW_DISPLAY_NAME;
      if(MODULE_PAYMENT_PAYPAL_PAYFLOW_IMAGE != "MODULE_PAYMENT_PAYPAL_PAYFLOW_IMAGE") $image = MODULE_PAYMENT_PAYPAL_PAYFLOW_IMAGE;
      if (DIR_WS_ADMIN != "DIR_WS_ADMIN" && DIR_WS_ADMIN != "") $path = "../";

		if($image != "" && file_exists($path . DIR_WS_IMAGES. $image)){
			$image = '<img src="' . HTTP_SERVER. DIR_WS_HTTP_CATALOG. DIR_WS_IMAGES. $image. '" width="103" height="33">';
		} else {
			$image_array = array ('.gif','.jpg','.jpeg','.png');
			$image_check = true;
			
			for($i=0;$i<sizeof($image_array);$i++){
				if($image_check && $image != "" && file_exists($path . DIR_WS_IMAGES. $image. $image_array[$i])){
					$image = '<img src="' . HTTP_SERVER. DIR_WS_HTTP_CATALOG. DIR_WS_IMAGES. $image. $image_array[$i] . '" width="103" height="33">';
					$image_check = false;
				}
			}
			if ($image_check) $image = "";
		}

		define ('MODULE_PAYMENT_PAYPAL_PAYFLOW_TEXT_TITLE', $name . '&nbsp;&nbsp;' . $image);
		define('MODULE_PAYMENT_PAYPAL_PAYFLOW_TEXT_TEXT_TITLE', $name);

		$this->text_title = MODULE_PAYMENT_PAYPAL_PAYFLOW_TEXT_TEXT_TITLE;
		$this->title = MODULE_PAYMENT_PAYPAL_PAYFLOW_TEXT_TITLE;
		$this->public_title = MODULE_PAYMENT_PAYPAL_PAYFLOW_TEXT_PUBLIC_TITLE;
		$this->description = MODULE_PAYMENT_PAYPAL_PAYFLOW_TEXT_DESCRIPTION;
		$this->sort_order = MODULE_PAYMENT_PAYPAL_PAYFLOW_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_PAYPAL_PAYFLOW_STATUS == 'True') ? true : false);

		if ((int)MODULE_PAYMENT_PAYPAL_PAYFLOW_ORDER_STATUS_ID > 0) {
			$this->order_status = MODULE_PAYMENT_PAYPAL_PAYFLOW_ORDER_STATUS_ID;
		}

		if (is_object($order)) $this->update_status();
		
/*		$this->cc_types = array('0' => 'Visa',
							    '1' => 'MasterCard',
							    '9' => 'Maestro',
							    'S' => 'Solo');*/
    }


    
    function update_status() {
		global $order;
		tep_check_module_status($this,MODULE_PAYMENT_PAYPAL_PAYFLOW_ZONE, trim(MODULE_PAYMENT_PAYPAL_PAYFLOW_EXCEPT_ZONE),trim(MODULE_PAYMENT_PAYPAL_PAYFLOW_EXCEPT_COUNTRY));
		$this->barred=tep_check_payment_barred(trim(MODULE_PAYMENT_PAYPAL_PAYFLOW_EXCEPT_COUNTRY));
	}


	function javascript_validation() {
		return false;
	}

    function selection() {
        global $order;

		$selection = array('id' => $this->code,
						 'module' => $this->title);
						 
/*        $types_array = array();
        while (list($key, $value) = each($this->cc_types)) {
          $types_array[] = array('id' => $key,
                                 'text' => $value);
        }*/

        $today = getdate();

        $months_array = array();
        for ($i=1; $i<13; $i++) {
          $months_array[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
        }

        $year_valid_from_array = array();
        for ($i=$today['year']-10; $i < $today['year']+1; $i++) {
          $year_valid_from_array[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
        }

        $year_expires_array = array();
        for ($i=$today['year']; $i < $today['year']+10; $i++) {
          $year_expires_array[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
        }

        $selection['fields'] = array(array('title' => MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_OWNER_FIRSTNAME,
                                           'field' => tep_draw_input_field('cc_owner_firstname', $order->billing['firstname'])),
                                     array('title' => MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_OWNER_LASTNAME,
                                           'field' => tep_draw_input_field('cc_owner_lastname', $order->billing['lastname'])),
                                     /*array('title' => MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_TYPE,
                                           'field' => tep_draw_pull_down_menu('cc_type', $types_array)),*/
                                     array('title' => MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_NUMBER,
                                           'field' => tep_draw_input_field('cc_number_nh-dns')),
                                     array('title' => MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_EXPIRES,
                                           'field' => tep_draw_pull_down_menu('cc_expires_month', $months_array) . '&nbsp;' . tep_draw_pull_down_menu('cc_expires_year', $year_expires_array)),
                                     array('title' => MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_CVC,
                                           'field' => tep_draw_input_field('cc_cvc_nh-dns', '', 'size="5" maxlength="4"')));
		return $selection;
    }

	function pre_confirmation_check() {
		global $FREQUEST;
		if ($FREQUEST->postvalue('cc_owner_firstname')=='' || $FREQUEST->postvalue('cc_owner_lastname')=='' || (strlen($FREQUEST->postvalue('cc_owner_firstname') . ' ' . $FREQUEST->postvalue('cc_owner_lastname')) < CC_OWNER_MIN_LENGTH) ||  (strlen($FREQUEST->postvalue('cc_number_nh-dns')) < CC_NUMBER_MIN_LENGTH)) {       
			$payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode(MODULE_PAYMENT_PAYPAL_PAYFLOW_ERROR_ALL_FIELDS_REQUIRED) . '&cc_owner_firstname=' . urlencode($FREQUEST->postvalue('cc_owner_firstname')) . '&cc_owner_lastname=' . urlencode($FREQUEST->postvalue('cc_owner_lastname')) . '&cc_expires_month=' . $FREQUEST->postvalue('cc_expires_month') . '&cc_expires_year=' . $FREQUEST->postvalue('cc_expires_year');
		
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
		}
		return false;
	}

    function confirmation() {
		global $FREQUEST;
      	$confirmation = array('title' => $this->title,
							  'fields' => array(array('title' => MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_OWNER,
											  'field' => $FREQUEST->postvalue('cc_owner_firstname') . ' ' . $FREQUEST->postvalue('cc_owner_lastname')),
						/*				array('title' => MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_TYPE,
											  'field' => $this->cc_types[$FREQUEST->postvalue('cc_type')]),*/
										array('title' => MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_NUMBER,
											  'field' => str_repeat('X', strlen($FREQUEST->postvalue('cc_number_nh-dns')) - 4) . substr($FREQUEST->postvalue('cc_number_nh-dns'), -4)),
										array('title' => MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_EXPIRES,
											  'field' => $FREQUEST->postvalue('cc_expires_month') . '/' . $FREQUEST->postvalue('cc_expires_year')),
										array('title' => MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_CVC,
											  'field' => $FREQUEST->postvalue('cc_cvc_nh-dns'))));

 

		return $confirmation;
    }

    function process_button() {
        global $FREQUEST;

		$process_button_string = tep_draw_hidden_field('cc_owner_firstname', $FREQUEST->postvalue('cc_owner_firstname')) .
								 tep_draw_hidden_field('cc_owner_lastname', $FREQUEST->postvalue('cc_owner_lastname')) .
								// tep_draw_hidden_field('cc_type', $FREQUEST->postvalue('cc_type')) .
								 tep_draw_hidden_field('cc_number_nh-dns', $FREQUEST->postvalue('cc_number_nh-dns')) .
								 tep_draw_hidden_field('cc_starts_year', $FREQUEST->postvalue('cc_starts_year')) .
								 tep_draw_hidden_field('cc_expires_month', $FREQUEST->postvalue('cc_expires_month')) .
								 tep_draw_hidden_field('cc_expires_year', $FREQUEST->postvalue('cc_expires_year')) .
								 tep_draw_hidden_field('cc_cvc_nh-dns', $FREQUEST->postvalue('cc_cvc_nh-dns'));

        return $process_button_string;
    }

	function before_process() {
		global $FREQUEST, $order, $sendto,$FSESSION;
		if (($FREQUEST->postvalue('cc_owner_firstname')!='') && ($FREQUEST->postvalue('cc_owner_lastname')!='') && ($FREQUEST->postvalue('cc_number_nh-dns')!='') ) {
			if (MODULE_PAYMENT_PAYPAL_PAYFLOW_TRANSACTION_SERVER == 'Live') {
			  $api_url = 'https://payflowpro.paypal.com//transaction';
			} else {
			  $api_url = 'https://pilot-payflowpro.paypal.com/transaction';
			}

			$name = explode(' ', $FREQUEST->postvalue('cc_owner'), 2);
	
			$params = array('USER' => (tep_not_null(MODULE_PAYMENT_PAYPAL_PAYFLOW_USERNAME) ? MODULE_PAYMENT_PAYPAL_PAYFLOW_USERNAME : MODULE_PAYMENT_PAYPAL_PAYFLOW_VENDOR),
							'VENDOR' => MODULE_PAYMENT_PAYPAL_PAYFLOW_VENDOR,
							'PARTNER' => MODULE_PAYMENT_PAYPAL_PAYFLOW_PARTNER,
							'PWD' => MODULE_PAYMENT_PAYPAL_PAYFLOW_PASSWORD,
							'TENDER' => 'C',
							'TRXTYPE' => ((MODULE_PAYMENT_PAYPAL_PAYFLOW_TRANSACTION_METHOD == 'Sale') ? 'S' : 'A'),
							'AMT' => $this->format_raw($order->info['total']),
							'CURRENCY' => $order->info['currency'],
							'FIRSTNAME' => $FREQUEST->postvalue('cc_owner_firstname'),
							'LASTNAME' => $FREQUEST->postvalue('cc_owner_lastname'),
							'STREET' => $order->billing['street_address'],
							'CITY' => $order->billing['city'],
							'STATE' => tep_get_zone_code($order->billing['country']['id'], $order->billing['zone_id'], $order->billing['state']),
							'COUNTRY' => $order->billing['country']['iso_code_2'],
							'ZIP' => $order->billing['postcode'],
							'CLIENTIP' => tep_get_ip_address(),
							'EMAIL' => $order->customer['email_address'],
							'ACCT' => $FREQUEST->postvalue('cc_number_nh-dns'),
							//'ACCTTYPE' => $FREQUEST->postvalue('cc_type'),
							//'CARDSTART' => $FREQUEST->postvalue('cc_starts_month') . $FREQUEST->postvalue('cc_starts_year'),
							'EXPDATE' => $FREQUEST->postvalue('cc_expires_month') . $FREQUEST->postvalue('cc_expires_year'),
							'CVV2' => $FREQUEST->postvalue('cc_cvc_nh-dns'),
							'BUTTONSOURCE' => 'osCommerce22_Default_PRO2DP');

/*			if ( ($FREQUEST->postvalue('cc_type') == '9') || ($FREQUEST->postvalue('cc_type') == 'S') ) {
			  $params['CARDISSUE'] = $FREQUEST->postvalue('cc_issue_nh-dns');
			}*/

			if (is_numeric($sendto) && ($sendto > 0)) {
				$params['SHIPTOFIRSTNAME'] = $order->delivery['firstname'];
				$params['SHIPTOLASTNAME'] = $order->delivery['lastname'];
				$params['SHIPTOSTREET'] = $order->delivery['street_address'];
				$params['SHIPTOCITY'] = $order->delivery['city'];
				$params['SHIPTOSTATE'] = tep_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], $order->delivery['state']);
				$params['SHIPTOCOUNTRY'] = $order->delivery['country']['iso_code_2'];
				$params['SHIPTOZIP'] = $order->delivery['postcode'];
			}

			$post_string = '';
			
			foreach ($params as $key => $value) {
				$post_string .= $key . '[' . strlen(trim($value)) . ']=' . trim($value) . '&';
			}

	        $post_string = substr($post_string, 0, -1);

			$response = $this->sendTransactionToGateway($api_url, $post_string, array('X-VPS-REQUEST-ID: ' . md5($cartID . $FSESSION->ID. rand())));
			
			$response_array = array();
			parse_str($response, $response_array);

			if ($response_array['RESULT'] != '0') {
				switch ($response_array['RESULT']) {
					case '1':
					case '26':
					  $error_message = MODULE_PAYMENT_PAYPAL_PAYFLOW_ERROR_CFG_ERROR;
					  break;
					
					case '7':
					  $error_message = MODULE_PAYMENT_PAYPAL_PAYFLOW_ERROR_ADDRESS;
					  break;
					
					case '12':
					  $error_message = MODULE_PAYMENT_PAYPAL_PAYFLOW_ERROR_DECLINED;
					  break;
					
					case '23':
					case '24':
					  $error_message = MODULE_PAYMENT_PAYPAL_PAYFLOW_ERROR_INVALID_CREDIT_CARD;
					  break;
					
					default:
					  $error_message = MODULE_PAYMENT_PAYPAL_PAYFLOW_ERROR_GENERAL;
					  break;
				}
				tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . urlencode($error_message), 'SSL'));
			}
		} else {
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . urlencode(MODULE_PAYMENT_PAYPAL_PAYFLOW_ERROR_ALL_FIELDS_REQUIRED), 'SSL'));
		}
		//write the response log
		//tep_db_query("INSERT INTO payflow_responses(entry_date,customer_id,payment_response) VALUES ('" . getServerDate(true) . "','" . $FSESSION->customer_id . "','" . tep_db_input($response) . "')");
    }
	function after_process() {
		return false;
	}

    function get_error() {
        global $FREQUEST;

		$error = array('error' => stripslashes(urldecode($FREQUEST->getvalue('error'))));
		
		return $error;
    }

    function check() {
		if (!isset($this->_check)) {
			$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_PAYFLOW_STATUS'");
			$this->_check = tep_db_num_rows($check_query);
		}
		return $this->_check;
    }

    function install() {
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PayPal PayFlow', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_STATUS', 'False', 'Do you want to accept PayPal PayFlow payments?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Vendor', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_VENDOR', '', 'Your merchant login ID that you created when you registered for the Website Payments Pro account.', '6', '2', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('User', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_USERNAME', '', 'If you set up one or more additional users on the account, this value is the ID of the user authorised to process transactions. If, however, you have not set up additional users on the account, USER has the same value as VENDOR.', '6', '3', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Password', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_PASSWORD', '', 'The 6- to 32-character password that you defined while registering for the account.', '6', '4', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Partner', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_PARTNER', 'PayPal', 'The ID provided to you by the authorised PayPal Reseller who registered you for the Payflow SDK. If you purchased your account directly from PayPal, use PayPal.', '6', '5', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Server', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_TRANSACTION_SERVER', 'Live', 'Use the live or testing (sandbox) gateway server to process transactions?', '6', '6', 'tep_cfg_select_option(array(\'Live\', \'Sandbox\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Method', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_TRANSACTION_METHOD', 'Sale', 'The processing method to use for each transaction.', '6', '7', 'tep_cfg_select_option(array(\'Authorization\', \'Sale\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '9', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_SORT_ORDER', '1', 'Sort order of display. Lowest is displayed first.', '6', '10', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value.', '6', '11', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('cURL Program Location', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_CURL', '/usr/bin/curl', 'The location to the cURL program application.', '6', '12' , now())");
		tep_db_query ("insert into " . TABLE_CONFIGURATION. " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Exclude these Countries', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_EXCEPT_COUNTRY', '', 'If countries are selected, disable this payment method for that countries.', '6', '13', 'tep_cfg_pull_down_zone_except_countries(MODULE_PAYMENT_PAYPAL_PAYFLOW_ZONE,', 'tep_get_zone_except_country', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION. " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Exclude these Zones', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_EXCEPT_ZONE', '', 'If a zone is selected, disable this payment method for that zone.', '6', '14', 'tep_cfg_pull_down_zone_classes (','tep_get_zone_class_title', now())");
		tep_db_query ("insert into " . TABLE_CONFIGURATION. " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Display Name', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_DISPLAY_NAME', 'PayPal PayFlow', 'Set the Display name to payment module', '6', '15', now())");
		tep_db_query ("insert into " . TABLE_CONFIGURATION. " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Image', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_IMAGE', 'payflow', 'Set the Image of payment module', '6', '16', 'tep_cfg_file_field(', now())");   
   }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_PAYPAL_PAYFLOW_STATUS', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_VENDOR', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_USERNAME', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_PASSWORD', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_PARTNER', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_TRANSACTION_SERVER', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_TRANSACTION_METHOD', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_ZONE', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_ORDER_STATUS_ID', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_SORT_ORDER', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_CURL', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_EXCEPT_COUNTRY', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_EXCEPT_ZONE', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_DISPLAY_NAME', 'MODULE_PAYMENT_PAYPAL_PAYFLOW_IMAGE');
    }

    function sendTransactionToGateway($url, $parameters, $headers = null) {
      $header = array();

      $server = parse_url($url);

      if (!isset($server['port'])) {
        $server['port'] = ($server['scheme'] == 'https') ? 443 : 80;
      }

      if (!isset($server['path'])) {
        $server['path'] = '/';
      }

      if (isset($server['user']) && isset($server['pass'])) {
        $header[] = 'Authorization: Basic ' . base64_encode($server['user'] . ':' . $server['pass']);
      }

      if (!empty($headers) && is_array($headers)) {
        $header = array_merge($header, $headers);
      }

      if (function_exists('curl_init')) {
        $curl = curl_init($server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : ''));
        curl_setopt($curl, CURLOPT_PORT, $server['port']);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);

        if (!empty($header)) {
          curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        $result = curl_exec($curl);

        curl_close($curl);
      } else {
        exec(escapeshellarg(MODULE_PAYMENT_PAYPAL_PAYFLOW_CURL) . ' -d ' . escapeshellarg($parameters) . ' "' . $server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : '') . '" -P ' . $server['port'] . ' -k' . (!empty($header) ? ' -H ' . escapeshellarg(implode("\r\n", $header)) : ''), $result);
        $result = implode("\n", $result);
      }

      return $result;
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
  }
?>
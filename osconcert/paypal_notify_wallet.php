<?php
/*
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	http://www.osconcert.com

  	Copyright (c) 2020 osConcert

	Released under the GNU General Public License
*/


// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	
	notify_log("----------------");
	include('includes/application_top.php');
	include(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_CHECKOUT_PROCESS);
	
	// load paypal_ipn payment module
	$FSESSION->set('payment','paypalipn');
	require(DIR_WS_CLASSES . 'payment.php');
	$payment_modules = new payment($FSESSION->payment);
	$file_contents = "";

	if (phpversion() <= '4.0.6') {
		$_POST = $FPOST;
		$file_contents = "PHP version:\n";
		$file_contents .= "-------------\n";
		$file_contents .= phpversion() . "\n";
		notify_log($file_contents);
	}

	require(DIR_WS_CLASSES . 'order.php');
	$order = new order($_POST['item_number']);
	
	$req = 'cmd=_notify-validate';
	
	$file_contents = "\nPost variables returned from PayPal for Wallet order ".$_POST['item_number'].":\n";
	$file_contents .= "----------------\n";
	foreach ($_POST as $key => $value) {
		$req .= '&' . $key . '=' . urlencode($value);
		$$key = $value;
		$file_contents .= $key . ":" . $value . "\n";
	}
	notify_log($file_contents);

	$response_verified = '';
	$paypal_response = '';
//Oct 2013
 $ipn_url="www.paypal.com";
	if (MODULE_PAYMENT_PAYPALIPN_TEST_MODE=='True') {
	
	   $ipn_url="www.sandbox.paypal.com";
		notify_log("Payment Mode(MODULE_PAYMENT_PAYPALIPN_TEST_MODE):" . MODULE_PAYMENT_PAYPALIPN_TEST_MODE . "\n");

		if ($item_number) {
		  $paypal_response = $_POST[ipnstatus];
	
		echo 'TEST IPN Processed for order #'.$item_number;
			$file_contents = "Item Order Number:\n";
			$file_contents .= "-------------\n";
			$file_contents .= "TEST IPN Processed for order #" . $item_number . "\n";
			notify_log($file_contents);
		} else {
	
		echo 'You need to specify an order #';
			$file_contents = "Item Order Number:\n";
			$file_contents .= "-------------\n";
			$file_contents .= "You need to specify an order #\n";
			notify_log($file_contents);
		};

  } 
  
  if (MODULE_PAYMENT_PAYPALIPN_CURL=='True') { // IF CURL IS ON, SEND DATA USING CURL (SECURE MODE, TO https://)
		notify_log("Payment Mode(MODULE_PAYMENT_PAYPALIPN_CURL):" . MODULE_PAYMENT_PAYPALIPN_CURL . "\n");

    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL,"https://".$ipn_url."/cgi-bin/webscr"); 
    curl_setopt($ch, CURLOPT_FAILONERROR, 1); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    $paypal_response = curl_exec($ch);
    curl_close ($ch);
	$file_contents = "Payment Response:\n";
	$file_contents .= "--------------------\n";
	$file_contents .= $paypal_response . "\n";
	notify_log($file_contents);

  } else { // ELSE, SEND IT WITH HEADERS (STANDARD MODE, TO http://)
	notify_log("Payment Module: attempting to contact PayPal using http.\n");

    $header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .="Host: ".$ipn_url."\r\n";
    $header .="Connection: close\r\n\r\n";
    $header .= "Content-Length: " . strlen ($req) . "\r\n\r\n";
    $fp = fsockopen ("www.paypal.com", 80, $errno, $errstr, 30);

    fputs ($fp, $header . $req);
    while (!feof($fp)) {
      $paypal_response .= fgets($fp, 1024);
    };
	notify_log("Paypal Response :\n" . $paypal_response . "\n");

    fclose ($fp);

  };

  if (preg_match('/VERIFIED/',$paypal_response)) {
    $response_verified = 1;
    $ipn_result = 'VERIFIED';
	notify_log("Paypal response Verified.\n");
  } else if (preg_match('/INVALID/',$paypal_response)) {
    $response_invalid = 1;
    $ipn_result = 'INVALID';
	notify_log("Invalid Response\n");
  } else {
    echo 'Error: no valid  received.';
	notify_log("Error: no valid response for IPN  received - try cURL.\n");
  };
  
  
  if ($txn_id && ($response_verified==1 || $response_invalid==1)) {

    $txn_check = tep_db_query("select txn_id from " . TABLE_PAYPALIPN_TXN . " where txn_id='".tep_db_input($txn_id)."'");
    if (tep_db_num_rows($txn_check)==0) { // If txn no previously registered, we should register it
		notify_log("Paypalipn_txn Has records.\n");

      $sql_data_array = array('txn_id' => $txn_id,
                              'ipn_result' => $ipn_result, 
                              'receiver_email' => $receiver_email, 
                              'business' => $business, 
                              'item_name' => $item_name, 
                              'item_number' => $item_number, 
                              'quantity' => $quantity, 
                              'invoice' => $invoice, 
                              'custom' => $custom, 
                              'option_name1' => $option_name1, 
                              'option_selection1' => $option_selection1, 
                              'option_name2' => $option_name2, 
                              'option_selection2' => $option_selection2, 
                              'num_cart_items' => $num_cart_items, 
                              'payment_status' => $payment_status, 
                              'pending_reason' => $pending_reason,
                              'payment_date' => $payment_date, 
                              'settle_amount' => $settle_amount, 
                              'settle_currency' => $settle_currency, 
                              'exchange_rate' => $exchange_rate, 
                              'payment_gross' => $payment_gross, 
                              'payment_fee' => $payment_fee, 
                              'mc_gross' => $mc_gross, 
                              'mc_fee' => $mc_fee, 
                              'mc_currency' => $mc_currency, 
                              'tax' => $tax, 
                              'txn_type' => $txn_type, 
                              'for_auction' => $for_auction, 
                              'memo' => $memo, 
                              'first_name' => $first_name, 
                              'last_name' => $last_name, 
                              'address_street' => $address_street, 
                              'address_city' => $address_city, 
                              'address_state' => $address_state, 
                              'address_zip' => $address_zip, 
                              'address_country' => $address_country, 
                              'address_status' => $address_status, 
                              'payer_email' => $payer_email, 
                              'payer_id' => $payer_id, 
                              'payer_status' => $payer_status,
                              'payment_type' => $payment_type,
                              'notify_version' => $notify_version,
                              'verify_sign' => $verify_sign);

      tep_db_perform(TABLE_PAYPALIPN_TXN,$sql_data_array);

    } else { // else we update it to the new status
		notify_log("Paypalipn_txn has not Records.\n");

      $sql_data_array = array('payment_status' => $payment_status,
                              'pending_reason' => $pending_reason,
                              'ipn_result' => $ipn_result,
                              'payer_email' => $payer_email, 
                              'payer_id' => $payer_id, 
                              'payer_status' => $payer_status,
                              'payment_type' => $payment_type);

      tep_db_perform(TABLE_PAYPALIPN_TXN,$sql_data_array,'update','txn_id=\''.$txn_id.'\'');

    };

  };
	
	
  if ($response_verified==1) {
  	notify_log("Response Verified.\n");
    if (strtolower($receiver_email)==strtolower(MODULE_PAYMENT_PAYPALIPN_ID) || strtolower($business)==strtolower(MODULE_PAYMENT_PAYPALIPN_ID))
	 {
		$file_contents = "MODULE_PAYMENT_PAYPALIPN_ID:" . MODULE_PAYMENT_PAYPALIPN_ID . "\n";
		$file_contents .= "received_email:" . $receiver_email . "\n";
		$file_contents .= strtolower($receiver_email) . ":" . strtolower(MODULE_PAYMENT_PAYPALIPN_ID) . "\n";
		$file_contents .= "business:" . $business . "\n";
		$file_contents .= strtolower($business) . ":" . strtolower(MODULE_PAYMENT_PAYPALIPN_ID) . "\n";
		notify_log($file_contents);
		notify_log('---'.$payment_status.'-----');
      if ($payment_status=='Completed') {
	  	notify_log("Payment Status is completed.\n");
		
		  if (is_numeric(MODULE_PAYMENT_PAYPALIPN_ORDER_STATUS_ID) && (MODULE_PAYMENT_PAYPALIPN_ORDER_STATUS_ID > 0) ) {
          $order_status = MODULE_PAYMENT_PAYPALIPN_ORDER_STATUS_ID;
			notify_log("order_status(MODULE_PAYMENT_PAYPALIPN_ORDER_STATUS_ID):" . MODULE_PAYMENT_PAYPALIPN_ORDER_STATUS_ID . "\n");
        } else {
          $order_status = 1;//pending
			     };


        $sql_data_array = array('comments' => 'PayPal IPN - payment success',
								'payment_status' =>  $order_status
								);
		
       tep_db_perform(TABLE_WALLET_UPLOADS, $sql_data_array, "update", "wallet_id='".$item_number."'");
	   
	   
	   
	   
	   				$email_sent=tep_db_query("select * from paypal_wallet_data where wallet_id='".$item_number."'");
				while($email_result= tep_db_fetch_array($email_sent)){
				 if($email_result['merge_data'] !=''){
					tep_send_default_email("WFU",unserialize($email_result['merge_data']),unserialize($email_result['send_data']));
				$sql_array=array(	"send_date"=>'now()',
						"customers_id"=>$email_result['customer_id'],
						"message_mode"=>"E",
						"message_type"=>"WFU"
				);
	              tep_db_perform(TABLE_WALLET_MESSAGES_HISTORY,$sql_array);
						
						}
	   

      };//end if payment status complete

    };
	};

  };
	function notify_log($file_contents){
	 //return;
	 $file = fopen("./'.LOG_PATH.'/logs/paypal_log.txt","a+");
	 fwrite($file,$file_contents);
	 fclose($file);
	}
?>
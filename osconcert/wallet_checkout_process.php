<?php
/*
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	http://www.osconcert.com

  	Copyright (c) 2020 osConcert

	Released under the GNU General Public License
*/

//edited 2014 Graeme - get PayPal to work

// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	
  include('includes/application_top.php');

	$validID=$FREQUEST->getvalue('validID','int',0);
	
	// if the customer is not logged on, redirect them to the login page
	if (!$FSESSION->is_registered('customer_id')) {
		$navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_WALLET_CHECKOUT_PAYMENT));
		tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
	}
	
	
	#############################################
	# payPal return here
	
	$paypalipn ='';
	$paypalorderno='';
	$paypalipn = $_GET['paypalipn'];
	$paypalorderno = $_GET['paypalorderno'];

		if ($paypalipn=='cancelled' && $paypalorderno !=''  )//manual cancellation at Paypal
        {
		paypalipn_cancel_wallet($paypalorderno,FILENAME_WALLET_CHECKOUT_PAYMENT,'',' Cancelled by customer clicking return link at PayPal');

		exit();
		}
	if ($paypalipn=='successful' && $paypalorderno !=''){//Ok so the order worked


	$FSESSION->set('error_count',0);
	//set the order status to the 'paid' value in the admin 

          $order_status = 1;//pending
			    


        $sql_data_array = array('comments' => 'Customer returns from PayPal, payment success reported awaits confirmation',
								'payment_status' =>  $order_status
								);
		
       tep_db_perform(TABLE_WALLET_UPLOADS, $sql_data_array, "update", "wallet_id='".$paypalorderno."'");
	
	// unregister session variables used during checkout
	$FSESSION->remove('payment_page');
	$FSESSION->remove('payment');
	$FSESSION->remove('comments');
	$FSESSION->remove('checkID');
	$FSESSION->remove('wallet_amount');
	$FSESSION->remove('wallet_timestamp');
	tep_redirect(tep_href_link(FILENAME_WALLET_CHECKOUT_SUCCESS,'insert_id=' . $paypalorderno, 'SSL'));

	}
############################################################

	if ( (tep_not_null(MODULE_PAYMENT_INSTALLED)) && (!$FSESSION->is_registered('payment')) ) {
		tep_redirect(tep_href_link(FILENAME_WALLET_CHECKOUT_PAYMENT,  'SSL'));
	}
	
	if ($validID!=$FSESSION->checkID) {
		tep_redirect(tep_href_link(FILENAME_WALLET_CHECKOUT_PAYMENT, '', 'SSL'));
	}

	$query=tep_db_query("SELECT customers_default_address_id from " . TABLE_CUSTOMERS . " c where customers_id='" . $FSESSION->customer_id . "'");
	$temp=tep_db_fetch_array($query);
	$FSESSION->billto=$temp['customers_default_address_id'];
	
	require(DIR_WS_CLASSES . 'order.php');
	$order = new order;

	$order->info['total']=$FSESSION->wallet_amount;

	
	// load selected payment module
	require(DIR_WS_CLASSES . 'payment.php');
	$payment=&$FSESSION->getobject('payment');
	$payment_modules = new payment($payment);

	$payment_modules->before_process();

	$payment_status = 1;//pending for the lot
	
	$sql_data_array = array(
							'customers_id' => $FSESSION->customer_id,
							'payment_date'=>'now()',
							'payment_method'=>$GLOBALS[$payment]->title,
							'payment_info' => $FSESSION->payment_info,
							'payment_status' => $payment_status,
							'comments' => $FSESSION->comments,
							'amount' => $FSESSION->wallet_amount
							);

	tep_db_perform(TABLE_WALLET_UPLOADS, $sql_data_array);

	$insert_id = tep_db_insert_id();

    $current_balance=tep_get_wallet_balance($FSESSION->customer_id);
	$customer_sql="SELECT c.customers_firstname,c.customers_lastname,c.customers_dob,c.customers_email_address,c.customers_telephone,
					c.customers_fax,a.entry_street_address,a.entry_city,a.entry_suburb,a.entry_postcode,a.entry_state,a.entry_country_id,a.entry_zone_id
					from " . TABLE_CUSTOMERS . " c," . TABLE_ADDRESS_BOOK . " a where a.address_book_id=c.customers_default_address_id and c.customers_id='" . (int)$FSESSION->customer_id . "'";

	$customer_query=tep_db_query($customer_sql);
	
	$customer_result=tep_db_fetch_array($customer_query);
		//for direct deposit module
	if($GLOBALS['payment']=='ausbank')
		$direct_deposit=sprintf(TEXT_DIRECT_DEPOSIT,substr(strtolower($customer_result['customers_firstname']),0,3) , substr(strtolower($customer_result['customers_lastname']),0,3) , $FSESSION->wallet_timestamp );
	else 
		$direct_deposit='';	
							
	$merge_details=array(	TEXT_FN=>$customer_result['customers_firstname'],
							TEXT_LN=>$customer_result['customers_lastname'],
							TEXT_DF=>format_date($customer_result['customers_dob']),
							TEXT_EM=>$customer_result['customers_email_address'],
							TEXT_TN=>$customer_result['entry_telephone_number'],
							TEXT_FX=>$customer_result['entry_fax'],
							TEXT_SA=>$customer_result['entry_street_address'],
							TEXT_SU=>$customer_result['entry_suburb'],
							TEXT_PC=>$customer_result['entry_postcode'],
							TEXT_CT=>$customer_result['entry_city'],
							TEXT_ST=>tep_get_zone_name($customer_result["entry_country_id"],$customer_result["entry_zone_id"],$customer_result['entry_state']),
							TEXT_CY=>tep_get_country_name($customer_result['entry_country_id']),
							TEXT_RE=>'',
							TEXT_IV=>'',
							TEXT_UN=>'',
							TEXT_PT=>$GLOBALS[$payment]->title,
							TEXT_DD=>$direct_deposit,
							TEXT_WAD=>$currencies->format($FSESSION->wallet_amount),
							TEXT_WCB=>$currencies->format($current_balance)
						);
	
   $send_details=array(
						array('to_name'=>$customer_result['customers_firstname'] . ' ' . $customer_result['customers_lastname'],
									 'to_email'=>$customer_result['customers_email_address'],
									 'from_name'=>STORE_OWNER,
									 'from_email'=>STORE_OWNER_EMAIL_ADDRESS
						)
					);
					
	#################### cut here for PayPal
		if ($GLOBALS[$payment]->code=="paypalipn" && $order->info['total']>0){
		
		//new code
		   tep_db_query("CREATE TABLE IF NOT EXISTS `paypal_wallet_data` (
				  `paypal_id` int(11) NOT NULL auto_increment,
				  `customer_id` int(11) NOT NULL,
				  `wallet_id` int(11) NOT NULL,
				  `send_data` longtext NOT NULL,
				  `merge_data` longtext NOT NULL,
				   PRIMARY KEY  (`paypal_id`)
				)  AUTO_INCREMENT=1 ;");
				
			 
                $sql_data_array = array(
				'wallet_id' => tep_db_prepare_input($insert_id),
                'customer_id' => $FSESSION->get('customer_id'),
                'send_data' => ( serialize($send_details)),
                'merge_data' =>( serialize($merge_details))
                );
                tep_db_perform('paypal_wallet_data', $sql_data_array);
           	
 
			$paypal_ipn_currency = MODULE_PAYMENT_PAYPALIPN_DEFAULT_CURRENCY; 
			
			$paypal_ipn_order_amount = $order->info['total'];
			
			$paypal_ipn_shipping_amount = 0.00;
			
			$paypal_ipn_tax_amount = 0.00;

	  
	 if(MODULE_PAYMENT_PAYPALIPN_TEST_MODE=='True') {
		$paypal_url="https://www.sandbox.paypal.com/us/cgi-bin/webscr";
	 }else{
		$paypal_url="https://www.paypal.com/cgi-bin/webscr";
	 }
	 	$FSESSION->set('paypal_ipn_started',$insert_id);
		
	 if(MODULE_PAYMENT_PAYPALIPN_RECORD_DATA=='True') {
		notify_log("-----------------------------\n New wallet payment # $insert_id data being sent to PayPal\n-----------------------------\n");
		notify_log("cmd=_ext-enter\n");
		notify_log("redirect_cmd=_xclick\n");
		notify_log("business=".MODULE_PAYMENT_PAYPALIPN_ID."\n");
		notify_log("item_name=".urlencode(STORE_NAME)."\n");
		notify_log("item_number=".$insert_id."\n");
		notify_log("currency_code=".$paypal_ipn_currency."\n");
		notify_log("amount=".$paypal_ipn_order_amount."\n");
		notify_log("shipping=".$paypal_ipn_shipping_amount.($paypal_ipn_tax_amount>0?"&tax=".$paypal_ipn_tax_amount:'') ."\n");
		//notify_log("tax=".$paypal_ipn_tax_amount:'') ."\n");
		notify_log("first_name=".urlencode($order->customer['firstname'])."\n");
		notify_log("last_name=".urlencode($order->customer['lastname'])."\n");
		notify_log("address1=".urlencode($order->customer['street_address'])."\n");
		notify_log("city=".urlencode($order->customer['city'])."\n");
		notify_log("state=".urlencode($order->customer['state'])."\n");
		notify_log("zip=".urlencode($order->customer['postcode'])."\n");
		notify_log("bn=" . urlencode(STORE_NAME) . "\n");
		notify_log("return=".(tep_href_link_paypal(FILENAME_CHECKOUT_PROCESS, 'paypalipn=successful%26paypalorderno='.$insert_id, 'SSL'))."\n");
		notify_log("cancel_return=".(tep_href_link_paypal(FILENAME_CHECKOUT_PROCESS, 'paypalipn=cancelled%26paypalorderno='.$insert_id, 'SSL'))."\n");
		notify_log("notify_url=".urlencode(MODULE_PAYMENT_PAYPALIPN_NOTIFY_URL)."\n");
		notify_log("-----------------------------\n");
		}
	  
	    $paypal_ipn_order_amount = tep_get_rounded_amount($paypal_ipn_order_amount * $currencies->get_value($paypal_ipn_currency));
		$FSESSION->set('paypalipn_temp_id',$insert_id);

	    tep_redirect($paypal_url."?cmd=_ext-enter&redirect_cmd=_xclick&business=".MODULE_PAYMENT_PAYPALIPN_ID."&custom=wallet&item_name=".urlencode(STORE_NAME.' Wallet Payment')."&item_number=".$insert_id."&currency_code=".$paypal_ipn_currency."&amount=".$paypal_ipn_order_amount."&shipping=".$paypal_ipn_shipping_amount.($paypal_ipn_tax_amount>0?"&tax=".$paypal_ipn_tax_amount:'') ."&first_name=".urlencode($customer_result['firstname'])."&last_name=".urlencode($customer_result['lastname'])."&address1=".urlencode($customer_result['entry_street_address'])."&city=".urlencode($customer_result['entry_city'])."&state=".urlencode($customer_result['entry_state'])."&zip=".urlencode($customer_result['entry_postcode'])."&bn=" . urlencode(STORE_NAME) . '&return='.(tep_href_link_paypal(FILENAME_WALLET_CHECKOUT_PROCESS, 'paypalipn=successful%26paypalorderno='.$insert_id, 'SSL'))."&cancel_return=".(tep_href_link_paypal(FILENAME_WALLET_CHECKOUT_PROCESS, 'paypalipn=cancelled%26paypalorderno='.$insert_id, 'SSL'))."&notify_url=".(tep_href_link_paypal('paypal_notify_wallet.php', '', 'SSL')));
		exit();
}
	
	#################### end PayPal cut
	$sql_array=array(	"send_date"=>'now()',
						"customers_id"=>$FSESSION->customer_id,
						"message_mode"=>"E",
						"message_type"=>"WFU"
				);
	tep_db_perform(TABLE_WALLET_MESSAGES_HISTORY,$sql_array);
	
	tep_send_default_email("WFU",$merge_details,$send_details);
	
	// unregister session variables used during checkout
	$FSESSION->remove('payment_page');
	$FSESSION->remove('payment');
	$FSESSION->remove('comments');
	$FSESSION->remove('checkID');
	$FSESSION->remove('wallet_amount');
	$FSESSION->remove('wallet_timestamp');
	tep_redirect(tep_href_link(FILENAME_WALLET_CHECKOUT_SUCCESS,'insert_id=' . $insert_id, 'SSL'));

	require(DIR_WS_INCLUDES . 'application_bottom.php');
	
	######################### start of new paypal code/ functions #######################
	  	function notify_log($file_contents){
	 //return;
	 $file = fopen("./'.LOG_PATH.'/logs/paypal_log.txt","a+");
	 fwrite($file,$file_contents);
	 fclose($file);
	}
	  function tep_href_link_paypal($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
    global $request_type, $SID,$FSESSION;
    if (!tep_not_null($page)) {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>');
    }
	if ($connection!='NONSSL' &&  $connection!='SSL'){
		die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL</b><br><br>');
	}
	if(strpos($parameters,'command=')!==false){
		$connection=$request_type;
		$search_engine_safe=false;
	}


	if ($connection == 'NONSSL') {
      $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == 'true') {
        $link = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG;
      } else {
        $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
      }
    } 
    if (tep_not_null($parameters)) {
      $link .= $page . '?' . tep_output_string($parameters);
      $separator = '&';
    } else {
      $link .= $page;
      $separator = '?';
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

// Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
    if ( ($add_session_id == true) && ($FSESSION->STARTED == true) && (SESSION_FORCE_COOKIE_USE == 'False') ) {
      if (tep_not_null($SID)) {
        $_sid = $SID;
      } elseif ( ( ($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL == true) ) || ( ($request_type == 'SSL') && ($connection == 'NONSSL') ) ) {
        if (HTTP_COOKIE_DOMAIN != HTTPS_COOKIE_DOMAIN) {
          $_sid = $FSESSION->NAME . '=' . $FSESSION->ID;
        }
      }
    }
	
    if (isset($_sid)) {
	  $link .= $separator . tep_output_string($_sid);
    }
   	return $link;
  }
  
  
  	function paypalipn_cancel_wallet($order_id,$redirect_page=FILENAME_WALLET_CHECKOUT_PAYMENT,$extras='', $note='')
{
    global $FSESSION;

    // if we have the order_id then do stuff
    if (tep_not_null($order_id)) {

        //change the wallet details status
        $sql_data_array = array('comments' => $note,
								'payment_status' =>  MODULE_PAYMENT_PAYPALIPN_CANX_ORDER_STATUS_ID,
								'amount'=> '0.00');

        tep_db_perform(TABLE_WALLET_UPLOADS, $sql_data_array, "update", "wallet_id='".$order_id."'");
  
        if(MODULE_PAYMENT_PAYPALIPN_RECORD_DATA=='True') {
		notify_log("-----------------------------\n Wallet order # $order_id $note\n-----------------------------\n");

		notify_log("------------------------------\n");		
		}       
	    $extras='error_message=Payment+Cancelled+at+PayPal.';
		$FSESSION->remove('paypalipn_temp_id');
        tep_redirect(tep_href_link(FILENAME_WALLET_CHECKOUT_PAYMENT,$extras, 'SSL'));
        exit();
        return false;
    }
}
?>
<?php
/*

  

  Freeway eCommerce from ZacWare
  http://www.openfreeway.org

  Copyright 2007 ZacWare Pty. Ltd

  Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );

	include('includes/application_top.php');
	require(DIR_WS_CLASSES . 'payment.php');
	require(DIR_WS_CLASSES . 'currencies.php');


	// check for the customer id 
	$cID=$FSESSION->get('upload_id','int',0);
	
	$validID=$FREQUEST->getvalue('validID','int',0);
	$checkID=$FSESSION->get("checkID",'int',0);
	$wallet_timestamp=$FSESSION->get('wallet_timestamp','int',0);
    $wallet_amount=$FSESSION->get('wallet_amount','int',0);
    $payment=$FSESSION->get('payment','','');
    $comments=$FSESSION->get('comments','','');
//echo $wallet_amount . $payment . $comments;exit;

	$server_date = getServerDate(true);
	
	if ($cID==0) {
		tep_redirect(tep_href_link(FILENAME_CUSTOMERS));
	}

	// check for the payment
	if ( (tep_not_null(MODULE_PAYMENT_INSTALLED)) && (!$FSESSION->is_registered('payment')) ) {
		tep_redirect(tep_href_link(FILENAME_WALLET_PAYMENT));
	}
	
	//check for the valid internal id
	if ($validID!=$checkID) {
		tep_redirect(tep_href_link(FILENAME_WALLET_PAYMENT));
	}

	$currencies=new currencies();
	$payment_modules = new payment($payment);
	  
	$payment_status = $GLOBALS['administrator_login'] ? $GLOBALS['administrator_login'] : DEFAULT_ORDERS_STATUS_ID;	
	if ( isset($GLOBALS[$payment]->order_status) && is_numeric($GLOBALS[$payment]->order_status) && ($GLOBALS[$payment]->order_status > 0) ) {
		$payment_status=$GLOBALS[$payment]->order_status;
	}

	$sql_data_array = array(
							'customers_id' => $cID,
							'payment_date'=>$server_date,
							'payment_method'=>$GLOBALS[$payment]->title,
							'payment_info' => $GLOBALS['payment_info'],
							'payment_status' => $payment_status,
							'comments' => $comments,
							'amount' => $wallet_amount
							);

	tep_db_perform(TABLE_WALLET_UPLOADS, $sql_data_array);
	$insert_id=tep_db_insert_id();
		
    $current_balance=tep_get_wallet_balance($cID);
	$customer_sql="SELECT c.customers_firstname,c.customers_lastname,c.customers_dob,c.customers_email_address,c.customers_telephone,
					c.customers_fax,a.entry_street_address,a.entry_city,a.entry_suburb,a.entry_postcode,a.entry_state,a.entry_country_id,a.entry_zone_id
					from " . TABLE_CUSTOMERS . " c," . TABLE_ADDRESS_BOOK . " a where a.address_book_id=c.customers_default_address_id and c.customers_id='" . (int)$cID . "'";

	$customer_query=tep_db_query($customer_sql);
	
	$customer_result=tep_db_fetch_array($customer_query);
	
		//for direct deposit module
	if($GLOBALS['payment']=='ausbank')
		$direct_deposit=sprintf(TEXT_DIRECT_DEPOSIT,substr(strtolower($customer_result['customers_firstname']),0,3) , substr(strtolower($customer_result['customers_lastname']),0,3) , $wallet_timestamp );
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
							TEXT_WAD=>$currencies->format($wallet_amount),
							TEXT_WCB=>$currencies->format($current_balance)
						);
	
   $send_details=array(
						array('to_name'=>$customer_result['customers_firstname'] . ' ' . $customer_result['customers_lastname'],
									 'to_email'=>$customer_result['customers_email_address'],
									 'from_name'=>STORE_OWNER,
									 'from_email'=>STORE_OWNER_EMAIL_ADDRESS
						)
					);
	$sql_array=array(	"send_date"=>$server_date,
						"customers_id"=>$cID,
						"message_mode"=>"E",
						"message_type"=>"WFU"
				);
	tep_db_perform(TABLE_WALLET_MESSAGES_HISTORY,$sql_array);
	
	tep_send_default_email("WFU",$merge_details,$send_details);
	
    //$messageStack->add_session(SUCCESS_UPLOAD_UPDATED, 'success');

	$FSESSION->remove('payment_page');
	$FSESSION->remove('comments');
	$FSESSION->remove('wallet_amount');
	$FSESSION->remove('payment');
	$FSESSION->remove('wallet_timestamp');
	
   tep_redirect(tep_href_link(FILENAME_WALLET_SUCCESS,'id=' . $insert_id));

	require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
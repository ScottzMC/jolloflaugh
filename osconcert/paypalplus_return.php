<?php
/*




Some portions (c) 2003 osCommerce
(c) 2005 osCommRes
(c) 2007 ZacWare
(c) 2009 osCommerce

*/


// Set flag that this is a parent file
define('_FEXEC', 1);


require('includes/application_top.php');
require('includes/functions/sessions.php');
include_once(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_CHECKOUT_CONFIRMATION);
include_once(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/payment/paypalplus_germany.php');
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//stop hack from not logged in folks
if (!$FSESSION->is_registered('customer_id')) {
				
				exit('No customer id');
} 
//check for order_id
if (!$FSESSION->is_registered('paypalplus_de_id')) {
			
				exit('No order');
}


// check for a returned id in the url
//check the token
$paymentId = $_GET['paymentId'];

if(!tep_not_null($paymentId)){
               exit('No payment id');
			   }
			   
$this_order_id = $order_id = $_SESSION['paypalplus_de_id'];

$FSESSION->remove('paypalplus_de_id');

			   
//curl to PayPal for order result
			      if (MODULE_PAYMENT_PAYPALPLUS_DE_TEST_MODE=='True') {
				    $data_url='admin/logs/data_test.json';
					$ppurl   ='api.sandbox.paypal.com';
					   }else{
					$data_url='admin/logs/data_live.json';
					$ppurl   ='api.paypal.com';}
					
$url='https://'.$ppurl.'/v1/payments/payment/'.$paymentId.'/execute';
$JSONrequest = '{"payer_id":"'.$_GET['PayerID'].'"}';

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

	$resultExecutePayment = json_decode(curl_exec($ch),true); 
	curl_close ($ch);




if (strtoupper($resultExecutePayment['state']) == 'APPROVED'){
// only approved we need the state of the sale object

if (($resultExecutePayment['transactions'][0]['related_resources'][0]['sale']['state'] == 'completed')){

	// change order status

				$order_status=tep_db_query("select orders_status from orders where orders_id='".$this_order_id."'");
				$order_result= tep_db_fetch_array($order_status);
				
				if ($order_result['orders_status']!= MODULE_PAYMENT_PAYPALPLUS_DE_COMP_ORDER_STATUS_ID){//completed status not found 			
						//update the status						
					$sql_data_array = array(
						'orders_status' => MODULE_PAYMENT_PAYPALPLUS_DE_COMP_ORDER_STATUS_ID,
									);
					tep_db_perform(TABLE_ORDERS, $sql_data_array, "update", "orders_id='" . $this_order_id. "'");
					    //update status history
					$sql_data_array = array('orders_id' => $this_order_id,
					'orders_status_id' => MODULE_PAYMENT_PAYPALPLUS_DE_COMP_ORDER_STATUS_ID,
					'date_added' => date('Y-m-d H:i:s',getServerDate(false)), 
					'customer_notified' => '1',
					'comments' => 'Customer returns from PayPal: Payment Completed.');

					tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
					}
					
					//2021 update products status
					
					tep_db_query("update orders_products set orders_products_status = ". MODULE_PAYMENT_PAYPALPLUS_DE_COMP_ORDER_STATUS_ID . " where orders_id = " . $this_order_id);
					
					 include_once('includes/classes/order.php');
				
				$order = new order($this_order_id);
				
				//send order emails 
					//(1) run through the order products list and compile $ order_is printable
					$order_is_printable = 0;
					for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {								
		                 $order_is_printable = $order_is_printable + $order->products[$i]['is_printable'];
					}
					//(2) if printable setup ticket and generate filename
					$filename="";
							
					if($order_is_printable > 0 && EMAIL_PDF_DELIVERED_ONLY=='true' && E_TICKETS == 'true' & MODULE_PAYMENT_PAYPALPLUS_DE_COMP_ORDER_STATUS_ID == E_TICKET_STATUS ){ 
					//if( EMAIL_PDF_DELIVERED_ONLY=='true' && E_TICKETS == 'true' && $MODULE_PAYMENT_PAYPALPLUS_DE_COMP_ORDER_STATUS_ID == E_TICKET_STATUS ){
					try
					  {
						  require_once('includes/functions/products_ticket.php');
						  $filename= (create_checkout_pdf($this_order_id));
						  }
						  
					 //catch exception
					   catch(Exception $e)
						  {
						   // empty catch statement  - this will allow email to still go if the require once fails
						
						 }	
					 //set ticket printed
		                   tep_db_query("update  " . TABLE_ORDERS . "  set ticket_printed='Y' where orders_id in(" . $this_order_id . ")");
						   
						     }
		
		        try{
			
				$email_sent=tep_db_query("select * from email_data where order_id='".$this_order_id."'");
				while($email_result= tep_db_fetch_array($email_sent)){			
					tep_send_default_email("PRD",unserialize(base64_decode($email_result['merge_data'])),unserialize(base64_decode($email_result['send_data'])),$filename);
				}
				}
				catch(Exception $e)
				{//empty catch in case webhook has beaten this file to it
				}
				

				//if delivered then delete email from table
				  if ($order_is_printable > 0){
					  tep_db_query("delete from email_data where order_id='".$this_order_id."'");
				  }
					}else{
					$order_status=tep_db_query("select orders_status from orders where orders_id='".$this_order_id."'");
				$order_result= tep_db_fetch_array($order_status);
				
			       {//completed status not found 	at PayPal so note the order		

					    //update status history
					$sql_data_array = array('orders_id' => $this_order_id,
					'orders_status_id' => $order_result['orders_status'],
					'date_added' => date('Y-m-d H:i:s',getServerDate(false)), 
					'customer_notified' => '1',
					'comments' => 'Kundenretoure von PayPal: Zahlung dargestellt als: '.$resultExecutePayment['transactions'][0]['related_resources'][0]['sale']['state']);

					tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
					}
					
					}
               


				
				

			
tep_db_query("DELETE FROM `customers_away_basket` where `customers_id`='".$_SESSION['customer_id']."'");
	            $cart->reset(true);
				$FSESSION->set('error_count',0);
				// unregister session variables used during checkout
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
				//Add Extra Fields
				$FSESSION->remove('other');
				$FSESSION->remove('field_1'); 
				$FSESSION->remove('field_2');
				$FSESSION->remove('field_3');
				$FSESSION->remove('field_4');
				//Add Extra Fields end


				
			 tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id='. $order_id . '&payment=paypalplus_de', 'SSL'));
				 
				 exit();

}

else {//do nothing
        
// payment is unknown or in progress
	// change order status

				$order_status=tep_db_query("select orders_status from orders where orders_id='".$this_order_id."'");
				$order_result= tep_db_fetch_array($order_status);
				
				{				

					    //update status history
					$sql_data_array = array('orders_id' => $this_order_id,
					'orders_status_id' => $order_result['orders_status'],
					'date_added' => date('Y-m-d H:i:s',getServerDate(false)), 
					'customer_notified' => '0',
					'comments' => 'Kunde kehrte nach Zahlungseingang zurück. Zahlungsstatus kann nicht bestätigt werden');

					tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
					}
					//check if email sent - if data in the table then use it, if not then nada
						//check if email sent - if data in the table then use it, if not then nada
				if(table_exists('email_data')){
				$email_sent=tep_db_query("select * from email_data where order_id='".$this_order_id."'");
				while($email_result= tep_db_fetch_array($email_sent)){			

tep_send_default_email("PRD",unserialize(base64_decode($email_result['merge_data'])),unserialize(base64_decode($email_result['send_data'])));
				}
				tep_db_query("delete from email_data where order_id='".$this_order_id."'");
				}
				
tep_db_query("DELETE FROM `customers_away_basket` where `customers_id`='".$_SESSION['customer_id']."'");
	            $cart->reset(true);
				$FSESSION->set('error_count',0);
				// unregister session variables used during checkout
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
				//Add Extra Fields
				$FSESSION->remove('other');
				$FSESSION->remove('field_1'); 
				$FSESSION->remove('field_2');
				$FSESSION->remove('field_3');
				$FSESSION->remove('field_4');
				//Add Extra Fields end


				
			 tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id='. $order_id . '&payment=paypalplus_de', 'SSL'));
				 
				 exit();



}


exit('error');

/////////////////////////////////////////////////////////
function table_exists($tablename, $database = false)
{
$CheckTable = tep_db_query("SHOW TABLES LIKE '".$tablename."'");
if( tep_db_num_rows($CheckTable) > 0 ) {
  return true;
  	}else{
  return false;
  }
    

}
//////////////////////////////////////////////////

function kill_sessions_tell_boss($note){
global $cart, $messageStack;

 //either incoming is missing or there's a missmatch
 $messageStack->add_session('header', MODULE_PAYMENT_PAYPALPLUS_DE_TEXT_SHA_MISMATCH , 'error');
 				//tell the boss
				tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'PayPalPlus error '.$note, 'There has just been a possible security breach attempt with the PayPalPlus customer return file. ', STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
				//kill all sessions leave order intact
				unset($_SESSION['customer_id']);
				unset($_SESSION['customer_default_address_id']);
				unset($_SESSION['customer_first_name']);
				unset($_SESSION['customer_country_id']);
				unset($_SESSION['customer_zone_id']);
				unset($_SESSION['comments']);
				unset($_SESSION['payever_cart']);
				$cart->reset(true);
				// unregister session variables used during checkout
				unset($_SESSION['BoxOffice']);
				unset($_SESSION['sendto']);
				unset($_SESSION['billto']);
				unset($_SESSION['shipping']);
				unset($_SESSION['payment']);
				unset($_SESSION['comments']);
				unset($_SESSION['order_timestamp']);
				unset($_SESSION['receiptNo']);
				unset($_SESSION['transactionNr']);
				unset($_SESSION['coupon']);
				unset($_SESSION['ccno']);
				unset($_SESSION['gv_redeem_code']);
				unset($_SESSION['billto_array']);
				unset($_SESSION['sendto_array']);
				unset($_SESSION['paypal_ipn_started']);
				//Add Extra Fields
				unset($_SESSION['other']);
				unset($_SESSION['field_1']); 
				unset($_SESSION['field_2']);
				unset($_SESSION['field_3']);
				unset($_SESSION['field_4']);
				//Add Extra Fields end
				

				//create error
				
				//redirect - login ot shopping cart    
				tep_redirect(tep_href_link('index.php'));
				exit();

}

function mollie_repopulate_cart($order_id,$mollie_status)
{
				global $FSESSION;

				if (tep_not_null($order_id)) {
				include_once('includes/functions/ga_tickets.php');
					if(table_exists('email_data')){
							tep_db_query("delete from email_data where order_id='".$order_id."'");
							}

								$order_query = tep_db_query("select products_id, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $order_id . "'");
								while ($order = tep_db_fetch_array($order_query)) {
								//we  need to NOT update here if quantity in order_products == zero
								//as it may already be done in the return url??
								if ($order['products_quantity'] >0){
								/// this is the check for the duplicate
												tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order['products_quantity'] . ", products_ordered = products_ordered - " . $order['products_quantity'] . ", products_status='1' where products_id = '" . (int) $order['products_id'] . "'");
								
												if (function_exists('ga_check_process_restock')){
										 		ga_check_process_restock((int)$order['products_id'], $order['products_quantity'], $order['events_type']);	
											}	
								} //$order = tep_db_fetch_array($order_query)
								}
								//give the order total a value of 0.00
								tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $order_id . "'");
								tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set products_quantity = '0' where orders_id = '" . $order_id . "'");
								tep_db_query("insert into " . TABLE_ORDERS_TOTAL . " (orders_id, title, text, value, class, sort_order) values ('" . $order_id . "', 'Total', '0.00', '0','ot_total', '99')");
								tep_db_query("delete from coupon_redeem_track where order_id = '" . $order_id . "'");
				
				
				
				
				
								//grab customers name from order
								$cust_query = tep_db_query("select customers_name from " . TABLE_ORDERS . " where orders_id = '" . (int) $order_id . "'");
								if (tep_db_num_rows($cust_query)) {
												$cust_query_result = tep_db_fetch_array($cust_query);
												$cust_name         = $cust_query_result['customers_name'];
								} //tep_db_num_rows($cust_query)
		
								$cancel = 'Paymark - Failed::';
								  if(stristr($cust_name, $cancel) === FALSE) {
										 $cust_name = $cancel.$cust_name;
									   }
								//change the order status
								$sql_data_array = array(
												'orders_status' => MODULE_PAYMENT_PAYPALPLUS_DE_TRANSACTION_CANX_STATUS_ID,
												'customers_name' =>  $cust_name
								);
								tep_db_perform(TABLE_ORDERS, $sql_data_array, "update", "orders_id='" . $order_id . "'");
								
								$sql_data_array = array(
												'orders_id' => $order_id,
												'orders_status_id' => MODULE_PAYMENT_PAYPALPLUS_DE_TRANSACTION_CANX_STATUS_ID,
												'date_added' => date('Y-m-d H:i:s',getServerDate(false)), 
												'customer_notified' => 0,
												'comments' => $mollie_status
								);
								tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
								
										 //restock basket table
							tep_db_query("INSERT IGNORE `customers_basket` SELECT * FROM `customers_away_basket` where `session_id`='".tep_session_id()."'");
							tep_db_query("DELETE FROM `customers_away_basket` where `session_id`='".tep_session_id()."'");
							//swap cart sessions
							 $FSESSION->set('cart',$_SESSION['paypalplus_de_cart']);
							 $FSESSION->set('paypalplus_de_cart','');

								
								tep_redirect( tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=paypalplus_de&ErrDesc=Payment+failed', 'SSL') );
								exit('Error on return page');
								
                                		
								
								exit();
								return false;
				} //tep_not_null($order_id)
}

// format prices without currency formatting
    function format_raw_a($number, $currency_code = '', $currency_value = '') {
      global $currencies, $currency, $FSESSION, $order;
          $currency_code = $order->info['currency'];
          $currency_value = $order->info['currency_value'];


      return number_format(tep_round($number * $currency_value, $currencies->currencies[$currency_code]['decimal_places']), $currencies->currencies[$currency_code]['decimal_places'], '.', '');
    }
  
?>
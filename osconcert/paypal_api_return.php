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
include_once(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/payment/paypal_api.php');

//stop hack from not logged in folks
if (!$FSESSION->is_registered('customer_id')) {
				
				exit(MODULE_PAYMENT_PAYPAL_API_TEXT_NO_ORDER);
} 
//check for order_id
if (!$FSESSION->is_registered('paypal_api_id')) {
			
				exit(MODULE_PAYMENT_PAYPAL_API_TEXT_NO_ORDER);
}


// check for a returned id in the url
//check the token
$paymentId = $_GET['paymentId'];

if(!tep_not_null($paymentId)){
               exit(MODULE_PAYMENT_PAYPAL_API_TEXT_NO_ORDER);
			   }
			   
$this_order_id = $order_id = $_SESSION['paypal_api_id'];


$_SESSION['paypal_api_return'] = $this_order_id;

require_once('includes/classes/order.php');
require_once('includes/classes/payment.php');

$order = new order($this_order_id );
$payment = new payment('paypal_api');

$FSESSION->remove('paypal_api_id');
$FSESSION->remove('paypal_api_token');
			   
//curl to PayPal for order result
			      if (MODULE_PAYMENT_PAYPAL_API_TEST_MODE=='True') {
				    $data_url=MODULE_PAYMENT_PAYPAL_API_ADMIN . '/logs/data_test.json';
					$ppurl   ='api.sandbox.paypal.com';
					   }else{
					$data_url=MODULE_PAYMENT_PAYPAL_API_ADMIN . '/logs/data_live.json';
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
	
	$duplicate = "";
	if (strtoupper($resultExecutePayment['name']) == 'DUPLICATE_TRANSACTION'){
	$duplicate = " Paypal notifies that a duplicate invoice number was seen - payment has not been fully processed.";
				if (MODULE_PAYMENT_PAYPAL_API_DEBUG == 'True' && tep_not_null(MODULE_PAYMENT_PAYPAL_API_DEBUG_EMAIL)){
	             tep_mail('DUPLICATE_TRANSACTION', MODULE_PAYMENT_PAYPAL_API_DEBUG_EMAIL, 'PayPal DUPLICATE_TRANSACTION', "Order #: ".$order_id. " seats reserved but payment not accepted at PayPal - update your PayPal settingd to allow duplicate invoice numbers.", STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);	
			}
	}


$delivered = "no";

if (strtoupper($resultExecutePayment['state']) == 'APPROVED'){ 
// only approved we need the state of the sale object

if (($resultExecutePayment['transactions'][0]['related_resources'][0]['sale']['state'] == 'completed')){

	// change order status

				$order_status=tep_db_query("select orders_status from orders where orders_id='".$this_order_id."'");
				$order_result= tep_db_fetch_array($order_status);
				
				
				if ($order_result['orders_status']!= MODULE_PAYMENT_PAYPAL_API_COMP_ORDER_STATUS_ID){//completed status not found 			
						//update the status						
					$delivered = "yes";
					$status = MODULE_PAYMENT_PAYPAL_API_COMP_ORDER_STATUS_ID;
					$sql_data_array = array(
						'orders_status' => $status,
									);
					tep_db_perform(TABLE_ORDERS, $sql_data_array, "update", "orders_id='" . $this_order_id. "'");
					    //update status history
					$sql_data_array = array('orders_id' => $this_order_id,
					'orders_status_id' => $status,
					'date_added' => date('Y-m-d H:i:s',getServerDate(false)), 
					'customer_notified' => '1',
					'comments' => 'Customer returns from PayPal: Payment Completed.');

					tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
					
					//better late than never
					//tep_db_query("update orders_products set orders_products_status = ". $status . " where orders_id = " . $this_order_id);
					  tep_db_query("update orders_products set orders_products_status = ". $status . " where orders_id = '" . $this_order_id . "'");
					
					}
					}else{
					$order_status=tep_db_query("select orders_status from orders where orders_id='".$this_order_id."'");
				    $order_result= tep_db_fetch_array($order_status);
					$status = $order_result['orders_status'];
				
			       {//completed status not found 	at PayPal so note the order		

					    //update status history
					$sql_data_array = array('orders_id' => $this_order_id,
					'orders_status_id' => $order_result['orders_status'],
					'date_added' => date('Y-m-d H:i:s',getServerDate(false)), 
					'customer_notified' => '1',
					'comments' => $duplicate .'Customer returns from PayPal: Payment shown as: '.$resultExecutePayment['transactions'][0]['related_resources'][0]['sale']['state']);

					tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
					}
					
					}
//send order emails 
					//(1) run through the order products list and compile $ order_is printable
					$order_is_printable = 0;
					for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {								
		                 $order_is_printable = $order_is_printable + $order->products[$i]['is_printable'];
					}
					//(2) if printable setup ticket and generate filename
					$filename="";
							
					if($order_is_printable > 0 && EMAIL_PDF_DELIVERED_ONLY=='true' && E_TICKETS == 'true' && $status == E_TICKET_STATUS ){ 
					//if( EMAIL_PDF_DELIVERED_ONLY=='true' && E_TICKETS == 'true' && $status == E_TICKET_STATUS );{
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
  if ($delivered == 'yes'){
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


				
			 tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id='. $order_id . '&payment=paypal_api', 'SSL'));
				 
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
					'comments' => 'Customer returned after payment. Unable to confirm payment status');

					tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
					}
					//check if email sent - if data in the table then use it, if not then nada
						//check if email sent - if data in the table then use it, if not then nada
				if(table_exists('email_data')){
				$email_sent=tep_db_query("select * from email_data where order_id='".$this_order_id."'");
				while($email_result= tep_db_fetch_array($email_sent)){			

tep_send_default_email("PRD",unserialize(base64_decode($email_result['merge_data'])),unserialize(base64_decode($email_result['send_data'])));
				}
				//tep_db_query("delete from email_data where order_id='".$this_order_id."'");
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
				//$FSESSION->remove('paypal_ipn_started');
				//Add Extra Fields
				$FSESSION->remove('other');
				$FSESSION->remove('field_1'); 
				$FSESSION->remove('field_2');
				$FSESSION->remove('field_3');
				$FSESSION->remove('field_4');
				//Add Extra Fields end


				
			 tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id='. $order_id . '&payment=paypal_api', 'SSL'));
				 
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

?>
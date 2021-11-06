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

// file to handle the Stripe webhook

// Set flag that this is a parent file
	define( '_FEXEC', 1 );

require('includes/application_top.php');

require_once('./includes/modules/payment/stripesca.php');
//require_once(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/payment/stripesca.php');
//there is no language session available to the webhook - hard code English
include_once('./includes/languages/english/modules/payment/stripesca.php');
require_once('./ext/modules/payment/stripesca/init.php');

$stripesca = new stripesca;
\Stripe\Stripe::setApiKey($stripesca->secret);

$endpoint_secret = MODULE_PAYMENT_STRIPESCA_WEBHOOK_KEY;

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch(\UnexpectedValueException $e) {
    // Invalid payload
	print"Unexpected Value Exception: problem with security";
	http_response_code(400); // PHP 5.4 or greater
    exit();
} 

if ($event->type == "payment_intent.succeeded") {
    $intent = $event->data->object;
    printf("Succeeded: %s", $intent->id);
	release_order($intent->id);
    http_response_code(200);
    exit();
} elseif ($event->type == "payment_intent.canceled") {
    $intent = $event->data->object;
    $error_message = $intent->last_payment_error ? $intent->last_payment_error->message : "";	
	cancel_order($intent->id, $error_message);
	print  "Canceled";
    http_response_code(200);
    exit();
}
	
	elseif ($event->type == "payment_intent.payment_failed") {
    $intent = $event->data->object;
    $error_message = $intent->last_payment_error ? $intent->last_payment_error->message : "";
    printf("Failed: %s, %s", $intent->id, $error_message);	
	note_order($intent->id, "Payment failed: ".$error_message);
    http_response_code(200);
    exit();
	
	

} elseif ($event->type == "checkout.session.completed") {
    $session_id = $event->data->object->id;
    $payment_id = $event->data->object->payment_intent;
	 print("Succeeded");
	http_response_code(200);
    exit();
}


function cancel_order($payment_id, $note){
	global $stripesca;
	$order_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where payment_return2 = '" . $payment_id. "' LIMIT 1");	
	$order_result =tep_db_fetch_array($order_query);
	$order_id = $order_result['orders_id'];

    if (empty($order_id)){echo ('  Order ID not found  ');
	http_response_code(400);
	exit();}

    // if we have the order_id then do stuff
    if (tep_not_null($order_id)) {
		
		$stripesca->repopulate_cart($order_id,'','',"Webhook: ".MODULE_PAYMENT_STRIPESCA_CANCEL_ERROR);
            
       return false;
      
    }

	
	
	
}

function note_order($payment_id, $note){
	
	$order_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where payment_return2 = '" . $payment_id. "' LIMIT 1");	
	$order_result =tep_db_fetch_array($order_query);
	$this_order_id = $order_result['orders_id'];

    if (empty($this_order_id)){
	//echo ('  Order ID not found  ');
	http_response_code(500);
	exit();}
	
	//get current order status
	$order_status=tep_db_query("select orders_status from orders where orders_id='".$this_order_id."'");
	$order_result= tep_db_fetch_array($order_status);
	$status_orig = $order_result['orders_status'];	
	
 $sql_data_array = array('orders_id' => $this_order_id,
        'orders_status_id' => $status_orig,
        'date_added' => date('Y-m-d H:i:s',getServerDate(false)),
        'customer_notified' => 0,
        'comments' => $note );
        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
		
		return false;
	
}

	
 function release_order($payment_id){
	 // get the order_id
	 
    $order_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where payment_return2 = '" . $payment_id. "' LIMIT 1");	
	$order_result =tep_db_fetch_array($order_query);
	$this_order_id = $order_result['orders_id'];

    if (empty($this_order_id)){
	//echo ('  Order ID not found  ');
	http_response_code(500);
	exit();}
	
	//get current order status
	$order_status=tep_db_query("select orders_status from orders where orders_id='".$this_order_id."'");
	$order_result= tep_db_fetch_array($order_status);
	$status_orig = $order_result['orders_status'];				

    $delivered = '';


if ( $result['status'] = 'succeeded'){
	$message = MODULE_PAYMENT_STRIPESCA_WEBHOOK_SUCCESS;
	$status  = MODULE_PAYMENT_STRIPESCA_COMP_ORDER_STATUS_ID;
	$delivered = 'yes';
}


	//update order changing status if completed
	
	if ($status != $status_orig){
		
	    $sql_data_array = array('orders_status' => $status);

        tep_db_perform(TABLE_ORDERS,$sql_data_array,'update','orders_id='.$this_order_id);
	
		// update products_id

	tep_db_query("update orders_products set orders_products_status = ". $status . " where orders_id = " . $this_order_id);
	
	}else{
		
		$status=$status_orig;
	}
	
	
	$sql_data_array = array('orders_id' => $this_order_id,
	'orders_status_id' => $status,
	'date_added' => date('Y-m-d H:i:s',getServerDate(false)),
	'customer_notified' => 1,
	 'comments' => $message);
	tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
	


//send order emails 
					//(1) run through the order products list and compile $ order_is printable
					$order_is_printable = 0;
					for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {								
		                 $order_is_printable = $order_is_printable + $order->products[$i]['is_printable'];
					}
					//(2) if printable setup ticket and generate filename
					$filename="";
							
					//if($order_is_printable > 0 && EMAIL_PDF_DELIVERED_ONLY=='true' && E_TICKETS == 'true' && $status == E_TICKET_STATUS ){ 
					if( EMAIL_PDF_DELIVERED_ONLY=='true' && E_TICKETS == 'true' && $status == E_TICKET_STATUS ){
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
	 
	   
	 
 }
?>
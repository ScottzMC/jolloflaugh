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

// file to handle the customer returning from Stripe after payment etc etc

// Set flag that this is a parent file
	define( '_FEXEC', 1 );

  require('includes/application_top.php');
  
    if(isset($_GET['c_id'])  )
		{
			$stripe_customer_id = $_GET['c_id'];
		}else{
			// fail silently
			exit();
		}
// 1. No customer session   then redirect to login

if (!$FSESSION->is_registered('customer_id') ) {
					tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
				exit();
				
				}

//Now we do some extra security checks
// 2. check that there is a stripe_order_id in the session

      if (!isset($_SESSION['stripe_order_id'])) {
        			tep_redirect(tep_href_link('index.php', '', 'SSL'));
				exit();
      }


// grab it and unset the session this stops customers refreshing the page 
// casuing multiple restocking

         $this_order_id = $_SESSION['stripe_order_id'];

         unset($_SESSION['stripe_order_id']);
		 
// 3. paranoia check

       if ($stripe_customer_id  != $FSESSION->get('customer_id') ) {
       				tep_redirect(tep_href_link('index.php', '', 'SSL'));
				exit();
      }
	  
//4. stripe session id?
            if (!isset($_SESSION['stripe_id'])) {
        			tep_redirect(tep_href_link('index.php', '', 'SSL'));
				exit();
              }

require_once('./includes/modules/payment/stripesca.php');
require_once(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/payment/stripesca.php');
require_once('./ext/modules/payment/stripesca/init.php');

$stripesca = new stripesca;


	\Stripe\Stripe::setApiKey($stripesca->secret);
    $result = \Stripe\Checkout\Session::retrieve($_SESSION['stripe_id'] );
    $intent = \Stripe\PaymentIntent::retrieve($result["payment_intent"]);
	


//check a few things

//get current order status
	$order_status=tep_db_query("select orders_status from orders where orders_id='".$this_order_id."'");
	$order_result= tep_db_fetch_array($order_status);
	$status_orig = $order_result['orders_status'];				



    $delivered = '';
	$message= MODULE_PAYMENT_STRIPESCA_UNCONFIRMED;

if ( $result['status'] = 'succeeded'){
	$message = MODULE_PAYMENT_STRIPESCA_SUCCESS;
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
		//$message= MODULE_PAYMENT_STRIPESCA_UNCONFIRMED;
	}
	
	
	$sql_data_array = array('orders_id' => $this_order_id,
	'orders_status_id' => $status,
	'date_added' => date('Y-m-d H:i:s',getServerDate(false)),
	'customer_notified' => 1,
	 'comments' => $message.'...');
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
			
	             tep_db_query("DELETE FROM `customers_away_basket` where `customers_id`='".(int)$FSESSION->customer_id."'");
	
	
		$FSESSION->remove('credit_covers');

		tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id='. $this_order_id . '&payment=stripesca', 'SSL'));


?>
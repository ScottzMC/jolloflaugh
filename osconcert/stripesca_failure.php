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

// file to handle the customer returning from Stripe after cancellation etc etc

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

//failure to redirect to Stripe we will have an order in the store database
//but nothing at Stripe, this next section is only tripped if we have $_GET['reason']
//there seems to be a pi_ reference available but the webhook does not get called?
//
if (isset($_GET['reason'])){
		$stripesca->repopulate_cart($this_order_id,'','payment_error=stripesca&error=' .MODULE_PAYMENT_STRIPESCA_GENERIC_ERROR, MODULE_PAYMENT_STRIPESCA_REDIRECT_ERROR);
exit();
	}

//So we have arrived here from Stripe itself - no $_GET['reason'] in the return url
//Now we double check the order status at Stripe just in case a customer manages
//to manually come to this page after making payment. In that scenario you could
//cancel a valid order.
	
	\Stripe\Stripe::setApiKey($stripesca->secret);
    $result = \Stripe\Checkout\Session::retrieve($_SESSION['stripe_id'] );
    $intent = \Stripe\PaymentIntent::retrieve($result["payment_intent"]);
	
	$intent->cancel();
 
	
	if($result['amount_received'] == 0 && $result['status'] = 'requires_payment_method' ){ 
	  //i.e. no funds received and the payment intent is unset
	  //we can assume that the order has been cancelled by the customer
	  		$stripesca->repopulate_cart($this_order_id,'','payment_error=stripesca&error=' .MODULE_PAYMENT_STRIPESCA_GENERIC_ERROR, $cancel.MODULE_PAYMENT_STRIPESCA_CANCEL_ERROR);
            exit();
	}
	
	//now a conundrum..... at this point we may want to restock the customers cart but if we have some sort of payment 
	//i.e. $result['amount_received'] != 0 and/or $result['status'] != 'requires_payment_method' )
	//it is very unclear from the current Stripe docs just how this callback page is accessed
	//leave the order 'as is'. send customer to checkout_success but tell the store owner
	$order_status=tep_db_query("select orders_status from orders where orders_id='".$this_order_id."'");
	$order_result= tep_db_fetch_array($order_status);
	$status_orig = $order_result['orders_status'];				


	
	$sql_data_array = array('orders_id' => $this_order_id,
	'orders_status_id' => $status_orig,
	'date_added' => date('Y-m-d H:i:s',getServerDate(false)),
	'customer_notified' => 1,
	 'comments' => MODULE_PAYMENT_STRIPESCA_UNCONFIRMED);
	 
	tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
	
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
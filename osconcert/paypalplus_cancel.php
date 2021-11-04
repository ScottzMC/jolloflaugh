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

//stop hack from not logged in folks
if (!$FSESSION->is_registered('customer_id')) { 
				
				exit('.'.MODULE_PAYMENT_PAYPALPLUS_DE_CANX_FAILED);
} 

//check for order_id
if (!$FSESSION->is_registered('paypalplus_de_id')) {
			
				exit('..'.MODULE_PAYMENT_PAYPALPLUS_DE_CANX_FAILED);
}
//check for token url session
if (!$FSESSION->is_registered('paypalplus_de_token')) {
			
				exit('....'.MODULE_PAYMENT_PAYPALPLUS_DE_CANX_FAILED);
}

//check for token url session
if (!$FSESSION->is_registered('access_token')) {
			
				exit('.....'.MODULE_PAYMENT_PAYPALPLUS_DE_CANX_FAILED);
}

##############################
 //check database for paymentID if not found or not matched exit
 
 $token = $_GET['token'];
 
$id_query=tep_db_query("select * from orders where payment_return2 ='".$token."'");

if (sizeof($id_query) < 1) {exit('.....'.MODULE_PAYMENT_PAYPALPLUS_DE_CANX_FAILED);}

while($id_result= tep_db_fetch_array($id_query)){
 
 //check the order_id from the previous check matches this one - if not exit 
	if ($token  != $_SESSION['paypalplus_de_token'] ){$FSESSION->remove('paypal_api_token');exit('Token error');}
	if ($id_result['orders_id'] != $_SESSION['paypalplus_de_id'] ){$FSESSION->remove('paypal_api_id');exit('Order error');} 
	tep_db_query("update orders set payment_return1 = '0', payment_return2 ='0' where payment_return2 ='".$token."'");

}

if (!strstr($_SESSION['paypalplus_de_token'],$token)){
                exit(MODULE_PAYMENT_PAYPALPLUS_DE_CANX_FAILED);
}


$this_order_id = $order_id = $_SESSION['paypalplus_de_id'];

$FSESSION->remove('paypalplus_de_id');
$FSESSION->remove('paypalplus_de_token');


repopulate_cart($this_order_id,'','','Bei PayPal vom Kunden storniert',$void);

//=================================================================================
	function repopulate_cart($order_id,$redirect_page=FILENAME_CHECKOUT_PAYMENT,$extras='', $note='',$void='')
{
    global $FSESSION, $id_result;
	
	//Try and void the PayPal payment/paypal_api	?
	//https://stackoverflow.com/questions/20750611/using-the-paypal-rest-api-how-can-i-cancel-a-payment
	//This cannot be done - instead remove token and payment ID from the database in main section of the code.

    // if we have the order_id then do stuff
    if (tep_not_null($order_id)) {
        //grab customers name from order
        $cust_query = tep_db_query("select customers_name from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
        if (tep_db_num_rows($cust_query) ) {
            $cust_query_result = tep_db_fetch_array($cust_query);
            $cust_name = $cust_query_result['customers_name'];
        }
        //change the order status
        $sql_data_array = array('orders_status' => MODULE_PAYMENT_PAYPALPLUS_DE_CANX_ORDER_STATUS_ID,
        'customers_id'  => MODULE_PAYMENT_PAYPALPLUS_DE_DUMMY_CUSTOMER_ID,
        'customers_name'=> 'PayPal+ -cancelled::'.$cust_name)
        ;
        tep_db_perform(TABLE_ORDERS, $sql_data_array, "update", "orders_id='".$order_id."'");
        
        $sql_data_array = array('orders_id' => $order_id,
        'orders_status_id' => MODULE_PAYMENT_PAYPALPLUS_DE_CANX_ORDER_STATUS_ID,
        'date_added' => date('Y-m-d H:i:s',getServerDate(false)), 
        'customer_notified' => 0,
        'comments' => $note );
        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
      
		include_once('includes/functions/ga_tickets.php');

        $order_query = tep_db_query("select products_id, products_quantity, events_type from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $order_id. "'");
        while ($order = tep_db_fetch_array($order_query)) {
            tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order['products_quantity'] . ", products_ordered = products_ordered - " . $order['products_quantity'] . ", products_status='1' where products_id = '" . (int)$order['products_id'] . "'");
			
			tep_db_query("update orders_products set orders_products_status = ". MODULE_PAYMENT_PAYPALPLUS_DE_CANX_ORDER_STATUS_ID . " where products_id = '" . (int)$order['products_id'] . "'");
			
			if (MODULE_PAYMENT_PAYPALPLUS_DE_DEBUG == 'True' && tep_not_null(MODULE_PAYMENT_PAYPALPLUS_DE_DEBUG_EMAIL)){
	             tep_mail('Debug', MODULE_PAYMENT_PAYPALPLUS_DE_DEBUG_EMAIL, 'PayPal cancel', "Order #: ".$order_id. " cancelled by customer", STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);	
			}

		if (function_exists('ga_check_process_restock')){
		  
			ga_check_process_restock((int)$order['products_id'], $order['products_quantity'], $order['events_type']);	
			}													

        }

        tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $order_id. "'");
        tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set products_quantity = '0' where orders_id = '" . $order_id . "'");
        tep_db_query("insert into " . TABLE_ORDERS_TOTAL. " (orders_id, title, text, value, class, sort_order) values ('" . $order_id . "', 'Total', '0.00', '0','ot_total', '99')");
		
		
        
         
	
	    	$FSESSION->remove('paypalplus_de_temp_id');
		
	
			if ($FSESSION->is_registered('paypalplus_de_cart')) {
				$FSESSION->set('cart',$_SESSION['paypalplus_de_cart']);
				$FSESSION->remove('paypalplus_de_cart');
		
			tep_db_query("DELETE FROM `customers_basket` where `customers_id`='".$_SESSION['customer_id']."'");
			tep_db_query("INSERT IGNORE `customers_basket` SELECT * FROM `customers_away_basket` where `customers_id`='".$_SESSION['customer_id']."'");
			tep_db_query("DELETE FROM `customers_away_basket` where `customers_id`='".$_SESSION['customer_id']."'");
		}
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT,$extras, 'SSL'));
        exit();
        return false;
    }
}
?>
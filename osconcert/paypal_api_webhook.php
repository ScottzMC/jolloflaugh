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
/* also code from
.---------------------------------------------------------------------------.
|    Software: PayPal-PPP                                                   |
|     Version: 0.2beta                                                      |
|        Date: 2016-02-09                                                   |
| Description: handle paypal-plus webhooks                                  |
|     Contact: info@andreas-guder.de                                        |
| ------------------------------------------------------------------------- |
|      Author: Andreas Guder                                                |
|     Contact: info@andreas-guder.de                                        |
| Copyright (c) 2015, Andreas Guder.                                        |
| ------------------------------------------------------------------------- |
|     License:  GNU Public License V2.0                                     |
|               http://www.gnu.org/licenses/gpl-2.0.html                    |
'--------------------------------------------------------------------------ö'
*/

// Set flag that this is a parent file
define('_FEXEC', 1);

require ('./includes/application_top.php');
include_once ('./includes/functions/sessions.php');
$postdata = file_get_contents("php://input");

if (empty($postdata))
{
    header("HTTP/1.0 400 Bad Request");
    echo 'No Content';
    exit;
}
$body = json_decode($postdata);


///// test
$body_array = json_decode($postdata, true);


if (!is_object($body))
{
    header("HTTP/1.0 500 Internal Server Error");
    echo 'JSON-Object Required';
    exit;
}

if (empty($body->id))
{
    header("HTTP/1.0 510 IDs required");
    header("Content-Length: 0");
    header("Connection: Close");
    exit;
}
//work only on sale completed or payment created
if ($body->event_type == 'PAYMENT.SALE.COMPLETED' || $body->event_type == 'PAYMENTS.PAYMENT.CREATED')
{

    if (!empty($body->id))
    {
        $parent_payment = $body->id;
    }else{
		header("HTTP/1.0 510 More IDs required");
		header("Content-Length: 0");
		header("Connection: Close");
    exit;		
	}
}
else
{   //end here for non applicable events
    header("HTTP/1.0 200 OK");
    header("Content-Length: 0");
    header("Connection: Close");
    exit();

}

// to get this far we have appear to have a genuine callback, we do not trust this as it may still be be a spoof
// we therefor use the incoming data, catch only the parent_payment Id and make our own call to PayPal


define('API_CREDENTIALS', MODULE_PAYMENT_PAYPAL_API_ID . ':' . MODULE_PAYMENT_PAYPAL_API_SECRET);

$data_url = trim(MODULE_PAYMENT_PAYPAL_API_ADMIN) . '/logs/data_live.json';
$ppurl = 'api.paypal.com';

if (MODULE_PAYMENT_PAYPAL_API_TEST_MODE == 'True')
{
    $data_url = trim(MODULE_PAYMENT_PAYPAL_API_ADMIN) . '/logs/data_test.json';
    $ppurl = 'api.sandbox.paypal.com';
}

include ('includes/classes/payment.php');

$payment = new payment('paypal_api');

$payment->after_process();

$paypal_api = new paypal_api;

//Get Access Token belt and braces
if (empty($_SESSION['access_token']))
{
    # check for valid access token
    $ts_now = time();
    $jsonDATA = (array)json_decode(file_get_contents($this->data_url, true));
    if (!empty($jsonDATA))
    {
        $_SESSION['expiry'] = $jsonDATA['expiry'];
        $_SESSION['access_token'] = $jsonDATA['access_token'];
        $_SESSION['app_id'] = $jsonDATA['app_id'];
        $_SESSION['token_type'] = $jsonDATA['token_type'];
        $_SESSION['webprofilID'] = $jsonDATA['webprofilID'];
    }
    else
    {
        $jsonDATA['expiry'] = 0;
    }

    if ($ts_now > $jsonDATA['expiry'])
    {
        $url = 'https://' . $this->ppurl . '/v1/oauth2/token';
        $JSONrequest = 'grant_type=client_credentials';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_SSLCERT, $sslcertpath);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Accept-Language: de_DE'
        ));
        curl_setopt($ch, CURLOPT_USERPWD, API_CREDENTIALS);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $JSONrequest);

        $result = curl_exec($ch);
        $resultGetAccessToken = json_decode($result, true);

        curl_close($ch);
        $_SESSION['expiry'] = time() + $resultGetAccessToken['expires_in'];
        $_SESSION['access_token'] = $resultGetAccessToken['access_token'];
        $_SESSION['app_id'] = $resultGetAccessToken['app_id'];
        $_SESSION['token_type'] = $resultGetAccessToken['token_type'];
        $jsonSTRING = '{ "expiry":"' . $resultGetAccessToken['expires_in'] . '" , "access_token":"' . $_SESSION['access_token'] . '" , "app_id":"' . $_SESSION['app_id'] . '","token_type":"' . $_SESSION['token_type'] . '","webprofilID":"' . $_SESSION['webprofilID'] . '"}';

        if (MODULE_PAYMENT_PAYPAL_API_ADMIN_USE == 'True')
        {
            file_put_contents($this->data_url, $jsonSTRING);
        }
    }
}
		if (MODULE_PAYMENT_PAYPAL_API_DEBUG == 'True' && tep_not_null(MODULE_PAYMENT_PAYPAL_API_DEBUG_EMAIL)){
	     tep_mail('Debug', MODULE_PAYMENT_PAYPAL_API_DEBUG_EMAIL, 'PayPal Webhook '.$body->event_type, $body->event_type, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);	
	   }
/////////////////////////////////// PAYMENTS.PAYMENT.CREATED
// try and capture the payment

if ($body->event_type == 'PAYMENTS.PAYMENT.CREATED'){

$incoming_order_id = $body_array['resource']['transactions'][0]['invoice_number'];
$url = $body_array['resource']['links'][1]['href'];
$payer_id = $body_array['resource']['payer']['payer_info']['payer_id'];
$JSONrequest = '{"payer_id":"' . $payer_id . '"}';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $_SESSION['access_token']
));

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $JSONrequest);

$result = curl_exec($ch); 


//skip NSS error?
if(curl_error($ch)){

	
	 if (curl_error($ch) != "NSS: client certificate not found (nickname not specified)"){
		if (MODULE_PAYMENT_PAYPAL_API_DEBUG == 'True' && tep_not_null(MODULE_PAYMENT_PAYPAL_API_DEBUG_EMAIL)){
	     tep_mail('Debug', MODULE_PAYMENT_PAYPAL_API_DEBUG_EMAIL, 'PayPal Webhook Capture Error', curl_error($ch) . print_r($result,true), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);	
	   }
	 }
	  
	}
curl_close($ch);
$resultExecutePayment = json_decode($result, true);

if (strtoupper($resultExecutePayment['state']) == 'APPROVED')
{

    // only approved we need the state of the sale object
    if (($resultExecutePayment['transactions'][0]['related_resources'][0]['sale']['state'] == 'completed'))
    {

        // change order status
        $order_status = tep_db_query("select orders_status from orders where orders_id='" . $incoming_order_id . "'");
        $order_result = tep_db_fetch_array($order_status);
		

        if ($order_result['orders_status'] == MODULE_PAYMENT_PAYPAL_API_ORDER_STATUS_ID)//pending only do not update cancelled
        { //completed status not found
            //update the status
            $sql_data_array = array(
                'orders_status' => MODULE_PAYMENT_PAYPAL_API_COMP_ORDER_STATUS_ID,
            );
            tep_db_perform(TABLE_ORDERS, $sql_data_array, "update", "orders_id='" . $incoming_order_id . "'");
            //update status history
            $sql_data_array = array(
                'orders_id' => $incoming_order_id,
                'orders_status_id' => MODULE_PAYMENT_PAYPAL_API_COMP_ORDER_STATUS_ID,
                'date_added' => date('Y-m-d H:i:s', getServerDate(false)) ,
                'customer_notified' => '1',
                'comments' => 'Webhook notifies payment captured. Transaction completed.'
            );

            tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
			
								
					//better late than never
					
					  tep_db_query("update orders_products set orders_products_status = ". MODULE_PAYMENT_PAYPAL_API_COMP_ORDER_STATUS_ID . " where orders_id = '" . $incoming_order_id . "'");
        
		
		//send order emails 
					//(1) run through the order products list and compile $ order_is printable
					include_once('./includes/classes/order.php');
					$order = new order($incoming_order_id);
					$order_is_printable = 0;
					for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {								
		                 $order_is_printable = $order_is_printable + $order->products[$i]['is_printable'];
					}
					//(2) if printable setup ticket and generate filename
					$filename="";
							
					if($order_is_printable > 0 && EMAIL_PDF_DELIVERED_ONLY=='true' && E_TICKETS == 'true' && $status == E_TICKET_STATUS ){ 
					//if( EMAIL_PDF_DELIVERED_ONLY=='true' && E_TICKETS == 'true' && MODULE_PAYMENT_PAYPAL_API_COMP_ORDER_STATUS_ID == E_TICKET_STATUS ){
					try
					  {
						  require_once('includes/functions/products_ticket.php');
						  $filename= (create_checkout_pdf($incoming_order_id));
						  }
						  
					 //catch exception
					   catch(Exception $e)
						  {
						   // empty catch statement  - this will allow email to still go if the require once fails
						
						 }	
					 //set ticket printed
		                   tep_db_query("update  " . TABLE_ORDERS . "  set ticket_printed='Y' where orders_id in(" . $incoming_order_id . ")");
						   
						     }
		
		        try{
			
				$email_sent=tep_db_query("select * from email_data where order_id='".$incoming_order_id."'");
				while($email_result= tep_db_fetch_array($email_sent)){			
					tep_send_default_email("PRD",unserialize(base64_decode($email_result['merge_data'])),unserialize(base64_decode($email_result['send_data'])),$filename);
				}
				}
				catch(Exception $e)
				{//empty catch in case webhook has beaten this file to it
				}

				tep_db_query("delete from email_data where order_id='".$incoming_order_id."'");

}//end pending only
    elseif($order_result['orders_status'] == MODULE_PAYMENT_PAYPAL_API_COMP_ORDER_STATUS_ID){//completed do nothing
	}
	else
    {
        { //completed status not found 	at PayPal so note the order
            //update status history
            $sql_data_array = array(
                'orders_id' => $incoming_order_id,
                'orders_status_id' => $order_result['orders_status'],
                'date_added' => date('Y-m-d H:i:s', getServerDate(false)) ,
                'customer_notified' => '0',
                'comments' => 'Webhook attempts capture. Incompatible order status. Customer may have used browser back button.'
            );

            tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
			tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'PayPal Webhook Order # '.$incoming_order_id, 'Possible error on this order. Webhook has attempted to change order status to DELIVERED from any status other than PENDING', STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);	
        }

    }

}
    else
    {
        $order_status = tep_db_query("select orders_status from orders where orders_id='" . $incoming_order_id . "'");
        $order_result = tep_db_fetch_array($order_status);

        { //completed status not found 	at PayPal so note the order
            //update status history
            $sql_data_array = array(
                'orders_id' => $incoming_order_id,
                'orders_status_id' => $order_result['orders_status'],
                'date_added' => date('Y-m-d H:i:s', getServerDate(false)) ,
                'customer_notified' => '0',
                'comments' => 'Webhook attempts capture. Result shown as: ' . $resultExecutePayment['transactions'][0]['related_resources'][0]['sale']['state']
            );

            tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
        }

    }

  }
		header("HTTP/1.0 200 OK");
		header("Content-Length: 0");
		header("Connection: Close");
		exit();
}
    /////////////////////////////////// PAYMENT.SALE.COMPLETED
if ($body->event_type == 'PAYMENT.SALE.COMPLETED' ){

    $url = 'https://' . $ppurl . '/v1/payments/sale/' . $parent_payment;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    //curl_setopt($ch, CURLOPT_SSLCERT, $sslcertpath);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $_SESSION['access_token']
    ));

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    //curl_setopt($ch, CURLOPT_HEADER, true);
    

    $resultExecutePayment = json_decode(curl_exec($ch) , true);

    if (curl_error($ch))
    {
        $resultExecutePayment = curl_error($ch);
    }
    curl_close($ch);

    if (strtoupper($resultExecutePayment['state']) != 'APPROVED')
    {
        exit();
    }
    else
    {

        $this_order_id = $resultExecutePayment['transactions'][0]['invoice_number'];

        $order_status = tep_db_query("select orders_status from orders where orders_id='" . $this_order_id . "'");
        $order_result = tep_db_fetch_array($order_status);

        if ($order_result['orders_status'] != MODULE_PAYMENT_PAYPAL_API_COMP_ORDER_STATUS_ID)
        { //completed status not found
            //update the status
            $sql_data_array = array(
                'orders_status' => MODULE_PAYMENT_PAYPAL_API_COMP_ORDER_STATUS_ID,
            );
            tep_db_perform(TABLE_ORDERS, $sql_data_array, "update", "orders_id='" . $this_order_id . "'");

            //check if email sent - if data in the table then use it, if not then nada
            $customer_notify = 0;
            if (table_exists('email_data'))
            {
                $customer_notify = 1;
                $email_sent = tep_db_query("select * from email_data where order_id='" . $this_order_id . "'");
                while ($email_result = tep_db_fetch_array($email_sent))
                {

                    tep_send_default_email("PRD", unserialize(base64_decode($email_result['merge_data'])) , unserialize(base64_decode($email_result['send_data'])));
                }
                tep_db_query("delete from email_data where order_id='" . $this_order_id . "'");
            }
            //update status history
            $sql_data_array = array(
                'orders_id' => $this_order_id,
                'orders_status_id' => MODULE_PAYMENT_PAYPAL_API_COMP_ORDER_STATUS_ID,
                'date_added' => date('Y-m-d H:i:s', getServerDate(false)) ,
                'customer_notified' => $customer_notify,
                'comments' => 'Payment Confirmed by PayPal.'
            );

            tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
        }
	}
	    header("HTTP/1.0 200 OK");
        header("Content-Length: 0");
        header("Connection: Close");
        exit();
}

    

    
/*         header("HTTP/1.0 500 Internal Server Error");
        header("Content-Length: 0");
        header("Connection: Close");
        exit;
     */

    function table_exists($tablename, $database = false)
    {
        $CheckTable = tep_db_query("SHOW TABLES LIKE '" . $tablename . "'");
        if (tep_db_num_rows($CheckTable) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }

    }
?>

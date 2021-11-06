<?php
/*
  $Id: edit_orders.php v5.0.5 08/27/2007 djmonkey1 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License http://www.gnu.org/licenses/
  
    Order Editor is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
  
  For Order Editor support or to post bug reports, feature requests, etc, please visit the Order Editor support thread:
  http://forums.oscommerce.com/index.php?showtopic=54032
  
  The original Order Editor contribution was written by Jonathan Hilgeman of SiteCreative.com
  
  Much of Order Editor 5.x is based on the order editing file found within the MOECTOE Suite Public Betas written by Josh DeChant
  
  Many, many people have contributed to Order Editor in many, many ways.  Thanks go to all- it is truly a community project.  
  
*/
// Set flag that this is a parent file
//ini_set('display_errors',1);
define('_FEXEC', 1);
  require('includes/application_top.php');

  // include the appropriate functions & classes
  include('order_editor/functions.php');
  include('order_editor/cart.php');
  include('order_editor/order.php');
  include('order_editor/shipping.php');
  include('order_editor/http_client.php');
  //New code Aug 2013 - correct missing cart session
  // create the cart session
 $cart=&$FSESSION->getobject("cart");
 if (!is_object($cart)){
  $cart=new manualCart;
  $FSESSION->set('cart',$cart);
 }
  $cart=&$FSESSION->getobject("cart");
 // end new code Aug 2013


  // Include currencies class
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

 
 //orders status
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("SELECT orders_status_id, orders_status_name 
                                       FROM " . TABLE_ORDERS_STATUS . " 
									   WHERE language_id = '" . (int)$FSESSION->languages_id  . "'");
									   
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    
	$orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }
  //get referrer and hold in temp session
  
 if (!$FSESSION->is_registered('the_referrer')) 
 {
		 $FSESSION->set('the_referrer',$_SERVER['HTTP_REFERER']); 
 }
  // Added By R
  $today = date("Y-m-d H:i:s"); 
 if($_POST['actval'] == 'updateordsta')
 {
     $ordid = trim($_POST['ordid'],",");
     if($ordid != '')
     {
       // echo  "UPDATE " . TABLE_ORDERS . " SET orders_status = 3,last_modified = now() WHERE orders_id IN (" . $ordid . ")";
     tep_db_query("UPDATE " . TABLE_ORDERS . " SET 
					  orders_status = 3, 
                      last_modified = '".$today."' 
                      WHERE orders_id IN (" . $ordid . ")");
 //Graeme need to also update the orders_products table
 		tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set orders_products_status= 3 where orders_id IN (" . $ordid . ")");
		tep_db_query("update " . TABLE_ORDERS_STATUS_HISTORY . " set orders_status_id= 3 where orders_id IN (" . $ordid . ")"); 
// email would be here
//the following code copied in from further down this page
//ideally should be ported out to a function()
########################## email start ##################

//we have a comma delimited string $ordid

$array = explode(',', $ordid);
foreach ($array as $oID)###################
{
						  	
  $check = '';

  $order_status_query = tep_db_query("select reference_id from " . TABLE_ORDERS . " where orders_id = '" . $oID . "'");
  $order_status = tep_db_fetch_array($order_status_query); 	
  if(tep_not_null($order_status['reference_id'])){

	 $check = md5($order_status['reference_id']);

			} else {
				
      break;}
//extract data from the post
//set POST variables
$fields_string ='';
$url = HTTP_CATALOG_SERVER.DIR_WS_CATALOG.'order_email_send.php'; 
$fields = array(
	'order_id' => urlencode($oID),
	'check' => $check
);

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
			
//execute post
$result = curl_exec($ch);
                    

//close connection
curl_close($ch);
		
$today = date("Y-m-d H:i:s"); 		
			
				tep_db_query("INSERT into " . TABLE_ORDERS_STATUS_HISTORY . " 
			(orders_id, orders_status_id, date_added, customer_notified, comments) 
			values ('" . tep_db_input($oID) . "', 
				'3', 
				'".$today."', 
				'1', 
				'Order email sent')");	

			}			  
########################## email finish #################
     }
     $result = "Sucess";
     echo $result;
     die;
 }
 //

  $action = (isset($_GET['action']) ? $_GET['action'] : 'edit');

  if (isset($action)) {
    switch ($action) {
       
    ////
    // Update Order
      case 'update_order':
	  
	   ### season tickets 

          
        $oID = tep_db_prepare_input($_GET['oID']);
        $status = tep_db_prepare_input($_POST['status']);
		
			   ### season tickets 
	  try {
	    if(function_exists('update_season_queue')){
			update_season_queue((int)$oID, $status );			
			}
		} catch (Exception $e) {
			unset($e);
		}
        
        // Set this Session's variables
        if (isset($_POST['billing_same_as_customer'])) $_SESSION['billing_same_as_customer'] = $_POST['billing_same_as_customer'];
        if (isset($_POST['shipping_same_as_billing'])) $_SESSION['shipping_same_as_billing'] = $_POST['shipping_same_as_billing'];
		
        // Update Order Info  
		//figure out the new currency value
		$currency_value_query = tep_db_query("SELECT value 
		                                      FROM " . TABLE_CURRENCIES . " 
											  WHERE code = '" . $_POST['update_info_payment_currency'] . "'");
		$currency_value = tep_db_fetch_array($currency_value_query);

		//figure out the country, state
		$update_customer_state = tep_get_zone_name($_POST['update_customer_country_id'], $_POST['update_customer_zone_id'], $_POST['update_customer_state']);
        $update_customer_country = tep_get_country_name($_POST['update_customer_country_id']);
        $update_billing_state = tep_get_zone_name($_POST['update_billing_country_id'], $_POST['update_billing_zone_id'], $_POST['update_billing_state']);
        $update_billing_country = tep_get_country_name($_POST['update_billing_country_id']);
        $update_delivery_state = tep_get_zone_name($_POST['update_delivery_country_id'], $_POST['update_delivery_zone_id'], $_POST['update_delivery_state']);
        $update_delivery_country = tep_get_country_name($_POST['update_delivery_country_id']);
		
        $sql_data_array = array(
		'customers_name' => tep_db_input(tep_db_prepare_input($_POST['update_customer_name'])),
        'customers_company' => tep_db_input(tep_db_prepare_input($_POST['update_customer_company'])),
        'customers_street_address' => tep_db_input(tep_db_prepare_input($_POST['update_customer_street_address'])),
        'customers_suburb' => tep_db_input(tep_db_prepare_input($_POST['update_customer_suburb'])),
        'customers_city' => tep_db_input(tep_db_prepare_input($_POST['update_customer_city'])),
        'customers_state' => tep_db_input(tep_db_prepare_input($update_customer_state)),
        'customers_postcode' => tep_db_input(tep_db_prepare_input($_POST['update_customer_postcode'])),
        'customers_country' => tep_db_input(tep_db_prepare_input($update_customer_country)),
        'customers_telephone' => tep_db_input(tep_db_prepare_input($_POST['update_customer_telephone'])),
        'customers_email_address' => tep_db_input(tep_db_prepare_input($_POST['update_customer_email_address'])),
                                
		'billing_name' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_name'] : $_POST['update_billing_name']))),
        'billing_company' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_company'] : $_POST['update_billing_company']))),
		 'billing_email' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_email'] : $_POST['update_billing_email']))),
        'billing_street_address' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_street_address'] : $_POST['update_billing_street_address']))),
        'billing_suburb' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_suburb'] : $_POST['update_billing_suburb']))),
        'billing_city' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_city'] : $_POST['update_billing_city']))),
        'billing_state' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $update_customer_state : $update_billing_state))),
        'billing_postcode' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_postcode'] : $_POST['update_billing_postcode']))),
        'billing_country' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $update_customer_country : $update_billing_country))),
								
								
	'delivery_name' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_name'] : $_POST['update_billing_name']) : $_POST['update_delivery_name']))),
    'delivery_company' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_company'] : $_POST['update_billing_company']) : $_POST['update_delivery_company']))),
	'delivery_email' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_email'] : $_POST['update_billing_email']) : $_POST['update_delivery_email']))),
    'delivery_street_address' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_street_address'] : $_POST['update_billing_street_address']) : $_POST['update_delivery_street_address']))),
    'delivery_suburb' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_suburb'] : $_POST['update_billing_suburb']) : $_POST['update_delivery_suburb']))),
    'delivery_city' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_city'] : $_POST['update_billing_city']) : $_POST['update_delivery_city']))),
    'delivery_state' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $update_customer_state : $update_billing_state) : $update_delivery_state))),
    'delivery_postcode' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_postcode'] : $_POST['update_billing_postcode']) : $_POST['update_delivery_postcode']))),
    'delivery_country' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $update_customer_country : $update_billing_country) : $update_delivery_country))),
                                
	'payment_method' => tep_db_input(tep_db_prepare_input($_POST['update_info_payment_method'])),
    'currency' => tep_db_input(tep_db_prepare_input($_POST['update_info_payment_currency'])),
    'currency_value' => tep_db_input(tep_db_prepare_input($currency_value['value'])),
    'cc_type' => tep_db_prepare_input($_POST['update_info_cc_type']),
    'cc_owner' => tep_db_prepare_input($_POST['update_info_cc_owner']),
	'cc_number' => tep_db_input(tep_db_prepare_input($_POST['update_info_cc_number'])),
    'cc_expires' => tep_db_prepare_input($_POST['update_info_cc_expires']),
    'last_modified' => date('Y-m-d H:i:s',getServerDate(false))
	);

        tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = \'' . tep_db_input($oID) . '\'');
        $order_updated = true;
        
    
	// UPDATE STATUS HISTORY & SEND EMAIL TO CUSTOMER IF NECESSARY #####

    $check_status_query = tep_db_query("
	                      SELECT customers_name, shipping_date,ticket_printed, customers_email_address, billing_email, billing_name, customers_language, orders_status, date_purchased,customers_country 
	                      FROM " . TABLE_ORDERS . " 
						  WHERE orders_id = '" . (int)$oID . "'");
						  
    $check_status = tep_db_fetch_array($check_status_query); 
	//get customer language for the raw query below
	$customers_language=$check_status['customers_language'];

	
	//print_r($_REQUEST);
        //echo $check_status['orders_status']." != ".$_POST['status'];
	//echo "Test";die;
	
	date_default_timezone_set(STORE_TIMEZONE);
	$today = date("Y-m-d H:i:s"); 
	if (($check_status['orders_status'] != $_POST['status']) || (tep_not_null($_POST['comments']))) 
	{
	
        tep_db_query("UPDATE " . TABLE_ORDERS . " SET 
					  orders_status = '" . tep_db_input($_POST['status']) . "', 
                      last_modified = '".$today."'
                      WHERE orders_id = '" . (int)$oID . "'");
 //Graeme need to also update the orders_products table
 		tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set orders_products_status= '" . tep_db_input($_POST['status']) . "' where orders_id = '" . (int)$oID. "'"); 
		
		
		
//  Graeme 2015 - codereader code should be invoked here at some point.
//  If admin fails to check the notify customer option then the section below // Notify Customer will not
//   be used so we'll make the codereader code work outside of that section - OK customer may then get two one codereader and the other normal
//   emails but that is better than none at all!

// Now include a file to invoke codereader - this file will be a copy of the /catalog/ folder version but that one
// uses a variable called $order - that variable is in use within this file already so we need to edit the codereader file
// to deal with that
	if (CR_ACTIVE=="True" && file_exists("includes/admin_cr_dbadd.php") && $_POST['status']=="3"){
		include_once("includes/admin_cr_dbadd.php");
		 }
		//print_r($_POST);die;
		 // Notify Customer ?
			$customer_notified = '0';
			if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) 
			{
                            //echo "III";
			  $notify_comments = '';
			  if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) 
			  {
			    $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $_POST['comments']) . "\n\n";
			  }		

			  //Send text email
			$email =  STORE_NAME . "\n" .
			EMAIL_SEPARATOR . "\n" . 
			EMAIL_TEXT_ORDER_NUMBER . ' ' . (int)$oID . "\n" . 
			EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n\n" .
			EMAIL_TEXT_STATUS_UPDATE ."\n" .
			EMAIL_TEXT_STATUS_COMMENT .$_POST['comments']."\n\n".
			sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]) . "\n".
			EMAIL_TEXT_STATUS_UPDATE . "\n" .
			EMAIL_TEXT_INVOICE_URL . '\n' .
			tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . (int)$oID, 'SSL') . "\n\n\n" .
			EMAIL_TEXT_STATUS_UPDATE2;
                        //echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/' .COMPANY_LOGO;
                        //echo $email."I am here";
			##################################################################################################	
			
			
			$orders_query_raw = tep_db_query("select o.orders_id, o.payment_return1, c.customers_firstname, c.customers_lastname,c.customers_username,c.customers_email_address, c.guest_account, o.payment_return2, o.reference_id, o.orders_status,op.products_quantity,concat(LTRIM(c.customers_lastname),' ',LTRIM(c.customers_firstname)) as customers_name, o.payment_method, o.date_purchased, o.billing_name, o.last_modified, o.currency, o.currency_value, s.orders_status_name, op.products_type, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s, " .TABLE_ORDERS_PRODUCTS." op, ".TABLE_CUSTOMERS ." c where  o.customers_id=c.customers_id and o.orders_status = s.orders_status_id and o.orders_id=op.orders_id and s.language_id = '" . (int)$customers_language . "' and ot.class = 'ot_total' and o.orders_id='".$oID ."' order by o.orders_id DESC,customers_name ASC");
			if (tep_db_num_rows($orders_query_raw)>0)
			{
			$order_result=tep_db_fetch_array($orders_query_raw);
			}
			
			$merge_details[TEXT_FB]=EMAIL_TEXT_DATE_ORDERED . ' ' . $order_result['date_purchased'];
			$merge_details[TEXT_FN]=$order_result['customers_firstname'];
			$merge_details[TEXT_LN]=$order_result['customers_lastname'];
			$merge_details[TEXT_AU]=$order_result['customers_username'];
			$merge_details[TEXT_LE]=$order_result['customers_email_address'];
			$merge_details[TEXT_BE]=$order_result['billing_email'];
			$merge_details[TEXT_LP]="--SECRET--";
			
			define("MAIL_TEXT_1",'Message_1');
			define("MAIL_TEXT_2",'Message_2');
			define("MAIL_TEXT_NS",'Text_New_Status');
			define("MAIL_TEXT_TC",'Text_Comments');
			define("MAIL_TEXT_ON",'Text_Order_Number');
			define("MAIL_TEXT_PD",'Text_Payment_Date');
			define("MAIL_TEXT_DP",'Text_Date_Purchased');
			define("MAIL_TEXT_AC",'Text_Account');
			define("MAIL_TEXT_PW",'Text_Password');
			define("MAIL_TEXT_DO",'Date_Ordered');
			
			//find the customers language directory to send from the lanaguage file templates.php
			$customers_language_id=$customers_language;
			$check_language_query = tep_db_query("SELECT directory from languages WHERE languages_id = '" . (int)$customers_language_id . "'");
			$check_language = tep_db_fetch_array($check_language_query);
			$customers_language_directory=$check_language['directory'];
			include(DIR_WS_LANGUAGES . $customers_language_directory . '/templates.php');
			
			$merge_details[MAIL_TEXT_1]=TEXT_OSU_MESSAGE1;
			$merge_details[MAIL_TEXT_2]=TEXT_OSU_MESSAGE2;
			$merge_details[MAIL_TEXT_NS]=TEXT_NEW_STATUS;
			$merge_details[MAIL_TEXT_TC]=TEXT_COMMENTS;
			$merge_details[MAIL_TEXT_ON]=TEXT_MAIL_ORDER_NUMBER;
			$merge_details[MAIL_TEXT_PD]=TEXT_PAYMENT_DATE;
			$merge_details[MAIL_TEXT_DP]=TEXT_DATE_PURCHASED;
			$merge_details[MAIL_TEXT_AC]=TEXT_ACCOUNT;
			$merge_details[MAIL_TEXT_PW]=TEXT_PASSWORD;
		
	######################################################################################################################		
						
			// Change RME OSU
			define("ORD_OSU","Order_Status_Update");
			define("ORD_CUS","Customers_Name");
			$order_status_update=$orders_status_array[$status];
			$merge_details[ORD_OSU]=$order_status_update;
			$merge_details[ORDR_NO]=(int)$oID;
			
			$merge_details[ORD_CUS]=$check_status['customers_name'];
			$merge_details[ORDR_OP]=tep_date_long($check_status['date_purchased']);//EMAIL_TEXT_DATE_ORDERED;
			
			if($order_result['guest_account']==1){
				$merge_details[ORDR_OL]="";
			}else{
				$merge_details[ORDR_OL]=tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . (int)$oID, 'SSL');
			}
			
			//EMAIL_TEXT_INVOICE_URL;
			$merge_details[ORDR_OM]=$_POST['comments'];//EMAIL_TEXT_STATUS_COMMENT;
			$merge_details[TEXT_SM]=STORE_NAME;
			$merge_details[TEXT_SN]=STORE_OWNER;
			$merge_details[TEXT_SE]=STORE_OWNER_EMAIL_ADDRESS;
			//$merge_details['Store_Logo']='<img src="' . tep_href_link(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/' .COMPANY_LOGO) . '" title="' . STORE_NAME . '">';
                        $url_home=(ENABLE_SSL == 'true'?HTTPS_SERVER . DIR_WS_CATALOG:HTTP_SERVER . DIR_WS_CATALOG);
                        $merge_details['Store_Logo'] = '<img src="' . $url_home . 'templates/' . DEFAULT_TEMPLATE . '/images/' .COMPANY_LOGO.'" title="' . STORE_NAME . '">';
                       //stop the old email template
                        //$merge_details["Store_Logo"]=tep_image(DIR_WS_TEMPLATES.DEFAULT_TEMPLATE.'/'.DIR_WS_IMAGES . COMPANY_LOGO, STORE_NAME,'','');
                       
			$send_details[0]['to_name'] = $check_status['customers_name'];
			$send_details[0]['to_email'] =  $check_status['customers_email_address'];
			$send_details[0]['from_name']=STORE_OWNER;
			$send_details[0]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
			if($check_status['customers_country']=="Box Office"){
			$send_details[1]['to_name'] = $check_status['billing_name'];
			$send_details[1]['to_email'] =  $check_status['billing_email'];
			$send_details[1]['from_name']=STORE_OWNER;
			$send_details[1]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
			}
			tep_send_default_email("OSU",$merge_details,$send_details);    		
	//die;

//END SEND HTML MAIL//			  
//echo $email;
//die;
			  
			  //tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			  
			  $customer_notified = '1';
			}			  
          		$today = date("Y-m-d H:i:s"); 
			tep_db_query("INSERT into " . TABLE_ORDERS_STATUS_HISTORY . " 
			(orders_id, orders_status_id, date_added, customer_notified, comments) 
			values ('" . tep_db_input($_GET['oID']) . "', 
				'" . tep_db_input($_POST['status']) . "', 
				'".$today."', 
				" . tep_db_input($customer_notified) . ", 
				'" . tep_db_input(tep_db_prepare_input($_POST['comments']))  . "')");
			
			}
			
		//set a delivery date	
		if (isset($_POST['delivery_date']) && ($_POST['delivery_date'] == 'on')) {
			tep_db_query("UPDATE " . TABLE_ORDERS . " SET 
                      shipping_date = now() 
                      WHERE orders_id = '" . (int)$oID . "'");
		}
        
        // Update Products
        if (is_array($_POST['update_products'])) {
		$product_comments = '';
          foreach($_POST['update_products'] as $orders_products_id => $products_details) {
		  
		  	//  Update Inventory Quantity
			$order_query = tep_db_query("
			SELECT products_id, products_quantity , products_type, support_packs_type
			FROM " . TABLE_ORDERS_PRODUCTS . " 
			WHERE orders_id = '" . (int)$oID . "'
			AND orders_products_id = '" . (int)$orders_products_id . "'");
			$order_products = tep_db_fetch_array($order_query);
			// First we do a stock check 
			
			if ($products_details['qty'] != $order_products['products_quantity']){
				
				
				
			$quantity_difference = ($products_details['qty'] - $order_products['products_quantity']);
				//if (STOCK_LIMITED == 'true'){
					
					if ($order_products['support_packs_type']=='F')
						{
						$family_ticket=$quantity_difference*FAMILY_TICKET_QTY;
						}else{
						$family_ticket=$quantity_difference;
						}
					
					tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_title_1='test4',
					products_quantity = products_quantity - " . $family_ticket . ",
					products_ordered = products_ordered + " . $quantity_difference . " 
					WHERE products_id = '" . (int)$order_products['products_id'] . "'");
				//2018 this next function is INCREASING GA so make the quantity NEGATIVE
					//ga_update((int)$order_products['products_id'],$quantity_difference, $order_products['support_packs_type']);
					ga_update((int)$order_products['products_id'],($quantity_difference * -1), $order_products['support_packs_type']);
				//}
				$product_comments .= " ". $products_details['model'] . " ". $products_details['name'] . " quantity changed from: ". $order_products['products_quantity'] ." to ".$products_details['qty'];
			}

		 
		   if ( (isset($products_details['delete'])) && ($products_details['delete'] == 'on') )
		   {
		     //check first to see if product should be deleted
			$product_comments .= " " . $products_details['qty']. " x " . $products_details['model'] . " ". $products_details['name'] . " deleted from order ";
		   			 //update quantities first
			    if (STOCK_LIMITED == 'true')
				{
					
						if ($products_details['events_type']=='F')
						{
						$family_ticket=$products_details["qty"]*FAMILY_TICKET_QTY;
						}else{
						$family_ticket=$products_details["qty"];
						}
							   
							tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_status='1',products_title_1='test',
							products_quantity = products_quantity + " . $family_ticket . ",
							products_ordered = products_ordered - " . $products_details["qty"] . " 
							WHERE products_id = '" . (int)$order_products['products_id'] . "'");
					
					clear_ticket_tables((int)$order_products['products_id'], (int)$oID );
					
					ga_update((int)$order_products['products_id'], $products_details["qty"], $order_products['support_packs_type']);

				} else {
					tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET products_status='1',
					products_ordered = products_ordered - " . $products_details["qty"] . "
					WHERE products_id = '" . (int)$order_products['products_id'] . "'");
				}
		   
                    tep_db_query("DELETE FROM " . TABLE_ORDERS_PRODUCTS . "  
	                              WHERE orders_id = '" . (int)$oID . "'
					              AND orders_products_id = '" . (int)$orders_products_id . "'");
      
	                
					tep_db_query("DELETE FROM " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . "
	                              WHERE orders_id = '" . (int)$oID . "'
                                  AND orders_products_id = '" . (int)$orders_products_id . "'");
           
		   } else {
		     //not deleted=> updated
		   
            // Update orders_products Table
             	$Query = "UPDATE " . TABLE_ORDERS_PRODUCTS . " SET
					products_model = '" . $products_details["model"] . "',
					products_name = '" . oe_html_quotes($products_details["name"]) . "',
					products_price = '" . $products_details["price"] . "',
					final_price = '" . $products_details["final_price"] . "',
					products_tax = '" . $products_details["tax"] . "',
					products_quantity = '" . $products_details["qty"] . "'
					WHERE orders_id = '" . (int)$oID . "'
					AND orders_products_id = '$orders_products_id';";
				tep_db_query($Query);
          


            } //end if/else product details delete= on
			
			//Graeme - we need to change product_status to '1' if quantity now > 0
          } //end foreach post update products########################
		  
		  //2018
		  if ($product_comments != ""){
		  //order_changed ( tep_db_input($_GET['oID']), tep_db_input($_POST['status']), $product_comments, $_SESSION['login_first_name'] . " " . $_SESSION['login_last_name']);
		  }
        }//end if is-array update products
		
	
	  //update any downloads that may exist
      if (is_array($_POST['update_downloads'])) {
	  foreach($_POST['update_downloads'] as $orders_products_download_id => $download_details) {
		$Query = "UPDATE " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " SET
					orders_products_filename = '" . $download_details["filename"] . "',
					download_maxdays = '" . $download_details["maxdays"] . "',
					download_count = '" . $download_details["maxcount"] . "'
					WHERE orders_id = '" . (int)$oID . "'
					AND orders_products_download_id = '$orders_products_download_id';";
					tep_db_query($Query);
			}
		}	//end downloads
		
						
				//delete or update comments
		      if (is_array($_POST['update_comments'])) {
	              foreach($_POST['update_comments'] as $orders_status_history_id => $comments_details) {
	  
	                  if (isset($comments_details['delete'])){
		
			             $Query = "DELETE FROM " . TABLE_ORDERS_STATUS_HISTORY . " 
			                              WHERE orders_id = '" . (int)$oID . "' 
			                              AND orders_status_history_id = '$orders_status_history_id';";
				                          tep_db_query($Query);
				
				        } else {

		                 $Query = "UPDATE " . TABLE_ORDERS_STATUS_HISTORY . " SET
					               comments = '" . $comments_details["comments"] . "'
					               WHERE orders_id = '" . (int)$oID . "'
					               AND orders_status_history_id = '$orders_status_history_id';";
					               tep_db_query($Query);
				        }
				    }	
				}//end comments update section

      $shipping = array();
      
      if (is_array($_POST['update_totals'])) {
        foreach($_POST['update_totals'] as $total_index => $total_details) {
          extract($total_details, EXTR_PREFIX_ALL, "ot");
          if ($ot_class == "ot_shipping") {
           
               $shipping['cost'] = $ot_value;
               $shipping['title'] = $ot_title;
               $shipping['id'] = $ot_id;
			
		  } // end if ($ot_class == "ot_shipping")
        } //end foreach
	  } //end if is_array

       if (tep_not_null($shipping['id'])) {
   tep_db_query("UPDATE " . TABLE_ORDERS . " SET shipping_module = '" . $shipping['id'] . "' WHERE orders_id = '" . (int)$oID . "'");
       }

        $order = new manualOrder($oID);
        $order->adjust_zones();

        $cart = new manualCart();
        $cart->restore_contents($oID);
        $total_count = $cart->count_contents();
        $total_weight = $cart->show_weight();

        // Get the shipping quotes- if we don't have shipping quotes shipping tax calculation can't happen
        $shipping_modules = new shipping;
        $shipping_quotes = $shipping_modules->quote();

		if (DISPLAY_PRICE_WITH_TAX == 'true') {//extract the base shipping cost or the ot_shipping module will add tax to it again
		   $module = substr($GLOBALS['shipping']['id'], 0, strpos($GLOBALS['shipping']['id'], '_'));
		   $tax = tep_get_tax_rate($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
		   $order->info['total'] -= ( $order->info['shipping_cost'] - ($order->info['shipping_cost'] / (1 + ($tax /100))) );
           $order->info['shipping_cost'] = ($order->info['shipping_cost'] / (1 + ($tax /100)));
		   }

		//this is where we call the order total modules
		require( 'order_editor/order_total.php');
		$order_total_modules = new order_total();
        $order_totals = $order_total_modules->process();  

        $current_ot_totals_array = array();
		$current_ot_titles_array = array();
        $current_ot_totals_query = tep_db_query("select class, title from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' order by sort_order");
        while ($current_ot_totals = tep_db_fetch_array($current_ot_totals_query)) {
          $current_ot_totals_array[] = $current_ot_totals['class'];
		  $current_ot_titles_array[] = $current_ot_totals['title'];
        }

		tep_db_query("DELETE FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '" . (int)$oID . "'");
		//tep_db_query("UPDATE " . TABLE_ORDERS_TOTAL . " SET orders_id = '" . (int)$oID * -1 . "'WHERE orders_id = '" . (int)$oID . "'");

        $j=1; //giving something a sort order of 0 ain't my bag baby
		$new_order_totals = array();

	    if (is_array($_POST['update_totals'])) { //1
          foreach($_POST['update_totals'] as $total_index => $total_details) { //2
            extract($total_details, EXTR_PREFIX_ALL, "ot");
            if (!strstr($ot_class, 'ot_custom')) { //3
             for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) { //4

			  if ($order_totals[$i]['code'] == 'ot_tax') { //5
			  $new_ot_total = ((in_array($order_totals[$i]['title'], $current_ot_titles_array)) ? false : true);
			  } else { //within 5
			  $new_ot_total = ((in_array($order_totals[$i]['code'], $current_ot_totals_array)) ? false : true);
			  }  //end 5 if ($order_totals[$i]['code'] == 'ot_tax')
 
			  if ( ( ($order_totals[$i]['code'] == 'ot_tax') && ($order_totals[$i]['code'] == $ot_class) && ($order_totals[$i]['title'] == $ot_title) ) || ( ($order_totals[$i]['code'] != 'ot_tax') && ($order_totals[$i]['code'] == $ot_class) ) ) { //6
			  //only good for components that show up in the $order_totals array

				if ($ot_title != '') { //7
                  $new_order_totals[] = array('title' => $ot_title,
                                              'text' => (($ot_class != 'ot_total') ? $order_totals[$i]['text'] : '<b>' . $currencies->format($order->info['total'], true, $order->info['currency'], $order->info['currency_value']) . '</b>'),
                                              'value' => (($order_totals[$i]['code'] != 'ot_total') ? $order_totals[$i]['value'] : $order->info['total']),
                                              'code' => $order_totals[$i]['code'],
                                              'sort_order' => $j);
                $written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
				$j++;
                } else { //within 7 does not trap ot_coupon??

				 // $order->info['total'] += ($ot_value*(-1)); 
				  $order->info['total'] += $ot_value; 
				  $written_ot_totals_array[] = $ot_class;
				  $written_ot_titles_array[] = $ot_title; 

                } //end 7

			  } elseif ( ($new_ot_total) && (!in_array($order_totals[$i]['title'], $current_ot_titles_array)) ) { //within 6

                $new_order_totals[] = array('title' => $order_totals[$i]['title'],
                                            'text' => $order_totals[$i]['text'],
                                            'value' => $order_totals[$i]['value'],
                                            'code' => $order_totals[$i]['code'],
                                            'sort_order' => $j);
                $current_ot_totals_array[] = $order_totals[$i]['code'];
				$current_ot_titles_array[] = $order_totals[$i]['title'];
				$written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
                $j++;
                //echo $order_totals[$i]['code'] . "<br>"; for debugging- use of this results in errors

			  } elseif ($new_ot_total) { //also within 6
                $order->info['total'] += ($order_totals[$i]['value']*(-1));
                $current_ot_totals_array[] = $order_totals[$i]['code'];
				$written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
              }//end 6
           }//end 4
         } elseif ( (tep_not_null($ot_value)) && (tep_not_null($ot_title)) ) { // this modifies if (!strstr($ot_class, 'ot_custom')) { //3
            $new_order_totals[] = array('title' => $ot_title,
                     'text' => $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']),
                                        'value' => $ot_value,
                                        'code' => 'ot_custom_' . $j,
                                        'sort_order' => $j);
            $order->info['total'] += $ot_value;
			$written_ot_totals_array[] = $ot_class;
		    $written_ot_titles_array[] = $ot_title;
            $j++;
          } //end 3
		  
		    //save ot_skippy from certain annihilation
			 if ( (!in_array($ot_class, $written_ot_totals_array)) && (!in_array($ot_title, $written_ot_titles_array)) && (tep_not_null($ot_value)) && (tep_not_null($ot_title)) && ($ot_class != 'ot_tax') && ($ot_class != 'ot_loworderfee') ) { //7
			//this is supposed to catch the oddball components that don't show up in $order_totals
				 
				    $new_order_totals[] = array(
					        'title' => $ot_title,
                            'text' => $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']),
                            'value' => $ot_value,
                            'code' => $ot_class,
                            'sort_order' => $j);
               //$current_ot_totals_array[] = $order_totals[$i]['code'];
				//$current_ot_titles_array[] = $order_totals[$i]['title'];
				$order->info['total'] += $ot_value;
				$written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
                $j++;
				 
				 } //end 7
        } //end 2
	  } else {//within 1
	  // $_POST['update_totals'] is not an array => write in all order total components that have been generated by the sundry modules
	   for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) { //8
	                  $new_order_totals[] = array('title' => $order_totals[$i]['title'],
                                            'text' => $order_totals[$i]['text'],
                                            'value' => $order_totals[$i]['value'],
                                            'code' => $order_totals[$i]['code'],
                                            'sort_order' => $j);
                $j++;
				
			} //end 8
				
		} //end if 
	  
		for ($i=0, $n=sizeof($new_order_totals); $i<$n; $i++) {
          $sql_data_array = array('orders_id' => $oID,
                                  'title' => $new_order_totals[$i]['title'],
                                  'text' => $new_order_totals[$i]['text'],
                                  'value' => $new_order_totals[$i]['value'], 
                                  'class' => $new_order_totals[$i]['code'], 
                                  'sort_order' => $new_order_totals[$i]['sort_order']);
          tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
        }
		
        
        if (isset($_POST['subaction'])) {
          switch($_POST['subaction']) {
            case 'add_product':
              tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit#products'));
              break;
              
          }
        }
        
		// 1.5 SUCCESS MESSAGE #####
		
		
	// CHECK FOR NEW EMAIL CONFIRMATION

    if ( (isset($_POST['nC1'])) || (isset($_POST['nC2'])) || (isset($_POST['nC3'])) ) {
	//then the user selected the option of sending a new email
    
    tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=email')); 
	//redirect to the email case
	 
  } else  { 
     //email? email?  We don't need no stinkin email!
	 
	 if ($order_updated)	{
			$messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
		}

		tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit'));
		
		}
		
	break;
		
	// 3. NEW ORDER EMAIL ###############################################################################################
	case 'email':
            
 require(DIR_WS_CLASSES . 'order.php');
 $insert_id = tep_db_prepare_input($_GET['oID']);
 $order = new order($insert_id );
  //$status is not set - causes problems - 
$status=($order->info['orders_status']);
// initialized for the email confirmation
//the following taken from checkout_process
		$products_ordered = '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
		$products_ordered.= '<tr height="30" class="textTableSubHead"><td width="20"><b>' . TEXT_QTY .'</b></td><td colspan="2"><b>'.TEXT_TICKET_NAME. '</b></td><td></td><td align="right" width="100"><b>' .TEXT_PRICE. '</b></td></tr>';
	$subtotal = 0;
	$total_tax = 0;
	$products_type='';
	$events_type='';
	$attributes_exist = '0';
	$products_ordered_attributes = '';
	$id=$order->products[$i]['id'];
	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) 
	{
	
			$heading_name = $order->products[$i]['categories_name'];
			$heading_venue = $order->products[$i]['concert_venue'];
			$heading_date = $order->products[$i]['concert_date'];
			$heading_time = $order->products[$i]['concert_time'];
				
		
//------insert customer choosen option eof ----
		$total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
		$total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
		$total_cost += $total_products_price;
		$flag_product=false;
		if ($order->products[$i]['products_type']=='P')
		{
			$flag_product=true;
			$family='';
			if ($order->products[$i]['support_packs_type']=='F')
			{
				$family= '<small>Family Ticket x '.FAMILY_TICKET_QTY.'</small>';
			}else{
				$family='';
			}
			
			$products_ordered .= '<tr class="textTableContent" height="56">' . 
									'<td valign="top"  width="40" class="textMain"><br>'. $order->products[$i]['qty'] .' x </td>' .
									'<td width="55" valign="top" class="textMain"><br>'. tep_product_email_image('ticket_icon.png',$order->products[$i]['name'],'style="width:50px;height:50px"').'</td>' . 
									'<td valign="top" class="textMain"><br>'. $order->products[$i]['name'].'<br>' . $products_ordered_attributes . (isset($order->products[$i]['discount_whole_text'])?'<br>' . $order->products[$i]['discount_whole_text']:'') . '</td>' .
									//cartzone hide SKU '<td valign="top" class="textMain"><small><br>' . $order->products[$i]['sku'] . '</small></td>' .
									'<td valign="top" class="textMain"><small><br>
									' . $heading_name .'<br>' . $heading_venue .' ' . $heading_date .' ' . $heading_time .' <br>'.$family.'</small></td>' .  
									'<td valign="top" align="right" class="textMain"><br>'.$currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty'],true).'&nbsp;</td>' . 
									'</tr>' .
									'<tr>' . 
									'<td colspan="5" height="1" class="textBorder">' . tep_draw_separator('pixel_trans.gif',10,1) . '</td>' . 
									'</tr>';

		}
		//if ($products_type!="M"){
			if ($products_type=='')
				$products_type=$order->products[$i]['element_type'];
			// else if ($products_type!=$order->products[$i]['element_type'])
				// $products_type="M";
		// }
		// if ($order->products[$i]['element_type']=='V'){
			// tep_send_service_order_email($i);
		// }

	}
	$products_ordered.="</table>";
        
$comments=$order->info['comments'] . $FSESSION->get('');
{
	$merge_details=array();
	$send_details=array();
	// order details
	$merge_details[ORDR_NO]=$insert_id;

	
	//$merge_details[ORDR_OP]=getServerDate(true);
	$merge_details[ORDR_OP]=strftime(DATE_FORMAT_LONG);
	$merge_details[ORDR_OM]=(($comments)?ORDER_COMMENTS . $comments:'');
	$merge_details[ORDR_PO]=$products_ordered;
	
	//Bank Transfer
	define("ORDR_BDM","Bank_Deposit_Message");//Bank Deposit Message as place holder %%Bank_Deposit_Message%%
	if($payment_modules->selected_module =='bank_transfer'){
	$merge_details[ORDR_BDM]=MODULE_BANK_TRANSFER_INFO;
	} else {
	$merge_details[ORDR_BDM]="";}
	
	//German Bank Transfer
	// define("ORDR_GBDM","German_Bank_Deposit_Message");//German Bank Deposit Message as place holder %%German_Bank_Deposit_Message%%
	// if($payment_modules->selected_module =='de_bank_transfer'){
	// $merge_details[ORDR_GBDM]=MODULE_DE_BANK_TRANSFER_INFO;
	// } else {
	// $merge_details[ORDR_GBDM]="";}
	
		//===get if any download links===============
	$downloads_query=tep_db_query("select opd.*,op.products_name from " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd," . TABLE_ORDERS_PRODUCTS . " op where opd.orders_products_id=op.orders_products_id and opd.orders_id='" . (int)$insert_id . "'");
	$download_content="<table>";
	if(tep_db_num_rows($downloads_query)>0)
		$download_content.='<tr class="textTableContent"><td class="textMain" colspan=10>' .TEXT_DOWNLOAD_LINK . '</td></tr>';
    while ($downloads = tep_db_fetch_array($downloads_query)) {
// MySQL 3.22 does not have INTERVAL
      list($dt_year, $dt_month, $dt_day) = explode('-', date('Y-m-d',strtotime(getServerDate())));
      $download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads['download_maxdays'], $dt_year);
      $download_expiry = date('Y-m-d H:i:s', $download_timestamp);
      $download_content.=' <tr class="textTableContent">';
      if ( ($downloads['download_count'] > 0) && (file_exists(DIR_FS_DOWNLOAD . $downloads['orders_products_filename'])) && ( ($downloads['download_maxdays'] == 0) || ($download_timestamp > time())) ) {
        $download_content.= '            <td class="textMain" align="center"><a href="'.tep_href_link(FILENAME_DOWNLOAD, 'id=' . $downloads['orders_products_download_id'], 'SSL', false).'" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . $downloads['products_name'] .  '</a></td>' . "\n";
      } else {
        $download_content.= '            <td class="textMain">' . $downloads['products_name'] . '</td>' . "\n";
      }
      $download_content.= '            <td class="textMain" nowrap><b>' . TABLE_HEADING_DOWNLOAD_DATE . '</b>&nbsp;' . tep_date_long($download_expiry) . '</td>' . "\n" .
           '            <td class="textMain" align="right"><b>' . $downloads['download_count'] . '</b>' .  TABLE_HEADING_DOWNLOAD_COUNT . '</td>' . "\n" .
           '          </tr>' . "\n";
    }
	$download_content.="</table>";
	$merge_details[TEXT_DL]=$download_content;
//==============================================

	$order_totals_str="<table border='0' cellpadding='2' cellspacing='0' width='100%'>";
	$order_totals=$order->totals;
	for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
		if ($order_totals[$i]['code']=='ot_total'){
			$class="textTotal";
		} else if ($i%2==0){
			$class="textTableTotalE";
		} else {
			$class="textTableTotalO";
		}
		if($order_totals[$i]['code']=='ot_tax'){
			$order_totals_str.= '<tr class="textMain" height="21"><td class="' . $class . '" align="right">' . TEXT_TAX . '</td><td class="' . $class . '" align="right" width="100">' . strip_tags($order_totals[$i]['text']) . "</td>";
		}else if($order_totals[$i]['code']=='ot_shipping'){
			$order_totals_str.= '<tr class="textMain" height="21"><td class="' . $class . '" align="right">' . TEXT_SHIPPING . '</td><td class="' . $class . '" align="right" width="100">' . strip_tags($order_totals[$i]['text']) . "</td>";
		}else if($order_totals[$i]['code']=='ot_total'){
			$order_totals_str.= '<tr class="textMain" height="21"><td class="' . $class . '" align="right">' . TEXT_TOTAL . '</td><td class="' . $class . '" align="right" width="100">' . strip_tags($order_totals[$i]['text']) . "</td>";
		}else if($order_totals[$i]['code']=='ot_subtotal'){
			$order_totals_str.= '<tr class="textMain" height="21"><td class="' . $class . '" align="right">' . TEXT_SUBTOTAL . '</td><td class="' . $class . '" align="right" width="100">' . strip_tags($order_totals[$i]['text']) . "</td>";
		} else {
			$order_totals_str.= '<tr class="textMain" height="21"><td class="' . $class . '" align="right">' . $order_totals[$i]['title'] . '</td><td class="' . $class . '" align="right" width="100">' . strip_tags($order_totals[$i]['text']) . "</td>";
		}
	}
	$order_totals_str.="</table>";
	//$merge_details['Order_Totals']=$order_totals_str;
	$merge_details[ORDR_OT]=$order_totals_str;
	//customer details
	$merge_details[CUST_CF]=$order->customer['firstname'];
	$merge_details[CUST_CL]=$order->customer['lastname'];
	$merge_details[CUST_CM]=$order->customer['company'];
	//$merge_details[CUST_BE]=$order->customer['email'];
	$merge_details[TEXT_SN]=STORE_OWNER;
	$merge_details[CUST_CT]=$order->customer['street_address'];
	$merge_details[CUST_CS]=$order->customer['suburb'];
	$merge_details[CUST_CC]=$order->customer['city'];
	$merge_details[CUST_CP]=$order->customer['postcode'];
	$merge_details[CUST_CE]=$order->customer['state'];
	$merge_details[CUST_CU]=$order->customer['country'];
	//$merge_details[CUST_CU]=($order->customer['country_id'] > 0)?(get_order_countryname($order->customer['country_id'])):$order->customer['country']['title'];
	$merge_details[CUST_CO]=$order->customer['telephone'];
	$merge_details[CUST_CA]=$order->customer['email_address'];
	
	//Billing details
	//$merge_details[BILL_NA]=$order->billing['firstname'] . ' ' . $order->billing['lastname'];
	$merge_details[BILL_NA]=$order->billing['name'];
	$merge_details[BILL_CM]=$order->billing['company'];
	//$merge_details[BILL_BE]=$order->billing['email'];
	if($order->billing['email']==''){
		$merge_details[BILL_BE]=$order->customer['email_address'];	
		}else{
		$merge_details[BILL_BE]=$order->billing['customer_email'];	
		}
	$merge_details[BILL_CT]=$order->billing['street_address'];
	$merge_details[BILL_CS]=$order->billing['suburb'];
	$merge_details[BILL_CC]=$order->billing['city'];
	$merge_details[BILL_CP]=$order->billing['postcode'];
	$merge_details[BILL_CE]=$order->billing['state'];
	$merge_details[BILL_CU]=$order->billing['country'];
	//$merge_details[BILL_CU]=($order->billing['country_id'] > 0)?(get_order_countryname($order->billing['country_id'])):$order->billing['country']['title'];

	//delivery details
	$merge_details[DELI_NA]=$order->delivery['name'];
	$merge_details[DELI_CM]=$order->delivery['company'];
	$merge_details[DELI_BE]=$order->delivery['email'];
	$merge_details[DELI_CT]=$order->delivery['street_address'];
	$merge_details[DELI_CS]=$order->delivery['suburb'];
	$merge_details[DELI_CC]=$order->delivery['city'];
	$merge_details[DELI_CP]=$order->delivery['postcode'];
	$merge_details[DELI_CE]=$order->delivery['state'];
	$merge_details[DELI_CU]=$order->delivery['country'];
	//$merge_details[DELI_CU]=($order->delivery['country_id'] > 0)?(get_order_countryname($order->delivery['country_id'])):$order->delivery['country']['title'];
	
	$customers_language_id=$order->customer['language'];
	
	$check_language_query = tep_db_query("SELECT directory from languages WHERE languages_id = '" . (int)$customers_language_id . "'");
	$check_language = tep_db_fetch_array($check_language_query);
	$customers_language_directory=$check_language['directory'];
	include(DIR_WS_LANGUAGES . $customers_language_directory . '/templates.php');
					  
    $check_billing = tep_db_fetch_array($check_billing_query); 
	
	$merge_details[ORDR_PM]=$order->info['payment_method'];
	define("ORDR_SM","Shipping_Method");
	$shipping_method=$order->info['shipping_method'];
	$merge_details[ORDR_SM]=$shipping_method;
	
	//$merge_details[ORDR_PM]='';
	$merge_details[ORDR_DD]='';	
	$merge_details[ORDR_PF]='';
	
	// $merge_details['Text_Order_Number']=TEXT_MAIL_ORDER_NUMBER;//Order Number
	// $merge_details['Text_Dear']=TEXT_DEAR;// Dear
	// $merge_details['Text_Thanks_Purchase']=TEXT_INV_THANKS_PURCHASE;//Thanks for...
	// $merge_details['Text_Delivery_Details']=TEXT_DELIVERY_DETAILS;//Delivery Details
	// $merge_details['Text_Address']=TEXT_ADDRESS;
	// $merge_details['Text_Telephone']=TEXT_TELEPHONE;
	// $merge_details['Text_Email']=TEXT_EMAIL;
	// $merge_details['Text_Payment_Details']=TEXT_PAYMENT_DETAILS;
	// $merge_details['Text_Payment Method']=TEXT_PAYMENT_METHOD;
	// $merge_details['Text_Tickets']=TEXT_TICKETS;
	// $merge_details['Text_With_Thanks']=TEXT_WITH_THANKS;
	

	$merge_details[TEXT_INV_PRICE]=EMAIL_TEXT_PRICE;
	$merge_details[TEXT_INV_QTY]=EMAIL_TEXT_QTY;
	$merge_details[TEXT_INV_ON]=TEXT_MAIL_ORDER_NUMBER;//Order Number
	$merge_details[TEXT_INV_DEAR]=TEXT_DEAR;// Dear
	$merge_details[TEXT_INV_THANKS_PURCHASE]=TEXT_THANKS_PURCHASE;//Thanks for...
	$merge_details[TEXT_INV_THANKS_PURCHASE_SENT]=TEXT_THANKS_PURCHASE_SENT;//Thanks for...PRS
	$merge_details[TEXT_INV_DD]=TEXT_DELIVERY_DETAILS;//Delivery Details
	$merge_details[TEXT_INV_ADDRESS]=TEXT_ADDRESS;
	$merge_details[TEXT_INV_TELEPHONE]=TEXT_TELEPHONE;
	$merge_details[TEXT_INV_EMAIL]=TEXT_EMAIL;
	$merge_details[TEXT_INV_PD]=TEXT_PAYMENT_DETAILS;
	$merge_details[TEXT_INV_PM]=TEXT_PAYMENT_METHOD;
	$merge_details[TEXT_INV_PRODUCTS]=TEXT_TICKETS;
	$merge_details[TEXT_INV_WITH_THANKS]=TEXT_WITH_THANKS;
	$merge_details[TEXT_IL]=TEXT_EMAIL_ORDER_COLLECT_YOUR_TICKET;
	$merge_details[MAIL_TEXT_TC]=TEXT_EMAIL_ORDER_COMMENTS;
	
	//Check if customer was a guest (PWA)
	$cust_query=tep_db_query("SELECT guest_account from " . TABLE_CUSTOMERS . " where customers_id='" . (int)$order->customer['id'] . "'");
	   $cust_result=tep_db_fetch_array($cust_query);
		   $guest=$cust_result['guest_account'];
	  
	$merge_details['Store_Link']='<a href="' . tep_catalog_href_link(FILENAME_DEFAULT) . '" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . STORE_OWNER . '</a>';
	$merge_details['Telephone']=$order->customer['telephone'];

	$url_home=(ENABLE_SSL == 'true'?HTTPS_SERVER . DIR_WS_CATALOG:HTTP_SERVER . DIR_WS_CATALOG);
//	$merge_details['Detailed_PDF_Format_Link']='<a href="' . tep_catalog_href_link(FILENAME_PDF_MAIL, 'file=pdf&order_id=' . $insert_id, 'SSL', false) . '" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . TEST_MAIL_PDF . '</a>';
	$merge_details['Store_Logo'] = '<img src="' . $url_home . 'templates/' . DEFAULT_TEMPLATE . '/images/' .COMPANY_LOGO.'" title="' . STORE_NAME . '">';

	if(!defined('TICKET_LINK_TEXT'))define('TICKET_LINK_TEXT', 'Collect your tickets here');
	// //Ticket Link for email receipt
	 if(($guest==0)&&($status==3)){ //email for PWA

				  //$merge_details[TEXT_INV_OL]=''.TICKET_LINK_TEXT.'<a href="'.tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . '" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . CLICK_HERE.'</a>';
				 
				$merge_details['Order_Link']=''.TICKET_LINK_TEXT.'<a href="'.tep_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . '" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . CLICK_HERE.'</a>';

	 }else{

				  $merge_details['Order_Link']='';

	 }
	 $oID = tep_db_prepare_input($_GET['oID']);
	 $check_billing_query = tep_db_query("
	                      SELECT customers_country, billing_email, billing_name
	                      FROM " . TABLE_ORDERS . " 
						  WHERE orders_id = '" . (int)$oID . "'");
						  
    $check_billing = tep_db_fetch_array($check_billing_query); 
	
	$send_details[0]['to_name']=$order->customer['firstname'] . ' ' . $order->customer['lastname'];
	$send_details[0]['to_email']=$order->customer['email_address'];
	$send_details[0]['from_name']=STORE_OWNER;
	$send_details[0]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
	
	if($check_billing['customers_country']=="Box Office"){
		$send_details[1]['to_name']=$check_billing['billing_name'];
		$send_details[1]['to_email']=$check_billing['billing_email'];
		$send_details[1]['from_name']=STORE_OWNER;
		$send_details[1]['from_email']=STORE_OWNER_EMAIL_ADDRESS;	
	}

		{ 
			//extra code to create pdf and attach
		
        $filename="";
			
			
		if(EMAIL_PDF_DELIVERED_ONLY=='true' && E_TICKETS == 'true' && $status == E_TICKET_STATUS ){ 
		try
		  {
		      require_once('includes/functions/products_ticket.php');
			  $filename= (DIR_FS_CATALOG.create_checkout_pdf($insert_id));
			  }
			  
		 //catch exception
           catch(Exception $e)
              {
                exit( 'Message: ' .$e->getMessage());
             }	
	       }

		//set ticket printed
		tep_db_query("update  " . TABLE_ORDERS . "  set ticket_printed='Y' where orders_id in(" . $insert_id . ")");
		//exit( $filename.$status );
		tep_send_default_email("PRS",$merge_details,$send_details,$filename);
		}
	}
//print_r($order);die;
	
	 	{
			$messageStack->add_session('Email sent', 'success');
		}

		tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit'));
		
		
		
		break;
    
    ////
    // Edit Order
      case 'edit':
        if (!isset($_GET['oID'])) {
		$messageStack->add(ERROR_NO_ORDER_SELECTED, 'error');
          break;
		  }
        $oID = tep_db_prepare_input($_GET['oID']);
        $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
        $order_exists = true;
        if (!tep_db_num_rows($orders_query)) {
        $order_exists = false;
          $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
          break;
        }
        
        $order = new manualOrder($oID);
        $shippingKey = $order->adjust_totals($oID);
        $order->adjust_zones();
        
        $cart = new manualCart();
        $cart->restore_contents($oID);
        $total_count = $cart->count_contents();
        $total_weight = $cart->show_weight();

        // Get the shipping quotes
        $shipping_modules = new shipping;
        $shipping_quotes = $shipping_modules->quote();
        break;
    }
  }

  // currecies drop-down array
  $currency_query = tep_db_query("select distinct title, code from " . TABLE_CURRENCIES . " order by code ASC");  
  $currency_array = array();
  while($currency = tep_db_fetch_array($currency_query)) {
    $currency_array[] = array('id' => $currency['code'],
                              'text' => $currency['code'] . ' - ' . $currency['title']);
  }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  
  <?php include('order_editor/css.php');  
      //because if you haven't got your css, what have you got?
      ?>

<script language="javascript" src="includes/general.js"></script>

  <?php include('order_editor/javascript.php');  
      //because if you haven't got your javascript, what have you got?
      ?>
 
</head>
<body>

<div id="dhtmltooltip"></div>

<script type="text/javascript">

/***********************************************
* Cool DHTML tooltip script- � Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

/***********************************************
* For Order Editor
* This has to stay here for the tooltips to work correctly
* I tried sticking it with the rest of the javascript, but it has to be inside the <body> tag
*
***********************************************/

var offsetxpoint=-60 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thecolor, thewidth){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e){
if (enabletip){
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20

var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth)
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
else if (curX<leftedge)
tipobj.style.left="5px"
else
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetxpoint+"px"

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight)
tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
else
tipobj.style.top=curY+offsetypoint+"px"
tipobj.style.visibility="visible"
}
}

function hideddrivetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor='white'
tipobj.style.width='200'
}
}

document.onmousemove=positiontip

</script>

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td >

    </td>
<!-- body_text //-->
    <td width="100%" valign="top">

 <?php
   
   if (($action == 'edit') && ($order_exists == true)) {
     
	 echo tep_draw_form('edit_order', FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=update_order');
    
 ?>
  
      <div id="header">
	  <?php 
			$check_status_query = tep_db_query("
			SELECT customers_name, shipping_date,ticket_printed, customers_email_address, billing_email, billing_name, customers_language, orders_status, date_purchased,customers_country 
			FROM " . TABLE_ORDERS . " 
			WHERE orders_id = '" . (int)$oID . "'");

			$check_status = tep_db_fetch_array($check_status_query); 

			$customers_language=$check_status['customers_language'];
			$check_language_query = tep_db_query("SELECT name from languages WHERE languages_id = '" . (int)$customers_language . "'");
			$check_language = tep_db_fetch_array($check_language_query);
			$customers_language_name=$check_language['name'];

			//echo '<br><h2>'.$customers_language_name.'</h2>';
	  ?>
	  
		  <p id="headerTitle" class="pageHeading">
		  <?php echo '('.$customers_language_name.') '.EDITING_ORDERS . $oID .' of '. tep_datetime_short($order->info['date_purchased']); ?></p>
		  
		  <?php	  
		  
		  // $format_date = format_date($order->info['date_purchased']);
	// $default_format = split("-",strtolower(EVENTS_DATE_FORMAT));
	// $format_date = split("-",$format_date);
	// $formated_date = array();
	// for($i=0;$i<count($default_format);$i++)$formated_date[$default_format[$i]] = $format_date[$i];
        //date_default_timezone_set('Europe/Berlin'); 
        // Set the gloabal LC_TIME constant to german
        //setlocale(LC_ALL, "de_DE", "de_DE.UTF-8", "deu", "deu_deu", "german");
        // Little bit other Syntax but better effect
       // echo "time " . strftime('%A, %d. %B %Y') . "<br>"; //Output: Mittwoch, 07. September 2005 
	   
	   
	//$new_date=utf8_encode(strftime('%A, %d. %B %Y', strtotime($order->info['date_purchased'])));
	//date('l d F, Y',mktime(0,0,0,$formated_date['m'],$formated_date['d'],$formated_date['y']));
	
	//$new_date=utf8_encode(strftime('%A %B %d, %Y', strtotime($order->info['date_purchased'])));
	//echo date("l F j, Y");

	?>
        
          <ul>
			  
			 <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>
			  <script language="JavaScript" type="text/javascript"><!--
			  //this button only works with javascript and is therefore only displayed on browsers with javascript enabled
              document.write("<li><a href=\"javascript:newOrderEmail()\"><img src=\"includes/languages/<?php echo $language; ?>/images/buttons/button_new_order_email.gif\" border=\"0\" alt=\"<?php echo IMAGE_NEW_ORDER_EMAIL; ?>\" title=\"<?php echo IMAGE_NEW_ORDER_EMAIL; ?>\" ></a></li>");
	           //--></script>
			   <?php } ?>
				  

		    <li><?php echo '<a href="' . tep_href_link(FILENAME_EVENTS_TICKET, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_ticket.gif', IMAGE_BUTTON_TICKET) . '</a>'; ?></li>
            <li><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a>'; ?></li>
	    
		    <?php if (FILENAME_PDF_INVOICE   !== 'FILENAME_PDF_INVOICE'   ) { ?>        			   
       			 <li><?php echo '<a href="' . tep_href_link(FILENAME_PDF_INVOICE,       'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_invoice_pdf.gif', IMAGE_ORDERS_INVOICE) . '</a>'; ?></li>  
			<?php } ?>	
		    <?php if (FILENAME_PDF_PACKINGSLIP   !== 'FILENAME_PDF_PACKINGSLIP'   ) { ?>        			   
       			 <li><?php echo '<a href="' . tep_href_link(FILENAME_PDF_PACKINGSLIP,       'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_packingslip_pdf.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a>'; ?></li>  
			<?php } ?>				

<!- eof 5.0.8 -->							
					    
		    <li><?php echo '<a href="'.$_SESSION['the_referrer'].'">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> '; ?></li>
		  </ul>
      
	  </div>
	   
	    <div id="ordersMessageStack">
	   	  <?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
	    </div>
	   	   
	<?php if (ORDER_EDITOR_USE_AJAX != 'true') { ?>
	<!-- Begin Update Block, only for non-ajax use -->

           <div class="updateBlock">
              <div class="update1"><?php echo HINT_PRESS_UPDATE; ?></div>
              <div class="update2">&nbsp;</div>
              <div class="update3">&nbsp;</div>
              <div class="update4" align="center"><?php echo ENTRY_SEND_NEW_ORDER_CONFIRMATION; ?>&nbsp;<?php echo tep_draw_checkbox_field('nC1', '', false); ?></div>
              <div class="update5" align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></div>
          </div>
	
	  <br>
	  <br>
	  <!-- End of Update Block -->
	  <?php } ?>


    <!-- customer_info bof //-->
            
        <table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top">
            <!-- customer_info bof //-->
            <table width="100%" border="0" cellspacing="0" cellpadding="2" style="border: 1px solid #C9C9C9;">
              <tr class="dataTableHeadingRow"> 
                <td colspan="4" class="dataTableHeadingContent" valign="top"><?php echo ENTRY_CUSTOMER; ?></td>
              </tr>
              <tr class="dataTableRow"> 
                <td class="dataTableContent" valign="middle" align="right" nowrap><?php echo ENTRY_NAME; ?></td>
                <td colspan="3" valign="top" class="dataTableContent"><input name="update_customer_name" size="37" value="<?php echo stripslashes($order->customer['name']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_name', encodeURIComponent(this.value))"<?php } ?>></td>
              </tr>
              <tr class="dataTableRow"> 
                <td class="dataTableContent" valign="middle" align="right" nowrap><?php echo ENTRY_COMPANY; ?></td>
                <td colspan="3" valign="top" class="dataTableContent"><input name="update_customer_company" size="37" value="<?php echo stripslashes($order->customer['company']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_company', encodeURIComponent(this.value))"<?php } ?>></td>
              </tr>
              <tr class="dataTableRow"> 
                <td class="dataTableContent" valign="middle" align="right" nowrap><?php echo ENTRY_STREET_ADDRESS; ?></td>
                <td colspan="3" valign="top" class="dataTableContent" nowrap><input name="update_customer_street_address" size="37" value="<?php echo stripslashes($order->customer['street_address']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_street_address', encodeURIComponent(this.value))"<?php } ?>></td>
              </tr>
              <tr class="dataTableRow"> 
                <td class="dataTableContent" valign="middle" align="right"><?php echo ENTRY_SUBURB; ?></td>
                <td colspan="3" valign="top" class="dataTableContent" nowrap><input name="update_customer_suburb" size="37" value="<?php echo stripslashes($order->customer['suburb']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_suburb', encodeURIComponent(this.value))"<?php } ?>></td>
              </tr>
              <tr class="dataTableRow"> 
                <td class="dataTableContent" valign="middle" align="right" nowrap><?php echo ENTRY_CITY_STATE; ?></td>
                <td colspan="2" valign="top" class="dataTableContent" nowrap><input name="update_customer_city" size="15" value="<?php echo stripslashes($order->customer['city']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_city', encodeURIComponent(this.value))"<?php } ?>>,</td>
                <td valign="top" class="dataTableContent"><span id="customerStateMenu">
				<?php if (ORDER_EDITOR_USE_AJAX == 'true') {
				echo tep_draw_pull_down_menu('update_customer_zone_id', tep_get_country_zones($order->customer['country_id']), $order->customer['zone_id'], 'style="width: 200px;" onChange="updateOrdersField(\'customers_state\', this.options[this.selectedIndex].text);"'); 
				} else {
				echo tep_draw_pull_down_menu('update_customer_zone_id', tep_get_country_zones($order->customer['country_id']), $order->customer['zone_id'], 'style="width: 200px;"');
				}?></span><span id="customerStateInput"><input name="update_customer_state" size="15" value="<?php echo stripslashes($order->customer['state']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_state', encodeURIComponent(this.value))"<?php } ?>></span></td>
              </tr>
              <tr class="dataTableRow"> 
                <td class="dataTableContent" valign="middle" align="right" nowrap><?php echo ENTRY_POST_CODE; ?></td>
                <td class="dataTableContent" valign="top"><input name="update_customer_postcode" size="5" value="<?php echo $order->customer['postcode']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_postcode', encodeURIComponent(this.value))"<?php } ?>></td>
                <td class="dataTableContent" valign="middle" align="right" nowrap><?php echo ENTRY_COUNTRY; ?></td>
                <td class="dataTableContent" valign="top">
				<?php if (ORDER_EDITOR_USE_AJAX == 'true') {
				echo tep_draw_pull_down_menu('update_customer_country_id', tep_get_countries(), $order->customer['country_id'], 'style="width: 200px;" onChange="update_zone(\'update_customer_country_id\', \'update_customer_zone_id\', \'customerStateInput\', \'customerStateMenu\'); updateOrdersField(\'customers_country\', this.options[this.selectedIndex].text);"'); 
				} else {
				echo tep_draw_pull_down_menu('update_customer_country_id', tep_get_countries(), $order->customer['country_id'], 'style="width: 200px;" onChange="update_zone(\'update_customer_country_id\', \'update_customer_zone_id\', \'customerStateInput\', \'customerStateMenu\');"'); 
				} ?></td>
              </tr>
              <tr class="dataTableRow"> 
                <td colspan="4" style="border-top: 1px solid #C9C9C9;"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
              </tr>
              <tr class="dataTableRow"> 
                <td class="dataTableContent" valign="middle" align="right"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
                <td colspan="3" valign="top" class="dataTableContent"><input name="update_customer_telephone" size="15" value="<?php echo $order->customer['telephone']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_telephone', encodeURIComponent(this.value))"<?php } ?>></td>
              </tr>
              <tr class="dataTableRow"> 
                <td class="dataTableContent" valign="middle" align="right"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                <td colspan="3" valign="top" class="dataTableContent"><input name="update_customer_email_address" size="35" value="<?php echo $order->customer['email_address']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_email_address', encodeURIComponent(this.value))"<?php } ?>></td>
              </tr>
            </table>
			
			<!-- customer_info_eof //-->
            <!-- shipping_address bof -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #C9C9C9;">
              <tr>
                <td class="dataTableContent">
                <table width="100%" cellspacing="0" cellpadding="2">
                  <tr class="dataTableHeadingRow"> 
				   <td class="dataTableHeadingContent" valign="top" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_SHIPPING_ADDRESS); ?>')"; onMouseout="hideddrivetip()"><?php echo ENTRY_SHIPPING_ADDRESS; ?> 
				   	<script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script>
				  
				</td>
                  </tr>
				  
                  <?php if (ORDER_EDITOR_USE_AJAX != 'true') { ?>
				  <tr class="dataTableRow"> 
                    <td valign="middle" class="dataTableContent"><input type="checkbox" name="shipping_same_as_billing"> <?php echo TEXT_SHIPPING_SAME_AS_BILLING; ?></td>
                  </tr>
				  <?php } ?>
				  
                </table>
                </td>
              </tr>
              <tr id="shippingAddressEntry">
                <td class="dataTableContent">
                <table width="100%" cellspacing="0" cellpadding="2">
                  <tr class="dataTableRow"> 
                    <td colspan="4" style="border-top: 1px solid #C9C9C9;"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
                  </tr>
                  <tr class="dataTableRow"> 
                    <td class="dataTableContent" valign="middle" align="right"><?php echo ENTRY_NAME; ?></td>
                    <td colspan="3" valign="top" class="dataTableContent"><input name="update_delivery_name" size="37" value="<?php echo stripslashes($order->delivery['name']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('delivery_name', encodeURIComponent(this.value))"<?php } ?>></td>
                  </tr>
                  <tr class="dataTableRow"> 
                    <td class="dataTableContent" valign="middle" align="right"><?php echo ENTRY_COMPANY; ?></td>
                    <td colspan="3" valign="top" class="dataTableContent"><input name="update_delivery_company" size="37" value="<?php echo stripslashes($order->delivery['company']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('delivery_company', encodeURIComponent(this.value))"<?php } ?>></td>
                  </tr>
				  <tr class="dataTableRow"> 
                    <td class="dataTableContent" valign="middle" align="right"><?php echo ENTRY_BILLING_EMAIL; ?></td>
                    <td colspan="3" valign="top" class="dataTableContent"><input name="update_delivery_email" size="37" value="<?php echo stripslashes($order->delivery['email']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('delivery_email', encodeURIComponent(this.value))"<?php } ?>></td>
                  </tr>
                  <tr class="dataTableRow"> 
                    <td class="dataTableContent" valign="middle" align="right"><?php echo ENTRY_STREET_ADDRESS; ?></td>
                    <td colspan="3" valign="top" class="dataTableContent"><input name="update_delivery_street_address" size="37" value="<?php echo stripslashes($order->delivery['street_address']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('delivery_street_address', encodeURIComponent(this.value))"<?php } ?>></td>
                  </tr>
                  <tr class="dataTableRow"> 
                    <td class="dataTableContent" valign="middle" align="right"><?php echo ENTRY_SUBURB; ?></td>
                    <td colspan="3" valign="top" class="dataTableContent"><input name="update_delivery_suburb" size="37" value="<?php echo stripslashes($order->delivery['suburb']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('delivery_suburb', encodeURIComponent(this.value))"<?php } ?>></td>
                  </tr>
                  <tr class="dataTableRow">
                    <td class="dataTableContent" valign="middle" align="right" nowrap><?php echo ENTRY_CITY_STATE; ?></td>
                    <td colspan="2" valign="top" class="dataTableContent" nowrap><input name="update_delivery_city" size="15" value="<?php echo stripslashes($order->delivery['city']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('delivery_city', encodeURIComponent(this.value))"<?php } ?>>,</td>
                    <td valign="top" class="dataTableContent"><span id="deliveryStateMenu">
					<?php if (ORDER_EDITOR_USE_AJAX == 'true') { 
				echo tep_draw_pull_down_menu('update_delivery_zone_id', tep_get_country_zones($order->delivery['country_id']), $order->delivery['zone_id'], 'style="width: 200px;" onChange="updateShippingZone(\'delivery_state\', this.options[this.selectedIndex].text);"'); 
					} else {
					echo tep_draw_pull_down_menu('update_delivery_zone_id', tep_get_country_zones($order->delivery['country_id']), $order->delivery['zone_id'], 'style="width: 200px;"'); 
					} ?>
					</span><span id="deliveryStateInput"><input name="update_delivery_state" size="15" value="<?php echo stripslashes($order->delivery['state']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateShippingZone('delivery_state', encodeURIComponent(this.value))"<?php } ?>></span></td>
                  </tr>
                  <tr class="dataTableRow"> 
                    <td class="dataTableContent" valign="middle" align="right"><?php echo ENTRY_POST_CODE; ?></td>
                    <td class="dataTableContent" valign="top"><input name="update_delivery_postcode" size="5" value="<?php echo $order->delivery['postcode']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateShippingZone('delivery_postcode', encodeURIComponent(this.value))"<?php } ?>></td>
                    <td class="dataTableContent" valign="middle" align="right"><?php echo ENTRY_COUNTRY; ?></td>
                    <td class="dataTableContent" valign="top">
					<?php if (ORDER_EDITOR_USE_AJAX == 'true') {
					echo tep_draw_pull_down_menu('update_delivery_country_id', tep_get_countries(), $order->delivery['country_id'], 'style="width: 200px;" onchange="update_zone(\'update_delivery_country_id\', \'update_delivery_zone_id\', \'deliveryStateInput\', \'deliveryStateMenu\'); updateShippingZone(\'delivery_country\', this.options[this.selectedIndex].text);"'); 
					} else {
					echo tep_draw_pull_down_menu('update_delivery_country_id', tep_get_countries(), $order->delivery['country_id'], 'style="width: 200px;" onchange="update_zone(\'update_delivery_country_id\', \'update_delivery_zone_id\', \'deliveryStateInput\', \'deliveryStateMenu\');"'); 
					}
					?></td>
                  </tr>       
                </table>
                </td>
              </tr>                  
            </table>
            <!-- shipping_address_eof //-->
            </td>
            <td valign="top" width="10">&nbsp;</td>
            <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #C9C9C9;">
              <!-- billing_address bof //-->
              <tr>
                <td class="dataTableContent">
                <table width="100%" cellspacing="0" cellpadding="2">
                  <tr class="dataTableHeadingRow"> 
                    <td colspan="4" class="dataTableHeadingContent" valign="top"><?php echo ENTRY_BILLING_ADDRESS; ?></td>
                  </tr>
				  
				  <?php if (ORDER_EDITOR_USE_AJAX != 'true') { ?>
                  <tr class="dataTableRow"> 
                    <td colspan="4" valign="middle" class="dataTableContent"><input type="checkbox" name="billing_same_as_customer"> <?php echo TEXT_BILLING_SAME_AS_CUSTOMER; ?></td>
                  </tr>
				  <?php } ?>
				  
                </table>
                </td>
              </tr>
              <tr id="billingAddressEntry">
                <td class="dataTableContent">
                <table width="100%" cellspacing="0" cellpadding="2">               
                  <tr class="dataTableRow">
                    <td colspan="4" style="border-top: 1px solid #C9C9C9;"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
                  </tr>
                  <tr class="dataTableRow"> 
                    <td class="dataTableContent" valign="middle" align="right" nowrap><?php echo ENTRY_NAME; ?></td>
                    <td colspan="3" valign="top" class="dataTableContent"><input name="update_billing_name" size="37" value="<?php echo stripslashes($order->billing['name']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('billing_name', encodeURIComponent(this.value))"<?php } ?>></td>
                  </tr>
                  <tr class="dataTableRow"> 
                    <td class="dataTableContent" valign="middle" align="right" nowrap><?php echo ENTRY_COMPANY; ?></td>
                    <td colspan="3" valign="top" class="dataTableContent"><input name="update_billing_company" size="37" value="<?php echo stripslashes($order->billing['company']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('billing_company', encodeURIComponent(this.value))"<?php } ?>></td>
                  </tr>
				  <tr class="dataTableRow"> 
                    <td class="dataTableContent" valign="middle" align="right" nowrap><?php echo ENTRY_BILLING_EMAIL; ?></td>
                    <td colspan="3" valign="top" class="dataTableContent"><input name="update_billing_email" size="37" value="<?php echo stripslashes($order->billing['email']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('billing_email', encodeURIComponent(this.value))"<?php } ?>></td>
                  </tr>
                  <tr class="dataTableRow"> 
                    <td class="dataTableContent" valign="middle" align="right" nowrap><?php echo ENTRY_STREET_ADDRESS; ?></td>
                    <td colspan="3" valign="top" class="dataTableContent"><input name="update_billing_street_address" size="37" value="<?php echo stripslashes($order->billing['street_address']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('billing_street_address', encodeURIComponent(this.value))"<?php } ?>></td>
                  </tr>
                  <tr class="dataTableRow"> 
                    <td class="dataTableContent" valign="middle" align="right" nowrap><?php echo ENTRY_SUBURB; ?></td>
                    <td colspan="3" valign="top" class="dataTableContent"><input name="update_billing_suburb" size="37" value="<?php echo stripslashes($order->billing['suburb']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('billing_suburb', encodeURIComponent(this.value))"<?php } ?>></td>
                  </tr>
                  <tr class="dataTableRow"> 
                    <td class="dataTableContent" valign="middle" align="right" nowrap><?php echo ENTRY_CITY_STATE; ?></td>
                    <td colspan="2" valign="top" class="dataTableContent" nowrap><input name="update_billing_city" size="15" value="<?php echo stripslashes($order->billing['city']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('billing_city', encodeURIComponent(this.value))"<?php } ?>>,</td>
                    <td valign="top" class="dataTableContent"><span id="billingStateMenu">
					<?php if (ORDER_EDITOR_USE_AJAX == 'true') {
					echo tep_draw_pull_down_menu('update_billing_zone_id', tep_get_country_zones($order->billing['country_id']), $order->billing['zone_id'], 'style="width: 200px;" onChange="updateOrdersField(\'billing_state\', this.options[this.selectedIndex].text);"'); 
					} else {
					echo tep_draw_pull_down_menu('update_billing_zone_id', tep_get_country_zones($order->billing['country_id']), $order->billing['zone_id'], 'style="width: 200px;"');
					} ?>
					</span><span id="billingStateInput"><input name="update_billing_state" size="15" value="<?php echo stripslashes($order->billing['state']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('billing_state', encodeURIComponent(this.value))"<?php } ?>></span></td>
                  </tr>
                  <tr class="dataTableRow"> 
                    <td class="dataTableContent" valign="middle" align="right" nowrap><?php echo ENTRY_POST_CODE; ?></td>
                    <td class="dataTableContent" valign="top"><input name="update_billing_postcode" size="5" value="<?php echo $order->billing['postcode']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('billing_postcode', encodeURIComponent(this.value))"<?php } ?>></td>
                    <td class="dataTableContent" valign="middle" align="right" nowrap><?php echo ENTRY_COUNTRY; ?></td>
                    <td class="dataTableContent" valign="top">
					<?php if (ORDER_EDITOR_USE_AJAX == 'true') {
					echo tep_draw_pull_down_menu('update_billing_country_id', tep_get_countries(), $order->billing['country_id'], 'style="width: 200px;" onchange="update_zone(\'update_billing_country_id\', \'update_billing_zone_id\', \'billingStateInput\', \'billingStateMenu\'); updateOrdersField(\'billing_country\', this.options[this.selectedIndex].text);"'); 
					} else {
					echo tep_draw_pull_down_menu('update_billing_country_id', tep_get_countries(), $order->billing['country_id'], 'style="width: 200px;" onchange="update_zone(\'update_billing_country_id\', \'update_billing_zone_id\', \'billingStateInput\', \'billingStateMenu\'); updateOrdersField(\'billing_country\', this.options[this.selectedIndex].text);"'); 
					} ?></td>
                  </tr>
                </table>
                </td>
              </tr>
              <!-- billing_address_eof //-->
              <!-- payment_method bof //-->
              <tr>
                <td class="dataTableContent">
             
      <table cellspacing="0" cellpadding="2" width="100%">
        <tr class="dataTableHeadingRow"> 
		<!--onMouseover="ddrivetip('<?php //echo oe_html_no_quote(HINT_UPDATE_TO_CC); ?>')" onMouseout="hideddrivetip()"-->
          <td colspan="2" class="dataTableHeadingContent" valign="bottom" ><?php echo ENTRY_PAYMENT_METHOD; ?>
		  		
				  <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script>
			
			</td>
	      
		     <td></td>
	         <td class="dataTableHeadingContent" valign="bottom" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_UPDATE_CURRENCY); ?>')" onMouseout="hideddrivetip()"><?php echo ENTRY_CURRENCY_TYPE; ?> 
		  
		  		  <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script>
				  
             </td>
	         <td></td>
	         <td class="dataTableHeadingContent"><?php echo ENTRY_CURRENCY_VALUE; ?></td>
         </tr>
                  
	     <tr class="dataTableRow"> 
	       <td colspan="2" class="main">
	       <?php 
	        //START for payment dropdown menu use this by quick_fixer
  		      if (ORDER_EDITOR_PAYMENT_DROPDOWN == 'true') { 
		
		    // Get list of all payment modules available
            $enabled_payment = array();
            $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
            $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));

             if ($dir = @dir($module_directory)) {
              while ($file = $dir->read()) {
               if (!is_dir( $module_directory . $file)) {
                if (substr($file, strrpos($file, '.')) == $file_extension) {
                   $directory_array[] = $file;
                 }
               }
             }
            sort($directory_array);
            $dir->close();
           }

          // For each available payment module, check if enabled
          for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
          $file = $directory_array[$i];

          include(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/payment/' . $file);
          include($module_directory . $file);

          $class = substr($file, 0, strrpos($file, '.'));
          if (tep_class_exists($class)) {
             $module = new $class;
             if ($module->check() > 0) {
              // If module enabled create array of titles
      	       $enabled_payment[] = array('id' => $module->title, 'text' => $module->title);
		
		      //if the payment method is the same as the payment module title then don't add it to dropdown menu
		      if ($module->title == $order->info['payment_method']) {
			      $paymentMatchExists='true';	
		         }
              }
            }
          }
 		//just in case the payment method found in db is not the same as the payment module title then make it part of the dropdown array or else it cannot be the selected default value
		  if ($paymentMatchExists !='true') {
			$enabled_payment[] = array('id' => $order->info['payment_method'], 'text' => $order->info['payment_method']);	
           }
            $enabled_payment[] = array('id' => 'Other', 'text' => 'Other');	
		    //draw the dropdown menu for payment methods and default to the order value
	  		  if (ORDER_EDITOR_USE_AJAX == 'true') {
			  echo tep_draw_pull_down_menu('update_info_payment_method', $enabled_payment, $order->info['payment_method'], 'id="update_info_payment_method" style="width: 150px;" onChange="init(); updateOrdersField(\'payment_method\', this.options[this.selectedIndex].text)"'); 
			  } else {
			  echo tep_draw_pull_down_menu('update_info_payment_method', $enabled_payment, $order->info['payment_method'], 'id="update_info_payment_method" style="width: 150px;" onChange="init();"'); 
			  }
		    }  else { //draw the input field for payment methods and default to the order value  ?>
		  
		   <input name="update_info_payment_method" size="35" value="<?php echo $order->info['payment_method']; ?>" id="update_info_payment_method" onChange="init();<?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?> updateOrdersField('payment_method', encodeURIComponent(this.value));<?php } ?>">
		   
		   <?php } //END for payment dropdown menu use this by quick_fixer ?>
		   
		   </td>
	
	       <td width="20">
	       </td>
	
	        <td>
			 <?php
	         ///get the currency info
              reset($currencies->currencies);
              $currencies_array = array();
               // while (list($key, $value) = each($currencies->currencies)) 
				//FOREACH
				foreach($currencies->currencies as $key => $value)
				{
                      $currencies_array[] = array('id' => $key, 'text' => $value['title']);
                 }
	
               echo tep_draw_pull_down_menu('update_info_payment_currency', $currencies_array, $order->info['currency'], 'id="update_info_payment_currency" onChange="currency(this.value)"'); 

?>
          </td>

         <td width="10">
         </td>

	     <td>
		  <input name="update_info_payment_currency_value" size="15" readonly id="update_info_payment_currency_value" value="<?php echo $order->info['currency_value']; ?>">
		 </td>
      </tr>

                  <!-- credit_card bof //-->
    <tr class="dataTableRow"> 
      <td colspan="6">
	  
	  <table id="optional"><!--  -->
	 <tr>
	    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
	<td class="main"><input name="update_info_cc_type" size="32" value="<?php echo $order->info['cc_type']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('cc_type', encodeURIComponent(this.value))"<?php } ?>></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
	    <td class="main"><input name="update_info_cc_owner" size="32" value="<?php echo $order->info['cc_owner']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('cc_owner', encodeURIComponent(this.value))<?php } ?>"></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
	    <td class="main"><input name="update_info_cc_number" size="32" value="<?php echo $order->info['cc_number']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('cc_number', encodeURIComponent(this.value))"<?php } ?>></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
	    <td class="main"><input name="update_info_cc_expires" size="4" value="<?php echo $order->info['cc_expires']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('cc_expires', encodeURIComponent(this.value))"<?php } ?>></td>
	  </tr>
	</table>
	  
   </td>
  </tr>
 </table>
				
				</td>
              </tr>                  
            </table></td>
          </tr>
        </table>
		
	<div id="productsMessageStack">
	  <?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
    </div>

	
	<div width="100%" style="border: 1px solid #C9C9C9;"> 
	  <a name="products"></a>
		<!-- product_listing bof //-->
         
            <table border="0" width="100%" cellspacing="0" cellpadding="2" id="productsTable">
			   <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><div align="center"><?php echo TABLE_HEADING_DELETE; ?></div></td>
			    <td class="dataTableHeadingContent"><div align="center"><?php echo TABLE_HEADING_QUANTITY; ?></div></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX; ?></td>
	  <td class="dataTableHeadingContent" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_BASE_PRICE); ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_BASE_PRICE; ?> <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script></td>
	  <td class="dataTableHeadingContent" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_PRICE_EXCL); ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_UNIT_PRICE; ?> <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script></td>
	  <td class="dataTableHeadingContent" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_PRICE_INCL); ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_UNIT_PRICE_TAXED; ?> <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script></td>
	  <td class="dataTableHeadingContent" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_TOTAL_EXCL); ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_TOTAL_PRICE; ?> <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script></td>
      <td class="dataTableHeadingContent" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_TOTAL_INCL); ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_TOTAL_PRICE_TAXED; ?> <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script></td>
              </tr>
  <?php
  if (sizeof($order->products)) {
    for ($i=0; $i<sizeof($order->products); $i++) {
      $orders_products_id = $order->products[$i]['orders_products_id'];  ?>
			   
			   <tr class="dataTableRow">
                
				<td class="dataTableContent" valign="top"><div align="center"><input type="checkbox" name="<?php echo "update_products[" . $orders_products_id . "][delete]"; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onClick="updateProductsField('delete', '<?php echo $orders_products_id; ?>', 'delete', this.checked, this)"<?php } ?>></div></td>
				
				<?php //sakwoya - we need to make the quantity box a non field here for products type=P
					  // option 1 is to ammend the includes/classes/order.php file but option 2 (code it here) 
					  // means less files to change in the standard osConcert setup
					  
					          $product_query = tep_db_query("select support_packs_type from " . TABLE_ORDERS_PRODUCTS . "  where orders_products_id = '" . (int)$orders_products_id . "' ");
        
        					$product_result = tep_db_fetch_array($product_query);
							//change to events_type to identify product type
							$product_type=$product_result['support_packs_type'];
							if($product_type=='P')
							{
								?>
							
								<td class="dataTableContent" valign="top"><div align="center">
								<?php echo $order->products[$i]['qty']; ?><input name="<?php echo "update_products[" . $orders_products_id . "][qty]"; ?>" type="hidden" value="<?php echo $order->products[$i]['qty']; ?>"></td>
								</td>			
							
							<?php  
							}
							else
							{	?>							 
                
			
				<td class="dataTableContent" valign="top"><div align="center"><input name="<?php echo "update_products[" . $orders_products_id . "][qty]"; ?>" size="2" onKeyUp="updatePrices('qty', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload1', '<?php echo $orders_products_id; ?>', 'products_quantity', encodeURIComponent(this.value))"<?php } ?> value="<?php echo $order->products[$i]['qty']; ?>" id="<?php echo "update_products[" . $orders_products_id . "][qty]"; ?>"></div><?php echo $order->products[$i]['type']; ?></td>
				
					<?php }?>
                 				
		<?php
		$orders_products_status=0;
		$orders_products_status_query = tep_db_query("select orders_products_status from " . TABLE_ORDERS_PRODUCTS . "  where orders_products_id = '" . (int)$orders_products_id . "' ");
        
			$orders_products_status_result = tep_db_fetch_array($orders_products_status_query);
			$orders_products_status=$orders_products_status_result['orders_products_status'];
		
		
		    $heading_name = $order->products[$i]['categories_name'];
			$heading_venue = $order->products[$i]['concert_venue'];
			$heading_date = $order->products[$i]['concert_date'];
			$heading_time = $order->products[$i]['concert_time'];
			$events_type = $order->products[$i]['events_type'];
			$discount='';
		
				$discount=(isset($order->products[$i]['discount_whole_text']) && $order->products[$i]['discount_whole_text']!='')?'<br>' . $order->products[$i]['discount_whole_text']:'';
				
				if ($product_type=='F')
				{
					//$family= '<small>'.FAMILY_TICKET.' x '.FAMILY_TICKET_QTY.'</small>';
					$family= '<small>FAMILY TICKET x '.FAMILY_TICKET_QTY.'</small>';
				}else{
					$family='';
				}
			
				?>
				

				<td class="dataTableContent" valign="top">
				<?php
				if(SHOW_ORDERS_PRODUCTS_STATUS=='yes'){
				echo "(".$orders_products_status.")";
				}
				?>
				<?php echo oe_html_quotes($order->products[$i]['name']); ?>
				<input name="<?php echo "update_products[" . $orders_products_id . "][name]"; ?>" type="hidden" value="<?php echo oe_html_quotes($order->products[$i]['name']); ?>">
				<br><?php echo $heading_name; ?> 
				<br><span class="smallText"><?php echo $heading_venue; ?> 
				<?php echo $heading_date; ?> 
				<?php echo $heading_time; ?>
				<?php echo '<br>'.$family; ?>
				<?php echo $discount;?></span>
				</td>

             
			<td class="dataTableContent" valign="top"><input name="<?php echo "update_products[" . $orders_products_id . "][model]"; ?>"  type="hidden"  value="<?php echo $order->products[$i]['model']; ?>"><?php echo $order->products[$i]['model']; ?></td>



            
			<td class="dataTableContent" valign="top"><input name="<?php echo "update_products[" . $orders_products_id . "][tax]"; ?>" size="5" onKeyUp="updatePrices('tax', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload1', '<?php echo $orders_products_id; ?>', 'products_tax', encodeURIComponent(this.value))"<?php } ?> value="<?php echo tep_display_tax_value($order->products[$i]['tax']); ?>" id="<?php echo "update_products[" . $orders_products_id . "][tax]"; ?>">%</td>
		
		    <td class="dataTableContent" valign="top"><input name="<?php echo "update_products[" . $orders_products_id . "][price]"; ?>" size="5" onKeyUp="updatePrices('price', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload2', '<?php echo $orders_products_id; ?>')"<?php } ?> value="<?php echo number_format($order->products[$i]['price'], 4, '.', ''); ?>" id="<?php echo "update_products[" . $orders_products_id . "][price]"; ?>"></td>
            
			<td class="dataTableContent" valign="top"><input name="<?php echo "update_products[" . $orders_products_id . "][final_price]"; ?>" size="5" onKeyUp="updatePrices('final_price', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload2', '<?php echo $orders_products_id; ?>')"<?php } ?> value="<?php echo number_format($order->products[$i]['final_price'], 4, '.', ''); ?>" id="<?php echo "update_products[" . $orders_products_id . "][final_price]"; ?>"></td>
                
			<td class="dataTableContent" valign="top"><input name="<?php echo "update_products[" . $orders_products_id . "][price_incl]"; ?>" size="5" value="<?php echo number_format(($order->products[$i]['final_price'] * (($order->products[$i]['tax']/100) + 1)), 4, '.', ''); ?>" onKeyUp="updatePrices('price_incl', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload2', '<?php echo $orders_products_id; ?>')"<?php } ?> id="<?php echo "update_products[" . $orders_products_id . "][price_incl]"; ?>"></td>
				
			<td class="dataTableContent" valign="top"><input name="<?php echo "update_products[" . $orders_products_id . "][total_excl]"; ?>" size="5" value="<?php echo number_format($order->products[$i]['final_price'] * $order->products[$i]['qty'], 4, '.', ''); ?>" onKeyUp="updatePrices('total_excl', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload2', '<?php echo $orders_products_id; ?>')"<?php } ?> id="<?php echo "update_products[" . $orders_products_id . "][total_excl]"; ?>"></td>
				
			<td class="dataTableContent" valign="top"><input name="<?php echo "update_products[" . $orders_products_id . "][total_incl]"; ?>" size="5" value="<?php echo number_format((($order->products[$i]['final_price'] * (($order->products[$i]['tax']/100) + 1))) * $order->products[$i]['qty'], 4, '.', ''); ?>" onKeyUp="updatePrices('total_incl', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload2', '<?php echo $orders_products_id; ?>')"<?php } ?> id="<?php echo "update_products[" . $orders_products_id . "][total_incl]"; ?>"></td>
				
              </tr>
             			  
<?php
    }
  } else {
    //the order has no products
?>
              <tr class="dataTableRow">
                <td colspan="10" class="dataTableContent" valign="middle" align="center" style="padding: 20px 0 20px 0;"><?php echo TEXT_NO_ORDER_PRODUCTS; ?></td>
              </tr>
              <tr class="dataTableRow"> 
                <td colspan="10" style="border-bottom: 1px solid #C9C9C9;"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
              </tr>
<?php
  }
?>
            </table><!-- product_listing_eof //-->
			
		<div id="totalsBlock">
		<table width="100%">
		  <tr><td>
			 
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top" width="75%">
				  <br>
				    <div>
					  <a href="<?php echo tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $_GET['oID'] . '&step=1'); ?>" target="addProducts" onClick="openWindow('<?php echo tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $_GET['oID'] . '&step=1'); ?>','addProducts');return false"><?php echo tep_image_button('button_add_article.gif', TEXT_ADD_NEW_PRODUCT); ?></a><input type="hidden" name="subaction" value="">
				    </div>
				  <br>
			    </td>
             
			  <!-- order_totals bof //-->
                <td align="right" rowspan="2" valign="top" nowrap class="dataTableRow" style="border: 1px solid #C9C9C9;">
                  <table border="0" cellspacing="0" cellpadding="2" width="25%">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent" width="15" nowrap "; onMouseout="hideddrivetip()"> <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script></td>
                      <td class="dataTableHeadingContent" nowrap><?php echo TABLE_HEADING_OT_TOTALS; ?></td>
                      <td class="dataTableHeadingContent" colspan="2" nowrap><?php echo TABLE_HEADING_OT_VALUES; ?></td>
                    </tr>
<?php
  for ($i=0; $i<sizeof($order->totals); $i++) {
  
    $id = $order->totals[$i]['class'];
	
	if ($order->totals[$i]['class'] == 'ot_shipping') {
	   if (tep_not_null($order->info['shipping_id'])) {
	       $shipping_module_id = $order->info['shipping_id'];
		   } else {
		   //here we could create logic to attempt to determine the shipping module used if it's not in the database
		   $shipping_module_id = '';
		   }
	  } else {
	    $shipping_module_id = '';
	  } //end if ($order->totals[$i]['class'] == 'ot_shipping') {
	 
    $rowStyle = (($i % 2) ? 'dataTableRowOver' : 'dataTableRow');
    if ( ($order->totals[$i]['class'] == 'ot_total') || ($order->totals[$i]['class'] == 'ot_subtotal') || ($order->totals[$i]['class'] == 'ot_tax') || ($order->totals[$i]['class'] == 'ot_loworderfee') ) {
      echo '                  <tr class="' . $rowStyle . '">' . "\n";
      if ($order->totals[$i]['class'] != 'ot_total') {
        echo '                    <td class="dataTableContent" valign="middle" height="15">
		<script language="JavaScript" type="text/javascript">
		<!--
		document.write("<span id=\"update_totals['.$i.']\"><a href=\"javascript:setCustomOTVisibility(\'update_totals['.($i+1).']\', \'visible\', \'update_totals['.$i.']\');\"><img src=\"order_editor/images/plus.gif\" border=\"0\" alt=\"' . IMAGE_ADD_NEW_OT . '\" title=\"' . IMAGE_ADD_NEW_OT . '\"></a></span>");
		//-->
        </script></td>' . "\n";
      } else {
        echo '                    <td class="dataTableContent" valign="middle">&nbsp;</td>' . "\n";
      }
      
      echo '                    <td align="right" class="dataTableContent"><input name="update_totals['.$i.'][title]" value="' . trim($order->totals[$i]['title']) . '" readonly="readonly"></td>' . "\n";
	  
      if ($order->info['currency'] != DEFAULT_CURRENCY) echo '                    <td class="dataTableContent">&nbsp;</td>' . "\n";
      echo '                    <td align="right" class="dataTableContent" nowrap>' . $order->totals[$i]['text'] . '<input name="update_totals['.$i.'][value]" type="hidden" value="' . number_format($order->totals[$i]['value'], 2, '.', '') . '"><input name="update_totals['.$i.'][class]" type="hidden" value="' . $order->totals[$i]['class'] . '"></td>' . "\n" .
           '                  </tr>' . "\n";
    } else {
      if ($i % 2) {
        echo '                  	    <script language="JavaScript" type="text/javascript">
		<!--
		document.write("<tr class=\"' . $rowStyle . '\" id=\"update_totals['.$i.']\" style=\"visibility: hidden; display: none;\"><td class=\"dataTableContent\" valign=\"middle\" height=\"15\"><a href=\"javascript:setCustomOTVisibility(\'update_totals['.($i).']\', \'hidden\', \'update_totals['.($i-1).']\');\"><img src=\"order_editor/images/minus.gif\" border=\"0\" alt=\"' . IMAGE_REMOVE_NEW_OT . '\" title=\"' . IMAGE_REMOVE_NEW_OT . '\"></a></td>");
			 //-->
        </script>
			 
			 <noscript><tr class="' . $rowStyle . '" id="update_totals['.$i.']" >' . "\n" .
             '                    <td class="dataTableContent" valign="middle" height="15"></td></noscript>' . "\n";
      } else {
        echo '                  <tr class="' . $rowStyle . '">' . "\n" .
             '                    <td class="dataTableContent" valign="middle" height="15">
	    <script language="JavaScript" type="text/javascript">
		<!--
		document.write("<span id=\"update_totals['.$i.']\"><a href=\"javascript:setCustomOTVisibility(\'update_totals['.($i+1).']\', \'visible\', \'update_totals['.$i.']\');\"><img src=\"order_editor/images/plus.gif\" border=\"0\" alt=\"' . IMAGE_ADD_NEW_OT . '\" title=\"' . IMAGE_ADD_NEW_OT . '\"></a></span>");
		//-->
        </script></td>' . "\n";
      }

       if (ORDER_EDITOR_USE_AJAX == 'true') {
	  echo '                    <td align="right" class="dataTableContent"><input name="update_totals['.$i.'][title]" id="'.$id.'[title]" value="' . trim($order->totals[$i]['title']) . '" onChange="obtainTotals()"></td>' . "\n" .
           '                    <td align="right" class="dataTableContent"><input name="update_totals['.$i.'][value]" id="'.$id.'[value]" value="' . number_format($order->totals[$i]['value'], 2, '.', '') . '" size="6" onChange="obtainTotals()"><input name="update_totals['.$i.'][class]" type="hidden" value="' . $order->totals[$i]['class'] . '"><input name="update_totals['.$i.'][id]" type="hidden" value="' . $shipping_module_id . '" id="' . $id . '[id]"></td>' . "\n";
		   } else {
	  echo '                    <td align="right" class="dataTableContent">
	  <input size="40"  name="update_totals['.$i.'][title]" id="'.$id.'[title]" value="' . trim($order->totals[$i]['title']) . '">
								</td>' . "\n" .
           '                    <td align="right" class="dataTableContent"><input name="update_totals['.$i.'][value]" id="'.$id.'[value]" value="' . number_format(floatval($order->totals[$i]['value']), 2, '.', '') . '" size="6"><input name="update_totals['.$i.'][class]" type="hidden" value="' . $order->totals[$i]['class'] . '"><input name="update_totals['.$i.'][id]" type="hidden" value="' . $shipping_module_id . '" id="' . $id . '[id]"></td>' . "\n";
		   }
		   
      if ($order->info['currency'] != DEFAULT_CURRENCY) echo '                    <td align="right" class="dataTableContent" nowrap>' . $order->totals[$i]['text'] . '</td>' . "\n";
      echo '                  </tr>' . "\n";
    }
  }
?>
                </table>
			  </td>
                <!-- order_totals_eof //-->
              </tr>              
              <tr>
                <td valign="bottom">
                
<?php 
  if (sizeof($shipping_quotes) > 0) {
?>
                <!-- shipping_quote bof //-->
                <table width="650" cellspacing="0" cellpadding="2" style="border: 1px solid #C9C9C9;">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" colspan="3"><?php echo TABLE_HEADING_SHIPPING_QUOTES; ?></td>
                  </tr>
				  
				  				  
<?php
    $r = 0;
    for ($i=0, $n=sizeof($shipping_quotes); $i<$n; $i++) {
      for ($j=0, $n2=sizeof($shipping_quotes[$i]['methods']); $j<$n2; $j++) {
        $r++;
		if (!isset($shipping_quotes[$i]['tax'])) $shipping_quotes[$i]['tax'] = 0;
        $rowClass = ((($r/2) == (floor($r/2))) ? 'dataTableRowOver' : 'dataTableRow');
        echo '                  <tr class="' . $rowClass . '" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this, \'' . $rowClass . '\')" onClick="selectRowEffect(this, ' . $r . '); setShipping(' . $r . ');">' .
             '                    <td class="dataTableContent" valign="top" align="left">
			 <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<input type=\"radio\" name=\"shipping\" id=\"shipping_radio_' . $r . '\" value=\"' . $shipping_quotes[$i]['id'] . '_' . $shipping_quotes[$i]['methods'][$j]['id'].'\">");
	               //-->
                  </script>
			 <input type="hidden" id="update_shipping[' . $r . '][title]" name="update_shipping[' . $r . '][title]" value="'.$shipping_quotes[$i]['module'] . ' (' . $shipping_quotes[$i]['methods'][$j]['title'].'):">' . "\n" .
			 '      <input type="hidden" id="update_shipping[' . $r . '][value]" name="update_shipping[' . $r . '][value]" value="'.tep_add_tax($shipping_quotes[$i]['methods'][$j]['cost'], $shipping_quotes[$i]['tax']).'">' . "\n" .
			 '      <input type="hidden" id="update_shipping[' . $r . '][id]" name="update_shipping[' . $r . '][id]" value="' . $shipping_quotes[$i]['id'] . '_' . $shipping_quotes[$i]['methods'][$j]['id'] . '">' . "\n" .
             '      <td class="dataTableContent" valign="top">' . $shipping_quotes[$i]['module'] . ' (' . $shipping_quotes[$i]['methods'][$j]['title'] . '):</td>' . "\n" . 
             '      <td class="dataTableContent" align="right">' . $currencies->format(tep_add_tax($shipping_quotes[$i]['methods'][$j]['cost'], $shipping_quotes[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" . 
             '                  </tr>';
      }
    }
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" colspan="3"><?php echo sprintf(TEXT_PACKAGE_WEIGHT_COUNT, $shipping_num_boxes . ' x ' . $shipping_weight, $total_count); ?></td>
                  </tr>
                </table>
                <!-- shipping_quote_eof //-->
<?php
  } else {
 // echo AJAX_NO_QUOTES;
  }
?>                </td>
              </tr> 
            </table>
		  
		  </td></tr>
		 </table> 
	  </div>
    </div> <!-- this is end of the master div for the whole totals/shipping area -->
		      
	<?php if (ORDER_EDITOR_USE_AJAX != 'true') { ?> 
    <!-- Begin Update Block, only for non-javascript browsers -->

	  <br>
            <div class="updateBlock">
              <div class="update1"><?php echo HINT_PRESS_UPDATE; ?></div>
              <div class="update2">&nbsp;</div>
              <div class="update3">&nbsp;</div>
              <div class="update4" align="center"><?php echo ENTRY_SEND_NEW_ORDER_CONFIRMATION; ?>&nbsp;<?php echo tep_draw_checkbox_field('nC1', '', false); ?></div>
              <div class="update5" align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></div>
           </div>
		  
	       <br>
            <div><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></div>
	 
	 <!-- End of Update Block -->  
	 <?php } ?>
		
	  <div id="historyMessageStack">
	    <?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
	  </div>

    <div id="commentsBlock">
	<table style="border: 1px solid #C9C9C9;" cellspacing="0" cellpadding="2" class="dataTableRow" id="commentsTable">
     <tr class="dataTableHeadingRow">
      <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_DELETE; ?></td>
      <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>
      <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
      <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>
      <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></td>
      <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>
      <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_STATUS; ?></td>
      <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>
      <td class="dataTableHeadingContent" align="left"><?php echo TICKET_PRINTED; ?></td>
      <?php //now the heading
	 if (EXTRA_FIELDS == 'yes'){
	 ?>
      <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>
      <td class="dataTableHeadingContent" align="left"><?php echo NEW_FIELDS_HEADING; ?></td><?php } ?>
      <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>
      <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_COMMENTS; ?></td>
    </tr>
    <?php
      $orders_history_query = tep_db_query("SELECT orders_status_history_id, orders_status_id, date_added, customer_notified, comments, field_1, field_2, field_3, field_4, other 
                                            FROM " . TABLE_ORDERS_STATUS_HISTORY . " 
									        WHERE orders_id = '" . (int)$oID . "' 
									        ORDER BY date_added");
        if (tep_db_num_rows($orders_history_query)) {
          while ($orders_history = tep_db_fetch_array($orders_history_query)) {
          
		   $r++;
           $rowClass = ((($r/2) == (floor($r/2))) ? 'dataTableRowOver' : 'dataTableRow');
        
	      if (ORDER_EDITOR_USE_AJAX == 'true') { 
		   echo '  <tr class="' . $rowClass . '" id="commentRow' . $orders_history['orders_status_history_id'] . '" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this, \'' . $rowClass . '\')">' . "\n" .
         '	  <td class="smallText" align="center"><div id="do_not_delete"><input name="update_comments[' . $orders_history['orders_status_history_id'] . '][delete]" type="checkbox" onClick="updateCommentsField(\'delete\', \'' . $orders_history['orders_status_history_id'] . '\', this.checked, \'\', this)"></div></td>' . "\n" . 
		 '    <td class="dataTableHeadingContent" align="left" width="10"> </td>' . "\n" .
         '    <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
         '    <td class="dataTableHeadingContent" align="left" width="10"> </td>' . "\n" .
         '    <td class="smallText" align="center">';
		 } else {
		 echo '  <tr class="' . $rowClass . '" id="commentRow' . $orders_history['orders_status_history_id'] . '" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this, \'' . $rowClass . '\')">' . "\n" .
         '	  <td class="smallText" align="center"><div id="do_not_delete"><input name="update_comments[' . $orders_history['orders_status_history_id'] . '][delete]" type="checkbox"></div></td>' . "\n" . 
		 '    <td class="dataTableHeadingContent" align="left" width="10"> </td>' . "\n" .
         '    <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
         '    <td class="dataTableHeadingContent" align="left" width="10"> </td>' . "\n" .
         '    <td class="smallText" align="center">';
		 }
      
	   if ($orders_history['customer_notified'] == '1') {
        echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
         } else {
        echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
         }
       
	    echo '    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>' . "\n" .
             '    <td class="smallText" align="left">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n";
        echo '    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>' . "\n" .
             '    <td class="smallText" align="left">';
			 //Check if ticket was ever printed cartzone
			 $check_ticket_query = tep_db_query("
	                      SELECT ticket_printed 
	                      FROM " . TABLE_ORDERS . " 
						  WHERE orders_id = '" . (int)$oID . "'");
						  
    					$check_ticket = tep_db_fetch_array($check_ticket_query); 
	
			 							$ticket=$check_ticket['ticket_printed'];
                                        if ($ticket == "Y"){
                                        echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK);
                                        }else{
                                        echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS);
                                        }
                                        ##############################		
                                                    '</td>';
//Add Extra Fields
				if (EXTRA_FIELDS == 'yes')
				$comment_style="style='display:true'";else $comment_style="style='display:none'";
		echo '    <td '. $comment_style .' class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>' . "\n" .
             '    <td '. $comment_style .' class="smallText" valign="top">';
                                    if(tep_not_null($orders_history['field_1']))	  
                                    {echo''.FIELD_1.'&nbsp;'.$orders_history['field_1'].'<br>';}
                                    if(tep_not_null($orders_history['field_2']))	  
                                    {echo''.FIELD_2.'&nbsp;'.$orders_history['field_2'].'<br>';}
                                    if(tep_not_null($orders_history['field_3']))	  
                                    {echo''.FIELD_3.'&nbsp;'.$orders_history['field_3'].'<br>';}
                                    if(tep_not_null($orders_history['field_4']))	  
                                    {echo''.FIELD_4.'&nbsp;'.$orders_history['field_4'].'<br>';}
                                    if(tep_not_null($orders_history['other']))	  
                                    {echo''.FIELD_5.'&nbsp;'.$orders_history['other'] . '&nbsp;' . $other . '<br></td>';}
									//Add Extra Fields end';
									
		echo '    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>' . "\n" .
             '    <td class="smallText" align="left">';
  
        
		 echo 	tep_db_output($orders_history['comments']). 
		 '    </td>' . "\n";
		 
 
        echo '  </tr>' . "\n";
  
        }
       } else {
       echo '  <tr>' . "\n" .
            '    <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
            '  </tr>' . "\n";
       }

    ?>
  </table> 
  </div>
				  
      <div>
	  <?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?>
	  </div>
	  <br>
	
<table style="border: 1px solid #C9C9C9;" cellspacing="0" cellpadding="2" class="dataTableRow">
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_NEW_STATUS; ?></td>
    <td class="main" width="10">&nbsp;</td>
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_COMMENTS; ?></td>
  </tr>
	<tr>
	  <td>
		  <table border="0" cellspacing="0" cellpadding="2">
		  
        <tr>
          <td class="main"><b><?php echo ENTRY_STATUS; ?></b></td>
          <td class="main" align="right"><?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status'], 'id="status"'); ?></td>
        </tr>
        <tr>
          <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b></td>
          <td class="main" align="right"><?php echo oe_draw_checkbox_field('notify', '', false, '', 'id="notify"'); ?></td>
        </tr>
        <tr>
          <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b></td>
          <td class="main" align="right"><?php echo oe_draw_checkbox_field('notify_comments', '', false, '', 'id="notify_comments"'); ?></td>
        </tr>
				 <tr>
          <td class="main"><b>Set Delivery Date</b></td>
          <td class="main" align="right"><?php echo oe_draw_checkbox_field('delivery_date', '', false, '', 'id="delivery_date"'); ?></td>
        </tr>
						 <tr>
          <td class="main"><b>Delivery Date</b></td>
          <td class="main" align="right"><?php 
		  $check_delivery_query = tep_db_query("
	                      SELECT shipping_date 
	                      FROM " . TABLE_ORDERS . " 
						  WHERE orders_id = '" . (int)$oID . "'");
						  
    					$check_delivery = tep_db_fetch_array($check_delivery_query); 
	
			 							$delivery=$check_delivery['shipping_date'];
		  
		  echo $delivery; ?></td>
        </tr>
     </table>
	  </td>
    <td class="main" width="10">&nbsp;</td>
    <td class="main" style="padding-right:30px">
    <?php echo tep_draw_textarea_field('comments', 'soft', '40', '5', '', 'id="comments"'); ?>
    </td>
  </tr>
    
	<?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?> 
	<script language="JavaScript" type="text/javascript">
     <!--
	     document.write("<tr>");
         document.write("<td colspan=\"3\" align=\"right\">");
		 document.write("<input type=\"button\" name=\"comments_button\" value=\"<?php echo oe_html_no_quote(AJAX_SUBMIT_COMMENT); ?>\" onClick=\"javascript:getNewComment();\">");
		 document.write("</td>");
		 document.write("</tr>");
	 //-->
    </script>
	<?php } ?>
				  
  </table>
  
    <div>
	  <?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
	</div>
    
	<!-- End of Status Block -->

	<?php if (ORDER_EDITOR_USE_AJAX != 'true') { ?> 
	<!-- Begin Update Block, only for non-javascript browsers -->
	       <div class="updateBlock">
              <div class="update1"><?php echo HINT_PRESS_UPDATE; ?></div>
              <div class="update2">&nbsp;</div>
              <div class="update3">&nbsp;</div>
              <div class="update4" align="center"><?php echo ENTRY_SEND_NEW_ORDER_CONFIRMATION; ?>&nbsp;<?php echo tep_draw_checkbox_field('nC1', '', false); ?></div>
              <div class="update5" align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></div>
          </div>
		  
	       <br>
            <div><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></div>
	
	<!-- End of Update Block -->
	<?php   }  //end if (ORDER_EDITOR_USE_AJAX != 'true') {
          echo '</form>';
        }
    ?>
  <!-- body_text_eof //-->
      </td>
    </tr>
  </table>
  <!-- body_eof //-->

  <!-- footer //-->
  <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
  <!-- footer_eof //-->
  <br>
  </body>
  </html>
  <?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
  
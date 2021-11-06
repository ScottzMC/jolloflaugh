<?php


// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	
  require('includes/application_top.php');
  
  $insert_id = $_POST['order_id'];
  
  if(!isset($insert_id)){exit ('f');}
 
 $check = $_POST['check'];
 
   if(!isset($check)){exit ('c');}
   
   
  $order_status_query = tep_db_query("select reference_id from " . TABLE_ORDERS . " where orders_id = '" . $insert_id . "'");
  $order_status = tep_db_fetch_array($order_status_query); 	
  if(tep_not_null($order_status['reference_id']))
  {
	 $check1 =  md5($order_status['reference_id']);
  }	  
  else
	{
      exit('a');
	  }
	  
	  if( $check != $check1){exit($check.'   '. $check1);
	  }

 send_order_email ($insert_id);


function send_order_email($insert_id)
{
    global $FSESSION, $currencies;
	
	$check_query = tep_db_query("
			  SELECT customers_language 
			  FROM " . TABLE_ORDERS . " 
			  WHERE orders_id = '" . (int)$oID . "'");
						  
		$check = tep_db_fetch_array($check_query); 
	
	$customers_language=$check['customers_language'];
	$check_language_query = tep_db_query("SELECT directory from languages WHERE languages_id = '" . (int)$customers_language . "'");
	$check_language = tep_db_fetch_array($check_language_query);
	$customers_language_directory=$check_language['directory'];	

    require_once (DIR_WS_LANGUAGES . $customers_language_directory . '/' . FILENAME_CHECKOUT_PROCESS);
	require_once(DIR_WS_CLASSES . 'order.php');
	require_once('includes/classes/currencies.php');
	$currencies = new currencies();
	$order = new order($insert_id);
	
	//exit(var_dump($order));
	
	$merge_details=array();
	$send_details=array();
	// order details
	$merge_details[ORDR_NO]=$insert_id;
	
	//products
	
	$products_ordered = '<table width="100%">';
	$products_ordered.= '<tr height="30" class="textTableSubHead"><td width="20"><b>' . TEXT_QUANTITY .'</b></td><td colspan="2"><b>'.TEXT_PRODUCTS_NAME. '</b></td><td></td><td align="right" width="100"><b>' .TEXT_PRICE. '</b></td></tr>';
	$subtotal = 0;
	$total_tax = 0;
	$products_type='';
	$events_type='';

	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {

	
		
		// prepare for heading
		###################################################################
		$id=$order->products[$i]['id'];
		require_once(DIR_WS_INCLUDES . 'functions/categories_lookup.php');
		#####################################################
        // call the new function
        $type = $products[$i]['product_type'];
        
        list($heading_name, $heading_venue,  $heading_date, $heading_time) = categories_lookup();

		######################################################
		$order_is_printable = 0;
		$order_is_printable = $order_is_printable + $order->products[$i]['is_printable'];

//------insert customer choosen option eof ----
		$total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
		$total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
		$total_cost += $total_products_price;
		$flag_product=false;

			$flag_product=true;
			//change the value to cover currencies if caled from admin then there's no currency session so we need this
			//so that the tax gets added correctly
			$order->products[$i]['final_price'] = $currencies->format($order->products[$i]['final_price'],true,$order->info['currency'], $order->info['currency_value']);
			
			
			$products_ordered .= '<tr class="textTableContent" height="56">' . 
									'<td style="vertical-align: text-top;"  width="40" class="textMain"><br>'. $order->products[$i]['qty'] .' x </td>' .
									'<td width="55" style="vertical-align: text-top;" class="textMain"><br>'. tep_product_email_image($stock_values["products_image_1"],$order->products[$i]['name'],'style="width:50px;height:50px"').'</td>' . 
									'<td style="vertical-align: text-top;" class="textMain"><br>'. $order->products[$i]['name'] . ($order->products[$i]['model']!=""?' (' . $order->products[$i]['model'] . ')':'').  '<br>' . $products_ordered_attributes . (isset($order->products[$i]['discount_whole_text'])?'<br>' . $order->products[$i]['discount_whole_text']:'') . '</td><td style="vertical-align: text-top;" class="textMain"><small><br>' . $heading_name . ' - ' . $heading_venue . ' - ' . $heading_date . ' - ' . $heading_time . '</small></td>' .  
								'<td style="vertical-align: text-top;" align="right" class="textMain"><br></td>' . 
								
									'<td style="vertical-align: text-top;" align="right" class="textMain"><br>'.$currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty'],false)
									
									.'&nbsp;</td>' . 
									'</tr>' .
									'<tr>' . 
									'<td colspan="5" height="1" class="textBorder">' . tep_draw_separator('pixel_trans.gif',10,1) . '</td>' . 
									'</tr>';			
			$shown_price = $currencies->format($shown_price,true,$order_result['currency'], $order_result['currency_value']);

		// if ($products_type!="M")
		// {
			 if ($products_type=='')
				 $products_type='P';//$order->products[$i]['element_type'];
			// else if ($products_type!=$order->products[$i]['element_type'])
				// $products_type="M";
		// }
	}
	$products_ordered.="</table>";
	
	if(!isset($_COOKIE['customer_is_guest'])){ //email for PWA	
	$merge_details[ORDR_OL]='<a href="'.tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false).'" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">'."Order Invoice Link".'</a>';}else{$merge_details[ORDR_OL]='';}
	
	$merge_details[ORDR_OP]=strftime(DATE_FORMAT_LONG);



	//Add Extra Fields
	
	$merge_details[ORDR_OM]='';
	//add shipping placeholder
	define("ORDR_SM","Shipping_Method");
	$shipping_method=$order->info['shipping_method'];
	$merge_details[ORDR_SM]=$shipping_method;
	
	//create messages for the Email Response
	//Bank Transfer
	define("ORDR_BDM","Bank_Deposit_Message");//Bank Deposit Message as place holder %%Bank_Deposit_Message%%
	define("ORDR_REF","Reference_ID");//Reference ID = %%Reference_ID%%
	if($payment_modules->selected_module =='bank_transfer'){
	$merge_details[ORDR_BDM]=MODULE_BANK_TRANSFER_INFO;
	} else {
	$merge_details[ORDR_BDM]="";}
	
	//German Bank Transfer
	define("ORDR_GBDM","German_Bank_Deposit_Message");//German Bank Deposit Message as place holder %%German_Bank_Deposit_Message%%
	if($payment_modules->selected_module =='de_bank_transfer'){
	$merge_details[ORDR_GBDM]=MODULE_DE_BANK_TRANSFER_INFO;
	} else {
	$merge_details[ORDR_GBDM]="";}
	
	$merge_details[ORDR_REF]=$reference;
	//Cheque/Money Order
	define("ORDR_MOP","Money_Order");//Money_Order as place holder %%Money Order%%
	if($payment_modules->selected_module =='moneyorder'){
	$merge_details[ORDR_MOP]=MODULE_PAYMENT_MONEYORDER_INFO;
	} else {
	$merge_details[ORDR_MOP]="";}
	//Paypal Message
	define("ORDR_PPM","Paypal_Message");//Money_Order as place holder %%Paypal_Message%%
	if($payment_modules->selected_module =='paypalapi'){
	$merge_details[ORDR_PPM]=MODULE_PAYMENT_PAYPAL_API_INFO;
	} else {
	$merge_details[ORDR_PPM]="";}
	
	//Shipping Will Call
	define("ORDR_WCWC","Willcall Message");//
	if($shipping['id']=='willcall_willcall'){
	$merge_details[ORDR_WCWC]=MODULE_SHIPPING_WILLCALL_TEXT_MESSAGE;
	} else {
	$merge_details[ORDR_WCWC]="";}
	
	$merge_details[ORDR_PO]=$products_ordered;

	if(!isset($_COOKIE['customer_is_guest']))
	{ //email for PWA		
	$downloads_query=tep_db_query("select opd.*,op.products_name from " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd," . TABLE_ORDERS_PRODUCTS . " op where opd.orders_products_id=op.orders_products_id and opd.orders_id='" . (int)$insert_id . "'");
	$download_content="<table>";
	if(tep_db_num_rows($downloads_query)>0)
		$download_content.='<tr class="textTableContent"><td class="textMain" colspan="10">' .TEXT_DOWNLOAD_LINK . '</td></tr>';
    while ($downloads = tep_db_fetch_array($downloads_query)) 
	{
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
	}else
	{
	$merge_details[TEXT_DL]='';}//end PWA
//==============================================

	$order_totals_str="<table border='0' cellpadding='2' cellspacing='0' width='100%'>";
	
	for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) 
	{
		if ($order->totals[$i]['code']=='ot_total')
		{
			$class="textTotal";
		} else if ($i%2==0)
		{
			$class="textTableTotalE";
		} else {
			$class="textTableTotalO";
		}
		if($order->totals[$i]['code']=='ot_tax')
		{
			$order_totals_str.= '<tr class="textMain" height="21"><td class="' . $class . '" align="right">' . EMAIL_TEXT_TAX . '</td><td class="' . $class . '" align="right" width="100">' . strip_tags($order->totals[$i]['text']) . "</td>";
		}else if($order->totals[$i]['code']=='ot_shipping')
		{
			$order_totals_str.= '<tr class="textMain" height="21"><td class="' . $class . '" align="right">' . EMAIL_TEXT_SHIPPING . '</td><td class="' . $class . '" align="right" width="100">' . strip_tags($order->totals[$i]['text']) . "</td>";
		}else if($order->totals[$i]['code']=='ot_total')
		{
			$order_totals_str.= '<tr class="textMain" height="21"><td class="' . $class . '" align="right">' . EMAIL_TEXT_TOTAL . '</td><td class="' . $class . '" align="right" width="100">' . strip_tags($order->totals[$i]['text']) . "</td>";
		}else if($order->totals[$i]['code']=='ot_subtotal')
		{
			$order_totals_str.= '<tr class="textMain" height="21"><td class="' . $class . '" align="right">' . EMAIL_TEXT_SUBTOTAL . '</td><td class="' . $class . '" align="right" width="100">' . strip_tags($order->totals[$i]['text']) . "</td>";
		} else 
		{
			$order_totals_str.= '<tr class="textMain" height="21"><td class="' . $class . '" align="right">' . $order->totals[$i]['title'] . '</td><td class="' . $class . '" align="right" width="100">' . strip_tags($order->totals[$i]['text']) . "</td>";
		}
	}
	$order_totals_str.="</table>";
			//if new field is in use as salutation
			//$extra_customer_query=tep_db_query("select * from customers_extra_info  where customers_id='" . (int)$FSESSION->get('customer_id') . "'");		
		//	while ($extra_customer_result=tep_db_fetch_array($extra_customer_query)){
		//	$title=$extra_customer_result['fieldvalue'];
		//	}
	define("BILL_NA","Billing_Name");
	define("ORDR_INTRO","Intro");
	define("ORDR_MOP","Money_Order");
	
	//Text Strings for the email template
	$merge_details[TEXT_INV_ON]=EMAIL_TEXT_ORDER_NUMBER;//Order Number
	$merge_details[TEXT_INV_DEAR]=TEXT_DEAR;// Dear
	$merge_details[TEXT_INV_THANKS_PURCHASE]=TEXT_THANKS_PURCHASE;//Thanks for...
	$merge_details[TEXT_INV_DD]=TEXT_DELIVERY_DETAILS;//Delivery Details
	$merge_details[TEXT_INV_ADDRESS]=TEXT_ADDRESS;
	$merge_details[TEXT_INV_TELEPHONE]=TEXT_TELEPHONE;
	$merge_details[TEXT_INV_EMAIL]=TEXT_EMAIL;
	$merge_details[TEXT_INV_PD]=TEXT_PAYMENT_DETAILS;
	$merge_details[TEXT_INV_PM]=EMAIL_TEXT_PAYMENT_METHOD;
	$merge_details[TEXT_INV_PRODUCTS]=EMAIL_TEXT_PRODUCTS;
	$merge_details[TEXT_INV_WITH_THANKS]=TEXT_WITH_THANKS;
	$merge_details[TEXT_INV_THANKS]=TEXT_THANKS_FOR;



	//sakwoya order_totals_str is OK
	$merge_details[ORDR_OT]=$order_totals_str;
	//customer details
	$merge_details[CUST_CF]=$order->customer['firstname'];
	$merge_details[CUST_CL]=$order->customer['lastname'];
	$merge_details[CUST_AU]=$order->customer['username'];
	$merge_details[CUST_CM]=$order->customer['company'];
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
	$merge_details[BILL_NA]=$title . ' ' . $order->billing['firstname'] . ' ' . $order->billing['lastname'];
	$merge_details[BILL_CM]=$order->billing['company'];
	$merge_details[BILL_CT]=$order->billing['street_address'];
	$merge_details[BILL_CS]=$order->billing['suburb'];
	
	$merge_details[BILL_BE]=$order->billing['customer_email'];
	
	$merge_details[BILL_CC]=$order->billing['city'];
	$merge_details[BILL_CP]=$order->billing['postcode'];
	$merge_details[BILL_CE]=$order->billing['state'];
	$merge_details[BILL_CU]=$order->billing['country'];
	//$merge_details[BILL_CU]=($order->billing['country_id'] > 0)?(get_order_countryname($order->billing['country_id'])):$order->billing['country']['title'];

	//Delivery details
	$merge_details[DELI_NA]=$title . ' ' . $order->delivery['firstname'] . ' ' . $order->delivery['lastname'];
	$merge_details[DELI_CM]=$order->delivery['company'];
	
	$merge_details[DELI_DE]=$order->delivery['customer_email'];
	
	$merge_details[DELI_CT]=$order->delivery['street_address'];
	$merge_details[DELI_CS]=$order->delivery['suburb'];
	$merge_details[DELI_CC]=$order->delivery['city'];
	$merge_details[DELI_CP]=$order->delivery['postcode'];
	$merge_details[DELI_CE]=$order->delivery['state'];
	$merge_details[DELI_CU]=$order->delivery['country'];
	//$merge_details[DELI_CU]=($order->delivery['country_id'] > 0)?(get_order_countryname($order->delivery['country_id'])):$order->delivery['country']['title'];
	$merge_details[ORDR_PM]=$order->info['payment_method'];

	$merge_details['Store_Link']='<a href="' . tep_href_link(FILENAME_DEFAULT) . '" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . STORE_OWNER . '</a>';
	$merge_details['Telephone']=$order->customer['telephone'];
	if(!defined('TICKET_LINK_TEXT'))define('TICKET_LINK_TEXT', 'Collect your tickets here');
//Ticket Link for email receipt
	if(!isset($_COOKIE['customer_is_guest'])){ //email for PWA

			// }else{
				 $merge_details['Order_Link']=''.TICKET_LINK_TEXT.'<a href="'.tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . '" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . CLICK_HERE.'</a>';

	}else{

				 $merge_details['Order_Link']='';

	}
		$merge_details['Store_Logo']='<img src="' . tep_href_link(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/' .COMPANY_LOGO) . '" title="' . STORE_NAME . '">';
		$merge_details['Store_Name']=STORE_NAME;
		$merge_details['Store_Address']=STORE_NAME_ADDRESS;
		$send_details[0]['to_name']=$order->customer['firstname'] . ' ' . $order->customer['lastname'];
		$send_details[0]['to_email']=$order->customer['email_address'];
		$send_details[0]['from_name']=STORE_OWNER;
		$send_details[0]['from_email']=STORE_OWNER_EMAIL_ADDRESS;

				if (SEND_EXTRA_ORDER_EMAILS_TO != '') 
				{
				$send_details[1]['to_name']="";
				$send_details[1]['to_email']=SEND_EXTRA_ORDER_EMAILS_TO;
				$send_details[1]['from_name']=STORE_OWNER;
				$send_details[1]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
			}


		{ 
		//extra code to create pdf and attach
        $filename="";
				
	if(EMAIL_QUICK_ORDER_UPDATE=='true')
	{ 
		try
		  {
		      require_once('includes/functions/products_ticket.php');
			  //$filename= (create_checkout_pdf($insert_id));
			  $filename= (DIR_FS_CATALOG.create_checkout_pdf($insert_id));
			  }
			  
		 //catch exception
           catch(Exception $e)
              {
               // exit( 'Message: ' .$e->getMessage());
             }	
		if(EMAIL_PDF_DELIVERED_ONLY=='false')
		{
			$filename="";
		}
		tep_send_default_email("PRD",$merge_details,$send_details,$filename);
	//	tep_send_default_email("OSU",$merge_details,$send_details); 
		}
	}	
		
}

//end function
### season tickets 

          
        $oID = $insert_id;//tep_db_prepare_input($_GET['oID']);
        $status = '3';//tep_db_prepare_input($_POST['status']);
		
			   ### season tickets 
	  try {
	    if(function_exists('update_season_queue')){
			update_season_queue((int)$oID, $status );			
			}
		} catch (Exception $e) {
			unset($e);
		}
?>

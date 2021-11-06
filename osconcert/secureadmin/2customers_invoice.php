<?php
/*

  Freeway eCommerce from ZacWare
  http://www.openfreeway.org

  Copyright 2007 ZacWare Pty. Ltd

  Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
  require('includes/application_top.php');
  include(DIR_WS_CLASSES . 'order.php');
  $customer_number_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($FGET['orders_id'])) . "'"); 
  $customer_number = tep_db_fetch_array($customer_number_query);
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
 	$dis_time_format="";
	if(defined('TIME_FORMAT')) {
		$dis_time_format=TIME_FORMAT;
	}
	$oID = $FREQUEST->getvalue('oID');
	$order = new order($oID);
	
	// get the comments
		$fields_query=tep_db_query("SELECT comments,field_1,field_2,field_3,field_4,other from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id='" . (int)$oID . "' order by date_added desc limit 1");
		$fields="";
		$fields_result=tep_db_fetch_array($fields_query);
		$comments=$fields_result["comments"];
		$field_1=$fields_result["field_1"];
		$field_2=$fields_result["field_2"];
		$field_3=$fields_result["field_3"];
		$field_4=$fields_result["field_4"];
		$other=$fields_result["other"];
		
		//print_r ($fields_result);
	
	$merge_details=array();
	$merge_details[TEXT_NO]=$oID;
	$merge_details[TEXT_OL]='<a href="'.tep_catalog_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL', false).'" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">'."Order Invoice Link".'</a>';
//	$merge_details[TEXT_OP]=$order->info['date_purchased'];
	
 if (EXTRA_FIELDS == 'yes'){
	 
	//add extra fields (may 2012)
	$extra_fields_str = '<span class="smallText"><b><i>' . NEW_FIELDS_HEADING . '</i></b>' . NOTE . '</span><table width="80%" border="0" cellspacing="0" cellpadding="5"><tr><td class="main" width="15%">' .FIELD_1. '&nbsp;</td><td class="main">' .$field_1. '</td></tr><tr><td class="main">' .FIELD_2. '&nbsp;</td><td class="main">' .$field_2. '</td></tr><tr><td class="main">' .FIELD_3. '&nbsp;</td><td class="main">' .$field_3. '</td></tr><tr><td class="main">' .FIELD_4. '&nbsp;</td><td class="main">' .$field_4. '</td></tr><tr><td class="main">' .FIELD_5. '&nbsp;</td><td class="main">' .$other. '</td></tr></table>';
	$merge_details[TEXT_XF]=$extra_fields_str;
	}
	//eof

	
	$format_date = format_date($order->info['date_purchased']);
	$default_format = explode("-",strtolower(EVENTS_DATE_FORMAT));
	$format_date = explode("-",$format_date);
	$formated_date = array();
	for($i=0;$i<count($default_format);$i++)$formated_date[$default_format[$i]] = $format_date[$i];
	$merge_details[TEXT_OP]=date('l d F, Y',mktime(0,0,0,$formated_date['m'],$formated_date['d'],$formated_date['y']));
	//$merge_details[TEXT_OP]=utf8_encode(strftime('%A %B %d, %Y', strtotime($order->info['date_purchased'])));
	//strftime(DATE_FORMAT_LONG);
	$merge_details[TEXT_OM]=$order->info['comments'];
	$merge_details[TEXT_FN]=$order->customer['firstname'];
	$merge_details[TEXT_LN]=$order->customer['lastname'];
	$merge_details[TEXT_PM]= $order->info['payment_method'];
	$merge_details[LOG_IP]=$order->info['ip_address'];
	$merge_details[TEXT_SM] =STORE_NAME;
	$merge_details[STORE_AD] =str_replace("\n","<br>",STORE_NAME_ADDRESS);
	// to find the events present in the order;
	$session_ids='';
	//print_r($merge_details);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE . ' - ' . TITLE_PRINT_ORDER . ' #' . $oID; ?></title>
<link rel="stylesheet" type="text/css" href="print.css">
<link rel="stylesheet" type="text/css" href="includes/events.css">
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
<!-- body_text //-->
<table border="0" width="100%" align="center" cellpadding="2" cellspacing="0">
	<tr> 
		<td valign="top" align="left" class="main"><script language="JavaScript">
			if (window.print) {
			document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo IMAGE_BUTTON_PRINT; ?></a></center>');
			}
			else document.write ('<h2><?php echo IMAGE_BUTTON_PRINT; ?></h2>')
			</script>
		</td>
		<td align="right" valign="bottom" class="main"><p align="right" class="main"><a href="javascript:window.close();"><?php echo '[Close]';?></a></p></td>
	</tr>
  <tr>
  	<td colspan="2">
		<?php echo tep_draw_separator('pixel_trans.gif',1,10);?>
	</td>
  </tr>
  <tr>
  	<td colspan="2" class="main">
	<?php 
		$products_ordered='<table border="0" cellspacing=0 cellpadding=0 width="100%">';
		$products_ordered.='<tr><td><table border=0 cellspacing=0 cellpadding=0 width="100%">';
		$products_ordered.="<tr height='20'>";
		//include headings
	/*	$products_ordered.="<td style='font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold' width='50'>" . TABLE_HEADING_QTY;
		$products_ordered.="<td style='font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold'>" . TABLE_HEADING_PRODUCTS;
		$products_ordered.="<td style='font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold'>" . TABLE_HEADING_SKU;
		$products_ordered.='<td style="font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold" align="right" width="100">' . TABLE_HEADING_TOTAL_INCLUDING_TAX;*/
		$product_type="";
			$products_name="";
			//$products = "";
			$products_names_p="";
			$pre_qty="";
			$pre_name="";
			$qty_p = "";
			$pre_sign="";
			$first_event = true;
			$first_event_id = 0;
			$jj=0;
			$attendees_name="";
			
			for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
				$products_names_p="";
				$qty_p = "";
				$sign_p="";
				$model="";
	 
				 if (isset($order->products[$i]['others']) && (sizeof($order->products[$i]['others']) > 0)) {
					$disp_date=$order->products[$i]['others']['end_date'];
					$disp_time=$order->products[$i]['others']['end_time'];
					if($disp_date && $disp_time){
						$date_time=strtotime($disp_date.' '.$disp_time)+60;
						$disp_date=date('Y-m-d',$date_time); 
						$disp_time=date('h:i A',$date_time); 
					}
				 }
				 if($order->products[$i]['others']['start_time'] || $order->products[$i]['others']['end_time']) {
					 $disp_stime=date('h:i A',strtotime($order->products[$i]['others']['start_time']));
					if($dis_time_format!="") {
						if($dis_time_format=='24') {
							$disp_time=date('H:i',$date_time); 
							$disp_stime=date('H:i',strtotime($order->products[$i]['others']['start_time']));
						}	
					}
				}
  
				  if($order->products[$i]['products_type']=="P"){ 
				  $products_names_p = $order->products[$i]['name'];
				  $qty_p=$order->products[$i]['qty'];
				  $sign_p="X";
				  	  if($order->products[$i]['model'])
					  	$model= " " . $order->products[$i]['model'];
				  }
				
				$rax_total=tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'];
				$sku = $order->products[$i]['products_sku'];
				$event_id = 0;

				$products[] = array( "qty" => $qty_p,
							   		 "sign"  => $sign_p,
									 "id" => $order->products[$i]['id'],
							  		 "products_type" => $order->products[$i]['products_type'],
							  		 "p_name"	 => $products_names_p,
							  		 "event_id" => $event_id,
							  		 "p_attributes1" => $products_ordered_attributes1, 
							  		 "p_attributes" => $products_ordered_attributes,
							  		 "model" => $model,
							  		 //"total" => $total,
							  		 "sku" => $sku,
							 	     "row_total"=>$rax_total
								   ); 
		   }

		$date_keys = array_keys($date);
		$date_keys_length = count($date_keys);
		for($i=0;$i<$date_keys_length;$i++){
			$final_date='';
			$var_date = $date[$date_keys[$i]];
			$date_split = explode(',',$var_date);
			$date_split_count = count($date_split);
			
			for($j=0;$j<$date_split_count;$j++){
				$final_date .= format_date($date_split[$j]) .',';
				$m=$j+1;
				//if($m%4==0)	$final_date = substr($final_date,0,-1). ',' . '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			   if($m%4==0)	$final_date = substr($final_date,0,-1) . '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			$date[$date_keys[$i]] = substr($final_date,0,-1);
		} 
		$event_id = 0;$flagp=0;$flage=0;$flagv=0;$flags=0;
		for ($i = 0, $n = sizeof($products); $i < $n; $i++) {
		
	   		 if($products[$i]['products_type']=="P"){
				$row_total= $products[$i]['row_total'];			
				$date=$products[$i]['model'];
			}

			if($event_id!=$products[$i]['event_id'] || $products[$i]['products_type']!='E'){

			 
			$model = "";

			if(($products[$i]['products_type']=="P")){
			     if($products[$i]['model']!="")
			       $model = $products[$i]['model'];	
				   //$attendees_name=""; 
			}
			$discount='';
			if(($products[$i]['products_type']=='P')){
				$discount=(isset($order->products[$i]['discount_whole_text']) && $order->products[$i]['discount_whole_text']!='')?'<br>' . $order->products[$i]['discount_whole_text']:'';
			}
			
			$event_id = $products[$i]['event_id'];
			$heading_name = $order->products[$i]['categories_name'];
			$heading_venue = $order->products[$i]['concert_venue'];
			$heading_date = $order->products[$i]['concert_date'];
			$heading_time = $order->products[$i]['concert_time'];
			$products_ordered.='<tr height="20"  style="background:#ECE9D8";>';
			if(($products[$i]['products_type']=='P')&&($flagp==0)){
				$products_ordered.="<td style='font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold' width='50'>" . TABLE_HEADING_QTY;
				$products_ordered.="<td style='font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold'>" . TABLE_HEADING_PRODUCTS."</td>";
				$products_ordered.="<td style='font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold'></td>";
				$products_ordered.='<td style="font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold" align="right" width="100">' . TABLE_HEADING_TOTAL_INCLUDING_TAX."</td></tr>";
		     $flagp=1;
			}
			$products_ordered.='<tr height="20" >';
			$products_ordered.='<td style="font-family:Arial, Helvetica, sans-serif;font-size:12px;" valign="top" align="left">' . "&nbsp;" . $products[$i]['qty'] . "&nbsp;&nbsp;" . $products[$i]['sign'] .'&nbsp;</td>';

			// products listed here
			$products_ordered.='<td style="font-family:Arial, Helvetica, sans-serif;font-size:12px;" valign="top" align="left">' . $products[$i]['p_name'] .  $products[$i]['p_attributes1'] . $products[$i]['p_attributes']. ' ['. $model . ']  ' . $discount .  ' ' .$heading_name . '   ' . $heading_venue . '  ' . $heading_date . '  ' . $heading_time . "</td>";
	
			$products_ordered.='<td style="font-family:Arial, Helvetica, sans-serif;font-size:12px;" valign="top" width="20"></td>';
			$products_ordered.='<td style="font-family:Arial, Helvetica, sans-serif;font-size:12px;" align="right" valign="top">' . $currencies->format($row_total, true, $order->info['currency'], $order->info['currency_value']) . '</td>';
			$products_ordered.='</tr>';
			
		  }
	  } 
	  for ($i = 0, $n = sizeof($products); $i < $n; $i++) {  
	   		if ($products_type!="M"){ 
		 		if ($products_type=='')
					$products_type=$products[$i]['products_type'];
			  			 else if ($products_type!=$products[$i]['products_type'])
							  $products_type="M";
						}

		}
	
	$products_ordered .='</table></td></tr><tr height="10"><td></td></tr>';
	$products_ordered .='<tr><td><table width="100%" cellspacing=0 cellpadding="0" border="0"><tr><td><table border=0 cellspacing=0 align="right" cellpadding="0" width="100%">';

	$pending_period=0;
	$fetch_id=0;

	$order_totals_str="";
	$titles=array('ot_subtotal'=>INVOICE_SUBTOTAL,'ot_shipping'=>INVOICE_SHIPPING,'ot_total'=>INVOICE_TOTAL);
	for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
		if ($order->totals[$i]['class']!="ot_total"){
			$order_totals_str .= '<tr height="20"><td style="font-family:Arial, Helvetica, sans-serif;font-size:12px;" align="right">' . (isset($titles[$order->totals[$i]['class']])?$titles[$order->totals[$i]['class']]:strip_tags($order->totals[$i]['title'])) . '<td style="font-family:Arial, Helvetica, sans-serif;font-size:12px;" width="80" align="right">' . strip_tags($order->totals[$i]['text']) . "</td><td width='2'></td></tr>"; 
		} else {
			$order_totals_str .= '<tr height="20" style="background:#FF7300;"><td style="font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#FFFFFF;" align="right"><b>' . (isset($titles[$order->totals[$i]['class']])?$titles[$order->totals[$i]['class']]:strip_tags($order->totals[$i]['title'])) . '</b><td style="font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#FFFFFF;" align="right"><b>' . strip_tags($order->totals[$i]['text']) . "</b></td><td width='2'></td></tr>"; 
		}
		//$order_totals_str.="<tr height='3'><td></tr>";
	}
	$products_ordered.=$order_totals_str .'</table></td></tr></table></td></tr></table>';

    $merge_details[TEXT_PO]=$products_ordered;
	
		
	$check_billing_query = tep_db_query("
	                      SELECT customers_country, customers_language, billing_email, billing_name
	                      FROM " . TABLE_ORDERS . " 
						  WHERE orders_id = '" . (int)$oID . "'");
						  
    $check_billing = tep_db_fetch_array($check_billing_query); 

	// get the shipping and billing address
	$order->billing['format_id']=1;
	$merge_details[ORDR_BA]=tep_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>');
	$order->delivery['format_id']=1;
	if(($order->delivery['format_id'])>0){
		$merge_details[ORDR_SA]=tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>');
	}else{
		$merge_details[ORDR_SA]=tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>');
	}
		
	$payment_detail="";
	$note="";

	
	//$store_address=str_replace("\n",', ',STORE_NAME_ADDRESS);
	if ($products_type=='P' && $order->info['status_id']==3) {// product and delivered
		$payment_detail=PAYMT_PRODUCT_C;
	} else if ($products_type=='P' && $order->info['status_id']==2) {// product and processed
		$payment_detail=PAYMT_PRODUCT_R;
	} else if ($products_type=='P'&& $order->info['status_id']==1) {// product and pending
		$payment_detail=sprintf(PAYMT_PRODUCT_P,STORE_NAME,STORE_NAME_ADDRESS);
	} 
	$merge_details[PAYMT_DT]='';
	if($check_billing['customers_country']=="Box Office"){
		$merge_details["Phone"]=$order->billing["company"];
		$merge_details["Email_Address"]=$check_billing['billing_email'];
	}else{
		$merge_details["Phone"]=$order->customer["telephone"];
		$merge_details["Email_Address"]=$order->customer["email_address"];
	}
	
	
	require(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/payment/bank_transfer.php');
	//Bank Transfer Payment - bank_transfer.php
	//Bank Deposit Message as place holder %%Bank_Deposit_Message%%
	define("ORDR_BDM","Bank_Deposit_Message");
	if($order->info['payment_method']=='Bank Transfer Payment'){
	
	$merge_details[ORDR_BDM]=MODULE_BANK_TRANSFER_INFO;
	}else{
	$merge_details[ORDR_BDM]="";
	}
	$customers_language=$order->customer['language'];
	include(DIR_WS_LANGUAGES . $customers_language . '/templates.php');
	
	$merge_details[TEXT_INV_ON]=TEXT_MAIL_ORDER_NUMBER;//Order Number
	$merge_details[TEXT_INV_DEAR]=TEXT_DEAR;// Dear
	$merge_details[TEXT_INV_THANKS_PURCHASE]=TEXT_THANKS_PURCHASE;//Thanks for...
	$merge_details[TEXT_INV_DD]=TEXT_DELIVERY_DETAILS;//Delivery Details
	$merge_details[TEXT_INV_ADDRESS]=TEXT_ADDRESS;
	$merge_details[TEXT_INV_TELEPHONE]=TEXT_TELEPHONE;
	$merge_details[TEXT_INV_EMAIL]=TEXT_EMAIL;
	$merge_details[TEXT_INV_PD]=TEXT_PAYMENT_DETAILS;
	$merge_details[TEXT_INV_PM]=TEXT_PAYMENT_METHOD;
	$merge_details[TEXT_INV_PRODUCTS]=TEXT_TICKETS;
	$merge_details[TEXT_INV_WITH_THANKS]=TEXT_WITH_THANKS;
	$merge_details[TEXT_INV_THANKS]=TEXT_THANKS_FOR;
	//$merge_details['Text_Regards']=TEXT_REGARDS;
	
	$merge_details["Store_Logo"]=tep_image(DIR_WS_TEMPLATES.DEFAULT_TEMPLATE.'/'.DIR_WS_IMAGES . COMPANY_LOGO, STORE_NAME,'','');

	$mail_data_query=tep_db_query("SELECT * from " . TABLE_EMAIL_MESSAGES . " where message_type='MIV'");

	if (tep_db_num_rows($mail_data_query)>0){
		
		$mail_data_result=tep_db_fetch_array($mail_data_query);
		
		//get message content
		$mes_content=$mail_data_result['message_text'];

		reset($merge_details);
	   //replace the merge details with the fields in content
	   while (list($key, $value) = each($merge_details))
		  $mes_content=str_replace("%%" . $key  . "%%",$value,$mes_content);
		  
	   //$mes_content=str_replace("\n","<br>",$mes_content);
	   
	   echo $mes_content;
	   
	} else {
		echo TEXT_NO_CONTENT;
	} 
	if ($note!=""){
		echo '<span class="smallText"><b><i>'  .TEXT_NOTE . '</i></b>' . $note . '</span>';
	}
?>
	</tr>
</table>
<!-- body_text_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
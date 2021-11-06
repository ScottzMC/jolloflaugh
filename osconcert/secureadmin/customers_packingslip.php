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

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = $FREQUEST->getvalue('oID');
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");

  include(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);
  $merge_details=array();
	$merge_details[TEXT_NO]=$oID;
	$merge_details[TEXT_OL]='<a href="'.tep_catalog_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL', false).'" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">'."Order Invoice Link".'</a>';
	$merge_details[TEXT_OP]=strftime(DATE_FORMAT_LONG);
	$merge_details[TEXT_OM]=tep_output_string($order->info['comments']);
	$merge_details[TEXT_FN]=$order->customer['firstname'];
	$merge_details[TEXT_LN]=$order->customer['lastname'];
	$merge_details[TEXT_PM]=$order->info['payment_method'];
	$merge_details[TEXT_SM]=STORE_NAME;
	$merge_details[STORE_AD] =str_replace("\n","<br>",STORE_NAME_ADDRESS);
	$merge_details[TEXT_EA]=$order->customer['email_address'];
	$merge_details[TEXT_SA]=$order->customer['street_address'];
	$merge_details[CUST_CM]=$order->customer['company'];
	$merge_details[CUST_CO]=$order->customer['telephone'];
	$merge_details[TEXT_SU]=$order->customer['suburb'];
	$merge_details[TEXT_PC]=$order->customer['postcode'];
	$merge_details[TEXT_CT]=$order->customer['city'];
	$merge_details[CUST_CE]=$order->customer['state'];
	$merge_details[TEXT_CY]=$order->customer['country'];
	/*$merge_details[TEXT_PN]=$order->products[0]['products_name']; 
	$merge_details[TEXT_PP]=$currencies->format($order->products[0]['price']); 
	$merge_details[TEXT_P_A]=$order->products[0]['model']; 
	$merge_details[TEXT_P_Q]=$order->products[0]['qty']; 
	$merge_details[TEXT_P_STATUS]=$order->info['orders_status'];*/ 
	$merge_details[TEXT_OT]=$currencies->format($order->info['total']); 
	$merge_details[TEXT_SN]=STORE_OWNER; 
	$merge_details[TEXT_SE]=STORE_OWNER_EMAIL_ADDRESS; 
	$merge_details[TEXT_AL]=HTTP_SERVER . DIR_WS_ADMIN;

	$merge_details[BILL_NA]=$order->billing['name'];
	$merge_details[BILL_CT]=$order->billing['street_address'];
	$merge_details[BILL_CM]=$order->billing['company'];
	$merge_details[BILL_CS]=$order->billing['suburb'];
	$merge_details[BILL_CC]=$order->billing['city'];
	$merge_details[BILL_CP]=$order->billing['postcode'];
	$merge_details[BILL_CE]=$order->billing['state'];
	$merge_details[BILL_CU]=$order->billing['country'];
	
	$merge_details[DELI_NA]=$order->delivery['name'];
	$merge_details[DELI_CT]=$order->delivery['street_address'];
	$merge_details[DELI_CM]=$order->delivery['company'];
	$merge_details[DELI_CS]=$order->delivery['suburb'];
	$merge_details[DELI_CC]=$order->delivery['city'];
	$merge_details[DELI_CP]=$order->delivery['postcode'];
	$merge_details[DELI_CE]=$order->delivery['state'];
	$merge_details[DELI_CU]=$order->delivery['country'];
	
	if($order->info['payment_method']=='Bank Transfer Payment')
		$direct_deposit=sprintf(TEXT_DIRECT_DEPOSIT, $order->info['reference_id']);
	else 
		$direct_deposit='';
		
	$merge_details[TEXT_DD]=$direct_deposit;
	$merge_details[TEXT_PF]=str_replace("\n","<br>",$order->info['payment_info']);
	 
	
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE . ' - ' . TITLE_PRINT_ORDER . ' #' . $FGET['order_id']; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="print.css">
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
<?php 
	$mail_data_sql = "SELECT * from " . TABLE_EMAIL_MESSAGES . " where message_type='PSP'";
	$mail_data_query=tep_db_query($mail_data_sql);
	$no_contents = false;
	if (tep_db_num_rows($mail_data_query)>0){
		$mail_data_result=tep_db_fetch_array($mail_data_query);
	//get message content
		$mes_content=$mail_data_result['message_text'];
		if(strip_tags($mes_content)==''){
			$no_contents = true;
		}else{
			$no_contents = false;
		}
	} else {
		$no_contents = true;
	}
	if($no_contents){
	?>

	<tr align="left"> 
		<td class="titleHeading"><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
	</tr>
	<tr> 
		<td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
			<tr> 
				<td><table border="0" align="center" width="75%" cellspacing="0" cellpadding="0">
					<tr> 
						<td class="pageHeading"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
						<td class="pageHeading" align="right"><?php 
						//echo tep_image('admin/'.DIR_WS_IMAGES . COMPANY_LOGO, STORE_NAME);
					      echo tep_image(DIR_WS_TEMPLATES.DEFAULT_TEMPLATE.'/'.DIR_WS_IMAGES . COMPANY_LOGO, STORE_NAME,'','');
						//echo tep_image(DIR_FS_TEMPLATES.DEFAULT_TEMPLATE.'/'.DIR_WS_IMAGES . 'yogababy_logo_small.png','434','119');
                        ?></td>
					</tr>
					<tr> 
						<td colspan="2" align="center" class="titleHeading"><b><?php echo 'Order#' . $FGET['oID']; ?></b></td>
					</tr>
					<tr align="left"> 
						<td colspan="2" class="titleHeading"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
				</table></td>
			</tr>
		</table></td>
	</tr>
	<tr> 
		<td align="center"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr> 
			<td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor=#000000>
				<tr> 
					<td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
						<tr class="dataTableHeadingRow"> 
							<td class="dataTableHeadingContent"><b><?php echo ENTRY_SOLD_TO; ?></b></td>
						</tr>
						<tr class="dataTableRow"> 
							<td class="dataTableContent"><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '&nbsp;', '<br>'); ?></td>
						</tr>
						<tr class="dataTableRow"> 
							<td class="dataTableContent"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
						</tr>
						<tr class="dataTableRow"> 
							<td class="dataTableContent"><?php echo '&nbsp;<b>Telephone#</b>' . '<br>&nbsp;' . $order->customer['telephone']; ?></td>
						</tr>
						<tr class="dataTableRow"> 
							<td class="dataTableContent"><?php echo '&nbsp;<b>eMail Address:</b>' . '<br>&nbsp;' . $order->customer['email_address']; ?></td>
						</tr>
					</table></td>
				</tr>
			</table></td>
			<td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor=#000000>
				<tr> 
					<td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
						<tr class="dataTableHeadingRow"> 
							<td class="dataTableHeadingContent"><b><?php echo ENTRY_SHIP_TO; ?></b></td>
						</tr>
						<tr class="dataTableRow"> 
							<td class="dataTableContent"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '&nbsp;', '<br>'); ?></td>
						</tr>
					</table></td>
				</tr>
			</table></td>
		</tr>
		</table></td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
		<td><table border="0" cellspacing="0" cellpadding="2">
			<tr>
				<td class="dataTableHeadingContent"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
				<td class="dataTableContent"><?php echo $order->info['payment_method']; ?></td>
			</tr>
		</table></td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="1" bgcolor=#000000>
			<tr>
				<td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr class="dataTableHeadingRow"> 
					<td class="dataTableHeadingContent" width=15% align="left" valign="top">
						<?php 
							echo tep_draw_separator('pixel_trans.gif','20','5');
							//echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
							//echo '&nbsp;&nbsp;&nbsp;&nbsp;';
							echo TABLE_HEADING_QUANTITY; ?></td>
					<td class="dataTableHeadingContent" width=60% align="left" valign="top">		
						<?php	echo tep_draw_separator('pixel_trans.gif','20','5');
						       //echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
							    echo TABLE_HEADING_PRODUCTS;
						?>
					</td>
					<td class="dataTableHeadingContent" width=30% align="center" valign="top"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
					</tr>
			<?php
		
				for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
				     if($order->products[$i]['products_type']=="P"){
					 
					 if(get_discount_details($FGET['oID'],$order->products[$i]['id'])){
						$discount=  '<br>' . '<b>'. get_discount_details($FGET['oID'],$order->products[$i]['id']); 
						}
				  echo '      <tr class="dataTableRow">' . "\n" .
					   '        <td class="dataTableContent" valign="top" align="center" width="10%">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
					   '        <td class="dataTableContent" valign="top" align="left" width="10%">' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $order->products[$i]['name'] .$discount;
			
				  if (sizeof($order->products[$i]['attributes']) > 0) {
					for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
					  echo '<br><nobr><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
					  echo '</i></small></nobr>';
					}
				  }
					
				  echo '        </td>' . "\n" .
					   '        <td class="dataTableContent" valign="top" width="10%">' . $order->products[$i]['model'] . '</td>' . "\n" .
					   '      </tr>' . "\n";
				} 
				
				
			}	
			?>
					</table></td>
			  </tr>
		</table></td>
  </tr>

	<?php 
		}else{
			$products_ordered='<tr><td colspan="2" class="main"><table border="0" width="70%" cellspacing="0" cellpadding="1" bgcolor=#000000>'.
									'<tr>' .
										'<td><table border="0" width="100%" cellspacing="0" cellpadding="2">' .
											'<tr class="dataTableHeadingRow">
												<td class="dataTableContent" align="right" valign="top">'.TEXT_QTY.'</td>' .
												'<td class="dataTableHeadingContent">' . TABLE_HEADING_PRODUCTS . '</td>' .
												'<td class="dataTableHeadingContent">' . TABLE_HEADING_PRODUCTS_MODEL . '</td>' .
							 				'</tr>';
			for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
			  		$products_ordered.='<tr class="dataTableRow">'  .
									    '<td class="dataTableContent" valign="top" align="right" width="40">' . $order->products[$i]['qty'] . '&nbsp;x</td>'  .
										'<td class="dataTableContent" valign="top" width="450">' . $order->products[$i]['name'];
		
			  if (sizeof($order->products[$i]['attributes']) > 0) {
				for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
				  $products_ordered.='<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
								  	  '</i></small></nobr>';
				}
			  }
		
			  $products_ordered.=' </td>'  .
								   '<td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' .
								   '</tr>' ;
			}
		
			$products_ordered.='</table></td>'.
							  '</tr>' .
							'</table></td></tr>';
						   $merge_details[TEXT_PO]=$products_ordered;		
		  
	  // get the shipping and billing address
		$address='<Tr><td><table border="0" cellpadding="1" cellspacing="0" class="tableArea"><tr class="dataTableHeadingRow"><td class="dataTableHeadingContent">';
		$address.=ENTRY_SOLD_TO . '</td></tr><tr class="dataTableRow"><td class="dataTableContent">';
		$address.=tep_address_format($order->customer['format_id'], $order->customer, 1, '&nbsp;', '<br>');
		if($order->customer['telephone']!="")
			$address.='<tr class="dataTableRow"> <td class="dataTableContent">&nbsp;<b>Telephone#</b><br>&nbsp;' . $order->customer['telephone'] . '</td></tr>';
		$address.='<tr class="dataTableRow"> <td class="dataTableContent">&nbsp;<b>eMail Address:</b>' . '<br>&nbsp;' . $order->customer['email_address'] . '</td></tr>';
		$address.='</td></tr></table>';
		$merge_details[ORDR_BA]=$address;
		$address_label=tep_address_format($order->delivery['format_id'], $order->delivery, 1, '&nbsp;', '<br>');
		$address="";
		if ($address_label!=""){
			$address='<table border="0" cellpadding="1" cellspacing="0" class="tableArea"><tr valign="top" class="dataTableHeadingRow"><td class="dataTableHeadingContent">';
			$address.=ENTRY_SHIP_TO . '</td></tr><tr class="dataTableRow"><td class="dataTableContent">';
			$address.=$address_label;
			$address.='</td></tr></table></td></tr>';
		}
		$merge_details[ORDR_SA]=$address;	
		$payment_detail="";
		$note="";
		
		//$store_address=str_replace("\n",', ',STORE_NAME_ADDRESS);
		if ($products_type=='P' && $order->info['status_id']==3) {// product and delivered
			$payment_detail=PAYMT_PRODUCT_C;
		} else if ($products_type=='P' && $order->info['status_id']==2) {// product and processed
			$payment_detail=PAYMT_PRODUCT_R;
		} else if ($products_type=='P'&& $order->info['status_id']==1) {// product and pending
			$payment_detail=sprintf(PAYMT_PRODUCT_P,STORE_NAME,STORE_NAME_ADDRESS);
		} else if ($products_type=='M' && $order->info['status_id']==1) { // event and pending
			$payment_detail=sprintf(PAYMT_MIXED_P,STORE_NAME,STORE_NAME_ADDRESS,$pending_period);
			$note=PAYMT_EVENT_NOTE_P;
		}
		$merge_details[PAYMT_DT]=$payment_detail;
		
		reset($merge_details);
		//FOREACH
		//replace the merge details with the fields in content
		//while (list($key, $value) = each($merge_details))
		foreach($merge_details as $key => $value)	
			$mes_content=str_replace("%%" . $key  . "%%",$value,$mes_content);
		echo $mes_content;

	}
?>
</table>
<!-- body_text_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

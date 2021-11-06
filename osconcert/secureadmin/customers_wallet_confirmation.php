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
	require(DIR_WS_CLASSES . 'payment.php');
	require(DIR_WS_CLASSES . 'currencies.php');
	// check for the customer id 
	$cID=$FSESSION->get('upload_id','int',0);
    $wallet_amount=$FREQUEST->postvalue('wallet_amount','int',0);
    $payment=$FREQUEST->postvalue('payment','','');
	
	// redirect to the customers page
	if ($cID==0) {
		tep_redirect(tep_href_link(FILENAME_CUSTOMERS));
	}
  
	//if (!tep_session_is_registered('wallet_amount')) tep_session_register('wallet_amount');
	//if (!tep_session_is_registered('payment')) tep_session_register('payment');
	//if (!tep_session_is_registered('comments')) tep_session_register('comments');


	// get the values from the payment page
	$FSESSION->set('wallet_amount',$FREQUEST->postvalue('wallet_amount'));
	$FSESSION->set('payment', $FREQUEST->postvalue('payment'));

	if (tep_not_null($FPOST['comments'])) {
		$FSESSION->set('comments',$FREQUEST->postvalue('comments'));
	}
	
	// check for the wallet amount
	if( $wallet_amount=="" || ((float)$wallet_amount<=0))  {
		tep_redirect(tep_href_link(FILENAME_WALLET_PAYMENT, 'error_message=' .urlencode(ERROR_NO_PAYMENT_MODULE_UPLOADS)));
	}
	
	$payment_modules=new payment($payment);
	
	// check for the payment
	if ((is_array($payment_modules->modules)) && (sizeof($payment_modules->modules) > 1) && (!is_object($GLOBALS[$FSESSION->payment]))) {
		tep_redirect(tep_href_link(FILENAME_WALLET_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED)));
	}
	  
	
	// perform a confirmation  
	if (is_array($payment_modules->modules)) {
		$payment_modules->pre_confirmation_check();
	}


	$currencies=new currencies();
	// get the customer address details	
	$customer_details_query = tep_db_query("select c.customers_id,c.customers_default_address_id, a.entry_firstname as firstname, a.entry_lastname as lastname, a.entry_company as company, a.entry_street_address as street_address, a.entry_suburb as suburb, a.entry_city as city, a.entry_postcode as postcode, a.entry_state as state, a.entry_zone_id as zone_id, a.entry_country_id as country_id from ". TABLE_ADDRESS_BOOK . " a, " . TABLE_CUSTOMERS . " c  where c.customers_id = " .(int)$cID . " and c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id");
	$customer_details = tep_db_fetch_array($customer_details_query);
	$firstname=$lastname=$streets=$citypost=$state=$country="";
	
	$label='$firstname $lastname<br>$streets<br>$city $postcode<br>$statename$country';
	$firstname=$customer_details["firstname"];
	$lastname=$customer_details["lastname"];
	$street=$customer_details["street_address"];
	$suburb=$customer_details["suburb"];
	$postcode=$customer_details["postcode"];
	$city=$customer_details["city"];
	$state=$customer_details["state"];
	$country=tep_get_country_name($customer_details['country_id']);

	if (isset($customer_details['zone_id']) && tep_not_null($customer_details['zone_id'])) {
		$state = tep_get_zone_code($customer_details['country_id'], $customer_details['zone_id'], $state);
	}
	$streets=$street;
	if ($suburb!="") $streets=$street . "<br>" . $suburb;
	if ($state!="") $statename=$state . ',';
	
	// print the string
	eval("\$address = \"$label\";");

// BOF: Lango Added for template MOD

?><!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/wallet.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
<tr> 
	<!-- body_text //-->	  
    <td width=100% align=left valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
	<tr>
	<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>
	  <tr>
	  	<td>
		<table cellpadding="0" cellspacing="0" width="60%" border="0" >
			<tr>
    		    <td>
				<table border="0" width="100%" cellspacing="1" cellpadding="2">
					<tr>
						<td width="30%" valign="top">
						<table border="0" width="100%" cellspacing="1" cellpadding="2">
							<tr>
								<td class="main"><?php  echo '<b>' . HEADING_UPLOAD_TO . '</b> '; ?></td>
								<td class="main"><?php echo '<b>' . HEADING_UPLOAD_AMOUNT . '</b> ' ?><?php echo $currencies->format($wallet_amount) .'  <a href="' . tep_href_link(FILENAME_WALLET_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
							</tr>
							<tr>
								<td class="main"><?php echo $address;?></td>
								<td></td>
							</tr>
						</table>
						</td>				
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
			</tr>
			  <tr>
				<td><table border="0" width="100%" cellspacing="1" cellpadding="2">
				  <tr >
					<td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
					  
					  <tr>
						<td class="main"><?php echo '<b>' . HEADING_PAYMENT_METHOD . '</b>'; ?></td>
					  </tr>
					  <tr>
						<td>
						<table cellpadding="0" cellspacing="0" border="0">
						<tr><td width="10%">&nbsp;</td><td class="main">
						<?php echo $$payment->title; ?></td></tr>
						</table></td>
					  </tr>
					</table>
				  </tr>
				</table></td>
			  </tr>
			<?php
			// draw the form
			
			// BOF: Lango modified for print order mod
			  if (is_array($payment_modules->modules)) {
				if ($confirmation = $payment_modules->confirmation()) {
				  $payment_info = $confirmation['title'];
				  $FSESSION->set('payment_info',$payment_info);
				 // if (!tep_session_is_registered('payment_info')) tep_session_register('payment_info');
			// EOF: Lango modified for print order mod
			?>
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
			  </tr>
			  <tr>
				<td><table border="0" width="100%" cellspacing="1" cellpadding="2">
				  <tr >
					<td><table border="0" cellspacing="0" cellpadding="2">
					<tr >
						<td class="main" colspan="4"><b><?php echo HEADING_PAYMENT_INFORMATION; ?></b></td>
					  </tr>
					  <tr>
						<td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
					  </tr>
					  <tr>
						<td class="main" colspan="4">
						<table cellpadding="0" cellspacing="0" border="0">
						<tr><td>&nbsp;</td><td class="main">
						<?php echo $confirmation['title']; ?></td></tr></table></td>
					  </tr>
					  <tr>
						<td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
					  </tr>
		<?php
			  for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
		?>
					  <tr>
						<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
						<td class="main"><?php echo $confirmation['fields'][$i]['title']; ?></td>
						<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
						<td class="main"><?php echo $confirmation['fields'][$i]['field']; ?></td>
					  </tr>
					  
		<?php
			  }
		?>
					</table></td>
				  </tr>
				</table></td>
			  </tr>
		<?php
			}
		  }
		?>
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
			  </tr>       
<?php
  if (tep_not_null($comments)) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
		  <tr>
			<td class="main"><?php echo '<b>' . HEADING_WALLET_COMMENTS . '</b> <a href="' . tep_href_link(FILENAME_WALLET_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
		  </tr>
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="4">
              <tr>
                <td class="main"><?php echo nl2br(tep_output_string_protected($comments)); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php  } ?>		  
	</table>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="60%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right" class="main">
				<?php
				
					// draw the output form
					$temp_array=array('info'=>array('total'=>$wallet_amount));
					$order=new objectInfo($temp_array);
					
					// create a random value for internal checking
					$validID=tep_create_random_value(5,'digits');
					$payment_params='validID=' . $validID;
					if (isset($$payment->form_action_url)) {
						echo '<form name="checkout_confirmation" action="' . $$payment->form_action_url . '" method="post">';
					} else {
						echo '<form name="checkout_confirmation" action="' . tep_href_link(FILENAME_WALLET_PROCESS,$payment_params) . '" method="post">';
					}
					//if (!tep_session_is_registered('checkID')) tep_session_register('checkID');
					$FSESSION->set('checkID',$validID);
				
					
					if (is_array($payment_modules->modules)) {
						echo $payment_modules->process_button();
					}
					
					echo '<a href="' . tep_href_link(FILENAME_WALLET_PAYMENT) . '">' . tep_image_button('button_back.gif',IMAGE_BACK) . '</a>&nbsp;';
					echo tep_image_submit('button_confirm.gif', IMAGE_CONTINUE);
					echo '<form>';
				?>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
    </table>
	</td></tr>
	</table>
	</table>
	<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php   require(DIR_WS_INCLUDES . 'application_bottom.php');?>
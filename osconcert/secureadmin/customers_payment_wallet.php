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
	require(DIR_WS_CLASSES . 'payment.php');

	$payment_modules = new payment;
	$currencies = new currencies();
	include(DIR_WS_CLASSES . 'order.php'); 

	// check for the customer id in input or session
	$cID=$FREQUEST->getvalue('cID','int',0);
	
	if ($cID==0) $cID=$FSESSION->get('upload_id','int',0);
	
	// redirect to the customers page
	if ($cID==0) {
		tep_redirect(tep_href_link(FILENAME_CUSTOMERS));
	}
	$wallet_timestamp=time();
	$FSESSION->set('wallet_timestamp',$wallet_timestamp);
 	 //if (!tep_session_is_registered('wallet_timestamp')) tep_session_register('wallet_timestamp');

//	if (!tep_session_is_registered('upload_id')) tep_session_register('upload_id');
	
	// check for the payment page
	//if (!tep_session_is_registered('payment_page')) tep_session_register('payment_page');
	
	$FSESSION->set('upload_id',$cID);
	$FSESSION->set('payment_page',"wallet");
	
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
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/wallet.js"></script>
<script language="javascript" src="includes/general.js"></script>
<link href="includes/stylesheet.css" rel="stylesheet" type="text/css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body -->
<table cellpadding="2" cellspacing="2" border="0" width="100%">
  <tr> 
<!-- body_text //-->

    <td width="100%" valign="top">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <?php  
			  $error_message=$FREQUEST->getvalue('error_message');
			  if ($error_message!=''){ ?>
          	<tr>
		  		<td colspan="2" valign="top" >
					<table width="100%" cellpadding="2" cellspacing="0" border="0">
						<tr><td class="main" ><font color=red><?php echo $error_message; ?></font>
					</table>	
				</td>
			</tr>
			<tr>
	<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
		  <?php  } ?>
		  
		<?php 
		if (isset($FGET['payment_error']) && is_object(${$FGET['payment_error']}) && ($error = ${$FGET['payment_error']}->get_error())) {?>
        <tr><td>
 	  <table border="0" width="100%" cellspacing="0" cellpadding="0">
	    <tr><td align=left class='pageHeading' colspan='2'><?php echo tep_output_string_protected($error['title']); ?></td></tr>
	  </table></td></tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" bgcolor="#ff6c6c">
          <tr class="infoBoxNoticeContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="100%" valign="top"><font color=black><?php echo $error['error']; ?></font></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
     
<?php } ?>
		      <tr>
        		<td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
	    	      <tr>
    	    	    <td class="pageHeading"><?php echo HEADING_TITLE; ?> <?php  ?></td>
            		<td class="pageHeading" align="right"><?php  echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
		            <td class="pageHeading" align="right"><?php ?></td>
        		  </tr>
				
		        </table></td>
			  </tr>

			   
			   <tr><?php echo tep_draw_form('checkout_payment', FILENAME_WALLET_CONFIRMATION,'','post') ; ?>
    		    <td>
					<table cellpadding="2" cellspacing="0" border="0"><tr><td class="formAreaTitle">
					</td></tr></table>	
				</td>
   			  </tr>
			  <tr>
			  	 <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>	
			  </tr>
			  <?php tep_content_title_top(WALLET_FUNDS); ?>
		      <tr>
			  <td  valign="top" >
			  	<table  border="0" cellpadding="2" cellspacing="0" width="60%">
				
					<tr><td valign="top">
			  		<table border="0" width="100%" cellspacing="0" cellpadding="2"  >
	    		   	  <tr>
					  	 <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>	
					  </tr>
					 <tr>
					 	<td ><?php echo tep_draw_separator('pixel_trans.gif', '7', '1'); ?></td>	
					 	 <td class="main" valign="top" width="150"><?php echo '<b>' . WALLET_UPLOAD_TO . '</b>' ; ?></td>
						 	<td class="main" align="left"><?php	 echo $address;?></td>	 
						</tr>
					 <tr>
					     <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '3'); ?></td>
				     </tr>	  
          			  <tr>
					  	<td><?php echo tep_draw_separator('pixel_trans.gif', '7', '1'); ?></td>	
					   	<td class="main" align="left"><?php echo '<b>' . WALLET_AMOUNT . '</b>' ?></td>
						<td class="main" align="left"><?php echo  tep_draw_input_field('wallet_amount',($FSESSION->get('wallet_amount','int')>0)?$FSESSION->get('wallet_amount','int',0):'','maxlength=12 size=13')?></td>
					  </tr>
					   <tr>
					  	 <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>	
					  </tr>
					</table>
				</td></tr>
				
				
				</table>
				
				
				</td>
			   </tr>
			   <?php tep_content_title_bottom(); ?>
			   
				<?php tep_content_title_top(WALLET_PAYMENT_METHOD); ?>
			   <tr>
        <td><table border="0" width="60%" cellspacing="1" cellpadding="2" >
		 
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2"  >
			   
			  <!-- begins payment blocks -->
			 
<?php
  $selection = $payment_modules->selection();
  $payment=tep_not_null($FSESSION->get('payment'))?$FSESSION->get('payment'):'';
   if (sizeof($selection) > 1) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><b><?php //echo TEXT_SELECT_PAYMENT_METHOD; ?></b></td>
                <td class="main" width="50%" valign="top" align="right"><b><?php echo TITLE_PLEASE_SELECT; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
  } else {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="100%" colspan="2"><?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
  }
$flag='B';
  $radio_buttons = 0;
  $cnt=1;
  
  for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
  
  
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php 
	if (!($selection[$i]['id']=='wallet' || $selection[$i]['id']=='internal')){
    if ( ($selection[$i]['id'] == $payment) || ($n == 1) ) 
		 echo '<tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ',\'', $selection[$i]['id'] . '\')">' . "\n";
     else 
		  echo '<tr id="defaultunSelected" class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ',\'', $selection[$i]['id'] . '\')">' . "\n";
	$radio_buttons++;
?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><b><?php echo $selection[$i]['module']; ?></b></td>
                    <td class="main" align="right">
<?php
    if (sizeof($selection) > 1) {
      echo tep_draw_radio_field('payment', $selection[$i]['id'],($FSESSION->get('payment')==$selection[$i]['id'])?true:false);
    } else {
      echo tep_draw_hidden_field('payment', $selection[$i]['id'],tep_not_null($FSESSION->get('payment'))?$FSESSION->get('payment'):'');
    }
    echo "<input type=hidden name=check_payment value='" . $selection[$i]['module'] . "'>";
?>
                    </td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
 <?php
    if (isset($selection[$i]['error'])) {
?>
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="4"><?php echo $selection[$i]['error']; ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
    } elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
	 ?>
		 <tr id="<?php echo $selection[$i]['id'];?>" <?php echo ($payment!=$selection[$i]['id']?'style="display:none"':'');?>>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td colspan="4"><table border="0" cellspacing="0" cellpadding="2">
<?php     for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) { ?>
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
<?php    } ?>
                    </table></td></tr>
               <tr><td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
  		  }
	}
?>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
			  
<?php
    //$radio_buttons++;
    $cnt++;
  }
?>
            </table></td>
          </tr>
		  <?php tep_content_title_bottom(); ?>
        </table></td>
      </tr>
	  <tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
	 </tr>
	 <script language="javascript">
		previousPaymentSelected="<?php echo $payment;?>";
	</script>
      
			  <!-- ends payment blocks -->
	<!-- begins comments block -->
	 <?php tep_content_title_top(TEXT_ENTER_COMMENTS); ?>
	<tr>
        <td><table border="0" width="60%" cellspacing="2" cellpadding="2" > 
          <tr >
		     <td><table border="0" width="100%" cellspacing="2" cellpadding="2" >
              <tr>
                <td><?php echo tep_draw_textarea_field('comments', 'soft', '80', '5',tep_not_null($FSESSION->get('comments'))?$FSESSION->get('comments'):''); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
	
	<?php tep_content_title_bottom(); ?>
	<!-- end comments block -->
	
	<tr>
		<td>
			<table cellpadding="2" cellspacing="0" width="60%">
			<?php //echo "<input type='hidden' name='confirm' value='confirm'>";
?>
				<tr><td><?php echo tep_image_submit('button_continue.gif', IMAGE_CONTINUE,'align="right"'); ?></td></tr>
			</table>
		</td>
	</tr>		  
	  </form>
		   
		</table>
	</td>		   		
  </tr>
 </table>
			  
		
<!-- Begin Addresses Block -->
  <?php 
  // set action depend on step value
  

// footer 
 require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>


<?php require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
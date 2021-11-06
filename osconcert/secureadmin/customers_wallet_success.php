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
 
 	// check for the customer id 
	$cID=$FSESSION->get('upload_id','int',0);

	if ($cID==0) {
		tep_redirect(tep_href_link(FILENAME_CUSTOMERS_MAINPAGE));
	}
	
	$insert_id=$FREQUEST->getvalue('id','int',0);
	

	$currencies=new currencies();  
  	$upload_query=tep_db_query("SELECT payment_status,amount from " . TABLE_WALLET_UPLOADS . " where wallet_id='" . (int)$insert_id . "'");
	$upload_result=tep_db_fetch_array($upload_query);
?><!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
<tr> 
	<!-- body_text //-->	  
    <td width=100% align=left valign="top">
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
			</tr>
        </table>
		</td>
      </tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>
	  <tr>
	  	<td>
		<table cellpadding="0" cellspacing="0" width="60%" border="0">
			<tr>
    		    <td>
				<table border="0" width="100%" cellspacing="1" cellpadding="2">
					<tr>
						<td width="30%" valign="top">
						<table border="0" width="100%" cellspacing="1" cellpadding="2">
							<tr>
								<td class="main">
									<?php echo TEXT_SUCCESS; ?><br><br>
								</td>
							</tr>
							<?php if ($upload_result['payment_status']<=1) { ?>
								<tr>
									<td class="smallText"><?php echo sprintf(TEXT_CREDIT,$currencies->format($upload_result["amount"])); ?><br><br>
									</td>
								</tr>
							<?php } ?>
							<tr>
								<td class="main">
									<?php 
										$balance=tep_get_wallet_balance($cID); 
										echo sprintf(TEXT_WALLET_BALANCE,$currencies->format($balance));
									?>
								</td>
							</tr>
						</table>
						</td>				
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
				<td class="main">
					<?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_MAINPAGE) . '">' . tep_image_button('button_continue.gif',IMAGE_CONTINUE) . '</a>'; ?>
				</td>
			</tr>
	</table>
	</td>
	</tr>
	</table>
	<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php   require(DIR_WS_INCLUDES . 'application_bottom.php');?>
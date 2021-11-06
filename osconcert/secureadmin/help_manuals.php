<?php 

/*

  Copyright cartZone UK www.osconcert.com

  Released under the GNU General Public License
*/
    define('_FEXEC',1);
	require('includes/application_top.php');
	require(DIR_WS_INCLUDES.'/tweak/general.php');
	frequire($FSESSION->language.'/help_manuals.php',RLANG);
	$languages = tep_get_languages();
	tep_get_last_access_file();
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0"  marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="javascript:pageLoaded();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<!-- body_text //-->
	<tr class="dataTableHeadingRow">
		<td valign="top">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
				<td class="main">
					<b><?php echo HEADING_TITLE;?></b>
				</td>
				
		</tr>
		
		</table>
		</td>
	</tr>
	<tr height="20" id="messageBoard" style="display:none">
		<td id="messageBoardText">
		</td>
	</tr>
	<tr>
		<td class="main" id="msr-1message">
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">	
			<tr>
				<td id="msrtotalContentResult">
	
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="25" valign="bottom">&nbsp;</td>
    <td valign="bottom">&nbsp;</td>
  </tr>
  <tr> 
    <td valign="bottom">&nbsp;</td>
    <td valign="bottom" class="pageHeading"><?php echo HEADING_TITLE_2; ?></td>
  </tr>
  <tr> 
    <td valign="top">&nbsp;</td>
    <td valign="top"><p align="justify"><br>
      <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr> 
          <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="info_content">
              <tr> 
                <td width="86%"><?php echo PDF_HELP; ?></td>
                <td width="14%"><a href="http://www.adobe.com/products/acrobat/readstep2.html" target="_blank"><img src="images/icons/getacro.gif" width="85" height="31" border="0"></a></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <br> 
      <table width="100%" border="0" cellpadding="2" cellspacing="4" class="info_content">
	   <tr> 
          <td width="75%"><strong><?php echo HELP_INFO; ?></strong></td>
          <td width="25%" align="center"></td>
        </tr>
		 <tr> 
          <td width="75%"></td>
          <td width="25%" align="center"></td>
        </tr>
		<tr> 
          <td width="75%">Introduction to osConcert</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=1">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">Getting Started</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=3">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">General Support</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=4">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">Template Set Up</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=11">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">Customer Management</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=12">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">Concert Details</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=13">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">eTickets</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=14">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">General Admission Events</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=15">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">Marketing/Email Templates</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=16">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">Language Support</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=17">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">Order Totals</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=18">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">Orders Explained</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=19">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">Products Explained</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=20">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">Reports Explained</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=21">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">Design Mode</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=22">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">Payment System</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=9">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">Discount Modules</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=10">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">Box Office Method</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=6">VIEW FAQ</a></td>
        </tr>
		<tr> 
          <td width="75%">Barcode Scanning</td>
          <td width="25%" align="center"><a target="_blank" href="https://www.osconcert.com/faq/?action=show&cat=8">VIEW FAQ</a></td>
        </tr>

      </table></p>
      <br> 
      <table width="100%" border="0" cellpadding="2" cellspacing="4"  class="info_content">
        <tr> 
          <td width="75%"><strong><?php echo HEADING_TITLE_3; ?></strong></td>
          <td width="25%" align="center">&nbsp;</td>
        </tr>
		 <tr> 
          <td>GDPR Features Explained</td>
          <td width="18%" align="center"><a target="_blank" href="https://www.osconcert.com/docs/GDPR-features-osconcert-explained.pdf">download 
            pdf</a></td>
        </tr>
        <tr> 
          <td>How to set up a General Admission product</td>
          <td width="18%" align="center"><a target="_blank" href="https://www.osconcert.com/docs/How_To_Set_Up_a_GA_Product_as_a_category_SHOW.pdf">download 
            pdf</a></td>
        </tr>
        <tr> 
          <td>How to set up a General Admission product in SHOW</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/How_To_Set_Up_A_Product_in_a_SHOW.pdf">download 
            pdf</a></td>
        </tr>
        <tr> 
          <td>How to set up a simple SINGLE General Admission product/event (full example guided tutorial)</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/How-To-Create-a-Single-General-Admission-Event.pdf">download 
            pdf</a></td>
        </tr>
        <tr>
          <td>How to set up a TAX for a specific country</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/How_to_set_up_a_TAX_for_a_specific_Country.pdf">download 
            pdf</a></td>
        </tr>
        <tr>
          <td>How to set a Box Office checkout</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/How_to_set_up_Box_Office_checkout.pdf">download 
            pdf</a></td>
        </tr>
		<tr> 
          <td>Scanning QR eTickets Using Smartphone App </td>
          <td width="18%" align="center"><a target="_blank" href="https://www.osconcert.com/docs/Scanning-eTickets-using-a-smartphone-app.pdf">download 
            pdf</a></td>
        </tr>
        <tr>
				<tr> 
          <td>Some Template Options 2020 </td>
          <td width="18%" align="center"><a target="_blank" href="https://www.osconcert.com/docs/osConcert-Bootstrap-4-Template-Options-2020.pdf">download 
            pdf</a></td>
        </tr>
        <tr>
		<tr> 
          <td>About Season Tickets </td>
          <td width="18%" align="center"><a target="_blank" href="http://osconcert.com/docs/season-tickets.pdf">download 
            pdf</a></td>
        </tr>
        <tr>
		<tr> 
          <td>How can osConcert be more GDPR compliant</td>
          <td width="18%" align="center"><a target="_blank" href="https://www.osconcert.com/docs/how-can-osconcert-be-more-GDPR-compliant-25-05-18.pdf">download 
            pdf</a></td>
        </tr>
        <tr>
          <td>How to enable shipping/delivery charge</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/How_To_Enable_Shipping.pdf">download 
            pdf</a></td>
        </tr>
        <tr>
          <td>How to best label your Show for the menu and headings.</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/How-to-best-label-your-show-for-menu-and-headings.pdf">download 
            pdf</a></td>
        </tr>
		<tr>
          <td>How to quickly disable rows of seats.</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/how-to-quickly-disable-rows.pdf">download 
            pdf</a></td>
        </tr>
		<tr>
          <td>How to set a New Zone.</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/How-To-Set-A-New-Zone.pdf">download 
            pdf</a></td>
        </tr>
		<tr>
          <td>More about Customer Info Fields.</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/About-Customer-Info-Fields.pdf">download 
            pdf</a></td>
        </tr>
		<tr>
          <td>How to apply the Restrict to Groups feature.</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/How_To_Apply_the_Restrict_to_Groups_Feature.pdf">download 
            pdf</a></td>
        </tr>
		<tr>
          <td>How to put products 'out of stock'.</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/how-to-put-products-out-of-stock.pdf">download 
            pdf</a></td>
        </tr>
		<tr>
          <td>How to set a company logo and header background.</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/How-to-set-company-logo-and-header-background.pdf">download 
            pdf</a></td>
        </tr>
		<tr>
          <td>How to set up a 'Billing Name' when in Box Office Mode.</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/How-to-set-up-billing-name-for-box-office-staff.pdf">download 
            pdf</a></td>
        </tr>
		<tr>
          <td>How to set up for Multi Language.</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/How-To-Set-Up-Multi-Language.pdf">download 
            pdf</a></td>
        </tr>
      </table>
      </p>
      <br> <table width="100%" border="0" cellpadding="2" cellspacing="4"  class="info_content">
        <tr> 
          <td width="75%"><strong><?php echo HEADING_TITLE_4; ?></strong></td>
          <td width="25%" align="center">&nbsp;</td>
        </tr>
        <tr> 
          <td>About osConcert application security and privacy</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/osConcert-security-and-privacy.pdf">download 
            pdf</a></td>
        </tr>
        <tr> 
          <td>SEO Urls (IMPORTANT: Use for GA General Admission Application Only)</td>
          <td align="center"><a target="_blank" href="https://www.osconcert.com/docs/osConcert_SEO-URLs.pdf">download 
            pdf</a></td>
        </tr>
		        <tr> 
          <td width="75%"><strong><?php echo HEADING_TITLE_1; ?></strong></td>
          <td width="25%" align="center"></td>
        </tr>
        <tr> 
          <td><?php echo HEADING_TITLE_TEXT_1; ?></td>
          <td width="18%" align="center"><a target="_blank" href="https://www.osconcert.com/docs/osconcert-user-manual.pdf">download pdf</a></td>
        </tr>
      </table>
	  <br>
</td>
  </tr>
</table>
<!-- stopprint -->
<br>
<br>

				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr style="display:none">
		<td id="ajaxLoadInfo"><div style="padding:5px 0px 5px 20px" class="main"><?php echo TEXT_LOADING . '&nbsp;' . tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div></td>
	</tr>
	<tr>
		<td id="ajaxLoadImage" style="display:none">
			<?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?>
		</td>
	</tr>
	<form name="fileUpload" id="fileUpload" action="quick_links.php?AJX_CMD=GL_ImageUpload" method="post" enctype="multipart/form-data" style="visibility:hidden;">
		<input type="hidden" name="image_list" id="image_list" value="">
		<input type="hidden" name="image_resize" value="1">
	</form>
	<div class="ajxMessageWindow" id="ajxLoad" style="display:none;width:400px;height:70px;"><span id="ajxLoadMessage">Loading...</span><br><?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div>
</table>
<!-- body_text_eof //-->
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>

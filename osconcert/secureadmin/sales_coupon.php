<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
    define('_FEXEC',1);
	require('includes/application_top.php');
	require(DIR_WS_INCLUDES.'/tweak/general.php');
	frequire('split_page_results_event.php',RCLA);
	frequire($FSESSION->language.'/sales_coupon.php',RLANG);
	frequire($FSESSION->language .'/mail.php',RLANG);
 	require(DIR_WS_CLASSES . 'currencies.php');
 	$currencies = new currencies();
	
	$SALESCOUPON=(new instance)->getTweakObject('display.salesCoupon');
	$SALESCOUPON->pagination=true;
	checkAJAX('SALESCOUPON');	 
	
	$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));
	
	$taxRates=array();
	$tax_class_query = tep_db_query("SELECT tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
	while ($tax_class = tep_db_fetch_array($tax_class_query)) {
		$taxRates[$tax_class['tax_class_id']] = tep_get_tax_rate_value($tax_class['tax_class_id']);
	}
	 
	$jsData->VARS["page"]=array('lastAction'=>false,'opened'=>array(),'locked'=>false,'NUlanguages'=>tep_get_languages(),'imgPath'=>DIR_WS_IMAGES,'taxRates'=>$taxRates,"menu"=>array(),'editorLoaded'=>false,'link'=>tep_href_link('sales_coupon.php'),'NUeditorControls'=>array(),'searchMode'=>false,'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY,'crypted'=>$ENCRYPTED,'alterRows'=>true);
	$jsData->VARS["page"]["template"]=array("MIN_ORDER_MUST_BE_NUMERIC"=>MIN_ORDER_MUST_BE_NUMERIC,
											"VALID_COUPON_NAME"=>VALID_COUPON_NAME,
											"VALID_COUPON_CODE"=>VALID_COUPON_CODE,
											"VALID_MIN_ORDER"=>VALID_MIN_ORDER,
											"COUPON_AMOUNT_REQUIRED"=>COUPON_AMOUNT_REQUIRED,
											"SELECT_CUSTOMERS"=>SELECT_CUSTOMERS,
											"SELECT_SUBJECT"=>SELECT_SUBJECT,
											"SELECT_MESSAGE"=>SELECT_MESSAGE,	
											"ERROR_USES_PER_USER"=>ERROR_USES_PER_USER,
											"UPDATE_DATA"=>TEXT_UPDATE_DATA,
											"TEXT_LOADING"=>TEXT_LOADING_DATA,
											"UPDATE_ORDER"=>TEXT_UPDATE_ORDER,
											"PRD_DELETING"=>TEXT_PRD_DELETING
									);
									
	$jsData->VARS["page"]["NUmenuGroups"]=array("normal","update");
	
	tep_get_last_access_file();
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script language="javascript" src="includes/http.js"></script>
<script type="text/javascript" src="includes/aim.js"></script>
<script type="text/javascript" src="includes/tweak/js/ajax.js"></script>
<script language="JavaScript" src="includes/date-picker.js"></script>
<script type="text/javascript" src="includes/tweak/js/sales_coupon.js"></script>
<?php
	require(DIR_WS_INCLUDES . 'tweak/' . HTML_EDITOR . '.php');
	textEditorLoadJS();
?>
<link href="includes/jquery-ui.css" rel="stylesheet">
<script src="includes/jquery-1.10.2.js"></script>
<script src="includes/jquery-ui.js"></script>
<script language="JavaScript">
   
   jQuery(function() {        
    jQuery( "#txt_start_date" ).datepicker(
        {
            changeMonth: true,
            changeYear: true,
            showOn: 'button',
            buttonImage: 'images/icon_calendar.gif',
            buttonImageOnly: true,
            dateFormat: '<?php $_array=array('d','m','Y');  $replace_array=array('dd','mm','yy'); echo $date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>',
            onClose: function( selectedDate ) {
				$( "#txt_end_date" ).datepicker( "option", "minDate", selectedDate );
			}
        }
    );
    
    jQuery( "#txt_end_date" ).datepicker(
        {
            changeMonth: true,
            changeYear: true,
            showOn: 'button',
            buttonImage: 'images/icon_calendar.gif',
            buttonImageOnly: true,
            dateFormat: '<?php $_array=array('d','m','Y');  $replace_array=array('dd','mm','yy'); echo $date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>',
            onClose: function( selectedDate ) {
				$( "#txt_start_date" ).datepicker( "option", "maxDate", selectedDate );
			}
        }
    );
  });
    
    
var img_src="<?php echo HTTP_SERVER.DIR_WS_ADMIN.'images/';?>";
var selectedTab;
var lang = new Array();
var display_flag=1;
<?php 
 $languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
    	echo "lang[".$i."]=".$languages[$i]['id']."\n";
    }
?>
</script>
<?php include('includes/date_format_js.php'); 
?>
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
				<td class="main" align="right">
					<?php 
						$status_array[] = array('id' => 'Y', 'text' => TEXT_COUPON_ACTIVE);
						$status_array[] = array('id' => 'N', 'text' => TEXT_COUPON_INACTIVE);
						$status_array[] = array('id' => '*', 'text' => TEXT_COUPON_ALL);

						echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', $status_array, '', 'onChange="javascript:list_detail(this.value);"'); 
					?>
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
		<td class="main" id="salCoupon-1message">
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			
			<tr>
				<td id="salCoupontotalContentResult">
					<?php $SALESCOUPON->doItems();?>
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
	<form name="fileUpload" id="fileUpload" action="sales_coupon.php?AJX_CMD=GL_ImageUpload" method="post" enctype="multipart/form-data" style="visibility:hidden;">
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

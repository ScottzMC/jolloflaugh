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
	frequire($FSESSION->language.'/products_featured.php',RLANG);
	
	frequire('currencies.php',RCLA);
	$currencies = new currencies();
	
	
	 $PRODUCTSFEATURED=(new instance)->getTweakObject('display.productsFeatured');
	 $PRODUCTSFEATURED->pagination=True;
	 checkAJAX('PRODUCTSFEATURED');	 
	 
	 $FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));
	 
	$jsData->VARS["page"]=array('lastAction'=>false,'opened'=>array(),'locked'=>false,'NUlanguages'=>$LANGUAGES,'imgPath'=>DIR_WS_IMAGES,"menu"=>array(),'link'=>tep_href_link('products_featured.php'),'searchMode'=>false,'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY,'crypted'=>$ENCRYPTED,'alterRows'=>true);
	$jsData->VARS["page"]["template"]=array("ERR_EXPIRY_DATE"=>ERR_EXPIRY_DATE,
											"ERR_EXPIRE_DATE_LESSTHAN"=>ERR_EXPIRE_DATE_LESSTHAN,
											"ERROR_NUMERIC_VALUE"=>ERROR_NUMERIC_VALUE,
											"UPDATE_IMAGE"=>TEXT_UPDATE_IMAGE,
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
<script language="JavaScript" src="includes/date-picker.js"></script>
<script language="javascript" src="includes/http.js"></script>
<script type="text/javascript" src="includes/aim.js"></script>
<script type="text/javascript" src="includes/tweak/js/ajax.js"></script>
<script type="text/javascript" src="includes/tweak/js/products_featured.js"></script>
<?php require('includes/date_format_js.php');?>
<script language="javascript" type="text/javascript">
var server_date="<?php echo getServerDate();?>"; 	  	    	  	    	  	   	
</script>

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
				<td class="main" width="47%">
					<b><?php echo  HEADING_TITLE;?></b>
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
		<td class="main" id="pfd-1message">
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			
			<tr>
				<td id="pfdtotalContentResult">
					<?php $PRODUCTSFEATURED->doItems();?>
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
	<form name="fileUpload" id="fileUpload" action="products_featured.php?AJX_CMD=GL_ImageUpload" method="post" enctype="multipart/form-data" style="visibility:hidden;">
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

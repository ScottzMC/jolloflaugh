<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	define('_FEXEC',1);
	require('includes/application_top.php');

	require(DIR_WS_INCLUDES . '/tweak/general.php');
	frequire($FSESSION->language.'/products_manufacturers.php',RLANG);

	frequire('currencies.php',RCLA);
	$currencies = new currencies();

	$LANGUAGES=tep_get_languages();

	$PRDMFC=(new instance)->getTweakObject('display.productsManufacturers');
	$PRDMFC->pagination=true;
	checkAJAX('PRDMFC');

	$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));
	$jsData->VARS["page"]=array('lastAction'=>false,
								'opened'=>array(),
								'locked'=>false,
								'NUlanguages'=>$LANGUAGES,
								'imgPath'=>DIR_WS_IMAGES,
								"menu"=>array(),
								'link'=>tep_href_link('products_manufacturers.php'),
								'searchMode'=>false,
								'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY,
								'crypted'=>$ENCRYPTED,
								'alterRows'=>true);
	$jsData->VARS["page"]["template"]=array("ERR_MANUFACTURE_NAME"=>ERR_MANUFACTURE_NAME,
											"ERR_MANUFACTURER_URL_SHOULD_NOT_EMPTY"=>ERR_MANUFACTURER_URL_SHOULD_NOT_EMPTY,
											"ERR_IMAGE_UPLOAD_TYPE"=>ERR_IMAGE_UPLOAD_TYPE,
											"TEXT_LOADING"=>TEXT_LOADING_DATA,
											"ERR_IMAGE_UPLOAD_TYPE"=>ERR_IMAGE_UPLOAD_TYPE,
											"IMAGE_DELETE_MANUFACTURER"=>IMAGE_DELETE_MANUFACTURER
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
			<script type="text/javascript" src="includes/tweak/js/products_manufacturers.js"></script>
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
					<td class="main" id="prdmfc-1message">
					</td>
				</tr>
				<tr>
					<td >
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td id="prdmfctotalContentResult">
								<?php $PRDMFC->doGetManufacturers();?>
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
				<?php drawUploadForm('products_manufacturers.php',1);?>
				<div class="ajxMessageWindow" id="ajxLoad" style="display:none;width:400px;height:70px;"><span id="ajxLoadMessage">Loading...</span><br><?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div>
			</table>	
			<!-- body_eof //-->
			<!-- footer //-->
				<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
			<!-- footer_eof //-->
		</body>
	</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>

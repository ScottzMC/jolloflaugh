<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	define( '_FEXEC', 1 );

	require('includes/application_top.php');
	require(DIR_WS_INCLUDES . '/tweak/general.php');

	frequire(array('categories_description.php'),RFUNC);
	frequire(array($FSESSION->language.'/products_expected.php',$FSESSION->language.'/products_mainpage.php',$FSESSION->language.'/products_create.php'),RLANG);
	frequire('currencies.php',RCLA);
	
	$currencies = new currencies();
	
	$CAT_TREE=array();
	
	$PRDEXP=(new instance)->getTweakObject('display.productsExpected');
	$PRDEXP->pagination=true;
	checkAJAX('PRDEXP');
	
	$taxRates=array();
	$tax_class_query = tep_db_query("SELECT tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
	while ($tax_class = tep_db_fetch_array($tax_class_query)) {
		$taxRates[$tax_class['tax_class_id']] = tep_get_tax_rate_value($tax_class['tax_class_id']);
	}
	
	$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));
	
	$jsData->VARS["page"]=array('lastAction'=>false,
								'opened'=>array(),
								'locked'=>false,
								'NUlanguages'=>tep_get_languages(),
								'imgPath'=>DIR_WS_IMAGES,
								'taxRates'=>$taxRates,
								"menu"=>array(),
								'editorLoaded'=>false,
								'link'=>tep_href_link('products_expected.php'),
								'NUeditorControls'=>array(),
								'searchMode'=>false,
								'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY
								);
	$jsData->VARS["page"]["template"]=array(
  										"CAT_MOVING"=>TEXT_CATEGORY_MOVING,
  										"PBK_OPTION"=>TEXT_PRICE_BREAK_OPTION_TEXT,
										"PBK_ERR_QUAN"=>ERR_PRICE_BREAK_QUANTITY,
										"PBK_ERR_PRICE_LESS"=>ERR_PRICE_BREAK_LESS_PRICE,
										"PBK_ERR_EXISTS"=>ERR_PRICE_BREAK_EXISTS,
										"PBK_ERR_PRICE"=>ERR_PRICE_BREAK_PRICE,
										"ERR_PRODUCT_PRICE"=>ERR_PRODUCT_PRICE,
										"ERR_SPK_NO_USERS"=>ERR_SUPPORTPACK_NO_USERS,
										"ERR_SPK_SELECT_SUBS"=>ERR_SUPPORTPACK_SELECT_SUBS,
										"ERR_SPK_ALREADY_EXISTS"=>ERR_SUPPORTPACK_ALREADY_EXISTS,
										"SPK_OPTION"=>TEXT_SUPPORTPACK_OPTION,
										"ATT_ERR_VALUE_CHANGE"=>ERR_ATTRIB_VALUE_CANNOT_CHANGE,
										"ATT_ERR_QUANTITY"=>ERR_ATTRIB_QUANTITY,
										"ATT_ERR_SKU"=>sprintf(ERR_ATTRIB_SKU,SKU_COUNT),
										"ATT_ERR_STOCK_EXISTS"=>ERR_ATTRIB_STOCK_EXISTS,
										"PRD_MOVING"=>TEXT_PRODUCT_MOVING,
										"PRD_DELETING"=>TEXT_PRODUCT_DELETING,
										"PRD_COPYING"=>TEXT_PRODUCT_COPYING,
										"PRD_ATTRIBUTES_COPYING"=>TEXT_ATTRIBUTES_COPYING,
										"PRD_ERR_NAME_EMPTY"=>ERR_PRODUCT_NAME_EMPTY,
										"PRD_ERR_SELECT_CAT"=>ERR_PRODUCT_SELECT_CATEGORY,
										"PRD_ERR_AUTHOR_EMPTY"=>ERR_PRODUCT_AUTHOR_EMPTY,
										//"PRD_ERR_SUPPORT_PACK"=>ERR_PRODUCT_SUPPORT_PACK,
										"PRD_ERR_DOWNLOAD_LINK"=>ERR_PRODUCT_DOWNLOAD_LINK,
										"PRD_ERR_PRICE_BREAKS_EMPTY"=>ERR_PRODUCT_PRICE_BREAKS_EMPTY,
										"PRD_ERR_ATTR_EMPTY"=>ERR_PRODUCT_ATTRIBUTES_EMPTY,
										"PRD_ERR_ATTR_STOCK"=>ERR_PRODUCT_ATTRIBUTES_STOCK_EMPTY,
										"PRD_ERR_WEIGHT_UNIT"=>ERR_PRODUCT_WEIGHT_UNIT,
										"PRD_ERR_LINKED_SUBS"=>ERR_PRODUCT_LINKED_SUBSCRIPTION,
										"PRD_ERR_IMAGE_TYPES"=>ERR_PRODUCT_IMAGE_TYPES,
										"PRD_ERR_PRICE"=>ERR_PRODUCT_PRICE,
										"PRD_ERR_SUPPORT_SUBS"=>ERR_PRODUCT_SUPPORT_SUBSCRIPTION,
										"PRD_ERR_CURRENT_CATEGORY"=>ERR_PRODUCT_CURRENT_CATEGORY,
										"IN_STOCK"=>TEXT_IN_STOCK,
										"OUT_STOCK"=>TEXT_OUT_STOCK,
  										"UPDATE_IMAGE"=>TEXT_UPDATE_IMAGE,
  										"UPDATE_DATA"=>TEXT_UPDATE_DATA,
										"TEXT_LOADING"=>TEXT_LOADING_DATA,
										"INFO_LOADING_PRODUCTS"=>INFO_LOADING_PRODUCTS,
										"PRD_ERR_QUANTITY"=>ERR_QUANTITY,
										"PRD_ERR_SKU"=>ERR_ATTRIBUTE_SKU_EMPTY
  									);
	$jsData->VARS["page"]["NUmenuGroups"]=array("normal","update");
	$jsData->VARS["page"]["attributesArray"]=array();
	$jsData->VARS["page"]["productAttribute"]=array();
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html <?php echo HTML_PARAMS; ?>>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
			<title><?php echo TITLE; ?></title>
			<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
			<style>
				.stl{
					/*body {background-color: #CEE7A3;*/ 
					scrollbar-shadow-color: #CEE7A3;
					scrollbar-highlight-color: #CEE7A3;
					scrollbar-face-color: #CEE7A3;
					scrollbar-3dlight-color: #CEE7A3;
					scrollbar-darkshadow-color: #CEE7A3;
					scrollbar-track-color: #CEE7A3;
					scrollbar-arrow-color: #CEE7A3;
				}
			</style>
		<script language="javascript" src="includes/general.js"></script>
		<script language="javascript" src="includes/http.js"></script>
		<script language="javascript" src="includes/menu.js"></script>
		<script language="JavaScript" src="includes/date-picker.js"></script>
		<script type="text/javascript" src="includes/aim.js"></script>
		<?php
		require(DIR_WS_INCLUDES . 'tweak/' . HTML_EDITOR . '.php');
		textEditorLoadJS();
		?>
		<script type="text/javascript" src="includes/tweak/js/ajax.js"></script>
		<script type="text/javascript" src="includes/tweak/js/products_expected.js"></script>
		</head>
		<body marginwidth="0"  marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="javascript:pageLoaded();">
			<div id="spiffycalendar" class="text"></div>
			<!-- header //-->
				<?php   require(DIR_WS_INCLUDES . 'header.php');?>
			<!-- header_eof //-->
			<!-- body //-->
			<table border="0" width="100%" cellspacing="2" cellpadding="2">
				<tr height="20" id="messageBoard" style="display:none">
					<td id="messageBoardText"></td>
				</tr>
				<tr>
					<td class="main" id="prdexp-1message"></td>
				</tr>
				<tr>
					<td id="prdexptotalContentResult">
						<?php $PRDEXP->doGetExpectedProducts();?>
					</td>
				</tr>
				<tr style="display:none">
					<td id="ajaxLoadInfo"><div style="padding:5px 0px 5px 20px" class="main"><?php echo TEXT_LOADING . '&nbsp;' . tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div></td>
				</tr>
				<tr style="display:none">
					<td id="previous_menu"></td>
				</tr>
				<tr>
					<td id="ajaxLoadImage" style="display:none">
						<?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?>
					</td>
				</tr>
				<?php drawUploadForm('products_mainpage_new.php',1);?>
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
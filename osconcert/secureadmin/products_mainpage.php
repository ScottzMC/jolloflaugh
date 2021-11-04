<?php
/*


Released under the GNU General Public License

Freeway eCommerce from ZacWare
http://www.openfreeway.org

Copyright 2007 ZacWare Pty. Ltd
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	

	require('includes/application_top.php');
	require(DIR_WS_INCLUDES . '/tweak/general.php');

	frequire(array('categories_description.php'),RFUNC);
	frequire($FSESSION->language.'/products_create.php',RLANG);
	frequire('currencies.php',RCLA);
	
	$currencies = new currencies();
	
	$CAT_TREE=array();
	
	$PRODUCTS=(new instance)->getTweakObject('display.productsMainpage');
	$PRODUCTS->pagination=true;
	checkAJAX('PRODUCTS');
	
	$taxRates=array();
	$tax_class_query = tep_db_query("SELECT tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
	while ($tax_class = tep_db_fetch_array($tax_class_query)) {
		$taxRates[$tax_class['tax_class_id']] = tep_get_tax_rate_value($tax_class['tax_class_id']);
	}
	
	$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));
	
	$jsData->VARS["page"]=array('lastAction'=>false,'opened'=>array(),'locked'=>false,'NUlanguages'=>tep_get_languages(),'imgPath'=>DIR_WS_IMAGES,'taxRates'=>$taxRates,"menu"=>array(),'editorLoaded'=>false,'link'=>tep_href_link('products_mainpage.php'),'NUeditorControls'=>array(),'searchMode'=>false,'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY,'extraParams'=>array());
	$jsData->VARS["page"]["template"]=array(
                                        "CAT_CLONE"=>TEXT_CATEGORY_MOVING,
  										"CAT_MOVING"=>TEXT_CATEGORY_MOVING,
  										"PBK_OPTION"=>TEXT_PRICE_BREAK_OPTION_TEXT,
										"PBK_ERR_QUAN"=>ERR_PRICE_BREAK_QUANTITY,
										"PBK_ERR_PRICE_LESS"=>ERR_PRICE_BREAK_LESS_PRICE,
										"PBK_ERR_EXISTS"=>ERR_PRICE_BREAK_EXISTS,
										"PBK_ERR_PRICE"=>ERR_PRICE_BREAK_PRICE,
                                        "ERR_IMAGE_UPLOAD_TYPE"=>ERR_IMAGE_UPLOAD_TYPE,
										"ERR_PRODUCT_PRICE"=>ERR_PRODUCT_PRICE,
										"ERR_SPK_NO_USERS"=>ERR_SUPPORTPACK_NO_USERS,
										//"ERR_SPK_SELECT_SUBS"=>ERR_SUPPORTPACK_SELECT_SUBS,
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
										"PRD_ERR_SUPPORT_PACK"=>ERR_PRODUCT_SUPPORT_PACK,
										"PRD_ERR_DOWNLOAD_LINK"=>ERR_PRODUCT_DOWNLOAD_LINK,
										"PRD_ERR_PRICE_BREAKS_EMPTY"=>ERR_PRODUCT_PRICE_BREAKS_EMPTY,
										"PRD_ERR_ATTR_EMPTY"=>ERR_PRODUCT_ATTRIBUTES_EMPTY,
										"PRD_ERR_ATTR_STOCK"=>ERR_PRODUCT_ATTRIBUTES_STOCK_EMPTY,
										"PRD_ERR_WEIGHT_UNIT"=>ERR_PRODUCT_WEIGHT_UNIT,
										//"PRD_ERR_LINKED_SUBS"=>ERR_PRODUCT_LINKED_SUBSCRIPTION,
										"PRD_ERR_IMAGE_TYPES"=>ERR_PRODUCT_IMAGE_TYPES,
										"PRD_ERR_PRICE"=>ERR_PRODUCT_PRICE,
										//"PRD_ERR_SUPPORT_SUBS"=>ERR_PRODUCT_SUPPORT_SUBSCRIPTION,
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
	
	/*$category_id=$FREQUEST->getvalue("mPath","int",0);
	if ($category_id==0){
		$category_id=$FREQUEST->getvalue("cID","int",0);
	}
	if ($category_id>0){

		$jsData->FUNCS[]="doDisplayAction({'id':" . $category_id . ",get:'catInfoAndProducts','result':doDisplayResult,'type':'cat','params':'cID=" . $category_id . "','style':'boxLevel1'})";
	}*/
?>
<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
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
	scrollbar-arrow-color: #CEE7A3;}
}
</style>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript" src="includes/http.js"></script>
<script language="javascript" src="includes/menu.js"></script>
<script language="JavaScript" src="includes/date-picker.js"></script>
<script type="text/javascript" src="includes/aim.js"></script>
<script type="text/javascript" src="includes/tweak/js/ajax.js"></script>
<script type="text/javascript" src="includes/tweak/js/products_mainpage.js"></script>


<link href="includes/js/jquery.datetimepicker.css" rel="stylesheet">
<script src="includes/js/jquery.1.0.2.js"></script>
<script src="includes/js/jquery.datetimepicker.full.js"></script>

<script type="text/javascript">
  function handleChange(el)
  {//alert();
   if(el.checked){ 
  		 document.forms.catSubmit.is_ga.value=1;
   		 document.getElementById('hidden_1').style.display='table-row';
		  document.getElementById('hidden_2').style.display='table-row';
   
   };
   if(!el.checked){
   	var answer = confirm("<?php echo GA_ARE_YOU_SURE;?>");
	if (answer){
		 document.getElementById('hidden_1').style.display='none';
		 document.getElementById('hidden_2').style.display='none';
		 document.forms.catSubmit.is_ga.value=0;
	//	 document.forms.catSubmit.quan_left.value='nil';
		  //reset the values to NULL
	}
	else{
		return;
	}
   
   
  
 //  
  }
  }
  </script>
<?php
	require(DIR_WS_INCLUDES . 'tweak/' . HTML_EDITOR . '.php');
	textEditorLoadJS();
?>

</head>
<body marginwidth="0"  marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="javascript:pageLoaded();">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php   
	$file_name=FILENAME_PRODUCTS_MAINPAGE;	
	//echo $file_name;
	//exit;
	require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr height="20" id="messageBoard" style="display:none">
		<td id="messageBoardText">
		</td>
	</tr>
	<tr>
		<td valign="top">
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
			<tr class="dataTableHeadingRow">
				<td valign="top">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td class="main">
							<b><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS;?></b>
						</td>
						<td width="60">
						</td>
						<td width="250" class="smallText">
							<?php echo TEXT_SEARCH . tep_draw_input_field('psearch','','onkeyup="javascript:check_key(event)"').'&nbsp;<a href="javascript:void(0)" onClick="javascript:doProductSearch(\'\');">' . tep_image(DIR_WS_IMAGES . 'icons/bar_search.gif',IMAGE_SEARCH,'','','align=absmiddle') . '</a>';?>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td class="main" id="cat-1message"><!--Add osConcert message-->
            <div class="osconcert_message">
            <?php echo OSCONCERT_MESSAGE_PRODUCTS; ?>
            </div>
            <!--EOF osConcert message-->
		</td>
	</tr>
	<tr>
		<td id="cattotalContentResult">
			<?php $PRODUCTS->doCategories(0);?>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0" style="display:none">
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr class="product_title">
						<td colspan="2"><?php echo '<b>' . TABLE_PRODUCT_MANAGEMENT . '</b>'; ?></td>
					</tr>	
					<tr height="7">
					<tr>
						<td width="7%"><table border="0" border="0" cellpadding="0" cellspacing="0" width="74" height="84">
							<tr> 
								<td width="63" height="74"> 
								<?php echo tep_image(DIR_WS_IMAGES . 'product_mgt.gif',TEXT_PRODUCT_MANAGEMENT);?>
								</td>
							</tr>	
						</table>	
						</td>
						<td width="93%" valign="top">
							<table width="100%" class="info_content">
								<tr><td class="info_content"><?php echo '<a href="' . tep_href_link(FILENAME_FEATURED) . '">' . IMAGE_NEW_FEATURED_PRODUCT . '</a>'; ?></td></tr>
								<tr><td class="info_content"><?php echo '<a href="' . tep_href_link(FILENAME_SPECIALS,'page=1&action=new') . '">' . IMAGE_NEW_SPECIAL_PRODUCT . '</a>'; ?></td></tr>
							</table>
						</td>
					</tr>	
				</table>
				</td>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%" border="0">
					<tr class="product_title">
						<td colspan="2"><?php echo '<b>' .TABLE_PRODUCT_MARKETING . '</b>'; ?></td>
					</tr>	<tr height="7"/>
					<tr>
						<td width="7%"><table border="0" cellpadding="0" cellspacing="0" width="74" height="84">
						<tr> 
								<td> 
							<?php echo tep_image(DIR_WS_IMAGES . 'pdt_marketing.gif', ProductMarketing);?>
							</td></tr>
							</table>
						</td>
						<td width="93%" valign="top">
							<table border="0" class="info_content" >
								<tr><td class="info_content"><?php echo '<a href="' . tep_href_link(FILENAME_SALEMAKER,'top=1') . '">' . IMAGE_DISCOUNTS . '</a>'; ?></td></tr>
								<tr><td class="info_content"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_SALES,'top=1') . '">' . PROMOTE_PRODUCT_SALE . '</a>'; ?></td></tr>
								<tr><td class="info_content"><?php echo '<a href="' . tep_href_link(FILENAME_REVIEWS,'top=1') . '">' . PRODUCTS_REMINDERS . '</a>'; ?></td></tr>
							</table>
						</td>
					</tr>
				</table>
				</td>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%" border="0">
					<tr class="product_title">
						<td colspan="2"><?php echo '<b>' . TABLE_PRODUCT_REPORTS . '</b>'; ?></td>
					</tr>
					<tr height="7">
					<tr>
						<td width="7%"><table border="0" cellpadding="0" cellspacing="0" width="74" height="84">
							<tr> 
							<td> 
							<?php echo tep_image(DIR_WS_IMAGES . 'reports.gif', Reports);?>
							</td></tr>
							</table>
						</td>		
						<td width="93%" valign="top">
                         <?php
                         if($FREQUEST->getvalue('create_category') == 'new')
                         { ?>
                            <script type="text/javascript">
                                setTimeout("new_category()",0);

                            </script>

                         <?php }

                        if($FREQUEST->getvalue('create_product') == 'new')
                        {  ?>
                            <script type="text/javascript">
                                setTimeout("new_product('<?php echo $PRODUCTS->catid;?>')",0);

                            </script>

                             <?php } ?>
							<table  class="info_content">
								<tr><td class="info_content"><?php echo '<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED,'top=1') . '">' . BEST_VIEWED_PRODUCTS . '</a>'; ?></td></tr>
								<tr><td class="info_content"><?php echo '<a href="' . tep_href_link(FILENAME_INVENTORY,'top=1') . '">' . SEE_INVENTORY . '</a>'; ?></td></tr>
								<tr><td class="info_content"><?php echo '<a href="' . tep_href_link(FILENAME_CART_DROP_REPORT,'top=1') . '">' . DROPPED_CART . '</a>'; ?></td></tr>
								<tr><td class="info_content"><?php echo '<a href="' . tep_href_link(FILENAME_REPORTS_FULFILLER,'top=1') . '">' . FULLFILLER . '</a>'; ?></td></tr>
							</table>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		 </table>
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
	<?php drawUploadForm('products_mainpage.php',1);?>
	<div class="ajxMessageWindow" id="ajxLoad" style="display:none;width:400px;height:70px;"><span id="ajxLoadMessage">Loading...</span><br><?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div>
</table>

<script language="JavaScript">
 $('body').on('focus',"#products_date_available", function(){
    $(this).datetimepicker({
	format: 'd-m-Y H:i',
	step:15
});
});



                                                          
  </script>


<!-- body_text_eof //-->
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>

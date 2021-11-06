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

	frequire($FSESSION->language.'/shop_admin_members.php',RLANG);
	frequire('shop_admin_members_general.php',RFUNC);
	
	 	 
	$CMSNEWS=(new instance)->getTweakObject('display.shopAdminMembers');
	$CMSNEWS->pagination=true;
	checkAJAX('CMSNEWS');	 
	
	$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));
	 
	$jsData->VARS["page"]=array('lastAction'=>false,'opened'=>array(),'locked'=>false,'NUlanguages'=>$LANGUAGES,'imgPath'=>DIR_WS_IMAGES,"menu"=>array(),'link'=>tep_href_link('shop_admin_members.php'),'searchMode'=>false,'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY,'crypted'=>$ENCRYPTED,'alterRows'=>true);
	$jsData->VARS["page"]["template"]=array("ERR_CATEGORY_NAME"=>ERR_CATEGORY_NAME,
											"ERR_FIRST_NAME"=>ERR_FIRST_NAME,
											"ERR_LAST_NAME"=>ERR_LAST_NAME,
											"ERR_EMAIL"=>ERR_EMAIL,
											"ERR_INVALID_EMAIL"=>ERR_INVALID_EMAIL,
											"ERROR_ADMIN_GROUP_LENGTH"=>ERROR_ADMIN_GROUP_LENGTH,
											"ERR_EXIST_EMAIL"=>ERR_EXIST_EMAIL,
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
<script language="JavaScript" src="includes/modules/newsdesk/html_editor/jsfunc.js" type="text/javascript"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript" src="includes/http.js"></script>
<script type="text/javascript" src="includes/aim.js"></script>
<script type="text/javascript" src="includes/date-picker.js"></script>
<script type="text/javascript" src="includes/tweak/js/ajax.js"></script>
<script type="text/javascript" src="includes/tweak/js/shop_admin_members.js"></script>
<?php require('includes/password_strength.js.php');?>

<script type="text/javascript">
function update_zone(theForm) {
  var NumState = theForm.sel_zone_id.options.length;
  var SelectedCountry = "";

  while(NumState > 0) {
    NumState--;
    theForm.sel_zone_id.options[NumState] = null;
  }         

  SelectedCountry = theForm.zone_country_id.options[theForm.zone_country_id.selectedIndex].value;

<?php echo tep_js_zone_list('SelectedCountry', 'theForm', 'sel_zone_id'); 
?>

}
function do_pwd_check(obj)
 { 
  <?php //if((MODULE_ADMIN_PASSWORD_STRENGTH=){?>	
 	pwd=obj.value;
	var password=document.getElementById('pwd_str');
	password.style.display="";
	if(pwd.length==0)
	  password.innerHTML='';
	else if(pwd.length<<?php echo ENTRY_PASSWORD_MIN_LENGTH;?>)
		password.innerHTML='<?php echo ENTRY_PASSWORD_STRENGTH_ERROR;?>';
	else if(!check_password_strength(pwd))
		password.innerHTML='<?php echo ENTRY_PASSWORD_STRENGTH_ERROR;?>';
	else
		password.innerHTML='';
   <?php //} ?>	 
 }

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
				<td class="main" width="45%">
					<b><?php echo  HEADING_TITLE;?></b>
				</td>
				<td width="25%" class="main" align="right">
					<b><?php echo HEADING_TITLE_SEARCH .'   '. tep_draw_input_field('Search','','onkeyup="javascript:check_key(event)"').'&nbsp;<a href="javascript:void(0)" onClick="javascript:doSearch(\'\');">' . tep_image(DIR_WS_IMAGES . 'icons/bar_search.gif',IMAGE_SEARCH,'','','align=absmiddle') . '</a>';?></b>
				</td>
				<td width="25%" class="main" align="center">
					<b><?php echo TEXT_JUMP_TO .'   '. tep_draw_pull_down_menu("cbo_jumpto",newsdesk_get_category_tree(),'','onChange="javascript:Category_Open(this.value);"'); ?></b>
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
		<td class="main" id="cfq-1message">
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			
			<tr>
				<td id="cfqtotalContentResult">
					<?php $CMSNEWS->doCategory();?>
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
	<form name="fileUpload" id="fileUpload" action="cms_news.php?AJX_CMD=GL_ImageUpload" method="post" enctype="multipart/form-data" style="visibility:hidden;">
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

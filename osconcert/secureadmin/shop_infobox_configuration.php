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
	frequire($FSESSION->language.'/shop_infobox_configuration.php',RLANG);
	 
	$INFOBOXCONFIG=(new instance)->getTweakObject('display.shopInfoboxConfiguration');
	$INFOBOXCONFIG->pagination=true;
	checkAJAX('INFOBOXCONFIG');	 
	
	$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));
	 
	$jsData->VARS["page"]=array('lastAction'=>false,'opened'=>array(),'locked'=>false,'NUlanguages'=>$LANGUAGES,'imgPath'=>DIR_WS_IMAGES,"menu"=>array(),'link'=>tep_href_link('shop_infobox_configuration.php'),'searchMode'=>false,'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY,'crypted'=>$ENCRYPTED,'alterRows'=>true);
	$jsData->VARS["page"]["template"]=array("TEXT_INFOBOX_HELP_FILENAME"=>TEXT_INFOBOX_HELP_FILENAME,
                                            // "TEXT_INFOBOX_HELP_FILENAME"=>addslashes(TEXT_INFOBOX_HELP_FILENAME),
											//"TEXT_INFOBOX_HELP_HEADING"=>addslashes(TEXT_INFOBOX_HELP_HEADING),
                                             "TEXT_INFOBOX_HELP_HEADING"=>TEXT_INFOBOX_HELP_HEADING,
											//"TEXT_INFOBOX_HELP_DEFINE"=>addslashes(TEXT_INFOBOX_HELP_DEFINE),
                                            "TEXT_INFOBOX_HELP_DEFINE"=>TEXT_INFOBOX_HELP_DEFINE,
											"JS_INFO_BOX_FILENAME"=>addslashes(JS_INFO_BOX_FILENAME),
											"JS_INFO_BOX_TEMPLATE"=>addslashes(JS_INFO_BOX_TEMPLATE),
											//"JS_INFO_BOX_HEADING"=>addslashes(JS_INFO_BOX_HEADING),
                                            "JS_INFO_BOX_HEADING"=>JS_INFO_BOX_HEADING,
											//"JS_BOX_HEADING"=>addslashes(JS_BOX_HEADING),
                                            "JS_BOX_HEADING"=>JS_BOX_HEADING,
											"JS_BOX_COLOR_ERROR"=>addslashes(JS_BOX_COLOR_ERROR),
											"UPDATE_IMAGE"=>TEXT_UPDATE_IMAGE,
											"UPDATE_DATA"=>TEXT_UPDATE_DATA,
											"TEXT_LOADING"=>TEXT_LOADING_DATA,
											"UPDATE_ORDER"=>TEXT_UPDATE_ORDER,
											"PRD_DELETING"=>TEXT_PRD_DELETING
                                           
									);
									
	$jsData->VARS["page"]["NUmenuGroups"]=array("normal","update");
	
	tep_get_last_access_file();
	
	$template = array();
	$templates_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " order by template_id");
		while ($template = tep_db_fetch_array($templates_query)) {
			$template_array[] = array('id' => $template['template_id'],
									'name' => $template['template_name']);
		}
	$template=$template_array;
	$template_array=array();
	for ($i = 0, $n = sizeof($template); $i < $n; $i++) {
		$template_array[] = array('id' => $template[$i]['id'],
								'text' => $template[$i]['name']);
	}	
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
		<style type="text/css">
			#pup {position:absolute; visibility:hidden; z-index:200; width:130; }
		</style>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script language="javascript" src="includes/http.js"></script>
<script type="text/javascript" src="includes/aim.js"></script>
<script type="text/javascript" src="includes/tweak/js/ajax.js"></script>

<script type="text/javascript" src="includes/tweak/js/shop_infobox_configuration.js"></script>
</head>
<body marginwidth="0"  marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="javascript:pageLoaded();">
<script language="javascript" src="includes/popup.js"></script>
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
				<?php 
				$avail_boxes=0;
				if(sizeof($template_array)>0) $gID = $template_array[0]['id'];
				$templates_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " where template_id = " . (int)$gID);
				$template = tep_db_fetch_array($templates_query);
				 if (file_exists(DIR_FS_TEMPLATES.$template['template_name']."/boxes") && ($handle = opendir(DIR_FS_TEMPLATES.$template['template_name']."/boxes"))) {
					/* This is the correct way to loop over the directory. */
						while (false !== ($file = readdir($handle))) { 
							if(is_file(DIR_FS_TEMPLATES .$template['template_name']. '/boxes/' . $file) && stristr($infobox_list.".,..", $file) == FALSE){
								$avail_boxes ++;
							}
						}
						closedir($handle);
					} 
				?>
				<td class="dataTableHeadingContent" align="right"><?php echo TEXT_INFO_TEMPLATE . tep_draw_separator('pixel_trans.gif','10','10') . tep_draw_pull_down_menu('gID', $template_array,  $gID,'onChange="javascript:list_detail(this.value);"'); ?></td>
		</tr>
		
		</table>
		</td>
	</tr>
	
			
	<tr height="20" id="messageBoard" style="display:none">
		<td id="messageBoardText">
		</td>
	</tr>
	<tr>
		<td class="main" id="shInfo-1message">
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			
			<tr>
				<td id="shInfototalContentResult">
					<?php $INFOBOXCONFIG->doItems($gID);?>
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
	<form name="fileUpload" id="fileUpload" action="shop_infobox_configuration.php?AJX_CMD=GL_ImageUpload" method="post" enctype="multipart/form-data" style="visibility:hidden;">
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

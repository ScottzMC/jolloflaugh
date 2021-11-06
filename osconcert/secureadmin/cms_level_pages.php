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

//	frequire(array('categories_description.php','subscription_tree.php'),RFUNC);
//	frequire($FSESSION->language.'/products_create.php',RLANG);
	frequire($FSESSION->language . '/cms_level_pages.php',RLANG);
	frequire('currencies.php',RCLA);
	$page_id=$FREQUEST->getvalue('page_id','int',0);
	$home_page=$FREQUEST->getvalue('home_page');
	
	$currencies = new currencies();
	
	$CAT_TREE=array();
	
	$CMSLEVELPAGES=(new instance)->getTweakObject('display.cmsLevelPages');
	$CMSLEVELPAGES->pagination=true;
	checkAJAX('CMSLEVELPAGES');
	
	$taxRates=array();
	$tax_class_query = tep_db_query("SELECT tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
	while ($tax_class = tep_db_fetch_array($tax_class_query)) {
		$taxRates[$tax_class['tax_class_id']] = tep_get_tax_rate_value($tax_class['tax_class_id']);
	}
	
	$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));
	
	$jsData->VARS["page"]=array('lastAction'=>false,'opened'=>array(),'locked'=>false,'NUlanguages'=>tep_get_languages(),'imgPath'=>DIR_WS_IMAGES,'taxRates'=>$taxRates,"menu"=>array(),'editorLoaded'=>false,'link'=>tep_href_link('cms_level_pages.php'),'NUeditorControls'=>array(),'searchMode'=>false,'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY,'extraParams'=>array());
	$jsData->VARS["page"]["template"]=array("ERR_CAT_NAME_EMPTY"=>ERR_CAT_NAME_EMPTY,
											"ERR_CAT_NAME_EMPTY"=>ERR_CAT_NAME_EMPTY,
											"UPDATE_DATA"=>TEXT_UPDATE_DATA,
											"TEXT_LOADING"=>TEXT_LOADING_DATA,
											"INFO_LOADING_PRODUCTS"=>INFO_LOADING_SUBPAGES
  										);
	$jsData->VARS["page"]["NUmenuGroups"]=array("normal","update");
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
	scrollbar-arrow-color: #CEE7A3;}
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
<script type="text/javascript" src="includes/tweak/js/cms_level_pages.js"></script>
</head>
<body marginwidth="0"  marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="javascript:pageLoaded();">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php   
	$file_name='cms_level_pages.php';	
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
						<td class="main" width="50%">
							<b><?php echo HEADING_TITLE;?></b>
						</td>
					<!--	<td width="10%" align="right">
						<?php
							$popup=array(
										array("img"=>'icon_ascending.gif',"txt"=>TEXT_ASCENDING,"jlink"=>"doPageAction({'id':-1,'type':'mpage','get':'sort','result':doTotalResult,'params':'mode=A','message':'". INFO_SORTING_DATA."'})"),
										array("img"=>'icon_descending.gif',"txt"=>TEXT_DESCENDING,"jlink"=>"doPageAction({'id':-1,'type':'mpage','get':'sort','result':doTotalResult,'params':'mode=D','message':'". INFO_SORTING_DATA."'})"),
										);
							for ($icnt=0,$n=count($popup);$icnt<$n;$icnt++){
								echo '<a style="color:#000000;text-decoration:none" href="javascript:void(0)" onClick="javascript:return ' . $popup[$icnt]["jlink"] . ';">' . tep_image(DIR_WS_IMAGES . "template/" .$popup[$icnt]["img"],$popup[$icnt]["txt"],'','','align=absmiddle') . '</a>&nbsp;';
							}
						?>
						</td> -->
						<td width="28%" class="smallText" align="right">
							<?php echo TEXT_SEARCH . '&nbsp; ' . tep_draw_input_field('psearch','','onkeyup="javascript:check_key(event)"').'&nbsp;<a href="javascript:void(0)" onClick="javascript:doSubpageSearch(\'\');">' . tep_image(DIR_WS_IMAGES . 'icons/bar_search.gif',IMAGE_SEARCH,'','','align=absmiddle') . '</a>';?>
						</td>
						<td class="smallText" width="25%" align="right">
							<?php 
							$jump_array=array();
							$go_to_query=tep_db_query("select mpd.page_id,mpd.page_name from ".TABLE_MAINPAGE." mp, ".TABLE_MAINPAGE_DESCRIPTIONS." mpd where mp.page_id=mpd.page_id and mp.parent_id='0' and mpd.language_id='" . (int)$FSESSION->languages_id . "'");
							while($go_to_array=tep_db_fetch_array($go_to_query)){
							$jump_array[]=array('id'=>$go_to_array['page_id'],'text'=>$go_to_array['page_name']);
							}
							echo HEADING_TITLE_GOTO . '&nbsp;' .tep_draw_pull_down_menu('jump',$jump_array,'',' onChange="javascript: main_page_fetch_details(this.value);" ');
							?>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td class="main" id="mpage-1message">
		</td>
	</tr>
	<tr>
		<td id="mpagetotalContentResult">
			<?php 
			$CMSLEVELPAGES->doMainpage(0);?>
		</td>
	</tr>
	<?php if($home_page) { ?>
	<tr><td><?php echo tep_draw_separator('pixel_trans.gif', '2', '10'); ?></td></tr>	 

							
						    <tr>
							   <td>
								  <table cellpadding="0" cellspacing="0" border="0" width="100%">
									  <tr class="product_title">
										<td colspan="2"><?php echo '<b>' .TEXT_GENERAL . '</b>'; ?></td>
									  </tr>	
									  <tr height="7"></tr>	
									  <tr>
										 <td valign="top" width="5%">
											<table border="0" cellpadding="0" cellspacing="0">
												<tr> 
													<td height="79px"  width="59px" align="center" valign="top"><?php echo tep_image(DIR_WS_IMAGES . 'categories/general.png', General);?></td>
												</tr>
											</table>
										 </td> 
								  
									 	 <td width="95%" valign="top">
								 		 	 <table cellpadding="1" cellspacing="3" border="0" width="100%" class="info_content">
								    			<tr>
													<td class="info_content" colspan="2" ><?php echo '<a href="'. tep_href_link(FILENAME_INFORMATION_PAGES,'top=1').'">'. HEADING_TITLE . '</a>';?></td>
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
			</table>		
		 </td>
	 </tr>
	 <?php } ?>
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
	<?php drawUploadForm('cms_level_pages.php',1);?>
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
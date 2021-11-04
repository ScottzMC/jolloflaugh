<?php

/*

  

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
http://www.zac-ware.com/freeway

Copyright 2007 ZacWare Pty. Ltd
*/
	require('includes/application_top.php');
	$action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
	$page_no = (isset($HTTP_GET_VARS['page']) ? $HTTP_GET_VARS['page'] : '0');	
	$languages = tep_get_languages();
    $server_date = getServerDate(true);	
 if (tep_not_null($action)) {
	switch ($action) {
			case 'new_page':
			case 'edit_page':
					$HTTP_GET_VARS['action']=$HTTP_GET_VARS['action'] . '_ACD';
				break;

			case 'insert_page':
			case 'update_page':
				//PR Algozone: copy image only if modified
					if($HTTP_POST_VARS['parent_id']==1)
						$parent_page=$HTTP_POST_VARS['parent_page'];
					else
						$parent_page=0;		
				
					if (isset($HTTP_GET_VARS['pID'])) $page_id = tep_db_prepare_input($HTTP_GET_VARS['pID']);
					$order_query=tep_db_query("select max(sort_order) as sorder from ".TABLE_MAINPAGE );
					$order_result=tep_db_fetch_array($order_query);				
					if($order_result['sorder']!='')
						$sort_order=$order_result['sorder'] +1;
					else
						$sort_order=1;	
										
						
						$sql_data_array = array('parent_id' => $parent_page);

						if ($action == 'insert_page') {
							$insert_sql_data = array('date_created' => $server_date,
														'sort_order' =>$sort_order,
														'page_status' =>1);
						
							$sql_data_array = array_merge($sql_data_array, $insert_sql_data);
						
							tep_db_perform(TABLE_MAINPAGE, $sql_data_array);
							$page_id = tep_db_insert_id();
						} elseif ($action == 'update_page') {
							$status_sql = "select page_status from " . TABLE_MAINPAGE . " where page_id=" . $page_id;
							$status_query = tep_db_query($status_sql);
							$status_result = tep_db_fetch_array($status_query);
							$update_sql_data = array('date_modified' => $server_date,
														'page_status' =>$status_result['page_status'] );
						
							$sql_data_array = array_merge($sql_data_array, $update_sql_data);
						
							tep_db_perform(TABLE_MAINPAGE, $sql_data_array, 'update', "page_id = '" . (int)$page_id . "'");
				        }
					
						for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
							$language_id = $languages[$i]['id'];
							$sql_data_array = array('page_name' => tep_db_prepare_input($HTTP_POST_VARS['page_name'][$language_id]),
													'description' => tep_db_prepare_input($HTTP_POST_VARS['page_description'][$language_id]));
													
							if ($action == 'insert_page') {
								$insert_sql_data = array(	'page_id' => $page_id,
															'language_id' => $language_id);
							
								$sql_data_array = array_merge($sql_data_array, $insert_sql_data);
							
								tep_db_perform(TABLE_MAINPAGE_DESCRIPTIONS, $sql_data_array);
							} elseif ($action == 'update_page') {
								tep_db_perform(TABLE_MAINPAGE_DESCRIPTIONS, $sql_data_array, 'update', "page_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
							}
						}
				
				
				tep_redirect(tep_href_link(FILENAME_INFORMATION_PAGES,'page=' . $page_no . '&sPath=' . $parent_page . '&pID='.$page_id));
        		break;
		/*	case 'delete_subscription_confirm':
				if (isset($HTTP_POST_VARS['subscription_id']) && isset($HTTP_POST_VARS['subscription_categories']) && is_array($HTTP_POST_VARS['subscription_categories'])) {
					$subscription_id = tep_db_prepare_input($HTTP_POST_VARS['subscription_id']);
					$subscription_categories = $HTTP_POST_VARS['subscription_categories'];
					for ($i=0, $n=sizeof($subscription_categories); $i<$n; $i++) {
						tep_db_query("delete from " . TABLE_SUBSCRIPTION_TO_SUBSCRIPTION_CATEGORIES . " where subscription_id = '" . (int)$subscription_id . "' and subscription_categories_id = '" . (int)$subscription_categories[$i] . "'");
					}
			
					$subscription_categories_query = tep_db_query("SELECT count(*) as total from " . TABLE_SUBSCRIPTION_TO_SUBSCRIPTION_CATEGORIES . " where subscription_id = '" . (int)$subscription_id . "'");
					$subscription_categories = tep_db_fetch_array($subscription_categories_query);
			
					if ($subscription_categories['total'] == '0') {
						tep_remove_subscription($subscription_id);
					}
				}

				if (USE_CACHE == 'true') {
					tep_reset_cache_block('subscription_categories');
				} 

				tep_redirect(tep_href_link(FILENAME_INFORMATION_PAGES_UPDATE, 'sucPath=' . $sucPath));
				break;*/
		}
	}
	$page_sql = "select mp.page_id,mpd.page_name from ".TABLE_MAINPAGE ." mp, ". TABLE_MAINPAGE_DESCRIPTIONS . " mpd where mp.page_id=mpd.page_id and mp.parent_id=0 and mpd.language_id='".$languages_id."' order by mp.sort_order";
	$page_query=tep_db_query($page_sql);
	while($page_result=tep_db_fetch_array($page_query)){
		if($page_result['page_id']!=$HTTP_GET_VARS['pID']){
			$page_array[]=array('id' =>$page_result['page_id'] ,'text' =>$page_result['page_name']);
		}
	}

	
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="JavaScript" src="includes/date-picker.js"></script>
<script type="text/javascript" src="htmlarea/htmlarea.js"></script>
<script type="text/javascript" src="htmlarea/editor.js"></script>

<?php
// WebMakers.com Added: Java Scripts
include(DIR_WS_INCLUDES . 'javascript/' . 'webmakers_added_js.php');
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
<tr> 
	<!-- body_text //-->
	<td width="100%" valign="top">
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
		<?php   //----- new_category / edit_category (when ALLOW_CATEGORY_DESCRIPTIONS is 'true') -----
			if ($HTTP_GET_VARS['action'] == 'new_page_ACD' || $HTTP_GET_VARS['action'] == 'edit_page_ACD'){
	
				$languages = tep_get_languages();
				$parameters = array('page_name' => '',
									'description' => '',
									'parent_id' => '',
									'page_status' => '1');
		 
			$pInfo = new objectInfo($parameters);
		
			if (isset($HTTP_GET_VARS['pID']) && (!$HTTP_POST_VARS)) {
			  $page_query = tep_db_query("select mp.page_id,mpd.page_name,mp.parent_id,mp.page_status,mpd.description from " .TABLE_MAINPAGE ." mp, ".TABLE_MAINPAGE_DESCRIPTIONS.
			  						" mpd where mp.page_id=mpd.page_id and mp.page_id='".$HTTP_GET_VARS['pID']."' and mpd.language_id = '" . (int)$languages_id . "'");
			  $page = tep_db_fetch_array($page_query);
		
			  $pInfo=new objectInfo($page);
			} elseif ($HTTP_POST_VARS) {
			  $pInfo=new objectInfo($HTTP_POST_VARS);
			  $page_name = $HTTP_POST_VARS['page_name'];
			  $page_description = $HTTP_POST_VARS['page_description'];
			} 

	?>
		<script language="javascript"><!--
	
		function ValidateForm(){
		 var error_result;
		 var count=0;
		 error_result="";
		 count=document.new_page.page_name_value.length;
		 var change_id = document.new_page.parent_page.value;
		if (document.new_page.page_name_value[0]){
		  for (icnt=0;icnt<count;icnt++){
			if (document.new_page.page_name_value[icnt].value==""){
			  error_result="<?php echo tep_db_input(TEXT_ERR_EMPTY_PAGE_NAME); ?>";break;}
		  }
		  
		} else {
		  if (document.new_page.page_name_value.value=="")  error_result="<?php echo tep_db_input(TEXT_ERR_EMPTY_PAGE_NAME); ?>";
		}
		
		 if (error_result!=""){
		   alert(error_result);
		   return false;
		 }
		 return true;
		}

		function set_parent(id){
			if(id==2){
				document.getElementById('parent_page').style.display="";
			}
			else {
				document.getElementById('parent_page').style.display="none";
			}
				
			
		}
		//-->
	</script>
	<?php 	$form_action = ($HTTP_GET_VARS['pID']) ? 'update_page' : 'insert_page'; 

		echo tep_draw_form('new_page', FILENAME_INFORMATION_PAGES_UPDATE, tep_get_all_get_params(array('action')). 'pID=' . $HTTP_GET_VARS['pID']. '&action='. $form_action, 'post', 'onSubmit="return ValidateForm();"'); ?>
		  <tr>
			<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <!-- <tr>
				<td class="pageHeading"><?php //echo HEADING_STATIC_PAGES; ?></td>
				<td class="pageHeading" align="right"></td>
			  </tr> !-->
			</table></td>
		  </tr>
					
					<tr>
						<td valign="top">
						<table border="0" cellpadding="4" cellspacing="0">
							<tr>
								<td>
								<table border="0" cellpadding="0" cellspacing="0">
									<?php
									for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
									?>
											<tr>
												<td class="main" width="150"><?php if ($i == 0) echo TEXT_PAGE_NAME; ?></td>
												<td class="main"><?php //echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('page_name[' . $languages[$i]['id'] . ']', (isset($page_name[$languages[$i]['id']]) ? $page_name[$languages[$i]['id']] : tep_get_page_name($pInfo->page_id, $languages[$i]['id'])),'id=page_name_value size=40'); ?>
												<?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' .'<input name="page_name['.$languages[$i]['id'].']" value="'.(isset($page_name[$languages[$i]['id']]) ? $page_name[$languages[$i]['id']] : tep_get_page_name($pInfo->page_id, $languages[$i]['id'])).'" id="page_name_value" size="40">';?>
												</td>
											</tr>
									<?php
									}
									?>
								</table>
								</td>
							</tr>
							<tr>
								<td colspan="2" class="main"><?php echo '&nbsp;&nbsp;&nbsp;'.tep_draw_radio_field("parent_id",'0',($pInfo->parent_id==0)?true:false,'','onClick=javascript:set_parent(\'1\');') . TEXT_LEVEL1. '&nbsp;'; ?>
								</td>
							</tr>
							<tr>
								<td colspan="2" class="main"><?php echo  '&nbsp;&nbsp;&nbsp;'.tep_draw_radio_field("parent_id",'1',($pInfo->parent_id>0)?true:false,'','onClick=javascript:set_parent(\'2\');').TEXT_LEVEL2;?>	</td>
							</tr>
							<tr>
								<td colspan="2" id="parent_page">
									<table  cellpadding="2" cellspacing="0" width="50%" border="0">
										<tr><td class="main" width="175" align="center"><?php echo tep_draw_separator('pixel_trans.gif', '30', '10').TEXT_PARENT_PAGE;?></td>
											<td class="main"><?php echo tep_draw_pull_down_menu('parent_page',$page_array,$pInfo->parent_id,' id="parent_page"');?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
								<table border="0" cellpadding="0" cellspacing="0">
									<?php for ($i=0, $n=sizeof($languages); $i<$n; $i++) {?>
										<tr>
											<td class="main" valign="top" width="150"><?php if ($i == 0) echo TEXT_PAGE_DESCRIPTION; ?></td>
											<td>
											<table border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
													<td class="main"><?php echo tep_draw_textarea_field('page_description[' . $languages[$i]['id'] . ']', 'soft', '80', '24', (isset($page_description[$languages[$i]['id']]) ? $page_description[$languages[$i]['id']] : tep_get_page_description($pInfo->page_id, $languages[$i]['id'])),'id="page_description[' . $languages[$i]['id'] . ']"'); ?></td>
												</tr>
											</table>
											</td>
										</tr>
									<?php } ?>
								</table>
								</td>
							</tr>
							
							<tr>
								<td colspan="2" align="right"><?php echo tep_image_submit('button_save.gif', IMAGE_SAVE) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_INFORMATION_PAGES,  tep_get_all_get_params(array('pID', 'action')).'pID=' . $HTTP_GET_VARS['pID'] ). '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>
								</td>
							</tr>	
						
						</table>
						</td>
					</tr>
					<tr>
						<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
					
	</table>
	</td>
</tr>
<?php echo '<input type="hidden" name="change_parent_id" id="change_parent_id" value="0">';?>
<script>
	var parent_id='<?php echo $pInfo->parent_id;?>';
	if(parent_id>0) {
		set_parent('2');
	}
	else {
		set_parent('1');
	}
</script>	

	<?php
	  if (HTML_AREA_WYSIWYG_DISABLE == 'Enable') { ?>
            <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
          		<script>initEditor('page_description[<?php echo $languages[$i]['id']; ?>]');</script>
     		<?php } } ?>
	<?php 
	}  
?></form></table>
    </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

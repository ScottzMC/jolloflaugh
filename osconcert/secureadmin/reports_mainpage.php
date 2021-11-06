<?php
	
	/*
		
		https://www.osconcert.com
		
		
		
		Released under the GNU General Public License
		
		Freeway eCommerce from ZacWare
		http://www.openfreeway.org
		
		Copyright 2007 ZacWare Pty. Ltd
	*/
	// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	require('includes/application_top.php');
	tep_get_last_access_file();

	$report_id = 0;
	$action = $FREQUEST->getvalue('action');

	//$report_id_sql = "select menu_id from " . TABLE_ADMIN_MENUS_DESCRIPTION . " where language_id=" . (int)$FSESSION->languages_id . " and menu_text='Berichte'";
	$report_id_sql = "select menu_id from " . TABLE_ADMIN_MENUS_DESCRIPTION . " where language_id=" . (int)$FSESSION->languages_id . " and menu_id='10'";
	$report_id_query = tep_db_query($report_id_sql);
	$report_id_array = tep_db_fetch_array($report_id_query);
	if($report_id_array['menu_id']>0)$report_id = $report_id_array['menu_id'];

	if($action == "ascend" || $action == "descend"){
		$category = $FREQUEST->getvalue('category');
		echo $category . "^";
		echo '<table border="0" cellspacing="0" cellpadding="2" width="100%"><tr><td width="2%" class="main" onMouseOver="javascript:display_popup()" onMouseOut="javascript:display_null()">';
		if($action == "ascend"){
			echo tep_image(DIR_WS_IMAGES . 'template/icon_ascending.gif',IMAGE_DESCENDING) . tep_draw_separator('pixel_trans.gif','10','5');
		}else{
			echo tep_image(DIR_WS_IMAGES . 'template/icon_descending.gif',IMAGE_ASCENDING) . tep_draw_separator('pixel_trans.gif','10','5');
		}
		echo '<br><table style="border:1px solid;border-color:#A5B8F2;display:none;position:absolute;background-color:#FFFFFF;" id="display_menu">';
		echo '<tr class="tableMenuItem" onMouseOver="javascript:rowOverEffect(this)" onMouseOut="javascript:rowOutEffect(this)"><td nowrap>';
		echo "<a style='color:#000000;text-decoration:none' href='javascript:do_sort(\"ascend\")'>" . tep_image(DIR_WS_IMAGES . 'template/icon_ascending.gif',IMAGE_ASCENDING) . tep_draw_separator('pixel_trans.gif','10','10') . TEXT_ASCENDING . tep_draw_separator('pixel_trans.gif','10','10') . "</a>";
		echo '</td></tr><tr class="tableMenuItem" onMouseOver="javascript:rowOverEffect(this)" onMouseOut="javascript:rowOutEffect(this)"><td nowrap>';
		echo "<a style='color:#000000;text-decoration:none' href='javascript:do_sort(\"descend\")'>" . tep_image(DIR_WS_IMAGES . 'template/icon_descending.gif',IMAGE_DESCENDING) . tep_draw_separator('pixel_trans.gif','10','10') . TEXT_DESCENDING . tep_draw_separator('pixel_trans.gif','10','10') . "</a>";
		echo "</td></tr></table>";
		if($action == "ascend")
			echo '</td><td valign="top" width="100" class="main">' . TEXT_HEADING_ASCENDING . '</td></tr>';
		else
			echo '</td><td valign="top" width="100" class="main">' . TEXT_HEADING_DESCENDING . '</td></tr>';
		echo '<tr><td colspan="2" width="100%">';
		echo view_menus($report_id);
		echo '</td></tr>';
		echo '<tr height="20"><td colspan="2" class="cell_bg_report_header"></td></tr></table>';
		exit;
	}

	function view_menus($top_id){
		global $action;
		$order = "";
		if($action == "ascend"){
			$order =  " order by amd.menu_text";
		}else if($action == "descend"){
			$order =  " order by amd.menu_text desc";
		}
		$menu_sql = "select am.menu_id,am.parent_id,amd.menu_text from " . TABLE_ADMIN_MENUS . " am," . TABLE_ADMIN_MENUS_DESCRIPTION . " amd where am.menu_id=amd.menu_id and am.parent_id=" . tep_db_input($top_id);
		$menu_query = tep_db_query($menu_sql);
		if(tep_db_num_rows(tep_db_query($menu_sql))>0){
			while($menu_result = tep_db_fetch_array($menu_query)){
				echo "<script language='javascript'>";
				echo "menu[count]='panel_" . $menu_result['menu_text'] . "';";
				echo "count++;";
				echo "</script>";
				tep_content_title_top($menu_result['menu_text'],true,false,true,'',true);
				$sub_menu_sql = "select am.menu_id,am.parent_id,amd.menu_text,filename from " . TABLE_ADMIN_MENUS . " am," . TABLE_ADMIN_MENUS_DESCRIPTION . " amd where am.menu_id=amd.menu_id and am.parent_id=" . (int)$menu_result['menu_id'] . $order;
				$sub_menu_query = tep_db_query($sub_menu_sql);
				$true = true;
				while($sub_menu_result = tep_db_fetch_array($sub_menu_query)){
					if($true){
						$true = false;
						echo '<tr height="23" class="dataTableRowOdd" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onClick="document.location.href=\'' . tep_href_link($sub_menu_result['filename'],'mPath=' . $top_id . "_" . $sub_menu_result['parent_id'] . "_" . $sub_menu_result['menu_id'] . '') . '\'">';
					}else{
						$true = true;
						echo '<tr height="23" class="dataTableRowEven" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onClick="document.location.href=\'' . tep_href_link($sub_menu_result['filename'],'mPath=' . $top_id . "_" . $sub_menu_result['parent_id'] . "_" . $sub_menu_result['menu_id'] . '') . '\'">';
					}
					echo '<td class="dataTableContent">';
					echo view_space($sub_menu_result['menu_id']) . $sub_menu_result['menu_text'];
					echo "</td></tr>";
				}
				tep_content_title_bottom();
			}
		}
	}
	function view_space($top_id){
		global $FSESSION,$selected_name,$login_id,$login_groups_type;
		$menu_sql = "select am.menu_id,am.parent_id,amd.menu_text from " . TABLE_ADMIN_MENUS . " am," . TABLE_ADMIN_MENUS_DESCRIPTION . " amd where am.menu_id=amd.menu_id and am.menu_id=" . tep_db_input($top_id);
		$menu_query = tep_db_query($menu_sql);
		$menu_result = tep_db_fetch_array($menu_query);
		if($menu_result['parent_id']>0){
			return tep_draw_separator('pixel_trans.gif', '20', '10') . view_space($menu_result['parent_id']);
		}else{
			return;
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
<script language="javascript" src="includes/http.js"></script>
<script language="javascript">
	var count=0;
	var menu = new Array();
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
	<table border="0" width="100%" cellspacing="2" cellpadding="2">
		<tr>
			<td width="100%" valign="top"><div id="repeat" class="main">
			<table border="0" cellspacing="0" style="cursor:pointer;" cellpadding="2" width="100%">
				<tr>
					<td width="2%" class="main" onMouseOver='javascript:display_popup()' onMouseOut='javascript:display_null()'>
						<?php 
							echo tep_image(DIR_WS_IMAGES . 'template/icon_ascending.gif',IMAGE_ASCENDING);
							echo tep_draw_separator('pixel_trans.gif','10','5');
							echo '<br><table class="main" style="border:1px solid;border-color:#6883CA;display:none;position:absolute;background-color:#FFFFFF;" id="display_menu"><tr class="tableMenuItem" onMouseOver="javascript:rowOverEffect(this)" onMouseOut="javascript:rowOutEffect(this)"><td nowrap>';
							echo "<a style='color:#000000;text-decoration:none' href='javascript:do_sort(\"ascend\")'>" . tep_image(DIR_WS_IMAGES . 'template/icon_ascending.gif',IMAGE_ASCENDING) . tep_draw_separator('pixel_trans.gif','10','10') . TEXT_ASCENDING . tep_draw_separator('pixel_trans.gif','10','10') . "</a>";
							echo '</td></tr><tr class="tableMenuItem" onMouseOver="javascript:rowOverEffect(this)" onMouseOut="javascript:rowOutEffect(this)"><td nowrap>';
							echo "<a style='color:#000000;text-decoration:none' href='javascript:do_sort(\"descend\")'>" . tep_image(DIR_WS_IMAGES . 'template/icon_descending.gif',IMAGE_DESCENDING) . tep_draw_separator('pixel_trans.gif','10','10') . TEXT_DESCENDING . tep_draw_separator('pixel_trans.gif','10','10') . "</a>";
							echo "</td></tr></table>";
						?>
					</td>
					<td valign="top" width="100" class="main"><?php echo TEXT_HEADING_SORT;?></td>
				</tr>
				<tr>
					<td colspan="2" width="100%">
						<?php echo view_menus($report_id);?>
					</td>
				</tr>
				<tr height="20">
					<td colspan="2" class="cell_bg_report_header"></td>
				</tr>
			</table></div></td>
		</tr>
	</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<script language="javascript">
	var display_category = true;
	for(i=0;i<menu.length;i++){
		if(document.getElementById(menu[i])){
			if(display_category){
				document.getElementById(menu[i]).style.display="";
				display_category=false;
			}else
				document.getElementById(menu[i]).style.display="none";
		}
	}
	function disp(panel){
		for(i=0;i<menu.length;i++){
			if(document.getElementById(menu[i]) && document.getElementById(menu[i])==panel)
				document.getElementById(menu[i]).style.display="";
			else if(document.getElementById(menu[i]))
				document.getElementById(menu[i]).style.display="none";
		}
	}
	function do_sort(action){
		var category = '';
		for(i=0;i<menu.length;i++)
		if(document.getElementById(menu[i]) && document.getElementById(menu[i]).style.display=="")
			category = menu[i];
		command = "<?php echo tep_href_link(FILENAME_REPORTS_MAINPAGE);?>"+"?action="+action+"&category="+category;
		do_get_command(command);
	}
	function display_popup(){
		if(document.getElementById("display_menu"))document.getElementById("display_menu").style.display = "";
	}
	function display_null(){
		if(document.getElementById("display_menu"))document.getElementById("display_menu").style.display = "none";
	}
	function do_result(response){
		response = response.split("^");
		document.getElementById("repeat").innerHTML = response[1];
		for(i=0;i<menu.length;i++){
			if(menu[i]==response[0]){
				document.getElementById(menu[i]).style.display="";
				display_category=false;
			}else
				document.getElementById(menu[i]).style.display="none";
		}
	}
</script>
</body>
</html> 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

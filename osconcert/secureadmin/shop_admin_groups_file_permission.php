<?php
/*

	osCommerce, Open Source E-Commerce Solutions
	http://www.oscommerce.com
	Copyright (c) 2003 osCommerce
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	osConcert Seat Booking Software
	Copyright (c) 2020 osConcert
	https://www.osconcert.com
    
    Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );

require('includes/application_top.php');
$command=$FREQUEST->getvalue('command');

//print_r($FREQUEST);

if($command!='')
{
	switch($command)
	{
		case 'load_admin_group_permission_details':
		$admin_groups_id=$FREQUEST->getvalue('admin_groups_id');
		echo 'load_admin_group_permission_details^';
			group_permission($admin_groups_id);
		break;
		case 'save_admin_groups_permission':
		 $global_admin_groups_id=$FREQUEST->getvalue('admin_groups_id');
		 $selected_checkbox=array();
		 $group_to_boxes=&$FREQUEST->getRefValue("groups_to_boxes","POST");		 
		 $selection_group_checkbox=array();
			if(isset($group_to_boxes['subgroups'])){
				$groups_to_boxes = array();
				$size=0;
				$size = sizeof(array_values($group_to_boxes['subgroups']));				
				for($i=0;$i<$size;$i++){
					$subgroups_to_boxes = array_values($group_to_boxes['subgroups']);
					$groups_to_boxes = array_merge($groups_to_boxes,$subgroups_to_boxes[$i]);
				}
				$selected_checkbox = array_merge($groups_to_boxes,array_keys($group_to_boxes['subgroups']));
				//$selected_checkbox = array_merge($groups_to_boxes,array_keys($HTTP_POST_VARS["groups_to_boxes"]['groups']));
				if(is_array($group_to_boxes['groups']));
					$selection_group_checkbox=array_values($group_to_boxes['groups']);
				//print_r($selected_checkbox);				
				//$subgroups_to_boxes = array_values($HTTP_POST_VARS["groups_to_boxes"]['subgroups']);
				//$selected_checkbox = $subgroups_to_boxes[0];

				$groups_type=$FREQUEST->postvalue('groups_type');
				
				for($i=1,$n=sizeof($group_to_boxes['groups']);$i<=$n; $i++){
					$group_id=$group_to_boxes['groups'][$i];
							$sql_data_array= array('admin_groups_id' => tep_db_prepare_input($FREQUEST->postvalue('checked_' . $group_id)));

						tep_db_perform(TABLE_ADMIN_FILES_GROUPS,$sql_data_array, 'update', 'admin_files_groups_id=\'' . $group_id. '\'');
					
				}
				/*$define_groups_query=tep_db_query("select admin_files_groups_id, admin_groups_id from " . TABLE_ADMIN_FILES_GROUPS ." order by admin_files_groups_id");
				while($define_groups = tep_db_fetch_array($define_groups_query)){
					$admin_groups_id = $define_groups['admin_files_groups_id']; 
					if($selected_checkbox!=""){
						if (in_array ($admin_groups_id, $selected_checkbox)) {
							$sql_data_array= array('admin_groups_id' => tep_db_prepare_input($HTTP_POST_VARS['checked_' . $admin_groups_id]));
						} else {
							//$sql_data_array = array('admin_groups_id'=> 1);
							$sql_data_array = array('admin_groups_id'=> tep_db_prepare_input($HTTP_POST_VARS['unchecked_'.$admin_groups_id]));
						} 

						tep_db_perform(TABLE_ADMIN_FILES_GROUPS,$sql_data_array, 'update', 'admin_files_groups_id=\'' . $admin_groups_id. '\'');	
					}
				}	*/
				
			}
		
		$define_groups_query=tep_db_query("select admin_files_groups_id, admin_groups_id from " . TABLE_ADMIN_FILES_GROUPS ." order by admin_files_groups_id");
				while($define_groups = tep_db_fetch_array($define_groups_query)){
					$admin_groups_id = $define_groups['admin_files_groups_id']; 
					if($selected_checkbox!=""){
						if (in_array ($admin_groups_id, $selection_group_checkbox)) {
							$sql_data_array= array('admin_groups_id' => tep_db_prepare_input($FREQUEST->postvalue('checked_' . $admin_groups_id)));
						} else {
							//$sql_data_array = array('admin_groups_id'=> 1);
							$sql_data_array = array('admin_groups_id'=> tep_db_prepare_input($FREQUEST->postvalue('unchecked_'.$admin_groups_id)));
						} 

						tep_db_perform(TABLE_ADMIN_FILES_GROUPS,$sql_data_array, 'update', 'admin_files_groups_id=\'' . $admin_groups_id. '\'');	
					}
		}		
		$define_files_sql = "select admin_files_id,admin_new_group_id,admin_files_name from " . TABLE_ADMIN_FILES . " order by admin_files_id";
		$define_files_query = tep_db_query($define_files_sql);
		while ($define_files = tep_db_fetch_array($define_files_query)) 
		{ 
					$admin_files_id = $define_files['admin_files_id'];
					//if($selected_checkbox!=""){
						if (in_array ($admin_files_id, $selected_checkbox)) 
						{
							$sql_data_array= array('admin_groups_id' => tep_db_prepare_input($FREQUEST->postvalue('checked_' . $admin_files_id)));
						} else 
						{
							//$sql_data_array= array('admin_groups_id' => '1');
							if($FREQUEST->postvalue('unchecked_'.$admin_files_id)!='')
							{
								$sql_data_array= array('admin_groups_id' => tep_db_prepare_input($FREQUEST->postvalue('unchecked_' . $admin_files_id)));
							}else
							{
								$sql_data_array=array('admin_groups_id'=>'1');
							}
						} 
						tep_db_perform(TABLE_ADMIN_FILES, $sql_data_array, 'update', 'admin_files_id = \'' . $admin_files_id . '\'');
					//}
		}	
		echo 'load_admin_group_permission_details^';
		group_permission($global_admin_groups_id);
		break;
	}
exit;
}

tep_get_last_access_file();
?>
<?php
	function group_permission($admin_groups_id)
	{		
			$group_name_sql = "select 
			admin_groups_type,
			admin_groups_name 
			from " . TABLE_ADMIN_GROUPS . " where admin_groups_id = " . tep_db_input($admin_groups_id);
			$group_name_query = tep_db_query($group_name_sql);
			$group_name = tep_db_fetch_array($group_name_query);
			if ($admin_groups_id == 1) {
				echo tep_draw_form('defineForm', FILENAME_SHOP_ADMIN_MEMBERS, 'gID=' . $admin_groups_id);
			} elseif ($admin_groups_id != 1) {
				echo tep_draw_form('defineForm', FILENAME_SHOP_ADMIN_MEMBERS, 'gID=' . $admin_groups_id . '&action=group_define', 'post', 'enctype="multipart/form-data"');
				echo tep_draw_hidden_field('admin_groups_id', $admin_groups_id); 
			}
	?>
		<table width="100%" cellpadding="0" cellspacing="2" border="0">
		<tr class="dataTableHeadingRow">
		<td class="dataTableHeadingContent" width="300"><?php echo tep_image(DIR_WS_IMAGES.'template/icon_active.gif').'&nbsp;&nbsp;'.$group_name['admin_groups_name']; ?></td>
		<?php if($group_name['admin_groups_name']!=TEXT_ADMINISTRATOR_ENTRY){ ?>
		<td style="padding-top:5px;" align="right"><?php echo '<a href="javascript: save_admin_groups_permission('.$admin_groups_id.');">'.tep_image(DIR_WS_IMAGES.'template/img_savel.gif','Save','','','').'</a>&nbsp;&nbsp;<a href="javascript: admin_group_change('.$admin_groups_id.')">'.tep_image(DIR_WS_IMAGES.'template/img_closel.gif','Close','','','').'</a>'; ?></td>
		<?php } ?>
		</tr>
		</table>
	
	
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
		<tr>
			<td colspan="2" >&nbsp;</td>
		</tr> 
		<?php

		               	// get type of event "customers_",
			$menu_array = array("products_","concert_","cms_","marketing_","sales_","payment_","reports_","shop_","myaccount_");
			$condition_query = "";
			for($i=0;$i<sizeof($menu_array);$i++)
			{
				$condition_query .= " admin_files_name not like '" . $menu_array[$i] . "%'";
				if($i<sizeof($menu_array)-1)$condition_query .= " and";
			}
		
			$group_name['admin_groups_type']='D';

				//if ($HTTP_GET_VARS['gDet'] == 1) {
				$db_boxes_sql = "select af.admin_files_id as admin_boxes_id, afg.admin_files_groups_desc,afg.admin_files_groups_name as admin_boxes_name, afg.admin_groups_id as boxes_group_id,af.admin_files_type as boxes_type,afg.admin_files_groups_id from " . TABLE_ADMIN_FILES . "  af, admin_files_groups afg where afg.admin_files_groups_id = af.admin_new_group_id and 
				af.admin_files_type !='N' and  af.admin_files_is_boxes = '1'   group by af.admin_new_group_id order by af.admin_files_name";
				
				//echo $db_boxes_sql;
				########################################################################
				// $db_boxes_sql = "select 
				// af.admin_files_id as admin_boxes_id, 
				// afg.admin_files_groups_name as admin_boxes_name, 
				// MAX(afg.admin_groups_id) as boxes_group_id,
				// af.admin_files_type as boxes_type,
				// afg.admin_files_groups_id 
				// from " . TABLE_ADMIN_FILES . "  af, 
				// admin_files_groups afg 
				// where afg.admin_files_groups_id = 
				// af.admin_new_group_id and 
				// af.admin_files_type !='N' and  
				// af.admin_files_is_boxes = '1'   
				// group by
				// afg.admin_files_groups_name
				// order by af.admin_files_name";
				// //echo $db_boxes_sql;
				
				$db_boxes_query = tep_db_query($db_boxes_sql);
				
				//echo tep_draw_hidden_field('groups_type','A');} //administrator
				/*else {
				echo tep_draw_hidden_field('groups_type','O'); // others
				$db_boxes_query = tep_db_query("select admin_files_id as admin_boxes_id, admin_files_name as admin_boxes_name, admin_groups_id as boxes_group_id,admin_files_type as boxes_type from " . TABLE_ADMIN_FILES . " where admin_files_is_boxes = '1' and admin_files_type!='E' order by admin_files_name");}*/

			while($group_boxes = tep_db_fetch_array($db_boxes_query)){

				//$group_boxes_files_sql = "select af.admin_files_id, af.admin_files_name, af.admin_groups_id,af.admin_files_help_id,af.admin_new_group_id from " . TABLE_ADMIN_FILES . "  af, admin_files_groups afg where af.admin_files_name like '%" . $group_boxes['admin_boxes_name'] . "%' and af.admin_new_group_id=afg.admin_files_groups_id order by af.admin_files_help_id,af.admin_files_name";
				$group_boxes_files_sql = "select af.admin_files_id, af.admin_files_name, af.admin_groups_id,af.admin_files_help_id,af.admin_new_group_id from " . TABLE_ADMIN_FILES . "  af, admin_files_groups afg where af.admin_new_group_id='".(int)$group_boxes['admin_files_groups_id']."' and af.admin_new_group_id=afg.admin_files_groups_id order by af.admin_files_help_id,af.admin_files_name";
				
				$group_boxes_files_query = tep_db_query($group_boxes_files_sql);
				$selectedGroups = $group_boxes['boxes_group_id'];
				$groupsArray = explode(",", $selectedGroups);
				if (in_array($admin_groups_id, $groupsArray)) 
				{
					$del_boxes = array($admin_groups_id);
					$result = array_diff ($groupsArray, $del_boxes);
					sort($result);
					$checkedBox = $selectedGroups;
					$uncheckedBox = implode (",", $result);
					$checked = true;
				} else 
				{
					$add_boxes = array($admin_groups_id);
					$result = array_merge ($add_boxes, $groupsArray);
					sort($result);
					$checkedBox = implode (",", $result);
					$uncheckedBox = $selectedGroups;
					$checked = false;
				}

		?>
		 <tr class="dataTableRowBoxes">
			<td class="dataTableContent" width="23"><?php 
			echo tep_draw_checkbox_field("groups_to_boxes[groups][" . $group_boxes['admin_files_groups_id'] . "]", $group_boxes['admin_files_groups_id'], $checked, '', ' alt="groups_' . $group_boxes['admin_files_groups_id'] . '"  onClick="checkGroups(this)"'); ?></td>
			<td class="dataTableContent"><b>
			<?php 
			$search_base_str_array=array('_');
					echo ucwords( str_replace($search_base_str_array," &raquo; ",$group_boxes['admin_boxes_name']) );
					echo ' ' . tep_draw_hidden_field('checked_' . $group_boxes['admin_files_groups_id'], $checkedBox) . tep_draw_hidden_field('unchecked_' . $group_boxes['admin_files_groups_id'], $uncheckedBox); 
			?>
			</b></td>
		</tr>
		<tr class="dataTableRow">
			<td class="dataTableContent">&nbsp;</td>
			<td class="dataTableContent">
			<table border="0" cellspacing="0" cellpadding="0">
			<?php
				while($group_boxes_files = tep_db_fetch_array($group_boxes_files_query)) 
				{
					$selectedGroups = $group_boxes_files['admin_groups_id'];
					$groupsArray = explode(",", $selectedGroups);
					
					if (in_array($admin_groups_id, $groupsArray)) 
					{  
						$del_boxes = array($admin_groups_id);
						$result = array_diff ($groupsArray, $del_boxes);
						sort($result);
						$checkedBox = $selectedGroups;
						$uncheckedBox = implode (",", $result);
						$checked = true;
					} else 
					{
						$add_boxes = array($admin_groups_id);
						$result = array_merge ($add_boxes, $groupsArray);
						sort($result);
						$checkedBox = implode (",", $result);
						$uncheckedBox = $selectedGroups;
						$checked = false;
					}
						if($group_boxes['admin_boxes_name']!=$group_boxes_files['admin_files_name'])
						{

			?>
				<tr>
					<td width="20">
						<?php 
							echo tep_draw_checkbox_field("groups_to_boxes[subgroups][" . $group_boxes['admin_files_groups_id'] . "][]", $group_boxes_files['admin_files_id'], $checked, '', ' alt="subgroups_' . $group_boxes['admin_boxes_id'] . '"  onClick="checkSub(this,\''.$group_boxes_files['admin_new_group_id'] . '\')"'); 
						?>
					</td>
					<?php
					$search_string_array=array($group_boxes['admin_boxes_name'],'_','.php');
					$replace_string_array=array(' ',' ',' ');
					?>
					<td class="dataTableContent">&nbsp;<?php  
					$admin_boxes_name=str_replace('_','',$group_boxes['admin_boxes_name']);
					$display_string=str_replace($search_string_array,$replace_string_array,$group_boxes_files['admin_files_name']);
					$display_desc=$group_boxes_files['admin_files_name'];

					echo ((trim($display_string)=='')? ' Index' : ucwords($display_string) );
					echo ' ' . tep_draw_hidden_field('checked_' . $group_boxes_files['admin_files_id'], $checkedBox) . tep_draw_hidden_field('unchecked_' . $group_boxes_files['admin_files_id'], $uncheckedBox);
					echo '&nbsp;&nbsp;&nbsp; '.$display_desc;

					?></td>
				</tr>
			<?php   
					}
				}
			?>
			
			</table> 
			</td>
		</tr>
		<?php
			}
		?>
		</table>
		<table width="100%" cellpadding="0" cellspacing="2" border="0">
		<tr class="dataTableHeadingRow">
		<td class="dataTableHeadingContent" width="300"><?php echo tep_image(DIR_WS_IMAGES.'template/icon_active.gif').'&nbsp;&nbsp;'.$group_name['admin_groups_name']; ?></td>
		<?php if($group_name['admin_groups_name']!=TEXT_ADMINISTRATOR_ENTRY){ ?>
		<td style="padding-top:5px;" align="right"><?php echo '<a href="javascript: save_admin_groups_permission('.$admin_groups_id.');">'.tep_image(DIR_WS_IMAGES.'template/img_savel.gif','Save','','','').'</a>&nbsp;&nbsp;<a href="javascript: admin_group_change('.$admin_groups_id.')">'.tep_image(DIR_WS_IMAGES.'template/img_closel.gif','Close','','','').'</a>'; ?></td>
		<?php } ?>
		</tr>
		</table>
		</form>
<?php
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
<script language="javascript" src="includes/http.js"></script>

<?php require('includes/account_check.js.php'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- Ajax Work Starts -->
<?php $session_admin_groups_id=$FSESSION->get('shop_admin_groups_id','string','1'); 
	$admin_groups_query=tep_db_query('select admin_groups_id,admin_groups_name from '.TABLE_ADMIN_GROUPS.' order by admin_groups_id');
	$group_name= array();
	while($admin_groups_array=tep_db_fetch_array($admin_groups_query))
		$group_name[]=array('id'=>$admin_groups_array['admin_groups_id'],'text'=>$admin_groups_array['admin_groups_name']);
?>
<table style="padding-top:5px;" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="dataTableHeadingRow">
	<td width="100%" class="dataTableHeadingContent" align="right" valign="middle"><?php echo TITLE_GO_TO.'&nbsp;&nbsp;'.tep_draw_pull_down_menu('admin_groups_id',$group_name,$session_admin_groups_id,' onChange="javascript: admin_group_change(this.value);" '); ?></td>
</tr>
</table>
	<div class="openContent" id="permission_details_id">
	<?php group_permission($session_admin_groups_id); ?>
	</div>
<!-- Ajax Work Ends -->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<script language="javascript">
	var ajax_image_load='<?php echo tep_image(DIR_WS_IMAGES.'24-1.gif'); ?>';
	function admin_group_change(admin_groups_id){
	document.getElementById('permission_details_id').innerHTML=ajax_image_load;
	do_get_command('<?php echo tep_href_link(FILENAME_ADMIN_GROUPS_FILE_PERMISSION,'command=load_admin_group_permission_details&admin_groups_id='); ?>'+admin_groups_id);
	}

	function save_admin_groups_permission(admin_groups_id){
	do_post_command('defineForm','<?php echo tep_href_link(FILENAME_ADMIN_GROUPS_FILE_PERMISSION,'command=save_admin_groups_permission&admin_groups_id='); ?>'+admin_groups_id);
	document.getElementById('permission_details_id').innerHTML=ajax_image_load;
	}
	function do_result(result){
	var token=result.split('^');
	switch(token[0]){
		case 'load_admin_group_permission_details':
		document.getElementById('permission_details_id').innerHTML=token[1];
		break;
	}
	}
</script>
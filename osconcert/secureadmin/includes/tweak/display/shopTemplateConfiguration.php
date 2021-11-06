<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/

defined('_FEXEC') or die();
class shopTemplateConfiguration
{
	var $pagination;
	var $splitResult;
	var $type;

	function __construct() {
		$this->type='shopConfig';
	}
	
	
	function  doChange_template_status()
		{
			global $SERVER_DATE,$jsData,$FREQUEST;
			
			$template_id=$FREQUEST->getvalue('tID','int',-1);
			$flag=$FREQUEST->getvalue('flag','int',0);
			
			if(($flag == 0) || ($flag == 1) ) {
			  if ($template_id > 0) {
				tep_db_query("update " . TABLE_TEMPLATE . " set active = '" . $flag . "', last_modified='". tep_db_input($SERVER_DATE) ."' where template_id = '" . (int)$template_id . "'");
				$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $template_id .",get:\'Change_template_status\',result:doSimpleResult,params:\'tID=". $template_id . "&flag=".(($flag==1)?0:1)."\',message:\'".TEXT_UPDATING_STATUS."\'});\">" . (($flag==0)?tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif'):tep_image(DIR_WS_IMAGES . 'template/icon_active.gif')) . '</a>';
			  }
			}
			$jsData->VARS['replace']=array("shopConfig".$template_id."bullet"=>$result);
		}
	
	function doUpdate()
			{
			global $FREQUEST,$jsData,$SERVER_DATE;
			

		$template_id=$FREQUEST->postvalue('template_id','int',-1);
        $template_name = $FREQUEST->postvalue('template_name');
		$box_width_left = $FREQUEST->postvalue('box_width_left');
		$include_module_one = $FREQUEST->postvalue('include_module_one');
		$include_module_two = $FREQUEST->postvalue('include_module_two');
		$include_module_three = $FREQUEST->postvalue('include_module_three');
		$include_module_four = $FREQUEST->postvalue('include_module_four');
		$include_module_five = $FREQUEST->postvalue('include_module_five');
		$include_module_six = $FREQUEST->postvalue('include_module_six');
		$edit_customer_greeting_personal = $FREQUEST->postvalue('edit_customer_greeting_personal');
		$edit_customer_greeting_personal_relogon = $FREQUEST->postvalue('edit_customer_greeting_personal_relogon');
		$edit_greeting_guest = $FREQUEST->postvalue('edit_greeting_guest');
		$site_width = $FREQUEST->postvalue('site_width');
		$header_height = $FREQUEST->postvalue('header_height');
		$container_border = $FREQUEST->postvalue('container_border');
		$header_banner = $FREQUEST->postvalue('header_banner');
		$template_color = $FREQUEST->postvalue('template_color');
		
		$sql_data_array = array('template_name' => $template_name,
								'box_width_left' => $box_width_left,
								'header_banner' => $header_banner,
								'template_color' => $template_color,
								'module_one' => $include_module_one,
								'module_two' => $include_module_two,
								'module_three' => $include_module_three,
								'module_four' => $include_module_four,
								'module_five' => $include_module_five,
								'module_six' => $include_module_six,
								'edit_customer_greeting_personal' => $edit_customer_greeting_personal,
								'edit_customer_greeting_personal_relogon' => $edit_customer_greeting_personal_relogon,
								'edit_greeting_guest' => $edit_greeting_guest,
								'container_border' => $container_border,
								'header_height' => $header_height,
								'site_width' => $site_width);
	       $update_sql_data = array('last_modified' => $SERVER_DATE);
           $sql_data_array = array_merge($sql_data_array, $update_sql_data);
           tep_db_perform(TABLE_TEMPLATE, $sql_data_array, 'update', "template_id = '" . (int)$template_id . "'");
	       if ($FREQUEST->postvalue('default') == 'on') 
		   {
    		    tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($template_name) . "' where configuration_key = 'DEFAULT_TEMPLATE'");
				tep_db_query("update " . TABLE_TEMPLATE . " set active = '1', last_modified='". tep_db_input($SERVER_DATE) ."' where template_id = '" . (int)$template_id . "'");

				$template_name='<b>'.$template_name.'  (default)</b>';
				
				$query=tep_db_query('select template_id from '.TABLE_TEMPLATE. ' where template_name="'.DEFAULT_TEMPLATE.'"');
				$array=tep_db_fetch_array($query);
				
				if($array['template_id']==$template_id)
				{
				$jsData->VARS["replace"]=array($this->type. $template_id . "name"=>$template_name);
				}
				else
				{
				$jsData->VARS["replace"]=array($this->type. $template_id . "name"=>$template_name,$this->type.$array['template_id']."name"=>DEFAULT_TEMPLATE);
				}		
		    }

				$jsData->VARS["prevAction"]=array('id'=>$template_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
				$this->doInfo($template_id);
				$jsData->VARS["updateMenu"]=",normal,";
			
			}
	
	
	function doDelete()
	{
			global $FREQUEST,$jsData;
			$template_id=$FREQUEST->postvalue('template_id','int',0);
			if ($template_id>0){
				
		if ($delete_image == 'on') 
		{
       	  $theme_query = tep_db_query("select template_image from " . TABLE_TEMPLATE . " where template_id = '" . (int)$template_id . "'");
          $theme = tep_db_fetch_array($theme_query);
          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $theme['template_image'];
          if (file_exists($image_location)) @unlink($image_location);
      	}
      tep_db_query("delete from " . TABLE_TEMPLATE . " where template_id = '" . (int)$template_id . "'");
      tep_db_query("delete from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . (int)$template_id . "'");
				
				$this->doItems();
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_TEMPLATE_DELETE_SUCCESS);
				tep_reset_seo_cache('template');
			} else {
				echo "Err:" . TEXT_TEMPLATE_NOT_DELETED;
			}
			
		}
		
		function doDeleteTemplate()
		{
			global $FREQUEST,$jsData;
			$template_id=$FREQUEST->getvalue('tID','int',0);

			$delete_message='<p><span class="smallText">' . TEXT_DELETE_INTRO . '</span>';
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="customers_group.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="template_id" value="<?php echo tep_output_string($template_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $template_id;?>message">
						</td>
					</tr>
					<tr>
						<td class="main">
						<?php echo $delete_message;?>
						</td>
					</tr>
					<tr height="40">
						<td class="main" style="vertical-align:bottom">
							<p>
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $template_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $template_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php echo $this->doInfo($template_id);?></td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}
	
	function domove_right_left()
	{ 
	global $FREQUEST,$jsData;
	$template_id=$FREQUEST->getvalue('tID','int',-1);
	$info_id=$FREQUEST->getvalue('info_id','int',-1);
	$column=$FREQUEST->getvalue('column');
	
		  if(($column == 'left') || ($column== 'right') ) 
		  {
				tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set display_in_column = '" . $column . "' where infobox_id = '" . (int)$info_id . "'");
				tep_template_box_realign($template_id,'left');
				tep_template_box_realign($template_id,'right');
		 }
		 
		 
	   $result_left=$this->infoboxes_left($template_id);
	 
	   $result_right=$this->infoboxes_right($template_id);
	 
	    
	
	$result_left=tep_db_input($result_left);
	$result_right=tep_db_input($result_right);
    $result_left=tep_db_prepare_input($result_left);
    $result_right=tep_db_prepare_input($result_right);
	
	$jsData->VARS["replace"]=array("infobox_left"=>$result_left,"infobox_right"=>$result_right);
	
	}
	
	function infoboxes_right($template_id)
	{
		ob_start();
	 $icnt=1;
				 $loc_limit=0;
				 $infobox_sql = tep_db_query("select max(location) as loc_limit from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . tep_db_input($template_id) . "' and display_in_column = 'right' and infobox_display='yes' group by template_id");
				 if(tep_db_num_rows($infobox_sql))
				 {
					$row=tep_db_fetch_array($infobox_sql);
					$loc_limit=$row["loc_limit"];
				 }
				 $infobox_query = tep_db_query("select infobox_id, display_in_column, infobox_file_name, location,infobox_display from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . tep_db_input($template_id) . "' and display_in_column = 'right'  order by infobox_display desc,location asc");
				 while ($infobox = tep_db_fetch_array($infobox_query))
				 {
					  $infid = $infobox['infobox_id'];
					  $infcol = $infobox['display_in_column'];
					  $infname = $infobox['infobox_file_name'];
					  $infloc = $infobox['location'];
					  $infdis = $infobox['infobox_display'];
				?>
	<table>
	<tr>
	<td id="<?php echo "bullet".$infid; ?>"><?php if($infdis=='yes') 
					{	
					echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $template_id . "&status=no&case=infobox_display&info_id=".$infid."',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
					}
				 else
					{
					echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $template_id . "&status=yes&case=infobox_display&info_id=".$infid."',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
					}
				?>
			</td>
			<td class="smalltext" width="175" height="20"><?php echo $infname;?></td><?php if($infdis=='yes')
						{
						  ?><td width='10'><?php
							if ($icnt>1) 
							{
							  echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $template_id .",get:'move_pos_updown',result:doSimpleResult,params:'tID=". $template_id . "&command=move_up&position=".$infloc."&column=".$infcol."',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/img_arrow_up.gif', IMG_MOVE_UP) . '</a>';
							}
							?></td><td width="10"><?php
							if ($loc_limit!=$infloc) 
							{
							   echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $template_id .",get:'move_pos_updown',result:doSimpleResult,params:'tID=". $template_id . "&command=move_down&position=".$infloc."&column=".$infcol."',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/img_arrow_down.gif', IMG_MOVE_DOWN) . '</a>';
							}
							 ?></td><?php
							$icnt++;
						}
						 else
						{?>
						 <td width="30"></td><?php
						 
						}
						
					?><td align="right"><?php 
					echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $template_id .",get:'move_right_left',result:doSimpleResult,params:'tID=". $template_id . "&column=left&info_id=".$infid."',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/arrow_left.gif', TEXT_MOVE_LEFT) . '</a>';
					?>
					</td>
					</tr>
					</table>
					<?php 
					}

		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
		
		
	}
	
		function infoboxes_left($template_id)
	{
	ob_start();
		 $icnt=1;
				 $infobox_sql = tep_db_query("select max(location) as loc_limit from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . tep_db_input($template_id) . "' and display_in_column = 'left' and infobox_display='yes' group by template_id");
				 if(tep_db_num_rows($infobox_sql))
				 {
					$row=tep_db_fetch_array($infobox_sql);
					$loc_limit=$row["loc_limit"];
				 }
				 $infobox_sql = "select infobox_id, display_in_column, infobox_file_name, location,infobox_display from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . tep_db_input($template_id) . "' and display_in_column = 'left'  order by infobox_display desc,location asc";
				 $infobox_query = tep_db_query($infobox_sql);
				 
				 while ($infobox = tep_db_fetch_array($infobox_query)) {
					  $infid = $infobox['infobox_id'];
					  $infcol = $infobox['display_in_column'];
					  $infname = $infobox['infobox_file_name'];
					  $infloc = $infobox['location'];
					  $infdis = $infobox['infobox_display'];
				?>
<table><tr><td id="<?php echo "bullet".$infid; ?>"><?php if($infdis=='yes') 
						echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $template_id . "&status=no&case=infobox_display&info_id=".$infid."',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
					 else
						echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $template_id . "&status=yes&case=infobox_display&info_id=".$infid."',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
						?></td><td class="smalltext" width="175" height="20"><?php echo $infname;?></td><?php if($infdis=='yes')
							  {
					  		  echo "<td width='10'>";
								
								if ($icnt>1) {
								  echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $template_id .",get:'move_pos_updown',result:doSimpleResult,params:'tID=". $template_id . "&command=move_up&position=".$infloc."&column=".$infcol."',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/img_arrow_up.gif', IMG_MOVE_UP) . '</a>';
								}
						
								echo "</td><td width='10'>";
							
							
								if ($loc_limit!=$infloc) {
								  echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $template_id .",get:'move_pos_updown',result:doSimpleResult,params:'tID=". $template_id . "&command=move_down&position=".$infloc."&column=".$infcol."',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/img_arrow_down.gif', IMG_MOVE_DOWN) . '</a>';
								}
						 		
								echo '</td>';
                                $icnt++;
								
							 }
							  else
						 	  {
						 		echo '<td width="30"> </td>';
							 }
						?><td align="right"><?php 
					//echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $template_id .",get:'move_right_left',result:doSimpleResult,params:'tID=". $template_id . "&column=right&info_id=".$infid."',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/arrow_right.gif', TEXT_MOVE_RIGHT) . '</a>';
					?></td></tr></table><?php
		}
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
		
	}
	
	function doNewUpdate()
	{
	
	global $SERVER_DATE,$FREQUEST;
	
		$template_image_name=$FREQUEST->postvalue('template_image');	
		$update_sql_data = array('template_image' => $template_image_name);
 
		$template_name=$FREQUEST->postvalue('template_name');
		$default_template_id=$FREQUEST->postvalue('info_box_sql','int',0);
		if($default_template_id>0)
		{
			$template_sql = "select * from " . TABLE_TEMPLATE . " where template_id=" . (int)$default_template_id;
			$template_query = tep_db_query($template_sql);
			$template_result = tep_db_fetch_array($template_query);
			$sql_data_array = array('template_name' => $template_name,
							'include_column_left' => $template_result["include_column_left"],
							'site_width' => $template_result["site_width"],
							'module_one'=> '',
							'module_two'=> '',
							'module_three'=> '',
							'module_four'=> '',
							'module_five'=> '',
							'module_six'=> '',
							'header_height' => $template_result["header_height"],
							'header_banner' => $template_result["header_banner"],
							'template_color' => $template_result["template_color"],
							'active'=>$template_result["active"],
							'edit_customer_greeting_personal' =>$template_result["edit_customer_greeting_personal"],
							'edit_customer_greeting_personal_relogon' =>$template_result["edit_customer_greeting_personal_relogon"],
							'edit_greeting_guest' =>$template_result["edit_greeting_guest"],	
							'date_added' => $SERVER_DATE);
		}else
		{
			$sql_data_array = array('template_name' => $template_name,
							'include_column_left' => 'no',
							'module_one'=> 'featured_categories.php',
							'site_width' => '84',
							'module_one'=> '',
							'module_two'=> '',
							'module_three'=> '',
							'module_four'=> '',
							'module_five'=> '',
							'module_six'=> '',
							'header_banner' => '',
							'template_color' => 'default',
							'active'=>1,
							'edit_customer_greeting_personal'=>'',
							'edit_customer_greeting_personal_relogon' =>'',
							'edit_greeting_guest' =>'',
							'date_added' => $SERVER_DATE);
		}

		if(isset($update_sql_data))
       		 $sql_data_array = array_merge($sql_data_array, $update_sql_data);

        tep_db_perform(TABLE_TEMPLATE, $sql_data_array);
        $template_id = tep_db_insert_id();
        
		
		if (!file_exists(DIR_FS_TEMPLATES . "/" . $template_name)) 
		{
    		if(@ mkdir(DIR_FS_TEMPLATES . "/" . $template_name, 0744)===false)
			{
  		  
				$FSESSION->set("config_error",sprintf(TEMP_CREATION_FOLDER_ERROR,$template_name));
				$error=true;
			} 
		} 
		if (!file_exists(DIR_FS_TEMPLATES . "/" . $template_name . '/boxes')) 
		{
		    if( @mkdir(DIR_FS_TEMPLATES . "/" . $template_name . '/boxes', 0744)===false){
  		   		$FSESSION->set("config_error",sprintf(TEMP_CREATION_FOLDER_ERROR,$template_name));
				$error=true;
			} 
  		}
		if (!file_exists(DIR_FS_TEMPLATES . "/" . $template_name . '/images')) 
		{
		if( @ mkdir(DIR_FS_TEMPLATES . "/" . $template_name . '/images', 0744)===false){
  		 		$FSESSION->set("config_error",sprintf(TEMP_CREATION_FOLDER_ERROR,$template_name));
				$error=true;
			}
  		}
		$infobox_sql=$FREQUEST->postvalue('info_box_sql');
		if($infobox_sql!='')
		{
			$sql_sql = "select * from " . TABLE_INFOBOX_CONFIGURATION . " where template_id='" . tep_db_input($infobox_sql) . "'";
			$sql_query=tep_db_query($sql_sql);
			while($row=tep_db_fetch_array($sql_query)){
				$sql_array=array("template_id"=>$template_id,
								 "infobox_file_name"=>$row['infobox_file_name'],
								 "infobox_display"=>$row['infobox_display'],
								 "display_in_column"=>$row['display_in_column'],
								 "location"=>$row['location'],
								 "sort_order"=>$row['sort_order'],
								 "box_heading"=>$row['box_heading'],	 
								 "date_added"=>$SERVER_DATE);
				tep_db_perform(TABLE_INFOBOX_CONFIGURATION, $sql_array);
			}				
		}else
		{
		
			$template_sql_file = DIR_FS_TEMPLATES . "/" . $template_name . "/" . $template_name . ".sql";
			$data = "";
			if(file_exists($template_sql_file))
			{
				if (($fp = fopen($template_sql_file,"r"))) 
				{
					while (!feof($fp))$data .= fgets($fp);
					$data = str_replace('#tID#', $template_id, $data); 
					$data = str_replace(';', '', $data); 
					tep_db_query($data);
					tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($template_name) . "' where configuration_key = 'DEFAULT_TEMPLATE'");
				}
			}
		}
		
		$this->doItems();
		
	}
	function doNew_Edit()
	{
	global $FREQUEST,$jsData;?>
	<form name="new_template" id="new_template" action="shop_template_configuration.php" method="post" enctype="multipart/form-data" >
	<table border="0" cellpadding="5" cellspacing="0" width="100%" class="dataTableContent">
		 <tr> 
		 <td></td>
		 </tr>
 		 <tr>
    		 <td width="100%" valign="top" class="main" colspan="2">
				<b><?php echo TEXT_NEW_INTRO?></b>
			 </td>
  `		</tr>
  		<tr>
			<td class="main">
				  <?php 
					if ($handle = opendir(DIR_FS_TEMPLATES)) 
					{
						$template_sql = "select template_name from " . TABLE_TEMPLATE;
						$template_query = tep_db_query($template_sql);
						$template_array = array();
						while($template_result = tep_db_fetch_array($template_query))
						{
							$template_array[] = $template_result["template_name"];
						}
						
						while (false !== ($file = readdir($handle))) 
						{ 
							if(is_dir(DIR_FS_TEMPLATES . '/' . $file) && stristr($curr_templates.".,..,seo,content", $file) == FALSE)
							{
								if($first_template_name=="")
								$first_template_name=$file;
								$dirs[] = $file;
								if(!in_array($file,$template_array))
								$dirs_array[] = array('id' => $file,
													'text' => $file);			
							}
						}
						closedir($handle); 
					}
      
					  if(count($dirs_array) == 0)
					  {
						echo  TEXT_TEMPLATE_NAME . '&nbsp;&nbsp;' . tep_draw_input_field('template_name');
					  }
					  else
					  {
						sort($dirs_array);
						echo TEXT_TEMPLATE_NAME . '&nbsp;&nbsp;' . tep_draw_pull_down_menu('template_name', $dirs_array, '', "style='width:150;' ");
					  }
    					?>
				</td>
	  			<Td class="main" nowrap>  
      					<?php echo TEXT_TEMPLATE_IMAGE . '&nbsp;&nbsp;' . tep_draw_file_field('template_image_file');
						echo tep_draw_hidden_field('template_image','');?>
	 			</Td>
			</tr>
	 		<tr>
				<td style="" id="show_infobox_sql" colspan="2">
					<?php 
					$template_query = tep_db_query("select * from " . TABLE_TEMPLATE);
					
					$template_array=array();
					while($template = tep_db_fetch_array($template_query))
					{
						if (DEFAULT_TEMPLATE == $template['template_name'])
						{ 
							$template['template_name']=$template['template_name'].' (default)';
						$default_template_id=$template['template_id'];
						}
						$template_array[]=array('id'=>$template['template_id'],'text'=>$template['template_name']);
						
					}
					
					echo TEXT_INFOBOX_CONFIGURATION; echo tep_draw_pull_down_menu('info_box_sql',$template_array,$default_template_id,'id="info_box_sql"');?>
				</td>
			</tr>
	 		<tr>
				<td>
					<?php echo tep_draw_separator('pixel_trans.gif','1','10');?>
				</td>
			</tr>
	 </table>
	 </form>
	<?php 
 
	$jsData->VARS["updateMenu"]=",new_update,";
	$display_mode_html=' style="display:none"';
 
	

}
	function doSort()
	{
		global $FREQUEST,$jsData;
		
		$sort=$FREQUEST->getvalue('sort');
		
		$this->doItems(' order by template_name '.$sort);
	}
	
	
	function doItems($sorting='')
	{
	
		global $FREQUEST,$jsData;
		$template=getListTemplate();
		$rep_array=array("TYPE"=>$this->type,
						"ID"=>-1,
						"NAME"=>HEADING_NEW_TEMPLATE,
						"IMAGE_PATH"=>DIR_WS_IMAGES,
						"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
						"UPDATE_RESULT"=>'doTotalResult',
						"ROW_CLICK_GET"=>'New_Edit',
						"FIRST_MENU_DISPLAY"=>"display:none"
		);
		?>
	<div class="main" id="shopConfig-lmessage"></div>
	<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
		<tr>
		<td><?php 	echo mergeTemplate($rep_array,$template); ?>
		</td>
		</tr>
		<tr>
			<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%">
					<tr class="dataTableHeadingRow">
							<td valign="top">
							
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td class="main" width="20%">
										<b><?php echo HEADING_TITLE;?></b>
										</td>
										<td width="60">
										
										<a href="javascript:void(0)" onClick="javascript:return doPageAction({'id':-1,'get':'sort','imgUpdate':false,'type':'shopConfig','style':'boxRow','result':doTotalResult,'params':'sort=asc'});"><?php echo tep_image(DIR_WS_IMAGES . "template/icon_ascending.gif",TEXT_ASCENDING,'','','align=absmiddle');?></a>
										<img src="<?php echo DIR_WS_IMAGES;?>/template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doPageAction({'id':-1,'get':'sort','imgUpdate':false,'type':'shopConfig','style':'boxRow','result':doTotalResult,'params':'sort=desc'});"><?php echo tep_image(DIR_WS_IMAGES."template/icon_descending.gif",TEXT_DESCENDING,'','','align=absmiddle');?></a>
										
										</td>
									</tr>
								</table>
								
							</td>
						</tr>
					<tr>
						<td>
						<div align="center"><?php $this->doList($sorting);?></div>
						</td>
					</tr>	
				</table>
			</td>
		</tr>
	</table>
	<?php 
	}
	
	function doList($sorting='')
	{
		global $FSESSION,$FREQUEST,$jsData;
		$query_split=false;
		$template_sql = "select template_name,template_id,active from " . TABLE_TEMPLATE.$sorting;
		
		$template_query=tep_db_query($template_sql);
		$found=false;
		if (tep_db_num_rows($template_query)>0) $found=true;
		if($found)
		{
			$template=getListTemplate();
			$icnt=1;
			while($template_result=tep_db_fetch_array($template_query)){
				
				
				if(DEFAULT_TEMPLATE == $template_result['template_name'])
					$template_result["template_name"]='<b>'.$template_result['template_name'].'(default)</b>';
				
				$rep_array=array("ID"=>$template_result["template_id"],
								"TYPE"=>$this->type,
								"NAME"=>$template_result["template_name"],
								"IMAGE_PATH"=>DIR_WS_IMAGES,
								"STATUS"=>'<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $template_result["template_id"] .",get:'Change_template_status',result:doSimpleResult,params:'tID=". $template_result["template_id"] . "&flag=" .($template_result["active"]==1?0:1) . "'});\">" . tep_image(DIR_WS_IMAGES . 'template/' . ($template_result["active"]==1?'icon_active.gif':'icon_inactive.gif')) . '</a>',
								"UPDATE_RESULT"=>'doDisplayResult',
								"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
								"ROW_CLICK_GET"=>'Info',
								"FIRST_MENU_DISPLAY"=>""
								);
								
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}
		}
		else 
		{
		echo '<div align="center" class="main">' . TEXT_EMPTY_TEMPLATES . '</div>';
		}
		if (!isset($jsData->VARS["Page"]))
		{
			$jsData->VARS["NUclearType"][]=$this->type;
		} 
		return $found;			
	}
	
		function doInfo($tID=0){
		global $FREQUEST,$FSESSION,$currencies,$jsData;
			
		if($tID <= 0)$tID=$FREQUEST->getvalue("tID","int",0);
		
		
		$template_query = tep_db_query("select * from " . TABLE_TEMPLATE . "  where template_id='" .tep_db_input($tID) . "'");
		
		if (tep_db_num_rows($template_query)>0)
		{
	 		$template_result = tep_db_fetch_array($template_query);
		
			$template=getInfoTemplate($tID);
			
			$rep_array=array("TYPE"=>$this->type,
							"ENT_TEMPLATE_NAME"=>TEXT_TEMPLATE_NAME,
							"TEMPLATE_NAME"=>$template_result['template_name'],
							"ENT_DATE_ADDED"=>TEXT_DATE_ADDED,
							"IMAGE"=>tep_product_small_image($template_result["template_image"],''),
							"DATE_ADDED"=>format_date($template_result['date_added']),
							"ENT_DATE_MODIFIED"=>(format_date($template_result['last_modified'])!=''?TEXT_DATE_MODIFIED:''),
							"DATE_MODIFIED"=>format_date($template_result['last_modified']),
							"ID"=>$template_result["template_id"]
							);
		
			echo mergeTemplate($rep_array,$template);
			$jsData->VARS["updateMenu"]=",normal,";
		}
		else 
		{
			echo 'Err:' . TEXT_TEMPLATE_FOUND;
		}
	}	

	function doEdit() 
	{
		global $FREQUEST,$jsData,$FSESSION;
		$tID=$FREQUEST->getvalue("tID","int",0);
		if($tID>0)
			{
			  $template_query = tep_db_query("select * from " . TABLE_TEMPLATE . "  where template_id='" .tep_db_input($tID) . "'");
			  $template = tep_db_fetch_array($template_query);
			} else {
			 $template=array('template_name'=>'','date_added'=>'','last_modified'=>'','template_image'=>'');
			}
	  		$tInfo_array = ($template);
	 		$tInfo = new objectInfo($tInfo_array);
			$template_id=$tInfo->template_id;?>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr> 
				<td class="main" width="20"><?php echo tep_image(DIR_WS_IMAGES . 'template/img_edit.gif')?>
				</td><td class="smalltext"><b><font color="#6666FF"><?php echo  TEXT_EDIT ;?></font></b></td> 
			</tr>
		</table>
		<form name="template_edit" id="template_edit" action="shop_template_configuration.php" method="post">
		<input type="hidden" name="template_id" id="template_id" value="<?php echo tep_output_string($tID);?>" />
		<table border="0" cellpadding="0" cellspacing="0" width="100%" >
  			<tr>
   				<td width="100%" valign="top" class="smalltext">
					<table border="0" cellpadding="5" cellspacing="0" width="100%">
	 					 <tr>
	  						<td width="20%"  colspan="1" class="smalltext"><?php echo TEXT_SET_DEFAULT;?></td>
       						<td width="40%"  colspan="1" class="smalltext"><?php echo tep_draw_hidden_field('template_name', $tInfo->template_name); ?>
							 	  <?php 
								  if (DEFAULT_TEMPLATE != $tInfo->template_name) 
								  {
											echo  tep_draw_checkbox_field('default','on');
								  }else{
											echo tep_draw_checkbox_field('default','on',true);
								  }	
								?>
							</td>		
         					<td width="20%" colspan="1" class="smalltext">
							<?php echo TEXT_HEADER_HEIGHT;?> 
							</td>
							<td width="40%"  colspan="1" class="smalltext">	
							<?php echo tep_draw_input_field('header_height', $tInfo->header_height,'size="3"');?>
		  					 </td>
   						</tr>
					<tr>
       						<td class="smalltext" nowrap><?php echo TEXT_INCLUDE_CART_IN_HEADER;?></td>
							<td id='bullet_cart_in_header'><?php 
									 if ($tInfo->cart_in_header == 'yes') 
									 {
										echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=no&case=cart_in_header',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
									 } else 
									 {
										echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=yes&case=cart_in_header',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
									  }
									?>
       						 </td>
                             <td class="smalltext" nowrap> <?php echo TEXT_INCLUDE_LANGUAGES_IN_HEADER;?></td>
		                     <td class="smalltext" id="bullet_languages_in_header"><?php 
									  if ($tInfo->languages_in_header == 'yes') 
									  {
											echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=no&case=languages_in_header',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
									  } else 
									  {
											echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=yes&case=languages_in_header',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
									  }
								?>
      						 </td>
   						 </tr>
						 <tr>

       						<td class="smalltext" nowrap><?php echo TEXT_BREADCRUMB; ?></td>
							<td class="smallText" id="bullet_show_breadcrumb">
								 <?php
								  if ($tInfo->show_breadcrumb == 'yes') 
								  {
										echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=no&case=show_breadcrumb',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
								  } else 
								  {
									echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=yes&case=show_breadcrumb',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
								  }
								?>
							</td>
							<tr>
							 <td>
                             <table>
				                <tr>
                    <td class="main" valign="top" width="150"><?php if ($icnt == 0) echo TEMPLATE_COLOR?></td>
                    <td class="main" valign="top">
					<?php 
					//quick template color list
					if($tInfo->template_id==3)
					{
					$colours_available = array('def'=>'default',
											   'green'=>'green',
											   'blue'=>'blue',
											   'valencia'=>'valencia',
											   'victoria'=>'victoria',
											   'cyan'=>'cyan',
											   'ruby'=>'ruby',
											   'steelblue'=>'steelblue',
											   ); 
										   
					}elseif($tInfo->template_id==2)
					{
					$colours_available = array(
											   'gold'=>'gold',
											   'blue'=>'blue',
										   ); 	
					}elseif($tInfo->template_id==4)
					{
					$colours_available = array(
											   'red'=>'red',
										   );
					}else{
					$colours_available = array(
											   'default'=>'default',
										   );	
					}
					
				   $select_box = '<select name="template_color" " style="width: 100" class="inputNormal">';
									foreach($colours_available as $let=>$color){ 
										  $select_box .= '<option value="' . $color . '"';
										  if ($color == $tInfo->template_color) $select_box .= ' SELECTED';
										  $select_box .= '>' . $color . '</option>';
									}
									$select_box .= "</select>";
									echo $select_box;?>.css
					</td>
                </tr>
				  </table>
							 </td>
							 <td colspan="3">
								 </td>
						</tr>
  				</table>
		<table border="0" cellpadding="5" cellspacing="0" width="99%" >
						<tr>
							<Td style="background:#DEE4F5;" height=40 class="smalltext" width="35%" colspan="2"><b><?php echo tep_image(DIR_WS_IMAGES . 'template/img_bullet1.gif') . '&nbsp;&nbsp;&nbsp;&nbsp;' . '<font color="#6666FF">' . TEXT_LEFT_COLUMN . '</font>'  ;?></b></Td>
							<Td  class="smalltext" width="30%" colspan="2"><b><?php echo tep_image(DIR_WS_IMAGES . 'template/img_bullet1.gif') . '&nbsp;&nbsp;&nbsp;&nbsp;' . '<font color="#6666FF">' . TEXT_MAIN_TABLE .  '</font>' ;?></b>
							</Td>
						</tr>
						<tr>
	 						<td width="10%" style="background:#DEE4F5;" class="smallText" nowrap><?php echo TEXT_INCLUDE_COLUMN_LEFT ;?></td>
							<td width="25%" style="background:#DEE4F5;" class="smallText" id="bullet_include_column_left">
									<?php
									  if ($tInfo->include_column_left == 'yes') 
									  {
									  echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=no&case=include_column_left',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
										} else 
										{
											
											echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=yes&case=include_column_left',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
									  }
									?>
							</td>			
        					<td class="smalltext" nowrap><?php echo TEXT_SHOW_TOPBAR; ?></td>
							<td class="smallText" id="bullet_show_topbar">
								 <?php
								  if ($tInfo->show_topbar == 'yes') 
								  {
										echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=no&case=show_topbar',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
								  } else 
								  {
									echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=yes&case=show_topbar',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
								  }
								?>
							</td>
						</tr>
						<tr>
       						<td width="10%" style="background:#DEE4F5;" class="smallText" nowrap><?php echo TEXT_INCLUDE_COLUMN_RIGHT;?></td>
							<td width="25%" style="background:#DEE4F5;" class="smallText" id="bullet_include_column_right">
									 <?php  if ($tInfo->include_column_right == 'yes') 
												echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=no&case=include_column_right',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
											else
												echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=yes&case=include_column_right',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
										?>
							</td>
							<td class="smalltext" width="10%" nowrap><?php echo TEXT_SHOW_HEADER_PANE; ?></td>
      						<td class="smalltext" width="20%" id="bullet_show_header_pane">
									 <?php
									  if ($tInfo->show_header_pane == 'yes') 
									  {
											echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=no&case=show_header_pane',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
									  } else 
									  {
											echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=yes&case=show_header_pane',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
									  }
									?>
       						</td>
							</tr>
							<tr>
					<td class="smalltext" width="10%" nowrap><?php echo TEXT_SHOW_LOGIN; ?></td>
      						<td class="smalltext" width="20%" id="bullet_show_page_descriptions">
									 <?php
									  if ($tInfo->show_page_descriptions == 'yes') 
									  {
											echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=no&case=show_page_descriptions',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
									  } else 
									  {
											echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=yes&case=show_page_descriptions',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
									  }
									?>
							
      						
       						
      				   </tr>


     				   <tr>
        					<td class="smalltext" style="background:#DEE4F5;" nowrap>
       							 <?php //echo TEXT_COLUMN_LEFT_WIDTH;?>
   						 </td>
       						 <td style="background:#DEE4F5;" >
       							  <?php //echo tep_draw_input_field('box_width_left', $tInfo->box_width_left,'size="3"');?>
							 </td>
        					 <td class="smalltext"><?php echo TEXT_CUSTOMER_GREET; ?>
						 </td>
		 					 <td class="smalltext" id="bullet_customer_greeting">
								<?php
								if ($tInfo->customer_greeting == 'yes') 
								{
								echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=no&case=customer_greeting',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
								} 
								else 
								{
								echo '<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $tInfo->template_id .",get:'change_template_button_status',result:doSimpleResult,params:'tID=". $tInfo->template_id . "&status=yes&case=customer_greeting',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
								}?>
							 </td>
                            
                             
          </tr>
	                     <tr>
                             <td width="100%" colspan="2"  style="background:#DEE4F5;" valign="top">
			                      <table border="0" cellpadding="3" cellspacing="0" width="100%">
		                                 <tr>
				                              <td class="smalltext" colspan="2">
					                                <?php echo "&nbsp;&nbsp;&nbsp;&nbsp;<b> <?php echo TEXT_INFOBOXES; ?> </b>";?>
			                               </td>
                                    </tr>
										  <tr>
										  	  <td id="infobox_left">
													<?php
													$column_left_content=$this->infoboxes_left($tInfo->template_id);
													echo $column_left_content;
													?>
											</td>
									</tr>
							   </table>
						   </td>
								<td colspan="2" valign="top">
								<!-- here's the big space-->
								
								<img src="images/template.png" class="img-responsive" width="500">
						   </td>
			   
		  </tr>
				  </table>
			  </td>
				</tr>
		</table>
</form>
<?php 
	$jsData->VARS['updateMenu']=',update,';	
		
	}
	
	
	function domove_pos_updown(){
	global $SERVER_DATE,$FREQUEST,$jsData;
	$command=$FREQUEST->getvalue('command');
	$template_id=$FREQUEST->getvalue('tID','int',0);
	$pos=$FREQUEST->getvalue('position');
	$col=$FREQUEST->getvalue('column');
	if ((int)$pos>0){
		if ($command=='move_up'){
			$query=tep_db_query("select infobox_id,location from ".TABLE_INFOBOX_CONFIGURATION ." where location <=" . (int)$pos ." and template_id ='" . tep_db_input($template_id) . "' and display_in_column='" . $col . "' order by location desc limit 2");
		} else if($command=='move_down'){
			$query=tep_db_query("select infobox_id,location from ".TABLE_INFOBOX_CONFIGURATION ." where location >=" . (int)$pos ." and template_id ='" . tep_db_input($template_id) . "' and display_in_column='" . $col . "' order by location limit 2");
		}
		if (tep_db_num_rows($query)>0){
			$details=array();
			$update_sql="UPDATE " .TABLE_INFOBOX_CONFIGURATION . " set location='{1}' where infobox_id='{4}';\nUPDATE " . TABLE_INFOBOX_CONFIGURATION . " set location='{3}' where infobox_id='{2}';";
			$icnt=1;
			while($result=tep_db_fetch_array($query)){
				$update_sql=str_replace("{" . $icnt . "}",$result["location"],$update_sql);
				$icnt++;
				$update_sql=str_replace("{" . $icnt . "}",$result["infobox_id"],$update_sql);
				$icnt++;
			}
			$splt_sql=preg_split("/;/",$update_sql);
			tep_db_query($splt_sql[0]);
			tep_db_query($splt_sql[1]);
		}
	}
	if($col=='left')
	   $result=$this->infoboxes_left($template_id);
	 elseif($col=='right')
	 	$result=$this->infoboxes_right($template_id);
	 
	    
	
	$result=tep_db_input($result);
    $result=tep_db_prepare_input($result);
	
	$jsData->VARS["replace"]=array("infobox_".$col=>$result);
	
}
	
	function dochange_template_button_status()
{
	global $FREQUEST,$SERVER_DATE,$jsData;
  
	$template_id=$FREQUEST->getvalue('tID','int',-1);
	$case=$FREQUEST->getvalue('case');
	$flag=$FREQUEST->getvalue('status');
	$iID=$FREQUEST->getvalue('info_id');
     if(($flag == 'yes') || ($flag == 'no')) 
	 {
	        if($case != 'infobox_display')
			{
	            tep_db_query("update " . TABLE_TEMPLATE . " set  $case = '" . $flag . "',last_modified='". tep_db_input($SERVER_DATE) ."' where template_id = '" . (int)$template_id . "'");
				
				
				$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $template_id .",get:\'change_template_button_status\',result:doSimpleResult,params:\'tID=". $template_id . "&status=".(($flag=='yes')?'no':'yes'). "&case=".$case."\',message:\'".TEXT_UPDATING_STATUS."\'});\">" . (($flag=='yes')?tep_image(DIR_WS_IMAGES . 'template/icon_active.gif'):tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif')) . '</a>';
				$jsData->VARS["replace"]=array("bullet_".$case=>$result);
         	}else
			{

                 
				if($flag=='no')
				{
				    tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set $case = '" . $flag . "',last_modified='". tep_db_input($SERVER_DATE) ."',location=0 where infobox_id = '" . (int)$iID . "'");
				}
				else
				{
				    tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set $case = '" . $flag . "',last_modified='". tep_db_input($SERVER_DATE) ."',location=999 where infobox_id = '" . (int)$iID . "'");
				}
			  $info_query=tep_db_query("select location,display_in_column from " . TABLE_INFOBOX_CONFIGURATION . " where infobox_id='" . (int)$iID. "'");
					  if(tep_db_num_rows($info_query)>0) {
						$info_result=tep_db_fetch_array($info_query);
						tep_template_box_realign($template_id,$info_result["display_in_column"]);
					 }
			if($info_result["display_in_column"]=='left') 
			     $result=$this->infoboxes_left($template_id);
			elseif($info_result["display_in_column"]=='right')
				$result=$this->infoboxes_right($template_id);

//$result=trim($result);
			$result=tep_db_input($result);
			

$result=tep_db_prepare_input($result);
			 $jsData->VARS["replace"]=array("infobox_".$info_result["display_in_column"]=>$result);
            
          }
    }
	
}


	
}

	function getListTemplate(){
		ob_start();
		getTemplateRowTop();
	?>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
			<tr>
				<td>
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="2%" id="shopConfig##ID##bullet">##STATUS##</td>
						<td width="28%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'tID=##ID##'});" id="##TYPE####ID##name">##NAME##</td>
						<td width="15%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
							<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
								<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'tID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
								<img src="##IMAGE_PATH##template/img_bar.gif"/>
								<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteTemplate','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'tID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
								<img src="##IMAGE_PATH##template/img_bar.gif"/>
							</span>
							<span id="##TYPE####ID##mupdate" style="display:none">
								<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','imgUpdate':true,'type':'##TYPE##','style':'boxRow','uptForm':'template_edit','customUpdate':doTemplateUpdate,'result':doDisplayResult,'params':'tID=##ID##','message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
								<img src="##IMAGE_PATH##template/img_bar.gif"/>
								<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'shopConfig','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
								<img src="##IMAGE_PATH##template/img_bar.gif"/>
							</span>
							<span id="##TYPE####ID##mnew_update" style="display:none">
								<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':-1,'get':'NewUpdate','imgUpdate':true,'type':'shopConfig','style':'boxRow','uptForm':'new_template','result':doTotalResult,'params':'tID=##ID##','message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
								<img src="##IMAGE_PATH##template/img_bar.gif"/>
								<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':-1,'get':'Edit','type':'shopConfig','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
								<img src="##IMAGE_PATH##template/img_bar.gif"/>
							</span>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
	<?php
		getTemplateRowBottom();
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	
	function getInfoTemplate(){
		ob_start();
?>
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<div class="hLineGray"></div>
			<tr>
				<td style="padding-left:50px;" valign="top">
					<table cellpadding="2" cellspacing="0" width="100%">
						<tr>
							<td width="120">##IMAGE##</td>
							<td valign="top">
								<table cellpadding="2" cellspacing="0" width="75%">
									<tr>
										<td class="main" align="left" width="150" height="30">##ENT_TEMPLATE_NAME##</td>
										<Td class="main" align="left">##TEMPLATE_NAME##</Td>
									</tr>
									<tr>
										<td class="main" align="left" height="30">##ENT_DATE_ADDED##</td>
										<Td class="main" align="left">##DATE_ADDED##</Td>
									</tr>
									<tr>
										<td class="main" align="left" height="30">##ENT_DATE_MODIFIED##</td>
										<Td class="main" align="left">##DATE_MODIFIED##</Td>
									</tr>
								</table>	
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
<?php
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	
	function image_resize($image){
	
	$images = getimagesize($image);

	$width = $images[0];
	$height = $images[1];
	$co_width = $width/5;
	$width = $width-$co_width;
	$co_height = $height/3;
	$height = $height - $co_height;
	return tep_image($image,'',$width,$height);
}


?>
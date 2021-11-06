<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	defined('_FEXEC') or die();
	class shopInfoboxConfiguration
	{
		var $pagination;
		var $splitResult;
		var $type;

		function __construct() {
		$this->pagination=false;
		$this->splitResult=false;
		$this->type='shInfo';
		}
		
		function doInfoboxSort(){
		global $SERVER_DATE,$FREQUEST;
		
		$flag=$FREQUEST->getvalue('move');
		$cID=$FREQUEST->getvalue('rID','int',-1);
		$gID=$FREQUEST->getvalue('gID','int',-1);
		$location=$FREQUEST->getvalue('location');
		$col=$FREQUEST->getvalue('column');
		
		$query=tep_db_query("select max(location) as max,min(location) as min from " . TABLE_INFOBOX_CONFIGURATION);
		$result=tep_db_fetch_array($query);
		
		if($flag=='up'){
			tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set location=location+1,last_modified='" . tep_db_input($SERVER_DATE)  . "' where location='". ($location-1) . "' and display_in_column='". $col . "' and infobox_display='yes'" );
			tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set location=location-1,last_modified='" . tep_db_input($SERVER_DATE)  . "' where location='" . $location . "' and infobox_id='" . (int)$cID . "' and display_in_column='". $col . "' and infobox_display='yes'");
		} else if($flag=='down'){
			tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set location=location-1,last_modified='" . tep_db_input($SERVER_DATE)  . "' where location='". ($location+1) . "' and display_in_column='". $col . "' and infobox_display='yes'" );
			tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set location=location+1,last_modified='" . tep_db_input($SERVER_DATE)  . "' where location='" . $location . "' and infobox_id='" . (int)$cID . "' and display_in_column='". $col . "' and infobox_display='yes'");
		}
	
		$this->doItems($gID);
		
		}
	
		
		
		function doChange_Column()
		{
		global $FREQUEST,$SERVER_DATE,$jsData;
		
		
		$infobox_id=$FREQUEST->getvalue('rID','int',-1);
		$flag=$FREQUEST->getvalue('column');
		$gID=$FREQUEST->getvalue('gID');

		
		
		$info_query=tep_db_query("select location,display_in_column from " . TABLE_INFOBOX_CONFIGURATION . " where infobox_id='" . tep_db_input($infobox_id). "'");
			if (tep_db_num_rows($info_query)>0) {
				$info_result=tep_db_fetch_array($info_query);
				tep_template_box_align($gID,$info_result["location"],$info_result["display_in_column"],"remove");
				tep_template_box_align($gID,$info_result["location"],$flag,"add",true);
				tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set display_in_column = '" . $flag . "',last_modified='" . tep_db_input($SERVER_DATE)  . "' where infobox_id = '" . (int)$infobox_id . "'");
			}
			
			$this->doItems($gID);
			
	
			
		
		}
		
		
		function doItems($gID=""){
		global $FREQUEST,$jsData,$template_array;

		if($gID<=0) $gID=$FREQUEST->getvalue('gID','int',0);

		if($FREQUEST->getvalue('gID','int',0)<=0 && $gID<=0) $gID=$template_array[0]['id'];	

		$template=getListTemplate();
		$rep_array=array("TYPE"=>$this->type,
						"ID"=>-1,
						"GID"=>$gID,
						"NAME"=>TEXT_NEW_INFOBOX,
						"IMAGE_PATH"=>DIR_WS_IMAGES,
						"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
						"COLUMN"=>'',
						"SORTING"=>'',
						"UPDATE_RESULT"=>'doTotalResult',
						"ROW_CLICK_GET"=>'Edit',
						"FIRST_MENU_DISPLAY"=>"display:none"
		);
		?>
	<div class="main" id="shInfo-lmessage"></div>
	<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
		<tr><td><?php 	echo mergeTemplate($rep_array,$template); ?></td></tr>
		<tr>
			<td><table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%">
					<tr class="dataTableHeadingRow">
						<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="main" width="30%"><b><?php echo  TABLE_HEADING_INFOBOX_FILE_NAME;?></b></td>
								<td width="15%">&nbsp;</td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td><div align="center"><?php $this->doList($gID);?></div></td>
					</tr>	
				</Table>
			</td>
		</tr>
	</table>
	<?php 
	} 
	function doList($gID){
		global $FSESSION,$currencies,$FREQUEST,$jsData,$template_array;
		tep_template_box_check($gID);
		$count_left_active = 0;
		$count_right_active = 0;
		$totInf_boxes = 0;
		$avail_boxes=0;
		$display='0';
		
		$templates_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " where template_id = " . (int)$gID );
		$template = tep_db_fetch_array($templates_query);
		if (file_exists(DIR_FS_TEMPLATES.$template['template_name']."/boxes") && ($handle = opendir(DIR_FS_TEMPLATES.$template['template_name']."/boxes"))) {
			/* This is the correct way to loop over the directory. */
				while (false !== ($file = readdir($handle))) { 
					if(is_file(DIR_FS_TEMPLATES .$template['template_name']. '/boxes/' . $file) && stristr($infobox_list.".,..", $file) == FALSE){
						$avail_boxes ++;
					}
				}
				closedir($handle); 
			
			if(sizeof($template_array)>0 && $gID=='2') $gID = $template_array[0]['id'];
			$configuration_query = tep_db_query("select *  from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . tep_db_input($gID) . "' order by display_in_column,location");
			$found=false;

			if(tep_db_num_rows($configuration_query)>0){
				$found=true;
				$template=getListTemplate();
				$icnt=1;
				 $row=0;
				while ($configuration = tep_db_fetch_array($configuration_query)) {
					if($row %2 ==0) $class="dataTableRowEven";
					else $class="dataTableRowOdd"; $row++;
					
					$totInf_boxes++;
					$cfgloc = $configuration['location'];
					$cfgValue = $configuration['infobox_display'];
					$cfgcol = $configuration['display_in_column'];
					$cfgtemp = $configuration['box_template'];
					$cfgkey = $configuration['infobox_define'];
					//$cfgfont = $configuration['box_heading_font_color'];
					if (($cfgcol == 'left') && ($cfgValue != 'no')) { 
						$count_left_active++;
					} else if (($cfgcol == 'right') && ($cfgValue != 'no')){
						$count_right_active++; 
					}
					
					$infobox_list .= $configuration['infobox_file_name']. ",";
					$display='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $configuration['infobox_id'] .",get:'StatusChange',result:doSimpleResult,params:'rID=". $configuration['infobox_id'] . "&gID=" . $configuration['template_id'] . "&status=" .($configuration['infobox_display']=='yes'?'no':'yes') . "','message':'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/' . ($configuration['infobox_display']=='yes'?'icon_active.gif':'icon_inactive.gif')) . '</a>';
					
					$display_column='<a href="javascript:void(0)" onclick="javascript:doPageAction({id:'. $configuration['infobox_id'] .",'type':'".$this->type."',get:'Change_Column',result:doTotalResult,params:'rID=". $configuration['infobox_id'] . "&gID=" . $configuration['template_id'] . "&column=" .($configuration['display_in_column']=='left'?'right':'left') . "','message':'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/' . ($configuration['display_in_column']=='left'?'arrow_right.gif':'arrow_left.gif')) . '</a>';
				
					$left_move_display_query = tep_db_query("select min(location) as min_up,max(location)as max_down from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . tep_db_input($gID)."' and display_in_column='left' ");
					$left_move_display_array=tep_db_fetch_array($left_move_display_query);
					$right_move_display_query = tep_db_query("select min(location) as min_up,max(location) as max_down from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . tep_db_input($gID). "' and display_in_column='right'");
					$right_move_display_array=tep_db_fetch_array($right_move_display_query);
					
					$move_up='';
					$move_down='';
					
					if($configuration['display_in_column']=='left')
						{
						
						if($configuration['location']!=$left_move_display_array['min_up'])
						$move_up='<a href="javascript:void(0)" onclick="javascript:doPageAction({id:'. $configuration['infobox_id'] .",'type':'".$this->type."',get:'InfoboxSort',result:doTotalResult,params:'rID=". $configuration['infobox_id'] . "&location=".$cfgloc."&column=".$cfgcol."&move=up&gID=" . $configuration['template_id'] . "','message':'".TEXT_SORTING_DATA."'});\">" . tep_image(DIR_WS_IMAGES . 'template/img_arrow_up.gif','Up') . '</a>';
					
						if($configuration['location']!=$left_move_display_array['max_down'])
						$move_down='<a href="javascript:void(0)" onclick="javascript:doPageAction({id:'. $configuration['infobox_id'] .",'type':'".$this->type."',get:'InfoboxSort',result:doTotalResult,params:'rID=". $configuration['infobox_id'] . "&location=".$cfgloc."&column=".$cfgcol. "&move=down&gID=" . $configuration['template_id'] . "','message':'".TEXT_SORTING_DATA."'});\">" . tep_image(DIR_WS_IMAGES . 'template/img_arrow_down.gif','Down') . '</a>';
					
						
						}
						
					 if($configuration['display_in_column']=='right')
						{
						if($configuration['location']!=$right_move_display_array['min_up'])
						{
						$move_up='<a href="javascript:void(0)" onclick="javascript:doPageAction({id:'. $configuration['infobox_id'] .",'type':'".$this->type."',get:'InfoboxSort',result:doTotalResult,params:'rID=". $configuration['infobox_id'] . "&location=".$cfgloc."&column=".$cfgcol . "&move=up&gID=" . $configuration['template_id'] . "','message':'".TEXT_SORTING_DATA."'});\">" . tep_image(DIR_WS_IMAGES . 'template/img_arrow_up.gif','Up') . '</a>';
						}
					if($configuration['location']!=$right_move_display_array['max_down'])
						{
						$move_down='<a href="javascript:void(0)" onclick="javascript:doPageAction({id:'. $configuration['infobox_id'] .",'type':'".$this->type."',get:'InfoboxSort',result:doTotalResult,params:'rID=". $configuration['infobox_id'] . "&location=".$cfgloc."&column=".$cfgcol. "&move=down&gID=" . $configuration['template_id'] . "','message':'".TEXT_SORTING_DATA."'});\">" . tep_image(DIR_WS_IMAGES . 'template/img_arrow_down.gif','Down') . '</a>';
						}
					
						
						}
				
					
					$name= '<font color="black">' . $configuration['box_heading'] . '</font>';
					$rep_array=array("ID"=>$configuration['infobox_id'],
									"GID"=>$gID,
									"TYPE"=>$this->type,
									"NAME"=>$name,
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"STATUS"=>$display,
									"COLUMN"=>$display_column,
									"MOVE_UP"=>$move_up,
									"MOVE_DOWN"=>$move_down,
									"TEXT_INFOBOX_CONTENT"=>$infobox_content,
									"UPDATE_RESULT"=>'doDisplayResult',
									"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
									"ROW_CLICK_GET"=>'InfoboxInfo',
									"FIRST_MENU_DISPLAY"=>""
									);
					echo mergeTemplate($rep_array,$template);
					$icnt++;
				}  ?>
				<tr>
					<td height="20" colspan="3"></td>
				</tr>
				<tr class="dataTableRowEvenOver">
					<td class="dataTableContent"  colspan="5" align="center"><?php echo '<br>There are currently <br>'. $count_left_active . ' active boxes in the left column and <br>'. $count_right_active . ' active boxes in the right column' . '<br><br>'?></td>
				</tr>	
			<?php }		
			if($avail_boxes>0)
			{ 
					$display='1';
			}
			else if($avail_boxes == 0)
			{
				$display='0';
			?>
			<tr>
				<td colspan="5">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td class="infoBoxContent" align="center">
								<?php echo "This template does not have any infoboxes to install. Please put the infoboxes that you want to install in this template's boxes directory."; ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?php
			} 
			
		} else { $display='0';?>
			<tr>
				<td colspan="5">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td class="infoBoxContent" align="center">
								<?php echo "This template does not have any infoboxes to install. Please put the infoboxes that you want to install in this template's boxes directory."; ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		<?php } ?>
	
	<?php  
		if (!isset($jsData->VARS["Page"])){
			$jsData->VARS["NUclearType"][]=$this->type;
		} 
		return $found;			
	}
	function doInfoboxInfo($gID=0,$infobox_id=0){
		global $FSESSION,$FREQUEST,$currencies;
		if($infobox_id<=0) $infobox_id=$FREQUEST->getvalue("rID","int",0);
		if($gID<=0) $gID=$FREQUEST->getvalue('gID','int',0);
		$configuration_query = tep_db_query("select *  from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . tep_db_input($gID) . "' and infobox_id='" . tep_db_input($infobox_id) . "' order by display_in_column,location");
		if (tep_db_num_rows($configuration_query)>0)
		{
			$configuration=tep_db_fetch_array($configuration_query); ?>
			<table cellpadding="2" cellspacing="2" border="0" width="100%">
				<tr>
					<td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif','10','10'); ?></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<table border="0">
							<tr>
								<td class="main" width="150"><?php echo TEXT_FILENAME; ?></td>
								<td class="main"><?php echo $configuration['infobox_file_name']; ?></td>
								<td class="main" width="125"><?php echo TEXT_TITLE; ?></td>
								<td class="main" width="175"><?php echo $configuration['box_heading']; ?></td>
							</tr>
					   </table>
					</td>
				</tr>
				<tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif','20','20'); ?></td>
				</tr>
		 	</table>
		<?php 	
			$jsData->VARS["updateMenu"]=",normal,";
		} else {
			echo 'Err:' . TEXT_PRODUCT_NOT_FOUND;
		}
	}
	function doEdit() {
		global $FREQUEST,$jsData;
		$gID=$FREQUEST->getvalue('gID','int',0);
		$infobox_id=$FREQUEST->getvalue('rID','int',0);
		
		$configuration_query = tep_db_query("select *  from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . tep_db_input($gID) . "' and infobox_id='" . tep_db_input($infobox_id) . "'order by display_in_column,location");
		$configuration=tep_db_fetch_array($configuration_query);
		
		$templates_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " where template_id = " . tep_db_input($gID));
		$template = tep_db_fetch_array($templates_query);
		
		if ($handle = opendir(DIR_FS_TEMPLATES.$template['template_name']."/boxes")) {
			/* This is the correct way to loop over the directory. */
			while (false !== ($file = readdir($handle))) { 
				if(is_file(DIR_FS_TEMPLATES .$template['template_name']. '/boxes/' . $file) && stristr($infobox_list.".,..", $file) == FALSE){
					$dirs[] = $file;
					$dirs_array[] = array('id' => $file,
					'text' => $file);
				}
			}
			closedir($handle); 
		}	
		
		if(tep_db_num_rows($configuration_query)>0)
			$cInfo=new objectInfo($configuration);
		?>
			<form name="infoboxes" action="shop_infobox_configuration.php" method="post">
			<?php 
				echo tep_draw_hidden_field('tempId',$gID); 
				echo tep_draw_hidden_field('infoBoxId',$infobox_id); 			?>
			<table border="0" width="100%" cellspacing="2" cellpadding="2">
				<tr>
					<td class="main" width="125"><?php echo TEXT_FILENAME;?></td>
					<td class="main" width="250"><?php echo (tep_db_num_rows($configuration_query)>0)?tep_draw_input_field('infobox_file_name',$cInfo->infobox_file_name,'size="20"','true'):tep_draw_pull_down_menu('infobox_file_name',$dirs_array,'', "style='width:150;'", 'true');?></td>
					<td width="50"><span onMouseOver="javascript:popupWindow('filename')" onMouseOut="javascript:kill();"><?php echo tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO)  ;?></span></td>
					<td class="main" width="125"><?php echo TEXT_INFOBOX_HEADING;?></td>
					<td class="main" width="250"><?php	echo tep_draw_input_field('box_heading',$cInfo->box_heading,'size="25"','true'); ?></td>
					<td width="50"><span onMouseOver="javascript:popupWindow('heading')" onMouseOut="javascript:kill();"><?php echo tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO);	?></span></td>
				</tr>

				
				<tr><td colspan="6"><?php echo tep_draw_separator('pixel_trans.gif','20','20');?></td></tr>
			</table>
			</form>
	<?php
		$jsData->VARS["updateMenu"]=",update,";
	}
	function doUpdate() {
		global $FREQUEST,$jsData;
		$server_date = getServerDate(true);
		$gID=$FREQUEST->postvalue('tempId','int',0);
		$infobox_id=$FREQUEST->postvalue('infoBoxId','int',0);
		$insert=0;
		if ($infobox_id<=0) {
			$position_query=tep_db_query("select max(location) position from " . TABLE_INFOBOX_CONFIGURATION . " where template_id='" . tep_db_input($gID) . "' and display_in_column='left' ");
			$position=tep_db_fetch_array($position_query);
			$configurations=$FREQUEST->postvalue('configuration');
			$location=tep_template_box_align($gID,$FREQUEST->postvalue("location"),$configurations["display_in_column"],"add",true); //($HTTP_POST_VARS["column"]!=$HTTP_POST_VARS["previous_column"]?true:false)
			$sql_array=array(
							"template_id"=>$gID,
							"infobox_file_name"=>$FREQUEST->postvalue('infobox_file_name'),
							"infobox_define"=>$FREQUEST->postvalue('infobox_define'),
							"display_in_column"=>'left',
							"infobox_display"=>'yes',
							"location"=>$position['position'] + 1,
							"box_heading"=>$FREQUEST->postvalue('box_heading'),
							//"box_template"=>$FREQUEST->postvalue('box_template'),
							"date_added"=>$server_date ,
							);
			tep_db_perform(TABLE_INFOBOX_CONFIGURATION,$sql_array);
			$infobox_id = tep_db_insert_id();
			$insert=1;
		} elseif ($infobox_id>0) 
		{ 
			if ($FREQUEST->postvalue('location')!=$FREQUEST->postvalue("previous_location") || $FREQUEST->postvalue("previous_column")!=$FREQUEST->postvalue('infobox_column')){
				//tep_template_box_align($gID,$HTTP_POST_VARS["previous_location"],$HTTP_POST_VARS["previous_column"],"remove");
				//$location=tep_template_box_align($gID,$HTTP_POST_VARS["location"],$HTTP_POST_VARS['infobox_column'],"add",true); //($HTTP_POST_VARS["column"]!=$HTTP_POST_VARS["previous_column"]?true:false)
			} else 
			{
				$location=$FREQUEST->postvalue("location");
			}
			$sql_array=array(
							"template_id"=>$gID,
							"infobox_file_name"=>$FREQUEST->postvalue('infobox_file_name'),
							"box_heading"=>$FREQUEST->postvalue('box_heading'),
							"last_modified"=>$server_date
			);
			tep_db_perform(TABLE_INFOBOX_CONFIGURATION,$sql_array,"update","infobox_id = '" . (int)$infobox_id . "'");
			$insert=0;
		}
		if($insert>0) {
			$this->doItems($gID);
		}
		else {
			//$jsData->VARS["replace"]=array($this->type. $infobox_id . "name"=>'<font color="' . $FREQUEST->postvalue('hexval') . '">' . $FREQUEST->postvalue('box_heading') . '</font>');
			$jsData->VARS["prevAction"]=array('id'=>$infobox_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
			$this->doInfoboxInfo($gID,$infobox_id);
			$jsData->VARS["updateMenu"]=",normal,";
		}
	}
	function doDeleteInfobox() {
		global $FREQUEST,$jsData;
		$gID=$FREQUEST->getvalue('gID','int',0);
		$infobox_id=$FREQUEST->getvalue('rID','int',0);

		$delete_message='<p><span class="smallText">' . TEXT_INFO_DELETE_INTRO . '</span>';
?>
		<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="shop_infobox_configuration.php" method="post" enctype="application/x-www-form-urlencoded">
			<input type="hidden" name="infoBoxId" value="<?php echo tep_output_string($infobox_id);?>"/>
			<input type="hidden" name="tempId" value="<?php echo tep_output_string($gID);?>"/>
			<table border="0" cellpadding="2" cellspacing="0" width="100%">
				<tr>
					<td class="main" id="<?php echo $this->type . $infobox_id;?>message">
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
						<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $infobox_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
						<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $infobox_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
					</td>
				</tr>
				<tr>
					<td><hr/></td>
				</tr>
				<tr>
					<td valign="top"><?php echo $this->doInfoboxInfo($gID,$infobox_id);?></td>
				</tr>
			</table>
		</form>
<?php
			$jsData->VARS["updateMenu"]="";			
	}
	function doDelete() {
		global $FREQUEST,$jsData;
		$infobox_id=$FREQUEST->postvalue('infoBoxId','int',0);
		$gID=$FREQUEST->postvalue('tempId','int',0);
		if ($infobox_id>0){
			$info_query=tep_db_query("select location,display_in_column from " . TABLE_INFOBOX_CONFIGURATION . " where infobox_id='" . tep_db_input($infobox_id) . "'");
			if (tep_db_num_rows($info_query)>0){
				$info_result=tep_db_fetch_array($info_query);
				tep_template_box_align($gID,$info_result["location"],$info_result["display_in_column"],"remove");
				tep_db_query("delete from " . TABLE_INFOBOX_CONFIGURATION . " where infobox_id = '" . tep_db_input($infobox_id) . "'");
			}
			$this->doItems($gID);
			$jsData->VARS["displayMessage"]=array('text'=>TEXT_INFOBOX_DELETE_SUCCESS);
		} else {
			echo "Err:" . TEXT_INFOBOX_NOT_DELETED;
		}
	}
	function doStatusChange() {
		global $FREQUEST,$jsData,$SERVER_DATE;
		$infobox_id=$FREQUEST->getvalue('rID','int',0);
		$gID=$FREQUEST->getvalue('gID','int',0);
		$status=$FREQUEST->getvalue('status','string','');
		if($infobox_id<=0) return;
		if($status!='yes' && $status!='no') $status='';
		tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set infobox_display = '" . $status . "',last_modified='" . tep_db_input($SERVER_DATE)  . "' where infobox_id = '" . (int)$infobox_id . "'");
	
		
		if ($status == 'yes') {
			$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $infobox_id .",get:\'StatusChange\',result:doSimpleResult,params:\'rID=". $infobox_id . "&status=no\',message:\'".TEXT_UPDATING_STATUS."\'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
			} else {
			$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $infobox_id .",get:\'StatusChange\',result:doSimpleResult,params:\'rID=". $infobox_id . "&status=yes\',message:\'".TEXT_UPDATING_STATUS."\'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
			}
		echo 'SUCCESS';
		$jsData->VARS["replace"]=array("shInfo". $infobox_id ."status"=>$result);
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
						<td width="2%" id="shInfo##ID##status" valign="middle" align="center">##STATUS##</td>
						<td width="2%" id="shInfo##ID##column" align="center" valign="middle">##COLUMN##</td>
						<td width="4%" align="center" class="boxRowMenu">
							<span style="##FIRST_MENU_DISPLAY##">
								##MOVE_UP##
								##MOVE_DOWN##
								
							</span>
						</td>
						<td width="47%" valign="middle" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&gID=##GID##'});" id="##TYPE####ID##name">##NAME##</td>
						<td  width="44%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
							<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
							<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&gID=##GID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteInfobox','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&gID=##GID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							</span>
							<span id="##TYPE####ID##mupdate" style="display:none">
							<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':validateForm,'uptForm':'infoboxes','customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
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
	
	?>
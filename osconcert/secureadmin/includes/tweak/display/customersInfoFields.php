<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare

    Released under the GNU General Public License
*/
	// Set flag that this is a parent file
	defined('_FEXEC') or die();
	class customersInfoFields{
		var $pagination;
		var $splitResult;
		var $type;
		function __construct() {
			$this->pagination=true;
			$this->splitResult=true;
			$this->type = 'cusInfo';
		}


		function doList()
        {
			global $FSESSION,$FREQUEST,$jsData;
			$page=$FREQUEST->getvalue('page','int',1);

			$orderBy=" order by ci.active DESC,ci.sort_order";
			$query_split=false;

			$sql = "select ci.uniquename,ci.info_id,ci.sort_order,cid.label_text,ci.input_type,ci.active from " . TABLE_CUSTOMERS_INFO_FIELDS . " ci,".TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION." cid where ci.info_id=cid.info_id and cid.languages_id='".(int)$FSESSION->languages_id."' $orderBy";
			//$maxRows=$FSESSION->get('displayRowsCnt');

            if($FREQUEST->getvalue("mode"))
            {
                $info_id=$FREQUEST->getvalue("rID","int",0);
                $mode=$FREQUEST->getvalue("mode","string","down");
    //            $first_id=$FREQUEST->getvalue("fID","int",0);
    //            $pageact=$FREQUEST->getvalue("pageact","int",0);

                $info_query=tep_db_query("select info_id, sort_order from " . TABLE_CUSTOMERS_INFO_FIELDS . " where info_id='" . (int)$info_id . "' ");
                if (tep_db_num_rows($info_query)<=0)
                {
                    echo "Err:" . TEXT_INFO_NOT_FOUND;
                    return;
                }
                $info_result=tep_db_fetch_array($info_query);
                $current_order=(int)$info_result["sort_order"];

                if ($mode=="up")
                    $info_sort_sql="select sort_order, info_id from " . TABLE_CUSTOMERS_INFO_FIELDS . " where sort_order<$current_order and active='Y' order by sort_order desc limit 1";
                 else
                    $info_sort_sql="select sort_order, info_id from " . TABLE_CUSTOMERS_INFO_FIELDS . " where sort_order>$current_order and active='Y' order by sort_order limit 1";

                $info_field_query=tep_db_query($info_sort_sql);

                if(tep_db_num_rows($info_field_query)<=0)
                {
                    echo "NOTRUNNED";
                    return;
                }

                $info_field_result=tep_db_fetch_array($info_field_query);
                $prev_order=$info_field_result['sort_order'];
                tep_db_query("UPDATE " . TABLE_CUSTOMERS_INFO_FIELDS . " set sort_order='" . $current_order . "' where info_id='" . (int)$info_field_result['info_id'] . "'");
                tep_db_query("UPDATE " . TABLE_CUSTOMERS_INFO_FIELDS . " set sort_order='" . $prev_order . "' where info_id='" . (int)$info_id . "'");

                //$jsData->VARS["moveRow"]=array("mode"=>$mode,"destID"=>$info_field_result["info_id"]);

             }

            if ($this->pagination)
			{
				$query_split=$this->splitResult = (new instance)->getSplitResult('cusInfo');
				$query_split->maxRows=40;//MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$sql);
				if ($query_split->queryRows > 0)
				{
//					if($FREQUEST->getvalue('search')!='')
//						$param = $FREQUEST->getvalue('search');

					$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'Items','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_DATA,'##PAGE_NO##') . "'})";
				}
             
			}

			$query=tep_db_query($sql);

			$found=false;
			if (tep_db_num_rows($query)>0) $found=true;

			if($found){
				$template=getListTemplate();
				$icnt=1;

				while($result=tep_db_fetch_array($query)){

                   $cur_order = (int)$result["sort_order"];
                   $sql_next = "select * from " . TABLE_CUSTOMERS_INFO_FIELDS . " where sort_order>$cur_order and `active` = 'Y'  order by active DESC,sort_order limit 1";
                   $info_field_next=tep_db_query($sql_next);

                   if(tep_db_num_rows($info_field_next)>0)
                        $lastactive = 1;
                    else
                        $lastactive = 0;

                   $sql_before = "select * from " . TABLE_CUSTOMERS_INFO_FIELDS . " where sort_order<$cur_order and `active` = 'Y'  order by active DESC,sort_order desc limit 1";
                   $info_field_before=tep_db_query($sql_before);

                   if(tep_db_num_rows($info_field_before)>0)
                        $firstactive = 1;
                    else
                        $firstactive = 0;

                     if(isset($_GET['page']) && $_GET['page'] !=0)
                        $pagedown = $_GET['page'];
                     else
                        $pagedown = 1;
					
				
					$rep_array=array(	"ID"=>$result["info_id"],
										"TYPE"=>$this->type,
										"NAME"=>$result["label_text"].' '.$warn,
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>'<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $result["info_id"] .",get:'CustomersInfoChangeStatus',result:doSimpleResult,params:'rID=". $result["info_id"] . "&active=" .($result["active"]=='Y'?'N':'Y') . "','message':'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/' . ($result["active"]=='Y'?'icon_active.gif':'icon_inactive.gif')) . '</a>',
										"UPDATE_RESULT"=>'doDisplayResult',
										"UPDATE_DATA"=>TEXT_UPDATE_DATA,
										"TEXT_SORTING_DATA"=>TEXT_SORTING_DATA,
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'Info',
                                        "IMGUP"=>($icnt ==1 && $firstactive ==0)?'':'<img src="'.DIR_WS_IMAGES.'/template/img_arrow_up.gif"  title="Up" align="absmiddle" />',
                                        "IMGDOWN"=>($lastactive == 0)?'':'<img src="'.DIR_WS_IMAGES.'/template/img_arrow_down.gif" title="Down"/>',
                                        "PAGEUP" => ($icnt ==1)?($_GET['page']-1):$_GET['page'],
                                        "PAGEDOWN" => ($icnt == tep_db_num_rows($query))?  ($pagedown+1):$_GET['page'],
										"SORT_MENU_DISPLAY"=>($result["active"] == 'Y')?'display:normal':'display:none',
										"FIRST_MENU_DISPLAY"=>""
									);
					echo mergeTemplate($rep_array,$template);

					$icnt++;
				}
				if (!isset($jsData->VARS["Page"])){
					$jsData->VARS["NUclearType"][]=$this->type;
				}
			}else{
				echo TEXT_INFO_NOT_FOUND;
			}
			$jsData->VARS['extraParams']=array('page'=>$page,'search'=>$search);
			return $found;
		}


		function doUpdate(){
			global $FSESSION,$FREQUEST,$LANGUAGES,$jsData,$SERVER_DATE_TIME;
			$info_id=$FREQUEST->postvalue("info_id","int",-1);

			$insert=true;
			if ($info_id>0) $insert=false;

			$languages = tep_get_languages();
			$unique_name=tep_db_prepare_input($FREQUEST->postvalue('unique_name','string',''));
			$active=$FREQUEST->postvalue('active','string', 'N');
			$show_label=$FREQUEST->postvalue('show_label','string','N');
			$required=$FREQUEST->postvalue('required','string','N');
			$input_type=$FREQUEST->postvalue('input_type');
			$default_value=$FREQUEST->postvalue('default_value');
			$display_page=$FREQUEST->postvalue('display_page');
			$textbox_size=$textbox_min_length=$textbox_max_length=0;
			$system=$FREQUEST->postvalue('system','string','N');
			$locked=$FREQUEST->postvalue('locked','string','N');

			if($input_type=='T') {
				$textbox_size=$FREQUEST->postvalue('textbox_size');
				$textbox_min_length=$FREQUEST->postvalue('textbox_min_length','int',0);
				$textbox_max_length=$FREQUEST->postvalue('textbox_max_length','int',0);
			}

			if($input_type=='A') {
				$textbox_size=$FREQUEST->postvalue('textarea_size');
				$textbox_min_length=$FREQUEST->postvalue('textarea_min_length','int',0);
				$textbox_max_length=$FREQUEST->postvalue('textarea_max_length','int',0);
			}
			$work_values=$FREQUEST->postvalue('work_values');
			$source=$FREQUEST->postvalue('source');
			if($work_values=='exists') {
				if($source!="") {
					$options_values=$FREQUEST->postvalue('source');
					if($options_values!="") $options_values=substr($options_values,0,-2);
				}
			}

			$sort_order_query=tep_db_query("Select max(sort_order) as sort_order from " . TABLE_CUSTOMERS_INFO_FIELDS);
			$sort_order_result=tep_db_fetch_array($sort_order_query);

			$sql_array=array();
			$sql_desc_array=array();
			$sql_info_array=array(	'show_label'=>$show_label,
									'default_value'=>$default_value,
									'textbox_size'=>$textbox_size,
									'textbox_min_length'=>$textbox_min_length,
									'textbox_max_length'=>$textbox_max_length
									);

			if ($system!='Y'){
				$sql_info_array['options_values']=$options_values;
				$sql_info_array['input_type']=$input_type;
				$sql_info_array['uniquename']=$unique_name;
			}
			if ($locked!='Y'){
				$sql_info_array['display_page']=$display_page;
				$sql_info_array['required']=$required;
				$sql_info_array['active']=$active;
				
			}
			$label_text_array=$FREQUEST->postvalue('label_text');
			$error_text_array=$FREQUEST->postvalue('error_text');
			$input_title_array=$FREQUEST->postvalue('input_title');
			$input_desc_array=$FREQUEST->postvalue('input_desc');
			if($insert) {
				$sql_info_array['sort_order']=$sort_order_result['sort_order']+1;
				tep_db_perform(TABLE_CUSTOMERS_INFO_FIELDS,$sql_info_array);
				$info_id=tep_db_insert_id();

				for($i=0;$i<sizeof($languages);$i++) {
					$language_id=$languages[$i]['id'];
					$replace_label_text=$label_text_array[$languages[0]['id']];
					$sql_desc_array=array(	'info_id'=>$info_id,
											'label_text'=>tep_db_prepare_input($label_text_array[$language_id]),
											'error_text'=>tep_db_prepare_input($error_text_array[$language_id]),
											'input_title'=>tep_db_prepare_input($input_title_array[$language_id]),
											'input_description'=>tep_db_prepare_input($input_desc_array[$language_id]),
											'languages_id'=>$language_id);
					tep_db_perform(TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION,$sql_desc_array);
				}
			}
			else {
				tep_db_perform(TABLE_CUSTOMERS_INFO_FIELDS,$sql_info_array,'update',"info_id='" . (int)$info_id . "'");

				tep_db_query("delete from " . TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION . " where info_id='" . (int)$info_id . "'");
				for($i=0;$i<sizeof($languages);$i++) {
					$language_id=$languages[$i]['id'];
					$replace_label_text=$label_text_array[$languages[0]['id']];
					$sql_desc_array=array(	'info_id'=>$info_id,
											'label_text'=>tep_db_prepare_input($label_text_array[$language_id]),
											'error_text'=>tep_db_prepare_input($error_text_array[$language_id]),
											'input_title'=>tep_db_prepare_input($input_title_array[$language_id]),
											'input_description'=>tep_db_prepare_input($input_desc_array[$language_id]),
											'languages_id'=>$language_id);
					tep_db_perform(TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION,$sql_desc_array);
				}
			}
			if ($insert) {
				$this->doItems();
			} else {
				$active_query=tep_db_query("select active from " . TABLE_CUSTOMERS_INFO_FIELDS . " where info_id='" . $info_id . "'");
				$active_result=tep_db_fetch_array($active_query);
				if ($active_result['active']=='Y'){
					$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $info_id .",get:'CustomersInfoChangeStatus',result:doSimpleResult,params:'rID=". $info_id . "&active=N'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
				} else {
					$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $info_id .",get:'CustomersInfoChangeStatus',result:doSimpleResult,params:'rID=". $info_id . "&active=Y'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
				}
				$jsData->VARS["replace"]=array($this->type . $info_id . "name"=>$replace_label_text,$this->type . $info_id . "bullet"=>$result);
				$jsData->VARS["prevAction"]=array('id'=>$info_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
				$this->doInfo($info_id);
				$jsData->VARS["updateMenu"]=",normal,";
			}
		}
		function doEdit(){
			global $FREQUEST,$FSESSION,$LANGUAGES,$CAT_TREE,$jsData;
			$input_type_array=array();
			$input_type_array=array(array('id'=>'L','text'=>'Title'),
									array('id'=>'T','text'=>'Text Box'),
									array('id'=>'A','text'=>'Text Area'),
									array('id'=>'C','text'=>'Checkbox'),
									array('id'=>'O','text'=>'Option Button'),
									array('id'=>'D','text'=>'Drop Down'),
									array('id'=>'U','text'=>'Custom'));
			$languages=&$LANGUAGES;
			$info_id=$FREQUEST->getvalue('rID','int',0);
			for ($icnt=0,$n=count($LANGUAGES);$icnt<$n;$icnt++){
				$label_text[$LANGUAGES[$icnt]['id']]='';
				$error_text[$LANGUAGES[$icnt]['id']]='';
				$input_description[$LANGUAGES[$icnt]['id']]='';
			}
			if($info_id<=0)
				$unique_sql = 'select uniquename from '.TABLE_CUSTOMERS_INFO_FIELDS;
			else
				$unique_sql="select uniquename from " . TABLE_CUSTOMERS_INFO_FIELDS . " where info_id not in('" . $info_id . "')";

			$unique_query=tep_db_query($unique_sql);
			$unique_array = array();
			while($unique_result = tep_db_fetch_array($unique_query)){
				$unique_array['name'][] = $unique_result['uniquename'];
			}

			if ($info_id<=0) $mode="new";
			if ($info_id>0){
				$query = tep_db_query("select ci.*,cid.* from " . TABLE_CUSTOMERS_INFO_FIELDS . " ci,".TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION." cid where ci.info_id=cid.info_id and cid.languages_id='".(int)$FSESSION->languages_id."' and ci.info_id='".(int)$info_id."'");
				$info_result=tep_db_fetch_array($query);

				$query=tep_db_query("SELECT cid.* from ".TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION." cid where cid.languages_id='".(int)$FSESSION->languages_id."' and cid.info_id='".(int)$info_id."'");
				while($result=tep_db_fetch_array($query)){
					$temp_lang=$result["languages_id"];
					$label_text[$temp_lang]=$result["label_text"];
					$error_text[$temp_lang]=$result["error_text"];
					$input_description[$temp_lang]=$result["input_description"];
				}
			}else {
				$info_result=array('info_id'=>0,'uniquename'=>'','active'=>'Y','system'=>'N','locked'=>'N','show_label'=>'Y','input_type'=>'T','default_value'=>'','textbox_size'=>5,'textbox_min_length'=>0,'textbox_max_length'=>0,'textarea_min_length'=>0,'textarea_max_length'=>0,'required'=>'N','code_type'=>'D','options_values'=>'','store_type'=>'D','display_page'=>'');
			}

			$fieldsInfo=new objectInfo($info_result);
			$jsData->VARS['doFunc']=array('type'=>'cusInfo','data'=>'show_datas##' . $info_result['system'] . '##' . $info_result['locked'] . '##' . $info_result['active']);

			$textbox_display='style="display:none"';
			$opt_button_display='style="display:none"';
			$textarea_display='style="display:none"';
			$show_note='style="display:none"';
			if($fieldsInfo->input_type=='T') $textbox_display='';
			if($fieldsInfo->input_type=='O' || $fieldsInfo->input_type=='D') $opt_button_display='';
			if($fieldsInfo->input_type=='A') $textarea_display='';
			if($fieldsInfo->input_type=='U') $show_note='';
			$jsData->VARS["updateMenu"]=",update,";
			$display_mode_html=' style="display:none"';
?>
			<form name="infoSubmit" action="<?php echo FILENAME_CUSTOMERS_INFO_FIELDS; ?>" method="post" id="infoSubmit">
			<?php
				for($i=0;$i<count($unique_array['name']);$i++){
					echo tep_draw_hidden_field('uniquename[]',$unique_array['name'][$i],'id="uniquename[]"');
				}
			?>
			<table cellpadding="4" cellspacing="0" width="85%">
				<tr>
					<td valign="top">
						<table cellpadding="2" cellspacing="0" width="100%">
							<tr><td colspan="2" class="main"><b><?php echo TEXT_HEADING_GENERAL; ?></b></td></tr>
							<tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif','10','10');?></td></tr>
							<tr  height="30">
								<td class="main" width="20%"><?php echo TEXT_UNIQUE_NAME; ?></td>
								<td>
									<?php
										echo tep_draw_input_field('unique_name',$fieldsInfo->uniquename,' title="' . TITLE_UNIQUE_NAME . '"');
									?>
									</td>
							</tr>
							<tr>
								<Td>&nbsp;</Td>
								<td><table cellpadding="2" cellspacing="0">
									<tr>
										<td class="main" ><?php echo TEXT_ACTIVE . '&nbsp; ' . tep_draw_checkbox_field('active','Y',(($fieldsInfo->active=='Y')?'checked':''),'', '  id="show_active" onClick=javascript:disable_keys(this.checked); title="' .  TITLE_ACTIVE . '"') . '&nbsp; '; ?></td>
										<td id="display_label" class="main" ><?php echo TEXT_SHOW_LABEL . '&nbsp; ' . tep_draw_checkbox_field('show_label','Y',(($fieldsInfo->show_label=='Y')?'checked':''),'', ' onClick=javascript:activate_label(this.checked); title="' .  TITLE_SHOW_LABEL . '"') . '&nbsp; '; ?></td>
										<td id="show_required" class="main"><?php echo TEXT_REQUIRED . '&nbsp; ' . tep_draw_checkbox_field('required','Y',(($fieldsInfo->required=='Y')?'checked':''),'', ' title="' .  TITLE_REQUIRED . '"') . '&nbsp; '; ?></td>
									</tr>
								</table></td>
							</tr>
							<tr height="30">
								<td class="main" width="20%"><?php echo TEXT_DEFAULT_VALUE; ?></td>
								<td><?php echo tep_draw_input_field('default_value',$fieldsInfo->default_value, ' title="' . TITLE_DEFAULT_VALUE . '"'); ?></td>
							</tr>
							<tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif','10','10');?></td></tr>
							<tr><td colspan="2" class="main"><b><?php echo TEXT_HEADING_INPUT_INFO; ?></b></td>
							<tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif','10','10');?></td></tr>
							<tr id="show_input_type" height="30" >
								<td class="main" width="20%"><?php echo TEXT_INPUT_TYPE; ?></td>
								<td><?php echo tep_draw_pull_down_menu('input_type',$input_type_array,$fieldsInfo->input_type,' onChange=javascript:show_detail(this.value);  title="' . TITLE_INPUT_TYPE . '"'); ?></td>
							</tr>
							<tr>
								<td colspan="2">
								<div id="show_textbox_params" <?php echo $textbox_display; ?>>
								<table cellpadding="2" cellspacing="0" width="100%" border="0">
									<tr><td colspan="6"><?php echo tep_draw_separator('pixel_trans.gif','10','10');?></td></tr>
									<tr>
										<td width="20%" class="main"><b><?php echo TEXT_BOX_TITLE; ?></b></td>
										<td colspan="5"><table cellpadding="2" cellspacing="0" width="100%">
											<tr>
												<td class="main"><?php echo TEXT_BOX_SIZE; ?></td>
												<td><?php echo tep_draw_input_field('textbox_size',(($fieldsInfo->input_type=='T')?$fieldsInfo->textbox_size:''),' size=10 , title="' . TITLE_TEXT_SIZE . '"'); ?></td>
												<td class="main"><?php echo TEXT_BOX_MIN_LENGTH; ?></td>
												<td><?php echo tep_draw_input_field('textbox_min_length',$fieldsInfo->textbox_min_length,' size=10 , title="' . TITLE_MIN_LENGTH . '"'); ?></td>
												<td class="main"><?php echo TEXT_BOX_MAX_LENGTH; ?></td>
												<td><?php echo tep_draw_input_field('textbox_max_length',$fieldsInfo->textbox_max_length,' size=10 , title="' . TITLE_MAX_LENGTH . '"'); ?></td>
											</tr>
										</table></td>
									</tr>
									<tr><td colspan="6"><?php echo tep_draw_separator('pixel_trans.gif','5','5');?></td></tr>
								</table>
								</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
								<div id="show_textarea_params" <?php echo $textarea_display; ?>>
								<table cellpadding="2" cellspacing="0" width="100%" border="0">
									<tr>
										<td colspan="2" width="20%" class="main"><b><?php echo TEXT_AREA_TITLE; ?></b></td>
										<td><table cellpadding="2" cellspacing="0" width="100%">
											<tr>
												<td class="main"><?php echo TEXT_AREA_SIZE; ?></td>
												<td><?php echo tep_draw_input_field('textarea_size',(($fieldsInfo->input_type=='A')?$fieldsInfo->textbox_size:''), ' title="' . TITLE_TEXT_SIZE . '"' ); ?></td>
												<td class="main"><?php echo TEXT_BOX_MIN_LENGTH; ?></td>
												<td><?php echo tep_draw_input_field('textarea_min_length',(($fieldsInfo->input_type=='A')?$fieldsInfo->textbox_min_length:''),' size=10  title="' . TITLE_MIN_LENGTH . '"'); ?></td>
												<td class="main"><?php echo TEXT_BOX_MAX_LENGTH; ?></td>
												<td><?php echo tep_draw_input_field('textarea_max_length',(($fieldsInfo->input_type=='A')?$fieldsInfo->textbox_max_length:''),' size=10  title="' . TITLE_MAX_LENGTH . '"'); ?></td>
											</tr>
										</table></td>
									</tr>
									<tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif','10','10');?></td></tr>
								</table>
								</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
								<div id="show_option_params" <?php echo $opt_button_display; ?>>
								<?php
									$option_values_array=array();
									$options_values=$info_result['options_values'];
									if($options_values!="") {
										$optValArr=array();
										$optValArr=explode('##',$options_values);
										for($i=0;$i<sizeof($optValArr);$i++) {
											if($optValArr[$i]!="") {
												$optSplitArray=array();
												$optSplitArray=explode('@@',$optValArr[$i]);
												$opt_val_id=$optValArr[$i];
												$opt_val_text=$optSplitArray[0];
												$option_values_array[]=array('id'=>$opt_val_id,'text'=>tep_db_prepare_input($opt_val_text));
											}
										}
									}
								?>
								<table cellpadding="2" cellspacing="0" width="75%" border="0">
									<tr><td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif','10','10');?></td></tr>
									<tr><td colspan="3" class="main"><b><?php echo TEXT_HEADING_OPTION_VALUES; ?></b></td></tr>
									<tr>
										<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif','10','10');?></td>
									</tr>
									<tr>
										<td class="main" valign="top" width="27%"><?php echo TEXT_OPTION_VALUES; ?></td>
										<td valign="top"><?php echo tep_draw_pull_down_menu('option_values_list',$option_values_array,$option_values_array[0]['id'],' size="7" style="width:300px;" id="option_values_list" title="' . TITLE_OPTION_VALUES_LIST . '" onClick="do_opt_value_select(\'select\');"'); ?></td>
										<td class="main" align="left" valign="top">
											<table cellpadding="2" cellspacing="0" border="0" width="100%">
												<tr>
													<td>
														<a href="javascript:void(0)" onclick="javascript:do_opt_value_select('add');" id="insert_event"><?php echo tep_image_button('button_insert.gif',IMAGE_INSERT_OPT_VALUE);?></a><br /><br />
														<a href="javascript:void(0)" onclick="javascript:do_opt_value_select('update');" id="update_event"><?php echo tep_image_button('button_update.gif',IMAGE_UPDATE_OPT_VALUE);?></a><br /><br />
														<a href="javascript:void(0)" onclick="javascript:do_opt_value_select('delete');" id="delete_event"><?php echo tep_image_button('button_delete.gif',IMAGE_DELETE_OPT_VALUE);?></a><br /><br />
													</td>
												</tr>

											</table>
										</td>
									</tr>
									<tr>
										<Td colspan="3"><table cellpadding="2" cellspacing="0" width="100%" border="0">
											<tr>
												<td class="main" width="27%"><?php echo TEXT_OPTION_NAME; ?></td>
												<td><?php echo tep_draw_input_field('option_name','',' title="' . TITLE_OPTION_NAME . '"'); ?></td>
												<td class="main" width="100"><?php echo TEXT_OPTION_VALUES; ?></td>
												<td><?php echo tep_draw_input_field('option_values','',' title="' . TITLE_OPTION_VALUE . '"'); ?></td>
											</tr>
										</table></Td>
									</tr>
								</table>
								</div>
								</td>
							</tr>
							<tr><td colspan="2">
								<table cellpadding="0" cellspacing="0" width="100%">
									<?php
									$err_text_arr=$lab_text_arr=$input_title_arr=$input_desc_arr=array();
									$languages = tep_get_languages();
									$desc_query=tep_db_query("Select * from " . TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION . " where info_id='" . (int)$info_id . "'");
									while($result=tep_db_fetch_array($desc_query)) {
										$err_text_arr[$result['languages_id']]=$result['error_text'];
										$lab_text_arr[$result['languages_id']]=$result['label_text'];
										$input_title_arr[$result['languages_id']]=$result['input_title'];
										$input_desc_arr[$result['languages_id']]=$result['input_description'];
									}
									?>
									<tr>
										<td valign="top" width="50%"><table cellpadding="2" cellspacing="0" width="100%" border="0">
											<?php
											for ($i=0; $i<sizeof($languages); $i++) {
												$lang_id=$languages[$i]['id'];
											?>
												  <tr>
													<td class="main" width="40%" valign="top"><?php if ($i == 0) echo TEXT_LABEL_TEXT; ?></td>
													<td class="main" valign="top" width="20%"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_textarea_field('label_text[' . $languages[$i]['id'] . ']', 'soft','30','3', (($lab_text_arr[$lang_id]) ? stripslashes($lab_text_arr[$lang_id]) : ''), ' title="' . TITLE_LABEL_TEXT . '"'); ?></td>
													<td>&nbsp;</td>
												  </tr>
											<?php }
											for ($i=0; $i<sizeof($languages); $i++) {
												$lang_id=$languages[$i]['id'];
											?>
												  <tr>
													<td class="main" width="40%" valign="top"><?php if ($i == 0) echo TEXT_ERROR_TEXT; ?></td>
													<td class="main" valign="top" width="20%"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_textarea_field('error_text[' . $languages[$i]['id'] . ']', 'soft','30','3', (($err_text_arr[$lang_id]) ? stripslashes($err_text_arr[$lang_id]) : '')); ?></td>
													<td>&nbsp;</td>
												  </tr>
											<?php } ?>
										</table></td>
										<td valign="top" width="50%"><table cellpadding="2" cellspacing="0" width="100%" border="0">
											<?php
											for ($i=0; $i<sizeof($languages); $i++) {
												$lang_id=$languages[$i]['id'];
											?>
												  <tr>
													<td class="main" width="40%" valign="top"><?php if ($i == 0) echo TEXT_INPUT_TITLE; ?></td>
													<td class="main" valign="top" width="20%"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_textarea_field('input_title[' . $languages[$i]['id'] . ']', 'soft', '30', '3' , (($input_title_arr[$lang_id]) ? stripslashes($input_title_arr[$lang_id]) : ''), ' title="' . TITLE_ROLLOVER_TEXT . '"'); ?></td>
													<td>&nbsp;</td>
												  </tr>
											<?php }
											for ($i=0; $i<sizeof($languages); $i++) {
												$lang_id=$languages[$i]['id'];
											?>
												  <tr>
													<td class="main" width="40%" valign="top"><?php if ($i == 0) echo TEXT_INPUT_DESCRIPTION; ?></td>
													<td class="main" valign="top" width="20%"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_textarea_field('input_desc[' . $languages[$i]['id'] . ']', 'soft', '30', '3',  (($input_desc_arr[$lang_id]) ? stripslashes($input_desc_arr[$lang_id]) : ''), ' title="' . TITLE_HELP_TEXT . '"'); ?></td>
													<td>&nbsp;</td>
												  </tr>
											<?php } ?>
										</table></td>
									</tr>
								</table></td>
							</tr>
							<tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif','10','10');?></td></tr>
							<tr id="show_display_page" >
								<td colspan="2">
									<table cellpadding="2" cellspacing="0" width="100%" border="0">
										<tr>
											<td class="main" width="20%"><?php echo TEXT_DISPLAY_PAGE; ?></td>
										</tr>
										<tr>
											<td width="119"></td>
											<td><table cellpadding="2" cellspacing="0" border="0">
												<tr height="25">
													<td class="main" colspan="3"><?php echo TEXT_FRONT_END; ?></td>
												</tr>
												<tr>
													<td class="main" width="100"><?php echo tep_draw_checkbox_field('display_page[]','C',((strpos($fieldsInfo->display_page,'C')!==false)?'checked':'') ) . TEXT_SIGN_UP; ?></td>
													<td class="main" width="125"><?php echo tep_draw_checkbox_field('display_page[]','E',((strpos($fieldsInfo->display_page,'E')!==false)?'checked':'') ) . TEXT_ACCOUNT_EDIT; ?></td>
                                                 <!--	<td class="main" width="100"><?php echo tep_draw_checkbox_field('display_page[]','F',((strpos($fieldsInfo->display_page,'F')!==false)?'checked':'') ) . TEXT_AFFILIATE_SIGNUP; ?></td> -->
                                                    <td class="main"><?php echo tep_draw_checkbox_field('hidden_display_page','A',((strpos($fieldsInfo->display_page,'A')!==false)?'checked':''),'','style="visibility:hidden;"'); ?></td>
                                               <tr>
											</table></td>
											<td><table cellpadding="2" cellspacing="0" border="0">
												<tr height="25">
													<td class="main" colspan="2"><?php echo TEXT_BACK_END; ?></td>
												</tr>
												<tr>
													<td class="main" width="150"><?php echo tep_draw_checkbox_field('display_page[]','B1',((strpos($fieldsInfo->display_page,'B1')!==false)?'checked':'') ) . TEXT_CREATE_ACCOUNT; ?></td>
													<td class="main" width="125"><?php echo tep_draw_checkbox_field('display_page[]','B2',((strpos($fieldsInfo->display_page,'B2')!==false)?'checked':'') ) . TEXT_EDIT_ACCOUNT; ?></td>
                                                 <!--    <td class="main" width="100"><?php echo tep_draw_checkbox_field('display_page[]','B3',((strpos($fieldsInfo->display_page,'B3')!==false)?'checked':'') ) . TEXT_AFFILIATE_SIGNUP; ?></td> -->
												</tr>
											</table></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif','10','10');?></td></tr>
							<tr><td colspan="2" class="main"><?php echo tep_draw_checkbox_field('show_note','Y',(($fieldsInfo->input_type=='U')?'checked':''),'',' onClick="javascript:display_tip(this.checked); "') . '&nbsp;' . TEXT_SHOW_NOTE; ?></td></tr>
							<tr><td colspan="2" class="main" id="display_note" <?php echo $show_note; ?>><?php echo TEXT_NOTE . '<br>' . TEXT_INFO; ?></td></tr>
							
						</table>
					</td>
				</tr>
			</table>
			<?php
			echo tep_draw_hidden_field('info_id',$info_id,'id="info_id"');
			echo tep_draw_hidden_field('source','','id="source"');
			echo tep_draw_hidden_field('work_values','','id="work_values"');
			echo tep_draw_hidden_field('flagSystem',$fieldsInfo->system,' id="flagSystem"');
			echo tep_draw_hidden_field('flagLocked',$fieldsInfo->locked,' id="flagLocked"');
			?>
			</form>
<?php
		}
		function doDelete(){
			global $FREQUEST,$jsData;
			$info_id=$FREQUEST->postvalue('info_id','int',0);
			if ($info_id>0){
				tep_db_query("DELETE from " . TABLE_CUSTOMERS_INFO_FIELDS . " where info_id='" . (int)$info_id . "' ");
				tep_db_query("DELETE from " . TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION. " where info_id='" . (int)$info_id . "'");
				$this->doItems();
				//$jsData->VARS["deleteRow"]=array("id"=>$manufacturer_id,"type"=>$this->type);
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_CUSTOMERS_FIELD_DELETE_SUCCESS);
			} else {
				echo "Err:" . TEXT_CUSTOMER_FIELD_NOT_DELETED;
			}
		}
		function doDeleteDisplay(){
			global $FREQUEST,$jsData,$FSESSION;
			$info_id=$FREQUEST->getvalue('rID','int',0);

			$info_query=tep_db_query("Select system from " . TABLE_CUSTOMERS_INFO_FIELDS . " where info_id='" . $info_id . "'");
			$info_result=tep_db_fetch_array($info_query);
			$no_delete=false;
			if($info_result['system']=='N') {
				$delete_message='<p><span class="smallText">' . TEXT_DELETE_INTRO . '</span>';
			}
			else {
				$no_delete=true;
				$delete_message='<p><span class="smallText">' . TEXT_NOT_DELETE_INTRO . '</span>';
			}
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="<?php echo FILENAME_CUSTOMERS_INFO_FIELDS; ?>" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="info_id" value="<?php echo tep_output_string($info_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $info_id;?>message">
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
							<?php if(!$no_delete) { ?>
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $info_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['DELETING_DATA'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<?php } ?>
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $info_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php echo $this->doInfo($info_id);?></td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}


		function doCustomersInfoSort()
         {
			global $FSESSION,$FREQUEST,$jsData;
			$info_id=$FREQUEST->getvalue("rID","int",0);
			$mode=$FREQUEST->getvalue("mode","string","down");


			$info_query=tep_db_query("select info_id, sort_order from " . TABLE_CUSTOMERS_INFO_FIELDS . " where info_id='" . (int)$info_id . "' ");
			if (tep_db_num_rows($info_query)<=0)
            {
				echo "Err:" . TEXT_INFO_NOT_FOUND;
				return;
			}
			$info_result=tep_db_fetch_array($info_query);
			$current_order=(int)$info_result["sort_order"];

			if ($mode=="up")
                $info_sort_sql="select sort_order, info_id from " . TABLE_CUSTOMERS_INFO_FIELDS . " where sort_order<$current_order and active='Y' order by sort_order desc limit 1";
			 else
				$info_sort_sql="select sort_order, info_id from " . TABLE_CUSTOMERS_INFO_FIELDS . " where sort_order>$current_order and active='Y' order by sort_order limit 1";

			$info_field_query=tep_db_query($info_sort_sql);

            if(tep_db_num_rows($info_field_query)<=0)
            {
				echo "NOTRUNNED";
				return;
			}

			$info_field_result=tep_db_fetch_array($info_field_query);
			$prev_order=$info_field_result['sort_order'];
			tep_db_query("UPDATE " . TABLE_CUSTOMERS_INFO_FIELDS . " set sort_order='" . $current_order . "' where info_id='" . (int)$info_field_result['info_id'] . "'");
			tep_db_query("UPDATE " . TABLE_CUSTOMERS_INFO_FIELDS . " set sort_order='" . $prev_order . "' where info_id='" . (int)$info_id . "'");

            $jsData->VARS["moveRow"]=array("mode"=>$mode,"destID"=>$info_field_result["info_id"]);


		}


		function doItems()
        {
			global $FREQUEST,$jsData;
			$template=getListTemplate();
			$rep_array=array(	"TYPE"=>$this->type,
								"ID"=>-1,
								"NAME"=>TEXT_NEW_INFO_FIELD,
								"IMAGE_PATH"=>DIR_WS_IMAGES,
								"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
								"UPDATE_RESULT"=>'doTotalResult',
								"UPDATE_DATA"=>TEXT_UPDATE_DATA,
								"TEXT_SORTING_DATA"=>TEXT_SORTING_DATA,
								"ROW_CLICK_GET"=>'Edit',
								"SORT_MENU_DISPLAY"=>"display:none",
								"FIRST_MENU_DISPLAY"=>"display:none"
							);

?>
			<div class="main" id="prd-1message"></div>
			<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
				<div><?php 	echo mergeTemplate($rep_array,$template); ?></div>
				<div align="center"><?php $this->doList();?></div>
			</table>
			<?php if (is_object($this->splitResult))
					  { ?>
						<table border="0" width="100%" height="100%">
					    <?php echo $this->splitResult->pgLinksCombo(); ?>
						</table>
				<?php }
		}


		function doInfo($info_id=0){
			global $FSESSION,$FREQUEST,$jsData;
			if ($info_id<=0) $info_id=$FREQUEST->getvalue("rID","int",0);
			$field_query = tep_db_query("select c.uniquename,c.default_value,c.active,c.show_label,c.required, cd.label_text,cd.error_text,c.info_id from " . TABLE_CUSTOMERS_INFO_FIELDS . " c, " . TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION . " cd where c.info_id = '" . (int)$info_id . "' and c.info_id = cd.info_id and cd.languages_id = '" . (int)$FSESSION->languages_id . "'");
			$field_result=tep_db_fetch_array($field_query);
			if(tep_db_num_rows($field_query)>0) {
				?>
				<table cellpadding="2" cellspacing="0" width=100% height="50">
						<tr>
							<td algin="center" width="50%">
								<table cellpadding=2 cellspacing=0 width="100%" style="padding-left:25px;">
									<tr height="25">
										<td class="main" width="100"><?php echo TEXT_UNIQUE_NAME . ':'; ?></td>
										<td class="main"><?php echo $field_result['uniquename'] ; ?></td>
									</tr>
									<tr height="25">
										<td class="main" width="100"><?php echo TEXT_DEFAULT_VALUE . ':'; ?></td>
										<td class="main"><?php echo $field_result['default_value'] ; ?></td>
									</tr>
									<tr height="25">
										<td class="main" width="100"><?php echo TEXT_ERROR_TEXT . ':'; ?></td>
										<td class="main"><?php echo $field_result['error_text']; ?></td>
									</tr>
								</table>
							</td>
							<td>
								<table cellpadding="2" cellspacing="0" width="100%">
									<tr height="25">
										<td class="main" width="100"><?php echo TEXT_ACTIVE . ':'; ?></td>
										<td class="main"><?php echo (($field_result['active']=='Y')?tep_image(DIR_WS_ICONS . '/tick.gif'):tep_image(DIR_WS_ICONS . '/cross.gif')) ; ?></td>
									</tr>
									<tr height="25">
										<td class="main"><?php echo TEXT_SHOW_LABEL . ':'; ?></td>
										<td class="main"><?php echo (($field_result['show_label']=='Y')?tep_image(DIR_WS_ICONS . '/tick.gif'):tep_image(DIR_WS_ICONS . '/cross.gif')); ?></td>
									</tr>
									<tr height="25">
										<td class="main"><?php echo TEXT_REQUIRED . ':'; ?></td>
										<td class="main"><?php echo (($field_result['required']=='Y')?tep_image(DIR_WS_ICONS . '/tick.gif'):tep_image(DIR_WS_ICONS . '/cross.gif')); ?></td>
									</tr>
								</table>
							</td>
						</table></td></tr>
						<tr>
							<td><?php echo tep_draw_separator('pixel_trans.gif','10','10'); ?></td>
						</tr>
					</table>
<?php
				$jsData->VARS["updateMenu"]=",normal,";
			} else {
				echo 'Err:' . TEXT_FIELDS_NOT_FOUND;
			}
		}


		function doCustomersInfoChangeStatus(){
			global $FREQUEST,$jsData;
			$info_id=$FREQUEST->getvalue("rID","int",0);
			$active=$FREQUEST->getvalue("active","string",'N');
			if ($info_id<=0) return;
			if ($active!='N' && $active!='Y') $active='N';
			if($active=='N') 
				$sql="UPDATE " . TABLE_CUSTOMERS_INFO_FIELDS . " set active='" . $active . "', show_label='N' where info_id='" . $info_id . "'";
			else
				$sql="UPDATE " . TABLE_CUSTOMERS_INFO_FIELDS . " set active='" . $active . "', show_label='Y' where info_id='" . $info_id . "'";
            
			if($sql!="") tep_db_query($sql);
			if ($active=='Y'){
				$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $info_id .",get:'CustomersInfoChangeStatus',result:doSimpleResult,params:'rID=". $info_id . "&active=N',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
			} else {
				$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $info_id .",get:'CustomersInfoChangeStatus',result:doSimpleResult,params:'rID=". $info_id . "&active=Y',message:'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
			}
			echo 'SUCCESS';
			$jsData->VARS["replace"]=array($this->type. $info_id ."bullet"=>$result);
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
									<td width="1%" id="##TYPE####ID##bullet">##STATUS##</td>
									<td width="2%" align="center" class="boxRowMenu">
										<span style="##SORT_MENU_DISPLAY##">
											<a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':##ID##,'get':'Items','result':doTotalResult,mode:'up',type:'cusInfo',params:'rID=##ID##&mode=up&page=##PAGEUP##','style':'boxLevel1','message':'##TEXT_SORTING_DATA##'})">##IMGUP##</a>
											<a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':##ID##,'get':'Items','result':doTotalResult,mode:'down',type:'cusInfo',params:'rID=##ID##&mode=down&page=##PAGEDOWN##','style':'boxLevel1','message':'##TEXT_SORTING_DATA##'})">##IMGDOWN##</a>
										</span>
									</td>
									<td width="35%"class="main" onclick="javascript:doDisplayAction({'id':##ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##name">##NAME##</td>
									<td  width="10%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
										<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##',extraFunc:disable_form,'params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'DeleteDisplay','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										</span>
										<span id="##TYPE####ID##mupdate" style="display:none">
										<a href="javascript:void(0)" onclick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','type':'##TYPE##','style':'boxRow','validate':check_form,'uptForm':'infoSubmit','customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':'##UPDATE_DATA##'});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onclick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
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

<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>


<?php
class salesMaker
	{
	var $pagination;
	var $splitResult;
	var $type;

	function __construct() {
	$this->pagination=false;
	$this->splitResult=false;
	$this->type='salmake';
	}
	function doItems(){
		global $FREQUEST,$jsData;
			
		$template=getListTemplate();
		$rep_array=array("TYPE"=>$this->type,
						"ID"=>-1,
						"NAME"=>HEADING_NEW_TITLE,
						"IMAGE_PATH"=>DIR_WS_IMAGES,
						"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
						"UPDATE_RESULT"=>'doTotalResult',
						"ROW_CLICK_GET"=>'Edit',
						"FIRST_MENU_DISPLAY"=>"display:none"
						);

	?>
	<div class="main" id="salmake-1message"></div>
	<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
		<tr><td><?php 	echo mergeTemplate($rep_array,$template); ?></td></tr>
		<tr>
			<td><table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%">
					<tr class="dataTableHeadingRow">
						<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="main" width="47%"><b><?php echo  TABLE_HEADING_NAME;?></b></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td><div align="center"><?php $this->doList();?></div></td>
					</tr>	
				</Table>
			</td>
		</tr>
	</table>
	<?php if (is_object($this->splitResult)){?>
		<table border="0" width="100%" height="100%">
			<?php echo $this->splitResult->pgLinksCombo(); ?>
		</table>
	<?php }
	}
	function doList($sale_id=0){
		global $FSESSION,$FREQUEST,$jsData;
		$page=$FREQUEST->getvalue('page','int',1);
		$query_split=false;
		
		$salemaker_sales_query_raw = "select sale_id, sale_status, sale_name,sale_discount_type, sale_deduction_value, sale_deduction_type, sale_pricerange_from, sale_pricerange_to, sale_specials_condition, sale_categories_selected, sale_categories_all, sale_date_start, sale_date_end, sale_date_added, sale_date_last_modified, sale_date_status_change,apply_to_cross_sale from " . TABLE_SALEMAKER_SALES . " order by sale_id desc";

		if ($this->pagination){
			$query_split=$this->splitResult = (new instance)->getSplitResult('CUSTOMER');
			$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
			$query_split->parse($page,$salemaker_sales_query_raw);
					if ($query_split->queryRows > 0){ 
						if ($search!=''){
							$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','get':'SearchGroup','result':doTotalResult,params:'search=". urlencode($search) . "&page='+##PAGE_NO##,'message':'" . INFO_SEARCHING_DATA . "'})";
						} else {	
							$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'Items','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_SALE,'##PAGE_NO##') . "'})";
						}
					}
		}
		$salemaker_sales_query=tep_db_query($salemaker_sales_query_raw);
		$found=false;
		if (tep_db_num_rows($salemaker_sales_query)>0) $found=true;
		if($found)
		{
			$template=getListTemplate();
			$icnt=1;
			while($sales_result=tep_db_fetch_array($salemaker_sales_query)){
					$rep_array=array(	"ID"=>$sales_result["sale_id"],
										"TYPE"=>$this->type,
										"NAME"=>$sales_result["sale_name"],
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>'<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $sales_result["sale_id"] .",get:'SaleChangeStatus',result:doSimpleResult,params:'rID=". $sales_result["sale_id"] . "&status=" .($sales_result["sale_status"]==1?0:1) . "','message':'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/' . ($sales_result["sale_status"]==1?'icon_active.gif':'icon_inactive.gif')) . '</a>',
										"UPDATE_RESULT"=>'doDisplayResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'Info',
										"FIRST_MENU_DISPLAY"=>""
									);
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}
		}
		else echo '<div align="center" class="main">'.TEXT_EMPTY_GROUPS.'</div>';

		if (!isset($jsData->VARS["Page"])){
			$jsData->VARS["NUclearType"][]=$this->type;
		} 
		return $found;			
	}
	function doInfo($sale_id=0){
		global $FREQUEST,$jsData;
			
		if($sale_id <= 0)$sale_id=$FREQUEST->getvalue("rID","int",0);
		$salemaker_sales_query = tep_db_query("select sale_id, sale_status, sale_name,sale_discount_type, choice_text,choice_warning,sale_deduction_value, sale_deduction_type, sale_pricerange_from, sale_pricerange_to, sale_specials_condition, sale_categories_selected, sale_categories_all, sale_date_start, sale_date_end, sale_date_added, sale_date_last_modified, sale_date_status_change,apply_to_cross_sale from " . TABLE_SALEMAKER_SALES . " where sale_id='" . tep_db_input($sale_id) . "'");
		$deduction_type_array = array(array('id' => '0', 'text' => DEDUCTION_TYPE_DROPDOWN_0),
                                array('id' => '1', 'text' => DEDUCTION_TYPE_DROPDOWN_1),
                                array('id' => '2', 'text' => DEDUCTION_TYPE_DROPDOWN_2));
		if (tep_db_num_rows($salemaker_sales_query)>0){
			$salemaker_sales_result=tep_db_fetch_array($salemaker_sales_query);
			$template=getInfoTemplate($sale_id);
		
			if($salemaker_sales_result['sale_discount_type']=='S')
				$sales_discount_type=TEXT_SALES_MAKER;
			else if($salemaker_sales_result['sale_discount_type']=='C')	
				$sales_discount_type=TEXT_CUSTOMER_CHOICE;
			$rep_array=array("TYPE"=>$this->type,
							"ENT_SALE_NAME"=>TEXT_SALEMAKER_NAME,
							"SALE_NAME"=>$salemaker_sales_result['sale_name'],	
							"ENT_START_DATE"=>TEXT_SALEMAKER_DATE_START,
							"START_DATE"=>(($salemaker_sales_result['sale_date_start'] == '0000-00-00') ? TEXT_SALEMAKER_IMMEDIATELY : format_date($salemaker_sales_result['sale_date_start'])),
							"ENT_SALE_TYPE"=>TEXT_SALEMAKER_DEDUCTION_TYPE,
							"SALE_TYPE"=>$deduction_type_array[$salemaker_sales_result['sale_deduction_type']]['text'],
							"ENT_DEDUCTION"=>TEXT_SALEMAKER_DEDUCTION,
							"DEDUCTION"=>$salemaker_sales_result['sale_deduction_value'],
							"ENT_END_DATE"=>TEXT_SALEMAKER_DATE_END,
							"END_DATE"=>(($salemaker_sales_result['sale_date_end'] == '0000-00-00') ? TEXT_SALEMAKER_NEVER : format_date($salemaker_sales_result['sale_date_end'])),
							"ENT_DISCOUNT_TYPE"=>TEXT_DISCOUNT_TYPE,
							"DISCOUNT_TYPE"=>$sales_discount_type,
							"ENT_USAGE_TIPS"=>TEXT_TIPS,
							"USAGE_TIPS"=>INFO_TEXT,
							"ID"=>$salemaker_sales_result["sale_id"],
							);
				
			echo mergeTemplate($rep_array,$template);
			$jsData->VARS["updateMenu"]=",normal,";
		}
		else {
			echo 'Err:' . TEXT_LOCATION_NOT_FOUND;
		}
	}	
	function doEdit()
	{
		global $FREQUEST,$jsData,$FSESSION;
		$sale_id=$FREQUEST->getvalue("rID","int",0);
		$salemaker_sales_query = tep_db_query("select sale_id, sale_status, sale_name,choice_text,choice_warning,sale_discount_type, sale_deduction_value, sale_deduction_type, sale_pricerange_from, sale_pricerange_to, sale_specials_condition, sale_categories_selected, sale_categories_all, sale_date_start, sale_date_end, sale_date_added, sale_date_last_modified, sale_date_status_change,sale_products_selected,apply_to_cross_sale from " . TABLE_SALEMAKER_SALES . " where sale_id='" . tep_db_input($sale_id) . "'");
		$salemaker_sales_result = tep_db_fetch_array($salemaker_sales_query); 
		$specials_condition_array = array(array('id' => '0', 'text' => SPECIALS_CONDITION_DROPDOWN_0),
                                    array('id' => '1', 'text' => SPECIALS_CONDITION_DROPDOWN_1),
                                    array('id' => '2', 'text' => SPECIALS_CONDITION_DROPDOWN_2));
		$deduction_type_array = array(array('id' => '0', 'text' => DEDUCTION_TYPE_DROPDOWN_0),
                                array('id' => '1', 'text' => DEDUCTION_TYPE_DROPDOWN_1),
                                array('id' => '2', 'text' => DEDUCTION_TYPE_DROPDOWN_2));							
		if(tep_db_num_rows($salemaker_sales_query)>0)
			$sInfo=new objectInfo($salemaker_sales_result);
		$row=0; 
		if($sInfo->sale_discount_type=='') 
			$sInfo->sale_discount_type='S';
		echo tep_draw_form('sales_maker','sales_maker.php', ' ' ,'post','id="sales_maker"');
	?>
        <script type="text/javascript" src="includes/date-picker.js"></script>
        <link href="includes/jquery-ui.css" rel="stylesheet">
        <script type="text/javascript" src="includes/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="includes/jquery-ui.js"></script>
	<table cellpadding="3" cellspacing="3" border="0" width="100%" align="center">
		<!-- <tr id="error_sales" style="display:none" class="main" align="center"><td colspan="2"><font color="#ff0000"><?php echo ERROR_SALE_MAKER;?></font></td></tr>-->
  <tr>
			<td valign="top">
				<table border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td class="main" width="200"><?php echo TEXT_SALEMAKER_NAME; ?>&nbsp;</td>
						<td class="main"><?php echo tep_draw_input_field('name', $sInfo->sale_name, 'size="37"'); ?></td>
					</tr>
					 <tr>
						<td class="main"><?php echo TEXT_DISCOUNT_TYPE; ?>&nbsp;</td>
						<td class="main"><?php echo tep_draw_radio_field('discount_type', 'S',($sInfo->sale_discount_type=='S')?true:false,'',' onClick="javascript:discount_action();"') . TEXT_SALES_MAKER . '&nbsp;' . tep_draw_radio_field('discount_type', 'C',($sInfo->sale_discount_type=='C')?true:false,'',' onClick="javascript:discount_action();"') . TEXT_CUSTOMER_CHOICE; ?></td>
					 </tr>
					 <?php $style='style="display:none"';
							if($sInfo->sale_discount_type=='C')
								$style='style="display:\'\'"';
					 ?>
					<tr id="choice_text" <?php echo $style;?>>
						<td class="main" width="200"><?php echo TEXT_CHOICE_TEXT; ?>&nbsp;</td>
						<td class="main"><?php echo tep_draw_input_field('txt_choice_text', $sInfo->choice_text, 'size="37"'); ?></td>
					</tr>
					<tr id="choice_warning" <?php echo $style;?>>
						<td class="main" width="200"><?php echo TEXT_CHOICE_WARNING; ?>&nbsp;</td>
						<td class="main"><?php echo tep_draw_input_field('txt_choice_warning', $sInfo->choice_warning, 'size="37"'); ?></td>
					</tr>
					<tr>
						<td colspan="2">
						<table border="0"  cellspacing="0" cellpadding="2">
							<?php
							$categories_query = tep_db_query("select c.categories_id, c.parent_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "'");
                            $categories_array = array();
							while($categories = tep_db_fetch_array($categories_query)) {
							  $categories_array[] = $categories;
							}
							$n = sizeof($categories_array);
							for($i = 0; $i < $n; $i++) {
							  $categories_array[$i]['path'] = $categories_array[$i]['categories_id'];
							  $categories_array[$i]['indent'] = 0;
							  $parent = $categories_array[$i]['parent_id'];
							  while($parent != 0) {
								$categories_array[$i]['indent']++;
								for($j = 0; $j < $n; $j++) {
								  if($categories_array[$j]['categories_id'] == $parent) {
									$categories_array[$i]['path'] = $parent . '_' . $categories_array[$i]['path'];
									$parent = $categories_array[$j]['parent_id'];
									break;
								  }
								}
							  }
							  $categories_array[$i]['path'] = '0_' . $categories_array[$i]['path'];
							}
							
							$order_changed = true;
							while($order_changed) {
							  $order_changed = false;
							  for($i = 0, $n = (sizeof($categories_array) - 1); $i < $n; $i++) {
								if($categories_array[$i]['path'] > $categories_array[$i + 1]['path']) {
								  $tmp = $categories_array[$i];
								  $categories_array[$i] = $categories_array[$i + 1];
								  $categories_array[$i + 1] = $tmp;
								  $order_changed = true;
								}
							  }
							}
							
							
							$main_selected = ($sInfo->sale_categories_all=='' && $sInfo->sale_categories_selected=='' && $sInfo_sale_products_selected=='')?true:false;
							echo "      <tr>\n";
							echo '        <td valign="bottom">' . tep_draw_separator('pixel_trans.gif', '4', '1') . tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif') . "</td>\n";
							echo '        <td class="main"><br>' . TEXT_SALEMAKER_ENTIRE_CATALOG . "</td>\n";
							echo "      </tr>\n";
							echo '      <tr>' . "\n";
							echo '        <td colspan="2" class="main"><label for="all_categories">' . tep_draw_checkbox_field('all_categories', '1', $main_selected,'','onClick="javascript: all_category();"').'&nbsp;&nbsp;'.TEXT_SALEMAKER_TOP . "</label></td>\n";
							echo '        <td class="main">&nbsp;</td>';
							echo "      </tr>\n";
							echo "      <tr>\n";
							echo '        <td valign="bottom">' . tep_draw_separator('pixel_trans.gif', '4', '1') . tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif') . "</td>\n";
							echo '        <td class="main"><br>' . TEXT_SALEMAKER_CATEGORIES . "</td>\n";
							echo "      </tr>\n";
							
							/* str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category['indent']) . */
							$prefix_category_name="";
							$sale_categories_selected_array = explode(',', $sInfo->sale_categories_selected);
							$sale_categories_all_array = explode(',', $sInfo->sale_categories_all);
							foreach($categories_array as $category) {
								if($category['parent_id'] != 0){ 
								$category_path_array = @explode("_",$category['path']);
								$prefix_category_name = "";
									for($i=0;$i<count($category_path_array);$i++){
									$category_name_query = tep_db_query("select categories_name from ".TABLE_CATEGORIES." c, ".TABLE_CATEGORIES_DESCRIPTION." cd where c.categories_id=cd.categories_id and cd.language_id='".(int)$FSESSION->languages_id."' and cd.categories_id='{$category_path_array[$i]}'");
									$category_name_array = tep_db_fetch_array($category_name_query);
									$prefix_category_name .= "{$category_name_array['categories_name']} > ";
									}
								$prefix_category_name = substr($prefix_category_name,2);
								$prefix_category_name = substr($prefix_category_name,0,-2);
								}else{
								$prefix_category_name = " ".$category['categories_name'];
								}
							 
							  if ( in_array($category['categories_id'],$sale_categories_selected_array) || in_array($category['categories_id'],$sale_categories_all_array) ) 
								$selected = true;						  
							  else 
								$selected = false;
							  
							  
							  $selected = ($selected==true)?'checked':'';
							  
							  // onClick="javascript: CategoryClick(\'' . $category['path'] . '\',\''.$category['categories_id'].'\',\''.$category['categories_name'].'\')"
							  echo '<tr>' . "\n";
							  //echo '<td width="10">' . tep_draw_checkbox_field('categories[]', $category['path'], $selected,'','') . "</td>\n";
							  echo '<td colspan="2" class="main">' . "<input type='checkbox' ".(($main_selected==true)?'disabled':'').' onClick="javascript: CategoryClick1(\'' . $category['path'] . '\',\''.$category['categories_id'].'\',\''.$category['categories_name'].'\')"'." name='categories[]' id='check_box_{$category['categories_id']}' $selected value='{$category['categories_id']}' style='border: medium none ;' class='inputNormal' onblur='javascript:toggle_focus(this,1);' onfocus='javascript:toggle_focus(this,2);'>". "$prefix_category_name</td>\n";
							  echo '<td>&nbsp;</td>';
							  echo '</tr>' . "\n";
							}
							?>
							</table>
							</td>
							</tr>
							
							
						</table>
					</td>
					<td colspan="2" valign="top">
				  <table valign="top" width="100%" cellpadding="3" cellspacing="3" border="0">
							<tr>
								<td class="main"><?php echo TEXT_SALEMAKER_DEDUCTION; ?>&nbsp;</td>
								<td class="main"><?php echo tep_draw_input_field('deduction', $sInfo->sale_deduction_value, 'size="8"') . TEXT_SALEMAKER_DEDUCTION_TYPE . tep_draw_pull_down_menu('type', $deduction_type_array, $sInfo->sale_deduction_type); ?></td>
							</tr>
							<tr>
								<td class="main" style="display:none"><?php echo TEXT_SALEMAKER_PRICERANGE_FROM; ?>&nbsp;</td>
								<td class="main" style="display:none"><?php echo tep_draw_input_field('from', $sInfo->sale_pricerange_from, 'size="8"') . TEXT_SALEMAKER_PRICERANGE_TO . tep_draw_input_field('to', $sInfo->sale_pricerange_to, 'size="8"'); ?></td>
							</tr>
							<tr>
								<td class="main"><?php echo TEXT_SALEMAKER_SPECIALS_CONDITION; ?>&nbsp;</td>
								<td class="main"><?php echo tep_draw_pull_down_menu('condition', $specials_condition_array, $sInfo->sale_specials_condition); ?></td>
							</tr>
							<tr>
								<?php $_array=array('d','m','Y');  $replace_array=array('DD','MM','YYYY'); 	$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>
								<td class="main"><?php echo TEXT_SALEMAKER_DATE_START . '('.$date_format . ')'; ?>&nbsp;</td>
								<td class="main" align="left">
                                <input type="text" size="10" maxlength="10" id="start" onclick="callstrt();" name="start"></td>
							</tr>
                                                        
							<tr>
								<td class="main"><?php echo TEXT_SALEMAKER_DATE_END. '('.$date_format . ')'; ?>&nbsp;</td>
								<td class="main" align="left">
                                                                <input type="text" size="10" maxlength="10" id="end" onclick="callenddt();" name="end">
                                                                <?php //echo  tep_draw_input_field("end",format_date($sInfo->sale_date_end),"size=10",false,'text',false);?>
									<!--a href="javascript:show_calendar('sales_maker.end',null,null,'<?php echo $date_format;?>');"
									onmouseover="window.status='Date Picker';return true;"
									onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/ align="absmiddle">  
								</a-->
								</td>
							</tr>	
							<tr>
								<td style="display:none" colspan="2" class="main"><?php echo tep_draw_checkbox_field('apply_to_cross_sale','Y',($sInfo->apply_to_cross_sale=='Y')?true:false) . '&nbsp;' . TEXT_APPLY_TO_CROSS_SALES;?></td>
							</tr>	
							<tr>
								<td style="display:none" colspan="2" class="main"><font color="#FF0000"><?php echo TEXT_FORCED_ITEM_WARNING;?></font></td>
							</tr>	
						</table>
				</td>
	  </tr>
			
			<tr>
				<td id="ajax_sales_maker">
				<div id="head_ajax_sales_maker" class="main" style="padding-bottom:3px;padding-left:5px;"><?php echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif').TEXT_SALEMAKER_PRODUCTS; ?></div>
				<?php echo list_sales_maker_categories($sInfo->sale_id); ?>
				</td>
			</tr>
			
			<tr><td><?php echo tep_draw_separator('pixel_trans.gif','10','10');?></td></tr>
		</table>	
<?php
		echo tep_draw_hidden_field('prd_sale_id',$sInfo->sale_id);
		echo tep_draw_hidden_field('unsel_list',$sInfo->sale_categories_selected);
		echo tep_draw_hidden_field('sel_prd_list',$sInfo->sale_products_selected);
		echo '</form>';	
		$jsData->VARS["updateMenu"]=",update,";
		$display_mode_html=' style="display:none"';
		 
	}	
	function doUpdate()
	{
		global $FREQUEST,$jsData;
		$sale_id=$FREQUEST->postvalue("prd_sale_id","int",-1);
		$status=0;									
		$error=false;
		if($FREQUEST->postvalue('categories')) {
			if(strpos($FREQUEST->postvalue('categories'),',')!==false) 
				{$categories = preg_split("/,/",$FREQUEST->postvalue('categories'));}
					else 
				{$categories[0] = $FREQUEST->postvalue('categories');	}
		}	

		$categories_all_string=","; $categories_selected_string=","; $products_selected_string=",";
		$all_categories = $FREQUEST->postvalue('all_categories');
		if($all_categories==1){
			$categories_all_string = '';
		}else{
			if($FREQUEST->postvalue('category_list')) {
				if(strpos($FREQUEST->postvalue('category_list'),',')!==false) $category_list = preg_split("/,/",$FREQUEST->postvalue('category_list'));
				else $category_list = $FREQUEST->postvalue('category_list');
			}	
			if($FREQUEST->postvalue('products')) {
				if(strpos($FREQUEST->postvalue('products'),',')!==false) $products = preg_split("/,/",$FREQUEST->postvalue('products'));
				else $products = $FREQUEST->postvalue('products');
			}	
			if($FREQUEST->postvalue('unsel_list')) {
				if(strpos($FREQUEST->postvalue('unsel_list'),',')!==false) $unsel_list = preg_split("/,/",$FREQUEST->postvalue('unsel_list'));
				else $unsel_list = $FREQUEST->postvalue('unsel_list');
			}	
			if($FREQUEST->postvalue('sel_prd_list')) {
				if(strpos($FREQUEST->postvalue('sel_prd_list'),',')!==false) $sel_prd_list = preg_split("/,/",$FREQUEST->postvalue('sel_prd_list'));
				else $sel_prd_list = $FREQUEST->postvalue('sel_prd_list');
			}	 
            /*for($i=0,$n=sizeof($categories);$i<$n;$i++){
                if(is_array($category_list) &&  array_key_exists($categories[$i],$category_list)){
                    $categories_all_string .= "{$categories[$i]},";
                }else{
                    if(is_array($products[$categories[$i]]))
                        while(list($key,$val) = each($products[$categories[$i]]))
                            $products_selected_string .= "$val,";
                $categories_selected_string .= "{$categories[$i]},";
                }
            }*/
                //$cat_all_array=array_intersect($category_list,$categories);
            /*	for($icnt=0;$icnt<sizeof($category_list);$icnt++) {
                    for($jcnt=0;$jcnt<sizeof($categories);$jcnt++) {
                        if($category_list[$icnt]!=$categories[$jcnt])
                            $sel_array[]=$categories[$jcnt];
                    }
                }
                $cat_sel_array=array_unique($sel_array);
                for($j=0;$j<sizeof($cat_sel_array);$j++) {
                    $categories_selected_string.=$cat_sel_array[$j] . ',';
                }	 */
     //       print_r($unsel_list); echo "<br>"; print_r($sel_prd_list); echo '<br>size='. sizeof($unsel_list) . "<br>size_prd_list=".sizeof($sel_prd_list);
			if(sizeof($categories)>0 || sizeof($unsel_list)>0 || sizeof($sel_prd_list)>0) {
				for($jcnt=0;$jcnt<sizeof($categories);$jcnt++) {
                    if(isset($categories[$jcnt]) && ($categories[$jcnt]!='')){
                    $categories_all_string.=$categories[$jcnt] . ',';
                    }
				}	
				for($icnt=0;$icnt<sizeof($unsel_list);$icnt++) {
                    if(isset($unsel_list[$icnt]) && ($unsel_list[$icnt]!='')){
                  //      echo $unsel_list[$icnt] . "<br>";
                  //      print_r($categories);
                        $exec=true;
                        if(is_array($categories))
                        if(in_array($unsel_list[$icnt],$categories))
                            $exec=false;
                    if($exec)
                        $categories_selected_string.=$unsel_list[$icnt] . ',';
                    }
				}	
				for($kcnt=0;$kcnt<sizeof($sel_prd_list);$kcnt++) {
                    if(isset($sel_prd_list[$kcnt]) && ($sel_prd_list[$kcnt]!='')){

                    $exec=true;
                    $chk_query = tep_db_query('select categories_id from products_to_categories where products_id="'.$sel_prd_list[$kcnt].'"');
                  // echo 'select categories_id from products_to_categories where products_id="'.$sel_prd_list[$kcnt].'"';
                   while($chk_array = tep_db_fetch_array($chk_query)){
                        if(is_array($categories))
                        if(in_array($chk_array['categories_id'],$categories))
                            $exec=false;

                    }

                    if($exec)
                        $products_selected_string.=$sel_prd_list[$kcnt] . ',';
                    }
				}	
			/*	$categories_all_string = substr($categories_all_string,0,-1);	
				$categories_selected_string = substr($categories_selected_string,0,-1);
				$products_selected_string = substr($products_selected_string,0,-1);  */
			}	
			else {
				$categories_all_string = $categories;
				$categories_selected_string = $unsel_list;
				$products_selected_string = $sel_prd_list;
				$select_cat_ids=$categories_all_string;
			}
		} 
		//if(sizeof($categories)>0 || sizeof($unsel_list)>0 || sizeof($sel_prd_list)>0) {
			
			if($categories_all_string==',') $categories_all_string='';
			if($categories_selected_string==',') $categories_selected_string='';
			if($products_selected_string==',') $products_selected_string='';

			
			if(strpos($categories_all_string,',')!==false) {
				$select_cat_ids=substr($categories_all_string,1);
				$select_cat_ids=substr($select_cat_ids,0,-1);
			}	
			else {
				$select_cat_ids=$categories_all_string;
			}	
		//}	
		$sel_product_list='';	
		if($categories_all_string!='') {
			$sWhere=" where categories_id in(" .$select_cat_ids . ")";
			$select_cat_query=tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . $sWhere);
			while($select_cat_result=tep_db_fetch_array($select_cat_query)) {
				$sel_product_list.=$select_cat_result['products_id'] . ",";
			}
			if($sel_product_list!='') $sel_product_list=substr($sel_product_list,0);
		}

        //echo '<br><br> prod '.$products_selected_string;

		$select_pro_ids=substr($products_selected_string,1);
		if($select_pro_ids!='')
			$sel_product_list.= $select_pro_ids;
		if(substr($sel_product_list,-1)==',') $sel_product_list=substr($sel_product_list,0,-1);
		if($sel_product_list!='') {
           // echo "select p.products_price_break from " . TABLE_PRODUCTS . " p where p.products_price_break='Y' and products_id in (" . $sel_product_list . ")";
			$sales_products_query=tep_db_query("select p.products_price_break from " . TABLE_PRODUCTS . " p where p.products_price_break='Y' and products_id in (" . $sel_product_list . ")");
			if(tep_db_num_rows($sales_products_query)>0) 
				$error=true;
		}	
		if(!$error)  {
			$start_date = tep_convert_date_raw($FREQUEST->postvalue('start'));
			/*if($start_date!='')
				$start_date = (getServerDate() < $start_date) ? $start_date : getServerDate(); */
				
			$end_date = tep_convert_date_raw($FREQUEST->postvalue('end'));
			$apply_to_cross_sale=$FREQUEST->postvalue('apply_to_cross_sale');
			if($apply_to_cross_sale!='Y')
				$apply_to_cross_sale='N';
			/*if($end_date!='')
				$end_date = (getServerDate() < $end_date) ? $end_date : getServerDate(); */
	
			$salemaker_sales_data_array = array('sale_name' => $FREQUEST->postvalue('name'),
												'sale_deduction_value' => $FREQUEST->postvalue('deduction'),
												'sale_deduction_type' => $FREQUEST->postvalue('type'),
												'sale_pricerange_from' => $FREQUEST->postvalue('from'),
												'sale_pricerange_to' => $FREQUEST->postvalue('to'),
												'sale_specials_condition' => $FREQUEST->postvalue('condition'),
												'sale_categories_selected' => $categories_selected_string,
												'sale_products_selected' => $products_selected_string,
												'sale_categories_all' => $categories_all_string,
												'sale_date_start' => (($start_date == '') ? '0000-00-00' : $start_date),
												'sale_date_end' => (($end_date == '') ? '0000-00-00' : $end_date),
												'sale_discount_type'=> $FREQUEST->postvalue('discount_type'),
												'apply_to_cross_sale'=>$apply_to_cross_sale);
												
			$salemaker_sales_data_array['choice_text']='';
			$salemaker_sales_data_array['choice_warning']='';		
			if($FREQUEST->postvalue('discount_type')=='C') {
				$salemaker_sales_data_array['choice_text']=$FREQUEST->postvalue('txt_choice_text');
				$salemaker_sales_data_array['choice_warning']=$FREQUEST->postvalue('txt_choice_warning');
			}
			if($salemaker_sales_data_array['sale_deduction_value'] == "") $salemaker_sales_data_array['sale_deduction_value']=0;
			if($salemaker_sales_data_array['sale_pricerange_from'] == "") $salemaker_sales_data_array['sale_pricerange_from']=0;
			if($salemaker_sales_data_array['sale_pricerange_to'] == "") $salemaker_sales_data_array['sale_pricerange_to']=0;
			//print_r($salemaker_sales_data_array);echo '======<br><br>';exit;
            if ($sale_id<=0) {
			  $salemaker_sales['sale_status'] = $status;
			  $salemaker_sales_data_array['sale_date_added'] = 'now()';
			  $salemaker_sales_data_array['sale_date_last_modified'] = '0000-00-00';
			  $salemaker_sales_data_array['sale_date_status_change'] = '0000-00-00';
			  tep_db_perform(TABLE_SALEMAKER_SALES, $salemaker_sales_data_array, 'insert');
			  $sale_id = tep_db_insert_id();
			  $insert=true;
			} elseif ($sale_id>0) { 
			  $salemaker_sales_data_array['sale_date_last_modified'] = 'now()';
			  tep_db_perform(TABLE_SALEMAKER_SALES, $salemaker_sales_data_array, 'update', "sale_id = '" . $sale_id . "'");
			  $insert=false;
			}
		} 
		else {
			 echo 'Err:'.'Product has price break choose some other product';return;
		}
	if ($insert) {
		$this->doItems();
	} else {
		if ($status==1){
			$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $sale_id .",get:\'SaleChangeStatus\',result:doSimpleResult,params:\'rID=". $sale_id . "&status=0\'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
		} else {
			$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $sale_id .",get:\'SaleChangeStatus\',result:doSimpleResult,params:\'rID=". $sale_id . "&status=1\'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
		}
		$jsData->VARS["replace"]=array($this->type. $sale_id . "name"=>$FREQUEST->postvalue('name'));
		$jsData->VARS["prevAction"]=array('id'=>$sale_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
		$this->doInfo($sale_id);
		$jsData->VARS["updateMenu"]=",normal,";
		}
		
	}
	function doDelete(){
		global $FREQUEST,$jsData;
		$sale_id=$FREQUEST->postvalue('sale_id','int',0);
		if ($sale_id>0){
      		  tep_db_query("delete from " . TABLE_SALEMAKER_SALES . " where sale_id = '" . tep_db_input($sale_id) . "'");
			$this->doItems();
			$jsData->VARS["displayMessage"]=array('text'=>TEXT_SALES_DELETE_SUCCESS);
			tep_reset_seo_cache('customers');
		} else {
			echo "Err:" . TEXT_SALES_NOT_DELETED;
		}
		
	}
		
	function doDeleteSales(){
		global $FREQUEST,$jsData;
		$sale_id=$FREQUEST->getvalue('rID','int',0);

		$delete_message='<p><span class="smallText">' . TEXT_INFO_DELETE_INTRO . '</span>';
?>
		<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="sales_maker.php" method="post" enctype="application/x-www-form-urlencoded">
			<input type="hidden" name="sale_id" value="<?php echo tep_output_string($sale_id);?>"/>
			<table border="0" cellpadding="2" cellspacing="0" width="100%">
				<tr>
					<td class="main" id="<?php echo $this->type . $sale_id;?>message">
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
						<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $sale_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
						<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $sale_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
					</td>
				</tr>
				<tr>
					<td><hr/></td>
				</tr>
				<tr>
					<td valign="top" class="categoryInfo"><?php echo $this->doInfo($sale_id);?></td>
				</tr>
			</table>
		</form>
<?php
		$jsData->VARS["updateMenu"]="";
	}
	function doSaleCopyDisplay() {
		global $FSESSION,$jsData,$FREQUEST;
		$sale_id=$FREQUEST->getvalue('rID','int',0);
		$salemaker_sales_query = tep_db_query("select sale_id, sale_status, sale_name, sale_deduction_value, sale_deduction_type, sale_pricerange_from, sale_pricerange_to, sale_specials_condition, sale_categories_selected, sale_categories_all, sale_date_start, sale_date_end, sale_date_added, sale_date_last_modified, sale_date_status_change,apply_to_cross_sale from " . TABLE_SALEMAKER_SALES . " where sale_id='" . tep_db_input($sale_id) . "'");
		$salemaker_sales_result = tep_db_fetch_array($salemaker_sales_query); 
?>
		<form  name="salmakeCopySubmit" id="salmakeCopySubmit" action="sales_maker.php" method="post" enctype="application/x-www-form-urlencoded">
		<input type="hidden" name="sale_id" value="<?php echo tep_output_string($sale_id);?>"/>
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<tr>
				<td class="main" id="salmake<?php echo $sale_id;?>message"></td>
			</tr>
			<tr>
				<td class="inner_title"><?php echo TEXT_INFO_HEADING_COPY_SALE;?></td>
			</tr>
			<tr>
				<td valign="top"><table cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main"><?php echo sprintf(TEXT_INFO_COPY_INTRO, $salemaker_sales_result['sale_name']); ?></td>
						<td class="main"><?php echo tep_draw_input_field('name', '', 'size="37"'); ?></td>
					</tr>
				</table></td>
			</tr>
			<tr>
				<td class="main" align="left">
					<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $sale_id;?>,type:'salmake',get:'SaleCopy',result:doTotalResult,message:'<?php echo tep_output_string(TEXT_PRODUCT_COPYING);?>','uptForm':'salmakeCopySubmit','validate':validateCopyDetail,'closePrev':true,'imgUpdate':false,params:''})"><?php echo tep_image_button('button_copy.gif');?></a>&nbsp;
					<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $sale_id;?>,type:'salmake',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button('button_cancel.gif');?></a>
				</td>

			</tr>
			</table>
			</form>
<?php 	$jsData->VARS["updateMenu"]="";
	}
	function doSaleCopy() {
		global $FSESSION, $jsData,$FREQUEST;
		$sale_id=$FREQUEST->postvalue('sale_id');
		$sql_array=array('sale_name'=>$FREQUEST->postvalue('name'),
                         'sale_date_added'=>'now()',
						 'sale_date_last_modified'=>'0000-00-00',
						 'sale_date_status_change'=>'0000-00-00');
		tep_db_perform(TABLE_SALEMAKER_SALES, $sql_array, 'insert');
		$sale_id = tep_db_insert_id();
		$this->doItems();
		$jsData->VARS["displayMessage"]=array('text'=>TEXT_SALES_COPY_SUCCESS);
	}
	function doSaleChangeStatus(){
		global $FREQUEST,$jsData;
		$sale_id=$FREQUEST->getvalue("rID","int",0);
		$status=$FREQUEST->getvalue("status","int",0);
		if ($sale_id<=0) return;
		if ($status!=0 && $status!=1) $status=0;
		tep_db_query("UPDATE " . TABLE_SALEMAKER_SALES . " set sale_status=" . $status . " where sale_id=$sale_id");
		if ($status==1){
			$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $sale_id .",get:\'SaleChangeStatus\',result:doSimpleResult,params:\'rID=". $sale_id . "&status=0\',message:\'".TEXT_UPDATING_STATUS."\'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
		} else {
			$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $sale_id .",get:\'SaleChangeStatus\',result:doSimpleResult,params:\'rID=". $sale_id . "&status=1\',message:\'".TEXT_UPDATING_STATUS."\'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
		}
		echo 'SUCCESS';
		$jsData->VARS["replace"]=array("salmake". $sale_id ."bullet"=>$result);
	}
	function doFetchProduct(){
	global $FSESSION,$FREQUEST,$jsData;
	$cat_id=$FREQUEST->getvalue('cID');
	$products_description_query = tep_db_query("select pd.products_id,pd.products_name from ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_DESCRIPTION." pd, ".TABLE_PRODUCTS_TO_CATEGORIES." ptc where ptc.products_id=p.products_id and p.products_id=pd.products_id and pd.language_id='".(int)$FSESSION->languages_id."' and ptc.categories_id='".(int)$cat_id."'");
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="30">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<?php 
	$sale_products_selected_array = array();
	if(is_object($sInfo))
		$sale_products_selected_array = explode(",",$sInfo->sale_products_selected);
	while($products_description_array = tep_db_fetch_array($products_description_query)){ 
	$checked=true;
	  if(is_object($sInfo))
	   	if(in_array($products_description_array['products_id'],$sale_products_selected_array))
			$checked=true;
		else
			$checked=false;
	?>
	<tr>
	<td class="main"><label><?php echo tep_draw_checkbox_field("products[$cat_id][]",$products_description_array['products_id'],'',$checked,'onClick="javascript: chk_category(\''.$cat_id.'\',\''.$products_description_array['products_id'].'\');"').'&nbsp;'.$products_description_array['products_name']; ?></label></td>
	</tr>
	<?php } 
	if(tep_db_num_rows($products_description_query)<=0){
	?>
	<tr>
	<td class="main" style="color:#FF0000;"><?php echo TEXT_NO_PRODUCTS_FOUND; ?></td>
	</tr>
	<?php } ?>
	</table>
</td>
</tr>
</table>
<?php  
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
					<td width="2%" id="salmake##ID##bullet">##STATUS##</td>
					<td class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##name">##NAME##</td>
					<td id="##TYPE####ID##menu" align="right" class="boxRowMenu">
						<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
						<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
						<img src="##IMAGE_PATH##template/img_bar.gif"/>
						<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'SaleCopyDisplay','result':doDisplayResult,'style':'boxRow','type':'salmake','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/copy_blue.gif" title="Copy"/></a>
						<img src="##IMAGE_PATH##template/img_bar.gif"/>
						<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteSales','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
						<img src="##IMAGE_PATH##template/img_bar.gif"/>
						</span>
						<span id="##TYPE####ID##mupdate" style="display:none">
						<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':check_form,'uptForm':'sales_maker','customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
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
	function getInfoTemplate(){
		ob_start();
?>
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<div class="hLineGray"></div>
			<tr> <td class="main"><div style=" font-weight:bold; padding-top:10px; width:100%;height:20px;overflow:hidden"><!--##HEAD_NAME##--></div></td>
			<tr>
				<td style="padding-left:50px;" valign="top">
					<table cellpadding="2" cellspacing="0" width="100%">
						<tr>
							<td valign="top">
								<table cellpadding="2" cellspacing="0" width="50%">
									<tr>
										<td class="main" align="left">##ENT_SALE_NAME##</td>
										<Td class="main" align="left">##SALE_NAME##</Td>
									</tr>
									<tr>
										<td class="main" align="left">##ENT_START_DATE##</td>
										<Td class="main" align="left">##START_DATE##</Td>
									</tr>
									<tr>
										<td class="main" align="left">##ENT_SALE_TYPE##</td>
										<Td class="main" align="left">##SALE_TYPE##</Td>
									</tr>
								</table>
							</td>
							<td valign="top">
								<table cellpadding="2" cellspacing="0" width="50%">
									<tr>
										<td class="main" align="left">##ENT_DEDUCTION##</td>
										<Td class="main" align="left">##DEDUCTION##</Td>
									</tr>
									<tr>
										<td class="main" align="left">##ENT_END_DATE##</td>
										<Td class="main" align="left">##END_DATE##</Td>
									</tr>
									<tr>
										<td class="main" align="left">##ENT_DISCOUNT_TYPE##</td>
										<Td class="main" align="left">##DISCOUNT_TYPE##</Td>
									</tr>
								</table>
							</td>
						</tr>
						<tr height="40"><td class="main" colspan="2" align="left" valign="bottom"><b>##ENT_USAGE_TIPS##</b></td></tr>
						<tr>	
							<Td class="main" colspan="2"><div style="width:80%;height:100;overflow:auto;">##USAGE_TIPS##</div></Td>
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
	function list_sales_maker_categories($sale_id){
	global $FSESSION,$sInfo;
	$salemaker_sales_query = tep_db_query("select sale_id, sale_status, sale_name,choice_text,choice_warning,sale_discount_type, sale_deduction_value, sale_deduction_type, sale_pricerange_from, sale_pricerange_to, sale_specials_condition, sale_categories_selected, sale_categories_all, sale_date_start, sale_date_end, sale_date_added, sale_date_last_modified, sale_date_status_change,sale_products_selected,apply_to_cross_sale from " . TABLE_SALEMAKER_SALES . " where sale_id='" . tep_db_input($sale_id) . "'");
	$salemaker_sales_result = tep_db_fetch_array($salemaker_sales_query);
   // print_r($salemaker_sales_result);
	if(tep_db_num_rows($salemaker_sales_query)>0)
		$sInfo=new objectInfo($salemaker_sales_result);
	if($sInfo->sale_categories_all!=""){
	$sale_categories_all = $sInfo->sale_categories_all;
	if(substr($sale_categories_all,-1)==',')
		$sale_categories_all = substr($sInfo->sale_categories_all,0,-1);
	if($sale_categories_all{0}==',')
		$sale_categories_all = substr($sale_categories_all,1);
	$sales_categories_query = tep_db_query("select cd.categories_id,cd.categories_name from ".TABLE_CATEGORIES." c, ".TABLE_CATEGORIES_DESCRIPTION." cd where c.categories_id=cd.categories_id and cd.language_id='".(int)$FSESSION->languages_id."' and cd.categories_id in ($sale_categories_all) ");
	while($sales_categories_array = tep_db_fetch_array($sales_categories_query)){
	?>
	
	<div class="main" id="cat_<?php echo $sales_categories_array['categories_id']; ?>" onClick="javascript:return doProductAction({'id':'<?php echo $sales_categories_array['categories_id']; ?>','get':'FetchProduct','style':'boxRow','type':'disproduct','params':'cID=<?php echo $sales_categories_array['categories_id']; ?>'});">
	<label for="category_<?php echo $sales_categories_array['categories_id']; ?>"><input name="category_list[]" checked="checked" id="category_<?php echo $sales_categories_array['categories_id']; ?>" value="<?php echo $sales_categories_array['categories_id']; ?>" type="checkbox">&nbsp;<?php echo $sales_categories_array['categories_name']; ?></label>
	</div>
	<div class="main" id="disproduct<?php echo $sales_categories_array['categories_id']; ?>"></div>
	<?php
	}
	}
	if($sInfo->sale_categories_selected!=''){
	$sale_categories_selected = $sInfo->sale_categories_selected;
	if(substr($sale_categories_selected,-1)==',')
		$sale_categories_selected = substr($sInfo->sale_categories_selected,0,-1);
	if($sale_categories_selected{0}==',')
		$sale_categories_selected = substr($sale_categories_selected,1);
	
	$sales_categories_query = tep_db_query("select cd.categories_id,cd.categories_name from ".TABLE_CATEGORIES." c, ".TABLE_CATEGORIES_DESCRIPTION." cd where c.categories_id=cd.categories_id and cd.language_id='". (int)$FSESSION->languages_id."' and cd.categories_id in ($sale_categories_selected) ");
	while($sales_categories_array = tep_db_fetch_array($sales_categories_query)){
	?>
	<div class="main" id="cat_<?php echo $sales_categories_array['categories_id']; ?>" onClick="javascript:return doProductAction({'id':'<?php echo $sales_categories_array['categories_id']; ?>','get':'FetchProduct','style':'boxRow','type':'disproduct','params':'cID=<?php echo $sales_categories_array['categories_id']; ?>'});">
	<label for="category_<?php echo $sales_categories_array['categories_id']; ?>"><input name="category_list[]" id="category_<?php echo $sales_categories_array['categories_id']; ?>" value="<?php echo $sales_categories_array['categories_id']; ?>" type="checkbox">&nbsp;<?php echo $sales_categories_array['categories_name']; ?></label>
	</div>
	<div class="main" id="disproduct<?php echo $sales_categories_array['categories_id']; ?>"><?php echo fetch_product($sales_categories_array['categories_id']); ?></div>
	<?php
	}
	} ?>
	<?php
	}
	function fetch_product($category_id){
	global $FSESSION,$sInfo;
	$products_description_query = tep_db_query("select pd.products_id,pd.products_name from ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_DESCRIPTION." pd, ".TABLE_PRODUCTS_TO_CATEGORIES." ptc where ptc.products_id=p.products_id and p.products_id=pd.products_id and pd.language_id='".(int)$FSESSION->languages_id."' and ptc.categories_id='".(int)$category_id."'");
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="30">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<?php 
	$sale_products_selected_array = array();
	if(is_object($sInfo))
		$sale_products_selected_array = explode(",",$sInfo->sale_products_selected);
	while($products_description_array = tep_db_fetch_array($products_description_query)){ 
	$checked=true;
	  if(is_object($sInfo))
	   	if(in_array($products_description_array['products_id'],$sale_products_selected_array))
			$checked=true;
		else
			$checked=false;
	?>
	<tr>
	<td class="main"><label><?php echo tep_draw_checkbox_field("products[$category_id][]",$products_description_array['products_id'],'',$checked,'onClick="javascript: chk_category(\''.$category_id.'\',\''.$products_description_array['products_id'].'\');"').'&nbsp;'.$products_description_array['products_name']; ?></label></td>
	</tr>
	<?php } 
	if(tep_db_num_rows($products_description_query)<=0){
	?>
	<tr>
	<td class="main" style="color:#FF0000;"><?php echo TEXT_NO_PRODUCTS_FOUND; ?></td>
	</tr>
	<?php } ?>
	</table>
</td>
</tr>
</table>
<?php } ?>
<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	defined('_FEXEC') or die();
	global $currencies;
	require(DIR_WS_CLASSES . 'currencies.php');			
	$currencies = new currencies();

	class paymentCurrencies{
		var $pagination;
		var $splitResult;
		var $type;

		function __construct() {
			$this->pagination=false;
			$this->splitResult=false;
			$this->type='cug';
		}
		
		function doSearchGroup(){
			global $FREQUEST,$jsData;
			$search=$FREQUEST->getvalue('search');
			$search_db=tep_db_input($search);
			?>
			<table border="0" width="100%" cellspacing="0" cellpadding="0" style="display:none">
				<tr>
					<td class="main">
						<b><?php echo TEXT_SEARCH_RESULTS;?></b>
					</td>
				</tr>
				<tr height="10">
					<td class="main">
					</td>
				</tr>
				<tr>
					<td>
					<table border="0" cellpadding="2" cellspacing="0" width="100%" id="catTable">
						<?php 
						$found=$this->doList(" where title like'%".$search_db."%'",0,$search);
						if (!$found){
						?>
						<tr>
							<td class="main">
								<?php echo TEXT_NO_RECORDS_FOUND;?>
							</td>
						</tr>			
						<?php 
							if ($this->splitResult->queryRows>0){
								echo $this->splitResult->pgLinksCombo();
							}
						} 
						?>
					</table>
					</td>
				</tr>
				<tr height="10">
					<td class="main">
					</td>
				</tr>
				<tr>
					<td class="main">
					<a href="javascript:void(0);" onClick="javascript:doSearchGroup('reset');"><?php echo tep_image_button('button_reset.gif',IMAGE_RESET);?></a>
					</td>
				</tr>
			</table>
<?php
		$jsData->VARS["NUclearType"]=$this->type;
		}
		function doDelete(){
			global $FREQUEST,$jsData;
			$group_id=$FREQUEST->postvalue('group_id','int',0);
			$last_flag=$FREQUEST->postvalue('lflag','int',0);
			$page=$FREQUEST->postvalue('page','int',0);
			
			if ($group_id>0){
				tep_db_query("DELETE from " . TABLE_CURRENCIES . " where currencies_id=$group_id");				
				
				if ($last_flag==1 && $page>1){
					$page=$page-1;
					$FREQUEST->setvalue('page',$page,'GET');
				}
				$this->doItems();
				
				$jsData->VARS["deleteRow"]=array("id"=>$group_id,"type"=>$this->type);
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_GROUP_DELETE_SUCCESS);
				tep_reset_seo_cache('customers');
			} else {
				echo "Err:" . TEXT_CUSTOMER_GROUPS_NOT_DELETED;
			}
			
		}
		
		function doDeleteGroups(){
			global $FREQUEST,$jsData;
			$group_id=$FREQUEST->getvalue('rID','int',0);

			$delete_message='<p><span class="smallText">' . TEXT_INFO_DELETE_INTRO . '</span>';	?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="<?php echo FILENAME_SHOP_LANGUAGES_NEW; ?>" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="group_id" value="<?php echo tep_output_string($group_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $group_id;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $group_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doLocationResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $group_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php echo $this->doInfo($group_id);?></td>
					</tr>
				</table>
			</form>
<?php		$jsData->VARS["updateMenu"]="";
		}
		function doList($where='',$group_id=0,$search=''){
			global $FSESSION,$FREQUEST,$jsData,$currencies;

			$page=$FREQUEST->getvalue('page','int',1);
			if ($search!=''){
				$orderBy="order by title";
			} else {
				$orderBy="order by customers_groups_id";
			}
			$query_split=false;
			

    	if (($FREQUEST->getvalue('search')!='') && tep_not_null($FREQUEST->getvalue('search'))){
		  $search = " where  (title like '%" . tep_db_input($FREQUEST->getvalue('search')) . "%' OR (code like '%".tep_db_input($FREQUEST->getvalue('search'))."%') )";  
		} else{
		  $search = "";
		}
	 	$customers_groups_sql="select * from " . TABLE_CURRENCIES . $search . " order by title";			
			if ($this->pagination){
				$query_split=$this->splitResult = (new instance)->getSplitResult('CUSTOMER');
				$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$customers_groups_sql);
						if ($query_split->queryRows > 0){ 
							if ($search!=''){
								$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','get':'SearchGroup','result':doTotalResult,params:'search=". urlencode($search) . "&page='+##PAGE_NO##,'message':'" . INFO_SEARCHING_DATA . "'})";
							} else {	
								$query_split->pageLink="doPageAction({'id':-1,'type':'cug','pageNav':true,'closePrev':true,'get':'Items','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_PRODUCTS,'##PAGE_NO##') . "'})";
							}
						}
			}
		//	echo $customers_groups_sql;
			$customers_groups_query=tep_db_query($customers_groups_sql);
			$found=false;
			if (tep_db_num_rows($customers_groups_query)>0) $found=true;
			if($found)
			{
			$template=getListTemplate();
			$icnt=1;
            
            while($customers_groups_result=tep_db_fetch_array($customers_groups_query)){
                    $query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key ='DEFAULT_CURRENCY'");
                    $row = tep_db_fetch_array($query);
					if($row['configuration_value']==$customers_groups_result["code"]){
					$currency_name=$customers_groups_result["title"].'&nbsp;<span style="font-size:10">(default)</span>';
					$currency_class='default_currency';
					}else{
					$currency_name=$customers_groups_result["title"];
					$currency_class='normal_currency';
					}
                    
					$rep_array=array(	"ID"=>$customers_groups_result["currencies_id"],
										"TYPE"=>$this->type,
										"NAME"=>'<div id="##CURRENCY_CLASS##" class="##CURRENCY_CLASS##">'.$currency_name.'</div>',
										"CODE"=>$customers_groups_result["code"],
										"VALUE"=>number_format($customers_groups_result["value"],2,'.',''),
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>'',
										"CURRENCY_CLASS"=>$currency_class,
										"UPDATE_RESULT"=>'doCurrencyResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'Info',
										"FIRST_MENU_DISPLAY"=>""
									);
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}
            
            }
			else if($search=='')
			{
			echo '<div align="center">'.TEXT_EMPTY_GROUPS.'</div>';
			}
			if (!isset($jsData->VARS["Page"])){
				$jsData->VARS["NUclearType"][]=$this->type;
			} 
			return $found;			
		}
		
		function doItems(){
			global $FREQUEST,$jsData;
			
			$template=getListTemplate();
				$rep_array=array(	"TYPE"=>$this->type,
									"ID"=>-1,
									"NAME"=>TEXT_INFO_HEADING_NEW_CURRENCY,
									"CODE"=>'',
									"VALUE"=>'',
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
									"UPDATE_RESULT"=>'doTotalResult',
									"ROW_CLICK_GET"=>'Edit',
									"FIRST_MENU_DISPLAY"=>"display:none"
								);
//echo CURRENCY_SERVER_PRIMARY;
?>
			<div class="main" id="cug-1message"></div>
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
									<tr  >
										<td class="main" width="40%">
										<b><?php echo  TABLE_HEADING_CURRENCY_NAME;?></b>
										</td>
										<td class="main" width="25%">
										<b><?php echo  TABLE_HEADING_CURRENCY_CODES;?></b>
										</td>
										<td class="main" width="25%">
										<b><?php echo  TABLE_HEADING_CURRENCY_VALUE;?></b>
										</td>
										<td class="main">&nbsp;
										
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<div align="center"><?php $this->doList();?></div>
							</td>
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
			function doEdit(){
				global $FREQUEST,$jsData,$currencies;
				$sh_country_id=$FREQUEST->getvalue("rID","int",0);
				$customers_info=array();
				$group_id=$sh_country_id;
				$customers_info_query=tep_db_query("select * from " . TABLE_CURRENCIES . " where currencies_id='" . (int)$sh_country_id . "' order by title");
				$query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key ='DEFAULT_CURRENCY'");
                $row = tep_db_fetch_array($query);
                if(tep_db_num_rows($customers_info_query)>0) $customers_info=tep_db_fetch_array($customers_info_query);
				 $cInfo=new objectInfo($customers_info);
			?>
				 
				<!--			started			-->		
				<table border="0" cellpadding="4" cellspacing="0" width="100%">
					<tr>
						<td valign="top">
							<form action="<?php echo FILENAME_SHOP_LANGUAGES_NEW; ?>" method="post" name="customer_groups" enctype="multipart/form-data" id="customer_groups">
								<input type="hidden" name="service_resource_id" value="<?php echo tep_output_string($service_resource_id);?>"/>
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									
									<tr>
										<td valign="top">
											<table border="0" cellpadding="0" cellspacing="0" width="100%" class="productEditCol">
												<tr id="productPanelGENERALview">
														<td colspan="2" class="main" align="center">
															<table border="0" cellpadding="4" cellspacing="0">
																<div class="hLineGray"></div>
																<tr> <td class="main"><div style=" font-weight:bold; padding-top:10px; width:100%;height:20px;overflow:hidden"><!--##HEAD_NAME##--></div></td>
																</tr>
																<tr>
																	<td style="padding-left:50px;">
																		<table border="0" cellpadding="4" cellspacing="0">
																			<tr>
																				<td class="main"><?php echo TEXT_INFO_CURRENCY_TITLE; ?></td>
																				<td class="main"><?php echo tep_draw_input_field('title',$cInfo->title,'size=30 maxlength=50').tep_draw_hidden_field('service_location_id',$location_id); ?></td>
																			</tr>
																			<tr>
																				<td class="main"><?php echo TEXT_INFO_CURRENCY_CODE; ?></td>
																				<td class="main"><?php echo tep_draw_input_field('code',$cInfo->code,'size=30 maxlength=3'); ?></td>
																			</tr>
																			<tr>
																				<td class="main"><?php echo TEXT_INFO_CURRENCY_SYMBOL_LEFT; ?></td>
																				<td class="main"><?php echo tep_draw_input_field('symbol_left',$cInfo->symbol_left,'size=30 maxlength=50'); ?></td>
																			</tr>
																			<tr>
																				<td class="main"><?php echo TEXT_INFO_CURRENCY_SYMBOL_RIGHT; ?></td>
																				<td class="main"><?php echo tep_draw_input_field('symbol_right',$cInfo->symbol_right,'size=30 maxlength=50'); ?></td>
																			</tr>
																		</table>
																	</td>
																	<td>
																		<table border="0" cellpadding="4" cellspacing="0">
																			<tr>
																				<td class="main"><?php echo TEXT_INFO_CURRENCY_DECIMAL_POINT.tep_draw_hidden_field('currencies_id',$sh_country_id); ?></td>
																				<td class="main"><?php echo tep_draw_input_field('decimal_point',$cInfo->decimal_point,'size=30 maxlength=1'); ?></td>
																			</tr>
																			<tr>
																				<td class="main"><?php echo TEXT_INFO_CURRENCY_THOUSANDS_POINT; ?></td>
																				<td class="main"><?php echo tep_draw_input_field('thousands_point',$cInfo->thousands_point,'size=30 maxlength=1'); ?></td>
																			</tr>
																			<tr>
																				<td class="main"><?php echo TEXT_INFO_CURRENCY_DECIMAL_PLACES; ?></td>
																				<td class="main"><?php echo tep_draw_input_field('decimal_places',$cInfo->decimal_places,'size=30 maxlength=50'); ?></td>
																			</tr>
																			<tr>
																				<td class="main"><?php echo TEXT_INFO_CURRENCY_VALUE; ?></td>
																				<td class="main"><?php echo tep_draw_input_field('value',$cInfo->value,'size=30 maxlength=50'); ?></td>
																			</tr>
																		</table>
																	</td>
																</tr>
																<?php if($cInfo->code!=$row['configuration_value']){ ?>
																<tr>
																<td class="main" style="padding-left:50px;">
																<input type="checkbox" name="set_default" id="set_default" value="" />&nbsp;
																Set as default (requires a manual update of currency values)
																</td>
																<td>&nbsp;
																
																</td>
																</tr>
																<?php } ?>
																<tr>
																<tr>
																<td height="20" colspan="2">&nbsp;</td>
																</tr>
																<td>
												
																</td>
															</tr>
														</table>
													</td>
												</tr>
										</table>
									</form>
								</td>
							</tr>
						</table>
				<!--			ended			-->
<?php					echo tep_draw_hidden_field('country_id',$sh_country_id);
				//		echo mergeTemplate($rep_array,$template);
						
					//	echo '</form>';
					$jsData->VARS["updateMenu"]=",update,";
					$display_mode_html=' style="display:none"';
				 
					}	
			
			function doUpdate(){
				global $FREQUEST,$jsData,$currencies;
				$language_referal_id=$FREQUEST->postvalue("currencies_id","int",-1);
				$default_currency_array=array();
	
				$title=$FREQUEST->postvalue('title');
				$code=$FREQUEST->postvalue('code');
				$symbol_left=$FREQUEST->postvalue('symbol_left');
				$symbol_right=$FREQUEST->postvalue('symbol_right');
				$decimal_point=$FREQUEST->postvalue('decimal_point');
				$thousands_point=$FREQUEST->postvalue('thousands_point');
				$decimal_places=$FREQUEST->postvalue('decimal_places');
				$value=$FREQUEST->postvalue('value');
				$default_currency=$FREQUEST->postvalue('default_currency','string','');
                if($default_currency!='')
                {
                    $old_query = tep_db_query("select con.configuration_value,con.configuration_title,cu.currencies_id,cu.title from ".TABLE_CONFIGURATION." con, ". TABLE_CURRENCIES . " cu where configuration_key='DEFAULT_CURRENCY' and con.configuration_value = cu.code");
                    $old = tep_db_fetch_array($old_query);
                    $old_stored_id="cug" . $old['currencies_id'] . "name";
					$old_stored_name=$old['title'];
                    tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value='".$default_currency."' where configuration_key='DEFAULT_CURRENCY'");
                }

                $query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key ='DEFAULT_CURRENCY'");
                $row = tep_db_fetch_array($query);
               	if($row['configuration_value']==$default_currency){
					$currency_name=$title.'&nbsp;<span style="font-size:10">(default)</span>';
					$currency_class='default_currency';
				} else{
					$currency_name=$title;
					$currency_class='normal_currency';
				}

				 $title_div='<div id="'.$currency_class.'" class="'.$currency_class.'">'.$currency_name.'</div>';

				$sql_data = array(  'title'=>$title,
									'code'=>$code,
									'symbol_left'=>$symbol_left,
									'symbol_right'=>$symbol_right,
									'decimal_point'=>$decimal_point,
									'thousands_point'=>$thousands_point,
									'decimal_places'=>$decimal_places,
									'value'=>$value
								 );		
								 
				 $str_arr=array();
				 $str_arr=explode('.',$value);
				 $reduction_value='';
				 if($str_arr[1]==''){
				 $reduction_value=$str_arr[0].'.00 ';
				 } else{
				 $reduction_value=number_format($value,2,'.','');
				 }		
				if ($language_referal_id>0){
					tep_db_perform(TABLE_CURRENCIES, $sql_data, 'update', "currencies_id = '" .$language_referal_id . "'");
                    $jsData->VARS["replace"]=array($old_stored_id => $old_stored_name,$this->type. $language_referal_id . "name"=>$title_div,$this->type . $language_referal_id . "code"=>substr($code,0,3),$this->type. $language_referal_id . "discount"=>$reduction_value);
					$jsData->VARS["prevAction"]=array('id'=>$language_referal_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
					$this->doInfo($country_referal_id);
					$jsData->VARS["updateMenu"]=",normal,";
				} else {
					tep_db_perform(TABLE_CURRENCIES,$sql_data);
					$jsData->VARS["replace"]=array($this->type. $language_referal_id . "name"=>$title_div,$this->type . $language_referal_id . "code"=>substr($code,0,3),$this->type. $language_referal_id . "discount"=>$reduction_value);
                    $language_referal_id=tep_db_insert_id();
                    $this->doItems();
				}				
				if($default_currency!=''){
					    echo "@sep@".$this->type. $language_referal_id . "name"."@sep@".$title;
				}  else{
					echo "@sep@@sep@";
				}
			}
			
			function doUpdateCurrency(){
				global $FREQUEST,$jsData,$currencies,$messageStack;
				$language_referal_id=$FREQUEST->postvalue("currencies_id","int",-1);
				$default_currency_array=array();
						
				$server_used = CURRENCY_SERVER_PRIMARY;
		
				$currency_query = tep_db_query("select currencies_id, code, title from " . TABLE_CURRENCIES);
				while ($currency = tep_db_fetch_array($currency_query)) {
					  $quote_function = 'quote_' . CURRENCY_SERVER_PRIMARY . '_currency';
					  $rate = $quote_function($currency['code']);
					  if (empty($rate) && (tep_not_null(CURRENCY_SERVER_BACKUP))) {
						$messageStack->add_session(sprintf(WARNING_PRIMARY_SERVER_FAILED, CURRENCY_SERVER_PRIMARY, $currency['title'], $currency['code']), 'warning');
			
						$quote_function = 'quote_' . CURRENCY_SERVER_BACKUP . '_currency';
						$rate = $quote_function($currency['code']);
			
						$server_used = CURRENCY_SERVER_BACKUP;
					  }
			
					  if (tep_not_null($rate)) {
//						tep_db_query("update " . TABLE_CURRENCIES . " set value = '" . tep_db_input($rate) . "', last_updated ='" . tep_db_input($server_date) . "' where currencies_id = '" . (int)$currency['currencies_id'] . "'");
						tep_db_query("update " . TABLE_CURRENCIES . " set value = '" . tep_db_input($rate) . "' where currencies_id = '" . (int)$currency['currencies_id'] . "'");
						$messageStack->add_session(sprintf(TEXT_INFO_CURRENCY_UPDATED, $currency['title'], $currency['code'], $server_used), 'success');
					  } else {
						$messageStack->add_session(sprintf(ERROR_CURRENCY_INVALID, $currency['title'], $currency['code'], $server_used), 'error');
					  }
					}
				echo $this->doItems();
			}

			
			function doInfo($sh_country_id=0){
				global $FREQUEST,$jsData,$FSESSION,$currencies;
	
				if($sh_country_id <= 0)$sh_country_id=$FREQUEST->postvalue("currencies_id","int",0);
				if($sh_country_id <= 0)$sh_country_id=$FREQUEST->getvalue("rID","int",0);
				$customers_groups_query = tep_db_query("select * from " . TABLE_CURRENCIES . " where currencies_id='" . (int)$sh_country_id . "' order by title");
	
				if (tep_db_num_rows($customers_groups_query)>0){
					$customers_groups_result=tep_db_fetch_array($customers_groups_query);
					$template=getInfoTemplate($location_id);
				
					$rep_array=array(	"TYPE"=>$this->type,
										"ENT_EQUIPMENT"=>TEXT_INFO_CURRENCY_TITLE,
										"EQUIPMENT"=> $customers_groups_result["title"],
										"ENT_CONTACT"=>TEXT_INFO_CURRENCY_CODE,
										"CONTACT"=>$customers_groups_result["code"],
										"ENT_SEATS"=>TEXT_LOCATION_MAX_SEATS,
										"SEATS"=>$customers_groups_result["image"],
										
										"ID"=>$customers_groups_result["countries_id"],
										);
					
					echo mergeTemplate($rep_array,$template);
					
					$jsData->VARS["updateMenu"]=",normal,";
				}
				else {
					echo 'Err:' . TEXT_LOCATION_NOT_FOUND;
				}
				
				}			
		}
		function getListTemplate(){
			ob_start();
			getTemplateRowTop();	?>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
			<tr>
				<td>
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="15" id="cug##ID##bullet">##STATUS##</td>
						<td width="40%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##name">##NAME##</td>
						<td width="25%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##code">##CODE##</td>
						<td width="20%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##discount">##VALUE##</td>
						<td  width="15%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
							<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
							<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'UpdateCurrency','result':doCurrencyResults,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/update2.gif" title="Update"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteGroups','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							</span>
							<span id="##TYPE####ID##mupdate" style="display:none">
							<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':groupValidate,'uptForm':'customer_groups','customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
							</span>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
<?php	getTemplateRowBottom();
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	function getInfoTemplate(){
		ob_start();	?>
	<table border="0" cellpadding="0" cellspacing="0" width="50%">
			<tr>
				<td valign="top" width="20" ><div style="width:100%;height:100px;overflow:hidden"></div></td>
			<td valign="middle" width="80%">
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<div class="hLineGray"></div>
			<tr>
				<td valign="top" width="20%" align="left" nowrap="nowrap" class="main">##ENT_EQUIPMENT##</td><td width="5%">:</td>
				<td valign="top" width="25%" align="left" class="main">##EQUIPMENT##</td>
			</tr>
			<tr>
				<td valign="top" width="20%" align="left" class="main">##ENT_CONTACT##</td><td width="5%">:</td>
				<td valign="top" width="10%"  align="left" class="main">##CONTACT##</td>
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
?>
<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License

*/
	defined('_FEXEC') or die();
	 
	class shopZones{
		var $pagination;
		var $splitResult;
		var $type;

		function __construct() {
			$this->pagination=false;
			$this->splitResult=false;
			$this->type='cfq';
		}
		
		function doCmsNewsDelete(){
			global $FREQUEST,$jsData;
			$zone_id=$FREQUEST->postvalue('news_id','int',0);
			$category_id=$FREQUEST->postvalue('category_id','int',0);
          
            if ($category_id>0 && $zone_id>0){

      				 $jsData->VARS['storePage']['opened']['cfq']=array("id"=> $category_id ,"get"=>"Info","result"=>"doDisplayResult","type"=>"cfq","params"=>"rID=$category_id","style"=>"boxLevel1");
                     

                }

			if ($zone_id>0){
				tep_db_query("delete from " . TABLE_ZONES . " where zone_id = '" . tep_db_input($zone_id) . "'");
				$this->doCmsNews($category_id);
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_NEWS_DELETED_SUCCESS);
				tep_reset_seo_cache('');
              
			} else {
				echo "Err:" . TEXT_CUSTOMER_GROUPS_NOT_DELETED;
			}
		}
			
		
		function doCmsNewsDeleteConfirm(){
			global $FREQUEST,$jsData;
			$country_id=$FREQUEST->getvalue('aID','int',0);
			$category_id=$FREQUEST->getvalue('cID','int',0);
			$news_id=$FREQUEST->getvalue('rID','int',0);
			$delete_message='<p><span class="smallText">' . TEXT_DELETE_INTRO . '</span>';	?>
			<form  name="prdDeleteSubmit" id="prdDeleteSubmit" action="cms_news.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="category_id" value="<?php echo tep_output_string($category_id);?>"/>
				<input type="hidden" name="news_id" value="<?php echo tep_output_string($news_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="prd<?php echo $country_id;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $news_id;?>,type:'prd',get:'CmsNewsDelete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'prdDeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $news_id;?>,type:'prd',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php echo $this->doCmsNewsInfo($news_id);?></td>
					</tr>
				</table>
			</form>
<?php		$jsData->VARS["updateMenu"]="";
		}
		function doCategoryEdit(){
			global $FREQUEST,$jsData,$LANGUAGES,$FSESSION;
			$category_id=$FREQUEST->getvalue("rID","int",0);
			$categories_query = tep_db_query("select countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, country_code, address_format_id from " . TABLE_COUNTRIES . " where countries_id='" . tep_db_input($category_id) . "' order by countries_name");
			if(tep_db_num_rows($categories_query)>0){
				$categories=tep_db_fetch_array($categories_query);
				$cInfo=new objectInfo($categories);
			}
			for ($i = 0, $n = sizeof($LANGUAGES); $i < $n; $i++)
				$contents.= '<td class="main" align="left" nowrap="true">'.tep_image(DIR_WS_CATALOG_LANGUAGES . $LANGUAGES[$i]['directory'] . '/images/' . $LANGUAGES[$i]['image'], $LANGUAGES[$i]['name']) . '&nbsp;<br>' . tep_draw_input_field('categories_name[' . $LANGUAGES[$i]['id'] . ']',$cInfo->categories_name,'maxlength="32"').'</td>';
			?>
			<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="Category" id="Category">
			<?php 
			 $rep_array=array(			"ENT_NAME"=>TEXT_CATEGORIES_NAME,
										"NAME"=>$contents,
										"TYPE"=>$this->type,
										"ENT_DESCRIPTION"=>TEXT_CATEGORIES_IMAGE.'('.((strlen($cInfo->categories_image)>20)?substr($cInfo->categories_image,0,20).'...':$cInfo->categories_image).')',
										"DESCRIPTION"=>'<div id="categories_image_container">'.tep_draw_file_field('categories_image_file').'</div>',
										"ID"=>$cInfo->geo_zone_id,
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"FIRST_MENU_DISPLAY"=>""
									);
				echo tep_draw_hidden_field('categories_image',$cInfo->categories_image);
				echo tep_draw_hidden_field('category_id',$cInfo->categories_id);
			?>
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%" class="productEditCol">
										<tr id="productPanelGENERALview">
												<td colspan="2" class="main" align="center">
													<table border="0" cellpadding="4" cellspacing="0" width="90%">
														<div class="hLineGray"></div>
														<tr> <td class="main"><div style=" font-weight:bold; padding-top:10px; width:100%;height:20px;overflow:hidden"><!--##HEAD_NAME##--></div></td>
														</tr>
														<tr>
															<td>
																<table border="0" cellpadding="4" cellspacing="0" width="100%">
																	<tr>
																		<td class="main" width="50%"><?php echo TEXT_COUNTRY_NAME; ?></td>
																		<td class="main" width="50%"><?php echo tep_draw_input_field('country_name',$cInfo->countries_name,'size=30 maxlength=50').tep_draw_hidden_field('country_id',$cInfo->countries_id); ?></td>
																	</tr>
																	<tr>
																		<td class="main" width="50%"><?php echo TEXT_COUNTRY_CODE; ?></td>
																		<td class="main" width="50%"><?php echo tep_draw_input_field('country_code',$cInfo->country_code,'size=30 maxlength=50'); ?></td>
																	</tr>
																</table>
															</td>
															<td>
																<table border="0" cellpadding="4" cellspacing="0" width="100%">
																	<tr>
																		<td class="main" width="50%"><?php echo TEXT_COUNTRY_ISO_CODE_TWO; ?></td>
																		<td class="main" width="50%"><?php echo tep_draw_input_field('countries_iso_code_2',$cInfo->countries_iso_code_2,'size=30 maxlength=2'); ?></td>
																	</tr>
																	<tr>
																		<td class="main" width="50%"><?php echo TEXT_COUNTRY_ISO_CODE_THREE; ?></td>
																		<td class="main" width="50%"><?php echo tep_draw_input_field('countries_iso_code_3',$cInfo->countries_iso_code_3,'size=30 maxlength=3'); ?></td>
																	</tr>
																</table>
															</td>
														</tr>
												</table>
											</td>
										</tr>
								</table>
                                <?php //if($FREQUEST->getvalue('rID')>0){?>
			<table border="0" cellpadding="1" cellspacing="0" width="100%">
				<tr>
					<td valign="top" style="border-top:solid 1px #C6CEEA;height:5px" class="smallText">&nbsp;</td>
				</tr>
				<tr>
					<td valign="top" class="categoryInfo">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td style="padding-right:10px">
								<table border="0" cellpadding="0" cellspacing="0" width="100%" class="boxLevel2">
									<tr height="4">
										<td class="topleft"></td>
										<td></td>
										<td class="topright"></td>
									</tr>
									<tr>
										<td width="4"></td>
										<td style="padding:5px">
											<div id="prdtotalContentResult">
											<?php
											echo $this->doCmsNews($category_id);
											?>
											</div>
										</td>
										<td width="4"></td>
									</tr>
									<tr height="4">
										<td class="botleft"></td>
										<td></td>
										<td class="botright"></td>
									</tr>
								</table>
								<td>
							</tr>
							<tr height="10">
								<td></td>
							</tr>
					</table>
					</td>
				</tr>
			</table>

			<?php
     //   }
                    echo '</form>';
					$jsData->VARS["updateMenu"]=",update,";
					$display_mode_html=' style="display:none"';
				 
		}
		
		function doCmsNewsEdit(){
			global $FSESSION,$FREQUEST,$jsData,$languages_id,$pInfo,$cID,$check_ID;
			if(!$cID) $cID=$check_ID;	
			$languages = tep_get_languages();	
			switch ($pInfo->product_status) {
				case '0': $in_status = false; $out_status = true; break;
				case '1':
				default: $in_status = true; $out_status = false;
			}	
			switch ($pInfo->product_sticky) {
				case '0': $sticky_on = false; $sticky_off = true; break;
				case '1': $sticky_on = true; $sticky_off = false; break;
				default: $sticky_on = false; $sticky_off = true;
			}	
			$panels=array('HEADLINE','SUMMARY','CONTENT','NEWSIMAGES');
			$default_panel='HEADLINE';
			$jsData->VARS['storePage']=array('instructorPanel'=>$default_panel);
			$jsData->VARS["updateMenu"]=",update,";
			$display_mode_html=' style="display:none"';
			
			$sh_country_id=$FREQUEST->getvalue("rID","int",0);
            $c_id=$FREQUEST->getvalue("cID","int",0);
            
           
			$customers_info=array();
			$gInfo=array();
			
			$group_id=$sh_country_id;
			$customers_info_query=tep_db_query("select zone_id, zone_name, zone_code, zone_country_id,placement from " . TABLE_ZONES . " where zone_id='" . (int)$FREQUEST->getvalue('rID'). "' order by zone_name");
			if(tep_db_num_rows($customers_info_query)>0) $customers_info=tep_db_fetch_array($customers_info_query);
			$cInfo=new objectInfo($customers_info); 
			echo tep_draw_form('new_product', FILENAME_SHOP_ZONES, '', 'post', 'enctype="multipart/form-data"');
			?>	
			<input type="hidden" name="category_id" id="category_id" value="<?php echo tep_output_string($category_id);?>" />
			<input type="hidden" name="news_id" id="news_id" value="<?php echo tep_output_string($news_id);?>" />
			<table border="0" width="100%" cellspacing="3" cellpadding="3">
			<tr>
			<td>
			<!--This is the zone info table-->
			<table border="0" cellpadding="4" cellspacing="0" width="100%">
				<tr>
					<td class="main"><?php echo TEXT_ZONE_NAME; ?></td>
					<td class="main"><?php echo TABLE_HEADING_ZONE_CODE; ?></td>
					<td class="main"><?php echo TEXT_COUNTRY_CODE; ?></td>
					<td class="main"><?php echo TEXT_DEFAULT_ZONE; ?></td>
				</tr>
				<tr>
					<td class="main"><?php echo tep_draw_input_field('zone_name',$cInfo->zone_name,'size=30 maxlength=50').tep_draw_hidden_field('zone_id',$cInfo->zone_id); ?></td>
					<td class="main"><?php echo tep_draw_input_field('zone_code',$cInfo->zone_code,'size=30 maxlength=50'); ?></td>
					<td class="main"><?php 
				//	echo tep_draw_input_field('zone_country_id',$cInfo->zone_country_id,'size=30 maxlength=50'); 
					$country_query=tep_db_query('select * from '.TABLE_COUNTRIES);
					$country_collection=array();
					while($country_arr=tep_db_fetch_array($country_query)){
						$country_collection[]=array('id'=>$country_arr['countries_id'],'text'=>$country_arr['countries_name']);
					}
					echo tep_draw_pull_down_menu('zone_country_id',$country_collection,$cInfo->zone_country_id);
                  
					echo tep_draw_hidden_field('ctry_id',$c_id);
					echo tep_draw_hidden_field('z_id',$sh_country_id);
                    
					?></td>
					<td><?php echo tep_draw_input_field('placement',$cInfo->placement,'size=3 maxlength=5'); ?>
				</td>
				</tr>
			</table>
			</td>
			</tr>
			</table>
			</form> 
			<?php 	$jsData->VARS['updateMenu']=',update,';
		}
		
		function doCmsNewsUpdate(){
			global $FREQUEST,$FSESSION,$jsData,$SERVER_DATE;

			$zone_referal_id=(int)$FREQUEST->getvalue('zone_id','int',0);
           
			$zone_name=tep_db_prepare_input($FREQUEST->postvalue('zone_name'));
			$zone_code=$FREQUEST->postvalue('zone_code');
			$placement=$FREQUEST->postvalue('placement');
			$zone_country_id=$FREQUEST->postvalue('zone_country_id');
            $ctry_id=(int)$FREQUEST->postvalue('ctry_id','int','0');
            $zne_id=(int)$FREQUEST->postvalue('z_id','int','0');
          
			$sql_data=array('zone_name'=>$zone_name,
							'zone_code'=>$zone_code,
							'placement'=>$placement,
							'zone_country_id'=>$ctry_id);
              if ($ctry_id>0 && $zne_id<0){
                     $jsData->VARS['storePage']['opened']['cfq']=array("id"=> $ctry_id ,"get"=>"Info","result"=>"doDisplayResult","type"=>"cfq","params"=>"rID=$ctry_id","style"=>"boxLevel1");
      				
      			}
			if($zne_id>0){
				tep_db_perform(TABLE_ZONES, $sql_data, 'update', "zone_id = '" .$zne_id . "'");
			} else{
				tep_db_perform(TABLE_ZONES,$sql_data);
				$group_id=tep_db_insert_id();
			}
			$languages = tep_get_languages();
			if($zne_id<0 && $ctry_id>0){
                
				$this->doCmsNews($ctry_id);
			} else{
               
				$jsData->VARS["replace"]=array('prd'. $zne_id . "name"=>$zone_name);
				$jsData->VARS["prevAction"]=array('id'=>$zne_id,'get'=>'doCmsNewsInfo','type'=>$this->type,'style'=>'boxRow');
				$this->doCmsNewsInfo($zone_referal_id);
				$jsData->VARS["updateMenu"]=",normal,";
			}
		}
		
		function doCmsNewsInfo($zone_id=0){
			global $FREQUEST,$jsData,$FSESSION;
			if($zone_id <= 0)$zone_id=$FREQUEST->getvalue("rID","int",0);
			if($zone_id <= 0)$zone_id=$FREQUEST->postvalue("zone_id","int",0);

			$zone_query = tep_db_query("select * from ".TABLE_ZONES." where zone_id='".$zone_id."'");

			if(tep_db_num_rows($zone_query)>0){
				$zone = tep_db_fetch_array($zone_query);
				$template=getZoneInfoTemplate($featured_id);
				$rep_array=array(	"TYPE"=>$this->type,
									"ID"=>$zone["zone_id"],
									"IMAGE_WIDTH"=>SMALL_IMAGE_WIDTH,
									"IMAGE"=>tep_product_small_image($zone["product_image"],$news["product_name"]),
									"DATE_ADDED"=>'<b>'.TEXT_INFO_ZONES_NAME.'</b>'.tep_draw_separator('pixel_trans.gif',9,20).$zone['zone_name'],
									'DATE_AVAILABLE'=>'<b>'.TEXT_INFO_ZONES_CODE.'</b>'.tep_draw_separator('pixel_trans.gif',9,20).$zone['zone_code']
									);
				echo mergeTemplate($rep_array,$template);
				$jsData->VARS["updateMenu"]=",normal,";
			} else{
				echo 'Err:' . TEXT_LOCATION_NOT_FOUND;
			}
		}
			
		function doCmsNewsList($where='',$category_id=0,$search=''){
			global $FSESSION,$FREQUEST,$jsData;
			$page=$FREQUEST->getvalue('page','int',1);
			$query_split=false;
			define('TEXT_RECORDS','Cms News');

			$products_sql = "select z.zone_id, c.countries_id, c.countries_name, z.zone_name, z.zone_code, z.zone_country_id from " . TABLE_ZONES . " z, " . TABLE_COUNTRIES . " c where z.zone_country_id = c.countries_id and c.countries_id='". (int)$category_id. "' order by c.countries_name, z.zone_name";

            $maxRows=$FSESSION->get('displayRowsCnt');
			if ($this->pagination && $maxRows!=-1){
				$query_split=$this->splitResult = (new instance)->getSplitResult('OPTIONS');
				$query_split->maxRows=$maxRows;
				$query_split->parse($page,$products_sql);
				if ($query_split->queryRows > 0){ 
					$query_split->pageLink="doPageAction({'id':-1,'type':'prd','pageNav':true,'closePrev':true,'get':'CmsNews','result':doTotalResult,params:'cID=". $category_id ."&page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_NEWS,'##PAGE_NO##') . "'})";
				}
			}
			$sql_query=tep_db_query($products_sql);
			
			$found=false;
			if (tep_db_num_rows($sql_query)>0) $found=true;
			if($found)
			{
			$template=getCmsNewsListTemplate();
			$icnt=1;
			while($sql_result=tep_db_fetch_array($sql_query)){
					$rep_array=array(	"ID"=>$sql_result["zone_id"],
										"CID"=>$category_id,
										"AID"=>$sql_result["zone_country_id"],
										"TYPE"=>'prd',
										"NAME"=>$sql_result["zone_name"],
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>'<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $sql_result["newsdesk_id"] .",get:'SetFlag',result:doSimpleResult,params:'pID=". $sql_result["newsdesk_id"] . "&status=" .($sql_result["product_status"]==1?0:1) . "'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>',
										"UPDATE_RESULT"=>'doDisplayResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'CmsNewsInfo',
										"FIRST_MENU_DISPLAY"=>""
									);
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}}
			/*
			if (!isset($jsData->VARS["Page"])){
				$jsData->VARS["NUclearType"][]='prd';
			} */
			if (!isset($jsData->VARS["page"])){
				$jsData->VARS["NUclearType"][]="prd";
			}

			return $found;			
		}
		function doCmsNews($category_id=0){
			global $FREQUEST,$jsData;
			if($category_id==0){
               
				if($FREQUEST->getValue('cID')) $category_id=$FREQUEST->getValue('cID','int',0);
			}

            
               
			$template=getCmsNewsListTemplate();
			$rep_array=array(	"CID"=>$category_id,
								"TYPE"=>"prd",
								"ID"=>-1,
								"NAME"=>TEXT_INFO_HEADING_NEW_ZONE,
								"IMAGE_PATH"=>DIR_WS_IMAGES,
								"SEARCH_NEEDED"=>"display:normal",
								"UPDATING_ORDER"=>TEXT_UPDATING_ORDER,
								"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
								"STATUS_STICKY"=>'',
								"UPDATE_RESULT"=>'doTotalResult',
								"ROW_CLICK_GET"=>'CmsNewsEdit',
								"FIRST_MENU_DISPLAY"=>"display:none",
								"EDIT_MENU_DISPLAY"=>"display:none",
								"FLAG_ONE_RECORD"=>''
							);	?>
			<div class="main" id="prd-1message"></div>
			<table border="0" width="100%" height="100%" id="prdTable">
				<tr>
					<td><?php 	echo mergeTemplate($rep_array,$template); ?></td>
				</tr>
				<tr>
					<td><?php $this->doCmsNewsList(" ",$category_id); ?></td>
				</tr>
			</table>
			<?php if ($this->splitResult && $this->splitResult->queryRows>0){ ?>
						<table border="0" width="100%" height="100%">
							<?php echo $this->splitResult->pgLinksCombo(); ?>
			</table><?php }
		}
		function doCategoryInfo($categories_id=0){
			global $FREQUEST,$jsData,$FSESSION;
		
			if($categories_id <= 0)$categories_id=$FREQUEST->getvalue("rID","int",0);
			$product_query=tep_db_query("select c.parent_id,date_format(c.date_added,'%Y-%m-%d') as date_added,date_format(c.last_modified,'%Y-%m-%d') as last_modified,cd.categories_id,cd.categories_name,c.sort_order,c.catagory_status,c.categories_image from ".TABLE_NEWSDESK_CATEGORIES." c,".TABLE_NEWSDESK_CATEGORIES_DESCRIPTION." cd where c.categories_id=cd.categories_id and cd.language_id='".(int)$FSESSION->languages_id."' and c.categories_id='".(int)$categories_id."'");
			
			if (tep_db_num_rows($product_query)>0){
				 $product = tep_db_fetch_array($product_query);
				 
				
			if($product['last_modified']=='0000-00-00'){
				$featured_date_data='<tr class="main"><td><b>'.TEXT_DATE_ADDED.'</b>' . tep_draw_separator('pixel_trans.gif',30,20) . format_date($product["date_added"]).'</td></tr>';
			} else{
				$featured_date_data='<tr class="main"><td><b>'.TEXT_DATE_ADDED.'</b>' . tep_draw_separator('pixel_trans.gif',30,20) . format_date($product["date_added"]).'</td></tr>'.'<tr class="main"><td><b>'.TEXT_LAST_MODIFIED.'</b>' . tep_draw_separator('pixel_trans.gif',18,20) . format_date($product["last_modified"]).'</td></tr>';
			}
			$template=getCategoryInfoTemplate($featured_id);
			$rep_array=array(	"TYPE"=>$this->type,
								"ID"=>$product["categories_id"],
								"IMAGE_WIDTH"=>SMALL_IMAGE_WIDTH,
								"IMAGE"=>tep_product_small_image($product["categories_image"],$product["categories_name"]),
								"DATE_ADDED"=>$featured_date_data,
								);
			echo mergeTemplate($rep_array,$template);
			$jsData->VARS["updateMenu"]=",normal,";
			} else{
				echo 'Err:' . TEXT_LOCATION_NOT_FOUND;
			}
		}
		
		function doInfo($category_id=0){
			global $FREQUEST,$FSESSION,$jsData;
			if ($category_id==0) $category_id=$FREQUEST->getvalue("rID","int",0);	?>
			<table border="0" cellpadding="1" cellspacing="0" width="100%">
				<tr>
					<td valign="top" style="border-top:solid 1px #C6CEEA;height:5px" class="smallText">&nbsp;</td>
				</tr>
				<tr>
					<td valign="top" class="categoryInfo">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr height="20">
								<td valign="top">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td class="bulletTitle" valign="middle">
											<?php echo tep_image(DIR_WS_IMAGES . 'layout/bullet1.gif','','','','align=absmiddle') . '&nbsp;' .HEADING_NEW_ZONES;?>
										</td>
										<td class="main" width="100">&nbsp;</td>
										<td width="20"></td>
									</tr>
								</table>
								</td>
							</tr>
							<tr>
								<td style="padding-right:10px">
								<table border="0" cellpadding="0" cellspacing="0" width="100%" class="boxLevel2">
									<tr height="4">
										<td class="topleft"></td>
										<td></td>
										<td class="topright"></td>
									</tr>
									<tr>
										<td width="4"></td>
										<td style="padding:5px">
											<div id="prdtotalContentResult">
											<?php echo $this->doCmsNews($category_id); ?>
											</div>
										</td>
										<td width="4"></td>
									</tr>
									<tr height="4">
										<td class="botleft"></td>
										<td></td>
										<td class="botright"></td>
									</tr>
								</table>
								<td>
							</tr>
							<tr height="10">
								<td></td>
							</tr>
					</table>
					</td>
				</tr>
			</table>
<?php
		}			
		
		function doDelete(){
			global $FREQUEST,$jsData;
			$categories_id=$FREQUEST->postvalue('category_id','int',0);
			
			if ($categories_id>0){
				tep_db_query("delete from " . TABLE_COUNTRIES . " where countries_id = '" . tep_db_input($categories_id) . "'");
				$this->doCategory();
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_CATEGORY_DELETED_SUCCESS);
				tep_reset_seo_cache('options');
			} else {
				echo "Err:" . TEXT_INSTRUCTOR_OPTIONS_NOT_DELETED;
			}
		}
		
		function doCategoryDelete(){
			global $FREQUEST,$jsData;
			$category_id=$FREQUEST->getvalue('rID','int',0);

			$delete_message='<p><span class="smallText">' . TEXT_INFO_DELETE_INTRO. '</span>'; ?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="cms_news.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="category_id" value="<?php echo tep_output_string($category_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $category_id;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $category_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $category_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php 
						/*
						$category_childs = newsdesk_childs_in_category_count($category_id);
						$category_products =newsdesk_products_in_category_count($category_id);
						if ($category_childs > 0) echo '<tr><td></td><td class="main">' . sprintf(TEXT_DELETE_WARNING_CHILDS, $category_childs).'</td></tr>';
						if ($category_products > 0) echo '<tr><td></td><td class="main">'. sprintf(TEXT_DELETE_WARNING_NEWSDESK, $category_products).'</td></tr>';
						*/
						// echo $this->doInfo($options_id);?></td>
					</tr>
				</table>
			</form>
<?php		$jsData->VARS["updateMenu"]="";
		}
		
		function doCategory(){
			global $FREQUEST,$jsData;
			
			$template=getCategoryListTemplate();
				$rep_array=array(	"TYPE"=>$this->type,
									"ID"=>-1,
									"BULLET_IMAGE"=>tep_image(DIR_WS_IMAGES . 'layout/bullet_close.gif'),
									"NAME"=>IMAGE_NEW_LOCATION,
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
									"UPDATE_RESULT"=>'doTotalResult',
									"ROW_CLICK_GET"=>'CategoryEdit',
									"FIRST_MENU_DISPLAY"=>"display:none"
								);

?>
			<div class="main" id="cfq-1message"></div>
			<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
				<?php 	echo mergeTemplate($rep_array,$template); ?>
			<!--</table>
			<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">-->
				<?php 	$this->doCategoryList();?>
			</table>
			<?php if (is_object($this->splitResult)){?>
				<table border="0" width="100%" height="100%">
						<?php echo $this->splitResult->pgLinksCombo(); ?>
				</table>
			<?php }
				
			 	
			}
		function doCategoryList($where='',$parent_id=0,$search='',$level=1){
			global $FSESSION,$FREQUEST,$jsData;
			$page=$FREQUEST->getvalue('page','int',1);

			$query_split=false;
		 	$categories_sql="select countries_id, countries_name, countries_iso_code_2, countries_iso_code_3,country_code, address_format_id from " . TABLE_COUNTRIES . " order by countries_name";	
			define('TEXT_RECORDS','tax zones');
			if ($this->pagination){
				$query_split=$this->splitResult = (new instance)->getSplitResult('OPTIONS');
				$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$categories_sql);
						if ($query_split->queryRows > 0){ 
								$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'Category','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_ZONES,'##PAGE_NO##') . "'})";
						}else {	
								$query_split->pageLink="doPageAction({'id':-1,'type':'cug','pageNav':true,'closePrev':true,'get':'Items','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_ZONES,'##PAGE_NO##') . "'})";
						}
			}

			$categories_query=tep_db_query($categories_sql);
			
			$found=false;
			if (tep_db_num_rows($categories_query)>0) $found=true;
			if($found){
			$template=getCategoryListTemplate();
			$icnt=1;
			
			$cnt=0;
			$pos=0;
			while($sql_result=tep_db_fetch_array($categories_query)){
							$rep_array=array(
										"BULLET_IMAGE"=>tep_image(DIR_WS_IMAGES . 'layout/bullet_close.gif'),
										"PAD_LEFT"=>$level*10,
										"CAT_PARENT"=>$parent_id,
										"ID"=>$sql_result["countries_id"],
										"TYPE"=>$this->type,
										"UPDATING_ORDER"=>TEXT_UPDATING_ORDER,
										"NAME"=>$sql_result["countries_name"].' ('.$sql_result["countries_id"].')',
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>'<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $sql_result["categories_id"] .",get:'SetFlag',result:doSimpleResult,params:'cID=". $sql_result["categories_id"] . "&status=" .($sql_result["catagory_status"]==1?0:1) . "'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>',
										"UPDATE_RESULT"=>'doTotalResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'Info',
										"FIRST_MENU_DISPLAY"=>""
									);
				echo mergeTemplate($rep_array,$template);
			}
			if (isset($jsData->VARS["page"])){
				$jsData->VARS["page"]["treeList"]["level" . $level]=$cnt;
				$jsData->VARS["page"]["treeList"][$parent_id]["totalchilds"]=$cnt;
				$jsData->VARS["page"]["treeList"][$parent_id]["childs"]=$pos;
			} else {
				$jsData->VARS["storePage"]["treeList"]["level" . $level]=$cnt;
				$jsData->VARS["storePage"]["treeList"][$parent_id]["totalchilds"]=$cnt;
				$jsData->VARS["storePage"]["treeList"][$parent_id]["childs"]=$pos;
			}
			}
			return $cnt;
		}
		function doCategoryUpdate(){
			global $FREQUEST,$jsData,$LANGUAGES,$SERVER_DATE;
			$category_id=$FREQUEST->postvalue("category_id","int",0);
			$categories_image=$FREQUEST->postvalue('categories_image');
			$insert=true;
			if ($category_id>0) $insert=false;
			//starts
			$country_referal_id=$FREQUEST->postvalue('country_id');
			$country_name=tep_db_prepare_input($FREQUEST->postvalue('country_name'));
			$country_code=$FREQUEST->postvalue('country_code');
			$countries_iso_code_2=$FREQUEST->postvalue('countries_iso_code_2');
			$countries_iso_code_3=$FREQUEST->postvalue('countries_iso_code_3');
						
			$field_customers_groups_discount=$customers_groups_discount_sign.$customers_groups_discount;
				$sql_data = array(  'countries_name'=>$country_name,
									'country_code'=>$country_code,
									'countries_iso_code_2'=>$countries_iso_code_2,
									'countries_iso_code_3'=>$countries_iso_code_3
								 );				
			
			if ($country_referal_id>0){
				tep_db_perform(TABLE_COUNTRIES, $sql_data, 'update', "countries_id = '" .$country_referal_id . "'");
			} else {
				tep_db_perform(TABLE_COUNTRIES,$sql_data);
				$group_id=tep_db_insert_id();
			}

			if ($insert){
				$this->doCategory();
			} else{
				$this->doInfo($category_id);
				$jsData->VARS["updateMenu"]=",normal,";
			}
		}
	}
		
		function getCategoryListTemplate(){
			ob_start();
			//getTemplateRowTop();?>		
			<tr id="##TYPE####ID##row">
				<td style="padding-left:##PAD_LEFT##px">
						<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##" class="boxLevel1" onmouseover="javascript:doMouseOverOut([{callFunc:changeItemRow,params:{element:this,'className':'boxLevel1','changeStyle':'Hover'}}]);" onmouseout="javascript:doMouseOverOut([{callFunc:changeItemRow,params:{element:this,'className':'boxLevel1'}}]);">
							<tr>
								<td class="head" valign="middle" height="25px">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr><td width="2%"></td>
										<td width="2%" id="##TYPE####ID##bullet2">##BULLET_IMAGE##</td>
										<td width="2%" id="##TYPE####ID##bullet">##STATUS##</td>
										<td width="30" align="center" class="boxRowMenu">&nbsp;</td>
										<td align="left" width="70%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxlevel1','type':'##TYPE##','params':'rID=##ID##','style':'boxLevel1'});" style="cursor:pointer;cursor:hand" id="##TYPE####ID##name">##NAME##</td>
										<td  width="20%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
											<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
											<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'CategoryEdit','result':doDisplayResult,'style':'boxlevel1','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
											<img src="##IMAGE_PATH##template/img_bar.gif"/>
											<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'CategoryDelete','result':doDisplayResult,'style':'boxlevel1','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
											</span>
											<span id="##TYPE####ID##mupdate" style="display:none">
											<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'CategoryUpdate','imgUpdate':true,'type':'##TYPE##','style':'boxlevel1','validate':CategoryValidate,'uptForm':'Category','result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
											<img src="##IMAGE_PATH##template/img_bar.gif"/>
											<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxlevel1'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
											</span>
										</td>
									</tr>
								</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
	<?php	//getTemplateRowBottom();
			$contents=ob_get_contents();
			ob_end_clean();
			return $contents;
		}
	
	function getCmsNewsListTemplate(){
		ob_start();
		getTemplateRowTop();	?>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
				<tr>
					<td>
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="15" id="##TYPE####ID##bullet">##STATUS##</td>
							<td align="left" width="75%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','cid':'##CID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&cID=##CID##&aID=##AID##'});" id="##TYPE####ID##name">##NAME##</td>
							<td  width="25%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
								<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
								<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'CmsNewsEdit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&cID=##CID##&aID=##AID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
								<img src="##IMAGE_PATH##template/img_bar.gif"/>
								<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'CmsNewsDeleteConfirm','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&cID=##CID##&aID=##AID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
								<img src="##IMAGE_PATH##template/img_bar.gif"/>
								</span>
								<span id="##TYPE####ID##mupdate" style="display:none">
								<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'CmsNewsUpdate','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':CmsNewsValidate,'uptForm':'new_product','result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img ty src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
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
		ob_start();
?>
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<div class="hLineGray"></div>
			<tr> <td class="main"><div style=" font-weight:bold; padding-top:10px; width:100%;height:20px;overflow:hidden"><!--##HEAD_NAME##--></div></td>
			
			<tr>
				<td width="10%" align="right" nowrap="nowrap" style="overflow:hidden;" class="main"><b>##ENT_NAME##</b></td>
				<td width="3%" align="left" style="overflow:hidden"  class="main">##NAME##</td>
				<td width="5%" align="right" style="overflow:hidden" class="main" nowrap="nowrap"><b>##ENT_DESCRIPTION##</b></td>
				<td width="10%"  align="left" style="overflow:hidden" class="main" nowrap="nowrap">##DESCRIPTION##</td>
			</tr>
		</table>
<?php
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	
	function getCategoryInfoTemplate(){
		ob_start();
?>
		<table border="0" cellpadding="0" cellspacing="0" width="50%">
			<tr>
				<td valign="top" width="##IMAGE_WIDTH##"><div style="width:100%;height:100px;overflow:hidden">##IMAGE##</div></td>
				<td width="10">&nbsp;</td>
				<td valign="top" width="250">
					<table border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr>
							<td class="main" >##DATE_ADDED##</td>
						</tr>
					</table>
				</td>
				
			</tr>
			<tr height="10">
				<td>&nbsp;</td>
			</tr>
		</table>
<?php	$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	
	function getZoneInfoTemplate(){
		ob_start();	?>
		<table border="0" cellpadding="0" cellspacing="0" width="50%">
			<tr>
				<td valign="top" width="##IMAGE_WIDTH##">&nbsp;</td>
				<td width="10">&nbsp;</td>
				<td valign="top" width="250">
					<table border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr>
							<td class="main" >##DATE_ADDED##</td>
						</tr>
						<tr>
							<td class="main" >##DATE_AVAILABLE##</td>
						</tr>
					</table>
				</td>
				
			</tr>
			<tr height="10">
				<td>&nbsp;</td>
			</tr>
		</table>
<?php	$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	} ?>
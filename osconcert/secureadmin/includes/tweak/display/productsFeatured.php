<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	defined('_FEXEC') or die();
	class productsFeatured
	{
		var $pagination;
		var $splitResult;
		var $type;

		function __construct() {
		$this->pagination=false;
		$this->splitResult=false;
		$this->type='pfd';
		}
		
		function doDelete(){
			global $FREQUEST,$jsData;
			$featured_id=$FREQUEST->postvalue('featured_id','int',0);
				if ($featured_id>0){
					tep_db_query("delete from " . TABLE_FEATURED . " where featured_id = '" . tep_db_input($featured_id) . "'");
					
					$this->doItems();
					$jsData->VARS["displayMessage"]=array('text'=>TEXT_FEATURED_DELETE_SUCCESS);
					tep_reset_seo_cache('featuredproduct');
					} else {
					echo "Err:" . TEXT_FEATURED_PRODUCT_NOT_DELETED;
				}
			
		}
		
		function doDeleteFeaturedProduct(){
			global $FREQUEST,$jsData;
			$featured_id=$FREQUEST->getvalue('pID','int',0);
			$delete_message='<p><span class="smallText">' . TEXT_DELETE_INTRO . '</span>';
?>
			<form name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="products_featured.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="featured_id" value="<?php echo tep_output_string($featured_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $featured_id;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $featured_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $featured_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php echo $this->doFeaturedInfo($featured_id);?></td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}
		function doProductsFeaturedList($products_id=0){
			global $FSESSION,$FREQUEST,$jsData;
			$page=$FREQUEST->getvalue('page','int',1);
			$query_split=false;
			$featured_query_raw = "select p.products_id, pd.products_name, s.featured_id, s.featured_date_added, s.featured_last_modified, s.expires_date, s.date_status_change, s.status from " . TABLE_PRODUCTS . " p, " . TABLE_FEATURED . " s, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' and p.products_id = s.products_id order by s.featured_id desc";
			
			if ($this->pagination){
				$query_split=$this->splitResult = (new instance)->getSplitResult('FEATURED');
				$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$featured_query_raw);
					if ($query_split->queryRows > 0){ 
							$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'Items','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_FEATURED,'##PAGE_NO##') . "'})";
						
					}
			}
			$featured_query = tep_db_query($featured_query_raw);
			$found=false;
			if (tep_db_num_rows($featured_query)>0) $found=true;
			$template=getListTemplate();
			$icnt=1;
			while($products_featured_result=tep_db_fetch_array($featured_query)){
			$rep_array=array(	"ID"=>$products_featured_result["featured_id"],
										"TYPE"=>'pfd',
										"STATUS"=>'<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $products_featured_result["featured_id"] .",get:'ProductChangeStatus',result:doSimpleResult,params:'pID=". $products_featured_result["featured_id"] . "&status=" .($products_featured_result["status"]==1?0:1) . "'});\">" . tep_image(DIR_WS_IMAGES . 'template/' . ($products_featured_result["status"]==1?'icon_active.gif':'icon_inactive.gif')) . '</a>',
										"PRODUCTS_NAME"=>$products_featured_result["products_name"],
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"UPDATE_RESULT"=>'doDisplayResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'FeaturedInfo',
										"FIRST_MENU_DISPLAY"=>""
										);
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}
			if (!isset($jsData->VARS["Page"])){
				$jsData->VARS["NUclearType"][]='pfd';
			} 
			return $found;			
		}
		
		function doItems(){
			global $FREQUEST,$jsData;
					
			$template=getListTemplate();
				$rep_array=array(	"TYPE"=>$this->type,
									"ID"=>-1,
									"PRODUCTS_NAME"=>HEADING_NEW_TITLE,
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
									"UPDATE_RESULT"=>'doTotalResult',
									"ROW_CLICK_GET"=>'FeaturedEdit',
									"FIRST_MENU_DISPLAY"=>"display:none"
								);

?>
			<div class="main" id="pfd-1message"></div>
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
											<td class="main">
												<b><?php echo  TABLE_HEADING_PRODUCTS;?></b>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<div align="center"><?php $this->doProductsFeaturedList();?></div>
								</td>
							</tr>	
						</table>
					</td>
				</tr>
			</table>
			<?php if (is_object($this->splitResult)){?>
				<table border="0" width="100%" height="100%">
						<?php echo $this->splitResult->pgLinksCombo(); ?>
				</table>
			<?php }
				
			 	
			}
			function doFeaturedEdit()
				{
				global $FREQUEST,$jsData,$SERVER_DATE,$FSESSION;
				$featured_id=$FREQUEST->getvalue("pID","int",0);
				$product=array();
				$product=array('products_name'=>'','expires_date'=>$SERVER_DATE);
			
				if($featured_id>0)
				{	
				$product_query = tep_db_query("select p.products_id, pd.products_name,s.expires_date,s.featured_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_FEATURED . " s where p.products_id = pd.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' and p.products_id = s.products_id and s.featured_id = '" . tep_db_input($featured_id) . "' order by pd.products_name");
				$product = tep_db_fetch_array($product_query);
				}  
				$sInfo = new objectInfo($product);  
				$featured_array = array();
				$featured_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p, " . TABLE_FEATURED . " s where s.products_id = p.products_id");
					while ($featured = tep_db_fetch_array($featured_query)) 
					{
						$featured_array[] = $featured['products_id'];
					}      
			    echo tep_draw_form('products_featured','products_featured.php', ' ' ,'post','id="products_featured"');
				echo tep_draw_hidden_field('featured_id',$sInfo->featured_id);
				
					?>
					<table border="0" cellpadding="4" cellspacing="0" width="100%">
		<div class="hLineGray"></div>
		<tr> 
		<td class="main">
		<div class="main" style=" font-weight:bold; padding-top:10px; width:100%;height:20px;overflow:hidden"><!--##HEAD_NAME##--></div>
		</td>
		<tr>
			<td valign="top" width="10%" align="left" ><div class="main" style="width:60%;height:30px;overflow:hidden"> <?php echo '<b>'.TEXT_FEATURED_PRODUCT.'</b>'.tep_draw_separator('pixel_trans.gif',35,20) .(($sInfo->products_name) ? $sInfo->products_name.tep_draw_hidden_field('products_id',$sInfo->products_id):tep_draw_products_featured_pull_down('products_id', 'style="font-size:10px"', $featured_array)) ?></div></td>
		</tr>
		<tr>	
			<td valign="top" width="20%"  align="left">
			<div  class="main" style="width:100%;height:30px;overflow:hidden">
			<?php 
			echo '<b>'.TEXT_FEATURED_EXPIRES_DATE.'</b>';
			echo tep_draw_input_field("expire_date",isset($sInfo->expires_date)?format_date($sInfo->expires_date):format_date($SERVER_DATE),'size=10' ); 
			$_array=array('d','m','Y');  
			$replace_array=array('DD','MM','YYYY'); 	
			$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);
			echo tep_create_calendar("products_featured.expire_date",$date_format);
			?></div>
			</td>
		</tr>
	</table>
					
			<?php 
			echo '</form>';
			$jsData->VARS["updateMenu"]=",update,";
			$display_mode_html=' style="display:none"';
			}	
			
			function doFeaturedProductUpdate()
			{
			global $FREQUEST,$jsData,$SERVER_DATE;
			$featured_id=$FREQUEST->postvalue("featured_id","int",-1);
			$expires_date = '';
			$expires_date = tep_convert_date_raw($FREQUEST->postvalue('expire_date','string','0000-00-00'));
  			$insert=true;
			if ($featured_id>0) $insert=false;
			if($insert)
				{			
				$sql_data = array(  'featured_date_added' => tep_db_input($SERVER_DATE),
										'products_id'=>$FREQUEST->postvalue('products_id'),
										'expires_date' =>tep_db_input($expires_date));	
				}
			else
				{
				$sql_data = array(  'featured_last_modified' => tep_db_input($SERVER_DATE),
									'products_id'=>$FREQUEST->postvalue('products_id'),
									'expires_date' =>tep_db_input($expires_date));
				
				}			
				
				if ($insert)
				{			
				tep_db_perform(TABLE_FEATURED,$sql_data);
				$featured_id=tep_db_insert_id();
				} else {
		
				tep_db_perform(TABLE_FEATURED, $sql_data, 'update', "featured_id = '" .$featured_id . "'");
				}
			if ($insert) 
			{
				$this->doItems();
			} else {
				$jsData->VARS["replace"]=array($this->type. $featured_id . "name"=>$expires_date);
				$jsData->VARS["prevAction"]=array('id'=>$featured_id,'get'=>'FeaturedInfo','type'=>$this->type,'style'=>'boxRow');
				$this->doFeaturedInfo($featured_id);
				$jsData->VARS["updateMenu"]=",normal,";
				}
				
			}
			function doProductChangeStatus()
			{
				global $FREQUEST,$jsData,$SERVER_DATE;
				$featured_id=$FREQUEST->getvalue("pID","int",0);
				$status=$FREQUEST->getvalue("status","int",0);
				if ($featured_id<=0) return;
				if ($status!=0 && $status!=1) $status=0;
					if ($status==1){
						tep_db_query("update " . TABLE_FEATURED . " set status = '1' where featured_id = '" . (int)$featured_id . "'");
						$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $featured_id .",get:\'ProductChangeStatus\',result:doSimpleResult,params:\'pID=". $featured_id . "&status=0\',message:\'".TEXT_UPDATING_STATUS."\'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
							} 
					else {
					tep_db_query("update " . TABLE_FEATURED . " set status = '0', date_status_change = '" . (int)$SERVER_DATE . "' where featured_id = '" . (int)$featured_id . "'");
						$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $featured_id .",get:\'ProductChangeStatus\',result:doSimpleResult,params:\'pID=". $featured_id . "&status=1\',message:\'".TEXT_UPDATING_STATUS."\'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
					}
				echo 'SUCCESS';
				$jsData->VARS["replace"]=array("pfd". $featured_id ."bullet"=>$result);
			}
			
			function doFeaturedInfo($featured_id=0)
			{
			global $FREQUEST,$jsData,$FSESSION;
			if($featured_id <= 0)$featured_id=$FREQUEST->getvalue("pID","int",0);
			$product_query = tep_db_query("select s.featured_id,pd.products_name,date_format(s.featured_date_added,'%Y-%m-%d') as featured_date_added,date_format(s.featured_last_modified,'%Y-%m-%d') as featured_last_modified,p.products_image_1,p.products_title_1,date_format(s.expires_date,'%Y-%m-%d') as expires_date,date_format(s.date_status_change,'%Y-%m-%d') as date_status_change,s.status  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_FEATURED . " s where p.products_id = pd.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' and p.products_id = s.products_id and s.featured_id = '" . tep_db_input($featured_id) . "'");		 
			
			if (tep_db_num_rows($product_query)>0){
				 $featured = tep_db_fetch_array($product_query);
			if($featured['featured_last_modified']=='0000-00-00')
			{
			$featured_date_data='<b>'.TEXT_INFO_DATE_ADDED.'</b> ' . format_date($featured["featured_date_added"]).'';
			}
			else
			{
			$featured_date_data='<b>'.TEXT_INFO_DATE_ADDED.'</b> ' . format_date($featured["featured_date_added"]).'<br><br>'.'<b>'.TEXT_INFO_LAST_MODIFIED.'</b> ' . format_date($featured["featured_last_modified"]).'';
			}
			$template=getFeaturedInfoTemplate($featured_id);
			$rep_array=array(	"TYPE"=>$this->type,
									"ID"=>$featured["featured_id"],
									"IMAGE_WIDTH"=>SMALL_IMAGE_WIDTH,
									"IMAGE"=>tep_product_small_image($featured["products_image_1"],$featured["products_title_1"]),
									"DATE_ADDED"=>$featured_date_data,
									"DATE_EXPIRY"=>'<b>'.TEXT_INFO_EXPIRES_DATE .'</b> ' . ($featured["expires_date"]=='' || $featured["expires_date"]=="0000-00-00"?format_date($featured["expires_date"]):format_date($featured["expires_date"])).''
									);
			echo mergeTemplate($rep_array,$template);
			$jsData->VARS["updateMenu"]=",normal,";
			}
			else {
				echo 'Err:' . TEXT_LOCATION_NOT_FOUND;
			}
			
			}			
	}
		
		function getListTemplate()
		{
		ob_start();
		getTemplateRowTop();
?>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" id="pfd##ID##">
						<tr>
							<td>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td width="0" id="pfd##ID##sort" class="boxRowMenu">
										<span style="##FIRST_MENU_DISPLAY##"></span>
									</td>
									<td width="15" id="pfd##ID##bullet">##STATUS##</td>
									<td class="main" onclick="javascript:doDisplayAction({'id':##ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'pfd','params':'pID=##ID##'});" id="pfd##ID##title">##PRODUCTS_NAME##</td>
									<td id="pfd##ID##menu" align="right" class="boxRowMenu">
										<span id="pfd##ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'FeaturedEdit','result':doDisplayResult,'style':'boxRow','type':'pfd','params':'pID=##ID##','backupMenu':true});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'DeleteFeaturedProduct','result':doDisplayResult,'style':'boxRow','type':'pfd','params':'pID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										</span>
										<span id="pfd##ID##mupdate" style="display:none">
										<a href="javascript:void(0)" onclick="javascript:return doUpdateAction({'id':##ID##,'get':'FeaturedProductUpdate','imgUpdate':true,'type':'pfd','style':'boxRow','validate':FeaturedProductValidate,'uptForm':'products_featured','customUpdate':doFeaturedProductUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onclick="javascript:return doCancelAction({'id':##ID##,'get':'ProductEdit','type':'pfd','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
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
	function getFeaturedInfoTemplate(){
		ob_start();
?>
		<table border="0" cellpadding="0" cellspacing="0" width="50%">
			<tr>
		<!--	<td valign="top" width="30"> </td>-->
				<td valign="top" width="##IMAGE_WIDTH##"><div style="width:100%;height:100px;overflow:hidden">##IMAGE##</div></td>
				<td width="10">&nbsp;</td>
				<td valign="top" width="250">
					<table border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr>
							<td class="main" >##DATE_ADDED##</td>
						</tr>
						<tr>
							<td class="main" >##DATE_EXPIRY##</td>
						</tr>				
					</table>
				</td>
				
			</tr>
			<tr height="10">
				<td>&nbsp;</td>
			</tr>
		</table>
<?php
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
?>
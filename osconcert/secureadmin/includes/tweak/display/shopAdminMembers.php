<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License

*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
	
	
	 
	class shopAdminMembers
		{
		var $pagination;
		var $splitResult;
		var $type;

		function __construct() {
		$this->pagination=false;
		$this->splitResult=false;
		$this->type='cfq';
		}
		
		// function doCategorySort()
		// {
			// global $FREQUEST,$jsData;
			// $mode=$FREQUEST->getvalue("mode","string","down");
			// $category_id=$FREQUEST->getvalue("cID","int",0);
			// $parent_id=$FREQUEST->getvalue("parent","int",0);
			
			// $category_query=tep_db_query("SELECT sort_order from " . TABLE_NEWSDESK_CATEGORIES . " where categories_id=$category_id and parent_id=$parent_id");
			// if (tep_db_num_rows($category_query)<=0){
				// echo "Err:"  . TEXT_CATEGORY_NOT_FOUND;
				// return;
			// }
			// $category_result=tep_db_fetch_array($category_query);
			// $current_order=(int)$category_result["sort_order"];

			// if ($mode=="up") {
			 	// $category_sort_query=tep_db_query("select sort_order, categories_id from ".TABLE_NEWSDESK_CATEGORIES." where parent_id=$parent_id and sort_order<$current_order order by sort_order desc limit 1");
			// } else {
				// $category_sort_query=tep_db_query("select sort_order, categories_id from ".TABLE_NEWSDESK_CATEGORIES." where parent_id=$parent_id and sort_order>$current_order order by sort_order limit 1");
			// }
			// if(tep_db_num_rows($category_sort_query)<=0){
				// echo "NOTRUNNED";
				// return;
			// }
			// $categories_result=tep_db_fetch_array($category_sort_query);
			// $prev_order=$categories_result['sort_order'];
			// tep_db_query("UPDATE " . TABLE_NEWSDESK_CATEGORIES . " set sort_order='" . $current_order ."' where categories_id='" . (int)$categories_result['categories_id'] . "'");
			// tep_db_query("UPDATE " .TABLE_NEWSDESK_CATEGORIES. " set sort_order='" . $prev_order . "' where categories_id=$category_id");
			// echo "SUCCESS";
			// $jsData->VARS['moveRows']=array('mode'=>$mode,'destID'=>$categories_result['categories_id']);
		// }
		
		
		// function doProductCopy()
		// {
		// global $FREQUEST,$jsData;
		
		// $cID=$FREQUEST->postvalue('cID','int',0);
		// $pID=$FREQUEST->postvalue('news_id','int',0);
		
		// $categories_id=$FREQUEST->postvalue('categories_id');
			// //$result="copy_details";
			// if ($pID>0) {				
				// $categories_id = tep_db_prepare_input($categories_id);							
					// if ($FREQUEST->postvalue('copy_as') == 'link') {
						// if ($categories_id != $cID) {
								// $check_query = tep_db_query("select count(*) as total from " . TABLE_NEWSDESK_TO_CATEGORIES . " where newsdesk_id = '" . tep_db_input($pID) . "' and categories_id = '" . tep_db_input($categories_id) . "'");
								// $check = tep_db_fetch_array($check_query);
								// if ($check['total'] < '1') 
									// tep_db_query("insert into " .TABLE_NEWSDESK_TO_CATEGORIES . " (newsdesk_id, categories_id) values ('" . tep_db_input($pID) . "', '" . tep_db_input($categories_id) . "')");
							// //$result.='copy_success';														
						// } else $result.=ERROR_CANNOT_LINK_TO_SAME_CATEGORY;					
					// } elseif ($FREQUEST->postvalue('copy_as') == 'duplicate') {			
						// $product_query = tep_db_query("select newsdesk_image, newsdesk_image_two, newsdesk_image_three, newsdesk_date_added, newsdesk_date_available,newsdesk_status, newsdesk_sticky 
													   // from " . TABLE_NEWSDESK . " where newsdesk_id = '" . tep_db_input($pID) . "'");
						// $product = tep_db_fetch_array($product_query);						
						// tep_db_query("insert into " . TABLE_NEWSDESK . " (newsdesk_image, newsdesk_image_two, newsdesk_image_three, newsdesk_date_added, newsdesk_date_available, 
									// newsdesk_status, newsdesk_sticky) values ('" . $product[newsdesk_image] . "','" . $product[newsdesk_image_two] . "',
									 // '" . $product[newsdesk_image_three] . "', '" . $product[newsdesk_date_added]  . "', '" . $product[newsdesk_date_available] . "', 
									 // '" . $product[newsdesk_status] . "', '" . $product[newsdesk_sticky] . "')");
						// $dup_product_id = tep_db_insert_id();
						// $description_query = tep_db_query("select language_id, newsdesk_article_name, newsdesk_article_description, newsdesk_article_url, newsdesk_image_text, newsdesk_image_text_two, 
														   // newsdesk_image_text_three, newsdesk_article_viewed, newsdesk_article_shorttext from " . TABLE_NEWSDESK_DESCRIPTION . " where newsdesk_id = '" . 
														   // tep_db_input($pID) . "'");
						
						// while ($description = tep_db_fetch_array($description_query)) {
										// tep_db_query("insert into " . TABLE_NEWSDESK_DESCRIPTION . " (newsdesk_id, language_id, newsdesk_article_name, 
													// newsdesk_article_description, newsdesk_article_url, newsdesk_image_text, newsdesk_image_text_two, newsdesk_image_text_three, 
													// newsdesk_article_viewed, newsdesk_article_shorttext) values ('" . $dup_product_id . "', '" . $description['language_id'] . "', '" 
													// . addslashes($description[newsdesk_article_name]) . "', '" . addslashes($description[newsdesk_article_description]) . "', 
													// '" . $description[newsdesk_article_url] . "', '" . $description[newsdesk_image_text] . "', '" . $description[newsdesk_image_text_two] . "', 
													// '" . $description[newsdesk_image_text_three] . "', '" . $description[newsdesk_article_viewed] . "', 
													// '" . $description[newsdesk_article_shorttext] . "')");
						// }				
						// tep_db_query("insert into " . TABLE_NEWSDESK_TO_CATEGORIES . " (newsdesk_id, categories_id) values ('" . $dup_product_id . "', '" . tep_db_input($categories_id) . "')");
						// $pID = $dup_product_id;
						// //$result.='copy_success';
					// }	
					
					// $this->doCmsNews($cID);
				// $jsData->VARS["displayMessage"]=array('text'=>TEXT_COPY_NEWS_SUCCESS);
				// tep_reset_seo_cache('options');		
			// }  // top closing if bracket
			// //echo $result;
		// }
		
		
		// function doProductCopyDisplay(){
			// global $FSESSION,$jsData,$FREQUEST;
			// $category_id=$FREQUEST->getvalue('cID','int',0);
			// $news_id=$FREQUEST->getvalue('rID','int',0);
			
			// $current_categories=newsdesk_output_generated_category_path($news_id, 'product');
			// $current_categories_tree=newsdesk_get_category_tree();


			//$jsData->VARS["updateMenu"]="";
		//}
		
			
		function doCmsNewsMove()
		{
		global $FREQUEST,$jsData;
		
		$news_id=$FREQUEST->postvalue('admin_id','int',0);
		$category_id=$FREQUEST->postvalue('admin_groups_id','int',0);

		if ($category_id>0){

      				$jsData->VARS["doFunc"]=array('type'=>'display','data'=>'{"id":"' . $category_id . '","get":"Info","result":doDisplayResult,"type":"cfq","params":"rID=' . $category_id . '","style":"boxLevel1"}');
      			}
		$new_parent_id = tep_db_prepare_input($FREQUEST->postvalue('move_to_category_id'));
			
			$duplicate_check_query = tep_db_query("select count(*) as total from " .TABLE_ADMIN. " where admin_id = '" . tep_db_input($news_id) . "' and admin_groups_id = '" . tep_db_input($new_parent_id) . "'");
			$duplicate_check = tep_db_fetch_array($duplicate_check_query);
			if ($duplicate_check['total'] < 1) tep_db_query("update " .TABLE_ADMIN . " set admin_groups_id = '" . tep_db_input($new_parent_id) . "' where admin_id = '" . tep_db_input($news_id) . "' and admin_groups_id = '" . tep_db_input($category_id) . "'");
			
			$this->doCmsNews($category_id);
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_MOVE_NEWS_SUCCESS);
				tep_reset_seo_cache('options');
			
		
		}
		
		function doCmsNewsMoveDisplay(){
			global $FREQUEST,$jsData,$CAT_TREE,$FSESSION;
			$news_id=$FREQUEST->getvalue('rID','int',0);
			$category_id=$FREQUEST->getvalue('cID','int',0);
			$category_name=$FREQUEST->getvalue('name');
			
			$categories_output=newsdesk_output_generated_category_path($news_id,$category_id);					
			$categories_output_tree=newsdesk_get_category_tree();
			//if (count($CAT_TREE)<=0) $CAT_TREE=tep_get_category_tree('0', '',$category_id);
			//$category_name=tep_get_category_name($category_id,$FSESSION->languages_id);
?>
			<form action="javascript:void(0)" method="post" enctype="application/x-www-form-urlencoded" name="catMoveSubmit" id="catMoveSubmit">
			<input type="hidden" name="admin_id" value="<?php echo tep_output_string($news_id); ?>"/>
			<input type="hidden" name="admin_groups_id" value="<?php echo tep_output_string($category_id);?>" />
			<table width="100%"  border="0" cellspacing="5" cellpadding="5" style="padding-left:20px;">
				<tr>
					<td class="main" id="cat<?php echo $news_id;?>message"></td>
				</tr>
				<tr>
					<td class="main"><b><?php echo sprintf(TEXT_MOVE_CATEGORIES_INTRO, '');?></b></td>
				</tr>
				<tr>
					<td class="main"><?php echo TEXT_INFO_CURRENT_CATEGORIES .'&nbsp;&nbsp;<b>' . $categories_output;?></td>
				</tr>
				<tr>
					<td class="main" align="left"><?php echo TEXT_MOVE_TO. '&nbsp;' . tep_draw_pull_down_menu('move_to_category_id', newsdesk_get_category_tree('0', '', $category_id));?></td>
				</tr>
				<tr>
					<td class="main"><a href="javascript:void(0);" onclick="javascript:return doUpdateAction({id:<?php echo $news_id;?>,type:'prd',style:'boxRow','get':'CmsNewsMove','result':doTotalResult,uptForm:'catMoveSubmit','imgUpdate':false,message:page.template['CAT_MOVING']});"><?php echo tep_image_button('button_move.gif');?></a>&nbsp;<a href="javascript:void(0)" onClick="javascript:return doCancelAction({id:<?php echo $news_id;?>,type:'prd','get':'closeRow',style:'boxRow'});"><?php echo tep_image_button('button_cancel.gif');?></a></td>
				</tr>
			</table>
			</form>
<?php
		}
		
		
		
		function doCategoryMove()
		{
		global $FREQUEST,$jsData,$SERVER_DATE;
		
		$cID=$FREQUEST->postvalue('category_id','int',0);
		
		
		if ($cID  && ($cID != $FREQUEST->postvalue('move_to_category_id'))){				
				$new_parent_id = tep_db_prepare_input($FREQUEST->postvalue('move_to_category_id'));
				
				tep_db_query("update " . TABLE_NEWSDESK_CATEGORIES. " set parent_id = '" . tep_db_input($new_parent_id) . "', last_modified = '" . $SERVER_DATE . "' where categories_id = '" . tep_db_input($cID) . "'");
				$this->doCategory();
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_MOVE_CATEGORY_SUCCESS);
				tep_reset_seo_cache('options');
				
			}
		
		}
		
		function doCategoryMoveDisplay(){
			global $FREQUEST,$jsData,$CAT_TREE,$FSESSION;
			$category_id=$FREQUEST->getvalue('rID','int',0);
			$category_name=$FREQUEST->getvalue('name');
			//if (count($CAT_TREE)<=0) $CAT_TREE=tep_get_category_tree('0', '',$category_id);
			//$category_name=tep_get_category_name($category_id,$FSESSION->languages_id);
?>
			<form action="javascript:void(0)" method="post" enctype="application/x-www-form-urlencoded" name="catMoveSubmit" id="catMoveSubmit">
			<input type="hidden" name="category_id" value="<?php echo $category_id; ?>"/>
			<table width="100%"  border="0" cellspacing="5" cellpadding="5" style="padding-left:20px;">
				<tr>
					<td class="main" id="cat<?php echo $category_id;?>message"></td>
				</tr>
				<tr>
					<td class="main"><b><?php echo sprintf(TEXT_MOVE_CATEGORIES_INTRO, $category_name);?></b></td>
				</tr>
				<tr>
					<td class="main" align="left"><?php echo sprintf(TEXT_MOVE, $category_name) . '&nbsp;' . tep_draw_pull_down_menu('move_to_category_id', newsdesk_get_category_tree('0', '', $category_id));?></td>
				</tr>
				<tr>
					<td class="main"><a href="javascript:void(0);" onclick="javascript:return doUpdateAction({id:<?php echo $category_id;?>,type:'cfq',style:'boxRow','get':'CategoryMove','result':doTotalResult,uptForm:'catMoveSubmit','imgUpdate':false,message:page.template['CAT_MOVING']});"><?php echo tep_image_button('button_move.gif');?></a>&nbsp;<a href="javascript:void(0)" onClick="javascript:return doCancelAction({id:<?php echo $category_id;?>,type:'cfq','get':'closeRow',style:'boxRow'});"><?php echo tep_image_button('button_cancel.gif');?></a></td>
				</tr>
			</table>
			</form>
<?php
		}
		
		
		function doSetFlag()
		{
		global $FREQUEST,$jsData;
		
		$cID=$FREQUEST->getvalue('cID');
		$pID=$FREQUEST->getvalue('pID');
		 $flag=$FREQUEST->getvalue('status');	
		 if (($flag == '0') || ($flag == '1')) {
			if ($pID) {
				newsdesk_set_product_status($pID, $flag);
			
			$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $pID .",get:\'SetFlag\',result:doSimpleResult,params:\'pID=". $pID . "&status=".(($flag==0)?1:0)."\',message:\'".TEXT_UPDATING_STATUS."\'});\">" . (($flag==0)?tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif'):tep_image(DIR_WS_IMAGES . 'template/icon_active.gif')) . '</a>';
			$jsData->VARS["replace"]=array("prd". $pID ."bullet"=>$result);
			
				
			}			
			if ($cID) {
			newsdesk_set_categories_status($cID, $flag);
			$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $cID .",get:\'SetFlag\',result:doSimpleResult,params:\'cID=". $cID . "&status=".(($flag==0)?1:0)."\',message:\'".TEXT_UPDATING_STATUS."\'});\">" . (($flag==0)?tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif'):tep_image(DIR_WS_IMAGES . 'template/icon_active.gif')) . '</a>';
			$jsData->VARS["replace"]=array("cfq". $cID ."bullet"=>$result);
			}			
		}	
		
		}
		
		
		function doSearch(){
			global $FREQUEST,$jsData;
			$search=$FREQUEST->getvalue('search');
			$search_db=tep_db_input($search);
			?>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
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
						$found=$this->doCategoryList(" ( a.admin_firstname like'%".$search_db."%' || a.admin_lastname like'%".$search_db."%')",0,$search);
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
					<a href="javascript:void(0);" onClick="javascript:doSearch('reset');"><?php echo tep_image_button('button_reset.gif',IMAGE_RESET);?></a>
					</td>
				</tr>
			</table>
<?php
		$jsData->VARS["NUclearType"]=$this->type;
		}
		
		
		
		function doCmsNewsDelete()
			{
			global $FREQUEST,$jsData;
			$news_id=$FREQUEST->postvalue('news_id','int',0);
			$category_id=$FREQUEST->postvalue('category_id','int',0);
			if ($category_id>0){

      			
                     $jsData->VARS['storePage']['opened']['cfq']=array("id"=> $category_id ,"get"=>"Info","result"=>"doDisplayResult","type"=>"cfq","params"=>"rID=$category_id","style"=>"boxLevel1");


                }

				
				if ($news_id>0){
				tep_db_query("delete from " . TABLE_ADMIN . " where admin_id = '" . tep_db_input($news_id) . "' and admin_groups_id = '" . tep_db_input($category_id) . "'");
				
				$product_categories_query = tep_db_query("select count(*) as total from " . TABLE_ADMIN . " where admin_id = '" . tep_db_input($news_id) . "'");
				$product_categories = tep_db_fetch_array($product_categories_query);			
				if ($product_categories['total'] == '0') {
					newsdesk_remove_product($news_id);
				}
				$this->doCmsNews($category_id);
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_NEWS_DELETED_SUCCESS);
				tep_reset_seo_cache('');
				} else {
				echo "Err:" . TEXT_CUSTOMER_GROUPS_NOT_DELETED;
				}
			}
			
		
			function doCmsNewsDeleteConfirm()
			{
			global $FREQUEST,$jsData;
			//$country_id=$FREQUEST->getvalue('aID','int',0);
			$category_id=$FREQUEST->getvalue('cID','int',0);
			$news_id=$FREQUEST->getvalue('rID','int',0);
			$delete_message='<p><span class="smallText">' . TEXT_DELETE_PRODUCT_INTRO . '</span>';
?>
			<form  name="prdDeleteSubmit" id="prdDeleteSubmit" action="shop_admin_members.php" method="post" enctype="application/x-www-form-urlencoded">
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
<?php
			$jsData->VARS["updateMenu"]="";
		
		}
		
		
			
		function doCategoryEdit()
				{
					global $FREQUEST,$jsData;
					$zones_id=$FREQUEST->getvalue("rID","int",0);
					$zones_info=array();
				 	$zones_query = tep_db_query("select admin_groups_id, admin_groups_name from " . TABLE_ADMIN_GROUPS . " where admin_groups_id='".tep_db_input($zones_id)."' ");
				 		if(tep_db_num_rows($zones_query)>0) $zones_info=tep_db_fetch_array($zones_query);
				 	$cInfo=new objectInfo($zones_info);
				 	
					$template=getInfoTemplate($zones_id);?>
				<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="Category" id="Category">					 
					<?php
					$rep_array=array(			"ENT_NAME"=>TEXT_CATEGORIES_NAME,
												"NAME"=>tep_draw_input_field('admin_groups_name',$cInfo->admin_groups_name,'size=15 maxlength=30'),
												"TYPE"=>$this->type,
												//"ENT_DESCRIPTION"=> TABLE_HEADING_ZONE_DESCRIPTION,
											//	"DESCRIPTION"=>tep_draw_input_field('geo_zone_description',$cInfo->geo_zone_description,'size=15 maxlength=30'),
												"ID"=>$cInfo->admin_groups_id,
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"FIRST_MENU_DISPLAY"=>""
											);
						echo tep_draw_hidden_field('admin_groups_id',$cInfo->admin_groups_id);
						echo mergeTemplate($rep_array,$template);
						echo '</form>';
					$jsData->VARS["updateMenu"]=",update,";
					$display_mode_html=' style="display:none"';
				 
		}
		
		//t
		//y
		function doCountryEdit()
			{
				global $FREQUEST,$jsData;
				$admin_groups_id=$FREQUEST->getvalue("cID","int",0);
				$admin_id=$FREQUEST->getvalue("rID","int",0);
				

				$zones_country_info=array();
				$zones_country_query = tep_db_query("select a.admin_id, a.admin_firstname,a.admin_lastname,a.admin_groups_id, a.admin_email_address,a.admin_password, a.admin_created,a.admin_lognum,ag.admin_groups_name from " . TABLE_ADMIN . " a , " . TABLE_ADMIN_GROUPS . " ag where a.admin_groups_id = ag.admin_groups_id  and a.admin_id = " . tep_db_input($admin_id) . " and a.admin_groups_id ='".tep_db_input($admin_groups_id)."'");
				
					 if(tep_db_num_rows($zones_country_query)>0) 
					 {
					 $zones_country_info=tep_db_fetch_array($zones_country_query);

				 $cInfo=new objectInfo($zones_country_info);

				 $template=getInfoTemplate1($admin_id);
				 echo tep_draw_form('new_product', 'shop_admin_members.php', '', 'post', 'enctype="multipart/form-data"');
				// echo tep_draw_form('country_list','shop_admin_members.php', ' ' ,'post','');
					 $rep_array=array(			"ENT_NAME"=>TEXT_NAME,
												"NAME"=>$zones_country_info["admin_firstname"].'&nbsp;'.$zones_country_info["admin_lastname"],
												"ENT_EMAIL"=>TEXT_EMAIL,
												"EMAIL"=>$zones_country_info["admin_email_address"],
												"ENT_GROUP"=>TEXT_GROUP_LEVEL,
												"GROUP"=>$zones_country_info["admin_groups_name"],
												"ENT_ACCOUNT"=>TEXT_ACCOUNT,
												"ACCOUNT"=>$zones_country_info["admin_created"],
												"ENT_HIDE"=>TEXT_HIDE,
												"HIDE"=>($cInfo->admin_hide_backend=='N')?'No':'Yes',
												"ENT_LOG"=>TEXT_LOG_NUM,
												"LOG_NUM"=>$zones_country_info["admin_lognum"],
												"TYPE"=>$this->type,
												"ID"=>$cInfo->admin_groups_id,
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"FIRST_MENU_DISPLAY"=>""
											);
						echo tep_draw_hidden_field('admin_id',$cInfo->admin_id);
						echo tep_draw_hidden_field('admin_groups_id',$cInfo->admin_groups_id);
						//echo tep_draw_hidden_field('assoc_id',$assoc_id);
						echo mergeTemplate($rep_array,$template);
						echo '</form>';
					$jsData->VARS["updateMenu"]=",normal,";
					$display_mode_html=' style="display:none"';
					}
					else if(tep_db_num_rows($zones_country_query)<=0) 
					{
					 $template=getInfoTemplate1();
				 echo tep_draw_form('new_product', 'shop_admin_members.php', '', 'post', 'enctype="multipart/form-data"');
				// echo tep_draw_form('country_list','shop_admin_members.php', ' ' ,'post','');
					 $rep_array=array(			"ENT_NAME"=>TEXT_INFO_FIRSTNAME,
												"NAME"=>tep_draw_input_field('new_admin_firstname'),
												"ENT_EMAIL"=>TEXT_INFO_LASTNAME,
												"EMAIL"=>tep_draw_input_field('new_admin_lastname'),
												"ENT_GROUP"=>TEXT_EMAIL,
												"GROUP"=>tep_draw_input_field('new_admin_email_address'),
												"ENT_ACCOUNT"=>TEXT_HIDE,
												"ACCOUNT"=>tep_draw_checkbox_field('new_admin_hide','Y'),
												"TYPE"=>$this->type,
												"ID"=>$admin_groups_id,
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"FIRST_MENU_DISPLAY"=>""
											);
					
					echo tep_draw_hidden_field('admin_groups_id',$admin_groups_id);
					echo mergeTemplate($rep_array,$template);
						echo '</form>';
					$jsData->VARS["updateMenu"]=",update,";
					$display_mode_html=' style="display:none"';
					
					}
		
		
		
		
		}
		function doCmsNewsEdit()
				{
					global $FREQUEST,$jsData;
					$admin_id=$FREQUEST->getvalue("rID","int",0);
					$admin_groups_id=$FREQUEST->getvalue("cID","int",0);
					$check_email_query = tep_db_query("select admin_email_address from " . TABLE_ADMIN . "");
					while ($check_email = tep_db_fetch_array($check_email_query)) {
					$stored_email[] = $check_email['admin_email_address'];
					}
					$zones_info=array();
				 	$zones_query=tep_db_query("select a.admin_id, a.admin_firstname,a.admin_lastname,a.admin_groups_id,a.admin_hide_backend, a.admin_email_address,a.admin_password,date_format(a.admin_created,'%Y-%m-%d') as product_date_added,a.admin_lognum,ag.admin_groups_name from " . TABLE_ADMIN . " a , " . TABLE_ADMIN_GROUPS . " ag where a.admin_groups_id = ag.admin_groups_id  and a.admin_id = " . tep_db_input($admin_id) . " and a.admin_groups_id ='".tep_db_input($admin_groups_id)."'");
					
					
				 		if(tep_db_num_rows($zones_query)>0) $zones_info=tep_db_fetch_array($zones_query);
				 	$cInfo=new objectInfo($zones_info);
				 	
					$template=getCmsInfoTemplate1($zones_id);?>
				<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="new_product" id="new_product">					 
					
							
			<?php		
			$first_name='<tr class="main"><td nowrap="nowrap"><b>'.TEXT_INFO_FIRSTNAME.'</b>' .'</td><td nowrap="nowrap">'.tep_draw_input_field('admin_firstname',$cInfo->admin_firstname,'size=15 maxlength=30').'</td></tr>';
			$last_name='<tr class="main"><td nowrap="nowrap"><b>'.TEXT_INFO_LASTNAME.'</b>' . '</td><td nowrap="nowrap">' .tep_draw_input_field('admin_lastname',$cInfo->admin_lastname,'size=15 maxlength=30').'</td></tr>';
			$e_mail='<tr class="main"><td nowrap="nowrap"><b>'.TEXT_EMAIL.'</b>' . '</td><td nowrap="nowrap">'.tep_draw_input_field('admin_email_address',$cInfo->admin_email_address,'size=35 maxlength=95').'</td></tr>';
			$hide_back='<tr class="main"><td nowrap="nowrap"><b>'.TEXT_HIDE.'</b>' .'</td><td nowrap="nowrap">' . tep_draw_checkbox_field('admin_hide_backend','Y',($cInfo->admin_hide_backend=='Y')?' checked ':'').'</td></tr>';
			if(tep_db_num_rows($zones_query)>0)
			$pass_word='<tr class="main"><td nowrap="nowrap"><b>'.TEXT_PASS.'</b>' . '</td><td nowrap="nowrap">' .tep_draw_input_field('admin_password','*****','maxlength="40"  onKeyUp="javascript:do_pwd_check(this);" onfocus="this.value=\'\'"; onblur="if (this.value==\'\') this.value=\'*****\';"', false).'<span id = "pwd_str" style="font-color:red;font-size:14;display:none;" ></span>'.'</td></tr>';
					
											
				$rep_array=array(			    "ENT_FIRST"=>$first_name,
												"ENT_LAST"=>$last_name,
												"ENT_EMAIL"=>$e_mail,
												
												"ENT_HIDE"=>$hide_back,
												"ENT_PASS"=>$pass_word,
												"TYPE"=>$this->type,
												"ID"=>$cInfo->admin_groups_id,
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"FIRST_MENU_DISPLAY"=>""
											);
											
						
									for($j=0;$j<sizeof($stored_email);$j++) { ?>
									<input type="hidden" name="emails[]" value="<?php echo $stored_email[$j];?>" />
									<?php } 	
									if(tep_db_num_rows($zones_query)>0) {?>
									<input type="hidden" name="oper" value="edit" />
									<input type="hidden" name="presv" value=<?php echo  $cInfo->admin_email_address ?> />

									<?php 
									}else {?>
									<input type="hidden" name="oper" value="new" />
								<?php }
						echo tep_draw_hidden_field('rID',$admin_id);
							echo tep_draw_hidden_field('cID',$admin_groups_id);
						echo mergeTemplate($rep_array,$template);
						echo '</form>';
					$jsData->VARS["updateMenu"]=",update,";
					$display_mode_html=' style="display:none"';
				 
		}
		
			function doCmsNewsUpdate()
			{
			global $FREQUEST,$jsData;
			$admin_groups_id=$FREQUEST->postvalue('cID','int',0);
			$cID = tep_db_prepare_input($admin_groups_id);	
			$admin_id=$FREQUEST->postvalue('rID','int',0);
			$rID = tep_db_prepare_input($admin_id);
			$admin_firstname=$FREQUEST->postvalue('admin_firstname');
			$admin_lastname=$FREQUEST->postvalue('admin_lastname');
			$admin_email_address=$FREQUEST->postvalue('admin_email_address');
			$admin_hide_backend=(($FREQUEST->postvalue('admin_hide_backend')!='')?'Y':'N');
			$password=$FREQUEST->postvalue('admin_password');			
				if ($password!='*****' && $password!='') {
				$admin_password=tep_encrypt_password($password);
				
				}
				else
				{
				$makePassword =$this->randomize();
				$admin_password=tep_encrypt_password($makePassword);
				}
			$encription_style=(defined('ENCRYPTION_STYLE'))?ENCRYPTION_STYLE:'O';
			$insert=true;
			if ($admin_id>0) 
			{
			$insert=false;
			$sql_updat_data = array(  
								
								'admin_firstname' => tep_db_prepare_input($admin_firstname),
								'admin_lastname' => tep_db_prepare_input($admin_lastname),
								'admin_hide_backend' => tep_db_prepare_input($admin_hide_backend),
								'admin_email_address' => tep_db_prepare_input($admin_email_address),
								'admin_password' => $admin_password,
								
								'admin_modified' => 'now()' ,
								'encryption_style'=>$encription_style
									
								 );	
			
			}		
			if ($admin_id<=0) 
			{
			$insert=true;
			$sql_insert_data = array(  'admin_groups_id' => tep_db_prepare_input($admin_groups_id),
								
								'admin_firstname' => tep_db_prepare_input($admin_firstname),
								'admin_lastname' => tep_db_prepare_input($admin_lastname),
								'admin_hide_backend' => tep_db_prepare_input($admin_hide_backend),
								'admin_email_address' => tep_db_prepare_input($admin_email_address),
								'admin_password' => $admin_password,
								'admin_created' =>'now()',
								
								'encryption_style'=>$encription_style
									
								 );	
			
			}
              if ($admin_groups_id>0){
                     $jsData->VARS['storePage']['opened']['cfq']=array("id"=> $admin_groups_id ,"get"=>"Info","result"=>"doDisplayResult","type"=>"cfq","params"=>"rID=$admin_groups_id","style"=>"boxLevel1");
      				
      			}

           
			
			
				if ($insert){
					tep_db_perform(TABLE_ADMIN,$sql_insert_data);
					$admin_id=tep_db_insert_id();
				} else {
					tep_db_perform(TABLE_ADMIN, $sql_updat_data, 'update', "admin_id = '" .$admin_id . "' and admin_groups_id = '" .$admin_groups_id . "'");
				}
			
				  if ($insert) {
				$this->doCmsNews($cID);
			} else {
				$jsData->VARS["replace"]=array('prd'. $rID . "name"=>($admin_firstname." ".$admin_lastname));
				
				
				$this->doCmsNews($cID);
				$jsData->VARS["updateMenu"]=",normal,";
				}
			}
			function randomize() {
		$salt = "abchefghjkmnpqrstuvwxyz0123456789";
		srand((double)microtime()*1000000); 
		$i = 0;
		while ($i <= 7) {
			$num = rand() % 33;
			$tmp = substr($salt, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}

		
		function doCmsNewsInfo($admin_id=0)
		{
		
			global $FREQUEST,$jsData,$FSESSION;
			
		
			
			if($admin_id <= 0)$admin_id=$FREQUEST->getvalue("rID","int",0);
			$admin_groups_id=$FREQUEST->getvalue("cID","int",0);
			
			$news_query=tep_db_query("select a.admin_id, a.admin_firstname,a.admin_lastname,a.admin_groups_id,a.admin_hide_backend, a.admin_email_address,a.admin_password,date_format(a.admin_created,'%Y-%m-%d') as product_date_added,a.admin_lognum,ag.admin_groups_name from " . TABLE_ADMIN . " a , " . TABLE_ADMIN_GROUPS . " ag where a.admin_groups_id = ag.admin_groups_id  and a.admin_id = " . tep_db_input($admin_id) . " and a.admin_groups_id ='".tep_db_input($admin_groups_id)."'");
			if (tep_db_num_rows($news_query)>0){
				 $news = tep_db_fetch_array($news_query);
				 
				  $cInfo=new objectInfo($news);
				 
				
			
		
			 
			 $name='<tr class="main"><td nowrap="nowrap"><b>'.TEXT_NAME.'</b>' .'</td><td nowrap="nowrap">'.$news["admin_firstname"].'&nbsp;'.$news["admin_lastname"].'</td></tr>';
			$e_mail='<tr class="main"><td nowrap="nowrap"><b>'.TEXT_EMAIL.'</b>' .'</td><td nowrap="nowrap">'.$news["admin_email_address"].'</td></tr>';
			$group_level='<tr class="main"><td nowrap="nowrap"><b>'.TEXT_GROUP_LEVEL.'</b>'  .'</td><td nowrap="nowrap">' .$news["admin_groups_name"].'</td></tr>';
			$acc_created='<tr class="main"><td nowrap="nowrap"><b>'.TEXT_ACCOUNT.'</b>' . '</td><td nowrap="nowrap">' .format_date($news["product_date_added"]).'</td></tr>';
			$hide_back='<tr class="main"><td nowrap="nowrap"><b>'.TEXT_HIDE.'</b>'  .'</td><td nowrap="nowrap">' .(( $cInfo->admin_hide_backend=='N')?'No':'Yes').'</td></tr>';
			$log_num='<tr class="main"><td nowrap="nowrap"><b>'.TEXT_LOG_NUM.'</b>'  . '</td><td nowrap="nowrap">'.$news["admin_lognum"].'</td></tr>';
			 
			
			
			
			$template=getCmsInfoTemplate($featured_id);
			
		
			$rep_array=array(			        "ENT_NAME"=>$name,
												"ENT_EMAIL"=>$e_mail,
												"ENT_GROUP"=>$group_level,
												"ENT_ACCOUNT"=>$acc_created,
												"ENT_HIDE"=>$hide_back,
												"ENT_LOG"=>$log_num,
												"TYPE"=>$this->type,
												"ID"=>$cInfo->admin_groups_id,
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"FIRST_MENU_DISPLAY"=>""
											);
											
											
											
									
									
			echo mergeTemplate($rep_array,$template);
			$jsData->VARS["updateMenu"]=",normal,";
			}
			else {
				echo 'Err:' . TEXT_LOCATION_NOT_FOUND;
			}
			
			
		

		}
			function doCmsNewsList($where='',$category_id=0,$search='')
			{
			global $FSESSION,$FREQUEST,$jsData;
			$page=$FREQUEST->getvalue('page','int',1);
			$query_split=false;
			define('TEXT_RECORDS','Members');	
			$products_sql="select admin_groups_id,admin_id,admin_firstname,admin_lastname from ".TABLE_ADMIN." where admin_groups_id='".(int)$category_id."' order by admin_firstname";
			
					
			$maxRows=$FSESSION->get('displayRowsCnt');
			if ($this->pagination && $maxRows!=-1){
				$query_split=$this->splitResult = (new instance)->getSplitResult('OPTIONS');
				$query_split->maxRows=$maxRows;
				$query_split->parse($page,$products_sql);
						if ($query_split->queryRows > 0){ 
								$query_split->pageLink="doPageAction({'id':-1,'type':'prd','pageNav':true,'closePrev':true,'get':'CmsNews','result':doTotalResult,params:'cID=". (int)$category_id ."&page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_COUNTRY,'##PAGE_NO##') . "'})";
							
						}
			}
			$sql_query=tep_db_query($products_sql);
			
			$found=false;
			if (tep_db_num_rows($sql_query)>0) $found=true;
			if($found)
			{
			
			$icnt=1;
			while($sql_result=tep_db_fetch_array($sql_query)){
					if($sql_result["admin_id"]=='1' && $sql_result["admin_groups_id"]=='1' )
					$template=getCmsNewsListTemplate1();
					else
					$template=getCmsNewsListTemplate();
					$rep_array=array(	"ID"=>$sql_result["admin_id"],
										"CID"=>$category_id,
										"AID"=>$sql_result["admin_groups_id"],
										"TYPE"=>'prd',
										"NAME"=>$sql_result["admin_firstname"].'&nbsp;'.$sql_result["admin_lastname"],
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>tep_image(DIR_WS_IMAGES .'template/icon_active.gif'),
										"UPDATE_RESULT"=>'doDisplayResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'CmsNewsInfo',
										"FIRST_MENU_DISPLAY"=>""
									);
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}}
			
			if (!isset($jsData->VARS["Page"])){
				$jsData->VARS["NUclearType"][]='prd';
			} 
			return $found;			
		}
			function doCmsNews($category_id=0)
			{
			global $FREQUEST,$jsData;
			
			
				if ($category_id==0)
				{
				if($FREQUEST->getValue('cID'))
				$category_id=$FREQUEST->getValue('cID','int',0);
				}
				
			
			$template=getCmsNewsListTemplate();
			$rep_array=array(	"CID"=>$category_id,
								"TYPE"=>"prd",
								"ID"=>-1,
								"NAME"=>TEXT_NEW_NEWS,
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
							);
		?>
				<div class="main" id="prd-1message"></div>
				<table border="0" width="100%" height="100%" id="prdTable">
					<tr>
						<td><?php 	echo mergeTemplate($rep_array,$template); ?>
						</td>
					</tr>
					<tr>
						<td><?php 	$this->doCmsNewsList(" ",$category_id); ?>
						</td>
					</tr>
				</table>
		<?php
			if ($this->splitResult && $this->splitResult->queryRows>0){ ?>
					<table border="0" width="100%" height="100%">
						<?php echo $this->splitResult->pgLinksCombo(); ?>
				</table><?php 
			}
		
		
		}
		// function doCategoryInfo($categories_id=0)
		// {
		
			// global $FREQUEST,$jsData,$FSESSION;
			
		
			// if($categories_id <= 0)$categories_id=$FREQUEST->getvalue("rID","int",0);
			// //$product_query=tep_db_query("select c.parent_id,date_format(c.date_added,'%Y-%m-%d') as date_added,date_format(c.last_modified,'%Y-%m-%d') as last_modified,cd.categories_id,cd.categories_name,c.sort_order,c.catagory_status,c.categories_image from ".TABLE_NEWSDESK_CATEGORIES." c,".TABLE_NEWSDESK_CATEGORIES_DESCRIPTION." cd where c.categories_id=cd.categories_id and cd.language_id='".(int)$FSESSION->languages_id."' and c.categories_id='".(int)$categories_id."'");
			
			// if (tep_db_num_rows($product_query)>0){
				 // $product = tep_db_fetch_array($product_query);
				 
				
			// if($product['last_modified']=='0000-00-00')
			// {
			// $featured_date_data='<tr class="main"><td><b>'.TEXT_DATE_ADDED.'</b>' . tep_draw_separator('pixel_trans.gif',30,20) . format_date($product["date_added"]).'</td></tr>';
			// }
			// else
			// {
			 
			// $featured_date_data='<tr class="main"><td><b>'.TEXT_DATE_ADDED.'</b>' . tep_draw_separator('pixel_trans.gif',30,20) . format_date($product["date_added"]).'</td></tr>'.'<tr class="main"><td><b>'.TEXT_LAST_MODIFIED.'</b>' . tep_draw_separator('pixel_trans.gif',18,20) . format_date($product["last_modified"]).'</td></tr>';
			// }
			// $template=getCategoryInfoTemplate($featured_id);
			// $rep_array=array(	"TYPE"=>$this->type,
									// "ID"=>$product["categories_id"],
									// "IMAGE_WIDTH"=>SMALL_IMAGE_WIDTH,
									// "IMAGE"=>tep_product_small_image($product["categories_image"],$product["categories_name"]),
									// "DATE_ADDED"=>$featured_date_data,
									// );
			// echo mergeTemplate($rep_array,$template);
			// $jsData->VARS["updateMenu"]=",normal,";
			// }
			// else {
				// echo 'Err:' . TEXT_LOCATION_NOT_FOUND;
			// }
			
			
		// }
		
		function doInfo($category_id=0){
			global $FREQUEST,$FSESSION,$jsData;
			if ($category_id==0) $category_id=$FREQUEST->getvalue("rID","int",0);
?>
			<table border="0" cellpadding="1" cellspacing="0" width="100%">
				<tr>
					<td valign="top" style="border-top:solid 1px #C6CEEA;height:5px" class="smallText">&nbsp;</td>
				</tr>
				<tr>
					<td valign="top" class="categoryInfo">
						<?php 
							//echo $this->doCategoryInfo($category_id);
						?>
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr height="20">
								<td valign="top">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td class="bulletTitle" valign="middle">
											<?php echo tep_image(DIR_WS_IMAGES . 'layout/bullet1.gif','','','','align=absmiddle') . '&nbsp;' .HEADING_NEW_NEWS;?>
										</td>
										<td class="main" width="100">
										<?php 
											if ($this->pagination) {
												for ($icnt=MAX_DISPLAY_SEARCH_RESULTS,$n=MAX_DISPLAY_SEARCH_RESULTS*5;$icnt<=$n;$icnt+=MAX_DISPLAY_SEARCH_RESULTS){
													$pg_rows[]=array('id'=>$icnt,'text'=>$icnt);
												}
												$pg_rows[]=array('id'=>-1,'text'=>TEXT_ALL);
												echo TEXT_SHOW.'   :  ' . tep_draw_pull_down_menu('totalRows',$pg_rows,$FSESSION->displayRowsCnt,'onChange="javascript:doPageAction({id:'. $category_id . ',type:\'prd\',get:\'CmsNews\',closePrev:true,pageNav:true,result:doTotalResult,params:\'cID='. $category_id .'&rowsCnt=\'+this.value,message:page.template[\'INFO_LOADING_PRODUCTS\']});"');
											}
										?>
										</td>
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
		}		
		
			function doDelete(){
			global $FREQUEST,$jsData;
			$zones_id=$FREQUEST->postvalue('category_id','int',0);
			
			if ($zones_id>0){
				tep_db_query("delete from ".TABLE_ADMIN." where admin_groups_id='".$zones_id."'");
				tep_db_query("delete from " . TABLE_ADMIN_GROUPS . " where admin_groups_id = '" . (int)$zones_id . "'");
				$this->doCategory();
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_CATEGORY_DELETED_SUCCESS);
				tep_reset_seo_cache('options');
			} else {
				echo "Err:" . TEXT_INSTRUCTOR_OPTIONS_NOT_DELETED;
			}
			
		}	
		
		
			function doCategoryDelete(){
			global $FREQUEST,$jsData;
			$zones_id=$FREQUEST->getvalue('rID','int',0);

			$delete_message='<p><span class="smallText">' . TEXT_DELETE_CATEGORY_INTRO. '</span>';
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="shop_admin_members.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="category_id" value="<?php echo tep_output_string($zones_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $zones_id;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $zones_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $zones_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo">
						<?php
						$category_childs = newsdesk_childs_in_category_count($zones_id);
						if ($category_childs > 0) echo '<tr><td></td><td class="main">' . sprintf(TEXT_DELETE_WARNING_CHILDS, $category_childs).'</td></tr>';
						?>
						
					
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}
		
		
		
		function doCategory(){
			global $FREQUEST,$jsData;
			
			$template=getCategoryListTemplate();
				$rep_array=array(	"TYPE"=>$this->type,
									"ID"=>-1,
									"BULLET_IMAGE"=>tep_image(DIR_WS_IMAGES . 'layout/bullet_close.gif'),
									"NAME"=>HEADING_NEW_TITLE,
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
			
			
			
			if($search!='' && $where!='')
			 $categories_sql = "select  admin_groups_id,admin_groups_name from ".TABLE_ADMIN_GROUPS." where admin_groups_id in (select  distinct(a.admin_groups_id) from ".TABLE_ADMIN ." a ,".TABLE_ADMIN_GROUPS." ag  where  a.admin_firstname like '%".tep_db_input($search)."%' ||  a.admin_lastname like '%".tep_db_input($search)."%'  or a.admin_groups_id in (select distinct(admin_groups_id) from  ".TABLE_ADMIN_GROUPS . " where  admin_groups_name like '%".tep_db_input($search)."%')   group by a.admin_id)";
			
			else
				 $categories_sql = "select admin_groups_id,admin_groups_name from  " .TABLE_ADMIN_GROUPS ."  order by admin_groups_id";
			
			
			 
			
						define('TEXT_RECORDS','Groups');
			if ($this->pagination){
				$query_split=$this->splitResult = (new instance)->getSplitResult('OPTIONS');
				$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$zones_sql);
						if ($query_split->queryRows > 0){ 
								$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'Category','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_ZONES,'##PAGE_NO##') . "'})";
							
						}
			}
			$categories_query=tep_db_query($categories_sql);
			
			$found=false;
			if (tep_db_num_rows($categories_query)>0) $found=true;
			if($found)
			{
			
			 
			
			$icnt=1;
            $pos=0;
			
			
			
			while($sql_result=tep_db_fetch_array($categories_query)){
						$admin_grp=$sql_result["admin_groups_id"];
						
						
						if($admin_grp=='1' || $admin_grp=='2' || $admin_grp=='3' || $admin_grp=='4' || $admin_grp=='5'|| $admin_grp=='6'|| $admin_grp=='8'|| $admin_grp=='9') 
						$template=getCategoryListTemplate1();
						else
						$template=getCategoryListTemplate();
							$rep_array=array(
										"BULLET_IMAGE"=>tep_image(DIR_WS_IMAGES . 'layout/bullet_close.gif'),
										"PAD_LEFT"=>$level*10,
										"CAT_PARENT"=>$parent_id,
										"ID"=>$sql_result["admin_groups_id"],
										"TYPE"=>$this->type,
										"UPDATING_ORDER"=>TEXT_UPDATING_ORDER,
										"NAME"=>$sql_result["admin_groups_name"],
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/icon_active.gif'),
										"UPDATE_RESULT"=>'doDisplayResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'Info',
										"FIRST_MENU_DISPLAY"=>""
									);
									
			
				echo mergeTemplate($rep_array,$template);
				
				$icnt++;
				
			//
              /* if (isset($jsData->VARS["page"])){
        $jsData->VARS["page"]["treeList"][$mainpage_result["page_id"]]["pos"]=$pos;
        $jsData->VARS["page"]["treeList"][$mainpage_result["page_id"]]["parent"]=$parent_id;
        $jsData->VARS["page"]["treeList"][$mainpage_result["page_id"]]["level"]=$level;
    } else {
        $jsData->VARS["storePage"]["treeList"][$mainpage_result["page_id"]]["pos"]=$pos;
        $jsData->VARS["storePage"]["treeList"][$mainpage_result["page_id"]]["parent"]=$parent_id;
        $jsData->VARS["storePage"]["treeList"][$mainpage_result["page_id"]]["level"]=$level;
    }
    $pos++;
}
if (isset($jsData->VARS["page"])){
    $jsData->VARS["page"]["treeList"]["level" . $level]=$icnt;
    $jsData->VARS["page"]["treeList"][$parent_id]["totalchilds"]=$icnt;
    $jsData->VARS["page"]["treeList"][$parent_id]["childs"]=$pos;
} else {
    $jsData->VARS["storePage"]["treeList"]["level" . $level]=$icnt;
    $jsData->VARS["storePage"]["treeList"][$parent_id]["totalchilds"]=$icnt;
    $jsData->VARS["storePage"]["treeList"][$parent_id]["childs"]=$pos;
}
            
	return $icnt;
            
            
            }*/
			
            }

            }
			if (!isset($jsData->VARS["Page"])){
				$jsData->VARS["NUclearType"][]=$this->type;
			} 
			return $found;	
		}
		
		
		function doCategoryUpdate()
			{
			global $FREQUEST,$jsData;
			$zones_id=$FREQUEST->postvalue("admin_groups_id","int",-1);
			
			$insert=true;
			if ($zones_id>0) $insert=false;
													
			$geo_zone_name=$FREQUEST->postvalue('admin_groups_name');
			
			
			$sql_data = array(  'admin_groups_name' => tep_db_prepare_input($geo_zone_name),
									
								 );	
			
				if ($insert){
					tep_db_perform(TABLE_ADMIN_GROUPS,$sql_data);
					$zones_id=tep_db_insert_id();
				} else {
					tep_db_perform(TABLE_ADMIN_GROUPS, $sql_data, 'update', "admin_groups_id = '" .$zones_id . "'");
				}
			if ($insert) {
				
				$this->doCategory();
			} else {
				$jsData->VARS["replace"]=array($this->type. $zones_id . "name"=>$geo_zone_name);
				$jsData->VARS["prevAction"]=array('id'=>$zones_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
				$this->doInfo($zones_id);
				$jsData->VARS["updateMenu"]=",normal,";
				}
			}
			
		
		}
		
	function getCategoryListTemplate(){
		ob_start();
		
?>		<tr id="##TYPE####ID##row">
			<td style="padding-left:##PAD_LEFT##px">
					<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##" class="boxLevel1" onmouseover="javascript:doMouseOverOut([{callFunc:changeItemRow,params:{element:this,'className':'boxLevel1','changeStyle':'Hover'}}]);" onmouseout="javascript:doMouseOverOut([{callFunc:changeItemRow,params:{element:this,'className':'boxLevel1'}}]);">
						<tr>
							<td class="head" valign="middle" height="25px">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr><td width="2%"></td>
									<td width="2%" id="##TYPE####ID##bullet2">##BULLET_IMAGE##</td>
									<td width="2%" id="##TYPE####ID##bullet">##STATUS##</td>
									<td width="30" align="center" class="boxRowMenu">
										<!--<span style="##FIRST_MENU_DISPLAY##">
											<a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':##ID##,'get':'CategorySort','result':doSimpleResult,mode:'up',type:'cfq',params:'cID=##ID##&mode=up&parent=##CAT_PARENT##',validate:sortCatValidate,'style':'boxLevel1','message':'##UPDATING_ORDER##'})"><img src="##IMAGE_PATH##template/img_arrow_up.gif"  title="Up" align="absmiddle"/></a>
											<a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':##ID##,'get':'CategorySort','result':doSimpleResult,mode:'down',type:'cfq',params:'cID=##ID##&mode=down&parent=##CAT_PARENT##',validate:sortCatValidate,'style':'boxLevel1','message':'##UPDATING_ORDER##'})"><img src="##IMAGE_PATH##template/img_arrow_down.gif" title="Down"/></a>
										</span>-->
									</td>
									<td align="left" width="70%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxlevel1','type':'##TYPE##','params':'rID=##ID##','style':'boxLevel1'});" style="cursor:pointer;cursor:hand" id="##TYPE####ID##name">##NAME##</td>
									<td  width="20%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
										<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'CategoryEdit','result':doDisplayResult,'style':'boxlevel1','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'CategoryDelete','result':doDisplayResult,'style':'boxlevel1','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<!--<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'CategoryMoveDisplay','result':doDisplayResult,'style':'boxlevel1','type':'##TYPE##','params':'rID=##ID##&name=##NAME##'});"><img src="##IMAGE_PATH##template/img_move.gif" title="Move Category"/></a>-->
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
<?php
		
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
		function getCategoryListTemplate1(){
		ob_start();
		
?>		<tr id="##TYPE####ID##row">
			<td style="padding-left:##PAD_LEFT##px">
					<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##" class="boxLevel1" onmouseover="javascript:doMouseOverOut([{callFunc:changeItemRow,params:{element:this,'className':'boxLevel1','changeStyle':'Hover'}}]);" onmouseout="javascript:doMouseOverOut([{callFunc:changeItemRow,params:{element:this,'className':'boxLevel1'}}]);">
						<tr>
							<td class="head" valign="middle" height="25px">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr><td width="2%"></td>
									<td width="2%" id="##TYPE####ID##bullet2">##BULLET_IMAGE##</td>
									<td width="2%" id="##TYPE####ID##bullet">##STATUS##</td>
									<td width="30" align="center" class="boxRowMenu">
										<!--<span  style="##FIRST_MENU_DISPLAY##">
											<a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':##ID##,'get':'CategorySort','result':doSimpleResult,mode:'up',type:'cfq',params:'cID=##ID##&mode=up&parent=##CAT_PARENT##',validate:sortCatValidate,'style':'boxLevel1','message':'##UPDATING_ORDER##'})"><img src="##IMAGE_PATH##template/img_arrow_up.gif"  title="Up" align="absmiddle"/></a>
											<a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':##ID##,'get':'CategorySort','result':doSimpleResult,mode:'down',type:'cfq',params:'cID=##ID##&mode=down&parent=##CAT_PARENT##',validate:sortCatValidate,'style':'boxLevel1','message':'##UPDATING_ORDER##'})"><img src="##IMAGE_PATH##template/img_arrow_down.gif" title="Down"/></a>
										</span>-->
									</td>
									<td align="left" width="70%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxlevel1','type':'##TYPE##','params':'rID=##ID##','style':'boxLevel1'});" style="cursor:pointer;cursor:hand" id="##TYPE####ID##name">##NAME##</td>
									<td  width="20%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
										<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										
										</span>
										<span id="##TYPE####ID##mupdate"  style="display:none">
										<!--<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'CategoryUpdate','imgUpdate':true,'type':'##TYPE##','style':'boxlevel1','validate':CategoryValidate,'uptForm':'Category','result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>-->
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
<?php
		//getTemplateRowBottom();
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	
	function getCmsNewsListTemplate(){
		ob_start();
		getTemplateRowTop();
?>
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
										<!--<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'ProductCopyDisplay','result':doDisplayResult,'style':'boxRow','type':'prd','params':'rID=##ID##&cID=##CID##'});"><img src="##IMAGE_PATH##template/copy_blue.gif" title="Copy"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>-->
										<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'CmsNewsMoveDisplay','result':doDisplayResult,'style':'boxRow','type':'prd','params':'rID=##ID##&name=##NAME##&cID=##CID##'});"><img src="##IMAGE_PATH##template/img_move.gif" title="Move Category"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										</span>
										<span id="##TYPE####ID##mupdate" style="display:none">
										<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'CmsNewsUpdate','imgUpdate':true,'type':'prd','params':'rID=##ID##&name=##NAME##&cID=##CID##','style':'boxRow','validate':CmsNewsValidate,'uptForm':'new_product','result':doTotalResult,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img ty src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
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
function getCmsNewsListTemplate1(){
		ob_start();
		getTemplateRowTop();
?>
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
										<!--<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'ProductCopyDisplay','result':doDisplayResult,'style':'boxRow','type':'prd','params':'rID=##ID##&cID=##CID##'});"><img src="##IMAGE_PATH##template/copy_blue.gif" title="Copy"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>-->
										
										</span>
										<span id="##TYPE####ID##mupdate" style="display:none">
										<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'CmsNewsUpdate','imgUpdate':true,'type':'prd','params':'rID=##ID##&name=##NAME##&cID=##CID##','style':'boxRow','validate':CmsNewsValidate,'uptForm':'new_product','result':doTotalResult,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img ty src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
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
				<td width="5%" align="right" nowrap="nowrap" style="overflow:hidden;" class="main"><b>##ENT_NAME##</b></td>
				<td width="10%" align="left" style="overflow:hidden"  class="main">##NAME##</td>
				<!--<td width="5%" align="right" style="overflow:hidden" class="main" nowrap="nowrap"><b>##ENT_DESCRIPTION##</b></td>
				<td width="10%"  align="left" style="overflow:hidden" class="main" nowrap="nowrap">##DESCRIPTION##</td>-->
			</tr>
		</table>
<?php
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	function getInfoTemplate1(){
		ob_start();
?>
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<div class="hLineGray"></div>
			<tr> <td class="main"><div style=" font-weight:bold; padding-top:10px; width:100%;height:20px;overflow:hidden"><!--##HEAD_NAME##--></div></td>
			
			<tr>
				<td width="5%" align="right" nowrap="nowrap" style="overflow:hidden;" class="main"><b>##ENT_NAME##</b></td>
				<td width="10%" align="left" style="overflow:hidden"  class="main">##NAME##</td>
				<!--<td width="5%" align="right" style="overflow:hidden" class="main" nowrap="nowrap"><b>##ENT_DESCRIPTION##</b></td>
				<td width="10%"  align="left" style="overflow:hidden" class="main" nowrap="nowrap">##DESCRIPTION##</td>-->
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
		<!--	<td valign="top" width="30"> </td>-->
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
<?php
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	
	function getCmsInfoTemplate(){
		ob_start();
?>
		<table border="0" cellpadding="0" cellspacing="0" width="50%">
			<tr>
		<!--	<td valign="top" width="30"> </td>-->
				<!--<td valign="top" width="##IMAGE_WIDTH##"><div style="width:100%;height:100px;overflow:hidden">##IMAGE##</div></td>-->
				<td width="10">&nbsp;</td>
				<td valign="top" width="250">
					<table border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr>
							<td class="main" >##ENT_NAME##</td>
							<td class="main" >##ENT_EMAIL##</td>
							<td class="main" >##ENT_GROUP##</td>
						</tr>
						<tr>
							<td class="main" >##ENT_ACCOUNT##</td>
							<td class="main" >##ENT_HIDE##</td>
							<td class="main" >##ENT_LOG##</td>
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
	function getCmsInfoTemplate1(){
		ob_start();
?>
		<table border="0" cellpadding="0" cellspacing="0" width="50%">
			<tr>
		<!--	<td valign="top" width="30"> </td>-->
				<!--<td valign="top" width="##IMAGE_WIDTH##"><div style="width:100%;height:100px;overflow:hidden">##IMAGE##</div></td>-->
				<td width="10">&nbsp;</td>
				<td valign="top" width="250">
					<table border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr>
							<td class="main" >##ENT_FIRST##</td>
							<td class="main" >##ENT_LAST##</td>
							<td class="main" >##ENT_EMAIL##</td>
						</tr>
						<tr>
							<td class="main" >##ENT_HIDE##</td>
							<td class="main" >##ENT_PASS##</td>
						
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
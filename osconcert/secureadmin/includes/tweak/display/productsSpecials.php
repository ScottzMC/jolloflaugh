<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
defined('_FEXEC') or die();
class productsSpecials
	{
	var $pagination;
	var $splitResult;
	var $type;

	function __construct() {
		$this->pagination=false;
		$this->splitResult=false;
		$this->type='pspl';
	}
	function doSearch() {
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
				<td class="main">
				<table border="0" cellpadding="2" cellspacing="0" width="100%" id="catTable" class="main">
					<?php 
					$found=$this->doList(" where pd.products_name like'%".$search_db."%'",0,$search);
					if (!$found){
					?>
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
		$jsData->VARS["NUclearType"]="pspl";
	}
	function doList($where='',$sId=0,$search='') {
		global $FSESSION,$FREQUEST,$jsData;
		$page=$FREQUEST->getvalue('page','int',1);
		$orderBy="order by p.products_id";
		$query_split=false;
	
		if(($FREQUEST->getvalue('search')) && ($FREQUEST->getvalue('search')!=""))
			$search = " and (pd.products_name like '%" . tep_db_input(($FREQUEST->getvalue('search'))). "%' )";
		//$specials_query_raw = "select p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price,s.customers_groups_id,s.customers_id from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = s.products_id " . $search . "order by pd.products_name";				 		
		$specials_query_raw = "select p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, date_format(s.expires_date,'%Y-%m-%d') as expires_date, s.date_status_change, s.status,s.customers_groups_id,s.customers_id from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' and p.products_id = s.products_id ".$search." order by s.specials_id desc";		       
		$all_groups=array();
		$customers_groups_query = tep_db_query("select distinct customers_groups_name, customers_groups_id from " . TABLE_CUSTOMERS_GROUPS . " order by customers_groups_id ");
		while ($existing_groups =  tep_db_fetch_array($customers_groups_query)) {
		$all_groups[$existing_groups['customers_groups_id']]=$existing_groups['customers_groups_name'];
		}
		$all_customers=array();
		$customers_query = tep_db_query("select distinct customers_firstname, customers_lastname, customers_id from " . TABLE_CUSTOMERS . " order by customers_id");
		while ($existing_customers = tep_db_fetch_array($customers_query)) {
		$all_customers[$existing_customers['customers_id']]=$existing_customers['customers_lastname'] . " " . $existing_customers['customers_firstname'];
		}
	
		if ($this->pagination){
			$query_split=$this->splitResult = (new instance)->getSplitResult('CUSTOMER');
			$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
			$query_split->parse($page,$specials_query_raw);
					if ($query_split->queryRows > 0){ 
						if ($search!=''){
							$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','get':'Search','result':doTotalResult,params:'search=". urlencode($search) . "&page='+##PAGE_NO##,'message':'" . INFO_SEARCHING_DATA . "'})";
						} else {	
							$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'Items','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_PRODUCTS,'##PAGE_NO##') . "'})";
						}
					}
		}
		$products_special_query=tep_db_query($specials_query_raw);
		$found=false;
		if (tep_db_num_rows($products_special_query)>0) $found=true;
		if($found) {
			$template=getListTemplate();
			$icnt=1;
			while($products_special_result=tep_db_fetch_array($products_special_query)){
				  if ($products_special_result['status'] == '1')
						$status=tep_image(DIR_WS_IMAGES . 'template/icon_active.gif', IMAGE_ICON_STATUS_GREEN, 10, 10);
					else
						$status=tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif', IMAGE_ICON_STATUS_RED, 10, 10);
					$special_cg=get_products_name($products_special_result['specials_id']);	
					$rep_array=array(	"ID"=>$products_special_result["specials_id"],
										"TYPE"=>$this->type,
										"NAME"=>$products_special_result["products_name"],
										"OLD_PRICE"=>$products_special_result['products_price'],
										"NEW_PRICE"=>$products_special_result['specials_new_products_price'] . ' ' . $special_cg,
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>$status,
										"UPDATE_RESULT"=>'doDisplayResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'Info',
										"FIRST_MENU_DISPLAY"=>""
									);
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}
			if (!isset($jsData->VARS["Page"])){
				$jsData->VARS["NUclearType"][]=$this->type;
			} 
		}
		else {
			 echo TEXT_NO_RECORDS_FOUND;
		}
		return $found;			
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
							"FIRST_MENU_DISPLAY"=>"display:none",
							"OLD_PRICE"=>"",
							"NEW_PRICE"=>""
							);
		?>
		<div class="main" id="prd-1message"></div>
		<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
			<?php 	echo mergeTemplate($rep_array,$template); ?>
			<tr>
				<td>
					<table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%">
						<tr class="dataTableHeadingRow">
							<td class="main" width="45%" style="padding-left:15px;">
								<b><?php echo TABLE_HEADING_PRODUCTS;?></b>
							</td>
							<td class="main">
								<b><?php echo TABLE_HEADING_PRODUCTS_PRICE;?></b>
							</td>
						</tr>
					</table>		
				</td>
			</tr>
			<tr>
				<td class="main" align="center">
			<?php 	$this->doList(); ?>
				</td>
			</tr>	
		</table>
		<?php if (is_object($this->splitResult)){?>
			<table border="0" width="100%" height="100%">
					<?php echo $this->splitResult->pgLinksCombo(); ?>
			</table>
		<?php }
	}
	function doEdit() {
		global $FSESSION,$FREQUEST,$jsData;
		$sId=$FREQUEST->getvalue("rID","int",-1);
		$server_dates = getServerDate(true); 
		//$product=array('products_name'=>'','specials_id'=>0,'products_id'=>0,'customers_id'=>0,'customers_groups_id'=>0,'specials_new_products_price'=>'','expires_date'=>$server_dates);	
		//CGDiscountSpecials start
		$specials_array = array();
		$specials_query = tep_db_query("select p.products_id, s.customers_groups_id from " .  TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s where s.products_id = p.products_id");
		while ($specials = tep_db_fetch_array($specials_query)) {
		  $specials_array[] = (int)$specials['products_id'].":".(int)$specials['customers_groups_id'];
		}	
		$customers_groups_query = tep_db_query("select distinct customers_groups_name, customers_groups_id from " . TABLE_CUSTOMERS_GROUPS . " order by customers_groups_name");
		$input_groups=array();
		$all_groups=array();
		$sde=0;
		while ($existing_groups = tep_db_fetch_array($customers_groups_query)) {
		  $input_groups[$sde++]=array("id"=>$existing_groups['customers_groups_id'],
							  "text"=>$existing_groups['customers_groups_name']);
		  $all_groups[$existing_groups['customers_groups_id']]=$existing_groups['customers_groups_name'];
		}
		$customers_query = tep_db_query("select distinct customers_firstname, customers_lastname, customers_id from " . TABLE_CUSTOMERS . " order by customers_lastname, customers_firstname");
		$input_customers=array();$all_customers=array();$sde=0;
		while ($existing_customers = tep_db_fetch_array($customers_query)) {
		  $input_customers[$sde++]=array("id"=>$existing_customers['customers_id'],
							  "text"=>$existing_customers['customers_lastname'] . " " . $existing_customers['customers_firstname']);
		  $all_customers[$existing_customers['customers_id']]=$existing_customers['customers_lastname'] . " " . $existing_customers['customers_firstname'];
		}
		//CGDiscountSpecials end	
		if ($FREQUEST->getvalue('rID')) {      
		  $product_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, s.specials_new_products_price, date_format(s.expires_date,'%Y-%m-%d') as expires_date ,s.customers_groups_id,s.customers_id,s.specials_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_id = pd.products_id  and pd.language_id='".(int)$FSESSION->languages_id."' and p.products_id = s.products_id and s.specials_id = '" . tep_db_input($sId) . "'");
		  $product = tep_db_fetch_array($product_query);      
		}
		if($sId>0) $sInfo = new objectInfo($product);    
		// create an array of products on special, which will be excluded from the pull down menu of products
		// (when creating a new product on special)
		$specials_array = array();
		$specials_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s where s.products_id = p.products_id");
		 while ($specials = tep_db_fetch_array($specials_query)) {
			$specials_array[] = $specials['products_id'];
		  }     
		 echo tep_draw_form('product_special','products_specials.php', ' ' ,'post','id="product_special"'); ?>
		<table cellpadding="4" cellspacing="0" width="100%" border="0">
			<tr>
				<td valign="top" width="25%" class="main" style=" font-weight:bold;height:30px;" ><?php echo TEXT_SPECIALS_PRODUCT; ?></td>
				<td valign="top" class="main" style=" font-weight:bold;height:30px;"><?php echo (isset($sInfo->products_name) ? $sInfo->products_name . ' <small>($' . substr($sInfo->products_price,0,-2) . ')</small>' : tep_draw_products_pull_down('products_id', 'style="font-size:10px"', $specials_array)); echo tep_draw_hidden_field('products_price', (isset($sInfo->products_price) ? $sInfo->products_price : '')); ?></td>
			</tr>
			<tr>
				<td valign="top" width="25%" class="main" style=" font-weight:bold;height:30px;"><?php echo TEXT_SPECIALS_CUSTOMERS; ?></td>
				<td valign="top" class="main" style=" font-weight:bold;height:30px;">
					<?php 
									
					if ($sInfo->customers_id != 0) {
					   echo tep_draw_pull_down_menu('customers', $input_customers, (isset($sInfo->customers_id)?$sInfo->customers_id:''),'');
					   echo '<input type="checkbox" name="checkbox_customers" value="Y" checked onClick="if (customers.disabled && checkbox_customers.checked) {customers.disabled = false; customers_groups.disabled = true; checkbox_customers_groups.checked = false} else {customers.disabled = true; customers_groups.disabled = true;}">';
					} else {
					   echo tep_draw_pull_down_menu('customers', $input_customers, (isset($sInfo->customers_id)?$sInfo->customers_id:''),'disabled');
					   echo '<input type="checkbox" name="checkbox_customers" value="Y" onClick="if (customers.disabled && checkbox_customers.checked) {customers.disabled = false; customers_groups.disabled = true; checkbox_customers_groups.checked = false} else {customers.disabled = true; customers_groups.disabled = true;}">';
					} 
					?></td>
					
			</tr>
			<tr>
				<td valign="top" width="25%" class="main" style=" font-weight:bold;height:30px;"><?php echo TEXT_SPECIALS_GROUP; ?></td>
				<td valign="top" class="main" style=" font-weight:bold;height:30px;">
					<?php 
				  if ($sInfo->customers_groups_id != 0) {
					  echo tep_draw_pull_down_menu('customers_groups', $input_groups, (isset($sInfo->customers_groups_id)?$sInfo->customers_groups_id:''),'');
					  echo '<input type="checkbox" name="checkbox_customers_groups" checked value="Y" onClick="if (customers_groups.disabled && checkbox_customers_groups.checked) {checkbox_customers_groups.value = true; customers_groups.disabled = false; customers.disabled = true; checkbox_customers.checked = false} else {checkbox_customers_groups.value = false; customers_groups.disabled = true; customers.disabled = true; }">';
				   } else {
					  echo tep_draw_pull_down_menu('customers_groups', $input_groups, (isset($sInfo->customers_groups_id)?$sInfo->customers_groups_id:''),'disabled');
					  echo '<input type="checkbox" name="checkbox_customers_groups" value="Y" onClick="if (customers_groups.disabled && checkbox_customers_groups.checked) {checkbox_customers_groups.value = true; customers_groups.disabled = false; customers.disabled = true; checkbox_customers.checked = false} else {checkbox_customers_groups.value = false; customers_groups.disabled = true; customers.disabled = true; }">';
				   } 
					?></td>
			</tr>
			<tr>
				<td valign="top" width="25%" class="main" style=" font-weight:bold;height:30px;"><?php echo TEXT_SPECIALS_SPECIAL_PRICE; ?></td>
				<td valign="top" class="main" style=" font-weight:bold;height:30px;"><?php echo tep_draw_input_field('specials_price', (isset($sInfo->specials_new_products_price) ? $sInfo->specials_new_products_price : ''),'size="10"'); ?></td>
			</tr>
			<tr>
				<td valign="top" width="25%" class="main" style=" font-weight:bold;height:30px;"><?php echo TEXT_SPECIALS_EXPIRES_DATE ; ?></td>
				<td valign="top" class="main" style=" font-weight:bold;height:30px;">
					<?php echo tep_draw_input_field("txt_date_begin",(tep_not_null($sInfo->expires_date)?format_date($sInfo->expires_date):format_date($server_dates)),"size=10",false,'text',false);
						  $_array=array('d','m','Y');  $replace_array=array('DD','MM','YYYY'); 	$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);
						  echo tep_create_calendar("product_special.txt_date_begin",$date_format); ?>
				</td>
			</tr>
			<tr>
				<Td colspan="2"><table cellpadding="2" cellspacing="0" width="100%">
					<tr>
						 <td class="main"><br><?php echo TEXT_SPECIALS_PRICE_TIP; ?></td>            
					</tr>
				</table></Td>	
			</tr>
		</table>
<?php	
	   	echo tep_draw_hidden_field('products_id_assign',$sInfo->products_id);
		echo tep_draw_hidden_field('special_id',$sInfo->specials_id);
		echo '</form>';
		$jsData->VARS["updateMenu"]=",update,";
		$display_mode_html=' style="display:none"';
	}
	function doUpdate() {
		global $FREQUEST,$jsData,$FSESSION;
		$server_dates = getServerDate();  
		$products_id = $FREQUEST->postvalue('products_id_assign','int',$FREQUEST->postvalue('products_id','int','-1'));				
		$insert=true;
		$special_id=$FREQUEST->postvalue('special_id');
        //echo tep_db_input(format_date($server_dates));
       //exit;
		if($special_id>0) $insert=false;
        $products_price = $FREQUEST->postvalue('products_price');
        $specials_price = $FREQUEST->postvalue('specials_price');
	    $expires_dates = tep_convert_date_raw($FREQUEST->postvalue("txt_date_begin","string","0000-00-00"));
	    //CGDiscountSpecials start
		$checkbox_customers_groups = $FREQUEST->postvalue('checkbox_customers_groups','','N');
		if($checkbox_customers_groups=='Y') $customers_groups = $FREQUEST->postvalue('customers_groups');
	
		$checkbox_customers = $FREQUEST->postvalue('checkbox_customers','','N');
		if($checkbox_customers=='Y')  $customers = $FREQUEST->postvalue('customers');
        //CGDiscountSpecials end
		
        //if (substr($FREQUEST->postvalue('specials_price'), -1) == '%') $specials_price = ($products_price - (($specials_price / 100) * $products_price));	
		if (substr($specials_price, -1) == '%') $specials_price = ($products_price - (($specials_price / 100) * $products_price));		
		if ($specials_price=='') $specials_price='0';
		 	$sql_data_array = array('specials_new_products_price' => tep_db_input($specials_price),
									'products_id'=>(int)$products_id,
									'customers_groups_id'=>(int)$customers_groups,
									'customers_id' =>(int)$customers, 
									'expires_date' =>tep_db_input($expires_dates)
									);
		if($insert) {											
			$insert_sql_data = array('specials_date_added' => getServerDate(),'specials_last_modified'=> '1970-01-02');
			$sql_data_array = array_merge($sql_data_array,$insert_sql_data);
			tep_db_perform(TABLE_SPECIALS, $sql_data_array);
			$special_id = tep_db_insert_id();			
		} else { 
			 $update_sql_data = array('specials_last_modified' => tep_db_input($server_dates));
			 $sql_data_array = array_merge($sql_data_array,$update_sql_data);
			 tep_db_perform(TABLE_SPECIALS, $sql_data_array, 'update', "specials_id = '" . (int)$special_id . "'");			 			  						 			
		}
		$specials_query_raw = "select p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, date_format(s.expires_date,'%Y-%m-%d') as expires_date, s.date_status_change, s.status,s.customers_groups_id,s.customers_id from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' and p.products_id = s.products_id and s.specials_id='" . $special_id . "' order by s.specials_id desc";		       
		$specials_query=tep_db_query($specials_query_raw);
		$specials_result=tep_db_fetch_array($specials_query);
		$special_cg=get_products_name($specials_result['specials_id']);	
			if($insert) {
				$this->doItems();
			} else {
				$jsData->VARS["replace"]=array($this->type. $special_id . "name"=>$specials_result['products_name'],$this->type . $special_id . "old_price"=>$specials_result['products_price'],$this->type . $special_id . "new_price"=>$specials_result['specials_new_products_price'] . $special_cg);
				$jsData->VARS["prevAction"]=array('id'=>$special_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
				$this->doInfo($special_id);
				$jsData->VARS["updateMenu"]=",normal,";
			}
	   	}	
		function doInfo($special_id=0) {
			global $FREQUEST,$FSESSION,$jsData,$currencies;
			if($special_id<=0) $special_id=$FREQUEST->getvalue('rID','int',0);
			$specials_query_raw = "select p.products_id, pd.products_name,p.products_image_1,p.products_title_1, p.products_price, s.specials_id, s.specials_new_products_price, date_format(s.specials_date_added,'%Y-%m-%d') as specials_date_added, date_format(s.specials_last_modified,'%Y-%m-%d') as specials_last_modified, date_format(s.expires_date,'%Y-%m-%d') as expires_date, s.date_status_change, s.status,s.customers_groups_id,s.customers_id from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' and p.products_id = s.products_id and s.specials_id='" . $special_id . "' order by s.specials_id desc";		       
			$specials_query=tep_db_query($specials_query_raw);
			if(tep_db_num_rows($specials_query)>0) {
				$specials_result=tep_db_fetch_array($specials_query);
				$special_cg=get_products_name($specials_result['specials_id']);	
				if($specials_result['specials_last_modified']!='0000-00-00' && $specials_result['specials_last_modified']!="") {	
					$date_added= '<tr>'.
									'<td class="main" width="100">' . TEXT_INFO_DATE_ADDED . '</td>'.
									'<td class="main">' . format_date($specials_result['specials_date_added']) . '</td>'.
								'</tr>';
					$date_added.= '<tr>'.
									'<td class="main" width="100">' . TEXT_INFO_LAST_MODIFIED . '</td>'.
									'<td class="main">' . format_date($specials_result['specials_last_modified']) . '</td>'.
								'</tr>';
				}
				else if($specials_result['specials_last_modified']=='0000-00-00') {
					$date_added= '<tr>'.
										'<td class="main" width="100">' . TEXT_INFO_DATE_ADDED . '</td>'.
										'<td class="main">' . format_date($specials_result['specials_date_added']) . '</td>'.
									'</tr>';
				}
				$template=getInfoTemplate($special_id);
                
				$rep_array=array('DATE_ADDED'=>$date_added,
								'ORIGINAL_PRICE'=>'<tr><td class="main">' . TEXT_INFO_ORIGINAL_PRICE . '</td><td class="main">' . $currencies->format($specials_result['products_price']) . '</td></tr>',
								'PRICE'=>'<tr><td class="main">' . TEXT_INFO_NEW_PRICE . '</td><td class="main">' . $currencies->format($specials_result['specials_new_products_price']) . '</td></tr>',
								'EXPIRES_DATE'=>'<tr><td class="main">' . TEXT_SPECIALS_EXPIRES_DATE . '</td><td class="main">' . format_date($specials_result['expires_date']) . '</td></tr>',
								"IMAGE_WIDTH"=>SMALL_IMAGE_WIDTH,
								"PRODUCTS_IMAGE"=>tep_product_small_image($specials_result['products_image_1'],$specials_result['products_title_1']),
								'type'=>$this->type,
								'ID'=>$specials_result['specials_id']
								);
								
			echo mergeTemplate($rep_array,$template);
			$jsData->VARS["updateMenu"]=",normal,";
			}
			else {
				echo 'Err:' . TEXT_SPECIALS_NOT_FOUND;
			}			
			
		}	
		function doDelete(){
			global $FREQUEST,$jsData;
			$special_id=$FREQUEST->postvalue('special_id','int',0);
		
			if ($special_id>0){
                $jsData->VARS['doFunc']=array('type'=>'group','data'=>'removeSearchValue');
				tep_db_query("DELETE from " . TABLE_SPECIALS . " where specials_id='".(int)$special_id."'");
				$this->doItems();
				//$jsData->VARS["deleteRow"]=array("id"=>$special_id,"type"=>$this->type);
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_SPECIAL_DELETE_SUCCESS);
				//tep_reset_seo_cache('customer');
			} else {
				echo "Err:" . TEXT_SPECIAL_NOT_DELETED;
			}
			
		}
		function doDeleteSpecials() {
			global $FREQUEST,$jsData;
			$special_id=$FREQUEST->getvalue('rID','int',0);

			$delete_message='<p><span class="smallText">' . TEXT_DELETE_INTRO . '</span>';
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="products_specials.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="special_id" value="<?php echo tep_output_string($special_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $special_id;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $special_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $special_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php echo $this->doInfo($special_id);?></td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}
}
function getListTemplate() {
	ob_start();
	getTemplateRowTop();	
?>
	<table cellpadding="2" cellspacing="0" width="100%" id="##TYPE####ID##">
		<tr>
			<td>
				<table cellpadding="2" cellspacing="0" width="100%" border="0" >
					<tr>
						<td width="1%" id="pspl##ID##bullet">##STATUS##</td>
						<td width="35%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##name">##NAME##</td>
						<td  class="main" align="left" width="35%" valign="top" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##price">
						  <span class="oldPrice" id="##TYPE####ID##old_price">##OLD_PRICE##</span> 
						  <span class="specialPrice" id="##TYPE####ID##new_price">##NEW_PRICE##</span>
						</td> 
						<!--<td class="oldPrice" id="##TYPE####ID##old_price##" align="right">##OLD_PRICE##</td>
						<td class="specialPrice" id="##TYPE####ID##new_price##" align="left">##NEW_PRICE##</td>-->
						<td  width="10%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
							<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
							<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##','backupMenu':true});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteSpecials','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							</span>
							<span id="##TYPE####ID##mupdate" style="display:none">
							<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':groupValidate,'uptForm':'product_special','customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
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
function getInfoTemplate() {
	ob_start(); ?>
	<table cellpadding="4" cellspacing="0" width="100%" border="0">
		<tr>
			<td valign="middle" width="##IMAGE_WIDTH##" class="main"><div style="width:100%;height:100px;overflow:hidden">##PRODUCTS_IMAGE##</div></td>
			<td width="3%">&nbsp;</td>
			<td valign="top">
				<table cellpadding="4" cellspacing="0" width="100%" border="0">
					##DATE_ADDED##
					##ORIGINAL_PRICE##
					##PRICE##
					##EXPIRES_DATE##
				</table>
			</td>
		</tr>
	</table>
<?php 		
	$contents=ob_get_contents();
	ob_end_clean();
	return $contents;
}
function get_products_name($id)
{   
    global $FSESSION;
    $all_groups=array();
    $customers_groups_query = tep_db_query("select distinct customers_groups_name, customers_groups_id from " . TABLE_CUSTOMERS_GROUPS . " order by customers_groups_id ");
    while ($existing_groups =  tep_db_fetch_array($customers_groups_query)) {
      $all_groups[$existing_groups['customers_groups_id']]=$existing_groups['customers_groups_name'];
    }
	$all_customers=array();
	$customers_query = tep_db_query("select distinct customers_firstname, customers_lastname, customers_id from " . TABLE_CUSTOMERS . " order by customers_id");
    while ($existing_customers = tep_db_fetch_array($customers_query)) {
      $all_customers[$existing_customers['customers_id']]=$existing_customers['customers_lastname'] . " " . $existing_customers['customers_firstname'];
    }
    //CGDiscountSpecials end
    $specials_query= tep_db_query("select p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, date_format(s.expires_date,'%Y-%m-%d') as expires_date, s.date_status_change, s.status,s.customers_groups_id,s.customers_id from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.language_id='$FSESSION->languages_id' and p.products_id = pd.products_id and s.specials_id='" . tep_db_input($id) . "' and p.products_id = s.products_id order by pd.products_name");     
	$specials = tep_db_fetch_array($specials_query);	
		if ($specials['customers_groups_id'] != 0) {
		   $group = " ( [G] -> " . $all_groups[$specials['customers_groups_id']];
		 } else if ($specials['customers_id'] != 0) {
		   $customer = " ( [C] -> " . $all_customers[$specials['customers_id']];
		 } else {
		   $para = " ( ";
		 }
		 $para1= " )";
		return $group . $customer . $para . $para1;
}
?>

<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
defined('_FEXEC') or die();
class salesCoupon
{
	var $pagination;
	var $splitResult;
	var $type;

	function salesCoupon()
	{
	$this->pagination=false;
	$this->splitResult=false;
	$this->type='salCoupon';
	}
	function doCheckCode() {
		global $FSESSION,$FREQUEST,$jsData;
		$coupon_id=$FREQUEST->getvalue('rID','int',0);
		$code=$FREQUEST->getvalue('code','string','');
		if($code!="" && $coupon_id>0) {
			$coupon_code_check_query = tep_db_query("select coupon_code from " . TABLE_COUPONS ."  where coupon_code = '" . tep_db_input($code) . "'");
			if(tep_db_num_rows($coupon_code_check_query)>0) 
				echo 'Err. Coupon Code is already Exists';
		 }
	}
	function doLoadCategories() {
		global $FREQUEST,$jsData;
		$prev_ids=$FREQUEST->getvalue('prev_ids');
		$type=$FREQUEST->getvalue('type','string','');
		if($prev_ids!=""){
			$prev_ids = preg_split('/,/',$prev_ids);
			echo display_categories($type,$prev_ids);
		}
		else
			echo display_categories($type);
	}
	function doLoadItems() {
		global $FREQUEST,$jsData;
		$prev_ids=$FREQUEST->getvalue('prev_ids');
		$type=$FREQUEST->getvalue('type','string','');
		if($prev_ids != ""){
			$prev_ids = preg_split('/,/',$prev_ids);
			echo display_items($type,$prev_ids);
		}else
			echo display_items($type);
	}
	function doList($status){
		global $FSESSION,$currencies,$FREQUEST,$jsData;
		$page=$FREQUEST->getvalue('page','int',1);
		$query_split=false;
		if ($status != '*'  && $status!='') 
	      $cc_query_raw = "select * from " . TABLE_COUPONS ." where coupon_active='" . tep_db_input($status) . "' and coupon_type != 'G' order by coupon_id desc";
    	else 
	      $cc_query_raw = "select * from " . TABLE_COUPONS . " where coupon_type != 'G' order by coupon_id desc";
		  
		if ($this->pagination){
			$query_split=$this->splitResult = (new instance)->getSplitResult('CUSTOMER');
			$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
			$query_split->parse($page,$cc_query_raw);
					if ($query_split->queryRows > 0){ 
						if ($search!=''){
							$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','get':'SearchGroup','result':doTotalResult,params:'search=". urlencode($search) . "&page='+##PAGE_NO##,'message':'" . INFO_SEARCHING_DATA . "'})";
						} else {	
							$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'Items','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(TEXT_LOADING_DATA,'##PAGE_NO##') . "'})";
						}
					}
		}
		$cc_query=tep_db_query($cc_query_raw);
		$found=false;
		if (tep_db_num_rows($cc_query)>0) $found=true;
		if($found)
		{
			$template=getListTemplate();
			$icnt=1;
			while($cc_result=tep_db_fetch_array($cc_query)){
				$coupon_description_query = tep_db_query("select c.orders_id,cd.coupon_name from " . TABLE_COUPONS . " c, " . TABLE_COUPONS_DESCRIPTION . " cd where c.coupon_id = cd.coupon_id and cd.coupon_id = '" . $cc_result['coupon_id'] . "' and cd.language_id = '" . (int)$FSESSION->languages_id . "'");
  				$coupon_desc = tep_db_fetch_array($coupon_description_query);
				$tax_rate=tep_get_tax_rate($cc_result["coupon_tax_class_id"]);
				$amount=tep_add_tax($cc_result["coupon_amount"],$tax_rate);
				if ($cc_result['coupon_type'] == 'P') {
					$coupon_amount=number_format($cc_result['coupon_amount'], 2, '.', '') . '%';

				} elseif ($cc_result['coupon_type'] == 'S') {
					$coupon_amount=TEXT_FREE_SHIPPING;
				} else {
					$coupon_amount=$currencies->format($amount);
				}
				$rep_array=array("ID"=>$cc_result["coupon_id"],
								"TYPE"=>$this->type,
								"NAME"=>$coupon_desc["coupon_name"],
								"ORDERID"=>$coupon_desc["orders_id"],
								"AMOUNT"=>$coupon_amount,
								"CODE"=>$cc_result["coupon_code"],
								"IMAGE_PATH"=>DIR_WS_IMAGES,
								//"STATUS"=>'<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $cc_result["coupon_id"] .",get:'SaleChangeStatus',result:doSimpleResult,params:'rID=". $cc_result["coupon_id"] . "&status=" .($cc_result["sale_status"]==1?0:1) . "','message':'".TEXT_UPDATING_STATUS."'});\">" . tep_image(DIR_WS_IMAGES . 'template/' . ($sales_result["sale_status"]==1?'icon_active.gif':'icon_inactive.gif')) . '</a>',
								"STATUS"=>'',
								"UPDATE_RESULT"=>'doDisplayResult',
								"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
								"ROW_CLICK_GET"=>'Info',
								"FIRST_MENU_DISPLAY"=>""
								);
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}
		}
		else echo '<div align="center" class="main">' . TEXT_EMPTY_COUPONS . '</div>';
		if (!isset($jsData->VARS["Page"])){
			$jsData->VARS["NUclearType"][]=$this->type;
		} 
		return $found;			
	}
	function doInfo($coupon_id=0){
		global $FREQUEST,$FSESSION,$currencies,$jsData;

     /* $tax_id=$FREQUEST->postvalue('coupon_tax_class_id');
		$tax_query=tep_db_query("SELECT tc.tax_class_title,tr.tax_rate from " . TABLE_TAX_CLASS . " tc," . TABLE_TAX_RATES . " tr where tc.tax_class_id='" . tep_db_input($tax_id) . "' and tc.tax_class_id=tr.tax_class_id");
		$tax_result=tep_db_fetch_array($tax_query);
		$tax_rate=$tax_result["tax_rate"];
		$tax_class=$tax_result["tax_class_title"];
		if ($tax_class=="") $tax_class="none";
		$amount=$FREQUEST->postvalue('coupon_amount');
		$gross_amount=$amount;
		if ($tax_rate>0) $gross_amount=$amount+($amount*$tax_rate/100);
        if (substr($FREQUEST->postvalue('coupon_amount'), -1) == '%'){
            $gross_amount =number_format($gross_amount, 2, '.', '').'%';
        }
	*/
		if($coupon_id <= 0)$coupon_id=$FREQUEST->getvalue("rID","int",0);
		$coupon_details_raw = "select c.coupon_active, c.coupon_id,c.coupon_amount,c.coupon_type,date_format(c.coupon_start_date,'%Y-%m-%d') as start_date,date_format(c.coupon_expire_date,'%Y-%m-%d') as end_date,c.uses_per_coupon,c.uses_per_user, c.uses_per_order,		c.restrict_to_products,c.restrict_to_categories,date_format(c.date_created,'%Y-%m-%d') as date_created,date_format(c.date_modified,'%Y-%m-%d') as date_modified,cd.coupon_name,c.coupon_code from " . TABLE_COUPONS ." c, " . TABLE_COUPONS_DESCRIPTION . " cd  where c.coupon_id = cd.coupon_id and c.coupon_id =  '". tep_db_input($coupon_id) . "' and cd.language_id = '" . (int)$FSESSION->languages_id . "'";
		$coupon_details_query = tep_db_query($coupon_details_raw);
		if (tep_db_num_rows($coupon_details_query)>0){
			$coupon=tep_db_fetch_array($coupon_details_query);
			$template=getInfoTemplate($coupon_id);
			$apply_coupon="";
            if($tax_rate>0){
              $coupon_amount=$coupon['coupon_amount']+($coupon['coupon_amount']*$tax_rate/100);
            }else{
              $coupon_amount=$coupon['coupon_amount'];
            }
            if($coupon['coupon_type']=='P'){
                $coupon_amount=number_format($coupon_amount, 2, '.', '').'%';
            }else{
                $coupon_amount=$currencies->format(number_format($coupon_amount, 2, '.', ''));
            }
            if($coupon['restrict_to_products']=='' && $coupon['restrict_to_categories']=='')
				$apply_coupon='All Categories';
			$rep_array=array("TYPE"=>$this->type,
							"ENT_COUPON_NAME"=>COUPON_NAME,
							"COUPON_NAME"=>$coupon['coupon_name'],	
							"ENT_START_DATE"=>COUPON_STARTDATE,
							"START_DATE"=>(($coupon['start_date'] != '0000-00-00') ?format_date($coupon['start_date']):''),
							"ENT_USES_PER_COUPON"=>COUPON_USES_COUPON,
							"USES_PER_COUPON"=>$coupon['uses_per_coupon'],
							"ENT_DATE_CREATED"=>DATE_CREATED,
							"DATE_CREATED"=>(($coupon['date_created']!='0000-00-00')?format_date($coupon['date_created']):''),
							"ENT_APPLY_COUPON"=>TEXT_COUPON_ALL_APPLY,
                            "ENT_COUPON_ACTIVE" => TEXT_COUPON_ACTIVE,
                            "COUPON_ACTIVE" => $coupon['coupon_active'],
							"APPLY_COUPON"=>$apply_coupon,
							"ENT_COUPON_AMOUNT"=>COUPON_AMOUNT,
							"COUPON_AMOUNT"=>$coupon_amount,
							"ENT_END_DATE"=>COUPON_FINISHDATE,
							"END_DATE"=>(($coupon['end_date']!='0000-00-00')?format_date($coupon['end_date']):''),
							"ENT_USES_PER_CUSTOMER"=>COUPON_USES_USER,
							"USES_PER_CUSTOMER"=>$coupon['uses_per_user'],
							"ENT_DATE_MODIFIED"=>DATE_MODIFIED,
							"DATE_MODIFIED"=>(($coupon['date_modified']!='0000-00-00')?format_date($coupon['date_modified']):''),
							"ID"=>$coupon["coupon_id"],
							);
			
			$rep_array["VIEW_PRODUCT"]=$rep_array["VIEW_PRODUCT_CATEGORIES"]='style="display:none"';
										
			
			if($coupon['restrict_to_products']!="") {
				$rep_array["VIEW_PRODUCT"]="";
				$rep_array["ENT_PRODUCT_LIST"]="Valid Product List";
				$rep_array["VIEW_PRODUCT_LIST"]='<A HREF="sales_coupon_listproducts.php?cid=' . $coupon["coupon_id"] . '" TARGET="_blank" ONCLICK="window.open(\'sales_coupon_listproducts.php?cid=' . $coupon["coupon_id"] . '\', \'Valid_Categories\', \'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600\'); return false">View</A>';
			}
			if($coupon['restrict_to_categories']!="") {
				$rep_array["VIEW_PRODUCT_CATEGORIES"]="";
				$rep_array["ENT_PRODUCT_CAT_LIST"]="Valid Product Categories List";
				$rep_array["VIEW_PRODUCT_CAT_LIST"]='<A HREF="sales_coupon_listcategories.php?cid=' . $coupon["coupon_id"] . '" TARGET="_blank" ONCLICK="window.open(\'sales_coupon_listcategories.php?cid=' . $coupon["coupon_id"] . '\', \'Valid_Categories\', \'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600\'); return false">View</A>';
			}
				
			echo mergeTemplate($rep_array,$template);
			$jsData->VARS["updateMenu"]=",normal,";
		}
		else {
			echo 'Err:' . TEXT_COUPON_NOT_FOUND;
		}
	}	
	function doPreview() {
		global $FREQUEST,$jsData,$FSESSION;
		
		$coupon_id=$FREQUEST->postvalue('coupon_id','int',0); 
		echo tep_draw_form('preview_coupon','sales_coupon.php', ' ' ,'post','id="preview_coupon"');
		?>
 <table border="0" width="80%" cellspacing="0" cellpadding="6">
<?php
	$languages = tep_get_languages();
	$cpn_name=$FREQUEST->postvalue('coupon_name');
	for ($i = 0, $n = sizeof($languages); $i < $n; $i++) 
	{
		$language_id = $languages[$i]['id'];
?>
      <tr>
	  		<td width="2%">&nbsp;</td>
        	<td width="30%" class="main" align="left"><?php echo COUPON_NAME . '(' . $languages[$i]['code'] . ')'; ?></td>
        	<td class="main" align="left"><?php echo $cpn_name[$language_id]; echo tep_draw_hidden_field('coupon_name[' . $language_id . ']' ,$cpn_name[$language_id]); ?></td>
      </tr>
<?php		
	}
	$cpn_desc=$FREQUEST->postvalue('coupon_desc');
	for ($i = 0, $n = sizeof($languages); $i < $n; $i++) 
	{
		$language_id = $languages[$i]['id'];
?>
      <tr>
	  		<td width="2%">&nbsp;</td>
        	<td align="left" class="main" width="30%"><?php echo COUPON_DESC . '(' . $languages[$i]['code'] . ')'; ?></td>
        	<td align="left" class="main"><?php echo $cpn_desc[$language_id]; echo tep_draw_hidden_field('coupon_desc[' . $language_id . ']'  ,$cpn_desc[$language_id]);?></td>
      </tr>
<?php
		}// end of language-2 forloop
		$tax_id=$FREQUEST->postvalue('coupon_tax_class_id');
		$tax_query=tep_db_query("SELECT tc.tax_class_title,tr.tax_rate from " . TABLE_TAX_CLASS . " tc," . TABLE_TAX_RATES . " tr where tc.tax_class_id='" . tep_db_input($tax_id) . "' and tc.tax_class_id=tr.tax_class_id");
		$tax_result=tep_db_fetch_array($tax_query);
		$tax_rate=$tax_result["tax_rate"];
		$tax_class=$tax_result["tax_class_title"];
		if ($tax_class=="") $tax_class="none";
		$amount=$FREQUEST->postvalue('coupon_amount');
		$gross_amount=$amount;
		if ($tax_rate>0) $gross_amount=$amount+($amount*$tax_rate/100);
        if (substr($FREQUEST->postvalue('coupon_amount'), -1) == '%'){
            $gross_amount =number_format($gross_amount, 2, '.', '').'%';
        }
?>
		<tr>
			  <td width="2%">&nbsp;</td>
       		  <td align="left" class="main" width="30%"><?php echo TEXT_COUPON_TAX_CLASS; ?></td>
        	  <td align="left" class="main"><?php echo $tax_class; echo tep_draw_hidden_field('coupon_tax_class_id',$FREQUEST->postvalue('coupon_tax_class_id')); ?></td>
      </tr>
	   <tr>
	   		<td width="2%">&nbsp;</td>
       		 <td align="left" class="main" width="30%"><?php echo TEXT_COUPON_PRICE_NET; ?></td>
             <td align="left"class="main"><?php echo $amount; echo tep_draw_hidden_field('coupon_amount',$amount); ?></td>
      </tr>
	  <tr>
	  		<td width="2%">&nbsp;</td>
        	<td align="left"class="main" width="30%"><?php echo TEXT_COUPON_PRICE_GROSS; ?></td>
       		 <td align="left"class="main"><?php echo $gross_amount; echo tep_draw_hidden_field('coupon_amount_gross',$gross_amount); ?></td>
      </tr>
      <tr>
	  			<td width="2%">&nbsp;</td>
       		 <td align="left"class="main" width="30%"><?php echo COUPON_MIN_ORDER; ?></td>
       		 <td align="left"class="main"><?php echo $FREQUEST->postvalue('coupon_min_order'); echo tep_draw_hidden_field('coupon_min_order',$FREQUEST->postvalue('coupon_min_order')); ?></td>
      </tr>
      <tr>
	  		<td width="2%">&nbsp;</td>
        	<td align="left"class="main" width="30%"><?php echo COUPON_FREE_SHIP; ?></td>
<?php
    if ($FREQUEST->postvalue('coupon_free_ship')=='S') {
?>
       		 <td align="left"class="main"><?php echo TEXT_FREE_SHIPPING; ?></td>
<?php
    } else { 
?>
        	<td align="left"class="main"><?php echo TEXT_NO_FREE_SHIPPING; ?></td>
<?php
    }
	echo tep_draw_hidden_field('coupon_free_ship',$FREQUEST->postvalue('coupon_free_ship'));
?>
      </tr>
      <tr>
	  		<td width="2%">&nbsp;</td>
        	<td align="left"class="main" width="30%"><?php echo TEXT_COUPON_ACTIVE; ?></td>
<?php
    if ($FREQUEST->postvalue('coupon_active')=='Y') {
?>
       		 <td align="left"class="main"><?php echo TEXT_COUPON_ACTIVE; ?></td>
<?php
    } else { 
?>
        	<td align="left"class="main"><?php echo TEXT_COUPON_INACTIVE; ?></td>
<?php
    }
	echo tep_draw_hidden_field('coupon_active',$FREQUEST->postvalue('coupon_active'));
?>
      </tr>
      <tr>
	  		<td width="2%">&nbsp;</td>
        	<td align="left" class="main" width="30%"><?php echo COUPON_CODE; ?></td>
<?php
    if ($FREQUEST->postvalue('coupon_code')) {
      $c_code = $FREQUEST->postvalue('coupon_code');
    } else {
      $c_code = $coupon_code;
    }
?>
        <td align="left" class="main"><?php echo $c_code; echo tep_draw_hidden_field('coupon_code',$c_code); ?></td>
      </tr>
      <tr>
	  		<td width="2%">&nbsp;</td>
        	<td align="left" class="main" width="30%"><?php echo COUPON_USES_COUPON; ?></td>
        	<td align="left" class="main"><?php if(is_numeric($FREQUEST->postvalue('coupon_uses_coupon'))) echo $FREQUEST->postvalue('coupon_uses_coupon'); else echo TEXT_NO_LIMIT;  ?></td>
      </tr>
	  <input type="hidden" name="coupon_uses_coupon" value="<?php echo $FREQUEST->postvalue('coupon_uses_coupon'); ?>" />
      <tr>
	  		<td width="2%">&nbsp;</td>
        	<td align="left" class="main" width="30%"><?php echo COUPON_USES_USER; ?></td>
        	<td align="left" class="main"><?php if(is_numeric($FREQUEST->postvalue('coupon_uses_user'))) echo $FREQUEST->postvalue('coupon_uses_user'); else echo TEXT_NO_LIMIT; ?></td>
      </tr>
	  <input type="hidden" name="coupon_uses_user" value="<?php echo $FREQUEST->postvalue('coupon_uses_user'); ?>" />
            <tr>
	  		<td width="2%">&nbsp;</td>
        	<td align="left" class="main" width="30%"><?php echo COUPON_USES_ORDER; ?></td>
        	<td align="left" class="main"><?php if(is_numeric($FREQUEST->postvalue('coupon_uses_order'))) echo $FREQUEST->postvalue('coupon_uses_order'); else echo TEXT_NO_LIMIT; ?></td>
      </tr>
	  <input type="hidden" name="coupon_uses_order" value="<?php echo $FREQUEST->postvalue('coupon_uses_order'); ?>" />
	  <tr>
	  		<td width="2%">&nbsp;</td>	
        	<td align="left" class="main" width="30%"><?php echo COUPON_STARTDATE; ?> </td>
<?php
    $start_date = date(EVENTS_DATE_FORMAT, mktime(0, 0, 0, $FREQUEST->postvalue('coupon_startdate_month'),$FREQUEST->postvalue('coupon_startdate_day') ,$FREQUEST->postvalue('coupon_startdate_year') ));
?>  
        	<td align="left" class="main"><?php echo $start_date; ?><input type="hidden" name="coupon_startdate" value="<?php echo $start_date; ?>"></td>
      </tr>
      <tr>
	  		<td width="2%">&nbsp;</td>
        	<td align="left" class="main" width="30%"><?php echo COUPON_FINISHDATE; ?></td>
<?php
    $finish_date = date(EVENTS_DATE_FORMAT, mktime(0, 0, 0, $FREQUEST->postvalue('coupon_finishdate_month'),$FREQUEST->postvalue('coupon_finishdate_day') ,$FREQUEST->postvalue('coupon_finishdate_year') ));

?>
        	<td align="left" class="main"><?php echo $finish_date; ?><input type="hidden" name="coupon_finishdate" value="<?php echo $finish_date; ?>"></td>
      </tr>
<?php
	  if($FREQUEST->postvalue('coupon_alll'=='on')){ ?>
	   <tr>
		<td width="2%">&nbsp;</td>
        <td align="left" class="main"><?php echo TEXT_APPLY_COUPON; ?></td>

        <td align="left" class="main"><?php echo TEXT_TO_ALL_CATEGORIES; ?></td>

      </tr>
	  <?php } else {
		 if($FREQUEST->postvalue('coupon_categories')){
		 $prd_name_cat_query= tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION ." where language_id = '" . (int)$FSESSION->languages_id . "'  and categories_id in(". tep_db_input($FREQUEST->postvalue('coupon_categories')) . ")"); 
		  while($prd_cat_name = tep_db_fetch_array($prd_name_cat_query))
		  {
			$prd_cat_names .=  $prd_cat_name['categories_name'] . '&nbsp;'.',' . ' ';
			}
			$prd_cat_names = substr($prd_cat_names,0,-2);
		?>
	   <tr>
	   	<td width="2%">&nbsp;</td>
        <td align="left" class="main" width="30%"><?php echo COUPON_CATEGORIES; ?></td>
        <td align="left" class="main"><?php echo $prd_cat_names; ?></td>
      </tr><?php } ?>
	  
		<?php 
		 if($FREQUEST->postvalue('coupon_products')){
		 $prd_name_query= tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION ." where language_id =". (int)$FSESSION->languages_id ." and products_id in(". tep_db_input($FREQUEST->postvalue('coupon_products')) . ")"); 
		  while($prd_name = tep_db_fetch_array($prd_name_query)){
		  		$prd_names .=   $prd_name['products_name'] . '&nbsp;'. ',' .' ';
		  		}$prd_names = substr($prd_names,0,-2);
		?>
       <tr>
	   	<td width="2%">&nbsp;</td>
        <td align="left" class="main" width="30%"><?php echo COUPON_PRODUCTS; ?></td>
        <td align="left" class="main"><?php echo $prd_names; ?></td>
      </tr><?php } ?>
	  

	   	<?php } 
	
		echo tep_draw_hidden_field('coupon_categories',$FREQUEST->postvalue('coupon_categories')); 

		echo tep_draw_hidden_field('coupon_products',$FREQUEST->postvalue('coupon_products'));

		echo tep_draw_hidden_field('coupon_id',$coupon_id);
		if($coupon_id>0) 
		{
	   ?>
		<tr>
			<td width="2%">&nbsp;</td>
			<td align="right" style="cursor:pointer;" colspan="2" onclick="javascript:return doUpdateAction({'id':'<?php echo $coupon_id; ?>','get':'Update','result':doDisplayResult,'style':'boxRow','type':'salCoupon','uptForm':'preview_coupon','customUpdate':doItemUpdate('Update'),'params':'rID=<?php echo $coupon_id; ?>'});"><?php echo tep_image_button('button_confirm.gif',COUPON_BUTTON_CONFIRM); ?></td>
		</tr>
		<?php } 
		else { ?>
		<tr>
			<td width="2%">&nbsp;</td>
			<td align="right" style="cursor:pointer;" colspan="2" onclick="javascript:return doUpdateAction({'id':'<?php echo $coupon_id; ?>','get':'Update','result':doTotalResult,'style':'boxRow','type':'salCoupon','uptForm':'preview_coupon','customUpdate':doItemUpdate('Update'),'params':'rID=<?php echo $coupon_id; ?>'});"><?php echo tep_image_button('button_confirm.gif',COUPON_BUTTON_CONFIRM); ?></td>
		</tr>
		<?php } ?>
</table>
<?php
	echo '</form>';
	$jsData->VARS["updateMenu"]=",update,";
}	
	function doUpdate() 
	{
		global $FREQUEST,$FSESSION,$jsData,$currencies;
		$insert=true;
		$coupon_id=$FREQUEST->postvalue('coupon_id','int',0);
        $coupon_type = "F";
        $coupon_active = "N";
        $coupon_type=$FREQUEST->postvalue('coupon_free_ship');
        $coupon_am = $FREQUEST->postvalue('coupon_amount');
        $coupon_active = $FREQUEST->postvalue('coupon_active');
        if (substr($FREQUEST->postvalue('coupon_amount'), -1) == '%'){
            $coupon_type='P';
            $coupon_am = substr($FREQUEST->postvalue('coupon_amount'),0,-1);
        }
        //if ($FREQUEST->postvalue('coupon_free_ship')) $coupon_type = 'S';
		
		if($FREQUEST->postvalue('coupon_uses_coupon')=='') {
		$uses_per_coupon='0';
		}
		if($FREQUEST->postvalue('coupon_uses_order')=='') {
		$uses_per_order='0';
		}
		else $uses_per_coupon=$FREQUEST->postvalue('coupon_uses_coupon');
		 if($FREQUEST->postvalue('coupon_min_order')=='') {
		 $coupon_minimum_order='0';
		 }
		 else $coupon_minimum_order=$FREQUEST->postvalue('coupon_min_order');
		 if($FREQUEST->postvalue('coupon_uses_user')=='') $uses_per_user=0;
		 else $uses_per_user = $FREQUEST->postvalue('coupon_uses_user');
		 	 if($FREQUEST->postvalue('coupon_uses_order')=='') $uses_per_order=0;
		 else $uses_per_order = $FREQUEST->postvalue('coupon_uses_order');
		///echo (($coupon_am == $FREQUEST->postvalue('coupon_amount'))?$FREQUEST->postvalue('coupon_amount'):$coupon_am);exit;
        $sql_data_array = array('coupon_code' => $FREQUEST->postvalue('coupon_code'),
                                'coupon_amount' =>$coupon_am,
                                'coupon_type' => tep_db_prepare_input($coupon_type),
                                'coupon_active' => tep_db_prepare_input($coupon_active),
                                'uses_per_coupon' => tep_db_prepare_input($uses_per_coupon),
								'coupon_tax_class_id' => $FREQUEST->postvalue('coupon_tax_class_id'),
                                'uses_per_user' => tep_db_prepare_input($uses_per_user),
								'uses_per_order' => tep_db_prepare_input($uses_per_order),
                                'coupon_minimum_order' => tep_db_prepare_input($coupon_minimum_order),
                                'restrict_to_products' => $FREQUEST->postvalue('coupon_products'),
                                'restrict_to_categories' => $FREQUEST->postvalue('coupon_categories'),
                                'coupon_start_date' => tep_convert_date_raw($FREQUEST->postvalue('coupon_startdate')),
                                'coupon_expire_date' => tep_convert_date_raw($FREQUEST->postvalue('coupon_finishdate')),
                                'date_created' => getServerDate(),
                                'date_modified' => getServerDate());
                                
        $languages = tep_get_languages();
		  $cpn_name=$FREQUEST->postvalue('coupon_name');
		  $cpn_desc=$FREQUEST->postvalue('coupon_desc');
		$name_array=false;
        if(is_array($cpn_name) || is_array($cpn_desc)) {
			for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
				$language_id = $languages[$i]['id'];
				$sql_data_marray[$i] = array('coupon_name' => tep_db_prepare_input($cpn_name[$language_id]),
									 'coupon_description' => tep_db_prepare_input($cpn_desc[$language_id])
									 );
			}
			$name_array=true;
		}
		else {
			$sql_data_marray = array('coupon_name' => tep_db_prepare_input($cpn_name),
								 'coupon_description' => tep_db_prepare_input($cpn_desc)
								 );
			$name_array=false;					 
		}	

		if ($coupon_id>0) { 
			$cid = $FREQUEST->postvalue('coupon_id');
			tep_db_perform(TABLE_COUPONS, $sql_data_array, 'update', "coupon_id='" . tep_db_input($FREQUEST->postvalue('coupon_id'))."'"); 
			if($name_array) {
				for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
					$language_id = $languages[$i]['id'];
					tep_db_perform(TABLE_COUPONS_DESCRIPTION, $sql_data_marray[$i], 'update', 'coupon_id="' . tep_db_input($FREQUEST->postvalue('coupon_id')) . '" and language_id= "' . tep_db_input($language_id) . '" ');
				}
			}
			else {
				tep_db_perform(TABLE_COUPONS_DESCRIPTION, $sql_data_marray, 'update', 'coupon_id="' . tep_db_input($FREQUEST->postvalue('coupon_id')) . '" and language_id= "' . $FSESSION->languages_id . '" ');
			}	
			$insert=false;
		} else {   
			$query = tep_db_perform(TABLE_COUPONS, $sql_data_array);
			$insert_id = tep_db_insert_id();
			$cid = $insert_id;
			if($name_array) {
				for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
					$language_id = $languages[$i]['id'];
					$sql_data_marray[$i]['coupon_id'] = $insert_id;
					$sql_data_marray[$i]['language_id'] = $language_id;
					tep_db_perform(TABLE_COUPONS_DESCRIPTION, $sql_data_marray[$i]);            
				}
			}
			else {
				$sql_data_marray['coupon_id'] = $insert_id;
				$sql_data_marray['language_id'] = $FSESSION->languages_id;
				tep_db_perform(TABLE_COUPONS_DESCRIPTION, $sql_data_marray);            
			}	
			$insert=true;
		}
	  
	if ($insert) {
		$coupon_id=$insert_id;
		$this->doItems();
	} else {
		$coupon_name_query=tep_db_query("Select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id='" . $coupon_id . "' and language_id='" . $FSESSION->languages_id . "' ");
		$coupon_name_result=tep_db_fetch_array($coupon_name_query);
		
		$coupon_details_raw = "select coupon_id, coupon_code, coupon_amount,coupon_tax_class_id, coupon_type from " . TABLE_COUPONS . " where coupon_id =  '". tep_db_input($coupon_id) . "'";
		$coupon_details_query = tep_db_query($coupon_details_raw);
		$cc_result=tep_db_fetch_array($coupon_details_query);
		$tax_rate=tep_get_tax_rate($cc_result["coupon_tax_class_id"]);
		$amount=tep_add_tax($cc_result["coupon_amount"],$tax_rate);
		if ($cc_result['coupon_type'] == 'P') {
			$coupon_amount=$cc_result['coupon_amount'] . '%';
		} elseif ($cc_result['coupon_type'] == 'S') {
			$coupon_amount=TEXT_FREE_SHIPPING;
		} else {
			$coupon_amount=$currencies->format($amount);
		}
		$jsData->VARS["replace"]=array($this->type. $coupon_id . "name"=>$coupon_name_result['coupon_name'],$this->type . $coupon_id . "amount"=>$coupon_amount,$this->type . $coupon_id . "code"=>$FREQUEST->postvalue('coupon_code'));
		$jsData->VARS["prevAction"]=array('id'=>$coupon_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
		$this->doInfo($coupon_id);
		$jsData->VARS["updateMenu"]=",normal,";
		}
	}
	function doDelete() 
	{
		global $FREQUEST,$jsData;
		$coupon_id=$FREQUEST->postvalue('coupon_id','int',0);
		if ($coupon_id>0){
	   	 	tep_db_query("delete from " . TABLE_COUPONS . "  where coupon_id='".tep_db_input($coupon_id)."'");
			tep_db_query("delete from ". TABLE_COUPONS_DESCRIPTION . " where coupon_id='". tep_db_input($coupon_id) ."'");
			$this->doItems();
			$jsData->VARS["displayMessage"]=array('text'=>TEXT_COUPON_DELETE_SUCCESS);
		} else {
			echo "Err:" . TEXT_SALES_NOT_DELETED;
		}
	}
	function doDeleteCoupons() 
	{
		global $FREQUEST,$jsData;
		$coupon_id=$FREQUEST->getvalue('rID','int',0);

		$delete_message='<p><span class="smallText">' . TEXT_CONFIRM_DELETE . '</span>';
?>
		<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="sales_coupon.php" method="post" enctype="application/x-www-form-urlencoded">
			<input type="hidden" name="coupon_id" value="<?php echo tep_output_string($coupon_id);?>"/>
			<table border="0" cellpadding="2" cellspacing="0" width="100%">
				<tr>
					<td class="main" id="<?php echo $this->type . $coupon_id;?>message">
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
						<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $coupon_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
						<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $coupon_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
					</td>
				</tr>
				<tr>
					<td><hr/></td>
				</tr>
				<tr>
					<td valign="top" class="categoryInfo"><?php echo $this->doInfo($coupon_id);?></td>
				</tr>
			</table>
		</form>
<?php
			$jsData->VARS["updateMenu"]="";
	}
	function doItems()
	{
		global $FREQUEST,$jsData;
		$status=$FREQUEST->getvalue('cID');	
		$template=getListTemplate();
		$rep_array=array("TYPE"=>$this->type,
						"ID"=>-1,
						"NAME"=>HEADING_NEW_TITLE,
						"ORDERID"=>'',
						"AMOUNT"=>'',
						"CODE"=>'',
						"IMAGE_PATH"=>DIR_WS_IMAGES,
						"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
						"UPDATE_RESULT"=>'doTotalResult',
						"ROW_CLICK_GET"=>'Edit',
						"FIRST_MENU_DISPLAY"=>"display:none"
		);
		?>
	<div class="main" id="salCoupon-lmessage"></div>
	<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
		<tr><td><?php echo mergeTemplate($rep_array,$template); ?></td></tr>
		<tr>
			<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%">
					<tr class="dataTableHeadingRow">
						<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="main" width="25%"><b><?php echo COUPON_NAME;?></b></td>
								<td class="main" width="25%"><b><?php echo COUPON_AMOUNT;?></b></td>
								<td class="main" width="20%"><b><?php echo COUPON_CODE;?></b></td>
								<td class="main" width="20%"><b><?php echo COUPON_ORDER;?></b></td>
								<td width="10%">&nbsp;</td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td><div align="center"><?php $this->doList($status);?></div></td>
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
	function doReport() 
	{
		global $FSESSION,$FREQUEST,$jsData;
		$coupon_id=$FREQUEST->getvalue('rID','int',0);
		$cc_query_raw = "select * from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . tep_db_input($coupon_id) . "'";
		//echo $cc_query_raw;
		//$cc_split = new splitPageResultsEvent($FREQUEST->getvalue('page'), MAX_DISPLAY_SEARCH_RESULTS, $cc_query_raw, $cc_query_numrows);
		$cc_query = tep_db_query($cc_query_raw);
		?>
		<table border="0" cellpadding="2" cellspacing="0" width="100%">
			<Tr  height="40">
				<td class='search'><?php echo CUSTOMER_ID;?></td>
				<td class='search'><?php echo CUSTOMER_NAME;?></td>
				<td class='search'><?php echo IP_ADDRESS;?></td>
				<td class='search'><?php echo REDEEM_DATE;?></td>
			</Tr>
		<?php
		if(tep_db_num_rows($cc_query)>0) 
		{
			while ($cc_list = tep_db_fetch_array($cc_query)) 
			{
				$customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $cc_list['customer_id'] . "'");
				$customer = tep_db_fetch_array($customer_query);
				echo '<tr height="20" >'.
						'<td class="search">' . $cc_list['customer_id'] . '</td>'.
						'<td class="search">' . $customer['customers_firstname'] . ' ' . $customer['customers_lastname'] . '</td>'.
						'<Td class="search">' . $cc_list['redeem_ip'] . '</td>'.
						'<Td class="search">' . tep_convert_date_raw($cc_list['redeem_date']) . '</td>'.				
					'</tr>';	
			} 
		}
		else {
			echo '<tr><Td class="search" align="Center" colspan="4">No Report Found</td></tr>';
		}	
		echo '<Tr><td colspan="4" class="search">' . TEXT_REDEMPTIONS .'&nbsp;&nbsp;' .TEXT_REDEMPTIONS_TOTAL . '=' . tep_db_num_rows($cc_query) . '</td></tr>';
		echo '</table>';
		$jsData->VARS["updateMenu"]=",normal,";
	}
	function doEdit() {
		global $FREQUEST,$jsData,$FSESSION;
		$coupon_id=$FREQUEST->getvalue("rID","int",0);
		$languages = tep_get_languages();
 		$tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    	$tax_class_query = tep_db_query("SELECT tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    	while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      		$tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);
   		 }  
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
		  $language_id = $languages[$i]['id'];
		  $coupon_query1 = tep_db_query("select * from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" .  tep_db_input($coupon_id) . "' and language_id = '" . tep_db_input($language_id) . "'");
		  $coupon = tep_db_fetch_array($coupon_query1);
		  $coupon_name[$language_id] = $coupon['coupon_name'];
		  $coupon_desc[$language_id] = $coupon['coupon_description'];
	
		}
		$coupon_query=tep_db_query("select * from " . TABLE_COUPONS . " where coupon_id = '" . tep_db_input($coupon_id) . "'");
		$coupon=tep_db_fetch_array($coupon_query);
		
		$tax_id=$coupon['coupon_tax_class_id'];
		$tax_query=tep_db_query("SELECT tc.tax_class_title,tr.tax_rate from " . TABLE_TAX_CLASS . " tc," . TABLE_TAX_RATES . " tr where tc.tax_class_id='" . tep_db_input($tax_id) . "' and tc.tax_class_id=tr.tax_class_id");
		$tax_result=tep_db_fetch_array($tax_query);
		$tax_rate=$tax_result["tax_rate"];
		$tax_class=$tax_result["tax_class_title"];
		if ($tax_class=="") $tax_class="none";
		$amount=$coupon['coupon_amount'];
		$gross_amount=$amount;
		if ($tax_rate>0) $gross_amount=$amount+($amount*$tax_rate/100);
			$coupon_amount = $coupon['coupon_amount'];
			if ($coupon['coupon_type']=='P') {
			  $coupon_amount .= '%';
              $amount=number_format($coupon_amount, 2, '.', '').'%';
            //  $gross_amount =number_format($gross_amount, 2, '.', '').'%';
              $gross_amount =number_format($coupon_amount, 2, '.', '').'%';
			}
			//if ($coupon['coupon_type']=='S') {
//			  $coupon_free_ship .= true;
//			}
            if ($coupon['coupon_type']!='P') {
               $amount=number_format($amount, 2, '.', '');
               $gross_amount=number_format($gross_amount, 2, '.', '');
            }
			$coupon_min_order = $coupon['coupon_minimum_order'];
			$coupon_code = $coupon['coupon_code'];
			$coupon_uses_coupon = $coupon['uses_per_coupon'];
			$coupon_uses_order = $coupon['uses_per_order'];
			$coupon_uses_user = $coupon['uses_per_user'];
			$coupon_tax_class_id=$coupon['coupon_tax_class_id'];
			$coupon_products = $coupon['restrict_to_products'];
			$coupon_startdate=$coupon['coupon_start_date'];
			$coupon_expiredate=$coupon['coupon_expire_date'];
			$coupon_categories = $coupon['restrict_to_categories'];    
			$coupon_products=$coupon['restrict_to_products'];
			
		echo tep_draw_form('new_coupon','sales_coupon.php', ' ' ,'post','id="new_coupon"');
		echo tep_draw_hidden_field('coupon_id',$coupon_id);
		
	?>
<table border="0" width="100%" cellspacing="0" cellpadding="6">
	  <?php if($coupon_id==""){	  ?>
	  <tr>
				 <td class="main" width="30%">&nbsp;</td>
				 <td class="main" align="right" colspan="2"><a style="cursor:pointer" onClick="do_close();" ><img src="images/template/img_closel.gif" alt="Close" style="border:none"></a></td>
          </tr>
	  	
<?php }
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $language_id = $languages[$i]['id'];
if($coupon_id==''){?>
		<tr>
			<td colspan="2" class="pageHeading"><?php echo TEXT_NEW_COUPON_DETAILS; ?></td>
		</tr>
<?php } ?>		
      <tr>
        <td align="left" class="main" width="30%"><?php if ($i==0) echo COUPON_NAME; ?></td>
        <td align="left" width="30%"><?php echo tep_draw_input_field('coupon_name[' . $languages[$i]['id'] . ']', $coupon_name[$language_id]) . '&nbsp;' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
        <td align="left" class="main" width="40%"><?php if ($i==0) echo COUPON_NAME_HELP; ?></td>
      </tr>
<?php }
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $language_id = $languages[$i]['id'];?>
      <tr>
        <td align="left" valign="top" class="main" width="30%"><?php if ($i==0) echo COUPON_DESC; ?></td>
        <td align="left" valign="top" width="30%"><?php echo tep_draw_textarea_field('coupon_desc[' . $languages[$i]['id'] . ']','physical','24','3', $coupon_desc[$language_id]) . '&nbsp;' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
        <td align="left" valign="top" class="main" width="40%"><?php if ($i==0) echo COUPON_DESC_HELP; ?></td>
      </tr>
<?php }?>
          <tr>
				 <td class="main" width="30%"><?php echo TEXT_COUPON_TAX_CLASS; ?></td>
				 <td class="main" align="left" colspan="2"><?php echo  tep_draw_pull_down_menu('coupon_tax_class_id', $tax_class_array, $coupon_tax_class_id, ' id="coupon_tax_class_id" onChange="javascript:updateCouponGross();"'); ?></td>
          </tr>
          <tr>
				 <td class="main" width="30%"><?php echo TEXT_COUPON_PRICE_NET; ?></td>
				 <td class="main" align="left" width="30%"><?php echo  tep_draw_input_field('coupon_amount', $amount, ' id="coupon_amount" onKeyUp="javascript:updateCouponGross()"'); ?></td>
				 <td align="left" class="main"><?php echo COUPON_AMOUNT_HELP; ?></td>
          </tr>
          <tr>
				 <td class="main" width="30%"><?php echo TEXT_COUPON_PRICE_GROSS; ?></td>
				 <td class="main" align="left" colspan="2"><?php echo tep_draw_input_field('coupon_amount_gross', $gross_amount, ' id="coupon_amount_gross" OnKeyUp="javascript:updateNet()"'); ?></td>
          </tr>
 		  <tr>
				 <td align="left" class="main" width="30%"><?php echo COUPON_MIN_ORDER; ?></td>
				 <td align="left" width="30%"><?php echo tep_draw_input_field('coupon_min_order', $coupon_min_order); ?></td>
				 <td align="left" class="main"><?php echo COUPON_MIN_ORDER_HELP; ?></td>
          </tr>
          <tr>
				<td align="left" class="main" width="30%"><?php echo COUPON_FREE_SHIP; ?></td>
				<td align="left" width="30%"><?php echo tep_draw_checkbox_field('coupon_free_ship', $coupon_free_ship, (($coupon['coupon_type']=='S')?'checked':'')); ?></td>
				<td align="left" class="main"><?php echo COUPON_FREE_SHIP_HELP; ?></td>
          </tr>
           <tr>
				<td align="left" class="main" width="30%"><?php echo TEXT_COUPON_ACTIVE; ?></td>
				<td align="left" width="30%"><?php echo tep_draw_checkbox_field('coupon_active', $coupon_active, (($coupon['coupon_active']=='Y')?'checked':'')); ?></td>
				<td align="left" class="main"><?php  ?></td>
          </tr>
     	 <tr>
				<td align="left" class="main" width="30%"><?php echo COUPON_CODE; ?></td>
				<td align="left" width="30%"><?php echo tep_draw_input_field('coupon_code', $coupon_code,'onBlur=javascript:check_ccode(\'' . $coupon_id . '\',this.value)'); ?></td>
				<td align="left" class="main"><?php echo COUPON_CODE_HELP; ?></td>
      	 </tr>
         <tr>
				<td align="left" class="main" width="30%"><?php echo COUPON_USES_COUPON; ?></td>
				<td align="left" width="30%"><?php echo tep_draw_input_field('coupon_uses_coupon', $coupon_uses_coupon); ?></td>
				<td align="left" class="main"><?php echo COUPON_USES_COUPON_HELP; ?></td>
         </tr>
         <tr>
				<td align="left" class="main" width="30%"><?php echo COUPON_USES_ORDER; ?></td>
				<td align="left" width="30%"><?php echo tep_draw_input_field('coupon_uses_order', $coupon_uses_order); ?></td>
				<td align="left" class="main"><?php echo COUPON_USES_ORDER_HELP; ?></td>
         </tr>
         <tr>
				<td align="left" class="main" width="30%"><?php echo COUPON_USES_USER; ?></td>
				<td align="left" width="30%"><?php echo tep_draw_input_field('coupon_uses_user', $coupon_uses_user,'onBlur="javascript:compare()"'); ?></td>
				<td align="left" class="main"><?php echo COUPON_USES_USER_HELP; ?></td>
        </tr>
        <tr>
<?php
    if (!isset($coupon_startdate)) {
      $coupon_startdate = explode("-", getServerDate());
    } else {
      $coupon_startdate = explode("-", $coupon_startdate);
	//  exit (var_dump($coupon_startdate));
	  
	  
    }
    if (!isset($coupon_expiredate)) {
      $coupon_finishdate = explode("-", getServerDate());
      $coupon_finishdate[0] = $coupon_finishdate[0] + 1;
    } else {
      $coupon_finishdate = explode("-", $coupon_expiredate);
    }

?>

				<td align="left" class="main" width="30%"><?php echo COUPON_STARTDATE; ?></td>
				<td align="left" width="30%"><?php echo tep_draw_date_selector('coupon_startdate', mktime(0,0,0, $coupon_startdate[1], $coupon_startdate[2], $coupon_startdate[0])); ?></td>
				<td align="left" class="main"><?php echo COUPON_STARTDATE_HELP; ?></td>
       </tr>
       <tr>
				<td align="left" class="main" width="30%"><?php echo COUPON_FINISHDATE; ?></td>
				<td align="left" width="30%"><?php echo tep_draw_date_selector('coupon_finishdate', mktime(0,0,0, $coupon_finishdate[1], $coupon_finishdate[2], $coupon_finishdate[0])); ?></td>
				<td align="left" class="main"><?php echo COUPON_FINISHDATE_HELP; ?></td>
      </tr>
	  <?php  $style = "display:none";
	 	    if($coupon_categories !='' || $coupon_products !='')
			{ 
	  		$checked_sel = true;
			$checked_all = false;
	   		$style = "display:''";
			}else{
			$checked_all = true;
			$checked_sel = false;
			}?>

      <tr>
			<td align="left" class="main" width="30%"><?php echo TEXT_COUPON_ALL_APPLY  ; ?></td>
			<td align="left" colspan="2" class="main" width="30%"><?php echo tep_draw_radio_field('coupon_all','',$checked_all,'','onClick="javascript:apply_all()"');  ?> All Categories 
			<?php echo tep_draw_separator('pixel_trans.gif',25,5) . tep_draw_radio_field('coupon_selected','',$checked_sel,'','onClick="javascript:apply_selected()"'); ?> Selected Categories or List</td>
	  </tr>		
	  <tr>
	     <td id="tabstrib" style="display:none" colspan="3">
		 <table border="0" cellpadding="0" cellspacing="0">
		 <tr>	
		 	<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr height="20">
					<td class="tabGap" width="20">&nbsp;</td>
					<td class="tab" id="p" align="center" width="55" onClick="javascript:goto_ajax('p','<?php echo $coupon_categories; ?>','<?php echo $coupon_id; ?>')" onMouseOver="javascript:changeStyle(this,1);" onMouseOut="javascript:changeStyle(this,0);">Product Categories</td>
					<td class="tabGap">&nbsp;</td>
					<td class="tab" id="pi" align="center"  width="55" onClick="goto_ajax('pi','<?php echo $coupon_products; ?>','<?php echo $coupon_id; ?>')" onMouseOver="javascript:changeStyle(this,1);" onMouseOut="javascript:changeStyle(this,0);">Product</td>
					<td class="tabGap">&nbsp;</td>
					<td ><?php echo tep_draw_separator('pixel_trans.gif','68',10);?></td>
				</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td colspan="5" id="gen_id">
			
			</td>
		</tr>  
	</table>
	</td>
	</tr>	
		<?php  
		echo tep_draw_hidden_field('coupon_categories',$coupon_categories); 
		echo tep_draw_hidden_field('coupon_products',$coupon_products);
		echo tep_draw_hidden_field('coupon_alll',$coupon_all);
		$hidden = array("p"=>"$coupon_categories","pi"=>"$coupon_products");
		//FOREACH
		foreach($hidden as $k=>$v){
		  if(tep_not_null($v)){
		  	 $value = $v;
			 $type = $k;
			 break;
		  }
		}
		if($type=='')
			$type='p';
		$param="apply_coupon_categories##" . $type . "##" . $value;	
		$jsData->VARS['doFunc']=array('type'=>'salCoupon','data'=>$param);
		//$jsData->VARS["storePage"]=array('lastAction'=>false,'opened'=>array(),'locked'=>false);
		 ?>
	  <tr><td align="right" width="50%" colspan="3" ><table align="right" border="0" width="40%">
                 <tr>
				 <td align="left" onclick="javascript:return doUpdateAction({'id':'<?php echo $coupon_id; ?>','get':'Preview','result':doDisplayResult,'style':'boxRow','type':'salCoupon','validate':check_form,'uptForm':'new_coupon','customUpdate':doItemUpdate('Preview'),'params':'rID=<?php echo $coupon_id; ?>'});"><?php echo tep_image_button('button_preview.gif',COUPON_BUTTON_PREVIEW); ?></td></tr></table>
				 </td></tr>
      </table>	<?php
		echo '</form>';	
		$jsData->VARS["updateMenu"]=",update,";
		 
	}
	function doEmail() {
		global $FREQUEST,$FSESSION,$jsData;
				
		$coupon_id=$FREQUEST->getvalue('rID','int',0);
			$coupon_query = tep_db_query("select coupon_code from " . TABLE_COUPONS . " where coupon_id = '" . tep_db_input($coupon_id) . "'");
			$coupon_result = tep_db_fetch_array($coupon_query);
			$coupon_name_query = tep_db_query("select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . tep_db_input($coupon_id) . "' and language_id = '" . (int)$FSESSION->languages_id . "'");
			$coupon_name = tep_db_fetch_array($coupon_name_query);
?>
	<?php echo tep_draw_form('mail', 'sales_coupon.php'); 
	echo tep_draw_hidden_field('coupon_id',$coupon_id);
		$jsData->VARS['doFunc']=array('type'=>'salCoupon','data'=>'doEmailEditor');
	?>
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
		<tr>
			<td>
			<table border="0" cellpadding="0" cellspacing="2">
				<tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				</tr>
<?php
			$customers = array();
			$customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
			$customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
			$customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
			$mail_query = tep_db_query("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " order by customers_lastname");
			while($customers_values = tep_db_fetch_array($mail_query)) {
			  $customers[] = array('id' => $customers_values['customers_email_address'],
								   'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');
			}
?>
			<tr>
				<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			</tr>
			<tr>
				<td class="main"><?php echo TEXT_COUPON .':'; ?>&nbsp;&nbsp;</td>
				<td class="main"><?php echo $coupon_name['coupon_name']; ?></td>
			</tr>
			<tr>
				<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			</tr>
			<tr>
				<td class="main"><?php echo TEXT_CUSTOMER; ?>&nbsp;&nbsp;</td>
				<td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers, $FREQUEST->getvalue('customer'));?></td>
			</tr>
			<tr>
				<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			</tr>
			<tr>
				<td class="main"><?php echo TEXT_FROM; ?>&nbsp;&nbsp;</td>
				<td><?php echo tep_draw_input_field('from', EMAIL_FROM); ?></td>
			</tr>
			<tr>
				<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			</tr>
			<tr>
				<td class="main"><?php echo TEXT_SUBJECT; ?>&nbsp;&nbsp;</td>
				<td><?php echo tep_draw_input_field('subject'); ?></td>
			</tr>
			<tr>
				<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			</tr>
			<tr>
				<td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
				<td><?php 
				//we hide the WYSIWYG
				echo '<div style="display:none">';
				echo tep_draw_textarea_field('message', 'soft', '0', '0'); 
				echo '</div>';
				//$message = $FREQUEST->postvalue('message');
				$message .= "\n\n" . TEXT_TO_REDEEM . "\n\n";
				$message .= TEXT_VOUCHER_IS . $coupon_result['coupon_code'] . "\n\n";
				$message .= TEXT_REMEMBER . "\n\n";
				$message .= TEXT_VISIT . "\n\n";
				
				echo $message;
				
				echo '<br><br>'.STORE_OWNER;
				
				echo TEXT_EMAIL_BUTTON_HTML;
				?>
				
				</td>      
			</tr>
			<tr>
				<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			</tr>
			<?php 
			if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Enable')
			{  ?>
			<tr>
				<td colspan="2" align="right"onClick="javascript:return doUpdateAction({'id':<?php echo $coupon_id; ?>,'get':'PreviewEmail','imgUpdate':true,'type':'salCoupon','style':'boxRow','validate':validateEmail,'uptForm':'mail',extraFunc:textEditorRemove,'customUpdate':doMailUpdate('PreviewMail'),'result':doDisplayResult,'params':'rID=<?php echo coupon_id; ?>','message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});">
				<?php 
				
				echo tep_image_button('button_send_email.gif', IMAGE_SEND_EMAIL);
				
				
				// echo tep_image_submit('button_send_email.gif', IMAGE_SEND_EMAIL, 'onClick="validate();return returnVal;"');
						
					//	else {
					//		echo tep_image_button('button_send_email.gif', IMAGE_SEND_EMAIL,'onClick="preview_email('.$coupon_id.')"');
				//}
				
				?>
				</td>
			</tr>
			<?php 
			}
else
{
	?>
			<tr>
				<td colspan="2" align="right"onClick="javascript:return doUpdateAction({'id':<?php echo $coupon_id; ?>,'get':'PreviewEmail','imgUpdate':true,'type':'salCoupon','style':'boxRow','validate':validateEmail,'uptForm':'mail',extraFunc:textEditorRemove,'customUpdate':doMailUpdate('PreviewMail'),'result':doDisplayResult,'params':'rID=<?php echo coupon_id; ?>','message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});">
				<?php 
				
				echo tep_image_button('button_send_email.gif', IMAGE_SEND_EMAIL);
				
				
				// echo tep_image_submit('button_send_email.gif', IMAGE_SEND_EMAIL, 'onClick="validate();return returnVal;"');
						
					//	else {
					//		echo tep_image_button('button_send_email.gif', IMAGE_SEND_EMAIL,'onClick="preview_email('.$coupon_id.')"');
				//}
				
				?>
				</td>
			</tr>
			<?php
}	
			?>
			<tr>
				<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>
<?php	
		$jsData->VARS["updateMenu"]=",update,";
	}	
	function doPreviewMail() {
		global $FREQUEST,$FPOST,$FSESSION,$jsData;
		
		$coupon_id=$FREQUEST->postvalue('coupon_id','int',0);
		$coupon_query = tep_db_query("select coupon_code from " .TABLE_COUPONS . " where coupon_id = '" . tep_db_input($coupon_id) . "'");
		$coupon_result = tep_db_fetch_array($coupon_query);
		$coupon_name_query = tep_db_query("select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . tep_db_input($coupon_id) . "' and language_id = '" . (int)$FSESSION->languages_id . "'");
		$coupon_name = tep_db_fetch_array($coupon_name_query);
	
	    switch ($FREQUEST->postvalue('customers_email_address')) 
		{
    	case '***':
	      $mail_sent_to = TEXT_ALL_CUSTOMERS;
    	  break;
	    case '**D':
    	  $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
	      break;
	    default:
    	  $mail_sent_to = $FREQUEST->postvalue('customers_email_address');
	      break;
    	}
	echo tep_draw_form('preview_mail', 'sales_coupon.php'); 
	?>
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
		<tr>
			<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				</tr>
				<tr>
					<td class="smallText"><b><?php echo TEXT_CUSTOMER; ?></b><br><?php echo $mail_sent_to; ?></td>
				</tr>
				<tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				</tr>
				<tr>
					<td class="smallText"><b><?php echo TEXT_COUPON; ?></b><br><?php echo $coupon_name['coupon_name']; ?></td>
				</tr>
				<tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				</tr>
				<tr>
					<td class="smallText"><b><?php echo TEXT_FROM; ?></b><br><?php echo htmlspecialchars(stripslashes($FREQUEST->postvalue('from'))); ?></td>
				</tr>
				<tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				</tr>
				<tr>
					<td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo htmlspecialchars(stripslashes($FREQUEST->postvalue('subject'))); ?></td>
				</tr>
				<tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				</tr>
				<tr>
					<td class="smallText"><b>
				<?php 

				if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Enable') 
				{ 
				echo (stripslashes($FREQUEST->postvalue('message'))); 
				} 
				else 
				{ 

			    echo htmlspecialchars(stripslashes($FREQUEST->postvalue('message'))); 
				} 
				?></td>
				</tr>
				<tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				</tr>
				<tr>
					<td>
					<?php
					/* Re-Post all POST'ed variables */
					reset($FPOST);
					//while (list($key, $value) = each($FPOST)) 
						foreach($FPOST as $key => $value)
					{
					  if (!is_array($FREQUEST->postvalue($key))) 
					  {
						echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
					  }
					}
					?>
					<table border="0" width="100%" cellpadding="0" cellspacing="2">
						<tr>
							<td colspan="2"><?php ?>&nbsp;</td>
						<tr>	
						<tr>
							<td align="right" colspan="2">
								<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':<?php echo $coupon_id; ?>,'get':'Edit','type':'salCoupon','style':'boxRow'});"><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>
								<a href="javascript:void(0)" onclick="javascript:return doUpdateAction({'id':'<?php echo $coupon_id; ?>','get':'SendMail','result':doTotalResult,'style':'boxRow','type':'salCoupon','uptForm':'preview_mail','customUpdate':doMailUpdate('SendMail'),'params':'rID=<?php echo $coupon_id; ?>'});"> <?php echo tep_image_button('button_send_email.gif', IMAGE_SEND_EMAIL); ?></a>
							</td>
						</tr>
						<Tr>
							<td class="smallText" colspan="2">
							<?php 
							if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Disable')
							{
								?>
									<a href="javascript:void(0)"  onClick="javascript:return doCancelAction({'id':<?php echo $coupon_id; ?>,'get':'Edit','type':'salCoupon','style':'boxRow'});"><?php tep_image_button('button_back.gif', IMAGE_BACK, 'name="back"'); ?></a>
							<?php } ?>
							<?php 
							if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Disable') 
							{
									echo(TEXT_EMAIL_BUTTON_HTML);
								  } 
								  else 
								  { 
								  	echo(TEXT_EMAIL_BUTTON_TEXT); 
								  } ?>
							</td>
						</tr>
						<tr>
							<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
						</tr>
					</table>
					</td>
				</tr>
            </table>
			</td>
         </tr>
	</table>	 
<?php		
	echo '</form>';
	$jsData->VARS["updateMenu"]=",update,";
	}
	function doSendMail() 
	{
		global $FREQUEST,$jsData;
		$coupon_id=$FREQUEST->postvalue('coupon_id');
		//$jsData->VARS['doFunc']=array('type'=>'salCoupon','data'=>'textEditorRemove');
		if($FREQUEST->postvalue('customers_email_address')){
			switch ($FREQUEST->postvalue('customers_email_address')) {
				case '***':
					$mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS);
					$mail_sent_to = TEXT_ALL_CUSTOMERS;
				break;
				case '**D':
					$mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
					$mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
				break;
				default:
					$customers_email_address = $FREQUEST->postvalue('customers_email_address');
					$mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($customers_email_address) . "'");
					$mail_sent_to = $FREQUEST->postvalue('customers_email_address');
				break;
			}
		$coupon_query = tep_db_query("select coupon_code from " . TABLE_COUPONS . " where coupon_id = '" . tep_db_input($coupon_id) . "'");
		$coupon_result = tep_db_fetch_array($coupon_query);
		$coupon_name_query = tep_db_query("select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . tep_db_input($coupon_id) . "' and language_id = '" . (int)$FSESSION->languages_id . "'");
		$coupon_name = tep_db_fetch_array($coupon_name_query);

		$from = $FREQUEST->postvalue('from');
		$subject = $FREQUEST->postvalue('subject');
			while ($mail = tep_db_fetch_array($mail_query)) 
			{
				$message = $FREQUEST->postvalue('message');
				$message .= "\n\n" . TEXT_TO_REDEEM . "\n\n";
				$message .= TEXT_VOUCHER_IS . $coupon_result['coupon_code'] . "\n\n";
				$message .= TEXT_REMEMBER . "\n\n";
				$message .= TEXT_VISIT . "\n\n";
				$message .= '<br>,br>'.STORE_OWNER;
	
				//Let's build a message object using the email class
				$mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
				// add the message to the object
	
				// MaxiDVD Added Line For WYSIWYG HTML Area: BOF (Send TEXT Email when WYSIWYG Disabled)
				
				// if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Disable') 
				// {
					// $mimemessage->add_text($message);
				// } else {
					$mimemessage->add_html($message);
				//}
				// MaxiDVD Added Line For WYSIWYG HTML Area: EOF (Send HTML Email when WYSIWYG Enabled)
				$mimemessage->build_message();    
				$mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], '', $from, $subject);
			}
			$this->doItems();
			$jsData->VARS["displayMessage"]=array('text'=>TEXT_SEND_EMAIL_SUCCESS);
			$jsData->VARS["updateMenu"]=",normal,";
		}else{
			echo 'Err:' . 'Not_sent';
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
					<td width="2%" id="salCoupon##ID##bullet">##STATUS##</td>
					<td width="22%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##name">##NAME##</td>
					<td width="20%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##amount">##AMOUNT##</td>
					<td width="20%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##code">##CODE##</td>
					<td width="20%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##code">##ORDERID##</td>
					<td width="15%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
						<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
						<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
						<img src="##IMAGE_PATH##template/img_bar.gif"/>
						<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteCoupons','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
						<img src="##IMAGE_PATH##template/img_bar.gif"/>
						<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'Email','result':doDisplayResult,'style':'boxRow','type':'salCoupon','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/img_sms.gif" title="Email"/></a>
						<img src="##IMAGE_PATH##template/img_bar.gif"/>
						<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'Report','result':doDisplayResult,'style':'boxRow','type':'salCoupon','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/copy_blue.gif" title="Report"/></a>
						<img src="##IMAGE_PATH##template/img_bar.gif"/>
						</span>
						<span id="##TYPE####ID##mupdate" style="display:none">
						<!--<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','imgUpdate':true,'type':'salCoupon','style':'boxRow','uptForm':'preview_coupon','customUpdate':doItemUpdate('Update'),'result':doTotalResult,'params':'rID=##ID##','message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
						<img src="##IMAGE_PATH##template/img_bar.gif"/> -->
						<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'salCoupon','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
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
								<table cellpadding="2" cellspacing="0" width="75%">
									<tr>
										<td class="main" align="left">##ENT_COUPON_NAME##</td>
										<Td class="main" align="left">##COUPON_NAME##</Td>
									</tr>
                                    									<tr>
										<td class="main" align="left">##ENT_COUPON_ACTIVE##</td>
										<Td class="main" align="left">##COUPON_ACTIVE##</Td>
									</tr>
									<tr>
										<td class="main" align="left">##ENT_START_DATE##</td>
										<Td class="main" align="left">##START_DATE##</Td>
									</tr>
									<tr>
										<td class="main" align="left">##ENT_USES_PER_COUPON##</td>
										<Td class="main" align="left">##USES_PER_COUPON##</Td>
									</tr>
									<tr>
										<td class="main" align="left">##ENT_DATE_CREATED##</td>
										<Td class="main" align="left">##DATE_CREATED##</Td>
									</tr>
									<tr>
										<td class="main" align="left">##ENT_APPLY_COUPON##</td>
										<Td class="main" align="left">##APPLY_COUPON##</Td>
									</tr>
									<tr ##VIEW_PRODUCT##>
										<td class="main" align="left">##ENT_PRODUCT_LIST##</td>
										<Td class="main" align="left">##VIEW_PRODUCT_LIST##</Td>
									</tr>
									<tr ##VIEW_PRODUCT_CATEGORIES##>
										<td class="main" align="left">##ENT_PRODUCT_CAT_LIST##</td>
										<Td class="main" align="left">##VIEW_PRODUCT_CAT_LIST##</Td>
									</tr>
								</table>
							</td>
							<td valign="top">
								<table cellpadding="2" border="0" cellspacing="0" width="75%">
									<tr>
										<td class="main" align="left">##ENT_COUPON_AMOUNT##</td>
										<Td class="main" align="left">##COUPON_AMOUNT##</Td>
									</tr>
									<tr>
										<td class="main" align="left">##ENT_END_DATE##</td>
										<Td class="main" align="left">##END_DATE##</Td>
									</tr>
									<tr>
										<td class="main" align="left">##ENT_USES_PER_CUSTOMER##</td>
										<Td class="main" align="left">##USES_PER_CUSTOMER##</Td>
									</tr>
									<tr>
										<td class="main" align="left">##ENT_DATE_MODIFIED##</td>
										<Td class="main" align="left">##DATE_MODIFIED##</Td>
									</tr>
									<tr>
										<Td colspan="2">&nbsp;</Td>
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
 function display_categories($type,$prev_ids=''){
	$category = array('id'=>0,'text'=>'select');
	$prev_len = count($prev_ids);
	switch ($type){
		case 'p': 
		
		$category_qry=tep_db_query("SELECT * FROM categories, categories_description WHERE categories.categories_id = categories_description.categories_id and categories_description.language_id = 1 ORDER BY categories.sort_order");
		while($category_result = tep_db_fetch_array($category_qry))
		{
			if($category_result['parent_id']>0)
			{
			$parent='['.$category_result['parent_id'].']';
			}
			
			$parent_qry=tep_db_query("SELECT categories_id FROM categories WHERE categories_id=".$category_result['categories_id']."");
			while($parent_result = tep_db_fetch_array($parent_qry))
			{
				if($category_result['parent_id']==0)
				{
				$show_id=' ['.$parent_result['categories_id'].'] ';
				}else
				{
				$show_id='';	
				}
			}
			$category[] = array('id'=>$category_result['categories_id'],'text'=> $show_id.$category_result['categories_name'] . ' ' .$parent);
		}
		break;
	}	
 ?>
<table class="tabContent"  border="0" cellpadding="0" cellspacing="0" width="90%">
	<tr><td class="main">
		<table border="0"  cellpadding="0" cellspacing="0" width="100%">
			<tr height="10">
				<td colspan="7"><?php echo tep_draw_separator('pixel_trans.gif','100%',5); ?></td>
			</tr>	
			<tr>
				<td width="10"></td>
				<td class="tab_strib" align="center" >Categories Available</td>
				<td width="10"></td>
				<td class="tab_strib" align="center" ></td>
				<td width="10"></td>
				<td class="tab_strib" align="center" >Selected Categories</td>
				<td width="10"></td>
			</tr>
			<tr>
				<td colspan="7"><?php echo tep_draw_separator('pixel_trans.gif','100%',5); ?></td>
			</tr>
			<?php 
				if($prev_ids != ""){
					$prev_selected = array('id'=>0,'text'=>'select');
					$avail_len = count($category); 
					for($icnt=0;$icnt<$avail_len;$icnt++)	{
					    for($jcnt=0;$jcnt<$prev_len;$jcnt++){
						 	if($category[$icnt]['id']==$prev_ids[$jcnt]){
								$prev_selected[] = array('id'=>$category[$icnt]['id'],'text'=>$category[$icnt]['text']);
								unset($category[$icnt]['id'],$category[$icnt]['text']);            
							}
						}
					}
				}
				else{
				   $default = array('id'=>0,'text'=>'select');
				   $default[] = array('id'=>$category[0]['id'],'text'=>$category[0]['text']);
				}
			?>
		 <tr>
		    <td width="10">
		    <td valign="top" ><?php  echo tep_draw_mselect_menu('cat_coupon_avail',$category,$default,'id=cat_coupon_avail valign=top style="width:455px;height:500px"'); ?></td>
			<td width="10"></td>
			<td align="center" ><input size="4" type="button" value="&nbsp;>&nbsp;" onClick="javascript:load_selected('<?php echo $type; ?>')"><br>
			<input size="4" type="button" value="&nbsp;<&nbsp;" onClick="javascript:remove_selected('<?php echo $type; ?>')"><br>
			<input size="4" type="button" value=">>" onClick="javascript:load_all('<?php echo $type; ?>')"><br>
			<input size="4" type="button" value="<<" onClick="javascript:remove_all('<?php echo $type; ?>')"><br></td>
			<td width="10"></td>
			<?php if($prev_ids != ""){
			//print_r($prev_selected);
			?>
				<td align="right"><?php echo tep_draw_mselect_menu('category',$prev_selected,$prev_selected,'id=category valign=top style="width:455px;height:500px"');
					} else {?></td>
					<td align="right"><select name="category" id="category" multiple="multiple" style="width:455px;height:500px"></select> </td>
				 	<?php } ?>
					<td width="10"></td>
		</tr>
		<tr height="20"><td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif',5,5); ?></td></tr>
	 </table>
<?php } 
function display_items($type,$prev_ids=''){?>
 <?php //preparing the data to load listbox 
	$items = array('id'=>0,'text'=>'SELECT');
	$prev_len = count($prev_ids);
	switch ($type){
		case 'pi': 
			$items_qry=tep_db_query("SELECT * FROM products, products_description WHERE products.products_id = products_description.products_id and products_sku>'0' and products_description.language_id = '" . 1 . "' ORDER BY products.products_id"); //products_description.products_name
			//echo "SELECT * FROM products, products_description WHERE products.products_id = products_description.products_id and products_description.language_id = '" . 1 . "' ORDER BY products_description.products_name"; 
			while($items_result=tep_db_fetch_array($items_qry)){
				  $items[] = array('id'=>$items_result['products_id'],'text'=>$items_result['products_name']);	
			}
		break;
	}	
 ?>
<table class="tabContent" border="0" cellpadding="0" cellspacing="0" width="90%" >
  <tr> 
    <td class="main"><table border="0" cellpadding="0" cellspacing="0" width="100%">
	     <tr height="10"><td colspan="7"><?php echo tep_draw_separator('pixel_trans.gif','100%',5); ?></td></tr>
		 <tr>
			<td width="10"></td>
			<td class="tab_strib" align="center" >Items Available</td>
			<td class="tab_strib" align="center" width="10"> </td>
			<td class="tab_strib" align="center" ></td>
			<td class="tab_strib" align="center" width="10"> </td>
			<td class="tab_strib" align="center" >Selected Items</td>
			<td width="10"></td>
		</tr>
		<tr><td colspan="7"><?php echo tep_draw_separator('pixel_trans.gif','100%',5); ?></td></tr>
			 <?php if($prev_ids != ""){  
						$prev_selected = array('id'=>0,'text'=>'select');
						$avail_len = count($items); 
						for($icnt=0;$icnt<$avail_len;$icnt++)	{
							for($jcnt=0;$jcnt<$prev_len;$jcnt++){
						 		if($items[$icnt]['id']==$prev_ids[$jcnt]){
									$prev_selected[] = array('id'=>$items[$icnt]['id'],'text'=>$items[$icnt]['text']);
							 		unset($items[$icnt]['id'],$items[$icnt]['text']);		
								} 
							}
						}
					}
					else{
					   $default = array('id'=>0,'text'=>'select');
					   $default[] = array('id'=>$items[0]['id'],'text'=>$items[0]['text']);
					}
				?> 
		<tr>
			<td width="10"></td>
			<td valign="top" ><?php  echo tep_draw_mselect_menu('items_coupon_avail',$items,$default,'id=items_coupon_avail valign=top style="width:455px;height:500px"'); ?></td>
			<td width="10"></td>
			<td align="center" ><input size="4" type="button" value="&nbsp;>&nbsp;" onClick="javascript:load_selected_items('<?php echo $type; ?>')"><br>
			<input size="4" type="button" value="&nbsp;<&nbsp;" onClick="javascript:remove_selected_items('<?php echo $type; ?>')"><br>
			<input size="4" type="button" value=">>" onClick="javascript:load_all_items('<?php echo $type; ?>')"><br>
			<input size="4" type="button" value="<<" onClick="javascript:remove_all_items('<?php echo $type; ?>')"></td>
			<td width="10"></td>
			<?php if($prev_ids != ""){ ?>
					<td align="right"><?php echo tep_draw_mselect_menu('items',$prev_selected,$prev_selected,'id=items valign=top style="width:455px;height:500px"');
				} else {?></td>
					<td align="right"><select name="items" id="items" style="width:455px;height:500px" multiple="multiple"></select></td>
			<?php } ?>
			<td class="" width="10"></td>
		</tr>
		<tr height="20"><td colspan="5"><?php echo tep_draw_separator('pixel_trans.gif',5,5); ?></td>
	</table>
<?php } ?>
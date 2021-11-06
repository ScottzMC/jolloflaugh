<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	defined('_FEXEC') or die();
	class customersDetail
	{
		var $pagination;
		var $splitResult;
		var $type;
		
		####################################################################
 function doExportData(){
	     global $FREQUEST,$jsData,$FSESSION;   
		 $customer_id=$FREQUEST->getvalue('rID','int','');

		$customers_language='english';
				//$customers_language='czech';
					include(DIR_WS_LANGUAGES . $customers_language . '/templates.php');
#######################################
#    Customers Table First            #
#######################################

$exportSQL = "select * from customers where customers_id =  " .$customer_id;

$exportQuery = tep_db_query($exportSQL);



	
######################################
#         CSV Header                 #
######################################

$cust[]  = array(STORE_NAME . ': '.TEXT_GDPR_REQUEST);
$cust[]  = array(TEXT_GDPR_NO_DATA);
$cust[]  = array('=============================================================');
######################################
#         Customers table            #
######################################
if(tep_db_num_rows($exportQuery) > 0 ) {
	
$cust[]  = array(TEXT_GDPR_DETAILS);

$cust[]  = array('=============================================================');
	
    
   while($p = tep_db_fetch_array($exportQuery) ){
	   
    // tidy up some entries
    if ($p['customers_dob']	== "0000-00-00 00:00:00"){
		$p['customers_dob'] ='';
	}
         $cust[]  = array (TEXT_GDPR_CUSTOMER_NUMBER,$p['customers_id']);
         $cust[]  = array (TEXT_GDPR_FIRSTNAME, $p['customers_firstname']);
		 $cust[]  = array (TEXT_GDPR_LASTNAME, $p['customers_lastname']);
		 $cust[]  = array (TEXT_GDPR_GENDER, $p['customers_gender']);
		 $cust[]  = array (TEXT_GDPR_DOB, $p['customers_dob']);
		 $cust[]  = array (TEXT_GDPR_EMAIL, $p['customers_email_address']);
		 $cust[]  = array (TEXT_GDPR_SECOND_EMAIL, $p['customers_second_email_address']);
		 $cust[]  = array (TEXT_GDPR_TELEPHONE, $p['customers_telephone']);
		 $cust[]  = array (TEXT_GDPR_MOBILE, $p['customers_fax']);
		 $cust[]  = array (TEXT_GDPR_SECOND_TELEPHONE, $p['customers_second_telephone']);
		 $cust[]  = array (TEXT_GDPR_USERNAME, $p['customers_username']);
		 

		
		$send_details[0]['to_name'] = STORE_OWNER;
		$send_details[0]['to_email'] = STORE_OWNER_EMAIL_ADDRESS;
		$send_details[0]['from_name']=STORE_OWNER;
		$send_details[0]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
		$send_details[1]['to_name'] = $p['customers_firstname'] . ' ' .  $p['customers_lastname'];
		$send_details[1]['to_email'] =  $p['customers_email_address'];
		$send_details[1]['from_name']=STORE_OWNER;
		$send_details[1]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
		
		$merge_details[] = '';
                       
    }
}else{
	 $cust[]  = array(TEXT_GDPR_NO_DATA_HELD);
}
$cust[]  = array('=============================================================');

######################################
#         Addresses		             #
######################################

$cust[]  = array(TEXT_GDPR_ADDRESSES);

$cust[]  = array('=============================================================');

  $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "' order by firstname, lastname");
  if(tep_db_num_rows($addresses_query) > 0 ) 
  {  
  
  while ($addresses = tep_db_fetch_array($addresses_query)) 
  { 
		$format_id = tep_get_address_format_id($addresses['country_id']);		
		$cust[] =   array(tep_address_format($format_id, $addresses, true, ' ', '  '));             
     }
  }else
  {
	$cust[]  = array(TEXT_GDPR_NO_DATA_HELD);
  }
$cust[]  = array('=============================================================');
######################################
#        Orders                      #
######################################
$cust[]  = array(TEXT_GDPR_ORDERS);

$cust[]  = array('=============================================================');

      $history_query_raw =  tep_db_query("select * from " . TABLE_ORDERS . "  where customers_id = '" . $customer_id ."' order by orders_id DESC");

 if(tep_db_num_rows($history_query_raw) > 0 ) 
 {
   


    while ($history = tep_db_fetch_array($history_query_raw)) {

      require_once(DIR_WS_CLASSES . 'order.php'); 
      $order = new order($history['orders_id']);
	  
		$cust[]  = array(TEXT_GDPR_ORDER_NUMBER, $history['orders_id']);
        $cust[]  = array(TEXT_GDPR_DATE,format_date($history['date_purchased']));
		$cust[]  = array(TEXT_GDPR_STATUS, $order->info['orders_status'] );
		$cust[]  = array(TEXT_GDPR_ORDER_TOTAL, $order->info['total']);
		  if ($order->delivery != false) {
			  	if(($order->delivery['format_id'])>0){
                     $address = tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '  ');
						} else { 
					 $address = tep_address_format($order->customer['format_id'], $order->customer, 1, ' ', '  '); 
					 }	
		$cust[]  = array(TEXT_GDPR_ORDER_ADDRESSES, $address);
		
		  }
		$cust[]  = array(TEXT_GDPR_BILLING_ADDRESS,  tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '  '));
			if($order->info['ip_address']!=''){
		$cust[]  = array(TEXT_GDPR_IP_ADDRESS,$order->info['ip_address']); 		
			  }
			 if($order->info['reference_id']!=''){
		$cust[]  = array(TEXT_GDPR_REFERENCE,$order->info['reference_id']); 		
			  }
			 if($order->info['cc_type']!=''){
		$cust[]  = array(TEXT_GDPR_CCT,$order->info['cc_type']); 		
			  }
			 if($order->info['cc_owner']!=''){
		$cust[]  = array(TEXT_GDPR_CCO,$order->info['cc_owner']); 		
			  }
			 if($order->info['cc_number']!=''){
		$cust[]  = array(TEXT_GDPR_CCN,$order->info['cc_number']); 		
			  }			  
			 if($order->info['cc_expires']!=''){
		$cust[]  = array(TEXT_GDPR_CCE,$order->info['cc_expires']); 		
			  }				  

		$cust[]  = array(TEXT_GDPR_ORDER_NOTES);
		
		$statuses_query = tep_db_query("select os.orders_status_name, osh.date_added, osh.comments, osh.field_1, osh.field_2, osh.field_3, osh.field_4, osh.other from " . TABLE_ORDERS_STATUS . " os, " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.orders_id = '" . $history['orders_id'] . "' and osh.orders_status_id = os.orders_status_id order by osh.date_added");
		  while ($statuses = tep_db_fetch_array($statuses_query)) 
		  {
		  if (EXTRA_FIELDS == 'yes'){
			  $cust[]  = array('', format_date($statuses['date_added']) . '. Status: ' . $statuses['orders_status_name'] . '. Comments: ' 
			  . (empty($statuses['comments']) ? '&nbsp;' : '' . HEADING_COMMENT . ':&nbsp;'.nl2br(tep_output_string($statuses['comments'])))
			  . (empty($statuses['field_1']) ? '&nbsp;' : '' . FIELD_1 . ':&nbsp;'.nl2br(tep_output_string($statuses['field_1']))) 
			  . (empty($statuses['field_2']) ? '&nbsp;' : '' . FIELD_2 . ':&nbsp;'.nl2br(tep_output_string($statuses['field_2']))) 
			  . (empty($statuses['field_3']) ? '&nbsp;' : '' . FIELD_3 . ':&nbsp;'.nl2br(tep_output_string($statuses['field_3']))) 
			  . (empty($statuses['field_4']) ? '&nbsp;' : '' . FIELD_4 . ':&nbsp;'.nl2br(tep_output_string($statuses['field_4']))) 
			  . (empty($statuses['other']) ? '&nbsp;' : '' . FIELD_5 . ':&nbsp;'.nl2br(tep_output_string($statuses['other']))));
		}else{
				$cust[]  = array('', format_date($statuses['date_added']) . '. Status: ' . $statuses['orders_status_name'] . '. Comments: ' .tep_output_string($statuses['comments']));
				}
		}
		$cust[]  = array('');
		if(table_exists('stripe_data')) {
	$exportSQL = "select * from stripe_data where orders_id =  " .$history['orders_id'];

    $exportQuery = tep_db_query($exportSQL);


if(tep_db_num_rows($exportQuery) > 0 ) {
	
$cust[]  = array(TEXT_GDPR_STRIPE);


	
    
   while($p = tep_db_fetch_array($exportQuery) ){
	   
         $cust[]  = array (TEXT_GDPR_STRIPE_REF,$p['stripe_charge_id']);
         $cust[]  = array (TEXT_GDPR_STRIPE_DESC, $p['stripe_description']);
		 $cust[]  = array (TEXT_GDPR_CARD_NAME, $p['stripe_name']);
		 $cust[]  = array (TEXT_GDPR_CARD_LAST4, $p['stripe_last4']);
		 $cust[]  = array (TEXT_GDPR_CARD_EXP, $p['stripe_exp_month'] . '/'.$p['stripe_exp_year']);
		 $cust[]  = array (TEXT_GDPR_ADDRESS1, $p['stripe_address_line1']);
		 $cust[]  = array (TEXT_GDPR_ADDRESS2, $p['stripe_address_line2']);
		 $cust[]  = array (TEXT_GDPR_CITY, $p['stripe_address_city']);
		 $cust[]  = array (TEXT_GDPR_COUNTRY, $p['stripe_address_country']);
		 $cust[]  = array (TEXT_GDPR_ZIP, $p['stripe_address_zip']);
		 
                       
    }
}	
}//end stripe data
		
    }
  }else{
	$cust[]  = array(TEXT_GDPR_NO_DATA_HELD);
	}
$cust[]  = array('=============================================================');

// in admin area this is called via Ajax therefore
// we must create a temp file for ouput

$name = tempnam('tmp', 'csv');
//$name ="GDPR_Request.csv";
$handle = fopen($name, 'w');

foreach($cust as $customer) {

    fputcsv($handle, $customer);
}

fclose($handle);
// do stuff with the file


		//send mails to given addresses
		for ($icnt=0;$icnt<sizeof($send_details);$icnt++){
			$details['to_name']=$send_details[$icnt]['to_name'];
			$details['to_email']=$send_details[$icnt]['to_email'];
			$details['from_name']=$send_details[$icnt]['from_name'];
			$details['from_email']=$send_details[$icnt]['from_email'];
				
		//}
		
		$details['text'] = TEXT_GDPR_TEXT;
		$details['subject'] = TEXT_GDPR_SUBJECT;
		
		// this code lifted from the tep_mail() function
		// as that function will only send pdf
		 $message = new email(array('X-Mailer: osConcert'));
			
		 $message->add_text($details['text']);
		     	  

		//new code pdf send - changed to csv
		if( isset($name) && $name !==''){ 
			  $message->add_attachment($message->get_file($name),'data.csv','application/csv');
			  }
		  
		 $message->build_message();
		  if(strpos($details['to_email'],',')!==false) {
                 $to_email=array();
                 $to_email=explode(",",$details['to_email']);
                 $details['to_email']=$to_email;
             }
			 
		
		 $result=$message->send($details['to_name'], $details['to_email'], $details['from_name'], $details['from_email'], $details['subject']);
		 unset($message);
 }

        tep_db_query("update " . TABLE_CUSTOMERS . " set idcards_printed = 'N' where customers_id = '" . $customer_id . "'");
		
		if ($result == 1){
			echo 'Email sent';
		}else{
			echo 'Email not sent';
		}

readfile($name);
// delete the file
unlink($name);


	 }
	 #############################################################################
		
		
		
		
		

        function __construct() {
			$this->pagination=false;
			$this->splitResult=true;
			$this->type = 'custord';
		}
		 
		function doDeleteCustomer()
		{
			global $FREQUEST,$jsData,$FSESSION;            
			$customers_id=$FREQUEST->getvalue('rID','int',0);
			$delete_message='<p><span class="smallText">' . TEXT_DELETE_INTRO . '</span>';
			?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="products_manufacturers.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="customers_id" value="<?php echo tep_output_string($customers_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
				  <tr>
					<td class="main" id="<?php echo $this->type . $customers_id;?>message"></td>
				  </tr>
				  <tr>
					<td class="main">
					  <?php echo $delete_message;?>
					</td>
				  </tr>
				  <tr height="40">
					<td class="main" style="vertical-align:bottom">
					   <p>
						  <a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $customers_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['ORD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $customers_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</p>  
					</td>
				  </tr>
				  <tr>
					<td><hr/></td>
				  </tr>
				  <tr>
					<td valign="top" class="categoryInfo"><?php echo $this->doInfo($customers_id);?></td>
				  </tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
            
		}//function doDeleteGroups ends		
		
		function doDelete()
		{
			global $FREQUEST,$jsData;				
			$customers_id=$FREQUEST->postvalue('customer_id','int',0);
            $delete_reviews=$FREQUEST->postvalue('delete_reviews','','');
			$delete_orders = $FREQUEST->postvalue('delete_orders','','');
            if ($customers_id>0) {
					
				if (isset($delete_orders) && ($delete_orders == 'on')) {
					tep_db_query("delete from " . TABLE_ORDERS. " where customers_id = '" . (int)$customers_id . "'");
				}
				tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "'");
        		tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
        		tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customers_id . "'");
        		tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customers_id . "'");
        		//tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customers_id . "'");
        		tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . (int)$customers_id . "'");
                $this->doGetCustomersDetail();
			    $jsData->VARS["displayMessage"]=array('text'=>TEXT_DELETE_SUCCESS);
                $jsData->VARS["doFunc"]=array('type'=>'custord','data'=>'hide');
			    tep_reset_seo_cache('customers');
            } else $jsData->VARS["displayMessage"]=array('text'=>TEXT_CUSTOMER_NOT_DELETED);


		}//function	doDelete ends


        function doDeleteDisplay(){
			global $FREQUEST,$jsData,$FSESSION;
			$customer_id=$FREQUEST->getvalue('rID','int',0);


           
            $child_query=tep_db_query("SELECT count(*) as total_count from " . TABLE_ORDERS . " where customers_id='" . (int)$customer_id . "'");
            $child_count=tep_db_fetch_array($child_query);

			if($child_count['total_count']<=0) {
					$form_elements=tep_draw_checkbox_field('delete_reviews', 'on', true).sprintf(TEXT_DELETE_REVIEWS, $reviews['number_of_reviews']);
				//$form_elements=tep_draw_checkbox_field('delete_reviews', 'on', true).sprintf('', ''['']);
				$delete_message='<p><span class="smallText">' . TEXT_DELETE_INTRO . '</span>';
			} else {
				
						$child_query_new=tep_db_query("SELECT *  from " . TABLE_ORDERS . " where customers_id='" . (int)$customer_id . "' order by orders_id desc LIMIT 1");
						$child=tep_db_fetch_array($child_query_new );
					    $form_elements=tep_draw_checkbox_field('delete_reviews', 'on', true).sprintf(TEXT_DELETE_REVIEWS, $reviews['number_of_reviews']);
						$form_elements .= "<br>".tep_draw_checkbox_field('delete_orders', 'on', true). TEXT_INFO_CHILD_YESNO;
					
					   $delete_message='<p><span class="smallText">'.TEXT_INFO_CHILD_COUNT.$child_count['total_count'] .TEXT_INFO_CHILD_LAST. date(EVENTS_DATE_FORMAT,strtotime($child['date_purchased'])).  '</span>';
					}
?>
			<form  name="custordDeleteSubmit" id="custordDeleteSubmit" action="customers_mainpage.php" method="post">
                <input type="hidden" name="customer_id" value="<?php echo tep_output_string($customer_id);?>"/>
                <table border="0" cellpadding="2" cellspacing="0" width="100%">
                    <tr>
                        <td class="main" id="eve<?php echo $event_id;?>message">
                        </td>
                    </tr>
                    <tr>
                        <td class="main">
                        <?php echo $delete_message;?>
                        </td>
                    </tr>
                    <?php if ($form_elements!='') { ?>
                    <tr>
                        <td class="main">
                            <?php echo $form_elements;?>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr height="40">
                        <td class="main" style="vertical-align:bottom">
                            <p>
                            <?php if ($form_elements!='' && $child_num_rows<=0 ) { ?>
                            <a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $customer_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['EVE_DELETING'],'uptForm':'custordDeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
                            <?php } ?>
                            <a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $customer_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
                        </td>
                    </tr>
                    <tr>
                        <td><hr/></td>
                    </tr>
                    <tr>
                        <td valign="top" class="categoryInfo"><?php echo $this->doInfo($customer_id);?></td>
                    </tr>
                </table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}
		
		function doCustomersList($search='',$order,$page='') 
		{
			global $FSESSION,$FREQUEST,$jsData;
       		if($page=='')
				$page=$FREQUEST->getvalue('page','int',1);
		
			//$page=$FREQUEST->getvalue('page','int',1);
			//echo tep_draw_form('frm_customer', FILENAME_CUSTOMERS);

            $cID=$FREQUEST->getvalue('cID','int',0);
			if($FREQUEST->getvalue('rID','string','')){
				$cID=$FREQUEST->getvalue('rID','string','');
			}
			if($cID>0)$search.=' and c.customers_id='.$cID;
            $query_split=false;
			if($order == 0) {
			    $customers_sql = "select c.*, c.customers_id, LTRIM(c.customers_lastname) as customers_lastname, LTRIM(c.customers_firstname) as customers_firstname,LTRIM(c.customers_email_address) as customers_email_address, a.entry_country_id  from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id " . $search . " order by c.is_blocked desc, c.customers_id desc,c.idcards_printed desc, a.entry_country_id ,c.customers_id desc";
				
			//	$customers_sql = "select c.customers_id, LTRIM(c.customers_lastname) as customers_lastname, LTRIM(c.customers_firstname) as customers_firstname,LTRIM(c.customers_email_address) as customers_email_address  from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id " . $search . " order by concat(c.customers_firstname,' ',c.customers_lastname),c.customers_username";
			}
			
			if($order == 1) {
				$value=$FREQUEST->getvalue('value');
				$customers_sql = "select  c.*, c.customers_id, LTRIM(c.customers_lastname) as customers_lastname, LTRIM(c.customers_firstname) as customers_firstname,LTRIM(c.customers_email_address) as customers_email_address, a.entry_country_id  from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id " . $search . " order by concat(c.customers_".$value.") ASC";
			}
			if($order == 2) {
				$value=$FREQUEST->getvalue('value');
				$customers_sql = "select  c.*, c.customers_id, LTRIM(c.customers_lastname) as customers_lastname, LTRIM(c.customers_firstname) as customers_firstname,LTRIM(c.customers_email_address) as customers_email_address, a.entry_country_id  from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id " . $search . " order by concat(c.customers_".$value.") DESC";
			}	

			if ($this->pagination)
			{
				$query_split=$this->splitResult = (new instance)->getSplitResult('CUSTORD');
				$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$customers_sql);
				
				$page=$query_split->curPage;
				
				if ($query_split->queryRows > 0)
				{ 
					if($FREQUEST->getvalue('search')!='')
					$param = $FREQUEST->getvalue('search');
					$cID=$FREQUEST->getvalue('cID','int',0);
						if($FREQUEST->getvalue('rID','string','')){
							$cID=$FREQUEST->getvalue('rID','string','');
						}
						if($cID>0){
							$cusID=$cID;
						}
					
					//$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'GetCustomersDetail','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_DATA,'##PAGE_NO##') . "','searchparam':'".$param."','cID='".$cusID."','orderby':'".$order."','value':'".$value."'})";
                  $query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'GetCustomersDetail','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_DATA,'##PAGE_NO##') . "','searchparam':'".$param."','cID':'".$cusID."','orderby':'".$order."','value':'".$value."'})";
                }
            }
            if($cID>0)
				$jsData->FUNCS[]="doDisplayAction({'id':" .$cID. ",get:'Edit','result':doDisplayResult,'type':'custord',params:'rID=" .$cID. "','style':'boxRow'})";
              
			$customers_query= tep_db_query($customers_sql);
                     
			$found=false;
			if (tep_db_num_rows($customers_query)>0) $found=true;
			if($found)
			{
				$template=getListTemplate();
				$icnt=1;
				while($customers_detail_result=tep_db_fetch_array($customers_query))
				{						
					$customers_id=$customers_detail_result['customers_id'];					
					$customers_info_query=tep_db_query('select customers_info_date_account_last_modified as last_modified, customers_info_date_account_created as account_created from '.TABLE_CUSTOMERS_INFO." where customers_info_id='" . tep_db_input($customers_detail_result['customers_id']) . "'");						
					$customers_info_array=tep_db_fetch_array($customers_info_query);
					$customers_entry_id=$customers_detail_result['entry_country_id'];
					if ($customers_entry_id == 999)
					{
					$BO = 'red';
					}
					else
					{
					$BO = '';
					}
					//GDPR is blocked
					$is_blocked = $customers_id=$customers_detail_result['is_blocked'];
					$idcards_printed = $customers_id=$customers_detail_result['idcards_printed'];
					if ($is_blocked == "Y"){
							$table_style = ' style="background-color:yellow"';
						}else{
							$table_style = '';
					}
						if ($idcards_printed == "Y"){
							$col_style = ' style="padding-left:10px; background-color:red"';
						}else{
							$col_style = ' style="padding-left:10px"';
					}
					
					if($customers_info_array['last_modified']=='' || $customers_info_array['last_modified']=='0000-00-00' || $customers_info_array['last_modified']=='0000-00-00 00:00:00')
					{
							  /*$modified=substr($customers_info_array['account_created'],0,10);
							  $getDate = explode('-',$modified);
							  $last_modified = date("M d Y", mktime(0, 0, 0, $getDate[1], $getDate[0], $getDate[2]));*/
						
							  $rep_array=array(	"ID"=>$customers_detail_result["customers_id"],
												"TYPE"=>$this->type,
												"NAME"=>$customers_detail_result["customers_lastname"],
												"FNAME"=>$customers_detail_result["customers_firstname"],
												"MODIFY"=>date(EVENTS_DATE_FORMAT,strtotime($customers_info_array['account_created'])),
												"EMAIL" => '<span class="' . $BO . '">' . $customers_detail_result["customers_email_address"] . '</span>',
												"CREATED"=>date(EVENTS_DATE_FORMAT,strtotime($customers_info_array['account_created'])),
												"TOTAL"=>$total_customers,
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"STATUS"=>'',
												"UPDATE_RESULT"=>'doDisplayResult',
												"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
												"CNT"=>$cnt,
												"ROW_CLICK_GET"=>'Info',
												"FIRST_MENU_DISPLAY"=>"",
												"PAGE"=>$page,
												"STYLE" => $table_style,
												"COL_STYLE" => $col_style
											 );
					    } else if($customers_info_array['last_modified']!='') { 
									
							$rep_array=array(	"ID"=>$customers_detail_result["customers_id"],
												"TYPE"=>$this->type,
												"NAME"=>$customers_detail_result["customers_lastname"],
												"FNAME"=>$customers_detail_result["customers_firstname"],
												"MODIFY"=>date(EVENTS_DATE_FORMAT,strtotime($customers_info_array['last_modified'])),
												"EMAIL" => '<span class="' . $BO . '">' . $customers_detail_result["customers_email_address"] . '</span>',
												"CREATED"=>date(EVENTS_DATE_FORMAT,strtotime($customers_info_array['account_created'])),
												"TOTAL"=>$total_customers,
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"STATUS"=>'',
												"UPDATE_RESULT"=>'doDisplayResult',
												"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
												"CNT"=>$cnt,
												"ROW_CLICK_GET"=>'Info',
												"FIRST_MENU_DISPLAY"=>"",
												"STYLE" => $table_style,
												"COL_STYLE" => $col_style
										
										   );		
					  }
					echo mergeTemplate($rep_array,$template);
					$icnt++;
				}
				if (!isset($jsData->VARS["Page"]))
					$jsData->VARS["NUclearType"][]=$this->type;
			  } else {
				echo TEXT_NO_RECORDS_FOUND;
			  }	
			if($search!="" && !$cID>0)
			{ ?>
				<tr>
  					<td class="main"><a href="javascript:void(0);" onClick="javascript:doCustomerSearch('reset');"><?php echo tep_image_button('button_reset.gif',IMAGE_RESET);?></a> </td>
				</tr>
			   <?php
			}
			return $found;			
		}//function	doCustomersList ends		
		
		function doGetCustomersDetail($page='')
		{
			
			global $FREQUEST,$jsData;
             if(isset($_GET['order']))
			{
				$order=$FREQUEST->getvalue('order','int',0);
				$order ==1 ? $order_by =2:$order_by =1;					
			}	
			else
			{
				$order = 0;	
				$order_by =1;
			}	
			
			if(isset($_GET['value']))
			{
				if($_GET['value'] == 'lastname')
				{
					$bgcolor = "#CCCCCC";
					$order == 1 ? $img = '<img src="images/template/ico_arrow_up.gif" title="Ascending">':$img = '<img src="images/template/ico_arrow_down.gif" title="Descending">';
				}	
				if($_GET['value'] == 'firstname')
				{
					$bgcolor1 = "#CCCCCC";
					$order == 1 ? $img1 = '<img src="images/template/ico_arrow_up.gif" title="Ascending">':$img1 = '<img src="images/template/ico_arrow_down.gif" title="Descending">';
				}	
				if($_GET['value'] == 'email_address')
				{
					$bgcolor2 = "#CCCCCC";	
					$order == 1 ? $img2 = '<img src="images/template/ico_arrow_up.gif" title="Ascending">':$img2 = '<img src="images/template/ico_arrow_down.gif" title="Descending">';
				}	
			}	
			else
			{
				$bgcolor = '';
				$bgcolor1 = '';
				$bgcolor2 = '';
				$img = '';
				$img1 = '';
				$img2 = '';
			}	
			global $FREQUEST,$jsData;
			$template=getListTemplate();
            

			$keywords = $FREQUEST->getvalue('search');
		if ($keywords != '') $search_param = "where c.customers_lastname like '%" . tep_db_input($keywords) . "%' or c.customers_firstname like '%" . tep_db_input($keywords) . "%' or a.entry_country_id like '%" . tep_db_input($keywords) . "%' or a.entry_company like '%" . tep_db_input($keywords) . "%' or c.customers_email_address like '%" . tep_db_input($keywords) . "%'";
		$cID = $FREQUEST->getvalue('cID');
		
            if($cID>0)$search_param.=' where c.customers_id='.$cID;
           
			$template=getListTemplate();
			$rep_array=array( "TYPE"=>$this->type,
							   "ID"=>-1,
							   "NAME"=>TEXT_CREATE_CUSTOMER,
								"FNAME"=>'',
								"MODIFY"=>'',
								"EMAIL"=>'',
								"CREATED"=>'',
								"IMAGE_PATH"=>DIR_WS_IMAGES,
							    "STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
								"UPDATE_RESULT"=>'doTotalResult',
								"ROW_CLICK_GET"=>'Edit',
								"FIRST_MENU_DISPLAY"=>"display:none",
								"PAGE"=>$page
								);

              ?>			
			<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
  				<tr>
    				<td><!--start--><?php echo mergeTemplate($rep_array,$template); ?><!--end-->
  				<tr>
    				<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%">
        					<tr class="dataTableHeadingRow">
          						<td valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#999999" >
              							<tr>
											
                							<td bgcolor="<?php echo $bgcolor; ?>" class="main" width="13%" style="padding-left:10px;cursor:pointer" onClick="javascript:return doOrderBy({id:-1,type:'<?php echo $this->type;?>',get:'GetCustomersDetail',result:doTotalResult,params:'<?php echo $order_by; ?>','value':'lastname','searchparam':'<?php echo $keywords; ?>','message':'<?php echo sprintf(INFO_LOADING_DATA) ?>'});"><b><?php echo  TABLE_HEADING_LASTNAME;?></b>&nbsp;&nbsp;&nbsp;&nbsp;<span ><?php echo $img; ?></span></td>
                							<td bgcolor="<?php echo $bgcolor1; ?>" class="main" width="12%" style="padding-left:10px;cursor:pointer" onClick="javascript:return doOrderBy({id:-1,type:'<?php echo $this->type;?>',get:'GetCustomersDetail',result:doTotalResult,params:'<?php echo $order_by; ?>','value':'firstname','searchparam':'<?php echo $keywords; ?>','message':'<?php echo sprintf(INFO_LOADING_DATA) ?>'});"><b><?php echo  TABLE_HEADING_FIRSTNAME;?></b>&nbsp;&nbsp;&nbsp;&nbsp;<span ><?php echo $img1; ?></span> </td>
                							<td bgcolor="<?php echo $bgcolor2; ?>" class="main" width="25%" style="padding-left:10px;cursor:pointer" onClick="javascript:return doOrderBy({id:-1,type:'<?php echo $this->type;?>',get:'GetCustomersDetail',result:doTotalResult,params:'<?php echo $order_by; ?>','value':'email_address','searchparam':'<?php echo $keywords; ?>','message':'<?php echo sprintf(INFO_LOADING_DATA) ?>'});"><b><?php echo  TEXT_EMAIL;?></b>&nbsp;&nbsp;&nbsp;&nbsp;<span ><?php echo $img2; ?></span> </td>
											<td class="main" width="15%" style="padding-left:10px"><b><?php echo  TEXT_DATE_ACCOUNT_CREATED;?></b> </td>
											<td class="main" width="15%" style="padding-left:10px"><b><?php echo  TABLE_HEADING_LAST_MODIFIED;?></b> </td>
											<td  width="20%">&nbsp;</td>
              							</tr>
           							 </table>
								  </td>
        						</tr>
       							<?php $this->doCustomersList($search_param,$order,$page);?>

						 
  					
				</table>
				<?php if (is_object($this->splitResult))
					  { ?>
						<table border="0" width="100%" height="100%">
					    <?php echo $this->splitResult->pgLinksCombo(); ?>
						</table>
				<?php }
			 	
			}//function	dogetCustomersDetail ends			
			
            function doEdit()
			{
                
				global $FREQUEST,$jsData,$FSESSION,$customerAccount;
				$customer_id=$FREQUEST->getvalue("rID","int",0);
				if($customer_id!=-1)
                    $where=" and c.customers_id='" . $customer_id . "'";
                
				 $customer_query=tep_db_query("select a.*,c.* from " . TABLE_CUSTOMERS ." c, " . TABLE_ADDRESS_BOOK . " a  where c.customers_default_address_id=a.address_book_id and c.customers_id=a.customers_id " . $where);
                
                 if(tep_db_num_rows($customer_query)>0) {
                     echo tep_draw_form('customers','customers_mainpage.php', ' ' ,'post','id="customers"');
                        $ACCOUNT=array();
                        $fieldsDesc=array();
                        $customer_result=array();
                        $qwhere=" like '%B1%' ";                       
                       // $where='B1';
                        //if($customer_id>0) $where='B2';

                       $fieldsDesc=$customerAccount->getFieldsDescription($customer_id);
                        $desc=$fieldsDesc;
                        $item_count=0;
                        $id=$this->type . $customer_id . "message";
                ?>
                        <table border="0" cellpadding="0" cellspacing="0" class="account" width="100%" style="padding: 5 10 5 10px ">
                            <tr>
                            <td class="main" id="error-1message"></td>
                                <td id="<?php echo $id;?>" class="main" style="color:#FF0000"></td>
                            </tr>
                <?php
                        for ($icnt=0,$n=count($fieldsDesc);$icnt<$n;$icnt++){
                            $fieldDesc=&$fieldsDesc[$icnt];
                            $nextFieldDesc=&$fieldsDesc[$icnt+1];
                          if (!isset($ACCOUNT[$fieldDesc['uniquename']])){
                                $ACCOUNT[$fieldDesc['uniquename']]=$fieldDesc['default_value'];
                            }
                            if($fieldDesc['input_type']=='L') {
                                if($item_count>0) {
                                    echo '</td></tr>';
                                    tep_content_title_bottom();
                                    $item_count=0;
                                }
                                tep_content_title_top($fieldDesc['label_text']);
                                echo '<tr><td class="main" valign="bottom">';$item_count++;
                            } else {
                                echo '<tr><td class="main" valign="bottom">';
                                if (method_exists($customerAccount,"edit__" . $fieldDesc['uniquename'])){
                                    $customerAccount->{"edit__" . $fieldDesc['uniquename']}($fieldDesc);
                                } else {
                                    $customerAccount->commonInput($fieldDesc);
                                }
                                echo '</td></tr>';
                           // if($fieldsDesc['input_type']=='L') echo '</table></td></tr>';
                            }
                        }
                        echo '<tr><td>' . tep_draw_hidden_field('customers_id',$customer_id) . '</td></tr>';
                        if($item_count>0) {
                            echo '</td></tr>';
                            tep_content_title_bottom();
                        } if($customer_id<=0) {
                ?>
                            
                  <?php } ?>
                        </table>
                    </form>
                <?php

                   $jsData->VARS["updateMenu"]=",update,";
                   if (isset($jsData->VARS["page"]))
                        $jsData->VARS["page"]['fieldsDesc']=$desc;
                   else
                        $jsData->VARS["storePage"]['fieldsDesc']=$desc;
                } else {
                    echo 'Err:' . TEXT_CUSTOMER_DETAIL_NOT_FOUND;
                }
                if($customer_id<=0)
                    $jsData->VARS["doFunc"]=array('type'=>'custord','data'=>'hide');
                else
                    $jsData->VARS["doFunc"]=array('type'=>'custord','data'=>'display');
			}//function	doEdit ends

			function doUpdate()
			{
				global $FREQUEST,$jsData,$FSESSION,$customerAccount,$messageStack,$ACCOUNT,$CUSTOMER,$ADDRESS,$INFO,$EXTRA;
                $ACCOUNT=array();
                $CUSTOMER=array();
                $ADDRESS=array();
                $INFO=array();
                $EXTRA=array();
                $PREV_ERROR=array();
                $pass=true;
                $insert=true;
               //print_r($FREQUEST);
                $is_block = $FREQUEST->postvalue('is_blocked');
                
                $customer_id=$FREQUEST->postvalue('customers_id','int','0');
                if($customer_id>0) { $insert=false; }

                $POST_=$FREQUEST->getRef("POST");
                if (count($POST_)>0){
                    reset($POST_);
					//FOREACH
                    //while(list($key,)=each($POST_)){
					foreach(array_keys($POST_) as $key)
					{
                        $ACCOUNT[$key]=$FREQUEST->postvalue($key);
                    }
                }

                $fieldsDesc=array();
                $fieldsDesc=$customerAccount->getFieldsDescription($customer_id,'process');
                for ($icnt=0,$n=count($fieldsDesc);$icnt<$n;$icnt++){
                    $fieldDesc=&$fieldsDesc[$icnt];
                    if (method_exists($customerAccount,"check__" . $fieldDesc['uniquename'])){
                        $pass&=$customerAccount->{"check__" . $fieldDesc['uniquename']}($fieldDesc); // Change by Roy
                    } else {
                        $pass&=$customerAccount->commonCheck($fieldDesc); // Change by Roy
                    }
                }

                if ($pass){
                    if(!$insert){
                        tep_db_perform(TABLE_CUSTOMERS,$CUSTOMER,"update","customers_id='" . $customer_id. "'");
                        if (count($ADDRESS)>0){
                            tep_db_perform(TABLE_ADDRESS_BOOK,$ADDRESS,"update","customers_id='" .$customer_id . "'");
                        }
                       tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified ='" . tep_db_input(getServerDate(true)) . "' where customers_info_id = '" . (int)$customer_id . "'");
                       if (count($EXTRA)>0){
                            reset($EXTRA);
							//FOREACH
                           // while(list($key,$value)=each($EXTRA)){
							foreach($EXTRA as $key => $value) {
                                tep_db_query("REPLACE into " . TABLE_CUSTOMERS_EXTRA_INFO . " values('" . tep_db_input($key) . "','" . tep_db_input($value) . "'," .(int)$customer_id .")");
                            }
                        } 
                    } else {
                        $serverDate=getServerDate(true);
                        tep_db_perform(TABLE_CUSTOMERS,$CUSTOMER);
                        $customer_id=tep_db_insert_id();
                        $ADDRESS["customers_id"]=$customer_id;
                        tep_db_perform(TABLE_ADDRESS_BOOK,$ADDRESS);
                        $address_id=tep_db_insert_id();
                        tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '$address_id' where customers_id = '$customer_id'");

                       $INFO["customers_info_id"]=$customer_id;
                        $INFO["customers_info_number_of_logons"]=0;
                        $INFO["customers_info_date_account_created"]=$serverDate;
                        if (isset($INFO["customers_info_source_id"])){
                            if ($INFO["customers_info_source_id"]=='9999'){
                                tep_db_query("insert into " . TABLE_SOURCES_OTHER . " (customers_id, sources_other_name) values ('$customer_id', '". $INFO["source_other"] . "')");
                            }
                            unset($INFO["source_other"]);
                        }
                        tep_db_perform(TABLE_CUSTOMERS_INFO,$INFO);
                        if (count($EXTRA)>0){
                            reset($EXTRA);
							//FOREACH
                            //while(list($key,$value)=each($EXTRA)){
							foreach($EXTRA as $key => $value) {
                                $sql_array=array("customers_id"=>$customer_id,"uniquename"=>$key,"fieldvalue"=>$value);
                                tep_db_perform(TABLE_CUSTOMERS_EXTRA_INFO,$sql_array);
                            }
                        }
                    }
           // if($is_block == 'N') { $insert=true; }
            
            if($is_block == 'N'){
				
				$customers_language='english';
					include(DIR_WS_LANGUAGES . $customers_language . '/templates.php');
                    //Send email start
                    $send_details=array();
                    //build merge details
                    $merge_details=array();
                    $merge_details[TEXT_FN]=$ACCOUNT['#1_firstname'];
                    $merge_details[TEXT_LN]=$ACCOUNT['#1_lastname'];

                    if (isset($ACCOUNT["#1_gender"])) {
                        if ($gender == 'm') {
                            $merge_details[TEXT_GR] = sprintf(EMAIL_GREET_MR, $ACCOUNT['#1_lastname']);
                        } else {
                            $merge_details[TEXT_GR] = sprintf(EMAIL_GREET_MS, $ACCOUNT['#1_lastname']);
                        }
                    } else {
                        $merge_details[TEXT_GR] = sprintf(EMAIL_GREET_NONE, $ACCOUNT['#1_firstname']);
                    }

	
	$merge_details[TEXT_INV_ON]=TEXT_MAIL_ORDER_NUMBER;//Order Number
	$merge_details[TEXT_INV_DEAR]=TEXT_DEAR;// Dear
	$merge_details[TEXT_INV_THANKS_PURCHASE]=TEXT_THANKS_PURCHASE;//Thanks for...
	$merge_details[TEXT_INV_THANKS_PURCHASE_SENT]=TEXT_THANKS_PURCHASE_SENT;//Thanks for...PRS
	$merge_details[TEXT_INV_DD]=TEXT_DELIVERY_DETAILS;//Delivery Details
	$merge_details[TEXT_INV_ADDRESS]=TEXT_ADDRESS;
	$merge_details[TEXT_INV_TELEPHONE]=TEXT_TELEPHONE;
	$merge_details[TEXT_INV_EMAIL]=TEXT_EMAIL;
	$merge_details[TEXT_INV_PD]=TEXT_PAYMENT_DETAILS;
	$merge_details[TEXT_INV_PM]=TEXT_PAYMENT_METHOD;
	$merge_details[TEXT_INV_PRODUCTS]=TEXT_TICKETS;
	$merge_details[TEXT_INV_WITH_THANKS]=TEXT_WITH_THANKS;
	
					$merge_details[TEXT_YA]=TEXT_YOUR_ACCOUNT;
					$merge_details[TEXT_FH]=TEXT_FOR_HELP;
					$merge_details[TEXT_NT]=TEXT_NOTE;
					$merge_details[TEXT_INV_DEAR]=TEXT_DEAR;// Dear
					$merge_details[TEXT_USER]=TEXT_USERNAME;
					$merge_details[TEXT_PASS]=TEXT_PASSWORD;
					$merge_details[TEXT_INV_WITH_THANKS]=TEXT_WITH_THANKS;
					$merge_details[TEXT_LOGIN]=TEXT_LOGIN_EMAIL;

                    $merge_details[TEXT_SM]=STORE_NAME;
                    $merge_details[TEXT_SN]=STORE_OWNER;
                    $merge_details[TEXT_SE]=STORE_OWNER_EMAIL_ADDRESS;
					
					$merge_details[TEXT_SL]='<a href="' . tep_catalog_href_link(FILENAME_DEFAULT) . '">' . STORE_NAME . '</a>';
					//$merge_details['Store_Link']='<a href="' . tep_catalog_href_link(FILENAME_DEFAULT) . '" style="font-weight:bold;">' . STORE_NAME . '</a>';
					
                    $merge_details[TEXT_LE]=$ACCOUNT['customers_email_address'];
                    $merge_details[TEXT_US]=$ACCOUNT['customers_username'];
                    $merge_details[TEXT_LP]=$FREQUEST->postvalue('customers_password');

                    $send_details[0]['to_name'] = $CUSTOMER['customers_firstname'] . ' ' .  $CUSTOMER['customers_lastname'];
                    $send_details[0]['to_email'] =  $CUSTOMER['customers_email_address'];
                    $send_details[0]['from_name']=STORE_OWNER;
                    $send_details[0]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
                    //print_r($merge_details);die;
                    //tep_send_default_email("CUS",$merge_details,$send_details);
                    
                    tep_db_query("update " . TABLE_CUSTOMERS . " set mail_status = 'Y' where customers_id = '$customer_id'");
                    //Send email end
                }
                if($is_block == 'Y')
                {
                    tep_db_query("update " . TABLE_CUSTOMERS . " set mail_status = 'N' where customers_id = '$customer_id'");
                }
            
            if($insert){
				
					$customers_language='english';
					include(DIR_WS_LANGUAGES . $customers_language . '/templates.php');

						// Admin>Customers>Create Customer
						// Send email start

						$send_details = array();

						// build merge details

						$merge_details = array();
						$merge_details[TEXT_FN] = $ACCOUNT['#1_firstname'];
						$merge_details[TEXT_LN] = $ACCOUNT['#1_lastname'];
						if (isset($ACCOUNT["#1_gender"]))
							{
							if ($gender == 'm')
								{
								$merge_details[TEXT_GR] = sprintf(EMAIL_GREET_MR, $ACCOUNT['#1_lastname']);
								}
							  else
								{
								$merge_details[TEXT_GR] = sprintf(EMAIL_GREET_MS, $ACCOUNT['#1_lastname']);
								}
							}
						  else
							{
							$merge_details[TEXT_GR] = sprintf(EMAIL_GREET_NONE, $ACCOUNT['#1_firstname']);
							}
							
	
	$merge_details[TEXT_INV_ON]=TEXT_MAIL_ORDER_NUMBER;//Order Number
	$merge_details[TEXT_INV_DEAR]=TEXT_DEAR;// Dear
	$merge_details[TEXT_INV_THANKS_PURCHASE]=TEXT_THANKS_PURCHASE;//Thanks for...
	$merge_details[TEXT_INV_DD]=TEXT_DELIVERY_DETAILS;//Delivery Details
	$merge_details[TEXT_INV_ADDRESS]=TEXT_ADDRESS;
	$merge_details[TEXT_INV_TELEPHONE]=TEXT_TELEPHONE;
	$merge_details[TEXT_INV_EMAIL]=TEXT_EMAIL;
	$merge_details[TEXT_INV_PD]=TEXT_PAYMENT_DETAILS;
	$merge_details[TEXT_INV_PM]=TEXT_PAYMENT_METHOD;
	$merge_details[TEXT_INV_PRODUCTS]=TEXT_TICKETS;
	$merge_details[TEXT_INV_WITH_THANKS]=TEXT_WITH_THANKS;
							
					$merge_details[TEXT_YA]=TEXT_YOUR_ACCOUNT;
					$merge_details[TEXT_FH]=TEXT_FOR_HELP;
					$merge_details[TEXT_NT]=TEXT_NOTE;
					$merge_details[TEXT_INV_DEAR]=TEXT_DEAR;// Dear
					$merge_details[TEXT_USER]=TEXT_USERNAME;
					$merge_details[TEXT_PASS]=TEXT_PASSWORD;
					$merge_details[TEXT_INV_WITH_THANKS]=TEXT_WITH_THANKS;
					//$merge_details[TEXT_LOGIN]=TEXT_LOGIN_EMAIL;

                    $merge_details[TEXT_SM]=STORE_NAME;
                    $merge_details[TEXT_SN]=STORE_OWNER;
                    $merge_details[TEXT_SE]=STORE_OWNER_EMAIL_ADDRESS;
					
					$merge_details[TEXT_SL]='<a href="' . tep_catalog_href_link(FILENAME_DEFAULT) . '">' . STORE_NAME . '</a>';
					//$merge_details['Store_Link']='<a href="' . tep_catalog_href_link(FILENAME_DEFAULT) . '" style="font-weight:bold;">' . STORE_NAME . '</a>';
					
                    $merge_details[TEXT_LE]=$ACCOUNT['customers_email_address'];
                    $merge_details[TEXT_US]=$ACCOUNT['customers_username'];
                    $merge_details[TEXT_LP]=$FREQUEST->postvalue('customers_password');

                    $send_details[0]['to_name'] = $CUSTOMER['customers_firstname'] . ' ' .  $CUSTOMER['customers_lastname'];
                    $send_details[0]['to_email'] =  $CUSTOMER['customers_email_address'];
                    $send_details[0]['from_name']=STORE_OWNER;
                    $send_details[0]['from_email']=STORE_OWNER_EMAIL_ADDRESS;

                    tep_send_default_email("CUS",$merge_details,$send_details);
                    //Send email end
                }
                    if ($insert)
                    {
                        $create_order=$FREQUEST->postvalue('create_order','','N');
                       
                        if($create_order=='Y')  
						{
                            //tep_redirect(tep_href_link('create_order_new.php', 'customer=' . $customer_id . '&check=1','SSL'));
                             $jsData->VARS["doFunc"]=array('type'=>'new','data'=>$customer_id);                            
                        } 
                            $this->doGetCustomersDetail();
                    }
                    else
                    {
                        $customer_query=tep_db_query("select c.customers_firstname,c.customers_lastname, c.customers_email_address, date_format(ci.customers_info_date_account_created,'%Y-%m-%d') date_created,date_format(ci.customers_info_date_account_last_modified,'%Y-%m-%d') date_modified from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_INFO . " ci where ci.customers_info_id=c.customers_id and c.customers_id='" . (int)$customer_id . "'");
                        $customer_result=tep_db_fetch_array($customer_query);
                        $date_created=$customer_result['date_created'];
                        $date_modified=$customer_result['date_modified'];

                        if($date_created=='' || $date_created=='0000-00-00')
                            $date_created=getServerDate();
                        if($date_modified=='' || $date_modified=='0000-00-00')
                            $date_modified=getServerDate();

                        $jsData->VARS["replace"]=array($this->type. $customer_id . "lname"=>$customer_result['customers_lastname'],$this->type . $customer_id . "fname"=>$customer_result['customers_firstname'],$this->type . $customer_id . "email"=>$customer_result['customers_email_address'],$this->type . $customer_id . "create"=>format_date($date_created),$this->type . $customer_id . "modify"=>format_date($date_modified));
                        $jsData->VARS["prevAction"]=array('id'=>$customer_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
                        $this->doInfo($customer_id);
                    }
                } else {
                    $stack_error='';
                    for ($icnt=0,$n=count($customerAccount->errors);$icnt<$n;$icnt++){
                       $stack_error.=$customerAccount->errors[$icnt]. '<br>';
                    }
                    if($stack_error!='') {
                        $type='error';
                        echo 'Err,:' . $stack_error;

                    }
                }
			}//function	doUpdate ends

            function doChangePwd()
			{
				global $FREQUEST,$jsData,$FSESSION,$customerAccount;
				$customer_id=$FREQUEST->getvalue("rID","int",0);
                if($customer_id!=-1)
                    $where=" and c.customers_id='" . $customer_id . "'";

				 $customer_query=tep_db_query("select c.customers_password from " . TABLE_CUSTOMERS ." c, " . TABLE_ADDRESS_BOOK . " a  where c.customers_default_address_id=a.address_book_id and c.customers_id=a.customers_id " . $where);
                 if(tep_db_num_rows($customer_query)>0) {
                     echo tep_draw_form('customerspwd','customers_mainpage.php', ' ' ,'post','id="customerspwd"');
                        $ACCOUNT=array();
                        $fieldsDesc=array();
                        $fieldsDesc=$customerAccount->getFieldsDescription($customer_id,'',true);
                        $desc=$fieldsDesc;
                        $item_count=0;
                        $id=$this->type . $customer_id . "message";
                ?>
                        <table border="0" cellpadding="0" cellspacing="0" class="account" width="100%" style="padding: 5 10 5 10px ">
                            <tr>
                                <td class="main" id="error-1message"></td>
                                <td id="<?php echo $id;?>" class="main" style="color:#FF0000"></td>
                            </tr>
                            <tr>
                               <td class="main" colspan="2"><b><?php echo TEXT_NEW_PASSWORD;?></b></td>
                            </tr>
                <?php
                        for ($icnt=0,$n=count($fieldsDesc);$icnt<$n;$icnt++){
                            $fieldDesc=&$fieldsDesc[$icnt];
                            $nextFieldDesc=&$fieldsDesc[$icnt+1];
                          if (!isset($ACCOUNT[$fieldDesc['uniquename']])){
                                $ACCOUNT[$fieldDesc['uniquename']]=$fieldDesc['default_value'];
                            }
                           
                                echo '<tr><td class="main" valign="bottom">';
                                if (method_exists($customerAccount,"edit__" . $fieldDesc['uniquename'])){
                                    $customerAccount->{"edit__" . $fieldDesc['uniquename']}($fieldDesc);
                                } else {
                                    $customerAccount->commonInput($fieldDesc);
                                }
                                echo '</td></tr>';
                           // if($fieldsDesc['input_type']=='L') echo '</table></td></tr>';
                            
                        }
                        echo '<tr><td>' . tep_draw_hidden_field('customers_id',$customer_id) . '</td></tr>';
                       
                ?>
                        </table>
                    </form>
                <?php

                   $jsData->VARS["updateMenu"]=",updatepwd,";
                   if (isset($jsData->VARS["page"]))
                        $jsData->VARS["page"]['fieldsDesc']=$desc;
                   else
                        $jsData->VARS["storePage"]['fieldsDesc']=$desc;
                } else {
                    echo 'Err:' . TEXT_CUSTOMER_DETAIL_NOT_FOUND;
                }
				
				
				
				
				
				
				
			}//CLASS END>


            function doUpdatePwd()
			{
				global $FREQUEST,$jsData,$FSESSION,$customerAccount,$messageStack,$ACCOUNT,$CUSTOMER,$ADDRESS,$INFO,$EXTRA;
                $ACCOUNT=array();
                $CUSTOMER=array();
                $ADDRESS=array();
                $INFO=array();
                $EXTRA=array();
                $PREV_ERROR=array();
                $pass=true;
                $insert=true;
                $customer_id=$FREQUEST->postvalue('customers_id','int','0');
                if($customer_id>0) $insert=false;

                $POST_=$FREQUEST->getRef("POST");
                if (count($POST_)>0){
                    reset($POST_);
                    //while(list($key,)=each($POST_)){
					foreach (array_keys($POST_) as $key) {	
						//FOREACH
                        $ACCOUNT[$key]=$FREQUEST->postvalue($key);
                    }
                }

                $fieldsDesc=array();
                $fieldsDesc=$customerAccount->getFieldsDescription($customer_id,'process',true);
                for ($icnt=0,$n=count($fieldsDesc);$icnt<$n;$icnt++){
                    $fieldDesc=&$fieldsDesc[$icnt];
                    if (method_exists($customerAccount,"check__" . $fieldDesc['uniquename'])){
                        $pass&=$customerAccount->{"check__" . $fieldDesc['uniquename']}($fieldDesc); // Change by Roy
                    } else {
                        $pass&=$customerAccount->commonCheck($fieldDesc); // Change by Roy
                    }
                }

                if ($pass){
                    tep_db_perform(TABLE_CUSTOMERS,$CUSTOMER,"update","customers_id='" . $customer_id. "'");
                  
                    $customer_query=tep_db_query("select c.customers_gender,c.customers_firstname,c.customers_lastname, c.customers_email_address, c.customers_username, date_format(ci.customers_info_date_account_created,'%Y-%m-%d') date_created,date_format(ci.customers_info_date_account_last_modified,'%Y-%m-%d') date_modified from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_INFO . " ci where ci.customers_info_id=c.customers_id and c.customers_id='" . (int)$customer_id . "'");
                    $customer_result=tep_db_fetch_array($customer_query);





                     //Send email start
                    $send_details=array();
                    //build merge details
                    $merge_details=array();
                    $merge_details[TEXT_FN]=$customer_result['customers_firstname'];
                    $merge_details[TEXT_LN]=$customer_result['customers_lastname'];

                    if (isset($ACCOUNT["customers_gender"])) {
                        if ($gender == 'm') {
                            $merge_details[TEXT_GR] = sprintf(EMAIL_GREET_MR, $customer_result['customers_lastname']);
                        } else {
                            $merge_details[TEXT_GR] = sprintf(EMAIL_GREET_MS, $customer_result['customers_lastname']);
                        }
                    } else {
                        $merge_details[TEXT_GR] = sprintf(EMAIL_GREET_NONE, $customer_result['customers_firstname']);
                    }

                    $merge_details[TEXT_SM]=STORE_NAME;
                    $merge_details[TEXT_SN]=STORE_OWNER;
                    $merge_details[TEXT_SE]=STORE_OWNER_EMAIL_ADDRESS;
					//$merge_details[TEXT_SL]=='<a href="' . tep_catalog_href_link(FILENAME_DEFAULT) . '" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . STORE_OWNER . '</a>';
					$merge_details['Store_Link']='<a href="' . tep_catalog_href_link(FILENAME_DEFAULT) . '" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . STORE_OWNER . '</a>';
					if($customer_result['customers_username']!='')
						$merge_details[TEXT_LE]=$customer_result['customers_username'];
					else	
						$merge_details[TEXT_LE]=$customer_result['customers_email_address'];
                    $merge_details[TEXT_US]=$customer_result['customers_username'];
                    $merge_details[TEXT_LP]=$FREQUEST->postvalue('customers_password');

                    $send_details[0]['to_name'] = $customer_result['customers_firstname'] . ' ' .  $customer_result['customers_lastname'];
                    $send_details[0]['to_email'] =  $customer_result['customers_email_address'];
                    $send_details[0]['from_name']=STORE_OWNER;
                    $send_details[0]['from_email']=STORE_OWNER_EMAIL_ADDRESS;

                    tep_send_default_email("CUR",$merge_details,$send_details);

                    $date_created=$customer_result['date_created'];
                    $date_modified=$customer_result['date_modified'];
                   
                    if($date_created=='' || $date_created=='0000-00-00')
                        $date_created=getServerDate();
                    if($date_modified=='' || $date_modified=='0000-00-00')
                        $date_modified=getServerDate();

                    $jsData->VARS["replace"]=array($this->type. $customer_id . "lname"=>$customer_result['customers_lastname'],$this->type . $customer_id . "fname"=>$customer_result['customers_firstname'],$this->type . $customer_id . "email"=>$customer_result['customers_email_address'],$this->type . $customer_id . "create"=>format_date($date_created),$this->type . $customer_id . "modify"=>format_date($date_modified));
                    $jsData->VARS["prevAction"]=array('id'=>$customer_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
                    $this->doInfo($customer_id);
                } else {
                    $stack_error='';
                    for ($icnt=0,$n=count($customerAccount->errors);$icnt<$n;$icnt++){
                       $stack_error.=$customerAccount->errors[$icnt]. '<br>';
                    }
                    if($stack_error!='') {
                        $type='error';
                        echo '1Err:' . $stack_error;

                    }
                }
			}//function	doUpdate ends
			
			function doInfo($customers_id=0)
			{
				global $FREQUEST,$jsData;
				if($customers_id <= 0)
					$customers_id=$FREQUEST->getvalue("rID","int",0);
/*					$customers_query = tep_db_query("select c.customers_firstname,c.customers_lastname,c.customers_email_address,c.customers_id, c.customers_gender, LTRIM(c.customers_firstname) as customers_firstname, LTRIM(c.customers_lastname) as customers_lastname, c.customers_dob, c.customers_email_address, c.customers_second_email_address,c.customers_password,a.entry_company, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, c.customers_type,c.is_blocked,
										 a.entry_zone_id, a.entry_country_id, c.customers_telephone, c.customers_second_telephone,c.customers_fax, c.customers_newsletter,c.customers_subscription_newsletter,c.customers_reserve_newsletter,c.customers_default_address_id,c.customers_groups_id,c.customers_discount,c.customers_username,c.customers_occupation,c.suspend_from,c.resume_from,c.customers_interest from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . 
						 " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and c.customers_id = '" . (int)$customers_id . "' order by concat(c.customers_lastname,' ',c.customers_firstname), c.customers_username");
*/	
					$customers_query = tep_db_query("select c.customers_firstname,c.customers_lastname,c.customers_email_address,c.customers_id, c.customers_gender, LTRIM(c.customers_firstname) as customers_firstname, LTRIM(c.customers_lastname) as customers_lastname, c.customers_dob, c.customers_email_address, c.customers_second_email_address,c.customers_password,a.entry_company, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, c.customers_type,c.is_blocked,
										 a.entry_zone_id, a.entry_country_id, c.customers_telephone, c.customers_second_telephone,c.customers_fax, c.customers_newsletter,c.customers_subscription_newsletter,c.customers_reserve_newsletter,c.customers_default_address_id,c.customers_groups_id,c.customers_discount,c.customers_username,c.customers_occupation,c.suspend_from,c.resume_from,c.customers_interest from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK .
						 " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and c.customers_id = '" . (int)$customers_id . "' order by concat(c.customers_lastname,' ',c.customers_firstname), c.customers_username");
	

			 	$customers = tep_db_fetch_array($customers_query); 
				if (!is_array($customers))
				   $customers=array();
			
				$cInfo = new objectInfo($customers);			
		 		$info_query = tep_db_query("select customers_info_date_account_created as date_account_created, customers_info_date_account_last_modified as date_account_last_modified, customers_info_date_of_last_logon as date_last_logon, customers_info_number_of_logons as number_of_logons from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" .(int)$customers['customers_id'] . "'");				
				$info = tep_db_fetch_array($info_query);	
				$country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$customers['entry_country_id'] . "'");				
				$country = tep_db_fetch_array($country_query);			
			
				$customer_info = array_merge((array)$country, (array)$info, (array)$reviews);		
				$cInfo_array = array_merge($customers, (array)$customer_info);
				$cInfo = new objectInfo($cInfo_array);
			
				if (tep_db_num_rows($customers_query)>0)
				{
					
                    
                    $customers_result=tep_db_fetch_array($customers_query);
					$template=getInfoTemplate($customers_id);				
					$rep_array=array(	"TYPE"=>$this->type,
										"IMAGE"=>tep_draw_separator('pixel_trans.gif','10','10').( ($cInfo->is_blocked=='N')?tep_image(DIR_WS_IMAGES.'template/icon_active.gif','Block'):tep_image(DIR_WS_IMAGES.'template/icon_inactive.gif','UnBlock') ),
										"LASTNAME"=> $cInfo->customers_lastname,
										"FIRSTNAME"=> $cInfo->customers_firstname,
										"EMAIL"=>TEXT_EMAIL_ADDRESS,
										"EMAILADD"=>$cInfo->customers_email_address,
										"ACCOUNTCRT"=>TEXT_DATE_ACCOUNT_CREATED,
										"DATECRT"=>format_date($cInfo->date_account_created),
										"ENT_DISCOUNT"=>TEXT_CUSTOMERS_DISCOUNT,
										"LOGON"=>TEXT_INFO_DATE_LAST_LOGON,
										"LASTLOGON"=>format_date($cInfo->date_last_logon),
										"COUNTRY"=>TEXT_INFO_COUNTRY,
										"COUNTRYNAME"=>$cInfo->countries_name,
										"MODIFY"=>TEXT_DATE_ACCOUNT_LAST_MODIFIED,
										"LASTMODIFY"=>format_date($cInfo->date_account_last_modified),
										"NOLOGAN"=>TEXT_INFO_NUMBER_OF_LOGONS,
										"NUMBERLOGAN"=>$cInfo->number_of_logons,
										"LINK"=>tep_href_link(FILENAME_ORDERS,'cID='.$cInfo->customers_id),
										"BUTTON"=>tep_image_button('button_orders.gif','Orders'),
										"ID"=>$cInfo->customers_id,
									);					
						echo mergeTemplate($rep_array,$template);					
						$jsData->VARS["updateMenu"]=",normal,";
                        $jsData->VARS["doFunc"]=array('type'=>'custord','data'=>'display');
				}
				else 
				{
					echo 'Err:' . TEXT_CUSTOMER_DETAIL_NOT_FOUND;
				}
				
			}//function	doInfo ends

            function doInfoDetails() {
                global $FREQUEST,$currencies;
                    $customer_id=$FREQUEST->getvalue('rID','int',0);
                    $customers_info_query=tep_db_query('select c.customers_id,c.customers_email_address,c.customers_firstname,c.customers_lastname,ci.customers_info_date_account_created as account_created,ci.customers_info_date_of_last_logon as last_logon,ci.customers_info_number_of_logons as numberof_logons,ci.customers_info_date_account_last_modified as last_modified from '.TABLE_CUSTOMERS_INFO." ci, " . TABLE_CUSTOMERS . " c where c.customers_id=ci.customers_info_id and ci.customers_info_id='" . tep_db_input($customer_id) . "'");
                    $customers_info_array=tep_db_fetch_array($customers_info_query);

                ?>
                <table border="0" cellspacing="20" cellpadding="0" width="90%">
                    <tr>
                        <td width="25%" valign="top" class='infoBox'>
						<!--INFO-->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr class="dataTableHeadingRow">
                                    <td class="info_heading" align="left" valign='absmiddle'>&nbsp;<?php echo TEXT_CUSTOMER_INFO;?></td>
                                </tr>
                                <tr>
                                    <td valign="top" width="25%">
                                        <table cellpadding="2" cellspacing="2" border="0" width="100%">
                                            <tr>
                                                <td><?php echo TABLE_HEADING_FIRSTNAME . '<br>' . $customers_info_array['customers_firstname'];?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo TEXT_DATE_ACCOUNT_CREATED . '<br>' . $customers_info_array['account_created'];?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo TEXT_DATE_ACCOUNT_LAST_MODIFIED . '<br>' . $customers_info_array['last_modified'];?></td>
                                            </tr>
                                             <tr>
                                                <td><?php echo TEXT_INFO_NUMBER_OF_LOGONS . '<br>' . $customers_info_array['numberof_logons'];?></td>
                                            </tr>
                                             <tr>
                                                <td><?php echo TEXT_WALLET_BALANCE . '<br>' . $currencies->format(tep_get_wallet_balance($customer_id));?></td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="50%" valign="top" class='infoBox'>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr class="dataTableHeadingRow">
                                    <td class="info_heading" align="left">&nbsp;<?php echo TEXT_ORDERS;?></td>
                                </tr>
                                <tr>
                                    <td valign="top">
							<table cellpadding="2" cellspacing="2" width="100%" boder="0">
								<?php
									$order_query=tep_db_query("select distinct o.orders_id,o.date_purchased,o.payment_method,op.products_type,op.products_name,op.final_price,op.products_tax,op.products_quantity,op.categories_name,op.concert_venue,op.concert_date,op.concert_time,ot.class,ot.text from ".TABLE_ORDERS." o,".TABLE_ORDERS_PRODUCTS." op,".TABLE_ORDERS_TOTAL." ot where o.orders_id=op.orders_id and op.products_type in('P') and ot.orders_id=o.orders_id and ot.orders_id=op.orders_id and o.customers_id='". (int)$customer_id ."' and ot.class='ot_total' order by o.orders_id DESC limit 0,5");
									if(tep_db_num_rows($order_query)>0) 
									{
										while($order=tep_db_fetch_array($order_query)) 
										{
								?>
                                                        <tr>
                                                            <td class="main"><?php echo $order['orders_id'];?></td>
                                                            <td class="main"><?php echo $order['products_name'];?></td>
															<td class="main"><?php echo $order['categories_name'];?><br>
															<?php echo $order['concert_venue'];?><br>
															<?php echo $order['concert_date'];?><br>
															<?php echo $order['concert_time'];?>
															</td>
                                                            <td class="main"><?php echo $currencies->format($order['final_price']);?></td>
                                                            <td class="main"><?php echo format_date($order['date_purchased']);?></td>
                                                        </tr>
                                                        <?php }
                                                } else { ?>
                                                       <tr><td align="center" class="main"><?php echo TEXT_NO_RECORD_FOUND;?></td></tr>
                                             <?php
                                                 }
                                              ?>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
       <?php
            }

            function doStateChanges(){
                global $FREQUEST;
                $country=tep_db_prepare_input($FREQUEST->getvalue('country_id','int',0));
                $content="";

                  $zone_ids="";
                  $zone_name="";
                  $zones_query = tep_db_query("select zone_id,zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' order by zone_name");
                  while ($zones_values = tep_db_fetch_array($zones_query)) {
                     $zone_ids.=$zones_values['zone_id']."{}";
                     $zone_name.=$zones_values['zone_name']."{}";
                  }
               echo 'show_state^'.substr($zone_ids,0,-2)."^".substr($zone_name,0,-2)."^".$zone;
            }



            function doWalletUpdateConfirm() {
            global $FREQUEST,$FSESSION,$language,$currencies,$jsData;
            $cID=$FSESSION->get('upload_id','int',0);
            $confirm_error=false;
            // redirect to the customers page
            if ($cID==0) {
                //tep_redirect(tep_href_link(FILENAME_CUSTOMERS));

                $FSESSION->remove('payment_page');
                $FSESSION->remove('comments');
                $FSESSION->remove('wallet_amount');
                $FSESSION->remove('payment');
                $FSESSION->remove('wallet_timestamp');
                echo 'Err:' . 'Customer Id is Null';
                $confirm_error=true;
            }
            // get the values from the payment page
            $wallet_amount=$FREQUEST->postvalue('wallet_amount','float',0);
            $payment=$FREQUEST->postvalue('payment','','');
            $comments=$FREQUEST->postvalue('comments','','');           

            // check for the wallet amount
            if( $wallet_amount=="" || ((float)$wallet_amount<=0))  {
                //tep_redirect(tep_href_link(FILENAME_WALLET_PAYMENT, 'error_message=' .urlencode(ERROR_NO_PAYMENT_MODULE_UPLOADS)));
            //echo 'error^^'.'Wallet Amount Error'.'^^';
                echo 'Err:' . "Wallet Amount Error";
                 $confirm_error=true;
            }

            if (tep_not_null(MODULE_PAYMENT_INSTALLED) && ($payment=='')) {
                echo 'Err:'.'Payment Not Installed';
                $confirm_error=true;
            }


            if($confirm_error==false) {

                $FSESSION->set('wallet_amount',$FREQUEST->postvalue('wallet_amount','int',0));
                $FSESSION->set('payment',$FREQUEST->postvalue('payment'));

                if (tep_not_null($FREQUEST->postvalue('comments'))) {
                    $FSESSION->set('comments',$FREQUEST->postvalue('comments'));
                }
            
                $payment_modules=new payment($FSESSION->payment);

                // perform a confirmation
                if (is_array($payment_modules->modules)) {
                    $payment_modules->pre_confirmation_check();
                    $FSESSION->set('ajax_flag','ajax_flag');
                }

                // get the customer address details
                $customer_details_query = tep_db_query("select c.customers_id,c.customers_default_address_id, a.entry_firstname as firstname, a.entry_lastname as lastname, a.entry_company as company, a.entry_street_address as street_address, a.entry_suburb as suburb, a.entry_city as city, a.entry_postcode as postcode, a.entry_state as state, a.entry_zone_id as zone_id, a.entry_country_id as country_id from ". TABLE_ADDRESS_BOOK . " a, " . TABLE_CUSTOMERS . " c  where c.customers_id = " .(int)$cID . " and c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id");
                $customer_details = tep_db_fetch_array($customer_details_query);
                $firstname=$lastname=$streets=$citypost=$state=$country="";

                $label='$firstname $lastname<br>$streets<br>$city $postcode<br>$statename$country';
                $firstname=$customer_details["firstname"];
                $lastname=$customer_details["lastname"];
                $street=$customer_details["street_address"];
                $suburb=$customer_details["suburb"];
                $postcode=$customer_details["postcode"];
                $city=$customer_details["city"];
                $state=$customer_details["state"];
                $country=tep_get_country_name($customer_details['country_id']);

                if (isset($customer_details['zone_id']) && tep_not_null($customer_details['zone_id'])) {
                    $state = tep_get_zone_code($customer_details['country_id'], $customer_details['zone_id'], $state);
                }
                $streets=$street;
                if ($suburb!="") $streets=$street . "<br>" . $suburb;
                if ($state!="") $statename=$state . ',';

                // print the string
                eval("\$address = \"$label\";");

            // BOF: Lango Added for template MOD

                $form_action=$GLOBALS[$FSESSION->payment];
                if (isset($form_action->form_action_url)) {
                    echo '<form name="customer_wallet_confirmation" action="' . $form_action->form_action_url . '" method="post">';
                    echo tep_draw_hidden_field('action',$form_action->form_action_url);
                } else {
                    echo '<form name="customer_wallet_confirmation" action="' . tep_href_link(FILENAME_WALLET_PROCESS,$payment_params) . '" method="post">';
                    echo tep_draw_hidden_field('action',tep_href_link(FILENAME_WALLET_PROCESS,$payment_params));
                }
            ?>
                <!-- body //-->
            <table border="0" width="100%" cellspacing="2" cellpadding="2">
            <tr>
                <!-- body_text //-->
                <td width=100% align=left valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="pageHeading"><?php echo HEADING_UPLOAD_CONFIRMATION; ?></td>
                      </tr>
                    </table></td>
                  </tr>
                <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                </tr>
                  <tr>
                    <td>
                    <table cellpadding="0" cellspacing="0" width="60%" border="0" >
                        <tr>
                            <td>
                            <table border="0" width="100%" cellspacing="1" cellpadding="2">
                                <tr>
                                    <td width="30%" valign="top">
                                    <table border="0" width="100%" cellspacing="1" cellpadding="2">
                                        <tr>
                                            <td class="main"><?php  echo '<b>' . HEADING_UPLOAD_TO . '</b> '; ?></td>
                                            <td class="main"><?php echo '<b>' . HEADING_UPLOAD_AMOUNT . '</b> ' ?><?php echo $currencies->format($FSESSION->wallet_amount); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="main"><?php echo $address;?></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                    </td>
                                </tr>
                            </table>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                        </tr>
                          <tr>
                            <td>
                              <table border="0" width="100%" cellspacing="1" cellpadding="2">
                              <tr>
                               <td width="30%" valign="top">
                                 <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td class="main"><?php echo '<b>' . HEADING_PAYMENT_METHOD . '</b>'; ?></td>
                                     <table cellpadding="0" cellspacing="0" border="0">
                                     <tr><td width="10%">&nbsp;</td><td class="main">
                                    <tr>
                                    <td class="main"><?php echo $GLOBALS[$FSESSION->payment]->title; ?></td>
                                    </tr>
                                    <?php //echo $$payment->title; ?>
                                    </td></tr>
                                    </table>

                                  </td>
                                  </tr>

                            </table></td>
                          </tr>
                        <?php
                        // draw the form

                        // BOF: Lango modified for print order mod
                          if (is_array($payment_modules->modules)) {
                            if ($confirmation = $payment_modules->confirmation()) {
                              $FSESSION->set('payment_info',$confirmation['title']);
            //				  if (!tep_session_is_registered('payment_info')) tep_session_register('payment_info');
                        // EOF: Lango modified for print order mod
                        ?>
                          <tr>
                            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                          </tr>
                          <tr>
                            <td><table border="0" width="100%" cellspacing="1" cellpadding="2">
                              <tr >
                                <td><table border="0" cellspacing="0" cellpadding="2">
                                <tr >
                                    <td class="main" colspan="4"><b><?php echo HEADING_PAYMENT_INFORMATION; ?></b></td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
                                  </tr>
                                  <tr>
                                    <td class="main" colspan="4">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                    <tr><td>&nbsp;</td><td class="main">
                                    <?php echo $confirmation['title']; ?></td></tr></table></td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
                                  </tr>
                    <?php
                          for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
                    ?>
                                  <tr>
                                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                    <td class="main"><?php echo $confirmation['fields'][$i]['title']; ?></td>
                                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                    <td class="main"><?php echo $confirmation['fields'][$i]['field']; ?></td>
                                  </tr>

                    <?php
                          }
                    ?>
                                </table></td>
                              </tr>
                            </table></td>
                          </tr>
                    <?php
                        }
                      }
                    ?>
                          <tr>
                            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                          </tr>
            <?php
              if (tep_not_null($FSESSION->comments)) {
            ?>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                      <tr>
                        <td class="main"><?php echo '<b>' . HEADING_WALLET_COMMENTS . '</b>'; ?></td>
                      </tr>
                      <tr class="infoBoxContents">
                        <td><table border="0" width="100%" cellspacing="0" cellpadding="4">
                          <tr>
                            <td class="main"><?php echo nl2br(tep_output_string_protected($FSESSION->comments)); ?></td>
                          </tr>
                        </table></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
            <?php  } ?>
                </table>

                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td><table border="0" width="60%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="right" class="main">
                            <?php

                                // draw the output form
                                $temp_array=array('info'=>array('total'=>$wallet_amount));
                                $order=new objectInfo($temp_array);

                                // create a random value for internal checking
                                $validID=tep_create_random_value(5,'digits');
                                $payment_params='validID=' . $validID;

                                $FSESSION->set('checkID',$validID);
                                $FSESSION->set('validID',$validID);

                                if (is_array($payment_modules->modules)) {
                                    echo $payment_modules->process_button();
                                }

                            ?>
                        </td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
                            <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                          </tr>
                        </table></td>
                      </tr>
                </table>
                </td></tr>
                </table>
                </table>
            </form>
  <?php
    $jsData->VARS['updateMenu']=",walletconfirm,";

    }
       
  }

        function doWallet() {
            global $FREQUEST,$FSESSION,$currencies,$language,$upload_id,$jsData;

            // check for the customer id in input or session
            $cID=$FREQUEST->getvalue('rID','int',0);
            $payment_modules=new payment;

            if ($cID==0) $cID=$FSESSION->get('upload_id','int',0);

            $wallet_timestamp=time();
            $FSESSION->set('wallet_timestamp',$wallet_timestamp);
           // $FSESSION->set('upload_id',$upload_id);
        // 	 if (!tep_session_is_registered('wallet_timestamp')) tep_session_register('wallet_timestamp');
            //if (!tep_session_is_registered('upload_id')) tep_session_register('upload_id');
            // check for the payment page
            //if (!tep_session_is_registered('payment_page')) tep_session_register('payment_page');

            $FSESSION->set('upload_id',$cID);
            $FSESSION->set('payment_page','customers');

            $customer_details_query = tep_db_query("select c.customers_id,c.customers_default_address_id, a.entry_firstname as firstname, a.entry_lastname as lastname, a.entry_company as company, a.entry_street_address as street_address, a.entry_suburb as suburb, a.entry_city as city, a.entry_postcode as postcode, a.entry_state as state, a.entry_zone_id as zone_id, a.entry_country_id as country_id from ". TABLE_ADDRESS_BOOK . " a, " . TABLE_CUSTOMERS . " c  where c.customers_id = " .(int)$cID . " and c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id");
            $customer_details = tep_db_fetch_array($customer_details_query);
            $firstname=$lastname=$streets=$citypost=$state=$country="";

            $label='$firstname $lastname<br>$streets<br>$city $postcode<br>$statename$country';
            $firstname=$customer_details["firstname"];
            $lastname=$customer_details["lastname"];
            $street=$customer_details["street_address"];
            $suburb=$customer_details["suburb"];
            $postcode=$customer_details["postcode"];
            $city=$customer_details["city"];
            $state=$customer_details["state"];
            $country=tep_get_country_name($customer_details['country_id']);

            if (isset($customer_details['zone_id']) && tep_not_null($customer_details['zone_id'])) {
                $state = tep_get_zone_code($customer_details['country_id'], $customer_details['zone_id'], $state);
            }
            $streets=$street;
            if ($suburb!="") $streets=$street . "<br>" . $suburb;
            if ($state!="") $statename=$state . ',';

            // print the string
            eval("\$address = \"$label\";");

            ?>
        <?php echo tep_draw_form('customer_wallet', 'customers_mainpage.php','','post') ; ?>
        <!-- body -->
        <table cellpadding="2" cellspacing="2" border="0" width="100%">
          <tr>
        <!-- body_text //-->

            <td style="padding-left:20px;" width="100%" valign="top">
                <table border="0" width="100%" cellspacing="0" cellpadding="0">

                      <tr>
                        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td class="pageHeading"><?php echo HEADING_UPLOADS; ?> <?php  ?></td>
                            <td class="pageHeading" align="right"></td>
                            <td class="pageHeading" align="right"><?php ?></td>
                          </tr>

                        </table></td>
                      </tr>


                       <tr>
                        <td>
                            <table cellpadding="2" cellspacing="0" border="0"><tr><td class="formAreaTitle">
                            </td></tr></table>
                        </td>
                      </tr>
                      <tr>
                         <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                      </tr>
                      <?php tep_content_title_top(WALLET_FUNDS); ?>
                      <tr>
                      <td  valign="top" >
                        <table  border="0" cellpadding="2" cellspacing="0" width="60%">

                            <tr><td valign="top">
                            <table border="0" width="100%" cellspacing="0" cellpadding="2"  >
                              <tr>
                                 <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                              </tr>
                             <tr>
                                <td ><?php echo tep_draw_separator('pixel_trans.gif', '7', '1'); ?></td>
                                 <td class="main" valign="top" width="150"><?php echo '<b>' . WALLET_UPLOAD_TO . '</b>' ; ?></td>
                                    <td class="main" align="left"><?php	 echo $address;?></td>
                                </tr>
                             <tr>
                                 <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '3'); ?></td>
                             </tr>
                              <tr>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '7', '1'); ?></td>
                                <td class="main" align="left"><?php echo '<b>' . WALLET_AMOUNT . '</b>' ?></td>
                                <td class="main" align="left"><?php echo  tep_draw_input_field('wallet_amount','','maxlength=12 size=13')?></td>
                              </tr>
                               <tr>
                                 <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                              </tr>
                            </table>
                        </td></tr>
                        </table>
                        </td>
                       </tr>
                       <?php tep_content_title_bottom(); ?>

                        <?php tep_content_title_top(WALLET_PAYMENT_METHOD); ?>
            <tr>
                <td>
                <table border="0" width="100%" cellspacing="1" cellpadding="2" >
                <!--<tr><td id='payerr' style="display:none; " class="main" ><font color=red>payment err</font></td></tr>-->
                <tr id="show_payment_error" style="display:none">
                <td>
                <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBoxNotice">
                    <tr class="infoBoxNoticeContents">
                        <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <tr>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                <td class="main" width="100%" valign="top" id="payment_error_text"></td>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                </table>
                </td>
            </tr>
                    <tr>
                        <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <?php
                              $selection = $payment_modules->selection();

                              if (sizeof($selection) > 1) {
                            ?>
                                          <tr>
                                            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                            <td class="main" width="50%" valign="top"><?php echo TEXT_SELECT_PAYMENT_METHOD; ?></td>
                                            <td class="main" width="50%" valign="top" align="right"><b><?php echo TITLE_PLEASE_SELECT; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?></td>
                                            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                          </tr>
                            <?php
                              } else {
                            ?>
                                          <tr>
                                            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                            <td class="main" width="100%" colspan="2">
                                            <?php  if(sizeof($selection)>0)
                                                        echo TEXT_ENTER_PAYMENT_INFORMATION;
                                                 else
                                                        echo TEXT_NO_PAYMENT_SELECTION;
                                            ?></td>
                                            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                          </tr>
                            <?php
                              }
                              $flag='B';
                              $radio_buttons = 0;
                              $m = count($selection[0]['fields']);
                              $cnt = 0;
                              $barred=0;
                              for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
                                if(strtolower($selection[$i]['id'])!='wallet'){
                            ?>
                              <tr>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                <td colspan="2">
                                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                    <?php

                                    //if ($selection[$i]['id']!='wallet'){

                                     if ( ($selection[$i]['id'] == $payment) || ($n == 1) )
                                            echo '<tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectwalletRowEffect(this, ' . $radio_buttons . ',\'', $selection[$i]['id'] . '\')">' . "\n";
                                     else
                                           echo '<tr id="defaultunSelected" class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectwalletRowEffect(this, ' . $radio_buttons . ',\'', $selection[$i]['id'] . '\')">' . "\n";
                                    ?>
                                            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                            <td class="main" colspan="3"><b><?php echo $selection[$i]['module']; ?></b>
                                             <table border="0" cellpadding="0" cellspacing="0">
                                                <?php for($j=0;$j<$m;$j++){
                                                   while(list($k,$v) = each($selection[0]["fields"]["$j"])){
                                                          if($cnt%2==0)
                                                             //echo '<tr> ';
                                                            //echo  '<td class="main">'.$v . '</td>';
                                                            $cnt++;
                                                        }
                                                    } ?>
                                            </table>
                                            </td>
                                            <td class="main" align="right">
                                            <?php $single_payment=false;
                                                if (sizeof($selection)>1)
                                                  echo tep_draw_radio_field('payment', $selection[$i]['id'],(($i==0)?true:''));
                                                else {
                                                  $single_payment=true;
                                                  echo tep_draw_hidden_field('payment', $selection[$i]['id']);
                                                }
                                            ?>
                                            </td>
                                            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                        </tr>
                                        <?php
                                            if (isset($selection[$i]['error'])) {
                                        ?>
                                                          <tr>
                                                            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                            <td class="main" colspan="4"><?php echo $selection[$i]['error']; ?></td>
                                                            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                          </tr>
                                        <?php
                                            } elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
                                        ?>
                                                          <tr <?php if(!$single_payment){?>id="<?php echo $selection[$i]['id'];?>" <?php echo (($payment!=$selection[$i]['id'])?'style="display:none"':'');}?>>
                                                            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                            <td colspan="4">
                                                            <table border="0" cellspacing="0" cellpadding="2">
                                                                <?php
                                                                      for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
                                                                ?>
                                                                                      <tr>
                                                                                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                                                        <td class="main"><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
                                                                                        <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                                                        <td class="main"><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
                                                                                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                                                      </tr>
                                                                <?php
                                                                      }
                                                                ?>
                                                            </table>
                                                            </td>
                                                            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                        </tr>
                                        <?php
                                            } // fields,error
                                        //	}
                                        ?>
                                </table>
                                </td>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                          </tr>

        <?php
               $radio_buttons++;
                //$cnt++;
            }
          }
        ?>
                    </table></td>
                  </tr>
                  <?php tep_content_title_bottom(); ?>
                </table></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
             </tr>
                                  <!-- ends payment blocks -->
            <!-- begins comments block -->
             <?php tep_content_title_top(TEXT_ENTER_COMMENTS); ?>
            <tr>
                <td><table border="0" width="60%" cellspacing="2" cellpadding="2" >
                  <tr>
                     <td><table border="0" width="100%" cellspacing="2" cellpadding="2" >
                      <tr>
                        <td><?php echo tep_draw_textarea_field('comments', 'soft', '80', '5'); ?></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>

            <?php tep_content_title_bottom(); ?>
            <!-- end comments block -->



                </table>

         </form>
   <?php //$jsData->VARS["updateMenu"]="";
    $jsData->VARS["updateMenu"]=",wallet,";
    $jsData->VARS['storePage']['payment_cnt']=sizeof($selection);
 }


	function doWalletUpdateProcess(){
	global $FREQUEST,$FSESSION,$language,$currencies,$jsData;
    $process_err=false;

	// check for the customer id
	$cID=$FSESSION->get('upload_id','int',0);

	$validID=$FREQUEST->getvalue('validID','int',0);
	$checkID=$FSESSION->get('checkID','int',0);
	$wallet_timestamp=$FSESSION->get('wallet_timestamp','int',0);
	$server_date = getServerDate(true);

	if ($cID==0) {
		echo 'Err:'.'Customer Id is null';
        $process_err=true;
	}

	// check for the payment
	if ( (tep_not_null(MODULE_PAYMENT_INSTALLED)) && (!$FSESSION->is_registered('payment')) ) {
        echo 'Err:'.'Payment Not Installed';
        $process_err=true;
	}

	//check for the valid internal id
	if ($FSESSION->validID!=$FSESSION->checkID) {
        echo 'Err:'.'Wallet Payment Error';
        $process_err=true;
	}
    if($process_err==false) {
        $currencies=new currencies();
        $payment_modules = new payment($FSESSION->payment);

        $payment_status = $GLOBALS['administrator_login'] ? $GLOBALS['administrator_login'] : DEFAULT_ORDERS_STATUS_ID;
        if ( isset($GLOBALS[$FSESSION->payment]->order_status) && is_numeric($GLOBALS[$FSESSION->payment]->order_status) && ($GLOBALS[$FSESSION->payment]->order_status > 0) ) {
            $payment_status=$GLOBALS[$FSESSION->payment]->order_status;
        }

        $sql_data_array = array(
                                'customers_id' => $cID,
                                'payment_date'=>$server_date,
                                'payment_method'=>$GLOBALS[$FSESSION->payment]->title,
                                'payment_info' => $GLOBALS['payment_info'],
                                'payment_status' => $payment_status,
                                'comments' => $FSESSION->comments,
                                'amount' => $FSESSION->wallet_amount
                                );

        tep_db_perform(TABLE_WALLET_UPLOADS, $sql_data_array);
        $insert_id=tep_db_insert_id();

        $current_balance=tep_get_wallet_balance($cID);
        $customer_sql="SELECT c.customers_firstname,c.customers_lastname,c.customers_dob,c.customers_email_address,c.customers_telephone,
                        c.customers_fax,a.entry_street_address,a.entry_city,a.entry_suburb,a.entry_postcode,a.entry_state,a.entry_country_id,a.entry_zone_id
                        from " . TABLE_CUSTOMERS . " c," . TABLE_ADDRESS_BOOK . " a where a.address_book_id=c.customers_default_address_id and c.customers_id='" . (int)$cID . "'";

        $customer_query=tep_db_query($customer_sql);

        $customer_result=tep_db_fetch_array($customer_query);

            //for direct deposit module
        if($GLOBALS['payment']=='bank_transfer')
            $direct_deposit=sprintf(TEXT_DIRECT_DEPOSIT,substr(strtolower($customer_result['customers_firstname']),0,3) , substr(strtolower($customer_result['customers_lastname']),0,3) , $wallet_timestamp );
        else
            $direct_deposit='';

        $merge_details=array(	TEXT_FN=>$customer_result['customers_firstname'],
                                TEXT_LN=>$customer_result['customers_lastname'],
                                TEXT_DF=>format_date($customer_result['customers_dob']),
                                TEXT_EM=>$customer_result['customers_email_address'],
                                TEXT_TN=>$customer_result['entry_telephone_number'],
                                TEXT_FX=>$customer_result['entry_fax'],
                                TEXT_SA=>$customer_result['entry_street_address'],
                                TEXT_SU=>$customer_result['entry_suburb'],
                                TEXT_PC=>$customer_result['entry_postcode'],
                                TEXT_CT=>$customer_result['entry_city'],
                                TEXT_ST=>tep_get_zone_name($customer_result["entry_country_id"],$customer_result["entry_zone_id"],$customer_result['entry_state']),
                                TEXT_CY=>tep_get_country_name($customer_result['entry_country_id']),
                                TEXT_RE=>'',
                                TEXT_IV=>'',
                                TEXT_UN=>'',
                                TEXT_PT=>$GLOBALS[$payment]->title,
                                TEXT_DD=>$direct_deposit,
                                TEXT_WAD=>$currencies->format($wallet_amount),
                                TEXT_WCB=>$currencies->format($current_balance)
                            );

       $send_details=array(
                            array('to_name'=>$customer_result['customers_firstname'] . ' ' . $customer_result['customers_lastname'],
                                         'to_email'=>$customer_result['customers_email_address'],
                                         'from_name'=>STORE_OWNER,
                                         'from_email'=>STORE_OWNER_EMAIL_ADDRESS
                            )
                        );
        $sql_array=array(	"send_date"=>$server_date,
                            "customers_id"=>$cID,
                            "message_mode"=>"P",
                            "message_type"=>"WFU"
                    );
        tep_db_perform(TABLE_WALLET_MESSAGES_HISTORY,$sql_array);
        tep_send_default_email("WFU",$merge_details,$send_details);

        // check for the customer id
        $cID=$FSESSION->get('upload_id','int',0);

        $FSESSION->remove('payment_page');
        $FSESSION->remove('comments');
        $FSESSION->remove('wallet_amount');
        $FSESSION->remove('payment');
        $FSESSION->remove('wallet_timestamp');
        $FSESSION->remove('upload_id');

        $upload_query=tep_db_query("SELECT payment_status,amount from " . TABLE_WALLET_UPLOADS . " where wallet_id='" . (int)$insert_id . "'");
        $upload_result=tep_db_fetch_array($upload_query);
        ?>
        <!-- body //-->
    <table border="0" width="100%" cellspacing="2" cellpadding="2">
    <tr>
        <!-- body_text //-->
        <td width=100% align=left valign="top">
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="pageHeading"><?php echo HEADING_SUCCESS; ?></td>
                </tr>
            </table>
            </td>
          </tr>
        <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
          <tr>
            <td>
            <table cellpadding="0" cellspacing="0" width="60%" border="0">
                <tr>
                    <td>
                    <table border="0" width="100%" cellspacing="1" cellpadding="2">
                        <tr>
                            <td width="30%" valign="top">
                            <table border="0" width="100%" cellspacing="1" cellpadding="2">
                                <tr>
                                    <td class="main">
                                        <?php echo TEXT_UPLOAD_SUCCESS; ?><br><br>
                                    </td>
                                </tr>
                                <?php if ($upload_result['payment_status']<=1) { ?>
                                    <tr>
                                        <td class="smallText"><?php echo sprintf(TEXT_CREDIT,$currencies->format($upload_result["amount"])); ?><br><br>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td class="main">
                                        <?php
                                            $balance=tep_get_wallet_balance($cID);
                                            echo sprintf(TEXT_WALLET_UPLOAD_BALANCE,$currencies->format($balance));
                                        ?>
                                    </td>
                                </tr>
                            </table>
                            </td>
                        </tr>
                    </table>
                    </td>
                </tr>
                </table>
                </td>
                </tr>
                <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                </tr>
                <tr>
                    <td class="main">
                        <?php //echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS) . '">' . tep_image_button('button_continue.gif',IMAGE_CONTINUE) . '</a>'; ?>
                    </td>
                </tr>
        </table>
        </td>
        </tr>
        </table>
        <?php
            $jsData->VARS['updateMenu']=",normal,";
        }
	}
	//customers
     function doCustomerMail(){
                global $FREQUEST,$jsData;
                $customers_id=$FREQUEST->getvalue('rID','int','');
                $email_address_query=tep_db_query('select customers_email_address from '.TABLE_CUSTOMERS." where customers_id='$customers_id'");
                $email_address_array=tep_db_fetch_array($email_address_query);
                $email_address=$email_address_array['customers_email_address'];
                $jsData->VARS['doFunc']=array('type'=>'editor','data'=>'doEmailEditor');
                ?>
                <?php echo tep_draw_form('customer_mail', 'customers_mainpage.php') . tep_draw_hidden_field('rID',$customers_id) ; ?>
            <table border="0" cellpadding="0" cellspacing="2">
                <tr>
                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <?php
                $customers = array();
                $customers[] = array('id' => '0', 'text' => TEXT_SELECT_CUSTOMER);
                $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
                $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
                $mail_query = tep_db_query("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " order by customers_lastname");
                while($customers_values = tep_db_fetch_array($mail_query)) {
                $customers[] = array('id' => $customers_values['customers_email_address'],
                                       'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');
                }
                $fields_details=array(array('id'=>'TITLE_C','text'=>TEXT_TITLE_C),
                                           array('id'=>'FN','text'=>'&nbsp;&nbsp;' . TEXT_FN),
                                           array('id'=>'LN','text'=>'&nbsp;&nbsp;' . TEXT_LN),
                                           array('id'=>'SN','text'=>'&nbsp;&nbsp;' . TEXT_SN),
                                           array('id'=>'SM','text'=>'&nbsp;&nbsp;' . TEXT_SM),
                                           array('id'=>'SE','text'=>'&nbsp;&nbsp;' . TEXT_SE),
                                           array('id'=>'LA','text'=>'&nbsp;&nbsp;' . TEXT_LA),
                                           array('id'=>'EA','text'=>'&nbsp;&nbsp;' . TEXT_EA),
                                           array('id'=>'PW','text'=>'&nbsp;&nbsp;' . TEXT_PWD)
                               );

            ?>
                      <tr>
                        <td class="main"><?php echo TEXT_CUSTOMER; ?></td>
                        <td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers,$email_address);?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
                      <tr>
                        <td class="main"><?php echo TEXT_FROM; ?></td>
                        <td><?php echo tep_draw_input_field('mail_from', STORE_OWNER_EMAIL_ADDRESS,'size=40'); ?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
                      <tr>
                        <td class="main"><?php echo TEXT_SUBJECT; ?></td>
                        <td><?php echo tep_draw_input_field('mail_subject','','size=40'); ?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
                      <tr>
                        <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
                        <td><table border=0 cellspacing="0" cellpadding="0" style="background:ButtonFace;"><tr><td><?php echo tep_draw_textarea_field('message_text', 'soft', '80', '24','','id="message"'); ?></td>
                             <td  style="background:ButtonFace;" valign=bottom align=center>
                  <?php
                       echo "<div class='main'><b>" . TEXT_MERGE_FIELDS . '</b></div><br>';
                       echo tep_draw_pull_down_menu('fields',$fields_details,'','style="height:' .((strpos($_SERVER['HTTP_USER_AGENT'],"MSIE")>0)?'224':'241') .'" size=15 ondblClick="AddField()"');
                  ?></td></tr></table></td>

                      </tr>
                      <tr>
                         <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
                      <tr>
                         <td class="main" align="center"><?php echo tep_draw_checkbox_field('check_pwd','Y','','','onClick="javascript:pwd(this.checked);"') . '&nbsp;' . TEXT_CREATE_PWD;?></td>
                      </tr>

                    </table>
                  </form>
            <?php
                $jsData->VARS['updateMenu']=",mail,";
            }


        function doMailPreview(){
            global $FREQUEST,$FPOST,$jsData;
			
            switch ($FPOST['customers_email_address']) {
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
            $merge_details=array(TEXT_FN=>TEST_MAIL_FN,
                                  TEXT_LN=>TEST_MAIL_LN,
                                  TEXT_SN=>TEST_MAIL_SN,
                                  TEXT_SM=>TEST_MAIL_SM,
                                  TEXT_SE=>TEST_MAIL_SE,
                                  TEXT_LA=>TEST_MAIL_LA,
                                  TEXT_EA=>TEST_MAIL_EA,
                                  TEXT_PWD=>TEST_MAIL_PWD
                                 );


        $message_text=$FREQUEST->postvalue('message_text','','');
		//FOREACH
       // while(list($key,$value)=each($merge_details))
		foreach($merge_details as $key => $value)
           $message_text=str_replace("%%" . $key  . "%%",$value,$message_text);
        ?>
        <?php echo tep_draw_form('mail', 'customers_mainpage.php', 'action=send_email_to_user'); ?>
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
                    <td class="smallText"><b><?php echo TEXT_FROM; ?></b><br><?php echo htmlspecialchars(STORE_OWNER_EMAIL_ADDRESS); ?></td>
                  </tr>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                  </tr>
                  <tr>
                    <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo htmlspecialchars(stripslashes($FREQUEST->postvalue('mail_subject'))); ?></td>
                  </tr>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                  </tr>
                  <tr>
                    <td class="smallText"><b><?php echo TEXT_MESSAGE; ?></b><br><?php if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Enable') { echo (stripslashes($message_text)); } else { echo htmlspecialchars(stripslashes($message_text)); } ?></td>
                  </tr>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                  </tr>
                  <tr>
                    <td>
                    <?php
                    /* Re-Post all POST'ed variables */
                        echo $FREQUEST->getPostValues();
                    ?>
                        <tr>
                            <td align="right">
                            <?php
                                //echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="back"')  . '&nbsp;' . tep_image_submit('button_send_email.gif', IMAGE_SEND_EMAIL);
                            ?>
                            </td>
                        </tr>
                    </table>
                    </tr>
                </table>
				<?php 
				//echo tep_draw_hidden_field('customers_email_address',$FPOST['customers_email_address']);
				//echo tep_draw_hidden_field('chkpwd',$FPOST['check_pwd']);
				?>
              </form>
        <?php
            $jsData->VARS['updateMenu']=",mailsend,";
        }

        function doMailSend(){
        global $FREQUEST,$FPOST,$jsData;
            $customer_id=$FREQUEST->postvalue('rID','int',0);
            if (($FPOST['customers_email_address']) && !($FPOST['back_x']) ) {
            switch ($FPOST['customers_email_address']) {
                case '***':
                    $mail_query = tep_db_query("select customers_firstname,customers_id, customers_lastname, customers_email_address, customers_password from " . TABLE_CUSTOMERS);
                    $mail_sent_to = TEXT_ALL_CUSTOMERS;
                    break;
                case '**D':
                    $mail_query = tep_db_query("select customers_firstname,customers_id, customers_lastname, customers_email_address, customers_password from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
                    $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
                    break;
                default:
                    $customers_email_address = $FREQUEST->postvalue('customers_email_address');
                    $mail_query = tep_db_query("select customers_firstname, customers_id, customers_lastname, customers_email_address, customers_password from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($customers_email_address) . "'");
                    $mail_sent_to = $FREQUEST->postvalue('customers_email_address');
                    break;
            }
            $from = $FREQUEST->postvalue('mail_from');
            $subject = $FREQUEST->postvalue('mail_subject');
            $message = $FREQUEST->postvalue('message_text');
            $chkpwd=$FREQUEST->postvalue('check_pwd');

            //Let's build a message object using the email class
            $mimemessage = new email(array('X-Mailer: osConcert'));
            // add the message to the object
            while ($mail = tep_db_fetch_array($mail_query)) {
                $message_detail=$message;
                $pos=strpos($message,"%%Password%%");
            if($chkpwd=='Y' && $pos)
            {
            $pww=tep_rand(5);
            $password=tep_encrypt_password($pww);
            tep_db_query ("update " . TABLE_CUSTOMERS . " set customers_password = '". tep_db_input($password) ."' where customers_id = '" . (int)$mail['customers_id']. "'");
            }
            else
            {
            $pww="---- Hidden----";
            }
                $merge_details=array(	TEXT_FN=>$mail['customers_firstname'],
                                        TEXT_LN=>$mail['customers_lastname'],
                                        TEXT_SN=>STORE_NAME,
                                        TEXT_SM=>STORE_OWNER,
                                        TEXT_SE=>STORE_OWNER_EMAIL_ADDRESS,
                                      	TEXT_LA=>'<a href="' . tep_catalog_href_link(FILENAME_AUTOLOGIN,'email=' . $mail['customers_email_address'] . '&id=' .(($password=='')?$mail['customers_password']:$password)) . '">' .tep_catalog_href_link(FILENAME_AUTOLOGIN,'email=' . $mail['customers_email_address'] . '&id=' . (($password=='')?$mail['customers_password']:$password))  . '</a>',
                                        TEXT_EA=>$mail['customers_email_address'],
                                        TEXT_PWD=>$pww
                                    );

                while(list($key,$value)=each($merge_details))
                    $message_detail=str_replace("%%" . $key  . "%%",$value,$message_detail);

                $message_text=strip_tags($message_detail,'<br>');
                $message_text=str_replace('<br>',chr(13) . chr(10),$message_text);
                $message_text=str_replace('<BR>',chr(13) . chr(10),$message_text);

                if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Disable') {
                    $mimemessage->add_text($message_text);
                } else {
                    $mimemessage->add_html($message_detail,$message_text);
                }
                
                $mimemessage->build_message();
				
                //echo $mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], '', $from, $subject,$message_detail;
                $mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], STORE_OWNER, $from, $subject);
            }
        }
        $this->doInfo($customer_id);

        }

		
            }
		function getListTemplate()
		{
			
			ob_start();
			getTemplateRowTop();
			?>
			
			
			<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
  				<tr>
    				<td>
						<table border="0" cellpadding="0" cellspacing="0" width="100%" ##STYLE## id = "style##ID##">
        					<tr>
          						<td width="15" id="##TYPE####ID##status" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});">##STATUS##</td>
          						<td width="13%" class="main" ##COL_STYLE## onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##lname">##NAME##</td>
          						<td width="12%" class="main" style="padding-left:10px" align="left" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##fname">##FNAME##</td>
								<td width="25%" class="main" style="padding-left:10px" align="left" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##email">##EMAIL##</td>
								<td width="15%" class="main" style="padding-left:15px" align="left" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##create">##CREATED##</td>
          						<td width="15%" class="main" align="left" style="padding-left:17px" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##modify">##MODIFY##</td>
          						<td  width="20%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
                                    <span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
                                        <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'ChangePwd','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/copy_blue.gif" title="<?php echo TEXT_CHANGE_PASSWORD; ?>"/></a>
                                        <img src="##IMAGE_PATH##template/img_bar.gif"/>
										 <?php if (HIDE_DATA_PROTECT=='false'){
					  
										?>
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'ExportData','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/blue_download.png" title="<?php echo TEXT_GDPR_EXPORT; ?>"/></a>
                                        <img src="##IMAGE_PATH##template/img_bar.gif"/>
										<?php
										 }
										?>
                                        <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="<?php echo TEXT_EDIT; ?>"/></a>
                                        <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                        <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteDisplay','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="<?php echo TEXT_DELETE; ?>"/></a>
                                        <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                        <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Wallet','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/img_attrib.gif" title="Wallet"/></a>
                                        <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                        <a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'CustomerMail','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/mail.gif" title="Email"/></a>
                                        <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                    </span>
                                    <span id="##TYPE####ID##mupdate" style="display:none">
                                        <a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','imgUpdate':false,'type':'##TYPE##','style':'boxRow','validate':customerValidate,'uptForm':'customers','customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
                                        <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                        <a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow',extraFunc:hide_info});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
                                    </span>
                                     <span id="##TYPE####ID##mupdatepwd" style="display:none">
                                        <a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'UpdatePwd','imgUpdate':false,'type':'##TYPE##','style':'boxRow','validate':pwdValidate,'uptForm':'customerspwd','customUpdate':doItemUpdatePwd,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
                                        <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                        <a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow',extraFunc:hide_info});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
                                    </span>
                                     <span id="##TYPE####ID##mwallet" style="display:none">
                                        <a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'WalletUpdateConfirm','imgUpdate':false,'type':'##TYPE##','style':'boxRow','validate':paymentValidate,'uptForm':'customers','customUpdate':doPaymentUpdate,'result':doCheckPaymentResult,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
                                        <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                        <a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
                                    </span>
                                    <span id="##TYPE####ID##mwalletconfirm" style="display:none">
                                        <a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'WalletUpdateProcess','imgUpdate':false,'type':'##TYPE##','style':'boxRow','customUpdate':doPaymentProcess,'result':doDisplayResult,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
                                        <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                        <a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
                                    </span>
                                    <span id="##TYPE####ID##mmail" style="display:none">
                                        <a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'MailPreview','imgUpdate':false,'type':'##TYPE##','style':'boxRow','validate':customermailValidate,'customUpdate':doMailUpdate,'result':doDisplayResult,extraFunc:textEditorRemove,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
                                        <img src="##IMAGE_PATH##template/img_bar.gif"/>
                                        <a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##',extraFunc:textEditorRemove,'style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
                                    </span>
                                    <span id="##TYPE####ID##mmailsend" style="display:none">
                                        <a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'MailSend','imgUpdate':false,'type':'##TYPE##','style':'boxRow','customUpdate':doMailSend,'result':doDisplayResult,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
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
	  }//function getListTemplate ends	  
	
	  function getInfoTemplate()
	  {
		 ob_start();
		 ?>
 		<table border="0" cellpadding="4" cellspacing="0" width="100%">
  		  <div class="hLineGray"></div>
  			<tr>
			<td>&nbsp;</td>
    			<!--<td width="12%" align="right" nowrap="nowrap" style="overflow:hidden;" class="main"><b>##IMAGE##</b>&nbsp;&nbsp;<b>##FIRSTNAME##</b>&nbsp;&nbsp;<b>##LASTNAME##</b></td>-->
  			</tr>
  			<tr>
    			<td width="15%" style="padding-left:8px,overflow:hidden;" align="left" nowrap="nowrap" class="main"><b>##EMAIL##</b>&nbsp;&nbsp;<b>##EMAILADD##</b></td>
    			<td width="25%" align="left" style="overflow:hidden"  class="main"><b>##ACCOUNTCRT##</b>&nbsp;&nbsp;<b>##DATECRT##</b></td>
    			<td width="25%" align="left" style="overflow:hidden" class="main"><b>##LOGON##</b>&nbsp;&nbsp;<b>##LASTLOGON##</b></td>
  		    </tr>
 			<tr>
    			<td width="15%" style="padding-left:8px,overflow:hidden;" align="left" nowrap="nowrap" class="main"><b>##COUNTRY##</b>&nbsp;&nbsp;<b>##COUNTRYNAME##</b></td>
    			<td width="25%" align="left" style="overflow:hidden"  class="main"><b>##MODIFY##</b>&nbsp;&nbsp;<b>##LASTMODIFY##</b></td>
    			<td width="30%" align="left" style="overflow:hidden" class="main"><b>##NOLOGAN##</b>&nbsp;&nbsp;<b>##NUMBERLOGAN##</b>&nbsp;&nbsp;<a href="##LINK##">##BUTTON##</a></td>
  			</tr>
		</table>
		<?php
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;		
	 }//function getInfoTemplate ends

function table_exists($tablename, $database = false)
{
    $res = tep_db_query("
         SHOW TABLES IN " . DB_DATABASE . " LIKE '$tablename'
        ");
    
    if (tep_db_num_rows($res) < 1) {
        return 0;
    } else {
        return 1;
    }
	}

 ?>
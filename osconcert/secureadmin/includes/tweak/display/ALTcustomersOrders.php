<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare

    Released under the GNU General Public License
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
class customersOrders{
    var $pagination;
    var $splitResult;
    var $type;
    function __construct() {
        $this->pagination=true;
        $this->splitResult=true;
        $this->type = 'custord';
    }

    function doCustomersOrdersList($search='',$filter_param='',$order)
    {
        global $FSESSION,$FREQUEST,$jsData;
        $page=$FREQUEST->getvalue('page','int',1);
        //echo $filter_param."<br>";
        //echo $search;
       
        $query_split=false;
        if($order == 0)
        {
           $orders_query_raw = "select
			  o.orders_id,
			  MAX(o.payment_return1),
			  MAX(o.payment_return2),
			  MAX(o.ticket_printed),
			  op.categories_name,
			  MAX(o.reference_id),
			  MAX(op.products_quantity),
			  MAX(o.customers_name) as order_name,
			  o.billing_name,
			  o.payment_method,
			  o.date_purchased,
			  MAX(o.last_modified),
			  MAX(o.currency),
			  MAX(o.currency_value),
			  s.orders_status_name,
			  MAX(op.products_type),
			  MAX(op.products_name),
			  MAX(op.products_model),
			  MAX(ot.text) as order_total
			  from " . TABLE_ORDERS . "
			  o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id),
			  " . TABLE_ORDERS_STATUS . " s,
			  " .TABLE_ORDERS_PRODUCTS." op
			  where o.orders_status = s.orders_status_id
			  and o.orders_id=op.orders_id
			  and s.language_id = '" . (int)$FSESSION->languages_id . "'
			  and ot.class = 'ot_total' ".$search . " ".$filter_param." group by o.orders_id, o.date_purchased,o.billing_name,o.payment_method,s.orders_status_name  order by o.orders_id DESC";
           
        }

        if($order == 1)
        {
            $value=$FREQUEST->getvalue('value');
            $orders_query_raw = "select o.orders_id, o.payment_return1, o.payment_return2, o.reference_id, op.products_quantity,MAX(o.customers_name) as order_name, o.billing_name, o.payment_method, o.date_purchased, op.products_model, o.last_modified, o.currency, o.currency_value, s.orders_status_name, op.products_type, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s, " .TABLE_ORDERS_PRODUCTS." op, ".TABLE_CUSTOMERS ." c where o.customers_id=c.customers_id and o.orders_status = s.orders_status_id and o.orders_id=op.orders_id and s.language_id = '" . (int)$FSESSION->languages_id . "' and ot.class = 'ot_total' ".$search . " ".$filter_param." group by o.orders_id  order by ".$value." ASC ";

        }

        // if($order == 1)
        // {
            // $value=$FREQUEST->getvalue('value');
            // $orders_query_raw = "select 
			// o.orders_id, 
			// MAX(o.payment_return1),
			  // MAX(o.payment_return2),
			  // MAX(o.ticket_printed),
			  // MAX(op.categories_name),
			  // MAX(o.reference_id),
			  // MAX(op.products_quantity),
			  // MAX(o.customers_name) as order_name,
			  // o.billing_name,
			  // o.payment_method,
			  // o.date_purchased,
			  // MAX(o.last_modified),
			  // MAX(o.currency),
			  // MAX(o.currency_value),
			  // s.orders_status_name,
			  // MAX(op.products_type),
			  // MAX(op.products_name),
			  // op.products_model,
			  // MAX(ot.text) as order_total 
			// from " . TABLE_ORDERS . " 
			// o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), 
			// " . TABLE_ORDERS_STATUS . " s, 
			// " .TABLE_ORDERS_PRODUCTS." op, 
			// ".TABLE_CUSTOMERS ." c 
			// where o.customers_id=c.customers_id 
			// and o.orders_status = s.orders_status_id 
			// and o.orders_id=op.orders_id 
			// and s.language_id = '" . (int)$FSESSION->languages_id . "' 
			// and ot.class = 'ot_total' ".$search . " ".$filter_param." group by o.orders_id, o.date_purchased,o.billing_name,o.payment_method,s.orders_status_name order by ".$value." ASC ";

        // }
        if($order == 2)
        {
            $value=$FREQUEST->getvalue('value');
             $orders_query_raw = "select o.orders_id, 
			MAX(o.payment_return1),
			  MAX(o.payment_return2),
			  MAX(o.ticket_printed),
			  op.categories_name,
			  MAX(o.reference_id),
			  MAX(op.products_quantity),
			  MAX(o.customers_name) as order_name,
			  o.billing_name,
			  o.payment_method,
			  o.date_purchased,
			  MAX(o.last_modified),
			  MAX(o.currency),
			  MAX(o.currency_value),
			  s.orders_status_name,
			  MAX(op.products_type),
			  MAX(op.products_name),
			  MAX(op.products_model),
			  MAX(ot.text) as order_total 
			from " . TABLE_ORDERS . " 
			o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), 
			" . TABLE_ORDERS_STATUS . " s, 
			" .TABLE_ORDERS_PRODUCTS." op, 
			".TABLE_CUSTOMERS ." c 
			where o.customers_id=c.customers_id 
			and o.orders_status = s.orders_status_id 
			and o.orders_id=op.orders_id 
			and s.language_id = '" . (int)$FSESSION->languages_id . "' 
			and ot.class = 'ot_total' ".$search . " ".$filter_param." group by o.orders_id order by ".$value." ASC ";

        }
        if($order == 2)
        {
            $value=$FREQUEST->getvalue('value');
             $orders_query_raw = "select o.orders_id, 
			MAX(o.payment_return1),
			  MAX(o.payment_return2),
			  MAX(o.ticket_printed),
			  op.categories_name,
			  MAX(o.reference_id),
			  MAX(op.products_quantity),
			  MAX(o.customers_name) as order_name,
			  o.billing_name,
			  o.payment_method,
			  o.date_purchased,
			  MAX(o.last_modified),
			  MAX(o.currency),
			  MAX(o.currency_value),
			  s.orders_status_name,
			  MAX(op.products_type),
			  MAX(op.products_name),
			  MAX(op.products_model),
			  MAX(ot.text) as order_total
			 from " . TABLE_ORDERS . " o left join 
			 " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), 
			 " . TABLE_ORDERS_STATUS . " s, 
			 " .TABLE_ORDERS_PRODUCTS." op, 
			 ".TABLE_CUSTOMERS ." c 
			 where o.customers_id=c.customers_id 
			 and o.orders_status = s.orders_status_id 
			 and o.orders_id=op.orders_id 
			 and s.language_id = '" . (int)$FSESSION->languages_id . "' 
			 and ot.class = 'ot_total' ".$search . " ".$filter_param." group by o.orders_id order by ".$value." DESC";

        }
        if ($this->pagination)
        {
            $query_split=$this->splitResult = (new instance)->getSplitResult('CUSTORD');
            $query_split->maxRows=80;//MAX_DISPLAY_SEARCH_RESULTS;
            $query_split->parse($page,$orders_query_raw);
            if ($query_split->queryRows > 0)
            {

                if($FREQUEST->getvalue('search')!='')
                $param= $FREQUEST->getvalue('search');

                if($FREQUEST->getvalue('filter')!='')
                $filter= $FREQUEST->getvalue('filter');

                // $query_split->pageLink="doPageAction1({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'GetCustomersOrders','result':doTotalResult,params:'" .$param . "page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_DATA,'##PAGE_NO##') . "'})";
                $query_split->pageLink="doPageAction1({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'GetCustomersOrders','result':doTotalResult,params:'page='+##PAGE_NO##,filter:'".$filter."',search:'".$param."','message':'" . sprintf(INFO_LOADING_DATA,'##PAGE_NO##') . "','orderby':'".$order."','value':'".$value."'})";
            }
        }

       $orders_query=tep_db_query($orders_query_raw);
       
        $found=false;
        if (tep_db_num_rows($orders_query)>0) $found=true;

        if($found)
        {
            $template=getListTemplate();

            $icnt=1;
            while($order_result=tep_db_fetch_array($orders_query))
            {

				$ticket_print="specialPrice";
				$rep_array=array(	"ID"=>$order_result["orders_id"],
                                        "TYPE"=>$this->type,
                                        "NAME"=> stripslashes($order_result["customers_name"]),
										"BILLING_NAME"=>stripslashes($order_result["billing_name"]),
										"ORDER_NAME"=>stripslashes($order_result["order_name"]),
                                        "IMAGE_PATH"=>DIR_WS_IMAGES,
                                        "ORDER_ID"=>$order_result["orders_id"],
										"SHOW"=>$order_result["categories_name"],
										"PRICE"=>$order_result["products_price"],
                                        "STATUS"=>tep_image(DIR_WS_IMAGES.'template/icon_active.gif'),
                                        "TOTAL"=>strip_tags($order_result['order_total']),
                                        "DATE"=>date(EVENTS_DATE_FORMAT,strtotime($order_result['date_purchased'])),
										
									
					"PM"=>$order_result['payment_method'],
                                        "STATUS_NAME"=>$order_result['orders_status_name'],
										"TICKET"=>$order_result['ticket_printed'],
                                        "UPDATE_RESULT"=>'doDisplayResult',
                                        "UPDATE_DATA"=>TEXT_UPDATE_DATA,
                                        "ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
                                        "ROW_CLICK_GET"=>'Info',
                                        "FIRST_MENU_DISPLAY"=>"",
                                        "PAGE"=>$page
                );
                echo mergeTemplate($rep_array,$template);
                $icnt++;
            }
            if (!isset($jsData->VARS["Page"]))
            {
                $jsData->VARS["NUclearType"][]=$this->type;
            }
        }
        else
        {
            echo TEXT_NO_RECORDS_FOUND;
        }
		if($search!="" || ($filter_param!='' && !$found))
        { ?>

	<tr>
		<td class="main"><a href="javascript:void(0);" onClick="javascript:doOrderSearch('reset');">
		<?php echo tep_image_button('button_reset.gif',IMAGE_RESET);?></a></td>
	</tr>
	<?php
	}
			
	return $found;
	}

	function doUpdateCustomerOrder(){
	global $FREQUEST,$FPOST,$FSESSION,$jsData;
	$server_date = getServerDate(true);
	$ID = $FREQUEST->postvalue('oID');
	$status = $FREQUEST->postvalue('status');

	$comments = $FREQUEST->postvalue('comments');
	$shipping_date=$FREQUEST->postvalue('shipping_date','string','0000-00-00');
	if($shipping_date!='0000-00-00'){
	$shipping_date = tep_convert_date_raw($shipping_date);
	}
	$order_updated = false;
	$check_status_query = tep_db_query("select o.customers_name, o.customers_email_address, o.orders_status, o.date_purchased, op.orders_products_status from " . TABLE_ORDERS . " o," . TABLE_ORDERS_PRODUCTS . " op  where o.orders_id=op.orders_id and o.orders_id = '" . (int)$ID . "'");
	$check_status = tep_db_fetch_array($check_status_query);
	tep_db_query("update " . TABLE_ORDERS . " set shipping_date = '" . tep_db_input($shipping_date) . "', last_modified = '" . tep_db_input($server_date) . "' where orders_id = '" . (int)$ID . "'");
	if($check_status['orders_status'] != $status)	
	{
	if($check_status['orders_status']==1) //if pending
	if($status==2 || $status==3)  // to processing or deliverd
	tep_db_query("update " . TABLE_ORDERS . " set date_paid = '" . tep_db_input($server_date) . "' where orders_id = '" . (int)$ID . "'");
	}
	if(($check_status['orders_status'] != $status) || $comments != '' || ($status ==DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE))
	{
	tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = '" . tep_db_input($server_date) . "' where orders_id = '" . (int)$ID . "'");
	tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set orders_products_status= '" . tep_db_input($status) . "' where orders_id = '" . (int)$ID . "'"); 
	$check_status_query2 = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$ID . "'");
	$check_status2 = tep_db_fetch_array($check_status_query2);
	if ($check_status2['orders_status']==DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE ) {
		tep_db_query("update " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " set download_maxdays = '" . tep_db_input(tep_get_configuration_key_value('DOWNLOAD_MAX_DAYS')) . "', download_count = '" . tep_db_input(tep_get_configuration_key_value('DOWNLOAD_MAX_COUNT')) . "' where orders_id = '" . (int)$ID . "'");
	}
	}
	// Modified By RMA 16/07/2011 START
	$prodQty =  $FREQUEST->postvalue('prodqty');
	if( is_array( $prodQty ) ) {
	  foreach( $prodQty as $oid => $qty ){
		$oid = (int)($oid);
		$qty = (int)($qty);
		tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set products_quantity= '" . tep_db_input($qty) . "' where orders_id = '" . (int)$ID . "' AND orders_products_id=". $oid );   
	  }
	}
	// Modified By RMA 16/07/2011 END
	$customer_notified = '0';
	if (($FREQUEST->postvalue('notify')) && ($FREQUEST->postvalue('notify') == 'on')) {
	$notify_comments = '';
	if (($FREQUEST->postvalue('notify_comments')) && ($FREQUEST->postvalue('notify_comments') == 'on')) {
		$notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
	}
	if($check_status['orders_status'] != $status){
	tep_send_products_status_change_email($ID, $status, '', $notify_comments);
	}
	$customer_notified = '1';
	}  
	$user_name=tep_admin_name($FSESSION->login_id);
	tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments,user_added) values ('" . (int)$ID . "', '" . (int)$status . "', '" . tep_db_input($server_date) . "', '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "','" . tep_db_input($user_name) . "')");
	if ($insert) {
	$this->doGetCustomersOrders();
	} else {
	$status_query="select orders_status_name from " . TABLE_ORDERS_STATUS . "  where orders_status_id ='" . (int)$status . "'";

	$orders_status_query = tep_db_query($status_query);
	if (tep_db_num_rows($orders_status_query)>0){
		$status_result=tep_db_fetch_array($orders_status_query);
	}
	$new_status=$status_result["orders_status_name"];

	$jsData->VARS["replace"]=array($this->type . $ID . "status"=>$new_status);
	$jsData->VARS["prevAction"]=array('id'=>$ID,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
	$this->doInfo($ID);
	$jsData->VARS["updateMenu"]=",normal,";
	}
	}
function doRefundProcess()
{
	global $FREQUEST,$FSESSION,$FPOST,$currencies,$status_result,$order,$jsData;
	$server_date=getServerDate();
	$server_date1=getServerDate(true);										  
	$oID=$FREQUEST->getvalue('oID');

	include(DIR_WS_CLASSES . 'order.php'); 	 
	$selected_orders=$FREQUEST->postvalue('selected_orders');
	$orders_keys=explode(',',$selected_orders);
	$selected_events=$FREQUEST->postvalue('selected_events');
	$events_keys=explode(',',$selected_events);
	$order = new order($oID);
	tep_get_last_access_file();
	$chk_type=$FREQUEST->postvalue('chk_type');
	$chk_choice=$FREQUEST->postvalue('chk_choice');
	$txt_amount=$FREQUEST->postvalue('txt_amount','int','12');
	$refund_comments=$FREQUEST->postvalue('refund_comments');
	$chk_advise=$FREQUEST->postvalue('chk_advise');
	$order_total_amount=$FREQUEST->postvalue('total_amt');
	$command=$FREQUEST->getvalue('command');
	$is_restock=$FREQUEST->postvalue('is_restock');
	$prodQty =  $FREQUEST->postvalue('prodqty');										 

	$status_query=tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where lower(orders_status_name)='refunded'");
	$status_result=tep_db_fetch_array($status_query);

	$sql_array=array('orders_id' =>$oID,
		'customers_id' =>$order->customer['id'],
		'refund_type' =>$chk_type,
		'amount_type' =>$chk_choice,
		'refund_amount' => $txt_amount,
		'comments' => $refund_comments,
		'date_created' => $server_date1);

	if($command!='')				
	{
		switch($command)
		{
			case 'save':
			$notify='0';
			for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
				if(strtolower($order->totals[$i]['title'])==strtolower('total:')){
					$order_amount=$order->totals[$i]['text'];
					
					// this was added?

					$order_amount_value = $order->totals[$i]['value'];
					break;
				}
			}
			if($chk_choice=='A')
			{

				$amount=$currencies->format($txt_amount);
			}
			else if($chk_choice=='%'){

				$amount=$currencies->format((($txt_amount) * ($order_total_amount))/100) . " (" . $txt_amount . $chk_choice . ") " ;
				$txt_amount=(($txt_amount) * ($order_total_amount))/100;
			}

			if($chk_type=='F')
			{
				$type=TEXT_FULLY;
				$amount=$order_amount;
				$txt_amount=$order_total_amount;
				$chk_choice='';
			}
			else 
			{
				$type=TEXT_PARTIALLY;
			}


			if($chk_type=='F')
			{
			$rcomments='Fully Refunded on ' . format_date($server_date) . ". Refund Amount: " . $amount . ". " . $refund_comments;
			}else if($chk_type=='P')
			{
			$rcomments='Partial Refund on ' . format_date($server_date) . ". Refund Amount: " . $amount . ". " . $refund_comments;
			}
			if($txt_amount=='') 
				$txt_amount=0;
				$order_pid=$FREQUEST->postvalue('opid');
				$parray=preg_split("/,/",$order_pid);

			if($order->info['total']>0) 
			{
				$sql_array=array('orders_id' =>$oID,
					'customers_id' =>$order->customer['id'],
					'refund_type' =>$chk_type,
					'amount_type' =>$chk_choice,
					'refund_amount' => $txt_amount,
					'comments' => $refund_comments,
					'date_created' => $server_date1);
				tep_db_perform(TABLE_REFUNDS,$sql_array);
	
if($chk_advise=='Y')
{
						define("ORD_OSU","Order_Status_Update");
						define("ORD_RFC","Refund_Comments");
						define("ORD_RFA","Refund_Amount");
						define("ORD_TOA","Order_Amount");
						define("ORD_OAR","Order_Amount_Refunded");
						define("TEXT_FN","First_Name");
			
			$check_query = tep_db_query("
	                      SELECT customers_language 
	                      FROM " . TABLE_ORDERS . " 
						  WHERE orders_id = '" . (int)$oID . "'");
						  
    $check = tep_db_fetch_array($check_query); 
	
	$customers_language=$check['customers_language'];
	$check_language_query = tep_db_query("SELECT directory from languages WHERE languages_id = '" . (int)$customers_language . "'");
	$check_language = tep_db_fetch_array($check_language_query);
	$customers_language_directory=$check_language['directory'];
						
			include(DIR_WS_LANGUAGES . $customers_language_directory . '/templates.php');
				
			$orders_query_raw = tep_db_query("select 
			o.orders_id, 
			MAX(o.payment_return1),
			  MAX(o.payment_return2),
			  MAX(o.ticket_printed),
			  MAX(op.categories_name),
			  MAX(o.reference_id),
			  MAX(op.products_quantity),
			  MAX(o.customers_name) as order_name,
			  MAX(o.billing_name),
			  MAX(o.payment_method),
			  MAX(o.date_purchased),
			  MAX(o.last_modified),
			  MAX(o.currency),
			  MAX(o.currency_value),
			  MAX(s.orders_status_name),
			  MAX(op.products_type),
			  MAX(op.products_name),
			  MAX(op.products_model),
			  MAX(ot.text) as order_total
			from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s, " .TABLE_ORDERS_PRODUCTS." op, ".TABLE_CUSTOMERS ." c where o.customers_id=c.customers_id and o.orders_status = s.orders_status_id and o.orders_id=op.orders_id and s.language_id = '" . (int)$FSESSION->languages_id . "' and ot.class = 'ot_total' and o.orders_id='".$oID ."'  order by o.orders_id group by o.orders_id DESC,customers_name ASC");
			if (tep_db_num_rows($orders_query_raw)>0){
			$order_result=tep_db_fetch_array($orders_query_raw);	
			
				$merge_details[REFUND_TEXT_1]=TEXT_OAR_MESSAGE1;
				
				
						$merge_details[TEXT_FB]=EMAIL_TEXT_DATE_ORDERED . ' ' . $order_result['date_purchased'];
						$merge_details[TEXT_FN]=$order_result['customers_firstname'];
						$merge_details[TEXT_LN]=$order_result['customers_lastname'];
						$merge_details[TEXT_AU]=$order_result['customers_username'];
						$merge_details[TEXT_LE]=$order_result['customers_email_address'];
						$merge_details[TEXT_LP]="--SECRET--";

			}						
				
				
				$merge_details[ORDR_NO]=(int)$oID;
				
				$merge_details[ORDR_OL]=tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . (int)$oID, 'SSL');
				$merge_details[ORD_RFC]=$refund_comments;
				$merge_details[ORD_RFA]=TEXT_REFUND_AMOUNT . $txt_amount;
				$merge_details[ORD_TOA]=TEXT_ORDER_AMOUNT. $amount;
				$merge_details[ORD_OAR]=ORDER_AMOUNT_REFUNDED;
				
				
				$merge_details[TEXT_SP]="";//HTTP_SERVER . DIR_WS_CATALOG . "images/".COMPANY_LOGO;
				$merge_details[TEXT_SL]='<a href="' . tep_catalog_href_link(FILENAME_DEFAULT) . '">' . STORE_NAME . '</a>';
				$merge_details[TEXT_SM]=STORE_NAME;
				$merge_details[TEXT_SN]=STORE_OWNER;
				$merge_details[TEXT_SE]=STORE_OWNER_EMAIL_ADDRESS;

						$notify='1';
						
						$send_details[0]['to_name'] = $order->customer['name'];
						$send_details[0]['to_email'] =  $order->customer['email_address'];
						$send_details[0]['from_name']=STORE_OWNER;
						$send_details[0]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
			
						// $details['to_name']=$order->customer['name'];
						// $details['to_email']=$order->customer['email_address'];
						// $details['from_name']=STORE_OWNER;
						// $details['from_email']=STORE_OWNER_EMAIL_ADDRESS;
						// $details['subject']='Order Amount Refund';
						// $details["html_text"]='Order Amount Refunded'."<br>".TEXT_ORDER_AMOUNT. $order_amount ."<br>". TEXT_REFUND_TYPE .$type . '<br>' .  TEXT_REFUND_AMOUNT . $amount . '<br>' . $refund_comments;
						//tep_send_email($details,true);
						tep_send_default_email("OAR",$merge_details,$send_details);
		}

				if($chk_type=='F')
				{
					tep_db_query("update " . TABLE_ORDERS . " set orders_status='" .(int)$status_result['orders_status_id'] . "' where orders_id ='" .(int)$oID . "'");

					if($is_restock=='Y'){
						for($jcnt=0;$jcnt<sizeof($parray);$jcnt++){

							if($FREQUEST->postvalue('chk_restock' . $parray[$jcnt])=='Y'){
								clear_ticket_tables(tep_db_input($parray[$jcnt]), (int)$oID );
								$restock_id.=$parray[$jcnt] . ",";
								$events_query=tep_db_query("select products_type,orders_id ,products_quantity,products_id,orders_products_id,support_packs_type from " . TABLE_ORDERS_PRODUCTS . " where orders_products_id='" . tep_db_input($parray[$jcnt]) . "'");
								$events_result=tep_db_fetch_array($events_query);
		 
									if($events_result['products_type']=='P')
									$this->products_restock($events_result);
													   
									tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set products_quantity=0, orders_products_status='" . (int)$status_result['orders_status_id'] . "' where orders_products_id ='" . (int)$parray[$jcnt] . "'");

							}
						}
					}
				} 
				else if($chk_type=='P') 
				{

					if($is_restock=='Y'){
						for($jcnt=0;$jcnt<sizeof($parray);$jcnt++){

							if($FREQUEST->postvalue('chk_restock' . $parray[$jcnt])=='Y')
							{
								clear_ticket_tables(tep_db_input($parray[$jcnt]), (int)$oID );
								$restock_id.=$parray[$jcnt] . ",";
								$events_query=tep_db_query("select products_type,orders_id ,products_quantity,products_id,orders_products_id,support_packs_type from " . TABLE_ORDERS_PRODUCTS . " where orders_products_id='" . (int)$parray[$jcnt] . "'");
								$events_result=tep_db_fetch_array($events_query);

									if($events_result['products_type']=='P')
									$this->products_restock($events_result);
			 

									tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set products_quantity=0,orders_products_status='" . (int)$status_result['orders_status_id'] . "' where orders_products_id ='" . (int)$parray[$jcnt] . "'");

							}
						}
						$query=tep_db_query("select orders_products_id,products_name,products_id, products_type  from " . TABLE_ORDERS_PRODUCTS . " where orders_id='" . (int)$oID . "'  and orders_products_status!='5'");
						if(tep_db_num_rows($query)<=0)

						tep_db_query("update " . TABLE_ORDERS ." set orders_status='" . (int)$status_result['orders_status_id'] . "' where orders_id='" . (int)$oID . "'");
					}
				}

				tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set events_type='F' where orders_id='" . (int)$oID ."'");
				tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY ." (orders_id,orders_status_id,date_added,customer_notified,comments,user_added) values('" . tep_db_input($oID) . "','" . tep_db_input($status_result['orders_status_id']) . "','" . tep_db_input($server_date1) . "','" . tep_db_input($notify) ."','" . tep_db_input($rcomments) . "','') " );

			} 
			else
			{
				echo '<table cellpadding="3" cellspacing="5" border="0" width="80%" align="center"><tr><td class="main">Cannot Refund. Because the Order Amount is: ' . $order_amount . '<td><tr><tr><td>' . tep_draw_separator('pixel_trans.gif','10','10'). '</td></tr></table>';
			}
			break;
		}

	$qu="select  s.orders_status_name from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s, " .TABLE_ORDERS_PRODUCTS." op, ".TABLE_CUSTOMERS ." c where o.customers_id=c.customers_id and o.orders_status = s.orders_status_id and o.orders_id=op.orders_id and s.language_id = '" . (int)$FSESSION->languages_id . "' and ot.class = 'ot_total' and o.orders_id='".$oID ."' group by o.orders_id,s.orders_status_name order by o.orders_id DESC,customers_name ASC";

	$orders_query_raw1 = tep_db_query($qu);
	if (tep_db_num_rows($orders_query_raw1)>0){
		$order_result1=tep_db_fetch_array($orders_query_raw1);
	}
	$status1=$order_result1["orders_status_name"];

	$jsData->VARS["replace"]=array($this->type.$oID."status"=>$status1);
	$jsData->VARS["prevAction"]=array('id'=>$oID,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');

	$this->doInfo($oID);
	$jsData->VARS["updateMenu"]=",normal,";
	}
}
	//cartzone set product status to 1
	function products_restock($events_result){
	global $order;
	#######if MULTI seats
	if ($events_result['support_packs_type']=='P'){
	tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity='1', products_status='1', products_ordered=products_ordered - " . $events_result['products_quantity'] . " where products_id='" . (int)$events_result['products_id'] . "'");
	}
	#######cartzone if GA
	if (($events_result['support_packs_type']=='G')or($events_result['support_packs_type']=='B')){
	tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity=products_quantity+" . $events_result['products_quantity'] . ", products_ordered=products_ordered - " . $events_result['products_quantity'] . " where products_id='" . (int)$events_result['products_id'] . "'");

	ga_update((int)$events_result['products_id'], $events_result['products_quantity'], $events_result['support_packs_type']);
	}
	#######if type=F (family)
	if ($events_result['support_packs_type']=='F'){
	tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity=products_quantity+" . FAMILY_TICKET_QTY . ", products_ordered=products_ordered - " . $events_result['products_quantity'] . " where products_id='" . (int)$events_result['products_id'] . "'");

	ga_update((int)$order_products['products_id'], $order_products['products_quantity'], $order_products['support_packs_type']);
	}



	}

	// function tep_get_attbid($params) 
	// { 
	// if ( (is_array($params))) 
	// {
		// //FOREACH xx
	// while (list($option, $value) = each($params)) {
		// $uprid .= $option . '{' . $value . '}-' ;
	// }
	// }

	// return substr($uprid,0,-1);
	// }


	function doEditRefundCustomerOrder()
	{
	global $FREQUEST,$FSESSION,$LANGUAGES,$CAT_TREE,$jsData,$currencies;
	$languages=&$LANGUAGES;
	$order_id=$FREQUEST->getvalue('oID','int',0);

	$qu="select  s.orders_status_id from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s, " .TABLE_ORDERS_PRODUCTS." op, ".TABLE_CUSTOMERS ." c where o.customers_id=c.customers_id and o.orders_status = s.orders_status_id and o.orders_id=op.orders_id and s.language_id = '" . (int)$FSESSION->languages_id . "' and ot.class = 'ot_total' and o.orders_id='".$order_id ."' group by o.orders_id,s.orders_status_id order by o.orders_id DESC,customers_name ASC";

	$orders_query_raw1 = tep_db_query($qu);
	if (tep_db_num_rows($orders_query_raw1)>0){
	$order_result1=tep_db_fetch_array($orders_query_raw1);
	}
	$status1=$order_result1["orders_status_id"];
	if($status1=='5')
	{
	echo '<table cellpadding="3" cellspacing="5" border="0" width="80%" align="center"><tr><td class="main"> ' .TEXT_REFUND_ALREADY.  '<td><tr><tr><td>' . tep_draw_separator('pixel_trans.gif','10','10'). '</td></tr></table>';
	$jsData->VARS["storePage"]=array('lastAction'=>false,'locked'=>false);
	}
	else
	{

	$jsData->VARS["updateMenu"]=",refund,";
	$display_mode_html=' style="display:none"';
	$dis_time_format="";
	if(defined('TIME_FORMAT'))
	$dis_time_format=TIME_FORMAT;
	include(DIR_WS_CLASSES . 'order.php');
	$order = new order($order_id);
	$order_date = $order->info['date_purchased'];
	$split_date = preg_split("/ /",$order_date);
	$order_date = format_date($split_date[0]);

	$orders_statuses = array();
	$orders_status_array = array();
	$orders_statuses=tep_get_orders_status();
	$orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$FSESSION->languages_id . "'");
	while ($orders_status = tep_db_fetch_array($orders_status_query)){
		$orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
	}

	$refund_query=tep_db_query("select sum(rf.refund_amount)  as refund_amount, o.orders_status from " . TABLE_ORDERS . " o, " . TABLE_REFUNDS . " rf where rf.orders_id=o.orders_id and o.orders_id='" . (int)$order_id . "' group by rf.orders_id,o.orders_status");
	$refund_result=tep_db_fetch_array($refund_query);
	$order_total_query=tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where orders_id='" . (int)$order_id."' and class='ot_total'");
	$order_total_result=tep_db_fetch_array($order_total_query);
	$order_total_amount=$order_total_result['value'];
	$order_total_amount=$order_total_amount-$refund_result['refund_amount']	;

	$today_date = getServerDate();	?>

<table border="0" width="100%" cellspacing="5" cellpadding="4">
<tr>
<!-- body_text //-->
<td>
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr >
        <td> <?php echo tep_draw_form('refund',FILENAME_ORDERS_REFUND,'action=update&oID=' . $oID,'post',' onSubmit="return ValidateForm();"');  ?>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td>
                        <Table cellpadding="0" cellspacing="0" border="0" width="100%" style="display:none;">
                            <tr>
                                <td class="pageHeading" width="300" ><?php echo tep_draw_separator('pixel_trans.gif','10','10'). ORDER_REFUND;?></td>
                                <td align="right"><span  id="img_save" ><input type="image" id="save" src="images/template/img_savel.gif" alt="save" title="save" border="0">&nbsp;&nbsp;<a href="javascript:do_page_fetch('<?php echo $oID;?>','close_refund');"><img src="images/template/img_closel.gif"  id="close" alt="close" title="close" border="0"></a></span>
                                <span id="close_refund" style="display:none"><a href="javascript:do_page_fetch('<?php echo $oID;?>','close_refund');"><img src="images/template/img_closel.gif"   alt="close" title="close" border="0"></a></span>&nbsp;&nbsp;</td>
                            </tr>
                        </Table>
                    </td>
                </tr>
                <tr>
                    <td  class="dataTableContent">
                        <table cellpadding="3" cellspacing="5" border="0" width="100%" >
                            <tr>
                                <td width="150" valign="top" class="main"><?php echo TEXT_CUSTOMERS ;?></td>
                                <td class="main" width="300"><?php echo $order->customer['name'];?></td>
                                <td width="150" class="main" valign="top"><?php echo TEXT_ORDERID;?></td>
                                <td class="main" valign="top"><?php echo $order_id; ?></td>
                            </tr>
                            <tr>
                                <td width="150" class="main" valign="top"><?php echo TEXT_EMAIL;?></td>
                                <td class="main" valign="top"><?php echo $order->customer['email_address'];?></td>
                                <td class="main"><?php echo TEXT_ORDER_DATE_TIME;?></td>
                                <td class="main"><?php echo date(EVENTS_DATE_FORMAT . ' h:i:s A',strtotime($order->info['date_purchased']));?></td>
                            </tr>
                            <tr>
                                <td width="150" class="main"><?php echo TEXT_PAYMENT;?></td>
                                <td class="main" width="300"><?php echo $order->info['payment_method'];?></td>
                                <?php
                                for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
                                    if(strtolower($order->totals[$i]['title'])==strtolower('total:')){ ?>
                                <td class="main"><?php echo $order->totals[$i]['title'];?></td>
                                <td class="main"><?php echo $order->totals[$i]['text'];?></td>
                                <?php  }
                        } ?>
                            </tr>
                            <tr>
                                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif','10','10');?></td>
                                <td class="main"><?php echo TEXT_REFUNDABLE_AMOUNT; ?></td>
                                <td class="main"><?php echo $currencies->format($order_total_amount);?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellpadding="2" cellspacing="5" border="0" width="80%" >
                            <tr>	<td class="info_heading" colspan="2"><?php echo DETAILS;?></td></tr>
                            <tr>
                                <td>
                                    <table cellpadding="2" cellspacing="3" border="0" width="100%" style="padding-left:40px;">
                                        <?php	$dis_time_format="";
                                        if(defined('EVENTS_TIME_FORMAT'))
                                        $dis_time_format=EVENTS_TIME_FORMAT;
                                        $products_ordered="";
                                        $product_type="";
                                        $products_name="";
                                        $products = "";
                                        $products_names_p="";
                                        $pre_qty="";
                                        $pre_name="";
                                        $qty_p = "";
                                        $pre_sign="";
                                        $first_event = true;
                                        $first_event_id = 0;
                                        $jj=0;
                                        //$attendees_name="";
                                        $sku="";

                                        for($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
                                            $products_names_p="";
                                            $qty_p = "";
                                            $sign_p="";
                                            $model="";
                                            $products_ordered_attributes="";
  

                                            if (isset($order->products[$i]['others']) && (sizeof($order->products[$i]['others']) > 0)) {
                                                $disp_date=$order->products[$i]['others']['end_date'];
                                                $disp_time=$order->products[$i]['others']['end_time'];
                                                if($disp_date && $disp_time){
                                                    $date_time=strtotime($disp_date.' '.$disp_time)+60;
                                                    $disp_date=date('Y-m-d',$date_time);
                                                    $disp_time=date('h:i A',$date_time);
                                                }
                                            }
                                            if($order->products[$i]['others']['start_time'] || $order->products[$i]['others']['end_time']) {
                                                $disp_stime=date('h:i A',strtotime($order->products[$i]['others']['start_time']));
                                                if($dis_time_format!="") {
                                                    if($dis_time_format=='24') {
                                                        $disp_time=date('H:i',$date_time);
                                                        $disp_stime=date('H:i',strtotime($order->products[$i]['others']['start_time']));
                                                    }
                                                }
                                            }
                                            $products_ordered_attributes1="";
                                            if($order->products[$i]['others']['start_date'])
                                            $products_ordered_attributes1 .= "<br>&nbsp;&nbsp;&nbsp;&nbsp;- " . TEXT_START_DATE . ': &nbsp;&nbsp;' .  format_date($order->products[$i]['others']['start_date']) . '&nbsp;&nbsp;' . $disp_stime;
                                            if($order->products[$i]['others']['end_date'])
                                            $products_ordered_attributes1 .= "<br>&nbsp;&nbsp;&nbsp;&nbsp;- " . TEXT_END_DATE . ': &nbsp;&nbsp;&nbsp;&nbsp;' .  format_date($disp_date) . '&nbsp;&nbsp;' . $disp_time;
                                            if($order->products[$i]['others']['resource_name'])
                                            $products_ordered_attributes1 .= "<br>&nbsp;&nbsp;&nbsp;&nbsp;- " . TEXT_RESOURCE . ': &nbsp;&nbsp;&nbsp;&nbsp;' . $order->products[$i]['others']['resource_name'];
												
											if($order->products[$i]['products_type']=="P"){
                                                $products_names_p = $order->products[$i]['name'];
                                                $qty_p=$order->products[$i]['qty'];
                                                $sign_p="X";
                                                if($order->products[$i]['model'])
                                                $model= " , " . $order->products[$i]['model'];
                                            }

                                            $rax_total=tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'];
                                            $sku = $order->products[$i]['products_sku'];
                                            $event_id = 0;
                                   
                                            $products = array( "qty" => $qty_p,
                                                                'orders_products_id' => $order->products[$i]['orders_products_id'],
                                                                 "sign"  => $sign_p,
																 "id" => $order->products[$i]['id'],
																 "products_type" => $order->products[$i]['products_type'],
																 "p_name"	 => $products_names_p,
																 "event_id" => $event_id,
																 "p_attributes1" => $products_ordered_attributes1,
																 "p_attributes" => $products_ordered_attributes,
																 "model" => $model,
																 "sku" => $sku,
																 "row_total"=>$rax_total
                                            );
                                        }
                                        
                                        $date = array();

                                
                                        for ($i = 0, $n = sizeof($products); $i < $n; $i++) {
                                            if($products[$i]['products_type']=="P"){
                                                $row_total= $products[$i]['row_total'];
                                                $date=$products[$i]['model'];
                                            }
                                      
                                        } ?>
                                        <tr> <?php echo $products_ordered; ?> </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellpadding="2" cellspacing="5" border="0" width="100%" align="center">
                            <tr><td class="info_heading" colspan="2"><?php echo TEXT_REFUND; ?></td></tr>
                            <tr>
                                <td width="50"></td>
                                <td>
                                    <table cellpadding="2" cellspacing="3" border="0" width="100%">
                                        <tr>
                                            <td colspan="2">
                                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                    <tr>
                                                        <?php echo $chk_type.''; ?>
                                                        <td class="main" width="15%" align="left"><?php echo tep_draw_radio_field('chk_type','F','true',($chk_choice=='F')?true:false,'onClick=javascript:do_action()') . '&nbsp;' . TEXT_FULLY;?></td>
                                                        <td class="main" width="50%" align="left"><?php echo tep_draw_radio_field('chk_type','P','','','onClick=javascript:do_action()') . '&nbsp;' . TEXT_PARTIALLY;?></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr id="choice" style="display:none">
                                            <td colspan="2">
                                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                    <tr>
                                                        <td class="main" width="23%"><?php echo tep_draw_radio_field('chk_choice','A','true',($chk_choice=='A')?true:false,'onclick="javascript:change_amount_type(\'A\')"') . '&nbsp;' . TEXT_AMOUNT;?></td>
                                                        <td class="main" width="15%"><?php echo tep_draw_radio_field('chk_choice','%','','','onclick="javascript:change_amount_type()"') . '&nbsp;' . TEXT_PERCENTAGE;?></td>
                                                        <td class="main"><?php echo tep_draw_input_field('txt_amount').'<span id="amount_type">&nbsp;$</span>';?></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="main"><?php echo tep_draw_checkbox_field('chk_advise','Y') . '&nbsp;' . TEXT_ADVISE_CUSTOMERS ; ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                    <tr>
                                                        <td class="main" width="11%" valign="top"><?php echo TEXT_COMMENTS;?></td>
                                                        <td colspan="2" align="left"><textarea class="inputNormal" name="refund_comments" id="refund_comments" rows="5" cols="40"></textarea></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" colspan="2"><?php echo tep_draw_separator('pixel_trans.gif','10','10');?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellpadding="2" cellspacing="3" border="0" width="100%">
                            <tr><td class="info_heading"><?php echo TEXT_RESTOCK; ?></td></tr>
                            <tr><td class="main"><?php echo '&nbsp;' .tep_draw_checkbox_field('is_restock','Y','','','onClick="javascript:do_active();"') . '&nbsp;' .TEXT_RESTOCK?></td></tr>
                            <tr><td class="main"><?php echo tep_draw_separator('pixel_trans.gif','1','1') ;?></td></tr>
                            <tr>
                                <td align="left">
								<table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <?php $opid='';

                                        //echo '>>>'.$order_id;
                                        //exit;
                                        $sql_query1="select orders_products_id,categories_name,products_name,products_id , products_type  from " . TABLE_ORDERS_PRODUCTS . " where orders_id='" . tep_db_input($order_id) . "' and orders_products_status!='5' group by orders_products_id,categories_name,products_name,products_id,products_type ";


                                        $query=tep_db_query($sql_query1);

                                        while($result=tep_db_fetch_array($query)){
                                            $opid.=$result['orders_products_id'] . ",";
                                            echo '          <tr>' . "\n" .
												 '			  <td width="15">'  . '</td>'.
												 '            <td class="main" align="left" valign="top" width="15">' . tep_draw_checkbox_field('chk_restock' . $result['orders_products_id'] ,'Y','','','disabled=true onClick="javascript:do_chk_ords(this);" ') . '&nbsp;</td>' . "\n" .
												 '            <td class="main"  valign="top">' . $result['products_name'] . ' - ' . $result['categories_name'] ;
                                            echo '</td>' . "\n";
												'          </tr>' . "\n";

                                        }
                                        ?>
                                </table></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <div id="loading" style="display:none"><div style="height:30px;margin-left:30px"><img src="images/24-1.gif"></div></div>
                <tr><td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif','10','10');?></td></tr>
            </table>
            <input type="hidden" id="opid"  name="opid" value="<?php echo substr($opid,0,-1); ?>">
            <input type="hidden" id="total_amt"  name="total_amt" value="<?php echo $order_total_amount; ?>">
            <?php
            echo tep_draw_hidden_field('selected_orders','');
            echo tep_draw_hidden_field('selected_events','');
            ?>
            </form>
        </td>
    </tr>
    </table>
</td>
<!-- body_text_eof //-->
</tr>
</table>
<?php }}

function doEditCustomerOrder(){
global $FREQUEST,$FSESSION,$LANGUAGES,$CAT_TREE,$jsData,$currencies;
$languages=&$LANGUAGES;
$order_id=$FREQUEST->getvalue('oID','int',0);
$jsData->VARS["updateMenu"]=",update,";
$display_mode_html=' style="display:none"';
$dis_time_format="";
if(defined('TIME_FORMAT'))
$dis_time_format=TIME_FORMAT;
include(DIR_WS_CLASSES . 'order.php');
$order = new order($order_id);
$order_date = $order->info['date_purchased'];
$split_date = preg_split("/ /",$order_date);
$order_date = format_date($split_date[0]);

$orders_statuses=array();
$orders_status_array=array();
$orders_statuses=tep_get_orders_status();
$orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$FSESSION->languages_id . "'");
while ($orders_status = tep_db_fetch_array($orders_status_query)) {
$orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
}
$today_date = getServerDate();
?>
<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="orderSubmit" id="orderSubmit">
    <input type="hidden" name="order_id" value="<?php echo tep_output_string($order_id);?>"/>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                        <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
                        <td class="pageHeading" align="right">
                        </td>
                    </tr>
            </table></td>
        </tr>
        <tr>
            <td><table width="100%" border="0" cellspacing="10" cellpadding="2">
                    <tr>
                        <td colspan="3"><?php echo tep_draw_separator();?></td>
                    </tr>
                    <tr>
                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td class="main" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
                                    <td class="main"><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><b>Order # </b></td>
                                    <td class="main"><?php echo tep_db_input($order_id); ?></td>

                                </tr>
                                <tr>
                                    <tr>
                                        <td class="main"><b>Order Date & Time</b></td>
                                        <td class="main"><?php
                                            $date_purchased=date(EVENTS_DATE_FORMAT . ' h:i:s A',strtotime($order->info['date_purchased']));
                                            if($dis_time_format!="") {
                                                if($dis_time_format=='24')
                                                $date_purchased=date(EVENTS_DATE_FORMAT . ' H:i:s',strtotime($order->info['date_purchased']));
                                            }
                                            echo $date_purchased; ?></td>
                                    </tr>
                                </tr>
                                <?php if (ACCOUNT_SECOND_PHONE=='true') { ?>
                                    <?php if($order->customer['second_telephone'] != ''){ ?>
                                <tr>
                                    <td class="main"><b><?php echo ENTRY_SECOND_TELEPHONE_NUMBER; ?></b></td>
                                    <td class="main"><?php echo $order->customer['second_telephone']; ?></td>
                                </tr>
                                <?php } ?>
                            <?php } ?>
                        <?php if($order->customer['fax'] != ''){ ?>
                                <tr>
                                    <td class="main"><b><?php echo ENTRY_MOBILE_NUMBER ; ?></b></td>
                                    <td class="main"><?php echo $order->customer['fax']; ?></td>
                                </tr>
                                <?php } ?>
                            <?php if (ACCOUNT_SECOND_EMAIL=='true') { ?>
                                <?php if($order->customer['second_email_address'] != ''){ ?>
                                <tr>
                                    <td class="main"><b><?php echo ENTRY_SECOND_EMAIL_ADDRESS; ?></b></td>
                                    <td class="main"><?php echo '<a href="mailto:' . $order->customer['second_email_address'] . '"><u>' . $order->customer['second_email_address'] . '</u></a>'; ?></td>
                                </tr>
                                <?php } ?>
                            <?php } ?>
                        </table></td>
                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td class="main" valign="top"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></td>
                                    <td class="main"><?php echo (($order->delivery['format_id'] > 0)?(tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>')):(tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'))); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><b><?php echo TEXT_IP_ADDRESS; ?></b></td>
                                    <td class="main"><?php echo $FREQUEST->servervalue('REMOTE_ADDR','string'); ?></td>
                                </tr>
                        </table></td>
                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td class="main" valign="top"><b><?php echo ENTRY_BILLING_ADDRESS; ?></b></td>
                                    <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
                                    <td class="main"><?php echo $order->customer['telephone']; ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                                    <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
                                </tr>
                                <tr>
                                </tr>
                        </table></td>
                    </tr>
            </table></td>
        </tr>
        <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
            <td><table border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
                        <td class="main"><?php echo $order->info['payment_method']; ?></td>
                    </tr>
                    <?php
                    if (tep_not_null($order->info['cc_type']) || tep_not_null($order->info['cc_owner']) || tep_not_null($order->info['cc_number'])) {
                        ?>
                    <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                        <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
                        <td class="main"><?php echo $order->info['cc_type']; ?></td>
                    </tr>
                    <tr>
                        <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
                        <td class="main"><?php echo $order->info['cc_owner']; ?></td>
                    </tr>
                    <tr>
                        <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
                        <td class="main"><?php echo $order->info['cc_number']; ?></td>
                    </tr>
                    <tr>
                        <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
                        <td class="main"><?php echo $order->info['cc_expires']; ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table></td>
        </tr>
        <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
            <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="2" style="border:solid 1px #CCCCCC; background:#E6E6E6">
                    <?php
                    $flagp=0;$flage=0;$flagev=0;$flags=0;$flagt=0;
                    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
                        $products_model=$order->products[$i]['model'];
                        if(($order->products[$i]['products_type']=='P') && ($flagp==0)) {
                            ?>
                    <tr>
                    <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_SKU; ?></td>
                    <?php
                    $flagp=1;
                }
              
                if($flagt==0){?>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
                    <?php $flagt=1;
                }
                // Modified By RMA 16/07/2011 START
                echo '            </tr><tr class="dataTableRow">' . "\n" .
               '            <td class="dataTableContent" valign="top" align="right"><select name="prodqty['.$order->products[$i]['orders_products_id'].']">';
                 for( $qtyNm = 0; $qtyNm <= max( 1, $order->products[$i]['qty'] ); $qtyNm++ )
                 {
                    echo '<option value="'.$qtyNm.'"'
                          .($qtyNm==$order->products[$i]['qty']?' selected="selected"':'')
                          .'>'
                          .$qtyNm.'</option>'; 
                 }                 
                echo '</select>&nbsp;x</td>' . "\n";
                echo '            <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];
                // Modified By RMA 16/07/2011 END
                if (isset($order->products[$i]['others']) && (sizeof($order->products[$i]['others']) > 0)) {
                    $disp_date=$order->products[$i]['others']['end_date'];
                    $disp_time=$order->products[$i]['others']['end_time'];
                    if($disp_date && $disp_time){
                        $date_time=strtotime($disp_date.' '.$disp_time)+60;
                        $disp_date=date('Y-m-d',$date_time);
                        $disp_time=date('h:i A',$date_time);
                    }
                    $stime="";
                    $etime="";
                    if($order->products[$i]['others']['start_time']) {
                        $stime=date("h:i A",strtotime($order->products[$i]['others']['start_time']));
                        if($dis_time_format!="") {
                            if($dis_time_format=='24')
                            $stime=date("H:i",strtotime($order->products[$i]['others']['start_time']));
                        }
                    }
                    if($order->products[$i]['others']['end_time']) {
                        if($dis_time_format!="") {
                            if($dis_time_format=='24') $disp_time=date('H:i',$date_time);
                        }
                    }
                    $disp_date_time=format_date($disp_date).'&nbsp;&nbsp;'.$disp_time;
                    echo '<br><nobr><small>&nbsp;<i> -  Start Date: ' . format_date($order->products[$i]['others']['start_date']) . '&nbsp; ' .$stime  .  '<br><nobr>&nbsp; -' . ' End Date: ' . $disp_date_time . '&nbsp;';
                    echo '</i></small></nobr>';
                }


                $discount=(isset($order->products[$i]['discount_whole_text']) && $order->products[$i]['discount_whole_text']!='')?'<br>' . $order->products[$i]['discount_whole_text']:'';
                echo $discount;
                if($order->products[$i]['products_type']=='V')
                echo'</td><td class="dataTableContent" valign="top">'.(($order->products[$i]['others']['resource_name'])?' ' . $order->products[$i]['others']['resource_name']:'').'</td>';
                else{
                    echo  '</td><td class="dataTableContent" valign="top">' . (($order->products[$i]['products_type']=='E')?format_date($products_model):$products_model) . '</td>' ;
                }
                $table_content = '<td class="dataTableContent" valign="top">' . (($order->products[$i]['products_type']=='P')?$order->products[$i]['products_sku']:'') . '</td>'. "\n" .
                           '<td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
                           '<td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
                           '<td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
                $table_content .= '<td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'] , true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
                $table_content .= '<td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
                $table_content .= '</tr>' . "\n";
                echo $table_content;
 
            }
            ?>
                    <tr bgcolor="#EDF1FE">
                        <td align="right" colspan="10">
						<table border="0" cellspacing="0" cellpadding="2">
                                <?php
                                for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
                                    echo '              <tr>' . "\n" .
                                                       '                <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
                                                       '                <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
                                                       '              </tr>' . "\n";
                                }
                                ?>
                        </table></td>
                    </tr>
            </table></td>
        </tr>
        <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr><td class="main">
		
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr bgcolor=white>
                    <td class="main"><?php echo '<b>' . TEXT_CURRENT_STATUS . '</b>' ;?></td></tr>
                    <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '3'); ?></td></tr>
                    <tr>
                        <td class="main"><table border="0" cellspacing="0" cellpadding="3" class="infoBox">
                                <tr class="infoBoxContent">
                                    <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
                                    <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
                                    <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
                                    <td width="518" class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
                                    <td class="smallText" align="center"><b><?php echo TABLE_HEADING_USER_ADDED; ?></b></td>
                                </tr>
                                <?php
                                $orders_history_query = tep_db_query("select orders_status_id, date_added, customer_notified, comments,user_added from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int)$order_id . "' order by date_added");
                                if (tep_db_num_rows($orders_history_query)) {
                                    while ($orders_history = tep_db_fetch_array($orders_history_query)) {
                                        $date_added_text=date(EVENTS_DATE_FORMAT . ' h:i:s A',strtotime($orders_history['date_added']));
                                        if($dis_time_format!="") {
                                            if($dis_time_format=='24')
                                            $date_added_text=date(EVENTS_DATE_FORMAT . ' H:i',strtotime($orders_history['date_added']));
                                        }
                                        echo '          <tr>' . "\n" .
                                                         '            <td class="smallText" align="center">' . $date_added_text . '</td>' . "\n" .
                                                         '            <td class="smallText" align="center">';
                                        if ($orders_history['customer_notified'] == '1') {
                                            echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
                                        } else {
                                            echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
                                        }
                                        $comments='';
                                        if($orders_status_array[$orders_history['orders_status_id']] == TEXT_DELIVERED) {
                                            $comments = 'Shipping On :' . format_date($order->info['shipping_date']);
                                        }
                                        echo '            <td class="smallText">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n" .
                                                         '            <td class="smallText" align="center">' . $orders_history['comments'] . '&nbsp;' . $comments . '</td>' . "\n" .
                                                         '            <td class="smallText">' . $orders_history['user_added'] . '&nbsp;</td>' . "\n" .
                                                         '          </tr>' . "\n";
                                    }
                                } else {
                                    echo '          <tr>' . "\n" .
                                                         '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
                                                         '          </tr>' . "\n";
                                }
                                ?>
                        </table></td>
                    </tr>
                    <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '3'); ?></td>
                    </tr>
                    <tr bgcolor="white">
                        <td class="main"><b><?php echo TEXT_MODIFY_STATUS; ?></b></td>
                    </tr>
                    <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '3'); ?></td>
                    </tr>
                    <tr>
                        <?php
                        $current_date=getServerDate();
                        $ship_date="";
                        if(tep_not_null($order->info['shipping_date'])) {
                            $ship_date=format_date($order->info['shipping_date']);
                        }
                        ?>
                        <td>
						<table border="0" cellspacing="0" cellpadding="2">
                            <?php
                            $orders_status_load = array();
			    //REFUND
                            $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$FSESSION->languages_id . "' and lower(orders_status_name)!='refunded' order by orders_status_id");
                            while ($orders_status = tep_db_fetch_array($orders_status_query)) {
							//cartzone fix
                                /*if($orders_status['orders_status_id'] >= $order->info['orders_status'])*/{
                                    $orders_status_load[] = array('id' => $orders_status['orders_status_id'],
                                                'text' => $orders_status['orders_status_name']);
							
							//while ($orders_status = tep_db_fetch_array($orders_status_query)) {
//                                if($orders_status['orders_status_id'] >= $order->info['orders_status']){
//                                    $orders_status_load[] = array('id' => $orders_status['orders_status_id'],
//                                                'text' => $orders_status['orders_status_name']);
                                }
                            }
                            $_array=array('d','m','Y');  $replace_array=array('DD','MM','YYYY'); 	$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>
                            <tr><td class="main"><b><?php echo ENTRY_STATUS; ?></b><td width="200"><?php

                                            if($order->info['orders_status']==5 || $order->info['orders_status']==4)
                                            {
                                                echo tep_draw_hidden_field('status_undisplay',$order->info['orders_status']);
                                            }
                                            if($order->info['orders_status']=='5')
                                            echo '<font class="main">' . REFUNDED . '</font>';
                                            else if($order->info['orders_status']=='4')
                                            echo '<font class="main">' . BACKORDER . '</font>';
                                            else
                                            echo tep_draw_pull_down_menu('status', $orders_status_load, $order->info['orders_status'],'onchange="javascript:void change_status(\'1\');"'); ?></td>
                                <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b><td width="200">
								
								<?php //cartzone altered 'notify customer' checkbox to OFF here (false)
								echo tep_draw_checkbox_field('notify', '', false); ?></td>
                                <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b><td><?php echo tep_draw_checkbox_field('notify_comments', '', true); ?></td>

                            </tr>
                            <tr id='shipping' style="display:none;">
                                <td class="main"><b><?php echo ENTRY_SHIPPING_DATE; ?></b><br><small>(<?php echo $date_format; ?>)</small></td>
                                <td><?php  echo tep_draw_input_field("shipping_date",(tep_not_null($ship_date)?$ship_date:format_date($current_date)),"size=10",false,'text',false);
                                    echo tep_create_calendar("orderSubmit.shipping_date",$date_format);?>
                            </td></tr>
                        </table>
                    </tr>
                    <tr>
                        <td class="main" align="right" colspan="2"><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5'); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '3'); ?></td>
                    </tr>
                    <tr><td>
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="320">&nbsp;</td>
                                    <td align="right">
                                        <?php
                                        //if($order->info['orders_status']!='5')
                                        //echo '<a href="javascript: formValidate();">'.tep_image_button('button_update.gif' ,'Update','','').'</a>&nbsp;<span id="update_ajax_load"></span>';
                                        ?>
                    </td></tr></table></td></tr>
            </table></td>
            <script>change_status('2'); </script>
        </tr>
        <tr>
            <td colspan="2" align="left">
                <?php
                echo '<a href="'.tep_href_link(FILENAME_ORDERS_EDIT,'oID='.$order_id.'&cID='.$order->customer['id']).'">'.tep_image_button('button_edit.gif','Edit').'</a>';

                ?>
            </td>
        </tr>
    </table>
</form>
	<?php			
	}
	function doDelete(){
	global $FREQUEST,$jsData;
	$order_id=$FREQUEST->postvalue('order_id','int',0);
	 $jsData->VARS['doFunc']=array('type'=>'custord','data'=>'removeSearchValue');
	tep_remove_order_events($order_id, $FREQUEST->postvalue('restock'));
	$this->doGetCustomersOrders();
	$jsData->VARS["displayMessage"]=array('text'=>TEXT_ORDERS_DELETE_SUCCESS);
	}
	function doDeleteCustomerOrder(){
	global $FREQUEST,$jsData,$FSESSION;
	$order_id=$FREQUEST->getvalue('oID','int',0);

	$delete_message='<p><span class="smallText">' . TEXT_DELETE_INTRO . '</span>';
	?>
	<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="products_manufacturers.php" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="order_id" value="<?php echo tep_output_string($order_id);?>"/>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
        <tr>
            <td class="main" id="<?php echo $this->type . $order_id;?>message">
            </td>
        </tr>
        <tr>
            <td class="main">
                <?php echo $delete_message;?>
            </td>
        </tr>
        <tr>
            <td class="main" ><?php echo tep_draw_checkbox_field('restock').' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY; ?></td>
        </tr>
        <tr height="40">
            <td class="main" style="vertical-align:bottom">
                <p>
                    <a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $order_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['ORD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
                    <a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $order_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
                </p>
            </td>
        </tr>
        <tr>
            <td><hr/></td>
        </tr>
        <tr>
            <td valign="top" class="categoryInfo"><?php echo $this->doInfo($item_id);?></td>
        </tr>
    </table>
</form>
<?php
$jsData->VARS["updateMenu"]="";
}

function doGetCustomersOrders()
{

global $FREQUEST,$jsData;
       
$template=getListTemplate();
$filter=$FREQUEST->getvalue('filter');
if($filter!=0)
$filter_param=" and o.orders_status='".$filter."'";

$search=$FREQUEST->getvalue('search');
if ($search!=''){
	$filter_search=$FREQUEST->getvalue('filter_search');
	  //Graeme - we could use a switch statement here but maybe just go with if, else else
	    if($filter_search==1){
			$search_param = "and o.orders_id = '" . tep_db_input($search) . "'";
		}
		elseif($filter_search==2){
			$search_param = "and customers_name like '%" . tep_db_input($search) . "%'";
		}
		elseif($filter_search==3){
			$search_param = "and categories_name like '%" . tep_db_input($search) . "%'";
		}
		elseif($filter_search==4){
			$search_param = "and op.products_name like '%" . tep_db_input($search) . "%'";
		}
		elseif($filter_search==5){
			$search_param = "and o.reference_id like '%" . tep_db_input($search) . "%'";
		}
		elseif ($filter_search == 6) {
			$search_param = "and o.billing_name like '%" . tep_db_input($search) . "%'";
		}
		elseif($filter_search==7){
			$search_param = "and op.products_id like '%" . tep_db_input($search) . "%'";
		}
		elseif($filter_search==8){
			$search_param = "and op.products_model like '%" . tep_db_input($search) . "%'";
		}
		else{
			$search_param='';
			
		}	
}

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
    if($_GET['value'] == 'customers_name')
    {
        $bgcolor = "#CCCCCC";
        $order == 1 ? $img = '<img src="images/template/ico_arrow_up.gif" title="Ascending">':$img = '<img src="images/template/ico_arrow_down.gif" title="Descending">';
    }
    if($_GET['value'] == 'o.orders_id')
    {
        $bgcolor1 = "#CCCCCC";
        $order == 1 ? $img1 = '<img src="images/template/ico_arrow_up.gif" title="Ascending">':$img1 = '<img src="images/template/ico_arrow_down.gif" title="Descending">';
    }
    if($_GET['value'] == 'o.date_purchased')
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

/*	$rep_array=array(	"TYPE"=>$this->type,
                    "ID"=>-1,
                    "NAME"=>'<b>' . TABLE_HEADING_CUSTOMERS .'</b>',
                    "IMAGE_PATH"=>DIR_WS_IMAGES,
                    "ORDER_ID"=>'<b>' .TABLE_HEADING_ORDERS_ID.'</b>',
                    "STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
                    "TOTAL"=>'<b>' .TABLE_HEADING_ORDER_TOTAL.'</b>',
                    "DATE"=>'<b>' .TABLE_HEADING_DATE_PURCHASED.'</b>',
                    "STATUS_NAME"=>'<b>' .TABLE_HEADING_STATUS.'</b>',
                    "UPDATE_RESULT"=>'doTotalResult',
                    "UPDATE_DATA"=>TEXT_UPDATE_DATA,
                    "ROW_CLICK_GET"=>'EditOrder',
                    "FIRST_MENU_DISPLAY"=>"display:none"
                );
*/
?>
<table cellpadding="0" cellspacing="0" width="100%" border="0"  id="<?php echo $this->type;?>Table">
    <tr><td>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr class="dataTableHeadingRow">
                    <td valign="top">
					
					
					<table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#999999">
                            <tr>
                                <td width="2%" nowrap="nowrap">&nbsp; </td>
                                <td width="2%" nowrap="nowrap">&nbsp; </td>
                                <td width="10%" bgcolor="<?php echo $bgcolor; ?>" style="padding-left:10px;cursor:pointer" class="main" align="left" nowrap="nowrap" onClick="javascript:return doOrderBy({id:-1,type:'<?php echo $this->type;?>',get:'GetCustomersOrders',result:doTotalResult,params:'<?php echo $order_by; ?>','value':'customers_name','searchparam':'<?php echo $search; ?>','filter':'<?php echo $filter; ?>','message':'<?php echo sprintf(INFO_LOADING_DATA) ?>'});"><?php echo '<b>' . TABLE_HEADING_CUSTOMERS .'</b>'; ?>&nbsp;&nbsp;&nbsp;&nbsp;<span ><?php echo $img; ?></span></td>
                                <td width="10%" bgcolor="<?php echo $bgcolor; ?>" style="padding-left:10px;cursor:pointer" class="main" align="left" nowrap="nowrap" onClick="javascript:return doOrderBy({id:-1,type:'<?php echo $this->type;?>',get:'GetCustomersOrders',result:doTotalResult,params:'<?php echo $order_by; ?>','value':'billing_name','searchparam':'<?php echo $search; ?>','filter':'<?php echo $filter; ?>','message':'<?php echo sprintf(INFO_LOADING_DATA) ?>'});"><?php echo '<b>' . TABLE_HEADING_PURCHASER .'</b>'; ?>&nbsp;&nbsp;&nbsp;&nbsp;<span ><?php echo $img; ?></span></td>
                                <td width="8%" bgcolor="<?php echo $bgcolor1; ?>" class="main" align="left" style="padding-left:10px;cursor:pointer" nowrap="nowrap" onClick="javascript:return doOrderBy({id:-1,type:'<?php echo $this->type;?>',get:'GetCustomersOrders',result:doTotalResult,params:'<?php echo $order_by; ?>','value':'o.orders_id','searchparam':'<?php echo $search; ?>','filter':'<?php echo $filter; ?>','message':'<?php echo sprintf(INFO_LOADING_DATA) ?>'});"><?php echo '<b>' .TABLE_HEADING_ORDERS_ID.'</b>'; ?>&nbsp;&nbsp;<span ><?php echo $img1; ?></span></td>
                                <td width="10%"  class="main" align="left" nowrap="nowrap" style="padding-left:10px" ><?php echo '<b>' .TABLE_HEADING_ORDER_TOTAL.'</b>'; ?></td>
                                <!--<td width="18%"  class="main" align="left" nowrap="nowrap" style="padding-left:10px" ><?php //echo '<b>' .TABLE_HEADING_SHOW_NAME.'</b>'; ?></td>-->
                                <td width="10%" bgcolor="<?php echo $bgcolor2; ?>" class="main" align="left" style="padding-left:10px;cursor:pointer" nowrap="nowrap" onClick="javascript:return doOrderBy({id:-1,type:'<?php echo $this->type;?>',get:'GetCustomersOrders',result:doTotalResult,params:'<?php echo $order_by; ?>','value':'o.date_purchased','searchparam':'<?php echo $search; ?>','filter':'<?php echo $filter; ?>','message':'<?php echo sprintf(INFO_LOADING_DATA) ?>'});"><?php echo '<b>' .TABLE_HEADING_DATE_PURCHASED.'</b>'; ?>&nbsp;&nbsp;&nbsp;&nbsp;<span ><?php echo $img2; ?></span></td>
					
								<td  width="10%" class="main"  align="left" nowrap="nowrap" style="padding-left:10px"><?php echo '<b>' .TABLE_HEADING_PM.'</b>'; ?>&nbsp;&nbsp;&nbsp;</td>
								
                                <td width="10%" class="main"  align="left" nowrap="nowrap" style="padding-left:10px"><?php echo '<b>' .TABLE_HEADING_STATUS.'</b>'; ?>&nbsp;&nbsp;&nbsp;</td>
                                <td  width="150" nowrap="nowrap">&nbsp;</td>
                            </tr>
                    </table></td>
                </tr>
                <tr>
                    <td>
                        <?php $this->doCustomersOrdersList($search_param,$filter_param,$order);?>
                    </td>
                </tr>
            </table>
    </td></tr>
</table>
<!--	<table border="0" width="100%" id="<?php echo $this->type;?>Table">
        <?php //echo mergeTemplate($rep_array,$template); ?>
        <?php //$this->doCustomersOrdersList($search_param);?>
        </table>	-->
        <?php if (is_object($this->splitResult)){?>
	<table border="0" width="100%" height="100%">
		<?php echo $this->splitResult->pgLinksCombo(); ?>
	</table>
	<?php }
	}
	function doInfo($order_id=0)
	{
	global $FSESSION,$FREQUEST,$jsData;
	if ($order_id<=0) $order_id=$FREQUEST->getvalue("oID","int",0);

	$orders_query_raw = tep_db_query("select o.orders_id, o.payment_return1, o.payment_return2, o.reference_id, o.orders_status,op.products_quantity,concat(LTRIM(c.customers_lastname),' ',LTRIM(c.customers_firstname)) as customers_name, o.payment_method, o.date_purchased, o.billing_name, o.last_modified, o.currency, o.currency_value, s.orders_status_name, op.products_type, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s, " .TABLE_ORDERS_PRODUCTS." op, ".TABLE_CUSTOMERS ." c where o.customers_id=c.customers_id and o.orders_status = s.orders_status_id and o.orders_id=op.orders_id and s.language_id = '" . (int)$FSESSION->languages_id . "' and ot.class = 'ot_total' and o.orders_id='$order_id' order by o.orders_id DESC,customers_name ASC");
	if (tep_db_num_rows($orders_query_raw)>0)
	{
	$order_result=tep_db_fetch_array($orders_query_raw);
	$template=getInfoTemplate($order_id);

	$rep_array=array(	"ORD_DATE_ADDED"=>((format_date($order_result["date_purchased"])=='')?(""):(TEXT_DATE_ORDER_CREATED .  format_date($order_result["date_purchased"]))),
			"ORD_DATE_MODIFIED"=>((format_date($order_result["last_modified"])=='')?(""):(TEXT_DATE_ORDER_LAST_MODIFIED .  format_date($order_result["last_modified"]))),
			"ORD_TOTAL"=>TEXT_ORDERS_TOTAL . $order_result["order_total"],
			"ORD_STATUS"=>TEXT_ORDERS_STATUS.'<b>'.$order_result["orders_status_name"].'</b>',
			"ORD_REFID"=>TEXT_INFO_REFERENCE_ID . $order_result["reference_id"],
			"ORD_METHOD"=>TEXT_INFO_PAYMENT_METHOD.$order_result["payment_method"],
			"UPDATE_RESULT"=>'doDisplayResult'
	);
	echo mergeTemplate($rep_array,$template);
	$jsData->VARS["updateMenu"]=",normal,";
	} else {
	echo 'Err:' . TEXT_ORDER_NOT_FOUND;
	}
	$jsData->VARS["storePage"]=array('lastAction'=>false,'locked'=>false);
	}
	}
	function getListTemplate(){
	ob_start();
	getTemplateRowTop(); ?>
<style>.Y{color:red}</style>
<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
    <tr>
        <td>
        <!--Added column Purchaser in case of edit of customer name-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="2%" id="##TYPE####ID##bullet">##STATUS##</td>
                    <td width="2%" id="##TYPE_####ID"><input type="checkbox" name="ord_status[]" class="ordchkcls" value="##ID##"></td>
                    <td width="10%" class="main 2" onclick="javascript:doDisplayAction({'id':##ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'oID=##ID##'});" id="##TYPE####ID##name" style="padding-left:10px">##ORDER_NAME##</td>
                    <!--Customer Name-->
                    <!--<td width="10%" class="smallText" onclick="javascript:doDisplayAction({'id':##ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'oID=##ID##'});" id="##TYPE####ID##name" style="padding-left:10px">##NAME##</td>-->
                    <!--Billing Name-->
                    <td width="10%" class="smallText" onclick="javascript:doDisplayAction({'id':##ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'oID=##ID##'});" id="##TYPE####ID##name" style="padding-left:10px">##BILLING_NAME##</td>
                    <td width="8%" align="left" style="padding-left:10px" class="main" onclick="javascript:doDisplayAction({'id':##ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'oID=##ID##'});" id="##TYPE####ID##orderid">##ORDER_ID##</td>
                    <td width="10%"  align="left" style="padding-left:10px" class="main" onclick="javascript:doDisplayAction({'id':##ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'oID=##ID##'});" id="##TYPE####ID##total">##TOTAL##</td>
                    <!--<td width="18%"  align="left" style="padding-left:10px" class="main" onclick="javascript:doDisplayAction({'id':##ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'oID=##ID##'});" id="##TYPE####ID##total">##SHOW##</td>-->
                    <td width="10%" align="left" style="padding-left:13px" class="main" onclick="javascript:doDisplayAction({'id':##ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'oID=##ID##'});" id="##TYPE####ID##date">##DATE##</td>
					
					<!--payment-->
					<td  width="10%" align="left" style="padding-left:13px" class="main" onclick="javascript:doDisplayAction({'id':##ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'oID=##ID##'});" id="##TYPE####ID##date">##PM##</td>
                    <td width="10%" align="left" class="main" style="padding-left:20px" onclick="javascript:doDisplayAction({'id':##ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'oID=##ID##'});" id="##TYPE####ID##status"><span class="">##STATUS_NAME##</span></td>

                    <td  width="150" id="##TYPE####ID##menu" align="right" style="padding-left:20px" class="boxRowMenu" nowrap="nowrap">
                        &nbsp;<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
                            <?php if($FSESSION->login_groups_type!='Demo User Group' || $FSESSION->login_groups_type!='Call Centre Staff'){?>
                            <a style="display:none;" href="<?php echo tep_href_link(FILENAME_ORDERS_REFUND, 'oID=##ID##&preturn=co&page=##PAGE##')?>">
                                <img src="##IMAGE_PATH##/template/img_move.gif" title="<?php echo TEXT_REFUND; ?>"/>
                            </a>
                            <a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'EditRefundCustomerOrder','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'oID=##ID##'});">
                                <img src="##IMAGE_PATH##/template/img_move.gif" title="<?php echo TEXT_REFUND; ?>"/>
                            </a>
                            <img src="##IMAGE_PATH##/template/img_bar.gif"/>
                            <?php }?>
							
							
     <a href="edit_orders.php?oID=##ID##"><img src="##IMAGE_PATH##/template/edit_blue.gif" title="<?php echo TEXT_EDIT; ?>"/></a>
							
							
                            <img src="##IMAGE_PATH##/template/img_bar.gif"/>
                            <a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'DeleteCustomerOrder','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'oID=##ID##'});"><img src="##IMAGE_PATH##/template/delete_blue.gif" title="<?php echo TEXT_DELETE; ?>"/></a>
                            <img src="##IMAGE_PATH##/template/img_bar.gif"/>
                            <a href="<?php echo tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=##ID##')?>" TARGET="_blank"><img src="##IMAGE_PATH##/template/img_copy.gif" title="<?php echo TEXT_INVOICE; ?>"/></a>
                            <!--<img src="##IMAGE_PATH##/template/img_bar.gif"/>
                            <a href="<?php //echo tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=##ID##')?>" TARGET="_blank"><img src="##IMAGE_PATH##/template/img_move.gif" title="Packaging Slip"/></a>-->
                            <img src="##IMAGE_PATH##/template/img_bar.gif"/>
                            <a href="<?php echo tep_href_link(FILENAME_EVENTS_TICKET, 'oID=##ID##')?>" TARGET="_blank"><img src="##IMAGE_PATH##/template/img_sms.gif" title="Ticket"/></a>
                            <img src="##IMAGE_PATH##/template/img_bar.gif"/>
                        </span>
                        <span id="##TYPE####ID##mrefund" style="display:none">
                            <!--							<a href="javascript:void(0)" onclick="javascript:return false;"><img src="##IMAGE_PATH##/template/img_save_green.gif" title="<?php echo TEXT_REFUND; ?>"/></a>-->
                            <a href="javascript:void(0)" onclick="javascript:return doUpdateAction({'id':##ID##,'get':'RefundProcess','type':'##TYPE##','imgUpdate':false,'style':'boxRow','validate':refundValidate,'customUpdate':doRefundOrder,'uptForm':'orderSubmit','result':##UPDATE_RESULT##,'message1':'##UPDATE_DATA##'});"><img src="##IMAGE_PATH##/template/img_save_green.gif" title="<?php echo TEXT_REFUND; ?>"/></a>
                            <!--							<a href="javascript:void(0)" onclick="javascript:return doUpdateAction({'id':##ID##,'get':'UpdateUpdateCustomerOrder','type':'##TYPE##','imgUpdate':false,'style':'boxRow','validate':orderValidate,'uptForm':'orderSubmit','customUpdate':doUpdateCustomerOrder,'result':##UPDATE_RESULT##,'message1':'##UPDATE_DATA##'});"><img src="##IMAGE_PATH##/template/img_save_green.gif" title="<?php echo TEXT_REFUND; ?>"/></a>-->
                            <img src="##IMAGE_PATH##/template/img_bar.gif"/>
                            <a href="javascript:void(0)" onclick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##/template/img_close_blue.gif"/></a>
                        </span>
                        <span id="##TYPE####ID##mupdate" style="display:none">
                            <a href="javascript:void(0)" onclick="javascript:return doUpdateAction({'id':##ID##,'get':'UpdateCustomerOrder','type':'##TYPE##','imgUpdate':false,'style':'boxRow','validate':orderValidate,'uptForm':'orderSubmit','customUpdate':doUpdateCustomerOrder,'result':##UPDATE_RESULT##,'message1':'##UPDATE_DATA##'});"><img src="##IMAGE_PATH##/template/img_save_green.gif" title="Update"/></a>
                            <img src="##IMAGE_PATH##/template/img_bar.gif"/>
                            <a href="javascript:void(0)" onclick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##/template/img_close_blue.gif"/></a>
                        </span>&nbsp;
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
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td width="50">&nbsp;</td>
        <td width="50">&nbsp;</td>
        <td>
            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                    <td class="main">##ORD_TOTAL##</td>
                    <td class="main">##ORD_DATE_ADDED##</td>
                    <td class="main">##ORD_REFID##</td>
                </tr>
                <tr>
                    <td class="main">##ORD_STATUS##</td>
                    <td class="main">##ORD_DATE_MODIFIED##</td>
                    <td class="main">##ORD_METHOD##</td>
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
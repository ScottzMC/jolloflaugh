<?php

/*

  

  Released under the GNU General Public License
  Freeway eCommerce from ZacWare
  http://www.openfreeway.org

Copyright 2007 ZacWare Pty. Ltd 
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'split_page_results_report.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  require(DIR_WS_CLASSES . 'payment.php');
  require(DIR_WS_CLASSES . 'pdfTable.php');

  require(DIR_WS_LANGUAGES . $FSESSION->language . "/reports_refund_products.php");
  $currencies = new currencies();
  define(BOX_WIDTH1,'125');
	
  $btype=$FREQUEST->getvalue('btype','string','P');
 
    $grand_unit_price=0;
	$grand_total=0;
	$grand_total_tax=0;
	$grand_total_refund=0;
	$grand_payment_array=array();
 

  $date_offset=(defined('EVENTS_SERVER_DATE_OFFSET')?EVENTS_SERVER_DATE_OFFSET:0);
	// get initial parameters
	if (($FREQUEST->getvalue("return")!='') && ($FSESSION->get("rep_params")!='')){
		$input_params=&$FSESSION->get("rep_params");
	} else {
	 $input_params=&$FGET; 
	 if (isset($input_params["post_action"])){
		$FSESSION->set("rep_params",$FPOST);
	 } else {
		$FSESSION->set("rep_params",array());
	 } 
	}
	if(isset($input_params["type"])) {
		$type=$input_params["type"];
	}else {
		if(HIDE_FROM_BACKEND_MENU_PRODUCTS=='false')
			$type='P';
	
	}
	//ajax start
//	$input_params=&$HTTP_GET_VARS;
	$command=$FREQUEST->getvalue('command');
	$start_date = (isset($input_params['start_date'])?tep_convert_date_raw($input_params['start_date']):'');
	$end_date = (isset($input_params['end_date'])?tep_convert_date_raw($input_params['end_date']):getServerDate());
	$product_id = (isset($input_params['sel_product'])?$input_params['sel_product']:'-1');
	$event_id=(isset($input_params['sel_event'])?$input_params['sel_event']:'-1');
	$subscription_id=(isset($input_params['sel_subscription'])?$input_params['sel_subscription']:'-1');
	$service_id=(isset($input_params['sel_service'])?$input_params['sel_service']:'-1');
	$sort_manufact=(isset($input_params['sort_manufact'])?$input_params['sort_manufact']:'');
	$tax=(isset($input_params['tax'])?$input_params['tax']:'0');
	$sales_by_order=(isset($input_params['sales_by_order'])?$input_params['sales_by_order']:'0');
	$summary = (isset($input_params['summary'])?true:false);
	$post_action=(isset($input_params["post_action"])?$input_params["post_action"]:'screen');
	$page=(isset($input_params["page"])?$input_params["page"]:1);
	$type_prd = $type;	
	if($type=='H')
	{
		$sales_by_order=1;
	}
	if($sales_by_order==1){
		$subscription_id = -1;
		$event_id = -1;
		$service_id = -1;
		$product_id = -1;
	}

   if($start_date=="") {
   ///??????????????????????????????????????????????????/
		$res = tep_db_query("SELECT '".$end_date."' - INTERVAL 1 MONTH");
	   $row = tep_db_fetch_array($res);
	   $start_date = $row[0];
	 ////????????????????????????????????????????????????/
   }
   $display_header="";
	$display_header.=TEXT_START_DATE .  ":&nbsp;&nbsp;" . format_date($start_date) . "&nbsp;&nbsp;&nbsp;&nbsp;";
	$display_header.=TEXT_END_DATE . ":&nbsp;&nbsp;" . format_date($end_date) . "\t";
	
	if($sales_by_order==0){
		if($type=='P' && $product_id=="-1") $display_header.=TEXT_PRODUCT . ":&nbsp;&nbsp;" . TEXT_ALL_PRODUCTS  ." ";
	}
	
	if ($start_date!='') $where .= " and date_format(o.date_purchased,'%Y-%m-%d')>='". tep_db_input($start_date) ."'";
	if ($end_date!='')	$where .=" and date_format(o.date_purchased,'%Y-%m-%d')<='" . tep_db_input($end_date) . "'";
	
	if (($type=='P' || $type=='H') && $product_id>0) {
		$prd_where .= " and op.products_id='" . tep_db_input($product_id) . "' "; 
		if($type=='P')
			$display_header.=TEXT_PRODUCT . ":&nbsp;&nbsp;" . tep_get_products_name($product_id, $FSESSION->languages_id) . " ";
		//else
			//$display_header.=TEXT_SHIPPING . ":&nbsp;&nbsp;" . tep_get_products_name($product_id, $languages_id) . " ";
	}


	if($sales_by_order==1)$display_header.=TEXT_SALES_BY_ORDER.':&nbsp;' . TEXT_YES.':&nbsp;&nbsp;';
	if($tax==1)$display_header.=TEXT_TAX . ':&nbsp;' . TEXT_YES.':&nbsp;&nbsp;';
	if($type=='P' && $product_id<=0)if($sort_manufact==1)$display_header.=TEXT_SORT_MANUFACTURER.':&nbsp;' . TEXT_YES.':&nbsp;&nbsp;';
	if ($summary) $display_header.=TEXT_SUMMARY . ':&nbsp;' . TEXT_YES;
	if($type!='H')
		$type_where .=" and op.products_type='" .tep_db_input($type) ."'";
	else
		$type_where .=" and op.products_type='P'";
	//$attributes=array();
	// get the attributes of products and services and prepare the detail list
	//if ($type=="P" || $type=='V'){
		//$attributes_sql="select op.products_id,opa.products_options_id,opa.products_options_values_id,opa.products_options_values,op.orders_products_id,op.orders_id,r.orders_id,r.refund_type,r.amount_type,r.refund_amount,r.date_created from " . TABLE_ORDERS . " o," . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " opa, " . TABLE_REFUNDS . " r  where o.orders_id=op.orders_id and op.orders_products_id=opa.orders_products_id and o.orders_id=r.orders_id " . $where . $prd_where . $type_where . " and o.orders_status=5 order by op.orders_id,op.orders_products_id,op.products_id,opa.products_options_id";
		//echo $attributes_sql; 
		//$attributes_query=tep_db_query($attributes_sql);
		$prev_products_id="";
		$prev_orders_id=-1;
		
		// $attribute_list='';
		// while($attribute_result=tep_db_fetch_array($attributes_query))
		// {
			// if ($prev_orders_id!=$attribute_result["orders_products_id"]){
				// if ($attribute_list!=""){
					// $attributes[$prev_orders_products_id]=array("id"=>substr($attribute_list,0,-1),"name"=>substr($attribute_name,0,-1));
					// $attribute_list="";
					// $attribute_name="";
					// $prev_products_id="";
				// }
				// $prev_orders_id=$attribute_result["orders_products_id"];
			// }
			// if ($prev_products_id!=$attribute_result["products_id"]){
				// if ($attribute_list!=""){
					// $attributes[$prev_orders_products_id]=array("id"=>substr($attribute_list,0,-1),"name"=>substr($attribute_name,0,-1));
					// $attribute_list="";
					// $attribute_name="";
				// }
				// $prev_products_id=$attribute_result["products_id"];
			// }
			// $attribute_list.=sprintf("%s(%s)-",$attribute_result["products_options_id"],$attribute_result["products_options_values_id"]);
			// $attribute_name.=$attribute_result["products_options_values"] . ",";
			// $prev_orders_products_id=$attribute_result["orders_products_id"];
		// }
	
	// if ($attribute_list!=""){
		// $attributes[$prev_orders_products_id]=array("id"=>substr($attribute_list,0,-1),"name"=>substr($attribute_name,0,-1));
		// $attribute_list="";
		// $attribute_name="";
		// $prev_products_id="";
	// }
	// tep_db_free_result($attributes_query);
	// }
	$newdetails=array();
	$details=array();
	$found_results=false;
	$payment=new payment();
	$selection=$payment->selection();
	$cashpos=0;$moneyorderpos=0;
	$payment_list=array();

	$payment_col_count=0;

	
	while($unique_result=tep_db_fetch_array($unique_payment_query)){
		$ordered_payments.=strtolower($unique_result["payment_method"]) . ",";
	}

	for ($icnt=0;$icnt<count($selection);$icnt++){
		if ($selection[$icnt]['id']=="moneyorder") $moneyorderpos=$icnt;
		$payment_on=((strpos($ordered_payments,",". strtolower($selection[$icnt]['module']) .",")!==false)?1:0);
	
		if ($payment_on) {
			$payment_col_count++;
			$enable_payment_array[]=array('payment_pos'=>$icnt);
		}	

		$payment_list[]=array("name"=>$selection[$icnt]['module'],"subtotal"=>0,"total"=>0,"on"=>$payment_on);	
	}
	$payment_count=count($payment_list);
	// Get the details of ordered products
	//echo $type;
//	$products_sql = "SELECT op.orders_id,op.products_id,op.products_quantity,ot.value as cost,sum(op.products_quantity*p.products_weight) as weight,o.payment_method,o.customers_id,o.customers_name,o.orders_status,o.delivery_country,o.shipping_method from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_TOTAL . " ot, ". TABLE_PRODUCTS . " p where p.products_id=op.products_id and ot.orders_id=o.orders_id and ot.title='Shipping:' and o.orders_id=op.orders_id  " . $where . $prd_where . $type_where . " and o.orders_status>1 group by op.products_id order by op.products_name";	

	if($sales_by_order==1)
	{
		if($type!='H'){
		//	$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity) as products_quantity,sum(op.final_price*op.products_quantity) as final_price,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as products_tax,((op.final_price*op.products_tax)/100) as tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,r.orders_id,r.refund_type,r.amount_type,sum(r.refund_amount) as refund_amount,r.date_created from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_REFUNDS . " r where o.orders_id=op.orders_id and o.orders_id=r.orders_id  " . $where . $type_where . " and o.orders_status=5 group by o.orders_id order by o.date_paid,o.orders_id";
			$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity) as products_quantity,sum(op.final_price*op.products_quantity) as final_price,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as products_tax,((op.final_price*op.products_tax)/100) as tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,r.refund_id,r.orders_id,r.refund_type,r.amount_type,r.refund_amount,r.date_created from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_REFUNDS . " r where o.orders_id=op.orders_id and o.orders_id=r.orders_id  " . $where . $type_where . " and o.orders_status=5 group by o.orders_id,op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,o.date_purchased,op.products_quantity,op.final_price,op.products_tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,r.orders_id,r.refund_type,r.amount_type,r.refund_amount,r.date_created order by o.date_paid,o.orders_id";
		
		}else
			$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,ot.value as cost,sum((op.products_quantity*p.products_weight)) as weight,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity) as products_quantity,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,o.delivery_country ,ot.title as shipping_method  from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_TOTAL . " ot, ". TABLE_PRODUCTS . " p where p.products_id=op.products_id and ot.orders_id=o.orders_id and ot.value>0 and ot.class='ot_shipping' and  o.orders_id=op.orders_id  " . $where . $type_where . " and o.orders_status=5 group by o.orders_id,op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,o.date_purchased,op.products_quantity,op.final_price,op.products_tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,r.refund_id,r.orders_id,r.refund_type,r.amount_type,r.refund_amount,r.date_created order by o.date_paid,o.orders_id ";
	}
	else if($type=='P' && $sort_manufact=='1'){
		$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, op.products_quantity,op.final_price,((op.final_price*op.products_tax)/100) as tax,((op.final_price*op.products_tax)/100)*op.products_quantity as products_tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,r.refund_id,r.orders_id,r.refund_type,r.amount_type,r.refund_amount,r.date_created from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p, " . TABLE_REFUNDS . " r  where o.orders_id=op.orders_id and p.products_id=op.products_id and o.orders_id=r.orders_id  " . $where . $prd_where . $type_where . "  and o.orders_status=5 order by p.manufacturers_id ";	
	//	echo $products_sql; 
	}
	// else if($type=='E'){
	// //	$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, op.products_quantity,op.final_price,((op.final_price*op.products_tax)/100) as tax,((op.final_price*op.products_tax)/100)*op.products_quantity as products_tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,r.orders_id,r.refund_type,r.amount_type,r.refund_amount,r.date_created from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_EVENTS_SESSIONS . " es, " . TABLE_REFUNDS . " r where o.orders_id=op.orders_id  and o.orders_id=r.orders_id  " . $where . $prd_where . $type_where . $eve_where . " and o.orders_status=5 and es.sessions_id=op.products_id group by op.products_id order by op.products_name";	
	
		// $products_sql = "SELECT op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, op.products_quantity, ot.value as final_price,sum(((op.final_price*op.products_tax)/100)) as tax,((op.final_price*op.products_tax)/100)*op.products_quantity as products_tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,r.orders_id,r.refund_type,r.amount_type,r.refund_amount,r.date_created from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_EVENTS_SESSIONS . " es, " . TABLE_REFUNDS . " r, " . TABLE_ORDERS_TOTAL . " ot where o.orders_id=op.orders_id  and o.orders_id=r.orders_id and o.orders_id=ot.orders_id  " . $where . $prd_where . $type_where . $eve_where . " and o.orders_status=5 and es.sessions_id=op.products_id group by op.orders_id order by op.products_name";	
	
	// }
	else if($type=='H')
		$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,ot.value as cost,sum((op.products_quantity*p.products_weight)) as weight,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity) as products_quantity,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,o.delivery_country ,o.shipping_method  from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_TOTAL . " ot, ". TABLE_PRODUCTS . " p where p.products_id=op.products_id and ot.orders_id=o.orders_id and ot.class='ot_shipping' and o.orders_id=op.orders_id and o.orders_id=r.orders_id  " . $where . $type_where . " and o.orders_status=5 group by o.orders_id,op.orders_id,op.products_id,op.products_name,ot.value,op.products_quantity,p.products_weight,o.date_paid,o.date_purchased,o.date_purchased,op.products_quantity,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,o.delivery_country ,o.shipping_method order by o.orders_id,op.orders_id ";
	else
		$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, op.products_quantity,op.final_price,round(((op.final_price*op.products_tax)/100),2) as tax,round((((op.final_price*op.products_tax)/100)*op.products_quantity),2) as products_tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,r.refund_id, r.refund_type,r.amount_type,r.refund_amount,r.date_created from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_REFUNDS . " r where o.orders_id=op.orders_id  " . $where . $prd_where . $type_where . "  and o.orders_status=5 and o.orders_id=r.orders_id order by op.products_name";
	if ($post_action=="screen") $query_split = new splitPageResultsReport($page, REPORT_MAX_ROWS_PAGE, $products_sql, $query_split_numrows);
	$products_query=tep_db_query($products_sql);
//echo $products_sql;
	if (tep_db_num_rows($products_query)>0) $found_results=true;
	while($products_result=tep_db_fetch_array($products_query)){
		//print_r($products_result);
		$id=$products_result["orders_products_id"];
		$pid=$products_result["products_id"];
		$rid = $products_result["refund_id"];
		if (isset($attributes[$id])){
			$key_id=$rid . '-' . $attributes[$id]['id'];
			$name=$attributes[$id]["name"];
			$name1=$products_result["products_name"];
		} else {
			$key_id=$rid;
			$name1='';
			$name=$products_result["products_name"];
		}
		if($sales_by_order==1 || $type=='H')$key_id=$products_result["orders_id"];
		if (!isset($details[$key_id])){
			$details[$key_id]=array("name1"=>$name1,"name"=>$name,"contents"=>array());
		}
		if($sales_by_order==1){ 
			$final_price=$products_result["final_price"];
		}else{
		if($type=='E') 
			$final_price=$products_result["final_price"];	
		else
			$final_price=$products_result["final_price"]*$products_result["products_quantity"];
		}	
		
		if($type!='H'){
			$details[$key_id]["contents"][]=array("orders_id"=>$products_result["orders_id"], "name" =>	$name, "cname"=>$products_result["customers_name"],"quan"=>$products_result["products_quantity"],"uprice"=>$products_result["final_price"],"tprice"=>$final_price,"ptax"=>$products_result["products_tax"],"tax"=>$products_result["tax"],"col_pos"=>get_payment_col($products_result["payment_method"],$products_result["orders_status"]),"date"=>format_date($products_result["date_purchased"]),"paid_date"=>format_date($products_result["date_paid"]),"orders_id"=>$products_result["orders_id"],"products_id"=>$pid,"refund_type"=>$products_result["refund_type"],"amount_type"=>$products_result["amount_type"],"refund_amount"=>$products_result["refund_amount"],"refund_date"=>format_date($products_result["date_created"]));
			
			$details[$key_id]['order_id'] = $products_result["orders_id"]; 
			$details[$key_id]['cname'] = $products_result["customers_name"]; 
			$details[$key_id]['date'] = format_date($products_result["date_purchased"]); 
			$details[$key_id]['refund_amount'] = $products_result["refund_amount"]; 
			$details[$key_id]['refund_date'] = format_date($products_result["date_created"]);
			$details[$key_id]['refund_type'] = $products_result["refund_type"]; 

			
		}else{ 
			$details[$key_id]["contents"][]=array("orders_id"=>$products_result["orders_id"],"cname"=>$products_result["customers_name"],"location"=>$products_result["delivery_country"],"weight"=>$products_result["weight"],"shipping_method"=>$products_result["shipping_method"],"cost"=>$products_result["cost"],"uprice"=>$products_result["cost"],"col_pos"=>get_payment_col($products_result["payment_method"],$products_result["orders_status"]),"date"=>format_date($products_result["date_purchased"]),"paid_date"=>format_date($products_result["date_paid"]),"orders_id"=>$products_result["orders_id"],"products_id"=>$pid,"refund_type"=>$products_result["refund_type"],"amount_type"=>$products_result["amount_type"],"refund_amount"=>$products_result["refund_amount"],"refund_date"=>format_date($products_result["date_created"]));
			
			
		}
		
		
		
	 }
	 //print_r($details);
	
	//print_r($details);
	tep_db_free_result($products_query);
	 if ($post_action!="screen"){
	  	 $report_filename=sprintf("products_sales_%s_%s",$login_id,time());
		if (($FSESSION->get("report_filename")!='')){
			$old_file=DIR_FS_CATALOG . "images/" .$FSESSION->get("sess_report_filename") . ".pdf";
			if (file_exists($old_file)) unlink($old_file);
			$old_file=DIR_FS_CATALOG . "images/" . $FSESSION->get("sess_report_filename") . ".csv";
			if (file_exists($old_file)) unlink($old_file);
		}
		$FSESSION->set("sess_report_filename",$report_filename);
	}	
//print_r($details);
//exit;
	// output to pdf
	$display_array=array();
	if(HIDE_FROM_BACKEND_MENU_PRODUCTS=='false')
		$display_array[]='P';
		$display_array[]='H';
	if(HIDE_FROM_BACKEND_MENU_EVENTS=='false')
		$display_array[]='E';
	if(HIDE_FROM_BACKEND_MENU_SUBSCRIPTIONS=='false')
		$display_array[]='S';
	if(HIDE_FROM_BACKEND_MENU_SERVICES=='false')
		$display_array[]='V';

		if ($post_action=="pdf"){
			generate_pdf();
			echo $report_filename. ".pdf";
			return;
		}
		// output to excel
		if ($post_action=="excel"){
			generate_excel();
			echo $report_filename . ".csv";
			return;
		}

	if($command=='fetch_report'){
		get_result();
		exit;  
	}
	tep_delete_temp_files("products_sales_" . $login_id);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="JavaScript" src="includes/date-picker.js"></script>
<script language="javascript" src="includes/http.js"></script>
<?php  include(DIR_WS_INCLUDES."date_format_js.php")?>
<link href="includes/jquery-ui.css" rel="stylesheet">
<script src="includes/jquery-1.10.2.js"></script>
<script src="includes/jquery-ui.js"></script>
<script language="JavaScript">
    jQuery(function() {        
    jQuery( "#txt_start_date" ).datepicker(
        {
            changeMonth: true,
            changeYear: true,
            showOn: 'button',
            buttonImage: 'images/icon_calendar.gif',
            buttonImageOnly: true,
            dateFormat: '<?php $_array=array('d','m','Y');  $replace_array=array('dd','mm','yy'); echo $date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>',
            onClose: function( selectedDate ) {
				$( "#txt_end_date" ).datepicker( "option", "minDate", selectedDate );
			}
        }
    );
    
    jQuery( "#txt_end_date" ).datepicker(
        {
            changeMonth: true,
            changeYear: true,
            showOn: 'button',
            buttonImage: 'images/icon_calendar.gif',
            buttonImageOnly: true,
            dateFormat: '<?php $_array=array('d','m','Y');  $replace_array=array('dd','mm','yy'); echo $date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>',
            onClose: function( selectedDate ) {
				$( "#txt_start_date" ).datepicker( "option", "maxDate", selectedDate );
			}
        }
    );
  });
  var t = '<?php echo $FREQUEST->getvalue('btype','string','P'); ?>';
	var product=document.getElementById("fetch_report_product");
	var events=document.getElementById("fetch_report_event");
	var subscription=document.getElementById("fetch_report_subscription");
	var service=document.getElementById("fetch_report_service");
	 
	function set_display(obj,styles){
		if(!obj) return;
		if(obj && styles)  return obj.style.display='';	
		else if(obj && !styles) return obj.style.display='none';	
	}
	function get_divs(type,product,events,subscription,service){
		if(document.getElementById("load_details")) document.getElementById("load_details").style.visibility="";
		var product_display=document.getElementById("product");
		var view_sort_manu=document.getElementById("view_sort_manu");
		var show_tax=document.getElementById("show_tax");
		var show_sales=document.getElementById("show_sales");
		if(type=='P'){
			set_display(product,true);
			set_display(product_display,true);
			set_display(show_tax,true);
			set_display(show_sales,true);
		}else if(type=='H'){	
			set_display(view_sort_manu,false);
			set_display(show_tax,false);
			set_display(show_sales,false);
	}
}
function doChange(type) {	
	if(document.getElementById("close_div_"+type) && document.getElementById("close_div_"+type).style.display==""){
		document.getElementById("close_div_"+type).style.display="none";
		return false;
	}
	get_divs(type,product,events,subscription,service);
	if(type!="") { 
		where="";
		if(get_needed_details()!='') where=get_needed_details();
		where=where+"&post_action=screen&page=1&type="+type;
		command="<?php echo tep_href_link(FILENAME_PRODUCTS_REFUNDS);?>?command=fetch_report"+sel_node+where;
		do_get_command(command);	
	}
}
function doReport(mode,page) { 
		var startDate=date_format(document.f.txt_start_date.value,'','y-m-d',true);
		var endDate=date_format(document.f.txt_end_date.value,'','y-m-d',true);
		var product = document.getElementById("fetch_report_product");
		var shipping = document.getElementById("fetch_report_shipping");
		var events=document.getElementById("fetch_report_event");
		var subscription=document.getElementById("fetch_report_subscription");
		var service=document.getElementById("fetch_report_service");
		if(!page) page=1;
		if(startDate>endDate) {
			alert("<?php echo REPORT_START_AFTER_END?>");
			return;
		}
		post_action="screen";
		if (mode==2)
			post_action="pdf";
		else if (mode==3)
			post_action="excel";
	type=t;
	if(product && product.style.display=='') type='P';
	else if(shipping &&  shipping.style.display=='') type='H';
	get_divs(type,product);
	if(get_needed_details()!='') where=get_needed_details();
	command="<?php echo tep_href_link(FILENAME_PRODUCTS_REFUNDS);?>?command=fetch_report"+sel_node+where+"&page="+page+"&post_action="+post_action+"&type="+type;
	do_get_command(command);
}
function get_needed_details(){
	sel_node="";
	tax=0;
	sort_manufact=0;
	sales_by_order=0;
	summary=0;
	where=""; 
	startDate=document.f.txt_start_date.value;
	endDate=document.f.txt_end_date.value;
	sort_manu=document.getElementById("sort_manufact");
	sale_by_order=document.getElementById("sales_by_order");
	summary=document.getElementById("summary");
	var product_display=document.getElementById("product");
	var events_display=document.getElementById("events");
	var subscription_display=document.getElementById("subscription");
	var service_display=document.getElementById("service");
	if(document.getElementById("sel_product") && product_display && product_display.style.display!='none'){
		pro_object=document.getElementById("sel_product");
		sel_node="&sel_product="+pro_object.options[pro_object.selectedIndex].value;
	}
	where="&start_date="+startDate+"&end_date="+endDate;
	var display_tax = document.getElementById("display_tax");
	set_display(display_tax,false);
	if(document.getElementById("tax") && document.getElementById("tax").checked){ 
		set_display(display_tax,true);
		where=where+"&tax=1";
	}
	if(sort_manu && sort_manu.checked) 
		where=where+"&sort_manufact=1";
	if(sale_by_order && sale_by_order.checked) 
		where=where+"&sales_by_order=1";
	if(summary && summary.checked)
		where=where+"&summary=1";
	return where;
}

function disable_products(){
	if(document.getElementById("sales_by_order") && document.getElementById("sales_by_order").checked==true){
		set_display(document.getElementById("view_all_products"),false);
		set_display(document.getElementById("view_sort_manu"),false);
		set_display(document.getElementById("view_all_products_space"),true);
	} else if(document.getElementById("view_all_products")){
		set_display(document.getElementById("view_all_products"),true);
		if(document.getElementById("product").style.display=='')
		set_display(document.getElementById("view_sort_manu"),true);
		set_display(document.getElementById("view_all_products_space"),false);
	}
}

	function do_result(result){
	  if(result.substr(result.indexOf('.'),4)=='.pdf' || result.substr(result.indexOf('.'),4)=='.csv'){
			if(result.substr(0,8)!='products')return;
			window.open("<?php echo DIR_WS_CATALOG . "images/";?>"+result);
			doReport(1);
			return;
	  }else if(result!='') {
		element=document.getElementById("get_all_results");
		if(document.getElementById("load_details")) document.getElementById("load_details").style.visibility="hidden";
		if(element) element.innerHTML=result;
	  }
	}
</script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr> 
		<!-- body_text //-->	  
		<td width=100% align=left valign="top">
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
			<tr>
				<td class="pageHeading"><?php echo HEADING_PRODUCTS_REFUNDS;?></td>	
			</tr>
		   <tr>
			   <TD>
				<FORM action="reports_refund_products.php" id="f" name="f" method="post">
					<input type="hidden" name="page" value="1">
					<input type="hidden" name="type" value="P">
					<input type="hidden" name="post_action" value="1">
			  <table cellspacing="3" cellpadding="2" class="searchArea" width="100%" border="0">
			  <tbody>
				<?php $_array=array('d','m','Y');  $replace_array=array('DD','MM','YYYY'); 	$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>
				<tr> 
				<td  width="40%" nowrap valign="top"><?php echo REPORT_START_DATE . '&nbsp;' . tep_draw_input_field('txt_start_date',format_date($start_date),' size="10"');?> 
					<!--a href="javascript:show_calendar('f.txt_start_date',null,null,'<?php echo $date_format;?>');"
					   onmouseover="window.status='Date Picker';return true;"
					   onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/>  
					   </a-->
				  <?php echo '&nbsp;'.REPORT_END_DATE . tep_draw_input_field('txt_end_date',format_date($end_date),' size="10"');?>
					<!--a href="javascript:show_calendar('f.txt_end_date',null,null,'<?php echo $date_format;?>');"
					   onmouseover="window.status='Date Picker';return true;"
					   onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/>  
					   </a-->
				  </td>
				<td width="40%" nowrap>
					<table width=100% cellspacing="0" cellpadding="0">
						<tr>
							<td><label><?php echo tep_draw_checkbox_field('summary',1,$summary) .'  ' . REPORT_SUMMARY_ONLY;?></label></td>
							<td id="show_tax" nowrap><label><?php echo tep_draw_checkbox_field('tax',1,$tax) .'  ' . TEXT_TAX;?></label></td>
							<td id="show_sales" nowrap><label><?php echo  tep_draw_checkbox_field('sales_by_order',1,$sales_by_order,''," id='sales_by_order' checked onclick='disable_products()'") .'  ' . TEXT_REFUND_BY_ORDER . '</div>';?></label></td>
						</tr>
					</table>
				</td>
				<td  align="right" nowrap valign="top">
					<?php echo '<a href="javascript:doReport(1);">' . tep_image_button('button_report_search.gif', IMAGE_SEARCH_DETAILS) . '</a>'; ?>
				</td>
				</tr>
				<tr class="main">
				<td id="view_all_products_space" align="left" width="70%" colspan="2">
				</td>
				<td  id="view_all_products" align="left" width="70%" colspan="2">
					<?php 
					echo '<div id="product">';
						echo '&nbsp;&nbsp;&nbsp;';
						$all_products_array = array();
						$all_products_array = tep_get_products_array_single();
						if(sizeof($all_products_array)>0) echo TEXT_PRODUCTS . '  ' ;
						if(sizeof($all_products_array)<=0) echo TEXT_PRODUCTS_NOT_AVAILABLE;
						else echo tep_draw_products_select_menu('sel_product',$all_products_array,$product_id,' id="sel_product"');
					
					echo '</div>';
					?>
				</td>
				<td align="right" colspan="1">
					<?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. '<a href="javascript:doReport(2);">' . tep_image_button('button_export_pdf.gif', IMG_EXPORT_PDF) . '</a>'; ?>
				</td>
			</tr>
			<tr class="main">
				<td colspan="2">
				<table width="100%"><tr class="main">
				<td id="view_sort_manu" align="left" ><label>
					<?php 	if($type=='P') {
									echo '&nbsp;&nbsp;&nbsp;' . tep_draw_checkbox_field('sort_manufact',1,$sort_manufact) .'  ' . TEXT_SORT_MANUFACTURER;
								}
					 ?>
				 </label></td>
				 </tr></table></td>
				<td colspan="1" align="right">
					<?php echo '<a href="javascript:doReport(3);">' . tep_image_button('button_export_excel.gif', IMG_EXPORT_EXCEL) . '</a>'; ?>
				</td>
			</tr>
		
		</table>
		</td>
		</form>
	<tr ><td class="cell_bg_report_header">&nbsp;</td></tr>
	</tr>
		<tr><td class="smalltext" id="load_details">Loading...</td></tr>
		<tr>
			<td id="get_all_results"></td>
		</tr>  
		<script language="javascript">
				disable_products(); 
				doReport(1);
		</script>
		</table>
	 </td>
	</tr>				
 </table>
 </td>
 </tr>
 </table>
	<!-- footer //-->
	<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
	<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');

function get_payment_col($payment_method,$order_status){
	global $cashpos,$moneyorderpos,$payment_list,$payment_col_count;
	$switch=true;
	for ($icnt=0;$icnt<count($payment_list);$icnt++){
		if ($payment_method==$payment_list[$icnt]["name"]){
			$switch=true;
			break;
		}else{
			$switch=false;
		}
	}
	if ($icnt==count($payment_list)){
		if ($order_status>2){
			$icnt=$cashpos;
		} else {
			$icnt=$moneyorderpos;
		}
	}
	if(!$switch)return -1;
	return $icnt;
}
//fixsplit
 function getDetails($type="", $detailed_view=false) {
	global $display_header,$found_results,$details, $newdetails, $FREQUEST,$payment_count,$currencies,$report_filename,$payment_list,$summary,$payment_col_count,$tax,$sales_by_order;
	$mPath = preg_split("/mPath/",$FREQUEST->servervalue('HTTP_REFERER'));
	$mPath = preg_split("/=/",$mPath[1]);
	$mPath = preg_split("/&/",$mPath[1]);
	$mPath = $mPath[0];
		
				if(!$detailed_view) {
					display_total(true,$type);
				}else if($detailed_view) {
				 echo '<tr><td class="smalltext" colspan=' . (8+$tax+$payment_col_count) . ' width=100%><b>'; 
				 $str="img_$title";
				 ?> 
				 <table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr height="20">
					<td class="contentTitle" style="cursor:hand;cursor:pointer;" onClick="javascript:doChange('<?php echo $type;?>');" valign="top"><span style="background:#FFFFFF;padding-right:5px"><?php echo get_heading($type);?></span></td><td width="15" align="right"><img style="cursor:hand;cursor:pointer;" src="images/template/panel_up.gif" id="img_' . $title . '" border="0"></td>
					</tr>
					<tr height="10">
					<td></td>
					</tr>
				</table><?php echo '</b></td></tr><tr><td colspan='.(8+$tax+$payment_col_count).'><table id="close_div_'.$type.'" border="0" cellpadding="0" cellspacing="0" width="98%">';
				 if($type=='H')
				 {
				 	if($sales_by_order==1)
					{
						if($tax){
							$colspan=5;
						}else{
							$colspan=3;
						}
					}
					else
					{
						if($tax){
							$colspan=7+$payment_col_count-5;
						}else{
							$colspan=6+$payment_col_count-5;
						}
					}?>
					<tr class="dataTableHeadingTitleRow">
					<td colspan="<?php echo 6+$payment_col_count;?>" width="100%"><table width="100%">
					<tr class="dataTableHeadingTitleRow">
					<td class="dataTableHeadingTitleContent" width="5%"><?php echo  TEXT_INDEX;?></td>
					<td class="dataTableHeadingTitleContent"  width="15%"><?php echo  TEXT_CUSTOMER_NAME;?></td>
					<td class="dataTableHeadingTitleContent"  align="left" width="10%" ><?php echo TEXT_ORDER_ID;?></td>
					<td class="dataTableHeadingTitleContent"  align="left" width="20%"><?php echo TEXT_LOCATION;?></td>
					<?php if($payment_col_count>2){?>
					<td class="dataTableHeadingTitleContent" colspan="<?php echo $payment_col_count;?>"  align="left" nowrap width="35%"><?php echo TEXT_SHIPPING_METHOD;?></td>
					<?php }else{?>
					<td class="dataTableHeadingTitleContent" align="left" nowrap width="35%"><?php echo TEXT_SHIPPING_METHOD;?></td>
					<?php }?>
					<td class="dataTableHeadingTitleContent"  align="right" width="5%"><?php echo TEXT_WEIGHT;?></td>
					<td class="dataTableHeadingTitleContent"  align="right" width="10%"><?php echo TEXT_COST;?></td>
				</tr>
			
					</table></td>
					</tr>
				<?php }				 	
				if ($found_results) {
					reset($details);
					$prev_product_id=-1;
					$prev_products_name = "";
					$prev_attribute_id='-1';
					$row_cnt=1;
					$unit_subtotal=0;
					$unit_total=0;
					$all_subtotal=0;
					$all_subtax=0;
					$all_total=0;
					$cat_name="";
					$pre_id="";
					$prd_unit_subtotal=0;
					$prd_unit_all_subtotal=0;
					$prd_quan_subtotal=0;
					$all_refundtotal=0;
					$product_refundtotal=0;
									
					if($type=='H'){
					?>
						<tr>
						<td colspan="<?php echo 6+$payment_col_count;?>" width="100%">
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<?php 
					} 	// index ?>
					<tr class="cell_bg_report_header">
					<td class="dataTableHeadingTitleContent" width="3%"><?php echo TEXT_INDEX ?></td>
					<?php 
					if($summary!=1)
					if($sales_by_order==0){?> 
					<td class="dataTableHeadingTitleContent" align="left" width="15%"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;".TEXT_CLIENT;?></td>
					<?php }else if($sales_by_order==1){?>
					<td class="dataTableHeadingTitleContent" align="left" width="15%"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;" . TEXT_CLIENT;?></td>
					<td class="dataTableHeadingTitleContent" align="left" width="5%"><?php  echo TEXT_ORDER_ID ;?></td>
					<?php }if($summary!=1){?>
					<td class="dataTableHeadingTitleContent"  width="10%" align="right"><?php  echo TEXT_SALE_DATE;?></td>
					<?php }else if($summary==1 && $sales_by_order==0){ ?>
					<td class="dataTableHeadingTitleContent"  align="center"></td>
					<td class="dataTableHeadingTitleContent"  align="right" colspan="2"></td>
					<?php }else if($summary==1 && $sales_by_order==1){?>
					<td class="dataTableHeadingTitleContent"  align="center"></td>
					<td class="dataTableHeadingTitleContent"  align="right"></td>
					<td class="dataTableHeadingTitleContent"  align="right" colspan="2"></td>
					<?php } ?>
					<td class="dataTableHeadingTitleContent"  align="right" width="10%"><?php echo TEXT_TOTAL;?></td>
					<?php if($tax==1){?>
					<td class="dataTableHeadingTitleContent" align="right" width="10%"><?php echo TEXT_TAX;?></td>
					<?php }?>
					<?php if($summary==1){?>
					<td class="dataTableHeadingTitleContent" align="right" width="10%"><?php echo TEXT_REFUND;?></td>
					<?php } ?>
					<?php if($sales_by_order==0){?>
					<?php if($summary!=1){?>
					<td class="dataTableHeadingTitleContent"  width="10%" align="right"><?php  echo TEXT_REFUND_DATE;?></td>
					<td class="dataTableHeadingTitleContent"  width="7%" align="right"><?php echo TEXT_REFUND_TYPE;?></td>
					<td class="dataTableHeadingTitleContent"  width="10%" align="right"><?php echo TEXT_REFUND_AMOUNT;?></td>
					<?php } 
					}if($sales_by_order==1 && $summary!=1){?>
					<td class="dataTableHeadingTitleContent"  width="10%" align="right"><?php echo TEXT_REFUND_DATE;?></td>
					<td class="dataTableHeadingTitleContent"  width="10%" align="right"><?php echo TEXT_REFUND_TYPE;?></td>
					<td class="dataTableHeadingTitleContent"  width="10%" align="right"><?php echo TEXT_REFUND_AMOUNT;?></td>
					<?php }?>
			<!--		<td class="dataTableHeadingTitleContent"  align="right" width="10%"><?php echo TEXT_TOTAL;?></td>  !-->
					<?php  
							for ($icnt=0;$icnt<$payment_count;$icnt++){
								if ($payment_list[$icnt]["on"]==0) continue;
									$name=(strlen($payment_list[$icnt]["name"])>6?substr($payment_list[$icnt]["name"],0,6):$payment_list[$icnt]["name"]);
									if(substr($payment_list[$icnt]["name"],0,11)=='Credit Card' && strpos($payment_list[$icnt]["name"],"img")>0)	$payment_list[$icnt]["name"]='Credit Cart'; 
									//echo '<td  wrap class="dataTableHeadingTitleContent" align="right" title="' . $payment_list[$icnt]["name"] .'">' .  $name . '</td>';
								//	echo '<td class="dataTableHeadingTitleContent" align="right" title= $payment_list[$icnt]["name"]   > ' . $name . '</td>';
							}
				//}
							
			 ?>
				</tr><?php //echo "<pre>"; print_r($details);
					//while(list($key,)=each($details)){
						foreach (array_keys($details) as $key ) {
						//FOREACH 
						$row=&$details[$key];
						$splt_key=preg_split("/-/",$key);
						$cat_id=get_categories_name($content['products_id'],'cat_id');
						if($pre_id!=$cat_id){
								$cat_name=get_categories_name($content['products_id']);													
								$pre_id=get_categories_name($content['products_id'],'cat_id');
								
						}
					   if (count($splt_key)>1 && $sales_by_order==0){
							if ($splt_key[0]!=$prev_product_id){
								if ($unit_subtotal>0) 
								{
									print_total_row($unit_subtotal,$all_subtotal,$all_subtax,$quan_subtotal,$all_refundtotal);
									$unit_subtotal=0;
									$all_subtotal=0;
									$all_subtax=0;
									$quan_subtotal=0;
									$all_refundtotal=0;
								}
								echo '<tr class="dataTableHeadingRow" height="20"><td class="dataTableHeadingContent" colspan="' . (7+$tax+$payment_col_count) . '">' . $row["name1"] . '</td></tr>';
								$prev_attribute_id=-1;
								$prev_product_id=$splt_key[0];
							}
						//		if ($splt_key[1]!=$prev_attribute_id && $sales_by_order==0){
							if ($key!=$prev_attribute_id && $sales_by_order==0){
								if ($unit_subtotal>0) 
								{
									print_total_row($unit_subtotal,$all_subtotal,$all_subtax,$quan_subtotal,$all_refundtotal);
									$unit_subtotal=0;
									$all_subtotal=0;
									$all_subtax=0;
									$quan_subtotal=0;
									$all_refundtotal=0;
								}
									echo '<tr class="dataTableHeadingRow" height="20"><td class="dataTableHeadingContent" colspan="' . (7+$tax+$payment_col_count) . '">' . tep_draw_separator('pixel_trans.gif',10,1) .$row["cname"] . '</td></tr>';
									//$prev_attribute_id=$splt_key[1];
									$prev_attribute_id=$key;
							}
						} else {	
							if (($splt_key[0]!=$prev_product_id || $prev_attribute_id!=-1) && $sales_by_order==0){
								if ($unit_subtotal>0 && $row["name"]!=$prev_products_name) 
								{
									print_total_row($unit_subtotal,$all_subtotal,$all_subtax,$quan_subtotal,$all_refundtotal);
									$unit_subtotal=0;
									$all_subtotal=0;
									$all_subtax=0;
									$quan_subtotal=0;
									$all_refundtotal=0;
								}
								if ($splt_key[0]!=$prev_product_id && $row["cname"]!=$prev_products_name) echo '<tr class="dataTableHeadingRow" height="20"><td class="dataTableHeadingContent" colspan="' . (2+$tax+$payment_col_count) . '">' . $row["cname"] . '( Order No. ' . $row["order_id"] . ' )' . '</td>
								<td class="dataTableHeadingContent" colspan="' . (5+$tax+$payment_col_count) . '" align="right">' . $row["refund_amount"] . '</td>
								</tr>';
								$prev_product_id=$splt_key[0];
								$prev_products_name = $row["name"];
								$prev_attribute_id=-1;
							}
						}
						$contents=&$row["contents"];
						if($type=='H')
						{
							if($sales_by_order==1)
							{
								if($tax)
									$colspan=6+$payment_col_count-6;
								else
									$colspan=5+$payment_col_count-6;
							}
							else
							{
								if($tax)
									$colspan=7+$payment_col_count-6;
								else
									$colspan=6+$payment_col_count-6;
							}
						}					
						for ($icnt=0,$n=count($contents);$icnt<$n;$icnt++){
							$content=&$contents[$icnt];
							if($row_cnt%2==0)
								$class='class="dataTableRowEven"';
							else
								$class='class="dataTableRowOdd"';	
						//print_r($content);
						
							if(($pre_name!=$content["cname"]) && ($pre_oid!=$content["orders_id"])){ 
									$products_names_p = $content["cname"];
									$pre_name=$content["cname"];
									$pre_oid=$content["orders_id"];
									$price=tep_add_tax($content["tprice"], $content["ptax"]);
								//	echo 'id=' . $pre_oid;
								}else{
									$products_names_p = $content["cname"];
									$price=tep_add_tax($content["tprice"], $content["ptax"]);	
						     }
					
						if(!$summary) {
						?>
							<?php if($sales_by_order==1){
									if($type!='H'){?>
									<tr <?php echo $class; ?> valign="top" height="20" style="cursor:pointer;cursor:hand" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="javascript:location.href='<?php echo tep_href_link(FILENAME_ORDERS,'mPath=' . $mPath . '&oID=' .$content["orders_id"] . '&action=edit&return=rr&type='.$type);?>';">
									<td class="dataTableContent"><?php echo $row_cnt;?></td>
									<td  class="dataTableContent"><?php echo $products_names_p;?></td>
									<td  class="dataTableContent" align="left"><?php echo $content["orders_id"];?></td>
									<td  class="dataTableContent" align="right"><?php echo $content["date"];?></td>
									<td  class="dataTableContent"  align="right"><?php echo $currencies->format($content["tprice"]+$content["ptax"]);?></td>
									<?php if($tax==1){?>
									<td  class="dataTableContent" align="right"><?php echo $currencies->format($content["ptax"]);?></td>
									<?php } ?>
									<td  class="dataTableContent" align="right"><?php echo $content["refund_date"];?></td>
									<td  class="dataTableContent" align="right"><?php echo (($content["refund_type"]=='P')?'Partial':'Full');?></td>
									<td  class="dataTableContent" align="right"><?php echo $currencies->format($content["refund_amount"]);?></td>
									<?php
									}
									else
									{?>
									<tr <?php echo $class; ?> valign="top" height="20" style="cursor:pointer;cursor:hand" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="javascript:location.href='<?php echo tep_href_link(FILENAME_ORDERS,'mPath=' . $mPath . '&oID=' .$content["orders_id"] . '&action=edit&return=rr&type='.$type);?>';">
									<td class="dataTableContent" width="5%"><?php echo $row_cnt;?></td>
									<td  class="dataTableContent" width="15%"><?php echo $content["cname"];?></td>
									<td   class="dataTableContent" align="left" width="10%"><?php echo $content["orders_id"];?></td>
									<td  class="dataTableContent" align="left" width="20%"><?php echo $content["location"];?></td>
									<?php if($payment_col_count>2){?>
									<td  class="dataTableContent" colspan="<?php echo $payment_col_count-1;?>" align="left" width="30%"><?php $ship_method=preg_split('/-/',$content["shipping_method"]);echo $ship_method[0];?></td>
									<?php }else{?>
									<td  class="dataTableContent" align="left" width="30%"><?php $ship_method=preg_split('/-/',$content["shipping_method"]);echo $ship_method[0];?></td>
									<?php }?>
									<td  class="dataTableContent"  align="right" width="10%"><?php echo $content["weight"];?></td>
									<td  class="dataTableContent"  align="right" width="10%"><?php echo $currencies->format($content["cost"]);?></td>
						<?php } 
						}
						if($sales_by_order==0){
							if($type!='H'){?>
							<tr <?php echo $class; ?> valign="top" height="20" style="cursor:pointer;cursor:hand" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="javascript:location.href='<?php echo tep_href_link(FILENAME_ORDERS,'mPath=' . $mPath . '&oID=' .$content["orders_id"] . '&action=edit&return=rr&type=' . $type);?>';">
							<td class="dataTableContent"><?php echo $row_cnt;?></td>
							<td class="dataTableContent"><?php echo $content["name"];?></td>
							<td class="dataTableContent" align="right"><?php echo $content["date"];?></td>
							<?php if($type=='E'){ ?>
							<td  class="dataTableContent"  align="right"><?php echo $currencies->format($content["tprice"]);?></td>
							<?php }else{
							if($row['refund_type']=='F'){
								$pprice = '0.00';	
							}else if($row['refund_type']=='P'){	
								if($content["quan"]==0){
									$pprice = '0.00';	
								}else{
									$pprice = $content["uprice"];	
								} 	
							}
							?>
							<td  class="dataTableContent"  align="right"><?php echo $currencies->format($pprice+$content["ptax"]);?></td>
						    <?php } if($tax==1){?>
							<td  class="dataTableContent" align="right"><?php echo $currencies->format($content["ptax"]);?></td>
						    <?php
							 }
						   ?>
				     		<td class="dataTableContent" align="right"><?php echo $content["refund_date"];?></td>  
							<td  class="dataTableContent"  align="right"><?php echo (($content["refund_type"]=='P')?'Partial':'Full');?></td>
							<td  class="dataTableContent" align="right"><?php //echo $currencies->format($row["refund_amount"]);?></td>
							<?php 
								}else{?>
									<tr <?php echo $class; ?> valign="top" height="20" style="cursor:pointer;cursor:hand" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="javascript:location.href='<?php echo tep_href_link(FILENAME_ORDERS,'mPath=' . $mPath . '&oID=' .$content["orders_id"] . '&action=edit&return=rr&type='.$type);?>';">
									<td class="dataTableContent"><?php echo $row_cnt;?></td>
									<td  class="dataTableContent"><?php echo $content["cname"];?></td>
									<td   class="dataTableContent" align="left"><?php echo $content["orders_id"];?></td>
									<td  class="dataTableContent" align="left"><?php echo $content["location"];?></td>
									<?php if($payment_col_count>2){?>
									<td  class="dataTableContent" colspan="<?php echo $payment_col_count;?>" align="left"><?php $ship_method=preg_split('/-/',$content["shipping_method"]);echo $ship_method[0];?></td>
									<?php }else{?>
									<td  class="dataTableContent" align="left"><?php $ship_method=preg_split('/-/',$content["shipping_method"]);echo $ship_method[0];?></td>
									<td  class="dataTableContent"  align="right"><?php echo $content["weight"];?></td>
									<?php }?>
									<td  class="dataTableContent"  align="left" width="10%"><?php echo $content["weight"];?></td>
									<td  class="dataTableContent"  align="right"><?php echo $currencies->format($content["cost"]);?></td>
							<?php }
							}?>
						</tr>
						<?php
							} else { // $summary 
							for ($jcnt=0;$jcnt<$payment_count;$jcnt++){
									if ($payment_list[$jcnt]["on"]==0) continue;
									if ($jcnt==$content["col_pos"]){
										$payment_list[$content["col_pos"]]["subtotal"]+=$content["tprice"]+$content["ptax"];
									}
								} // $jcnt
						   }						   
						$unit_subtotal = $content["uprice"]+$content["tax"];
						$all_subtotal+=$content["tprice"]+$content["ptax"];
						$all_subtax+=$content["ptax"];
						//$all_refundtotal+=$content["refund_amount"];
					if($type!='H')
							$quan_subtotal+=$content["quan"];
						else
							$quan_subtotal+=$content["weight"];
						$row_cnt++;
					} // $icnt
					//echo $row["refund_amount"];
					$product_refundtotal+=$row["refund_amount"];
					} //$key
					if ($unit_subtotal>0)
					{
						 
						 echo '<tr>
							<td class="smallText" colspan="5" align="right"></td>
							<td class="dataTableContent" align="right">Refund Total</td> 
							<td class="dataTableContent" align="right">'.$currencies->format($product_refundtotal).'</td>
				
		 				</tr>';
						 
						 
						 
						 //print_total_row($unit_subtotal,$all_subtotal,$all_subtax,$quan_subtotal,$all_refundtotal,'prt');
					}
					if($type=='H'){
					?>
					</table></td></tr>
					<?php 
					}
				} else { //$found_results
					echo '<tr><td colspan="' . (10+$tax+$payment_col_count) . '" class="main" align="center">' . TEXT_NO_RECORDS_FOUND . '</td></tr>';
				}
					//tep_content_title_bottom_div();
					echo '</table></td></tr>';
			}
				?>
<?php			
}

function display_total($show_total=false,$prd_type) { 
	global $products_id, $type, $where, $prd_where, $payment_col_count, $currencies, $payment_list, $grand_total,$grand_total_refund,$grand_total_tax,$grand_payment_array,$grand_pay_array,$tax,$summary,$tax,$sales_by_order,$page,$grand_quan,$grand_unit_price,$all_refundtotal;
	$heading=get_heading($prd_type);
	$payment=array();

		if($type==$prd_type){ 
			$total_sql="SELECT op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity) as quan,sum(round(op.final_price,2)) as pro_total,sum(round((op.products_quantity*op.final_price),2)) as total,sum(round((((op.final_price*op.products_tax)/100)*op.products_quantity),2)) as total_tax,sum(round(((op.final_price*op.products_tax)/100),2)) as tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,sum(r.refund_amount) as refund_amount,r.amount_type from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_REFUNDS . " r  where o.orders_id=op.orders_id and o.orders_id=r.orders_id  " . $where . $prd_where . $type_where . " and op.products_type='" . tep_db_input($prd_type) . "' and o.orders_status=5 group by payment_method,op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,o.date_purchased,op.products_quantity,op.final_price,op.products_tax,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,r.orders_id,r.refund_type,r.amount_type,r.refund_amount,r.date_created  order by op.products_name ";
		}else{
			$total_sql=	"SELECT sum((round(op.final_price,2))) as pro_total,sum(op.products_quantity) as quan,op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(round((op.products_quantity*op.final_price),2)) as total,sum(round((((op.final_price*op.products_tax)/100)*op.products_quantity),2)) as total_tax,sum((round(((op.final_price*op.products_tax)/100),2))) as tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id, sum(r.refund_amount) as refund_amount,r.amount_type from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_REFUNDS . " r " . $session_table . " where o.orders_id=op.orders_id and o.orders_id=r.orders_id " . $where . $event_where . " and op.products_type='" . tep_db_input($prd_type) . "' and o.orders_status=5  group by payment_method,op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,o.date_purchased,op.products_quantity,op.final_price,op.products_tax,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,r.orders_id,r.refund_type,r.amount_type,r.refund_amount,r.date_created order by op.products_name";
		}	
	
		if($prd_type=='H')$total_sql="select sum(ot.value) as shipping_total from " . TABLE_ORDERS . " o," . TABLE_ORDERS_TOTAL . "  ot where ot.orders_id=o.orders_id and ot.value!=0 " . $where . " and ot.class='ot_shipping' and o.orders_status=5 group by o.orders_status=5";
		$total_shipping = '';
		$total_query=tep_db_query($total_sql);
		$prd_total_array=array('product_type'=>$prd_type,'title'=>$heading,'total'=>0,'total_tax'=>0,'payment'=>array()); 
		
		
		while($total_result=tep_db_fetch_array($total_query)) {
			if($total_result['shipping_total']){
				$total_shipping = $total_result['shipping_total'];
			}
			$prd_total_array['payment'][get_payment_col($total_result['payment_method'],$total_result['order_status'])]+=$total_result['total']+$total_result['total_tax'];
		
		
			$prd_total+=$total_result['total']+$total_result['total_tax']; 
			$prd_total_tax+=$total_result['total_tax'];
			$quan+=$total_result["quan"];
			$pro_total+=$total_result["pro_total"]+$total_result['tax'];
			$refund_total+=$total_result["refund_amount"];


		} 
		$prd_total_array['total']=$prd_total; 
		$prd_total_array['total_tax']=$prd_total_tax;
		$prd_total_array['quan']=$quan;
		$prd_total_array['pro_total']=$pro_total;
		$prd_total_array['refund_amount']=$refund_total;
		$payment=$prd_total_array['payment'];
		if(count($payment)>0)
			ksort($payment); 
		$grand_unit_price+=$pro_total;
		$grand_quan+=$quan;
		$grand_total+=$prd_total;
		$grand_total_refund+=$refund_total;
		if($tax==1)$grand_total_tax+=$prd_total_tax;
		$grand_payment_array=array();
		for($icnt=0;$icnt<count($payment_list);$icnt++) { 
			if($payment_list[$icnt]['on']>0) { 
				$grand_payment_array[$icnt]=$payment[$icnt];
				$grand_pay_array[$icnt][]=$payment[$icnt];
			}
		}

	if($show_total) {
		if($type!=$prd_type && $prd_total_array['title']!='Shipping') { 
		?>
		   <tr><td class="smalltext" colspan='<?php echo (8+$tax+$payment_col_count) ?>' width=100%>
				 <table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr height="20">
					<td class="contentTitle" style="cursor:hand;cursor:pointer;" onClick="javascript:doChange('<?php echo $prd_type;?>');" valign="top"><span style="background:#FFFFFF;padding-right:5px"><?php echo $prd_total_array['title'];?></span></td><td width="15" align="right"><a style="cursor:hand;cursor:pointer;" onClick="javascript:doChange('<?php echo $prd_type; ?>')"><img src="images/template/panel_down.gif" id="img_' . $title . '" border="0"></a></td>
					</tr>
					<tr height="10">
					<td></td>
					</tr>
				</table>
			</td></tr>
			 <tr  style="cursor:pointer;cursor:hand">
				<?php
				if($summary==1)
					$span=($sales_by_order==0)?4:5;
				else 
					$span=($sales_by_order==0)?3:4;
				echo '<td colspan=' . $span . '></Td>';
			?>
			<?php 
		} else if($type==$prd_type){
			 ?> 
			<tr>
			<?php if($summary==1){ ?>
				<td class="smallText" colspan=<?php $span=($sales_by_order==0)?3:4;echo $span;?> align="right"></td>
			<?php }else{ ?>
				<td class="smallText" colspan=<?php $span=($sales_by_order==0)?2:3;echo $span;?> align="right"></td>
			<?php } ?>
			<td class="smallText"  nowrap align="right"><b>
			<?php echo $prd_total_array['title'] . '&nbsp;Total';?> 
				</b></td>
			<?php 
		//}
			 ?>
			 <?php if($sales_by_order==0){?>	
		<!--	<td class="dataTableContent" align="right" >
			<?php 
				if($pro_total) echo  $currencies->format($pro_total);
			?>
		!-->	
			</td>
		<!--	<td class="dataTableContent" align="right" >
			<?php if($total_shipping=='')echo $quan; ?>
			</td>
	    !-->		
			<?php }
			?>
			<td class="dataTableContent" align="right" >
			<?php 
				if($prd_total)echo $currencies->format($prd_total);
				//if($total_shipping)echo $currencies->format($total_shipping);
			?>
			</td>
			<?php 
				if($tax==1){
					echo '<td class="dataTableContent" align="right" >';
					if($prd_total_tax)echo $currencies->format($prd_total_tax);
					echo '</td>';
				}
			?>
			<?php if($summary!=1){ ?>
				<td class="dataTableContent" align="right" ></td>
				<td class="dataTableContent" align="right" ></td> 
				<Td class="dataTableContent" align="right" >
				<?php 
					if($refund_total) echo $currencies->format($refund_total); 
				?>
				</Td>
			<?php }else{ ?>
				<Td class="dataTableContent" align="right" >
				<?php 
					if($refund_total) echo $currencies->format($refund_total); 
				?>
				</Td>
               <?php } 
			   		} 
				}
			 ?>	
		 </tr>		
			
<?php 		
  } 
function get_grand_total($grand_total,$grand_total_tax,$grand_total_refund,$grand_pay_array,$grand_unit_price,$grand_quan) {
	global $where, $prd_where, $payment_list, $payment_count, $currencies, $grand_total,$grand_total_tax,$grand_total_refund,$grand_pay_array,$tax,$summary,$sales_by_order,$grand_unit_price,$grand_quan;
	////
	echo '<tr class="cell_bg_report_header">';
	if($summary==1) $span=($sales_by_order==0)?4:5;
	else 
		$span=($sales_by_order==0)?3:4;
	echo '<td align="right" class="dataTableHeadingContent" colspan=' .$span . '></td>';
	if($sales_by_order==0)
	{
	}
	echo '<td class="dataTableHeadingTitleContent"  align="right" width="10%">'.TEXT_TOTAL.'</td>';
	if($tax==1){
		echo '<td class="dataTableHeadingTitleContent" align="right" width="10%">'.TEXT_TAX .'</td>';
	 }
	 
	if(($sales_by_order==1 && $summary==1) || $summary==1){
	echo '<td class="dataTableHeadingTitleContent"  width="15%" align="right">'.TEXT_REFUND_AMOUNT.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	}else if($sales_by_order==0 || $sales_by_order==1 || $tax==1){
	echo '<td  class="dataTableHeadingContent"  align="right"></td>' ;
	echo '<td  class="dataTableHeadingContent"  align="right"></td>' ; 
	echo '<td class="dataTableHeadingTitleContent"  width="15%" align="right">'.TEXT_REFUND_AMOUNT.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	}else{
	echo '<td class="dataTableHeadingTitleContent"  width="15%" align="right">'.TEXT_REFUND_AMOUNT.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';	}
	
	echo '</tr>';
	/////
	echo '<tr  class="dataTableHeadingTotal">';
	if($summary==1) $span=($sales_by_order==0)?3:4;
	else 
		$span=($sales_by_order==0)?2:3;
	echo '<td align="right" class="dataTableHeadingContent" colspan=' .$span . '></td>';
	echo '<td class="dataTableHeadingContent"align=right ><b>Grand Total</b></td>';
	if($sales_by_order==0)
	{
	}
	echo '<td  class="dataTableHeadingContent"  align="right">' . (($grand_total)?$currencies->format($grand_total):'') . '</td>' ;
	if($tax==1)	echo '<td class="dataTableHeadingContent"  align="right">' . (($grand_total_tax)?$currencies->format($grand_total_tax):'') . '</td>';
	if(($sales_by_order==1 && $summary==1) || $summary==1){
	echo '<td  class="dataTableHeadingContent" align="right">' . (($grand_total_refund)?$currencies->format($grand_total_refund):'') . '&nbsp;&nbsp;&nbsp;&nbsp;</td>' ;
	}else if($sales_by_order==0 || $sales_by_order==1 || $tax==1){
	echo '<td  class="dataTableHeadingContent"  align="right"></td>' ;
	echo '<td  class="dataTableHeadingContent"  align="right"></td>' ; 
	echo '<td  class="dataTableHeadingContent" align="right">' . (($grand_total_refund)?$currencies->format($grand_total_refund):'') . '&nbsp;&nbsp;&nbsp;&nbsp;</td>' ;
	}else{
	echo '<td  class="dataTableHeadingContent" align="right">' . (($grand_total_refund)?$currencies->format($grand_total_refund):'') . '&nbsp;&nbsp;&nbsp;&nbsp;</td>' ;
	}
	echo '</tr>';
} 

function get_heading($prd_type) {

 	if($prd_type=='P') $heading=TEXT_HEADING_PRODUCTS;
	else if($prd_type=='S') $heading=TEXT_HEADING_SUBSCRIPTIONS;
	else if($prd_type=='E') $heading=TEXT_HEADING_EVENTS;
	else if($prd_type=='V') $heading=TEXT_HEADING_SERVICES;
	else if($prd_type=='H') $heading=TEXT_HEADING_SHIPPING;
	return $heading;
}	 
function print_total_row($unit_subtotal,$all_subtotal,$all_subtax,$quan_subtotal,$all_refundtotal,$prt=''){
	global $payment_list,$payment_count,$currencies,$prd_sub_total,$type,$tax,$summary,$sales_by_order,$sort_manufact,$payment_col_count;
		echo "<tr>";
		if($type=='H')
		{
			if($sales_by_order==1)
			{
				if($tax)
					$colspan=7+$payment_col_count-3+1;
				else
					$colspan=6+$payment_col_count-3+1;
			}
			else
			{
				if($tax)
					$colspan=8+$payment_col_count-3+1;
				else
					$colspan=7+$payment_col_count-3+1;
			}
			if($payment_col_count>2)
				echo '<td class="smallText" align="right" colspan=' . (6+$payment_col_count-2) . '><b>' . TEXT_SUBTOTAL . '</b></td>';
			else
				echo '<td class="smallText" align="right" colspan=6><b>' . TEXT_SUBTOTAL . '</b></td>';
			echo '<td class="smallText" align="right" width="5%">' . number_format($quan_subtotal,2) . '</td>';
			echo '<td class="smallText" align="right" width="10%">' . $currencies->format($unit_subtotal) . '</td></tr>';
			return;
		}
		if($sales_by_order==1 && $summary!=1){
		 	echo '<td class="smallText" align="right" colspan=3></td><td align=right class=smalltext><b>'. TEXT_SUBTOTAL .'</b></td>';
		}else if($sales_by_order==1 && $summary==1) echo '<td colspan=4 class="smallText" align="right"</td><td  class=smalltext align=right><b>'. TEXT_SUBTOTAL .'</b></td>';
		else if($sales_by_order==0 && $summary!=1) echo "<td  colspan=2 >&nbsp;</td><td align=right  class='smalltext'><b>". TEXT_SUBTOTAL ."</b></td>";
		
		else if($sales_by_order==0 && $summary==1 && $sort_manufact==1){ 
			echo '<td  colspan=3>&nbsp;</td><td  class="smallText" align="right"><b>'. TEXT_SUBTOTAL .'</b></td>'; 
		}else if(($sales_by_order==0 && $summary==1 && $sort_manufact==0)) {
			 echo '<td colspan=3>&nbsp;</td><td  class="smallText" align="right"><b>'. TEXT_SUBTOTAL .'</b></td>'; 
		}if($sales_by_order==0){ 
			if($summary==1){   
				//echo '<td  class="smallText" align="right">' . $currencies->format($unit_subtotal) .'</td>';
				//echo '<td  class="smallText"  align="right">'. $quan_subtotal .'</td>';
			}else {
			/*	echo '<td  class="smallText" align="right">' . $currencies->format($unit_subtotal) .'</td>';
				echo '<td  class="smallText"  align="right">'. $quan_subtotal .'</td>';
			*/
			}
		}else if($sales_by_order){ 
			//echo '<td  class="smallText" align="right">' . $currencies->format($unit_subtotal) .'</td>';
			//echo '<td  class="smallText"  align="right">'. $quan_subtotal .'</td>';
		}
		//echo '<td  class="smallText"  align="right">'. $currencies->format($all_subtotal) .'</td>';
		echo '<td  class="smallText"  align="right"></td>';
		if($tax==1){
			echo '<td  class="smallText"  align="right">'. $currencies->format($all_subtax) .'</td>';
		}
		if($summary==1){
		echo '<td  class="smallText"  align="right">'. $currencies->format($all_refundtotal) .'</td>';
		}else{
		echo '<td  class="smallText"  align="right"></td>';
		echo '<td  class="smallText"  align="right"></td>';
		//echo '<td  class="smallText"  align="right">'. $currencies->format($all_refundtotal) .'</td>';
		echo '<td  class="smallText"  align="right"></td>';
		}//echo '<tr height="20"><td><td><td class="smallText" align="right"><b>' . TEXT_SUBTOTAL . '<b></td><td class="smallText" align="right">'  . $currencies->format($unit_subtotal) . '</td><td class="smallText" align="right">' . $quan_subtotal . '</td><td class="smallText" align="right">' . $currencies->format($all_subtotal) . '</td>';
		
	if($prt!="") {
		display_total(true,$type);
	} 
}


function print_total_row_excel($unit_subtotal,$all_subtotal,$quan_subtotal,$all_refundtotal,$prt="",$type,&$unit_tax,$all_tax=""){
	global $grand_unit_price,$grand_total,$grand_quan,$grand_total_tax,$grand_total_refund,$grand_pay_array,$payment_list,$payment_count,$currencies,$where,$prd_where,$tax,$sales_by_order,$summary;
	$payment=array();
	if($prt=="prt" || $prt=="")
	{
		if($type=='H')
		{
				$result=sprintf(",,,,\"%s\",\"%s\",\"%s\"," , TEXT_SUBTOTAL ,number_format($quan_subtotal,2),$currencies->format($unit_subtotal));
		}
		if($sales_by_order==1 && $summary==0){
			if($tax==1){
				$result=sprintf(",,,\"%s\",\"%s\",\"%s\",,,\"%s\"," , TEXT_SUBTOTAL ,$currencies->format($all_subtotal),$currencies->format($unit_tax),$currencies->format($all_refundtotal));
			}else{
				$result=sprintf(",,,\"%s\",\"%s\",,,\"%s\"," , TEXT_SUBTOTAL ,$currencies->format($all_subtotal),$currencies->format($all_refundtotal));
			}
		}else if($summary==1){
			if($tax==1){
			if($sales_by_order==1){ 
				$result=sprintf(",,,,\"%s\",\"%s\",\"%s\",,,\"%s\"," , TEXT_SUBTOTAL , $currencies->format($all_subtotal),$currencies->format($unit_tax),$currencies->format($all_refundtotal));
			}else{	
				$result=sprintf(",,,\"%s\",\"%s\",\"%s\",,,\"%s\"," , TEXT_SUBTOTAL , $currencies->format($all_subtotal),$currencies->format($unit_tax),$currencies->format($all_refundtotal));
			  }
			}else{
			if($sales_by_order==1){
				$result=sprintf(",,,,\"%s\",\"%s\",,,\"%s\"," , TEXT_SUBTOTAL , $currencies->format($all_subtotal),$currencies->format($all_refundtotal));
			}else {
				$result=sprintf(",,,\"%s\",\"%s\",,,\"%s\"," , TEXT_SUBTOTAL ,$currencies->format($all_subtotal),$currencies->format($all_refundtotal));
			 }
			}
		}
		else{
			if($tax==1){
				$result=sprintf(",,\"%s\",\"%s\",\"%s\",,,\"%s\"," , TEXT_SUBTOTAL , $currencies->format($all_subtotal),$currencies->format($unit_tax),$currencies->format($all_refundtotal));
			}else{
				$result=sprintf(",,\"%s\",\"%s\",,,\"%s\"," , TEXT_SUBTOTAL ,$currencies->format($all_subtotal),$currencies->format($all_refundtotal));
			}
		}
	$result.="\n";
	}
	if($prt=="prt" || $prt=="prt_type_total" && $type!='H') {
			if($sales_by_order==1)$total_sql="SELECT op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity*op.final_price) as total,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax,sum((op.final_price*op.products_tax)/100) as tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,sum(op.products_quantity) as total_quan,sum(op.final_price) as unit_subtotal,op.products_tax,sum(r.refund_amount) as refund_amount,r.refund_type,r.date_created from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_REFUNDS . " r where o.orders_id=op.orders_id and o.orders_id=r.orders_id  " . $where .  " and op.products_type='" . tep_db_input($type) . "' and o.orders_status=5 group by payment_method,op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,o.date_purchased,op.products_quantity,op.final_price,op.products_tax,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,r.orders_id,r.refund_type,r.amount_type,r.refund_amount,r.date_created order by op.products_name";
			else $total_sql="SELECT op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity*op.final_price) as total,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax,sum((op.final_price*op.products_tax)/100) as tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,sum(op.products_quantity) as total_quan,sum(op.final_price) as unit_subtotal,op.products_tax,sum(r.refund_amount) as refund_amount,r.refund_type,r.date_created from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_REFUNDS . " r where o.orders_id=op.orders_id and o.orders_id=r.orders_id  " . $where . $prd_where . " and op.products_type='" . tep_db_input($type) . "' and o.orders_status=5 group by payment_method,op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,o.date_purchased,op.products_quantity,op.final_price,op.products_tax,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,r.orders_id,r.refund_type,r.amount_type,r.refund_amount,r.date_created order by op.products_name";
			$total_query=tep_db_query($total_sql);
			while($total_result=tep_db_fetch_array($total_query)) {
				$prd_unit_price+=$total_result['unit_subtotal']+$total_result['tax']; 
				$prd_quan+=$total_result['total_quan'];
				$prd_total+=$total_result['total']+$total_result['total_tax'];
				$prd_tax+=$total_result['total_tax'];
				$refund_total+=$total_result['refund_amount'];
				$payment[get_payment_col($total_result['payment_method'],$total_result['order_status'])]=$total_result['total']+$total_result['total_tax'];
			}
			
		if(count($payment)>0)	
			ksort($payment);
		$heading=get_heading($type);
		if($sales_by_order==1 && $summary==0){
			if($tax==1){
				$result.=sprintf("\"%s\",,,,\"%s\",\"%s\",,,\"%s\",", $heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,$currencies->format($prd_total),$currencies->format($prd_tax),$currencies->format($refund_total)); 
			}else{
				$result.=sprintf("\"%s\",,,,\"%s\",,,\"%s\",", $heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,$currencies->format($prd_total),$currencies->format($refund_total)); 
			}
		}else if($summary==1 && $sales_by_order==0){
			if($tax==1){
				$result.=sprintf("\"%s\",,,,\"%s\",\"%s\",,,\"%s\",", $heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,$currencies->format($prd_total),$currencies->format($prd_tax),$currencies->format($refund_total)); 
			}else{
				$result.=sprintf("\"%s\",,,,\"%s\",,,\"%s\",", $heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,$currencies->format($prd_total),$currencies->format($refund_total)); 
			}
		}else if($summary==1 && $sales_by_order==1){
			if($tax==1){
				$result.=sprintf("\"%s\",,,,,\"%s\",\"%s\",,,\"%s\",", $heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,$currencies->format($prd_total),$currencies->format($prd_tax),$currencies->format($refund_total)); 
			}else{
				$result.=sprintf("\"%s\",,,,,\"%s\",,,\"%s\",", $heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,$currencies->format($prd_total),$currencies->format($refund_total)); 
			}
		}else if($sales_by_order==0){
			if($tax==1){
				$result.=sprintf("\"%s\",,,\"%s\",\"%s\",,,\"%s\",", $heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,$currencies->format($prd_total),$currencies->format($prd_tax),$currencies->format($refund_total)); 
			}else{
				$result.=sprintf("\"%s\",,,\"%s\",,,\"%s\",", $heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,$currencies->format($prd_total),$currencies->format($refund_total)); 
			}
		}else if($summary==1){
			if($tax==1){
			if($sales_by_order==1){ 
				$result.=sprintf("\"%s\",,,,\"%s\",\"%s\",,,\"%s\",",$heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,$currencies->format($prd_total),$currencies->format($prd_tax),$currencies->format($refund_total)); 
			}else{	
				$result.=sprintf("\"%s\",,,,\"%s\",\"%s\",,,\"%s\",",$heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,$currencies->format($prd_total),$currencies->format($prd_tax),$currencies->format($refund_total)); 
			  }
			}else{
			if($sales_by_order==1){
				$result.=sprintf("\"%s\",,,,\"%s\",\"%s\",,,",$heading . ' ' . ($prt=='prt'?TEXT_TOTAL:''),$currencies->format($prd_total),$currencies->format($refund_total)); 
			}else {
				$result.=sprintf("\"%s\",,,,\"%s\",\"%s\",,,",$heading . ' ' . ($prt=='prt'?TEXT_TOTAL:''),$currencies->format($prd_total),$currencies->format($refund_total)); 
			 }
			}
		}else{
			if($tax==1){
				$result.=sprintf("\"%s\",,,\"%s\",\"%s\",,,\"%s\",",$heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,$currencies->format($prd_total),$currencies->format($prd_tax),$currencies->format($refund_total)); 
			}else{
				$result.=sprintf("\"%s\",,,\"%s\",\"%s\",,,",$heading . ' ' . ($prt=='prt'?TEXT_TOTAL:''),$currencies->format($prd_total),$currencies->format($refund_total)); 
			}
		}
	
		 for($icnt=0;$icnt<count($payment_list);$icnt++) { 
			if($payment_list[$icnt]['on']>0) {
				//$result.=sprintf("\"%s\",", $currencies->format($payment[$icnt]));
			} 
		}
		$grand_unit_price+=$prd_unit_price;
		$grand_quan+=$prd_quan;
		$grand_total+=$prd_total;
		if($tax==1)$grand_total_tax+=$prd_tax;
		$grand_total_refund+=$refund_total;
		$grand_payment_array=array();
		for($icnt=0;$icnt<count($payment_list);$icnt++) { 
			if($payment_list[$icnt]['on']>0) { 
				$grand_payment_array[$icnt]=$payment[$icnt];
				$grand_pay_array[$icnt][]=$payment[$icnt];
			}
		}
	
	}
	elseif($prt=='prt_grand_total')
	{
		if($sales_by_order==1 && $summary==0){
			if($tax==1){
				$result.=sprintf("\"%s\",,,,\"%s\",\"%s\",,,\"%s\",", 'Grand Total' ,$currencies->format($grand_total),$currencies->format($grand_total_tax),$currencies->format($grand_total_refund)); 
			}else{
				$result.=sprintf("\"%s\",,,,\"%s\",,,\"%s\",", 'Grand Total' ,$currencies->format($grand_total),$currencies->format($grand_total_refund)); 
			}
		}else if($summary=='1' && $sales_by_order==0){
			if($tax==1){
				$result.=sprintf("\"%s\",,,,\"%s\",\"%s\",,,\"%s\",", 'Grand Total' ,$currencies->format($grand_total),$currencies->format($grand_total_tax),$currencies->format($grand_total_refund)); 
			}else{
				$result.=sprintf("\"%s\",,,,\"%s\",,,\"%s\",", 'Grand Total' ,$currencies->format($grand_total),$currencies->format($grand_total_refund)); 
			}
		}else if($summary==1 && $sales_by_order==1){
			if($tax==1){
				$result.=sprintf("\"%s\",,,,,\"%s\",\"%s\",,,\"%s\",", 'Grand Total' ,$currencies->format($grand_total),$currencies->format($grand_total_tax),$currencies->format($grand_total_refund)); 
			}else{
				$result.=sprintf("\"%s\",,,,,\"%s\",,,\"%s\",", 'Grand Total' ,$currencies->format($grand_total),$currencies->format($grand_total_refund)); 
			}
		}else if($sales_by_order==0){
			if($tax==1){
				$result.=sprintf("\"%s\",,,\"%s\",\"%s\",,,\"%s\",", 'Grand Total' ,$currencies->format($grand_total),$currencies->format($grand_total_tax),$currencies->format($grand_total_refund)); 
			}else{
				$result.=sprintf("\"%s\",,,\"%s\",,,\"%s\",", 'Grand Total' ,$currencies->format($grand_total),$currencies->format($grand_total_refund)); 
			}
		}else if($summary==1){
			if($tax==1){
			if($sales_by_order==1){ 
				$result.=sprintf("\"%s\",,,\"%s\",\"%s\",,,\"%s\",",'Grand Total' ,$currencies->format($grand_total),$currencies->format($grand_total_tax),$currencies->format($grand_total_refund)); 
			}else{	
				$result.=sprintf("\"%s\",,,\"%s\",\"%s\",,,\"%s\",",'Grand Total' ,$currencies->format($grand_total),$currencies->format($grand_total_tax),$currencies->format($grand_total_refund)); 
			  }
			}else{
			if($sales_by_order==1){
				$result.=sprintf("\"%s\",,,\"%s\",\"%s\",,,",'Grand Total',$currencies->format($grand_total),$currencies->format($grand_total_refund)); 
			}else {
				$result.=sprintf("\"%s\",,,\"%s\",\"%s\",,,",'Grand Total',$currencies->format($grand_total),$currencies->format($grand_total_refund)); 
			 }
			}
		}else{
			if($tax==1){
				$result.=sprintf("\"%s\",,,\"%s\",\"%s\",,,\"%s\",",'Grand Total' ,$currencies->format($grand_total),$currencies->format($grand_total_tax),$currencies->format($grand_total_refund)); 
			}else{
				$result.=sprintf("\"%s\",,,\"%s\",\"%s\",,,",'Grand Total',$currencies->format($grand_total),$currencies->format($grand_total_refund)); 
			}
		}
	}
	$unit_subtotal=0;
	$all_subtotal=0;
	$quan_subtotal=0;
	$unit_tax=0;
	$all_tax=0;
	$all_refundtotal=0;
	return $result;

}
function print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_refundtotal,$table,$widths,$type,$prt="",$unit_tax,$all_tax=""){ 
	global $grand_unit_price,$grand_total,$grand_quan,$grand_total_tax,$grand_total_refund,$grand_pay_array,$payment_list,$payment_count,$currencies, $where, $prd_where,$tax,$sales_by_order; 
	$payment=array();

	if($prt=="prt" || $prt==""){
		if($type=='H'){
			$cols[]=array("text"=>TEXT_SUBTOTAL,"width"=>$widths[5]+$widths[1]+$widths[2]+$widths[4],"align"=>"R","style"=>"headrow","valign"=>"M");
			$cols[]=array("text"=>$quan_subtotal,"width"=>$widths[5],"align"=>"R","style"=>"headrow","valign"=>"M");
			$cols[]=array("text"=>$currencies->format($unit_subtotal),"width"=>$widths[4],"align"=>"R","style"=>"headrow","valign"=>"M");
			$table->OutputRow($cols,20);
			unset($cols);
			return;
		}
		$cols[]=array("text"=>TEXT_SUBTOTAL,"width"=>$widths[0]+$widths[1]+$widths[2],"align"=>"R","style"=>"subrow","valign"=>"M");
		if($sales_by_order==0){
			$cols[]=array("text"=>$currencies->format($unit_subtotal),"width"=>$widths[4],"align"=>"R","style"=>"subrow","valign"=>"M");
			$cols[]=array("text"=>$quan_subtotal,"width"=>$widths[5],"align"=>"R","style"=>"subrow","valign"=>"M");
		}else{
			$cols[]=array("text"=>'',"width"=>$widths[7],"align"=>"R","style"=>"subrow","valign"=>"M");
			$cols[]=array("text"=>'',"width"=>$widths[7],"align"=>"R","style"=>"subrow","valign"=>"M");
		}
		$cols[]=array("text"=>$currencies->format($all_subtotal),"width"=>$widths[4],"align"=>"R","style"=>"subrow","valign"=>"M");
		if($tax) $cols[]=array("text"=>$currencies->format($unit_tax),"width"=>$widths[10],"align"=>"R","style"=>"subrow","valign"=>"M");
		$cols[]=array("text"=>'',"width"=>$widths[6],"align"=>"R","style"=>"subrow","valign"=>"M");
		if($sales_by_order==0){
			$cols[]=array("text"=>'',"width"=>$widths[8],"align"=>"R","style"=>"subrow","valign"=>"M");
			$cols[]=array("text"=>$currencies->format($all_refundtotal),"width"=>$widths[9],"align"=>"R","style"=>"subrow","valign"=>"M");
		}if($sales_by_order==1){
			$cols[]=array("text"=>$currencies->format($all_refundtotal),"width"=>$widths[8]+$widths[9],"align"=>"R","style"=>"subrow","valign"=>"M");
		}
	
		$table->OutputRow($cols,15);
		unset($cols);
	}
	if($prt=="prt" || $prt=="prt_type_total" && $type!='H') {
		unset($cols);
		if($sales_by_order==1) 	{
			$total_sql="SELECT op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity*op.final_price) as total,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax,sum((op.final_price*op.products_tax)/100) as tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,op.products_tax,sum(op.final_price) as unit_price,sum(op.products_quantity) as quan,r.orders_id,r.refund_type,r.amount_type,sum(r.refund_amount) as refund_amount ,r.date_created from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_REFUNDS . " r where o.orders_id=op.orders_id and o.orders_id=r.orders_id  " . $where . " and op.products_type='" . tep_db_input($type) . "' and o.orders_status=5 group by payment_method,op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,o.date_purchased,op.products_quantity,op.final_price,op.products_tax,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,r.orders_id,r.refund_type,r.amount_type,r.refund_amount,r.date_created order by op.products_name";
		}else{ $total_sql="SELECT op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity*op.final_price) as total,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax,sum((op.final_price*op.products_tax)/100) as tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,op.products_tax,sum(op.final_price) as unit_price,sum(op.products_quantity) as quan,r.orders_id,r.refund_type,r.amount_type,sum(r.refund_amount) as refund_amount,r.date_created from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_REFUNDS . " r where o.orders_id=op.orders_id and o.orders_id=r.orders_id  " . $where . $prd_where . " and op.products_type='" . tep_db_input($type) . "' and o.orders_status=5 group by payment_method,op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,o.date_purchased,op.products_quantity,op.final_price,op.products_tax,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,r.orders_id,r.refund_type,r.amount_type,r.refund_amount,r.date_created order by op.products_name";
		}
				$total_query=tep_db_query($total_sql);
				while($total_result=tep_db_fetch_array($total_query)) {
					$prd_unit_subtotal+=$total_result['unit_price']+$total_result['tax'];
					$prd_quan_subtotal+=$total_result['quan'];
					$prd_total+=$total_result['total']+$total_result['total_tax'];
					$prd_tax+=$total_result['total_tax'];
					$refund_total+=$total_result['refund_amount'];
					$payment[get_payment_col($total_result['payment_method'],$total_result['order_status'])]=$total_result['total']+$total_result['total_tax'];
				}
				$heading=get_heading($type);
				
		if($sales_by_order==1){
			$cols[]=array("text"=>$heading . ($prt=='prt'?' Total':''),"width"=>$widths[0]+$widths[1]+$widths[2]+$widths[7],"align"=>"L","style"=>"headrow");
		}
		else{ 
			$cols[]=array("text"=>$heading . ($prt=='prt'?' Total':''),"width"=>$widths[0]+$widths[1]+$widths[2],"align"=>"L","style"=>"headrow");
		}
		if($sales_by_order==1){
			$cols[]=array("text"=>'',"width"=>$widths[2],"align"=>"R","style"=>"headrow");
			$cols[]=array("text"=>$currencies->format($prd_total),"width"=>$widths[6],"align"=>"R","style"=>"headrow");
			if($tax) $cols[]=array("text"=>$currencies->format($prd_tax),"width"=>$widths[10],"align"=>"R","style"=>"headrow");
			$cols[]=array("text"=>$currencies->format($refund_total),"width"=>$widths[7]+$widths[8]+$widths[9],"align"=>"R","style"=>"headrow");
		}else {
			$cols[]=array("text"=>$currencies->format($prd_unit_subtotal),"width"=>$widths[4],"align"=>"R","style"=>"headrow");
			$cols[]=array("text"=>$prd_quan_subtotal,"width"=>$widths[5],"align"=>"R","style"=>"headrow");
			$cols[]=array("text"=>$currencies->format($prd_total),"width"=>$widths[6],"align"=>"R","style"=>"headrow");
			if($tax) $cols[]=array("text"=>$currencies->format($prd_tax),"width"=>$widths[10],"align"=>"R","style"=>"headrow");
			$cols[]=array("text"=>$currencies->format($refund_total),"width"=>$widths[7]+$widths[8]+$widths[9],"align"=>"R","style"=>"headrow");
		} 
		
		$table->OutputRow($cols,20);
		$grand_unit_price+=$prd_unit_subtotal;
		$grand_quan+=$prd_quan_subtotal;
		$grand_total+=$prd_total;
		$grand_total_refund+=$refund_total;
		if($tax==1)$grand_total_tax+=$prd_tax;
		$grand_payment_array=array();
		for($icnt=0;$icnt<count($payment_list);$icnt++) { 
			if($payment_list[$icnt]['on']>0) { 
				$grand_payment_array[$icnt]=$payment[$icnt];
				$grand_pay_array[$icnt][]=$payment[$icnt];
			}
		}
	}
	elseif($prt=='prt_grand_total')
	{
		if($sales_by_order==1){
			$cols[]=array("text"=>'Grand Total',"width"=>$widths[0]+$widths[1]+$widths[2]+$widths[7],"align"=>"L","style"=>"headrow");
		}else{ 
			$cols[]=array("text"=>'Grand Total',"width"=>$widths[0]+$widths[1]+$widths[2],"align"=>"L","style"=>"headrow");
			$cols[]=array("text"=>$currencies->format($grand_unit_price),"width"=>$widths[4],"align"=>"R","style"=>"headrow");
			$cols[]=array("text"=>$grand_quan,"width"=>$widths[5],"align"=>"R","style"=>"headrow");
		}
		if($sales_by_order==1){
			$cols[]=array("text"=>'',"width"=>$widths[2],"align"=>"R","style"=>"headrow");
			$cols[]=array("text"=>$currencies->format($grand_total),"width"=>$widths[6],"align"=>"R","style"=>"headrow");
			if($tax) $cols[]=array("text"=>$currencies->format($grand_total_tax),"width"=>$widths[10],"align"=>"R","style"=>"headrow");
			$cols[]=array("text"=>$currencies->format($grand_total_refund),"width"=>$widths[7]+$widths[8]+$widths[9],"align"=>"R","style"=>"headrow");
		}else{
			$cols[]=array("text"=>$currencies->format($grand_total),"width"=>$widths[6],"align"=>"R","style"=>"headrow");
			if($tax) $cols[]=array("text"=>$currencies->format($grand_total_tax),"width"=>$widths[10],"align"=>"R","style"=>"headrow");
			$cols[]=array("text"=>$currencies->format($grand_total_refund),"width"=>$widths[7]+$widths[8]+$widths[9],"align"=>"R","style"=>"headrow");
		}
		$table->OutputRow($cols,20);
	}
	$cols[]=array("text"=>'',"width"=>$table->width,"align"=>"R","style"=>"subrow","valign"=>"M");
	unset($cols);
	$cols[]=array("text"=>'',"width"=>$table->width,"align"=>"R","style"=>"subrow","valign"=>"M");
	$table->OutputRow($cols,20);
	unset($cols);
	$unit_subtotal=0;
	$all_subtotal=0;
	$quan_subtotal=0;
	$unit_tax=0;
	$all_tax=0;
	$all_refundtotal=0;
	 
}

	// function to generate pdf content
	function generate_pdf(){
		global $display_header,$display_array,$type,$found_results,$prd_total_array,$details,$payment_count,$currencies,$report_filename,$payment_list,$summary,$payment_col_count,$tax,$sales_by_order;
		//create pdf table class
		
		$table=new pdfTable("A4","l");
		
		//set margins
		$table->left_margin=20;
		$table->top_margin=20;
		$table->right_margin=5;
		$table->bottom_margin=20;
		$table->pdfInit();
		if(!$tax) $tax=0;
		// create styles for output content
		$table->AddStyle("heading","color:#727272;bgcolor:#ffffff;font:DejaVu;size:18;style:B");
		$table->AddStyle("user","color:#000000;bgcolor:#f1f9fe;font:DejaVu;size:14;style:B;border-color:#7b9ebd");
		$table->AddStyle("subhead","color:#000000;bgcolor:#7b9ebd;font:DejaVu;size:10;style:B");
		$table->AddStyle("headrow","color:#000000;bgcolor:#C9C9C9;font:DejaVu;size:10;style:B");
		$table->AddStyle("headrow1","color:#000000;bgcolor:#C9C9C9;font:DejaVu;size:9;style:B");
		$table->AddStyle("row","color:#000000;bgcolor:#F0F1F1;font:DejaVu;size:10");
		$table->AddStyle("query","color:#000000;bgcolor:#FFFFFF;font:DejaVu;size:11");
		$table->AddStyle("subrow","color:#000000;bgcolor:#FFFFFF;font:DejaVu;size:10");
		$table->AddStyle("subrowhead","color:#000000;bgcolor:#FFFFFF;font:DejaVu;size:10;style:B");

		// add headers
		$table->headers["text"]=$display_header;
		$table->headers["style"]="query";
		$table->headers["height"]=12;
		$table->headers["width"]="100%";
		$table->headers["cols"]=3;

		// generate coloumn widths
		
		// generate table header columns
		if($type!='H'){ // not shipping
			$widths[0]=40;
			$widths[2]=90;
			$widths[3]=90;
			$widths[4]=90;
			$widths[5]=90;
			$widths[6]=90;
			$widths[7]=90;
			$widths[8]=90;
			$widths[9]=90;
			$wd=$widths[6];
			if($tax){
				$widths[10]=90;
				$wd+=$widths[10];
			}
			//$payment_col_width=60;
			//$payment_width=$payment_col_count*$payment_col_width;
			//if($sales_by_order==1){
				//$temp_width=$table->width-($widths[0]+$widths[2]+$widths[3]+$widths[4]+$widths[5]+$widths[7]+$widths[8]+$widths[9]+$wd);
			//}else{
				$temp_width=$table->width-($widths[0]+$widths[2]+$widths[4]+$widths[5]+$widths[7]+$widths[8]+$widths[9]+$wd);
			//}
			$widths[1]=$temp_width;
			$cols=array();

			$cols[]=array("text"=>TEXT_INDEX,"width"=>$widths[0],"align"=>"L","style"=>"headrow","valign"=>"M");
			$cols[]=array("text"=>TEXT_CLIENT,"width"=>$widths[1],"align"=>"L","style"=>"headrow","valign"=>"M");
			if($sales_by_order==1){
				$cols[]=array("text"=>TEXT_ORDER_ID,"width"=>$widths[2],"align"=>"R","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>'',"width"=>$widths[2],"align"=>"L","style"=>"headrow","valign"=>"M");
			}
			$cols[]=array("text"=>TEXT_SALE_DATE,"width"=>$widths[2],"align"=>"R","style"=>"headrow","valign"=>"M");
			if($sales_by_order==0){
				$cols[]=array("text"=>TEXT_UNIT_PRICE,"width"=>$widths[4],"align"=>"R","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>TEXT_SOLD,"width"=>$widths[5],"align"=>"R","style"=>"headrow","valign"=>"M");
			}
			$cols[]=array("text"=>TEXT_TOTAL,"width"=>$widths[6],"align"=>"R","style"=>"headrow","valign"=>"M");
			if($tax)$cols[]=array("text"=>TEXT_TAX,"width"=>$widths[10],"align"=>"R","style"=>"headrow","valign"=>"M");
			$cols[]=array("text"=>TEXT_REFUND_DATE,"width"=>$widths[7],"align"=>"R","style"=>"headrow","valign"=>"M");
			$cols[]=array("text"=>TEXT_REFUND_TYPE,"width"=>$widths[8],"align"=>"R","style"=>"headrow","valign"=>"M");
			$cols[]=array("text"=>TEXT_REFUND_AMOUNT,"width"=>$widths[9],"align"=>"R","style"=>"headrow","valign"=>"M");		
		}else{
			$widths[0]=40;
			$widths[1]=80;
			$widths[2]=80;
			$widths[3]=80;
			$widths[5]=50;
			$widths[6]=50;
			$widths[4]=$table->width-($widths[0]+$widths[2]+$widths[3]+$widths[1]+$widths[5]+$widths[6]);
			$cols=array();

			$cols[]=array("text"=>TEXT_INDEX,"width"=>$widths[0],"align"=>"L","style"=>"headrow","valign"=>"M");
			$cols[]=array("text"=>TEXT_CUSTOMER_NAME,"width"=>$widths[1],"align"=>"L","style"=>"headrow","valign"=>"M");
			$cols[]=array("text"=>TEXT_ORDER_ID,"width"=>$widths[2],"align"=>"L","style"=>"headrow","valign"=>"M");
			$cols[]=array("text"=>TEXT_LOCATION,"width"=>$widths[3],"align"=>"L","style"=>"headrow","valign"=>"M");
			$cols[]=array("text"=>TEXT_SHIPPING_METHOD,"width"=>$widths[4],"align"=>"L","style"=>"headrow","valign"=>"M");
			$cols[]=array("text"=>TEXT_WEIGHT,"width"=>$widths[5],"align"=>"R","style"=>"headrow","valign"=>"M");
			$cols[]=array("text"=>TEXT_COST,"width"=>$widths[6],"align"=>"R","style"=>"headrow","valign"=>"M");
		}
		$table->tableheaders["text"]=$cols;
		unset($cols);
		if ($found_results) {
		reset($details);
		$prev_product_id=-1;
		$prev_products_name="";
		$prev_attribute_id='-1';
		$row_cnt=1;
		$all_tax=0;
		$orders_id=0;
		$display_orders=0;
		for($disp_cnt=0;$disp_cnt<count($display_array);$disp_cnt++)
		{
			if($display_array[$disp_cnt]==$type)
			{
			$cols[]=array("text"=>get_heading($display_array[$disp_cnt]),"width"=>$table->width,"align"=>"L","style"=>"headrow","valign"=>"M");
			$table->OutputRow($cols,20);
			unset($cols);
			//while(list($key,)=each($details)){
			//foreach($details as $key => $value) 
			foreach (array_keys($details) as $key)
			{			
				$row=&$details[$key];
				$splt_key=preg_split("/-/",$key);
				//print_r($splt_key);
				if (count($splt_key)>1){
					if ($splt_key[0]!=$prev_product_id && $sales_by_order==0 ){
						if ($unit_subtotal>0) print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_refundtotal,$table,$widths,$type,'',$unit_tax,'');
						$cols[]=array("text"=>$row["name1"],"width"=>$table->width,"align"=>"L","style"=>"headrow","valign"=>"M");
						$table->OutputRow($cols,20);
						unset($cols);
						$prev_attribute_id=-1;
						$prev_product_id=$splt_key[0];
						$unit_subtotal=0;
						$all_subtotal=0;
						$unit_tax=0;
						$quan_subtotal=0;
						$all_refundtotal=0;
					}
					if ($key!=$prev_attribute_id && $sales_by_order==0){
						if ($unit_subtotal>0) print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_refundtotal,$table,$widths,$type,'',$unit_tax,'');
						$cols[]=array("text"=>'   ' .$row["name"],"width"=>$table->width,"align"=>"L","style"=>"headrow1","valign"=>"M");
						$table->OutputRow($cols,20);
						unset($cols);
						$prev_attribute_id=$key;
						$unit_subtotal=0;
						$all_subtotal=0;
						$unit_tax=0;
						$quan_subtotal=0;
						$all_refundtotal=0;
					}
				} else {
					if (($splt_key[0]!=$prev_product_id || $prev_attribute_id!=-1) && $sales_by_order==0){
						if ($unit_subtotal>0 && $row["name"]!=$prev_products_name) print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_refundtotal,$table,$widths,$type,'',$unit_tax,'');
						if ($splt_key[0]!=$prev_product_id && $row["name"]!=$prev_products_name) {
							$cols[]=array("text"=>$row["name"],"width"=>$table->width,"align"=>"L","style"=>"headrow","valign"=>"M");
							$table->OutputRow($cols,20);
							unset($cols);
							$all_subtotal=0;
							$unit_subtotal=0;
							$unit_tax=0;
							$quan_subtotal=0;
							$all_refundtotal=0;
						}
						$prev_product_id=$splt_key[0];
						//echo $prev_product_id.'<br>';
						$prev_products_name = $row["name"];
						$prev_attribute_id=-1;
						
					}
				} // $splt_key
				
				
				$contents=&$row["contents"];
				for ($icnt=0,$n=count($contents);$icnt<$n;$icnt++){
					$content=&$contents[$icnt];
					if (!$summary){
						if($type!='H'){
							$cols[]=array("text"=>$row_cnt,"width"=>$widths[0],"align"=>"L","style"=>"row","valign"=>"M");
							$cols[]=array("text"=>$content["cname"],"width"=>$widths[1],"align"=>"L","style"=>"row","valign"=>"M");
							if($sales_by_order==1){
								$cols[]=array("text"=>$content["orders_id"],"width"=>$widths[2],"align"=>"R","style"=>"row","valign"=>"M");
								$cols[]=array("text"=>'',"width"=>$widths[2],"align"=>"R","style"=>"row","valign"=>"M");
								if(!$summary)$cols[]=array("text"=>$content["date"],"width"=>$widths[2],"align"=>"R","style"=>"row","valign"=>"M");
							}
							else if(!$summary)$cols[]=array("text"=>$content["date"],"width"=>$widths[2],"align"=>"R","style"=>"row","valign"=>"M");
							if($sales_by_order==0){
								$cols[]=array("text"=>$currencies->format($content["uprice"]+$content["tax"]),"width"=>$widths[4],"align"=>"R","style"=>"row","valign"=>"M");
								$cols[]=array("text"=>$content["quan"],"width"=>$widths[5],"align"=>"R","style"=>"row","valign"=>"M");
							}
							$cols[]=array("text"=>$currencies->format($content["tprice"]+$content["ptax"]),"width"=>$widths[6],"align"=>"R","style"=>"row","valign"=>"M");
							if($tax) $cols[]=array("text"=>$currencies->format($content["ptax"]),"width"=>$widths[10],"align"=>"R","style"=>"row","valign"=>"M"); 
							if($content["refund_date"] == '')$content["refund_date"] = '-';
							$cols[]=array("text"=>$content["refund_date"],"width"=>$widths[7],"align"=>"R","style"=>"row","valign"=>"M");
							if(strtolower($content["refund_type"]) == 'p')$content["refund_type"] = 'Partial';
							else if(strtolower($content["refund_type"]) == 'f')$content["refund_type"] = 'Full';
							else $content["refund_type"] = '-';
							$cols[]=array("text"=>$content["refund_type"],"width"=>$widths[8],"align"=>"R","style"=>"row","valign"=>"M");
							$cols[]=array("text"=>$currencies->format($content["refund_amount"]),"width"=>$widths[9],"align"=>"R","style"=>"row","valign"=>"M");
						}else{
							$cols[]=array("text"=>$row_cnt,"width"=>$widths[0],"align"=>"L","style"=>"row","valign"=>"M");
							$cols[]=array("text"=>$content["cname"],"width"=>$widths[1],"align"=>"L","style"=>"row","valign"=>"M");
							$cols[]=array("text"=>$content["orders_id"],"width"=>$widths[2],"align"=>"L","style"=>"row","valign"=>"M");
							$cols[]=array("text"=>$content["location"],"width"=>$widths[3],"align"=>"L","style"=>"row","valign"=>"M");
							$ship_method=preg_split('/-/',$content["shipping_method"]);
							$cols[]=array("text"=>$ship_method[0],"width"=>$widths[4],"align"=>"L","style"=>"row","valign"=>"M");
							$cols[]=array("text"=>$content["weight"],"width"=>$widths[5],"align"=>"R","style"=>"row","valign"=>"M");	
							$cols[]=array("text"=>$currencies->format($content["cost"]),"width"=>$widths[6],"align"=>"R","style"=>"row","valign"=>"M");
						}
						$row_cnt++;
						$table->OutputRow($cols,20);
						unset($cols);
					}   else if ($summary){
						for ($jcnt=0;$jcnt<$payment_count;$jcnt++){
							if ($payment_list[$jcnt]["on"]==0) continue;
							if ($jcnt==$content["col_pos"]){
								$payment_list[$content["col_pos"]]["subtotal"]+=$content["tprice"]+$content["ptax"];
							} 
						} // $jcnt
	
						$row_cnt++;
					//	$table->OutputRow($cols,20);
					//	unset($cols);
					}   
					$unit_subtotal+=$content["uprice"]+$content["tax"];
					$all_subtotal+=$content["tprice"]+$content["ptax"];
					$all_refundtotal+=$content["refund_amount"];
					if($type!='H')
						$quan_subtotal+=$content["quan"];
					else
						$quan_subtotal+=$content["weight"];
					$unit_tax+=$content["ptax"]; 
					$all_tax+=$content["ptax"];
									
					if($sales_by_order==1){
						if($display_orders==22 && $summary==0){
							print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_refundtotal,$table,$widths,$type,'',$unit_tax,'');
							$display_orders=0;
						}
						$display_orders++;
					}
				} // $icnt
			} // $found_results
			if ($unit_subtotal>0) {
				 print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_refundtotal,$table,$widths,$type,'prt',$unit_tax,$all_tax); 
				 unset($cols);
			}
		}
		else
		{
			 print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_refundtotal,$table,$widths,$display_array[$disp_cnt],'prt_type_total',$unit_tax,$all_tax);
			unset($cols);
		}
	  }//for end
	 print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_refundtotal,$table,$widths,'','prt_grand_total',$unit_tax,$all_tax);
	} else {
		$cols[]=array("text"=>REPORT_NO_RESULTS,"width"=>"100%","align"=>"C","style"=>"subrow","valign"=>"M");
		$table->OutputRow($cols,15);
		unset($cols);
	}
	// output pdf file
	$table->Render($report_filename .".pdf",'F');
}

//function to get excel format csv datas
function generate_excel(){
	global $display_array,$type,$details,$filename,$payment_list,$payment_count,$found_results,$report_filename,$currencies,$summary,$payment_col_count,$tax,$sales_by_order;
	if($type!='H')
	{
		if($sales_by_order==1 && $summary==0){
			if($tax){
				$res=sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s",TEXT_INDEX,TEXT_CLIENT,TEXT_ORDER_ID,TEXT_SALE_DATE,TEXT_TOTAL,TEXT_TAX,TEXT_REFUND_DATE,TEXT_REFUND_TYPE,TEXT_REFUND_AMOUNT);	
			}else if(!$tax) {
				$res=sprintf("%s,%s,%s,%s,%s,%s,%s,%s",TEXT_INDEX,TEXT_CLIENT,TEXT_ORDER_ID,TEXT_SALE_DATE,TEXT_TOTAL,TEXT_REFUND_DATE,TEXT_REFUND_TYPE,TEXT_REFUND_AMOUNT);
			}
		}else if($sales_by_order==0 && $summary==1){
			if($tax){
				$res=sprintf("%s,,,,%s,%s,,,%s",TEXT_INDEX,TEXT_TOTAL,TEXT_TAX,TEXT_REFUND_AMOUNT);	
			}else if(!$tax) {
				$res=sprintf("%s,,,,%s,,,%s",TEXT_INDEX,TEXT_TOTAL,TEXT_REFUND_AMOUNT);
			}
		}else if($sales_by_order==1 && $summary==1){
			if($tax){
				$res=sprintf("%s,,,,,%s,%s,,,%s",TEXT_INDEX,TEXT_TOTAL,TEXT_TAX,TEXT_REFUND_AMOUNT);	
			}else if(!$tax) {
				$res=sprintf("%s,,,,,%s,,,%s",TEXT_INDEX,TEXT_TOTAL,TEXT_REFUND_AMOUNT);
			}
		}else{
			if($tax){
				$res=sprintf("%s,%s,%s,%s,%s,%s,%s,%s",TEXT_INDEX,TEXT_CLIENT,TEXT_SALE_DATE,TEXT_TOTAL,TEXT_TAX,TEXT_REFUND_DATE,TEXT_REFUND_TYPE,TEXT_REFUND_AMOUNT);	
			}else if(!$tax) {
				$res=sprintf("%s,%s,%s,%s,%s,%s,%s",TEXT_INDEX,TEXT_CLIENT,TEXT_SALE_DATE,TEXT_TOTAL,TEXT_REFUND_DATE,TEXT_REFUND_TYPE,TEXT_REFUND_AMOUNT);
			}
		}
		$result.=$res;
	}
	
	$result.="\n";

	if ($found_results) {
		reset($details);
		$prev_product_id=-1;
		$prev_products_name = "";
		$prev_attribute_id='-1';
		$row_cnt=1;
		//$unit_subtotal=0;
		$unit_total=0;
		$all_subtotal=0;
		$all_total=0;
		$unit_tax=0;
		$all_tax=0;
		$all_refundtotal;
		for($disp_cnt=0;$disp_cnt<count($display_array);$disp_cnt++)
		{
			if($display_array[$disp_cnt]==$type)
			{
			$result.="\n";
			$result.= '"' . get_heading($display_array[$disp_cnt]) . "\"\n";   
			//while(list($key,)=each($details)){
			foreach (array_keys($details) as $key)
			{
				$row=&$details[$key];
				$splt_key=preg_split("/-/",$key);
				if (count($splt_key)>1){   
					if ($splt_key[0]!=$prev_product_id && $sales_by_order==0){
						if ($unit_subtotal>0) $result.=print_total_row_excel($unit_subtotal,$all_subtotal,$quan_subtotal,$all_refundtotal,'',$type,$unit_tax,'');
						$result.= '"' . $row["name1"] . "\"\n";    
						$prev_attribute_id=-1;      
						$prev_product_id=$splt_key[0]; 
						$unit_subtotal=0;
						$all_subtotal=0;
						$unit_tax=0;
						$quan_subtotal=0;
						$all_refundtotal=0;
					}  
					if ($key!=$prev_attribute_id && $sales_by_order==0){  
						if ($unit_subtotal>0) $result.=print_total_row_excel($unit_subtotal,$all_subtotal,$quan_subtotal,$all_refundtotal,'',$type,$unit_tax,'');
						$result.='"  ' . $row["name"] . "\"\n"; 
						$prev_attribute_id=$key; 
						$unit_subtotal=0;
						$all_subtotal=0;
						$unit_tax=0;
						$quan_subtotal=0;
						$all_refundtotal=0;
					} 
				} else { 
					if (($splt_key[0]!=$prev_product_id || $prev_attribute_id!=-1) && $sales_by_order==0){
						if ($unit_subtotal>0 && $row["name"]!=$prev_products_name) $result.=print_total_row_excel($unit_subtotal,$all_subtotal,$quan_subtotal,$all_refundtotal,'',$type,$unit_tax,'');
						if ($splt_key[0]!=$prev_product_id && $row["name"]!=$prev_products_name){
						 $result.='"' . $row["name"] . "\"\n";
						 $quan_subtotal=0;
						$unit_subtotal=0;
						$all_subtotal=0;
						$unit_tax=0;
						$all_refundtotal=0;
						 }
						$prev_product_id=$splt_key[0];
						$prev_products_name = $row["name"];
						$prev_attribute_id=-1;
					//	$quan_subtotal=0;
					}
				}
	
				$contents=&$row["contents"];	
			//print_r($contents);
				for ($icnt=0,$n=count($contents);$icnt<$n;$icnt++){
					$content=&$contents[$icnt];
					if($type!='H')
					{
						  if($sales_by_order==1 && !$summary){
							if($tax){
								$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"",$row_cnt,$content["cname"],$content["orders_id"],$content["date"],$currencies->format($content["tprice"]+$content["ptax"]),$currencies->format($content["ptax"]),$content["refund_date"],(($content["refund_type"]=="P")?"Partial":"Full"),$currencies->format($content["refund_amount"]));
							}else if(!$tax){
								$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"",$row_cnt,$content["cname"],$content["orders_id"],$content["date"],$currencies->format($content["tprice"]+$content["ptax"]),$content["refund_date"],(($content["refund_type"]=="P")?"Partial":"Full"),$currencies->format($content["refund_amount"]));	
							}
						}else if(!$summary){
	
							if($tax){
								$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"",$row_cnt,$content["cname"],$content["date"],$currencies->format($content["tprice"]+$content["ptax"]),$currencies->format($content["ptax"]),$content["refund_date"],(($content["refund_type"]=="P")?"Partial":"Full"),$currencies->format($content["refund_amount"]));
							}else if(!$tax){
								$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"",$row_cnt,$content["cname"],$content["date"],$currencies->format($content["tprice"]+$content["ptax"]),$content["refund_date"],(($content["refund_type"]=="P")?"Partial":"Full"),$currencies->format($content["refund_amount"]));	
							}
						}
						if (!$summary){
							
						
							$row_cnt++;
						$result.="\n";
					} else { 
						
						
						//$result.=',"'.$currencies->format($content["amount_received"]).'","'.$currencies->format($content["amount_due"]).'","'.$currencies->format($content["site_margin"]).'"';
					} 
				}
				else
				{
					$ship_method=preg_split('/-/',$content["shipping_method"]);
					$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"",$row_cnt,$content["cname"],$content["orders_id"],$content["location"],$ship_method[0],$content["weight"],$currencies->format($content["cost"]));
					$result.="\n";
					$row_cnt++;
				}
					$unit_subtotal+=$content["uprice"]+$content["tax"];
					$all_subtotal+=$content["tprice"]+$content["ptax"];
					if($type!='H')
						$quan_subtotal+=$content["quan"];
					else
						$quan_subtotal+=$content["weight"];
					$unit_tax+=$content["ptax"];
					$all_tax+=$content["ptax"];
					$all_refundtotal+=$content["refund_amount"];
				 } // $jcnt
			} // $icnt
			if ($unit_subtotal>0) $result.=print_total_row_excel($unit_subtotal,$all_subtotal,$quan_subtotal,$all_refundtotal,'prt',$type,$unit_tax,$all_tax);
		  }
		  else
		  {
		  	 $result.="\n" .print_total_row_excel($unit_subtotal,$all_subtotal,$quan_subtotal,$all_refundtotal,'prt_type_total',$display_array[$disp_cnt],$unit_tax,$all_tax);
		  }
      } //end for
       $result.="\n" . print_total_row_excel($unit_subtotal,$all_subtotal,$quan_subtotal,$all_refundtotal,'prt_grand_total','',$unit_tax,$all_tax);
	} else {
		$result.=REPORT_NO_RESULTS . "\n";
	}
	tep_write_text_file($report_filename . ".csv",$result);
}

function cmp($a, $b) 
{
	global $details;
	$atext=$details[$a]["name"] . $details[$a]["name1"];
	$btext=$details[$b]["name"] . $details[$b]["name1"];
   if ( $atext== $btext) {
       return 0;
   }
   return ($atext < $btext) ? 1 : -1;
}
function get_categories_name($product_id,$display=''){
	if(!$product_id) return;
	
	$categories_desc=tep_db_query("select c.categories_id,categories_name from ".TABLE_CATEGORIES." c LEFT JOIN ".TABLE_CATEGORIES_DESCRIPTION." cd ON(c.categories_id=cd.categories_id) LEFT JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." pTc ON(cd.categories_id=pTc.categories_id) where pTc.products_id=$product_id group by c.categories_id,categories_name");
	if(tep_db_num_rows($categories_desc)>0){
		$categories=tep_db_fetch_array($categories_desc);
		if($display=='cat_id')
			return $categories['categories_id'];
		else if($display=='')
			return $categories['categories_name'];
		
	} 
}
?>

<?php  function get_result(){
global $query_split,$type,$page,$payment_count,$payment_list,$tax,$query_split_numrows,$summary,$sales_by_order;
?> 
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<?php if(REPORT_MAX_ROWS_PAGE<$query_split_numrows){?>
			<tr>
			<td>
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr> 
						<td class="smallText" align="left">
							<?php //echo tep_get_report_params();
							echo $query_split->display_links($query_split_numrows, REPORT_MAX_ROWS_PAGE, REPORT_MAX_LINKS_PAGE, $page,tep_get_report_params()); ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	<?php } ?>
	<tr>
		<td>
			<table width="100%" cellpadding="3" cellspacing="0" border="0">
			<?php /* 
			<tr class="dataTableHeadingTitleRow">
					<td class="dataTableHeadingTitleContent" width="3%"><?php echo TEXT_INDEX ?></td>
					<?php 
					if($summary!=1)
					if($sales_by_order==0){?> 
					<td class="dataTableHeadingTitleContent" align="left" width="15%"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;".TEXT_CLIENT;?></td>
					<?php }else if($sales_by_order==1){?>
					<td class="dataTableHeadingTitleContent" align="left" width="15%"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;" . TEXT_CLIENT;?></td>
					<td class="dataTableHeadingTitleContent" align="left" width="5%"><?php  echo TEXT_ORDER_ID ;?></td>
					<?php }if($summary!=1){?>
					<td class="dataTableHeadingTitleContent"  width="10%" align="right"><?php  echo TEXT_SALE_DATE;?></td>
					<?php }else if($summary==1 && $sales_by_order==0){ ?>
					<td class="dataTableHeadingTitleContent"  align="center"></td>
					<td class="dataTableHeadingTitleContent"  align="right" colspan="2"></td>
					<?php }else if($summary==1 && $sales_by_order==1){?>
					<td class="dataTableHeadingTitleContent"  align="center"></td>
					<td class="dataTableHeadingTitleContent"  align="right"></td>
					<td class="dataTableHeadingTitleContent"  align="right" colspan="2"></td>
					<?php } ?>
					<td class="dataTableHeadingTitleContent"  align="right" width="10%"><?php echo TEXT_TOTAL;?></td>
					<?php if($tax==1){?>
					<td class="dataTableHeadingTitleContent" align="right" width="10%"><?php echo TEXT_TAX;?></td>
					<?php }?>
					<?php if($summary==1){?>
					<td class="dataTableHeadingTitleContent" align="right" width="10%"><?php echo TEXT_REFUND;?></td>
					<?php } ?>
					<?php if($sales_by_order==0){?>
					<?php if($summary!=1){?>
					<td class="dataTableHeadingTitleContent"  width="10%" align="right"><?php  echo TEXT_REFUND_DATE;?></td>
					<td class="dataTableHeadingTitleContent"  width="7%" align="right"><?php echo TEXT_REFUND_TYPE;?></td>
					<td class="dataTableHeadingTitleContent"  width="10%" align="right"><?php echo TEXT_REFUND_AMOUNT;?></td>
					<?php } 
					}if($sales_by_order==1 && $summary!=1){?>
					<td class="dataTableHeadingTitleContent"  width="10%" align="right"><?php echo TEXT_REFUND_DATE;?></td>
					<td class="dataTableHeadingTitleContent"  width="10%" align="right"><?php echo TEXT_REFUND_TYPE;?></td>
					<td class="dataTableHeadingTitleContent"  width="10%" align="right"><?php echo TEXT_REFUND_AMOUNT;?></td>
					<?php }?>
			<!--		<td class="dataTableHeadingTitleContent"  align="right" width="10%"><?php echo TEXT_TOTAL;?></td>  !-->
					<?php  
							for ($icnt=0;$icnt<$payment_count;$icnt++){
								if ($payment_list[$icnt]["on"]==0) continue;
									$name=(strlen($payment_list[$icnt]["name"])>6?substr($payment_list[$icnt]["name"],0,6):$payment_list[$icnt]["name"]);
									if(substr($payment_list[$icnt]["name"],0,11)=='Credit Card' && strpos($payment_list[$icnt]["name"],"img")>0)	$payment_list[$icnt]["name"]='Credit Cart'; 
									//echo '<td  wrap class="dataTableHeadingTitleContent" align="right" title="' . $payment_list[$icnt]["name"] .'">' .  $name . '</td>';
								//	echo '<td class="dataTableHeadingTitleContent" align="right" title= $payment_list[$icnt]["name"]   > ' . $name . '</td>';
							}
				//}
							
			 ?>
				</tr> */?>
				<tr><td colspan="15" height="5"></td></tr><tr><td width="100%" colspan="10">
				<?php  if(HIDE_FROM_BACKEND_MENU_PRODUCTS=='false') { ?>
				<?php if($type!="P") 
						getDetails('P',false);
					 else if($type=='P'){
					 	 getDetails($type,true);
					 	 echo '<div id="fetch_report_product"></div>';
					}
					if($type!='H')
						getDetails('H',false);
					else if($type=='H'){
						getDetails($type,true);
						echo '<div id="fetch_report_shipping"></div>';
					}
					
				?>
				<?php } 
				if(HIDE_FROM_BACKEND_MENU_EVENTS=='false') { ?>
					<?php 
						if($type!='E')
							getDetails('E',false);
						else if($type=='E'){
							getDetails($type,true);
							echo '<div id="fetch_report_event"></div>';
						}
					 ?>
				<?php } 
				if(HIDE_FROM_BACKEND_MENU_SUBSCRIPTIONS=='false') { ?>
					<?php 
						if($type!='S') 
							getDetails('S',false);
						else if($type=='S') {
							getDetails($type,true);
							echo '<div id="fetch_report_subscription"></div>';
						}
					 ?>
				<?php } 
				if(HIDE_FROM_BACKEND_MENU_SERVICES=='false') { ?>
					<?php  
						if($type!='V') 
							getDetails('V',false);
						else if($type=='V'){ 
							getDetails($type,true);
							echo '<div id="fetch_report_service"></div>';
						}
					 ?>
				<?php } 
				if(HIDE_FROM_BACKEND_MENU_PRODUCTS=='false' && HIDE_FROM_BACKEND_MENU_EVENTS=='false' && HIDE_FROM_BACKEND_MENU_SUBSCRIPTIONS=='false' && HIDE_FROM_BACKEND_MENU_SERVICES=='false') { ?>
						<?php get_grand_total($grand_total,$grand_total_tax,$grand_total_refund,$grand_pay_array,$grand_unit_price,$grand_quan); ?>
			</table> </td></tr></table>
<?php 	} 
 }?>
 <script language="javascript">
	function nav_page(page){
		doReport(1,page);
	}
	</script>

  <script language="javascript">
  doChange('<?php echo $btype;?>');
  </script>

<input type="hidden" name="products_type">
<?
function display_total_row_pdf($prd_type,$table,$widths) { 
	global $products_id,  $where, $prd_where, $payment_col_count, $currencies, $payment_list, $grand_total,$grand_total_tax,$grand_payment_array,$grand_pay_array,$tax,$summary,$sales_by_order,$page,$grand_quan,$grand_unit_price;

		 print_total_row_pdf(pro_total,$prd_total,$quan,$table,$widths,$prd_type,'prttot',$unit_tax,$all_tax);
 } 
?>
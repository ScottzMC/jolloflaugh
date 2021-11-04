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
	//require(DIR_WS_FUNCTIONS . 'services_tree.php');
	//require(DIR_WS_FUNCTIONS . 'events_tree.php');
	//require(DIR_WS_FUNCTIONS . 'subscription_tree.php');
	require(DIR_WS_LANGUAGES . $FSESSION->language . "/reports_sales_products.php");
	$currencies = new currencies();
	define(BOX_WIDTH1,'125');
	$btype=	$FREQUEST->getvalue('btype','string','P');
	//$btype=(isset($HTTP_GET_VARS["btype"])?$HTTP_GET_VARS["btype"]:'P');
	
	$grand_unit_price=0;
	$grand_total=0;
	$grand_total_tax=0;
	$manualPrice=0;
	$grand_payment_array=array();
 

	$date_offset=(defined('EVENTS_SERVER_DATE_OFFSET')?EVENTS_SERVER_DATE_OFFSET:0);
	
	// get initial parameters
	$return=$FREQUEST->getvalue('return');
	$rep_params=$FREQUEST->getvalue('rep_params');
	if (($return!='') && ($rep_params!='')){
		$input_params=&$FSESSION->get("rep_params");
	} else {
		//$input_params=&$HTTP_GET_VARS; 
			$input_params=&$FREQUEST->getRef("GET");
		if (isset($input_params["post_action"])){
			$FSESSION->set("rep_params",$FREQUEST->getRef("POST"));
		} else {
			$FSESSION->set("rep_params",array());
		} 
	}

	if(isset($input_params["type"])) {
		$type=$input_params["type"];
	}else {
		if(HIDE_FROM_BACKEND_MENU_PRODUCTS=='false')
			$type='P';
		else if(HIDE_FROM_BACKEND_MENU_EVENTS=='false')
			$type='E';
		else if(HIDE_FROM_BACKEND_MENU_SUBSCRIPTIONS=='false')
			$type='S';	
		else if(HIDE_FROM_BACKEND_MENU_SERVICES=='false')
			$type='V';	
	}

	//ajax start
	//	$input_params=&$HTTP_GET_VARS;
//	$command=isset($HTTP_GET_VARS['command'])?$HTTP_GET_VARS['command']:'';
	$command=$FREQUEST->getvalue('command');
	$start_date = (isset($input_params['start_date'])?tep_convert_date_raw($input_params['start_date']):'');
	$end_date = (isset($input_params['end_date'])?tep_convert_date_raw($input_params['end_date']):getServerDate());
	$start_time = (isset($input_params['stTime'])?($input_params['stTime']):'');
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
	if($type=='H'){
		$sales_by_order=1;
	}
	$temp_type=$type;
	if($sales_by_order==1){
		$subscription_id = -1;
		$event_id = -1;
		$service_id = -1;
		$product_id = -1;
	}
	
	if($start_date=="") {
		$res = tep_db_query("SELECT '".$end_date."' - INTERVAL 1 MONTH");
		$row = tep_db_fetch_array($res);
		$start_date = $row[0];
	}
	$display_header="";
	$display_header.=TEXT_START_DATE .  ":&nbsp;&nbsp;" . format_date($start_date) . "&nbsp;&nbsp;&nbsp;&nbsp;";
	$display_header.=TEXT_END_DATE . ":&nbsp;&nbsp;" . format_date($end_date) . "&nbsp;&nbsp;&nbsp;&nbsp;";
	$display_header.=TEXT_BUSINESS_DAY_START_TIME .  ":&nbsp;&nbsp;" . $start_time . "&nbsp;&nbsp;&nbsp;&nbsp;";
	
	if($sales_by_order==0){
		if($type=='P' && $product_id=="-1") $display_header.=TEXT_PRODUCT . ":&nbsp;&nbsp;" . TEXT_ALL_PRODUCTS  ." ";
		if($type=='S' && $subscription_id=="-1") $display_header.=TEXT_SUBSCRIPTION . ":&nbsp;&nbsp;" . TEXT_ALL_SUBSCRIPTIONS . " ";
		if($type=='E' && $event_id=="-1") $display_header.=TEXT_EVENT. ":&nbsp;&nbsp;" . TEXT_ALL_EVENTS . " ";
		if($type=='V' && $service_id=="-1") $display_header.=TEXT_SERVICE. ":&nbsp;&nbsp;" . TEXT_ALL_SERVICES . " ";
		if($type=='H' && $product_id=="-1") $display_header.=TEXT_SHIPPING. ":&nbsp;&nbsp;" . TEXT_ALL_SHIPPING . " ";
	}
	if($start_time==''){
	$start_time_query=tep_db_query('select configuration_value from '. TABLE_CONFIGURATION .' where configuration_key="REPORT_BUSINESS_DAY_START_TIME"');
	if($start_time_array=tep_db_fetch_array($start_time_query)) $start_time=$start_time_array['configuration_value'];	
	}
	if($start_time!=''){
	if (($start_date!='') && ($start_time!='')) $where .= " and date_format(o.date_purchased,'%Y-%m-%d %H:%i')>='". $start_date ." ". $start_time ."'";
	if (($end_date!='') && ($start_time!=''))	$where .=" and date_format(o.date_purchased,'%Y-%m-%d %H:%i')<='" . $end_date ." ". $start_time . "'";
	} else{
	if ($start_date!='') $where .= " and date_format(o.date_purchased,'%Y-%m-%d')>='". $start_date ."'";
	if ($end_date!='')	$where .=" and date_format(o.date_purchased,'%Y-%m-%d')<='" . $end_date . "'";
	}
	if ($type=='P' && $product_id>0) {
		$prd_where .= " and op.products_id='" . (int)$product_id . "' "; 
		if($type=='P')
			$display_header.=TEXT_PRODUCT . ":&nbsp;&nbsp;" . tep_get_products_name($product_id, $FSESSION->languages_id) . " ";
		else
			$display_header.=TEXT_SHIPPING . ":&nbsp;&nbsp;" . tep_get_products_name($product_id, $FSESSION->languages_id) . " ";
	}
	
	if($type=='S' && $subscription_id>0) {
		$prd_where .= " and op.products_id='" . (int)$subscription_id . "' ";
		$display_header.= TEXT_SUBSCRIPTION . ":&nbsp;&nbsp;" . tep_get_subscription_name($subscription_id, $FSESSION->languages_id) . " ";
	}	
	$eve_where = "";
	if($type=='E' && $event_id>0) {		 
		$eve_where .= " and es.events_id='" .(int)$event_id . "' ";
		$display_header.= TEXT_EVENT . ":&nbsp;&nbsp;" . tep_get_events_name($event_id, $FSESSION->languages_id) . " ";
	}
	if($type=='V' && $service_id>0) {		 
		$prd_where .= " and op.products_id='" . (int)$service_id . "' ";
		$display_header.= TEXT_SERVICE . ":&nbsp;&nbsp;" . tep_get_service_name($service_id, $FSESSION->languages_id) . " ";
	}

	if($sales_by_order==1 && $type != 'H')$display_header.=TEXT_SALES_BY_ORDER.':&nbsp;' . TEXT_YES.'&nbsp;&nbsp;';
	if($tax==1)$display_header.=TEXT_TAX . ':&nbsp;' . TEXT_YES.'&nbsp;&nbsp;';
	if($type=='P' && $product_id<=0)if($sort_manufact==1)$display_header.=TEXT_SORT_MANUFACTURER.':&nbsp;' . TEXT_YES.'&nbsp;&nbsp;';
	if ($summary) $display_header.=TEXT_SUMMARY . ':&nbsp;' . TEXT_YES;
	if($type!='H')
	$type_where .=" and op.products_type='" . $type ."'";
	else
	$type_where .=" and op.products_type='P'";
	$attributes=array();
	// get the attributes of products and services and prepare the detail list
	if ($type=="P" || $type=='V'){
		$attributes_sql="select op.products_id,opa.products_options_id,opa.products_options_values_id,opa.products_options_values,op.orders_products_id,op.orders_id from " . TABLE_ORDERS . " o," . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " opa where o.orders_id=op.orders_id and op.orders_products_id=opa.orders_products_id " . $where . $prd_where . $type_where . " and op.orders_products_status>1 and op.orders_products_status<5 order by op.orders_id,op.orders_products_id,op.products_id,opa.products_options_id";
		$attributes_query=tep_db_query($attributes_sql);
		$prev_products_id="";
		$prev_orders_id=-1;
		
		$attribute_list='';
		while($attribute_result=tep_db_fetch_array($attributes_query)){
			if ($prev_orders_id!=$attribute_result["orders_products_id"]){
				if ($attribute_list!=""){
					$attributes[$prev_orders_products_id]=array("id"=>substr($attribute_list,0,-1),"name"=>substr($attribute_name,0,-1));
					$attribute_list="";
					$attribute_name="";
					$prev_products_id="";
				}
				$prev_orders_id=$attribute_result["orders_products_id"];
			}
			if ($prev_products_id!=$attribute_result["products_id"]){
				if ($attribute_list!=""){
					$attributes[$prev_orders_products_id]=array("id"=>substr($attribute_list,0,-1),"name"=>substr($attribute_name,0,-1));
					$attribute_list="";
					$attribute_name="";
				}
				$prev_products_id=$attribute_result["products_id"];
			}
			$attribute_list.=sprintf("%s(%s)-",$attribute_result["products_options_id"],$attribute_result["products_options_values_id"]);
			$attribute_name.=$attribute_result["products_options_values"] . ",";
			$prev_orders_products_id=$attribute_result["orders_products_id"];
		}
		if ($attribute_list!=""){
			$attributes[$prev_orders_products_id]=array("id"=>substr($attribute_list,0,-1),"name"=>substr($attribute_name,0,-1));
			$attribute_list="";
			$attribute_name="";
			$prev_products_id="";
		}
		tep_db_free_result($attributes_query);
	}
	$details=array();
	$found_results=false;
	$payment=new payment();
	$selection=$payment->selection();
	$cashpos=0;$moneyorderpos=0;
	$payment_list=array();

	$payment_col_count=0;
	if($type=='E') 
		$unique_payment_sql="SELECT distinct payment_method from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_EVENTS_SESSIONS . " es where o.orders_id=op.orders_id  and es.sessions_id=op.products_id " . $where . $prd_where. " and op.orders_products_status>1  and op.orders_products_status<5 order by o.payment_method";		
	else
		$unique_payment_sql="SELECT distinct payment_method from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where o.orders_id=op.orders_id  " . $where . $prd_where. " and op.orders_products_status>1 and op.orders_products_status<5 order by o.payment_method";

	$unique_payment_query=tep_db_query($unique_payment_sql);
	$ordered_payments=",";

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
		//if($selection[$icnt]['id']=='germanbank' && $selection[$icnt]['module']=='Bank Transfer Payment') $selection[$icnt]['module']='Germanbank';
		//if($selection[$icnt]['id']=='commbank') $selection[$icnt]['module']='commbank';
		$payment_list[]=array("name"=>$selection[$icnt]['module'],"subtotal"=>0,"total"=>0,"on"=>$payment_on);	
	}
	$payment_count=count($payment_list);
	// Get the details of ordered products
	//echo $type;
	//	$products_sql = "SELECT op.orders_id,op.products_id,op.products_quantity,ot.value as cost,sum(op.products_quantity*p.products_weight) as weight,o.payment_method,o.customers_id,o.customers_name,o.orders_status,o.delivery_country,o.shipping_method from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_TOTAL . " ot, ". TABLE_PRODUCTS . " p where p.products_id=op.products_id and ot.orders_id=o.orders_id and ot.title='Shipping:' and o.orders_id=op.orders_id  " . $where . $prd_where . $type_where . " and o.orders_status>1 group by op.products_id order by op.products_name";	

	if($sales_by_order==1){
		if($type!='H')
		$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity) as products_quantity,sum(op.final_price*op.products_quantity) as final_price,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as products_tax,((op.final_price*op.products_tax)/100) as tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where o.orders_id=op.orders_id  " . $where . $type_where . " and op.orders_products_status>1 and op.orders_products_status<5 group by o.orders_id order by o.date_paid,o.orders_id";
		else
		$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,ot.value as cost,sum((op.products_quantity*p.products_weight)) as weight,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity) as products_quantity,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,o.delivery_country ,ot.title as shipping_method  from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_TOTAL . " ot, ". TABLE_PRODUCTS . " p where p.products_id=op.products_id and ot.orders_id=o.orders_id and ot.value>0 and ot.class='ot_shipping' and  o.orders_id=op.orders_id  " . $where . $type_where . " and op.orders_products_status>1  and op.orders_products_status<5 group by o.orders_id order by o.date_paid,o.orders_id ";
	}
	
	else if($type=='P' && $sort_manufact=='1')
		$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, op.products_quantity,op.final_price,((op.final_price*op.products_tax)/100) as tax,((op.final_price*op.products_tax)/100)*op.products_quantity as products_tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p where o.orders_id=op.orders_id and p.products_id=op.products_id  " . $where . $prd_where . $type_where . "  and op.orders_products_status>1 and op.orders_products_status<5 order by p.manufacturers_id ";	
	else if($type=='E'){
		///???????/////?????/////
		/*$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, op.products_quantity,op.final_price,((op.final_price*op.products_tax)/100) as tax,((op.final_price*op.products_tax)/100)*op.products_quantity as products_tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_EVENTS_SESSIONS . " es where o.orders_id=op.orders_id  " . $where . $prd_where . $type_where . $eve_where . " and op.orders_products_status>1 and op.orders_products_status<5 and es.sessions_id=op.products_id group by op.products_id order by op.products_name";
		$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, op.products_quantity,op.final_price,((op.final_price*op.products_tax)/100) as tax,((op.final_price*op.products_tax)/100)*op.products_quantity as products_tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_EVENTS_SESSIONS . " es where o.orders_id=op.orders_id  " . $where . $prd_where . $type_where . $eve_where . " and op.orders_products_status>1 and op.orders_products_status<5 and es.sessions_id=op.products_id group by op.orders_id,op.products_id order by op.products_name";	
		*/
		////???? ///// ???????? ///////	
		$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity) as products_quantity,sum(op.final_price) as price, op.final_price,((op.final_price*op.products_tax)/100) as tax,(((op.final_price*op.products_tax)/100)*(sum(op.products_quantity))) as products_tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_EVENTS_SESSIONS . " es where o.orders_id=op.orders_id  " . $where . $prd_where . $type_where . $eve_where . " and op.orders_products_status>1 and op.orders_products_status<5 and es.sessions_id=op.products_id group by op.orders_id,op.events_id order by op.products_name,op.orders_id desc";	
	}
	else if($type=='H')
		$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,ot.value as cost,sum((op.products_quantity*p.products_weight)) as weight,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity) as products_quantity,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,o.delivery_country ,o.shipping_method  from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_TOTAL . " ot, ". TABLE_PRODUCTS . " p where p.products_id=op.products_id and ot.orders_id=o.orders_id and ot.class='ot_shipping' and  o.orders_id=op.orders_id  " . $where . $type_where . " and op.orders_products_status>1 and op.orders_products_status<5 group by o.orders_id order by o.orders_id,op.orders_id ";
	else { 
		if($type=='P') {
			//$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, op.products_quantity,op.final_price,((op.final_price*op.products_tax)/100) as tax,((op.final_price*op.products_tax)/100)*op.products_quantity as products_tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,ot.class,ot.value from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot on ot.orders_id=op.orders_id and ot.class='ot_gv' where o.orders_id=op.orders_id  " . $where . $prd_where . $type_where . "  and op.orders_products_status>1 and op.orders_products_status<5 order by op.products_name";
			//tep_db_query("SET OPTION SQL_BIG_SELECTS=1"); // CARTZONE added
			$products_sql = "SELECT ot1.value as orders_total,ot2.value as gv ,op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, op.products_quantity,op.final_price,round(((op.final_price*op.products_tax)/100),2) as tax,((op.final_price*op.products_tax)/100)*op.products_quantity as products_tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id from " . TABLE_ORDERS . " o,  " . TABLE_ORDERS_PRODUCTS . " op LEFT JOIN  " . TABLE_ORDERS_TOTAL ." ot1 on op.orders_id=ot1.orders_id and ot1.class='ot_total' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot2 on op.orders_id=ot2.orders_id and ot2.class='ot_gv' where o.orders_id=op.orders_id  " . $where . $prd_where . $type_where . "  and op.orders_products_status>1 and op.orders_products_status<5 order by op.products_name,op.orders_id desc";
		} else 
			$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, op.products_quantity,op.final_price,((op.final_price*op.products_tax)/100) as tax,((op.final_price*op.products_tax)/100)*op.products_quantity as products_tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where o.orders_id=op.orders_id  " . $where . $prd_where . $type_where . "  and op.orders_products_status>1 and op.orders_products_status<5 order by op.products_name,op.orders_id desc";
	}			
	if ($post_action=="screen") $query_split = new splitPageResultsReport($page, REPORT_MAX_ROWS_PAGE, $products_sql, $query_split_numrows);
	$products_query=tep_db_query($products_sql);
	//echo $products_sql;
	if (tep_db_num_rows($products_query)>0) 
	$found_results=true;
	while($products_result=tep_db_fetch_array($products_query)){
		$id=$products_result["orders_products_id"];
		$pid=$products_result["products_id"];
		if (isset($attributes[$id])){
			$key_id=$pid . '-' . $attributes[$id]['id'];
			$name=$attributes[$id]["name"];
			$name1=$products_result["products_name"];
		} else {
			$key_id=$pid;
			$name1='';
			$name=$products_result["products_name"];
		}
		if($sales_by_order==1 )$key_id=$products_result["orders_id"];
		if (!isset($details[$key_id])){
			$details[$key_id]=array("name1"=>$name1,"name"=>$name,"contents"=>array());
		}
		if($sales_by_order==1)
			$final_price=$products_result["final_price"];
		else
			$final_price=$products_result["final_price"]*$products_result["products_quantity"];
		if($type!='H'){
		$details[$key_id]["contents"][]=array("orders_id"=>$products_result["orders_id"],"cname"=>$products_result["customers_name"],"quan"=>$products_result["products_quantity"],"uprice"=>$products_result["final_price"],"tprice"=>$final_price,"ptax"=>$products_result["products_tax"],"tax"=>$products_result["tax"],"col_pos"=>get_payment_col($products_result["payment_method"],$products_result["orders_status"]),"date"=>format_date($products_result["date_purchased"]),"paid_date"=>format_date($products_result["date_paid"]),"orders_id"=>$products_result["orders_id"],"products_id"=>$pid,"gv"=>$products_result["gv"]);
		}else{ 
		$details[$key_id]["contents"][]=array("orders_id"=>$products_result["orders_id"],"cname"=>$products_result["customers_name"],"location"=>$products_result["delivery_country"],"weight"=>$products_result["weight"],"shipping_method"=>$products_result["shipping_method"],"cost"=>$products_result["cost"],"uprice"=>$products_result["cost"],"col_pos"=>get_payment_col($products_result["payment_method"],$products_result["orders_status"]),"date"=>format_date($products_result["date_purchased"]),"paid_date"=>format_date($products_result["date_paid"]),"orders_id"=>$products_result["orders_id"],"products_id"=>$pid);
		}
	}
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
<script language="JavaScript">
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
		var events_display=document.getElementById("events");
		var subscription_display=document.getElementById("subscription");
		var service_display=document.getElementById("service");
		var view_sort_manu=document.getElementById("view_sort_manu");
		var show_tax=document.getElementById("show_tax");
		var show_sales=document.getElementById("show_sales");
		if(type=='P' ){
			set_display(product,true);
			set_display(product_display,true);
			set_display(subscription_display,false);
			set_display(events_display,false);
			set_display(service_display,false);
			set_display(events,false);
			set_display(subscription,false);
			set_display(service,false);
			set_display(show_tax,true);
			set_display(show_sales,true);
		}else if(type=='E'){
			set_display(events,true);
			set_display(events_display,true);
			set_display(subscription_display,false);
			set_display(product_display,false);
			set_display(service_display,false);
			set_display(product,false);
			set_display(subscription,false);
			set_display(view_sort_manu,false);
			set_display(service,false);
			set_display(show_tax,true);
			set_display(show_sales,true);
		}else if(type=='S'){
			set_display(subscription,true);
			set_display(subscription_display,true);
			set_display(events_display,false);
			set_display(product_display,false);
			set_display(service_display,false);
			set_display(product,false);
			set_display(events,false);
			set_display(service,false);
			set_display(view_sort_manu,false);
			set_display(show_tax,true);
			set_display(show_sales,true);
		}else if(type=='V'){
			set_display(service,true);
			set_display(service_display,true);
			set_display(events_display,false);
			set_display(product_display,false);
			set_display(subscription_display,false);
			set_display(product,false);
			set_display(events,false);
			set_display(subscription,false);	
			set_display(view_sort_manu,false);
			set_display(show_tax,true);
			set_display(show_sales,true);
		}else if(type=='H'){
			set_display(service,false);
			set_display(service_display,false);
			set_display(events_display,false);
			set_display(product_display,false);
			set_display(subscription_display,false);
			set_display(product,false);
			set_display(events,false);
			set_display(subscription,false);	
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
			command="<?php echo tep_href_link(FILENAME_PRODUCTS_SALES);?>?command=fetch_report"+sel_node+where;
			do_get_command(command);
		}
	}
	
	function doReport(mode,page) { 
		var startDate=date_format(document.f.txt_start_date.value,'','y-m-d',true);
		var endDate=date_format(document.f.txt_end_date.value,'','y-m-d',true);
		var stTime=document.f.selected_time.value;
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
		else if(events &&  events.style.display=='') type='E';
		else if(subscription  && subscription.style.display=='') type='S';
		else if(service && service.style.display=='') type='V';
		get_divs(type,product,events,subscription,service);
		if(get_needed_details()!='') where=get_needed_details();
		command="<?php echo tep_href_link(FILENAME_PRODUCTS_SALES);?>?command=fetch_report"+sel_node+where+"&page="+page+"&post_action="+post_action+"&type="+type+"&stTime="+stTime;
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
		}else if(document.getElementById("sel_event") && events_display && events_display.style.display!='none'){
			pro_object=document.getElementById("sel_event");
			sel_node="&sel_event="+pro_object.options[pro_object.selectedIndex].value;
		}else if(document.getElementById("sel_subscription") && subscription_display && subscription_display.style.display!='none'){
			pro_object=document.getElementById("sel_subscription");
			sel_node="&sel_subscription="+pro_object.options[pro_object.selectedIndex].value;
		}else if(document.getElementById("sel_service") && service_display && service_display.style.display!='none'){
			pro_object=document.getElementById("sel_service");
			sel_node="&sel_service="+pro_object.options[pro_object.selectedIndex].value;
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
			function formatDate(arg) {
				var selected=document.getElementById('selected_item').value;
				if(selected!=''){
				var time = document.getElementById(selected).value;
				if(document.getElementById('hours')) hours=document.getElementById('hours');
				if(document.getElementById('mins')) mins=document.getElementById('mins');

				var hour = parseFloat(hours.value);
				var minu = parseFloat(mins.value);
				if(arg=='next'){
				if(selected=='hours'){
					hour++;
					if(hour>23) hour=0;
				} else if(selected=='mins'){
					minu++;
					if(minu>59){ 
						minu=0; hour++;
						if(hour>23) hour=0; 
					}
				}
				} else{
				if(selected=='hours'){
					hour--;
					if(hour<0){ 
					hour=0;
					}
				} else if(selected=='mins'){
					minu--;
					if(minu<0){ 
						minu=59;
						if(hour>0){
							hour--;
						} else{
							hour=23;
						}
					} 
				}
				}
				hours.value = ((hour < 10 ) ? '0' + hour : hour);
				mins.value = ((minu < 10) ? '0' + minu : minu);
				document.getElementById('selected_time').value=hours.value+':'+mins.value;
				}
			}

</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php');?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr> 
		<!-- body_text //-->	  
		<td width=100% align=left valign="top">
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
			<tr>
				<td class="pageHeading"><?php echo HEADING_PRODUCTS_SALES;?></td>	
			</tr>
		   <tr>
			   <TD>
				<FORM action="reports_sales_products.php" id="f" name="f" method="post">
					<input type="hidden" name="page" value="1">
					<input type="hidden" name="type" value="P">
					<input type="hidden" name="post_action" value="1">
			  <table cellspacing="3" cellpadding="2" class="searchArea" width="100%" border="0">
			  <tbody>
				<?php $_array=array('d','m','Y');  $replace_array=array('DD','MM','YYYY'); 	$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>
				<tr> 
				<td  width="40%" nowrap valign="top"><?php echo REPORT_START_DATE . '&nbsp;' . tep_draw_input_field('txt_start_date',format_date($start_date),' size="10"');?> 
					<a href="javascript:show_calendar('f.txt_start_date',null,null,'<?php echo $date_format;?>');"
					   onmouseover="window.status='Date Picker';return true;"
					   onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/>  
					   </a>
				  <?php echo '&nbsp;'.REPORT_END_DATE . tep_draw_input_field('txt_end_date',format_date($end_date),' size="10"');?>
					<a href="javascript:show_calendar('f.txt_end_date',null,null,'<?php echo $date_format;?>');"
					   onmouseover="window.status='Date Picker';return true;"
					   onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/>  
					   </a>
				  </td>
				<td width="40%" align="left" nowrap="nowrap" style="padding-left:10px">
					<?php
					$start_time_query=tep_db_query('select configuration_value from '. TABLE_CONFIGURATION .' where configuration_key="REPORT_BUSINESS_DAY_START_TIME"');
					if($start_time_array=tep_db_fetch_array($start_time_query)) $time=$start_time_array['configuration_value'];
					$time=explode(':',$time);
					$hour=$time[0];
					$minu=$time[1];
					$st_times=$hour.':'.$minu;
					echo TEXT_BUSINESS_DAY_START_TIME .'&nbsp;'.$st_times;
					?>
					<INPUT ID="selected_time" name="selected_time" value="<?php echo (($st_times)?$st_times:'00:00'); ?>" type="hidden">						
				</td>
				<td  align="right" nowrap valign="top">
					<?php echo '<a href="javascript:doReport(1);">' . tep_image_button('button_report_search.gif', IMAGE_SEARCH_DETAILS) . '</a>'; ?>
				</td>
				</tr>
				<tr class="main">
				<td id="view_all_products_space" align="left" width="80%"></td>
				<td id="view_all_products" align="left" width="80%">
					<?php 
					echo '<div id="product">';
						echo '&nbsp;&nbsp;&nbsp;';
						$all_products_array = array();
						$all_products_array = tep_get_products_array_single();
						if(sizeof($all_products_array)>0) echo TEXT_PRODUCTS . '  ' ;
						if(sizeof($all_products_array)<=0) echo TEXT_PRODUCTS_NOT_AVAILABLE;
						else echo tep_draw_products_select_menu('sel_product',$all_products_array,$product_id,' id="sel_product"');
					echo '</div><div id="subscription" style="display:none">';
					echo TEXT_SUBSCRIPTIONS . '  ';
						$all_subscriptions_array=array();
						$all_subscriptions_array = tep_get_subscription_array_single();
						if(sizeof($all_subscriptions_array)<=0) echo 'Subscriptions are not Available';
						else echo tep_draw_subscription_select_menu('sel_subscription',$all_subscriptions_array,$subscription_id,' id="sel_subscription"');
					echo '</div><div id="events" style="display:none">';
						echo TEXT_EVENTS . '  ';
						$all_events_array=array();
						$all_events_array = tep_get_events_array_single();
						if(sizeof($all_events_array)<=0) echo 'Events are not Available';
						else echo tep_draw_event_select_menu('sel_event',$all_events_array,$event_id,' id="sel_event"');
					echo '</div><div id="service" style="display:none">';
						echo TEXT_SERVICES . '  ';
						$all_service_array=array();
						$all_service_array = tep_get_service_array_single();
						if(sizeof($all_service_array)<=0) echo 'Services are not Available';
						else echo tep_draw_service_select_menu('sel_service',$all_service_array,$service_id,' id="sel_service"');
					echo '</div>';
					?>
				</td>
				<td width="40%">
					<table width=100% cellspacing="0" cellpadding="0">
						<tr>
							<td nowrap="nowrap"><label><?php echo tep_draw_checkbox_field('summary',0,$summary) .'  ' . REPORT_SUMMARY_ONLY;?></label></td>
							<td id="show_tax" nowrap><label><?php echo tep_draw_checkbox_field('tax',0,$tax) .'  ' . TEXT_TAX;?></label></td>
							<td id="show_sales" nowrap><label><?php echo  tep_draw_checkbox_field('sales_by_order',0,$sales_by_order,''," id='sales_by_order' onclick='disable_products()'") .'  ' . 'Sales By Order</div>';?></label></td>
						</tr>
					</table>
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
		<?php if($start_time=='00:00'){ 
		?>
		<tr><td style="background:#FEB3B5;" class="smalltext"><?php echo TEXT_BUSINESS_DAY_START_TIME_ERR; ?>
		<a class="main" href="<?php echo tep_href_link(FILENAME_CONFIGURATION,'top=1&mPath=11_160_169'); ?>"><span class="main">click here</span></a>
		</td></tr>
		<?php } ?>
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


	function getDetails($type="", $detailed_view=false) {
		global $display_header,$found_results,$details,$payment_count,$currencies,$report_filename,$payment_list,$summary,$payment_col_count,$tax,$sales_by_order,$FREQUEST;
		$mPath = explode("mPath",$FREQUEST->servervalue('HTTP_REFERER'));
		$mPath = explode("=",$mPath[1]);
		$mPath = explode("&",$mPath[1]);
		$mPath = $mPath[0];
		?>		  
		<?php 
			if(!$detailed_view) {
				display_total(true,$type);
			}else if($detailed_view) {
				if($type=='P') {
					echo '<tr><td class="smalltext" colspan=' . (8+$tax+$payment_col_count) . ' width=100%><b>';
				}	
				else  {
					echo '<tr><td class="smalltext" colspan=' . (7+$tax+$payment_col_count) . ' width=100%><b>' ;
				}	?>
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr height="20" onClick="javascript:doChange('<?php echo $type; ?>')" style="cursor:hand;cursor:pointer;">
						<td class="contentTitle" valign="top" width="98%">
							<div style="width:80px;background:#FFFFFF;">
								<?php echo get_heading($type);?>
							</div>	
						</td>
						<td width="2%" align="right"><a style="cursor:hand;cursor:pointer"><img src="images/template/panel_up.gif" id="img_' . $title . '" border="0"></a></td>
					</tr>
					<tr height="10">
						<td></td>
					</tr>
				</table>
				<?php echo '</b></td></tr><tr><td colspan='.(8+$tax+$payment_col_count).'> <table id="close_div_'.$type.'" border="0" cellpadding="0" cellspacing="0" width="98%">';?>
				<?php 
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
						$all_manual_price=0;
						if($type=='H'){
						?>
						<tr>
							<td colspan="<?php echo 6+$payment_col_count;?>" width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<?php }?>
									<tr class=" cell_bg_report_header ">
										<?php if(!$summary) {?>
											<?php if($sales_by_order==1){ 
												if($type=='H'){	?>
													<td class="dataTableHeadingTitleContent" width="5%"><?php echo  TEXT_INDEX;?></td>
													<td class="dataTableHeadingTitleContent"  width="15%"><?php echo  TEXT_CUSTOMER_NAME;?></td>
													<td class="dataTableHeadingTitleContent"  align="left" width="10%" ><?php echo TEXT_ORDER_ID;?></td>
													<td class="dataTableHeadingTitleContent"  align="left" width="20%"><?php echo TEXT_LOCATION;?></td>
													<td class="dataTableHeadingTitleContent" align="left" nowrap width="35%"><?php echo TEXT_SHIPPING_METHOD;?></td>
													<td class="dataTableHeadingTitleContent"  align="right" width="5%"><?php echo TEXT_WEIGHT;?></td>
													<td class="dataTableHeadingTitleContent"  align="right" width="10%"><?php echo TEXT_COST;?></td>
												<?php }else{?>
													<td class="dataTableHeadingTitleContent"  width="5"><?php echo TEXT_INDEX ?></td>
													<td class="dataTableHeadingTitleContent" align="left"  width="150"><?php echo TEXT_CLIENT;?></td>
													<td class="dataTableHeadingTitleContent" align="left" width="50" nowrap="nowrap"><?php  echo TEXT_ORDER_ID ;?></td>
													<td class="dataTableHeadingTitleContent"  align="right" width="50"><?php  echo TEXT_SALE_DATE;?></td>
													<td class="dataTableHeadingTitleContent"  align="right" width="75"><?php  echo TEXT_PAID_DATE;?></td>
													<td class="dataTableHeadingTitleContent"  align="right" width="75"><?php  echo TEXT_TOTAL;?></td>
													<?php if($tax==1){?>
														<td class="dataTableHeadingTitleContent" align="right" width="75"><?php echo TEXT_TAX;?></td>
													<?php }?>
													<?php  
														for ($icnt=0;$icnt<$payment_count;$icnt++){
															if ($payment_list[$icnt]["on"]==0) continue;
															if(substr($payment_list[$icnt]["name"],0,4)=='Cash') 
																$name='Cash';
															else{
																$name=(strlen($payment_list[$icnt]["name"])>6?substr($payment_list[$icnt]["name"],0,6):$payment_list[$icnt]["name"]);
															}
															if(substr($payment_list[$icnt]["name"],0,11)=='Credit Card' && strpos($payment_list[$icnt]["name"],"img")>0)	
																$payment_list[$icnt]["name"]='Credit Cart'; 
															echo '<td  width="50" class="dataTableHeadingTitleContent" align="right" title= $payment_list[$icnt]["name"]> ' . $name . '</td>';
														}
													?>
													<td class="dataTableHeadingTitleContent"  align="right" width="105"><?php  echo 'Price Adjustment';?></td>
													<?php	}
											}
											if($sales_by_order==0){
													if($type=='H'){?>
														<td class="dataTableHeadingTitleContent" width="5%"><?php echo  TEXT_INDEX;?></td>
														<td class="dataTableHeadingTitleContent"  width="15%"><?php echo  TEXT_CUSTOMER_NAME;?></td>
														<td class="dataTableHeadingTitleContent"  align="left" width="10%" ><?php echo TEXT_ORDER_ID;?></td>
														<td class="dataTableHeadingTitleContent"  align="left" width="20%"><?php echo TEXT_LOCATION;?></td>
														<td class="dataTableHeadingTitleContent" align="left" nowrap width="35%"><?php echo TEXT_SHIPPING_METHOD;?></td>
														<td class="dataTableHeadingTitleContent"  align="right" width="5%"><?php echo TEXT_WEIGHT;?></td>
														<td class="dataTableHeadingTitleContent"  align="right" width="10%"><?php echo TEXT_COST;?></td>
													<?php }else{?>
														<td class="dataTableHeadingTitleContent"><?php echo TEXT_INDEX ?></td>
														<td class="dataTableHeadingTitleContent" align="left" ><?php echo TEXT_CLIENT;?></td>
														<td class="dataTableHeadingTitleContent"  align="right" width="50"><?php  echo TEXT_SALE_DATE;?></td>
														<td class="dataTableHeadingTitleContent"  align="right" width="50"><?php  echo TEXT_PAID_DATE;?></td>
														<td class="dataTableHeadingTitleContent"  width="10%" align="right" width="50"><?php echo TEXT_UNIT_PRICE;?></td>
														<td class="dataTableHeadingTitleContent"  width="5%" align="right" width="25"><?php echo TEXT_SOLD;?></td>
														<?php if($type=='P'){ ?>
															<td class="dataTableHeadingTitleContent" width="8%" align="right" width="25"><?php echo TEXT_GIFT_VOUCHER;?></td>
														<?php }?>
															<td class="dataTableHeadingTitleContent"  align="right"><?php  echo TEXT_TOTAL;?></td>
														<?php if($tax==1){?>
															<td class="dataTableHeadingTitleContent" align="right" width="75"><?php echo TEXT_TAX;?></td>
														<?php }
															for ($icnt=0;$icnt<$payment_count;$icnt++){
																if ($payment_list[$icnt]["on"]==0) continue;
																if(substr($payment_list[$icnt]["name"],0,4)=='Cash')
																	$name='Cash';
																else{
																	$name=(strlen($payment_list[$icnt]["name"])>6?substr($payment_list[$icnt]["name"],0,6):$payment_list[$icnt]["name"]);
																}
																if(substr($payment_list[$icnt]["name"],0,11)=='Credit Card' && strpos($payment_list[$icnt]["name"],"img")>0)	
																	$payment_list[$icnt]["name"]='Credit Cart'; 
																echo '<td width="75" class="dataTableHeadingTitleContent" align="right" title= $payment_list[$icnt]["name"]> ' . $name . '</td>';
															}
														?>
														<td class="dataTableHeadingTitleContent"  align="right" width="95"><?php  echo 'Price Adjustment';?></td>
													<?php	}		
												}
											}else{
												if($sales_by_order==1){ ?>
													<?php if($type != 'H'){?>
														<td class="dataTableHeadingTitleContent" align="left" colspan="5" ></td>
														<td class="dataTableHeadingTitleContent"  align="right"><?php  echo TEXT_TOTAL;?></td>
														<?php if($tax==1){?>
														<td class="dataTableHeadingTitleContent" align="right" ><?php echo TEXT_TAX;?></td>
														<?php }  
															for ($icnt=0;$icnt<$payment_count;$icnt++){
																if ($payment_list[$icnt]["on"]==0) continue;
																	if(substr($payment_list[$icnt]["name"],0,4)=='Cash') $name='Cash';
																else{
																	$name=(strlen($payment_list[$icnt]["name"])>6?substr($payment_list[$icnt]["name"],0,6):$payment_list[$icnt]["name"]);
																}
																if(substr($payment_list[$icnt]["name"],0,11)=='Credit Card' && strpos($payment_list[$icnt]["name"],"img")>0)	
																	$payment_list[$icnt]["name"]='Credit Cart'; 
																echo '<td class="dataTableHeadingTitleContent" align="right" title= $payment_list[$icnt]["name"]> ' . $name . '</td>';
															}
														?>
													<td class="dataTableHeadingTitleContent" align="right" ><?php echo "Price Adjustment";?></td>
													<?php }else {?>
														<td class="dataTableHeadingTitleContent" align="left" colspan="5" ></td>
														<td class="dataTableHeadingTitleContent"  align="right"><?php  echo TEXT_WEIGHT;?></td>
														<td class="dataTableHeadingTitleContent"  align="right"><?php  echo TEXT_COST;?></td>
													<?php }?>
												<?php		
												}
												if($sales_by_order==0){?>	
													<?php if($type!= 'H'){?>
														<td class="dataTableHeadingTitleContent" align="left" colspan="4" ></td>
														<td class="dataTableHeadingTitleContent"  width="10%" align="right"><?php /* echo TEXT_UNIT_PRICE;*/?></td>
														<td class="dataTableHeadingTitleContent"  width="5%" align="right"><?php echo TEXT_SOLD;?></td>
														<?php if($type=='P'){ ?>
															<td class="dataTableHeadingTitleContent" width="8%" align="center"><?php echo TEXT_GIFT_VOUCHER;?></td>
														<?php }?>
														<td class="dataTableHeadingTitleContent"  align="right"><?php  echo TEXT_TOTAL;?></td>
														<?php if($tax==1){?>
															<td class="dataTableHeadingTitleContent" align="right"><?php echo TEXT_TAX;?></td>
														<?php }
															for ($icnt=0;$icnt<$payment_count;$icnt++){
																if ($payment_list[$icnt]["on"]==0) continue;
																if(substr($payment_list[$icnt]["name"],0,4)=='Cash') $name='Cash';
																else{
																	$name=(strlen($payment_list[$icnt]["name"])>6?substr($payment_list[$icnt]["name"],0,6):$payment_list[$icnt]["name"]);
																}
																if(substr($payment_list[$icnt]["name"],0,11)=='Credit Card' && strpos($payment_list[$icnt]["name"],"img")>0)	
																	$payment_list[$icnt]["name"]='Credit Cart'; 
																echo '<td class="dataTableHeadingTitleContent" align="right" title= $payment_list[$icnt]["name"]> ' . $name . '</td>';
															}
														?>
													<td class="dataTableHeadingTitleContent" align="right" ><?php echo "Price Adjustment";?></td>
													<?php }else {?>
														<td class="dataTableHeadingTitleContent" align="left" colspan="5" ></td>
														<td class="dataTableHeadingTitleContent"  align="right"><?php  echo TEXT_WEIGHT;?></td>
														<td class="dataTableHeadingTitleContent"  align="right"><?php  echo TEXT_COST;?></td>
													<?php }?>
												<?php				
												}				
											}?>
									</tr>
						
									<?php
										//while(list($key,)=each($details)){
										foreach (array_keys($details) as $key)
										{
											$row=&$details[$key];
											$splt_key=explode("/-/",$key);
											$cat_id=get_categories_name($content['products_id'],'cat_id');
											if($pre_id!=$cat_id){
												$cat_name=get_categories_name($content['products_id']);													
												$pre_id=get_categories_name($content['products_id'],'cat_id');
											}
											if (count($splt_key)>1 && $sales_by_order==0){
												if ($splt_key[0]!=$prev_product_id){
													if ($unit_subtotal>0) {
														print_total_row($unit_subtotal,$all_subtotal,$all_subtax,$quan_subtotal,$all_gv,$all_manual_price);
														$unit_subtotal=0;
														$all_subtotal=0;
														$all_subtax=0;
														$quan_subtotal=0;
														$all_manual_price=0;
														$all_gv=0;
													}
													if($type=='P')
														echo '<tr class="dataTableHeadingRow" height="20"><td class="dataTableHeadingContent" colspan="' . (9+$tax+$payment_col_count) . '">' . $row["name1"] . '</td></tr>';
													else
														echo '<tr class="dataTableHeadingRow" height="20"><td class="dataTableHeadingContent" colspan="' . (8+$tax+$payment_col_count) . '">' . $row["name1"] . '</td></tr>';
													$prev_attribute_id=-1;
													$prev_product_id=$splt_key[0];
												}
												//		if ($splt_key[1]!=$prev_attribute_id && $sales_by_order==0){
												if ($key!=$prev_attribute_id && $sales_by_order==0){
													if ($unit_subtotal>0) {
														print_total_row($unit_subtotal,$all_subtotal,$all_subtax,$quan_subtotal,$all_gv,$all_manual_price);
														$unit_subtotal=0;
														$all_subtotal=0;
														$all_manual_price=0;
														$all_subtax=0;
														$quan_subtotal=0;
														$all_gv=0;
													}
													if($type=='P')
														echo '<tr class="dataTableHeadingRow" height="20"><td class="dataTableHeadingContent" colspan="' . (9+$tax+$payment_col_count) . '">' . tep_draw_separator('pixel_trans.gif',10,1) .$row["name"] . '</td></tr>';
													else	
														echo '<tr class="dataTableHeadingRow" height="20"><td class="dataTableHeadingContent" colspan="' . (8+$tax+$payment_col_count) . '">' . tep_draw_separator('pixel_trans.gif',10,1) .$row["name"] . '</td></tr>';
													//$prev_attribute_id=$splt_key[1];
													$prev_attribute_id=$key;
												}
											} else {	
												if (($splt_key[0]!=$prev_product_id || $prev_attribute_id!=-1) && $sales_by_order==0){
													if ($unit_subtotal>0 && $row["name"]!=$prev_products_name) {
														print_total_row($unit_subtotal,$all_subtotal,$all_subtax,$quan_subtotal,$all_gv,$all_manual_price);
														$unit_subtotal=0;
														$all_subtotal=0;
														$all_subtax=0;
														$all_manual_price=0;
														$quan_subtotal=0;
														$all_gv=0;
													}
													if ($splt_key[0]!=$prev_product_id && $row["name"]!=$prev_products_name){
														if($type=='P')
															echo '<tr class="dataTableHeadingRow" height="20"><td class="dataTableHeadingContent" colspan="' . (9+$tax+$payment_col_count) . '">' . $row["name"] . '</td></tr>';	
														else	
															echo '<tr class="dataTableHeadingRow" height="20"><td class="dataTableHeadingContent" colspan="' . (8+$tax+$payment_col_count) . '">' . $row["name"] . '</td></tr>';
													} 
													$prev_product_id=$splt_key[0];
													$prev_products_name = $row["name"];
													$prev_attribute_id=-1;
												}
											}
											$contents=&$row["contents"];
											if($type=='H'){
												if($sales_by_order==1){
												if($tax)
													$colspan=6+$payment_col_count-8;
												else
													$colspan=5+$payment_col_count-8;
												}
												else{
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
												
												if(!$summary) {?>
													<?php if($sales_by_order==1){
														if($type!='H'){?>
															<tr <?php echo $class; ?> valign="top" height="20" style="cursor:pointer;cursor:hand" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="javascript:location.href='<?php echo tep_href_link(FILENAME_ORDERS,'mPath=' . $mPath . '&oID=' .$content["orders_id"] . '&action=edit&return=ps&type='.$type);?>';">
															<td class="dataTableContent" ><?php echo $row_cnt;?></td>
															<td  class="dataTableContent"><?php echo $content["cname"];?></td>
															<td  class="dataTableContent"  align="left"><?php echo $content["orders_id"];?></td>
															<td  class="dataTableContent" align="right"><?php echo $content["date"];?></td>
															<td  class="dataTableContent" align="right"><?php echo $content["paid_date"];?></td>
															<td  class="dataTableContent"  align="right"><?php echo $currencies->format($content["tprice"]+$content["ptax"]);?></td>
															<?php if($tax==1){?>
															<td  class="dataTableContent" align="right"><?php echo $currencies->format($content["ptax"]);?></td>
															<?php }
																for ($jcnt=0;$jcnt<$payment_count;$jcnt++){
																	if ($payment_list[$jcnt]["on"]==0) continue;
																	if ($jcnt==$content["col_pos"]){ 
																		echo '<td class="dataTableContent" align="right" >' . $currencies->format($content["tprice"]+$content["ptax"]) .'</td>';
																		$payment_list[$content["col_pos"]]["subtotal"]+=$content["tprice"]+$content["ptax"];
																	} else {
																		echo '<td class="dataTableContent" align="right" ></td>';
																	}
																} ?>
															<td align="right" class="dataTableContent" >
																<?php 
																	$manual_price_query = tep_db_query('select text,value from '.TABLE_ORDERS_TOTAL.' where class = "ot_adjust" and orders_id="'.$content["orders_id"].'"');
																	$manual_price_result = tep_db_fetch_array($manual_price_query);

																	if($manual_price_result["value"] < 0){
																		$manualprice = (-1 * $manual_price_result["value"]);
																		echo $manual_price_result['text'];
																	}else{
																		echo $currencies->format($manual_price_result['value']);
																	}
																	
/*																	if($manual_price_result['value']!=0){
																		if(strpos($manual_price_result['text'],'-',1) > 0){
																			echo $manual_price_result['text'];
																		}else{
																			echo $currencies->format($manual_price_result['value']);
																		}
																	}else{
																			echo $currencies->format(0);
																	}
*/																?>
															</td>
														<?php }else{?>
															<tr <?php echo $class; ?> valign="top" height="20" style="cursor:pointer;cursor:hand" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="javascript:location.href='<?php echo tep_href_link(FILENAME_REPORTS_PRODUCTS_RECONCILIATION);?>';">
															<td class="dataTableContent" width="5%"><?php echo $row_cnt;?></td>
															<td  class="dataTableContent" width="15%"><?php echo $content["cname"];?></td>
															<td   class="dataTableContent" align="left" width="10%"><?php echo $content["orders_id"];?></td>
															<td  class="dataTableContent" align="left" width="20%"><?php echo $content["location"];?></td>
															<td  class="dataTableContent" align="left" width="35%"><?php $ship_method=explode('/-/',$content["shipping_method"]);echo $ship_method[0];?></td>
															<td  class="dataTableContent"  align="right" width="10%"><?php echo $content["weight"];?></td>
															<td  class="dataTableContent"  align="right" width="10%"><?php echo $currencies->format($content["cost"]);?></td>
														<?php } 
													}
													if($sales_by_order==0){
														if($type!='H'){?>
															<tr <?php echo $class; ?> valign="top" height="20" style="cursor:pointer;cursor:hand" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="javascript:location.href='<?php echo tep_href_link(FILENAME_ORDERS,'mPath=' . $mPath . '&oID=' .$content["orders_id"] . '&action=edit&return=ps&type=' . $type);?>';">
																<td class="dataTableContent"><?php echo $row_cnt;?></td>
																<td class="dataTableContent"><?php echo $content["cname"];?></td>
																<td class="dataTableContent" align="right"><?php echo $content["date"];?></td>
																<td class="dataTableContent" align="right"><?php echo $content["paid_date"];?></td>
																<td class="dataTableContent" align="right"><?php echo $currencies->format($content["uprice"]+$content["tax"]);?></td>
																<td  class="dataTableContent"  align="right"><?php echo $content["quan"];?></td>
																<?php if($type=='P'){ ?>
																	<td class="dataTableContent" align="right"><?php echo '<a href="' .tep_href_link(FILENAME_GV_QUEUE,'top=1&mPath=' . $mPath . '&oID=' .$content["orders_id"] . '&action=edit&return=gvq'). '">' .$currencies->format($content["gv"]) . '</a>';?></td>
																<?php }?>
																<td  class="dataTableContent"  align="right" width="75"><?php echo $currencies->format($content["tprice"]+$content["ptax"]);?></td>
																<?php if($tax==1){?>
																	<td  class="dataTableContent" align="right"><?php echo $currencies->format($content["ptax"]);?></td>
																<?php }?>
																<?php 
																	for ($jcnt=0;$jcnt<$payment_count;$jcnt++){
																		if ($payment_list[$jcnt]["on"]==0) continue;
																		if ($jcnt==$content["col_pos"]){
																			echo '<td class="dataTableContent" align="right" >' . $currencies->format($content["tprice"]+$content["ptax"]) .'</td>';
																			$payment_list[$content["col_pos"]]["subtotal"]+=$content["tprice"]+$content["ptax"];
																		} else {
																			echo '<td class="dataTableContent" align="right" >&nbsp;</td>';
																		}
																	} ?>
																<td align="right" class="dataTableContent" >
																	<?php 
																		$manual_price_query = tep_db_query('select text,value from '.TABLE_ORDERS_TOTAL.' where class = "ot_adjust" and orders_id="'.$content["orders_id"].'"');
																		$manual_price_result = tep_db_fetch_array($manual_price_query);
																		
																		if($manual_price_result["value"] < 0){
																			$manualprice = (-1 * $manual_price_result["value"]);
																			echo $manual_price_result['text'];
																		}else{
																			echo $currencies->format($manual_price_result['value']);
																		}
																		
/*																		if($manual_price_result['value']!=0){
																			if(strpos($manual_price_result['text'],'-',1) > 0){
																				echo $manual_price_result['text'];
																			}else{
																				echo $currencies->format($manual_price_result['value']);
																			}
																		}else{
																				echo $currencies->format(0);
																		}
*/																	?>
																</td>
														<?php }else{?>
															<tr <?php echo $class; ?> valign="top" height="20" style="cursor:pointer;cursor:hand" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="javascript:location.href='<?php echo tep_href_link(FILENAME_REPORTS_PRODUCTS_RECONCILIATION);?>';">
																<td class="dataTableContent" width="5%"><?php echo $row_cnt;?></td>
																<td  class="dataTableContent" width="15%"><?php echo $content["cname"];?></td>
																<td   class="dataTableContent" align="left" width="10%"><?php echo $content["orders_id"];?></td>
																<td  class="dataTableContent" align="left" width="20%"><?php echo $content["location"];?></td>
																<td  class="dataTableContent" align="left" width=35%"><?php $ship_method=explode('/-/',$content["shipping_method"]);echo $ship_method[0];?></td>
																<td class="dataTableContent"  align="left" width="10%"><?php echo $content["weight"];?></td>
																<td  class="dataTableContent"  align="right" width="10%"><?php echo $currencies->format($content["cost"]);?></td>
														<?php }
													}?>
												</tr>
												<?php
												} else { // $summary 
													$manual_price_query = tep_db_query('select text,value from '.TABLE_ORDERS_TOTAL.' where class = "ot_adjust" and orders_id="'.$content["orders_id"].'"');
													$manual_price_result = tep_db_fetch_array($manual_price_query);
													if(!$summary)
													for ($jcnt=0;$jcnt<$payment_count;$jcnt++){
														if ($payment_list[$jcnt]["on"]==0) continue;
														if ($jcnt==$content["col_pos"]){
															$payment_list[$content["col_pos"]]["subtotal"]+=$content["tprice"]+$content["ptax"];
														}
													} // $jcnt
												}
												$all_manual_price+=$manual_price_result["value"];
												$unit_subtotal+=$content["uprice"]+$content["tax"];
												$all_subtotal+=$content["tprice"]+$content["ptax"];
												$all_subtax+=$content["ptax"];
												
												
												if($type!='H')
													$quan_subtotal+=$content["quan"];
												else
													$quan_subtotal+=$content["weight"];
												$all_gv+=$content["gv"];	
												$row_cnt++;
											} // $icnt
										} //$key
										if ($unit_subtotal>0){
											print_total_row($unit_subtotal,$all_subtotal,$all_subtax,$quan_subtotal,$all_gv,$all_manual_price,'prt');
										}
										if($type=='H'){?>
											</table></td></tr>
										<?php }
									} else { //$found_results
										echo '<tr><td colspan="' . (10+$tax+$payment_col_count) . '" class="main" align="center">' . TEXT_NO_RECORDS_FOUND . '</td></tr>';
									}
									//tep_content_title_bottom_div();
									echo '</table></td></tr>';
								}?>
						<?php		
				}
				function display_total($show_total=false,$prd_type) {				
					global $products_id, $type,$manual_payment_price, $where,$manual_price,$payment_array, $prd_where, $payment_col_count, $currencies, $payment_list, $grand_total,$grand_total_tax,$grand_payment_array,$grand_pay_array,$tax,$summary,$tax,$sales_by_order,$page,$grand_quan,$grand_unit_price,$grand_gv,$manualPrice, $shipping_price, $shipping_payment_price;
					$heading=get_heading($prd_type);
					$payment=array();
					$session_table="";
					if($prd_type=='E') 	{
						$session_table = " ,events_sessions es ";
						$event_where .= " and es.sessions_id=op.products_id ";
					}	
					//$total_sql="SELECT op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity) as quan,sum(op.final_price) as pro_total,sum(op.products_quantity*op.final_price) as total,((op.final_price*op.products_tax)/100)*op.products_quantity as total_tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p where o.orders_id=op.orders_id and p.products_id=op.products_id  " . $where . $prd_where . $type_where . " and op.products_type='" . $prd_type . "' and o.orders_status>1 group by payment_method  order by op.products_name ";
					if($type==$prd_type) {
						if($type=='P'){
							$total_sql =" SELECT ";
							$total_sql .=" sum(ot1.value) as orders_total, ";
							$total_sql .=" sum(ot2.value) as gv , ";
							$total_sql .=" ot3.value as shipping_total, ";
							$total_sql .=" ot4.value as manual_price , ";
							$total_sql .=" op.orders_id,op.products_id,op.products_type, ";
							$total_sql .=" op.products_name,o.date_purchased, ";
							$total_sql .=" date_format(o.date_purchased, '%H:%i%s') as time, ";
							$total_sql .=" sum(op.products_quantity) as quan, ";
							$total_sql .=" sum(round(op.final_price,2)) as pro_total, ";
							$total_sql .=" sum(op.products_quantity*op.final_price) as total, ";
							$total_sql .=" sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax, ";
							$total_sql .=" sum((op.final_price*op.products_tax)/100) as tax,o.payment_method, ";
							$total_sql .=" o.customers_id,o.customers_name,o.orders_status,op.orders_products_id ";
							$total_sql .=" from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op ";
							$total_sql .=" LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot1 on op.orders_id=ot1.orders_id and ot1.class='ot_total' ";
							$total_sql .=" LEFT JOIN "  . TABLE_ORDERS_TOTAL . " ot2 on op.orders_id=ot2.orders_id and ot2.class='ot_gv' ";
							$total_sql .=" LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot3 on op.orders_id=ot3.orders_id and ot3.class='ot_shipping' ";
							$total_sql .=" LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot4 on op.orders_id=ot4.orders_id and ot4.class='ot_adjust' ";
							$total_sql .=" where o.orders_id=op.orders_id   " . $where . $prd_where . $type_where . " and ";
							$total_sql .=" op.products_type='" . tep_db_input($prd_type) . "' and ";
							$total_sql .=" op.orders_products_status>1 and ";
							$total_sql .=" op.orders_products_status<5 ";
							$total_sql .=" group by orders_id ";
							$total_sql .=" order by op.products_name ";
						}	
						else {
							$total_sql="SELECT op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity) as quan,op.products_type, sum(op.final_price) as pro_total,sum(op.products_quantity*op.final_price) as total,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax,sum((op.final_price*op.products_tax)/100) as tax, ot3.value as shipping_total, ot4.value as manual_price, o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op 
LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot4 on op.orders_id=ot4.orders_id and ot4.class='ot_adjust'
LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot3 on op.orders_id=ot3.orders_id and ot3.class='ot_shipping' 
where o.orders_id=op.orders_id   " . $where . $prd_where . $type_where . " and op.products_type='" . tep_db_input($prd_type) . "' and op.orders_products_status>1 and op.orders_products_status<5 group by orders_id order by op.products_name ";
						}
					}	
					else
						$total_sql=	"SELECT sum(op.final_price) as pro_total,sum(op.products_quantity) as quan,op.orders_id,op.products_id,op.products_name,op.products_type,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity*op.final_price) as total,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax,sum((op.final_price*op.products_tax)/100) as tax,  ot3.value as shipping_total, ot4.value as manual_price, o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot4 on op.orders_id=ot4.orders_id and ot4.class='ot_adjust' 
LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot3 on op.orders_id=ot3.orders_id and ot3.class='ot_shipping' " . $session_table . " where o.orders_id=op.orders_id  " . $where . $event_where . " and op.products_type='" . tep_db_input($prd_type) . "' and op.orders_products_status>1 and op.orders_products_status<5  group by orders_id order by op.products_name";

					if($type=='H'){
/*						$total_sql = "select ot.value as shipping_total,o.payment_method,o.orders_status from ".TABLE_ORDERS_TOTAL." ot, ".TABLE_ORDERS." o, ".TABLE_PRODUCTS." p, ".TABLE_ORDERS_PRODUCTS." op where op.orders_products_status>1 and op.orders_products_status<5 and p.products_id=op.products_id and o.orders_id=op.orders_id and ot.orders_id=o.orders_id and ot.class = 'ot_shipping' group by o.orders_id order by o.orders_id";
*/
					
					$total_sql="select sum(ot.value) as shipping_total, o.orders_id from " . TABLE_ORDERS . " o," . TABLE_ORDERS_TOTAL . "  ot where ot.orders_id=o.orders_id and ot.value!=0 " . $where . " and ot.class='ot_shipping' and o.orders_status>1 and o.orders_status<5  group by o.orders_status>1";
				}
				
					$total_shipping = 0;
					$total_query=tep_db_query($total_sql);
					$prd_total_array=array('product_type'=>$prd_type,'title'=>$heading,'total'=>0,'total_tax'=>0,'payment'=>array()); 
					$prd_total=0;
					$shipping = 0;
				//	$manualPrice=0;
					
					while($total_result=tep_db_fetch_array($total_query)) {

						$shipping_sql = tep_db_query("select value from ".TABLE_ORDERS_TOTAL." where class='ot_shipping' and orders_id = '".$total_result['orders_id']."'");
						$shipping_result = tep_db_fetch_array($shipping_sql);
												
						$manual_price_query = tep_db_query('select value from '.TABLE_ORDERS_TOTAL.' where class = "ot_adjust" and orders_id="'.$total_result["orders_id"].'"');
					
						$manual_price_result = tep_db_fetch_array($manual_price_query);
					
				//		if($total_result['total_shipping']>0) $total_shipping += $total_result['total_shipping'];
						
																
						if($total_result['shipping_total']){
							//$total_shipping = $total_result['shipping_total'];
						}
						
						$prd_total_array['payment'][get_payment_col($total_result['payment_method'],$total_result['orders_status'])]+=$total_result['total']+$total_result['total_tax'];
												
						$prd_total_array['payment1'][get_payment_col($total_result['payment_method'],$total_result['orders_status'])]+=$total_result['total']+$total_result['total_tax'];
						
						
						
/*						if(isset($total_result['shipping_total']) && $total_result['shipping_total']>0){
							$prd_total_array['payment'][get_payment_col($total_result['payment_method'],$total_result['o.orders_status'])]+=$total_result['shipping_total'];
							$shipping += $total_result['shipping_total'];
						}
						else if(($type!='P') && isset($total_result['total_shipping']) && $total_result['total_shipping']>0){
							$prd_total_array['payment'][get_payment_col($total_result['payment_method'],$total_result['o.orders_status'])]+=$total_result['total_shipping'];
							$shipping += $total_result['total_shipping'];
						}
*/					
						
/*						if($shipping_result['value']>0){
							$prd_total_array['payment'][get_payment_col($total_result['payment_method'],$total_result['order_status'])]+=$total_result['shipping_total'];
							$shipping += $total_result['shipping_total'];
						}
*/					


						// Shipping Price Calculation
				
						if(!isset($shipping_price))	
							$shipping_price = array();

						if(!isset($shipping_payment_price))$shipping_payment_price = array();
						
						if($total_result['shipping_total']){
								$shipping_payment_price[get_payment_col($total_result['payment_method'],$total_result['orders_status'])][$total_result['orders_id']] = $total_result['shipping_total'];
								$shipping_price[$total_result['orders_id']]=$total_result['shipping_total'];
						}
						
						// Shipping Price Calculation

			
						// Manual Price Calculation
				
						if(!isset($manual_price_total[$type]))
							$manual_price_total[$type] = array();
						
						if(!isset($manual_price))	
							$manual_price = array();

						if(!isset($manual_payment_price))$manual_payment_price = array();
						
						if($total_result['manual_price']){
								$manual_payment_price[get_payment_col($total_result['payment_method'],$total_result['orders_status'])][$total_result['orders_id']] = $total_result['manual_price'];
								$manual_price_total[$type][$total_result['orders_id']]=$total_result['manual_price'];
								$manual_price[$total_result['orders_id']]=$total_result['manual_price'];
						}
						
						// Manual Price Calculation

						$prd_total+=$total_result['total']+$total_result['total_tax']; 
						$prd_total_tax+=$total_result['total_tax'];
						$quan+=$total_result["quan"];
						
						$pro_total+=$total_result["pro_total"]+$total_result['tax'];
						
						$pro_gv+=$total_result['gv'];
					} 
					
					$prd_total_array['total']=$prd_total; 
					$prd_total_array['total_tax']=$prd_total_tax;
					$prd_total_array['quan']=$quan;
					$prd_total_array['pro_total']=$pro_total;
					$prd_total_array['gv']=$pro_gv;
					$payment=$prd_total_array['payment'];
					$payment_array[$total_result['orders_id']]=$manual_payment_price;
					$payment1=$prd_total_array['payment1'];
					
					
					if(count($payment[$prd_type])>0)
						ksort($payment[$prd_type]); 
					$grand_unit_price+=$pro_total;
					$grand_quan+=$quan;
					$grand_gv+=$pro_gv;
                    	$grand_total+=$prd_total;
						
/*					if($shipping > 0 ){
                       	$grand_total+=$shipping;
					}
*/					
						
/*					if(is_array($manual_price))$price = array_sum($manual_price);
					else $price = $manual_price;
					$grand_total+=$price;
*/					
					if($tax==1)$grand_total_tax+=$prd_total_tax;
					$grand_payment_array=array();
					
					for($icnt=0;$icnt<count($payment_list);$icnt++) { 
						if($payment_list[$icnt]['on']>0) {
						$grand_payment_array[$icnt]=$payment[$icnt];
						$grand_pay_array[$icnt][]=$payment[$icnt];
						}
					}
					
					if($show_total) {
						if($type!=$prd_type) {
							if($type=='P'){
							?>
								<tr><td class="smalltext" colspan='<?php echo (8+$tax+$payment_col_count) ?>' width=100%>
							<?php } else {?>	
								<tr><td class="smalltext" colspan='<?php echo (7+$tax+$payment_col_count) ?>' width=100%>
							<?php }?> 
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr height="20" style="cursor:hand;cursor:pointer;" onClick="javascript:doChange('<?php echo $prd_type; ?>')">
										<td class="contentTitle" valign="top" width="98%">
											<div style="background:#FFFFFF;padding-right:5px; width:80px;">
												<?php echo $prd_total_array['title'];?>
											</div>
										</td>
										<td width="2%" align="right">
											<!--<a style="cursor:hand;cursor:pointer;" onClick="javascript:doChange('<?php echo $prd_type; ?>')">-->
												<img src="images/template/panel_down.gif" id="img_' . $title . '" border="0">
											<!--</a>-->
										</td>
									</tr>
									<tr height="10">
									<td></td>
									</tr>
								</table>
							</td></tr>
						<tr  style="cursor:pointer;cursor:hand">
							<?php
								$span=($sales_by_order==0)?4:5;
								echo '<td colspan=' . $span . '></td>';
							?>
						<?php 
						} else if($type==$prd_type){
							echo '<br/>';		
						?> 
						<tr >
							<td class="smallText" colspan=<?php $span=3;echo $span;?> align="right"></td>
							<td class="smallText"  nowrap align="right"><b>
								<?php echo $prd_total_array['title'] . '&nbsp;Total';?> </b>
							</td>
							<?php if($sales_by_order==0){?>	
								<td class="dataTableContent" align="right" >
								<?php 
									if($pro_total) echo $currencies->format($pro_total);
								?>
								</td>
								<td class="dataTableContent" align="right" >
									<?php if($total_shipping=='')echo $quan; ?>
								</td>
								<?php if($type=='P'){?>
								<td class="dataTableContent" align="right" >
									<?php if(!$pro_gv) echo $currencies->format($pro_gv);?>
								</td>
								<?php } 
							} else{?>
								<td></td>
							<?php }?>
							<td class="dataTableContent" align="right" >
								<?php 
									if($prd_total)echo $currencies->format($prd_total);
									if($total_shipping)echo $currencies->format($total_shipping);
								?>
							</td>
							<?php 
								if($tax==1){
									echo '<td class="dataTableContent" align="right" >';
									if($prd_total_tax)echo $currencies->format($prd_total_tax);
									echo '</td>';
								}
								for($icnt=0;$icnt<count($payment_list);$icnt++) { 
									if($payment_list[$icnt]['on']>0) { 	?>
									<td class="dataTableContent" align="right" >
										<?php if($payment1[$icnt]>0) {
											echo $currencies->format($payment1[$icnt]); 
										}		
										?>
									</td>
									<?php 
									}
								} //display the Total
								?>
							<td class="dataTableContent" align="right" >
								<?php 
									if(is_array($manual_price_total))$price = array_sum($manual_price_total[$type]);
									if($price < 0){
										$price = (-1 * $price);
										echo '(-)' . $currencies->format($price);
									}else{
										echo $currencies->format($price);
									}	
								?>
							</td>
							<?php	
							}
						}
						?>	
						</tr>	
					<?php 		
				} 
				function get_grand_total($grand_total,$grand_total_tax,$grand_pay_array,$grand_unit_price,$grand_quan,$grand_gv) {
					global $where, $prd_where,$manual_price,$manual_payment_price,$payment_array, $payment_list,$type, $payment_count, $currencies, $grand_total,$grand_total_tax,$grand_pay_array,$tax,$summary,$sales_by_order,$grand_unit_price,$grand_quan,$grand_gv,$manualPrice, $shipping_price, $shipping_payment_price, $shipping_array;
									
					// Manual Price Calculation
					if(is_array($manual_price)){
						$grand_total+=array_sum($manual_price);
					}
					// Manual Price Calculation
			//	print_r($shipping_price);	
					if(is_array($shipping_price)){
						$grand_total+=array_sum($shipping_price);
					}
					
					?>
					<br>
					<tr class=" cell_bg_report_header ">
						<?php
							$span=($sales_by_order==0)?4:5;
							echo '<td align="right" class="dataTableHeadingContent" colspan=' .$span . '></td>';
							if($sales_by_order==0){
						?>
								<td class="dataTableHeadingTitleContent"  width="5%" align="right"><?php echo TEXT_SOLD;?></td>
								<?php if($type=='P'){ ?>
									<td class="dataTableHeadingTitleContent" width="8%" align="center"><?php echo TEXT_GIFT_VOUCHER;?></td>
								<?php }
							} 
							echo ('<td class="dataTableHeadingTitleContent"  align="right" width="8%">'. TEXT_TOTAL.'</td>');
							if($tax==1){?>
								<td class="dataTableHeadingTitleContent" align="right" width="10%"><?php echo TEXT_TAX;?></td><?php
							}
							for ($icnt=0;$icnt<$payment_count;$icnt++){
								if ($payment_list[$icnt]["on"]==0) continue;
								if(substr($payment_list[$icnt]["name"],0,4)=='Cash') $name='Cash';
								else{
									$name=(strlen($payment_list[$icnt]["name"])>6?substr($payment_list[$icnt]["name"],0,6):$payment_list[$icnt]["name"]);
								}
								if(substr($payment_list[$icnt]["name"],0,11)=='Credit Card' && strpos($payment_list[$icnt]["name"],"img")>0)	
									$payment_list[$icnt]["name"]='Credit Cart'; 
								echo '<td class="dataTableHeadingTitleContent" align="right" title= $payment_list[$icnt]["name"]> ' . $name . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
							}
							//echo "<td width='40' class='dataTableHeadingTitleContent' align='right'>Price Adjustment</td>";
							echo '</tr>';?>
							
					</tr>
					<?php
						echo '<tr  class="dataTableHeadingTotal">';
						$span=($sales_by_order==0)?3:4;
						echo '<td align="right" class="dataTableHeadingContent" colspan=' .$span . '></td>';
						echo '<td  class="dataTableHeadingContent"align=right ><b>Grand Total</b></td>';
						if($sales_by_order==0){
							//decho '<td  class="dataTableHeadingContent"  align="right">' . (($grand_unit_price)?$currencies->format($grand_unit_price):'') . '</td>' ;
							echo '<td  class="dataTableHeadingContent" align="right">' . (($grand_quan)?$grand_quan:'') . '</td>' ;
							if($type=='P'){
								echo '<td  class="dataTableHeadingContent" align="right">' .  (($grand_quan)?$currencies->format($grand_gv):''). '</td>' ;
							}	
						}
						echo '<td  class="dataTableHeadingContent"  align="right">' . (($grand_total)?$currencies->format($grand_total):'') . '</td>' ;
						if($tax==1)	echo '<td class="dataTableHeadingContent"  align="right">' . (($grand_total_tax)?$currencies->format($grand_total_tax):'') . '</td>';
												
						
						for($icnt=0;$icnt<count($payment_list);$icnt++) {
							if($payment_list[$icnt]['on']==1) {
								$pay_total = 0;
																
								// Manual Price Calculation
								if(is_array($manual_payment_price[$icnt]))
									$value = array_sum(($manual_payment_price[$icnt]));
								$pay_total+=$value;

								// Manual Price Calculation
					
								if(is_array($shipping_payment_price[$icnt])){
									$shipping_value = array_sum(($shipping_payment_price[$icnt]));
									$pay_total+=$shipping_value;
								}	
								
								for($jcnt=0;$jcnt<count($grand_pay_array[$icnt]);$jcnt++) {
									$pay_total+=(float)$grand_pay_array[$icnt][$jcnt];
								}
								echo '<td class="dataTableHeadingContent"  align="right">' . (($pay_total)?$currencies->format($pay_total):'') .  '&nbsp;&nbsp;&nbsp;&nbsp;</td>';
							}
						}
						//echo '<td class="dataTableHeadingContent"  align="right">'.$currencies->format($manualPrice).'</td>';
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
				function print_total_row($unit_subtotal,$all_subtotal,$all_subtax,$quan_subtotal,$all_gv=0,$all_manual_price=0,$prt=''){
					global $payment_list,$payment_count,$currencies,$prd_sub_total,$type,$tax,$summary,$sales_by_order,$sort_manufact,$payment_col_count,$manualPrice;
					echo "<tr>";
					if($type=='H'){
						if($sales_by_order==1){
							if($tax)
								$colspan=7+$payment_col_count-3+1;
							else
								$colspan=6+$payment_col_count-3+1;
						}
						else{
							if($tax)
								$colspan=8+$payment_col_count-3+1;
							else
								$colspan=7+$payment_col_count-3+1;
						}
					
						if($payment_col_count>2)
							echo '<td class="smallText" align="right" colspan=' . (6+$payment_col_count-4) . '><b>' . TEXT_SUBTOTAL . '</b></td>';
						else
							echo '<td class="smallText" align="right" colspan=5><b>' . TEXT_SUBTOTAL . '</b></td>';
						echo '<td class="smallText" align="right" width="5%">' . number_format($quan_subtotal,2) . '</td>';
						echo '<td class="smallText" align="right" width="10%">' . $currencies->format($unit_subtotal) . '</td></tr>';
						display_total(false,$type);
						return;
					}
					if($sales_by_order==1 && $summary!=1){
						echo '<td class="smallText" align="right" colspan=3></td><td align=right class=smalltext><b>'. TEXT_SUBTOTAL .'</b></td>';
					}else if($sales_by_order==1 && $summary==1) echo '<td colspan=3 class="smallText" align="right"</td><td  class=smalltext align=right><b>'. TEXT_SUBTOTAL .'</b></td>';
					if($sales_by_order==0 && $summary!=1) echo "<td  colspan=3 >&nbsp;</td><td align=right  class='smalltext'><b>". TEXT_SUBTOTAL ."</b></td>";
					else if($sales_by_order==0 && $summary==1 && $sort_manufact==1){ 
						echo '<td  colspan=3>&nbsp;</td><td  class="smallText" align="right"><b>'. TEXT_SUBTOTAL .'</b></td>'; 
					}else if(($sales_by_order==0 && $summary==1 && $sort_manufact==0)) {
						echo '<td colspan=3>&nbsp;</td><td  class="smallText" align="right"><b>'. TEXT_SUBTOTAL .'</b></td>'; 
					} 
					if(($sales_by_order==0 && $summary==1) || (($sales_by_order==0 && $summary==0))){ 
						if($summary==1){  
							echo '<td  class="smallText" colspan="2" align="right">'. $quan_subtotal .'</td>';
							if($type=='P') 
							echo '<td  class="smallText"  align="right">'.  $currencies->format($all_gv) .'</td>';
						}else {
							echo '<td  class="smallText"  colspan="2" align="right">'. $quan_subtotal .'</td>';
							if($type=='P') 
							echo '<td  class="smallText"  align="right">'.  $currencies->format($all_gv) . '</td>';
						}
					}else if($sales_by_order){ 
						//echo '<td  class="smallText" align="right">' . $currencies->format($unit_subtotal) .'</td>';
						echo '<td  class="smallText" align="right"></td>';
					}
					echo '<td  class="smallText"  align="right">'. $currencies->format($all_subtotal) .'</td>';
					if($tax==1){
						echo '<td  class="smallText"  align="right">'. $currencies->format($all_subtax) .'</td>';
					} 
					$pay_count =1;  
					for ($jcnt=0;$jcnt<$payment_count;$jcnt++){
						if ($payment_list[$jcnt]["on"]==0) continue;
						if ($payment_list[$jcnt]['subtotal']>0){
							$prd_sub_total+=$payment_list[$jcnt]['subtotal'];
							$cnt_payment++;
							echo '<td class="dataTableContent" align="right" >' .  $currencies->format($payment_list[$jcnt]['subtotal']) .'</td>';
							$pay_count++;
						} else {
							echo '<td class="dataTableContent" align="right" >&nbsp;</td>';
						}
						$payment_list[$jcnt]['subtotal']=0;
					}
					if($all_manual_price < 0){
						$all_manual_price = (-1 * $all_manual_price);
						echo "<td class='dataTableContent' align='right' > (-)".$currencies->format($all_manual_price)."</td>";
					}else{
						echo "<td class='dataTableContent' align='right' >".$currencies->format($all_manual_price)."</td>";
					}
					echo '</tr>';
					if($prt!="") {	
						display_total(true,$type);
					} 
				}
				function print_total_row_excel($unit_subtotal,$all_subtotal,$quan_subtotal,$all_gv=0,$all_manual_price=0,$prt="",$type,&$unit_tax,$all_tax=""){
					global $grand_unit_price,$grand_total,$grand_quan,$grand_gv,$grand_total_tax,$grand_pay_array,$payment_list,$payment_count,$currencies,$where,$prd_where,$tax,$sales_by_order,$temp_type,$manualPrice,$grand_payment_array,$manual_payment_price,$manual_price, $shipping_price, $shipping_payment_price;
					$payment=array();
					if($prt=="prt" || $prt==""){
						if($type=='H'){
							$result=sprintf(",,,,\"%s\",\"%s\",\"%s\"," , TEXT_SUBTOTAL ,number_format($quan_subtotal,2),$currencies->format($unit_subtotal));
							return $result;
						}
						if($sales_by_order==1){
							if($tax==1){
								$result=sprintf(",,,,\"%s\",\"%s\",\"%s\"," , TEXT_SUBTOTAL ,$currencies->format($all_subtotal),$currencies->format($unit_tax));
							}else{
								$result=sprintf(",,,,\"%s\",\"%s\"," , TEXT_SUBTOTAL ,$currencies->format($all_subtotal));
							}
						}else{
							if($temp_type=='P'){
								if($tax==1){
									$result=sprintf(",,,,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"," , TEXT_SUBTOTAL ,$currencies->format($unit_subtotal), $quan_subtotal ,$currencies->format($all_gv),$currencies->format($all_subtotal),$currencies->format($unit_tax));
								}else{
									$result=sprintf(",,,,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"," , TEXT_SUBTOTAL , $currencies->format($unit_subtotal), $quan_subtotal ,$currencies->format($all_gv),$currencies->format($all_subtotal));
								}
							} else {
								if($tax==1){
									$result=sprintf(",,,,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"," , TEXT_SUBTOTAL ,$currencies->format($unit_subtotal), $quan_subtotal ,$currencies->format($all_subtotal),$currencies->format($unit_tax));
								}else{
									$result=sprintf(",,,,\"%s\",\"%s\",\"%s\",\"%s\"," , TEXT_SUBTOTAL , $currencies->format($unit_subtotal), $quan_subtotal ,$currencies->format($all_subtotal));
								}
							}	
						}
						for ($jcnt=0;$jcnt<$payment_count;$jcnt++){
							if ($payment_list[$jcnt]["on"]==0) continue;
							if ($payment_list[$jcnt]['subtotal']>0){
								$result.=sprintf("\"%s\",",$currencies->format($payment_list[$jcnt]['subtotal']));
							} else {
								$result.=',';
							}
							$payment_list[$jcnt]['subtotal']=0;
						}
						if($all_manual_price < 0){
							$all_manual_price = (-1 * $all_manual_price);
							$result.=sprintf("\"%s\",","(-)".$currencies->format($all_manual_price));
						}else{
							$result.=sprintf("\"%s\",",$currencies->format($all_manual_price));
						}
						$result.="\n";
					}
					if($prt=="prt" || $prt=="prt_type_total") {
							//$result.="\n";
							if($sales_by_order==1){
								if($type=='P'){
								
									$total_sql="SELECT sum(ot1.value) as orders_total,ot3.value as shipping_total,sum(ot2.value) as gv, ot4.value as manual_price,op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity*op.final_price) as total,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax,sum((op.final_price*op.products_tax)/100) as tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,sum(op.products_quantity) as total_quan,sum(op.final_price) as unit_subtotal,op.products_tax from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot1 on op.orders_id=ot1.orders_id and ot1.class='ot_total' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot2 on op.orders_id=ot2.orders_id and ot2.class='ot_gv' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot3 on op.orders_id=ot3.orders_id and ot3.class='ot_shipping' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot4 on op.orders_id=ot4.orders_id and ot4.class='ot_adjust' where o.orders_id=op.orders_id  " . $where . $prd_where . " and op.products_type='" . $type . "' and op.orders_products_status>1 and op.orders_products_status<5 and op.orders_products_status!=5 group by orders_id order by op.products_name";
								}elseif($type=='H'){
									$total_sql = "select ot.value as total_shipping from ".TABLE_ORDERS_TOTAL." ot, ".TABLE_ORDERS." o, ".TABLE_PRODUCTS." p, ".TABLE_ORDERS_PRODUCTS." op where op.orders_products_status>1 and op.orders_products_status<5 and p.products_id=op.products_id and o.orders_id=op.orders_id and ot.orders_id=o.orders_id and ot.class = 'ot_shipping' group by o.orders_id order by o.orders_id";
								}else{
									$total_sql="SELECT op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity*op.final_price) as total,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax,ot4.value as manual_price, ot3.value as shipping_total, sum((op.final_price*op.products_tax)/100) as tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,sum(op.products_quantity) as total_quan,sum(op.final_price) as unit_subtotal,op.products_tax from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot4 on op.orders_id=ot4.orders_id and ot4.class='ot_adjust' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot3 on op.orders_id=ot3.orders_id and ot3.class='ot_shipping' where o.orders_id=op.orders_id  " . $where .  " and op.products_type='" . tep_db_input($type) . "' and op.orders_products_status>1 and op.orders_products_status<5 group by payment_method order by op.products_name";
								}
							}	
							else {
								if($type=='P'){
									$total_sql="SELECT sum(ot1.value) as orders_total,ot3.value as shipping_total,sum(ot2.value) as gv,op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity*op.final_price) as total,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax,sum((op.final_price*op.products_tax)/100) as tax,ot4.value as manual_price, o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,sum(op.products_quantity) as total_quan,sum(op.final_price) as unit_subtotal,op.products_tax from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot1 on op.orders_id=ot1.orders_id and ot1.class='ot_total' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot2 on op.orders_id=ot2.orders_id and ot2.class='ot_gv' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot3 on op.orders_id=ot3.orders_id and ot3.class='ot_shipping' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot4 on op.orders_id=ot4.orders_id and ot4.class='ot_adjust' where o.orders_id=op.orders_id  " . $where . $prd_where . " and op.products_type='" . $type . "' and op.orders_products_status>1 and op.orders_products_status<5 and op.orders_products_status!=5 group by orders_id order by op.products_name";
								}elseif($type=='H'){
									$total_sql = "select ot.value as total_shipping from ".TABLE_ORDERS_TOTAL." ot, ".TABLE_ORDERS." o, ".TABLE_PRODUCTS." p, ".TABLE_ORDERS_PRODUCTS." op where op.orders_products_status>1 and op.orders_products_status<5 and p.products_id=op.products_id and o.orders_id=op.orders_id and ot.orders_id=o.orders_id and ot.class = 'ot_shipping' group by o.orders_id order by o.orders_id";
								}else{
									$total_sql="SELECT op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity*op.final_price) as total,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax,ot4.value as manual_price, ot3.value as shipping_total, sum((op.final_price*op.products_tax)/100) as tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,sum(op.products_quantity) as total_quan,sum(op.final_price) as unit_subtotal,op.products_tax from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot4 on op.orders_id=ot4.orders_id and ot4.class='ot_adjust' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot3 on op.orders_id=ot3.orders_id and ot3.class='ot_shipping' where o.orders_id=op.orders_id  " . $where . $prd_where . " and op.products_type='" . tep_db_input($type) . "' and op.orders_products_status>1 and op.orders_products_status<5 and op.orders_products_status!=5 group by orders_id order by op.products_name";
								}	
							}	
								
							$total_query=tep_db_query($total_sql);
							$shipping = 0;
					//		$manual_price = 0;
							while($total_result=tep_db_fetch_array($total_query)) {
								$manual_price_query = tep_db_query('select value from '.TABLE_ORDERS_TOTAL.' where class = "ot_adjust" and orders_id="'.$total_result["orders_id"].'"');
								$manual_price_result = tep_db_fetch_array($manual_price_query);
								
								if($total_result['total_shipping']>0) $total_shipping += $total_result['total_shipping'];
								
								$prd_unit_price+=$total_result['unit_subtotal']+$total_result['tax']; 
								$prd_quan+=$total_result['total_quan'];
								$prd_total+=$total_result['total']+$total_result['total_tax'];
								$prd_tax+=$total_result['total_tax'];
								$prd_gv+=$total_result['gv'];
								$payment[get_payment_col($total_result['payment_method'],$total_result['order_status'])]+=$total_result['total']+$total_result['total_tax'];
/*								if($total_result['shipping_total']>0){
									$payment[get_payment_col($total_result['payment_method'],$total_result['order_status'])]+=$total_result['shipping_total'];
									$shipping += $total_result['shipping_total'];
								}	
*/								
								
								// Shipping Price Calculation
								if(!isset($shipping_price))$shipping_price=array();
								if(!isset($shipping_payment_price))$shipping_payment_price = array();
								
								if($total_result['shipping_total']){
									$shipping_payment_price[get_payment_col($total_result['payment_method'],$total_result['orders_status'])][$total_result['orders_id']] = $total_result['shipping_total'];
									$shipping_price[$total_result['orders_id']]=$total_result['shipping_total'];
								}
								
								// Shipping Price Calculation
								
								
								
								// Manual Price Calculation
								if(!isset($manual_price))$manual_price=array();
								if(!isset($manual_payment_price))$manual_payment_price = array();
								
		//						print_r($total_result);
								
								if($total_result['manual_price']){
									$manual_payment_price[get_payment_col($total_result['payment_method'],$total_result['orders_status'])][$total_result['orders_id']] = $total_result['manual_price'];
									$manual_price[$total_result['orders_id']]=$total_result['manual_price'];
								}
								
								// Manual Price Calculation
								
								
/*								if($manual_price_result['value']){
									$payment[get_payment_col($total_result['payment_method'],$total_result['order_status'])]+=$manual_price_result['value'];
									$manual_price += $manual_price_result['value'];
									$manualPrice+=$manual_price_result['value'];
								}
*/							}
							
							if(count($payment)>0)	
								ksort($payment);
							$heading=get_heading($type);
							if($sales_by_order==1){
								if($tax==1){
									$result.=sprintf(",\"%s\",,,,\"%s\",\"%s\",", $heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,$currencies->format($prd_total),$currencies->format($prd_tax)); 
								}else{
/*									$result.=sprintf(",\"%s\",,,,\"%s\",", $heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,(($type=='H')?$currencies->format($total_shipping):$currencies->format($prd_total))); 
*/							
									$result.=sprintf(",\"%s\",,,,\"%s\",", $heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,(($type=='H')?$currencies->format(array_sum($shipping_price)):$currencies->format($prd_total))); 

								}
							}else{
								if($temp_type=='P'){
									if($tax==1){
										$result.=sprintf(",\"%s\",,,,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",",$heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,$currencies->format($prd_unit_price),$prd_quan,$currencies->format($prd_gv),$currencies->format($prd_total),$currencies->format($prd_tax)); 
									}else{
/*										$result.=sprintf(",\"%s\",,,,\"%s\",\"%s\",\"%s\",\"%s\",",$heading . ' ' . ($prt=='prt'?TEXT_TOTAL:''),$currencies->format($prd_unit_price),$prd_quan, $currencies->format($prd_gv),(($type=='H')?$currencies->format($total_shipping):$currencies->format($prd_total))); 
*/									
										$result.=sprintf(",\"%s\",,,,\"%s\",\"%s\",\"%s\",\"%s\",",$heading . ' ' . ($prt=='prt'?TEXT_TOTAL:''),$currencies->format($prd_unit_price),$prd_quan, $currencies->format($prd_gv),(($type=='H')?$currencies->format(array_sum($shipping_price)):$currencies->format($prd_total))); 

									}
								} else {
									if($tax==1){
										$result.=sprintf(",\"%s\",,,,\"%s\",\"%s\",\"%s\",\"%s\",",$heading . ' ' . ($prt=='prt'?TEXT_TOTAL:'') ,$currencies->format($prd_unit_price),$prd_quan,$currencies->format($prd_total),$currencies->format($prd_tax)); 
									}else{
/*										$result.=sprintf(",\"%s\",,,,\"%s\",\"%s\",\"%s\",",$heading . ' ' . ($prt=='prt'?TEXT_TOTAL:''),$currencies->format($prd_unit_price),$prd_quan,(($type=='H')?$currencies->format($total_shipping):$currencies->format($prd_total))); 
*/								
										$result.=sprintf(",\"%s\",,,,\"%s\",\"%s\",\"%s\",",$heading . ' ' . ($prt=='prt'?TEXT_TOTAL:''),$currencies->format($prd_unit_price),$prd_quan,(($type=='H')?$currencies->format(array_sum($shipping_price)):$currencies->format($prd_total))); 
									}
								}
							}
							for($icnt=0;$icnt<count($payment_list);$icnt++) { 
								if($payment_list[$icnt]['on']>0) {
									$result.=sprintf("\"%s\",", $currencies->format($payment[$icnt]));
								} 
							}
							/*if($manual_price < 0){
								$manual_price = (-1 * $manual_price);
								$result.=sprintf("\"%s\",","(-)".$currencies->format($manual_price));
							}else{
								$result.=sprintf("\"%s\",",$currencies->format($manual_price));
							}*/
						
							$grand_gv+=$prd_gv;
							$grand_unit_price+=$prd_unit_price;
							$grand_quan+=$prd_quan;
							$grand_total+=$prd_total;

/*							if($shipping > 0 ){
								$grand_unit_price+=$shipping;
								$grand_total+=$shipping;
							}	
*//*							if($manual_price){
								$grand_unit_price+=$manual_price;
								$grand_total+=$manual_price;
							}
*/							
							if($tax==1)$grand_total_tax+=$prd_tax;
							$grand_payment_array=array();
							for($icnt=0;$icnt<count($payment_list);$icnt++) { 
								if($payment_list[$icnt]['on']>0) { 
									$grand_payment_array[$icnt]=$payment[$icnt];
									$grand_pay_array[$icnt][]=$payment[$icnt];
								}
							}
						}elseif($prt=='prt_grand_total'){
							if(is_array($manual_price)){
								$grand_total+=array_sum($manual_price);
								$grand_unit_price+=array_sum($manual_price);
						    }else{
								$grand_total+=$manual_price;
								$grand_unit_price+=$manual_price;
							}
							if(is_array($shipping_price)){
								$grand_total+=array_sum($shipping_price);
								$grand_unit_price+=array_sum($shipping_price);
						    }else{
								$grand_total+=$shipping_price;
								$grand_unit_price+=$shipping_price;
							}
							
							if($temp_type!='H') {
								if($sales_by_order==1){
									if($tax==1){
										$result.=sprintf(",\"%s\",,,,\"%s\",\"%s\",", 'Grand Total' ,$currencies->format($grand_total),$currencies->format($grand_total_tax)); 
									}else{
										$result.=sprintf(",\"%s\",,,,\"%s\",", 'Grand Total' ,$currencies->format($grand_total)); 
									}
								}else{
									if($temp_type=='P'){
										if($tax==1){
											$result.=sprintf(",\"%s\",,,,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",",'Grand Total' ,$currencies->format($grand_unit_price),$grand_quan,$currencies->format($grand_gv),$currencies->format($grand_total),$currencies->format($grand_total_tax)); 
										}else{
											$result.=sprintf(",\"%s\",,,,\"%s\",\"%s\",\"%s\",\"%s\",",'Grand Total',$currencies->format($grand_unit_price),$grand_quan,$currencies->format($grand_gv),$currencies->format($grand_total)); 
										}
									} else {
										if($tax==1){
											$result.=sprintf(",\"%s\",,,,\"%s\",\"%s\",\"%s\",\"%s\",",'Grand Total' ,$currencies->format($grand_unit_price),$grand_quan,$currencies->format($grand_total),$currencies->format($grand_total_tax)); 
										}else{
											$result.=sprintf(",\"%s\",,,,\"%s\",\"%s\",\"%s\",",'Grand Total',$currencies->format($grand_unit_price),$grand_quan,$currencies->format($grand_total)); 
										}
									}	
								}
								for($icnt=0;$icnt<count($payment_list);$icnt++) {
									if($payment_list[$icnt]['on']==1) {
										$pay_total=0;
									
										// Manual Price Calculation
										if(is_array($manual_payment_price[$icnt]))
											$value = array_sum(($manual_payment_price[$icnt]));
										$pay_total+=$value;
										// Manual Price Calculation
										
										// Shipping Price Calculation
										if(is_array($shipping_payment_price[$icnt])){
											$value = array_sum(($shipping_payment_price[$icnt]));
											$pay_total+=$value;
										}	
										//Shipping Price Calculation
										
									
										for($jcnt=0;$jcnt<count($grand_pay_array[$icnt]);$jcnt++) {
											$pay_total+=(float)$grand_pay_array[$icnt][$jcnt];
										}
										$result.=sprintf("\"%s\",", (($pay_total)?$currencies->format($pay_total):''));
									}
								}
								/*if($manualPrice < 0){
									$manualPrice = (-1 * $manualPrice);
									$result.=sprintf("\"%s\",","(-)".$currencies->format($manualPrice));
								}else{
									$result.=sprintf("\"%s\",",$currencies->format($manualPrice));
								}*/
							}
						}
						$unit_subtotal=0;
						$all_subtotal=0;
						$quan_subtotal=0;
						$unit_tax=0;
						$all_tax=0;
						$all_gv=0;
						$all_manual_price=0;
						return $result;
					}
					function print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_gv=0,$all_manual_price=0,$table,$widths,$type,$prt="",$unit_tax,$all_tax=""){ 
						global $grand_unit_price,$grand_total,$grand_quan,$grand_gv,$grand_total_tax,$grand_pay_array,$payment_list,$payment_count,$currencies, $where, $prd_where,$tax,$sales_by_order,$temp_type,$manualPrice,$grand_payment_array,$manual_payment_price,$manual_price, $shipping_price, $shipping_payment_price; 
						$payment=array();
						if($prt=="prt" || $prt==""){ 
							if($type=='H'){
								$cols[]=array("text"=>TEXT_SUBTOTAL,"width"=>$widths[0]+$widths[1]+$widths[2]+$widths[3]+$widths[4],"align"=>"R","style"=>"headrow","valign"=>"M");
								$cols[]=array("text"=>number_format($quan_subtotal,2),"width"=>$widths[5],"align"=>"R","style"=>"headrow","valign"=>"M");
								$cols[]=array("text"=>$currencies->format($unit_subtotal),"width"=>$widths[6],"align"=>"R","style"=>"headrow","valign"=>"M");
								$table->OutputRow($cols,18);
								unset($cols);
								return;
							}
						
							$cols[]=array("text"=>TEXT_SUBTOTAL,"width"=>$widths[0]+$widths[1]+$widths[2]+$widths[3]+$widths[4],"align"=>"R","style"=>"subrow","valign"=>"M");
							if(!$sales_by_order){
								$cols[]=array("text"=>$currencies->format($unit_subtotal),"width"=>$widths[5],"align"=>"R","style"=>"subrow","valign"=>"M");
								$cols[]=array("text"=>$quan_subtotal,"width"=>$widths[6],"align"=>"R","style"=>"subrow","valign"=>"M");
								if($type=='P')
								$cols[]=array("text"=>$currencies->format($all_gv),"width"=>$widths[7],"align"=>"R","style"=>"subrow","valign"=>"M");
							}else {
								$cols[]=array("text"=>'',"width"=>$widths[6],"align"=>"R","style"=>"subrow","valign"=>"M");
							}
							$cols[]=array("text"=>$currencies->format($all_subtotal),"width"=>$widths[8],"align"=>"R","style"=>"subrow","valign"=>"M");
							if($tax)$cols[]=array("text"=>$currencies->format($unit_tax),"width"=>$widths[9],"align"=>"R","style"=>"subrow","valign"=>"M");
							for ($jcnt=0;$jcnt<$payment_count;$jcnt++){
								if ($payment_list[$jcnt]["on"]==0) continue;  
								if ($payment_list[$jcnt]['subtotal']>0){
									$all_sub_total+=$payment_list[$jcnt]['subtotal'];
									if($payment_list[$jcnt]['name']=='Bank Transfer Payment')
										$all_pdf_bank_total+=$payment_list[$jcnt]['subtotal'];
									if($payment_list[$jcnt]['name']=='WestPac Payment')
										$all_pdf_west_total+=$payment_list[$jcnt]['subtotal'];	
									$cols[]=array("text"=>$currencies->format($payment_list[$jcnt]['subtotal']),"width"=>60,"align"=>"R","style"=>"subrow","valign"=>"M");
								} else {
									$cols[]=array("text"=>'',"width"=>60,"align"=>"R","style"=>"subrow","valign"=>"M");
								}
								if(!$sales_by_order)
									$payment_list[$jcnt]['subtotal']=0;
							} 
							if($all_manual_price < 0){
								$all_manual_price = (-1 * $all_manual_price);
								$cols[]=array("text"=>"(-)".$currencies->format($all_manual_price),"width"=>'100',"align"=>"R","style"=>"subrow");
							}else{
								$cols[]=array("text"=>$currencies->format($all_manual_price),"width"=>'100',"align"=>"R","style"=>"subrow");
							}
							$table->OutputRow($cols,18);
							unset($cols);
						}
						if($prt=="prt" || $prt=="prt_type_total") {
							unset($cols);
							if($sales_by_order==1){
								if($type=='P'){
								//tep_db_query("SET OPTION SQL_BIG_SELECTS=1"); // CARTZONE added
									$total_sql="SELECT sum(ot1.value) as orders_total,ot3.value as shipping_total,sum(ot2.value) as gv,ot4.value as manual_price, op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity*op.final_price) as total,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax,sum((op.final_price*op.products_tax)/100) as tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,op.products_tax,sum(op.final_price) as unit_price,sum(op.products_quantity) as quan from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot1 on op.orders_id=ot1.orders_id and ot1.class='ot_total' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot2 on op.orders_id=ot2.orders_id and ot2.class='ot_gv' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot3 on op.orders_id=ot3.orders_id and ot3.class='ot_shipping' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot4 on op.orders_id=ot4.orders_id and ot4.class='ot_adjust' where o.orders_id=op.orders_id  " . $where . $prd_where . " and op.products_type='" . tep_db_input($type) . "' and op.orders_products_status>1 and op.orders_products_status<5 group by orders_id order by op.products_name";
								}elseif($type=='H'){
								//tep_db_query("SET OPTION SQL_BIG_SELECTS=1"); // CARTZONE added
									$total_sql = "select ot.value as total_shipping from ".TABLE_ORDERS_TOTAL." ot, ".TABLE_ORDERS." o, ".TABLE_PRODUCTS." p, ".TABLE_ORDERS_PRODUCTS." op where op.orders_products_status>1 and op.orders_products_status<5 and p.products_id=op.products_id and o.orders_id=op.orders_id and ot.orders_id=o.orders_id and ot.class = 'ot_shipping' group by o.orders_id order by o.orders_id";
								}else{
								//tep_db_query("SET OPTION SQL_BIG_SELECTS=1"); // CARTZONE added
									$total_sql="SELECT op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity*op.final_price) as total,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax,sum((op.final_price*op.products_tax)/100) as tax, ot4.value as manual_price,ot3.value as shipping_total, o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,op.products_tax,sum(op.final_price) as unit_price,sum(op.products_quantity) as quan from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot4 on op.orders_id=ot4.orders_id and ot4.class='ot_adjust' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot3 on op.orders_id=ot3.orders_id and ot3.class='ot_shipping' where o.orders_id=op.orders_id  " . $where . " and op.products_type='" . tep_db_input($type) . "' and op.orders_products_status>1 and op.orders_products_status<5 group by orders_id order by op.products_name";
								}
							}
							else {
								if($type=='P'){
							//	tep_db_query("SET OPTION SQL_BIG_SELECTS=1"); // CARTZONE added
									$total_sql="SELECT sum(ot1.value) as orders_total,ot3.value as shipping_total,sum(ot2.value) as gv,op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity*op.final_price) as total,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax,sum((op.final_price*op.products_tax)/100) as tax,ot4.value as manual_price, o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,op.products_tax,sum(op.final_price) as unit_price,sum(op.products_quantity) as quan from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot1 on op.orders_id=ot1.orders_id and ot1.class='ot_total' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot2 on op.orders_id=ot2.orders_id and ot2.class='ot_gv' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot3 on op.orders_id=ot3.orders_id and ot3.class='ot_shipping' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot4 on op.orders_id=ot4.orders_id and ot4.class='ot_adjust' where o.orders_id=op.orders_id  " . $where . $prd_where . " and op.products_type='" . tep_db_input($type) . "' and op.orders_products_status>1 and op.orders_products_status<5 group by orders_id order by op.products_name";
								}elseif($type=='H'){
									$total_sql = "select ot.value as total_shipping from ".TABLE_ORDERS_TOTAL." ot, ".TABLE_ORDERS." o, ".TABLE_PRODUCTS." p, ".TABLE_ORDERS_PRODUCTS." op where op.orders_products_status>1 and op.orders_products_status<5 and p.products_id=op.products_id and o.orders_id=op.orders_id and ot.orders_id=o.orders_id and ot.class = 'ot_shipping' group by o.orders_id order by o.orders_id";
								}else{
							//	tep_db_query("SET OPTION SQL_BIG_SELECTS=1"); // CARTZONE added
									$total_sql="SELECT op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity*op.final_price) as total,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax,sum((op.final_price*op.products_tax)/100) as tax, ot4.value as manual_price, ot3.value as shipping_total, o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,op.products_tax,sum(op.final_price) as unit_price,sum(op.products_quantity) as quan from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot4 on op.orders_id=ot4.orders_id and ot4.class='ot_adjust' LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot3 on op.orders_id=ot3.orders_id and ot3.class='ot_shipping' where o.orders_id=op.orders_id  " . $where . $prd_where . " and op.products_type='" . tep_db_input($type) . "' and op.orders_products_status>1 and op.orders_products_status<5 group by orders_id order by op.products_name";
								}	
							}	//echo $total_sql;
							$total_query=tep_db_query($total_sql);
							$shipping = 0;
							$total_shipping = 0;
						//	$manual_price = 0;
							while($total_result=tep_db_fetch_array($total_query)) {
								$manual_price_query = tep_db_query('select value from '.TABLE_ORDERS_TOTAL.' where class = "ot_adjust" and orders_id="'.$total_result["orders_id"].'"');
								$manual_price_result = tep_db_fetch_array($manual_price_query);
								
								if($total_result['total_shipping']>0) $total_shipping += $total_result['total_shipping'];

								$prd_unit_subtotal+=$total_result['unit_price']+$total_result['tax'];
								$prd_quan_subtotal+=$total_result['quan'];
								$prd_total+=$total_result['total']+$total_result['total_tax'];
								$prd_tax+=$total_result['total_tax'];
								$prd_gv+=$total_result['gv'];
								
								$payment[get_payment_col($total_result['payment_method'],$total_result['orders_status'])]+=$total_result['total']+$total_result['total_tax'];
/*								if($total_result['shipping_total']>0){
									$payment[get_payment_col($total_result['payment_method'],$total_result['orders_status'])]+=$total_result['shipping_total'];
									$shipping += $total_result['shipping_total'];
								}	
*/								
						
								// Shipping Price Calculation
								if(!isset($shipping_price))$shipping_price=array();
								if(!isset($shipping_payment_price))$shipping_payment_price = array();
								
		//						print_r($total_result);
								
								if($total_result['shipping_total']){
									$shipping_payment_price[get_payment_col($total_result['payment_method'],$total_result['orders_status'])][$total_result['orders_id']] = $total_result['shipping_total'];
									$shipping_price[$total_result['orders_id']]=$total_result['shipping_total'];
								}
								
								// Manual Price Calculation
	
						
								// Manual Price Calculation
								if(!isset($manual_price))$manual_price=array();
								if(!isset($manual_payment_price))$manual_payment_price = array();
								
		//						print_r($total_result);
								
								if($total_result['manual_price']){
									$manual_payment_price[get_payment_col($total_result['payment_method'],$total_result['orders_status'])][$total_result['orders_id']] = $total_result['manual_price'];
									$manual_price[$total_result['orders_id']]=$total_result['manual_price'];
								}
								
								// Manual Price Calculation
								
								
/*								if($manual_price_result['value']){
									$payment[get_payment_col($total_result['payment_method'],$total_result['order_status'])]+=$manual_price_result['value'];
									$manual_price += $manual_price_result['value'];
									$manualPrice+=$manual_price_result['value'];
								}
*/							}
							$heading=get_heading($type);
							
							if($sales_by_order==1){
								$cols[]=array("text"=>$heading . ($prt=='prt'?' Total':''),"width"=>$widths[0]+$widths[1]+$widths[2]+$widths[3]+$widths[4]+$widths[5],"align"=>"L","style"=>"headrow");
							}else{ 
								$cols[]=array("text"=>$heading . ($prt=='prt'?' Total':''),"width"=>$widths[0]+$widths[1]+$widths[2]+$widths[3]+$widths[4],"align"=>"L","style"=>"headrow");
								if($type=='H')
									$cols[]=array("text"=>'',"width"=>$widths[5],"align"=>"R","style"=>"headrow");
								else
									$cols[]=array("text"=>$currencies->format($prd_unit_subtotal),"width"=>$widths[5],"align"=>"R","style"=>"headrow");
								$cols[]=array("text"=>$prd_quan_subtotal,"width"=>$widths[6],"align"=>"R","style"=>"headrow");
								if($temp_type=='P')
									$cols[]=array("text"=>$currencies->format($prd_gv),"width"=>$widths[7],"align"=>"R","style"=>"headrow");
							}
							if($type=='H')
/*								$cols[]=array("text"=>$currencies->format($total_shipping),"width"=>$widths[5],"align"=>"R","style"=>"headrow");
*/								$cols[]=array("text"=>$currencies->format(array_sum($shipping_price)),"width"=>$widths[5],"align"=>"R","style"=>"headrow");
							else
								$cols[]=array("text"=>$currencies->format($prd_total),"width"=>$widths[8],"align"=>"R","style"=>"headrow");
							if($tax)$cols[]=array("text"=>$currencies->format($prd_tax),"width"=>$widths[9],"align"=>"R","style"=>"headrow");
							for($icnt=0;$icnt<count($payment_list);$icnt++) { 
								if($payment_list[$icnt]['on']>0) {
									$cols[]=array("text"=>($payment[$icnt])?$currencies->format($payment[$icnt]):'',"width"=>60,"align"=>"R","style"=>"headrow");
								} 
							}	
							/*if($manual_price < 0){
								$manual_price = (-1 * $manual_price);
								$cols[]=array("text"=>"(-)".$currencies->format($manual_price),"width"=>'100',"align"=>"R","style"=>"headrow");
							}else{
								$cols[]=array("text"=>$currencies->format($manual_price),"width"=>'100',"align"=>"R","style"=>"headrow");
							}*/
							$cols[]=array("text"=>'',"width"=>'100',"align"=>"R","style"=>"headrow");
							$table->OutputRow($cols,18);
							$grand_unit_price+=$prd_unit_subtotal;
							$grand_quan+=$prd_quan_subtotal;
							$grand_total+=$prd_total;
		//			echo 'shipping=' . 	$total_shipping; 	
/*							if($shipping > 0 ){
								$grand_unit_price+=$shipping;
								$grand_total+=$shipping;
							}	
*//*							if($manual_price){
								$grand_unit_price=array_sum($manual_price);
								$grand_total=array_sum($manual_price);
							}
*/							
							$grand_gv+=$prd_gv;
							if($tax==1)$grand_total_tax+=$prd_tax;
							$grand_payment_array=array();
							for($icnt=0;$icnt<count($payment_list);$icnt++) { 
								if($payment_list[$icnt]['on']>0) { 
									$grand_payment_array[$icnt]=$payment[$icnt];
									$grand_pay_array[$icnt][]=$payment[$icnt];
								}
							}
						}elseif($prt=='prt_grand_total'){ 
							if(is_array($manual_price)){
								$grand_total+=array_sum($manual_price);
								$grand_unit_price+=array_sum($manual_price);
						    }else{
								$grand_total+=$manual_price;
								$grand_unit_price+=$manual_price;
							}
							if(is_array($shipping_price)){
								$grand_total+=array_sum($shipping_price);
								$grand_unit_price+=array_sum($shipping_price);
						    }else{
								$grand_total+=$shipping_price;
								$grand_unit_price+=$shipping_price;
							}
							
							if($temp_type!='H') {
								if($sales_by_order==1){
									$cols[]=array("text"=>'Grand Total',"width"=>$widths[0]+$widths[1]+$widths[2]+$widths[3]+$widths[4]+$widths[5],"align"=>"L","style"=>"headrow");
								}else{ 
									$cols[]=array("text"=>'Grand Total',"width"=>$widths[0]+$widths[1]+$widths[2]+$widths[3]+$widths[4],"align"=>"L","style"=>"headrow");
									$cols[]=array("text"=>$currencies->format($grand_unit_price),"width"=>$widths[5],"align"=>"R","style"=>"headrow");
									$cols[]=array("text"=>$grand_quan,"width"=>$widths[6],"align"=>"R","style"=>"headrow");
									if($temp_type=='P')
										$cols[]=array("text"=>$currencies->format($grand_gv),"width"=>$widths[7],"align"=>"R","style"=>"headrow");
								}
							
								$cols[]=array("text"=>$currencies->format($grand_total),"width"=>$widths[8],"align"=>"R","style"=>"headrow");
								if($tax)$cols[]=array("text"=>$currencies->format($grand_total_tax),"width"=>$widths[9],"align"=>"R","style"=>"headrow");
								
								
								for($icnt=0;$icnt<count($payment_list);$icnt++) {
									if($payment_list[$icnt]['on']==1) {
										$pay_total=0;
										
										// Manual Price Calculation
										if(is_array($manual_payment_price[$icnt]))
											$value = array_sum(($manual_payment_price[$icnt]));
										$pay_total+=$value;
										// Manual Price Calculation
								
								
										// Manual Price Calculation
										if(is_array($shipping_payment_price[$icnt])){
											$value = array_sum(($shipping_payment_price[$icnt]));
											$pay_total+=$value;
										}	
										// Manual Price Calculation
										
										for($jcnt=0;$jcnt<count($grand_pay_array[$icnt]);$jcnt++) {
											$pay_total+=(float)$grand_pay_array[$icnt][$jcnt];
										}
										$cols[]=array("text"=>(($pay_total)?$currencies->format($pay_total):''),"width"=>60,"align"=>"R","style"=>"headrow");
									}
								}
								/*if($manualPrice < 0){
									$manualPrice = (-1 * $manualPrice);
									$cols[]=array("text"=>"(-)".$currencies->format($manualPrice),"width"=>'100',"align"=>"R","style"=>"headrow");
								}else{
									$cols[]=array("text"=>$currencies->format($manualPrice),"width"=>'100',"align"=>"R","style"=>"headrow");
								}*/
								$cols[]=array("text"=>'',"width"=>'100',"align"=>"R","style"=>"headrow");
								$table->OutputRow($cols,18);
							}
						}
						$cols[]=array("text"=>'',"width"=>$table->width,"align"=>"R","style"=>"subrow","valign"=>"M");
						unset($cols);
						$cols[]=array("text"=>'',"width"=>$table->width,"align"=>"R","style"=>"subrow","valign"=>"M");
						$table->OutputRow($cols,18);
						unset($cols);
						$unit_subtotal=0;
						$all_subtotal=0;
						$quan_subtotal=0;
						$unit_tax=0;
						$all_tax=0;
						$all_gv=0;
						$all_manual_price=0;
					}

				// function to generate pdf content
					function generate_pdf(){
						global $display_header,$display_array,$type,$found_results,$prd_total_array,$details,$payment_count,$currencies,$report_filename,$payment_list,$summary,$payment_col_count,$tax,$sales_by_order,$manualPrice;
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
						$table->headers["height"]=10;
						$table->headers["width"]="100%";
						$table->headers["cols"]=5;
						
						// generate coloumn widths
						
						// generate table header columns
							if($type!='H') {// not shipping
							$widths[0]=40;
							$widths[2]=60;
							$widths[3]=60;
							$widths[4]=60;
							$widths[5]=60;
							$widths[6]=60;
							if($type=='P')
								$widths[7]=60;
							$widths[8]=60;
							$wd=$widths[8];
							if($tax){
								$widths[9]=40;
								$wd=$widths[8]+$widths[9];
							}
							if($type=='P' && $sales_by_order==0){
								$wd=$wd+$widths[6]+$widths[7]+100;
							} else{
								$wd=$wd+100;
							}
							
							$payment_col_width=60;
							$payment_width=$payment_col_count*$payment_col_width;
							if($sales_by_order || $type=='P')
								$temp_width=$table->width-($widths[0]+$widths[2]+$widths[3]+$widths[4]+$widths[5]+$wd+$payment_width);
							else
								$temp_width=$table->width-($widths[0]+$widths[2]+$widths[3]+$widths[4]+$widths[5]+$widths[6]+$wd+$payment_width);
							$widths[1]=$temp_width;
							$cols=array();
							
							$cols[]=array("text"=>TEXT_INDEX,"width"=>$widths[0],"align"=>"L","style"=>"headrow","valign"=>"M");
							$cols[]=array("text"=>TEXT_CLIENT,"width"=>$widths[1],"align"=>"L","style"=>"headrow","valign"=>"M");
							$cols[]=array("text"=>TEXT_ORDER_ID,"width"=>$widths[3],"align"=>"R","style"=>"headrow","valign"=>"M");
							
							if($sales_by_order==1){
								$cols[]=array("text"=>'',"width"=>$widths[3],"align"=>"L","style"=>"headrow","valign"=>"M");
							}
							$cols[]=array("text"=>TEXT_SALE_DATE,"width"=>$widths[2],"align"=>"R","style"=>"headrow","valign"=>"M");
							$cols[]=array("text"=>TEXT_PAID_DATE,"width"=>$widths[3],"align"=>"R","style"=>"headrow","valign"=>"M");
							if($sales_by_order==0){
								$cols[]=array("text"=>TEXT_UNIT_PRICE,"width"=>$widths[5],"align"=>"R","style"=>"headrow","valign"=>"M");
								$cols[]=array("text"=>TEXT_SOLD,"width"=>$widths[6],"align"=>"R","style"=>"headrow","valign"=>"M");
								if($type=='P')
								$cols[]=array("text"=>TEXT_GIFT_VOUCHER,"width"=>$widths[7],"align"=>"C","style"=>"headrow","valign"=>"M");
							}
							$cols[]=array("text"=>TEXT_TOTAL,"width"=>$widths[8],"align"=>"R","style"=>"headrow","valign"=>"M");
							if($tax)$cols[]=array("text"=>TEXT_TAX,"width"=>$widths[9],"align"=>"R","style"=>"headrow","valign"=>"M");
							for ($icnt=0;$icnt<$payment_count;$icnt++){
								if ($payment_list[$icnt]["on"]==0) continue;
								if(substr($payment_list[$icnt]["name"],0,4)=='Cash') $name='Cash';
								else{
									$name=(strlen($payment_list[$icnt]["name"])>6?substr($payment_list[$icnt]["name"],0,6):$payment_list[$icnt]["name"]);
								}
								$cols[]=array("text"=>$name,"width"=>$payment_col_width,"align"=>"R","style"=>"headrow","valign"=>"M");
							}
							$cols[]=array("text"=>'Price Adjustment',"width"=>'100',"align"=>"R","style"=>"headrow","valign"=>"M");
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
							//	$unit_subtotal=0;
							//	$all_total=0;
							//	$unit_tax=0;
							$all_tax=0;
							$orders_id=0;
							$display_orders=0; 
							for($disp_cnt=0;$disp_cnt<count($display_array);$disp_cnt++){
								if($display_array[$disp_cnt]==$type){
									if($sales_by_order==0 && $type='P')
										$cols[]=array("text"=>get_heading($display_array[$disp_cnt]),"width"=>$table->width,"align"=>"L","style"=>"headrow","valign"=>"M");
									else 
										$cols[]=array("text"=>get_heading($display_array[$disp_cnt]),"width"=>($table->width),"align"=>"L","style"=>"headrow","valign"=>"M");	
									$table->OutputRow($cols,18);
									unset($cols);
									//while(list($key,)=each($details)){
									foreach (array_keys($details) as $key)
									{
										$row=&$details[$key];
										$splt_key=explode("/-/",$key);
										//	print_r($splt_key);
										if (count($splt_key)>1){
											if ($splt_key[0]!=$prev_product_id && $sales_by_order==0 ){
												if ($unit_subtotal>0) print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_gv,$all_manual_price,$table,$widths,$display_array[$disp_cnt],'',$unit_tax,'');
												$cols[]=array("text"=>$row["name1"],"width"=>$table->width,"align"=>"L","style"=>"headrow","valign"=>"M");
												$table->OutputRow($cols,18);
												unset($cols);
												$prev_attribute_id=-1;
												$prev_product_id=$splt_key[0];
												$unit_subtotal=0;
												$all_subtotal=0;
												$unit_tax=0;
												$all_manual_price=0;
												$quan_subtotal=0;
												$all_gv=0;
											}
											if ($key!=$prev_attribute_id && $sales_by_order==0){
												if ($unit_subtotal>0) print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_gv,$all_manual_price,$table,$widths,$display_array[$disp_cnt],'',$unit_tax,'');
												$cols[]=array("text"=>'   ' .$row["name"],"width"=>$table->width,"align"=>"L","style"=>"headrow1","valign"=>"M");
												$table->OutputRow($cols,18);
												unset($cols);
												$prev_attribute_id=$key;
												$unit_subtotal=0;
												$all_subtotal=0;
												$unit_tax=0;
												$quan_subtotal=0;
												$all_gv=0;
												$all_manual_price=0;
											}
										} else {
											if (($splt_key[0]!=$prev_product_id || $prev_attribute_id!=-1) && $sales_by_order==0){
												if ($unit_subtotal>0 && $row["name"]!=$prev_products_name) print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_gv,$all_manual_price,$table,$widths,$display_array[$disp_cnt],'',$unit_tax,'');
												if ($splt_key[0]!=$prev_product_id && $row["name"]!=$prev_products_name) {
													$cols[]=array("text"=>$row["name"],"width"=>$table->width,"align"=>"L","style"=>"headrow","valign"=>"M");
													$table->OutputRow($cols,18);
													unset($cols);
													$all_subtotal=0;
													$unit_subtotal=0;
													$unit_tax=0;
													$quan_subtotal=0;
													$all_gv=0;
													$all_manual_price=0;
												}
												$prev_product_id=$splt_key[0];
												//echo $prev_product_id.'<br>';
												$prev_products_name = $row["name"];
												$prev_attribute_id=-1;
											}
										} // $splt_key
										
										
										$contents=&$row["contents"];
										//	$unit_subtotal=0;
										//	$all_subtotal=0;
										//	$unit_tax=0;
										//	$quan_subtotal=0;
										for ($icnt=0,$n=count($contents);$icnt<$n;$icnt++){
											$content=&$contents[$icnt];
											$manual_price_query = tep_db_query('select text,value from '.TABLE_ORDERS_TOTAL.' where class = "ot_adjust" and orders_id="'.$content["orders_id"].'"');
											$manual_price_result = tep_db_fetch_array($manual_price_query);
											if($type!='H'){
												if (!$summary){
													$cols[]=array("text"=>$row_cnt,"width"=>$widths[0],"align"=>"L","style"=>"row","valign"=>"M");
													$cols[]=array("text"=>$content["cname"],"width"=>$widths[1],"align"=>"L","style"=>"row","valign"=>"M");
													$cols[]=array("text"=>$content["orders_id"],"width"=>$widths[4],"align"=>"R","style"=>"row","valign"=>"M");
													if($sales_by_order==1){
														$cols[]=array("text"=>'',"width"=>$widths[4],"align"=>"R","style"=>"row","valign"=>"M");
													}
													$cols[]=array("text"=>$content["date"],"width"=>$widths[2],"align"=>"R","style"=>"row","valign"=>"M");
													$cols[]=array("text"=>$content["paid_date"],"width"=>$widths[3],"align"=>"R","style"=>"row","valign"=>"M");
													if($sales_by_order==0){
														$cols[]=array("text"=>$currencies->format($content["uprice"]+$content["tax"]),"width"=>$widths[5],"align"=>"R","style"=>"row","valign"=>"M");
														$cols[]=array("text"=>$content["quan"],"width"=>$widths[6],"align"=>"R","style"=>"row","valign"=>"M");
														if($type=='P')
														$cols[]=array("text"=>$currencies->format($content["gv"]),"width"=>$widths[7],"align"=>"R","style"=>"row","valign"=>"M");
														
													}
													$cols[]=array("text"=>$currencies->format($content["tprice"]+$content["ptax"]),"width"=>$widths[8],"align"=>"R","style"=>"row","valign"=>"M");
													if($tax) $cols[]=array("text"=>$currencies->format($content["ptax"]),"width"=>$widths[9],"align"=>"R","style"=>"row","valign"=>"M"); 
													for ($jcnt=0;$jcnt<$payment_count;$jcnt++){
														if ($payment_list[$jcnt]["on"]==0) continue;
														if ($jcnt==$content["col_pos"]){
															$cols[]=array("text"=>$currencies->format($content["tprice"]+$content["ptax"]),"width"=>$payment_col_width,"align"=>"R","style"=>"row","valign"=>"M");
															$payment_list[$content["col_pos"]]["subtotal"]+=$content["tprice"]+$content["ptax"];
														} else {
															$cols[]=array("text"=>'',"width"=>$payment_col_width,"align"=>"R","style"=>"row","valign"=>"M");
														}
													} 
													if($manual_price_result["value"] < 0){
														$manualprice = (-1 * $manual_price_result["value"]);
														$cols[]=array("text"=>"(-)".$currencies->format($manualprice),"width"=>'100',"align"=>"R","style"=>"row","valign"=>"M");
													}else{
														$cols[]=array("text"=>$currencies->format($manual_price_result["value"]),"width"=>'100',"align"=>"R","style"=>"row","valign"=>"M");
													}
													$row_cnt++;
													$table->OutputRow($cols,18);
													unset($cols);
												}   else if ($summary){
													for ($jcnt=0;$jcnt<$payment_count;$jcnt++){
														if ($payment_list[$jcnt]["on"]==0) continue;
														if ($jcnt==$content["col_pos"]){
															$payment_list[$content["col_pos"]]["subtotal"]+=$content["tprice"]+$content["ptax"];
														} 
													} // $jcnt
												
													$row_cnt++;
												}   
											}else{
												if (!$summary){
													$ship_method=explode('/-/',$content["shipping_method"]);
													$cols[]=array("text"=>$row_cnt,"width"=>$widths[0],"align"=>"L","style"=>"row","valign"=>"M");
													$cols[]=array("text"=>$content["cname"],"width"=>$widths[1],"align"=>"L","style"=>"row","valign"=>"M");
													$cols[]=array("text"=>$content["orders_id"],"width"=>$widths[2],"align"=>"L","style"=>"row","valign"=>"M");
													$cols[]=array("text"=>$content["location"],"width"=>$widths[3],"align"=>"L","style"=>"row","valign"=>"M");
													$cols[]=array("text"=>$ship_method[0],"width"=>$widths[4],"align"=>"L","style"=>"row");
													$cols[]=array("text"=>$content["weight"],"width"=>$widths[5],"align"=>"R","style"=>"row","valign"=>"M");
													$cols[]=array("text"=>$currencies->format($content["cost"]),"width"=>$widths[6],"align"=>"R","style"=>"row","valign"=>"M");
													$quan_subtotal+=$content["weight"];
													$row_cnt++;
												}else{
													$quan_subtotal+=$content["weight"];
												}

												$table->OutputRow($cols,18);
												unset($cols);
											}
											$all_manual_price+=$manual_price_result["value"];
											$unit_subtotal+=$content["uprice"]+$content["tax"];
											$all_subtotal+=$content["tprice"]+$content["ptax"];
											$all_gv+=$content['gv'];
											$quan_subtotal+=$content["quan"];
											$unit_tax+=$content["ptax"]; 
											$all_tax+=$content["ptax"];
											
											if($sales_by_order==1){
												if($display_orders==22 && $summary==0){
													print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_gv,$all_manual_price,$table,$widths,$display_array[$disp_cnt],'',$unit_tax,'');
													$display_orders=0;
												}
												$display_orders++;
											}
										} 
										// $icnt
									} // $found_results
									if ($unit_subtotal>0) {
										print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_gv,$all_manual_price,$table,$widths,$display_array[$disp_cnt],'prt',$unit_tax,$all_tax); 
										unset($cols);
									}
								}else{ 
									if($type!='H'){
										 print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_gv,$all_manual_price,$table,$widths,$display_array[$disp_cnt],'prt_type_total',$unit_tax,$all_tax);
										unset($cols);
									}	
								}
							}//for end
							 print_total_row_pdf($unit_subtotal,$all_subtotal,$quan_subtotal,$all_gv,$all_manual_price,$table,$widths,'','prt_grand_total',$unit_tax,$all_tax);
						} else {
							$cols[]=array("text"=>REPORT_NO_RESULTS,"width"=>"100%","align"=>"C","style"=>"subrow","valign"=>"M");
							$table->OutputRow($cols,18);
							unset($cols);
						}
						// output pdf file
						$table->Render($report_filename .".pdf",'F');
					}

				//function to get excel format csv datas
					function generate_excel(){
						global $display_array,$type,$details,$filename,$payment_list,$payment_count,$found_results,$report_filename,$currencies,$summary,$payment_col_count,$tax,$sales_by_order;
						//	$temp_type=$type;
						if($type!='H'){
							if($sales_by_order==1){
								if($tax){
									$res=sprintf("%s,%s,%s,%s,%s,%s,%s",TEXT_INDEX,TEXT_CLIENT,TEXT_ORDER_ID,TEXT_SALE_DATE,TEXT_PAID_DATE,TEXT_TOTAL,TEXT_TAX);	
								}else if(!$tax) {
									$res=sprintf("%s,%s,%s,%s,%s,%s",TEXT_INDEX,TEXT_CLIENT,TEXT_ORDER_ID,TEXT_SALE_DATE,TEXT_PAID_DATE,TEXT_TOTAL);
								}
							}else{
								if($type=='P'){
									if($tax){
										$res=sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s,%s",TEXT_INDEX,TEXT_CLIENT,TEXT_ORDER_ID,TEXT_SALE_DATE,TEXT_PAID_DATE,TEXT_UNIT_PRICE,TEXT_SOLD,TEXT_GIFT_VOUCHER,TEXT_TOTAL,TEXT_TAX);	
									}else if(!$tax) {
										$res=sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s",TEXT_INDEX,TEXT_CLIENT,TEXT_ORDER_ID,TEXT_SALE_DATE,TEXT_PAID_DATE,TEXT_UNIT_PRICE,TEXT_SOLD,TEXT_GIFT_VOUCHER,TEXT_TOTAL);
									}
								} else {
									if($tax){
										$res=sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s",TEXT_INDEX,TEXT_CLIENT,TEXT_ORDER_ID,TEXT_SALE_DATE,TEXT_PAID_DATE,TEXT_UNIT_PRICE,TEXT_SOLD,TEXT_TOTAL,TEXT_TAX);	
									}else if(!$tax) {
										$res=sprintf("%s,%s,%s,%s,%s,%s,%s,%s",TEXT_INDEX,TEXT_CLIENT,TEXT_ORDER_ID,TEXT_SALE_DATE,TEXT_PAID_DATE,TEXT_UNIT_PRICE,TEXT_SOLD,TEXT_TOTAL);
									}
								}	
							}
							$result.=$res;
							for ($icnt=0;$icnt<$payment_count;$icnt++){
								if ($payment_list[$icnt]["on"]==0) continue;
								if(substr($payment_list[$icnt]["name"],0,4)=='Cash') $name='Cash';
								else{
									$name=(strlen($payment_list[$icnt]["name"])>6?substr($payment_list[$icnt]["name"],0,6):$payment_list[$icnt]["name"]);
								}
								$result.=',' .  $name;
							}
							$result.=',Price Adjustment';
						}else{
							$res=sprintf("%s,%s,%s,%s,%s,%s,%s",TEXT_INDEX,TEXT_CUSTOMER_NAME,TEXT_ORDER_ID,TEXT_LOCATION,TEXT_SHIPPING_METHOD,TEXT_WEIGHT,TEXT_COST);	
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
							//	$all_subtotal=0;
							$all_total=0;
							//	$unit_tax=0;
							$all_tax=0;
							for($disp_cnt=0;$disp_cnt<count($display_array);$disp_cnt++){
								if($display_array[$disp_cnt]==$type){
									$result.="\n";
									$result.= '"' . get_heading($display_array[$disp_cnt]) . "\"\n";   
									//while(list($key,)=each($details)){
									foreach (array_keys($details) as $key)
									{
										$row=&$details[$key];
										$splt_key=explode("/-/",$key);
										if (count($splt_key)>1){   
											if ($splt_key[0]!=$prev_product_id && $sales_by_order==0){
												if ($unit_subtotal>0) $result.=print_total_row_excel($unit_subtotal,$all_subtotal,$quan_subtotal,$all_gv,$all_manual_price,'',$type,$unit_tax,'');
												$result.= '"' . $row["name1"] . "\"\n";    
												$prev_attribute_id=-1;      
												$prev_product_id=$splt_key[0]; 
												$unit_subtotal=0;
												$all_subtotal=0;
												$unit_tax=0;
												$quan_subtotal=0;
												$all_gv=0;
												$all_manual_price=0;
											}  
											if ($key!=$prev_attribute_id && $sales_by_order==0){  
												if ($unit_subtotal>0) $result.=print_total_row_excel($unit_subtotal,$all_subtotal,$quan_subtotal,$all_gv,$all_manual_price,'',$type,$unit_tax,'');
												$result.='"  ' . $row["name"] . "\"\n"; 
												$prev_attribute_id=$key; 
												$unit_subtotal=0;
												$all_subtotal=0;
												$unit_tax=0;
												$quan_subtotal=0;
												$all_gv=0;
												$all_manual_price=0;
											} 
										} else { 
											if (($splt_key[0]!=$prev_product_id || $prev_attribute_id!=-1) && $sales_by_order==0){
												if ($unit_subtotal>0 && $row["name"]!=$prev_products_name) $result.=print_total_row_excel($unit_subtotal,$all_subtotal,$quan_subtotal,$all_gv,$all_manual_price,'',$type,$unit_tax,'');
												if ($splt_key[0]!=$prev_product_id && $row["name"]!=$prev_products_name){
													$result.='"' . $row["name"] . "\"\n";
													$quan_subtotal=0;
													$unit_subtotal=0;
													$all_subtotal=0;
													$unit_tax=0;
													$all_gv=0;
													$all_manual_price=0;
												}
												$prev_product_id=$splt_key[0];
												$prev_products_name = $row["name"];
												$prev_attribute_id=-1;
												//	$quan_subtotal=0;
											}
										}
										
										$contents=&$row["contents"];
										for ($icnt=0,$n=count($contents);$icnt<$n;$icnt++){
											$content=&$contents[$icnt];
											if($type!='H'){
												$manual_price_query = tep_db_query('select text,value from '.TABLE_ORDERS_TOTAL.' where class = "ot_adjust" and orders_id="'.$content["orders_id"].'"');
												$manual_price_result = tep_db_fetch_array($manual_price_query);
												
												if($sales_by_order==1 && !$summary){
													if($tax){
														$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"",$row_cnt,$content["cname"],$content["orders_id"],$content["date"],$content["paid_date"],$currencies->format($content["tprice"]+$content["ptax"]),$currencies->format($content["ptax"]));
													}else if(!$tax){
														$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"",$row_cnt,$content["cname"],$content["orders_id"],$content["date"],$content["paid_date"],$currencies->format($content["tprice"]+$content["ptax"]));	
													}
												}else if(!$summary){
													if($type=='P') {
														if($tax){
															$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"",$row_cnt,$content["cname"],$content["orders_id"],$content["date"],$content["paid_date"],$currencies->format($content["uprice"]+$content["tax"]),$content["quan"],$currencies->format($content['gv']),$currencies->format($content["tprice"]+$content["ptax"]),$currencies->format($content["ptax"]));
														}else if(!$tax){
															$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"",$row_cnt,$content["cname"],$content["orders_id"],$content["date"],$content["paid_date"],$currencies->format($content["uprice"]+$content["tax"]),$content["quan"],$currencies->format($content['gv']),$currencies->format($content["tprice"]+$content["ptax"]));	
														}
													} else {
														if($tax){
															$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"",$row_cnt,$content["cname"],$content["orders_id"],$content["date"],$content["paid_date"],$currencies->format($content["uprice"]+$content["tax"]),$content["quan"],$currencies->format($content["tprice"]+$content["ptax"]),$currencies->format($content["ptax"]));
														}else if(!$tax){
															$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"",$row_cnt,$content["cname"],$content["orders_id"],$content["date"],$content["paid_date"],$currencies->format($content["uprice"]+$content["tax"]),$content["quan"],$currencies->format($content["tprice"]+$content["ptax"]));	
														}
													}	
												}
												if (!$summary){
													for ($jcnt=0;$jcnt<$payment_count;$jcnt++){
														if ($payment_list[$jcnt]["on"]==0) continue;
														if ($jcnt==$content["col_pos"]){
															$result.=',"' . $currencies->format($content["tprice"]+$content["ptax"]).'"';
															$payment_list[$content["col_pos"]]["subtotal"]+=$content["tprice"]+$content["ptax"];
														} else {
															$result.=",";
														}
													}
													$row_cnt++;
													if($manual_price_result["value"] < 0){
														$manualprice = (-1 * $manual_price_result["value"]);
														$result.=',"' . '(-)' .$currencies->format($manualprice).'"';
													}else{
														$result.=',"' . $currencies->format($manual_price_result["value"]).'"';
													}
													//$result.=',"' . $currencies->format($manual_price_result["value"]).'"';
													$result.="\n";
												} else { // $summary
													for ($jcnt=0;$jcnt<$payment_count;$jcnt++){
														if ($payment_list[$jcnt]["on"]==0) continue;
														if ($jcnt==$content["col_pos"]){
															$payment_list[$content["col_pos"]]["subtotal"]+=$content["tprice"]+$content["ptax"];
														}
													}
													//$result.=',"'.$currencies->format($content["amount_received"]).'","'.$currencies->format($content["amount_due"]).'","'.$currencies->format($content["site_margin"]).'"';
												} 
											}else{ 
												if(!$summary){
													$ship_method=explode('/-/',$content["shipping_method"]);
													$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"",$row_cnt,$content["cname"],$content["orders_id"],$content["location"],$ship_method[0],$content["weight"],$currencies->format($content["cost"]));
													$result.="\n";
													$row_cnt++;
												}
											}
											$all_manual_price+=$manual_price_result["value"];
											$unit_subtotal+=$content["uprice"]+$content["tax"];
											$all_subtotal+=$content["tprice"]+$content["ptax"];
											if($type!='H')
												$quan_subtotal+=$content["quan"];
											else
												$quan_subtotal+=$content["weight"];
											$unit_tax+=$content["ptax"];
											$all_tax+=$content["ptax"];
											$all_gv+=$content['gv'];
										} // $jcnt
									} // $icnt
									if ($unit_subtotal>0) $result.=print_total_row_excel($unit_subtotal,$all_subtotal,$quan_subtotal,$all_gv,$all_manual_price,'prt',$type,$unit_tax,$all_tax);
								}else{
									if($type!='H')
										$result.="\n" .print_total_row_excel($unit_subtotal,$all_subtotal,$quan_subtotal,$all_gv,$all_manual_price,'prt_type_total',$display_array[$disp_cnt],$unit_tax,$all_tax);
								}
							} //end for
							$result.="\n" . print_total_row_excel($unit_subtotal,$all_subtotal,$quan_subtotal,$all_gv,$all_manual_price,'prt_grand_total','',$unit_tax,$all_tax);
						} else {
							$result.=REPORT_NO_RESULTS . "\n";
						}
						tep_write_text_file($report_filename . ".csv",$result);
					}

					function cmp($a, $b) {
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
						
						$categories_desc=tep_db_query("select c.categories_id,categories_name from ".TABLE_CATEGORIES." c LEFT JOIN ".TABLE_CATEGORIES_DESCRIPTION." cd ON(c.categories_id=cd.categories_id) LEFT JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." pTc ON(cd.categories_id=pTc.categories_id) where pTc.products_id=$product_id group by c.categories_id");
						if(tep_db_num_rows($categories_desc)>0){
							$categories=tep_db_fetch_array($categories_desc);
							if($display=='cat_id')
								return $categories['categories_id'];
							else if($display=='')
								return $categories['categories_name'];
							
						} 
					}
					function get_result(){
						global $query_split,$type,$FREQUEST,$page,$payment_count,$payment_list,$tax,$query_split_numrows,$summary,$sales_by_order,$grand_total,$grand_total_tax,$grand_pay_array,$grand_unit_price,$grand_quan,$grand_gv,$manual_price;
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
									<td colspan="15" height="5"></td>
								</tr>
								<tr>
									<td width="100%" colspan="10">
										<?php if(HIDE_FROM_BACKEND_MENU_PRODUCTS=='false') { ?>
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
												<?php get_grand_total($grand_total,$grand_total_tax,$grand_pay_array,$grand_unit_price,$grand_quan,$grand_gv);
										}	?>
									</td>
								</tr>
							</table>
					<?php } ?>
					<script language="javascript">
						function nav_page(page){
							doReport(1,page);
						}
						doChange('<?php echo $btype;?>');
					</script>

					<input type="hidden" name="products_type">
					<?php
						function display_total_row_pdf($prd_type,$table,$widths) { 
							global $products_id,  $where, $prd_where, $payment_col_count, $currencies, $payment_list, $grand_total,$grand_total_tax,$grand_payment_array,$grand_pay_array,$tax,$summary,$tax,$sales_by_order,$page,$grand_quan,$grand_unit_price;
							/*$payment=array();
							$session_table="";
							if($prd_type=='E') 	{
								$session_table = " ,events_sessions es ";
								$event_where .= " and es.sessions_id=op.products_id ";
							}	
								$total_sql=	"SELECT sum(op.final_price) as pro_total,sum(op.products_quantity) as quan,op.orders_id,op.products_id,op.products_name,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity*op.final_price) as total,sum(((op.final_price*op.products_tax)/100)*op.products_quantity) as total_tax,sum((op.final_price*op.products_tax)/100) as tax,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op " . $session_table . " where o.orders_id=op.orders_id  " . $where . $event_where . " and op.products_type='" . $prd_type . "' and o.orders_status>1  group by payment_method order by op.products_name";
									
								if($prd_type=='H')$total_sql="select sum(ot.value) as shipping_total from " . TABLE_ORDERS . " o," . TABLE_ORDERS_TOTAL . "  ot where ot.orders_id=o.orders_id and ot.value!=0 " . $where . " and ot.class='ot_shipping' and o.orders_status>1 group by o.orders_status>1";
								//echo $total_sql;
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
								} */
								 print_total_row_pdf(pro_total,$prd_total,$quan,$all_gv,$table,$widths,$prd_type,'prttot',$unit_tax,$all_tax);
						} 
					?>

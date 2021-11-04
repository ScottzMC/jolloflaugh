<?php
/*
  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
http://www.openfreeway.org
Copyright (c) 2010/11/12 osConcert
*/
// Set flag that this is a parent file
  define( '_FEXEC', 1 );
  ini_set('max_execution_time', 300);
  
//++++ 2016 there are two mentions of $net_total in  this file that are not the same
//the one used in the generation of excel, pdf etc. is actually a grand_total
  
  require('includes/application_top.php');  
  tep_get_last_access_file();
  
 tep_set_time_limit(0);
  require('includes/classes/currencies.php');
  require('includes/classes/pdfTable.php');
  require('tfpdf/font/makefont/makefont.php');
//  echo $FSESSION->login_groups_type;
  require(DIR_WS_CLASSES . 'split_page_results_report.php');
  define(BOX_WIDTH1,'125');
  //lang
	define(TEXT_TICKETS_SOLD,'Tickets Sold (not Season Tickets or Gift Coupons) : ');
	define(TEXT_COMMENTS,'Order Comments');
	define(TEXT_PURCHASED,'Date Purchased');
//  define(TEXT_SEATS,'Tickets');
//  define(TEXT_QTY,'Qty');
//  define(TEXT_BILLING_NAME,'Billing Name'); //Code change RM 
//  define(ALL_SEATS,'All Tickets');
//	define(SEP,',');
//  define(TEXT_DATEID,'DATE ID');
//  define(TEXT_USER,'User');
//  define(ADMIN_USER,'admin user');
  //define(TEXT_PAYMENT,'Payment Method');
  //Graeme Tyson, sakwoya@sakwoya.co.uk,  March  2012 for osConcert
	$costs='';
	$product_price='';
	// get initial parameters, try to load from session for previous settings
	if (($FREQUEST->getvalue("return")!='') && ($FSESSION->get("rep_params")!=''))
	{
		$input_params=&$FSESSION->get("rep_params");
	} else{
		$input_params=&$FPOST;
		if (isset($input_params["post_action"]))
		{
			$FSESSION->set("rep_params",$FPOST);
			$GLOBALS["rep_params"]["post_action"]="screen";
		} else
		{
			$FSESSION->set("rep_params",array());
		}
	}
	$date_begin=isset($input_params['txt_start_date'])?tep_convert_date_raw($input_params['txt_start_date']):'';
	$date_end=isset($input_params['txt_end_date'])?tep_convert_date_raw($input_params['txt_end_date']):'';
	//Users Admin or web
	//$users=isset($input_params['sel_user'])?$input_params['sel_user']:"All";
	//call centre staff
	$staff=isset($input_params['sel_staff'])?$input_params['sel_staff']:"All";
	// payment statuses
	$order_status=isset($input_params['sel_status'])?$input_params['sel_status']:"All";
	// Date ID
	$model=isset($input_params['sel_model'])?$input_params['sel_model']:"All";
	// Cine (products_number) event time
	$eventtime=isset($input_params['sel_eventtime'])?$input_params['sel_eventtime']:"All";
	//$summary_show=isset($input_params['chk_summary'])?true:false;
	$subtotal_show=(($FREQUEST->postvalue("chk_summary")!='')?true:false);
	$post_action = isset($input_params['post_action'])?$input_params['post_action']:'';
	$page=isset($input_params['page'])?$input_params['page']:1;
	$selected_category=isset($input_params['selected_category'])?$input_params['selected_category']:'product';
	$product_show=((strtolower($selected_category)=='product')?true:true);
	$currencies=new currencies();
	if ($post_action=="")
	{
		$output_pdf=true;
		$post_action="screen";
	}
	if (!$product_show){
		$product_show=true;
	}
	date_default_timezone_set(STORE_TIMEZONE); // Added by R101
	if ($date_begin=="")
	{
		$sql =  "select date_sub('".getServerDate()."', interval 3 month) begin,'".date('Y-m-d')."' as end";
		$sql_result = tep_db_query($sql);
		$row = tep_db_fetch_assoc($sql_result);
		$date_begin = $row["begin"];
		$date_end = $row["end"];
	}
	$display_header="";
	
	if ($date_end==""){
		$sql =  "select date_add('$date_begin', interval -(dayofmonth('$date_begin')-1) day) begin, date_add('$date_begin', interval (30-dayofmonth('$date_begin')) day) end";	
		$sql_result = tep_db_query($sql);
		$row = tep_db_fetch_assoc($sql_result);
		$date_begin = $row["begin"];
		$date_end = $row["end"];
	}
	
	//echo date('Y-m-d');
	// create header text for pdf content
	$display_header="";
	$display_header.=TEXT_FROM  . ":&nbsp;&nbsp;" . $date_begin . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$display_header.=TEXT_TO  . ":&nbsp;&nbsp;" . $date_end ."\t";
	
	if ($staff=="All") $display_header.=TEXT_CALL_CENTRE_STAFF . ":&nbsp;&nbsp;" . TEXT_ALL_STAFFS."\t";
	if ($model=="All") $display_header.=TEXT_DATEID . ":&nbsp;&nbsp;" . TEXT_ALL_DATE."\t";
	if ($order_status=="All") $display_header.=STATUS . ":&nbsp;&nbsp;" . TEXT_ALL_STATUS."\t";
	
	// get order statuses for drop down menu
	$sql="SELECT orders_status_id,orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id='" . (int)$FSESSION->languages_id . "' order by orders_status_id";
	//	echo $sql;
	$sql_result=tep_db_query($sql);
	$status_array[]=array("id"=>"All","text"=>TEXT_ALL_STATUS);
	while($row=tep_db_fetch_array($sql_result)){
		$status_array[]=array("id"=>$row["orders_status_id"],"text"=>$row["orders_status_name"]);
		if ($row["orders_status_id"]==$order_status){
			$display_header.=STATUS  . ":&nbsp;&nbsp;" . $row["orders_status_name"] . "&nbsp;&nbsp;";
		}
	}
	tep_db_free_result($sql_result);
	// get date id for drop down menu
	$sql="SELECT distinct(op.products_model) from " . TABLE_ORDERS_PRODUCTS . " op WHERE products_model !='' order by orders_id desc LIMIT 125";
	//echo $sql;
	$sql_result=tep_db_query($sql);
	$model_array[]=array("id"=>"All","text"=>ALL_SEATS);
	while($row=tep_db_fetch_array($sql_result)){
		$model_array[]=array("id"=>$row["products_model"],"text"=>$row["products_model"]);
		if ($row["products_model"]==$model){
			$display_header.=TEXT_DATEID  . ":&nbsp;&nbsp;" . $row["products_model"] . "&nbsp;&nbsp;";
		}
	}
	tep_db_free_result($sql_result);
	#######################################################################################
	//CINE
	define(ALL_TIMES,'All');
	define(TEXT_EVENTTIME,'Times');
	#######################################################################################
	// get date times for drop down menu
	$sql="SELECT distinct(op.concert_time) from " . TABLE_ORDERS_PRODUCTS . " op WHERE concert_time !='' order by concert_time desc LIMIT 125";
	//echo $sql;
	$sql_result=tep_db_query($sql);
	$eventtime_array[]=array("id"=>"All","text"=>ALL_TIMES);
	while($row=tep_db_fetch_array($sql_result)){
		$eventtime_array[]=array("id"=>$row["concert_time"],"text"=>$row["concert_time"]);
		if ($row["concert_time"]==$eventtime){
			$display_header.=TEXT_DATETIME  . ":&nbsp;&nbsp;" . $row["concert_time"] . "&nbsp;&nbsp;";
		}
	}
	tep_db_free_result($sql_result);
	
	####################
	//Box Office
	$sql ="select concat(c.customers_firstname,' ',c.customers_lastname) as customers_name from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and entry_country_id='999' order by customers_name";
//	echo $sql;
	$sql_result=tep_db_query($sql);
	$call_staff_array[]=array("id"=>"All","text"=>TEXT_ALL_STAFFS);
	while($row=tep_db_fetch_array($sql_result))
	{
		$call_staff_array[]=array("id"=>$row["customers_name"],"text"=>$row["customers_name"]);
		if ($row["customers_name"]==$staff)
		{
			$display_header.=TEXT_CALL_CENTRE_STAFF  . ":&nbsp;&nbsp;" . $row["customers_name"] . "&nbsp;&nbsp;";
		}
	}
	
	// get the mysql results;
	$sql="SELECT orders_status_id,orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id='" . (int)$FSESSION->languages_id . "'";
	$sql_result=tep_db_query($sql);
	while($row=tep_db_fetch_array($sql_result))
	{
		$order_status_array[$row["orders_status_id"]]=$row["orders_status_name"];
	}
		
	$found_results=false;
		$where = " ";
		$db_result=array();
		$num_rows=0;
		$cur_row=0;
		$rows_each=REPORT_MAX_ROWS_PAGE;
		
		// if reseller details is needed to show
		$where=" ";
		if ($product_show) 
		{
			if($staff!="All") 
			{
				$where.= " and customers_name='".tep_db_prepare_input($staff)."' ";
			}
		$stats=" ";
		if ($product_show) 
		{
			if($order_status!="All") 
			{
				//$stats.= " and op.orders_products_status='".tep_db_prepare_input($order_status)."' ";
				$stats.= " and o.orders_status='".tep_db_prepare_input($order_status)."' ";
			}
		}
		
		
		if($_POST['sel_model'] != "All")
		{
			$where.= " and op.products_model ='".tep_db_prepare_input($_POST['sel_model'])."' ";
			}

			//if(CUST_LIST=='true')
			//{
				//$order_by="c.customers_lastname";
			//}else
			//{
				$order_by="o.orders_id";
			//}
			function tep_add_tax_true($price, $tax) 
			{
			global $currencies;
			return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']) + tep_calculate_tax($price, $tax);
			}
			
			$sql = "select 
			(op.final_price*op.products_quantity) as final_price,
			op.products_tax, 
			o.customers_id,
			o.orders_id,
			op.products_price,
			o.ticket_printed,
			op.support_packs_type, 
			op.orders_products_id,
			op.events_type,
			op.events_id,
			op.products_type,
			op.categories_name,
			op.concert_venue,
			op.concert_date,
			op.concert_time,
			o.date_purchased,
			o.customers_name as order_name,
			o.customers_company,
			o.customers_street_address,
			o.customers_suburb,
			o.customers_city,
			o.customers_postcode,
			o.customers_state,
			o.customers_country,
			o.customers_telephone,
			op.products_id as item_id,
			o.customers_email_address,
			op.products_quantity,
			op.orders_products_status,
			o.payment_method,
			o.shipping_method,
			o.billing_name,
			op.products_model,
			op.products_name as item_name, 
			o.orders_status from " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o where op.orders_id=o.orders_id and orders_status!='0' and date_format(o.date_purchased,'%Y-%m-%d')>='".tep_db_input($date_begin)."' and date_format(o.date_purchased,'%Y-%m-%d')<='".tep_db_input($date_end)."' ".$where." ".$event." ".$stats." order by ".$order_by." ";
			
			
			
			
			
			//echo $sql;
// for a split screen page we need to get the order_total in some way
// running and sql here to pull out ot_totals aggregated gives a different value to that
// obtained by using the order_products_quantity * final price as ot_discount etc. etc. skews the ot_total
// so we need to aggregate the amounts here
				$sum_query=tep_db_query($sql);
			//	$sum_sql='';
			    $tot_amount= 0 ;
				$new_tot_amount = 0;
				while($row1=tep_db_fetch_array($sum_query))
				{
					
					$new_tot_amount += tep_add_tax_true($row1['final_price'],$row1["products_tax"]);
			    	$tot_amount += tep_add_tax($row1['final_price'],$row1["products_tax"]);
					$new_tot_amount = tep_get_rounded_amount($new_tot_amount);
					$tot_amount = tep_get_rounded_amount($tot_amount);
				}
				$tot_amount = $currencies->format($tot_amount);
				$new_tot_amount = $currencies->format($new_tot_amount);
				
			
				
###################################################################################################################
//count tickets sold			
// and op.products_sku !=6	
		$ticket_sum_sql="SELECT sum(op.products_quantity) as tickets_sold from " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o where  op.orders_id=o.orders_id and orders_status!='0' and date_format(o.date_purchased,'%Y-%m-%d')>='".tep_db_input($date_begin)."' and date_format(o.date_purchased,'%Y-%m-%d')<='".tep_db_input($date_end)."' ".$where." ".$event." ".$stats."  and op.events_type != 'X' and op.products_sku !=6";
										
										
				$ticket_sum_query=tep_db_query($ticket_sum_sql);
			//	$sum_sql='';
				$row2=tep_db_fetch_array($ticket_sum_query);
				
				$tickets=$row2['tickets_sold'];
				
				//print_r($ticket_sum_sql);

#######################################################################################################################				
				//we now have tot_amount to go forward with
				
		    $total_record_in_db = tep_db_num_rows(tep_db_query($sql));
			if ($post_action=="screen")
			{
				// split the content
				// this also splits the total down to just the tickets on the individual page in the query_split2
				// by popping limit onto the sql query		
				$query_split2 = new splitPageResultsReport($page, $rows_each, $sql, $query_split_numrows2,false);
			}
			$db_result[$cur_row]=array("display"=>TEXT_PRODUCTS , "result"=>tep_db_query($sql));
			$num_rows+=tep_db_num_rows($db_result[$cur_row]["result"]);
			$cur_row++;
			$where='';
			$count=$num_rows;
			//echo $sql;
		}
		if ($num_rows>0) 
		{	
			$found_results=true;
			if ($post_action=="screen"){
				if ($query_split_numrows1>=$query_split_numrows2)
				{
					$query_split=&$query_split1;
				} else {
					$query_split=&$query_split2;
				}
				$query_split_numrows=$query_split_numrows1+$query_split_numrows2;
			}
		}


		// manipulate the results and prepare the array;
		//Graeme Tyson, sakwoya@sakwoya.co.uk,  March 2012 for osConcert
		$orders_array=array();
		$coupon_total=0;
		//end
		$output_array=array();
		$row_cnt=0;
		$all_totals=array(TEXT_PRODUCTS=>0);
		for ($icnt=0;$icnt<sizeof($db_result);$icnt++) 
		{
			$sql_result=&$db_result[$icnt]["result"];
			$output_array[$row_cnt]=array('type'=>'head','content'=>$db_result[$icnt]["display"]);
			$row_cnt++;
			$output_array[$row_cnt]=array('type'=>'startdiv');
			$row_cnt++;
			$total=0;
			$sub_total=0;
			$last_row=0;
			$i=1;
			$prev_user="";
			$prev_item=0;
			$net_total=0;
    /// product loop starts here
	while($row = tep_db_fetch_array($sql_result)) 
	{
			$order_id=(int)$row["orders_id"];  
			$item=$row["item_id"];
			$orders_status=$order_status_array[$row["orders_status"]];
			$ticket_printed=$row["ticket_printed"];
			$payment=$row["payment_method"];
			$shipping=$row["shipping_method"];
			$order_id=(int)$row["orders_id"];
			$events_type=$row['events_type'];
			//family?
			if($events_type=='F')
			{
				$quantity=$row['products_quantity']*FAMILY_TICKET_QTY;
				$family='('.$events_type=$row['events_type'].')';
			}else
			{
				$quantity=$row['products_quantity'];
				$family='';
			}
			//$quantity=$row['products_quantity'];
				
			//SHOW name
			$showname=$row["categories_name"].' '.$family;
			$showvenue=$row["concert_venue"];
			$showdate=$row["concert_date"];
			$showtime=$row["concert_time"];
			
			//ADDRESSS
			$customers_company=$row["customers_company"];
			$customers_street_address=$row["customers_street_address"];
			$customers_suburb=$row["customers_suburb"];
			$customers_city=$row["customers_city"];
			$customers_postcode=$row["customers_postcode"];
			$customers_state=$row["customers_state"];
			$customers_country=$row["customers_country"];
			$customers_telephone=$row["customers_telephone"];
			
			$date_purchased=$row["date_purchased"];
			$season_ticket=$row["products_season"];
			
			if($season_ticket==1)
			{
				$payment="Season Ticket";
			}
			
			$date_id=$row["products_model"];
			if ($date_id=='')
			{
			$date_id="NO DATE ID";
			}
			//unique ticket ID
			//check if GA tickets
			if($row["support_packs_type"]=="G")
			{
				$ticket_id='';
				for($running_number=1;$running_number <= $row['products_quantity']; $running_number++)
				{
				$ticket_id.= $row["orders_id"] .'_'.$item.'_'.$running_number.'<br/>';
				}
			}else
			{
			$ticket_id= $row["orders_id"] .'_'.$item.'_1';
			}
			//$ticket_id=$item;
			$prd_type_where=" and op.products_id='".$item."'";

			$sum_sql="SELECT sum(op.final_price*op.products_quantity) as final_price,op.products_tax from " . 
									TABLE_ORDERS_PRODUCTS . " op where op.orders_id='" . $order_id . "'" . $prd_type_where . " group by orders_id,products_tax";
			$sum_query=tep_db_query($sum_sql);
		//	$sum_sql='';
			$row1=tep_db_fetch_array($sum_query);
			if ($prev_user!=$user) 
			{ 
				// output user name
				if ($sub_total>0) 
				{
					// output sub-total amount
					$output_array[$row_cnt]=array('type'=>'subtotal','content'=>$currencies->format($sub_total));
					$row_cnt++;
					$sub_total=0;
				}
		if ($coupon_total>0) 
		{
			$output_array[$row_cnt]=array('type'=>'coupons','content'=>$currencies->format($coupon_total));
			$row_cnt++;
			$coupon_total=0;
		}
				if ($total>0)
				{
					// output total amount
					$output_array[$row_cnt]=array('type'=>'total','content'=>$currencies->format($total));
					$output_array[$last_row]['amount']=$total;
					$row_cnt++;
					$total=0;
				}
				$output_array[$row_cnt]=array('type'=>'user','content'=>$user,'amount'=>0);
				$last_row=$row_cnt;
				$row_cnt++;
				$prev_user=$user;
				$prev_item=0;
			}
			if ($prev_item!=$item)
			{
				// output item name
				if ($sub_total>0) 
				{
					$output_array[$row_cnt]=array('type'=>'subtotal','content'=>$currencies->format($sub_total));
					$row_cnt++;
					$sub_total=0;
				}
		if ($coupon_total>0) 
		{
			$output_array[$row_cnt]=array('type'=>'coupons','content'=>$currencies->format($coupon_total));
			$row_cnt++;
			$coupon_total=0;
		}
			$output_array[$row_cnt]=array('type'=>'item','content'=>$row['item_name']);
			$row_cnt++;
			$prev_item=$item;
			}
			// if($payment=="Season Ticket")
			//{
				// $row1['final_price']=0;
			// }
			$amount=tep_add_tax($row1['final_price'],$row1["products_tax"]);
			$amount=tep_get_rounded_amount($amount);
			//$amount2=tep_get_rounded_amount($taxed);
			$final_price=$row1['final_price'];
			$id=$item;
			
			$comments_sql = "select	comments from " . TABLE_ORDERS_STATUS_HISTORY . "  where orders_id=".$order_id."  ";
			$comments_sql_result=tep_db_query($comments_sql);
			while($comments_row=tep_db_fetch_array($comments_sql_result))
			{
			$comments=$comments_row["comments"];
			}
				
				
		//require_once(DIR_WS_INCLUDES . 'functions/categories_lookup.php');
		// if needed to display summary or not cartzone
		if (!$summary_show) 
		{
			$output_array[$row_cnt]=array('type'=>'row',
			// 'col13'=>'Name: (' .$row['order_name'] . ') Price: (' . $currencies->format($amount) . ') Seat: (' . $row['item_name'] . ') Show: (' . $showname . ') Date: (' . $showdate . ') Time: (' . $showtime . ') Venue: (' .$showvenue . ') Date ID: (' . $date_id . ')' ,
			
			'col'=>$row['item_name'],
			'col1'=>$row['orders_id'],
			'col2'=>$i,
			'col3'=>$row['order_name'],
			'col4'=>$date_id,
			'col5'=>$orders_status,
			'col6'=>$currencies->format($amount),
			'col7'=>$quantity,
			'col8'=>$row['billing_name'],
			'col9'=>$ticket_id,
			'col10'=>$ticket_printed,
			'col11'=>$showname,
			'col12'=>$payment,
			'col13'=>$date_purchased,
			'col14'=>$shipping,
			'col15'=>$customers_company,
			'col16'=>$customers_street_address,
			'col17'=>$customers_suburb,			
			'col18'=>$customers_postcode,
			'col19'=>$customers_state,
			'col20'=>$row['customers_email_address'],
			'col21'=>$customers_country,
			'col22'=>$customers_telephone,
			'col98'=>$currencies->format($row['products_price']*$row1["products_tax"] / 100),
			'col99'=>$row['support_packs_type'],
			'col100'=>$amount
			);
			
			$row_cnt++;
		}
		$i++;
		$sub_total+=$amount;
		$total+=$amount;
		$net_total+=$amount;
		////Graeme Tyson, sakwoya@sakwoya.co.uk,  March 2012 for osConcert
		//We need to get the coupon values used - these are stored in orders_total with a class=ot_coupon on a per order basis
		//We cannot grab the value of ot_coupon for each iteration of this loop as this will multiply ot_coupon if there is more than one ticket per order
		//So two options - either create an array, add each unique order id to it as we loop and then process that or run a new sql query
		//Trying the array version
		//(1) Create the array above this loop - line 258 above $orders_array=array();
		//(2) Grab each order_id within this loop and add to this array
		$orders_array[]=$order_id;
	}// end of the loop for products
	
				//OK now we have an array of order_id's in this report
				//(3) strip duplicates
				$orders_array=array_unique($orders_array);	
				//(4) now go to orders_total and grab the values for the above
				if($orders_array)
				{
				 $ids = join(',',$orders_array);  
				 $get_sql = "SELECT sum(value) as coupon_total FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id IN ($ids) AND class='ot_coupon'";
				
				 $sum_get_query=tep_db_query($get_sql);
				 $sum_result=tep_db_fetch_array( $sum_get_query);
				 $coupon_total=$sum_result['coupon_total'];
				 }
				 //Graeme Tyson, sakwoya@sakwoya.co.uk,  March  2012 for osConcert				
					
			if ($sub_total>0) 
			{
				$output_array[$row_cnt]=array('type'=>'subtotal','content'=>$currencies->format($sub_total));
				$row_cnt++;
				$sub_total=0;
			}
			//Graeme Tyson, sakwoya@sakwoya.co.uk,  March  2012 for osConcert
			if ($coupon_total>0) 
			{
				$output_array[$row_cnt]=array('type'=>'coupons','content'=>$currencies->format($coupon_total));
				$all_totals[$db_result[$icnt]["coupon"]]=$coupon_total;
				$row_cnt++;
				//$coupon_total=0;
			}
			//Graeme Tyson, sakwoya@sakwoya.co.uk,  March  2012 for osConcert
			if ($total>0)
			{
				$output_array[$row_cnt]=array('type'=>'total','content'=>$currencies->format($total));
				$output_array[$last_row]['amount']=$total;
				$row_cnt++;
				$total=0;
			}
			$all_totals[$db_result[$icnt]["display"]]=$net_total;
						$output_array[$row_cnt]=array('type'=>'enddiv');
			$row_cnt++;
	}//end while
		
		
		$report_filename=sprintf("products_sales_%s_%s",$login_id,time());
		// output to pdf
		if ($post_action=="pdf")
		{
			generate_pdf();
			tep_redirect(DIR_WS_CATALOG . "images/".$report_filename.".pdf");
			return;
		}
		// output to excel
		if ($post_action=="excel")
		{
			generate_excel();
			tep_redirect(DIR_WS_CATALOG . "images/".$report_filename.".csv");
			return;
		}
		// output to excel
		if ($post_action=="excel2")
		{
			generate_excel2();
			tep_redirect(DIR_WS_CATALOG . "images/".$report_filename.".csv");
			return;
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
<?php  include(DIR_WS_INCLUDES."date_format_js.php")?>
<!--tep_href_link(FILENAME_ORDERS,'oID=' . $row["orders_id"] . '&action=edit&return=ps')-->
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
	function showOrderDetails(id)
	{
		/*location.href="customers_orders.php?oID="+id+"&action=edit&return=ps";*/
		location.href="edit_orders.php?action=edit&oID="+id+"&return=cl";
	}
	function doReport(mode) {
		startDate=date_format(document.f.txt_start_date.value,'','y-m-d',true);
		endDate=date_format(document.f.txt_end_date.value,'','y-m-d',true);
		if(startDate>endDate) {
			alert("<?php echo REPORT_START_AFTER_END?>");
			return;
		}
		if (mode==4){
			document.f.post_action.value="excel2"
			document.f.target="_blank";
		} else if (mode==2){
			document.f.post_action.value="pdf";
			document.f.target="_blank";
		} else if (mode==3){
			document.f.post_action.value="excel"
			document.f.target="_blank";
		} else {
			document.f.post_action.value="screen";
			document.f.target="_self";
		}
		if(document.getElementById('selected_category')){
			if(document.getElementById('selected_category').value!=''){
			}
		}
		document.f.submit();
	}
	function checkbox_check()
	{
	if(document.getElementById("chk_event").checked)
	{
//	if(document.getElementById("event_content1")) document.getElementById("event_content1").style.display='';
	document.getElementById("event_content1").style.display='';
	document.getElementById("event_content2").style.display='';
	document.getElementById("event_1").style.display='none';
	document.getElementById("event_2").style.display='none';
	}
	else
	{
//	if(document.getElementById("event_content1")) document.getElementById("event_content1").style.display='none';
	document.getElementById("event_content1").style.display='none';
	document.getElementById("event_content2").style.display='none';
	document.getElementById("event_1").style.display='none';
	document.getElementById("event_2").style.display='';
	}
	}
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" leftmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
	<!-- header //-->
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
	<!-- header_eof //-->
	<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2"><!-- container table //-->
<tr> 
	<!-- body_text //-->	  
	<td width="100%" align=left valign="top">
	<table border="0" width="100%" cellspacing="0" cellpadding="2"><!-- main table //-->
        <tr>
		  	<td>
			<?php 	echo tep_draw_form("f",FILENAME_PRODUCTS_SALES_NEW);
					$report_display='';
					echo tep_draw_hidden_field("post_action","screen");
					//echo tep_draw_hidden_field("selected_option",(($FREQUEST->postvalue('selected_category')!='')?($FREQUEST->postvalue('selected_category')):''));
					echo tep_draw_hidden_field("selected_option",(($selected_category!='')?(strtolower($selected_category)):'product'));
			?>
			<table border="0" cellspacing="2" cellpadding="2" class="searchArea" width="100%">
				<tr>
					<td nowrap="true" colspan="2"><?php 
					$_array=array('d','m','Y');  
					$replace_array=array('DD','MM','YYYY'); 	
					$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);
					echo TEXT_FROM . '&nbsp;' . tep_draw_input_field("txt_start_date",format_date($date_begin),'maxlength="10" size="10"');?>
						<!--a href="javascript:show_calendar('f.txt_start_date',null,null,'<?php echo $date_format;?>');"
						onmouseover="window.status='Date Picker';return true;"
						onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/>						</a-->
						<?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . TEXT_TO . '&nbsp;' . tep_draw_input_field("txt_end_date",format_date($date_end),'maxlength="10" size="10"');?>
						<!--a href="javascript:show_calendar('f.txt_end_date',null,null,'<?php echo $date_format;?>');"
						onmouseover="window.status='Date Picker';return true;"
						onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/>						</a-->
                                            <br>
<br>
	<?php 
    if($selected_category!='')
	{
      //echo "&nbsp;". ucfirst($selected_category) ."&nbsp;";
        switch(strtolower($selected_category))
		{
            case 'product':
            //cartzone
				//echo '&nbsp;&nbsp;' . TEXT_USERS . ': '.tep_draw_pull_down_menu("sel_user",$users_array,$users);
 				echo '&nbsp;&nbsp;' . tep_draw_pull_down_menu("sel_staff",$call_staff_array,$staff);
                echo '&nbsp;&nbsp;' . TEXT_DATEID . ': '.tep_draw_pull_down_menu("sel_model",$model_array,$model);

	 			echo '&nbsp;&nbsp;' . STATUS . ': ' . tep_draw_pull_down_menu("sel_status",$status_array,$order_status);
                echo '&nbsp;&nbsp;' . tep_draw_checkbox_field("chk_summary",1,$summary_show,'','onClick="javascript:check(1)"') . SUB_TOTALS;
                break;	
        }
    }	?></td>
					<td valign="middle" align="right">
						<?php echo '<a href="javascript:doReport(1);">' . tep_image_button('', IMAGE_SEARCH_DETAILS) . '</a>'; ?>					</td>
				</tr>
				<tr>
					<td nowrap="true"></td>
					<td>&nbsp;</td>
					<td class="main" valign="middle" align="right">
						<?php echo '<a href="javascript:doReport(2);">' . tep_image_button('', IMG_EXPORT_PDF) . '</a>';?></td>
				</tr>
				<tr>
					<td nowrap="true" id="event_content2" >&nbsp;</td>
					<td id="event_2"></td>
					<td class="main" valign="middle" align="right" >
						<?php echo '<a href="javascript:doReport(3);">' . tep_image_button('', IMG_EXPORT_EXCEL) . '</a>';?></td>
				</tr>
                <tr>
					<td nowrap="true" id="event_content2" >&nbsp;</td>
					<td id="event_2"></td>
					<td class="main" valign="middle" align="right" >
						<?php echo '<a href="javascript:doReport(4);">' . tep_image_button_sm('', IMG_EXPORT_SPECIAL) . '</a>';?></td>
				</tr>
		</table><!-- report content table end -->
		</form>
		</td>
	</tr>
	<tr><td class="cell_bg_report_header">&nbsp;</td></tr>
	<tr><td height="5"></td></tr>
	<?php if ($found_results) 
		{ ?>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="2"><!-- count listings table //-->
			<tr>
				<td width="80%" align="left" class="smallText">
					PAGE: <?php if($query_split) echo $query_split->display_links($query_split_numrows, REPORT_MAX_ROWS_PAGE, REPORT_MAX_LINKS_PAGE, $page,tep_get_report_params()); ?>&nbsp;  <?php echo TOTAL_COUNT;?>: <b><?php echo $total_record_in_db ;?></b>	&nbsp;  <?php echo TEXT_TICKETS_SOLD;?><b><?php echo $tickets ;?></b>			</td>
			    <td width="20%" align="left" class="smallText">&nbsp;</td>
			</tr>
		</table><!-- count listings table end //-->
		</td>
	</tr>
	<?php }?>
	<tr>
		<td>
	   <table border="0" width="100%" cellspacing="1" cellpadding="1"><!-- content report table //-->
							<tr>
                <?php
				if ($found_results) 
				{
					$close_tag=false;
					//$report_display='';
					$output_limit=sizeof($output_array);
					for ($icnt=0;$icnt<$output_limit;$icnt++) {
						$type=$output_array[$icnt]['type'];
						$element=&$output_array[$icnt];
						if ($type=='head'){
							if(strtolower($element['content'])==strtolower($selected_category))
							{
								$report_display_status=false;
								$report_display='accept';
							} else
							{
								$report_display_status=true;
								$report_display='reject';
							}
							$net_total=$all_totals[$element['content']];

?>


							<td class="contentTitle">
							<?php echo TEXT_NET_TOTAL_THIS_PAGE; ?> 
							<?php echo $currencies->format(tep_get_rounded_amount($net_total))?>
                            &nbsp;::&nbsp;
                            <?php echo TEXT_MAIN_GRAND_TOTAL; ?> 
                            
                            <?php echo $new_tot_amount;?>
							<?php tep_content_title_top($element['content'],'','',$report_display_status);?></td>
							</tr>
							<tr>
                            	<td><?php echo tep_draw_separator('pixel_trans.gif',1,5);?></td>
                            </tr>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<?php
			} else if ($type=='startdiv') 
			{ ?>
                            </table>
							<tr>
								<td>
                        <!--if you add more listings ...don't forget the colspan for GRAND_TOTAL
                       #1 add the field name to the SQL (line 189)
                        #2 add to output_array (line 312)
                        #3 add to result/array (line 237)
                        #4 add to heading list (line 602)
                        #5 add to content (line 718)
                        #6 add colspan +1 (line 690)
                        #7 add to pdf (line 855)
                        #8 add to pdf content (line 875)
                        #9 add to csv heading (line 934)
                        #10 add to csv/content (line 956)
                        -->
						<table border="0" cellpadding="0" cellspacing="0" width="100%"><!-- grand total table //-->
						<tr class='dataTableHeadingTitleRow'>
						<td class='dataTableHeadingTitleContent' width="75"><?php echo TEXT_ID;?></td>
                        <td class='dataTableHeadingTitleContent' width="50"><?php echo ORDER_ID;?></td>
                        <td class='dataTableHeadingTitleContent' width="75"><?php echo TICKET_ID;?></td>
                        <td class='dataTableHeadingTitleContent' width="50"><?php echo TICKET_PRINTED;?></td>
                        <td class='dataTableHeadingTitleContent' width="75"><?php echo DATE_ID;?></td>
                        <td class='dataTableHeadingTitleContent' width="225"><?php echo SHOW;?></td>
						<td class='dataTableHeadingTitleContent' width="100"><?php echo TEXT_CLIENT;?></td>	
						<td class='dataTableHeadingTitleContent' width="100" ><?php echo TEXT_BILLING_NAME;?></td>
						 <!--cartzone<td class='dataTableHeadingTitleContent' width="50"><?php //echo TEXT_EMAIL;?></td>-->
                        <td class='dataTableHeadingTitleContent' width="50"><?php echo TEXT_QTY;?></td>
						<td class='dataTableHeadingTitleContent' width="50"><?php echo TEXT_STATUS;?></td>
                        <td class='dataTableHeadingTitleContent' width="50"><?php echo TEXT_PAYMENT;?></td>
                        <td class='dataTableHeadingTitleContent' width="25"></td>
						<td class='dataTableHeadingTitleContent' width="75" align="right"><?php echo TEXT_AMOUNT;?></td>
                        </tr>
			<?php		
			} else if ($type=='enddiv') 
			{ ?>
							</table><!-- content listings table //-->
								</td>
							</tr>
							
							<tr height="20" style="background:#EEEEEE;">
								<td align="right">
									<table border="0" cellpadding="0" cellspacing="0" width="30%"><!-- tickets sold //-->									<tr>
											<td width="64%" class="dataTableContent" style="cursor:pointer;cursor:hand" align="right" valign="middle"><b><?php echo TEXT_TICKETS_SOLD; ?></b>
											</td>
											<td style="cursor:hand" align="right" class='dataTableContent'>
												<?php echo $tickets; ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							
							<tr height="20" style="background:#EEEEEE;">
								<td align="right">
									<table border="0" cellpadding="0" cellspacing="0" width="30%"><!-- tickets sold //-->									<tr>
											<td width="64%" class="dataTableContent" style="cursor:pointer;cursor:hand" align="right" valign="middle"><b><?php echo TEXT_COUPONS; ?></b>
											</td>
											<td style="cursor:hand" align="right" class='dataTableContent'>
												<?php echo $currencies->format(tep_get_rounded_amount($coupon_total)); ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							
							
							
			<?php		if (($report_display=='accept') && (tep_get_rounded_amount($net_total)>0))
						{ ?>
							<tr height="20" style="background:#EEEEEE;">
								<td align="right">
									<table border="0" cellpadding="0" cellspacing="0" width="30%"><!-- grand total table //-->									<tr>
											<td width="64%" class="dataTableContent" style="cursor:pointer;cursor:hand" align="right" valign="middle"><b><?php echo TEXT_NET_TOTAL_THIS_PAGE;?></b>
											</td>
											<td style="cursor:hand" align="right" class='dataTableContent'>
												<?php //echo $currencies->format(tep_get_rounded_amount($net_total)); 
												?>
												<?php echo $tot_amount; ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
                            <!--add NET TOTAL-->
                            <tr height="20" style="background:#ccc;display:true">
								<td align="right">
									<table border="0" cellpadding="0" cellspacing="0" width="30%"><!-- net total table //-->
										<tr>
											<td width="64%" class="dataTableContent" style="cursor:pointer;cursor:hand" align="right" valign="middle"><b><?php echo TEXT_MAIN_GRAND_TOTAL; ?> </b>
											</td>
											<td style="cursor:hand" align="right" class='dataTableContent'>
												<?php echo $new_tot_amount;?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
			<?php		} 
						else
						{ ?>
				<tr height="20">
					<td style="padding:30 0 30 0;" class="main" align="center"><b><?php 
					if ($netvalue==0)
					{ 
					echo ''; 
					} 
					else
					{ 
					echo NO_DETAILS_FOUND;
					}
					?></b></td>
				</tr>
			<?php 
					} ?>
							<tr>
								<td><?php echo tep_draw_separator('pixel_trans.gif',10,10);?></td>
							</tr>
							<?php tep_content_title_bottom();?>
			<?php 
			} else if (($type=='subtotal') && ($report_display=='accept')) 
			
						{ ?>
            <?php if ($subtotal_show) 
							{ ?>
							<tr height="20" style="display:true">
                            <td></td>
								<td class="dataTableContent" style="cursor:pointer;cursor:hand;" colspan="11" align="right" valign="middle"><b><?php echo TEXT_SUB_TOTAL;?></b>
                                
								</td>
								<td style="cursor:hand" align="right" class="dataTableContent">
									<?php 
									
									
									echo $element['content'];?>
								</td>
							</tr>
                           
							<tr>
								<td colspan="5"><?php echo tep_draw_separator('pixel_grey.gif',1,1);?></td>
							</tr>
                            <?php 
							} ?>
            <?php 
						} 
                            //Graeme Tyson, sakwoya@sakwoya.co.uk,  March 2012 for osConcert
			else if (($type=='coupons') && ($report_display=='accept')) 
						{ ?>
							<tr height="20">
								<td class="dataTableContent" style="cursor:pointer;cursor:hand;" colspan="6" align="right" valign="middle">
									<b><?php echo TEXT_COUPONS;?></b>
								</td>
								<td  colspan="6" style="cursor:hand" align="right" class="dataTableContent">
									<?php echo $element['content'];?>
								</td>
							</tr>
							<tr>
								<td colspan="5"><?php echo tep_draw_separator('pixel_grey.gif',1,1);?></td>
							</tr>
			
			<!--Graeme Tyson, sakwoya@sakwoya.co.uk,  March 2012 for osConcert-->
        
			<?php 
						} else if (($type=='total') && ($report_display=='accept'))
						{ ?>
							<tr height="20">
                            <td></td>
								<td class="dataTableContent" style="cursor:pointer;cursor:hand" colspan="11" align="right" valign="middle"><b><?php echo TEXT_GRAND_TOTAL;?></b>
								</td>
								<td style="cursor:hand" align="right" colspan="2" class='dataTableContent'>
									<?php echo $element['content']; ?><!-- grand total //-->
								</td>
							</tr>
							<tr>
								<td><?php echo tep_draw_separator('pixel_trans.gif',15,10);?></td>
							</tr>
                        	<tr height="30">
                            <td valign="top" align="left">
                          </td>
			<?php 		} else if (($type=='item') && ($report_display=='accept')) 
						{ ?>
							<tr height="20">
								<td class="dataTableContent" colspan="8" valign="middle">
									<b>
									<?php 
									//seat names
									echo $element['content'];?>
									</b>
								</td>
							</tr>
			<?php 		} else if (($type=='row') && ($report_display=='accept')) 
						{
						  if($disp_class=='dataTableRowOver')
						  	$disp_class='dataTableRowOver';
						  else
						  	$disp_class='dataTableRowOver';
					?>
						 <tr class='<?php echo $disp_class;?>' height="20" style="cursor:pointer;cursor:hand"  onClick="javascript:showOrderDetails('<?php echo $element["col1"];?>')">
                          <td class='dataTableContent' style="cursor:hand"><?php echo $element['col2'];?></td>
                          <td class='dataTableContent' style="cursor:hand"><?php echo $element['col1'];?></td>
                          <td class='dataTableContent' style="cursor:hand"><?php echo $element['col9'];?></td>
                          <td class='dataTableContent' style="cursor:hand"><?php echo $element['col10'];?></td>
                          <td class='dataTableContent' style="cursor:hand"><?php echo $element['col4'];?></td>
                          <td class='dataTableContent' style="cursor:hand"><?php echo $element['col11'];?></td>
                          <td class='dataTableContent' style="cursor:hand"><?php echo $element['col3'];?></td>
                          <td class='dataTableContent' style="cursor:hand"><?php echo $element['col8'];?></td>
							<!--<td class='dataTableContent' style="cursor:hand" align="center">
							<a href="mailto:<?php //echo $element['col4'];?>">
							<img src="images/icons/mail.gif" border="0"></a></td>-->
                          <td class='dataTableContent' style="cursor:hand"><?php echo $element['col7'];?></td>
                          <td class='dataTableContent' style="cursor:hand"><?php echo $element['col5'];?></td>
                          <td class='dataTableContent' style="cursor:hand"><?php echo $element['col12'];?></td>
                          <td class='dataTableContent'></td>
                          <td class='dataTableContent' style="cursor:hand" align="right"><?php echo $element['col6'];?></td>
						  </tr>
	<?php 				}
				    }	
			}
				else
				{
					echo '<tr><td class="main" align="center">' . TEXT_NO_RECORDS_FOUND . '</td></tr>';
				}
				?>
               
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
		// function to create pdf content
	function generate_pdf()
	{
		global $coupon_total,$output_array,$all_totals,$display_header,$found_results,$login_id,$report_filename,$currencies,$FREQUEST,$selected_category;
		
		// set initial parameters
		$table=new pdfTable("A4","l");
		$table->left_margin=20;
		$table->top_margin=20;
		$table->right_margin=20;
		$table->bottom_margin=20;
		$table->pdfInit();
		// Add Styles for table output
		$table->AddStyle("heading","color:#727272;bgcolor:#ffffff;font:DejaVu;size:18;style:B");
		$table->AddStyle("user","color:#000000;bgcolor:#f1f9fe;font:DejaVu;size:14;style:B;border-color:#7b9ebd");
		$table->AddStyle("subhead","color:#000000;bgcolor:#7b9ebd;font:DejaVu;size:10;style:B");
		$table->AddStyle("headrow","color:#000000;bgcolor:#C9C9C9;font:DejaVu;size:10;style:B");
		$table->AddStyle("row","color:#000000;bgcolor:#F0F1F1;font:DejaVu;size:10");
		$table->AddStyle("query","color:#000000;bgcolor:#FFFFFF;font:DejaVu;size:11");
		$table->AddStyle("subrow","color:#000000;bgcolor:#FFFFFF;font:DejaVu;size:10");
		$table->AddStyle("subrowhead","color:#000000;bgcolor:#FFFFFF;font:DejaVu;size:10;style:B");
		//header text
		$table->headers["text"]=$display_header;
		$table->headers["style"]="query";
		$table->headers["height"]=12;
		$table->headers["width"]="100%";
		$table->headers["cols"]=3;
		//header image
		//$table->headerImage['file']=DIR_FS_TEMPLATES.DEFAULT_TEMPLATE.'/'.DIR_WS_IMAGES . COMPANY_LOGO;
		$table->headerImage['file']=DIR_FS_CATALOG.DIR_WS_IMAGES .'osconcert.jpg';
		$data = getimagesize($table->headerImage['file']);
		$table->headerImage['width']=$data[0];
		$table->headerImage['height']=$data[1];
		
		// define table column width	
		$widths[0]=$table->width*2/100;//0
		$widths[1]=$table->width*12/100;
		$widths[2]=$table->width*4/100;
		$widths[3]=$table->width*8/100;
		$widths[4]=$table->width*4/100;
		$widths[5]=$table->width*12/100;
		$widths[6]=$table->width*12/100;
		$widths[7]=$table->width*25/100;
		$widths[8]=$table->width*6/100;
		$widths[9]=$table->width*4/100;
		$widths[10]=$table->width*6/100;
		//$widths[12]=$table->width*6/100;
		$widths[12]=$table->width*6/100;
		
		
		$widths[13]=$table->width-($widths[0]+$widths[1]+$widths[2]+$widths[3]+$widths[4]+$widths[5]+$widths[6]+$widths[7]+$widths[8]+$widths[9]+$widths[10]+$widths[12]);
		
		$widths[14]=$table->width*87/100;
		$widths[15]=$table->width*4/100;
		$widths[16]=$table->width*1/100;
		$widths[17]=$table->width*65/100;
		$widths[18]=$table->width*43/100;
		$widths[19]=$table->width*81/100;
		
		if ($found_results){
		// output the content	
		$subtotal_show=(($FREQUEST->postvalue("chk_summary")!='')?true:false);
		$report_display_header=strtolower($selected_category);
		for ($icnt=0;$icnt<sizeof($output_array);$icnt++) 
		{
			$type=$output_array[$icnt]['type'];
			$element=$output_array[$icnt];
			$doll=$currencies->format($element["col6"]);
			$cols=array();
			if ($subtotal_show){
			$sub='subtotal';
			}else{
			$sub='';
			}
			if ($type=='head'){
				if($report_display_header==strtolower($element['content']))
				{
					$report_display='accept';
					$net_total=$all_totals[$element['content']];
					//found where to change the heading!!!
									
					$cols[]=array("text"=>TEXT_HEADING_PRODUCTS,"width"=>"100%","align"=>"L","style"=>"heading","valign"=>"M");
					$table->OutputRow($cols,15);
					unset($cols);
					$table->DrawGap(10);
					
				
				//$cols[]=array("text"=>TEXT_ID,"width"=>$widths[0],"align"=>"L","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>SEAT_NAME,"width"=>$widths[1],"align"=>"L","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>ORDER_ID,"width"=>$widths[2],"align"=>"L","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>TICKET_ID,"width"=>$widths[3],"align"=>"L","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>TICKET_PRINTED,"width"=>$widths[4],"align"=>"L","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>TEXT_CLIENT,"width"=>$widths[5],"align"=>"L","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>TEXT_BILLING_NAME,"width"=>$widths[6],"align"=>"L","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>SHOW,"width"=>$widths[7],"align"=>"L","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>DATE_ID,"width"=>$widths[8],"align"=>"L","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>TEXT_QTY,"width"=>$widths[9],"align"=>"L","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>TEXT_STATUS,"width"=>$widths[10],"align"=>"L","style"=>"headrow","valign"=>"M");
				//$cols[]=array("text"=>TEXT_PAYMENT,"width"=>$widths[11],"align"=>"L","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>TEXT_AMOUNT,"width"=>$widths[12],"align"=>"L","style"=>"headrow","valign"=>"M");
				$table->OutputRow($cols,15);
				unset($cols);
				
				} else
				{
					$report_display='reject';
				}
			} else if (($type==$sub) && ($report_display=='accept')) 
			{
				$cols[]=array("text"=>'',"width"=>$widths[19],"align"=>"R","style"=>"subrowhead","valign"=>"M");
				$cols[]=array("text"=>TEXT_SUB_TOTAL,"width"=>$widths[6],"align"=>"L","style"=>"subrowhead","valign"=>"M");
				$cols[]=array("text"=>$element['content'],"width"=>$widths[15],"align"=>"L","style"=>"subrow","valign"=>"M");
				$table->OutputRow($cols,15);
				unset($cols);
				$table->DrawLine();
			//Graeme Tyson, sakwoya@sakwoya.co.uk,  March  2012 for osConcert
				} else if (($type=='coupons') && ($report_display=='accept')) 
				{
				$cols[]=array("text"=>'',"width"=>$widths[18],"align"=>"R","style"=>"subrowhead","valign"=>"M");
				$cols[]=array("text"=>TEXT_COUPONS,"width"=>$widths[7],"align"=>"L","style"=>"subrowhead","valign"=>"M");
				$cols[]=array("text"=>'',"width"=>$widths[7],"align"=>"R","style"=>"subrowhead","valign"=>"M");
				$cols[]=array("text"=>$element['content'],"width"=>$widths[14],"align"=>"L","style"=>"subrow","valign"=>"M");
				$table->OutputRow($cols,15);
				unset($cols);
				$table->DrawLine();
			//Graeme Tyson, sakwoya@sakwoya.co.uk,  March  2012 for osConcert	
			} else if (($type=='total') && ($report_display=='accept'))
			{
				$cols[]=array("text"=>'',"width"=>$widths[14],"align"=>"R","style"=>"subrowhead","valign"=>"M");
				$cols[]=array("text"=>TEXT_GRAND_TOTAL,"width"=>$widths[4],"align"=>"L","style"=>"subrowhead","valign"=>"M");
				$cols[]=array("text"=>'',"width"=>$widths[16],"align"=>"R","style"=>"subrowhead","valign"=>"M");
				$cols[]=array("text"=>$element['content'],"width"=>$widths[15],"align"=>"L","style"=>"subrow","valign"=>"M");
				$height=15;
				$table->OutputRow($cols,25);
				unset($cols);
				$table->DrawGap(6);
			}
			 else if (($type=='item')&& ($report_display=='accept'))
			 {
				//if ($net_total==0){
					//$net_total=0.001;
				//}
				//$percent=number_format(($element['amount']/$net_total)*100,2);
				//$cols[]=array("text"=>sprintf(TEXT_USER_ORDER,$element['content'],$percent),"width"=>"100%","align"=>"L","style"=>"user","valign"=>"M");
				//$table->OutputRow($cols,20);
				//unset($cols);
				$table->DrawGap(6);
			} else if (($type=='row') && ($report_display=='accept'))
			{
				if($element["col99"]=="G"){//GA Ticket
				  //split the HTML string
				  //exit($element["col9"]);
				  $list_tickets=explode("<br/>", rtrim($element["col9"],'<br/>'));
				  if(sizeof($list_tickets)>1)
				  {
				
						foreach($list_tickets as $key=> $value)
						{	unset($cols);
						//unable to handle 

						//$cols[]=array("text"=>$element["col2"],"width"=>$widths[0],"align"=>"L","style"=>"row","valign"=>"M");
						$cols[]=array("text"=>$element["col"],"width"=>$widths[1],"align"=>"L","style"=>"row","valign"=>"M");
						$cols[]=array("text"=>$element["col1"],"width"=>$widths[2],"align"=>"L","style"=>"row","valign"=>"M");
						$cols[]=array("text"=>$value,"width"=>$widths[3],"align"=>"L","style"=>"row","valign"=>"M");
						$cols[]=array("text"=>$element["col10"],"width"=>$widths[4],"align"=>"L","style"=>"row","valign"=>"M");
						$cols[]=array("text"=>$element["col3"],"width"=>$widths[5],"align"=>"L","style"=>"row","valign"=>"M");
						$cols[]=array("text"=>$element["col8"],"width"=>$widths[6],"align"=>"L","style"=>"row","valign"=>"M");
						$cols[]=array("text"=>$element["col11"],"width"=>$widths[7],"align"=>"L","style"=>"row","valign"=>"M");
						$cols[]=array("text"=>$element["col4"],"width"=>$widths[8],"align"=>"L","style"=>"row","valign"=>"M");
						$cols[]=array("text"=>"1","width"=>$widths[9],"align"=>"L","style"=>"row","valign"=>"M");
						$cols[]=array("text"=>$element["col5"],"width"=>$widths[10],"align"=>"L","style"=>"row","valign"=>"M");
						//$cols[]=array("text"=>$element["col12"],"width"=>$widths[10],"align"=>"L","style"=>"row","valign"=>"M");							
						//	$cols[]=array("text"=>$doll,"width"=>$widths[3],"align"=>"R","style"=>"row","valign"=>"M");	
						$cols[]=array("text"=> $currencies->format($element["col100"]/ sizeof($list_tickets)),"width"=>$widths[12],"align"=>"L","style"=>"row","valign"=>"M");
						$table->OutputRow($cols,15);

						}
				  
				  }
				  
				  else
				{//just one ticket strip out the only BR				  
					$element["col9"]=$list_tickets[4];
					// and process as normal
			    
				//$cols[]=array("text"=>$element["col2"],"width"=>$widths[0],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col"],"width"=>$widths[1],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col1"],"width"=>$widths[2],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col9"],"width"=>$widths[3],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col10"],"width"=>$widths[4],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col3"],"width"=>$widths[5],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col8"],"width"=>$widths[6],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col11"],"width"=>$widths[7],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col4"],"width"=>$widths[8],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col7"],"width"=>$widths[9],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col5"],"width"=>$widths[10],"align"=>"L","style"=>"row","valign"=>"M");
				//$cols[]=array("text"=>$element["col12"],"width"=>$widths[10],"align"=>"L","style"=>"row","valign"=>"M");							
			//	$cols[]=array("text"=>$doll,"width"=>$widths[3],"align"=>"R","style"=>"row","valign"=>"M");	
				$cols[]=array("text"=>$element['col6'],"width"=>$widths[11],"align"=>"L","style"=>"row","valign"=>"M");
				$table->OutputRow($cols,15);
				}
			}else
					
				{	
				//here
			    
				//$cols[]=array("text"=>$element["col2"],"width"=>$widths[0],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col"],"width"=>$widths[1],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col1"],"width"=>$widths[2],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col9"],"width"=>$widths[3],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col10"],"width"=>$widths[4],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col3"],"width"=>$widths[5],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col8"],"width"=>$widths[6],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col11"],"width"=>$widths[7],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col4"],"width"=>$widths[8],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col7"],"width"=>$widths[9],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col5"],"width"=>$widths[10],"align"=>"L","style"=>"row","valign"=>"M");
				//$cols[]=array("text"=>$element["col12"],"width"=>$widths[11],"align"=>"L","style"=>"row","valign"=>"M");							
				//$cols[]=array("text"=>$doll,"width"=>$widths[3],"align"=>"L","style"=>"row","valign"=>"M");	
				$cols[]=array("text"=>$element['col6'],"width"=>$widths[12],"align"=>"L","style"=>"row","valign"=>"M");
				$table->OutputRow($cols,15);
				}
				
			} else if(($type=='enddiv') && ($report_display=='accept'))
			{	
				if(tep_get_rounded_amount($net_total)>0)
				{
					
					
				
				
				$cols[]=array("text"=>'',"width"=>$widths[17],"align"=>"R","style"=>"subrowhead","valign"=>"M");
				$cols[]=array("text"=>TEXT_MAIN_GRAND_TOTAL,"width"=>$widths[7],"align"=>"L","style"=>"subrowhead","valign"=>"M");
				$cols[]=array("text"=>'',"width"=>$widths[2],"align"=>"R","style"=>"subrowhead","valign"=>"M");
				$cols[]=array("text"=>$currencies->format(tep_get_rounded_amount($net_total)),"width"=>$widths[2],"align"=>"L","style"=>"subrow","valign"=>"M");
				}
				$height=15;
				$table->OutputRow($cols,12);
				
				unset($cols);
				$table->DrawGap(6);
			} 
		  }
//		}
		} else 
		{
			$cols[]=array("text"=>TEXT_NO_RECORDS_FOUND,"width"=>"100%","align"=>"C","style"=>"subrow","valign"=>"M");
			$table->OutputRow($cols,15);
			unset($cols);
		}
		// create the pdf
		$table->Render($report_filename.".pdf");
		unset($table);
	}
	// function to create excel content
	function generate_excel()
	{
		global $coupon_total,$output_array,$all_totals,$tickets,$found_results,$login_id,$report_filename,$selected_category,$currencies;
		$result="";
		if ($found_results){
		$report_display_header=strtolower($selected_category);
		
		for ($icnt=0;$icnt<sizeof($output_array);$icnt++) {
			$type=$output_array[$icnt]['type'];
			$element=&$output_array[$icnt];
			$cols=array();
			if ($subtotal_show){
			$sub='subtotal';
			}else{
			$sub='';
			}
			if ($type=='head')
			{
				if($report_display_header==strtolower($element['content'])){
					$report_display='accept';
					$net_total = $all_totals[$element['content']];//+++++++++ this is the grand_total
					//$result.=$element['content'] . "\n";
					$result.="" . TEXT_SEATS . "\n";
					$result.=TEXT_ID . "," . SEAT_NAME . "," . ORDER_ID . "," . TICKET_ID . "," . TICKET_PRINTED . "," . DATE_ID . "," . SHOW . "," . TEXT_CLIENT . "," . TEXT_BILLING_NAME . "," . QTY . "," . TEXT_STATUS . "," . TEXT_PAYMENT . "," . TEXT_PURCHASED . "," . TEXT_AMOUNT . "," . TEXT_TAX . "\n";
				} else{
					$report_display='reject';
				}
			} else if (($type==$sub) && ($report_display=='accept')){//
				$result.=",," . TEXT_SUB_TOTAL . ",\"" . $element['content'] . "\"\n";//
			} else if (($type=='total') && ($report_display=='accept')){
			///////////////////////////////////////////////////////////////////////////////////////////
				$result.=",," . TEXT_GRAND_TOTAL . ",\"" . $element['content'] . "\"\n";
			} else if (($type=='user') && ($report_display=='accept')){
				if ($net_total==0){
					$net_total=0.001;
				}
				$percent=number_format(($element['amount']/$net_total)*100,2);
				$result.=sprintf(TEXT_USER_ORDER,$element['content'],$percent) . "\n";
				//Code change RM 
				//$result.=SEAT . "," . ORDER_ID . "," . TICKET_ID . "," . DATE_ID . "," . TEXT_CLIENT . "," . TEXT_BILLING_NAME . "," . QTY . "," . TEXT_STATUS . "," . TEXT_AMOUNT . "\n";
			//} else if (($type=='item') && ($report_display=='accept')){
				//$result.=$element["content"] . "\n";
			} else if (($type=='row') && ($report_display=='accept')){
			
				//col9=ticket-id
				//col99=G
				//col7 = quantity
				//col20 email address
				//col6 total
#################################################################################################################################################
//ADD AN ELEMENT TO THE END OF THESE RESULTS TO QUICKLY LIST ASSOCIATED DATA IN THE CSV DOWNLOAD: EMAIL ADDRESS APPENDS HERE AS AN EXAMPLE
#################################################################################################################################################
// commas break the csv - for Excel this may be fixed by enclosing the cell data in quotes
########################################################################################################				
			if($element["col99"]=="G")
			{//GA Ticket
			//split the HTML string
			$list_tickets=explode("<br/>", rtrim($element["col9"],'<br/>'));
			if(sizeof($list_tickets)>1)
			{
			foreach($list_tickets as $key=> $value)
			{
			$result.=$element["col2"] . "," . utf8_decode($element["col"]) . "," . utf8_decode($element["col1"]) . "," . $value . "," . $element["col10"] . "," . $element["col4"] . "," . $element["col11"] . "," . utf8_decode($element["col3"]) . "," . utf8_decode($element["col8"]) . "," .  "1," . utf8_decode($element["col5"]) . "," . utf8_decode($element["col12"]) . "," . $element["col13"] . "," .  $currencies->format($element["col100"]/ sizeof($list_tickets)) . "," . $element["col98"] . "," . $element["col20"] . "\n";
			}

			}else{//just one ticket strip out the only BR				  
			$element["col9"]=$list_tickets[0];
			// and process as normal
			$result.=
			$element["col2"] . "," . utf8_decode($element["col"]) . "," . utf8_decode($element["col1"]) . "," . $element["col9"] . "," . $element["col10"] . "," . $element["col4"] . "," . $element["col11"] . "," . utf8_decode($element["col3"]) . "," . utf8_decode($element["col8"]) . "," . $element["col7"] . "," . utf8_decode($element["col5"]) . "," . utf8_decode($element["col12"]) . "," . $element["col13"] . "," . $element["col6"] . "," . $element["col98"] . "," . $element["col20"] . "\n";
			}
			}else
			{			

			$result.=$element["col2"] . "," . utf8_decode($element["col"]) . "," . utf8_decode($element["col1"]) . "," . $element["col9"] . "," . $element["col10"] . "," . $element["col4"] . "," . $element["col11"] . "," . utf8_decode($element["col3"]) . "," . utf8_decode($element["col8"]) . "," . $element["col7"] . "," . utf8_decode($element["col5"]) . "," . utf8_decode($element["col12"]) . "," . $element["col13"] . "," . $element["col6"] . "," . $element["col98"] . "," . $element["col20"] . "\n";
			}
							
				
			} else if (($type=='enddiv') && ($report_display=='accept'))
			{
			//Graeme Tyson, sakwoya@sakwoya.co.uk,  March  2012 for osConcert
			//	if(tep_get_rounded_amount($coupon_total)>0)
				{
				$result.=",," . TEXT_COUPONS . ",\"" . $currencies->format(tep_get_rounded_amount($coupon_total)) . "\"\n";
				}
				
				if(tep_get_rounded_amount($net_total)>0){
				$result.=",," . TEXT_TICKETS_SOLD . ",,,,,,,\"" . $tickets. "\"\n";
				}
				//Graeme Tyson, sakwoya@sakwoya.co.uk,  March  2012 for osConcert	
			
				if(tep_get_rounded_amount($net_total)>0){
				$result.=",," . TEXT_MAIN_GRAND_TOTAL . ",\"" . $currencies->format(tep_get_rounded_amount($net_total)) . "\"\n";
				} else{
				$result.=",," . NO_DETAILS_FOUND . "\n";
				}
			}
		}
		} else {
			$result.=TEXT_NO_RECORDS_FOUND;
		}
		
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Length: " . strlen($out));
    // Output to browser with appropriate mime type, you choose ;)
    //header("Content-type: text/x-csv");
    header("Content-type: text/csv");
    //header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=$report_filename");
	header("Pragma: no-cache");
	header("Expires: 0");
    //echo $out;
		tep_write_text_file($report_filename.".csv",$result);
	}
	
	
	// function to create excel content
	function generate_excel2()
	{
		global $coupon_total,$output_array,$all_totals,$tickets,$found_results,$login_id,$report_filename,$selected_category,$currencies;
		$result="";
		if ($found_results){
		$report_display_header=strtolower($selected_category);
		
		for ($icnt=0;$icnt<sizeof($output_array);$icnt++) 
		{
			$type=$output_array[$icnt]['type'];
			$element=&$output_array[$icnt];
			$cols=array();
			if ($subtotal_show)
			{
			$sub='subtotal';
			}else{
			$sub='';
			}
			if ($type=='head')
			{
				if($report_display_header==strtolower($element['content']))
				{
					$report_display='accept';
					$net_total = $all_totals[$element['content']];//+++++++++ this is the grand_total
					//$result.=$element['content'] . "\n";
					$result.="" . TEXT_SEATS . "\n";
					$result.=TEXT_ID . "," . SEAT_NAME . "," . ORDER_ID . "," . TICKET_ID . "," . DATE_ID . "," . SHOW . "," . TEXT_CLIENT . "," . TEXT_BILLING_NAME . "," . TEXT_COMPANY . "," . TEXT_STREET_ADDRESS . "," . TEXT_SUBURB . "," . TEXT_POSTCODE . "," . TEXT_STATE . "," . TEXT_TELEPHONE . "\n";
				} else
				{
					$report_display='reject';
				}
			} else if (($type==$sub) && ($report_display=='accept'))
			{//
				$result.=",," . TEXT_SUB_TOTAL . ",\"" . $element['content'] . "\"\n";//
			} else if (($type=='total') && ($report_display=='accept'))
			{
			///////////////////////////////////////////////////////////////////////////////////////////
				$result.=",," . TEXT_GRAND_TOTAL . ",\"" . $element['content'] . "\"\n";
			} else if (($type=='user') && ($report_display=='accept'))
			{
				if ($net_total==0)
				{
					$net_total=0.001;
				}
				$percent=number_format(($element['amount']/$net_total)*100,2);
				$result.=sprintf(TEXT_USER_ORDER,$element['content'],$percent) . "\n";
				
				//Code change RM 
				//$result.=SEAT . "," . ORDER_ID . "," . TICKET_ID . "," . DATE_ID . "," . TEXT_CLIENT . "," . TEXT_BILLING_NAME . "," . QTY . "," . TEXT_STATUS . "," . TEXT_AMOUNT . "\n";
			//} else if (($type=='item') && ($report_display=='accept')){
				//$result.=$element["content"] . "\n";
				
				
			} else if (($type=='row') && ($report_display=='accept'))
			{
			
				//col9=ticket-id
				//col99=G
				//col7 = quantity
				//col20 email address
				//col6 total
				#################################################################################################################################################
				//ADD AN ELEMENT TO THE END OF THESE RESULTS TO QUICKLY LIST ASSOCIATED DATA IN THE CSV DOWNLOAD: EMAIL ADDRESS APPENDS HERE AS AN EXAMPLE
				#################################################################################################################################################
				// commas break the csv - for Excel this may be fixed by enclosing the cell data in quotes
				########################################################################################################				
			if($element["col99"]=="G")
			{//GA Ticket
			//split the HTML string
			$list_tickets=explode("<br/>", rtrim($element["col9"],'<br/>'));
				if(sizeof($list_tickets)>1)
				{
					foreach($list_tickets as $key=> $value)
					{

					$result.=
					// $element["col2"] . "," . utf8_decode($element["col"]) . "," . utf8_decode($element["col1"]) . "," . $value . "," . $element["col10"] . "," . $element["col4"] . "," . $element["col11"] . "," . utf8_decode($element["col3"]) . "," . utf8_decode($element["col8"]) . "," .  "1," . utf8_decode($element["col5"]) . "," . utf8_decode($element["col12"]) . "," . $element["col13"] . "," .  $currencies->format($element["col100"]/ sizeof($list_tickets)) . "," . $element["col98"] . "\n";
					$element["col2"] . "," . utf8_decode($element["col"]) . "," . $element["col1"] . "," . $element["col9"] . "," . $element["col4"] . "," . utf8_decode($element["col11"]) . "," . utf8_decode($element["col3"]) . "," . utf8_decode($element["col8"]) . "," . utf8_decode($element["col15"]) . "," . utf8_decode($element["col16"]) . "," . utf8_decode($element["col17"]) . "," . utf8_decode($element["col18"]) . "," . utf8_decode($element["col19"]) . "," . $element["col22"] . "\n";
					}

				}else
				{//just one ticket strip out the only BR				  
				$element["col9"]=$list_tickets[0];
				// and process as normal
				$result.=
				// $element["col2"] . "," . utf8_decode($element["col"]) . "," . utf8_decode($element["col1"]) . "," . $element["col9"] . "," . $element["col10"] . "," . $element["col4"] . "," . $element["col11"] . "," . utf8_decode($element["col3"]) . "," . utf8_decode($element["col8"]) . "," . $element["col7"] . "," . utf8_decode($element["col5"]) . "," . utf8_decode($element["col12"]) . "," . $element["col13"] . "," . $element["col6"] . "," . $element["col98"] . 
				
				
				$element["col2"] . "," . utf8_decode($element["col"]) . "," . $element["col1"] . "," . $element["col9"] . "," . $element["col4"] . "," . utf8_decode($element["col11"]) . "," . utf8_decode($element["col3"]) . "," . utf8_decode($element["col8"]) . "," . utf8_decode($element["col15"]) . "," . utf8_decode($element["col16"]) . "," . utf8_decode($element["col17"]) . "," . utf8_decode($element["col18"]) . "," . utf8_decode($element["col19"]) . "," . $element["col22"] . "\n";
				}
			}else
				{			

				$result.=
				
				// $element["col2"] . "," . utf8_decode($element["col"]) . "," . utf8_decode($element["col1"]) . "," . $element["col9"] . "," . $element["col10"] . "," . $element["col4"] . "," . $element["col11"] . "," . utf8_decode($element["col3"]) . "," . utf8_decode($element["col8"]) . "," . $element["col7"] . "," . utf8_decode($element["col5"]) . "," . utf8_decode($element["col12"]) . "," . $element["col13"] . "," . $element["col6"] . "," . $element["col98"] . "\n";
				$element["col2"] . "," . utf8_decode($element["col"]) . "," . $element["col1"] . "," . $element["col9"] . "," . $element["col4"] . "," . utf8_decode($element["col11"]) . "," . utf8_decode($element["col3"]) . "," . utf8_decode($element["col8"]) . "," . utf8_decode($element["col15"]) . "," . utf8_decode($element["col16"]) . "," . utf8_decode($element["col17"]) . "," . utf8_decode($element["col18"]) . "," . utf8_decode($element["col19"]) . "," . $element["col22"] . "\n";
				}
							
				
			} else if (($type=='enddiv') && ($report_display=='accept'))
			{
			//Graeme Tyson, sakwoya@sakwoya.co.uk,  March  2012 for osConcert
			//	if(tep_get_rounded_amount($coupon_total)>0)
				{
				$result.=",," . TEXT_COUPONS . ",\"" . $currencies->format(tep_get_rounded_amount($coupon_total)) . "\"\n";
				}
				
				if(tep_get_rounded_amount($net_total)>0)
				{
				$result.=",," . TEXT_TICKETS_SOLD . ",,,,,,,\"" . $tickets. "\"\n";
				}
				//Graeme Tyson, sakwoya@sakwoya.co.uk,  March  2012 for osConcert	
			
				if(tep_get_rounded_amount($net_total)>0)
				{
				$result.=",," . TEXT_MAIN_GRAND_TOTAL . ",\"" . $currencies->format(tep_get_rounded_amount($net_total)) . "\"\n";
				} 
				else
				{
				$result.=",," . NO_DETAILS_FOUND . "\n";
				}
			}
		}
		} else 
		{
			$result.=TEXT_NO_RECORDS_FOUND;
		}
		
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Length: " . strlen($out));
		// Output to browser with appropriate mime type, you choose ;)
		//header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		//header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=$report_filename");
		header("Pragma: no-cache");
		header("Expires: 0");
		//echo $out;
		tep_write_text_file($report_filename.".csv",$result);
	}

?>
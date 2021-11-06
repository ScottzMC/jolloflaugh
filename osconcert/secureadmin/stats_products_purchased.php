<?php

/*

  

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
http://www.openfreeway.org

Copyright 2007 ZacWare Pty. Ltd 
*/
// Set flag that this is a parent file

  define( '_FEXEC', 1 );
//call center orders
  require('includes/application_top.php');  
  tep_get_last_access_file();
  require('includes/classes/currencies.php');

  require('includes/classes/pdfTable.php');
  require('tfpdf/font/makefont/makefont.php');

//  echo $FSESSION->login_groups_type;
  require(DIR_WS_CLASSES . 'split_page_results_report.php');
  define(BOX_WIDTH1,'125');

	// get initial parameters, try to load from session for previous settings
	if (($FREQUEST->getvalue("return")!='') && ($FSESSION->get("rep_params")!='')){
		$input_params=&$FSESSION->get("rep_params");
	} else{
		$input_params=&$FPOST;
		if (isset($input_params["post_action"])){
			$FSESSION->set("rep_params",$FPOST);
			$GLOBALS["rep_params"]["post_action"]="screen";
		} else{
			$FSESSION->set("rep_params",array());
		}
	}
	$date_begin=isset($input_params['txt_start_date'])?tep_convert_date_raw($input_params['txt_start_date']):'';
	$date_end=isset($input_params['txt_end_date'])?tep_convert_date_raw($input_params['txt_end_date']):'';

	$staff=isset($input_params['sel_staff'])?$input_params['sel_staff']:"All";


	$sel_model=isset($input_params['sel_model'])?$input_params['sel_model']:-1;

	$summary_show=isset($input_params['chk_summary'])?true:false;	

	$post_action = isset($input_params['post_action'])?$input_params['post_action']:'';
	$page=isset($input_params['page'])?$input_params['page']:1;

	$selected_category='product';

	$product_show=((strtolower($selected_category)=='product')?true:true);

	$currencies=new currencies();
	if ($post_action==""){
		$output_pdf=true;
		$post_action="screen";
	}
	if (!$product_show){

		$product_show=true;

	}
	if ($date_begin==""){
		$sql =  "select date_sub('".getServerDate()."', interval 1 month) begin,'".getServerDate()."' as end";
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
	// create header text for pdf content
	$display_header="";
	//$display_header.=TEXT_FROM  . ":&nbsp;&nbsp;" . $date_begin . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	//$display_header.=TEXT_TO  . ":&nbsp;&nbsp;" . $date_end ."\t";
	
	if(strtolower($selected_category)=='product'){
		if($sel_model!=-1){
		$name_query=tep_db_query("select products_model model from ".TABLE_PRODUCTS." where products_id='".$sel_model."'");
		if($product_arr=tep_db_fetch_array($name_query)) $product_name=$product_arr['model'];
		} else{
		$product_name=ALL_SEATS;
		}	

	$display_header.=STORE_NAME_ADDRESS . "&nbsp;&nbsp;" . TEXT_RESERVED . "\t";
}
	
	//if ($staff=="All") $display_header.=TEXT_RESERVED . ":&nbsp;&nbsp;".$product_name  ."\t";
	//$instructors_array = array();
//	$events_array = array();
//	$locations_array=array();
	$order_status_array=array();
	//}
	
	//print_r ($sel_model);
	
	// get all call centre orders
	$sql="SELECT concat(a.admin_firstname,' ',a.admin_lastname) as admin_name from " . TABLE_ADMIN . " a, " . TABLE_ADMIN_GROUPS . 
			" ag where ag.admin_groups_id=a.admin_groups_id  and ag.admin_groups_name='" . TEXT_CALLSTAFF_ENTRY . "' order by admin_firstname,admin_lastname";
//	echo $sql;
	$sql_result=tep_db_query($sql);
	$call_staff_array[]=array("id"=>"All","text"=>TEXT_ALL_STAFFS);
	while($row=tep_db_fetch_array($sql_result)){
		$call_staff_array[]=array("id"=>$row["admin_name"],"text"=>$row["admin_name"]);
		if ($row["admin_name"]==$staff){
			$display_header.=TEXT_CALL_CENTRE_STAFF  . ":&nbsp;&nbsp;" . $row["admin_name"];
		}
	}
	tep_db_free_result($sql_result);

	// get the mysql results;
	$sql="SELECT orders_status_id,orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id='" . (int)$FSESSION->languages_id . "'";
	$sql_result=tep_db_query($sql);
	while($row=tep_db_fetch_array($sql_result)){
		$order_status_array[$row["orders_status_id"]]=$row["orders_status_name"];
	}
		
	$found_results=false;
		$where = " ";
		$db_result=array();
		$num_rows=0;
		$cur_row=0;
		$rows_each=REPORT_MAX_ROWS_PAGE;
		if ($product_show){
			$rows_each=ceil($rows_each/2);
		}
		// if event details is needed to show
		$where=" ";
		if ($product_show) {
			if($staff!="All") {
				$where.= "and customers_name='".tep_db_prepare_input($staff)."' ";
			}
			//if($_POST['sel_name'] == $main){
//				$where.= " and pd.products_name like '" . $m . "' ";
//			}
		//	if($_POST['sel_name'] == $one){
//				$where.= " and op.products_name like '" . $a . "' ";
//			}
//			if($_POST['sel_name'] == $two){
//				$where.= " and op.products_name like '" . $b . "' ";
//			}
//			if($_POST['sel_name'] == $three){
//				$where.= " and op.products_name like '" . $c . "' ";
//			}
//			if($_POST['sel_name'] == $four){
//				$where.= " and op.products_name like '" . $d . "' ";
//			}
			if($_POST['sel_model'] != "All"){
				$where.= " and p.products_model ='".tep_db_prepare_input($_POST['sel_model'])."' ";
			}
		}

			//need to create for GA
			// $sql = "select p.products_id, p.products_ordered, p.products_status, pd.products_name, p.products_model from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . (int)$FSESSION->languages_id. "'
			 // ".$where." group by pd.products_id order by p.products_ordered ASC";
			 
			$sql = "select p.products_id, p.products_ordered, p.products_status, pd.products_name, p.products_model from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . (int)$FSESSION->languages_id. "' and p.products_status = '0'
			 ".$where." group by pd.products_id, p.products_id,p.products_ordered, p.products_status, pd.products_name, p.products_model order by p.products_ordered ASC";
		
			$total_record_in_db = tep_db_num_rows(tep_db_query($sql));
			if ($post_action=="screen"){
				// split the content
				$query_split2 = new splitPageResultsReport($page, $rows_each, $sql, $query_split_numrows2,false);
			}
			$db_result[$cur_row]=array("display"=>TEXT_PRODUCTS , "result"=>tep_db_query($sql));
			$num_rows+=tep_db_num_rows($db_result[$cur_row]["result"]);
			$cur_row++;
			$where='';
		//}
		if ($num_rows>0) {	
			$found_results=true;
			if ($post_action=="screen"){
				if ($query_split_numrows1>=$query_split_numrows2){
					$query_split=&$query_split1;
				} else {
					$query_split=&$query_split2;
				}
				$query_split_numrows=$query_split_numrows1+$query_split_numrows2;
			}
		}

		// manipulate the results and prepare the array;
		$output_array=array();
		$row_cnt=0;
		$all_totals=array(TEXT_PRODUCTS=>0);
		for ($icnt=0;$icnt<sizeof($db_result);$icnt++) {
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
           
			while($row = tep_db_fetch_array($sql_result)) {
            	$user=$row["user_added"];
				$item=$row["item_id"];

				if($row["products_type"]!=''){
					switch($row["products_type"]){
						case 'P':
								$prd_type_where=" and op.products_id='".$item."'";
								break;
								
						default:
							//	$prd_type_where=" and op.events_id='".$item."'";
								$prd_type_where="";
								break;
					}
				}
				
				$sum_sql="SELECT sum(op.final_price*op.products_quantity) as final_price,op.products_tax from " . 
										TABLE_ORDERS_PRODUCTS . " op where op.orders_id='" . (int)$row["orders_id"] . "'" . $prd_type_where . " group by orders_id,products_tax";

				$sum_query=tep_db_query($sum_sql);
			//	$sum_sql='';
				$row1=tep_db_fetch_array($sum_query);
				if ($prev_user!=$user) { 
					// output user name
					if ($sub_total>0) {
						// output sub-total amount
						$output_array[$row_cnt]=array('type'=>'subtotal','content'=>$currencies->format($sub_total));
						$row_cnt++;
						$sub_total=0;
					}
					if ($total>0){
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
				if ($prev_item!=$item){
					// output item name
					if ($sub_total>0) {
						$output_array[$row_cnt]=array('type'=>'subtotal','content'=>$currencies->format($sub_total));
						$row_cnt++;
						$sub_total=0;
					}
					$output_array[$row_cnt]=array('type'=>'item','content'=>$row['item_name']);
					$row_cnt++;
					$prev_item=$item;
				}
				$amount=tep_add_tax($row1['final_price'],$row1["products_tax"]);
				$amount=tep_get_rounded_amount($amount);
				
				// if needed to display summary or not cartzone
				if (!$summary_show) {
					$output_array[$row_cnt]=array('type'=>'row','col1'=>$row["orders_id"],'col2'=>$i,'col3'=>$row['products_name'],
													'col4'=>$row['products_model']
													);
													//$output_array[$row_cnt]=array('type'=>'row','col1'=>$row["orders_id"],'col2'=>$i,'col3'=>$row['products_name'],
//													'col4'=>$row['products_model'],'col5'=>$order_status_array[$row['orders_status']],
//													'col6'=>$currencies->format($amount)
//													);
					$row_cnt++;
				}
				$i++;
				$sub_total+=$amount;
				$total+=$amount;
				$net_total+=$amount;
			}
			if ($sub_total>0) {
				$output_array[$row_cnt]=array('type'=>'subtotal','content'=>$currencies->format($sub_total));
				$row_cnt++;
				$sub_total=0;
			}
			if ($total>0){
				$output_array[$row_cnt]=array('type'=>'total','content'=>$currencies->format($total));
				$output_array[$last_row]['amount']=$total;
				$row_cnt++;
				$total=0;
			}
			$all_totals[$db_result[$icnt]["display"]]=$net_total;
			$output_array[$row_cnt]=array('type'=>'enddiv');
			$row_cnt++;
		}
		$report_filename=sprintf("products_purchased_report_%s_%s",$login_id,time());
		// output to pdf
		if ($post_action=="pdf"){
			generate_pdf();
			tep_redirect(DIR_WS_CATALOG . "images/".$report_filename.".pdf");
			return;
		}
		// output to excel
		if ($post_action=="excel"){
			generate_excel();
			tep_redirect(DIR_WS_CATALOG . "images/".$report_filename.".csv");
			return;
		}
		tep_delete_temp_files("products_purchased_report_" . $login_id);
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
<script language="javascript">
	function showOrderDetails(id){
		location.href="customers_orders.php?action=edit&oID="+id+"&return=cl";
	}
	function doReport(mode) {
		startDate=date_format(document.f.txt_start_date.value,'','y-m-d',true);
		endDate=date_format(document.f.txt_end_date.value,'','y-m-d',true);
		
		if(startDate>endDate) {
			alert("<?php echo REPORT_START_AFTER_END?>");
			return;
		}
		if (mode==3){
			document.f.post_action.value="excel"
			document.f.target="_blank";
		} else if (mode==2){
			document.f.post_action.value="pdf";
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
<style type="text/css">
<!--
.dataTableRowNew {
	background-color: #F3F3F3;
}
-->
</style>
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
		<tr height="10">
			 <td class="pageHeading"><?php echo TEXT_QUICK_REPORT; ?></td> 	
		</tr>
        <tr>
		  	<TD>
			<?php 	echo tep_draw_form("f",FILENAME_STATS_PRODUCTS_PURCHASED);
					$report_display='';
					echo tep_draw_hidden_field("post_action","screen");
//					echo tep_draw_hidden_field("selected_option",(($FREQUEST->postvalue('selected_category')!='')?($FREQUEST->postvalue('selected_category')):''));
					echo tep_draw_hidden_field("selected_option",(($selected_category!='')?(strtolower($selected_category)):'product'));
			?>
			<table border="0" cellspacing="3" cellpadding="2" class="searchArea" width="100%">
				<tr>
					<td nowrap="true" colspan="2"><?php $_array=array('d','m','Y');  $replace_array=array('DD','MM','YYYY'); 	$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>
						<?php echo TEXT_FROM . '&nbsp;' . tep_draw_input_field("txt_start_date",format_date($date_begin),'maxlength="10" size="10"');?>
						<a href="javascript:show_calendar('f.txt_start_date',null,null,'<?php echo $date_format;?>');"
						onmouseover="window.status='Date Picker';return true;"
						onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/></a>
						<?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . TEXT_TO . '&nbsp;' . tep_draw_input_field("txt_end_date",format_date($date_end),'maxlength="10" size="10"');?>
						<a href="javascript:show_calendar('f.txt_end_date',null,null,'<?php echo $date_format;?>');"
						onmouseover="window.status='Date Picker';return true;"
						onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/></a>
						<div style="display:none; float:left;">
						<?php    
					//	echo '&nbsp;' . TEXT_SHOW . '&nbsp;' . tep_draw_checkbox_field("chk_product",0,$product_show) . TEXT_RESERVED;
//						echo '&nbsp;' . tep_draw_checkbox_field("chk_event",0,$event_show,'','onclick="checkbox_check()"') . TEXT_EVENTS;
//						echo '&nbsp;' . tep_draw_checkbox_field("chk_subscription",0,$subscription_show) . TEXT_SUBSCRIPTION;
//						echo '&nbsp;' . tep_draw_checkbox_field("chk_services",0,$services_show,'','onclick="check_services(this)"') . TEXT_SERVICES;
//						echo '&nbsp;' . tep_draw_checkbox_field("chk_summary",0,$summary_show) . TEXT_SUMMARY_ONLY;
//						echo tep_draw_hidden_field('selected_category');
						?>
						</div>
					    <?php 
						$all_seats=ALL_SEATS;
						$all_shows=ALL_SHOWS;
					//	if($selected_category!=''){
//						  echo "&nbsp;". ucfirst($selected_category) ."&nbsp;";
							switch(strtolower($selected_category)){
								
								case 'product':
								
									$name_list = array();
									$name_list[]=array("id"=>"All","text"=>$all_seats);
									
									echo  '&nbsp;&nbsp;'. SEATS . ' : '.tep_draw_pull_down_menu("sel_name",$name_list);

									$p_mod_q = tep_db_query("select distinct(products_model) from products order by products_model");
									$modle_list = array();
									$modle_list[]=array("id"=>"All","text"=>$all_shows);
									while($p_mod_d = tep_db_fetch_array($p_mod_q)){
										$modle_list[] = array("id"=>$p_mod_d["products_model"], "text"=>$p_mod_d["products_model"]);
									}
									echo '&nbsp;&nbsp;'. DATE_ID . ' : '.tep_draw_pull_down_menu("sel_model",$modle_list);
									
									break;
			
							}
							?></td>
					<td valign="middle" align="right">
						<?php echo '<a href="javascript:doReport(1);">' . tep_image_button('button_report_search.gif', IMAGE_SEARCH_DETAILS) . '</a>'; ?>					</td>
				</tr>
				<tr>
					<td nowrap="true">
					<?php if(strtolower($selected_category)=='event'){ ?>
						<div id="event_content1" style="float:left;">
						<?php 	$all_events_array=array();
								$all_events_array = tep_get_events_array_single();
								echo TEXT_INSTRUCTOR . '&nbsp;' . tep_draw_pull_down_menu("sel_instructor",$instructors_array,$instructor) .'&nbsp;&nbsp;';
								echo TEXT_LOCATION . '&nbsp;&nbsp;&nbsp;'  . tep_draw_pull_down_menu("sel_location",$locations_array,$location).tep_draw_separator('pixel_trans.gif',45,10).'&nbsp;&nbsp;';
						?>
						</div>
						<?php }
						//echo TEXT_CALL_CENTRE_STAFF . '&nbsp;' . tep_draw_pull_down_menu("sel_staff",$call_staff_array,$staff);
						 ?>					</td>
					<td id="event_1">&nbsp;</td>
					<td class="main" valign="middle" align="right">
							<?php echo '<a href="javascript:doReport(2);">' . tep_image_button('button_export_pdf.gif', IMG_EXPORT_PDF) . '</a>';?>					</td>
				</tr>
				<tr>
					<td nowrap="true" id="event_content2" >&nbsp;</td>
					<td id="event_2"></td>
					<td class="main" valign="middle" align="right" >
						<?php echo '<a href="javascript:doReport(3);">' . tep_image_button('button_export_excel.gif', IMG_EXPORT_EXCEL) . '</a>';?>					</td>
				</tr>
			<!-- report content -->
		</table>
		</form>
		</td>
	</tr>
	<tr><td class="cell_bg_report_header">&nbsp;</td></tr>
	<tr><td height="5"></td></tr>
	<?php if ($found_results) { ?>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
			<tr>
            <td class="smallText" align="left">
				PAGE: <?php if($query_split) echo $query_split->display_links($query_split_numrows, REPORT_MAX_ROWS_PAGE, REPORT_MAX_LINKS_PAGE, $page,tep_get_report_params()); ?>&nbsp;<?php echo TEXT_TOTAL_SEATS_RESERVED; ?> : <b><?php echo $total_record_in_db ;?></b>&nbsp;</td>
				<td class="smallText" align="left">
</td>
			</tr>
		</table>
		</td>
	</tr>
	<?php }?>
	<tr>
		<td>
	   <table border="0" width="100%" cellspacing="1" cellpadding="1">
			<?php
				if ($found_results) {
					$close_tag=false;
//					$report_display='';
					$output_limit=sizeof($output_array);
					for ($icnt=0;$icnt<$output_limit;$icnt++) {
						$type=$output_array[$icnt]['type'];
						$element=&$output_array[$icnt];
						if ($type=='head'){
							if(strtolower($element['content'])==strtolower($selected_category)){
								$report_display_status=false;
								$report_display='accept';
							} else{
								$report_display_status=true;
								$report_display='reject';
							}
							$net_total=$all_totals[$element['content']];	?>
							<tr>
								<td class="pageHeading"><?php echo TEXT_QUICK_REPORT; ?><?php //tep_content_title_top($element['content'],'','',$report_display_status);?></td>
							</tr>
							<tr><td><?php echo tep_draw_separator('pixel_trans.gif',1,5);?></td></tr>
			<?php		} else if ($type=='startdiv') { ?>
							<tr>
								<td>
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<?php		} else if ($type=='enddiv') { ?>
								</table>
								</td>
							</tr>

			<?php		if (($report_display=='accept') && (tep_get_rounded_amount($net_total)>0)){ ?>
							<tr height="20" style="background:#EEEEEE;">
								<td align="right">
									<table border="0" cellpadding="0" cellspacing="0" width="30%">
										<tr>
											<td width="64%" class="dataTableContent" align="right" valign="middle">
												<b><?php echo TEXT_MAIN_GRAND_TOTAL;?></b>
											</td>
											<td align="right" class='dataTableContent'>
												<?php echo $currencies->format(tep_get_rounded_amount($net_total)); ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
			<?php		} else{ ?>
				<tr height="20">
					<td style="padding:30 0 30 0;" class="main" align="center"><?php //echo NO_DETAILS_FOUND; ?></td>
				</tr>
			<?php } ?>
							<tr>
								<td><?php echo tep_draw_separator('pixel_trans.gif',1,10);?></td>
							</tr>
							<?php tep_content_title_bottom();?>
			<?php		} else if (($type=='subtotal') && ($report_display=='accept')) { ?>
							<tr height="20">
								<td class="dataTableContent"  colspan="4" align="right" valign="middle">
									<b><?php echo TEXT_SUB_TOTAL;?></b>
								</td>
								<td align="right" class='dataTableContent'>
									<?php echo $element['content'];?>
								</td>
							</tr>
							<tr>
								<td colspan="5"><?php echo tep_draw_separator();?></td>
							</tr>
			<?php		} else if (($type=='total') && ($report_display=='accept')){ ?>
							<tr height="20">
								<td class="dataTableContent"  colspan="4" align="right" valign="middle">
									<b><?php echo TEXT_GRAND_TOTAL;?></b>
								</td>
								<td class='dataTableContent'>
									<?php echo $element['content']; ?>
								</td>
							</tr>
							<tr>
								<td><?php echo tep_draw_separator('pixel_trans.gif',1,10);?></td>
							</tr>
			<?php		 } else if (($type=='user') && ($report_display=='accept')) {
							$percent=number_format(($element['amount']/$net_total)*100,2);
						 ?>
							<tr height="30">
								<td valign="top" colspan="6" align="left">
								<table border="0" cellpadding="2" cellspacing="0" class="searchArea" width="90%">
									<tr>
										<td class="TableHeading"><?php echo TEXT_QUICK_REPORT;?><?php //echo sprintf(TEXT_USER_ORDER,$element['content'],$percent);?></td>
									</tr>
								</table>
								</td>
							</tr>
							<tr class='dataTableHeadingTitleRow'>
								<td class='dataTableHeadingTitleContent' width="100"><?php echo TEXT_ID;?></td>
                                <td class='dataTableHeadingTitleContent' width="100"><?php echo ORDER_ID;?></td>
								<td class='dataTableHeadingTitleContent'><?php echo TEXT_CLIENT;?></td>
                                <td class='dataTableHeadingTitleContent' width="150"><?php echo DATE_ID;?></td>
								<td class='dataTableHeadingTitleContent' width="100"><?php echo TEXT_STATUS;?></td>
								<td class='dataTableHeadingTitleContent' width="100" align="right"><?php echo TEXT_AMOUNT;?></td>
							</tr>
			<?php 		} else if (($type=='item') && ($report_display=='accept')) { ?>
							<tr  height=20>
								<td class="dataTableContent" colspan="5" valign="middle">
									<b>
									<?php echo $element['content'];?>
									</b>
								</td>
							</tr>
			<?php 		} else if (($type=='row') && ($report_display=='accept')) {
						  if($disp_class=='dataTableRow')
						  	$disp_class='dataTableRowNew';
						  else
						  	$disp_class='dataTableRow';
					?>
						 <tr class='<?php echo $disp_class;?>' height="20">
							  <td class='dataTableContent'><?php echo $element['col2'];?></td>
							  <td class='dataTableContent'><?php echo $element['col1'];?></td>
                              <td class='dataTableContent'><?php echo $element['col3'];?></td>
                              <td class='dataTableContent'><?php echo $element['col4'];?></td>
							  <td class='dataTableContent'><?php echo $element['col5'];?></td>
							  <td class='dataTableContent' align="right"><?php echo $element['col6'];?></td>
						  </tr>
			<?php 		}
				  }	
				}
				else{
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
	function generate_pdf(){
		global $output_array,$all_totals,$display_header,$found_results,$login_id,$report_filename,$currencies,$FREQUEST,$selected_category;
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

		$table->headers["text"]=$display_header;
		$table->headers["style"]="query";
		$table->headers["height"]=12;
		$table->headers["width"]="100%";
		$table->headers["cols"]=1;

		// define table column width
		$widths[0]=$table->width*5/100;
		$widths[2]=$table->width*20/100;
		$widths[3]=$table->width*10/100;
		$widths[4]=$table->width*10/100;

		$widths[1]=$table->width-($widths[0]+$widths[2]+$widths[3]+$widths[4]);
		
		if ($found_results){
			// output the content		
		$report_display_header=strtolower($selected_category);
		for ($icnt=0;$icnt<sizeof($output_array);$icnt++) {
			$type=$output_array[$icnt]['type'];
			$element=&$output_array[$icnt];
			$doll=$currencies->format($element["col6"]);
			$cols=array();
			if ($type=='head'){
				if($report_display_header==strtolower($element['content'])){
					$report_display='accept';
					$net_total=$all_totals[$element['content']];
					
					//cartzone removed element[content]
					$cols[]=array("text"=>"osConcert Quick Report","width"=>"100%","align"=>"L","style"=>"heading","valign"=>"M");
					$table->OutputRow($cols,20);
					unset($cols);
					$table->DrawGap(10);
				} else{
					$report_display='reject';
				}
			} if (($type=='subtotal') && ($report_display=='accept')) {
				$cols[]=array("text"=>TEXT_SUB_TOTAL,"width"=>$widths[0]+$widths[1]+$widths[2],"align"=>"R","style"=>"subrowhead","valign"=>"M");
				$cols[]=array("text"=>$element['content'],"width"=>$widths[3],"align"=>"R","style"=>"subrow","valign"=>"M");
				$table->OutputRow($cols,15);
				unset($cols);
				$table->DrawLine();
			} else if (($type=='total') && ($report_display=='accept')){
				$cols[]=array("text"=>TEXT_GRAND_TOTAL,"width"=>$widths[0]+$widths[1]+$widths[2],"align"=>"R","style"=>"subrowhead","valign"=>"M");
				$cols[]=array("text"=>$element['content'],"width"=>$widths[3],"align"=>"R","style"=>"subrow","valign"=>"M");
				$height=15;
				$table->OutputRow($cols,15);
				unset($cols);
				$table->DrawGap(6);
			} else if (($type=='user') && ($report_display=='accept')){
				$percent=number_format(($element['amount']/$net_total)*100,2);
				$cols[]=array("text"=>sprintf(TEXT_USER_ORDER,$element['content'],$percent),"width"=>"100%","align"=>"L","style"=>"user","valign"=>"M");
				$table->OutputRow($cols,20);
				unset($cols);
				$table->DrawGap(6);
				$cols[]=array("text"=>TEXT_ID,"width"=>$widths[0],"align"=>"L","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>TEXT_CLIENT,"width"=>$widths[1],"align"=>"L","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>DATE_ID,"width"=>$widths[2],"align"=>"L","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>TEXT_STATUS,"width"=>$widths[3],"align"=>"L","style"=>"headrow","valign"=>"M");
				$cols[]=array("text"=>TEXT_AMOUNT,"width"=>$widths[4],"align"=>"R","style"=>"headrow","valign"=>"M");
				$table->OutputRow($cols,15);
				unset($cols);
			} else if (($type=='item') && ($report_display=='accept')){
				$table->DrawGap(3);
				$cols[]=array("text"=>$element["content"],"width"=>"100%","align"=>"L","style"=>"subrowhead","valign"=>"M");
				$table->OutputRow($cols,15);
				unset($cols);
				$table->DrawGap(3);
			} else if (($type=='row') && ($report_display=='accept')){
				$cols[]=array("text"=>$element["col2"],"width"=>$widths[0],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col3"],"width"=>$widths[1],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col4"],"width"=>$widths[2],"align"=>"L","style"=>"row","valign"=>"M");
				$cols[]=array("text"=>$element["col5"],"width"=>$widths[3],"align"=>"L","style"=>"row","valign"=>"M");							
				//$cols[]=array("text"=>$doll,"width"=>$widths[3],"align"=>"R","style"=>"row","valign"=>"M");	
				//$cols[]=array("text"=>$element['col6'],"width"=>$widths[4],"align"=>"R","style"=>"row","valign"=>"M");
				$table->OutputRow($cols,15);
			} else if(($type=='enddiv') && ($report_display=='accept')){	
				if(tep_get_rounded_amount($net_total)>0){
				$cols[]=array("text"=>TEXT_MAIN_GRAND_TOTAL,"width"=>$widths[0]+$widths[1]+$widths[2],"align"=>"R","style"=>"subrowhead","valign"=>"M");
				$cols[]=array("text"=>$currencies->format(tep_get_rounded_amount($net_total)),"width"=>$widths[3],"align"=>"R","style"=>"subrow","valign"=>"M");
				} else{
				//$cols[]=array("text"=>NO_DETAILS_FOUND,"width"=>$widths[0]+$widths[1],"align"=>"C","style"=>"subrowhead","valign"=>"M");
				}
				$height=15;
				$table->OutputRow($cols,15);
				unset($cols);
				$table->DrawGap(6);
			} 
		  }
//		}
		} else {
			$cols[]=array("text"=>TEXT_NO_RECORDS_FOUND,"width"=>"100%","align"=>"C","style"=>"subrow","valign"=>"M");
			$table->OutputRow($cols,15);
			unset($cols);
		}
		// create the pdf
		$table->Render($report_filename.".pdf");
		unset($table);
	}
	$osconcert="osConcert Quick Report";
	// function to create excel content
	function generate_excel(){
		global $output_array,$all_totals,$found_results,$login_id,$report_filename,$selected_category,$currencies;
		$result="";
		
		if ($found_results){
		$report_display_header=strtolower($selected_category);
		for ($icnt=0;$icnt<sizeof($output_array);$icnt++) {
			$type=$output_array[$icnt]['type'];
			$element=&$output_array[$icnt];
			$cols=array();
			if ($type=='head'){
				if($report_display_header==strtolower($element['content'])){
					$report_display='accept';
					$net_total=$all_totals[$element['content']];
					$result.=$osconcert . "\n";
				} else{
					$report_display='reject';
				}
			} else if (($type=='subtotal') && ($report_display=='accept')){
				$result.=",," . TEXT_SUB_TOTAL . ",\"" . $element['content'] . "\"\n";
			} else if (($type=='total') && ($report_display=='accept')){
				$result.=",," . TEXT_GRAND_TOTAL . ",\"" . $element['content'] . "\"\n";
			} else if (($type=='user') && ($report_display=='accept')){
				$percent=number_format(($element['amount']/$net_total)*100,2);
				$result.=sprintf(TEXT_USER_ORDER,$element['content'],$percent) . "\n";
				//$result.=TEXT_ID . "," . TEXT_ID . "," . TEXT_CLIENT . "," . TEXT_STATUS . "," . TEXT_AMOUNT . "\n";
			} else if (($type=='item') && ($report_display=='accept')){
				$result.=$element["content"] . "\n";
			} else if (($type=='row') && ($report_display=='accept')){
				$result.=$element["col2"] . "," . $element["col3"] . "," . $element["col4"] . "\n";
			} else if (($type=='enddiv') && ($report_display=='accept')){
				if(tep_get_rounded_amount($net_total)>0){
				$result.=",," . TEXT_MAIN_GRAND_TOTAL . ",\"" . $currencies->format(tep_get_rounded_amount($net_total)) . "\"\n";
				} else{
				//$result.=",," . NO_DETAILS_FOUND . "\n";
				}
			}
		}
		} else {
			$result.=TEXT_NO_RECORDS_FOUND;
		}
		tep_write_text_file($report_filename.".csv",$result);
	}
?>
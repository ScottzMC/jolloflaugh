<?php

/*

osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 
Copyright (c) 2003 osCommerce 


Freeway eCommerce 
http://www.openfreeway.org
Copyright (c) 2007 ZacWare

Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'pdfTable.php');
  tep_get_last_access_file();
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $mPath=(int)$FREQUEST->getvalue('mPath');
  $command=$FREQUEST->getvalue('command');
  $post_action=$FREQUEST->postvalue("post_action","string","screen");
  $start_date = tep_convert_date_raw($FREQUEST->postvalue('txt_start_date'));
  $end_date = tep_convert_date_raw($FREQUEST->postvalue('txt_end_date'));
  if($end_date=='')
  		$end_date=getServerDate();
     $display_header="";
	$display_header.=TEXT_START_DATE .  ":&nbsp;&nbsp;" . format_date($start_date) . "&nbsp;&nbsp;&nbsp;&nbsp;";
	$display_header.=TEXT_END_DATE . ":&nbsp;&nbsp;" . format_date($end_date) . "\t";

  if($start_date=="") {
		$res = tep_db_query("SELECT '".$end_date."' - INTERVAL 1 MONTH");
		$row = tep_db_fetch_array($res);
		$start_date = $row[0];
	}

	if($command!='fetch_report_details'){
        $report_filename=sprintf("reports_shipping_%s_%s",$login_id,time());
        if (($FSESSION->get("report_filename")!='')){
        $old_file=DIR_FS_CATALOG . "images/" .$FSESSION->get("sess_report_filename") . ".pdf";
        if (file_exists($old_file)) unlink($old_file);
        $old_file=DIR_FS_CATALOG . "images/" . $FSESSION->get("sess_report_filename") . ".csv";
        if (file_exists($old_file)) unlink($old_file);
        }
        $FSESSION->set("sess_report_filename",$report_filename);
        tep_delete_temp_files("reports_shipping_" . $login_id);
	}
	
	if($command!=''){
	switch($command){
		case 'fetch_report_details':
			echo 'fetch_report_details^^';
			echo generate_shipping_report($start_date,$end_date);
			break;
		case 'pdf':
			echo 'pdf^^';
			generate_pdf();
			echo $report_filename. ".pdf";
			break;
		case 'excel':
			echo 'excell^^';
			generate_excel();
			echo $report_filename . ".csv";
			break;
	}
	exit;
	}
		

	function generate_shipping_report($start_date,$end_date){
	global $currencies,$products_sql;
	if ($start_date!='') $where .= " and date_format(o.date_purchased,'%Y-%m-%d')>='". tep_db_input($start_date) ."'";
	if ($end_date!='')	$where .=" and date_format(o.date_purchased,'%Y-%m-%d')<='" . tep_db_input($end_date) . "'";
	$type_where =" and op.products_type='P'";
	//$type_where =" ";
	$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,ot.value as cost,sum((op.products_quantity*p.products_weight)) as weight,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity) as products_quantity,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,o.delivery_country ,ot.title as shipping_method  from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_TOTAL . " ot, ". TABLE_PRODUCTS . " p where p.products_id=op.products_id and ot.orders_id=o.orders_id and ot.value>0 and ot.class='ot_shipping' and  o.orders_id=op.orders_id  " . $where . $type_where . " and op.orders_products_status>1  and op.orders_products_status<5 group by o.orders_id,op.orders_id,op.products_id,op.products_name,ot.value,op.products_quantity,p.products_weight,o.date_paid,o.date_purchased,o.date_purchased,op.products_quantity,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,o.delivery_country ,ot.title order by o.date_paid,o.orders_id ";
	$products_sql_query=tep_db_query($products_sql);
	echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
	?>
	<tr class="dataTableHeadingTitleRow">
		<td class="dataTableHeadingTitleContent" width="5%"><?php echo  TEXT_INDEX;?></td>
		<td class="dataTableHeadingTitleContent"  width="15%"><?php echo  TEXT_CUSTOMER_NAME;?></td>
		<td class="dataTableHeadingTitleContent"  align="left" width="10%" ><?php echo TEXT_ORDER_ID;?></td>
		<td class="dataTableHeadingTitleContent"  align="left" width="20%"><?php echo TEXT_LOCATION;?></td>
		<td class="dataTableHeadingTitleContent" align="left" nowrap width="35%"><?php echo TEXT_SHIPPING_METHOD;?></td>
		<td class="dataTableHeadingTitleContent"  align="right" width="5%"><?php echo TEXT_WEIGHT;?></td>
		<td class="dataTableHeadingTitleContent"  align="right" width="10%"><?php echo TEXT_COST;?></td>
	</tr>
	<?php
	if(tep_db_num_rows($products_sql_query)>0){
	$row_cnt=1; $class="dataTableRowOdd"; $quan_subtotal=0; $cost=0;
		while($content=tep_db_fetch_array($products_sql_query)){
		$class=($class=='dataTableRowOdd')?'dataTableRowEven':'dataTableRowOdd';
		?>
		<tr class="<?php echo $class; ?>" valign="top" height="20" style="cursor:pointer;cursor:hand" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="javascript:location.href='<?php echo tep_href_link(FILENAME_ORDERS,'mPath=' . $mPath . '&oID=' .$content["orders_id"] . '&action=edit&return=rs&type='.$type);?>';">
		<td class="dataTableContent"><?php echo $row_cnt;?></td>
		<td  class="dataTableContent"><?php echo $content["customers_name"];?></td>
		<td   class="dataTableContent" align="left"><?php echo $content["orders_id"];?></td>
		<td  class="dataTableContent" align="left"><?php echo $content["delivery_country"];?></td>
		<td  class="dataTableContent" align="left"><?php $ship_method=preg_split('/-/',$content["shipping_method"]);echo $ship_method[0];?></td>
		<td  class="dataTableContent"  align="right"><?php echo $content["weight"];?></td>
		<td  class="dataTableContent"  align="right"><?php echo $currencies->format($content["cost"]);?></td>
		</tr>
		<?php
		$cost+=substr($currencies->format($content["cost"]),1);
		$quan_subtotal+=$content["weight"];
		$row_cnt++;
		}
	echo '<tr><td colspan="5" class="dataTableHeadingTitleContent" align="right">'.TEXT_TOTAL.'</td><td class="dataTableContent" align="right">&nbsp;'.number_format($quan_subtotal,2,'.',' ').'</td><td class="dataTableContent" align="right">'.$currencies->format($cost).'</td>';	
	}else
		echo '<tr><td colspan="7" align="center" class="dataTableContent">'.REPORT_NO_RESULT.'</td></tr>';
	
	echo '</table>';
	}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script language="JavaScript" src="includes/date-picker.js"></script>
<script language="JavaScript" src="includes/http.js"></script>
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
  
	function doReport(index){
		switch(index){
		case 1:
			document.getElementById('ajax_loading').innerHTML='<span class="smallText">loading...</span>';
			var command='<?php echo tep_href_link(FILENAME_REPORTS_SHIPPING,'command=fetch_report_details'); ?>';
			break;
		case 2:
			var command='<?php echo tep_href_link(FILENAME_REPORTS_SHIPPING,'command=pdf'); ?>';
			break;
		case 3:
			var command='<?php echo tep_href_link(FILENAME_REPORTS_SHIPPING,'command=excel'); ?>';
			break;
		}
	do_post_command('reports_shipping',command);
	}
	function do_result(result){
	var token=result.split('^^');
		switch(token[0]){
		case 'fetch_report_details':
			document.getElementById('ajax_loading').innerHTML=token[1];
			break;
		case 'pdf':
		case 'excell':
			window.open("<?php echo DIR_WS_CATALOG . "images/";?>"+token[1]);
			break;
		}
	}
	</script>
       
</head>
<body  marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php $_array=array('d','m','Y');  $replace_array=array('DD','MM','YYYY'); 	$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="100%" align="left">
<?php echo tep_draw_form('reports_shipping',FILENAME_REPORTS_SHIPPING,'','post').tep_draw_hidden_field('post_action'); ?>
	<table width="100%" cellpadding="2" cellspacing="0" border="0">
	<tr>
	<td style="padding:10px;" class="pageHeading"><?php echo HEADING_TITLE; ?></td>
	</tr>
	<tr>
	<td><table width="100%" class="searchArea" cellpadding="2" cellspacing="3" border="0">
	<tr>
	<td style="padding-left:10px;" width="50%"><?php echo REPORT_START_DATE . '&nbsp;' . tep_draw_input_field('txt_start_date',format_date($start_date),' size="10"');?> 
					 <!--a href="javascript:show_calendar('reports_shipping.txt_start_date',null,null,'<?php echo $date_format;?>');"
					   onmouseover="window.status='Date Picker';return true;"
					   onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/>  
					   </a-->&nbsp;&nbsp;
					<?php echo '&nbsp;'.REPORT_END_DATE . tep_draw_input_field('txt_end_date',format_date($end_date),' size="10"');?>
					<!--a href="javascript:show_calendar('reports_shipping.txt_end_date',null,null,'<?php echo $date_format;?>');"
					   onmouseover="window.status='Date Picker';return true;"
					   onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/>  
					   </a-->
	</td>
	<td width="50%" align="right">
	<?php 
	echo '<a href="javascript:doReport(1);">' . tep_image_button('button_report_search.gif', IMAGE_SEARCH_DETAILS) . '</a><br>'; 
	echo '<div style="padding-top:5px;"><a href="javascript:doReport(2);">' . tep_image_button('button_export_pdf.gif', IMG_EXPORT_PDF) . '</a></div>'; 
	echo '<div style="padding-top:5px;"><a href="javascript:doReport(3);">' . tep_image_button('button_export_excel.gif', IMG_EXPORT_EXCEL) . '</a></div>';
	?>
	</td>
	</tr>
	<tr class="dataTableHeadingRow"><td colspan="2">&nbsp;</td></tr>
	</table></td>
	</tr>
	
	<tr>
	<td id="ajax_loading"><?php echo generate_shipping_report($start_date,$end_date); ?></td>
	</tr>
	
	</table>
</form>
</td>
</tr>
</table> 


<!-- body_eof //-->
</body>
</html>

<?php
	// function to generate pdf content
	function generate_pdf(){
	global $display_header,$currencies,$start_date,$end_date,$report_filename;
	
	if ($start_date!='') $where .= " and date_format(o.date_purchased,'%Y-%m-%d')>='". tep_db_input($start_date) ."'";
	if ($end_date!='')	$where .=" and date_format(o.date_purchased,'%Y-%m-%d')<='" . tep_db_input($end_date) . "'";
	//$type_where =" and op.products_type='P'";
	$type_where ="'";
	$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,ot.value as cost,sum((op.products_quantity*p.products_weight)) as weight,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity) as products_quantity,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,o.delivery_country ,ot.title as shipping_method  from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_TOTAL . " ot, ". TABLE_PRODUCTS . " p where p.products_id=op.products_id and ot.orders_id=o.orders_id and ot.value>0 and ot.class='ot_shipping' and  o.orders_id=op.orders_id  " . $where . $type_where . " and op.orders_products_status>1  and op.orders_products_status<5 group by o.orders_id,products_id,products_name,cost,orders_products_id,title,shipping_method order by o.date_paid,o.orders_id ";

	$table=new pdfTable("A4","l");
	
	//set margins
	$table->left_margin=20;
	$table->top_margin=20;
	$table->right_margin=5;
	$table->bottom_margin=20;
	$table->pdfInit();
	
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
		
	$table->tableheaders["text"]=$cols;
	unset($cols);
	$row_cnt=1;$total_weight=0; $total_cost=0;
	$products_sql_query=tep_db_query($products_sql);
	if(tep_db_num_rows($products_sql_query)>0){
	while($content=tep_db_fetch_array($products_sql_query)){
	$cols[]=array("text"=>$row_cnt,"width"=>$widths[0],"align"=>"L","style"=>"row","valign"=>"M");
	$cols[]=array("text"=>$content["customers_name"],"width"=>$widths[1],"align"=>"L","style"=>"row","valign"=>"M");
	$cols[]=array("text"=>$content["orders_id"],"width"=>$widths[2],"align"=>"L","style"=>"row","valign"=>"M");
	$cols[]=array("text"=>$content["delivery_country"],"width"=>$widths[3],"align"=>"L","style"=>"row","valign"=>"M");
	$ship_method=preg_split('/-/',$content["shipping_method"]);
	$cols[]=array("text"=>strip_tags($ship_method[0]),"width"=>$widths[4],"align"=>"L","style"=>"row","valign"=>"M");
	$cols[]=array("text"=>$content["weight"],"width"=>$widths[5],"align"=>"R","style"=>"row","valign"=>"M");	
	$cols[]=array("text"=>$currencies->format($content["cost"]),"width"=>$widths[6],"align"=>"R","style"=>"row","valign"=>"M");
	 $total_cost+=substr($currencies->format($content["cost"]),1); $total_weight+=$content['weight'];
	$table->OutputRowMultiple($cols,10);
	$row_cnt++;
	unset($cols);
	}
	$cols[]=array("text"=>TEXT_TOTAL,"width"=>710,"align"=>"R","style"=>"row","valign"=>"M");
	$cols[]=array("text"=>number_format($total_weight,2,'.',' '),"width"=>40,"align"=>"R","style"=>"row","valign"=>"M");
	$cols[]=array("text"=>$currencies->format($total_cost),"width"=>52,"align"=>"R","style"=>"row","valign"=>"M");
	$table->OutputRowMultiple($cols,10);

	} else {
		$cols[]=array("text"=>REPORT_NO_RESULTS,"width"=>"100%","align"=>"C","style"=>"subrow","valign"=>"M");
		$table->OutputRow($cols,15);
		unset($cols);
	}
	// output pdf file
	$table->Render($report_filename .".pdf",'F');

	}
	function generate_excel(){
	global $display_header,$currencies,$start_date,$end_date,$report_filename;
	if ($start_date!='') $where .= " and date_format(o.date_purchased,'%Y-%m-%d')>='". $start_date ."'";
	if ($end_date!='')	$where .=" and date_format(o.date_purchased,'%Y-%m-%d')<='" . $end_date . "'";
	//$type_where =" and op.products_type='P'";
	$type_where ="'";
	$products_sql = "SELECT op.orders_id,op.products_id,op.products_name,ot.value as cost,sum((op.products_quantity*p.products_weight)) as weight,o.date_paid,o.date_purchased,date_format(o.date_purchased, '%H:%i%s') as time, sum(op.products_quantity) as products_quantity,o.payment_method,o.customers_id,o.customers_name,o.orders_status,op.orders_products_id,o.delivery_country ,ot.title as shipping_method  from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_TOTAL . " ot, ". TABLE_PRODUCTS . " p where p.products_id=op.products_id and ot.orders_id=o.orders_id and ot.value>0 and ot.class='ot_shipping' and  o.orders_id=op.orders_id  " . $where . $type_where . " and op.orders_products_status>1  and op.orders_products_status<5 group by o.orders_id,products_id,products_name,cost,orders_products_id,title,shipping_method order by o.date_paid,o.orders_id ";
	$result="";
	$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"  ",TEXT_INDEX,TEXT_CUSTOMER_NAME,TEXT_ORDER_ID,TEXT_LOCATION,TEXT_SHIPPING_METHOD,TEXT_WEIGHT,TEXT_COST);
	$result.="\n";
	
	$row_cnt=1;$total_weight=0; $total_cost=0;
	$products_sql_query=tep_db_query($products_sql);
		if(tep_db_num_rows($products_sql_query)>0){
			while($content=tep_db_fetch_array($products_sql_query)){
			$ship_method=preg_split('/-/',$content["shipping_method"]);
			$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"  ",$row_cnt,$content["customers_name"],$content["orders_id"],$content["delivery_country"],$ship_method[0],$content["weight"],$currencies->format($content["cost"]));
			$result.="\n";
			$total_cost+=substr($currencies->format($content["cost"]),1); $total_weight+=$content['weight'];
			}
		$result.=sprintf(",,,,%s,\"%s\",\"%s\"",TEXT_TOTAL,$total_weight,$currencies->format($total_cost));
		$result.="\n";
		}else{
		$result.=sprintf(",,,".REPORT_NO_RESULTS.",,");
		$result.="\n";
		}
	
	tep_write_text_file($report_filename . ".csv",$result);
	}
?>


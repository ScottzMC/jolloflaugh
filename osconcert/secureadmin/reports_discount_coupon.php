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
  $start_date = tep_convert_date_raw($FREQUEST->postvalue('txt_start_date'));
  $end_date = tep_convert_date_raw($FREQUEST->postvalue('txt_end_date'));
  if($end_date=='')
  		$end_date=getServerDate();
  	$display_header="";
	$display_header.=TEXT_START_DATE  .  ":&nbsp;" . format_date($start_date) . "&nbsp;&nbsp;";
	$display_header.=TEXT_END_DATE . ":&nbsp;&nbsp;" . format_date($end_date) . "\t";

	if($command!='fetch_discount_coupon'){
        $report_filename=sprintf("reports_discount_coupon_%s_%s",$login_id,time());
        if (($FSESSION->get("report_filename")!='')){
        $old_file=DIR_FS_CATALOG . "images/" .$FSESSION->get("sess_report_filename") . ".pdf";
        if (file_exists($old_file)) unlink($old_file);
        $old_file=DIR_FS_CATALOG . "images/" . $FSESSION->get("sess_report_filename") . ".csv";
        if (file_exists($old_file)) unlink($old_file);
        }
        $FSESSION->set("sess_report_filename",$report_filename);
        tep_delete_temp_files("reports_discount_coupon_" . $login_id);
	}


  $command=$FREQUEST->getvalue('command');
  $sort_by_coupon=$FREQUEST->postvalue('sort_by_coupon','string','N');
  if($command!=""){
  	switch($command){
		case 'fetch_discount_coupon':
		echo 'fetch_discount_coupon^^';
			echo fetch_discount_coupon($start_date,$end_date,$sort_by_coupon);
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

   if($start_date=="") {
		$res = tep_db_query("SELECT '".$end_date."' - INTERVAL 1 MONTH");
		$row = tep_db_fetch_array($res);
		$start_date = $row[0];
   }
   
 
	tep_db_query("DELETE FROM " . TABLE_COUPON_REDEEM_TRACK . "  
	WHERE NOT EXISTS (
    SELECT * FROM ".TABLE_ORDERS." WHERE orders_id=coupon_redeem_track.order_id)
	");
	
	





 function sort_by_coupon($start_date,$end_date){
	 global $FSESSION,$currencies;
	 $where="";
 	if ($start_date!='') $where .= " and date_format(crt.redeem_date,'%Y-%m-%d')>='". tep_db_input($start_date) ."'";
	if ($end_date!='')	$where .=" and date_format(crt.redeem_date,'%Y-%m-%d')<='" . tep_db_input($end_date) . "'";
   	$orderby_query="  order by cd.coupon_id";
	 $display_coupon_query=tep_db_query("select cd.coupon_id,cd.coupon_name from ".TABLE_COUPONS_DESCRIPTION." cd, ".TABLE_COUPON_REDEEM_TRACK." crt where cd.coupon_id=crt.coupon_id and language_id='$FSESSION->languages_id' ". $where." order by coupon_id");
	 echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
	 if(tep_db_num_rows($display_coupon_query)>0){
	 while($display_coupon_array=tep_db_fetch_array($display_coupon_query)){
	 	if($coupon_id!=$display_coupon_array['coupon_id']){
	 $coupon_id=$display_coupon_array['coupon_id'];
	 $coupon_query=tep_db_query("select cd.coupon_id,cd.coupon_name,c.coupon_amount,cus.customers_firstname,cus.customers_lastname,crt.order_id from " . TABLE_CUSTOMERS." cus, ". TABLE_COUPON_REDEEM_TRACK." crt, ".TABLE_COUPONS." c, " .TABLE_COUPONS_DESCRIPTION." cd where cd.language_id='".(int)$FSESSION->languages_id."' and cd.coupon_id=crt.coupon_id and c.coupon_id=crt.coupon_id and crt.customer_id=cus.customers_id and c.coupon_id='$coupon_id'");
	 echo '<tr height="20" valign="middle" onclick="javascript: coupon_focus('.$display_coupon_array['coupon_id'].');"><td>rr
	 <div class="contentTitle">'.$display_coupon_array['coupon_name'].'</div>
	 	<table width="100%" cellpadding="0" cellspacing="0" border="0">';
	 while($coupon_array=tep_db_fetch_array($coupon_query)){
	 
	 }
	 echo '</table>
	 </td></tr>';
	 	}
	 }
	}else{
	}		
 echo '<table>';
 }
  
  function fetch_discount_coupon($start_date,$end_date,$sort_flag='N'){
  global $FSESSION,$currencies;
  	if ($start_date!='') $where .= " and date_format(crt.redeem_date,'%Y-%m-%d')>='". tep_db_input($start_date) ."'";
	if ($end_date!='')	$where .=" and date_format(crt.redeem_date,'%Y-%m-%d')<='" . tep_db_input($end_date) . "'";
  
  if($sort_flag=='Y')
  	$orderby_query="  order by cd.coupon_id";
  else
  	$orderby_query=" order by crt.order_id";
	//Oct 2013 added coupon_type 
  $coupon_query=tep_db_query("select cd.coupon_id,cd.coupon_name,c.coupon_amount,c.coupon_type,cus.customers_firstname,cus.customers_lastname,crt.order_id,crt.email_redeem_id from " . TABLE_CUSTOMERS." cus, ". TABLE_COUPON_REDEEM_TRACK." crt, ".TABLE_COUPONS." c, " .TABLE_COUPONS_DESCRIPTION." cd where cd.language_id='".(int)$FSESSION->languages_id."' and cd.coupon_id=crt.coupon_id and c.coupon_id=crt.coupon_id and crt.customer_id=cus.customers_id".$where.$orderby_query);
//print_r($_SESSION);

  ?>
  <table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
  	<td class="dataTableHeadingTitleContent" width="30%"><?php echo TEXT_COUPON; ?></td>
	<td class="dataTableHeadingTitleContent" width="30%"><?php echo TEXT_CUSTOMER; ?></td>
	<td class="dataTableHeadingTitleContent" align="right" width="10%"><?php echo TEXT_VALUE; ?></td>
	<td class="dataTableHeadingTitleContent" align="center" width="30%"><?php echo TEXT_ORDER; ?></td>
	<td class="dataTableHeadingTitleContent" align="center" width="30%"><?php echo TEXT_EMAIL; ?></td>
  </tr>
  <?php 
  if(tep_db_num_rows($coupon_query)>0){
  $class="dataTableRowOdd"; $coupon_amount=0; $coupon_id=''; $coupon_flag=false; $grand_total=0;
  $coupon_name='';
  while($coupon_array=tep_db_fetch_array($coupon_query)){
  
  //Oct 2013 P types
if($coupon_array['coupon_type']=='P'){
	$value_query=tep_db_query("select value from ".TABLE_ORDERS_TOTAL." where orders_id = '".$coupon_array['order_id']."' and class= 'ot_coupon' LIMIT 1");
	if(tep_db_num_rows($value_query)>0){
		$result = tep_db_fetch_array($value_query);
		
		$coupon_array['coupon_amount']=$result['value'];
	}
else{
		$coupon_array['coupon_amount']=0;
	}


}
//Oct 2013 end
  
  
  $class=($class=="dataTableRowOdd")?'dataTableRowEven':'dataTableRowOdd';
	if($coupon_id!=$coupon_array['coupon_id'] && $coupon_flag==true && $sort_flag=='Y'){
	$chk_flag=true;
	echo '<tr><td class="dataTableHeadingTitleContent" align="right" colspan="2">'.TEXT_SUB_TOTAL.'&nbsp;</td><td align="right" class="dataTableHeadingTitleContent">'.$currencies->format($coupon_amount).'</td></tr>';
	$coupon_amount=0;
	}
  ?>
  <tr class="<?php echo $class; ?>" valign="middle" height="20" style="cursor:pointer;cursor:hand" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="javascript:location.href='<?php echo tep_href_link('edit_orders.php?action=edit&oID=' .$coupon_array["order_id"] . '&action=edit&return=rdc&type='.$type);?>';">
  <td class="dataTableContent"><?php if($coupon_name!=$coupon_array['coupon_name'] && $sort_flag=='Y') echo $coupon_array['coupon_name']; elseif($sort_flag=='N') echo $coupon_array['coupon_name']; ?></td>
  <td class="dataTableContent"><?php echo $coupon_array['customers_firstname'].'&nbsp;'.$coupon_array['customers_lastname']; ?></td>
  <td class="dataTableContent" align="right"><?php echo $currencies->format(tep_get_rounded_amount($coupon_array['coupon_amount'])); ?></td>
  <td class="dataTableContent" align="center"><?php echo $coupon_array['order_id']; ?></td>
  <td class="dataTableContent" align="center"><?php echo (($coupon_array['email_redeem_id']!='0')?tep_image(DIR_WS_IMAGES . 'checked.gif',IMAGE_EMAIL):tep_image(DIR_WS_IMAGES . 'unchecked.gif',IMAGE_COUPON));?></td>
  </tr>
  <?php
  $grand_total+=$coupon_array['coupon_amount'];		
  $coupon_amount+=$coupon_array['coupon_amount'];
  $coupon_id=$coupon_array['coupon_id'];
  $coupon_name=$coupon_array['coupon_name'];
  $coupon_flag=true; 
	}
$total_class=($class=='dataTableRowOdd')?'dataTableRowEven':'dataTableRowOdd';
  if($sort_flag=='N')
  	echo '<tr height="20" class="'.$total_class.'" valign="middle"><td class="dataTableHeadingTitleContent" align="right" colspan="2">'.TEXT_TOTAL.'&nbsp;</td><td align="right" class="dataTableHeadingTitleContent">'.$currencies->format(tep_get_rounded_amount($coupon_amount)).'</td><td class="dataTableHeadingTitleContent" colspan="2">&nbsp;</td></tr>';
  elseif($sort_flag=='Y')
  	echo '<tr height="20" class="'.$total_class.'" valign="middle"><td class="dataTableHeadingTitleContent" align="right" colspan="2">'.TEXT_SUB_TOTAL.'&nbsp;</td><td align="right" class="dataTableHeadingTitleContent">'.$currencies->format(tep_get_rounded_amount($coupon_amount)).'</td><td class="dataTableHeadingTitleContent" colspan="2">&nbsp;</td></tr>';
  if($sort_flag=='Y')	
  echo '<tr height="20" class="'.$total_class.'" valign="middle"><td class="dataTableHeadingTitleContent" align="right" colspan="2">'.TEXT_GRAND_TOTAL.'&nbsp;</td><td align="right" class="dataTableHeadingTitleContent">'.$currencies->format(tep_get_rounded_amount($grand_total)).'</td><td class="dataTableHeadingTitleContent" colspan="2">&nbsp;</td></tr>';	
  } else 
  	echo '<tr height="20" valign="middle"><td class="dataTableContent" align="center" colspan="4">'.TEXT_NO_RECORD.'</td></tr>';
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
	var command;
	switch(index){
		case 1:
			document.getElementById('ajax_loading').innerHTML='<span class="smallText">loading...</span>';
			command='<?php echo tep_href_link(FILENAME_REPORTS_DISCOUNT_COUPON,'command=fetch_discount_coupon'); ?>';
			break;
		case 2:
			command='<?php echo tep_href_link(FILENAME_REPORTS_DISCOUNT_COUPON,'command=pdf'); ?>';		
			break;
		case 3:
			command='<?php echo tep_href_link(FILENAME_REPORTS_DISCOUNT_COUPON,'command=excel'); ?>';				
			break;
	}
	do_post_command('coupon_form',command);
	}
	function do_result(result){
	var token=result.split('^^');
		switch(token[0]){
		case 'fetch_discount_coupon':
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
<?php $_array=array('d','m','Y');  $replace_array=array('DD','MM','YYYY'); 	$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>
<table width="100%" cellpadding="2" cellspacing="2" border="0">
<tr>
	<td><table width="100%" cellpadding="3" cellspacing="3" border="0">
	<tr class="dataTableHeadingRow">
	<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
	</tr>
	<tr>
	<td class="main">
	<?php echo tep_draw_form('coupon_form',FILENAME_REPORTS_DISCOUNT_COUPON,'','post'); ?>
	<table width="100%" class="searchArea" cellpadding="2" cellspacing="3" border="0">
	<tr>
	<td width="80%"><?php echo REPORT_START_DATE . '&nbsp;' . tep_draw_input_field('txt_start_date',format_date($start_date),' size="10"');?> 
					<!--a href="javascript:show_calendar('coupon_form.txt_start_date',null,null,'<?php echo $date_format;?>');"
					   onmouseover="window.status='Date Picker';return true;"
					   onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/>  
					   </a-->&nbsp;&nbsp;
					<?php echo '&nbsp;'.REPORT_END_DATE . tep_draw_input_field('txt_end_date',format_date($end_date),' size="10"');?>
					<!--a href="javascript:show_calendar('coupon_form.txt_end_date',null,null,'<?php echo $date_format;?>');"
					   onmouseover="window.status='Date Picker';return true;"
					   onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/>  
					   </a-->&nbsp;
	<label for="sort_by_coupon"><?php echo tep_draw_checkbox_field('sort_by_coupon','Y').'&nbsp;'.TEXT_SORT_BY_COUPON; ?></label>				   
	</td>
	<td width="20%" align="right">
	<?php 
	echo '<a href="javascript:doReport(1);">' . tep_image_button('button_report_search.gif', IMAGE_SEARCH_DETAILS) . '</a><br>'; 
	echo '<div style="padding-top:5px;"><a href="javascript:doReport(2);">' . tep_image_button('button_export_pdf.gif', IMG_EXPORT_PDF) . '</a></div>'; 
	echo '<div style="padding-top:5px;"><a href="javascript:doReport(3);">' . tep_image_button('button_export_excel.gif', IMG_EXPORT_EXCEL) . '</a></div>';
	?>
	</form>
	</td>
	</tr>
	</table>

	</td>
	</tr>
	</table></td>
</tr>
	<tr class="dataTableHeadingRow"><td colspan="2">&nbsp;</td></tr>
<tr>
	<td id="ajax_loading"><?php echo fetch_discount_coupon($start_date,$end_date,'N'); ?></td>
</tr>
</table>

</body>
</html>
<script language="javascript">
	function auto_call(){
		if(document.coupon_form.sort_by_coupon.checked==true)
			doReport(1);
	}
	auto_call();
</script>

<?php
	// function to generate pdf content
	function generate_pdf(){
	global $display_header,$currencies,$start_date,$end_date,$report_filename,$sort_by_coupon,$FSESSION;
	
	if ($start_date!='') $where .= " and date_format(crt.redeem_date,'%Y-%m-%d')>='". tep_db_input($start_date) ."'";
	if ($end_date!='')	$where .=" and date_format(crt.redeem_date,'%Y-%m-%d')<='" . tep_db_input($end_date) . "'";
  	if($sort_by_coupon=='Y'){
		$display_header.="  Sort By Coupon";
  		$orderby_query="  order by cd.coupon_id";
  	}else
  		$orderby_query=" order by crt.order_id";
  	
	$coupon_query=tep_db_query("select cd.coupon_id,cd.coupon_name,c.coupon_amount,cus.customers_firstname,cus.customers_lastname,crt.order_id,crt.email_redeem_id from " . TABLE_CUSTOMERS." cus, ". TABLE_COUPON_REDEEM_TRACK." crt, ".TABLE_COUPONS." c, " .TABLE_COUPONS_DESCRIPTION." cd where cd.language_id='".(int)$FSESSION->languages_id."' and cd.coupon_id=crt.coupon_id and c.coupon_id=crt.coupon_id and crt.customer_id=cus.customers_id".$where.$orderby_query);


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
	$table->AddStyle("row_bold","color:#000000;bgcolor:#F0F1F1;font:DejaVu;size:10;style:B");
	$table->AddStyle("query","color:#000000;bgcolor:#FFFFFF;font:DejaVu;size:11");
	$table->AddStyle("subrow","color:#000000;bgcolor:#FFFFFF;font:DejaVu;size:10");
	$table->AddStyle("subrowhead","color:#000000;bgcolor:#FFFFFF;font:DejaVu;size:10;style:B");

	$widhts[1]=150;
	$widths[2]=150;
	$widths[3]=150;
	$widths[4]=150;
	$widths[5]=100;

	$table->width=
	// add headers
	$table->headers["text"]=$display_header;
	$table->headers["style"]="query";
	$table->headers["height"]=12;
	$table->headers["width"]="100%";
	//$widths[1]=$table->width-($widths[2]+$widths[3]+$widths[4]+$widths[5]);
	//$table->headers["cols"]=3;
	//$widths[1]=$table->width-($widths[1]+$widths[2]+$widths[3]+$widths[4]);

	$cols=array();

	$cols[]=array("text"=>TEXT_COUPON,"width"=>$widths[1],"align"=>"L","style"=>"headrow","valign"=>"M");
	$cols[]=array("text"=>TEXT_CUSTOMER,"width"=>$widths[2],"align"=>"L","style"=>"headrow","valign"=>"M");
	$cols[]=array("text"=>TEXT_VALUE,"width"=>$widths[3],"align"=>"R","style"=>"headrow","valign"=>"M");
	$cols[]=array("text"=>$seperator.TEXT_ORDER,"width"=>$widths[4],"align"=>"R","style"=>"headrow","valign"=>"M");
	$cols[]=array("text"=>TEXT_EMAIL,"width"=>$widths[5],"align"=>"R","style"=>"headrow","valign"=>"M");
		
	$table->tableheaders["text"]=$cols;
	unset($cols);
	$row_cnt=1;$sub_total=0; $total_cost=0; $coupon_id='';
	$coupon_flag=false; 
	if(tep_db_num_rows($coupon_query)>0){ $display_coupon_name=""; $coupon_name="";
	while($coupon_array=tep_db_fetch_array($coupon_query)){

	
	if($coupon_id!=$coupon_array['coupon_id'] && $coupon_flag==true && $sort_by_coupon=='Y'){
	$cols[]=array("text"=>TEXT_SUB_TOTAL,"width"=>$widths[1]+$widths[2],"align"=>"R","style"=>"row_bold","valign"=>"M");
	$cols[]=array("text"=>$currencies->format($sub_total),"width"=>$widths[3],"align"=>"R","style"=>"row_bold","valign"=>"M");
	$cols[]=array("text"=>'',"width"=>$widths[4]+$widths[5]+244,"align"=>"R","style"=>"row_bold","valign"=>"M");

	$sub_total=0;
	$table->OutputRow($cols,15);
	unset($cols);
	}
	if($coupon_name!=$coupon_array['coupon_name'] && $sort_by_coupon=='Y') 
		$display_coupon_name=$coupon_array['coupon_name']; 
	elseif($sort_by_coupon=='N') 
		$display_coupon_name=$coupon_array['coupon_name'];
	if($coupon_array['email_redeem_id']!='0')
		$email_coupon='M';
	else 
		$email_coupon='C';


	$cols[]=array("text"=>$display_coupon_name,"width"=>$widths[1],"align"=>"L","style"=>"row","valign"=>"T");
	$cols[]=array("text"=>$coupon_array["customers_firstname"].' '.$coupon_array['customers_lastname'],"width"=>$widths[2],"align"=>"L","style"=>"row","valign"=>"T");
	$cols[]=array("text"=>$currencies->format($coupon_array["coupon_amount"]),"width"=>$widths[3],"align"=>"R","style"=>"row","valign"=>"T");
	$cols[]=array("text"=>$coupon_array["order_id"],"width"=>$widths[4],"align"=>"R","style"=>"row","valign"=>"T");	
	$cols[]=array("text"=>$email_coupon,"width"=>$widths[5],"align"=>"R","style"=>"row","valign"=>"M");
	$grand_total+=$coupon_array['coupon_amount']; $sub_total+=$coupon_array['coupon_amount'];
	$table->OutputRow($cols,15);

	unset($cols);
	$coupon_flag=true;
	$coupon_id=$coupon_array['coupon_id']; $coupon_name=$coupon_array['coupon_name']; $display_coupon_name="";
	}
	if($sort_by_coupon=='Y'){
	$cols[]=array("text"=>TEXT_SUB_TOTAL,"width"=>$widths[1]+$widths[2],"align"=>"R","style"=>"row_bold","valign"=>"M");
	$cols[]=array("text"=>$currencies->format($sub_total),"width"=>$widths[3],"align"=>"R","style"=>"row_bold","valign"=>"M");
	$cols[]=array("text"=>'',"width"=>$widths[4]+$widths[5]+244,"align"=>"R","style"=>"row_bold","valign"=>"M");
	$coupon_amount=0;
	$table->OutputRow($cols,15);
	unset($cols);
	}
	
	$cols[]=array("text"=>($sort_by_coupon=='Y')?TEXT_GRAND_TOTAL:TEXT_TOTAL,"width"=>$widths[1]+$widths[2],"align"=>"R","style"=>"row_bold","valign"=>"M");
	$cols[]=array("text"=>$currencies->format($grand_total),"width"=>$widths[3],"align"=>"R","style"=>"row_bold","valign"=>"M");
	$cols[]=array("text"=>'',"width"=>$widths[4]+$widths[5]+244,"align"=>"R","style"=>"row_bold","valign"=>"M");
	$table->OutputRow($cols,15);
	unset($cols);

	} else {
		$cols[]=array("text"=>TEXT_NO_RECORD,"width"=>"80%","align"=>"C","style"=>"subrow","valign"=>"M");
		$table->OutputRow($cols,15);
		unset($cols);
	}
	// output pdf file
	$table->Render($report_filename .".pdf",'F');

	}
	function generate_excel(){
	global $display_header,$currencies,$start_date,$end_date,$report_filename,$sort_by_coupon,$FSESSION;

	if ($start_date!='') $where .= " and date_format(crt.redeem_date,'%Y-%m-%d')>='". tep_db_input($start_date) ."'";
	if ($end_date!='')	$where .=" and date_format(crt.redeem_date,'%Y-%m-%d')<='" . tep_db_input($end_date) . "'";
  	if($sort_by_coupon=='Y')
  		$orderby_query="  order by cd.coupon_id";
  	else
  		$orderby_query=" order by crt.order_id";
  	
	$coupon_query=tep_db_query("select cd.coupon_id,cd.coupon_name,c.coupon_amount,cus.customers_firstname,cus.customers_lastname,crt.order_id,crt.email_redeem_id from " . TABLE_CUSTOMERS." cus, ". TABLE_COUPON_REDEEM_TRACK." crt, ".TABLE_COUPONS." c, " .TABLE_COUPONS_DESCRIPTION." cd where cd.language_id='".(int)$FSESSION->languages_id."' and cd.coupon_id=crt.coupon_id and c.coupon_id=crt.coupon_id and crt.customer_id=cus.customers_id".$where.$orderby_query);	$result="";
	
	$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\"",TEXT_COUPON,TEXT_CUSTOMER,TEXT_VALUE,TEXT_ORDER,TEXT_EMAIL);
	$result.="\n";
	
	
	$sub_total=0;$grand_total=0;
	if(tep_db_num_rows($coupon_query)>0){
	$coupon_flag=false; $display_coupon_name=""; $coupon_name="";
			while($coupon_array=tep_db_fetch_array($coupon_query)){
				if($coupon_id!=$coupon_array['coupon_id'] && $coupon_flag==true && $sort_by_coupon=='Y'){
				$result.=sprintf(",\"%s\",\"%s\" ",TEXT_SUB_TOTAL,$currencies->format($sub_total));
				$result.="\n";
				$sub_total=0;
				}
				
		if($coupon_name!=$coupon_array['coupon_name'] && $sort_by_coupon=='Y') 
			$display_coupon_name=$coupon_array['coupon_name']; 
		elseif($sort_by_coupon=='N') 
			$display_coupon_name=$coupon_array['coupon_name'];
	if($coupon_array['email_redeem_id']!='0')
		$email_coupon='M';
	else 
		$email_coupon='C';
			
			$result.=sprintf("%s,\"%s\",\"%s\",\"%s\",\"%s\"  ",$display_coupon_name,$coupon_array['customers_firstname'].' '.$coupon_array['customers_lastname'],$currencies->format($coupon_array['coupon_amount']),$coupon_array['order_id'],$email_coupon);
			$result.="\n";
			$sub_total+=$coupon_array['coupon_amount']; $grand_total+=$coupon_array['coupon_amount'];
			$coupon_flag=true; $coupon_name=$coupon_array['coupon_name'];
			$coupon_id=$coupon_array['coupon_id']; $display_coupon_name=""; 
			}
		if($sort_by_coupon=='Y'){
		$result.=sprintf(",\"%s\",\"%s\" ",TEXT_SUB_TOTAL,$currencies->format($sub_total));
		$result.="\n";
		}
	
		$result.=sprintf(",\"%s\",\"%s\" ",($sort_by_coupon=='Y')?TEXT_GRAND_TOTAL:TEXT_TOTAL,$currencies->format($grand_total));
		$result.="\n";
		}else{
		$result.=sprintf(",".REPORT_NO_RESULTS.",,");
		$result.="\n";
		}
	
	tep_write_text_file($report_filename . ".csv",$result);
	}
?>

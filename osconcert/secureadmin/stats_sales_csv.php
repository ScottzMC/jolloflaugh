<?php
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
    require('includes/application_top.php');
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
///try to bring osconcert date begin/end
$date_begin=isset($input_params['txt_start_date'])?tep_convert_date_raw($input_params['txt_start_date']):'';
$date_end=isset($input_params['txt_end_date'])?tep_convert_date_raw($input_params['txt_end_date']):'';
/////
	date_default_timezone_set(STORE_TIMEZONE); 
	if ($date_begin==""){
		$sql =  "select date_sub('".getServerDate()."', interval 3 month) begin,'".date('Y-m-d')."' as end";
		$sql_result = tep_db_query($sql);
		$row = tep_db_fetch_assoc($sql_result);
		$date_begin = $row["begin"];
		$date_end = $row["end"];
	}
	
	if ($date_end==""){
		$sql =  "select date_add('$date_begin', interval -(dayofmonth('$date_begin')-1) day) begin, date_add('$date_begin', interval (30-dayofmonth('$date_begin')) day) end";	
		$sql_result = tep_db_query($sql);
		$row = tep_db_fetch_assoc($sql_result);
		$date_begin = $row["begin"];
		$date_end = $row["end"];
	}

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $sales_products_query = tep_db_query("select sum(op.final_price*op.products_quantity) as daily_prod, sum(op.final_price*op.products_quantity*(1+op.products_tax/100)) as withtax, o.date_purchased, op.products_name, sum(op.products_quantity) as qty, op.products_model from " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o where o.orders_id = op.orders_id GROUP by year(o.date_purchased), month(o.date_purchased),op.final_price,op.products_quantity, op.products_name,op.products_model,o.date_purchased ORDER BY year(o.date_purchased) DESC, month(o.date_purchased) DESC");
  
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id='" . (int)$FSESSION->languages_id . "'");
  $orders_statuses[] = array('id' => "",
                             'text' =>"--Select--");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
  }
  
  function download_file($file)
	{
		if (!is_file($file)) 
			   			{
			   				 die("<b>404 File not found!</b>"); 
			   			}

		
			  //Gather relevent info about file
			$len = filesize($file);
			$filename = basename($file);
			$file_extension = strtolower(substr(strrchr($filename,"."),1));

			//This will set the Content-Type to the appropriate setting for the file
			switch( $file_extension )
				 {
 				    case "csv": $ctype="application/force-download"; break;
					//The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
					default: $ctype="application/force-download";
				}
				ob_clean();
				
				
				//Begin writing headers
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: public"); 
				header("Content-Description: File Transfer");

				//Use the switch-generated Content-Type
				header("Content-Type: $ctype");

				//Force the download
				$header="Content-Disposition: attachment; filename=".$filename."";
				//header("Content-Disposition: attachment; filename=$filename");
				header($header );
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: ".$len);
				@readfile($file);
				exit;
				
		}
	
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
	function showOrderDetails(id){
		location.href="edit_orders.php?action=edit&oID="+id+"&return=cl";
	}

</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

<div id="spiffycalendar" class="text"></div>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
      
	</td>
<!-- body_text //-->
    <td width="100%" valign="top">
	  <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td>
         
		
	        <table border="0" width="60%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading" width="500" nowrap><?php echo HEADING_TITLE; ?></td>
                <td class="main" align="right">&nbsp;</td>
              </tr>
              <tr>
                
              </tr>
            </table>

	      <br>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>

	      <?php echo tep_draw_form('SalesReport', basename($PHP_SELF)); ?>
    	 	<table width="100%" cellspacing="0" cellpadding="2" class="searchArea">
	      		 <tr>
				 <td>
				 <?php
				 $_array=array('d','m','Y'); 
				 
					$replace_array=array('DD','MM','YYYY'); 	
					$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);
					echo TEXT_FROM . '&nbsp;' . tep_draw_input_field("txt_start_date",format_date($date_begin),'maxlength="10" size="10"');
					?>
	
					<?php 
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . TEXT_TO . '&nbsp;' . tep_draw_input_field("txt_end_date",format_date($date_end),'maxlength="10" size="10"');
					?>
					</td>
		          <td class="main"><b><?php echo ORDER_STATUS; ?></b> 
				  <?php echo tep_draw_pull_down_menu('status', $orders_statuses, (isset($_POST['status']) && $_POST['status'] !="")?$_POST['status']:""); ?>
		            </td>
		            <td><input type="submit" name="SalesSubmit" value="Submit"></td>
		            </tr>	      	
	      	</table>
	      </form>
          <br>
          </td>
        </tr>
<?php
   $orders_query_raw = "select o.*,o.customers_email_address,o.orders_id, o.customers_name, o.shipping_date, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total
   						 from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s 
   						 where ";
   
$orders_query_raw .="o.orders_status = s.orders_status_id and s.language_id='" . (int)$FSESSION->languages_id . "' and ot.class = 'ot_total'";
   $Message = "";

	if(isset($_POST['status']) && $_POST['status'] !="")
{
	$orders_query_raw .=" and s.orders_status_id = '" . (int)$_POST['status'] . "'";
	
}
	
	$orders_query_raw .=" and date_format(o.date_purchased,'%Y-%m-%d')>='".tep_db_input($date_begin)."' and date_format(o.date_purchased,'%Y-%m-%d')<='".tep_db_input($date_end)."' ";

	$orders_query_raw .=" order by o.orders_id DESC";

	$orders_query = tep_db_query($orders_query_raw);
 
 if(tep_db_num_rows($orders_query) && isset($_POST['SalesSubmit']))
		{
   			$filename="../".date("Md-Y-his").".csv";
			$fp = fopen($filename, "wb");
			fputs($fp,$Message."\n");	
				
   			 //////download_file($filename);	
 	?>
 	 <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingTitleRow">
			<td class="dataTableHeadingTitleContent"><?php echo TABLE_HEADING_DELIVERY_NAME; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_INVOICE_NUMBER; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_INVOICE_DATE; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_DELIVERY_DATE; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_UNIQUE_ITEMS; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_TOTAL_NUMBER_OF_ITEMS; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_SUBTOTAL; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_BOFR_FEE; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_SERVICE_FEE; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_GV_FEE; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_LOW_ORDER_FEE; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_COUPON; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_QTY_DISCOUNT; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_DONATIONS; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_SHIPPING; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
			<td class="dataTableHeadingTitleContent" align="right"><?php echo TABLE_HEADING_TOTAL; ?></td>
		  </tr>
 	<?php
 	fputs($fp,TABLE_HEADING_INVOICE_NUMBER .",");
 	fputs($fp,TABLE_HEADING_INVOICE_DATE .",");
	fputs($fp,TABLE_HEADING_DELIVERY_DATE .",");
 	fputs($fp,TABLE_HEADING_DELIVERY_NAME .",");
 	fputs($fp,TABLE_HEADING_UNIQUE_ITEMS .",");
 	fputs($fp,TABLE_HEADING_TOTAL_NUMBER_OF_ITEMS .",");
 	fputs($fp,TABLE_HEADING_SUBTOTAL .",");
	fputs($fp,TABLE_HEADING_BOFR_FEE .",");
	fputs($fp,TABLE_HEADING_SERVICE_FEE .",");
	fputs($fp,TABLE_HEADING_GV_FEE .",");
 	fputs($fp,TABLE_HEADING_LOW_ORDER_FEE .",");
	fputs($fp,TABLE_HEADING_COUPON .",");
	fputs($fp,TABLE_HEADING_QTY_DISCOUNT .",");
	fputs($fp,TABLE_HEADING_DONATIONS .",");
	fputs($fp,TABLE_HEADING_SHIPPING .",");
	fputs($fp,TABLE_HEADING_TAX .",");
 	fputs($fp,TABLE_HEADING_TOTAL .",");
 	fputs($fp,"\n");
 	while ($orders = tep_db_fetch_array($orders_query)) 
 	{
			$shipping="";
			$gross="";
			$item="";
			$shipto="";
			$bofr="";
			$gv="";
			$loworderfee="";
			$tax="";
			$shipping="";
			$qty_discount="";
			$donation="";
			$service_fee="";
			$coupon="";
			$unique_items= 0;
			$total_number_of_items = 0;

			$sqlsub = "select text,class from orders_total where orders_id=".$orders['orders_id']." and class='ot_subtotal'"; 
			$sql_subtotal=tep_db_query($sqlsub);
			$gross1=tep_db_fetch_array($sql_subtotal);
			$gross=$gross1['text'];

			$sqlsub = "select text,class from orders_total where orders_id=".$orders['orders_id']." and class='ot_loworderfee'"; 
			$sql_loworderfee=tep_db_query($sqlsub);
			$loworderfee1=tep_db_fetch_array($sql_loworderfee);
			$loworderfee=$loworderfee1['text'];
			
			$sqlsub = "select text,class from orders_total where orders_id=".$orders['orders_id']." and class='ot_bofr'"; 
			$sql_bofr=tep_db_query($sqlsub);
			$bofr1=tep_db_fetch_array($sql_bofr);
			$bofr=$bofr1['text'];
			
			$sqlsub = "select text,class from orders_total where orders_id=".$orders['orders_id']." and class='ot_service_fee'"; 
			$sql_servicefee=tep_db_query($sqlsub);
			$servicefee1=tep_db_fetch_array($sql_servicefee);
			$servicefee=$servicefee1['text'];
			
			$sqlsub = "select text,class from orders_total where orders_id=".$orders['orders_id']." and class='ot_gv'"; 
			$sql_gv=tep_db_query($sqlsub);
			$gv1=tep_db_fetch_array($sql_gv);
			$gv=$gv1['text'];
			
			$sqlsub = "select text,class from orders_total where orders_id=".$orders['orders_id']." and class='ot_coupon'"; 
			$sql_coupon=tep_db_query($sqlsub);
			$coupon1=tep_db_fetch_array($sql_coupon);
			$coupon=$coupon1['text'];
			
			$sqlsub = "select text,class from orders_total where orders_id=".$orders['orders_id']." and class='ot_qty_discount'"; 
			$sql_qty_discount=tep_db_query($sqlsub);
			$qty_discount1=tep_db_fetch_array($sql_qty_discount);
			$qty_discount=$qty_discount1['text'];
			
			$sqlsub = "select text,class from orders_total where orders_id=".$orders['orders_id']." and class='ot_donation'"; 
			$sql_donation=tep_db_query($sqlsub);
			$donation1=tep_db_fetch_array($sql_donation);
			$donation=$donation1['text'];
			
			$sqlsub = "select text,class from orders_total where orders_id=".$orders['orders_id']." and class='ot_shipping'"; 
			$sql_shipping=tep_db_query($sqlsub);
			$shipping1=tep_db_fetch_array($sql_shipping);
			$shipping=$shipping1['text'];
			
			$sqlsub = "select text,class from orders_total where orders_id=".$orders['orders_id']." and class='ot_tax'"; 
			$sql_tax=tep_db_query($sqlsub);
			$tax1=tep_db_fetch_array($sql_tax);
			$tax=$tax1['text'];
			
			$sql_item=tep_db_query("select * from orders_products where orders_id=".$orders['orders_id']);
			while ($orders_item = tep_db_fetch_array($sql_item)) 
			{
				$unique_items = $unique_items + 1;
				$total_number_of_items = $total_number_of_items + (int)$orders_item['products_quantity'];
			}

    ?>
    <tr class="dataTableRow">
		<td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $orders['customers_name']; ?></td>
		<td class="dataTableContent" align="right"><?php echo strip_tags($orders['orders_id']); ?></td>
		<td class="dataTableContent" align="right"><?php echo tep_date_short($orders['date_purchased']); ?></td>
		<td class="dataTableContent" align="right"><?php echo tep_date_short($orders['shipping_date']); ?></td>
		<td class="dataTableContent" align="right"><?php echo $unique_items; ?></td>
		<td class="dataTableContent" align="right"><?php echo $total_number_of_items; ?></td>  
		<td class="dataTableContent" align="right"><?php echo $gross; ?></td>
		<td class="dataTableContent" align="right"><?php echo $bofr; ?></td>
		<td class="dataTableContent" align="right"><?php echo $servicefee; ?></td>
		<td class="dataTableContent" align="right"><?php echo $gv; ?></td>
		<td class="dataTableContent" align="right"><?php echo $loworderfee; ?></td>
		<td class="dataTableContent" align="right"><?php echo $coupon; ?></td>
		<td class="dataTableContent" align="right"><?php echo $qty_discount; ?></td>
		<td class="dataTableContent" align="right"><?php echo $donation; ?></td>
		<td class="dataTableContent" align="right"><?php echo $shipping; ?></td>
		<td class="dataTableContent" align="right"><?php echo $tax; ?></td>
		<td class="dataTableContent" align="right"><?php echo strip_tags($orders['order_total']); ?></td>
		</tr>
		   
    <?php
  
		fputs($fp,strip_tags($orders['orders_id']).",");
		fputs($fp,strip_tags(tep_date_short((substr($orders['date_purchased'],0,10)))).",");
		fputs($fp,strip_tags(tep_date_short((substr($orders['shipping_date'],0,10)))).",");
		fputs($fp,strip_tags($orders['customers_name']).",");
		fputs($fp,strip_tags($unique_items).",");
		fputs($fp,strip_tags($total_number_of_items).",");
		fputs($fp,strip_tags($gross).",");
		fputs($fp,strip_tags($bofr).",");
		fputs($fp,strip_tags($servicefee).",");
		fputs($fp,strip_tags($gv).",");
		fputs($fp,strip_tags($loworderfee).",");
		fputs($fp,strip_tags($coupon).",");
		fputs($fp,strip_tags($qty_discount).",");
		fputs($fp,strip_tags($donation).",");
		fputs($fp,strip_tags($shipping).",");
		fputs($fp,strip_tags($tax).",");
		fputs($fp,strip_tags($orders['order_total']).",");
		fputs($fp,"\n");
 	
    }

    ?>
     </table>
    	</td>
    </tr>
    <tr>
    <td>
    <a href="<?php echo $filename; ?>" target="_blank"><?php echo DOWNLOAD_LINK; ?></a></td></tr>
    <?php
      fclose($fp);
	  }
    /////////end reports
	?>
      </table>
    </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>

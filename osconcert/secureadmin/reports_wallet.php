<?php

/*

  

  Released under the GNU General Public License

    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
  require('includes/application_top.php');
  tep_get_last_access_file();require('includes/classes/currencies.php');
  require(DIR_WS_CLASSES . 'split_page_results_report.php');
 // require(DIR_WS_INCLUDES . 'date_format_js.php');
  define(BOX_WIDTH1,'125');

	$post_action=$FREQUEST->postvalue('post_action');
	$currencies=new currencies();
	
	
	// change the current status of selected orders
	if ($post_action=="change_status"){
		$stat_ids=$FREQUEST->postvalue("chk_status");

		$customer_ids="";
		for ($icnt=0;$icnt<sizeof($stat_ids);$icnt++){
			$customer_ids.=$stat_ids[$icnt] . ",";
		}
		
		$new_status=$FREQUEST->postvalue('new_status');
		
		if ($customer_ids!=""){
			$customer_ids=substr($customer_ids,0,-1);
			tep_db_query("Update " . TABLE_WALLET_UPLOADS . " set payment_status='" . $new_status . "' where wallet_id in(" . (int)$customer_ids . ")");
			tep_redirect(tep_href_link(FILENAME_REPORTS_WALLET,'return=1'));
		}
	}
	//status 

	// get initial parameters, check the session for previous settings
	if (($FREQUEST->getvalue("return")!='') && ($FSESSION->get("rep_params")!='')){
		$input_params=&$FSESSION->get("rep_params");
	} else {
		$input_params=&$FPOST;
		if (isset($input_params["post_action"])){
			$FSESSION->set("rep_params",$FPOST);
			$GLOBALS["rep_params"]["post_action"]="screen";
		} else {
			$FSESSION->set("rep_params",array());
		}
	}
	$page=(isset($input_params["page"])?$input_params["page"]:1);
	$amount=(isset($input_params['amount'])?$input_params['amount']:"0");
	$date_begin = (isset($input_params["txt_date_begin"])?tep_convert_date_raw($input_params["txt_date_begin"]):getServerDate());
	$date_end = (isset($input_params["txt_date_end"])?tep_convert_date_raw($input_params["txt_date_end"]):'');
	$customer=isset($input_params['sel_customer'])?$input_params['sel_customer']:"-1";
	$sel_status=(isset($input_params['sel_status'])?$input_params['sel_status']:'');
	
	$status_array=array();
	$sql="SELECT orders_status_id,orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id=". (int)$FSESSION->languages_id;	
	$sql_result=tep_db_query($sql);
	$status_array=tep_get_orders_status();
	$change_status_array=tep_get_orders_status(true);
	//$change_status_array[]=array('id'=>0,'text'=>"All ");
	while($row=tep_db_fetch_array($sql_result)){
		$order_status_array[$row["orders_status_id"]]=$row["orders_status_name"];
		//$status_array[]=array('id'=>$row["orders_status_id"],'text'=>$row["orders_status_name"]);
		//$change_status_array[]=array('id'=>$row["orders_status_id"],'text'=>$row["orders_status_name"]);
	}
	//$change_status_array=$order_status_array;
	if ($date_end==""){
		$res = tep_db_query("SELECT date_add('" . $date_begin . "',INTERVAL 7 day)");	
		$row = tep_db_fetch_array($res);
		$date_end = $row[0];
	}
	
	$customers_array = array();
    $sql="SELECT distinct c.customers_id,concat(LTRIM(c.customers_firstname),'  ',LTRIM(c.customers_lastname))as customer_name from " . TABLE_CUSTOMERS ." c," . TABLE_WALLET_UPLOADS ." wu where wu.customers_id=c.customers_id order by customer_name";			
	$sql_result=tep_db_query($sql);
	$customers_array[]=array("id"=>"-1","text"=>TEXT_ALL_CUSTOMERS);
	while($row=tep_db_fetch_array($sql_result)){
		$customers_array[]=array("id"=>$row["customers_id"],"text"=>$row["customer_name"]);
	}
	tep_db_free_result($sql_result);
		
	$found_results=false;
		$where = " ";
		$where.=" and date_format(wu.payment_date,'%Y-%m-%d')>='" . tep_db_input($date_begin) . "'" . " and date_format(wu.payment_date,'%Y-%m-%d')<='" . tep_db_input($date_end) . "'";
		if($customer!="-1") {
			$where.= " and wu.customers_id='".tep_db_input($customer)."' ";
		}
		if($amount!=0){
	      $where.=" and wu.amount='".tep_db_input($amount)."' ";	
		}
		if($sel_status!=-1 && $sel_status!=""){
	      $where.=" and wu.payment_status='" . tep_db_input($sel_status)."' ";	
		}
		
		// create the query for fetching registered subscribers
			$sql = "select c.customers_id,LTRIM(c.customers_firstname) as customers_firstname,LTRIM(c.customers_lastname) as customers_lastname,wu.payment_status,wu.wallet_id,wu.payment_date,wu.payment_method,wu.amount from ". TABLE_WALLET_UPLOADS ." wu," . TABLE_ORDERS_STATUS ." o," . TABLE_CUSTOMERS ." c where c.customers_id=wu.customers_id and o.orders_status_id=wu.payment_status ". $where .  " order by customers_lastname,customers_firstname";


		// split the report
		$query_split = new splitPageResultsReport($page, REPORT_MAX_ROWS_PAGE, $sql, $query_split_numrows);
		$sql_result = tep_db_query($sql);

		if (tep_db_num_rows($sql_result)>0) $found_results=true;
                //echo EVENTS_DATE_FORMAT;
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
<link href="includes/jquery-ui.css" rel="stylesheet">
<script src="includes/jquery-1.10.2.js"></script>
<script src="includes/jquery-ui.js"></script>
<script language="JavaScript">
    jQuery(function() {        
    jQuery( "#txt_date_begin" ).datepicker(
        {
            changeMonth: true,
            changeYear: true,
            showOn: 'button',
            buttonImage: 'images/icon_calendar.gif',
            buttonImageOnly: true,
            dateFormat: '<?php $_array=array('d','m','Y');  $replace_array=array('dd','mm','yy'); echo $date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>',
            onClose: function( selectedDate ) {
				$( "#txt_date_end" ).datepicker( "option", "minDate", selectedDate );
			}
        }
    );
    
    jQuery( "#txt_date_end" ).datepicker(
        {
            changeMonth: true,
            changeYear: true,
            showOn: 'button',
            buttonImage: 'images/icon_calendar.gif',
            buttonImageOnly: true,
            dateFormat: '<?php $_array=array('d','m','Y');  $replace_array=array('dd','mm','yy'); echo $date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>',
            onClose: function( selectedDate ) {
				$( "#txt_date_begin" ).datepicker( "option", "maxDate", selectedDate );
			}
        }
    );
  });
  
	function doSubmit() {
		document.f.submit();
	}
	function showOrderDetails(id){
		location.href="customers.php?action=edit&ID="+id;
	} 
	
	function validateForm(frm){
	
			var err="";
			var element=frm.elements["chk_status[]"];
			if (element.length){
				for (icnt=0;icnt<element.length;icnt++){
					if (element[icnt].checked) break;
				}
				if (icnt>=element.length){
					err="<?php echo addslashes(stripslashes(ERR_SELECT));?>";
				}
			} else {
				if (!element.checked){
					err="<?php echo addslashes(stripslashes(ERR_SELECT));?>";
				}
			}
			if (err!="") {
				alert(err);
				return false;
			}
			return true;
		}
	
	function selectAll(){
			var element=document.change_status.elements["chk_status[]"];
			var status=document.change_status.chk_select.checked;
			if (element.length){
				for (icnt=0;icnt<element.length;icnt++){
					element[icnt].checked=status;
				}
			} else {
				element.checked=status;			
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
				<!--<td class="pageHeading"><?php //echo HEADING_WALLET_DETAILS;?></td>	!-->
			<tr>
			<?php echo tep_draw_form("f",FILENAME_REPORTS_WALLET,'','post'); ?>
			<input type="hidden" name="post_action" value="1">
			<!-- form -->
			<tr >
				<td>
				<table border="0" cellspacing="3" cellpadding="2" class="searchArea" width="100%">
					<tr>
						<td nowrap><?php $_array=array('d','m','Y');  $replace_array=array('DD','MM','YYYY'); 	$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>
						<?php echo TEXT_PAYMENT_DATE . '&nbsp;' . tep_draw_input_field("txt_date_begin",format_date($date_begin),"size=10",false,'text',false);?>
                                                    
<!--							<a href="javascript:show_calendar('f.txt_date_begin',null,null,'<?php echo $date_format;?>');"
							onmouseover="window.status='Date Picker';return true;"
							onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/>  
							</a>-->
						</td>
						<td nowrap width="250">
						<?php echo TEXT_END_DATE . '&nbsp;' . tep_draw_input_field("txt_date_end",format_date($date_end),"size=10",false,'text',false);?>
							<!--a href="javascript:show_calendar('f.txt_date_end',null,null,'<?php echo $date_format;?>');"
							onmouseover="window.status='Date Picker';return true;"
							onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/>  
							</a-->
						</td>
						<td rowspan="2" valign="middle" align="right">
						   <a href="javascript:doSubmit()"><?php echo tep_image_submit('button_report_search.gif', IMAGE_SEARCH_WALLET); ?></a>
						</td>
					</tr>
					<tr>
						<td nowrap="true">
						
							<?php 
							echo TEXT_CUST_NAME . '&nbsp;' . tep_draw_pull_down_menu("sel_customer",$customers_array,$customer);
							?>
						</td>
						
						<td nowrap="true">
						<?php 
						echo TEXT_AMOUNT . '&nbsp;' . tep_draw_input_field("amount");
						
						?>
						<?php 
						echo TEXT_STATUS . '&nbsp;' .  tep_draw_pull_down_menu('sel_status',$change_status_array,$new_status);
						?>
						</td>
						
					</tr>
				<!-- report content -->
				</table>
				</td>
			</tr>
			</form>
			<tr ><td class="cell_bg_report_header">&nbsp;</td></tr>
<?php 
				if ($found_results){
				?>
				
					<tr>
						<td>
						<table border="0" width="100%" cellspacing="0" cellpadding="2">
							<tr>
								<td class="smallText" align="left">
									<?php echo $query_split->display_links($query_split_numrows, REPORT_MAX_ROWS_PAGE, REPORT_MAX_LINKS_PAGE, $page,tep_get_report_params()); ?>&nbsp;
								</td>
							</tr>
						</table>
						</td>
					</tr>
					 <form name="change_status" id="change_status" method="post" action="<?php echo $PHP_SELF;?>" onSubmit="javascript:return validateForm(this);">
					<input type="hidden" name="post_action" value="change_status">
					<tr>
						<td>
						<table border="0" width="100%" cellspacing="1" cellpadding="1">
							<tr class='dataTableHeadingTitleRow'>
							    <td class='dataTableHeadingTitleContent' width="30">&nbsp;</td>
								<td class='dataTableHeadingTitleContent' width="30">#</td>
								<td class='dataTableHeadingTitleContent'><?php echo TEXT_LAST_NAME;?></td>
								<td class='dataTableHeadingTitleContent'><?php echo TEXT_FIRST_NAME;?></td>
								<td class='dataTableHeadingTitleContent'><?php echo TEXT_PAYMENT_METHOD;?></td>
								<td class='dataTableHeadingTitleContent'><?php echo TEXT_PAYMENT_STATUS;?></td>
								<td class='dataTableHeadingTitleContent' width="130"><?php echo TEXT_PAYMENT_DATE;?></td>
								<td class='dataTableHeadingTitleContent'  width="130" align="right"><?php echo TEXT_AMOUNT;?></td>
							</tr>
							<tr height="10"></tr>
                      
				<?php
					$i=1;
          			while($row = tep_db_fetch_assoc($sql_result)) {
								$payment_query="select orders_status_name as payment_status from orders_status where  orders_status_id=". tep_db_input($row['payment_status']);
								$payment_result=tep_db_query($payment_query);
								while($status=tep_db_fetch_array($payment_result)){
									$paymentresult=$status["payment_status"];
								}
//								while($paymentstatus=tep_db_fetch($payment_result))
					?>
					  <tr class='dataTableRow' height="20" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)">
					      <td class='dataTableContent' style="cursor:hand"><?php echo tep_draw_checkbox_field('chk_status[]',$row['wallet_id']);?></td>
						  <span onClick="javascript:location.href='<?php echo tep_href_link(FILENAME_CUSTOMERS,'cID=' . $row["customers_id"] . '&action=edit&return=waup');?>';">
						  <td class='dataTableContent' style="cursor:hand"><?php echo $i?></td>
						  <td class='dataTableContent' style="cursor:hand"><?php echo $row['customers_lastname'];?></td>
						  <td class='dataTableContent' style="cursor:hand"><?php echo $row['customers_firstname'];?></td>
						  <td class='dataTableContent' style="cursor:hand"><?php echo $row['payment_method'];?></td>
						  <td class='dataTableContent' style="cursor:hand"><?php echo $paymentresult;?></td>
						  <td class='dataTableContent' style="cursor:hand"><?php echo format_date($row['payment_date']);?></td>
						  <td class='dataTableContent' style="cursor:hand" align="right"><?php $amt=$row['amount'];echo $currencies->format($amt);?></td>
						  </span>
					  </tr>
      			<?php 
						$i++;			
					} // for $icnt
					?>
				</table>
				</TD>
			</tr>
			<tr><td class="bottomLine">&nbsp;</td></tr>
			<tr>
			<td class="main" valign="top">
			<?php  echo '<input type="checkbox" name="chk_select" value="1" onClick="javascript:selectAll();">&nbsp;' . TEXT_SELECT_ALL . '&nbsp;&nbsp;'; ?>
					
			</td>
			</tr>
			<tr>
						<td><?php echo tep_draw_separator('pixel_trans.gif',1,5);?></td>
					</tr>
					<tr>
						<td class="main" valign="middle">
						<?php 
								echo tep_image_submit('button_change_status.gif',IMAGE_CHANGE_STATUS,'align=absmiddle') . '&nbsp;&nbsp;';
								echo TEXT_STATUS . '&nbsp;' .  tep_draw_pull_down_menu('new_status',$status_array);
						?>
						</td>
					</tr>
					 </form>
					 
			<?php	} // if $found_results
			
					else{
						echo '<tr><td class="main" align="center">' . TEXT_NO_RECORDS_FOUND . '</td></tr>';
					}
			?>
	</table>
	</td>
</tr>
</table>	
	<!-- footer //-->
	<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
	<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
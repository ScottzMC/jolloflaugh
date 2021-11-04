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
tep_get_last_access_file();
require('includes/classes/currencies.php');
require(DIR_WS_CLASSES . 'split_page_results_report.php');
define(BOX_WIDTH1,'125');
$server_date = getServerDate(true);
// get initial parameters, first check session for previous settings
if (($FREQUEST->getvalue("return")!='') && ($FSESSION->get("rep_params")!='')){
    $input_params=&$FSESSION->get("rep_params");
} else {
    $input_params=&$FPOST;
    if (isset($input_params["post_action"])){
        if ($input_params["post_action"]!="change_status"){
            $FSESSION->set("rep_params",$FPOST);
            $GLOBALS["rep_params"]["post_action"]="screen";
        }
    } else {
        $FSESSION->set("rep_params",array());
    }
}
$date_begin=isset($input_params['txt_start_date'])?tep_convert_date_raw($input_params['txt_start_date']):'';
$date_end=isset($input_params['txt_end_date'])?tep_convert_date_raw($input_params['txt_end_date']):'';
$sel_status=isset($input_params['sel_status'])?$input_params['sel_status']:1;
$post_action = isset($input_params['post_action'])?$input_params['post_action']:'';
$page = isset($input_params['page'])?$input_params['page']:1;
$currencies=new currencies();
// change the current status of selected orders
if ($post_action=="change_status"){
    $stat_ids=$input_params["chk_status"];
    $new_status=$input_params["new_status"];
    $order_ids="";
    //	echo sizeof($stat_ids);
    for ($icnt=0;$icnt<sizeof($stat_ids);$icnt++){
        //tep_send_products_status_change_email($stat_ids[$icnt],$new_status);
        $order_ids.=$stat_ids[$icnt] . ",";
    }
    if ($order_ids!=""){
        $order_ids=substr($order_ids,0,-1);

        tep_db_query("UPDATE " . TABLE_ORDERS . " set last_modified='" . tep_db_input($server_date) . "',orders_status='" . tep_db_input($new_status) . "' where orders_id in(" . (int)$order_ids . ")");
        tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set orders_products_status='" . tep_db_input($new_status) . "' where orders_id in(" . (int)$order_ids . ")");
        tep_redirect(tep_href_link(FILENAME_REPORTS_DIRECT_DEPOSIT,'return=1'));
        /*		echo tep_draw_form("frmSearch",FILENAME_REPORTS_DIRECT_DEPOSIT);
                echo '<input type="hidden" name="post_action" value="screen">';
                echo tep_get_report_params();
                echo '<script language="javascript">' .
                    'document.frmSearch.submit();' .
                    '</script></form>';
                return; */
    }
}

if ($post_action==""){
    $post_action="screen";
}
if ($date_begin==""){
    $date_begin=getServerDate();
}
if ($date_end==""){
    $sql =  "select date_add('$date_begin', interval 1 month) end";
    $sql_result = tep_db_query($sql);
    $row = tep_db_fetch_assoc($sql_result);
    $date_end = $row["end"];
}
$status_where="";
if($sel_status!="" && $sel_status!=-1)
$status_where= " and o.orders_status='" . $sel_status . "' ";

$order_status_array=array();
$status_array=array();
// get the mysql results;
//$sql="SELECT orders_status_id,orders_status_name from " . TABLE_ORDERS_STATUS . " where  language_id='" . (int)$FSESSION->languages_id . "' and lower(orders_status_name)!='refunded'";
$sql="SELECT orders_status_id,orders_status_name from " . TABLE_ORDERS_STATUS . " where  language_id='" . (int)$FSESSION->languages_id . "' and orders_status_id!='5'";
$sql_result=tep_db_query($sql);
$sel_status_array[0] = array('id'=>-1,'text'=>TEXT_ALL);
while($row=tep_db_fetch_array($sql_result)){
    $order_status_array[$row["orders_status_id"]]=$row["orders_status_name"];
    if($row['orders_status_id']!=4) {
        $sel_status_array[]=array('id'=>$row["orders_status_id"],'text'=>$row["orders_status_name"]);
        $status_array[]=array('id'=>$row["orders_status_id"],'text'=>$row["orders_status_name"]);
    }
}

$found_results=false;
$where = " ";
$where.=" and ((date_format(o.date_purchased,'%Y-%m-%d')>='" . tep_db_input($date_begin) . "'" . " and date_format(o.date_purchased,'%Y-%m-%d')<='" . tep_db_input($date_end) . "'))";
$db_result=array();
$num_rows=0;
$cur_row=0;
$rows_each=REPORT_MAX_ROWS_PAGE;

$sql="Select o.orders_id, concat(LTRIM(c.customers_lastname), ' ',LTRIM(c.customers_firstname)) as customers_name, o.customers_id, 
        orders_status, sum(op.final_price) as final_price, op.products_tax from " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o, " . TABLE_CUSTOMERS . " c
        where o.customers_id=c.customers_id and op.orders_id=o.orders_id  and o.payment_method like '%Bank Transfer Payment%'  " . $where . $status_where . " group by op.orders_id,o.orders_id,c.customers_lastname,c.customers_firstname,customers_name,o.customers_id,o.orders_status,final_price,op.products_tax order by orders_id desc " ;

$sq_query  = "Select o.orders_id, concat(LTRIM(c.customers_lastname), ' ',LTRIM(c.customers_firstname)) as customers_name, o.customers_id,
        orders_status, op.final_price as final_price, op.products_tax from " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o, " . TABLE_CUSTOMERS . " c
        where o.customers_id=c.customers_id and op.orders_id=o.orders_id  and o.payment_method like '%Bank Transfer Payment%'  " . $where . $status_where . " group by op.orders_products_id,o.orders_id,c.customers_lastname,c.customers_firstname,customers_name,o.customers_id,o.orders_status,final_price,op.products_tax order by orders_id desc " ;
if ($post_action=="screen"){
    $query_split1 = new splitPageResultsReport($page, $rows_each, $sq_query, $query_split_numrows1);
}
$db_result[$cur_row]=array("result"=>tep_db_query($sq_query));
$num_rows=tep_db_num_rows($db_result[$cur_row]["result"]);
$cur_row++;
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
//	function showOrderDetails(id){
//		location.href="orders.php?action=edit&oID="+id;
//	}
function doReport(mode) {
strStart = document.f.txt_start_date.value.split("-");
startDate = new Date(strStart[2], strStart[1], strStart[0]);

strEnd = document.f.txt_end_date.value.split("-");
endDate = new Date(strEnd[2], strEnd[1], strEnd[0]);
if(startDate.getTime()>endDate.getTime()) {
alert("<?php echo REPORT_START_AFTER_END?>");
return;
}
document.f.submit();
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
    <tr height="10">
        <!--<td class="pageHeading"><?php //echo HEADING_REPORT_DIRECT_DEPOSIT;?></td>!-->
    </tr>
    <tr>
        <TD>
        <?php 	echo tep_draw_form("f",FILENAME_REPORTS_DIRECT_DEPOSIT);
        echo '<input type="hidden" name="post_action" value="screen">';
        ?>
        <table border="0" cellspacing="0" cellpadding="0" class="searchArea" width="100%">
            <tr>
                <td nowrap="true" width="30%"><?php $_array=array('d','m','Y');  $replace_array=array('DD','MM','YYYY'); 	$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>
                    <?php echo TEXT_FROM . '&nbsp;' . tep_draw_input_field("txt_start_date",format_date($date_begin),'maxlength="10" size="10"');?>
                    <!--a href="javascript:show_calendar('f.txt_start_date',null,null,'<?php echo $date_format;?>');"
                       onmouseover="window.status='Date Picker';return true;"
                       onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/>
                    </a-->
                </td>
                <td nowrap="true" width="30%">
                    <?php echo  TEXT_TO . '&nbsp;' . tep_draw_input_field("txt_end_date",format_date($date_end),'maxlength="10" size="10"');?>
                    <!--a href="javascript:show_calendar('f.txt_end_date',null,null,'<?php echo $date_format;?>');"
                       onmouseover="window.status='Date Picker';return true;"
                       onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/>
                    </a-->
                </td>
                <td>
                    <?php echo TEXT_ORDER_STATUS . '&nbsp;' . tep_draw_pull_down_menu('sel_status',$sel_status_array,$sel_status); ?>
                </td>

                <td valign="middle" align="left" rowspan="3">
                    <?php echo '<a href="javascript:doReport(1);">' . tep_image_button('button_report_search.gif', IMAGE_SEARCH_DETAILS) . '</a>'; ?>
                </td>
            </tr>
            <!-- report content -->
        </table>
        </form>
        <br>
    </td>
</tr>
<tr ><td class="cell_bg_report_header">&nbsp;</td></tr>
<script language="JavaScript">
function validateForm(frm){
var err="";
var element=frm.elements["chk_status[]"];
if (element.length){
for (icnt=0;icnt<element.length;icnt++){
if (element[icnt].checked) break;
}
if (icnt>=element.length){
err="<?php echo addslashes(stripslashes(ERR_SELECT_ORDER));?>";
}
} else {
if (!element.checked){
err="<?php echo addslashes(stripslashes(ERR_SELECT_ORDER));?>";
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
<?php if ($found_results) {
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
<?php } ?>
<tr>
<td>
<table border="0" width="100%" cellspacing="1" cellpadding="1">
    <?php
    if ($found_results) {
        echo tep_draw_form("change_status",FILENAME_REPORTS_DIRECT_DEPOSIT,'','post','onSubmit="javascript:return validateForm(this);"');
        //echo tep_get_report_params();
        echo '<input type="hidden" name="txt_start_date" value="' . $input_params['txt_start_date'] . '">';
        echo '<input type="hidden" name="txt_end_date" value="' . $input_params['txt_end_date'] . '">';
        echo '<input type="hidden" name="sel_status" value="' . $input_params['sel_status'] . '">';
        echo '<input type="hidden" name="post_action" value="change_status">';
        for ($icnt=0;$icnt<sizeof($db_result);$icnt++) {
            $sql_result=&$db_result[$icnt]["result"];
            ?>

    <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif',1,5);?></td>
    </tr>
    <tr>
        <td>
            <table border="0" cellpadding="2" cellspacing="0" width="100%">
                <tr class='dataTableHeadingTitleRow'>
                    <td class='dataTableHeadingTitleContent' width="30">&nbsp;</td>
                    <td class='dataTableHeadingTitleContent' width="30"><?php echo TEXT_ID;?></td>
                    <td class='dataTableHeadingTitleContent'><?php echo TEXT_CLIENT;?></td>
                    <td class='dataTableHeadingTitleContent' width="100"><?php echo TEXT_STATUS;?></td>
                    <td class='dataTableHeadingTitleContent' width="100" align="right"><?php echo TEXT_AMOUNT;?></td>
                </tr>
                <tr height="10"></tr>
                <?php
                $i=1;
                $prev_order=0;
                $total=0;
                $grand_total=0;
                $icnt=0;
                 while($row = tep_db_fetch_array($sql_result)) {

                     if($prev_order!=$row['orders_id']) {                        
                       if($prev_order!=0) {
                           if($manual_value!=''){
                                $amount=$amount+$manual_value;
                            }
                            $deposit_array[$icnt-1]['total']=$amount;
                            $amount=0;
                        }
                         $deposit_array[$icnt++]=array('orders_id'=>$row['orders_id'],'customers_name'=>$row['customers_name'],'orders_status'=>$order_status_array[$row['orders_status']]);
                        $manual_value=0;
                        $manual_sql="select text,value from ". TABLE_ORDERS_TOTAL . " where class = 'ot_adjust' and orders_id='" .$row["orders_id"] ."'";
                        $manual_sql_qry=tep_db_query($manual_sql);
                        $manual_num_rows=(tep_db_num_rows($manual_sql_qry));
                        if($manual_num_rows!='0' & $manual_num_rows>0){
                            while($manual_result=tep_db_fetch_array($manual_sql_qry)){
                                $manual_text=$manual_result['text'];
                                $sign=substr($manual_result['text'],1,1);
                                if($sign=='+')
                                $manual_value=$sign . $manual_result['value'];
                                else
                                $manual_value=$manual_result['value'];
                            }
                        }
                     } $prev_order=$row['orders_id'];
					 
                      $amount+=tep_add_tax($row['final_price'],$row["products_tax"]);
                           
                            $amount=tep_get_rounded_amount($amount);
                 }
				 
				 
                  if($manual_value!=''){
                                $amount=$amount+$manual_value;
                            }
                 $deposit_array[$icnt-1]['total']=$amount;

                 for($jcnt=0;$jcnt<count($deposit_array);$jcnt++) {
                      $row=$deposit_array[$jcnt];
                      if($disp_class=='dataTableRow')
                        $disp_class='dataTableRowOver';
                        else
                        $disp_class='dataTableRow';
                         $grand_total+=$row['total'];
                        ?>

                         <tr class='<?php echo $disp_class;?>' height="20">
                    <td class='dataTableContent'><?php echo tep_draw_checkbox_field('chk_status[]',$row["orders_id"]);?></td>
                    <span onClick="javascript:location.href='<?php echo tep_href_link(FILENAME_ORDERS,'oID=' . $row["orders_id"] . '&action=edit&return=dd');?>';">
                        <td class='dataTableContent' style="cursor:default;cursor:hand"><?php echo $jcnt+1?></td>
                        <td class='dataTableContent' style="cursor:default;cursor:hand"><?php echo $row['customers_name']?></td>
                        <td class='dataTableContent' style="cursor:default;cursor:hand"><?php echo $row['orders_status'];?></td>
                    <td class='dataTableContent' style="cursor:default;cursor:hand" align="right"><?php echo $currencies->format($row['total']);?></td>
                    </span>
                </tr>

                <?php }
                 
             
        // total >0
        if ($grand_total>0) {
            ?>
                <tr>
                    <td class="dataTableContent"  colspan="4" align="right">
                        <b><?php echo TEXT_GRAND_TOTAL;?></b>
                    </td>
                    <td style="cursor:hand" align="right" class='dataTableContent'>
                        <?php echo $currencies->format($grand_total);
                        ?>
                    </td>
                </tr>
                <?php } // grand total >0 ?>
            </table>
        </td>
    </tr>
    <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif',1,5);?></td>
    </tr>
    <?php
}
?>
    <tr>
        <td class="main" valign="top">
            <?php  echo '<input type="checkbox" name="chk_select" value="1" onClick="javascript:selectAll();">&nbsp;' . TEXT_SELECT_ALL . '&nbsp;&nbsp;'; ?>
        </td>
    </tr>
    <tr>
        <td class="bottomLine"><?php echo tep_draw_separator('pixel_trans.gif',1,5);?></td>
    </tr>
    <tr height="5"></tr>
    <tr>
        <td class="main" valign="middle">
            <?php
            echo tep_image_submit('button_change_status.gif',IMAGE_CHANGE_STATUS,'align=absmiddle') . '&nbsp;&nbsp;';
            echo tep_draw_pull_down_menu('new_status',$status_array);
            ?>
        </td>
    </tr>
    </form>
    <?php
}
else{
    echo '<tr><td class="main" align="center">' . TEXT_NO_RECORDS_FOUND . '</td></tr>';
}
?>
</table>
</TD>
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

?>
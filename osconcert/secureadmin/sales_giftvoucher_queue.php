<?php

/*
osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 

Released under the GNU General Public License

Freeway eCommerce
http://www.openfreeway.org
Copyright (c) 2007 ZacWare
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
  require('includes/application_top.php');
  tep_get_last_access_file();
  require(DIR_WS_CLASSES . 'currencies.php');
  
  $currencies = new currencies();
  require('includes/classes/split_page_results_event.php');
   
  $command=$FREQUEST->getvalue('command');
  $oID=$FREQUEST->getvalue('oID');
  $gid = $FREQUEST->getvalue('id');
  $page = $FREQUEST->getvalue('page');
  $page = $FREQUEST->getvalue('action');
  if($oID!='' && $action=='edit') {
  	$query=tep_db_query("select unique_id from " . TABLE_COUPON_GV_QUEUE . " where order_id='" . tep_db_input($oID) . "'");
	$result=tep_db_fetch_array($query);
	$gid=$result['unique_id'];
  }
  
  if($command != ''){
		if($command=='get_details'){
			echo get_default_details($gid);
		}else if($command=='ask_confirm'){
			echo 'ask_confirm';
			$ask_confirm_string="";
			$ask_confirm_string = "<table border='0' cellspacing='0' cellpadding='0' width='100%'>".
								  "<tr height='25'><td class='main'>".tep_draw_separator('pixel_trans.gif',50,10)."Are you sure want to redeem?</td></tr>".	
								  "<tr height='25'><td>".tep_draw_separator('pixel_trans.gif',170,10).tep_image_button('button_confirm.gif', IMAGE_CONFIRM,"onclick=javascript:expand_category($gid,5)")."</tr>";
								  "<tr ><td>".tep_draw_separator('pixel_trans.gif',10,30)."</td></tr></table>";
		   echo $ask_confirm_string;
		}else if($command=='confirm' && $gid>0){
			
			$gv_query=tep_db_query("select release_flag from " . TABLE_COUPON_GV_QUEUE . " where unique_id='".tep_db_input($gid)."'");
			$gv_result=tep_db_fetch_array($gv_query);
			if ($gv_result['release_flag']=='N') { 
			  $gv_query=tep_db_query("select customer_id, amount from " . TABLE_COUPON_GV_QUEUE ." where unique_id='".tep_db_input($gid)."'");
			  if ($gv_resulta=tep_db_fetch_array($gv_query)) {
			  $gv_amount = $gv_resulta['amount'];
			  //Let's build a message object using the email class
			  $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . $gv_resulta['customer_id'] . "'");
			  $mail = tep_db_fetch_array($mail_query);
			  $message = TEXT_REDEEM_COUPON_MESSAGE_HEADER;
			  $message .= sprintf(TEXT_REDEEM_COUPON_MESSAGE_AMOUNT, $currencies->format($gv_amount));
			  $message .= TEXT_REDEEM_COUPON_MESSAGE_BODY;
			  $message .= TEXT_REDEEM_COUPON_MESSAGE_FOOTER;
			  $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
			  // add the message to the object
			  $mimemessage->add_text($message);
			  $mimemessage->build_message();
			
			  $mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], '', EMAIL_FROM, TEXT_REDEEM_COUPON_SUBJECT );
			  $gv_amount=$gv_resulta['amount'];
			  $gv_query=tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id='".(int)$gv_resulta['customer_id']."'");
			  $customer_gv=false;
			  $total_gv_amount=0;
			  if ($gv_result=tep_db_fetch_array($gv_query)) {
				$total_gv_amount=$gv_result['amount'];
				$customer_gv=true;
			  }    
			  $total_gv_amount=$total_gv_amount+$gv_amount;
			  if ($customer_gv) {
				$gv_update=tep_db_query("update " . TABLE_COUPON_GV_CUSTOMER . " set amount='".$total_gv_amount."' where customer_id='".(int)$gv_resulta['customer_id']."'");
			  } else {
				$gv_insert=tep_db_query("insert into " .TABLE_COUPON_GV_CUSTOMER . " (customer_id, amount) values ('".$gv_resulta['customer_id']."','".$total_gv_amount."')");
			  }
				$gv_update=tep_db_query("update " . TABLE_COUPON_GV_QUEUE . " set release_flag='Y' where unique_id='".(int)$gid."'");
				echo 'updated' .$gid;
			  }
			}
		
		}else if($command=='page_navigation'){
			echo 'page_navigation';
			echo table_write();
		}
		else if($command=='page_navigations'){
				echo 'page_navigation';?>
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            	<td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              		<tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent" width="30%"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
						<td class="dataTableHeadingContent" width="20%" align="right"><?php echo TABLE_HEADING_ORDERS_ID; ?></td>
						<td class="dataTableHeadingContent" width="20%"align="right"><?php echo TABLE_HEADING_VOUCHER_VALUE; ?></td>
						<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
				
              </tr>
			<?php $gv_query_raw = "select c.customers_firstname, c.customers_lastname, gv.unique_id, gv.date_created, gv.amount, gv.order_id from " . TABLE_CUSTOMERS . " c, " . TABLE_COUPON_GV_QUEUE . " gv where (gv.customer_id = c.customers_id and gv.release_flag = 'N')";
 				 $gv_split = new splitPageResultsEvent($FREQUEST->getvalue('page'), MAX_DISPLAY_SEARCH_RESULTS, $gv_query_raw, $gv_query_numrows);
 				 $gv_query = tep_db_query($gv_query_raw);
  				 while ($gv_list = tep_db_fetch_array($gv_query)) {
	 			    echo '  <tr  class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="javascript:if(document.getElementById(\'head_'.$gv_list['unique_id'].'\').style.display==\'none\') expand_category(' . $gv_list['unique_id'] . ',3)">' . "\n";
?>
		<td colspan="4"> 
				<div id="data_<?php echo $gv_list['unique_id']; ?>" >
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td class="dataTableContent" width="30%"><?php echo $gv_list['customers_firstname'] . ' ' . $gv_list['customers_lastname']; ?></td>
						<td class="dataTableContent" width="20%" align="right"><?php echo $gv_list['order_id']; ?></td>
						<td class="dataTableContent"  width="20%" align="right"><?php echo $currencies->format($gv_list['amount']); ?></td>
						<td class="dataTableContent" align="right"><?php echo tep_datetime_short($gv_list['date_created']); ?></td>
					  </tr>
					   </table>
				</div>
				<div id="head_<?php echo $gv_list['unique_id']; ?>" style="display:none" class="openContent"></div>
				  </td>
				 </tr>
<?php
  } //end of while?>
				<tr>
					<td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
					  <tr>
						<td class="smallText" valign="top"><?php echo $gv_split->display_count($gv_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $FREQUEST->getvalue('page'), TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS); ?></td>
						<td class="smallText" align="right"><?php echo $gv_split->display_script_links($gv_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $FREQUEST->getvalue('page'),1,'expand_category(\'\'','\'\''); ?></td>
					  </tr>
					</table></td>
				  </tr>
<?php 		}
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
<script language="javascript" src="includes/http.js"></script>

<script language="javascript">
var img_src="<?php echo HTTP_SERVER.DIR_WS_ADMIN.'images/';?>";	
function expand_category(id,category,s,sta){ 	
		if(!sta) sta="";
		if(category=='1' && document.getElementById("page_''") && sta!=""){
 	 		id=parseInt(document.getElementById("page_''").options[document.getElementById("page_''").selectedIndex].value);
 	 		if(sta=='next' && document.getElementById("page_''")) {
 	 			if(document.getElementById("page_''").length==document.getElementById("page_''").selectedIndex+1) return;
 	 			id+=1;
 	 		}else if(sta=='prev') {
 	 			if(document.getElementById("page_''").selectedIndex==0) return;
 	 			id-=1;
 	 		}
 	 		if(id<=1) id=1;
 	 	}else if(category=='1' && sta=="") id="";
 	 	 if(document.new_gv && document.new_gv.gv_id && document.new_gv.gv_id.value>0)  { 
			var old_ids=document.new_gv.gv_id.value;			
			var pre_data_id=document.getElementById("data_"+old_ids);
			var pre_head=document.getElementById("head_"+old_ids);			
			var loadings=document.getElementById("loading");
			if(loadings) loadings.style.display="none";
			if(category==1 && pre_head && pre_head.innerHTML!='') pre_head.innerHTML="";									
		 }
		if(category!=1 && document.new_gv && document.new_gv.gv_id&& document.new_gv.gv_id.value=="") document.new_gv.gv_id.value=id; 	 	
			if(id>0 && document.new_gv.gv_id.value>0 && document.new_gv.gv_id.value!=id){			   
			   if(pre_head && pre_head.style.display=="") pre_head.style.display="none";
			   if(pre_data_id && pre_data_id.style.display=="none") pre_data_id.style.display="";
			   document.new_gv.gv_id.value=id;			   
			}
		 								 								 	 	 	 
 	 	if(category){
 	 		var span_details=document.getElementById("span_details");
			var data_id=document.getElementById("data_"+id);	
			var head=document.getElementById("head_"+id);
			
			var command="";																		 
 	 		switch(category){ 	 			
 	 			case '1': 
 	 			       var ser="";
 	 			       var page="";
					   
 	 			       if(document.getElementById('fst_nav')) document.getElementById('fst_nav').style.display='none';
					   if(id!="") page="&page="+id; 
 	 			       command="<?php echo tep_href_link(FILENAME_GV_QUEUE,'command=page_navigation');?>"+page+ser;
					   
 	 			   	   if(document.getElementById("ajax_details")) document.getElementById("ajax_details").innerHTML="<img src='images/24-1.gif' alt='Loading...' title='Loading...'>"; 	 			   					   
 	 			break;
 	 			case 3: 
 	 			   if(head && head.style.display==""){
 	 			   	  if(span_details.style.display=="" || span_ask.style.display==""){
					 
 	 			   	    head.style.display="none"; 	 			   	    
 	 			   	  	data_id.style.display=""; 	 			   	  	
 	 			   	  }	 			    			   	   	 			   
 	 			 }else {  		 	 	 			 	
 	 			 	if(data_id && data_id.style.display=="") data_id.style.display="none";
 	 			 	if(pre_head && pre_head.style.display=="") pre_head.style.display="none"; 			 	
 	 			 	if(head) {
 	 			 	 	head.style.display=""; 	 			 	 	 	 			 	  	 			
 	 			 		head.innerHTML="<img src='images/24-1.gif' alt='Loading...' title='Loading...'>";	 			 		
 	 			 	}
 	 			 	command="<?php echo tep_href_link(FILENAME_GV_QUEUE,'command=get_details');?>&id="+id; 	
					
 	 			 } 	 			  	 			  	 			
 	 			break;
				case 4:
						command="<?php echo tep_href_link(FILENAME_GV_QUEUE,'command=ask_confirm');?>&id="+id; 	
						if(document.getElementById('span_ask')) document.getElementById('span_ask').style.display="";
				break;
				case 5:
						command="<?php echo tep_href_link(FILENAME_GV_QUEUE,'command=confirm');?>&id="+id; 	
				break;
 	 		}
 	 		if(command!="") do_get_command(command);
 	 	}		
 	 }
	 function do_result(result){
	 
	 	if(result.substr(0,11)=='get_details') print_details('get_details',result.substr(11));
		else  if(result.substr(0,15)=='page_navigation') print_details('page_navigation',result.substr(15));
		else  if(result.substr(0,11)=='ask_confirm') print_details('ask_confirm',result.substr(11));
		else  if(result.substr(0,7)=='updated') expand_category(result.substr(6),1);
	 }
	 function print_details(category,result){ 		 
 	 	 	 	 	 	 	
 	 	var no_result=""; 	 	
 	 	var include="";
		if(result.substr(0,15)=='No Record Found'){
 	 		no_result="No Record Found";
 	 	}else if(result!='' && result.length>0 && category!='edit' && result.substr(0,15)!='No Record Found'){
 	 		splt_str=result.split("^"); 
			var gid="";
			var customer_name="";
			var order_id="";
			var amount="";
			var date_created="";
			var ipaddr="";
			
			gid = splt_str[0];
			customer_name = splt_str[1] + '&nbsp;'+ splt_str[2];
			order_id = splt_str[3];
			amount = splt_str[4];
			date_created = splt_str[5];
			ipaddr = splt_str[6];
			
 	 	}	  	 		 	 	 	 	  	 		 	  	 
 		if(document.new_gv && document.new_gv.gv_id) var id=document.new_gv.gv_id.value;
 		var span_details=document.getElementById("span_details");
		var span_ask=document.getElementById("span_ask");	
 		var head=document.getElementById("head_"+id); 		 
 		var data_id=document.getElementById("data_"+id);
 		var img_save=document.getElementById("img_save");
		if(img_save && category!='get_details') img_save.style.display=""; 
 	 	switch(category){ 	 		 	 	 	 		
 	 		case 'get_details': 
			if(head  && no_result!="")
				head.innerHTML=no_result; 	 			 	 		
 	 		else if(head && no_result==""){  
	 	 		display_main ="<table width='100%' border='0' cellspacing='0' cellpadding='0' style='padding-top:4px;' class='cell_bg_popm_left'>"+
				"<tr>"+
					"<td class='main' width='40%'><b>&nbsp;"+customer_name+"</b><td>"+
					"<td width='10%'  onclick='javascript:change_class(this,1);expand_category("+gid+",4);' onmouseover='javascript:change_class(this,2);' onmouseout='javascript:change_class(this,3);' ><img alt='Redeem' title='Redeem' src='images/template/img_edit.gif'></td>"+
					"<td colspan='6' align='right'><a href='javascript:expand_category("+id +",3);'><img src='images/template/img_closel.gif' alt='close' title='close' border='0'></a></td>"+
				"</tr>"+	
				"<tr>"+
				  "<td colspan='8' class='main'><span id='span_ask' style='display:none'></span><span id='span_details'>"+	"<table width='100%' border='0' cellspacing='0' cellpadding='0'>"+
				  		"<tr>"+
				  			"<td width='100'>&nbsp;</td>"+				  							  			
							"<td width='700'>"+				  							  			
				  				"<table width='100%' border='0' cellspacing='5' cellpadding='0' class='main'>"+
				  					"<tr>"+
				  						"<td align='left' width='100' valign='top'><?php echo 'Order Id:';?></td>"+
				  						"<td align='left' width='100' valign='top'>"+order_id+"</td>"+
										"<td align='left' width='100'><?php echo 'Amount' ;?></td>"+
										"<td align='left' width='250'>"+amount+"</td>"+
				  					"</tr>"+			  					
				  					"<tr>"+
				  						"<td align='left' width='100'><?php echo 'Date Created:' ;?></td>"+
				  						"<td align='left' width='100'>"+date_created+"</td>"+
										"<td align='left' width='100'><?php echo 'IP Address:' ;?></td>"+
										"<td align='left' width='250'>"+ipaddr+"</td>"+
				  					"</tr>"+									
									
									"<tr>"+
										'<td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif',50,10); ?></td>'+
									"</tr>"+
				  			  "</table></td></tr>"+
					"</span>"+
				  "</td>"+
				"</tr>"+
			"</table>";
			head.innerHTML = display_main ;
 	 		}		 	 		
 	 	break;
		case 'ask_confirm':
				if(span_ask){
			  		span_ask.innerHTML = result;
					span_ask.style.display='';
					span_details.style.display='none';
					}
		break;
 	 	case 'page_navigation':
 	 		if(document.getElementById("ajax_details")){ 
 	 		 document.getElementById("ajax_details").innerHTML=result;
			 }
 	 	break;	
 	 	}
	  }
	  function change_class(obj,eve){ 	 		 	 	
		 var span_details=document.getElementById("span_details"); 
		  var span_ask=document.getElementById("span_ask");
		 // alert(span_ask);
		 if(obj){		   		    		    
		    var img_get_src=obj.childNodes[0];
			if(img_get_src){
					if(img_get_src.src==img_src+'template/img_edit.gif') { 
					if(span_ask && span_ask.style.display=="" && eve==1 || eve==2){
					 	img_get_src.src=img_src+'template/img_edit_hover.gif';
					}  		   		 	 			 	 			
			    }else if(eve==3 && img_get_src.src==img_src+'template/img_edit_hover.gif' && span_ask && span_ask.style.display=="none") img_get_src.src=img_src+'template/img_edit.gif';
		   }
		 }		 
	  }	
</script>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr> 
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <!--<tr>
            <td class="pageHeading"><?php //echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php //echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>!-->
        </table></td>
      </tr>
      <tr>
        <td id="ajax_details"><form name="new_gv"><input type="hidden" id="gv_id" name="gv_id"></form>
		<!--<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="30%"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" width="20%" align="right"><?php echo TABLE_HEADING_ORDERS_ID; ?></td>
                <td class="dataTableHeadingContent" width="20%"align="right"><?php echo TABLE_HEADING_VOUCHER_VALUE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
				
              </tr>-->
<?php echo table_write();
  /*$gv_query_raw = "select c.customers_firstname, c.customers_lastname, gv.unique_id, gv.date_created, gv.amount, gv.order_id from " . TABLE_CUSTOMERS . " c, " . TABLE_COUPON_GV_QUEUE . " gv where (gv.customer_id = c.customers_id and gv.release_flag = 'N')";
  //echo $gv_query_raw;
  $gv_split = new splitPageResultsEvent($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $gv_query_raw, $gv_query_numrows);
  $gv_query = tep_db_query($gv_query_raw);
  while ($gv_list = tep_db_fetch_array($gv_query)) {
    if (((!$HTTP_GET_VARS['gid']) || (@$HTTP_GET_VARS['gid'] == $gv_list['unique_id'])) && (!$gInfo)) {
      $gInfo = new objectInfo($gv_list);
    }
	 echo '  <tr  class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="javascript:if(document.getElementById(\'head_'.$gv_list['unique_id'].'\').style.display==\'none\') expand_category(' . $gv_list['unique_id'] . ',3)">' . "\n";
?>
<td colspan="4"> 
		<div id="data_<?php echo $gv_list['unique_id']; ?>" >
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
                <td class="dataTableContent" width="30%"><?php echo $gv_list['customers_firstname'] . ' ' . $gv_list['customers_lastname']; ?></td>
                <td class="dataTableContent" width="20%" align="right"><?php echo $gv_list['order_id']; ?></td>
                <td class="dataTableContent"  width="20%" align="right"><?php echo $currencies->format($gv_list['amount']); ?></td>
                <td class="dataTableContent" align="right"><?php echo tep_datetime_short($gv_list['date_created']); ?></td>
              </tr>
			   </table>
		</div>
		<div id="head_<?php echo $gv_list['unique_id']; ?>" style="display:none" class="openContent"></div>
		  </td>
		 </tr>
<?php
  }*/
?>
             <!-- <tr>
                <td colspan="5"><form name="new_gv"><input type="hidden" id="gv_id" name="gv_id"></form>
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr id="fst_nav">
                    <td class="smallText" valign="top"><?php echo $gv_split->display_count($gv_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $FREQUEST->getvalue('page'), TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS); ?></td>
                    <td class="smallText" align="right"><?php echo $gv_split->display_script_links($gv_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $FREQUEST->getvalue('page'),1,'expand_category(\'\'','\'\''); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table>--></td>

          </tr>
		  <?php if($action=='edit' && $oID!='') { 
		 	 $filename=tep_get_report_name($return) ;?>
		  	<tr>
				<td align="right"><?php echo '<a href="' . $filename . '&mPath=' . $FREQUEST->getvalue('mPath') . '">' . tep_image_button("button_back.gif",IMAGE_BACK) . '</a>&nbsp;';?></td>
			</tr>	
		  <?php }?>
        </table></td>
      </tr>
    </table>
<!-- body_text_eof //-->

<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

<?php 

function table_write(){
 global $currencies,$gv_split,$page,$action,$oID;
 if($action=='edit' && $oID!='')
 		$gv_query_raw = "select c.customers_firstname, c.customers_lastname, gv.unique_id, gv.date_created, gv.amount, gv.order_id from " . TABLE_CUSTOMERS . " c, " . TABLE_COUPON_GV_QUEUE . " gv where (gv.customer_id = c.customers_id and gv.release_flag = 'N' and gv.order_id='" . tep_db_input($oID) ."')";
else 			
  $gv_query_raw = "select c.customers_firstname, c.customers_lastname, gv.unique_id, gv.date_created, gv.amount, gv.order_id from " . TABLE_CUSTOMERS . " c, " . TABLE_COUPON_GV_QUEUE . " gv where (gv.customer_id = c.customers_id and gv.release_flag = 'N')";
  $gv_split = new splitPageResultsEvent($page, MAX_DISPLAY_SEARCH_RESULTS, $gv_query_raw, $gv_query_numrows);
  $gv_query = tep_db_query($gv_query_raw);?>
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="30%"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" width="20%" align="right"><?php echo TABLE_HEADING_ORDERS_ID; ?></td>
                <td class="dataTableHeadingContent" width="20%"align="right"><?php echo TABLE_HEADING_VOUCHER_VALUE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
				
              </tr>
<?php  
	$class_name='dataTableRowEven';
	while ($gv_list = tep_db_fetch_array($gv_query)) {
	$class_name=($class_name=='dataTableRowOdd')?'dataTableRowEven':'dataTableRowOdd';
	 echo '  <tr  class="'.$class_name.'" onmouseover="rowOverEffect(this);" onmouseout="rowOutEffect(this);" onclick="javascript:if(document.getElementById(\'head_'.$gv_list['unique_id'].'\').style.display==\'none\') expand_category(' . $gv_list['unique_id'] . ',3)">' . "\n";
?>
<td colspan="4"> 
		<div id="data_<?php echo $gv_list['unique_id']; ?>" >
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
                <td class="dataTableContent" width="30%"><?php echo $gv_list['customers_firstname'] . ' ' . $gv_list['customers_lastname']; ?></td>
                <td class="dataTableContent" width="20%" align="right"><?php echo $gv_list['order_id']; ?></td>
                <td class="dataTableContent"  width="20%" align="right"><?php echo $currencies->format($gv_list['amount']); ?></td>
                <td class="dataTableContent" align="right"><?php echo tep_datetime_short($gv_list['date_created']); ?></td>
              </tr>
			   </table>
		</div>
		<div id="head_<?php echo $gv_list['unique_id']; ?>" style="display:none" class="openContent"></div>
		  </td>
		 </tr>
<?php } if(!tep_db_num_rows($gv_query)){ ?>
		<tr height="10"></tr><tr class="main"><td class="main" colspan="4" align="center"><?php echo TEXT_NO_RESULTS_FOUND ; ?></td></tr>
		<?php } ?>
  <tr>
                <td colspan="5">
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr id="fst_nav">
                    <td class="smallText" valign="top"><?php echo $gv_split->display_count($gv_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $page, TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS); ?></td>
                    <td class="smallText" align="right"><?php echo $gv_split->display_script_links($gv_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $page,1,'expand_category(\'\'','\'\''); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table>
		 </td>
	 </tr> 
 </table>	  	
<?php
}


function get_default_details($gid){

	 $gv_query_raw = "select  c.customers_firstname, c.customers_lastname,gv.order_id, gv.amount, gv.date_created,gv.ipaddr  from " . TABLE_CUSTOMERS . " c, " . TABLE_COUPON_GV_QUEUE . " gv where (gv.customer_id = c.customers_id and gv.unique_id ='" . tep_db_input($gid) ."' )";
	 $gv_query_raw = tep_db_query($gv_query_raw);
	 $gv = tep_db_fetch_array($gv_query_raw);
	
	while(list($key,$vals)= each($gv)){
			if($key=='date_created') $vals = format_date($vals);
			$str_vals .= $vals.'^';
		} 
	echo 'get_details'.$gid .'^'.$str_vals;

}
 /* $heading = array();
  $contents = array();
  switch ($HTTP_GET_VARS['action']) {
    case 'release':
      $heading[] = array('text' => '[' . $gInfo->unique_id . '] ' . tep_datetime_short($gInfo->date_created) . ' ' . $currencies->format($gInfo->amount));

      $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link('gv_queue.php','action=confirmrelease&gid='.$gInfo->unique_id,'NONSSL').'">'.tep_image_button('button_confirm.gif', IMAGE_CONFIRM) . '</a> <a href="' . tep_href_link('gv_queue.php','action=cancel&gid=' . $gInfo->unique_id,'NONSSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      $heading[] = array('text' => '[' . $gInfo->unique_id . '] ' . tep_datetime_short($gInfo->date_created) . ' ' . $currencies->format($gInfo->amount));

      $contents[] = array('align' => 'center','text' => '<a href="' . tep_href_link('gv_queue.php','action=release&gid=' . $gInfo->unique_id,'NONSSL'). '">' . tep_image_button('button_redeem.gif', IMAGE_RELEASE) . '</a>');
      break;
   }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }*/
?>
<script>
<?php if($action=='edit' && $oID!=''){?>
		var gid='<?php echo $gid;?>';
		if(document.getElementById('head_' + gid).style.display=='none')
		expand_category(gid,3)
<?php }?>
</script>

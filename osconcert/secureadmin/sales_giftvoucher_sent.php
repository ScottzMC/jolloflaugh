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
  require('includes/classes/split_page_results_event.php');
  tep_get_last_access_file();require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $command=$FREQUEST->getvalue('command');
  $coupon_id = $FREQUEST->getvalue('id');
  
  if($command != ''){
		if($command=='get_details'){
			echo get_default_details($coupon_id);
		 }else if($command=='page_navigation'){  echo 'page_navigation';?>
		 	<table border="0" width="100%" cellspacing="0" cellpadding="0" >
			 <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="25%"><?php echo TABLE_HEADING_SENDERS_NAME; ?></td>
                <td class="dataTableHeadingContent"  width="25%" align="center"><?php echo TABLE_HEADING_VOUCHER_VALUE; ?></td>
                <td class="dataTableHeadingContent" width="25%" align="center"><?php echo TABLE_HEADING_VOUCHER_CODE; ?></td>
                <td class="dataTableHeadingContent"  width="25%" align="right"><?php echo TABLE_HEADING_DATE_SENT; ?></td>		
              </tr>
<?php 
  $gv_query_raw = "select c.coupon_amount, c.coupon_code, c.coupon_id, et.sent_firstname, et.sent_lastname, et.customer_id_sent, et.emailed_to, et.date_sent, c.coupon_id from " . TABLE_COUPONS . " c, " . TABLE_COUPON_EMAIL_TRACK . " et where c.coupon_id ='". (int)et.coupon_id."'";
 // echo  $gv_query_raw;
  $gv_split = new splitPageResultsEvent($FREQUEST->getvalue('page'), MAX_DISPLAY_SEARCH_RESULTS, $gv_query_raw, $gv_query_numrows);
  $gv_query = tep_db_query($gv_query_raw);
  while ($gv_list = tep_db_fetch_array($gv_query)) {
    if (((!$FREQUEST->getvalue('gid')) || (@$FREQUEST->getvalue('gid') == $gv_list['coupon_id'])) && (!$gInfo)) {
    $gInfo = new objectInfo($gv_list);
    }
	 echo '  <tr  class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="javascript:if(document.getElementById(\'head_'.$gv_list['coupon_id'].'\').style.display==\'none\') expand_category(' . $gv_list['coupon_id'] . ',3)">' . "\n";
	
?> <td colspan="4"> 
		<div id="data_<?php echo $gv_list['coupon_id']; ?>">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
                <td class="dataTableContent" width="25%"><?php echo $gv_list['sent_firstname'] . ' ' . $gv_list['sent_lastname']; ?></td>
                <td class="dataTableContent" width="25%" align="center"><?php echo $currencies->format($gv_list['coupon_amount']); ?></td>
                <td class="dataTableContent"  width="25%" align="center"><?php echo $gv_list['coupon_code']; ?></td>
                <td class="dataTableContent" width="25%"  align="right"><?php echo format_date($gv_list['date_sent']); ?></td>
              </tr>
			 </table>
		</div>
		<div id="head_<?php echo $gv_list['coupon_id']; ?>" style="display:none" class="openContent"></div>
		 </td> 
		</tr> 
		
<?php
 				 } //end of while?>
				 <tr><td colspan="4">
				 <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $gv_split->display_count($gv_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $FREQUEST->getvalue('page'), TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS); ?></td>
                    <td class="smallText" align="right"><?php echo $gv_split->display_script_links($gv_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $FREQUEST->getvalue('page'), 1,'expand_category(\'\'','\'\'');?></td>
			      </tr></table></td></tr>
				  </table></td></tr></table>
<?php	 }
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
 	 			       command="<?php echo tep_href_link(FILENAME_GV_SENT,'command=page_navigation');?>"+page+ser;
					   
 	 			   	   if(document.getElementById("ajax_details")) document.getElementById("ajax_details").innerHTML="<img src='images/24-1.gif' alt='Loading...' title='Loading...'>"; 	 			   					   
 	 			break;
 	 			case 3:
 	 			   if(head && head.style.display==""){
 	 			   	  if(span_details.style.display==""){
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
 	 			 	command="<?php echo tep_href_link(FILENAME_GV_SENT,'command=get_details');?>&id="+id; 	
 	 			 } 	 			  	 			  	 			
 	 			break;
 	 				
 	 		}
 	 		if(command!="") do_get_command(command);
 	 	}		
 	 }
	 function do_result(result){
	 
	 	if(result.substr(0,11)=='get_details') print_details('get_details',result.substr(11));
		else  if(result.substr(0,15)=='page_navigation') print_details('page_navigation',result.substr(15));
	 }
	 function print_details(category,result){ 		 
 	 	 	 	 	 	 	
 	 	var no_result=""; 	 	
 	 	var include="";
		if(result.substr(0,15)=='No Record Found'){
 	 		no_result="No Record Found";
 	 	}else if(result!='' && result.length>0 && category!='edit' && result.substr(0,15)!='No Record Found'){
 	 		splt_str=result.split("^"); 
			var coupon_id="";
			var coupon_amount="";
			var coupon_code="";
			var sender_name="";	 		  	 		
			var sender_id="";
			var email_addr="";
			var date_sent="";
			var redeem="";
			var redeem_date="";
			var redeem_ip="";
			var redee="";
			
			coupon_amount = splt_str[0];
			coupon_code = splt_str[1];
			coupon_id = splt_str[2];
			sender_name = splt_str[3] +'&nbsp;'+splt_str[4];
			sender_id = splt_str[5];
			email_addr = splt_str[6];
			date_sent = splt_str[7];
			redeem =splt_str[8];
			if(splt_str[9]) redeem_date=splt_str[9];
			if(splt_str[10]) redeem_ip=splt_str[10];
			
			if(redeem_date && redeem_ip){
			redee = 			"<tr>"+
								"<td align='left' width='100'><?php echo TEXT_INFO_DATE_REDEEMED ;?></td>"+
								"<td align='left' width='100'>"+redeem_date+"</td>"+
								"<td align='left' width='100'><?php echo TEXT_INFO_IP_ADDRESS;?></td>"+
								"<td align='left' width='250'>"+redeem_ip+"</td>"+
							"</tr>";
			}
 	 	}	  	 		 	 	 	 	  	 		 	  	 
 		if(document.new_gv && document.new_gv.gv_id) var id=document.new_gv.gv_id.value;
 		var span_details=document.getElementById("span_details");	
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
					"<td class='main'><b>&nbsp;"+sender_name+"</b><td>"+
					"<td colspan='7' align='right'><a href='javascript:expand_category("+coupon_id +",3);'><img src='images/template/img_closel.gif' alt='close' title='close' border='0'></a></td>"+
				"</tr>"+	
				"<tr>"+
				  "<td colspan='8' class='main'></span><span id='span_details'>"+	"<table width='100%' border='0' cellspacing='0' cellpadding='0'>"+
				  		"<tr>"+
				  			"<td width='100'>&nbsp;</td>"+				  							  			
							"<td width='700'>"+				  							  			
				  				"<table width='100%' border='0' cellspacing='5' cellpadding='0' class='main'>"+
				  					"<tr>"+
				  						"<td align='left' width='100' valign='top'><?php echo TEXT_INFO_SENDERS_ID;?></td>"+
				  						"<td align='left' width='100' valign='top'>"+sender_id+"</td>"+
										"<td align='left' width='100'><?php echo TEXT_INFO_AMOUNT_SENT ;?></td>"+
										"<td align='left' width='250'>"+coupon_amount+"</td>"+
				  					"</tr>"+			  					
				  					"<tr>"+
				  						"<td align='left' width='100'><?php echo TEXT_INFO_DATE_SENT ;?></td>"+
				  						"<td align='left' width='100'>"+date_sent+"</td>"+
										"<td align='left' width='100'><?php echo TEXT_INFO_VOUCHER_CODE ;?></td>"+
										"<td align='left' width='250'>"+coupon_code+"</td>"+
				  					"</tr>"+									
									"<tr>"+
										"<td align='left' width='100'><?php echo TEXT_INFO_EMAIL_ADDRESS ;?></td>"+
										"<td align='left' width='100'>"+email_addr+"</td>"+
										"<td align='left' width='100'><?php echo 'Redeemed Info:' ;?></td>"+
										"<td align='left' width='250'>"+redeem+"<?php echo '&nbsp;redeemed'; ?></td>"+
									"</tr>";
									
							display_end=		"<tr>"+
										'<td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif',50,10); ?></td>'+
									"</tr>"+
				  			  "</table></td></tr>"+
					"</span>"+
				  "</td>"+
				"</tr>"+
			"</table>";
			head.innerHTML = display_main + redee+display_end;
 	 		}		 	 		
 	 	break;
 	 	case 'page_navigation':
 	 		if(document.getElementById("ajax_details")){ 
 	 		 document.getElementById("ajax_details").innerHTML=result;
			 }
 	 	break;	
 	 	}
	  }
</script>
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" >
          <tr>
           <!--- <td class="pageHeading"><?php //echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php //echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>!-->
          </tr>
        </table></td>
      </tr>
      <tr>
        <td id="ajax_details"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="25%"><?php echo TABLE_HEADING_SENDERS_NAME; ?></td>
                <td class="dataTableHeadingContent"  width="25%" align="center"><?php echo TABLE_HEADING_VOUCHER_VALUE; ?></td>
                <td class="dataTableHeadingContent" width="25%" align="center"><?php echo TABLE_HEADING_VOUCHER_CODE; ?></td>
                <td class="dataTableHeadingContent"  width="25%" align="right"><?php echo TABLE_HEADING_DATE_SENT; ?></td>		
              </tr>
<?php
  $gv_query_raw = "select c.coupon_amount, c.coupon_code, c.coupon_id, et.sent_firstname, et.sent_lastname, et.customer_id_sent, et.emailed_to, et.date_sent, c.coupon_id from " . TABLE_COUPONS . " c, " . TABLE_COUPON_EMAIL_TRACK . " et where c.coupon_id = et.coupon_id";
 // echo  $gv_query_raw;
  $gv_split = new splitPageResultsEvent($FREQUEST->getvalue('page'), MAX_DISPLAY_SEARCH_RESULTS, $gv_query_raw, $gv_query_numrows);
  $gv_query = tep_db_query($gv_query_raw);
  while ($gv_list = tep_db_fetch_array($gv_query)) {
    if (((!$FREQUEST->getvalue('gid')) || (@$FREQUEST->getvalue('gid') == $gv_list['coupon_id'])) && (!$gInfo)) {
    $gInfo = new objectInfo($gv_list);
    }
	 echo '  <tr  class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="javascript:if(document.getElementById(\'head_'.$gv_list['coupon_id'].'\').style.display==\'none\') expand_category(' . $gv_list['coupon_id'] . ',3)">' . "\n";
	
?> <td colspan="4"> 
		<div id="data_<?php echo $gv_list['coupon_id']; ?>" >
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
                <td class="dataTableContent" width="25%"><?php echo $gv_list['sent_firstname'] . ' ' . $gv_list['sent_lastname']; ?></td>
                <td class="dataTableContent" width="25%" align="center"><?php echo $currencies->format($gv_list['coupon_amount']); ?></td>
                <td class="dataTableContent"  width="25%" align="center"><?php echo $gv_list['coupon_code']; ?></td>
                <td class="dataTableContent" width="25%"  align="right"><?php echo format_date($gv_list['date_sent']); ?></td>
              </tr>
			 </table>
		</div>
		<div id="head_<?php echo $gv_list['coupon_id']; ?>" style="display:none" class="openContent"></div>
		 </td> 
		</tr> 
		
<?php
  } if(!tep_db_num_rows($gv_query)){ ?>
		<tr height="10"></tr><tr class="main"><td class="main" colspan="4" align="center"><?php echo TEXT_NO_RESULTS_FOUND ; ?></td></tr>
		<?php } ?>
              <tr>
                <td colspan="5"><form name="new_gv"><input type="hidden" id="gv_id" name="gv_id"></form>
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr id="fst_nav">
                    <td class="smallText" valign="top"><?php echo $gv_split->display_count($gv_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $FREQUEST->getvalue('page'), TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS); ?></td>
                    <td class="smallText" align="right"><?php echo $gv_split->display_script_links($gv_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $FREQUEST->getvalue('page'), 1,'expand_category(\'\'','\'\'');?></td>
			      </tr></table>
				</td>
              </tr>
            </table></td>
<?php
  
  function get_default_details($coupon_id){
  	
	$gv_query_raw = "select c.coupon_amount, c.coupon_code, c.coupon_id, et.sent_firstname, et.sent_lastname, et.customer_id_sent, et.emailed_to, et.date_sent, c.coupon_id from " . TABLE_COUPONS . " c, " . TABLE_COUPON_EMAIL_TRACK . " et where c.coupon_id = et.coupon_id and c.coupon_id =" . tep_db_input($coupon_id);
	$gv_query = tep_db_query($gv_query_raw);
	$gv=tep_db_fetch_array($gv_query);
	while(list($key,$vals)= each($gv)){
		//FOREACH x
		if($key=='date_sent') $vals = format_date($vals);
		$str_vals .= $vals.'^';
	} 
	$redeem_query = tep_db_query("select redeem_date,redeem_ip from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . tep_db_input($coupon_id) . "'");
	$redeemed = 'No';
	if (tep_db_num_rows($redeem_query) > 0) $redeemed = 'Yes';
	$str_vals .= $redeemed.'^';
	if ($redeemed=='Yes') {
		$redeem = tep_db_fetch_array($redeem_query);
		while(list($key,$vals)= each($redeem)){
			if($key=='redeem_date') $vals = format_date($vals);
			$str_vals .= $vals.'^';
		}
	}echo 'get_details'.$str_vals;
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

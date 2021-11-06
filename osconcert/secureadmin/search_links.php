<?php 
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	require('includes/application_top.php');
	require('includes/classes/split_page_results_event.php');
	$retn=$FREQUEST->getvalue('return');
	$search_word = $FREQUEST->postvalue('search_link');
	$command = $FREQUEST->getvalue('command');
	$action = $FREQUEST->getvalue('action');
	$customer_id=$FREQUEST->getvalue('id','string','0');
	$page=(int)$FREQUEST->getvalue('page','int',1);
	
	if($search_word=='')
		$search_word = $FREQUEST->getvalue('search_link');
	if($command!='') {
		echo $command . '{}' . $id . '||';
		switch($command) {
			case 'get_details':
				echo get_customer_details();
			break;
			case 'page_navigation':
				echo getcustomer_lists();
			break;
		}
		exit;
	}
	//if(isset($HTTP_GET_VARS['spage']))
	//	$page=(isset($HTTP_GET_VARS['spage'])?(int)$HTTP_GET_VARS['spage']:'1');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
		<title><?php echo TITLE; ?></title>
		<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
		<script language="javascript" src="includes/menu.js"></script>
		<script language="javascript" src="includes/general.js"></script>
		<script language="javascript" src="includes/http.js"></script>
		<script>
			var prev_id='';
			var page_id=<?php echo $page;?>;
			var img_src="<?php echo HTTP_SERVER.DIR_WS_ADMIN.'images/';?>";
			function do_page_fetch(action,id) {
				var command='';
				command='<?php echo tep_href_link(FILENAME_SEARCH_LINKS);?>' + '?command=' + action + '&id=' + id +'&search_link=' + search_word + '&page=' + page_id;
				switch(action) {
					case 'get_details':
						var head=document.getElementById('head_' + id);
						var data=document.getElementById('data_' + id);
						if(prev_id!=id) {
							row_expand=false;
							close_obj=false;
						}
						if(data.style.display=='' && document.getElementById('data_' + id).innerHTML!='' && close_obj==false)  {
							data.style.display="none";
							data.innerHTML='';
						//	document.getElementById('data_' + id).innerHTML='';
							head.style.display='';
							row_expand=false;
							ajx_start=false;
							prev_id='';
							return;
						}if(data.style.display=="none" && row_expand==false){ 
							row_expand=true;
							data.style.display="";
							document.getElementById('data_' + id).innerHTML=document.getElementById('loading').innerHTML;
							if(prev_id!=''){
								document.getElementById('head_' + prev_id).style.display="";
								document.getElementById('data_' + prev_id).innerHTML="";
								document.getElementById('data_' + prev_id).style.display="none";
							}
							prev_id=id;
							head.style.display="none"
						} 
						close_obj=false;
					break;
					case 'edit': 
						document.getElementById('span_details').innerHTML=document.getElementById('loading').innerHTML;;
					//	location.href='<?php echo tep_href_link(FILENAME_CUSTOMERS,'mPath=1');?>&cID='+id+'&action=edit&return=csl&page=' + page_id+ '&search_link='  + search_word ;
                        location.href='<?php echo tep_href_link(FILENAME_CUSTOMERS_MAINPAGE,'top=1&mPath=1_328');?>&cID='+id;
						return;
					break;
				}
				
				if(command!='')
					do_get_command(command);
			}
			function do_result(result) {
				var splt=Array();
				splt=result.split("{}");
				var spl_arr=Array();
				var sp1=splt[1];
				spl_arr=sp1.split("||");
				switch(splt[0]) {
					case 'get_details':
						document.getElementById('head_' + spl_arr[0]).style.display="none";
						document.getElementById('data_' + spl_arr[0]).style.display="";
						document.getElementById('data_' + spl_arr[0]).innerHTML=spl_arr[1];
					break;
					case 'page_navigation':
						document.getElementById('ajx_details').innerHTML=spl_arr[1];
					
					break;
				}	
				ajx_start=false;
			}
			function do_hover_change(element,eve){ 
			  var img_get_src=element.childNodes[0];
				if(eve==2) {
					img_get_src.src=img_src+'template/img_edit_hover.gif';
				} else if(eve==3){
					img_get_src.src=img_src+'template/img_edit.gif';
				}
					
			 }  
			function edit_customer(id) {
				document.getElementById('span_details').innerHTML=document.getElementById('loading').innerHTML;;
				location.href='<?php echo tep_href_link(FILENAME_CUSTOMERS,'mPath=1');?>&cID='+id+'&action=edit&return=csl&page=' + page_id+ '&search_link='  + search_word ;
			}
			
			function do_page_navigation(value,params,name,plink){ 
				if(params=='page_navigation' && document.getElementById("page_''") && plink!=''){
					 page_id = parseInt(document.getElementById("page_''").options[document.getElementById("page_''").selectedIndex].value);
					if(plink=='next')
						page_id=page_id+1;
					else if(plink=='prev')
						page_id=page_id-1;
				}
				prev_page_id=page_id;
				prev_id='';
				command='<?php echo tep_href_link(FILENAME_SEARCH_LINKS);?>' + '?command=page_navigation&page=' + page_id + '&search_link=' + search_word;
				do_get_command(command);
			}


		</script>
	</head>
<?php  require(DIR_WS_INCLUDES . 'header.php');?>
<table width="100%" border="0" cellpadding="2" cellspacing="2">
	<tr>
		<td width="100%" valign="top">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">

			<div id="loading" style="display:none"><div style="height:30px;margin-left:30px"><img src="images/24-1.gif"></div></div>
			<input type="hidden" id="search_word" value="<?php echo tep_output_string($search_word);?>"/>
	 <?php 	// Freeway coding start
	 		if($retn=='csl' || strtolower($search_word)=='customers') { 
				echo '<tr><td class="pageHeading">' . sprintf(TEXT_CUSTOMER_HEADING,$search_word) . '</td><td>' . tep_draw_separator('pixel_trans.gif','10','10'). '</td></tr>';
				echo '<tr><td id="ajx_details" colspan="2">';
				echo getcustomer_lists();	
				if(strtolower($search_word)!='customers')
					$where.= " and (c.customers_firstname like '%" . tep_db_input($search_word) . "%' or c.customers_lastname like '%" .tep_db_input($search_word) . "%')";
					$customers_query_raw=tep_db_query("select c.customers_id,c.customers_firstname,c.customers_lastname,c.customers_email_address from " .TABLE_CUSTOMERS_INFO." ci, ". TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id where ci.customers_info_id=c.customers_id and ci.customers_info_id=a.customers_id " . $where);
					if(tep_db_num_rows($customers_query_raw)>0) 
						$search_word='customers';
					
				echo '</td></tr>';
				echo '<tr><td colspan="2">' . tep_draw_separator('pixel_trans.gif','20','20') . '</td></tr>';
			} //else {
				$parent_name = array();
				$search_link_lists = array();
				$parent_id=""; 
				$parent_ids = "";
				if($search_word!=''){
				$search_links_sql = " select amd.*,am.menu_id,am.parent_id,am.filename,am.params from admin_menus_description  amd, admin_menus am
									  where menu_text like '%" . tep_db_input($search_word) . "%' and am.menu_id=amd.menu_id";
				
				$search_links_query = tep_db_query($search_links_sql);
				if(tep_db_num_rows($search_links_query)>0){
				while($search_links = tep_db_fetch_array($search_links_query)){
					 $parent_ids .= $search_links['parent_id'].',';
					
					$search_link_lists[] = array( 'menu_id' => $search_links['menu_id'],
												   'menu_text'	=> $search_links['menu_text'],
												   'filename' => $search_links['filename'],
												   'params' => $search_links['params'],
												   'parent_id' => $search_links['parent_id']);
				 }
				
				$parent_ids = substr($parent_ids,0,-1);
				$menu_text_sql = "select menu_text,menu_id from admin_menus_description where menu_id in (" . $parent_ids . ")";
				 $menu_text_query = tep_db_query($menu_text_sql);
				
				 while($menu_text = tep_db_fetch_array($menu_text_query)){
					$parent_name[$menu_text['menu_id']] = $menu_text['menu_text'];
				 }
				 $search_link_count = count($search_link_lists);
				 for($i=0;$i<$search_link_count;$i++){
					 if(array_key_exists($search_link_lists[$i]['parent_id'],$parent_name)){
						$parent_id = $search_link_lists[$i]['parent_id'];
						$search_link_lists[$i]['parent_name'] = $parent_name[$parent_id];
					 }	
				   }
				  }
				}
		?>
				<tr>
					<td class="pageHeading"><?php echo sprintf(TEXT_TITLE_HEADING,$search_word);?></td>
					<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
				</tr>
				<tr>
					<td colspan="2">
					<table width="100%" cellpadding="2" cellspacing="2" border="0">
					<?php if($search_link_count>0){
							$n=1; 
							 for($i=0;$i<$search_link_count;$i++){
							   if($search_link_lists[$i]['params'])
								$search_link_lists[$i]['filename'] = $search_link_lists[$i]['filename'] . '?' . $search_link_lists[$i]['params'];
							   
								if($search_link_lists[$i]['filename']){
								
								echo '<tr class="dataTableRow" height="20">' .
									 '<td class="search" width="5%">' . ($n) . '</td>' .
									 '<td class="search" width="45%">' .
									 '<a class="search" href="' . $search_link_lists[$i]['filename'] . '">' . $search_link_lists[$i]['menu_text'] . '</a></td>' .
									 '<td class="search" width="50%">' . $search_link_lists[$i]['parent_name'] . '</td></tr>';
								$n = $n+1;
							   }
							 }
					}else{
							 ?>
							 <tr height="30">
							<td class="main" align="center" width="100%" valign="bottom"><?php echo TEXT_NO_RESULTS_FOUND;?></td>
							</tr>
					<?php  } ?>
					
			</table>
			</td>
		</tr>
	<?php //} ?>
	</table>
	</td>
	</tr>
</table>
<?php require(DIR_WS_INCLUDES . 'footer.php');?>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');
	

	function getcustomer_lists() {
		global $search_word,$page; 
		$where="";
		$result="";
		if(strtolower($search_word)!='customers')
			$where.= " and (c.customers_firstname like '%" . tep_db_input($search_word) . "%' or c.customers_lastname like '%" .tep_db_input($search_word) . "%')";
			$customers_query_raw="select c.customers_id,c.customers_firstname,c.customers_lastname,c.customers_email_address from " .TABLE_CUSTOMERS_INFO." ci, ". TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id where ci.customers_info_id=c.customers_id and ci.customers_info_id=a.customers_id " . $where;
						
		    $customers_query_split = new splitPageResultsEvent($page, MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_raw_numrows);
			$customers_query=tep_db_query($customers_query_raw);
			$result='<table cellpadding="0" cellspacing="0" border="0" width="100%">' .
						'<tr><td colspan="3">' . tep_draw_separator('pixel_trans.gif','10','10') . '</td></tr>'.
						'<tr class="dataTableHeadingRow"><td class="dataTableHeadingContent" width="30%">' . HEADING_LAST_NAME . '</td><td class="dataTableHeadingContent" width="30%">' . HEADING_FIRST_NAME . '</td><td class="dataTableHeadingContent" width="30%">' . HEADING_EMAIL_ADDRESS . '</td></tr>'.
						'<tr><td colspan="3">';
			$result.='<table cellpadding="0" cellspacing="0" border="0" width="100%">';
			if(tep_db_num_rows($customers_query)>0) {
				
				while($customers_result=tep_db_fetch_array($customers_query)) {
					if(($row%2)==0)
						$class="dataTableRowEven";
					else 
						$class="dataTableRowOdd";
					$result.='<tr id="" class="' . $class . '" onClick="if(document.getElementById(\'data_' . $customers_result['customers_id']. '\').style.display==\'none\') do_page_fetch(\'get_details\',\'' . $customers_result['customers_id'] . '\');" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">'.
								'<td><div id="head_' . $customers_result['customers_id'] . '"><table cellpadding="3" cellspacing="3" border="0" width="100%" ><tr>' .
										'<td class="dataTableContent" width="30%">' . $customers_result['customers_lastname'] . '</td>'.
										'<td class="dataTableContent" width="30%">' . $customers_result['customers_firstname'] . '</td>'.
										'<td class="dataTableContent" width="30%">' . $customers_result['customers_email_address'] . '</td>'.
										
										'<td width="40%"></td>'.
									'</tr></table></div><div id="data_' . $customers_result['customers_id'] . '" style="display:none"></div>'.
								'</td>'.
							'</tr>'; 
					
				}
				$result.='<tr><td>' . tep_draw_separator('pixel_trans.gif','10','10') . '</td></tr>';
				$result.='<tr><td><table cellpadding="0" cellspacing="0" width="100%" border="0"><tr>'.
							'<td class="smallText" valign="top">' .  $customers_query_split->display_count($customers_query_raw_numrows, MAX_DISPLAY_SEARCH_RESULTS, $page, TEXT_DISPLAY_NUMBER_OF_CUSTOMERS) . '</td>'.
							'<td class="smallText" align="right"> ' . $customers_query_split->display_script_links($customers_query_raw_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $page,'page_navigation','do_page_navigation(\'\'','\'\'') . '</td>'.
						'</tr></table></td></tr>';	
				
				
			} else {
				$result.='<tr><td align="center">' . TEXT_NO_RESULTS_FOUND . '</td></tr>';
			}
			$result.='</table></td></tr></table>';
			
			return $result;
	}
	
	function get_customer_details () {
			global $customer_id,$search_word;
			$dis_time_format="";
			if(defined('TIME_FORMAT')) $dis_time_format=TIME_FORMAT;

			$customer_query=tep_db_query("select c.customers_id,c.customers_firstname,c.customers_lastname,c.customers_email_address,date_format(ci.customers_info_date_account_created,'%Y-%m-%d') as account_created,ci.customers_info_date_of_last_logon as last_logon,ci.customers_info_number_of_logons as numberof_logons,date_format(ci.customers_info_date_account_last_modified,'%Y-%m-%d') as last_modified,a.entry_country_id from " .TABLE_CUSTOMERS_INFO." ci, ". TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id where ci.customers_info_id=c.customers_id and ci.customers_info_id=a.customers_id and c.customers_id='".(int)$customer_id."'");	
		$customer_result = tep_db_fetch_array($customer_query); $row=0;
		$country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$customer_result['entry_country_id'] . "'");
		$country = tep_db_fetch_array($country_query);
		$result='<table width="100%" border="0" cellspacing="0" cellpadding="0"   class="openContent">'.
				'<tr heigth="40" bgcolor="#EDF1FE"  class="openContent_top" onclick="javascript:do_page_fetch(\'get_details\',\'' . $customer_id . '\');">';  	
		$result.=		  '<td width="40" valign="top" align="center">&nbsp;</td>';
		$result.=		  '<td align="left" valign="middle" width="350" class="inner_title">'. tep_draw_separator('pixel_trans.gif','10','10') . $customer_result['customers_lastname'] . ' ' . $customer_result['customers_firstname'].'</td>'.
				  '<td width="470" align="left" class="main" valign="top">'.
				  	'<table border="0" cellspacing="0" cellpadding="0" style="width:100;height:30px;display:inline;position:relative;">'.
				  	  '<tr class="img_move" id="img_ids">'.
				  	  	'<td class="cell_bg_popm_left" width="10">&nbsp;</td>';
					$result.=   '<td width="10%" onclick="javascript:do_hover_change(this,1);do_page_fetch(\'edit\',\'' . $customer_id .'\');" onmouseover="javascript:do_hover_change(this,2);" onmouseout="javascript:do_hover_change(this,3);"><img alt="Edit" title="Edit" src="images/template/img_edit.gif"></td>'.
				  	  
                        '<td class="cell_bg_popm_right" width="10">&nbsp;</td>'.
				  	  '</tr>'.
				  	'</table>'.
				  '</td>';
			$result.=	'</tr>';
				$customer_account_created=format_date($customer_result['account_created']);
				$customer_last_modified=format_date($customer_result['last_modified']);
				$customer_last_logon=format_date($customer_result['last_logon']);
				
				/*$acc_created=date('h:i A',strtotime($customer_result['account_created']));
				$las_modified=date('h:i A',strtotime($customer_result['last_modified']));
				$last_logon=date('h:i A',strtotime($customer_result['last_logon']));
				if($dis_time_format!="") {
					if($dis_time_format=='24') {
						$acc_created=date('H:i:s',strtotime($customer_result['account_created']));
						$las_modified=date('H:i:s',strtotime($customer_result['last_modified']));
						$last_logon=date('H:i:s',strtotime($customer_result['last_logon']));
					}
				}
				$customer_account_created=$customer_account_created . ' ' . $acc_created;
				$customer_last_modified=$customer_last_modified . ' ' . $las_modified;
				$customer_last_logon=$customer_last_logon . ' ' . $last_logon;*/

					$result.= '<tr><td width="40" valign="top" align="center">&nbsp;</td><td colspan="4"><div id="span_details" class="openContent"><table cellpadding="2" cellspacing="2" border="0" width="100%">';
					$result.='<tr><td colspan="4">' . tep_draw_separator('pixel_trans.gif','10','10') . '</td></tr>';
					$result.='<tr><td class="main" width="125">' . TEXT_EMAIL_ADDRESS . '</td><td class="main" width="175">' . $customer_result['customers_email_address'] . '</td><td class="main" width="125">' . TEXT_ACCOUNT_CREATED . '</td><td class="main">' . $customer_account_created . '</td><td class="main" width="125">' . TEXT_LAST_LOGON . '</td><td class="main" width="175">' .  $customer_last_logon . '</td><tr>';
					$result.='<tr><td class="main" width="125">' . TEXT_COUNTRY . '</td><td class="main">' . $country['countries_name'] . '</td><td class="main" width="125">' . TEXT_LAST_MODIFIED . '</td><td class="main" width="175">' . $customer_last_modified . '</td><td class="main" width="125">' . TEXT_NUMBER_OF_LOGONS . '</td><td class="main">' . $customer_result['numberof_logons'] . '</td><tr>';
					$result.='<tr><td colspan="5">&nbsp;</td><td align="center"><a href="'.tep_href_link(FILENAME_ORDERS,'cID='.$customer_result['customers_id'].'&search_word='.$search_word).'">'.tep_image_button('button_orders.gif','Orders').'</a></td></tr>';
					$result.='<tr><td colspan="4">' . tep_draw_separator('pixel_trans.gif','10','10') . '</td></tr></table></div></td></tr>';
					
			$result .='</table>';
			return $result;

	}
?>
<script>
<?php if($action=='get_details' && $retn=='csl') { ?>
			do_page_fetch('get_details','<?php echo $customer_id;?>');
<?php } ?>
		var search_word=document.getElementById('search_word').value;
</script>

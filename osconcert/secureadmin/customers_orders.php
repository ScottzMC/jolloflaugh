<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	define('_FEXEC',1);
	require('includes/application_top.php');
	require(DIR_WS_INCLUDES . '/tweak/general.php');
	frequire($FSESSION->language.'/customers_orders.php',RLANG);
	frequire($FSESSION->language.'/customers_orders_refund.php',RLANG);
	

		//delete/restore tables - array
		//pop them into an array - key is now table name, value is the column used for the order_id
		$table_array=array();
		$table_array['coupon_redeem_track']='order_id';
        $table_array['coupon_season_track']='order_id';
		$table_array['orders']='orders_id';
		$table_array['orders_products']='orders_id';
		$table_array['orders_status_history']='orders_id';
		$table_array['orders_total']='orders_id';
		$table_array['payment_response']='order_id';
		$table_array['refunds']='orders_id';
		$table_array['orders_tickets']='orders_id';
		$table_array['orders_barcode']='orders_id';
		$table_array['stripe_data']='orders_id';
		$table_array['email_data']='order_id';
		
					
		
//jump in here looking for the $_POST['wipe_all'] if this is
//  present then the delete multiple orders form has been submitted

if ($_POST['wipe_all']=='wipe_all' ){	
		foreach($table_array as $k=>$v){
			if(table_exists($k)){
				//(1) Create the backup tables if this option has been selected
				if ($_POST['backup_select']=='yes'){
				//(2) DROP any existing backups in case there has been a change in structure
				//    in the original tables
				  tep_db_query("drop table if exists bax_".$k);
		          tep_db_query("create table bax_".$k." like ".$k);
                //(3) copy over
                  tep_db_query("insert into bax_".$k." select * from ".$k);
				//create a session to hold the undelete option
		          $_SESSION['order_restore']=DELETE_ALL_ORDERS_KEYWORD;
				}//end backup_select
				//delete any leftover tables
				 else{
					 tep_db_query("drop table if exists bax_".$k);
				 }
                //(4) if keyword and delete all
				if ($_POST['delete_all']==DELETE_ALL_ORDERS_KEYWORD){
		          tep_db_query("truncate ".$k);
				}
				//(5) elseif keyword and delete up to
				elseif ($_POST['delete_up_key']==DELETE_ALL_ORDERS_KEYWORD && is_numeric($_POST['delete_up'])){
		          tep_db_query("delete from ".$k ." where ".$v." <= ".$_POST['delete_up']);
				}
				//(6) elseif keyword and delete from
				elseif ($_POST['delete_from_key']==DELETE_ALL_ORDERS_KEYWORD && is_numeric($_POST['delete_from'])){
		          tep_db_query("delete from ".$k ." where ".$v." >= ".$_POST['delete_from']);
				}
			}//end if table exists
		//end the delete all orders loop
		}
		


		}
	
//undo the delete - once only option, drop backup tables after
    	if ($_POST['restore_all']== DELETE_ALL_ORDERS_KEYWORD && $_POST['unwipe_all']=='unwipe_all'){		
		foreach($table_array as $k=>$v){
				if((table_exists($k)) && (table_exists('bax_'.$k))){
				  tep_db_query("truncate ".$k);
                  tep_db_query("insert into ".$k." select * from bax_".$k);
				  tep_db_query("drop table if exists bax_".$k);
				}
		//end the delete all orders loop
		}
		}
		
	// ############################################################################
	function table_exists($tablename, $database = false)
					{
					$res = tep_db_query("
         SHOW TABLES IN " . DB_DATABASE . " LIKE '$tablename'
        ");
					if (tep_db_num_rows($res) < 1)
						{
						return 0;
						}
					  else
						{
						return 1;
						}
					}	
		
	
	frequire('currencies.php',RCLA);
	$currencies = new currencies();
	
	//kill any referrer session
	 if ($FSESSION->is_registered('the_referrer')) {
	 $FSESSION->remove('the_referrer');}

	$LANGUAGES=tep_get_languages();

//	$CUSTORD=&instance::getTweakObject('display.customersOrders');
	$CUSTORD= (new instance)->getTweakObject('display.customersOrders');
	$CUSTORD->pagination=true;
	checkAJAX('CUSTORD');

	$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));
	$jsData->VARS["page"]=array('lastAction'=>false,
								'opened'=>array(),
								'locked'=>false,
								'NUlanguages'=>$LANGUAGES,
								'imgPath'=>DIR_WS_IMAGES,
								"menu"=>array(),
								'link'=>tep_href_link('customers_orders.php'),
								'searchMode'=>false,
								'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY,
								'crypted'=>$ENCRYPTED,
								'alterRows'=>true);
	$jsData->VARS["page"]["template"]=array("TEXT_DELIVERED_ID"=>TEXT_DELIVERED_ID,
											"ERROR_EMPTY_SHIPPING_DATE"=>ERROR_EMPTY_SHIPPING_DATE,
											"TEXT_LOADING"=>TEXT_LOADING_DATA,
											"IMAGE_DELETE_MANUFACTURER"=>IMAGE_DELETE_MANUFACTURER,
											"INFO_SEARCHING_DATA"=>INFO_SEARCHING_DATA,
											"INFO_LOADING_DATA"=>INFO_LOADING_DATA,
											"ERROR_EMPTY_AMOUNT"=>ERROR_EMPTY_AMOUNT,
											"ERROR_EMPTY_RESTOCK_CHOICE"=>ERROR_EMPTY_RESTOCK_CHOICE,
											"ERR_CHOICE_EMPTY"=>ERR_CHOICE_EMPTY,
											"ERR_AMOUNT_EMPTY"=>ERR_AMOUNT_EMPTY,
											"ERR_REFUND_AMOUNT"=>ERR_REFUND_AMOUNT,
											"ERR_PERCENTAGE_VALUE"=>ERR_PERCENTAGE_VALUE
											
											);

	$jsData->VARS["page"]["NUmenuGroups"]=array("normal","update","refund");
    $display_order_id=$FREQUEST->getvalue('oID','int',0);
    $page='';

         if($display_order_id>0)
        {
         $jsData->FUNCS[]="doDisplayAction({'id':" . $display_order_id . ",get:'EditCustomerOrder','result':doDisplayResult,'type':'custord','params':'oID=" . $display_order_id . "','style':'boxRow'})";
         $orders_ids_query=tep_db_query("select o.orders_id   from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s, " .TABLE_ORDERS_PRODUCTS." op, ".TABLE_CUSTOMERS ." c where o.customers_id=c.customers_id and o.orders_status = s.orders_status_id and o.orders_id=op.orders_id and  s.language_id = '" . (int)$FSESSION->languages_id . "' and ot.class = 'ot_total' group by o.orders_id  order by o.orders_id DESC");
         $i=0;
          while($orders_ids_array=tep_db_fetch_array($orders_ids_query))
            {
                $count_orders_ids_query=tep_db_query("select orders_id from ".TABLE_ORDERS." where orders_id >=".$display_order_id." and orders_id='".$orders_ids_array['orders_id']."'");
                while($count_orders_ids_array=tep_db_fetch_array($count_orders_ids_query))
                {
                if($count_orders_ids_array['orders_id']>0)$i++;
                }
            }
            

         $page=(int)($i/MAX_DISPLAY_SEARCH_RESULTS)+ (($i % MAX_DISPLAY_SEARCH_RESULTS)>0? 1 :0);

        }
     /* if($display_order_id>0)
     
        $jsData->FUNCS[]="doDisplayAction({'id':" . $display_order_id . ",get:'EditCustomerOrder','result':doDisplayResult,'type':'custord','params':'oID=" . $display_order_id . "','style':'boxRow'})";*/

        
        tep_get_last_access_file();
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
    
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script language="javascript" src="includes/http.js"></script>

<script language="javascript" src="includes/jquery-1.10.2.js"></script> <!-- Added By R -->

<script language="javascript" src="includes/date-picker.js"></script>
<script type="text/javascript" src="includes/aim.js"></script>
<script type="text/javascript" src="includes/tweak/js/ajax.js"></script>
<script type="text/javascript" src="includes/tweak/js/customersOrders.js"></script>
<script type="text/javascript">

$(function() {
    var limit = 4;
$('input.ordchkcls').on('change', function(evt) {
   if($("input[name='ord_status[]']:checked").length > limit) {
       this.checked = false;
	   alert('Quick order update limited to '+limit+' orders');
   }
});
});

</script>
<script type="text/javascript">

function doOrderBy(action)
{
	//alert(action.type+'  '+action.get+'  '+action.params+' '+action.message);
	//if (page.locked || (action.get!='Search' && page.searchMode)) return;
		if (action.closePrev && !closePreviousOpened(action)) return;
		checkMessageDisplay(action);
		page.lastAction=action;
		
			do_get_command(page.link+'?AJX_CMD='+action.get+'&order='+action.params+'&value='+action.value+'&search='+action.searchparam+'&filter='+action.filter);

}

function doPageAction1(action)
	{
		
		
		if (action.closePrev && !closePreviousOpened(action)) return;
		checkMessageDisplay(action);
		page.lastAction=action;
		do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params+'&order='+action.orderby+'&value='+action.value+'&search='+action.search+'&filter='+action.filter+'&cID='+action.cID+'&search_word='+action.search_word);
		
	}

 function doPageAction2(action)
	{


		if (action.closePrev && !closePreviousOpened(action)) return;
		checkMessageDisplay(action);
		page.lastAction=action;
		do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params);

	}
function showHide() {
    var showme= document.getElementById('showme');
    showme.style.display = (showme.style.display == 'none') ? 'block' : 'none';
}		

function delete_manage(button_id,field_id,keyword_id) {
	
	delete_all_submit.disabled = true;
	delete_up_submit.disabled = true;
	delete_from_submit.disabled = true;
	
	if (field_id != 'nil'){
		var inputValue = document.getElementById(field_id).value;
	}
	var bt =  document.getElementById(button_id);
	var keyword = document.getElementById(keyword_id).value;
	
	if (keyword != "<?php echo DELETE_ALL_ORDERS_KEYWORD;?>"){return}
		
	if ( button_id == "delete_all_submit" && keyword == "<?php echo DELETE_ALL_ORDERS_KEYWORD;?>") {
		document.getElementById('delete_up_key').value = '';
		document.getElementById('delete_from_key').value = '';
		document.getElementById('delete_up').value = '';
		document.getElementById('delete_from').value = '';
		bt.disabled = false
	}
	else if ( button_id == "delete_up_submit" &&  parseInt(inputValue ) == inputValue && inputValue > 0 && keyword == "<?php echo DELETE_ALL_ORDERS_KEYWORD;?>") {
		document.getElementById('delete_all').value = '';
		document.getElementById('delete_from_key').value = '';
		document.getElementById('delete_from').value = '';
		bt.disabled = false
	}
	else if ( button_id == "delete_from_submit" &&  parseInt(inputValue ) == inputValue && inputValue > 0 && keyword == "<?php echo DELETE_ALL_ORDERS_KEYWORD;?>") {
		document.getElementById('delete_all').value = '';
		document.getElementById('delete_up_key').value = '';	
		document.getElementById('delete_up').value = '';
		bt.disabled = false
	}
	else {
		bt.disabled = true; 
	}
    }

function validateMyForm(stuff,order_number) {
	if (stuff == "delete_all"){

    // return confirm('<?php echo TEXT_ABOUT_DELETE_ALL;?>')	
		
		  if(!confirm("<?php echo TEXT_ABOUT_DELETE_ALL;?>")) {
			 clear_inputs(stuff);
          return false;  }
		document.getElementById('showme').style.display="none";
		document.getElementById('wait').style.display="block";
        document.getElementById("myForm").submit();
		//alert ("die");
	}
	
	else if (stuff == "delete_up"){
		var keyword = document.getElementById("delete_up").value;
		if(!confirm("<?php echo TEXT_ABOUT_DELETE_ALL_TO;?>" + keyword)) {
		clear_inputs(stuff);
          return false;  }
		document.getElementById('showme').style.display="none";
		document.getElementById('wait').style.display="block";
        document.getElementById("myForm").submit();
		//alert ("die");
	}
	else if (stuff == "delete_from"){
		var keyword = document.getElementById("delete_from").value;
		if(!confirm("<?php echo TEXT_ABOUT_DELETE_ALL_FROM;?>" + keyword)) {
		  clear_inputs(stuff);
          return false;  }
		document.getElementById('showme').style.display="none";
		document.getElementById('wait').style.display="block";
        document.getElementById("myForm").submit();
		//alert ("die");
	}
	
}
	
function clear_inputs(button_id){
		document.getElementById('delete_all').value = '';
		document.getElementById('delete_up_key').value = '';	
		document.getElementById('delete_up').value = '';
	    document.getElementById('delete_from_key').value = '';	
		document.getElementById('delete_from').value = '';
	    document.getElementById(button_id+"_submit").disabled = true;
	
}
function validateMyForm2() {
    var x = document.forms["myForm2"]["restore_all"].value;
    if (x == null || x == "" || x !== "<?php echo DELETE_ALL_ORDERS_KEYWORD;?>") {
        alert("Please enter <?php echo DELETE_ALL_ORDERS_KEYWORD;?> to restore all records");
        return false;
    }else{
		document.getElementById('undelete1').style.display="none";
		document.getElementById('undelete0').style.display="block";
	}
     
}
// Added By R
function updateord()
	{
        var ordstr = '';
        $('.ordchkcls').each(function() {
            if($(this).is(':checked') == true)
                {
                    //alert($(this).val());
                    ordstr += $(this).val() + ",";
                }
            //$(this).prop("checked", all.prop("checked"));

        });
        if(ordstr == '')
        {
            alert("<?php echo TEXT_AT_LEAST; ?>");
            return false;
        }
        $.ajax({
		type: "POST",
		data: "actval=updateordsta&ordid="+ordstr,
		url: "edit_orders.php",
		success: function(response){
		//console.log(response);
                //if(response == 'Sucess')//not working for php7.4.20 do we need it??
                //{
                    $('#display-alert-msg').remove();
                    $('<div id="display-alert-msg"></div>').appendTo('body');
                    $('#display-alert-msg').removeClass('');
                    $('#display-alert-msg').html('<?php echo ORDER_UPDATE_SUCCESS; ?>');
                    $('#display-alert-msg').addClass('notify_label alert-success');
                    $('.alert-success').slideDown().delay(2000).slideUp();
                   // setInterval("location.href = 'customers_orders.php'",3000);
					setTimeout(function(){location.href="customers_orders.php"} , 5000);
                //}
		//var result =$.parseJSON(response);

	},
		complete: function(){
				console.log( "AJAX - complete()" );
				//hideLoadingBar();
		}
	});
        //alert(ordstr);
        //do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params);
		/*if (action.closePrev && !closePreviousOpened(action)) return;
		checkMessageDisplay(action);
		page.lastAction=action;
		do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params);*/
return false;
	}
</script>
<style>/* New CSS added for Error */
.notify_label{
		margin-bottom: 8px;
		padding: 8px 35px 8px 14px;
		position:fixed;
		display:none;
    	text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
		z-index:10000;
		top:0%;
		left:30%;
		width:50%;
		text-align:center;
	}


.alert-success {
    background-color: #DFF0D8;
    border-color: #46886A;
    color: #468847;
}

.success{background:#d8f59f}

.errormsg{background:#faa}
.warningmsg{background:#fff2aa}</style>
		</head>
		<body marginwidth="0"  marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="javascript:pageLoaded();">
			<!-- header //-->
			<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
			<!-- header_eof //-->
		<?php
		if(SHOW_OSCONCERT_HELP=='yes')
		{
		?>
		<div class="osconcert_message"><?php echo TEXT_IMPORTANT; ?></div>
		<?php
		}
		?>			
			
			<!-- body //-->
			<table border="0" width="100%" cellspacing="2" cellpadding="2">
				<!-- body_text //-->
				<tr>
				<td valign="top">
				
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="main">
									<b><?php echo HEADING_TITLE;?></b>
								</td>
								<td class="main">
									<?php 
								  $orders_statuses = array();
								  $orders_status_array = array();
								  $orders_statuses=tep_get_orders_status();
								  //March 2014 new search array
								  $orders_search=array();
								  //add in the fields you want to search here
								  //tie into the code in includes/tweak/customersOrders.php
								  //in function doGetCustomersOrders()
								  //$orders_search[]=array('id' => 1, 'text'=>'Order ID');
								  
								  $orders_search[]=array('id' => 2, 'text'=>TEXT_CUSTOMER_NAME);
								  $orders_search[]=array('id' => 3, 'text'=>TEXT_SHOW_NAME);
								  $orders_search[]=array('id' => 4, 'text'=>TEXT_SEAT_NUMBER);
								  $orders_search[]=array('id' => 5, 'text'=>TEXT_REF_ID);
								  $orders_search[]=array('id' => 6, 'text'=>TEXT_BILLING_NAME);
								  $orders_search[]=array('id' => 7, 'text'=>TEXT_PRODUCTS_ID);
								  $orders_search[]=array('id' => 8, 'text'=>TEXT_DATE_ID);

								  //end new code
								  echo TEXT_SEARCH . '&nbsp;'. tep_draw_input_field('psearch','','onkeyup="javascript:check_key(event)"').
								  '&nbsp;'.TEXT_SEARCH_IN.
								   tep_draw_pull_down_menu('filter_search', array_merge(array(array('id' => '1', 'text' => "Order ID")), $orders_search), '','').'
								  &nbsp;
								  <a href="javascript:void(0)" onClick="javascript:doOrderSearch(\'\');">' . tep_image(DIR_WS_IMAGES . 'icons/bar_search.gif',IMAGE_SEARCH,'','','align=absmiddle') . '</a>
								  &nbsp;&nbsp;'. 'Status ' . ' ' . tep_draw_pull_down_menu('filter_status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', "onChange=javascript:doFilterOrders();");?>
								  </td>
								  <td>
								  <table border="0" cellpadding="0" cellspacing="5" width="100%"><tr><td>
								    <div class="dataTableHeadingContent"><?php echo DELETE_ALL_ORDERS; ?>
								     <input type="checkbox" onChange="showHide()" /></div>
								</td></tr>
								</table>
								</td>
							</tr>
						</table>
							<?php //delete all orders?>
							
							<table>
							<tr>
								<td id="wait" class="main" style="display:none">
									<?php echo TEXT_DELETE_ORDERS_WAIT;?>
								</td>
								<td id="showme" class="main" style="display: none" >
									<table bgcolor="#FDEDED">
										<tr class="dataTableHeadingRow">
										   <td class="main" >
											   <?php echo TEXT_DELETE_ORDERS_TYPE; ?>
											</td>
											<td class="main" >
												<?php echo TEXT_DELETE_ORDERS_TO_FROM; ?>
											</td>
											<td class="main" >
												<?php echo TEXT_DELETE_ORDERS_PASS; ?>
											</td>
											<td>
											</td>										
										</tr>
										<tr>
											<td class="main" >
								   <form name="myForm" id="myForm" action=""  method="post"> 
									
									<?php echo TEXT_DELETE_ALL_ORDERS; ?> &nbsp;&nbsp;
									   		</td>
											<td class="main" >
												-- <?php echo TEXT_DELETE_ALL_ORDERS; ?> --
											</td>
											<td class="main" >
									<input name="delete_all" id="delete_all" size="5" type="text" oninput="delete_manage('delete_all_submit','nil', 'delete_all' )" > &nbsp;&nbsp;
											</td>
											<td class="main" >
									<input type="submit" value="Delete" id="delete_all_submit" disabled onclick="validateMyForm('delete_all')" >
											</td>
										</tr>
										<tr>
										 <td class="main" colspan="4"><strong>OR</strong></td>
										</tr>
										<tr>
											<td class="main" >
											<?php echo TEXT_DELETE_UP_TO_ORDERS; ?> &nbsp;&nbsp;
											</td>
											<td class="main" >
									<input name="delete_up" id="delete_up" oninput="delete_manage('delete_up_submit', 'delete_up' , 'delete_up_key' )"  type="number" min="1" step="1"> &nbsp;&nbsp;
											</td>
											<td class="main" >
									<input name="delete_up_key" id="delete_up_key" size="5" type="text" oninput="delete_manage('delete_up_submit', 'delete_up' , 'delete_up_key' )" > 
											</td>
												<td class="main" >
									<input type="submit" value="Delete" id="delete_up_submit" disabled onclick="validateMyForm('delete_up')" >
											</td>
										<tr>
										 <td class="main"  colspan="4"><strong>OR</strong></td>
										</tr>
										<tr>
											<td class="main" >
											<?php echo TEXT_DELETE_FROM_ORDERS; ?> &nbsp;&nbsp;
											</td>
											<td class="main" >
									<input name="delete_from" id="delete_from" oninput="delete_manage('delete_from_submit', 'delete_from','delete_from_key' )"  type="number" min="1" step="1"> &nbsp;&nbsp;
											</td>
											<td class="main" >
									<input name="delete_from_key" id="delete_from_key" size="5" type="text" oninput="delete_manage('delete_from_submit', 'delete_from' , 'delete_from_key' )" >   
											</td>
											<td class="main" >
									<input type="submit" value="Delete" id="delete_from_submit" disabled onclick="validateMyForm('delete_from')" >
										</td>
								</tr>
								<tr>
										<td class="main"  colspan="4"><strong>AND</strong></td>
								</tr>
								<tr>
									<td class="main"  colspan="4">
									  <?php echo DELETE_WITH_BACKUP;?>
										  <label>
										    <input type="radio" name="backup_select" value="yes" id="backup_select_0">
											<?php echo DELETE_WITH_BACKUP_YES;?>
										    </label>
										
										  &nbsp;&nbsp;
										  <label>
										    <input type="radio" name="backup_select" value="no" id="backup_select_1" checked="checked">
										    <?php echo DELETE_WITH_BACKUP_NO;?>
										  </label>
										  <br>
									  </td>
								</tr>
							</table>
									   
									<input type="hidden" value="wipe_all" name="wipe_all" />
			                        </form>
									<br/>
									<br/>
								</td>
							</tr>
						<?php //undelete all orders option
						   if($_SESSION['order_restore']== DELETE_ALL_ORDERS_KEYWORD){
						   	 //once only option
						   	  unset($_SESSION['order_restore']);
					     ?>
							<tr>
								<td id = "undelete0" style = "display: none">
									<?php echo TEXT_UNDO_DELETE_ORDERS_WAIT;?>								
								</td>
								<td id ="undelete1" class="main"  style="display:block">
								   <form name="myForm2" action="" onSubmit="return validateMyForm2()" method="post"> 
									<br/>
									 <?php echo TEXT_ALL_ORDERS_DELETED; ?>&nbsp;&nbsp;
									<input name="restore_all" id="restore_all" size="4" type="text"> &nbsp;&nbsp;
									<input type="submit" value="Restore">
									<input type="hidden" value="unwipe_all" name="unwipe_all" />
			                        </form>
								</td>
							</tr>
							
						  <?php  }?>
						
						</table>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
							
                                <td height="30"><input type="button" name="updateord" value="<?php 
								if(EMAIL_QUICK_ORDER_UPDATE=='true')
								{
									echo QUICK_ORDER_UPDATE_EMAIL;
								}else
								{
									echo QUICK_ORDER_UPDATE ;
								}
								
								?>" onClick="updateord();" ></td>
							</tr>
							</table>
						</td>
					</tr>
				</table>
				</td>
			</tr>
				<tr height="20" id="messageBoard" style="display:none">
					<td id="messageBoardText">
					</td>
				</tr>
				<tr>
					<td class="main" id="custord-1message">
					</td>
				</tr>
				<tr>
					<td >
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td id="custordtotalContentResult">
								<?php 
								
								$CUSTORD->doGetCustomersOrders($page);?>
								</td>
							</tr>
						</table>	
					</td>
				</tr>
				<tr style="display:none">
					<td id="ajaxLoadInfo"><div style="padding:5px 0px 5px 20px" class="main"><?php echo TEXT_LOADING . '&nbsp;' . tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div></td>
				</tr>
				<tr>
					<td id="ajaxLoadImage" style="display:none">
						<?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?>
					</td>
				</tr>
					</table>
					<div class="ajxMessageWindow" id="ajxLoad" style="display:none;width:400px;height:70px;"><span id="ajxLoadMessage"><?php echo TEXT_LOADING_DATA; ?></span><br><?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div>
			
			<!-- body_eof //-->
			<!-- footer //-->
				<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
			<!-- footer_eof //-->
		</body>
	</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>

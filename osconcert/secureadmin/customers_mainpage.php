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
	frequire($FSESSION->language.'/customers_mainpage.php',RLANG);
    frequire($FSESSION->language.'/customers_orders_refund.php',RLANG);
    frequire($FSESSION->language.'/'.FILENAME_WALLET_PAYMENT,RLANG);
    frequire($FSESSION->language.'/'.FILENAME_WALLET_CONFIRMATION,RLANG);
    frequire($FSESSION->language . '/' . FILENAME_MAIL,RLANG);
    require( DIR_WS_CLASSES . 'customerAccount.php');
    $customerAccount=new customerAccount();	
    require(DIR_WS_CLASSES . 'payment.php');
	frequire('currencies.php',RCLA);
	$currencies = new currencies();
	$dis_time_format="";
	if(defined('TIME_FORMAT')) $dis_time_format=TIME_FORMAT;
	$LANGUAGES=tep_get_languages();

	$CUSTORD=(new instance)->getTweakObject('display.customersDetail');
	$CUSTORD->pagination=true;
	checkAJAX('CUSTORD');
	$format=array('d-m-Y'=>'dd-mm-yyyy',
				  'm-d-Y'=>'mm-dd-yyyy',
				  'Y-m-d'=>'yyyy-mm-dd');

	$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));
	$jsData->VARS["page"]	=	array('lastAction'=>false,
											'opened'=>array(),
											'locked'=>false,
											'NUlanguages'=>$LANGUAGES,
											'imgPath'=>DIR_WS_IMAGES,
											"menu"=>array(),
											'link'=>tep_href_link('customers_mainpage.php'),
											'searchMode'=>false,
											'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY,
											'crypted'=>$ENCRYPTED,
											'alterRows'=>true,
                                             'formName'=>'customers',
                                            'dateFormat'=>$format[EVENTS_DATE_FORMAT],
                                            'editorLoaded'=>false,
                                            'NUeditorControls'=>array(),
                                            'formErrText'=>str_replace("\\n","--",JS_ERROR)
										);
	$jsData->VARS["page"]["template"]=array("ERROR_CUSTOMERS_GROUPS_NAME"=>ERROR_CUSTOMERS_GROUPS_NAME,
											"ERROR_CUSTOMERS_DISCOUNT"=>ERROR_CUSTOMERS_DISCOUNT,
											"ERROR_NUMERIC_VALUE"=>ERROR_NUMERIC_VALUE,
											"UPDATE_IMAGE"=>TEXT_UPDATE_IMAGE,
                                            "INFO_SEARCHING_DATA"=>INFO_SEARCHING_DATA,
											"INFO_LOADING_DATA"=>INFO_LOADING_DATA,
											"TEXT_LOADING"=>TEXT_LOADING_DATA,
											"UPDATE_ORDER"=>TEXT_UPDATE_ORDER,
											"PRD_DELETING"=>TEXT_PRD_DELETING,
                                            "ERROR_WALLET_AMOUNT"=>JS_WALLET_AMOUNT,
                                            "ERROR_PAYMENT"=>JS_PAYMENT_ERROR,
                                            "ERROR_MAIL_EMAIL"=>JS_EMAIL_ADDRESS_ERROR,
                                            "ERROR_MAIL_SUBJECT"=>JS_SUBJECT_ERROR
									);

	$jsData->VARS["page"]["NUmenuGroups"]=array("normal","update","updatepwd","wallet","walletconfirm","mail","mailsend");
	
   
   
    $display_customer_id=$FREQUEST->getvalue('rID','int',0);
    $page='';
	$count=array();

         if($display_customer_id>0)
         {
         $jsData->FUNCS[]="doDisplayAction({'id':" . $display_customer_id . ",'get':'Edit','result':doDisplayResult,'type':'custord','style':'boxRow','params':'rID=" . $display_customer_id . "'})";
		 
		 $customers_ids_query=tep_db_query("select c.customers_id, a.entry_country_id,LTRIM(c.customers_lastname) as customers_lastname, LTRIM(c.customers_firstname) as customers_firstname,LTRIM(c.customers_email_address) as customers_email_address  from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id order by c.customers_id desc;");
		 $i=0;
          while($customers_ids_array=tep_db_fetch_array($customers_ids_query))
            {  
				$count_customers_ids_query=tep_db_query("select customers_id from ".TABLE_CUSTOMERS." where customers_id >=".$display_customer_id." and customers_id='".$customers_ids_array['customers_id']."'");
                while($count_orders_ids_array=tep_db_fetch_array($count_customers_ids_query))
                {
                if($count_orders_ids_array['customers_id']>0)$i++;
                }
            }       
	     $page=(int)($i/MAX_DISPLAY_SEARCH_RESULTS)+ (($i % MAX_DISPLAY_SEARCH_RESULTS)>0? 1 :0);
        }
	tep_get_last_access_file();

?>

<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
    <head>
        <meta
            http-equiv="Content-Type"
            content="text/html; charset=<?php echo CHARSET; ?>">
        <title><?php echo TITLE; ?></title>
        <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
        <script language="javascript" src="includes/menu.js"></script>
        <script language="javascript" src="includes/general.js"></script>
        <script language="javascript" src="includes/http.js"></script>
        <script language="javascript" src="includes/date-picker.js"></script>
        <script type="text/javascript" src="includes/aim.js"></script>
        <script type="text/javascript" src="includes/tweak/js/ajax.js"></script>
        <script type="text/javascript" src="includes/tweak/js/customers_mainpage.js"></script>
        <?php include('includes/password_strength.js.php');?>
        <?php
	require(DIR_WS_INCLUDES . 'tweak/' . HTML_EDITOR . '.php');
	textEditorLoadJS();

    if($FREQUEST->getvalue('command')=='new')
        $jsData->FUNCS[]="doDisplayAction({'id':-1,get:'Edit','result':doDisplayResult,'type':'custord','params':'rID=-1','style':'boxRow'})";
?>

        <style>
            .account {
                padding: 2px 2px 2px 4px;
            }
            .account .main {
                padding: 0 0 8px;
            }
            .account .main h2 {
                font-size: 13px;
                margin: 8px 0 4px;
                line-height: 15px;
            }
            .account .main h3 {
                font-size: 12px;
                float: left;
                width: 230px;
                font-weight: normal;
                margin: 0;
            }
            .account .main div {
                float: left;
            }
            .account .main span.required {
                color: #FF0000;
            }
            .account .main span.desc {
                color: #FF0000;
                font-size: 11px;
            }
        </style>
    </head>
    <body
        marginwidth="0"
        marginheight="0"
        topmargin="0"
        leftmargin="0"
        bgcolor="#FFFFFF"
        onload="javascript:pageLoaded();">
        <!-- header //-->
        <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
        <!-- header_eof //-->
        <!-- body //-->
        <table border="0" width="100%" cellspacing="2" cellpadding="2">
            <!-- body_text //-->
            <tr>
                <td valign="top">
                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tr class="dataTableHeadingRow">
                            <td valign="top">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td class="main">
                                            <b><?php echo TEXT_CLIENTS;?></b>
                                        </td>

                                        <td width="400" class="main" align="right"><?php 
								  $orders_statuses = array();
								  $orders_status_array = array();
								  $orders_statuses=tep_get_orders_status();
								  echo TEXT_SEARCH . '&nbsp;'.tep_draw_input_field('psearch','','onkeyup="javascript:check_key(event)"').'&nbsp;<a href="javascript:void(0)" onClick="javascript:doCustomerSearch(\'\');">' . tep_image(DIR_WS_IMAGES . 'icons/bar_search.gif',IMAGE_SEARCH,'','','align=absmiddle') . '</a>&nbsp;&nbsp;' ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
						<tr>
                                        <td class="osconcert_message">
                                            <b><?php echo TEXT_OSCONCERT_MESSAGE;?></b>
                                        </td>
										</tr>
                    </table>
                </td>
            </tr>
            <tr height="20" id="messageBoard" style="display:none">
                <td id="messageBoardText"></td>
            </tr>
            <!-- <tr> <td class="main" id="custord-1message"></td> </tr>-->
            <tr>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">

                        <tr>
                            <td id="custordtotalContentResult"><?php $CUSTORD->doGetCustomersDetail($page);?></td>
                        </tr>

                    </table>
                </td>
            </tr>
            <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '10'); ?></td>
            </tr>
            <tr>
                <td>
                    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="margin-top:40px;">
                        <tr>
                            <td>
                                <table border="0" width="100%" cellpadding="5" cellspacing="0">
                                    <tr>
                                        <td>
                                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                <tr class="product_title">
                                                    <td colspan="2"><?php echo '<b>' . TEXT_CLIENTS . '</b>'; ?></td>
                                                </tr>
                                                
                                                <tr>
                                                    <td valign="top" width="7%">
                                                        <table border="0" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td height="74px" width="59px" align="center" valign="top">
																<?php echo tep_image(DIR_WS_IMAGES . 'categories/clients.png', Clients,'58','60');?></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td width="93%" valign="top">
                                                        <table
                                                            cellpadding="1"
                                                            cellspacing="3"
                                                            border="0"
                                                            width="100%"
                                                            class="info_content">
                                                            <tr>
                                                                <td class="info_content" valign="top">
                                                                    <a
                                                                        href="javascript:doDisplayAction({'id':'-1','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'custord','params':'rID=-1'});"><?php echo BOX_CLIENTS;?></a> <a href="customer_export.php">(Export)</a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td id="cust_infos"></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table cellpadding="0" cellspacing="0" border="0" width="100%"  style="display:none">
                                                <tr class="product_title">
                                                    <td colspan="2"><?php echo '<b>' . TEXT_ORDERS . '</b>'; ?></td>
                                                </tr>
                                                
                                                <tr>
                                                    <td valign="top" width="7%">
                                                        <table border="0" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td height="74px" width="59px" align="center" valign="top"><?php echo tep_image(DIR_WS_IMAGES . 'categories/orders.png', Orders,'58','60');?></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td width="93%" valign="top">
                                                        <table
                                                            cellpadding="1"
                                                            cellspacing="3"
                                                            border="0"
                                                            width="100%"
                                                            class="info_content">
                                                            <tr>
                                                                <td class="info_content" valign="top"><?php echo '<a href="'. tep_href_link(FILENAME_CREATE_ORDER_NEW,'top=1').'">'. BOX_ORDERS . '</a>';?></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <!--This table hidden-->
                                            <table
                                                cellpadding="0"
                                                cellspacing="0"
                                                border="0"
                                                width="100%"
                                                style="display:none">
                                                <tr class="product_title">
                                                    <td colspan="2">
                                                        <b><?php echo EXPORT_CUSTOMERS; ?></b>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td valign="top" width="7%">
                                                        <table border="0" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td height="74px" width="59px" align="center" valign="top">
                                                                    <p>
                                                                        <a class="button" href="export.php"><?php echo tep_image(DIR_WS_IMAGES . 'categories/export_customers.jpg', Orders,'58','60');?></a>
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td width="93%" valign="top">
                                                        <table
                                                            cellpadding="1"
                                                            cellspacing="3"
                                                            border="0"
                                                            width="100%"
                                                            class="info_content">
                                                            <tr>
                                                                <td class="info_content" valign="top">
                                                                    <p>
                                                                        <a href="export.php">Export Customers CSV</a>
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <tr>
                                                <td>
                                                    <!--This table hidden-->
                                                    <table
                                                        cellpadding="0"
                                                        cellspacing="0"
                                                        border="0"
                                                        width="100%"
                                                        style="display:none">
                                                        <tr class="product_title">
                                                            <td colspan="2">
                                                                <b><?php echo EXPORT_ORDERS; ?></b>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td valign="top" width="7%">
                                                                <table border="0" cellpadding="0" cellspacing="0">
                                                                    <tr>
                                                                        <td height="74px" width="59px" align="center" valign="top">
                                                                            <p>
                                                                                <a class="button" href="export2.php"><?php echo tep_image(DIR_WS_IMAGES . 'categories/export_orders.png', Orders,'58','60');?></a>
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td width="93%" valign="top">
                                                                <table
                                                                    cellpadding="1"
                                                                    cellspacing="3"
                                                                    border="0"
                                                                    width="100%"
                                                                    class="info_content">
                                                                    <tr>
                                                                        <td class="info_content" valign="top">
                                                                            <p>
                                                                                <a href="export2.php">Export Orders CSV</a>
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                        <tr class="product_title">
                                                            <td colspan="2"><?php echo '<b>' . TEXT_GROUPS . '</b>'; ?></td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td valign="top" width="7%">
                                                                <table border="0" cellpadding="0" cellspacing="0">
                                                                    <tr>
                                                                        <td height="74px" width="59px" align="center" valign="top"><?php echo tep_image(DIR_WS_IMAGES . 'categories/groups.png', Groups,'58','60');?></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td width="93%" valign="top">
                                                                <table
                                                                    cellpadding="1"
                                                                    cellspacing="3"
                                                                    border="0"
                                                                    width="100%"
                                                                    class="info_content">
                                                                    <tr>
                                                                        <td class="info_content" valign="top"><?php echo '<a href="'. tep_href_link(FILENAME_CUSTOMERS_GROUPS,'top=1').'">'. BOX_CUSTOMERS_GROUPS . '</a>';?></td>
                                                                    </tr>

                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="display:none">
                        <td id="ajaxLoadInfo">
                            <div style="padding:5px 0px 5px 20px" class="main"><?php echo TEXT_LOADING . '&nbsp;' . tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div>
                        </td>
                    </tr>
                    <tr>
                        <td id="ajaxLoadImage" style="display:none"><?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?>
                        </td>
                    </tr>
                </table>
				<div
                        class="ajxMessageWindow"
                        id="ajxLoad"
                        style="display:none;width:400px;height:70px;">
                        <span id="ajxLoadMessage"><?php echo TEXT_LOADING_DATA; ?></span><br>
                        <?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div>
                <!-- body_text_eof //-->
                <!-- body_eof //-->
                <!-- footer //-->
                <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
                <!-- footer_eof //-->
            </body>
        </html>
        <?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>
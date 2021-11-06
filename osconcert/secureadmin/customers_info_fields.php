<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare

    Released under the GNU General Public License
*/
    define('_FEXEC',1);
	require('includes/application_top.php');
	require(DIR_WS_INCLUDES.'/tweak/general.php');
	frequire('split_page_results_event.php',RCLA);
	frequire($FSESSION->language.'/customers_info_fields.php',RLANG);

    frequire('currencies.php',RCLA);
	$currencies = new currencies();
	$dis_time_format="";
	if(defined('TIME_FORMAT')) $dis_time_format=TIME_FORMAT;
	$LANGUAGES=tep_get_languages();


	$cusInfo=(new instance)->getTweakObject('display.customersInfoFields');
	$cusInfo->pagination=true;
	checkAJAX('cusInfo');

	$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));



	$jsData->VARS["page"]=array('lastAction'=>false,'opened'=>array(),'locked'=>false,'NUlanguages'=>tep_get_languages(),'imgPath'=>DIR_WS_IMAGES,"menu"=>array(),'link'=>tep_href_link(FILENAME_CUSTOMERS_INFO_FIELDS),'searchMode'=>false,'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY,'crypted'=>$ENCRYPTED,'alterRows'=>true,'extraParams'=>array());
	$jsData->VARS["page"]["template"]=array("ERR_UNIQUE_NAME"=>ERR_UNIQUE_NAME,
											"ERR_LABEL_TEXT"=>ERR_LABEL_TEXT,
											"ERR_ERROR_TEXT"=>ERR_ERROR_TEXT,
											"ERR_TEXT_BOX_SIZE"=>ERR_TEXT_BOX_SIZE,
											"ERR_TEXT_AREA_SIZE"=>ERR_TEXT_AREA_SIZE,
											"ERR_ALPHANUMERIC_TEXT"=>ERR_ALPHANUMERIC_TEXT,
											"ERR_DISPLAY_PAGE"=>ERR_DISPLAY_PAGE,
											"ERR_TEXT_AREA_LENGTH"=>ERR_TEXT_AREA_LENGTH,
											"ERR_TEXT_BOX_LENGTH"=>ERR_TEXT_BOX_LENGTH,
											"ERR_OPTION_VALUES_EMPTY"=>ERR_OPTION_VALUES_EMPTY,
											"ERR_TEXT_BOX_LENGTH_NUMERIC"=>ERR_TEXT_BOX_LENGTH_NUMERIC,
											"ERR_TEXT_AREA_LENGTH_NUMERIC"=>ERR_TEXT_AREA_LENGTH_NUMERIC,
											"TEXT_LOADING"=>TEXT_LOADING_DATA,
											"TEXT_SORTING_DATA"=>TEXT_SORTING_DATA,
										);

	$jsData->VARS["page"]["NUmenuGroups"]=array("normal","update");

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
<script type="text/javascript" src="includes/aim.js"></script>
<script type="text/javascript" src="includes/tweak/js/ajax.js"></script>
<script type="text/javascript" src="includes/tweak/js/customers_info_fields.js"></script>
</head>
<body marginwidth="0"  marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="javascript:pageLoaded();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<!-- body_text //-->
	<tr class="dataTableHeadingRow">
		<td valign="top">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td class="main">
				<b><?php echo TEXT_HEADING_TITLE;?></b>
			</td>
			<!-- <td class="main" align="right">
			<?php
				for ($icnt=MAX_DISPLAY_SEARCH_RESULTS,$n=MAX_DISPLAY_SEARCH_RESULTS*5;$icnt<=$n;$icnt+=MAX_DISPLAY_SEARCH_RESULTS){
					$pg_rows[]=array('id'=>$icnt,'text'=>$icnt);
				}
				$pg_rows[]=array('id'=>-1,'text'=>TEXT_ALL);
				echo TEXT_SHOW . '&nbsp; &nbsp;' . tep_draw_pull_down_menu('totalRows',$pg_rows,$FSESSION->displayRowsCnt,'onChange="javascript:doPageAction({id:-1,type:\'cusInfo\',get:\'Items\',closePrev:true,pageNav:true,result:doTotalResult,params:\'rID=-1&rowsCnt=\'+this.value,message:page.template[\'LOADING_DATA\']});"');
			?>
			</td> -->
		</tr>

		</table>
		</td>
	</tr>


	<tr height="20" id="messageBoard" style="display:none">
		<td id="messageBoardText">
		</td>
	</tr>
	<tr>
		<td class="main" id="cusInfo-1message">
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
			</tr>
			<tr>
				<td id="cusInfototalContentResult">
					<?php $cusInfo->doItems();?>
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
	<div class="ajxMessageWindow" id="ajxLoad" style="display:none;width:400px;height:70px;"><span id="ajxLoadMessage">Loading...</span><br><?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div>
</table>
<!-- body_text_eof //-->
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>

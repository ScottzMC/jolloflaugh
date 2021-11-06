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
	frequire($FSESSION->language.'/sales_maker.php',RLANG);
	
	$SALESMAKER = (new instance)->getTweakObject('display.salesMaker');
	$SALESMAKER->pagination=true;
	checkAJAX('SALESMAKER');
	
	$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));
	
	$jsData->VARS["page"]=array(
		'lastAction'=>false,
		'opened'=>array(),
		'locked'=>false,
		'NUlanguages'=>$LANGUAGES,
		'imgPath'=>DIR_WS_IMAGES,
		"menu"=>array(),
		'link'=>tep_href_link('sales_maker.php'),
		'searchMode'=>false,
		'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY,
		'crypted'=>$ENCRYPTED,
		'alterRows'=>true
	);
	
	$jsData->VARS["page"]["template"] = array(
		"ERR_SOURCES_NAME"=>ERR_SOURCES_NAME,
		"ERR_CHOICE_TEXT"=>ERR_CHOICE_TEXT,
		"ERR_CHOICE_WARNING"=>ERR_CHOICE_WARNING,
		"ERR_INVALID_DEDUCTION"=>ERR_INVALID_DEDUCTION,
		"ERR_INVALID_PRODUCTS_RANGE_FROM"=>ERR_INVALID_PRODUCTS_RANGE_FROM,
		"ERR_INVALID_PRODUCTS_RANGE_TO"=>ERR_INVALID_PRODUCTS_RANGE_TO,
		"ERR_INVALID_START_DATE"=>ERR_INVALID_START_DATE,
		"ERR_INVALID_END_DATE"=>ERR_INVALID_END_DATE,
		"EVENTS_DATE_FORMAT"=>EVENTS_DATE_FORMAT,
		"ERR_START_DATE"=>ERR_START_DATE,
		"COPY_NAME_REQUIRED"=>COPY_NAME_REQUIRED,
		"UPDATE_DATA"=>TEXT_UPDATE_DATA,
		"TEXT_LOADING"=>TEXT_LOADING_DATA,
		"UPDATE_ORDER"=>TEXT_UPDATE_ORDER,
		"PRD_DELETING"=>TEXT_PRD_DELETING
	);
	
	$jsData->VARS["page"]["NUmenuGroups"] = array("normal","update");
	
	tep_get_last_access_file();
	
?><!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
	<title><?php echo TITLE; ?></title>
	<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
	<script type="text/javascript" src="includes/general.js"></script>
        
	<script type="text/javascript" src="includes/http.js"></script>
	<script type="text/javascript" src="includes/aim.js"></script>
	<script type="text/javascript" src="includes/tweak/js/ajax.js"></script>
	
	<script type="text/javascript" src="includes/tweak/js/sales_maker.js"></script>
        
	<?php include('includes/date_format_js.php'); ?>

</head>
<body marginwidth="0"  marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="javascript:pageLoaded();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<link href="includes/jquery-ui.css" rel="stylesheet">
<script src="includes/jquery-1.10.2.js"></script>
<script src="includes/jquery-ui.js"></script>

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<!-- body_text //-->
	<tr class="dataTableHeadingRow">
		<td valign="top">
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
				<td class="main">
					<b><?php echo HEADING_TITLE;?></b>
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
		<td class="main" id="salmake-1message">
		</td>
	</tr>
	<tr>
		<td>
		<?php
		if(SHOW_OSCONCERT_HELP=='yes')
		{
		?>
		<div class="osconcert_message"><?php echo TEXT_IMPORTANT; ?></div>
		<?php
		}
		?>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td id="salmaketotalContentResult">
					<?php $SALESMAKER->doItems();?>
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
	<form name="fileUpload" id="fileUpload" action="sales_maker.php?AJX_CMD=GL_ImageUpload" method="post" enctype="multipart/form-data" style="display:none;">
		<input type="hidden" name="image_list" id="image_list" value="">
		<input type="hidden" name="image_resize" value="1">
	</form>
	<div class="ajxMessageWindow" id="ajxLoad" style="display:none;width:400px;height:70px;"><span id="ajxLoadMessage">Loading...</span><br><?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div>
</table>

<script language="JavaScript">
    
jQuery('body').on('focus',"#start", function(){
    jQuery(this).datepicker(
        {
    changeMonth: true,
    changeYear: true,
    dateFormat: '<?php $_array=array('d','m','Y');  $replace_array=array('dd','mm','yy'); echo $date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>',
    onClose: function( selectedDate ) {
                        $( "#end" ).datepicker( "option", "minDate", selectedDate );
                }
}
            );
});

jQuery('body').on('focus',"#end", function(){
    jQuery(this).datepicker(
        {
    changeMonth: true,
    changeYear: true,
    dateFormat: '<?php $_array=array('d','m','Y');  $replace_array=array('dd','mm','yy'); echo $date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>',
    onClose: function( selectedDate ) {
                        $( "#start" ).datepicker( "option", "maxDate", selectedDate );
                }
}
            );
});

function callstrt(){
    //alert("I am ");
   // console.log("Here");
    

jQuery( "#start" ).datepicker(
{
    changeMonth: true,
    changeYear: true,
    dateFormat: '<?php $_array=array('d','m','Y');  $replace_array=array('dd','mm','yy'); echo $date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>',
    onClose: function( selectedDate ) {
                        $( "#end" ).datepicker( "option", "minDate", selectedDate );
                }
}
);

}

function callenddt(){
   // alert("I am ");
    //$( "#txt_start_date" ).datepicker();
    jQuery( "#end" ).datepicker(
    {
        changeMonth: true,
        changeYear: true,
        dateFormat: '<?php $_array=array('d','m','Y');  $replace_array=array('dd','mm','yy'); echo $date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>',
        onClose: function( selectedDate ) {
                           $( "#start" ).datepicker( "option", "maxDate", selectedDate );
                    }
    }
    );

}
                                                          
  </script>

<!-- body_text_eof //-->
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>

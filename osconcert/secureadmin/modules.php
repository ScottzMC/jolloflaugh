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
	frequire($FSESSION->language.'/modules.php',RLANG);
	$MODULES=(new instance)->getTweakObject('display.modulesMainpage');
	$MODULES->pagination=true;
	checkAJAX('MODULES');	
	$set=$FREQUEST->getvalue('set'); 
	$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));
	$jsData->VARS["page"]=array('lastAction'=>false,'set'=>$set,'opened'=>array(),'locked'=>false,'NUlanguages'=>$LANGUAGES,'imgPath'=>DIR_WS_IMAGES,"menu"=>array(),'link'=>tep_href_link('modules.php'),'searchMode'=>false,'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY,'crypted'=>$ENCRYPTED,'alterRows'=>true);
	$jsData->VARS["page"]["template"]=array("ERROR_CUSTOMERS_GROUPS_NAME"=>ERROR_CUSTOMERS_GROUPS_NAME,
											"ERROR_CUSTOMERS_DISCOUNT"=>ERROR_CUSTOMERS_DISCOUNT,
											"ERROR_NUMERIC_VALUE"=>ERROR_NUMERIC_VALUE,
											"UPDATE_IMAGE"=>TEXT_UPDATE_IMAGE,
											"UPDATE_DATA"=>TEXT_UPDATE_DATA,
											"TEXT_LOADING"=>TEXT_LOADING_DATA,
											"UPDATE_ORDER"=>TEXT_UPDATE_ORDER,
											"PRD_DELETING"=>TEXT_PRD_DELETING
									);
	$jsData->VARS["page"]["NUmenuGroups"]=array("normal","update");
	tep_get_last_access_file();
	$mPath=$FREQUEST->getvalue('mPath');
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
<script type="text/javascript" src="includes/tweak/js/modules.js"></script>

</head>
<body marginwidth="0"  marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="javascript:pageLoaded();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<!-- body_text //-->
	<tr>
        <td width="100%">
		<table border="0" width="100%" cellspacing="0" cellpadding="0" style="display:none">
          <tr>
		  	<td width="50" nowrap="nowrap">
			<?php
					$popup=array(
								array("img"=>'icon_ascending.gif',"txt"=>TEXT_ASCENDING,"jlink"=>"doPageAction({'id':-1,'type':'modu','get':'sort','result':doTotalResult,'params':'mode=A&set=" . $set ."','message':'". INFO_SORTING_DATA."'})"),
								array("img"=>'icon_descending.gif',"txt"=>TEXT_DESCENDING,"jlink"=>"doPageAction({'id':-1,'type':'modu','get':'sort','result':doTotalResult,'params':'mode=D&set=" . $set ."','message':'". INFO_SORTING_DATA."'})"),
								);
					for ($icnt=0,$n=count($popup);$icnt<$n;$icnt++){
						echo '<a style="color:#000000;text-decoration:none" href="javascript:void(0)" onClick="javascript:return ' . $popup[$icnt]["jlink"] . ';">' . tep_image(DIR_WS_IMAGES . "template/" .$popup[$icnt]["img"],$popup[$icnt]["txt"],'','','align=absmiddle') . '</a>&nbsp;';
					}
				?>
			</td>
					<?php
					//echo $mPath;
		if(SHOW_OSCONCERT_HELP=='yes' && $mPath=='9_97_207')
		{
		?>
		<div class="osconcert_message"><?php echo TEXT_IMPORTANT; ?></div>
		<?php
		}else{
		?>	
		<div class="osconcert_message"><?php echo TEXT_IMPORTANT_PAYPAL; ?></div>
		<?php
		}
		?>
			<td class="dataTableHeadingContent"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table>
		</td>
      </tr>
	
	<tr height="20" id="messageBoard" style="display:none">
		<td id="messageBoardText"></td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td id="modutotalContentResult">
						<?php $MODULES->doModuleList();?>
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
	<form name="fileUpload" id="fileUpload" action="customers_groups.php?AJX_CMD=GL_ImageUpload" method="post" enctype="multipart/form-data" style="visibility:hidden;">
		<input type="hidden" name="image_list" id="image_list" value="">
		<input type="hidden" name="image_resize" value="1">
	</form>
	
</table>
<div class="ajxMessageWindow" id="ajxLoad" style="display:none;width:400px;height:70px;"><span id="ajxLoadMessage">Loading...</span><br><?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div>
<!-- body_text_eof //-->
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>
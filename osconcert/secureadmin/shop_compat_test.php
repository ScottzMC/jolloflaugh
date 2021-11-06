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
	tep_get_last_access_file();                                                                                                   
	$www_location = HTTP_SERVER . DIR_WS_CATALOG;
	
	$dir_fs_www_root=DIR_FS_CATALOG;
	
  	$last_test="";
  	if ($action=="testemail"){
		$send_email=$FREQUEST->postvalue("email_address");
		
		$subject = STORE_NAME . " Install - Test Email";
		$content = "Installer Compatibility Test Created on " . date('Y-m-d H:i:s') . "\n\n";
		$headers = "From: shop@osconcert.com\n";
		$headers .= "Return-Path: shop@osconcert.com\n";
		$headers .= "X-Mailer: osConcert \n"; 
		$sent=@mail($send_email, $subject, $content, $headers);
		if ($sent && trim($send_email)!=""){
			$last_test="<font color='blue'>Test Email Sent succesfully to ". $send_email ."</font>";
		} else {
			$last_test="<font color='red'>Failed to Send Test Email to '" . $send_email. "'</font>";
		}
	}
	$compat_link=tep_href_link("shop_compat_test.php","action=testemail");
	$current_link=tep_href_link("shop_compat_test.php");
	
	$error_image='<img src="images/setting_error.gif" border="0">';
	$warning_image='<img src="images/setting_warning.gif" border="0">';
	$notice_image='<img src="images/setting_notice.gif" border="0">';
	$right_image='<img src="images/setting_right.gif" border="0">';
	$require_load=true;
	$version_query=@tep_db_query("select version()");
	$version_result=@tep_db_fetch_array($version_query);
	$mysql_text.=$version_result[0] . ' </b>';
	$mysql_image=$right_image;
	$server_exists=true;
	   $include_path=";" . str_replace(":",";",ini_get("include_path")) . ";";
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
<tr> 
	<!-- body_text //-->
	<td valign="top" align="left">
	<table border="0" width="100%" cellspacing="0" cellpadding="5">
		<tr>
			<td>
			<?php require("compat_test_info.php");?>	
			</td>
		</tr>
	</table>
	</td>
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

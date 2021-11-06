<?php

/*

 osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 

Freeway eCommerce 
http://www.openfreeway.org
Copyright (c) 2007 ZacWare

Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
define('DISPLAYTTT', 'True');

  require("includes/application_top.php");

  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_SALEMAKER_INFO);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
</head>
<body>
<p class="main"><center><h1><?php echo HEADING_TITLE; ?><?php echo tep_draw_separator(); ?></h1></center></p>
<table width="90%" align="center">
<p class="main"><h3><?php echo SUBHEADING_TITLE; ?></h3></p>
<div class="main">
<?php echo INFO_TEXT; ?>
</div>
<p align="center" class="main"><a href="javascript:window.close();"><?php echo TEXT_CLOSE_WINDOW; ?></a></p>
<?php
if ( (defined('DISPLAYTTT')) && (DISPLAYTTT == 'True') ) {
?>
<center><?php echo tep_draw_separator('pixel_trans.gif', '4', '1') . tep_image(DIR_WS_IMAGES . 'thinktank.jpg'); ?></center>
<?php
} else {
  echo '<br>';
}
?>
</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>

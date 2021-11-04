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
  require('includes/application_top.php');
  tep_get_last_access_file();
  // if (TEMP_STORE_OPTIONS == 'true')
  // {
    // tep_redirect(tep_href_link("disable_options.php", ''));
  // }

  $system = tep_get_system_information();
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="3">
              <tr>
                <td class="smallText"><b><?php echo TITLE_SERVER_HOST; ?></b></td>
                <td class="smallText"><?php echo $system['host'] . ' (' . $system['ip'] . ')'; ?></td>
                <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE_HOST; ?></b></td>
                <td class="smallText"><?php echo $system['db_server'] . ' (' . $system['db_ip'] . ')'; ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TITLE_SERVER_OS; ?></b></td>
                <td class="smallText"><?php echo $system['system'] . ' ' . $system['kernel']; ?></td>
                <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE; ?></b></td>
                <td class="smallText"><?php echo $system['db_version']; echo mysqli_get_server_version(); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TITLE_SERVER_DATE; ?></b></td>
                <td class="smallText"><?php echo $system['date']; ?></td>
                <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE_DATE; ?></b></td>
                <td class="smallText"><?php echo $system['db_date']; ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TITLE_SERVER_UP_TIME; ?></b></td>
                <td colspan="3" class="smallText"><?php echo $system['uptime']; ?></td>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TITLE_HTTP_SERVER; ?></b></td>
                <td colspan="3" class="smallText"><?php echo $system['http_server']; ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TITLE_PHP_VERSION; ?></b></td>
                <td colspan="3" class="smallText"><?php echo $system['php'] . ' (' . TITLE_ZEND_VERSION . ' ' . $system['zend'] . ')'; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php
  if (function_exists('ob_start')) {
?>
<style type="text/css">
body, td, th {font-family: sans-serif; font-size: 10px;}
.p {text-align: left;}
.e {background-color: #ccccff; font-weight: bold;}
.h {background-color: #9999cc; font-weight: bold;}
.v {background-color: #cccccc;}
i {color: #666666;}
hr {display: none;}
</style>
<?php
    ob_start();
    phpinfo();
    $phpinfo = ob_get_contents();
    ob_end_clean();

//    $phpinfo = str_replace('border: 1px', '', $phpinfo);
//    ereg('<body>(.*)</body>', $phpinfo, $regs);
//    echo '<table border="1" cellpadding="3" width="600" style="border: 0px; border-color: #000000;">' .
//         '  <tr><td><h1 class="p"> ' . PROJECT_VERSION . '</h1></td>' . 
//         '  </tr>' .
//         '</table>';
//    echo $regs[1];
	define('PROJECT_VERSION', 'osConcert');
	$phpinfo = str_replace('border: 1px', '', $phpinfo);
    preg_match('/<body>(.*)<\/body>/is', $phpinfo, $regs);
    echo '<table>' .
         '  <tr><td><a href="https://www.osconcert.com"><img border="0" src="images/osconcert.png" title="' . PROJECT_VERSION . '" /></a><h1 class="p"> ' . PROJECT_VERSION . '</h1></td>' .
         '  </tr>' .
         '</table>';
    echo $regs[1];
  } else {
    phpinfo();
  }
?>
        </td>
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

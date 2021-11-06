<?php
/*
  $Id: barcodes_export.php adapted from customers export,v 1.5 2002/03/08 22:10:08 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
define( '_FEXEC', 1 );
  require('includes/application_top.php');
  if (!$_POST['submit']) {
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//DE">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
</head>
<body id="barcodes">
<!-- header //-->
<?php require(DIR_WS_INCLUDES.'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <!-- body_text //-->
    <td valign="top">
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_form('export', 'barcodes_export.php', '', 'post'); ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main"><?php echo TABLE_HEADING_BARCODES_EXPORT; ?></td>
              </tr>
				<tr>
				  <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
				</tr>
              <tr>
                <td class="main">
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent"><?php echo TITLE_ID; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_ORDERS_ID; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_PRODUCTS_ID; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_SHOWTIME; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_PRODUCTS_NAME; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_BARCODE; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_CREATED; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_SCANNED; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_SCANNED_DATE; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_LOCATION; ?></td>
                      <!--<td class="dataTableHeadingContent"><?php //echo TITLE_DATA; ?></td>-->
                    </tr>
<?php
    
		
		
		
		if (($FREQUEST->getvalue('page')!='') && ($FREQUEST->getvalue('page') > 1)) $rows = $FREQUEST->getvalue('page') * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
  $barcodes_query_raw = "select * 
    							   from orders_barcode 
    							   order by orders_id";
    $barcodes_split = new splitPageResults($FREQUEST->getvalue('page'), MAX_DISPLAY_SEARCH_RESULTS, $barcodes_query_raw, $barcodes_query_numrows);
    $barcodes_query = tep_db_query($barcodes_query_raw);
    while ($barcodes = tep_db_fetch_array($barcodes_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }

?>

                 <tr class="dataTableRow">
                	  <td class="dataTableContent"><b><?php echo $barcodes['barcode_id']; ?></b></td>
                      <td class="dataTableContent"><?php echo $barcodes['orders_id']; ?></td>
                      <td class="dataTableContent"><?php echo $barcodes['products_id']; ?></td>
                      <td class="dataTableContent"><?php echo $barcodes['showtime']; ?></td>
                      <td class="dataTableContent"><?php echo $barcodes['products_name']; ?></td>
                      <td class="dataTableContent"><?php echo $barcodes['barcode']; ?></td>
                      <td class="dataTableContent"><?php echo $barcodes['created']; ?></td>
                      <td class="dataTableContent"><?php echo $barcodes['scanned']; ?></td>
                      <td class="dataTableContent"><?php echo $barcodes['scanned_date']; ?></td>
                      <td class="dataTableContent"><?php echo $barcodes['location']; ?></td>
                      <!--<td class="dataTableContent"><?php //echo $barcodes['data']; ?></td>-->
                    </tr>
<?php
    }
?>
                  </table>
                  </td>
              </tr>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                 <tr>
                <td class="smallText" valign="top"><?php echo $barcodes_split->display_count($barcodes_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $FREQUEST->getvalue('page'), TEXT_DISPLAY_NUMBER_OF_BARCODES); ?></td>
                <td class="smallText" align="right"><?php echo $barcodes_split->display_links($barcodes_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $FREQUEST->getvalue('page')); ?>&nbsp;</td>
              </tr>
              <tr>
              <td class="smallText" ><?php echo TEXT_SEPARATOR; ?><input name="separator" type="text" value="\t" size="3">&nbsp;&nbsp;<input type="submit" value="Export" name="submit"></td>
              </tr>
            </table>
            </form>
          </td>
        </tr>
      </table></td>
  </tr>
</table>

<?php
}
else
{
	if($HTTP_POST_VARS['separator']!="") {
	$sep=stripcslashes($HTTP_POST_VARS['separator']);
	}else{ 
	$sep=",";
	}
	$sep= str_replace(',', ",", $sep);
	
	$contents="barcode_id".$sep."orders_id".$sep."products_id".$sep."showtime".$sep."showtime".$sep."products_name".$sep."barcode".$sep."created".$sep."scanned".$sep."scanned_date".$sep."location\n";
	$barcodes_query_raw = "select * 
    							   from orders_barcode 
    							   order by orders_id";
    $barcodes_query = tep_db_query($barcodes_query_raw);
    while ($row = tep_db_fetch_array($barcodes_query)) {

		$contents.=$row['barcode_id'].$sep;
		$contents.=$row['orders_id'].$sep;
		$contents.=$row['products_id'].$sep;
		$contents.=$row['showtime'].$sep;
		$contents.=$row['products_name'].$sep;
		$contents.=$row['barcode'].$sep;
		$contents.=$row['created'].$sep;
		$contents.=$row['scanned'].$sep;
		$contents.=$row['scanned_date'].$sep;
		$contents.=$row['location']."\n";
	}
	/*Header("Content-Disposition: attachment; filename=export.txt");
	print $contents;*/
	 header("Content-Type: application/force-download\n");
                header("Content-disposition: attachment; filename=barcodes_export_" . date("Ymd") . ".csv");
				header("Pragma: no-cache");
				header("Expires: 0");
				echo $contents;
				die();
}
?>
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
<?php
/*

  

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
  http://www.openfreeway.org

Copyright 2007 ZacWare Pty. Ltd 
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
  require('includes/application_top.php');
  tep_get_last_access_file();                                                                                                  
  $action = $FREQUEST->getvalue('action');
  require(DIR_WS_CLASSES . 'split_page_results_event.php');
  if ($action == 'display_other') {
    $referrals_query_raw = "select count(ci.customers_info_source_id) as no_referrals, so.sources_other_name as sources_name from " . TABLE_CUSTOMERS_INFO . " ci, " . TABLE_SOURCES_OTHER . " so where ci.customers_info_source_id = '9999' group by so.sources_other_name,customers_info_source_id order by so.sources_other_name DESC";
  } else {
    $referrals_query_raw = "select count(ci.customers_info_source_id) as no_referrals, s.sources_name, s.sources_id from " . TABLE_CUSTOMERS_INFO . " ci LEFT JOIN " . TABLE_SOURCES . " s ON s.sources_id = ci.customers_info_source_id group by s.sources_id,sources_name order by ci.customers_info_source_id DESC";
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr> 
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <!--<td class="pageHeading"><?php //echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php //echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>!-->
          </tr>
        </table></td>
      </tr>
	  <tr ><td class="cell_bg_report_header">&nbsp;</td></tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingTitleRow">
                <td class="dataTableHeadingTitleContent"><?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingTitleContent"><?php echo TABLE_HEADING_REFERRALS; ?></td>
                <td class="dataTableHeadingTitleContent" align="center"><?php echo TABLE_HEADING_VIEWED; ?>&nbsp;</td>
              </tr>
			  <tr height="10">
			  </tr>
					  
			  
<?php
  if (($FREQUEST->getvalue('page')!='') && ($FREQUEST->getvalue('page') > 1)) $rows = $FREQUEST->getvalue('page') * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
  $rows = 0;
  $referrals_split = new splitPageResultsEvent($FREQUEST->getvalue('page'), 500, $referrals_query_raw, $referrals_query_numrows);
  $referrals_query = tep_db_query($referrals_query_raw);
  while ($referrals = tep_db_fetch_array($referrals_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
	}
    if ( tep_not_null($referrals['sources_name']) ) {
?>
              <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)">
<?php
    } else {
?>
              <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_STATS_REFERRAL_SOURCES, 'action=display_other'); ?>'">
<?php
    }
?>
                <td class="dataTableContent"><?php echo $rows; ?>.</td>
                <td class="dataTableContent"><?php echo (tep_not_null($referrals['sources_name']) ? $referrals['sources_name'] : TEXT_OTHER );?>&nbsp;</td>
                <td class="dataTableContent" align="center"><?php 
				if ($action == 'display_other') {
					echo '';
				}else{
					
				echo $referrals['no_referrals']; 
				}
				
				?>&nbsp;</td>

				
              </tr>
<?php
  }
?>
            </table></td>
          </tr>
		  <?php if($referrals_query_numrows>0){?>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $referrals_split->display_count($referrals_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $FREQUEST->getvalue('page'), TEXT_DISPLAY_NUMBER_OF_REFERRALS); ?></td>
                <td class="smallText" align="right"><?php echo $referrals_split->display_links($referrals_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $FREQUEST->getvalue('page')); ?></td>
              </tr>
            </table></td>
          </tr>
		  <?php 
		  	}
			else{
					echo '<tr><td class="main" align="center">' . TEXT_NO_RECORDS_FOUND . '</td></tr>';
			}
		  ?>
        </table></td>
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

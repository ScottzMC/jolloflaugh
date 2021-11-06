<?php
/*
  $Id: customer_export.php,v 1.5 2002/03/08 22:10:08 hpdl Exp $

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
<body id="customers">
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
          <td><?php echo tep_draw_form('export', 'customer_export.php', '', 'post'); ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main"><?php echo TABLE_HEADING_CUSTOMER_EXPORT; ?></td>
              </tr>
				<tr>
				  <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
				</tr>
              <tr>
                <td class="main">
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent"><?php echo TITLE_ID; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_FIRSTNAME; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_LASTNAME; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_EMAIL; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_GENDER; ?></td>
                    <!--  <td class="dataTableHeadingContent"><?php //echo TITLE_DOB; ?></td>-->
                      <td class="dataTableHeadingContent"><?php echo TITLE_COMPANY; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_STREET_ADDRESS; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_POSTCODE; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_CITY; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_STATE; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_SUBURB; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_COUNTRY; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_PHONE; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TITLE_FAX; ?></td>
                    </tr>
<?php
    
		
		
		
		if (($FREQUEST->getvalue('page')!='') && ($FREQUEST->getvalue('page') > 1)) $rows = $FREQUEST->getvalue('page') * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
  $customers_query_raw = "select c.customers_id,
    							  c.customers_lastname,
    							  c.customers_firstname,
    							  c.customers_email_address,
    							  c.customers_gender,
    							  c.customers_dob,
    							  c.customers_telephone,
    							  c.customers_fax,
    							  a.entry_company,
    							  a.entry_street_address,
    							  a.entry_postcode,
    							  a.entry_city,
    							  a.entry_state,
    							  a.entry_suburb,
    							  co.countries_name
    							   from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id
    							   left join " . TABLE_COUNTRIES . " co on co.countries_id = a.entry_country_id
    							   order by c.customers_lastname, c.customers_firstname";
    $customers_split = new splitPageResults($FREQUEST->getvalue('page'), MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
    $customers_query = tep_db_query($customers_query_raw);
    while ($customers = tep_db_fetch_array($customers_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }

?>

                 <tr class="dataTableRow">
                	  <td class="dataTableContent"><b><?php echo $customers['customers_id']; ?></b></td>
                      <td class="dataTableContent"><?php echo $customers['customers_firstname']; ?></td>
                      <td class="dataTableContent"><?php echo $customers['customers_lastname']; ?></td>
                      <td class="dataTableContent"><?php echo $customers['customers_email_address']; ?></td>
                      <td class="dataTableContent"><?php echo $customers['customers_gender']; ?></td>
                      <!--<td class="dataTableContent"><?php //echo tep_date_short($customers['customers_dob']); ?></td>-->
                      <td class="dataTableContent"><?php echo $customers['entry_company']; ?></td>
                      <td class="dataTableContent"><?php echo $customers['entry_street_address']; ?></td>
                      <td class="dataTableContent"><?php echo $customers['entry_postcode']; ?></td>
                      <td class="dataTableContent"><?php echo $customers['entry_city']; ?></td>
                      <td class="dataTableContent"><?php echo $customers['entry_state']; ?></td>
                      <td class="dataTableContent"><?php echo $customers['entry_suburb']; ?></td>
                      <td class="dataTableContent"><?php echo $customers['countries_name']; ?></td>
                      <td class="dataTableContent"><?php echo $customers['customers_telephone']; ?></td>

                      <td class="dataTableContent"><?php echo $customers['customers_fax']; ?></td>
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
                <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $FREQUEST->getvalue('page'), TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $FREQUEST->getvalue('page')); ?>&nbsp;</td>
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
	$contents="customers_id".$sep."customers_lastname".$sep."customers_firstname".$sep."customers_email_address".$sep."customers_gender".$sep."customers_dob".$sep."entry_company".$sep."entry_street_address".$sep."entry_postcode".$sep."entry_city".$sep."entry_state".$sep."entry_suburb".$sep."countries_name".$sep."customers_telephone".$sep."customers_fax\n";
	$customers_query_raw = "select c.customers_id,
    							  c.customers_lastname,
    							  c.customers_firstname,
    							  c.customers_email_address,
    							  c.customers_gender,
    							  c.customers_dob,
    							  c.customers_telephone,
    							  c.customers_fax,
    							  a.entry_company,
    							  a.entry_street_address,
    							  a.entry_postcode,
    							  a.entry_city,
    							  a.entry_state,
    							  a.entry_suburb,
    							  co.countries_name
    							   from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id
    							   left join " . TABLE_COUNTRIES . " co on co.countries_id = a.entry_country_id
    							   order by c.customers_lastname, c.customers_firstname";
    $customers_query = tep_db_query($customers_query_raw);
    while ($row = tep_db_fetch_array($customers_query)) {

		$contents.=$row['customers_id'].$sep;
		$contents.=$row['customers_lastname'].$sep;
		$contents.=$row['customers_firstname'].$sep;
		$contents.=$row['customers_email_address'].$sep;
		$contents.=$row['customers_gender'].$sep;
		//$contents.=tep_date_short($row['customers_dob']).$sep;
		$contents.=$row['entry_company'].$sep;
		$contents.=$row['entry_street_address'].$sep;
		$contents.=$row['entry_postcode'].$sep;
		$contents.=$row['entry_city'].$sep;
		$contents.=$row['entry_state'].$sep;
		$contents.=$row['entry_suburb'].$sep;
		$contents.=$row['countries_name'].$sep;
        $contents.=$row['customers_telephone'].$sep;
		$contents.=$row['customers_fax']."\n";
	}
	/*Header("Content-Disposition: attachment; filename=export.txt");
	print $contents;*/
	 header("Content-Type: application/force-download\n");
                header("Content-disposition: attachment; filename=customers_export_" . date("Ymd") . ".csv");
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
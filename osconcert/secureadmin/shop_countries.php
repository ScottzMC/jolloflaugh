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
   $action = $FREQUEST->getvalue('action');
   $cID=$FREQUEST->getvalue('cID','string','1');
   $page=$FREQUEST->getvalue('page','string','1');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
        $countries_name = $FREQUEST->postvalue('countries_name');
        $countries_iso_code_2 = $FREQUEST->postvalue('countries_iso_code_2');
        $countries_iso_code_3 = $FREQUEST->postvalue('countries_iso_code_3');
		$countries_code=$FREQUEST->postvalue('countries_code','string','0');
        $address_format_id = $FREQUEST->postvalue('address_format_id');

        tep_db_query("insert into " . TABLE_COUNTRIES . " (countries_name, countries_iso_code_2, countries_iso_code_3,country_code, address_format_id) values ('" . tep_db_input($countries_name) . "', '" . tep_db_input($countries_iso_code_2) . "', '" . tep_db_input($countries_iso_code_3) . "','" .tep_db_input($countries_code). "', '" . (int)$address_format_id . "')");

        tep_redirect(tep_href_link(FILENAME_COUNTRIES));
        break;
      case 'save':
        $countries_id = $FREQUEST->getvalue('cID');
        $countries_name = $FREQUEST->postvalue('countries_name');
        $countries_iso_code_2 = $FREQUEST->postvalue('countries_iso_code_2');
        $countries_iso_code_3 = $FREQUEST->postvalue('countries_iso_code_3');
		$countries_code=$FREQUEST->postvalue('countries_code');
        $address_format_id = $FREQUEST->postvalue('address_format_id');

        tep_db_query("update " . TABLE_COUNTRIES . " set countries_name = '" . tep_db_input($countries_name) . "', countries_iso_code_2 = '" . tep_db_input($countries_iso_code_2) . "', countries_iso_code_3 = '" . tep_db_input($countries_iso_code_3) ."',country_code='".tep_db_prepare_input($countries_code)."', address_format_id = '" . (int)$address_format_id . "' where countries_id = '" . (int)$countries_id . "'");
        tep_redirect(tep_href_link(FILENAME_COUNTRIES, 'page=' . $FREQUEST->getvalue('page') . (($FREQUEST->getvalue('search')!='')?'&search='. $search :''). '&cID=' . $countries_id));
        break;
      case 'deleteconfirm':
        $countries_id = $FREQUEST->getvalue('cID');

        tep_db_query("delete from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "'");

        tep_redirect(tep_href_link(FILENAME_COUNTRIES, 'page=' . $FREQUEST->getvalue('page').(($FREQUEST->getvalue('search')!='')?'&search='. $search: '')));
        break;
    }
  }
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
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr> 
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText" align="right">
					<?php
						echo tep_draw_form('search', FILENAME_COUNTRIES, '', 'get');
						echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search');
						echo '</form>';
					?>
                </td>
              </tr>
          <!--    <tr>
                <td class="smallText" align="right">
					<?php 
						/*echo tep_draw_form('goto', FILENAME_COUNTRIES, '', 'get');
						echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cID', tep_get_countries(TEXT_TOP), $current_category_id, 'onChange="this.form.submit();"');
						echo '</form>';*/
					?>
                </td>
              </tr>
            </table></td> 
          </tr>-->
        </table></td>
      </tr>
	  <tr><td colspan="2"><?php echo tep_draw_separator("pixel_trans.gif","1","10");?></td></tr>
      <tr>
        <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_COUNTRY_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center" colspan="2"><?php echo TABLE_HEADING_COUNTRY_CODES; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    if ($FREQUEST->getvalue('search')!='') {
      $search = $FREQUEST->getvalue('search');
  $countries_query_raw = "select countries_id, countries_name, countries_iso_code_2, countries_iso_code_3,country_code, address_format_id from " . TABLE_COUNTRIES . "  where countries_name like '%". $FREQUEST->getvalue('search'). "%' order by countries_name";
  } else {
	  $countries_query_raw = "select countries_id, countries_name, countries_iso_code_2, countries_iso_code_3,country_code, address_format_id from " . TABLE_COUNTRIES . " order by countries_name";
	}  
	//echo $countries_query_raw;
	
  $countries_split = new splitPageResults($FREQUEST->getvalue('page'), MAX_DISPLAY_SEARCH_RESULTS, $countries_query_raw, $countries_query_numrows);
  $countries_query = tep_db_query($countries_query_raw);
  while ($countries = tep_db_fetch_array($countries_query)) 
  {
    if ((
	($FREQUEST->getvalue('cID')=='') || 
	($FREQUEST->getvalue('cID')!='') && 
	($FREQUEST->getvalue('cID') == $countries['countries_id'])
	)
	&& !isset($cInfo) && (substr($action, 0, 3) != 'new')) 
	{
      $cInfo = new objectInfo($countries);
    }
	//print_r($cInfo);

    if (isset($cInfo) && is_object($cInfo) && ($countries['countries_id'] == $cInfo->countries_id)) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $FREQUEST->getvalue('page') .(($FREQUEST->getvalue('search')!='')?'&search=' . $search:''). '&cID=' . $cInfo->countries_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $FREQUEST->getvalue('page') .(($FREQUEST->getvalue('search')!='')?'&search=' . $search:''). '&cID=' . $countries['countries_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_ZONES, 'cPage='. $page. '&cID=' . $countries['countries_id']) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . $countries['countries_name'] . '</b>'; ?></td>
                <td class="dataTableContent" align="center" width="40"><?php echo $countries['countries_iso_code_2']; ?></td>
                <td class="dataTableContent" align="center" width="40"><?php echo $countries['countries_iso_code_3']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($countries['countries_id'] == $cInfo->countries_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $FREQUEST->getvalue('page') . '&cID=' . $countries['countries_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
	}
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $countries_split->display_count($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $FREQUEST->getvalue('page'), TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></td>
                    <td class="smallText" align="right"><?php echo $countries_split->display_links($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $FREQUEST->getvalue('page')); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php if((($FREQUEST->getvalue('search')!=''))) echo '<a href="' . tep_href_link(FILENAME_COUNTRIES) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; else echo '<a href="' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $FREQUEST->getvalue('page') . '&action=new') . '">' . tep_image_button('button_new_country.gif', IMAGE_NEW_COUNTRY) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_COUNTRY . '</b>');
	  $contents = array('form' => tep_draw_form('countries', FILENAME_COUNTRIES, 'page=' . $FREQUEST->getvalue('page') . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . tep_draw_input_field('countries_name'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . '<br>' . tep_draw_input_field('countries_iso_code_2'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_3 . '<br>' . tep_draw_input_field('countries_iso_code_3'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE . '<br>' . tep_draw_input_field('countries_code'));
	  $contents[] = array('text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . '<br>' . tep_draw_pull_down_menu('address_format_id', tep_get_address_formats()));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $FREQUEST->getvalue('page')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_COUNTRY . '</b>');

      $contents = array('form' => tep_draw_form('countries', FILENAME_COUNTRIES, 'page=' . $FREQUEST->getvalue('page') .(($FREQUEST->getvalue('search')!='')?'&search='. $search: '').'&cID=' . $cInfo->countries_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . tep_draw_input_field('countries_name', $cInfo->countries_name));
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . '<br>' . tep_draw_input_field('countries_iso_code_2', $cInfo->countries_iso_code_2));
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_3 . '<br>' . tep_draw_input_field('countries_iso_code_3', $cInfo->countries_iso_code_3));
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE . '<br>' . tep_draw_input_field('countries_code', $cInfo->country_code));
	  $contents[] = array('text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . '<br>' . tep_draw_pull_down_menu('address_format_id', tep_get_address_formats(), $cInfo->address_format_id));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $FREQUEST->getvalue('page') . (($FREQUEST->getvalue('search')!='')?'&search='. $search: '').  '&cID=' . $cInfo->countries_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_COUNTRY . '</b>');
      $contents = array('form' => tep_draw_form('countries', FILENAME_COUNTRIES, 'page=' . $FREQUEST->getvalue('page') .(($FREQUEST->getvalue('search')!='')?'&search='. $search: '').  '&cID=' . $cInfo->countries_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $cInfo->countries_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $FREQUEST->getvalue('page') .(($FREQUEST->getvalue('search')!='')?'&search='. $search: '').  '&cID=' . $cInfo->countries_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->countries_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $FREQUEST->getvalue('page') .(($FREQUEST->getvalue('search')!='')?'&search='. $search: ''). '&cID=' . $cInfo->countries_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $FREQUEST->getvalue('page') .(($FREQUEST->getvalue('search')!='')?'&search='. $search: '').  '&cID=' . $cInfo->countries_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . $cInfo->countries_name);
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . ' ' . $cInfo->countries_iso_code_2);
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_3 . ' ' . $cInfo->countries_iso_code_3);
		$contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE . ' ' . $cInfo->country_code);
        $contents[] = array('text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . ' ' . $cInfo->address_format_id);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
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

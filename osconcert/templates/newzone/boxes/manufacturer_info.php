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
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
 if ($FREQUEST->getvalue('products_id')!='')
 {
  $manufacturer_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image from " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS . " p  where p.products_id = '" . (int)$FREQUEST->getvalue('products_id') . "' and p.manufacturers_id = m.manufacturers_id");
  if (tep_db_num_rows($manufacturer_query)) 
  {
    $manufacturer = tep_db_fetch_array($manufacturer_query);

    $manufacturer_url_query = tep_db_query("select manufacturers_url from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturer['manufacturers_id'] . "'");
    $manufacturer_url_values = tep_db_fetch_array($manufacturer_url_query);
    $has_manufacturer_url = ($manufacturer_url_values['manufacturers_url']) ? 'true' : 'false';
?>
<!-- manufacturer_info //-->

<?php
	if(!defined('BOX_HEADING_MANUFACTURER_INFO'))define('BOX_HEADING_MANUFACTURER_INFO', 'Manufactures Info');

     $manufacturer_info_string = '<div align="center">' . tep_image(DIR_WS_IMAGES . $manufacturer['manufacturers_image'], $manufacturer['manufacturers_name'], 100, 88) . '</div><div>';
	 
    if ($has_manufacturer_url == 'true')
 	//$manufacturer_info_string .= '<tr><td valign="top" class="smalltext">-&nbsp;</td><td valign="top" class="smalltext"><a href="' . tep_href_link(FILENAME_REDIRECT, 'action=manufacturer&manufacturers_id=' . $manufacturer['manufacturers_id'], 'NONSSL') . '" target="_blank">' . sprintf(BOX_MANUFACTURER_INFO_HOMEPAGE, $manufacturer['manufacturers_name']) . '</a></td></tr>';

	$manufacturer_info_string.='<span>-&nbsp;</span><div class="smalltext"><a  target="_blank" href="http://' . $manufacturer_url_values['manufacturers_url'] . '">'. sprintf(BOX_MANUFACTURER_INFO_HOMEPAGE, $manufacturer['manufacturers_name']).'</a></div>';
    $manufacturer_info_string .= '<div class="smalltext">-&nbsp;</div><div class="smalltext"><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id'], 'NONSSL') . '">' . BOX_MANUFACTURER_INFO_OTHER_PRODUCTS . '</a></div></div>';

  
	echo '<div class="card box-shadow">';
	echo '<div class="card-header">';
	echo '<strong>';
	echo BOX_HEADING_MANUFACTURER_INFO;
	echo '</strong>';
	echo '</div>';
	echo '<div class="list-group">';
	echo $manufacturer_info_string;
	echo '</div>';
	echo '</div>';
	echo '<br class="clearfloat">';
}
?>

<!-- manufacturer_info_eof //-->
<?php
	}
?>
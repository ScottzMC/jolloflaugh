<?php
/*
 osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 action=update_template

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/
// Check to ensure this file is included in osConcert!
	defined('_FEXEC') or die();
	
if ($FSESSION->is_registered('customer_id')) 
{

  	if (substr(basename($PHP_SELF), 0, 8) != 'checkout') 
	{

?>
<!-- template_theme //-->
<?php
	if(!defined('BOX_HEADING_TEMPLATE_SELECT'))define('BOX_HEADING_TEMPLATE_SELECT', 'Select Template');

	$template_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . "  order by template_name");
 
// Display a drop-down
	$select_box = "<div style='padding:3px'>";
    $select_box .= '<select class="form-control" name="template" onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '" style="width: 100%">';
    if (MAX_THEME_LIST < 2) {
      $select_box .= '<option value="">' . PULL_DOWN_DEFAULT . '</option>';
    }
    while ($template_values = tep_db_fetch_array($template_query)) {
      $select_box .= '<option value="' . $template_values['template_name'] . '"';
      if ($FREQUEST->getvalue('template_id') == $template_values['template_id']) $select_box .= ' SELECTED';
      $select_box .= '>' . substr($template_values['template_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '</option>';
    }
    $select_box .= "</select>";
	$select_box .= "</div>";
					 
	echo '<div class="card box-shadow">';
	echo '<div class="card-header">';
	echo '<strong>';
	echo BOX_HEADING_TEMPLATE_SELECT;
	echo '</strong>';
	echo '</div>';
	echo '<div class="list-group">';
	echo '<form name="template" method="post" action="' . tep_href_link(FILENAME_DEFAULT, '&action=update_template', 'NONSSL') . '">'.$select_box.'</form>';
	echo '</div>';
	echo '</div>';
	echo '<br class="clearfloat">';
	}
}
?><!-- template_theme_eof //-->
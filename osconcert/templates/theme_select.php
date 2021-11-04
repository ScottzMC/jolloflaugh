<?php

// Check to ensure this file is included in osConcert!
	defined('_FEXEC') or die();

  	if (substr(basename($PHP_SELF), 0, 8) != 'checkout') 
	{

?>
<!-- template_theme //-->
<?php

	define('TEMPLATE_SELECT', 'Select Template');

	$template_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . "  WHERE active=1 order by template_name");
 
// Display a drop-down
	$select_box = "<div style='padding:3px'>";
    $select_box .= '<select class="form-control" name="template" onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '" style="width: 160px">';
    if (MAX_THEME_LIST < 4) 
	{
      $select_box .= '<option value="">' . TEMPLATE_SELECT . '</option>';
    }
    while ($template_values = tep_db_fetch_array($template_query)) 
	{
      $select_box .= '<option value="' . $template_values['template_name'] . '"';
      if ($FREQUEST->getvalue('template_id') == $template_values['template_id']) $select_box .= ' SELECTED';
      $select_box .= '>' . substr($template_values['template_name'], 0, 10) . '</option>';
    }
    $select_box .= "</select>";
	$select_box .= "</div>";
					 
	echo '<form name="template" method="post" action="' . tep_href_link(FILENAME_DEFAULT, '&action=update_template', 'NONSSL') . '">'.$select_box.'</form>';

	}
?><!-- template_theme_eof //-->
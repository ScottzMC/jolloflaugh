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

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//Site thema configuration
  $configuration_query = tep_db_query("select  configuration_value as template_selected from " . TABLE_CONFIGURATION . " where configuration_id = '10'");
  $configuration = tep_db_fetch_array($configuration_query);

	if (tep_not_null($configuration['template_selected'])) 
	{
	define('TEMPLATE_NAME', $configuration['template_selected']);
	define('TEMPLATE_STYLE', DIR_WS_TEMPLATES . TEMPLATE_NAME . "/css/style.css");
	}else{
	define('TEMPLATE_NAME', DEFAULT_TEMPLATE);
	define(TEMPLATE_STYLE, DIR_WS_TEMPLATES . DEFAULT_TEMPLATE . "/css/style.css");
	}
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

if ( file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/extra_html_output.php')) {
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/extra_html_output.php');
}
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

  $template_query = tep_db_query("select * from " . TABLE_TEMPLATE . " where template_name = '" . tep_db_input(TEMPLATE_NAME) . "'");
  $template = tep_db_fetch_array($template_query);

	define('TEMPLATE_ID', $template['template_id']);
	define('TEMPLATE_COLOR', $template['template_color']);
	define('DISPLAY_COLUMN_LEFT', $template['include_column_left']); 
	define('DISPLAY_COLUMN_RIGHT', $template['include_column_right']);
	define('SITE_WIDTH', $template['site_width']); 
	define('HEADER_HEIGHT', $template['header_height']); 
	define('BOX_WIDTH_LEFT', $template['box_width_left']); 
	define('HEADER_BANNER', $template['header_banner']);
	define('SHOW_CONTAINER_BORDER', $template['container_border']);
	define('SHOW_LANGUAGES_IN_HEADER', $template['languages_in_header']);
	define('SHOW_CART_IN_HEADER', $template['cart_in_header']);
	define('SHOW_CUSTOMER_GREETING', $template['customer_greeting']);
	define('SHOW_BREADCRUMB', $template['show_breadcrumb']);
	define('SHOW_HEADER_PANE', $template['show_header_pane']);
	define('SHOW_TOP_BAR', $template['show_topbar']);
	define('SHOW_LOGIN', $template['show_page_descriptions']);
	 // define('INCLUDE_MODULE_ONE', $template[module_one]);
	 // define('INCLUDE_MODULE_TWO', $template[module_two]);
	 // define('INCLUDE_MODULE_THREE', $template[module_three]);
	 // define('INCLUDE_MODULE_FOUR', $template[module_four]);
	 // define('INCLUDE_MODULE_FIVE', $template[module_five]);
	 // define('INCLUDE_MODULE_SIX', $template[module_six]);
 
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

//for templatebox
//if ($FREQUEST->getvalue('action')) {
//    switch ($FREQUEST->getvalue('action')) {
//      case 'update_template':
//
//      if ($template >= '1'){
//         $thema_template = tep_db_input($FREQUEST->postvalue('template'));
//           tep_db_query("update " . TABLE_CUSTOMERS . " set customers_selected_template = '$thema_template' where customers_id = '" . tep_db_input($FSESSION->customer_id) . "'");
//          tep_redirect(tep_href_link(basename(FILENAME_DEFAULT)));
//             }
//         break;
//    }
//  }

	if ($FREQUEST->getvalue('action')) 
	{
    switch ($FREQUEST->getvalue('action')) 
	{
      case 'update_template':

      if ($template >= '1')
	  {
         $thema_template = tep_db_input($FREQUEST->postvalue('template'));
           tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '$thema_template' where configuration_id = '10'");
          tep_redirect(tep_href_link(basename(FILENAME_DEFAULT)));
             }
         break;
    }
  }
?>
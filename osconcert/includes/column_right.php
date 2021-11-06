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

$count=0;
  $column_query = tep_db_query('select display_in_column as cfgcol, infobox_file_name as cfgtitle, infobox_display as cfgvalue, infobox_define as cfgkey, box_heading, box_template from ' . TABLE_INFOBOX_CONFIGURATION . ' where template_id = ' . tep_db_input(TEMPLATE_ID) . ' order by location');
  while ($column = tep_db_fetch_array($column_query)) 
  {

if ( ($column[cfgvalue] == 'yes') && ($column[cfgcol] == 'right')) 
{
if ( file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/' . $column['cfgtitle'])) 
{
define($column['cfgkey'],$column['box_heading']);
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/' . $column['cfgtitle']);
} 
}
}
?>
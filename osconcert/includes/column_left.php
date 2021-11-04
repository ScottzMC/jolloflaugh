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
// calculate category path
  if ($FREQUEST->getvalue('cPath')) 
  { 
    $cPath = $FREQUEST->getvalue('cPath');
  } elseif ($FREQUEST->getvalue('products_id','int') && !$FREQUEST->getvalue('manufacturers_id')) 
  {
    $cPath = tep_get_product_path($FREQUEST->getvalue('products_id','int'));
  } else 
  {
    $cPath = '';
  }

  if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $current_category_id = 0;
  }
   if (tep_not_null($cPath))
   {
     $FSESSION->set('prev_category_viewed','cPath='.$cPath);
  }
	$count=0; 
	$column_query = tep_db_query('select display_in_column as cfgcol, infobox_file_name as cfgtitle, infobox_display as cfgvalue, infobox_define as cfgkey, box_heading, box_template from ' . TABLE_INFOBOX_CONFIGURATION . ' where template_id = ' . tep_db_input(TEMPLATE_ID) . ' order by location');
	$cfg_cnt=1;
	$cfg_info_addin="o";
	while ($column = tep_db_fetch_array($column_query)) 
	{
		if (($column['cfgvalue'] == 'yes') && ($column['cfgcol'] == 'left')) {
			if (file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/' . $column['cfgtitle'])) 
			{


				require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/' . $column['cfgtitle']);
				
				$cfg_cnt++;
			}
		}
	}
	$cPath = $current_category_id;
?>
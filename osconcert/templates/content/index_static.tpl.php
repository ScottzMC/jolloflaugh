<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
 
$header_text = '&nbsp;';

    //Get the details
	$static_category_sql = "select mpc.page_name, mpc.page_id, mpc.description, mp.page_id,mp.parent_id from " . TABLE_MAINPAGE . " mp, " . TABLE_MAINPAGE_DESCRIPTIONS . " mpc where mp.page_id = '" . (int)$current_static_category_id . "' and mpc.page_id = '" . (int)$current_static_category_id . "' and mpc.language_id = '" . (int)$FSESSION->languages_id . "'";

	$static_category_query = tep_db_query($static_category_sql);
	$static_category = tep_db_fetch_array($static_category_query);

	$stcPath_new = tep_get_static_path($static_category['page_id']);
	$stcPath_end = $static_category['page_id'];

	$header_text = $static_category['page_name'];
	?>
	<div class="index_static">
	<?php echo $static_category['description']; ?>
	</div>
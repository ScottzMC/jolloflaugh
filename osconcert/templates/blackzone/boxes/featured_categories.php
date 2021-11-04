<?php
/*
   

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	Featured Categories for osConcert by cartZone UK 
Copyright (c) 2009-2014 osConcert

	Released under the GNU General Public License 
*/
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<!-- featured categories //-->
<?php 

	$current_sql = "select c.categories_status,c.categories_id, cd.categories_name,cd.categories_heading_title, c.categories_image, c.parent_id, cd.categories_description, cd.concert_venue,cd.concert_date,cd.concert_time from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status > '0' and c.categories_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "'";
	if ($current_categories = tep_random_select($current_sql)) 
	{

	 if(!defined('BOX_HEADING_FEATURED_CATEGORIES'))define('BOX_HEADING_FEATURED_CATEGORIES', 'Featured');
	//' . tep_image(DIR_WS_IMAGES . $current_categories['categories_image'], $current_categories['categories_name']) . '
	
	echo '<div class="card-header">';
	echo '<strong>';
	echo BOX_HEADING_FEATURED_CATEGORIES;
	echo '</strong>';
	echo '</div>';
	echo '<div id="featured"><a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $current_categories['categories_id']) . '"><img class="img-fluid" src="'.DIR_WS_IMAGES.'/'.$current_categories['categories_image'].'" alt="Leaf on the street" /></a>';
	echo '</div>';
	echo '<br class="clearfloat">';
?><!--eof featured categories //-->

<?php
  }
?>
<!-- featured_categories eof //-->

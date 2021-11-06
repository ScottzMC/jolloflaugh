<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>

	<div class="section-header">
	<h2><?php echo HEADING_TITLE; ?></h2>
	</div>


<?php
  list($usec, $sec) = explode(' ', microtime());
  srand( (float) $sec + ((float) $usec * 100000) );
  $mtm= rand();

  $featured_products_query_raw = "select p.products_id, pd.products_name, p.products_image_1,p.products_title_1,p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' where p.products_status = '1' and f.status = '1' order by rand($mtm) ";
  
  
   //$featured_products_query_raw = "select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $FSESSION->languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_status = '1' and f.status = '1' order by p.products_date_added DESC, pd.products_name";

  $featured_products_split = new splitPageResults($featured_products_query_raw, MAX_DISPLAY_FEATURED_PRODUCTS_LISTING);

  $featured_products_query = tep_db_query($featured_products_query_raw);
  while ($featured_products = tep_db_fetch_array($featured_products_query)) {
    $featured_products_array[] = array('id' => $featured_products['products_id'],
                                  'name' => $featured_products['products_name_1'],
                                  'image' => $featured_products['products_image_1'],
								  'title'=>$featured_products['products_title_1'],
                                  'price' => $featured_products['products_price'],
                                  'specials_price' => $featured_products['specials_new_products_price'],
                                  'tax_class_id' => $featured_products['products_tax_class_id'],
                                  'date_added' => tep_date_long($featured_products['products_date_added']),
                                  'manufacturer' => $featured_products['manufacturers_name']);
  }

?>

<?php

  require(DIR_WS_MODULES  . FILENAME_FEATURED_PRODUCTS);
	//require(DIR_WS_MODULES  . FILENAME_FEATURED);


?>


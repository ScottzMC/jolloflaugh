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
<!-- featured //-->
<?php 
	$customer_exists = '';
	if($FSESSION->is_registered('customer_id'))
	$customer_exists = "OR(p.restrict_to_customers like '%," . (int)$FSESSION->customer_id . ",%' OR p.restrict_to_groups like '%," . tep_db_input($customers_groups_id) . ",%')";
	$random_sql = "select p.products_id, pd.products_name, p.products_image_1,p.products_title_1, p.products_price, p.products_tax_class_id, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id  left join " . TABLE_FEATURED . " f on p.products_id = f.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "'  where p.products_status = '1' and f.status = '1' " . 
				" and (" . 
						"(p.restrict_to_customers = '' OR ISNULL(p.restrict_to_customers))" . 
						"and(p.restrict_to_groups = '' OR ISNULL(p.restrict_to_groups))" . $customer_exists . 
				") order by f.featured_date_added DESC limit " . MAX_RANDOM_SELECT_SPECIALS;
  if ($random_product = tep_random_select($random_sql)) 
  {
?>

<?php
$special_price=tep_get_products_special_price($random_product["products_id"]);
	if ($special_price) 
	{
	$whats_new_price =  '<s>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id']),1,true) . '</s><br>';
	$whats_new_price .= $currencies->display_price($special_price, tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>';
	} else 
	{
      $whats_new_price =  $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id']),1,true);
    }

	if(!defined('BOX_HEADING_FEATURED'))define('BOX_HEADING_FEATURED', 'Featured Product');

	echo '<div class="card box-shadow">';
	echo '<div class="card-header">';
	echo '<strong>';
	echo BOX_HEADING_FEATURED;
	echo '</strong>';
	echo '</div>';
	echo '<div class="list-group">';
	echo '<div class="text-center">
	<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . tep_product_custom_image($random_product['products_image_1'], $random_product['products_title_1']) . '</a>
	<br>
	<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id'], 'NONSSL') . '">' . $random_product['products_name'] . '</a>
	<br>
	<span class="productSpecialPrice">' . $whats_new_price . '</span>
	</div>';
	echo '</div>';
	echo '</div>';
	echo '<br class="clearfloat">';
?>
<?php
  }
?>
<!-- featured_eof //-->

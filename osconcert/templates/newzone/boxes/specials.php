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
?>
<!-- specials //-->
<?php
		//start change
		$customer_id=$FSESSION->get("customer_id","int",0);
		$customer_group = tep_get_customers_groups_id();
		$customer_exists = '';
		if($FSESSION->is_registered('customer_id'))
		$customer_exists = "OR(p.restrict_to_customers like '%," . (int)$customer_id . ",%' OR p.restrict_to_groups like '%," . (int)$customers_groups_id . ",%')";
		$random_sql = "select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image_1,p.products_title_1,s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' and s.status = '1' and ((s.customers_id = '" . $FSESSION->customer_id . "' and " . (int)$FSESSION->customer_id .">0) or (s.customers_groups_id = '" . $customer_group . "' and " . (int)$customer_group . ">0) or (s.customers_id = '0' and s.customers_groups_id = '0')) " . 
					" and (" . 
							"(restrict_to_customers = '' OR ISNULL(restrict_to_customers))" . 
							"and(restrict_to_groups = '' OR ISNULL(restrict_to_groups))" . $customer_exists . 
				   ") order by s.specials_date_added desc limit " . MAX_RANDOM_SELECT_SPECIALS;
		if ($random_product = tep_random_select($random_sql)) {
		//end change
?>

<?php
	if(!defined('BOX_HEADING_SPECIALS'))define('BOX_HEADING_SPECIALS', 'Specials');
	
	echo '<div class="card box-shadow">';
	echo '<div class="card-header">';
	echo '<strong>';
	echo BOX_HEADING_SPECIALS;
	echo '</strong>';
	echo '</div>';
	echo '<div class="list-group">';
	echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product["products_id"]) . '">' . tep_product_small_image($random_product['products_image_1'], $random_product['products_title_1']) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . $random_product['products_name'] . '</a><br><s>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id']),1,true) . '</s><br><span class="productSpecialPrice">' . $currencies->display_price(tep_get_products_special_price($random_product['products_id']), tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>';
	echo '</div>';
	echo '</div>';
	echo '<br class="clearfloat">';

  }
?><!-- specials_eof //-->

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
	$template_html='<div class="{{CLASS}}"><a style="display: block;" href="{{LINK_1}}"> {{NAME}}</a></div>';

	$orders_query = tep_db_query("select distinct op.products_id,o.date_purchased from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p where o.customers_id = '" . $FSESSION->customer_id . "' and o.orders_id = op.orders_id and op.products_id = p.products_id and p.products_status = '1' group by products_id,o.date_purchased order by o.date_purchased desc limit " . MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX);
	if (tep_db_num_rows($orders_query)) 
	{
?>
<!-- customer_orders //-->
<?php

	if(!defined('BOX_HEADING_CUSTOMER_ORDERS'))define('BOX_HEADING_CUSTOMER_ORDERS', 'Order History');

	$product_ids = '';
	while ($orders = tep_db_fetch_array($orders_query)) 
	{
		$product_ids .= $orders['products_id'] . ',';
	}
    $product_ids = substr($product_ids, 0, -1);

	$replace_array=array();
	$replace_array["INITIAL_WIDTH"]=$INITIAL_WIDTH;
	$replace_array["CLASS"]="list-group-item";
	$replace_array["SPACER"]="";
	$replace_array["ICON"]=$CONTENT_ICON;
	$replace_html=$template_html;
	
	reset($replace_array);

	foreach($replace_array as $key=>$value) 
	{
		$replace_html=str_replace("{{" . $key . "}}",$replace_array[$key],$replace_html);
	}
	
	$replace_array=array();
    $products_query = tep_db_query("select products_id, products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id in (" . (int)$product_ids . ") and language_id = '" . (int)$FSESSION->languages_id . "' order by products_name");
    while ($products = tep_db_fetch_array($products_query)) 
	{
		$prod_name_length=strlen($products['products_name']);
		if($prod_name_length>20) $products['products_name']=substr($products['products_name'],0,20).'..';
		
		$replace_string=$replace_html;
		$replace_array["LINK_1"] = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']);
		$replace_array["NAME"]= $products['products_name'];
		$replace_array['LINK_2']=tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=cust_order&pid=' . $products['products_id'], 'NONSSL');
		
		reset($replace_array);

	foreach($replace_array as $key=>$value) 
	{
			$replace_string=str_replace("{{" . $key . "}}",$replace_array[$key],$replace_string);
		}
		$customer_orders_string.=$replace_string;  
    }

	echo '<div class="card box-shadow">';
	echo '<div class="card-header">';
	echo '<strong>';
	echo BOX_HEADING_CUSTOMER_ORDERS;
	echo '</strong>';
	echo '</div>';
	echo '<div class="list-group">';
	echo $customer_orders_string;
	echo '</div>';
	echo '</div>';
	echo '<br class="clearfloat">';
  }
?><!-- customer_orders_eof //-->
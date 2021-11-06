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
<!-- downloads //-->
<div>
<?php
	if (!strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) 
	{
	// Get last order id for checkout_success
	$orders_query_raw = "SELECT orders_id FROM " . TABLE_ORDERS . " WHERE customers_id = '" . (int)$FSESSION->customer_id . "' ORDER BY orders_id DESC LIMIT 1";
	$orders_query = tep_db_query($orders_query_raw);
	$orders_values = tep_db_fetch_array($orders_query);
	$last_order = $orders_values['orders_id'];
	} else 
	{
	$last_order = $FREQUEST->getvalue('order_id');
	}

// Now get all downloadable products in that order
  $downloads_query_raw = "SELECT DATE_FORMAT(date_purchased, '%Y-%m-%d') as date_purchased_day, opd.download_maxdays, op.products_name, opd.orders_products_download_id, opd.orders_products_filename, opd.download_count, opd.download_maxdays
                          FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd
                          WHERE customers_id = '" . (int)$FSESSION->customer_id . "'
                          AND o.orders_id = '" . (int)$last_order . "'
                          AND op.orders_id = '" . (int)$last_order . "'
                          AND opd.orders_products_id=op.orders_products_id
                          AND opd.orders_products_filename<>''";
  $downloads_query = tep_db_query($downloads_query_raw);

// Don't display if there is no downloadable product
	if (tep_db_num_rows($downloads_query) > 0) 
	{
     require(DIR_WS_LANGUAGES . $FSESSION->language . '/download.php');
         // echo '<tr><td>' ;
		if(!defined('BOX_HEADING_DOWNLOADS'))define('BOX_HEADING_DOWNLOADS', 'Downloads');
		
		echo '<div class="card box-shadow">';
		echo '<div class="card-header">';
		echo '<strong>';
		echo BOX_HEADING_DOWNLOADS;
		echo '</strong>';
		echo '</div>';
	?>
	<!-- list of products -->
	<?php
		while ($downloads_values = tep_db_fetch_array($downloads_query)) 
		{
	?>

	<!-- left box -->
	<?php
	// MySQL 3.22 does not have INTERVAL
			list($dt_year, $dt_month, $dt_day) = explode('-', $downloads_values['date_purchased_day']);
			$download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads_values['download_maxdays'], $dt_year);
			$download_expiry = date('Y-m-d H:i:s', $download_timestamp);

	// The link will appear only if:
	// - Download remaining count is > 0, AND
	// - The file is present in the DOWNLOAD directory, AND EITHER
	// - No expiry date is enforced (maxdays == 0), OR
	// - The expiry date is not reached
		  if (($downloads_values['download_count'] > 0) &&
			  (file_exists(DIR_FS_DOWNLOAD . $downloads_values['orders_products_filename'])) &&
			  (($downloads_values['download_maxdays'] == 0) ||
			   ($download_timestamp > time()))) 
			   {
	 
	 echo TEXT_HEADING_DOWNLOAD_FILE . '<br><br><a href="' . tep_href_link(FILENAME_DOWNLOAD, 'order=' . $last_order . '&id=' . $downloads_values['orders_products_download_id']) . '">' . $downloads_values['products_name'] . '</a>';
	 
		  } else 
		  {
	 
	 echo $downloads_values['products_name'];

		  }
		?>
		<!-- right box -->
		<?php
		echo TEXT_HEADING_DOWNLOAD_DATE . '<br>' .  tep_date_long($download_expiry);

		echo $downloads_values['download_count'] . '  ' .  TEXT_HEADING_DOWNLOAD_COUNT;
		}

			if (!strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) 
			{
			echo TEXT_FOOTER_DOWNLOAD . '<br><a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . TEXT_DOWNLOAD_MY_ACCOUNT . '</a>';
		   }

		echo '</div>';
		echo '<br class="clearfloat">';
	}
	?>
	<!-- downloads_eof //-->

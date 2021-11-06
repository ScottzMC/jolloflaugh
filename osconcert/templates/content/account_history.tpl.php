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
  $orders_total = tep_count_customer_orders();

  if ($orders_total > 0) {
// Changed for Paypal IPN Mod

//Here is the new line for paypal IPN
    $history_query_raw = "select o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$FSESSION->customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$FSESSION->languages_id . "' and o.orders_status != '99999' order by orders_id DESC";
//End Paypal IPN Mod
    $history_split = new splitPageResults($history_query_raw, MAX_DISPLAY_ORDER_HISTORY);
    $history_query = tep_db_query($history_split->sql_query);

    while ($history = tep_db_fetch_array($history_query)) {
      $products_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$history['orders_id'] . "'");
      $products = tep_db_fetch_array($products_query);

      if (tep_not_null($history['delivery_name'])) {
        $order_type = TEXT_ORDER_SHIPPED_TO;
        $order_name = $history['delivery_name'];
      } else {
        $order_type = TEXT_ORDER_BILLED_TO;
        $order_name = $history['billing_name'];
      }
?>
          <table width="100%" cellpadding="2">
            <tr>
              <td class="main"><?php echo '<b>' . TEXT_ORDER_NUMBER . '</b> ' . $history['orders_id']; ?></td>
              <td class="main" align="right"><?php echo '<b>' . TEXT_ORDER_STATUS . '</b> ' . $history['orders_status_name']; ?></td>
            </tr>
          </table>
          <table width="100%" cellspacing="1" cellpadding="2" class="infoBox">
            <tr class="infoBoxContents">
              <td><table width="100%" cellspacing="2" cellpadding="4">
                <tr>
                  <td class="main" width="50%" valign="top">&nbsp;&nbsp;<?php echo '<b>' . TEXT_ORDER_DATE . '</b> ' . format_date($history['date_purchased']) . '<br><b>' . $order_type . '</b> ' . tep_output_string_protected($order_name); ?></td>
                  <td class="main" width="30%" valign="top"><?php echo '<b>' . TEXT_ORDER_PRODUCTS . '</b> ' . $products['count'] . '<br><b>' . TEXT_ORDER_COST . '</b> ' . strip_tags($history['order_total']); ?></td>
                  <td class="main" width="20%"><?php echo '<a class="btn" href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, ($FREQUEST->getvalue('page')!='' ? 'page=' . $FREQUEST->getvalue('page') . '&' : '') . 'order_id=' . $history['orders_id'], 'SSL') . '">' . tep_template_image_button_basic('small_view.gif', SMALL_IMAGE_BUTTON_VIEW) . '</a>'; ?></td>
                </tr>
              </table></td>
            </tr>
          </table>
<?php
    }
  } else {
?>
          <?php echo TEXT_NO_PURCHASES; ?>
<?php
  }
?>

<?php
  if ($orders_total > 0) {
?>
      <table width="100%" cellpadding="2">
          <tr>
            <td class="smallText" valign="top" width="50%"><?php echo $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
            <td class="smallText" align="right" width="40%"><?php echo TEXT_RESULT_PAGE . '</td>&nbsp;<td class="pageNavigation" width="10%" align="right"> ' . $history_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table>
<?php
  }
?>
      <div><?php echo '<a class="btn btn-primary" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button_basic('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></div>

<br><br>
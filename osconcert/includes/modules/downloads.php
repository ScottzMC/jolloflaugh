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
$server_date = getServerDate(true);

?>
<!-- downloads //-->
<?php
  if (!strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
// Get last order id for checkout_success
    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$FSESSION->customer_id . "' order by orders_id desc limit 1");
    $orders = tep_db_fetch_array($orders_query);
    $last_order = $orders['orders_id'];
  } else {
    $last_order = $FREQUEST->getvalue('order_id');
  }
$total_download=0;
// Now get all downloadable products in that order
// BOF: WebMakers.com Added: Downloads Controller
// DEFINE WHICH ORDERS_STATUS TO USE IN downloads_controller.php
// USE last_modified instead of date_purchased
// original  $downloads_query = tep_db_query("select                 date_format(o.date_purchased, '%Y-%m-%d') as date_purchased_day, opd.download_maxdays, op.products_name, opd.orders_products_download_id, opd.orders_products_filename, opd.download_count, opd.download_maxdays from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd where o.customers_id = '" . (int)$FSESSION->customer_id . "' and o.orders_id = '" . (int)$last_order . "' and o.orders_id = op.orders_id and op.orders_products_id = opd.orders_products_id and opd.orders_products_filename != ''");
  $downloads_query = tep_db_query("select o.orders_status, date_format(o.last_modified, '%Y-%m-%d') as date_purchased_day, opd.download_maxdays, op.products_name, opd.orders_products_download_id, opd.orders_products_filename, opd.download_count, opd.download_maxdays from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd where o.customers_id = '" . (int)$FSESSION->customer_id . "' and o.orders_status >= '" . DOWNLOADS_CONTROLLER_ORDERS_STATUS . "' and o.orders_id = '" . (int)$last_order . "' and o.orders_id = op.orders_id and op.orders_products_id = opd.orders_products_id and opd.orders_products_filename != ''");
  
  if (tep_db_num_rows($downloads_query) > 0) {
?>

<div><h4><?php echo HEADING_DOWNLOAD; ?></h4></div>
<table width="100%" cellspacing="2" cellpadding="5" class="download_area">
		<tr>
		<th> Name </th> <th> Download Expiry Date </th> 
		<th> Download Remaining</th> <th>Size(KB)</th> 
		<th> Download </th> 
		</tr>
			
<!-- list of products -->
<?php
    while ($downloads = tep_db_fetch_array($downloads_query)) {
	
// MySQL 3.22 does not have INTERVAL
      list($dt_year, $dt_month, $dt_day) = explode('-', $downloads['date_purchased_day']);
      $download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads['download_maxdays'], $dt_year);
      $download_expiry = date('Y-m-d H:i:s', $download_timestamp);
	 //$download_expiry = date('Y-m-d H:i:s', strtotime($server_date));
	  $total_download=$downloads['download_count'];
?>
          <tr>
<!-- left box -->
<?php
// The link will appear only if:
// - Download remaining count is > 0, AND
// - The file is present in the DOWNLOAD directory, AND EITHER
// - No expiry date is enforced (maxdays == 0), OR
// - The expiry date is not reached
     // if ( ($downloads['download_count'] > 0) && (file_exists(DIR_FS_DOWNLOAD . $downloads['orders_products_filename'])) && ( ($downloads['download_maxdays'] == 0) || ($download_timestamp > time())) ) {
// WebMakers.com Added: Downloads Controller Show Button
// original MS1        echo '            <td class="main"><a href="' . tep_href_link(FILENAME_DOWNLOAD, 'order=' . $last_order . '&id=' . $downloads['orders_products_download_id']) . '">' . $downloads['products_name'] . '</a></td>' . "\n";
		//echo '            <td class="main" align="center">'. $downloads['products_name']."</td>" . "\n";
		// } else {
        //echo '            <td class="main">' . $downloads['products_name'] . '</td>' . "\n";
      //}
?>
<!-- right box -->
<?php

// BOF: WebMakers.com Added: Downloads Controller
	  echo '            <td class="main" align="center">'. $downloads['products_name']."</td>" . "\n";
      echo '      <td class="main" align="center">'. tep_date_long($download_expiry) . '</td>' . "\n" .
           '            <td class="main" align="center"><span id="download_count">' . $downloads['download_count'] .'</span></td>' . "\n";
      $download_kb_size=round(filesize(DIR_FS_DOWNLOAD . $downloads['orders_products_filename'])/1024);
	  echo '<td class="main" align="center">'.$download_kb_size.'</td>'; 
		
		if ( ($downloads['download_count'] > 0) && (file_exists(DIR_FS_DOWNLOAD . $downloads['orders_products_filename'])) && ( ($downloads['download_maxdays'] == 0) || ($download_timestamp > time())) ) {
		echo '<td class="link_download" align="center"> <a href="javascript:do_download(\'' . $last_order . '\',\'' .$downloads['orders_products_download_id'] . '\');">'.tep_image(DIR_WS_ICONS."download.gif",'Click Here').'</td></tr>';
     	 }   
		 else
		 {
		  echo '<td align="center"> <a href="javascript:do_download(\'' . $last_order . '\',\'' .$downloads['orders_products_download_id'] . '\');">'.tep_image(DIR_WS_ICONS."download.gif",'Click Here').'</td></tr>';
		  } 
		   //'          </tr>' . "\n";
// EOF: WebMakers.com Added: Downloads Controller
    }
?>
          </tr>
<?php
    if (!strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
?>
      <tr>
        <td class="main" colspan="4"><p class="link_download"><?php printf(FOOTER_DOWNLOAD, '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . HEADER_TITLE_MY_ACCOUNT . '</font></a>'); ?></p></td>
      </tr>
<?php
    }
?>	
	    </table>
<?php  }
?>
<?php
// BOF: WebMakers.com Added: Downloads Controller
// If there is a download in the order and they cannot get it, tell customer about download rules
  $downloads_check_query = tep_db_query("select o.orders_id, opd.orders_products_download_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd where o.orders_id = opd.orders_id and o.orders_id = '" . (int)$last_order . "' and opd.orders_products_filename != ''");

if (tep_db_num_rows($downloads_check_query) > 0 && tep_db_num_rows($downloads_query) < 1) {
// if (tep_db_num_rows($downloads_query) < 1) {
?>
      <tr>
        <td colspan="3" align="center" valign="top" class="main" height="30">
		<FONT FACE="Arial" SIZE=1 COLOR="FF000"><?php echo DOWNLOADS_CONTROLLER_ON_HOLD_MSG ?></FONT>
		</td>
      </tr>
<?php
} 
// EOF: WebMakers.com Added: Downloads Controller
 include(DIR_WS_INCLUDES.'date_format_js.php');

//echo $server_date;

?>
<!-- downloads_eof //-->
<script>
	var total_download=<?php echo $total_download;?>;
	var expiry=date_format('<?php echo $download_expiry?>','Y-m-d','',true);
	var server=date_format('<?php echo $server_date?>','Y-m-d','',true);
	//for checking expiring
	//var server=date_format('<?php echo '5-09-2008'?>','','',true);
	//alert (server );
	var error_result;
	function do_download(order,did) {
	error_result='';	
		total_download=parseInt(total_download) -1;
		if(total_download>=0)
			document.getElementById('download_count').innerHTML=total_download;
		if(total_download<0) 
			error_result+='* You are exceeded the download limit\n';
		else if(expiry <= server)
		    error_result+='* Your Download link expires for this product\n';	
		
			
	    if (error_result!=""){
		   alert(error_result);
		}else{
		   location.href='<?php echo tep_href_link('download.php','','SSL',false);?>' + '?order=' + order + '&id=' + did;

		}		
	}
</script>
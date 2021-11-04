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
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
  $xx_mins_ago = (time() - 900);

  require('includes/application_top.php');
  
  $command=$FREQUEST->getvalue('command');
  if($command!=''){
  	switch($command){
		case 'load_online_details':
		$customers_id=$FREQUEST->getvalue('customers_id');
		$session_id=$FREQUEST->getvalue('session_id');
		echo 'load_online_details^^'.$session_id.'^^';
		echo load_online_details($customers_id,$session_id);
		break;
	}
  exit;
  }
  
   tep_get_last_access_file();
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

// remove entries that have expired
  tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");
  
  function load_whos_online(){
  $whos_online_query = tep_db_query("select customer_id, full_name, ip_address, time_entry, time_last_click, last_page_url, session_id from " . TABLE_WHOS_ONLINE);
  echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
  $cnt=0;
  while($whos_online=tep_db_fetch_array($whos_online_query)){
  $class_name=($cnt%2==0)?'dataTableRowEven':'dataTableRowOdd';
  $time_online = (time() - $whos_online['time_entry']);
  ?>
  <tr>
  <td style="padding-top:5px;">
  <div onClick="javascript: load_online_details(<?php echo $whos_online['customer_id'].',\''.$whos_online['session_id'].'\''; ?>);" id="online_<?php echo $whos_online['session_id']; ?>" class="<?php echo $class_name; ?>" onMouseOver="rowOverEffect(this);" onMouseOut="rowOutEffect(this);">
   <table width="100%" cellpadding="0" cellspacing="0" border="0">
   <tr>
   <td width="100" class="dataTableContent"><?php echo gmdate('H:i:s', $time_online); ?></td>
   <td width="100" class="dataTableContent" align="center"><?php echo $whos_online['customer_id']; ?></td>
	<td width="100" class="dataTableContent"><?php echo $whos_online['full_name']; ?></td>
	<td width="100" class="dataTableContent" align="center"><?php echo $whos_online['ip_address']; ?></td>
	<td width="100" class="dataTableContent"><?php echo date('H:i:s', $whos_online['time_entry']); ?></td>
	<td width="100" class="dataTableContent" align="center"><?php echo date('H:i:s', $whos_online['time_last_click']); ?></td>
	<td width="150" class="dataTableContent"><?php if (preg_match('/^(.*)' . $FSESSION->NAME . '=[A-Z0-9,-]+[&]*(.*)/i', $whos_online['last_page_url'], $array)) { echo $array[1] . $array[2]; } else { echo $whos_online['last_page_url']; } ?>&nbsp;</td>
	</tr>
	</table>
  </div>
  <div id="ajax_online_<?php echo $whos_online['session_id']; ?>">
  </div>
  </td>
  </tr>
  <?php
  }
  echo '<tr>'.
       '<td style="padding-top:10px;" class="smallText">'. sprintf(TEXT_NUMBER_OF_CUSTOMERS, tep_db_num_rows($whos_online_query)).' </td>'.
       '</tr></table>';
  }
  
	 function load_online_details($customers_id,$session_id){
	 global $cart,$currency;
	 require(DIR_WS_CLASSES . 'currencies.php');
	 $currencies = new currencies();
     $whos_online_query = tep_db_query("select customer_id, full_name, ip_address, time_entry, time_last_click, last_page_url, session_id from " . TABLE_WHOS_ONLINE." where session_id ='".$session_id."'");
	 $whos_online=tep_db_fetch_array($whos_online_query);
	 $info = $whos_online['session_id'];
	 $time_online = (time() - $whos_online['time_entry']);
	 ?>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="openContent">
	<tr class="openContent_top">
	<td width="300" class="main"><?php echo tep_image(DIR_WS_IMAGES.'template/icon_active.gif').'&nbsp;'.$whos_online['full_name']; ?></td>
	<td align="right"><?php echo '<a href="javascript: close_online_details(\''.$whos_online['session_id'].'\');">'.tep_image(DIR_WS_IMAGES.'template/img_closel.gif','Close','','','').'</a>'; ?>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2"><table width="100%" cellpadding="2" cellspacing="2" border="0">
		<tr>
		<td class="dataTableHeadingContent" width="200" align="right"><?php echo TABLE_HEADING_CUSTOMER_ID; ?> :</td>
		<td class="smallText" width="100"><?php echo $whos_online['customer_id']; ?></td>
		<td class="dataTableHeadingContent" width="200" align="right"><?php echo TABLE_HEADING_ONLINE; ?> :</td>
		<td class="smallText"><?php echo gmdate('H:i:s', $time_online); ?></td>
		</tr>
		<tr>
		<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ENTRY_TIME; ?> :</td>
		<td class="smallText"><?php echo date('H:i:s', $whos_online['time_entry']); ?></td>
		<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_LAST_CLICK; ?></td>
		<td class="smallText"><?php echo date('H:i:s', $whos_online['time_last_click']); ?></td>
		</tr>
		</table></td>
	</tr>
	<?php
    $heading[] = array('text' => '<b>' . TABLE_HEADING_SHOPPING_CART . '</b>');

    if (STORE_SESSIONS == 'mysql') { 
      $session_data = tep_db_query("select value from " . TABLE_SESSIONS . " WHERE sesskey = '" . tep_db_input($info) . "'");
      $session_data = tep_db_fetch_array($session_data);
      $session_data = trim($session_data['value']);
    } else {
      if ( (file_exists(tep_session_save_path() . '/sess_' . $info)) && (filesize(tep_session_save_path() . '/sess_' . $info) > 0) ) {
        $session_data = file(tep_session_save_path() . '/sess_' . $info);
        $session_data = trim(implode('', $session_data));
      }
    }

    if ($length = strlen($session_data)) {
      if (PHP_VERSION < 4) {
        $start_id = strpos($session_data, 'customer_id[==]s');
        $start_cart = strpos($session_data, 'cart[==]o');
        $start_currency = strpos($session_data, 'currency[==]s');
        $start_country = strpos($session_data, 'customer_country_id[==]s');
        $start_zone = strpos($session_data, 'customer_zone_id[==]s');
      } else {
        $start_id = strpos($session_data, 'customer_id|s');
        $start_cart = strpos($session_data, 'cart|O');
        $start_currency = strpos($session_data, 'currency|s');
        $start_country = strpos($session_data, 'customer_country_id|s');
        $start_zone = strpos($session_data, 'customer_zone_id|s');
      }

      for ($i=$start_cart; $i<$length; $i++) {
        if ($session_data[$i] == '{') {
          if (isset($tag)) {
            $tag++;
          } else {
            $tag = 1;
          }
        } elseif ($session_data[$i] == '}') {
          $tag--;
        } elseif ( (isset($tag)) && ($tag < 1) ) {
          break;
        }
      }

      $session_data_id = substr($session_data, $start_id, (strpos($session_data, ';', $start_id) - $start_id + 1));
      $session_data_cart = substr($session_data, $start_cart, $i);
      $session_data_currency = substr($session_data, $start_currency, (strpos($session_data, ';', $start_currency) - $start_currency + 1));
      $session_data_country = substr($session_data, $start_country, (strpos($session_data, ';', $start_country) - $start_country + 1));
      $session_data_zone = substr($session_data, $start_zone, (strpos($session_data, ';', $start_zone) - $start_zone + 1));

      session_decode($session_data_id);
      session_decode($session_data_currency);
      session_decode($session_data_country);
      session_decode($session_data_zone);
      session_decode($session_data_cart);

      if (PHP_VERSION < 4) {
        $broken_cart = $cart;
        $cart = new shoppingCart;
        $cart->unserialize($broken_cart);
      }


    }

	if(is_object($cart)){
	echo '<tr><td colspan="2"><br><table width="300" align="center" cellpadding="0" cellspacing="0" border="0">';
		$products=$cart->get_products();
		if(sizeof($products)>0){
		echo '<tr><td class="dataTableHeadingContent">'.TABLE_HEADING_SHOPPING_CART .'</td></tr>';
		}
		for($i=0,$n=sizeof($products);$i<$n;$i++){
		echo '<tr><td style="padding-top:3px;" class="smallText">'.$products[$i]['quantity'].' x '.$products[$i]['name'].'</td></tr>';
 	?>
	
	<?php
		} // for
		if(sizeof($products)>0){
		echo '<tr><td>'.tep_draw_separator('pixel_black.gif', '100%', '1').'</td></tr>';
		echo '<tr><td class="smallText" align="right">'.TEXT_SHOPPING_CART_SUBTOTAL.' '.$currencies->format($cart->show_total(),true,$currency).'</td></tr>';
		}
	echo '</table></td></tr>';
	}
	?>
   <tr><td colspan="2"><br></td></tr>
	</table>
  <?php } ?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript" src="includes/http.js"></script>
<script language="javascript">
var ajax_image_load='<?php echo tep_image(DIR_WS_IMAGES.'24-1.gif'); ?>',previous_session_id='';
	function load_online_details(customers_id,session_id){
	document.getElementById('ajax_online_'+session_id).style.display='';
	document.getElementById('ajax_online_'+session_id).innerHTML=ajax_image_load;
	do_get_command('<?php echo tep_href_link(FILENAME_WHOS_ONLINE,'command=load_online_details&customers_id='); ?>'+customers_id+'&session_id='+session_id);
		if(previous_session_id!='' && previous_session_id!=session_id)
			close_online_details(previous_session_id);
	previous_session_id=session_id;
	}
	function close_online_details(session_id){
	document.getElementById('ajax_online_'+session_id).innerHTML='';
	document.getElementById('ajax_online_'+session_id).style.display='none';
	document.getElementById('online_'+session_id).style.display='';
	}
	function do_result(result){
	var token=result.split('^^');
		switch(token[0]){
			case 'load_online_details': 
			document.getElementById('online_'+token[1]).style.display='none';
			document.getElementById('ajax_online_'+token[1]).innerHTML=token[2];
			document.getElementById('ajax_online_'+token[1]).style.display='';
			break;
		}
	}
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- Ajax work Starts -->
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td style="padding:2px;" class="pageHeading"><?php echo HEADING_TITLE; ?></td>
	</tr>
	<tr>
	<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr class="dataTableHeadingRow">
	<td width="100" class="dataTableHeadingContent"><?php echo TABLE_HEADING_ONLINE; ?></td>
	<td width="100" class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_CUSTOMER_ID; ?></td>
	<td width="100" class="dataTableHeadingContent"><?php echo TABLE_HEADING_FULL_NAME; ?></td>
	<td width="100" class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_IP_ADDRESS; ?></td>
	<td width="100" class="dataTableHeadingContent"><?php echo TABLE_HEADING_ENTRY_TIME; ?></td>
	<td width="100" class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LAST_CLICK; ?></td>
	<td width="150" class="dataTableHeadingContent"><?php echo TABLE_HEADING_LAST_PAGE_URL; ?>&nbsp;</td>
	</tr>
	<tr><td colspan="7"><?php echo load_whos_online(); ?></td></tr>

	</table></td>
	</tr>
	</table>
<!-- Ajax work Ends -->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>


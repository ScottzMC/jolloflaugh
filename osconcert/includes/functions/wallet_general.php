<?php
/*
  

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
  Released under the GNU General Public License
*/


// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();


// To Find the users wallet balance

	function tep_get_wallet_balance($customer_id){
	
		//total of  available amount uploads
		$upload_query=tep_db_query("select sum(amount) as upload_total from ". TABLE_WALLET_UPLOADS. " where customers_id='" . (int)$customer_id . "'  and payment_status>1  and payment_status<4");
		$upload_result=tep_db_fetch_array($upload_query);
		$upload_total=$upload_result['upload_total'];

		// total of amount drawn
		$drawn_query=tep_db_query("select sum(amount) as drawn_total from ". TABLE_WALLET_HISTORY. " where customers_id='" . (int)$customer_id . "'");
		$drawn_result=tep_db_fetch_array($drawn_query);
		$drawn_total=$drawn_result['drawn_total'];
		
		return $upload_total-$drawn_total;
	}
	
	function tep_get_pending_wallet_balance($customer_id){
	

		//total of  pending amount uploads
		$pending_query=tep_db_query("select sum(amount) as pending_total from ". TABLE_WALLET_UPLOADS. " where customers_id='" . (int)$customer_id . "'  and payment_status=1");
		$pending_result=tep_db_fetch_array($pending_query);
		$pending_total=$pending_result['pending_total'];
		


		return $pending_total;
	}

	function tep_get_wallet_min_balance($customers_id){
		$amount=(int) WALLET_MINIMUM_AMOUNT;
		$type=WALLET_MINIMUM_TYPE;
		if ($type=="P"){ // percentage of last 3 orders
			$order_sql="SELECT count(*) as orders_count,sum(value) as total from " . TABLE_WALLET_HISTORY . " wh, " . TABLE_ORDERS_TOTAL . " ot where wh.customers_id='" . (int)$customers_id . "' " .
						" and wh.orders_id=ot.orders_id order by wh.drawn_date limit 3";
			$order_query=tep_db_query($order_sql);
			$order_result=tep_db_fetch_array($order_query);
			if ($order_result["orders_count"]>0 && $order_result["total"]>0){
				$final_amount=($order_result["total"]/$order_result["orders_count"])* WALLET_MINIMUM_AMOUNT/100;
			} else {
				$type="C";
			}
		}
		if ($type!="P") $final_amount=$amount;
		
		return $final_amount;
	}
	
	function tep_count_wallet_uploads() {
	    global $FSESSION;

		$wallet_upload_query = tep_db_query("select count(*) as total from " . TABLE_WALLET_UPLOADS . " where customers_id = '" . (int)$FSESSION->customer_id . "'");
		$wallet_upload = tep_db_fetch_array($wallet_upload_query);

		return $wallet_upload['total'];
  }
?>

<?php
/*
  

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
http://www.zac-ware.com/freeway

Copyright 2007 ZacWare Pty. Ltd 
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	
	//require('includes/sms_application_top.php');
	require('includes/classes/currencies.php'); 

	
	$mail_status=array('E'=>'N','S'=>'N');

	$now=getServerDate(true);
	$curdate=getServerDate();

	//$curdate='2006-04-23';
	//$now='2006-04-22 00:00:00';
	
	$currencies=new currencies();
	$current_time=date("H:i");

	$time_split=(defined('EVENTS_EMAIL_TIMINGS')?split(",",EVENTS_EMAIL_TIMINGS):array());
	$offset=(int)EVENTS_SERVER_DATE_OFFSET;
	
	if (sizeof($time_split)>1 && $offset>=-23 && $offset<=23){
		$start_time="";
		$end_time="";
		//if ($time_split[0]>0) $start_time=date("H:i",tep_timeadd(strtotime($time_split[0]),'hr',$offset));
		//if ($time_split[1]>0) $end_time=date("H:i",tep_timeadd(strtotime($time_split[1]),'hr',$offset));
		if ($time_split[0]>0) $start_time=date("H:i",mktime(date('H')+abs($offset+$time_split[0])));
		if ($time_split[1]>0) $end_time=date("H:i",mktime(date('H')+abs($offset+$time_split[1])));
		$flag=0;
		if ($time_split[0]<=0 || $current_time>=$start_time) $flag++;
		if ($time_split[1]<=0 || $current_time<=$end_time) $flag++;
		if ($flag==2) $mail_status['E']='Y';
	}
	if ((int)EMAIL_ACTIVATE!=1) $mail_status['E']='N';

	// find if the template for the wallet balance reminder email is present
	$template_query=tep_db_query("SELECT * from " . TABLE_EMAIL_MESSAGES . " where message_type='WBW'");
	if (tep_db_num_rows($template_query)<=0) {
		$mail_status["E"]="N";
	}
	
	// if mail sending is enabled and if we are in the time scheduling 

		
		//check for wallet Balance
		// first get the wallet upoad total
		$balance=array();
		$sql="SELECT customers_id,sum(amount) as total from " . TABLE_WALLET_UPLOADS . " where payment_status>1 group by customers_id order by customers_id";
		$upload_query=tep_db_query($sql);
		while($upload_result=tep_db_fetch_array($upload_query)){
			$balance[$upload_result["customers_id"]]["upload_total"]=$upload_result["total"];
		}
		
		// get the wallet drawn total
		$sql="SELECT customers_id,sum(amount) as total from " . TABLE_WALLET_HISTORY . " group by customers_id order by customers_id";
		$drawn_query=tep_db_query($sql);
		while($drawn_result=tep_db_fetch_array($drawn_query)){
			$balance[$drawn_result["customers_id"]]["drawn_total"]=$drawn_result["total"];
		}
		$mail_status['E']='Y';
	if ($mail_status['E']=='Y'){
		// get the customers to be the messages are sent
		$sql="SELECT customers_id,if(date_format(date_add(max(send_date), interval 3 day),'%Y-%m-%d')='" .$curdate ."','Y','N') as mail_send from " . TABLE_WALLET_MESSAGES_HISTORY . " where message_type='WBW' group by customers_id";
		$mail_query=tep_db_query($sql);
		while($mail_result=tep_db_fetch_array($mail_query)){
			$balance[$mail_result["customers_id"]]["mail_send"]=$mail_result["mail_send"];
		}
		// find the customers who had balance < defined balance amount
		if (count($balance)>0){
			reset($balance);
			//while(list($customers_id,$value)=each($balance)){
				foreach($balance as $customers_id => $value)
				{
				//FOREACH
				if (!isset($value["upload_total"])) $value["upload_total"]=0;
				if (!isset($value["drawn_total"])) $value["drawn_total"]=0;
				$current_balance=$value["upload_total"]-$value["drawn_total"];

				// get minimum balance
				$minimum_balance=tep_get_wallet_min_balance($customers_id);
				if ($current_balance<$minimum_balance && (!isset($value["mail_send"]) || $value["mail_send"]=="Y")) {
					tep_send_wallet_balance_reminder($customers_id,$current_balance);
				}
			} // while balance
		} // count balance
	} // if mailstatus
	
	
	// making payment for the expired subscription
	// first get the expired subscriptions for the current date
	// if subscription wallet payment is on, draw amount after subscription ends
	// if subscription periodic payment is on, draw amount after/before periodic days from subscription ends
	$expired_sql="SELECT o.customers_id,su.wallet_payment,su.periodic_payment,o.orders_id,su.subscription_id,date_add(max(o.date_purchased), interval su.subscription_period day) as subscription_end_date, " . 
						" if (su.periodic_type='A',date_add(max(o.date_purchased), interval su.subscription_period+su.periodic_days day),date_add(max(o.date_purchased), interval su.subscription_period-su.periodic_days day)) as subscription_payment_date from " .
						TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " .
						TABLE_SUBSCRIPTIONS . " su where op.orders_id=o.orders_id and op.products_id=su.subscription_id and op.products_type='S' and (su.wallet_payment='Y' || su.periodic_payment='Y') " .
						" group by o.customers_id,su.subscription_id having (su.wallet_payment='Y' and '" . $curdate . "'>=date_format(subscription_end_date,'%Y-%m-%d')) or (su.periodic_payment='Y' and '" . $curdate . "'>=date_format(subscription_payment_date,'%Y-%m-%d')) order by su.subscription_id";

	$expired_query=tep_db_query($expired_sql);


	$total_class=array(	'ot_subtotal'=>array('title'=>"Sub-Total:",'sort_order'=>1),
						'ot_tax'=>array('title'=>'Tax:','sort_order'=>2),
						'ot_total'=>array('title'=>'Total:','sort_order'=>4));

	$prev_id=0;
	// create new orders for the entries 
	while($expired_result=tep_db_fetch_array($expired_query)){
		// get the subscription price
		$temp_drawn_amount=0;
		$customers_id=$expired_result["customers_id"];
		if ($prev_id!=$expired_result["subscription_id"]){
			$subscription_query=tep_db_query("SELECT su.subscription_id,su.subscription_costs,sud.subscription_name,su.subscription_tax_class_id from " . TABLE_SUBSCRIPTIONS . " su, " .
												TABLE_SUBSCRIPTIONS_DESCRIPTION . " sud where su.subscription_id=sud.subscription_id and sud.language_id='" .  $languages_id . "' and su.subscription_id='" . $expired_result["subscription_id"] . "'"
												);
			$subscription_result=tep_db_fetch_array($subscription_query);
			$prev_id=$subscription_result["subscription_id"];
			
			$subscription_result["tax_rate"]=tep_get_tax_rate($subscription_result["subscription_tax_class_id"]);
			$subscription_result["ot_tax"]=tep_calculate_tax($subscription_result["subscription_costs"],$subscription_result["tax_rate"]);
			$subscription_result["ot_total"]=$subscription_result["subscription_costs"]+$subscription_result["ot_tax"];
			$subscription_result["ot_subtotal"]=$subscription_result["ot_total"];
		}
		
		if (tep_db_num_rows($subscription_query)<=0) continue;
		if (!isset($balance[$customers_id])) continue;
		
		// check for amount present in wallet
		$temp_drawn_amount=$balance[$customers_id]["drawn_total"]+$subscription_result["ot_total"];
		$upload_amount=$balance[$customers_id]["upload_total"];

		if ($temp_drawn_amount>$upload_amount) continue;
		
		
		// update to order
		$order_query=tep_db_query("SELECT * from " . TABLE_ORDERS . " where orders_id='" . $expired_result["orders_id"] . "'");
		$order_result=tep_db_fetch_array($order_query);
		$order_result["payment_method"]="Wallet Payment";
		$order_result["cc_type"]="";
		$order_result["cc_owner"]="";
		$order_result["cc_number"]="";
		$order_result["last_modified"]="";
		$order_result["date_purchased"]=$now;
		$order_result["orders_date_finished"]="";
		$order_result["payment_info"]="Wallet Payment";
		$order_result["cc_cvv_number"]="";
		unset($order_result["orders_id"]);
		tep_db_perform(TABLE_ORDERS,$order_result);
		$insert_id=tep_db_insert_id();
		// update to order products
		$sql_array=array(	"orders_id"=>$insert_id,
							"products_id"=>$subscription_result["subscription_id"],
							"products_model"=>"",
							"products_name"=>$subscription_result["subscription_name"],
							"products_price"=>$subscription_result["subscription_costs"],
							"final_price"=>$subscription_result["subscription_costs"],
							"products_quantity"=>1,
							"products_tax"=>$subscription_result["tax_rate"],
							"products_type"=>"S",
							"events_type"=>"",
							"waitlist_type"=>"",
							"events_fees"=>0,
							"waitlist_orders_id"=>0
							);
		tep_db_perform(TABLE_ORDERS_PRODUCTS,$sql_array);
  
  		// update the order total
		reset($total_class);
		//while(list($key,)=each($total_class)){
		foreach (array_keys($total_class) as $key)
			{
			$val_amount=$subscription_result[$key];
			$sql_data_array = array('orders_id' => $insert_id,
									'title' => $total_class[$key]['title'],
									'text' => $currencies->format($val_amount),
									'value' => $val_amount, 
									'class' => $key, 
									'sort_order' => $total_class[$key]['sort_order']);
			tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
		}
		$balance[$customers_id]["drawn_total"]=$temp_drawn_amount;
		// update the order status history
		$sql_array=array(	"orders_id"=>$insert_id,
							"orders_status_id"=>2,
							"date_added"=>$now,
							"customer_notified"=>'1',
							"comments"=>"Created Order Using Automatic Wallet Payment",
							"user_added"=>"Auto Wallet"
		);
		tep_db_perform(TABLE_ORDERS_STATUS_HISTORY,$sql_array);
		//update the wallet_history about usage of amount
		$sql_array=array(	"orders_id"=>$insert_id,
							"drawn_date"=>$now,
							"customers_id"=>$customers_id,
							"amount"=>$subscription_result["ot_total"]
						);
		tep_db_perform(TABLE_WALLET_HISTORY,$sql_array);
		tep_send_subscription_order_email($insert_id);
	}
	
	function tep_send_wallet_balance_reminder($customers_id,$balance){
		global $currencies,$curdate,$now;

		$customer_sql="SELECT c.customers_firstname,c.customers_lastname,c.customers_dob,c.customers_email_address,c.customers_telephone,
						c.customers_fax,a.entry_street_address,a.entry_city,a.entry_suburb,a.entry_postcode,a.entry_state,a.entry_country_id,a.entry_zone_id
						from " . TABLE_CUSTOMERS . " c," . TABLE_ADDRESS_BOOK . " a where a.address_book_id=c.customers_default_address_id and c.customers_id='" . $customers_id . "'";


		$customer_query=tep_db_query($customer_sql);
		
		if (tep_db_num_rows($customer_query)<=0){
			error_notification("Wallet Balance =>Customer (Customer Id:" . $customers_id . ") Not Found");
			return;
		}
		$customer_result=tep_db_fetch_array($customer_query);

								
		$merge_details=array(	TEXT_FN=>$customer_result['customers_firstname'],
								TEXT_LN=>$customer_result['customers_lastname'],
								TEXT_DF=>format_date($customer_result['customers_dob']),
								TEXT_EM=>$customer_result['customers_email_address'],
								TEXT_TN=>$customer_result['entry_telephone_number'],
								TEXT_FX=>$customer_result['entry_fax'],
								TEXT_SA=>$customer_result['entry_street_address'],
								TEXT_SU=>$customer_result['entry_suburb'],
								TEXT_PC=>$customer_result['entry_postcode'],
								TEXT_CT=>$customer_result['entry_city'],
								TEXT_ST=>tep_get_zone_name($customer_result["entry_country_id"],$customer_result["entry_zone_id"],$customer_result['entry_state']),
								TEXT_CY=>tep_get_country_name($customer_result['entry_country_id']),
								TEXT_RE=>'',
								TEXT_IV=>'',
								TEXT_UN=>'',
								TEXT_PT=>'',
								TEXT_WAD=>'',
								TEXT_WCB=>$currencies->format($balance)
							);
		
       $send_details=array(
	   						array('to_name'=>$customer_result['customers_firstname'] . ' ' . $customer_result['customers_lastname'],
	                                     'to_email'=>$customer_result['customers_email_address'],
										 'from_name'=>STORE_OWNER,
										 'from_email'=>STORE_OWNER_EMAIL_ADDRESS
							)
						);
		// store in the message history
		$sql_array=array(
							"send_date"=>$now,
							"customers_id"=>$customers_id,
							"message_mode"=>"E",
							"message_type"=>"WBW"
						);
						
		tep_db_perform(TABLE_WALLET_MESSAGES_HISTORY,$sql_array);
		tep_send_default_email("WBW",$merge_details,$send_details);
	}
	function error_notification($error){
		global $HTTP_SERVER_VARS;
		$error_array = array(
							'cron_jobs_error_action' => basename($HTTP_SERVER_VARS['PHP_SELF']),
							'cron_jobs_error_text' => $error,
							'cron_jobs_error_date' => 'now()'
							);
		tep_db_perform(TABLE_CRON_JOBS_ERROR,$error_array);
	}
?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
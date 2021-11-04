<?php
/*pseudo_cron.php
(c)2012 by Graeme Tyson /osconcert
this file is intended to mimic a cronjob by checking the
server timestamp for the file timestamp.php and, if found to be too old
run some code (or call another file)*/


// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

include_once(DIR_WS_INCLUDES . 'application_top.php');

if(ENABLE_CANCEL_CRON == 'true')
{

	global $timestamp_filename;
	// define the filename for the timestamp - anything you like just needs to be in a writeable folder
	$timestamp_filename= DIR_WS_INCLUDES .'pseudo_cron_timestamp.txt';

	// enter the time in seconds between runs of the 'cron' job
	$time_in_seconds = EXPIRY_CRON;
	//$time_in_seconds = 10;//10 second testing

	//if it exists then check the time it was created - if not then create
	if (file_exists($timestamp_filename))
	{
		$timedif = @(time() - filemtime($timestamp_filename));
			if ($timedif > $time_in_seconds ) 
			{
				if (SHOW_TIME_DIFF == 'yes')
				{
				echo 'timediff='.$timedif. '    '.getServerDateCron(true);
				}
			// reset the file time and run your cronjob
			updateTimeStamp($timestamp_filename,cronjob());
			}
			else
			{
			echo 'Testing - file time not exceeded';
			}
	}
	else
	{
		updateTimeStamp($timestamp_filename,'');//echo'file created';
	}
}
		
	//------------------------ functions ---------------------------
		//opens a file and writes the text pseudo-cron timestamp file to it - if file not found it will attempt to create it
		function updateTimeStamp($timestamp_filename, $function_to_run)
		{
			
			if ($fp = @fopen($timestamp_filename, 'w')) {
				flock($fp,LOCK_EX); //lock the file to grant exclusive access
				fputs($fp, 'pseudo-cron timestamp file');
				$function_to_run; // run any functions
				flock($fp, LOCK_UN); 
				fclose($fp);
				}else{echo 'unable to create pseudo-cron-timestamp file in the folder selected - please edit the file includes/pseudo_cron.php';}
		}
		//our function to run
		
		function cronjob()
		{
		// define ('CRON_RESTOCK_EXPIRY', '1');  # expiry time in hours (or minutes ...see below)
		// define ('CRON_RESTOCK_STATUS', '1');  # match to the PayPal Pending order status number
		// define ('CRON_CANX_STATUS', '7');      # match to the 'Cancelled' order status number

	if('CRON_RESTOCK_STATUS' == 3){
		
		exit();
		
	}

	$time_now =  date('Y-m-d H:i:s',getServerDate(false));

	//$expiry = date('Y-m-d H:i:s',strtotime("-". CRON_RESTOCK_EXPIRY ." hours",strtotime($time_now)));
	$expiry = date('Y-m-d H:i:s',strtotime("-". CRON_RESTOCK_EXPIRY ." minutes",strtotime($time_now)));


			try{
				 
			  $bor_query = tep_db_query("select orders_id, date_purchased from " . TABLE_ORDERS . " 
														  where  orders_status = '".CRON_RESTOCK_STATUS."'
														  and date_purchased < '". $expiry . "'");
				if (tep_db_num_rows($bor_query) > 0 ) 
				{ 
				

				   $number = 1;
				   set_time_limit(250); //set script timeout in case it goes over 30 seconds
				   
				while ($bor_results = tep_db_fetch_array($bor_query))
				{
					canx_order($bor_results['orders_id'],'Order automatically restocked');
				
					$number ++;
				}
				}else{
			
				}
			}catch (Exception $e) 
			{
				
				//echo any error
				
				#echo '  ERROR: ' .$e;
			}

	#echo'Done';

		

			
			}
							
	// changed verion of get serverdate to include H i S
	function getServerDateCron($time=false)
	{

		$offset=(float)EVENTS_SERVER_DATE_OFFSET;
		if($offset>0)
		{
			if(strpos($offset,'.')>0)
			{
				$cur_offset_time = mktime(date('H')+abs($offset),date('i')+30,date('s'),date('m'),date('d'),date('y'));
				if($time)
					return date('Y-m-d H:i:s',$cur_offset_time);
				else
					return date('Y-m-d',$cur_offset_time);
			}else{
				$cur_offset_time = mktime(date('H')+abs($offset),date('i'),date('s'),date('m'),date('d'),date('y'));
				if($time)
					return date('Y-m-d H:i:s',$cur_offset_time);
				else
					return date('Y-m-d',$cur_offset_time);
			}
		}else{
			if(strpos($offset,'.')>0)
			{
				$cur_offset_time = mktime(date('H')-abs($offset)+1,date('i')-30,date('s'),date('m'),date('d'),date('y'));
				if($time)
					return date('Y-m-d H:i:s',$cur_offset_time);
				else
					return date('Y-m-d',$cur_offset_time);
			}else{
				$cur_offset_time = mktime(date('H')-abs($offset),date('i'),date('s'),date('m'),date('d'),date('y'));
				if($time)
					return date('Y-m-d H:i:s',$cur_offset_time);
				else
					return date('Y-m-d',$cur_offset_time);
			}
		}
	}
		///// restock function
	function canx_order($order_id, $note='')
	{


		// if we have the order_id then do stuff
		if (tep_not_null($order_id)) 
		{

			//grab customers details from order
			$cust_query = tep_db_query("select customers_name, customers_email_address from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
			if (tep_db_num_rows($cust_query) ) 
			{
				$cust_query_result = tep_db_fetch_array($cust_query);
				$cust_name = $cust_query_result['customers_name'];
			}
			if(SEND_CRON_CANCEL_NOTIFICATION == 'yes')
			{
					// send the email to the customer
					tep_mail($cust_query_result['customers_name'], $cust_query_result['customers_email_address'], 'Your order '.$order_id . ' has been canceled',
					'Dear '.$cust_query_result['customers_name']
					.'   Your order at '. STORE_NAME. ' has been canceled.  '
					. STORE_OWNER , STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			}		
			//change the order status
			$sql_data_array = array('orders_status' => CRON_CANX_STATUS,
			'customers_name'=> 'Cancelled::'.$cust_name)
			;
			tep_db_perform(TABLE_ORDERS, $sql_data_array, "update", "orders_id='".$order_id."'");
			
			$sql_data_array = array('orders_id' => $order_id,
			'orders_status_id' => CRON_CANX_STATUS,
			'date_added' => date('Y-m-d H:i:s',getServerDate(false)),
			'customer_notified' => 0,
			'comments' => $note );
			tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
			
			include_once('includes/functions/ga_tickets.php');
			
			//reset the products quantity and status
			//n.b. that in the orders_products table that the products_type field does not reflect the products_type field in
			//the products table - you need to use events_type
			
			$order_query = tep_db_query("select products_id, products_quantity, events_type from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $order_id. "'");
			while ($order = tep_db_fetch_array($order_query)) 
			{
				
				if ($order['events_type']=='F')
						{
						$family_ticket=$order['products_quantity']*FAMILY_TICKET_QTY;
						}else{
						$family_ticket=$order['products_quantity'];
						}
				
				
				tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $family_ticket . ", products_ordered = products_ordered - " . $order['products_quantity'] . ", products_status='1' where products_id = '" . (int)$order['products_id'] . "'");
				tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set orders_products_status = '".CRON_CANX_STATUS."' where orders_id = '" . (int)$order_id ."'");
				
				//where products_id = '" . (int)$order['products_id'] . "'");

				if (function_exists('ga_check_process_restock'))
				{
				ga_check_process_restock((int)$order['products_id'], $order['products_quantity'], $order['events_type']);	
				}													

			}

			//give the order total a value of 0.00
			tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $order_id. "'");
			tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set products_quantity = '0' where orders_id = '" . $order_id . "'");
			tep_db_query("insert into " . TABLE_ORDERS_TOTAL. " (orders_id, title, text, value, class, sort_order) values ('" . $order_id . "', 'Total', '0.00', '0','ot_total', '99')");
			

		   return false;
		}
	}
	?>
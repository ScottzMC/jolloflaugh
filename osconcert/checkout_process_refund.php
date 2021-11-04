<?php
/*
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
	https://www.osconcert.com
	
	Copyright (c) 2009-2020 osConcert
	Released under the GNU General Public License
*/

// Set flag that this is a parent file
	define( '_FEXEC', 1 );


 include('includes/application_top.php');
 
//attempted access by non BoxOffice
if(!$_SESSION['customer_country_id']==999 || !isset($_SESSION['box_office_refund'] )){exit('..');} 

   
	$payment_return1=$FREQUEST->getvalue('payment_return1');
	$payment_return2=$FREQUEST->getvalue('payment_return2');
	
	include(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_CHECKOUT_PROCESS);
// load selected payment module
	require(DIR_WS_CLASSES . 'payment.php');

	$payment=&$FSESSION->getobject('payment');
	$payment_modules = new payment($payment);
		
// load the selected shipping module
	require(DIR_WS_CLASSES . 'shipping.php');
	$shipping=&$FSESSION->getobject('shipping');
	$shipping_modules = new shipping($shipping);
	
	require(DIR_WS_CLASSES . 'order.php');
	$order = new order;

	
	include_once('includes/functions/ga_tickets.php');


	if($FSESSION->is_registered('sendto_array'))
	 	$order->delivery=$FSESSION->get("sendto_array");
	if($FSESSION->is_registered('billto_array'))
		$order->billing=$FSESSION->get("billto_array");

	require(DIR_WS_CLASSES . 'order_total.php');
	$order_total_modules = new order_total;

	$order_totals = $order_total_modules->process();

	 $total_weight = $cart->show_weight();
	 $ip_address= $FREQUEST->servervalue('REMOTE_ADDR');

	// load the before_process function from the payment modules
	$payment_modules->before_process();

	// BOF: WebMakers.com Added: Downloads Controller
		   $sql_data_array = array(
							'customers_id' => $FSESSION->get('customer_id'),
							'customers_language' => $language,
							'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
							'customers_username' => $order->customer['username'],
							'customers_company' => $order->customer['company'],
							'customers_street_address' => $order->customer['street_address'],
							'customers_suburb' => $order->customer['suburb'],
							'customers_city' => $order->customer['city'],
							'customers_postcode' => $order->customer['postcode'], 
							'customers_state' => $order->customer['state'], 
							'customers_country' => $order->customer['country']['title'], 
							'customers_telephone' => $order->customer['telephone'], 
							'customers_email_address' => $order->customer['email_address'],
							'customers_second_telephone'=>$order->customer['second_telephone'],
							'customers_second_email_address'=>$order->customer['second_email_address'],
							'customers_fax'=>$order->customer['fax'],
							'customers_address_format_id' => $order->customer['format_id'], 
							'customers_dummy_account' => 0,
							'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'], 
							'delivery_company' => $order->delivery['company'],
							'delivery_email' => $order->delivery['customer_email'],//new added 14-08-17
							'delivery_street_address' => $order->delivery['street_address'], 
							'delivery_suburb' => $order->delivery['suburb'], 
							'delivery_city' => $order->delivery['city'], 
							'delivery_postcode' => $order->delivery['postcode'], 
							'delivery_state' => $order->delivery['state'], 
							'delivery_country' => ($order->delivery['country_id'] > 0)?(get_order_countryname($order->delivery['country_id'])):$order->delivery['country']['title'], 
							'delivery_address_format_id' => 1, 
							'billing_name' => 'Box Office Refund', 
							'billing_email' => $order->billing['customer_email'],	//new added 14-08-17
							'billing_company' => $order->billing['company'],
							'billing_street_address' => $order->billing['street_address'], 
							'billing_suburb' => $order->billing['suburb'], 
							'billing_city' => $order->billing['city'], 
							'billing_postcode' => $order->billing['postcode'], 
							'billing_state' => $order->billing['state'], 
							'billing_country' => ($order->billing['country_id'] > 0)?(get_order_countryname($order->billing['country_id'])):$order->billing['country']['title'], 
							'billing_address_format_id' => 1,
							'payment_method' => $order->info['payment_method'],
							'reference_id' => $FSESSION->get('referenceID'),
							'payment_info' => $FSESSION->get('payment_info'),
							'cc_type' => $order->info['cc_type'], 
							'cc_owner' => $order->info['cc_owner'], 
							'cc_number' => $order->info['cc_number'], 
							'cc_expires' => $order->info['cc_expires'], 
							'cc_cvv_number'=> $order->info['cc_cvv_number'],
							'date_purchased' => date('Y-m-d H:i:s',getServerDate(false)), 
							'last_modified' => date('Y-m-d H:i:s',getServerDate(false)),
							'orders_status' => 5, //<---------- refunded
							'currency' => $order->info['currency'], 
							'currency_value' => $order->info['currency_value'],
							'owd_cost' =>$order->info['owd_cost'],
							'payment_return1'=>$payment_return1,
							'payment_return2'=>$payment_return2,
							'shipping_weight'=>$total_weight,
							'shipping_method'=>$order->info['shipping_method'],
							'shipping_module' => $shipping['id'], 
							'ip_address'=> $ip_address
							);
	
	tep_db_perform(TABLE_ORDERS, $sql_data_array);
	
	$reference=$order->info['reference_id'] . $FSESSION->get('referenceID');
	
	$insert_id = tep_db_insert_id();
	
	if ($FREQUEST->getvalue('rstor')!='')
	{
		tep_update_payment_response_code(tep_db_prepare_input($FREQUEST->getvalue('rstor')),$insert_id);
	} else if ($GLOBALS["PAYMENT_RESPONSE"]!="" && $GLOBALS[$payment]->order_status>0){
		tep_write_payment_response($insert_id,$GLOBALS["PAYMENT_RESPONSE"]);
	}
	
	if($order->info['order_status']==3) //processing
	{
  		  tep_db_query("update " . TABLE_ORDERS . " set date_paid = '" . getServerDate(true) . "' where orders_id = '" . (int)$insert_id . "'");
	}
	//save multiple customers in customers and orders table
	$result_array=array();
		
	//refund mode - note that the order totals are not calculated alongside the ticket totals 
	//so we need to 'negative' the values now
	// belay the above
	for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) 
	{
    	$sql_data_array = array(
								'orders_id' => $insert_id,
								'title' => $order_totals[$i]['title'],
								'text' => $order_totals[$i]['text'],
								'value' => $order_totals[$i]['value'],
								'class' => $order_totals[$i]['code'],
								'sort_order' => $order_totals[$i]['sort_order']
								);
		tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
	}
	//$customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
// initialized for the email confirmation
	$products_ordered = '<table width="100%">';
	$products_ordered.= '<tr height="30" class="textTableSubHead"><td width="20"><b>' . TEXT_QUANTITY .'</b></td><td colspan="2"><b>'.TEXT_PRODUCTS_NAME. '</b></td><td></td><td align="right" width="100"><b>' .TEXT_PRICE. '</b></td></tr>';
	$subtotal = 0;
	$total_tax = 0;
	$products_type='';
	$events_type='';
	$refund_comments='';

   $new_ot_subtotal_value = 0;
   $new_ot_total_value = 0;



	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) 
	{  
				//box office refund
				//1 find order relating to the original purchase looking for the most recent entry in the orders-products table
				//2 added products_sku=1 so it ONLY picks up normal tickets
				//$products_orig_query=tep_db_query("select op.orders_id, o.orders_status,op.final_price,  op.products_quantity,  op.products_name from " . TABLE_ORDERS_PRODUCTS . " op, ".TABLE_ORDERS." o  where op.products_id = '" . tep_get_prid($order->products[$i]['id']) . "' and o.orders_id=op.orders_id and op.products_sku = '1' order by op.orders_products_id DESC limit 1");
				$products_orig_query=tep_db_query("select op.orders_id, o.orders_status,op.final_price,  op.products_quantity,  op.products_name from " . TABLE_ORDERS_PRODUCTS . " op, ".TABLE_ORDERS." o  where op.products_id = '" . tep_get_prid($order->products[$i]['id']) . "' and o.orders_id=op.orders_id and op.products_sku = '1'   order by op.orders_products_id DESC limit 1");
				if (tep_db_num_rows($products_orig_query)) 
				{ 
				     $orig_order_values = tep_db_fetch_array($products_orig_query);
					 //get the new quantity
					 $new_quantity= $orig_order_values['products_quantity']-$order->products[$i]['qty'];
					 if($new_quantity < 0){$new_quantity=0;}
					 //relabel the item
					 tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set products_quantity ='".$new_quantity."' , products_name = 'REFUNDED:".$orig_order_values['products_name']."' where orders_id = '" . $orig_order_values['orders_id']. "' and products_id='" . tep_get_prid($order->products[$i]['id'])."'");
					 //note the original order
					 
					 //add comments to original
						$sql_data_array = array(
							'orders_id' => $orig_order_values['orders_id'],
							'orders_status_id' => $orig_order_values['orders_status'],
							'date_added' => date('Y-m-d H:i:s',getServerDate(false)),
							'customer_notified' => 0,
							'comments' => $orig_order_values['products_name'].' refunded - order '.$insert_id.' refers',
							'user_added'=>"Box Office Refund"
							);
	                   tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
					//adjust the original order totals
					//get the values		
					    $order_new=new order($orig_order_values['orders_id']);
						$total_array=$order_new->totals;
						
					//we now have the original order_total table values as an array
					//work through these and adjust only for ot_total and ot_subtotal
					for ($k=0, $kn=sizeof($total_array); $k<$kn; $k++) 
					{
						if( $total_array[$k]['class'] =='ot_subtotal')
						{     
						   $orig_value=$total_array[$k]['value'];
						   $new_value = $orig_value -(  $order->products[$i]['qty'] * $orig_order_values['final_price']);
						   $new_text = $currencies->format($new_value, true, $order_new->info['currency'], $order_new->info['currency_value']); 
						//   echo '<br>subtotal value'.$orig_value;
						//   echo '<br>new value'.$new_value;
						//   echo '<br>new_text'.$new_text;
						 tep_db_query("update " . TABLE_ORDERS_TOTAL . " set value ='".$new_value."' , text = '".$new_text."' where orders_id = '" . $orig_order_values['orders_id']. "' and class='ot_subtotal'");
						  }//end ot_subtotal
						elseif( $total_array[$k]['class'] =='ot_total')
						{
							$orig_value=$total_array[$k]['value'];
							$new_value = $orig_value -(  $order->products[$i]['qty'] * $orig_order_values['final_price']);
							$new_text = '<b>'.$currencies->format($new_value, true, $order_new->info['currency'], $order_new->info['currency_value']).'</b>'; 
							tep_db_query("update " . TABLE_ORDERS_TOTAL . " set value ='".$new_value."' , text = '".$new_text."' where orders_id = '" . $orig_order_values['orders_id']. "' and class='ot_total'");
						}//end ot_total
					}
					
				//exit();
					
					
	                //comments for this order
	                     $order->info['comments'].=$orig_order_values['products_name'].' refunded - order '.$orig_order_values['orders_id'].' refers.  ';
					
				 }
						  
						  
			// 3 Element type = P = allocated seating AND GA
	
	
		if ($order->products[$i]['element_type']=='P') 
		{
		
			if (DOWNLOAD_ENABLED == 'true') 
			{
				$stock_query_raw = "SELECT products_quantity,products_image_1,pad.products_attributes_filename
									FROM " . TABLE_PRODUCTS . " p
									LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
									 ON p.products_id=pa.products_id
									LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
									 ON pa.products_attributes_id=pad.products_attributes_id
									WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
	// Will work with only one option for downloadable products
	// otherwise, we have to build the query dynamically with a loop
					$products_attributes = $order->products[$i]['attributes'];
					if (is_array($products_attributes)) {
					$stock_query_raw .= " AND pa.options_id = '" . tep_db_input($products_attributes[0]['option_id']) . "' AND pa.options_values_id = '" . tep_db_input($products_attributes[0]['value_id']) . "'";
					}
					$stock_query = tep_db_query($stock_query_raw);
			} else 
			{
					$stock_query = tep_db_query("select p.products_image_1,p.products_quantity from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where p.products_id = pa.products_id and p.products_id = '" . tep_db_input(tep_get_prid($order->products[$i]['id'])) . "' and pa.options_id = '" . tep_db_input($products_attributes[0]['option_id']) . "' AND pa.options_values_id = '" . tep_db_input($products_attributes[0]['value_id']) . "'");
					//$stock_query = tep_db_query("select products_image_1,products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
			}
			if (tep_db_num_rows($stock_query) > 0) 
			{
				$stock_values = tep_db_fetch_array($stock_query);
// do not decrement quantities if products_attributes_filename exists
				if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
					$stock_left = $stock_values['products_quantity'] + $order->products[$i]['qty'];
				} else {
					$stock_left = $stock_values['products_quantity'] ;
				}
				
        		//tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
				
				if ( ($stock_left >0) && (STOCK_ALLOW_CHECKOUT == 'false') ) 
				{
					tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '1' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
				}
      		}
			
						// for decrement quantity in products table
			$quan=tep_db_query("select products_quantity from " .  TABLE_PRODUCTS . " p where products_id = '" . tep_get_prid($order->products[$i]['id']) . "' ");
			if (tep_db_num_rows($quan) > 0) 
			{
				$quan_values = tep_db_fetch_array($quan);
				$quan_left=$quan_values['products_quantity'] + $order->products[$i]['qty'];
				  tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $quan_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
					if ( ($quan_left >0) ) 
					{
					tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '1' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
				}

				}
				
				//4 the next function (ga_check_process) normally decreases a GA Product 
				//ammended to ga_check_process_refund
                //run only if $order->products[$i]['sku']==9
				if($order->products[$i]['sku']==9)
				{
				ga_check_process_refund(tep_get_prid($order->products[$i]['id']), $order->products[$i]['qty'], $order->products[$i]['element_type'],$order->products[$i]['old_orders_id'],$insert_id);
				}

				
				
    	}

		
		// prepare for heading
		###################################################################
		$id=$order->products[$i]['id'];
		require_once(DIR_WS_INCLUDES . 'functions/categories_lookup.php');
		#####################################################
        // call the new function
        $type = $products[$i]['product_type'];
        
        list($heading_name, $heading_venue,  $heading_date, $heading_time) = categories_lookup();	
		

		########################################################### v6
		// Update products_ordered (for bestsellers list)
		if ($order->products[$i]['element_type']=='P')
		{
			tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered - " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");


            //change the order totals


                    $new_ot_subtotal_value +=( $order->products[$i]['qty'] * ($orig_order_values['final_price'] ));
                    $new_ot_total_value +=( $order->products[$i]['qty'] * ($orig_order_values['final_price'] ));


            //change price back to original order
            $order->products[$i]['final_price'] = $orig_order_values['final_price'];

			$sql_data_array = array(
							'orders_id' => $insert_id, 
							'products_id' => tep_get_prid($order->products[$i]['id']), 
							'products_model' => $order->products[$i]['model'], 
							'products_season' => $order->products[$i]['products_season'], 
							'products_name' => $order->products[$i]['name'], 
							'products_price' => $order->products[$i]['price'], 
							'final_price' => $order->products[$i]['final_price'], 
							'products_tax' => $order->products[$i]['tax'], 
							'products_quantity' => $order->products[$i]['qty'],
							//box office for GA
							//'waitlist_orders_id' => $order->products[$i]['qty'],
							//box office
							'events_type'=>$order->products[$i]['events_type'],
							'products_type'=>$order->products[$i]['element_type'],
							'products_sku'=>$order->products[$i]['sku'],
							'is_printable'=>$order->products[$i]['is_printable'],
							'orders_products_status'=>$order->info['order_status'],
							'support_packs_type'=>$order->products[$i]['support_packs_type'], //Support Packs Type
							'discount_type'=>$order->products[$i]['discount_type'],
							'discount_id'=>(int)$order->products[$i]['discount_id'],
							'discount_text'=>$order->products[$i]['discount_text'],
							//'events_id'=>$order->products[$i]['events_id'],
							'categories_name'=> $heading_name,
							'concert_venue'=> $heading_venue,
							'concert_date'=> $heading_date,
							'concert_time'=> $heading_time
							);
    		tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
    		$order_products_id = tep_db_insert_id();
			//update the original
			//product mode download type------------
			if ($order->products[$i]['product_mode']=="V") 
			{ //virtual type
	//			$unique_id=md5(date('m/d/y H:i:s'));
				
				 $unique_id=create_unique_id();		
				// $unique_id=md5(time());
				//'download_maxdays' => $order->products[$i]['download_last_date'], 
				 $sql_data_array = array('orders_products_download_id'=>$unique_id,
				  						  'orders_id' => $insert_id, 
										  'orders_products_id' => $order_products_id, 
										  'orders_products_filename' => $order->products[$i]['download_link'], 
										  'download_maxdays' => $order->products[$i]['download_no_of_days'], 
										  'download_count' => $order->products[$i]['downloads_per_customer']);
				  tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
			}

			//=====================================
			
			
		}


   
		 // send email about ordered events to user
	$order_total_modules->update_credit_account($i);//ICW ADDED FOR CREDIT CLASS SYSTEM
//------insert customer choosen option to order--------
		$attributes_exist = '0';
		// $products_ordered_attributes = '';
		// if (isset($order->products[$i]['attributes'])) 
		// {
			// $attributes_exist = '1';
			// $attribute_id="";
			// for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) 
			// {


				// $attributes = tep_db_query("select popt.products_options_id,popt.products_options_name,poval.products_options_values_id,poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . (int)$order->products[$i]['id'] . "' and pa.options_id = '" . (int)$order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . (int)$order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . (int)$FSESSION->get('languages_id') . "' and poval.language_id = '" . (int)$FSESSION->get('languages_id') . "'");
				// $attributes_values = tep_db_fetch_array($attributes);

				// if($attributes_values['options_values_price']=="")$attributes_values['options_values_price'] = 0;
				// $sql_data_array = array(
										// 'orders_id' => $insert_id, 
										// 'orders_products_id' => $order_products_id, 
										// 'products_options' => $attributes_values['products_options_name'],
										// 'products_options_values' => $attributes_values['products_options_values_name'], 
										// 'options_values_price' => $attributes_values['options_values_price'], 
										// 'price_prefix' => $attributes_values['price_prefix'],
										// 'products_options_id'=>$attributes_values['products_options_id'],
										// 'products_options_values_id'=>$attributes_values['products_options_values_id']
										// );
				// tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);


				// if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) 
				// {
				  // $sql_data_array = array('orders_id' => $insert_id, 
										  // 'orders_products_id' => $order_products_id, 
										  // 'orders_products_filename' => $attributes_values['products_attributes_filename'], 
										  // 'download_maxdays' => $attributes_values['products_attributes_maxdays'], 
										  // 'download_count' => $attributes_values['products_attributes_maxcount']);
				  // tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
				// }
        		// $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ': ' .$attributes_values['products_options_values_name']." ". $currencies->format(tep_add_tax($attributes_values['options_values_price'],$order->products[$i]['tax']))."<br>";
				// if($attribute_id!="")
					// $attribute_id.="-";
				// $attribute_id.=$order->products[$i]['attributes'][$j]['option_id'] . "{" . $order->products[$i]['attributes'][$j]['value_id'] . "}";
      		
      		// }
			// tep_db_query("update " . TABLE_PRODUCTS_STOCK . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . ",products_quantity=products_quantity - " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "' and attributes_id = '" . tep_get_sorted_attribute_ids($attribute_id) . "'");
			// tep_db_query("update " . TABLE_PRODUCTS_STOCK . " set attribute_status=0 where products_quantity<=0 and products_id=" . tep_get_prid($order->products[$i]['id']) . " and attributes_id = '" . tep_get_sorted_attribute_ids($attribute_id) . "'");
    	  // }

//------insert customer choosen option eof ----
		$total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
		$total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
		$total_cost += $total_products_price;
		$flag_product=false;
		if ($order->products[$i]['element_type']=='P'){}
		if ($products_type!="M"){}
		if ($order->products[$i]['element_type']=='V'){}

	}//end of products loop ########################################################################


        $new_text = $currencies->format($new_ot_subtotal_value, true, $order->info['currency'], $order->info['currency_value']);

        tep_db_query("update " . TABLE_ORDERS_TOTAL . " set value ='".$new_ot_subtotal_value."' , text = '".$new_text."' where orders_id = '" . $insert_id. "' and class='ot_subtotal'");

        $new_text = '<b>'.$currencies->format($new_ot_total_value, true, $order->info['currency'], $order->info['currency_value']).'</b>';

        tep_db_query("update " . TABLE_ORDERS_TOTAL . " set value ='".$new_ot_total_value."' , text = '".$new_text."' where orders_id = '" . $insert_id . "' and class='ot_total'");


	
	$products_ordered.="</table>";

	

		$status=5;//<---------- refunded
	


	$order->info['id']=$insert_id;
	
	$sql_data_array = array(
							'orders_id' => $insert_id,
							'orders_status_id' => $status,
							'date_added' => date('Y-m-d H:i:s',getServerDate(false)),
							'customer_notified' => 0,
							'comments' => 'Box Office Refund: '.$order->info['comments'] . $FSESSION->get(''),

							'user_added'=>"box office"
							);
	tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

	$order_total_modules->apply_credit();//ICW ADDED FOR CREDIT CLASS SYSTEM

	require(DIR_WS_CLASSES . 'xml_parser.php');
	
	$parser=new xml_parser_class;
	$parser->store_positions=0;
	$parser->case_folding=0;
	$parser->target_encoding="ISO-8859-1";
	$parser->simplified_xml=0;
	$parser->fail_on_non_simplified_xml=0;

	// lets start with the email confirmation
	//Add Extra Fields
	$field_1=$order->info['field_1'] . $FSESSION->get('');
	$field_2=$order->info['field_2'] . $FSESSION->get('');
	$field_3=$order->info['field_3'] . $FSESSION->get('');
	$field_4=$order->info['field_4'] . $FSESSION->get('');
	$other=$order->info['other'] . $FSESSION->get('');
	//Add Extra Fields end
	$comments=$order->info['comments'] . $FSESSION->get('');
				
	if ($products_type=='M' || $products_type=='P'){
	$merge_details=array();
	$send_details=array();
	// order details
	$merge_details[ORDR_NO]=$insert_id;
	
	if(!isset($_COOKIE['customer_is_guest']))
	{ //email for PWA	
	$merge_details[ORDR_OL]='<a href="'.tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false).'" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">'."Order Invoice Link".'</a>';
	}
	else
	{
	$merge_details[ORDR_OL]='';
	}
	
	$merge_details[ORDR_OP]=strftime(DATE_FORMAT_LONG);
	//$merge_details[ORDR_OP]=getServerDate(true);
	//Add Extra Fields
	
	$merge_details[ORDR_OM]=(($field_1)?'<br>'.FIELD_1.' ' . $field_1:'');
	$merge_details[ORDR_OM].=(($field_2)?'<br>'.FIELD_2.' ' . $field_2:'');
	$merge_details[ORDR_OM].=(($field_3)?'<br>'.FIELD_3.' ' . $field_3:'');
	$merge_details[ORDR_OM].=(($field_4)?'<br>'.FIELD_4.' ' . $field_4:'');
	$merge_details[ORDR_OM].=(($other)?'<br>'.FIELD_5.' ' . $other:'');
	//Add Extra Fields end
	$merge_details[ORDR_OM].=(($comments)?TEXT_ORDER_COMMENTS .' '. $comments:'');
	
	//add shipping placeholder
	define("ORDR_SM","Shipping_Method");
	$shipping_method=$order->info['shipping_method'];
	$merge_details[ORDR_SM]=$shipping_method;
	
	//Bank Transfer
	define("ORDR_BDM","Bank_Deposit_Message");//Bank Deposit Message as place holder %%Bank_Deposit_Message%%
	if($payment_modules->selected_module =='bank_transfer')
	{
	$merge_details[ORDR_BDM]=MODULE_BANK_TRANSFER_INFO;
	} else {
	$merge_details[ORDR_BDM]="";
	} 
	
	
	$merge_details[ORDR_PO]=$products_ordered;
		//===get if any download links===============
		//if (!$FSESSION->is_registered('customer_is_guest')){//edit email for PWA
		if(!isset($_COOKIE['customer_is_guest']))
		{ //email for PWA		
		$downloads_query=tep_db_query("select opd.*,op.products_name from " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd," . TABLE_ORDERS_PRODUCTS . " op where opd.orders_products_id=op.orders_products_id and opd.orders_id='" . (int)$insert_id . "'");
		$download_content="<table>";
		if(tep_db_num_rows($downloads_query)>0)
		$download_content.='<tr class="textTableContent"><td class="textMain" colspan="10">' .TEXT_DOWNLOAD_LINK . '</td></tr>';
		while ($downloads = tep_db_fetch_array($downloads_query)) 
		{
			// MySQL 3.22 does not have INTERVAL
			list($dt_year, $dt_month, $dt_day) = explode('-', date('Y-m-d',strtotime(getServerDate())));
			$download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads['download_maxdays'], $dt_year);
			$download_expiry = date('Y-m-d H:i:s', $download_timestamp);
			$download_content.=' <tr class="textTableContent">';
			if ( ($downloads['download_count'] > 0) && (file_exists(DIR_FS_DOWNLOAD . $downloads['orders_products_filename'])) && ( ($downloads['download_maxdays'] == 0) || ($download_timestamp > time())) ) 
			{
			$download_content.= '            <td class="textMain" align="center"><a href="'.tep_href_link(FILENAME_DOWNLOAD, 'id=' . $downloads['orders_products_download_id'], 'SSL', false).'" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . $downloads['products_name'] .  '</a></td>' . "\n";
			} else 
			{
			$download_content.= '            <td class="textMain">' . $downloads['products_name'] . '</td>' . "\n";
			}
		  $download_content.= '            <td class="textMain" nowrap><b>' . TABLE_HEADING_DOWNLOAD_DATE . '</b>&nbsp;' . tep_date_long($download_expiry) . '</td>' . "\n" .
			   '            <td class="textMain" align="right"><b>' . $downloads['download_count'] . '</b>' .  TABLE_HEADING_DOWNLOAD_COUNT . '</td>' . "\n" .
			   '          </tr>' . "\n";
		}
		$download_content.="</table>";
		$merge_details[TEXT_DL]=$download_content;
		}else
		{
			$merge_details[TEXT_DL]='';}//end PWA
		//==============================================

	$order_totals_str="<table border='0' cellpadding='2' cellspacing='0' width='100%'>";
	
		for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) 
		{
			if ($order_totals[$i]['code']=='ot_total')
			{
				$class="textTotal";
			} else if ($i%2==0)
			{
				$class="textTableTotalE";
			} else 
			{
				$class="textTableTotalO";
			}
			if($order_totals[$i]['code']=='ot_tax')
			{
				$order_totals_str.= '<tr class="textMain" height="21"><td class="' . $class . '" align="right">' . EMAIL_TEXT_TAX . '</td><td class="' . $class . '" align="right" width="100">' . strip_tags($order_totals[$i]['text']) . "</td>";
			}else if($order_totals[$i]['code']=='ot_shipping')
			{
				$order_totals_str.= '<tr class="textMain" height="21"><td class="' . $class . '" align="right">' . EMAIL_TEXT_SHIPPING . '</td><td class="' . $class . '" align="right" width="100">' . strip_tags($order_totals[$i]['text']) . "</td>";
			}else if($order_totals[$i]['code']=='ot_total')
			{
				$order_totals_str.= '<tr class="textMain" height="21"><td class="' . $class . '" align="right">' . EMAIL_TEXT_TOTAL . '</td><td class="' . $class . '" align="right" width="100">' . strip_tags($order_totals[$i]['text']) . "</td>";
			}else if($order_totals[$i]['code']=='ot_subtotal'){
				$order_totals_str.= '<tr class="textMain" height="21"><td class="' . $class . '" align="right">' . EMAIL_TEXT_SUBTOTAL . '</td><td class="' . $class . '" align="right" width="100">' . strip_tags($order_totals[$i]['text']) . "</td>";
			} else 
			{
				$order_totals_str.= '<tr class="textMain" height="21"><td class="' . $class . '" align="right">' . $order_totals[$i]['title'] . '</td><td class="' . $class . '" align="right" width="100">' . strip_tags($order_totals[$i]['text']) . "</td>";
			}
		}
		$order_totals_str.="</table>";
		$merge_details[ORDR_OT]=$order_totals_str;
		//customer details
		$merge_details[CUST_CF]=$order->customer['firstname'];
		$merge_details[CUST_CL]=$order->customer['lastname'];
		$merge_details[CUST_CM]=$order->customer['company'];
		$merge_details[TEXT_SN]=STORE_OWNER;
		$merge_details[CUST_CT]=$order->customer['street_address'];
		$merge_details[CUST_CS]=$order->customer['suburb'];
		$merge_details[CUST_CC]=$order->customer['city'];
		$merge_details[CUST_CP]=$order->customer['postcode'];
		$merge_details[CUST_CE]=$order->customer['state'];
		$merge_details[CUST_CU]=($order->customer['country_id'] > 0)?(get_order_countryname($order->customer['country_id'])):$order->customer['country']['title'];
		$merge_details[CUST_CO]=$order->customer['telephone'];
		$merge_details[CUST_CA]=$order->customer['email_address'];
	  
	//Billing details
	$merge_details[BILL_NA]=$title . ' ' . $order->billing['firstname'] . ' ' . $order->billing['lastname'];
	$merge_details[BILL_CM]=$order->billing['company'];
	$merge_details[BILL_CT]=$order->billing['street_address'];
	$merge_details[BILL_CS]=$order->billing['suburb'];
	$merge_details[BILL_CC]=$order->billing['city'];
	$merge_details[BILL_CP]=$order->billing['postcode'];
	$merge_details[BILL_CE]=$order->billing['state'];
	$merge_details[BILL_CU]=($order->billing['country_id'] > 0)?(get_order_countryname($order->billing['country_id'])):$order->billing['country']['title'];

	//Delivery details
	$merge_details[DELI_NA]=$title . ' ' . $order->delivery['firstname'] . ' ' . $order->delivery['lastname'];
	$merge_details[DELI_CM]=$order->delivery['company'];
	$merge_details[DELI_CT]=$order->delivery['street_address'];
	$merge_details[DELI_CS]=$order->delivery['suburb'];
	$merge_details[DELI_CC]=$order->delivery['city'];
	$merge_details[DELI_CP]=$order->delivery['postcode'];
	$merge_details[DELI_CE]=$order->delivery['state'];
	$merge_details[DELI_CU]=($order->delivery['country_id'] > 0)?(get_order_countryname($order->delivery['country_id'])):$order->delivery['country']['title'];



	$merge_details['Store_Link']='<a href="' . tep_href_link(FILENAME_DEFAULT) . '" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . STORE_OWNER . '</a>';
	$merge_details['Telephone']=$order->customer['telephone'];
	if(!defined('TICKET_LINK_TEXT'))define('TICKET_LINK', 'Collect your tickets here');
	
		$merge_details['Store_Logo']='<img src="' . tep_href_link(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/' .COMPANY_LOGO) . '" title="' . STORE_NAME . '">';
		$merge_details['Store_Address']=STORE_NAME_ADDRESS;
		$send_details[0]['to_name']=$order->customer['firstname'] . ' ' . $order->customer['lastname'];
		$send_details[0]['to_email']=$order->customer['email_address'];
		$send_details[0]['from_name']=STORE_OWNER;
		$send_details[0]['from_email']=STORE_OWNER_EMAIL_ADDRESS;

	  // send emails to other people 
	if (SEND_EXTRA_ORDER_EMAILS_TO != '') 
	{
		$send_details[1]['to_name']="";
		$send_details[1]['to_email']=SEND_EXTRA_ORDER_EMAILS_TO;
		$send_details[1]['from_name']=STORE_OWNER;
		$send_details[1]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
	}

	if (($GLOBALS[$payment]->code=="paypalipn") || ($GLOBALS[$payment]->code=="creditguard2") || ($GLOBALS[$payment]->code=="realexredir")){
	$flag_product=false;
	}
		if($flag_product==true)
		{ 
		tep_send_default_email("PRD",$merge_details,$send_details);
		}
}

	// modified for wallet payment -start
	if ($GLOBALS[$payment]->code=="wallet"){
		$sql_wallet_array=array('drawn_date' => date('Y-m-d H:i:s',getServerDate(false)),
								'customers_id' => $FSESSION->get('customer_id'),
								'orders_id'=>$insert_id,
								'amount'=>$order->info['total']
		);
		
		tep_db_perform(TABLE_WALLET_HISTORY, $sql_wallet_array);
		$balance=tep_get_wallet_balance($FSESSION->get('customer_id'));
		$minimum_balance=tep_get_wallet_min_balance($FSESSION->get('customer_id'));
		if ($balance<$minimum_balance){
			// send wallet balance email
			$merge_details=array(	TEXT_FN=>$order->customer['firstname'],
									TEXT_LN=>$order->customer['lastname'],
									TEXT_DF=>'',
									TEXT_EM=>$order->customer['email_address'],
									TEXT_TN=>$order->customer['telephone_number'],
									TEXT_FX=>$order->customer['fax'],
									TEXT_SA=>$order->billing['street_address'],
									TEXT_SU=>$order->billing['suburb'],
									TEXT_PC=>$order->billing['postcode'],
									TEXT_CT=>$order->billing['city'],
									TEXT_ST=>$order->billing["state"],
									TEXT_CY=>$order->billing["country"]["title"],
									TEXT_RE=>'',
									TEXT_IV=>'',
									TEXT_UN=>'',
									TEXT_PT=>'',
									TEXT_WAD=>'',
									TEXT_WCB=>$currencies->format($balance)
								);
		   $send_details=array(
								array('to_name'=>$order->customer['firstname'] . ' ' . $order->customer['lastname'],
											 'to_email'=>$order->customer['email_address'],
											 'from_name'=>STORE_OWNER,
											 'from_email'=>STORE_OWNER_EMAIL_ADDRESS
								)
							);
			tep_send_default_email("WBW",$merge_details,$send_details);

			// store in the message history
			$sql_array=array(
								"send_date"=>date('Y-m-d H:i:s',getServerDate(false)),
								"customers_id"=>$FSESSION->get('customer_id'),
								"message_mode"=>"E",
								"message_type"=>"WBW"
							);
							
			tep_db_perform(TABLE_WALLET_MESSAGES_HISTORY,$sql_array);
		}
	}
	// modified for wallet payment -end

		// load the after_process function from the payment modules
		$payment_modules->after_process();
		
		$cart->reset(true);
		$FSESSION->set('error_count',0);
		// unregister session variables used during checkout
		//$FSESSION->remove('box_office_refund');
		$FSESSION->remove('box_office_reservation');//bor
		$FSESSION->remove('BoxOffice');
		$FSESSION->remove('sendto');
		$FSESSION->remove('billto');
		$FSESSION->remove('shipping');
		$FSESSION->remove('payment');
		$FSESSION->remove('comments');
		$FSESSION->remove('order_timestamp');
		$FSESSION->remove('receiptNo');
		$FSESSION->remove('transactionNr');
		$FSESSION->remove('coupon');
		$FSESSION->remove('ccno');
		$FSESSION->remove('gv_redeem_code');
		$FSESSION->remove('billto_array');
		$FSESSION->remove('sendto_array');
		$FSESSION->remove('paypal_ipn_started');
		//Add Extra Fields
		$FSESSION->remove('other');
		$FSESSION->remove('field_1'); 
		$FSESSION->remove('field_2');
		$FSESSION->remove('field_3');
		$FSESSION->remove('field_4');
		//Add Extra Fields end
		//ga cumulative
		//ga_kill_sessions();
		$order_total_modules->clear_posts();//ICW ADDED FOR CREDIT CLASS SYSTEM

		// BOF: Lango added for print order mod

		tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id='. $insert_id . '&payment=box_office_refund', 'SSL'));
		//tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id='. $insert_id, 'SSL'));
		// EOF: Lango added for print order mod
		require(DIR_WS_INCLUDES . 'application_bottom.php');

		function randomize() 
		{
		$salt = "abchefghjkmnpqrstuvwxyz0123456789";
		srand((double)microtime()*1000000); 
		$i = 0;
		while ($i <= 7) {
		$num = rand() % 33;
		$tmp = substr($salt, $num, 1);
		$pass = $pass . $tmp;
		$i++;
		}
		return $pass;
		}
		  
	function create_unique_id()
	{
	$random_array=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
					'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
					'0','1','2','3','4','5','6','7','8','9');
	$unique_id="";
	for($i=0;$i<8;$i++)
		$unique_quote.=$random_array
		{
			rand(0,61)
		};
	
	$chk_query=tep_db_query("select count(*) as total from " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . "  where orders_products_download_id like '$unique_id'");
	$chk_array=tep_db_fetch_array($chk_query);
	if($chk_array['total']<=0)
	{
		return $unique_quote;
	}
	else
	{
		create_unique_quote();
	}
	}
?>
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

// if the customer is not logged on, redirect them to the login page
	if (!$FSESSION->is_registered('customer_id')) 
	{
	$navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_SHOPPING_CART));
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  	}
	//osConcert - check the cart to make sure that each ticket is still available, if not then $order_succ=false and bounce cart back to shopping cart
    $order_succ=true;
   
    foreach($_SESSION['cart']->contents as $keys => $val)
	{
	$qty_check=tep_db_query("select products_quantity, product_type from ".TABLE_PRODUCTS." where products_id='".$keys."'");
	$qty_check_result=tep_db_fetch_array($qty_check);
		if($qty_check_result['product_type']=='P')
		{//ok we have a ticket
			if($qty_check_result['products_quantity']<=0)
			{
				$order_succ=false;
			}
		}
   
    }   
    if($order_succ==false)
	{
        tep_redirect(tep_href_link('shopping_cart.php', 'order_id='. $insert_id . '&payment=' . $payment, 'SSL'));       
        die();
    }  
    

	
	include(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_CHECKOUT_PROCESS);

	require(DIR_WS_CLASSES . 'order.php');
	$order = new order;
	
	
	//July 2013 GA cumulative checks prior to order process
		//July 2013 clear the ga_cat_id sessions if they exist
  		include_once('includes/functions/ga_tickets.php');
		$ga_any_out_of_stock=false;
			for ($i=0, $n=sizeof($order->products); $i<$n; $i++) 
			{
				//grab the result for all products as a session
				if($order->products[$i]['product_type'] != 'B')//Add Ons Not Invited
				{
				$ga_check= ga_check($order->products[$i]['id'],$order->products[$i]['qty'],$order->products[$i]['product_type']);
				}
			}
			// run through the sessions
		foreach ($_SESSION as $key => $value) 
		{
			if (substr($key, 0, 9) == "ga_cat_id") 
			{      

			//get the cat_id
			$ga_cat_id = substr($key, 10); // returns cat_id
			$category_ga_query = tep_db_query("select categories_GA,categories_quantity_remaining from " . TABLE_CATEGORIES . " where categories_id = '" . $ga_cat_id . "' limit 1");
				if (tep_db_num_rows($category_ga_query)) 
				{ 
				$category_ga = tep_db_fetch_array($category_ga_query);
				if($category_ga['categories_GA']==1)
						{//this is a GA category
						//2015 changed this for GA Master Quantity to bypass B and handle F product types
							if($order->products[$i]['product_type'] !='B')
							{
								//increase quantity by FAMILY_TICKET_QTY for Family Ticket
								if($order->products[$i]['product_type'] =='F')
								{
									$family=$order->products[$i]['qty']*FAMILY_TICKET_QTY;
								}
								else
								{
									$family=$order->products[$i]['qty'];
								}
								$quantity_left_ga=(($category_ga['categories_quantity_remaining'])-($family));
								 //update the master quantity
									tep_db_query("update " . TABLE_CATEGORIES . " set categories_quantity_remaining = '" .(($category_ga['categories_quantity_remaining'])-$family) . "' where categories_id = '" . $ga_path_array[$ii] . "'");
							}
							
						}//end this is a GA category == 1
				} 

			}
		}

	require(DIR_WS_CLASSES . 'order_total.php');
	$order_total_modules = new order_total;
	
	$order_totals = $order_total_modules->process();
	
	// Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($ga_any_out_of_stock == true) ) 
	{
	 $messageStack->add_session('header', TEXT_GA_CHECK_ORDER, 'error');
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
	
    $pay_info=FREE_CHECKOUT_EVENT;
	if ($order->billing['country']['id'] == 999)
	{
	//Box Office - let's also try a session - remember to kill it later at the foot of checkout process
	$FSESSION->set('BoxOffice','999');
	$pay_method=BOX_OFFICE_PAYMENT;
	}
	else
	{
	$pay_method=FREE_CHECKOUT;
	}
    
	$ip_address= $FREQUEST->servervalue('REMOTE_ADDR');

	$sql_data_array = array(
							'customers_id' => $FSESSION->get('customer_id'),
							'customers_language' => $languages_id,
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
				'delivery_address_format_id' => $order->customer['format_id'],
							'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'],
							'billing_email' => $order->billing['customer_email'],//new added 14-08-17						
							'billing_company' => $order->billing['company'],
							'billing_street_address' => $order->billing['street_address'], 
							'billing_suburb' => $order->billing['suburb'], 
							'billing_city' => $order->billing['city'], 
							'billing_postcode' => $order->billing['postcode'], 
							'billing_state' => $order->billing['state'], 
							'billing_country' => ($order->billing['country_id'] > 0)?(get_order_countryname($order->billing['country_id'])):$order->billing['country']['title'], 
				'billing_address_format_id' => $order->customer['format_id'],
							'payment_method' => $pay_method,
							'reference_id' => $FSESSION->get('referenceID'),
							'payment_info' => $pay_info,
							'cc_type' => '', 
							'cc_owner' => '', 
							'cc_number' => '', 
							'cc_expires' => '', 
							'date_purchased' => date('Y-m-d H:i:s',getServerDate(false)), 
							'last_modified' => date('Y-m-d H:i:s',getServerDate(false)),
							//'date_purchased' => 'now()', 
							//'last_modified' => 'now()',
							'orders_status' => 3, 
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
	$insert_id = tep_db_insert_id();
	
	if($order->info['order_status']==3) //processing to delivered
	{
  	tep_db_query("update " . TABLE_ORDERS . " set date_paid = '" . getServerDate(true) . "' where orders_id = '" . (int)$insert_id . "'");
	}
	$result_array=array();

	tep_db_query("INSERT INTO ".TABLE_CUSTOMERS_TO_CUSTOMERS."(group_customer_id,customer_id,orders_id) values('".(int)$FSESSION->get('customer_id')."','".(int)$customer_check_result['customers_id']."','".(int)$insert_id."')");


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

	$products_ordered = '<table width="100%">';
	$products_ordered.= '<tr height="30" class="textTableSubHead"><td width="20"><b>' . TEXT_QUANTITY .'</b></td><td colspan="2"><b>'.TEXT_PRODUCTS_NAME. '</b></td><td></td><td align="right" width="100"><b></td></tr>';
		
	$subtotal = 0;
	$total_tax = 0;
	$products_type='';
	$events_type='';
	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) 
	{
		if (STOCK_LIMITED == 'true') 
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

				} else 
				{
						$stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
				}
				if (tep_db_num_rows($stock_query) > 0 && $order->products[$i]['sku'] != 6) 
				{
					$stock_values = tep_db_fetch_array($stock_query);
					// do not decrement quantities if products_attributes_filename exists
					if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) 
					{
						$stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
					} 
					else 
					{
						$stock_left = $stock_values['products_quantity'] ;
					}
					//DEVELOPER set colors easy
					//tep_db_query("update " . TABLE_PRODUCTS . " set color_code = 'red' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
					
					if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) 
					{
						tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
					}
				}
				
				// for decrement quantity in products table
				$quan=tep_db_query("select products_quantity from " .  TABLE_PRODUCTS . " p where products_id = '" . tep_get_prid($order->products[$i]['id']) . "' ");
				if (tep_db_num_rows($quan) > 0 && $order->products[$i]['sku'] != 6) 
				{
					$quan_values = tep_db_fetch_array($quan);
					//deduct family ticket quantity here
					if($order->products[$i]['events_type'] == 'F')
					{
					$quan_left=$quan_values['products_quantity'] - $order->products[$i]['qty']*FAMILY_TICKET_QTY;
					}
					else
					{
					$quan_left=$quan_values['products_quantity'] - $order->products[$i]['qty'];
					}
					
					
					  tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $quan_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
				}
				
		}
					######################################################################
					#    Single seat tickets                                             #
					######################################################################
					
					if($order->products[$i]['events_type'] == 'P')
					{
					tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '0',  products_status = '0'  where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
					}
					######################################################################
					#    Single seat tickets end                                         #
					######################################################################
					
		//take the product id, find the cPath, explode it and then run a check to see if any cat has a GA setting
		$ga_path_array = array();
		$ga_path_array = explode('_', ga_get_product_path(tep_get_prid($order->products[$i]['id']) ));//n.b. tep_get_product path requires products_status=1 
		$max = sizeof($ga_path_array);
		if ($max >0)
		{
			for ($ii=0; $ii<$max; $ii++) 
			{
			//start of category loops
					$category_ga_query = tep_db_query("select categories_GA,categories_quantity_remaining from " . TABLE_CATEGORIES . " where categories_id = '" .$ga_path_array[$ii]. "' limit 1");
					if (tep_db_num_rows($category_ga_query)) 
					{ 
					  $category_ga = tep_db_fetch_array($category_ga_query);
						if($category_ga['categories_GA']==1)
						{//this is a GA category
						//2015 changed this for GA Master Quantity to bypass B and handle F product types
							if($order->products[$i]['product_type'] !='B')
							{
								//increase quantity by FAMILY_TICKET_QTY for Family Ticket
								if($order->products[$i]['product_type'] =='F')
								{
									$family=$order->products[$i]['qty']*FAMILY_TICKET_QTY;
								}
								else
								{
									$family=$order->products[$i]['qty'];
								}
								//$quantity_left_ga=(($category_ga['categories_quantity_remaining'])-($family));
								 //update the master quantity
									tep_db_query("update " . TABLE_CATEGORIES . " set categories_quantity_remaining = '" .(($category_ga['categories_quantity_remaining'])-$family) . "' where categories_id = '" . $ga_path_array[$ii] . "'");
							}
							
						}//end this is a GA category == 1
					}
			
			
			} // end of the $max loop
		}//ditto			
			
			// prepare for heading
			###################################################################
			$id=$order->products[$i]['id'];
			require_once(DIR_WS_INCLUDES . 'functions/categories_lookup.php');
			###################################################################
			// call the new function
			$type = $products[$i]['product_type'];
			
			list($heading_name, $heading_venue,  $heading_date, $heading_time) = categories_lookup();
			
			###########################################################
			//get quantity for season tickets
			
			if ($season_tickets_used >0)
			{
			//alter the order values
			$order->products[$i]['price'] = $order->products[$i]['final_price'] =  $order->products[$i]['tax'] = 0;
			// if quantity is 1
				if( $order->products[$i]['qty'] <= $season_tickets_used)
				{
				$order->products[$i]['products_season'] = $order->products[$i]['qty'];
				$season_tickets_used = $season_tickets_used -  $order->products[$i]['qty'];
				}
				else 
				{ 
				$order->products[$i]['products_season'] = $season_tickets_used;	
				$season_tickets_used = 0;	
				}    
			
			}
			########################################################### 
			// Update products_ordered (for bestsellers list)
				
				tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
				
				 //coupon
				if ($order->products[$i]['sku'] == 6)
				{
					$dummy_quantity = 1;
					$dummy_final_price = $order->products[$i]['final_price'] * $order->products[$i]['qty'];
					$dummy_price = $order->products[$i]['price'] * $order->products[$i]['qty'];
					$coupon_code = tep_generate_coupon_code();
					$coupon_code_text = ' '.$coupon_code;
				}
				else
				{
					//this gives family tickets per person
					//WARNING: we must make other changes or problems with prices: DO NOT USE IT
					// if($order->products[$i]['events_type'] =='F')
					// {
						// $dummy_quantity = $order->products[$i]['qty']*FAMILY_TICKET_QTY;
					// }
					// else 
					// {
						// $dummy_quantity = $order->products[$i]['qty'];
					// }
					$dummy_quantity = $order->products[$i]['qty'];
					$dummy_final_price = $order->products[$i]['final_price'];
					$dummy_price = $order->products[$i]['price'];  
					$coupon_code_text = '';              
				}
			
				$sql_data_array = array(
							'orders_id' => $insert_id, 
								'products_id' => tep_get_prid($order->products[$i]['id']), 
								'products_model' => $order->products[$i]['model'], 
								//'master_quantity' => $order->products[$i]['master'],
								'products_season' => $order->products[$i]['products_season'], 
								'products_name' => $order->products[$i]['name'] . $coupon_code_text, 
								'products_price' => $dummy_price, 
								'final_price' => $dummy_final_price, 
								'products_tax' => $order->products[$i]['tax'], 
								'products_quantity' => $dummy_quantity,
								//'products_sort_order' => $order->products[$i]['products_sort_order'],
								'events_type'=>$order->products[$i]['events_type'],
								'products_type'=>$order->products[$i]['element_type'],
								'products_sku'=>$order->products[$i]['sku'],
								'is_printable'=>$order->products[$i]['is_printable'],
								'orders_products_status'=>3,
								'support_packs_type'=>$order->products[$i]['support_packs_type'],
								'products_date_available'=>$order->products[$i]['products_date_available'],
								'discount_type'=>$order->products[$i]['discount_type'],
								'discount_id'=>(int)$order->products[$i]['discount_id'],
								'discount_text'=>$order->products[$i]['discount_text'],
								'categories_name'=> $heading_name,
								'concert_venue'=> $heading_venue,
								'concert_date'=> $heading_date,
								'concert_time'=> $heading_time
							);
				tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
				$order_products_id = tep_db_insert_id();
				$order_total_modules->update_credit_account($i);// CCGV
				 
				//product mode download type------------
				if ($order->products[$i]['product_mode']=="V") 
				{ //virtual type
				//$unique_id=md5(date('m/d/y H:i:s'));
					
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
				
			$order_is_printable = 0;
			$order_is_printable = $order_is_printable + $order->products[$i]['is_printable'];
	   
			 // send email about ordered events to user
			$order_total_modules->update_credit_account($i);
					if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) 
					{
					  $sql_data_array = array('orders_id' => $insert_id, 
											  'orders_products_id' => $order_products_id, 
											  'orders_products_filename' => $attributes_values['products_attributes_filename'], 
											  'download_maxdays' => $attributes_values['products_attributes_maxdays'], 
											  'download_count' => $attributes_values['products_attributes_maxcount']);
					  tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
					}
			$total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
			$total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
			$total_cost += $total_products_price;
			$flag_product=false;
			//SEASON PASS CODE EXAMPLE
			//$products_show_id=$order->products[$i]['products_sort_order'];
			
			//	if($order->products[$i]['events_type'] == 'P')
				//	{
				//	tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '0',restrict_to_customers='".(int)$FSESSION->get('customer_id')."',  products_status = '0'  where products_sort_order = '" . $products_show_id . "'");
				//	}
			
			
			if ($order->products[$i]['element_type']=='P')
			{
				$flag_product=true;
				
				$text_family='';
				if($order->products[$i]['events_type'] =='F')
				{
					$text_family= '<small>'.FAMILY_TICKET.' x '.FAMILY_TICKET_QTY.'</small>';
				}else{
					$text_family='';
				}
				
				$products_ordered .= '<tr class="textTableContent" height="56">' . 
										'<td style="vertical-align: text-top;"  width="40" class="textMain"><br>'. $dummy_quantity .' x </td>' .
										'<td width="55" style="vertical-align: text-top;" class="textMain"><br>'. tep_product_email_image($stock_values["products_image_1"],$order->products[$i]['name'],'style="width:50px;height:50px"').'</td>' . 
										'<td style="vertical-align: text-top;" class="textMain"><br>'. $order->products[$i]['name'] .  '<br>' . $products_ordered_attributes . (isset($order->products[$i]['discount_whole_text'])?'<br>' . $order->products[$i]['discount_whole_text']:'') . '</td><td style="vertical-align: text-top;" class="textMain"><small><br>' . $heading_name . ' - ' . 
										$heading_venue . ' - ' . 
										$heading_date . ' - ' . 
										$heading_time . 
										'<br>' . $text_family .
										'</small></td>' .  
										'<td style="vertical-align: text-top;" align="right" class="textMain"><br></td>' . 
										'<td style="vertical-align: text-top;" align="right" class="textMain"><br>'.$currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty'],false).'&nbsp;</td>' . 
										'</tr>' .
										'<tr>' . 
										'<td colspan="5" height="1" class="textBorder">' . tep_draw_separator('pixel_trans.gif',10,1) . '</td>' . 
										'</tr>';
			}										

	}###################### end product loop ##################
	$products_ordered.="</table>";
    $status=3;
 
	$FSESSION->comments=FREE_CHECKOUT;
	
	$order->info['id']=$insert_id;
	
	if($status=='')
	{	
	$status=1;
	}
	
	$sql_data_array = array(
							'orders_id' => $insert_id,
							'orders_status_id' => $status,
							'date_added' => date('Y-m-d H:i:s',getServerDate(false)),
							'comments' => $order->info['comments'] . $FSESSION->get(''),
							//Add Extra Fields
							'other' => $order->info['other'] . $FSESSION->get(''),
							##########################################################
							'field_1' => $order->info['field_1'] . $FSESSION->get(''),
							'field_2' => $order->info['field_2'] . $FSESSION->get(''),
							'field_3' => $order->info['field_3'] . $FSESSION->get(''),
							'field_4' => $order->info['field_4'] . $FSESSION->get(''),
							##########################################################
							'user_added'=>"web"
							);
	tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
	
	//$order_total_modules->apply_credit();//ICW ADDED FOR CREDIT CLASS SYSTEM
	
	// lets start with the email confirmation
	//Add Extra Fields
	######################################################
	$field_1=$order->info['field_1'] . $FSESSION->get('');
	$field_2=$order->info['field_2'] . $FSESSION->get('');
	$field_3=$order->info['field_3'] . $FSESSION->get('');
	$field_4=$order->info['field_4'] . $FSESSION->get('');
	######################################################
	$other=$order->info['other'] . $FSESSION->get('');
	//Add Extra Fields end
	$comments=$order->info['comments'] . $FSESSION->get('');
				
	
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
	#####################################################################
	$merge_details[ORDR_OM]=(($field_1)?'<br>'.FIELD_1.' ' . $field_1:'');
	$merge_details[ORDR_OM].=(($field_2)?'<br>'.FIELD_2.' ' . $field_2:'');
	$merge_details[ORDR_OM].=(($field_3)?'<br>'.FIELD_3.' ' . $field_3:'');
	$merge_details[ORDR_OM].=(($field_4)?'<br>'.FIELD_4.' ' . $field_4:'');
	######################################################################
	$merge_details[ORDR_OM].=(($other)?'<br>'.FIELD_5.' ' . $other:'');
	//Add Extra Fields end
	$merge_details[ORDR_OM].=(($comments)?TEXT_ORDER_COMMENTS .' '. $comments:'');
	
	//add shipping placeholder
	define("ORDR_SM","Shipping_Method");
	$shipping_method=$order->info['shipping_method'];
	$merge_details[ORDR_SM]=$shipping_method;
	
	//create messages for the Email Response
	//Bank Transfer
	define("ORDR_BDM","Bank_Deposit_Message");//Bank Deposit Message as place holder %%Bank_Deposit_Message%%
	define("ORDR_REF","Reference_ID");//Reference ID = %%Reference_ID%%
	if($payment_modules->selected_module =='bank_transfer')
	{
	$merge_details[ORDR_BDM]=MODULE_BANK_TRANSFER_INFO;
	} 
	else 
	{
	$merge_details[ORDR_BDM]="";
	}
	
	//German Bank Transfer
	define("ORDR_GBDM","German_Bank_Deposit_Message");//German Bank Deposit Message as place holder %%German_Bank_Deposit_Message%%
	if($payment_modules->selected_module =='de_bank_transfer')
	{
	$merge_details[ORDR_GBDM]=MODULE_DE_BANK_TRANSFER_INFO;
	} 
	else 
	{
	$merge_details[ORDR_GBDM]="";
	}
	
	$merge_details[ORDR_REF]=$reference;
	//Cheque/Money Order
	define("ORDR_MOP","Money_Order");//place holder %%Money Order%%
	if($payment_modules->selected_module =='moneyorder')
	{
	$merge_details[ORDR_MOP]=MODULE_PAYMENT_MONEYORDER_INFO;
	} 
	else 
	{
	$merge_details[ORDR_MOP]="";
	}
	//Paypal Message
	define("ORDR_PPM","Paypal_Message");//place holder %%Paypal_Message%%
	if($payment_modules->selected_module =='paypal_api')
	{
	$merge_details[ORDR_PPM]=MODULE_PAYMENT_PAYPAL_API_INFO;
	} 
	else 
	{
	$merge_details[ORDR_PPM]="";
	}
	
	//Shipping Will Call
	define("ORDR_WCWC","Willcall Message");//
	if($shipping['id']=='willcall_willcall')
	{
	$merge_details[ORDR_WCWC]=MODULE_SHIPPING_WILLCALL_TEXT_MESSAGE;
	} 
	else 
	{
	$merge_details[ORDR_WCWC]="";
	}
	
	$merge_details[ORDR_PO]=$products_ordered;
	
	//===get if any download links===============

	if(!isset($_COOKIE['customer_is_guest']))
	{ //email for PWA
		
		$downloads_query=tep_db_query("select opd.*,op.products_name from " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd," . TABLE_ORDERS_PRODUCTS . " op where opd.orders_products_id=op.orders_products_id and opd.orders_id='" . (int)$insert_id . "'");
		$download_content="<table>";
		if(tep_db_num_rows($downloads_query)>0)
		{
			$download_content.='<tr class="textTableContent"><td class="textMain" colspan="10">' .TEXT_DOWNLOAD_LINK . '</td></tr>';
		}
		while ($downloads = tep_db_fetch_array($downloads_query)) 
		{
			// MySQL 3.22 does not have INTERVAL
			list($dt_year, $dt_month, $dt_day) = explode('-', date('Y-m-d',strtotime(getServerDate())));
			$download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads['download_maxdays'], $dt_year);
			$download_expiry = date('Y-m-d H:i:s', $download_timestamp);
			$download_content.=' <tr class="textTableContent">';
			if (($downloads['download_count'] > 0) && (file_exists(DIR_FS_DOWNLOAD . $downloads['orders_products_filename'])) && ( ($downloads['download_maxdays'] == 0) || ($download_timestamp > time()))) 
			{
			$download_content.= '            <td class="textMain" align="center"><a href="'.tep_href_link(FILENAME_DOWNLOAD, 'id=' . $downloads['orders_products_download_id'], 'SSL', false).'" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . $downloads['products_name'] .  '</a></td>' . "\n";
			} 
			else 
			{
			$download_content.= '            <td class="textMain">' . $downloads['products_name'] . '</td>' . "\n";
			}
			$download_content.= '            <td class="textMain" nowrap><b>' . TABLE_HEADING_DOWNLOAD_DATE . '</b>&nbsp;' . tep_date_long($download_expiry) . '</td>' . "\n" .
			   '            <td class="textMain" align="right"><b>' . $downloads['download_count'] . '</b>' .  TABLE_HEADING_DOWNLOAD_COUNT . '</td>' . "\n" .
			   '          </tr>' . "\n";
		}
		$download_content.="</table>";
		$merge_details[TEXT_DL]=$download_content;
	}
	else
	{
	$merge_details[TEXT_DL]='';
	}//end PWA
//==============================================

	//Text Strings for the email template
	$merge_details[TEXT_INV_ON]=EMAIL_TEXT_ORDER_NUMBER;//Order Number
	$merge_details[TEXT_INV_DEAR]=TEXT_DEAR;// Dear
	$merge_details[TEXT_INV_THANKS_PURCHASE]=TEXT_THANKS_PURCHASE;//Thanks for...
	$merge_details[TEXT_INV_DD]=TEXT_DELIVERY_DETAILS;//Delivery Details
	$merge_details[TEXT_INV_ADDRESS]=TEXT_ADDRESS;
	$merge_details[TEXT_INV_TELEPHONE]=TEXT_TELEPHONE;
	$merge_details[TEXT_INV_EMAIL]=TEXT_EMAIL;
	$merge_details[TEXT_INV_PD]=TEXT_PAYMENT_DETAILS;
	$merge_details[TEXT_INV_PM]=EMAIL_TEXT_PAYMENT_METHOD;
	$merge_details[TEXT_INV_PRODUCTS]=EMAIL_TEXT_PRODUCTS;
	$merge_details[TEXT_INV_WITH_THANKS]=TEXT_WITH_THANKS;
	$merge_details[TEXT_INV_THANKS]=TEXT_THANKS_FOR;
	$merge_details[ORDR_OT]=$order_totals_str;
	//customer details
	$merge_details[CUST_CF]=$order->customer['firstname'];
	$merge_details[CUST_CL]=$order->customer['lastname'];
	$merge_details[CUST_AU]=$order->customer['username'];
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
	
	$merge_details[BILL_BE]=$order->billing['customer_email'];
	
	$merge_details[BILL_CC]=$order->billing['city'];
	$merge_details[BILL_CP]=$order->billing['postcode'];
	$merge_details[BILL_CE]=$order->billing['state'];
	$merge_details[BILL_CU]=($order->billing['country_id'] > 0)?(get_order_countryname($order->billing['country_id'])):$order->billing['country']['title'];
	//Delivery details
	$merge_details[DELI_NA]=$title . ' ' . $order->delivery['firstname'] . ' ' . $order->delivery['lastname'];
	$merge_details[DELI_CM]=$order->delivery['company'];
	$merge_details[DELI_DE]=$order->delivery['customer_email'];
	$merge_details[DELI_CT]=$order->delivery['street_address'];
	$merge_details[DELI_CS]=$order->delivery['suburb'];
	$merge_details[DELI_CC]=$order->delivery['city'];
	$merge_details[DELI_CP]=$order->delivery['postcode'];
	$merge_details[DELI_CE]=$order->delivery['state'];
	$merge_details[DELI_CU]=($order->delivery['country_id'] > 0)?(get_order_countryname($order->delivery['country_id'])):$order->delivery['country']['title'];
	if ($order->billing['country']['id'] == 999)
	{
		//Box Office - let's also try a session - remember to kill it later at the foot of checkout process
		$FSESSION->set('BoxOffice','999');
	$merge_details[ORDR_PM]= BOX_OFFICE_PAYMENT;
	}else{
	$merge_details[ORDR_PM]= FREE_CHECKOUT;
	}
	
		
	//Remove Messages from Placeholders				  
	define("ORDR_BDM","Bank_Deposit_Message");
	define("ORDR_GBDM","German_Bank_Deposit_Message");
	define("ORDR_MOP","Money_Order");
	define("ORDR_PPM","Paypal_Message");
	$merge_details[ORDR_BDM]="";
	$merge_details[ORDR_MOP]="";
	$merge_details[ORDR_PPM]="";
	$merge_details[ORDR_GBDM]="";
	//create messages for the Email Response

			$merge_details[ORDR_DD]='';	
	  		$merge_details[ORDR_PF] .= '';

	$merge_details['Store_Link']='<a href="' . tep_href_link(FILENAME_DEFAULT) . '" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . STORE_OWNER . '</a>';
	$merge_details['Telephone']=$order->customer['telephone'];
	if(!defined('TICKET_LINK_TEXT'))define('TICKET_LINK_TEXT', 'Collect your tickets here');
	//Ticket Link for email receipt
		if(!isset($_COOKIE['customer_is_guest']))
		{ //email for PWA
		$merge_details['Order_Link']=''.TICKET_LINK_TEXT.'<a href="'.tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . '" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . CLICK_HERE.'</a>';
		}
		else
		{
		$merge_details['Order_Link']='';
		}
		$merge_details['Store_Logo']='<img src="' . tep_href_link(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/' .COMPANY_LOGO) . '" title="' . STORE_NAME . '">';
		$merge_details['Store_Address']=STORE_NAME_ADDRESS;
		//SEND EMAILS
		//We send to BO or Customer
		$send_details[0]['to_name']=$order->customer['firstname'] . ' ' . $order->customer['lastname'];
		if (($_SESSION['BoxOffice']== 999)or($_SESSION['customer_country_id']==999))
		{
			if(NO_BOXOFFICE_EMAIL=='yes')
			{
			$sending_to='';
			}else
			{
			$sending_to=$order->customer['email_address'];
			}	
		$send_details[0]['to_email']=$sending_to;
		}
		else
		{
		$send_details[0]['to_email']=$order->customer['email_address'];	
		}
		$send_details[0]['from_name']=STORE_OWNER;
		$send_details[0]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
		
		//WE send  a  second  email
		if (($_SESSION['BoxOffice']== 999)or($_SESSION['customer_country_id']==999))
		{
			$merge_details["Email_Address"]=$order->billing['customer_email'].' via ( ' .$order->customer['email_address'].' )';
			$send_details[1]['to_name']=$order->billing['firstname'] . ' ' . $order->billing['lastname'];
			$send_details[1]['to_email']=$order->billing['customer_email'];
			
			$send_details[1]['from_name']=STORE_OWNER;
			$send_details[1]['from_email']=STORE_OWNER_EMAIL_ADDRESS;	
			  // send emails to other people 
				if (SEND_EXTRA_ORDER_EMAILS_TO != '') 
				{
					$send_details[2]['to_name']="";
					$send_details[2]['to_email']=SEND_EXTRA_ORDER_EMAILS_TO;
					$send_details[2]['from_name']=STORE_OWNER;
					$send_details[2]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
				}
		}
		else
		{
				if (SEND_EXTRA_ORDER_EMAILS_TO != '') 
				{
					$send_details[1]['to_name']="";
					$send_details[1]['to_email']=SEND_EXTRA_ORDER_EMAILS_TO;
					$send_details[1]['from_name']=STORE_OWNER;
					$send_details[1]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
				}
		}

			if ($GLOBALS[$payment]->code!="wallet")
			{
				if($flag_product==true)
				{ 
					//extra code to create pdf and attach
					$filename="";
							
					if($order_is_printable > 0 && EMAIL_PDF_DELIVERED_ONLY=='true' && E_TICKETS == 'true' && $status == E_TICKET_STATUS )
					{ 
					try
						{
						//creates the  eticket
						require_once('includes/functions/products_ticket.php');
						$filename= (create_checkout_pdf($insert_id));
						}
						  
						//catch exception
					catch(Exception $e)
						{
						   // exit( 'Message: ' .$e->getMessage());
						}	
					}
						//set ticket printed
					//tep_db_query("update  " . TABLE_ORDERS . "  set ticket_printed='Y' where orders_id in(" . $insert_id . ")");

					tep_send_default_email("PRD",$merge_details,$send_details,$filename);
				}
			}

	// modified for wallet payment -start
	if ($GLOBALS[$payment]->code=="wallet")
	{
		$sql_wallet_array=array('drawn_date' => date('Y-m-d H:i:s',getServerDate(false)),
								'customers_id' => $FSESSION->get('customer_id'),
								'orders_id'=>$insert_id,
								'amount'=>$order->info['total']
		);
		
		tep_db_perform(TABLE_WALLET_HISTORY, $sql_wallet_array);
		$balance=tep_get_wallet_balance($FSESSION->get('customer_id'));
		$minimum_balance=tep_get_wallet_min_balance($FSESSION->get('customer_id'));
		if ($balance<$minimum_balance)
		{
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
		
		$cart->reset(true);
		$FSESSION->set('error_count',0);
		// unregister session variables used during checkout
		$FSESSION->remove('box_office_refund');
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
		###########################
		$FSESSION->remove('field_1'); 
		$FSESSION->remove('field_2');
		$FSESSION->remove('field_3');
		$FSESSION->remove('field_4');
		###########################
		//Add Extra Fields end
		//ga cumulative
		ga_kill_sessions();
		$order_total_modules->clear_posts();//ICW ADDED FOR CREDIT CLASS SYSTEM
		$FSESSION->remove('credit_covers');
		tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id='. $insert_id . '&payment=' . $payment, 'SSL'));
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
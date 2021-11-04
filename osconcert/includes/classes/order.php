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

  class order {
    var $info, $totals, $products, $customer, $delivery, $content_type;

    function __construct($order_id = '') 
	{ 
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();

      if (tep_not_null($order_id)) 
	  {
        $this->query($order_id);
      } else 
	  {
        $this->cart();
      }
    }

    function query($order_id) 
	{  
      global $FSESSION,$currencies;

      $order_id = tep_db_prepare_input($order_id);

      $order_query = tep_db_query("select customers_id, customers_name, customers_username, customers_language, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_second_telephone,customers_second_email_address,customers_fax, customers_email_address, customers_address_format_id, delivery_name, delivery_email, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name,  billing_email, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, shipping_method, payment_return1, payment_return2, reference_id, cc_type, cc_owner, cc_number, cc_expires, currency, currency_value, DATE_FORMAT( date_purchased, '%W %d %M %Y' ) as date_purchased,orders_status, last_modified,ip_address from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
	  $order = tep_db_fetch_array($order_query);
      $totals_query = tep_db_query("select title, text,class, value, sort_order from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");
      while ($totals = tep_db_fetch_array($totals_query)) 
	  {
        $this->totals[] = array('title' => $totals['title'],
								'class'=>$totals['class'],
                                'text' => $totals['text'],
								'sort' => $totals['sort_order'],
								'value' => $totals['value']);
      }

  	  $cust_query=tep_db_query("SELECT customers_email_address,customers_username,customers_firstname,customers_lastname from " . TABLE_CUSTOMERS . " where customers_id='" . (int)$order["customers_id"] . "'");
	  $cust_result=tep_db_fetch_array($cust_query);

      $order_total_query = tep_db_query("select text from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_total'");
      $order_total = tep_db_fetch_array($order_total_query);

      $shipping_method_query = tep_db_query("select title from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_shipping'");
      $shipping_method = tep_db_fetch_array($shipping_method_query);

      $order_status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . $order['orders_status'] . "' and language_id = '" . (int)$FSESSION->get('languages_id') . "'");
      $order_status = tep_db_fetch_array($order_status_query);

      $this->info = array('currency' => $order['currency'],
                          'currency_value' => $order['currency_value'],
                          'payment_method' => $order['payment_method'],
                          'cc_type' => $order['cc_type'],
                          'cc_owner' => $order['cc_owner'],
                          'cc_number' => $order['cc_number'],
                          'cc_expires' => $order['cc_expires'],
						  'cc_cvv_number' => $order['cc_cvv_number'],
                          'date_purchased' => $order['date_purchased'],
						  'orders_status' => $order_status['orders_status_name'],
						  'status_id' => $order["orders_status"],
                          'last_modified' => $order['last_modified'],
                          'total' => strip_tags($order_total['text']),
						  'reference_id' => $order['reference_id'],
						  'ip_address' => $order['ip_address'],
						  'shipping_method' => ((substr($shipping_method['title'], -1) == ':') ? substr(strip_tags($shipping_method['title']), 0, -1) : strip_tags($shipping_method['title'])));

      $this->customer = array('id' => $order['customers_id'],
                              'name' => $order['customers_name'],
							  //'email' => $order['billing_email'],//new added 14-08-17
							  'firstname' => $cust_result["customers_firstname"],
							  'lastname' => $cust_result["customers_lastname"],
							  'language' => $order['customers_language'],
                              'company' => $order['customers_company'],
							  'username' => $cust_result['customers_username'],
                              'street_address' => $order['customers_street_address'],
                              'suburb' => $order['customers_suburb'],
                              'city' => $order['customers_city'],
                              'postcode' => $order['customers_postcode'],
                              'state' => $order['customers_state'],
                              'country' => $order['customers_country'],
                              'format_id' => $order['customers_address_format_id'],
                              'telephone' => $order['customers_telephone'],
			      			  'second_telephone' => $order['customers_second_telephone'],
			      			  'second_email_address' => $order['customers_second_email_address'],
			      			  'fax' => $order['customers_fax'],
                              'email_address' => $order['customers_email_address']);

      $this->delivery = array('name' => $order['delivery_name'],
	  						  'customer_email' => $order['delivery_email'],//new added 14-08-17
                              'company' => $order['delivery_company'],
                              'street_address' => $order['delivery_street_address'],
                              'suburb' => $order['delivery_suburb'],
                              'city' => $order['delivery_city'],
                              'postcode' => $order['delivery_postcode'],
                              'state' => $order['delivery_state'],
                              'country' => $order['delivery_country'],
                              'format_id' => $order['delivery_address_format_id']);

      if (empty($this->delivery['name']) && empty($this->delivery['street_address'])) 
	  {
        $this->delivery = false;
      }

      $this->billing = array('name' => $order['billing_name'],
                             'company' => $order['billing_company'],
							 'customer_email' => $order['billing_email'],//new added 14-08-17
                             'street_address' => $order['billing_street_address'],
                             'suburb' => $order['billing_suburb'],
                             'city' => $order['billing_city'],
                             'postcode' => $order['billing_postcode'],
                             'state' => $order['billing_state'],
                             'country' => $order['billing_country'],
                             'format_id' => $order['billing_address_format_id']);

      $index = 0;

 $orders_products_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "' order by products_model,products_name");
      while ($orders_products = tep_db_fetch_array($orders_products_query)) 
	  {
	  		$discount_whole_text='';
	  		if ($orders_products["discount_id"]>0 && $orders_products["discount_text"]!='')
			{
	  			$discount_whole_text=TEXT_DISCOUNT_APPLIED . '&nbsp;' . $orders_products["discount_text"] .'<br>' . TEXT_ORIGINAL_AMOUNT . '&nbsp;' . $currencies->format(tep_add_tax($orders_products['products_price'],$orders_products['products_tax'])*$orders_products['products_quantity']) . '<br>' . TEXT_DISCOUNT . '&nbsp;' .$currencies->format(tep_add_tax($orders_products['products_price']-$orders_products['final_price'],$orders_products['products_tax'])*$orders_products['products_quantity']) . '<br>';
			}
			 $new_qty=$orders_products['products_quantity'];
			// }
			$this->products[$index] = array(
										'element_type'=>$orders_products['products_type'],
										'events_type'=>$orders_products['events_type'],
										'events_id'=>$orders_products['events_id'],
										'qty' => $new_qty,
										'id' => $orders_products['products_id'],
										'is_printable' => $orders_products['is_printable'],
										'name' => $orders_products['products_name'],
										'support_packs_type' => $orders_products['support_packs_type'],
										'products_date_available' => $orders_products['products_date_available'],
									  	'model' => $orders_products['products_model'],
										'products_season' => $orders_products['products_season'],
										'tax' => $orders_products['products_tax'],
										'old_orders_id' => 0,
										'sku' => $orders_products['products_sku'],
		            	                'price' => $orders_products['products_price'],
        			                    'final_price' => $orders_products['final_price'],
										'discount_whole_text' => $discount_whole_text,
										'discount_text' => $orders_products["discount_text"],
										'categories_name' => $orders_products['categories_name'],
										'concert_venue' => $orders_products['concert_venue'],
										'concert_date' => $orders_products['concert_date'],
										'concert_time' => $orders_products['concert_time']
			);
																	
        $subindex = 0;

	
		// if($orders_products['products_type']=="P")
		// {    
		//datediff(download_last_date,CURDATE()) as download_last_date
		   $product_query = tep_db_query("Select product_mode,download_no_of_days,download_link,downloads_per_customer  from " . TABLE_PRODUCTS . " where products_id='" . (int)$orders_products['products_id'] . "'");
			if($prd_result=tep_db_fetch_array($product_query))
			{
				$this->products[$index]['product_mode'] = $prd_result['product_mode'];
				$this->products[$index]['download_link'] = $prd_result['download_link'];
				//$this->products[$index]['download_last_date'] = $prd_result['download_last_date'];
				$this->products[$index]['download_no_of_days'] = $prd_result['download_no_of_days'];
				$this->products[$index]['downloads_per_customer'] = $prd_result['downloads_per_customer'];
			}
		//}	
		
		  $this->info['tax_groups']["{$this->products[$index]['tax']}"] = '1';

        $index++;
      }
    }

	function cart() 
	{
	global $FSESSION, $customer_id, $sendto, $billto, $cart, $languages_id, $currency, $currencies, $shipping, $payment, $comments, $customer_default_address_id;//CCGV
	//if($FSESSION->get('billto')=='')$FSESSION->set('billto',$FSESSION->get('customer_id'));
	if($FSESSION->get('billto')=='')$FSESSION->set('billto',$FSESSION->get('customer_default_address_id'));
	
	$this->content_type = $cart->get_content_type();

	$customer_address_query = tep_db_query("select c.customers_firstname, c.customers_lastname, c.customers_username, c.customers_telephone, c.customers_email_address,c.customers_second_email_address,c.customers_second_telephone,c.customers_fax,ab.entry_company,  ab.entry_customer_email, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, co.countries_id, co.countries_name, co.countries_iso_code_2, co.countries_iso_code_3, co.address_format_id, ab.entry_state from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " co on (ab.entry_country_id = co.countries_id) where c.customers_id = '" . (int)$FSESSION->get('customer_id') . "' and ab.customers_id = '" . (int)$FSESSION->get('customer_id') . "' and c.customers_default_address_id = ab.address_book_id");
	$customer_address = tep_db_fetch_array($customer_address_query);

	$shipping_address_query = tep_db_query("select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_customer_email,ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, ab.entry_country_id, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " c on (ab.entry_country_id = c.countries_id) where ab.customers_id = '" . (int)$FSESSION->get('customer_id') . "' and ab.address_book_id = '" . (int)$FSESSION->get('sendto') . "'");
	$shipping_address = tep_db_fetch_array($shipping_address_query);
	
	$billing_address_query = tep_db_query("select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_customer_email,ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, ab.entry_country_id, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " c on (ab.entry_country_id = c.countries_id) where ab.customers_id = '" . (int)$FSESSION->get('customer_id') . "' and ab.address_book_id = '" . (int)$FSESSION->get('billto') . "'");

	$billing_address = tep_db_fetch_array($billing_address_query);
   
    $shipping=$FSESSION->getobject('shipping');

	$tax_address_query = tep_db_query("select ab.entry_country_id, ab.entry_zone_id from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) where ab.customers_id = '" . (int)$FSESSION->get('customer_id') . "' and ab.address_book_id = '" . (($this->content_type == 'virtual' || $this->content_type=='event')? (int)$FSESSION->get('billto') : (int)$FSESSION->get('sendto')) . "'");

	$tax_address = tep_db_fetch_array($tax_address_query);
	$this->info = array('currency' => $FSESSION->get('currency'),
						'currency_value' => $currencies->currencies[$FSESSION->currency]['value'],
						'payment_method' => $FSESSION->get('payment'),
						'cc_type' => (isset($GLOBALS['cc_type']) ? $GLOBALS['cc_type'] : ''),
						'cc_owner' => (isset($GLOBALS['cc_owner']) ? $GLOBALS['cc_owner'] : ''),
						'cc_number' => (isset($GLOBALS['cc_number']) ? $GLOBALS['cc_number'] : ''),
						'cc_expires' => (isset($GLOBALS['cc_expires']) ? $GLOBALS['cc_expires'] : ''),
						'shipping_module' => $shipping['id'],
						'shipping_method' => $shipping['title'],
						'shipping_cost' => $shipping['cost'],
						'subtotal' => 0,
						'tax' => 0,
						'tax_groups' => array(),
						'comments' => (isset($FSESSION->comments) ? $FSESSION->get('comments') : ''),
						//Add Extra Fields
						'other' => (isset($FSESSION->other) ? $FSESSION->get('other') : ''),
						'field_1' => (isset($FSESSION->field_1) ? $FSESSION->get('field_1') : ''),
						'field_2' => (isset($FSESSION->field_2) ? $FSESSION->get('field_2') : ''),
						'field_3' => (isset($FSESSION->field_3) ? $FSESSION->get('field_3') : ''),
						'field_4' => (isset($FSESSION->field_4) ? $FSESSION->get('field_4') : '')
						);

	$payment=$FSESSION->get('payment');
	if (isset($GLOBALS[$payment]) && is_object($GLOBALS[$payment])) 
	{
		$this->info['payment_method'] = $GLOBALS[$payment]->text_title;
	
		if ( isset($GLOBALS[$payment]->order_status) && is_numeric($GLOBALS[$payment]->order_status) && ($GLOBALS[$payment]->order_status > 0) ) 
		{
			$this->info['order_status'] = $GLOBALS[$payment]->order_status;
		}
	}

	$this->customer = array(
							'firstname' => $customer_address['customers_firstname'],
							'lastname' => $customer_address['customers_lastname'],
							'username' => $customer_address['customers_username'],
							//'customer_email' => $customer_address['entry_customer_email'],
							'company' => $customer_address['entry_company'],
							'street_address' => $customer_address['entry_street_address'],
							'suburb' => $customer_address['entry_suburb'],
							'city' => $customer_address['entry_city'],
							'postcode' => $customer_address['entry_postcode'],
							'state' => ((($customer_address['entry_state']!='')) ? $customer_address['entry_state'] : $customer_address['zone_name']),
							'zone_id' => $customer_address['entry_zone_id'],
							'country' => array('id' => $customer_address['countries_id'], 'title' => $customer_address['countries_name'], 'iso_code_2' => $customer_address['countries_iso_code_2'], 'iso_code_3' => $customer_address['countries_iso_code_3']),
							'format_id' => $customer_address['address_format_id'],
							'telephone' => $customer_address['customers_telephone'],
							'second_telephone' => $customer_address['customers_second_telephone'],
							'fax' => $customer_address['customers_fax'],
							'second_email_address'=>$customer_address['customers_second_email_address'],
							'email_address' => $customer_address['customers_email_address']
							);
	$this->delivery = array(
							'firstname' => $shipping_address['entry_firstname'],
							'lastname' => $shipping_address['entry_lastname'],
							'customer_email' => $shipping_address['entry_customer_email'],
							'company' => $shipping_address['entry_company'],
							'street_address' => $shipping_address['entry_street_address'],
							'suburb' => $shipping_address['entry_suburb'],
							'city' => $shipping_address['entry_city'],
							'postcode' => $shipping_address['entry_postcode'],
							'state' => ((($shipping_address['entry_state']!='')) ? $shipping_address['entry_state'] : $shipping_address['zone_name']),
							'zone_id' => $shipping_address['entry_zone_id'],
							'country' => array('id' => $shipping_address['countries_id'], 'title' => $shipping_address['countries_name'], 'iso_code_2' => $shipping_address['countries_iso_code_2'], 'iso_code_3' => $shipping_address['countries_iso_code_3']),
							'country_id' => $shipping_address['entry_country_id'],
							'format_id' => $shipping_address['address_format_id']
							);


	$this->billing = array(
							'firstname' => $billing_address['entry_firstname'],
							'lastname' => $billing_address['entry_lastname'],
							'customer_email' => $billing_address['entry_customer_email'],
							'company' => $billing_address['entry_company'],
							'street_address' => $billing_address['entry_street_address'],
							'suburb' => $billing_address['entry_suburb'],
							'city' => $billing_address['entry_city'],
							'postcode' => $billing_address['entry_postcode'],
							'state' => ((($billing_address['entry_state']!='')) ? $billing_address['entry_state'] : $billing_address['zone_name']),
							'zone_id' => $billing_address['entry_zone_id'],
							'country' => array(
												'id' => $billing_address['countries_id'], 
												'title' => $billing_address['countries_name'], 
												'iso_code_2' => $billing_address['countries_iso_code_2'], 
												'iso_code_3' => $billing_address['countries_iso_code_3']),
							'country_id' => $billing_address['entry_country_id'],
							'format_id' => $billing_address['address_format_id']
							);
	$index = 0;
	$products = $cart->get_products();
	
	$owd_cost=0;
	$total_weight=0;
	for ($i=0, $n=sizeof($products); $i<$n; $i++) 
	{
		//echo $product_tax . 'test';
				$product_tax=tep_get_tax_rate($products[$i]['tax_class_id'], $tax_address['entry_country_id'], $tax_address['entry_zone_id']);
			     $name=$products[$i]['name'];
				 $price=$products[$i]['price'];//this NOT the original price see line 567 in classes/shopping_cart
				//echo $products[$i]['tax_class_id'] . 'test';

		  
		$this->products[$index] = array(
								'element_type' => $products[$i]['element_type'],
								'answer_type'=>$products[$i]['answer_type'],
								'answer_value'=>$products[$i]['answer_value'],
								'qty' => $products[$i]['quantity'],
								'sessions'=>$products[$i]['sessions'],
								'name' => $products[$i]['name'],
								'model' => $products[$i]['model'],
								//'master' => $products[$i]['master'],
								'products_season' => $products[$i]['products_season'],
								'tax' => $product_tax,
								'tax_description' => tep_get_tax_description($products[$i]['tax_class_id'], $tax_address['entry_country_id'], $tax_address['entry_zone_id']),
								'product_type' => $products[$i]['product_type'],//added Aug 2012 (Graeme Tyson)
								//'price' => $products[$i]['real_price'],
								'price' => $price,
								'old_orders_id'=>$products[$i]['old_orders_id'],
								'final_price' => $products[$i]['final_price'] ,
								'weight' => $products[$i]['weight'],
								'id' => $products[$i]['id'],
								'sh_id'=>$products[$i]['sh_id'],
								'events_type' => $products[$i]['product_type'],
								'is_printable'=>$products[$i]['is_printable'],
								'support_packs_type'=>$products[$i]['product_type'],
								'products_date_available'=>$products[$i]['products_date_available'] 
								);
							//	print_r($this->products[$index]);
		$total_weight+=$products[$i]['weight'];

		//qpb need to adjust final price here
		if ($products[$i]['price_breaks']=="Y") 
		{
			$discount=tep_get_products_price_breaks_discount($prid,1 );
			$this->products[$index]['final_price'] = $this->products[$index]['final_price'] - $discount; 
			
		}

		$sku=$products[$i]['sku'];
		
		$attribs=array();				
		if ($this->products[$i]['element_type']=='V')
		{
			$this->products[$index]['others']=$products[$i]['others'];
		}
		

		$this->products[$index]["sku"]=$sku;

		$products_tax = $this->products[$index]['tax'];
		$products_tax_description = $this->products[$index]['tax_description'];
	
			$this->products[$index]['discount_id']=$products[$i]['discount_id'];
			$this->products[$index]['discount_type']=$products[$i]['discount_type'];
			$this->products[$index]['discount_text']=$products[$i]['discount_text'];
			$this->products[$index]['discount_whole_text']=$products[$i]['discount_whole_text'];
			$owd_cost +=tep_owd_costs('quantity',$products[$i]['quantity']);
			//datediff(download_last_date,CURDATE()) as download_last_date
		    $product_query = tep_db_query("Select product_mode,download_no_of_days,download_link,downloads_per_customer  from " . TABLE_PRODUCTS . " where products_id='" . (int)$products[$i]['id'] . "'");
			if($prd_result=tep_db_fetch_array($product_query))
			{
				$this->products[$index]['product_mode'] = $prd_result['product_mode'];
				$this->products[$index]['download_link'] = $prd_result['download_link'];
				//$this->products[$index]['download_last_date'] = $prd_result['download_last_date'];
				$this->products[$index]['download_no_of_days'] = $prd_result['download_no_of_days'];
				$this->products[$index]['downloads_per_customer'] = $prd_result['downloads_per_customer'];
			}


		$shown_price = tep_add_tax($this->products[$index]['final_price'], $this->products[$index]['tax'])  * $this->products[$index]['qty'];	
		
		$this->info['subtotal']+=$shown_price;
		if (DISPLAY_PRICE_WITH_TAX == 'true') 
		{
		  $this->info['tax'] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
		  if (isset($this->info['tax_groups']["$products_tax_description"])) 
		  {
			$this->info['tax_groups']["$products_tax_description"] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
		  } else 
		  {
			$this->info['tax_groups']["$products_tax_description"] = $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
		  }
		} else 
		{
		  $this->info['tax'] += ($products_tax / 100) * $shown_price;
		  if (isset($this->info['tax_groups']["$products_tax_description"])) 
		  {
			$this->info['tax_groups']["$products_tax_description"] += ($products_tax / 100) * $shown_price;
		  } else 
		  {
			$this->info['tax_groups']["$products_tax_description"] = ($products_tax / 100) * $shown_price;
		  }
		}
		$index++;
	}
	if ($owd_cost>0)
	{
		$owd_cost+=tep_owd_costs('order');
		$owd_cost+=tep_owd_costs('overweight',$total_weight);	
		$owd_cost+=tep_owd_costs('packing',$total_weight);
	}
	$this->info['owd_cost'] = $owd_cost;
	$this->info['subtotal']+=$this->info['owd_cost'];
	if (DISPLAY_PRICE_WITH_TAX == 'true') 
	{
		$this->info['total'] = $this->info['subtotal'] + $this->info['shipping_cost'];
	} else 
	{
		$this->info['total'] = $this->info['subtotal'] + $this->info['tax'] + $this->info['shipping_cost'];
	}
					$this->info['total']=tep_round($this->info['total'],2);

	}

  }
?>
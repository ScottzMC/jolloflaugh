<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  
  

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
  http://www.openfreeway.org

Copyright 2007 ZacWare Pty. Ltd
*/
 // Check to ensure this file is included in osConcer!
//this are to be testing 
 
defined('_FEXEC') or die(); 
  class order {
    var $info, $totals, $products, $customer, $delivery;
    function __construct($order_id='',$retrieve_temp=false) {
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();
	  if (tep_not_null($order_id)) {
        $this->query($order_id,$retrieve_temp);
      } else {
        $this->cart();
      }
    }
	
    function query($order_id,$retrieve_temp=false) {
	global $products_ordered, $currencies,$ordered_product, $order_totals_str;
	$ordered_product=""; 
 
		
      $order_query = tep_db_query("select o.customers_id,o.customers_name, o.customers_company, o.customers_language, o.customers_street_address, o.customers_suburb, o.customers_city, o.customers_postcode, o.customers_state, o.customers_country, o.customers_telephone, o.customers_email_address, o.customers_second_email_address,o.customers_second_telephone,o.customers_fax,
                                   o.customers_address_format_id, o.delivery_name, o.delivery_company, o.delivery_street_address, o.delivery_suburb, o.delivery_city, o.delivery_postcode, o.delivery_state, o.delivery_country, o.delivery_address_format_id, o.billing_name, o.billing_company, o.billing_street_address, o.billing_suburb, o.billing_city, o.billing_postcode, o.billing_state, o.billing_country, o.billing_address_format_id, o.payment_method, o.payment_info,o.cc_type, o.cc_owner, o.cc_number, o.cc_expires, o.cc_cvv_number,o.currency,o.shipping_date, o.currency_value,o.date_purchased, o.orders_status, o.last_modified, o.payment_return1, o.payment_return2, o.reference_id, c.customers_firstname, c.customers_lastname,o.ip_address from " . TABLE_ORDERS . " o, " . TABLE_CUSTOMERS . " c where o.orders_id = '" . (int)$order_id . "' and o.customers_id=c.customers_id");
	  
	  $order = tep_db_fetch_array($order_query);
	 

	   $totals_query = tep_db_query("select title, text,class, value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");
      while ($totals = tep_db_fetch_array($totals_query)) {
        $this->totals[] = array('title' => $totals['title'],
								'class'=>$totals['class'],
                                'text' => $totals['text'],
								'value' => $totals['value']);
						
      }
	  	$order_total_query = tep_db_query("select text from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_total'");
      $order_total = tep_db_fetch_array($order_total_query);
	  	$order_tax_query = tep_db_query("select text from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_tax'");
      $order_tax = tep_db_fetch_array($order_tax_query);
		// get the comments
		$comments_query=tep_db_query("SELECT comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id='" . (int)$order_id . "' order by date_added desc limit 1");
		$comments="";
		$comments_result=tep_db_fetch_array($comments_query);
		$comments=$comments_result["comments"];
      $this->info = array('currency' => $order['currency'],
                          'currency_value' => $order['currency_value'],
                          'payment_method' => $order['payment_method'],
						  'payment_info'=>$order['payment_info'],
                          'cc_type' => $order['cc_type'],
                          'cc_owner' => $order['cc_owner'],
                          'cc_number' => $order['cc_number'],
                          'cc_expires' => $order['cc_expires'],
			  			  'cc_cvv_number'=> $order['cc_cvv_number'],
  						  'status_id'=>$order['orders_status'],
						  'comments'=>$comments,
						  'payment_return1' => $order['payment_return1'],
						  'payment_return2' => $order['payment_return2'],
						  'reference_id' => $order['reference_id'],
                          'date_purchased' => $order['date_purchased'],
                          'date_purchasing' => $order['date_purchasing'],
  						  'shipping_date' => $order['shipping_date'],
						  'total'=>strip_tags($order_total['text']),
						  'orders_status' => $order['orders_status'],
						  'ip_address'=>$order['ip_address'],
                          'last_modified' => $order['last_modified'],
						  'shipping_method' => ((substr($shipping_method['title'], -1) == ':') ? substr(strip_tags($shipping_method['title']), 0, -1) : strip_tags($shipping_method['title'])));
						  
      $this->customer = array('id'=> $order['customers_id'],
	  						  'firstname'=>$order['customers_firstname'],
							  'lastname'=>$order['customers_lastname'],
                              'name' => $order['customers_name'],
                              'company' => $order['customers_company'],
							  'language' => $order['customers_language'],
                              'street_address' => $order['customers_street_address'],
                              'suburb' => $order['customers_suburb'],
                              'city' => $order['customers_city'],
                              'postcode' => $order['customers_postcode'],
                              'state' => $order['customers_state'],
                              'country' => $order['customers_country'],
                              'format_id' => $order['customers_address_format_id'],
                              'telephone' => $order['customers_telephone'],
			    			  'second_telephone'=>$order['customers_second_telephone'],
			   			      'second_email_address'=>$order['customers_second_email_address'],
			   			      'fax'=>$order['customers_fax'],
                              'email_address' => $order['customers_email_address']);
							  
							

      $this->delivery = array('name' => $order['delivery_name'],
                              'company' => $order['delivery_company'],
                              'street_address' => $order['delivery_street_address'],
                              'suburb' => $order['delivery_suburb'],
                              'city' => $order['delivery_city'],
                              'postcode' => $order['delivery_postcode'],
                              'state' => $order['delivery_state'],
                              'country' => $order['delivery_country'],
                              'format_id' => $order['delivery_address_format_id']);

      $this->billing = array('name' => $order['billing_name'],
                             'company' => $order['billing_company'],
                             'street_address' => $order['billing_street_address'],
                             'suburb' => $order['billing_suburb'],
                             'city' => $order['billing_city'],
                             'postcode' => $order['billing_postcode'],
                             'state' => $order['billing_state'],
                             'country' => $order['billing_country'],
                             'format_id' => $order['billing_address_format_id']);
							 
							
      $index = 0;
	  $pcnt=0;
	 if(!$retrieve_temp){
		  $orders_products_query = tep_db_query("select orders_products_id, products_id, products_name,  products_model, products_price, products_tax, products_quantity, final_price,products_type,events_type,orders_products_status,products_sku,support_packs_type,discount_text,discount_id, categories_name, concert_venue, concert_date, concert_time from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "' order by products_model,products_name");

	 }else{
	      $orders_products_query = tep_db_query("select orders_products_id, products_id, products_name,date_format(products_model,'%Y-%m-%d') as products_model,products_price, products_tax, products_quantity, final_price,products_type,events_type,products_sku from " . TABLE_ORDERS_EDIT_PRODUCTS . " where orders_id = '" . (int)$order_id . "' order by products_model,products_name");
	 }	  	  
	  	$products_ordered = '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
		$products_ordered.= '<tr class="textTableSubHead" height="21"><td width="5">&nbsp;' . TEXT_QUANTITY .'</td><td colspan="2">'.TEXT_PRODUCTS_NAME.'</td><Td>SKU</td><td align="center" width="100">Total</td></tr>';
		  while ($orders_products = tep_db_fetch_array($orders_products_query)) 
		  {
		  	$discount_whole_text='';
	  		if ($orders_products["discount_id"]>0 && $orders_products["discount_text"]!=''){
	  			$discount_whole_text=TEXT_DISCOUNT_APPLIED . '&nbsp;' . $orders_products["discount_text"] .'<br>' . TEXT_ORIGINAL_AMOUNT . '&nbsp;' . $currencies->format(tep_add_tax($orders_products['products_price'],$orders_products['products_tax'])*$orders_products['products_quantity']) . '<br>' . TEXT_DISCOUNT . '&nbsp;' .$currencies->format(tep_add_tax($orders_products['products_price']-$orders_products['final_price'],$orders_products['products_tax'])*$orders_products['products_quantity']) . '<br>';
			}

		  //if(!$retrieve_temp)
			$this->products[$index] = array('id'=>$orders_products['products_id'],
											'orders_products_id'=>$orders_products['orders_products_id'],
											'qty' => $orders_products['products_quantity'],
											'name' => $orders_products['products_name'],
											'model' => $orders_products['products_model'],
											'orders_products_status'=>$orders_products['orders_products_status'],
											'tax' => $orders_products['products_tax'],
											'price' => $orders_products['products_price'],
											'final_price' => $orders_products['final_price'],
											'products_type'=>$orders_products['products_type'],
											'events_type'=>$orders_products['events_type'],
											'products_sku' =>$orders_products['products_sku'],
											'discount_whole_text'=>$discount_whole_text,
											'categories_name'=> $orders_products['categories_name'],
											'concert_venue'=> $orders_products['concert_venue'],
											'concert_date'=> $orders_products['concert_date'],
											'concert_time'=> $orders_products['concert_time'],
											'support_packs_type'=>$orders_products['support_packs_type'] //Support Packs
									);
										
			$subindex = 0;


			if($this->products[$index]['products_type']=='P'){

				$product_query = tep_db_query("Select product_mode,datediff(download_last_date,CURDATE()) as download_last_date,download_link,downloads_per_customer  from " . TABLE_PRODUCTS . " where products_id='" . (int)$orders_products['products_id'] . "'");
				if($prd_result=tep_db_fetch_array($product_query))
				{
					$this->products[$index]['product_mode'] = $prd_result['product_mode'];
					$this->products[$index]['download_link'] = $prd_result['download_link'];
					$this->products[$index]['download_last_date'] = $prd_result['download_last_date'];
					$this->products[$index]['downloads_per_customer'] = $prd_result['downloads_per_customer'];
				}
				$product_query=tep_db_query("select products_image_1 from ".TABLE_PRODUCTS." where products_id='".(int)$this->products[$index]["id"]."'");
				$product_result=tep_db_fetch_array($product_query);
				
				$products_ordered .= '<tr class="textTableContent" height="56">' . 
										'<td valign="top"  width="60"><br>&nbsp;'.$this->products[$index]['qty'].' x </td>' .'</td>' . 
										'<td width="75" valign="top"><br>'.tep_product_email_image($product_result["products_image_1"],$this->products[$index]['name'],'style="width:50px;height:50px"').'</td>' .
										'<td valign="top"><br>&nbsp;&nbsp;'.$this->products[$index]['name'] . ($this->products[$index]['model']!=""?' (' . $this->products[$index]['model'] . ')':'') . '<br>' . $products_ordered_attributes . '</td>' . 
										'<td valign="top"><br>'. $sku .'</td>' .
										'<td valign="top" align="right" class="textMain"><br>'.$currencies->display_price($this->products[$index]['final_price'], $this->products[$index]['tax'], $this->products[$index]['qty'],true).'&nbsp;</td>' .
									'</tr>' .
									'<tr>' .
										'<td colspan="5" height="1" class="textBorder">' . tep_draw_separator('pixel_trans.gif',10,1) . '</td>' . 
									'</tr>';
														
										
			}
			$this->products[$index]['sku'] = $sku;

			$res_index=0;			

	
			$products_tax = $this->products[$index]['tax'];
			$products_tax_description = $this->products[$index]['tax_description'];
			
			
			$shown_price = tep_add_tax($this->products[$index]['final_price'] , $this->products[$index]['tax'])  * $this->products[$index]['qty'];
			$this->info['subtotal']+=$shown_price;
			
			if (DISPLAY_PRICE_WITH_TAX == 'true') {
				$this->info['tax'] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
				if (isset($this->info['tax_groups']["$products_tax_description"])) {
					$this->info['tax_groups']["$products_tax_description"] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
				} else {
					$this->info['tax_groups']["$products_tax_description"] = $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
				}
			} else {
				$this->info['tax'] += ($products_tax / 100) * $shown_price;
				if (isset($this->info['tax_groups']["$products_tax_description"])) {
					$this->info['tax_groups']["$products_tax_description"] += ($products_tax / 100) * $shown_price;
				} else {
					$this->info['tax_groups']["$products_tax_description"] = ($products_tax / 100) * $shown_price;
				}
			}
			$index++;
			$pcnt++;
			}
	  $products_ordered.="</table>";
	  

	if (DISPLAY_PRICE_WITH_TAX == 'true') {
		$this->info['total'] = $this->info['subtotal'];
	} else {
		$this->info['total'] = $this->info['subtotal'] + $this->info['tax'];
	}
	$this->info['total']=tep_round($this->info['total'],2);

	}
 function get_order_country_id($country_name) {
    $country_id_query = tep_db_query("select * from " . TABLE_COUNTRIES . " where countries_name = '" . tep_db_input($country_name) . "'");
    if (!tep_db_num_rows($country_id_query)) {
      return 0;
    }
    else {
      $country_id_row = tep_db_fetch_array($country_id_query);
      return $country_id_row['countries_id'];
    }
  }	
 function get_order_country_name($country_id) {
    $country_name_query = tep_db_query("select * from " . TABLE_COUNTRIES . " where countries_name = '" . tep_db_input($country_id) . "'");
    if (!tep_db_num_rows($country_name_query)) {
      return 0;
    }
    else {
      $country_name_row = tep_db_fetch_array($country_name_query);
      return $country_name_row['countries_name'];
    }
  }	

  function cart() {
	global $customer_id,$customer_default_address_id,$order_status,$billto,$order_id, $cart, $currency, $currencies, $shipping, $payment,$modify_price_prefix,$sign,$purpose,$manual_option,$FSESSION,$FREQUEST,$comments;
	$this->content_type = $cart->get_content_type();

	$sendto=$FSESSION->get('customer_default_address_id');
	$billto=$FSESSION->get('customer_default_address_id');

	$customer_address_query = tep_db_query("select c.customers_firstname, c.customers_lastname, c.customers_telephone, c.customers_email_address,c.customers_second_email_address,c.customers_second_telephone,c.customers_fax,ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, co.countries_id, co.countries_name, co.countries_iso_code_2, co.countries_iso_code_3, co.address_format_id, ab.entry_state from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " co on (ab.entry_country_id = co.countries_id) where c.customers_id = '" . (int)$FSESSION->customer_id . "' and ab.customers_id = '" . (int)$FSESSION->customer_id. "' and c.customers_default_address_id = ab.address_book_id");
	$customer_address = tep_db_fetch_array($customer_address_query);
	
	if($FSESSION->is_registered('order_id')){
        $address_query = tep_db_query("select o.*,z.zone_id from " . TABLE_ORDERS . " o left join " . TABLE_ZONES . " z on z.zone_name =o.billing_state where o.customers_id = '" . (int)$FSESSION->customer_id . "' and o.orders_id='" . (int)$FSESSION->order_id . "'");
	}else{
		//$address_query = tep_db_query("select ab.*,z.zone_id from " . TABLE_CUSTOMERS_BASKET_ADDRESS . " ab left join " . TABLE_ZONES . " z on z.zone_name =ab.billing_state  where ab.customers_id = '" . (int)$FSESSION->customer_id . "' and orders_id=0");
	}	
	$address = tep_db_fetch_array($address_query);
	$shipping_address_query = tep_db_query("select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, ab.entry_country_id, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " c on (ab.entry_country_id = c.countries_id) where ab.customers_id = '" . (int)$FSESSION->customer_id . "' and ab.address_book_id = '" . (int)$sendto . "'");
	$shipping_address = tep_db_fetch_array($shipping_address_query);
	
	$billing_address_query = tep_db_query("select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, ab.entry_country_id, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " c on (ab.entry_country_id = c.countries_id) where ab.customers_id = '" . (int)$FSESSION->customer_id . "' and ab.address_book_id = '" . (int)$sendto . "'");
	$billing_address = tep_db_fetch_array($billing_address_query);

	$tax_address_query = tep_db_query("select ab.entry_country_id, ab.entry_zone_id from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) where ab.customers_id = '" . (int)$FSESSION->customer_id . "' and ab.address_book_id = '" . (int)($this->content_type == 'virtual' || $this->content_type=='event'? $billto : $sendto) . "'");
	$tax_address = tep_db_fetch_array($tax_address_query);
	//Oct 2013
    $rr=array();
    if(isset($_SESSION['shipping'])){
    $rr=$FSESSION->get('shipping');}
    else{
    $rr['title']='';
    $rr['cost']=0;
    }
    //end
    $this->info = array(
						'order_status' => (isset($GLOBALS['administrator_login'] ) ? $GLOBALS['administrator_login'] : DEFAULT_ORDERS_STATUS_ID),
						'currency' => $FSESSION->currency,
						'modify_price_prefix'=>$FSESSION->modify_price_prefix,
						'sign'=>$FSESSION->sign,
						'purpose'=>$FSESSION->purpose,
						'manual_option'=>$FSESSION->manual_option,
						'currency_value' => $currencies->currencies[$FSESSION->currency]['value'],
						'payment_method' => $payment,
						
						'cc_type' => (isset($GLOBALS['cc_type']) ? $GLOBALS['cc_type'] : ''),
						'cc_owner' => (isset($GLOBALS['cc_owner']) ? $GLOBALS['cc_owner'] : ''),
						'cc_number' => (isset($GLOBALS['cc_number']) ? $GLOBALS['cc_number'] : ''),
						'cc_expires' => (isset($GLOBALS['cc_expires']) ? $GLOBALS['cc_expires'] : ''),
						'shipping_method' => $rr['title'],
						'shipping_cost' => $rr['cost'],
						'subtotal' => 0,
						'tax' => 0,
						'tax_groups' => array(),
						'comments' => ($FREQUEST->postvalue('comments')?$FREQUEST->postvalue('comments'):'')
						);
						
	if (($GLOBALS[$payment]) && is_object($GLOBALS[$payment])) {
		$this->info['payment_method'] = $GLOBALS[$payment]->title;
	
		if ( isset($GLOBALS[$payment]->order_status) && is_numeric($GLOBALS[$payment]->order_status) && ($GLOBALS[$payment]->order_status > 0) ) {
			$this->info['order_status'] = $GLOBALS[$payment]->order_status;
		}
	}

	$this->customer = array(
							'firstname' => $customer_address['customers_firstname'],
							'lastname' => $customer_address['customers_lastname'],
							'company' => $customer_address['entry_company'],
							'street_address' => $customer_address['entry_street_address'],
							'suburb' => $customer_address['entry_suburb'],
							'city' => $customer_address['entry_city'],
							'postcode' => $customer_address['entry_postcode'],
							'state' => ((tep_not_null($customer_address['entry_state'])) ? $customer_address['entry_state'] : $customer_address['zone_name']),
							'zone_id' => $customer_address['entry_zone_id'],
							'country' => array('id' => $customer_address['countries_id'], 'title' => $customer_address['countries_name'], 'iso_code_2' => $customer_address['countries_iso_code_2'], 'iso_code_3' => $customer_address['countries_iso_code_3']),
							'format_id' => $customer_address['address_format_id'],
							'telephone' => $address['customers_telephone'],
							'second_telephone' => $address['customers_second_telephone'],
							'fax' => $address['customers_fax'],
							'second_email_address'=>$address['customers_second_email_address'],
							'email_address' => $address['customers_email_address']
							);
	$delivery_zone_query = tep_db_query("select z.zone_id from " .  TABLE_ZONES . " z where z.zone_name ='" . tep_db_input($address['delivery_state']) . "'");
		if(tep_db_num_rows($delivery_zone_query)>0) {
			$delivery_zone_result=tep_db_fetch_array($delivery_zone_query);
			$delivery_zone=$delivery_zone_result['zone_id'];
		} else $delivery_zone=0;
	$this->delivery = array('name' => $address['delivery_name'],
							'company' => $customer_address['delivery_company'],
							'street_address' => $address['delivery_street_address'],
							'suburb' =>$address['delivery_suburb'],
							'city' => $address['delivery_city'],
							'postcode' => $customer_address['entry_postcode'],
							'state' => $address['delivery_state'],
							'zone_id' => $delivery_zone,
							'country' => array('id'=>$this->get_order_country_id($address['delivery_country']),'title'=>$address['delivery_country'], 'iso_code_2' => $customer_address['countries_iso_code_2'], 'iso_code_3' => $customer_address['countries_iso_code_3']),
							'country_id' =>  $this->get_order_country_id($address['delivery_country']),
							'format_id' => $address['delivery_address_format_id']
							);

      $this->billing = array('name' => $address['billing_name'],
                             'company' => $address['billing_company'],
                             'street_address' => $address['billing_street_address'],
                             'suburb' => $address['billing_suburb'],
                             'city' => $address['billing_city'],
                             'postcode' => $address['billing_postcode'],
                             'state' => $address['billing_state'],
							'zone_id' => $address['zone_id'],
							'country' => array('id' => 
													($this->get_order_country_id($address['billing_country'])!='')?
													$this->get_order_country_id($address['billing_country']):$billing_address['entry_country_id'], 
												'title' => ($address['billing_country']!='')?$address['billing_country']:($this->get_order_country_name($billing_address['entry_country_id'])), 
												'iso_code_2' => $billing_address['countries_iso_code_2'], 
												'iso_code_3' => $billing_address['countries_iso_code_3']
												),
 							'country_id' => $this->get_order_country_id($address['billing_country']),                             
							'format_id' => $address['billing_address_format_id']);
	
	$index = 0;
	$products = $cart->get_products();
	$owd_cost=0;
	$total_weight=0;

	for ($i=0, $n=sizeof($products); $i<$n; $i++) {

		$product_tax=tep_get_tax_rate($products[$i]['tax_class_id'], $tax_address['entry_country_id'], $tax_address['entry_zone_id']);

		$this->products[$index] = array(
								'element_type' => $products[$i]['element_type'],
								'answer_type'=>$products[$i]['answer_type'],
								'answer_value'=>$products[$i]['answer_value'],
								'qty' => $products[$i]['quantity'],
								'sessions'=>$products[$i]['sessions'],
								'name' => $products[$i]['name'],
								'model' => $products[$i]['model'],
								'tax' => $product_tax,
								'tax_description' => tep_get_tax_description($products[$i]['tax_class_id'], $tax_address['entry_country_id'], $tax_address['entry_zone_id']),
								'price' => $products[$i]['price'],
								 'events_type'=>$products[$i]['events_type'],
								'old_orders_id'=>$products[$i]['old_orders_id'],
								'final_price' => $products[$i]['final_price'] ,
								'weight' => $products[$i]['weight'],
								'id' => $products[$i]['id'],
								'sh_id'=>$products[$i]['sh_id'],
								'order_products_id'=>isset($products[$i]['order_products_id'])?$products[$i]['order_products_id']:'0',
								'support_packs_type'=>$products[$i]['support_packs_type'] //Support Packs
								);
								
		$total_weight+=$products[$i]['weight'];
	
		$sku=$products[$i]['sku'];
		
		$attribs=array();				
		if ($this->products[$i]['element_type']=='V'){
			$this->products[$index]['others']=$products[$i]['others'];
		}
		
		// if ($products[$i]['attributes']) 
		// {
			// $subindex = 0;
	
				// reset($products[$i]['attributes']);
				// while (list($option, $value) = each($products[$i]['attributes'])) {
					// $attributes_query = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . (int)$products[$i]['id'] . "' and pa.options_id = '" . (int)$option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . (int)$value . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . (int)$FSESSION->languages_id . "' and poval.language_id = '" . (int)$FSESSION->languages_id . "'");
					// $attributes = tep_db_fetch_array($attributes_query);
		
					// $this->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options_name'],
																			 // 'value' => $attributes['products_options_values_name'],
																			 // 'option_id' => $option,
																			 // 'value_id' => $value,
																			 // 'prefix' => $attributes['price_prefix'],
																			 // 'price' => $attributes['options_values_price']);
		
					// $attribs[]=$option . "{" . $value . "}";
					// $subindex++;
				// }
			
			// if ($products[$i]['element_type']=="P")
			// {
				// sort($attribs);
				// $attrib_key=join("-",$attribs);
				// $sku_query=tep_db_query("select sku from " . TABLE_PRODUCTS_STOCK . " where products_id='" . (int)$products[$i]['id'] . "' and attributes_id='" . tep_db_input($attrib_key) . "'");
				// $sku_result=tep_db_fetch_array($sku_query);
				// $sku=$sku_result["sku"];
			// }
		// }
		if ($products[$i]['element_type']=="P"){
			$this->products[$index]['discount_type']=$products[$i]['discount_type'];
			$this->products[$index]['discount_text']=$products[$i]['discount_text'];
			$this->products[$index]['discount_whole_text']=$products[$i]['discount_whole_text'];
			$this->products[$index]['discount_id']=$products[$i]['discount_id'];
			$product_query = tep_db_query("Select product_mode,datediff(download_last_date,CURDATE()) as download_last_date,download_link,downloads_per_customer  from " . TABLE_PRODUCTS . " where products_id='" . (int)$products[$i]['id'] . "'");
			if($prd_result=tep_db_fetch_array($product_query))
			{
				$this->products[$index]['product_mode'] = $prd_result['product_mode'];
				$this->products[$index]['download_link'] = $prd_result['download_link'];
				$this->products[$index]['download_last_date'] = $prd_result['download_last_date'];
				$this->products[$index]['downloads_per_customer'] = $prd_result['downloads_per_customer'];
			}
		}
		$this->products[$index]["sku"]=$sku;
		$products_tax = $this->products[$index]['tax'];
		$products_tax_description = $this->products[$index]['tax_description'];
	
		if ($products[$i]['element_type']=="P"){
			$owd_cost +=tep_owd_costs('quantity',$products[$i]['quantity']);
		}

		$shown_price = tep_add_tax($this->products[$index]['final_price'] , $this->products[$index]['tax'])  * $this->products[$index]['qty'];	
		//echo $shown_price.'hh';
		if($this->products[$index]['order_products_id']>0) //edit order
		{
		 		//$this->info['subtotal']+=$shown_price;
				//echo $shown_price.'hh'. $this->info['subtotal'];
				if (DISPLAY_PRICE_WITH_TAX == 'true') 
				  $this->info['tax'] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
				else
				  $this->info['tax'] += ($products_tax / 100) * $shown_price;
		}
		else
		{
			$this->info['subtotal']+=$shown_price;
			if (DISPLAY_PRICE_WITH_TAX == 'true') 
			  $this->info['tax'] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
			else
			  $this->info['tax'] += ($products_tax / 100) * $shown_price;
		}
		
		
	
		
		$this->info['nettotal']+=$shown_price;
		//echo $this->info['nettotal'].'ff';
		if (DISPLAY_PRICE_WITH_TAX == 'true') {
			  $this->info['net_tax'] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
		}else{
			  $this->info['net_tax'] += ($products_tax / 100) * $shown_price;
		}	  
		if (DISPLAY_PRICE_WITH_TAX == 'true') {
		  if (isset($this->info['tax_groups']["$products_tax_description"])) {
			$this->info['tax_groups']["$products_tax_description"] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
		  } else {
			$this->info['tax_groups']["$products_tax_description"] = $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
		  }
		} else {
		  if (isset($this->info['tax_groups']["$products_tax_description"])) {
			$this->info['tax_groups']["$products_tax_description"] += ($products_tax / 100) * $shown_price;
		  } else {
			$this->info['tax_groups']["$products_tax_description"] = ($products_tax / 100) * $shown_price;
		  }
		}	
		//echo $this->products[$index]['final_price'].'jjj';
  
		
		$index++;
	}

	if ($owd_cost>0){
		$owd_cost+=tep_owd_costs('order');
		$owd_cost+=tep_owd_costs('overweight',$total_weight);	
		$owd_cost+=tep_owd_costs('packing',$total_weight);
	}
	$this->info['owd_cost'] = $owd_cost;
	$this->info['subtotal']+=$this->info['owd_cost'];
	$this->info['nettotal']+=$this->info['owd_cost'];	
	//echo $this->info['tax'] . 'kkk'. $this->info['net_tax'];
	if (DISPLAY_PRICE_WITH_TAX == 'true') {
	
	$this->info['total'] = $this->info['subtotal'] + $this->info['shipping_cost'];
		$this->info['overall_total'] = $this->info['nettotal'] + $this->info['shipping_cost'];	
			
	} else {
	
	$this->info['total'] = $this->info['subtotal'] + $this->info['tax'] + $this->info['shipping_cost'];
		$this->info['overall_total'] = $this->info['nettotal'] + $this->info['net_tax'] + $this->info['shipping_cost'];	
		
	}
	//$this->info['total'] --- updated total
	//$this->info['overall_total'] --total
	//$this->info['subtotal'] --updated amount
	//$this->info['nettotal'] --subtotal
	//manual price adjustment
	 if($this->info['manual_option']=='Y'){
		$this->info['total'] +=$this->info['modify_price_prefix'];
		if($FSESSION->order_id>0) 
		{$this->info['overall_total'] +=$this->info['modify_price_prefix'];
		
		}
	}
	//echo $this->info['total'];
	//echo $this->info['overall_total'].'gf';
	//print_r ($this);
	$this->info['total']=tep_round($this->info['total'],2);
	$this->info['overall_total']=tep_round($this->info['overall_total'],2);	
	}
  }
?>
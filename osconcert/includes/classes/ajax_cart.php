<?php
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
/*
	AJAX Cart Class for osConcert
	Copyright 2012 by Martin Zeitler, Germany
	
*/
	class ajaxCart {
		
		/* get customer cart - required */
		function getCart($mode='html')
		{
			global $FSESSION;
			//July 2013 grab ga_functions
			include_once('includes/functions/ga_tickets.php');
			//end
			global $FSESSION, $currencies;
			$currency = $FSESSION->currency;
			$lang_id = (int)$FSESSION->languages_id;
			
			/* switch tables */
			if($_SESSION['customer_id']){
				$table = TABLE_CUSTOMERS_BASKET;
			}
			else{
				$table = TABLE_CUSTOMERS_TEMP_BASKET;
			}
			
			$product_status = " p.products_status = 1 AND pd.language_id = ".$lang_id;
			
			//box office refund fix
			//2016 add tax
			if($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_refund'] ))
			{
				$product_status = " pd.language_id = ".$lang_id;
				} 
			$sql = "
				SELECT 
				p.products_id, 
                p.products_sku,
				p.products_model,	
				p2c.categories_id, 
				pd.products_name, 
				p.products_price, 
				p.products_tax_class_id,	
				p.color_code, 
				p.products_status, 
				p.product_type, 
				".$table.".customers_basket_quantity, 	
				".$table.".discount_id,	
				".$table.".old_orders_id,	
				pd.language_id, 
				cd2.categories_name AS parent_categories_heading_title
				FROM
					products AS p
				LEFT JOIN
					products_description AS pd ON p.products_id = pd.products_id
				AND pd.language_id = ".$lang_id."
				LEFT JOIN
					products_to_categories AS p2c ON p2c.products_id = p.products_id
				LEFT JOIN
					categories_description AS cd ON cd.categories_id = p2c.categories_id
                AND cd.language_id = ".$lang_id."
				RIGHT JOIN
					".$table." ON ".$table.".products_id = p.products_id
				LEFT JOIN
					categories AS c ON c.categories_id = cd.categories_id
				LEFT JOIN
					categories_description AS cd2 ON cd2.categories_id = c.parent_id
                AND cd2.language_id = ".$lang_id."
				WHERE
					".$product_status."
				
			";
                        //exit($sql);
			
			if($_SESSION['customer_id'])
			{
				$sql .= " AND ".$table.".customers_id = ".(int)$_SESSION['customer_id'];
			}
			else{
				$sql .= " AND ".$table.".customers_id = '".$FSESSION->ID."'";
			}
			
			/* sorting by timestamp - so that the tickets won't flip on page reload */
			$sql .= " ORDER BY ".$table.".customers_basket_date_added ASC";
			$display=FLAT_CART_CONTENTS;
			//$html = "\n".'<ul style="display: '.$display.';" id="ajax_cart">';
			$html = "\n".'<ul style="display:true" id="ajax_cart">';
			$sum = (float)0;
			$i = (int)0;
			
			$result = tep_db_query($sql);
			while($seat = tep_db_fetch_array($result))
			{
				
				$categories_name=$seat['categories_heading_title'];
				
				if ($seat['products_sku'] == 6)
				{
					$item='<span class="pn">'.(($seat['customers_basket_quantity'] > 1)? '1 x ':'').'<a class="product_cart_link" href="product_info.php?products_id='.$seat['products_id'].'">'.$seat['products_name'].'</a></span>';
				}else
				{
					$item='<span class="pn">'.(($seat['customers_basket_quantity'] > 1)? $seat['customers_basket_quantity'].' x ':'').'<a class="product_cart_link" href="product_info.php?products_id='.$seat['products_id'].'">'.$seat['products_name'].'</a></span>';
				}
				
				//box office
				if($seat['old_orders_id']==0)
				{
				   $seat['old_orders_id']='';
				}else{
				   $seat['old_orders_id']='-'.$seat['old_orders_id'];
				}
				
				if ($seat['products_sku'] == 6)
				{
					$cart_quantity_display = 1;
					}
					else
					{
					$cart_quantity_display = $seat['customers_basket_quantity'];      
					}
				switch($mode)
				{
					
					case 'html':
						#########################################################
						$html .=
						"\n".'<li id="c'.$seat['products_id'].$seat['old_orders_id'].'" class="c '.(($seat['color_code']!='')? ' '.$this->color_to_class($seat['color_code']):'').'">'.
						'<span class="cht">'.(($seat['parent_categories_heading_title'])? $seat['parent_categories_heading_title']:$this->cat_name($seat['categories_id'])).'</span><div style="clear:both"></div>'.$item .'<span class="cnt">'.$cart_quantity_display.'</span>';
						'<a class="product_cart_link" href="' . tep_href_link(FILENAME_PRODUCT_INFO,'products_id='.$seat['products_id']).'">'
						.$seat['products_name'].
						'</a></span>'.
						'<span class="cnt">'.$cart_quantity_display.'</span>';
						
						/* don't show the remove button while checking out */
						if($_SERVER['PHP_SELF']!=DIR_WS_HTTP_CATALOG . 'checkout_confirmation.php')
						{
							$html .='<div id="del'.$seat['products_id'].'" style="font-size:18px;color:red;" class="bd bi-x"></div>';
						}
						##########################################################
						if($seat['discount_id']==0)
						{
							/* no discount */
							//but what about a special price?							
							if($new_price = tep_get_products_special_price($seat['products_id'])){
							$price = ($new_price*$seat['customers_basket_quantity']);
							}else{							
							$price = ($seat['products_price']*$seat['customers_basket_quantity']);
							}
						}
						else 
						{
							/* discounted */
							$price = ($this->getDiscountPrice((float)$seat['products_price'], (int)$seat['discount_id'])*$seat['customers_basket_quantity']);
						}
						//2017 - customer group?
								$query = tep_db_query("select g.customers_groups_discount from " . TABLE_CUSTOMERS_GROUPS . " g inner join  " . TABLE_CUSTOMERS  . " c on g.customers_groups_id = c.customers_groups_id and c.customers_id = '" . (int)$FSESSION->customer_id . "'");
								$query_result = tep_db_fetch_array($query);
								$customers_groups_discount = $query_result['customers_groups_discount'];
								$query = tep_db_query("select customers_discount from " . TABLE_CUSTOMERS . " where customers_id =  '" . (int)$FSESSION->customer_id . "'");
								$query_result = tep_db_fetch_array($query);
								$customer_discount = $query_result['customers_discount'];
								$customer_discount = $customer_discount + $customers_groups_discount;
								if ($customer_discount !== 0) 
								{
								 $price = $price - $price * abs($customer_discount) / 100;	
								}
						//tax fix
						$price =(tep_add_tax($price, tep_get_tax_rate($seat['products_tax_class_id'])));
						/////////////////////////////////////////////////////////////////////////////////////

						 $html .='<span class="pp">'.$currencies->format($price, true, $currency).'</span>'."</li>";
						 $sum += $price;
						 $i+=$cart_quantity_display;
					break;
					
					/* unsure if this is even required here */
					case 'json':
						$arr[] = $seat;
						$sum += $seat['products_price']*$seat['customers_basket_quantity'];
						$i += $cart_quantity_display;
						break;
				}
			}
			
			//let's find out if there is a left column shopping cart?		
			$shopping_cart_query = tep_db_query('select  infobox_display, infobox_file_name from ' . TABLE_INFOBOX_CONFIGURATION . ' where template_id = ' . tep_db_input(TEMPLATE_ID));

			while ($shopping_cart = tep_db_fetch_array($shopping_cart_query)) 
			{
			if (($shopping_cart['infobox_file_name'] == 'shopping_cart.php') && ($shopping_cart['infobox_display'] == 'no')) 
			{
			$top="_top";
			}
		}

			
			/* xHTML validation fix: */
			if($i==0)
			{
			$html .= "<li style='display:none;'><span class='pp'>0</span><span class='cnt'>0</span></li>";
			}	
			$html .= "\n</ul>";
			$html .= "\n<div id='ajax_cart_stats".$top."'>
					  \n<span id='total_seats'><a href='shopping_cart.php'>" . (($i==1)? '1 ' . ITEM . '': $i . ' ' . ITEMS . '') . "</a></span>
					  \n" . '<span id="total_price">' . $currencies->format($sum, true, $currency) . "</span>
					  \n" . "</div>
					  \n";
					  
			tep_db_free_result($result);
			switch($mode){
				case 'html':return $html;break;
				case 'json':return $arr;break;
			}
		} /* end: getCart() */
		
		
		/* might be merged with below function */
		function clearCart($customers_id,$cPath)
		{
			global $FSESSION, $cart;
			$cart->reset();
			tep_db_query("DELETE FROM ".TABLE_CUSTOMERS_BASKET." WHERE customers_id = ".(int)$customers_id);
			
			/* log an event - if live logging is enabled in options */
			if(SEATPLAN_LOGGING=='true'){$log = $this->tep_logSeatPlanEvent(1,(int)$cPath,0,(int)$customers_id,$FSESSION->ID,'cart timeout');}else{$log = 'disabled';}
			
			return array(
				'status' => 'terminated',
				'log' => $log
			);
		} /* end: clearCart() */
		
		
		function clearTempCart($cPath)
		{
			global $FSESSION, $cart;
			tep_db_query("DELETE FROM ".TABLE_CUSTOMERS_TEMP_BASKET." WHERE customers_id = '".$FSESSION->ID."'");
			tep_db_query("DELETE FROM ".TABLE_SESSIONS." where sesskey = '".$FSESSION->ID."'");
			
			/* log an event - if live logging is enabled in options*/
			if(SEATPLAN_LOGGING=='true'){$log = $this->tep_logSeatPlanEvent(1,(int)$cPath,0,0,$FSESSION->ID,'temp cart timeout');}else{$log = 'disabled';}
			
			$cart->reset();
			session_destroy();
			return array(
				'status' => 'terminated',
				'log' => $log
			);
		} /* end: clearTempCart() */
		
		
		function checkLocks($product_id){
			$bool = false;
			$result = tep_db_query("SELECT * FROM carts_united WHERE products_id = ".(int)$product_id);
			$check = tep_db_fetch_array($result);
			if($check['products_id']){$bool = true;}
			
			/* free up resources and return */
			tep_db_free_result($result);
			return $bool;
		} /* end: checkLocks() */
		
		
		function logSeatPlanEvent($log_level,$cPath,$products_id,$customers_id,$sesskey,$event)
		{
			if($products_id != 0){$products_name = $this->product_to_name($products_id);}else{$products_name='&nbsp;';}
			$sql='
				INSERT INTO `seatplan_events`
				(`log_level`, `cPath`, `products_id`, `products_name`, `customers_id`, `sesskey`, `timestamp`, `event`)
				VALUES ('.(int)$log_level.', '.(int)$cPath.', '.(int)$products_id.', "'.$products_name.'", '.(int)$customers_id.', "'.$sesskey.'", NOW(), "'.$event.'")
			';
			tep_db_query($sql);
		} /* end: logSeatPlanEvent() */
		
		
		function color_to_class($color){
			if(!$color){return '';}
			switch($color){
				case 'blue':			$cls='bl';break;
				case 'red':				$cls='rd';break;
				case 'green':			$cls='gr';break;
				case 'fuchsia':			$cls='fu';break;
				case 'yellow':			$cls='ye';break;
				case 'salmon':			$cls='sa';break;
				case 'teal':			$cls='te';break;
				case 'skyblue':			$cls='sb';break;
				case 'orange':			$cls='or';break;
				case 'palegreen':		$cls='pg';break;
				case 'thistle':			$cls='th';break;
				default:				$cls='';
			}
			return $cls;
		} /* end: color_to_class() */
		
		
		function product_to_name($products_id)
		{
			
			$sql = "SELECT products_name FROM ".TABLE_PRODUCTS_DESCRIPTION." WHERE products_id = ".(int)$products_id;
			$result = tep_db_query($sql);
	
			$row = tep_db_fetch_array($result);
			$products_name = $row['products_name'];
			
			/* free up resources and return */
			tep_db_free_result($result);
			return $products_name;
		} /* end: product_to_name() */
		
		function cat_name($cPath=0)
		{
			global $FSESSION;
			$sql= "SELECT categories_name as cht FROM " . TABLE_CATEGORIES_DESCRIPTION . "  WHERE categories_id= '" . $cPath . "' and language_id = '" . (int)$FSESSION->languages_id . "'";
			$arr = tep_db_fetch_array(tep_db_query($sql));
			return $arr['cht'];
		}
		
		function getDiscountPrice($price, $discount_id)
		{
			$sql = 'SELECT * FROM '.TABLE_SALEMAKER_SALES.' WHERE sale_id ='.(int)$discount_id;
			$sm = (object)tep_db_fetch_array(tep_db_query($sql));
			switch($sm->sale_deduction_type){
				
				/* fixed deduction */
				case 0: $price = ($price - $sm->sale_deduction_value);
				break;
				/* percentual deduction */
				case 1: $price = ($price * (100-$sm->sale_deduction_value)/100);
				break;
				//March 2013
				case 2://new price				
			   $price = $sm->sale_deduction_value;						
				break;
			}
			return $price;
		} /* end: getDiscountPrice() */
		
	}
?>
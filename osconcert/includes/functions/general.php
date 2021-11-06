<?php
/*

  osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

osCommRes, Services Oline 
http://www.oscommres.com 
Copyright (c) 2005 osCommRes 

Freeway eCommerce
http://www.openfreeway.org
Copyright (c) 2007 ZacWare

Released under the GNU General Public License
*/

// Redirect to another page or site
  function tep_redirect($url) {
    if ( (strstr($url, "\n") != false) || (strstr($url, "\r") != false) ) { 
      tep_redirect(tep_href_link('index.php', '', 'NONSSL', false));
    }

    if ( (ENABLE_SSL == true) && (getenv('HTTPS') == 'on') ) { // We are loading an SSL page
      if (substr($url, 0, strlen(HTTP_SERVER . DIR_WS_HTTP_CATALOG)) == HTTP_SERVER . DIR_WS_HTTP_CATALOG) { // NONSSL url
        $url = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG . substr($url, strlen(HTTP_SERVER . DIR_WS_HTTP_CATALOG)); // Change it to SSL
      }
    }

    if ( strpos($url, '&amp;') !== false ) {
      $url = str_replace('&amp;', '&', $url);
    }

    header('Location: ' . $url);

    tep_exit();
  }


// function tep_redirect($url) {
  // global $logger,$PHP_SELF,$FGET,$FSESSION;
  // $parts=parse_url($url);
  // if (strpos($parts["path"],FILENAME_CHECKOUT_PAYMENT)!==false && (basename($PHP_SELF)==FILENAME_CHECKOUT_SINGLE || basename($PHP_SELF)==FILENAME_CUSTOMERS ) && isset($GLOBALS["EXECUTE_PAYMENT"])){ // ajax payment redirect
  	// if (strpos($parts["query"],"error")!==false){
		// $splt=preg_split('/&/',$parts["query"]);
		// $args=array();
		
		// for ($icnt=0,$n=count($splt);$icnt<$n;$icnt++){
			// $splt1=preg_split("/=/",$splt[$icnt]);
			// if ($splt1[0]!='' && $splt1[1]!='') $args[$splt1[0]]=$splt1[1];
			// $FGET[$splt1[0]]=$splt1[1];
		// }
		// if (isset($args['payment_error']) && is_object($GLOBALS[$args['payment_error']]) && ($error = $GLOBALS[$args['payment_error']]->get_error())) $err_text=$error["error"];
		// else if (isset($args['error_message'])&& $args["error_message"]!="") $err_text=$args["error_message"];
		// else if (isset($args['error']) && $args["error"]) $err_text=$args["error"];
		// else $err_text="Unknown error";
		
		// echo "payment_error||" . urldecode($err_text);
		// exit;
	// }
  // }

  // if ( (strstr($url, "\n") != false) || (strstr($url, "\r") != false) ) {

    // tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));

  // }
	// if ( (ENABLE_SSL == true) && (getenv('HTTPS') == 'on') ) { // We are loading an SSL page

    // if (substr($url, 0, strlen(HTTP_SERVER)) == HTTP_SERVER) { // NONSSL url

      // $url = HTTPS_SERVER . substr($url, strlen(HTTP_SERVER)); // Change it to SSL
	// }
 // }
 	// $FSESSION->close();
	// $url = str_replace('&amp;', '&', $url);
	// header('Location: ' . $url);
	// tep_exit();
// }

////
// Parse the data used in the html tags to ensure the tags will not break
  function tep_parse_input_field_data($data, $parse) {
    return strtr(trim($data), $parse);
  }

  function tep_output_string($string, $translate = false, $protected = false) {
    if ($protected == true) {
      return htmlspecialchars($string);
    } else {
      if ($translate == false) {
        return tep_parse_input_field_data($string, array('"' => '&quot;'));
      } else {
        return tep_parse_input_field_data($string, $translate);
      }
    }
  }

  function tep_output_string_protected($string) {
    return tep_output_string($string, false, true);
  }

  function tep_sanitize_string($string) {
    //$string = ereg_replace(' +', ' ', trim($string));
	$string = preg_replace('/ +/', ' ', trim($string));
    return preg_replace("/[<>]/", '_', $string);
  }

////
// Return a random row from a database query
  function tep_random_select($query) {
    $random_product = '';
    $random_query = tep_db_query($query);
    $num_rows = tep_db_num_rows($random_query);
    if ($num_rows > 0) {
      $random_row = tep_rand(0, ($num_rows - 1));
      tep_db_data_seek($random_query, $random_row);
      $random_product = tep_db_fetch_array($random_query);
    }

    return $random_product;
  }

////
// Return a product's name
// TABLES: products
  function tep_get_products_name($product_id, $language = '') {
    global $FSESSION;

    if (empty($language)) $language = $FSESSION->languages_id;

    $product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_name'];
  }

////
// Return a product's special price (returns nothing if there is no offer)
// TABLES: products

  function tep_get_customers_groups_id() {
    global $FSESSION;
    $customers_groups_query = tep_db_query("select customers_groups_id from " . TABLE_CUSTOMERS . " where customers_id =  '" . (int)$FSESSION->customer_id . "'");
    $customers_groups_id = tep_db_fetch_array($customers_groups_query);
    return $customers_groups_id['customers_groups_id'];
  }
  
  function tep_get_products_special_price($product_id,$sale_id=0,$cart_item_id='') {
  	global $FSESSION,$cart;
    $product_query = tep_db_query("select products_price, products_model,products_price_break from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
    if (tep_db_num_rows($product_query)) {
      $product = tep_db_fetch_array($product_query);

	  $product_price = $product['products_price'];
	  if ($product["products_price_break"]=="Y") return false;
    } else {
	  return false;
    }

    $customer_groups_id = tep_get_customers_groups_id();
	$specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "' and status = '1' and customers_id = '" . (int)$FSESSION->customer_id . "' and customers_groups_id = '0'");
	if (!tep_db_num_rows($specials_query)) {
	  $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "' and status = '1' and customers_groups_id = '" . (int)$customer_groups_id . "' and customers_id = '0'");
	  if (!tep_db_num_rows($specials_query)) {
	    $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "' and status = '1' and customers_groups_id = '0' and customers_id = '0'");
	  }
	}
    //$specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $product_id . "' and status");


	
	$specials_query = tep_db_query("select specials_new_products_price,customers_id,customers_groups_id from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "' and status = '1'");

    if (tep_db_num_rows($specials_query)) {
      $special = tep_db_fetch_array($specials_query);
	  	if ($FSESSION->customer_id>0 && $FSESSION->customer_id==$special["customers_id"]){
		  $special_price = $special['specials_new_products_price'];
		} else if ($customer_groups_id>0 && $customer_groups_id==$special["customers_groups_id"]){
		  $special_price = $special['specials_new_products_price'];
		} else if ($special["customers_id"]<=0 && $special["customers_groups_id"]<=0){
		  $special_price = $special['specials_new_products_price'];
		} else {
			$special_price=false;
		}
    } else {
	  $special_price = false;
    }
	
    if(substr($product['products_model'], 0, 4) == 'GIFT') 
	{    //Never apply a salededuction to Ian Wilson's Giftvouchers
      return $special_price;
    }

    $product_to_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
    $product_to_categories = tep_db_fetch_array($product_to_categories_query);
    $category = $product_to_categories['categories_id'];

	$option_where='';
	if ($sale_id>0) 
		$option_where=" sale_id=" . $sale_id . " and ";
	else $option_where=" sale_discount_type='S' and ";

	
	$sDate=getServerDate(true);
    $sale_query = tep_db_query("select sale_specials_condition, sale_deduction_value, sale_deduction_type,sale_id,sale_discount_type from " . TABLE_SALEMAKER_SALES . " where " . $option_where . " ((sale_categories_all='' and sale_products_selected='') or sale_categories_all like '%," . tep_db_input($category) . ",%' or sale_products_selected like '%," . (int)$product_id .",%') and sale_status = '1' and (sale_date_start <= '" . $sDate. "' or sale_date_start = '0000-00-00') and (sale_date_end >= '" . $sDate . "' or sale_date_end = '0000-00-00') and (sale_pricerange_from <= '" . $product_price . "' or sale_pricerange_from = '0') and (sale_pricerange_to >= '" . $product_price . "' or sale_pricerange_to = '0')");

    if (tep_db_num_rows($sale_query)) {
      $sale = tep_db_fetch_array($sale_query);
    } else {
	  return $special_price;
    }

    if (!$special_price) {
      $tmp_special_price = $product_price;
    } else {
      $tmp_special_price = $special_price;
    }
	if($cart_item_id!='' && $sale['sale_discount_type']=='S')
		$cart->contents[$cart_item_id]['salemaker_id']=$sale['sale_id'];
    switch ($sale['sale_deduction_type']) {
      case 0:
        $sale_product_price = $product_price - $sale['sale_deduction_value'];
        $sale_special_price = $tmp_special_price - $sale['sale_deduction_value'];
        break;
      case 1:
        $sale_product_price = $product_price - (($product_price * $sale['sale_deduction_value']) / 100);
        $sale_special_price = $tmp_special_price - (($tmp_special_price * $sale['sale_deduction_value']) / 100);
        break;
      case 2:
        $sale_product_price = $sale['sale_deduction_value'];
        $sale_special_price = $sale['sale_deduction_value'];
        break;
      default:
        return $special_price;
    }

    if ($sale_product_price < 0) {
      $sale_product_price = 0;
    }

    if ($sale_special_price < 0) {
      $sale_special_price = 0;
    }

    if (!$special_price) {
      return number_format($sale_product_price, 4, '.', '');
	} else {
      switch($sale['sale_specials_condition']){
        case 0:
          return number_format($sale_product_price, 4, '.', '');
          break;
        case 1:
          return number_format($special_price, 4, '.', '');
          break;
        case 2:
          return number_format($sale_special_price, 4, '.', '');
          break;
        default:
          return number_format($special_price, 4, '.', '');
      }
    }
  }
  function tep_get_salemaker_price($special_price,$product_price,$sale){
    if (!$special_price) {
      $tmp_special_price = $product_price;
    } else {
      $tmp_special_price = $special_price;
    }

    switch ($sale['sale_deduction_type']) {
      case 0:
        $sale_product_price = $product_price - $sale['sale_deduction_value'];
        $sale_special_price = $tmp_special_price - $sale['sale_deduction_value'];
        break;
      case 1:
        $sale_product_price = $product_price - (($product_price * $sale['sale_deduction_value']) / 100);
        $sale_special_price = $tmp_special_price - (($tmp_special_price * $sale['sale_deduction_value']) / 100);
        break;
      case 2:
        $sale_product_price = $sale['sale_deduction_value'];
        $sale_special_price = $sale['sale_deduction_value'];
        break;
      default:
        return $special_price;
    }

    if ($sale_product_price < 0) {
      $sale_product_price = 0;
    }

    if ($sale_special_price < 0) {
      $sale_special_price = 0;
    }

    if (!$special_price) {
      return number_format($sale_product_price, 4, '.', '');
	} else {
      switch($sale['sale_specials_condition']){
        case 0:
          return number_format($sale_product_price, 4, '.', '');
          break;
        case 1:
          return number_format($special_price, 4, '.', '');
          break;
        case 2:
          return number_format($sale_special_price, 4, '.', '');
          break;
        default:
          return number_format($special_price, 4, '.', '');
      }
    }
  }

  function tep_get_products_special_price_only($products_id){
  	global $FSESSION;
	$customer_id=$FSESSION->get('customer_id','int',0);
	
    $customer_groups_id = tep_get_customers_groups_id($customer_id);
	$specials_query = tep_db_query("select specials_new_products_price,customers_id,customers_groups_id from " . TABLE_SPECIALS . " where products_id = '" . (int)$products_id . "' and status = '1'");

		if (tep_db_num_rows($specials_query)) {
		  $special = tep_db_fetch_array($specials_query);
			if ($customer_id>0 && $customer_id==$special["customers_id"]){
			  $special_price = $special['specials_new_products_price'];
			} else if ($customer_groups_id>0 && $customer_groups_id==$special["customers_groups_id"]){
			  $special_price = $special['specials_new_products_price'];
			} else if ($special["customers_id"]<=0 && $special["customers_groups_id"]<=0){
			  $special_price = $special['specials_new_products_price'];
			} else {
				$special_price=false;
			}
		} else {
		  $special_price = false;
		}
		return $special_price;
	}
////
// Return a product's stock
// TABLES: products
  function tep_get_products_stock($products_id) {
    $products_id = tep_get_prid($products_id);
    $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
    $stock_values = tep_db_fetch_array($stock_query);

    return $stock_values['products_quantity'];
  }

////
// Check if the required stock is available
// If insufficent stock is available return an out of stock message
  function tep_check_stock($products_id, $products_quantity) {
    $stock_left = tep_get_products_stock($products_id) - $products_quantity;
    $out_of_stock = '';

    if ($stock_left < 0) {
      $out_of_stock = '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
    }

    return $out_of_stock;
  }
  //added Aug 2012 sakwoya@sakwoya.co.uk (Graeme Tyson)
    function tep_check_stock_ga($products_id, $products_quantity) {
	$stock_av=tep_get_products_stock($products_id);
    $stock_left = $stock_av - $products_quantity;
    $out_of_stock_ga = '';

    if ($stock_left < 0) {
      $out_of_stock_ga = '<span class="markProductOutOfStock">' .STOCK_MARK_PRODUCT_OUT_OF_STOCK_GA .' '. $stock_av.OUT_OF_STOCK_CANT_CHECKOUT_GA_TRAILER.'</span>';
    }

    return $out_of_stock_ga;
  }
   /* function tep_get_attributes_stock($products_id,$options_id,$values_id) {
    $products_id = tep_get_prid($products_id);
    $stock_query = tep_db_query("select products_attributes_quantity from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' and options_id='".$options_id."' and options_values_id='".$values_id."'");
	//echo "select products_attributes_quantity from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' and options_id='".$options_id."' and options_values_id='".$values_id."'";
    $stock_values = tep_db_fetch_array($stock_query);

    return $stock_values['products_attributes_quantity'];
  }*/
  
   function tep_get_attributes_stock($products_id,$attribute_id) 
   {
    $products_id = tep_get_prid($products_id);
	$attribute_id=tep_get_sorted_attribute_ids($attribute_id);
   // $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS_STOCK . " where products_id = '" . (int)$products_id . "' and attributes_id='". tep_db_input($attribute_id)."'");
	//echo "select products_attributes_quantity from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' and options_id='".$options_id."' and options_values_id='".$values_id."'";
    $stock_values = tep_db_fetch_array($stock_query);

    return $stock_values['products_quantity'];
  }

////
// Check if the required stock is available
// If insufficent stock is available return an out of stock message
  /*function tep_check_attribute_stock($products_id, $options_id,$values_id,$products_quantity) {
    $stock_left = tep_get_attributes_stock($products_id,$options_id,$values_id) - $products_quantity;
    $out_of_stock = '';

    if ($stock_left <0) {
      $out_of_stock = '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
    }

    return $out_of_stock;
  }*/
  
  function tep_check_attribute_stock($products_id, $attribute_id,$products_quantity) {
  	$attrb_splt=preg_split("/-/",$attribute_id);
	$cart_count=0;
	if(sizeof($attrb_splt)>0){
		$attrb_id="";
		for($i=0;$i<sizeof($attrb_splt);$i++){
			$splt=split("/{/",$attrb_splt[$i]);
			$value="{". $splt[0]."}".substr($splt[1],0,-1);
			$attrb_id.=$value;
		}
	}
  	$cart_count=tep_attribute_count_cart($products_id.$attrb_id);

   $stock_exists = tep_get_attributes_stock($products_id,$attribute_id) - $cart_count;   
   $stock_left=$stock_exists - $products_quantity;
	
    $out_of_stock = '';
    if ($stock_left < 0 ) {
      $out_of_stock = '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
    }
    return $out_of_stock;
  }
  // function tep_get_sorted_attribute_ids($att_ids)
// {
	// $attribute_array=preg_split('/-/',$att_ids);
	// for($inc=0;$inc<count($attribute_array);$inc++)
	// {
		// $option_values=preg_split('/{/',$attribute_array[$inc]);
		// $attrb_list[$option_values[0]]=substr($option_values[1],0,-1);
	// }
	// ksort($attrb_list);
	// reset($attrb_list);
	// $attribute_id="";
	// while (list($key,$value)=each($attrb_list))
		// $attribute_id.=$key . '{' . $value . '}-';
	// $attribute_id=substr($attribute_id,0,-1);
	// return $attribute_id;
// }
  

////
// Break a word in a string if it is longer than a specified length ($len)
  function tep_break_string($string, $len, $break_char = '-') {
    $l = 0;
    $output = '';
    for ($i=0, $n=strlen($string); $i<$n; $i++) {
      $char = substr($string, $i, 1);
      if ($char != ' ') {
        $l++;
      } else {
        $l = 0;
      }
      if ($l > $len) {
        $l = 1;
        $output .= $break_char;
      }
      $output .= $char;
    }

    return $output;
  }

////
// Return all HTTP GET variables, except those passed as a parameter
  function tep_get_all_get_params($exclude_array = '') {
    global $FGET,$FSESSION;

	if (!is_array($exclude_array)) $exclude_array = array();
	$exclude_array[]=$FSESSION->NAME;
	$exclude_array[]='error';
	$exclude_array[]='x';
	$exclude_array[]='y';
	$exclude_array[]='option';
	$exclude_array[]='openfile';
	$exclude_array[]='component';
    $get_url = '';
    if (is_array($FGET) && (sizeof($FGET) > 0)) {
      reset($FGET);
      //FOREACH
     // while (list($key, $value) = each($FGET)) {	
		foreach($FGET as $key => $value)
		{  
        if ( (strlen($value) > 0) && ($key != $FSESSION->NAME) && ($key != 'error') && (!in_array($key, $exclude_array)) && ($key != 'x') && ($key != 'y') ) {
          $get_url .= $key . '=' . rawurlencode(stripslashes($value)) . '&';
        }
      }
    }

    return $get_url;
  }

////
// Returns an array with countries
// TABLES: countries
  function tep_get_countries($countries_id = '', $with_iso_codes = false) {
    $countries_array = array();
    if (tep_not_null($countries_id)) {
      if ($with_iso_codes == true) {
        $countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "' order by countries_name");
        $countries_values = tep_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name'],
                                 'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
                                 'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
      } else {
        $countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "'");
        $countries_values = tep_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name']);
      }
    } else {
      $countries = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " where countries_id<999 order by countries_name");
      while ($countries_values = tep_db_fetch_array($countries)) {
        $countries_array[] = array('countries_id' => $countries_values['countries_id'],
                                   'countries_name' => $countries_values['countries_name']);
      }
    }

    return $countries_array;
  }

////
// Alias function to tep_get_countries, which also returns the countries iso codes
  function tep_get_countries_with_iso_codes($countries_id) {
    return tep_get_countries($countries_id, true);
  }

////
// Generate a path to categories
  function tep_get_path($current_category_id = '') {
    global $cPath_array;

    if (tep_not_null($current_category_id)) {
      $cp_size = sizeof($cPath_array);
      if ($cp_size == 0) {
        $cPath_new = $current_category_id;
      } else {
        $cPath_new = '';
        $last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cPath_array[($cp_size-1)] . "'");
        $last_category = tep_db_fetch_array($last_category_query);

        $current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
        $current_category = tep_db_fetch_array($current_category_query);

        if ($last_category['parent_id'] == $current_category['parent_id']) {
          for ($i=0; $i<($cp_size-1); $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        } else {
          for ($i=0; $i<$cp_size; $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        }
        $cPath_new .= '_' . $current_category_id;

        if (substr($cPath_new, 0, 1) == '_') {
          $cPath_new = substr($cPath_new, 1);
        }
      }
    } else {      
      $cPath_new = implode('_', $cPath_array);
    }
    return 'cPath=' . $cPath_new;
  }

////
// Returns the clients browser
  function tep_browser_detect($component) {
    return stristr($_SERVER['HTTP_USER_AGENT'], $component);
  }

////
// Alias function to tep_get_countries()
  function tep_get_country_name($country_id) {
    $country_array = tep_get_countries($country_id);

    return $country_array['countries_name'];
  }

////
// Returns the zone (State/Province) name
// TABLES: zones
  function tep_get_zone_name($country_id, $zone_id, $default_zone) {
    $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
    if (tep_db_num_rows($zone_query)) {
      $zone = tep_db_fetch_array($zone_query);
      return $zone['zone_name'];
    } else {
      return $default_zone;
    }
  }

////
// Returns the zone (State/Province) code
// TABLES: zones
  function tep_get_zone_code($country_id, $zone_id, $default_zone) {
    $zone_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
    if (tep_db_num_rows($zone_query)) {
      $zone = tep_db_fetch_array($zone_query);
      return $zone['zone_code'];
    } else {
      return $default_zone;
    }
  }
  


////
// Wrapper function for round()
  function tep_round($number, $precision) {
    if (strpos($number, '.') && (strlen(substr($number, strpos($number, '.')+1)) > $precision)) {
      $number = substr($number, 0, strpos($number, '.') + 1 + $precision + 1);

      if (substr($number, -1) >= 5) {
        if ($precision > 1) {
          $number = substr($number, 0, -1) + ('0.' . str_repeat(0, $precision-1) . '1');
        } elseif ($precision == 1) {
          $number = substr($number, 0, -1) + 0.1;
        } else {
          $number = substr($number, 0, -1) + 1;
        }
      } else {
        $number = substr($number, 0, -1);
      }
    }

    return $number;
  }

////
// Returns the tax rate for a zone / class
// TABLES: tax_rates, zones_to_geo_zones
  function tep_get_tax_rate($class_id, $country_id = -1, $zone_id = -1) {
    global $FSESSION;

    if ( ($country_id == -1) && ($zone_id == -1) ) {
      if (!$FSESSION->is_registered('customer_id')) {
        $country_id = STORE_COUNTRY;
        $zone_id = STORE_ZONE;
      } else {
        $country_id = $FSESSION->customer_country_id;
        $zone_id = $FSESSION->customer_zone_id;
      }
    }

    $tax_query = tep_db_query("select sum(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' group by tr.tax_priority");
    if (tep_db_num_rows($tax_query)) {
      $tax_multiplier = 1.0;
      while ($tax = tep_db_fetch_array($tax_query)) {
        $tax_multiplier *= 1.0 + ($tax['tax_rate'] / 100);
      }
      return ($tax_multiplier - 1.0) * 100;
    } else {
      return 0;
    }
  }

////
// Return the tax description for a zone / class
// TABLES: tax_rates;
  function tep_get_tax_description($class_id, $country_id, $zone_id) {
    $tax_query = tep_db_query("select tax_description from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' order by tr.tax_priority");
    if (tep_db_num_rows($tax_query)) {
      $tax_description = '';
      while ($tax = tep_db_fetch_array($tax_query)) {
        $tax_description .= $tax['tax_description'] . ' + ';
      }
      $tax_description = substr($tax_description, 0, -3);

      return $tax_description;
    } else {
      return TEXT_UNKNOWN_TAX_RATE;
    }
  }

////
// Add tax to a products price
  function tep_add_tax($price, $tax,$deposit=100) {
    global $currencies;
    if ( (DISPLAY_PRICE_WITH_TAX == 'true') && ($tax > 0) ) {
      return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']) + tep_calculate_tax($price, $tax);
    } else {
      return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
    }
  }

// Calculates Tax rounding the result
  function tep_calculate_tax($price, $tax) {
    global $currencies;

    return tep_round($price * $tax / 100, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
  }

////
// Return the number of products in a category
// TABLES: products, products_to_categories, categories
  function tep_count_products_in_category($category_id, $include_inactive = false) {
    $products_count = 0;
    if ($include_inactive == true) {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$category_id . "'");
    } else {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . (int)$category_id . "'");
    }
    $products = tep_db_fetch_array($products_query);
    $products_count += $products['total'];

    $child_categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
    if (tep_db_num_rows($child_categories_query)) {
      while ($child_categories = tep_db_fetch_array($child_categories_query)) {
        $products_count += tep_count_products_in_category($child_categories['categories_id'], $include_inactive);
      }
    }

    return $products_count;
  }

////
// Return true if the category has subcategories
// TABLES: categories
  function tep_has_category_subcategories($category_id) {
    $child_category_query = tep_db_query("select count(*) as count from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
    $child_category = tep_db_fetch_array($child_category_query);

    if ($child_category['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }

////
// Returns the address_format_id for the given country
// TABLES: countries;
  function tep_get_address_format_id($country_id) {
    $address_format_query = tep_db_query("select address_format_id as format_id from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$country_id . "'");
    if (tep_db_num_rows($address_format_query)) {
      $address_format = tep_db_fetch_array($address_format_query);
      return $address_format['format_id'];
    } else {
      return '1';
    }
  }
  
// Return a formatted address
// TABLES: address_format
  function tep_address_format($address_format_id, $address, $html, $boln, $eoln) 
  {
    $address_format_query = tep_db_query("select address_format as format from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . (int)$address_format_id . "'");
    $address_format = tep_db_fetch_array($address_format_query);

    $company = tep_output_string_protected($address['company']);
    if (isset($address['firstname']) && tep_not_null($address['firstname'])) {
      $firstname = tep_output_string_protected($address['firstname']);
      $lastname = tep_output_string_protected($address['lastname']);
    } elseif (isset($address['name']) && tep_not_null($address['name'])) {
      $firstname = tep_output_string_protected($address['name']);
      $lastname = '';
    } else {
      $firstname = '';
      $lastname = '';
    }
	$customer_email = tep_output_string_protected($address['customer_email']);
    $street = tep_output_string_protected($address['street_address']);
    $suburb = tep_output_string_protected($address['suburb']);
    $city = tep_output_string_protected($address['city']);
    $state = tep_output_string_protected($address['state']);
    if (isset($address['country_id']) && tep_not_null($address['country_id'])) {
      $country = tep_get_country_name($address['country_id']);

      if (isset($address['zone_id']) && tep_not_null($address['zone_id'])) {
        $state = tep_get_zone_name($address['country_id'], $address['zone_id'], $state);
      }
    } elseif (isset($address['country']) && tep_not_null($address['country'])) {
      //$country = tep_output_string_protected($address['country']['title']);
	  $country = tep_output_string_protected($address['country']); 
    } else {
      $country = '';
    }
    $postcode = tep_output_string_protected($address['postcode']);
    $zip = $postcode;

    if ($html) {
// HTML Mode
      $HR = '<hr>';
      $hr = '<hr>';
      if ( ($boln == '') && ($eoln == "\n") ) { // Values not specified, use rational defaults
        $CR = '<br>';
        $cr = '<br>';
        $eoln = $cr;
      } else { // Use values supplied
        $CR = $eoln . $boln;
        $cr = $CR;
      }
    } else {
// Text Mode
      $CR = $eoln;
      $cr = $CR;
      $HR = '----------------------------------------';
      $hr = '----------------------------------------';
    }

    $statecomma = '';
    $streets = $street;
    if ($suburb != '') $streets = $street . $cr . $suburb;
    if ($state != '') $statecomma = $state . ', ';

    $fmt = $address_format['format'];
    eval("\$address = \"$fmt\";");

    if ( (ACCOUNT_COMPANY == 'true') && (tep_not_null($company)) ) {
      $address = $company . $cr . $address;
    }

    return $address;
  }


  
  
  
  
  
  ////
// Return a formatted address
// TABLES: customers, address_book
  function tep_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n") {
   	 /*$cust=tep_db_query("select customers_default_address_id from customers where customers_id='". (int)$customers_id  ."'");
	 $custo=tep_db_fetch_array($cust);
	  // to set the selected address to display
	$address_id=$custo['customers_default_address_id'];*/

    $address_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_customer_email as customer_email, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$address_id . "'");
    $address = tep_db_fetch_array($address_query);

    $format_id = tep_get_address_format_id($address['country_id']);

    return tep_address_format($format_id, $address, $html, $boln, $eoln);
  }

  function tep_row_number_format($number) {
    if ( ($number < 10) && (substr($number, 0, 1) != '0') ) $number = '0' . $number;

    return $number;
  }

  function tep_get_categories($categories_array = '', $parent_id = '0', $indent = '') {
    global $FSESSION;

    if (!is_array($categories_array)) $categories_array = array();

    $categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id = '" . (int)$parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "' order by sort_order, cd.categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_array[] = array('id' => $categories['categories_id'],
                                  'text' => $indent . $categories['categories_name']);

      if ($categories['categories_id'] != $parent_id) {
        $categories_array = tep_get_categories($categories_array, $categories['categories_id'], $indent . '&nbsp;&nbsp;');
      }
    }

    return $categories_array;
  }

  function tep_get_manufacturers($manufacturers_array = '') {
    if (!is_array($manufacturers_array)) $manufacturers_array = array();

    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'], 'text' => $manufacturers['manufacturers_name']);
    }

    return $manufacturers_array;
  }

////
// Return all subcategory IDs
// TABLES: categories
  function tep_get_subcategories(&$subcategories_array, $parent_id = 0) {
    $subcategories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$parent_id . "'");
    while ($subcategories = tep_db_fetch_array($subcategories_query)) {
      $subcategories_array[sizeof($subcategories_array)] = $subcategories['categories_id'];
      if ($subcategories['categories_id'] != $parent_id) {
        tep_get_subcategories($subcategories_array, $subcategories['categories_id']);
      }
    }
  }

// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
  function tep_date_long($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

    $year = (int)substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    return strftime(DATE_FORMAT_LONG, mktime($hour,$minute,$second,$month,$day,$year));
  }

////
// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
// NOTE: Includes a workaround for dates before 01/01/1970 that fail on windows servers
  function tep_date_short($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || empty($raw_date) ) return false;

    $year = substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);
	if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
      return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
    } else {
      return preg_replace('/2037$/', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
    }
  }

////
// Parse search string into indivual objects
  function tep_parse_search_string($search_str = '', &$objects) {
    $search_str = trim(strtolower($search_str));

// Break up $search_str on whitespace; quoted string will be reconstructed later
    $pieces = preg_split('/[\s]+/', $search_str);
    $objects = array();
    $tmpstring = '';
    $flag = '';

    for ($k=0; $k<count($pieces); $k++) {
      while (substr($pieces[$k], 0, 1) == '(') {
        $objects[] = '(';
        if (strlen($pieces[$k]) > 1) {
          $pieces[$k] = substr($pieces[$k], 1);
        } else {
          $pieces[$k] = '';
        }
      }

      $post_objects = array();

      while (substr($pieces[$k], -1) == ')')  {
        $post_objects[] = ')';
        if (strlen($pieces[$k]) > 1) {
          $pieces[$k] = substr($pieces[$k], 0, -1);
        } else {
          $pieces[$k] = '';
        }
      }

// Check individual words

      if ( (substr($pieces[$k], -1) != '"') && (substr($pieces[$k], 0, 1) != '"') ) {
        $objects[] = trim($pieces[$k]);

        for ($j=0; $j<count($post_objects); $j++) {
          $objects[] = $post_objects[$j];
        }
      } else {
/* This means that the $piece is either the beginning or the end of a string.
   So, we'll slurp up the $pieces and stick them together until we get to the
   end of the string or run out of pieces.
*/

// Add this word to the $tmpstring, starting the $tmpstring
        $tmpstring = trim(preg_replace('/"/', ' ', $pieces[$k]));

// Check for one possible exception to the rule. That there is a single quoted word.
        if (substr($pieces[$k], -1 ) == '"') {
// Turn the flag off for future iterations
          $flag = 'off';

          $objects[] = trim(preg_replace('/"/', ' ', $pieces[$k]));

          for ($j=0; $j<count($post_objects); $j++) {
            $objects[] = $post_objects[$j];
          }

          unset($tmpstring);

// Stop looking for the end of the string and move onto the next word.
          continue;
        }

// Otherwise, turn on the flag to indicate no quotes have been found attached to this word in the string.
        $flag = 'on';

// Move on to the next word
        $k++;

// Keep reading until the end of the string as long as the $flag is on

        while ( ($flag == 'on') && ($k < count($pieces)) ) {
          while (substr($pieces[$k], -1) == ')') {
            $post_objects[] = ')';
            if (strlen($pieces[$k]) > 1) {
              $pieces[$k] = substr($pieces[$k], 0, -1);
            } else {
              $pieces[$k] = '';
            }
          }

// If the word doesn't end in double quotes, append it to the $tmpstring.
          if (substr($pieces[$k], -1) != '"') {
// Tack this word onto the current string entity
            $tmpstring .= ' ' . $pieces[$k];

// Move on to the next word
            $k++;
            continue;
          } else {
/* If the $piece ends in double quotes, strip the double quotes, tack the
   $piece onto the tail of the string, push the $tmpstring onto the $haves,
   kill the $tmpstring, turn the $flag "off", and return.
*/
            $tmpstring .= ' ' . trim(preg_replace('/"/', ' ', $pieces[$k]));

// Push the $tmpstring onto the array of stuff to search for
            $objects[] = trim($tmpstring);

            for ($j=0; $j<count($post_objects); $j++) {
              $objects[] = $post_objects[$j];
            }

            unset($tmpstring);

// Turn off the flag to exit the loop
            $flag = 'off';
          }
        }
      }
    }

// add default logical operators if needed
    $temp = array();
    for($i=0; $i<(count($objects)-1); $i++) {
      $temp[] = $objects[$i];
      if ( ($objects[$i] != 'and') &&
           ($objects[$i] != 'or') &&
           ($objects[$i] != '(') &&
           ($objects[$i+1] != 'and') &&
           ($objects[$i+1] != 'or') &&
           ($objects[$i+1] != ')') ) {
        $temp[] = ADVANCED_SEARCH_DEFAULT_OPERATOR;
      }
    }
    $temp[] = $objects[$i];
    $objects = $temp;

    $keyword_count = 0;
    $operator_count = 0;
    $balance = 0;
    for($i=0; $i<count($objects); $i++) {
      if ($objects[$i] == '(') $balance --;
      if ($objects[$i] == ')') $balance ++;
      if ( ($objects[$i] == 'and') || ($objects[$i] == 'or') ) {
        $operator_count ++;
      } elseif ( ($objects[$i]) && ($objects[$i] != '(') && ($objects[$i] != ')') ) {
        $keyword_count ++;
      }
    }

    if ( ($operator_count < $keyword_count) && ($balance == 0) ) {
      return true;
    } else {
      return false;
    }
  }

////
// Check date
  function tep_checkdate($date_to_check, $format_string, &$date_array) {
    $separator_idx = -1;

    $separators = array('-', ' ', '/', '.');
    $month_abbr = array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
    $no_of_days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    $format_string = strtolower($format_string);

    if (strlen($date_to_check) != strlen($format_string)) {
      return false;
    }

    $size = sizeof($separators);
    for ($i=0; $i<$size; $i++) {
      $pos_separator = strpos($date_to_check, $separators[$i]);
      if ($pos_separator != false) {
        $date_separator_idx = $i;
        break;
      }
    }

    for ($i=0; $i<$size; $i++) {
      $pos_separator = strpos($format_string, $separators[$i]);
      if ($pos_separator != false) {
        $format_separator_idx = $i;
        break;
      }
    }

    if ($date_separator_idx != $format_separator_idx) {
      return false;
    }

    if ($date_separator_idx != -1) {
      $format_string_array = explode( $separators[$date_separator_idx], $format_string );
      if (sizeof($format_string_array) != 3) {
        return false;
      }

      $date_to_check_array = explode( $separators[$date_separator_idx], $date_to_check );
      if (sizeof($date_to_check_array) != 3) {
        return false;
      }

      $size = sizeof($format_string_array);
      for ($i=0; $i<$size; $i++) {
        if ($format_string_array[$i] == 'mm' || $format_string_array[$i] == 'mmm') $month = $date_to_check_array[$i];
        if ($format_string_array[$i] == 'dd') $day = $date_to_check_array[$i];
        if ( ($format_string_array[$i] == 'yyyy') || ($format_string_array[$i] == 'aaaa') ) $year = $date_to_check_array[$i];
      }
    } else {
      if (strlen($format_string) == 8 || strlen($format_string) == 9) {
        $pos_month = strpos($format_string, 'mmm');
        if ($pos_month != false) {
          $month = substr( $date_to_check, $pos_month, 3 );
          $size = sizeof($month_abbr);
          for ($i=0; $i<$size; $i++) {
            if ($month == $month_abbr[$i]) {
              $month = $i;
              break;
            }
          }
        } else {
          $month = substr($date_to_check, strpos($format_string, 'mm'), 2);
        }
      } else {
        return false;
      }

      $day = substr($date_to_check, strpos($format_string, 'dd'), 2);
      $year = substr($date_to_check, strpos($format_string, 'yyyy'), 4);
    }

    if (strlen($year) != 4) {
      return false;
    }

    if (!settype($year, 'integer') || !settype($month, 'integer') || !settype($day, 'integer')) {
      return false;
    }

    if ($month > 12 || $month < 1) {
      return false;
    }

    if ($day < 1) {
      return false;
    }

    if (tep_is_leap_year($year)) {
      $no_of_days[1] = 29;
    }

    if ($day > $no_of_days[$month - 1]) {
      return false;
    }

    $date_array = array($year, $month, $day);

    return true;
  }

////
// Check if year is a leap year
  function tep_is_leap_year($year) {
    if ($year % 100 == 0) {
      if ($year % 400 == 0) return true;
    } else {
      if (($year % 4) == 0) return true;
    }

    return false;
  }

////
// Return table heading with sorting capabilities
  function tep_create_sort_heading($sortby, $colnum, $heading) {
    global $PHP_SELF;

    $sort_prefix = '';
    $sort_suffix = '';

    if ($sortby) {
      $sort_prefix = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('page', 'info', 'sort')) . 'page=1&sort=' . $colnum . ($sortby == $colnum . 'a' ? 'd' : 'a')) . '" title="' . tep_output_string(TEXT_SORT_PRODUCTS . ($sortby == $colnum . 'd' || substr($sortby, 0, 1) != $colnum ? TEXT_ASCENDINGLY : TEXT_DESCENDINGLY) . TEXT_BY . $heading) . '" class="productListing-heading">' ;
      $sort_suffix = (substr($sortby, 0, 1) == $colnum ? (substr($sortby, 1, 1) == 'a' ? '+' : '-') : '') . '</a>';
    }

    return $sort_prefix . $heading . $sort_suffix;
  }

////
// Recursively go through the categories and retreive all parent categories IDs
// TABLES: categories
  function tep_get_parent_categories(&$categories, $categories_id) {
    $parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id . "'");
    while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
      if ($parent_categories['parent_id'] == 0) return true;
      $categories[sizeof($categories)] = $parent_categories['parent_id'];
      if ($parent_categories['parent_id'] != $categories_id) {
        tep_get_parent_categories($categories, $parent_categories['parent_id']);
      }
    }
  }

////
// Construct a category path to the product
// TABLES: products_to_categories
  function tep_get_product_path($products_id) {
    $cPath = '';	
	//echo "select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1";

    $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1");
    if (tep_db_num_rows($category_query)) {
      $category = tep_db_fetch_array($category_query);

      $categories = array();
      tep_get_parent_categories($categories, $category['categories_id']);

      $categories = array_reverse($categories);

      $cPath = implode('_', $categories);

      if (tep_not_null($cPath)) $cPath .= '_';
      $cPath .= $category['categories_id'];
    }

    return $cPath;
  }

////
// Return a product ID with attributes
  function tep_get_uprid($prid, $params) {
    if (is_numeric($prid)) {
      $uprid = (int)$prid;

      if (is_array($params) && (sizeof($params) > 0)) {
        $attributes_check = true;
        $attributes_ids = '';
//FOREACH
        foreach($params as $option => $value) {
          if (is_numeric($option) && is_numeric($value)) {
            $attributes_ids .= '{' . (int)$option . '}' . (int)$value;
          } else {
            $attributes_check = false;
            break;
          }
        }

        if ($attributes_check == true) {
          $uprid .= $attributes_ids;
        }
      }
    } else {
      $uprid = tep_get_prid($prid);

      if (is_numeric($uprid)) {
        if (strpos($prid, '{') !== false) {
          $attributes_check = true;
          $attributes_ids = '';

// strpos()+1 to remove up to and including the first { which would create an empty array element in explode()
        $attributes = explode('{', substr($prid, strpos($prid, '{')+1));
 
        for ($i=0, $n=sizeof($attributes); $i<$n; $i++) {
          $pair = explode('}', $attributes[$i]);
 
          if (is_numeric($pair[0]) && is_numeric($pair[1])) {
            $attributes_ids .= '{' . (int)$pair[0] . '}' . (int)$pair[1];
          } else {
            $attributes_check = false;
            break;
          }
        }
 
        if ($attributes_check == true) {
          $uprid .= $attributes_ids;
        }
      }
    } else {
      return false;
    }
  }
 
  return $uprid;
}



////
// Return a product ID from a product ID with attributes
  function tep_get_prid($uprid) {
    $pieces = explode('{', $uprid);

    if (is_numeric($pieces[0])) {
      return (int)$pieces[0];
    } else {
      return false;
  }
}



////
// Return a customer greeting
  function tep_customer_greeting() {
    global $FSESSION;
    if ($FSESSION->is_registered('customer_first_name') && $FSESSION->is_registered('customer_id')) {
      $greeting_string = sprintf(TEXT_GREETING_PERSONAL, tep_output_string_protected($FSESSION->customer_first_name), tep_href_link(FILENAME_PRODUCTS_NEW));
    } else {
      $greeting_string = sprintf(TEXT_GREETING_GUEST, tep_href_link(FILENAME_LOGIN, '', 'SSL'), tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
    }
    return $greeting_string;
    
  }


////
//! Send email (text/html) using MIME
// This is the central mail function. The SMTP Server should be configured
// correct in php.ini
// Parameters:
// $to_name           The name of the recipient, e.g. "Jan Wildeboer"
// $to_email_address  The eMail address of the recipient,
//                    e.g. jan.wildeboer@gmx.de
// $email_subject     The subject of the eMail
// $email_text        The text of the eMail, may contain HTML entities
// $from_email_name   The name of the sender, e.g. Shop Administration
// $from_email_adress The eMail address of the sender,
//                    e.g. info@mytepshop.com

  function tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address) {
    if (SEND_EMAILS != 'true') return false;
 
    // Instantiate a new mail object
    $message = new email();

    // Build the text version
    $text = strip_tags($email_text);
    if (EMAIL_USE_HTML == 'true') {
      $message->add_html($email_text, $text);
    } else {
      $message->add_text($text);
    }
    // Send message
	//echo $text;
    $message->build_message();
    $message->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject);
  }

////
// Check if product has attributes
  function tep_has_product_attributes($products_id) {
    $attributes_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "'");
    $attributes = tep_db_fetch_array($attributes_query);

    if ($attributes['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }

////
// Get the number of times a word/character is present in a string
  function tep_word_count($string, $needle) {
    $temp_array = preg_split('/'.$needle.'/', $string);

    return sizeof($temp_array);
  }

  function tep_count_modules($modules = '') {
    $count = 0;

    if (empty($modules)) return $count;

    $modules_array = explode(';', $modules);

    for ($i=0, $n=sizeof($modules_array); $i<$n; $i++) {
      $class = substr($modules_array[$i], 0, strrpos($modules_array[$i], '.'));

      if (isset($GLOBALS[$class]) && is_object($GLOBALS[$class])) {
        if ($GLOBALS[$class]->enabled) {
          $count++;
        }
      }
    }

    return $count;
  }

  function tep_count_payment_modules() {
    return tep_count_modules(MODULE_PAYMENT_INSTALLED);
  }

  function tep_count_shipping_modules() {
    return tep_count_modules(MODULE_SHIPPING_INSTALLED);
  }

  // function tep_create_random_value($length, $type = 'mixed') {
    // if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;

    // $rand_value = '';
    // while (strlen($rand_value) < $length) {
      // if ($type == 'digits') {
        // $char = tep_rand(0,9);
      // } else {
        // $char = chr(tep_rand(0,255));
      // }
     // if ($type == 'mixed') {
        // if (preg_match('/^[a-z0-9]$/i', $char)) $rand_value .= $char;
      // } elseif ($type == 'chars') {
        // if (preg_match('/^[a-z]$/i', $char)) $rand_value .= $char;
      // } elseif ($type == 'digits') {
        // if (preg_match('/^[0-9]$/i', $char)) $rand_value .= $char;
      // }
    // }

    // return $rand_value;
  // }
    function tep_create_random_value($length, $type = 'mixed') {
    if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) $type = 'mixed';

    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $digits = '0123456789';

    $base = '';

    if ( ($type == 'mixed') || ($type == 'chars') ) {
      $base .= $chars;
    }

    if ( ($type == 'mixed') || ($type == 'digits') ) {
      $base .= $digits;
    }

    $value = '';

    if (!class_exists('PasswordHash')) {
      include('includes/classes/passwordhash.php');
    }

    $hasher = new PasswordHash(10, true);

    do {
      $random = base64_encode($hasher->get_random_bytes($length));

      for ($i = 0, $n = strlen($random); $i < $n; $i++) {
        $char = substr($random, $i, 1);

        if ( strpos($base, $char) !== false ) {
          $value .= $char;
        }
      }
    } while ( strlen($value) < $length );

    if ( strlen($value) > $length ) {
      $value = substr($value, 0, $length);
    }

    return $value;
  }

  function tep_array_to_string($array, $exclude = '', $equals = '=', $separator = '&') {
    if (!is_array($exclude)) $exclude = array();

    $get_string = '';
    if (sizeof($array) > 0) {
      foreach($array as $key => $value) {
        if ( (!in_array($key, $exclude)) && ($key != 'x') && ($key != 'y') ) {
          $get_string .= $key . $equals . $value . $separator;
        }
      }
      $remove_chars = strlen($separator);
      $get_string = substr($get_string, 0, -$remove_chars);
    }

    return $get_string;
  }

  function tep_not_null($value) {
    if (is_array($value)) {
      if (sizeof($value) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
        return true;
      } else {
        return false;
      }
    }
  }

////
// Output the tax percentage with optional padded decimals
  function tep_display_tax_value($value, $padding = TAX_DECIMAL_PLACES) {
    if (strpos($value, '.')) {
      $loop = true;
      while ($loop) {
        if (substr($value, -1) == '0') {
          $value = substr($value, 0, -1);
        } else {
          $loop = false;
          if (substr($value, -1) == '.') {
            $value = substr($value, 0, -1);
          }
        }
      }
    }

    if ($padding > 0) {
      if ($decimal_pos = strpos($value, '.')) {
        $decimals = strlen(substr($value, ($decimal_pos+1)));
        for ($i=$decimals; $i<$padding; $i++) {
          $value .= '0';
        }
      } else {
        $value .= '.';
        for ($i=0; $i<$padding; $i++) {
          $value .= '0';
        }
      }
    }

    return $value;
  }

////
// Checks to see if the currency code exists as a currency
// TABLES: currencies
  // function tep_currency_exists($code) {
    // $code = tep_db_prepare_input($code);

    // $currency_code = tep_db_query("select currencies_id from " . TABLE_CURRENCIES . " where code = '" . tep_db_input($code) . "'");
    // if (tep_db_num_rows($currency_code)) {
      // return $code;
    // } else {
      // return false;
    // }
  // }
    function tep_currency_exists($code) {
    $code = tep_db_prepare_input($code);

    $currency_query = tep_db_query("select code from currencies where code = '" . tep_db_input($code) . "' limit 1");
    if (tep_db_num_rows($currency_query)) {
      $currency = tep_db_fetch_array($currency_query);
      return $currency['code'];
    } else {
      return false;
    }
  }

  function tep_string_to_int($string) {
    return (int)$string;
  }

////
// Parse and secure the cPath parameter values
  function tep_parse_category_path($cPath) {
// make sure the category IDs are integers
    $cPath_array = array_map('tep_string_to_int', explode('_', $cPath));

// make sure no duplicate category IDs exist which could lock the server in a loop
    $tmp_array = array();
    $n = sizeof($cPath_array);
    for ($i=0; $i<$n; $i++) {
      if (!in_array($cPath_array[$i], $tmp_array)) {
        $tmp_array[] = $cPath_array[$i];
      }
    }

    return $tmp_array;
  }

////
// Return a random value
  function tep_rand($min = null, $max = null) {
    static $seeded;

    if (!isset($seeded)) {
      mt_srand((double)microtime()*1000000);
      $seeded = true;
    }

    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }

  function tep_setcookie($name, $value = '', $expire = 0, $path = '/', $domain = '', $secure = 0) {
    setcookie($name, $value, $expire, $path, (tep_not_null($domain) ? $domain : ''), $secure);
  }
  
  // function tep_validate_ip_address($ip_address) {
     // if (function_exists('filter_var') && defined('FILTER_VALIDATE_IP')) {
       // return filter_var($ip_address, FILTER_VALIDATE_IP, array('flags' => FILTER_FLAG_IPV4 || FILTER_FLAG_IPV6));
     // }
 
     // if (preg_match('/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/', $ip_address)) {
       // $parts = explode('.', $ip_address);
 
       // foreach ($parts as $ip_parts) {
         // if ( (intval($ip_parts) > 255) || (intval($ip_parts) < 0) ) {
           // return false; // number is not within 0-255
         // }
       // }
 
       // return true;
     // }
 
     // return false;
   // }
  // // function tep_validate_ip_address($ip_address) {
    // // if (function_exists('filter_var') && defined('FILTER_VALIDATE_IP')) {
      // // return filter_var($ip_address, FILTER_VALIDATE_IP, array('flags' => FILTER_FLAG_IPV4));
    // // }

    // // if (preg_match('/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/', $ip_address)) {
      // // $parts = explode('.', $ip_address);

      // // foreach ($parts as $ip_parts) {
        // // if ( (intval($ip_parts) > 255) || (intval($ip_parts) < 0) ) {
          // // return false; // number is not within 0-255
        // // }
      // // }

      // // return true;
    // // }

    // // return false;
  // // }

  // function tep_get_ip_address() {
    // global $HTTP_SERVER_VARS;

    // $ip_address = null;
    // $ip_addresses = array();

    // if (isset($HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR']) && !empty($HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'])) {
      // foreach ( array_reverse(explode(',', $HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'])) as $x_ip ) {
        // $x_ip = trim($x_ip);

        // if (tep_validate_ip_address($x_ip)) {
          // $ip_addresses[] = $x_ip;
        // }
      // }
    // }

    // if (isset($HTTP_SERVER_VARS['HTTP_CLIENT_IP']) && !empty($HTTP_SERVER_VARS['HTTP_CLIENT_IP'])) {
      // $ip_addresses[] = $HTTP_SERVER_VARS['HTTP_CLIENT_IP'];
    // }

    // if (isset($HTTP_SERVER_VARS['HTTP_X_CLUSTER_CLIENT_IP']) && !empty($HTTP_SERVER_VARS['HTTP_X_CLUSTER_CLIENT_IP'])) {
      // $ip_addresses[] = $HTTP_SERVER_VARS['HTTP_X_CLUSTER_CLIENT_IP'];
    // }

    // if (isset($HTTP_SERVER_VARS['HTTP_PROXY_USER']) && !empty($HTTP_SERVER_VARS['HTTP_PROXY_USER'])) {
      // $ip_addresses[] = $HTTP_SERVER_VARS['HTTP_PROXY_USER'];
    // }

    // $ip_addresses[] = $HTTP_SERVER_VARS['REMOTE_ADDR'];

    // foreach ( $ip_addresses as $ip ) {
      // if (!empty($ip) && tep_validate_ip_address($ip)) {
        // $ip_address = $ip;
        // break;
      // }
    // }

    // return $ip_address;
  // }

  // function tep_get_ip_address() {
    // if (isset($_SERVER)) {
      // if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      // } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        // $ip = $_SERVER['HTTP_CLIENT_IP'];
      // } else {
        // $ip = $_SERVER['REMOTE_ADDR'];
      // }
    // } else {
      // if (getenv('HTTP_X_FORWARDED_FOR')) {
        // $ip = getenv('HTTP_X_FORWARDED_FOR');
      // } elseif (getenv('HTTP_CLIENT_IP')) {
        // $ip = getenv('HTTP_CLIENT_IP');
      // } else {
        // $ip = getenv('REMOTE_ADDR');
      // }
    // }

    // return $ip;
  // }
  
  
  //from feenix
  
  function tep_validate_ip_address($ip_address) {
    return filter_var($ip_address, FILTER_VALIDATE_IP, array('flags' => FILTER_FLAG_IPV4));
  }

  function tep_get_ip_address() {
    global $_SERVER;

    $ip_address = null;
    $ip_addresses = array();

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      foreach ( array_reverse(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])) as $x_ip ) {
        $x_ip = trim($x_ip);

        if (tep_validate_ip_address($x_ip)) {
          $ip_addresses[] = $x_ip;
        }
      }
    }

    if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip_addresses[] = $_SERVER['HTTP_CLIENT_IP'];
    }

    if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && !empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
      $ip_addresses[] = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    }

    if (isset($_SERVER['HTTP_PROXY_USER']) && !empty($_SERVER['HTTP_PROXY_USER'])) {
      $ip_addresses[] = $_SERVER['HTTP_PROXY_USER'];
    }

    $ip_addresses[] = $_SERVER['REMOTE_ADDR'];

    foreach ( $ip_addresses as $ip ) {
      if (!empty($ip) && tep_validate_ip_address($ip)) {
        $ip_address = $ip;
        break;
      }
    }

    return $ip_address;
  }

  function tep_count_customer_orders($id = '', $check_session = true) {
    global $FSESSION;

    if (is_numeric($id) == false) {
      if ($FSESSION->is_registered('customer_id')) {
        $id = $FSESSION->customer_id;
      } else {
        return 0;
      }
    }

    if ($check_session == true) {
      if ( ($FSESSION->is_registered('customer_id') == false) || ($id != $FSESSION->customer_id) ) {
        return 0;
      }
    }

    $orders_check_query = tep_db_query("select count(*) as total from " . TABLE_ORDERS . " where customers_id = '" . (int)$id . "'");
    $orders_check = tep_db_fetch_array($orders_check_query);

    return $orders_check['total'];
  }

  function tep_count_customer_address_book_entries($id = '', $check_session = true) {
    global $FSESSION;

    if (is_numeric($id) == false) {
      if ($FSESSION->is_registered('customer_id')) {
        $id = $FSESSION->customer_id;
      } else {
        return 0;
      }
    }

    if ($check_session == true) {
      if ( ($FSESSION->is_registered('customer_id') == false) || ($id != $FSESSION->customer_id) ) {
        return 0;
      }
    }

    $addresses_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$id . "'");
    $addresses = tep_db_fetch_array($addresses_query);

    return $addresses['total'];
  }

// nl2br() prior PHP 4.2.0 did not convert linefeeds on all OSs (it only converted \n)
// nl2br() prior PHP 4.2.0 did not convert linefeeds on all OSs (it only converted \n)
  function tep_convert_linefeeds($from, $to, $string) {
    return str_replace($from, $to, $string);
  }

  function tep_get_configuration_key_value($lookup) {
    $configuration_query_raw= tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='" . tep_db_input($lookup) . "'");
    $configuration_query= tep_db_fetch_array($configuration_query_raw);
    $lookup_value= $configuration_query['configuration_value'];
    /*if ( !($lookup_value) ) {
      $lookup_value='<font color="FF0000">' . $lookup . '</font>';
    }*/
    return $lookup_value;
  }
////
//CLR 030228 Add function tep_decode_specialchars
// Decode string encoded with htmlspecialchars()
  function tep_decode_specialchars($string){
    $string=str_replace('&gt;', '>', $string);
    $string=str_replace('&lt;', '<', $string);
    $string=str_replace('&#039;', "'", $string);
    $string=str_replace('&quot;', "\"", $string);
    $string=str_replace('&amp;', '&', $string);

    return $string;
  }

// saved from old code
  function tep_output_warning($warning) {
    new errorBox(array(array('text' => tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . ' ' . $warning)));
  }
function tep_check_is_blocked_customer(){
	global $FSESSION;
	$group_sql = "select customers_id from " . TABLE_CUSTOMERS . " c where customers_id=" . (int)$FSESSION->customer_id . " and c.is_blocked='Y'";
	$group_query = tep_db_query($group_sql);
	$is_data = tep_db_fetch_array($group_query);
	if($is_data['customers_id'] != '') return true;
	//if (tep_db_num_rows($group_query)>0) return true;
	else return false;
}
function tep_check_is_suspended_customer(){
	global $FSESSION;
	$group_sql = "select customers_id from " . TABLE_CUSTOMERS . " c where customers_id=" . (int)$FSESSION->customer_id . 
					" and ((suspend_from!='0000-00-00' and suspend_from<=curdate() and (resume_from='0000-00-00' or resume_from>curdate()))
					or (resume_from!='0000-00-00' && resume_from>curdate()))";
	$group_query = tep_db_query($group_sql);
	$is_data = tep_db_fetch_array($group_query);
	if($is_data['customers_id'] != '') return true;
	//if (tep_db_num_rows($group_query)>0) return true;
	else return false;
}

function tep_get_product_price_for_order($products_id,$products_price){
  global $FSESSION;
	$query = tep_db_query("select g.customers_groups_discount from " . TABLE_CUSTOMERS_GROUPS . " g inner join  " . TABLE_CUSTOMERS  . " c on g.customers_groups_id = c.customers_groups_id and c.customers_id = '" . (int)$FSESSION->customer_id . "'");
	$query_result = tep_db_fetch_array($query);
	$customers_groups_discount = $query_result['customers_groups_discount'];
	$query = tep_db_query("select customers_discount from " . TABLE_CUSTOMERS . " where customers_id =  '" . (int)$FSESSION->customer_id . "'");
	$query_result = tep_db_fetch_array($query);
	$customer_discount = $query_result['customers_discount'];
	$customer_discount = $customer_discount + $customers_groups_discount;
	if ($customer_discount >= 0) {
	 $products_price = $products_price + $products_price * abs($customer_discount) / 100;
	} else {
	 $products_price = $products_price - $products_price * abs($customer_discount) / 100;
	}
	if ($special_price = tep_get_products_special_price($products_id)) $products_price = $special_price;
	return $products_price;
}

		//little function to check for ticket amounts and redirect to cart - stops hack attempts to purchase more than ticket limit
		function tep_check_ticket_limit($cust_id,$redirect)
		{
			if (is_numeric(CUSTOMER_TICKET_LIMIT) && (CUSTOMER_TICKET_LIMIT > 0)) { 
			
				$basket_query_raw ="select cb.products_id, p.products_model, cb.customers_basket_quantity from ".TABLE_CUSTOMERS_BASKET." cb, ".TABLE_PRODUCTS." p WHERE cb.customers_id = '".(int)$cust_id."' and p.products_id = cb.products_id";
			 $basket_query = tep_db_query($basket_query_raw);
			 $show_total=array();
			 while($result=tep_db_fetch_array($basket_query))  {
			 
			 if(isset($show_total[$result['products_model']])){
			 $show_total[$result['products_model']] = $show_total[$result['products_model']]+$result['customers_basket_quantity'];
			 }else{
			 
			 $show_total[$result['products_model']] = $result['customers_basket_quantity'];
			 }
			 }
			 //OK now go get the stuff from the orders table
			 
			 $customers_query_raw = "select  c.customers_firstname, sum(op.products_quantity ) as ordersum, op.products_model from " . TABLE_CUSTOMERS . " c, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o where c.customers_id = o.customers_id and o.orders_id = op.orders_id and c.customers_id='" . (int)$cust_id . "' group by op.products_model order by op.products_model";
			//echo $customers_query_raw;
 			 $customers_query = tep_db_query($customers_query_raw);

		while ($customers = tep_db_fetch_array($customers_query))  {
  		
			 
			 if(isset($show_total[$customers['products_model']])){
			 $show_total[$customers['products_model']] = $show_total[$customers['products_model']]+$customers['ordersum'];
			 }else{
			 
			 $show_total[$customers['products_model']] = $customers['ordersum'];
			 }
			 }
			 
			 //we now have an array of shows and tickets bought/in cart
			 $oversubscribed=0;//could use false/true
			 foreach($show_total as $key => $value){
			 		if ($value <= CUSTOMER_TICKET_LIMIT){//it's OK so drop from the array
						unset($show_total[$key]);}
						else{//OTT so increment the flag
						$oversubscribed++;
						}
					 }
			 // check the flag
			 	if($oversubscribed>0){// send to cart page with a session holding the array
					$_SESSION['oversubscribed']=$show_total;
					//stop redirect if on shopping cart page so we can call the function from there
					      if ( $redirect ==1){			
					
					tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
				}
				}
			 }
			 }
			 
  require(DIR_WS_FUNCTIONS . "events_general.php");
 
  
  
  function tep_get_static_path($current_static_category_id = '') {
    global $stcPath_array;

    if (tep_not_null($current_static_category_id)) {
      $ecp_size = sizeof($stcPath_array);
      if ($ecp_size == 0) {
        $stcPath_new = $current_static_category_id;
      } else {
        $stcPath_new = '';
        $last_static_category_query = tep_db_query("select parent_id from " . TABLE_MAINPAGE . " where page_id = '" . (int)$stcPath_array[($sucp_size-1)] . "'");
        $last_static_category = tep_db_fetch_array($last_static_category_query);

        $current_static_category_query = tep_db_query("select parent_id from " . TABLE_MAINPAGE . " where page_id = '" . (int)$current_static_category_id . "'");
        $current_static_category = tep_db_fetch_array($current_static_category_query);

        if ($last_static_category['parent_id'] == $current_static_category['parent_id']) {
          for ($i=0; $i<($sucp_size-1); $i++) {
            $stcPath_new .= '_' . $stcPath_array[$i];
          }
        } else {
          for ($i=0; $i<$sucp_size; $i++) {
            $stcPath_new .= '_' . $stcPath_array[$i];
          }
        }
        $stcPath_new .= '_' . $current_static_category_id;

        if (substr($stcPath_new, 0, 1) == '_') {
          $stcPath_new = substr($stcPath_new, 1);
        }
      }
    } else {
      $stcPath_new = implode('_', $stcPath_array);
    }

    return 'stcPath=' . $stcPath_new;
  }


// Parse and secure the sucPath parameter values
  function tep_parse_static_category_path($stcPath) {
// make sure the category IDs are integers
    $stcPath_array = array_map('tep_string_to_int', explode('_', $stcPath));

// make sure no duplicate category IDs exist which could lock the server in a loop
    $tmp_array = array();
    $n = sizeof($stcPath_array);
    for ($i=0; $i<$n; $i++) {
      if (!in_array($stcPath_array[$i], $tmp_array)) {
        $tmp_array[] = $stcPath_array[$i];
      }
    }

    return $tmp_array;
  }


function tep_change_category_status_off($parent_id) 
{
   		//change the parent cat id
      		 tep_db_query("UPDATE " . TABLE_CATEGORIES . " set categories_status='0' where categories_id='".(int)$parent_id."'");
			
		      // $subcategories_array = array();
   			  // tep_get_subcategories($subcategories_array, $parent_id);
				  // for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) 
				  // {
					 // tep_db_query("UPDATE " . TABLE_CATEGORIES . " set categories_status='0', date_expires='0000-00-00 00:00:00' where categories_id='".(int)$subcategories_array[$i]."'");
					  // change_product_status((int)$subcategories_array[$i],'1','9');
      // }				

      
    }
	//draggable
		function get_percentage($total, $number)
			{
			  if ( $total > 0 ) {
			   return round(($number * 100) / $total, 2);
			  } else {
				return 0;
			  }
			}
?>
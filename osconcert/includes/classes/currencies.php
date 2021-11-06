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
////
// Class to handle currencies
// TABLES: currencies
  class currencies {
    var $currencies;

// class constructor
    function __construct() {
      $this->currencies = array();
      $currencies_query = tep_db_query("select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from " . TABLE_CURRENCIES);
      while ($currencies = tep_db_fetch_array($currencies_query)) {

        if(trim($currencies['symbol_left']) == '£'){
          $currencies['symbol_left'] = '&#163;';
        }
        if(trim($currencies['symbol_right']) == '£'){          
          $currencies['symbol_right'] = '&#163;';
        }



        $this->currencies[$currencies['code']] = array('title' => $currencies['title'],
                                                       'symbol_left' => $currencies['symbol_left'],
                                                       'symbol_right' => $currencies['symbol_right'],
                                                       'decimal_point' => $currencies['decimal_point'],
                                                       'thousands_point' => $currencies['thousands_point'],
                                                       'decimal_places' => $currencies['decimal_places'],
                                                       'value' => $currencies['value']);
      }
    }

// class methods

//new method Feb 2014

// Function added to extend currencies class:
    function get_numeric_value($passed_price,$passed_currency) 
	{
      $passed_price = str_replace($this->currencies[$passed_currency]['symbol_right'], '', $passed_price);
      $passed_price = str_replace($this->currencies[$passed_currency]['symbol_left'], '', $passed_price);
	  $passed_price = str_replace($this->currencies[$passed_currency]['thousands_point'], '', $passed_price);      
	  return $passed_price;	
    }
// :Function added to extend currencies class

    function format($number, $calculate_currency_value = true, $currency_type = '', $currency_value = '') {
      global $FSESSION, $order;
	    
      if (empty($currency_type)) $currency_type = $FSESSION->currency;

      if ($calculate_currency_value == true) 
	  {
          
          if ( (DEFAULT_CURRENCY == 'EUR') && ($currency_type == 'DEM' || $currency_type == 'BEF' || $currency_type == 'LUF' || $currency_type == 'ESP' || $currency_type == 'FRF' || $currency_type == 'IEP' || $currency_type == 'ITL' || $currency_type == 'NLG' || $currency_type == 'ATS' || $currency_type == 'PTE' || $currency_type == 'FIM' || $currency_type == 'GRD') ) {
          $format_string .= ' <small>[' . $this->format($number, true, 'EUR') . ']</small>';
        }
        $rate = (tep_not_null($currency_value)) ? $currency_value : $this->currencies[$currency_type]['value'];
        $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(tep_round($number * $rate, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . ' '.$this->currencies[$currency_type]['symbol_right'];
			// if the selected currency is in the european euro-conversion and the default currency is euro,
			// the currency will displayed in the national currency and euro currency
			// if ( (DEFAULT_CURRENCY == 'EUR') && ($currency_type == 'DEM' || $currency_type == 'BEF' || $currency_type == 'LUF' || $currency_type == 'ESP' || $currency_type == 'FRF' || $currency_type == 'IEP' || $currency_type == 'ITL' || $currency_type == 'NLG' || $currency_type == 'ATS' || $currency_type == 'PTE' || $currency_type == 'FIM' || $currency_type == 'GRD') ) {
			// $format_string .= ' <small>[' . $this->format($number, true, 'EUR') . ']</small>';
			// }
      } else 
	  {
	    $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(tep_round($number, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . ' '.$this->currencies[$currency_type]['symbol_right'];
      }

// Down for Maintenance
      if (DOWN_FOR_MAINTENANCE=='true' && DOWN_FOR_MAINTENANCE_PRICES_OFF=='true') 
	  {
        $format_string= '';
      }
	  
	 //cartzone
	  if(DEFAULT_CURRENCY == 'GBP')
	  {
	  	if(!$post_action) $post_action=$command;
		if(($post_action=='pdf' || $post_action=='excel') && (strpos(",".$this->currencies[$currency_type]['symbol_left'],"Â")>0 || strpos(",".$this->currencies[$currency_type]['symbol_left'],"â‚¬")>0 || strpos(",".$this->currencies[$currency_type]['symbol_left'],"?")>0))
		{
	  		$format_string=str_replace($this->currencies[$currency_type]['symbol_left'],"£", $format_string);					
	  	}	
	  }
      //return $format_string;
	  
	  elseif(DEFAULT_CURRENCY == 'EUR'){
	  	if(!$post_action) $post_action=$command;
		if(($post_action=='pdf' || $post_action=='excel') && (strpos(",".$this->currencies[$currency_type]['symbol_left'],"Â")>0 || strpos(",".$this->currencies[$currency_type]['symbol_left'],"?")>0 || strpos(",".$this->currencies[$currency_type]['symbol_left'],"â‚¬")>0)){
	  		$format_string=str_replace($this->currencies[$currency_type]['symbol_left'],"€", $format_string);					
	  	}	
	  }
      return $format_string;	 
	  	 
    }

    function is_set($code) {
      if (isset($this->currencies[$code]) && tep_not_null($this->currencies[$code])) {
        return true;
      } else {
        return false;
      }
    }

    function get_value($code) {
      return $this->currencies[$code]['value'];
    }

    function get_decimal_places($code) {
      return $this->currencies[$code]['decimal_places'];
    }
    function get_decimal_point($code) {
      return $this->currencies[$code]['decimal_point'];
    }
    function get_thousands_point($code) {
      return $this->currencies[$code]['thousands_point'];
    }
	function get_symbol_left($code) {
      return $this->currencies[$code]['symbol_left'];
    }
	function get_symbol_right($code) {
      return $this->currencies[$code]['symbol_right'];
    }

    function display_price($products_price, $products_tax, $quantity = 1,$discount=false) {
		global $FSESSION;
		$price=0;
		if ($discount){
			$price=$this->display_price_discount($products_price,$products_tax,$quantity);
		} else {
			//check price for guest
			if (defined("ALLOW_GUEST_TO_SEE_PRICES") && !$FSESSION->is_registered('customer_id')){
				if (ALLOW_GUEST_TO_SEE_PRICES=='true'){
					$price=$this->format(tep_add_tax($products_price, $products_tax) * $quantity);	
				} else {
					$price=PRICES_LOGGED_IN_TEXT;
				}
			} else {
		    	$price=$this->format(tep_add_tax($products_price, $products_tax) * $quantity);
			}
		}
		return  $price;
	}
    function display_price_discount($products_price, $products_tax, $quantity = 1) {
      global $FSESSION;
	  if (defined("ALLOW_GUEST_TO_SEE_PRICES")){
	      if (ALLOW_GUEST_TO_SEE_PRICES=='true' && !$FSESSION->is_registered('customer_id')) {
			 $customer_discount = 0;
			 if (defined("GUEST_DISCOUNT")) $customer_discount=(int)GUEST_DISCOUNT;
			 if ($customer_discount >= 0) {
				$products_price = $products_price + $products_price * abs($customer_discount) / 100;
			 } else {
				$products_price = $products_price - $products_price * abs($customer_discount) / 100;
			 }
			 return $this->format(tep_add_tax($products_price, $products_tax) * $quantity);
			 
		  } elseif ($FSESSION->is_registered('customer_id')) {
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
			 return $this->format(tep_add_tax($products_price, $products_tax) * $quantity);
	      } else {
    	     return PRICES_LOGGED_IN_TEXT;
		  }
	  } else {
		  return $this->format(tep_add_tax($products_price, $products_tax) * $quantity);
      }
    }
  }
?>
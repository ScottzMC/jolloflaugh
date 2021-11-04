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
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 
////
// Class to handle currencies
// TABLES: currencies
  class currencies {
    var $currencies;

// class constructor
    function __construct() 
	{
      $this->currencies = array();
      $currencies_query = tep_db_query("select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from " . TABLE_CURRENCIES);
      while ($currencies = tep_db_fetch_array($currencies_query)) 
	  {
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
    function format($number, $calculate_currency_value = true, $currency_type = DEFAULT_CURRENCY, $currency_value = '') 
	{
      global $post_action,$command;
	  if ($calculate_currency_value) 
	  {
        if ( (DEFAULT_CURRENCY == 'EUR') && ($currency_type == 'DEM' || $currency_type == 'BEF' || $currency_type == 'LUF' || $currency_type == 'ESP' || $currency_type == 'FRF' || $currency_type == 'IEP' || $currency_type == 'ITL' || $currency_type == 'NLG' || $currency_type == 'ATS' || $currency_type == 'PTE' || $currency_type == 'FIM' || $currency_type == 'GRD') ) {
          $format_string .= ' <small>[' . $this->format($number, true, 'EUR') . ']</small>';
        }
        $rate = ($currency_value) ? $currency_value : $this->currencies[$currency_type]['value'];
	    $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format($number * $rate, $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . ' '.$this->currencies[$currency_type]['symbol_right'];
		// if the selected currency is in the european euro-conversion and the default currency is euro,
		// the currency will displayed in the national currency and euro currency
      } else 
	  {
        $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format($number, $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . $this->currencies[$currency_type]['symbol_right'];
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
	


    function get_value($code) {
      return $this->currencies[$code]['value'];
    }
	
    function get_decimal_places($code) {
      return $this->currencies[$code]['decimal_places'];
    }

    function display_price($products_price, $products_tax, $quantity = 1,$discount=false) {
		global $FSESSION;
		/*if ($discount)
			$price=$this->display_price_discount($products_price,$products_tax,$quantity);
		else */
			$price=$this->format(tep_add_tax($products_price, $products_tax) * $quantity);
			
      return $price;
	//  return $this->format($products_price * $quantity);
    }
	
    function display_price_discount($products_price, $products_tax, $quantity = 1) {
      global $customer_id,$FSESSION;
		  if ($FSESSION->is_registered('customer_id')) {
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
		  	return $this->format(tep_add_tax($products_price, $products_tax) * $quantity);
      	  }
    }
	
  }
?>

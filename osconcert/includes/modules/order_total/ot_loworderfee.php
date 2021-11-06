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


  class ot_loworderfee {
    var $title, $output;

    function __construct() {
      $this->code = 'ot_loworderfee';
      $this->title = MODULE_ORDER_TOTAL_LOWORDERFEE_TITLE;
      $this->description = MODULE_ORDER_TOTAL_LOWORDERFEE_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER;

      $this->output = array();
    }

    function process() {
      global $order, $currencies;
		
		//if ($order->info['payment_method']=="bank_transfer"){
			//if($_SESSION['customer_country_id']!=999){
		
      if (MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE == 'true') {
       switch (MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION) {
          case 'national':
            if ($order->delivery['country_id'] == STORE_COUNTRY && $order->delivery['country_id'] !=999) $pass = true; break;
          case 'international':
            if ($order->delivery['country_id'] != STORE_COUNTRY && $order->delivery['country_id'] !=999) $pass = true; break;
		  case 'all':
            $pass = true; break;
          case 'noBoxOffice':
            if ($order->delivery['country_id'] !=999) $pass = true; break;
		  case 'BoxOfficeOnly':
            if ($order->delivery['country_id'] ==999) $pass = true; break;
          default:
            $pass = false; break;
        }
	  }

        //if ( ($pass == true) && ( ($order->info['total'] - $order->info['shipping_cost']) < MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER) ) {
		if ( ($pass == true) && (substr(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, -1) == '%') && ( ($order->info['total'] - $order->info['shipping_cost']) < MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER) ) {
          $ot_loworderfee_calc_percent = ((MODULE_ORDER_TOTAL_LOWORDERFEE_FEE / 100) * $order->info['subtotal']);
		
          $tax = tep_get_tax_rate(MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);
          $tax_description = tep_get_tax_description(MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);


		  $order->info['tax'] += tep_calculate_tax($ot_loworderfee_calc_percent, $tax);
          $order->info['tax_groups']["$tax_description"] += tep_calculate_tax($ot_loworderfee_calc_percent, $tax);
          $order->info['total'] += $ot_loworderfee_calc_percent + tep_calculate_tax($ot_loworderfee_calc_percent, $tax);

          $this->output[] = array('title' => $this->title . ':',
                                  'text' => $currencies->format(tep_add_tax($ot_loworderfee_calc_percent, $tax), true, $order->info['currency'], $order->info['currency_value']),
                                  'value' => tep_add_tax($ot_loworderfee_calc_percent, $tax));
								  
		}elseif ( ($pass == true) && ( ($order->info['total'] - $order->info['shipping_cost']) < MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER) ) {

          $tax = tep_get_tax_rate(MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);
          $tax_description = tep_get_tax_description(MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);

          $order->info['tax'] += tep_calculate_tax(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax);
          $order->info['tax_groups']["$tax_description"] += tep_calculate_tax(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax);
          $order->info['total'] += MODULE_ORDER_TOTAL_LOWORDERFEE_FEE + tep_calculate_tax(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax);
		  $this->output[] = array('title' => $this->title . ':',
                                  'text' => $currencies->format(tep_add_tax(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax), true, $order->info['currency'], $order->info['currency_value']),
                                  'net_value' => MODULE_ORDER_TOTAL_LOWORDERFEE_FEE,
                                  'value' => tep_add_tax(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax));
        }
      //}
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }
    function collect_posts() {
	return false;
	}
   	function pre_confirmation_check() {
	return false;
	}
	function update_credit_account() {
	return false;
	}
	function apply_credit() {
	return false;
	}
    function keys() {
      return array('MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS', 'MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER', 'MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE', 'MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER', 'MODULE_ORDER_TOTAL_LOWORDERFEE_FEE', 'MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION', 'MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Booking Fee', 'MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS', 'true', 'Do you want to display the booking fee?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER', '5', 'Sort order of display.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow Booking Fee', 'MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE', 'true', 'Do you want to allow booking fees?', '6', '3', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('Order Fee For Orders Under', 'MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER', '50000', 'Add the booking fee to orders under this amount.', '6', '4', 'currencies->format', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('Order Fee', 'MODULE_ORDER_TOTAL_LOWORDERFEE_FEE', '5', 'Booking fee.', '6', '5', 'currencies->format', now())");
       tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Attach Booking Fee On Orders Made', 'MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION', 'all', 'Attach booking fee for orders sent to the set destination.', '6', '6', 'tep_cfg_select_option(array(\'national\', \'international\', \'all\', \'noBoxOffice\', \'BoxOfficeOnly\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS', '0', 'Use the following tax class on the booking fee.', '6', '7', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
	  
	  
	   // tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Geb&uuml;hr bei niedrigem Bestellwert aktivieren', 'MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS', 'true', 'Wollen Sie die Geb&uuml;hr bei niedrigem Bestellwert aktivieren?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      // tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER', '4', 'Sort order of display.', '6', '2', now())");
      // tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Erlaube Geb&uuml;hr bei niedrigem Bestellwert', 'MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE', 'false', 'Wollen Sie Geb&uuml;hren bei niedrigem Bestellwert erlauben?', '6', '3', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      // tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('Geb&uuml;hr f&uuml;r Buchungen unter', 'MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER', '50', 'F&uuml;ge die Geb&uuml;hr hinzu f&uuml;r Buchungen unter angegebener Summe (ohne W&auml;hrungszeichen!).', '6', '4', 'currencies->format', now())");
      // tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('Geb&uuml;hr', 'MODULE_ORDER_TOTAL_LOWORDERFEE_FEE', '5', 'Festgelegte Geb&uuml;hr bei niedrigem Bestellwert.', '6', '5', 'currencies->format', now())");
      // tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('F&uuml;ge Geb&uuml;hr hinzu bei Buchungen', 'MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION', 'both', 'F&uuml;ge Geb&uuml;hr hinzu bei Buchungen von folgenden Bestimmungsorten.', '6', '6', 'tep_cfg_select_option(array(\'national\', \'international\', \'all\', \'noBoxOffice\', \'BoxOfficeOnly\'), ', now())");
      // tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Steuerklasse', 'MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS', '0', 'Verwende folgende Steuerklasse bei diesem Modul.', '6', '7', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
   
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>

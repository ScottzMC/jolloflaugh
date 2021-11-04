<?php
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
/*
$Id: ups.php,v 1.1.1.1 2003/09/18 19:04:56 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/

/*
revised by Fritz Clapp as UPS Choice 1.8 2003/08/02
  filters service types to those selected in admin and saved in 
  configuration table with key MODULE_SHIPPING_UPS_TYPES;
  suggests STD service as default for Canada;
  modified error message refers to failure to get quote;

*/
  class ups {
    var $code, $title, $descrption, $icon, $enabled, $types;
// class constructor
    function ups() {
      global $order;

      $this->code = 'ups';
      $this->title = MODULE_SHIPPING_UPS_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_UPS_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_UPS_SORT_ORDER;
      $this->icon = DIR_WS_ICONS . 'shipping_ups.gif';
      $this->tax_class = MODULE_SHIPPING_UPS_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_UPS_STATUS == 'True') ? true : false);
      $module_weight=(defined('MODULE_SHIPPING_UPS_HANDLING_WEIGHT'))?MODULE_SHIPPING_UPS_HANDLING_WEIGHT:0;
	  	//check products weight
	  		$total_weight=0;
	  		for($i=0;$i<count($order->products);$i++){
	  			$total_weight+=$order->products[$i]['weight']*$order->products[$i]['qty'];
	  		}
	  		if($total_weight<=$module_weight) $this->enabled=false;
	        
		    tep_check_shipping_module_status($this,MODULE_SHIPPING_UPS_ZONE,trim(MODULE_SHIPPING_UPS_EXCEPT_ZONE),trim(MODULE_SHIPPING_UPS_EXCEPT_COUNTRY));
		$this->types = array('1DM' => 'Next Day Air Early AM',
                           '1DML' => 'Next Day Air Early AM Letter',
                           '1DA' => 'Next Day Air',
                           '1DAL' => 'Next Day Air Letter',
                           '1DAPI' => 'Next Day Air Intra (Puerto Rico)',
                           '1DP' => 'Next Day Air Saver',
                           '1DPL' => 'Next Day Air Saver Letter',
                           '2DM' => '2nd Day Air AM',
                           '2DML' => '2nd Day Air AM Letter',
                           '2DA' => '2nd Day Air',
                           '2DAL' => '2nd Day Air Letter',
                           '3DS' => '3 Day Select',
                           'GND' => 'Ground',
                           'GNDCOM' => 'Ground Commercial',
                           'GNDRES' => 'Ground Residential',
                           'STD' => 'Canada Standard',
                           'XPR' => 'Worldwide Express',
                           'XPRL' =>'worldwide Express Letter',
                           'XDM' => 'Worldwide Express Plus',
                           'XDML' =>'Worldwide Express Plus Letter',
                           'XPD' => 'Worldwide Expedited');
    }

// class methods
    function quote($method = '') {
      global $FREQUEST, $order, $shipping_weight, $shipping_num_boxes;
		
		//get fees details
			$fee=MODULE_SHIPPING_UPS_HANDLING;
		  	$handling_fee=substr($fee,-1,1);
		  	if($handling_fee=='$' || $handling_fee=='%')
		  	$fee=substr($fee,0,-1);
		 //get fees details
      if ( (tep_not_null($method)) && (isset($this->types[$method])) ) {
        $prod = $method;
      } else if ($order->delivery['country']['iso_code_2'] == 'CA') {
	    $prod = 'STD';
      } else {
        $prod = 'GNDRES';
      }
		
      if ($method) $this->_upsAction('3'); // return a single quote

      $this->_upsProduct($prod);

      $country_name = tep_get_countries(SHIPPING_ORIGIN_COUNTRY, true);
      $this->_upsOrigin(SHIPPING_ORIGIN_ZIP, $country_name['countries_iso_code_2']);
      $this->_upsDest($order->delivery['postcode'], $order->delivery['country']['iso_code_2']);
      $this->_upsRate(MODULE_SHIPPING_UPS_PICKUP);
      $this->_upsContainer(MODULE_SHIPPING_UPS_PACKAGE);
      $this->_upsWeight($shipping_weight);
      $this->_upsRescom(MODULE_SHIPPING_UPS_RES);
      $upsQuote = $this->_upsGetQuote();
		if ( (is_array($upsQuote)) && (sizeof($upsQuote) > 0) ) {
		  $this->quotes = array('id' => $this->code,
                              'module' => $this->title . ' (' . $shipping_num_boxes . ' x ' . $shipping_weight . tep_get_unit_name() .')');

      $methods = array();
		if(defined('MODULE_SHIPPING_UPS_TYPES')) $allowed_methods = explode(", ", MODULE_SHIPPING_UPS_TYPES);
		$std_rcd = false;
      $qsize = sizeof($upsQuote);
        for ($i=0; $i<$qsize; $i++) {
          list($type, $cost) = each($upsQuote[$i]);
		  if ($type=='STD') {
			  if ($std_rcd) continue;
			  else $std_rcd = true;
			};
		 if($handling_fee=='%' && defined('MODULE_SHIPPING_UPS_HANDLING'))
	  		 $fee=($cost * MODULE_SHIPPING_UPS_HANDLING)/100;
	  		 
		    if (!in_array($type, $allowed_methods)) continue;
		     $methods[] = array('id' => $type,
                             'title' =>$this->types[$type],
                             'cost' =>($cost + $fee) *  $shipping_num_boxes);
          }
        $this->quotes['methods'] = $methods;

        if ($this->tax_class > 0) {
          $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
        }
      } else {
        $this->quotes = array('module' => $this->title,
                              'error' => 'We are unable to obtain a rate quote for UPS shipping.<br>Please contact the store if no other alternative is shown.');
      }
		if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_UPS_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable UPS Shipping', 'MODULE_SHIPPING_UPS_STATUS', 'True', 'Do you want to offer UPS shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Pickup Method', 'MODULE_SHIPPING_UPS_PICKUP', 'CC', 'How do you give packages to UPS? CC - Customer Counter, RDP - Daily Pickup, OTP - One Time Pickup, LC - Letter Center, OCA - On Call Air', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Packaging?', 'MODULE_SHIPPING_UPS_PACKAGE', 'CP', 'CP - Your Packaging, ULE - UPS Letter, UT - UPS Tube, UBE - UPS Express Box', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Residential Delivery?', 'MODULE_SHIPPING_UPS_RES', 'RES', 'Quote for Residential (RES) or Commercial Delivery (COM)', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Handling Fee', 'MODULE_SHIPPING_UPS_HANDLING', '0', 'Handling fee for this shipping method.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('UPS Tax Class', 'MODULE_SHIPPING_UPS_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('UPS Shipping Zone', 'MODULE_SHIPPING_UPS_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('UPS Except Shipping Country', 'MODULE_SHIPPING_UPS_EXCEPT_COUNTRY', '', 'If countries are selected, disable this shipping method for that countries.', '6', '4', 'tep_cfg_pull_down_zone_except_countries(MODULE_SHIPPING_UPS_ZONE,', 'tep_get_zone_except_country', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('UPS Except Shipping Zone', 'MODULE_SHIPPING_UPS_EXCEPT_ZONE', '', 'If a zone is selected, disable this shipping method for that zone.', '6', '3', 'tep_cfg_pull_down_zone_classes(','tep_get_zone_class_title', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Sort order of display.', 'MODULE_SHIPPING_UPS_SORT_ORDER', '12', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('UPS Shipping Methods', 'MODULE_SHIPPING_UPS_TYPES', 'Nxt AM,Nxt AM Ltr,Nxt,Nxt Ltr,Nxt PR,Nxt Save,Nxt Save Ltr,2nd AM,2nd AM Ltr,2nd,2nd Ltr,3 Day Select,Ground,Canada,World Xp,World Xp Ltr, World Xp Plus,World Xp Plus Ltr,World Expedite', 'Select the UPS services to be offered.', '6', '13', 'tep_cfg_select_multioption(array(\'1DM\',\'1DML\', \'1DA\', \'1DAL\', \'1DAPI\', \'1DP\', \'1DPL\', \'2DM\', \'2DML\', \'2DA\', \'2DAL\', \'3DS\',\'GND\', \'STD\', \'XPR\', \'XPRL\', \'XDM\', \'XDML\', \'XPD\'), ', now() )");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('UPS Weight Unit', 'MODULE_SHIPPING_UPS_WEIGHT_UNIT', 'kg', 'What unit of weight is used?.', '6', '0', 'tep_cfg_pull_down_weight_unit_title', 'tep_cfg_pull_down_weight_unit(', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) values('UPS Handling weight','MODULE_SHIPPING_UPS_HANDLING_WEIGHT',2,'What weight will the module be used for.',6,0,'0000-00-00',now(),'','tep_cfg_user_defined_text_field(\"before\",\"<=\",')");
	}

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_UPS_STATUS', 'MODULE_SHIPPING_UPS_PICKUP', 'MODULE_SHIPPING_UPS_PACKAGE', 'MODULE_SHIPPING_UPS_RES', 'MODULE_SHIPPING_UPS_HANDLING', 'MODULE_SHIPPING_UPS_TAX_CLASS', 'MODULE_SHIPPING_UPS_ZONE', 'MODULE_SHIPPING_UPS_EXCEPT_ZONE', 'MODULE_SHIPPING_UPS_EXCEPT_COUNTRY', 'MODULE_SHIPPING_UPS_SORT_ORDER', 'MODULE_SHIPPING_UPS_TYPES','MODULE_SHIPPING_UPS_WEIGHT_UNIT','MODULE_SHIPPING_UPS_HANDLING_WEIGHT');
    }

    function _upsProduct($prod){
      $this->_upsProductCode = $prod;
    }

    function _upsOrigin($postal, $country){
      $this->_upsOriginPostalCode = $postal;
      $this->_upsOriginCountryCode = $country;
    }

    function _upsDest($postal, $country){
      $postal = str_replace(' ', '', $postal);

      if ($country == 'US') {
        $this->_upsDestPostalCode = substr($postal, 0, 5);
      } else {
        $this->_upsDestPostalCode = $postal;
      }

      $this->_upsDestCountryCode = $country;
    }

    function _upsRate($foo) {
      switch ($foo) {
        case 'RDP':
          $this->_upsRateCode = 'Regular+Daily+Pickup';
          break;
        case 'OCA':
          $this->_upsRateCode = 'On+Call+Air';
          break;
        case 'OTP':
          $this->_upsRateCode = 'One+Time+Pickup';
          break;
        case 'LC':
          $this->_upsRateCode = 'Letter+Center';
          break;
        case 'CC':
          $this->_upsRateCode = 'Customer+Counter';
          break;
      }
    }

    function _upsContainer($foo) {
      switch ($foo) {
        case 'CP': // Customer Packaging
          $this->_upsContainerCode = '00';
          break;
        case 'ULE': // UPS Letter Envelope
          $this->_upsContainerCode = '01';
          break;
        case 'UT': // UPS Tube
          $this->_upsContainerCode = '03';
          break;
        case 'UEB': // UPS Express Box
          $this->_upsContainerCode = '21';
          break;
        case 'UW25': // UPS Worldwide 25 kilo
          $this->_upsContainerCode = '24';
          break;
        case 'UW10': // UPS Worldwide 10 kilo
          $this->_upsContainerCode = '25';
          break;
      }
    }

    function _upsWeight($foo) {
		if (SHOP_WEIGHT_UNIT=="OZ"){
	      $this->_upsPackageWeight = number_format($foo*0.02835,2);
		} else {
			$this->_upsPackageWeight = $foo;
		}
    }

    function _upsRescom($foo) {
      switch ($foo) {
        case 'RES': // Residential Address
          $this->_upsResComCode = '1';
          break;
        case 'COM': // Commercial Address
          $this->_upsResComCode = '2';
          break;
      }
    }

    function _upsAction($action) {
      /* 3 - Single Quote
         4 - All Available Quotes */

      $this->_upsActionCode = $action;
    }

    function _upsGetQuote() {
      if (!isset($this->_upsActionCode)) $this->_upsActionCode = '4';

      $request = join('&', array('accept_UPS_license_agreement=yes',
                                 '10_action=' . $this->_upsActionCode,
                                 '13_product=' . $this->_upsProductCode,
                                 '14_origCountry=' . $this->_upsOriginCountryCode,
                                 '15_origPostal=' . $this->_upsOriginPostalCode,
                                 '19_destPostal=' . $this->_upsDestPostalCode,
                                 '22_destCountry=' . $this->_upsDestCountryCode,
                                 '23_weight=' . $this->_upsPackageWeight,
                                 '47_rate_chart=' . $this->_upsRateCode,
                                 '48_container=' . $this->_upsContainerCode,
                                 '49_residential=' . $this->_upsResComCode));

      $http = new httpClient();
      if ($http->Connect('www.ups.com', 80)) {
        $http->addHeader('Host', 'www.ups.com');
        $http->addHeader('User-Agent', 'osCommerce');
        $http->addHeader('Connection', 'Close');

        if ($http->Get('/using/services/rave/qcostcgi.cgi?' . $request)) $body = $http->getBody();

        $http->Disconnect();
      } else {
        return 'error';
      }

      $body_array = explode("\n", $body);

      $returnval = array();
      $errorret = 'error'; // only return error if NO rates returned

      $n = sizeof($body_array);
      for ($i=0; $i<$n; $i++) {
        $result = explode('%', $body_array[$i]);
        $errcode = substr($result[0], -1);
        switch ($errcode) {
          case 3:
            if (is_array($returnval)) $returnval[] = array($result[1] => $result[8]);
            break;
          case 4:
            if (is_array($returnval)) $returnval[] = array($result[1] => $result[8]);
            break;
          case 5:
            $errorret = $result[1];
            break;
          case 6:
            if (is_array($returnval)) $returnval[] = array($result[3] => $result[10]);
            break;
        }
      }
      if (empty($returnval)) $returnval = $errorret;
      
      return $returnval;
    }
  }
?>

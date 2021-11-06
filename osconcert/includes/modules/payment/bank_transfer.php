<?php
/*
  

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();


  class bank_transfer {
    var $code, $title, $description, $enabled;

// class constructor
	function __construct() {
		global $order;
		
		$this->code = 'bank_transfer';
		$name = "Bank Transfer Payment";
		$image = "";
		$path = "";
		if(MODULE_PAYMENT_BANK_TRANSFER_DISPLAY_NAME != "MODULE_PAYMENT_BANK_TRANSFER_DISPLAY_NAME")
			$name = MODULE_PAYMENT_BANK_TRANSFER_DISPLAY_NAME;
		if(MODULE_PAYMENT_BANK_TRANSFER_IMAGE != "MODULE_PAYMENT_BANK_TRANSFER_IMAGE")
			$image = MODULE_PAYMENT_BANK_TRANSFER_IMAGE;
		if(DIR_WS_ADMIN != "DIR_WS_ADMIN" && DIR_WS_ADMIN != "")$path = "../";
		if($image != "" && file_exists($path . DIR_WS_IMAGES . $image))
		{
			$image = '<img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_WS_IMAGES . $image . '" height="35">';
		}else
		{
			$image_array = array('.png','.gif','.jpg','.jpeg');
			$image_check = true;
			for($i=0;$i<sizeof($image_array);$i++){
				if($image_check && $image != "" && file_exists($path . DIR_WS_IMAGES . $image . $image_array[$i])){
					$image = '<img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_WS_IMAGES . $image . $image_array[$i] . '" width="400" height="35" class="img-fluid">';
					$image_check = false;
				}
			}
			if($image_check)$image = $path;
		}
		define('MODULE_PAYMENT_BANK_TRANSFER_TEXT_TITLE', $name . '&nbsp;&nbsp;' . $image);		
		define('MODULE_PAYMENT_BANK_TRANSFER_TEXT_TEXT_TITLE', $name);
		$this->title = MODULE_PAYMENT_BANK_TRANSFER_TEXT_TITLE;
		$this->text_title = MODULE_PAYMENT_BANK_TRANSFER_TEXT_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_BANK_TRANSFER_TEXT_DESCRIPTION;
		$this->email_footer = MODULE_PAYMENT_BANK_TRANSFER_TEXT_EMAIL_FOOTER;
		$this->sort_order = MODULE_PAYMENT_BANK_TRANSFER_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_BANK_TRANSFER_STATUS == 'True') ? true : false);
		$this->barred=false;
		
		if ((int)MODULE_PAYMENT_BANK_TRANSFER_ORDER_STATUS_ID > 0) {
			$this->order_status = MODULE_PAYMENT_BANK_TRANSFER_ORDER_STATUS_ID;
		}
		
		if (is_object($order)) $this->update_status();
	}

// class methods
    function update_status() {
      global $order;
	  
	  tep_check_module_status($this,MODULE_PAYMENT_BANK_TRANSFER_ZONE,trim(MODULE_PAYMENT_BANK_TRANSFER_EXCEPT_ZONE),trim(MODULE_PAYMENT_BANK_TRANSFER_EXCEPT_COUNTRY));
	  $this->barred=tep_check_payment_barred(trim(MODULE_PAYMENT_BANK_TRANSFER_EXCEPT_COUNTRY));
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
	 global $order, $id, $FSESSION;
	 $order_query=tep_db_query("select max(orders_id) as orders_id from " . TABLE_ORDERS );
	 $order_result=tep_db_fetch_array($order_query);
	 $orders_id=$order_result['orders_id'] + 1;
	 $reference='';
	 
	 // '<b>' . HEADING_REFERENCE_ID . '</b>' .  $FSESSION->referenceID ;
	  // $selection_text='<table width="100%" >
						// <tr>
						// <td class="smallText" width="50%">' . MODULE_PAYMENT_BANK_TRANSFER_BANK_DETAILS . '<br><small>' .  $reference . '</small>' .'</td>
						// <td  class="smallText" width="50%" valign="bottom"><small><b>' .TITLE_PLEASE_NOTE .'</b><br>'. REFERENCE_DESCRIPTION  .'</small>
						// </td> 
						// </tr></table>';
						
	$selection_text='<div style="padding: 5px;border: red dotted 2px;"><strong>'.HEADING_REFERENCE.'</strong></div>';
	
    ############################################################
    //define( 'MODULE_PAYMENT_BANK_TRANSFER_CUTOFF_DAYS', '1' );

	
    require_once(DIR_WS_CLASSES . 'order.php');
	$min_order = new order;

    for ( $i = 0, $n = count( $min_order->products ); $i < $n; $i++ ) 
	{
        ######################################################
        $id = tep_db_input( $min_order->products[ $i ][ 'id' ] );
        require_once( 'includes/functions/categories_lookup.php' );
        #####################################################

        list( $heading_name, $heading_venue, $heading_date, $categories_date, $heading_time ) = categories_lookup();

		if(!strtotime($categories_date))
		{
		//echo "";
		}else
		{
			
			$numberOfDays = MODULE_PAYMENT_BANK_TRANSFER_CUTOFF_DAYS;
			$todaysDate = date( EVENTS_DATE_FORMAT, getServerDate( false ) );
	
				$date = new DateTime( $heading_date );
				try {
					date_add( $date, date_interval_create_from_date_string( "-" . $numberOfDays . " days" ) );
					$cutoffDate = $date->format( EVENTS_DATE_FORMAT );
				} catch ( Exception $e ) {
					//do nothing. This is here in case there is an error in the value for MODULE_PAYMENT_BANK_TRANSFER_CUTOFF_DAYS
				}

			if ( $todaysDate > $cutoffDate ) {
				return false;
				exit();
			}

		}
	}
    ############################################################
    # cutoff date end
    ############################################################
	
	
      $selection = array('id' => $this->code,
                         'module' => $this->title,
						 'barred'=>$this->barred,
                         'fields' => array(array('title' => '',
                         'field' => $selection_text)
						));
	  return $selection;
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return array('title' => MODULE_PAYMENT_BANK_TRANSFER_TEXT_EMAIL_FOOTER );
    }

    function process_button() {
      return false;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_BANK_TRANSFER_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Bank Deposit Module', 'MODULE_PAYMENT_BANK_TRANSFER_STATUS', 'True', 'Do you want to accept Bank Deposit payments?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_BANK_TRANSFER_ZONE', '2', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Exclude these Countries', 'MODULE_PAYMENT_BANK_TRANSFER_EXCEPT_COUNTRY', '', 'If countries are selected, disable this payment method for that countries.', '6', '3', 'tep_cfg_pull_down_zone_except_countries(MODULE_PAYMENT_BANK_TRANSFER_ZONE,', 'tep_get_zone_except_country', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Exclude these Zones', 'MODULE_PAYMENT_BANK_TRANSFER_EXCEPT_ZONE', '', 'If a zone is selected, disable this payment method for that zone.', '6', '4', 'tep_cfg_pull_down_zone_classes(','tep_get_zone_class_title', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_BANK_TRANSFER_SORT_ORDER', '1', 'Sort order of display. Lowest is displayed first.', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('BIC Number', 'MODULE_PAYMENT_BANK_TRANSFER_BIC', 'ABCDEFGH', 'BIC Number', '6', '6', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bank Account No.', 'MODULE_PAYMENT_BANK_TRANSFER_ACCNUM', '12345678', 'Bank Account No.', '6', '7', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('IBAN Code.', 'MODULE_PAYMENT_BANK_TRANSFER_IBAN', '1234 5678 1234 5678', 'IBAN Code.', '6', '8', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bank Account Holder', 'MODULE_PAYMENT_BANK_TRANSFER_ACCNAM', 'Joe Bloggs', 'Bank account name', '6', '9', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Bank Name', 'MODULE_PAYMENT_BANK_TRANSFER_BANKNAM', 'The Bank', 'Bank Name', '6', '10', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_BANK_TRANSFER_ORDER_STATUS_ID', '1', 'Set the status of orders made with this payment module to this value', '6', '11', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Display Name', 'MODULE_PAYMENT_BANK_TRANSFER_DISPLAY_NAME', 'Bank Transfer Payment', 'Set the Display name to payment module', '6', '12', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Image', 'MODULE_PAYMENT_BANK_TRANSFER_IMAGE', 'bank_transfer', 'Set the Image of payment module', '6', '13', 'tep_cfg_file_field(', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cut off day', 'MODULE_PAYMENT_BANK_TRANSFER_CUTOFF_DAYS', '0', 'Amount of cut off days before day of concert', '6', '16', now());");
   }


      function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_BANK_TRANSFER_STATUS', 'MODULE_PAYMENT_BANK_TRANSFER_ZONE', 'MODULE_PAYMENT_BANK_TRANSFER_EXCEPT_ZONE', 'MODULE_PAYMENT_BANK_TRANSFER_EXCEPT_COUNTRY', 'MODULE_PAYMENT_BANK_TRANSFER_SORT_ORDER', 'MODULE_PAYMENT_BANK_TRANSFER_BIC', 'MODULE_PAYMENT_BANK_TRANSFER_ACCNUM', 'MODULE_PAYMENT_BANK_TRANSFER_ACCNAM', 'MODULE_PAYMENT_BANK_TRANSFER_IBAN', 'MODULE_PAYMENT_BANK_TRANSFER_BANKNAM', 'MODULE_PAYMENT_BANK_TRANSFER_ORDER_STATUS_ID','MODULE_PAYMENT_BANK_TRANSFER_DISPLAY_NAME','MODULE_PAYMENT_BANK_TRANSFER_IMAGE','MODULE_PAYMENT_BANK_TRANSFER_CUTOFF_DAYS');

    }
	
	function get_comments() {
		global $FSESSION;
		  $comments.= MODULE_BANK_TRANSFER_INFO . TEXT_REFERENCEID . ' : ' . $FSESSION->referenceID ;
		  if($comments!="")
		  	return $comments;
		  else
		  	return;	
	}
  }
?>
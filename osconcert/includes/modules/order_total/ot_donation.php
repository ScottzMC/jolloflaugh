<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2019 osConcert

  Released under the GNU General Public License

*/

// Check to ensure this file is included in osConcert
defined('_FEXEC') or die();


  class ot_donation {
    var $title, $output;

    function __construct() {
		$this->code = 'ot_donation';
		$this->title = MODULE_ORDER_TOTAL_DONATION_TITLE;
		$this->description = MODULE_ORDER_TOTAL_DONATION_DESCRIPTION;
		$this->enabled = ((MODULE_ORDER_TOTAL_DONATION_STATUS == 'true') ? true : false);
		$this->sort_order = MODULE_ORDER_TOTAL_DONATION_SORT_ORDER;
		$this->credit_class = true;
		$this->include_tax = MODULE_ORDER_TOTAL_DONATION_INC_TAX;
		$this->calculate_tax = MODULE_ORDER_TOTAL_DONATION_CALC_TAX;//recalculate tax
		$this->output = array();
		}

    function process() {
      global $FREQUEST, $order, $currencies, $ot_subtotal;
	      global $FSESSION;
	      {
          $od_amount = $_SESSION['donation'];
		  $tod_amount = 0;
     //if ($this->calculate_tax == 'true') {$tod_amount = $this->calculate_tax_effect($od_amount);}
     //adding tax to a donation is incorrect?
      if ($od_amount > 0) {

        $this->deduction = $od_amount+$tod_amount;
        $this->output[] = array('title' => $this->title.':',
                                'text' =>  $currencies->format($od_amount),
                                'value' => $od_amount);
        $order->info['total'] += $this->deduction;
        $order->info['tax'] += $tod_amount;
        if ($this->sort_order < $ot_subtotal->sort_order) {$order->info['subtotal'] += $this->deduction;}
      }
	  }
    }
/// display on the payment seletion page
//  not used - see new method display_before_comments
	  function credit_selection() {

        return false;
    }

##### This is run between the payment and confirmation pages
##### Here we check/sanitise the input and pass it into a session
	function pre_confirmation_check() {
		 
    global $FSESSION, $FREQUEST;
		$FSESSION->remove('donation');
		
     	//redo the session
			if($FREQUEST->postvalue('donation') && is_numeric($FREQUEST->postvalue('donation')) && $FREQUEST->postvalue('donation') > 0  && 	MODULE_ORDER_TOTAL_DONATION_STATUS == 'true'){
				$FSESSION->set('donation', $FREQUEST->postvalue('donation'));
			
	   }else{
		   $FSESSION->set('donation', 0);
	   }
	 return true;
	}

    function selection_test() {
    return true;
  }
  
  function display_before_comments(){
	  
	  global $currencies;
	  ######
	  # $output creates a table
	  # if not seen then check the 
	  # orders_total class file holds the 
	  # new method
	  ######
	  
	  ### currency attributes
	  ### may not all be used
	  ### rely on the new methods

		$decimal_places = $currencies->get_decimal_places($_SESSION['currency']);
		$decimal_point  = $currencies->get_decimal_point($_SESSION['currency']);
		$thousands_point= $currencies->get_thousands_point($_SESSION['currency']);
		$symbol_left =  $currencies->get_symbol_left($_SESSION['currency']);
		$symbol_right =  $currencies->get_symbol_right($_SESSION['currency']);
		$min_value = max(0,MODULE_ORDER_TOTAL_DONATION_MIN_AMOUNT);    //a negative amount then round to zero
		$start_value = (MODULE_ORDER_TOTAL_DONATION_START_AMOUNT);
		if ($min_value  > 0){
			$value_to_use = $min_value;
		}else{
			$value_to_use = "";
		}
		


		### transfer admin settings to payment field
		### 1 - allow decimal places?
		### 2 - check for non decimal point delimiter
		### 3 - required or not

		$step = 1;
		$decimal_point_explain = $explain = $required = "";

		if (MODULE_ORDER_TOTAL_DONATION_ALLOW_DECIMALS == 'true'){
			//set the step
			switch ($decimal_places){
				case 4:
					$step = 0.0001;
					break;
				case 3:
					$step = 0.001;
					break;
				case 2:
					$step = 0.01;
					break;
				case 1:
					$step = 0.1;
					break;
				default:
					$step = 1;	
			}
			// check the decimal point
			if ($decimal_point != "."){
				$decimal_point_explain = MODULE_ORDER_TOTAL_DONATION_EXPLAIN_DECIMAL; 
			}
			//placeholder
			//$start_value = number_format($start_value,$decimal_places,'.', '');
		}else{
			//$start_value = number_format($start_value,0);
			$explain = MODULE_ORDER_TOTAL_DONATION_EXPLAIN;
		}
		 if (MODULE_ORDER_TOTAL_DONATION_REQUIRED == 'true'){
			 $required = ' required ';
		 }

	  
	  $output = '<div style="display:true">';

				$output .= '<h4>' . MODULE_ORDER_TOTAL_DONATION_TITLE .'</h4>';

                $output .= '</div>';
				
				$output .= '<div id="show_discount">

								<table width="100%" class="infoBox">
								 <tr class="infoBoxContents">
									<td>
									<table width="100%">
										<tr>
											<td>';
				## form field
				  
				$output .='<div>
						<label>'.MODULE_ORDER_TOTAL_DONATION_AMOUNT_DESCRIPTION. $symbol_left .'
							<input type="number" 
							placeholder="'. $start_value .'" 
							'. $required .' 
							name="donation" 
							value="'.$value_to_use.'" 
							min = "'. $min_value .'"
							step ="' .$step . '"
							title="Currency" 
							onblur="this.parentNode.parentNode.style.backgroundColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?"inherit":"red""> '. $symbol_right .'</label> 
							 '.$decimal_point_explain . ' ' . $explain .'
						</div>';
				## end form field
											
											
				$output.= '			</td>
										</tr>
									</table>
									</td>
								</tr>
									<tr class="infoBoxContents">
										<td>
										<table width="100%">
											<tr>
												<td>'.MODULE_ORDER_TOTAL_DONATION_PAYMENT_DESCRIPTION.'</td>
											</tr>
										</table>
										</td>
									</tr>
								</table>
							</div>
	  
	  ';
	  ######
	return $output;
}
	
	
	
	function collect_posts() {
		return false;
	}
	function update_credit_account($i) {
		return false;
	}
	function collect_posts_forajax() {
		return false;
	}
	 function apply_credit() {
	      global $FSESSION;
	      if ($FSESSION->is_registered("donation")){
		      $result = $_SESSION['donation'];
			  $FSESSION->remove('donation');
              return $result;
			   }else{
			  return false;
			  }
    }




    function check() {
      if (!isset($this->check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_DONATION_STATUS'");
        $this->check = mysqli_num_rows($check_query);
      }

      return $this->check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_DONATION_STATUS', 'MODULE_ORDER_TOTAL_DONATION_SORT_ORDER',   'MODULE_ORDER_TOTAL_THOUSAND_PLACES','MODULE_ORDER_TOTAL_DECIMAL_PLACES','MODULE_ORDER_TOTAL_DONATION_START_AMOUNT', 'MODULE_ORDER_TOTAL_DONATION_ALLOW_DECIMALS', 'MODULE_ORDER_TOTAL_DONATION_REQUIRED','MODULE_ORDER_TOTAL_DONATION_MIN_AMOUNT', );
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display donation field?', 'MODULE_ORDER_TOTAL_DONATION_STATUS', 'true', 'Do you want to display a donation box on the payment page?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	  
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_DONATION_SORT_ORDER', '1', 'Sort order of display.', '6', '2', now())");

   //  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Tax', 'MODULE_ORDER_TOTAL_DONATION_INC_TAX', 'false', 'Include Tax in calculation.', '6', '7','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	 
	// tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Taxes to include', 'MODULE_ORDER_TOTAL_DONATION_TAX_GROUP', 'false', 'Tax groups', '6', '8','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");

	 
	//  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Display - over ride decimal places?', 'MODULE_ORDER_TOTAL_DECIMAL_PLACES', 'false', 'Show no decimal places?', '6', '10','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	  
	//  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Display - over ride thousands?', 'MODULE_ORDER_TOTAL_THOUSAND_PLACES', 'false', 'Show no thousands separator', '6', '11','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	  
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Placeholder.', 'MODULE_ORDER_TOTAL_DONATION_START_AMOUNT', 'Text or numbers', 'Placeholder only', '6', '12', now())");  
	  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Allow decimals?', 'MODULE_ORDER_TOTAL_DONATION_ALLOW_DECIMALS', 'false', 'Permit customers to enter pennies, cents etc.', '6', '11','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Mandatory?', 'MODULE_ORDER_TOTAL_DONATION_REQUIRED', 'false', 'Enforce the donation - make it obligatory?', '6', '13','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	  
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Minimum amount', 'MODULE_ORDER_TOTAL_DONATION_MIN_AMOUNT', '0', 'Do you want to enforce a minimum payment', '6', '14', now())"); 
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
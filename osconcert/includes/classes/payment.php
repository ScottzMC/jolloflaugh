<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

  Copyright (c) 2012 osCommerce

 

Freeway eCommerce
http://www.openfreeway.org
Copyright (c) 2007 ZacWare

Released under the GNU General Public License
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();


  class payment {
    var $modules, $selected_module;

// class constructor
    function __construct($module = '') 
	{

      global $FSESSION,  $PHP_SELF, $cart;


      if (defined('MODULE_PAYMENT_INSTALLED') && tep_not_null(MODULE_PAYMENT_INSTALLED)) 
	  {
        $this->modules = explode(';', MODULE_PAYMENT_INSTALLED);
        
        $include_modules = array();

        if ( (tep_not_null($module)) && (in_array($module . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.')+1)), $this->modules)) ) 
		{
          $this->selected_module = $module;
          $include_modules[] = array('class' => $module, 'file' => $module . '.php');
        } else {
          reset($this->modules);

// Show either normal payment modules or free payment module when Free Shipping Module is On
          // Free Payment Only
          if (tep_get_configuration_key_value('MODULE_PAYMENT_FREECHARGER_STATUS') and ($cart->show_total()==0 and $cart->show_weight==0)) 
		  {
            $this->selected_module = $module;
            $include_modules[] = array('class'=> 'freecharger', 'file' => 'freecharger.php');
          } else 
		  {
            // All Other Payment Modules
			
            //while (list(, $value) = each($this->modules)) 
	    //{
	    foreach($this->modules as $value) {
              $class = substr($value, 0, strrpos($value, '.'));
			  // Don't show Free Payment Module
              if ($class !='freecharger') {
                $include_modules[] = array('class' => $class, 'file' => $value);
              }
            }
          }
        }

        for ($i=0, $n=sizeof($include_modules); $i<$n; $i++) 
		{
          include(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/payment/' . $include_modules[$i]['file']);
          include(DIR_WS_MODULES . 'payment/' . $include_modules[$i]['file']);
          $GLOBALS[$include_modules[$i]['class']] = new $include_modules[$i]['class'];
        }

// if there is only one payment method, select it as default because in
// checkout_confirmation.php the $payment variable is being assigned the
// $HTTP_POST_VARS['payment'] value which will be empty (no radio button selection possible)
        if ( (tep_count_payment_modules() == 1) && (!isset($GLOBALS[$payment]) || (isset($GLOBALS[$payment]) && !is_object($GLOBALS[$payment]))) ) 
		{
          $FSESSION->set('payment',$include_modules[0]['class']);
        }

        if ( (tep_not_null($module)) && (in_array($module, $this->modules)) && (isset($GLOBALS[$module]->form_action_url)) ) {
          $this->form_action_url = $GLOBALS[$module]->form_action_url;
        }
      }
    }

// class methods
/* The following method is needed in the checkout_confirmation.php page
   due to a chicken and egg problem with the payment class and order class.
   The payment modules needs the order destination data for the dynamic status
   feature, and the order class needs the payment module title.
   The following method is a work-around to implementing the method in all
   payment modules available which would break the modules in the contributions
   section. This should be looked into again post 2.2.
*/   
    function update_status() 
	{
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module])) 
		{
          if (function_exists('method_exists')) {
            if (method_exists($GLOBALS[$this->selected_module], 'update_status')) {
              $GLOBALS[$this->selected_module]->update_status();
            }
          } else { // PHP3 compatibility
            @call_user_method('update_status', $GLOBALS[$this->selected_module]);
          }
        }
      }
    }

    function javascript_validation() 
	{
      $js = '';
      if (is_array($this->modules)) {
        $js = '<script><!-- ' . "\n" .
              'function check_form() {' . "\n" .
              '  var error = 0;' . "\n" .
              '  var error_message = "' . JS_ERROR . '";' . "\n" .
              '  var payment_value = null;' . "\n" .
              '  if (document.checkout_payment.payment.length) {' . "\n" .
              '    for (var i=0; i<document.checkout_payment.payment.length; i++) {' . "\n" .
              '      if (document.checkout_payment.payment[i].checked) {' . "\n" .
              '        payment_value = document.checkout_payment.payment[i].value;' . "\n" .
              '      }' . "\n" .
              '    }' . "\n" .
              '  } else if (document.checkout_payment.payment.checked) {' . "\n" .
              '    payment_value = document.checkout_payment.payment.value;' . "\n" .
              '  } else if (document.checkout_payment.payment.value) {' . "\n" .
              '    payment_value = document.checkout_payment.payment.value;' . "\n" .
              '  }' . "\n\n";

        reset($this->modules);
       // while (list(, $value) = each($this->modules)) 
	foreach($this->modules as $value) 
	{
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $js .= $GLOBALS[$class]->javascript_validation();
          }
        }

        $js .= "\n" . '  if (payment_value == null && submitter != 1) {' . "\n" . // ICW CREDIT CLASS Gift Voucher System
               '    error_message = error_message + "' . JS_ERROR_NO_PAYMENT_MODULE_SELECTED . '";' . "\n" .
               '    error = 1;' . "\n" .
               '  }' . "\n\n" .
// Gift Voucher System Line below amended
               '  if (error == 1 && submitter != 1) {' . "\n" .
               '    alert(error_message);' . "\n" .
               '    return false;' . "\n" .
               '  } else {' . "\n" .
               '    return true;' . "\n" .
               '  }' . "\n" .
               '}' . "\n" .
               '//--></script>' . "\n";
      }

      return $js;
    }

    function checkout_initialization_method() {
      $initialize_array = array();

      if (is_array($this->modules)) {
        foreach($this->modules as $value) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled && method_exists($GLOBALS[$class], 'checkout_initialization_method')) {
            $initialize_array[] = $GLOBALS[$class]->checkout_initialization_method();
          }
        }
      }

      return $initialize_array;
    }

    function selection() {
      $selection_array = array();
		 if (is_array($this->modules)) 
		 { 
        reset($this->modules);
        //while (list(, $value) = each($this->modules)) { 
		
	foreach($this->modules as $value) {
          $class = substr($value, 0, strrpos($value, '.'));
		  if ($GLOBALS[$class]->enabled) 
		  {
            $selection = $GLOBALS[$class]->selection();
            if (is_array($selection)) $selection_array[] = $selection;
          }
        }
      }
		 return $selection_array;
    }
//ADDED FOR CCGV
 // check credit covers was setup to test whether credit covers is set in other parts of the code
function check_credit_covers() {
  global $credit_covers;
  return $credit_covers;
}
// END ADDED FOR CCGV
    function pre_confirmation_check() {
      global $credit_covers, $payment_modules; //ICW CREDIT CLASS Gift Voucher System
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          if ($credit_covers) { // Gift Voucher System
            $GLOBALS[$this->selected_module]->enabled = false; //ICW CREDIT CLASS Gift Voucher System
            $GLOBALS[$this->selected_module] = NULL; //ICW CREDIT CLASS Gift Voucher System
            $payment_modules = ''; //ICW CREDIT CLASS Gift Voucher System
          } else { //ICW CREDIT CLASS Gift Voucher System
            $GLOBALS[$this->selected_module]->pre_confirmation_check();
          }
        }
      }
    } //ICW CREDIT CLASS Gift Voucher System

    function confirmation() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->confirmation();
        }
      }
    }

    function process_button() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->process_button();
        }
      }
    }
	//
  function before_email() {
    if (is_array($this->modules)) {
      reset($this->modules);
      //while (list(, $value) = each($this->modules)) {
      foreach($this->modules as $value) {
        $class = substr($value, 0, strrpos($value, '.'));
        if ($GLOBALS[$class]->enabled && method_exists($GLOBALS[$class], 'before_email')) {
          $module_result = $GLOBALS[$class]->before_email();
          if ($module_result === true) {
		     return $GLOBALS[$this->selected_module]->before_email();
			}else{
			return false;}
        }
      }
    }
  }

    function before_process() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->before_process();
        }
      }
    }

    function after_process() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->after_process();
        }
      }
    }
	function get_comments(){
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
			if (method_exists($GLOBALS[$this->selected_module],"get_comments")){
	          return $GLOBALS[$this->selected_module]->get_comments();
			} else {
				return "";
			}
        }
      }
	}
    function get_error() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->get_error();
        }
      }
    }
  }
?>
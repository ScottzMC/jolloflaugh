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

  class order_total {
    var $modules;

// class constructor
    function __construct() 
	{
      global $FSESSION;
      	
      if (defined('MODULE_ORDER_TOTAL_INSTALLED') && tep_not_null(MODULE_ORDER_TOTAL_INSTALLED)) {
        $this->modules = explode(';', MODULE_ORDER_TOTAL_INSTALLED);
        reset($this->modules);        
       //while (list(, $value) = each($this->modules)) {
	   //FOREACH
	   foreach($this->modules as $value) {
		//if($value!='ot_gv.php')
		{ // as per E-204
		//echo DIR_WS_LANGUAGES . $language . '/modules/order_total/' . $value;		
          include(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/order_total/' . $value);
		//  echo DIR_WS_MODULES . 'order_total/' . $value;
          include(DIR_WS_MODULES . 'order_total/' . $value);
		  $class = substr($value, 0, strrpos($value, '.'));
          $GLOBALS[$class] = new $class;
		 } 
        }
      }
    }
  function display_before_comments() {
	$header_string .= '   <table width="100%" cellpadding="2">' . "\n";
	$header_string .= '<tr>' . "\n";
	$header_string .= '   <td><table  width="100%" cellpadding="2" class="infoBox">' . "\n";
	$header_string .= '     <tr class="infoBoxContents"><td><table width="100%" cellpadding="2">' ."\n";
	$header_string .= '       <tr>' . "\n";
	$header_string .= '           <td colspan="2"><table width="100%" cellpadding="2">' . "\n";
	$close_string   = '                           </table></td>';
	$close_string  .= '<td width="10"></td>';
	$close_string  .= '</tr></table></td></tr></table></td>';
	$close_string  .= '<tr><td width="100%"></td></tr>';
	$output_string = '';
      reset($this->modules);
      //while (list(, $value) = each($this->modules)) {
	   //FOREACH
	   foreach($this->modules as $value) 
	   {
        $class = substr($value, 0, strrpos($value, '.')); //echo $class;
        if ($GLOBALS[$class]->enabled && method_exists($GLOBALS[$class], 'display_before_comments')) 
		{
		$output_string .= $GLOBALS[$class]->display_before_comments();
		}   
      }
	    $header_string .= '    </table>' . "\n" ;
        if ($output_string != '') 
		{
          $output_string = $header_string . $output_string;
          $output_string .= $close_string;
        }
	  return $output_string;
    }
    function process() 
	{
		global $order;

 	  $order_total_array = array();
      if (is_array($this->modules)) {
        reset($this->modules);
        //while (list(, $value) = each($this->modules)) {
	   //FOREACH
	   foreach($this->modules as $value) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $GLOBALS[$class]->process();
            for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) {
              if (tep_not_null($GLOBALS[$class]->output[$i]['title']) && tep_not_null($GLOBALS[$class]->output[$i]['text'])) {
			
                $order_total_array[] = array('code' => $GLOBALS[$class]->code,
											 'class' => $GLOBALS[$class]->credit_class,
                                             'title' => $GLOBALS[$class]->output[$i]['title'],
                                             'text' => $GLOBALS[$class]->output[$i]['text'],
                                             'value' => $GLOBALS[$class]->output[$i]['value'],
                                             'sort_order' => $GLOBALS[$class]->sort_order);
											 
											// print_r($order_total_array);
              }
            }
          }
        }
      }
      return $order_total_array;
    }

    function output() 
	{

      $output_string = '';
      if (is_array($this->modules)) {
        reset($this->modules);
        //while (list(, $value) = each($this->modules)) {
	   //FOREACH
	   foreach($this->modules as $value) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $size = sizeof($GLOBALS[$class]->output);
			
			//<td align="left" class="ot">' . iconv('UTF-8', 'ISO-8859-1',$GLOBALS[$class]->output[$i]['title']) . '</td>
			for ($i=0; $i<$size; $i++) 
			{
              $output_string .= '              <tr>' . "\n" .
                                '                <td align="right" class="ot">' . $GLOBALS[$class]->output[$i]['title'] . '</td>' . "\n" .
                                '                <td align="right" class="ot">' . $GLOBALS[$class]->output[$i]['text'] . '</td>' . "\n" .
                                '              </tr>';
            }

            // for ($i=0; $i<$size; $i++) {
              // $output_string .= '              <tr>' . "\n" .
			// '                <td align="left" class="ot">' . iconv('UTF-8', 'ISO-8859-1',$GLOBALS[$class]->output[$i]['title']) . '</td>' . "\n" .
                                // '                <td align="right" class="ot">' . $GLOBALS[$class]->output[$i]['text'] . '</td>' . "\n" .
                                // '              </tr>';
            // }
          }
        }
      }

      return $output_string;
    }
// CCGV - START ADDITION
//
// This function is called in checkout payment after display of payment methods. It actually calls
// two credit class functions.
//
// use_credit_amount() is normally a checkbox used to decide whether the credit amount should be applied to reduce
// the order total. Whether this is a Gift Voucher, or discount coupon or reward points etc.
//
// The second function called is credit_selection(). This in the credit classes already made is usually a redeem box.
// for entering a Gift Voucher number. Note credit classes can decide whether this part is displayed depending on
// E.g. a setting in the admin section.
//
    function credit_selection() 
	{
	
	$a=MODULE_ORDER_TOTAL_COUPON_HEADER;
	$b=MODULE_ORDER_TOTAL_COUPON_DESCRIPTION;
      $selection_string = '';
      $close_string = '';
      $credit_class_string = '';
      if (MODULE_ORDER_TOTAL_INSTALLED) 
	  {
         //$header_string .= '   <table width="100%" cellpadding="2">' . "\n";
        // $header_string .= '<tr>' . "\n";
        // $header_string .= '   <td><table  width="100%" cellpadding="2" class="infoBox">' . "\n";
        // $header_string .= '     <tr class="infoBoxContents"><td><table width="100%" cellpadding="2">' ."\n";
        // $header_string .= '       <tr><td width="10"></td>' . "\n";
        // $header_string .= '           <td colspan="2"><table width="100%" cellpadding="2">' . "\n";
        // $close_string   = '                           </table></td>';
        // $close_string  .= '<td width="10"></td>';
        // $close_string  .= '</tr></table></td></tr></table></td>';
        // $close_string  .= '<tr><td width="100%"></td></tr>';
        reset($this->modules);
        $output_string = '';
        //while (list(, $value) = each($this->modules)) {
	   //FOREACH
	   foreach($this->modules as $value) {
          $class = substr($value, 0, strrpos($value, '.'));
		  
		  
          if ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class && $class =='ot_coupon' )
		  {
            $use_credit_string = $GLOBALS[$class]->use_credit_amount();
            if ($selection_string =='') $selection_string = $GLOBALS[$class]->credit_selection();
            if ( ($use_credit_string !='' ) || ($selection_string != '') ) 
			{
              $output_string .= $selection_string;
            }

          }
        }
        //$header_string .= '    </table>' . "\n" ;
        if ($output_string != '') {
          $output_string = $header_string . $output_string;
          $output_string .= $close_string;
        }
      }
	  
      return $output_string;
    }

  function season_credit_selection() 
  {
  
	// $header_string .= '   <table width="100%" cellpadding="2">' . "\n";
	// $header_string .= '<tr>' . "\n";
	// $header_string .= '   <td><table  width="100%" cellpadding="2" class="infoBox">' . "\n";
	// $header_string .= '     <tr class="infoBoxContents"><td><table width="100%" cellpadding="2">' ."\n";
	// $header_string .= '       <tr><td width="10"></td>' . "\n";
	// $header_string .= '           <td colspan="2"><table width="100%" cellpadding="2">' . "\n";
	// $close_string   = '                           </table></td>';
	// $close_string  .= '<td width="10"></td>';
	// $close_string  .= '</tr></table></td></tr></table></td>';
	// $close_string  .= '<tr><td width="100%"></td></tr>';
	$output_string = '';
      reset($this->modules);
      //while (list(, $value) = each($this->modules)) {
	   //FOREACH
	   foreach($this->modules as $value) {
        $class = substr($value, 0, strrpos($value, '.')); //echo $class;
        if ($GLOBALS[$class]->enabled && method_exists($GLOBALS[$class], 'season_credit_selection')) 
		{

		    $output_string .= $GLOBALS[$class]->season_credit_selection();
			}   
      }
	  
	    //$header_string .= '    </table>' . "\n" ;
        if ($output_string != '') {
          $output_string = $header_string . $output_string;
          $output_string .= $close_string;
        }
	  return $output_string;
    }
  


// update_credit_account is called in checkout process on a per product basis. It's purpose
// is to decide whether each product in the cart should add something to a credit account.
// e.g. for the Gift Voucher it checks whether the product is a Gift voucher and then adds the amount
// to the Gift Voucher account.
// Another use would be to check if the product would give reward points and add these to the points/reward account.
//
    function update_credit_account($i) {
      if (MODULE_ORDER_TOTAL_INSTALLED) {
        reset($this->modules);
        //while (list(, $value) = each($this->modules)) {
	   //FOREACH
	   foreach($this->modules as $value) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) ) {
            $GLOBALS[$class]->update_credit_account($i);
          }
        }
      }
    }
// This function is called in checkout confirmation.
// It's main use is for credit classes that use the credit_selection() method. This is usually for
// entering redeem codes(Gift Vouchers/Discount Coupons). This function is used to validate these codes.
// If they are valid then the necessary actions are taken, if not valid we are returned to checkout payment
// with an error
//
    function collect_posts() {
      global $FREQUEST,$FSESSION;
      if (MODULE_ORDER_TOTAL_INSTALLED) {
        reset($this->modules);
        //while (list(, $value) = each($this->modules)) {
	   //FOREACH
	   foreach($this->modules as $value) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) ) {
            $post_var = 'c' . $GLOBALS[$class]->code;
            if ($FREQUEST->postvalue($post_var)) $FSESSION->set($post_var,$FREQUEST->postvalue($post_var));
//            if (!$FSESSION->is_registered($post_var)) tep_session_register($post_var);
            $GLOBALS[$class]->collect_posts();
          }
        }
      }
    }
// pre_confirmation_check is called on checkout confirmation. It's function is to decide whether the
// credits available are greater than the order total. If they are then a variable (credit_covers) is set to
// true. This is used to bypass the payment method. In other words if the Gift Voucher is more than the order
// total, we don't want to go to paypal etc.
//
    function pre_confirmation_check() {
      global $FSESSION, $order, $credit_covers;
	    	if($FSESSION->is_registered('credit_covers'))
			$FSESSION->remove('credit_covers');
      if (MODULE_ORDER_TOTAL_INSTALLED) {
        $total_deductions  = 0;
        reset($this->modules);
        $order_total = $order->info['total'];
        //while (list(, $value) = each($this->modules)) {
	   //FOREACH
	   foreach($this->modules as $value) {
          $class = substr($value, 0, strrpos($value, '.'));
          $order_total=$this->get_order_total_main($class,$order_total);
          if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) ) {
		  	$deduction=$GLOBALS[$class]->pre_confirmation_check($order_total); 
            $total_deductions = $total_deductions + $deduction;
            $order_total = $order_total + $deduction_amount;
          }
        }
        if ($order->info['total'] - $total_deductions <= 0 ) { 
          $credit_covers = true; 
		  $_SESSION['credit_covers']='valid';
        }else{$FSESSION->remove('credit_covers');}
      }
    }
// this function is called in checkout process. it tests whether a decision was made at checkout payment to use
// the credit amount be applied aginst the order. If so some action is taken. E.g. for a Gift voucher the account
// is reduced the order total amount.
//
    function apply_credit() {
      if (MODULE_ORDER_TOTAL_INSTALLED) {
        reset($this->modules);
        //while (list(, $value) = each($this->modules)) {
	   //FOREACH
	   foreach($this->modules as $value) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) ) {
            $GLOBALS[$class]->apply_credit();
          }
        }
      }
    }
// Called in checkout process to clear session variables created by each credit class module.
//
    function clear_posts() {
      global $FSESSION;
      if (MODULE_ORDER_TOTAL_INSTALLED) {
        reset($this->modules);
        //while (list(, $value) = each($this->modules)) {
	   //FOREACH
	   foreach($this->modules as $value) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) ) {
			$post_var = 'c' . $GLOBALS[$class]->code;
            $FSESSION->set($post_var,'c_' . $GLOBALS[$class]->code);
//            if ($FSESSION->is_registered($post_var)) $FSESSION->remove($post_var);
          }
        }
      }
    }
// Called at various times. This function calulates the total value of the order that the
// credit will be appled aginst. This varies depending on whether the credit class applies
// to shipping & tax
//
    function get_order_total_main($class, $order_total) {
      global $credit, $order;
      if ($GLOBALS[$class]->include_tax == 'false') $order_total=$order_total-$order->info['tax'];
      if ($GLOBALS[$class]->include_shipping == 'false') $order_total=$order_total-$order->info['shipping_cost'];
      return $order_total;
    }
    
    function collect_posts_forajax() {
      global $FREQUEST,$FSESSION;
      if (MODULE_ORDER_TOTAL_INSTALLED) {
        reset($this->modules);
        //while (list(, $value) = each($this->modules)) {
	   //FOREACH
	   foreach($this->modules as $value) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) ) {
            $post_var = 'c' . $GLOBALS[$class]->code;
            if ($FREQUEST->postvalue($post_var)) $FSESSION->set($post_var,$FREQUEST->postvalue($post_var));
//            if (!$FSESSION->is_registered($post_var)) tep_session_register($post_var);
            $GLOBALS[$class]->collect_posts_forajax();
          }
        }
      }
    }
// ICW ORDER TOTAL CREDIT CLASS/GV SYSTEM - END ADDITION
  }
?>
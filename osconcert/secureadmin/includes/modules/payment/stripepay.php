<?php
/*
file includes/modules/payment/stripepay.php 

Some code copyright (c) 2003-2014 osCommerce Released under the GNU General Public License 
Some code copyright 2013 osConcert

*/
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

class stripepay
{
    var $code, $title, $description, $enabled;
    // class constructor
    function __construct()
    {
        global $order;

        $this->code            = 'stripepay';
		$name = "Stripe Secure Payments";
		$image = "";
		$path = "";
		if(MODULE_PAYMENT_STRIPEPAY_DISPLAY_NAME != "MODULE_PAYMENT_STRIPEPAY_DISPLAY_NAME")$name = MODULE_PAYMENT_STRIPEPAY_DISPLAY_NAME;
		if(MODULE_PAYMENT_STRIPEPAY_IMAGE != "MODULE_PAYMENT_STRIPEPAY_IMAGE")$image = MODULE_PAYMENT_STRIPEPAY_IMAGE;
		if(DIR_WS_ADMIN != "DIR_WS_ADMIN" && DIR_WS_ADMIN != "")$path = "../";
		if($image != "" && file_exists($path . DIR_WS_IMAGES . $image)){
			$image = '<img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $image . '" height="35">';
		}else{
			$image_array = array('.gif','.jpg','.jpeg','.png');
			$image_check = true;
			for($i=0;$i<sizeof($image_array);$i++){
				if($image_check && $image != "" && file_exists($path . DIR_WS_IMAGES . $image . $image_array[$i])){
					$image = '<img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $image . $image_array[$i] . '" width="103" height="33">';
					$image_check = false;
				}
			}
			if($image_check)$image = $path;
		}
		
        $this->api_version     = 'Stripe Payments';
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_TITLE', $name . '&nbsp;&nbsp;' . $image);
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_TEXT_TITLE', $name);
        $this->title           = MODULE_PAYMENT_STRIPEPAY_TEXT_TITLE;
		$this->text_title      = MODULE_PAYMENT_STRIPEPAY_TEXT_TEXT_TITLE;
        $this->description     = MODULE_PAYMENT_STRIPEPAY_TEXT_DESCRIPTION;
        $this->sort_order      = MODULE_PAYMENT_STRIPEPAY_SORT_ORDER;
        $this->enabled         = ((MODULE_PAYMENT_STRIPEPAY_STATUS == 'True') ? true : false);
        $this->form_action_url = '';
        if ((int) MODULE_PAYMENT_STRIPEPAY_ORDER_STATUS_ID > 0) {
            $this->order_status = MODULE_PAYMENT_STRIPEPAY_ORDER_STATUS_ID;
        } 
        if (is_object($order))
            $this->update_status();
        
    }
    // class methods
    function update_status() {
      global $order;

  	  tep_check_module_status($this,MODULE_PAYMENT_STRIPEPAY_ZONE,trim(MODULE_PAYMENT_STRIPEPAY_EXCEPT_ZONE),trim(MODULE_PAYMENT_STRIPEPAY_EXCEPT_COUNTRY));	
  	  $this->barred=tep_check_payment_barred(trim(MODULE_PAYMENT_STRIPEPAY_EXCEPT_COUNTRY));
    }
    function javascript_validation()
    {
        return false;
    }
    function selection()
    {
        return array(
            'id' => $this->code,
            'module' => $this->title
        );
    }
    function pre_confirmation_check()
    {
        return false;
        
    }
    function confirmation()
    {
        global $order, $customer_id, $currency;
        //Stripe get the test/production state
        $publishable_key = ((MODULE_PAYMENT_STRIPEPAY_TESTMODE == 'Test') ? MODULE_PAYMENT_STRIPEPAY_TESTING_PUBLISHABLE_KEY : MODULE_PAYMENT_STRIPEPAY_LIVE_PUBLISHABLE_KEY);
        if ($publishable_key == '') {
?>
		<script type="text/javascript">
		alert('No Stripe Publishable Key found - unable to procede');
		</script>
		<?php
        }
		//V2.0 option to see Stripe checkout
		if(MODULE_PAYMENT_STRIPEPAY_CHECKOUT=='True'){
        $allow_remember = MODULE_PAYMENT_STRIPEPAY_REMEMBER;
		if((int)$customer_id == 0){$allow_remember='false';}
	    $confirmation['title'] .= '<div id="payment-errors" class="payment-errors messageStackError"></div>';
        $confirmation['title'] .= '<script type="text/javascript">	if (typeof jQuery == "undefined") {//no jquery
								document.write("<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js\">");
								document.write("<\/script>");} </script>';
		  if (MODULE_PAYMENT_STRIPEPAY_TESTMODE == 'Test') {
		    $confirmation['title'] .= '<h3>' . MODULE_PAYMENT_STRIPEPAY_TEXT_TITLE . '</h3>';
            $confirmation['title'] .= '<div class="messageStackError" style="margin:10px">Stripe Payments Test Mode
                                 <br>Use test card number 4242424242424242 or see https://stripe.com/docs/testing
                                <br>Use any expiry date in the future';
								
            if (MODULE_PAYMENT_STRIPEPAY_CVV == 'True') {
                $confirmation['title'] .= '<br>Use any CVV number<br>';
            } //MODULE_PAYMENT_STRIPEPAY_CVV == 'True'
            $confirmation['title'] .= '</div>';
            if ($this->stripe_is_ssl() == false) {
                $confirmation['title'] .= '<div class="messageStackError" style="margin:10px;padding:10px">This page is unsecured. Use only test credit card numbers.<br> Do <strong>NOT</strong> use for live transactions</div>';
            }
          } //MODULE_PAYMENT_STRIPEPAY_TESTMODE == 'Test'

$confirmation['title'] .= '<script src="https://checkout.stripe.com/checkout.js"></script>				
    <script type="text/javascript">
       jQuery(document).ready(function () {
        var form_bt$ = jQuery("form[name=checkout_confirmation]");

        var handler = StripeCheckout.configure({

            key: \''.$publishable_key.'\',
            token: function (token, args) {
                form_bt$.append("<input type=\'hidden\' name=\'StripeToken\' value=\'" + token.id + "\'>");
                form_bt$.attr(\'action\', \'checkout_process.php\');
                //hide button 
                jQuery("'.MODULE_PAYMENT_STRIPEPAY_BUTTON_ID.'").hide();
                // and submit
                form_bt$.get(0).submit();
			
            }
        });
        jQuery("form[name=checkout_confirmation]").submit(function (event) {
		     //MATC: if the checkbox exists and is not checked
			 if(   jQuery("input[name=agree]").prop("checked")==false){

			   			   return false;
			   
			 }
		
            // Open Checkout with further options
            handler.open({
                name: \''.STORE_NAME.'\',
				email: \''.$order->customer['email_address'].'\',
                description:\''.MODULE_PAYMENT_STRIPEPAY_POPUP_DESC.'\',
                amount: \''.$this->format_raw($order->info['total']) * $this->bt_currency_multiplyer($order->info['currency']).'\',
                currency: \''.$order->info['currency'].'\',
				image: \''.DIR_WS_IMAGES.MODULE_PAYMENT_STRIPEPAY_POPUP_IMAGE.'\',
                allowRememberMe: '.$allow_remember.',
				panelLabel:\''.MODULE_PAYMENT_STRIPEPAY_TEXT_POPUP_WORDING.'\'
            });
           
            	
            return false;
        });
    });

    </script >';
		}else{
        for ($i = 1; $i < 13; $i++) {
            $expires_month[] = array(
                'id' => sprintf('%02d', $i),
                'text' => strftime('%B', mktime(0, 0, 0, $i, 1, 2000))
            );
        } //$i = 1; $i < 13; $i++
        $today = getdate();
        for ($i = $today['year']; $i < $today['year'] + 10; $i++) {
            $expires_year[] = array(
                'id' => strftime('%y', mktime(0, 0, 0, 1, 1, $i)),
                'text' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
            );
        } //$i = $today['year']; $i < $today['year'] + 10; $i++
        $prev_customer          = false;
        $confirmation           = array();
        $confirmation['fields'] = array();
        //Stripe - check the stripe-id table for a stripe customer number
        if ($this->stripe_table_exists('stripe_data') && MODULE_PAYMENT_STRIPEPAY_CREATE_OBJECT == 'True' && ((int) $customer_id >0 )) {
            //this will get the most recent order by this customer only - will ignore previous orders 
            //maybe change this in the future if Stripe allow multiple cards	
            $check_customer_query = tep_db_query("select customers_id, stripe_description, stripe_customer, stripe_fingerprint, stripe_last4, stripe_type from stripe_data where customers_id = '" . (int) $customer_id . "' order by stripe_id DESC LIMIT 1");
            
            if (tep_db_num_rows($check_customer_query)) {
                $check_customer = tep_db_fetch_array($check_customer_query);
            }
            
            if (tep_not_null($check_customer['stripe_customer']) ) {//customer object in db
			 //check the customer email against that at Stripe just in case there is a discrepancy/OPC code
			      require_once(DIR_FS_CATALOG . "ext/modules/payment/stripe/Stripe.php");
				//Stripe get the test/production state
				$secret_key = ((MODULE_PAYMENT_STRIPEPAY_TESTMODE == 'Test') ? MODULE_PAYMENT_STRIPEPAY_TESTING_SECRET_KEY : MODULE_PAYMENT_STRIPEPAY_MERCHANT_LIVE_SECRET_KEY);
				Stripe::setApiKey($secret_key);
				$check_object=Stripe_Customer::retrieve($check_customer['stripe_customer']);
					if($check_object['description']==$order->customer['email_address']){
                      $prev_customer = true;
					  }
                
            }
        }
        
        //prev customer
        if ($prev_customer == true) { //previous customer with a stripe id
            $confirmation['fields'][] = array(
                'title' => '<div style="width:300px">' . MODULE_PAYMENT_STRIPEPAY_TEXT_PREV_CUST_CARD . ' ' . $check_customer['stripe_type'] . ' ' . MODULE_PAYMENT_STRIPEPAY_TEXT_PREV_CUST_NUMBER . ' ' . $check_customer['stripe_last4'] . ' ' . MODULE_PAYMENT_STRIPEPAY_TEXT_PREV_CUST_PAY . '<br>' . '<span id="stripe_tick">' . MODULE_PAYMENT_STRIPEPAY_TEXT_PREV_CUST_UNTICK . '</span></div>',
                'field' => tep_draw_checkbox_field('', 'use_me', $checked = true, 'class="existing_stripe"')
            );
            //pass the customer id
            $confirmation['fields'][] = array(
                'title' => '',
                'field' => tep_draw_hidden_field('StripeCustomerID', $check_customer['stripe_customer'])
            );
            $confirmation['fields'][] = array(
                'title' => '',
                'field' => tep_draw_hidden_field('StripeToken', 'NONE')
            );
            
            
        }
        //cc FIELDS
        $confirmation['fields'][] = array(
            'title' => '<span class="card_hide ">' . MODULE_PAYMENT_STRIPEPAY_CREDIT_CARD_OWNER . '</span>',
            'field' => $this->stripe_draw_input_field('', $order->billing['firstname'] . ' ' . $order->billing['lastname'], 'class="card-name card_hide form-control"')
        );
        $confirmation['fields'][] = array(
            'title' => '<span class="card_hide ">' . MODULE_PAYMENT_STRIPEPAY_CREDIT_CARD_NUMBER . '</span>',
            'field' => $this->stripe_draw_input_field('', '', 'class="card_number card_hide form-control"')
        );
        $confirmation['fields'][] = array(
            'title' => '<span class="card_hide">' . MODULE_PAYMENT_STRIPEPAY_CREDIT_CARD_EXPIRES . '</span>',
            'field' => $this->stripe_draw_pull_down_menu('', $expires_month, '', 'class="card_expiry_month card_hide form-control"') . '&nbsp;' . $this->stripe_draw_pull_down_menu('', $expires_year, '', 'class="card-expiry-year  card_hide form-control"')
        );
        
        //now for the extra things like CVV
        if (MODULE_PAYMENT_STRIPEPAY_CVV == 'True') {
            $confirmation['fields'][] = array(
                'title' => '<span class="card_hide">' . MODULE_PAYMENT_STRIPEPAY_CREDIT_CARD_CVC . '</span>',
                'field' => $this->stripe_draw_input_field('', '', 'size="5" maxlength="4" class="card_cvc card_hide"')
            );
        } //MODULE_PAYMENT_STRIPEPAY_CVV == 'True'
        
        //AVS                
        if (MODULE_PAYMENT_STRIPEPAY_AVS == 'True') {
            $confirmation['fields'][] = array(
                'title' => '',
                'field' => tep_draw_hidden_field('', $order->billing['street_address'], 'class="address_line1"')
            );
            $confirmation['fields'][] = array(
                'title' => '',
                'field' => tep_draw_hidden_field('', $order->billing['suburb'], 'class="address_line2"')
            );
            $confirmation['fields'][] = array(
                'title' => '',
                'field' => tep_draw_hidden_field('', $order->billing['state'], 'class="address_state"')
            );
            $confirmation['fields'][] = array(
                'title' => '',
                'field' => tep_draw_hidden_field('', $order->billing['postcode'], 'class="address_zip"')
            );
            $confirmation['fields'][] = array(
                'title' => '',
                'field' => tep_draw_hidden_field('', $order->billing['city'], 'class="address_city"')
            );
            $confirmation['fields'][] = array(
                'title' => '',
                'field' => tep_draw_hidden_field('', $order->billing['country']['title'], 'class="address_country"')
            );
        } //MODULE_PAYMENT_STRIPEPAY_AVS == 'True'
        //Now add in a 'save my details at Stripe
        //is the option to be allowed
        if (MODULE_PAYMENT_STRIPEPAY_SAVE_CARD == 'True' && MODULE_PAYMENT_STRIPEPAY_CREATE_OBJECT == 'True'&& ((int) $customer_id >0 )) {
            if (MODULE_PAYMENT_STRIPEPAY_SAVE_CARD_CHECK == 'Checked') {
                $box_tick = '$checked=true';
            } else {
                $box_tick = '';
            }
            $confirmation['fields'][] = array(
                'title' => '<div style="width:300px"><span class="card_hide ">' . MODULE_PAYMENT_STRIPEPAY_CREDIT_TEXT_CARD_SAVE . '</span></div>',
                'field' => tep_draw_checkbox_field('', 'save_me', $box_tick, 'class="new_stripe card_hide"')
            );
            
        }
        $confirmation['fields'][] = array(
            'title' => '',
            'field' => tep_draw_hidden_field('StripeSaveCard', 'YES')
        );

        
        
        $confirmation['title'] .= '<h3>' . MODULE_PAYMENT_STRIPEPAY_TEXT_TITLE . '</h3>';
        $confirmation['title'] .= '<div id="payment-errors" class="payment-errors messageStackError"></div>';
        $confirmation['title'] .= '<script type="text/javascript">	if (typeof jQuery == "undefined") {//no jquery
								document.write("<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js\">");
								document.write("<\/script>");} </script>';
        
        if ($prev_customer == true) { //previous customer with a stripe id           
            
            $confirmation['title'] .= '  <script type="text/javascript">
											$(document).ready(function() {										
											$(".card_hide").hide();
											$(".existing_stripe").change(function() {										
																					
											$(".card_hide").toggle("slow");										
											 if ( $(".existing_stripe:checked").length > 0) {
											 $("#stripe_tick").html("' . MODULE_PAYMENT_STRIPEPAY_TEXT_PREV_CUST_UNTICK . '");
											 }else{
											 $("#stripe_tick").html("' . MODULE_PAYMENT_STRIPEPAY_TEXT_PREV_CUST_TICK . '");
											 } 					
										
										
											});									
										
										
											});										
										   </script>';
            
        }
        
        if (MODULE_PAYMENT_STRIPEPAY_TESTMODE == 'Test') {
            $confirmation['title'] .= '<div class="messageStackError" style="margin:10px">Stripe Payments Test Mode
                                 <br>Use test card number 4242424242424242 or see https://stripe.com/docs/testing
                                <br>Use any expiry date in the future';
            if (MODULE_PAYMENT_STRIPEPAY_CVV == 'True') {
                $confirmation['title'] .= '<br>Use any CVV number<br>';
            } //MODULE_PAYMENT_STRIPEPAY_CVV == 'True'
            $confirmation['title'] .= '</div>';
            if ($this->stripe_is_ssl() == false) {
                $confirmation['title'] .= '<div class="messageStackError" style="margin:10px;padding:10px">This page is unsecured. Use only test credit card numbers.<br> Do <strong>NOT</strong> use for live transactions</div>';
            }
        } //MODULE_PAYMENT_STRIPEPAY_TESTMODE == 'Test'
        else {
            if ($this->stripe_is_ssl() == false) {
                $confirmation['title'] .= '<div class="messageStackError" style="margin:10px;padding:10px"><strong>Warning: Stripe Payment Module in Live Mode!</strong><br>This page is unsecured. Use only test credit card numbers.<br> Do <strong>NOT</strong> use for live transactions</div>';
            }
        }
        $confirmation['title'] .= '<script type="text/javascript" src="https://js.stripe.com/v1/"></script>';
        $confirmation['title'] .= '<script type="text/javascript">Stripe.setPublishableKey(\'' . $publishable_key . '\');</script>';
        $confirmation['title'] .= '<script type="text/javascript">

																
                                 jQuery(document).ready(function() {
								 	   jQuery(".btn-primary").removeAttr(\'onclick\');
                                      jQuery("form[name=checkout_confirmation]").submit(function(event) {
									  		     //MATC: if the checkbox exists and is not checked
													 if(   jQuery("input[name=agree]").prop("checked")==false){
										
																   return false;
													   
													 }
                                if ( jQuery(\'.existing_stripe\').attr(\'checked\')) { 								
							var form$ = jQuery("form[name=checkout_confirmation]");
                            form$.attr(\'action\', \'checkout_process.php\'); 
							//hide button
							jQuery("'.MODULE_PAYMENT_STRIPEPAY_BUTTON_ID.'").hide();;
							

                            // and submit
                            form$.get(0).submit();
					                                          
								
								}else{       
                                        Stripe.createToken({
                                            name: jQuery(\'.card-name\').val(),                    
                                            number: jQuery(\'.card_number\').val(),';
        if (MODULE_PAYMENT_STRIPEPAY_CVV == 'True') {
            $confirmation['title'] .= 'cvc: jQuery(\'.card_cvc\').val(),';
        } //MODULE_PAYMENT_STRIPEPAY_CVV == 'True'
        if (MODULE_PAYMENT_STRIPEPAY_AVS == 'True') {
            $confirmation['title'] .= 'address_line1: jQuery(\'.address_line1\').val(),';
            $confirmation['title'] .= 'address_line2: jQuery(\'.address_line2\').val(),';
            $confirmation['title'] .= 'address_state: jQuery(\'.address_state\').val(),';
            $confirmation['title'] .= 'address_zip: jQuery(\'.address_zip\').val(),';
            $confirmation['title'] .= 'address_city: jQuery(\'.address_city\').val(),';
            $confirmation['title'] .= 'address_country: jQuery(\'.address_country\').val(),';
        } //MODULE_PAYMENT_STRIPEPAY_AVS == 'True'
		if(defined('MODULE_PAYMENT_STRIPEPAY_STATEMENT_DESCRIPTION') && tep_not_null(MODULE_PAYMENT_STRIPEPAY_STATEMENT_DESCRIPTION)){ 
			$confirmation['title'] .= 'statement_descriptor: jQuery(\'.statement_descriptor\').val(),';
		}
        $confirmation['title'] .= 'exp_month: jQuery(\'select.card_expiry_month\').val(),
                                   exp_year: jQuery(\'select.card-expiry-year\').val()
                                        }, stripeResponseHandler);
										}
										
									   return false;
                                      });
                                    });
									
                                </script>';
        
        $confirmation['title'] .= '<script type="text/javascript">
                                function stripeResponseHandler(status, response) {
                        if (response.error) {
                            alert(response.error.message);
							//jQuery(".payment-errors").text(response.error.message);
                        } else {
                            var form$ = jQuery("form[name=checkout_confirmation]");
                            var token = response[\'id\'];
                            form$.append("<input type=\'hidden\' name=\'StripeToken\' value=\'" + token + "\'/>");
							 if ( jQuery(\'.new_stripe\').attr(\'checked\')) { 
							 form$.append("<input type=\'hidden\' name=\'StripeSaveCard\' value=\'YES\'/>");
							 }
                            form$.attr(\'action\', \'checkout_process.php\'); 
							//hide button
							 jQuery("'.MODULE_PAYMENT_STRIPEPAY_BUTTON_ID.'").hide();
							

                            // and submit
                            form$.get(0).submit();
                        }
                    }
                    </script>';
					}
        return $confirmation;
    }
    function process_button()
    {
        return false;
    }
    function before_process()
    {
        global $HTTP_POST_VARS, $customer_id, $order, $sendto, $currencies, $charge, $currency;
        require_once(DIR_FS_CATALOG . "ext/modules/payment/stripe/Stripe.php");
        //Stripe get the test/production state
        $secret_key = ((MODULE_PAYMENT_STRIPEPAY_TESTMODE == 'Test') ? MODULE_PAYMENT_STRIPEPAY_TESTING_SECRET_KEY : MODULE_PAYMENT_STRIPEPAY_MERCHANT_LIVE_SECRET_KEY);
        Stripe::setApiKey($secret_key);
        $error = '';
        // get the credit card details submitted by the form
        $token = $_POST['StripeToken'];
        
        $total_price = $this->format_raw($order->info['total']);
		//
		//Nov 2015 customer statement desc
			$statement_descriptor = NULL;
			if(defined('MODULE_PAYMENT_STRIPEPAY_STATEMENT_DESCRIPTION') && tep_not_null(MODULE_PAYMENT_STRIPEPAY_STATEMENT_DESCRIPTION)){ 
				$statement_descriptor = substr(MODULE_PAYMENT_STRIPEPAY_STATEMENT_DESCRIPTION,0,22);
						}
        //v1.2.2 create object
        if (MODULE_PAYMENT_STRIPEPAY_CREATE_OBJECT == 'True') {
            //existing customer 
            if (tep_not_null($_POST['StripeCustomerID'])) {
                if ($token == 'NONE') {
                    //charge the customer on existing card
                    try {
                        $charge = Stripe_Charge::create(array(
                            "amount" => $total_price * $this->bt_currency_multiplyer($order->info['currency']),
                            "currency" => $order->info['currency'],
							"capture" => MODULE_PAYMENT_STRIPEPAY_CAPTURE,
                            "customer" => $_POST['StripeCustomerID'],
							"statement_descriptor" => $statement_descriptor
                        ));
                    }
                    catch (Exception $e) {
                        $error = $e->getMessage();
                        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $error, 'SSL'));
                    }
                } //end use existing card
                //start new card
                
                //new card for the customer and he wants to save it (or we are not allowing the option do StripesaveCard==YES
                elseif (tep_not_null($_POST['StripeSaveCard']) && ($_POST['StripeSaveCard'] == 'YES')) {
                    try {
                        //update the card for the customer
                        $cu       = Stripe_Customer::retrieve($_POST['StripeCustomerID']);
                        $cu->card = $token;
                        $cu->save();
                        //charge the customer
                        $charge = Stripe_Charge::create(array(
                            "amount" => $total_price * $this->bt_currency_multiplyer($order->info['currency']),
                            "currency" => $order->info['currency'],
							"capture" => MODULE_PAYMENT_STRIPEPAY_CAPTURE,
                            "customer" => $_POST['StripeCustomerID'],
							"statement_descriptor" => $statement_descriptor
                        ));
                    }
                    catch (Exception $e) {
                        $error = $e->getMessage();
                        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $error, 'SSL'));
                    }
                } //end save card
                else {
                    //a saved customer has entered new card details but does NOT want them saved. Currently (Nov 2012) Stripe does not allow you to remove a card object so you'll have to charge the card and not the customer
                    try {
                        // create the charge on Stripe's servers - this will charge the user's card no customer object
                        $charge = Stripe_Charge::create(array(
                            "amount" => $total_price * $this->bt_currency_multiplyer($order->info['currency']),
                            "currency" => $order->info['currency'],
                            "card" => $token,
							"capture" => MODULE_PAYMENT_STRIPEPAY_CAPTURE,
                            "description" => $order->customer['email_address'],
							"statement_descriptor" => $statement_descriptor
                        ));
                    }
                    
                    catch (Exception $e) {
                        $error = $e->getMessage();
                        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $error, 'SSL'));
                    }
                }
                
                
                
            } //end existing customer
            //new customer wants to save card details
            elseif (tep_not_null($_POST['StripeSaveCard']) && ($_POST['StripeSaveCard'] == 'YES')) {
                //new customer create the object
                try {
                    // create a Customer
                    $customer = Stripe_Customer::create(array(
                        "card" => $token,
                        "description" => $order->customer['email_address']
                    ));
                    
                    // charge the Customer instead of the card
                    $charge = Stripe_Charge::create(array(
                        "amount" => $total_price * $this->bt_currency_multiplyer($order->info['currency']),
                        "currency" => $order->info['currency'],
                        "customer" => $customer->id,
						"capture" => MODULE_PAYMENT_STRIPEPAY_CAPTURE,
						"statement_descriptor" => $statement_descriptor
                    ));
                    
                    
                }
                catch (Exception $e) {
                    $error = $e->getMessage();
                    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $error, 'SSL'));
                }
                
            }
            //   not a customer token
            else {
                try {
                    // create the charge on Stripe's servers - this will charge the user's card no customer object
                    $charge = Stripe_Charge::create(array(
                        "amount" => $total_price * $this->bt_currency_multiplyer($order->info['currency']),
                        "currency" => $order->info['currency'],
                        "card" => $token,
						"capture" => MODULE_PAYMENT_STRIPEPAY_CAPTURE,
                        "description" => $order->customer['email_address'],
						"statement_descriptor" => $statement_descriptor
                    ));
                }
                
                catch (Exception $e) {
                    $error = $e->getMessage();
                    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $error, 'SSL'));
                }
            } //end not a customer token
            
        } //end of customer object = true
        else { //simple never create customer object
            
            try {
                // create the charge on Stripe's servers - this will charge the user's card no customer object
                $charge = Stripe_Charge::create(array(
                    "amount" => $total_price * $this->bt_currency_multiplyer($order->info['currency']),
                    "currency" => $order->info['currency'],
                    "card" => $token,
					"capture" => MODULE_PAYMENT_STRIPEPAY_CAPTURE,
                    "description" => $order->customer['email_address'],
					"statement_descriptor" => $statement_descriptor
                ));
            }
            
            catch (Exception $e) {
                $error = $e->getMessage();
                tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $error, 'SSL'));
            }
            
            
            
        }
        
        
        
        //	die ( $charge);
        return false;
    }
    function after_process()
    {
        global $charge, $insert_id, $customer_id, $order;
        //let's update the stripe_id table
        //exit(var_dump($charge));
        if ($this->stripe_table_exists('stripe_data')) {
            $sql_data_array = array(
                'orders_id' => tep_db_prepare_input($insert_id),
                'stripe_charge_id' => tep_db_prepare_input($charge->id),
                'customers_id' => tep_db_prepare_input($customer_id),
                'stripe_amount' => tep_db_prepare_input($charge->amount),
                'stripe_amount_refunded' => tep_db_prepare_input($charge->amount_refunded),
                'stripe_currency' => strtoupper(tep_db_prepare_input($charge->currency)),
                'stripe_customer' => tep_db_prepare_input($charge->customer),
                'stripe_description' => tep_db_prepare_input($charge->description),
               // 'stripe_disputed' => tep_db_prepare_input($charge->disputed),
               //'stripe_fee' => tep_db_prepare_input($charge->fee),
                'stripe_invoice' => tep_db_prepare_input($charge->invoice),
                //  'stripe_object' => tep_db_prepare_input($charge->object);
                'stripe_paid' => tep_db_prepare_input($charge->paid),
				'stripe_captured' => tep_db_prepare_input($charge->captured),
                'stripe_address_city' => tep_db_prepare_input($charge->source->address_city),
                'stripe_address_country' => tep_db_prepare_input($charge->source->address_country),
                'stripe_address_line1' => tep_db_prepare_input($charge->source->address_line1),
                'stripe_address_line1_check' => tep_db_prepare_input($charge->source->address_line1_check),
                'stripe_address_line2' => tep_db_prepare_input($charge->source->address_line2),
                'stripe_address_zip' => tep_db_prepare_input($charge->source->address_zip),
                'stripe_address_zip_check' => tep_db_prepare_input($charge->source->address_zip_check),
                'stripe_country' => tep_db_prepare_input($charge->source->country),
                'stripe_fingerprint' => tep_db_prepare_input($charge->source->fingerprint),
                'stripe_cvc_check' => tep_db_prepare_input($charge->source->cvc_check),
                'stripe_name' => tep_db_prepare_input($charge->source->name),
                'stripe_last4' => tep_db_prepare_input($charge->source->last4),
                'stripe_exp_month' => tep_db_prepare_input($charge->source->exp_month),
                'stripe_exp_year' => tep_db_prepare_input($charge->source->exp_year),
                'stripe_type' => tep_db_prepare_input($charge->source->type)
            );
            tep_db_perform('stripe_data', $sql_data_array);
        }
        //now let's update the orders table
        
        tep_db_query("update " . TABLE_ORDERS . " set 
							   cc_type = '" . tep_db_prepare_input($charge->source->type) . "',
							   cc_owner='" . tep_db_prepare_input($charge->source->name) . "',
							   cc_expires='" . tep_db_prepare_input($charge->source->exp_month) . "/" . tep_db_prepare_input($charge->source->exp_year) . "',
							   cc_number='XXXX-XXXX-XXXX-" . tep_db_prepare_input($charge->source->last4) . "'
    								where
						    	orders_id = '" . (int) $insert_id . "' ");
        
        //AVS checking
        if (MODULE_PAYMENT_STRIPEPAY_AVS == 'True' && ($charge->source->address_line1_check !== 'pass' || $charge->source->address_zip_check !== 'pass')) {
            $error = '';
            if ($charge->source->address_line1_check == 'fail') {
                $error .= MODULE_PAYMENT_STRIPEPAY_TEXT_AVS_FAILED . '. ';
            } //$charge->source->address_line1_check == 'fail'
            if ($charge->source->address_zip_check == 'fail') {
                $error .= MODULE_PAYMENT_STRIPEPAY_TEXT_ZIP_FAILED . '. ';
            } //$charge->source->address_zip_check == 'fail'
            if ($charge->source->address_line1_check == 'unchecked') {
                $error .= MODULE_PAYMENT_STRIPEPAY_TEXT_AVS_UNCHECKED . '. ';
            } //$charge->source->address_line1_check == 'unchecked'
            if ($charge->source->address_zip_check == 'unchecked') {
                $error .= MODULE_PAYMENT_STRIPEPAY_TEXT_ZIP_UNCHECKED;
            } //$charge->source->address_zip_check == 'unchecked'
            $sql_data_array2 = array(
                'orders_status' => MODULE_PAYMENT_STRIPEPAY_AVS_FAILED
            );
            tep_db_perform(TABLE_ORDERS, $sql_data_array2, "update", "orders_id='" . (int) $insert_id . "'");
            //// also change status  in order history
            $sql_data_array3 = array(
                'orders_id' => (int) $insert_id,
                'orders_status_id' => MODULE_PAYMENT_STRIPEPAY_AVS_FAILED,
                'date_added' => 'now()',
                'customer_notified' => 0,
                'comments' => $error
            );
            tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array3);
        } //MODULE_PAYMENT_STRIPEPAY_AVS == 'True' && ($charge->source->address_line1_check !== 'pass' || $charge->source->address_zip_check !== 'pass')
        //CVV checking
        if (MODULE_PAYMENT_STRIPEPAY_CVV == 'True' && $charge->source->cvc_check !== 'pass') {
            $cvv_error = '';
            if ($charge->source->cvc_check == 'fail') {
                $cvv_error .= MODULE_PAYMENT_STRIPEPAY_TEXT_CVV_FAILED . '. ';
            } //$charge->source->cvc_check == 'fail'
            elseif ($charge->source->cvc_check == 'unchecked') {
                $cvv_error .= MODULE_PAYMENT_STRIPEPAY_TEXT_CVV_UNCHECKED . '. ';
            } //$charge->source->cvc_check == 'unchecked'
            $sql_data_array4 = array(
                'orders_status' => MODULE_PAYMENT_STRIPEPAY_CVV_FAILED
            );
            tep_db_perform(TABLE_ORDERS, $sql_data_array4, "update", "orders_id='" . (int) $insert_id . "'");
            //// also change status  in order history
            $sql_data_array5 = array(
                'orders_id' => (int) $insert_id,
                'orders_status_id' => MODULE_PAYMENT_STRIPEPAY_CVV_FAILED,
                'date_added' => 'now()',
                'customer_notified' => 0,
                'comments' => $cvv_error
            );
            tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array5);
        } //MODULE_PAYMENT_STRIPEPAY_CVV == 'True' && $charge->source->cvc_check !== 'pass'
		#################################################	
//metadata
     try{
             $metadata_array = array();
			 $metadata_array['Invoice #: '] =    $insert_id;
			 
         		$products_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$insert_id . "'  order by products_id");
		while ($products = tep_db_fetch_array($products_query)) {
		//cartzone
			$products_array[] = array(	'id' => $products['products_id'],
										'qty' => $products['products_quantity'],
										'seat' => $products['products_name'],
										'date' => $products['concert_date'],
										'time' => $products['concert_time'],
										'venue' => $products['concert_venue'],										
										'type' => $products['events_type'],
										'categories_name' => $products['categories_name'],
										'concert_date' => $products['concert_date'],
										'is_printable' => $products['is_printable'],
										);
		}
		if (sizeof($products_array)>0){
					
					
						for ($i=0, $n=sizeof($products_array); $i<$n; $i++) {
							 {
							$metadata_array['Item '.($i+1)] = $products_array[$i]['qty'] . ' x ' . $products_array[$i]['seat'] . ': ' . $products_array[$i]['categories_name'] . ' - ' . $products_array[$i]['venue']. ' - ' . $products_array[$i]['time']. ' - ' . $products_array[$i]['date'];
						}
					
						}
						}
         ##########################################################
		 # send to stripe
		 ##########################################################
		 
		$ch = Stripe_Charge::retrieve($charge->id);
		$ch->metadata = $metadata_array;
		$ch->save();

}
catch (Exception $e) {//do nothing just continue
						}
        
        return false;
    }
    function get_error()
    {
        global $_GET;
        $error = array(
            'title' => MODULE_PAYMENT_STRIPEPAY_ERROR_TITLE,
            'error' => stripslashes($_GET['error'])
        );
        return $error;
    }
    function check()
    {
        if (!isset($this->_check)) {
            $check_query  = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_STRIPEPAY_STATUS'");
            $this->_check = tep_db_num_rows($check_query);
        } //!isset($this->_check)
        return $this->_check;
    }
    function install()
    {
        
        // OK lets add in a new order statuses  Stripe - failures
        $check_query = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'CVV error, payment taken' limit 1");
        if (tep_db_num_rows($check_query) < 1) {
            $status_query = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
            $status       = tep_db_fetch_array($status_query);
            $status_id    = $status['status_id'] + 1;
            $languages    = tep_get_languages();
            foreach ($languages as $lang) {
                tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id . "', '" . $lang['id'] . "', 'CVV error, payment taken')");
            } //$languages as $lang
        } //tep_db_num_rows($check_query) < 1
        else {
            $check     = tep_db_fetch_array($check_query);
            $status_id = $check['orders_status_id'];
        }
        $check_query2 = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'AVS unsuccessful, payment taken' limit 1");
        if (tep_db_num_rows($check_query2) < 1) {
            $status_query2 = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
            $status2       = tep_db_fetch_array($status_query2);
            $status_id2    = $status2['status_id'] + 1;
            $languages     = tep_get_languages();
            foreach ($languages as $lang) {
                tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id2 . "', '" . $lang['id'] . "', 'AVS unsuccessful, payment taken')");
            } //$languages as $lang
        } //tep_db_num_rows($check_query2) < 1
        else {
            $check2     = tep_db_fetch_array($check_query2);
            $status_id2 = $check2['orders_status_id'];
        }
        //Now for 'unchecked
        
        // OK lets add in a new order statuses  Stripe - failures
        $check_query3 = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'CVV - not checked, payment taken' limit 1");
        if (tep_db_num_rows($check_query3) < 1) {
            $status_query3 = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
            $status3       = tep_db_fetch_array($status_query3);
            $status_id3    = $status3['status_id'] + 1;
            $languages     = tep_get_languages();
            foreach ($languages as $lang) {
                tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id3 . "', '" . $lang['id'] . "', 'CVV - not checked, payment taken')");
            } //$languages as $lang
        } //tep_db_num_rows($check_query) < 1
        else {
            $check3     = tep_db_fetch_array($check_query3);
            $status_id3 = $check3['orders_status_id'];
        }
        $check_query4 = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'AVS not checked, payment taken' limit 1");
        if (tep_db_num_rows($check_query4) < 1) {
            $status_query4 = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
            $status4       = tep_db_fetch_array($status_query4);
            $status_id4    = $status4['status_id'] + 1;
            $languages     = tep_get_languages();
            foreach ($languages as $lang) {
                tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id4 . "', '" . $lang['id'] . "', 'AVS not checked, payment taken')");
            } //$languages as $lang
        } //tep_db_num_rows($check_query2) < 1
        else {
            $check4     = tep_db_fetch_array($check_query4);
            $status_id4 = $check4['orders_status_id'];
        }
        //duplicate the remove function to get any extra keys out of database e.g. if install fails part way through
		        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
		//config options
        
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('----- Common Settings -----<br /><br />Enable Stripe Payments', 'MODULE_PAYMENT_STRIPEPAY_STATUS', 'True', 'Do you want to accept Stripe payments?', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_STRIPEPAY_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '20', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_STRIPEPAY_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '30', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_STRIPEPAY_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '40', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        //extra payment statuses
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('----- Payment Statuses (rtm) ----- <br /><br />CVV failure order status', 'MODULE_PAYMENT_STRIPEPAY_CVV_FAILED', '" . $status_id . "', 'If CVV checking is activated what order status do you want to apply to CVV check failures?', '6', '69', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('AVS failure order status', 'MODULE_PAYMENT_STRIPEPAY_AVS_FAILED', '" . $status_id2 . "', 'If AVS checking is activated what order status do you want to apply to AVS check failures?', '6', '71', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('CVV unchecked order status', 'MODULE_PAYMENT_STRIPEPAY_CVV_UNCHECKED', '" . $status_id3 . "', 'If CVV checking is activated what order status do you want to apply to cases where the CVV is returned as unchecked??', '6', '69', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('AVS unchecked order status', 'MODULE_PAYMENT_STRIPEPAY_AVS_UNCHECKED', '" . $status_id4 . "', 'If AVS checking is activated what order status do you want to apply to cases where the AVS is returned as unchecked?', '6', '71', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        // test or production?
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_STRIPEPAY_TESTMODE', 'Test', 'Transaction mode used for processing orders', '6', '50', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ', now())");
		        // auth or capture?
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Capture payments (true) or Auth only (false)', 'MODULE_PAYMENT_STRIPEPAY_CAPTURE', 'true', 'True = Auth and capture a payment False = Auth only', '6', '50', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
        //Button ID
               tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order,  date_added) values ('CSS id or class of the payment button', 'MODULE_PAYMENT_STRIPEPAY_BUTTON_ID', '.btn-primary', 'If you enter the CSS class or id of your Confirm Order button here it will be hidden as the Stripe payment proceeds', '6', '50',  now())");
        //API keys
        //Testing Secret Key
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Testing Secret Key', 'MODULE_PAYMENT_STRIPEPAY_TESTING_SECRET_KEY', '', 'Testing Secret Key - obtainable in your Stripe dashboard.', '6', '60', now())");
        //Testing Publishable Key
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Testing Publishable Key', 'MODULE_PAYMENT_STRIPEPAY_TESTING_PUBLISHABLE_KEY', '', 'Testing Publishable Key  - obtainable in your Stripe dashboard.', '6', '61', now())");
        //Live Secret key
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Live Secret key', 'MODULE_PAYMENT_STRIPEPAY_MERCHANT_LIVE_SECRET_KEY', '', 'Live Secret key  - obtainable in your Stripe dashboard.', '6', '62', now())");
        //Live Publishable key    
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Live Publishable key', 'MODULE_PAYMENT_STRIPEPAY_LIVE_PUBLISHABLE_KEY', '', 'Live Publishable key  - obtainable in your Stripe dashboard.', '6', '63', now())");
        //CVV - defaults to True
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('----- Non Checkout Options ----- <br /><br />Enable CVV/CVC checking', 'MODULE_PAYMENT_STRIPEPAY_CVV', 'True', 'Do you want to enable CVV/CVC checking at Stripe? <b>Highly recommended</b>', '6', '68', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        //AVS - defaults to False
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable AVS check', 'MODULE_PAYMENT_STRIPEPAY_AVS', 'False', 'Do you want to enable Address Verification System checking at Stripe?', '6', '70', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        //create customer object?
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Create a Customer Object at Stripe?', 'MODULE_PAYMENT_STRIPEPAY_CREATE_OBJECT', 'False', 'Do you want to create Customer Objects at Stripe (True) or just charge the card every time (False)? ', '6', '72', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        //save card for customer
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow customers option not to save their card details?', 'MODULE_PAYMENT_STRIPEPAY_SAVE_CARD', 'False', 'If the above is set to <b>True</b> (create Customer Object) do you want to allow customers the option of not saving their card token with Stripe?', '6', '75', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Above option checked or unchecked?', 'MODULE_PAYMENT_STRIPEPAY_SAVE_CARD_CHECK', 'Checked', 'If the above is set to <b>True</b> do you want the option of saving to be checked or unchecked?', '6', '76', 'tep_cfg_select_option(array(\'Checked\', \'Unchecked\'), ', now())");
        //version 2.0 choose checkout
		        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('----- Use Stripe Checkout ? -----<br />Do you wish to use the Stripe Checkout module?', 'MODULE_PAYMENT_STRIPEPAY_CHECKOUT', 'False', 'Use the Stripe supplied checkout popup? ', '6', '64', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		//version 2.0 image on popup
		        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('----- Stripe Checkout Options -----<br />(Only applicable when the above is True)<br /><br />Image to use in popup.', 'MODULE_PAYMENT_STRIPEPAY_POPUP_IMAGE', 'stripe.png', 'Image to be in your store images folder and recommended size 128px x 128px', '6', '65', now())");
		//version 2.0 description on popup		
				        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Description to use in popup.', 'MODULE_PAYMENT_STRIPEPAY_POPUP_DESC', '', 'Description to go below the above image in the popup (30 characters or less) ', '6', '66', now())");
								//version 2.0 checkbox on popup		
				        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow \"Remember Me\" in popup?', 'MODULE_PAYMENT_STRIPEPAY_REMEMBER', 'false', 'Use the Stripe supplied checkout popup? ', '6', '65','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
						// November 2015 - customer statement description
						   tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order,  date_added) values ('Description for customer statement', 'MODULE_PAYMENT_STRIPEPAY_STATEMENT_DESCRIPTION', '', '22 alpha-numeric description that will appear on credit card statement. Leave blank to use default as per your Stripe dashboard', '6', '64',  now())");
						   tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Image', 'MODULE_PAYMENT_STRIPEPAY_IMAGE', 'stripepay.png', 'Set the Image of payment module', '6', '17', 'tep_cfg_file_field(', now())");
						   tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Display Name', 'MODULE_PAYMENT_STRIPEPAY_DISPLAY_NAME', 'Stripe Secure Payments', 'Set the Display name to payment module', '6', '8', now())");
						
						
        //new database table
        tep_db_query("CREATE TABLE IF NOT EXISTS `stripe_data` (
				  `stripe_id` int(11) NOT NULL auto_increment,
				  `orders_id` int(11) NOT NULL,
				  `customers_id` int(11) NOT NULL,
				  `stripe_charge_id` varchar(25) NOT NULL,
				  `stripe_amount` int(25) NOT NULL,
				  `stripe_amount_refunded` int(25) default NULL,
				  `stripe_currency` varchar(6) NOT NULL,
				  `stripe_customer` varchar(64) default NULL,
				  `stripe_description` varchar(255) default NULL,
				  `stripe_disputed` varchar(64) NOT NULL,
				  `stripe_fee` int(11) NOT NULL,
				  `stripe_invoice` varchar(64) default NULL,
				  `stripe_object` varchar(64) NOT NULL,
				  `stripe_paid` int(11) NOT NULL,
				  `stripe_captured` int(12) NOT NULL,
				  `stripe_address_city` varchar(255) NOT NULL,
				  `stripe_address_country` varchar(255) NOT NULL,
				  `stripe_address_line1` varchar(255) NOT NULL,
				  `stripe_address_line1_check` varchar(64) default NULL,
				  `stripe_address_line2` varchar(255) default NULL,
				  `stripe_address_zip` varchar(255) default NULL,
				  `stripe_address_zip_check` varchar(64) default NULL,
				  `stripe_country` varchar(64) NOT NULL,
				  `stripe_fingerprint` varchar(64) NOT NULL,
				  `stripe_cvc_check` varchar(64) default NULL,
				  `stripe_name` varchar(64) NOT NULL,
				  `stripe_last4` int(4) NOT NULL,
				  `stripe_exp_month` int(2) NOT NULL,
				  `stripe_exp_year` int(4) NOT NULL,
				  `stripe_type` varchar(64) NOT NULL,
				  PRIMARY KEY  (`stripe_id`)
				)   AUTO_INCREMENT=1 ;");
    }
    function remove()
    {
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
        
    }
    function keys()
    {
        return array(
            'MODULE_PAYMENT_STRIPEPAY_STATUS',
            'MODULE_PAYMENT_STRIPEPAY_ZONE',
            'MODULE_PAYMENT_STRIPEPAY_ORDER_STATUS_ID',
            'MODULE_PAYMENT_STRIPEPAY_SORT_ORDER',
            'MODULE_PAYMENT_STRIPEPAY_TESTMODE',
			'MODULE_PAYMENT_STRIPEPAY_CAPTURE',
			'MODULE_PAYMENT_STRIPEPAY_BUTTON_ID',
            'MODULE_PAYMENT_STRIPEPAY_TESTING_SECRET_KEY',
            'MODULE_PAYMENT_STRIPEPAY_TESTING_PUBLISHABLE_KEY',
            'MODULE_PAYMENT_STRIPEPAY_MERCHANT_LIVE_SECRET_KEY',
            'MODULE_PAYMENT_STRIPEPAY_LIVE_PUBLISHABLE_KEY',
			'MODULE_PAYMENT_STRIPEPAY_CVV_FAILED',
            'MODULE_PAYMENT_STRIPEPAY_CVV_UNCHECKED',
            'MODULE_PAYMENT_STRIPEPAY_AVS',
            'MODULE_PAYMENT_STRIPEPAY_AVS_FAILED',
            'MODULE_PAYMENT_STRIPEPAY_AVS_UNCHECKED',
			'MODULE_PAYMENT_STRIPEPAY_CHECKOUT',
			'MODULE_PAYMENT_STRIPEPAY_POPUP_IMAGE',
			'MODULE_PAYMENT_STRIPEPAY_POPUP_DESC',
			'MODULE_PAYMENT_STRIPEPAY_REMEMBER',
            'MODULE_PAYMENT_STRIPEPAY_CVV',
            'MODULE_PAYMENT_STRIPEPAY_CREATE_OBJECT',
            'MODULE_PAYMENT_STRIPEPAY_SAVE_CARD',
            'MODULE_PAYMENT_STRIPEPAY_SAVE_CARD_CHECK',
			'MODULE_PAYMENT_STRIPEPAY_IMAGE',
			'MODULE_PAYMENT_STRIPEPAY_DISPLAY_NAME',
			'MODULE_PAYMENT_STRIPEPAY_STATEMENT_DESCRIPTION'
            
        );
    }
    // format prices without currency formatting

	
	    function format_raw($number, $currency_code = '', $currency_value = '') {
      global $currencies,$FSESSION;
	  $currency=$FSESSION->currency;

      if (empty($currency_code) || !$this->is_set($currency_code)) {
        $currency_code = $currency;
      }

      if (empty($currency_value) || !is_numeric($currency_value)) {
        $currency_value = $currencies->currencies[$currency_code]['value'];
      }

      return number_format(tep_round($number * $currency_value, $currencies->currencies[$currency_code]['decimal_places']), $currencies->currencies[$currency_code]['decimal_places'], '.', '');
    }
    
    //function to get the currency multiplyer to reduce currency to base units - the default is 100 (e.g. USD, GBP,EUR) but we have a list here of other currencies to be checked 
    function bt_currency_multiplyer($order_currency)
    {
        //array currency code => mutiplyer
        $exceptions = array(
            'BIF' => 1,
            'BYR' => 1,
            'CLF' => 1,
            'CLP' => 1,
            'CVE' => 1,
            'DJF' => 1,
            'GNF' => 1,
            'IDR' => 1,
            'IQD' => 1,
            'IRR' => 1,
            'ISK' => 1,
            'JPY' => 1,
            'KMF' => 1,
            'KPW' => 1,
            'KRW' => 1,
            'LAK' => 1,
            'LBP' => 1,
            'MMK' => 1,
            'PYG' => 1,
            'RWF' => 1,
            'SLL' => 1,
            'STD' => 1,
            'UYI' => 1,
            'VND' => 1,
            'VUV' => 1,
            'XAF' => 1,
            'XOF' => 1,
            'XPF' => 1,
            'MOP' => 10,
            'BHD' => 1000,
            'JOD' => 1000,
            'KWD' => 1000,
            'LYD' => 1000,
            'OMR' => 1000,
            'TND' => 1000
        );
        $multiplyer = 100; //default value
        foreach ($exceptions as $key => $value) {
            if (($order_currency == $key)) {
                $multiplyer = $value;
                break;
            }
            
            
        }
        return $multiplyer;
        
    }
    
	
function stripe_is_ssl()
{
    if (isset($_SERVER['HTTPS'])) {
        if ('on' == strtolower($_SERVER['HTTPS']))
            return true;
        if ('1' == $_SERVER['HTTPS'])
            return true;
    } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
        return true;
    }
    return false;
}	


  function stripe_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {


    $field = '<select name="' . tep_output_string($name) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

   if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' selected="selected"';
      }

      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }
  function stripe_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true) {
       $field = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';


    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
    } elseif (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    }



    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= ' />';

    return $field;
  }
////
function stripe_table_exists($tablename, $database = false)
{

    $res = tep_db_query("
         SHOW TABLES IN " . DB_DATABASE . " LIKE '$tablename'
        ");
    
    if (tep_db_num_rows($res) < 1) {
        return 0;
    } else {
        return 1;
    }
	}
}//end class

?>
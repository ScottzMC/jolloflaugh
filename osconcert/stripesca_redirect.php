<?php
/*
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	http://www.osconcert.com

  	Copyright (c) 2020 osConcert

	Released under the GNU General Public License
*/

// Set flag that this is a parent file

##################################################################################
#           this file handles the stripe.js redirect for                         #
#           payment - it is accessed via checkout_process.php                    #
##################################################################################
	define( '_FEXEC', 1 );

    require('includes/application_top.php');
	
	require(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/payment/stripesca.php');
	
	// if the customer is not logged on, redirect them to the shopping cart page
	  if (!$FSESSION->is_registered('customer_id')) {
		tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
	  }
	  
	  

	$publishable_key = ((MODULE_PAYMENT_STRIPESCA_TESTMODE == 'Test') ? MODULE_PAYMENT_STRIPESCA_TESTING_PUBLISHABLE_KEY : MODULE_PAYMENT_STRIPESCA_LIVE_PUBLISHABLE_KEY); 
?>
				<html>
					<head>
					</head>
					<body>
					    <?php echo MODULE_PAYMENT_STRIPESCA_WAIT; ?>
						<script src="https://js.stripe.com/v3/"></script>
						<script>
							var stripe = Stripe('<?php echo $publishable_key;?>');

							stripe.redirectToCheckout({
							  sessionId: '<?php echo $FSESSION->stripe_id;?>'
							}).then(function (result) {
							//if the above javascript fails to work then return to store and 
                            //cancel the order	
								
							  window.location.replace("./stripesca_failure.php?c_id=<?PHP echo $_SESSION['customer_id'];?>&reason=1");
							});
						 </script>
					</body>
				</html>
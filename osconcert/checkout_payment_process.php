<?php
/*
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	https://www.osconcert.com

  	Copyright (c) 2020 osConcert

	Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	

	require(DIR_WS_CLASSES . 'payment.php');
	echo "processed^^";
	$payment_modules = new payment($FSESSION->payment);
	if (is_array($payment_modules->modules)) {
		echo $payment_modules->process_button();
	}
?>
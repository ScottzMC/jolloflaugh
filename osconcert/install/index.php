<?php
/* 
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	http://www.osconcert.com

  	Copyright (c) 2009-2014 osConcert
	
	osConcert Visual Seat Reservation
    Copyright (c) 2009 cartZone UK 
	
	Released under the GNU General Public License 

*/ 
//Graeme Tyson, sakwoya@sakwoya.co.uk,  March  2012 for osConcert
//skips index page and sends direct to install t&c
header("Location: install.php?step=10&upg=license");
//Graeme Tyson, sakwoya@sakwoya.co.uk,  March  2012 for osConcert
  require('includes/application.php');

  $page_file = 'index.php';
  $page_title = 'Welcome to the osConcert Installation';
  $page_contents = 'index.php';
  require('templates/main_page.php');
?>

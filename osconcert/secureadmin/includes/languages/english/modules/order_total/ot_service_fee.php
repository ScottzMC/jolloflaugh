<?php
/*
  $Id: ot_qty_discount.php,v 1.4 2004-08-22 dreamscape Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 Josh Dechant
  Protions Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  Adapted for osConcert, October 2010 Gordon Farmer cartzone.co.uk
*/

// Check to ensure this file is included in osConcert
defined('_FEXEC') or die();

  define('MODULE_ORDER_TOTAL_SERVICE_FEE_TITLE', 'Booking Fee (per ticket)');
  define('MODULE_ORDER_TOTAL_SERVICE_FEE_DESCRIPTION', 'Flat rate fee per ticket.');
  define('SHIPPING_NOT_INCLUDED', ' [Shipping not included]');
  define('TAX_NOT_INCLUDED', ' [Tax not included]');
  
  define('MODULE_ORDER_TOTAL_SERVICE_FEE_PERCENTAGE_TEXT_EXTENSION', ' (%s%%)'); // %s is the percent discount as a number; %% displays a % sign
  define('MODULE_ORDER_TOTAL_SERVICE_FEE_FLAT_RATE_TEXT_EXTENSION', ' (%s)');
  
  define('MODULE_ORDER_TOTAL_SERVICE_FEE_FORMATED_TITLE', '(per ticket) Booking Fee %s :'); //confirmation// %s is the placement of the MODULE_ORDER_TOTAL_SERVICE_FEE_PERCENTAGE_TEXT_EXTENSION
  define('MODULE_ORDER_TOTAL_SERVICE_FEE_FORMATED_TEXT', '<strong>+%s</strong>'); // %s is the discount amount formated for the currency
?>
<?php
/*
  $Id: ot_bofr.php,v 1.4 2004-08-22 dreamscape Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 Josh Dechant
  Protions Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  Adapted for osConcert, October 2010 Gordon Farmer cartzone.co.uk
*/

// Check to ensure this file is included in osConcert
defined('_FEXEC') or die();

  define('MODULE_ORDER_TOTAL_BOFR_TITLE', 'Box Office Discount');
  define('MODULE_ORDER_TOTAL_BOFR_DESCRIPTION', 'Global discount amount: ');
    define('MODULE_ORDER_TOTAL_BOFR_TITLE_DESCRIPTION', 'Description for order: <br>Leave blank for default.');
   define('MODULE_ORDER_TOTAL_BOFR_ERROR', 'Global discount amount was greater than the order amount: ');
   define ('MODULE_ORDER_TOTAL_BOFR_ERROR_NUMERIC','Amount did not appear to be numeric: ');
  define('SHIPPING_NOT_INCLUDED', ' [Shipping not included]');
  define('TAX_NOT_INCLUDED', ' [Tax not included]');
  
  define('MODULE_ORDER_TOTAL_BOFR_FEE_PERCENTAGE_TEXT_EXTENSION', ' (%s%%)'); // %s is the percent discount as a number; %% displays a % sign
  define('MODULE_ORDER_TOTAL_BOFR_FEE_FLAT_RATE_TEXT_EXTENSION', ' (%s)');
  
  define('MODULE_ORDER_TOTAL_BOFR_FEE_FORMATED_TITLE', '<strong>(per ticket) Booking Fee %s:</strong>'); //confirmation// %s is the placement of the MODULE_ORDER_TOTAL_BOFR_FEE_PERCENTAGE_TEXT_EXTENSION
  define('MODULE_ORDER_TOTAL_BOFR_FEE_FORMATED_TEXT', '<strong>+%s</strong>'); // %s is the discount amount formated for the currency
?>
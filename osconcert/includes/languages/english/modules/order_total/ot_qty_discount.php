<?php
/*
  $Id: ot_qty_discount.php,v 1.4 2004-08-22 dreamscape Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 Josh Dechant
  Protions Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  Adapted for osConcert, October 2010 Graeme Tyson, sakwoya@sakwoya.co.uk
*/

// Check to ensure this file is included in Freeway
defined('_FEXEC') or die();

  define('MODULE_ORDER_TOTAL_DISCOUNT_TITLE', 'Global Quantity Discount');
  define('MODULE_ORDER_TOTAL_DISCOUNT_DESCRIPTION', 'Quantity specific discount percentage or flat rate - Specify discount rate based on the number of items in the cart (global across all products).');
  define('SHIPPING_NOT_INCLUDED', ' [Shipping not included]');
  define('TAX_NOT_INCLUDED', ' [Tax not included]');
  
  define('MODULE_ORDER_TOTAL_DISCOUNT_PERCENTAGE_TEXT_EXTENSION', ' (%s%%)'); // %s is the percent discount as a number; %% displays a % sign
  define('MODULE_ORDER_TOTAL_DISCOUNT_FORMATED_TITLE', '<strong>Quantity Discount:</strong>'); // %s is the placement of the MODULE_ORDER_TOTAL_DISCOUNT_PERCENTAGE_TEXT_EXTENSION
  define('MODULE_ORDER_TOTAL_DISCOUNT_FORMATED_TEXT', '<strong>-%s</strong>'); // %s is the discount amount formated for the currency
?>
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


  define('MODULE_ORDER_TOTAL_COUPON_TITLE', 'Discount Coupon Code');
  define('MODULE_ORDER_TOTAL_EMAIL_COUPON_TITLE', 'Email Code');
  define('MODULE_ORDER_TOTAL_COUPON_HEADER', 'Gift Vouchers/Discount Coupons');
  define('MODULE_ORDER_TOTAL_COUPON_DESCRIPTION', 'Discount Coupon');
  define('SHIPPING_NOT_INCLUDED', ' [Shipping not included]');
  define('TAX_NOT_INCLUDED', ' [Tax not included]');
  define('MODULE_ORDER_TOTAL_COUPON_USER_PROMPT', '');
  define('ERROR_NO_INVALID_REDEEM_COUPON', 'This is not a valid redeem code.');
  define('ERROR_INVALID_STARTDATE_COUPON', 'This coupon is not available yet');
  define('ERROR_INVALID_FINISDATE_COUPON', 'This coupon has expired');
  define('ERROR_INVALID_USES_COUPON', 'This coupon could only be used ');  
  define('ERROR_INVALID_FREE_SHIPPING_COUPON','This coupon is valid only for the products which have shipping amount.');
  define('ERROR_LESSTHAN_COUPON_PRICE','The value of your Gift Voucher exceeds the cost of your order. If you proceed, Gift Voucher will be used in full and you will not gain the full benefit of of your gift voucher');
    define('ERROR_LESSTHAN_COUPON_TOTAL','The value of your coupon(s) exceeds the cost of your order. If you proceed coupon(s) will be used in full.');
  define('ERROR_LOW_ORDER_TOTAL','Coupon minimum order is %s - your order value is only %s');
  define('ERROR_COUPON_PRICE_EQUAL','Order Value is equal to Coupon Price');
  define('TIMES', ' times.');
  define('ERROR_INVALID_USES_USER_COUPON', 'You have used or are trying to use this coupon more times than it is valid to do so.'); 
    define('ERROR_INVALID_USES_USER_COUPON_ORDER', 'You have used or are trying to use this coupon more times on this order than it is valid to do so.'); 
  define('REDEEMED_COUPON', 'a coupon worth ');  
  define('REDEEMED_MIN_ORDER', 'on orders over ');  
  define('REDEEMED_RESTRICTIONS', ' [Product-Category restrictions apply]');
  //Message for Enforced Coupon Users
  if (ENFORCED_COUPON == 'yes'){
  define('TEXT_ENTER_COUPON_CODE', '<b>Enforced Coupon.<b> Enter Redeem Code and click Continue&nbsp;&nbsp;');
  }else{
  define('TEXT_ENTER_COUPON_CODE', 'Enter Redeem Code');
  }  
	define('TEXT_ENTER_COUPON_CODE_MULTIPLE', 'Separate multiple codes by commas');
	
	define('TEXT_COUPON_VALID','Valid coupon code: ');
	define('TEXT_COUPON_VALID_AMOUNT','Value: ');
	define('TEXT_COUPON_CODE_NEEDED','Please enter a code.');
	define('TEXT_COUPON_VALIDATE','Validate');
	define('TEXT_COUPON_CANCEL',' UNCHECK to Cancel coupons/start over.');
	define('TEXT_COUPON_RESTRICT1',' Restricted coupon, may not be used against: ');
	define('TEXT_COUPON_RESTRICT2',' Restricted coupon,');
	define('TEXT_COUPON_RESTRICT_PRODUCT',' Coupon valid only against certain products none of which are in your cart.');
	define('TEXT_ON_ORDERS',' on orders greater than ');

?>
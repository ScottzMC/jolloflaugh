<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('TEXT_IMPORTANT','<span class="red">IMPORTANT:</span> Uninstall and RE-install ALL modules everytime you return to add more.<br>Keep the Discount Coupon AFTER Booking Fee.<br>
The correct way is to install your mods one by one with the Total very last.<br>
An example of such an order is Quantity Discount, Booking Fee, Sub Total, Discount Coupons, Total.');
if(MODULE_PAYMENT_PAYPAL_API_STATUS=='True' && OWNER_IDENTIFICATION==''){
define('TEXT_IMPORTANT_PAYPAL','<span class="red">IMPORTANT:</span> If using Paypal API...don\'t forget to set your CLIENT ID >Payment Options>Owner Payment ID <br><span class="red">IMPORTANT:</span> Check you have your correct payment status. Please do not select -Default-');
}else{
define('TEXT_IMPORTANT_PAYPAL','<span class="red">IMPORTANT:</span> Check you have your correct payment status. Please do not select -Default-. Set as -Delivered-');	
}

//define('HEADING_TITLE', 'Modules');
//define('HEADING_TITLE', 'Called Payment');
define('HEADING_TITLE_MODULES_PAYMENT', 'Payment Modules');
define('HEADING_TITLE_MODULES_SHIPPING', 'Shipping Modules');
define('HEADING_TITLE_MODULES_ORDER_TOTAL', 'Order Total Modules');


define('TABLE_HEADING_ACTIVE','Active');
define('TABLE_HEADING_MODULES', 'Modules');
define('TABLE_HEADING_SORT_ORDER', 'Sort Order');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_MODULE_DIRECTORY', 'Module Directory:');
define('TEXT_KG','kg');
define('TEXT_KILOGRAMS','Kilograms');
define('TEXT_LP','lb');
define('TEXT_POUNDS','Pounds');
define('TEXT_EDIT','Edit');
define('TEXT_ASCENDING','Ascending');
define('TEXT_DESCENDING','Decending');
define('TEXT_SETTINGS','Settings');

define('INSTALLED_MODULES','Installed Modules');
define('NO_INSTALLED_MODULES_FOUND','No Installed Modules found');
define('UNINSTALLED_MODS','UnInstalled Modules');

define('IMAGE_ORDER_UP','Up');
define('IMAGE_ORDER_DOWN','Down');
define('IMAGE_INSTALL','Install');
define('IMAGE_REMOVE','Remove');
define('IMAGE_INSTALLED','Installed');
define('IMAGE_REMOVED','Not Installed');
define('IMAGE_SAVE','Save');
define('IMAGE_CLOSE_EDIT','Close');
define('IMAGE_EDIT','Edit');
define('IMAGE_DELETE','Delete');
define('TEXT_LOADING_DATA','Loading...');
define('TEXT_SORTING_DATA','Sorting...');
define('TEXT_DELETING_MODULE','Deleting Module...');
define('TEXT_INSTALLING_MODULE',"Installing...");
?>
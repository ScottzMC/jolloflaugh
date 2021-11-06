<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('TABLE_HEADING_CATEGORIES_PRODUCTS', 'Categories / Products');
define('TABLE_PRODUCT_MANAGEMENT','Product Management');
define('TABLE_PRODUCT_MARKETING','Product Marketing');
define('TABLE_PRODUCT_REPORTS','Product Reports');
define('HEADING_TITLE', 'Products/Seats');
define('TEXT_INFO_DATE_ADDED','Date Added');
define('IMAGE_VIEW','View');
define('IMAGE_NEW_CATEGORY','New Category');
define('IMAGE_NEW_PRODUCT','New Product');
define('IMAGE_NEW_FEATURED_PRODUCT','New Featured Product');
define('IMAGE_NEW_SPECIAL_PRODUCT','New Special Product');
define('IMAGE_DISCOUNTS','Discounts');
define('PRODUCT_STATUS_CHANGE','Product Status Change');
define('PRODUCTS_REMINDERS','Products Reminders');
define('TEXT_SEARCH','Search ');
define('TEXT_NOT_SET','Not Set');
define('TEXT_ALL_TICKETS_PRINTABLE','All tickets printable?');
define('TICKETS_MAY_BE_PRINTED','Tickets may be printed');
define('NO_TICKETS_MAY_BE_PRINTED','NO tickets may be printed');
define('TEXT_CATEGORY_ROWS_ONLY','(Reserved Seating Category Rows ONLY)');
define('PLEASE_SELECT','Please Select');
define('TEXT_GA_ONLY','Category with Master Quantity');
//define('TEXT_GA_PLUS','GA plus reserved seating');
define('TEXT_CAT_ID','Category ID');
define('TEXT_CREATE_TICKETS','Create pdf tickets? This may take some time for large shows.<br>Please be patient');

define('BEST_VIEWED_PRODUCTS','Best Viewed Products');
define('NO_PRODUCTS_FOUND','No Products Found');
define('IMAGE_DESCENDING','Descending');
define('IMAGE_ASCENDING','Ascending');
define('IMAGE_SHOW_AVAILABLE','Show Available');
define('TEXT_ASCENDING','A to Z ordering');
define('TEXT_DESCENDING','Z to A ordering');
define('TEXT_SHOW_AVAILABLE','Show Available');
define('ERR_CATEGORY_NAME','Category name should not empty for all languages...');
define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNING:</b> There are %s (child-)categories still linked to this category!');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>WARNING:</b> There are %s products still linked to this category!');
define('TEXT_MAIN_CATEGORIES','Main Category');
define('ERROR_IN_SORT_ORDER_TRY_AGAIN','we cannot sort this product');

define('TEXT_CATEGORY_SORT_ORDER','Category sort order:');
define('ERR_SELECT_OPTIONS_CHECKBOX','* Select anyone option checkbox');
define('ERR_PRICE_EMPTY','* Price should not empty for all option');
define('ERR_WEIGHT_EMPTY','* Weight should not empty for all option');
define('ERR_UNITS_EMPTY','* Unit should not empty for all option');
define('ERR_UNITS_PRICE_EMPTY','* Unit Price should not empty for all option');
define('ERR_QUANTITY_EMPTY','* Quantity should not empty or zero');

define('TEXT_RESTRICT_GROUPS','Restrict to Groups: ');
define('TEXT_RESTRICT_CUSTOMERS','Restrict to Customers: ');
define('HEADING_QUANTITY','Quantity');
define('HEADING_PRICE','Price');
define('HEADING_DISCOUNT','Discount Price');
define('IMAGE_FAILED_TO_UPLOAD','Image failed to upload');
define('FAILED_TO_UPLOAD','File failed to upload');
define('TEXT_PRICE_BREAK','Products Price Breaks');
define('IMAGE_ADD','Add');
define('IMAGE_UPDATE','Update');
define('IMAGE_DELETE','Delete');
define('TEXT_PRODUCT_QUANTITY','Quantity: ');
define('TEXT_PRODUCT_PRICE','Price: ');
define('TEXT_DISCOUNT_PRICE','Discount per Item: ');
define('ERR_PRODUCTS_QUANTITY','Invalid quantity');
define('ERR_DISCOUNT_PRICE','Invalid discount price');
define('ERR_QUANTITY','Quantity should be > 1');
define('ERR_PRICE_ALREADY_EXISTS','Price break already exists for this quantity');
define('ERR_DISC_PRICE','Discount price should not greater than products price');
define('ERR_PRICE_BREAK_EMPTY','Price Breaks should not be empty');
define('ERR_NEGATIVE_PRICE_BREAK','Price breaks should be a +ve value');
define('ERR_PRICE_BREAK','Invalid Price breaks');
define('TEXT_PRODUCT_TYPE','Product Type (IMPORTANT: <br>Select -General Admission- when creating General Admission items. <br>Select -Reserved Seating- when editing Seat Plan/Map products.)');
define('TEXT_GENERAL','General');

define('IMAGE_SAVE','Save');
define('IMAGE_CANCEL','Cancel');
// define('TEXT_NUM_USERS','Number of Users');
// define('ERR_NUM_USERS','Number of users should not empty and must be numeric');
define('ERR_CATEGORIES_IMAGE_LENGTH','Product categories image length should not greater than 64');
define('ERR_CATEGORIES_IMAGE_DOESNT_EXISTS','Image does not exists in this path');
define('ERR_IMAGE_LENGTH','image length should not greater than 64');
define('ERR_PRODUCT_IMAGE_LENGTH','Product image length should not greater than 64');

define('IMAGE_CLOSE','Close');
define('ERROR_SOURCE_FILE','Source file doesnt have write permission');

define('TEXT_INFO_DATE_MODIFIED','Date Modified');
define('TITLE_PRODUCTS','Products');
define('TEXT_PARENT_CATEGORY','Parent Category');
define('TEXT_DELETE_CONFIRM_INFO','Are you sure you want to delete this category and all its products?');
define('TEXT_DELETING_CATEGORY','Deleting the category..');
define('TEXT_DELETE_CONFIRM_INFO_IMAGE','Are you sure to delete this image');
define('TEXT_DELETING_CATEGORY_IMAGE','Deleting the category image ..');
define('TEXT_DELETE_INFO_IMAGE','Delete image?');
define('TEXT_EDIT_CATEGORIES_IMAGE_NEW','Upload a new category image?');
define('TEXT_EDIT_CATEGORIES_IMAGE_DELETE','Delete the category image?');
define('TEXT_PRODUCT_UPDATE_IMAGE','Saving Images..');
define('TEXT_PRODUCT_UPDATE_DATA','Saving Data..');
define('TEXT_CATEGORY_MOVING','Moving Category...');
define('TEXT_NEW_PRODUCT','New product');
define('TEXT_NEW_CATEGORY','New Category');
define('TEXT_VENUE','Venue');
define('TEXT_DELETING_PRODUCT','Deleting the product..');

define('TEXT_GENERAL','General');

define('TEXT_NO_USERS','Number of Users');
define('TEXT_PRICE_BREAK_OPTION_TEXT','##1## or more discount: ##2## each');
define('TEXT_FINAL_PRICE','Final Price');
define('TEXT_PRODUCT_MOVING','Moving Product...');

define('ERR_SELECT_PARENT_CATEGORY','Please select a parent category.');
define('ERR_CATEGORY_NAME_EMPTY','Category Name should not be empty for all languages.');
define('ERR_CATEGORY_TITLE_EMPTY','Category Heading Title should not be empty for all languages.');

define('ERR_PRODUCT_NAME_EMPTY','Product Name should not be empty for all languages.');
define('ERR_PRODUCT_SELECT_CATEGORY','Select one or more categories.');
define('ERR_PRODUCT_AUTHOR_EMPTY','Author Name should not be empty.');

define('ERR_PRODUCT_PRICE','Products price should be >=0');
define('ERR_PRODUCT_DOWNLOAD_LINK','Download Link should not be empty');
define('ERR_PRODUCT_PRICE_BREAKS_EMPTY','Price Break list should not be empty');
define('ERR_PRODUCT_WEIGHT_UNIT','Products weight should be a whole value');
define('ERR_PRODUCT_IMAGE_TYPES','Product Image Types should be .gif,.jpeg,.jpg,.png');

define('HEADING_ITEM_GENERAL','General');
define('HEADING_ITEM_TYPE','Type');
define('HEADING_ITEM_DESCRIPTION','Description');
define('HEADING_ITEM_COSTSTOCK','Cost & Stock');
define('HEADING_ITEM_IMAGE','Image');

define('EXT_PRICE_BREAK','Price Breaks');
define('TEXT_IN_STOCK','In Stock');
define('TEXT_OUT_STOCK','Out Stock');

define('ERR_PRICE_BREAK_EXISTS','Price Break already exists for this quantity');
define('ERR_PRICE_BREAK_LESS_PRICE','Discount price should be less than Net price');
define('ERR_PRICE_BREAK_QUANTITY','Quantity must be >=1');
define('ERR_PRICE_BREAK_PRICE','Discount Price should be a valid numeric value');
define('ERR_PRODUCT_PRICE','Product Net Price should be a valid numeric value');
define('ERR_PRODUCT_CURRENT_CATEGORY','Current Category cannot be unselected. If you want to remove please use Delete or Move action.');

define('TEXT_ATTRIB_SKU','SKU');

define('TEXT_ATTRIB_WEIGHT_PREFIX','Weight Prefix');
define('TEXT_ATTRIB_WEIGHT','Weight');
define('TEXT_ATTRIB_UNITS','Units');
define('TEXT_ATTRIB_UNITS_PRICE','Units Price');
define('TEXT_PRODUCT_NOT_DELETED','Product not deleted!');

define('TEXT_COPY_ALSO','Include');
define('TEXT_COPY_PRICEBREAKS','Price Breaks');
define('TEXT_INVALID_DATA','Invalid Data');
define('TEXT_COPY_IMAGES','Images');

define('TEXT_PRODUCT_DELETE_SUCCESS','Product from Selected categories deleted Successfully');

define('TEXT_PRODUCT_MOVED_SUCCESS','Product Moved Successfully');
define('TEXT_PRODUCT_ALREADY_LINKED','Product Already linked to selected Category');

define('TEXT_PRODUCT_MOVING','Moving Product..');
define('TEXT_PRODUCT_COPYING','Copying Product..');
define('TEXT_PRODUCT_DELETING','Deleting Product..');

define('TEXT_PRODUCT_LINKED_SUCCESS','Product Linked to selected category successfully');
define('ERR_PRODUCT_NOT_COPIED','Product not copied');

define('TEXT_NEW_CATEGORY','New Category');
define('TEXT_PRODUCT_COPIED_SUCCESS','Product Copied to selected category successfully');
define('INFO_LOADING_PRODUCTS','Loading Products...');

define('TEXT_ALL','All');
define('TEXT_SHOW','Show: ');
define('ERR_CATEGORY_SELECT_PARENT','Please select Parent Category');
define('HEADING_ITEM_COST','Cost');
define('HEADING_ITEM_STOCK','Stock');
define('PRODUCT_INVALID_DATE_AVAILABLE','Products date available should be in the format');

?>
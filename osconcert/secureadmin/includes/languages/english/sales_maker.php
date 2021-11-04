<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('TEXT_IMPORTANT','<span class="red">IMPORTANT:</span> Only available for RESERVED SEATING products. NOT General Admission');

define('HEADING_TITLE', 'SaleMaker');

define('TABLE_HEADING_SALE_NAME', 'SaleName');
define('TABLE_HEADING_SALE_DEDUCTION', 'Deduction');
define('TABLE_HEADING_SALE_DATE_START', 'Startdate');
define('TABLE_HEADING_SALE_DATE_END', 'Enddate');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_SALEMAKER_NAME', 'SaleName:');
define('TEXT_SALEMAKER_DEDUCTION', 'Deduction:');
define('TEXT_SALEMAKER_DEDUCTION_TYPE', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Type:&nbsp;&nbsp;');
define('TEXT_SALEMAKER_PRICERANGE_FROM', 'Products Price range:');
define('TEXT_SALEMAKER_PRICERANGE_TO', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
define('TEXT_SALEMAKER_SPECIALS_CONDITION', 'If a product is a Special:');
define('TEXT_SALEMAKER_DATE_START', 'Start Date:');
define('TEXT_SALEMAKER_DATE_END', 'End Date:');
define('TEXT_SALEMAKER_CATEGORIES', '<b>Or</b> check the categories to which this sale applies:');
//define('TEXT_SALEMAKER_POPUP', '<a href="javascript:session_win();"><span class="errorText"><b>Click here for Salemaker Usage Tips.</b></span></a>');
define('TEXT_SALEMAKER_IMMEDIATELY', 'Immediately');
define('TEXT_SALEMAKER_NEVER', 'Never');
define('TEXT_SALEMAKER_ENTIRE_CATALOG', 'Check this box if you want the sale to be applied to <b>all products</b>:');
define('TEXT_SALEMAKER_TOP', 'Entire catalog');
define('TEXT_SALEMAKER_PRODUCTS','&nbsp; Check the Products to which this sale applies:');

define('TEXT_INFO_DATE_ADDED', 'Date Added:');
define('TEXT_INFO_DATE_MODIFIED', 'Last Modified:');
define('TEXT_INFO_DATE_STATUS_CHANGE', 'Last Status Change:');
define('TEXT_INFO_SPECIALS_CONDITION', 'Specials Condition:');
define('TEXT_INFO_DEDUCTION', 'Deduction:');
define('TEXT_INFO_PRICERANGE_FROM', 'Price range:');
define('TEXT_INFO_PRICERANGE_TO', ' to ');
define('TEXT_INFO_DATE_START', 'Starts:');
define('TEXT_INFO_DATE_END', 'Expires:');
define('TEXT_LOCATION_NOT_FOUND', 'No Location found');

define('SPECIALS_CONDITION_DROPDOWN_0', 'Ignore Specials Price');
define('SPECIALS_CONDITION_DROPDOWN_1', 'Ignore SaleCondition');
define('SPECIALS_CONDITION_DROPDOWN_2', 'Apply SaleDeduction to Specials Price');

define('DEDUCTION_TYPE_DROPDOWN_0', 'Deduct amount');
define('DEDUCTION_TYPE_DROPDOWN_1', 'Percent');
define('DEDUCTION_TYPE_DROPDOWN_2', 'New Price');

define('TEXT_INFO_HEADING_COPY_SALE', 'Copy Sale');
define('TEXT_INFO_COPY_INTRO', 'Enter a name for the copy of &nbsp;&nbsp;"%s"');

define('TEXT_INFO_HEADING_DELETE_SALE', 'Delete Sale');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure to delete this sale?');
define('TEXT_NEW_SALES','Create New Discount');
define('IMAGE_BUTTON_NEW','New');
define('INFO_TEXT', '<ul>
                      <li>
                        Always use a \'.\' as decimal point in deduction and Price range.
                      </li>
                      <li>
                        Enter amounts in the same currency as you would when creating/editing a product.
                      </li>
                      <li>
                        In the Deduction fields, you can enter an amount or a percentage to deduct,
                        or a replacement Price. (eg. deduct $5.00 from all Prices, deduct 10% from
                        all Prices or change all Prices to $25.00)
                      </li>
                      <li>
                        Entering a Price range narrowes down the productrange that will be affected. (eg.
                        products from $50.00 to $150.00)
                      </li>
                      <li>
                        You must choose the action to take if a product is a special <i>and</i> is subject to this sale:
						<ul>
                          <li>
                            Ignore Specials Price<br>
							The salededuction will be applied to the regular Price of the product.
                            (eg. Regular Price $10.00, Specials Price is $9.50, SaleCondition is 10%.
							The product\'s final Price will show $9.00 on sale. The Specials Price is ignored.)
                          </li>
                          <li>
                            Ignore SaleCondition<br>
                            The salededuction will not be applied to Specials. The Specials Price will show just like
                            when there is no sale defined. (eg. Regular Price $10.00, Specials Price is $9.50,
                            SaleCondition is 10%. The product\'s final Price will show $9.50 on sale.
                            The SalesCondition is ignored.)
                          </li>
                          <li>
                            Apply SaleCondition to Specials Price<br>
                            The salededuction will be applied to the Specials Price. A compounded Price will show.
                            (eg. Regular Price $10.00, Specials Price is $9.50, SaleCondition is 10%. The product\'s
                            final Price will show $8.55. An additional 10% off the Specials Price.)
                          </li>
                        </ul>
                      </li>
                      <li>
                        Leaving the start date empty will start the sale immediately.
                      </li>
                      <li>
                        Leave the end date empty if you do not want the sale to expire.</li>
                      <li>
                        Checking a category automatically includes the sub-categories.
                      </li>
                    </ul>');
define('TEXT_TIPS','Salemaker Usage Tips');		
define('ERR_INVALID_DEDUCTION','Invalid Deduction Value')		;
define('ERR_INVALID_PRODUCTS_RANGE_FROM','Invalid products price range from value');
define('ERR_INVALID_PRODUCTS_RANGE_TO','Invalid products price range to value');
define('ERR_START_DATE','Start date must less than the End date');
define('ERR_INVALID_START_DATE','Invalid Start Date');
define('ERR_INVALID_END_DATE','Invalid End Date');
define('ERR_SOURCES_NAME','Invalid Source Name');
define('TEXT_NO_SALES','No Sales Found');
define('TEXT_SALES_MAKER','Sales Maker');
define('TEXT_CUSTOMER_CHOICE','Customer Choice');
define('TEXT_DISCOUNT_TYPE','Discount Type: ');
define('TABLE_HEADING_DISCOUNT_TYPE','Discount Type');
define('TEXT_CHOICE_TEXT','Choice Text: ');
define('TEXT_CHOICE_WARNING','Choice Warning: ');
define('ERR_CHOICE_TEXT','Choice text should not be empty.');
define('ERR_CHOICE_WARNING','Choice warning should not be empty.');
define('ERROR_SALE_MAKER','Cannot save this sale maker, some of the products has price breaks.');
define('TEXT_DISCOUNTS','Already customer has group discounts.');
define('TEXT_APPLY_TO_CROSS_SALES','Apply to cross sales');
define('TEXT_FORCED_ITEM_WARNING','Please ensure that the Forced Sale items selected in the Applied List of categories or products');

define('TABLE_HEADING_NAME','Sales Name');
define('HEADING_NEW_TITLE','New Sale');
define('TEXT_SALES_DELETE_SUCCESS','Sale Deleted Successfully');
define('TEXT_SALES_NOT_DELETED','Sales not deleted');
define('TEXT_LOADING_DATA','Loading Data');
define('TEXT_UPDATING_DATA','Updating Data');
define('INFO_LOADING_SALE','Loading Data');
define('TEXT_RECORDS','Sales');
define('TEXT_SALES_COPY_SUCCESS','Sale Copied Successfully');
define('TEXT_EMPTY_GROUPS','No Records Found');
define('COPY_NAME_REQUIRED','Name is Required to Copy the details');
?>
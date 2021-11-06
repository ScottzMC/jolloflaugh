<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('HEADING_TITLE', 'Salemaker');
define('SUBHEADING_TITLE', 'Salemaker Usage Tips:');
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
define('TEXT_CLOSE_WINDOW', '[ close window ]');
?>
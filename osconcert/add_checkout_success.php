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


//ICW ADDED FOR ORDER_TOTAL CREDIT SYSTEM - Start Addition
  $gv_query=tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id='". (int)$FSESSION->customer_id ."'");
  if ($gv_result=tep_db_fetch_array($gv_query)) 
  {
    if ($gv_result['amount'] > 0) 
	{
?>
<table>
      <tr>
        <td align="center" class="main">
		<?php 
		echo GV_HAS_VOUCHERA; 
		echo tep_href_link(FILENAME_GV_SEND); 
		echo GV_HAS_VOUCHERB; 
		?>
		</td>
      </tr>
</table>
<?php
	}
 }
//ICW ADDED FOR ORDER_TOTAL CREDIT SYSTEM - End Addition
?>
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
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
require('includes/application_top.php');


?>
<html>
<head>
<title>Valid Categories/Products List</title>
<style type="text/css">
<!--
h4 {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: x-small; text-align: center}
p {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small}
th {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small}
td {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small}
-->
</style>
<head>
<body>
<table width="550" border="1" cellspacing="1" bordercolor="gray">
<tr>
<td colspan="3">
<h4>Valid Products List</h4>
</td>
</tr>
<?php
   $coupon_get=tep_db_query("select restrict_to_products,restrict_to_categories from " . TABLE_COUPONS . "  where coupon_id='".tep_db_input($FREQUEST->getvalue('cid'))."'");
   $get_result=tep_db_fetch_array($coupon_get);

    echo "<tr><th>Product ID</th><th>Product Name</th></tr><tr>";
	// echo "<tr><th>Product ID</th><th>Product Name</th><th>Product Size</th></tr><tr>";
    $pr_ids = preg_split("/[,]/", $get_result['restrict_to_products']);
    for ($i = 0; $i < count($pr_ids); $i++) {
      $result = tep_db_query("SELECT  * FROM products, products_description WHERE products.products_id = products_description.products_id and products_description.language_id = '" . (int)$FSESSION->languages_id . "'and products.products_id = '" . (int)$pr_ids[$i] . "'");
      if ($row = tep_db_fetch_array($result)) {
            echo "<td>".$row["products_id"]."</td>\n";
            echo "<td>".$row["products_name"]."</td>\n";
          //  echo "<td>".$row["products_model"]."</td>\n";
            echo "</tr>\n";
      }
    }
      echo "</table>\n";
?>
<br>
<table width="550" border="0" cellspacing="1">
<tr>
<td align=middle><input type="button" value="Close Window" onClick="window.close()"></td>
</tr></table>
</body>
</html>

<?php
// handles ajax calls for draggable seats
// could be integrated with seatplan_ajax.php 
// after final development

define( '_FEXEC', 1 );
require('includes/application_top.php');

if($_SESSION['customer_country_id']==999 ){

if(!$_POST["data"]){
    exit;
}

$data = json_decode($_POST["data"]);
 
foreach($data->coords as $item) {
    //Extract X number for panel
    $coord_X = preg_replace('/[^\d\s]/', '', intval($item->coordLeft));
    //Extract Y number for panel
    $coord_Y = preg_replace('/[^\d\s]/', '', intval($item->coordTop));
	// Extract the product id
	$product_id = preg_replace('/[^\d\s]/', '', $item->id);
    //escape our values - as good practice
    $x_coord = tep_db_input($coord_X);
    $y_coord = tep_db_input($coord_Y);
	$prod_id = tep_db_input($product_id);
     
    //Setup our Query
    $sql = "UPDATE products SET products_x = '$x_coord', products_y = '$y_coord' WHERE products_id = '$prod_id'";
    //echo $sql;
    //Execute our Query
    tep_db_query($sql) or die("Error updating Coords :".mysqli_error()); 
}
	
	
}

		

?>
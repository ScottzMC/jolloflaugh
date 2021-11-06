<?php
/* This script is intended to serve the CODEREADR API Barcode without exposing
your unique API Key. Your API Key is your automated login for API Calls and if
exposed will present security vulnerabilities

To generate a barcode using this script use <img src="PATH/barcode.php?value=BARCODEVALUE" />
This does not add values to any CODEREADR or local database. 
 */
 define('_FEXEC','0');
 include("includes/application_top.php");
if(CR_ACTIVE=="True"){
$cr_value=$_GET['value'];


$img="http://www.codereadr.com/api/?section=barcode&api_key=".CR_KEY."&action=generate&value=".str_replace(" ","%20",$cr_value)."&size=".CR_SIZE."&hidevalue=1";
header("Content-Type: image/png");
readfile($img);
}

?>
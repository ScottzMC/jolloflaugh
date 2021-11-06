<?php
// this file is a copy of the /catalog/cr_dbadd.php file with some changes
if(CR_ACTIVE=="True")
{

//$url="http://";
/*Establish SQL Connection*/
	tep_db_connect();
/*Query for and load order record for reference verification*/
	$order_query=tep_db_query("SELECT * FROM ".TABLE_ORDERS." WHERE orders_id='".(int)$oID."'");
	$db_record_order =tep_db_fetch_array($order_query);
	$ordr_name=$db_record_order['customers_name'];

	if ($_POST['status']=="3")
	{
	
		if (isset($db_record_order['orders_id'])){
		
	/*Load orders_product*/
			$order_querya=tep_db_query("SELECT * FROM ".TABLE_ORDERS_PRODUCTS." WHERE orders_id='".(int)$oID."'");
			while($db_record_product = tep_db_fetch_array($order_querya)){
			$id = $db_record_product['products_id'];
			$type = $db_record_product['support_packs_type'];//products_type
			$dateid = $db_record_product['products_model'];
			$quantity = $db_record_product['products_quantity'] + 1;//used in a loop further on
			//TIX ID e.g 11_1234
			$cr_value=$db_record_product['orders_id'].'_'.$db_record_product['products_id'];
						
		require_once('../includes/functions/categories_lookup.php');
		
		//require_once(DIR_WS_INCLUDES . 'functions/categories_lookup.php');
		#####################################################
        // call the new function
        $type = $products[$i]['product_type'];
        
        list($heading_name, $heading_venue,  $heading_date, $heading_time) = categories_lookup();

		######################################################
			
	/*Get database ID*/
			$filename="http://www.codereadr.com/api/?section=database&api_key=".CR_KEY."&action=retrieve";
			$xml = simplexml_load_file($filename);
			//$cr_dbname=$heading_name."-".$heading_venue."-".$heading_date."-".$heading_time."-".$dateid;
			$cr_dbname=$dateid; 																															//$cr_dbname=$db_record_row_id['categories_heading_title']."-".$db_record_venue['concert_venue']."-".$db_record_date['concert_date']."-".$db_record_time['concert_time']."-".$db_record_time_id['date_id'];
			$databases = $xml->database;
			$db_flag=0;
			for ($i = 0; $i < count($databases); $i++) {
				if($databases[$i]->name==$cr_dbname){
					$cr_db=$databases[$i]->attributes()->id;
					//echo("<!-- Loaded: ".$databases[$i]->attributes()->id."-->");
					$db_flag=1;
				}
			}
			/*Create database if it does not exist*/
			if ($db_flag==0){
				$filename="http://www.codereadr.com/api/?section=database&api_key=".CR_KEY."&action=create&database_name=".$cr_dbname;
				$xml = simplexml_load_file($filename);
				//echo("<!-- Created: ".$xml->id."-->");
				$cr_db=$xml->id;
			}
			for ($xx = 1; $xx < $quantity; $xx++) { //will only fire once if products_quantity = 1
			
			$cr_response="Admit: ".$db_record_product['products_quantity']."  Name: (".$ordr_name.") Seat: (".
			$db_record_product['products_name'].") Show: (".
			$heading_name.") Date: (".$heading_date.") Time: (".
			$heading_time.") Venue: (".$heading_venue.") Date ID: (".
			$dateid.") ";
			//a little bit to get the extra where quantity > 1
			if ($quantity < 3){
			   $extra = '_1';}
			   else{
			   $extra = "_".$xx;
			   }
			//this line should update the cr db - the value will now be orders_id_product_id_xx where
			//xx is the value of $xx 
			
			$file = "http://www.codereadr.com/api/?section=databases&api_key=".CR_KEY."&database_id=".$cr_db."&action=addvalue&value=".$cr_value.$extra."&response=".$cr_response;
			//$file=str_replace(" ","%20",$file);
		$xml = simplexml_load_file($file);
		if($xml->status=='1'){
		
		$img_logo="<img src='".CR_LOGO."'  />";
		//$img_barcode="<img src='".DIR_WS_HTTP_CATALOG."barcode.php?value=".$cr_value."&hidevalue=1' />";
		 $img_barcode='<img src="'. DIR_WS_CATALOG.'barcode.php?value='.($cr_value.$extra).'" />';
				$merge_details['CUSTOMER']=$ordr_name;
				$merge_details['BORDER_SIZE']='3px';
				$merge_details['PRODUCT']=$db_record_product['products_name'];
				$merge_details['SHOW']=$heading_name;
				$merge_details['DATE']=$heading_date;
				$merge_details['TIME']=$heading_time;
				$merge_details['VENUE']=$heading_venue;
				$merge_details['DATEID']=$dateid;
				$merge_details['LOGO']=$img_logo;
                $merge_details['QUANTITY']=$quantity-1;
				$merge_details['BARCODE']=$img_barcode;
				$send_details[0]['to_name'] = $ordr_name;
				$send_details[0]['to_email'] = $db_record_order['customers_email_address'];
				$send_details[0]['from_name']=STORE_OWNER;
				$send_details[0]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
		############################################################
		//WE ARE DISCONNECTING THE TICKET TEMPLATE AND EMAIL
		############################################################
		//tep_send_default_email("TIX",$merge_details,$send_details);
		}// end if $xml -> status
	 }//end of if $xx loop
		
	}//end while
		
	}
}

}
?>
<?php
/*

  osConcert, Online Seat Booking 
  https://www.osconcert.com

  Copyright (c) 2009-2020 osConcert
  Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	require('includes/application_top.php');
	// include currencies class and create an instance

	require_once('includes/classes/currencies.php');
	$currencies = new currencies();
	
	require(DIR_WS_INCLUDES . '/tweak/general.php');
	/////frequire($FSESSION->language.'/products_ticket.php',RLANG);

	define('FPDF_FONTPATH','tfpdf/font/');
	require_once('tfpdf/tfpdf.php');
	tep_set_time_limit(100);
	
	//PlaceHolders
	//For Multi-language
	define("TICKET_TEXT_1","Text 1");
	define("TICKET_TEXT_2","Text 2");
	define("TICKET_TEXT_3","Text 3");
	define("TICKET_TEXT_4","Text 4");
	define("TICKET_TEXT_5","Text 5");
	define("TICKET_TEXT_6","Text 6");
	define("TICKET_TEXT_CONDITIONS","Text Conditions");
	define("TICKET_TEXT_7","Text 7");
	
	define("TEXT_CODE","Code");
	define("TEXT_PDA","Time Available");
	define("TEXT_PN","Products Name");
	define("TEXT_PDS","Products Description");
	define("TEXT_OID","Order ID");
	define("TEXT_CPM","Concert ID");
	define("TEXT_RI","Ref ID");
	define("TEXT_PI","Prd ID");
	define("TEXT_DS","Coupon");
	define("TEXT_GAT","GA ID");//GA run
	//concert headings
	define("TEXT_CHN","Concert Name");
	define("TEXT_CCV","Concert Venue");
	define("TEXT_CCD","Concert Date");
	define("TEXT_CCT","Concert Time");
	//add prices
	define("TEXT_CP1","Concert Price");
	define("TEXT_CP2","Symbol");
	define("TEXT_CDT","Discount Type");
	define("TEXT_CST","Season Ticket");
	define("TEXT_BN","Billing Name");
	define("TEXT_CUN","Customers Name");
	define("TEXT_CEA","Customers Email");
	//Customers Extra Info>New Field
	define("TEXT_CEI","New Field");
	//payment method
	define("TEXT_PAY","Payment");
	define("TEXT_PD","Payment Date");
	define("TEXT_DATE","Server Date");
	// Add Manufacturers Name
	define("TEXT_MN","Manu Name");
	//Spacing
	define("SPACE_25","SPACE 25");
	define("SPACE_20","SPACE 20");
	define("SPACE_15","SPACE 15");
	define("SPACE_10","SPACE 10");
	define("SPACE_5","SPACE 5");

	//This is some text when Box Office represents a billing name i.e Box Office for: Billing Name
	define("TEXT_FOR"," for: ");
	$for=TEXT_FOR;
	//Unique Number
	define("TEXT_BUN","Unique Number");
	
	$order_id=$FGET['oID'];
	$found_results=false;
	$unit=72/25.4;
	$order_ids='0';
	if ($order_id!="")
	{
		$order_id_splt=preg_split("/,/",$order_id);
		$order_id_splt=array_unique($order_id_splt);
		$order_ids="'" .join("','",$order_id_splt) . "'";
	}

	

	
	$pos_left=5;
	$pos_top=5;
	$pos_width=$ticket_width;
	$pos_height=$ticket_height;
	// create the content and positions to be drawn 
	$content=array();
	// get the ticket details to be shown from the orders tables
	$order_sql="SELECT distinct 
	c.customers_id,
	op.discount_id,
	op.products_tax,
	op.products_quantity,
	op.products_name,
	op.products_id,
	op.products_model,
	op.orders_products_id,
	op.products_price,
	op.final_price,
	op.events_type,
	op.discount_type,
	o.date_purchased,
	o.currency,
	op.discount_text,
	o.reference_id,
	op.categories_name,
	op.concert_venue,
	op.concert_date,
	op.concert_time,
	op.support_packs_type,
	op.products_sku,
	op.products_season,
	op.coupon_amount,
	op.products_date_available,
	o.orders_id,
	c.customers_firstname,
	c.customers_lastname,
	c.customers_groups_id,
	o.billing_name,
	o.payment_method,
	o.customers_name,
	o.customers_language
	from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_TO_CUSTOMERS ." c2c ".
				" where o.customers_id=c2c.group_customer_id and c2c.customer_id=c.customers_id and c2c.orders_id=o.orders_id and o.orders_id in(" . $order_ids . ") and o.orders_id=op.orders_id and op.is_printable = 1" . " order by c.customers_id,o.orders_id,op.products_name";

	$order_query=tep_db_query($order_sql);
		
	if (!(tep_db_num_rows($order_query)))
	{ // ??...and op.support_packs_type !='B'
	
		$order_sql="SELECT 
		c.customers_id,
		op.discount_id,
		op.products_tax,
		op.products_quantity,
		op.products_name,
		op.products_id,
		op.products_model,
		op.orders_products_id,
		op.products_price,
		op.final_price,
		op.events_type,
		op.discount_type,
		o.date_purchased,
		o.currency,
		o.currency_value,
		op.discount_text,
		o.reference_id,
		op.categories_name,
		op.concert_venue,
		op.concert_date,
		op.concert_time,
		op.support_packs_type,
		op.products_date_available,
		op.products_sku,
		op.products_season,
		op.coupon_amount,
		o.orders_id,
		c.customers_firstname,
		c.customers_lastname,
		c.customers_groups_id,
		o.billing_name,
		o.payment_method,
		o.customers_name,
		o.customers_language
		from " . 
					TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_CUSTOMERS . " c " .
					" where o.orders_id in(" . $order_ids . ") 
					  and o.orders_id=op.orders_id and o.customers_id=c.customers_id and op.is_printable = 1" .
					" order by o.orders_id,op.products_name";

		$order_query=tep_db_query($order_sql);
	
	}
	if (tep_db_num_rows($order_query))
	{ 
	$found_results=true;
	}
	
	$details=array();
	$tickets=array();
	$cnt=1;
	$row=0;
	$prev_order=0;
	$prev_event=0;
	$pre_cus_id=0;
	####################### start of the loop for each product in the order
	$running_number = 0;
	
	while($order_result=tep_db_fetch_array($order_query))
	{
		$product_id=$order_result["products_id"];
		$products_name=$order_result["products_name"];
		$orders_id=$order_result["orders_id"];
		$date_id=$order_result["products_model"];
		$orders_products_id=$order_result["orders_products_id"];
		$date_purchased=$order_result["date_purchased"];
		$events_type=$order_result["events_type"];
		$sku=$order_result["products_sku"];
		$customers_id=$order_result["customers_id"];
		$customers_group=$order_result["customers_groups_id"];
		$customers_name=$order_result["customers_name"];
		$customers_language=$order_result["customers_language"];
		$currency_value=$order_result["currency_value"];
		$discount_id=$order_result["discount_id"];
		$discount_text=$order_result["discount_text"];
		$products_tax=$order_result["products_tax"];
		$products_price=$order_result["products_price"];
		$final_price=$order_result["final_price"];
		$discount_type=$order_result["discount_type"];
		$reference_id=$order_result["reference_id"];
		$type=$order_result["support_packs_type"];
		$payment_method=$order_result["payment_method"];
		$products_quantity=$order_result["products_quantity"];
		$billing_name=$order_result["billing_name"];
		$products_season=$order_result["products_season"];
		$currency=$order_result["currency"];
		$currency_type=$order_result["currency_value"];
		$coupon_amount=$order_result["coupon_amount"];
		
		$check_query = tep_db_query("
			  SELECT customers_language 
			  FROM " . TABLE_ORDERS . " 
			  WHERE orders_id = '" . (int)$order_id . "'");
						  
		$check = tep_db_fetch_array($check_query); 
	
	$customers_language=$check['customers_language'];
	$check_language_query = tep_db_query("SELECT directory from languages WHERE languages_id = '" . (int)$customers_language . "'");
	$check_language = tep_db_fetch_array($check_language_query);
	$customers_language_directory=$check_language['directory'];
		
		
		
		//$customers_language=$order_result["customers_language"];
		
		
		
		include(DIR_WS_LANGUAGES . $customers_language_directory . '/products_ticket.php');
		//$discount_text=$order_result["discount_text"];
		
		//get an extra field
		//define("UNIQUE_NAME","dni");
		$cei=tep_db_query("SELECT * FROM  " . TABLE_CUSTOMERS_EXTRA_INFO . "  WHERE customers_id = '". $customers_id ."'"); 
		//and uniquename='" . UNIQUE_NAME . "'");
		// and uniquename=UNIQUE_NAME;
		$ce=tep_db_fetch_array($cei);
		$new_field=$ce['fieldvalue'];
		
		if(is_numeric($q['products_season']))
		{
		$season_quantity = (int)$q['products_season'];
		}
		else
		{
		$season_quantity = 0;
		}
		
		//print_r($q);
		
		if($events_type=='F')
		{
		$ticket_type="FAMILY TICKET";	
		}
		
		if ($prev_order!=$orders_id)
		{
			$cnt=1;
			if ($prev_order>0) $row=$row+1;
			$prev_order=$orders_id;
			$prev_event=0;
		}
		if($pre_cus_id!=$order_result["customers_id"])
		{
		}
		if ($prev_event!=$product_id)
		{
			if (!isset($details[$product_id]))
			{
				//Anything from the PRODUCTS tables? product query?
				$event_query=tep_db_query("SELECT p.products_model,p.color_code,pd.products_description,p.products_tax_class_id,p.manufacturers_id,pd.products_name,p.products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id=pd.products_id  where p.products_id='" . $product_id . "' and pd.language_id='" . (int)$FSESSION->languages_id . "'"
												);
				$event_products=tep_db_fetch_array($event_query);	
				$manufacturers_id=$event_products["manufacturers_id"];
				$color_code=$event_products["color_code"];
				$products_description=$event_products["products_description"];
				//OPTIONAL
				// if($color_code=='thistle'){
					// $code='VVIP';
				// }
				
				$order_total_query=tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $order_id . "' and class='ot_bofr'");
				$order_total_result=tep_db_fetch_array($order_total_query);
				$order_total_amount=substr($order_total_result['value'],0, -2);
				$order_total_title=$order_total_result['title'];
				
				if($coupon_amount==$final_price){
					$coupon_txt = COUPON_DISCOUNT;
				}

				//get manufacturers details
				$manufacturers_query = tep_db_query("select manufacturers_name,manufacturers_image from ". TABLE_MANUFACTURERS." where manufacturers_id = '".$manufacturers_id."'");
				$manufacturers_result=tep_db_fetch_array($manufacturers_query);
				$manufacturers_name=$manufacturers_result["manufacturers_name"];
				$manufacturers_image=$manufacturers_result["manufacturers_image"];
				
				$server_date = date('Y-m-d',getServerDate(false));
				//date('Y-m-d H:i:s',getServerDate(false))

				$heading_name=$order_result["categories_name"];
				$heading_venue=$order_result["concert_venue"];
				$heading_date=$order_result["concert_date"];
				
				if($heading_date!='')
				{
				//$date = strtotime($heading_date);
				//$thedate = date(EVENTS_DATE_FORMAT, $date);
				$thedate = $heading_date;
				}
				if($thedate=='01-01-1970')
				{
				$thedate = $heading_date;
				}
				if($heading_date=='')
				{
				$thedate='';	
				}
				
				$heading_time=$order_result["concert_time"];
				
				$pda = strtotime($order_result["products_date_available"]);
				//$date_available=date('F j, Y, g:i a', $pda);
				$date_available=date('F j g:i a', $pda);
				//$date_available=$order_result["products_date_available"];
				
				$details[$product_id][TEXT_CODE]=$code;
				$details[$product_id][TEXT_PDA]=$date_available;
				$details[$product_id][TEXT_CHN]=$heading_name;
				$details[$product_id][TEXT_CCV]=$heading_venue;
				$details[$product_id][TEXT_CCD]=$thedate;
				$details[$product_id][TEXT_CCT]=$heading_time;
				$details[$product_id][TEXT_CPM]=$date_id;
				$details[$product_id][TEXT_PDS]=$products_description;
				if($type=='F')
				{
				//$details[$product_id][TEXT_PN]=$products_name.' x '.FAMILY_TICKET_QTY;
				$details[$product_id][TEXT_PN]=$products_name.' '. $ticket_type;
				}else
				{
				$details[$product_id][TEXT_PN]=$products_name;
				}
				$details[$product_id][TEXT_OID]=$orders_id;
				$details[$product_id][TEXT_PD]=$date_purchased;
				##################################################
				
				//if SALEMAKER DISCOUNT is used -note eTicket
				$sms=tep_db_query("SELECT * FROM  " . TABLE_SALEMAKER_SALES . "  WHERE sale_id = '". $discount_id ."'");
				$sm=tep_db_fetch_array($sms);
				
				$choice_text=$sm['choice_text'];
				
				//if SPECIALS DISCOUNT is used -note eTicket
				if ( $discount_type == 'S')
				{
				$specials = '+SPECIAL PRICE';
				}elseif ( $discount_type == 'C')
				{
				$specials = $discount_text;
				}
				##################################################
				$currency_sym = $currencies->format($order->info['currency']); 
				$currency_sym = preg_replace( '([0-9Â.]*)', '', $currency_sym);
			//	$currency_sym = '€';
				$discount = 0;
				$tax = 0;
				$special = '';
				$shown_price = 0;
				if($order_result['products_price'] != $order_result['final_price'])
				{//discount
					$special = $order_result['products_price'] - $order_result['final_price'];//net discount
				}

				if ($products_tax > 0)
				{
					$shown_price = tep_add_tax($order_result['final_price'], $products_tax);
					$tax = (($order_result['final_price'] * $products_tax)/100);
					$plustax = TEXT_TAX_PLUS . " " .$tax;
				    $special .= $plustax;
				}else
				{
					$shown_price = substr($order_result['final_price'],0,-2);
				}

				if (SHOW_TICKET_DISCOUNT=='true')
				{
					if (($special !='')&&($discount_text==''))
					{
						$special = '(' . TEXT_TICKET_DISCOUNT . $special.')';
					}
					else
					{
						$special=$discount_text.$order_total_title;
					}
					
					if($shown_price==$coupon_amount)
					{
						$shown_price=0;
					}
					
					
				}else
				{
					$special='';
				}

				$price=$shown_price;
				
				if($currencies->currencies[$FSESSION->currency]['symbol_right'] !='')
				{				
					$details[$product_id][TEXT_CP1]=$price.$currency_sym;
				}
				else
				{
					
					//$currency_sym = '' . LEFT_SYMBOL . '';
					//$details[$product_id][TEXT_CP2]=$currency_sym;
					//$details[$product_id][TEXT_CP1]=$price;
					
					$details[$product_id][TEXT_CP1]=$currency_sym.$price;
				}				
					
				$details[$product_id][TEXT_CDT]= $special;
				############################################################
			}
			

				
			$cnt=1;
			if ($prev_event>0) $row=$row+1;
			$prev_event=$product_id;
		}
		if ($cnt==1) 
		{
			for ($i = 0; $i != $products_quantity; $i++) 
			{
							$running_number = $running_number + 1;
							$q = $i+1;
							$l="(";
							if($type=='F')
							{
							$r=" of " . FAMILY_TICKET_QTY . ")";
							}else
							{
							$r=")";
							}
				
							// season ticket shows here
							$season_text = '';
							if ($season_quantity > 0)
							{
								  $season_text = SEASON_TICKET_PURCHASE;
								  $season_quantity = $season_quantity - 1;
							}
							
			
				
				$details[$product_id][TEXT_CST]= $season_text;
				
				$tickets[$row]["products_id"]=$product_id;
				$tickets[$row]["customers_id"]=$order_result["customers_id"];
				$tickets[$row]["orders_id"]=$orders_id;
				$tickets[$row]["customers_firstname"]=$order_result["customers_firstname"];
				$tickets[$row]["customers_lastname"]=$order_result["customers_lastname"];
				$tickets[$row]["customers_name"]=$order_result["customers_name"];
				$tickets[$row]["customers_groups_id"]=$order_result["customers_groups_id"];
				$tickets[$row]["run"]=$q;
				$tickets[$row]["reference_id"]=$order_result["reference_id"];
				$tickets[$row]["products_model"]=$order_result["products_model"];
				$tickets[$row]["products_name"]=$order_result["products_name"];
				$tickets[$row]["concert_venue"]=$order_result["concert_venue"];
				$tickets[$row]["concert_date"]=$order_result["concert_date"];
				$tickets[$row]["concert_time"]=$order_result["concert_time"];
				$tickets[$row]["products_date_available"]=$order_result["products_date_available"];
				$tickets[$row]["products_name"]=$order_result["products_name"];
				$tickets[$row]["customers_id"]=$order_result["customers_id"];
				$tickets[$row]["events_type"]=$order_result["events_type"];

				############################## unique id
				if (function_exists(tep_create_unique_id))
				{
				// order_id/order_products_id/quantity
					$tickets[$row]["unique_number"] = tep_create_unique_id( $orders_id, $product_id, $q );
				//ATTN
				//	$tickets[$row]["unique_number"] = tep_create_unique_id( $orders_id, $product_id, $running_number );
				}
				#############################  unique_id ends
				
				//Give the GA tickets a run number
				
				if ($products_quantity == 1)
				{
				$tickets[$row]["run"]='_1';
				}else
				{
				$tickets[$row]["run"]='_'.$q;
			   //Want an underscore or NOT?
			   //$tickets[$row]["run"]=$q;
				}
				
				$tickets[$row]["billing_name"]=$billing_name;
				$tickets[$row]["payment_method"]=$payment_method;
				//manufacturers name
				$tickets[$row]["manufacturers_name"]=$manufacturers_name;
				if ($i != $products_quantity) { $row++; }
			}
		}	
		$cnt=$cnt+1;
		if ($cnt>9)
		{
			$row=$row+1;
			$cnt=1;
		}
	}//end order query
	
	################################################################################################
	
	if(!defined('TICKET_TEMPLATE'))
	{
	define('TICKET_TEMPLATE', '1');
	}
	//This for the Front End ONLY!
	// $customer_group=tep_get_customers_groups_id();
	// if ($customer_group==1){
		// $template_id=1;
	// }elseif ($customer_group==2){
		// $template_id=2;
	// }elseif ($customer_group==3){
		// $template_id=3;
	// }elseif ($customer_group==4){
		// $template_id=4;
	// }else{
		// //$template_id=1;
		// if ($sku==6){
		// $template_id=2;
		// }else{	
		// $template_id=1;
		// }
	// }
//	if($products_season!='')
//	{
//	//use template 2
//	$template_id=3;
//	}
//	else{
//	$template_id=1;	
//	}

	
	$template_id=TICKET_TEMPLATE;
	// get the template details if not found use default template
	$template_query=tep_db_query("SELECT template_id,template_width,template_height,template_type,template_content from " . TABLE_GENERAL_TEMPLATES . " where template_id='" . $template_id . "'");	
	//get the ticket type
	if (tep_db_num_rows($template_query)>0) 
	{
		$template_result=tep_db_fetch_array($template_query);
		//$tic_type=$template_result['template_type'];
	}else 
	{
		$template_result=getDefaultTemplate();
	}
	//get the content
	$template_splt=preg_split("/{}/",$template_result["template_content"]);
	for ($icnt=0;$icnt<count($template_splt);$icnt=$icnt+2)
	{
		$key=$template_splt[$icnt];
		$template[$key]=$template_splt[$icnt+1];
	}
	//get the dimensions
	$ticket_width=$template_result["template_width"]*10;
	$ticket_height=$template_result["template_height"]*10;
	//get the format
	if(PAGE_FORMAT=="custom")
	{
	$resolution= array($ticket_width, $ticket_height);
	}else
	{
	$resolution	=PAGE_FORMAT;
	}
	
	// set page setup default width and height
	//$pdf=new tFPDF("l","mm",array($ticket_width,$ticket_height));
	$pdf = new tFPDF(ORIENTATION, "mm", $resolution, true, 'UTF-8', false);
	
	$pos_left=5;
	$pos_top=5;
	$pos_width=$ticket_width;
	$pos_height=$ticket_height;
	##########################################################################################

	if ($found_results)
	{
		//now do barcode stuffs
		require_once(DIR_FS_CATALOG_MODULES . "barcode/image.php");
		$bar_width=15;
		
		// print the ticket details
		$tickets = array_values($tickets);
		for ($tcnt=0;$tcnt<count($tickets);$tcnt++)
		{
			$ticket=$tickets[$tcnt];
			$event=$details[$ticket["products_id"]];
			$pdf->AddPage();
			$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
			$pdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
			$pdf->SetFont('DejaVu','B',15); //13
			$pos_top=8; //8 from the top
			$pos_left=5; // no adjustment??

			$event[TEXT_FN]=$ticket["customers_firstname"];
			$event[TEXT_LN]=$ticket["customers_lastname"];
			$event[TEXT_CUN]=$ticket["customers_name"];
			$event[TEXT_CEA]=$ticket["customers_email_address"];
			$event[TEXT_RI]=$ticket["reference_id"];
			$event[TEXT_PI]=$ticket["products_id"];	
			$event[TEXT_PDA]=$date_available;
			if (($_SESSION['BoxOffice']== 999)or($_SESSION['customer_country_id']==999))
			{
			$event[TEXT_BN]=$for .$ticket["billing_name"];
			}else
			{
			$event[TEXT_BN]=$ticket["billing_name"];	
			}
			$event[TEXT_PAY]=$ticket["payment_method"];
			$event[TEXT_CEI]=$ticket["fieldvalue"];
			$event[TEXT_GAT]=$ticket["run"];
			$event[TEXT_CPM]=$ticket["products_model"];
			//add manufacturers name
			$event[TEXT_MN]=$ticket["manufacturers_name"];
			$event[TEXT_DS]=$coupon_txt;
			$event[TEXT_DATE]=$server_date;
			$event[SPACE_25]="                         ";
			$event[SPACE_20]="                    ";
			$event[SPACE_15]="               ";
			$event[SPACE_10]="          ";
			$event[SPACE_5]="     ";
			$event[TEXT_BUN]=$ticket["unique_number"];
			//For Multi-language
			if($ticket["events_type"]=='B'){
			$event[TICKET_TEXT_1]=ADD_1;
			$event[TICKET_TEXT_2]=ADD_2;
			$event[TICKET_TEXT_3]=ADD_3;
			}else{
			$event[TICKET_TEXT_1]=TEXT_1;
			$event[TICKET_TEXT_2]=TEXT_2;
			$event[TICKET_TEXT_3]=TEXT_3;
			}
			
			$event[TICKET_TEXT_4]=TEXT_4;
			$event[TICKET_TEXT_5]=TEXT_5;
			$event[TICKET_TEXT_6]=TEXT_6;
			$event[TICKET_TEXT_CONDITIONS]=TEXT_CONDITIONS;
			$event[TICKET_TEXT_7]=TEXT_7;
			
				#############################################################
				//Ticket Image for each Category
				#############################################################
				$get_cat_id=tep_db_query("select categories_id from ". TABLE_PRODUCTS_TO_CATEGORIES ." where products_id='".$ticket["products_id"]."'");
				$got_id =tep_db_fetch_array($get_cat_id);
				$cat_id=$got_id['categories_id'];
				//got category id
				//We get the parent id where the category id is the same as the p2c category id
				$get_parent_id = tep_db_query("select * from ". TABLE_CATEGORIES ." where categories_id = '" . (int)$cat_id . "'");
				$got_parent_id =tep_db_fetch_array($get_parent_id);
				$parent_id=$got_parent_id['parent_id'];
				$cat_ticket_image=$got_parent_id['categories_image_2'];
				$get_parent_cat_id = tep_db_query("select * from ". TABLE_CATEGORIES ." where categories_id = '" . (int)$parent_id . "'");
				$got_parent_cat_id=tep_db_fetch_array($get_parent_cat_id);
				$parent_cat_id=$got_parent_cat_id['parent_id'];
				$parent_ticket_image=$got_parent_cat_id['categories_image_2'];
				
				$get_parent_parent_cat_id = tep_db_query("select * from ". TABLE_CATEGORIES ." where categories_id = '" . (int)$parent_cat_id . "'");
				$got_parent_parent_cat_id=tep_db_fetch_array($get_parent_parent_cat_id);
				$parent_parent_cat_id=$got_parent_parent_cat_id['parent_id'];
				$parent_parent_ticket_image=$got_parent_parent_cat_id['categories_image_2'];
	
				if ($cat_ticket_image!='')
				{
				$ticket_image=$cat_ticket_image;
				}
				elseif ($parent_ticket_image!='')
				{
				$ticket_image=$parent_ticket_image;
				}
				elseif ($parent_parent_ticket_image!='')
				{
				$ticket_image=$parent_parent_ticket_image;
				}
				
				###############################################################

				$tInfo=new objectInfo($template);
				
				//if($ticket_image !=''){
				if(tep_not_null($ticket_image))
				{
				$tInfo->shop_logo_image=DIR_FS_CATALOG_IMAGES . $ticket_image;
				}else
				{
				$tInfo->shop_logo_image=DIR_FS_CATALOG_IMAGES . $tInfo->shop_logo_image;
				}
				
				$tInfo->sponsor_logo_image=DIR_FS_CATALOG_IMAGES . $tInfo->sponsor_logo_image;

				// get info of image
				if (file_exists($tInfo->shop_logo_image))
				{
					//$unit=72/25.4;
					//850px x 330px
					//29.98724cm
					//180,70
					
					$info_shop=getimagesize($tInfo->shop_logo_image);
					$info_shop[0]=($info_shop[0]/$unit)*74/100;
					$info_shop[1]=($info_shop[1]/$unit)*74/100;
				}
				if (file_exists($tInfo->sponsor_logo_image))
				{
					$info_sponsor=getimagesize($tInfo->sponsor_logo_image);
					$info_sponsor[0]=($info_sponsor[0]/$unit)*114/100;
					$info_sponsor[1]=($info_sponsor[1]/$unit)*114/100;
				}
				// shop logo
				if (isset($info_shop)){
					if ($tInfo->shop_logo_position=="L")
					{
					$pdf->Image($tInfo->shop_logo_image,($tInfo->bar_image_position=="L"?$pos_left:BG_IMAGE_LEFT),BG_IMAGE_TOP,BG_IMAGE_WIDTH,BG_IMAGE_HEIGHT); //L,T,W,H//
					}else
					{
					$pdf->Image($tInfo->shop_logo_image,($tInfo->bar_image_position=="L"?$pos_width-$info_shop[0]:$pos_width-$info_shop[0]-2),BG_IMAGE_TOP,BG_IMAGE_WIDTH,BG_IMAGE_HEIGHT);
					}
			}
			###############################################################	
					
			// create bar image
			if (BARCODE !="none")
			{
					//128 OUT OF ORDER
					// if (BARCODE =="128")
					// {
						// $bar_text=$ticket["orders_id"] . "_" . $ticket["products_id"] . $ticket["run"];
						
						// //Use more INFO
						// //$bar_text=$ticket["unique_number"] . SEP . $ticket["orders_id"] . SEP . $ticket["products_id"] . $ticket["run"] . SEP . $ticket["concert_venue"] . SEP . $ticket["concert_date"] . SEP . $ticket["concert_time"] . SEP . $ticket["products_model"]. SEP . $ticket["products_name"] . SEP;
						
						// //create a shorter version for the barcode image file name
						// $bar_text_short=$ticket["orders_id"] . '_' . $ticket["products_id"] . $ticket["run"];
						// $bar_filename=DIR_WS_IMAGES . "tickets/ticket_bar_". $bar_text_short . ".png";
								
							// if (!file_exists($bar_filename)){
								// create_bar_image($bar_text,$bar_filename);
							// }
							// // get info
							// if (file_exists($bar_filename))
							// {
								// $info_bar=getimagesize($bar_filename);
								// $info_bar[0]=$info_bar[0]/$unit*(74/100);
								// $info_bar[1]=$info_bar[1]/$unit*(74/100);
								// $bar_width=$info_bar[0];
							// }
							 // //adjust for the bar image			
							// if ($tInfo->bar_image_position=="L")
							// {
								// $pos_left=$bar_width+2;
							// }else
							// {
								// $pos_width=$ticket_width-$bar_width-2;
							// }
							// $content_width=$ticket_width-$bar_width-2;
							
							// //bar image
							// if (isset($info_bar))
							// {
								// if ($tInfo->bar_image_position=="L")
								// {
									// $pdf->Image($bar_filename,2,2);
								// }else
								// {
									// $pdf->Image($bar_filename,$ticket_width-$bar_width-1,1, $bar_width); // position bar image horizontal-vertical
								// }
							// }
					// }//end 128
							
					##############################################################
					if (BARCODE == "QR") 
					{
						//qrcode
						require_once('tfpdf/qrcode/qrlib.php');
						$qr_width=QR_WIDTH;

						$qr_text=$ticket["orders_id"] . '_' . $ticket["products_id"] . $ticket["run"];
						
						//Use More INFO
						//$qr_text=$ticket["unique_number"] . SEP . $ticket["orders_id"] . SEP . $ticket["products_id"] . $ticket["run"] . SEP . $ticket["concert_venue"] . SEP . $ticket["concert_date"] . SEP . $ticket["concert_time"] . SEP . $ticket["products_model"]. SEP . $ticket["products_name"] . SEP;
						
						if(BARCODE_SCAN=='yes')
						{
							
							tep_db_query("CREATE TABLE IF NOT EXISTS `orders_barcode` (
										  `barcode_id` bigint(255) NOT NULL AUTO_INCREMENT,
										  `orders_id` int(255) NOT NULL DEFAULT '0',
										  `products_id` bigint(255) NOT NULL DEFAULT '0',
										  `showtime` varchar(255) NOT NULL,
										  `products_name` varchar(255) NOT NULL,
										  `barcode` varchar(255) NOT NULL DEFAULT '0',
										  `created` int(50) NOT NULL DEFAULT '0',
										  `scanned` int(1) NOT NULL DEFAULT '0',
										  `scanned_date` int(50) NOT NULL DEFAULT '0',
										  `location` varchar(255) NOT NULL DEFAULT '',
										  `data` text NOT NULL,
										  PRIMARY KEY (`barcode_id`),
										  UNIQUE KEY `barcode_id` (`barcode_id`),
										  KEY `barcode_id_2` (`barcode_id`)
										)   AUTO_INCREMENT=1 ;");

							$products_id = (int)$ticket["products_id"];
							$showtime = $ticket["concert_date"].' '.$ticket["concert_time"];
							$products_name = $ticket["products_name"];
							$orders_id = (int)$ticket["orders_id"];
							//new for scan
							$order_data_query = tep_db_query("
							SELECT
								op.categories_name AS categories_name,
								op.concert_venue AS concert_venue
							FROM 
								" . TABLE_ORDERS_PRODUCTS . " op
							WHERE 
								op.orders_id = '". $orders_id . "'");
								
							$order_data = tep_db_fetch_array($order_data_query);

							$product_data_query=tep_db_query("SELECT
								op.products_model,
								op.products_name,
								op.products_price 
							from " . TABLE_ORDERS_PRODUCTS . " op where op.products_id='" . (int)$ticket["products_id"] . "'"
														);
							$product_data = tep_db_fetch_array($product_data_query);
								
							//save barcode
							$orders_barcode_query = tep_db_query("SELECT `barcode_id` FROM `orders_barcode` WHERE `orders_id` = '".$orders_id."' AND `products_id` = '".(int)$products_id."' AND `barcode` = '".tep_db_input($qr_text)."'");
							$orders_barcode = tep_db_fetch_array($orders_barcode_query);
							
							$JSON_BARCODE = json_encode(array_merge($ticket, $order_data, $product_data));
							$JSON_BARCODE = strtr($JSON_BARCODE, array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
							$JSON_BARCODE = addslashes(stripslashes($JSON_BARCODE));
							
							if(NULL !== $orders_barcode && false !== $orders_barcode)
							{
								tep_db_query(sprintf("UPDATE `orders_barcode` SET `orders_id`='%d', `products_id`='%d',`showtime`='%s', `products_name`='%s',`barcode`='%s', `created`='%d', `data` = '%s' WHERE `barcode_id` = '%d'",
									(int)$orders_id,
									(int)$products_id,
									$showtime,
									$products_name,
									tep_db_input($qr_text),
									time(),
									$JSON_BARCODE,
									//json_encode(array_merge($ticket)),
									(int)$orders_barcode['barcode_id']
								));
							}
							else
							{
								tep_db_query(sprintf("INSERT INTO `orders_barcode`(`orders_id`, `products_id`,  `showtime`,`products_name`,`barcode`, `created`, `data`) VALUES ('%d','%d', '%s','%s', '%s', '%d', '%s');",
									(int)$orders_id,
									(int)$products_id,
									$showtime,
									$products_name,
									tep_db_input($qr_text),
									time(),
									$JSON_BARCODE
								));
							}

						}
						
						$qr_filename=DIR_FS_CATALOG_IMAGES . "tickets/ticket_qr_". $qr_text . ".png";
						QRcode::png($qr_text, $qr_filename,"Q",4,4); 
						
						//$pdf->Image($qr_filename,$ticket_width-$qr_width-1,1, $qr_width); //file,x,y,width,height
						$pdf->Image($qr_filename,QR_LEFT_POSITION,QR_TOP_POSITION, $qr_width); //file,x,y,width,height
					}// end QR
			}
			##############################################################
				
				$top_font_size=TOP_FONT_SIZE;
				$pdf->SetFont('DejaVu','B',$top_font_size); //sets the font for TOP line (20)
				
				//event details MIDDLE
				$content=$tInfo->event_details_content;
				reset($event);

				//while(list($key1,$value1)=each($event))
				foreach($event as $key1 => $value1) 	
				{
					//$placeholder;
					$content=preg_replace("/%%" . $key1  . "%%/i",$value1,$content);
					$content=preg_replace("/##" . $key1  . "##/i",$value1,$content);
				}
		
				$splt_line=preg_split("/\n/",$content);
				$temp_width=0;
				for ($icnt=0;$icnt<count($splt_line);$icnt++)
				{
					$str_width=$pdf->GetStringWidth($splt_line[$icnt]);
					if ($str_width>$temp_width)	$temp_width=$str_width;
				}
		
				if ($tInfo->event_details_position=="L")
				{
					$temp_left=$pos_left;
				}else 
				{
					$temp_left=$pos_width+20-$temp_width; //text from left margin when its set for position 'R'
				}
				$mid_font_size=MID_FONT_SIZE;
				for ($icnt=0;$icnt<count($splt_line);$icnt++)
				{
					if ($icnt>=1) $pdf->SetFont('DejaVu','B',$mid_font_size); //size of MIDDLE written text (12)
					//$pdf->Text($temp_left,$pos_top,$splt_line[$icnt]);
					$pdf->Text($temp_left+TEXT_LEFT_POSITION,$pos_top+TEXT_TOP_POSITION,$splt_line[$icnt]); //manipulate the positioning of all the text
					
					if($tic_type=="TIC2")
					{
					//Make a Double Ticket
					//$icnt>=1=The text will be the MIDDLE so we can hide the TOP line otherwise remove it if ($icnt>=1)
					//UNCOMMENT BELOW TO MAKE A DOUBLE SIDED TICKET
					$tear=TEAR_LINE;
					if ($icnt>=1) $pdf->Text($temp_left+$tear,$pos_top,$splt_line[$icnt]);
					$pdf->Text($temp_left+$tear,$pos_top,$splt_line[$icnt]);
					}
					
					$pos_top+=MID_TEXT_SPACING; //spacing of the MIDDLE text 4
				}
				$pos_top+=8;
		
				// conditions BOTTOM
				$pdf->SetFont('DejaVu','B',BOTTOM_FONT_SIZE);
			
				$pos_top+=10;

				if (!isset($cond_splt))
				{
					$cond_height=0;
					$cond_splt=preg_split("/\n/",$tInfo->event_condition_content);
					$cond_height=count($cond_splt)*3;
					$cond_width=0;
					$temp_width=0;
					for ($icnt=0;$icnt<count($cond_splt);$icnt++)
					{
						$temp_width=$pdf->GetStringWidth($cond_splt[$icnt]);
						if ($temp_width>$cond_width) $cond_width=$temp_width;
					}
				}
				
				$temp_top=$ticket_height-$cond_height+1;
				if ($tInfo->event_condition_position=="L")
				{
					$cond_left=$temp_left; //position of BOTTOM text from left margin
				}else 
				{
					$cond_left=$ticket_width-$cond_width;
				}
				
				//event condition
				$content=$tInfo->event_condition_content;
				reset($event);
				//while(list($key1,$value1)=each($event))
				foreach($event as $key1 => $value1)
				{
					$content=preg_replace("/%%" . $key1  . "%%/i",$value1,$content);
					$content=preg_replace("/##" . $key1  . "##/i",$value1,$content);
				}
		
				$splt_line=preg_split("/\n/",$content);
				$temp_width=0;
				for ($icnt=0;$icnt<count($splt_line);$icnt++)
				{
					$pdf->Text($cond_left,$temp_top,$splt_line[$icnt]);  
				}
		}//end  print the ticket details
	}else
	{
		$pdf->AddPage();
		$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
		$pdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
		$pdf->SetFont('DejaVu','B',15); //13
		$pdf->Text(5,5,'No Details Found or this product is NOT printable');
	}
	$events_tickets=sprintf("events_tickets_%s_%s",$login_id,time());
	
	//alternatives
	//$events_tickets=sprintf("events_tickets_%s%s_%s",$order_id,$login_id,time());
	//$events_tickets=sprintf("events_tickets_%s_%s%s_%s",$customers_name,$order_id,$login_id,time());
	//default
	$pdf->output(DIR_FS_CATALOG . "images/tickets/".$events_tickets.".pdf");
	tep_db_query("update  " . TABLE_ORDERS . "  set ticket_printed='Y' where orders_id in(" . $order_ids . ")");
	tep_redirect(DIR_WS_CATALOG . "images/tickets/".$events_tickets.".pdf");
	function &getDefaultTemplate()
	{
		$template_result=array(	"template_width"=>18.0000,
								"template_height"=>7.0000,
								"template_content"=>
								"shop_logo_image{}ticket.png{}shop_logo_position{}L{}event_details_content{}%%Concert Name%%\n%%Concert Venue%%\n%%Concert Date%% - %%Concert Time%% \nSeat name:%%Products Name%% Price: %%Symbol%%%%Concert Price%% %%Discount Type%% \n%%Coupon%%\n%%First_Name%% %%Last_Name%%\nTicket ref: %%Ref ID%%%%Prd ID%% \n{}event_details_position{}L{}sponsor_logo_image{}sponsor_logo.jpg{}sponsor_logo_position{}R{}event_condition_content{}Refundable only if event is cancelled{}event_condition_position{}L{}bar_image_position{}R'"
								);
		return $template_result;
	}
?>
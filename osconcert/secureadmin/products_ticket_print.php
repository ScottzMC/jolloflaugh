<?php
/*

  osConcert, Online Seat Booking 
  https://www.osconcert.com
  Copyright (c) 2009-2019 osConcert
  Released under the GNU General Public License
 * This file originates in the customer facing ticket printing
 * it may need a template tweak around line 68
 * will dump a series of tickets into the folder /admin/tickets/xxxx
 * based on a category id
 * will recurse through lower categories
 * one ticket per pdf
*/

// Set flag that this is a parent file
	define( '_FEXEC', 1 );
        
	require('includes/application_top.php');
	$tickets_printed=false;
           // error_reporting(E_ALL);
           // ini_set('display_errors', '1'); 
            
	if($_REQUEST['cat_id'] && $_REQUEST['cat_id']!=0)
	{
			 
					 //check for flush
					if($_REQUEST['flush'] && $_REQUEST['flush'] == 'Yes')
					{ 
					 
							$dir = 'tickets/output/';
							$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
							$files = new RecursiveIteratorIterator($it,
							RecursiveIteratorIterator::CHILD_FIRST);
						foreach($files as $file) 
						{
							if ($file->isDir()){
							rmdir($file->getRealPath());
							} else {
							unlink($file->getRealPath());
							}
						}
					//allow sleep as on some servers trying to create a pdf seems to fail
					sleep(5);
					set_time_limit(60);
					}
					
					define('FPDF_FONTPATH','tfpdf/font/');
	require_once('tfpdf/tfpdf.php');
					
	//cartzone for the products tickets
	//For Multi-language
	define("TICKET_TEXT_1","Text 1");
	define("TICKET_TEXT_2","Text 2");
	define("TICKET_TEXT_3","Text 3");
	define("TICKET_TEXT_4","Text 4");
	define("TICKET_TEXT_5","Text 5");
	define("TICKET_TEXT_6","Text 6");
	define("TICKET_TEXT_CONDITIONS","Text Conditions");
	define("TICKET_TEXT_7","Text 7");
	if(!defined("TEXT_PN"))
	define("TEXT_PN","Products Name");

	define("TEXT_CODE","Code");
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
					$unit=72/25.4;

			################################################################################################
				#  change the TICKET_TEMPLATE if you want to use anything other than 1
				################################################################################################
					if(!defined('TICKET_TEMPLATE')){
					define('TICKET_TEMPLATE', '1');
					}	
				$template_id=TICKET_TEMPLATE;
				// get the template details if not found use default template
				$template_query=tep_db_query("SELECT template_id,template_width,template_height,template_content from " . TABLE_GENERAL_TEMPLATES . " where template_type='TIC' and template_id='" . $template_id . "'");
			
						if (tep_db_num_rows($template_query)>0) 
						{
							$template_result=tep_db_fetch_array($template_query);
						} else 
						{
							$template_result=getDefaultTemplate();
						}

				$template_splt=preg_split("/{}/",$template_result["template_content"]);
				for ($icnt=0;$icnt<count($template_splt);$icnt=$icnt+2)
				{
					$key=$template_splt[$icnt];
					$template[$key]=$template_splt[$icnt+1];
				}

				$ticket_width=$template_result["template_width"]*10;
				$ticket_height=$template_result["template_height"]*10;
			
			// set page setup default width and height
				class pdf extends tFPDF
				{
					function __construct($orientation, $units, $size)
					{
					parent::tFPDF(ORIENTATION, $units, $size);
					}
				}
			
				//$pdf=new pdf("l","mm",array($ticket_width,$ticket_height));
				//$pdf=new tFPDF("l","mm",array($ticket_width,$ticket_height));
				
				
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
				// create the content and positions to be drawn 
					############################################################################################
					# end template 
					############################################################################################
					//test here for the category POST
			   
				//check for sub cats
				 $cat_array=(create_cat_array($_REQUEST['cat_id']));
				 //add parent cat if not zero
				 if($_REQUEST['cat_id']!=0)
				 {
				 $cat_array[]=$_REQUEST['cat_id'];
				 }
				 
				// exit(var_dump($cat_array));
				//for each category run this
			foreach($cat_array as $key=>$value)
			{
					$content=array();
					// get the ticket details to be shown for each category
					$order_query=tep_db_query("SELECT 
					pd.products_name, 
					p.color_code,
					p.product_type, 
					p.products_id, 
					p.products_price,
					p.products_model, 
					p.products_quantity,
					p.master_quantity, 
					p.products_ordered 
					FROM  products p,  products_to_categories p2c, products_description pd WHERE p2c.categories_id='".$value."' and p.products_id=p2c.products_id and pd.language_id=1 and pd.products_id=p2c.products_id order by p.products_id ");
				
						//  if(tep_db_num_rows($order_query<1))break;
						//$order_result=tep_db_fetch_array($order_query);								
						//exit(print_r($order_result));

				//product loop
				while($order_result=tep_db_fetch_array($order_query))

				{
					//individual tickets 
						
					set_time_limit(60);
					$details=array();
					$tickets=array();
					$cnt=1;
					$row=0;
					$prev_order=0;
					$prev_event=0;
					$pre_cus_id=0;
					$heading_name='';
					$heading_venue='';
					$heading_date='';
					$heading_time='';
					$discount_type='';   
					$product_id=$id=$order_result["products_id"];
					$orders_id='';
					$date_id=$order_result["products_model"];
					$orders_products_id='';
					//adjust quantity in case some already sold	
					if(TICKET_MASTER_QUANTITY=='true'){	
					$quantity = (int)$order_result['master_quantity'];
					}else{
					$quantity = (int)$order_result['products_quantity']+(int)$order_result['products_ordered'];
					}
		
			
				{

						{
						require_once('includes/functions/categories_lookup.php');
						
						$type = $products[$i]['product_type'];
        
						list($heading_name, $heading_venue,  $heading_date, $heading_time) = categories_lookup();
				
						######################################################################################
				
						$details[$product_id][TEXT_CODE]=$code;
						$details[$product_id][TEXT_CHN]=$heading_name;
						$details[$product_id][TEXT_CCV]=$heading_venue;
						$details[$product_id][TEXT_CCD]=$heading_date;
						$details[$product_id][TEXT_CCT]=$heading_time;
						$details[$product_id][TEXT_CPM]=$date_id;
						$details[$product_id][TEXT_PDS]=$products_description;
						$details[$product_id][TEXT_CDT]= '';
						
								
						$details[$product_id][TEXT_OID]=$orders_id;
						if($type=='F')
						{
						$details[$product_id][TEXT_PN]=$products_name.' x '.FAMILY_TICKET_QTY;
						}else
						{
						$details[$product_id][TEXT_PN]=$products_name;
						}
						$details[$product_id][TEXT_PD]=$date_purchased;
						
						##################################################
				require_once('includes/classes/currencies.php');
					$currencies = new currencies();

				
						##################################################
						
						
						
						 $price = substr($order_result["products_price"],0,-2);
						
						$details[$product_id][TEXT_CP1]=$price;
						
						$details[$product_id][TEXT_CDT]='';
						
						$details[$product_id][TEXT_DS]="";
			}
		
					}

					$tickets[$row]["products_id"]=$product_id;
					$tickets[$row]["customers_id"]='';
					$tickets[$row]["orders_id"]='';
					$tickets[$row]["customers_firstname"]='';
					$tickets[$row]["customers_lastname"]='';
					$tickets[$row]["customers_name"]='';
					$tickets[$row]["reference_id"]='';
					$tickets[$row]["products_name"]=$order_result["products_name"];
					$tickets[$row]["customers_id"]='';
					$tickets[$row]["orders_products_id"]=$product_id;
					$tickets[$row]["barcode"]='';
					$tickets[$row]["billing_name"]='';
					$tickets[$row]["payment_method"]='';
					
					//if ($i != $quantity) { $row++; }
					

			

				require_once(DIR_FS_CATALOG_MODULES . "barcode/image.php");
				$bar_width=15;
				
				// print the ticket details
				$tickets = array_values($tickets);
					   //echo print_r($tickets);
					$tcnt=0;
			
					{       
					$pdf=new tFPDF("l","mm",array($ticket_width,$ticket_height));
					$ticket=$tickets[$row];
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
			$event[TEXT_BN]=$for .$ticket["billing_name"];
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
					
					if ($type=="G"){
					//$run='-' .$ticket["barcode"];
					$run=$ticket["barcode"];
					}else{
					$run="";
					}
					
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
					
					if (tep_not_null($cat_ticket_image)){
					$ticket_image=$cat_ticket_image;
					}
					elseif (tep_not_null($parent_ticket_image)){
					$ticket_image=$parent_ticket_image;
					}
					elseif (tep_not_null($parent_parent_ticket_image)){
					$ticket_image=$parent_parent_ticket_image;
					}else{
					$ticket_image="ticket.png";
					}
				
			###############################################################
			$tInfo=new objectInfo($template);
			$tInfo->shop_logo_image='../'.DIR_WS_IMAGES . $ticket_image;
			//$tInfo->shop_logo_image=DIR_FS_CATALOG_IMAGES . $tInfo->shop_logo_image;
			$tInfo->sponsor_logo_image='../'.DIR_WS_IMAGES . $tInfo->sponsor_logo_image;
				// get info of image
			if (file_exists($tInfo->shop_logo_image)){
				$info_shop=getimagesize($tInfo->shop_logo_image);
				$info_shop[0]=($info_shop[0]/$unit)*74/100;
				$info_shop[1]=($info_shop[1]/$unit)*74/100;
			}
			if (file_exists($tInfo->sponsor_logo_image)){
					$info_sponsor=getimagesize($tInfo->sponsor_logo_image);
					$info_sponsor[0]=($info_sponsor[0]/$unit)*114/100;
					$info_sponsor[1]=($info_sponsor[1]/$unit)*114/100;
				}
				// shop logo
				if (isset($info_shop)){
					if ($tInfo->shop_logo_position=="L")
					{
					$pdf->Image($tInfo->shop_logo_image,($tInfo->bar_image_position=="L"?$pos_left:0),0,0,65); //cartzone adjust image here
					}else
					{
					$pdf->Image($tInfo->shop_logo_image,($tInfo->bar_image_position=="L"?$pos_width-$info_shop[0]:$pos_width-$info_shop[0]-2),0);
					}
			}
			###############################################################	
					//setup folders
				$foldername='';
				if(isset($_REQUEST['foldernames'])&& $_REQUEST['foldernames']=='Yes'){
				if(tep_not_null($heading_name)){$foldername.= strtolower(trim(preg_replace('#\W+#', '_', $heading_name), '_')).'/';}
				if(tep_not_null($heading_venue)){$foldername.= strtolower(trim(preg_replace('#\W+#', '_', $heading_venue), '_')).'/';}
				if(tep_not_null($heading_date)){$foldername.= strtolower(trim(preg_replace('#\W+#', '_', $heading_date.$heading_time), '_')).'/';}
				}
									
			 
						   
					###############################################################	
					
								// create bar image
			if (BARCODE !="none")
			{
					//128
						if (BARCODE =="128"){
					//$bar_text=$ticket["orders_id"] . "_" . $ticket["customers_id"] . "_" . $ticket["products_id"] . $run;
					$bar_text=$ticket["products_id"] . $run;
					$bar_filename="tickets/images/ticket_bar_". $bar_text . ".png";
								if(!file_exists(dirname($bar_filename))){
								mkdir(dirname($bar_filename), 0777, true);}
					if (!file_exists($bar_filename)){
						create_bar_image($bar_text,$bar_filename);
					}
					// get info
					if (file_exists($bar_filename)){
						$info_bar=getimagesize($bar_filename);
						$info_bar[0]=$info_bar[0]/$unit*(74/100);
						$info_bar[1]=$info_bar[1]/$unit*(74/100);
						$bar_width=$info_bar[0];
					}
					 //adjust for the bar image			
					if ($tInfo->bar_image_position=="L"){
						$pos_left=$bar_width+2;
					
					} else {
						$pos_width=$ticket_width-$bar_width-2;
					}
					$content_width=$ticket_width-$bar_width-2; //from 2
						//bar image
						if (isset($info_bar)){
							if ($tInfo->bar_image_position=="L"){
								$pdf->Image($bar_filename,2,2);
							} else {
								$pdf->Image($bar_filename,$ticket_width-$bar_width-1,1, $bar_width); // position bar image horizontal-vertical
							}
						}
					}
							
					##############################################################
						if (BARCODE =="QR")
						{
						//qrcode
						
							require_once('tfpdf/qrcode/qrlib.php');
							$qr_width=QR_WIDTH;
							$qr_text=$ticket["orders_id"] . '_' . $ticket["products_id"] . $ticket["run"];
							$qr_filename="tickets/images/ticket_qr_". $qr_text . ".png";//ADMIN IMAGE PATH HERE
							if(!file_exists(dirname($qr_filename))){
							mkdir(dirname($qr_filename), 0777, true);}
							if (!file_exists($qr_filename)){
							QRcode::png($qr_text, $qr_filename,"Q",4,4); 
							$pdf->Image($qr_filename,QR_LEFT_POSITION,QR_TOP_POSITION, $qr_width); //file,x,y,width,height
								create_bar_image($qr_text,$qr_filename);
							}
							
							
						}// end QR
			}
			##############################################################
				
				$top_font_size=TOP_FONT_SIZE;
				$pdf->SetFont('DejaVu','B',$top_font_size); //sets the font for TOP line (20)
				
				//event details MIDDLE
				$content=$tInfo->event_details_content;
				reset($event);
				//while(list($key,$value)=each($event))
				foreach($event as $key => $value) 
				{
					$content=preg_replace("/%%" . $key1  . "%%/i",$value1,$content);
					//$content=preg_replace("/@@" . $key1  . "@@/i",$value1,$content);
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
			
			
					$pdf->SetFont('DejaVu','B',BOTTOM_FONT_SIZE);

					
					
					$pos_top+=10;

					if (!isset($cond_splt))
					{
						$cond_height=0;
						$cond_splt=preg_split("/\n/",$tInfo->event_condition_content);
						$cond_height=count($cond_splt)*3;
						$cond_width=0;
						$temp_width=0;
						for ($icnt=0;$icnt<count($cond_splt);$icnt++){
							$temp_width=$pdf->GetStringWidth($cond_splt[$icnt]);
							if ($temp_width>$cond_width) $cond_width=$temp_width;
						}
					}
					
					
					$temp_left=0;
					$temp_right=$ticket_width-$info_sponsor[0];
					if (isset($info_sponsor))
					{
						if ($tInfo->sponsor_logo_position=="L"){
							//$pdf->Image($tInfo->sponsor_logo_image,$temp_left,$ticket_height-$info_sponsor[1]);
							//$pdf->Image($tInfo->sponsor_logo_image,$vt,$hz,$wd,$ht);//vertical,horizontal,width,height
							//manually set positioning
							$pdf->Image($tInfo->sponsor_logo_image,$temp_left+5,2,40,20);//vertical,horizontal,width,height
							$temp_left+=$info_sponsor[0];
						} else  {
							//$pdf->Image($tInfo->sponsor_logo_image,$temp_right,$ticket_height-$info_sponsor[1]);
							//$pdf->Image($tInfo->sponsor_logo_image,$vt,$hz,$wd,$ht);
							//manually set positioning
							$pdf->Image($tInfo->sponsor_logo_image,110,5,60,20);//vertical,horizontal,width,height120,2,40,20
						}
					}
					
					// conditions BOTTOM
					$temp_top=$ticket_height-$cond_height;
					if ($tInfo->event_condition_position=="L")
					{
						$cond_left=$temp_left+5; //position of BOTTOM text from left margin
					} else {
						$cond_left=$ticket_width-$cond_width;
					}
					for ($icnt=0;$icnt<count($cond_splt);$icnt++){
						$pdf->Text($cond_left,$temp_top,$cond_splt[$icnt]);
						$temp_top+=3;
					}
				}
				for ($tcnt=0;$tcnt<$quantity;$tcnt++){  
				   
						if($quantity>1){$end='_'.((int)$tcnt+1);}else{$end='';}
						$events_tickets=strtolower(trim(preg_replace('#\W+#', '_', $order_result["products_name"]), '_')).$end;

						$file= "tickets/output/".$foldername.$events_tickets.".pdf";
						if(!file_exists(dirname($file))){
						mkdir(dirname($file), 0777, true);
									 }
						$pdf->output($file,'F');
						}
				}
			}//end ticket print function
			$tickets_printed=true;

			if(isset($_REQUEST['zip']) && $_REQUEST['zip']=='Yes')
			{
				$the_folder = 'tickets/output/';
				$zip_file_name = 'pdf_tickets.zip';


					$download_file= true;
					//$delete_file_after_download= true; 


					class FlxZipArchive extends ZipArchive 
					{
						/** Add a Dir with Files and Subdirs to the archive;;;;; @param string $location Real Location;;;;  @param string $name Name in Archive;;; @author Nicolas Heimann;;;; @access private  **/

							public function addDir($location, $name) {
							$this->addEmptyDir($name);

							$this->addDirDo($location, $name);
							} // EO addDir;

							/**  Add Files & Dirs to archive;;;; @param string $location Real Location;  @param string $name Name in Archive;;;;;; @author Nicolas Heimann
							 * @access private   **/
							private function addDirDo($location, $name) {
								$name .= '/';
								$location .= '/';

								// Read all Files in Dir
								$dir = opendir ($location);
								while ($file = readdir($dir))
								{
									if ($file == '.' || $file == '..') continue;
									// Rekursiv, If dir: FlxZipArchive::addDir(), else ::File();
									$do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
									$this->$do($location . $file, $name . $file);
								}
							} // EO addDirDo();
					}

					$za = new FlxZipArchive;
					$res = $za->open($zip_file_name, ZipArchive::CREATE);
					if($res === TRUE) 
					{
						$za->addDir($the_folder, basename($the_folder));
						$za->close();
					}
					else  { echo 'Could not create a zip archive';}

					if ($download_file)
					{
						ob_get_clean();
						header("Pragma: public");
						header("Expires: 0");
						header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
						header("Cache-Control: private", false);
						header("Content-Type: application/zip");
						header("Content-Disposition: attachment; filename=" . basename($zip_file_name) . ";" );
						header("Content-Transfer-Encoding: binary");
						header("Content-Length: " . filesize($zip_file_name));
						readfile($zip_file_name);

						//deletes file when its done...
						//if ($delete_file_after_download) 
						//{ unlink($zip_file_name); }
					}
					$tickets_printed=true;
			}
	}//end if cat_id
	
	########################################################################
	function &getDefaultTemplate(){
		$template_result=array(	"template_width"=>18.0000,
								"template_height"=>7.0000,
								"template_content"=>
								"shop_logo_image{}ticket.png{}shop_logo_position{}L{}event_details_content{}%%Concert Name%%\n%%Concert Venue%%\n%%Concert Date%% - %%Concert Time%% \nSeat name:%%Products Name%% Price: %%Symbol%%%%Concert Price%% %%Discount Type%% \n%%Coupon%%\n%%Customers Name%%\nTicket ref: %%Ref ID%%%%Prd ID%% \n{}event_details_position{}L{}sponsor_logo_image{}sponsor_logo.jpg{}sponsor_logo_position{}R{}event_condition_content{}Refundable only if event is cancelled{}event_condition_position{}L{}bar_image_position{}R'"
								);
		return $template_result;
	}
	###############################################################################
        function  create_cat_array($category,  $level  =  0){
            global $categories_string;

            $q  =  "select c.categories_id, cd.categories_name, c.parent_id from categories c , categories_description cd  where c.parent_id ='".$category."' and c.categories_id = cd.categories_id order by sort_order , cd.categories_name";
             $r  =  tep_db_query($q); 
                $level++;             
               while($d  =  tep_db_fetch_row($r)){//we may need a mysqli here but there's no tep_db_fetch_row
                    
                 $categories_string[]= $d[0];
                 
                  //recursive  call  :
                 create_cat_array($d[0],  $level);

                } 
            return $categories_string;
        }
        #############################################
        
function Zip($source, $destination, $include_dir = false)
{

    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    if (file_exists($destination)) {
        unlink ($destination);
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }
    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        if ($include_dir) {

            $arr = explode("/",$source);
            $maindir = $arr[count($arr)- 1];

            $source = "";
            for ($i=0; $i < count($arr) - 1; $i++) { 
                $source .= '/' . $arr[$i];
            }

            $source = substr($source, 1);

            $zip->addEmptyDir($maindir);

        }

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $file = realpath($file);

            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}
        define ('HEADING_TITLE', 'Create PDF tickets');
?>	


<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Create PDF tickets</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script language="javascript" src="includes/http.js"></script>
<script type="text/javascript" src="includes/aim.js"></script>
<script type="text/javascript" src="includes/tweak/js/ajax.js"></script>
<script language="JavaScript" src="includes/date-picker.js"></script>


<script language="javascript">
var img_src="<?php echo HTTP_SERVER.DIR_WS_ADMIN.'images/';?>";
var selectedTab;
var lang = new Array();
var display_flag=1;
<?php 
 $languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
    	echo "lang[".$i."]=".$languages[$i]['id']."\n";
    }
?>
</script>
<?php include('includes/date_format_js.php'); 
?>
</head>
<body marginwidth="0"  marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" ">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<!-- body_text //-->
	<tr class="dataTableHeadingRow">
		<td valign="top">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
				<td class="main">
					<b>Create PDF tickets</b>
				</td>
				<td class="main" align="right">
					
				</td>
		</tr>
		</table>
		</td>
	</tr>
        <tr>
            <td>
            </td>
        </tr>
       <?php if($tickets_printed==true){?>
        <tr>
            <td>
      <p>
       PDF files created:<br />
       They are in the /output/ folder <a href="./tickets/index.php" target="_blank"> here </a> (new window/tab)
       <br />
       <br />
       <a href="javascript:history.go(-2)">[Reset]</a>
        
    </p>
            </td>
        </tr>
        <?php }else{?>
        <tr>
            <td>
                 <form name="catget" action ="" method="POST">
                Please select a category to print. You may select an entire show or any sub category.<br ?>
                The resulting tickets may be downloaded in a zip file, otherwise they will be saved in the <br />
                folder admin/tickets.
            </td>
        </tr>
        <tr>
       
                <td class="dataTableContent" valign="top"><?php echo tep_draw_pull_down_menu('cat_id', tep_get_category_tree('0', '', '0', $category_array), $_REQUEST['cat_print_id'],'style="width:300px;" '); ?></td>
            
        </tr>
        <tr>
            <td>
                <input type="checkbox" checked name="foldernames" value="Yes">Use foldernames
            </td>
        </tr>
        <tr>
            <td>
                <?php if(extension_loaded('zip')) {?>
                <input type="checkbox" name="zip" value="Yes">Download as a zip file
                <?php }?>
            </td>
        </tr>
                <tr>
            <td>
                <input type="checkbox" name="flush" value="Yes">Delete existing files on the server before creation.
            </td>
        </tr>
         <tr>
            <td>
                <button type="submit"  value="Submit">Submit</button>                </form>
 </td>
        </tr>
        <?php }?>
	<tr height="20" id="messageBoard" style="display:none">
		<td id="messageBoardText">
		</td>
	</tr>
	<tr>
		<td class="main" id="salCoupon-1message">
		</td>
	</tr>
	<tr>
		<td>

		</td>
	</tr>
	<tr style="display:none">
		<td id="ajaxLoadInfo"><div style="padding:5px 0px 5px 20px" class="main"><?php echo TEXT_LOADING . '&nbsp;' . tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div></td>
	</tr>
	<tr>
		<td id="ajaxLoadImage" style="display:none">
			<?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?>
		</td>
	</tr>

</table>
<!-- body_text_eof //-->
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>


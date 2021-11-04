<?php

/*

  

  Freeway eCommerce from ZacWare
  http://www.openfreeway.org

  Copyright 2007 ZacWare Pty. Ltd

  Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	require('includes/application_top.php');
	define('FPDF_FONTPATH','tfpdf/font/');
	require_once('tfpdf/tfpdf.php');
	tep_set_time_limit(100);
	$order_id=isset($HTTP_GET_VARS["oID"])?(int)$HTTP_GET_VARS["oID"]:0;

	$found_results=false;
	$unit=72/25.4;
	$order_ids='0';
	if ($order_id!=""){
		$order_id_splt=preg_split("/,/",$order_id);
		$order_id_splt=array_unique($order_id_splt);
		$order_ids="'" .join("','",$order_id_splt) . "'";
	}
		
	// get the template details if not found use default template
	$template_query=tep_db_query("SELECT template_width,template_height,template_content from " . TABLE_GENERAL_TEMPLATES . " where template_type='TIC'");
	if (tep_db_num_rows($template_query)>0) {
	    $found_results=true;
		$template_result=tep_db_fetch_array($template_query);
	} else {
		$template_result=getDefaultTemplate();
	}
	
	

	$template_splt=preg_split("/{}/",$template_result["template_content"]);
	for ($icnt=0;$icnt<count($template_splt);$icnt=$icnt+2){
		$key=$template_splt[$icnt];
		$template[$key]=$template_splt[$icnt+1];
	}

	$ticket_width=$template_result["template_width"]*10;
	$ticket_height=$template_result["template_height"]*10;
	
	$tInfo=new objectInfo($template);
	
	// set page setup default width and height
	$pdf=new tFPDF("P","mm",array($ticket_width,$ticket_height));

	
	$pos_left=5;
	$pos_top=5;
	$pos_width=$ticket_width;
	$pos_height=$ticket_height;
	// create the content and positions to be drawn 
	$content=array();

	// get info of image

	if (file_exists($tInfo->shop_logo_image)){
		$info_shop=getimagesize($tInfo->shop_logo_image);
		$info_shop[0]=$info_shop[0]/$unit;
		$info_shop[1]=$info_shop[1]/$unit;
	}
		
	if (file_exists($tInfo->oscomm_logo_image)){
		$info_oscomm=getimagesize($tInfo->oscomm_logo_image);
		$info_oscomm[0]=$info_oscomm[0]/$unit;
		$info_oscomm[1]=$info_oscomm[1]/$unit;
	}

	
	$details=array();
	$details[$events_id][TEXT_EL]="Location Name";
	$details[$events_id][TEXT_EN]="Events Name";
	$details[$events_id][TEXT_SI]="Events Start Time";
	$details[$events_id][TEXT_EI]="Events End Time";

	if ($found_results){

		require_once(DIR_FS_CATALOG_MODULES . "barcode/image.php");
		
		$bar_width=15;
		
			$ticket=1;
			$event=1;
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',10);
			$pos_top=5;
			$pos_left=5;
			
			// create bar image
			$bar_text=1 . "-" . 1;
			$bar_filename=DIR_FS_CATALOG_IMAGES . "ticket_bar_". $bar_text . ".png";
			if (!file_exists($bar_filename)){
				create_bar_image($bar_text,$bar_filename);
			}
			
			// get info
			if (file_exists($bar_filename)){
				$info_bar=getimagesize($bar_filename);
				$info_bar[0]=$info_bar[0]/$unit;
				$info_bar[1]=$info_bar[1]/$unit;
				$bar_width=$info_bar[0];
			}

			// adjust for the bar image			
			if ($template["bar_image_position"]=="L"){
				$pos_left=$bar_width+2;
			} else {
				$pos_width=$ticket_width-$bar_width-2;
			}
			$content_width=$ticket_width-$bar_width-2;
			//bar image
			if (isset($info_bar)){
				if ($tInfo->bar_image_position=="L"){
					$pdf->Image($bar_filename,2,2);
				} else {
					$pdf->Image($bar_filename,$ticket_width-$bar_width-2,2);
				}
			}

			// shop logo
			if (isset($info_shop)){
				if ($tInfo->shop_logo_position=="L"){
					$pdf->Image($tInfo->shop_logo_image,($pos_left>5?$pos_left:0),0);
				} else  {
					$pdf->Image($tInfo->shop_logo_image,$pos_width-$info_shop[0]-2,0);
				}
			}
			$pdf->SetFont('Arial','B',10);
			
			//event details
			$content=$tInfo->event_details_content;
			$mixed_search=array('%%First_Name%%','%%Last_Name%%','%%Event_Name%%','%%Events_Location%%','%%Start_Time%%','%%End_Time%%');
			$replace_array=array('First Name','Last Name','Events Name','Events Location','Start Time','End Time');	
			$content=str_replace($mixed_search,$replace_array,$content);
			$splt_line=preg_split("/\n/",$content);
			$temp_width=0;
			for ($icnt=0;$icnt<count($splt_line);$icnt++){
				$str_width=$pdf->GetStringWidth($splt_line[$icnt]);
				if ($str_width>$temp_width)	$temp_width=$str_width;
			}
	
			if ($tInfo->event_details_position=="L"){
				$temp_left=$pos_left;
			} else {
				$temp_left=$pos_width-2-$temp_width;
			}
			
			for ($icnt=0;$icnt<count($splt_line);$icnt++){
				if ($icnt>=1) $pdf->SetFont('Arial','B',8);
				$pdf->Text($temp_left,$pos_top,$splt_line[$icnt]);
				$pos_top+=3;
			}
			$pos_top+=8;
			$pdf->SetFont('Arial','B',10);
			
			// session dates
			$temp_top=$pos_top;
			$temp_width=$content_width-10;
			$temp_left=$pos_left;
			$part_width=($temp_width-($pdf->GetStringWidth("00-00-0000")*3))/2;
			$cnt=0;
			for ($icnt=0;$icnt<1;$icnt++){
				$pdf->Text($temp_left,$temp_top,date('Y-m-d'));
				$temp_left+=$part_width+$pdf->GetStringWidth(getServerDate());
				$cnt=$cnt+1;
				if ($cnt%3==0){
					$temp_top+=6;
					$temp_left=$pos_left;
				}
			}
			$pdf->SetFont('Arial','B',8);
			$pos_top+=20;
			
			if (!isset($cond_splt)){
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
			
			// print osConcert
			$temp_left=2;
			$temp_right=$ticket_width-2-$info_oscomm[0];
			if (isset($info_oscomm)){
				if ($tInfo->oscomm_logo_position=="L"){
					$pdf->Image($tInfo->oscomm_logo_image,$temp_left,$ticket_height-$info_oscomm[1]-2);
					$temp_left+=$info_oscomm[0];
				} else  {
					$pdf->Image($tInfo->oscomm_logo_image,$temp_right,$ticket_height-$info_oscomm[1]-5);
				}
			}
			
			// conditions
			$temp_top=$ticket_height-2-$cond_height;
			if ($tInfo->event_condition_position=="L"){
				$cond_left=$temp_left+2;
			} else {
				$cond_left=$temp_right-2-$cond_width;
			}
			for ($icnt=0;$icnt<count($cond_splt);$icnt++){
				$pdf->Text($cond_left,$temp_top,$cond_splt[$icnt]);
				$temp_top+=3;
			}
	} else {
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->Text(5,5,'No Details Found');
	}
	$pdf->output(DIR_FS_CATALOG . "images/preview_tickets.pdf",'I');
	//tep_db_query("update  " . TABLE_ORDERS . "  set ticket_printed='Y' where orders_id in(" . $order_ids . ")");
	//tep_redirect(DIR_WS_CATALOG . "images/preview_tickets.pdf");
	function &getDefaultTemplate(){
		$template_result=array(	"template_width"=>10.5,
								"template_height"=>6.3,
								"template_content"=>"shop_logo_image{}" . DIR_FS_CATALOG . "images/shop_logo.png{}shop_logo_position{}L{}event_details_content{}%%First_Name%%-%%Last_Name%% \n%%Event_Name%% \n%%Events_Location%% \n%%Start_Time%% %%End_Time%%{}event_details_position{}R{}oscomm_logo_image{}" . DIR_FS_CATALOG . "/images/sponsor_logo.png{}oscomm_logo_position{}R{}event_condition_content{}Please Bring this card for each class. \nFull Conditions www.yogbaby.com.au{}event_condition_position{}L{}bar_image_position{}R"
								);
		return $template_result;
	}
?>
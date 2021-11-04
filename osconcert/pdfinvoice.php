<?php
define( '_FEXEC', 1 );
ob_start ();
// Get everything set-up
	//define('FPDF_FONTPATH','includes/modules/fpdf/font/');
	//require('includes/modules/fpdf/fpdf.php');
	define('FPDF_FONTPATH','tfpdf/font/');
	require_once('tfpdf/tfpdf.php');
	
	$pdf = new tFPDF();
	$pdf->AddPage();
	$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$pdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
	$pdf->SetFont('DejaVu','B',15); //13

	
  require('includes/application_top.php');
	if (!$FSESSION->is_registered('customer_id')) 
	{
		$navigation->set_snapshot();
		tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
	}
	
	//require(DIR_WS_CLASSES . 'object_info.php');
require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . 'pdfinvoice.php');
require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . 'account_history_info.php');
  include(DIR_WS_CLASSES . 'order.php');
  $customer_number_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($FGET['orders_id'])) . "'"); 
  $customer_number = tep_db_fetch_array($customer_number_query);

 	$dis_time_format="";
	if(defined('TIME_FORMAT')) {
		$dis_time_format=TIME_FORMAT;
	}
	$oID = $FREQUEST->getvalue('oID');
	$order = new order($oID);

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if(ALL_INVOICE_HEADINGS=='yes'){
define ('PROD_BOX_LENGTH','122');//without headings
}else{
define ('PROD_BOX_LENGTH','182');//with headings
}
// Set width of product model box if shown
$model_box_length = 260;
// ------------------------------------------

// Set product description box and text sizes - without product model box
$prod_attrib_length = 90;
$prod_name_length = 70;
$prod_box_length = PROD_BOX_LENGTH;
$x_indent = 18;
// Fix the product box and indent lngths for repeating pages
$fixed_prod_box_length = $prod_box_length;
$fixed_x_indent = $x_indent;
// Page Numbering
$pgno=1;

  // Get text colours
  $standard_color = html2rgb(PDF_INV_STANDARD_COLOR);
  $com_name_color = html2rgb(PDF_INV_COM_NAME_COLOR);
  $com_address_color = html2rgb(PDF_INV_COM_ADDRESS_COLOR);
  $com_email_color = html2rgb(PDF_INV_COM_EMAIL_COLOR);
  $com_web_address_color = html2rgb(PDF_INV_COM_WEB_ADDRESS_COLOR);
  $inv_num_id_date_color = html2rgb(PDF_INV_NUMIDDATE_COLOR);
  $invoice_line = html2rgb(PDF_INV_INVLINE_COLOR);
  $footer_color = html2rgb(PDF_INV_FOOTER_COLOR);
  $border_color = html2rgb(PDF_INV_BORDER_COLOR);
  $cell_color = html2rgb(PDF_INV_CELL_COLOR);
  $watermark_color = html2rgb(PDF_INV_WATERMARK_COLOR);
      
  // Find Currency Symbol
  // $currency_sym = 'CZK';//this worked
  $currency_sym = $currencies->format($order->info['currency']);
  $currency_sym = preg_replace( '([0-9Â.]*)', '', $currency_sym);
  $prod_line_sym = '';
  if (DISPLAY_PROD_LINE_CURRENCY == 'true'){$prod_line_sym = $currency_sym . ' ';}

  // Search and replace arrays for order totals
  $search_for = array( '/'.$currency_sym.'/', '/Â/', '(<[a-z/]*>)' );
  $change_to = array( ' ', '', '' );
    
  // Remove http:// from web address (if enabled)
  if (REMOVE_HTTP_WEB_ADDRESS == 'true'){
  $web_address = HTTP_SERVER;
  $web_address = preg_replace( '(http://)', '', $web_address);
  $web_address = preg_replace( '(https://)', '', $web_address);
  } else {
  $web_address = HTTP_SERVER;
  }
  
   	$order_id=$FGET['oID'];
// Perform security check to prevent "get" tampering to view other customer's invoices
  if (!$FSESSION->is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link('login.php', '', 'SSL'));
  }

  if (!isset($order_id) || (isset($order_id) && !is_numeric($order_id))) 
  {
    tep_redirect(tep_href_link('account_history.php', '', 'SSL'));
  }
  
  $customer_info_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". $order_id . "'");
  $customer_info = tep_db_fetch_array($customer_info_query);
  if ($customer_info['customers_id'] != (int)$FSESSION->customer_id) {
    tep_redirect(tep_href_link('account_history.php', '', 'SSL'));
  }

  
 

  
// Security check end //

	// Function to return rgb value for fpdf from supplied hex (#abcdef) 
	function html2rgb($color){
	if ($color[0] == '#')
		$color = substr($color, 1);

	if (strlen($color) == 6)
		list($r, $g, $b) = array($color[0].$color[1],
								 $color[2].$color[3],
								 $color[4].$color[5]);
	elseif (strlen($color) == 3)
		list($r, $g, $b) = array($color[0], $color[1], $color[2]);
	else
		return false;

	$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

	return array($r,$g,$b);
	}
   
	// function to decode html entities
	function tep_html_entity_decode($text, $quote_style = ENT_QUOTES){
	return html_entity_decode($text, $quote_style);
	}
		
	// find image type
	function findextension ($filename)
	{
	$filename = strtolower($filename);
	$extension= explode("\.", $filename);
	$n = count($extension)-1;
	$extension = $extension[$n];
	return $extension;
	}
 
	// set invoice date - today or day ordered. set in config
	$date = (PDF_INV_DATE == 'oID') ? tep_date_long($order->info['date_purchased']) : strftime(DATE_FORMAT_LONG);
	$shortdate = (PDF_INV_DATE == 'oID') ? tep_date_short($order->info['date_purchased']) : strftime(DATE_FORMAT_SHORT);


class PDF extends tFPDF
{


//Wordwrap for comment box
function WordWrap(&$text, $maxwidth)
{
    $text = trim($text);
    if ($text==='')
        return 0;
    $space = $this->GetStringWidth(' ');
    $lines = explode("\n", $text);
    $text = '';
    $count = 0;

    foreach ($lines as $line)
    {
        $words = preg_split('/ +/', $line);
        $width = 0;

        foreach ($words as $word)
        {
            $wordwidth = $this->GetStringWidth($word);
            if ($width + $wordwidth <= $maxwidth)
            {
                $width += $wordwidth + $space;
                $text .= $word.' ';
            }
            else
            {
                $width = $wordwidth + $space;
                $text = rtrim($text)."\n".$word.' ';
                $count++;
            }
        }
        $text = rtrim($text)."\n";
        $count++;
    }
    $text = rtrim($text);
    return $count;
}

//Page header
 function RoundedRect($x, $y, $w, $h,$r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' or $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2f %.2f m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l', $xc*$k,($hp-$y)*$k ));

        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
	
    function Header()
    {
    global $FREQUEST, $order_id, $pos_y, $com_name_color, $com_address_color, $com_email_color, $com_web_address_color, $inv_num_id_date_color, $invoice_line,
	$date, $image_function, $customer_id, $web_address, $vat_shift_y;
    
	//Logo
    $size =getimagesize(PDF_INVOICE_IMAGE);
    $this->$image_function(PDF_INVOICE_IMAGE,7,10,($size[0]*PDF_INV_IMG_CORRECTION),($size[1]*PDF_INV_IMG_CORRECTION),'', FILENAME_DEFAULT);
    //BELOW WE CAN EXTEND THE SECTIONS USING CELL/MULTICELL VALUES TRIAL BY ERROR
    // Company name
	$this->SetX(0);
	$this->SetY(10);
    $this->SetFont(PDF_INV_CORE_FONT,'B',10);
	$this->SetTextColor($com_name_color[0],$com_name_color[1],$com_name_color[2]);
    //$this->Ln(0);
    $this->Cell(107);
	$this->MultiCell(100, 3.5, tep_html_entity_decode(STORE_NAME),0,'L');
    
	$pos_y = 15;
	// Company Address
	$this->SetFont(PDF_INV_CORE_FONT,'B',10);
	$this->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$this->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
	$this->SetFont('DejaVu','',10); //13
	$this->SetX(1);
	$this->SetY($pos_y);
    $this->SetFont(PDF_INV_CORE_FONT,'',10);
	$this->SetTextColor($com_address_color[0],$com_address_color[1],$com_address_color[2]);
  //  $this->Ln(0);
    $this->Cell(107);
   
	$this->MultiCell(120, 4, tep_html_entity_decode(STORE_NAME_ADDRESS),0,'L');
	$pos_y += ((substr_count(STORE_NAME_ADDRESS, "\n")+1) * 4.4);

    // VAT / Tax number (if enabled)
    if (DISPLAY_PDF_TAX_NUMBER == 'true')
	{
		$this->SetX(0);
		$this->SetY($pos_y+3);
		$this->SetFont(PDF_INV_CORE_FONT,'',10);
		$this->SetTextColor($standard_color[0],$standard_color[1],$standard_color[2]);
		//$this->Ln(0);
		$this->Cell(107);
		$this->MultiCell(100, 6, tep_html_entity_decode(PDF_TAX_NAME) . ' ' . PDF_TAX_NUMBER,0,'L');
		$vat_shift_y = 10;
		$pos_y += 10;
    }
	
	//Email
	$this->SetX(0);
	$this->SetY($pos_y+.5);
	$this->SetFont(PDF_INV_CORE_FONT,'',10);
	$this->SetTextColor($com_email_color[0],$com_email_color[1],$com_email_color[2]);
	//$this->Ln(0);
    $this->Cell(107);
	$this->MultiCell(100, 6, tep_html_entity_decode(PDF_INV_EMAIL) . STORE_OWNER_EMAIL_ADDRESS,0,'L');
	
	//Website
	$this->SetX(0);
	$this->SetY($pos_y+5.5);
	$this->SetFont(PDF_INV_CORE_FONT,'',10);
	$this->SetTextColor($com_web_address_color[0],$com_web_address_color[1],$com_web_address_color[2]);
	//$this->Ln(0);
    $this->Cell(107);
	$this->MultiCell(100, 6, tep_html_entity_decode(PDF_INV_WEB) . $web_address,0,'L');
	
	// Invoice Number, customer reference and date
	$this->SetFont(PDF_INV_CORE_FONT,'',10);
	$this->SetTextColor($inv_num_id_date_color[0],$inv_num_id_date_color[1],$inv_num_id_date_color[2]);
	$this->Text(9, $pos_y+15 ,tep_html_entity_decode(PRINT_INVOICE_TITLE) . "#" . $order_id );
	$this->Text(9, $pos_y+19 ,$date );
	
	// Draw the top line with invoice text
	$pos_y += 25;
	$this->Cell(50);
	$this->SetY($pos_y);
	$this->SetDrawColor($invoice_line[0],$invoice_line[1],$invoice_line[2]);
	$this->Cell(15,.1,'',1,1,'L',1);
	$this->SetFont(PDF_INV_CORE_FONT,'BI',12);
	$this->SetTextColor($invoice_line[0],$invoice_line[1],$invoice_line[2]);
	$this->Text(20,$pos_y+1.5,tep_html_entity_decode(PRINT_INVOICE_HEADING));
	//$this->SetDrawColor($invoice_line[0],$invoice_line[1],$invoice_line[2]);
	$this->Cell(48);
	$this->Cell(150,.1,'',1,1,'L',1);
}

// function taken and modified from $Id: pdf_datasheet_functions v1.40 2005/06/16 13:46:29 ip chilipepper.it Exp $

  function RotatedText($x,$y,$txt,$angle)
 {
    //Text rotated around its origin
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
 }

 
 function Watermark()
 {
    global $watermark_color;
    $this->SetFont(PDF_INV_CORE_FONT,'B',60);
    $this->SetTextColor($watermark_color[0], $watermark_color[1], $watermark_color[2]);
    $ang=30;                                                                             
    $cos=cos(deg2rad($ang));
    $wwm=($this->GetStringWidth(tep_html_entity_decode(PDF_INV_WATERMARK_TEXT))*$cos);
    $this->RotatedText(($this->w-$wwm)/2,$this->w,PDF_INV_WATERMARK_TEXT,$ang);
 }

function Footer()
{
    global $footer_color, $invoice_line, $pgno;
        
    // insert horiz line
    $this->SetY(-19);
    $this->SetDrawColor($invoice_line[0],$invoice_line[1],$invoice_line[2]);
    $this->Cell(198,.1,'',1,1,'L',1);
    
    //Position at 1.5 cm from bottom
    if (DISPLAY_PAGE_NUMBER == 'true') {$this->SetY(-19.5);} else {$this->SetY(-17);}
    $this->SetFont(PDF_INV_CORE_FONT,'',8);
	$this->SetTextColor($footer_color[0],$footer_color[1],$footer_color[2]);
	$this->Cell(0,10, tep_html_entity_decode(PDF_INV_FOOTER_TEXT), 0,0,'C');  
	
    //Page Number
    if (DISPLAY_PAGE_NUMBER == 'true'){
		$this->SetY(-15);
		$this->SetTextColor(100,100,100);
		$this->Cell(0,10, 'Page ' . $pgno, 0,0,'C');
		$pgno++;
	}
  }
  
}

/***************************
* Software: FPDF_EPS
* Version:  1.3
* Date:     2006-07-28
* Author:   Valentin Schmidt
****************************/
class PDF_EPS extends PDF{

function __construct($orientation='P',$unit='mm',$format='A4'){
	parent::FPDF($orientation,$unit,$format);
}

	function ImageEps ($file, $x, $y, $w=0, $h=0, $link='', $useBoundingBox=true)
	{
	$data = file_get_contents($file);
	if ($data===false) $this->Error('EPS file not found: '.$file);

	# strip binary bytes in front of PS-header
	$start = strpos($data, '%!PS-Adobe');
	if ($start>0) $data = substr($data, $start);

	# find BoundingBox params
	ereg ("%%BoundingBox:([^\r\n]+)", $data, $regs);
	if (count($regs)>1){
		list($x1,$y1,$x2,$y2) = explode(' ', trim($regs[1]));
	}
	else $this->Error('No BoundingBox found in EPS file: '.$file);

	$start = strpos($data, '%%EndSetup');
	if ($start===false) $start = strpos($data, '%%EndProlog');
	if ($start===false) $start = strpos($data, '%%BoundingBox');

	$data = substr($data, $start);

	$end = strpos($data, '%%PageTrailer');
	if ($end===false) $end = strpos($data, 'showpage');
	if ($end) $data = substr($data, 0, $end);

	# save the current graphic state
	$this->_out('q');

	$k = $this->k;

	if ($useBoundingBox){
		$dx = $x*$k-$x1;
		$dy = $y*$k-$y1;
	}else{
		$dx = $x*$k;
		$dy = $y*$k;
	}
	
	# translate
	$this->_out(sprintf('%.3f %.3f %.3f %.3f %.3f %.3f cm', 1,0,0,1,$dx,$dy+($this->hPt - 2*$y*$k - ($y2-$y1))));
	
	if ($w>0){
		$scale_x = $w/(($x2-$x1)/$k);
		if ($h>0){
			$scale_y = $h/(($y2-$y1)/$k);
		}else{
			$scale_y = $scale_x;
			$h = ($y2-$y1)/$k * $scale_y;
		}
	}else{
		if ($h>0){
			$scale_y = $h/(($y2-$y1)/$k);
			$scale_x = $scale_y;
			$w = ($x2-$x1)/$k * $scale_x;
		}else{
			$w = ($x2-$x1)/$k;
			$h = ($y2-$y1)/$k;
		}
	}
	
	# scale	
	if (isset($scale_x))
		$this->_out(sprintf('%.3f %.3f %.3f %.3f %.3f %.3f cm', $scale_x,0,0,$scale_y, $x1*(1-$scale_x), $y2*(1-$scale_y)));
	
	# handle pc/unix/mac line endings
	$lines = explode("\r\n|[\r\n]", $data);

	$u=0;
	$cnt = count($lines);
	for ($i=0;$i<$cnt;$i++){
		$line = $lines[$i];
		if ($line=='' || $line[0]=='%') continue;
		$len = strlen($line);
		if ($len>2 && $line[$len-2]!=' ') continue;
		$cmd = $line[$len-1];

		switch ($cmd){
			case 'm':
			case 'l':
			case 'v':
			case 'y':
			case 'c':

			case 'k':
			case 'K':
			case 'g':
			case 'G':

			case 's':
			case 'S':

			case 'J':
			case 'j':
			case 'w':
			case 'M':
			case 'd' :
			
			case 'n' :
			case 'v' :
				$this->_out($line);
				break;
										
			case 'x': # custom colors
				list($c,$m,$y,$k) = explode(' ', $line);
				$this->_out("$c $m $y $k k");
				break;
				
			case 'Y':
				$line[$len-1]='y';
				$this->_out($line);
				break;

			case 'N':
				$line[$len-1]='n';
				$this->_out($line);
				break;
		
			case 'V':
				$line[$len-1]='v';
				$this->_out($line);
				break;
												
			case 'L':
				$line[$len-1]='l';
				$this->_out($line);
				break;

			case 'C':
				$line[$len-1]='c';
				$this->_out($line);
				break;

			case 'b':
			case 'B':
				$this->_out($cmd . '*');
				break;

			case 'f':
			case 'F':
				if ($u>0){
					$isU = false;
					$max = min($i+5,$cnt);
					for ($j=$i+1;$j<$max;$j++)
						$isU = ($isU || ($lines[$j]=='U' || $lines[$j]=='*U'));
					if ($isU) $this->_out("f*");
				}else
					$this->_out("f*");
				break;

			case 'u':
				if ($line[0]=='*') $u++;
				break;

			case 'U':
				if ($line[0]=='*') $u--;
				break;
			
			#default: echo "$cmd<br>"; #just for debugging
		}

	}

	# restore previous graphic state
	$this->_out('Q');
	if ($link)
		$this->Link($x,$y,$w,$h,$link);
}

}# END CLASS

# for backward compatibility
if (!function_exists('file_get_contents')){
	function file_get_contents($filename, $use_include_path = 0){
		$file = @fopen($filename, 'rb', $use_include_path);
		if ($file){
			if ($fsize = @filesize($filename))
				$data = fread($file, $fsize);
			else {
				$data = '';
				while (!feof($file)) $data .= fread($file, 1024);
			}
			fclose($file);
			return $data;
		}else
			return false;
	}
}


// Instanciation of inherited class - choose according to logo supplied, vector or raster
if(findextension(PDF_INVOICE_IMAGE) == 'ai' || findextension(PDF_INVOICE_IMAGE) == 'eps'){
	$pdf=new PDF_EPS();
	$image_function = "ImageEps";
	}
	else{
	$pdf=new PDF();
	$image_function = "Image";
	}




	// Set the Page Margins
	$pdf->SetMargins(6,2,6);

	// Initialise y offset
	$shift_y=0;
	
	// Add the first page
	$pdf->AddPage();

	// Add watermark if required
    if(PDF_SHOW_WATERMARK == 'true')
	{
    $pdf->Watermark();
    }

	
	// Draw Box for Invoice Address
	$pdf->SetDrawColor($border_color[0],$border_color[1],$border_color[2]);
	$pdf->SetLineWidth(0.2);
	$pdf->SetFillColor($cell_color[0],$cell_color[1],$cell_color[2]);
	$pdf->RoundedRect(9, $pos_y+5, 93, 35, 2, 'DF');

	// Draw the invoice address text
    $pdf->SetFont(PDF_INV_CORE_FONT,'B',10);
	$pdf->SetTextColor($standard_color[0],$standard_color[1],$standard_color[2]);
	$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$pdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
	$pdf->SetFont('DejaVu','',12);
	$pdf->Text(14,$pos_y+14, tep_html_entity_decode(ENTRY_SOLD_TO));
	$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$pdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
	$pdf->SetFont('DejaVu','',10); //13
	$pdf->SetX(3);
	$pdf->SetY($pos_y+16);
    $pdf->Cell(12);
	$pdf->MultiCell(73, 4, tep_html_entity_decode(tep_address_format($order->customer['format_id'], $order->customer, '', '', "\n")),0,'L');
	
	// Draw Box for Delivery Address
	$pdf->SetDrawColor($border_color[0],$border_color[1],$border_color[2]);
	$pdf->SetLineWidth(0.2);
	$pdf->SetFillColor(255);
	$pdf->RoundedRect(108, $pos_y+5, 93, 35, 2, 'DF');
	
	// Draw the invoice delivery address text
    // $pdf->SetFont(PDF_INV_CORE_FONT,'B',10);
	// $pdf->SetTextColor($standard_color[0],$standard_color[1],$standard_color[2]);
	// $pdf->Text(114,$pos_y+14,tep_html_entity_decode(ENTRY_SHIP_TO));
	// $pdf->SetFont(PDF_INV_CORE_FONT,'',10);
	// $pdf->SetX(0);
	// $pdf->SetY($pos_y+17);
    // $pdf->Cell(111);
	// $pdf->MultiCell(70, 4, tep_html_entity_decode(tep_address_format($order->delivery['format_id'], $order->delivery, '', '', "\n")),0,'L');
	
	// Draw the invoice billing address text
    $pdf->SetFont(PDF_INV_CORE_FONT,'B',10);
	$pdf->SetTextColor($standard_color[0],$standard_color[1],$standard_color[2]);
	$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$pdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
	$pdf->SetFont('DejaVu','',12);
	$pdf->Text(114,$pos_y+14,tep_html_entity_decode(ENTRY_BILL_TO));
	$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$pdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
	$pdf->SetFont('DejaVu','',10); //13
	$pdf->SetX(0);
	$pdf->SetY($pos_y+16);
    $pdf->Cell(111);
	$pdf->MultiCell(70, 4, tep_html_entity_decode(tep_address_format($order->billing['format_id'], $order->billing, '', '', "\n")),0,'L');
 
	// Begin Box for Order Number, Date & Payment method & customer reference
	$pdf->SetDrawColor($border_color[0],$border_color[1],$border_color[2]);
	$pdf->SetLineWidth(0.2);
	$pdf->SetFillColor($cell_color[0],$cell_color[1],$cell_color[2]);

	$pos_y+=36;
	// Display Customer Reference Number
	if (DISPLAY_CUSTOMER_REFERENCE == 'true'){
		// Finish Box for Order Number, Date & Payment method & customer reference
		$pdf->RoundedRect(9, $pos_y+7, 192, 14, 2, 'DF');
		$cref_shift_y=5;
		// Draw customer reference
		$pdf->SetFont(PDF_INV_CORE_FONT,'B',10);
		$pdf->Text(13,$pos_y+17.5, tep_html_entity_decode(PDF_INV_CUSTOMER_REF));
		$pdf->SetFont(PDF_INV_CORE_FONT,'',10);
		$pdf->Text(48,$pos_y+17.5, (int)$customer_id);
		} else {
		// Finish Box for Order Number, Date & Payment method
		$pdf->RoundedRect(9, $pos_y+7, 192, 9, 2, 'DF');
	}
		
	// Draw Order Number Text
	$pdf->SetFont(PDF_INV_CORE_FONT,'B',10);
	$pdf->Text(12,$pos_y+13, tep_html_entity_decode(PRINT_INVOICE_ORDERNR));	
	$pdf->SetFont(PDF_INV_CORE_FONT,'',10);
	$pdf->Text(39,$pos_y+13, (int)$order_id);	
	// Draw Date of Order Text
	$pdf->SetFont(PDF_INV_CORE_FONT,'B',10);
	$pdf->Text(48,$pos_y+13, tep_html_entity_decode(PRINT_INVOICE_DATE));	
	$pdf->SetFont(PDF_INV_CORE_FONT,'',10);
	$pdf->Text(88,$pos_y+13, $shortdate);	
	// Draw Payment Method Text
	$pdf->SetFont(PDF_INV_CORE_FONT,'B',10);
	$pdf->Text(118,$pos_y+13, tep_html_entity_decode(ENTRY_PAYMENT_METHOD));
	$pdf->SetFont(PDF_INV_CORE_FONT,'',10);
	$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$pdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
	$pdf->SetFont('DejaVu','',10); //13
	$pdf->Text(152,$pos_y+13, tep_html_entity_decode(substr($order->info['payment_method'] , 0, 23)));
	
	$pos_y += $cref_shift_y+6;
	
	// Comments Box p1 Begin
	$totlines=10;
	$comm_shift_y=0;
    if (DISPLAY_PDF_COMMENTS == 'true' && ($order->comments != NULL)){
		$pos_y+=13;
		//
		// check how many lines for comments box
		//
		 for ($i = 0, $n = 3; $i < $n; $i++){
			if ($order->comments[$i]['comments'] != NULL) {
				//$pdf->Text(10 , 133+(5*$i), tep_date_short($order->comments[$i]['date_added']) . " : " . 
				$text=$order->comments[$i]['comments'];

				$text = str_replace("\r", "", $text);
				$text = str_replace("\n", "", $text);

				$nb=$pdf->WordWrap($text, 230);
				$totlines += (($nb+1)*3.5);
				//$totlines += ($nb==1 ? ($nb*3.5) : ($nb+1)*3.5);
			}
		}
		//
		// end check lines
		//

		//Draw Box for Comments
		$pdf->SetDrawColor($border_color[0],$border_color[1],$border_color[2]);
		$pdf->SetLineWidth(0.2);
		$pdf->SetFillColor($cell_color[0],$cell_color[1],$cell_color[2]);
		$pdf->RoundedRect(9, $pos_y, 192, $totlines, 2, 'DF');
		
		// Output comments - limited by $n, here set to 3. Any more and you need to adjust box height, product table y position etc
		$pdf->SetFont(PDF_INV_CORE_FONT,'B',10);
		$pdf->Text(13 ,$pos_y+6, tep_html_entity_decode(PDF_INV_COMMENTS));
		$pdf->SetFont(PDF_INV_CORE_FONT,'',8);

		$pdf->SetLeftMargin(12);
		//$pdf->SetY($pos_y+2);

		for ($i = 0, $n = 3; $i < $n; $i++){
			if ($order->comments[$i]['comments'] != NULL) {
				$text=$order->comments[$i]['comments'];

				$text = str_replace("\r", "", $text);
				$text = str_replace("\n", "", $text);

				$nb=$pdf->WordWrap($text, 185);

				$pdf->SetY($pos_y+8+$comm_shift_y);
				$pdf->SetFont(PDF_INV_CORE_FONT,'B',8);
				$pdf->Write(3.5, 'Date added : ' . tep_date_short($order->comments[$i]['date_added']));

				$pdf->SetY($pos_y+11+$comm_shift_y);
				$pdf->SetFont(PDF_INV_CORE_FONT,'',8);
				$pdf->Write(3.5, $text);
				$comm_shift_y += (($nb+1)*3.5);
			}
		}
		$pdf->SetLeftMargin(6);
	}
	// Comments Box p1 End
	
	// Fields Name position
	$Y_Fields_Name_position = $pos_y+3+$totlines;
	// Table position, under Fields Name
	$Y_Table_Position = $Y_Fields_Name_position+6;

	
	function output_table_heading($Y_Fields_Name_position){
	global  $pdf, $cell_color, $prod_attrib_length, $prod_name_length, $model_box_length, $prod_box_length, $x_indent;
	// First create each Field Name
	// Config color filling each Field Name box
	$pdf->SetFillColor($cell_color[0],$cell_color[1],$cell_color[2]);
	// Bold Font for Field Name
	$pdf->SetFont(PDF_INV_CORE_FONT,'B',8);
	$pdf->SetY($Y_Fields_Name_position);
	$pdf->SetX(9);
	$pdf->Cell(9,6,tep_html_entity_decode(PDF_INV_QTY_CELL),1,0,'C',1);
	$pdf->SetX(18);
	
	if (DISPLAY_PRODUCT_MODEL == 'true'){
		// Set product description box and text sizes - with product model box
		$prod_box_length = $prod_box_length - $model_box_length;
		$x_indent = $x_indent + $model_box_length;
		$prod_attrib_length = round($prod_box_length * 0.85);
		$prod_name_length = round($prod_box_length * 0.7);
		//
		$pdf->Cell($model_box_length,6,tep_html_entity_decode(TABLE_HEADING_PRODUCTS_MODEL),1,0,'C',1);
		$pdf->SetX($x_indent);
		$pdf->Cell($prod_box_length,6,tep_html_entity_decode(TABLE_HEADING_PRODUCTS),1,0,'C',1);
		} else {
		$pdf->Cell($prod_box_length,6,tep_html_entity_decode(TABLE_HEADING_PRODUCTS),1,0,'C',1);
		}
	$pdf->SetX(185);//moves the price box
	$pdf->Cell(15,6,tep_html_entity_decode(TABLE_HEADING_PRICE),1,0,'C',1);	
	$pdf->SetX(140);
	#######################################################################################
	if(ALL_INVOICE_HEADINGS=='yes'){
	$pdf->Cell(15,6,tep_html_entity_decode(TABLE_HEADING_PRICE_EXCLUDING_TAX),1,0,'C',1);
	$pdf->SetX(155);
	$pdf->Cell(15,6,tep_html_entity_decode(TABLE_HEADING_PRICE_INCLUDING_TAX),1,0,'C',1);
	$pdf->SetX(170);
	$pdf->Cell(15,6,tep_html_entity_decode(TABLE_HEADING_TOTAL_EXCLUDING_TAX),1,0,'C',1);
	$pdf->SetX(185);
	$pdf->Cell(15,6,tep_html_entity_decode(TABLE_HEADING_TOTAL_INCLUDING_TAX),1,0,'C',1);
	}
	#######################################################################################
	$pdf->Ln();
	}

output_table_heading($Y_Fields_Name_position);

// Show the products information line by line
for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
	$prod_attribs='';
        
	// Get product attributes and concatenate
	if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
		for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
			$prod_attribs .= " - " .$order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value']; 
			}
		}   
	// Clean attributes
	$prod_attribs = preg_replace( $search_for, $change_to, $prod_attribs);		
	$product_name_attrib_contact = $order->products[$i]['name'] . $prod_attribs;

	// Get product final price values
	if(ALL_INVOICE_HEADINGS=='yes'){
	$price = substr($order->products[$i]['price'],0,6);
	$price_ex = substr($order->products[$i]['final_price'],0,6);
	$price_inc = tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']);
	$subt_ex = tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'];
	$subt_inc = tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'];
	}else{
	$price = $currencies->format($order->products[$i]['price'], true, $order->info['currency'], $order->info['currency_value']);
    	$price_ex = $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']);
	$price_inc = $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']);
	$subt_ex = $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']);
	$subt_inc = $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']);
	}

	// Clean product final price values
	$price = preg_replace( $search_for, $change_to, $price);
	$price_ex = preg_replace( $search_for, $change_to, $price_ex);
	$price_inc = preg_replace( $search_for, $change_to, $price_inc);
	$subt_ex = preg_replace( $search_for, $change_to, $subt_ex);
	$subt_inc = preg_replace( $search_for, $change_to, $subt_inc);

	// Trim products name to fit
	if (strlen($order->products[$i]['name']) > $prod_name_length){
		$order->products[$i]['name'] = substr($order->products[$i]['name'],0,$prod_name_length) . ' ... ';
		$heading_name;
		}

	// Write product lines	
	$pdf->SetFont(PDF_INV_CORE_FONT,'',8);
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(9);
	$pdf->MultiCell(9,7,$order->products[$i]['qty'],1,'C');
	$pdf->SetFont(PDF_INV_CORE_FONT,'',7);
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(18);
	
	$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$pdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
	$pdf->SetFont('DejaVu','',8); //13
	
	if (DISPLAY_PRODUCT_MODEL == 'true'){
		// Product model box & text
		$pdf->MultiCell($model_box_length,7,tep_html_entity_decode($order->products[$i]['model']),1,'C');
		$pdf->SetY($Y_Table_Position);
		$pdf->SetX($x_indent);
		}
	
	$text_indent = $x_indent + 0.5;
	
	// Write product description boxes and text
	$pdf->MultiCell($prod_box_length,7, '',1,'');
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX($text_indent);
	if (strlen($prod_attribs) > 1 ) 
	{
		$pdf->MultiCell($prod_box_length,5,tep_html_entity_decode($order->products[$i]['name']),0,'L');
		} else 
		{
		if($order->products[$i]['events_type']=='F')
		{
		$family= '(F)';
		}else
		{
		$family= '';	
		}
		
			
		$pro_name = tep_html_entity_decode($order->products[$i]['name'].'   '.$order->products[$i]['discount_text'].'   '.$order->products[$i]['categories_name'].'   '.$order->products[$i]['concert_time'].'   '.$order->products[$i]['concert_date'].' '.$family);
		//.'   '.$order->products[$i]['concert_venue']
		//$pro_name = iconv('UTF-8', 'windows-1252', $pro_name);	
			
		$pdf->MultiCell(
		$prod_box_length,7,$pro_name,0,'L'
		);
		}
	$pdf->SetY($Y_Table_Position + 4);
	$pdf->SetX($x_indent);
	$pdf->SetFont(PDF_INV_CORE_FONT,'',6);
	if (strlen($prod_attribs) > $prod_attrib_length){
		$pdf->MultiCell($prod_box_length,2,substr($prod_attribs,0,$prod_attrib_length) . ' ... ',0,'L');
		} else {
		$pdf->MultiCell($prod_box_length,2,$prod_attribs,0,'L');
		}

	$pdf->SetFont(PDF_INV_CORE_FONT,'',8);
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(185);//moves the price box
	
    // Product line totals
	$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$pdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
	$pdf->SetFont('DejaVu','',8); //13
	$pdf->MultiCell(15,7,$prod_line_sym.$price,1,'R');
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(140);
	
	#############################################################
	if(ALL_INVOICE_HEADINGS=='yes'){
	$pdf->MultiCell(15,7,$prod_line_sym.$price_ex,1,'R');
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(155);
	$pdf->MultiCell(15,7,$prod_line_sym.$price_inc,1,'R');
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(170);
	$pdf->MultiCell(15,7,$prod_line_sym.$subt_ex,1,'R');
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(185);
	$pdf->MultiCell(15,7,$prod_line_sym.$subt_inc,1,'R');
	}
	#############################################################
	
	$Y_Table_Position += 10;

    // Check for product line overflow
    $item_count++;
    if ($Y_Table_Position>265){
		// Reset indent and box widths
		$prod_box_length = $fixed_prod_box_length;
		$x_indent = $fixed_x_indent;
		// New Page
        $pdf->AddPage();
        // Table position, under Fields Name
        $Y_Table_Position = 165+$vat_shift_y;
        output_table_heading($Y_Table_Position-6);
    }
}


// Subtotals, Tax and Invoice Total
for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
	$pdf->SetFont(PDF_INV_CORE_FONT,'B',8);
	$pdf->SetY($Y_Table_Position + 5);
	$pdf->SetX(9);
	$order->totals[$i]['text'] = preg_replace( $search_for, $change_to, $order->totals[$i]['text']);
// Remove html from Shipping title
	$order->totals[$i]['title'] = str_replace( "<b>", " ", $order->totals[$i]['title']);
	$order->totals[$i]['title'] = str_replace( "</b>", " ", $order->totals[$i]['title']);
	$order->totals[$i]['title'] = str_replace( "<br>", " ", $order->totals[$i]['title']);
	$order->totals[$i]['title'] = str_replace( "&nbsp;", " ", $order->totals[$i]['title']);
// End Remove html from Shipping title

	//$pdf->AddFont('arial','','arial.php');
	//$pdf->AddFont('arial','','arial.php');
	//$pdf->SetFont('arial','',8);
	
	//$pdf->AddFont('arial','','arial.ttf',true);
	//$pdf->SetFont('arial','',8);
	$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$pdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
	$pdf->SetFont('DejaVu','',8);

	$pdf->MultiCell(177,5, $order->totals[$i]['title'].' ',0,'R');
	$pdf->SetY($Y_Table_Position + 5);
	$pdf->SetX(186);
	$pdf->MultiCell(15,5,$order->totals[$i]['text'],0,'R');
	$Y_Table_Position += 5;
}


	// Set PDF metadata
	$pdf->SetTitle(PDF_META_TITLE);
	$pdf->SetSubject(PDF_META_SUBJECT . $order_id);
	$pdf->SetAuthor(STORE_OWNER);
     
  	// PDF created
    function safe_filename ($filename) {
    $search = array(
    '/ß/',
    '/ä/','/Ä/',
    '/ö/','/Ö/',
    '/ü/','/Ü/',
    '([^[:alnum:]._])' 
    );
    $replace = array(
    'ss',
    'ae','Ae',
    'oe','Oe',
    'ue','Ue',
    '_'
    );
    
    // Return a safe filename, lowercased and suffixed with invoice number.
    return strtolower(preg_replace($search,$replace,$filename));
}

    $file_name = safe_filename(STORE_NAME);
    $file_name .= "_invoice_" . $order_id . ".pdf";
    $mode = (FORCE_PDF_INVOICE_DOWNLOAD == 'true') ? 'D' : 'I';
	
    // What do we do? display inline or force download  
	$pdf->Output($file_name , $mode);
	ob_end_flush();
?>

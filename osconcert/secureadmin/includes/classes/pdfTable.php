<?php

/*
  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
  http://www.openfreeway.org

Copyright 2007 ZacWare Pty. Ltd
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

	require("tfpdf/tfpdf.php");

class pdfTable {
	var $pdf;
	var $width;
	var $height;
	var $left_margin;
	var $top_margin;
	var $right_margin;
	var $bottom_margin;
	var $headers;
	var $headerImage;
	var $tableheaders;
	var $styles;
	var $posx;
	var $posy;
	var $newpage;
	var $page;
	var $orient;
	
	function __construct($page="A4",$orient="p"){
		$this->pdf=new tFPDF($orient,"pt",$page);
		$this->pdf->AliasNbPages();
		$this->pdf->SetAutoPageBreak(false);
		$this->page=$page;
		$this->orient=$orient;
	}
	function pdfInit(){
		if ($this->page=="A4"){
			$w=595.28-($this->left_margin+$this->right_margin);
			$h=841.89-($this->top_margin+$this->bottom_margin);
		}
		if ($this->orient=="l"){
			$this->width=$h;
			$this->height=$w;
		} else {
			$this->width=$w;
			$this->height=$h;
		}
		$this->AddStyle("default","color:#000000;bgcolor:#FFFFFF;font:DejaVu;size:11");
		$headers=array(	"text"=>"",
						"style"=>"default",
						"width"=>"100%",
						"height"=>12
						);
		$tableheaders=array(	"text"=>array(),
						"style"=>"default",
						"height"=>11,
						);
		$this->posx=$this->left_margin;
		$this->posy=$this->top_margin;
		$this->newpage=true;
		$params=$this->GetStyleParams("default");
			$this->pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
			$this->pdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
		$this->pdf->SetFont($params["font"],$params["style"],$params["size"]);
		$this->SetTextColor($params["color"]);
		$this->SetBackColor($params["bgcolor"]);
		$this->styles=array();
	}
	function OutputHeaderImage(){
	
		$headerImage=&$this->headerImage;
		  if ($headerImage["file"]==""){return;}
			if(file_exists($headerImage["file"])){
			 if(pathinfo($headerImage["file"], PATHINFO_EXTENSION) =='gif' ||  pathinfo($headerImage["file"], PATHINFO_EXTENSION)=='jpg') {

		   $start_x = $this->posx;
		   $start_y = $this->posy;
		    $image_height=$headerImage["height"];
	    	$image_width=$headerImage["width"];
			$this->pdf->SetXY($this->posx,$this->posy);
		  
		   $this->pdf->Image($headerImage['file'], $this->posx, $this->posy + 5, $image_width,	$image_height ) ;
	
		   
		   $this->posx+=$image_width;
		   $this->posy+=$image_height;
			}else{return;}
			}else{return;}//end of if file exists
				}
	
	
	
	
	
	
	
	function OutputHeader(){
		$headers=&$this->headers;
		if ($headers["text"]=="") return;
		$lines=preg_split("/\t/",$headers["text"]);
		$cols=$headers["cols"];
		$colwidth=($headers["width"]=="100%"?$this->width:$headers["width"]);
		if ($cols>1){
			$colwidth=$colwidth/$cols;
		}
		$params=$this->GetStyleParams($headers["style"]);
		$this->pdf->SetFont($params["font"],$params["style"],$params["size"]);
		$this->SetTextColor($params["color"]);
		$this->SetBackColor($params["bgcolor"]);
		$col=1;
		for ($icnt=0;$icnt<sizeof($lines);$icnt++){
			$this->pdf->SetXY($this->posx,$this->posy);
			$lines[$icnt]=str_replace("&nbsp;"," ",$lines[$icnt]);
			$this->pdf->Cell($colwidth, $headers["height"], $lines[$icnt] ,0,0,"L","1");
			$col++;
			if ($col>$cols){
				$this->posx=$this->left_margin;
				$this->posy+=$headers["height"];
				$col=1;
			} else {
				$this->posx+=$colwidth;
			}
		}
		if ($col<=$cols){
			$this->posy+=$headers["height"];
		}
	}
	function OutputRow(&$cols,$height){
		if (($this->posy+$height)>=$this->height) $this->newpage=true;

		if ($this->newpage) $this->NewPage();

		for ($icnt=0;$icnt<sizeof($cols);$icnt++){
			$params=$this->GetStyleParams($cols[$icnt]['style']);
			$w=($cols[$icnt]["width"]=="100%"?$this->width:$cols[$icnt]["width"]);
			$y=$this->posy;
			if ($cols[$icnt]['valign']=="M") {
				$y+=($height-$this->pdf->GetStringWidth($cols[$icnt]["text"]))/2;
			} else if ($cols[$icnt]['valign']=="B") {
				$y+=($height-$this->pdf->GetStringWidth($cols[$icnt]["text"]));
			}
			$this->pdf->SetXY($this->posx,$this->posy);
			$this->pdf->SetFont($params["font"],$params["style"],$params["size"]);
			$this->SetTextColor($params["color"]);
			$border=0;
			if (isset($params["border-color"])){
				$this->SetForeColor($params["border-color"]);
				$border=1;
			}
			$this->SetBackColor($params["bgcolor"]);
			$this->pdf->Cell($w, $height, $cols[$icnt]['text'] ,$border,0,$cols[$icnt]['align'],"1");
			$this->posx+=$w;
		}
		$this->posx=$this->left_margin;
		$this->posy+=$height;
	}
	function OutputRowMultiple(&$cols,$height){
		$no_rows=0;
		for ($icnt=0;$icnt<sizeof($cols);$icnt++){
			$text=preg_split("/\n/",$cols[$icnt]['text']);
			$cur_rows=sizeof($text);
			if ($cur_rows>$no_rows) $no_rows=$cur_rows;
		}
		$final_height=$no_rows*$height;
		if (($this->posy+$final_height)>=$this->height) $this->newpage=true;
		if ($this->newpage) $this->NewPage();
		$this->posx=$this->left_margin;
		for ($icnt=0;$icnt<sizeof($cols);$icnt++){
			$params=$this->GetStyleParams($cols[$icnt]['style']);
			$w=($cols[$icnt]["width"]=="100%"?$this->width:$cols[$icnt]["width"]);
			/*$y=$this->posy;
			if ($cols[$icnt]['valign']=="M") {
				$y+=($height-$this->pdf->GetStringWidth($cols[$icnt]["text"]))/2;
			} else if ($cols[$icnt]['valign']=="B") {
				$y+=($height-$this->pdf->GetStringWidth($cols[$icnt]["text"]));
			}*/
			
			$this->pdf->SetFont($params["font"],$params["style"],$params["size"]);
			$this->SetTextColor($params["color"]);
			$border=0;
			if (isset($params["border-color"])){
				$this->SetForeColor($params["border-color"]);
				$border=1;
			}
			$this->SetBackColor($params["bgcolor"]);
			if ($cols[$icnt]['text']=="") $cols[$icnt]['text']=" ";
			$text=preg_split("/\n/",$cols[$icnt]['text']);
			if (sizeof($text)<$no_rows){
				$cols[$icnt]['text'].=str_repeat("\n",($no_rows-sizeof($text))+1);
			}
			$this->pdf->SetXY($this->posx,$this->posy);
			$this->pdf->MultiCell($w, $height, $cols[$icnt]['text'] ,$border,$cols[$icnt]['align'],"1");
			$this->posx+=$w;
		}
		$this->posx=$this->left_margin;
		$this->posy+=$final_height;
		$this->pdf->SetXY($this->posx,$this->posy);
		$this->pdf->Cell($this->width, 5, " " ,0,0,"L","1");
		$this->posx=$this->left_margin;
		$this->posy+=5;
	}
	function NewPage(){
		$this->newpage=false;
		$this->posx=$this->left_margin;
		$this->posy=$this->top_margin;
		$this->pdf->AddPage();
		$this->OutputHeader();
		$this->DrawGap(10);
		$this->OutputHeaderImage();
		$this->DrawGap(10);

		if (sizeof($this->tableheaders["text"])>0){
			$this->OutputRow($this->tableheaders["text"],15);
		}
		$this->posx=$this->left_margin;
	}
	function OutputHeaderRow(){
		$headers=&$this->tableheaders;
		if ($headers["text"]=="") return;
		$cols=&$headers["cols"];
		for ($icnt=0;$icnt<sizeof($cols);$icnt++){
			$params=$this->GetStyleParams($cols[$icnt]['style']);
			$w=($cols[$icnt]["width"]=="100%"?$this->width:$cols[$icnt]["width"]);
			$this->pdf->SetXY($this->posx,$this->posy);
			$this->pdf->SetFont($params["font"],$params["style"],$params["size"]);
			$this->SetTextColor($params["color"]);
			$this->SetBackColor($params["bgcolor"]);
			$this->pdf->Cell($w, $headers["height"], $cols[$icnt]['text'] ,0,0,$cols[$icnt]['align'],"1");
			$this->posx+=$w;
		}
		$this->posy+=$headers["height"];
	}
	function DrawLine($height=0){
		if (($this->posy+$height)>=$this->height) $this->newpage=true;
		if ($this->newpage) $this->NewPage();

		$this->posx=$this->left_margin;
		$this->SetTextColor("#000000");
		$this->SetForeColor("#000000");
		$this->SetBackColor("#FFFFFF");
		$this->pdf->Line($this->posx,$this->posy,$this->posx+$this->width,$this->posy);
		$this->posy+=2;
	}
	function DrawGap($height){
		if (($this->posy+$height)>=$this->height) $this->newpage=true;
		if ($this->newpage) {
			$this->NewPage();
			return;
		}
		$this->posy+=$height;
		$this->posx=$this->left_margin;
	}
	function Render($filename,$mode='I'){
		if (file_exists(DIR_FS_CATALOG . "images/" . $filename)){
			unlink(DIR_FS_CATALOG . "images/" . $filename);
		}
		$this->pdf->output(DIR_FS_CATALOG . "images/" . $filename,$mode);
		unset($this->pdf);
	}
	function SetForeColor($color){
		$red=hexdec(substr($color,1,2));
		$green=hexdec(substr($color,3,2));
		$blue=hexdec(substr($color,5,2));
		$this->pdf->SetDrawColor($red,$blue,$green);
	}
	function SetTextColor($color){
		$red=hexdec(substr($color,1,2));
		$green=hexdec(substr($color,3,2));
		$blue=hexdec(substr($color,5,2));
		$this->pdf->SetTextColor($red,$blue,$green);
	}
	function SetBackColor($color){
		$red=hexdec(substr($color,1,2));
		$green=hexdec(substr($color,3,2));
		$blue=hexdec(substr($color,5,2));
		$this->pdf->SetFillColor($red,$green,$blue);
	}
	function AddStyle($key,$value){
		$this->styles[$key]=$value;
	}
	function RemoveStyle($key){
		if (isset($this->styles[$key])){
			unset($this->styles[$key]);
		}
	}
	function ImageShow($file, $x=0, $y=0, $w=0, $h=0)
{
$this->pdf->Image($file, $x, $y, $w, $h);

}
	function &GetStyleParams($key){
		$result=array();
		$styles=$this->styles;
		if (!isset($styles[$key]) || $styles[$key]=="") $key="default";
		$splt=preg_split("/;/",$styles[$key]);
		for ($icnt=0;$icnt<sizeof($splt);$icnt++){
			if ($splt[$icnt]!=""){
				$splt_1=preg_split("/:/",$splt[$icnt]);
				$result[$splt_1[0]]=$splt_1[1];
			}
		}
		return $result;
	}
}
?>
<?php 

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<?php

define('IN_CB',true);
	
// Including all required classes
require_once('class/BCGFontFile.php');
require_once('class/BCGColor.php');
require_once('class/BCGDrawing.php');

// Including the barcode technology
require_once('class/BCGcode128.barcode.php');


function create_bar_image($bar_text,$bar_filename){

	// Loading Font
	$font = new BCGFontFile('barcode/font/Arial.ttf', 10);

	// The arguments are R, G, B for color.
	$color_black = new BCGColor(0, 0, 0);
	$color_white = new BCGColor(255, 255, 255);

	$drawException = null;
	try {
		$code = new BCGcode128();
		$code->setScale(2); // Resolution
		$code->setThickness(20); // Thickness
		$code->setForegroundColor($color_black); // Color of bars
		$code->setBackgroundColor($color_white); // Color of spaces
		$code->setFont($font); // Font (or 0)
        $code->setStart('B',$bar_text);
		$code->parse($bar_text);// Text
		 
	} catch(Exception $exception) {
		$drawException = $exception;
	}

	/* Here is the list of the arguments
	1 - Filename (empty : display on screen)
	2 - Background color */
	$drawing = new BCGDrawing($bar_filename, $color_white);
	if($drawException) {
		$drawing->drawException($drawException);
	} else {
		$drawing->setBarcode($code);
		$drawing->setRotationAngle(90);
		$drawing->draw();
	}

	// Draw (or save) the image into PNG format.
	$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
}
?>
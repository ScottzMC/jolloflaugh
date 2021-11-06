<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  
  

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
  http://www.openfreeway.org

Copyright 2007 ZacWare Pty. Ltd
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 
define('ERROR_FILETYPE_NOT_ALLOWED','Invalid Image extension detected');
define('WARNING_NO_FILE_UPLOADED','Image not uploaded');

  class uploadNew {
    var $file, $filename, $extensions, $tmp_filename, $maxSize;

    function uploadNew($file = '', $new_name='',$maxSize,$extensions = 'jpg,jpeg,png,gif') {
	  $this->file=$file;
      $this->destination=$destination;
      $this->new_name=$new_name;
      $this->extensions=str_replace(',','|\\.',$extensions);
      $this->maxSize=$maxSize;
    }

    function parse() {
        $file = array('name' => $_FILES[$this->file]['name'],
                      'type' => $_FILES[$this->file]['type'],
                      'size' => $_FILES[$this->file]['size'],
                      'tmp_name' => $_FILES[$this->file]['tmp_name']);

        if ($_FILES[$this->file]['error']==2){
            return -3;
        }
        if (!(tep_not_null($file['tmp_name']) && ($file['tmp_name'] != 'none') && is_uploaded_file($file['tmp_name']))) {
            return -2;
        }
        if ($this->extensions!='' && preg_match("/\." . $this->extensions . "$/i",$file['name'],$pat)==0) {
            return -1;
        }
        
        if ($_FILES[$this->file]['size']>$this->maxSize){
            return -3;
        }
        $this->extension=$pat[0];
        if ($this->new_name!=''){
            $this->filename=$this->new_name . $pat[0];
        } else {
            $this->filename=$file['name'];
        }

        $this->tmp_filename=$file['tmp_name'];
    }
    function resizeAndSave($sizeParams,$rootPath=DIR_WS_IMAGES){
        for ($icnt=0,$n=count($sizeParams);$icnt<$n;$icnt++){
            if (!tep_resize_image($this->tmp_filename,$rootPath . $sizeParams[$icnt]['path'] . $this->filename,$this->extension,$sizeParams[$icnt]['width'])) {
                return -2;
            }
        }
        return 0;
    }
    function save($destination) {
		  if (!move_uploaded_file($this->file['tmp_name'], $destination . $this->filename)) {
              return -2;
		  }
          return 0;
    }
    static function fileDataExists($fileInput){
        if (isset($_FILES[$fileInput]) && $_FILES[$fileInput]['error']!=4) {
            return true;
        } else {
            return false;
        }
    }
  }

  	function tep_resize_image($inputFilename,$outputFilename,$image_type,$new_mode)
	{
		$imagedata = getimagesize($inputFilename);
		if (!$imagedata) return false;
		$w = $imagedata[0];
		$h = $imagedata[1];

		if ($w>$new_mode || $h>$new_mode){
			if ($h > $w) {
				$new_w = ($new_mode / $h) * $w;
				$new_h = $new_mode;
			} else {
				$new_h = ($new_mode/ $w) * $h;
				$new_w = $new_mode;
			}
		} else {
			$new_w=$w;
			$new_h=$h;
		}
		//$im2 = @ImageCreateTrueColor($new_w, $new_h);
		// call function according to the file type
		switch(strtolower($image_type)){
			case ".jpg":
			case ".jpeg":
				if(@ImageCreateFromJpeg($inputFilename))
					$image = ImageCreateFromJpeg($inputFilename);
				break;
			case ".gif":
				if(@ImageCreateFromGif($inputFilename))
					$image = ImageCreateFromGif($inputFilename);
				break;
			case ".png":
				if(@(ImageCreateFromPng($inputFilename)))
					$image = ImageCreateFromPng($inputFilename);
				break;
		}
		if (!$image) return;

		$im2 = imagecreatetruecolor($new_w, $new_h);
		if (!$im2)  return false;

		$trnprt_indx = imagecolorat($image, 0,127);
		imagefill($im2, 0, 0, $trnprt_indx);
		imagecolortransparent($im2, $trnprt_indx);

		if( (strtolower($image_type) == ".gif") || (strtolower($image_type) == ".png") ){
                // Turn off transparency blending (temporarily)
                imagealphablending($im2, false);

                // Create a new transparent color for image
                $color = imagecolorallocatealpha($im2, 0, 0, 0, 127);

                // Completely fill the background of the new image with allocated color.
                imagefill($im2, 0, 0, $color);
  
                // Restore transparency blending
                imagesavealpha($im2, true);
		}

		imagecopyresampled($im2, $image, 0, 0, 0, 0, $new_w, $new_h, $imagedata[0], $imagedata[1]);

		switch(strtolower($image_type)){
			case ".jpg":
			case ".jpeg":
				@imagejpeg($im2,$outputFilename);
				break;
			case ".gif":
				@imagegif($im2,$outputFilename);
				break;
			case ".png":
				@imagepng($im2,$outputFilename);
				break;
            default:
                return false;
		}
		imagedestroy($im2);
		return true;
	}
?>

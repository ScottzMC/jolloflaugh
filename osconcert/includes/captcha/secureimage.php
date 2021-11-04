<?php 

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<?php
//this are to be testing
    @include_once(DIR_WS_INCLUDES . 'captcha/securimage/securimage.php');
    class clsSecureImage{
        var $enabled=true;
		
        function __construct(){
            if (!class_exists('Securimage')){
                $this->enabled=false;
                return;
            }
        }
        function renderDisplay($id,$reloadLink=true,$audioLink=true){
            // echo '<br><div><img id="' . $id . '_captcha" src="'. tep_href_link('securitykey.php','mode=image&sid=' . md5(uniqid(time()))) . '" style="float:left">';
            // if ($reloadLink){
                // echo '<a href="#" onclick="document.getElementById(\'' . $id . '_captcha\').src = \'' . tep_href_link('securitykey.php','mode=image') . '&\'+ Math.random(); return false">'. tep_image(DIR_WS_IMAGES . 'icons/refresh.gif','Reload the image') . '<br/>';
            // }
            // if ($audioLink){
               // echo '<a href="' . tep_href_link('securitykey.php','mode=audio') . '" >' . tep_image(DIR_WS_IMAGES . 'icons/audio_icon.gif','Play Audio') . '</a>';
            // }
            // echo '</div>';
			
			
			// show captcha HTML using Securimage::getCaptchaHtml()
      //require_once 'securimage.php';
      $options = array();
      //$options['input_name']             = 'ct_captcha'; // change name of input element for form post
	  $options['input_name']             = 'security_code'; // change name of input element for form post
      $options['disable_flash_fallback'] = false; // allow flash fallback

      if (!empty($_SESSION['ctform']['captcha_error'])) {
        // error html to show in captcha output
        $options['error_html'] = $_SESSION['ctform']['captcha_error'];
      }

      echo "<div id='captcha_container_1'>\n";
      echo Securimage::getCaptchaHtml($options);
      echo "\n</div>\n";

      /*
      // To render some or all captcha components individually
      $options['input_name'] = 'ct_captcha_2';
      $options['image_id']   = 'ct_captcha_2';
      $options['input_id']   = 'ct_captcha_2';
      $options['namespace']  = 'captcha2';

      echo "<br>\n<div id='captcha_container_2'>\n";
      echo Securimage::getCaptchaHtml($options, Securimage::HTML_IMG);

      echo Securimage::getCaptchaHtml($options, Securimage::HTML_ICON_REFRESH);
      echo Securimage::getCaptchaHtml($options, Securimage::HTML_AUDIO);

      echo '<div style="clear: both"></div>';

      echo Securimage::getCaptchaHtml($options, Securimage::HTML_INPUT_LABEL);
      echo Securimage::getCaptchaHtml($options, Securimage::HTML_INPUT);
      echo "\n</div>";
      */
        }
        function validate($postKey){
			
			global $_COOKIE;
            $checkKey = $_COOKIE['fetest']; 
			
            if ($checkKey!='' && $checkKey == trim($postKey)) {
                 setcookie('fetest','');
                 return true;
            } else {
                return false;
            }
        }
        // function refreshImage(){
            // $img = new securimage();
            // $img->show(); // alternate use:  $img->show('/path/to/background.jpg');
        // }
        // function getAudio(){
            // $img = new Securimage();

            // header('Content-type: audio/x-wav');
            // header('Content-Disposition: attachment; name="securimage.wav"');
            // header('Cache-Control: no-store, no-cache, must-revalidate');
            // header('Expires: Sun, 1 Jan 2029 12:00:00 GMT');
            // header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');

            // echo $img->getAudibleCode();
        // }
    }
?>
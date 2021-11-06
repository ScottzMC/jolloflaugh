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
////
// This funstion validates a plain text password with an
// encrpyted password
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

  function tep_validate_password($plain, $encrypted,$type=ENCRYPTION_STYLE) {
  	$valid_password=false;
    if (tep_not_null($plain) && tep_not_null($encrypted)) {
// split apart the hash / salt
      $stack = explode(':', $encrypted);

      if (sizeof($stack) != 2) return false;
      if ($type=="V"){
	  	if (md5(md5($plain) . $stack[1]) == $stack[0]) {
	        $valid_password=true;
		}
      } else {
	  	if (md5($stack[1] . $plain) == $stack[0]){
		  	$valid_password=true;
		}
	  }
    }
    return $valid_password;
  }

////
// This function makes a new password from a plaintext password. 
  function tep_encrypt_password($plain,$type=ENCRYPTION_STYLE) {
    $password = '';

    for ($i=0; $i<10; $i++) {
      $password .= tep_rand();
    }

   
    $salt = substr(md5($password), 0, 2);
	if ($type=="V"){
	    $password = md5(md5($plain) . $salt) . ':' . $salt;
	} else {
		$password = md5($salt . $plain) . ':' . $salt;
	}

    return $password;
  }
?>

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

  class objectInfo {
// class constructor
    function __construct($object_array) {
		  $this->objectInfo($object_array);
		}

    function objectInfo($object_array) {
      foreach($object_array as $key => $value) {
        $this->$key = tep_db_prepare_input($value);
      }
    }
  }
?>

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

  function tep_get_category_heading_title($category_id, $language_id) {
    $category_query = tep_db_query("select categories_heading_title from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . tep_db_input($category_id) . "' and language_id = '" . tep_db_input($language_id) . "'");
    $category = tep_db_fetch_array($category_query);
    return $category['categories_heading_title'];
  }

  function tep_get_category_description($category_id, $language_id) {
    $category_query = tep_db_query("select categories_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . tep_db_input($category_id) . "' and language_id = '" . tep_db_input($language_id) . "'");
    $category = tep_db_fetch_array($category_query);
    return $category['categories_description'];
  }

?>

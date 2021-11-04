<?php
/*

 osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 

Freeway eCommerce
http://www.openfreeway.org
Copyright (c) 2007 ZacWare

Released under the GNU General Public License
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();


////
// Sets the status of a featured product


  function tep_set_featured_status($featured_id, $status) {
    return tep_db_query("update " . TABLE_FEATURED . " set status = '" . $status . "', date_status_change = now() where featured_id = '" . (int)$featured_id . "'");
  }

////
// Auto expire featured products
  function tep_expire_featured() 
  {
    $featured_query = tep_db_query("select featured_id from " . TABLE_FEATURED . " where status = '1' and now() >= expires_date and expires_date > 0");
    if (tep_db_num_rows($featured_query)) 
	{
      while ($featured = tep_db_fetch_array($featured_query)) 
	  {
        tep_set_featured_status($featured['featured_id'], '0');
      }
    }
  }
  //
  
	function tep_set_featured_cats_status($categories_id, $status) 
	{
	return tep_db_query("update " . TABLE_CATEGORIES . " set categories_status = '" . $status . "', last_modified = now() where categories_id = '" . (int)$categories_id . "'");
	}
  //
  
  function tep_expire_featured_cats() 
  {
    $unixTime = time();
	$unix = $unixTime;
	$unixplus=$unix+23*60*60;
	//$unixminus=$unix-19*60*60; 
	$unixminus=$unix-TIME_CATEGORIES_EXPIRE*60*60;
	
	$featured_cats_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where categories_status = '1' and ".$unixminus." >= concert_date_unix and concert_date_unix > 0");
    if (tep_db_num_rows($featured_cats_query)) 
	{
      while ($featured_cats = tep_db_fetch_array($featured_cats_query)) 
	  {
        tep_set_featured_cats_status($featured_cats['categories_id'], '0');
      }
    }
	//echo $unixminus;
	//echo gmdate("Y-m-d\TH:i:s\Z", $unix);
	
  }
  
  function tep_expire_featured_time() 
  {
    $unixTime = time();
	$unix = $unixTime;
	$unixplus=$unix+23*60*60;
	//$unixminus=$unix-19*60*60; 
	$unixminus=$unix-TIME_CATEGORIES_EXPIRE*60*60;
	
	$featured_time_query = tep_db_query("select * from " . TABLE_CATEGORIES . " where parent_id>0 AND categories_status = '1' and ".$unix." >= concert_date_unix and concert_date_unix > 0");
    if (tep_db_num_rows($featured_time_query)) 
	{
      while ($featured_time = tep_db_fetch_array($featured_time_query)) 
	  {
        tep_set_featured_cats_status($featured_time['categories_id'], '0');
      }
    }
	//echo $unixminus;
	//echo gmdate("Y-m-d\TH:i:s\Z", $unix);
	
  }
  
  
  function tep_set_products_status($products_id, $products_status) 
	{
	return tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '" . $products_status . "', products_last_modified = now() where products_id = '" . (int)$products_id . "'");
	}
  //
  
  function tep_expire_products() 
  {
	//$expires=" and p.products_date_available >'" . $serverDate . "' ";
	
	$expire_products_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_status = '1' and now() >= products_date_available and products_date_available > 0");
    if (tep_db_num_rows($expire_products_query)) 
	{
      while ($expire_products = tep_db_fetch_array($expire_products_query)) 
	  {
        tep_set_products_status($expire_products['products_id'], '0');
      }
    }
  }
?>

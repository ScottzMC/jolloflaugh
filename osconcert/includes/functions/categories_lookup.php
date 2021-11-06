<?php
/*
Freeway eCommerce
http://www.openfreeway.org
Copyright (c) 2007 ZacWare

  Categories Functions
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

function tep_get_products_category_id($products_id) 
	{
    global $FSESSION;

    $the_products_category_query = tep_db_query("select products_id, categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . tep_db_input($products_id) . "'" . " order by products_id,categories_id");
    $the_products_category = tep_db_fetch_array($the_products_category_query);

    return $the_products_category['categories_id'];
	}
  
function tep_get_parent_id($the_products_category) 
	{
    global $FSESSION;
    $the_parent_id_query= tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id= '" . tep_db_input($the_products_category) . "'");

    $the_parent_id = tep_db_fetch_array($the_parent_id_query);
    return $the_parent_id['parent_id'];
	}
	
function tep_get_parent_cat_id($the_parent_id) 
	{
    global $FSESSION;
    $the_parent_cat_id_query= tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id= '" . tep_db_input($the_parent_id) . "'");

    $the_parent_cat_id = tep_db_fetch_array($the_parent_cat_id_query);
    return $the_parent_cat_id['parent_id'];
	}

function tep_get_plan_id($the_parent_id) 
	{
    global $FSESSION;
    $the_plan_id_query= tep_db_query("select plan_id from " . TABLE_CATEGORIES . " where categories_id= '" . tep_db_input($the_parent_id) . "'");

    $the_plan_id = tep_db_fetch_array($the_plan_id_query);
    return $the_plan_id['plan_id'];
	}

function tep_is_unix($the_parent_id) 
	{
    global $FSESSION;
    $is_unix_query= tep_db_query("select concert_date_unix from " . TABLE_CATEGORIES . " where categories_id= '" . tep_db_input($the_parent_id) . "'");

    $is_unix = tep_db_fetch_array($is_unix_query);
    return $is_unix['concert_date_unix'];
	}
  
function tep_get_categories_name($the_products_category) 
	{
	global $FSESSION;
	$the_categories_name_query= tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id= '" . tep_db_input($the_products_category) . "' and language_id= '" . (int)$FSESSION->languages_id . "'");

	$the_categories_name = tep_db_fetch_array($the_categories_name_query);
	return $the_categories_name['categories_name'];
	}
	
function tep_get_categories_heading_title($the_products_category) 
	{
	global $FSESSION;
	$the_categories_title_query= tep_db_query("select categories_heading_title from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id= '" . tep_db_input($the_products_category) . "' and language_id= '" . (int)$FSESSION->languages_id . "'");

	$the_categories_title = tep_db_fetch_array($the_categories_title_query);
	return $the_categories_title['categories_heading_title'];
	}
	
function tep_get_categories_parent_name($the_parent_id) 
	{
	global $FSESSION;
	$the_categories_parent_name_query= tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id= '" . tep_db_input($top_cat_id) . "' and language_id= '" . (int)$FSESSION->languages_id . "'");

	$the_categories_parent_name = tep_db_fetch_array($the_categories_parent_name_query);
	return $the_categories_parent_name['categories_name'];
	}

function tep_get_categories_venue($the_products_category) 
	{
	global $FSESSION;
	$the_categories_venue_query= tep_db_query("select concert_venue from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id= '" . tep_db_input($the_products_category) . "' and language_id= '" . (int)$FSESSION->languages_id . "'");

	$the_categories_venue = tep_db_fetch_array($the_categories_venue_query);
	return $the_categories_venue['concert_venue'];
	}
  
function tep_get_categories_date($the_products_category) 
	{
	global $FSESSION;
	$the_categories_date_query= tep_db_query("select concert_date from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id= '" . tep_db_input($the_products_category) . "' and language_id= '" . (int)$FSESSION->languages_id . "'");

	$the_categories_date = tep_db_fetch_array($the_categories_date_query);
	return $the_categories_date['concert_date'];
	}
  
function tep_get_categories_time($the_products_category) 
	{
	global $FSESSION;
	$the_categories_time_query= tep_db_query("select concert_time from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id= '" . tep_db_input($the_products_category) . "' and language_id= '" . (int)$FSESSION->languages_id . "'");

	$the_categories_time = tep_db_fetch_array($the_categories_time_query);
	return $the_categories_time['concert_time'];
	}
	
function tep_get_categories_description($the_products_category) 
	{
	global $FSESSION;
	$the_categories_description_query= tep_db_query("select categories_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id= '" . tep_db_input($the_products_category) . "' and language_id= '" . (int)$FSESSION->languages_id . "'");

	$the_categories_description = tep_db_fetch_array($the_categories_description_query);
	return $the_categories_description['categories_description'];
	}

function categories_lookup()
	{
    
    global $id;
    
	$heading_name = $heading_venue = $heading_date = $heading_time = $heading_title = $heading_categories_description ='';
    
	//we get the category ID where this product resides
	$cat_id=tep_get_products_category_id($id);
	//we can get the category ID of the parent category if it exists.
	$my_parent_id=tep_get_parent_id($cat_id);
	//we get the top category ID 
	$top_cat_id=tep_get_parent_cat_id($my_parent_id);
		$my_plan_id=tep_get_plan_id($cat_id);
		$heading_categories_description=tep_get_categories_description($cat_id);
		//$is_unix=tep_is_unix($key);

	if($my_plan_id==9)
	{
		if(($my_parent_id>0)||($top_cat_id>0))//The products are in a Sub Category Sub Category
		{
		$heading_name=tep_get_categories_name($my_parent_id).' '.tep_get_categories_name($cat_id);
		$heading_venue=tep_get_categories_venue($my_parent_id);
		$categories_date=tep_get_categories_date($my_parent_id);
		$categories_time=tep_get_categories_time($my_parent_id);
		}
		else
		{		
		//$heading_name=tep_get_categories_name($my_parent_id);
		$heading_name=tep_get_categories_name($cat_id);
		$heading_venue=tep_get_categories_venue($cat_id);
		$categories_date=tep_get_categories_date($cat_id);
		$categories_time=tep_get_categories_time($cat_id);
		}	
	}	
	else
	{
		if($my_parent_id>0)//The products are in a Sub Category
		{
			//if( !empty( $top_cat_id ) && $top_cat_id === true )//if the sub cat has a top cat
			if($top_cat_id>0)
			{ 
			$key=$top_cat_id;
			}
			else // if no top cat
			{
			$key=$my_parent_id;	
			}
		}else // if none of the above
		{	
		$key=$cat_id;
		}		
		$heading_name=tep_get_categories_name($key);
		$heading_venue=tep_get_categories_venue($key);
		$categories_date=tep_get_categories_date($key);
		$categories_time=tep_get_categories_time($key);
	}	
		require(DIR_WS_FUNCTIONS.'/date_formats.php');	

    return array($heading_name,$heading_venue,$heading_date, $heading_time,$heading_title,$heading_categories_description);    
	}  
?>
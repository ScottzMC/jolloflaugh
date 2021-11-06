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
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
function newsdesk_draw_file_field($name, $parameters = '', $required = false) {
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
$field = tep_draw_input_field($name, '', $parameters, $required, 'file');

return $field;

}

// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// function newsdesk_get_path($current_category_id = '') 
// {
// // -------------------------------------------------------------------------------------------------------------------------------------------------------------
// global $cPath_array;

// if ($current_category_id == '') {
	// $cPath_new = implode('_', $cPath_array);
// } else {
	// if (sizeof($cPath_array) == 0) {
		// $cPath_new = $current_category_id;
	// } else {
		// $cPath_new = '';
		// $last_category_query = tep_db_query("select parent_id from " . TABLE_NEWSDESK_CATEGORIES . " where categories_id = '" . tep_db_input($cPath_array[(sizeof($cPath_array)-1)]) . "'");
		// $last_category = tep_db_fetch_array($last_category_query);
		// $current_category_query = tep_db_query("select parent_id from " . TABLE_NEWSDESK_CATEGORIES . " where categories_id = '" . tep_db_input($current_category_id) . "'");
		// $current_category = tep_db_fetch_array($current_category_query);
		// if ($last_category['parent_id'] == $current_category['parent_id']) {
			// for ($i = 0, $n = sizeof($cPath_array) - 1; $i < $n; $i++) {
				// $cPath_new .= '_' . $cPath_array[$i];
			// }
		// } else {
			// for ($i = 0, $n = sizeof($cPath_array); $i < $n; $i++) {
				// $cPath_new .= '_' . $cPath_array[$i];
			// }
		// }
		// $cPath_new .= '_' . $current_category_id;
		// if (substr($cPath_new, 0, 1) == '_') {
			// $cPath_new = substr($cPath_new, 1);
		// }
	// }
// }

// return 'cPath=' . $cPath_new;

// }


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
function newsdesk_output_generated_category_path($id, $id1=0) {
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
$calculated_category_path_string = '';
$last_category=array();
$sql_qu="select admin_groups_name from " . TABLE_ADMIN_GROUPS . " where admin_groups_id='".tep_db_input($id1) ."'";

$last_category_query = tep_db_query($sql_qu);
		$last_category = tep_db_fetch_array($last_category_query);
		
		$calculated_category_path_string=$last_category['admin_groups_name'];

return $calculated_category_path_string;

}


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// function newsdesk_get_newsdesk_article_name($product_id, $language_id = 0) {
// // -------------------------------------------------------------------------------------------------------------------------------------------------------------
// global $FSESSION;

// if ($language_id == 0) $language_id = $FSESSION->languages_id;
// $product_query = tep_db_query("select newsdesk_article_name from " . TABLE_NEWSDESK_DESCRIPTION . " where newsdesk_id = '" . tep_db_input($product_id) . "' and language_id = '" . tep_db_input($language_id) . "'");
// $product = tep_db_fetch_array($product_query);

// return $product['newsdesk_article_name'];

// }


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
function newsdesk_get_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false) {

global $FSESSION;

if (!is_array($category_tree_array)) $category_tree_array = array();
//if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);

if ($include_itself) {
	$category_query = tep_db_query("select admin_groups_id,admin_groups_name from " . TABLE_ADMIN_GROUPS ." order by  admin_groups_id");
	$category = tep_db_fetch_array($category_query);
	$category_tree_array[] = array('id' =>$category['admin_groups_id'], 'text' => $category['admin_groups_name']);
	}

$categories_query = tep_db_query("select admin_groups_id,admin_groups_name from " . TABLE_ADMIN_GROUPS ." order by  admin_groups_id");

while ($categories = tep_db_fetch_array($categories_query)) {
	if ($exclude != $categories['admin_groups_id']) $category_tree_array[] = array('id' => $categories['admin_groups_id'], 'text' =>$categories['admin_groups_name']);
		
}

return $category_tree_array;

}


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// function newsdesk_get_category_name($category_id, $language_id) {
// // -------------------------------------------------------------------------------------------------------------------------------------------------------------
// $category_query = tep_db_query("select categories_name from " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " where categories_id = '" . tep_db_input($category_id) . "' and language_id = '" . tep_db_input($language_id) . "'");
// $category = tep_db_fetch_array($category_query);

// return $category['categories_name'];

// }


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// function newsdesk_get_newsdesk_article_description($product_id, $language_id) {
// // -------------------------------------------------------------------------------------------------------------------------------------------------------------
// $product_query = tep_db_query("select newsdesk_article_description from " . TABLE_NEWSDESK_DESCRIPTION . " where newsdesk_id = '" . tep_db_input($product_id) . "' and language_id = '" . tep_db_input($language_id) . "'");
// $product = tep_db_fetch_array($product_query);

// return $product['newsdesk_article_description'];

// }


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// function newsdesk_get_newsdesk_article_shorttext($product_id, $language_id) {
// // -------------------------------------------------------------------------------------------------------------------------------------------------------------
// $product_query = tep_db_query("select newsdesk_article_shorttext from " . TABLE_NEWSDESK_DESCRIPTION . " where newsdesk_id = '" . tep_db_input($product_id) . "' and language_id = '" . tep_db_input($language_id) . "'");
// $product = tep_db_fetch_array($product_query);

// return $product['newsdesk_article_shorttext'];

// }


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// Count how many products exist in a category
// TABLES: products, products_to_categories, categories
function newsdesk_products_in_category_count($categories_id, $include_deactivated = false) {
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
$products_count = 0;

if ($include_deactivated) {
	$products_query = tep_db_query("select count(*) as total from " . TABLE_ADMIN . " p, " . TABLE_ADMIN_GROUPS . " p2c where p.admin_groups_id = p2c.admin_groups_id and p.admin_groups_id = '" . tep_db_input($categories_id) . "'");
	} else {
	$products_query = tep_db_query("select count(*) as total from " . TABLE_ADMIN . " p, " . TABLE_ADMIN_GROUPS . " p2c where p.admin_groups_id = p2c.admin_groups_id and p.admin_groups_id = '" . tep_db_input($categories_id) . "'");
}

$products = tep_db_fetch_array($products_query);

$products_count += $products['total'];
$childs_query = tep_db_query("select ag.admin_groups_id from " . TABLE_ADMIN . " a,".TABLE_ADMIN_GROUPS." ag where a.admin_groups_id = '" . tep_db_input($categories_id) . "'");
//$childs_query = tep_db_query("select ag.admin_groups_id from " . TABLE_NEWSDESK_CATEGORIES . " where parent_id = '" . tep_db_input($categories_id) . "'");
if (tep_db_num_rows($childs_query)) {
	while ($childs = tep_db_fetch_array($childs_query)) {
		$products_count += newsdesk_products_in_category_count($childs['admin_groups_id'], $include_deactivated);
	}
}

return $products_count;

}


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// Count how many subcategories exist in a category
// TABLES: categories
function newsdesk_childs_in_category_count($categories_id) {
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
$categories_count = 0;

//$categories_query = tep_db_query("select a.admin_groups_id from " . TABLE_ADMIN . " a,".TABLE_ADMIN_GROUPS." ag where a.admin_groups_id = '" . tep_db_input($categories_id) . "'");
$categories_query = tep_db_query("select count(admin_groups_id) as cate from " . TABLE_ADMIN . " where admin_groups_id = '" . tep_db_input($categories_id) . "'");
if($categories = tep_db_fetch_array($categories_query)) {
	
	$categories_count =$categories['cate'];
}
//echo $categories_counr;
//exit;

return $categories_count;

}


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// function newsdesk_generate_category_path($id, $from = 'category', $categories_array = '', $index = 0) {
// // -------------------------------------------------------------------------------------------------------------------------------------------------------------
// global $FSESSION;

// if (!is_array($categories_array)) $categories_array = array();

// if ($from == 'product') {
	// $categories_query = tep_db_query("select categories_id from " . TABLE_NEWSDESK_TO_CATEGORIES . " where newsdesk_id = '" . tep_db_input($id) . "'");
	// while ($categories = tep_db_fetch_array($categories_query)) {
	// if ($categories['categories_id'] == '0') {
		// $categories_array[$index][] = array('id' => '0', 'text' => TEXT_TOP);
	// } else {
		// $category_query = tep_db_query("select cd.categories_name, c.parent_id from " . TABLE_NEWSDESK_CATEGORIES . " c, " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$categories['categories_id'] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "'");
		// $category = tep_db_fetch_array($category_query);
		// $categories_array[$index][] = array('id' => $categories['categories_id'], 'text' => $category['categories_name']);
		// if ( (tep_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = newsdesk_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
			// $categories_array[$index] = array_reverse($categories_array[$index]);
		// }
		// $index++;
	// }
	// } elseif ($from == 'category') {
		// $category_query = tep_db_query("select cd.categories_name, c.parent_id from " . TABLE_NEWSDESK_CATEGORIES . " c, " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "'");
		// $category = tep_db_fetch_array($category_query);
		// $categories_array[$index][] = array('id' => $id, 'text' => $category['categories_name']);
		// if ( (tep_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = newsdesk_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
	// }

// return $categories_array;

// }


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// function newsdesk_remove_category($category_id) {
// // -------------------------------------------------------------------------------------------------------------------------------------------------------------
// $category_image_query = tep_db_query("select categories_image from " . TABLE_NEWSDESK_CATEGORIES . " where categories_id = '" . tep_db_input($category_id) . "'");
// $category_image = tep_db_fetch_array($category_image_query);

// $duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_NEWSDESK_CATEGORIES . " where categories_image = '" . tep_db_input($category_image['categories_image']) . "'");
// $duplicate_image = tep_db_fetch_array($duplicate_image_query);

// if ($duplicate_image['total'] < 2) {
	// if (file_exists(DIR_FS_CATALOG_IMAGES . $category_image['categories_image'])) {
		// @unlink(DIR_FS_CATALOG_IMAGES . $category_image['categories_image']);
	// }
// }

// tep_db_query("delete from " . TABLE_NEWSDESK_CATEGORIES . " where categories_id = '" . tep_db_input($category_id) . "'");
// tep_db_query("delete from " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " where categories_id = '" . tep_db_input($category_id) . "'");
// tep_db_query("delete from " . TABLE_NEWSDESK_TO_CATEGORIES . " where categories_id = '" . tep_db_input($category_id) . "'");



// }
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
function newsdesk_remove_product($product_id) {
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
tep_db_query("delete from " . TABLE_ADMIN . " where admin_id = '" . tep_db_input($product_id) . "'");


}


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// Sets the status of a product
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// function newsdesk_set_product_status($newsdesk_id, $status) {
// if ($status == '1') {
	// return tep_db_query("update " . TABLE_NEWSDESK . " set newsdesk_status = '1', newsdesk_last_modified = now() where newsdesk_id = '" . tep_db_input($newsdesk_id) . "'");
// } elseif ($status == '0') {
	// return tep_db_query("update " . TABLE_NEWSDESK . " set newsdesk_status = '0', newsdesk_last_modified = now() where newsdesk_id = '" . tep_db_input($newsdesk_id) . "'");
// } else {
	// return -1;
// }

// }


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// function newsdesk_get_newsdesk_article_url($product_id, $language_id) {
// // -------------------------------------------------------------------------------------------------------------------------------------------------------------
// $product_query = tep_db_query("select newsdesk_article_url from " . TABLE_NEWSDESK_DESCRIPTION . " where newsdesk_id = '" . tep_db_input($product_id) . "' and language_id = '" . tep_db_input($language_id) . "'");
// $product = tep_db_fetch_array($product_query);

// return $product['newsdesk_article_url'];

// }


// // -------------------------------------------------------------------------------------------------------------------------------------------------------------
// function newsdesk_get_products_name($newsdesk_id, $language_id = 0) {
// // -------------------------------------------------------------------------------------------------------------------------------------------------------------
// global $FSESSION;

// if ($language_id == 0) $language_id = $FSESSION->languages_id;
	// $product_query = tep_db_query(
// "select newsdesk_article_name from " . TABLE_NEWSDESK_DESCRIPTION . " where newsdesk_id = '" . tep_db_input($newsdesk_id) . "' and language_id = '" . tep_db_input($language_id) . "'"
	// );
	// $product = tep_db_fetch_array($product_query);

	// return $product['newsdesk_article_name'];
// }
// -------------------------------------------------------------------------------------------------------------------------------------------------------------


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// // Output a form textarea field
// function newsdesk_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
	// $field = '
// <textarea name="' . newsdesk_parse_input_field_data($name, array('"' => '&quot;')) . '" wrap="' 
// . newsdesk_parse_input_field_data($wrap, array('"' => '&quot;')) . '" cols="' 
// . newsdesk_parse_input_field_data($width, array('"' => '&quot;')) . '" rows="' 
// . newsdesk_parse_input_field_data($height, array('"' => '&quot;')) . '"
// ';

	// if (newsdesk_not_null($parameters)) $field .= ' ' . $parameters;

// //	$field .= 'class="post" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);"';
// $field .= 'ONSELECT="Javascript:storeCaret(this);" ONCLICK="Javascript:storeCaret(this);" ONKEYUP="Javascript:storeCaret(this);" ONCHANGE="Javascript:storeCaret(this);"';
	// $field .= '>';

	// if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
		// $field .= $GLOBALS[$name];
	// } elseif (newsdesk_not_null($text)) {
		// $field .= $text;
	// }

	// $field .= '</textarea>';

// return $field;

// }
// -------------------------------------------------------------------------------------------------------------------------------------------------------------


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// // Parse the data used in the html tags to ensure the tags will not break
// function newsdesk_parse_input_field_data($data, $parse) {
	// return strtr(trim($data), $parse);
// }
// // -------------------------------------------------------------------------------------------------------------------------------------------------------------


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
function newsdesk_not_null($value) {
if (is_array($value)) {
	if (sizeof($value) > 0) {
		return true;
	} else {
		return false;
	}
} else {
	if (($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0)) {
		return true;
	} else {
		return false;
	}
}

}
// -------------------------------------------------------------------------------------------------------------------------------------------------------------


// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// function newsdesk_set_categories_status($categories_id, $status) {
// if ($status == '1') {
	// return tep_db_query("update " . TABLE_NEWSDESK_CATEGORIES . " set catagory_status = '1' where categories_id = '" . tep_db_input($categories_id) . "'");
// } elseif ($status == '0') {
	// return tep_db_query("update " . TABLE_NEWSDESK_CATEGORIES . " set catagory_status = '0' where categories_id = '" . tep_db_input($categories_id) . "'");
// } else {
	// return -1;
// }

// }
// -------------------------------------------------------------------------------------------------------------------------------------------------------------


// // -----------------------------------------------------------------------
// function newsdesk_get_newsdesk_image_text($product_id, $language_id = 0) {
// // -------------------------------------------------------------------------------------------------------------------------------------------------------------
// global $FSESSION;

// if ($language_id == 0) $language_id = $FSESSION->languages_id;
// $product_query = tep_db_query("select newsdesk_image_text from " . TABLE_NEWSDESK_DESCRIPTION . " where newsdesk_id = '" . tep_db_input($product_id) . "' and language_id = '" . tep_db_input($language_id) . "'");
// $product = tep_db_fetch_array($product_query);

// return $product['newsdesk_image_text'];

// }
// // -----------------------------------------------------------------------
// function newsdesk_get_newsdesk_image_text_two($product_id, $language_id = 0) {
// // -------------------------------------------------------------------------------------------------------------------------------------------------------------
// global $FSESSION;

// if ($language_id == 0) $language_id = $FSESSION->languages_id;
// $product_query = tep_db_query("select newsdesk_image_text_two from " . TABLE_NEWSDESK_DESCRIPTION . " where newsdesk_id = '" . tep_db_input($product_id) . "' and language_id = '" . tep_db_input($language_id) . "'");
// $product = tep_db_fetch_array($product_query);

// return $product['newsdesk_image_text_two'];

// }
// // -----------------------------------------------------------------------
// function newsdesk_get_newsdesk_image_text_three($product_id, $language_id = 0) {
// // -------------------------------------------------------------------------------------------------------------------------------------------------------------
// global $FSESSION;

// if ($language_id == 0) $language_id = $FSESSION->languages_id;
// $product_query = tep_db_query("select newsdesk_image_text_three from " . TABLE_NEWSDESK_DESCRIPTION . " where newsdesk_id = '" . tep_db_input($product_id) . "' and language_id = '" . tep_db_input($language_id) . "'");
// $product = tep_db_fetch_array($product_query);

// return $product['newsdesk_image_text_three'];

// }


// // -----------------------------------------------------------------------
// // Sets the sticky of a product
// // -----------------------------------------------------------------------
// function newsdesk_set_product_sticky($newsdesk_id, $sticky) {
// if ($sticky == '1') {
	// return tep_db_query("update " . TABLE_NEWSDESK . " set newsdesk_sticky = '1', newsdesk_last_modified = now() where newsdesk_id = '" . tep_db_input($newsdesk_id) . "'");
// } elseif ($sticky == '0') {
	// return tep_db_query("update " . TABLE_NEWSDESK . " set newsdesk_sticky = '0', newsdesk_last_modified = now() where newsdesk_id = '" . tep_db_input($newsdesk_id) . "'");
// } else {
	// return -1;
// }

// }


// -----------------------------------------------------------------------
// nl2br >> br2nl ... stripbreaks code found on php.net forum
// -----------------------------------------------------------------------
function stripbr($str) {
$str=eregi_replace('<BR[[:space:]]*/?[[:space:]]*>',"",$str);
return $str;
}
// -----------------------------------------------------------------------


// -----------------------------------------------------------------------
// upload file function (taken from loaded 5)
// -----------------------------------------------------------------------
function tep_get_uploaded_file($filename) {
if (isset($_FILES[$filename])) {
	$uploaded_file = array(
		'name' => $_FILES[$filename]['name'],
		'type' => $_FILES[$filename]['type'],
		'size' => $_FILES[$filename]['size'],
		'tmp_name' => $_FILES[$filename]['tmp_name']
	);
} elseif (isset($GLOBALS['HTTP_POST_FILES'][$filename])) {
	global $HTTP_POST_FILES;

	$uploaded_file = array(
	'name' => $HTTP_POST_FILES[$filename]['name'],
	'type' => $HTTP_POST_FILES[$filename]['type'],
	'size' => $HTTP_POST_FILES[$filename]['size'],
	'tmp_name' => $HTTP_POST_FILES[$filename]['tmp_name']
	);
} else {
	$uploaded_file = array(
		'name' => $GLOBALS[$filename . '_name'],
		'type' => $GLOBALS[$filename . '_type'],
		'size' => $GLOBALS[$filename . '_size'],
		'tmp_name' => $GLOBALS[$filename]
	);
}

	return $uploaded_file;
}
// -----------------------------------------------------------------------


// -----------------------------------------------------------------------
// return a local directory path (without trailing slash)
// -----------------------------------------------------------------------
function tep_get_local_path($path) {
	if (substr($path, -1) == '/') $path = substr($path, 0, -1);
	return $path;
}
// -----------------------------------------------------------------------


// -----------------------------------------------------------------------
// the $filename parameter is an array with the following elements:
// name, type, size, tmp_name
// -----------------------------------------------------------------------
function tep_copy_uploaded_file($filename, $target) {
	if (substr($target, -1) != '/') $target .= '/';
	$target .= $filename['name'];
	move_uploaded_file($filename['tmp_name'], $target);
}
// -----------------------------------------------------------------------


/*

	osCommerce, Open Source E-Commerce Solutions ---- http://www.oscommerce.com
	Copyright (c) 2002 osCommerce
	Released under the GNU General Public License

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.

	script name:	NewsDesk
	version:		1.4.5
	date:			2003-08-31
	author:			Carsten aka moyashi
	web site:		www..com

*/
?>

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
// FILE: meta_tags.php
// USE : This file controls the title, meta description,
//       and meta keywords of every page on your web site.
//       See the install docs for instructions.

	// Check to ensure this file is included in osConcert!
	defined('_FEXEC') or die();

	// Define Primary Section Output
	define('PRIMARY_SECTION', ' ');

	// Define Secondary Section Output
	define('SECONDARY_SECTION', ' - ');

	// Define Tertiary Section Output
	define('TERTIARY_SECTION', ', ');

	// Optional customization options for each language
	switch ($FSESSION->languages_id) {
		
		// English language
		case '1':
			//Extra keywords that will be outputted on every page
			$mt_extra_keywords = '';
			//Descriptive tagline of your web site
			$web_site_tagline = TERTIARY_SECTION . '';
			break;
		
		// German language
		case '2':
			//Extra keywords that will be outputted on every page
			$mt_extra_keywords = '';
			//Descriptive tagline of your web site
			$web_site_tagline = TERTIARY_SECTION . '';
			break;
		
		// Spanish language
		case '3':
			//Extra keywords that will be outputted on every page
			$mt_extra_keywords = '';
			//Descriptive tagline of your web site
			$web_site_tagline = TERTIARY_SECTION . '';
			break;
	}
	
	// Clear web site tagline if not customized
	// if($web_site_tagline == TERTIARY_SECTION) {
		// $web_site_tagline = '';
	// }
	
	// Get all top category names for use with web site keywords

		$mt_categories_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$FSESSION->languages_id ."' AND c.categories_id='" . (int)$current_category_id . "'");
		while ($mt_categories = tep_db_fetch_array($mt_categories_query)) {
			$mt_keywords_string = $mt_categories['categories_name'] . ',';
		}
	
	
	$mt_keywords_string=substr($mt_keywords_string,0,-1);
	$replace_array=array("Categories_Name"=>$mt_keywords_string);
	$content_query=tep_db_query("SELECT title,description,keywords from " . TABLE_META_TAGS . " where tag_id='" . (int)$current_category_id . "'");
	if(tep_db_num_rows($content_query)>0){

		$content_result=tep_db_fetch_array($content_query);
		switch($content){
			
			case 'CONTENT_INDEX_NESTED':
				$mt_category_query = tep_db_query("select categories_name,categories_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "' and language_id = '" . (int)$FSESSION->languages_id . "'");
				$mt_category = tep_db_fetch_array($mt_category_query);
				$replace_array["Category_Name"]=$mt_category["categories_name"];
				$replace_array["Category_Description"]=$mt_category["categories_description"];
				break;
			
			case 'CONTENT_INDEX_PRODUCTS':
			case 'CONTENT_INDEX_PRODUCTS_ALL':
				if($FREQUEST->getvalue('manufacturers_id')) {
					$mt_manufacturer_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $FREQUEST->getvalue('manufacturers_id','int') . "'");
					$mt_manufacturer = tep_db_fetch_array($mt_manufacturer_query);
					$replace_array["Category_Name"]=$mt_manufacturer['manufacturers_name'];
					$replace_array["Category_Description"]="";
				}
				else {
					$mt_category_query = tep_db_query("select categories_name,categories_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "' and language_id = '" . (int)$FSESSION->languages_id . "'");
					$mt_category = tep_db_fetch_array($mt_category_query);
					$replace_array["Category_Name"]=$mt_category["categories_name"];
					$replace_array["Category_Description"]=$mt_category["categories_description"];
				}
				break;

			
			case 'CONTENT_PRODUCT_INFO':
				$mt_product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_price, p.products_tax_class_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . $FREQUEST->getvalue('products_id','int') . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "'");
				$mt_product_info = tep_db_fetch_array($mt_product_info_query);
				
				if($mt_new_price = tep_get_products_special_price($mt_product_info['products_id'])) {
					$mt_products_price = $currencies->display_price($mt_product_info['products_price'], tep_get_tax_rate($mt_product_info['products_tax_class_id']),1,true) . $currencies->display_price($mt_new_price, tep_get_tax_rate($mt_product_info['products_tax_class_id']));
				}
				else {
					$mt_products_price = $currencies->display_price($mt_product_info['products_price'], tep_get_tax_rate($mt_product_info['products_tax_class_id']),1,true);
				}
				
				if (tep_not_null($mt_product_info['products_model'])) {
					$mt_products_name = $mt_product_info['products_name'] . ' [' . $mt_product_info['products_model'] . ']';
				}
				else {
					$mt_products_name = $mt_product_info['products_name'];
				}
				$mt_products_description = substr(strip_tags(stripslashes($mt_product_info['products_description'])), 0, 100);
				$replace_array["Item_Name"]=$mt_products_name;
				$replace_array["Item_Price"]=$mt_products_price;
				$replace_array["Item_Description"]=$mt_products_description;
				break;

			
		}
		
		$title=replace_details($replace_array,$content_result["title"]);
		$description=replace_details($replace_array,$content_result["description"]);
		$keyword=replace_details($replace_array,$content_result["keywords"]);
		$title=preg_replace("/(%%([^%%]+)%%)/","",$title);
		$description=preg_replace("/(%%([^%%]+)%%)/","",$description);
		$keyword=preg_replace("/(%%([^%%]+)%%)/","",$keyword);
		
		define('META_TAG_TITLE', $title);
		define('META_TAG_DESCRIPTION', $description);
		define('META_TAG_KEYWORDS', $keyword);
		return;
	}
	
	function replace_details($replace_array,$content){
		if(count($replace_array)<=0) return;
		reset($replace_array);
		//FOREACH
		//while(list($key,$value)=each($replace_array)){
			
		foreach($replace_array as $key => $value)	
		{
			$content=preg_replace("/%%" . $key . "%%/",$value,$content);
		}
		return $content;
	}
	
	define('WEB_SITE_KEYWORDS', $mt_keywords_string . $mt_extra_keywords);
	
	switch($content) {
		

		
		case 'CONTENT_INDEX_PRODUCTS':
		case 'CONTENT_INDEX_PRODUCTS_ALL':
			if($FREQUEST->getvalue('manufacturers_id')) {
				$mt_manufacturer_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $FREQUEST->getvalue('manufacturers_id','int') . "'");
				$mt_manufacturer = tep_db_fetch_array($mt_manufacturer_query);
				define('META_TAG_TITLE', $mt_manufacturer['manufacturers_name'] . PRIMARY_SECTION . TITLE_NEW . $web_site_tagline);
				define('META_TAG_DESCRIPTION', TITLE_NEW . PRIMARY_SECTION . $mt_manufacturer['manufacturers_name']) . SECONDARY_SECTION . WEB_SITE_KEYWORDS;
				define('META_TAG_KEYWORDS', WEB_SITE_KEYWORDS . $mt_manufacturer['manufacturers_name']);
			}
			else {
				$mt_category_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "' and language_id = '" . (int)$FSESSION->languages_id . "'");
				$mt_category = tep_db_fetch_array($mt_category_query);
				define('META_TAG_TITLE', $mt_category['categories_name'] . PRIMARY_SECTION . TITLE_NEW . $web_site_tagline);
				define('META_TAG_DESCRIPTION', TITLE_NEW . PRIMARY_SECTION . $mt_category['categories_name']) . SECONDARY_SECTION . WEB_SITE_KEYWORDS;
				define('META_TAG_KEYWORDS', WEB_SITE_KEYWORDS . $mt_category['categories_name']);
			}
			break;
	
		
		case 'CONTENT_PRODUCT_INFO':
			$mt_product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_price, p.products_tax_class_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . $FREQUEST->getvalue('products_id','int') . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "'");
			$mt_product_info = tep_db_fetch_array($mt_product_info_query);
			if($mt_new_price = tep_get_products_special_price($mt_product_info['products_id'])) {
				$mt_products_price = $currencies->display_price($mt_product_info['products_price'], tep_get_tax_rate($mt_product_info['products_tax_class_id']),1,true) . $currencies->display_price($mt_new_price, tep_get_tax_rate($mt_product_info['products_tax_class_id']));
			}
			else {
				$mt_products_price = $currencies->display_price($mt_product_info['products_price'], tep_get_tax_rate($mt_product_info['products_tax_class_id']),1,true);
			}
			if(tep_not_null($mt_product_info['products_model'])) {
				$mt_products_name = $mt_product_info['products_name'] . ' [' . $mt_product_info['products_model'] . ']';
			}
			else {
				$mt_products_name = $mt_product_info['products_name'];
			}
			$mt_products_description = substr(strip_tags(stripslashes($mt_product_info['products_description'])), 0, 100);
			define('META_TAG_TITLE', $mt_products_name . SECONDARY_SECTION . $mt_products_price . PRIMARY_SECTION . TITLE_NEW . $web_site_tagline);
			define('META_TAG_DESCRIPTION', TITLE_NEW . PRIMARY_SECTION . $mt_products_name . SECONDARY_SECTION . $mt_products_description . '...');
			define('META_TAG_KEYWORDS', WEB_SITE_KEYWORDS . $mt_products_name);
			break;
		
		default:
			define('NAVBAR_TITLE','');
			$title=NAVBAR_TITLE .  PRIMARY_SECTION . TITLE_NEW . $web_site_tagline;
			//$title=NAVBAR_TITLE .  PRIMARY_SECTION . $web_site_tagline;
			$description=TITLE_NEW . PRIMARY_SECTION . NAVBAR_TITLE . SECONDARY_SECTION . WEB_SITE_KEYWORDS;
			$keyword=WEB_SITE_KEYWORDS . NAVBAR_TITLE;
			if(defined("FRONT_END_PAGE_TITLE") && FRONT_END_PAGE_TITLE!='' ){$title=FRONT_END_PAGE_TITLE;}
			if(defined("FRONT_END_PAGE_DESCRIPTION") && FRONT_END_PAGE_DESCRIPTION!=''){$description=FRONT_END_PAGE_DESCRIPTION;}
			if(defined("FRONT_END_PAGE_KEYWORD") && FRONT_END_PAGE_KEYWORD!=''){$keyword=FRONT_END_PAGE_KEYWORD;}
			define('META_TAG_TITLE',$title);
			define('META_TAG_DESCRIPTION', $description);
			define('META_TAG_KEYWORDS',$keyword);
	}
?>
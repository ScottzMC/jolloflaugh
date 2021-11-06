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
	
	define('CHECK_CON','');

	// Set the level of error reporting
	error_reporting(0);
	error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
	//error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
	//error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
	
	ini_set('session.bug_compat_42','off');
	ini_set('session.bug_compat_warn','off');
	
	// Start the clock for the page parse time log
	define('PAGE_PARSE_START_TIME', microtime());
	
	define('TITLE','osConcert');
	
	// Set the local configuration parameters - mainly for developers
	if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');
	
	// Include application configuration parameters
	require('includes/configure.php');
	
	include(DIR_WS_CLASSES . 'request.php');
	$FREQUEST=new phprequest();

	$FGET=&$FREQUEST->getRef('GET');
	$FPOST=&$FREQUEST->getRef('POST');

	// define('WARNING_CONFIG_FILE_WRITEABLE', 'Warning: I am able to write to the configuration file: ' . dirname($FREQUEST->servervalue('SCRIPT_FILENAME')) . '/includes/configure.php. This is a potential security risk - please set the right user permissions on this file.');

	
	//define('WARNING_CONFIG_FILE_WRITEABLE', 'Warning: I am able to write to the configuration file: ' . dirname($FREQUEST->servervalue('SCRIPT_FILENAME')) . '/includes/configure.php. This is a potential security risk - please set the right user permissions on this file.');

	// set the type of request (secure or not)
	$request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

	// set php_self in the local scope
	$PHP_SELF=$FREQUEST->servervalue('PHP_SELF');
	if ($PHP_SELF=='') $PHP_SELF=$FREQUEST->servervalue('PHP_SELF');


	// Used in the "Backup Manager" to compress backups
	define('LOCAL_EXE_GZIP', '/usr/bin/gzip');
	define('LOCAL_EXE_GUNZIP', '/usr/bin/gunzip');
	define('LOCAL_EXE_ZIP', '/usr/local/bin/zip');
	define('LOCAL_EXE_UNZIP', '/usr/local/bin/unzip');

	// include the list of project filenames
	require(DIR_WS_INCLUDES . 'filenames.php');

	// include the list of project database tables
	require(DIR_WS_INCLUDES . 'database_tables.php');

	// define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)
	define('MENU_DHTML', false);

	// include the database functions
	require(DIR_WS_FUNCTIONS . 'database.php');
	
	
	$EXCLUDE_INPUT=array('_description','message_text');

	// make a connection to the database... now
	tep_db_connect() or die('Unable to connect to database server!');

	// set application wide parameters
	$configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
	while ($configuration = tep_db_fetch_array($configuration_query)) {
		define($configuration['cfgKey'], $configuration['cfgValue']);
	}
	date_default_timezone_set(STORE_TIMEZONE); // Added by R101
        //echo date('Y-m-d H:i:s');
	// define our general functions used application-wide
	require(DIR_WS_FUNCTIONS . 'general.php');
	require(DIR_WS_FUNCTIONS . 'html_output.php');
	//Admin begin
	if (FORCE_HTTPS == 'true') {
    forceHTTPS();
	}
	require(DIR_WS_FUNCTIONS . 'password_funcs.php');
	//Admin end

	// initialize the logger class
	require(DIR_WS_CLASSES . 'logger.php');

	// include shopping cart class
	//require(DIR_WS_CLASSES . 'shopping_cart.php');

	// some code to solve compatibility issues
	require(DIR_WS_FUNCTIONS . 'compatibility.php');

	//session management
	require(DIR_WS_CLASSES . 'sessions.php');
	
    if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
		$SESS_LIFE = 1440;
    }
	if (defined("SESSION_TIMEOUT_BACKEND") && (int)SESSION_TIMEOUT_BACKEND>=0){
		$SESS_LIFE=SESSION_TIMEOUT_BACKEND*60;
	}
	// session management
	$FSESSION=new phpsession(STORE_SESSIONS);
	$FSESSION->LIFETIME=$SESS_LIFE;
	$FSESSION->NAME='fAdminID';
	$FSESSION->SAVE_PATH=SESSION_WRITE_DIRECTORY;
	$FSESSION->COOKIE_PATH=DIR_WS_ADMIN;
	$FSESSION->COOKIE_DOMAIN="";
	$FSESSION->REQUEST_TYPE=$request_type;
	

	$FSESSION->start();
	if($FSESSION->is_registered('cart'))
		$cart=$FSESSION->get('cart');
	// set the language
	$t_language=$FREQUEST->getvalue('language');
	if (!$FSESSION->is_registered('language') || $t_language!=''){
		if ($t_language=='') $t_language=$FSESSION->language;
	
		include(DIR_WS_CLASSES . 'language.php');
		$lng = new language();
	
		if ($t_language!='' && isset($lng->catalog_languages[$t_language])) {
			$lng->set_language($t_language);
		} else {
			$lng->get_browser_language();
		}
	
		$FSESSION->set("language",$lng->language['directory']);
		$FSESSION->set("languages_id",$lng->language['id']);
	}  
   
	// include the language translations
	require(DIR_WS_LANGUAGES . $FSESSION->language . '.php');
	
	$current_page = basename($PHP_SELF);
	//$current_page = basename($_SERVER['SCRIPT_NAME']);
	if (file_exists(DIR_WS_LANGUAGES . $FSESSION->language . '/' . $current_page)) {
		include(DIR_WS_LANGUAGES . $FSESSION->language . '/' . $current_page);
	}
  
	$t_currency=DEFAULT_CURRENCY;
	$FSESSION->set('currency',$t_currency);
  
	// define our localization functions
	require(DIR_WS_FUNCTIONS . 'localization.php');
	
	// Include validation functions (right now only email address)
	require(DIR_WS_FUNCTIONS . 'validations.php');

	// setup our boxes
	require(DIR_WS_CLASSES . 'table_block.php');
	require(DIR_WS_CLASSES . 'box.php');
	require(DIR_WS_CLASSES . 'alertbox.php');
	// initialize the message stack for output messages
	require(DIR_WS_CLASSES . 'message_stack.php');
	$messageStack = new messageStack;

	// split-page-results
	require(DIR_WS_CLASSES . 'split_page_results.php');
	
	// entry/item info classes
	require(DIR_WS_CLASSES . 'object_info.php');

	// email classes
	require(DIR_WS_CLASSES . 'mime.php');
	require(DIR_WS_CLASSES . 'email.php'); 
	if (defined('USE_SMTP') && USE_SMTP && file_exists(DIR_WS_CLASSES . 'class.phpgmailer.php')) {
		require(DIR_WS_CLASSES . 'class.phpgmailer.php');
	}
	
	// file uploading class
	require(DIR_WS_CLASSES . 'upload.php');
	
	// calculate category path
	$cPath=$FREQUEST->getvalue('cPath');
	
	if ($cPath!='') {
		$cPath_array = tep_parse_category_path($cPath);
		$cPath = implode('_', $cPath_array);
		$current_category_id = $cPath_array[(count($cPath_array)-1)];
	} else {
		$current_category_id = 0;
	}

	// the following cache blocks are used in the Tools->Cache section
	// ('language' in the filename is automatically replaced by available languages)
	$cache_blocks = array(
		array('title' => TEXT_CACHE_CATEGORIES, 'code' => 'categories', 'file' => 'categories_box-language.cache', 'multiple' => true),
		array('title' => TEXT_CACHE_MANUFACTURERS, 'code' => 'manufacturers', 'file' => 'manufacturers_box-language.cache', 'multiple' => true),
		array('title' => TEXT_CACHE_ALSO_PURCHASED, 'code' => 'also_purchased', 'file' => 'also_purchased-language.cache', 'multiple' => true)
	);

	// check if a default currency is set
	if (!defined('DEFAULT_CURRENCY')) {
		$messageStack->add(ERROR_NO_DEFAULT_CURRENCY_DEFINED, 'error');
	}

	// check if a default language is set
	if(!defined('DEFAULT_LANGUAGE')) {
		$messageStack->add(ERROR_NO_DEFAULT_LANGUAGE_DEFINED, 'error');
	}

	if (function_exists('ini_get') && ((bool)ini_get('file_uploads') == false) ) {
		$messageStack->add(WARNING_FILE_UPLOADS_DISABLED,'warning');
	}
	
	if((file_exists(dirname($FREQUEST->servervalue('SCRIPT_FILENAME')) . '/includes/configure.php')) && (is_writeable(dirname($FREQUEST->servervalue('SCRIPT_FILENAME')) . '/includes/configure.php')) ) {
		//$messageStack->add(WARNING_CONFIG_FILE_WRITEABLE,'warning');
	}
	
	/* display a warning when seatplan caching is disabled */
	if(SEAT_PLAN_CACHE=='false'){$messageStack->add(WARNING_SEATPLAN_CACHE_DISABLED,'warning');}
	
	/* display a warning when seatplan live-logging is enabled */
	if(SEATPLAN_LOGGING=='true'){$messageStack->add(WARNING_SEATPLAN_LOGGING_ENABLED,'warning');}


	//Admin begin
	
	if ((substr_count(strtoupper($PHP_SELF), strtoupper('.PHP')) > 1) || basename($PHP_SELF) != FILENAME_LOGIN){  
		tep_admin_check_login();  
	}//Admin end


	
	require(DIR_WS_INCLUDES . 'add_ccgvdc_application_top.php');
	
	include('includes/application_top_faqdesk.php');
	
	define('FILENAME_CREATE_ACCOUNT', 'create_account.php');
	define('FILENAME_CREATE_ACCOUNT_PROCESS', 'create_account_process.php');
	define('FILENAME_CREATE_ACCOUNT_SUCCESS', 'create_account_success.php');
	define('FILENAME_CREATE_ORDER_PROCESS', 'create_order_process.php');
	define('FILENAME_CREATE_ORDER', 'create_order.php');
	define('FILENAME_EDIT_ORDERS', 'edit_orders.php');
	//==================
	
	
	require(DIR_WS_CLASSES . 'filter_input.php');
  
	$INPUT_FILTER = new filter_input();
	
	
	if(($FREQUEST->isEXists("POST"))>0){
		reset($FPOST);
		//FOREACH
		//while(list($key,) = each($FPOST)){
			
		foreach($FPOST as $key) 
		{
		
			$FREQUEST->setvalue($key,tep_strip_input($key,$FPOST[$key],"POST"),"POST");
		}		
	}
	if (($FREQUEST->isEXists("GET"))>0){
		reset($FGET);	
		//while(list($key,) = each($FGET)){
		foreach($_GET as $key => $value) 
		{	
			$FREQUEST->setvalue($key,tep_strip_input($key,$FGET[$key],"GET"),"GET");
		}		
	}

//print_r($FPOST);
  if (isset($FGET['product_action'])) {
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled
    switch ($FGET['product_action']) {
      // customer wants to update the product quantity in their shopping cart
      case 'update_product' : for ($i=0, $n=sizeof($FPOST['products_id']); $i<$n; $i++) {
                                if (in_array($FPOST['products_id'][$i], (is_array($FPOST['cart_delete']) ? $FPOST['cart_delete'] : array()))) {
                                  $cart->remove($FPOST['products_id'][$i]);
                                } else {
                                  if (PHP_VERSION < 4) {
                                    // if PHP3, make correction for lack of multidimensional array.
                                    reset($FPOST);
                                    while (list($key, $value) = each($FPOST)) {
                                      if (is_array($value)) {
                                        while (list($key2, $value2) = each($value)) {
                                          if (ereg ("(.*)\]\[(.*)", $key2, $var)) {
                                            $id2[$var[1]][$var[2]] = $value2;
                                          }
                                        }
                                      }
                                    }
                                    $attributes = ($id2[$FPOST['products_id'][$i]]) ? $id2[$FPOST['products_id'][$i]] : '';
                                  } else {
                                    $attributes = ($FPOST['id'][$FPOST['products_id'][$i]]) ? $FPOST['id'][$FPOST['products_id'][$i]] : '';
                                  }
								  if(!$FPOST['discount_ids'][$i])
                                    $FPOST['discount_ids'][$i]=0;
                                 $FPOST['txt_price'][$i]=substr($FPOST['txt_price'][$i],1);
                                  $cart->add_cart($FPOST['products_id'][$i], $FPOST['cart_quantity'][$i], $attributes, false,'',0,$FPOST['txt_tax'][$i],$FPOST['txt_price'][$i],$FPOST['price_breaks'][$i],$FPOST['discount_ids'][$i]);
                                }
                              }
                            //  tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
      // customer adds a product from the products page
      case 'add_product' :   
				if (isset($FPOST['add_product_products_id']) && is_numeric($FPOST['add_product_products_id'])) {
					$products_id=(int)$FPOST['add_product_products_id'];
					if($FPOST['add_product_options']) $FPOST['id']=$FPOST['add_product_options']; //for listing attributes options in cart
					//$cart->add_cart($products_id, $cart->get_quantity(tep_get_uprid($products_id, $FPOST['id']))+1, $FPOST['id'],true,array(),0);
					if($FPOST['qty']==0) $FPOST['qty']=1;
					
					//PPB
					$attributes="";
					if (isset($FPOST['id'])){
						$attributes=$FPOST['id'];
					} else if (isset($FPOST['attrValues']) && isset($FPOST['attrIds'])){
						for ($icnt=0,$n=count($FPOST['attrValues']);$icnt<$n;$icnt++){
							$attributes[$FPOST['attrIds'][$icnt]]=$FPOST['attrValues'][$icnt];
						}
					}
					
					$salemaker_id=isset($FPOST['salemaker_id'])?(int)$FPOST['salemaker_id']:0;
					
					//$cart->add_cart($products_id, $cart->get_quantity(tep_get_uprid($products_id, $attributes))+$FPOST['qty'], $attributes,true,array(),0);
                   
					$cart->add_cart($products_id, $cart->get_quantity(tep_get_uprid($products_id, $attributes))+$FPOST['qty'], $attributes,true,array(),0,$FPOST['txt_tax'],$FPOST['txt_price'],$FPOST['price_breaks'],$salemaker_id);
					//PPB
				//	$cart->add_cart($products_id, $cart->get_quantity(tep_get_uprid($products_id, $FPOST['id']))+$FPOST['qty'], $FPOST['id'],true,array(),0);
					// check for forced products;
					//$xsell_ids=(isset($FPOST["xsell_forced_id"])?$FPOST["xsell_forced_id"]:array());
					// if (count($xsell_ids)>0){
						// for ($lcnt=0;$lcnt<count($xsell_ids);$lcnt++){
							// $attr=(isset($xsell_forced_attributes[$xsell_ids[$lcnt]])?$xsell_forced_attributes[$xsell_ids[$lcnt]]:array());
							// $cart->add_cart($xsell_ids[$lcnt], 1, $attr,true,array(),0);
						// }
					// }
				}
				//tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                break;
      // performed by the 'buy now' button in product listings and review page
      case 'buy_now' :        
	  				if (isset($FGET['products_id']) && is_numeric($FGET['products_id'])) {
                        if (tep_has_product_attributes($FGET['products_id'])) {
                           tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $FGET['products_id']));
                        } else {
                            $cart->add_cart($FGET['products_id'], $cart->get_quantity($FGET['products_id'])+1);
                        }
					}
                    tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                    break;
      case 'notify' :         if ($FSESSION->is_registered('customer_id')) {
                                if (isset($FGET['products_id'])) {
                                  $notify = $FGET['products_id'];
                                } elseif (isset($FGET['notify'])) {
                                  $notify = $FGET['notify'];
                                } elseif (isset($FPOST['notify'])) {
                                  $notify = $FPOST['notify'];
                                } else {
                                  tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));
                                }
                                if (!is_array($notify)) $notify = array($notify);
                                for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
                                  $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . tep_db_input($notify[$i]) . "' and customers_id = '" . (int)$customer_id . "'");
                                  $check = tep_db_fetch_array($check_query);
                                  if ($check['count'] < 1) {
                                    tep_db_query("insert into " . TABLE_PRODUCTS_NOTIFICATIONS . " (products_id, customers_id, date_added) values ('" . $notify[$i] . "', '" . $customer_id . "', now())");
                                  }
                                }
                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));
                              } else {
                                $navigation->set_snapshot();
                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'notify_remove' :  if ($FSESSION->is_registered('customer_id') && isset($FGET['products_id'])) {
                                $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . tep_db_input($FGET['products_id']) . "' and customers_id = '" . tep_db_input($customer_id) . "'");
                                $check = tep_db_fetch_array($check_query);
                                if ($check['count'] > 0) {
                                  tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . tep_db_input($FGET['products_id']) . "' and customers_id = '" . tep_db_input($customer_id) . "'");
                                }
                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action'))));
                              } else {
                                $navigation->set_snapshot();
                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'cust_order' :     if ($FSESSION->is_registered('customer_id') && isset($FGET['pid'])) {
                                if (tep_has_product_attributes($FGET['pid'])) {
                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $FGET['pid']));
                                } else {
                                  $cart->add_cart($FGET['pid'], $cart->get_quantity($FGET['pid'])+1);
                                }
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
    }
  }

//==============================

require('includes/classes/breadcrumb.php');
// calculate events category path
  if (file_exists(DIR_WS_LANGUAGES . '/' . FILENAME_EVENTS_MESSAGES_MAIL)) {
    include(DIR_WS_LANGUAGES  . "/" . FILENAME_EVENTS_MESSAGES_MAIL);
  }
 //=================================
 // if (isset($FGET['event_action'])) {
 	// $element_type=$FREQUEST->getvalue('element_type','string','P');  
    // $goto =  FILENAME_SHOPPING_CART;
    // $parameters = array('event_action', 'ecPath', 'products_id','products_qty');
  // }  


if ($FSESSION->is_registered('edit_order')){
  $page=basename($PHP_SELF);
  $pages=array(FILENAME_EDIT_ORDERS,FILENAME_ADDPRODUCTS,FILENAME_EDIT_ORDERS_CONFIRM,FILENAME_EDIT_ORDERS_PROCESS,FILENAME_CHECKOUT_PAYMENT,FILENAME_CHECKOUT_PROCESS);

  if (!in_array($page,$pages)){
      $edit_cart=new editOrderCart($FSESSION->get('edit_order','int',0));
      $edit_cart->reset((int)$FSESSION->get('edit_order','int',0));
      $FSESSION->remove('edit_order');
  }

}
 $page=basename($PHP_SELF);

 if ($page!=FILENAME_FORBIDEN && $page!='save_flag.php'){
	 /*if (!$FSESSION->is_registered('lastAccessPage')){
		tep_session_register('lastAccessPage');	
	 }*/
	
	 $lastAccessPage=$page;
 }

 if  (MENU_DHTML != true)
    define('BOX_WIDTH', 125);

 if ($login_groups_type=='TEXT_CALLSTAFF_ENTRY')
     $SESS_LIFE=4500;
	 
// Hide from backend menu Products (NOT NEEDED ANYMORE ...everything is P)
$menu_query=tep_db_query("select configuration_value from ".TABLE_CONFIGURATION ." where configuration_key='SHOP_SELL_ITEMS'")	 ;
$menu_result=tep_db_fetch_array($menu_query);
$prd=true;
for($i=0;$i<strlen($menu_result['configuration_value']);$i++){
	$menu_value=substr($menu_result['configuration_value'],$i,1);
	switch ($menu_value){
		case 'P':
			define('HIDE_FROM_BACKEND_MENU_PRODUCTS','false');
			break;
	}
}
//require(DIR_WS_LANGUAGES . $FSESSION->language . "/reports_english.php");
  // menu related details
  if(basename($PHP_SELF)==FILENAME_LOGIN || basename($PHP_SELF)==FILENAME_DEFAULT)
  		$FSESSION->remove('mPath');

  $menu_path=$FREQUEST->getvalue('mPath');
 // if ($menu_path=='') $menu_path=$FSESSION->get('mPath');

  if ($menu_path=='') $menu_path=get_current_menu_path();
  
  
  if ($menu_path!=''){
  		$FSESSION->set('mPath',$menu_path);
	$menu_splt = preg_split("/_/",$menu_path);
	for ($icnt=1,$n=count($menu_splt);$icnt<=$n;$icnt++){
		$GLOBALS["l" . $icnt]=$menu_splt[$icnt-1];
	}
  }

  // designing the menu structure
  $menu_arr=array();
  $breadcrumb=new breadcrumb();
  load_menu(0);
  
  $CONFIG_ACCESS_KEY='';
  //print_r($menu_arr);
  //prepare the navigation list
  for ($icnt=1;$icnt<=5;$icnt++)
  {
	if (!isset($GLOBALS["l" . $icnt])) break;
	$menu_id=$GLOBALS["l" . $icnt];
	
	//echo "menu_id=" . $menu_id . "<br>";
	if (isset($menu_arr[$icnt][$menu_id])){
		$breadcrumb->add($menu_arr[$icnt][$menu_id]['text'],tep_href_link($menu_arr[$icnt][$menu_id]['file'],"mPath=" . $menu_arr[$icnt][$menu_id]['path'].'&'.$menu_arr[$icnt][$menu_id]['params'] ));
		$CONFIG_ACCESS_KEY=$CONFIG_ACCESS_KEY . $menu_arr[$icnt][$menu_id]['text'] ." ";
		//echo $CONFIG_ACCESS_KEY;
	}
	}
  $CONFIG_ACCESS_KEY=substr($CONFIG_ACCESS_KEY,0,-1);

   function tep_strip_input($key,$data,$mode="GET"){
		global $INPUT_FILTER;
		if ($mode=="POST" && (strpos($key,'_description')!==false || strpos($key,'newsdesk_article_')!==false || strpos($key,'faqdesk_answer_')!==false || $key=='message_text' || $key=='file_contents')) return $data;
		if (is_array($data)) {
			if (count($data)>0){
				reset($data);
				while(list($key,)=each($data)){
					$data[$key]=tep_strip_input($key,$data[$key],$mode);
				}
			}
			return $data;
		} else {
			return $INPUT_FILTER->_cleanTags($data);
		}
	
}	

  header('Content-Type: text/html; charset=UTF-8');
   if(ADMIN_SINGLE_PAGE_CHECKOUT=='True'){
 	$base_file = basename($PHP_SELF);
 	// if($base_file==FILENAME_CREATE_ORDER) {
		// $perror='';
		// if(isset($FGET['error']) && tep_not_null($FGET['error']))
			// $perror='&error=' . $FGET['error'];
		// tep_redirect(tep_href_link(FILENAME_CREATE_ORDER_NEW,tep_get_all_get_params(array('error')) . $perror,'SSL'));
	// } else if($base_file==FILENAME_SHOPPING_CART) {
        // tep_redirect(tep_href_link(FILENAME_SHOPPING_CART_NEW,tep_get_all_get_params(),'SSL'));
    // }

 } elseif(ADMIN_SINGLE_PAGE_CHECKOUT=='False'){
   
     
 	$base_file = basename($PHP_SELF);
 	// if($base_file==FILENAME_CREATE_ORDER_NEW){
		// //tep_redirect(tep_href_link(FILENAME_CREATE_ORDER,tep_get_all_get_params() . '&top=1&mPath=1_7','SSL'));
        // tep_redirect(tep_href_link(FILENAME_CREATE_ORDER,tep_get_all_get_params() ,'SSL'));
    // }else if($base_file==FILENAME_SHOPPING_CART_NEW){
		// tep_redirect(tep_href_link(FILENAME_SHOPPING_CART,tep_get_all_get_params(),'SSL'));
	// }
 }
?>
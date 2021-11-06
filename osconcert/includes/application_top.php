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
	error_reporting(0);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	//uncomment below to reveal errors
	//error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
	
	ini_set('session.cookie_httponly',1);
	ini_set('session.bug_compat_42',0);
	ini_set('session.bug_compat_warn',0);
	
	ini_set('default_charset','iso-8859-1'); //set charset
	// start the timer for the page pars	e time log
	define('PAGE_PARSE_START_TIME', microtime());
	$_SERVER['SCRIPT_FILENAME']=$_SERVER['DOCUMENT_ROOT'].((strpos($_SERVER['DOCUMENT_ROOT'],"\\")>0)?str_replace("/","\\",$_SERVER['PHP_SELF']):$_SERVER['PHP_SELF']);
	define('CHECK_CON','');
	// Set the local configuration parameters - mainly for developers
	if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');
	// include server parameters
	require('includes/configure.php');
	include(DIR_WS_CLASSES . 'request.php');
	
	$FREQUEST=new phprequest();
	
	$FGET=&$FREQUEST->getRef('GET');
	$FPOST=&$FREQUEST->getRef('POST');
	
	require(DIR_WS_CLASSES . 'filter_input.php');
  
	if( (isset($FPOST)) || (isset($FGET)) )
	{
	$INPUT_FILTER = new filter_input();
		if(count($FPOST)>0)
		{
			reset($FPOST);
			foreach (array_keys($FPOST) as $key)
			{
					 if(!is_array($FPOST[$key]))
					 {
					 $FPOST[$key]=$INPUT_FILTER->_cleanTags($FPOST[$key]);
					 }
				}
		}		
		if (count($FGET)>0)
		{
			reset($FGET);	
			foreach (array_keys($FGET) as $key)
			{
					if(!is_array($FGET[$key]))
					{
					$FGET[$key]=$INPUT_FILTER->_cleanTags($FGET[$key]);
					}
				}	
		}	
	}
	
	if (strlen(DB_SERVER) < 1) 
	{
		if (is_dir('install')) 
		{
			header('Location: install/index.php');
		}
	}
	// define the project version
	define('PROJECT_VERSION', 'osConcert');
	// set the type of request (secure or not)
	$request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';
	// set php_self in the local scope
	if (!isset($PHP_SELF)) $PHP_SELF = $FREQUEST->servervalue('PHP_SELF');
	if ($request_type == 'NONSSL') 
	{
		define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
	} else {
		define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
	}
	
	
	$TEMPLATE_MAIN_PAGE='';
	// include the list of project filenames
	require(DIR_WS_INCLUDES . 'filenames.php');
	
	// include the list of project database tables
	require(DIR_WS_INCLUDES . 'database_tables.php');
	// include the database functions
	require(DIR_WS_FUNCTIONS . 'database.php');
	// make a connection to the database... now
	tep_db_connect() or die('Unable to connect to database server!');
	// set the application parameters
	$configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
	while ($configuration = tep_db_fetch_array($configuration_query)) 
	{
		define($configuration['cfgKey'], $configuration['cfgValue']);
	}
         
        date_default_timezone_set(STORE_TIMEZONE);
        //echo date('Y-m-d H:i:s');
	// if gzip_compression is enabled, start to buffer the output
	if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded('zlib')) && (PHP_VERSION >= '4') ) 
	{
	if (($ini_zlib_output_compression = (int)ini_get('zlib.output_compression')) < 1) 
	{
	  if (PHP_VERSION >= '4.0.4') 
	  {
		ob_start('ob_gzhandler');
	  } else {
		include(DIR_WS_FUNCTIONS . 'gzip_compression.php');
		ob_start();
		ob_implicit_flush();
	  }
	} else {
	  ini_set('zlib.output_compression_level', GZIP_LEVEL);
	}
	}
// set the HTTP GET parameters manually if search_engine_friendly_urls is enabled
  if (SEARCH_ENGINE_FRIENDLY_URLS == 'true' && strpos($FREQUEST->servervalue('REQUEST_URI'), '.php')!==false) 
  {
	$path_info=$FREQUEST->servervalue("REQUEST_URI");
    if (strlen($path_info) > 1) 
	{
      $GET_array = array();
	  $temp_pos=strpos($path_info,".php");
	  if ($temp_pos!==false){
		  $PHP_SELF=substr($path_info,0,$temp_pos+4);
		  $FREQUEST->setvalue('PHP_SELF',$PHP_SELF,'SERVER');
	      $path_info = substr($path_info,$temp_pos+4);
	  }
      $vars = explode('/', substr($path_info, 1));
      for ($i=0, $n=count($vars); $i<$n; $i++) 
	  {
        if (strpos($vars[$i], '[]')) 
		{
          $GET_array[substr($vars[$i], 0, -2)][] = $vars[$i+1];
        } else {
          $FREQUEST->setvalue($vars[$i],$vars[$i+1],'GET');
        }
        $i++;
      }
      if (sizeof($GET_array) > 0) 
	  {
        //FOREACH
		//while (list($key, $value) = each($GET_array)) 
		//{
		foreach($GET_array as $key => $value) 
		{
           $FREQUEST->setvalue($key,$value,'GET');
        }
      }
    }
  }
	// define general functions used application-wide
	require(DIR_WS_FUNCTIONS . 'general.php');
	require(DIR_WS_FUNCTIONS . 'html_output.php');
	//SSL new
	if (FORCE_HTTPS == 'true') 
	{
    forceHTTPS();
	}
	// set the cookie domain
	$cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
	$cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);
	// include cache functions if enabled
	if (USE_CACHE == 'true') include(DIR_WS_FUNCTIONS . 'cache.php');
	// SEF BEGIN
/*	require(DIR_WS_CLASSES . 'seo_url.php');
	$SEO_URL = new seo_url;
	if ($GLOBALS["SEO_URL"]->enabled){
		$SEO_URL->restore_seo_url();
	}*/
	// include shopping cart class
	require(DIR_WS_CLASSES . 'shopping_cart.php');

	// include navigation history class
	require(DIR_WS_CLASSES . 'navigation_history.php');
	// some code to solve compatibility issues
	require(DIR_WS_FUNCTIONS . 'compatibility.php');
	include(DIR_WS_CLASSES . 'sessions.php');
    if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) 
	{
		$SESS_LIFE = 1440;
    }
	if (defined("SESSION_TIMEOUT_FRONTEND") && (int)SESSION_TIMEOUT_FRONTEND>=0)
	{
		//$SESS_LIFE=SESSION_TIMEOUT_FRONTEND*60;
		$SESS_LIFE=SEATPLAN_TIMEOUT+100;
	}
	$FSESSION = new phpsession(STORE_SESSIONS);
	$FSESSION->LIFETIME=$SESS_LIFE;
	$FSESSION->NAME='fEcom';
	$FSESSION->SAVE_PATH=SESSION_WRITE_DIRECTORY;
	$FSESSION->COOKIE_PATH=$cookie_path;
	$FSESSION->COOKIE_DOMAIN=$cookie_domain;
	$FSESSION->REQUEST_TYPE=$request_type;
	
	$FSESSION->start();

	// verify the IP address if the feature is enabled
  if (SESSION_CHECK_IP_ADDRESS == 'True') 
  {
    $ip_address = tep_get_ip_address();
    if (!$FSESSION->is_registered('SESSION_IP_ADDRESS')) 
	{
      $SESSION_IP_ADDRESS = $ip_address;
      $FREQUEST->postvalue('SESSION_IP_ADDRESS');
    }

    if ($SESSION_IP_ADDRESS != $ip_address) 
	{
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
  }

// create the shopping cart & fix the cart if necesary
	$cart=&$FSESSION->getobject("cart");
	if (!is_object($cart))
	{
		$cart=new shoppingCart;
		$FSESSION->set('cart',$cart);
	}
 	$cart=&$FSESSION->getobject("cart");
	####################################
	$cart_array = (array) $cart;	
	$cart_array_ids = (array_keys($cart_array['contents']));
    //var_dump($cart_array_ids);
		# get list of products in database
			if ($FSESSION->is_registered('customer_id')) 
			{
				$query = tep_db_query("select * from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$FSESSION->customer_id . "' ");			
				} else {
				$query = tep_db_query("select * from " . TABLE_CUSTOMERS_TEMP_BASKET . " where customers_id = '" . tep_db_input($FSESSION->ID) . "'");
				}
			while ($query_result = tep_db_fetch_array($query)) 
			{ 
			   //echo '<br>'.$query_result['products_id'];
				if (!in_array($query_result['products_id'],$cart_array_ids)){

					 $cart->contents[$query_result['products_id']] = array('qty' => $query_result['customers_basket_quantity'],'element_type'=>'P','sessions'=>array(),'old_orders_id'=>0,'answer_type'=>'','answer_value'=>'','forced_item_ids'=>'','discount_id'=>'');
				 }
			}
	
	// check the cookie for the user values
	$osCuser=$FREQUEST->cookievalue('osCuser');
	$oscPass=$FREQUEST->cookievalue('osCpass');
	$current_file=basename($PHP_SELF);
	if (!$FSESSION->is_registered('customer_id') && !$FSESSION->is_registered('customer_auto_name') && $osCuser!="" && $oscPass!="" && $current_file!=FILENAME_LOGIN && $current_file!=FILENAME_LOGOFF)
	{
		$add_option="";
		if (ACCOUNT_USERNAME=="true") {$add_option=" or customers_username='" . tep_db_input($osCuser) . "'";
		$check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id,encryption_style from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($osCuser) . "' " . $add_option);}
	
		if (tep_db_num_rows($check_customer_query)>0)
		{
			$check_customer = tep_db_fetch_array($check_customer_query);
			$hash_value=defined('ENCRYPTION_HASH_VALUE')?ENCRYPTION_HASH_VALUE:'@sC@mmRes';
				if ($oscPass==md5($check_customer["customers_password"] . $hash_value))
				{
				$FSESSION->set('customer_auto_name',$check_customer['customers_firstname']);
			
				$guest=$check_customer['guest_account'];
				// update survey flag if customer exist
				$FSESSION->set('error_count',0); 
				$sName=$FSESSION->name;
				$sKey=$FREQUEST->getvalue($sName);
				

				$SID = (defined('SID') ? SID : '');
				tep_redirect(tep_href_link($current_file,tep_get_all_get_params(),'SSL'));
				}// $oscPass
		}// $check_customers_query
	}// session_registered
  
	// include currencies class and create an instance
	require(DIR_WS_CLASSES . 'currencies.php');
	$currencies = new currencies();
	// include the mail classes
	require(DIR_WS_CLASSES . 'mime.php');
	require(DIR_WS_CLASSES . 'email.php');
  
	if (defined('USE_SMTP') && USE_SMTP && file_exists(DIR_WS_CLASSES . 'class.phpgmailer.php')) 
	{
	  require(DIR_WS_CLASSES . 'class.phpgmailer.php');
    }
  
	// set the language
	if ( ! isset($_SESSION['language']) || isset($_GET['language']) ) 
	{
	include(DIR_WS_CLASSES . 'language.php');
	$lng = new language();
	if ( isset($_GET['language']) && tep_not_null($_GET['language']) ) 
	{
	$lng->set_language($_GET['language']);
	} else 
	{
	$lng->get_browser_language();
	}
	$FSESSION->set("language",$lng->language['directory']);
	$FSESSION->set("languages_id",$lng->language['id']);
	$FSESSION->set("languages_name",$lng->language['name']);
	$FSESSION->set("languages_code",$lng->language['code']);
	}
	$language = $_SESSION['language'];
	$languages_id = $_SESSION['languages_id'];
	// include the language translations
	$languages_name = $_SESSION['languages_name'];
	$languages_code = $_SESSION['languages_code'];
	require(DIR_WS_LANGUAGES . $FSESSION->language . '.php');

	$current_page = basename($PHP_SELF);
	if (file_exists(DIR_WS_LANGUAGES . $FSESSION->language . '/' . $current_page)) 
	{
		include(DIR_WS_LANGUAGES . $FSESSION->language . '/' . $current_page);
	}
			 if ((!defined(SEO_ENABLED)) || (SEO_ENABLED == 'true')) 
			 {
			   include_once(DIR_WS_CLASSES . 'seo.class.php');
			   if ( !is_object($seo_urls) )
			   {
				 $seo_urls = new SEO_URL($languages_id);
			   }
			 }
	// currency
	$t_currency=$FREQUEST->getvalue('currency');
	if (!$FSESSION->is_registered('t_currency') || $t_currency!='')
	{
//	$t_currency='';	//ALREADY_DEFINED
	if ($t_currency=='') $t_currency=$FSESSION->currency;
	if (!tep_currency_exists($t_currency)) $t_currency=DEFAULT_CURRENCY;
	$FSESSION->set('currency',$t_currency);
	}
// navigation history
	$navigation=&$FSESSION->getobject("navigation");
	if (!is_object($navigation))
	{
		$navigation=new navigationHistory;
		$FSESSION->set('navigation',$navigation);
	}
	$navigation=&$FSESSION->getobject("navigation");
	
	$navigation->add_current_page();

	if (EXCLUDE_ADMIN_IP_FOR_MAINTENANCE != $FREQUEST->servervalue('REMOTE_ADDR'))
	{
		if (DOWN_FOR_MAINTENANCE=='true' and !strstr($PHP_SELF,DOWN_FOR_MAINTENANCE_FILENAME)) 
		{ 
		tep_redirect(tep_href_link(DOWN_FOR_MAINTENANCE_FILENAME)); 
		}
	}
	// do not let people get to down for maintenance page if not turned on
	if (DOWN_FOR_MAINTENANCE=='false' and strstr($PHP_SELF,DOWN_FOR_MAINTENANCE_FILENAME)) 
	{
		tep_redirect(tep_href_link(FILENAME_DEFAULT));
	}

	if (isset($FGET['action'])) 
	{
		
		
		if (DISPLAY_CART == 'true') 
		{
			$goto =  FILENAME_SHOPPING_CART;
			
			$parameters = array('action', 'cPath', 'products_id', 'pid');
		} else 
		{
			//$goto = basename($PHP_SELF);
			$goto =  FILENAME_CHECKOUT_PAYMENT;
			if ($FGET['action'] == 'buy_now') 
			{
			$parameters = array('action', 'pid', 'products_id');
			} 
			else 
			{
			$parameters = array('action', 'pid');
			}
		}
		switch ($FGET['action']) 
		{
				// customer wants to update the product quantity in their shopping cart
				case 'update_product' : for ($i=0, $n=sizeof($FPOST['products_id']); $i<$n; $i++) 
				{
								if (in_array($FPOST['products_id'][$i], (is_array($FPOST['cart_delete']) ? $FPOST['cart_delete'] : array()))) 
								{
									$cart->remove($FPOST['products_id'][$i]);
									
								} else 
								{
									  if (PHP_VERSION < 4) 
									  {
										// if PHP3, make correction for lack of multidimensional array. If php 4 it's got ereg mate!
										reset($FPOST);
										while (list($key, $value) = each($FPOST)) 
										{
										  if (is_array($value)) {
											while (list($key2, $value2) = each($value)) 
											{
											  if (ereg ("(.*)\]\[(.*)", $key2, $var)) 
											  {
												$id2[$var[1]][$var[2]] = $value2;
											  }
											}
										  }
										}
										$attributes = ($id2[$FPOST['products_id'][$i]]) ? $id2[$FPOST['products_id'][$i]] : '';
										} 
										else 
										{
										$attributes = ($FPOST['id'][$FPOST['products_id'][$i]]) ? $FPOST['id'][$FPOST['products_id'][$i]] : '';
										}
										$cart->add_cart($FPOST['products_id'][$i], $FPOST['cart_quantity'][$i], $attributes, false);
								}
				}
			    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, tep_get_all_get_params($parameters)));
			    break;
				// customer adds a product from the products page
				case 'add_product' :   
				if (isset($FPOST['products_id']) && is_numeric($FPOST['products_id'])) 
				{
					$products_id=$FREQUEST->postvalue('products_id','int');
					$serverDate = date('Y-m-d',getServerDate(false));
					
					$qty=$FREQUEST->postvalue('qty','int',1);
					if ($qty<=0) $qty=1;
					
					$salemaker_id=$FREQUEST->postvalue('salemaker_id','int',0);
					//if (!$cart->is_price_break((int)$HTTP_POST_VARS['products_id'])) $qty+=$cart->get_quantity(tep_get_uprid($products_id, $HTTP_POST_VARS['id']));
					$cart->add_cart($products_id, $qty, $attributes,true,array(),0,$salemaker_id);
				}
				tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
				break;
				// performed by the 'buy now' button in product listings
				case 'buy_now' :
					if (isset($FGET['products_id']) && is_numeric($FGET['products_id'])) 
					{
						if (tep_has_product_attributes($FGET['products_id'])) 
						{
							tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $FGET['products_id']));
						}
						else 
						{
							$cart->add_cart($FGET['products_id'], $cart->get_quantity($FGET['products_id'])+1);
						}
					}
					tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
					break;
					
				//////////////////// multi_product_add////////////////////////////
				case 'add_multi':
				foreach ($_POST['qty'] as $key => $value) 
				{
					//if zero do nothing
					if ($value > 0)
					{
					  $cart->add_cart($key, $cart->get_quantity($key)+ $value);
					}
				}
				if (DISPLAY_CART == 'false') 
				{
					$goto =  FILENAME_CHECKOUT_PAYMENT;
					tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
				}
				
				break;
				///////////////////end multi_product_add//////////////////////////		


				case 'cust_order' :  if ($FSESSION->is_registered('customer_id') && isset($FGET['pid'])) 
				{
								if (tep_has_product_attributes($FGET['pid'])) 
								{
								  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $FGET['pid']));
								} else 
								{
								  $cart->add_cart($FGET['pid'], $cart->get_quantity($FGET['pid'])+1);
								}
				}
							  tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
							  break;
							  
				case 'remove_product' : if (isset($FGET['products_id'])) 
				{       
						$pid = $FGET['products_id'];
						$cart->remove($pid);
						//$messageStack->add_session('product_action', sprintf(PRODUCT_REMOVED, tep_get_products_name($pid)), 'warning');
						tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, tep_get_all_get_params($parameters)));
						break;
				}
				case 'update_product' :  		
				if (isset($FPOST['products_id'])) 
								{
								$n=sizeof($FPOST['products_id']);
								
								for ($i=0; $i<$n; $i++) 
								{
								  if (isset($FPOST['cart_delete']) && in_array($FPOST['products_id'][$i], (is_array($FPOST['cart_delete']) ? $FPOST['cart_delete'] : array()))) 
								  {
									$cart->remove($FPOST['products_id'][$i]);
									$messageStack->add_session('product_action', sprintf(PRODUCT_REMOVED, tep_get_products_name($FPOST['products_id'][$i])), 'warning');
								  } else {
									$attributes = '';
									$cart->add_cart($products_id, $FPOST['cart_quantity'][$i], $attributes, false,array(),0,$FPOST['discount_ids'][$i]);
								  }
								}
							  }
				tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, tep_get_all_get_params($parameters)));		
				
				case 'update_cart' : //not in use?
				for ($i=0, $n=sizeof($FPOST['products_id']); $i<$n; $i++) {
					if (in_array($FPOST['products_id'][$i], (is_array($FPOST['cart_delete']) ? $FPOST['cart_delete'] : array()))) 
					{
					  $products_id=$FPOST['products_id'][$i];
					  $cart->remove($products_id);
					} else 
					{
						$products_id=$FPOST['products_id'][$i];
						//if ($FREQUEST->postvalue('products_type'][$i]=="P" || $FREQUEST->postvalue('products_type'][$i]=="V")
						//$products_id=tep_get_prid($FREQUEST->postvalue('products_id'][$i]);
							  if (PHP_VERSION < 4) 
							  {
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
								$attributes = ($id2[$products_id]) ? $id2[$products_id] : '';
							  } else 
							  {
								$attributes = ($FPOST['id'][$products_id]) ? $FPOST['id'][$products_id] : '';
							  
							  } 
							$cart->add_cart($products_id, $FPOST['cart_quantity'][$i], $attributes, false,array(),0,$FPOST['discount_ids'][$i]);
					}
				}				  
				 tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));      
								
						break;
				
				// case 'clear_cart' :   $cart->reset(true);
						// //tep_redirect($_SERVER[PHP_SELF]);
						// break;
		}
	}

	// include the who's online functions
	require(DIR_WS_FUNCTIONS . 'whos_online.php');
	tep_update_whos_online();
	// include the password crypto functions
	require(DIR_WS_FUNCTIONS . 'password_funcs.php');
	// include validation functions (right now only email address)
	require(DIR_WS_FUNCTIONS . 'validations.php');
	// split-page-results
	require(DIR_WS_CLASSES . 'split_page_results.php');
	// Lango added for template BOF:
	require(DIR_WS_INCLUDES . 'template_application_top.php');
	// Lango added for template EOF:
	if (!defined('TEMPLATENAME_MAIN_PAGE')) define('TEMPLATENAME_MAIN_PAGE',TEMPLATENAME_MAIN_PAGE_DEFAULT);
	// auto expire special products
	require(DIR_WS_FUNCTIONS . 'specials.php');
	tep_expire_specials();
	// include category tree class
	require('includes/classes/category_tree.php');
	// auto expire featured products
	require(DIR_WS_FUNCTIONS . 'featured.php');
	tep_expire_featured();
	//
	if((ALLOW_CATEGORY_EXPIRY=='yes') or (ALLOW_CATEGORY_EXPIRY=='yes_and_hide_expired'))
	{
		if(EXPIRE_DATE_TIME=='yes')
		{
		tep_expire_featured_time();
		}
		else
		{
		tep_expire_featured_cats();
		}
    }
	if(ALLOW_PRODUCT_EXPIRY=='yes')	
	{
	tep_expire_products();	
	}
	// calculate category path
	if ($FREQUEST->getvalue('cPath')) 
	{ 
	$cPath = $FREQUEST->getvalue('cPath');
	} elseif ($FREQUEST->getvalue('products_id','int') && !$FREQUEST->getvalue('manufacturers_id')) 
	{
	$cPath = tep_get_product_path($FREQUEST->getvalue('products_id','int'));
	} else 
	{
	$cPath = '';
	}
  
  
	if (tep_not_null($cPath)) 
	{
	$cPath_array = tep_parse_category_path($cPath);
	$cPath = implode('_', $cPath_array);
	$current_category_id = end($cPath_array);

	$OSCOM_category = new category_tree;
	} else 
	{
	$current_category_id = 0;
	}
	if (tep_not_null($cPath))
	{
	$FSESSION->set('prev_category_viewed','cPath='.$cPath);
	}
  
		// include the breadcrumb class and start the breadcrumb trail
		require(DIR_WS_CLASSES . 'breadcrumb.php');
		$breadcrumb = new breadcrumb;

		$breadcrumb->add(HEADER_TITLE_TOP, (defined("HTTP_HOME_URL")?HTTP_HOME_URL:HTTP_SERVER));

		$breadcrumb->add(HEADER_TITLE_CATALOG, tep_href_link(FILENAME_DEFAULT));
		// add category names or the manufacturer name to the breadcrumb trail
		if (isset($cPath_array)) 
		{
			for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) 
			{
			  $categories_query = tep_db_query("select cd.categories_name, c.categories_status from " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CATEGORIES . " c where cd.categories_id=c.categories_id and cd.categories_id = '" . (int)$cPath_array[$i] . "' and c.categories_status > '0' and language_id = '" . (int)$FSESSION->languages_id . "'");
			  if (tep_db_num_rows($categories_query) > 0) 
			  {
				$categories = tep_db_fetch_array($categories_query);
				$breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1)))));
			  } else 
			  {
				break;
			  }
			}
		} elseif ($FREQUEST->getvalue('manufacturers_id')) 
		{
			$manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $FREQUEST->getvalue('manufacturers_id','int') . "'");
			if (tep_db_num_rows($manufacturers_query)) 
			{
			  $manufacturers = tep_db_fetch_array($manufacturers_query);
			  $breadcrumb->add($manufacturers['manufacturers_name'], tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $FREQUEST->getvalue('manufacturers_id','int')));
			}
		}
		// add the products model to the breadcrumb trail
		if ($FREQUEST->getvalue('products_id')) 
		{
			$model_query = tep_db_query("select p.products_model, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . tep_db_input($FREQUEST->getvalue('products_id','int')) . "' and p.products_id=pd.products_id and pd.language_id='" . (int)$FSESSION->languages_id . "' ");
			if (tep_db_num_rows($model_query)) 
			{
				$model = tep_db_fetch_array($model_query);
				if($model['products_model']!="")
				//only need name here
				$product_name=$model['products_name'];
				else
				$product_name=$model['products_name'];	
				$breadcrumb->add($product_name, tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $FREQUEST->getvalue('products_id','int')));
			}
		}
	// initialize the message stack for output messages
	require('includes/classes/alertbox.php');
	require('includes/classes/message_stack.php');
	$messageStack = new messageStack;
	//  if($currency_count<=0)
	//	  $messageStack->add('header', sprintf(WARNING_CURRENCY,$currency), 'warning');
	// if ($GLOBALS["SEO_URL"]->lastError!='')
	  //$messageStack->add('header', $GLOBALS["SEO_URL"]->lastError, 'error');

	  
	// set which precautions should be checked
	define('WARN_INSTALL_EXISTENCE', 'true');
	//define('WARN_CONFIG_WRITEABLE', 'true');
	define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true');
	define('WARN_SESSION_AUTO_START', 'true');
	define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true');
	define('WARN_IMAGES_DIRECTORY_NOT_WRITEABLE','true');
	define('WARN_IMAGES_BIG_DIRECTORY_NOT_WRITEABLE','true');
	define('WARN_IMAGES_SMALL_DIRECTORY_NOT_WRITEABLE','true');
	//define('WARN_LOG_DIRECTORY_NOT_WRITABLE','true');
 // Include OSC-AFFILIATE
 // require(DIR_WS_INCLUDES .'affiliate_application_top.php');
	require(DIR_WS_INCLUDES .'add_ccgvdc_application_top.php');
	include(DIR_WS_INCLUDES .'languages/add_ccgvdc_english.php');
	require(DIR_WS_LANGUAGES . '/' . FILENAME_EVENTS_MESSAGES_MAIL);
  
	if ($FREQUEST->getvalue('stcPath')!='') 
	{

	$stcPath = $FREQUEST->getvalue('stcPath','int');
	} elseif ($FREQUEST->getvalue('page_id')!='') 
	{
	$stcPath = tep_get_static_path($FREQUEST->getvalue('page_id','int'));
	} else 
	{
	$stcPath = '';
	}
  
	if (tep_not_null($stcPath)) 
	{
	$stcPath_array = tep_parse_static_category_path($stcPath);
	$stcPath = implode('_', $stcPath_array);
	$current_static_category_id = $stcPath_array[(sizeof($stcPath_array)-1)];
	} else 
	{
	$current_static_category_id = 0;
	}
 

	function json_string(&$array)
	{
		global $js_string;
		$js_string.="{";
		$first=true;
		//while(list($key,)=each($array)){
		foreach (array_keys($array) as $key)
			{
			if (!$first) {
				$js_string.=",\n";
			}
			$js_string.="'" . $key . "':";
			$first=false;
			if (is_array($array[$key])){
				if (count($array[$key])>0)
					json_string($array[$key]);
				else
					$js_string.="{}";
			} else{
				$js_string.=dispvalue($array[$key]);
			}
		}
		$js_string.="}";
	}
 header('Content-Type: text/html; charset=utf-8');
 
	if(SINGLE_PAGE_CHECKOUT=='True')
	{
	$base_file = basename($PHP_SELF);
		if($base_file==FILENAME_CHECKOUT_SHIPPING || $base_file==FILENAME_CHECKOUT_PAYMENT || $base_file==FILENAME_CHECKOUT_CONFIRMATION) 
		{
			$perror='';
			if(isset($FGET['error']) && tep_not_null($FGET['error']))
			$perror='&error=' . $FGET['error'];
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_SINGLE,tep_get_all_get_params(array('error')) . $perror,'SSL'));
		}	
	} 
	elseif(SINGLE_PAGE_CHECKOUT=='False')
	{
	$base_file = basename($PHP_SELF);
	if($base_file==FILENAME_CHECKOUT_SINGLE)
		tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING,'','SSL'));
	}
 
   function tep_strip_input($data)
   {
		global $INPUT_FILTER;
		if (is_array($data) && count($data)>0) 
		{
			reset($data);
			//FOREACH
			while(list($key,)=each($data))
			{
				$data[$key]=$INPUT_FILTER->_cleanTags($data[$key]);
			}
			return $data;
		} else 
		{
			return $INPUT_FILTER->_cleanTags($data);
		}
	}	
	function dispvalue($value)
	{
		if(is_numeric($value)) return $value;
		else if(is_bool($value)){
			return ($value)?'true':'false';
		}else{
			return "'".addslashes(str_replace(array("\\n",chr(13) .chr(10),chr(10))," ",$value))."'";
		}		
	}

		if ($FSESSION->is_registered('customer_id') && (isset($_COOKIE['customer_is_guest'])) && substr(basename($PHP_SELF),0,7)=='account') tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
		// PWA EOF
		
		//Ticket Limit Per User
		if ($FSESSION->is_registered('customer_id')  && substr(basename($PHP_SELF),0,8)=='checkout')
		{ 
		tep_check_ticket_limit((int)$FSESSION->customer_id,1 );
		}

 		//loop and display - let's set the max number at seven
		if (!defined('MAX_MULTI_COUPONS'))
		{
			define ('MAX_MULTI_COUPONS', '7');
		}
		//clear tickets
		if (E_TICKETS == 'false')
		{
			array_map('unlink', glob("images/tickets/*.pdf")); 
			array_map('unlink', glob("images/tickets/*.png")); 
			array_map('unlink', glob("*.csv"));
		}
		
		
		function getDefaultLanguage() {
   if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
      return parseDefaultLanguage($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
   else
      return parseDefaultLanguage(NULL);
   }

function parseDefaultLanguage($http_accept, $deflang = "en") {
   if(isset($http_accept) && strlen($http_accept) > 1)  {
      # Split possible languages into array
      $x = explode(",",$http_accept);
      foreach ($x as $val) {
         #check for q-value and create associative array. No q-value means 1 by rule
         if(preg_match("/(.*);q=([0-1]{0,1}.\d{0,4})/i",$val,$matches))
            $lang[$matches[1]] = (float)$matches[2];
         else
            $lang[$val] = 1.0;
      }

      #return default language (highest q-value)
      $qval = 0.0;
      foreach ($lang as $key => $value) {
         if ($value > $qval) {
            $qval = (float)$value;
            $deflang = $key;
         }
      }
   }
   return strtolower($deflang);
}
?>
<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  
  osCommRes, Services Online
  http://www.oscommres.com

  Copyright (c) 2005 osCommRes

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
  http://www.openfreeway.org

  Copyright 2007 ZacWare Pty. Ltd
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

function tep_setcookie($name, $value = '', $expire = 0, $path = '/', $domain = '', $secure = 0) {
 setcookie($name, $value, $expire, $path, (tep_not_null($domain) ? $domain : ''), $secure);
}

function tep_check_payment_barred($exclude)
{
 global $IP_COUNTRY;
 if ($exclude=="" || substr($exclude,0,1)=='M') return false;
 if ($IP_COUNTRY=="") return false;
 if (BLOCK_EXCLUDED_COUNTRIES!='Yes') return false;
 
 $query=tep_db_query("SELECT countries_name from " . TABLE_COUNTRIES . " where countries_iso_code_2='$IP_COUNTRY' or countries_iso_code_3='$IP_COUNTRY' or lower(countries_name)='" . strtolower($IP_COUNTRY) . "' and countries_id in(" . $exclude . ")");
 if (tep_db_num_rows($query)>0) return true;
 return false;
}

  function tep_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n") {
    $address_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address,entry_customer_email as customer_email, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$address_id . "'");
    $address = tep_db_fetch_array($address_query);

    $format_id = tep_get_address_format_id($address['country_id']);

    return tep_address_format($format_id, $address, $html, $boln, $eoln);
  }
  
    function tep_get_address_format_id($country_id) {
    $address_format_query = tep_db_query("select address_format_id as format_id from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$country_id . "'");
    if (tep_db_num_rows($address_format_query)) {
      $address_format = tep_db_fetch_array($address_format_query);
      return $address_format['format_id'];
    } else {
      return '1';
    }
  }
	function tep_get_mpath($menu_id){
		$sql = "select menu_id,parent_id from " . TABLE_ADMIN_MENUS . " where menu_id=" . (int)$menu_id;
		$query = tep_db_query($sql);
		$result = tep_db_fetch_array($query);
		if($result['parent_id'] > 0)
		return tep_get_mpath($result['parent_id']) . "_" . $result['menu_id'];
		else return $result['menu_id'];
	}
	
	function tep_get_menu_array($menu_id){
		$search_array = array();
		$top_sql = "select menu_id,filename,params from " . TABLE_ADMIN_MENUS . " where parent_id='" . (int)$menu_id . "'";
		$top_query = tep_db_query($top_sql);
		while($top_menu = tep_db_fetch_array($top_query)){
			$search_array[] = $top_menu;
			$sub_sql = "select menu_id,filename,params from " . TABLE_ADMIN_MENUS . " where parent_id='" . (int)$top_menu['menu_id'] . "'";
			$sub_query = tep_db_query($sub_sql);
			while($sub_menu = tep_db_fetch_array($sub_query))
				$search_array[] = $sub_menu;
		}
		return $search_array;
	}

//Admin begin
				//any weird login problem try this
				
				// function tep_admin_check_login() {

                // global $PHP_SELF, $login_groups_id,$login_groups_type,$FREQUEST,$FSESSION;

                // if (!($FSESSION->is_registered('login_id'))) {$FSESSION->set('login_id',1);///<-hardcoded

                                // //tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

                // } else {
////
//Check login and file access
function tep_admin_check_login() {
	global $PHP_SELF, $login_groups_id,$login_groups_type,$FREQUEST,$FSESSION;
	
	if (!($FSESSION->is_registered('login_id'))) {
		tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
	} else {
		$mPath=$FREQUEST->getvalue('mPath');
		$from=$FREQUEST->getvalue('from');
		$filename = basename( $PHP_SELF ); 
	//	echo 'group=' . $FSESSION->login_groups_type . "<br>";
		if(strtolower($FSESSION->login_groups_type) != "top administrator"){
			// Search ` Initial Display Page `
			$admin_menus_id = array();
			$get_path = (int)$mPath;
			if(strpos($get_path,'_')>0)$admin_menus_id = preg_split("/_/",$get_path);
			else $admin_menus_id[0] = $get_path;
			// Fetch Page Root Menu Path
			$sql = "select parent_id,menu_id from " . TABLE_ADMIN_MENUS . " where menu_id='" . (int)$admin_menus_id[0] . "'";
			$query = tep_db_query($sql);
			$result = tep_db_fetch_array($query);
	
			// Check the GET_VAR from column_left page
			if($from && $from == 'col' && tep_db_num_rows($query) > 0 && $mPath){
				$FREQUEST->unsetvalue('from',$from);
				$search_array = array();
				$search_array = tep_get_menu_array($result['menu_id']);
				
				for($i=0;$i<sizeof($search_array);$i++){
					if($search_array[$i]['filename'] == '')continue;
					$filename = $search_array[$i]['filename'];
					if ($filename != FILENAME_DEFAULT && $filename != FILENAME_FORBIDEN && $filename != FILENAME_LOGOFF & $filename != FILENAME_POPUP_IMAGE && $filename != 'packingslip.php' && $filename != 'invoice.php') {
						$db_file_query = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . (int)$FSESSION->login_groups_id . "', admin_groups_id) and admin_files_name = '" . tep_db_input($filename) . "'");
						
						if (!tep_db_num_rows($db_file_query)) {
							if($i == sizeof($search_array)-1){
								
								tep_redirect(tep_href_link(FILENAME_FORBIDEN));
								
								//tep_redirect(tep_href_link(FILENAME_FORBIDEN,tep_get_all_get_params()));
							}
						}else{
							$param = '';
							if($search_array[$i]['params']!='')$param = $search_array[$i]['params'] . "&";
							tep_redirect(tep_href_link($filename,$param . 'mPath=' . tep_get_mpath($search_array[$i]['menu_id']) . tep_get_all_get_params(array('mPath','from'))));
						}
					}
				}
			}else{ 
				if ($filename != FILENAME_DEFAULT && $filename != FILENAME_FORBIDEN && $filename != FILENAME_LOGOFF && $filename != FILENAME_POPUP_IMAGE && $filename != 'packingslip.php' && $filename != 'invoice.php') {
					
				$db_file_query = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . (int)$FSESSION->login_groups_id . "', admin_groups_id) and admin_files_name = '" . tep_db_input($filename) . "'");
				
					if (!tep_db_num_rows($db_file_query)) {
					
						tep_redirect(tep_href_link(FILENAME_FORBIDEN,tep_get_all_get_params()));
					}
				}
			}
		}else{ 
			if ($filename != FILENAME_DEFAULT && $filename != FILENAME_FORBIDEN && $filename != FILENAME_LOGOFF && $filename != FILENAME_ADMIN_ACCOUNT && $filename != FILENAME_POPUP_IMAGE && $filename != 'packingslip.php' && $filename != 'invoice.php') {
				$db_file_query = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . (int)$FSESSION->login_groups_id . "', admin_groups_id) and admin_files_name = '" . tep_db_input($filename) . "'");
				if (!tep_db_num_rows($db_file_query)) {
					tep_redirect(tep_href_link(FILENAME_FORBIDEN,tep_get_all_get_params()));
				}
			}
		}
		if($from!='')$FREQUEST->unsetvalue('from',$from);
	}  
}

////
//Return 'true' or 'false' value to display boxes and files in index.php and column_left.php
function tep_admin_check_boxes($filename, $boxes='') {
  global $FSESSION;
  
  $is_boxes = 1;
  if ($boxes == 'sub_boxes') {
    $is_boxes = 0;
  }
  $dbquery = tep_db_query("select admin_files_id from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $FSESSION->login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '" . tep_db_input($is_boxes) . "' and admin_files_name = '" . tep_db_input($filename) . "'");
  
  $return_value = false;
  if (tep_db_num_rows($dbquery)) {
    $return_value = true;
  }
  return $return_value;
}

////
//Return files stored in box that can be accessed by user
function tep_admin_files_boxes($filename, $sub_box_name) {
  global $FSESSION;
  $sub_boxes = '';
  
  $dbquery = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . (int)$FSESSION->login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '0' and admin_files_name = '" . tep_db_input($filename) . "'");
  if (tep_db_num_rows($dbquery)) {
    $sub_boxes = '<a href="' . tep_href_link($filename,'top=1') . '" class="menuBoxContentLink">' . $sub_box_name . '</a><br>';

  }//else 
  //{
  	//$sub_boxes = $sub_box_name . '<br>';
  //}
  return $sub_boxes;
}

////
//Get selected file for index.php
function tep_selected_file($filename) {
  global $FSESSION;
  $randomize = FILENAME_ADMIN_ACCOUNT;
  
  $dbquery = tep_db_query("select admin_files_id as boxes_id from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . (int)$FSESSION->login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '1' and admin_files_name = '" . tep_db_input($filename) . "'");
  if (tep_db_num_rows($dbquery)) {
    $boxes_id = tep_db_fetch_array($dbquery);
    $randomize_query = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . (int)$FSESSION->login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '0' and admin_files_to_boxes = '" . (int)$boxes_id['boxes_id'] . "'");
    if (tep_db_num_rows($randomize_query)) {
      $file_selected = tep_db_fetch_array($randomize_query);
      $randomize = $file_selected['admin_files_name'];
    }
  }
  return $randomize;
}
//Admin end

////
// Redirect to another page or site
function tep_redirect($url) 
{
    

  global $logger,$PHP_SELF,$FREQUEST,$FSESSION;
  $parts=parse_url($url);
	$FSESSION->close();
    

  // if (strpos($parts["path"],FILENAME_CHECKOUT_PAYMENT)!==false && (basename($PHP_SELF)==FILENAME_CREATE_ORDER_NEW || basename($PHP_SELF)==FILENAME_CREATE_ORDER ||basename($PHP_SELF)==FILENAME_CUSTOMERS ) && (isset($GLOBALS["EXECUTE_PAYMENT"]) || ($FSESSION->single_page_flag =="execute" && basename($PHP_SELF)==FILENAME_CREATE_ORDER_NEW)))
  // { // ajax payment redirect

  	// if (strpos($parts["query"],"error")!==false){
		// $splt=split("&",$parts["query"]);
		// $args=array();
		
		// for ($icnt=0,$n=count($splt);$icnt<$n;$icnt++){
			// $splt1=split("=",$splt[$icnt]);
			// if ($splt1[0]!='' && $splt1[1]!='') $args[$splt1[0]]=$splt1[1];
			// //$HTTP_GET_VARS[$splt1[0]]=$splt1[1];
			// $FREQUEST->setvalue($splt1[0],$splt1[1],'GET');			
		// }
		// if (isset($args['payment_error']) && is_object($GLOBALS[$args['payment_error']]) && ($error = $GLOBALS[$args['payment_error']]->get_error())) $err_text=$error["error"];
		// else if (isset($args['error_message']) && $args["error_message"]!="") $err_text=$args["error_message"];
		// else if (isset($args['error']) && $args["error"]) $err_text=$args["error"];
		// else $err_text="Unknown error";
		
		// echo "payment_error||" . urldecode($err_text);
		// exit;
	// }
  // }
  
 
  if ( (strstr($url, "\n") != false) || (strstr($url, "\r") != false) ) 
  {

    tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));

  }
 
  header('Location: ' . $url);
 
  if (STORE_PAGE_PARSE_TIME == 'true') 
  {
    if (!is_object($logger)) $logger = new logger;

    $logger->timer_stop();
  }
  exit;
}

////
// Parse the data used in the html tags to ensure the tags will not break
  function tep_parse_input_field_data($data, $parse) {
    return strtr(trim($data), $parse);
  }

  function tep_output_string($string, $translate = false, $protected = false) {
    if ($protected == true) {
      return htmlspecialchars($string);
    } else {
      if ($translate == false) {
        return tep_parse_input_field_data($string, array('"' => '&quot;'));
      } else {
        return tep_parse_input_field_data($string, $translate);
      }
    }
  }

  function tep_output_string_protected($string) {
    return tep_output_string($string, false, true);
  }

  function tep_sanitize_string($string) {
    $string = preg_replace('/ +/', ' ', trim($string));
    return preg_replace("/[<>]/", '_', $string);
  }
  //function tep_sanitize_string($string) {
//    $patterns = array ('/ +/','/[<>]/');
//    $replace = array (' ', '_');
//    return preg_replace($patterns, $replace, trim($string));
//  }

  function tep_customers_name($customers_id) {
    $customers = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
    $customers_values = tep_db_fetch_array($customers);

    return $customers_values['customers_firstname'] . ' ' . $customers_values['customers_lastname'];
  }

  function tep_get_path($current_category_id = '') {
    global $cPath_array;

    if ($current_category_id == '') {
      $cPath_new = implode('_', $cPath_array);
    } else {
      if (sizeof($cPath_array) == 0) {
        $cPath_new = $current_category_id;
      } else {
        $cPath_new = '';
        $last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cPath_array[(sizeof($cPath_array)-1)] . "'");
        $last_category = tep_db_fetch_array($last_category_query);

        $current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
        $current_category = tep_db_fetch_array($current_category_query);

        if ($last_category['parent_id'] == $current_category['parent_id']) {
          for ($i = 0, $n = sizeof($cPath_array) - 1; $i < $n; $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        } else {
          for ($i = 0, $n = sizeof($cPath_array); $i < $n; $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        }

        $cPath_new .= '_' . $current_category_id;

        if (substr($cPath_new, 0, 1) == '_') {
          $cPath_new = substr($cPath_new, 1);
        }
      }
    }

    return 'cPath=' . $cPath_new;
  }

  function tep_get_all_get_params($exclude_array = '',$include_error=false) {
    global $FGET,$FREQUEST,$FSESSION;

    if ($exclude_array == '') $exclude_array = array();
    $get_url = '';
	reset($FGET);
    //while (list($key, $value) = each($FGET)) {
	foreach($FGET as $key => $value)
	{	
		//FOREACH
      if (($key != $FSESSION->NAME)  && ($key != 'error' || $include_error) && $key!='top' && (!in_array($key, $exclude_array))) $get_url .= $key . '=' . $value . '&';
    }

    return $get_url;
  }

  function tep_date_long($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

    $year = (int)substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    return strftime(DATE_FORMAT_LONG, mktime($hour, $minute, $second, $month, $day, $year));
  }

////
// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
// NOTE: Includes a workaround for dates before 01/01/1970 that fail on windows servers
  function tep_date_short($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

    $year = substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);
	if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
      return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
    } else {
      return preg_replace('/2037$/', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
    }
    // if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
      // return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
    // } else {
      // return ereg_replace('2037' . '$', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
    // }

  }

  function tep_datetime_short($raw_datetime) {
    if ( ($raw_datetime == '0000-00-00 00:00:00') || ($raw_datetime == '') ) return false;

    $year = (int)substr($raw_datetime, 0, 4);
    $month = (int)substr($raw_datetime, 5, 2);
    $day = (int)substr($raw_datetime, 8, 2);
    $hour = (int)substr($raw_datetime, 11, 2);
    $minute = (int)substr($raw_datetime, 14, 2);
    $second = (int)substr($raw_datetime, 17, 2);

    return strftime(DATE_TIME_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
  }
    	// get page name by passing page_id and language_id
  function tep_get_page_name($page_id, $language_id) {
    $page_query = tep_db_query("select page_name from " .TABLE_MAINPAGE_DESCRIPTIONS . " where  page_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
    $page_array = tep_db_fetch_array($page_query);
    return $page_array['page_name'];
  }

	// get page description by passing page_id and language_id
  function tep_get_page_description($page_id, $language_id) {
    $page_query = tep_db_query("select description from " . TABLE_MAINPAGE_DESCRIPTIONS . " where page_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
    $page_array = tep_db_fetch_array($page_query);
    return $page_array['description'];
  }

  function tep_get_static_pages_tree($parent_id='0',$spacing='',$exclude='',$static_pages_tree_array='',$include_itself=false,$default='Level 1'){
  	global $FSESSION;
	
	if (!is_array($static_pages_tree_array)) $static_pages_tree_array = array();
    if ( (sizeof($static_pages_tree_array) < 1) && ($exclude != '0') ) $static_pages_tree_array[] = array('id' => '0', 'text' => $default);

    if ($include_itself) {
      $static_pages_query = tep_db_query("select mp.parent_id,mp.page_id,mpd.page_name from " . TABLE_MAINPAGE . " mp," . TABLE_MAINPAGE_DESCRIPTIONS . " mpd where mp.page_id=mpd.page_id and mpd.language_id='".(int)$FSESSION->languages_id."'");
      $static_pages = tep_db_fetch_array($static_pages_query);
      $static_pages_tree_array[] = array('id' => $parent_id, 'text' => $static_pages['page_name']);
    }
    $static_pages_query = tep_db_query("select mp.parent_id,mpd.page_name,mp.page_id from " . TABLE_MAINPAGE . " mp," . TABLE_MAINPAGE_DESCRIPTIONS . " mpd where mp.page_id=mpd.page_id and mpd.language_id='".(int)$FSESSION->languages_id."' and mp.parent_id='".(int)$parent_id."' order by mp.sort_order");
    while ($static_pages = tep_db_fetch_array($static_pages_query)) {
		//$has_childs = tep_db_query("select page_id from " . TABLE_MAINPAGE . " where parent_id=" . $static_pages['page_id']);
		//if(tep_db_num_rows($has_childs)>0){
			if ($exclude != $static_pages['page_id']) $static_pages_tree_array[] = array('id' => $static_pages['page_id'], 'text' => $spacing . $static_pages['page_name']);
				//$static_pages_tree_array = tep_get_static_pages_tree($static_pages['page_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $static_pages_tree_array);
		//}
    }
	//print_r ( $static_pages_tree_array);
	
	return $static_pages_tree_array;
  }

  function tep_get_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false,$default=TEXT_TOP) {
    global $FSESSION;

    if (!is_array($category_tree_array)) $category_tree_array = array();
    if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => $default);

    if ($include_itself) {
      $category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '" . (int)$FSESSION->languages_id . "' and cd.categories_id = '" . (int)$parent_id . "' order by cd.categories_id");
      $category = tep_db_fetch_array($category_query);
      $category_tree_array[] = array('id' => $parent_id, 'text' => $category['categories_name']);
    }

    $categories_query = tep_db_query("select c.categories_id, c.date_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "' and c.parent_id = '" . (int)$parent_id . "' order by cd.categories_id, c.sort_order ");
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ($exclude != $categories['categories_id']) $category_tree_array[] = array('id' => $categories['categories_id'], 'text' => $spacing . $categories['categories_name'].'&nbsp;&nbsp;&nbsp; ('.$categories['date_id'].')');
      $category_tree_array = tep_get_category_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
    }

    return $category_tree_array;
  }

	function tep_get_admin_tree($group_id = '0', $spacing = '', $exclude = '', $groups_tree_array = '', $include_itself = false,$default=TEXT_TOP) {
		
		if (!is_array($groups_tree_array)) $groups_tree_array = array();
		if ( (sizeof($groups_tree_array) < 1) && ($exclude != '0') ) $groups_tree_array[] = array('id' => '0', 'text' => $default);
		
		if ($include_itself) {
			$groups_query = tep_db_query("select ag.admin_groups_name from " . TABLE_ADMIN_GROUPS . " ag");
			$groups = tep_db_fetch_array($groups_query);
			$groups_tree_array[] = array('id' => $parent_id, 'text' => $groups['admin_groups_name']);
		}
		
		$groups_query = tep_db_query("select ag.admin_groups_id, ag.admin_groups_name from " . TABLE_ADMIN_GROUPS . " ag");
		while ($groups = tep_db_fetch_array($groups_query)) {
			if ($exclude != $groups['admin_groups_id']) $groups_tree_array[] = array('id' => $groups['admin_groups_id'], 'text' => $spacing . $groups['admin_groups_name']);
			//$groups_tree_array = tep_get_admin_tree($groups['admin_groups_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $groups_tree_array);
		}
		
		return $groups_tree_array;
	}
  function tep_draw_products_pull_down($name, $parameters = '', $exclude = '') {
    global $currencies, $FSESSION;

    if ($exclude == '') {
      $exclude = array();
    }

    $select_string = '<select name="' . $name . '"';

    if ($parameters) {
      $select_string .= ' ' . $parameters;
    }

    $select_string .= '>';

    $products_query = tep_db_query("select p.products_id, p.products_price, p.products_model, pd.products_name from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on (p.products_id = pd.products_id) where pd.language_id = '" . (int)$FSESSION->languages_id  . "' order by pd.products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      if (!in_array($products['products_id'], $exclude)) {
        if($products['products_name']) $select_string .= '<option value="' . $products['products_id'] . '">' . $products['products_name'] . ' (' . $currencies->format($products['products_price']) . ')</option>';
      }
    }

    $select_string .= '</select>';

    return $select_string;
  }
  //new function for new featured products AUG 2014
  function tep_draw_products_featured_pull_down($name, $parameters = '', $exclude = '') {
    global $currencies, $FSESSION;

    if ($exclude == '') {
      $exclude = array();
    }

    $select_string = '<select name="' . $name . '"';

    if ($parameters) {
      $select_string .= ' ' . $parameters;
    }

    $select_string .= '>';

    $products_query = tep_db_query("select p.products_id, p.products_price, p.products_model, pd.products_name from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on (p.products_id = pd.products_id) where pd.language_id = '" . (int)$FSESSION->languages_id  . "' and product_type ='G' order by pd.products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      if (!in_array($products['products_id'], $exclude)) {
        if($products['products_name']) $select_string .= '<option value="' . $products['products_id'] . '">' . $products['products_name'] . ' (' . $currencies->format($products['products_price']) . ')</option>';
      }
    }

    $select_string .= '</select>';

    return $select_string;
  }

  function tep_break_string($string, $len, $break_char = '-') {
    $l = 0;
    $output = '';
    for ($i=0, $n=strlen($string); $i<$n; $i++) {
      $char = substr($string, $i, 1);
      if ($char != ' ') {
        $l++;
      } else {
        $l = 0;
      }
      if ($l > $len) {
        $l = 1;
        $output .= $break_char;
      }
      $output .= $char;
    }

    return $output;
  }

  function tep_get_country_name($country_id) {
    $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$country_id . "'");

    if (!tep_db_num_rows($country_query)) {
      return $country_id;
    } else {
      $country = tep_db_fetch_array($country_query);
      return $country['countries_name'];
    }
  }
  // Added by R101
  function tep_get_timezone($timezone_id) {
    $timezone_query = tep_db_query("select timezone from " . TABLE_TIMEZONE . " where timezone_id = '" . (int)$timezone_id . "'");

    if (!tep_db_num_rows($timezone_query)) {
      return $timezone_id;
    } else {
      $timezone = tep_db_fetch_array($timezone_query);
      return $timezone['timezone'];
    }
  }

  function tep_get_zone_name($country_id, $zone_id, $default_zone) {
    $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
    if (tep_db_num_rows($zone_query)) {
      $zone = tep_db_fetch_array($zone_query);
      return $zone['zone_name'];
    } else {
      return $default_zone;
    }
  }

  function tep_not_null($value) {
    if (is_array($value)) {
      if (sizeof($value) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      if ( (is_string($value) || is_numeric($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0)) {
        return true;
      } else {
        return false;
      }
    }
  }

  function tep_browser_detect($component) {
    global $HTTP_USER_AGENT;

    return stristr($HTTP_USER_AGENT, $component);
  }

  function tep_tax_classes_pull_down($parameters, $selected = '') {
    $select_string = '<select ' . $parameters . '>';
    $classes_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($classes = tep_db_fetch_array($classes_query)) {
      $select_string .= '<option value="' . $classes['tax_class_id'] . '"';
      if ($selected == $classes['tax_class_id']) $select_string .= ' SELECTED';
      $select_string .= '>' . $classes['tax_class_title'] . '</option>';
    }
    $select_string .= '</select>';

    return $select_string;
  }

  function tep_geo_zones_pull_down($parameters, $selected = '') {
    $select_string = '<select ' . $parameters . '>';
    $zones_query = tep_db_query("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " order by geo_zone_name");
    while ($zones = tep_db_fetch_array($zones_query)) {
      $select_string .= '<option value="' . $zones['geo_zone_id'] . '"';
      if ($selected == $zones['geo_zone_id']) $select_string .= ' SELECTED';
      $select_string .= '>' . $zones['geo_zone_name'] . '</option>';
    }
    $select_string .= '</select>';

    return $select_string;
  }

  function tep_get_geo_zone_name($geo_zone_id) {
    $zones_query = tep_db_query("select geo_zone_name from " . TABLE_GEO_ZONES . " where geo_zone_id = '" . (int)$geo_zone_id . "'");

    if (!tep_db_num_rows($zones_query)) {
      $geo_zone_name = $geo_zone_id;
    } else {
      $zones = tep_db_fetch_array($zones_query);
      $geo_zone_name = $zones['geo_zone_name'];
    }

    return $geo_zone_name;
  }

  function tep_address_format($address_format_id, $address, $html, $boln, $eoln) {
    $address_format_query = tep_db_query("select address_format as format from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . (int)$address_format_id . "'");
    $address_format = tep_db_fetch_array($address_format_query);

    $company = tep_output_string_protected($address['company']);
    if (isset($address['firstname']) && tep_not_null($address['firstname'])) {
      $firstname = tep_output_string_protected($address['firstname']);
      $lastname = tep_output_string_protected($address['lastname']);
    } elseif (isset($address['name']) && tep_not_null($address['name'])) {
      $firstname = tep_output_string_protected($address['name']);
      $lastname = '';
    } else {
      $firstname = '';
      $lastname = '';
    }
    $customer_email = tep_output_string_protected($address['customer_email']);
    $street = tep_output_string_protected($address['street_address']);
    $suburb = tep_output_string_protected($address['suburb']);
    $city = tep_output_string_protected($address['city']);
    $state = tep_output_string_protected($address['state']);
    if (isset($address['country_id']) && tep_not_null($address['country_id'])) {
      $country = tep_get_country_name($address['country_id']);

      if (isset($address['zone_id']) && tep_not_null($address['zone_id'])) {
        $state = tep_get_zone_name($address['country_id'], $address['zone_id'], $state);
      }
    } elseif (isset($address['country']) && tep_not_null($address['country'])) {
      $country = tep_output_string_protected($address['country']);
    } else {
      $country = '';
    }
    $postcode = tep_output_string_protected($address['postcode']);
    $zip = $postcode;

    if ($html) {
// HTML Mode
      $HR = '<hr>';
      $hr = '<hr>';
      if ( ($boln == '') && ($eoln == "\n") ) { // Values not specified, use rational defaults
        $CR = '<br>';
        $cr = '<br>';
        $eoln = $cr;
      } else { // Use values supplied
        $CR = $eoln . $boln;
        $cr = $CR;
      }
    } else {
// Text Mode
      $CR = $eoln;
      $cr = $CR;
      $HR = '----------------------------------------';
      $hr = '----------------------------------------';
    }

    $statecomma = '';
    $streets = $street;
    if ($suburb != '') $streets = $street . $cr . $suburb;
    if ($country == '') $country = tep_output_string_protected($address['country']);
    if ($state != '') $statecomma = $state . ', ';

    $fmt = $address_format['format'];
    eval("\$address = \"$fmt\";");

    if ( (ACCOUNT_COMPANY == 'true') && (tep_not_null($company)) ) {
      $address = $company . $cr . $address;
    }

    return $address;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_get_zone_code
  //
  // Arguments   : country           country code string
  //               zone              state/province zone_id
  //               def_state         default string if zone==0
  //
  // Return      : state_prov_code   state/province code
  //
  // Description : Function to retrieve the state/province code (as in FL for Florida etc)
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_get_zone_code($country, $zone, $def_state) {

    $state_prov_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and zone_id = '" . (int)$zone . "'");

    if (!tep_db_num_rows($state_prov_query)) {
      $state_prov_code = $def_state;
    }
    else {
      $state_prov_values = tep_db_fetch_array($state_prov_query);
      $state_prov_code = $state_prov_values['zone_code'];
    }
    
    return $state_prov_code;
  }

  function tep_get_uprid($prid, $params) {
    $uprid = $prid;
    if ( (is_array($params)) && (!strstr($prid, '{')) ) {
      while (list($option, $value) = each($params)) {
        $uprid = $uprid . '{' . $option . '}' . $value;
      }
    }

    return $uprid;
  }

  function tep_get_prid($uprid) {
    $pieces = explode('{', $uprid);

    return $pieces[0];
  }

  function tep_get_languages() {
    $languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " order by sort_order");
	while ($languages = tep_db_fetch_array($languages_query)) {
      $languages_array[] = array('id' => $languages['languages_id'],
                                 'name' => $languages['name'],
                                 'code' => $languages['code'],
                                 'image' => $languages['image'],
                                 'directory' => $languages['directory']);
    }

    return $languages_array;
  }

  function tep_get_category_name($category_id, $language_id) {
    $category_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
    $category = tep_db_fetch_array($category_query);

    return $category['categories_name'];
  }

  function tep_get_orders_status_name($orders_status_id, $language_id = '') {
    global $FSESSION;

    if (!$language_id) $language_id = $FSESSION->languages_id;
    $orders_status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . (int)$orders_status_id . "' and language_id = '" . (int)$language_id . "'");
    $orders_status = tep_db_fetch_array($orders_status_query);

    return $orders_status['orders_status_name'];
  }

  function tep_get_orders_status($text_all=false) {
    global $FSESSION;

    $orders_status_array = array();
	if($text_all)
		$orders_status_array[]=array("id"=>"-1","text"=>TEXT_ALL);
    $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$FSESSION->languages_id . "' and lower(orders_status_name)!='refunded' order by orders_status_id");
    while ($orders_status = tep_db_fetch_array($orders_status_query)) {
      $orders_status_array[] = array('id' => $orders_status['orders_status_id'],
                                     'text' => $orders_status['orders_status_name']);
    }

    return $orders_status_array;
  }

  function tep_get_products_name($product_id, $language_id = 0) {
    global $FSESSION;
	
	if($language_id<=0) 
		$language_id=$FSESSION->languages_id;
    $product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_name'];
  }

  function tep_get_infobox_file_name($infobox_id, $language_id = 0) {
    global $FSESSION;
		if($language_id<=0) 
		$language_id=$FSESSION->languages_id;

    $infobox_query = tep_db_query("select infobox_file_name from " . TABLE_INFOBOX_CONFIGURATION . " where infobox_id = '" . (int)$infobox_id . "' and language_id = '" . (int)$language_id . "'");
    $infobox = tep_db_fetch_array($infobox_query);

    return $infobox['infobox_file_name'];
  }

  function tep_get_products_description($product_id, $language_id) {
    $product_query = tep_db_query("select products_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_description'];
  }

  function tep_get_products_url($product_id, $language_id) {
    $product_query = tep_db_query("select products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_url'];
  }

////
// Return the manufacturers URL in the needed language
// TABLES: manufacturers_info
  function tep_get_manufacturer_url($manufacturer_id, $language_id) {
    $manufacturer_query = tep_db_query("select manufacturers_url from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturer_id . "' and languages_id = '" . (int)$language_id . "'");
    $manufacturer = tep_db_fetch_array($manufacturer_query);

    return $manufacturer['manufacturers_url'];
  }

////
// Wrapper for class_exists() function
// This function is not available in all PHP versions so we test it before using it.
  function tep_class_exists($class_name) {
    if (function_exists('class_exists')) {
      return class_exists($class_name);
    } else {
      return true;
    }
  }

////
// Count how many products exist in a category
// TABLES: products, products_to_categories, categories
  function tep_products_in_category_count($categories_id, $include_deactivated = false) {
    $products_count = 0;

    if ($include_deactivated) {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$categories_id . "'");
    } else {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . (int)$categories_id . "'");
    }

    $products = tep_db_fetch_array($products_query);

    $products_count += $products['total'];

    $childs_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$categories_id . "'");
    if (tep_db_num_rows($childs_query)) {
      while ($childs = tep_db_fetch_array($childs_query)) {
        $products_count += tep_products_in_category_count($childs['categories_id'], $include_deactivated);
      }
    }

    return $products_count;
  }

////
// Count how many subcategories exist in a category
// TABLES: categories
  function tep_childs_in_category_count($categories_id) {
    $categories_count = 0;

    $categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$categories_id . "'");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $categories_count += tep_childs_in_category_count($categories['categories_id']);
    }

    return $categories_count;
  }

////
// Returns an array with countries
// TABLES: countries
  function tep_get_countries($default = '') {
    $countries_array = array();
    if ($default) {
      $countries_array[] = array('id' => '',
                                 'text' => $default);
    }
    $countries_query = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
    while ($countries = tep_db_fetch_array($countries_query)) {
      $countries_array[] = array('id' => $countries['countries_id'],
                                 'text' => $countries['countries_name']);
    }

    return $countries_array;
  }
  // Added by R101
  function tep_get_timezone_list($default = '') {
    $timezone_array = array();
    if ($default) {
      $timezone_array[] = array('id' => '',
                                 'text' => $default);
    }
    
    $timezone_query = tep_db_query("select timezone,timezone_value from " . TABLE_TIMEZONE . " order by timezone");
    while ($timezone = tep_db_fetch_array($timezone_query)) {
      $timezone_array[] = array('id' => $timezone['timezone'],
                                 'text' => $timezone['timezone'] .' - '. $timezone['timezone_value']);
    }

    return $timezone_array;
  }

////
// return an array with country zones
  function tep_get_country_zones($country_id) {
    $zones_array = array();
    $zones_query = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' order by zone_name");
    while ($zones = tep_db_fetch_array($zones_query)) {
      $zones_array[] = array('id' => $zones['zone_id'],
                             'text' => $zones['zone_name']);
    }

    return $zones_array;
  }
  
  // return an array with all country zones except australia
   function tep_get_country_all_zones($country_id) {
    $zones_array = array();
	 $zones_array[] = array('id' => '-1',
                             'text' => TEXT_DEFAULT);
    $zones_query = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id != '" . (int)$country_id . "' order by zone_name");
    while ($zones = tep_db_fetch_array($zones_query)) {
      $zones_array[] = array('id' => $zones['zone_id'],
                             'text' => $zones['zone_name']);
    }

    return $zones_array;
  }

  function tep_prepare_country_zones_pull_down($country_id = '') {
// preset the width of the drop-down for Netscape
    $pre = '';
    if ( (!tep_browser_detect('MSIE')) && (tep_browser_detect('Mozilla/4')) ) {
      for ($i=0; $i<45; $i++) $pre .= '&nbsp;';
    }

    $zones = tep_get_country_zones($country_id);

    if (sizeof($zones) > 0) {
      $zones_select = array(array('id' => '', 'text' => PLEASE_SELECT));
      $zones = array_merge($zones_select, $zones);
    } else {
      $zones = array(array('id' => '', 'text' => TYPE_BELOW));
// create dummy options for Netscape to preset the height of the drop-down
      if ( (!tep_browser_detect('MSIE')) && (tep_browser_detect('Mozilla/4')) ) {
        for ($i=0; $i<9; $i++) {
          $zones[] = array('id' => '', 'text' => $pre);
        }
      }
    }

    return $zones;
  }

////
// Get list of address_format_id's
  function tep_get_address_formats() {
    $address_format_query = tep_db_query("select address_format_id from " . TABLE_ADDRESS_FORMAT . " order by address_format_id");
    $address_format_array = array();
    while ($address_format_values = tep_db_fetch_array($address_format_query)) {
      $address_format_array[] = array('id' => $address_format_values['address_format_id'],
                                      'text' => $address_format_values['address_format_id']);
    }
    return $address_format_array;
  }

////
// Alias function for Store configuration values in the Administration Tool
  function tep_cfg_pull_down_country_list($country_id,$key='') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
    return tep_draw_pull_down_menu($name, tep_get_countries(), $country_id);
  }
  // Added by R101
  function tep_cfg_pull_down_timezone_list($timezone_id,$key='') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
    return tep_draw_pull_down_menu($name, tep_get_timezone_list(), $timezone_id);
  }

  function tep_cfg_pull_down_zone_list($zone_id,$key='') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
    return tep_draw_pull_down_menu($name, tep_get_country_zones(STORE_COUNTRY), $zone_id);
  }

  function tep_cfg_pull_down_tax_classes($tax_class_id, $key = '') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);
    }

    return tep_draw_pull_down_menu($name, $tax_class_array, $tax_class_id);
  }

////
// Function to read in text area in admin
 function tep_cfg_textarea($text,$key='') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
    return tep_draw_textarea_field($name, false, 35, 5, $text);
  }

 function tep_cfg_text_field($text,$key='') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
    return tep_draw_input_field($name, $text);
  }
  
 function tep_cfg_text_small_field($text,$key='') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
    return tep_draw_input_field($name, $text,'size=10');
  }


  function tep_cfg_get_zone_name($zone_id) {
    $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_id = '" . (int)$zone_id . "'");

    if (!tep_db_num_rows($zone_query)) {
      return $zone_id;
    } else {
      $zone = tep_db_fetch_array($zone_query);
      return $zone['zone_name'];
    }
  }


// Sets the status of a product
  function tep_set_product_status($products_id, $status) {
    if ($status == '1') 
	{
      return tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '1', products_sku = '1',products_quantity = '1',  products_last_modified = now() where products_id = '" . (int)$products_id . "'");
    } 
	elseif ($status == '0') 
	{
      return tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0', products_quantity = '0', products_last_modified = now() where products_id = '" . (int)$products_id . "'");
    } 
	elseif ($status == '8') 
	{
      return tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '8', products_quantity = '0', products_last_modified = now() where products_id = '" . (int)$products_id . "'");
    } 
	elseif ($status == '3') 
	{
      return tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '3', products_quantity = '0', products_last_modified = now() where products_id = '" . (int)$products_id . "'");
    } 
	else 
	{
      return -1;
    }
  }

////
// Sets the status of a product on special
  function tep_set_specials_status($specials_id, $status) {
    if ($status == '1') {
      return tep_db_query("update " . TABLE_SPECIALS . " set status = '1', expires_date = NULL, date_status_change = NULL where specials_id = '" . (int)$specials_id . "'");
    } elseif ($status == '0') {
      return tep_db_query("update " . TABLE_SPECIALS . " set status = '0', date_status_change = now() where specials_id = '" . (int)$specials_id . "'");
    } else {
      return -1;
    }
  }

////
// Sets timeout for the current script.
// Cant be used in safe mode.
  function tep_set_time_limit($limit) {
    if (!get_cfg_var('safe_mode')) {
      set_time_limit($limit);
    }
  }


  

////
// Alias function for module configuration keys
  function tep_mod_select_option($select_array, $key_name, $key_value) {
    reset($select_array);
    while (list($key, $value) = each($select_array)) {
      if (is_int($key)) $key = $value;
      $string .= '<br><input type="radio" name="configuration[' . $key_name . ']" value="' . $key . '"';
      if ($key_value == $key) $string .= ' CHECKED';
      $string .= '> ' . $value;
    }

    return $string;
  }

////
// Retreive server information
  function tep_get_system_information() {
    global $FREQUEST;

    $db_query = tep_db_query("select now() as datetime");
    $db = tep_db_fetch_array($db_query);

    list($system, $host, $kernel) = preg_split('/[\s,]+/', @exec('uname -a'), 5);
//kodes
    return array('date' => tep_datetime_short(date('Y-m-d H:i:s')),
                 'system' => $system,
                 'kernel' => $kernel,
                 'host' => $host,
                 'ip' => gethostbyname($host),
                 'uptime' => @exec('uptime'),
                 'http_server' => $FREQUEST->servervalue('SERVER_SOFTWARE'),
                 'php' => PHP_VERSION,
                 'zend' => (function_exists('zend_version') ? zend_version() : ''),
                 'db_server' => DB_SERVER,
                 'db_ip' => gethostbyname(DB_SERVER),
                 'db_version' => 'MySQL ' . (function_exists('mysqli_get_server_info') ? mysqli_get_server_info() : ''),
                 'db_date' => tep_datetime_short($db['datetime']));
  }

  function tep_generate_category_path($id, $from = 'category', $categories_array = '', $index = 0) {
    global $FSESSION;

    if (!is_array($categories_array)) $categories_array = array();

    if ($from == 'product') {
      $categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$id . "'");
      while ($categories = tep_db_fetch_array($categories_query)) {
        if ($categories['categories_id'] == '0') {
          $categories_array[$index][] = array('id' => '0', 'text' => TEXT_TOP);
        } else {
          $category_query = tep_db_query("select cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$categories['categories_id'] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "'");
          $category = tep_db_fetch_array($category_query);
          $categories_array[$index][] = array('id' => $categories['categories_id'], 'text' => $category['categories_name']);
          if ( (tep_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = tep_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
          $categories_array[$index] = array_reverse($categories_array[$index]);
        }
        $index++;
      }
    } elseif ($from == 'category') {
      $category_query = tep_db_query("select cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "'");
      $category = tep_db_fetch_array($category_query);
      $categories_array[$index][] = array('id' => $id, 'text' => $category['categories_name']);
      if ( (tep_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = tep_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
    }

    return $categories_array;
  }

  function tep_output_generated_category_path($id, $from = 'category') {
    $calculated_category_path_string = '';
    $calculated_category_path = tep_generate_category_path($id, $from);
    for ($i=0, $n=sizeof($calculated_category_path); $i<$n; $i++) {
      for ($j=0, $k=sizeof($calculated_category_path[$i]); $j<$k; $j++) {
        $calculated_category_path_string .= $calculated_category_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
      }
      $calculated_category_path_string = substr($calculated_category_path_string, 0, -16) . '<br>';
    }
    $calculated_category_path_string = substr($calculated_category_path_string, 0, -4);

    if (strlen($calculated_category_path_string) < 1) $calculated_category_path_string = TEXT_TOP;

    return $calculated_category_path_string;
  }

  function tep_get_generated_category_path_ids($id, $from = 'category') {
    $calculated_category_path_string = '';
    $calculated_category_path = tep_generate_category_path($id, $from);
    for ($i=0, $n=sizeof($calculated_category_path); $i<$n; $i++) {
      for ($j=0, $k=sizeof($calculated_category_path[$i]); $j<$k; $j++) {
        $calculated_category_path_string .= $calculated_category_path[$i][$j]['id'] . '_';
      }
      $calculated_category_path_string = substr($calculated_category_path_string, 0, -1) . '<br>';
    }
    $calculated_category_path_string = substr($calculated_category_path_string, 0, -4);

    if (strlen($calculated_category_path_string) < 1) $calculated_category_path_string = TEXT_TOP;

    return $calculated_category_path_string;
  }

  function tep_remove_category($category_id) {
    $category_image_query = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
    $category_image = tep_db_fetch_array($category_image_query);

    $duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where categories_image = '" . tep_db_input($category_image['categories_image']) . "'");
    $duplicate_image = tep_db_fetch_array($duplicate_image_query);

    if ($duplicate_image['total'] < 2) {
      if (file_exists(DIR_FS_CATALOG_IMAGES . $category_image['categories_image'])) {
        @unlink(DIR_FS_CATALOG_IMAGES . $category_image['categories_image']);
      }
    }

    tep_db_query("delete from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
    tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "'");
    tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");

 }

  function tep_remove_product($product_id) {
    $product_image_query = tep_db_query("select products_image_1 from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
    $product_image = tep_db_fetch_array($product_image_query);

	for ($icnt=1;$icnt<=5;$icnt++)
	{
		$duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_1 = '" . tep_db_input($product_image['products_image_1']) . "'");
		$duplicate_image = tep_db_fetch_array($duplicate_image_query);
	
		if ($duplicate_image['total'] < 2) 
		{
		  if (file_exists(DIR_FS_CATALOG_IMAGES . $product_image['products_image_1'])) 
		  {
			@unlink(DIR_FS_CATALOG_IMAGES . $product_image['products_image_1']);
		  }
		  if (file_exists(DIR_FS_CATALOG_IMAGES . "small/" . $product_image['products_image_1'])) 
		  {
			  @unlink(DIR_FS_CATALOG_IMAGES . "small/" . $product_image['products_image_1']);
		  }
		  if (file_exists(DIR_FS_CATALOG_IMAGES . "big/" . $product_image['products_image_1'])) 
		  {
			  @unlink(DIR_FS_CATALOG_IMAGES . "big/" . $product_image['products_image_1']);
		  }
		}
	}
    tep_db_query("delete from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where products_id = '" . (int)$product_id . "' or products_id like '" . (int)$product_id . "{%'");
	tep_db_query("delete from " . TABLE_PRODUCTS_PRICE_BREAK . " where products_id='".(int)$product_id."'");
   }

  function tep_remove_order($order_id, $restock = false) {
    if ($restock == 'on') {
      $order_query = tep_db_query("select products_id, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
      while ($order = tep_db_fetch_array($order_query)) {
        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order['products_quantity'] . ", products_ordered = products_ordered - " . $order['products_quantity'] . " where products_id = '" . (int)$order['products_id'] . "'");
      }
    }

    tep_db_query("delete from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "'");
  }

  function tep_reset_cache_block($cache_block) {
    global $cache_blocks;

    for ($i=0, $n=sizeof($cache_blocks); $i<$n; $i++) {
      if ($cache_blocks[$i]['code'] == $cache_block) {
        if ($cache_blocks[$i]['multiple'])  {
          if ($dir = @opendir(DIR_FS_CACHE)) {
		     while ($cache_file = readdir($dir)) {
              $cached_file = $cache_blocks[$i]['file'];
              $languages = tep_get_languages();
              for ($j=0, $k=sizeof($languages); $j<$k; $j++) {
                $cached_file_unlink = ereg_replace('-language', '-' . $languages[$j]['directory'], $cached_file);
                if (preg_match('/^' . $cached_file_unlink . '/', $cache_file)) {
                  @unlink(DIR_FS_CACHE . $cache_file);
                }
              }
            }
            closedir($dir);
          }
        } else {
          $cached_file = $cache_blocks[$i]['file'];
          $languages = tep_get_languages();
          for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
            $cached_file = ereg_replace('-language', '-' . $languages[$i]['directory'], $cached_file);
            @unlink(DIR_FS_CACHE . $cached_file);
          }
        }
        break;
      }
    }
  }
	function tep_reset_seo_cache($type){ 
		$fs_cache=DIR_FS_CACHE;
		if (substr($fs_cache,-1,1)!="/") $fs_cache.="/";
		if ($type=="events"){
			@unlink($fs_cache . 'url.events.cache');
		} else if ($type=="services"){
			@unlink($fs_cache . 'url.servi.cache');
		} else if ($type=="subscriptions"){
			@unlink($fs_cache . 'url.subscr.cache');
		} else {
			@unlink($fs_cache . 'url.products.cache');
		}
	}
	
  function tep_get_file_permissions($mode) {
// determine type
    if ( ($mode & 0xC000) == 0xC000) { // unix domain socket
      $type = 's';
    } elseif ( ($mode & 0x4000) == 0x4000) { // directory
      $type = 'd';
    } elseif ( ($mode & 0xA000) == 0xA000) { // symbolic link
      $type = 'l';
    } elseif ( ($mode & 0x8000) == 0x8000) { // regular file
      $type = '-';
    } elseif ( ($mode & 0x6000) == 0x6000) { //bBlock special file
      $type = 'b';
    } elseif ( ($mode & 0x2000) == 0x2000) { // character special file
      $type = 'c';
    } elseif ( ($mode & 0x1000) == 0x1000) { // named pipe
      $type = 'p';
    } else { // unknown
      $type = '?';
    }

// determine permissions
    $owner['read']    = ($mode & 00400) ? 'r' : '-';
    $owner['write']   = ($mode & 00200) ? 'w' : '-';
    $owner['execute'] = ($mode & 00100) ? 'x' : '-';
    $group['read']    = ($mode & 00040) ? 'r' : '-';
    $group['write']   = ($mode & 00020) ? 'w' : '-';
    $group['execute'] = ($mode & 00010) ? 'x' : '-';
    $world['read']    = ($mode & 00004) ? 'r' : '-';
    $world['write']   = ($mode & 00002) ? 'w' : '-';
    $world['execute'] = ($mode & 00001) ? 'x' : '-';

// adjust for SUID, SGID and sticky bit
    if ($mode & 0x800 ) $owner['execute'] = ($owner['execute'] == 'x') ? 's' : 'S';
    if ($mode & 0x400 ) $group['execute'] = ($group['execute'] == 'x') ? 's' : 'S';
    if ($mode & 0x200 ) $world['execute'] = ($world['execute'] == 'x') ? 't' : 'T';

    return $type .
           $owner['read'] . $owner['write'] . $owner['execute'] .
           $group['read'] . $group['write'] . $group['execute'] .
           $world['read'] . $world['write'] . $world['execute'];
  }

  function tep_remove($source) {
    global $messageStack, $tep_remove_error;

    if (isset($tep_remove_error)) $tep_remove_error = false;

    if (is_dir($source)) {
      $dir = dir($source);
      while ($file = $dir->read()) {
        if ( ($file != '.') && ($file != '..') ) {
          if (is_writeable($source . '/' . $file)) {
            tep_remove($source . '/' . $file);
          } else {
            $messageStack->add(sprintf(ERROR_FILE_NOT_REMOVEABLE, $source . '/' . $file), 'error');
            $tep_remove_error = true;
          }
        }
      }
      $dir->close();

      if (is_writeable($source)) {
        rmdir($source);
      } else {
        $messageStack->add(sprintf(ERROR_DIRECTORY_NOT_REMOVEABLE, $source), 'error');
        $tep_remove_error = true;
      }
    } else {
      if (is_writeable($source)) {
        unlink($source);
      } else {
        $messageStack->add(sprintf(ERROR_FILE_NOT_REMOVEABLE, $source), 'error');
        $tep_remove_error = true;
      }
    }
  }

////
// Output the tax percentage with optional padded decimals
  function tep_display_tax_value($value, $padding = TAX_DECIMAL_PLACES) {
    if (strpos($value, '.')) {
      $loop = true;
      while ($loop) {
        if (substr($value, -1) == '0') {
          $value = substr($value, 0, -1);
        } else {
          $loop = false;
          if (substr($value, -1) == '.') {
            $value = substr($value, 0, -1);
          }
        }
      }
    }

    if ($padding > 0) {
      if ($decimal_pos = strpos($value, '.')) {
        $decimals = strlen(substr($value, ($decimal_pos+1)));
        for ($i=$decimals; $i<$padding; $i++) {
          $value .= '0';
        }
      } else {
        $value .= '.';
        for ($i=0; $i<$padding; $i++) {
          $value .= '0';
        }
      }
    }

    return $value;
  }

  function tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address) {
    if (SEND_EMAILS != 'true') return false;

    // Instantiate a new mail object
    $message = new email(array('X-Mailer: osConcert'));

    // Build the text version
    $text = strip_tags($email_text);
    if (EMAIL_USE_HTML == 'true') {
      $message->add_html($email_text, $text);
    } else {
      $message->add_text($text);
    }
	 if(defined('DEBUGGING_MODE_VIEW') && (DEBUGGING_MODE_VIEW=='1' || DEBUGGING_MODE_VIEW=='true')){
	   //echos($text);
	}

    // Send message
    $message->build_message();    	
    $message->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject);
  }

  function tep_get_tax_class_title($tax_class_id) {
    if ($tax_class_id == '0') {
      return TEXT_NONE;
    } else {
      $classes_query = tep_db_query("select tax_class_title from " . TABLE_TAX_CLASS . " where tax_class_id = '" . (int)$tax_class_id . "'");
      $classes = tep_db_fetch_array($classes_query);

      return $classes['tax_class_title'];
    }
  }

  function tep_banner_image_extension() {
    if (function_exists('imagetypes')) {
      if (imagetypes() & IMG_PNG) {
        return 'png';
      } elseif (imagetypes() & IMG_JPG) {
        return 'jpg';
      } elseif (imagetypes() & IMG_GIF) {
        return 'gif';
      }
    } elseif (function_exists('imagecreatefrompng') && function_exists('imagepng')) {
      return 'png';
    } elseif (function_exists('imagecreatefromjpeg') && function_exists('imagejpeg')) {
      return 'jpg';
    } elseif (function_exists('imagecreatefromgif') && function_exists('imagegif')) {
      return 'gif';
    }

    return false;
  }

////
// Wrapper function for round() for php3 compatibility
  function tep_round($value, $precision) {
    if (PHP_VERSION < 4) {
      $exp = pow(10, $precision);
      return round($value * $exp) / $exp;
    } else {
      return round($value, $precision);
    }
  }

////
// Add tax to a products price
  function tep_add_tax($price, $tax) {
    global $currencies;

    if (DISPLAY_PRICE_WITH_TAX == 'true') 
	{
      return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']) + tep_calculate_tax($price, $tax);
    } else {
      return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
    }
  }

// Calculates Tax rounding the result
  function tep_calculate_tax($price, $tax) {
    global $currencies;
    return tep_round($price * $tax / 100, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
  }

////
// Returns the tax rate for a zone / class
// TABLES: tax_rates, zones_to_geo_zones
  function tep_get_tax_rate($class_id, $country_id = -1, $zone_id = -1) {
    global $customer_zone_id, $customer_country_id,$FSESSION;

    if ( ($country_id == -1) && ($zone_id == -1) ) {
      if (!$FSESSION->is_registered('customer_id')) {
        $country_id = STORE_COUNTRY;
        $zone_id = STORE_ZONE;
      } else {
        $country_id = $FSESSION->customer_country_id;
        $zone_id = $FSESSION->customer_zone_id;
      }
    }
    $tax_query = tep_db_query("select SUM(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za ON tr.tax_zone_id = za.geo_zone_id left join " . TABLE_GEO_ZONES . " tz ON tz.geo_zone_id = tr.tax_zone_id WHERE (za.zone_country_id IS NULL OR za.zone_country_id = '0' OR za.zone_country_id = '" . (int)$country_id  . "') AND (za.zone_id IS NULL OR za.zone_id = '0' OR za.zone_id = '" . (int)$zone_id . "') AND tr.tax_class_id = '" . (int)$class_id . "' GROUP BY tr.tax_priority");
	if (tep_db_num_rows($tax_query)) {
      $tax_multiplier = 0;
      while ($tax = tep_db_fetch_array($tax_query)) {
        $tax_multiplier += $tax['tax_rate'];
      }
      return $tax_multiplier;
    } else {
      return 0;
    }
  }

////
// Returns the tax rate for a tax class
// TABLES: tax_rates
  function tep_get_tax_rate_value($class_id) {
    $tax_query = tep_db_query("select SUM(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " where tax_class_id = '" . (int)$class_id . "' group by tax_priority");
    if (tep_db_num_rows($tax_query)) {
      $tax_multiplier = 0;
      while ($tax = tep_db_fetch_array($tax_query)) {
        $tax_multiplier += $tax['tax_rate'];
      }
      return $tax_multiplier;
    } else {
      return 0;
    }
  }

  function tep_call_function($function, $parameter, $object = '') { 
    if ($object == '') {
	   return call_user_func($function, $parameter);
    } elseif (PHP_VERSION < 4) {
      return call_user_method($function, $object, $parameter);
    } else {
      return call_user_func(array($object, $function), $parameter);
    }
  }

  function tep_get_zone_class_title($zone_class_id) {
		$geo_zone_name = '';
		if ($zone_class_id!=""){
			$geo_zone_sql = "select geo_zone_name from " . TABLE_GEO_ZONES . " where geo_zone_id in(" . tep_db_input($zone_class_id) . ")";
			$geo_zone_query = tep_db_query($geo_zone_sql);
	
			while($geo_zone_result = tep_db_fetch_array($geo_zone_query)){
				$geo_zone_name.=$geo_zone_result["geo_zone_name"] . ",";
			}
		}		
		if($geo_zone_name=='')$geo_zone_name = TEXT_NONE;
		else $geo_zone_name=substr($geo_zone_name,0,-1);
		return $geo_zone_name;
  }
  
	function tep_get_shipping_zone_class_title($zone_class_id){
		$geo_zone_name = '';
		if ($zone_class_id!=""){
			$geo_zone_sql = "select geo_zone_name from " . TABLE_GEO_ZONES . " where geo_zone_id in(" . tep_db_input($zone_class_id) . ")";
			$geo_zone_query = tep_db_query($geo_zone_sql);
	
			while($geo_zone_result = tep_db_fetch_array($geo_zone_query)){
				$geo_zone_name.=$geo_zone_result["geo_zone_name"] . ",";
			}
		}		
		if($geo_zone_name=='')$geo_zone_name = TEXT_NONE;
		else $geo_zone_name=substr($geo_zone_name,0,-1);
		return $geo_zone_name;
	}  

 /* function tep_cfg_pull_down_zone_classes($zone_class_id, $key = '') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $zone_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $zone_class_query = tep_db_query("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " order by geo_zone_name");
    while ($zone_class = tep_db_fetch_array($zone_class_query)) {
      $zone_class_array[] = array('id' => $zone_class['geo_zone_id'],
                                  'text' => $zone_class['geo_zone_name']);
    }

    return tep_draw_pull_down_menu($name, $zone_class_array, $zone_class_id);
  }*/
  
   function tep_cfg_pull_down_zone_classes($zone_class_id, $key = '',$country_control='default',$country_key='default') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

   $zone_original=$zone_class_id;
   $zone_class_id="," .$zone_class_id .",";
   $zone_class_sql = "select distinct gz.geo_zone_id, gz.geo_zone_name from " . TABLE_GEO_ZONES . " gz," . TABLE_ZONES_TO_GEO_ZONES . " zgz where gz.geo_zone_id=zgz.geo_zone_id order by geo_zone_name";
   //$zone_class_sql = "select distinct gz.geo_zone_id, gz.geo_zone_name from " . TABLE_GEO_ZONES . " gz order by geo_zone_name";
   $zone_class_query = tep_db_query($zone_class_sql);
   $result.='<input type="hidden" name="' . $name . '" value="' . $zone_original . '" id="' . $name .'">';
   $result.='<div style="height:100px;overflow:auto;width:200px;vertical-align:top"><table border="0" width="100%" cellpadding="0" cellspacing="0" valign="top">';
   $icnt=0;
    while ($zone_class = tep_db_fetch_array($zone_class_query)) {
		$checked='';
		if (strpos($zone_class_id,"," . $zone_class['geo_zone_id'] . ",")!==FALSE) $checked=" checked ";
		$result.='<tr><td valign="top" class="smallText" style="height:4px" valign="top"><input type=checkbox name="check_' . $name. '" id="check_' . $icnt . '_' . $name . '" value="' . $zone_class['geo_zone_id'] .'"' . $checked . ' onClick="javascript:check_selected_values(this,\'' . $name . '\',\'' . $country_control . '\',\'' . $country_key . '\');">' . $zone_class['geo_zone_name'] . '</td>';
		$icnt++;
    }
	$result.='<input type="hidden" id="count_' . $name . '" value="' . ($icnt-1) . '">';
	$result.='</tr>';
	$result.='</table>';
	$result.='</div>';
    return $result;
  }
  
   function tep_cfg_pull_down_shipping_zone_classes($zone_class_id, $key = '',$country_control='default',$country_key='default') {
  //  $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

   /* $zone_class_array = array(array('id' => '0', 'text' => TEXT_NONE),
								array('id'=>'-1', 'text'=>TEXT_WORLD_ZONES),
								array('id'=>'13', 'text'=>TEXT_AUSTRALIAN_ZONES));*/
  /* $zone_original=$zone_class_id;
   $zone_class_id="," .$zone_class_id .",";
   $zone_class_query = tep_db_query("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " order by geo_zone_name");

   $result.='<input type="hidden" name="' . $name . '" value="' . $zone_original . '">';
   $result.='<div style="height:100px;overflow:auto;width:200px;vertical-align:top"><table border="0" width="100%" cellpadding="0" cellspacing="0" valign="top">';
    while ($zone_class = tep_db_fetch_array($zone_class_query)) {
		$checked='';
		if (strpos($zone_class_id,"," . $zone_class['geo_zone_id'] . ",")!==FALSE) $checked=" checked ";
		$result.='<tr><td valign="top" class="smallText"><input type=checkbox name="config_check_shipping" id="config_check_shipping" value="' . $zone_class['geo_zone_id'] .'"' . $checked . ' onClick="javascript:check_selected_zone(this,\'' . $name . '\');">&nbsp;' . $zone_class['geo_zone_name'] . '<td width="20"><a href="' . tep_href_link(FILENAME_PRODUCTS_FREIGHT,'zID='. $zone_class['geo_zone_id']) . '">' . tep_image(DIR_WS_ICONS . 'image_edit.gif','Update Frieght') . '</a></td></tr>';
    }
	$result.='<tr>';
	$result.='</table>';
	$result.='</div>';
    return $result; */
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

   $zone_original=$zone_class_id;
   $zone_class_id="," .$zone_class_id .",";
   $zone_class_query = tep_db_query("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " order by geo_zone_name");
   $result.='<input type="hidden" name="' . $name . '" value="' . $zone_original . '" id="' . $name . '">';
   $result.='<div style="height:100px;overflow:auto;width:200px;vertical-align:top"><table border="0" width="100%" cellpadding="0" cellspacing="0" valign="top">';
   $icnt=0;
    while ($zone_class = tep_db_fetch_array($zone_class_query)) {
		$checked='';
		if (strpos($zone_class_id,"," . $zone_class['geo_zone_id'] . ",")!==FALSE) $checked=" checked ";
		$result.='<tr><td valign="top" class="smallText" style="height:4px" valign="top"><input type=checkbox name="check_' . $name. '" id="check_' . $icnt . "_"  .$name . '" value="' . $zone_class['geo_zone_id'] .'"' . $checked . ' onClick="javascript:check_selected_values(this,\'' . $name . '\',\'' . $country_control . '\',\'' . $country_key . '\');">' . $zone_class['geo_zone_name'] . '</td>'.'<td width="20"><a href="' . tep_href_link(FILENAME_PRODUCTS_FREIGHT,'zID='. $zone_class['geo_zone_id']) . '">' . tep_image(DIR_WS_ICONS . 'image_edit.gif','Update Frieght') . '</a></td>';
		$icnt++;
    }
	$result.='<input type="hidden" id="count_' . $name . '" value="' . ($icnt-1) . '">';
	$result.='</tr>';
	$result.='</table>';
	$result.='</div>';
    return $result;
  }

   /*function tep_cfg_pull_down_shipping_zone_classes($zone_class_id, $key = '') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

   $zone_class_query = tep_db_query("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " order by geo_zone_name");
    while ($zone_class = tep_db_fetch_array($zone_class_query)) {
      $zone_class_array[] = array('id' => $zone_class['geo_zone_id'],
                                  'text' => $zone_class['geo_zone_name']);
    }
	$result = tep_draw_pull_down_menu($name, $zone_class_array, $zone_class_id, 'id="' . $name . '"') . '<br><br>&nbsp;&nbsp;&nbsp;' . tep_image_button('button_edit.gif', IMAGE_EDIT,'onClick=javascript:getZones(\'' . $name . '\');');
    return $result;
  }*/
  
  function tep_cfg_pull_down_order_statuses($order_status_id, $key = '') {
    global $FSESSION;

    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $statuses_array = array(array('id' => '0', 'text' => TEXT_DEFAULT));
    //$statuses_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$FSESSION->languages_id . "' and lower(orders_status_name)!='refunded' order by orders_status_name");
	
	$statuses_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$FSESSION->languages_id . "' and orders_status_id!='5' order by orders_status_name");
    while ($statuses = tep_db_fetch_array($statuses_query)) {
      $statuses_array[] = array('id' => $statuses['orders_status_id'],
                                'text' => $statuses['orders_status_name']);
    }

    return tep_draw_pull_down_menu($name, $statuses_array, $order_status_id);
  }

  function tep_get_order_status_name($order_status_id, $language_id = '') {
    global $FSESSION;

    if ($order_status_id < 1) return TEXT_DEFAULT;

    if (!is_numeric($language_id)) $language_id = $FSESSION->languages_id;

    $status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . (int)$order_status_id . "' and language_id = '" . (int)$language_id . "'");
    $status = tep_db_fetch_array($status_query);

    return $status['orders_status_name'];
  }

////
// Return a random value
  function tep_rand($min = null, $max = null) {
    static $seeded;

    if (!$seeded) {
      mt_srand((double)microtime()*1000000);
      $seeded = true;
    }

    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }

// nl2br() prior PHP 4.2.0 did not convert linefeeds on all OSs (it only converted \n)
  function tep_convert_linefeeds($from, $to, $string) {
    if ((PHP_VERSION < "4.0.5") && is_array($from)) {
      return ereg_replace('(' . implode('|', $from) . ')', $to, $string);
    } else {
      return str_replace($from, $to, $string);
    }
  }

  function tep_string_to_int($string) {
    return (int)$string;
  }

////
// Parse and secure the cPath parameter values
  function tep_parse_category_path($cPath) {
// make sure the category IDs are integers
    $cPath_array = array_map('tep_string_to_int', explode('_', $cPath));

// make sure no duplicate category IDs exist which could lock the server in a loop
    $tmp_array = array();
    $n = sizeof($cPath_array);
    for ($i=0; $i<$n; $i++) {
      if (!in_array($cPath_array[$i], $tmp_array)) {
        $tmp_array[] = $cPath_array[$i];
      }
    }

    return $tmp_array;
  }
// Alias function for array of configuration values in the Administration Tool
  function tep_cfg_select_multioption($select_array, $key_value, $key = ''){
  	  $check_array=array('P' =>'Products','E'=>'Events','S' =>'Subscriptions','V' =>'Services');
  	  for($i=0;$i<sizeof($select_array);$i++){
		$name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
		$checked="";
		if($key==''){
			$string .= '<input type="checkbox" name="check_' . $name . '" id="check_' . $name . '" value="' . $select_array[$i] . '"';
			if (strpos($key_value, $select_array[$i] )!==FALSE) $string.=" CHECKED "; 
			  $string.=' onClick="javascript:check_selected_values(this,\'' . $name . '\');" >'.$check_array[$select_array[$i]];
		}else {
			$string .= '<br><input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '"';		
      		$key_values = explode(", ", $key_value);
      		if (in_array($select_array[$i],$key_values) ) $string .= ' CHECKED ';
      		$string .= '> ' . $select_array[$i];
		}
	  }
	  return $string;
  } 
  ////
// Alias function for Store configuration values in the Administration Tool

  
  function tep_cfg_select_option($select_array, $key_value, $key = '') {
    $string = '';
    for ($i=0, $n=sizeof($select_array); $i<$n; $i++) {
    	 $name = ((tep_not_null($key)) ? 'configuration[' . $key . ']' : 'configuration_value');
     if($i%2 && $i!=0)
	  $string .= '<br>';
      $string .= '<input type="radio" name="' . $name . '" value="' . $select_array[$i] . '"'.'&nbsp;';

      if ($key_value == $select_array[$i]) $string .= ' CHECKED';

      $string .= '> ' . $select_array[$i];
    }

    return $string;
  }
  
  function tep_cfg_select_option_title($value){
  	$check_array=array('P' =>'Products','E'=>'Events','S' =>'Subscriptions','V' =>'Services');
	for($i=0;$i<strlen($value);$i++){
		$check_value=substr($value,$i,1);
		$result.=$check_array[$check_value] .",";
	}
	if($result!='')
		$result=substr($result,0,-1);
	return $result;
  }
  
   function tep_cfg_password_option($select_array, $key_value, $key = '') {
    $string = '';
  	$check_array=array('1' =>'no checking','2'=>'alphanumeric','3' =>'alphanumeric + symbols');
    for ($i=0, $n=sizeof($select_array); $i<$n; $i++) {
    	  $name = ((tep_not_null($key)) ? 'configuration[' . $key . ']' : 'configuration_value');
		  
	 $string .= '<input type="radio" name="' . $name . '" value="' . $select_array[$i] . '"'.'&nbsp;';

      if ($key_value == $select_array[$i]) $string .= ' CHECKED';

      $string .= '> ' . $check_array[$select_array[$i]];
    }

    return $string;
  }
  
  /*  function tep_cfg_password_option($select_array, $key_value, $key = '') {
    $string = '';
  	$check_array=array('1' =>'no checking','2'=>'alphanumeric','3' =>'alphanumeric + symbols','4' =>'alphanumeric + symbols + dictionary words');
	$string .='<table cellpadding="0" cellspacing="0" width="0" border="0"><tr>';
    for ($i=0, $n=sizeof($select_array); $i<$n; $i++) {
    	  $name = ((tep_not_null($key)) ? 'configuration[' . $key . ']' : 'configuration_value');
		  
	
      $string .= '<td nowrap="nowrap"><input type="radio" name="' . $name . '" value="' . $select_array[$i] . '"'.'&nbsp;';

      if ($key_value == $select_array[$i]) $string .= ' CHECKED';

      $string .= '> ' . $check_array[$select_array[$i]] .'<td>';
	  if($i=='1') $string .='</tr><tr>';
    }
	$string .='</tr></table>';
    return $string;
  }*/
  
    function tep_get_password_option_title($value){
  	$check_array=array('1' =>'no checking','2'=>'alphanumeric','3' =>'alphanumeric + symbols');
	for($i=0;$i<strlen($value);$i++){
		$check_value=substr($value,$i,1);
		$result.=$check_array[$check_value] .",";
	}
	if($result!='')
		$result=substr($result,0,-1);
	return $result;
  }
  
//create a select list to display list of themes available for selection
  function tep_cfg_pull_down_template_list($template_id, $key = '') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $template_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " order by template_name");
    while ($template = tep_db_fetch_array($template_query)) {
      $template_array[] = array('id' => $template['template_name'],
                                 'text' => $template['template_name']);
    }

    return tep_draw_pull_down_menu($name, $template_array, $template_id);
  }

 //create a select list of  product listing navigation bar
    function tep_cfg_pull_down_navigation_type($id,$key='')
	{
	    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
		$minimum_type_array = array(array('id' =>1,'text'=>'top'),array('id'=>2,'text'=>'bottom'),array('id'=>3,'text'=>'both'));
		return tep_draw_pull_down_menu($name, $minimum_type_array, $id);	
	} 
	function tep_get_order_count($products_id,$where_clause='1=1')
	{
		$res = tep_db_query("SELECT sum(ord.products_quantity) as total_quantity from " . TABLE_ORDERS_PRODUCTS . " ord," . TABLE_ORDERS . " o where o.orders_id=ord.orders_id and ord.products_type='P' and ord.orders_products_status!=3 and o.orders_status=2 and ord.products_id=" . (int)$products_id . " and " . $where_clause);
		$row = tep_db_fetch_array($res);
		return $row[0]; 
	}
	function tep_get_quantity_remaining($products_id)
	{
		$res = tep_db_query("SELECT (products_quantity-products_ordered) as products_remaining  from " . TABLE_PRODUCTS . " where products_id=" . tep_db_input($products_id));
		$row = tep_db_fetch_array($res);
		return $row[0]; 
	}
  function tep_get_group_id($grpname) {
	$group_query = tep_db_query("select admin_groups_id from " . TABLE_ADMIN_GROUPS . " where admin_groups_name= '" . tep_db_input($grpname) . "'");
	if (tep_db_num_rows($group_query)) {
		$groups = tep_db_fetch_array($group_query);
		return $groups['admin_groups_id'];
	} else {
		return 0;
	}
  }
  function tep_set_customer_admin_groups($customer_id, $is_blocked) {
      return tep_db_query("update " . TABLE_CUSTOMERS . " set is_blocked = '" . $is_blocked. "' where customers_id = '" . (int)$customer_id . "'");
  }

	function tep_cfg_user_defined_text_field($assign,$value,$text,$key=''){
		$name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
		if($assign=="before"){
			return "&nbsp;<b>" . $value . "</b>&nbsp;".tep_draw_input_field($name,$text);
		}else if($assign=="after"){
			return tep_draw_input_field($name,$text) . "&nbsp;<b>" . $value . "</b>&nbsp;";
		}
	}
	function tep_cfg_file_field($name,$value,$type='file'){
		return "<input class='inputNormal' type='" . $type . "' name='" . $name . "'><br>" . $value;
	}


require(DIR_WS_FUNCTIONS . 'downloads_controller.php');

	//September 2012 Graeme a little recursive function to reset staus of all sub categories
	// first input the category found on the catalog side - not found in admin
	  function tep_get_subcategories(&$subcategories_array, $parent_id = 0) {
    $subcategories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$parent_id . "'");
    while ($subcategories = tep_db_fetch_array($subcategories_query)) {
      $subcategories_array[sizeof($subcategories_array)] = $subcategories['categories_id'];
      if ($subcategories['categories_id'] != $parent_id) {
        tep_get_subcategories($subcategories_array, $subcategories['categories_id']);
      }
    }
  }
  	// now a new function to change the category status - have added the opportunity to changethe status rather than a hardcoded one (may be useful if we ever have a starting date)
function tep_change_category_status_off($parent_id) {
   		//change the parent cat id
      		 tep_db_query("UPDATE " . TABLE_CATEGORIES . " set categories_status='0', date_expires='0000-00-00 00:00:00' where categories_id='".(int)$parent_id."'");
			 change_product_status((int)$parent_id,'1','9');
		      $subcategories_array = array();
   			  tep_get_subcategories($subcategories_array, $parent_id);
				  for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
					 tep_db_query("UPDATE " . TABLE_CATEGORIES . " set categories_status='0', date_expires='0000-00-00 00:00:00' where categories_id='".(int)$subcategories_array[$i]."'");
					  change_product_status((int)$subcategories_array[$i],'1','9');
      }				

      
    }
	
function tep_change_category_status_off2($parent_id) {
   		//change the parent cat id
      		 tep_db_query("UPDATE " . TABLE_CATEGORIES . " set categories_status='0' where categories_id='".(int)$parent_id."'");
			 change_product_status((int)$parent_id,'1','9');
		      $subcategories_array = array();
   			  tep_get_subcategories($subcategories_array, $parent_id);
				  for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
					 tep_db_query("UPDATE " . TABLE_CATEGORIES . " set categories_status='0' where categories_id='".(int)$subcategories_array[$i]."'");
					  change_product_status((int)$subcategories_array[$i],'1','9');
      }				

      
    }
      		
function tep_change_category_status_on($parent_id) {
   		//change the parent cat id
      		 tep_db_query("UPDATE " . TABLE_CATEGORIES . " set categories_status='1', date_commences='0000-00-00 00:00:00' where categories_id='".(int)$parent_id."'");
			 change_product_status((int)$parent_id,'9','1');
		      $subcategories_array = array();
   			  tep_get_subcategories($subcategories_array, $parent_id);
				  for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
				  //if current cat has a plan id of 2 and the parent id is 3 then skip the status change
				  if(get_parent_plan_id((int)$subcategories_array[$i])){
					 tep_db_query("UPDATE " . TABLE_CATEGORIES . " set categories_status='1', date_commences='0000-00-00 00:00:00' where categories_id='".(int)$subcategories_array[$i]."' and plan_id <> '1'");}
					  change_product_status((int)$subcategories_array[$i],'9','1');
					  }
      }				

      
    
// change a products_status 
function change_product_status($cat_id,$start,$end){

  					tep_db_query("UPDATE " . TABLE_PRODUCTS . " 
					 			  JOIN ".TABLE_PRODUCTS_TO_CATEGORIES . "
								  ON " . TABLE_PRODUCTS . ".products_id = ".TABLE_PRODUCTS_TO_CATEGORIES . ".products_id 
								  SET " . TABLE_PRODUCTS . ".products_status='".$end."' 
								  WHERE
								  " . TABLE_PRODUCTS . ".products_status= '".$start."'
								  AND
								  ".TABLE_PRODUCTS_TO_CATEGORIES . ".categories_id = '".$cat_id."'
								  ");
}

//get the parent plan_id

function get_parent_plan_id($cat_id){

			$categories_query =tep_db_query("select categories_id, parent_id, plan_id
						from " . TABLE_CATEGORIES . " 
						where categories_id='".$cat_id."' order by categories_id ASC limit 1
						");
						
						
			while($result = tep_db_fetch_array($categories_query)){
			
				if($result['plan_id']<>2){return true; break;}
				
						$plan_query =tep_db_query("select plan_id
						from " . TABLE_CATEGORIES . " 
						where categories_id='".$result['parent_id']."'
						");
						while($result2 = tep_db_fetch_array($plan_query)){	
								$plan_id= $result2['plan_id'];					
						}
						}
			if ($plan_id==3){return false;}else{return true;}
						
}
 //July 2013 new function to update master quantity
	function ga_update($product_id, $prod_quantity, $prod_type)
	{
	$result = array();
	//take the product id, find the cPath, explode it and then run a check to see if any cat has a GA setting
	$ga_path_array = array();
	$ga_path_array = explode('_', ga_get_product_path($product_id));//n.b. tep_get_product path requires products_status=1 
	//$ga_path_array = array_reverse($ga_path_array);
	$max = sizeof($ga_path_array);

	if ($max >0){
		for ($i=0; $i<$max; $i++) {
	
	// reduce ga_amount by this product's quantity

			 $category_ga_query = tep_db_query("select categories_GA,categories_quantity_remaining from " . TABLE_CATEGORIES . " where categories_id = '" . $ga_path_array[$i]. "' limit 1");
				 if (tep_db_num_rows($category_ga_query)) { 
				  $category_ga = tep_db_fetch_array($category_ga_query);
							
						if(($prod_type=='G' && $category_ga['categories_GA'] == 1))
						{
											 
						$quantity_left_ga=(($category_ga['categories_quantity_remaining'])+($prod_quantity));
						 //update the master quantity
							tep_db_query("update " . TABLE_CATEGORIES . " set categories_quantity_remaining = '" .$quantity_left_ga . "' where categories_id = '" . $ga_path_array[$i] . "'");
						}elseif($prod_type=='F' && $category_ga['categories_GA'] == 1)
						{
											 
						$quantity_left_ga=(($category_ga['categories_quantity_remaining'])+FAMILY_TICKET_QTY);
						 //update the master quantity
						 tep_db_query("update " . TABLE_CATEGORIES . " set categories_quantity_remaining = '" .$quantity_left_ga . "' where categories_id = '" . $ga_path_array[$i] . "'");
						}//end elseif
								
								
		  }//end tep_db_num_rows
		}
	   }
	}
	## July 2013 these ga_ functions taken from cataolg/includes/functions/ga_tickets.php
	function ga_get_children($parent_id, $tree_string=array()) 
	{
    $tree = array();
    // getOneLevel() returns a one-dimensional array of child ids        
    $tree = ga_get_one_level($parent_id);     
    if(count($tree)>0 && is_array($tree)){      
        $tree_string=array_merge($tree_string,$tree);
    }
    foreach ($tree as $key => $val) {
        ga_get_children($val, $tree_string);
    }   
    return $tree_string;
	}
	function ga_get_one_level($catId)
	{
    $query=tep_db_query("SELECT categories_id FROM categories WHERE parent_id='".$catId."'");
    $cat_id=array();
    if(tep_db_num_rows($query)>0){
        while($result=tep_db_fetch_assoc($query)){
            $cat_id[]=$result['categories_id'];
        }
    }   
    return $cat_id;
	}
    function ga_get_product_path($products_id) {
    $cPath = '';	
	//echo "select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1";

    $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "'  and p.products_id = p2c.products_id limit 1");
    if (tep_db_num_rows($category_query)) {
      $category = tep_db_fetch_array($category_query);

      $categories = array();
      tep_get_parent_categories($categories, $category['categories_id']);

      $categories = array_reverse($categories);

      $cPath = implode('_', $categories);

      if (tep_not_null($cPath)) $cPath .= '_';
      $cPath .= $category['categories_id'];
    }

    return $cPath;
  }
  
    function tep_get_parent_categories(&$categories, $categories_id) {
    $parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id . "'");
    while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
      if ($parent_categories['parent_id'] == 0) return true;
      $categories[sizeof($categories)] = $parent_categories['parent_id'];
      if ($parent_categories['parent_id'] != $categories_id) {
        tep_get_parent_categories($categories, $parent_categories['parent_id']);
      }
    }
  }
  

   function tep_validate_ip_address($ip_address) {
    return filter_var($ip_address, FILTER_VALIDATE_IP, array('flags' => FILTER_FLAG_IPV4));
  }

  function tep_get_ip_address() {
    global $_SERVER;

    $ip_address = null;
    $ip_addresses = array();

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      foreach ( array_reverse(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])) as $x_ip ) {
        $x_ip = trim($x_ip);

        if (tep_validate_ip_address($x_ip)) {
          $ip_addresses[] = $x_ip;
        }
      }
    }

    if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip_addresses[] = $_SERVER['HTTP_CLIENT_IP'];
    }

    if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && !empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
      $ip_addresses[] = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    }

    if (isset($_SERVER['HTTP_PROXY_USER']) && !empty($_SERVER['HTTP_PROXY_USER'])) {
      $ip_addresses[] = $_SERVER['HTTP_PROXY_USER'];
    }

    $ip_addresses[] = $_SERVER['REMOTE_ADDR'];

    foreach ( $ip_addresses as $ip ) {
      if (!empty($ip) && tep_validate_ip_address($ip)) {
        $ip_address = $ip;
        break;
      }
    }

    return $ip_address;
  }

  
  
  
  	function tep_get_row_status_tree($products_status = '0', $spacing = '', $exclude = '', $rows_tree_array = '', $include_itself = false,$default='In Stock') 
	{
		
		if (!is_array($rows_tree_array)) $rows_tree_array = array();
		if ( (sizeof($rows_tree_array) < 1) && ($exclude != '0') ) 
			$rows_tree_array[] = array('id' => '1', 'text' => $default);
		
		// if ($include_itself) 
		// {
			// $rows_query = tep_db_query("select rs.row_status_name from row_status rs");
			// $rows = tep_db_fetch_array($rows_query);
			// $rows_tree_array[] = array('id' => $products_status, 'text' => $rows['row_status_name']);
		// }
		
		$rows_query = tep_db_query("select rs.products_status, rs.row_status_name from row_status rs");
		while ($rows = tep_db_fetch_array($rows_query)) {
			if ($exclude != $rows['products_status']) $rows_tree_array[] = array('id' => $rows['products_status'], 'text' => $spacing . $rows['row_status_name']);
		}
		
		return $rows_tree_array;
	}
	
	function update_season_queue($order_id, $order_status){
	  global $FSESSION;
	  //update the flag field to match the order status
	  if(is_int($order_id) && is_int($order_status)){
		   tep_db_query("UPDATE `coupon_season_queue` set `flag` = '".$order_status."' where  `order_id` = '".$order_id."'");		  
	  }
	  // now update  the entries where flag =3 	   
	   	if($order_status==3) {
			
		//how many season tickets to grant
		 $season_query = tep_db_query("select amount, customer_id from `coupon_season_queue`  where  `order_id` = '".$order_id."'");

		$season_count =tep_db_num_rows($season_query);
		if ($season_count > 0 ){
        $season_result = tep_db_fetch_array($season_query);
        $season_amount = $season_result['amount'];

		 
		 //is there any in the account already?

		
        $gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $season_result['customer_id'] . "'");
		$gv_count =tep_db_num_rows($gv_query);
		if ($gv_count > 0 ){
        $gv_result = tep_db_fetch_array($gv_query);
        $gv_amount = $gv_result['amount'] + $season_amount;
        $gv_update = tep_db_query("update " . TABLE_COUPON_GV_CUSTOMER . " set amount = '" . $gv_amount . "' where customer_id = '" . $season_result['customer_id'] . "'");}
		
		 else{
		  	$sql_data_array = array(
							'customer_id' => $season_result['customer_id'], 
							'amount' => $season_amount							
							);
    		tep_db_perform(TABLE_COUPON_GV_CUSTOMER, $sql_data_array);
		 }
		 	// now lets remove the queue entry
        	  tep_db_query("DELETE FROM `coupon_season_queue` where `customer_id`='".$season_result['customer_id'] ."' and `order_id` = '".$order_id."'");
			//and note the order
				$sql_data_array = array(
						'orders_id' => $order_id,
						'orders_status_id' =>  $order_status,
						'date_added' => date('Y-m-d H:i:s',getServerDate(false)),
						'customer_notified' => 0,
						'comments' => $season_amount . ' season tickets released',
						'user_added'=>"web"
						);
						
	tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
		}
		}
  }  


	function tep_get_tax_description($class_id, $country_id, $zone_id) {
    $tax_query = tep_db_query("select tax_description from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' order by tr.tax_priority");
    if (tep_db_num_rows($tax_query)) {
      $tax_description = '';
      while ($tax = tep_db_fetch_array($tax_query)) {
        $tax_description .= $tax['tax_description'] . ' + ';
      }
      $tax_description = substr($tax_description, 0, -3);

      return $tax_description;
    } else {
     // return TEXT_UNKNOWN_TAX_RATE;
	 return 'Unknown Tax Rate';
    }
  }

  // function tep_display_attributes($products_id=0){
  		// global $currencies;
  		// $result=""; 
  		// //$attribute_query=tep_db_query("select products_options,products_options_values,options_values_price from ".TABLE_ORDERS_PRODUCTS_ATTRIBUTES." where orders_id='".(int)$orders_id."' order by orders_products_id,products_options");
  		// $attribute_query=tep_db_query("SELECT pa.options_values_price as options_values_price,po.products_options_name as products_options,pov.products_options_values_name as products_options_values FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON po.products_options_id=pa.options_id LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov ON pov.products_options_values_id=pa.options_values_id WHERE products_id='$products_id' and pa.options_id=po.products_options_id and pa.options_values_id=pov.products_options_values_id group by products_options_values_id order by products_options_name");
		 
  		// if(tep_db_num_rows($attribute_query)>0){
  		 // $result.="<tr><td colspan='9' class='smalltext'><table border='0' cellspacing='0' cellpadding='0' width='60%'>";
  		 // while($attributes=tep_db_fetch_array($attribute_query)){
  			// $result.="<tr><td width='15%'></td>".
  			// "<td width='23%' align='left' class='smalltext' nowrap>".$attributes['products_options']."</td>". 
  			// "<td width='20%' align='left' class='smalltext' nowrap>".$attributes['products_options_values']."</td>".
  			// "<td width='25%' align='left' class='smalltext' nowrap>".$currencies->format($attributes['options_values_price'])."</td></tr>";  			
  		 // } 
  		 // $result.="</table></td></tr>";
  		// }
  		// return $result;
  // }
  function tep_admin_name($admin_id) {
    $admin = tep_db_query("select admin_firstname, admin_lastname from " . TABLE_ADMIN . " where admin_id = '" . (int)$admin_id . "'");
    $admin_values = tep_db_fetch_array($admin);

    return $admin_values['admin_firstname'] . ' ' . $admin_values['admin_lastname'];
  }
  
  function tep_create_random_value($length, $type = 'mixed') {
    if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;

    $rand_value = '';
    while (strlen($rand_value) < $length) {
      if ($type == 'digits') {
        $char = tep_rand(0,9);
      } else {
        $char = chr(tep_rand(0,255));
      }

	if ($type == 'mixed') {
        if (preg_match('/^[a-z0-9]$/i', $char)) $rand_value .= $char;
      } elseif ($type == 'chars') {
        if (preg_match('/^[a-z]$/i', $char)) $rand_value .= $char;
      } elseif ($type == 'digits') {
        if (preg_match('/^[0-9]$/i', $char)) $rand_value .= $char;
      }
    }

    return $rand_value;
  }

// count payment modules
  function tep_count_payment_modules() {
    return tep_count_modules(MODULE_PAYMENT_INSTALLED);
  }  

  function tep_count_modules($modules = '') {
    $count = 0;

    if (empty($modules)) {
		return $count;
	}	

    $modules_array = preg_split('/;/', $modules);

    for ($i=0, $n=sizeof($modules_array); $i<$n; $i++) {
      $class = substr($modules_array[$i], 0, strrpos($modules_array[$i], '.'));
      if (is_object($GLOBALS[$class])) {
        if ($GLOBALS[$class]->enabled) {
          $count++;
        }
      }	  
    }
    return $count;
  }

  // config function for date formats
  function tep_cfg_date_formats($value,$key='') {
    $format=array(array('id'=>'d-m-Y','text'=>'dd-mm-yyyy'),
	              array('id'=>'m-d-Y','text'=>'mm-dd-yyyy'),
				  array('id'=>'Y-m-d','text'=>'yyyy-mm-dd'));
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
	$result=tep_draw_pull_down_menu($name,$format,$value) . '&nbsp;';
	//$result.=tep_image_submit('button_admin_modify.gif',IMAGE_MODIFY,'align=absmiddle');
	return $result;
  }
 // config function for date formats
  function tep_cfg_time_formats($value,$key='') {
    $format=array(array('id'=>'12','text'=>'12 hour'),
	              array('id'=>'24','text'=>'24 hour'));
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
	$result=tep_draw_pull_down_menu($name,$format,$value) . '&nbsp;';
	//$result.=tep_image_submit('button_admin_modify.gif',IMAGE_MODIFY,'align=absmiddle');
	return $result;
  }

  // config function for order amount round factor
  function tep_cfg_pull_down_round_factors($value,$key='') {
    $format=array(array('id'=>'0','text'=>'None'),
	              array('id'=>'0.01','text'=>'0.01'),
	              array('id'=>'0.05','text'=>'0.05'),
				  array('id'=>'0.1','text'=>'0.1'));
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
	$result=tep_draw_pull_down_menu($name,$format,$value,'onchange="javascript:save_round_factor()"') . '&nbsp;';
	//$result.=tep_image_submit('button_admin_modify.gif',IMAGE_MODIFY,'align=absmiddle');
	return $result;
  }
  // config function for activating sms gateway
  function tep_cfg_sms_option($value,$key='') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
	$result=tep_draw_radio_field($name,'1',($value==1?true:false)) . '&nbsp;' . TEXT_ENABLED . '&nbsp;';
	$result.=tep_draw_radio_field($name,'0',($value==0?true:false)) . '&nbsp;' . TEXT_DISABLED . '&nbsp;';
	//$result.=tep_image_submit('button_admin_modify.gif',IMAGE_MODIFY,'align=absmiddle');
	return $result;
  }
  // config function for renewal days 
  function tep_cfg_renewal_option($value,$key='') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
	$result=tep_draw_radio_field($name,'1',($value==1?true:false)) . '&nbsp;' . TEXT_ENABLED . '&nbsp;';
	$result.=tep_draw_radio_field($name,'0',($value==0?true:false)) . '&nbsp;' . TEXT_DISABLED . '&nbsp;';
	//$result.=tep_image_submit('button_admin_modify.gif',IMAGE_MODIFY,'align=absmiddle');
	return $result;
  }
  // config function for activating email
  function tep_cfg_email_option($value,$key='') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
	$result=tep_draw_radio_field($name,'1',($value==1?true:false)) . '&nbsp;' . TEXT_ENABLED . '&nbsp;';
	$result.=tep_draw_radio_field($name,'0',($value==0?true:false)) . '&nbsp;' . TEXT_DISABLED . '&nbsp;';
	//$result.=tep_image_submit('button_admin_modify.gif',IMAGE_MODIFY,'align=absmiddle');
	return $result;
  }
  // config function for report maximum rows
  function tep_cfg_pull_down_report_max_rows($value,$key=''){
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
  	$result=tep_draw_input_field($name,$value,'size=6 maxlength=5') . '&nbsp;';
	//$result.=tep_image_submit('button_admin_modify.gif',IMAGE_MODIFY,'align=absmiddle');
	return $result;
  }
  // config function for report maximum links
  function tep_cfg_pull_down_report_max_links($value,$key=''){
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
  	$result=tep_draw_input_field($name,$value,'size=6 maxlength=5') . '&nbsp;';
	//$result.=tep_image_submit('button_admin_modify.gif',IMAGE_MODIFY,'align=absmiddle');
	return $result;
  }
  function tep_cfg_spinner_report_business_day_start_time($value,$key=''){
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
  	$result=tep_draw_input_field($name,$value,'size=6 maxlength=5') . '&nbsp;';
	//$result.=tep_image_submit('button_admin_modify.gif',IMAGE_MODIFY,'align=absmiddle');
	return $result;
  }
  
    // SetTime Limit For E-Mail Sending Process
  function tep_cfg_email_timelimit($value,$key='')
  {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
  	$result=tep_draw_input_field($name,$value,'size=6 maxlength=5') . '&nbsp;';
	//$result.=tep_image_submit('button_admin_modify.gif',IMAGE_MODIFY,'align=absmiddle');
	return $result;
  }
  
  // date offset popup to display hours offset
  function tep_cfg_pull_down_date_offset($value,$key='') {
    $offset=array(array('id'=>'0','text'=>TEXT_NO_OFFSET));
	for ($icnt=-23.5;$icnt<=23.5;$icnt=$icnt+0.5){
		$offset[]=array('id'=>$icnt,'text'=>sprintf("%+.1f",$icnt));
	}
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $result= date("l dS of F Y h:i:s A") . '<br><br>'  . tep_draw_separator('pixel_trans.gif',20,1);
	$result.=tep_draw_pull_down_menu($name, $offset, $value) . '&nbsp;' . TEXT_OFFSET_HOURS . '&nbsp;';
	//$result.	=tep_image_submit('button_admin_modify.gif',IMAGE_MODIFY,'align=absmiddle');
	return $result;
  }

  
  function tep_cfg_pull_down_msg_times($value,$key='') {
    $times=array(array('id'=>'0','text'=>TEXT_NO_LIMIT));
    if(!$value) $value='0,0';
	for ($icnt=1;$icnt<=23;$icnt++){
		$times[]=array('id'=>$icnt . ':00','text'=>$icnt . ':00');
	}
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
    $name1 = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
	$value_split=preg_split("/,/",$value);
    $result = TEXT_MES_START . '&nbsp;';
	$result.=tep_draw_pull_down_menu($name, $times, (isset($value_split[0])?$value_split[0]:'')) . '&nbsp;<br><br>' . tep_draw_separator('pixel_trans.gif',20,1);
	$result.=TEXT_MES_END . '&nbsp;';
	$result.=tep_draw_pull_down_menu($name1, $times, (isset($value_split[1])?$value_split[1]:'')) . '&nbsp;';
	//$result.=tep_image_submit('button_admin_modify.gif',IMAGE_MODIFY,'align=absmiddle');
	return $result;
  }
  
  // to set event view style normal,calendar
  function tep_cfg_pull_down_view_style($value,$key=''){
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
  	$choices=array(array("id"=>"basic","text"=>"Basic"),array("id"=>"calendar","text"=>"calendar"),array("id"=>"view1","text"=>"View1"),array("id"=>"view2","text"=>"View2"),array("id"=>"view3","text"=>"View3"));
	$result.=tep_draw_pull_down_menu($name,$choices,strtolower($value)) . '&nbsp;';
	//$result.=tep_image_submit('button_modify.gif',IMAGE_MODIFY,'align=absmiddle');
	return $result;
  }
  
  // to set event view calendar style
  function tep_cfg_pull_down_calendar_style($value,$key=''){
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
  	$choices=array(array("id"=>"day","text"=>"Day"),array("id"=>"week","text"=>"Week"),array("id"=>"month","text"=>"Month"));
	$lock_option="";
	
	$configuration_value_query=tep_db_query('select configuration_value from '.TABLE_CONFIGURATION." where configuration_key = 'EVENTS_VIEW_STYLE' ");
	$configuration_value_array=tep_db_fetch_array($configuration_value_query);
	if($configuration_value_array['configuration_value']!='calendar') $lock_option='disabled=true';
	
	//if (strtolower(EVENTS_VIEW_STYLE)!='calendar') $lock_option='disabled=true';
	$result.=tep_draw_pull_down_menu($name,$choices,strtolower($value),$lock_option)  . '&nbsp;';
	//$result.=tep_image_submit('button_modify.gif',IMAGE_MODIFY,'align=absmiddle');
	return $result;
  }

  function tep_cfg_pull_down_event_display($value,$key=''){
    $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value[]');
  	$choices=array(array("id"=>"E","text"=>"Name"),array("id"=>"EL","text"=>"Name - Location"),
					array("id"=>"ET","text"=>"Name - Time"),array("id"=>"ELT","text"=>"Name - Time - Location"),
					);
	$result.=tep_draw_pull_down_menu($name,$choices,strtolower($value))  . '&nbsp;';
	$result.=tep_image_submit('button_modify.gif',IMAGE_MODIFY,'align=absmiddle');
	return $result;
  }


  
  function getGMTTime(){
		$timestring=date("Y-m-d H:i:s",mktime());
		$offset=date("O");
		$timestring = preg_replace("#\s#","",$timestring);
		$timestring = preg_replace("#\:#","",$timestring);
		$timestring = preg_replace("#\.#","",$timestring);
		$timestring = preg_replace("#\/#","",$timestring);
		$timestring = preg_replace("#\-#","",$timestring);
		$fac = ("-" == substr($timestring,0,1)) ? -1 : 1;
		$timestamp = ("-" == substr($timestring,0,1)) ? substr($timestring,1,strlen($timestring)-1) : $timestring;

		$array['years']     = (6==strlen($timestamp)) ? $fac * substr($timestamp,0,2): $fac * substr($timestamp,0,4);
		$array['months']     = (6==strlen($timestamp)) ? $fac * substr($timestamp,2,2): $fac * substr($timestamp,4,2);
		$array['days']        = (6==strlen($timestamp)) ? $fac * substr($timestamp,4,2): $fac * substr($timestamp,6,2);
		$array['hours']     = (12 > strlen($timestamp)) ? 0 : $fac * substr($timestamp, 8,2);
		$array['minutes']     = (12 > strlen($timestamp)) ? 0 : $fac * substr($timestamp, 10,2);
		$array['seconds']     = (14 > strlen($timestamp)) ? 0 : $fac * substr($timestamp, 12,2);

		$hour=(int)substr($offset,1,2);
		$min=(int)substr($offset,3);


		if (substr($offset,0,1)=="-"){
		   $rtime = mktime ( $array['hours']      +     $hour,
							$array['minutes'] +     $min,
							$array['seconds'] +     0,
							$array['months']  +     0,
							$array['days']      +     0,
							$array['years']      +     0);
		} else {
		   $rtime = mktime ( $array['hours']      -     $hour,
							$array['minutes'] -    $min,
							$array['seconds'] -     0,
							$array['months']  -     0,
							$array['days']      -     0,
							$array['years']      -     0);
		}
	return $rtime;
 }

  
  //draw the date elements
  function tep_draw_combo_select($types,$selectname,$valueselect)
  {
    $result='';
    if ($types=='weeks') 
	{
      $element=array('1'=>'first','2'=>'second','3'=>'third','4'=>'fourth');
	}
	elseif ($types=='months')
	{
	  $element=array('1'=>'January','2'=>'February','3'=>'March','4'=>'April',
				  '5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September',
				  '10'=>'October','11'=>'November','12'=>'December');
	}
	else{
	  $element=array('1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday',
	                 '5'=>'Friday','6'=>'Saturday','7'=>'Sunday');}
	$result='<select name="'. $selectname . '" id="' . $selectname . '" ';
	$result.=' class="inputNormal" onBlur="javascript:toggle_focus(this,1);" onFocus="javascript:toggle_focus(this,2);"';
	$result.='>';
	for($icnt=1,$n=sizeof($element);$icnt<=$n;$icnt++)
	{
	   $result=$result . '<option value="' . ($icnt) . '"';
	   if ($icnt==$valueselect) $result=$result . ' selected';
	   $result=$result . '>' . $element[$icnt] . '</option>';
	}
	$result=$result . '</select>';
	return $result;
  }

 
 
 // select values by passing tablename,fields and condition
 function tep_get_value($table,$fields,$condition)
 {
   $value_query=tep_db_query("select " . $fields . " from " . $table . " where " . $condition);
   if ($value_result=tep_db_fetch_array($value_query))
      return $value_result;
   return array();      
 }
 

// to add specified interval to a given date

function tep_dateadd($timestamp, $unit, $amount) { 
  if($amount<0) $amount=1;
  // Possible $units are: "hr", "min", "sec",
  // "mon", "day", or "yr"
  // $amount should be an integer
   	  if($timestamp>0){
		$timestring=date("Y-m-d H:i:s",$timestamp);
		$timestring = preg_replace("#\s#","",$timestring);
		$timestring = preg_replace("#\:#","",$timestring);
		$timestring = preg_replace("#\.#","",$timestring);
		$timestring = preg_replace("#\/#","",$timestring);
		$timestring = preg_replace("#\-#","",$timestring);
		$fac = ("-" == substr($timestring,0,1)) ? -1 : 1;
		$timestamp = ("-" == substr($timestring,0,1)) ? substr($timestring,1,strlen($timestring)-1) : $timestring;
		$array['yr']     = (6==strlen($timestamp)) ? $fac * substr($timestamp,0,2): $fac * substr($timestamp,0,4);
		$array['mon']    = (6==strlen($timestamp)) ? $fac * substr($timestamp,2,2): $fac * substr($timestamp,4,2);
		$array['day']    = (6==strlen($timestamp)) ? $fac * substr($timestamp,4,2): $fac * substr($timestamp,6,2);
		$array['hr']     = (12 > strlen($timestamp)) ? 0 : $fac * substr($timestamp, 8,2);
		$array['min']    = (12 > strlen($timestamp)) ? 0 : $fac * substr($timestamp, 10,2);
		$array['sec']    = (14 > strlen($timestamp)) ? 0 : $fac * substr($timestamp, 12,2);		
		$array[$unit]+=$amount;
		$rtime = mktime ($array['hr'],$array['min'],$array['sec'],$array['mon'],$array['day'],$array['yr']);
   	  }
	return $rtime;
}

//to add specified interval to a given time
function tep_timeadd($timestamp, $unit, $amount) {
if($amount<0) $amount=1;
// Possible $units are: "hr", "min", "sec",
//          "mon", "day", or "yr"
// $amount should be an integer

	if ($amount==0) return $timestamp;
   $delta_vars = array("hr"=>0, "min"=>0,
   "sec"=>0, "mon"=>1,
   "day"=>1,"yr"=>1);

   $delta_vars[$unit] += $amount;

   $delta = gmmktime($delta_vars["hr"],
   $delta_vars["min"],
   $delta_vars["sec"],
   $delta_vars["mon"],
   $delta_vars["day"],
   $delta_vars["yr"]);
   return $timestamp + $delta;
}
// subtract specified interval to a given date
function tep_datesub($timestamp, $unit, $amount) {
	if($amount<=0) $amount=1;
// Possible $units are: "hr", "min", "sec",
//          "mon", "day", or "yr"
// $amount should be an integer
		$timestring=date("Y-m-d H:i:s",$timestamp);
		$timestring = preg_replace("#\s#","",$timestring);
		$timestring = preg_replace("#\:#","",$timestring);
		$timestring = preg_replace("#\.#","",$timestring);
		$timestring = preg_replace("#\/#","",$timestring);
		$timestring = preg_replace("#\-#","",$timestring);
		$fac = ("-" == substr($timestring,0,1)) ? -1 : 1;
		$timestamp = ("-" == substr($timestring,0,1)) ? substr($timestring,1,strlen($timestring)-1) : $timestring;
		$array['yr']     = (6==strlen($timestamp)) ? $fac * substr($timestamp,0,2): $fac * substr($timestamp,0,4);
		$array['mon']     = (6==strlen($timestamp)) ? $fac * substr($timestamp,2,2): $fac * substr($timestamp,4,2);
		$array['day']        = (6==strlen($timestamp)) ? $fac * substr($timestamp,4,2): $fac * substr($timestamp,6,2);
		$array['hr']     = (12 > strlen($timestamp)) ? 0 : $fac * substr($timestamp, 8,2);
		$array['min']     = (12 > strlen($timestamp)) ? 0 : $fac * substr($timestamp, 10,2);
		$array['sec']     = (14 > strlen($timestamp)) ? 0 : $fac * substr($timestamp, 12,2);
  		$array[$unit]-=$amount;
	   $rtime = mktime ($array['hr'],$array['min'],$array['sec'],$array['mon'],$array['day'],$array['yr']);
	return $rtime;
} 

	// get the products display tree
  function tep_get_products_display_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false) {
    global $FSESSION;

    if (!is_array($category_tree_array)) $category_tree_array = array();
    if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);

    if ($include_itself) {
      $category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '" . (int)$FSESSION->languages_id . "' and cd.categories_id = '" . (int)$parent_id . "'");
      $category = tep_db_fetch_array($category_query);
      $category_tree_array[] = array('id' => $parent_id, 'text' => $category['categories_name']);
    }

    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "' and c.parent_id = '" . (int)$parent_id . "' order by c.sort_order, cd.categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ($exclude != $categories['categories_id']) $category_tree_array[] = array('id' => $categories['categories_id'], 'text' => $spacing . $categories['categories_name']);
      $category_tree_array = tep_get_products_display_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
    }
    $products_query=tep_db_query("select pd.products_name,pd.products_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " pcd where pcd.categories_id='" . (int)$parent_id . "' and pd.products_id=pcd.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "'");
    while ($products=tep_db_fetch_array($products_query))
    {
        $category_tree_array[] = array('id' => $products['products_id'], 'text' => $spacing . $products['products_name'] . '(product)');
    }
    return $category_tree_array;
  }
  
// // replace the test mail content
function tep_replace_test_mail_content($mail_content){
  global $currencies;
    $replace_array=array(TEXT_FN=>TEST_MAIL_FN,
          TEXT_LN=>TEST_MAIL_LN,
		  CUST_CM=>TEST_MAIL_CM,
          TEXT_DF=>TEST_MAIL_DF,
          TEXT_EM=>TEST_MAIL_EM,
          TEXT_TN=>TEST_MAIL_TN,
			 TEXT_FX=>TEST_MAIL_FX,
          TEXT_SA=>TEST_MAIL_SA,
          TEXT_SU=>TEST_MAIL_SU,
          TEXT_PC=>TEST_MAIL_PC,
          TEXT_CT=>TEST_MAIL_CT,
          TEXT_ST=>TEST_MAIL_ST,
          TEXT_CY=>TEST_MAIL_CY,
          TEXT_EN=>TEST_MAIL_EN,
          TEXT_SD=>getServerDate(),
			 TEXT_SI=>TEST_MAIL_SI,
			 TEXT_EI=>TEST_MAIL_EI,
			 TEXT_EF=>TEST_MAIL_EF,
			 TEXT_IN=>TEST_MAIL_IN,
			 TEXT_EL=>TEST_MAIL_EL,
			 TEXT_IV=>TEST_MAIL_IV,
			 TEXT_IC=>TEST_MAIL_IC,
			 TEXT_IF=>TEST_MAIL_IF,
			 TEXT_UN=>TEST_MAIL_UN,
			 TEXT_RE=>TEST_MAIL_RE,
			 TEXT_FE=>TEST_MAIL_FE,
			 TEXT_CF=>TEST_MAIL_CF,
			 TEXT_CL=>TEST_MAIL_CL,
			 TEXT_RL=>TEST_MAIL_RL,
			 TEXT_SS=>sprintf(TEST_MAIL_FORMAT_SS,TEST_MAIL_EN,TEST_MAIL_SI,TEST_MAIL_EI,date('l, jS F'),TEST_MAIL_EL),
         	 
			 TEXT_SSM=>date(str_replace("/","-",EVENTS_DATE_FORMAT)),
			
			 TEXT_RM=>TEST_MAIL_RM,
			 TEXT_RV=>TEST_MAIL_RV,
			 TEXT_WV=>TEST_MAIL_WV,
			 TEXT_FV=>TEST_MAIL_FV,
			 TEXT_ON=>TEST_MAIL_ON,
			TEXT_SL=>'<a href="' . tep_href_link(FILENAME_DEFAULT) . '" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . STORE_OWNER . '</a>',
			TEXT_SP=>'<img src="' . tep_href_link('images/' .COMPANY_LOGO) . '" title="' . STORE_NAME . '">',
			TEXT_MA=>'<td align="left" valign="top" nowrap="nowrap"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000"><b>Multiple Attendees</b></font></td>' . 
					'<td align="left"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">' . TEST_MAIL_FN . " " . TEST_MAIL_LN . '</font></td>',
         TEXT_BARCODE=>'Barcode',
		  TEXT_OC=>TEST_MAIL_OC,
          TEXT_OD=>TEST_MAIL_OD,
          TEXT_BT=>TEST_MAIL_BT,
          TEXT_BA=>TEST_MAIL_BA,
          TEXT_PY=>TEST_MAIL_PY,
			 TEXT_DD=>TEST_MAIL_DD,
			 TEXT_SB=>$currencies->format(TEST_MAIL_SB),
          TEXT_TX=>$currencies->format(TEST_MAIL_TX),
			 TEXT_TL=>$currencies->format(TEST_MAIL_TL),
			 TEXT_PDF=>TEST_MAIL_PDF,
			 TEXT_IL=>TEST_MAIL_IL,
			 TEXT_SUB_NA=>TEST_MAIL_SUB_NA,
			 TEXT_SUB_CO=>$currencies->format(TEST_MAIL_SUB_CO),
			 TEXT_SUB_PE=>TEST_MAIL_SUB_PE,
			 TEXT_SUB_RL=>TEST_MAIL_SUB_RL,
			 TEXT_WAD=>TEST_MAIL_WAD,
			 TEXT_PT=>TEST_MAIL_PT,
			 TEXT_WCB=>TEST_MAIL_WCB,
			 TEXT_SER_SN=>TEST_MAIL_SER_SN,
			 TEXT_SER_SC=>TEST_MAIL_SER_SC,
			 TEXT_SER_SD=>TEST_MAIL_SER_SD,
			 TEXT_SER_ED=>TEST_MAIL_SER_ED,
			 TEXT_SER_ST=>TEST_MAIL_SER_ST,
			 TEXT_SER_ET=>TEST_MAIL_SER_ET,
			 TEXT_SER_RN=>TEST_MAIL_SER_RN,
			 TEXT_SER_AD=>TEST_MAIL_SER_AD,
			 TEXT_SER_TT=>TEST_MAIL_SER_TT,
			 TEXT_SER_PN=>TEST_MAIL_SER_PN,
			 TEXT_SER_PP=>TEST_MAIL_SER_PP,
			 TEXT_SER_SL=>TEST_MAIL_SER_SL,
			 TEXT_SUB_TY=>TEST_SUB_TY,
			 TEXT_SUB_SD=>TEST_SUB_SD,
			 TEXT_LC=>TEST_LC,
									TEXT_SPL=>TEST_SPL,
									TEXT_DN=>TEST_DOMAIN_NAME,
									TEXT_DE=>TEST_DE,
									TEXT_DU=>TEST_DU,
									TEXT_R=>TEST_R,
									TEXT_TST=>TEST_TST,
									TEXT_PU=>TEST_PU,
									TEXT_SUM=>TEST_SUM
			 
			  );
			  //print_r($replace_array);
	reset($replace_array);
   while (list($key, $value) = each($replace_array))
      $mail_content=preg_replace("/%%" . $key  . "%%/i",$value,$mail_content);
  return $mail_content;

}

	// send default admin related email 
	function tep_send_default_test_email($type){

       
        global $currencies;
		$details=array();
		$replace_array="";
		$details["type"]=$type;
		$details["table"]=TABLE_EMAIL_MESSAGES;
		
		tep_get_template($details); // get template of content
	    
		tep_merge_details($replace_array,"test_default");  // get default merge data
		tep_replace_template($details,$replace_array);
		
		$details["to_name"]=TEST_MAIL_FN . ' ' . TEST_MAIL_LN;
		$details["to_email"]=EVENTS_TEST_EMAIL_ADDRESS;
		$details["from_name"]=STORE_OWNER;
		$details["from_email"]=STORE_OWNER_EMAIL_ADDRESS;
		tep_send_email($details,true);
		
	} 

	// get the merge details for test
	function tep_merge_details(&$replace_array,$type)
	{ 
	    global $currencies;
		switch($type){
			case "test_default":  
				$replace_array=array(TEXT_FN=>TEST_MAIL_FN,
									TEXT_LN=>TEST_MAIL_LN,
									CUST_CM=>TEST_MAIL_CM,
									TEXT_SM=>TEST_MAIL_SM,	
									TEXT_SE=>TEST_MAIL_SE,
									TEXT_LE=>TEST_MAIL_LE,	
									TEXT_LP=>TEST_MAIL_LP,
									TEXT_SN=>TEST_MAIL_SN,	
									TEXT_AL=>TEST_MAIL_AL,
									TEXT_GR=>TEST_MAIL_GR,
									TEXT_US=>TEST_MAIL_US,
									'Store_Link'=>STORE_NAME,
									'Order_Link'=>'Click Here',
									'Bank_Deposit_Message'=>'Bank Message',
									'Store_Logo'=>tep_image(DIR_WS_IMAGES . COMPANY_LOGO),
									TEXT_PN=>TEST_MAIL_PN, 
									TEXT_PP=>TEST_MAIL_PP,
									TEXT_PA=>TEST_MAIL_PA, 
									EXT_P_M=>TEST_MAIL_P_M,
									TEXT_P_DA=>TEST_MAIL_P_DA, 		
									TEXT_P_W=>TEST_MAIL_P_W,
									TEXT_P_A_UP=>TEST_MAIL_P_A_UP, 	
									TEXT_P_U=>TEST_MAIL_P_U,
									TEXT_P_Q=>TEST_MAIL_P_Q,   		
									TEXT_SN=>TEST_MAIL_SN,
									TEXT_P_SM=>TEST_MAIL_SM,  		
									TEXT_SE=>TEST_MAIL_SE,
														
									ORDR_NO=>TEST_ORDR_NO, ORDR_OP=>TEST_ORDR_OP,
									ORDR_OL=>TEST_ORDR_OL, ORDR_OM=>TEST_ORDR_OM,
									ORDR_PO=>TEST_ORDR_PO, ORDR_OT=>TEST_ORDR_OT,
									ORDR_PM=>TEST_ORDR_PM, ORDR_PF=>TEST_ORDR_PF,
									ORDR_DD=>TEST_ORDR_DD, ORDR_PD=>TEST_ORDR_PD,
									
									CUST_CF=>TEST_ORDR_CF, CUST_CL=>TEST_ORDR_CL,
									CUST_CM=>TEST_ORDR_CM, CUST_CT=>TEST_ORDR_CT,
									CUST_CS=>TEST_ORDR_CS, CUST_CC=>TEST_ORDR_CC,
									CUST_CP=>TEST_ORDR_CP, CUST_CE=>TEST_ORDR_CE,
									CUST_CU=>TEST_ORDR_CU, CUST_CO=>TEST_ORDR_CO,
									CUST_CA=>TEST_ORDR_CA, DELI_NA=>TEST_ORDR_CF, 
									
									DELI_CM=>TEST_ORDR_CM, DELI_CT=>TEST_ORDR_CT,
									DELI_CS=>TEST_ORDR_CS, DELI_CC=>TEST_ORDR_CC,
									DELI_CP=>TEST_ORDR_CP, DELI_CE=>TEST_ORDR_CE,
									DELI_CU=>TEST_ORDR_CU, DELI_CO=>TEST_ORDR_CO,
									DELI_CA=>TEST_ORDR_CA, BILL_NA=>TEST_ORDR_CF,
									
									BILL_CM=>TEST_ORDR_CM, BILL_CT=>TEST_ORDR_CT,
									BILL_CS=>TEST_ORDR_CS, BILL_CC=>TEST_ORDR_CC,
									BILL_CP=>TEST_ORDR_CP, BILL_CE=>TEST_ORDR_CE,
									BILL_CU=>TEST_ORDR_CU, BILL_CO=>TEST_ORDR_CO,
									BILL_CA=>TEST_ORDR_CA, 
								
									
									TEXT_AD=>TEST_MAIL_AD,
									TEXT_AU=>TEST_MAIL_AU,
									TEXT_AP=>TEST_MAIL_AP,
									TEXT_FL=>TEST_MAIL_FL,
									TEXT_PDF=>TEST_MAIL_PDF,
									TEXT_DL=>'Download Link',
									
									TEXT_FD=>TEST_MAIL_FD,
									TEXT_FB=>TEST_MAIL_FB,
									TEXT_FI=>TEST_MAIL_FI,
									TEXT_P_STATUS=>TEST_P_STATUS,
									TEXT_LC=>TEST_LC,
									TEXT_SPL=>TEST_SPL,
									TEXT_DN=>TEST_DOMAIN_NAME,
									TEXT_DE=>TEST_DE,
									TEXT_DU=>TEST_DU,
									TEXT_R=>TEST_R,
									TEXT_TST=>TEST_TST,
									TEXT_PU=>TEST_PU,
									TEXT_SUM=>TEST_SUM
									
									
						);
				//print_r($replace_array);
				break;
		
		}
	}

// count the no of days for given start date and end date
function tep_count_days($start, $end)
{
   if( $start != '0000-00-00' and $end != '0000-00-00' )
   {
       $timestamp_start = strtotime($start);
       $timestamp_end = strtotime($end);
       if( $timestamp_start >= $timestamp_end ) return 0;
       $start_year = date("Y",$timestamp_start);
       $end_year = date("Y", $timestamp_end);
       $num_days_start = date("z",strtotime($start));
       $num_days_end = date("z", strtotime($end));
       $num_days = 0;
       $i = 0;
       if( $end_year > $start_year )
       {
           while( $i < ( $end_year - $start_year ) )
           {
             $num_days = $num_days + date("z", strtotime(($start_year + $i)."-12-31"));
             $i++;
           }
         }
         return ( $num_days_end + $num_days ) - $num_days_start;
   }
   else
   {
         return 0;
     }
}

// set default rights automatically for the selected group
function tep_set_default_group_rights($group_name,$group_id=0){
	if ($group_name=="") return;

	if ($group_id==0){
		$group_query=tep_db_query("SELECT admin_groups_id from " . TABLE_ADMIN_GROUPS . " where admin_groups_name='" . tep_db_input($group_name) . "'");
		$group_result=tep_db_fetch_array($group_query);
		$group_id=(int)$group_result['admin_groups_id'];
	}
	if ($group_id<=0) return;
		$file_list=array();
		switch($group_name){
			case TEXT_ADMINISTRATOR_ENTRY:
    		    $file_list=array(FILENAME_ADMIN_ACCOUNT,FILENAME_ADMIN_FILES,FILENAME_ADMIN_MEMBERS,
								FILENAME_CATEGORIES,FILENAME_DEFINE_MAINPAGE,FILENAME_EASYPOPULATE,FILENAME_FEATURED,FILENAME_MANUFACTURERS,FILENAME_PRODUCTS_ATTRIBUTES,
								FILENAME_PRODUCTS_EXPECTED,FILENAME_QUICK_ATTIRBUTES_POPUP,FILENAME_QUICK_PRODUCTS_POPUP,FILENAME_REVIEWS,FILENAME_SALEMAKER,
								FILENAME_SALEMAKER_INFO,FILENAME_SPECIALS,FILENAME_XSELL_PRODUCTS,
								FILENAME_CONFIGURATION,
								FILENAME_CREATE_ACCOUNT,FILENAME_CREATE_ACCOUNT_PROCESS,FILENAME_CREATE_ACCOUNT_SUCCESS,FILENAME_CREATE_ORDER,FILENAME_CREATE_ORDER_PROCESS,
								FILENAME_CUSTOMERS,FILENAME_EDIT_ORDERS,FILENAME_ORDERS,
							
							FILENAME_ADMIN_LETTERS,FILENAME_EVENTS_CATEGORIES,FILENAME_EVENTS_BROWSE,FILENAME_EVENTS_DISCOUNTS,FILENAME_EVENTS_LOCATIONS,
							 	FILENAME_EVENTS_OPTIONS,FILENAME_SEARCH_EVENTS,FILENAME_POP_QUESTIONS,FILENAME_SESSIONS,
								
							FILENAME_EVENTS_COPY_MESSAGES,FILENAME_EVENTS_MESSAGES,FILENAME_EVENTS_MESSAGES_LOG,FILENAME_EVENTS_SMS_MESSAGES,FILENAME_SEND_EMAIL,
								FILENAME_SEND_MESSAGES_SCRIPT,FILENAME_SEND_SMS_EMAIL,

							FILENAME_ADD_EVENTS,FILENAME_CHECKOUT_PAYMENT,FILENAME_CHECKOUT_PROCESS,FILENAME_EDIT_EVENTS,FILENAME_EDIT_ORDERS_CONFIRM,FILENAME_EDIT_ORDERS_PROCESS,
								FILENAME_EDIT_ORDERS_SUCCESS,FILENAME_PENDING_WAITLIST_NEW_ORDER,FILENAME_PENDING_WAITLIST_PAYMENTS,FILENAME_PENDING_WAITLIST_PAYMENTS_CONFIRM,
								FILENAME_PENDING_WAITLIST_PAYMENTS_PROCESS,

							FILENAME_MOVE_RESERVATIONS_CONFIRM,FILENAME_MOVE_RESERVATIONS_PROCESS,FILENAME_REFUND_CONFIRM_RESERVATIONS,FILENAME_REFUND_PROCESS,FILENAME_REFUND_RESERVATIONS,
								FILENAME_REFUND_SUCCESS,FILENAME_SEARCH_RESERVATIONS,FILENAME_SEARCH_RESERVATIONS_DATED,FILENAME_SEARCH_RESERVATIONS_PRINT,FILENAME_SEARCH_RESERVATION_PRINT_DATED,
								FILENAME_SEARCH_RESERVATIONS_PRINT_PDF,FILENAME_WAITLISTS,FILENAME_WITHDRAW_PROCESS,FILENAME_WITHDRAW_RESERVATIONS,
							
							FILENAME_EVENTS_REPORTS_WAITLIST,FILENAME_STATS_RESERVATIONS_COUNT,

							FILENAME_EVENTS_COPY_SURVEYS,FILENAME_EVENTS_REPORTS_SURVEYS,FILENAME_EVENTS_REPORTS_SURVEYS_DETAIL,FILENAME_EVENTS_REPORTS_SURVEYS_FILE,FILENAME_EVENTS_REPORTS_SURVEYS_RELATION,
								FILENAME_EVENTS_REPORTS_SURVEYS_SUMMARY,FILENAME_EVENTS_SURVEYS,FILENAME_EVENTS_SURVEYS_ANSWER,FILENAME_EVENTS_SURVEYS_EDIT,FILENAME_EVENTS_SURVEYS_PROCESS,

							FILENAME_FAQDESK,FILENAME_FAQDESK_REVIEWS,
							FILENAME_FAQDESK_CONFIGURATION,
							FILENAME_COUPON_ADMIN,FILENAME_COUPON_RESTRICT,FILENAME_GV_MAIL,FILENAME_GV_QUEUE,FILENAME_GV_SENT,
							FILENAME_INFOBOX_CONFIGURATION,FILENAME_TEMPLATE_CONFIGURATION,
							FILENAME_CURRENCIES,FILENAME_LANGUAGES,FILENAME_ORDERS_STATUS,FILENAME_MODULES,
							FILENAME_NEWSDESK,FILENAME_NEWSDESK_REVIEWS,
							FILENAME_NEWSDESK_CONFIGURATION,
							FILENAME_PAYPALIPN_TESTS,FILENAME_PAYPALIPN_TRANSACTIONS,
							FILENAME_STATS_CUSTOMERS,FILENAME_STATS_PRODUCTS_PURCHASED,FILENAME_STATS_PRODUCTS_VIEWED,FILENAME_STATS_MONTHLY_SALES,
							FILENAME_COUNTRIES,FILENAME_GEO_ZONES,FILENAME_TAX_CLASSES,FILENAME_TAX_RATES,FILENAME_ZONES,
							FILENAME_BACKUP,FILENAME_CACHE,FILENAME_DEFINE_LANGUAGE,FILENAME_FILE_MANAGER,
							FILENAME_MAIL,FILENAME_NEWSLETTERS,FILENAME_SERVER_INFO,FILENAME_WHOS_ONLINE
					);
					$box_list=array('affiliate.php','configuration.php','modules.php','customers.php','taxes.php','localization.php','reports.php','tools.php','paypalipn.php','infoboxes.php','gv_admin.php');
					break;
			case TEXT_CALLMANAGER_ENTRY:
				$file_list=array(FILENAME_REVIEWS,FILENAME_CREATE_ACCOUNT,FILENAME_CREATE_ACCOUNT_PROCESS,FILENAME_CREATE_ACCOUNT_SUCCESS,FILENAME_CREATE_ORDER,
							FILENAME_CREATE_ORDER_PROCESS,FILENAME_CUSTOMERS,FILENAME_EDIT_ORDERS,FILENAME_ORDERS,
							
							FILENAME_ADMIN_LETTERS,FILENAME_EVENTS_BROWSE,
								
							FILENAME_ADD_EVENTS,FILENAME_CHECKOUT_PAYMENT,FILENAME_CHECKOUT_PROCESS,FILENAME_EDIT_ORDERS_CONFIRM,FILENAME_EDIT_ORDERS_PROCESS,FILENAME_EDIT_EVENTS,FILENAME_EDIT_ORDERS_SUCCESS,
							FILENAME_PENDING_WAITLIST_NEW_ORDER,FILENAME_PENDING_WAITLIST_PAYMENTS,FILENAME_PENDING_WAITLIST_PAYMENTS_CONFIRM,FILENAME_PENDING_WAITLIST_PAYMENTS_PROCESS,
							
							FILENAME_SEND_EMAIL,FILENAME_SEND_SMS_EMAIL,
							
							FILENAME_MOVE_RESERVATIONS_CONFIRM,FILENAME_MOVE_RESERVATIONS_PROCESS,FILENAME_REFUND_RESERVATIONS,FILENAME_REFUND_CONFIRM_RESERVATIONS,FILENAME_REFUND_PROCESS,
								FILENAME_REFUND_SUCCESS,FILENAME_SEARCH_RESERVATIONS,FILENAME_SEARCH_RESERVATIONS_DATED,FILENAME_SEARCH_RESERVATION_PRINT,FILENAME_SEARCH_RESERVATION_PRINT_DATED,
								FILENAME_SEARCH_RESERVATION_PRINT_PDF,FILENAME_WAITLISTS,FILENAME_WITHDRAW_PROCESS,FILENAME_WITHDRAW_RESERVATIONS
					);
				$box_list=array('catalog.php','customers.php');
				break;
			// case TEXT_CALLSTAFF_ENTRY:
				// $file_list=array(FILENAME_CREATE_ACCOUNT,FILENAME_CREATE_ACCOUNT_PROCESS,FILENAME_CREATE_ACCOUNT_SUCCESS,FILENAME_CREATE_ORDER,
							// FILENAME_CREATE_ORDER_PROCESS,FILENAME_CUSTOMERS,FILENAME_EDIT_ORDERS,FILENAME_ORDERS,

							// FILENAME_ADMIN_LETTERS,FILENAME_EVENTS_BROWSE,

							// FILENAME_ADD_EVENTS,FILENAME_CHECKOUT_PAYMENT,FILENAME_CHECKOUT_PROCESS,FILENAME_EDIT_ORDERS_CONFIRM,
			    				// FILENAME_EDIT_ORDERS_PROCESS,FILENAME_EDIT_EVENTS,FILENAME_EDIT_ORDERS_SUCCESS,

							// FILENAME_PENDING_WAITLIST_NEW_ORDER,FILENAME_PENDING_WAITLIST_PAYMENTS,FILENAME_PENDING_WAITLIST_PAYMENTS_CONFIRM,
								// FILENAME_PENDING_WAITLIST_PAYMENTS_PROCESS,
							
							// FILENAME_SEND_EMAIL,
							
							// FILENAME_MOVE_RESERVATIONS_CONFIRM,FILENAME_MOVE_RESERVATIONS_PROCESS,FILENAME_REFUND_SUCCESS,FILENAME_SEARCH_RESERVATIONS,
								// FILENAME_SEARCH_RESERVATIONS,FILENAME_SEARCH_RESERVATIONS_DATED,FILENAME_SEARCH_RESERVATION_PRINT,FILENAME_SEARCH_RESERVATION_PRINT_DATED,
								// FILENAME_SEARCH_RESERVATION_PRINT_PDF,FILENAME_WAITLISTS,FILENAME_WITHDRAW_PROCESS,FILENAME_WITHDRAW_RESERVATIONS
						// );
				// $box_list=array('event.php','customers.php');
				// break;
	
			case TEXT_PRODUCTMANAGER_ENTRY:
				$file_list=array(FILENAME_CATEGORIES,FILENAME_DEFINE_MAINPAGE,FILENAME_EASYPOPULATE,FILENAME_FEATURED,FILENAME_MANUFACTURERS,FILENAME_PRODUCTS_ATTRIBUTES,
							FILENAME_PRODUCTS_EXPECTED,FILENAME_QUICK_ATTIRBUTES_POPUP,FILENAME_QUICK_PRODUCTS_POPUP,FILENAME_REVIEWS,FILENAME_SALEMAKER,
							FILENAME_SALEMAKER_INFO,FILENAME_SPECIALS,FILENAME_XSELL_PRODUCTS,
							FILENAME_FAQDESK,FILENAME_FAQDESK_REVIEWS,
							FILENAME_COUPON_ADMIN,FILENAME_COUPON_RESTRICT,FILENAME_GV_MAIL,FILENAME_GV_QUEUE,FILENAME_GV_SENT,
							FILENAME_STATS_CUSTOMERS,FILENAME_STATS_PRODUCTS_PURCHASED,FILENAME_STATS_PRODUCTS_VIEWED,FILENAME_STATS_MONTHLY_SALES
						);
				$box_list=array('catalog.php','reports.php','faqdesk.php','gv_admin.php');
				break;
			case TEXT_RESERVATIONMANAGER_ENTRY:
				$file_list=array(FILENAME_REVIEWS,FILENAME_CREATE_ACCOUNT,FILENAME_CREATE_ACCOUNT_PROCESS,FILENAME_CREATE_ACCOUNT_SUCCESS,FILENAME_CREATE_ORDER,FILENAME_CREATE_ORDER_PROCESS,
							FILENAME_CUSTOMERS,FILENAME_EDIT_ORDERS,FILENAME_ORDERS,
							
							FILENAME_ADMIN_LETTERS,FILENAME_EVENTS_CATEGORIES,FILENAME_EVENTS_DISCOUNTS,FILENAME_EVENTS_LOCATIONS,FILENAME_EVENTS_OPTIONS,FILENAME_SEARCH_EVENTS,
								FILENAME_EVENTS_BROWSE,FILENAME_POP_QUESTIONS,FILENAME_SESSIONS,

						    FILENAME_INSTRUCTORS,FILENAME_INSTRUCTORS_OPTIONS,FILENAME_VIEW_RESERVATIONS,FILENAME_INSTRUCTOR_RESERVATIONS,
							
							FILENAME_ADD_EVENTS,FILENAME_CHECKOUT_PAYMENT,FILENAME_CHECKOUT_PROCESS,FILENAME_EDIT_ORDERS_CONFIRM,FILENAME_EDIT_ORDERS_PROCESS,
							    FILENAME_PENDING_WAITLIST_NEW_ORDER,FILENAME_PENDING_WAITLIST_PAYMENTS,FILENAME_PENDING_WAITLIST_PAYMENTS_CONFIRM,FILENAME_PENDING_WAITLIST_PAYMENTS_PROCESS,
								FILENAME_EDIT_EVENTS,FILENAME_EDIT_ORDERS_SUCCESS,
				
							FILENAME_EVENTS_COPY_MESSAGES,FILENAME_EVENTS_MESSAGES,FILENAME_EVENTS_SMS_MESSAGES,FILENAME_SEND_EMAIL,FILENAME_SEND_SMS_EMAIL,
							FILENAME_EVENTS_MESSAGES_LOG,
							
							FILENAME_MOVE_RESERVATIONS_CONFIRM,FILENAME_MOVE_RESERVATIONS_PROCESS,FILENAME_REFUND_RESERVATIONS,FILENAME_REFUND_CONFIRM_RESERVATIONS,FILENAME_REFUND_PROCESS,
								FILENAME_REFUND_SUCCESS,FILENAME_SEARCH_RESERVATIONS,FILENAME_SEARCH_RESERVATION_PRINT,FILENAME_SEARCH_RESERVATIONS_DATED,FILENAME_SEARCH_RESERVATION_PRINT_DATED,
								FILENAME_SEARCH_RESERVATION_PRINT_PDF,FILENAME_WAITLISTS,FILENAME_WITHDRAW_PROCESS,FILENAME_WITHDRAW_RESERVATIONS,
							    
							FILENAME_EVENTS_REPORTS_WAITLIST,FILENAME_STATS_RESERVATIONS_COUNT,
							FILENAME_FAQDESK,FILENAME_FAQDESK_REVIEWS
							);
							$box_list=array('catalog.php','customers.php','event.php','instructor.php','faqdesk.php');
						}
  if (sizeof($file_list)<=0) return;

  $files_to_modify=implode("','",$file_list);
  $boxes_to_modify=implode("','",$box_list);
  
  $sql="SELECT * from " . TABLE_ADMIN_FILES . " where admin_files_is_boxes='1'";
  $boxes_query=tep_db_query($sql);
  if (tep_db_num_rows($boxes_query)<=0) return;
  while($boxes_result=tep_db_fetch_array($boxes_query)){
    $admin_groups=explode(",",$boxes_result['admin_groups_id']);
   if(in_array($boxes_result['admin_files_name'],$box_list)){
    if (!in_array($group_id,$admin_groups)){
      $admin_groups[]=$group_id;
      sort($admin_groups,SORT_NUMERIC);
      $update_groups=implode(",",$admin_groups);
      tep_db_query("UPDATE " . TABLE_ADMIN_FILES . " set admin_groups_id='" . $update_groups . "' where admin_files_id='" . $boxes_result['admin_files_id'] . "'");
    }
   } else {
     if (in_array($group_id,$admin_groups)){
       $admin_groups=array_diff($admin_groups,array($group_id));
       $update_groups=implode(",",$admin_groups);
       tep_db_query("UPDATE " . TABLE_ADMIN_FILES . " set admin_groups_id='" . $update_groups . "' where admin_files_id='" . $boxes_result['admin_files_id'] . "'");
     }
   }
  }
  
  $sql="SELECT * from " . TABLE_ADMIN_FILES . " where admin_files_is_boxes='0'";
  $files_query=tep_db_query($sql);
  if (tep_db_num_rows($files_query)<=0) return;
  while($files_result=tep_db_fetch_array($files_query)){
    $admin_groups=explode(",",$files_result['admin_groups_id']);
    if(in_array($files_result['admin_files_name'],$file_list)){
     if (!in_array($group_id,$admin_groups)){
      $admin_groups[]=$group_id;
      sort($admin_groups,SORT_NUMERIC);
      $update_groups=implode(",",$admin_groups);
      tep_db_query("UPDATE " . TABLE_ADMIN_FILES . " set admin_groups_id='" . $update_groups . "' where admin_files_id='" . $files_result['admin_files_id'] . "'");
     }
    } else {
     if (in_array($group_id,$admin_groups)){
       $admin_groups=array_diff($admin_groups,array($group_id));
       $update_groups=implode(",",$admin_groups);
       tep_db_query("UPDATE " . TABLE_ADMIN_FILES . " set admin_groups_id='" . $update_groups . "' where admin_files_id='" . $files_result['admin_files_id'] . "'");
     }
    }
  }
}


	function tep_send_default_email($type,$merge_details,$send_details,$filename = ''){
		$details=array();
		$details['type']=$type;
		
		$details['table']=TABLE_EMAIL_MESSAGES;
		tep_get_template($details);      	  // get template using type and table   
		if ($details['html_text']=="") {	
			if($type!='PRD') return;
			$fp=@fopen(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . "/images/product_email_template.html","r");
			if (!$fp) return;
			$details['html_text']=fread($fp,filesize(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . "/images/product_email_template.html"));
			if ($details['html_text']=="") return false;
			$details['text']=strip_tags($details['html_text']);
			$details['format']="B";
		}
		tep_replace_template($details,$merge_details); // replace template content with merge details
		tep_strip_html($details); // strip tags;
		//send mails to given addresses
		for ($icnt=0;$icnt<sizeof($send_details);$icnt++){
			$details['to_name']=$send_details[$icnt]['to_name'];
			$details['to_email']=$send_details[$icnt]['to_email'];
			$details['from_name']=$send_details[$icnt]['from_name'];
			$details['from_email']=$send_details[$icnt]['from_email'];
			tep_send_email($details,true,$filename); // send email
		}
		
	}
	
	function tep_draw_hidden($name, $value = '', $parameters = '') {
    $field = '<input type="hidden" name="' . tep_output_string($name) . '"';

      $field .= ' value="' . tep_output_string($value) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }
  
  // draw the pull down menu
  function tep_draw_alt_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    $field = '<select name="' . tep_output_string($name) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['text']) {
        $field .= ' SELECTED';
      }

      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

  // format the given date according to configuration setting EVENTS_DATE_FORMAT
  function format_date($raw_date,$simple=false){
    if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '0000-00-00') || ($raw_date == '') || ($raw_date <= 0)) return false;
	$format=EVENTS_DATE_FORMAT;

	if ($simple) $format=strtolower($format);
    $year = substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);
	if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
      return date($format, mktime($hour, $minute, $second, $month, $day, $year));
    } else {
      return preg_replace('/2037$/', $year, date($format, mktime($hour, $minute, $second, $month, $day, 2037)));
    }

  }

	// mail functions used to send emails and message
	function send_internal_message(&$details){
		$sql_array=array(	"message_from"=>$details["from_name"],
							"message_to"=>$details["to_name"],
							"message_date"=>'now()',
							"message_subject"=>$details['subject'],
							"message_text"=>$details['text']);
		tep_db_perform(TABLE_ADMIN_MESSAGES,$sql_array);
	}
	// replace the template with merge details
	 function tep_replace_template(&$details,&$replace_array){
 
  reset($replace_array);
  //FOREACH
  //while(list($key,$value)=each($replace_array)){
	foreach($replace_array as $key => $value) {
   $details['html_text']=preg_replace("/%%" . $key . "%%/i",preg_escape_back($value). "",$details['html_text']);
  }
  $details['html_text']=preg_replace("/%%current_url%%/i",HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . "/",$details['html_text']);
 }
 function preg_escape_back($string) {
    // Replace $ with \$ 
    $string = preg_replace('#(\\$|\\\\)#', '\\\\$1', $string);
    return $string;
  } 
	// common function to send email
	//fix for test email 2021
	// edited to handle pdf attachment
	function tep_send_email(&$details,$default=false,$filename=''){
		$result=false;
		if ($default==false && (int)EMAIL_ACTIVATE!=1) return $result;

		 $message = new email(array('X-Mailer: osConcert'));
			
		 if ($details['format'] != 'T') {
			 $message->add_html($details['html_text'], $details['text']);
		 } else {
			 $message->add_text($details['text']);
		 }
	     	  

		//new code pdf send
		//Feb 2020 add in smtp attachment
		if(DISPLAY_PDF_DELIVERED_ONLY=='true' && isset($filename) && $filename !==''){ 
			  $message->add_attachment($message->get_file($filename),'eticket.pdf','application/pdf');
			  //smtp
			  $message->add_smtp_attachment($filename,'eticket.pdf');
			  }
		  
		 $message->build_message();
		  if(strpos($details['to_email'],',')!==false) {
                 $to_email=array();
                 $to_email=explode(",",$details['to_email']);
                 $details['to_email']=$to_email;
             }
			 
		
		 $result=$message->send($details['to_name'], $details['to_email'], $details['from_name'], $details['from_email'], $details['subject']);
		 unset($message);
		 return $result;
	}
	// function to fetch content from database table
	function tep_get_template(&$details){ 
		$details['html_text']='';
		$details['text']='';
		$events_option="";
		// if (isset($details['events_id']))
			// $add_option=" and events_id='" . $details['events_id'] . "'";
		// else if (isset($details['subscription_id']))
			// $add_option=" and subscription_id='" . $details['subscription_id'] . "'";
         
         
		$mail_data_query=tep_db_query("SELECT * from " . $details["table"] . " where message_type='" . tep_db_input($details["type"]) . "'" . $add_option);
		
		if (tep_db_num_rows($mail_data_query)>0){
			$mail_data_result=tep_db_fetch_array($mail_data_query);
			$details['format']=$mail_data_result['message_format'];
			$details['html_text']=$mail_data_result['message_text'];

			$details['text']=strip_tags($mail_data_result['message_text'],'<br>');
			$details['subject']=$mail_data_result['message_subject'];
			
            return $details;
		}
		
	}
	// stip the html 
	function tep_strip_html(&$details){
		$details['text']=strip_tags($details['html_text'],'<br>');
		$details['text']=str_replace(array('<br />','<br>','<BR>','<BR />','<br/>','<BR/>'),chr(13). chr(10),$details['text']);
	}
	
	
	// redraw the post fields to the page
	function tep_draw_post_fields($fields){
		global $FREQUEST;
		reset($fields);
		$result="";
		//reset and iterate all fields in the array
		$post_key=$FREQUEST->postvalue($key);
		//FOREACH
		//while(list(,$key)=each($fields)){
		foreach($fields as $key=>$value)	
		{
			if ($post_key!=''){ // if the field value is present
				if (is_array($post_key)){ // if its a array
					$value=&$post_key;
					for ($icnt=0;$icnt<sizeof($value);$icnt++){
						$result.='<input type="hidden" name="' . $key . '[]" value="' . $value[$icnt] . '">' . "\n";
					}
				} else {
					$result.='<input type="hidden" name="' . $key . '" value="' . tep_output_string($post_key) . '">' . "\n";
				}
			}
		}
		return $result;
	}
	function tep_redirect_post($filename,$params,$values){
		echo '<html><head><title>test</title></head><body>';
		echo '<form name="frmSubmit" method="post" action="' . tep_href_link($filename,$params) . '">';
		echo tep_draw_post_fields($values);
		echo '<input type="hidden" name="add_option" value="none">';
		echo '</form>';
		echo '<script language="javascript">document.frmSubmit.submit();</script>';
		echo '</body></html>';
		require(DIR_WS_INCLUDES . 'application_bottom.php');
		tep_exit_execution();
	}
	function tep_exit_execution(){
    global $logger;
		if (STORE_PAGE_PARSE_TIME == 'true') {
		  if (!is_object($logger)) $logger = new logger;
		  $logger->timer_stop();
		}
	
		exit;
	}
	

	
	// calculate the given date
	function tep_date_calculate($timestamp, $unit, $amount, $mode) {
		if($amount<=0) $amount=1;
	// Possible $units are: "hr", "min", "sec",
	//          "mon", "day", or "yr"
	// $amount should be an integer
	   $delta_vars = array("hr"=>0, "min"=>0,
	   "sec"=>0, "mon"=>1,
	   "day"=>1,"yr"=>1970);
	   if ($mode==1)
		   $delta_vars[$unit] += $amount;
		else 
			$delta_vars[$unit] -= $amount;
	   $delta = gmmktime($delta_vars["hr"],
	   $delta_vars["min"],
	   $delta_vars["sec"],
	   $delta_vars["mon"],
	   $delta_vars["day"],
	   $delta_vars["yr"]);
	   return $timestamp - $delta;
	}
	
	
 
 // round the given amount according to round factor
 function tep_get_rounded_amount($amount){
 	$result=0;
	$round_digit=0;
	$decimal=0;
 	$round_type=EVENTS_ORDER_AMOUNT_ROUND;

	$decimal=floor((($amount-floor($amount))*100)+0.5);
	switch($round_type){
		case "0.01":
			$result=$amount;
			break;
		case "0.05":
			$round_digit=$decimal%10;
			if ($round_digit>7) {
				$decimal=(floor($decimal/10)+1)*10;
			} else if ($round_digit>2) {
				$decimal=(floor($decimal/10)*10)+5;
			} else {
				$decimal=floor($decimal/10)*10;			
			}
			$result=floor($amount)+$decimal/100;
			break;
		case "0.1":
			$decimal=(floor($decimal/10))*10;
			$result=floor($amount)+$decimal/100;
			break;
		case "0":
		default:
			if ($decimal>=50)
				$result=floor($amount)+1;
			else
				$result=floor($amount);
	}
	return $result;
 }

 // utility function to prepare hidden fields
 function tep_prepare_hidden_params($keys,$mode='post',$output='post',$check_array=array()){
 	global $FREQUEST;
	$result="";
	if ($mode=='post'){
		for ($icnt=0;$icnt<sizeof($keys);$icnt++){
			$keys_icnt=$FREQUEST->postvalue($keys[$icnt]);
			if ($keys_icnt!=''){
				if ($output=='post'){
					$result.= '<input type="hidden" name="' . $keys[$icnt] . '" value="' . htmlspecialchars(stripslashes($keys_icnt)) . '">';
				} else {
					$result.=$keys[$icnt] . "=" . stripslashes($keys_icnt) . "&";
				}
			}
		}
	} else if ($mode=='get') {
		for ($icnt=0;$icnt<sizeof($keys);$icnt++){
			if ($keys_icnt!=''){
				if ($output=='post'){
					$result.= '<input type="hidden" name="' . $keys[$icnt] . '" value="' . htmlspecialchars(stripslashes($keys_icnt)) . '">';
				} else {
					$result.=$keys[$icnt] . "=" . stripslashes($keys_icnt) . "&";
				}
			}
		}
	} else {
		for ($icnt=0;$icnt<sizeof($keys);$icnt++){
			if (isset($check_array[$keys[$icnt]])){
				if ($output=='post'){
					$result.= '<input type="hidden" name="' . $keys[$icnt] . '" value="' . htmlspecialchars(stripslashes($check_array[$keys[$icnt]])) . '">';
				} else {
					$result.=$keys[$icnt] . "=" . stripslashes($check_array[$keys[$icnt]]) . "&";
				}
			}
		}
	}
	return $result;
 }
 // format may be in format dd mm yyyy in any combinations but length must be equal to this
 function tep_check_date_raw($date,$format,$sep_char="/-/"){
 	$date_details=array('y'=>0,'m'=>0,'d'=>0);
	$split_arr=preg_split($sep_char,$date);
	$split_format=preg_split($sep_char,$format);
	if (!(sizeof($split_arr)==sizeof($split_format) && sizeof($split_arr)>0 && sizeof($split_arr)<=3)) return false;
	
	for ($icnt=0;$icnt<sizeof($split_arr);$icnt++){
		if (isset($date_details[strtolower($split_format[$icnt])]))
			$date_details[strtolower($split_format[$icnt])]=(int)$split_arr[$icnt];
	}
	return checkdate($date_details['m'],$date_details['d'],$date_details['y']);
 }
 // format may be in format dd mm yyyy in any combinations but length must be equal to this
 function tep_convert_date_raw($date,$format=EVENTS_DATE_FORMAT,$sep_char="/-/"){
	$date_details=array('y'=>0,'m'=>0,'d'=>0);
	$split_arr=preg_split($sep_char,$date);
	$split_format=preg_split($sep_char,$format);
	if (!(sizeof($split_arr)==sizeof($split_format) && sizeof($split_arr)>0 && sizeof($split_arr)<=3)) return "";
	
	for ($icnt=0;$icnt<sizeof($split_arr);$icnt++){
		if (isset($date_details[strtolower($split_format[$icnt])]))
			$date_details[strtolower($split_format[$icnt])]=(int)$split_arr[$icnt];
	}
	$date_details['d']=sprintf("%02d",$date_details['d']);
	$date_details['m']=sprintf("%02d",$date_details['m']);
	return $date_details['y'] . '-' . $date_details['m'] . '-' . $date_details['d']; 
 }


 // get the current server date considering date offset settings
 function getServerDate($time=false){

	$offset=(float)EVENTS_SERVER_DATE_OFFSET;
	if($offset>0){
		if(strpos($offset,'.')>0){
			$cur_offset_time = mktime(date('H')+abs($offset),date('i')+30,date('s'),date('m'),date('d'),date('y'));
			if($time)
				return date('Y-m-d H:i:s',$cur_offset_time);
			else
				return date('Y-m-d',$cur_offset_time);
		}else{
			$cur_offset_time = mktime(date('H')+abs($offset),date('i'),date('s'),date('m'),date('d'),date('y'));
			if($time)
				return date('Y-m-d H:i:s',$cur_offset_time);
			else
				return date('Y-m-d',$cur_offset_time);
		}
	}else{
		if(strpos($offset,'.')>0){
			$cur_offset_time = mktime(date('H')-abs($offset)+1,date('i')-30,date('s'),date('m'),date('d'),date('y'));
			if($time)
				return date('Y-m-d H:i:s',$cur_offset_time);
			else
				return date('Y-m-d',$cur_offset_time);
		}else{
			$cur_offset_time = mktime(date('H')-abs($offset),date('i'),date('s'),date('m'),date('d'),date('y'));
			if($time)
				return date('Y-m-d H:i:s',$cur_offset_time);
			else
				return date('Y-m-d',$cur_offset_time);
		}
	}
 }
 
  
 //new function used in the funtion after to update master quantity in event of a refund
 function ga_update_quan($product_id, $prod_quantity, $prod_type){
$result = array();
//take the product id, find the cPath, explode it and then run a check to see if any cat has a GA setting
$ga_path_array = array();
$ga_path_array = explode('_', ga_get_product_path($product_id));
$max = sizeof($ga_path_array);

if ($max >0){
	for ($i=0; $i<$max; $i++) {
##
// increase ga_amount by this product's quantity

 $category_ga_query = tep_db_query("select categories_GA,categories_quantity_remaining from " . TABLE_CATEGORIES . " where categories_id = '" . $ga_path_array[$i]. "' limit 1");
   			 if (tep_db_num_rows($category_ga_query)) { 
			  $category_ga = tep_db_fetch_array($category_ga_query);

						
					if(($prod_type=='G' && $category_ga['categories_GA'] == 1))
														{
					$quantity_left_ga=(($category_ga['categories_quantity_remaining'])+($prod_quantity));
					 //update the master quantity
					 	tep_db_query("update " . TABLE_CATEGORIES . " set categories_quantity_remaining = '" .$quantity_left_ga. "' where categories_id = '" . $ga_path_array[$i] . "'");
		}//end elseif
		
		elseif(($prod_type=='F' && $category_ga['categories_GA'] == 1))
														{
										 
					$quantity_left_ga=(($category_ga['categories_quantity_remaining'])+FAMILY_TICKET_QTY);
					 //update the master quantity
					 	tep_db_query("update " . TABLE_CATEGORIES . " set categories_quantity_remaining = '" .$quantity_left_ga . "' where categories_id = '" . $ga_path_array[$i] . "'");
							}//end elseif
		
      }//end tep_db_num_rows
	}
   }
}
 #######cartzone we are using the support_packs_type as a guide to distinguish the difference between a GA order and a MULTI seat reservation########
  function tep_remove_order_events($order_id, $restock = false) {
    if ($restock == 'on') {
      $order_query = tep_db_query("select products_id, products_quantity,support_packs_type from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "' and products_type='P'");
      while ($order = tep_db_fetch_array($order_query)) 
	  {
	   //determine if master quantity present and update
	    ga_update_quan($order['products_id'], $order['products_quantity'],$order['support_packs_type']);
	   
	  if ($order['support_packs_type'] == 'P')
	  {
	  	 //cartzone restock
	  tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '1', products_status ='1', products_ordered = products_ordered - " . $order['products_quantity'] . " where products_id = '" . (int)$order['products_id'] . "'");
	  }elseif ($order['support_packs_type'] == 'F')
	  {
	  	 //cartzone restock FAMILY tickets
		 $products_quantity=$order['products_quantity'];
		 
	  tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $products_quantity*FAMILY_TICKET_QTY . ", products_ordered = products_ordered - " . $order['products_quantity'] . " where products_id = '" . (int)$order['products_id'] . "'");
	  }
	  else{//GA Products
        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order['products_quantity'] . ", products_ordered = products_ordered - " . $order['products_quantity'] . " where products_id = '" . (int)$order['products_id'] . "'");
   
		 }
      }
    }//end of if restock = on

	tep_db_query("delete from " . TABLE_CUSTOMERS_TO_CUSTOMERS ." where orders_id='".(int)$order_id."'");
 	//}
    tep_db_query("delete from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "'");
	tep_db_query("delete from " . TABLE_WALLET_HISTORY . " where orders_id = '" . (int)$order_id . "'");
	
	clear_ticket_tables(0, (int)$order_id );
	
	
  }
	
	
	// utility function to get the input parameter
	function get_request_param($key,$type="numeric",$default=""){
		global $FREQUEST;
		$success=true;
		$get_key=$FREQUEST->getvalue($key);
		if ($get_key==''){
			$success=false;
		} else if ($type=="N" && !is_numeric($get_key)){
			$success=false;
		}
		if ($success){
			return $get_key;
		} else {
			if ($default!="") return $default;
			if ($type=="N") return 0;
			return "";
		}
	}
	
	// write  content to a text file
	function tep_write_text_file($filename,&$content){
		$filename=DIR_FS_CATALOG . '/images/' . $filename;
		if (file_exists($filename)){
			unlink($filename);
		}
		$fp=@fopen($filename,"w");
		if ($fp){
			fwrite($fp,$content,strlen($content));
			fclose($fp);
		}
	}
	function tep_get_report_name($idValue){
		$idValue=strtolower($idValue);
		$name="";
		switch($idValue){
			case "rs":
				$name=FILENAME_REPORTS_SHIPPING;
				break;
			case 'rdc':
				$name=FILENAME_REPORTS_DISCOUNT_COUPON;
				break;
			case "ct":
				$name=FILENAME_CUSTOMER_DETAILS;
			
			case "cl":
				$name=FILENAME_CALL_CENTRE_ORDERS;
				break;
			
			 case "waup":
			 	$name=FILENAME_REPORTS_WALLET;
				break;
			
			case "dd":
				$name=FILENAME_REPORTS_DIRECT_DEPOSIT;
				break;	
			case "rscl":
				$name=FILENAME_SERVICES_CALL_CENTRE_ORDERS;
				break;
			case "rr":
				$name=FILENAME_PRODUCTS_REFUNDS;
				break;
			
			case 'sdc':
				$name=FILENAME_DISCOUNT_COUPONS;
				break;
			case 'cm':
				$name=FILENAME_CUSTOMERS_MAINPAGE;
				break;
		}
		return tep_href_link($name,"return=1");
	}
	function tep_get_report_params(){
		global $input_params;
		$result="";
		$params=$input_params;
		if (sizeof($params)>0){
			reset($params);
			//FOREACH
			//while(list($key,$value)=each($params)){
			foreach($params as $key => $value) {
				if (!is_array($value)){
					if ($key!="post_action" && $key!='new_status' && $key!='X' && $key!='Y' && $key!='page')
						$result.='<input type="hidden" name="' . $key . '" value="' . htmlentities(stripslashes($value)) . '">';
				}
			}
		}
		return $result;
	}
	
// TABLES: sources
  function tep_get_sources($sources_id = '') {
    $sources_array = array();
    if (tep_not_null($sources_id)) {
        $sources = tep_db_query("select sources_name from " . TABLE_SOURCES . " where sources_id = '" . (int)$sources_id . "'");
        $sources_values = tep_db_fetch_array($sources);
        $sources_array = array('sources_name' => $sources_values['sources_name']);
    } else {
      $sources = tep_db_query("select sources_id, sources_name from " . TABLE_SOURCES . " order by sources_name");
      while ($sources_values = tep_db_fetch_array($sources)) {
        $sources_array[] = array('sources_id' => $sources_values['sources_id'],
                                   'sources_name' => $sources_values['sources_name']);
      }
    }

    return $sources_array;
  }

// Creates a pull-down list of countries
  function tep_get_source_list($name, $selected = '', $parameters = '') {
    $sources_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
    $sources = tep_get_sources();

    for ($i=0, $n=sizeof($sources); $i<$n; $i++) {
      	$sources_array[] = array('id' => $sources[$i]['sources_id'], 'text' => $sources[$i]['sources_name']);
    }
	if (DISPLAY_REFERRAL_OTHER=='true'){
		$sources_array[] = array('id' => '9999', 'text' => TEXT_REFERRAL_OTHER);
	}

    return tep_draw_pull_down_menu($name, $sources_array, $selected, $parameters);
  }
  
  function tep_get_customer_options($type) {
    $options_array = array();
	$options_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
    $options = tep_db_query("select options_id, options_name from " . TABLE_CUSTOMER_OPTIONS . " where options_type='". tep_db_input($type)  ."' order by options_name");
      while ($options_values = tep_db_fetch_array($options)) {
        $options_array[] = array('id' => $options_values['options_id'],
                                   'text' => $options_values['options_name']);
      }
    return $options_array;
  }

  function tep_get_customers_groups_id($customer_id) {
    $customers_groups_query = tep_db_query("select customers_groups_id from " . TABLE_CUSTOMERS . " where customers_id =  '" . tep_db_input($customer_id) . "'");
    $customers_groups_id = tep_db_fetch_array($customers_groups_query);
    return $customers_groups_id['customers_groups_id'];
  }
  function tep_get_products_special_price($product_id,$customer_id) {
    $customer_groups_id = tep_get_customers_groups_id($customer_id);

	$specials_query = tep_db_query("select specials_new_products_price,customers_id,customers_groups_id from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "' and status = '1'");

    if (tep_db_num_rows($specials_query)) {
      $special = tep_db_fetch_array($specials_query);
	  	if ($customer_id>0 && $customer_id==$special["customers_id"]){
		  $special_price = $special['specials_new_products_price'];
		} else if ($customer_groups_id>0 && $customer_groups_id==$special["customers_groups_id"]){
		  $special_price = $special['specials_new_products_price'];
		} else if ($special["customers_id"]<=0 && $special["customers_groups_id"]<=0){
		  $special_price = $special['specials_new_products_price'];
		} else {
			$special_price=false;
		}
    } else {
	  $special_price = false;
    }
	
	return $special_price;
  }
  //get discount price from products
  function tep_get_products_discount_price($products_price,$customer_id){  
  
	 $query = tep_db_query("select g.customers_groups_discount from " . TABLE_CUSTOMERS_GROUPS . " g inner join  " . TABLE_CUSTOMERS  . " c on g.customers_groups_id = c.customers_groups_id and c.customers_id = '" . tep_db_input($customer_id) . "'");
	 $query_result = tep_db_fetch_array($query);
	 $customers_groups_discount = $query_result['customers_groups_discount'];
	 $query = tep_db_query("select customers_discount from " . TABLE_CUSTOMERS . " where customers_id =  '" . tep_db_input($customer_id) . "'");
	 $query_result = tep_db_fetch_array($query);
	 $customer_discount = $query_result['customers_discount'];
	 $customer_discount = $customer_discount + $customers_groups_discount;
	 if ($customer_discount >= 0) {
		$products_price = $products_price + $products_price * abs($customer_discount) / 100;
	 } else {
		$products_price = $products_price - $products_price * abs($customer_discount) / 100;
	 }
	 
	 return $products_price;
  }
  
  function tep_cfg_hide_from_backend($id,$key='')
	{
	    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
		$hide_backend_array = array(array('id' =>TEXT_PRODUCTS,'text'=>TEXT_PRODUCTS));
		return tep_draw_pull_down_menu($name, $hide_backend_array, $id);	
	} 
  function tep_payment_installed($module){
		$modules=MODULE_PAYMENT_INSTALLED;

		if ($modules!=""){
			$modules_array=preg_split("/;/",$modules);
			
			if (array_search($module,$modules_array)===false){
				return false;
			} else {
				return true;
			}
		}
	}
   function tep_discount_price($products_id,$products_price,$products_tax,$customer_id)
  {  
    $empty=0;
	$result_array=array();
	$count_customer_query=tep_db_query("select customers_groups_id,customers_id,specials_new_products_price from " . TABLE_SPECIALS ."");
	$count_customer_value=tep_db_fetch_array($count_customer_query);
	$ap_customer_id=$count_customer_value['customers_id'];
	$ap_customer_group_id=$count_customer_value['customers_groups_id'];
	$ap_price=$count_customer_value['specials_new_products_price'];
	if($ap_customer_group_id==0 && $ap_customer_id==0 && ($ap_price!='' || $ap_price!=0)){
      $query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id='" . tep_db_input($products_id) . "'");
	}else {  
	  $query = tep_db_query("select specials_new_products_price from " . TABLE_CUSTOMERS . " c," . TABLE_SPECIALS . " s where s.products_id='" . tep_db_input($products_id) . "' and ((s.customers_id ='" . tep_db_input($customer_id) ."' and s.customers_id=c.customers_id) or (c.customers_id='". tep_db_input($customer_id) ."' and s.customers_groups_id=c.customers_groups_id))");
	}
	$query_result = tep_db_fetch_array($query);
	$products_special_price = $query_result['specials_new_products_price'];
		if(($ap_customer_group_id!=0 || $ap_customer_id!=0) || ($ap_customer_group_id==0 && $ap_customer_id==0 && $ap_price!=0) ){
		  $result_array=array('id'=>'SP','text'=>tep_add_tax($products_special_price, $products_tax));
	      return  $result_array;
		}else if($ap_price==0 && ($ap_customer_id==0 && $ap_customer_group_id==0)) {
		     $query = tep_db_query("select g.customers_groups_discount from " . TABLE_CUSTOMERS_GROUPS . " g inner join  " . TABLE_CUSTOMERS  . " c on g.customers_groups_id = c.customers_groups_id and c.customers_id = '" . tep_db_input($customer_id) . "'");
			 $query_result = tep_db_fetch_array($query);
			 $customers_groups_discount = $query_result['customers_groups_discount'];
			 $query = tep_db_query("select customers_discount from " . TABLE_CUSTOMERS . " where customers_id =  '" . tep_db_input($customer_id) . "'");
			 $query_result = tep_db_fetch_array($query);
			 $customer_discount = $query_result['customers_discount'];
			 $customer_discount = $customer_discount + $customers_groups_discount;
			 if ($customer_discount >= 0) {
				$products_price = $products_price + $products_price * abs($customer_discount) / 100;
				$result_array=array('id'=>'GD','text'=>$products_price);
			 } else {
				$products_price = $products_price - $products_price * abs($customer_discount) / 100;
			   $result_array=array('id'=>'GD','text'=>$products_price);
			 }
			 return $result_array; 
		}else {
		  return $empty;
		}
  }
  function tep_product_small_image($image,$title,$parameters=""){
	if (file_exists(DIR_FS_CATALOG_IMAGES . "small/" . $image)){
		return tep_image(DIR_WS_CATALOG_IMAGES . "small/" . $image,$title,'','',$parameters);
	} else if (file_exists(DIR_FS_CATALOG_IMAGES . "big/" . $image)){
		return tep_image(DIR_WS_CATALOG_IMAGES . "big/" . $image,$title,SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT,$parameters);
	} else if (file_exists(DIR_FS_CATALOG_IMAGES . $image)){
		return tep_image(DIR_WS_CATALOG_IMAGES . $image,$title,SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT,$parameters);
	} else { 
		return TEXT_IMAGE_DOES_NOT_EXIST;
	}
  }
  function tep_product_email_image($image,$title,$parameters=""){
	if (file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . "small/" . $image)){
		return '<img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . "small/" . $image . '" width=50 height=50>';
	} else if (file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . "big/" . $image)){
		return '<img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . "big/" . $image . '" width=50 height=50>';
	} else if(file_exists(HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $image)) {
		return '<img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $image . '" width=50 height=50>';
	} else {
		return tep_image(DIR_WS_IMAGES . 'pixel_trans.gif','', '50','50');
	}
  }	
  function tep_get_products_stock($products_id) {
    $products_id = tep_get_prid($products_id);
    $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
    $stock_values = tep_db_fetch_array($stock_query);

    return $stock_values['products_quantity'];
  }

////
// Check if the required stock is available
// If insufficent stock is available return an out of stock message
  function tep_check_stock($products_id, $products_quantity) {
    $stock_left = tep_get_products_stock($products_id) - $products_quantity;
    $out_of_stock = '';

    if ($stock_left < 0) {
      $out_of_stock = '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
    }

    return $out_of_stock;
  }
  
    function tep_get_attributes_stock($products_id,$attribute_id) {
    $products_id = tep_get_prid($products_id);
	$attribute_id=tep_get_sorted_attribute_ids($attribute_id);
	$stock_sql = "select products_quantity from " . TABLE_PRODUCTS_STOCK . " where products_id = '" . (int)$products_id . "' and attributes_id='". tep_db_input($attribute_id)."'";
	$stock_query = tep_db_query($stock_sql);
	//echo "select products_attributes_quantity from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' and options_id='".$options_id."' and options_values_id='".$values_id."'";
    $stock_values = tep_db_fetch_array($stock_query);
    return $stock_values['products_quantity'];
  }
  
  
  // Add an option in Products Expected to email all customers 
  //who have been waiting for news about an out of stock item. 

 
   function tep_productsexpected($products_id){
   
   $prod_att=tep_db_query("select pa.products_id,pa.attributes_id from " . TABLE_PRODUCTS_STOCK ." pa where pa.products_id='". tep_db_input($products_id) ."' group by pa.attributes_id ,pa.products_id");
				$num_rows=tep_db_num_rows($prod_att);
				if ($num_rows>0) {    
					  while ($prod_att_result = tep_db_fetch_array($prod_att)) {
					  
					  $att_id=$prod_att_result['attributes_id'];
					 
					  $id_splt=preg_split("/-/",$att_id);
					  for($inc=0;$inc<count($id_splt);$inc++)
					  {
					  $option_values=preg_split('/{/',$id_splt[$inc]);
					 
					  $att_value=tep_db_query("select po.products_options_name,po.products_options_id,pov.products_options_values_name,pov.products_options_values_id from " . TABLE_PRODUCTS_OPTIONS ." po ," . TABLE_PRODUCTS_OPTIONS_VALUES . " pov ," . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " povp, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where po.products_options_id=povp.products_options_id and pov.products_options_values_id=povp.products_options_values_id and pa.options_id=po.products_options_id and pa.options_values_id=pov.products_options_values_id and pa.products_id='".$prod_att_result['products_id']."' and po.products_options_id = '".tep_db_input($option_values[0])."' and pov.products_options_values_id='".tep_db_input($option_values[1]). "' ");
                      $att_value_result = tep_db_fetch_array($att_value);
					  $att_op_name.= $att_value_result['products_options_name'].'-'.$att_value_result['products_options_values_name'].'<br>';
				
					  }
					  }
					  }
					  else 
					  {
					  $att_op_name="No Attributes";
					  }
					  
					
    return $att_op_name;
   }
  
////
// Check if the required stock is available
// If insufficent stock is available return an out of stock message
  function tep_check_attribute_stock($products_id, $attribute_id,$products_quantity) {
    $stock_left = tep_get_attributes_stock($products_id,$attribute_id) - $products_quantity;
    $out_of_stock = '';

    if ($stock_left <0) {
      $out_of_stock = '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
    }
    return $out_of_stock;
  }
  


 function tep_cfg_pull_down_prod_view($config_value,$key=''){
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
  	$details_arr=array(array("id"=>"BACKORDER","text"=>"BackOrder"),array("id"=>"PARTIALSHIP","text"=>"PartialShip"),array("id"=>"HOLDORDER","text"=>"Hold Order"),array("id"=>"NOBACKORDER ","text"=>"NoBackOrder"));
  	$result=tep_draw_pull_down_menu($name,$details_arr,$config_value);
	return $result;
  }

 
  function tep_get_products_array_single($first=''){
		global $customer_id,$FSESSION;//$customers_group_id
		$customers_group_id=tep_get_customers_groups_id($customer_id);
		if($customers_group_id == '')$customers_group_id = 0;
		if($customer_id == '')$customer_id = 0;
		$products_array=array();
		/*$products_sql = "select pd.products_name,pd.products_id from  " . TABLE_PRODUCTS . " p," . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " pcd " . 
		" where p.products_id = pd.products_id and pd.products_id=pcd.products_id and pd.language_id = '" . (int)$languages_id . "' and (p.restrict_to_customers like '%," . $customer_id . ",%' OR p.restrict_to_groups like '%," . $customers_group_id . ",%' " . 
		" OR (p.restrict_to_customers = '' AND p.restrict_to_groups = ''))";*/
		
		//cartzone dumped some query to get refunds to work basically
		$products_sql = "select pd.products_name,pd.products_id from  " . TABLE_PRODUCTS . " p," . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " pcd " . 
		" where p.products_id = pd.products_id and pd.products_id=pcd.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' ";
		//" OR (p.restrict_to_customers like '%," . $customer_id . ",%' OR p.restrict_to_groups like '%," . $customers_group_id . ",%') " . 
//		" and ((p.restrict_to_customers='' OR ISNULL(p.restrict_to_customers)) AND (p.restrict_to_groups='' OR ISNULL(p.restrict_to_groups)))";
//		" and (" . 
//				"(p.restrict_to_customers = '' OR ISNULL(p.restrict_to_customers))" . 
//				"and(p.restrict_to_groups = '' OR ISNULL(p.restrict_to_groups))" . 
//				"OR(p.restrict_to_customers like '%," . $customer_id . ",%' OR p.restrict_to_groups like '%," . $customers_group_id . ",%')" . 
		//")
		//";
		//echo $products_sql;

		$products_query=tep_db_query($products_sql);
		while($products_result=tep_db_fetch_array($products_query)){
			$products_array[]=array('id'=>$products_result['products_id'],'text'=>$products_result['products_name']);
		}
		return $products_array;
	} 
	
	function tep_get_products_output_tree($parent_id = '0',&$content,$default,$product_category_id=0) {
    global $customer_id,$FSESSION,$selected_name; //$customer_group_id
	$customers_group_id=tep_get_customers_groups_id($customer_id);

	// iterate with nested category
	
	$products_where = "";
	if($product_category_id>0)$products_where = " and c.categories_id='" . tep_db_input($product_category_id) . "' ";
	$categories_sql = "select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "' and c.parent_id = '" . (int)$parent_id . "' " . $products_where . " order by c.sort_order, cd.categories_name";
	$categories_query = tep_db_query($categories_sql);
	while ($products_category = tep_db_fetch_array($categories_query)) {
		$content.= '<optgroup style="color:#0000FF" label="' . write_category_space($products_category['categories_id']) . tep_output_string($products_category['categories_name'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '">';
		// select the list of products of particular category
		if($customers_group_id == '')$customers_group_id = 0;
		if($customer_id == '')$customer_id = 0;
		$products_sql = "select pd.products_name,pd.products_id from " . TABLE_PRODUCTS . " p," . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " pcd " . 
		" where p.products_id=pd.products_id and  pcd.categories_id='" . (int)$products_category['categories_id'] . "' " . 
		" and pd.products_id=pcd.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' and p.products_status=1" . 
		//" and (p.restrict_to_customers like '%," . $customer_id . ",%' OR p.restrict_to_groups like '%," . $customer_group_id . ",%' OR (p.restrict_to_customers = '' AND p.restrict_to_groups = ''))";
		/*$products_sql = "select pd.products_name,pd.products_id from  " . TABLE_PRODUCTS . " p," . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " pcd " . 
		"where p.products_id = pd.products_id and pd.products_id=pcd.products_id and pd.language_id = '" . (int)$languages_id . "' " . 
		*/" and (" . 
				"(p.restrict_to_customers = '' OR ISNULL(p.restrict_to_customers))" . 
				"and(p.restrict_to_groups = '' OR ISNULL(p.restrict_to_groups))" . 
				"OR(p.restrict_to_customers like '%," . $customer_id . ",%' OR p.restrict_to_groups like '%," . $customers_group_id . ",%')" . 
		")";
		$products_query=tep_db_query($products_sql);
		while ($products = tep_db_fetch_array($products_query))
		{
			$content .= '<option style="color:#000000" value="' . tep_output_string($products['products_id']) . '"';
			if (is_array($default))
			{
			  for ($j=0; $j<sizeof($default); $j++) {
					if ($default[$j]['id'] == $products['products_id'])
					{
						$content .= ' SELECTED';
					}
				}
			}
			elseif($default == $products['products_id']){
				$selected_name=$products['products_name'];
				$content .= ' SELECTED';
			}
			$content .= '>' . write_category_space($products_category['categories_id']) . tep_output_string($products['products_name'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
		}
		$content.='</optgroup>';
		tep_get_products_output_tree($products_category['categories_id'],$content,$default);
	}
}
	function write_category_space($category_id){
	    global $FSESSION,$selected_name;
		$sql = "select parent_id,c.categories_id,categories_name from " . TABLE_CATEGORIES . " c," . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id=cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "' and c.categories_id=" . tep_db_input($category_id);
		$query = tep_db_query($sql);
		$result = tep_db_fetch_array($query);
		if($result['parent_id']>0){
			return "&nbsp;&nbsp;" . write_category_space($result['parent_id']);
		}else{
			return "";
		}
	}
	// Output a form pull down menu
	function tep_draw_products_select_menu($name, $values,$selected_vals,  $parameters = '', $required = false,$initial_category_id=0,$need_all=true,$initial_parent_id=0) {
		global $selected_name;
		$field = '<select name="' . tep_output_string($name) . '"';
	
		if (tep_not_null($parameters)) $field .= ' ' . $parameters;
		$field.=' class="inputNormal" onBlur="javascript:toggle_focus(this,1);" onFocus="javascript:toggle_focus(this,2);"';
	
		$field .= '>';
		if($need_all)
		$field .= '<option style="color:#000000" value="-1">' . TEXT_ALL_PRODUCTS . '</option>';
	
		if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);
	
		$content="";
		
		tep_get_products_output_tree($initial_parent_id,$content,$selected_vals,$initial_category_id);
		$field.=$content;
	$field .= '</select>';
	
	if ($required == true) $field .= TEXT_FIELD_REQUIRED;
	
	return $field;
	}
function tep_cfg_pull_down_weight_unit_title($id){
  if($id=='Kgs:')
  	$value=TEXT_KILOGRAMS;
  else if($id=='lb'	)
  	$value=TEXT_POUNDS;
	return $value;
  }
function tep_cfg_pull_down_weight_unit($id,$key=''){
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
  	$weight_unit=array(array('id' => TEXT_KG,'text' =>TEXT_KILOGRAMS),array('id' =>TEXT_LP,'text' =>TEXT_POUNDS));
	    return tep_draw_pull_down_menu($name, $weight_unit, $id);

  }
  function tep_cfg_pull_down_shop_weight_unit($unit_id, $key = '') {
   $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
	$unit_array = array(array("id"=>"KG","text" =>TEXT_KILOGRAMS),array("id"=>"OZ","text"=>TEXT_OUNCES));
  return tep_draw_pull_down_menu($name, $unit_array, $unit_id);
 }

 function tep_cfg_pull_down_shop_weight_unit_title($unit_id){
	if($unit_id==TEXT_KG){
		return TEXT_KILOGRAMS;
	}else if($unit_id==TEXT_OZ){
		return TEXT_OUNCES;
	}
 }
////
// Break a word in a string if it is longer than a specified length ($len)

function tep_get_OWD_shipping_type($type=''){
		switch($type){
			case 'usps_Express':
				$shipping="POS.EXP"; 
			break;
			case 'usps_Parcel':
				$shipping="OWD.4th.PARCEL";
			break;
			case 'ups_GND':
				$shipping="UPS.GND";
			break;
			case 'ups_1DA':
				$shipping="UPS.NDA";
			break;
			default:
			$shipping="OWD.1ST.PRIORITY";
		}
		return $shipping; 
	}	
	function tep_get_unit_name(){
		if (!defined("SHOP_WEIGHT_UNIT")) return TEXT_KG;
		if (!defined("TEXT_" . SHOP_WEIGHT_UNIT)) return TEXT_KG;
		return constant("TEXT_" . SHOP_WEIGHT_UNIT);
	}
function tep_get_sku_ids($products_id,$attribute_ids=''){
		if($products_id=='') return;
		$sku_ids="";
		if($attribute_ids!=""){
			$sku_query=tep_db_query("select sku from ".TABLE_PRODUCTS_STOCK." where products_id=$products_id and attributes_id='".(int)$attribute_ids."'");
			if(tep_db_num_rows($sku_query)>0){
				$sku=tep_db_fetch_array($sku_query);
				$sku_ids=$sku['sku'];
			}
		}else if($attribute_ids==""){
			$sku_query=tep_db_query("select products_sku from ".TABLE_PRODUCTS." where products_id='".(int)$products_id."'");
		   if(tep_db_num_rows($sku_query)>0){	
				$sku=tep_db_fetch_array($sku_query);
				$sku_ids=$sku['products_sku'];	
			}	
		}
		return $sku_ids;		
	}	
	
	// it return the new location if needed it can update the location
	function tep_template_box_align($template_id,$new_location,$column,$mode="add",$change_column=false){
		$check_query=tep_db_query("select location from " . TABLE_INFOBOX_CONFIGURATION . " where location=" . (int)$new_location . " and display_in_column='" . tep_db_input($column) . "' and template_id='". tep_db_input($template_id) ."'");
		if ($mode=="add"){
			if (tep_db_num_rows($check_query)>0){
				tep_db_query("UPDATE ". TABLE_INFOBOX_CONFIGURATION . " set location=location+1 where location>=" . $new_location . " and display_in_column='" . tep_db_input($column) . "' and template_id='". tep_db_input($template_id) ."'");
			} else if ($change_column){
				$max_query=tep_db_query("select max(location) as new_location from " . TABLE_INFOBOX_CONFIGURATION . " where display_in_column='" . tep_db_input($column) . "' and template_id='".tep_db_input($template_id) ."'");
				$max_result=tep_db_fetch_array($max_query);
				$new_location=(int)$max_result["new_location"];
				$new_location++;
			}
			return $new_location;
		} else {
			if (tep_db_num_rows($check_query)>0){
				tep_db_query("UPDATE ". TABLE_INFOBOX_CONFIGURATION . " set location=location-1 where location>" . $new_location . " and display_in_column='" . tep_db_input($column) . "' and template_id='" . tep_db_input($template_id) . "'");
			}
		}
	}
	function tep_template_box_realign($template_id,$column){
		$location=1;
		$box_query=tep_db_query("select infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where display_in_column='" . tep_db_input($column) ."' and template_id='" . tep_db_input($template_id) . "' and infobox_display='yes' order by location asc ");
		while($box_result=tep_db_fetch_array($box_query)){
			tep_db_query("UPDATE ". TABLE_INFOBOX_CONFIGURATION . " set location=" . $location . " where infobox_id='" . $box_result["infobox_id"] ."' and template_id='". tep_db_input($template_id) ."'");
			$location++;
		}
	}
	
	// function to retrieve the last used files
	function tep_get_last_access_file(){
	    global $PHP_SELF,$selected_box,$FSESSION,$FREQUEST;  
		//print_r($HTTP_GET_VARS);
			$get_top=$FREQUEST->getvalue('top');
			if ($get_top!=''){
				$page=basename($PHP_SELF);
				$temp_params=tep_get_all_get_params();
				setcookie("store_file_name_" . $FSESSION->login_groups_id,$page.'?'.$temp_params,time()+60*60*24*30);
				
				// for quick links
				/*$query=tep_db_query("select * from " . TABLE_QUICK_LINKS. " where filename='". $page ."' and login_group_id='".$login_groups_id ."'");
				if(tep_db_num_rows($query) >0 ){
					tep_db_query("update ". TABLE_QUICK_LINKS ." set count=count+1 where filename='". $page . "' and login_group_id='" . $login_groups_id ."'" );
				} else {
					tep_db_query("insert into " . TABLE_QUICK_LINKS ." (login_group_id,params,filename,count) values('". $login_groups_id ."','". $temp_params . "','" . $page ."','1')" );
				}	*/
				
  			}
	}
		
	function tep_template_box_check($template_id){
	    $temp_query=tep_db_query("SELECT * from " . TABLE_INFOBOX_CONFIGURATION . " where template_id='" . tep_db_input($template_id) . "' and display_in_column!='left' and display_in_column='right' limit 1");
		if (tep_db_num_rows($temp_query)>0){
			tep_db_query("UPDATE " . TABLE_INFOBOX_CONFIGURATION . " set display_in_column='left' where template_id='" . tep_db_input($template_id) . "' and display_in_column!='left' and display_in_column!='right'");
		}
		$template_query=tep_db_query("SELECT count(*) as total_location,count(location) as total_location_sum,display_in_column from " . TABLE_INFOBOX_CONFIGURATION . " where template_id='" . tep_db_input($template_id) . "' group by display_in_column");
		
		while($template_result=tep_db_fetch_array($template_query)){
			$total_location_sum=($template_result["total_location"]*($template_result["total_location"]+1))/2;
			if ($template_result["display_in_column"]!="" && $total_location_sum!=$template_result["total_location_sum"]){
				tep_template_box_realign($template_id,$template_result["display_in_column"]);
			}
		}
	}
	  $nwords = array(    "Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven",
                     "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
                     "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
                     "Nineteen", "Twenty", 30 => "Thirty", 40 => "Forty",
                     50 => "Fifty", 60 => "Sixty", 70 => "Seventy", 80 => "Eighty",
                     90 => "Ninety" );

function int_to_words($x)
{
     global $nwords;
     if(!is_numeric($x))
     {
         $w = '#';
     }else if(fmod($x, 1) != 0)
     {
         $w = '#';
     }else{
         if($x < 0)
         {
             $w = 'minus ';
             $x = -$x;
         }else{
             $w = '';
         }
         if($x < 21)
         {
             $w .= $nwords[$x];
         }else if($x < 100)
         {
             $w .= $nwords[10 * floor($x/10)];
             $r = fmod($x, 10);
             if($r > 0)
             {
                 $w .= '-'. $nwords[$r];
             }
         } else if($x < 1000)
         {
             $w .= $nwords[floor($x/100)] .' Hundred';
             $r = fmod($x, 100);
             if($r > 0)
             {
                 $w .= ' and '. int_to_words($r);
             }
         } else if($x < 1000000)
         {
             $w .= int_to_words(floor($x/1000)) .' Thousand';
             $r = fmod($x, 1000);
             if($r > 0)
             {
                 $w .= ' ';
                 if($r < 100)
                 {
                     $w .= 'and ';
                 }
                 $w .= int_to_words($r);
             }
         } else {
             $w .= int_to_words(floor($x/1000000)) .' Million';
             $r = fmod($x, 1000000);
             if($r > 0)
             {
                 $w .= ' ';
                 if($r < 100)
                 {
                     $word .= 'and ';
                 }
                 $w .= int_to_words($r);
             }
         }
     }
     return $w; 
	}
	function tep_cfg_pull_down_encryption_type($value,$key='') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
  	   $encryp_type=array(
			array('id'=>'O','text'=>PASSWORD_osConcert),   
			array('id'=>'V','text'=>PASSWORD_VBULTIN)
  		 );
	$result=tep_draw_pull_down_menu($name,$encryp_type,$value) . '&nbsp;';
	return $result;
  }
  function  tep_get_encryption_name($type='O') { 
		$pass_type=array('O'=>PASSWORD_osConcert,'V'=>PASSWORD_VBULTIN);
		return $pass_type[$type];
  }
  
	function tep_cfg_pull_down_zone_except_countries($zone_id,$except_id, $key = '') {
		$name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

		$result.='<input type="hidden" name="' . $name . '" value="" id="' . $name . '"><br><font color="red">'.TEXT_SELECT_PAYMENT_ZONE.'</font>';
		if ($zone_id=='' || $zone_id==0) return $result;
		$result='<input type="hidden" name="' . $name . '" value="' . $except_id . '" id="' . $name . '">';
		
		$except_id="," .$except_id .",";
		$country_query = tep_db_query("select distinct co.countries_name,co.countries_id from " . TABLE_ZONES_TO_GEO_ZONES . ' gz,' . TABLE_COUNTRIES  . " co where co.countries_id=gz.zone_country_id and gz.geo_zone_id in(" . $zone_id . ") order by countries_name");
	   

		$result.='<div style="height:100px;overflow:auto;width:260px;vertical-align:top"><table border="0" width="100%" cellpadding="0" cellspacing="0" valign="top"><tr><td valign="top" class="smallText">';
		$icnt=0;
		while ($country = tep_db_fetch_array($country_query)) {
			$checked='';
			if (strpos($except_id,"," . $country['countries_id'] . ",")!==FALSE) $checked=" checked ";
			$result.='<tr><td width="5"><input class="smallCheck" type=checkbox name="check_'  . $name . '" id="check_' . $icnt . '_' . $name . '" value="' . $country['countries_id'] .'"' . $checked . ' onClick="javascript:check_selected_values(this,\'' . $name . '\');"><td class="smallText" style="line-height:8pt">' . $country['countries_name'] . '</td></tr>';
			$icnt++;
		}
		$result.='<input type="hidden" id="count_' . $name . '" value="' . ($icnt-1) . '">';
		$result.='</tr>';
		$result.='</table>';
		$result.='</div>';
		return $result;
  }
  function tep_get_zone_except_country($except_id) {

		$except_country = '';
		if ($except_id!=""){
			// Code to check the mysql query error for Defect-137
			if($except_id(strlen($except_id)-1)==',')
				$except_id=substr($except_id,0,-1);
			if($except_id[0]==',')
				$except_id=substr($except_id,1,strlen($except_id));
				
			$except_id=str_replace(",,",",",$except_id);
			$country_sql = "select countries_name from " . TABLE_COUNTRIES . " where countries_id in(" . tep_db_input($except_id) . ")";			
			$country_query = tep_db_query($country_sql);
	
			while($country_result = tep_db_fetch_array($country_query)){
				$except_country.=$country_result["countries_name"] . ",";
			}
		}		
		if($except_country=='')$except_country = TEXT_NONE;
		else $except_country=substr($except_country,0,-1);
		return $except_country;
  }
	function tep_check_module_status(&$module,$payment_zone,$except_zone_list,$except_country_list){
		global $order;
		return true;
	  if ( ($module->enabled == true) && (($except_zone_list!='' && substr($except_zone_list,0,1)!='M') || ($except_country_list!='' && substr($except_country_list,0,1)!='M'))) {
	  	$add_option='';
		if ($except_zone_list!='' && substr($except_zone_list,0,1)!='M') $add_option.=" geo_zone_id in(" . $except_zone_list . ") ";
		if ($except_country_list!='' && substr($except_country_list,0,1)!='M') {
			if ($add_option!='') $add_option.=' or ';
			$add_option.=" zone_country_id in(" . $except_country_list . ") ";
		}
		$check_query=tep_db_query("SELECT zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where  (" . $add_option. ") and zone_country_id='" . (int)$order->billing['country']['id'] . "' and (zone_id is null or zone_id = '0' or zone_id ='" . (int)$order->billing['zone_id'] ."');");
		if (tep_db_num_rows($check_query)>0){
			$module->enabled=false;
		}
	  }
      if ( ($module->enabled == true) && ($payment_zone!='') ) {
        $check_flag = false;
		$securepay_zone=trim($payment_zone)!=""?$payment_zone:'0';
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id in(" . tep_db_input($payment_zone) . ") and zone_country_id = '" . (int)$order->billing['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $module->enabled = false;
        }
      }
	}
	
	function tep_check_shipping_module_status(&$module,$payment_zone,$except_zone_list,$except_country_list){

		global $order;
		
/*		echo "modules=" . "<br>";
		print_r($module);
		echo "<br> . payzone";
		print_r($payment_zone);
		echo "<br>";
		print_r($except_zone_list);
		echo "<br>";
*/		
	  if ( ($module->enabled == true) && (($except_zone_list!='' && substr($except_zone_list,0,1)!='M') || ($except_country_list!='' && substr($except_country_list,0,1)!='M'))) {
	  	$add_option='';
		if ($except_zone_list!='' && substr($except_zone_list,0,1)!='M') $add_option.=" geo_zone_id in(" . $except_zone_list . ") ";
		if ($except_country_list!='' && substr($except_country_list,0,1)!='M') {
			if ($add_option!='') $add_option.=' or ';
			$add_option.=" zone_country_id in(" . $except_country_list . ") ";
		}
		$check_query=tep_db_query("SELECT zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where  (" . $add_option. ") and zone_country_id='" . (int)$order->delivery['country']['id'] . "' and (zone_id is null or zone_id = '0' or zone_id ='" . (int)$order->delivery['zone_id'] ."');");

		if (tep_db_num_rows($check_query)>0){
			$module->enabled=false;
		}
	  }
      if ( ($module->enabled == true) && ($payment_zone!='') ) {
        $check_flag = false;
		$securepay_zone=trim($payment_zone)!=""?$payment_zone:'0';
		$check_sql = "select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id in(" . tep_db_input($payment_zone) . ") and zone_country_id = '" . (int)$order->delivery['country']['id'] . "' order by zone_id";
        $check_query = tep_db_query($check_sql);
         while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $module->enabled = false;
        }
      }
	}
function tep_send_products_status_change_email($order_id,$status,$orders_products_id="",$comments="") {
  
  		if ((int)EMAIL_ACTIVATE!=1) return;

		if ((int)$order_id<=0)	return;
	
		if($status=="") return;
				
		if($status=='2') {
			$type='PST';
		}	
		else if($status=='3') {
			$type='PRS';	
		}	
			// get mail template details
			$details_r=array();
			$details_r['type']=$type;
			$details_r['table']=TABLE_EMAIL_MESSAGES;
			tep_get_template($details_r);
			
		//$order_total_query=tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where orders_id='" . (int)$order_id."' and class='ot_total'");
//		$order_total_result=tep_db_fetch_array($order_total_query);
	
		$add_option="";
		if($orders_products_id!="") 
			$add_option=" and op.orders_products_id='" . tep_db_input($orders_products_id) . "' ";
		$status_query=tep_db_query("Select o.orders_status, c.customers_firstname, c.customers_lastname,c.customers_email_address, c.customers_telephone, o.customers_company, o.customers_street_address, o.customers_suburb, o.customers_postcode, o.customers_city, o.customers_state, o.customers_country, o.payment_method, o.billing_name, o.date_purchased, op.products_id,op.products_name,op.products_price, op.products_quantity, op.orders_products_status from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op,  " . TABLE_CUSTOMERS . " c where o.orders_id='" . tep_db_input($order_id) . "' and o.orders_id=op.orders_id " . $add_option . " and op.products_type='P' and o.customers_id=c.customers_id ");
	   
		if(tep_db_num_rows($status_query)<=0) return;
		$product_detail='<table cellpadding="0" cellspacing="0" width="100%" border="0"><tr><td>Tickets:</td></tr></Table>';
		while($status_result=tep_db_fetch_array($status_query)) {
			if($orders_products_id!="")
				$product_status=tep_get_order_status_name($status_result['orders_products_status']);
			else
				$product_status=tep_get_order_status_name($status_result['orders_status']);	
			//$store_logo='<img src="' . tep_href_link(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/' .COMPANY_LOGO) . '" title="' . STORE_NAME . '">';
			$firstname=$status_result['customers_firstname'];
			$lastname=$status_result['customers_lastname'];	
			$telephone=$status_result['customers_telephone'];
			$company=$status_result['customers_company'];
			$street_address=$status_result['customers_street_address'];
			$suburb=$status_result['customers_suburb'];
			$city=$status_result['customers_city'];
			$postcode=$status_result['customers_postcode'];
			$state=$status_result['customers_state'];
			$country=$status_result['customers_country'];
			$email_address=$status_result['customers_email_address'];
			$date_purchased=$status_result['date_purchased'];
			$products_name=$status_result['products_name'];
			$products_price=$status_result['products_price'];
			$products_quantity=$status_result['products_quantity'];
			$payment_method=$status_result['payment_method'];
			
			$to_name=$status_result['customers_firstname'] . ' ' . $status_result['customers_lastname'];
			$email_address=$status_result['customers_email_address'];
			$product_detail.='<table cellpadding="0" cellspacing="0" width="100%" border="0"><tr><td>' . $status_result['products_quantity'] . ' x ' . $status_result['products_name'] . ' &nbsp;&nbsp;&#36;' . $status_result['products_price'] . '</td></tr></Table>'; 
			//$order_link='<a href="'.tep_catalog_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $order_id, 'SSL', false).'" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">'."Order Invoice Link".'</a>';	
			$order_link='<a href="'.tep_catalog_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $order_id, 'SSL', false).'" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#3966B1;font-weight:bold;text-decoration:none;">' . CLICK_HERE . '</a>';
			
			//$product_detail.='</Table>';
		}	
			$replace_array=array(CUST_CF=>$firstname,
								 CUST_CL=>$lastname,
								 CUST_CO=>$telephone,
								 CUST_CM=>$company,
								 CUST_CT=>$street_address,
								 CUST_CS=>$suburb,
								 CUST_CC=>$city,
								 CUST_CP=>$postcode,
								 CUST_CE=>$state,
								 CUST_CU=>$country,
								 CUST_CA=>$email_address,
								 TEXT_P_Q=>$products_quantity,
								 TEXT_PN=>$products_name,
								 TEXT_PP=>$products_price,
								 //TEXT_SP=>$store_logo,
								 ORDR_NO=>$order_id,
								 ORDR_OP=>$date_purchased,
								 ORDR_OL=>$order_link,
								 ORDR_PD=>$product_detail,
								 ORDR_PM=>$payment_method,
								 ORDR_SM=>$shipping_method,
								 TEXT_P_STATUS=>$product_status,	
								 TEXT_SN=>STORE_OWNER,
								 TEXT_SM=>STORE_NAME,
								 TEXT_SE=>STORE_OWNER_EMAIL_ADDRESS,
								 TEXT_OC=>(($comments!="")?$comments:''),
					);
                   
                    
               
			if ($details_r['html_text']!='') {
			 $replace_array=str_replace('�', " ", $replace_array);
					tep_replace_template($details_r,$replace_array);
					$details_r=str_replace('�', " ", $details_r);
					//tep_strip_html($details_r);
					$details_r['to_name']=$to_name;
					$details_r['to_email']=$email_address;
					$details_r['from_name']=STORE_OWNER;
					$details_r['from_email']=STORE_OWNER_EMAIL_ADDRESS;
					tep_send_email($details_r);				
			}
 	 }
	
 function tep_cfg_sku_length_count_option($value,$key=''){
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
 	 $sku_array=array();
 	 for($i=1;$i<=20;$i++){
 	 	$sku_array[]=array('id'=>$i,'text'=>$i);
 	 }
 	 return tep_draw_pull_down_menu($name,$sku_array,$value);
  }
  function tep_content_title_top($title,$funname='false',$hide_prev=false,$show_hidden=false,$params="",$align=false,$expand=0){


  	$heding=$title;
  	$title=str_replace(" ","_",$title);
  	$title=str_replace("\"","",$title);
	$title=str_replace("'","",$title);
  	$title=trim($title);
	/*echo '<tr>
            <td colspan="2">' . tep_draw_separator('pixel_trans.gif', '1', '15') . '</td>
          </tr>';*/
  	echo '<tr>
			<td valign="top" colspan="2">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr height="20">' ;
					echo '<td id="img_' . $title . '" class="contentTitle" style="cursor:hand;" onClick="javascript:toggle_panel(\'' . $title .'\',\''. $funname.'\',\'' . $hide_prev . '\',\''. $expand . '\');' . $params . '">' . $heding . '</td>';
				echo '</tr>';
	if($show_hidden)
			echo '<tr id="panel_' . $title . '" style="display:none">';
	else
			echo '<tr id="panel_' . $title . '">';
				
				if($align)
					echo '<td colspan="2">';
				else
					echo'<td>';
					
				echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
			';
  }
  function tep_content_title_bottom(){
  	echo '
					</table>
				</td>
			</tr>
  			</table>
			</td>
		  </tr>';
  }
  function tep_content_title1_top($title,$funname='false',$hide_prev=false,$show_hidden=false,$params="",$align=false,$expand=0,$left_pane=0){
  		$heding=$title;
  	$title=str_replace(" ","_",$title);
  	$title=str_replace("\"","",$title);
	$title=str_replace("'","",$title);
  	$title=trim($title);
	/*echo '<tr>
            <td colspan="2">' . tep_draw_separator('pixel_trans.gif', '1', '15') . '</td>
          </tr>';*/
  	echo '<tr>
			<td valign="top" colspan="2">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr height="20">' ;

					echo '<td class="contentTitle1" style="cursor:hand;" onClick="javascript:toggle_panel1(\'' . $title .'\',\''. $funname.'\',\'' . $hide_prev . '\',\''. $expand . '\');"' . $params . '"><span style="background:#FFFFFF;padding-right:5px;' . ($left_pane>0?"padding-left:$left_pane px":'') . '">' . $heding . '</span></td><td width="15" align="right"><a href="javascript:toggle_panel1(\'' . $title .'\',\''. $funname.'\',\'' . $hide_prev . '\',\''. $expand . '\');' . $params . '"><img src="images/template/panel_down.gif" id="img_' . $title . '" border="0"></a></td>';

				echo '</tr>
				<tr height="10">
					<td></td>
				</tr>';
	/*if($show_hidden)
			echo '<tr id="panel_' . $title .'" style="display:none">';
	else
			echo '<tr id="panel_' . $title .'">';*/
				
				if($align)
					echo '<td colspan="2">';
				else
					echo'<td>';
					
				echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td id="panel_' . $title .'">';
  }
  function tep_content_title1_bottom(){
  	echo '
			   </td></tr></table>
			   </td>
			  </tr>
  			 </table>
			</td>
		  </tr>';
  }
  // Get tax rate from tax description
  function tep_get_tax_rate_from_desc($tax_desc) {
    $tax_query = tep_db_query("select tax_rate from " . TABLE_TAX_RATES . " where tax_description = '" . tep_db_input($tax_desc) . "'");
    $tax = tep_db_fetch_array($tax_query);
    return $tax['tax_rate'];
  }
  
	function load_menu($parent,$level=1,$path=""){
		global $menu_arr,$FSESSION,$navig_list;
		$show_items=SHOP_SELL_ITEMS;
		
		$menu_query=tep_db_query("SELECT m.menu_id,md.menu_text,m.filename,params from " . TABLE_ADMIN_MENUS . " m," . TABLE_ADMIN_MENUS_DESCRIPTION . " md where m.parent_id='" . tep_db_input($parent). "' and m.menu_id=md.menu_id and '" .$show_items . "' like concat('%',menu_item_type,'%') order by m.menu_pos");
		
		if (tep_db_num_rows($menu_query)<=0) return;
		if ($path!="") $path.="_";
		$icnt=1;
		while($menu_result=tep_db_fetch_array($menu_query)){
			$menu_arr[$level][$menu_result["menu_id"]]=array("text"=>$menu_result["menu_text"],"path"=>$path . $menu_result["menu_id"],"file"=>$menu_result["filename"],"params"=>$menu_result["params"]);
			if ($GLOBALS["l" . $level]==$menu_result["menu_id"]){
				$menu_arr[$level][$menu_result["menu_id"]]["select"]=1;
			}
			if ($level>1 && $GLOBALS["l" . ($level-1)]==$parent){
				$menu_arr[$level][$menu_result["menu_id"]]["show"]=1;
			}
			if ($level>1) {
				$navig_list[substr($path,0,-1)][]=$menu_result["menu_id"];
			}
			load_menu($menu_result["menu_id"],$level+1,$path . $menu_result["menu_id"]);
			if ($level>1 && $menu_arr[$level][$menu_result["menu_id"]]["file"]!="" && $menu_arr[$level-1][$parent]["file"]==""){
				$menu_arr[$level-1][$parent]["file"]=$menu_arr[$level][$menu_result["menu_id"]]["file"];
				$menu_arr[$level-1][$parent]["params"]=$menu_arr[$level][$menu_result["menu_id"]]["params"];
				$menu_arr[$level-1][$parent]["path"]=$menu_arr[$level][$menu_result["menu_id"]]["path"];
			}
			$icnt++;
		}
		tep_db_free_result($menu_query);
   }

	function get_current_menu_path(){
		global $CURRENT_MENU,$PHP_SELF,$menu_path;
		if ($CURRENT_MENU && $CURRENT_MENU!=''){
			$menu_query=tep_db_query("select m.menu_id,m.parent_id from " . TABLE_ADMIN_MENUS . " m," . TABLE_ADMIN_MENUS_DESCRIPTION . " md where m.menu_id=md.menu_id and md.menu_text='" . tep_db_input($CURRENT_MENU) ."'");
		} else {
			$menu_query=tep_db_query("select m.menu_id,m.parent_id from " . TABLE_ADMIN_MENUS . " m," . TABLE_ADMIN_MENUS_DESCRIPTION . " md where m.menu_id=md.menu_id and m.filename='" . basename($PHP_SELF) ."'");
			//$menu_query=tep_db_query("select m.menu_id,m.parent_id from " . TABLE_ADMIN_MENUS . " m," . TABLE_ADMIN_MENUS_DESCRIPTION . " md where m.menu_id=md.menu_id and m.filename='customers.php'");
		}
		if (tep_db_num_rows($menu_query)<=0) return;
		$menu_result=tep_db_fetch_array($menu_query);
		$path=$menu_result["menu_id"];
		get_recursive_path($menu_result["parent_id"],$path);
		return $path;
	}
	function get_recursive_path($menu_id,&$path){
		if ($menu_id<=0) return;
		$path=$menu_id ."_" . $path;
		$menu_query=tep_db_query("select m.menu_id,m.parent_id from " . TABLE_ADMIN_MENUS . " m," . TABLE_ADMIN_MENUS_DESCRIPTION . " md where m.menu_id=md.menu_id and m.menu_id='" . tep_db_input($menu_id) ."'");
		if (tep_db_num_rows($menu_query)<=0) return $path;
		$menu_result=tep_db_fetch_array($menu_query);
		get_recursive_path($menu_result["parent_id"],$path);
	}
	function draw_panel_handle_start($panelname,$icon){
		$panelname1=str_replace(" ","_",strtolower($panelname));
		echo '
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" class="cell_bg_navig_l4">
						<table border="0" cellpadding="2" cellspacing="0" width="100%">
							<tr>
								<td width="18" align="right"><img src="images/template/' . $icon . '"/></td>
								<td>' . $panelname . '</td>
								<td width="10" onClick="javascript:toggle_left_panel(\'' . $panelname1 . '\');" style="cursor:pointer;cursor:hand"><img src="images/template/ico_arrow_up.gif" id="img_' . $panelname1 . '"></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr id="panel_' . $panelname1 .'">
						<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
			';
	}
	function draw_panel_handle_end(){
		echo '
						</table>
						</td>
					</tr>
				</table>
			';
	}
	function tep_get_expanded_params($params){
		
		$params_splt = preg_split('/&/',$params);
		$new_params='';
		for ($icnt=0,$n=count($params_splt);$icnt<$n;$icnt++){
			if (substr($params_splt[$icnt],0,1)=='$'){
				$new_params.=substr($params_splt[$icnt],1) . '=' .$GLOBALS[substr($params_splt[$icnt],1)] .'&';
			} else if ($params_splt[$icnt]!=''){
				$new_params.=$params_splt[$icnt] . '&';
			}
		}
		return $new_params;
	}

	function tep_check_password_strength($pwd)
	{
            $tot_average        = 0.0; 
            $pwdav_len            = 0.0;                 
            $pwdav_caps        = 0.0;                 
            $pwdav_nums        = 0.0;                                         
            $pwdav_small        = 0.0; 
            $pwdav_puncts        = 0.0;                 
            $total_char_used = 0; 
            if (strlen($pwd)>0) 
            { 
                $p_limit = 5; 
                $pwd_len = strlen($pwd); 
                $nums_cnt = 0; 
                for($i=0;$i<$pwd_len;$i++) 
                { 
                    if (is_numeric($pwd[$i])) 
                        $nums_cnt++; 
                } 
                if ($nums_cnt>0) 
                    $total_char_used += 10; 
					
                $small_cnt = 0; 
                for($i=0;$i<$pwd_len;$i++) 
                { 
                    if (ctype_lower($pwd[$i])) 
                    { 
                        $small_cnt++; 
                    } 
                } 
                if ($small_cnt>0) 
                { 
                    $total_char_used += 26; 
                } 

                $caps_cnt = 0; 
                for($i=0;$i<$pwd_len;$i++) 
                { 
                    if (ctype_upper($pwd[$i])) 
                    { 
                        $caps_cnt++; 
                    } 
                } 
                if ($caps_cnt>0) 
                { 
                    $total_char_used += 26; 
                } 

                $puncts_cnt = 0; 
                for($i=0;$i<$pwd_len;$i++) 
                { 
                    if (ctype_punct($pwd[$i])) 
                    { 
                        $puncts_cnt++; 
                    } 
                } 
                if ($puncts_cnt>0) 
                { 
                    $total_char_used += 31; 
                } 
                // calculation   
				$len_min=ENTRY_PASSWORD_MIN_LENGTH;
				$len_max=16;                                      
                if (($pwd_len>$len_min) and ($pwd_len<$len_max)) 
                { 
                    $pwdav_len += (100 / $p_limit); 
                }                                                         
                // caps 
                $tot_average += $pwdav_len; 
                if (20 <= (($caps_cnt * 100) / $pwd_len)) 
                { 
                    $pwdav_caps += (100 / $p_limit); 
                } 
                else 
                { 
                    $pwdav_caps += ($caps_cnt > 0) ? ((100 / $p_limit) - 10) :  0; 
                } 
                $tot_average += $pwdav_caps; 
                // numbers 
                if (10 <= (($nums_cnt * 100) / $pwd_len)) 
                { 
                    $pwdav_nums += (100 / $p_limit); 
                } 
                else 
                { 
                    $pwdav_nums += ($nums_cnt > 0) ? ((100 / $p_limit) - 10) :  0; 
                } 
                $tot_average += $pwdav_nums; 
                // small 
                if (30 <= (($small_cnt * 100) / $pwd_len)) 
                { 
                    $pwdav_small += (100 / $p_limit); 
                } 
                else 
                { 
                    $pwdav_small += ($small_cnt > 0) ? ((100 / $p_limit) - 10) :  0; 
                } 
                $tot_average += $pwdav_small; 
                // symbols 
                if (10 <= (($puncts_cnt * 100) / $pwd_len)) 
                { 
                    $pwdav_puncts += (100 / $p_limit); 
                } 
                else 
                { 
                    $pwdav_puncts += ($puncts_cnt > 0) ? ((100 / $p_limit) - 10) :  0; 
                } 
                 
                $tot_average += $pwdav_puncts;             

                $charSet = $total_char_used; 
            } 

            $tot_average=round($tot_average, 0); 
			if($tot_average<=40)
				return false;
			else
				return true;
				
	}
function tep_check_error($error){
	if($error=='') return false;
	
	$errors = '<div align="center" >' .
			  '<table border="0" cellspacing="0" cellpadding="1" width="100%">'	.
			  '<tr height="30"> <td class="error"></td>' . '<td class="error">' . '<img src="'.DIR_FS_ADMIN.'/images/error_info.jpg ">'.'&nbsp;&nbsp;&nbsp;' . $error . '</td>' . '<td></td>' .
			  '</tr></table></div>';
	
	return $errors;

}
// products tree structure
	
	function write_products_category_space($category_id){
	    global $FSESSION,$selected_name;
		$sql = "select parent_id,pc.categories_id,categories_name from " . TABLE_CATEGORIES . " pc," . TABLE_CATEGORIES_DESCRIPTION . " pcd where pc.categories_id=pcd.categories_id and pcd.language_id = '" . (int)$FSESSION->languages_id . "' and pc.categories_id=" . tep_db_input($category_id);
		$query = tep_db_query($sql);
		$result = tep_db_fetch_array($query);
		if($result['parent_id']>0){
			return "&nbsp;&nbsp;" . write_products_category_space($result['parent_id']);
		}else{
			return "";
			}
     }
	 
	 // Output a form pull down menu
	function tep_draw_product_select_menu($name, $values, $default = '', $parameters = '', $required = false,$initial_category_id=0,$need_all=true,$initial_parent_id=0) {
		global $selected_name;
		$field = '<select name="' . tep_output_string($name) . '"';
		if (tep_not_null($parameters)) $field .= ' ' . $parameters;		
		$field.=' class="inputNormal" onBlur="javascript:toggle_focus(this,1);" onFocus="javascript:toggle_focus(this,2);"';
		$field .= '>';
		if($need_all)
		$field .= '<option style="color:#000000" value="-1">' . TEXT_SELECT . '</option>';
		if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);
		$content="";
		tep_get_products_output_tree($initial_parent_id,$content,$default,$initial_category_id);
		$field.=$content;
		$field .= '</select>';
		if ($required == true) $field .= TEXT_FIELD_REQUIRED;
		return $field;
	}
function tep_owd_costs($type='',$value=0){
		$cost_total = 0;
		if($type=='') return;
		
		if($type=="order" && defined('MODULE_FULFILLER_ONEWORLD_COST_PER_ORDER')){
			$cost_total = MODULE_FULFILLER_ONEWORLD_COST_PER_ORDER;
		
		} else if($type=="overweight" && defined('MODULE_FULFILLER_ONEWORLD_OVERWEIGHT_HANDLING_FEE')){
			if (defined("MODULE_FULFILLER_ONEWORLD_OVERWEIGHT_THRESHOLD") && MODULE_FULFILLER_ONEWORLD_OVERWEIGHT_THRESHOLD>0){
				if($value>MODULE_FULFILLER_ONEWORLD_OVERWEIGHT_THRESHOLD) {
					$cost_total = MODULE_FULFILLER_ONEWORLD_OVERWEIGHT_HANDLING_FEE;
				}
			}
		} else if($type=='quantity' && defined('MODULE_FULFILLER_ONEWORLD_COST_PER_ITEM')) {
			$cost_total = MODULE_FULFILLER_ONEWORLD_COST_PER_ITEM*$value;
		
		} else if($type=="packing" && defined('MODULE_FULFILLER_ONEWORLD_COST_PER_PACKING') && defined('SHIPPING_MAX_WEIGHT')) {
			$cost_total = MODULE_FULFILLER_ONEWORLD_COST_PER_PACKING*(ceil($value/SHIPPING_MAX_WEIGHT));
		}
		
		return $cost_total;
	}

	function tep_get_display_weight($weight){
		return $weight . '&nbsp;' . tep_get_unit_name();
	}
	function tep_count_shipping_modules() {
		return tep_count_modules(MODULE_SHIPPING_INSTALLED);
	}	 
	function tep_cfg_skynet_zone_settings(){
		return '<a href="'.tep_href_link(FILENAME_SKYNET_ZONESETTINGS).'"><img src="images/template/img_edit.gif" alt="Edit" title="Edit" border="0"></a>';
	}   
  
	function product_prices($products_id,$return_type='content'){
		global $currencies,$cart;
		$products_price_break_sql = "select * from " . TABLE_PRODUCTS_PRICE_BREAK . " ppb where products_id = '" . tep_db_input($products_id) . "' order by quantity";
		$products_price_break_query = tep_db_query($products_price_break_sql);
		$products_price_sql = "select products_price,products_tax_class_id from " . TABLE_PRODUCTS . " p where products_id = '" . tep_db_input($products_id) . "'";
		$products_price_query = tep_db_query($products_price_sql);
		$products_price_result = tep_db_fetch_array($products_price_query);
		$return_string = "<tr><td align='center' class='dataTableContent'><b>Quantity</td><td align='left' class='dataTableContent'><b>Price</td></tr>";
		$return_string .= "<tr class='moduleRow' onClick='javascript:setQuantity(this,1)' onmouseout='javascript:rowOutEffect2(this);' onmouseover='javascript:rowOverEffect2(this);'><td align='center' class='dataTableContent'>1</td><td align='left' class='dataTableContent'>" . $currencies->format(tep_add_tax($products_price_result['products_price'],tep_get_tax_rate($products_price_result["products_tax_class_id"]))) . "</td></tr>";
		while($products_price_break_result = tep_db_fetch_array($products_price_break_query)){
			$return_string .= "<tr class='moduleRow' onClick='javascript:setQuantity(this," . $products_price_break_result['quantity'] . ")' onmouseout='javascript:rowOutEffect2(this);' onmouseover='javascript:rowOverEffect2(this);'>";
			$return_string .= "<td align='center' class='dataTableContent'>";
			$return_string .= $products_price_break_result['quantity'];
			$return_string .= "</td>";
			$return_string .= "<td align='left' class='dataTableContent'>";
			$return_string .= $currencies->format($products_price_break_result['quantity']*tep_add_tax(($products_price_result['products_price'] - $products_price_break_result['discount_per_item']),tep_get_tax_rate($products_price_result["products_tax_class_id"])));
			$return_string .= "</td>";
			$return_string .= "</tr>";
		}
		$return_string .= "<tr height='20'><td colspan='2' align='left' class='dataTableContent'></tr>";
		return $return_string;
	}
// Construct a category path to the product
// TABLES: products_to_categories
  function tep_get_product_path($products_id) {
    $cPath = '';   
    //echo "select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1";
 
    $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1");
    if (tep_db_num_rows($category_query)) {
      $category = tep_db_fetch_array($category_query);
 
      $categories = array();
      tep_get_parent_categories($categories, $category['categories_id']);
 
      $categories = array_reverse($categories);
 
      $cPath = implode('_', $categories);
 
      if (tep_not_null($cPath)) $cPath .= '_';
      $cPath .= $category['categories_id'];
    }
 
    return $cPath;
  }
  
   ############################## unique id
	function tep_create_unique_id ($orders_id, $orders_products_id,  $qty){
	// function to create a 20 character code for use in QR
	// check for database table if not found create
	tep_db_query("CREATE TABLE IF NOT EXISTS `orders_tickets` (
			`orders_tickets_id` int(11) NOT NULL AUTO_INCREMENT,
			`orders_id` int(11) NOT NULL,
			`orders_products_id` int(11) NOT NULL,
			`orders_products_id_index` int(4) NOT NULL,
			`unique_id` text (20) NOT NULL,
			PRIMARY KEY (`orders_tickets_id`)
	) ");
	 /// populate the table 
	 
	 //first check that the ticket has not already been entered - this will happen if the customer relaods the pdf
	 		     $unique_query = tep_db_query("select * from orders_tickets where orders_id='" . (int)$orders_id . "' and orders_products_id = '".(int)$orders_products_id."' and orders_products_id_index = '".(int)$qty."' LIMIT 1" );
				if (tep_db_num_rows($unique_query)>0){
					$result=tep_db_fetch_array($unique_query);
					return $result["unique_id"];
				
				 }else{
	 
	 	//for ($i=0, $n=$qty; $i<$n; $i++) {
		//make up the twenty digit id here use zero padding so
		//   order_id  order_products_id order_products_id_index random filler
		//     5            6                 3                       6
		// lay them out below for clarity = could just be dumped into the array that follows
		
		$orders_id = sprintf('%05d', $orders_id); 
		$orders_products_id = sprintf('%06d', $orders_products_id);
		$orders_products_id_index = sprintf('%03d', $qty);
		$random_filler = mt_rand(100000, 999999);
		
		//$unique_id = $orders_id.$orders_products_id.$orders_products_id_index.$random_filler;
		
		$unique_id = $qty;
	
    	$sql_data_array = array(
								'orders_id' => $orders_id,
								'orders_products_id' => $orders_products_id,
								'orders_products_id_index' => $orders_products_id_index,
								'unique_id' => $unique_id
								);
		tep_db_perform('orders_tickets', $sql_data_array);
				//	}
		return $unique_id;
		}
	}
	
	function tep_get_unique_ids ($order_id){
	     //returns an array of unique numbers
		 //query the table and get the numbers
		     $unique_query = tep_db_query("select * from orders_tickets where orders_id='" . (int)$order_id . "'" );
				if (tep_db_num_rows($unique_query)>0){
				   $response = array();
				   while($unique_result=tep_db_fetch_array($unique_query)){
						$response[]=array('products_id'=>$unique_result["orders_products_id"],'unique_id'=>$unique_result["unique_id"]);
		                                             }
					return $response;
				 }else{
				    return false;
     }
	 }
	 
	function tep_get_order_id_from_unique($unique){
	$query=tep_db_query("SELECT orders_id from orders_tickets where unique_id='" . tep_db_input( $unique). "'");
	$result=tep_db_fetch_array($query);
	return $result["orders_id"];
}
	############################## unique id ends
	
	
function does_table_exist($tablename, $database = false)
{

    $res = tep_db_query("
                 SHOW TABLES IN " . DB_DATABASE . " LIKE '$tablename'
                ");

    if (tep_db_num_rows($res) < 1) {
        return 0;
    } else {
        return 1;
    }
}



#### delete orders_tickets and orders_ barcode
#### pass a zero value to $input to delete ALL tickets in order


function clear_ticket_tables($input, $order = 0){
    if ($input == 0)
	{
        $sql_barcodes = $sql_tickets = " where orders_id = '" . $order. "'";
    }
	else 
	{
	    $sql_barcodes = " where products_id = '" . $input. "' and orders_id = '". $order ."'";
        $sql_tickets  = " where orders_products_id = '" . $input. "' and orders_id = '". $order ."'";
    }
    if(does_table_exist('orders_tickets')){
        tep_db_query("delete  from orders_tickets " . $sql_tickets);

    }
    if(does_table_exist('orders_barcode')){
        tep_db_query("delete  from orders_barcode " . $sql_barcodes);

    }

}
  function tep_process_stock_global_notification_emails($product = array(), $products_id, $language_directory, $language_id, $audience = array(), $currencies) {

      include 'includes/languages/' . $language_directory . '/stats_low_stock_email.php';

      $num = 0;

      $message = '';

      reset($product);
      while (list($id, $item) = each ($product)) {

        $products_model = '';
        if (tep_not_null($item['products_model']))
          $products_model = '(' . $item['products_model'] . ')';

        $products_image = '';
        if (tep_not_null($item['products_image']) && EMAIL_USE_HTML == 'true')
          $products_image = "\n\n" . '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . 'product_info.php?products_id=' . $id . '&language=' . DEFAULT_LANGUAGE . '">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $item['products_image'], '', '', '180') . '</a>';

        if( $item['status'] == 1 ) {
          $products_price = (EMAIL_USE_HTML == 'true' ? '<del style="font-weight: normal; font-size: 15px; color: #646363;">' . $currencies->display_price( $item['products_price'], tep_get_tax_rate( $item['products_tax_class_id'] ) ) . '</del>&nbsp;' : '');
          $products_price .= '<span style="font-size: 20px; font-weight: normal; color: #F1300E;">' . $currencies->display_price( $item['specials_new_products_price'], tep_get_tax_rate( $item['products_tax_class_id'] ) ) . '</span>';
        } else {
          $products_price = '<span style="font-size: 20px; font-weight: normal; color: #464444;">' . $currencies->display_price( $item['products_price'], tep_get_tax_rate( $item['products_tax_class_id'] ) ) . '</span>';
        }

        $product_desc_query = tep_db_query("select products_name from products_description where products_id = '" . (int)$id . "' and language_id = '" . (int)$language_id . "'");
        $product_desc = tep_db_fetch_array($product_desc_query);

        $message .= '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . 'product_info.php?products_id=' . $id . '&language=' . DEFAULT_LANGUAGE . '" style="text-decoration: none;"><span style="font-size: 16px; font-weight: normal; color: #0000BF;">' . $product_desc['products_name'] . '</span></a>' . $HTML_NOTIFICATION3 . $products_price . '<sup><span style="font-size: 11px; color: #646363;"> (' . DEFAULT_CURRENCY . ')</span></sup>' . $HTML_NOTIFICATION4 . '<span style="color: #646363; font-size: 11px;">' . $products_model . '</span>' . $products_image . "\n\n";

      }

      $message .= $HTML_NOTIFICATION5a . STORE_NAME . $HTML_NOTIFICATION6a . $HTML_NOTIFICATION7a . '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . 'index.php?language=' . DEFAULT_LANGUAGE . '">' . STORE_NAME . '</a>' . $HTML_NOTIFICATION8a . STORE_NAME . $HTML_NOTIFICATION9;

      reset($audience);
      while (list($key, $customers) = each ($audience)) {

        $num++;

        $mimemessage = new email(array('X-Mailer: osCommerce'));

        $message_string = '<span style="font-family: Arial, Tahoma, Verdana, sans-serif; font-size: 14px;">';
        $message_string .= $HTML_NOTIFICATION1 . $customers['firstname'] . $HTML_NOTIFICATION2 . $message;
        $message_string .= '</span>';

        $text = strip_tags($message_string);
        if (EMAIL_USE_HTML == 'true') {
          $mimemessage->add_html($message_string, $text);
        } else {
          $mimemessage->add_text($text);
        }
        $mimemessage->build_message();
        $mimemessage->send($customers['firstname'] . ' ' . $customers['lastname'], $customers['email_address'], STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $EMAIL_SUBJECT . $EMAIL_SUBJECT1 . $EMAIL_SUBJECT2a . STORE_NAME . $EMAIL_SUBJECT5);

      }

      return $num;
    }

    // process the product notification e-mails
    function tep_process_stock_product_notification_emails($product = array(), $products_id, $language_directory, $language_id, $audience = array(), $currencies) {

      include 'includes/languages/' . $language_directory . '/stats_low_stock_email.php';

      $num = 0;

      $products_model = '';
      if (tep_not_null($product['products_model']))
        $products_model = '(' . $product['products_model'] . ')';

      $products_image = '';
      if (tep_not_null($product['products_image']) && EMAIL_USE_HTML == 'true')
        $products_image = "\n\n" . '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . 'product_info.php?products_id=' . $products_id . '&language=' . DEFAULT_LANGUAGE . '">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $product['products_image'], '', '', '180') . '</a>';

      if( $product['status'] == 1 ) {
        $products_price = (EMAIL_USE_HTML == 'true' ? '<del style="font-weight: normal; font-size: 15px; color: #646363;">' . $currencies->display_price( $product['products_price'], tep_get_tax_rate( $product['products_tax_class_id'] ) ) . '</del>&nbsp;' : '');
        $products_price .= '<span style="font-size: 20px; font-weight: normal; color: #F1300E;">' . $currencies->display_price( $product['specials_new_products_price'], tep_get_tax_rate( $product['products_tax_class_id'] ) ) . '</span>';
      } else {
        $products_price = '<span style="font-size: 20px; font-weight: normal; color: #464444;">' . $currencies->display_price( $product['products_price'], tep_get_tax_rate( $product['products_tax_class_id'] ) ) . '</span>';
      }

      $product_desc_query = tep_db_query("select products_name from products_description where products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
      $product_desc = tep_db_fetch_array($product_desc_query);

      reset($audience);
      while (list($key, $customers) = each ($audience)) {

        $num++;

        $mimemessage = new email(array('X-Mailer: osCommerce'));

        $message_subject = $EMAIL_SUBJECT . '"' . $product_desc['products_name'] . '"' . $EMAIL_SUBJECT2 . STORE_NAME . $EMAIL_SUBJECT5;
        $message_string = '<span style="font-family: Arial, Tahoma, Verdana, sans-serif; font-size: 14px;">';
        $message_string .= $HTML_NOTIFICATION1 . $customers['firstname'] . $HTML_NOTIFICATION2 . '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . 'product_info.php?products_id=' . $products_id . '&language=' . DEFAULT_LANGUAGE . '" style="text-decoration: none;"><span style="font-size: 16px; font-weight: normal; color: #0000BF;">' . $product_desc['products_name'] . '</span></a>' . $HTML_NOTIFICATION3 . $products_price . '<sup><span style="font-size: 11px; color: #646363;"> (' . DEFAULT_CURRENCY . ')</span></sup>' . $HTML_NOTIFICATION4 . '<span style="color: #646363; font-size: 11px;">' . $products_model . '</span>' . $products_image . $HTML_NOTIFICATION5 . STORE_NAME . $HTML_NOTIFICATION6 . '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . 'product_info.php?products_id=' . $products_id . '&language=' . DEFAULT_LANGUAGE . '">' . $product_desc['products_name'] . '</a>' . $HTML_NOTIFICATION7 . '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . 'product_info.php?products_id=' . $products_id . '&language=' . DEFAULT_LANGUAGE . '">' . STORE_NAME . '</a>' . $HTML_NOTIFICATION8 . STORE_NAME . $HTML_NOTIFICATION9;
        $message_string .= '</span>';

        $text = strip_tags($message_string);
        if (EMAIL_USE_HTML == 'true') {
          $mimemessage->add_html($message_string, $text);
        } else {
          $mimemessage->add_text($text);
        }
        $mimemessage->build_message();
        $mimemessage->send($customers['firstname'] . ' ' . $customers['lastname'], $customers['email_address'], STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $message_subject);

      }

      return $num;
    }

    // send notification e-mails
    function tep_send_stock_notification_emails($product = array(), $products_id, $action = 'update_product') {

      // include currencies class to show product prices in notification e-mails
      require('includes/classes/currencies.php');
      $currencies = new currencies();

      $num = 0;

      $languages = tep_get_languages();

      // get default store language to use for notification e-mails (it is not possible to know what is the customer's preferred language so we just use the default store language)
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        if ($languages[$i]['code'] == DEFAULT_LANGUAGE) {
          $language_directory = $languages[$i]['directory'];
          $language_id = $languages[$i]['id'];
        }
  	  }

      if ($action == 'update_all') { // if update all products is used

        // first we send to customers who have set global_product_notifications on. each customer gets an e-mail with all the products being updated within the one e-mail.

        $audience = array();
        $global_audience_ids = array();

        $customers_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from customers c left join customers_info ci on c.customers_id = ci.customers_info_id where ci.global_product_notifications = '1'");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_id']] = array('firstname' => $customers['customers_firstname'],
                                                        'lastname' => $customers['customers_lastname'],
                                                        'email_address' => $customers['customers_email_address']);
          $global_audience_ids[] = $customers['customers_id'];
        }

        if (sizeof($audience) > 0) {
          $num = $num + tep_process_stock_global_notification_emails($product, $products_id, $language_directory, $language_id, $audience, $currencies);
        }

        // then we send to customers who have set only specific product_notifications on. customer gets an e-mail with the product being updated, but only if they haven't already got global e-mail above, so they don't get duplicates (global product notifications over-rides product notifications)
        reset($product);
        while (list($id, $item) = each ($product)) {

          $audience = array();

          $customers_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from customers c left join products_notifications pn on c.customers_id = pn.customers_id where pn.products_id = '" . (int)$id . "'");
          while ($customers = tep_db_fetch_array($customers_query)) {
            if (!in_array($customers['customers_id'], $global_audience_ids)) {
              $audience[$customers['customers_id']] = array('firstname' => $customers['customers_firstname'],
                                                            'lastname' => $customers['customers_lastname'],
                                                            'email_address' => $customers['customers_email_address']);
            }
          }

          if (sizeof($audience) > 0) {
            $num = $num + tep_process_stock_product_notification_emails($item, $id, $language_directory, $language_id, $audience, $currencies);
          }
        }

      } else { // for single product updates

        $audience = array();

        // account customers with global_product_notifications on and account customers with a specific product_notification on (customers do not get duplicate e-mails if global_product_notifications and product_notification are both on for the customer, global_product_notifications over-rides product_notification)
        $customers_query = tep_db_query("select distinct c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from customers c left join customers_info ci on c.customers_id = ci.customers_info_id left join products_notifications pn on c.customers_id = pn.customers_id where (c.customers_id = pn.customers_id and pn.products_id = '" . (int)$products_id . "') or (ci.global_product_notifications = '1')");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_id']] = array('firstname' => $customers['customers_firstname'],
                                                        'lastname' => $customers['customers_lastname'],
                                                        'email_address' => $customers['customers_email_address']);
        }

        if (sizeof($audience) > 0) {
          $num = tep_process_stock_product_notification_emails($product, $products_id, $language_directory, $language_id, $audience, $currencies);
	    }
      }

      return $num;
    }

    // generate category path
    function tep_output_generated_low_stock_category_path($id, $from = 'category', $strong_cat = false) {
      $calculated_category_path_string = '';
      $calculated_category_path = tep_generate_category_path($id, $from);
      for ($i=0, $n=sizeof($calculated_category_path); $i<$n; $i++) {
        if ($from == 'category') $calculated_category_path[$i] = array_reverse($calculated_category_path[$i]); //new
        for ($j=0, $k=sizeof($calculated_category_path[$i]); $j<$k; $j++) {
          if ($j==$k-1 && $strong_cat) {
            $calculated_category_path_string .= '<strong>' . $calculated_category_path[$i][$j]['text'] . '</strong>&nbsp;&gt;&nbsp;';
          } else {
            $calculated_category_path_string .= $calculated_category_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
          }
        }
        $calculated_category_path_string = substr($calculated_category_path_string, 0, -16) . '<br />';
      }
      $calculated_category_path_string = substr($calculated_category_path_string, 0, -6);

      if (strlen($calculated_category_path_string) < 1) $calculated_category_path_string = TEXT_TOP;

      return $calculated_category_path_string;
    }

    // Recursively go through the categories and retreive all parent categories IDs
    if(!function_exists('tep_get_parent_categories')) {
      function tep_get_parent_categories(&$categories, $categories_id) {
        $parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id . "'");
        while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
          if ($parent_categories['parent_id'] == 0) return true;
          $categories[sizeof($categories)] = $parent_categories['parent_id'];
          if ($parent_categories['parent_id'] != $categories_id) {
            tep_get_parent_categories($categories, $parent_categories['parent_id']);
          }
        }
      }
    }

    // Get the category ids of path to the category/sub-category
    if(!function_exists('tep_get_category_path_ids')) {
      function tep_get_category_path_ids($cat_id, $include_cat_id = true) {

        $categories = array();
        tep_get_parent_categories($categories, $cat_id);

        $categories = array_reverse($categories);
        if ($include_cat_id == true)
          $categories[sizeof($categories)] = $cat_id;

        return $categories;
      }
    }

    // Output a selection field - alias function for tep_draw_low_stock_checkbox_field() and tep_draw_low_stock_radio_field()
    function tep_draw_low_stock_selection_field($name, $type, $value = '', $checked = false, $compare = '', $parameters = '') {
      $selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

      if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';

      if ( ($checked == true) || (isset($_GET[$name]) && is_string($_GET[$name]) && (($_GET[$name] == 'on') || (stripslashes($_GET[$name]) == $value))) || (isset($_POST[$name]) && is_string($_POST[$name]) && (($_POST[$name] == 'on') || (stripslashes($_POST[$name]) == $value))) || (tep_not_null($compare) && ($value == $compare)) ) {
        $selection .= ' checked="checked"';
      }

      if (tep_not_null($parameters)) $selection .= ' ' . $parameters;

      $selection .= ' />';

      return $selection;
    }

    // Output a form checkbox field
    function tep_draw_low_stock_checkbox_field($name, $value = '', $checked = false, $compare = '', $parameters = '') {
      return tep_draw_low_stock_selection_field($name, 'checkbox', $value, $checked, $compare, $parameters);
    }

    // Output a form radio field
    function tep_draw_low_stock_radio_field($name, $value = '', $checked = false, $compare = '', $parameters = '') {
      return tep_draw_low_stock_selection_field($name, 'radio', $value, $checked, $compare, $parameters);
    }

    // get the page number according to the position of the id in the array and depending on rows per page
    function tep_get_low_stock_page_number($db_query, $id, $rows, $field) {

      $page_num = 1;
      $num_items = tep_db_num_rows($db_query);

      if ($num_items > $rows) {
        $pos_num = 0;
        while ($find_item = tep_db_fetch_array($db_query)) {
          $pos_num++;
          if ($find_item[$field] == $id)
            break;
        }
        if ($pos_num > $rows) {
          $total_rows = $rows;
          while ($total_rows < $num_items) {
            $total_rows += $rows;
            $page_num++;
            if ($pos_num <= $total_rows)
              break;
          }
        }
      }

      return $page_num;
    }

    ///////////////// process actions
    // actions needed to be placed here before the alert message which appears in the header, since any quantity changes needed to be processed before the alert message appears

    $action = (isset($_GET['action_sls']) ? $_GET['action_sls'] : '');
    $update_all_complete = false;

    if (tep_not_null($action)) {

      switch ($action) {
        case 'setflag':
          if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
            if (isset($_GET['pID'])) {
              tep_set_product_status($_GET['pID'], $_GET['flag']);
            }

            if (USE_CACHE == 'true') {
              tep_reset_cache_block('categories');
              tep_reset_cache_block('also_purchased');
            }
          }

          break;

        case 'update_product_qty':
          if (isset($_GET['pID'])) $products_id = tep_db_prepare_input($_GET['pID']);
          $products_quantity = tep_db_prepare_input($_POST['update_quantity']);

          $restock_query = tep_db_query("select products_quantity, products_date_available from products where products_id = '" . (int)$products_id . "'");
          $restock = tep_db_fetch_array($restock_query);

          $product_status = '1';
          if ($products_quantity < 1) {
            $product_status = '0';
          }

          $sql_data_array = array('products_quantity' => (int)$products_quantity,
                                  'products_status' => $product_status,
                                  'products_last_modified' => 'now()');

          $update_sql_data = array();
          // setup upcoming products so they are now in stock
          if ($products_quantity > 0 && $restock['products_date_available'] >= date("Y-m-d", time())) {
            $update_sql_data = array('products_date_available' => 'null');
          }
          if (!empty($update_sql_data))
            $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform('products', $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");

          if (isset($_POST['notify_cust_' . $products_id]) && $_POST['notify_cust_' . $products_id] == 'on') {

            $num_cust = 0;
		    if ($restock['products_quantity'] < 1 && $products_quantity > 0) {  // existing products having qty updated (new stock arrived), we will only send e-mails if qty is increased to above 0 (from 0 or less) - e-mails are sent to Account Customers (i.e. Customers subscribed to Product Notifications, to get product updates.)

  			  $product_query = tep_db_query("select p.products_model, p.products_image, p.products_price, p.products_tax_class_id, s.status, s.specials_new_products_price from products p left join specials s on p.products_id = s.products_id where p.products_id = '" . (int)$products_id . "'");
		      $product = tep_db_fetch_array($product_query);

              $num_cust = tep_send_stock_notification_emails($product, $products_id);
		    }

		    if ($num_cust == 0) {
		      $notification = 'warning';
		    } else {
		      $notification = 'success';
		    }

            $messageStack->add(sprintf(TEXT_CUSTOMERS_NOTIFIED, $num_cust), $notification);
          }

          break;

        case 'confirm_update_all_product_qty':
          if (isset($_POST['update_qty_method'])) $update_method = $_POST['update_qty_method'];
          if (isset($_POST['include_upcoming'])) $include_upcoming = $_POST['include_upcoming'];
          if (isset($_POST['update_all_products'])) $update_all_products = $_POST['update_all_products'];
          if (isset($_POST['notify_cust'])) $notify_cust = $_POST['notify_cust'];
          $all_products_quantity = tep_db_prepare_input($_POST['update_all_quantities']);

          if ($current_category_id == 0) {
            $update_cat = ''; // all categories
          } else {
            $cats[] = $current_category_id;
            for($i=0;$i<count($cats);$i++) {
              $category_query = tep_db_query("select categories_id from categories where parent_id = '" . (int)$cats[$i] . "'");
              while ($category = tep_db_fetch_array($category_query)) {
                $cats[] = $category['categories_id'];
              }
              $cats = array_unique($cats);
            }
            $update_cat = "(p2c.categories_id = '" . (int)$current_category_id  . "' or c.parent_id in ('" . implode("', '", $cats) . "')) and"; // only selected category (including sub-categories)
          }

          if ($update_all_products) {
            if ($current_category_id == 0) {
              $get_low_stock = ""; // all products in all categories)
            } else {
              $get_low_stock = " where (p2c.categories_id = '" . (int)$current_category_id  . "' or c.parent_id in ('" . implode("', '", $cats) . "'))";
            }
          } else {
            $get_low_stock = " where " . $update_cat . " p.products_quantity <= '" . STOCK_REORDER_LEVEL . "'";
          }

          $restock_query = tep_db_query("select p.products_id, p.products_quantity, p.products_status, p.products_date_available from products p left join products_to_categories p2c on p.products_id = p2c.products_id left join categories c on c.categories_id = p2c.categories_id" . $get_low_stock);

          $num_products_updated = 0;
          if ($notify_cust)
            $products = array();
          while ($restock = tep_db_fetch_array($restock_query)) {

            $virtual_product = false;
            if (DOWNLOAD_ENABLED == 'true') {
              $attribute_check_query = tep_db_query("select products_attributes_id from products_attributes where products_id = '" . (int)$restock['products_id'] . "'");
              $num_attributes = tep_db_num_rows($attribute_check_query);
              if ($num_attributes > 0) {
                $attribute_check = tep_db_fetch_array($attribute_check_query);
                $virtual_check_query = tep_db_query("select * from products_attributes_download where products_attributes_id = '" . (int)$attribute_check['products_attributes_id'] . "'");
                if (tep_db_num_rows($virtual_check_query) == $num_attributes) $virtual_product = true;
              }
            }

            if (!$virtual_product) {
              $num_products_updated++;

              if ($update_method == '1') { // add update qty
                 $products_quantity = $all_products_quantity + $restock['products_quantity'];
              } else if ($update_method == '0') { // over-ride update qty
                 $products_quantity = $all_products_quantity;
              }

              $update_sql_data = array();
              if (!$include_upcoming) {
                if ($restock['products_date_available'] >= date("Y-m-d", time())) {
                  $num_products_updated--;
                  continue;
                }
              } else {
                // if include upcoming in qty update, setup upcoming products so they are now in stock
                if ($products_quantity > 0 && $restock['products_date_available'] >= date("Y-m-d", time())) {
                  $update_sql_data = array('products_date_available' => 'null');
                }
              }

              $product_status = '1';
              if ($products_quantity < 1) {
                $product_status = '0';
              }

              $sql_data_array = array('products_quantity' => (int)$products_quantity,
                                      'products_status' => $product_status,
                                      'products_last_modified' => 'now()');

              if (!empty($update_sql_data))
                $sql_data_array = array_merge($sql_data_array, $update_sql_data);

              tep_db_perform('products', $sql_data_array, 'update', "products_id = '" . (int)$restock['products_id'] . "'");

              if ($notify_cust) {

		        if ($restock['products_quantity'] < 1 && $products_quantity > 0) {  // existing products having qty updated (new stock arrived), we will only send e-mails if qty is increased to above 0 (from 0 or less) - e-mails are sent to Account Customers (i.e. Customers subscribed to Product Notifications, to get product updates.)

  			      $product_query = tep_db_query("select p.products_model, p.products_image, p.products_price, p.products_tax_class_id, s.status, s.specials_new_products_price from products p left join specials s on p.products_id = s.products_id where p.products_id = '" . (int)$restock['products_id'] . "'");
		          $product = tep_db_fetch_array($product_query);

                  $products[$restock['products_id']] = array('products_model' => $product['products_model'],
                                                             'products_image' => $product['products_image'],
                                                             'products_price' => $product['products_price'],
                                                             'products_tax_class_id' => $product['products_tax_class_id'],
                                                             'status' => $product['status'],
                                                             'specials_new_products_price' => $product['specials_new_products_price']);

		        }
              }
            }
          }

          if ($notify_cust) {

            $num_cust = 0;
            if (sizeof($products) > 0)
              $num_cust = tep_send_stock_notification_emails($products, 0, 'update_all');

		    if ($num_cust == 0) {
		      $notification = 'warning';
		    } else {
		      $notification = 'success';
		    }

            $messageStack->add(sprintf(TEXT_CUSTOMERS_NOTIFIED, $num_cust), $notification);
          }

          $update_all_complete = true;

          break;

        case 'show_all_stock':
          // if (!$FSESSION->is_registered('show_all_stock'));
            // //tep_session_register('show_all_stock');
           $show_all_stock = true;

          break;

        case 'show_low_stock':
          if (!$FSESSION->is_registered('show_all_stock'));
           // tep_session_register('show_all_stock');
          $show_all_stock = false;

          break;

       }
    }

    ///////////////// alert message, if enabled
    if (defined('ENABLE_LOW_STOCK_ALERT') && ENABLE_LOW_STOCK_ALERT == 'true') {

      require('includes/languages/english/stats_low_stock_message.php');

      $products_query = tep_db_query("select products_id, products_quantity, products_date_available from products where products_quantity <= " . STOCK_REORDER_LEVEL . " order by products_date_available desc");

      $num_low_stock = 0;
      $upcoming_product = false;
      if (tep_db_num_rows($products_query)) {
        if (STOCK_UPCOMING_INCLUDE_IN_NOTIFY == 'false') {

          while ($products = tep_db_fetch_array($products_query)) {

            if ($products['products_date_available'] == '' || $products['products_date_available'] < date("Y-m-d", time())) {
              $virtual_product = false;
              if (DOWNLOAD_ENABLED == 'true') {
                $attribute_check_query = tep_db_query("select products_attributes_id from products_attributes where products_id = '" . (int)$products['products_id'] . "'");
                $num_attributes = tep_db_num_rows($attribute_check_query);
                if ($num_attributes > 0) {
                  $attribute_check = tep_db_fetch_array($attribute_check_query);
                  $virtual_check_query = tep_db_query("select * from products_attributes_download where products_attributes_id = '" . (int)$attribute_check['products_attributes_id'] . "'");
                  if (tep_db_num_rows($virtual_check_query) == $num_attributes) $virtual_product = true;
                }
              }
              if (!$virtual_product) {
                $num_low_stock++;
                break;
              }
            }

          }

        } else {

          while ($products = tep_db_fetch_array($products_query)) {

            if ($products['products_date_available'] >= date("Y-m-d", time())) {
              $upcoming_product = true;
            }

            $virtual_product = false;
            if (DOWNLOAD_ENABLED == 'true') {
              $attribute_check_query = tep_db_query("select products_attributes_id from products_attributes where products_id = '" . (int)$products['products_id'] . "'");
              $num_attributes = tep_db_num_rows($attribute_check_query);
              if ($num_attributes > 0) {
                $attribute_check = tep_db_fetch_array($attribute_check_query);
                $virtual_check_query = tep_db_query("select * from products_attributes_download where products_attributes_id = '" . (int)$attribute_check['products_attributes_id'] . "'");
                if (tep_db_num_rows($virtual_check_query) == $num_attributes) $virtual_product = true;
              }
            }
            if (!$virtual_product || $upcoming_product) {
              $num_low_stock++;
              break;
            }

          }
        }
      }
      if ($num_low_stock > 0) {
        //$messageStack->add('<a href="' . tep_href_link('stats_low_stock.php') . '">' . sprintf(TEXT_REPORTS_STOCK_LEVEL_ALERT, STOCK_REORDER_LEVEL, (STOCK_UPCOMING_INCLUDE_IN_NOTIFY == 'true' ? ($upcoming_product == true ? '<strong>' . TEXT_REPORTS_STOCK_LEVEL_ALERT_UPCOMING . '</strong> ' : '') : '')) . '</a>', 'warning', 'stats_low_stock');
      } else {
        if ((tep_not_null($action) && ($action == 'update_product_qty' || $action == 'confirm_update_all_product_qty')) || (isset($_GET['pID']) && tep_not_null($_GET['pID']))) {
          $config_query = tep_db_query("select configuration_value from configuration where configuration_key = 'ADMIN_LOW_STOCK_EMAIL_SENT'");
          $config = tep_db_fetch_array($config_query);
          if ($config['configuration_value'] == '1') {
            tep_db_query("update configuration set configuration_value = '0' where configuration_key = 'ADMIN_LOW_STOCK_EMAIL_SENT'");
          }
        }
      }
    }
	
	function tep_category_title_top($title,$category_id,$show=false,$level=0){
	$image='panel_down.gif';
	$child_show="style='display:none'";
	if ($show) {
		$image='panel_up.gif'; 
		$child_show="";
	}
  	echo '<tr id="tr_id_'.$category_id.'">
			<td valign="top">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr height="20">
					<td>
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td class="contentTitle1" style="cursor:pointer;cursor:hand;" onClick="javascript:toggle_category_panel(' . $category_id .','. $level . ');"><span id="span_category_'.$category_id.'" style="background:#FFFFFF;padding-right:5px;' . ($level>0?"padding-left:" . ($level*5) ." px":'') . '">' . $title  . '</span></td><td width="15" align="right"><a href="javascript:toggle_category_panel(' . $category_id .','. $level . ');"><img src="images/template/' .$image .'" id="panel_' . $category_id . '_img" border="0"></a></td>
						</tr>
					</table>
					</td>
				</tr>
				<tr height="5">
					<td></td>
				</tr>
				<input type="hidden" id="panel_' . $category_id . '_pgno" value="1">
				<tr><td style="padding-left:' . (($level+1)*15) .'px">
				<div id="panel_' . $category_id .'_content"' . $child_show. '>
			';
  }
  function tep_category_title_bottom(){
  	echo '
				</div>
				</td>
			</tr>
  			</table>
			</td>
		  </tr>';
  }
  
  
function tep_get_plain_products_price($products_price){
     global $FSESSION;
	  if (!$FSESSION->is_registered('customer_id')) {
		 $customer_discount = 0;
		 if (defined("GUEST_DISCOUNT")) $customer_discount=(int)GUEST_DISCOUNT;
		 if ($customer_discount >= 0) {
			$products_price = $products_price + $products_price * abs($customer_discount) / 100;
		 } else {
			$products_price = $products_price - $products_price * abs($customer_discount) / 100;
		 }
		 return $products_price;
		 
	  } elseif ($FSESSION->is_registered('customer_id')) {
		 $query = tep_db_query("select g.customers_groups_discount from " . TABLE_CUSTOMERS_GROUPS . " g inner join  " . TABLE_CUSTOMERS  . " c on g.customers_groups_id = c.customers_groups_id and c.customers_id = '" . (int)$FSESSION->customer_id . "'");
		 $query_result = tep_db_fetch_array($query);
		 $customers_groups_discount = $query_result['customers_groups_discount'];
		 $query = tep_db_query("select customers_discount from " . TABLE_CUSTOMERS . " where customers_id =  '" . (int)$FSESSION->customer_id . "'");
		 $query_result = tep_db_fetch_array($query);
		 $customer_discount = $query_result['customers_discount'];
		 $customer_discount = $customer_discount + $customers_groups_discount;

		 if ($customer_discount >= 0) {
			$products_price = $products_price + $products_price * abs($customer_discount) / 100;
		 } else {
			$products_price = $products_price - $products_price * abs($customer_discount) / 100;
		 }
		return $products_price;
	  }
}

  function tep_get_products_spl_price($product_id,$sale_id=0,$cart_item_id='') {
  	global $cart,$FSESSION;
    $product_query = tep_db_query("select products_price, products_model,products_price_break from " . TABLE_PRODUCTS . " where products_id = '" .(int)$product_id . "'");
    if (tep_db_num_rows($product_query)) {
      $product = tep_db_fetch_array($product_query);
	  $product_price = $product['products_price'];
	  if ($product["products_price_break"]=='Y') return false;
    } else {
	  return false;
    }
	$sDate=getServerDate();

	if (!isset($customer_id)) $customer_id = 0;
    $customer_groups_id = tep_get_customers_groups_id($FSESSION->customer_id);

	$specials_query = tep_db_query("select specials_new_products_price,customers_id,customers_groups_id from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "' and status = '1'");
	

    if (tep_db_num_rows($specials_query)) {
      $special = tep_db_fetch_array($specials_query);
	  	if ($customer_id>0 && $customer_id==$special["customers_id"]){
		  $special_price = $special['specials_new_products_price'];
		} else if ($customer_groups_id>0 && $customer_groups_id==$special["customers_groups_id"]){
		  $special_price = $special['specials_new_products_price'];
		} else if ($special["customers_id"]<=0 && $special["customers_groups_id"]<=0){
		  $special_price = $special['specials_new_products_price'];
		} else {
			$special_price=false;
		}
    } else {
	  $special_price = false;
    }
	
    if(substr($product['products_model'], 0, 4) == 'GIFT') {    //Never apply a salededuction to Ian Wilson's Giftvouchers
      return $special_price;
    }

    $product_to_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
    $product_to_categories = tep_db_fetch_array($product_to_categories_query);
    $category = $product_to_categories['categories_id'];

	$option_where='';
	if ($sale_id>0) $option_where=" sale_id=" . tep_db_input($sale_id) . " and ";
	else $option_where=" sale_discount_type='S' and ";
//echo "select sale_specials_condition, sale_deduction_value, sale_deduction_type, sale_id from " . TABLE_SALEMAKER_SALES . " where " . $option_where . " ((sale_categories_all='' and sale_products_selected='') or sale_categories_all like '%," . $category . ",%' or sale_products_selected like '%," . (int)$product_id .",%') and sale_status = '1' and (sale_date_start <= '" . $sDate. "' or sale_date_start = '0000-00-00') and (sale_date_end >= '" . $sDate . "' or sale_date_end = '0000-00-00') and (sale_pricerange_from <= '" . $product_price . "' or sale_pricerange_from = '0') and (sale_pricerange_to >= '" . $product_price . "' or sale_pricerange_to = '0')";
    $sale_query = tep_db_query("select sale_specials_condition, sale_deduction_value, sale_deduction_type, sale_discount_type,sale_id from " . TABLE_SALEMAKER_SALES . " where " . $option_where . " ((sale_categories_all='' and sale_products_selected='') or sale_categories_all like '%," . tep_db_input($category) . ",%' or sale_products_selected like '%," . (int)$product_id .",%') and sale_status = '1' and (sale_date_start <= '" . $sDate. "' or sale_date_start = '0000-00-00') and (sale_date_end >= '" . tep_db_input($sDate) . "' or sale_date_end = '0000-00-00') and (sale_pricerange_from <= '" . tep_db_input($product_price) . "' or sale_pricerange_from = '0') and (sale_pricerange_to >= '" . tep_db_input($product_price) . "' or sale_pricerange_to = '0')");
    if (tep_db_num_rows($sale_query)) {
      $sale = tep_db_fetch_array($sale_query);
    } else {
	  return $special_price;
    }
    if (!$special_price) {
      $tmp_special_price = $product_price;
    } else {
      $tmp_special_price = $special_price;
    }
	if($cart_item_id!='' && $sale['sale_discount_type']=='S')
		$cart->contents[$cart_item_id]['salemaker_id']=$sale['sale_id'];
		
    switch ($sale['sale_deduction_type']) {
      case 0:
        $sale_product_price = $product_price - $sale['sale_deduction_value'];
        $sale_special_price = $tmp_special_price - $sale['sale_deduction_value'];
        break;
      case 1:
        $sale_product_price = $product_price - (($product_price * $sale['sale_deduction_value']) / 100);
        $sale_special_price = $tmp_special_price - (($tmp_special_price * $sale['sale_deduction_value']) / 100);

        break;
      case 2:
        $sale_product_price = $sale['sale_deduction_value'];
        $sale_special_price = $sale['sale_deduction_value'];
        break;
      default:
        return $special_price;
    }

    if ($sale_product_price < 0) {
      $sale_product_price = 0;
    }

    if ($sale_special_price < 0) {
      $sale_special_price = 0;
    }

    if (!$special_price) {
      return number_format($sale_product_price, 4, '.', '');
	} else {
      switch($sale['sale_specials_condition']){
        case 0:
          return number_format($sale_product_price, 4, '.', '');
          break;
        case 1:
          return number_format($special_price, 4, '.', '');
          break;
        case 2:
          return number_format($sale_special_price, 4, '.', '');
          break;
        default:
          return number_format($special_price, 4, '.', '');
      }
    }
  }
  
  
  function tep_get_salemaker_price($special_price,$product_price,$sale){
    if (!$special_price) {
      $tmp_special_price = $product_price;
    } else {
      $tmp_special_price = $special_price;
    }

    switch ($sale['sale_deduction_type']) {
      case 0:
        $sale_product_price = $product_price - $sale['sale_deduction_value'];
        $sale_special_price = $tmp_special_price - $sale['sale_deduction_value'];
        break;
      case 1:
        $sale_product_price = $product_price - (($product_price * $sale['sale_deduction_value']) / 100);
        $sale_special_price = $tmp_special_price - (($tmp_special_price * $sale['sale_deduction_value']) / 100);
        break;
      case 2:
        $sale_product_price = $sale['sale_deduction_value'];
        $sale_special_price = $sale['sale_deduction_value'];
        break;
      default:
        return $special_price;
    }

    if ($sale_product_price < 0) {
      $sale_product_price = 0;
    }

    if ($sale_special_price < 0) {
      $sale_special_price = 0;
    }

    if (!$special_price) {
      return number_format($sale_product_price, 4, '.', '');
	} else {
      switch($sale['sale_specials_condition']){
        case 0:
          return number_format($sale_product_price, 4, '.', '');
          break;
        case 1:
          return number_format($special_price, 4, '.', '');
          break;
        case 2:
          return number_format($sale_special_price, 4, '.', '');
          break;
        default:
          return number_format($special_price, 4, '.', '');
      }
    }
  }
  function tep_get_products_special_price_only($products_id){
  	global $customer_id;
  	if (!isset($customer_id)) $customer_id = 0;
	
    $customer_groups_id = tep_get_customers_groups_id($customer_id);
	$specials_query = tep_db_query("select specials_new_products_price,customers_id,customers_groups_id from " . TABLE_SPECIALS . " where products_id = '" . (int)$products_id . "' and status = '1'");

		if (tep_db_num_rows($specials_query)) {
		  $special = tep_db_fetch_array($specials_query);
			if ($customer_id>0 && $customer_id==$special["customers_id"]){
			  $special_price = $special['specials_new_products_price'];
			} else if ($customer_groups_id>0 && $customer_groups_id==$special["customers_groups_id"]){
			  $special_price = $special['specials_new_products_price'];
			} else if ($special["customers_id"]<=0 && $special["customers_groups_id"]<=0){
			  $special_price = $special['specials_new_products_price'];
			} else {
				$special_price=false;
			}
		} else {
		  $special_price = false;
		}
		return $special_price;
	}
 function get_discount_details($orders_id,$product_id){
 global $currencies;
  	$return='';   
    $product_query = tep_db_query("select discount_type,discount_text,products_price,final_price,products_tax,products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id='" . tep_db_input($orders_id) . "' and products_id='" . tep_db_input($product_id) . "'" );
	
	$product_result=tep_db_fetch_array($product_query);
	if($product_result['discount_type']=='C' || $product_result['discount_type']=='S') {
		$product_to_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . tep_db_input($product_id) . "'");
		$product_to_categories = tep_db_fetch_array($product_to_categories_query);
		$category = $product_to_categories['categories_id'];
		$fp=$product_result["final_price"];
		$tax=$product_result["products_tax"];
		$qty=$product_result["products_quantity"];
		//$tax_rate=tep_get_tax_rate($products_result['products_price'],$tax);
		$price=$product_result['products_price'];
		$product_amount=tep_add_tax(($price * $product_result['products_quantity']),$tax);
		$final_amount=tep_add_tax(($fp*$product_result['products_quantity']),$tax);
		$total=($product_amount-$final_amount);
		
			$return .=TEXT_DISCOUNT_APPLIED . '&nbsp;&nbsp;'. $product_result['discount_text']  . '<br>';
			$return .=TEXT_ORIGINAL_AMOUNT . ' &nbsp;&nbsp; '. $currencies->format($product_amount) . '<br>';
			$return .=TEXT_DISCOUNT . ' &nbsp;&nbsp; '. $currencies->format($total);
	}	
	return $return;
 	
 }
 
   function format_datetime($raw_date,$simple=false){
    if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '0000-00-00') || ($raw_date == '') || ($raw_date <= 0)) return false;
	$format='d-m-Y H:i';
	//$format=EVENTS_DATE_FORMAT.' H:i';

	if ($simple) $format=strtolower($format);
    $year = substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);
	if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
      return date($format, mktime($hour, $minute, $second, $month, $day, $year));
    } else {
      return preg_replace('/2037$/', $year, date($format, mktime($hour, $minute, $second, $month, $day, 2037)));
    }

  }
 
 function tep_convert_datetime_raw($date,$format='d-m-Y',$sep_char="/-/"){
	$date_details=array('y'=>0,'m'=>0,'d'=>0);
	$split_arr=preg_split($sep_char,$date);
	$split_format=preg_split($sep_char,$format);
	if (!(sizeof($split_arr)==sizeof($split_format) && sizeof($split_arr)>0 && sizeof($split_arr)<=3)) return "";
	
	for ($icnt=0;$icnt<sizeof($split_arr);$icnt++)
	{
		if (isset($date_details[strtolower($split_format[$icnt])]))
			$date_details[strtolower($split_format[$icnt])]=$split_arr[$icnt];
	}
	$date_details['d']=sprintf("%02d",$date_details['d']);
	$date_details['m']=sprintf("%02d",$date_details['m']);

	$year = explode(" ",$date_details['y']);
	
	return $year[0] . '-' . $date_details['m'] . '-' . $date_details['d'] . ' '. $year[1]; 
 }
  
   require('wallet_general.php');

?>
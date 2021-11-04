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


  if (STORE_SESSIONS == 'mysql') {
  	
    if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
      $SESS_LIFE = 1440;
    }
	if (defined("SESSION_TIMEOUT_FRONTEND") && (int)SESSION_TIMEOUT_FRONTEND>=0){
		//$SESS_LIFE=SESSION_TIMEOUT_FRONTEND*60;
		$SESS_LIFE=SEATPLAN_TIMEOUT;
	}
    function _sess_open($save_path, $session_name) {
      return true;
    }

    function _sess_close() {
      return true;
    }

    function _sess_read($key) {
      $value_query = tep_db_query("select value from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "' and expiry > '" . time() . "'");
      $value = tep_db_fetch_array($value_query);

      if (isset($value['value'])) {
        return $value['value'];
      }

      return false;
    }

    function _sess_write($key, $val) {
      global $SESS_LIFE;

      $expiry = time() + $SESS_LIFE;
      $value = $val;

      $check_query = tep_db_query("select count(*) as total from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
      $check = tep_db_fetch_array($check_query);

      if ($check['total'] > 0) {
        return tep_db_query("update " . TABLE_SESSIONS . " set expiry = '" . tep_db_input($expiry) . "', value = '" . tep_db_input($value) . "' where sesskey = '" . tep_db_input($key) . "'");
      } else {
        return tep_db_query("insert into " . TABLE_SESSIONS . " values ('" . tep_db_input($key) . "', '" . tep_db_input($expiry) . "', '" . tep_db_input($value) . "')");
      }
    }

    function _sess_destroy($key) {
      return tep_db_query("delete from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
    }

    function _sess_gc($maxlifetime) {
      tep_db_query("delete from " . TABLE_SESSIONS . " where expiry < '" . time() . "'");

      return true;
    }

    session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
  }

  function tep_session_start() {
     global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS;
      $sane_session_id = true;
     if (isset($HTTP_GET_VARS[tep_session_name()])) {

     if (preg_match('/^[a-zA-Z0-9]+$/', $HTTP_GET_VARS[tep_session_name()]) == false) {

      unset($HTTP_GET_VARS[tep_session_name()]);

      $sane_session_id = false;

    }

  } elseif (isset($HTTP_POST_VARS[tep_session_name()])) {

    if (preg_match('/^[a-zA-Z0-9]+$/', $HTTP_POST_VARS[tep_session_name()]) == false) {

      unset($HTTP_POST_VARS[tep_session_name()]);
  
       $sane_session_id = false;

    }

  } elseif (isset($HTTP_COOKIE_VARS[tep_session_name()])) {

    if (preg_match('/^[a-zA-Z0-9]+$/', $HTTP_COOKIE_VARS[tep_session_name()]) == false) {

      $session_data = session_get_cookie_params();

      setcookie(tep_session_name(), '', time()-42000, $session_data['path'], $session_data['domain']);
      $sane_session_id = false;

    }

  }

  if ($sane_session_id == false) 
    {
       tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
    }
	return session_start();
 }


  /*function tep_session_register($variable) {
    global $FSESSION;

    if ($FSESSION->STARTED == true) {
      return session_register($variable);
    } else {
      return false;
    }
  }

  function tep_session_is_registered($variable) {
    return session_is_registered($variable);
  }

  function tep_session_unregister($variable) {
    return session_unregister($variable);
  }*/
  
  
  function tep_session_register($variable) {
  global $NOGLOBALS_SESSION_DATA;
    if(isset($GLOBALS[$variable])) {
       $NOGLOBALS_SESSION_DATA[$variable] = $GLOBALS[$variable];
    }else {
       $NOGLOBALS_SESSION_DATA[$variable] = null;
       $GLOBALS[$variable]='';	
    }
	if (!isset($NOGLOBALS_SESSION_DATA["GLOBAL_LIST"][$variable])) $NOGLOBALS_SESSION_DATA["GLOBAL_LIST"][$variable]=1;
  }

  function tep_session_is_registered($variable) {
  global $NOGLOBALS_SESSION_DATA;
    if(isset($NOGLOBALS_SESSION_DATA[$variable])) {
      return true;
    } else {
      return false;
    }
  }

  function tep_session_unregister($variable) {
  global $NOGLOBALS_SESSION_DATA;
    unset($NOGLOBALS_SESSION_DATA[$variable]);
	unset($GLOBALS[$variable]);
    unset($GLOBALS["GLOBAL_LIST"][$variable]);
  }

  function tep_session_id($sessid = '') {
    if (!empty($sessid)) {
      return session_id($sessid);
    } else {
      return session_id();
    }
  }

  function tep_session_name($name = '') {
    if (!empty($name)) {
      return session_name($name);
    } else {
      return session_name();
    }
  }

  /*function tep_session_close() {
    if (PHP_VERSION >= '4.0.4') {
      return session_write_close();
    } elseif (function_exists('session_close')) {
      return session_close();
    }
  }*/
  // Register Globals MOD - http://oscommerce.magic-seo-url.com
  function tep_session_close() {
  global $NOGLOBALS_SESSION_DATA,$HTTP_SESSION_VARS;
    foreach($NOGLOBALS_SESSION_DATA as $key => $value) {
      if (isset($NOGLOBALS_SESSION_DATA["GLOBAL_LIST"][$key])) {
	  	$NOGLOBALS_SESSION_DATA[$key] = $GLOBALS[$key];
	  }
    }
    if (PHP_VERSION >= '4.0.4') {
      return session_write_close();
    } elseif (function_exists('session_close')) {
      return session_close();
    }
  }

  function tep_session_destroy() {
    return session_destroy();
  }

  function tep_session_save_path($path = '') {
    if (!empty($path)) {
      return session_save_path($path);
    } else {
      return session_save_path();
    }
  }

 /* function tep_session_recreate() {
    if (PHP_VERSION >= 4.1) {
	  tep_session_close(false);
      $session_backup = $_SESSION;

      unset($_COOKIE[tep_session_name()]);

      tep_session_destroy();

      if (STORE_SESSIONS == 'mysql') {
        session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
      }

      tep_session_start();

      $_SESSION = $session_backup;
      unset($session_backup);
    }
  }*/
  
 function tep_session_recreate() {
	return;
 }
 
  function tep_set_session_value($name,$value){
  global $NOGLOBALS_SESSION_DATA;
 	 	$NOGLOBALS_SESSION_DATA[$name]=$value;
 	 	$GLOBALS[$name]=$value;
 }
?>

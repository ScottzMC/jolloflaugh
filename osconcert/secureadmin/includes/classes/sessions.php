<?php

/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
	sessions.php
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

  class phpsession {
    var $name;
    var $save_path;

    var $lifetime;

    var $cookie_path;
    var $cookie_domain;
	var $started;
	var $ID;
	var $VARS;
	var $request_type;
    function __construct($store_sessions='') {

		if ($store_sessions == 'mysql') {
			session_set_save_handler(
			array(&$this, "_OPEN"), 
			array(&$this, "_CLOSE"), 
			array(&$this, "_READ"), 
			array(&$this, "_WRITE"), 
			array(&$this, "_DESTROY"), 
			array(&$this, "_GC")
			);
		} 
		$this->LIFETIME=1440;
		$this->NAME='phpsid';
		$this->SAVE_PATH="/tmp";
		$this->COOKIE_PATH="/";
		$this->COOKIE_DOMAIN="";
		$this->REQUEST_TYPE='NONSSL';
		
		$this->STARTED=false;
		ini_set('register_globals','0');
    }
	function start(){
		
		$request=&$GLOBALS["FREQUEST"];
		
		session_name($this->NAME);
		session_save_path($this->SAVE_PATH);

		if (function_exists('session_set_cookie_params')) {
			session_set_cookie_params(0, $this->COOKIE_PATH,$this->COOKIE_DOMAIN);
		} elseif (function_exists('ini_set')) {
			ini_set('session.cookie_lifetime', '0');
			ini_set('session.cookie_path', $this->COOKIE_PATH);
			ini_set('session.cookie_domain', $this->COOKIE_DOMAIN);
		}
		//check if session id present in query or post string
		$searches=array('POST');
		if ($this->REQUEST_TYPE=='SSL') $searches[]='GET';
		$searches[]='COOKIE';

		$session_id=$request->searchvalue($this->NAME,'string','',$searches);

		if ($session_id!=''){
			if (preg_match('/^[a-zA-Z0-9]+$/',$session_id)==false){
				$request->unsetvalue($this->name,'GET');
				$request->setcookie($this->name, '', time()-42000, $this->cookie_path, $this->cookie_domain);
			} else {
				session_id($session_id);
			}
		}
		session_start();
		$this->STARTED=true;
		
		$this->ID=session_id();
		if (version_compare(phpversion(), "4.1.0", "<") === true) {
			$this->VARS=&$GLOBALS["HTTP_SESSION_VARS"];
		} else {
			$this->VARS=&$_SESSION;
		}
		if (count($this->VARS)>0){
			reset($this->VARS);
			foreach($this->VARS as $key=>$value){
				if (!is_object($value) && !is_array($value)) $this->$key=$value;
			}
		}
	}
	
	function close(){
		session_write_close();
	}
	function destroy(){
		session_destroy();
	}
	function _OPEN($save_path, $session_name) {
		return true;
	}

	function _CLOSE() {
		return true;
	}
	
	function _READ($key) {
					$value_query = tep_db_query("select value from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "' and expiry > '" . time() . "'");
					$value = tep_db_fetch_array($value_query);
					if (isset($value['value'])) {
					return $value['value'];
					}else{
					return '';
					}
                        
                    return false;
                }

	// function _READ($key) {
		// $value_query = tep_db_query("select value from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "' and expiry > '" . time() . "'");
		// $value = tep_db_fetch_array($value_query);
		// if (isset($value['value'])) {
		// return $value['value'];
		// }
		
		// return false;
	// }

    function _WRITE($key, $val) {
		tep_db_connect();
		$expiry = time() + $GLOBALS["SESS_LIFE"];
		$value = $val;

		$check_query = tep_db_query("select count(*) as total from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
		$check = tep_db_fetch_array($check_query);
		
		if ($check['total'] > 0) {
			return tep_db_query("update " . TABLE_SESSIONS . " set expiry = '" . tep_db_input($expiry) . "', value = '" . tep_db_input($value) . "' where sesskey = '" . tep_db_input($key) . "'");
		} else {
			return tep_db_query("insert into " . TABLE_SESSIONS . " values ('" . tep_db_input($key) . "', '" . tep_db_input($expiry) . "', '" . tep_db_input($value) . "')");
		}
    }

	function _DESTROY($key) {
		return tep_db_query("delete from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
	}
	
	function _GC($maxlifetime) {
		tep_db_query("delete from " . TABLE_SESSIONS . " where expiry < '" . time() . "'");
	
		return true;
	}
	function set($name,$value){
		$this->VARS[$name]=$value;
		if (!is_object($value) && !is_array($value)) $this->$name=$value;
	}
	
	function get($name,$type='string',$default=''){
		if (isset($this->VARS[$name])){
			switch($type)
			{
			case 'int':
				return (int)$this->VARS[$name];
				break;
			case 'float':
				return (float)$this->VARS[$name];
				break;
			default:
				return $this->VARS[$name];
				break;
			}	
		} else {
			return $default;
		}
	}
	function remove($name){
		unset($this->VARS[$name]);
	}
	function &getobject($name,$default=false){
		if (isset($this->VARS[$name])){
			return $this->VARS[$name];
		} else {
			return $default;
		}
	}
	function &getarray($name,$default=array()){
		if (isset($this->VARS[$name])){
			return $this->VARS[$name];
		} else {
			return $default;
		}
	}
	function is_registered($name){
		if (isset($this->VARS[$name])){
			return true;
		} else {
			return false;
		}
	}
	
	function &getRef($name){
		if (isset($this->VARS[$name])){
			return $this->VARS[$name];
		} else {
			return $default;
		}
	}
  }
?>
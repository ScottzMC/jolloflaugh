<?php
/*Freeway eCommerce
http://www.openfreeway.org
Copyright (c) 2007 ZacWare
*/ // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

class phprequest{
	var $VARS;
	
	function __construct() {
		if (version_compare(phpversion(), "4.1.0", "<") === true) {
			$this->VARS["GET"]=&$GLOBALS["HTTP_GET_VARS"];
			$this->VARS["POST"]=&$GLOBALS["HTTP_POST_VARS"];
			$this->VARS["COOKIE"]=&$GLOBALS["HTTP_COOKIE_VARS"];
			$this->VARS["SERVER"]=&$GLOBALS["HTTP_SERVER_VARS"];
			$this->VARS["FILES"]=&$GLOBALS["HTTP_POST_FILES"];
		} else {
			$this->VARS["GET"]=&$_GET;
			$this->VARS["POST"]=&$_POST;
			$this->VARS["COOKIE"]=&$_COOKIE;
			$this->VARS["SERVER"]=&$_SERVER;
			$this->VARS["FILES"]=&$_FILES;
		}
	}
	function getvalue($name,$type='string',$default=''){
		return $this->_value($name,$type,"GET",$default);
	}
	function postvalue($name,$type='string',$default=''){
		return $this->_value($name,$type,"POST",$default);
	}
	function cookievalue($name,$type='string',$default=''){
		return $this->_value($name,$type,"COOKIE",$default);
	}
	function servervalue($name,$type='string',$default=''){
		if (!isset($this->VARS["SERVER"][$name])) return $default;
		return $this->VARS["SERVER"][$name];
	}
	function setvalue($name,$value,$var){
		if (!isset($this->VARS[$var])) return;
		$this->VARS[$var][$name]=$value;
	}
	function unsetvalue($name,$var){
		if (!isset($this->VARS[$var])) return;
		unset($this->VARS[$var][$name]);
	}
	function setcookie($name, $value = '', $expire = 0, $path = '/', $domain = '', $secure = 0){
		setcookie($name, $value, $expire, $path, (tep_not_null($domain) ? $domain : ''), $secure);
	}
	function _value($name,$type,$var,$default=''){
		if (!isset($this->VARS[$var][$name])) return $default;
		switch($type){
			case 'int':
				return ((int) $this->VARS[$var][$name]);
			case 'float':
				return ((float) $this->VARS[$var][$name]);
			case 'date':
			default:
				if ($this->VARS[$var][$name]=='') return $default;
				return $this->_prepareString($this->VARS[$var][$name]);
			
		}
	}
	function &getRefValue($name,$intype,$default=array()){
		if (!isset($this->VARS[$intype][$name])) return $default;
		return $this->VARS[$intype][$name];
	}
	function &getRef($intype){
		return $this->VARS[$intype];
	}
	function &getPostFile($name,$default=''){
		if (!isset($this->VARS["FILES"][$name]) || $this->VARS["FILES"][$name]['size']<=0) return $default;
		return $this->VARS["FILES"][$name];
	}
	function searchvalue($name,$type='string',$default='',$searches=array()){
		for ($icnt=0,$n=count($searches);$icnt<$n;$icnt++){
			$temp=&$this->VARS[$searches[$icnt]];
			if (isset($temp[$name])) return $this->_value($name,$type,$searches[$icnt]);
		}
		return $default;
	}
	function _prepareString($string){
		if (is_string($string)) {
		  $search_array = array('"',"'","(",")");
		  $replace_array = array('\"',"\'","","");
		  $string = str_replace($search_array,$replace_array,$string);
		  return trim($this->_sanitizeString(stripslashes($string)));
		} elseif (is_array($string)) {
		  reset($string);
		  //FOREACH
		 // while (list($key, $value) = each($string)) {
		  foreach($string as $key => $value)
		  {		  
			  
			/*$search_array = array('"',"'","(",")");
			$replace_array = array('\"',"\'","","");
			$value = str_replace($search_array,$replace_array,$value);*/
			$string[$key] = $this->_prepareString($value);
		  }
		  return $string;
		} else {
		  return $string;
		}
	}
	function _sanitizeString($string) {
		$string = preg_replace('/ +/', ' ', trim($string));
		return $string;
		//return preg_replace("/[<>]/", '_', $string);
	}
	function getPostValues($exclude_array=array()){
		$result='';
		reset($this->VARS['POST']);
		//while (list($key, $value) = each($this->VARS['POST'])) {
			foreach($this->VARS['POST'] as $key => $value)
			{
			if (!is_array($this->VARS['POST'][$key]) && !in_array($key,$exclude_array)) {
				$result.=tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
			}
		}
		return $result;
	}
	
	function getAllValues($exclude_array=array()){
		$result='';
		reset($this->VARS['GET']);
		//while (list($key, $value) = each($this->VARS['GET'])) {
			foreach($this->VARS['GET'] as $key => $value)
			{
			if (!is_array($this->VARS['GET'][$key]) && !in_array($key,$exclude_array)) {
				$result.=tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
			}
		}
		return $result;
	}
	function isExists($intype){
		if(count($this->VARS[$intype])>0)
				return true;
		else
				return false;
	}
  	
}
?>
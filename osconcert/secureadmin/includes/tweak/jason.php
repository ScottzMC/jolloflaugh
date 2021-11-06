<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
	class jason{
		var $VARS;
		var $FUNCS;
		var $jasonStr;
		var $funcStr;
		var $ajxSep;
		function __construct() {
			$this->VARS=array();
			$this->FUNCS=array();
			$this->jasonStr='';
			$this->funcStr='';
			$this->ajxSep='@sep@';
		}
		function outVARS(){
			if (count($this->VARS)>0){
				$this->jasonStr='';
				$this->_jasonString($this->VARS);
				echo '@sep@' . ajxEncrypt($this->jasonStr);
			}	
		}
		function outJS(){
				echo '<!-- Prepare Initial JS VALUES and default functions //-->' . "\n" .
					 '<script type="text/javascript">';
			if (count($this->VARS)>0){
				reset($this->VARS);
				while(list($key)=each($this->VARS)){
					$this->jasonStr='';
					$this->_jasonString($this->VARS[$key],substr($key,0,2)=="NU"?"num":"key");
					echo "var $key=" . $this->jasonStr . ";\n";
				}
			} 
			echo 'function pageLoaded(){' ."\n";
			if (count($this->FUNCS)>0){
				reset($this->FUNCS);
				//while(list($key,$value)=each($this->FUNCS)){
				foreach($this->FUNCS as $key => $value)	
				{
					echo $value .";";
				}
			}
			echo "}\n";
			echo "</script>\n<!-- JS VALUES //-->";
		}
		function _jasonString(&$array,$arr_mode="key"){
			$first=true;
			reset($array);
			$this->jasonStr.=($arr_mode=="num"?"[":"{");		
			while(list($key,)=each($array)){
				if (!$first) {
					$this->jasonStr.=",\n";
				}
				if($arr_mode!="num"){
					if(substr($key,0,2)=="NU")
						$this->jasonStr.="'".substr($key,2)."':";
					else
						$this->jasonStr.="'" . $key . "':";
				}
				$first=false;
				
				if (is_array($array[$key])){
					if (count($array[$key])>0)
						$this->_jasonString($array[$key],substr($key,0,2)=="NU"?"num":"key");
					else
						$this->jasonStr.=(substr($key,0,2)=="NU"?"[]":"{}");
				} else{
					$this->jasonStr.=$this->_formatValue($array[$key]);
				}
			}
			$this->jasonStr.=($arr_mode=="num"?"]":"}");
		}
		function _formatValue($value){
			  if(is_numeric($value)) return $value;
			  else if(is_bool($value)){
				return ($value)?'true':'false';
			  }else{
				return "'".addslashes($value)."'";
			  }		
		}
	}
?>
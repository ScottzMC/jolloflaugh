<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
	class instance{
		function __construct() {
			$instances=array();
		}
		function &getSplitResult($type){
			static $instance;
			if (!isset($instance[$type])){
				if (count($instance)==0){
					frequire('splitResults.php',RTWE);
				}
				$instance[$type] = new splitResults();
			}
			return $instance[$type];
		}
		function getTweakObject($objPath){
			static $instance;
			if (!isset($instance[$objPath])){
				$parts=explode(".",$objPath);
	
				$className=array_pop($parts);
				$filePath='';
				if (count($parts)>0) $filePath=join(DSEP,$parts) . DSEP;
	
				$fileName=$className . '.php';
	
				require_once(DIR_WS_INCLUDES . 'tweak' . DSEP . $filePath . $fileName);
				
				if (!class_exists($className)){
					echo 'Err: Unable to load the Class '  . $className;
					appExit();
				}
				$instance[$objPath]=new $className;
			}
			return $instance[$objPath];
		}
	}
?>
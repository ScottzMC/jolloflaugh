<?php
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>

<?php // Start Modules Display 
// we have the mainpage
include(DIR_WS_MODULES . "mainpage.php");


$loaded_modules=array('mainpage.php'=>1);
//and/or 
$featured_products='';
if (SHOW_MAIN_FEATURED_PRODUCTS=='true')
{
$featured_products="featured_products.php";
}
$featured_categories='';
if (SHOW_MAINPAGE_FEATURED_CATEGORIES=='true')
{
$featured_categories="featured_categories.php";
}

define('INCLUDE_MODULE_TWO','featured_categories.php');
define('INCLUDE_MODULE_THREE','featured_products.php');
define('INCLUDE_MODULE_FOUR','');//double_boxes.php
define('INCLUDE_MODULE_FIVE','');
define('INCLUDE_MODULE_SIX','');


	$include_modules=array(
	'TWO'=>$featured_categories,
	'THREE'=>$featured_products,
	//'FOUR'=>'double_boxes.php',
	'FIVE'=>'',
	'SIX'=>''
	);
	$count=array('TWO','THREE','FOUR','FIVE','SIX');
	$loaded_modules=array();
	for($icnt=0,$n=sizeof($count);$icnt<$n;$icnt++)
	{
		$module=trim(constant('INCLUDE_MODULE_' . $count[$icnt]));
		if($module !='' && file_exists(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . "/content/" . $module))

		$loaded_modules[]=$module;
	}
	
	if(sizeof($loaded_modules) <4)
	{
	foreach($include_modules as $module) 	
	{
		if(!in_array($module,$loaded_modules) && $module!='')
		{
			$loaded_modules[]=$module;
		}	
	}
	}
	$icnt=0;
	

	reset($loaded_modules);

	for($jcnt=0,$n=sizeof($loaded_modules);$jcnt<$n;$jcnt++)
	{
		$module_filename=$loaded_modules[$jcnt];
		//looks for template/content file if exists (php won't work in there)
		$content=tep_load_template_content($module_filename);
		
		
		if (file_exists(DIR_WS_LANGUAGES . $FSESSION->language . "/" . $module_filename))
		{
			require DIR_WS_LANGUAGES . $FSESSION->language . "/" . $module_filename;
		}

		if ($content!='')
		{
			
		echo '<!--mod--><div>' . $content . '</div>';
		} 
		else 
		{ 
			include(DIR_WS_MODULES . $module_filename);
		}
		$icnt++;
	}
	
	
//	include(DIR_WS_MODULES . "mainpage.php");
	
	//print_r($loaded_modules);
	//print_r($module);
	//print_r($module_filename);
	
if(SHOW_MAINPAGE_CONTACT_FORM=='true'){
include(DIR_WS_MODULES . "contact.php");
}
?>
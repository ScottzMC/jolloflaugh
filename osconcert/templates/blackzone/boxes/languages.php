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
?>
<!-- languages //-->
<?php
	if (substr(basename($PHP_SELF), 0, 8) != 'checkout') 
	{
		if(!defined('BOX_HEADING_LANGUAGES'))define('BOX_HEADING_LANGUAGES', 'Languages');
		
		if (!is_object($lng)) 
		{
			include(DIR_WS_CLASSES . 'language.php');
			$lng = new language;
		}
		
		if (getenv('HTTPS') == 'on') $connection = 'SSL';
		else $connection = 'NONSSL';
		
		$languages_string = '';
		reset($lng->catalog_languages);
		//FOREACH x
			//while (list($key, $value) = each($lng->catalog_languages)) 
			foreach($lng->catalog_languages as $key => $value)
			{
			//$languages_string .= '<li class="list-group-item"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $connection) . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/big/' . $value['image'], $value['name']) . '</a></li> ';
			
			$languages_string .= '<a style="margin:5px" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $connection) . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/big/' . $value['image'], $value['name']) . '</a> ';
			
		}
		//echo $_SESSION['language'];
		echo '<div class="card box-shadow">';	
		echo '<div class="card-header">';
		echo '<strong>';
		echo BOX_HEADING_LANGUAGES;
		echo '</strong>';
		echo '</div>';
		echo '<div class="" style="padding:2px">';
		echo $languages_string;
		echo '</div>';
		echo '</div>';
		echo '<br class="clearfloat">';
		
	}
?><!-- languages_eof //-->
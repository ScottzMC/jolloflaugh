<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('HEADING_TITLE', 'Languages');

define('TEXT_IMPORTANT','<span class="red">IMPORTANT:</span>Language Files are always named -english- by default (if your app is another language it does not matter about the name of the files...they should remain as default -english- (MONO). <br>Language files are in Front End and Backend includes/languages. To edit the current language files use <a href="shop_define_language.php">[Edit]</a><br>Do not attempt to add a language unless you have the correct language files installed at the server. Contact osConcert for assistance for Multi-Language projects.'); 

define('TABLE_HEADING_LANGUAGE_NAME', 'Language');
define('TABLE_HEADING_LANGUAGE_CODE', 'Code');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_LANGUAGE_NAME', 'Name');
define('TEXT_INFO_LANGUAGE_CODE', 'Code');
define('TEXT_INFO_LANGUAGE_IMAGE', 'Image');
define('TEXT_INFO_LANGUAGE_DIRECTORY', 'Directory');
define('TEXT_INFO_LANGUAGE_SORT_ORDER', 'Sort Order:');
define('TEXT_INFO_INSERT_INTRO', 'Please enter the new language with its related data');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this language?');
define('TEXT_INFO_HEADING_NEW_LANGUAGE', 'New Language');
define('TEXT_INFO_HEADING_EDIT_LANGUAGE', 'Edit Language');
define('TEXT_INFO_HEADING_DELETE_LANGUAGE', 'Delete Language');
define('HEADING_TITLE_SEARCH','Search');

define('ERROR_REMOVE_DEFAULT_LANGUAGE', 'Error: The default language cannot be removed. Please set another language as default, and try again.');
define('TEXT_DELETED_SUCCESSFULLY','Language Deleted Successfully');

define('TEXT_NO_LANGUAGES','No Languages found');

define('ERROR_REMOVE_DEFAULT_LANG','The default Languages cannot be removed. Please set another language as default, and try again.');
define('TEXT_RECORDS','languages');
define('INFO_LOADING_PRODUCTS','Loading...');
define('ERROR_LANGUAGE_NAME','Language name cannot be Empty');
define('ERROR_LANGUAGE_CODE','Language code cannot be Empty');
define('ERROR_IMAGE_NAME','Image name cannot be Empty');
define('ERROR_LANGUAGE_DIRECTORY_NAME','Language directory cannot be Empty');
define('TEXT_EMPTY_GROUPS','No Languages Found');
?>

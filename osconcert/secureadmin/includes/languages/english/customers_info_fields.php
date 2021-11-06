<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('TEXT_HEADING_TITLE', 'Customer Account Fields');
define('HEADING_TITLE', 'Customer Account Fields');
define('TEXT_UNIQUE_NAME', 'Unique Name ');
define('TEXT_ACTIVE','Active');
define('TEXT_SHOW_LABEL','Show Label');
define('TEXT_REQUIRED','Required');
define('TEXT_INPUT_TYPE','Input Type');
define('TEXT_DEFAULT_VALUE','Default Value');
define('TEXT_BOX_TITLE','Text Box');
define('TEXT_BOX_SIZE','Size');
define('TEXT_BOX_MIN_LENGTH','Minimum Length');
define('TEXT_BOX_MAX_LENGTH','Maximum Length');
define('TEXT_HEADING_OPTION_VALUES','Option Values List');
define('TEXT_OPTION_VALUES','Option Values');
define('TEXT_OPTION_NAME','Option Name');
define('TEXT_OPTION_VALUES','Option Value');
define('TEXT_LABEL_TEXT','Label Text');
define('TEXT_ERROR_TEXT','Error Text');
define('TEXT_AREA_SIZE','Size');
define('TEXT_NEW_INFO_FIELD','New Field');
define('TEXT_RECORDS','Records');
define('TEXT_DISPLAY_PAGE','Select Pages to Display');
define('TEXT_FRONT_END','Front End');
define('TEXT_SIGN_UP','Sign Up');
define('TEXT_ACCOUNT_EDIT','Account Edit');
define('TEXT_ADDRESS_EDIT','Address Edit');
define('TEXT_BACK_END','Backend');
define('TEXT_CREATE_ACCOUNT','Create Account');
define('TEXT_EDIT_ACCOUNT','Edit Account');
define('TEXT_LOADING_DATA','Loading Data');
define('TEXT_INPUT_TITLE','Input Rollover Text');
define('TEXT_INPUT_DESCRIPTION','Input Help Text');
define('TEXT_DELETE_INTRO','Are you sure to delete this field?');
define('TEXT_CUSTOMERS_FIELD_DELETE_SUCCESS','Customers Field Deleted Successfully');
define('TEXT_CUSTOMER_FIELD_NOT_DELETED','Customers Field Not Deleted');
define('TEXT_HEADING_GENERAL','General');
define('TEXT_AREA_TITLE','Text Area');
define('TEXT_HEADING_INPUT_INFO','Input Information');
define('DELETING_DATA','Deleting Data');
define('TEXT_SHOW','Show');
define('TEXT_ALL','All');
define('TEXT_SHOW_NOTE','Show Note');

define('ERR_ERROR_TEXT','Error Text Required');
define('ERR_UNIQUE_NAME','Unique Name Required');
define('ERR_LABEL_TEXT','Label Text Required');
define('ERR_TEXT_AREA_SIZE','Text area size Required');
define('ERR_TEXT_BOX_SIZE','Text box size Required');
define('ERR_ALPHANUMERIC_TEXT','Unique name must be a Alphanumeric value');
define('ERR_DISPLAY_PAGE','Please select at least one page');
define('ERR_TEXT_BOX_LENGTH','Textbox max. value must be greater than min. value');
define('ERR_TEXT_AREA_LENGTH','Textarea max. value must be greater than min. value');
define('ERR_TEXT_AREA_LENGTH_NUMERIC','Textarea Length must be a numeric value');
define('ERR_TEXT_BOX_LENGTH_NUMERIC','Textbox Length must be a numeric value');

define('TEXT_SORTING_DATA','Sorting Data');
define('TEXT_NOT_DELETE_INTRO','System Field can not be deleted');

define('TITLE_UNIQUE_NAME','Enter the uniquename for this field which is used in writing custom code and default manipulation');
define('TITLE_ACTIVE','Show/hide this field in pages');
define('TITLE_SHOW_LABEL','Show/hide the label before the input field');
define('TITLE_REQUIRED','Optional/Forced Input');
define('TITLE_DEFAULT_VALUE','Enter the default value displayed or selected in the input');
define('TITLE_INPUT_TYPE','Select the Input type of field. For custom type please read the Note at the bottom');
define('TITLE_TEXT_SIZE','Set the textbox size for display. For textarea enter cols and rows like 50##5');
define('TITLE_MIN_LENGTH','Set the Minimum number of characters entered in the input');
define('TITLE_MAX_LENGTH','Set the maximum number of characters entered in the Input');
define('TITLE_OPTION_VALUES_LIST','List of option values for rendering Option buton and Dropdown');
define('TITLE_OPTION_NAME','Enter the option name');
define('TITLE_OPTION_VALUE','Enter the option value, if empty option name is used as value');
define('TITLE_LABEL_TEXT','Set the label text to be shown before the Input field');
define('TITLE_ROLLOVER_TEXT','Set the rollover title for the input field to help the user ');
define('TITLE_HELP_TEXT','Set the text to be shown after the Input for informative tips to users');
define('IMAGE_INSERT_OPT_VALUE','Insert');
define('IMAGE_UPDATE_OPT_VALUE','Update');
define('IMAGE_DELETE_OPT_VALUE','Delete');

define('TEXT_NOTE','<font class="main" color="#ff0000"><b>Note:</b></font>');
define('TEXT_INFO',"<strong>Locked</strong>
In the database TABLE 'customers_info_fields'. The locked setting 'N' will prevent editing of the 'required' field above.<br><strong>System</strong>
In the database TABLE 'customers_info_fields'. The system setting 'N' will prevent editing of the 'TextBox' Min/Max fields above.<br><br>
If you select 'Input Type' as 'Custom' you have to write your code to validate and prepare entered values to be stored in database. The following are the needed functions
					<ul><li>For Rendering the Field, create the function 'edit__" . htmlspecialchars("<uniquename entered>'") . " in includes/classes/customerAccount.php</li>
						<li>For retrieving the Field from db, create the function 'getdb__"  . htmlspecialchars("<uniquename entered>'") . " in includes/classes/customerAccount.php</li>
						<li>For Validation and preparing field for storage, create the function 'check__" . htmlspecialchars("<uniquename entered>'") . " in includes/classes/customerAccount.php</li>
						<li>For Validation on javascript, create the function 'validation.check__" . htmlspecialchars("<uniquename entered>'") . " in includes/javascript/customer_account.js</li>
						<li>Also without choosing 'Input Type' as custom you are still able to create any of the above functions for your needs. Our Working code adjusts to call your own defined functions</li>
					</ul>");
define('ERR_OPTION_VALUES_EMPTY','Option Values must not be empty');	
			
?>

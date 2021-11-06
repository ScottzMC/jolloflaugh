<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 


define('HEADING_TITLE', 'Infobox Display,Update and Create');
define('TABLE_HEADING_INFOBOX_FILE_NAME', 'Title');
define('TABLE_HEADING_ACTIVE', 'Activate Box?');
define('TABLE_HEADING_KEY', 'Box Heading Define: ');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_COLUMN', 'Set Column');
define('TABLE_HEADING_SORT_ORDER', 'Position');
define('TABLE_HEADING_TEMPLATE', 'Box template: ');

define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_DATE_ADDED', 'Date Added:');
define('TEXT_INFO_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_HEADING_NEW_INFOBOX', 'Create a new Infobox');
define('TEXT_INFO_INSERT_INTRO', 'An example for the<b> what\'s_new.php</b> infobox is selected');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete the infobox?');
define('TEXT_INFO_HEADING_DELETE_INFOBOX', 'Delete Infobox?');
define('TEXT_INFO_HEADING_UPDATE_INFOBOX', 'Update the Infobox ');
define('TEXT_INFO_TEMPLATE','Template');

define('IMAGE_INFOBOX_STATUS_GREEN', 'Left');
define('IMAGE_INFOBOX_STATUS_GREEN_LIGHT', 'Set left');
define('IMAGE_INFOBOX_STATUS_RED', 'Right');
define('IMAGE_INFOBOX_STATUS_RED_LIGHT', 'Set Right');

define('BOX_HEADING_BOXES', 'Boxes admin');

define('JS_BOX_HEADING', '* The \'Define Key\' must be completed.    Example BOX_HEADING_WHATS_NEW');
define('JS_INFO_BOX_HEADING', '* The \'Box heading\' must be completed.');
define('JS_BOX_LOCATION', '* You must select a column to display your Infobox\n');
define('JS_INFO_BOX_FILENAME', '* You must select a Filename for your Infobox');
define('JS_INFO_BOX_TEMPLATE','* You must select a Box Template name');
define('TEXT_FILENAME','Filename: ');
define('TEXT_TITLE','Title: ');
define('TEXT_INFOBOX_HEADING','InfoBox heading: ');
define('TEXT_BOX_TEMPLATE','Which Box Template?');
define('TEXT_DEFINE_KEY',' Define key: ');
define('TEXT_COLOR','Color: ');
define('TEXT_NEW_INFOBOX','New InfoBox');
define('IMAGE_BUTTON_NEW','New');
define('TEXT_INFOBOX_HELP_FILENAME', 'This must represent the name of the box file you have put in your <u>catalog/includes/boxes</u> folder.<br><br> It must be lowercase, but can have spaces instead of using the underscore (_)<br><br>For example:<br>Your new Infobox is named <b>new_box.php</b>, you would type in here "<b> new box</b>"<br><br>Another example would be the <b>whats_new</b> box.<br> Obviuosly it is named <b>whats_new.php </b>, you could type in here <b>what\'s new</b>');
define('TEXT_INFOBOX_HELP_HEADING', 'This is quite simply what will be displayed above the Infobox in your catalog.<br><div align="center"><img border="0" src="images/help1.gif"><br></div>');
define('TEXT_INFOBOX_HELP_DEFINE', 'An example of this would be: <b>BOX_HEADING_WHATS_NEW</b>.<br> This is then used with the main logic of your store as this: <b> define(\'BOX_HEADING_WHATS_NEW\', \'What\'s New?\');</b><br><br> If you open the file <u>catalog/includes/languages/' . $FSESSION->language . '.php</u> you can see plenty of examples, the ones that contain BOX_HEADING are no longer needed as they are now stored within the database and defined in the files <b>column_left.php</b> and <b>column_right.php</b>.<br>But there is no need to delete them!! ');

define('JS_BOX_COLOR_ERROR','* You must select a Color For Box heading');
define('TEXT_LOADING_DATA','Loading Data');
define('UPDATE_DATA','Updating Data');
define('TEXT_INFOBOX_DELETE_SUCCESS','Infobox Deleted Successfully');
define('TEXT_INFOBOX_NOT_DELETED','Infobox Not Deleted');

define('TEXT_SORTING_DATA','sorting infoboxes...');
?>
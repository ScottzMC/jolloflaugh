<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('HEADING_TITLE', 'Newsletter Manager');

define('TABLE_HEADING_NEWSLETTERS', 'Newsletters');
define('TABLE_HEADING_SIZE', 'Size');
define('TABLE_HEADING_MODULE', 'Module');
define('TABLE_HEADING_SENT', 'Sent');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');
define('TEXT_LOCKED','Locked:');
define('TEXT_NEWSLETTER_MODULE', 'Module:');
define('TEXT_NEWSLETTER_TITLE', 'Newsletter Title:');
define('TEXT_NEWSLETTER_CONTENT', 'Content:');

define('TEXT_NEW_NEWS_LETTER','New News Letter');

define('TEXT_NEWSLETTER_DATE_ADDED', 'Date Added:');
define('TEXT_NEWSLETTER_DATE_SENT', 'Date Sent:');

define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this newsletter?');

define('TEXT_PLEASE_WAIT', 'Please wait .. sending emails ..<br><br>Please do not interrupt this process!');
define('TEXT_FINISHED_SENDING_EMAILS', 'Finished sending e-mails!');

define('ERROR_NEWSLETTER_TITLE', 'Error: Newsletter title required');
define('ERROR_NEWSLETTER_MODULE', 'Error: Newsletter module required');
define('ERROR_REMOVE_UNLOCKED_NEWSLETTER', 'Error: Please lock the newsletter before deleting it.');
define('ERROR_EDIT_UNLOCKED_NEWSLETTER', 'Error: Please lock the newsletter before editing it.');
define('ERROR_SEND_UNLOCKED_NEWSLETTER', 'Error: Please lock the newsletter before sending it.');

define('HEADING_NEW_TITLE','New Newsletter');
define('TEXT_LOADING_DATA','Loading data...');
define('TEXT_RECORDS','newsletters');
define('INFO_LOADING_NEWSLETTERS','Loading Newsletters.....');
define('TEXT_NEWSLETTER_DELETE_SUCCESS','Newsletter Deleted Successfully');
define('TEXT_EMPTY_NEWSLETTER','No Newsletters Found');
define('ERROR_SELECT_PRODUCT','Please select any product');
?>
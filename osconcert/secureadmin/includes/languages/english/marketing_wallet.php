<?php
/*

  

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
http://www.openfreeway.org

Copyright 2007 ZacWare Pty. Ltd
*/
 // Check to ensure this file is included in Freeway!
defined('_FEXEC') or die(); 

define('HEADING_TITLE','Wallet');
define('TEXT_HEADING_TITLE', 'Messages of Wallet Payment');
define('TEXT_HEADING_PREVIEW','Preview Mail Template');

define('TEXT_MESSAGE_TYPE','Message Type');
define('TEXT_MESSAGE_ADD','Message Action');

// text for entry form
define('TEXT_NEW_MESSAGE','Message Configuration');
define('TEXT_MESSAGE_SUBJECT','Email Subject:');
define('TEXT_MESSAGE_REPLY_TO','Email Reply-to:');
define('TEXT_MESSAGE_TEXT','Email Text:');
define('TEXT_MESSAGE_FORMAT','Email Format:');
define('TEXT_FORMAT_TEXT','Text Email');
define('TEXT_FORMAT_HTML','HTML Email');
define('TEXT_FORMAT_BOTH','Text and HTML Email');
define('TEXT_MERGE_FIELDS','Merge Fields');

//image popup display text
define('IMAGE_ADD_MESSAGE','Add Message');
define('IMAGE_MODIFY_MESSAGE','Modify Message');
define('IMAGE_DELETE_MESSAGE','Delete Message');
define('IMAGE_TEST_TEMPLATE','Test Template');
define('IMAGE_CREATE_TEMPLATE','Create Template');
define('IMAGE_TEST_MAIL','Test Mail');

//error messge text
define('ERR_EMPTY_SUBJECT','Subject field must not be empty');
define('ERR_EMPTY_REPLY_TO','Reply to field must not be empty');
define('ERR_EMPTY_EMAIL_TEXT','Email text must not be empty');
define('ERR_INVALID_EMAIL_TEXT','Email address is invalid');
define('ERR_INVALID_REPLY_TO','Reply to address is invalid');
//text for options

define('TEXT_TITLE_P','Customer Details');
define('TEXT_TITLE_W','Wallet Payment Details');

define('TEXT_DELETE_CONFIRM','Are you sure to delete the Template?');
define('TEXT_TEST_CONFIRM','Do you want to send test mail?');
define('TEST_EMAIL_SENT_TO','Test Mail successfully sent');
define('TEXT_MAIL_FROM','<b>From</b> : %s (%s)');
define('TEXT_MAIL_TO','<b>To</b> : %s (%s)');
define('TEXT_MAIL_REPLY_TO','<b>Reply to</b> : %s');
define('TEXT_MAIL_SUBJECT','<b>Subject</b> : %s');
define('TABLE_HEADING_NAME','Message Type');
define('TEXT_WALLET_PAYMENT_MESSAGES','Messages of Wallet Payment');
define('ERROR_MESSAGE_SUBJECT','Email Subject cannot be empty');
define('ERROR_MESSAGE_REPLY','Reply to Email address cannot be empty');
define('ERROR_MESSAGE_CONTENT','Email message content cannot be empty');
define('TEXT_DELETE_PAGE_INTRO','Are you sure to delete this template?');
define('TEXT_NO_DELETE_INTRO','Are you sure to delete this template?');
define('TEXT_EMAIL_DELETE_SUCCESS','The selected template was deleted successfully');
define('TEXT_TEMPLATES_EMPTY','No Details found');
define('ERR_INVALID_REPLY_TO','Reply to email address must be valid');
define('ERR_EMPTY_REPLY_TO','Reply to email address cannot be empty');
define('ERR_EMPTY_SUBJECT','Email subject cannot be empty');

define('TEXT_TEST_MAIL_INTRO','Are you sure to send Test Mail?');
define('TEXT_NO_TEST_MAIL_INTRO','No Message found to send Test Mail');
define('TEXT_TEST_MAIL_SENT_SUCCESS','Test Mail Sent Successfully');
define('TEXT_TEST_MAIL_NOT_SENT','Test Mail Not Sent');
define('TEXT_NO_DELETE_INTRO','No Message found to Delete');
define('NO_DETAILS_FOUND','No Details Found');
?>
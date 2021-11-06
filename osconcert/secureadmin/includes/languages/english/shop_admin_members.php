<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('HEADING_TITLE', 'Admin Groups');
define('HEADING_TITLE_SEARCH', 'Search:');
define('HEADING_TITLE_GOTO', 'Go To:');
define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_NEWSDESK', 'Categories / News');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_STATUS', 'Status');
define('IMAGE_NEW_STORY', 'New Story');
define('TEXT_CATEGORIES', 'Categories:');
define('TEXT_SUBCATEGORIES', 'Subcategories:');
define('TEXT_NEWSDESK', 'News:');
define('TEXT_NEW_NEWSDESK', 'News Story in the catagory &quot;%s&quot;');
define('TABLE_HEADING_LATEST_NEWS_HEADLINE', 'Headline');
define('TEXT_NEWS_ITEMS', 'News Items:');
define('TEXT_INFO_HEADING_DELETE_ITEM', 'Delete Item');
define('TEXT_DELETE_ITEM_INTRO', 'Are you sure you want to permanently delete this item?');
define('TEXT_LATEST_NEWS_HEADLINE', 'Headline:');
define('TEXT_NEWSDESK_CONTENT', 'Content:');
define('IMAGE_NEW_NEWS_ITEM', 'New news item');
define('TEXT_NEWSDESK_STATUS', 'News Article Status:');
define('TEXT_NEWSDESK_DATE_AVAILABLE', 'Date Available:');
define('TEXT_NEWSDESK_AVAILABLE', 'In Print');
define('TEXT_NEWSDESK_NOT_AVAILABLE', 'Out of Print');
define('TEXT_NEWSDESK_URL', 'URL to outside resource:');
define('TEXT_NEWSDESK_URL_WITHOUT_HTTP', '<small>(without http://)</small>');
define('TEXT_NEWSDESK_SUMMARY', 'Summary:');
define('TEXT_NEWSDESK_CONTENT', 'Content:');
define('TEXT_NEWSDESK_HEADLINE', 'Headline:');
define('TEXT_NEWSDESK_DATE_AVAILABLE', 'Start Date:');
define('TEXT_NEWSDESK_DATE_ADDED', 'This story was submitted on:');
define('TEXT_NEWSDESK_ADDED_LINK_HEADER', "This is the link you've added:");
define('TEXT_NEWSDESK_ADDED_LINK', '<a href="http://%s" target="blank"><u>webpage</u></a>');
define('TEXT_NEWSDESK_PRICE_INFO', 'Price:');
define('TEXT_NEWSDESK_TAX_CLASS', 'Tax Class:');
define('TEXT_NEWSDESK_AVERAGE_RATING', 'Average Rating:');
define('TEXT_NEWSDESK_QUANTITY_INFO', 'Quantity:');
define('TEXT_DATE_ADDED', 'Date Added:');
define('TEXT_DATE_AVAILABLE', 'Date Available:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');
define('TEXT_NO_CHILD_CATEGORIES_OR_story', 'Please insert a new category or news story in<br>&nbsp;<br><b>%s</b>');
define('TEXT_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_EDIT_CATEGORIES_ID', 'Category ID:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Category Name:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Category Image:');
define('TEXT_EDIT_SORT_ORDER', 'Sort Order:');
define('TEXT_INFO_COPY_TO_INTRO', 'Please choose a new category you wish to copy this Article to');
define('TEXT_INFO_CURRENT_CATEGORIES', 'Current Group:');
define('TEXT_INFO_HEADING_NEW_CATEGORY', 'New Group');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Edit Group');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Delete Group');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Move Category');
define('TEXT_INFO_HEADING_DELETE_NEWS', 'Delete Member');
define('TEXT_INFO_HEADING_MOVE_NEWS', 'Move Member');
define('TEXT_INFO_HEADING_COPY_TO', 'Copy To');
define('TEXT_DELETE_CATEGORY_INTRO', 'Are you sure you want to delete this Group?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Are you sure you want to permanently delete this member?');
define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNING:</b> There are %s (child-)members still linked to this Group!');
define('TEXT_DELETE_WARNING_NEWSDESK', '<b>WARNING:</b> There are %s members still linked to this Group!');
define('TEXT_MOVE_NEWSDESK_INTRO', 'Please select which group you wish <b>%s</b> to reside in');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Please select which group you wish <b>%s</b> to reside in');
define('TEXT_MOVE', 'Move <b>%s</b> to:');
define('TEXT_NEW_CATEGORY_INTRO', 'Please fill out the following information for the new Group');
define('TEXT_CATEGORIES_NAME', 'Group Name:');
define('TEXT_CATEGORIES_IMAGE', 'Category Image:');
define('TEXT_SORT_ORDER', 'Sort Order:');
define('EMPTY_CATEGORY', 'Empty Category');
define('TEXT_HOW_TO_COPY', 'Copy Method:');
define('TEXT_COPY_AS_LINK', 'Link Article');
define('TEXT_COPY_AS_DUPLICATE', 'Duplicate Article');
define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Error: Can not link Articles in the same category.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Catalog images directory is not writeable: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Catalog images directory does not exist: ' . DIR_FS_CATALOG_IMAGES);
define('TEXT_NEWSDESK_START_DATE', 'Start Date:');
define('TEXT_DATE_FORMAT', 'Date formated as:');
define('TEXT_SHOW_STATUS', 'Status');
define('TEXT_DELETE_IMAGE', 'Delete Image(s) ?');
define('TEXT_DELETE_IMAGE_INTRO', 'BEWARE:: Deleting this/these image(s) will completely remove it/them. If you use this/these image(s) elsewhere -- I warned you !!');
define('TEXT_NEWSDESK_STICKY', 'Sticky Status');
define('TEXT_NEWSDESK_STICKY_ON', 'ON');
define('TEXT_NEWSDESK_STICKY_OFF', 'OFF');
define('TABLE_HEADING_STICKY', 'Sticky');
define('TEXT_NEWSDESK_IMAGE', 'Article Image(s):');
define('TEXT_NEWSDESK_IMAGE_ONE', 'Image one:');
define('TEXT_NEWSDESK_IMAGE_TWO', 'Image two:');
define('TEXT_NEWSDESK_IMAGE_THREE', 'Image three:');
define('TEXT_NEWSDESK_IMAGE_SUBTITLE', 'Enter image title for image one:');
define('TEXT_NEWSDESK_IMAGE_SUBTITLE_TWO', 'Enter image title for image two:');
define('TEXT_NEWSDESK_IMAGE_SUBTITLE_THREE', 'Enter image title for image three:');
define('TEXT_NEWSDESK_IMAGE_PREVIEW_ONE', 'Article Image number 1:');
define('TEXT_NEWSDESK_IMAGE_PREVIEW_TWO', 'Article Image number 2:');
define('TEXT_NEWSDESK_IMAGE_PREVIEW_THREE', 'Article Image number 3:');
define('IMAGE_ASCENDING','Ascending');
define('TEXT_ASCENDING','Ascending');
define('IMAGE_DESCENDING','Descending');
define('TEXT_DESCENDING','Descending');
define('IMAGE_SHOW_AVAILABLE','Show Aavilable');
define('TEXT_SHOW_AVAILABLE','Show Aavilable');
define('NO_CMS_FOUND','No records found');
define('TEXT_DATE_AVAILABLE','Date Available');
define('ERROR_IN_SORT_ORDER_TRY_AGAIN','Error in sorting the categories');
define('ERR_IMAGE_UPLOAD_TYPE','Image should be the .png or .jpg or .jpeg or .gif');
define('ERR_IMAGE_TWO_UPLOAD_TYPE','Image two should be the .png or .jpg or .jpeg or .gif');
define('ERR_IMAGE_THREE_UPLOAD_TYPE','Image three should be the .png or .jpg or .jpeg or .gif');
define('ERR_CATEGORY_NAME','* Group name should not be empty! \n');
define('TEXT_MAIN_CATEGORIES','Main category');
define('ERR_ARTICLE_NAME','* Article Headline should not empty for all languages\n');
define('ERR_ARTICLE_START_DATE','* start date should not empty\n');
define('TEXT_CATEGORY_DELETED_SUCCESS','Group deleted successfully...');
define('TEXT_CATEGORY_MOVED_SUCCESS','Category Moved suceesfully...');
define('TEXT_PRODUCT_INSERTED_SUCCESS','product inserted successfully...');
define('TEXT_PRODUCT_DELETED_SUCCESS','product deleted successfully...');
define('TEXT_PRODUCT_COPIED_SUCCESS','product copied successfully...');
define('TEXT_PRODUCT_MOVED_SUCCESS','product moved successfully...');
define('ERR_FIRST_NAME','* Firstname is required! \n');
define('ERR_LAST_NAME','* Lastname is required! \n');
define('ERR_EMAIL','* Email Address is required! \n');
define('ERR_INVALID_EMAIL','* Email Address format is invalid! \n');
define('ERROR_ADMIN_GROUP_LENGTH','At least the group name must have more than 5 characters');



define('HEADING_NEW_TITLE','New Group');
define('TEXT_LOADING_DATA','Loading data...');
define('INFO_LOADING_NEWS','Loading News.....');

define('TEXT_CATEGORY_HEADLINE','Headline');
define('TEXT_CATEGORY_SUMMARY','Summary');
define('TEXT_CATEGORY_CONTENT','Content');
define('TEXT_CATEGORY_NEWSIMAGES','Article Image(s)');

define('TEXT_MOVE_CATEGORY_SUCCESS','Category Moved Successfully');
define('TEXT_COPY_NEWS_SUCCESS','News Copied Successfully');
define('TEXT_MOVE_NEWS_SUCCESS','Member Moved Successfully');
define('TEXT_NEWS_DELETED_SUCCESS','Member Deleted Successfully');
define('TEXT_NEW_NEWS','New Member');
define('HEADING_NEW_NEWS','Members');
define('TEXT_MOVE_TO','Move to');
define('TEXT_NAME','Name :');
define('TEXT_EMAIL','Email Address :');
define('TEXT_GROUP_LEVEL','Group Level :');
define('TEXT_ACCOUNT','Account Created :');
define('TEXT_HIDE','Hide in Backend :');
define('TEXT_LOG_NUM','Log Number :');
define('TEXT_INFO_FIRSTNAME','Firstname :');
define('TEXT_INFO_LASTNAME','Lastname :');
define('TEXT_PASS','Password :');
define('ENTRY_PASSWORD_STRENGTH_ERROR', '&nbsp;<small><font color="#FF0000">Password strength is poor</font></small>');
define('INFO_LOADING_COUNTRY','Loading Members...');
define('TEXT_ALL','All');
define('TEXT_SHOW','Show:');
define('ERR_EXIST_EMAIL','Email Address already exists ! ');
//define('TEXT_NO_RECORDS_FOUND','NO ');
?>

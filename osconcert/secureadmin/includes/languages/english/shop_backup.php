<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('HEADING_TITLE', 'Database Backup Manager');

define('TABLE_HEADING_TITLE', 'Title');
define('TABLE_HEADING_FILE_DATE', 'Date');
define('TABLE_HEADING_FILE_SIZE', 'Size');
define('TABLE_HEADING_ACTION', 'Action');
// BOF new defines
define('TABLE_HEADING_TABLE_NAME', 'Name');
define('TABLE_HEADING_TIME_USED', 'Time used (s)');
// EOF new defines

define('TEXT_INFO_HEADING_NEW_BACKUP', 'New Backup');
define('TEXT_INFO_HEADING_RESTORE_LOCAL', 'Restore Local');
define('TEXT_INFO_NEW_BACKUP', 'Do not interrupt the backup process which might take a couple of minutes.');
define('TEXT_INFO_UNPACK', '<br><br>(after unpacking the file from the archive)');
define('TEXT_INFO_RESTORE', 'Do not interrupt the restoration process.<br><br>The larger the backup, the longer this process takes!<br><br>If possible, use the mysql client.<br><br>For example:<br><br><b>mysql -h' . DB_SERVER . ' -u' . DB_SERVER_USERNAME . ' -p ' . DB_DATABASE . ' < %s </b> %s');
define('TEXT_INFO_RESTORE_LOCAL', 'Do not interrupt the restoration process.<br><br>The larger the backup, the longer this process takes!');
// define('TEXT_INFO_RESTORE_LOCAL_RAW_FILE', 'The file uploaded must be a raw sql (text) file.');
define('TEXT_INFO_DATE', 'Date:');
define('TEXT_INFO_SIZE', 'Size:');
define('TEXT_INFO_COMPRESSION', 'Compression:');
define('TEXT_INFO_USE_GZIP', 'Use GZIP');
define('TEXT_INFO_USE_ZIP', 'Use ZIP');
define('TEXT_INFO_USE_NO_COMPRESSION', 'No Compression (Pure SQL)');
define('TEXT_INFO_DOWNLOAD_ONLY', 'Download only (do not store server side)');
define('TEXT_INFO_BEST_THROUGH_HTTPS', 'Best through a HTTPS connection');
define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this backup?');
define('TEXT_INFO_TABLE_STRUCTURE_ONLY', 'Only the table structure will be added to the backup, not the contents of the table');
define('TEXT_NO_EXTENSION', 'None');
define('TEXT_BACKUP_DIRECTORY', 'Backup Directory:');
define('TEXT_LAST_RESTORATION', 'Last Restoration:');
define('TEXT_FORGET', '(<u>forget</u>)');
// BOF new defines
define('TEXT_INFO_RESTORE_LOCAL_FILE', 'The file uploaded must be an sql file, either raw (.sql) or  compressed [gzipped (.gz) or zipped (.zip)] provided the server is capable of decompressing them.');
define('TEXT_INFO_MAX_FILE_SIZE_FOR_UPLOAD', 'The maximum size of an uploaded file that will be accepted by the server is ');
define('TEXT_INFO_EMPTY_SESSIONS_WHOSONLINE', 'Do you want to empty the tables sessions and whos_online (advisable when doing a complete restore, will also reset the date and file used for last restore)?');
define('TEXT_CONTINUE_BACKUP_IN_X_SECONDS', 'Backup will continue in <span id="countdown"></span> seconds.');
define('TEXT_CONTINUE_RESTORE_IN_X_SECONDS', 'Restore of backup in progress please wait.');
define('TEXT_REFRESH_IN_X_SECONDS', 'Next screen in maximum <span id="countdown2"></span> seconds (approximately)');
define('TEXT_PROGRESS_OF_RESTORE', 'Progress of restore: %s%% of file read.');
define('TEXT_TIME_NEEDED_FOR_RESTORE', 'Total time used for restore: %s (s)');
// EOF new defines

define('ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST', 'Error: Backup directory does not exist. , please create it and/or set location in configure.php.');
define('ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE', 'Error: Backup directory is not writeable.');
define('ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE', 'Error: Download link not acceptable.');

define('SUCCESS_LAST_RESTORE_CLEARED', 'Success: The last restoration date has been cleared.');
define('SUCCESS_DATABASE_SAVED', 'Success: The database has been saved');
define('SUCCESS_DATABASE_RESTORED', 'Success: The database has been restored.');
define('SUCCESS_BACKUP_DELETED', 'Success: The backup has been removed.');
// BOF new defines
define('SUCCESS_LOG_DELETED', 'Success: The log file has been removed.');
define('SELECT_DESELECT_ALL', '<b>Select/Deselect all tables</b>');
define('SORT_BY_NAME', 'Sort by name ascending   --> A-B-C From Top ');
define('SORT_BY_NAME_DESC', 'Sort by name descending  --> Z-X-Y From Top ');
define('SORT_BY_DATE', 'Sort by date ascending  --> Old files first ');
define('SORT_BY_DATE_DESC', 'Sort by date descending  --> Newest files first ');
define('SORT_BY_SIZE', 'Sort by size ascending  --> Large to small ');
define('SORT_BY_SIZE_DESC', 'Sort by size descending  --> Small to large ');
define('TEXT_INFO_NO_INFORMATION', 'No information available');
define('SUCCESS_GZIP_COMPRESS', 'Success: File %s succesfully compressed');
define('SUCCESS_ZIP_COMPRESS', 'Success: File %s succesfully compressed');
define('SUCCESS_GUNZIP', 'Success: File %s succesfully gunziped');
define('SUCCESS_UNZIP', 'Success: File %s succesfully unziped');
define('SUCCESS_EMAIL', 'Backup %s sent by email  OK');
define('EMAIL_MESSAGE', 'Please find attached your database backup file: %s Created with Auto Backup or Database Backup Manager.%s Many hours are spent creating contributions, please do consider making a donation to support and enable continued development.');
define('ERROR_BACKUP_NO_TABLES_SELECTED', 'Error: no tables selected for backup');
define('ERROR_RESTORE_NO_TABLES_SELECTED', 'Error: no tables selected for restore');
define('ERROR_FILE_DOES_NOT_EXIST', 'Error: File %s does not exist.');
define('ERROR_FILE_CANNOT_BE_MOVED', 'Error: Uploaded file %s cannot be moved.');
define('ERROR_FILE_EXTENSION_NOT_SQL_GZ_ZIP', 'Error: You may only upload files with the extensions sql, gz, or zip.');
define('ERROR_PROBLEM_WITH_RESTORE_FILE', 'Error: File %s does not exist or is not set.');
define('ERROR_FILE_CANNOT_BE_OPENED_FOR_READING', 'Error: Backup file %s cannot be opened for reading.');
define('ERROR_FILE_SEEKING_FILESIZE', 'Error: Problem seeking end of file %s to get file size.');
define('ERROR_FILEOFFSET_LARGER_THAN_FILESIZE', 'Error: File offset given for %s is larger than file size.');
define('ERROR_CANNOT_READ_FILE_POINTER', 'Error: File pointer for getting file offset cannot be read.');
define('ERROR_FILE_ALREADY_EXISTS', 'Error: File %s already exists.');
define('ERROR_GZIP_FILE_NOT_VALID', 'Error: File %s does not appear to be a gzip file.');
define('ERROR_ZIP_FILE_NOT_VALID', 'Error: File %s does not appear to be a zip file.');
define('ERROR_NO_GZIP_AVAILABLE', 'Error: Gzip compression both through exec and PHP is not available');
define('ERROR_NO_GUNZIP_AVAILABLE', 'Error: Gunzip both through exec and PHP is not available');
define('ERROR_NO_ZIP_AVAILABLE', 'Error: ZIP compression (through exec) is not available');
define('ERROR_NO_UNZIP_AVAILABLE', 'Error: UNZIP decompression (through exec) is not available');
define('ERROR_COMPRESSED_FILE_ALREADY_EXISTS', 'Error: A compressed file %s already exists.');
define('ERROR_UNCOMPRESSED_FILE_ALREADY_EXISTS', 'Error: An uncompressed file %s already exists.');
define('TEXT_INFO_TABLES_IN_BACKUP', '<br />' . "\n" .'<b>Tables in this backup:</b>' . "\n");
define('ERROR_FILE_NOT_REMOVEABLE', 'Error: I can not remove this file. Please set the right user permissions on: %s'); // general osC bug; only in language file of filemanager
define('ERROR_ON_GZIP', 'Error: GZIPing the database file was not successful');
define('ERROR_ON_GUNZIP', 'Error: GUNZIPing the database file was not successful');
define('ERROR_BACKUP_NO_BACKUP_FILE', 'Error: no backup file found');
define('ERROR_DATABASE_RESTORE_QUERY','Error: a database error was encountered during a query');
define('ERROR_EMAIL_PARAMS', 'Emailing Backup failure, authentication/address parameters missing.');
define('ERROR_EMAIL_FAILED','Errors occurred trying to send backup %s by email.');
define('ERROR_EMAIL_SIZE','Emailing Backup Error, the file size of %s is beyond the mail server limit.');
define('ERROR_EMAIL_MEMORY','Emailing Backup Error, Need %s memory, but only %s available.');
define('ERROR_EMAIL_AUTH_PARAM','Emailing Backup failure, authentication/address parameters missing.');
define('ERROR_EMAIL_ADD_PARAM','Emailing Backup failure, address parameters missing.');
define('ERROR_BACKUP_NO_BACKUP_FILE', 'Error: no backup file found');
define('WARNING_PEAR', 'Warning, PEAR Mail not found or insufficient to email backup, reverting to standard mail function.');
define('WARNING_BACKUP_TABLE_UNDERWAY', 'Progress of table %s.');
define('IMAGE_GZIP', 'GZip Compress');
define('IMAGE_GUNZIP', 'Uncompress');
define('IMAGE_SUBMIT', 'Submit');
define('IMAGE_ZIP', 'Zip');
define('IMAGE_UNZIP', 'Unzip');
// see http://www.php.net/manual/en/features.file-upload.errors.php
define('PHP_FILE_UPLOAD_ERROR_0', 'There is no error, the file uploaded with success.'); // UPLOAD_ERR_OK
define('PHP_FILE_UPLOAD_ERROR_1', 'The uploaded file exceeds the upload_max_filesize directive in php.ini.'); // UPLOAD_ERR_INI_SIZE
define('PHP_FILE_UPLOAD_ERROR_2', 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.'); // UPLOAD_ERR_FORM_SIZE
define('PHP_FILE_UPLOAD_ERROR_3', 'The uploaded file was only partially uploaded.'); // UPLOAD_ERR_PARTIAL
define('PHP_FILE_UPLOAD_ERROR_4', 'No file was uploaded.');  // UPLOAD_ERR_NO_FILE
// there is no error 5 in case you wondered why this is missing
define('PHP_FILE_UPLOAD_ERROR_6', 'Missing a temporary folder.'); //  UPLOAD_ERR_NO_TMP_DIR
define('PHP_FILE_UPLOAD_ERROR_7', 'Failed to write file to disk.'); // UPLOAD_ERR_CANT_WRITE
define('PHP_FILE_UPLOAD_ERROR_8', 'File upload stopped by extension.'); // UPLOAD_ERR_EXTENSION
define('PHP_FILE_UPLOAD_ERROR_UNKNOWN', 'Unknown error uploading file.');
?>
<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  
  https://www.osconcert.com
  

  Released under the GNU General Public License
*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', 'https://scottnnaghor.com'); // eg, http://localhost - should not be empty for productive servers
  define('HTTPS_SERVER', 'https://scottnnaghor.com'); // eg, https://localhost - should not be empty for productive servers
  define('HTTP_CATALOG_SERVER', 'https://scottnnaghor.com');
  define('HTTPS_CATALOG_SERVER', 'https://scottnnaghor.com');
  define('ENABLE_SSL_CATALOG', 'true'); // secure webserver for catalog module
  define('DIR_FS_DOCUMENT_ROOT', '/home/scottjpq/public_html/jollof_n_laugh/osconcert/'); // where the pages are located on the server
  define('DIR_WS_ADMIN', '/jollof_n_laugh/osconcert/secureadmin/'); // absolute path required
  define('DIR_FS_ADMIN', '/home/scottjpq/public_html/jollof_n_laugh/osconcert/secureadmin/'); // absolute pate required
  define('DIR_WS_CATALOG', '/jollof_n_laugh/osconcert/'); // absolute path required
  define('DIR_FS_CATALOG', '/home/scottjpq/public_html/jollof_n_laugh/osconcert/'); // absolute path required
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
  define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
  define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
  define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');
  //Added for Htmlarea editor. 
  define('DIR_WS_CATALOG_IMAGES_ROOT', 'images/');// folder name for catalog images 
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');// folder for saving download files 

// Added for Templating
	define('DIR_FS_CATALOG_MAINPAGE_MODULES', DIR_FS_CATALOG_MODULES . 'mainpage_modules/');
	define('DIR_WS_TEMPLATES', DIR_WS_CATALOG . 'templates/');
	define('DIR_FS_TEMPLATES', DIR_FS_CATALOG . 'templates/');

// define our database connection
  define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'scottjpq_admin');
  define('DB_SERVER_PASSWORD', 'TigerPhenix100');
  define('DB_DATABASE', 'scottjpq_ticket_event');
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'
?>
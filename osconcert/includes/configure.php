<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  
  http://www.osconcert.com
  

  Released under the GNU General Public License
*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', 'https://scottnnaghor.com'); // eg, http://localhost - should not be empty for productive servers
  define('HTTPS_SERVER', 'https://scottnnaghor.com'); // eg, https://localhost - should not be empty for productive servers
  define('ENABLE_SSL', true); // secure webserver for checkout procedure?
  define('HTTP_COOKIE_DOMAIN', 'scottnnaghor.com');
  define('HTTPS_COOKIE_DOMAIN', 'scottnnaghor.com');
  define('HTTP_COOKIE_PATH', '/jollof_n_laugh/osconcert/');
  define('HTTPS_COOKIE_PATH', '/jollof_n_laugh/osconcert/');
  define('HTTP_HOME_URL', 'https://scottnnaghor.com/jollof_n_laugh/osconcert/');
  define('DIR_WS_HTTP_CATALOG', '/jollof_n_laugh/osconcert/');
  define('DIR_WS_HTTPS_CATALOG', '/jollof_n_laugh/osconcert/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

//Added for BTS1.0
  define('DIR_WS_TEMPLATES', 'templates/');
  define('DIR_WS_CONTENT', DIR_WS_TEMPLATES . 'content/');
  define('DIR_WS_JAVASCRIPT', DIR_WS_INCLUDES . 'javascript/');
//End BTS1.0
  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  define('DIR_FS_CATALOG', '/home/scottjpq/public_html/jollof_n_laugh/osconcert/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

// define our database connection
  define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'scottjpq_admin');
  define('DB_SERVER_PASSWORD', 'TigerPhenix100');
  define('DB_DATABASE', 'scottjpq_ticket_event');
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'
?>
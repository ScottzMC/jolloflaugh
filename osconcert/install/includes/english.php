<?php

// Global entries for the <html> tag
define('HTML_PARAMS','dir="LTR" lang="en"');

// charset for web pages and emails
define('CHARSET', 'utf-8');

// page title
define('TEXT_LICENCING','Licensing and Copyright Notices');
define('TEXT_GNU','osConcert is licensed under the terms of the GNU General Public License (GPL) Version 2');
define('TEXT_COPYR','This theme is the copyright of osconcert.com');
define('TEXT_AGREE','I agree with the license conditions');
define('TEXT_INSTALL','Install');
define('TEXT_OPEN','Open Installation Guide');
define('TEXT_ABOUT','osConcert is adapted to provide <strong>Online Visual Theatre Seat Reservation</strong>');
define('TEXT_UPGRADE','Upgrade');
define('TEXT_CT','Compatibility Test');
define('TEXT_COMPAT','Installation compatability test');
define('TEXT_IO','Installation Options');
define('TEXT_DI','Database Import');
define('TEXT_TC','Test Connection');
define('TEXT_CUSTOM','Please customize the new installation with the following options:');
define('TEXT_NEW','New Installation');
define('TEXT_CONFIG','Configuration');
define('TEXT_IMPORT','Import Catalog Database:');
define('TEXT_INSTALL_DB','Install the database and add the sample data \n Checking this box will import the database structure required data and some sample data. (required for first time installations)');
define('TEXT_ITB','Install the database');
define('TEXT_AC','Automatic Configuration:');
define('TEXT_SAVE_CONFIG','Save configuration values \n Checking this box will save all entered data during the installation procedure to the appropriate configuration files on the server.');
define('TEXT_SCV','Save configuration values');
define('TEXT_CANCEL','Cancel');
define('TEXT_CONTINUE','Continue');
define('TEXT_UNSUCCESSFUL','A test connection made to the database was unsuccessful.');
define('TEXT_INSTALLATION','Installation');
define('TEXT_SCA','Server Capabilities');
define('TEXT_SC','Server Configuration');
define('TEXT_PHP','PHP Version');
define('TEXT_SETTINGS','PHP Settings');
define('TEXT_DB1','Database');

define('TEXT_THE_ERROR','The error message returned is:');
define('TEXT_REVIEW','Please review your database server settings.');
define('TEXT_REQUIRE','If you require help with your database server settings please consult your hosting company.');
define('TEXT_SUCCESS','A test connection made to the database was successful.');
define('TEXT_UNSUCCESS','A test connection made to the database was <font color="#AB212E">unsuccessful.</font>
.');
define('TEXT_PLEASE_CONTINUE','Please continue the installation process to execute the database import procedure.');
define('TEXT_BACKUP','Please take essential backups if an upgrade is being made to the database.');
define('TEXT_IMPORTANT','It is important this procedure is not interrupted otherwise the database may end up corrupt.');
define('TEXT_FILES','The files to be imported must be located at:');
define('TEXT_DB_UPGRADE','A database upgrade is available for your osConcert installation.');
define('TEXT_CONSIDER','Please consider making a  database backup before proceeding.');

define('TEXT_LOCATED','The files to be imported must be located at:');
define('TEXT_LOCATED2','The following files were located and will be installed:');
define('TEXT_UPTODATE','The existing osConcert database appears to be up to date.');
define('TEXT_REINSTALL','Do you want to Reinstall the Database wiping all existing data?');
define('TEXT_YES','Yes');
define('TEXT_NO','No');
define('TEXT_PROCEED','Click \'Continue\' to proceed');
define('TEXT_ENTER_SERVER','Please enter the database server information:');
define('TEXT_ENTER','Please enter the database information:');
define('TEXT_DBS','Database server:');
define('TEXT_SERVER','The database server can be in the form of a hostname  such as db1.myserver.com  or as an IP-address  such as 192.168.0.1');
define('TEXT_HOSTNAME','Hostame or IP-address of the database server');
define('TEXT_USERNAME','Username:');
define('TEXT_DB','Database username');
define('TEXT_USERNAME_USED','The username used to connect to the database server. An example username is \'mysql_10\'.\n \n Note: Create and Drop permissions <b>are not required</b> for the general use of osConcert.');

define('TEXT_PASSWORD','Password:');
define('TEXT_USER','The password is used together with the username  which forms the database user account.');
define('TEXT_DB_PASSWORD','Database password');
define('TEXT_DB_NAME','Database name:');
define('TEXT_DB_NAME_DESC','The database used to hold the data. An example database name is \'osconcert\'.');
define('TEXT_DB_NAME2','Database Name');
define('TEXT_SS','Session Storage:');
define('TEXT_NOTE_SECURITY','Note: Due to security related issues  database session storage is recommended for shared servers.');
define('TEXT_STORE_USER','Store user session data in the database.');
define('TEXT_DB_UNSUCCESFUL','The database import was unsuccessful.');
define('TEXT_SQL_FILE','Sql file:');
define('TEXT_DB_SUCCESSFULL','The database import was <font color=#117a8b>successful.</font>');
define('TEXT_HOWEVER','However there was an error running the \'Create View\' sql command <br>- please try running this manually via phpMyAdmin or similar');
define('TEXT_FAILED','Custom upgrade failed');
define('TEXT_CONTACT_SUPPLIER','Please contact your software supplier');
define('TEXT_CUSTOM','A custom upgrade appears to be  available for your database.');
define('TEXT_CLICK_UPGRADE','Click on \'Upgrade\' to install it or \'Continue\' to <b>skip</b> this stage');
define('TEXT_SKIP','Skip');
define('TEXT_WAIT','Please wait a moment');
define('TEXT_SERVER_CONF','Server Configuration');
define('TEXT_SSC','Secure Server Configuration');
define('TEXT_DC','Database Configuration');
define('TEXT_CD','Configuration Done');
define('TEXT_ENTER2','Please enter the web server information:');
define('TEXT_WWW_ADDRESS','WWW Address:');
define('TEXT_FULL_ADDRESS','The full website address to the online store');
define('TEXT_WEB_ADDRESS','The web address to the online store  for example <i>http://www.my-server.com/osconcert/</i>');
define('TEXT_WRD','Webserver Root Directory:');
define('TEXT_DIRECTORY','The directory where osConcert is installed on the server for example <i>/home/myname/public_html/osconcert/</i>');
define('TEXT_PATH','The server path to the online store');
define('TEXT_HTTP_COOKIE','HTTP Cookie Domain:');
define('TEXT_TLD','The full or top-level domain to store the cookies in  for example <i>.my-server.com</i>');
define('TEXT_COOKIE_DOMAIN','The domain to store cookies in');
define('TEXT_HTTP_PATH','HTTP Cookie Path:');
define('TEXT_EXAMPLE','The web address to limit the cookie to  for example <i>/catalog/</i>');
define('TEXT_PTSC','The path to store cookies under');
define('TEXT_HOMEPAGE','Home Page Link:');
define('TEXT_HOMEURL','The home url path  for example  <i>http://www.yoursite.com</i>');
define('TEXT_HOMELINK','Home Link');
define('TEXT_SSL','Enable SSL Connections:');
define('TEXT_ENABLESSL','Enable secure SSL/HTTPS connections (requires a secure certificate installed on the web server)');
define('TEXT_ENABLESSL2','Enable secure SSL/HTTPS connections');
define('TEXT_ADMIN_NAME','Administration Directory Name:');
define('TEXT_THIS_ADMIN','This is the directory where the administration section will be installed. You should change this for security reasons.');
define('TEXT_THIS','This is the directory where the administration section will be installed.<br>PLEASE CHANGE THIS TO SOMETHING THAT IS MORE DIFFICULT TO FIND');
define('TEXT_ENTER3','Please enter the secure web server information:');
define('TEXT_SECURE_CD','Secure Cookie Domain:');
define('TEXT_SECURE_WWW','Secure WWW Address:');
define('TEXT_HTTPS_COOKIE','HTTPS Cookie Domain:');
define('TEXT_STLD','The secure web address to the online store, for example <i>https://ssl.my-hosting-company.com/osconcert/</i>');
define('TEXT_SCOOKIE_DOMAIN','The secure domain to store cookies in');
define('TEXT_HTTPS_PATH','Secure Cookie Path:');
define('TEXT_SECURE_WEB','The web address of the secure server to limit the cookie to, for example <i>/my_name/tickets/</i>');
define('TEXT_SECURE_FULL','The full website address to the online store on the secure server');
define('TEXT_SECURE_FULLTLD','The full or top-level domain of the secure server to store the cookies in, for example <i>ssl.my-hosting-company.com</i>');
define('TEXT_HTTPS_DOMAIN','The secure domain to store cookies in');
define('TEXT_SECURE_PATH','The secure path to store cookies under');
define('TEXT_BACK','Please review your database server settings. [ back ]');
define('TEXT_HELP','If you require help with your database server settings  please consult your hosting company.');
define('TEXT_ERROR2','The following error has occurred:');
define('TEXT_NOT_EXIST','The configuration files do not exist  or permission levels are not set.');
define('TEXT_PERFORM','Please perform the following actions:');
define('TEXT_SUCCESSFUL2','The configuration was <font color=#117a8b>successful</font>');
define('TEXT_ADMIN','Admin');
define('TEXT_CATALOGS','Catalogs');
define('TEXT_IMPORT_SP','Import the osConcert Sample Data');
define('TEXT_IMPORT_SP2','Import osConcert Design Mode Sample Data');
define('TEXT_NOTFOUND','osconcert_data.sql not found in install folder');
define('TEXT_CHANGE','Please change the Login Settings for Administration tool');
define('TEXT_FINISHED','Installation finished');
define('TEXT_SUCCESSFUL3','Installation was <strong>successful</strong>');
define('TEXT_ADMIN_LOGIN_SETTINGS','The Login Settings for Administration tool are set <strong>successfully</strong>');

define('TEXT_TOOL','Please change the Login Settings for Administration tool.');
define('TEXT_EMAIL','Email Address:');
define('TEXT_CONFIRM','Confirm Password:');

define('TEXT_SQLERROR','MySQLi error - further action required');
define('TEXT_VIEW','Whilst the database was successfully installed an attempt to automatically run a MySQL \'Create View\' command failed with this error:');
define('TEXT_MANUALLY','You need to manually run the following MySQL query either through your hosting control panel or, in some instances, by requesting your hosting company to do so:');


define('TEXT_AFTER','After Install remove install directory');
define('TEXT_STILL_EXISTS','install <b>Directory still exists</b>');
define('TEXT_DELETED','For security reasons this should be deleted.');
define('TEXT_WRITABLE','<strong>is writable. Please alter this after installation.</strong><br>');
define('TEXT_CDS','Config Directory Security');
define('TEXT_FRONT_END','Front End');
define('TEXT_ADMINISTRATION','Administration');
define('TEXT_CONF_OSC','Configure osConcert');
define('TEXT_COMPLETE','Installation Complete');
define('TEXT_LOGIN_SETTINGS','The Login Settings for Administration tool are <b>Username</b>: webmaster@osconcert.com');
define('TEXT_PASSWORD_SETTINGS','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Password</b>:user@123@go ');
?>
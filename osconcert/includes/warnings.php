<?php
/*
osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 

Freeway eCommerce
http://www.openfreeway.org
Copyright (c) 2007 ZacWare

Released under the GNU General Public License 
*/
// check if the 'install' directory exists, and warn of its existence

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

  function check_dir(){
       global $PHP_SELF,$FREQUEST;
	   if(defined('DIR_FS_CATALOG')){
		$FREQUEST->setvalue('SCRIPT_FILENAME',DIR_FS_CATALOG,'SERVER');
		$file_dir=$FREQUEST->servervalue('SCRIPT_FILENAME');
	   }
	   return $file_dir.basename($PHP_SELF);
  } 
	
  $file_name=check_dir(); 
  	 
 if(WARN_INSTALL_EXISTENCE == 'true') 
 {
    if(file_exists(dirname($file_name) . '/install')) {
      $messageStack->add('header', WARNING_INSTALL_DIRECTORY_EXISTS, 'warning');
    }
  }

// check if the configure.php file is writeable
  // if(WARN_CONFIG_WRITEABLE == 'true') 
  // {
    // if ( (file_exists(dirname($file_name) . '/includes/configure.php')) && (is_writeable(dirname($file_name) . '/includes/configure.php')) ) {
      // $messageStack->add('header', WARNING_CONFIG_FILE_WRITEABLE, 'warning');
    // }
  // }
  
  // check if the Log folder is not writable
/*if(WARN_LOG_DIRECTORY_NOT_WRITABLE == 'true')
{
$path=STORE_PAGE_PARSE_TIME_LOG;
$dir = substr(strrchr($path, "/"), 1);

if($dir!="")
	  $pos=is_file($path);
if($pos!="")
	$dirname=substr($path,0,-(strlen($dir)));
	
else
	$dirname=$path;
	
 if ((!file_exists($dirname)) || (!is_writeable($dirname)) ) {
      $messageStack->add('header', WARNING_LOG_DIRECTORY_WRITEABLE, 'warning');
    }
}*/

  
 // check if the images folder is not writeable
  if (WARN_IMAGES_DIRECTORY_NOT_WRITEABLE == 'true') { 
  		if ( (!file_exists(dirname($file_name) . '/images')) || (!is_writeable(dirname($file_name) . '/images')) ) {
      $messageStack->add('header', WARNING_IMAGES_DIRECTORY_WRITEABLE, 'warning');
    }
  }

// check if the images/big folder is not writeable
  if (WARN_IMAGES_BIG_DIRECTORY_NOT_WRITEABLE == 'true') {
      if ( (!file_exists(dirname($file_name) . '/images/big')) || (!is_writeable(dirname($file_name) . '/images/big')) ) {
      $messageStack->add('header', WARNING_IMAGES_BIG_DIRECTORY_WRITEABLE, 'warning');
    }
  }
  // check if the images/small folder is not writeable
  if (WARN_IMAGES_SMALL_DIRECTORY_NOT_WRITEABLE == 'true') {
      if ( (!file_exists(dirname($file_name) . '/images/small')) || (!is_writeable(dirname($file_name) . '/images/small')) ) {
      $messageStack->add('header', WARNING_IMAGES_SMALL_DIRECTORY_WRITEABLE, 'warning');
    }
  }
  
// check if the session folder is writeable
  if (WARN_SESSION_DIRECTORY_NOT_WRITEABLE == 'true') {
    if (STORE_SESSIONS == '') {
      if (!is_dir($FSESSION->save_path)) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NON_EXISTENT, 'warning');
      } elseif (!is_writeable($FSESSION->save_path)) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NOT_WRITEABLE, 'warning');
      }
    }
  }


// give the visitors a message that the website will be down at ... time
  if ( (WARN_BEFORE_DOWN_FOR_MAINTENANCE == 'true') && (DOWN_FOR_MAINTENANCE == 'false') ) {
       $messageStack->add('header', TEXT_BEFORE_DOWN_FOR_MAINTENANCE . PERIOD_BEFORE_DOWN_FOR_MAINTENANCE, 'warning');
  }


// this will let the admin know that the website is DOWN FOR MAINTENANCE to the public
  if ( (DOWN_FOR_MAINTENANCE == 'true') && (EXCLUDE_ADMIN_IP_FOR_MAINTENANCE == getenv('REMOTE_ADDR')) ) {
       $messageStack->add('header', TEXT_ADMIN_DOWN_FOR_MAINTENANCE, 'warning');
  }

// check session.auto_start is disabled
  if ( (function_exists('ini_get')) && (WARN_SESSION_AUTO_START == 'true') ) {
    if (ini_get('session.auto_start') == '1') {
      $messageStack->add('header', WARNING_SESSION_AUTO_START, 'warning');
    }
  }

  if ( (WARN_DOWNLOAD_DIRECTORY_NOT_READABLE == 'true') && (DOWNLOAD_ENABLED == 'true') ) {
    if (!is_dir(DIR_FS_DOWNLOAD)) {
      $messageStack->add('header', WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT, 'warning');
    }
  }

  if ($messageStack->size('header') > 0) {
    echo $messageStack->output('header');
  }

  /*if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<table width="100%" cellpadding="2">
  <tr class="headerError">
    <td class="headerError"><?php echo htmlspecialchars(urldecode($HTTP_GET_VARS['error_message'])); ?></td>
  </tr>
</table>
<?php
  } */

  if ($FREQUEST->getvalue('info_message') && tep_not_null($FREQUEST->getvalue('info_message'))) {
?>
<table>
  <tr class="headerInfo">
    <td class="headerInfo"><?php echo htmlspecialchars($FREQUEST->getvalue('info_message')); ?></td>
  </tr>
</table>
<?php
  }
?>

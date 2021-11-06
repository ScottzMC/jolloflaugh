<?php
/* 
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	http://www.osconcert.com

  	Copyright (c) 2020 osConcert 
	
	Released under the GNU General Public License 
*/ 
  $dir_fs_document_root = $HTTP_POST_VARS['DIR_FS_DOCUMENT_ROOT'];
  if ((substr($dir_fs_document_root, -1) != '/') && (substr($dir_fs_document_root, -1) != '/')) {
    $where = strrpos($dir_fs_document_root, '\\');
    if (is_string($where) && !$where) {
      $dir_fs_document_root .= '/';
    } else {
      $dir_fs_document_root .= '\\';
    }
  }
  $db = array();
  $db['DB_SERVER'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER']));
  $db['DB_SERVER_USERNAME'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_USERNAME']));
  $db['DB_SERVER_PASSWORD'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_PASSWORD']));
  $db['DB_DATABASE'] = trim(stripslashes($HTTP_POST_VARS['DB_DATABASE']));

  $db_error = false;
  osc_db_connect($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD'], $db['DB_DATABASE']);

  if ($db_error == false) {
    osc_db_test_connection($db['DB_DATABASE']);
  }
  
  if ($db_error != false) {
  	$current_step="config";
?>
	<form name="install" action="install.php?step=6" method="post">
		<div class="card border-danger mb-3 col-4 mx-auto" style="max-width: 50rem;" id="txr_terms">
			<div class="card-header"><i class='fas fa-times-circle' style='color:red'></i>A test connection made to the database was <font color="#AB212E">unsuccessful.</font></div>
			<div class="card-body">
				<p class="card-text">The error message returned is:</p>
				<p class="card-text"><?php echo $db_error; ?></p>
				<p class="card-text">Please click on the <i>Back</i> button below to review your database server settings.</p>
				<p class="card-text">If you require help with your database server settings, please consult your hosting company.</p>
			</div>
		</div>
		<?php 
				reset($HTTP_POST_VARS);
				//while (list($key, $value) = each($HTTP_POST_VARS)) {
					foreach($HTTP_POST_VARS as $key => $value) {
					  if (($key != 'x') && ($key != 'y') && ($key != 'DB_TEST_CONNECTION')) {
						if (is_array($value)) {
							  for ($i=0; $i<sizeof($value); $i++) {
								echo osc_draw_hidden_field($key . '[]', $value[$i]);
							  }
						} else {
							  echo osc_draw_hidden_field($key, $value);
						}
					  }
				}
		?>
		<button type="button" class="btn btn-info" style="float: left; margin-top: 10px;" onClick="this.form.submit();"><i class='fas fa-arrow-alt-circle-left' style='color:white'></i> Back</button>
		<a class="btn btn-info" style="float: right; margin-top: 10px;" href="index.php" role="button"><i class='fas fa-times-circle' style='color:white'></i> Cancel</a>
	</form>	   

<?php
  } elseif (( (file_exists($dir_fs_document_root . 'includes/configure.php')) && (!is_writeable($dir_fs_document_root . 'includes/configure.php')) ) || ( (file_exists($dir_fs_document_root . '/admin/includes/configure.php')) && (!is_writeable($dir_fs_document_root . '/admin/includes/configure.php')) ) ) {
   	$current_step="config";
?>
	<form name="install" action="install.php?step=7" method="post">
		<div class="card border-danger mb-3 col-4 mx-auto" style="max-width: 50rem;" id="txr_terms">
			<div class="card-header"><i class='fas fa-times-circle' style='color:red'></i>The following error has occurred:</div>
			<div class="card-body">
				<p class="card-text">
					<b>The configuration files do not exist, or permission levels are not set.</b>
					<br><br>Please perform the following actions:
					<ul><li>cd <?php echo $dir_fs_document_root; ?>includes/</li><li>touch configure.php</li><li>chmod 706 configure.php</li></ul>
					<ul><li>cd <?php echo $dir_fs_document_root; ?>admin/includes/</li><li>touch configure.php</li><li>chmod 706 configure.php</li></ul>
				</p>
				<p class="card-text">If <i>chmod 706</i> does not work, please try <i>chmod 777</i>.</p>
				<p class="card-text">If you are running this installation procedure under a Microsoft Windows environment, try renaming the existing configuration file so a new file can be created.</p>
			</div>
		</div>
		<?php 
				reset($HTTP_POST_VARS);
				//while (list($key, $value) = each($HTTP_POST_VARS)) {
					foreach($HTTP_POST_VARS as $key => $value) {
					  if ($key != 'x' && $key != 'y' ) {
						if (is_array($value)) {
							  for ($i=0; $i<sizeof($value); $i++) {
								echo osc_draw_hidden_field($key . '[]', $value[$i]);
							  }
						} else {
							  echo osc_draw_hidden_field($key, $value);
						}
					  }
				}
		?>
		<button type="button" class="btn btn-info" style="float: left; margin-top: 10px;" onClick="this.form.submit();"><i class='fas fa-arrow-alt-circle-left' style='color:white'></i> Back</button>
		<a class="btn btn-info" style="float: right; margin-top: 10px;" href="index.php" role="button"><i class='fas fa-times-circle' style='color:white'></i> Cancel</a>
	</form>	 
<?php
} else {
    $http_url = parse_url($HTTP_POST_VARS['HTTP_WWW_ADDRESS']);
    $http_server = $http_url['scheme'] . '://' . $http_url['host'];
    $http_catalog = $http_url['path'];
    if (isset($http_url['port']) && !empty($http_url['port'])) {
      $http_server .= ':' . $http_url['port'];
    }

    if (substr($http_catalog, -1) != '/') {
      $http_catalog .= '/';
    }

    $https_server = '';
    $https_catalog = '';
    if (isset($HTTP_POST_VARS['HTTPS_WWW_ADDRESS']) && !empty($HTTP_POST_VARS['HTTPS_WWW_ADDRESS'])) {
      $https_url = parse_url($HTTP_POST_VARS['HTTPS_WWW_ADDRESS']);
      $https_server = $https_url['scheme'] . '://' . $https_url['host'];
      $https_catalog = $https_url['path'];

      if (isset($https_url['port']) && !empty($https_url['port'])) {
        $https_server .= ':' . $https_url['port'];
      }

      if (substr($https_catalog, -1) != '/') {
        $https_catalog .= '/';
      }
    }

    $enable_ssl = (isset($HTTP_POST_VARS['ENABLE_SSL']) && ($HTTP_POST_VARS['ENABLE_SSL'] == 'true') ? 'true' : 'false');
    $http_cookie_domain = $HTTP_POST_VARS['HTTP_COOKIE_DOMAIN'];
    $https_cookie_domain = (isset($HTTP_POST_VARS['HTTPS_COOKIE_DOMAIN']) ? $HTTP_POST_VARS['HTTPS_COOKIE_DOMAIN'] : '');
    $http_cookie_path = $HTTP_POST_VARS['HTTP_COOKIE_PATH'];
    $http_home_url = $HTTP_POST_VARS['HTTP_HOME_URL'];	
    $https_cookie_path = (isset($HTTP_POST_VARS['HTTPS_COOKIE_PATH']) ? $HTTP_POST_VARS['HTTPS_COOKIE_PATH'] : '');
	//force ssl on site
	if ($enable_ssl == true){
		
	  $http_server = $https_server;
	  $http_home_url = $url = str_replace( 'http://', 'https://', strtolower($http_home_url)  );
	}

    $file_contents = '<?php' . "\n" .
                     '/*' . "\n" .
                     '  osCommerce, Open Source E-Commerce Solutions' . "\n" .
                     '  http://www.oscommerce.com' . "\n" .
                     '' . "\n" .
                     '  Copyright (c) 2003 osCommerce' . "\n" .
                     '' . "\n" .
					 '  ' . "\n" .
					 '  http://www.osconcert.com' . "\n" .
					 '  ' . "\n" . 
					 '' . "\n" .
                     '  Released under the GNU General Public License' . "\n" .
                     '*/' . "\n" .
                     '' . "\n" .
                     '// Define the webserver and path parameters' . "\n" .
                     '// * DIR_FS_* = Filesystem directories (local/physical)' . "\n" .
                     '// * DIR_WS_* = Webserver directories (virtual/URL)' . "\n" .
                     '  define(\'HTTP_SERVER\', \'' . $http_server . '\'); // eg, http://localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'HTTPS_SERVER\', \'' . $https_server . '\'); // eg, https://localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'ENABLE_SSL\', ' . $enable_ssl . '); // secure webserver for checkout procedure?' . "\n" .
                     '  define(\'HTTP_COOKIE_DOMAIN\', \'' . $http_cookie_domain . '\');' . "\n" .
                     '  define(\'HTTPS_COOKIE_DOMAIN\', \'' . $https_cookie_domain . '\');' . "\n" .
                     '  define(\'HTTP_COOKIE_PATH\', \'' . $http_cookie_path . '\');' . "\n" .
                     '  define(\'HTTPS_COOKIE_PATH\', \'' . $https_cookie_path . '\');' . "\n" .
		 					'  define(\'HTTP_HOME_URL\', \'' . $http_home_url . '\');' . "\n" .
                     '  define(\'DIR_WS_HTTP_CATALOG\', \'' . $http_catalog . '\');' . "\n" .
                     '  define(\'DIR_WS_HTTPS_CATALOG\', \'' . $https_catalog . '\');' . "\n" .
                     '  define(\'DIR_WS_IMAGES\', \'images/\');' . "\n" .
                     '  define(\'DIR_WS_ICONS\', DIR_WS_IMAGES . \'icons/\');' . "\n" .
                     '  define(\'DIR_WS_INCLUDES\', \'includes/\');' . "\n" .
                     '  define(\'DIR_WS_BOXES\', DIR_WS_INCLUDES . \'boxes/\');' . "\n" .
                     '  define(\'DIR_WS_FUNCTIONS\', DIR_WS_INCLUDES . \'functions/\');' . "\n" .
                     '  define(\'DIR_WS_CLASSES\', DIR_WS_INCLUDES . \'classes/\');' . "\n" .
                     '  define(\'DIR_WS_MODULES\', DIR_WS_INCLUDES . \'modules/\');' . "\n" .
                     '  define(\'DIR_WS_LANGUAGES\', DIR_WS_INCLUDES . \'languages/\');' . "\n" .
                     '' . "\n" .
                     '//Added for BTS1.0' . "\n" .
                     '  define(\'DIR_WS_TEMPLATES\', \'templates/\');' . "\n" .
                     '  define(\'DIR_WS_CONTENT\', DIR_WS_TEMPLATES . \'content/\');' . "\n" .
                     '  define(\'DIR_WS_JAVASCRIPT\', DIR_WS_INCLUDES . \'javascript/\');' . "\n" .
                     '//End BTS1.0' . "\n" .
                     '  define(\'DIR_WS_DOWNLOAD_PUBLIC\', \'pub/\');' . "\n" .
                     '  define(\'DIR_FS_CATALOG\', \'' . $dir_fs_document_root . '\');' . "\n" .
                     '  define(\'DIR_FS_DOWNLOAD\', DIR_FS_CATALOG . \'download/\');' . "\n" .
                     '  define(\'DIR_FS_DOWNLOAD_PUBLIC\', DIR_FS_CATALOG . \'pub/\');' . "\n" .
                     '' . "\n" .
                     '// define our database connection' . "\n" .
                     '  define(\'DB_SERVER\', \'' . $HTTP_POST_VARS['DB_SERVER'] . '\'); // eg, localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'DB_SERVER_USERNAME\', \'' . $HTTP_POST_VARS['DB_SERVER_USERNAME'] . '\');' . "\n" .
                     '  define(\'DB_SERVER_PASSWORD\', \'' . $HTTP_POST_VARS['DB_SERVER_PASSWORD']. '\');' . "\n" .
                     '  define(\'DB_DATABASE\', \'' . $HTTP_POST_VARS['DB_DATABASE']. '\');' . "\n" .
                     //'  define(\'USE_PCONNECT\', \'' . (($HTTP_POST_VARS['USE_PCONNECT'] == 'true') ? 'true' : 'false') . '\'); // use persistent connections?' . "\n" .
                     '  define(\'STORE_SESSIONS\', \'' . (($HTTP_POST_VARS['STORE_SESSIONS'] == 'files') ? '' : 'mysql') . '\'); // leave empty \'\' for default handler or set to \'mysql\'' . "\n" .
                     '?>';

    $fp = fopen($dir_fs_document_root . 'includes/configure.php', 'w');
    fputs($fp, $file_contents);
    fclose($fp);
	$admin_folder = 'admin';
	if (isset($HTTP_POST_VARS['CFG_ADMIN_DIRECTORY']) && !empty($HTTP_POST_VARS['CFG_ADMIN_DIRECTORY']) && osc_is_writable($dir_fs_document_root) && osc_is_writable($dir_fs_document_root . 'admin')) {
	$admin_folder = preg_replace('/[^a-zA-Z0-9]/', '', trim($HTTP_POST_VARS['CFG_ADMIN_DIRECTORY']));

	if (empty($admin_folder)) {
	  $admin_folder = 'admin';
	}
	}


    $file_contents = '<?php' . "\n" .
                     '/*' . "\n" .
                     '  osCommerce, Open Source E-Commerce Solutions' . "\n" .
                     '  http://www.oscommerce.com' . "\n" .
                     '' . "\n" .
                     '  Copyright (c) 2003 osCommerce' . "\n" .
                     '' . "\n" .
					 '  ' . "\n" .
					 '  https://www.osconcert.com' . "\n" .
					 '  ' . "\n" . 
					 '' . "\n" .
                     '  Released under the GNU General Public License' . "\n" .
                     '*/' . "\n" .
                     '' . "\n" .
                     '// Define the webserver and path parameters' . "\n" .
                     '// * DIR_FS_* = Filesystem directories (local/physical)' . "\n" .
                     '// * DIR_WS_* = Webserver directories (virtual/URL)' . "\n" .
                     '  define(\'HTTP_SERVER\', \'' . $http_server . '\'); // eg, http://localhost - should not be empty for productive servers' . "\n" .
		     '  define(\'HTTPS_SERVER\', \'' . $https_server . '\'); // eg, https://localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'HTTP_CATALOG_SERVER\', \'' . $http_server . '\');' . "\n" .
                     '  define(\'HTTPS_CATALOG_SERVER\', \'' . $https_server . '\');' . "\n" .
                     '  define(\'ENABLE_SSL_CATALOG\', \'' . $enable_ssl . '\'); // secure webserver for catalog module' . "\n" .
                     '  define(\'DIR_FS_DOCUMENT_ROOT\', \'' . $dir_fs_document_root . '\'); // where the pages are located on the server' . "\n" .
                     '  define(\'DIR_WS_ADMIN\', \'' . $http_catalog . $admin_folder .'/\'); // absolute path required' . "\n" .
                     '  define(\'DIR_FS_ADMIN\', \'' . $dir_fs_document_root . $admin_folder . '/\'); // absolute pate required' . "\n" .
                     '  define(\'DIR_WS_CATALOG\', \'' . $http_catalog . '\'); // absolute path required' . "\n" .
                     '  define(\'DIR_FS_CATALOG\', \'' . $dir_fs_document_root . '\'); // absolute path required' . "\n" .
                     '  define(\'DIR_WS_IMAGES\', \'images/\');' . "\n" .
                     '  define(\'DIR_WS_ICONS\', DIR_WS_IMAGES . \'icons/\');' . "\n" .
                     '  define(\'DIR_WS_CATALOG_IMAGES\', DIR_WS_CATALOG . \'images/\');' . "\n" .
                     '  define(\'DIR_WS_INCLUDES\', \'includes/\');' . "\n" .
                     '  define(\'DIR_WS_BOXES\', DIR_WS_INCLUDES . \'boxes/\');' . "\n" .
                     '  define(\'DIR_WS_FUNCTIONS\', DIR_WS_INCLUDES . \'functions/\');' . "\n" .
                     '  define(\'DIR_WS_CLASSES\', DIR_WS_INCLUDES . \'classes/\');' . "\n" .
                     '  define(\'DIR_WS_MODULES\', DIR_WS_INCLUDES . \'modules/\');' . "\n" .
                     '  define(\'DIR_WS_LANGUAGES\', DIR_WS_INCLUDES . \'languages/\');' . "\n" .
                     '  define(\'DIR_WS_CATALOG_LANGUAGES\', DIR_WS_CATALOG . \'includes/languages/\');' . "\n" .
                     '  define(\'DIR_FS_CATALOG_LANGUAGES\', DIR_FS_CATALOG . \'includes/languages/\');' . "\n" .
                     '  define(\'DIR_FS_CATALOG_IMAGES\', DIR_FS_CATALOG . \'images/\');' . "\n" .
                     '  define(\'DIR_FS_CATALOG_MODULES\', DIR_FS_CATALOG . \'includes/modules/\');' . "\n" .
                     '  define(\'DIR_FS_BACKUP\', DIR_FS_ADMIN . \'backups/\');' . "\n" .
					 '  //Added for Htmlarea editor. ' . "\n" .
					 '  define(\'DIR_WS_CATALOG_IMAGES_ROOT\', \'images/\');' . "// folder name for catalog images \n" .
					 '  define(\'DIR_FS_DOWNLOAD\', DIR_FS_CATALOG . \'download/\');' . "// folder for saving download files \n" .
                     '' . "\n" .
                     '// Added for Templating' . "\n" .
  		     '	define(\'DIR_FS_CATALOG_MAINPAGE_MODULES\', DIR_FS_CATALOG_MODULES . \'mainpage_modules/\');' . "\n" .
  		     '	define(\'DIR_WS_TEMPLATES\', DIR_WS_CATALOG . \'templates/\');' . "\n" .
  		     '	define(\'DIR_FS_TEMPLATES\', DIR_FS_CATALOG . \'templates/\');' . "\n" .
                     '' . "\n" .
                     '// define our database connection' . "\n" .
                     '  define(\'DB_SERVER\', \'' . $HTTP_POST_VARS['DB_SERVER'] . '\'); // eg, localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'DB_SERVER_USERNAME\', \'' . $HTTP_POST_VARS['DB_SERVER_USERNAME'] . '\');' . "\n" .
                     '  define(\'DB_SERVER_PASSWORD\', \'' . $HTTP_POST_VARS['DB_SERVER_PASSWORD']. '\');' . "\n" .
                     '  define(\'DB_DATABASE\', \'' . $HTTP_POST_VARS['DB_DATABASE']. '\');' . "\n" .
                    // '  define(\'USE_PCONNECT\', \'' . (($HTTP_POST_VARS['USE_PCONNECT'] == 'true') ? 'true' : 'false') . '\'); // use persisstent connections?' . "\n" .
                     '  define(\'STORE_SESSIONS\', \'' . (($HTTP_POST_VARS['STORE_SESSIONS'] == 'files') ? '' : 'mysql') . '\'); // leave empty \'\' for default handler or set to \'mysql\'' . "\n" .
                     '?>';

	$fp = fopen($dir_fs_document_root . 'admin/includes/configure.php', 'w');
	fputs($fp, $file_contents);
	fclose($fp);
	
	@chmod($dir_fs_document_root . 'admin/includes/configure.php', 0644);
	
	if ($admin_folder != 'admin') {
		@rename($dir_fs_document_root . 'admin', $dir_fs_document_root . $admin_folder);
	}
?>
<?php 
	if (osc_in_array("database",$HTTP_POST_VARS["install"])) { 
		$current_step="config";
	?>
		<div class="container">
			<div class="col-md-12 col-sm-12 col-xs-12">			
				<form name="install" action="install.php?step=8" method="post">
					<p><i class='fas fa-chevron-circle-down' style='color:green'></i><b>The configuration was <font color="#117a8b">successful</font></b></p>
					<p><b>Please wait a moment</b></p>            
	   <?php
				reset($HTTP_POST_VARS);
				//while (list($key, $value) = each($HTTP_POST_VARS)) {
					foreach($HTTP_POST_VARS as $key => $value) {
					if ($key != 'x' && $key != 'y') {
						if (is_array($value)) {
							for ($i=0; $i<sizeof($value); $i++) {
								echo osc_draw_hidden_field($key . '[]', $value[$i]);
							}
						} else {
							echo osc_draw_hidden_field($key, $value);
						}
					}
				}
				echo osc_draw_hidden_field('CFG_ADMIN_FOLDER', $admin_folder);			 
		?>
		<button type="button" class="btn btn-info" style="float: left; margin-top: 10px;" onClick="this.form.submit();"><i class='fas fa-arrow-alt-circle-left' style='color:white'></i> Continue</button>	
	   <script type="text/javascript">
		function myfunc () {
			document.forms["install"].submit()
		}
		window.onload = myfunc;
		</script>
				</form>
			</div>
		</div>
	<?php 
	} else {
		$current_step="complete";
?>
		<div class="container">
			<div class="col-md-12 col-sm-12 col-xs-12">			
				<form name="install" action="install.php?step=8" method="post">
					<p><i class='fas fa-chevron-circle-down' style='color:green'></i><b>Installation was <font color="#117a8b">successful</font></b></p>            
	   <?php
					reset($HTTP_POST_VARS);
					//while (list($key, $value) = each($HTTP_POST_VARS)) {
						foreach($HTTP_POST_VARS as $key => $value) {
						if ($key != 'x' && $key != 'y') {
						if (is_array($value)) {
							for ($i=0; $i<sizeof($value); $i++) {
								echo osc_draw_hidden_field($key . '[]', $value[$i]);
							}
						} else {
							echo osc_draw_hidden_field($key, $value);
							}
						}
					}
		?>
					<a class="btn btn-info" style="float: left; margin-top: 10px;" href="<?php echo HTTP_POST_VARS['HTTP_WWW_ADDRESS'] . 'index.php'; ?>" role="button"><i class='fas fa-book-open' style='color:white'></i> Catalogs</a>
					<a class="btn btn-info" style="float: right; margin-top: 10px;" href="<?php echo $HTTP_POST_VARS['HTTP_WWW_ADDRESS'] . $admin_folder.'/index.php'; ?>" role="button"><i class='fas fa-user-alt' style='color:white'></i> Admin</a>   
				</form>
			</div>
		</div>
<?php
    }
}
?>
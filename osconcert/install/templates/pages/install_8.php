<?php
/* 
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	http://www.osconcert.com

  	Copyright (c) 2021 osConcert 
	
	Released under the GNU General Public License 
*/
	$admin_folder = $HTTP_POST_VARS['CFG_ADMIN_FOLDER'];
	$www_location=isset($HTTP_POST_VARS['HTTP_WWW_ADDRESS'])?$HTTP_POST_VARS['HTTP_WWW_ADDRESS']:'';
	$web_service=isset($HTTP_POST_VARS['webservice'])?$HTTP_POST_VARS['webservice']:'0';
	$search_engine=isset($HTTP_POST_VARS['search_engine'])?$HTTP_POST_VARS['search_engine']:'N';
	$sample_data=isset($HTTP_POST_VARS['sample_data'])?$HTTP_POST_VARS['sample_data']:'N';
	//$sample_data2=isset($HTTP_POST_VARS['sample_data2'])?$HTTP_POST_VARS['sample_data2']:'N';
	//$sample_data3=isset($HTTP_POST_VARS['sample_data3'])?$HTTP_POST_VARS['sample_data3']:'N';
	$db_error="";
	if ($www_location=="")
	{
	 $www_location = 'http://' .  $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
	 $www_location = substr($www_location, 0, strpos($www_location, 'install'));
	}
	if (osc_in_array('database', $HTTP_POST_VARS['install']) && isset($HTTP_POST_VARS["sell"])) 
	{
		$db = array();
		$db['DB_SERVER'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER']));
		$db['DB_SERVER_USERNAME'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_USERNAME']));
		$db['DB_SERVER_PASSWORD'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_PASSWORD']));
		$db['DB_DATABASE'] = trim(stripslashes($HTTP_POST_VARS['DB_DATABASE']));
		  
		osc_db_connect($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD'], $db['DB_DATABASE']);
		
		if (!osc_db_select_db($db['DB_DATABASE']))
		{
			$db_error=mysqli_error();
		}

		if (!$db_error){
			$sell=$HTTP_POST_VARS["sell"];
			$encrypt_value=isset($HTTP_POST_VARS["encrypt_value"])?$HTTP_POST_VARS["encrypt_value"]:'';
			$sell_items="";
			for ($icnt=0,$n=count($sell);$icnt<$n;$icnt++){
				$sell_items.=$sell[$icnt];
			}
			if ($sell_items=="") $sell_items="P";
			if (!osc_db_query("UPDATE configuration set configuration_value='" . $sell_items . "' where configuration_key='SHOP_SELL_ITEMS'")){
				$db_error=mysqli_error();
			}
			//=================================
			$config_query=osc_db_query("SELECT configuration_value from configuration where configuration_key='ENCRYPTION_HASH_VALUE'");
			if ($encrypt_value!='' && osc_db_num_rows($config_query)<=0){
			  osc_db_query("INSERT INTO configuration VALUES (NULL,'Encryption Hash Value','ENCRYPTION_HASH_VALUE','" . mysqli_escape_string(stripslashes($encrypt_value)) ."','Encryption Hash Value applied in cookies for storage of password',1,100,'','','','');");
			}
		}
		//$s="1";
		//$sp="2";
		//$sdp="3";
		$time_extend=false;
		if (osc_set_time_limit(220)) 
			$time_extend=true;
		
		if (!$db_error && $sample_data=='Y')
		{
			osc_db_select_db($db['DB_DATABASE']);
			$installed_query=osc_db_query("SELECT file_name from sql_queries where file_name='osconcert_data.sql'");
			$categories_query=osc_db_query("SELECT * from categories limit 1");
			if (osc_db_num_rows($installed_query)<=0 && osc_db_num_rows($categories_query)<=0){
				$script_filename = getenv('PATH_TRANSLATED');
			
				if (empty($script_filename)) {
					$script_filename = getenv('SCRIPT_FILENAME');
				}
				if (empty($script_filename)) {
					$script_filename = $_SERVER["SCRIPT_FILENAME"];
				}
			
				$script_filename = str_replace('\\', '/', $script_filename);
				$script_filename = str_replace('//', '/', $script_filename);
			
				$dir_fs_www_root_array = explode('/', dirname($script_filename));
				$dir_fs_www_root = array();
				for ($i=0, $n=sizeof($dir_fs_www_root_array)-1; $i<$n; $i++) {
					$dir_fs_www_root[] = $dir_fs_www_root_array[$i];
				}
				$dir_fs_www_root = implode('/', $dir_fs_www_root) . '/';
				
				if (file_exists($dir_fs_www_root . "install/osconcert_data.sql")){
					osc_db_install($db['DB_DATABASE'], $dir_fs_www_root . "install/osconcert_data.sql");
				} else {
					$db_error=TEXT_NOTFOUND;
				}
			}
		}
		if ($db_error != false) {
			$current_step="config";
?>
			<div class="card border-danger  mb-3  mx-auto" style="max-width: 50rem;" id="txr_terms">
				<div class="card-header"><?php echo TEXT_ERROR2; ?></div>
				<div class="card-body">
					<p class="card-text"><?php echo $db_error; ?></p>
					<div>	</div>
					<a class="btn btn-info" style="float: left; margin-top: 10px;" href="index.php" role="button"><i class='fas fa-arrow-alt-circle-left' style='color:white'></i> <?php echo TEXT_CANCEL; ?></a>
				</div>
			</div>
<?php
		} else {
			echo '<html><body>';
			echo '<form action="install.php?step=9" method="post" name="frmConfig">';
			  reset($HTTP_POST_VARS);
			 // while (list($key, $value) = each($HTTP_POST_VARS)) {
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
			echo '</form>';
			echo '</body></html>';
			echo '<script language="javascript">document.frmConfig.submit();</script>';
			exit;
	?>
			<p><b><?php echo TEXT_CONF_OSC; ?></b></p>
			<div style="width:95%; float:left;" class="formPage">
			  <div style="width:100%; float:left;">
				  <p><?php echo TEXT_SUCCESSFUL2; ?></b></p>
				  <p></p>
				  <p><?php echo TEXT_LOGIN_SETTINGS; ?></p>
				  <p><?php echo TEXT_PASSWORD_SETTINGS; ?></p>
				</div>
			</div>
			<table border="0" cellspacing="5" cellpadding="0" align="center">
			  <tr>
				<td align="center"><a href="<?php echo $www_location . 'index.php'; ?>" target="_blank">
									<input type="button" class="activebtncls" value="<?php echo TEXT_FRONT_END; ?>"  > 
								</a></td>
				<td align="center"><a href="<?php echo $www_location . $admin_folder.'/index.php'; ?>" target="_blank">
									<input type="button" class="activebtncls" value="<?php echo TEXT_ADMINISTRATION; ?>"  > 
								</a></td>
			  </tr>
			</table>
<?php
		} // db_error
		return;
	} // update
	if (osc_in_array('database', $HTTP_POST_VARS['install'])){
		// fetch ENCRYPTION_VALUE from configuration table if empty
		$show_encrypt=false;
		
	    $db = array();
		$db['DB_SERVER'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER']));
		$db['DB_SERVER_USERNAME'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_USERNAME']));
		$db['DB_SERVER_PASSWORD'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_PASSWORD']));
		$db['DB_DATABASE'] = trim(stripslashes($HTTP_POST_VARS['DB_DATABASE']));
	  
		osc_db_connect($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD'], $db['DB_DATABASE']);
	
		if (!osc_db_select_db($db['DB_DATABASE'])){
			$db_error=mysqli_error();
		}
		if (!$db_error){
			$config_query=osc_db_query("SELECT configuration_value from configuration where configuration_key='ENCRYPTION_HASH_VALUE'");
			if (osc_db_num_rows($config_query)<=0){
				$show_encrypt=true;
			}
		}
	  	$current_step="config";
?>
		<script language="javascript">
			function checkSell(item){
			
				if (item.checked==false){
					var selected=false;
					var sell=document.install.elements["sell[]"];
					for (icnt=0;icnt<sell.length;icnt++){	
						if (sell[icnt].checked) {
							selected=true;
							break;
						}
					}
					if (!selected) item.checked=true;
				}
			}

			function doAction(){
				error="";
				<?php if ($show_encrypt) {?>
				if (str_trim(document.install.encrypt_value.value)=="") error="Encryption Hash Value cannot be empty\n";
				else if (str_trim(document.install.encrypt_value.value).length<6) error="Encryption Hash value must have at least 6 chars\n";
				<?php } ?>
				if(document.install.email_alert.checked)
				{
					if(document.install.email_address.value=="")
						error+="Email Address is required.\n";
					else
					{
						if(document.install.email_address.value!=document.install.cemail_address.value)
							error+="Email Address does not match with confirm Email.\n";
					}
				}
				if (error!=""){
					alert(error);
					return;
				}
				document.install.submit();
			}
			 function str_trim(str){  
				if(!str || typeof str != 'string')  
					return " ";  
				return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
			 } 
		</script>
		<div class="container">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<form name="install" action="install.php?step=8" method="post">
				<div class="container" style="width:800px">
			<div class="row">
					<div class="col-md-12">
					<h3><?php echo TEXT_IMPORT_SP; ?></h3>  				
					<div style="display:none;"><input type="checkbox" name="sell[]" value="P" checked onclick="javascript:checkSell(this)" class="checkbox">&nbsp;Products</div>
					<input type="checkbox" name="sample_data" value="Y" checked="checked" class="checkbox"><span style="font-zize: 18px;color:red">&nbsp;<?php echo TEXT_IMPORT_SP2; ?></span><br>
					<div style="display:none;">
						<i class='fas fa-times-circle' style='color:red'>Alert Messaging</i>
					</div>
					<!--all below are hidden unneeded data-->
					<div style="display:none;">
						<b>What type of upgrade notices do you want to receive?</b><br>
						<input type="radio" name="upgrade_type" value="n" checked>I am new to osConcert. Keep me updated by email<br>
						<input type="radio" name="upgrade_type" value="d" >I am a developer, alert me when there are new versions.
					</div>		
					<div style="display:none;">
						<input type="checkbox" name="email_alert" value="1" id="email_alert" onclick="javascript:if(this.checked && document.getElementById('email_alert_div')) document.getElementById('email_alert_div').style.display=''; else document.getElementById('email_alert_div').style.display='none';"><b>Email me when there are security alerts.</b>
					</div>		
					<div style="display:none;">
						<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">
							<div style="width: 20%; vertical-align: text-top; float: left;" >Email Address:</div>
							<div style="width: 70%; float: left;"><input type="text" name="email_address"></div>
						</div>						
						<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">
							<div style="width: 20%; vertical-align: text-top; float: left;" >Confirm Address:</div>
							<div style="width: 70%; float: left;"><input type="text" name="cemail_address"></div>
						</div>	
					</div>		
					<div style="display:none;">
						<b><input type="checkbox" name="search_engine" value='Y' id="search_engine" onclick="javascript:if(this.checked && document.getElementById('sengine_div')) document.getElementById('sengine_div').style.display=''; else document.getElementById('sengine_div').style.display='none';">Do you want to use Search Engine Friendly URLs?</b>									
					</div>
					<div style="display:none;">
						<i class='fas fa-exclamation-triangle' style='color:yellow'></i>For Search Engine Friendly URLs to work, please ensure your <?php echo "<";?> osconcert>/tmp directory is writeable by the webserver. Add the following to your .htaccess file or Apache config:<br><br>
						<ul>
							RewriteEngine on
							<li>RewriteBase /<?php echo "<";?>subdirectory_path> or freeway/ </li> 
							<li>RewriteCond %{REQUEST_FILENAME} !-f </li> 
							<li>RewriteCond %{REQUEST_FILENAME} !-d </li> 
							<li>RewriteRule ^(.*) index.php </li> 
						</ul>						
						<br>For example: <br> 						
						If your osConcert installation is at mydomain.com/osconcert, your RewriteBase line will be: <br />
						RewriteBase /osconcert						
					</div>
		<?php 
					if ($show_encrypt) { ?>
						<div style="display:none;">
							Encryption Hash Value
							<input type="text" name="encrypt_value" value="<?php echo time();?>" size="20">
							<br>
							<div class="smallText" style="padding-left:10px">
								<font color="red">
									Encryption Hash Value applied in cookies for storage of password
								</font>
							</div>
						</div>
	<?php 
					}
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
					<a class="btn btn-info" style="float: left; margin-top: 10px;" href="index.php" role="button"><i class='fas fa-arrow-alt-circle-left' style='color:white'></i> <?php echo TEXT_CANCEL; ?></a>
					<button type="button" class="btn btn-info" style="float: right; margin-top: 10px;" onClick="doAction();"><?php echo TEXT_CONTINUE; ?> <i class='fas fa-arrow-alt-circle-right' style='color:white'></i></button>
					</div></div></div>
				</form>
			</div>
		</div>
<?php
	} 
?>

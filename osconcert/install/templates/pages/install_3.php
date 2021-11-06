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

  	Copyright (c) 2021 osConcert 
	
	Released under the GNU General Public License 
*/ 


	$start_time=time();
	// attempt to set the time limit to 30 seconds default  maximum 2mins(120)
	$time_extend=false;
	if (osc_set_time_limit(220)) $time_extend=true; 
	
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
	if (osc_in_array('database', $HTTP_POST_VARS['install'])) {
		$db = array();
		$db['DB_SERVER'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER']));
		$db['DB_SERVER_USERNAME'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_USERNAME']));
		$db['DB_SERVER_PASSWORD'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_PASSWORD']));
		$db['DB_DATABASE'] = trim(stripslashes($HTTP_POST_VARS['DB_DATABASE']));
	
		osc_db_connect($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD'], $db['DB_DATABASE']);
	
		$db_error = false;
		$sql_files=array();
		$version="9_0_0";
		$installed_sqls=array();
		// first check the database existance
		$database_query=osc_db_query("SHOW DATABASES like '" . $db['DB_DATABASE'] . "'");
		if (osc_db_num_rows($database_query)>0  && (!isset($HTTP_POST_VARS["optReinstall"]) || $HTTP_POST_VARS["optReinstall"]!="Y")){
			// first sql_queries table exists
			$check_query=osc_db_query("SHOW TABLES FROM " . $db['DB_DATABASE'] . " like 'sql_queries'");
			if (osc_db_num_rows($check_query)>0){
				osc_db_select_db($db['DB_DATABASE']);
				$installed_query=osc_db_query("SELECT file_name from sql_queries order by file_name");
				while($installed_result=mysqli_fetch_array($installed_query)){
					$installed_sqls[$installed_result["file_name"]]=1;
				}
			}
		} 
		// first get the last executed sql file
		$version_splt=preg_split("/_/",$version);
		$current_version=$version;
		while(true){
			$temp_file="osconcert_v" . $current_version;
			if (!file_exists($dir_fs_www_root . "install/" . $temp_file . ".sql")) break;
			if (!isset($installed_sqls[$temp_file . ".sql"])){
				$sql_files[]=$temp_file;
			}
			for ($icnt=2;$icnt>=0;$icnt--){
				$version_splt[$icnt]+=1;
				if ($version_splt[$icnt]<=9 || $icnt==0) break;
				$version_splt[$icnt]=0;
			}
			$current_version=join("_",$version_splt);
		}
		$first_install=0;
		$last_sql="";
		for ($icnt=0;$icnt<count($sql_files);$icnt++){
			if ($sql_files[$icnt]=="osconcert_v9_0_0") $first_install=1;
			$last_sql=$sql_files[$icnt];
			osc_db_install($db['DB_DATABASE'], $dir_fs_www_root . "install/" . $sql_files[$icnt] . ".sql");
			if ($db_error) break;
		}
		if ($db_error != false) {
			$current_step="install"; ?>
			<div class="card border-danger  mb-3 mx-auto" style="max-width: 50rem;" id="txr_terms">
				<div class="card-header"><i class='fa fa-times-circle' style='color:red'></i>The database import was unsuccessful.</div>
				<div class="card-body">
					<p class="card-text"><?php echo $db_error; ?></p>
					Sql file: <?php echo $last_sql;?>
					<div>	</div>
					<a class="btn btn-info pull-left" style="margin-top: 10px;" href="index.php" role="button"><i class='fas fa-arrow-alt-circle-left' style='color:white'></i> Cancel</a>
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
			}?>
<?php	} else {
			$current_step="install";
//Graeme PHP 5.3 VIEW
//Grab the PHP version
			$ver_info=phpversion();
			$v2 = preg_replace("/[^0-9\.]+/","",$ver_info);
			$v1=$v2-0;
			$v2=substr($v2,strlen($v1."")+1);
//$v1 is now our PHP	
			if ($v1 >= 5.0 ){
				osc_db_query("DROP VIEW IF EXISTS `carts_united`");
				$result =osc_db_query("CREATE ALGORITHM = UNDEFINED DEFINER = CURRENT_USER SQL SECURITY DEFINER VIEW `carts_united` AS SELECT `cb`.`customers_basket_id` AS `customers_basket_id` , `cb`.`products_id` AS `products_id` , `cb`.`customers_id` AS `customers_id` , `cb`.`customers_basket_date_added` AS `customers_basket_date_added` , `cb`.`customers_basket_quantity` AS `customers_basket_quantity` , `cb`.`discount_id` AS `discount_id`
				FROM `customers_basket` `cb`
				UNION SELECT `tb`.`customers_basket_id` AS `customers_basket_id` , `tb`.`products_id` AS `products_id` , `tb`.`customers_id` AS `customers_id` , `tb`.`customers_basket_date_added` AS `customers_basket_date_added` , `tb`.`customers_basket_quantity` AS `customers_basket_quantity` , `tb`.`discount_id` AS `discount_id`
				FROM `customers_temp_basket` `tb`") ;
			} ?>
			<div class="container">
				<div class="col-md-12 col-sm-12 col-xs-12">			
					<form name="install" action="install.php?step=4" method="post">
						<?php // Check result of the VIEW
						unset($create_view_error);
						unset($create_view_message);
						if (!$result) {// CREATE VIEW HAS FAILED				
							$create_view_error =  mysqli_error() ;
							$create_view_message = $query;			  
							echo osc_draw_hidden_field(create_view_error, $create_view_error);
							echo osc_draw_hidden_field(create_view_message, $create_view_message);
						}
						if($_POST['no_database_updates']!=='Y'){?>
							<p><i class='fas fa-chevron-circle-down'style='color:green'></i><b>The database import was <font color="#117a8b">successful.</font></b></p><?php 
						}
						if(isset($create_view_error)){?>
							<p>However there was an error running the 'Create View' sql command <br>
							- please try running this manually via phpMyAdmin or similar</p>
			<?php 		}
						if($_POST['osconcert_upgrade']=='Y'){//page resubmitted to insert osconcert_upgrade file
							$_POST['osconcert_upgrade']=='';
							$db_error = false;
							osc_db_install($db['DB_DATABASE'], $dir_fs_www_root . "install/osconcert_upgrade.sql");
							if($db_error){?>
								<p>Custom upgrade failed</p>
								<p class="boxme"><?php echo $db_error; ?></p>
								<p>Please contact your software supplier</p>
				<?php 		}
						}	
						if (file_exists($dir_fs_www_root . "install/osconcert_upgrade.sql") && $_POST['osconcert_upgrade']!=='Y'){?>
							<input type="hidden" name="osconcert_upgrade" value="Y">
							<?php ############################# START OF  CUSTOM UPDATE ######################################### ?>			
							<p><b>A custom upgrade appears to be  available for your database.</b></p>
							<p> Click on 'Upgrade' to install it or 'Continue' to <b>skip</b> this stage</p>
			<?php
							echo '<input type="hidden" name="first_install" value="' . $first_install . '">';
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
							<div style="width: 100%; float: left; margin-top: 10px;">
								<div style="width: 50%; float: left;"  align="left">
									<a href="javascript:doAction(1)"><img src="images/button_continue.gif" border="0" alt="Skip"></a>
								</div>
								<div style="width: 50%; float: right;" align="right">
								   <a href="javascript:doAction(2)"><img src="images/button_upgrade.gif" border="0" alt="Continue"></a>
								</div>
							</div>
							<script language="javascript">
								function doAction(type){
									if (type==1){
										document.install.action="install.php?step=4";
									} else {
										document.install.action="install.php?step=3";
									}
									document.install.submit();
								}
							</script>
				<?php 	} else {
				############################# START OF NO CUSTOM UPDATE #########################################
							if(!$db_error){?>		
								<p><b>Please wait a moment</b></p>
					<?php 	}
							echo '<input type="hidden" name="first_install" value="' . $first_install . '">';
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
							if(!$db_error){?>
								<script type="text/javascript">
									function myfunc () {
										doAction(1);
										
									}
									window.onload = myfunc;
								</script>
					<?php 	} ?>					
							<?php
							if (osc_in_array('configure', $HTTP_POST_VARS['install'])) {?>
								<button type="button" class="btn btn-info" style="float: right; margin-top: 10px;" onClick="doAction(1);">Continue <i class='fas fa-arrow-alt-circle-right' style='color:white'></i></button>
					<?php	} else { ?>    
								<button type="button" class="btn btn-info" style="float: right; margin-top: 10px;" onClick="doAction(2);">Continue <i class='fas fa-arrow-alt-circle-right' style='color:white'></i></button>
					<?php	} ?>
							<script language="javascript">
								function doAction(type){
									if (type==1){
										document.install.action="install.php?step=4";
									} else {
										document.install.action="install.php?step=8";
									}
									document.install.submit();
								}
							</script>
				<?php 	} ?>
					</form>
				</div>
			</div>
<?php	}
############################# END OF NO CUSTOM UPDATE ######################################### 
	} ?>

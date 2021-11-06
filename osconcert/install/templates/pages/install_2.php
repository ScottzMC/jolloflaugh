<?php
/* 
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	https://www.osconcert.com

  	Copyright (c) 2021 osConcert 
	
	Released under the GNU General Public License 
*/ 

?>
<?php

//ini_set('memory_limit', '512M');

if (isset($HTTP_POST_VARS['DB_SERVER']) && !empty($HTTP_POST_VARS['DB_SERVER']) && isset($HTTP_POST_VARS['DB_TEST_CONNECTION']) && ($HTTP_POST_VARS['DB_TEST_CONNECTION'] == 'true')) {
    $db = array();
    $db['DB_SERVER'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER']));
    $db['DB_SERVER_USERNAME'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_USERNAME']));
    $db['DB_SERVER_PASSWORD'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_PASSWORD']));
    $db['DB_DATABASE'] = trim(stripslashes($HTTP_POST_VARS['DB_DATABASE']));
    $db_error = false;
    osc_db_connect($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD'],  $db['DB_DATABASE']);

    if ($db_error == false) {
      osc_db_test_create_db_permission($db['DB_DATABASE']);
    }
	
	$current_step="install";
    if ($db_error != false) {?>
		<form name="install" action="install.php?step=2" method="post">
		<div class="container" style="width:800px">
			<div class="row">
			<div class="col-md-12">
			<div class="card border-danger mb-3 mx-auto" style="max-width: 50rem;" id="txr_terms">
				<div class="card-header"><i class='fa fa-times-circle' style='color:red'></i>
				<?php echo TEXT_UNSUCCESSFUL; ?></div>
				<div class="card-body">
					<p class="card-text"><?php echo TEXT_GNU; ?></p>
					<p class="card-text"><?php echo TEXT_THE_ERROR; ?></p>
					<p class="card-text alert-danger"><?php echo $db_error; ?></p>
					<p class="card-text"><?php echo TEXT_REVIEW; ?></p>
					<p class="card-text"><?php echo TEXT_REQUIRE; ?></p>
				</div>
			</div>
			<?php 
					reset($HTTP_POST_VARS);
					foreach($HTTP_POST_VARS as $key => $value) {
						  if (($key != 'x') && ($key != 'y') && ($key != 'DB_TEST_CONNECTION')) 
						  {
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
			<button type="button" class="btn btn-info" style="float: left; margin-top: 10px;" onClick="this.form.submit();"><i class='fas fa-arrow-alt-circle-left' style='color:white'></i> <?php echo TEXT_BACK; ?></button>
			<a class="btn btn-info" style="float: right; margin-top: 10px;" href="index.php" role="button"><i class='fas fa-times-circle' style='color:white'></i> <?php echo TEXT_CANCEL; ?></a>
			</div>
			</div>
			</div>
		</form>
<?php
    } else {
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

		$sql_files=array();
		$installed_sqls=array();
		$version="9_0_0";
		// first check the database existance
		$database_query=osc_db_query("SHOW DATABASES like '" . $db['DB_DATABASE'] . "'");
		if (osc_db_num_rows($database_query)>0)
		{
		$table_found='yes';
			// first sql_queries table exists
			$check_query=osc_db_query("SHOW TABLES FROM " . $db['DB_DATABASE'] . " like 'sql_queries'");
			if (osc_db_num_rows($check_query)>0){
				osc_db_select_db($db['DB_DATABASE']);
				$installed_query=osc_db_query("SELECT file_name from sql_queries order by file_name");
				while($installed_result=mysqli_fetch_array($installed_query)){
					$installed_sqls[$installed_result["file_name"]]=1;
				}
			}else{//no sql_queries table
			$table_found='no';}
		} 
		// first get the last executed sql file
		osc_set_time_limit(5);
		$version_splt=explode("_",$version);
		$current_version=$version;
		while(true){
			$temp_file="osconcert_v" . $current_version;
			if (!file_exists($dir_fs_www_root . "install/" . $temp_file . ".sql")) break;//found the last file that exists
			if (!isset($installed_sqls[$temp_file . ".sql"])){
				$sql_files[]=$temp_file;
			}
			for ($icnt=2;$icnt>=0;$icnt--)
			{
				$version_splt[$icnt]+=1;
				if ($version_splt[$icnt]<=9 || $icnt==0) break;
				$version_splt[$icnt]=0;
			}
			$current_version=join("_",$version_splt);
		}
?>
	<script>
		function do_action(step){
				alert(step);
			if (document.install.optReinstall && document.install.optReinstall[0].checked){
				document.install.action="install.php?step=3";
			} 
				else if (document.install.optReinstall && document.install.optReinstall[1].checked){
				document.install.action="install.php?step=3";
			}
			
			else {
				document.install.action="install.php?step="+step;
			}
		}
	</script>
			<div class="container" style="width:800px">
			<div class="row">
					<div class="col-md-12">
					<ul class="list-group list-group-horizontal justify-content-center">
					  <li class="list-group-item"><?php echo TEXT_CT; ?></li>
					  <li class="list-group-item"><?php echo TEXT_IO; ?></li>
					  <li class="list-group-item"><strong><?php echo TEXT_DI; ?></strong></li>
					  <li class="list-group-item"><?php echo TEXT_TC; ?></li>
					</ul> <br>
					</div>
			</div>
			<div class="row">
					<div class="col-md-12">
					<div class="progress progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">50%</div>
					</div><br>
			</div>
			<div class="row">
			<div class="col-md-12"><br>
					<form name="install" action="install.php?step=3" method="post">
				<h3><i class='fa fa-check' style='color:green'></i>
				<?php echo TEXT_SUCCESS; ?></h3>
				<?php 
				$continue=3;
				if (count($sql_files)>0 && $table_found=='no') 
				{ //empty database - new install?>
					<ul>	
						<li><?php echo TEXT_PLEASE_CONTINUE; ?></li>
						<li><?php echo TEXT_BACKUP; ?></li>
						<li><?php echo TEXT_IMPORTANT; ?></li>
					</ul>
					<p><?php echo TEXT_FILES; ?><br></p>
					<font color="#117a8b"><?php echo $dir_fs_www_root . "install/"; ?></font>
					<p>
						<?php  
							$splt_arr=array();
							$col=1;
							$row=1;
							for ($icnt=0,$n=count($sql_files);$icnt<$n;$icnt++)
							{
								$splt_arr[$row][$col]=$sql_files[$icnt];
								if (($icnt+1)>1 && ($icnt+1) % 10==0) 
								{
									$row=1;
									$col++;
								} else {
									$row++;
								}
							}
							if(count($sql_files>0))
							{
								echo '<div><div>'.TEXT_DB_UPGRADE.'</div></div>';
								}

							for ($icnt=1,$n=count($splt_arr);$icnt<=$n;$icnt++)
							{
									echo '<div>';
									for ($jcnt=1,$n1=count($splt_arr[$icnt]);$jcnt<=$n1;$jcnt++)
									{
											echo '<div ' . ($jcnt>1?"style='padding-left:20px;'":'') .  '>' . $splt_arr[$icnt][$jcnt] . '.sql</div>';
									}
									echo '</div>';
							}
						?>
					</p>
					<?php 	} elseif (count($sql_files)>0 && $table_found=='yes') 
					{ ?>
					<p>
						<?php echo TEXT_DB_UPGRADE; ?><br>
						<?php echo TEXT_CONSIDER; ?><br>
						<?php echo TEXT_IMPORTANT; ?>
					</p>
					<p><?php echo TEXT_LOCATED; ?><br><br>
						<font color="#117a8b"><?php echo $dir_fs_www_root . "install/"; ?></font>
					</p>
					<?php  
						$splt_arr=array();
						$col=1;
						$row=1;
						for ($icnt=0,$n=count($sql_files);$icnt<$n;$icnt++)
						{
							$splt_arr[$row][$col]=$sql_files[$icnt];
							if (($icnt+1)>1 && ($icnt+1) % 10==0) 
							{
								$row=1;
								$col++;
							} else {
								$row++;
							}
						}
						if(count($sql_files>0)){echo '<div><div>'.TEXT_LOCATED2.'</div></div>';}
						for ($icnt=1,$n=count($splt_arr);$icnt<=$n;$icnt++){
							echo '<div>';
							for ($jcnt=1,$n1=count($splt_arr[$icnt]);$jcnt<=$n1;$jcnt++){
								echo '<div ' . ($jcnt>1?"style='padding-left:20px;'":'') .  '>' . $splt_arr[$icnt][$jcnt] . '.sql</div>';
							}
							echo '</div>';
						}
				} else { 
						if (osc_in_array('configure', $HTTP_POST_VARS['install'])) {
							$continue=3;
						} else{
							$continue=8;
						}
					?>
						<p><?php echo TEXT_UPTODATE; ?></p>
						<p><?php echo TEXT_REINSTALL; ?> <input type="radio" name="optReinstall" value="Y" style="border:0"/>&nbsp;<?php echo TEXT_YES; ?>&nbsp;<input type="radio" name="optReinstall" value="N" checked style="border:0">&nbsp;<?php echo TEXT_NO; ?>&nbsp;</p>
						<p><?php echo TEXT_PROCEED; ?></p>
						<input type="hidden" name="no_database_updates" value="Y">
					<?php 	}
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
				<button type="button" class="btn btn-info" style="float: right; margin-top: 10px;" onClick="this.form.submit();"><?php echo TEXT_CONTINUE; ?><i class='fas fa-arrow-alt-circle-right' style='color:white'></i></button>
			</form>
			</div>
			</div>
			</div><br>
	

<?php
    }
} else {
?>
		<div class="container" style="width:800px">
		<div class="row">
		<div class="col-md-12">
		<ul class="list-group list-group-horizontal justify-content-center">

					  <li class="list-group-item"><?php echo TEXT_CT; ?></li>
					  <li class="list-group-item"><?php echo TEXT_IO; ?></li>
					  <li class="list-group-item"><strong><?php echo TEXT_DI; ?></strong></li>
					  <li class="list-group-item"><?php echo TEXT_TC; ?></li>
					</ul><br> 
		</div>
		</div>
		<div class="row">
		<div class="col-md-12">
		<div class="progress progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">25%</div>
		</div><br>
		</div>

		<div class="row">
		<div class="col-md-12">
		<form name="install" action="install.php?step=2" method="post">
			<h3 class="text-center text-info"><?php echo TEXT_ENTER; ?></h3>      
			<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">   		
				<div style="width: 30%; vertical-align: text-top; float: left;"><?php echo TEXT_DBS; ?></div>
				<div style="width: 70%; float: left;">
					<?php echo osc_draw_input_field('DB_SERVER'); ?>
					<?php $tooltip_ip = TEXT_SERVER; ?>
					<i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_ip;?>">
						<?php echo TEXT_HOSTNAME; ?>
					</i>
				</div>
			</div>

			<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
				 <div style="width: 30%; vertical-align: text-top; float: left;" ><?php echo TEXT_USERNAME; ?></div>
				 <div style="width: 70%; float: left;">
					<?php echo osc_draw_input_field('DB_SERVER_USERNAME'); ?>
					<?php $tooltip_username = TEXT_USERNAME_USED; ?>
					<i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_username;?>">
					<?php echo TEXT_DB; ?>
					</i>
				 </div>    
			</div>

			<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
				<div style="width: 30%; vertical-align: text-top; float: left;" ><?php echo TEXT_PASSWORD; ?></div>
				<div style="width: 70%; float: left;">
					<?php echo osc_draw_password_field('DB_SERVER_PASSWORD'); ?>
					<?php $tooltip_pass = TEXT_USER; ?>
					<i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_pass;?>">
					<?php echo TEXT_DB_PASSWORD; ?>
					</i>
				</div>    
			</div>

			<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
				<div style="width: 30%; vertical-align: text-top; float: left;" ><?php echo TEXT_DB_NAME; ?></div>
				<div style="width: 70%; float: left;">
					<?php echo osc_draw_input_field('DB_DATABASE'); ?>
					<?php $tooltip_db = TEXT_DB_NAME_DESC; ?>
					<i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_db;?>">
					<?php echo TEXT_DB_NAME; ?>
					</i>
				</div>    
			</div>

			<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
				<div style="width: 30%; vertical-align: text-top; float: left;" ><?php echo TEXT_SS; ?></div>
				<div style="width: 70%; float: left;">

					<div class="form-check-inline">
						<?php echo osc_draw_radio_field('STORE_SESSIONS', 'mysql', true); ?><?php echo TEXT_DBS; ?>
					</div>				
					<?php $tooltip_ss = TEXT_NOTE_SECURITY; ?>
					<i class="fa fa-question-circle" style="color:#117a8b" data-toggle="tooltip" data-placement="bottom" title="<?php echo $tooltip_ss;?>">
					<?php echo TEXT_STORE_USER; ?>
					</i>
				</div>
			</div>
			<a class="btn btn-info" style="float: left; margin-top: 10px;" href="index.php" role="button"><i class='fas fa-arrow-alt-circle-left' style='color:white'></i> <?php echo TEXT_CANCEL; ?></a>
			<button type="button" class="btn btn-info" style="float: right; margin-top: 10px;" onClick="this.form.submit();"><?php echo TEXT_CONTINUE; ?> <i class='fas fa-arrow-alt-circle-right' style='color:white'></i></button>
			<?php
			  reset($HTTP_POST_VARS);
			  //while (list($key, $value) = each($HTTP_POST_VARS)) {
			foreach($HTTP_POST_VARS as $key => $value) {
				if (($key != 'x') && ($key != 'y') && ($key != 'DB_SERVER') && ($key != 'DB_SERVER_USERNAME') && ($key != 'DB_SERVER_PASSWORD') && ($key != 'DB_DATABASE') && ($key != 'STORE_SESSIONS') && ($key != 'DB_TEST_CONNECTION')) {
				  if (is_array($value)) {
					for ($i=0; $i<sizeof($value); $i++) {
					  echo osc_draw_hidden_field($key . '[]', $value[$i]);
					}
				  } else {
					echo osc_draw_hidden_field($key, $value);
				  }
				}
			  }
			
			  echo osc_draw_hidden_field('DB_TEST_CONNECTION', 'true');
			?>
		</form>
		</div>
		</div>
		</div>
<br>
<?php
  }
?>
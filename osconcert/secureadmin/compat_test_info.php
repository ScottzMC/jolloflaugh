<?php
/*

  

  Freeway eCommerce from ZacWare
  http://www.openfreeway.org

  Copyright 2007 ZacWare Pty. Ltd

  Released under the GNU General Public License
*/
// Set flag that this is a parent file
	defined('_FEXEC') or die();
	
	###########################################
	#
	###########################################
	$action=$FREQUEST->getvalue('action');
	$last_test="";
	
	if ($action=="testemail")
		{ 
			$send_email=$FREQUEST->postvalue('email_address');
			
			$subject = STORE_NAME . " Install - Test Email";
			$content = "Installer Compatibility Test Created on " . date('Y-m-d H:i:s') . "\n\n";
			$headers = "From: ".STORE_OWNER_EMAIL_ADDRESS."\n";
			$headers .= "Return-Path: ".STORE_OWNER_EMAIL_ADDRESS."\n";
			$headers .= "X-Mailer: osConcert \n"; 
			$sent=@mail($send_email, $subject, $content, $headers);
			if ($sent && trim($send_email)!="")
			{
				$last_test="<font color='blue'>Test Email Sent succesfully to ". $send_email ."</font>";
			} else {
				$last_test="<font color='red'>Failed to Send Test Email to '" . $send_email. "'</font>";
			}
		}
	if (!isset($require_load)){
		  error_reporting(E_ALL ^ E_NOTICE);
		  
		  if (!function_exists('ini_get')) {
			exit('FATAL ERROR: function ini_get does not exist!');	
		  }
		  ini_get('register_globals') or exit('FATAL ERROR: register_globals is disabled in php.ini, please enable it!');
		  if (ini_get("open_basedir")) {
				exit('FATAL ERROR: open_basedir enabled in php.ini, please disable it!');
		  }
		   $include_path=ini_get("include_path");
			if (!(strpos($include_path,".;")!==false || strpos($include_path,".:")!==false)){
				exit('FATAL ERROR: current dir not found in include_path. Please add it!');
			}
		ini_get('display_errors') or exit('FATAL ERROR: display_errors is disabled in php.ini, please enable it!');
		
		  $www_location = 'http://' . $FREQUEST->servervalue('HTTP_HOST') . $FREQUEST->servervalue('SCRIPT_NAME');
		  $www_location = substr($www_location, 0, strpos($www_location, 'install_compat.php'));

		  $action=$FREQUEST->getvalue('action');

 		  $script_filename = $FREQUEST->servervalue('SCRIPT_FILENAME');
		  

		  $script_filename = str_replace('\\', '/', $script_filename);
		  $script_filename = str_replace('//', '/', $script_filename);
		  $dir_fs_www_root = dirname($script_filename) . "/";
		    $DB_SERVER=$DB_SERVER_USERNAME=$DB_SERVER_PASSWORD='';
  		
		$display_mode="all";
		// if ($action=="testemail")
		// {
			// $send_email=$FREQUEST->postvalue('email_address');
			
			// $subject = STORE_NAME . " Install - Test Email";
			// $content = "Installer Compatibility Test Created on " . date('Y-m-d H:i:s') . "\n\n";
			// $headers = "From: shop@osconcert.com\n";
			// $headers .= "Return-Path: shop@osconcert.com\n";
			// $headers .= "X-Mailer: osConcert \n"; 
			// $sent=@mail($send_email, $subject, $content, $headers);
			// if ($sent && trim($send_email)!="")
			// {
				// $last_test="<font color='blue'>Test Email Sent succesfully to ". $send_email ."</font>";
			// } else {
				// $last_test="<font color='red'>Failed to Send Test Email to '" . $send_email. "'</font>";
			// }
		// }
		if (!$db_check){
			$DB_SERVER=$FREQUEST->postvalue('DB_SERVER','string','localhost');
			$DB_USERNAME=$FREQUEST->postvalue('DB_SERVER_USERNAME');
			$DB_PASSWORD=$FREQUEST->postvalue('DB_SERVER_PASSWORD');
			$server_exists=false;
			if (function_exists("mysqli_connect")){
				$connect=@mysqli_connect($DB_SERVER,$DB_USERNAME,$DB_PASSWORD);
				if ($connect) {
					$mysql_text='<b>MySqli detected ';
					$version_query=@mysqli_query("select version()",$connect);
					$version_result=@mysqli_fetch_array($version_query);
					$mysql_text.=$version_result[0] . ' </b>';
					$mysql_image=$right_image;
					mysqli_close($connect);
				}
			}
		}
		$show_db_params=true;
		$compat_link="compat_test_info.php?action=testemail";
		$current_link="compat_test_info.php";
		$compat_mysql_link="compat_test_info.php?action=testdb";
		$show_continue=false;
	}

	$error_image='<img src="images/setting_error.gif" border="0">';
	$warning_image='<img src="images/setting_warning.gif" border="0">';
	$notice_image='<img src="images/setting_notice.gif" border="0">';
	$right_image='<img src="images/setting_right.gif" border="0">';

		$except=0;
		$err=0;
		$war=0;
		clearstatcache();
if (!isset($require_load)){
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head><title>Installation compatability test</title></head>
	<body marginwidth="0" marginheight="0" topmargin="5" bottommargin="5" leftmargin="5" rightmargin="5">
	<table border="0" cellpadding="2" cellspacing="0" align="center" width="60%">
		<tr>
			<td>
<?php } ?>
		<style>
			td {vertical-align:top;}
			.heading{font-family:Verdana, Arial, Helvetica, sans-serif;font-size:15pt;font-weight:bold}
			.subHeading{font-family:Verdana, Arial, Helvetica, sans-serif;font-size:13pt;font-weight:bold}
			.title{font-family:Verdana, Arial, Helvetica, sans-serif;font-size:11pt;font-weight:bold}
			.content{font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9pt;font-weight:normal}
			.contentGray{font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9pt;font-weight:normal;color:#999999}
			.contentB{font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9pt;font-weight:bold}
		</style>
		
		<table border="0" cellpadding="2" cellspacing="0" align="center" width="95%">

			<tr>
				<td colspan="2" class="heading">
					Installation Compatibility test
				</td>
			<tr>
				<td colspan="2" class="content">
					<span class="subHeading">Legend</span><br /><br />
					<span class="contentGray"><b>Just information</b> This is a simple message with some information about something.</span>
				</td>
			</tr>
			<tr>
				<td width="20"><?php echo $right_image;?></td>
				<td class="contentGray"><b>Check was successful</b> Indicates that something was checked and returned an expected result.</td>
			</tr>
			<tr>
				<td><?php echo $notice_image;?></td>
				<td class="contentGray">
				<b>Notice!</b> Indicates that something is important to be aware of.<br />
				This does not indicate an error.
				</td>
			</tr>
			<tr>
				<td><?php echo $warning_image;?></td>
				<td class="contentGray">
				 <b>Warning!</b> Indicates that something may very well cause trouble and you should definitely look into it before proceeding.<br/>
				This indicates a potential error.
				</td>
			</tr>
			<tr>
				<td><?php echo $error_image;?></td>
				<td class="contentGray">
				 <b>Error!</b> Indicates that something is definitely wrong and that osConcert will most likely not perform as expected if this problem is not solved.<br />
				This indicates an actual error.
				</td>
			</tr>
			<?php if ($display_mode=="all" || $display_mode!="dir") { 
			?>
			<tr>
				<td colspan="2">
				<table border="0" cellpadding="0" cellspacing="2">
					<tr>
						<td class="title" colspan="2">General</td>
					</tr>
					<tr>
						<td height="3">
						</td>
					</tr>
					<tr>
						<td class="content">OS detected</td><td class="contentB">&nbsp;<?php echo PHP_OS;?></td>
					</tr>
					<tr>
						<td class="content">UNIX/CGI detected</td><td class="contentB">&nbsp;<?php echo $FREQUEST->servervalue('GATEWAY_INTERFACE','string','No');?></td>
					</tr>
					<tr>
						<td class="content">PATH_thisScript</td><td class="contentB">&nbsp;<?php echo $www_location . "install";?></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="15" colspan="2">&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="2" class="title">
					MySQLi Database
				</td>
			</td>
			<tr>
				<td colspan="2" class="content">
					<i>osConcert requires MySQLi - PHP > 5.0 will have this</i>
				</td>
			</tr>
			<?php
				if ($server_exists) {
					$image=$right_image;
				} else {
					$image=$error_image;
					$err++;
				}
			?>
			<tr>
				<td class="content"><?php echo $image;?></td>
				<td class="content"><?php echo $mysql_text;?></td>
			</tr>
			<tr>
				<td height="5" colspan="2">&nbsp;
				</td>
			</tr>
			<?php 
			if ($show_db_params && !$server_exists) {?>
			<form action="<?php echo $compat_mysql_link;?>" method="post">
			<tr>
				<td colspan="2">
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td>Database Server:  </td>
						<td>&nbsp;&nbsp;<?php echo osc_draw_input_field('DB_SERVER'); ?></td>
					</tr>
					<tr>
						<td>Database username:  </td>
						<td>&nbsp;&nbsp;<?php echo osc_draw_input_field('DB_SERVER_USERNAME'); ?></td>
					</tr>
					<tr>
						<td>Password:  </td>
						<td>&nbsp;&nbsp;<?php echo osc_draw_input_field('DB_SERVER_PASSWORD'); ?><br><br></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" value="Test Mysql Server"><br><br></td>
					</tr>
				</table>
				</td>
			</tr>
			</form>
			<?php } 
			?>

			<tr>
				<td class="content"><?php echo $notice_image;?></td>
				<td class="content">
				<?php 
						if (isset($_ENV["SERVER_SOFTWARE"]))
							echo $_ENV["SERVER_SOFTWARE"];
						else if (($FREQUEST->servervalue('SERVER_SOFTWARE'))!='')
							echo $FREQUEST->servervalue('SERVER_SOFTWARE');
						else
							echo 'UNKNOWN';
				 ?></td>
			</tr>
			<tr>
				<td height="15" colspan="2">&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="2" class="title">
					PHP
				</td>
			</td>
			<tr>
				<td colspan="2" class="content">
					<i>osConcert will only work with PHP versions 5 and above.</i>
				</td>
			</tr>
			<?php
				$text=" <b>is compatible</b>";
					$ver_info=phpversion();
					$v2 = preg_replace("/[^0-9\.]+/","",$ver_info);
					$v1=$v2-0;
					$v2=substr($v2,strlen($v1."")+1);
					if ($v1>5){
						$text=$ver_info . $text;
						$image=$right_image;
					} else {
						$text=$ver_info . " <b>is not compatible</b>";
						$image=$error_image;
						$err++;
					}
			?>
			<tr>
				<td class="content"><?php echo $image;?></td>
				<td class="content"><?php echo $text;?></td>
			</tr>
			<tr>
				<td height="15" colspan="2">&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="2" class="title">
					Directories Writable
				</td>
			</td>
			<tr>
				<td colspan="2" class="content">
					<i>These directories should be writeable</i>
				</td>
			</tr>
			<tr>
				<td height="3" colspan="2">
				</td>
			</tr>
			<?php 
				$dirs=array("images","images/big","images/small","admin/images","admin/backups");
				for ($icnt=0,$n=count($dirs);$icnt<$n;$icnt++){
					$check_file=$dir_fs_www_root . $dirs[$icnt];
					if (!file_exists($check_file) || !is_writable($check_file))
					{
						$image=$warning_image;
						$war++;
						$text=" <b>not writable</b>";
					} else {
						$image=$right_image;
						$text=" <b>writable</b>";
					}
			?>
				<tr>
					<td class="content"><?php echo $image;?></td>
					<td class="content"><?php echo $dirs[$icnt] . $text;?></td>
				</tr>
			<?php } ?>
			<tr>
				<td height="15" colspan="2">&nbsp;
				</td>
			</tr>
		<?php
			} // display mode
			if ($display_mode=="all" || $display_mode=="dir") 
			{
		?>
			<tr>
				<td colspan="2" class="title">
					After Install remove install directory
				</td>
			</td>
			<tr>
				<td height="3" colspan="2">
				</td>
			</tr>
			<?php
				if (file_exists($dir_fs_www_root . "install"))
				{
					$err++;
					$except++;
					$image=$error_image;
				} else {
					$image=$right_image;
				}
			?>
				<tr>
					<td class="content"><?php echo $image;?></td>
					<td class="content">install <b>Directory still exists</b></td>
				</tr>
				<tr>
					<td height="15" colspan="2">&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan="2" class="title">
						Config Directory Security
					</td>
				</td>
				<tr>
					<td height="3">
					</td>
				</tr>
				<?php
					$files=array("includes/configure.php","admin/includes/configure.php");
					for ($icnt=0;$icnt<=1;$icnt++){
						if (is_writable($dir_fs_www_root . $files[$icnt])){
							$err++;
							$except++;
							$image=$error_image;
							$text=" <b>is writable</b>";
						} else {
							$image=$right_image;
							$text=" <b>is not writable</b>";
						}
					
				?>
				<tr>
					<td class="content"><?php echo $image;?></td>
					<td class="content"><?php echo $files[$icnt] . $text;?></td>
				</tr>
				<?php } ?>
				<tr>
					<td height="15" colspan="2">&nbsp;
					</td>
				</tr>
				<?php } // $display_mode 
			if ($display_mode=="all" || $display_mode!="dir") {
				?>
				<tr>
					<td colspan="2" class="title">
						php.ini Configuration
					</td>
				</td>
				<tr>
					<td height="3" colspan="2">
					</td>
				</tr>
			<?php 
				$php_version=substr(PHP_VERSION,0,1);
				if ($php_version>=5){
					if (ini_get("register_long_arrays")){
						$image=$right_image;
					} else {
						$war++;
						$image=$warning_image;
					}
					//$result[]=array("image"=>$image,"text"=>"<b>register_long_arrays</b>");
				} else {
					if (ini_get("register_globals")){
						$image=$right_image;
					} else {
						$war++;
						$image=$warning_image;
					}
					$result[]=array("image"=>$image,"text"=>"register_globals");
				}
				if (strpos($include_path,";.;")!==false){
					$result[]=array("image"=>$right_image,"text"=>"<b>Current dir in include path</b>");					
				} else {
					$war++;
					$result[]=array("image"=>$warning_image,"text"=>"<b>Current dir in include path</b>");
				}

				$result[]=array("image"=>$notice_image,"text"=>"<b>File uploads allowed <br> Max Upload filesize too large?</b> <i>upload_max_filesize=2M</i>");
				$mem_limit=ini_get("memory_limit");

				if ($mem_limit=='' || (get_bytes($mem_limit)>=get_bytes("64M"))){
					$image=$right_image;
				} else {
					$war++;
					$image=$warning_image;
				}
				$result[]=array("image"=>$image,"text"=>"<b>Memory Limit </b> <i>memory_limit=64M</i>");
				if (ini_get("max_execution_time")>=30){
					$image=$right_image;
				} else {
					$war++;
					$image=$warning_image;
				}
				$result[]=array("image"=>$image,"text"=>"<b>Max Execution Time</b> <i>max_execution_time=30</i>");
				if (ini_get("disable_functions")!=""){
					$war++;
					$image=$warning_image;
				} else {
					$image=$right_image;
				}
				$result[]=array("image"=>$image,"text"=>"<b>Disable Functions</b> <i>disable_functions=none</i>");
				
				$smtp=$sendmail="";
				$sendmail=ini_get("sendmail_path");
				if ($sendmail=="") $smtp=ini_get("SMTP");
				$mail_test=true;
				if ($sendmail!=""){
					$result[]=array("image"=>$right_image,"text"=>"<b>Sendmail OK</b> <i>sendmail_path=/usr/sbin/sendmail -t -i</i>");
					$mail_test=true;
				} else if ($smtp!=""){
					$result[]=array("image"=>$right_image,"text"=>"<b>SMTP OK</b> <i>SMTP=localhost smtp_port = 2535</i>");
					$mail_test=true;
				} else {
					$err++;
					$result[]=array("image"=>$error_image,"text"=>"<b>Sendmail OK</b> <i>sendmail_path=/usr/sbin/sendmail -t -i</i>");
				}
				for ($icnt=0,$n=count($result);$icnt<$n;$icnt++){
			?>
				<tr>
					<td><?php echo $result[$icnt]["image"];?></td>
					<td class="content"><?php echo $result[$icnt]["text"];?></td>
				</tr>
			<?php
				}
				$result=array();
				if ($mail_test) {
			?>
				<tr>
					<td colspan="2" class="content">
						<form action="<?php echo $compat_link;?>" method="post">
							Check the mail() function by entering an email address to send to and clicking the "Test Mail" button.<br/><br />
							<input type="text" name="email_address" size="30"/>&nbsp;<input type="submit" value="Send Test Email"><br /><br />
							The email is sent from (and the return path set to): <?php echo STORE_OWNER_EMAIL_ADDRESS ;?>. <br />
							Some mail servers won't send the mail if the host of the return-path is not resolved correctly
						</form>	
					</td>
				</tr>
			<?php if ($last_test!="") { ?>
			<tr>
				<td class="content" colspan="2"><?php echo $last_test;?></td>
			</tr>
			<?php } ?>
			<?php 
				}
				$result[]=array();

				$image=$error_image;
				if (!ini_get("safe_mode")){
					$image=$right_image;
				} else {
					$err++;
				}
				$result[]=array("image"=>$image,"text"=>"<b>safe_mode: Off</b>");
				
				$image=$error_image;
				if (!ini_get("sql.safe_mode")){
					$image=$right_image;
				} else {
					$err++;
				}
				$result[]=array("image"=>$image,"text"=>"<b>sql.safe_mode: Off</b>");

				$image=$error_image;
			    if ($open_basedir=='' || strpos($open_basedir,";" .$dir_fs_www_root_top .";")!==true) {
					$image=$right_image;
				} else {
					$err++;
				}
				$result[]=array("image"=>$image,"text"=>"<b>open_basedir: Off</b>");
				for ($icnt=0,$n=count($result);$icnt<$n;$icnt++){
			?>
				<tr>
					<td><?php echo $result[$icnt]["image"];?></td>
					<td class="content"><?php echo $result[$icnt]["text"];?></td>
				</tr>
			<?php
				}
			?>
			<tr>
				<td height="15" colspan="2">&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="2" class="title">
					GDLib
				</td>
			</td>
			<tr>
				<td height="3" colspan="2">
				</td>
			</tr>
			<?php
				$result=array();
				$gd_info=get_gd_info();
				$image=$error_image;
				if ($gd_info["GD Version"]!=""){
					$image=$right_image;
				}
				$result[]=array("image"=>$image,"text"=>"<b>GDLib found</b>");
				$image=$error_image;
				if ($gd_info["PNG Support"]==1){
					$image=$right_image;
				} else {
					$err++;
				}
				$result[]=array("image"=>$image,"text"=>"<b>PNG supported</b>");

				$image=$error_image;
				if ($gd_info["JPG Support"]==1){
					$image=$right_image;
				} else {
					//$err++;
					//jpg is always supported?
					$image=$right_image;
				}
				$result[]=array("image"=>$image,"text"=>"<b>JPG supported</b>");

				$image=$error_image;
				if ($gd_info["GIF Create Support"]==1){
					$image=$right_image;
				} else {
					$err++;
				}
				$result[]=array("image"=>$image,"text"=>"<b>GIF supported</b>");

				for ($icnt=0,$n=count($result);$icnt<$n;$icnt++){
			?>
				<tr>
					<td><?php echo $result[$icnt]["image"];?></td>
					<td class="content"><?php echo $result[$icnt]["text"];?></td>
				</tr>
			<?php
				}
			?>
			<tr>
				<td><?php echo $notice_image;?></td>
				<td class="content"><b>GIF / PNG issues</b> You can choose between generating GIF or PNG files, as your GDLib supports both<br /><br />
				</td>
				</tr>
			</tr>
			
					<?php 
			} // $display_mode
					if ($display_mode=="dir") $err=0;
						if ($err<=0) { 
							if ($show_continue) {
					?>
						<tr>
							<td colspan="2" class="content" align="center">
								<br/>
								<form action="install.php<?php echo ($display_mode=="dir"?"?step=9&action=complete":'');?>" method="post">
								<?php 
										echo 	osc_draw_hidden_field('DB_SERVER',$DB_SERVER) .
												osc_draw_hidden_field('DB_SERVER_USERNAME',$DB_SERVER_USERNAME) .
												osc_draw_hidden_field('DB_SERVER_PASSWORD',$DB_SERVER_PASSWORD);
								?>
								<input type="hidden" name="compat_test_pass" value="1"/>
								<input type="image" src="images/button_continue.gif" border="0" alt="Continue" class="imageButton">
								</form>
							</td>
						</tr>
					<?php
							}
						} else { ?>
						<tr>
							<td colspan="2" class="content" align="left">
							<hr />
							<table border="0" cellpadding="0" cellspacing="0" border="0" width="100%">
							<?php if ($show_continue) { ?>
								<tr>
									<td>
									<?php
											echo '<br><b>Installation cannot continue</b><br>';
											if ($err>0)	echo '<font color="red">Found ' . $err . " error(s)</font><br>";
											if ($war>0)	echo '<font color="red">Found ' . $war . " warning(s)</font>";
									?>
									</td>
								</tr>
							<?php } ?>
								<tr>
									<td align="center"><a href="<?php echo $current_link;?>"><img src="images/button_retry.gif" border="0"></a></td>
								</td>
							</table>
						</td>
					</tr>
					<?php 
					} // $show_continue
					?>
		</table>
<?php 
	if (!isset($require_load)){
	?>
	</td>
	</tr>
	</table>
	</body>
	</html>
<?php
	}
?>
<?php
	
	function get_gd_info() {
	   if(function_exists("gd_info")) return gd_info();
       $array = Array(
                      "GD Version" => "",
                      "FreeType Support" => 0,
                      "FreeType Support" => 0,
                      "FreeType Linkage" => "",
                      "T1Lib Support" => 0,
                      "GIF Read Support" => 0,
                      "GIF Create Support" => 0,
                      "JPG Support" => 0,
                      "PNG Support" => 0,
                      "WBMP Support" => 0,
                      "XBM Support" => 0,
                     );
       $gif_support = 0;
       ob_start();
       eval("phpinfo();");
       $info = ob_get_contents();
       ob_end_clean();
     
       foreach(explode("\n", $info) as $line) {
           if(strpos($line, "GD Version")!==false)
               $array["GD Version"] = trim(str_replace("GD Version", "", strip_tags($line)));
           if(strpos($line, "FreeType Support")!==false)
               $array["FreeType Support"] = trim(str_replace("FreeType Support", "", strip_tags($line)));
           if(strpos($line, "FreeType Linkage")!==false)
               $array["FreeType Linkage"] = trim(str_replace("FreeType Linkage", "", strip_tags($line)));
           if(strpos($line, "T1Lib Support")!==false)
               $array["T1Lib Support"] = trim(str_replace("T1Lib Support", "", strip_tags($line)));
           if(strpos($line, "GIF Read Support")!==false)
               $array["GIF Read Support"] = trim(str_replace("GIF Read Support", "", strip_tags($line)));
           if(strpos($line, "GIF Create Support")!==false)
               $array["GIF Create Support"] = trim(str_replace("GIF Create Support", "", strip_tags($line)));
           if(strpos($line, "GIF Support")!==false)
               $gif_support = trim(str_replace("GIF Support", "", strip_tags($line)));
           if(strpos($line, "JPG Support")!==false)
               $array["JPG Support"] = trim(str_replace("JPG Support", "", strip_tags($line)));
           if(strpos($line, "PNG Support")!==false)
               $array["PNG Support"] = trim(str_replace("PNG Support", "", strip_tags($line)));
           if(strpos($line, "WBMP Support")!==false)
               $array["WBMP Support"] = trim(str_replace("WBMP Support", "", strip_tags($line)));
           if(strpos($line, "XBM Support")!==false)
               $array["XBM Support"] = trim(str_replace("XBM Support", "", strip_tags($line)));
       }
       
       if($gif_support==="enabled") {
           $array["GIF Read Support"]   = 1;
           $array["GIF Create Support"] = 1;
       }
       if($array["FreeType Support"]==="enabled"){
           $array["FreeType Support"] = 1;    }

       if($array["T1Lib Support"]==="enabled")
           $array["T1Lib Support"] = 1;     
      
       if($array["GIF Read Support"]==="enabled"){
           $array["GIF Read Support"] = 1;    }

       if($array["GIF Create Support"]==="enabled")
           $array["GIF Create Support"] = 1;     
       if($array["JPG Support"]==="enabled")
           $array["JPG Support"] = 1;
           
       if($array["PNG Support"]==="enabled")
           $array["PNG Support"] = 1;
           
       if($array["WBMP Support"]==="enabled")
           $array["WBMP Support"] = 1;
           
       if($array["XBM Support"]==="enabled")
           $array["XBM Support"] = 1;
       
       return $array;
   }
   function mysql_version(){
       ob_start();
       eval("phpinfo();");
       $info = ob_get_contents();
       ob_end_clean();
	   $start_pos=strpos($info,"MySQL Support");
	   if ($start_pos===false) return "";
	   $end_pos=strpos($info,"</table>",$start_pos);
	   $content=strip_tags(substr($info,$start_pos,$end_pos-$start_pos));
	   $splt_element=preg_split("/Client API version/",$content);
	   $version=preg_split("/ /",trim($splt_element[1]));
	   return $version[0];
   }

	function get_bytes($val) {
	   $val = trim($val);
	   $last = strtolower($val{strlen($val)-1});
	   switch($last) {
		   // The 'G' modifier is available since PHP 5.1.0
		   case 'g':
			   $val *= 1024;
		   case 'm':
			   $val *= 1024;
		   case 'k':
			   $val *= 1024;
	   }
	
	   return $val;
	}
?>
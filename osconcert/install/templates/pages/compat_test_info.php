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
	if (!isset($require_load)){
		  error_reporting(E_ALL ^ E_NOTICE);
		  
		  if (!function_exists('ini_get')) {
			exit('FATAL ERROR: function ini_get does not exist!');	
		  }
		  //ini_get('register_globals') or exit('FATAL ERROR: register_globals is disabled in php.ini, please enable it!');
		  if (ini_get("open_basedir")) {
				exit('FATAL ERROR: open_basedir enabled in php.ini, please disable it!');
		  }
		   $include_path=ini_get("include_path");
			if (!(strpos($include_path,".;")!==false || strpos($include_path,".:")!==false)){
				exit('FATAL ERROR: current dir not found in include_path. Please add it!');
			}
		ini_get('display_errors') or exit('FATAL ERROR: display_errors is disabled in php.ini, please enable it!');
		
		  $www_location = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER["SCRIPT_NAME"];
		  $www_location = substr($www_location, 0, strpos($www_location, 'install_compat.php'));

		  $action=(isset($HTTP_GET_VARS["action"])?$HTTP_GET_VARS["action"]:'');

 		  $script_filename = $_SERVER["SCRIPT_FILENAME"];
		  

		  $script_filename = str_replace('\\', '/', $script_filename);
		  $script_filename = str_replace('//', '/', $script_filename);
		  $dir_fs_www_root = dirname($script_filename) . "/";
		    $DB_SERVER=$DB_SERVER_USERNAME=$DB_SERVER_PASSWORD='';
  		$last_test="";
		$display_mode="all";
		if ($action=="testemail"){
			$send_email=$HTTP_POST_VARS["email_address"];
			
			$subject = STORE_NAME . " Install - Test Email";
			$content = "Installer Compatibility Test Created on " . date('Y-m-d H:i:s') . "\n\n";
			$headers = "From: webmaster@osconcert.com\n";
			$headers .= "Return-Path: webmaster@osconcert.com\n";
			$headers .= "X-Mailer: osConcert \n"; 
			$sent=@mail($send_email, $subject, $content, $headers);
			if ($sent && trim($send_email)!=""){
				$last_test="<font color='blue'>Test Email Sent succesfully to ". $send_email ."</font>";
			} else {
				$last_test="<font color='red'>Failed to Send Test Email to '" . $send_email. "'</font>";
			}
		}
		if (!$db_check){
			$DB_SERVER=isset($HTTP_POST_VARS["DB_SERVER"])?$HTTP_POST_VARS["DB_SERVER"]:'localhost';
			$DB_USERNAME=isset($HTTP_POST_VARS["DB_SERVER_USERNAME"])?$HTTP_POST_VARS["DB_SERVER_USERNAME"]:'';
			$DB_PASSWORD=isset($HTTP_POST_VARS["DB_SERVER_PASSWORD"])?$HTTP_POST_VARS["DB_SERVER_PASSWORD"]:'';
			$server_exists=false;
			if (function_exists("mysqli_connect")){
				$connect=@mysqli_connect($DB_SERVER,$DB_USERNAME,$DB_PASSWORD);
				if ($connect) {
					$mysql_text='<b>MySqli detected </b>';
					$mysql_image=$right_image;
					mysql_close($connect);
				}
			}
		}
		$show_db_params=true;
		$compat_link="compat_test_info.php?action=testemail";
		$current_link="compat_test_info.php";
		$compat_mysql_link="compat_test_info.php?action=testdb";
		$show_continue=false;
	}
	
	$error_image="<i class='fas fa-times-circle' style='color:red'></i>";
	$notice_image="<i class='fa fa-exclamation-circle' style='color:blue'></i>";
	$warning_image="<i class='fas fa-exclamation-triangle' style='color:yellow'></i>";
	$right_image="<i class='fas fa-chevron-circle-down'style='color:green'></i>";

		$except=0;
		$err=0;
		$war=0;
		clearstatcache();
if (!isset($require_load)){#seems we never hit this if condition
?>


<!DOCTYPE html>
<html>
	<head><title><?php echo TEXT_COMPAT; ?></title></head>
	<body>
<?php }#endOfIf ?>

    <?php if ($last_test!="") { ?>	
		<div class="content">
			<?php echo $last_test;?>
		</div>
    <?php } ?>
    <?php if ($display_mode=="all" || $display_mode!="dir"){?>
	
	<div class="container">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12"><h5><?php echo TEXT_INSTALLATION; ?></h5>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					 <ul class="list-group list-group-horizontal justify-content-center">
					  <li class="list-group-item"><strong><?php echo TEXT_CT; ?></strong></li>
					  <li class="list-group-item"><?php echo TEXT_IO; ?></li>
					  <li class="list-group-item"><?php echo TEXT_DI; ?></li>
					  <li class="list-group-item"><?php echo TEXT_TC; ?></li>
					</ul> 
				</div>
			</div>
<br>
			<div class="row">
				<div class="col-md-4">
				</div>
				<div class="col-md-4">    
				<div class="card" style="width:300px">
      <div class="card-header">
        <?php echo TEXT_SC; ?>
      </div>
        <table class="table table-condensed table-striped">
          <tr>
            <th colspan="2"><?php echo TEXT_PHP; ?></th>
          </tr>
          <tr>
            <th><?php echo PHP_VERSION; ?></th>
            <td align="right" width="25"><?php echo ((PHP_VERSION >= 5.3) ? '<i class="fa fa-thumbs-up text-success"></i>' : '<i class="fa fa-thumbs-down text-danger"></i>'); ?></td>
          </tr>
        </table>

<?php
  if (function_exists('ini_get')) 
  {
?>

        <br />

        <table class="table table-condensed table-striped">
          <tr>
            <th colspan="3"><?php echo TEXT_SETTINGS; ?></th>
          </tr>
          <tr>
            <th>register_globals</th>
            <td align="right"><?php echo (((int)ini_get('register_globals') == 0) ? 'Off' : 'On'); ?></td>
            <td align="right"><?php 
			$right_image='<i class="fa fa-thumbs-up text-success"></i>';
			$error_image='<i class="fa fa-thumbs-down text-danger"></i>';
			echo ((!ini_get("register_globals") || strtolower(ini_get("register_globals"))=="off" || strtolower(ini_get("register_globals"))=="0")?'&nbsp;&nbsp;'.$right_image.'':'&nbsp;&nbsp;'.$error_image."&nbsp;&nbsp;");
			?>
			</td>
          </tr>
          <tr>
            <th>magic_quotes</th>
            <td align="right"><?php echo (((int)ini_get('magic_quotes') == 0) ? 'Off' : 'On'); ?></td>
            <td align="right"><?php echo (((int)ini_get('magic_quotes') == 0) ? '<i class="fa fa-thumbs-up text-success"></i>' : '<i class="fa fa-thumbs-down text-danger"></i>'); ?></td>
          </tr>
          <tr>
            <th>file_uploads</th>
            <td align="right"><?php echo (((int)ini_get('file_uploads') == 0) ? 'Off' : 'On'); ?></td>
            <td align="right"><?php echo (((int)ini_get('file_uploads') == 1) ? '<i class="fa fa-thumbs-up text-success"></i>' : '<i class="fa fa-thumbs-down text-danger"></i>'); ?></td>
          </tr>
          <tr>
            <th>session.auto_start</th>
            <td align="right"><?php echo (((int)ini_get('session.auto_start') == 0) ? 'Off' : 'On'); ?></td>
            <td align="right"><?php echo (((int)ini_get('session.auto_start') == 0) ? '<i class="fa fa-thumbs-up text-success"></i>' : '<i class="fa fa-thumbs-down text-danger"></i>'); ?></td>
          </tr>
          <tr>
            <th>session.use_trans_sid</th>
            <td align="right"><?php echo (((int)ini_get('session.use_trans_sid') == 0) ? 'Off' : 'On'); ?></td>
            <td align="right"><?php echo (((int)ini_get('session.use_trans_sid') == 0) ? '<i class="fa fa-thumbs-up text-success"></i>' : '<i class="fa fa-thumbs-down text-danger"></i>'); ?></td>
          </tr>
        </table>

        <br />

        <table class="table table-condensed table-striped">
          <tr>
            <th colspan="2">Required PHP Extensions</th>
          </tr>
          <tr>
            <th>MySQL<?php echo extension_loaded('mysqli') ? 'i' : ''; ?></th>
            <td align="right"><?php echo (extension_loaded('mysqli') ? '<i class="fa fa-thumbs-up text-success"></i>' : '<i class="fa fa-thumbs-down text-danger"></i>'); ?></td>
          </tr>
        </table>

        <br />

        <table class="table table-condensed table-striped">
          <tr>
            <th colspan="2">Recommended PHP Extensions</th>
          </tr>
          <tr>
            <th>GD</th>
            <td align="right"><?php echo (extension_loaded('gd') ? '<i class="fa fa-thumbs-up text-success"></i>' : '<i class="fa fa-thumbs-down text-danger"></i>'); ?></td>
          </tr>
          <tr>
            <th>cURL</th>
            <td align="right"><?php echo (extension_loaded('curl') ? '<i class="fa fa-thumbs-up text-success"></i>' : '<i class="fa fa-thumbs-down text-danger"></i>'); ?></td>
          </tr>
          <tr>
            <th>OpenSSL</th>
            <td align="right"><?php echo (extension_loaded('openssl') ? '<i class="fa fa-thumbs-up text-success"></i>' : '<i class="fa fa-thumbs-down text-danger"></i>'); ?></td>
          </tr>
        </table>

<?php
  }
?>
    </div>
				</div>
				<div class="col-md-4">
				</div>

	</div>
</div>
</div>

		<!--<div class="progress progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">25%</div>
		</div>-->
		
		<!-- need to finish this legend-->
				<div class="container">

					<div class="panel" style="display:none">
						<div class="panel-heading">
								<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#bs-collapse" href="#one">
									<i class="fa fa-caret-down"></i> Legend
								</a>
							</h4>
						</div>
						<div id="one" class="panel-collapse collapse show">
							<div class="panel-body">
								<b>Just information</b><br> This is a simple message with some information about something.	<br>	
								<div>
									<?php echo $error_image;?>
									<b>Error!</b> Indicates that something is definitely wrong and that osConcert will most likely not perform as expected if this problem is not solved.<br />
									This indicates an actual error.
								</div>
								<div>
									<?php echo $right_image;?>
									<b>Check was successful</b> Indicates that something was checked and returned an expected result.
								</div>										
								<div>
									<?php echo $notice_image;?>									
									<b>Notice!</b> Indicates that something is important to be aware of.<br />
									This does not indicate an error.										
								</div>									
								<div>
									
									<?php echo $warning_image;?>
									<b>Warning!</b> Indicates that something may very well cause trouble and you should definitely look into it before proceeding.<br/>
									This indicates a potential error.										
								</div>									
							</div>
						</div>
					</div>





					</div>			

		<!-- end of container -->	
		<br>		
    <?php }
	if ($display_mode=="dir") {$err=0;}
	if(isset($skip_error)){$err--;}
    if ($err<=0) { 
		if ($show_continue) {
	?>
                    <?php 
					if ($war>0)	{
                        $tooltip =  "Found " . $war . " warning(s)";
                        $tooltip .= "\n See above for details";
						$tooltip .= "\n Installation may still continue";
                        $tooltip .= "\n\n";
					} 
					if ($display_mode=="dir") {
                        echo '<form name="skippy" action="install.php?step=9&action=email" method="post">';
                        reset($HTTP_POST_VARS);
						//FOREACH
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
						echo '<input type="button" class="btn btn-info" value="Continue" >';
						echo '</form>';?>
						<script type="text/javascript">
                            function myfunc () {
                                      document.forms["skippy"].submit()
                            }
                            window.onload = myfunc;
						</script>						
					<?php
					} else {
						echo '<form action="install.php" method="post" id="continue">';
                        echo osc_draw_hidden_field('DB_SERVER',$DB_SERVER) .
                             osc_draw_hidden_field('DB_SERVER_USERNAME',$DB_SERVER_USERNAME) .
                             osc_draw_hidden_field('DB_SERVER_PASSWORD',$DB_SERVER_PASSWORD);			
                        echo '<input type="hidden" name="compat_test_pass" value="1"/>';
						echo '<button type="button" class="btn btn-info mx-auto d-block" data-toggle="tooltip" data-placement="top" title="'.$tooltip.'" onClick="this.form.submit();">Continue <i class="fas fa-arrow-alt-circle-right" style="color:white"></i></button><br>';
                        echo '</form>';
					}
					?>			  
        <?php
		}
    }
				
if(isset($skip_error)){$err++;}
              //  if($skip_error)$err++;
                if ($err>0){ ?>
                <div id="retry" style="width:100%; float: left;">
                    <div class="content" align="left" style="width:100%; float: left;">
                        
                        <?php if ($show_continue) { ?>
                                
                                        <div>
                                        <?php
                                                        echo '<br><b>Installation cannot continue</b><br>';
                                                        if ($err>0){
                                                                echo '<font color="white">Found ' . $err . " error(s)</font><br>";
                                                                echo osc_draw_hidden_field('error',$err);
                                                        }
                                                        if ($war>0)	echo '<font color="white">Found ' . $war . " warning(s)</font>";
                                                        echo '<br><br>';
                                        ?>
                                        </div>
                               
                        <?php } ?>
                                <div style="width:100%; float: left;">
                                        <div>
                                            
                                            <a href="<?php echo $current_link;?>">
                                                <input type="button" class="activebtncls" value="Retry" >

                                            </a></div>
                                </div>
                       
                </div>
        </div>
        <?php 
        } // $show_continue
        ?>                
		
<?php 
	if (!isset($require_load)){
	?>
	</body>
	</html>
<?php
	}
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
	   $splt_element=split("Client API version",$content);
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
<script language="javascript">
	var error = 0;
	if(document.getElementById('check'))document.getElementById('check').checked=false;
	if(document.getElementById('error'))error = document.getElementById('error').value;
	if(error>0){
		if(document.getElementById('continue'))document.getElementById('continue').style.display="none";
		if(document.getElementById('retry'))document.getElementById('retry').style.display="";
	}
	function skip_test(checked){
		if(checked && document.getElementById('details'))document.getElementById('details').style.display="none";
		else if(document.getElementById('details'))document.getElementById('details').style.display="";
		if(checked && document.getElementById('skip') && error<=1){
			if(document.getElementById('continue'))document.getElementById('continue').style.display="";
			if(document.getElementById('retry'))document.getElementById('retry').style.display="none";
		}else{
			if(document.getElementById('continue'))document.getElementById('continue').style.display="none";
			if(document.getElementById('retry'))document.getElementById('retry').style.display="";
		}
	}
</script>
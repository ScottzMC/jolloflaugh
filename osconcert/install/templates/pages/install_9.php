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
  ?>
<script>
 function do_pwd_check(obj)
 { 
  <?php 
  //if(MODULE_CUSTOMERS_PASSWORD_STRENGTH==4){
  
  ?>	
 	pwd=obj.value;
	if(pwd.length==0)
	  document.all.pwd_str.innerHTML='';
	else if(pwd.length<"5")
		document.all.pwd_str.innerHTML='<font color="red">Password strength is poor</font>';
	else if(!check_password_strength(pwd))
		document.all.pwd_str.innerHTML='<font color="red">Password strength is poor</font>';
	else
		document.all.pwd_str.innerHTML='';
  <?php //} ?>
 }
 
 function check_password_strength(pwd)
	{ 
		var r_sym = new RegExp("[~`!@#$%^&*_=|\/><,?;:+-]","i");
		var r_caps =/^[A-Z]+$/; //new RegExp("[A-Z]","i");
		var r_small =/^[a-z]+$/; // new RegExp("[a-z]","i");
            tot_average        = 0.0; 
            pwdav_len            = 0.0;                 
            pwdav_caps        = 0.0;                 
            pwdav_nums        = 0.0;                                         
            pwdav_small        = 0.0; 
            pwdav_puncts        = 0.0;                 
            total_char_used = 0; 
            if (pwd.length>0) 
            { 
                p_limit = 5; 
                pwd_len = pwd.length; 
                nums_cnt = 0; 
                for(i=0;i<pwd_len;i++) 
                { 
					val=pwd.substr(i,1);
                    if (!isNaN(val)) 
                        nums_cnt++; 
                } 
                if (nums_cnt>0) 
                    total_char_used += 10; 
					
                small_cnt = 0; 
                for(i=0;i<pwd_len;i++) 
                { 
					val=pwd.substr(i,1);
					res = r_small.test(val);
                    if (res==true)
                        small_cnt++; 
                } 
                if (small_cnt>0) 
                    total_char_used += 26; 

                caps_cnt = 0; 
                for(i=0;i<pwd_len;i++) 
                { 
					val=pwd.substr(i,1);
					res = r_caps.test(val);
                    if (res==true) 
                        caps_cnt++; 
                } 
                if (caps_cnt>0) 
                    total_char_used += 26; 

                puncts_cnt = 0; 
                for(i=0;i<pwd_len;i++) 
                { 
					val=pwd.substr(i,1);
					res=val.match(r_sym);
                    if (res!=null) 
                        puncts_cnt++; 
                } 
                if (puncts_cnt>0) 
                    total_char_used += 31; 

                // calculation   
				len_min="5";
				len_max=16;                                      
                if ((pwd_len>len_min) && (pwd_len<len_max)) 
                    pwdav_len += (100 / p_limit); 
                // caps 
                tot_average += pwdav_len; 
                if (20 <= ((caps_cnt * 100) / pwd_len)) 
                    pwdav_caps += (100 / p_limit); 
                else 
                    pwdav_caps += (caps_cnt > 0) ? ((100 / p_limit) - 10) :  0; 
                tot_average += pwdav_caps; 
                // numbers 
                if (20 <= ((nums_cnt * 100) / pwd_len)) 
                    pwdav_nums += (100 / p_limit); 
                else 
                    pwdav_nums += (nums_cnt > 0) ? ((100 / p_limit) - 10) :  0; 
                tot_average += pwdav_nums; 
                // small 
                if (30 <= ((small_cnt * 100) / pwd_len)) 
                    pwdav_small += (100 / p_limit); 
                else 
                    pwdav_small += (small_cnt > 0) ? ((100 / p_limit) - 10) :  0; 
                tot_average += pwdav_small; 
				
                // symbols 
                if (10 <= ((puncts_cnt * 100) / pwd_len)) 
                    pwdav_puncts += (100 / p_limit); 
                else 
                    pwdav_puncts += (puncts_cnt > 0) ? ((100 / p_limit) - 10) :  0; 
                 
                tot_average += pwdav_puncts;             
                charSet = total_char_used; 
            } 
			if(tot_average<=40)
				return false;
			else
				return true;
	}
	
function pwdValidation(type,strng){
	switch (type){
			case '1':
				if(str_trim(strng)=='')
					return false;
				break;
			case '2':
				 var alphanum = /^[a-zA-Z0-9_]+$/;
				 return alphanum.test(strng);
				 break;
			case '3':
				//var symbol = /^([a-zA-Z0-9_\.\+\-\*\/])+$/;
				var symbol = /^([0-9a-zA-Z_\.\+\-\=\*\/\@\#\$\!\`\~\%\^\&\*\(\)\_\|\>\<\,\?\:\;\"\'\{\[\}\]\\])+$/;
                var ans = symbol.test(strng);
				if (ans == true) return true;
				else return false;
				// return symbol.test(strng);	
				 break;
		/*	case '4':
				//var symbol = /^([0-9a-zA-Z]\*\+\-\*)*?/g;
					var symbol = /^([0-9a-zA-Z_\.\+\-\*\/])+$/;
					//alert(symbol.test(strng));
					var ans = symbol.test(strng);
					if (ans == true){
					return true;
					}
					else 
					return false;
					// return symbol.test(strng);	
				 break;*/
	}			
	return true;
}
</script>

<?php	
$admin_folder = $HTTP_POST_VARS['CFG_ADMIN_FOLDER'];
$action=(isset($HTTP_GET_VARS["action"])?$HTTP_GET_VARS["action"]:'');
if ($action=='email_update'){
	$db['DB_SERVER'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER']));
	$db['DB_SERVER_USERNAME'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_USERNAME']));
	$db['DB_SERVER_PASSWORD'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_PASSWORD']));
	$db['DB_DATABASE'] = trim(stripslashes($HTTP_POST_VARS['DB_DATABASE']));
	$db_error = false;    
	osc_db_connect($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD'], $db['DB_DATABASE']);

	if (!osc_db_select_db($db['DB_DATABASE'])){
		$db_error=mysqli_error();
	}
	if (!$db_error){
		$email_address=isset($HTTP_POST_VARS["txtEmail"])?$HTTP_POST_VARS["txtEmail"]:'';
		$password=isset($HTTP_POST_VARS["txtPass"])?$HTTP_POST_VARS["txtPass"]:'';
		if ($email_address!='' && $password!=''){
			$password=osc_encrypt_password($password);
			osc_db_query("UPDATE admin set admin_email_address='" . $email_address . "',admin_password='" . $password . "' where admin_id=1");
		}
	}
	$action="complete";
}
$www_location=isset($HTTP_POST_VARS['HTTP_WWW_ADDRESS'])?$HTTP_POST_VARS['HTTP_WWW_ADDRESS']:'';
$web_service=isset($HTTP_POST_VARS['webservice'])?$HTTP_POST_VARS['webservice']:'0';
if ($www_location==""){
	$www_location = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER["SCRIPT_NAME"];
	$www_location = substr($www_location, 0, strpos($www_location, 'install'));
}
  
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

$last_test="";
$compat_link="install.php?mode=compat_test&action=testemail";
$show_continue=true;
$require_load=true;
$display_mode="dir";
if ($action=="email"){
  	if (!isset($HTTP_POST_VARS["first_install"]) || $HTTP_POST_VARS["first_install"]!='1'){
		$action="complete_no_config";
	}
}
if ($action=="email") {
	$current_step="complete";
?>
	<script language="javascript">

		function validateForm(){
			frm=document.frmSetting;
			error="";
				
			if (trim(frm.txtEmail.value)==""){
				error+="* Email Address cannot be empty.\n";
			} else if (!checkEmail(frm.txtEmail.value)){
				error+="* Email Address not valid.\n";
			}
			if (trim(frm.txtPass.value)==""){
				error+="* Password cannot be empty.\n";
			} else if (frm.txtPass.value.length<5 || !check_password_strength(frm.txtPass.value) || !pwdValidation('3',frm.txtPass.value)){
				error+="* Password must be strong.\n";
			}
			if (trim(frm.txtConfirmPass.value)==""){
				error+="* Confirm Password cannot be empty.\n";
			}
			if (error=="" && (frm.txtPass.value!=frm.txtConfirmPass.value)){
				error+="* Password and Confirm Password are not matching\n";
			}
			
			if(error!=''){
				alert(error);
				return false;
			}
			return true;
		}
		function checkEmail(email) {
			if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)){
				return (true)
			}
			return false;
		}
		function trim(str){
			if(!str || typeof str != 'string')
				return '';
		
			return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
		}
	</script>
	<div class="container" style="width:800px">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<form action="install.php?step=9&action=email_update" onSubmit="javascript:return validateForm();" name="frmSetting" method="post">
				<h3><i class='fa fa-check' style='color:green'></i><?php echo TEXT_SUCCESSFUL3; ?></h3>
				<p> <?php echo TEXT_TOOL; ?><br />		
				<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
					<div style="width: 30%; vertical-align: text-top; float: left;" ><b><?php echo TEXT_EMAIL; ?></b></div>
					<div style="width: 70%; float: left;">
						<input type="text"  class="form-control" name="txtEmail" value=""/>
					</div>    
				</div>
				
				<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
					<div style="width: 30%; vertical-align: text-top; float: left;" ><b><?php echo TEXT_PASSWORD; ?></b></div>
					<div style="width: 70%; float: left;">
						<input name="txtPass" class="form-control" type="password" onkeyup="javascript:do_pwd_check(this);"/>
						<span id='pwd_str' class="inputRequirement" style="font-color:red;font-size:11"></span>
					</div>    
				</div>
				
				<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
					<div style="width: 30%; vertical-align: text-top; float: left;" ><b><?php echo TEXT_CONFIRM; ?></b></div>
					<div style="width: 70%; float: left;">
						<input name="txtConfirmPass" class="form-control" type="password"/>
					</div>    
				</div>
				<!--<div style="width: 100%; float: left; padding-right:15px; margin: 10px 0px;">                        
					<div style="width: 30%; vertical-align: text-top; float: left;" ><b>Time Zone:</b></div>
					<div style="width: 70%; float: left;">
						<?php //echo osc_draw_time_zone_select_menu('CFG_TIME_ZONE'); ?>
					</div>  
				</div> -->
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
				<a class="btn btn-info" style="float: left; margin-top: 10px;" href="index.php" role="button"><i class='fas fa-arrow-alt-circle-left' style='color:white'></i> <?php echo TEXT_CANCEL; ?></a>
				<button type="button" class="btn btn-info" style="float: right; margin-top: 10px;" onClick="this.form.submit();"><?php echo TEXT_CONTINUE; ?> <i class='fas fa-arrow-alt-circle-right' style='color:white'></i></button>
			</form>
		</div>
	</div>
<?php
} else if ($action=="complete" || $action=="complete_no_config") {
	$current_step="complete";
?>
<div class="container" style="width:800px">
<div class="card border-info" id="txr_terms">
	<div class="card-header"> <?php echo TEXT_FINISHED; ?></div>
	<div class="card-body">
		<p class="card-text">
			<i class='fa fa-check' style='color:green'></i>
			<?php 
			if ($action=="complete_no_config") { ?>
				<?php echo TEXT_SUCCESSFUL3; ?>
	<?php 	} else { ?>
				<?php echo TEXT_ADMIN_LOGIN_SETTINGS; ?>
	<?php 	} ?>
		</p>			
		<div>	</div>
	<?php /////////////////////////////////////
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
		$dir_fs_www_root = dirname($script_filename) . "/";
		$error_image="<i class='fa fa-times-circle' style='color:red'></i>";
		$notice_image="<i class='fa fa-exclamation-circle' style='color:blue'></i>";
		$warning_image="<i class='fa fa-exclamation-triangle' style='color:yellow'></i>";
		$right_image="<i class='fas fa-chevron-circle-down' style='color:green'></i>";
		if(isset($_POST['create_view_error'])){?>		
			<p class="card-text">
				<ul style="list-style-type:none">
					<li><?php echo $warning_image;?> <?php echo TEXT_SQLERROR; ?></li>
					<ul style="list-style-type:none">
						<li><?php echo $error_image;?><?php echo TEXT_VIEW; ?></li>
						<ul style="list-style-type:none">
							<li><b><?php echo $_POST['create_view_error'];?></b></li>
						</ul>
					</ul>
					<li><?php echo TEXT_MANUALLY; ?></li>
					<li><?php echo $_POST['create_view_message'];?></li>
				</ul>
			</p>
<?php 	}?>
	</div>
	<div>	</div>	
	<div class="card-header"><?php echo TEXT_AFTER; ?></div>
	<div class="card-body">
		<p class="card-text">
	<?php	if (file_exists($dir_fs_www_root )){
				$image=$error_image;?>
				<div class="content">
					<?php echo $image;?><?php echo TEXT_STILL_EXISTS; ?><br />
					<?php echo $dir_fs_www_root;?><br />
					<?php echo TEXT_DELETED; ?>
				</div>
	<?php 	}
?>
		</p>
	</div>
	<div class="card-header"><?php echo TEXT_CDS; ?></div>
	<div class="card-body">
		<p class="card-text">
			<?php
			$files=array("../includes/configure.php","../".$admin_folder."/includes/configure.php");
			for ($icnt=0;$icnt<=1;$icnt++){
				if (file_exists($dir_fs_www_root . $files[$icnt]) ){
					if (is_writable($dir_fs_www_root . $files[$icnt]) ){
						$image=$error_image;
						$text=" <b>is writable. Please alter this after installation.</b><br>";
					} else {
						$image=$right_image;
						$text=" <b>is not writable</b>";
					}
				} else {
					$text="&nbsp;not found";$image=$error_image;
				}
				echo "".$image;?>&nbsp;<?php echo $files[$icnt] . $text;						
			}?>
		</p>
	</div>
</div>
<div>
<a class="btn btn-info" style="float: left; margin-top: 10px;" href="<?php echo $www_location . 'index.php'; ?>" role="button"><i class='fas fa-book-open' style='color:white'></i><?php echo TEXT_FRONT_END; ?></a>
<a class="btn btn-info" style="float: right; margin-top: 10px;" href="<?php echo $www_location . $admin_folder.'/index.php'; ?>" role="button"><i class='fas fa-user-alt' style='color:white'></i> <?php echo TEXT_ADMINISTRATION; ?></a>
</div>
</div><br><br><br><br>
<?php
} else {
	$current_step="config";
	require("compat_test_info.php");
}
?>
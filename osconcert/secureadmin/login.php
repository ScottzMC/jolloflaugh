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

// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	require('includes/application_top.php');
	if(($FREQUEST->getvalue('action')!='') && ($FREQUEST->getvalue('action') == 'process')) 
	{
		$email_address = $FREQUEST->postvalue('email_address');
		$password = $FREQUEST->postvalue('password');

		// Check if email exists
		$check_admin_query = tep_db_query("select admin_id as login_id, admin_groups_id as login_groups_id, admin_firstname as login_firstname, admin_lastname as login_lastname, admin_email_address as login_email_address, admin_password as login_password, admin_modified as login_modified, admin_logdate as login_logdate, admin_lognum as login_lognum,encryption_style from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
		if(!tep_db_num_rows($check_admin_query)) 
		{ 
			$FREQUEST->setvalue('login','fail','GET');
		}
		else 
		{
			$check_admin = tep_db_fetch_array($check_admin_query);
			
			// Check that password is correct
			if(!tep_validate_password($password, $check_admin['login_password'],$check_admin['encryption_style'])) 
			{ 
				$FREQUEST->setvalue('login','fail','GET');
			}
			else 
			{
				
				if( $FSESSION->is_registered('password_forgotten')) 
				{
					$FSESSION->remove('password_forgotten');
				}
				
				if(!($check_admin['encryption_style']=="" && ENCRYPTION_STYLE=="O") && $check_admin['encryption_style']!=ENCRYPTION_STYLE)
				{
					$new_password=tep_encrypt_password($password);
					tep_db_query("UPDATE " . TABLE_ADMIN . " set admin_password='" . tep_db_input($new_password) . "', encryption_style='" . ENCRYPTION_STYLE . "' where admin_id=" . (int)$check_admin['login_id']);
				}
				
				$login_id = $check_admin['login_id'];
				$login_groups_id = $check_admin['login_groups_id'];
				$login_first_name = $check_admin['login_firstname'];
				$login_last_name = $check_admin['login_lastname'];
				$login_email_address = $check_admin['login_email_address'];
				$login_logdate = $check_admin['login_logdate'];
				$login_lognum = $check_admin['login_lognum'];
				$login_modified = $check_admin['login_modified'];
				$FSESSION->set('login_id',$login_id);
				$FSESSION->set('login_groups_id',$login_groups_id);
				$FSESSION->set('login_first_name', $login_first_name);
				$FSESSION->set('login_last_name', $login_last_name);
				$FSESSION->set('login_email',$login_email_address);
				
				// error count
				$FSESSION->set('error_count',0);
				
				// find the admin groups name
				$group_query=tep_db_query("SELECT admin_groups_name from ". TABLE_ADMIN_GROUPS . " where admin_groups_id='". (int)$FSESSION->login_groups_id . "'");
				$group_result=tep_db_fetch_array($group_query);
				$login_groups_name=$group_result['admin_groups_name'];
				$login_groups_type=$login_groups_name;
				$FSESSION->set('login_groups_type',$login_groups_type);
				
				// error count
				$FSESSION->set('error_count',0);
				
				// set top administrator email
				$admin_query=tep_db_query("SELECT admin_email_address from " . TABLE_ADMIN . " a, " . TABLE_ADMIN_GROUPS . " ag where ag.admin_groups_name='" . TEXT_ADMINISTRATOR_ENTRY . "' and a.admin_groups_id=ag.admin_groups_id");
				$admin_result=tep_db_fetch_array($admin_query);
				$FSESSION->set('top_admin_email',$admin_result["admin_email_address"]);
				
				$select_box="";
				tep_db_query("update " . TABLE_ADMIN . " set admin_logdate ='" . tep_db_input(getServerDate(true)) . "', admin_lognum = admin_lognum+1 where admin_id = '" . (int)$FSESSION->login_id . "'");
				$file_name = $FREQUEST->cookievalue('store_file_name_' . $FSESSION->login_groups_id,'string','configuration.php?from=col&top=1&mPath=11_152');
				if($login_groups_name==TEXT_ADMINISTRATOR_ENTRY)
				{
					tep_redirect(tep_href_link($file_name));
				}

				
				tep_redirect(tep_href_link('myaccount_update_account.php?mPath=12'));
			}
			
		}
	}
require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_LOGIN);
?><!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<style type="text/css">
/* BASIC */

html {
  background-color: #56baed;
}

body {
  font-family: "Poppins", sans-serif;
  height: 100vh;
}

a {
  color: #92badd;
  display:inline-block;
  text-decoration: none;
  font-weight: 400;
}

h2 {
  text-align: center;
  font-size: 16px;
  font-weight: 600;
  text-transform: uppercase;
  display:inline-block;
  margin: 40px 8px 10px 8px; 
  color: #cccccc;
}



/* STRUCTURE */

.wrapper {
  display: flex;
  align-items: center;
  flex-direction: column; 
  justify-content: center;
  width: 100%;
  min-height: 100%;
  padding: 20px;
}

#formContent {
  -webkit-border-radius: 10px 10px 10px 10px;
  border-radius: 10px 10px 10px 10px;
  background: #fff;
  padding: 30px;
  width: 90%;
  max-width: 450px;
  position: relative;
  padding: 0px;
  -webkit-box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
  box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
  text-align: center;
}

#formFooter {
  background-color: #f6f6f6;
  border-top: 1px solid #dce8f1;
  padding: 25px;
  text-align: center;
  -webkit-border-radius: 0 0 10px 10px;
  border-radius: 0 0 10px 10px;
}



/* TABS */

h2.inactive {
  color: #cccccc;
}

h2.active {
  color: #0d0d0d;
  border-bottom: 2px solid #5fbae9;
}



/* FORM TYPOGRAPHY*/

input[type=button], input[type=submit], input[type=reset]  {
  background-color: #56baed;
  border: none;
  color: white;
  padding: 15px 80px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  text-transform: uppercase;
  font-size: 13px;
  -webkit-box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
  box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
  -webkit-border-radius: 5px 5px 5px 5px;
  border-radius: 5px 5px 5px 5px;
  margin: 5px 20px 40px 20px;
  -webkit-transition: all 0.3s ease-in-out;
  -moz-transition: all 0.3s ease-in-out;
  -ms-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
}

input[type=button]:hover, input[type=submit]:hover, input[type=reset]:hover  {
  background-color: #39ace7;
}

input[type=button]:active, input[type=submit]:active, input[type=reset]:active  {
  -moz-transform: scale(0.95);
  -webkit-transform: scale(0.95);
  -o-transform: scale(0.95);
  -ms-transform: scale(0.95);
  transform: scale(0.95);
}

input[type=text] {
  background-color: #f6f6f6;
  border: none;
  color: #0d0d0d;
  padding: 5px 22px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 5px;
  width: 85%;
  border: 2px solid #f6f6f6;
  -webkit-transition: all 0.5s ease-in-out;
  -moz-transition: all 0.5s ease-in-out;
  -ms-transition: all 0.5s ease-in-out;
  -o-transition: all 0.5s ease-in-out;
  transition: all 0.5s ease-in-out;
  -webkit-border-radius: 5px 5px 5px 5px;
  border-radius: 5px 5px 5px 5px;
}

input[type=password] {
  background-color: #f6f6f6;
  border: none;
  color: #0d0d0d;
  padding: 5px 22px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 5px;
  width: 85%;
  border: 2px solid #f6f6f6;
  -webkit-transition: all 0.5s ease-in-out;
  -moz-transition: all 0.5s ease-in-out;
  -ms-transition: all 0.5s ease-in-out;
  -o-transition: all 0.5s ease-in-out;
  transition: all 0.5s ease-in-out;
  -webkit-border-radius: 5px 5px 5px 5px;
  border-radius: 5px 5px 5px 5px;
}

input[type=text]:focus {
  background-color: #fff;
  border-bottom: 2px solid #5fbae9;
}

input[type=text]:placeholder {
  color: #cccccc;
}



/* ANIMATIONS */

/* Simple CSS3 Fade-in-down Animation */
.fadeInDown {
  -webkit-animation-name: fadeInDown;
  animation-name: fadeInDown;
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
}

@-webkit-keyframes fadeInDown {
  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, -100%, 0);
    transform: translate3d(0, -100%, 0);
  }
  100% {
    opacity: 1;
    -webkit-transform: none;
    transform: none;
  }
}

@keyframes fadeInDown {
  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, -100%, 0);
    transform: translate3d(0, -100%, 0);
  }
  100% {
    opacity: 1;
    -webkit-transform: none;
    transform: none;
  }
}

/* Simple CSS3 Fade-in Animation */
@-webkit-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
@-moz-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
@keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

.fadeIn {
  opacity:0;
  -webkit-animation:fadeIn ease-in 1;
  -moz-animation:fadeIn ease-in 1;
  animation:fadeIn ease-in 1;

  -webkit-animation-fill-mode:forwards;
  -moz-animation-fill-mode:forwards;
  animation-fill-mode:forwards;

  -webkit-animation-duration:1s;
  -moz-animation-duration:1s;
  animation-duration:1s;
}

.fadeIn.first {
  -webkit-animation-delay: 0.4s;
  -moz-animation-delay: 0.4s;
  animation-delay: 0.4s;
}

.fadeIn.second {
  -webkit-animation-delay: 0.6s;
  -moz-animation-delay: 0.6s;
  animation-delay: 0.6s;
}

.fadeIn.third {
  -webkit-animation-delay: 0.8s;
  -moz-animation-delay: 0.8s;
  animation-delay: 0.8s;
}

.fadeIn.fourth {
  -webkit-animation-delay: 1s;
  -moz-animation-delay: 1s;
  animation-delay: 1s;
}

/* Simple CSS3 Fade-in Animation */
.underlineHover:after {
  display: block;
  left: 0;
  bottom: -10px;
  width: 0;
  height: 2px;
  background-color: #56baed;
  content: "";
  transition: width 0.2s;
}

.underlineHover:hover {
  color: #0d0d0d;
}

.underlineHover:hover:after{
  width: 100%;
}



/* OTHERS */

*:focus {
    outline: none;
} 

#icon {
  width:60%;
}
</style>
</head>
<body>
<div class="wrapper fadeInDown">
  <div id="formContent">
    <!-- Tabs Titles -->

    <!-- Icon -->
    <div class="fadeIn first">
      <a href="<?php echo HOME_URL; ?>"><?php echo tep_image(DIR_WS_IMAGES . ADMIN_LOGO, STORE_NAME,'','',''); ?></a>
    </div>

    <!-- Login Form -->
    <?php echo tep_draw_form('login', FILENAME_LOGIN, 'action=process'); ?> 
	<?php
		if($FREQUEST->getvalue('login') == 'fail') 
		{
			$info_message = TEXT_LOGIN_ERROR;
		}
		if(isset($info_message)) 
		{?>
		<div><?php echo $info_message; ?></div>
	<?php
		}
		else 
		{
	?>

	<?php
		}
	?>
	
	<?php echo tep_draw_input_field('email_address'),'','',''; ?>
	<?php echo tep_draw_password_field('password'); ?>
	  <br>
      <input type="submit" class="fadeIn fourth" value="Log In">
    </form>

    <!-- Remind Passowrd -->
    <!--<div id="formFooter">
      <a class="underlineHover" href="#">Forgot Password?</a>
    </div>-->

  </div>
</div>
<?php //require(DIR_WS_INCLUDES . 'footer.php'); ?>
</body>
</html>
<?php
/*
 osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 

  IMPORTANT NOTE:

  This script is not part of the official osC distribution
  but an add-on contributed to the osC community. Please
  read the README and  INSTALL documents that are provided
  with this file for further information and installation notes.

  loginbox.php -   Version 1.0
  This puts a login request in a box with a login button.
  If already logged in, will not show anything.

  Modified to utilize SSL to bypass Security Alert
*/
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
require(DIR_WS_LANGUAGES . $FSESSION->language . '/loginbox.php');
 

// Do not show if on login or create account
if ((!strstr($_SERVER['PHP_SELF'],'login.php')) and (!strstr($_SERVER['PHP_SELF'],'create_account.php')) and !$FSESSION->is_registered('customer_id'))  
{
?>
<!-- loginbox //-->
<?php
    if (!$FSESSION->is_registered('customer_id')) 
	{

	if(!defined('BOX_HEADING_LOGIN'))define('BOX_HEADING_LOGIN', 'Login');

    $loginboxcontent = "
	<form name=\"login\" method=\"post\" action=\"" . tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL') . "\" id=\"login-nav\">	  
	<div class=\"container-fluid\">
		<div class=\"row\">
		<div class=\"col-md-12\" style=\"padding:10px\">
			<div class=\"row\">
				<div class=\"col-md-6\">
				<div class=\"form-group\">
				<label class=\"sr-only\" for=\"email\">" . BOX_LOGINBOX_EMAIL . "</label>
				<input type=\"text\" name=\"email_address\" class=\"form-control\" id=\"email\" placeholder=" . BOX_LOGINBOX_EMAIL . " required>
				</div>
				</div>
				<div class=\"col-md-6\">
				<div class=\"form-group\">
				<label class=\"sr-only\" for=\"password\">" . BOX_LOGINBOX_PASSWORD . "</label>
				<input type=\"password\" name=\"password\" class=\"form-control\" id=\"password\" placeholder=" . BOX_LOGINBOX_PASSWORD . " required>
				<small><a  style=\"text-transform: lowercase;text-transform: capitalize;\" href=\"" . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . "\">" . TEXT_PASSWORD_FORGOTTEN . "</a></small></div>
				</div>
			</div>
			<div class=\"form-group\" style=\"width:80px;margin:auto\">
				" . tep_template_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN) . "
				</div>
				</form>
		</div>
	</div>
</div>";


		echo '<div class="card-header">';
		echo '<strong>';
		//echo BOX_HEADING_LOGIN;
		echo 'Login as <strong>Box Office Agent</strong> and arrange the objects in the Design Space';
		echo '</strong>';
		echo '</div>';
		echo '<div class="list-group">';
		echo $loginboxcontent;
		echo '</div>';

		//echo '<br class="clearfloat">';
  } 
}
  ?>

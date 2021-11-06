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
?>

<?php

	if(!defined('BOX_HEADING_LOGIN'))define('BOX_HEADING_LOGIN', 'Login');

    $loginboxcontent = "
            <form name=\"login\" method=\"post\" action=\"" . tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL') . "\" id=\"login-nav\">
				<div class=\"form-group\">
				<label class=\"sr-only\" for=\"email\">" . BOX_LOGINBOX_EMAIL . "</label>
				<input type=\"text\" name=\"email_address\" class=\"form-control\" id=\"email\" placeholder=" . BOX_LOGINBOX_EMAIL . " required>
				</div>
				<div class=\"form-group\">
				<label class=\"sr-only\" for=\"password\">" . BOX_LOGINBOX_PASSWORD . "</label>
				<input type=\"password\" name=\"password\" class=\"form-control\" id=\"password\" placeholder=" . BOX_LOGINBOX_PASSWORD . " required>
				<small><a  style=\"text-transform: lowercase;text-transform: capitalize;\" href=\"" . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . "\">" . TEXT_PASSWORD_FORGOTTEN . "</a></small></div>
				<div class=\"form-group\" style=\"width:80px;margin:auto\">
				" . tep_template_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN) . "
				</div>
				</form>
              ";

		echo '<div class="card box-shadow">';
		echo '<div class="card-header">';
		echo '<strong>';
		echo BOX_HEADING_LOGIN;
		echo '</strong>';
		echo '</div>';
		echo '<div class="list-group">';
		echo '<div>' . $loginboxcontent . '</div>';
		echo '</div>';
		echo '</div>';
		echo '<br class="clearfloat">';
?>

<?php
  } else
	  {
  // If you want to display anything when the user IS logged in, put it
  // in here...  Possibly a "You are logged in as :" box or something.


  }
?>
<!-- loginbox_eof //-->
<?php
// My Account Info Box
} else 
{
  if ($FSESSION->is_registered('customer_id')) 
  {
?>
<!-- my_account_info //-->
<?php

	$template_html='<li class="{{CLASS}}"><a style="display: block;" href="{{LINK_1}}"> {{NAME}}</a></li>
					';

	$replace_array=array();
	$replace_array["INITIAL_WIDTH"]=$INITIAL_WIDTH;
	$replace_array["CLASS"]="list-group-item ";
	$replace_array["SPACER"]="";
	$replace_array["ICON"]=$CONTENT_ICON;
	$replace_html=$template_html;
	
	reset($replace_array);
	//FOREACH
	//while(list($key,)=each($replace_array))
	foreach($replace_array as $key=>$value) 
	{
		$replace_html=str_replace("{{" . $key . "}}",$replace_array[$key],$replace_html);
	}
	
	$detail_info=array();
	$detail_info[]=array(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'),LOGIN_BOX_MY_ACCOUNT);
	$detail_info[]=array(tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'),LOGIN_BOX_ACCOUNT_EDIT);
	$detail_info[]=array(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'),LOGIN_BOX_ACCOUNT_HISTORY);
	$detail_info[]=array(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'),LOGIN_BOX_ADDRESS_BOOK);
	$detail_info[]=array(tep_href_link(FILENAME_LOGOFF, '', 'NONSSL'),LOGIN_BOX_LOGOFF);

	$login_box_string='';
	for ($icnt=0,$n=count($detail_info);$icnt<$n;$icnt++)
	{
		$replace_string=$replace_html;
		$replace_string=str_replace("{{LINK_1}}",$detail_info[$icnt][0],$replace_string);
		$replace_string=str_replace("{{NAME}}",$detail_info[$icnt][1],$replace_string);
		$login_box_string.=$replace_string;;
	}
	
	echo '<div class="card box-shadow">';
	echo '<div class="card-header">';
	echo '<strong>';
	echo BOX_HEADING_LOGIN_BOX_MY_ACCOUNT;
	echo '</strong>';
	echo '</div>';
	echo '<div>';
	echo '<ul class="list-group">' . $login_box_string . '</ul>';
	echo '</div>';
	echo '</div>';
	echo '<br class="clearfloat">';
?><!-- my_account_info_eof //-->
<?php
  }
}
?>

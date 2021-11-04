<?php
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

############################ new code check the login/server load
//check for enforcement
$show_normal_page=1;
//check for the max number logged in
if( null!==RESTRICT_NO_LOGONS && RESTRICT_NO_LOGONS >0)
{
// this next line is lifted from the admin whos online file
	$xx_mins_ago = (time() - SEATPLAN_TIMEOUT + 60);//15 mins = 900 secs
	tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");

	//get count of logged on customers i.e. customer_id >0
	$whos_online_query = tep_db_query("select count(*) as count from " . TABLE_WHOS_ONLINE. " where customer_id > 0");
	$whos_online=tep_db_fetch_array($whos_online_query);
	if ($whos_online['count'] >= RESTRICT_NO_LOGONS){
	$show_normal_page=0;
	} 
}
### end of whose online stuff
 
	if(SEATPLAN_LOGIN_ENFORCED=='true')
	{
	// if the enforced login is set then check server load.
	//$_GET['out']='php_array';
	//call the Linfo class 
	//include(DIR_FS_CATALOG.'phpinfo/index.php'); 
	$url=tep_href_link('phpinfo/index.php','out=php_array');
	$ch = curl_init();
	
	// Set query data here with the URL
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, '3');
	$server_array= trim(curl_exec($ch));
	curl_close($ch);
	//echo '--';
	//class edited to just produce server load in % or a different string if error//
	//$load =  str_replace('%','',$server_array['Load']); 
	$load =  str_replace('%','',$server_array); 
	//if load is integer
	if (is_numeric($load) && null!==(SEATPLAN_LOGIN_ENFORCED_LOAD) && ($load > SEATPLAN_LOGIN_ENFORCED_LOAD))
	{
	$show_normal_page=0; 
	}
	//if it is not
		if (!is_numeric($load))
			//we stopped the email send 2019
		{	//tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'Server Load Check Failure', 'The customer login page has just failed to get the server load figure - the server response to the request was : '.$load, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		}
	}
	
	if(SEATPLAN_LOGIN_ENFORCED=='true' && !$FSESSION->is_registered['customer_id'])
		{
			$sign_up_style="style=\"display:none;\"";
		}else
		{ 
			$sign_up_style="style=\"display:true;\"";
		}
			if(HIDE_SIGNUP=='false')
			{
			$sign_up_style="style=\"display:true;\"";
			}else
			{
			$sign_up_style="style=\"display:none;\"";
			}
	
	
	if ($show_normal_page==0)
	{
		//display 'sorry' screen
		#### 
		echo SEATPLAN_LOGIN_ENFORCED_LOAD_TEXT;
		echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, '' , 'SSL')); ?>
		
		<table>
		<tr>
		 <td>
			<?php echo SEATPLAN_LOGIN_ENFORCED_LOAD_DESC; ?>
		 </td>
		</tr>
		<tr>
		<td><?php echo tep_template_image_submit('', IMAGE_BUTTON_LOGIN) .'&nbsp;&nbsp;'; ?></td>
		</tr>
		</table>
		<?php 
	}else
	{
		########################## end new code
		echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process&' . tep_get_all_get_params(array("action")) , 'SSL')); ?>
		
		<div class="container-fluid wow fadeInUp">
		
		<div class="section-header">
		<h2><?php echo HEADING_TITLE; ?></h2>
		</div>
		<?php		
		//print_r ($messageStack);
		if ($messageStack->size('login') > 0) 
		{
		?>
		<div><?php echo $messageStack->output('login'); ?></div>
		<?php
		}
		?>
		<div class="row" style="height:100%">
			<div class="col-sm-6 cm-login-form">
			<h2><?php echo HEADING_RETURNING_CUSTOMER; ?></h2>
			<div class="mb-3">
			<?php 
			echo tep_draw_input_field('email_address', NULL,'required aria-required="true" placeholder="' . (ACCOUNT_USERNAME=="true"?ENTRY_USERNAME:ENTRY_EMAIL_ADDRESS) . '"', '' . (ACCOUNT_USERNAME=="true"?'':'email') . '');
			?>
			</div>
			<div class="mb-3">
			<?php 
			echo tep_draw_input_field('password',NULL,'required aria-required="true" autocomplete="off" placeholder="' . ENTRY_PASSWORD . '"', 'password');
			?>
			</div>
			<div><div style="width:250px" class="pull-left"><p><small><?php echo '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>'; ?></small></p></div>
			
			<div style="position:relative;bottom:5px" class="pull-right"><?php echo tep_template_image_submit('', IMAGE_BUTTON_LOGIN) .''; ?></div></div>
			</div>
			<div class="col-sm-6 cm-create-account-link" <?php echo $sign_up_style; ?>>
			<h2><?php echo HEADING_NEW_CUSTOMER; ?></h2>
			<p><?php echo TEXT_NEW_CUSTOMER_INTRODUCTION; ?></p>
			<?php echo '<a class="pull-right" href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>
			</div>
		</div>
		</div>
		<!-- #login -->

		<?php
		// PWA BOF
		if (defined('PURCHASE_WITHOUT_ACCOUNT') && ($cart->count_contents() > 0) && (PURCHASE_WITHOUT_ACCOUNT =='yes' or PURCHASE_NO_ACCOUNT=='yes')) 
		{//allow PWA
			?>
			<div <?php echo $sign_up_style; ?>>
			<br><br>
			<h2 class="h3"><?php echo HEADING_GUEST; ?></h2>

				<p><?php echo TEXT_GUEST_INTRODUCTION; ?></p>
		   
			<?php echo '<a href="' .  tep_href_link(FILENAME_CREATE_ACCOUNT, 'guest=guest', 'SSL') . '">
				<div style="float:right">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</div></a>'; ?>
				</div>
			<?php 
		}
	  // PWA EOF
		?>
	<?php  
	}
	?>
	<br><br></form>
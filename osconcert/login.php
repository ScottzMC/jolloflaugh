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
	$action=$FREQUEST->getvalue("action");
	
	// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
	if ($FSESSION->STARTED == false) 
	{
	if ( !isset($_GET['cookie_test']) ) 
	{
	  $all_get = tep_get_all_get_params();
	  tep_redirect(tep_href_link('login.php', $all_get . (empty($all_get) ? '' : '&') . 'cookie_test=1', 'SSL'));
	}

	tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
	}

	require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_LOGIN);
  
	$serverDate = date('Y-m-d H:i:s',getServerDate(false));
	if ($action=="") $action=($FREQUEST->postvalue("user")!=""?"processh":'');
	$error = false; 
	
	//let's delete some old sessions
	//tep_db_query("delete from " . TABLE_SESSIONS . " where expiry < '" . (time()-3600) . "'");
    $purge_query = tep_db_query("select * from " . TABLE_SESSIONS . " where expiry < '" . (time()-60 ) . "'"); 
	while ($purge_results = tep_db_fetch_array($purge_query)){
		//temp_basket
		
		tep_db_query("delete from " . TABLE_CUSTOMERS_TEMP_BASKET . " where customers_id = '" . $purge_results['sesskey'] . "'");
		//basket
		//get customer id from the session      
		      $session_data = trim($purge_results['value']);
		 	  $pieces = explode('customer_id|s:1:"',$session_data);
        	  $smaller_pieces = explode ('"',$pieces[1]);
			  tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $smaller_pieces[0] . "'");
		
		//session
		tep_db_query("delete from " . TABLE_SESSIONS . " where sesskey = '" . $purge_results['sesskey'] . "'");
	}

	if($action !='return' && $FSESSION->is_registered('customer_id')) tep_redirect(tep_href_link('account.php'));
	if (defined('PURCHASE_NO_ACCOUNT')  && ( PURCHASE_NO_ACCOUNT == 'yes'))
 	{//forcing no account
	tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT, 'guest=guest', 'SSL'));
	}
	
	if ($FREQUEST->cookievalue('customer_is_guest'))
	{ 
	setcookie ("customer_is_guest", "", time() - 2592000);
	}
	if ($action == 'process' || $action == 'processh' || $action=='return') 
	{
		if ($action=="process") 
		{
			$email_address = $FREQUEST->postvalue('email_address');
			$password = $FREQUEST->postvalue('password');
		} else {
			$email_address = $FREQUEST->postvalue('user');
			$password = $FREQUEST->postvalue('pass');
		}
	// Check if email exists
	$add_option="";
	//GDPR May 25th 2018
	if (ACCOUNT_USERNAME=="true") $add_option=" or  is_blocked = 'N' AND customers_username='" . tep_db_input($email_address) . "'";
	
    $check_customer_query = tep_db_query("select customers_id, customers_firstname,customers_groups_id, customers_password, customers_email_address, customers_default_address_id, guest_account, encryption_style from " . TABLE_CUSTOMERS . " where is_blocked = 'N' AND customers_email_address = '" . tep_db_input($email_address) . "' " . $add_option);
    if (!tep_db_num_rows($check_customer_query)) 
	{ 
      $error = true;
	  if($action=='return' && $error) 
	  {
	  	echo 'Failed';
		exit;
	  }
    } else {
      $check_customer = tep_db_fetch_array($check_customer_query);
// Check that password is good
      if (!tep_validate_password($password, $check_customer['customers_password'],$check_customer['encryption_style'])) 
	  {
        $error = true;
		if($action=='return' && $error) 
		{
	  	echo 'Failed';
		exit;
	  }
      } else 
	  { 
        /*if (SESSION_RECREATE == 'True') {
          tep_session_recreate();
        }*/
		/* please check the same in application_top for automatic login */
		if (!($check_customer['encryption_style']=="" && ENCRYPTION_STYLE=="O") && $check_customer['encryption_style']!=ENCRYPTION_STYLE && $check_customer["customers_id"]>0)
		{
			$new_password=tep_encrypt_password($password);
			tep_db_query("UPDATE " . TABLE_CUSTOMERS . " set customers_password='" . tep_db_input($new_password) . "', encryption_style='" . ENCRYPTION_STYLE . "' where customers_id=" . (int)$check_customer["customers_id"]);
		}

        $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
        $check_country = tep_db_fetch_array($check_country_query);
		
		$customer_id = $check_customer['customers_id'];
        $customer_default_address_id = $check_customer['customers_default_address_id'];
		
        $customer_first_name = $check_customer['customers_firstname'];
        $customer_country_id = $check_country['entry_country_id'];
        $customer_zone_id = $check_country['entry_zone_id'];
	    $FSESSION->set('customer_id',$customer_id);
      	$FSESSION->set('customer_default_address_id',$customer_default_address_id);
     	$FSESSION->set('customer_first_name',$customer_first_name);
	    $FSESSION->set('customer_country_id',$customer_country_id);
    	$FSESSION->set('customer_zone_id',$customer_zone_id);
        $FSESSION->set('customers_groups_id',$check_customer['customers_groups_id']);
		if ($FSESSION->is_registered("customer_auto_name"))
			$FSESSION->remove('customer_auto_name');

		  tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon ='". $serverDate ."', customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$FSESSION->customer_id . "'");
		// update survey flag if customer exist
		$FSESSION->set('error_count',0); 
		$sName=$FSESSION->name;
		$sKey=$FREQUEST->getvalue($sName);

// restore cart contents
        $cart->restore_contents();
		
		# Start for wallet payment is installed
		if(tep_payment_installed('wallet.php'))
		{
		// modified for wallet payment -start
		// find the current wallet balance
		$balance=tep_get_wallet_balance($FSESSION->customer_id);
		$wallet_text='<a href="' . tep_href_link(FILENAME_ACCOUNT) . '">'  . sprintf(TEXT_CURRENT_WALLET_BALANCE,$currencies->format($balance)) . '</a>';
		$messageStack->add_session("wallet",$wallet_text,"warning");
		// modified for wallet payment -end
        }
		#End for wallet payment is installed
		$encript_value=defined('ENCRYPTION_HASH_VALUE')?ENCRYPTION_HASH_VALUE:'@sC@mmRes';
		setcookie("osCuser",$email_address,time()+60*60*24*30,$cookie_path,$cookie_domain);
		setcookie("osCpass",md5($check_customer["customers_password"] . $encrypt_value),time()+60*60*24*30,$cookie_path,$cookie_domain);
		if ($FSESSION->is_registered("customer_auto_name"))
			$FSESSION->remove('customer_auto_name');
		if($action=='return')
		{
		 echo 'Login'.'@'.tep_db_prepare_input($FSESSION->customer_id);
	 	exit;
	 }

        if (sizeof($navigation->snapshot) > 0) 
		{
          if(is_array($navigation->snapshot['post']) && count($navigation->snapshot['post'])>0)
		  {
			$origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array($FSESSION->name)), $navigation->snapshot['mode']);
 			$post_array=$navigation->snapshot['post'];
			$navigation->clear_snapshot();
			$submit_string="<html><head></head><body><form name='frm_login_post' id='frm_login_post' method='post' action='".$origin_href."'>";
			//while(list($name,$value)=each($post_array)) 
			foreach($post_array as $name => $value)
			$submit_string.="<input type='hidden' name='".$name."' id='".$name."' value='".$value."'>";										
			$submit_string.="</form></body></html><script language='javascript'>if(document.frm_login_post) frm_login_post.submit();</script>";
			echo $submit_string; 	          	
          }else 
		  {
          	$origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array($FSESSION->name)), $navigation->snapshot['mode']);
          	$navigation->clear_snapshot();
          	tep_redirect($origin_href);	
          }
        } else {
			//MyAccount Login
			if(MY_ACCOUNT_LOGIN=='true')  
			{
				tep_redirect(tep_href_link('account.php'));
			}
			if($cart->count_contents() > 0) 
			{
				tep_redirect(tep_href_link(FILENAME_SHOPPING_CART,'','SSL'));
			}
			//ATTN
			if(SHOW_FEATURED_CATEGORIES=='true')
			{
			tep_redirect(tep_href_link(FILENAME_FEATURED_CATEGORIES,'','SSL'));
			}
			else{
			tep_redirect(tep_href_link(FILENAME_DEFAULT,'','SSL'));	
			}
			//tep_redirect(tep_href_link(FILENAME_DEFAULT,'','SSL'));
        }
      }
    }
	
  }

  if ($error == true) 
  {
    $messageStack->add('login', (ACCOUNT_USERNAME=="true"?TEXT_LOGIN_ERROR_USER:TEXT_LOGIN_ERROR));
  }
  
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_LOGIN, '', 'SSL'));

  $content = CONTENT_LOGIN;
 // $javascript = $content . '.js';
  	
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
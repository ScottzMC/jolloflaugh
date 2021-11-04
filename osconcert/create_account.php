<?php
	/*Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License*/


	define( '_FEXEC', 1 );
	
	$format=array('d-m-Y'=>'dd-mm-yyyy',
				  'm-d-Y'=>'mm-dd-yyyy',
				  'Y-m-d'=>'yyyy-mm-dd');
	
	require('includes/application_top.php');
	//MailerLite
        require_once dirname(__FILE__).'/ML_Subscribers.php';
        require_once dirname(__FILE__).'/ML_Lists.php';
		$serverDate = date('Y-m-d H:i:s',getServerDate(false));
        $API_KEY = ML_KEY;
        $mail_API_groupid = MAIL_API_GROUP_ID;
        $ML_Subscribers = new ML_Subscribers($API_KEY);
        //$ML_Lists = new ML_Lists($API_KEY);
        //$lists = $ML_Lists->getAll( );
        //print_r($lists);
	//ajax end
	//if($FSESSION->is_registered('customer_id')) tep_redirect(tep_href_link('account.php'));//sakwoya commented out
	// PWA EOF
	if (isset($_GET['guest']) && $cart->count_contents() < 1) tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
	// PWA BOF
	//PWA BOF addition  
	if (isset($_GET['guest'])){ setcookie('customer_is_guest','1', 0);} //set a cookie that expires on browser close or checkout_success

	// needs to be included earlier to set the success message in the messageStack
	require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_CREATE_ACCOUNT_NEW);
	
	include(DIR_WS_LANGUAGES . $FSESSION->language . '/templates.php');
	
	$action=tep_db_prepare_input($FREQUEST->postvalue('action'));
	if ($action=="process"){
		$POST_=$FREQUEST->getRef("POST");
		if (count($POST_)>0){
			reset($POST_);
			//FOREACH
			//while(list($key,)=each($POST_)){
				foreach($POST_ as $key=>$value) 
				{
				$ACCOUNT[$key]=$FREQUEST->postvalue($key);
				
			}	
		}
	} else {
		$ACCOUNT=array();
	}

	require(DIR_WS_CLASSES . 'customerAccount.php');
	$customerAccount=new customerAccount();
	$fieldsDesc=$customerAccount->getFieldsDescription();

	$action=tep_db_prepare_input($FREQUEST->postvalue('action'));
	if ($action=="process"){
		$CUSTOMER=array();
		$ADDRESS=array();
		$INFO=array();
		$EXTRA=array();
		$PREV_ERROR=array();
		$pass=true;
		for ($icnt=0,$n=count($fieldsDesc);$icnt<$n;$icnt++)
		{
			$fieldDesc=&$fieldsDesc[$icnt];
			if (method_exists($customerAccount,"check__" . $fieldDesc['uniquename']))
			{
				$pass&=$customerAccount->{"check__" . $fieldDesc['uniquename']}($fieldDesc); // Change $fieldDesc instead of &$fieldDesc By Roy
			} else {
				$pass&=$customerAccount->commonCheck($fieldDesc); // Change $fieldDesc instead of &$fieldDesc By Roy
			}
		}
		if ($pass)
		{
			///NEW??
			//$CUSTOMER['customers_gender']='';
			//$CUSTOMER['customers_selected_template']='';
			//$CUSTOMER['customers_second_email_address']='';
			//$CUSTOMER['customers_second_telephone']='';
			$CUSTOMER['mail_status']='';
			$CUSTOMER['customers_token']='';
			$CUSTOMER['customers_timestamp']=0;
			//$CUSTOMER['customers_dob']='2017-11-01 00:00:00';
		   // Change for data insert into Mailerlite API RME
			$subscriber = array(
		    'email' => $CUSTOMER['customers_email_address'],
		    'name' => $CUSTOMER['customers_firstname']." ".$CUSTOMER['customers_lastname'],
		    'fields' => array(
						array( 'name' => 'customers_telephone', 'value' => $CUSTOMER['customers_telephone'] ),
                        array( 'name' => 'customers_gender', 'value' => $CUSTOMER['customers_gender'] ),
						array( 'name' => 'customers_newsletter', 'value' => $CUSTOMER['customers_newsletter'] ),
                        array( 'name' => 'company', 'value' => $ADDRESS['entry_company'] ),
                        array( 'name' => 'postcode', 'value' => $ADDRESS['entry_postcode'] ),
                        array( 'name' => 'city', 'value' => $ADDRESS['entry_city'] )		       
		    )
                    );
		if(ML_KEY>0)
		{
			if($CUSTOMER['customers_newsletter'] ==1)
			{		
			$resultmailapi = $ML_Subscribers->setId($mail_API_groupid)->add( $subscriber );
			}
		}
        //testing
        // $myfile = fopen("account.txt", "a") or die("Unable to open file!");
        // $txt = date('Y-m-d h:i:s')." - Account Detail : ".$resultmailapi."\n" ;
        // fwrite($myfile, $txt);
        // Change for data insert into Mailerlite API RME
                   // print_r($subscriber);
                   //die;
			tep_db_perform(TABLE_CUSTOMERS,$CUSTOMER);
			$customer_id=tep_db_insert_id();
			$ADDRESS["customers_id"]=$customer_id;
			$ADDRESS["entry_customer_email"]=$CUSTOMER['customers_email_address'];
			tep_db_perform(TABLE_ADDRESS_BOOK,$ADDRESS);
			$address_id=tep_db_insert_id();
			tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '$address_id' where customers_id = '$customer_id'");
			//PWA
			//$guest_account_value = $_SESSION['customer_is_guest'];
			$guest_account_value='';
			if(isset($_COOKIE['customer_is_guest']))
			{ 
			$guest_account_value = $_COOKIE['customer_is_guest'];}
			else
			{
			$guest_account_value=0;
			}//1 for PWA null for non
			tep_db_query("update " . TABLE_CUSTOMERS . " set guest_account = '$guest_account_value' where customers_id = '$customer_id'");
			//PWA end
			$INFO["customers_info_id"]=$customer_id;
			$INFO["customers_info_number_of_logons"]=0;
			$INFO["customers_info_date_account_created"]=$serverDate;
			if (isset($INFO["customers_info_source_id"]))
			{
				if ($INFO["customers_info_source_id"]=='9999')
				{
					tep_db_query("insert into " . TABLE_SOURCES_OTHER . " (customers_id, sources_other_name) values ('$customer_id', '". $INFO["source_other"] . "')");
				}
				unset($INFO["source_other"]);
			}
			tep_db_perform(TABLE_CUSTOMERS_INFO,$INFO);
			if (count($EXTRA)>0){
				reset($EXTRA);
				//while(list($key,$value)=each($EXTRA))
				foreach($EXTRA as $key => $value)
				{
					$sql_array=array("customers_id"=>$customer_id,"uniquename"=>$key,"fieldvalue"=>$value);
					tep_db_perform(TABLE_CUSTOMERS_EXTRA_INFO,$sql_array);
				}
			}
			if(REVIEW_ACCOUNT=='no')
			{
			$FSESSION->set('customer_id',$customer_id);
			$FSESSION->set('customer_default_address_id',$address_id);
			$FSESSION->set('customer_first_name',$ACCOUNT['#1_firstname']);
			$FSESSION->set('customer_country_id',$ACCOUNT['entry_country']);
			$FSESSION->set('customer_zone_id',$ACCOUNT['entry_zone_id']);
			}

			$cart->restore_contents();
			
			//Send email
			//  if (!$FSESSION->is_registered('customer_is_guest')){//skip welcome email for PWA
			if(!isset($_COOKIE['customer_is_guest']))
			{
				$send_details=array();
				
				//build merge details
				$merge_details=array();
				//For Multi Language we need this full message//editable in templates.php
				$merge_details[ACCOUNT_TEXT_1]=TEXT_CUS_MESSAGE1;
				
				$merge_details[TEXT_FN]=$ACCOUNT['#1_firstname'];
				$merge_details[TEXT_LN]=$ACCOUNT['#1_lastname'];

				if (isset($ACCOUNT["#1_gender"])) 
				{
					if ($gender == 'm') {
						$merge_details[TEXT_GR] = sprintf(EMAIL_GREET_MR, $ACCOUNT['#1_lastname']);
					} else {
						$merge_details[TEXT_GR] = sprintf(EMAIL_GREET_MS, $ACCOUNT['#1_lastname']);
					}
				} else {
					$merge_details[TEXT_GR] = sprintf(EMAIL_GREET_NONE, $ACCOUNT['#1_firstname']);
				}

				define("TEXT_SL","STORE_LINK");
				$merge_details[TEXT_SL]=tep_href_link(FILENAME_LOGIN);
				$merge_details[TEXT_SM]=STORE_NAME;
				$merge_details[TEXT_SN]=STORE_OWNER;
				$merge_details[TEXT_SE]=STORE_OWNER_EMAIL_ADDRESS;
				$merge_details[TEXT_LE]=$ACCOUNT['customers_email_address'];
				$merge_details[TEXT_AU]=$ACCOUNT['customers_username'];
				if((REVIEW_ACCOUNT=='yes')or(ADMIN_SIGNUP_NOTIFICATION=='true'))
				{
				$merge_details[TEXT_LP]="";
				}else{
				$merge_details[TEXT_LP]=$FREQUEST->postvalue('customers_password');	
				}
				$merge_details[TEXT_SP]=HTTP_SERVER . DIR_WS_CATALOG . "images/".COMPANY_LOGO;
				$send_details[0]['to_name'] = $CUSTOMER['customers_firstname'] . ' ' .  $CUSTOMER['customers_lastname'];
				$send_details[0]['to_email'] =  $CUSTOMER['customers_email_address'];
				// . ' , ' . STORE_OWNER_EMAIL_ADDRESS;
				$send_details[0]['from_name']=STORE_OWNER;
				$send_details[0]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
				if(REVIEW_ACCOUNT=='yes'){
				$send_details[1]['from_name']=STORE_OWNER;
				$send_details[1]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
				$send_details[1]['to_email']=STORE_OWNER_EMAIL_ADDRESS;
				}
				//PWA
				// if (PURCHASE_WITHOUT_ACCOUNT=='yes'){
				// tep_send_default_email("PWA",$merge_details,$send_details);
				// }else{
				  if(REVIEW_ACCOUNT=='yes')
				  {
					  $mail_type=APV;
				  }else
				  {
					  $mail_type=CUS;
				  }
	 
				tep_send_default_email($mail_type,$merge_details,$send_details);

				//end PWA
			
			}//end of email prevention for PWA  
	
			tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
		}
		for ($icnt=0,$n=count($customerAccount->errors);$icnt<$n;$icnt++)
		{
			$messageStack->add('account',$customerAccount->errors[$icnt]);
		}
	}
	$JS_VARS["page"]=array('formName'=>'account','fieldsDesc'=>$fieldsDesc,'dateFormat'=>$format[EVENTS_DATE_FORMAT],'formErrText'=>str_replace("\\n","--",JS_ERROR));
	
	 // PWA BOF
	 if (!isset($_GET['guest']) && !isset($_POST['guest']))
	 {
	   $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
	 }else
	 {
	   $breadcrumb->add(NAVBAR_TITLE_PWA, tep_href_link(FILENAME_CREATE_ACCOUNT, 'guest=guest', 'SSL'));
	 }
	// PWA EOF
	$content = CONTENT_CREATE_ACCOUNT_NEW;
	
	require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
	require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
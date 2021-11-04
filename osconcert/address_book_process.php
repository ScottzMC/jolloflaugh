<?php
	/*Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	https://www.osconcert.com

  	Copyright (c) 2020 osConcert

	Released under the GNU General Public License*/


	define( '_FEXEC', 1 );
	
	$format=array('d-m-Y'=>'dd-mm-yyyy',
				  'm-d-Y'=>'mm-dd-yyyy',
				  'Y-m-d'=>'yyyy-mm-dd');


	//if(isset($joomla_include)) $usersConfig = &JComponentHelper::getParams('com_users');	
	require('includes/application_top.php');


	$serverDate = date('Y-m-d H:i:s',getServerDate(false));
	$address_book_id=tep_db_prepare_input($FREQUEST->getvalue('edit'),'int','','');
	$del_address_book_id=tep_db_prepare_input($FREQUEST->getvalue('delete'),'int','');
	
	if(!$FSESSION->is_registered('customer_id')) tep_redirect(tep_href_link('login.php'));

	$action=tep_db_prepare_input($FREQUEST->postvalue('action'));
	if ($action==""){
		$action=tep_db_prepare_input($FREQUEST->getvalue('action'));
	}
	// needs to be included earlier to set the success message in the messageStack
	require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_ADDRESS_BOOK_PROCESS_NEW);
	
	if ($action!='' && $action == 'deleteconfirm' && $del_address_book_id!='' && is_numeric($del_address_book_id)) {
		tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$del_address_book_id . "' and customers_id = '" . (int)$FSESSION->customer_id . "'");
		
		$messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_DELETED, 'success');
	
		tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
	}

	require(DIR_WS_CLASSES . 'customerAccount.php');
	$customerAccount=new customerAccount();

	$ACCOUNT=array();	
	$fieldsDesc=array();
	
	$country_check=tep_db_prepare_input($FREQUEST->postvalue('entry_country'));
	if ($country_check == 999){
	    $cart->reset(true);
		$FSESSION->set('error_count',0);
		$FSESSION->remove('box_office_refund');
		$FSESSION->remove('BoxOffice');
		$FSESSION->remove('sendto');
		$FSESSION->remove('billto');
		$FSESSION->remove('shipping');
		$FSESSION->remove('payment');
		$FSESSION->remove('comments');
		$FSESSION->remove('order_timestamp');
		$FSESSION->remove('receiptNo');
		$FSESSION->remove('transactionNr');
		$FSESSION->remove('coupon');
		$FSESSION->remove('ccno');
		$FSESSION->remove('gv_redeem_code');
		$FSESSION->remove('billto_array');
		$FSESSION->remove('sendto_array');
		$FSESSION->remove('paypal_ipn_started');
		$FSESSION->remove('other');
		$FSESSION->remove('field_1'); 
		$FSESSION->remove('field_2');
		$FSESSION->remove('field_3');
		$FSESSION->remove('field_4');
		$FSESSION->remove('credit_covers');
	    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
	}

	$primary=tep_db_prepare_input($FREQUEST->postvalue('set_primary'));
    if($action!='' && ($action=='process' || $action=='update')){
//        echo $action.'>>';
//        exit;
    }

	if ($action=="" && is_numeric($address_book_id)){
		$customer_query=tep_db_query("select a.* from " . TABLE_ADDRESS_BOOK . " a  where a.customers_id='" . $FSESSION->customer_id . "' and a.address_book_id=" . (int)$address_book_id);
		if (tep_db_num_rows($customer_query)<=0) {
			$messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);
			
			tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
		}
		$customer_result=tep_db_fetch_array($customer_query);
	} else if ($action=="process" || $action=="update"){
		$POST_=$FREQUEST->getRef("POST");
		if (count($POST_)>0){
			reset($POST_);
			//FOREACH
			//while(list($key,)=each($POST_)){
			foreach($POST_ as $key => $value) {
			
				$ACCOUNT[$key]=$FREQUEST->postvalue($key);
			}
		}
	}

	//get the required display fields
	$query=tep_db_query("SELECT cif.*,cifd.label_text,cifd.input_description,cifd.error_text,cifd.input_title from ".  TABLE_CUSTOMERS_INFO_FIELDS . " cif, " . TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION . " cifd where cif.info_id=cifd.info_id and cifd.languages_id=" . $FSESSION->languages_id. " and cif.display_page like '%A%' and cif.active='Y' order by cif.sort_order");
	$icnt=0;

	while($fieldsDesc[$icnt]=tep_db_fetch_array($query)){
		$fieldDesc=&$fieldsDesc[$icnt];

		if (strpos($fieldDesc['error_text'],"==")!==false){
			$fieldDesc['error_text']=str_replace(array("==MIN==","==MAX==","==DATE_FORMAT==","==EX_DATE=="),array($fieldDesc['textbox_min_length'],$fieldDesc['textbox_max_length'],EVENTS_DATE_FORMAT,format_date('1970-05-20')),$fieldDesc['error_text']);
		}
		if (strpos($fieldDesc['input_description'],"==")!==false){
			$fieldDesc['input_description']=str_replace(array("==MIN==","==MAX==","==DATE_FORMAT==","==EX_DATE=="),array($fieldDesc['textbox_min_length'],$fieldDesc['textbox_max_length'],EVENTS_DATE_FORMAT,format_date('1970-05-20')),$fieldDesc['input_description']);
		}
		$icnt++;
		if ($action=="process" || $action=="update") continue;
		if (method_exists($customerAccount,"getdb__" . $fieldDesc['uniquename'])){
			$customerAccount->{"getdb__" . $fieldDesc['uniquename']}($customer_result); // Change by Roy
		} else {
			$customerAccount->commonEntries($fieldDesc['uniquename'],$customer_result); // Change by Roy
		}
	}

	unset($fieldsDesc[$icnt]);

	if ($action=="process" || $action=="update"){
		$CUSTOMER=array();
		$ADDRESS=array();
		$INFO=array();
		$EXTRA=array();
		$PREV_ERROR=array();
		$pass=true;
		$FREQUEST->setvalue("customers_id",$FSESSION->customer_id,"POST");
		for ($icnt=0,$n=count($fieldsDesc);$icnt<$n;$icnt++){
			$fieldDesc=&$fieldsDesc[$icnt];
			if (method_exists($customerAccount,"check__" . $fieldDesc['uniquename'])){
				$pass&=$customerAccount->{"check__" . $fieldDesc['uniquename']}($fieldDesc); // Change by Roy
			} else {
				$pass&=$customerAccount->commonCheck($fieldDesc); // Change by Roy
			}
		}
		if($action=='update') {
            /*
            echo $FSESSION->customer_id.'>>';
            echo $address_book_id.'>>';
            print_r($ADDRESS);
            echo '>>';
            */
            if($address_book_id==''){
                    $ADDRESS['customers_id']=(int)$FSESSION->customer_id;
                    tep_db_perform(TABLE_ADDRESS_BOOK,$ADDRESS);
					$new_address_book_id=tep_db_insert_id();
                    if($primary == 'on'){
                            $address_book_id=$new_address_book_id;
                            $FSESSION->set('customer_default_address_id',(int)$address_book_id);
                    }
            } else{
                    tep_db_perform(TABLE_ADDRESS_BOOK,$ADDRESS,"update","customers_id='" .(int)$FSESSION->customer_id . "' and address_book_id='" .$address_book_id ."'");
            }
			// reregister session variables
			if ($primary == 'on' || ($address_book_id == $FSESSION->customer_default_address_id) ) {
			  $FSESSION->set('customer_first_name',$CUSTOMER['customers_firstname']);
			  $FSESSION->set('customer_country_id',$ADDRESS['entry_country_id']);
			  $FSESSION->set('customer_zone_id',(int)$ADDRESS['entry_zone_id']);
			  $FSESSION->set('customer_default_address_id',(int)$address_book_id);

			  $CUSTOMER['customers_default_address_id']=(int)$address_book_id;
			  tep_db_perform(TABLE_CUSTOMERS, $CUSTOMER, 'update', "customers_id = '" . (int)$FSESSION->customer_id . "'");
			}
		} else {
			$ADDRESS['customers_id'] = (int)$FSESSION->customer_id;
			tep_db_perform(TABLE_ADDRESS_BOOK, $ADDRESS);
			$address_book_id=(int)tep_db_insert_id();
			// reregister session variables
			if (($FREQUEST->postvalue('set_primary') == 'on')) {
				$FSESSION->set('customer_first_name',$CUSTOMER['customers_firstname']);
				$FSESSION->set('customer_country_id',$ADDRESS['entry_country_id']);
				$FSESSION->set('customer_zone_id',(int)$ADDRESS['entry_zone_id']);
				if ($primary == 'on') $FSESSION->set('customer_default_address_id',$address_book_id);
				
				if (($FREQUEST->postvalue('set_primary') == 'on')) $CUSTOMER['customers_default_address_id'] = (int)$address_book_id;
				
				tep_db_perform(TABLE_CUSTOMERS, $CUSTOMER, 'update', "customers_id = '" . (int)$FSESSION->customer_id . "'");
			}
		}	
		tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
		
		for ($icnt=0,$n=count($customerAccount->errors);$icnt<$n;$icnt++){
			$messageStack->add('addressbook',$customerAccount->errors[$icnt]);
		}
	}
	
	if ($address_book_id!='' && is_numeric($address_book_id)) {
		$entry_query = tep_db_query("select entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_customer_email, entry_postcode, entry_city, entry_state, entry_zone_id, entry_country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$FSESSION->customer_id . "' and address_book_id = '" . (int)$address_book_id . "'");
		
		if (!tep_db_num_rows($entry_query)) {
			$messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);
			
			tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
		}
		
		$entry = tep_db_fetch_array($entry_query);
	} elseif ($del_address_book_id!='' && is_numeric($del_address_book_id)) {
		if ($del_address_book_id == $FSESSION->customer_default_address_id) {
			$messageStack->add_session('addressbook', WARNING_PRIMARY_ADDRESS_DELETION, 'warning');
			
			tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
		} else {
			$check_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$del_address_book_id . "' and customers_id = '" . (int)$FSESSION->customer_id . "'");
			$check = tep_db_fetch_array($check_query);
			
			if ($check['total'] < 1) {
				$messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);
				
				tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
			}
		}
	} else {
		$entry = array();
	}
	
	if ($del_address_book_id=="" && $address_book_id=="") {
		if (tep_count_customer_address_book_entries() >= MAX_ADDRESS_BOOK_ENTRIES) {
			$messageStack->add_session('addressbook', ERROR_ADDRESS_BOOK_FULL);
			
			tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
		}
	}
	
	$JS_VARS["page"]=array('formName'=>'account','fieldsDesc'=>$fieldsDesc,'dateFormat'=>$format[EVENTS_DATE_FORMAT],'formErrText'=>str_replace("\\n","--",JS_ERROR));
	
	$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS_NEW, '', 'SSL'));
	$content =CONTENT_ADDRESS_BOOK_PROCESS_NEW;
	
	require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
	require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
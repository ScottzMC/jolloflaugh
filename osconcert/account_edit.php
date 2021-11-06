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

	require('includes/application_top.php');	
	$serverDate = date('Y-m-d H:i:s',getServerDate(false));
	
		
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


	if(!$FSESSION->is_registered('customer_id')) tep_redirect(tep_href_link('index.php'));
	

	// needs to be included earlier to set the success message in the messageStack
	require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_ACCOUNT_EDIT_NEW);

	$action=tep_db_prepare_input($FREQUEST->postvalue('action'));
	require(DIR_WS_CLASSES . 'customerAccount.php');
	$customerAccount=new customerAccount();

	$ACCOUNT=array();	
	$fieldsDesc=array();
	
	if ($action!="process"){
		$customer_query=tep_db_query("select a.*,c.* from " . TABLE_CUSTOMERS ." c, " . TABLE_ADDRESS_BOOK . " a  where c.customers_id='" . $FSESSION->customer_id . "' and c.customers_default_address_id=a.address_book_id and c.customers_id=a.customers_id");
		$customer_result=tep_db_fetch_array($customer_query);
		$extra_query=tep_db_query("SELECT uniquename,fieldvalue from " . TABLE_CUSTOMERS_EXTRA_INFO . " where customers_id=" .$FSESSION->customer_id);
		while($extra=tep_db_fetch_array($extra_query)){
			$customer_result[$extra['uniquename']]=$extra["fieldvalue"];
		}
	} else {
		$POST_=$FREQUEST->getRef("POST");
		if (count($POST_)>0){
			reset($POST_);
			//while(list($key,)=each($POST_)){
			foreach($POST_ as $key => $value) {
				$ACCOUNT[$key]=$FREQUEST->postvalue($key);
			}
		}
	}

	//get the required display fields
	$query=tep_db_query("SELECT cif.*,cifd.label_text,cifd.input_description,cifd.error_text,cifd.input_title from ".  TABLE_CUSTOMERS_INFO_FIELDS . " cif, " . TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION . " cifd where cif.info_id=cifd.info_id and cifd.languages_id=" . $FSESSION->languages_id. " and cif.display_page like '%E%' and cif.active='Y' order by cif.sort_order");
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
		if ($action=="process") continue;
		if (method_exists($customerAccount,"getdb__" . $fieldDesc['uniquename'])){
			$customerAccount->{"getdb__" . $fieldDesc['uniquename']}($customer_result); // Change by Roy
		} else {
			$customerAccount->commonEntries($fieldDesc['uniquename'],$customer_result); // Change by Roy
		}
	}
	unset($fieldsDesc[$icnt]);
	if ($action=="process"){
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
				$pass&=$customerAccount->commonCheck($fieldDesc);
			}
		}
		if ($pass){
			tep_db_perform(TABLE_CUSTOMERS,$CUSTOMER,"update","customers_id='" . $FSESSION->customer_id. "'");
			if (count($ADDRESS)>0){
				tep_db_perform(TABLE_ADDRESS_BOOK,$ADDRESS,"update","customers_id='" .$FSESSION->customer_id . "'");
			}
			if (count($INFO)>0){
				if (isset($INFO["customers_info_source_id"])){
					if ($INFO["customers_info_source_id"]=='9999'){
						tep_db_query("update " . TABLE_SOURCES_OTHER . " set  sources_other_name='". $INFO["source_other"] . "' where customers_id='". $FSESSION->customers_id . "'");
					}
					unset($INFO["source_other"]);
				}
				tep_db_perform(TABLE_CUSTOMERS_INFO,$INFO);
			}
			if (count($EXTRA)>0){
				reset($EXTRA);
				//FOREACH
				//while(list($key,$value)=each($EXTRA)){
				foreach($EXTRA as $key => $value) 
				{
					tep_db_query("REPLACE into " . TABLE_CUSTOMERS_EXTRA_INFO . " values('" . tep_db_input($key) . "','" . tep_db_input($value) . "'," . $FSESSION->customer_id .")");
				}
			}
			$messageStack->add_session('account', SUCCESS_ACCOUNT_UPDATED, 'success');


			tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
		}
		for ($icnt=0,$n=count($customerAccount->errors);$icnt<$n;$icnt++){
			$messageStack->add('account',$customerAccount->errors[$icnt]);
		}
	}
	$JS_VARS["page"]=array('formName'=>'account','fieldsDesc'=>$fieldsDesc,'dateFormat'=>$format[EVENTS_DATE_FORMAT],'formErrText'=>str_replace("\\n","--",JS_ERROR));
	
	$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ACCOUNT_EDIT_NEW, '', 'SSL'));
	$content = CONTENT_ACCOUNT_EDIT_NEW;
	
	require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
	require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
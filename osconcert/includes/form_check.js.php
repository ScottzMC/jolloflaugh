<?php
defined('_FEXEC') or die();
?>
<script> 
var form = "";
var submitted = false;
var error = false;
var error_message = "";

function show_state(zone){
		//document.getElementById("entry_state1").style.display="none";
		//document.getElementById("entry_state").style.display="";
		var this_=document.getElementById("a_country");
		id=this_.options[this_.selectedIndex].value;
		var qry_str="";
		if(id){
			command="<?php echo tep_href_link(FILENAME_AFFILIATE_SIGNUP,'command=show_state','SSL',true,false);?>&country_id="+id+"&zone="+zone;
			do_get_command(command);	
		}
	} 
	
	function do_action(){
		var payment=document.affiliate_signup.elements["a_payment"];
		if(payment){ 
			if(payment.length>0){
				for(i=0;i<payment.length;i++){
					if(payment[i].checked){
						disable(payment[i].value);
						return;
					}	
				}	
			} else {
				if(payment.checked)
				disable(payment.value);
			}
		}
	}
	
	function disable(val){
		switch (val){
			case 'C':
				if(document.getElementById('affiliate_cheque')) document.getElementById('affiliate_cheque').style.display="";
				if(document.getElementById('affiliate_paypal')) document.getElementById('affiliate_paypal').style.display="none";
				if(document.getElementById('affiliate_bank')) document.getElementById('affiliate_bank').style.display="none";
				if(document.affiliate_signup.elements['a_payment_paypal']) document.affiliate_signup.elements['a_payment_paypal'].value="";
				if(document.affiliate_signup.elements['a_payment_bank_name']) document.affiliate_signup.elements['a_payment_bank_name'].value="";
				if(document.affiliate_signup.elements['a_payment_bank_branch_number']) document.affiliate_signup.elements['a_payment_bank_branch_number'].value="";
				if(document.affiliate_signup.elements['a_payment_bank_swift_code']) document.affiliate_signup.elements['a_payment_bank_swift_code'].value="";
				if(document.affiliate_signup.elements['a_payment_bank_account_name']) document.affiliate_signup.elements['a_payment_bank_account_name'].value="";
				if(document.affiliate_signup.elements['a_payment_bank_account_number']) document.affiliate_signup.elements['a_payment_bank_account_number'].value="";
				break;
			case 'P':
				if(document.getElementById('affiliate_cheque')) document.getElementById('affiliate_cheque').style.display="";
				if(document.getElementById('affiliate_paypal')) document.getElementById('affiliate_paypal').style.display="";
				if(document.getElementById('affiliate_bank')) document.getElementById('affiliate_bank').style.display="none";
				if(document.affiliate_signup.elements['a_payment_bank_name']) document.affiliate_signup.elements['a_payment_bank_name'].value="";
				if(document.affiliate_signup.elements['a_payment_bank_branch_number']) document.affiliate_signup.elements['a_payment_bank_branch_number'].value="";
				if(document.affiliate_signup.elements['a_payment_bank_swift_code']) document.affiliate_signup.elements['a_payment_bank_swift_code'].value="";
				if(document.affiliate_signup.elements['a_payment_bank_account_name']) document.affiliate_signup.elements['a_payment_bank_account_name'].value="";
				if(document.affiliate_signup.elements['a_payment_bank_account_number']) document.affiliate_signup.elements['a_payment_bank_account_number'].value="";
			break;	
			case 'D':
				if(document.getElementById('affiliate_cheque')) document.getElementById('affiliate_cheque').style.display="";
				if(document.getElementById('affiliate_paypal')) document.getElementById('affiliate_paypal').style.display="none";
				if(document.getElementById('affiliate_bank')) document.getElementById('affiliate_bank').style.display="";
				if(document.affiliate_signup.elements['a_payment_paypal']) document.affiliate_signup.elements['a_payment_paypal'].value="";
			break;
		}
	}
	function do_result(result){
	 if(result!='' && result.substr(result.indexOf('show_state'),10)=='show_state'){
	   result=result.substr(result.indexOf('show_state')+10,result.length);
	   var textArr=new Array();
		var valueArr=new Array();
		splt=result.split("^");
		valueArr=splt[1].split(",");		
		textArr=splt[1].split(",");
		var sel=document.getElementById("a_state");
		var sta1=document.getElementById("a_state1");
		if(valueArr.length==1) {
			if(sta1) sta1.style.display="";
			if(sel)sel.style.display="none";
		}else {
			if(sta1) {
				sta1.style.display="none";	
				sta1.value="";
			}
			if(sel) {
			  sel.style.display="";
			  sel.value="";	
			}
			optionElement=sel;
			// clear the previous details
			while(optionElement.options.length>0){
				optionElement.remove(optionElement.options.length - 1);
			}
			//create the new details
			for (icnt=0;icnt<textArr.length;icnt++){
			  var option = document.createElement('option');
			  option.text = textArr[icnt];
			  option.value = valueArr[icnt];
			  try {
				optionElement.add(option, null); // standards compliant; doesn't work in IE
			  }
			  catch(ex) {
				optionElement.add(option); // IE only
			  }
			}
			if(typeof(default_zone) != "undefined")
			for(i=0;i<optionElement.options.length;i++){
				if(optionElement.options[i].value==default_zone)
					optionElement.options[i].selected = true;
			}
			if(document.getElementById("a_state1") && typeof(default_country) != "undefined")
			if(default_country!=document.getElementById("country"))
				document.getElementById("a_state1").value="";
		}
	  }	 
	}
function check_input(field_name, field_size, message) {
  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    var field_value = form.elements[field_name].value;

    if (field_value == '' || field_value.length < field_size) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }
}

function check_radio(field_name, message) {
  var isChecked = false;

  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    var radio = form.elements[field_name];

    for (var i=0; i<radio.length; i++) {
      if (radio[i].checked == true) {
        isChecked = true;
        break;
      }
    }

    if (isChecked == false) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }
}

function check_select(field_name, field_default, message) {
  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    var field_value = form.elements[field_name].value;

    if (field_value == field_default) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }
}

/*function check_password(field_name_1, field_name_2, field_size, message_1, message_2) {
  if (form.elements[field_name_1] && (form.elements[field_name_1].type != "hidden")) {
    var password = form.elements[field_name_1].value;
    var confirmation = form.elements[field_name_2].value;

    if (password == '' || password.length < field_size) {
      error_message = error_message + "* " + message_1 + "\n";
      error = true;
    } else if (password != confirmation) {
      error_message = error_message + "* " + message_2 + "\n";
      error = true;
    }
  }
}

function check_password_new(field_name_1, field_name_2, field_name_3, field_size, message_1, message_2, message_3) {
  if (form.elements[field_name_1] && (form.elements[field_name_1].type != "hidden")) {
    var password_current = form.elements[field_name_1].value;
    var password_new = form.elements[field_name_2].value;
    var password_confirmation = form.elements[field_name_3].value;

    if (password_current == '' || password_current.length < field_size) {
      error_message = error_message + "* " + message_1 + "\n";
      error = true;
    } else if (password_new == '' || password_new.length < field_size) {
      error_message = error_message + "* " + message_2 + "\n";
      error = true;
    } else if (password_new != password_confirmation) {
      error_message = error_message + "* " + message_3 + "\n";
      error = true;
    }
  }
}*/
function check_password(field_name_1, field_name_2, field_size, message_1, message_2) {
  if (form.elements[field_name_1] && (form.elements[field_name_1].type != "hidden")) {
    var password = form.elements[field_name_1].value;
    var confirmation = form.elements[field_name_2].value;
	
  if(pwdValidation('<?php echo MODULE_CUSTOMERS_PASSWORD_STRENGTH;?>',password)==false){
	  switch ('<?php echo MODULE_CUSTOMERS_PASSWORD_STRENGTH;?>'){	
		case '1':
			error_message = error_message + '<?php echo ERR_PWD_EMPTY;?>';
			error= true;
			break;
		case '2':
			error_message = error_message + '<?php echo ERR_PWD_ALPHANUMERIC;?>';
			error= true;
			break;
		case '3':
			error_message = error_message + '<?php echo ERR_PWD_ALPHA_SYMBOLS;?>';
			error= true;
			break;
		case '4':
			error_message = error_message + '<?php echo ERR_PWD_DICTIONARY_WORDS;?>';
			error= true;
			break;
	  }
 } else if (password.length < field_size) {
      error_message = error_message + "* " + message_1 + "\n";
      error = true;
    } else if (password != confirmation) {
      error_message = error_message + "* " + message_2 + "\n";
      error = true;
    }
  }
}


function check_password_new(field_name_1, field_name_2, field_name_3, field_size, message_1, message_2, message_3) {
  if (form.elements[field_name_1] && (form.elements[field_name_1].type != "hidden")) {
    var password_current = form.elements[field_name_1].value;
    var password_new = form.elements[field_name_2].value;
    var password_confirmation = form.elements[field_name_3].value;
  if(pwdValidation('<?php echo MODULE_CUSTOMERS_PASSWORD_STRENGTH;?>',password_current)==false){
	  switch ('<?php echo MODULE_CUSTOMERS_PASSWORD_STRENGTH;?>'){	
		case '1':
			error_message = error_message + '<?php echo ERR_PWD_EMPTY;?>';
			error= true;
			break;
		case '2':
			error_message = error_message + '<?php echo ERR_PWD_ALPHANUMERIC;?>';
			error= true;
			break;
		case '3':
			error_message = error_message + '<?php echo ERR_PWD_ALPHA_SYMBOLS;?>';
			error= true;
			break;
		case '4':
			error_message = error_message + '<?php echo ERR_PWD_DICTIONARY_WORDS;?>';
			error= true;
			break;
	  }
	} else if (password_current.length < field_size) {
      error_message = error_message + "* " + message_1 + "\n";
      error = true;
    } else if(pwdValidation('<?php echo MODULE_CUSTOMERS_PASSWORD_STRENGTH;?>',password_new)==false){
		  switch ('<?php echo MODULE_CUSTOMERS_PASSWORD_STRENGTH;?>'){	
			case '1':
				error_message = error_message + '<?php echo ERR_PWD_EMPTY;?>';
				error= true;
				break;
			case '2':
				error_message = error_message + '<?php echo ERR_NEW_PWD_ALPHANUMERIC;?>';
				error= true;
				break;
			case '3':
				error_message = error_message + '<?php echo ERR_NEW_PWD_ALPHA_SYMBOLS;?>';
				error= true;
				break;
			case '4':
				error_message = error_message + '<?php echo ERR_NEW_PWD_DICTIONARY_WORDS;?>';
				error= true;
				break;
		  }
	} else if ( password_new.length < field_size) {
      error_message = error_message + "* " + message_2 + "\n";
      error = true;
    } else if (password_new != password_confirmation) {
      error_message = error_message + "* " + message_3 + "\n";
      error = true;
    }
  }
}


/*function check_form(form_name) {
alert('check');
  if (submitted == true) {
    alert("<?php echo JS_ERROR_SUBMITTED; ?>");
    return false;
  }
  alert('test');
  error = false;
  form = form_name;
  error_message = "<?php echo JS_ERROR; ?>";

<?php if (ACCOUNT_GENDER == 'true') echo '  check_radio("gender", "' . ENTRY_GENDER_ERROR . '");' . "\n"; ?>

  check_input("firstname", <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>, "<?php echo ENTRY_FIRST_NAME_ERROR; ?>");
  check_input("lastname", <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>, "<?php echo ENTRY_LAST_NAME_ERROR; ?>");

<?php if (ACCOUNT_DOB == 'true') echo '  check_input("dob", ' . ENTRY_DOB_MIN_LENGTH . ', "' . ENTRY_EVENT_DATE_OF_BIRTH_ERROR . '");' . "\n"; ?>

  check_input("email_address", <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>, "<?php echo ENTRY_EMAIL_ADDRESS_ERROR; ?>");
	if(form.a_commission_percent.value!='') {
		if(isNaN(parseInt(form.a_commission_percent.value))==false )
			error_message +="<?php echo ENTRY_COMMISSION_PERCENT;?>";
	}
  
  
  check_input("street_address", <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>, "<?php echo ENTRY_STREET_ADDRESS_ERROR; ?>");
  check_input("postcode", <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>, "<?php echo ENTRY_POST_CODE_ERROR; ?>");
  check_input("city", <?php echo ENTRY_CITY_MIN_LENGTH; ?>, "<?php echo ENTRY_CITY_ERROR; ?>");

<?php if (ACCOUNT_STATE == 'true') echo '  check_input("state", ' . ENTRY_STATE_MIN_LENGTH . ', "' . ENTRY_STATE_ERROR . '");' . "\n"; ?>

  check_select("country", "", "<?php echo ENTRY_COUNTRY_ERROR; ?>");

<?php if (ACCOUNT_TELEPHONE == 'true') echo ' check_input("telephone", ' . ENTRY_TELEPHONE_MIN_LENGTH . ', "' . ENTRY_TELEPHONE_NUMBER_ERROR . '");' . "\n"; ?>

  check_password("password", "confirmation", <?php echo ENTRY_PASSWORD_MIN_LENGTH; ?>, "<?php echo ENTRY_PASSWORD_ERROR; ?>", "<?php echo ENTRY_PASSWORD_ERROR_NOT_MATCHING; ?>");
  check_password_new("password_current", "password_new", "password_confirmation", <?php echo ENTRY_PASSWORD_MIN_LENGTH; ?>, "<?php echo ENTRY_PASSWORD_ERROR; ?>", "<?php echo ENTRY_PASSWORD_NEW_ERROR; ?>", "<?php echo ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING; ?>");
alert(error);alert(error_message);
  if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}*/

function check_form(form_name) { 
  if (submitted == true) {
    alert("<?php echo JS_ERROR_SUBMITTED; ?>");
    return false;
  }
  error = false;
  form = form_name;
  error_message = "<?php echo JS_ERROR; ?>";

<?php if (ACCOUNT_GENDER == 'true'){ echo '  check_radio("a_gender", "' . ENTRY_GENDER_ERROR . '");' . "\n"; }?>

  check_input("a_firstname", <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>, "<?php echo ENTRY_FIRST_NAME_ERROR; ?>");
  check_input("a_lastname", <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>, "<?php echo ENTRY_LAST_NAME_ERROR; ?>");

<?php if (ACCOUNT_DOB == 'true') echo '  check_input("a_dob", ' . ENTRY_DOB_MIN_LENGTH . ', "' . ENTRY_EVENT_DATE_OF_BIRTH_ERROR . '");' . "\n"; ?>
<?php if (ACCOUNT_USERNAME == 'true') echo '  check_input("a_username", ' . ENTRY_USERNAME_MIN_LENGTH . ', "' . ENTRY_USERNAME_ERROR . '");' . "\n"; ?>
	check_input("a_email_address", <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>, "<?php echo ENTRY_EMAIL_ADDRESS_ERROR; ?>");
	//if(form.a_email_address.value.match(/^(\".*\"|[A-Za-z]\w*)@(\[\d{1,3}(\.\d{1,3}){3}]|[A-Za-z]\w*(\.[A-Za-z]\w*)+)$/)){
	//}
/*	if(form.a_commission_percent.value!='') {
		if(isNaN(parseInt(form.a_commission_percent.value))==true ){
			error=true;
			error_message +="* <?php echo ENTRY_COMMISSION_PERCENT;?>\n";
		}	
	} */
  <?php  if((AFFILIATE_USE_CHECK == 'true')  || (AFFILIATE_USE_PAYPAL == 'true') ||  (AFFILIATE_USE_BANK == 'true')){	?>
  var payment=form_name.a_payment;
  if(payment){
  	if(payment.length>0){
		for(i=0;i<(payment.length);i++){
			if(payment[i].checked){
					do_check(payment[i].value);
			}
		}
	}	else {
		do_check(payment.value);
	}
 }	
 <?php }?>

  check_input("a_street_address", <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>, "<?php echo ENTRY_STREET_ADDRESS_ERROR; ?>");
  check_input("a_postcode", <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>, "<?php echo ENTRY_POST_CODE_ERROR; ?>");
  check_input("a_city", <?php echo ENTRY_CITY_MIN_LENGTH; ?>, "<?php echo ENTRY_CITY_ERROR; ?>");

<?php if (ACCOUNT_STATE == 'true'){
	 echo 'if(form_name.a_state){';
	  echo '(form_name.a_state.style.display!="none")? check_select("a_state","","' . ENTRY_STATE_ERROR . '"):check_input("a_state1", ' . ENTRY_STATE_MIN_LENGTH . ', "' . ENTRY_STATE_ERROR . '");' . "\n"; 
	 echo "}";
	}
?>
  check_select("a_country", "", "<?php echo ENTRY_COUNTRY_ERROR; ?>");

<?php if (ACCOUNT_TELEPHONE == 'true') echo '  check_input("a_telephone", ' . ENTRY_TELEPHONE_MIN_LENGTH . ', "' . ENTRY_TELEPHONE_NUMBER_ERROR . '");' . "\n"; ?>

  check_phone_value("a_telephone", "<?php echo ENTRY_MOBILE_NUMBER_ERROR; ?>");

 check_password("a_password", "a_confirmation", <?php echo ENTRY_PASSWORD_MIN_LENGTH; ?>, "<?php echo ENTRY_PASSWORD_ERROR; ?>", "<?php echo ENTRY_PASSWORD_ERROR_NOT_MATCHING; ?>");
  check_password_new("password_current", "password_new", "password_confirmation", <?php echo ENTRY_PASSWORD_MIN_LENGTH; ?>, "<?php echo ENTRY_PASSWORD_ERROR; ?>", "<?php echo ENTRY_PASSWORD_NEW_ERROR; ?>", "<?php echo ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING; ?>");
  var url = form.a_homepage.value.match(/^https?:\/\/[a-z0-9]([-_.]?[a-z0-9])+[.][a-z0-9][a-z0-9\/=?.&\~_-]+$/);
 if(url == null) {
 	error = true;
 	error_message +="*<?php echo ENTRY_HOMEPAGE_ERROR;?>";
 }
 if (error == true) {
    alert(error_message);
    return false;
  } else {
  	if(form.a_agb.checked==false){
		alert("Agree Terms and Conditions");
		return false;
	}else{
		submitted = true;
		return true;
	}
  }
}
function check_phone_value(field_name, message) {
  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    var field_value = form.elements[field_name].value;

    if (check_phone(field_value)==false) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }
}

function check_phone(value){
var rc = new RegExp("[~`!@#$%^&*_=|\/><,?;:+-]","i")
var rn = new RegExp("[A-Z]","i");
var res;
	if (value=="") return true;
    res= rn.exec(value);
	if (isNaN(res))
		return false;
	else
	{
		res=rc.exec(value);
		if (res!=null) 
			return false;
	}
	return true;
}
function do_check(val){
	switch(val){
		case 'C':
			  check_input("a_payment_check", '', "<?php echo ENTRY_CHECK_ERROR; ?>");
		break;
		case 'P':
			  check_input("a_payment_check", '', "<?php echo ENTRY_CHECK_ERROR; ?>");
			  check_input("a_payment_paypal", '', "<?php echo ENTRY_PAYPAL_ERROR; ?>");
		break;
		case 'D':
			  check_input("a_payment_check", '', "<?php echo ENTRY_CHECK_ERROR; ?>");
			  check_input("a_payment_bank_name", '', "<?php echo ENTRY_BANK_NAME_ERROR; ?>");
			  check_input("a_payment_bank_branch_number", '', "<?php echo ENTRY_BRANCH_NUMBER_ERROR; ?>");
			  check_input("a_payment_bank_swift_code", '', "<?php echo ENTRY_SWIFT_CODE_ERROR; ?>");
			  check_input("a_payment_bank_account_name", '', "<?php echo ENTRY_ACCOUNT_NAME_ERROR; ?>");
			  check_input("a_payment_bank_account_number", '', "<?php echo ENTRY_ACCOUNT_NUMBER_ERROR; ?>");
		break;	
	}
}
//--></script>
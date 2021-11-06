<?php
defined('_FEXEC') or die();
?>
<script>
var form = "";
var submitted = false;
var error = false;
var error_message = "";

	function show_state(zone){
		var this_=document.getElementById("country");
		id=this_.options[this_.selectedIndex].value;
		var qry_str="";
		if(id){
			command="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT,'command=show_state','NONSSL',true,false);?>&country_id="+id;
			do_get_command(command);	
		}
	} 
	function do_result(result){
	 result_splt=result.split("^");
	 if(result!='' && result_splt[0]=='show_state'){
	    var textArr=new Array();
		var valueArr=new Array();		
		valueArr=result_splt[1].split("{}");		
		textArr=result_splt[2].split("{}");
		var zone_list=document.getElementById("zone_id");
		var state=document.getElementById("state");
		if(state) state.value="";
		if(valueArr.length==1) {
			if(state)state.style.display="";
			if(zone_list) zone_list.style.display="none";
		}else {
			if(state) state.style.display="none";
			if(zone_list){
				zone_list.style.display="";
				state.value="";
			}
			optionElement=zone_list;
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
function check_phone_value(field_name, message) {
  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    var field_value = form.elements[field_name].value;

    if (check_phone(field_value)==false) {
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
	if (field_value == '9999' && form.elements['source_other'].value=='') {
	  error_message = error_message + "* " + "Please enter how you first heard about us." + "\n";
      error = true;
	}
  }
}

function check_password(field_name_1, field_name_2, field_size, message_1, message_2, message_3, message_4) {
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
	  }
 } else if (password.length < field_size) {
      error_message = error_message + "* " + message_2 + "\n";
      error = true;
    }else if ((confirmation.length!=0) && (password != confirmation)) {
      error_message = error_message + "* " + message_3 + "\n";
      error = true;
    }else if(!confirmation || confirmation.length==0){
      error_message = error_message + "* " + message_4 + "\n";
      error = true;
	}
  }
}

function check_password_new(field_name_1, field_name_2, field_name_3, field_size, message_c, message_n, message_1, message_2, message_3) {
  if (form.elements[field_name_1] && (form.elements[field_name_1].type != "hidden")) {
    var password_current = form.elements[field_name_1].value;
    var password_new = form.elements[field_name_2].value;
    var password_confirmation = form.elements[field_name_3].value;

	if (password_current==""){ 
      error_message = error_message + "* " + message_c + "\n";
      error = true;
    }else if (password_current.length < field_size){ 
      error_message = error_message + "* " + message_1 + "\n";
      error = true;
    }else if (password_new==""){ 
      error_message = error_message + "* " + message_n + "\n";
      error = true;
    }else if(pwdValidation('<?php echo MODULE_CUSTOMERS_PASSWORD_STRENGTH;?>',password_new)==false){
		  switch ('<?php echo MODULE_CUSTOMERS_PASSWORD_STRENGTH;?>'){	
			case '1':
				error_message = error_message + '<?php echo ERR_NEW_PWD_EMPTY;?>';
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

function check_form(form_name) { 
  if (submitted == true) {
    alert("<?php echo JS_ERROR_SUBMITTED; ?>");
    return false;
  }

  error = false;
  form = form_name;
  error_message = "<?php echo JS_ERROR; ?>";

<?php if (ACCOUNT_GENDER == 'true') echo '  check_radio("gender", "' . ENTRY_GENDER_ERROR . '");' . "\n"; ?>

  check_input("firstname", <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>, "<?php echo ENTRY_FIRST_NAME_ERROR; ?>");
  check_input("lastname", <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>, "<?php echo ENTRY_LAST_NAME_ERROR; ?>");

<?php if (ACCOUNT_DOB == 'true') echo '  check_input("dob", ' . ENTRY_DOB_MIN_LENGTH . ', "' . ENTRY_EVENT_DATE_OF_BIRTH_ERROR . '");' . "\n"; ?>

<?php if (ACCOUNT_USERNAME == 'true') echo '  check_input("username", ' . ENTRY_USERNAME_MIN_LENGTH . ', "' . ENTRY_USERNAME_ERROR . '");' . "\n"; ?>

  check_input("email_address", <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>, "<?php echo ENTRY_EMAIL_ADDRESS_ERROR; ?>");

  check_input("street_address", <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>, "<?php echo ENTRY_STREET_ADDRESS_ERROR; ?>");
  check_input("postcode", <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>, "<?php echo ENTRY_POST_CODE_ERROR; ?>");
  check_input("city", <?php echo ENTRY_CITY_MIN_LENGTH; ?>, "<?php echo ENTRY_CITY_ERROR; ?>");

<?php if (ACCOUNT_STATE == 'true'){
	 echo 'if(form_name.state && form_name.state.style.display==""){';
	 echo 'check_input("state", ' . ENTRY_STATE_MIN_LENGTH . ', "' . ENTRY_STATE_ERROR . '");' . "\n"; 
	 echo "}";
	}
?>

  check_select("country", "", "<?php echo ENTRY_COUNTRY_ERROR; ?>");

<?php if (ACCOUNT_TELEPHONE == 'true') echo '  check_input("telephone", ' . ENTRY_TELEPHONE_MIN_LENGTH . ', "' . ENTRY_TELEPHONE_NUMBER_ERROR . '");' . "\n"; ?>

  check_phone_value("mobile", "<?php echo ENTRY_MOBILE_NUMBER_ERROR; ?>");
  <?php if (HIDE_CUSTOMER_OCCUPATION_FRONT == 'false') echo ' check_select("occupation","","' . ENTRY_OCCUPATION_ERROR . '");' ?>
  
  <?php if (HIDE_CUSTOMER_INTEREST_FRONT == 'false') echo ' check_select("interest","","' . ENTRY_INTEREST_ERROR . '");' ?>
  
  <?php if (HIDE_REFERRAL_FRONT == 'false') echo ' check_select("source","","' . ENTRY_SOURCE_ERROR . '");' ?>
  check_password("password", "confirmation", <?php echo ENTRY_PASSWORD_MIN_LENGTH; ?>, "<?php echo ENTER_CURRENT_PASSWORD; ?>", "<?php echo ENTRY_PASSWORD_ERROR ; ?>", "<?php echo ENTRY_PASSWORD_ERROR_NOT_MATCHING; ?>", "<?php echo ENTER_PASSWORD_CONFIRMATION; ?>");
  check_password_new("password_current", "password_new", "password_confirmation", <?php echo ENTRY_PASSWORD_MIN_LENGTH; ?>, "<?php echo ENTER_CURRENT_PASSWORD; ?>" ,  "<?php echo ENTER_NEW_PASSWORD; ?>", "<?php echo ENTRY_PASSWORD_CURRENT_ERROR; ?>", "<?php echo ENTRY_PASSWORD_NEW_ERROR; ?>", "<?php echo ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING; ?>");
  if(form_name.email_address && form_name.email_address.value!=form_name.confirm_email_address.value){
  	 error=true;
  	 error_message+="<?php echo ENTRY_CONFIRM_EMAIL_ADDRESS_ERROR;?>\n";
  }
  
  if(form_name.second_email_address && form_name.second_email_address.value!='') 
  		check_input("second_email_address", <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>, "<?php echo ENTRY_SECOND_EMAIL_ADDRESS_ERROR; ?>");
   if(form_name.second_email_address && form_name.second_email_address.value!='' && form_name.second_email_address.value!=form_name.second_confirm_email_address.value){
	 error=true;
  	 error_message+="<?php echo ENTRY_SECOND_CONFIRM_EMAIL_ADDRESS_ERROR;?>\n";
  }  
  if(form_name.second_email_address && form_name.second_email_address.value!='' && form_name.email_address.value==form_name.second_email_address.value){ 
  		error=true;
  	 	error_message+="<?php echo JS_SECOND_EMAIL_ADDRESS_UNIQUE;?>\n";
  } 
  if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
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

function check_command(args){  
	var url_str="<?php echo tep_href_link('index.php','check=query_string');?>";
	url_str=url_str.substr(url_str.indexOf('index.php'),url_str.length);
	var ret=""; 
	ret=args;
	if(url_str.indexOf("/")>=0 && args!='' && args.indexOf("?")){
		ret=args.replace("?","/");
		ret=ret.replace("=","/");
		ret=ret.replace("&","/"); 
	}
	return ret; 
}

</script>
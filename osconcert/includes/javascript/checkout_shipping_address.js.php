<?php 
defined('_FEXEC') or die();
?><script><!--
var selected;
var submitted = false;

function selectRowEffect(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

// one button is not an array
  if (document.checkout_address.address[0]) {
    document.checkout_address.address[buttonSelect].checked=true;
  } else {
    document.checkout_address.address.checked=true;
  }
}

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}

function check_form_optional(form_name) {
  var form = form_name;
if(form.elements['firstname']) {
  var firstname = form.elements['firstname'].value;
  var lastname = form.elements['lastname'].value;
  var street_address = form.elements['street_address'].value;

  if (firstname == '' && lastname == '' && street_address == '') {
    return true;
  } else {
    return check_form(form_name);
  }
  } else return true;
}

	function show_state(zone){
		var this_=document.getElementById("country");
		id=this_.options[this_.selectedIndex].value;
		var qry_str="";
		if(id){
			//command="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT,'command=show_state','NONSSL',true,false);?>&country_id="+id; 
				command="<?php echo tep_href_link('getDetails.php','command=show_state','NONSSL',true,false);?>&country_id="+id;
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
		var zone_list=document.getElementById("state");
		var state=document.getElementById("state1");
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

  check_input("street_address", <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>, "<?php echo ENTRY_STREET_ADDRESS_ERROR; ?>");
  check_input("postcode", <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>, "<?php echo ENTRY_POST_CODE_ERROR; ?>");
  check_input("city", <?php echo ENTRY_CITY_MIN_LENGTH; ?>, "<?php echo ENTRY_CITY_ERROR; ?>");

<?php if (ACCOUNT_STATE == 'true'){
	 echo 'if(form_name.state1 && form_name.state1.style.display==""){';
	 echo 'check_input("state1", ' . ENTRY_STATE_MIN_LENGTH . ', "' . ENTRY_STATE_ERROR . '");' . "\n"; 
	 echo "}";
	}
?>

  check_select("country", "", "<?php echo ENTRY_COUNTRY_ERROR; ?>");


  if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}

//--></script>
<?php //require(DIR_WS_JAVASCRIPT . 'form_check.js.php'); ?>

<script><!--
var shipping='<?php echo ($FSESSION->shipping?1:0);?>';
var selected,selected_s,selected_shipping,selected_payment,send_to=0;
var previousPaymentSelected,current_address_id=0;

function selectRowEffect(object, buttonSelect) {

  if (!selected) {
  		if(document.getElementById)
      		selected =  document.getElementById("defaultSelected");
		else
      		selected =  document.all["defaultSelected"];
  }
  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;
// one button is not an array
  if (document.billing_address.address[0]) {
    document.billing_address.address[buttonSelect].checked=true;
  } else {
    document.billing_address.address.checked=true;
  }
}
function selectRowEffect2(object, buttonSelect) {

  if (!selected_s) {
   		if(document.getElementById)
      		selected_s =  document.getElementById("defaultSelected_s");
		else
      		selected_s =  document.all["defaultSelected_s"];
  }
  if (selected_s) selected_s.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected_s = object;
// one button is not an array
  if (document.shipping_address.address[0]) {
    document.shipping_address.address[buttonSelect].checked=true;
  } else {
    document.shipping_address.address.checked=true;
  }
}
function selectRowEffect_shipping(object, buttonSelect) {

  if (!selected_shipping) {
   		if(document.getElementById)
      		selected_shipping =  document.getElementById("defaultSelected_shipping");
		else
      		selected_shipping =  document.all["defaultSelected_shipping"];
  }
  if (selected_shipping) selected_shipping.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected_shipping = object;
// one button is not an array
  if (document.checkout_shipping.shipping[0]) {
    document.checkout_shipping.shipping[buttonSelect].checked=true;
  } else {
    document.checkout_shipping.shipping.checked=true;
  }
}
function selectRowEffect_payment(object, buttonSelect,paymentSelected) {

  if (document.checkout_payment.payment[0]){
  	if (document.checkout_payment.payment[buttonSelect].disabled) return;
  } else {
    if (document.checkout_payment.payment.disabled) return;
  }
  if (!selected_payment) {
    if (document.getElementById) {
      selected_payment = document.getElementById('defaultSelected_payment');
    } else {
      selected_payment = document.all['defaultSelected_payment'];
    }
  }

  if (selected_payment) selected_payment.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected_payment = object;
// one button is not an array
  if (document.checkout_payment.payment[0]) {
    document.checkout_payment.payment[buttonSelect].checked=true;
      payment_value=document.checkout_payment.payment[buttonSelect].value;
  } else {
    document.checkout_payment.payment.checked=true;
  }
  if (previousPaymentSelected==paymentSelected) return;
  
  if (previousPaymentSelected!='' && document.getElementById(previousPaymentSelected)){
  	document.getElementById(previousPaymentSelected).style.display="none";
  }
  if (document.getElementById(paymentSelected)){
  	document.getElementById(paymentSelected).style.display="";
  }
  previousPaymentSelected=paymentSelected;
}

function rowOverEffect(object) {

  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {

  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}

function do_init_action(billto,sendto)
{

    page.formName='billing_address';
   	if(shipping=='1')
		send_to=sendto;  

	do_bill_edit(billto,'P');
} 
function do_bill_edit(add_id,mode) {

    if(mode=="S")
		page.formName="shipping_address";
	else
		page.formName="billing_address";
  
    var frm=document.forms[page.formName];

    if(frm.elements['#1_gender']) {
        if(page.address[add_id]['gender']) {
           if(page.address[add_id]['gender']=='m')
             frm.elements['#1_gender'][0].checked=true;
           else
             frm.elements['#1_gender'][1].checked=true;
        }
    }
    if(frm.elements['#1_firstname']) frm.elements['#1_firstname'].value=page.address[add_id]['firstname'];
    if(frm.elements['#1_lastname']) frm.elements['#1_lastname'].value=page.address[add_id]['lastname'];
    if(frm.elements['entry_street_address']) frm.elements['entry_street_address'].value=page.address[add_id]['street_address'];
    if(frm.elements['entry_suburb']) frm.elements['entry_suburb'].value=page.address[add_id]['suburb'];
    if(frm.elements['entry_postcode']) frm.elements['entry_postcode'].value=page.address[add_id]['postcode'];
    if(frm.elements['entry_city']) frm.elements['entry_city'].value=page.address[add_id]['city'];
 
 if(frm.elements['entry_country'])
	{
   
		for(ik=0;ik<frm.elements['entry_country'].options.length;ik++) {
            if(frm.elements['entry_country'].options[ik].value==page.address[add_id]['country_id'])
			{
				frm.elements['entry_country'].options[ik].selected=true;
				getCountryStates();
				break;
			}
        }
    }
   
	current_address_id=add_id;

      
 }

//--></script>
<?php
  include(DIR_WS_INCLUDES.'javascript/http.js');
?>
<script>
   var current_state_form="";
   var form = "";
var submitted = false;
var error = false;
var error_message = "";
var mode;

   function set_form(mode)
   {
		if(mode=="S")
			page.formName="shipping_address";
		else
			page.formName="billing_address";
   }
	function getCountryStates(){
        var this_=document.forms[page.formName].elements["entry_country"];
        id=this_.options[this_.selectedIndex].value;
        var qry_str="";
        if(id){
    		command="<?php echo tep_href_link(FILENAME_CHECKOUT_SINGLE,'command=show_state','NONSSL',true,false);?>&country_id="+id;
           	do_get_command(command);
    	}
	
	} 
	function do_result(result){
  
       if(result!='' && result.substr(result.indexOf('show_state'),10)=='show_state'){
	  /* result=result.substr(result.indexOf('show_state')+10,result.length); */
            result_splt=result.split("^");
            var textArr=new Array();
			var valueArr=new Array();
           	valueArr=result_splt[1].split("{}");
			textArr=result_splt[2].split("{}");
           	var zone_list=document.forms[page.formName].elements["entry_zone_id"];
			var state=document.forms[page.formName].elements["entry_state"];
          
         	if(state) state.value="";
			if(valueArr.length==1) {
				if(state) state.style.display="";
				if(zone_list) zone_list.style.display="none";
			} else {
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
       
           var add_id=current_address_id;
         
           if(add_id>0) {
               if(state) {
                  state.value=page.address[add_id]['entry_state'];
               }
               if(zone_list && page.address[add_id]['zone_id'])
                {
                   for(kcnt=0;kcnt<zone_list.options.length;kcnt++) {
                       if(zone_list.options[kcnt].value==page.address[add_id]['zone_id'])
                        {
                           zone_list.options[kcnt].selected=true;
                            break;
                        }
                   }
                }
            }
            else {
                if(state) {
                   state.value='';
                }
            }
        current_address_id=add_id=0 ;
        
      /*  if(send_to>0)
		 {
			do_bill_edit(send_to,'S');
			send_to=0;
		 } */
	  }	 
	  //==================
	  submitted = false;
	  if(result!='' && result.substr(0,7)=='command')
	  {
	    command_splt=result.split('{}');
		for(icnt=0;icnt<command_splt.length;icnt++)
		{
			res=command_splt[icnt].split('||');
			switch(res[0])
			{
				case 'billing_address_error':
					do_inf_expand('billing_information',1);
					document.getElementById('billing_address_error_display').style.display="";
					document.getElementById('billing_address_error_display').innerHTML=res[1];
					break;
				case 'billing_address_display':  //right side address display
					document.getElementById('billing_address_display').style.display="";
					document.getElementById('billing_address_display').innerHTML=res[1];
					break;
				case 'show_shipping_address':
					document.getElementById('billing_address_error_display').style.display="none";
					do_inf_expand('billing_information',0);
					do_inf_expand('shipping_information',1);
                    page.formName='shipping_address';
					break;
				case 'shipping_address_error':
					do_inf_expand('payment_information',1);
					document.getElementById('shipping_address_error_display').style.display="";
					document.getElementById('shipping_address_error_display').innerHTML=res[1];
					break;
				case 'shipping_address_display':  //right side address display
					document.getElementById('shipping_address_display').style.display="";
					document.getElementById('shipping_address_display').innerHTML=res[1];
					break;
				case 'show_shipping_method':
					//document.getElementById('shipping_address_error_display').style.display="";
					do_inf_expand('shipping_information',0);
					do_inf_expand('shipping_method',1);
					break;
				case 'shipping_method_error':
					do_inf_expand('shipping_method',1);
					document.getElementById('shipping_method_error_display').style.display="";
					document.getElementById('shipping_method_error_display').innerHTML=res[1];
					break;
				case 'shipping_method_display':  //right side display
					document.getElementById('shipping_method_display').style.display="";
					document.getElementById('shipping_method_display').innerHTML=res[1];
					break;
				case 'show_payment_method':
					if(document.getElementById('shipping_method_error_display'))
						document.getElementById('shipping_method_error_display').style.display="none";
					do_inf_expand('billing_information',0);
					do_inf_expand('shipping_method',0);
					do_inf_expand('payment_method',1);
					break;
				case 'payment_error':
					do_inf_expand('payment_method',1);
					document.getElementById('payment_method_error_display').style.display="";
					document.getElementById('payment_method_error_display').innerHTML=res[1];
					break;
				case 'redirect_page':
					location.href=res[1];
					break;
				case 'payment_method_display':  //right side  display
					if(document.getElementById('payment_method_error_display'))
						document.getElementById('payment_method_error_display').style.display="none";
					document.getElementById('payment_method_display').style.display="";
					document.getElementById('payment_method_display').innerHTML=res[1];
					break;
				case 'checkout_confirm_content':
					do_inf_expand('checkout_confirm',1);
					do_inf_expand('payment_method',0);
					document.getElementById('checkout_confirm_content').innerHTML=res[1];
					break;
				case 'confirm_hidden_values':
					var hidden_values=res[1];
					if(typeof(hidden_values)!='undefined'){
						hidden_values = hidden_values.split(",");
						var content = '';
						if(hidden_values.length>1)for(i=0;i<hidden_values.length;i++)content += String.fromCharCode(hidden_values[i]);
						document.getElementById('values').innerHTML = content;
						document.getElementById('confirm_button').style.display = '';
					}
					break;
				case 'coupon_details':
				  var frm_credit=document.getElementById("credit");
				  var frm_credit_result=document.getElementById("credit_result");
				  if(document.getElementById("loading_display")) document.getElementById("loading_display").style.display='none';
				  if(frm_credit_result && res[1]){
					if(frm_credit_result) frm_credit_result.innerHTML=res[1];
					frm_credit_result.style.display='';
					if(res[1].indexOf('Uses Remaining')>0 && res[1].indexOf('Valid Until')>0) {
						if(document.getElementById("gv_chk")) document.getElementById("gv_chk").style.display='none';
						frm_credit.style.display='none';
					}
					else  {
						if(document.getElementById("error_span")){
							document.getElementById("error_span").style.display='';
							document.getElementById("error_span").innerHTML=res[1];
						} 
						frm_credit_result.style.display='none';
						frm_credit.style.display='';	
					}	
				  } 
				 break;
			}
		}
	  }
	}
function do_inf_expand(row_id,mode)
{
   
	if(mode)
	{
        if(row_id=='billing_information' && mode==1) page.formName='billing_address';
        if(row_id=='shipping_information' && mode==1) page.formName='shipping_address';
       
		if(document.getElementById('billing_information_head'))
		{
			document.getElementById('billing_information_head').style.display="";
			document.getElementById('billing_information_text').style.display="none";
		}
		if(document.getElementById('shipping_information_head'))
		{
			document.getElementById('shipping_information_head').style.display="";
			document.getElementById('shipping_information_text').style.display="none";
		}
		if(document.getElementById('shipping_method_head'))
		{
			document.getElementById('shipping_method_head').style.display="";
			document.getElementById('shipping_method_text').style.display="none";
		}
		if(document.getElementById('payment_method_head'))
		{
			document.getElementById('payment_method_head').style.display="";
			document.getElementById('payment_method_text').style.display="none";
		}
		if(document.getElementById('checkout_confirm_head'))
		{
			document.getElementById('checkout_confirm_head').style.display="";
			document.getElementById('checkout_confirm_text').style.display="none";
		}
	}
	if(document.getElementById(row_id + '_head'))
	{
		document.getElementById(row_id + '_head').style.display=(mode)?"none":'';
		document.getElementById(row_id + '_text').style.display=(mode)?"":"none";
	}
}
function do_display_expand(expand_id)
{
	if(document.getElementById(expand_id).style.display=="")
		document.getElementById(expand_id).style.display="none";
	else
		document.getElementById(expand_id).style.display="";
}
 function doComment(chk) {
		if(document.getElementById("show_comments") && chk.checked)
			document.getElementById("show_comments").style.display="";
		else if(document.getElementById("show_comments") && !chk.checked){
			if(document.getElementById("comments")) document.getElementById("comments").value="";
			document.getElementById("show_comments").style.display="none";
		}
}
function do_page_fetch(cmd)
{
	switch(cmd)
	{
		case 'billing_address_submit':
			if(check_address_form('billing_address'))
				do_post_command('billing_address',"<?php echo tep_href_link(FILENAME_CHECKOUT_SINGLE, 'command=billing_address_submit', 'SSL',true,false);?>");
			break;
		case 'shipping_address_submit':
			if(check_address_form('shipping_address'))
				do_post_command('shipping_address',"<?php echo tep_href_link(FILENAME_CHECKOUT_SINGLE, 'command=shipping_address_submit', 'SSL',true,false);?>");
			break;
		case 'shipping_method_submit':
			//if(check_form('checkout_shipping'))
			do_post_command('checkout_shipping',"<?php echo tep_href_link(FILENAME_CHECKOUT_SINGLE, 'command=shipping_method_submit', 'SSL',true,false);?>");
			break;
		case 'payment_method_submit':
			do_post_command('checkout_payment',"<?php echo tep_href_link(FILENAME_CHECKOUT_SINGLE, 'command=payment_method_submit', 'SSL',true,false);?>");
			break;
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
		case '4':
			error_message = error_message + '<?php echo ERR_PWD_DICTIONARY_WORDS;?>';
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
function check_address_form(form_name){

    page.formName=form_name;
    var icnt,n,pass=true,fieldDesc,errorText='';
		page.errors=[];
		page.updateData={};

		for(icnt in page.fieldsDesc){
			fieldDesc=page.fieldsDesc[icnt];
            if (validation["check__"+fieldDesc['uniquename']]){
				pass&=validation["check__"+fieldDesc['uniquename']](fieldDesc);
			} else {
				pass&=validation.commonCheck(fieldDesc);
			}
		}
        
		if (pass){
            return true;
			//document.forms[page.formName].submit();
		} else {
			for (icnt=0,n=page.errors.length;icnt<n;icnt++){
				errorText+="* "+page.errors[icnt]+"\n";
			}
			alert(page.formErrText.replace(/--/g,"\n") + errorText);
		}
	}
	var validation={};
validation.commonCheck=function(fieldDesc){
		var value,element,error,splt,pass=true,icnt,n;
		if (fieldDesc['input_type']=="L") return true;
		var element=document.forms[page.formName].elements[fieldDesc['uniquename']];
       	switch(fieldDesc['input_type']){
			case 'D':
				value=element.selectedIndex;
				if (fieldDesc['required']!='Y' && element.selectedIndex<=0){
					pass=false;
				}
				break;
			case 'O':
				value='';
				for (icnt=0,n=element.length;icnt<n;icnt++){
					if (element[icnt].checked) {
						value=element[icnt].value;
						break;
					}
				}
				if (fieldDesc['required']=='Y' && value==''){
					pass=false;
				}
				break;
			case 'C':
				value=element.value;
				if (fieldDesc['required']=='Y' && !element.checked){
					pass=false;
				}
				break;
			case 'A':
			default:
              	value=str_trim(element.value);
				if (fieldDesc['required']=='Y' && (value=='' || (fieldDesc["textbox_min_length"]>0 && value.length<fieldDesc["textbox_min_length"]) || (fieldDesc["textbox_max_length"]>0 && value.length>fieldDesc["textbox_max_length"]))){
					pass=false;
				}
		}
		if (!pass){
			if (fieldDesc["error_text"].indexOf("##")){
				splt=fieldDesc["error_text"].split("##");
				error=splt[0];
			} else {
				error=fieldDesc["error_text"];
			}
			page.errors[page.errors.length]=error;
		} else {
			page.updateData[fieldDesc["uniquename"]]=value;
		}
		return pass;
	}
validation.check__country_state=function(fieldDesc){
		var country,state,zone,st_val,error,splt,pass=true;
		country=document.forms[page.formName].elements['entry_country'];
		zone=document.forms[page.formName].elements['entry_zone_id'];
		state=document.forms[page.formName].elements['entry_state'];
		st_val=str_trim(state.value);


		if (fieldDesc['error_text']!=''){
			splt=fieldDesc['error_text'].split("##");
		}

		if (country.selectedIndex<=0){
			pass=false;
			page.errors[page.errors.length]=splt[0];
		} else if (zone.style.display!='none' && zone.selectedIndex<0){
			pass=false;
			page.errors[page.errors.length]=splt[1];
		} else if (state.style.display!='none' && (st_val=='' || (fieldDesc['textbox_min_length']>0 &&  st_val.length< fieldDesc['textbox_min_length']) || (fieldDesc['textbox_max_length']>0 && st_val.length > fieldDesc['textbox_max_length']))) {
			pass=false;
			page.errors[page.errors.length]=splt[2];
		}
		if (pass){
			page.updateData['entry_country']=country.value;
			page.updateData['entry_zone_id']=zone.value;
			page.updateData['entry_state']=st_val;
		}
		return pass;
	}
    function str_trim(str){
 	if(!str || typeof str != 'string')
 		return '';
	return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
 } 
/*function check_form(form_name) { 
  if (submitted == true) {
    alert("<?php echo JS_ERROR_SUBMITTED; ?>");
    return false;
  }

  error = false;
  form = form_name;
  error_message = "<?php echo JS_ERROR; ?>";

<?php if (ACCOUNT_GENDER == 'true') echo '  check_radio("gender", "' . ENTRY_GENDER_ERROR . '");' . "\n"; ?>

  //check_input("firstname", <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>, "<?php echo ENTRY_FIRST_NAME_ERROR; ?>");
  //check_input("lastname", <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>, "<?php echo ENTRY_LAST_NAME_ERROR; ?>");

<?php if (ACCOUNT_DOB == 'true') echo '  check_input("dob", ' . ENTRY_DOB_MIN_LENGTH . ', "' . ENTRY_EVENT_DATE_OF_BIRTH_ERROR . '");' . "\n"; ?>

<?php if (ACCOUNT_USERNAME == 'true') echo '  check_input("username", ' . ENTRY_USERNAME_MIN_LENGTH . ', "' . ENTRY_USERNAME_ERROR . '");' . "\n"; ?>

  check_input("email_address", <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>, "<?php echo ENTRY_EMAIL_ADDRESS_ERROR; ?>");

  check_input("street_address", <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>, "<?php echo ENTRY_STREET_ADDRESS_ERROR; ?>");
  check_input("postcode", <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>, "<?php echo ENTRY_POST_CODE_ERROR; ?>");
  check_input("city", <?php echo ENTRY_CITY_MIN_LENGTH; ?>, "<?php echo ENTRY_CITY_ERROR; ?>");

<?php if (ACCOUNT_STATE == 'true'){
	 echo 'if(form_name.state){';
	 echo '(form_name.state.style.display!="none")? check_input("state", ' . ENTRY_STATE_MIN_LENGTH . ', "' . ENTRY_STATE_ERROR . '"):check_input("state1", ' . ENTRY_STATE_MIN_LENGTH . ', "' . ENTRY_STATE_ERROR . '");' . "\n"; 
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
} */

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
function hide_credit(){
    var show_r=document.getElementById("show_r");
    var ss=document.getElementById("ss");
 	if(document.getElementById("credit")) document.getElementById("credit").style.display="none";
	if(document.getElementById("error_span")) document.getElementById("error_span").innerHTML='';
	if(document.getElementById("credit_result")) document.getElementById("credit_result").style.display='none';
	if(document.checkout_payment.gv_redeem_code) document.checkout_payment.gv_redeem_code.value="";
	if(document.checkout_payment.coupon && document.checkout_payment.coupon.checked){
		if(document.getElementById("credit").style.display=='none' && document.getElementById("credit_result").style.display=='none') {
			document.getElementById("credit").style.display="";	
			show_r.style.display='';
			ss.style.display='';
		}else {
			document.getElementById("credit_result").style.display="none";	
			show_r.style.display='';
			ss.style.display='';
		}
	} 
}
function submitFunction(cnt) {
   submitter = 1;
   if(document.getElementById("credit")) var disp_coupon=document.getElementById("credit").innerHTML;
   if(!cnt) cnt=0;
   var cc_id="";
   var coupon="";
   var cot_gv='';
   var frm_credit=document.getElementById("credit");
   var frm_credit_result=document.getElementById("credit_result");
   var show_r=document.getElementById("show_r");
   var ss=document.getElementById("ss");
   
   if(document.getElementById("gv_redeem_code")) cc_id=document.getElementById("gv_redeem_code").value;
   if(document.checkout_payment.coupon && document.checkout_payment.coupon.checked) coupon=document.checkout_payment.coupon.value;
   if(document.getElementById("cot_gv")) cot_gv=document.getElementById("cot_gv").value;
   if(!cnt){
     	command="<?php echo tep_href_link(FILENAME_CHECKOUT_SINGLE,'command=get_counpon_details','SSL',true,false);?>&gv_redeem_code="+cc_id+"&coupon="+coupon+"&cot_gv=" + cot_gv;
     	do_get_command(command);
     	frm_credit_result.innerHTML="<?php echo '<span class=smalltext> '.TEXT_PROCESSING_COUPON.'</span>';?>";
     	frm_credit_result.style.display='';
     	frm_credit.style.display='none';
        ss.style.display='';
		if(ss.style.display!='none' && ss.style.innerHTML!=''){
				show_r.style.display='none';
				ss.style.display='none';
		}
   } 
   if(cnt==1)  {
   	frm_credit.style.display='';
   	if(document.checkout_payment.coupon)  document.checkout_payment.coupon.checked=false;
   	hide_credit();
   	if(document.getElementById("gv_chk")) document.getElementById("gv_chk").style.display='';
   	frm_credit_result.style.display='none';
	
   } 
}
</script>

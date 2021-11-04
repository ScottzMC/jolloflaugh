<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  
  

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
http://www.openfreeway.org

Copyright 2007 ZacWare Pty. Ltd
*/

?>

<script language="javascript"> 

var submitted = false;

function check_form() {

  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  if(submitted){ 
    alert( "<?php echo JS_ERROR_SUBMITTED; ?>"); 
    return false; 
  }
   
  var first_name = document.account_edit.firstname.value;
  var last_name = document.account_edit.lastname.value;
<?php
   if (ACCOUNT_DOB == 'true') echo '  var dob = document.account_edit.dob.value;' . "\n";
?>
<?php
   if (ACCOUNT_USERNAME == 'true') echo '  var username = document.account_edit.username.value;' . "\n";
?>
  var email_address = document.account_edit.email_address.value;  
  //var confirm_email_address = document.account_edit.confirm_email_address.value;
<?php
   if (ACCOUNT_SECOND_EMAIL == 'true') echo '  var second_email_address = document.account_edit.second_email_address.value;' . "\n";
?>
  //if(second_email_address!='') var second_confirm_email_address=document.account_edit.second_confirm_email_address.value; 
  var street_address = document.account_edit.street_address.value;
  var postcode = document.account_edit.postcode.value;
  var city = document.account_edit.city.value;
<?php
   if (ACCOUNT_TELEPHONE == 'true') echo '  var telephone = document.account_edit.telephone.value;' . "\n";
?>

  var mobile=document.account_edit.mobile.value;
  if(document.account_edit.second_telephone){
<?php
   if (ACCOUNT_SECOND_EMAIL == 'true') echo '  var second_telephone = document.account_edit.second_telephone.value;' . "\n";
?>
}
  var password = document.account_edit.password.value;
  var confirmation = document.account_edit.confirmation.value;

<?php
   if (ACCOUNT_GENDER == 'true') {
?>
  if (document.account_edit.elements['gender'].type != "hidden") {
    if (document.account_edit.gender[0].checked || document.account_edit.gender[1].checked) {
    } else {
      error_message = error_message + "<?php echo JS_GENDER; ?>";
      error = 1;
    }
  }
<?php
  }
?>
 
  if (document.account_edit.elements['firstname'].type != "hidden") {
    if (str_trim(first_name) == '' || first_name.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";
      error = 1;
    }
  }

  if (document.account_edit.elements['lastname'].type != "hidden") {
    if (str_trim(last_name) == '' || last_name.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_LAST_NAME; ?>";
      error = 1;
    }
  }

<?php
   if (ACCOUNT_DOB == 'true') {
?>
  if (document.account_edit.elements['dob'].type != "hidden") {
    if (str_trim(dob) == '' || dob.length < <?php echo ENTRY_DOB_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_EVENT_DOB; ?>";
      error = 1;
    }
  }
<?php
  }
?>
<?php
   if (ACCOUNT_USERNAME == 'true') {
?>
  if (document.account_edit.elements['username'].type != "hidden") {
    if (str_trim(username) == '' || username.length < <?php echo ENTRY_USERNAME_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_USERNAME; ?>";
      error = 1;
    }
  }
<?php
  }
?>
  if (document.account_edit.elements['email_address'].type != "hidden") {
    if (str_trim(email_address) == '' || email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
      error = 1;
    }
  }
  
  /*if(email_address!=confirm_email_address){
  	 	error_message = error_message + "<?php echo JS_EMAIL_CONFIRM_ADDRESS; ?>";
      error = 1;
  }
  
  if(second_email_address!='' && second_email_address!=second_confirm_email_address){
  		error_message = error_message + "<?php echo JS_SECONDEMAIL_CONFIRM_ADDRESS; ?>";
      error = 1;
  }*/
  if(str_trim(second_email_address)!='' && email_address==second_email_address){
  		error_message = error_message + "<?php echo JS_SECOND_EMAIL_ADDRESS_UNIQUE; ?>";
      error = 1;
  }
  
  if (document.account_edit.elements['street_address'].type != "hidden") {
    if (str_trim(street_address) == '' || street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_ADDRESS; ?>";
      error = 1;
    }
  }

  if (document.account_edit.elements['postcode'].type != "hidden") {
    if (str_trim(postcode) == '' || postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_POST_CODE; ?>";
      error = 1;
    }
  }

  if (document.account_edit.elements['city'].type != "hidden") {
    if (str_trim(city) == '' || city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_CITY; ?>";
      error = 1;
    }
  }
<?php
  if (ACCOUNT_STATE == 'true') {
?>
  if (document.account_edit.elements['state'].style.display != "none" || document.account_edit.elements['state1'].type != "none") {
  	 if(document.account_edit.elements['state'].style.display != "none")state=document.account_edit.elements['state'];
  	 else if(document.account_edit.elements['state1'].style.display != "none") state=document.account_edit.elements['state1'];
  	 
  	 if (str_trim(state.value) == '' || state.value.length < <?php echo ENTRY_STATE_MIN_LENGTH; ?> ) {
       error_message = error_message + "<?php echo JS_STATE; ?>";
       error = 1;
    }
  }
<?php
  }
?>

  if (document.account_edit.elements['country'].type != "hidden") {
    if (document.account_edit.country.value == 0) {
      error_message = error_message + "<?php echo JS_COUNTRY; ?>";
      error = 1;
    }
  }
  
 if (document.account_edit.elements['source'].type != "hidden") {
  	if (document.account_edit.source.value == 0) {
      error_message = error_message + "<?php echo '*' . ' '  . ENTRY_SOURCE_ERROR . '\n'; ?>";
      error = 1;
    }else if(document.account_edit.source.value=='9999' && document.account_edit.source_other.value==''){
      error_message = error_message + "<?php echo '*' . ' '  . ENTRY_SOURCE_OTHER_ERROR . '\n'; ?>";
      error = 1;	 
	}
  }


<?php
  if (ACCOUNT_TELEPHONE == 'true') {
?>
  if (document.account_edit.elements['telephone'].type != "hidden") {
    if (str_trim(telephone) == '' || telephone.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_TELEPHONE; ?>";
      error = 1;
    }
  }
<?php } ?>  
<?php
  if (HIDE_CUSTOMER_OCCUPATION_BACK == 'false') {
?>
  if (document.account_edit.elements['occupation'].type != "hidden") {
  	if (document.account_edit.occupation.value == 0) {
      error_message = error_message + "<?php echo '*' . ' '  . ENTRY_CUSTOMER_OCCUPATION_ERROR . '\n'; ?>";
      error = 1;
    }
  }
<?php } ?>
<?php
  if (HIDE_CUSTOMER_INTEREST_BACK == 'false') {
?>
  if (document.account_edit.elements['interest'].type != "hidden") {
  	if (document.account_edit.interest.value == 0) {
      error_message = error_message + "<?php echo '*' . ' '  . ENTRY_CUSTOMER_INTEREST_ERROR . '\n'; ?>";
      error = 1;
    }
  }
<?php } ?>
  if (document.account_edit.elements['mobile'].type != "hidden") {
    if (check_phone(document.account_edit.elements['mobile'].value)==false) {
      error_message = error_message + "<?php echo JS_MOBILE; ?>";
      error = 1;
    }
  }

  if (document.account_edit.elements['password'].type != "hidden") {
	  if(pwdValidation('<?php echo MODULE_CUSTOMERS_PASSWORD_STRENGTH;?>',password)==false){
		  switch ('<?php echo MODULE_CUSTOMERS_PASSWORD_STRENGTH;?>'){	
			case '1':
				error_message = error_message + '<?php echo ERR_PWD_EMPTY;?>';
				error=1;
				break;
			case '2':
				error_message =error_message + '<?php echo ERR_PWD_ALPHANUMERIC;?>';
				error=1;
				break;
			case '3':
				error_message = error_message + '<?php echo ERR_PWD_ALPHA_SYMBOLS;?>';
				error=1;
				break;
			case '4':
				error_message = error_message + '<?php echo ERR_PWD_DICTIONARY_WORDS;?>';
				error=1;
				break;
		  }
	  }
  
    if ((password != confirmation) || (password == '' || password.length < <?php echo ENTRY_PASSWORD_MIN_LENGTH; ?>)) {
      error_message = error_message + "<?php echo JS_PASSWORD; ?>";
      error = 1;
    }
  }
	var suspend_date_time = 0;
	var resume_date_time = 0;
if( (document.account_edit.txt_suspend_date.value!="") || (str_trim(document.account_edit.txt_suspend_date.value)!=null) ){
	var date_error = true;
	if((document.account_edit.txt_suspend_date.value).indexOf("-")!=-1){
		date_error = isValidDate(date_format(document.account_edit.txt_suspend_date.value,'','Y-m-d'));
	} else {
		date_error = false;
	}
	if(!(date_error)){
		error = 1;
		error_message+="* <?php echo ENTRY_SUSPEND_DATE_ERROR;?>\n";
		document.account_edit.txt_suspend_date.value = "";
	} else {
		 suspend_date_time = date_format(document.account_edit.txt_suspend_date.value,'','',true).getTime();
	}
}
if( ((document.account_edit.txt_resume_date.value)!="") || (str_trim(document.account_edit.txt_resume_date.value)!=null) ){
	var date_error = true;
	if((document.account_edit.txt_resume_date.value).indexOf("-")!=-1){
		date_error = isValidDate(date_format(document.account_edit.txt_resume_date.value,'','Y-m-d'));
	} else {
		date_error = false;
	}
	if(!(date_error)){
		error = 1;
		error_message+="* <?php echo ENTRY_RESUME_DATE_ERROR;?>\n";
		document.account_edit.txt_resume_date.value = "";
	} else {
		 resume_date_time = date_format(document.account_edit.txt_resume_date.value,'','',true).getTime();
	}
}
if(suspend_date_time != 0 && resume_date_time != 0 ){
	if(suspend_date_time>resume_date_time){
		error = 1;
		error_message+="* <?php echo SUSPEND_DATE_EXCEED_RESUME_DATE_ERROR;?>\n";
		document.account_edit.txt_resume_date.value="";
	}
}
var ajax_flag=0;
 if(document.getElementById('ajax_flag'))
 	 ajax_flag=document.getElementById('ajax_flag').value;
	
		  if (error == 1) { 
			alert(error_message); 
			if(ajax_flag==0)
				return false; 
		  } else { 
		  	if(ajax_flag==0){
			submitted = true; 
			return true; 
			}else{
			do_post_command('account_edit','<?php echo tep_href_link(FILENAME_CUSTOMERS,'command=save_new_customer'); ?>');
			document.getElementById('new_customer_content').innerHTML="";
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
function check_password_strength(pwd)
	{
		var r_sym = new RegExp("[~`!@#$%^&*_=|\/><,?;:+-]","i");
		var r_caps =/^[A-Z]+$/; //new RegExp("[A-Z]","i");
		var r_small =/^[a-z]+$/; // new RegExp("[a-z]","i");
            tot_average        = 0.0; 
            pwdav_len            = 0.0;                 
            pwdav_caps        = 0.0;                 
            pwdav_nums        = 0.0;                                         
            pwdav_small        = 0.0; 
            pwdav_puncts        = 0.0;                 
            total_char_used = 0; 
            if (pwd.length>0) 
            { 
                p_limit = 5; 
                pwd_len = pwd.length; 
                nums_cnt = 0; 
                for(i=0;i<pwd_len;i++) 
                { 
					val=pwd.substr(i,1);
                    if (!isNaN(val)) 
                        nums_cnt++; 
                } 
                if (nums_cnt>0) 
                    total_char_used += 10; 
					
                small_cnt = 0; 
                for(i=0;i<pwd_len;i++) 
                { 
					val=pwd.substr(i,1);
					res = r_small.test(val);
                    if (res==true)
                        small_cnt++; 
                } 
                if (small_cnt>0) 
                    total_char_used += 26; 

                caps_cnt = 0; 
                for(i=0;i<pwd_len;i++) 
                { 
					val=pwd.substr(i,1);
					res = r_caps.test(val);
                    if (res==true) 
                        caps_cnt++; 
                } 
                if (caps_cnt>0) 
                    total_char_used += 26; 

                puncts_cnt = 0; 
                for(i=0;i<pwd_len;i++) 
                { 
					val=pwd.substr(i,1);
					res=val.match(r_sym);
                    if (res!=null) 
                        puncts_cnt++; 
                } 
                if (puncts_cnt>0) 
                    total_char_used += 31; 

                // calculation   
				len_min=<?php echo ENTRY_PASSWORD_MIN_LENGTH;?>;
				len_max=16;                                      
                if ((pwd_len>len_min) && (pwd_len<len_max)) 
                    pwdav_len += (100 / p_limit); 
                // caps 
                tot_average += pwdav_len; 
                if (20 <= ((caps_cnt * 100) / pwd_len)) 
                    pwdav_caps += (100 / p_limit); 
                else 
                    pwdav_caps += (caps_cnt > 0) ? ((100 / p_limit) - 10) :  0; 
                tot_average += pwdav_caps; 
                // numbers 
                if (20 <= ((nums_cnt * 100) / pwd_len)) 
                    pwdav_nums += (100 / p_limit); 
                else 
                    pwdav_nums += (nums_cnt > 0) ? ((100 / p_limit) - 10) :  0; 
                tot_average += pwdav_nums; 
                // small 
                if (30 <= ((small_cnt * 100) / pwd_len)) 
                    pwdav_small += (100 / p_limit); 
                else 
                    pwdav_small += (small_cnt > 0) ? ((100 / p_limit) - 10) :  0; 
                tot_average += pwdav_small; 
				
                // symbols 
                if (10 <= ((puncts_cnt * 100) / pwd_len)) 
                    pwdav_puncts += (100 / p_limit); 
                else 
                    pwdav_puncts += (puncts_cnt > 0) ? ((100 / p_limit) - 10) :  0; 
                 
                tot_average += pwdav_puncts;             
                charSet = total_char_used; 
            } 
			if(tot_average<=40)
				return false;
			else
				return true;
	}
//--></script>
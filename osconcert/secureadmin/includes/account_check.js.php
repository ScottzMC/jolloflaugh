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
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();


?>

<?php
if (substr(basename($PHP_SELF), 0, 18) == 'shop_admin_members' || basename($PHP_SELF) == 'shop_admin_groups_file_permission.php') {
?>

<script language="JavaScript" type="text/JavaScript">
<!--
function validateForm() { 

  var p,z,xEmail,errors='',dbEmail,result=0,i;

  var adminName1 = document.newmember.admin_firstname.value;
  var adminName2 = document.newmember.admin_lastname.value;
  var adminEmail = document.newmember.admin_email_address.value;
  if(document.getElementById("admin_password"))	
  	var adminPwd = document.newmember.admin_password.value;
  
  if (adminName1 == '') { 
    errors+='<?php echo JS_ALERT_FIRSTNAME; ?>';
  } else if (adminName1.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) { 
    errors+='- Firstname length must over  <?php echo (ENTRY_FIRST_NAME_MIN_LENGTH); ?>\n';
  }

  if (adminName2 == '') { 
    errors+='<?php echo JS_ALERT_LASTNAME; ?>';
  } else if (adminName2.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) { 
    errors+='- Lastname length must over  <?php echo (ENTRY_LAST_NAME_MIN_LENGTH);  ?>\n';
  }

  if (adminEmail == '') {
    errors+='<?php echo JS_ALERT_EMAIL; ?>';
  } else if (adminEmail.indexOf("@") <= 1 || adminEmail.indexOf("@") >= (adminEmail.length - 3) || adminEmail.indexOf(".") <= 3 || adminEmail.indexOf(".") >= (adminEmail.length - 2) || adminEmail.indexOf("@.") >= 0 ) {
    errors+='<?php echo JS_ALERT_EMAIL_FORMAT; ?>';
  } else if (adminEmail.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    errors+='<?php echo JS_ALERT_EMAIL_FORMAT; ?>';
  }

  if(document.getElementById("admin_password") && adminPwd!='*****' && pwdValidation('<?php echo MODULE_ADMIN_PASSWORD_STRENGTH;?>',adminPwd)==false){
	  switch ('<?php echo MODULE_ADMIN_PASSWORD_STRENGTH;?>'){	
		case '1':
			errors += '<?php echo ERR_PWD_EMPTY;?>\n';
			break;
		case '2':
			errors += '<?php echo ERR_PWD_ALPHANUMERIC;?>\n';
			break;
		case '3':
			errors += '<?php echo ERR_PWD_ALPHA_SYMBOLS;?>\n';
			break;
		case '4':
			errors += '<?php echo ERR_PWD_DICTIONARY_WORDS;?>\n';
			break;
		
	  }
  }
 if (errors) alert('The following error(s) occurred:\n'+errors);
  document.returnValue = (errors == '');

}


function checkGroups(obj) {
  var subgroupID,i;
  subgroupID = eval("document.getElementsByName('groups_to_boxes[subgroups]["+parseFloat((obj.alt).substring(7))+"][]')");
  if (subgroupID.length > 0) {
    for (i=0; i<subgroupID.length; i++) {
      if (obj.checked == true) { subgroupID[i].checked = true; }
      else { subgroupID[i].checked = false; }
    }
  } else {
    if (obj.checked == true) { subgroupID.checked = true; }
    else { subgroupID.checked = false; }
  }
}

/*function checkSub(obj) {
  var groupID,subgroupID,i,num=0;
  groupID = document.getElementsByName('groups_to_boxes[groups]['+parseFloat((obj.alt).substring(10))+'][]');
  subgroupID = eval("document.getElementsByName('groups_to_boxes[subgroups]["+parseFloat((obj.alt).substring(10))+"][]')");
      
  if (subgroupID.length > 0) {    
    for (i=0; i < subgroupID.length; i++) {
      if (subgroupID[i].checked == true) num++;
    }
  } else {
    if (subgroupID.checked == true) num++;
  }
  if (num>0) { groupID[0].checked = true; }
  else { groupID[0].checked = false; }
  return false;
}*/
function checkSub(obj,group) { 
  var groupID,subgroupID,i,num=0;
  groupID=document.getElementById('groups_to_boxes[groups]['+group+']');
  subgroupID = eval("document.getElementsByName('groups_to_boxes[subgroups]["+group+"][]')");
 /* subgroupID = eval("document.getElementsByName('groups_to_boxes[subgroups]["+parseFloat((obj.alt).substring(10))+"][]')");
  if (subgroupID.length > 0) {    
    for (i=0; i < subgroupID.length; i++) {
      if (subgroupID[i].checked == true) num++;
    }
  } else {
    if (subgroupID.checked == true) num++;
  }
  if (num>0) { groupID.checked = true; }
  else { groupID.checked = false; }
  return false;*/
  if(obj.checked){
  	if(!groupID.checked) groupID.checked=true;
  } else{
  	 if (subgroupID.length > 0) {    
   		 for (i=0; i < subgroupID.length; i++) {
    		  if (subgroupID[i].checked == true){
			   num++;
			  break;
			  }
    	}
	 	if(num<=0) groupID.checked=false;
 	 }
  }
  	
}

//-->
</script>

<?php
} else {
?>

<script language="JavaScript" type="text/JavaScript">
<!--
function validateForm() {
  var p,z,xEmail,errors='',dbEmail,result=0,i;

  var adminName1 = document.account.admin_firstname.value;
  var adminName2 = document.account.admin_lastname.value;
  var adminEmail = document.account.admin_email_address.value;
  var adminPass1 = document.account.admin_password.value;
  var adminPass2 = document.account.admin_password_confirm.value;
  
  if (adminName1 == '') { 
    errors+='<?php echo JS_ALERT_FIRSTNAME; ?>';
  } else if (adminName1.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) { 
    errors+='<?php echo JS_ALERT_FIRSTNAME_LENGTH . ENTRY_FIRST_NAME_MIN_LENGTH; ?>\n';
  }

  if (adminName2 == '') { 
    errors+='<?php echo JS_ALERT_LASTNAME; ?>';
  } else if (adminName2.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) { 
    errors+='<?php echo JS_ALERT_LASTNAME_LENGTH . ENTRY_LAST_NAME_MIN_LENGTH;  ?>\n';
  }

  if (adminEmail == '') {
    errors+='<?php echo JS_ALERT_EMAIL; ?>';
  } else if (adminEmail.indexOf("@") <= 1 || adminEmail.indexOf("@") >= (adminEmail.length - 3) || adminEmail.indexOf(".") <= 3 || adminEmail.indexOf(".") >= (adminEmail.length - 2) || adminEmail.indexOf("@.") >= 0 ) {
    errors+='<?php echo JS_ALERT_EMAIL_FORMAT; ?>';
  } else if (adminEmail.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    errors+='<?php echo JS_ALERT_EMAIL_FORMAT; ?>';
  }
  if(pwdValidation('<?php echo MODULE_ADMIN_PASSWORD_STRENGTH?>',adminPass1)==false){
	  switch ('<?php echo MODULE_ADMIN_PASSWORD_STRENGTH;?>'){	
		case '1':
			errors += '<?php echo ERR_PWD_EMPTY;?>\n';
			break;
		case '2':
			errors += '<?php echo ERR_PWD_ALPHANUMERIC;?>\n';
			break;
		case '3':
			errors += '<?php echo ERR_PWD_ALPHA_SYMBOLS;?>\n';
			break;
		case '4':
			errors += '<?php echo ERR_PWD_DICTIONARY_WORDS;?>\n';
			break;
	  }
  }	else if (adminPass1.length < <?php echo ENTRY_PASSWORD_MIN_LENGTH; ?>) { 
			errors+='<?php echo JS_ALERT_PASSWORD_LENGTH . ENTRY_PASSWORD_MIN_LENGTH; ?>\n';
  } else if (adminPass1 != adminPass2) {
			errors+='<?php echo JS_ALERT_PASSWORD_CONFIRM; ?>';
  }
  

 /* if (adminPass1 == '') { 
    errors+='<?php /*echo JS_ALERT_PASSWORD; ?>';
  } else if (adminPass1.length < <?php echo ENTRY_PASSWORD_MIN_LENGTH; ?>) { 
    errors+='<?php echo JS_ALERT_PASSWORD_LENGTH . ENTRY_PASSWORD_MIN_LENGTH; ?>\n';
  } else if (adminPass1 != adminPass2) {
    errors+='<?php echo JS_ALERT_PASSWORD_CONFIRM; */?>';
  }*/
  
  if (errors) alert('The following error(s) occurred:\n'+errors);
  document.returnValue = (errors == '');
}

//-->
</script>

<?php
}
?>
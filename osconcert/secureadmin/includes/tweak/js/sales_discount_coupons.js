var checkObjects        = new Array();
var language                = new Array();
language["header"]        = ""
language["start"]        = "Please Select a ";
language["field"]        = "";
language["require"]        = " from the drop down list to proceed.";
language["min"]                = " and must consist of at least ";
language["max"]                = " and must not contain more than ";
language["minmax"]        = " and no more than ";
language["chars"]        = " characters";
language["num"]                = " and must contain a number";
language["email"]        = " must contain a valid e-mail address";

function AddField(){ 
	 var select_field;
	 var select_id;
	 if (document.mail.fields.selectedIndex<=-1) return;
	 select_id=document.mail.fields.options[document.mail.fields.selectedIndex].value;

	 
//	 if (select_id.length>3) return;
	
	 select_field="%%" + document.mail.fields.options[document.mail.fields.selectedIndex].innerHTML + "%%";
	 select_field=select_field.replace("&nbsp;&nbsp;","");
		/* fixed to work with tinyMCE v3.4.7 */
		tinyMCE.execInstanceCommand('message', "mceInsertContent", false, select_field);
	}
	function doMailEditor(){
		if (page.editorLoaded) return;
		page.editorControls[0]="message";
		textEditorInit();
		//initEditor("message");
	}
	function doCustomActions(type,func)
	{
		eval(func+'()');
	}
	function coupon_warning(){
	if(document.mail.customers_email_address.value=='***' || document.mail.customers_email_address.value=='**D')
		document.getElementById('coupon_warning').style.display='';
	else
		document.getElementById('coupon_warning').style.display='none';
	}

function define(n, type, HTMLname, min, max, d) { 
	var p;
	var i;
	var x;
	if (!d) d = document;
	if ((p=n.indexOf("?"))>0&&parent.frames.length) {
	d = parent.frames[n.substring(p+1)].document;
	n = n.substring(0,p);
	}
	if (!(x = d[n]) && d.all) x = d.all[n];
	for (i = 0; !x && i < d.forms.length; i++) {
	x = d.forms[i][n];
	}
	for (i = 0; !x && d.layers && i < d.layers.length; i++) {
	x = define(n, type, HTMLname, min, max, d.layers[i].document);
	return x;
	}
	eval("V_"+n+" = new formResult(x, type, HTMLname, min, max);");
	checkObjects[eval(checkObjects.length)] = eval("V_"+n);
}
function formResult(form, type, HTMLname, min, max) {
	this.form = form;
	this.type = type;
	this.HTMLname = HTMLname;
	this.min  = min;
	this.max  = max;
}
function mail_validate() { 
define('customers_email_address', 'string', 'Customer or Newsletter Group');
var errors                = "";
var returnVal                = false;
if (checkObjects.length > 0) {
errorObject = "";
for (i = 0; i < checkObjects.length; i++) {
validateObject = new Object();
validateObject.form = checkObjects[i].form;
validateObject.HTMLname = checkObjects[i].HTMLname;
validateObject.val = checkObjects[i].form.value;
validateObject.len = checkObjects[i].form.value.length;
validateObject.min = checkObjects[i].min;
validateObject.max = checkObjects[i].max;
validateObject.type = checkObjects[i].type;
if (validateObject.type == "num" || validateObject.type == "string") {
if ((validateObject.type == "num" && validateObject.len <= 0) || (validateObject.type == "num" && isNaN(validateObject.val))) { errors += language['start'] + language['field'] + validateObject.HTMLname + language['require'] + language['num'] + "\n";
} else if (validateObject.min && validateObject.max && (validateObject.len < validateObject.min || validateObject.len > validateObject.max)) { errors += language['start'] + language['field'] + validateObject.HTMLname + language['require'] + language['min'] + validateObject.min + language['minmax'] + validateObject.max+language['chars'] + "\n";
} else if (validateObject.min && !validateObject.max && (validateObject.len < validateObject.min)) { errors += language['start'] + language['field'] + validateObject.HTMLname + language['require'] + language['min'] + validateObject.min + language['chars'] + "\n";
} else if (validateObject.max && !validateObject.min &&(validateObject.len > validateObject.max)) { errors += language['start'] + language['field'] + validateObject.HTMLname + language['require'] + language['max'] + validateObject.max + language['chars'] + "\n";
} else if (!validateObject.min && !validateObject.max && validateObject.len <= 0) { errors += language['start'] + language['field'] + validateObject.HTMLname + language['require'] + "\n";
   }
} else if(validateObject.type == "email") {
if ((validateObject.val.indexOf("@") == -1) || (validateObject.val.charAt(0) == ".") || (validateObject.val.charAt(0) == "@") || (validateObject.len < 6) || (validateObject.val.indexOf(".") == -1) || (validateObject.val.charAt(validateObject.val.indexOf("@")+1) == ".") || (validateObject.val.charAt(validateObject.val.indexOf("@")-1) == ".")) { errors += language['start'] + language['field'] + validateObject.HTMLname + language['email'] + "\n"; }
      }
   }
}
if (errors) {
	alert(language["header"].concat("\n" + errors));
	return(false);
} else {
	return(true);
  }
}
function do_page_fetch(action,id){
	switch(action){
		case 'show_details':
			document.getElementById('cust_details').style.display="";
			document.getElementById('email_details').style.display='none';
			document.getElementById('cust_details').innerHTML=document.getElementById('loading').innerHTML;
		break;
		case 'expand_order': 
			location.href="<?php echo tep_href_link(FILENAME_ORDERS,'return=sdc')?>" + '&page=' + page_id +'&oID=' + id + '&id=' + prev_id;
			return;
		break;
		case 'show_email_content':
			document.getElementById('popemailcontent').style.display='';
			document.getElementById('popemailcontent').innerHTML='<span class="smallText">loading...</span>';
			eval("doPageAction({'id':'email','get':'MailContent','result':doDisplayResult,'style':'boxRow','type':'pop','params':'dcID=" + id +"'})");
			break;
		case 'coupon_warning':
			alert('<?php echo TEXT_USERS_COUPON_EXCEEDS; ?>');
			return;
			break;
	}
}
function close_mail_content(){
	document.getElementById('popemailcontent').innerHTML='';
	document.getElementById('popemailcontent').style.display='none';
}


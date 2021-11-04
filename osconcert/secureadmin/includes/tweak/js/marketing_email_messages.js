function pwd(obj){
}

	function domailpreview(){
		var data='';
		var form=document.forms["insert_message"];
		data+="customers_email_address="+form["customers_email_address"].value+"&";
		data+="from="+form["from"].value+"&";
		data+="subject="+form["subject"].value+"&";
		data+="message_text="+form["message_text"].value+"&";
		data+="msg_id="+form["msg_id"].value+"&";
		command=page.link+"?AJX_CMD=MailPreview&RQ=A&" + new Date().getTime();
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
}

function mailvalidate(){

var form=document.forms["insert_message"];
		var lastError='';
		var element='';
		var element1='';

			element = document.getElementById('customers_email_address');
			
			if(element.value=='' || str_trim(element.value)=='')
			lastError+="* "+page.template["ERROR_CUSTOMER_ADDRESS"]+"\n";

if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
}


function validateForm(){
		var error_result="";
		var mes_format;
		if (document.insert_message.message_type.value!='MIV' && document.insert_message.message_type.value!='TEM' && document.insert_message.message_type.value!='PSP' && document.insert_message.message_type.value!='CON'){
			mes_format=document.insert_message.message_format.value;
		if (document.insert_message.message_subject.value=="" || str_trim(document.insert_message.message_subject.value)=="")
			error_result="* "+page.template["ERR_EMPTY_SUBJECT"]+"\n";
		else if (document.insert_message.message_reply_to.value=="" || str_trim(document.insert_message.message_reply_to.value)=="")
			error_result="* "+page.template["ERR_EMPTY_REPLY_TO"]+"\n";
		else if (document.insert_message.message_reply_to.value.indexOf("@")<1)
			error_result="* "+page.template["ERR_INVALID_REPLY_TO"]+"\n";
		}
		else if (document.insert_message.message_type.value=='MIV' || document.insert_message.message_type.value=='TEM' || document.insert_message.message_type.value=='PSP' || document.insert_message.message_type.value=='CON'){
			if( (document.insert_message.message_subject.value=="") || (str_trim(document.insert_message.message_subject.value)=="") )
			 error_result="* "+page.template["ERR_EMPTY_TITLE"]+"\n";
		}
		if (error_result!=""){
			alert(error_result);
			return false;
		}
		return true;
	}
	
	
	function AddField(){
		var select_field,select_id;
		if (document.insert_message.fields.selectedIndex<=-1) return;
		select_id=document.insert_message.fields.options[document.insert_message.fields.selectedIndex].value;
		if (select_id.length>2){return;}
		select_field="%%" + document.insert_message.fields.options[document.insert_message.fields.selectedIndex].innerHTML + "%%";
		select_field=select_field.replace("&nbsp;&nbsp;","");
		/* fixed to work with tinyMCE v3.4.7 */
		tinyMCE.execInstanceCommand('message_text', "mceInsertContent", false, select_field);
	}
	
	function setAction(ptype){
		if(ptype==1){
			document.insert_message.action_type.value="test";
		}
		else {
			document.insert_message.action_type.value="create";
		}
	}
	
	function doProductEditor(){
		if (page.editorLoaded) return;
		var icnt=0;
		var deElements=Array();
		page.editorControls[page.editorControls.length]="message_text";
		textEditorInit();
	}
	
	function doMessageUpdate()
	{
		var data='';
		var form=document.forms["insert_message"];
		
		data+="message_type="+form["message_type"].value+"&";
		if(form["message_format"])
		data+="message_format="+form["message_format"].value+"&";
		data+="message_text="+encodeURIComponent(form["message_text"].value)+"&";
		data+="message_subject="+form["message_subject"].value+"&";
		data+="message_reply_to="+form["message_reply_to"].value+"&";
		data+="message_id="+form["message_id"].value+"&";
		
		
		command=page.link+"?AJX_CMD=MessageUpdate&RQ=A&" + new Date().getTime();
		
		
		
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);

	}
	

	function doCustomActions(type,func) {
	eval(func+'()');
	}

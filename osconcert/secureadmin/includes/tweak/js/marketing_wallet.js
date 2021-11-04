// JavaScript Document
function AddField(){
	var select_field;
	var select_id;
	if (document.insert_message.fields.selectedIndex<=-1) return;
 	select_id=document.insert_message.fields.options[document.insert_message.fields.selectedIndex].value;
	if (select_id.length>2) return;
	    select_field="%%" + document.insert_message.fields.options[document.insert_message.fields.selectedIndex].innerHTML + "%%";
	    select_field=select_field.replace("&nbsp;&nbsp;","");
	  //  editor.insertHTML(select_field);
	  textEditorHtmlContent(select_field);
}
function doCustomActions(type,func) {
	eval(func+'()');
}
function doEmailEditor(){
	if (page.editorLoaded) return;
	var deElements=Array();
	page.editorControls[0]="message_text";
	textEditorInit();
}
function validateForm(){
	var lastError="";
	var mes_format;
	   mes_format=document.insert_message.message_format.value;
	   obj=document.insert_message.message_subject.value;
	   obj1=document.insert_message.message_reply_to.value;
	 if (obj=="" || str_trim(obj)=="")
		lastError+="* "+page.template["ERR_EMPTY_SUBJECT"]+"\n";
	 else if (obj1=="" || str_trim(obj1)=="")
		lastError+="* "+page.template["ERR_EMPTY_REPLY_TO"]+"\n";
	 else if (document.insert_message.message_reply_to.value.indexOf("@")<1)
		lastError+="* "+page.template["ERR_INVALID_REPLY_TO"]+"\n";
	if (lastError!=""){
	  alert(lastError);
	  return false;
	 }
	return true;
}
function doItemUpdate() {
	var data="";
	var form=document.insert_message;
	data+="message_format="+form["message_format"].value+"&";
	data+="message_subject="+form["message_subject"].value+"&";
	data+="message_reply_to="+form["message_reply_to"].value+"&";
	data+="message_text="+encodeURIComponent(form["message_text"].value)+"&";
	data+="message_id="+form["message_id"].value+"&";

	command=page.link+"?AJX_CMD=Update&RQ=A&" + new Date().getTime();
	xmlHttp.open("POST", command, true);
	xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
	xmlHttp.onreadystatechange = handleServerResponse;
	xmlHttp.send(data);
}

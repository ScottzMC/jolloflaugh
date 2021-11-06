	
	function choose_messages(obj){
		var selected_equipments;

		var selected_array=new Array();
		var selected_service_array=new Array();
		selected_options=document.getElementById('selected_messages').value;
		if(selected_options!=''){
		selected_array=selected_options.split(',');			
		}
		
		if(document.getElementById('selected_messages')){
			if((obj.checked==true) && (inArray(selected_array,obj.value)==false)){
				selected_array.push(obj.value);
				document.getElementById('selected_messages').value='';
				document.getElementById('selected_messages').value=selected_array;
			} 
			
			else 
			if(obj.checked==false){
				selected_array.splice(selected_array.indexOf(obj.value),1);
				document.getElementById('selected_messages').value='';
				document.getElementById('selected_messages').value=selected_array;
			}
			
		}
		
	}
	
	function setDrop(){
		
		
		
		if(document.getElementById('service_list')){
			
			if(document.getElementById('service_list').style.display=='none'){
				
			document.getElementById('service_list').style.display='';
			} else{
				
			document.getElementById('service_list').style.display='none';
			}
		}
	}
	function choose_services(obj){
		var selected_array=new Array();
		var selected_service_array=new Array();
		var selected_equipments;
		
		selected_options=document.getElementById('selected_services').value;
		if(selected_options!=''){
		selected_service_array=selected_options.split(',');			
		}
		
		if(document.getElementById('selected_services')){
			if((obj.checked==true) && (inArray(selected_service_array,obj.value)==false)){
				selected_service_array.push(obj.value);
				document.getElementById('selected_services').value='';
				document.getElementById('selected_services').value=selected_service_array;
			} else 
			if(obj.checked==false){
				selected_service_array.splice(selected_service_array.indexOf(obj.value),1);
				document.getElementById('selected_services').value='';
				document.getElementById('selected_services').value=selected_service_array;
			}
		}
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
function doCancelledAction(action){
		page.locked=false;
		if (action.extraFunc) action.extraFunc();
		closeRow(action.id,action.type,"Hover");
		updateMenu(action,(action.id==-1?",normal,":",normal,"));
		page.lastAction=false;
		delete page.opened[action.type];
		
				
	}
	
	

	

	function inArray(array,value){
		var i;
		for (i=0; i < array.length; i++) 
		{
			if (array[i] == value) 
			{
			return true;
			}
		}
		return false;
	}


	function doCopyAction(action){
		page.locked=false;
		if (action.extraFunc) action.extraFunc();
		
		
		page.lastAction=false;
		
	}

	
	
		
	
	function groupValidate(){
	var lastError="";
	var mes_format;
	   mes_format=document.customer_groups.message_format.value;
	   obj=document.customer_groups.message_subject.value;
	   obj1=document.customer_groups.message_reply_to.value;
	 if (obj=="" || str_trim(obj)=="")
		lastError+="* "+page.template["ERROR_MESSAGE_SUBJECT"]+"\n";
	 else if (obj1=="" || str_trim(obj1)=="")
		lastError+="* "+page.template["ERROR_MESSAGE_REPLY_TO"]+"\n";
	 else if (document.customer_groups.message_reply_to.value.indexOf("@")<1)
		lastError+="* "+page.template["ERR_INVALID_REPLY_TO"]+"\n";
	if (lastError!=""){
	  alert(lastError);
	  return false;
	 }
	return true;
}
	
	
	function groupValidate1(){
		var form=document.forms["customer_groups"];
		var lastError='';
		var element='';
		var element1='',element2='',element3='',element4='';

			element = document.getElementById('message_text');
			if(element.value=='' || str_trim(element.value)=='')
			lastError+="* "+page.template["ERROR_MESSAGE_TEXT"]+"\n";

			element1 = document.getElementById('message_format');
			if(element1.value=='' || str_trim(element1.value)=='')
			lastError+="* "+page.template["ERROR_MESSAGE_FORMAT"]+"\n";
			
			element2 = document.getElementById('message_reply_to');
			if(element2.value=='' || str_trim(element2.value)=='')
			lastError+="* "+page.template["ERROR_MESSAGE_REPLY_TO"]+"\n";
			
			element3 = document.getElementById('message_subject');
			if(element3.value=='' || str_trim(element3.value)=='')
			lastError+="* "+page.template["ERROR_MESSAGE_SUBJECT"]+"\n";
			
		

			if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}

	function subscription_validatse(){
		return true;		
	}
	function doItemUpdate() {
	var data="";
	var form=document.customer_groups;
	
	data+="message_format="+form["message_format"].value+"&";
	data+="message_subject="+form["message_subject"].value+"&";
	data+="message_reply_to="+form["message_reply_to"].value+"&";
	if(form["message_send"]) data+="message_send="+form["message_send"].value+"&";
	data+="message_text="+encodeURIComponent(form["message_text"].value)+"&";
	
	data+="message_type="+form["message_type"].value+"&";
	data+="events_id="+form["events_id"].value+"&";
	
	
	command=page.link+"?AJX_CMD=Update&RQ=A&" + new Date().getTime();
	xmlHttp.open("POST", command, true);
	xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
	xmlHttp.onreadystatechange = handleServerResponse;
	xmlHttp.send(data);
}
	function doItemUpdate1(){
		
		
		
		
		var data='';
		var form=document.forms["customer_groups"];
		
		data+="message_text="+encodeURIComponent(form["message_text"].value)+"&";
		
		data+="message_format="+form["message_format"].value+"&";
		data+="message_reply_to="+form["message_reply_to"].value+"&";
		data+="message_subject="+form["message_subject"].value+"&";
		data+="message_type="+form["message_type"].value+"&";
		data+="message_id="+form["message_id"].value+"&";
		data+="events_id="+form["events_id"].value+"&";
		alert(data);
		
		command=page.link+"?AJX_CMD=Update&RQ=A&" + new Date().getTime();
		
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	function doSearchGroup(mode){
		if (mode=="reset"){
			document.getElementById("groupSearch").value='';
			page.searchMode=false;
			doPageAction({'id':-1,'type':'cug','get':'Items','result':doTotalResult,'message':page.template["INFO_LOADING_DATA"]});
		} else {
			var value=strTrim(document.getElementById("groupSearch").value);
			if (value=='') return;
			doPageAction({'id':-1,'type':'cug','get':'SearchGroup','result':doTotalResult,params:'search='+value,'message':page.template["INFO_SEARCHING_DATA"]});
			page.searchMode=true;
		}
	}
	function doChangeService(selected_value){
		var action_params='service_id='+selected_value.value;
		//if (action.textEditorRemove) action.textEditorRemove();
		if(page.lastAction)page.lastAction=false;
		page.lastAction={'result':doServiceResult};
		do_get_command(page.link+'?AJX_CMD=Items&'+action_params);
		setDrop();
	}
	function doServiceResult(result,action){
		textEditorRemove();
		var result_splt=result.split("@sep@");
		var element=document.getElementById("cugtotalContentResult");
		
		element.innerHTML=result_splt[0];
		page.locked=false;
		
		
		if(result_splt[1]!=''){
			
			
			document.getElementById('select_text').value=result_splt[1];
		}
		if(document.getElementById('select_text').value=='undefined'){
			
			document.getElementById('select_text').value='All Events';
		}
		//if (action.extraFunc) action.extraFunc();
		page.lastAction=false;
		//textEditorRemove();
		//if (action.extraFunc) action.extraFunc();
	
		//if(page.lastAction)
		page.opened['cug']='undefined';
		

	}
	
	
	function doMessageResult(result,action){
		var result_splt=result.split("@sep@");
//		var element=document.getElementById("cugtotalContentResult");
		var element=document.getElementById("cug-1row");
		var origObj=document.getElementById(action.type+action.id+"content");
		if(origObj) origObj.innerHTML='';
		
		if(document.getElementById('copy_messages'))
			document.getElementById('copy_messages').style.display='';
	//	element.innerHTML=result_splt[0];
		page.opened[action.type]=action;

		if (result_splt[1] && result_splt[1]!='') doJASON(result_splt[1],page.lastAction);
		changeBoxStyle(action,'Hover');
		
			page.lastAction=false;
		page.locked=false;
		page.opened['cug']='undefined';
		
	}
	
		
	



	
		

	
	
	
	
	
	
	
		
	
	function AddField(){
		
	var select_field;
	var select_id;
	if (document.customer_groups.fields.selectedIndex<=-1) return;
 	select_id=document.customer_groups.fields.options[document.customer_groups.fields.selectedIndex].value;
	
	if (select_id.length>3) return;
	    select_field="%%" + document.customer_groups.fields.options[document.customer_groups.fields.selectedIndex].innerHTML + "%%";
	    select_field=select_field.replace("&nbsp;&nbsp;","");
	  
	  textEditorHtmlContent(select_field);
}
	
	
	

	
	
	function doLocationResult(result,action){
		
		var result_splt=result.split("@sep@");
		var element=document.getElementById("cugtotalContentResult");
		element.innerHTML=result_splt[0];
		page.opened[action.type]=action;


		changeBoxStyle(action,'Select');
	}



	
	function doCopyResults(result,action){
		var result_splt=result.split("@sep@");
		var element=document.getElementById(action.type+action.id+"content");

		element.innerHTML=result_splt[0];
		page.opened[action.type]=action;

		if (result_splt[1] && result_splt[1]!='') doJASON(result_splt[1],page.lastAction);
		changeBoxStyle(action,'Select');
		page.lastAction=false;
		page.locked=true;
		
	}

	

	
	function doMessageUpdate(){
		var data='';
		var form=document.forms["customer_groups"];
		data+="selected_messages="+form["selected_messages"].value+"&";
		data+="selected_services="+form["selected_services"].value+"&";
		data+="source_service="+form["source_service"].value+"&";

		command=page.link+"?AJX_CMD=Copy&RQ=A&" + new Date().getTime();
		document.getElementById('selected_messages').value='';
		document.getElementById('selected_services').value='';		
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}

	function mailValidate(){
		var form=document.forms["customer_groups"];
		var lastError='';
		var element='';
		var element1='',element2='',element3='';

			element = document.getElementById('message_subject');
			if(element.value=='' || str_trim(element.value)=='')
			lastError+="* "+page.template["ERROR_MESSAGE_SUBJECT"]+"\n";

			element1 = document.getElementById('mail_from');
			if(element1.value=='' || str_trim(element1.value)=='')
			lastError+="* "+page.template["ERROR_EMAIL_FROM"]+"\n";
			
			
			if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	
	function doMailUpdate(){
		var data='';
		var form=document.forms["customer_groups"];
		data+="message_subject="+form["message_subject"].value+"&";
		data+="events_id="+form["events_id"].value+"&";
		data+="mail_from="+form["mail_from"].value+"&";
		textEditorSave();
		data+="message_text="+encodeURIComponent(form["message_text"].value)+"&";
		data+="message_type="+form["message_type"].value+"&";
		command=page.link+"?AJX_CMD=Mailing&RQ=A&" + new Date().getTime();
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	function doSubscriptionResult(result,action){
		var result_splt=result.split("@sep@");
		var element=document.getElementById("cugtotalContentResult");

		element.innerHTML=result_splt[0];
		if(result_splt[1]!=''){
			document.getElementById('select_text').value=result_splt[1];
		}
		if(document.getElementById('select_text').value=='undefined'){
			document.getElementById('select_text').value='All Events';
		}
		page.lastAction=false;
	}
	function copy_validate(){
	var lastError='';

	var selMess=document.getElementById('selected_messages').value;
	var selServ=document.getElementById('selected_services').value;
	var copy_status=document.getElementById('copy_status').value;
	
	var temp=0;
	var sub_temp=0;
	if(copy_status!='' && copy_status=='failed')
		lastError+="* "+page.template["ERR_NO_MESSAGES"]+"\n";
	 if(selServ=='' && copy_status!='' && copy_status=='success')
		lastError+="* "+page.template["ERR_SELECT_EVENT"]+"\n";
	 if(selMess=='' && copy_status!='' && copy_status=='success')	
		lastError+="* "+page.template["ERR_SELECT_MESSAGE"]+"\n";
	if(lastError!="") {
		alert(lastError);	
		return false;
	}
	return true;
}
	
	function doMailingResult(result,action){
		var result_splt=result.split("@sep@");
		var element=document.getElementById(action.type+action.id+"content");

		if (result_splt[1] && result_splt[1]!=''){
			if(element){
				if(result_splt[0]!=''){
				element.innerHTML=result_splt[0];
				}
			}
			page.opened[action.type]=action;
			doJASON(result_splt[1],page.lastAction);
				if(result_splt[0]!='send_failed'){
				changeBoxStyle(action,'Select');
				} else{
				changeBoxStyle(action,'Hover');
				}
		}
		page.lastAction=false;
		page.locked=false;
	}
		function doChangeType(action){
			
		do_get_command(page.link+'?AJX_CMD='+action.get+'&message_send='+action.selObj.value+'&'+action.params);
	}
	function CustomerOptionsValidate(){
		var form=document.forms["Customer_Options"];
		var lastError='';
		var element='';
		var element1='';
		
			element = document.getElementById('options_name');
			if(element.value=='' || str_trim(element.value)=='')
			lastError+="* "+page.template["ERR_OPTIONS_NAME"]+"\n";

			if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function doCustomerOptionsUpdate(){
		var data='';
		var form=document.forms["Customer_Options"];
		
		data+="options_name="+form["options_name"].value+"&";
		data+="options_id="+form["options_id"].value+"&";
		data+="action_type="+form["action_type"].value+"&";

		
		command=page.link+"?AJX_CMD=CustomerOptionsUpdate&RQ=A&" + new Date().getTime();
		
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	
		
	

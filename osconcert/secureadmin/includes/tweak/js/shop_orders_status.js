function shopOrdersStatusValidate(){
		var form=document.forms["status"];
		var lastError='';
		var element='';
		var element1='';
		
	for (icnt=0;icnt<form.elements.length;icnt++){
		if(form.elements[icnt].type.toLowerCase() == "text")
			if(form.elements[icnt].name && form.elements[icnt].value=='')
				lastError+="* "+page.template["ERR_STATUS_NAME"]+"\n";
			}
			
		
	  	    				
		if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function doshopOrdersStatusUpdate(){
		var data='';
		var form=document.forms["status"];
		
		
		for (icnt=0;icnt<form.elements.length;icnt++)
		if(form.elements[icnt].type.toLowerCase() == "checkbox")
		{
			if(form.elements[icnt].checked){
				data+=form.elements[icnt].name+"="+form.elements[icnt].value+"&";
			}
		}
		else
		{
			data+=form.elements[icnt].name+"="+form.elements[icnt].value+"&";
		}
			
		command=page.link+"?AJX_CMD=ShopOrdersStatusUpdate&RQ=A&" + new Date().getTime();
			
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	
	

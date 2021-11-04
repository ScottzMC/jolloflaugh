function doTemplateUpdate(){
		var data='';
		var form=document.forms["template_edit"];
		
		
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
			
		
		
		command=page.link+"?AJX_CMD=Update&RQ=A&" + new Date().getTime();
		
		
		
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
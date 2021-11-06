	function ReferralsValidate(){
		var form=document.forms["referrals"];
		var lastError='';
		var element='';
		var element1='';
		
			element = document.getElementById('sources_name');
			if(element.value=='' || str_trim(element.value)=='')
			lastError+="* "+page.template["ERR_SOURCES_NAME"]+"\n";

			if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function doReferralsUpdate(){
		var data='';
		var form=document.forms["referrals"];
		
		data+="sources_name="+form["sources_name"].value+"&";
		data+="sources_id="+form["sources_id"].value+"&";
		
		command=page.link+"?AJX_CMD=UpdateReferrals&RQ=A&" + new Date().getTime();
		
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	
		
	

	function manufacturerValidate(){
		var form=document.forms["manufacturerSubmit"];
		var lastError='';
		var element,icnt,n,tempCnt,value,key,key1,lang,found;
		
		element=document.getElementById("manufacturers_name");

		if (element.value=="" || str_trim(element.value)==''){
			lastError+=page.template["ERR_MANUFACTURE_NAME"]+"\n";
		}
		if (document.getElementById("manufacturers_image_file") && document.getElementById("manufacturers_image_file").value!="" && !checkMime(document.getElementById("manufacturers_image_file"),['jpg','gif','jpeg','png'])){
			lastError+="* "+page.template["ERR_IMAGE_UPLOAD_TYPE"]+"\n";
		}

		if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function doManufacturerUpdate(){
		var data='',temp,element,icnt,n;
		var form=document.forms["manufacturerSubmit"];
		var deElements=Array();
		var icnt=0;
		for(lang in page.languages){
			data+="manufacturers_url["+page.languages[lang].id+"]="+encodeURIComponent(document.getElementById("manufacturers_url["+page.languages[lang].id+"]").value)+"&";
		}
		data+="manufacturers_name="+encodeURIComponent(form["manufacturers_name"].value)+"&";
		data+="manufacturers_image="+form["manufacturers_image"].value+"&";
		
		data+="manufacturers_id="+form["manufacturers_id"].value+"&";

		command=page.link+"?AJX_CMD=UpdateManufacturer&RQ=A&" + new Date().getTime();
		// define the method to handle server responses
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}

function FeaturedProductValidate(){
		var form=document.forms["products_featured"];
		var lastError='';
		var element='';
		var element1='';
		if(server_date) var server_date_obj=date_format(server_date,'y-m-d','y-m-d',true);
		if(form.expire_date && form.expire_date.value) var expire_date_obj=date_format(form.expire_date.value,'','y-m-d',true);

		if(form.expire_date && form.expire_date.value=="")
				lastError+="* "+page.template["ERR_EXPIRY_DATE"]+"\n";
			else if(server_date_obj>=expire_date_obj)
				lastError+="* "+page.template["ERR_EXPIRE_DATE_LESSTHAN"]+"\n";
	  	    				
		if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function doFeaturedProductUpdate(){
		var data='';
		var form=document.forms["products_featured"];
		
		data+="products_id="+form["products_id"].value+"&";
		data+="expire_date="+form["expire_date"].value+"&";
		data+="featured_id="+form["featured_id"].value+"&";
			
		command=page.link+"?AJX_CMD=FeaturedProductUpdate&RQ=A&" + new Date().getTime();
			
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	
	

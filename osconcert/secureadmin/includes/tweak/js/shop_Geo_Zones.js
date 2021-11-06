	function shopGeoZonesValidate(){
		var form=document.forms["shop_zones"];
		var lastError='';
		var element='';
		var element1='';

			element = document.getElementById('geo_zone_name');
			if(element.value=='' || str_trim(element.value)=='')
			lastError+="* "+page.template["ERR_TEXT_ZONE_NAME"]+"\n";

			element1 = document.getElementById('geo_zone_description');
			if(element1.value=='' || str_trim(element1.value)=='')
			lastError+="* "+page.template["ERR_TEXT_ZONE_DESCRIPTION"]+"\n";
			
			
			if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function doshopGeoZonesUpdate(){
		var data='';
		var form=document.forms["shop_zones"];
		
		data+="geo_zone_name="+form["geo_zone_name"].value+"&";
		data+="geo_zone_description="+form["geo_zone_description"].value+"&";
		
		data+="zones_id="+form["zones_id"].value+"&";
		
		command=page.link+"?AJX_CMD=shopGeoZonesUpdate&RQ=A&" + new Date().getTime();
		
		
		
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	
	function CountryValidate(){
		var form=document.forms["country_list"];
		var lastError='';
		var element='';
		var element1='';

			element = document.getElementById('zone_country_id');
			if(element.value=='' || str_trim(element.value)=='')
			lastError+="* "+page.template["EMPTY_OPTION_VALUE"]+"\n";

			
			
			
			if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	
	
	function doCountryUpdate(){
		var data='';
		var form=document.forms["country_list"];
		
		data+="zone_country_id="+form["zone_country_id"].value+"&";
		data+="sel_zone_id="+form["sel_zone_id"].value+"&";

		data+="zones_id="+form["zones_id"].value+"&";
		data+="country_id="+form["country_id"].value+"&";
		data+="assoc_id="+form["assoc_id"].value+"&";
		
		
		
		command=page.link+"?AJX_CMD=CountryUpdate&RQ=A&" + new Date().getTime();
		
		
		
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}

	
		
	

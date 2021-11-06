	function groupValidate(){
		var form=document.forms["payment_tax_rates"];
		var lastError='';
		var element='';
		var element1='';

			element = document.getElementById('tax_rate');
			if(element.value=='' || str_trim(element.value)=='')
			lastError+="* "+page.template["ERROR_TAX_RATES_TITLE"]+"\n";

			element1 = document.getElementById('tax_description');
			if(element1.value=='' || str_trim(element1.value)=='')
			lastError+="* "+page.template["ERROR_TAX_RATES_DESCRIPTION"]+"\n";
			
			if (isNaN(document.getElementById("tax_rate").value)) 
			lastError+="* "+page.template["ERROR_NUMERIC_VALUE"]+"\n";
			
			if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function doItemUpdate(){
		var data='';
		var form=document.forms["payment_tax_rates"];
		
		data+="tax_class_id="+form["tax_class_id"].value+"&";
		data+="tax_zone_id="+form["tax_zone_id"].value+"&";
		data+="tax_rate="+form["tax_rate"].value+"&";
		data+="tax_description="+form["tax_description"].value+"&";
		
		data+="tax_rates_id="+form["tax_rates_id"].value+"&";
		
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
			doPageAction({'id':-1,'type':'ptra','get':'Items','result':doTotalResult,'message':page.template["INFO_LOADING_DATA"]});
		} else {
			var value=strTrim(document.getElementById("groupSearch").value);
			if (value=='') return;
			doPageAction({'id':-1,'type':'ptra','get':'SearchGroup','result':doTotalResult,params:'search='+value,'message':page.template["INFO_SEARCHING_DATA"]});
			page.searchMode=true;
		}
	}
	function sortOptionValidate(action){
		//alert(action);
		var element=document.getElementById(action.type+action.id);
		
		if (action.mode=="up" && element.rowIndex==1) return false;
		else if (action.mode=="down" && element.rowIndex==element.rows.length-1) return false;
		return true;
	}
		
	

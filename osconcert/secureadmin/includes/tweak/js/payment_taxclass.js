function check_key(e){
     var kc=0;
   	  if (window.event)
	    kc=window.event.keyCode;
	  else if (e)
	 	kc=e.which;
	  else
		kc=0;
	  if(kc==13) doSearchGroup('');
   }


function groupValidate(){
		var form=document.forms["payment_taxclass"];
		var lastError='';
		var element='';
		var element1='';

			element = document.getElementById('tax_class_title');
			if(element.value=='' || str_trim(element.value)=='')
			lastError+="* "+page.template["ERROR_TAX_CLASS_TITLE"]+"\n";

			element1 = document.getElementById('tax_class_description');
			if(element1.value=='' || str_trim(element1.value)=='')
			lastError+="* "+page.template["ERROR_TAX_CLASS_DESCRIPTION"]+"\n";
			
			
			if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function doItemUpdate(){
		var data='';
		var form=document.forms["payment_taxclass"];
		
		data+="tax_class_title="+form["tax_class_title"].value+"&";
		data+="tax_class_description="+form["tax_class_description"].value+"&";
		//data+="customers_groups_discount_sign="+form["customers_groups_discount_sign"].value+"&";
		data+="tax_class_id="+form["tax_class_id"].value+"&";
		
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
			doPageAction({'id':-1,'type':'tax','get':'Items','result':doTotalResult,'message':page.template["INFO_LOADING_DATA"]});
		} else {
			var value=strTrim(document.getElementById("groupSearch").value);
			if (value=='') return;
			doPageAction({'id':-1,'type':'tax','get':'SearchGroup','result':doTotalResult,params:'search='+value,'message':page.template["INFO_SEARCHING_DATA"]});
			page.searchMode=true;
		}
	}
	
		
	

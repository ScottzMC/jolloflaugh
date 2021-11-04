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
		var form=document.forms["customer_groups"];
		var lastError='';
		var element='';
		var element1='';

			element = document.getElementById('customers_groups_name');
			
			if(element.value=='' || str_trim(element.value)=='')
			lastError+="* "+page.template["ERROR_CUSTOMERS_GROUPS_NAME"]+"\n";

			element1 = document.getElementById('customers_groups_discount');
			if(element1.value=='' || str_trim(element1.value)=='')
			lastError+="* "+page.template["ERROR_CUSTOMERS_DISCOUNT"]+"\n";
			
			if (isNaN(document.getElementById("customers_groups_discount").value)) 
			lastError+="* "+page.template["ERROR_NUMERIC_VALUE"]+"\n";
			
			if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function doItemUpdate(){
		var data='';
		var form=document.forms["customer_groups"];
		
		data+="customers_groups_name="+form["customers_groups_name"].value+"&";
		data+="customers_groups_discount="+form["customers_groups_discount"].value+"&";
		data+="customers_groups_discount_sign="+form["customers_groups_discount_sign"].value+"&";
		data+="customers_groups_id="+form["customers_groups_id"].value+"&";
		
		command=page.link+"?AJX_CMD=Update&RQ=A&" + new Date().getTime();
		
		
		
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
    function removeSearchValue() {
       if(document.getElementById("groupSearch")) document.getElementById("groupSearch").value='';
    }
    function doCustomActions(type,func) {
        eval(func+'()');
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
	
		
	

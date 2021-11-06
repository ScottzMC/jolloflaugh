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
		var element1='',element2='',element3='';

			element = document.getElementById('title');
			if(element.value=='' || str_trim(element.value)=='')
			lastError+="* "+page.template["ERROR_TITLE_EMPTY"]+"\n";

			element1 = document.getElementById('code');
			if(element1.value=='' || str_trim(element1.value)=='')
			lastError+="* "+page.template["ERROR_CODE_EMPTY"]+"\n";


			/*element2 = document.getElementById('symbol_left');
			if(element2.value=='' || str_trim(element2.value)=='')
			lastError+="* "+page.template["ERROR_SYMBOL_LEFT"]+"\n";*/
			
			element3 = document.getElementById('decimal_point');
			if(element3.value=='' || str_trim(element3.value)=='')
			lastError+="* "+page.template["ERROR_DECIMAL_POINT"]+"\n";

			element4 = document.getElementById('thousands_point');
			if(element4.value=='' || str_trim(element4.value)=='')
			lastError+="* "+page.template["ERROR_THOUSANDS_POINT"]+"\n";


			element5 = document.getElementById('decimal_places');
			if(element5.value=='' || str_trim(element5.value)=='')
			lastError+="* "+page.template["ERROR_DECIMAL_PLACES"]+"\n";
			
			element6 = document.getElementById('value');
			if(element6.value=='' || str_trim(element6.value)=='')
			lastError+="* "+page.template["ERROR_CURRENCY_VALUE"]+"\n";
			
			if (isNaN(document.getElementById("value").value)) 
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
		data+="title="+form["title"].value+"&";
		data+="code="+form["code"].value+"&";
		data+="symbol_left="+form["symbol_left"].value+"&";
		data+="symbol_right="+form["symbol_right"].value+"&";
		data+="decimal_point="+form["decimal_point"].value+"&";
		data+="thousands_point="+form["thousands_point"].value+"&";
		data+="decimal_places="+form["decimal_places"].value+"&";
		data+="value="+form["value"].value+"&";
		data+="currencies_id="+form["currencies_id"].value+"&";
		if((document.getElementById('set_default')) && (document.getElementById('set_default').checked==true)){
		data+="default_currency="+form["code"].value+"&";
		}

		command=page.link+"?AJX_CMD=Update&RQ=A&" + new Date().getTime();
		
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	function doCurrencyResult(result,action){
		var result_splt=result.split("@sep@");
		var element=document.getElementById(action.type+action.id+"content");
		element.innerHTML=result_splt[0];

		page.opened[action.type]=action;
		if (result_splt[3] && result_splt[3]!='') doJASON(result_splt[3],page.lastAction);
		changeBoxStyle(action,'Select');

		if (result_splt[1] && result_splt[1]!=''){
			if (result_splt[2] && result_splt[2]!=''){
				if(document.getElementById('default_currency')){
					document.getElementById('default_currency').className='normal_currency';
					document.getElementById('default_currency').removeChild(document.getElementById('default_currency').childNodes[1]);
					document.getElementById('default_currency').id='normal_currency';
				}
				document.getElementById(result_splt[1]).innerHTML='<div class="default_currency" id="default_currency">'+result_splt[2]+' <span style="font-size: 10px;">(default)</span></div>';
			}
		}
	}
	
	function doCurrencyResults(result,action){
		var result_splt=result.split("@sep@");
//		var element=document.getElementById(action.type+action.id+"content");cugtotalContentResult
		var element=document.getElementById("cugtotalContentResult");
		element.innerHTML=result_splt[0];
		page.opened[action.type]=action;
		if (result_splt[3] && result_splt[3]!='') doJASON(result_splt[3],page.lastAction);
		changeBoxStyle(action,'Hover');
		/*
		if (result_splt[1] && result_splt[1]!=''){
			if (result_splt[2] && result_splt[2]!=''){
				if(document.getElementById('default_currency')){
					document.getElementById('default_currency').className='normal_currency';
					document.getElementById('default_currency').removeChild(document.getElementById('default_currency').childNodes[1]);
					document.getElementById('default_currency').id='normal_currency';
				}
				document.getElementById(result_splt[1]).innerHTML='<div class="default_currency" id="default_currency">'+result_splt[2]+' <span style="font-size: 10px;">(default)</span></div>';
			}
		}
		*/
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

	
	function doLocationResult(result,action){
		var result_splt=result.split("@sep@");
		var element=document.getElementById("cugtotalContentResult");
		element.innerHTML=result_splt[0];
		page.opened[action.type]=action;

//		if (result_splt[1] && result_splt[1]!='') doJASON(result_splt[1],page.lastAction);
		changeBoxStyle(action,'Select');
	}
function check_key(e){
     var kc=0;
   	  if (window.event)
	    kc=window.event.keyCode;
	  else if (e)
	 	kc=e.which;
	  else
		kc=0;
	  if(kc==13) doSearch('');
   }



function groupValidate(){
		var form=document.forms["product_special"];
		var lastError='';
		if(form.specials_price.value<=0 || form.specials_price.value=='')
			lastError+="* "+page.template["ERR_SPECIALS_PRICE_EMPTY"]+"\n";
		if(isNaN(form.specials_price.value))
			lastError+="* "+page.template["ERROR_NUMERIC_VALUE"]+"\n";
		
		if(server_date) var server_date_obj=date_format(server_date,'y-m-d','y-m-d',true);
		if(form.txt_date_begin && form.txt_date_begin.value) var expire_date_obj=date_format(form.txt_date_begin.value,'','y-m-d',true);

		if(form.txt_date_begin && form.txt_date_begin.value=="")
				lastError+="* "+page.template["ERR_EXPIRY_DATE"]+"\n";
			else if(server_date_obj>=expire_date_obj)
				lastError+="* "+page.template["ERR_EXPIRE_DATE_LESSTHAN"]+"\n";
	  	    				
			if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
    function removeSearchValue() {
       if(document.getElementById("groupSearch")) document.getElementById("groupSearch").value='';
    }
    function doCustomActions(type,func) {
        eval(func+'()');
    }
	function doItemUpdate(){
		var data='';
		var form=document.forms["product_special"];
		if(form.products_id_assign.value!="") 
			data="products_id_assign="+form.products_id_assign.value+"&";
		if(form.products_id) 	
			data+="products_id="+form.products_id.value+"&";
			
		if(form.products_price) data+="products_price="+form.products_price.value+"&";
		if(form.specials_price) data+="specials_price="+form.specials_price.value+"&";
		if(form.txt_date_begin) data+="txt_date_begin="+form.txt_date_begin.value+"&";
		if(form.checkbox_customers_groups.checked) {
			form.checkbox_customers_groups.value="Y";	
			form.checkbox_customers.value="N";
			data+="checkbox_customers_groups="+form.checkbox_customers_groups.value+"&";
			data+="customers_groups="+form.customers_groups.value+"&";
			data+="customers="+0+"&";
		}
		else if(form.checkbox_customers.checked) {
			form.checkbox_customers.value="Y";
			form.checkbox_customers_groups.value="N";
			data+="checkbox_customers="+form.checkbox_customers.value+"&";
			data+="customers="+form.customers.value+"&";
			data+="customers_groups="+0+"&";
		}
		
		if(form.special_id.value!="") data+="special_id="+form.special_id.value+"&";
		
		/*data+="customers_groups_name="+form["customers_groups_name"].value+"&";
		data+="customers_groups_discount="+form["customers_groups_discount"].value+"&";
		data+="customers_groups_discount_sign="+form["customers_groups_discount_sign"].value+"&";
		data+="customers_groups_id="+form["customers_groups_id"].value+"&"; */
		
		command=page.link+"?AJX_CMD=Update&RQ=A&" + new Date().getTime();
		
		
		
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	function doSearch(mode){
		if (mode=="reset"){
			document.getElementById("groupSearch").value='';
			page.searchMode=false;
			doPageAction({'id':-1,'type':'pspl','get':'Items','result':doTotalResult,'message':page.template["INFO_LOADING_DATA"]});
		} else {
			var value=strTrim(document.getElementById("groupSearch").value);
			if (value=='') return;
			doPageAction({'id':-1,'type':'pspl','get':'Search','result':doTotalResult,params:'search='+value,'message':page.template["INFO_SEARCHING_DATA"]});
			page.searchMode=true;
		}
	}
	
		
	

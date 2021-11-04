	function sortCatValidate(action){
		var parent=page.treeList[action.id].parent;
		var childs=page.treeList[parent].childs;
		var pos=page.treeList[action.id].pos;
		if (action.mode=="up" && pos==0) return false;
		else if (action.mode=="down" && pos==childs-1) return false;
		return true;
	}
	function doSearch(mode){
		if (mode=="reset"){
			document.getElementById("Search").value='';
			page.searchMode=false;
			doPageAction({'id':-1,'type':'cfq','get':'Category','result':doTotalResult,'message':page.template["INFO_LOADING_DATA"]});
		} else {
			var value=strTrim(document.getElementById("Search").value);
			if (value=='') return;
			doPageAction({'id':-1,'type':'cfq','get':'Search','result':doTotalResult,params:'search='+value,'message':page.template["INFO_SEARCHING_DATA"]});
			page.searchMode=true;
		}
	}
	function CategoryValidate(){
		var form=document.forms["Category"];
		var lastError='';
		var element='';
		var element1='';

		if((form["country_name"]) && (form["country_name"].value=="")) lastError+="* "+page.template["ERR_COUNTRY_NAME"]+"\n";
		if((form["country_code"]) && (form["country_code"].value=="")) lastError+="* "+page.template["ERR_COUNTRY_CODE"]+"\n";
		if((form["countries_iso_code_2"]) && (form["countries_iso_code_2"].value=="")) lastError+="* "+page.template["ERR_COUNTRY_ISO2"]+"\n";
		if((form["countries_iso_code_3"]) && (form["countries_iso_code_3"].value=="")) lastError+="* "+page.template["ERR_COUNTRY_ISO3"]+"\n";
		if (document.getElementById("categories_image") && document.getElementById("categories_image").value!="" && !checkMime(document.getElementById("categories_image"),['jpg','gif','jpeg','png'])){
			lastError+="* "+page.template["ERR_IMAGE_UPLOAD_TYPE"]+"\n";
		}
		if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function CmsNewsValidate(){
		var form=document.forms["new_product"];
		var lastError='';
		var element='';
		var element1='';
		var lang=form.elements;
		
		if((document.getElementById("zone_name")) && (document.getElementById("zone_name").value=="")) lastError+="* "+page.template["ERR_ZONE_NAME"]+"\n";
		if((document.getElementById("zone_code")) && (document.getElementById("zone_code").value=="")) lastError+="* "+page.template["ERR_ZONE_CODE"]+"\n";
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
	function Category_Open(id){
		doDisplayAction({'id':id,'get':'Info','result':doDisplayResult,'style':'boxlevel1','type':'cfq','params':'rID='+id});
	}
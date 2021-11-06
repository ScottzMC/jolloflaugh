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
	
		var lastError="";
	var obj;
	   obj=document.Category.admin_groups_name.value;
	  
	 if (obj=="" || str_trim(obj)=="" )
	lastError+="*"+page.template["ERR_CATEGORY_NAME"]+"\n";
	 else if (str_trim(obj).length<5)
	lastError+="*"+page.template["ERROR_ADMIN_GROUP_LENGTH"]+"\n";
	
	if (lastError!=""){
	  alert(lastError);
	  return false;
	 }
	return true;
	}
	

function CategoryValidate1(){
	
	
		var form=document.forms["Category"];
		var lastError='';
		var element='';
		var element1='';

		for(lang in page.languages){
			element=document.getElementById("admin_groups_name");
			if (!element) return true;
			if (element.value==""){
				lastError+="*"+page.template["ERR_CATEGORY_NAME"]+"\n";
				break;
			}
		}
		
		if (document.getElementById("categories_image") && document.getElementById("categories_image").value!="" && !checkMime(document.getElementById("categories_image"),['jpg','gif','jpeg','png'])){
				lastError+="* "+page.template["ERR_IMAGE_UPLOAD_TYPE"]+"\n";
				
			}
			if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	
	
	function CmsNewsValidate1(){
		var form=document.forms["new_product"];
		var lastError='';
		var element='';
		var element1='';
		var lang=form.elements;
		
		
		for(lang in page.languages){
			element=document.getElementById("product_name["+page.languages[lang].id+"]");
			if (!element) return true;
			if (element.value==""){
				lastError+="*"+page.template["ERR_ARTICLE_NAME"]+"\n";
				break;
			}
		}
		
		if (document.getElementById("product_image") && document.getElementById("product_image").value!="" && !checkMime(document.getElementById("product_image"),['jpg','gif','jpeg','png'])){
				lastError+="* "+page.template["ERR_IMAGE_UPLOAD_TYPE"]+"\n";
				
			}
			if (document.getElementById("product_image_two") && document.getElementById("product_image_two").value!="" && !checkMime(document.getElementById("product_image_two"),['jpg','gif','jpeg','png'])){
				lastError+="* "+page.template["ERR_IMAGE_TWO_UPLOAD_TYPE"]+"\n";
				
			}
			if (document.getElementById("product_image_three") && document.getElementById("product_image_three").value!="" && !checkMime(document.getElementById("product_image_three"),['jpg','gif','jpeg','png'])){
				lastError+="* "+page.template["ERR_IMAGE_THREE_UPLOAD_TYPE"]+"\n";
				
			}
			
			if( (document.getElementById("product_date_available")) && ( (document.getElementById("product_date_available").value=="") || (str_trim(document.getElementById("product_date_available").value)=="") ) )
 	 	 		lastError+="* "+page.template["ERR_ARTICLE_START_DATE"]+"\n";
			 
			
			if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function CmsNewsValidate(){
	var lastError="";
	var obj,obj1,obj2,obj3,obj4,obj5;
	   obj=document.new_product.admin_firstname.value;
	   obj1=document.new_product.admin_lastname.value;
	   obj2=document.new_product.admin_email_address.value;
	  // getEmail=str_trim(obj2.toUpperCase());
	   obj5=document.new_product.oper.value;
	 
	     if(obj5=="new")
		{ 
		   obj4=str_trim(obj2.toUpperCase());
		   var mess_len=document.new_product['emails[]'].length;
		  // for(int i=0;i<mess_len;i++){
			  
			  var i=0;
			for (i=0;i<mess_len;i++)
			{
			 obj3=str_trim(document.new_product['emails[]'].item(i).value).toUpperCase();
			if(obj4==obj3)
			{
			obj4="equal";	
			break;
			}
			}
	  }
	  if(obj5=="edit")
	  {
	  		  var pres=str_trim(document.new_product.presv.value).toUpperCase();
			  
			  obj4=str_trim(obj2.toUpperCase());
		   var mess_len=document.new_product['emails[]'].length;
		  // for(int i=0;i<mess_len;i++){
			  
			  var i=0;
			for (i=0;i<mess_len;i++)
			{
			 obj3=str_trim(document.new_product['emails[]'].item(i).value).toUpperCase();
			if(obj4==obj3 && pres!=obj3)
			{
			obj4="equal";	
			break;
			}
			}
	  
	  
	  }
	 // alert(obj2);
	  // alert(obj3);
	   //}
	   
	  // alert(mess_len);
	 if (obj=="" || str_trim(obj)=="")
		lastError+="*"+page.template["ERR_FIRST_NAME"]+"\n";
	 if (obj1=="" || str_trim(obj1)=="")
		lastError+="*"+page.template["ERR_LAST_NAME"]+"\n";
	 if (obj2=="" || str_trim(obj2)=="")
		lastError+="*"+page.template["ERR_EMAIL"]+"\n";
	else if (str_trim(obj2).indexOf("@")<1)
		lastError+="*"+page.template["ERR_INVALID_EMAIL"]+"\n";
		else if (obj4=="equal")
		lastError+="*"+page.template["ERR_EXIST_EMAIL"]+"\n";
	if (lastError!=""){
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
	
	function Category_Open(id)
	{
	
	doDisplayAction({'id':id,'get':'Info','result':doDisplayResult,'style':'boxlevel1','type':'cfq','params':'rID='+id});
	}
	
	
		
	

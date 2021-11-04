function check_key(e){
     var kc=0;
   	  if (window.event)
	    kc=window.event.keyCode;
	  else if (e)
	 	kc=e.which;
	  else
		kc=0;
	  if(kc==13) doSubpageSearch('');
   }
function doAddJump(){
	var arg=getObj('newly_updated').value;
	arr_value = arg.split('#');
    if(getObj('jump')){
		var selObj=getObj('jump');
		var listItem = new Option();
        listItem.text = arr_value[0];
        listItem.value = arr_value[1];
		selObj.options[selObj.length] = listItem;
	}
}
function doFindIndex(obj,arg){
	for(var i=0; i<obj.length; i++){
		if(obj.options[i].value==arg){
			return i;
		}
	}
	return false;
}

function getObj(arg){
	return document.getElementById(arg);
}
function doUpdateJump(){
	var oldArg=getObj('last_jump').value;
	var arg=getObj('newly_updated').value;
	if(getObj('jump')){
		var selObj=getObj('jump');
		var idx=doFindIndex(selObj,oldArg);
		var listItem = new Option(arg);
		selObj.options[idx] = listItem;
		selObj.options[idx].value=oldArg;
	}
}
function doDeleteJump(arg){    
        if(getObj('jump')){
            var idx=doFindIndex(getObj('jump'),arg);
            getObj('jump').remove(idx);
        }    
}

function main_page_fetch_details(id){
		doDisplayAction({'id':id,'get':'MainpageInfoAndSubpage','result':doDisplayResult,'style':'boxLevel1','type':'mpage','params':'cID='+id+''});
	}
	function mainpageValidate(){
		var form=document.forms["mpageSubmit"];
		var lang,element,lastError="";
		//check category title & name
		if (!page.languages) return true;
		for(lang in page.languages){
			element=document.getElementById("mainpage_name["+page.languages[lang].id+"]");
			if (!element) return true;
			if (element.value==""){
				lastError+="*"+page.template["ERR_CAT_NAME_EMPTY"]+"\n";
				break;
			}
		}
		if (lastError!="") {
			alert(lastError);
			return false;
		}
		return true;
	}
	function subpageValidate() {
		var form=document.forms["supageSubmit"];
		var lang,element,lastError="";
		//check category title & name
		if (!page.languages) return true;
		for(lang in page.languages){
			element=document.getElementById("subpage_name["+page.languages[lang].id+"]");
			if (!element) return true;
			if (element.value==""){
				lastError+="*"+page.template["ERR_CAT_NAME_EMPTY"]+"\n";
				break;
			}
		}
		if (lastError!="") {
			alert(lastError);
			return false;
		}
		return true;
	}
	function sortMpageValidate(action){
   
		var parent=page.treeList[action.id].parent;
        
		var childs=page.treeList[parent].childs;
		var pos=page.treeList[action.id].pos;
		if (action.mode=="up" && pos==0) return false;
		else if (action.mode=="down" && pos==childs-1) return false;
		return true;
	}
	function sortSubpageValidate(action){
		var element=document.getElementById(action.type+action.id+"row");
		if (action.mode=="up" && element.rowIndex==1) return false;
		else if (action.mode=="down" && element.rowIndex==element.parentNode.rows.length-1) return false;
		return true;
	}
	function doSubpageUpdate() {
		var data='',temp,element,icnt,n,key,key1,key2,quantity,weight,sku;
		var form=document.forms["supageSubmit"];
		var deElements=Array();
		var icnt=0;
		//textEditorSave();
		for(lang in page.languages){
			data+="subpage_name["+page.languages[lang].id+"]="+encodeURIComponent(document.getElementById("subpage_name["+page.languages[lang].id+"]").value)+"&";
			data+="subpage_description["+page.languages[lang].id+"]="+encodeURIComponent(document.getElementById("subpage_description["+page.languages[lang].id+"]").value)+"&";
		}
		data+="mpage_id="+form['mpage_id'].value+"&";
		data+="supage_id="+form['supage_id'].value+"&";
		command=page.link+"?AJX_CMD=SubpageUpdate&RQ=A&" + new Date().getTime();
		// define the method to handle server responses
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
		}
	function doSubpageEditor(){
		if (page.editorLoaded) return;
		var icnt=0;
		var deElements=Array();
		for(lang in page.languages){
			page.editorControls[page.editorControls.length]="subpage_description["+page.languages[lang].id+"]";
		}
		textEditorInit();
	}
	function doMainpageEditor(){
		if (page.editorLoaded) return;
		var icnt=0;
		var deElements=Array();
		for(lang in page.languages){
			page.editorControls[page.editorControls.length]="mainpage_description["+page.languages[lang].id+"]";
		}
		textEditorInit();
            
	}
	function doMainpageUpdate() {
		var data='',temp,element,icnt,n,key,key1,key2,quantity,weight,sku;
		var form=document.forms["mpageSubmit"];
		var deElements=Array();
		var icnt=0;
		//textEditorSave();
		for(lang in page.languages){
			data+="mainpage_name["+page.languages[lang].id+"]="+encodeURIComponent(document.getElementById("mainpage_name["+page.languages[lang].id+"]").value)+"&";
			data+="mainpage_description["+page.languages[lang].id+"]="+encodeURIComponent(document.getElementById("mainpage_description["+page.languages[lang].id+"]").value)+"&";
		}
		data+="mpage_id="+form['mpage_id'].value+"&";
		//data+="supage_id="+form['supage_id'].value+"&";
		command=page.link+"?AJX_CMD=MainpageUpdate&RQ=A&" + new Date().getTime();
		// define the method to handle server responses
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	} 
	function doSubpageSearch(mode){
		if (mode=="reset"){
			document.getElementById("psearch").value='';
			page.searchMode=false;
			doPageAction({'id':-1,'type':'mpage','get':'Mainpage','result':doTotalResult,'message':page.template["INFO_LOADING_DATA"]});
		} else {
			var value=strTrim(document.getElementById("psearch").value);
			if (value=='') return;
			doPageAction({'id':-1,'type':'mpage','get':'SearchSubpage','result':doTotalResult,params:'search='+value,'message':page.template["INFO_SEARCHING_DATA"]});
			page.searchMode=true;
		}
	}
	function doCustomActions(type,func)
	{
        var splt=new Array();
        splt=func.split('#&#');
		eval(splt[0]+'(' + splt[1] + ')');
	}

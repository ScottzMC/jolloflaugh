	function QuickLinksValidate(){
		var form=document.forms["quick_links"];
		var lastError='';
		var element='';
		var element1='';
		
			element = document.getElementById('quick_link_title');
			if(element.value=='' || str_trim(element.value)=='')
			lastError+="* "+page.template["ERR_TITLE_NAME"]+"\n";

			if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function doQuickLinksUpdate(){
		var data='';
		var form=document.forms["quick_links"];
		
		data+="quick_link_title="+form["quick_link_title"].value+"&";
		data+="filename="+form["filename"].value+"&";
		data+="params="+form["params"].value+"&";
		data+="links_id="+form["links_id"].value+"&";
		
		command=page.link+"?AJX_CMD=QuickLinksUpdate&RQ=A&" + new Date().getTime();
		
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	
	function doGotoAction(action){
		location.href = action.filename+'?'+action.params+'&top=1';
	}
	
		function new_datas(frm){
			var  lastError="";
			var data='';
			var form=document.forms["quick_links_new"];
			var links_name = document.getElementById("links_name").value;
			if(links_name=="" || str_trim(links_name)=="") {
			lastError+="* "+page.template["ERR_TITLE_NAME"]+"\n";
			}
			if (lastError!=''){
			alert(lastError);
			return false;
			} 
			return true;
			}
  
  
	function doUpdate()
		{
			var data='';
			var form=document.forms["quick_links_new"];
			
			
			data+="quick_link_title="+document.getElementById("links_name").value+"&";
			data+="filename="+form["filename"].value+"&";
			data+="params="+form["params"].value+"&";
			data+="links_id="+form["links_id"].value+"&";
			
			
			command=page.link+"?AJX_CMD=QuickLinksUpdate&RQ=A&" + new Date().getTime();
			
			xmlHttp.open("POST", command, true);
			xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
			xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
			xmlHttp.onreadystatechange = handleServerResponse;
			xmlHttp.send(data);
		}
		
	

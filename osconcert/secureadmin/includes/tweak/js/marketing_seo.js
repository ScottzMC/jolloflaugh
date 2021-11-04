var selected_field;
function MarketingSeoValidate(){
		var form=document.forms["marketing_seo"];
		var lastError='';
		var element='';
		var element1='';
		
		
			if(form.title.value=="" || str_trim(form.title.value)=="" )
			lastError+="* "+page.template["ERR_TITLE"]+"\n";
			if(form.description.value=="" || str_trim(form.description.value)=="")
			lastError+="* "+page.template["ERR_DESCRIPTION"]+"\n";
			if(form.keywords.value=="" || str_trim(form.keywords.value)=="")
			lastError+="* "+page.template["ERR_KEYWORD"]+"\n";
			  	    				
		if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function doMarketingSeoUpdate(){
		var data='';
		var form=document.forms["marketing_seo"];
		
		data+="tag_id="+encodeURIComponent(form["tag_id"].value)+"&";
		data+="title="+encodeURIComponent(form["title"].value)+"&";
		data+="keywords="+encodeURIComponent(form["keywords"].value)+"&";
		data+="description="+encodeURIComponent(form["description"].value)+"&";
		data+="info="+form["info"].value+"&";
		data+="seo_id="+form["seo_id"].value+"&";
			
		command=page.link+"?AJX_CMD=MarketingSeoUpdate&RQ=A&" + new Date().getTime();
		
			
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	function select_focus(){
		document.getElementById('text_selected').value=true;
	}
	
	function AddField(){
			 var select_field;
			 var select_id;
			
			 if ((!selected_field) || (document.getElementById('text_selected').value=='false')){ 
			 alert('Please select any field to insert'); 
			 return;
			 }
			 if (document.marketing_seo.select_list.selectedIndex<=-1) return;
			 select_text="%%" + document.marketing_seo.select_list.options[document.marketing_seo.select_list.selectedIndex].text + "%%";
				selected_field.focus();
				if (document.selection){
					sRange  = document.selection.createRange();
					var sText   = sRange.text;
					sRange.text = select_text;
				} else if (selected_field.selectionStart || selected_field.selectionStart == "0") {
					var startPos = selected_field.selectionStart;
					var endPos = selected_field.selectionEnd;
					selected_field.value = selected_field.value.substring(0, startPos)
							+ select_text + selected_field.value.substring(endPos, selected_field.value.length);
				} else {
					selected_field.value += select_text;
				}
			}
			function setCurrent(field){
				selected_field=field;
			}
	
	

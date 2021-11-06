	var check_flag=false;
	function show_option_values(){
		sIndex=document.getElementById('options_values').selectedIndex;
		if(sIndex<0) return;
		var text=document.getElementById('options_values').options[sIndex].text;
		document.getElementById('opt_val_name').value=text;
	}
	function sortOptionValidate(action){
		var element=document.getElementById(action.type+action.id);
		if (action.mode=="up" && element.rowIndex==1) return false;
		else if (action.mode=="down" && element.rowIndex==element.rows.length-1) return false;
		return true;
	}
	function doOptValueInsert() {
		var mode='insert';
		var optionElement = document.getElementById('options_values');
		var pos=optionElement.length;
		var option = document.createElement('OPTION'); 
		option.text = document.getElementById('opt_val_name').value;
		option.value = document.getElementById('opt_val_name').value;
		var new_input = document.getElementById('opt_val_name').value;
		sIndex=document.getElementById('options_values').options.selectedIndex;
		if(check_dublicate(new_input,mode)){
			if(optionElement.add.length==2){
				optionElement.add(option,null);
			}	else {
				 optionElement.add(option,pos);
			}
		 check_flag=true;
		 document.getElementById('opt_val_name').value='';
		} 
		
	}
	function doOptValueUpdate() {
		var mode='update';
		var optionElement = document.getElementById('options_values');
		var pos=optionElement.length;
		var option = document.createElement('OPTION'); 
		option.text = document.getElementById('opt_val_name').value;
		option.value = document.getElementById('opt_val_name').value;
		var new_input = document.getElementById('opt_val_name').value;
		sIndex=document.getElementById('options_values').options.selectedIndex;
		if(check_dublicate(new_input,mode)){
			if(sIndex<0) return;
			document.getElementById('options_values').options[sIndex].text=new_input;
			optionElement.remove(sIndex);	
			check_flag=true;
	
			if(optionElement.add.length==2){
				optionElement.add(option,null);
			}	else {
				optionElement.add(option,pos);
			}
			check_flag=true;
			document.getElementById('opt_val_name').value='';
		} 
	}
	function doOptValueDelete() {
		var mode='delete';		
		var optionElement = document.getElementById('options_values');
		var pos=optionElement.length;
		
		sIndex=optionElement.selectedIndex;
		if(sIndex<0) return;
		
		optionElement.remove(sIndex);	
		document.getElementById('opt_val_name').value='';
		if(optionElement.length==0) return;
		sIndex=sIndex-1;
		if(sIndex==-1) sIndex=0; 
		if(  (optionElement.options[sIndex].text) && sIndex>0 )
			optionElement.selectedIndex=sIndex;
		else  
			optionElement.selectedIndex=sIndex;	
		document.getElementById('opt_val_name').value= document.getElementById('options_values').options[sIndex].text;
		check_flag=true;
	}	
	function check_dublicate(value,mode){ 
		 var selectbox=document.getElementById('options_values');
		 var length = document.getElementById('options_values').length;
		/*if(length>0) {
			document.getElementById('update_event').style.display='';
			document.getElementById('delete_event').style.display='';
		}*/
		 var sIndex=-1;
		 if(mode=='update')
			 sIndex=document.getElementById('options_values').options.selectedIndex;
		 var lastError='';
		 if(value==''){
			lastError+="* "+page.template["EMPTY_OPTION_VALUE"]+"\n";
		  }
		 
		 if(length>0){
			for(j=0;j<length;j++){
				if(value==selectbox.options[j].text && sIndex!=j) 
					lastError+="* "+page.template["TEXT_OPTION_VALUE_ALREADY_EXISTS"]+"\n";
					//error_msg='* Option Values already exists';
			}
			if(lastError==''){ 
				return true;
			}else{
			  alert(lastError);
			  return false;
			 }
		  }
		  else
		  	return true
	}
	function validateOption() {
		var element,lastError='';
		for(lang in page.languages){
			element=document.getElementById("products_option_name["+page.languages[lang].id+"]");
			if (!element) return true;
			if (element.value==""){
				lastError+="*"+page.template["EMPTY_OPTION_NAME"]+"\n";
				break;
			}
		}
		if(lastError!="") {
			alert(lastError);
			return false;
		}
		else
			return true;
	}
	function doItemUpdate(){
		var data='';
		var form=document.forms["products_attributes"];
		
		for(lang in page.languages)
			data+="products_option_name["+page.languages[lang].id+"]="+encodeURIComponent(document.getElementById("products_option_name["+page.languages[lang].id+"]").value)+"&";
		data+="option_id="+form.option_id.value+"&";	
		
		if(form['options_values']) {
			if(check_flag){
				var length=form['options_values'].length;
				var listbox=form['options_values'];
				var source = '';
				for(icnt=0;icnt<length;icnt++){
					source += listbox.options[icnt].value+'^'+listbox.options[icnt].text +'#';
				}
				form.source.value=source;
				form.work_values.value="exists";
			}	
			
			data+="source="+source+"&";
			data+="work_values="+form.work_values.value+"&";
		}
		command=page.link+"?AJX_CMD=Update&RQ=A&" + new Date().getTime();
		
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}

// JavaScript Document
var check_flag=false;
	function show_detail(val) {
		if(!val) return;
		for(lang in page.languages){
			document.infoSubmit.elements["error_text["+page.languages[lang].id+"]"].disabled=false;
			document.infoSubmit.elements["input_title["+page.languages[lang].id+"]"].disabled=false;
			document.infoSubmit.elements["input_desc["+page.languages[lang].id+"]"].disabled=false;
		}
		document.getElementById('default_value').disabled=false;
		document.getElementById('show_textarea_params').style.display='none';
		document.getElementById('show_textbox_params').style.display='none';
		document.getElementById('show_option_params').style.display='none';
		document.getElementById('show_note').checked=false;
		document.getElementById('display_note').style.display='none';

		switch(val) {
			case 'T':
				document.getElementById('show_textbox_params').style.display='';
				document.getElementById('show_option_params').style.display='none';
				document.getElementById('show_textarea_params').style.display='none';
			break;
			case 'D':
			case 'O':
				document.getElementById('show_option_params').style.display='';
				document.getElementById('show_textbox_params').style.display='none';
				document.getElementById('show_textarea_params').style.display='none';
			break;
			case 'A':
				document.getElementById('show_textarea_params').style.display='';
				document.getElementById('show_textbox_params').style.display='none';
				document.getElementById('show_option_params').style.display='none';
			break;
			case 'L':
				for(lang in page.languages){
					document.infoSubmit.elements["error_text["+page.languages[lang].id+"]"].disabled=true;
					document.infoSubmit.elements["input_title["+page.languages[lang].id+"]"].disabled=true;
					document.infoSubmit.elements["input_desc["+page.languages[lang].id+"]"].disabled=true;
				}
			break;
			case 'U':
				document.getElementById('show_note').checked=true;
				document.getElementById('display_note').style.display='';
			break;
		}

	}
	function doCustomActions(type,func)
	{
		if(func!="") {
			if(func.indexOf('##')>=0) {
				var func_arr=Array();
				func_arr=func.split('##');
				var func_name=func_arr[0];
				eval(func_name+'("'+func_arr[1]+'","'+func_arr[2]+'","'+func_arr[3]+'")');
			}
			else {
				eval(func+'()');
			}
		}
	}
	function disable_form() {
		if(document.getElementById('infoSubmit'))
			document.getElementById('infoSubmit').disabled=true;
	}
	function disable_keys(val) {
		if(!val) {
			document.infoSubmit.show_label.disabled=true;
			document.infoSubmit.required.disabled=true;
		}
		else {
			document.infoSubmit.show_label.disabled=false;
			document.infoSubmit.required.disabled=false;
            document.infoSubmit.show_label.checked=true;
		}
	}
    function activate_label(val) {
        if(!val) {
            if(document.infoSubmit.active.checked)
                document.infoSubmit.show_label.checked=true;
            else
                document.infoSubmit.show_label.checked=val;

        }
    }
	function show_datas(sys_val,lock_val,active) {
		document.getElementById('infoSubmit').disabled=false;
		var form=document.infoSubmit;
		var input_type=document.getElementById('input_type').value;
		if(active!="" && active=='Y') {
			form.show_label.disabled=false;
			form.required.disabled=false;
		}
		else {
			form.show_label.disabled=true;
			form.required.disabled=true;
		}
		if(input_type!="" && input_type=='L') {
			for(lang in page.languages){
				form.elements["error_text["+page.languages[lang].id+"]"].disabled=true;
				form.elements["input_title["+page.languages[lang].id+"]"].disabled=true;
				form.elements["input_desc["+page.languages[lang].id+"]"].disabled=true;
			}
			document.getElementById('default_value').disabled=true;
		}
		if(sys_val=='Y' || (sys_val=='Y' && lock_val=='Y') ) {
			document.getElementById('unique_name').disabled=true;
			document.getElementById('input_type').disabled=true;
			document.getElementById('textbox_size').disabled=document.getElementById('textbox_max_length').disabled=document.getElementById('textbox_min_length').disabled=true;
			document.getElementById('textarea_size').disabled=document.getElementById('textarea_max_length').disabled=document.getElementById('textarea_min_length').disabled=true;
			document.getElementById('option_name').disabled=document.getElementById('option_values').disabled=document.getElementById('option_values_list').disabled=true;
		}
		if(lock_val=='Y') {
			document.getElementById('required').disabled=true;
			document.getElementById('active').disabled=true;
			if(form.elements['display_page[]']) {
				for(i=0;i<form.elements['display_page[]'].length;i++) {
					form.elements['display_page[]'][i].disabled=true;
				}
			}
		}
	}
	function display_tip(val) {
		if(val)
			document.getElementById('display_note').style.display='';
		else
			document.getElementById('display_note').style.display='none';
	}
	function check_form() {
		var form=document.infoSubmit;
		var lastError='';
		var unique_flag=0;
		var system_field=document.getElementById('flagSystem').value;
		var lock_field=document.getElementById('flagLocked').value;
		if(system_field=="" || system_field=='N') {
			if(form.unique_name) {
				if(form.unique_name.value=='') {
					lastError+="*"+page.template["ERR_UNIQUE_NAME"]+"\n";
				}
				else if(form.unique_name.value!="") {
					var alpha_error="";
					var numaric = form.unique_name.value;
					for(var j=0; j<numaric.length; j++){
						var alphaa = numaric.charAt(j);
						var hh = alphaa.charCodeAt(0);
						if((hh > 47 && hh<58) || (hh > 64 && hh<91) || (hh > 96 && hh<123) || hh==95 || hh==35){
						}else	{
							alpha_error="error";
						}
					}
					if(alpha_error!="") lastError+="*"+page.template["ERR_ALPHANUMERIC_TEXT"]+"\n";
				}
				element = document.getElementsByName('uniquename[]');
				for(i=0;i<element.length;i++){
					if(element[i].value==form.unique_name.value)
						unique_flag=1;
				}
				if(unique_flag==1)
					lastError+="* Unique name Already assigned.\n";
			}
		}
		if(document.getElementById('input_type')) {
			for(lang in page.languages){
					element=document.getElementById("label_text["+page.languages[lang].id+"]");
					if (!element) return true;
					if (element.value==""){
						lastError+="*"+page.template["ERR_LABEL_TEXT"]+"\n";
						break;
					}
				}
			if(form.input_type.value!='L' && form.active.checked) {
				for(lang in page.languages){
					element=document.getElementById("error_text["+page.languages[lang].id+"]");
					if (!element) return true;
					if (element.value==""){
						lastError+="*"+page.template["ERR_ERROR_TEXT"]+"\n";
					break;
					}
				}
			}
			
			if(form.input_type.value=='T') {
				if(form.textbox_size && form.textbox_size.value=='')
					lastError+="*"+page.template["ERR_TEXT_BOX_SIZE"]+"\n";
				if(!form.required.disabled && form.required.checked) {
					if(parseInt(form.textbox_max_length.value)>=0 && parseInt(form.textbox_min_length.value)>=0) {
						
					/*	if(parseInt(form.textbox_min_length.value)>parseInt(form.textbox_max_length.value))
							lastError+="*"+page.template["ERR_TEXT_BOX_LENGTH"]+"\n";*/
					}
					if(parseInt(form.textbox_min_length.value)<0 || parseInt(form.textbox_max_length.value)<0 || isNaN(form.textbox_min_length.value) || isNaN(form.textbox_max_length.value))
						lastError+="*"+page.template["ERR_TEXT_BOX_LENGTH_NUMERIC"]+"\n";
				}
			}
			if(form.input_type.value=='A') {
				if(form.textarea_size && form.textarea_size.value=='' )
					lastError+="*"+page.template["ERR_TEXT_AREA_SIZE"]+"\n";
				if(!form.required.disabled && form.required.checked) {
					if(parseInt(form.textarea_max_length.value)>=0 && parseInt(form.textarea_min_length.value)>=0) {
						if(parseInt(form.textarea_min_length.value)>parseInt(form.textarea_max_length.value))
							lastError+="*"+page.template["ERR_TEXT_AREA_LENGTH"]+"\n";
					}
					if(parseInt(form.textarea_min_length.value)<0 || parseInt(form.textarea_max_length.value)<0 || isNaN(form.textarea_min_length.value) || isNaN(form.textarea_max_length.value))
						lastError+="*"+page.template["ERR_TEXT_AREA_LENGTH_NUMERIC"]+"\n";
				}
			}
		}

		if(lock_field!="" && lock_field!='Y') {
			if(form.elements['display_page[]']) {
				var cnt=0;
				for(i=0;i<form.elements['display_page[]'].length;i++) {
					if(form.elements['display_page[]'][i].checked) {
						cnt++;
					}
				}
				if(cnt==0) lastError+="*"+page.template["ERR_DISPLAY_PAGE"]+"\n";
			}
		var input_val=form.input_type.value;
		if(input_val=='O' || input_val=='D') {
			var def_value="";
			var source_value="";
			var source='';
			if(input_val=='D') {
				if(document.getElementById('option_values_list').length<=0)
					def_value='Please Select@@-1##';
			}
			if(check_flag){
				var length=document.getElementById('option_values_list').length;
				var listbox=document.getElementById('option_values_list');
				for(icnt=0;icnt<length;icnt++){
					source += listbox.options[icnt].value+'##';
				}
				if(input_val=='D') {
					if(source.indexOf('-1')<0) def_value='Please Select@@-1##';
				}
			}
			if(input_val=='O')
				source_value=source;
			else if(input_val=='D')

			source_value=def_value+source;
			if(document.getElementById('option_values_list').length<=0)
				lastError+="*"+page.template["ERR_OPTION_VALUES_EMPTY"]+"\n";
			form.source.value=source_value;
			form.work_values.value="exists";
		}

		}
		var id=document.getElementById('info_id').value;
		if(lastError!="") {
			alert(lastError);
			return false;
		}
		return true;
	}
	function do_opt_value_select(mode){
		var optionElement = document.getElementById('option_values_list');
		if (optionElement.disabled) return;
		var pos=optionElement.length;
			switch(mode){
				case "select" :
					sIndex=document.getElementById('option_values_list').selectedIndex;
					if(sIndex<0) return;
					var text=document.getElementById('option_values_list').options[sIndex].value;
					var textArr=Array();
					textArr=text.split('@@');
					document.getElementById('option_name').value=textArr[0];
					document.getElementById('option_values').value=textArr[1]
				break;
				case "update":
				case "add":
					var option = document.createElement('OPTION');
					option.text = document.getElementById('option_name').value;
					option.value = document.getElementById('option_name').value+'@@'+document.getElementById('option_values').value;
					var new_opt_name = document.getElementById('option_name').value;
					var new_opt_val=document.getElementById('option_values').value;
					var new_input=new_opt_name;
					sIndex=document.getElementById('option_values_list').options.selectedIndex;
					if(check_dublicate(new_opt_name,new_opt_val,mode)){
						if (mode=="update") {
						if(sIndex<0) return;
						document.getElementById('option_values_list').options[sIndex].text=new_input;
						optionElement.remove(sIndex);
						check_flag=true;
						//break;
						}
						if(optionElement.add.length==2){
							optionElement.add(option,null);
						}	else {
							optionElement.add(option,pos);
						}
						check_flag=true;
						document.getElementById('option_name').value='';
						document.getElementById('option_values').value='';
					}
				break;
				case "delete":
					sIndex=optionElement.selectedIndex;
					if(sIndex<0) return;
					optionElement.remove(sIndex);
					if(optionElement.length==0) break;
					sIndex=sIndex-1;
					if(sIndex==-1) sIndex=0;
					if(  (optionElement.options[sIndex].text) && sIndex>0 )
					optionElement.selectedIndex=sIndex;
					else  optionElement.selectedIndex=sIndex;
					document.getElementById('option_name').value='';
					document.getElementById('option_values').value='';
					check_flag=true;
				break;
			}
	}
	function check_dublicate(name,val,mode){
		var selectbox=document.getElementById('option_values_list');
		var length = document.getElementById('option_values_list').length;
		var sIndex=-1;
		if(mode=='update') sIndex=document.getElementById('option_values_list').options.selectedIndex;
		var error_msg='';
		if(val==''){
			alert('* Give Valid Option Value');
			return false;
		}
		if(name=='') {
			alert('* Give Valid Option Name');
			return false;
		}
		var value=name+'@@'+val;
		if(length>0){
			for(j=0;j<length;j++){
				var opt_val_arr=Array();
				var opt_val=selectbox.options[j].value;
				var opt_val_arr=opt_val.split('@@');
				if((name==opt_val_arr[0] || val==opt_val_arr[1]) && sIndex!=j)  {
					if(name==opt_val_arr[0]) error_msg='* Option Name already exists';
					if(val==opt_val_arr[1]) error_msg='* Option Value already exists';
				}
			}

			if(error_msg==''){
				return true;
			} else {
				alert(error_msg);
				return false;
			}
		}
		else return true
	}
	function doItemUpdate() {
		var data='';
		var display_page='';
		var form=document.forms["infoSubmit"];
		if(form["flagSystem"].value!='Y') {
			if(form["unique_name"]) data+="unique_name="+form["unique_name"].value+"&";
		}
		if(form["input_type"]) data+="input_type="+form["input_type"].value+"&";
		if(form["input_type"]) {
			if(form["input_type"].value=='T') {
				if(form["textbox_size"]) data+="textbox_size="+form["textbox_size"].value+"&";
				if(form["textbox_min_length"]) data+="textbox_min_length="+form["textbox_min_length"].value+"&";
				if(form["textbox_max_length"]) data+="textbox_max_length="+form["textbox_max_length"].value+"&";
			}
			else if(form["input_type"].value=='A') {
				if(form['textarea_size']) data+="textarea_size="+form["textarea_size"].value+"&";
				if(form['textarea_min_length']) data+="textarea_min_length="+form["textarea_min_length"].value+"&";
				if(form['textarea_max_length']) data+="textarea_max_length="+form["textarea_max_length"].value+"&";
			}
			else if(form["input_type"].value=='O' || form["input_type"].value=='D') {
				data+="source="+form["source"].value+"&";
				data+="work_values="+form["work_values"].value+"&";
				if(form["input_type"].value=='O') {
					var val_source=form["source"].value;
					if(val_source.indexOf('-1')>0) {
						data+="source="+val_source.substring(19,(val_source.length))+"&";
					}
				}
			}

			if(form["input_type"]!='L') {
				for(lang in page.languages){
					data+="error_text["+page.languages[lang].id+"]="+(form["error_text["+page.languages[lang].id+"]"].value)+"&";
					data+="input_title["+page.languages[lang].id+"]="+(form["input_title["+page.languages[lang].id+"]"].value)+"&";
					data+="input_desc["+page.languages[lang].id+"]="+(form["input_desc["+page.languages[lang].id+"]"].value)+"&";
				}
			}
		}
		for(lang in page.languages)
			data+="label_text["+page.languages[lang].id+"]="+(form["label_text["+page.languages[lang].id+"]"].value)+"&";

		if(form["default_value"]) data+="default_value="+form["default_value"].value+"&";
		if(form["flagLocked"].value!='Y') {
				if(form["active"] && form["active"].checked) {
					data+="active="+form["active"].value+"&";
					if(form["required"]) {
						if(form["required"].checked) data+="required="+form["required"].value+"&";
					}
				}
			if(form.elements['display_page[]']) {
				for(i=0;i<form.elements['display_page[]'].length;i++) {
					if(form.elements['display_page[]'][i].checked) {
						display_page+=form.elements['display_page[]'][i].value + ",";
					}
				}
				if(display_page!="") {
					display_page=display_page.substring(0,(display_page.length-1));
					
				}

			}
            var hide_display_page='';
            if(form.elements['hidden_display_page'].checked) {
                hide_display_page=',A';
            }
           data+="display_page="+display_page+hide_display_page+"&";
		}
		if(form["active"].checked) {
			if(form["show_label"]) {
				if(form["show_label"].checked) data+="show_label="+form["show_label"].value+"&";
			}
		}
		data+="info_id="+form["info_id"].value+"&";
		data+="system="+form["flagSystem"].value+"&";
		data+="locked="+form["flagLocked"].value+"&";

		command=page.link+"?AJX_CMD=Update&RQ=A&" + new Date().getTime();
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	function sortInfoValidate(action){

		var element=document.getElementById(action.type+action.id+"row");
		if (action.mode=="up" && element.rowIndex==1) {
			return false;
		}
		else if (action.mode=="down" && element.rowIndex==element.parentNode.rows.length-1) {
			return false;
		}
		return true;
	}

    function doPageAction2(action){

		if (action.closePrev && !closePreviousOpened(action)) return;
		checkMessageDisplay(action);
		page.lastAction=action;
		
		do_get_command(page.link+'?AJX_CMD='+action.get+'&page='+action.params);

	}

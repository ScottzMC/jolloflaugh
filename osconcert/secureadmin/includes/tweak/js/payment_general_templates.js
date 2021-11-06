	function groupValidate(){
		var form=document.forms["customer_groups"];
		var lastError='';
		var element='';
		var element1='',element2='',element3='';
/*
			element = document.getElementById('event_details_content');
			if(element.value=='' || str_trim(element.value)=='')
			lastError+="* "+page.template["ERROR_EVENT_DETAILS_CONTENT"]+"\n";
			
*/
//Image Box Cannot Empty and Image type should not be .gif file

			element1 = document.getElementById('shop_logo_image');
			if((element1) && (element1.value=='' || str_trim(element1.value)==''))
			lastError+="* "+page.template["ERROR_SHOP_LOGO"]+"\n";


			/*element2 = document.getElementById('sponsor_logo_image');
			if((element2) && (element2.value=='' || str_trim(element2.value)==''))
			lastError+="* "+page.template["ERROR_FREEWAY_LOGO"]+"\n";*/
			
			element3 = document.getElementById('member_logo_image');
			if((element3) && (element3.value=='' || str_trim(element3.value)==''))
			lastError+="* "+page.template["ERROR_MEMBER_LOGO"]+"\n";
			
			element4 = document.getElementById('event_condition_content');
			if((element4) && (element4.value=='' || str_trim(element4.value)==''))
			lastError+="* "+page.template["ERROR_EVENT_CONDITION_CONTENT"]+"\n";
			
			element5 = document.getElementById('event_condition_large');
			if((element5) && (element5.value=='' || str_trim(element5.value)==''))
			lastError+="* "+page.template["ERROR_EVENT_CONDITION_LARGE"]+"\n";
			
			element6 = document.getElementById('template_height');
			if((element6) && (element6.value=='' || str_trim(element6.value)==''))
			lastError+="* "+page.template["ERROR_TEMPLATE_HEIGHT"]+"\n";
			
			element7 = document.getElementById('template_width');
			if((element7) && (element7.value=='' || str_trim(element7.value)==''))
			lastError+="* "+page.template["ERROR_TEMPLATE_WIDTH"]+"\n";


			element8 = document.getElementById('event_details_content');
			if((element8) && (element8.value=='' || str_trim(element8.value)==''))
			lastError+="* "+page.template["ERROR_EVENT_DETAILS_CONTENT"]+"\n";
			
			element9 = document.getElementById('customer_details_content');
			if((element9) && (element9.value=='' || str_trim(element9.value)==''))
			lastError+="* "+page.template["ERROR_CUSTOMER_DETAILS_CONTENT"]+"\n";
/*			
			element3 = document.getElementById('directory');
			if(element3.value=='' || str_trim(element3.value)=='')
			lastError+="* "+page.template["ERROR_COUNTRY_ISO_CODE_THREE"]+"\n";
			*/

			if (lastError!=''){
				alert(lastError);
				return false;
			}
		return true;
	}
	
	function doItemUpdate(){
		var data='';

		var form=document.forms["customer_groups"];
		data+="action_type="+form["action_type"].value+"&";
		//data+="bar_image_position="+form["bar_image_position"].value+"&";
		data+="event_condition_content="+form["event_condition_content"].value+"&";
		data+="event_condition_position="+form["event_condition_position"].value+"&";
		if((form["event_details_content"]) && (form["event_details_content"].value!='')){
		data+="event_details_content="+form["event_details_content"].value+"&";		
		} else{
		data+="customer_details_content="+form["customer_details_content"].value+"&";		
		}
		if((form["event_details_position"]) && (form["event_details_position"].value!='')){
		data+="event_details_position="+form["event_details_position"].value+"&";
		}

		data+="merge_fields="+form["merge_fields"].value+"&";
		data+="sponsor_logo_image="+form["sponsor_logo_image"].value+"&";
		data+="sponsor_logo_position="+form["sponsor_logo_position"].value+"&";
		if(form["shop_logo_image"]){
		data+="shop_logo_image="+form["shop_logo_image"].value+"&";		
		data+="shop_logo_position="+form["shop_logo_position"].value+"&";
		} else{
		data+="member_logo_image="+form["member_logo_image"].value+"&";		
		data+="member_logo_position="+form["member_logo_position"].value+"&";
		data+="event_condition_large="+form["event_condition_large"].value+"&";
		}

		data+="template_height="+form["template_height"].value+"&";
		data+="template_type="+form["template_type"].value+"&";
		data+="template_width="+form["template_width"].value+"&";		
		
		command=page.link+"?AJX_CMD=Update&RQ=A&" + new Date().getTime();
		
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
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

		if (result_splt[1] && result_splt[1]!='') doJASON_function(result_splt[1],page.lastAction);
		changeBoxStyle(action,'Select');
	}
	
	function doPaymentResult(result,action){
		var result_splt=result.split("@sep@");
		var element=document.getElementById(action.type+action.id+"content");
		element.innerHTML=result_splt[0];

		page.opened[action.type]=action;
		if (result_splt[1] && result_splt[1]!='') doJASON(result_splt[1],page.lastAction);
		changeBoxStyles(action,'Select');
		page.locked=false;
	}
	
	function changeBoxStyles(action,style_type){
		var element;
		if (action.style) {
			if (document.getElementById(action.type+action.id+"style"))
				element=document.getElementById(action.type+action.id+"style");
			else if (document.getElementById(action.type+action.id)){
				element=document.getElementById(action.type+action.id);
			}
			if (style_type=='check'){
				if (element.className!=action.style+"Select"){
					element.className=action.style;
				}
			} else {
				element.className=action.style+style_type;
			}
		} 
	}
	
	function doJASON_function(data,action){
		var list,icnt,n;
		var command,key;
		if (data=="") return;
		data=ajxDecrypt(data);
		eval("list="+data);
		for(command in list){
		
		var row=document.getElementById(list[command].type+list[command].id+"row");
		if(row){
		row.parentNode.removeChild(row);
//		delete page.opened[action.type];
		}
		setAlternateColors(action.type);
		}
	}
	
	function doCancelDelete(action){
		page.locked=false;
		if (action.extraFunc) action.extraFunc();
		closePaymentRow(action.id,action.type,"Hover");
		updateMenu(action,(action.id==-1?"":",normal,"));
		page.lastAction=false;
		delete page.opened[action.type];
	}
	
	function doDisplayPayment(action){
//	if (page.lastAction || page.locked) return;
//		if (!closePreviousOpened(action)) return;
		var temp='';
		var element=document.getElementById(action.type+action.id);
		if (!element) return;
		page.lastAction=action;
		addContentRow(element,1);
		if (action.get.indexOf("Edit")>=0) page.locked=true;
				
		appendExtraParams(action);
		if (action.extraFunc) action.extraFunc();
		do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params);
	}
	
	function closePaymentRow(id,type,style){
		var element=document.getElementById(type+id);
	//	element.deleteRow(element.rows.length-1);
		if(style=='') style='Hover';
		var elem=document.getElementById(type+id+"content");
		if(elem){
		elem.innerHTML='';
		}
		changeBoxStyle(page.opened[type],style);
	}
	function doPreviewDisplay(action){
		var data='';
		if(groupValidate()==false){
			return false;
		}
		var form=document.forms["customer_groups"];
		data+="action_type="+form["action_type"].value+"&";
		data+="bar_image_position="+form["bar_image_position"].value+"&";
		data+="event_condition_content="+form["event_condition_content"].value+"&";
		data+="event_condition_position="+form["event_condition_position"].value+"&";
		if((form["event_details_content"]) && (form["event_details_content"].value!='')){
		data+="event_details_content="+form["event_details_content"].value+"&";		
		} else{
		data+="customer_details_content="+form["customer_details_content"].value+"&";		
		}
		if((form["event_details_position"]) && (form["event_details_position"].value!='')){
		data+="event_details_position="+form["event_details_position"].value+"&";
		}

		data+="merge_fields="+form["merge_fields"].value+"&";
		data+="sponsor_logo_image="+form["sponsor_logo_image"].value+"&";
		data+="sponsor_logo_position="+form["sponsor_logo_position"].value+"&";
		if(form["shop_logo_image"]){
		data+="shop_logo_image="+form["shop_logo_image"].value+"&";		
		data+="shop_logo_position="+form["shop_logo_position"].value+"&";
		} else{
		data+="member_logo_image="+form["member_logo_image"].value+"&";		
		data+="member_logo_position="+form["member_logo_position"].value+"&";
		data+="event_condition_large="+form["event_condition_large"].value+"&";
		}

		data+="template_height="+form["template_height"].value+"&";
		data+="template_type="+form["template_type"].value+"&";
		data+="template_width="+form["template_width"].value+"&";		
//		alert('sdfg>>'+action.get);
		command=page.link+"?AJX_CMD="+action.get+'&'+action.params+"&RQ=A&" + new Date().getTime();
		page.lastAction=action;
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);

/*
		page.lastAction=action;
		do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params);
*/		
	}

	function doGeneratePDF(result,action){
		var result_splt=result.split("@sep@");

		if((result_splt[0]=='preview') && (result_splt[1]!='')){
			window.open(result_splt[1]);
		}
	}

	function AddField(box){
		var select_field;
		var select_id;		
		var frmName=document.customer_groups;
		if (frmName.merge_fields.selectedIndex<=-1) return;
				
		select_id=frmName.merge_fields.options[frmName.merge_fields.selectedIndex].value;
		if (select_id.length>3) return;
				
		select_field="%%" + frmName.merge_fields.options[frmName.merge_fields.selectedIndex].text + "%%";
		textArea=frmName.elements[box];
		
		textArea.focus();
		if (textArea.selection){
			sRange  = document.selection.createRange();
			var sText   = sRange.text;
			sRange.text = select_field;
		} else if (textArea.selectionStart || textArea.selectionStart == "0") {
			var startPos = textArea.selectionStart;
			var endPos = textArea.selectionEnd;
			textArea.value = textArea.value.substring(0, startPos)
					+ select_field + textArea.value.substring(endPos, textArea.value.length);
		} else {
			textArea.value += select_field;
		}	
	}

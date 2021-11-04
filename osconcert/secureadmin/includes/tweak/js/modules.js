// JavaScript Document
function check_selected_values(configcheckbox,controlname,countrycontrol,key){
		var element=document.getElementById(controlname);
		var elementZone=document.getElementById(controlname);
		var checkbox;
		if (!element) return;
		var result='';
		var icnt;
		var count=parseInt(document.getElementById("count_"+controlname).value);
		for (icnt=0;icnt<=count;icnt++){
			checkbox=document.getElementById("check_"+icnt+"_"+controlname);
			if (checkbox.checked){
				result+=','+checkbox.value;
			}
		}
		result=result.substr(1);
		element.value=result;
		var elementCountry=document.getElementById(countrycontrol);
		if (!elementCountry) return;
		var row_content=document.getElementById("ROW_"+key+"content" );
		row_content.innerHTML=document.getElementById("ajxLoad").innerHTML;
		eval("doSimpleAction({'id':'" +key +"','get':'GetZone','result':doDisplayResult,'type':'ROW_','params':'set=" + page.set + "&cfg_key="+key+"&cfg_zone="+elementZone.value+"&cfg_value="+elementCountry.value+ "'})");
	}
	function doModuleUpdate(action)
	{
		input_image=document.getElementById('hasImages').value;
		if (input_image=="yes"){
			if (!AIM.submit(document.config_input, {'onStart' : startCallback, 'onComplete' : completeCallback})) return;
			document.config_input.submit();
		} else {
			do_post_command(action.uptForm,page.link+'?AJX_CMD='+action.get+'&'+action.params);
		}
		page.locked=false;
	}
	function startCallback() {
	// make something useful before submit (onStart)
		return true;
	}
	function completeCallback(response) {
		// make something useful after (onComplete)
		do_result(response);
	}
	function save_round_factor(){
		do_post_command('frm_round_factor',page.link+'?AJX_CMD=SaveRoundFactor&set='+page.set);
		//if(document.getElementById("img_loading")) document.getElementById("img_loading").style.display="";
	}

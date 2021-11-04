// JavaScript Document
var baby=new Array();
baby[1]="";	
var cEvent = 0;
function list_detail(id){
	doEventAction({'id':"'"+id+"'",'get':'Items','style':'boxRow','type':'shInfo','params':'gID='+id+''});
}
function doEventAction(action){
	page.lastAction={'result':doEventResult,'id':action.id};
	do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params);
}
function doEventResult(result,cid){
	var result_splt=result.split("@sep@");
	var element=document.getElementById('shInfototalContentResult');
	element.innerHTML=result_splt[0];
	page.lastAction=false;	
}
function showColor(val) { 
	document.infoboxes.hexval.value = val; 
} 
function popupWindow(action){ 
	var contentText;
	switch(action){
		case 'filename':
			contentText=page.template["TEXT_INFOBOX_HELP_FILENAME"];
			//'<?php echo addslashes(TEXT_INFOBOX_HELP_FILENAME);?>';
			break;
		case 'heading':
			contentText=page.template["TEXT_INFOBOX_HELP_HEADING"];
			//'<?php echo addslashes(TEXT_INFOBOX_HELP_HEADING);?>';
			break;
		// case 'template':
			// contentText=page.template["TEXT_INFOBOX_HELP_DEFINE"];
			// //'<?php echo addslashes(TEXT_INFOBOX_HELP_DEFINE);?>';
			// break;
		// case 'define':
			// contentText=page.template["TEXT_INFOBOX_HELP_DEFINE"];
			// //'<?php echo addslashes(TEXT_INFOBOX_HELP_DEFINE);?>';
			// break;
	}
	
	content='<table border="0" cellspacing="0" cellpadding="4" class="formArea" width="100%"><tr><td class=smallText>';
	content+=contentText;
	content+='</td></tr></table>';
	popwidth=250;
	baby[1]=content;
	if (baby[1]!="")
	{
		popup("1","#ffffff");
	}	
}
function validateForm() {
	var error_message="";
	
	var infobox_file_name = document.infoboxes.infobox_file_name.value;
	var box_heading = document.infoboxes.box_heading.value;
	//var box_template= document.infoboxes.box_template.value;
	//var infobox_define = document.infoboxes.infobox_define.value;  
	//var hexval = document.infoboxes.hexval.value;  
	

	if( (infobox_file_name == "") || (str_trim(infobox_file_name)=="") ) {
		error_message+=page.template["JS_INFO_BOX_FILENAME"]+"\n";
	}
	
	// if( (box_template == "") || (str_trim(box_template)=="") ){
		// error_message+=page.template["JS_INFO_BOX_TEMPLATE"]+"\n";
	// }
	if( (box_heading == "") || (str_trim(box_heading)=="") ) {
		error_message+=page.template["JS_INFO_BOX_HEADING"]+"\n";
	}
	
	// if (infobox_define == "" || infobox_define == "BOX_HEADING_????" || str_trim(infobox_define)=="") {
		// error_message+=page.template["JS_BOX_HEADING"]+"\n";
	// }
	
	// if( (hexval == "") || (str_trim(hexval)=="") ) {
		// error_message+=page.template["JS_BOX_COLOR_ERROR"]+"\n";
	// }
	
	if (error_message!="") {
		alert(error_message);
		return false;
	} else {
		return true;
	}
}
function doItemUpdate() {
	var data="";
	var form=document.infoboxes;
	data+="infobox_file_name="+form["infobox_file_name"].value+"&";
	data+="box_heading="+form["box_heading"].value+"&";
	//data+="box_template="+form["box_template"].value+"&";
	//data+="infobox_define="+form["infobox_define"].value+"&";
	//data+="hexval="+form["hexval"].value+"&";
	data+="infoBoxId="+form["infoBoxId"].value+"&";
	data+="tempId="+form["tempId"].value+"&";
	
	command=page.link+"?AJX_CMD=Update&RQ=A&" + new Date().getTime();
	xmlHttp.open("POST", command, true);
	xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
	xmlHttp.onreadystatechange = handleServerResponse;
	xmlHttp.send(data);
}
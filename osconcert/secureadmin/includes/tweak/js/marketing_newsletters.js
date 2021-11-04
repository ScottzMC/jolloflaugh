	function NewsletterValidate(){
		var form=document.forms["save_newsletters"];
		var lastError='';
		var element='';
		var element1='';

			element = document.getElementById('edit_title');
			if(element.value=='' || str_trim(element.value)=='')
			lastError+="* "+page.template["ERROR_NEWSLETTER_TITLE"]+"\n";

			
			if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function doNewsUpdate(){
		var data='';
		var form=document.forms["save_newsletters"];
		
		data+="edit_modules="+form["edit_modules"].value+"&";
		data+="edit_title="+form["edit_title"].value+"&";
		data+="message_text="+encodeURIComponent(form["message_text"].value)+"&";
		data+="newsletter_id="+form["newsletter_id"].value+"&";
		
		command=page.link+"?AJX_CMD=NewslettersUpdate&RQ=A&" + new Date().getTime();

		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	
	
function mover(move) {
  if (move == 'remove') {
    for (x=0; x<(document.notifications.products.length); x++) {
      if (document.notifications.products.options[x].selected) {
        with(document.notifications.elements['chosen[]']) {
          options[options.length] = new Option(document.notifications.products.options[x].text,document.notifications.products.options[x].value);
        }
        document.notifications.products.options[x] = null;
        x = -1;
      }
    }
  }
  if (move == 'add') {
    for (x=0; x<(document.notifications.elements['chosen[]'].length); x++) {
      if (document.notifications.elements['chosen[]'].options[x].selected) {
        with(document.notifications.products) {
          options[options.length] = new Option(document.notifications.elements['chosen[]'].options[x].text,document.notifications.elements['chosen[]'].options[x].value);
        }
        document.notifications.elements['chosen[]'].options[x] = null;
        x = -1;
      }
    }
  }
  return true;
}


function MailValidate(){
		
		var lastError='';
		var element='';
		var element1='';
			
			var chosen_array=new Array(); 
			
			if(document.getElementById('chosen[]') && document.getElementById('chosen[]').length<=0)
			{
			lastError+="* "+page.template["ERROR_SELECT_PRODUCT"]+"\n";
			}
				
			
			
			if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function doMailUpdate(){
		var data='';
		
		var chosen_array=new Array(); 
		if(document.getElementById('chosen[]'))
			for(i=0;i<document.getElementById('chosen[]').length;i++){
			chosen_array[i]=document.getElementById('chosen[]')[i].value;
			}

		data+="newsletter_id="+document.getElementById("newsletter_id").value+"&";

		command=page.link+"?AJX_CMD=MailUpdate&chosen="+chosen_array+"&RQ=A&"+new Date().getTime();
		
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	
	function doCustomActions(type,func) {
		eval(func+'()');
	}

	function doProductEditor(){
	if (page.editorLoaded) return;
	var deElements=Array();
	page.editorControls[0]="message_text";
	textEditorInit();
	}
	
	
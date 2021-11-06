var xmlHttp = createXmlHttpRequestObject(); 

// retrieves the XMLHttpRequest object
function createXmlHttpRequestObject() 
{  
  // will store the reference to the XMLHttpRequest object
var xmlHttp;
  try
    {
    // Firefox, Opera 8.0+, Safari
    xmlHttp=new XMLHttpRequest();
    }
  catch (e)
    {
    // Internet Explorer
    try
      {
      xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
      }
    catch (e)
      {
      try
        {
        xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
      catch (e)
        {
        alert("Your browser does not support AJAX!");
        }
      }
    }
    return xmlHttp;
}

function do_close(){
	if (xmlHttp.readyState>0 && xmlHttp.readyState<4){
		xmlHttp.abort();
	}
}
// make asynchronous HTTP request using the XMLHttpRequest object 
function do_get_command(command)
{ 
  // proceed only if the xmlHttp object isn't busy
  if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
  {  
	  command+="&RQ=A&" +new Date().getTime();
    xmlHttp.open("GET", command, true);  
    // define the method to handle server responses
    xmlHttp.onreadystatechange = handleServerResponse;
    // make the server request
    xmlHttp.send(null);
  }
}
function do_post_command(formname,command)
{  
  // proceed only if the xmlHttp object isn't busy
 var icnt=0,frm;
 if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
  {
	frm=document.forms[formname];
	var params="";
	for (icnt=0;icnt<frm.elements.length;icnt++){
	    c_obj=frm.elements[icnt];
	//	alert(c_obj.type);
		if(c_obj.type.toLowerCase()=="radio"){
			if(c_obj.checked){
				params+=c_obj.name+"="+encodeURIComponent(c_obj.value)+"&";
			}
		} else if(c_obj.type.toLowerCase()=="checkbox")	{
			if(c_obj.checked)
			{
				if(c_obj.value){
					c_val=c_obj.value;
				} else{
					c_val="on";
				}
				params+=c_obj.name+"="+encodeURIComponent(c_val)+"&";
			}
		} else if(c_obj.tagName.toLowerCase()=="select"){
			if (c_obj.selectedIndex>=0){
			params+=c_obj.name+"="+encodeURIComponent(c_obj.options[c_obj.selectedIndex].value)+"&";
			} else {
				params+=c_obj.name+"="+"&";
			}
			//alert(params);
		} else {
			//c_obj.value=c_obj.value.replace(/&/g,'|||');
			params+=c_obj.name+"="+encodeURIComponent(c_obj.value)+"&";
			//params+=c_obj.name+"="+URLEncode(c_obj.value)+"&";
			//params+=c_obj.name+"="+encodeURL(c_obj.value)+"&"
			
		}
	}
	command+="&RQ=A&" + new Date().getTime();
    // define the method to handle server responses
    xmlHttp.open("POST", command, true);
	
	xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
	xmlHttp.onreadystatechange = handleServerResponse;
	//xmlHttp.setRequestHeader("Content-length", params.length);
	//xmlHttp.setRequestHeader("Connection", "close");
    // make the server request
   xmlHttp.send(params);
  }
}
// executed automatically when a message is received from the server
function handleServerResponse() 
{ 
  // move forward only if the transaction has completed
  if (xmlHttp.readyState == 4) 
  { 
    // status of 200 indicates the transaction completed successfully
    if (xmlHttp.status == 200) 
    { 
      // extract the XML retrieved from the server
      response = xmlHttp.responseText;
	  
	  var sess=response.lastIndexOf('<!--');
	  var pos=response.substr(sess);
	  if(pos=='<!--session expired-->') {
	  	location.href="login.php";
	  }
    	  do_result(response);
    } 
    // a HTTP status different than 200 signals an error
    else if (xmlHttp.status != 0)
    {
      alert("There was a problem accessing the server: " + xmlHttp.status);
    }
  }
}
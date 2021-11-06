
<script language="javascript">
var xmlHttp = createXmlHttpRequestObject(); 
var progressOn=false;
// retrieves the XMLHttpRequest object
function createXmlHttpRequestObject() 
{  
  // will store the reference to the XMLHttpRequest object
  var xmlHttp;
  // if running Internet Explorer
  if(window.ActiveXObject)
  {
    try
    {
      xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    catch (e) 
    {
      xmlHttp = false;
    }
  }
  // if running Mozilla or other browsers
  else
  {
    try 
    {
      xmlHttp = new XMLHttpRequest();
    }
    catch (e) 
    {
      xmlHttp = false;
    }
  }
  // return the created object or display an error message
  if (!xmlHttp)
    alert("Error creating the XMLHttpRequest object.");
  else 
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
  if (progressOn) do_close();
  //if ((xmlHttp.readyState == 4 || xmlHttp.readyState == 0))
  //{
	  command+="&" +new Date().getTime();

    xmlHttp.open("GET", command, true);  
    // define the method to handle server responses
    xmlHttp.onreadystatechange = handleServerResponse;
    // make the server request
    xmlHttp.send(null);
	progressOn=true;
  //}
}
function do_post_command(formname,command)
{
  // proceed only if the xmlHttp object isn't busy
  //if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
  //{
	  // form the send parameters
	frm=document.forms[formname];
	params="";
	for (icnt=0;icnt<frm.elements.length;icnt++){
		params+=frm.elements[icnt].name+"="+encodeURI(frm.elements[icnt].value)+"&";
	}
	params+=new Date().getTime();
	command+="&" + new Date().getTime();
    // define the method to handle server responses
    
    xmlHttp.open("POST", command, true);
	xmlHttp.onreadystatechange = handleServerResponse;
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	//xmlHttp.setRequestHeader("Content-length", params.length);
	//xmlHttp.setRequestHeader("Connection", "close");
    // make the server request
   xmlHttp.send(params);
  //}
  progressOn=true;
}
// executed automatically when a message is received from the server
function handleServerResponse() 
{
  try{
  // move forward only if the transaction has completed
	  if (xmlHttp.readyState == 4) 
	  {
		// status of 200 indicates the transaction completed successfully
		if (xmlHttp.status == 200) 
		{
		  // extract the XML retrieved from the server
		  response = xmlHttp.responseText;
		  do_result(response);
		} 
		// a HTTP status different than 200 signals an error
		else if (xmlHttp.status != 0)
		{
		  alert("There was a problem accessing the server: " + xmlHttp.status);
		}
	  }
	} catch(e){
	}
	progressOn=false;
}
</script>
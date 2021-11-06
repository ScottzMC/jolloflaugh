var Xoffset=10;        // modify these values to ...
var Yoffset= 10;        // change the popup position.
var popwidth=250;       // popup width
var bcolor="darkgray";  // popup border color
var fcolor="black";     // popup font color
var fface="verdana";    // popup font face
var POP_OPEN=false;
// create content box
document.write("<div ID='pup'></div>");

// id browsers
var iex=(document.all); 
var nav=(document.layers);
var old=(navigator.appName=="Netscape" && !document.layers && !document.getElementById);
var n_6=(window.sidebar);
var netscape=navigator.appName;


// assign object
var skin;
if(nav) skin=document.pup;
if(iex) skin=pup.style;
if(n_6) skin=document.getElementById("pup").style;
if(netscape=='Netscape'){ skin=document.getElementById("pup").style;   }

// park modifier
var yyy=-1000;

// capture pointer




//if(nav)document.captureEvents(Event.MOUSEOVER);
//if(n_6) document.addEventListener("mouseover",get_mouse,true);
//if(nav||iex)document.onmouseover=get_mouse;

// set dynamic coords
function get_mouse(e)
{
  var x,y;

  if(nav || n_6 || (netscape=='Netscape') ) x=e.pageX;
  if(iex) x=event.x+document.body.scrollLeft; 
  
  if(nav || n_6 || (netscape=='Netscape')) y=e.pageY;
  if(iex)
  {
	y=event.y;
	if(navigator.appVersion.indexOf("MSIE 4")==-1)
	  y+=document.body.scrollTop;
  }

  if(iex || nav)
  {
	skin.top=y+yyy;
	skin.left=x+Xoffset; 
  }

  if(n_6 || (netscape=='Netscape'))
  {
	skin.top=(y+yyy)+"px";
    if(typeof(x)!="undefined" && typeof(Xoffset)!="undefined")
	skin.left=(x+Xoffset)+"px";
  }
  if(typeof(x)!="undefined" && typeof(Xoffset)!="undefined")
  nudge(x);
}

// avoid edge overflow
function nudge(x)
{
  var extreme,overflow,temp;

  // right
  if(iex) extreme=(document.body.clientWidth-popwidth);
  if(n_6 || nav || (netscape=='Netscape')) extreme=(window.innerWidth-popwidth);

  if(parseInt(skin.left)>extreme)
  {
	overflow=parseInt(skin.left)-extreme;
	temp=parseInt(skin.left);
	temp-=overflow;
	if(nav || iex) skin.left=temp;
	if(n_6 || (netscape=='Netscape'))skin.left=temp+"px";
  }

  // left
  if(parseInt(skin.left)<1)
  {
	overflow=parseInt(skin.left)-1;
	temp=parseInt(skin.left);
	temp-=overflow;
	if(nav || iex) skin.left=temp;
	if(n_6 || (netscape=='Netscape'))skin.left=temp+"px";
  }
}

function popup(title,bak)
{	
	
	 if (!POP_OPEN){
		 if (nav) document.captureEvents(Event.MOUSEMOVE);
		 if (n_6) document.addEventListener("mousemove",get_mouse,true);
		 if (nav||iex || netscape=='Netscape') document.onmousemove=get_mouse;
	 }
	 POP_OPEN=true;
	 var content="<TABLE WIDTH='"+popwidth+"' BORDER='0' BORDERCOLOR='#C2C5CC' CELLPADDING=0 CELLSPACING=0 BGCOLOR='#ffffff' style='border-collapse: collapse' align='left' valign=top>";
	 var content = content+"<TR><TD ALIGN='center' valign='top' bgcolor='#ffffff' width='"+popwidth+"'>";
	 var content = content+ baby[title];
	 var content = content+"</TD></TR></TABLE>";

	  if(old)
	  {
		alert(msg);
		return;
	  } 

	  yyy=Yoffset; 
	  skin.width=popwidth;

	  if(nav)
	  { 
		skin.document.open();
		skin.document.write(content);
		skin.document.close();
		
	  }
	  if (cEvent) get_mouse(cEvent);
	  
	  if(iex || (netscape=='Netscape'))
	  { 
		pup.innerHTML=content;
		skin.visibility="visible";
	  }  
	
	  if(n_6)
	  { 
		skin.visibility="visible";
		document.getElementById("pup").innerHTML=content;
		skin.visibility="visible";
	  }
}
	
	
	// park content box
	function kill()
	{
	  if(!old)
	  {
		yyy=-1000;
		skin.visibility="hidden";
		skin.width=0;
	  }
	  if (POP_OPEN){
		 if(nav)document.releaseEvents(Event.MOUSEMOVE);
		 if(n_6) document.removeEventListener("mousemove",get_mouse,true);
		 if(nav||iex)document.onmousemove="";
 	     POP_OPEN=false;
	  }
	// document.f.sel_instructor.style.display='';
	}


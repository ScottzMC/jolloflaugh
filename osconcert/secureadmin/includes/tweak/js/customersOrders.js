function check_key(e){
     var kc=0;
   	  if (window.event)
	    kc=window.event.keyCode;
	  else if (e)
	 	kc=e.which;
	  else
		kc=0;
	  if(kc==13) doOrderSearch('');
   }

function removeSearchValue() {
	if(document.getElementById('psearch')) document.getElementById('psearch').value='';	 
}	
 function doCustomActions(type,func) {
        eval(func+'()');
    }
function orderValidate(){
		var form=document.forms["orderSubmit"];
		var lastError='';
		var element,icnt,n,tempCnt,value,key,key1,lang,found;
		var statusId;
		var statustext;
		if(form.status){
			statusId=form.status.options[form.status.selectedIndex].value;
			statustext=form.status.options[form.status.selectedIndex].text;
		}	
		if(statusId != page.template['TEXT_DELIVERED_ID'])
			document.getElementById('shipping_date').value='';		
		if(statusId== page.template['TEXT_DELIVERED_ID']){
			if(form.shipping_date.value=="") {
				lastError=page.template['ERROR_EMPTY_SHIPPING_DATE'];
			}
		 }
		if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	
	function refundValidate(){
			var frm=document.refund;
		var form=document.forms["refund"];
		var total_amount=form.total_amt.value;
		var lastError='';
		
		for(i=0;i<frm.chk_type.length;i++){
			if(frm.chk_type[i].checked){
				value=frm.chk_type[i].value;
				}
		}  
		if(value=='')error_message+='* <?php echo ERR_CHOICE_EMPTY;?>\n';
		else if(value!='F'){
			for(i=0;i<frm.chk_choice.length;i++){
				if(frm.chk_choice[i].checked){
					choice_value=frm.chk_choice[i].value;
				}	
			}
			if(frm.txt_amount.value=='' || isNaN(frm.txt_amount.value) || (frm.txt_amount.value<=0))
				lastError+="* "+page.template["ERR_AMOUNT_EMPTY"]+"\n"
			else{
				 if(choice_value=='A'){ 
					if(parseFloat(frm.txt_amount.value)>=parseFloat(total_amount))
						lastError+="* "+page.template["ERR_REFUND_AMOUNT"]+"\n"
				} else {
					if(parseFloat(frm.txt_amount.value)>=parseFloat(100))
						lastError+="* "+page.template["ERR_PERCENTAGE_VALUE"]+"\n"
				}
				
			}	
		}
	
	if(document.getElementById('is_restock').checked){
			var sid=document.refund.opid.value;
			var sarray=Array();
			var sel_id=false;
			sarray=sid.split(',');
			for(i=0;i<sarray.length;i++){
				if(document.getElementById('chk_restock' + sarray[i]).checked)	
					sel_id=true;
			}
			if(!sel_id)
			lastError+="* "+page.template["ERROR_EMPTY_RESTOCK_CHOICE"]+"\n"
		}
		

		if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function doUpdateCustomerOrder(){
		var data='',temp,element,icnt,n;
		var form=document.forms["orderSubmit"];
		var deElements=Array();
		var icnt=0;
		if(form["status_undisplay"]) data+="status="+form["status_undisplay"].value+"&";
		if(form["comments"]) data+="comments="+encodeURIComponent(form["comments"].value)+"&";
		if(form["order_id"]) data+="oID="+form["order_id"].value+"&";
		if(form["status"]) data+="status="+form["status"].value+"&";
		if(form["shipping_date"]) data+="shipping_date="+form["shipping_date"].value+"&";
		if(form["notify"].checked)
			data+="notify=on&";
		if(form["notify_comments"].checked)
			data+="notify_comments=on&";
		command=page.link+"?AJX_CMD=UpdateCustomerOrder&RQ=A&" + new Date().getTime();
		// define the method to handle server responses
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	//
	 function do_chk_ords(obj) { 
	 var selected_array=new Array();
		var selected_service_array=new Array();
		var selected_options;
		
		selected_options=document.getElementById('selected_orders').value;
		if(selected_options!=''){
		selected_service_array=selected_options.split(',');			
		}
		
		if(document.getElementById('selected_orders')){
			if((obj.checked==true) && (inArray(selected_service_array,obj.value)==false)){
				selected_service_array.push(obj.value);
				document.getElementById('selected_orders').value='';
				document.getElementById('selected_orders').value=selected_service_array;
			} else 
			if(obj.checked==false){
				selected_service_array.splice(selected_service_array.indexOf(obj.value),1);
				document.getElementById('selected_orders').value='';
				document.getElementById('selected_orders').value=selected_service_array;
			}
		}
	 
		
	 }
	 function inArray(array,value){
		var i;
		for (i=0; i < array.length; i++) 
		{
			if (array[i] == value) 
			{
			return true;
			}
		}
		return false;
	}
	function do_chk_events(obj) { 
		var selected_array=new Array();
		var selected_service_array=new Array();
		var selected_options;
		
		selected_options=document.getElementById('selected_events').value;
		if(selected_options!=''){
		selected_service_array=selected_options.split(',');			
		}
		
		if(document.getElementById('selected_events')){
			if((obj.checked==true) && (inArray(selected_service_array,obj.value)==false)){
				selected_service_array.push(obj.value);
				document.getElementById('selected_events').value='';
				document.getElementById('selected_events').value=selected_service_array;
			} else 
			if(obj.checked==false){
				selected_service_array.splice(selected_service_array.indexOf(obj.value),1);
				document.getElementById('selected_events').value='';
				document.getElementById('selected_events').value=selected_service_array;
			}
		}
	 }

	 function do_active() { 
	 	var stock_id=document.refund.opid.value;
		//alert("stock id "+stock_id);
		
		var sarray=Array();
		
		
	 	if(document.getElementById('is_restock').checked){ 
			sarray=stock_id.split(',');
			for(i=0;i<sarray.length;i++){
				document.getElementById('chk_restock'+ sarray[i]).disabled=false;
			}
		} else {
			sarray=stock_id.split(',');
			for(i=0;i<sarray.length;i++){
				document.getElementById('chk_restock'+ sarray[i]).checked=false;
				document.getElementById('chk_restock'+ sarray[i]).disabled=true;
			}
		}
		
	 }
	function do_action(){ 
		var frm=document.refund;
		for(i=0;i<frm.chk_type.length;i++){
			if(frm.chk_type[i].checked){
				if(frm.chk_type[i].value=='P')
					document.getElementById('choice').style.display="";
					
					
				else{
					document.refund.txt_amount.value='';
				    document.getElementById('choice').style.display="none"	;
				}
			}
		}		
	}
	function change_amount_type(amount_type){
	   var amount_type_span=document.getElementById("amount_type");
	   if(amount_type_span){
		 if(amount_type=='A')
			amount_type_span.innerHTML="&nbsp;$";
		 else
			amount_type_span.innerHTML="&nbsp;%";
	   }
	}
	function doRefundOrder(action){
		var data='',temp,element,icnt,n;
		var form=document.forms["orderSubmit"];
		
		var deElements=Array();
		var icnt=0;
			var frm=document.refund;
		if(form){
			if(form["comments"]) data+="comments="+encodeURIComponent(form["comments"].value)+"&";
			if(form["order_id"]) data+="oID="+form["order_id"].value+"&";
		} else{
			
		var total_amount1=frm.total_amt.value;
		for(i=0;i<frm.chk_type.length;i++){
			if(frm.chk_type[i].checked){
				if(frm.chk_type[i].value=='P')
				{
				data+="chk_type=P"+"&";
				 data+="txt_amount="+document.getElementById("txt_amount").value+"&";
				}
				else if(frm.chk_type[i].value=='F')
				{
				data+="chk_type=F"+"&";
				 data+="txt_amount="+total_amount1+"&";
			
				}
			}
		}
				
				for(i=0;i<frm.chk_choice.length;i++){
			if(frm.chk_choice[i].checked){
				if(frm.chk_choice[i].value=='A')
				data+="chk_choice=A"+"&";
				else if(frm.chk_choice[i].value=='%')
				data+="chk_choice=%"+"&";
			}
		}
			
			
			
			// if(frm.chk_advise.checked)
				// data+="chk_advise=Y"+"&";
				// else 
				// data+="chk_advise=N"+"&";
			
			// if(frm.is_restock.checked)
				// data+="is_restock=Y"+"&";
				// else 
				// data+="is_restock=N"+"&";
			
			
			var opid1=frm.opid.value;
			
			
			if(document.getElementById("opid")) data+="opid="+opid1+"&";
			if(document.getElementById("total_amt")) data+="total_amt="+total_amount1+"&";
			if(document.getElementById("refund_comments")) data+="refund_comments="+encodeURIComponent(document.getElementById("refund_comments").value)+"&";
			
			
			var stock_id1=document.refund.opid.value;
		
		var sarray1=Array();
		
		
	 	if(document.getElementById('is_restock').checked){ 
			sarray1=stock_id1.split(',');
			for(i=0;i<sarray1.length;i++){
				if(document.getElementById('chk_restock'+ sarray1[i]).checked && (frm["selected_orders"].value=='Y'))data+="chk_restock"+ sarray1[i]+"="+frm["selected_orders"].value+"&";
				if(document.getElementById('chk_restock'+ sarray1[i]).checked && (frm["selected_events"].value=='Y'))data+="chk_restock"+ sarray1[i]+"="+frm["selected_events"].value+"&";
			}
		} 
		
		
		
		
		
		
		
		
		
		}
		command=page.link+"?AJX_CMD="+action.get+"&command=save&oID="+action.id+"&RQ=A&" + new Date().getTime();
		// define the method to handle server responses
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	function change_status(mode){ 
		var statusId=document.orderSubmit.status.options[document.orderSubmit.status.selectedIndex].value;
		var statustext=document.orderSubmit.status.options[document.orderSubmit.status.selectedIndex].text;
		if(statusId == page.template['TEXT_DELIVERED_ID']){
			document.getElementById('shipping').style.display='';
		}
		else {
			document.getElementById('shipping').style.display='none';
		}	
		if(mode!="") {
			if(mode=='1')
				document.orderSubmit.comments.value="";
		}	
	}
	function doFilterOrders(mode)
	{
			var value=strTrim(document.getElementById("filter_status").value);
             var search=strTrim(document.getElementById("psearch").value);
           
			
			doPageAction({'id':-1,'type':'custord','get':'GetCustomersOrders','result':doTotalResult,params:'filter='+value+'&search='+search,'message':page.template["INFO_LOADING_DATA"]});
			page.filterMode=true;
	}

	function doOrderSearch(mode)
    {

        if (mode=="reset")
        {
            document.getElementById("psearch").value='';
            document.getElementById("filter_status").value='';
			document.getElementById("filter_search").value='';

            page.searchMode=false;
            doPageAction({'id':-1,'type':'custord','get':'GetCustomersOrders','result':doTotalResult,'message':page.template["INFO_SEARCHING_DATA"]});
        }
	else if(mode=='customer_mainpage_redirect')
	{
	search_word=document.getElementById('search_word').value

	location.href='search_links.php?return=csl&search_link='+search_word;
	}

        else
        {
            var value=strTrim(document.getElementById("psearch").value);
            var filter=strTrim(document.getElementById("filter_status").value);
            var filter_search=strTrim(document.getElementById("filter_search").value);
            
            if(value=='')
            {
                doPageSearch({'id':-1,'type':'custord','get':'GetCustomersOrders','result':doTotalResult,params:'search='+value,filter:'filter='+filter,filter_search:'filter_search='+filter_search,'message':page.template["INFO_SEARCHING_DATA"]});
                page.searchMode=true;
            }
            else
            {
                //alert(value);
                doPageSearch({'id':-1,'type':'custord','get':'GetCustomersOrders','result':doTotalResult,params:'search='+value,filter:'filter='+filter,filter_search:'filter_search='+filter_search,'message':page.template["INFO_SEARCHING_DATA"]});
                page.searchMode=true;
            }
        }
	}

    function doPageSearch(action)
    {
       

		//if (page.locked || (action.get!='Search' && page.searchMode)) return;
		if (action.closePrev && !closePreviousOpened(action)) return;
		checkMessageDisplay(action);
		page.lastAction=action;
      
		do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params+'&'+action.filter+'&'+action.filter_search);

	}
function checkboxlimit(checkgroup, limit){
	var checkgroup=checkgroup
	var limit=limit
	for (var i=0; i<checkgroup.length; i++){
		checkgroup[i].onclick=function(){
		var checkedcount=0
		for (var i=0; i<checkgroup.length; i++)
			checkedcount+=(checkgroup[i].checked)? 1 : 0
		if (checkedcount>limit){
			alert("You can only select a maximum of "+limit+" orders")
			this.checked=false
			}
		}
	}
}

// JavaScript Document

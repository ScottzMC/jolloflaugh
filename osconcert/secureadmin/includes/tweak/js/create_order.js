	/*var iex=(document.all);//ie
	var na6=(window.sidebar);//netscape 6.2
	if(na6) document.select_customer.customer_text.addEventListener("keyup",selectList,true);
	if(iex) document.select_customer.customer_text.onkeyup=selectList;
	if(na6) document.select_customer.customer_text.addEventListener("keydown",scrollList,true);
	if(iex) document.select_customer.customer_text.onkeydown=scrollList;*/
	var intervalId;
	var sessions=new Array();
	var chk_var='';
function showPanelContent_check(action)
{
	switch(action.id)
	{
		case 'SELECTCUSTOMER':
		break;
		case 'SHOPPINGCART':
			if((!page.cartdata['customer_id'] || page.cartdata['customer_id']<=0) && str_trim(document.getElementById('cOrderSHOPPINGCARTview').innerHTML)=="")
			{
				alert("Select Customer first");
				return;
			}
           // page.cOrder_step
		break;
		case 'BILLINGSHIPPING':
			if((!page.cartdata['cart_count'] || page.cartdata['cart_count']<=0) && str_trim(document.getElementById('cOrderBILLINGSHIPPINGview').innerHTML)=="")
			{
				alert('Please Checkout the shopping cart');
				return;
			}
		break;
		case 'SHIPPING':
			if((page.cartdata['sent_to']<=0 || page.cartdata['bill_to']<=0) && str_trim(document.getElementById('cOrderSHIPPINGview').innerHTML)=="")
			{
				alert('Select shipping and billing address');
				return;
			}

		break;
		case 'PAYMENT':
			if(!page.cartdata['shipping'] && str_trim(document.getElementById('cOrderPAYMENTview').innerHTML)=="")
			{
				alert('Select any Shipping method');
				return;
			}
		break;
		case 'CONFIRM':
			if(!page.cartdata['payment'] && str_trim(document.getElementById('cOrderCONFIRMview').innerHTML)=="")
			{
				alert('Select any Payment method');
				return;
			}
		break;
	}
	showPanelContent(action);
}
function do_new_item(ele)
{
    doclosenew();
    document.getElementById(ele+'newitem-1').style.display='';
	if(ele=='P')
	{
		getfunc='ProductNew';
		disp_msg=page.template['TEXT_NEW_PRODUCT'];
    }
	else if(ele=='E')
	{
		getfunc='EventNew';
		disp_msg=page.template['TEXT_NEW_EVENT'];
    }
	else if(ele=='S')
	{
		getfunc='SubscriptionNew';
		disp_msg=page.template['TEXT_NEW_SUBSCRIPTION'];
    }
	else if(ele=='V')
	{
		getfunc='ServiceNew';
		disp_msg=page.template['TEXT_NEW_SERVICE'];
    }
	eval("doSimpleAction({'id':'" + ele + "newitem-1','get':'" + getfunc + "','result':doDisplayOrderResult,'style':'boxLevel1','type':'item','params':'','message':'" +disp_msg+ "'})");
}
function do_page_fetch(cmd)
{
	switch(cmd)
	{
		case "new_product_step2":
			add_product_products_id=document.new_product.add_product_products_id.value;
			eval("doSimpleAction({'id':'new_product_step2','get':'NewProductStep2','result':doDisplayOrderResult,'style':'boxRow','type':'item','params':'add_product_products_id=" + add_product_products_id + "','message':'"+ page.template['TEXT_LOADING'] +"'})");
			break;
		case "new_event_step2":
			event_id=document.select_session.events_name.value;
			param='&';
			if(document.select_session.overbook_reserve)
				if(document.select_session.overbook_reserve.checked)
					param+="overbook_reserve=1&"
			if(document.select_session.override_reserve)
				if(document.select_session.override_reserve.checked)
					param+="override_reserve=1&"
			eval("doSimpleAction({'id':'new_event_step2','get':'NewEventStep2','result':doDisplayOrderResult,'style':'boxRow','type':'item','params':'event_id=" + event_id +param + "','message':'"+ page.template['TEXT_LOADING'] +"'})");
			break;
		case "new_event_step3":
			event_id=document.select_session.events_name.value;
			if(document.select_session.no_attendees){
				var sel_attendees=document.select_session.no_attendees.value;
				if(page.max_attendees>0 && ((page.max_attendees<sel_attendees) || (page.max_attendees>sel_attendees)) ) {
				   alert("Cart already has an event with " + page.max_attendees + " attendees. Number of Attendees for this event must be equal to " +  page.max_attendees);
				   return;
				 }
			}
			param='&';
			if(document.select_session.overbook_reserve)
				if(document.select_session.overbook_reserve.checked)
					param+="overbook_reserve=1&"
			if(document.select_session.override_reserve)
				if(document.select_session.override_reserve.checked)
					param+="override_reserve=1&"

			if (document.getElementById("event_process_show2"))	document.getElementById("event_process_show2").style.display='';
			param+="show_period=1&no_attendees=" + document.select_session.no_attendees.value +'&';
			eval("doSimpleAction({'id':'new_event_step2','get':'NewEventStep2','result':doDisplayOrderResult,'style':'boxRow','type':'item','params':'event_id=" + event_id +param + "','message':'"+ page.template['TEXT_LOADING'] +"'})");
			break;
		case "show_attendees":
			var frm =document.select_session;
			var att=frm.elements["sessions_id[]"];
			var events_id = frm.events_id.value;
			var type = frm.session_type.value;
			var sessions_ids = '';
			for(icnt=0;icnt<att.length;icnt++){
				if(att[icnt].checked){
					sessions_ids += att[icnt].value + ',';
				}
			}
			if(frm.no_attendees.options[document.select_session.no_attendees.selectedIndex].value>1){
                if(validateeventForm(document.forms['select_session']))
                    eval("doSimpleAction({'id':'show_attendees','get':'ShowAttendees','result':doDisplayOrderResult,'style':'boxRow','type':'item','params':'no_attendees=" + document.select_session.no_attendees.options[document.select_session.no_attendees.selectedIndex].value  + "','message':'"+ page.template['TEXT_LOADING'] +"'})");
			}
			else
				do_page_fetch("event_submit");
			break;
		case "event_submit":
			if(validateeventForm(document.forms['select_session']))
			{
				submitform=true;
				if(document.select_session.no_attendees.value>1)
					if(check_attendee_form('select_session'))
						submitform=true;
					else
						submitform=false;
				if(submitform)
				{
					eval("doUpdateAction({'id':'new','get':'EventUpdate','imgUpdate':false,'type':'item','style':'boxRow','uptForm':'select_session','customUpdate':doEventAdd,'result':doStep,'message1':page.template['UPDATE_DATA']})");
				}
			}
			break;
		case "new_subscription_step2":
			subscriptions_id=document.add_new_subscription.subscriptions_id.value;
			eval("doSimpleAction({'id':'new_subscription_step2','get':'SubscriptionNewStep2','result':doDisplayOrderResult,'style':'boxRow','type':'item','params':'subscriptions_id=" + subscriptions_id + "','message':'"+ page.template['TEXT_LOADING'] +"'})");
			break;
		case "subscription_submit":
			eval("doUpdateAction({'id':'new','get':'SubsUpdate','imgUpdate':false,'type':'item','style':'boxRow','uptForm':'add_new_subscription','customUpdate':doSubscriptionAdd,'result':doStep,'message1':page.template['UPDATE_DATA']})");
			break;
		case "new_service_step2":
			service_id=document.select_resource.service_name.value;
			eval("doSimpleAction({'id':'new_service_step2','get':'ServiceNewStep2','result':doDisplayOrderResult,'style':'boxRow','type':'item','params':'service_id=" + service_id + "','message':'"+ page.template['TEXT_LOADING'] +"'})");
			break;
		case "new_service_step3":
			service_id=document.select_resource.service_name.value;
			action={'id':'new_service_step3','get':'ServiceNewStep3','result':doDisplayOrderResult,'style':'boxRow','type':'item','params':'service_id=' + service_id + '','message':'' + page.template["TEXT_LOADING"] + ''};
			checkMessageDisplay(action);
			page.lastAction=action;
			do_post_command('select_resource',page.link+'?AJX_CMD='+action.get+'&'+action.params);
			break;
		case "service_submit":
			eval("doUpdateAction({'id':'new','get':'serviceUpdate','validate':ValidateserviceForm,'imgUpdate':false,'type':'item','style':'boxRow','uptForm':'select_resource','customUpdate':doServiceAdd,'result':doStep,'message1':page.template['UPDATE_DATA']})");
			break;
		case "update_cart":
			eval("doUpdateAction({'id':'new','get':'Update','imgUpdate':false,'type':'item','style':'boxRow','uptForm':'cart_quantity_p','customUpdate':doUpdateQuantity,'result':doStep,'message1':page.template['UPDATE_DATA']})");
			break;
		case "product_delete":
			do_delete_action("cart_quantity_p",'P');
			break;
		case "event_delete":
			do_delete_action("cart_quantity_e",'E');
			break;
		case "subscription_delete":
			do_delete_action("cart_quantity_s",'S');
			break;
		case "service_delete":
			do_delete_action("cart_quantity_v",'V');
			break;
		case "show_payment_address":
			    var disable_shipping=0;
				var pp_result="";
				var manual_option;
				if(document.getElementById('modify_price_prefix')) var modify_price_prefix=document.getElementById("modify_price_prefix").value;
				if(document.getElementById('sign')) var sign=document.getElementById("sign").value;
				if(document.getElementById('purpose')) var purpose=document.getElementById("purpose").value;
				if(document.getElementById('manual_priceadjust_option').checked==true){
					manual_option='Y';
					if (purpose=="")
   					  pp_result="Enter the Purpose";
   					else if (modify_price_prefix=="")
   					  pp_result="Enter the Price Adjustment";
					}
					else {
						manual_option='N';
					}
				if(sign=='+')
				{sign='plus';}
				else {sign='minus';}
				if(document.getElementById('disable_shipping') && document.getElementById('disable_shipping').checked==true)
					disable_shipping=1;
				else
					disable_shipping=0;
				if(!document.getElementById('disable_shipping')) disable_shipping=1;
				if (pp_result!=""){
        		 alert(pp_result);
				}else{
					eval("doSimpleAction({'id':'step3','get':'BillingShipping','result':doStep,'style':'boxRow','type':'item','params':'disable_shipping="+disable_shipping+"&modify_price_prefix="+modify_price_prefix+"&sign="+sign+"&purpose="+purpose+"&manual_option="+manual_option+ "','message':'"+ page.template['TEXT_LOADING'] +"'})");
				}
				break;
			case 'submit_payment_address':
				if(document.getElementById('update_billing_state') && document.getElementById('update_billing_state').value!=''){
					document.getElementById('update_billing_state1').value=='';
				}
				if(document.getElementById('update_billing_name') && document.getElementById('update_billing_name').value==''){
					alert('Customer Name is required');
				}else{
					eval("doUpdateAction({'id':'new','get':'BillingShippingSubmit','imgUpdate':false,'type':'item','style':'boxRow','uptForm':'payment_address','result':doStep,'message1':page.template['UPDATE_DATA']})");
				}
				break;
			case "submit_shipping_details":
				eval("doUpdateAction({'id':'new','get':'ShippingSubmit','imgUpdate':false,'type':'item','style':'boxRow','uptForm':'checkout_shipping','result':doStep,'message1':page.template['UPDATE_DATA']})");
				break;
			case "submit_payment_details":
				document.getElementById('show_payment_error').style.display="none";
				eval("doUpdateAction({'id':'new','get':'PaymentSubmit','imgUpdate':false,'type':'item','style':'boxRow','uptForm':'edit_order','result':doStep,'message1':page.template['UPDATE_DATA']})");
				break;
			case "process_order":
				if(document.checkout_confirmation){
					if(document.getElementById('confirm_button')) document.getElementById('confirm_button').style.display="none";
						document.checkout_confirmation.submit();
				}
				else{
					location.href="<?php echo tep_href_link(FILENAME_CHECKOUT_PROCESS_NEW);?>";
				}
				break;

	}
}
function doDisplayOrderResult(result,action){
	if(action.get=='CheckAttribStock')
	{
		if (result=="1") toggleOutStock(true);
		else toggleOutStock(false);
		toggleLoadMsgs("stockProductQuan",1);
		toggleLoadMsgs("stockAttrQuan",1);
		return;
	}
	else if(action.get=='CheckAttendees')
	{
		document.getElementById('partly_full').innerHTML=result;
		if(result==""){
			eval("doSimpleAction({'id':'show_attendees','get':'ShowAttendees','result':doDisplayOrderResult,'style':'boxRow','type':'item','params':'no_attendees=" + document.select_session.no_attendees.options[document.select_session.no_attendees.selectedIndex].value  + "','message':'"+ page.template['TEXT_LOADING'] +"'})");
		}
		return;
	}
	else if(action.get=='FetchCustomersDetails')
	{
		res=result.split('##');
		var val=new Array('','','','','','','','');
		if(res[0]!='') val=res[0].split("^");
	 	if(res[1]!='') fetch_details(val,res[1]);
		return;
	}
	else if(action.get=='ShowState')
	{
		if(action.id=="new_event_step2") //attendeee
		{
			splt=result.split("^");
			valueArr=splt[1].split(",");
			textArr=splt[1].split(",");
			cnt=parseInt(splt[3]);
			 var frm=document.select_session;
			 var frm_attendees=frm.elements["attendee[]"];
			if(frm_attendees){
				if(frm_attendees && frm_attendees.checked) return;
				else if(frm_attendees[cnt]  && frm_attendees[cnt].checked) return;
			}
			if(frm.elements["first_name[]"].length>1){
				state=frm.elements["state[]"][cnt];
				state1=frm.elements["state1[]"][cnt];
			} else{
				state=frm.elements["state[]"];
				state1=frm.elements["state1[]"];
			}
			if(valueArr.length==1) {
				state1.style.display="";
				 state.style.display="none";
			}else {
				state1.style.display="none";
				state.style.display="";
				optionElement=state;
				while(optionElement.options.length>0){
					optionElement.remove(optionElement.options.length - 1);
				}
				for (icnt=0;icnt<textArr.length;icnt++){
				  var option = document.createElement('option');
				  option.text = textArr[icnt];
				  option.value = valueArr[icnt];
				  try {
					optionElement.add(option, null); // standards compliant; doesn't work in IE
				  }
				  catch(ex) {
					optionElement.add(option); // IE only
				  }
				}
			}
		}
		else
		{
			var textArr=new Array();
			var valueArr=new Array();
			splt=result.split("^");
			valueArr=splt[1].split(",");
			textArr=splt[1].split(",");
			sel_zone=splt[2];
			var zone_list=document.forms[page.formName].elements["entry_zone_id"];
			var state=document.forms[page.formName].elements["entry_state"];
			if(state) state.value="";
			if(valueArr.length==1 && textArr=='') {
				if(state) state.style.display="";
				if(zone_list) zone_list.style.display="none";
			}else {
				if(state) state.style.display="none";
				if(zone_list){
					zone_list.style.display="";
					state.value="";
				}
				optionElement=zone_list;
				// clear the previous details
				while(optionElement.options.length>0){
					optionElement.remove(optionElement.options.length - 1);
				}
				//create the new details
				for (icnt=0;icnt<textArr.length;icnt++){
					var option = document.createElement('option');
					option.text = textArr[icnt];
					option.value = valueArr[icnt];
					try {
						optionElement.add(option, null); // standards compliant; doesn't work in IE
					}
					catch(ex) {
						optionElement.add(option); // IE only
					}
				}
			}
		}
		return;
	}
	else if(action.get=='ShowStateBilling')
	{
		var textArr=new Array();
		var valueArr=new Array();
		splt=result.split("^");
		valueArr=splt[1].split(",");
		textArr=splt[1].split(",");
		sel_zone=splt[2];
		type=splt[3];
		if(type=='B' && document.getElementById("update_billing_state")) var sel=document.getElementById("update_billing_state");
		else if(type=='D' &&  document.getElementById("update_delivery_state")) var sel=document.getElementById("update_delivery_state");
		else if(type=='C' &&  document.getElementById("update_customer_state")) var sel=document.getElementById("update_customer_state");
		if(type=='B' &&  document.getElementById("update_billing_state1")) state1=document.getElementById("update_billing_state1");
		else if(type=='D' &&  document.getElementById("update_delivery_state1")) state1=document.getElementById("update_delivery_state1");
		else if(type=='C' &&  document.getElementById("update_customer_state1")) state1=document.getElementById("update_customer_state1");
		if(valueArr.length==1) {
			if(state1) state1.style.display="";
			if(sel)	sel.style.display="none";
		}else if(valueArr.length>1){
			if(state1){
				state1.value="";
				state1.style.display="none";
			}
			sel.style.display="";
			optionElement=sel;
			// clear the previous details
			while(optionElement.options.length>0){
				optionElement.remove(optionElement.options.length - 1);
			}
			//create the new details
			for (icnt=0;icnt<textArr.length;icnt++){
			  var option = document.createElement('option');
			  option.text = textArr[icnt];
			  option.value = valueArr[icnt];
			  try {
				optionElement.add(option, null); // standards compliant; doesn't work in IE
			  }
			  catch(ex) {
				optionElement.add(option); // IE only
			  }
		}
		if(typeof(default_zone) != "undefined")
		for(i=0;i<optionElement.options.length;i++){
			if(optionElement.options[i].value==default_zone)
				optionElement.options[i].selected = true;
		}
		if(document.getElementById("state1") && typeof(default_country) != "undefined")
		if(default_country!=document.getElementById("country"))
			document.getElementById("state1").value="";
	}
	return;
	}
	var result_splt=result.split("@sep@");
	var element=document.getElementById(action.id);
	element.style.display="";
	element.innerHTML=result_splt[0];

	if (result_splt[1] && result_splt[1]!='') doJASON(result_splt[1],page.lastAction);
	if(action.get=='NewProductStep2')
	{
		eval('page.products.id='+page.products.id);
		eval('page.products.stock='+page.products.stock);
		eval('page.products.priceBreaks='+page.products.priceBreaks);
		eval('page.products.valid='+page.products.valid);
		eval('page.products.priceAttr='+page.products.priceAttr);
		eval('page.products.saleMaker='+page.products.saleMaker);
		//eval('page.products.currency='+page.products.currency);
		setQuantity('',1);
		if(!page.products.priceBreaks.enabled) {
			checkStock('');
		}
	}
	else if(action.get=="NewEventStep2")
	{
		temp=page.dates_array
		temp_arr=temp.split('^');
		sessions=Array();
		for (cnt=0;cnt<temp_arr.length-1;cnt++) {
			temp_arr2=temp_arr[cnt].split('?');
			sessions[cnt]=Array();
			sessions[cnt][0]=temp_arr2[0];
			sessions[cnt][1]=temp_arr2[1];
			sessions[cnt][2]=temp_arr2[2];
			sessions[cnt][3]=temp_arr2[3];
		}
	}
	else if(action.get=="ShowAttendees")
	{
		document.getElementById('new_event_step1').style.display='none';
		document.getElementById('new_event_step2').style.display='none';
		document.getElementById('itemnew_event_step2mupdate').style.display="";
	}
	else if(action.get=="ServiceNewStep2")
	{
		temp=page.nb_dates;
		temp_arr=temp.split('?');
		for (cnt=0;cnt<temp_arr.length-1;cnt++)
			nb_dates[cnt]=temp_arr[cnt];
		add_start_times();
		add_end_times();
	}
	else if(action.get=="ServiceNewStep3")
	{
		temp=page.attributes_array;
		temp_arr=temp.split('?');
		for (cnt=0;cnt<temp_arr.length-1;cnt++)
			attribute_array[cnt]=temp_arr[cnt];
	    if(page.serror!=""){
			document.getElementById('itemnew_service_step3mupdate').style.display="none";
		}
	}
	//changeBoxStyle(action,'Select');
}

function doCustDisplayResult(data)
{
	var result_splt=data.split("@sep@");
	if (result_splt[1] && result_splt[1]!='') doJASON(result_splt[1],page.lastAction);
	document.getElementById("cOrdercustomer_details").innerHTML=result_splt[0];
}

function doStep(data)
{
	var result_splt=data.split("@sep@");
	if (result_splt[1] && result_splt[1]!='') doJASON(result_splt[1],page.lastAction);
	if(page.cOrder_step>2 && page.cOrder_step<5)
	{
		if((page.is_shipping=='0'))
		{
			document.getElementById("cOrderSHIPPINGmenu").style.display="none";
		}
		else
		{
			document.getElementById("cOrderSHIPPINGmenu").style.display="";
		}
	}
	switch(page.cOrder_step)
	{
		case 1:  //select customer
			res=result_splt[0];
			res_t=res.split('ERROR:||');
			if(res_t[1])
			  document.getElementById("customer_err_text").innerHTML=res_t[1];
			else
			 doCustDisplayResult(res);
		break;
		case 2:	//shopping cart
			document.getElementById("cOrderSHOPPINGCARTview").innerHTML=result_splt[0];
			eval("showPanelContent({'id':'SHOPPINGCART','className':'boxRow','type':'cOrder'})");
			page.lastAction=false;
			page.locked=false;
		break;
		case 3:	//billingshipping
			document.getElementById("cOrderBILLINGSHIPPINGview").innerHTML=result_splt[0];
			eval("showPanelContent({'id':'BILLINGSHIPPING','className':'boxRow','type':'cOrder'})");
			page.lastAction=false;
			page.locked=false;
		break;
		case 4:	//shipping
			document.getElementById("cOrderSHIPPINGview").innerHTML=result_splt[0];
			eval("showPanelContent({'id':'SHIPPING','className':'boxRow','type':'cOrder'})");
			page.lastAction=false;
			page.locked=false;
		break;
		case 5:	//payment
			res=result_splt[0];
			if(res.indexOf("payment_error||")>=0)
			{
				cmd_splt=res.split('||');
				document.getElementById('show_payment_error').style.display="";
				document.getElementById('payment_error_text').innerHTML=cmd_splt[1];
				document.getElementById('payment_error_text').style.display='';
				return;
			}
			else if(res.indexOf("coupon_details||")>=0)
			{
				cmd_splt=res.split('||');
				document.getElementById("credit_result").style.display="";
				document.getElementById("credit_result").innerHTML=cmd_splt[1];
				if(document.getElementById('payment_error_text')){
					document.getElementById('payment_error_text').style.display="none";
				 	document.getElementById('show_payment_error').style.display="none";
				 	document.getElementById('credit').style.display="none";
				 }
				 return;
			}
			document.getElementById("cOrderPAYMENTview").innerHTML=result_splt[0];
			eval("showPanelContent({'id':'PAYMENT','className':'boxRow','type':'cOrder'})");
			page.lastAction=false;
			page.locked=false;
		break;
		case 6:	//confirmation
			document.getElementById("cOrderCONFIRMview").innerHTML=result_splt[0];
			eval("showPanelContent({'id':'CONFIRM','className':'boxRow','type':'cOrder'})");
			page.lastAction=false;
			page.locked=false;
		break;
	}
}
function checkdropDown(){
	if (currentField==""){
		clearInterval(intervalId);
		hideList();
		intervalId="";
	}
}
function toggleText(mode){
	if (mode==1) {
		currentField="Text";
		showList();
		if (intervalId) clearInterval(intervalId);
		intervalId=setInterval("checkdropDown()",300);
	} else {
		currentField="";
	}
}
function toggleList(mode){
	if (mode==1){
		currentField="drop";
	} else {
		currentField="";
	}
}
function showList(){
	var listbox=document.getElementById("customer_list");
	var textbox=document.select_customer.customer_text;
	listbox.style.visibility="visible";
	listbox.style.display="";
	var left = getPageOffsetLeft(textbox);
	var top =getPageOffsetTop(textbox);

	if(window.scrollMaxX >0)listbox.style.left=left;
	else listbox.style.left=left;
	listbox.style.top=top+17;

}
function hideList(){
	var listbox=document.getElementById("customer_list");
	var textbox=document.select_customer.customer_text;
	setListboxText(1);
	listbox.style.display="none";
}
function scrollList(e){
	var listbox=document.select_customer.customers_id;

	if (iex) keyCode=window.event.keyCode;
	if (na6) keyCode=e.which;

	if (keyCode==40 || keyCode==38){
		if (keyCode==40 && listbox.selectedIndex>=0 && listbox.selectedIndex<listbox.options.length-1){
			listbox.selectedIndex+=1;
		} else if (keyCode==38 && listbox.selectedIndex>0 && listbox.selectedIndex<=listbox.options.length-1){
			listbox.selectedIndex-=1;
		}

		setListboxText(0);
		if (iex) window.event.returnValue=0;
		if (na6) e.returnValue=0;
		return;

	}
}

function selectList(e){
	var listbox=document.select_customer.customers_id;
	var search_word=document.select_customer.customer_text.value;
	var text_length;
	var found=-1;
	var listbox_word;
	var eventObject;
	var keyCode=0;
	var fromPos=0;
	var toPos=0;

	if (!listbox.options || listbox.options.length<=0) return;

	if (search_word!="") {
		search_word=search_word.toLowerCase();
		found=binaryFind(listbox,search_word,0,listbox.options.length-1);
		if (found>=0)
			listbox.selectedIndex=found;
	}

}
function setListboxText(mode){
	var listbox=document.select_customer.customers_id;
	if (!listbox.options || listbox.options.length<=0 || listbox.selectedIndex<=-1) return;
	var textbox_name=document.select_customer.customer_text;
	if (textbox_name.value=="" && mode==1) return "";
	textbox_name.value=listbox.options[listbox.selectedIndex].innerHTML;
	//merge
	if((document.getElementById("img_process1") && document.getElementById("ajx_load_content_img")))
		document.getElementById("img_process1").innerHTML=document.getElementById("ajx_load_content_img").innerHTML;
	//merge
	doSimpleAction({'id':'customer_details','get':'Customer_details','result':doCustDisplayResult,'style':'boxRow','type':'cOrder','params':'cID=' + listbox.options[listbox.selectedIndex].value + '','message':'' + page.template['TEXT_LOADING'] + ''});
	//command="<?php echo tep_href_link(FILENAME_CREATE_ORDER_NEW,'command=show_customer_details');?>&cID="+listbox.options[listbox.selectedIndex].value;
	//do_get_command(command);
}
function binaryFind(listBox,fWord,sPos,ePos){
	var listElement;
	var tWord;
	var mPos;
	if (sPos>ePos) return -1;
	mPos=sPos+Math.ceil((ePos-sPos)/2);

	if (mPos<0 || mPos>=listBox.options.length) return -1;
	listElement=listBox.options[mPos].innerHTML.toLowerCase();
	tWord=listElement.substr(0,fWord.length);

	if (tWord==fWord) return mPos;
	if (mPos<=0 || mPos>=listBox.options.length) return -1;

	if (fWord<tWord) return binaryFind(listBox,fWord,mPos+1,ePos);
	else return binaryFind(listBox,fWord,sPos,mPos-1);
}
//=======================
	var prev;
	var products_stock='';
	var prevQuantity=1;
	function setQuantity(object,qty){
		var icnt,n;
		if (!document.getElementById("tablePriceBreaks")) return;
		if (object=="") object=document.getElementById("tablePriceBreaks").rows[1];
		object.className="moduleRowSelected";
		document.getElementById("qty").value=qty;
		if(prev && prev!=object)
			prev.className="moduleRow";
			prev=object;

		setTotalPrice();
		if (page.products.priceAttr.enabled){
			for (icnt=0,n=page.products.priceAttr.count;icnt<n;icnt++){
				if (document.getElementById("attrValues["+icnt+"]").selectedIndex>0) {
					price=getAttributePrice(icnt);
					setAttrDisplayPrice(price,icnt);
				}
			}
		}
			checkStock('stockProductQuan');
	}

	function setTotalPrice(){
		var icnt,n,totalPrice,exString='';
		element=document.getElementById("totalProductsPrice");
		if (!element) return;
		if (!page.products["priceCalc"].length) return;
		totalPrice=0;
		exString+="totalPrice=";
		for (icnt=0,n=page.products["priceCalc"].length;icnt<n;icnt++){
			exString+=page.products["priceCalc"][icnt]+"+";
		}
		eval(exString+"0");
		element.innerHTML=formatCurrency(totalPrice);
	}

	function selectAttribute(object,index){
		var price=0;
		if (object.selectedIndex>0) price=getAttributePrice(index);
		setAttrDisplayPrice(price,index);
		setTotalPrice();
		if (object.selectedIndex>0) checkStock("stockAttrQuan");
	}

	function setAttrDisplayPrice(price,index){
		if (!document.getElementById("attrPrice"+index)) return;
		if (price!=0){
			document.getElementById("attrPrice"+index).innerHTML=(price>0?'+':'-')+formatCurrency(Math.abs(price));
		} else {
			document.getElementById("attrPrice"+index).innerHTML="&nbsp;";
		}
	}

	function selectDiscount(id){
		if (page.products["saleMaker"].sales[id]){
			document.getElementById("salemaker_info").innerHTML=page.products["saleMaker"].sales[id].warning;
			document.getElementById("salemaker_info").style.display="block";
		} else {
			document.getElementById("salemaker_info").innerHTML='';
			document.getElementById("salemaker_info").style.display="none";
		}
		setTotalPrice();
	}

	function getProductsPrice(){
		var price=0,quan,id;
		quan=getProductsQuantity();
		if (page.products["priceBreaks"].enabled && quan>1 && page.products["priceBreaks"].prices[quan]){
			price=page.products["priceBreaks"].prices[quan];
		} else if (page.products["saleMaker"] && page.products["saleMaker"].enabled){
			salemaker=document.forms["new_product"].elements["salemaker_id"];
			for (icnt=0,n=salemaker.length;icnt<n;icnt++){
				if (salemaker[icnt].checked) {
					id=salemaker[icnt].value;
					break;
				}
			}
			if (id>0){
				price=page.products["saleMaker"].sales[id].price*quan;
			} else {
				price=parseFloat(document.getElementById("product_price").value)*quan;
			}
		} else {
			price=parseFloat(document.getElementById("product_price").value)*quan;
		}
		return price;
	}

	function getAttributePrice(index){
		var price=0,fromIndex,toIndex,icnt,quan,element,element1;
		if (!page.products.priceAttr.enabled) return price;
		if (index==-1) {
			fromIndex=0;
			toIndex=page.products.priceAttr.count-1;
		} else {
			fromIndex=toIndex=index;
		}
		quan=getProductsQuantity();
		for (icnt=fromIndex;icnt<=toIndex;icnt++){
			element=document.getElementById("attrIds["+icnt+"]");
			element1=document.getElementById("attrValues["+icnt+"]");
			if (!element || !element1) break;
			attrValue=parseInt(element1.value);
			if (attrValue>0) {
				price+=(page.products.priceAttr.prices["op"+element.value][attrValue]*quan);
			}
		}
		return price;
	}

	function getProductsQuantity(){
		var quan=1;
		if (document.getElementById("qty"))
			quan=parseInt(document.getElementById("qty").value);
		if (!quan || isNaN(quan) || quan<=0) quan=1;
		return quan;
	}

	function checkStock(idMsg){
		var ids,command,quan;
		products_stock=document.getElementById('product_stock').value;
		quan=getProductsQuantity();

		if(!page.products.priceBreaks.enabled)
			quanAutoCheck();
		if (quan>products_stock) {
			toggleOutStock(false);
			return;
		}

		if (!page.products.valid) toggleOutStock(true);
		if (!page.products.priceAttr.enabled) return;

		ids=getAttributeValues(true);
		if (ids=="") return;
		eval("doSimpleAction({'id':'temp','get':'CheckAttribStock','result':doDisplayOrderResult,'style':'boxRow','type':'item','params':'products_id="+page.products.id+"&attrib_ids="+ids+"&quan="+quan+"','message':'"+ page.template['TEXT_LOADING'] +"'})");
		toggleLoadMsgs(idMsg,2);
		page.products.valid=false;
	}

	function toggleLoadMsgs(id,mode){
		var element=document.getElementById(id);
		if (!element) return;

		if (mode==2) element.style.display="";
		else {
			element.style.display="none";
		}
	}

	function getAttributeValues(allCheck){
		var icnt,n,ids="";
		for (icnt=0,n=page.products.priceAttr.count;icnt<n;icnt++){
			if (document.getElementById("attrValues["+icnt+"]").value<=0) {
				if (allCheck) return "";
				break;
			}
			if (ids!="") ids+="-";
			ids+=document.getElementById("attrIds["+icnt+"]").value+"{"+document.getElementById("attrValues["+icnt+"]").value+"}";
		}
		return ids;
	}

	function toggleOutStock(flag){
		if (flag){
			page.products.valid=true;
			document.getElementById("outstock").style.display="none";
			document.getElementById("itemnew_product_step2mupdate").style.display="";
		} else{
			page.products.valid=false;
			document.getElementById("outstock").style.display="";
			document.getElementById("itemnew_product_step2mupdate").style.display="none";
		}
	}

	function formatCurrency(Num) {
		var sym_left=page.template['SYMBOL_LEFT'];
		var sym_right=page.template['SYMBOL_RIGHT'];
		if (typeof(Num)=="number") {
			Num=doRound(Num,2);
			Num=""+Num;
		} else {
			Num=""+doRound(parseFloat(Num),2);
		}

		dec = Num.indexOf(".");
		end = ((dec > -1) ? "" + Num.substring(dec,Num.length) : ".00");
		Num = "" + parseInt(Num);

		var temp1 = "";
		var temp2 = "";

		if (end.length == 2) end += "0";
		if (end.length == 1) end += "00";
		if (end == "") end += ".00";

		var count = 0;
		for (var k = Num.length-1; k >= 0; k--) {
		var oneChar = Num.charAt(k);
		if (count == 3 && oneChar!="-") {
		temp1 += ",";
		temp1 += oneChar;
		count = 1;
		continue;
		}
		else {
		temp1 += oneChar;
		count ++;
		   }
		}
		for (var k = temp1.length-1; k >= 0; k--) {
		var oneChar = temp1.charAt(k);
		temp2 += oneChar;
		}
		temp2 = sym_left + temp2 + end + sym_right;
		return temp2;
	}

	function quanAutoCheck(){
		quan=getProductsQuantity();
		if (prevQuantity!=quan){
			page.products.valid=false;
			if (page.products.priceAttr.enabled){
				for (icnt=0,n=page.products.priceAttr.count;icnt<n;icnt++){
					if (document.getElementById("attrValues["+icnt+"]").selectedIndex>0) {
						price=getAttributePrice(icnt);
						setAttrDisplayPrice(price,icnt);
					}
				}
			}
			setTotalPrice();
			prevQuantity=quan;
		}
	}
function numericOnly(e) {
	var iKeyCode;
	if (!e) {
		var e = window.event;
	}
	if (e.keyCode) {
		iKeyCode = e.keyCode;
	} else {
		if (e.which) {
			iKeyCode = e.which;
		}
	}
	switch(iKeyCode) {
		case 8:
		case 9:
		case 37:
		case 38:
		case 39:
		case 40:
		case 46:
		break;
		case 48:
		case 49:
		case 50:
		case 51:
		case 52:
		case 53:
		case 54:
		case 55:
		case 56:
		case 57:
			if (e.shiftKey || e.altKey){
				return false;
			}
		break;
		case 96:
		case 97:
		case 98:
		case 99:
		case 100:
		case 101:
		case 102:
		case 103:
		case 104:
		case 105:
		//return correct numeric from keypad
			return iKeyCode - 48; break;
		case 110:
		//case 190:
		//if you are supporting decimal points
		//return '.'; break;
		default: return false;
	}
}


	function show_manual_price()
	{
	 var tot_value,tot_update_value;
	tot_value=parseFloat(page.shop_price);
	//tot_update_value=parseFloat(shop_updated_price);
	if(document.getElementById('manual_priceadjust_option').checked==true){
			document.getElementById('mpa').style.display='';
			}
		else {
			document.getElementById('mpa').style.display='none';
			//if(document.getElementById("up_tot")) document.getElementById("up_tot").innerHTML=dollarAmount((tot_update_value));
		 if(document.getElementById("net_tot")) document.getElementById("net_tot").innerHTML=dollarAmount((tot_value));
		}

	}

	function modify_price()
	{
	    //shop_price comes from do_result
	    var tot_value,tot_update_value;
		var val=0;
		//if(document.getElementById("modify_price_prefix").value=='' ) return;

		if(document.getElementById("sign"))
		var sign=document.getElementById("sign").value;
		var purpose=document.getElementById("purpose").value;
		var element=parseFloat(document.getElementById("modify_price_prefix").value);
		if(document.getElementById("modify_price_prefix").value=='' ) element=0;
		if(isNaN(element))
		{
			document.getElementById("modify_price_prefix").value='';
			return;
		}
		tot_value=parseFloat(page.shop_price);
		//tot_update_value=parseFloat(shop_updated_price);

		if(sign=='+'){
		   val=tot_value+element;
		  // update_val=tot_update_value+element;
		 }
		else{
		   val=tot_value-element;
		  // update_val=tot_update_value-element;
		   //alert (update_val);
		}
		if(val<=0) {
		alert ('Entered Manual fees is not a valid');
		if(document.getElementById("net_tot")) document.getElementById("net_tot").innerHTML=dollarAmount(tot_value);
		}
		else{
		// if(document.getElementById("up_tot")) document.getElementById("up_tot").innerHTML=dollarAmount(update_val);
		 if(document.getElementById("net_tot")) document.getElementById("net_tot").innerHTML=dollarAmount(val);
		 }
	}
	function  validateproductForm(){
	 var value_array = new Array();
	 var chk = 1;
	 prd_quantity = 0;
	 var error='';
	if (page.products.priceAttr.enabled){
		for(icnt=0,n=page.products.priceAttr.count;icnt<n;icnt++){
			if(document.getElementById("attrValues["+icnt+"]").selectedIndex<=0){
				error+="* " + page.template['ERR_SELECT_ATTRIBUTES'];
				break;
			}
		}
	}

	if(document.getElementById('qty') && (isNaN(parseInt(document.getElementById('qty').value))) || parseInt(document.getElementById('qty').value) <= 0){
		error+="* " + page.template['ERR_TEXT_QUANTITY'];
	}
	if (error!=""){
		alert(error);
		return false;
	}
	//if (!(page.products.valid)) return true;
	return true;
	page.products={0:0};

}
function validateproductquantity(prd_quantity){
   var chk = 1;
   if (document.new_product.qty.value > parseInt(prd_quantity) ) {
	 alert ('Error : Product Quantity Out of Stock');
	 return false;
   }
   return true;
}
function do_delete_action(frmname,type)
{
		var frm=document.forms[frmname];
		var select_box=frm.elements["cart_delete[]"];
		sflag=0;
		if (select_box[0]){
			for (icnt=0;icnt<select_box.length;icnt++){
				if (select_box[icnt].checked){
					sflag=1;
					 break;
				}
			}
		} else {
			if (select_box.checked){
				sflag=1;
			}
		}
		if (sflag==0){
			alert(page.template['ERR_SELECT_DELETE']);
			return;
		}
		eval("doUpdateAction({'id':'new','get':'EventUpdate','imgUpdate':false,'type':'"+type+"','style':'boxRow','uptForm':'" + frmname+"','customUpdate':doDelete,'result':doStep,'message1':page.template['DELETING']})");
}

function doUpdateQuantity()
{
   command=page.link+'?AJX_CMD=Shopping&product_action=update_product';
   do_post_command("cart_quantity_p",command);
}
function doDelete(action)
{
	command=page.link+'?AJX_CMD=Shopping&event_action=update_cart&type=' + action.type;
	do_post_command(action.uptForm,command);
}
function doProductAdd()
{
	command=page.link+'?AJX_CMD=Shopping&product_action=add_product';
	do_post_command("new_product",command);
}
function doEventAdd()
{
	command=page.link+'?AJX_CMD=Shopping&event_action=buy_product';
	do_post_command("select_session",command);
}
function doSubscriptionAdd()
{
	command=page.link+'?AJX_CMD=Shopping&subs_action=add_subscription';
	do_post_command("add_new_subscription",command);
}
function doServiceAdd()
{
	service_id=document.select_resource.service_name.value;
	command=page.link+'?AJX_CMD=Shopping&service_action=buy_service&service_id=' + service_id;
	do_post_command("select_resource",command);
}
function doclosenew()
{
    if(document.getElementById('Pnewitem-1'))
    {
        document.getElementById('Pnewitem-1').innerHTML='';
        document.getElementById('Pnewitem-1').style.display='none';
    }
    if(document.getElementById('Enewitem-1'))
    {
        document.getElementById('Enewitem-1').innerHTML='';
        document.getElementById('Enewitem-1').style.display='none';
    }
    if(document.getElementById('Snewitem-1'))
    {
        document.getElementById('Snewitem-1').innerHTML='';
        document.getElementById('Snewitem-1').style.display='none';
    }
    if(document.getElementById('Vnewitem-1'))
    {
        document.getElementById('Vnewitem-1').innerHTML='';
        document.getElementById('Vnewitem-1').style.display='none';
    }
	page.locked=false;
	page.lastAction=false;
}
//===================
//=====events==================
	function selectEventItem(boxname,id,index){
		var element=document.getElementById(boxname+id);
		var field=document.select_session.elements[boxname+"_index"];
		field.value=index;
		field=document.select_session.elements[boxname];
		if (field.value!=''){
			document.getElementById(boxname+field.value).style.backgroundColor="#FFFFFF";
		}
		field.value=id;
		element.style.backgroundColor="#D8EDFB";
	}
	function selectSessionItem(boxname,id,index){
		var element=document.getElementById(boxname+id);
		var session_type=document.select_session.session_type.value;
		var bgcolor="#FFFFFF";
		var pindex=0;
		var index_field;

		field=document.select_session.elements[boxname+"_index"];
		if (field.value!="") pindex=parseInt(field.value);
		field.value=index;
		if (session_type!="S")
			field=document.select_session.elements[boxname+"_field"];
		else
			field=document.select_session.elements[boxname+"[]"];

		if (field.value!=''){
			if (sessions[pindex][3]==0) bgcolor="#F5F5F5";
			document.getElementById(boxname+field.value).style.backgroundColor=bgcolor;

		}
		element.style.backgroundColor="#D8EDFB";
		field.value=id;
	}
	function setElementPos(){
		var boxnames=Array("events_name","sessions_id");
		var ids=Array("<?php echo $events_id;?>","<?php echo $sessions_id;?>");
		var box;
		var element;
		for (icnt=0;icnt<boxnames.length;icnt++){
			box=document.getElementById(boxnames[icnt]+"_box");
			element=document.getElementById(boxnames[icnt]+ids[icnt]);
			if (box!=null && element!=null)
				box.scrollTop=element.offsetTop;
		}
	}

	function sel_attendees(){
	  var val="<?php echo sizeof($edit_cart->attendees);?>";
	  var no_atten=document.select_session.no_attendees.value;
	  if(val>0){
	   if(val!=no_atten){
	    alert("Already has an event with " + val + " attendees." + " " + "Number of Attendees for this event must be equal to" + " " + no_atten );
		return false;
	   }else {
	    return true;
	   }
	  }else {
	   return true;
	  }
	}

		var choiceAmount1;
		var choiceAmount2;
		var choiceDays1;
		var choiceDays2;
		var selectedSession=0;
		var validIndexes=Array();
		var auto_select=false;

		function displayCurrency(num){
			var commaDelimiter=page.template['COMMA_DELIMITER'];
			var symbolLeft=page.template['SYMBOL_LEFT'];
			var symbolRight=page.template['SYMBOL_RIGHT'];
			var output=FormatNumber(new String(num),2,commaDelimiter,".");
			return symbolLeft+output+symbolRight;
		}
		function displaySessionsCountFixed(sIndex){
			var row=document.getElementById("sessions_count_row");
			var select_count=parseInt(document.select_session.select_count.value);
			var select_interval=parseInt(document.select_session.select_interval.value);
			var session_style=document.select_session.session_style.value;
			//var session_type=document.select_session.session_type.value;
			var amount_r=0;
			var amount_w=0;
			var total_fee_r=0;
			var total_fee_w=0;
			var total_days_r=0;
			var total_days_w=0;
			var total_days=0;
			var option;
			//var choice=document.select_session.sessions_count;
			var currentIndex=1;
			var waitlist_amount=0;
			var event_type="";
			//clearChoice(choice);
			if (select_interval==0) select_interval=1;

			choiceDays1[choiceDays1.length]=total_days_r;
			choiceDays2[choiceDays2.length]=total_days_w;
			choiceAmount1[choiceAmount1.length]=total_fee_r;
			choiceAmount2[choiceAmount2.length]=total_fee_w;
			//choice.options[choice.options.length]=new Option("",0);
			for (icnt=sIndex;icnt<sessions.length;icnt++){
				amount_r=0;
				amount_w=0;
				if (sessions[icnt][1]=="R" || sessions[icnt][1]=="W") {
					if (sessions[icnt][1]=="R"){//reserve user
						amount_r=sessions[icnt][2];
						amount_w=0;
						total_days_r+=1;
					} else if (sessions[icnt][1]=="W") {
						amount_w=0;
						amount_r=0;
						if (session_style=='S' || event_type==''){
							amount_w=page.fees_vip;
							event_type='W';
						} else {
							amount_w=0;
						}
						total_days_w+=1;
					}
					total_fee_r+=amount_r;
					total_fee_w+=amount_w;
					if (currentIndex % select_interval==0){
						choiceDays1[choiceDays1.length]=total_days_r;
						choiceDays2[choiceDays2.length]=total_days_w;
						choiceAmount1[choiceAmount1.length]=total_fee_r;
						choiceAmount2[choiceAmount2.length]=total_fee_w;
						if (choiceAmount1.length>=select_count) break;
					}
					currentIndex+=1;
				}
			}
			total_days=total_days_r+total_days_w;
			if (total_days<select_interval && choiceAmount1.length==0){
				//choice.options[choice.options.length]=new Option(total_days+"<?php echo TEXT_FOR;?>"+displayCurrency(total_fee_r+total_fee_w),select_interval);
				choice.options[choice.options.length]=new Option(total_days,select_interval);
				if (page.selectedCount==select_interval)
					choice.options[choice.options.length-1].selected=true;
			}
			for (icnt=1;icnt<choiceAmount1.length;icnt++){
				currentIndex=choiceDays1[icnt]+choiceDays2[icnt];
				//choice.options[choice.options.length]=new Option(currentIndex+"<?php echo TEXT_FOR;?>"+displayCurrency(choiceAmount1[icnt]+choiceAmount2[icnt]),currentIndex);
				choice.options[choice.options.length]=new Option(currentIndex,currentIndex);
				if (page.selectedCount==currentIndex)
					choice.options[choice.options.length-1].selected=true;

			}
			if (!row) return;
			row.style.display="block";
			calculateSelectedDate(1);
		}
		function calculateSelectedDate(selectMode){
			var icnt=0;
			var jcnt=0;
			var checkbox_field;
			var event_type="";
			var amount_r=0;
			var amount_w=0;
			var total_fee_r=0;
			var total_fee_w=0;
			var total_days_r=0;
			var total_days_w=0;
			var total_fee=0;
			var total_days=0;
			var sIndex=0;
			var eIndex=sessions.length-1;
			//var length_field=document.select_session.sessions_count;
			var select_interval=parseInt(document.select_session.select_interval.value);
			var session_style=document.select_session.session_style.value;
			var session_type=document.select_session.session_type.value;
			var reserve_type=document.select_session.reserve_type.value;
			var length_index=0;
			var length_remind=0;
			var cnt=0;
			var slotNo=0;
			var checkFlag;
			var prevIndex=0;
			var session_map=Array();
			var current_interval=0;
			document.getElementById("amount_notify_r").style.visibility="hidden";
			document.getElementById("amount_notify_w").style.visibility="hidden";
			document.getElementById("amount_notify_t").style.visibility="hidden";
			if (session_type=='S') return;
			checkbox_field=document.select_session.elements['sessions_id[]'];
			if (sessions.length==1){
				if (checkbox_field.checked) {
					if (sessions[0][1]=="R") {
						$total_fee_r=sessions[icnt][2];
						total_days_r=1;
					} else if (sessions[0][1]=="W")  {
						$total_fee_w=page.fees_vip;
						total_days_w=1;
					}
				}
			}
			// session length is more than one
			if (sessions.length>1){
				reserve_type="R";
				//set the session map amount
				if (session_style!=''){
					jcnt=0;
					for (icnt=0;icnt<sessions.length;icnt++){
						if (checkbox_field[icnt].checked && sessions[icnt][1]!='E') {
							if (sessions[icnt][1]=="W") reserve_type="W";
							jcnt++;
							if (jcnt%select_interval==0) {
								session_map[current_interval]=reserve_type;
								current_interval++;
								reserve_type='R';
							}
						}
					}
					if (jcnt%select_interval!=0){
						session_map[current_interval]=reserve_type;
					}
				}
				cnt=0;
				current_interval=0;
				reserve_type="";
				for (icnt=sIndex;icnt<=eIndex;icnt++){
					amount_r=0;
					amount_w=0;
					if (checkbox_field[icnt] && sessions[icnt][1]!='E' && checkbox_field[icnt].checked) {
						if (session_style!='E'){
							if (sessions[icnt][1]=="R"){
								amount_r=sessions[icnt][2];
								total_days_r+=1;
							} else if (sessions[icnt][1]=="W") {
								amount_w=page.fees_vip;
								total_days_w+=1;
							}
						} else {
							if (session_map[current_interval]=='R'){
								amount_r=sessions[icnt][2];
								total_days_r+=1;
							} else {
								if (reserve_type=="") {
									amount_w=page.fees_vip;
									reserve_type="W";
								}
								total_days_w+=1;
							}
						}
						cnt++;
						if (session_style!=""){
							if (cnt%select_interval==0) {
								reserve_type="";
								current_interval++;
							}
						}
						total_fee_r+=parseFloat(amount_r);
						total_fee_w+=parseFloat(amount_w);
					}
				}
			}
			//alert(total_fee_r);
			total_days=total_days_r+total_days_w;
			if (total_fee_r>0){
				document.getElementById("amount_notify_r").innerHTML="<?php echo RESERVE_FEE;?>"+displayCurrency(total_fee_r)+" ("+total_days_r+" <?php echo TEXT_DAYS;?>"+")";
				document.getElementById("amount_notify_r").style.visibility="visible";
			}
			if (total_fee_w>0) {
				document.getElementById("amount_notify_w").innerHTML="<?php echo WAITLIST_FEE;?>"+displayCurrency(total_fee_w)+" ("+total_days_w+" <?php echo TEXT_DAYS;?>"+")";
				document.getElementById("amount_notify_w").style.visibility="visible";
			}
			total_fee=total_fee_r+total_fee_w;
			total_days=total_days_r+total_days_w;
			if (total_fee>0) {
				document.getElementById("amount_notify_t").innerHTML="<?php echo TOTAL_FEE;?>"+displayCurrency(total_fee)+" ("+total_days+" <?php echo TEXT_DAYS;?>"+")";
				document.getElementById("amount_notify_t").style.visibility="visible";
			}
			document.getElementById("no_sessions").innerHTML=total_days;
		}
		function selectValidDates(index){
			//alert(document.select_session.session_reduction.value);
			var select_interval=parseInt(document.select_session.select_interval.value);
			var session_style=document.select_session.session_style.value;
			var session_type=document.select_session.session_type.value;
			var session_gaps=document.select_session.session_gaps.value;
			var session_reduction=document.select_session.session_reduction.value;
			if(document.getElementById('interval')){
				if(document.getElementById('interval').innerHTML != select_interval) select_interval = parseInt(document.getElementById('interval').innerHTML);
			}
			var validIndexs=0;
			var validIndexe=0;
			var no_reserve=0;
			var no_waitlist=0;
			var checkbox_field;
			var selectSlotType=""; // check type category the selected group falls full reserve,waitlist,mixed;
			var error="";
			var no_sessions=0;
			var reserve_type="";
			var selection_mode='fixed' // 'fixed'=>fixed selection. 'reserve'=>select only reservation if possible if not change to fixed reservation
			// if single session or sessions.lengh <2 or  index is invalid exit

			//alert(select_interval);

			checkbox_field=document.select_session.elements['sessions_id[]'];
			if (session_type=='S' || sessions.length<2 || index<0 || index>=sessions.length){
				calculateSelectedDate(0);
				return;
			}
			// if auto select just select the sessions and exit
			if (auto_select){
				auto_select=false;
				if (sessions.length>1) checkbox_field[index].checked=true;
				else checkbox_field.checked=true;
			}
			//if select _interval is less than 2 exit
			if (select_interval<2 || sessions.length<2){
				calculateSelectedDate(0);
			}
			// checkbox is being selected or unselected
			checkFlag=checkbox_field[index].checked;
			if (checkFlag){
				validIndexs=index;
				checkbox_field[index].checked=false;
				reserve_type='R';
				selectSlotType="";
				// if sessions gaps is allowed and selected is reserved just change selection mode reserve
				if (session_gaps=='Y' && sessions[index][1]=="R") selection_mode='reserve';
				// iterate first by taking "reserve mode" and then work for fixed
				for (var kcnt=1;kcnt<=2;kcnt++){
					jcnt=0;
					// if selected box is reserved
					if (selection_mode=='reserve'){
						// iterate upto the sessions count & automatically checke the sessions upto select "interval" count or until a session is selected
						for (icnt=index;icnt<sessions.length;icnt++){
							if (checkbox_field[icnt].checked) break;
							validIndexe=icnt;
							if (sessions[icnt][1]=='R') {
								jcnt++;
								if (jcnt>=select_interval) break;
							}
						}
					} else { // if selected is waitlist
						// iterate and select "interval" no of sessions if valid
						for (icnt=index;icnt<sessions.length;icnt++){

							if (checkbox_field[icnt].checked) break;
							// mark the slot type to waitlist or mixed or reserved
							if (sessions[icnt][1]!='E'){
								if (sessions[icnt][1]=='W' && selectSlotType!="W"){
									if (selectSlotType=="") selectSlotType="W";
									else selectSlotType="M";
								}
								if (sessions[icnt][1]=='R' && selectSlotType!="R"){
									if (selectSlotType=="") selectSlotType="R";
									else selectSlotType="M";
								}
							}
							// if there is a empty in slot and session_gaps is not allowed pop the error message
							if (sessions[icnt][1]=='E' && session_gaps!='Y'){
                                 error="* "+page.template["ERR_SESSION_GAPS"]+"\n"
								break;
							}
							// store the last index
							validIndexe=icnt;
							if (sessions[icnt][1]!='E') {
								jcnt++;
								if (jcnt>=select_interval) break;
							}
						}
					}
					// if selection mode is reserve and "no sessions" exceeds just exit
					if (selection_mode=="reserve"){
						if (jcnt>=select_interval) {
							selectSlotType="R";
							break;
						}
						selection_mode="fixed";
					}
				}
				// if selection mode is fixed and no sessions is less than expected interval and "session reduction" is off just error
				if (error=="" && selection_mode=="fixed"){
					if (jcnt<select_interval && sessions.length>select_interval && session_reduction!='Y'){
                        error="* "+page.template["ERR_SESSION_REDUCTIONS"]+"\n"
					}
				}
				if (error!=""){
					alert(error);
					return;
				}
				// check above selected session to find any waitlist is present and slottype is other than waitlist
				for (icnt=0;icnt<validIndexs;icnt++){
					if (checkbox_field[icnt].checked){
						if (sessions[icnt][1]=='W' && selectSlotType!='W') {
                            error="* "+page.template["ERR_SESSION_WAITLIST_ABOVE"]+"\n"
							break;
						}
					}
				}
				if (error!=""){
					alert(error);
					return;
				}
				jcnt=0;
				reserve_type='R';
				for (icnt=validIndexe+1;icnt<sessions.length;icnt++){
					if (checkbox_field[icnt].checked && sessions[icnt][1]!='E'){
						if (sessions[icnt][1]=='W') reserve_type='W';
						jcnt++;
						if (jcnt%select_interval==0){
							if (reserve_type=='R' && selectSlotType!='R') {
                                error="* "+page.template["ERR_SESSION_RESERVE_BELOW"]+"\n"
								break;
							}
							reserve_type="R";
						}
					}
				}
				if (jcnt%select_interval!=0){
					if (reserve_type=='R' && selectSlotType!='R') {
                        error="* "+page.template["ERR_SESSION_RESERVE_BELOW"]+"\n"
					}
				}
				if (error!=""){
					alert(error);
					return;
				}
				// if there is no error select the possible session dates
				for (icnt=validIndexs;icnt<=validIndexe;icnt++){
					if (sessions[icnt][1]!='E') {
						if ((selection_mode=="reserve" && sessions[icnt][1]=='R') || selection_mode!="reserve")
						checkbox_field[icnt].checked=true;
					}
				}
				if (session_reduction!='Y')
					validIndexes[validIndexes.length]=validIndexs+","+validIndexe;
			} else if (session_reduction!='Y') {
				var tempStr="";
				var delIndex=-1;
				for (icnt=0;icnt<validIndexes.length;icnt++){
					tempStr=validIndexes[icnt].split(",");
					validIndexs=parseInt(tempStr[0]);
					validIndexe=parseInt(tempStr[1]);
					if (index>=validIndexs && index<=validIndexe){
						validIndexes[icnt]="";
						delIndex=icnt;
						break;
					}
				}
				if (delIndex>=0) {
					for (icnt=validIndexs;icnt<=validIndexe;icnt++){
						if (sessions[icnt][1]!='E') {
							checkbox_field[icnt].checked=false;
						}
					}
					deleteStoredIndex(delIndex);
				}
			}
			calculateSelectedDate(0);
		}
		function deleteStoredIndex(delIndex){
			if (delIndex<0 || delIndex>=validIndexes.length) return;
			for (var jcnt=delIndex; jcnt<validIndexes.length-1; jcnt++)
				validIndexes[jcnt] = validIndexes[jcnt+1];
			validIndexes.length = validIndexes.length-1;
		}
		function clearChoice(choice){
			if (choice.options==null) return;
			while(choice.options.length>0){
				choice.options[0]=null;
			}
			choice.selectedIndex=-1;
			choiceAmount1=Array();
			choiceAmount2=Array();
			choiceDays1=Array();
			choiceDays2=Array();
		}
		function validateeventForm(frm){
    		var error="";
			var icnt=0;
			var check_field=frm.elements["sessions_id[]"];
			var session_type=frm.session_type.value;
			var session_style=frm.session_style.value;
			var session_gaps=frm.session_gaps.value;
			var session_reduction=frm.session_reduction.value;
			//if (session_type=='S') return true;

			if (sessions.length>1){
				error="* "+page.template["ERR_SELECT_SESSION_DATE"]+"\n"
				for (icnt=0;icnt<check_field.length;icnt++){
					if (check_field[icnt].checked) {
						error="";
						break;
					}
				}
			} else {
				if (check_field.checked==false){
					error="* "+page.template["ERR_SELECT_SESSION_DATE"]+"\n"
                 
				}
			}
			if (error=="" && frm.question_type){
				if (frm.question_type.value=="D"){
					if (isDate(frm.question_month.value+"/"+frm.question_day.value+"/"+frm.question_year.value)==false){
						error="<?php echo addslashes(stripslashes($question_result_error));?>";
					} else {
						frm.question_value_data.value=frm.question_year.value + "-" + frm.question_month.value + "-" + frm.question_day.value;
					}
				} else if (frm.question_type.value=="T" && frm.question_value_data.value==""){
						error="<?php echo addslashes(stripslashes($question_result_error));?>";
				} else if (frm.question_type.value=="N" && isNaN(parseInt(frm.question_value_data.value))){
						error="<?php echo addslashes(stripslashes($question_result_error));?>";
				}
			}
			if (error!=""){
				alert(error);
				return false;
			}
			if (sessions.length<=1 || session_style!='E' || session_type=='S') return true;
			// check for event based waiting lists that reservations exist before selected waitlist
			var no_reserve=0; // selected reserve count
			var no_waitlist=0;
			var gap_on=false;
			session_store_type="M";
			for (icnt=0;icnt<sessions.length;icnt++){
				if (sessions[icnt][1]=='W' && check_field[icnt].checked) {
					session_store_type='W';
				}
				if (sessions[icnt][1]=='R' && check_field[icnt].checked) no_reserve+=1;
			}
			return true;
		}
		function isDate(dateStr) {

		  var datePat = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
		  var matchArray = dateStr.match(datePat); // is format OK?

		  if (matchArray == null) {
			return false;
		  }

		  // parse date into variables
		  month = matchArray[1];
		  day = matchArray[3];
		  year = matchArray[5];

		  if (month < 1 || month > 12) { // check month range
			return false;
		  }

		  if (day < 1 || day > 31) {
			return false;
		  }

		  if ((month==4 || month==6 || month==9 || month==11) && day==31) {
			return false;
		  }

		  if (month == 2) { // check for february 29th
			var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
			if (day > 29 || (day==29 && !isleap)) {
			  return false;
			}
		  }
		  return true;  // date is valid
}
	function check_input(field_name, pos,field_size) {
	  var form=document.select_session;
	  if (pos>=0){
		  var control=form.elements[field_name][pos];
	  } else {
		  var control=form.elements[field_name];
	  }
	  var field_value = control.value;
	  if (field_value == '' || field_value.length < field_size) {
		return true;
	  }
	}
	function check_select(field_name, pos,field_default) {
	  var form=document.select_session;
	  if (pos>=0){
		  var control=form.elements[field_name][pos];
	  } else {
		  var control=form.elements[field_name];
	  }
	  var field_value = control.value;

	   if (field_value == field_default) {
		 return true;
	   }
	}

function dofill(){
	var frm=document.select_session;
	frm_customers=frm.elements["attendee[]"];
	for (icnt=1;icnt<frm.elements["city[]"].length;icnt++)
	{
	 if(frm_customers && frm_customers[icnt].checked) return;
	 frm.elements["city[]"][icnt].value=frm.elements["city[]"][0].value;
	 frm.elements["state1[]"][icnt].style.display='';
	 frm.elements["state[]"][icnt].style.display="none";
	 if(frm.elements["state[]"][0].style.display!='none')
	 	state=frm.elements["state[]"][0].value;
	 else if(frm.elements["state1[]"][0].style.display!='none')
	 	state=frm.elements["state1[]"][0].value;

	 frm.elements["state1[]"][icnt].value=state;
	 frm.elements["country[]"][icnt].value=frm.elements["country[]"][0].value;
	 frm.elements["postcode[]"][icnt].value=frm.elements["postcode[]"][0].value;
	 frm.elements["street_address[]"][icnt].value=frm.elements["street_address[]"][0].value;
	 frm.elements["email_address[]"][icnt].value=frm.elements["email_address[]"][0].value;
	}
}
function clear_error(pos){
	error_control=document.getElementById("error_box_"+pos);
	error_control.innerHTML="";
	error_control.style.display="none";
}
function show_errors(count){
	for (icnt=0;icnt<count;icnt++){
		error_control=document.getElementById("error_box_"+icnt);
		error_control.style.display="";
	}
}
function check_customer(pos){
	var error_att="";
		if (check_input("first_name[]",pos,page.template["ENTRY_FIRST_NAME_MIN_LENGTH"])){
			error_att+=page.template["FIRST_NAME_ERROR"] +"<br>";
		}
		if (check_input("last_name[]",pos,page.template["ENTRY_LAST_NAME_MIN_LENGTH"])){
			error_att+=page.template["LAST_NAME_ERROR"] +"<br>";
		}
		if (check_input("email_address[]",pos,page.template["ENTRY_EMAIL_ADDRESS_MIN_LENGTH"])){
			error_att+=page.template["EMAIL_ADDRESS_ERROR"]+"<br>";
		}
        controls=document.select_session.elements['email_address[]'][pos];
        
        if(controls.value.indexOf('@')<=0 || controls.value.indexOf('.')<=0){
			error_att+=page.template["ADDRESS_CHECK_ERROR"];
		}

		if (check_input("street_address[]",pos,page.template["ENTRY_STREET_ADDRESS_MIN_LENGTH"])){
			error_att+=page.template["STREET_ADDRESS_ERROR"] + "<br>";
		}
		if (check_input("city[]",pos,page.template["ENTRY_CITY_MIN_LENGTH"])){
			error_att+=page.template["CITY_ERROR"]+"<br>";
		}
		if (check_input("postcode[]",pos,page.template["ENTRY_POSTCODE_MIN_LENGTH"])){
			error_att+=page.template["POSTCODE_ERROR"] +"<br>";
		}
		if(document.select_session.elements["state[]"].size!=0){
			if (document.select_session.elements["state[]"][pos].style.display!='none' && check_input("state[]",pos,page.template["ENTRY_STATE_MIN_LENGTH"])){
				error_att+=page.template["STATE_ERROR"] +"<br>";
			}else if(document.select_session.elements["state1[]"][pos].style.display!='none' && check_input("state1[]",pos,page.template["ENTRY_STATE_MIN_LENGTH"])){
				error_att+=page.template["STATE_ERROR"] +"<br>";
			}
		}else if(document.select_session.elements["state[]"].size==0) {
			if (document.select_session.elements["state[]"].style.display!='none' && check_input("state[]",pos,page.template["ENTRY_STATE_MIN_LENGTH"])){
				error_att+=page.template["STATE_ERROR"] +"<br>";
			}else if(document.select_session.elements["state1[]"].style.display!='none' && check_input("state1[]",pos,page.template["ENTRY_STATE_MIN_LENGTH"])){
				error_att+=page.template["STATE_ERROR"] +"<br>";
			}
		}
		if (check_select("country[]",pos,"")){
			error_att+=page.template["COUNTRY_ERROR"] +"<br>";
		}
		if (pos>0){
			var controls=document.select_session.elements["email_address[]"];
			var attendees=document.select_session.elements["attendee[]"];
			for (jcnt=pos-1;jcnt>=0;jcnt--){
				if (controls[pos].value==controls[jcnt].value){
					if(attendees && attendees[pos].checked)
						error_att+=page.template["ERR_ATTENDEES_MUST_UNIQUE"];
					else if(attendees && !attendees[pos].checked)
						error_att+=page.template["EMAIL_UNIQUE_ERROR"];
				}
				
			}
		}
	return error_att;
}
function check_attendee_form(form_name) {
	var error=false;
	var frm=document.select_session;
	if(frm.elements["country[]"].size!=0){
		for(i=0;i<frm.elements["country[]"].length;i++){
			if(frm.elements["state[]"][i].style.display!='none')
				frm.elements["state[]"][i].disabled=false;
				frm.elements["country[]"][i].disabled=false;
		}
	}else if(frm.elements["country[]"].size==0){
			if(frm.elements["state[]"].style.display!='none')
				frm.elements["state[]"].disabled=false;
			frm.elements["country[]"].disabled=false;
	}
	var controls=frm.elements["first_name[]"];
	set_state();
	if (controls.length){
		for (icnt=0;icnt<controls.length;icnt++){
			error_att=check_customer(icnt);
			if (error_att!="") {
				error=true;
				error_control=document.getElementById("error_box_"+icnt);
				error_control.innerHTML=error_att;
				error_control.style.display="";
			}
		}
	} else {
		error_att=check_customer(-1);
		if (error_att!="") {
			error=true;
			error_control=document.getElementById("error_box_0");
			error_control.innerHTML=error_att;
			error_control.style.display="";
		}
	}
	if (error == true) {
		return false;
	} else {

		submitted = true;
		return true;
	}
}

 function hide_customerlistbox(i){
	 var frm=document.select_session;
	 var val=new Array('','','','','','','','');
	 var frm_customer=frm.elements["attendee[]"];
	 var check=false;
	 	 if(frm_customer.length>0){
				if(frm_customer) check=frm_customer[i].checked;
				if(check){
					frm.customer_text[i].style.display="";
					fill_details(i);
				}else if(!check) {
					frm.customer_text[i].style.display="none";
					fetch_details(val,i);
				}
				frm.elements["first_name[]"][i].readOnly=frm.elements["last_name[]"][i].readOnly=frm.elements["email_address[]"][i].readOnly=frm.elements["street_address[]"][i].readOnly=frm.elements["city[]"][i].readOnly=frm.elements["postcode[]"][i].readOnly=check;
				frm.elements["country[]"][i].disabled=check;
				frm.elements["state[]"][i].style.display='none';
				frm.elements["state1[]"][i].style.display='';
				if(frm.elements["state1[]"][i])
					frm.elements["state1[]"][i].readOnly=check;

		}else {
			if(frm.elements["attendee[]"]) check=frm.elements["attendee[]"].checked;
			if(check){
				frm.customer_text.style.display="";
				fill_details(0);
			}else if(!check) {
				frm.customer_text.style.display="none";
				fetch_details(val,0);
			}
			frm.elements["first_name[]"].readOnly=frm.elements["last_name[]"].readOnly=frm.elements["email_address[]"].readOnly=frm.elements["street_address[]"].readOnly=frm.elements["city[]"].readOnly=frm.elements["postcode[]"].readOnly=check;
			frm.elements["country[]"].disabled=check;
			if(frm.elements["state[]"].style.display!='none')
				frm.elements["state[]"].disabled=check;
			else if(frm.elements["state1[]"])
				frm.elements["state1[]"].readOnly=check;
		}
	}
  function fill_details(cnt){
  	id=0;
  	var frm=document.select_session.elements["customer_text"];
  	if(frm.size!=0)
  		id=frm[cnt].options[frm[cnt].selectedIndex].value;
	else
		id=frm.options[frm.selectedIndex].value;
		eval("doSimpleAction({'id':'new_event_step2','get':'FetchCustomersDetails','result':doDisplayOrderResult,'style':'boxRow','type':'item','params':'cust_id=" + id +"&cnt=" + cnt + "','message':'"+ page.template['TEXT_LOADING'] +"'})");
  }
	function set_state(){
		var frm=document.select_session;
			if(frm.elements["country[]"].size!=0){
				for(i=0;i<frm.elements["country[]"].length;i++){
					if(frm.elements["state[]"][i].style.display!='none'){
						frm.elements["customer_state[]"][i].value=frm.elements["state[]"][i].value;
					}
					else
						frm.elements["customer_state[]"][i].value=frm.elements["state1[]"][i].value;
				}
			}else if(frm.elements["country[]"].size==0){
					if(frm.elements["state[]"].style.display!='none'){
						frm.elements["customer_state[]"].value=frm.elements["state[]"].value;
					}
					else
						frm.elements["customer_state[]"].value=frm.elements["state1[]"].value;
			}

	}

  function fetch_details(val,i){
  	var frm=document.select_session;
  	if(!frm.elements["first_name[]"].length){
  		frm.elements["first_name[]"].value=val[0];
		frm.elements["last_name[]"].value=val[1];
		frm.elements["email_address[]"].value=val[2];
		frm.elements["street_address[]"].value=val[3];
		frm.elements["city[]"].value=val[4];
		frm.elements["postcode[]"].value=val[5];
		if(frm.elements["state[]"].style.display!='none')
			frm.elements["state[]"].value=val[6];
		else if(frm.elements["state1[]"])
			frm.elements["state1[]"].value=val[6];
		frm.elements["country[]"].value=val[7];
	}else if(frm.elements["first_name[]"][i]){
		frm.elements["first_name[]"][i].value=val[0];
		frm.elements["last_name[]"][i].value=val[1];
		frm.elements["email_address[]"][i].value=val[2];
		frm.elements["street_address[]"][i].value=val[3];
		frm.elements["city[]"][i].value=val[4];
		frm.elements["postcode[]"][i].value=val[5];
		if(frm.elements["state[]"][i].style.display!='none')
			frm.elements["state[]"][i].value=val[6];
		else if(frm.elements["state1[]"][i]){
			frm.elements["state1[]"][i].value=val[6];
		}
		frm.elements["country[]"][i].value=val[7];
	}
  }

  function show_attendee_state(cnt){
  		var this_=document.select_session.elements["country[]"];
  		if(this_[cnt].size==0)
  			id=this_[cnt].options[this_[cnt].selectedIndex].value;
  		else
  			id=this_.options[this_.selectedIndex].value;
		if(id){
			eval("doSimpleAction({'id':'new_event_step2','get':'ShowState','result':doDisplayOrderResult,'style':'boxRow','type':'item','params':'country_id="+id+"&cnt="+cnt + "','message':'"+ page.template['TEXT_LOADING'] +"'})");
		}
  }

//======================
//====service===============
			var nb_dates=Array();
			var start_time="00:00";
			var end_time="23:59";
		    var attribute_array=Array();
 			var sel_start_date="";
			var sel_start_time="";
			var select_edate="";
			//if(!page.time_length) page.time_length=1;
			var tempCheck=1;
	function selectServiceItem(boxname,id,index){
		var element=document.getElementById(boxname+id);
		var field=document.select_resource.elements[boxname+"_index"];
		field.value=index;
		field=document.select_resource.elements[boxname];
		if (field.value!=''){
			document.getElementById(boxname+field.value).style.backgroundColor="#FFFFFF";
		}
		field.value=id;
		element.style.backgroundColor="#D8EDFB";
	}

				function ValidateserviceForm(){
					var frm = document.select_resource;
					var error_result="";
					if (page.book_type=="D"){
						if (frm && frm.start_times.value==""){
							error_result=page.template['ERR_SELECT_START_TIME'];
						} else if (frm && frm.end_times.value==""){
							error_result=page.template['ERR_SELECT_END_TIME'];
						}
					} else {
						if (frm && frm.start_date.value=="" || frm && frm.start_time.value==""){
							error_result=page.template['ERR_SELECT_START_TIME'];
						} else if (frm && frm.end_times.value==""){
							error_result=page.template['ERR_SELECT_END_TIME'];
						}
					}
					if(frm && page.include_resource=='N' &&  page.multiple_purchase=='Y')
					{
						if(frm.quantity.value=="" || isNaN(frm.ser_quantity.value))
							error_result=page.template['ERR_EMPTY_HOW_MANY'];
					}
					if (error_result!=""){
						alert(error_result);
						return false;
					}
					var option_id="";
					if(frm && page.include_resource=='N' &&  page.multiple_purchase=='Y')
						frm.ser_quantity.value=frm.quantity.value;
					else if(frm)
						frm.ser_quantity.value=1;
					var id="";
					if(frm){
						for(i=0;i<attribute_array.length;i++){
							id="id_attrib["+attribute_array[i]+"]";
							frm_option=document.getElementById(id);
							if(frm_option) option_id+=frm_option.options[frm_option.selectedIndex].value+",";
						}
					}
					if(document.select_resource.option_ids) document.select_resource.option_ids.value=option_id;
					if(frm && page.book_type=='D' && frm.start_times)
						frm.start_times.value=frm.start_times.options[frm.start_times.selectedIndex].value;
					return true;
				}

				function sel_services(boxname,id,index){
					var element=document.getElementById(boxname+id);
					var field=document.select_resource.elements[boxname+"_index"];
					field.value=index;
					field=document.select_resource.elements[boxname];
					if (field.value!=''){
						document.getElementById(boxname+field.value).style.backgroundColor="#FFFFFF";
					}
					field.value=id;
					element.style.backgroundColor="#D8EDFB";
				}
				function get_diff_minutes(stime,etime){
					splt1=stime.split(":");
					splt2=etime.split(":");
					smin=parseInt(splt1[0],10)*60+parseInt(splt1[1],10);
					emin=parseInt(splt2[0],10)*60+parseInt(splt2[1],10);
					return emin-smin;
				}

				function add_options(textArr,valueArr,optionElement){
					// clear the previous details

					while(optionElement.options.length>0){
						optionElement.remove(optionElement.options.length - 1);
					}
					//create the new details
					for (icnt=0;icnt<textArr.length;icnt++){
						  var option = document.createElement('option');
						  option.text = textArr[icnt];
						  option.value = valueArr[icnt];
						  try {
							optionElement.add(option, null); // standards compliant; doesn't work in IE
						  }
						  catch(ex) {
							optionElement.add(option); // IE only
						  }
						  if (valueArr[icnt]==select_edate) optionElement.selectedIndex=optionElement.options.length-1;
					}
				}

			function add_start_times(){
				  	var select_cnt=0;
					var dis_time_format="";
					dis_time_format="<?php if(defined('TIME_FORMAT')) echo TIME_FORMAT; ?>";
					if((page.book_type=='H' || page.book_type=='M') && document.select_resource && document.select_resource.start_date){
						st_date=document.select_resource.start_date.value;
						var tempDate=convert_to_sdate(st_date);
					}
					var i=0;
					var hour;
					var sel_ecnt=0;
					var arr_text=Array();
					var arr_value=Array();
					splt_cur_hour=page.cur_hour.split(":");
					var cnt=0;
					if(tempDate==page.cur_date) i=(parseInt(splt_cur_hour[0])+1) * 60;
					var slot=parseInt(page.book_granular); //booking granular
					if(page.book_type=='H') slot=parseInt(slot*60);
					while(i<=1440){
						if(i>1440) break;
					   // loop for no booking date and time
					    for(j=0;j<nb_dates.length;j++){
							 n_dates=nb_dates[j].split("#");
							 n_sdate=n_dates[0];
							 nb_s_time=n_dates[1];
							 nb_e_time=n_dates[2];
							 splt_etime=nb_e_time.split(":");
							 splt_stime=nb_s_time.split(":");
							 splt_stime_mt=splt_stime[0] * 60;
							 splt_etime_mt=splt_etime[0] * 60;
							 if(n_sdate==tempDate){
							      if(splt_stime_mt<=i && i<=splt_etime_mt || splt_stime_mt==i)
							   	  	i=splt_etime_mt;
						     }
						}
						// loop for no booking date and time
						 buf_min=0;
						 if(i>=1440) break;
						 var mins=parseInt(page.time_length*60);
						 if(page.book_type=='M') mins=parseInt(page.time_length);
						 for(ict=0;ict<=59;ict+=slot){
					 	     var d_obj=new Date(1976,1,1,0,i,ict*60);
						  	 hr=((d_obj.getHours()==0)?00:d_obj.getHours());
							 hr=((hr<10)?"0"+hr:hr);
						     mt=((d_obj.getMinutes()<10)?"0"+d_obj.getMinutes():d_obj.getMinutes());
						     var tem_hr=hr;
						     if(dis_time_format=='24'){
						     	if(hr==12) tem_hr=12;
						     	//if(hr==00) tem_hr=24;
						     }else if(hr==12) tem_hr=24;
						     tem_hour=tem_hr +":"+mt;
						     hour=hr +":"+mt;
						     var n_obj=new Date(1976,1,1,hr,(mt+mins));
						     if(page.span_days=='N' && n_obj.getDate()=="2" && (n_obj.getMinutes()>0 || n_obj.getHours()>0)) hour="";
						     	if(hour!=""){
									var dis_time=convert_to_stime(tem_hour);
									if(dis_time_format!="" && dis_time_format=='24') dis_time=tem_hour;
							     		if(convert_to_stime(page.st_time)==convert_to_stime(hour)) select_cnt=cnt;
											arr_text[cnt]=dis_time;
											arr_value[cnt]=hour;
							     			cnt++;
						     	}
						  }
						  if(page.book_type=='H') i=parseInt(i+slot);
						  else i=parseInt(i+60);
						  if(i>1440) break;
					 }
					 if(arr_text.length<=0 && arr_value.length<=0 && (page.book_type=='H' || page.book_type=='M') && document.select_resource.start_time && document.select_resource.start_date){
					 	document.select_resource.start_date.remove(document.select_resource.start_date.options.selectedIndex);
					 	add_start_times();
					 }
					 if (arr_text.length>0 && arr_value.length>0 && (page.book_type=='H' || page.book_type=='M')){
					 	add_options(arr_text,arr_value,document.select_resource.start_time);
					 	add_end_times();
					 }
					return select_cnt;
				}

				function add_end_times(){
				var arr_text=Array();
				var sel_ecnt=0;
				var arr_value=Array();
				//check if booking is day based or hours based
				var dis_time_format="";
				dis_time_format="<?php if(defined('TIME_FORMAT')) echo TIME_FORMAT; ?>";
				var end_time='23:59:00';
				var send_time=convert_to_stime('00:00:00');
				if(dis_time_format!="") if(dis_time_format=='24') send_time='00:00';
				if (page.book_type=="D"){
						if(document.select_resource && document.select_resource.start_times) var tempDate=getDatePart(document.select_resource.start_times.value,"D");
						var check_date=tempDate;
						jcnt=0;
						if (!tempDate) return;
						for (icnt=page.time_length;icnt<=page.max_bookings;icnt=icnt+page.time_length){
						    start_time_split=start_time.split(":");
						    hour=start_time_split[0];
						  if(hour==00){
								if(jcnt>=1)
								  	tempDate=dateAdd("d",page.time_length,tempDate);
								else
									tempDate=dateAdd("d",page.time_length-1,tempDate);
					      }else tempDate=dateAdd("d",page.time_length,tempDate);
						 	if(page.et_time==tempDate+" "+end_time)sel_ecnt=jcnt;
						 	tempDate_ed=dateAdd("d",1,tempDate);
							arr_value[jcnt]=tempDate+" "+end_time;
							arr_text[jcnt]=date_format(tempDate_ed,'Y-m-d','')+" "+send_time;
							jcnt++;
						}
				} else {
						var tempTime_s='0:0';
						var tempDate='1-1';
						if(document.select_resource){
							tempTime_s=document.select_resource.start_time.value;
							tempDate=document.select_resource.start_date.value;
							var check_date=tempDate;
							var tempDateS=document.select_resource.start_date.value;
						}
						var check_date=tempDateS;
						if (!tempTime_s && document.select_resource) {
							document.select_resource.start_time.remove(document.select_resource.start_time.options.selectedIndex)
							if(document.select_resource.start_time.options.length<0) document.select_resource.start_date.remove(document.select_resource.start_date.options.selectedIndex)
						}
						if (page.book_granular<=0) page.book_granular=1;
						slot=page.book_granular;
						jcnt=0;
						elapsed=1;
						tempCheck=0;
						hour=0;
						var display_hour = 0;
						var display_minute = 0;
						var mins=page.time_length*60;
						var maxs=page.max_bookings*60;
						if(page.book_type=='M'){
							mins=page.time_length;
							maxs=page.max_bookings;
						}
						while(true){
								tempTime_splt=tempTime_s.split(":");
								tempdate_splt=check_date.split("-");
								hour=parseInt(tempTime_splt[0],10);
								if(tempTime_s=="24:00"){
							    	hour=11+parseInt(page.time_length);
								    display_hour=11+parseInt(page.time_length);  //Display Hour
								}
								//else if(hour==23 && page.book_type=='H') mins-=1;
								else if(tempTime_splt[0]=="24"){
								  hour=12+parseInt(page.time_length);
								  display_hour=12+parseInt(page.time_length);  //Display Hour
								}
							hour=((hour<10)?"0"+hour:hour);
							minute=parseInt(tempTime_splt[1],10)+mins;
							minute=((minute<10)?"0"+minute:minute);
							var dt=new Date(parseInt(tempdate_splt[0]),tempdate_splt[1]-1,tempdate_splt[2],hour,minute,0);
							getday=dt.getDate();
							getmonth=dt.getMonth()+1;
							getyear=dt.getFullYear();
							set_getday=getday;
							set_getmonth=getmonth
							set_getyear=getyear;
							if(tempdate_splt[2]<getday && dt.getHours()==0 && dt.getMinutes()==0){
								set_getday=tempdate_splt[2];
								set_getmonth=tempdate_splt[1];
								set_getyear=tempdate_splt[0];
							}
							if(set_getday<10)   set_getday="0"+set_getday;
							if(set_getmonth<10) set_getmonth="0"+set_getmonth;
							tempDate=tempDateS=(dt.getFullYear()+"-"+getmonth+"-"+getday);
							set_tempDate=(set_getyear+"-"+set_getmonth+"-"+set_getday);
							tempTime_s=((dt.getHours()<10)?"0"+dt.getHours():dt.getHours()) + ":"+ dt.getMinutes();
							var display_dt=new Date(tempdate_splt[0],tempdate_splt[1]-1,tempdate_splt[2],hour,minute,(minute=="59")?60:1);
							display_tempDate=(display_dt.getFullYear()+"-"+(display_dt.getMonth()+1)+"-"+display_dt.getDate());
							display_tempTime_s=((display_dt.getHours()<10)?"0"+display_dt.getHours():display_dt.getHours())+ ":" + ((display_dt.getMinutes()<10)?"0"+display_dt.getMinutes():display_dt.getMinutes());
							var dis_time=convert_to_stime(display_tempTime_s);
							if(dis_time_format!="" && dis_time_format=='24')  dis_time=display_tempTime_s;
							check_date_obj=date_format(check_date,'Y-m-d','Y-m-d',true);
							tempDate_obj=date_format(tempDate,'Y-m-d','Y-m-d',true);
							if (tempTime_s>end_time || (page.span_days=='N' && check_date_obj<tempDate_obj && (dt.getMinutes()>0 || dt.getHours()>0))) break;
								if(page.et_time==tempDate+" "+tempTime_s) sel_ecnt=jcnt;
								arr_text[jcnt]=date_format(display_tempDate,'Y-m-d','')+" "+dis_time;
								arr_value[jcnt]=set_tempDate+" "+tempTime_s;
								elapsed+=mins;
							if (elapsed>maxs) break;
							jcnt++;
					 }
				 }
				  if(arr_text.length>0 && document.select_resource) {
					document.select_resource.end_times.style.display='';
					add_options(arr_text,arr_value,document.select_resource.end_times);
				  }
				  return sel_ecnt;
				}
				function getDatePart(source,type){
					var result="";
					if (source!=""){
						splt=source.split(" ");
						if (type=="D")
							result=splt[0];
						else
							result=splt[1];
					}

					return result;
				}
				function convert_to_sdate(sdate){
					format=page.template['EVENTS_DATE_FORMAT'];
					splt=sdate.split("-");
					switch(format){
						case "d-m-Y":
							rdate=splt[2]+"-"+splt[1]+"-"+splt[0];
							break;
						case "m-d-Y":
							rdate=splt[1]+"-"+splt[2]+"-"+splt[0];
							break;
						case "Y-m-d":
							rdate=sdate;
							break;
					}
					return rdate;
				}

				function convert_to_stime(stime){
					splt=stime.split(":");
					hour=parseInt(splt[0],10);
					minutes=parseInt(splt[1],10);
					if (hour>12 || hour==12){
						if (hour>12) hour=hour-12;
						rtime=" PM";
					} else
						rtime=" AM";
					if (hour<10) hour="0"+hour;
					if (minutes<10) minutes="0"+minutes;
					rtime=((hour==00)?12:hour)+":"+minutes+rtime;
					return rtime;
				}


   function dateAdd(p_Interval, p_Number, p_Date,ret_type){
				if(isNaN(p_Number)){return false;}
				//convert date elements
				if(!ret_type) ret_type=false;
				if (page.book_type=="H"){
					splt=new Array(1967,1,1);
					splt_hours=p_Date.split(" ");
					if(splt_hours[1]) splt_times=splt_hours[1].split(":");
					splt=splt_hours[0].split("-");
					m=parseInt(splt[1],10);
					if (tempCheck==0) {
					  m--;
					  tempCheck=1;
					}
					var dt=new Date(parseInt(splt[0],10),parseInt(splt[1],10)-1,parseInt(splt[2],10),parseInt(splt_times[0],10),parseInt(splt_times[1],10));
				} else {
					splt_times=new Array(0,0,0);
					splt_hours=getDatePart(p_Date,'D');
					if(getDatePart(p_Date,'H')) splt_times=getDatePart(p_Date,'H').split(":");
					splt=getDatePart(p_Date,'D').split("-");
					var dt=new Date(parseInt(splt[0],10),parseInt(splt[1],10)-1,parseInt(splt[2],10),parseInt(splt_times[0],10),parseInt(splt_times[1],10));
				}
				p_Number = new Number(p_Number);
				switch(p_Interval.toLowerCase()){
					case "yyyy": {// year
						dt.setFullYear(dt.getFullYear() + p_Number);
						break;
					}
					case "q": {		// quarter
						dt.setMonth(dt.getMonth() + (p_Number*3));
						break;
					}
					case "m": {		// month
						dt.setMonth(dt.getMonth() + p_Number);
						break;
					}
					case "y":		// day of year
					case "d":		// day
					case "w": {		// weekday
						dt.setDate(dt.getDate() + p_Number);
						break;
					}
					case "ww": {	// week of year
						dt.setDate(dt.getDate() + (p_Number*7));
						break;
					}
					case "h": {		// hour
						dt.setHours(dt.getHours() + p_Number);
						break;
					}
					case "n": {		// minute
						dt.setMinutes(dt.getMinutes() + p_Number);
						break;
					}
					case "s": {		// second
						dt.setSeconds(dt.getSeconds() + p_Number);
						break;
					}
					case "ms": {		// second
						dt.setMilliseconds(dt.getMilliseconds() + p_Number);
						break;
					}
					default: {
						return "invalid interval: '" + p_Interval + "'";
					}
				}
			hour=dt.getHours();
			minute=dt.getMinutes();
			if (hour<10) hour="0"+hour;
			if (minute<10) minute="0"+minute;
			day=dt.getDate();
			month=dt.getMonth()+1;
			if (day<10) day="0"+day;
			if (month<10) month="0"+month;
			var ret="";
			if (page.book_type=="H") {
				if(ret_type) ret=dt.getFullYear()+"-"+month+"-"+day+" "+hour+":"+minute;
				else ret=hour+":"+minute;
			}else {
				if(ret_type) ret=dt.getFullYear()+"-"+month+"-"+day+" "+hour+":"+minute;
				else ret=dt.getFullYear()+"-"+month+"-"+day;
			}
			  return ret;
			}

	function on_select(cou){
	   if(document.select_resource){
	  	var frm=document.select_resource.elements["resource[]"];
		var resource_id="";
		if(frm){
		 if(frm.length){
			frm[0].checked=true;
			frm[cou].checked=true;
			for(i=0;i<frm.length;i++){
			 if(frm[i].checked){
			  resource_split=frm[i].value.split('#');
			  resource=resource_split[0].split('-');
			  st_time_string=resource_split[1];
			  et_time_string=resource_split[2];
			  start_date=getDatePart(st_time_string,'D');
			  start_time=getDatePart(st_time_string,'H');
			  end_date=getDatePart(et_time_string,'D');
			  end_time=getDatePart(et_time_string,'H');
			  end_times=et_time_string;
			  resource_id=resource[0];
			  costs=resource[1];
			 }
		   }
		 }else {
		 	frm.checked=true;
		 	if(frm.checked) {
		 		resource_split=frm.value.split('#');
			    resource=resource_split[0].split('-');
		 	}
			resource_id=resource[0];
			st_time_string=resource_split[1];
			et_time_string=resource_split[2];
			start_date=getDatePart(st_time_string,'D');
			start_time=getDatePart(st_time_string,'H');
			end_date=getDatePart(et_time_string,'D');
			end_time=getDatePart(et_time_string,'H');
			end_times=et_time_string;
			costs=resource[1];
		 }
		 document.select_resource.resource_ids.value=resource_id;
		 document.select_resource.resource_costs.value=costs;
		 document.select_resource.end_times.value=end_times;
		 formatdate=dateAdd('n',0,end_times,true);
		 if(document.getElementById("div_dates")) document.getElementById("div_dates").innerHTML="<span class=smalltext><b>"+ page.template['TEXT_START_DATE'] + "</b>&nbsp;" +date_format(start_date,'Y-m-d')+" "+convert_to_stime(start_time)+"&nbsp;&nbsp;&nbsp;&nbsp;<b>" +page.template['TEXT_END_DATE'] + "</b>&nbsp;"+date_format(getDatePart(formatdate,'D'),'Y-m-d')+" "+convert_to_stime(getDatePart(formatdate,'H'))+"</span>";
		}
	  }
	}

//==========================
//===========date functions=================
	function date_format(sourceDate,srcFormat,resultFormat,bolConvertObject){
			var dt;
			if (srcFormat=="")	srcFormat=page.template['EVENTS_DATE_FORMAT'];
			if (resultFormat=="")	resultFormat=page.template['EVENTS_DATE_FORMAT'];
			if (typeof(sourceDate)=="string"){
				if (sourceDate=="") return false;
				if (!bolConvertObject && srcFormat!="" && resultFormat==srcFormat) return sourceDate;
				var splt=sourceDate.split("-");
				if (splt.length!=3) return false;
				switch(srcFormat){
					case "d-m-Y":
						sourceDate=new Date(parseInt(splt[2],10),parseInt(splt[1],10)-1,parseInt(splt[0],10));
						break;
					case "m-d-Y":
						sourceDate=new Date(parseInt(splt[2],10),parseInt(splt[0],10)-1,parseInt(splt[1],10));
						break;

					default:
						sourceDate=new Date(parseInt(splt[0],10),parseInt(splt[1],10)-1,parseInt(splt[2],10));
				}
			}

			if (!sourceDate) return false;
			if (bolConvertObject) return sourceDate;
			var day=sourceDate.getDate();
			var month=sourceDate.getMonth()+1;
			if (day<10) day="0"+day;
			var year=sourceDate.getFullYear();
			if (month<10) month="0"+month;
			switch(resultFormat){
				case "d-m-Y":
					var textdate=day+"-"+month+"-"+year;
					break;
				case "m-d-Y":
					var textdate=month+"-"+day+"-"+year;
					break;
				default:
					var textdate=year+"-"+month+"-"+day;
					break;
			}
			return textdate;
		}
 	function doRound(x, places) {
	  	return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
	}

	function getTaxRate(parameterVal) {
	  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
		return tax_rates[parameterVal];
	  } else {
		return 0;
	  }
	}

	function updateGross(grossValue,taxRate,total) {
	  var qty="";
	   if (taxRate > 0) {
		grossValue = grossValue * ((taxRate / 100) + 1);
	  }
	ret_value=doRound(grossValue, 4);
	if(total){
		ret_value=dollarAmount(qty*ret_value);
	}
	return ret_value;
	}
	function dollarAmount(Num) {
		var sym_left=page.template['SYMBOL_LEFT'];
		var sym_right=page.template['SYMBOL_RIGHT'];

		if (typeof(Num)=="number") {
			Num=doRound(Num,2);
			Num=""+Num;
		} else {
			Num=""+doRound(parseFloat(Num),2);
		}

		dec = Num.indexOf(".");
		end = ((dec > -1) ? "" + Num.substring(dec,Num.length) : ".00");
		Num = "" + parseInt(Num);

		var temp1 = "";
		var temp2 = "";

		if (end.length == 2) end += "0";
		if (end.length == 1) end += "00";
		if (end == "") end += ".00";

		var count = 0;
		for (var k = Num.length-1; k >= 0; k--) {
		var oneChar = Num.charAt(k);
		if (count == 3 && oneChar!="-") {
		temp1 += ",";
		temp1 += oneChar;
		count = 1;
		continue;
		}
		else {
		temp1 += oneChar;
		count ++;
		   }
		}
		for (var k = temp1.length-1; k >= 0; k--) {
		var oneChar = temp1.charAt(k);
		temp2 += oneChar;
		}
		temp2 = sym_left + temp2 + end + sym_right;
		return temp2;
	  }
	function show_state(zone){
		document.getElementById("state1").style.display="none";
		document.getElementById("state").style.display="";
		var this_=document.getElementById("country");
		id=this_.options[this_.selectedIndex].value;
		if(id){
			eval("doSimpleAction({'id':'customer','get':'ShowState','result':doDisplayOrderResult,'style':'boxLevel1','type':'item','params':'country_id="+id+"','message':'"+ page.template['TEXT_LOADING'] +"'})");
		}
	}
	function show_state_billing(do_this){
		var this_=do_this;
		id=this_.options[this_.selectedIndex].value;
		type=(this_.name.indexOf('billing')>0)?'B':((this_.name.indexOf('customer')>0)?'C':'D');

		if(id){
			eval("doSimpleAction({'id':'customer','get':'ShowStateBilling','result':doDisplayOrderResult,'style':'boxLevel1','type':'item','params':'country_id="+id+"&type="+type + "','message':'"+ page.template['TEXT_LOADING'] +"'})");
		}else{
			if(do_this.name.indexOf('billing')>0) document.getElementById("update_billing_state").innerHTML="Loading...";
			else if(do_this.name.indexOf('delivery')>0) document.getElementById("update_delivery_state").innerHTML="Loading...";
		}
	}
	function shipping_status(){
		if(document.getElementById('cOrderSHIPPINGview')){
		if(document.getElementById('disable_shipping').checked==true)
			document.getElementById('cOrderSHIPPINGview').style.display='none';
		else
			document.getElementById('cOrderSHIPPINGview').style.display='';
		}
	}
function str_trim(str){
	if(!str || typeof str != 'string')
		return null;
	return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
}
function showComment(obj)
{
	if(obj.checked)
		document.all.show_comments.style.display="";
	else
		document.all.show_comments.style.display="none";
}
		 function check_form() {
		  var error = 0;
		  var error_message = page.template['JS_ERROR'];
		  var customers_firstname = document.customers.firstname.value;
		  var customers_lastname = document.customers.lastname.value;
		  var customers_email_address = document.customers.email_address.value;
		  var customers_confirm_email_address = customers_email_address; //document.customers.confirm_email_address.value;
		 if(document.customers.second_email_address) var customers_second_email_address =  document.customers.second_email_address.value;
		 if(document.customers.second_telephone) var customers_second_telephone = document.customers.second_telephone.value;
		 if(document.customers.company)  var entry_company = document.customers.company.value;
		  if(customers_second_email_address!='')
		  	customers_second_confirm_email_address=customers_second_email_address; //document.customers.second_confirm_email_address.value;

		  var entry_street_address = document.customers.street_address.value;
		  var entry_postcode = document.customers.postcode.value;
		  var entry_city = document.customers.city.value;
		  if(document.customers.telephone)
		  {
		  	var customers_telephone = document.customers.telephone.value;
			is_customers_telephone=true;
		  }
		  else
		  {
		   is_customers_telephone=false;
		  }

		  if (document.customers.customers_id.value=="") {
			alert(page.template['JS_SELECT_CUSTOMER']);
			return false;
		  }
		  if (customers_firstname == "" || customers_firstname.length < page.template['ENTRY_FIRST_NAME_MIN_LENGTH']) {
			error_message = error_message + page.template['JS_FIRST_NAME'];
			error = 1;
		  }
		  if (customers_lastname == "" || customers_lastname.length < page.template['ENTRY_LAST_NAME_MIN_LENGTH']) {
			error_message = error_message + page.template['JS_LAST_NAME'];
			error = 1;
		  }
		  if (customers_email_address == "" || customers_email_address.length < page.template['ENTRY_EMAIL_ADDRESS_MIN_LENGTH']) {
			error_message = error_message + page.template['JS_EMAIL_ADDRESS'];
			error = 1;
		  }
		  if (customers_email_address!=customers_confirm_email_address) {
				error_message = error_message + page.template['JS_EMAIL_CONFIRM_ADDRESS'];
				error = 1;
		  }
		  if(customers_second_email_address!=''){
		  	if (customers_second_email_address!=customers_second_confirm_email_address){
				error_message = error_message + page.template['JS_SECONDEMAIL_CONFIRM_ADDRESS'];
				error = 1;
		  	}else if(customers_email_address==customers_second_email_address){
		  		error_message = error_message + page.template['JS_SECOND_EMAIL_ADDRESS_UNIQUE'];
				error = 1;
		  	}
		  }
		  if (entry_street_address == "" || entry_street_address.length < page.template['ENTRY_STREET_ADDRESS_MIN_LENGTH']) {
			error_message = error_message + page.template['JS_ADDRESS'];
			error = 1;
		  }
		  if (entry_postcode == "" || entry_postcode.length < page.template['ENTRY_POSTCODE_MIN_LENGTH']) {
			error_message = error_message + page.template['JS_POST_CODE'];
			error = 1;
		  }
		  if (entry_city == "" || entry_city.length < page.template['ENTRY_CITY_MIN_LENGTH']) {
			error_message = error_message +  page.template['JS_CITY'];
			error = 1;
		  }
		  if (document.customers.elements['state'] && document.customers.elements['state'].style.display != "none") {

			if (document.customers.state.value == '' || document.customers.state.value.length <  page.template['ENTRY_STATE_MIN_LENGTH'] ) {
			   error_message = error_message +  page.template['JS_STATE'];
			   error = 1;
			}
		  }else if (document.customers.elements['state1'] && document.customers.elements['state1'].style.display != "none") {
			if (document.customers.state1.value == '' || document.customers.state1.value.length <  page.template['ENTRY_STATE_MIN_LENGTH'] ) {
			   error_message = error_message +  page.template['JS_STATE'];
			   error = 1;
			}
		  }
		  if (document.customers.elements['country'] && document.customers.elements['country'].type != "hidden") {
			if (document.customers.country.value == 0) {
			  error_message = error_message +  page.template['JS_COUNTRY'];
			  error = 1;
			}
		  }
		if(is_customers_telephone)
		{
		  if (customers_telephone == "" || customers_telephone.length <  page.template['ENTRY_TELEPHONE_MIN_LENGTH']) {
			error_message = error_message +  page.template['JS_TELEPHONE'];
			error = 1;
		  }
		}
		  if (document.customers.elements['mobile'] && document.customers.elements['mobile'].type != "hidden") {
			if (check_phone(document.customers.elements['mobile'].value)==false) {
			  error_message = error_message +  page.template['JS_MOBILE'];
			  error = 1;
			}
		  }
		  submitProcessFlag=true;
		  if (error == 1) {
			alert(error_message);
			submitProcessFlag=false;
			return false;
		  } else {
		    submitProcessFlag=true;
			return true;
		  }
		}

		function check_phone(value){
		var rc = new RegExp("[~`!@#$%^&*_=|\/><,?;:+-]","i")
		var rn = new RegExp("[A-Z]","i");
		var res;
			if (value=="") return true;
			res= rn.exec(value);
			if (isNaN(res))
				return false;
			else
			{
				res=rc.exec(value);
				if (res!=null)
					return false;
			}
			return true;
		}
function hide_credit(mode){
		if(mode==1)document.edit_order.coupon.checked=false;
		document.getElementById("credit").style.display="none";
		if(document.edit_order.coupon.checked==true){
			document.getElementById("credit").style.display="";
		}else{
			document.edit_order.gv_redeem_code.value="";
			document.getElementById("credit_result").style.display="none";
		}
	}
	function submitFunction()
	{
		eval("doUpdateAction({'id':'new','get':'PaymentSubmit','imgUpdate':false,'type':'item','style':'boxRow','uptForm':'edit_order','customUpdate':doCounponDetails,'result':doStep,'message1':page.template['TEXT_LOADING']})");
	}
	function doCounponDetails()
	{
		command=page.link+'?AJX_CMD=PaymentSubmit&do_action=get_counpon_details';
		do_post_command("edit_order",command);
	}

	var errors='';
	var validation='';
function getCountryStates(){
	var this_=document.forms[page.formName].elements["entry_country"];
	id=this_.options[this_.selectedIndex].value;
	var qry_str="";
	if(id){
		eval("doSimpleAction({'id':'customer','get':'ShowState','result':doDisplayOrderResult,'style':'boxLevel1','type':'item','params':'country_id="+id+"','message':'"+ page.template['TEXT_LOADING'] +"'})");
	}
}
 function checkPWDStrength(obj){
	var pwd,splt,element;
	element=document.getElementById("strength_info");
	if (!element) return;
	if (!obj.linkField){
		obj.linkField=getLinkField("password_and_confirm");
	}
	if (obj.linkField=='') return;
	if (obj.linkField.error_text!=''){
		splt=obj.linkField.error_text.split("##");
	}
 	pwd=obj.value;
	if(pwd=='' || pwd.length==0)
	  element.innerHTML='';
	else if(obj.linkField.textbox_min_length>0 && pwd.length<obj.linkField.textbox_min_length)
		element.innerHTML=splt[2];
	else if(!check_password_strength(pwd))
		element.innerHTML=splt[2];
	else
		element.innerHTML='';
 }
	function getLinkField(key){
		var icnt;
		for(icnt in page.fieldsDesc){
			if (page.fieldsDesc[icnt].uniquename==key){
				return page.fieldsDesc[icnt];
			}
		}
		return '';
	}
	function validateForm(){
		var icnt,n,pass=true,fieldDesc,errorText='';
		page.errors=[];
		page.updateData={};

		for(icnt in page.fieldsDesc){
			fieldDesc=page.fieldsDesc[icnt];
			if (validation["check__"+fieldDesc['uniquename']]){
				pass&=validation["check__"+fieldDesc['uniquename']](fieldDesc);
			} else {
				pass&=validation.commonCheck(fieldDesc);
			}
		}
		if (pass){
			return true;
		} else {
			for (icnt=0,n=page.errors.length;icnt<n;icnt++){
				errorText+="* "+page.errors[icnt]+"\n";
			}
			alert(page.formErrText.replace(/--/g,"\n") + errorText);
			return false;
		}
	}
	var validation={};

	validation.commonCheck=function(fieldDesc){
		var value,element,error,splt,pass=true,icnt,n;
		if (fieldDesc['input_type']=="L") return true;
		var element=document.forms[page.formName].elements[fieldDesc['uniquename']];
		switch(fieldDesc['input_type']){
			case 'D':
				value=element.selectedIndex;
				if (fieldDesc['required']!='Y' && element.selectedIndex<=0){
					pass=false;
				}
				break;
			case 'O':
				value='';
				for (icnt=0,n=element.length;icnt<n;icnt++){
					if (element[icnt].checked) {
						value=element[icnt].value;
						break;
					}
				}
				if (fieldDesc['required']=='Y' && value==''){
					pass=false;
				}
				break;
			case 'C':
				value=element.value;
				if (fieldDesc['required']=='Y' && !element.checked){
					pass=false;
				}
				break;
			case 'A':
			default:
				value=element.value;
				if (fieldDesc['required']=='Y' && (value=='' || (fieldDesc["textbox_min_length"]>0 && value.length<fieldDesc["textbox_min_length"]) || (fieldDesc["textbox_max_length"]>0 && value.length>fieldDesc["textbox_max_length"]))){
					pass=false;
				}
				/*cartzone remove element.name=='entry_postcode' || Freeway Bug v233*/
            if (element.name=='customers_telephone' || element.name=='customers_second_telephone' || element.name=='customers_fax')
            {

            for (i=0; i<value.length; i++)
            {
                if ((value.charCodeAt(i)<48 || value.charCodeAt(i)>57) && value.charCodeAt(i) !== 44 )
                {

                pass=false;
                }
            }
				if (fieldDesc['required']=='Y' && (value=='' || (fieldDesc["textbox_min_length"]>0 && value.length<fieldDesc["textbox_min_length"]) || (fieldDesc["textbox_max_length"]>0 && value.length>fieldDesc["textbox_max_length"]))){
					pass=false;
				}


            }
		}
		if (!pass){
			if (fieldDesc["error_text"].indexOf("##")){
				splt=fieldDesc["error_text"].split("##");
				error=splt[0];
			} else {
				error=fieldDesc["error_text"];
			}
			page.errors[page.errors.length]=error;
		} else {
			page.updateData[fieldDesc["uniquename"]]=value;
		}
		return pass;
	}
	validation.check__customers_dob=function(fieldDesc){
		var value,pass=true;
		value=str_trim(document.forms[page.formName].elements[fieldDesc['uniquename']].value);
		if (fieldDesc["required"]=='Y' && (value=='' || !IsValidDate(value,page.dateFormat))){
			pass=false;
			page.errors[page.errors.length]=fieldDesc['error_text'];
		}
		if (pass){
			page.updateData[fieldDesc['uniquename']]=value;
		}
		return pass;

	}
	validation.check__customers_confirm_email_address=function(fieldDesc){
		var value,value1,pass=true;
		value=str_trim(document.forms[page.formName].elements['customers_email_address'].value);
		value1=str_trim(document.forms[page.formName].elements['customers_confirm_email_address'].value);
		if (str_trim(value)!=str_trim(value1)){
			pass=false;
			page.errors[page.errors.length]=fieldDesc['error_text'];
		}
		return pass;
	}
	validation.check__country_state=function(fieldDesc){
		var country,state,zone,st_val,error,splt,pass=true;
		country=document.forms[page.formName].elements['entry_country'];
		zone=document.forms[page.formName].elements['entry_zone_id'];
		state=document.forms[page.formName].elements['entry_state'];
		st_val=str_trim(state.value);


		if (fieldDesc['error_text']!=''){
			splt=fieldDesc['error_text'].split("##");
		}

		if (country.selectedIndex<=0){
			pass=false;
			page.errors[page.errors.length]=splt[0];
		} else if (zone.style.display!='none' && zone.selectedIndex<0){
			pass=false;
			page.errors[page.errors.length]=splt[1];
		} else if (state.style.display!='none' && (st_val=='' || (fieldDesc['textbox_min_length']>0 &&  st_val.length< fieldDesc['textbox_min_length']) || (fieldDesc['textbox_max_length']>0 && st_val.length > fieldDesc['textbox_max_length']))) {
			pass=false;
			page.errors[page.errors.length]=splt[2];
		}
		if (pass){
			page.updateData['entry_country']=country.value;
			page.updateData['entry_zone_id']=zone.value;
			page.updateData['entry_state']=st_val;
		}
		return pass;
	}
	validation.check__customers_referal=function(fieldDesc){
		var source,source_other,error,splt,pass=true;
		if (fieldDesc['error_text']!=''){
			splt=fieldDesc['error_text'].split("##");
		}

		source=document.forms[page.formName].elements['entry_source'].value;
		source_other=str_trim(document.forms[page.formName].elements['entry_source_other'].value);
		if (fieldDesc['error_text']!=''){
			splt=fieldDesc['error_text'].split("##");
		}
		if (fieldDesc['required']=='Y' && (source=='' || parseInt(source)<=0)) {
			pass = false;
			page.errors[page.errors.length]=splt[0];
		} else if (fieldDesc['required']=='Y' && source==999 && source_other==''){
			page.errors[page.errors.length]=splt[1];
			pass = false;
		} else {
			page.updateData["entry_source"]=source;
			page.updateData["entry_source_other"]=source_other;
		}
		return pass;
	}
	validation.check__password_and_confirm=function(fieldDesc){
		var password,password_confirm,error,splt,pass=true;
		if (fieldDesc['error_text']!=''){
			splt=fieldDesc['error_text'].split("##");
		}

		password=document.forms[page.formName].elements['customers_password'].value;
		password_confirm=document.forms[page.formName].elements['customers_password_confirm'].value;

		if (password=='' || (fieldDesc['textbox_min_length']>0 && password.length < fieldDesc['textbox_min_length']) || (fieldDesc['textbox_max_length']>0 && password.length > fieldDesc['textbox_max_length'])) {
			pass = false;
			page.errors[page.errors.length]=splt[0];
		} else if (password != password_confirm) {
			pass = false;
			page.errors[page.errors.length]=splt[1];
		}
		if (pass){
			page.updateData["entry_source"]=password;
			page.updateData["entry_source_other"]=password_confirm;
		}
		return pass;
	}

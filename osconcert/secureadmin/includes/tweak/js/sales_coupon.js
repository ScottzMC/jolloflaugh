// JavaScript Document
var selectedTab;
function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}
function list_detail(id){
	doEventAction({'id':"'"+id+"'",'get':'Items','style':'boxRow','type':'salCoupon','params':'cID='+id+''});
}
function doEventAction(action){
	page.lastAction={'result':doEventResult,'id':action.id};
	do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params);
}
function doEventResult(result,cid){
	var result_splt=result.split("@sep@");
	var element=document.getElementById('salCoupontotalContentResult');
	element.innerHTML=result_splt[0];
	page.lastAction=false;	
}
function getCouponTaxRate() {
	if (!page.taxRates) return 0;
		var element=document.getElementById('coupon_tax_class_id');
		if (!element) return 0;
		var selected_value=element.selectedIndex;
		var parameterVal=element.options[selected_value].value;
		if ((parameterVal>0) && (page.taxRates[parameterVal])) {
			return page.taxRates[parameterVal];
		}
		else
			return 0;
}

function updateCouponGross() { 
	var taxRate = getCouponTaxRate();
	var grossValue = document.getElementById('coupon_amount').value;
	var percentage = "";
	if(grossValue.lastIndexOf("%")!=-1){
		grossValue = grossValue.substring(0,grossValue.length-1);
		percentage = "%";
	}
	if (taxRate > 0) {
		grossValue = grossValue * ((taxRate / 100) + 1);
	}
	if(percentage=="%"){
		if(isNaN(doRound(grossValue, 4)))
			document.getElementById('coupon_amount_gross').value = doRound(grossValue, 4).toFixed(4);
		else 
			document.getElementById('coupon_amount_gross').value = doRound(grossValue, 4).toFixed(4)+"%";
	}
	else
		document.getElementById('coupon_amount_gross').value = doRound(grossValue, 4).toFixed(4);
}
function updateNet() {
	var taxRate = getCouponTaxRate();
	var netValue = document.getElementById('coupon_amount_gross').value;
	var percentage = "";
	if(netValue.lastIndexOf("%")!=-1){
		netValue = netValue.substring(0,netValue.length-1);
		percentage = "%";
	}
	if (taxRate > 0) {
		netValue = netValue / ((taxRate / 100) + 1);
	}
	if(percentage=="%")
		if(isNaN(doRound(netValue, 4)))
			document.getElementById('coupon_amount').value = doRound(netValue, 4).toFixed(4);
		else
			document.getElementById('coupon_amount').value = doRound(netValue, 4).toFixed(4)+"%";
	else
		document.getElementById('coupon_amount').value = doRound(netValue, 4).toFixed(4);
}
function compare(){ 
	var compareError="";
	if( parseInt(document.new_coupon.coupon_uses_user.value) > parseInt(document.new_coupon.coupon_uses_coupon.value) &&  (document.new_coupon.coupon_uses_coupon.value != 0) && (document.new_coupon.coupon_uses_coupon.value!= '')){
		compareError+="* "+page.template["ERROR_USES_PER_USER"]+"\n";
		document.new_coupon.coupon_uses_user.value = '';
		document.new_coupon.coupon_uses_user.focus();
	}
	if(compareError!="")
		alert(compareError);
	return false;
}
function check_ccode(id,val){
	doCodeAction({'id':id,'get':'CheckCode','style':'boxRow','type':'salevent','params':'rID='+id+'&code='+val+''});
}
function doCodeAction(action){
	page.lastAction={'result':doCodeResult,'id':action.id};
	do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params);
}
function doCodeResult(result,cid){
	var result_splt=result.split("@sep@");
	var str_result=result_splt[0].substr(0,3);
	if(str_result!="" && str_result=='Err')
		alert(result_splt[0]);
	page.lastAction=false;	
}
function apply_all(){
	var frm = document.new_coupon;
	if(frm.coupon_all.checked==true){
		document.getElementById('tabstrib').style.display='none';
		frm.coupon_selected.checked = false;
		frm.coupon_categories.value ='';
		frm.coupon_products.value = '';
		frm.coupon_alll.value = 1;
	}else{
	    document.getElementById('tabstrib').style.display='';
	}

}
function apply_selected(){
	if(document.new_coupon.coupon_selected.checked==true){
		document.new_coupon.coupon_all.checked = false;
		document.getElementById('tabstrib').style.display='';
		goto_ajax('p',document.new_coupon.coupon_categories.value,document.new_coupon.coupon_id.value);
	}else{
	   document.new_coupon.coupon_all.disabled= false;
	   document.getElementById('tabstrib').style.display='none';
    }
}
function goto_ajax(type,prev_ids,coupon_id){ 
		 if(selectedTab) selectedTab.className="tab";
		 selectedTab=document.getElementById(type);
		 selectedTab.className="tabSelect";
		 var frm = document.new_coupon;
		 
		if(type=='p' && frm.coupon_categories.value !="")
		   prev_ids = frm.coupon_categories.value ;
		else if(type=='pi' && frm.coupon_products.value !="" )
			prev_ids = frm.coupon_products.value;

		  
		if(frm.coupon_selected.checked==true){
			if(type == 'p') {
				doCatAction({'id':coupon_id,'get':'LoadCategories','style':'boxRow','type':'salevent','params':'rID='+coupon_id+'&type='+type+'&prev_ids='+prev_ids+''});
			}
			else {
				doCatAction({'id':coupon_id,'get':'LoadItems','style':'boxRow','type':'salevent','params':'rID='+coupon_id+'&type='+type+'&prev_ids='+prev_ids+''});
			}
		}
}
function changeStyle(element,mode){
	if (selectedTab && selectedTab.id==element.id) return;
}

function doCatAction(action){
	page.lastAction={'result':doCatResult,'id':action.id};
	do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params);
}
function doCatResult(result,cid){
	var result_splt=result.split("@sep@");
	var element=document.getElementById('gen_id');
	element.innerHTML=result_splt[0];
	page.lastAction=false;	
}
function load_selected(type){
	con = document.getElementById('cat_coupon_avail');
	category = document.getElementById('category');
	hid_value1 ="";
	dublicate = '';
	count = '';
	
	if(con.selectedIndex == -1 ){ return;
	}else{
		con_len = con.length;
		for(i=0;i<con.length;i++){
		   if(con.options[i].selected){
				var option = document.createElement('option');
					option.text = con.options[i].text;
					option.value = con.options[i].value;
			 		 sel = con.selectedIndex;
			  		
				for(j=0;j<category.length;j++){
				if(category[j].value ==option.value)
			   		dublicate = 'exists';
				}	
				if( dublicate != 'exists'){ count = count + 1;
		  			 try {
			   			category.add(option, null); // standards compliant; doesn't work in IE
			 			}
	  			 	catch(ex) {
	 		  			category.add(option); // IE only
	 				 }
			
				 }else return;}}
	  } 
	  for(jcnt=0;jcnt<con.length;jcnt++){ 
	  if(con.options[jcnt].selected)
	  	con.remove(con.selectedIndex);
	  }
	  if(!con[sel]) sel=0;
	 	con.selectedIndex = sel;
	 	category_len = category.length;
	  for(icnt=0;icnt<category_len;icnt++)
	  	  hid_value1= hid_value1 + ','+ document.getElementById('category')[icnt].value;
		  
	  hid_value1 = hid_value1.substring(1);
	  
	  //Loading hidden values to appropriate selected categories
	  if(type=='p')
	  		document.new_coupon.coupon_categories.value = hid_value1;
		
		if(category_len>0) category.selectedIndex = 0;
	}
function remove_selected(type){
	category = document.getElementById('category');
	con = document.getElementById('cat_coupon_avail');
	hid_value1 ="";
	count_r = '';
	if(category.selectedIndex== -1) return; 
	else{
	 	for(i=0;i<category.length;i++){
			if(category.options[i].selected){
				var option = document.createElement('option');
	 			option.text = category.options[i].text;
				option.value= category.options[i].value;
				sel = category.selectedIndex;
				count_r = count_r + 1;
		
		try {
		  con.add(option, null); 
		 	}
	  	catch(ex) {
	 	  	con.add(option); 
	 	 	}
		  }
		 }	
		}
		for(jcnt=0;jcnt<category.length;jcnt++){ 
		if(category.options[jcnt].selected)
		   category.remove(category.selectedIndex);
		 }
		if(!category[sel]) sel = 0;
		category.selectedIndex = sel;
		
		category_len = category.length;
		for(icnt=0;icnt<category_len;icnt++)
	  	  hid_value1= hid_value1 + ','+ document.getElementById('category')[icnt].value;
		  
	hid_value1 = hid_value1.substring(1);
	if(type=='p')
	  		document.new_coupon.coupon_categories.value = hid_value1;
			
		if(con.length>0) con.selectedIndex = 0;
}

function remove_all(type){
	category = document.getElementById('category');
	con = document.getElementById('cat_coupon_avail');
	cat_len = category.length; 
	for(i=0;i<cat_len;i++){
		var option = document.createElement('option');
		option.text = document.getElementById('category')[i].text;
		option.value = document.getElementById('category')[i].value;
		try {
		  con.add(option, null); 
		}
		catch(ex) {
			con.add(option); 
		}
	}
	 document.getElementById('cat_coupon_avail').selectedIndex = 0;
	 while(category.options.length>0){
		category.remove(category.options.length - 1);
	 }
	document.new_coupon.coupon_categories.value = '';

}

function remove_all_items(type){
	items = document.getElementById('items');
	con_item = document.getElementById('items_coupon_avail');
	items_len = items.length;
	
	
	 	for(i=0;i<items_len;i++){
		var option = document.createElement('option');
	 	option.text = document.getElementById('items')[i].text;
	 	option.value = document.getElementById('items')[i].value;
		try {
		  con_item.add(option, null); 
		 	}
	  	catch(ex) {
	 	  	con_item.add(option); 
	 	 	}
		}
	 while(items.options.length>0){
			items.remove(items.options.length - 1);
	 }
	document.getElementById('items_coupon_avail').selectedIndex = 0;
	document.new_coupon.coupon_products.value = '';

}

function remove_selected_items(type){
	items = document.getElementById('items');
	con_item = document.getElementById('items_coupon_avail');
	hid_item_sel = '';
	
	if(items.selectedIndex == -1) return; //alert('<?PHP echo ERROR_SELECT_ITEMS; ?>');
	else{
	   for(i=0;i<items.length;i++){
	   	   if(items.options[i].selected){
	   		var option = document.createElement('option');
			option.text = items.options[i].text;
			option.value = items.options[i].value;
			sel = items.selectedIndex;
		try {
		  con_item.add(option, null); 
		 	}
	  	catch(ex) {
	 	  	con_item.add(option); 
	 	 	}
		  }	
		}
	}
	 for(jcnt=0;jcnt<items.length;jcnt++){
	 if(items.options[jcnt].selected)
	  	items.remove(items.selectedIndex);
	 }
	 if(!items[sel]) sel =0;
		  items.selectedIndex = sel;
			  
	  items_len = items.length;
	  for(icnt=0;icnt<items_len;icnt++)
	  	  hid_item_sel= hid_item_sel + ','+ document.getElementById('items')[icnt].value;
		  
	  hid_item_sel = hid_item_sel.substring(1);
	  if(type=='pi')
	  		document.new_coupon.coupon_products.value = hid_item_sel;
			
		if(con_item.length>0) con_item.selectedIndex = 0;
}

function load_all(type){
	category = document.getElementById('category');
	con = document.getElementById('cat_coupon_avail');
	len = document.getElementById('cat_coupon_avail').length;
	hid_value="";
	var option = document.createElement('option');
	for(icnt=0;icnt<len;icnt++){
		var option = document.createElement('option');
		option.text = document.getElementById('cat_coupon_avail')[icnt].text;
		option.value = document.getElementById('cat_coupon_avail')[icnt].value;
		try {
		   category.add(option, null); // standards compliant; doesn't work in IE
		}
	  	catch(ex) {
	 	   category.add(option); // IE only
	 	}
		hid_value =   hid_value + ',' + document.getElementById('cat_coupon_avail')[icnt].value ;
	 }
	 //}
	 document.getElementById('category').selectedIndex = 0;
	 while(con.options.length>0){
		con.remove(con.options.length - 1);
	}
	 
	 hid_value = hid_value.substring(1);
	 // loading hidden value to appropriate categories
	 if(type=='p')
	 	document.new_coupon.coupon_categories.value = hid_value;

	}
	
function load_selected_items(type){
	con_item = document.getElementById('items_coupon_avail');
	items = document.getElementById('items');
	hid_item_sel ="";
	dublicate = '';
	count ='';
	
	if(con_item.selectedIndex == -1 ){ return;
	}else{
		for(i=0;i<con_item.length;i++){
		 if(con_item.options[i].selected){
				var option = document.createElement('option');
				option.text = con_item.options[i].text;
				option.value = con_item.options[i].value;
			  	sel = con_item.selectedIndex;
			  
			for(j=0;j<items.length;j++){
				if(document.getElementById('items')[j].value == option.value)
			     	dublicate = 'exists';
			}
			if( dublicate != 'exists'){
				
				count = count +1;
				try {
					items.add(option, null); // standards compliant; doesn't work in IE
				}
	  			catch(ex) {
	 				items.add(option); // IE only
	 			}
			 	
		 	}else return;
		  }
		}
	  } 
	  for(jcnt=0;jcnt<con_item.length;jcnt++){
	  if(con_item.options[jcnt].selected)
	  	con_item.remove(con_item.selectedIndex)
	  }
	  if(!con_item[sel]) sel = 0;
	  con_item.selectedIndex = sel;
	  items_len = items.length;
	  for(icnt=0;icnt<items_len;icnt++)
	  	  hid_item_sel= hid_item_sel + ','+ document.getElementById('items')[icnt].value;
		  
	  hid_item_sel = hid_item_sel.substring(1);
	  //Loading hidden values to appropriate selected products/items
	  if(type=='pi')
	  		document.new_coupon.coupon_products.value = hid_item_sel;
		
		if(items.length>0) items.selectedIndex = 0;
	}
 	
	
	function load_all_items(type){ 
	items = document.getElementById('items');
	con_item = document.getElementById('items_coupon_avail');
	len_item = document.getElementById('items_coupon_avail').length;
	hid_items_all="";
	var option = document.createElement('option');
	for(icnt=0;icnt<len_item;icnt++){
		//alert(document.getElementById('cat_coupon_avail')[icnt].value);
		var option = document.createElement('option');
		option.text = document.getElementById('items_coupon_avail')[icnt].text;
		option.value = document.getElementById('items_coupon_avail')[icnt].value;
		try {
		   items.add(option, null); // standards compliant; doesn't work in IE
		}
	  	catch(ex) {
	 	   items.add(option); // IE only
	 	}
		hid_items_all =   hid_items_all + ',' + document.getElementById('items_coupon_avail')[icnt].value ;
	 }
	 document.getElementById('items').selectedIndex = 0;
	 while(con_item.options.length>0){
			con_item.remove(con_item.options.length - 1);
		}
	 
	 hid_items_all = hid_items_all.substring(1);
	 // loading hidden value to appropriate categories
	 if(type=='pi')
	 	document.new_coupon.coupon_products.value = hid_items_all;
	}
function apply_coupon_categories(cat,value) {	
	if(document.new_coupon.coupon_selected.checked==true) {
		document.getElementById("tabstrib").style.display="";
		goto_ajax(cat,value);
	 } 
}
function doEmailEditor(){
	if (page.editorLoaded) return;
	page.editorControls[0]="message";
	textEditorInit();
}
function doCustomActions(type,func)
{
	if(func.indexOf('#')>0) {
		var funParam;
		var param1="";
		var param2="0";
		funParam=func.split('##');
		if(funParam[1]!="") param1=funParam[1];
		if(funParam[2]!="") param2=funParam[2];
		apply_coupon_categories(param1,param2);
	}
	else
		eval(func+'()');
}
function check_form(){
	var coupon_id = document.new_coupon.coupon_id.value;
	var frm = document.new_coupon;
	var lastError='';
	var lang;
	for(lang in page.languages){
		if (frm.elements["coupon_name["+page.languages[lang].id+"]"].value=="" || str_trim(frm.elements["coupon_name["+page.languages[lang].id+"]"].value)==""){
			lastError+="* "+page.template["VALID_COUPON_NAME"]+"\n";
			break;
		} 
	}
	if(str_trim(frm.coupon_code.value)=='' || frm.coupon_code.value==''){
		lastError+="* "+page.template["VALID_COUPON_CODE"]+"\n";
	}
	if(str_trim(frm.coupon_min_order.value)=='' || frm.coupon_min_order.value=='') {
		lastError+="* "+page.template["VALID_MIN_ORDER"]+"\n";
	}
	else if(isNaN(frm.coupon_min_order.value)){
		lastError+="* "+page.template["MIN_ORDER_MUST_BE_NUMERIC"]+"\n";
	}
	
	if(str_trim(frm.coupon_amount.value)=='' || frm.coupon_amount.value=='' ){
		lastError+="* "+page.template["COUPON_AMOUNT_REQUIRED"]+"\n";
	}
	if(lastError!=""){
		alert(lastError);
		return false;	
	} 
	return true;
}
function doItemUpdate(mode) {
	var data='';
	if(mode!="") {
		if(mode=='Preview')
			var form=document.forms["new_coupon"];
		else 	
			var form=document.forms["preview_coupon"];
	}

	if(mode=='Preview') {
		for(lang in page.languages){
			data+="coupon_name["+page.languages[lang].id+"]="+encodeURIComponent(document.getElementById("coupon_name["+page.languages[lang].id+"]").value)+"&";
			data+="coupon_desc["+page.languages[lang].id+"]="+encodeURIComponent(document.getElementById("coupon_desc["+page.languages[lang].id+"]").value)+"&";
		}
		data+="coupon_startdate_day="+form["coupon_startdate_day"].value+"&";
		data+="coupon_startdate_month="+form["coupon_startdate_month"].value+"&";
		data+="coupon_startdate_year="+form["coupon_startdate_year"].value+"&";
		data+="coupon_finishdate_day="+form["coupon_finishdate_day"].value+"&";
		data+="coupon_finishdate_month="+form["coupon_finishdate_month"].value+"&";
		data+="coupon_finishdate_year="+form["coupon_finishdate_year"].value+"&";
		data+="coupon_alll="+form["coupon_alll"].value+"&";
		if(form["coupon_free_ship"].checked)
			data+="coupon_free_ship=S&";
		else
			data+="coupon_free_ship=F&";
		//coupons
		if(form["coupon_active"].checked)
			data+="coupon_active=Y&";
		else
			data+="coupon_active=N&";
		//end coupons
	}
	if(mode=='Update') {
		for(lang in page.languages){
			data+="coupon_name["+page.languages[lang].id+"]="+encodeURIComponent(document.getElementById("coupon_name["+page.languages[lang].id+"]").value)+"&";
			data+="coupon_desc["+page.languages[lang].id+"]="+encodeURIComponent(document.getElementById("coupon_desc["+page.languages[lang].id+"]").value)+"&";
		}
		data+="coupon_startdate="+form["coupon_startdate"].value+"&";
		data+="coupon_finishdate="+form["coupon_finishdate"].value+"&";
		data+="coupon_free_ship="+form["coupon_free_ship"].value+"&";
		//coupons
		data+="coupon_active="+form["coupon_active"].value+"&";
		//end coupons
	}
	
	data+="coupon_tax_class_id="+form["coupon_tax_class_id"].value+"&";
	data+="coupon_amount="+form["coupon_amount"].value+"&";
	data+="coupon_amount_gross="+form["coupon_amount_gross"].value+"&";
	data+="coupon_min_order="+form["coupon_min_order"].value+"&";
	data+="coupon_code="+form["coupon_code"].value+"&";
	data+="coupon_uses_coupon="+form["coupon_uses_coupon"].value+"&";
	data+="coupon_uses_user="+form["coupon_uses_user"].value+"&";
	data+="coupon_uses_order="+form["coupon_uses_order"].value+"&";
	data+="coupon_categories="+form["coupon_categories"].value+"&";
	data+="coupon_products="+form["coupon_products"].value+"&";
	data+="coupon_id="+form["coupon_id"].value+"&";
	command=page.link+"?AJX_CMD="+mode+"&RQ=A&" + new Date().getTime();
	xmlHttp.open("POST", command, true);
	xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
	xmlHttp.onreadystatechange = handleServerResponse;
	xmlHttp.send(data);
}
function validateEmail(){
	var mailError="";
	var customers_email_address = document.mail.customers_email_address.value;
	if(customers_email_address=='')
		mailError+="* "+page.template["SELECT_CUSTOMERS"]+"\n";
	if(document.mail.subject && document.mail.subject.value=="")
		mailError+="* "+page.template["SELECT_SUBJECT"]+"\n";
	if(mailError!="") {
		alert(mailError);
		return false;
	}
	return true;
}
function doMailUpdate(mode) {
	var data="";
	if(mode!="") {
		if(mode=='PreviewMail')
			var frm=document.mail;
		if(mode=='SendMail')
			var frm=document.preview_mail;
	}
	data+="customers_email_address="+frm["customers_email_address"].value+"&";
	data+="from="+frm["from"].value+"&";
	data+="subject="+frm["subject"].value+"&";
	data+="message="+frm["message"].value+"&";
	data+="coupon_id="+frm["coupon_id"].value+"&";
	command=page.link+"?AJX_CMD="+mode+"&RQ=A&" + new Date().getTime();
	xmlHttp.open("POST", command, true);
	xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
	xmlHttp.onreadystatechange = handleServerResponse;
	xmlHttp.send(data);
}

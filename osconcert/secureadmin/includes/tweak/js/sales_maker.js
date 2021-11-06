function discount_action(){
	var discount_type=document.sales_maker.elements['discount_type'];
	if(discount_type) {
		for(i=0;i<discount_type.length;i++) {
			if(discount_type[i].checked && discount_type[i].value=='C') {
				document.getElementById('choice_text').style.display="";
				document.getElementById('choice_warning').style.display="";
			} else  {
				document.getElementById('choice_text').style.display="none";
				document.getElementById('choice_warning').style.display="none";
			}
		}
	}
		
}

function CategoryClick1(cat_path,cat_id,category_name){ 
	add_categories(cat_id,category_name);
	display_header();
}

function add_categories(cat_id,category_name){
	if(document.getElementById('check_box_'+cat_id).checked==true){
	/*
	insert_value="<div class='main' onclick='javascript: fetch_product("+cat_id+");' id='cat_"+cat_id+"'><label for='category_"+cat_id+"'><input name='category_list["+cat_id+"][]' id='category_"+cat_id+"' value='"+cat_id+"' checked type='checkbox'>&nbsp;"+category_name+"</label></div>"+
				"<div class='main' id='ajax_cat_"+cat_id+"'></div>";
	if(document.getElementById('ajax_sales_maker')){
		document.getElementById('ajax_sales_maker').innerHTML += insert_value;
	}
	*/
	var cat_div = document.createElement("div");
	var cat_ajax_div = document.createElement("div");	
	cat_div.className="main";
	cat_div.id='cat_'+cat_id;
	//cat_div.onclick=function(){ fetch_product(cat_id); };
	cat_div.onclick=function() { doProductAction({'id':cat_id,'get':'FetchProduct','style':'boxRow','type':'disproduct','params':'cID='+cat_id+''}); };
	cat_ajax_div.className="main";
	cat_ajax_div.id="disproduct"+cat_id;
	cat_div.innerHTML="<label for='category_"+cat_id+"'><input name='category_list[]' id='category_"+cat_id+"' value='"+cat_id+"' checked type='checkbox'>&nbsp;"+category_name+"</label>";
	document.getElementById('ajax_sales_maker').appendChild(cat_div);
	document.getElementById('ajax_sales_maker').appendChild(cat_ajax_div);
	}else if(document.getElementById('cat_'+cat_id)){
	document.getElementById('cat_'+cat_id).parentNode.removeChild(document.getElementById('cat_'+cat_id));
	document.getElementById('disproduct'+cat_id).parentNode.removeChild(document.getElementById('disproduct'+cat_id));
	}
}

function display_header(){
	if(document.getElementById('ajax_sales_maker').getElementsByTagName("div").length==1)
		document.getElementById('head_ajax_sales_maker').style.display='none';
	else
		document.getElementById('head_ajax_sales_maker').style.display='';
}

function all_category(){
	if(document.getElementById('all_categories').checked==true){
		for(i=0;i<document.getElementsByName('categories[]').length;i++)
			document.getElementsByName('categories[]')[i].disabled=true;
	document.getElementById('ajax_sales_maker').style.display='none';		
	}else{
		document.getElementById('ajax_sales_maker').style.display='';		
		for(i=0;i<document.getElementsByName('categories[]').length;i++)
			document.getElementsByName('categories[]')[i].disabled=false;
	}
}

function chk_category(category_id,products_id){
	prd_chk = true;
	for(i=0;i<document.getElementsByName('products['+category_id+'][]').length;i++)
		if(document.getElementsByName('products['+category_id+'][]')[i].checked==false)
			prd_chk = false;

	if(prd_chk==false){
		document.getElementById('category_'+category_id).checked=false;
	}
	else {
		document.getElementById('category_'+category_id).checked=true;
	}
}

function check_form(){
	var lastError='';
	var date_error=true;
	obj=document.sales_maker.name.value;
	if(obj=='' || str_trim(obj) == ''){
		lastError+="* "+page.template["ERR_SOURCES_NAME"]+"\n";
	}
		
		var discount_type=document.sales_maker.elements['discount_type'];
		for(i=0;i<discount_type.length;i++){ 
			if(discount_type[i].checked && discount_type[i].value=='C') {
				if(document.getElementById('txt_choice_text').value=='' || str_trim(document.getElementById('txt_choice_text').value)=='')
					lastError+="* "+page.template["ERR_CHOICE_TEXT"]+"\n";
				if(document.getElementById('txt_choice_warning').value=='' || str_trim(document.getElementById('txt_choice_warning').value)=='')
					lastError+="* "+page.template["ERR_CHOICE_WARNING"]+"\n";
			}
		}
		if(isNaN(document.sales_maker.deduction.value) )
			lastError+="* "+page.template["ERR_INVALID_DEDUCTION"]+"\n";
		if(isNaN(document.sales_maker.from.value))
			lastError+="* "+page.template["ERR_INVALID_PRODUCTS_RANGE_FROM"]+"\n";
		if(isNaN(document.sales_maker.to.value))
			lastError+="* "+page.template["ERR_INVALID_PRODUCTS_RANGE_TO"]+"\n";
			
		if(document.sales_maker.end.value!=''){
		if(((document.sales_maker.start.value).indexOf("-")!=-1)){
			date_error=isValidDate(date_format(document.sales_maker.start.value,'','Y-m-d')); 
			}
			else {
				date_error=false;
				lastError+="* "+page.template["ERR_INVALID_START_DATE"]+"\n";
			}
		}
		
		if(document.sales_maker.end.value!='') {
			if(((document.sales_maker.end.value).indexOf("-")!=-1)){
				date_error=isValidDate(date_format(document.sales_maker.end.value,'','Y-m-d'));
			}
			else{
				lastError+="* "+page.template["ERR_INVALID_END_DATE"]+"\n";
				date_error=false;
			}
		}
		
		if(date_error==true){
			var st_date=date_format(document.sales_maker.start.value,'','y-m-d',true);
			var end_date=date_format(document.sales_maker.end.value,'','y-m-d',true);
			if(st_date>end_date){
				lastError+="* "+page.template["ERR_START_DATE"]+"\n";
			}
		}
		
	if(lastError!=''){
		alert(lastError);
		close_obj=true;
		form_obj=true;
		return false;
	}
	return true;
}

function doItemUpdate(){
	var data='';
	var form=document.forms["sales_maker"];
	var discount_type="";
	var category_list=categories=products=unsel_list=prd_list=sel_prd_list="";
	
	data+="name="+form["name"].value+"&";
	
	for(i=0;i<form["discount_type"].length;i++) {
		if(form['discount_type'][i].checked) {
			discount_type=form["discount_type"][i].value;
			break;
		}
	}
	
	data+="discount_type="+discount_type+"&";
	if(discount_type=='C') {
		data+="txt_choice_text="+form["txt_choice_text"].value+"&";
		data+="txt_choice_warning="+form["txt_choice_warning"].value+"&";
	}
	
	if(form["all_categories"].checked) data+="all_categories=1"+"&";
	data+="type="+form["type"].value+"&";
	data+="deduction="+form["deduction"].value+"&";
	data+="from="+form["from"].value+"&";
	data+="to="+form["to"].value+"&";
	data+="condition="+form["condition"].value+"&";
	data+="prd_sale_id="+form["prd_sale_id"].value+"&";
	data+="start="+form["start"].value+"&";
	data+="end="+form["end"].value+"&";
	if(form["apply_to_cross_sale"].checked)
		data+="apply_to_cross_sale=Y&";
	else
		data+="apply_to_cross_sale=N&";
	if(document.getElementsByName('category_list[]')) {
		if(document.getElementsByName('category_list[]').length>0) {
			for(j=0;j<document.getElementsByName('category_list[]').length;j++) {
				if(document.getElementsByName('category_list[]')[j].checked) {
					categories+=document.getElementsByName('category_list[]')[j].value+',';
					category_list+=document.getElementsByName('category_list[]')[j].value+',';
					//alert('cat='+categories+'cat_list='+category_list);
				}
				else {
					category_list+=document.getElementsByName('category_list[]')[j].value+',';
					unsel_list+=document.getElementsByName('category_list[]')[j].value+',';
					prd_list=form.elements['products['+document.getElementsByName('category_list[]')[j].value+'][]'];
					if(prd_list.length>0) {
						for(i=0;i<prd_list.length;i++){
							if(form.elements['products['+document.getElementsByName('category_list[]')[j].value+'][]'][i].checked){
								sel_prd_list+=(form.elements['products['+document.getElementsByName('category_list[]')[j].value+'][]'][i].value)+',';
							}
						}
					}
					else {
						if(prd_list.checked){
							sel_prd_list+=prd_list.value+',';
						}
					}
				}
			}
		}
		
	/*	else
			if(form.elements['category_list[]'].checked) {
					categories+=form.elements['category_list[]'].value+',';
					category_list+=form.elements['category_list[]'].value+',';
			}
    */
  /*   else {//alert(form.elements['category_list[]'].checked);
			//if(form.elements['category_list[]'].checked) {
					//categories+=form.elements['category_list[]'].value+',';
					category_list+=form.elements['category_list[]'][j].value+',';
					unsel_list+=form.elements['category_list[]'][j].value+',';
					prd_list=form.elements['products['+form.elements['category_list[]'][j].value+'][]'];
                    
					if(prd_list.length>0) {
						for(i=0;i<prd_list.length;i++){
							sel_prd_list+=(form.elements['products['+form.elements['category_list[]'][j].value+'][]'][i].value)+',';
						}
					}
					else {
                        if(prd_list.checked)
						sel_prd_list+=prd_list.value+',';
					}
			//}
            }
   */
	}

	if(categories!="") categories=categories.substr(0,(categories.length-1));
	if(category_list!="") category_list=category_list.substr(0,(category_list.length-1));
	if(unsel_list!="") {
		unsel_list=unsel_list.substr(0,(unsel_list.length-1));
		form['unsel_list'].value=unsel_list;
	}
	if(sel_prd_list!="") {
		sel_prd_list=sel_prd_list.substr(0,(sel_prd_list.length-1));
		form['sel_prd_list'].value=sel_prd_list;
	}
	
	data+="category_list="+category_list+"&";
	data+="categories="+categories+"&";
	data+="unsel_list="+form['unsel_list'].value+"&";
	data+="sel_prd_list="+form['sel_prd_list'].value+"&";

	command=page.link+"?AJX_CMD=Update&RQ=A&" + new Date().getTime();
	xmlHttp.open("POST", command, true);
	xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
	xmlHttp.onreadystatechange = handleServerResponse;
	xmlHttp.send(data);
}

function doProductAction(action){
	if(document.getElementById('disproduct'+action.id).innerHTML==''){
		document.getElementById('category_'+action.id).checked=true;
		page.lastAction={'result':doProductResult,'id':action.id};
		do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params);
	}
	else {
		chk_category(action.id);
	}
}

function doProductResult(result,cid){
	var result_splt=result.split("@sep@");
	var element=document.getElementById('disproduct'+cid.id);
	element.innerHTML=result_splt[0];
	page.lastAction=false;	
}

function validateCopyDetail() {
	var form=document.salmakeCopySubmit;
	var copyError="";
	if(form.name && form.name.value=="") {
		copyError+="* "+page.template["COPY_NAME_REQUIRED"]+"\n";
		alert(copyError);
		return false;
	}
	return true;
}
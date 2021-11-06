<script language='javascript'>
var product = {
	'id':{{PRODUCT_ID}},
	'stock':{{PRODUCT_STOCK}},
	'priceBreaks':{{PRODUCT_PRICE_BREAKS}},
	'priceAttr':{{PRODUCT_PRICE_ATTR}},
	'currency':{{CURRENCY_DETAIL}},
	'priceCalc':{{PRICE_CALC_FUNCTIONS}},
	'ajxurl':{{AJX_URL}},
	valid:false,
	ajxCall:false,
	errMsgs:{{ERROR_MSGS}},
	'saleMaker':{{SALEMAKER_DATAS}},
	'forced':{{FORCED_ITEMS}},
	'cPath':{{CPATH}},
	'discount_id':0
}
var prevQuanRow;

function setQuantity(object,quan){
	var icnt,n;
	if(!document.getElementById("tablePriceBreaks")) return;
	if(object==""){object=document.getElementById("tablePriceBreaks").rows[1];}
	object.className="moduleRow";//
	document.getElementById("qty").value=quan;
	if(prevQuanRow && prevQuanRow!=object) {
		prevQuanRow.className="moduleRow";prevQuanRow=object;
	}
	setTotalPrice();
	if(product.priceAttr.enabled) {
		for(icnt=0, n=product.priceAttr.count; icnt < n; icnt++){
			if(document.getElementById("attrValues["+icnt+"]").selectedIndex > 0) {
				price=getAttributePrice(icnt);
				setAttrDisplayPrice(price,icnt);
			}
		}
	}
	checkStock("stockCheckQuan");
}

function setTotalPrice(){
	var icnt,n,totalPrice,exString='';
	element=document.getElementById("totalProductsPrice");
	if(!element || !product["priceCalc"].length){return;}
	totalPrice=0;
	exString+="totalPrice=";
	for(icnt=0, n=product["priceCalc"].length; icnt < n; icnt++){
		exString+=product["priceCalc"][icnt]+"+";
	}
	eval(exString+"0");
	element.innerHTML=formatCurrency(totalPrice);
}

function selectAttribute(object,index){
	var price=0;
	if(object.selectedIndex > 0){
		price=getAttributePrice(index);
	}
	setAttrDisplayPrice(price,index);
	setTotalPrice();
	if(object.selectedIndex>0){
		checkStock("stockCheckAttr");
	}
}

function setAttrDisplayPrice(price,index){
	if(!document.getElementById("attrPrice"+index)){return;}
	if(price!=0){
		document.getElementById("attrPrice"+index).innerHTML=(price>0?'+':'-')+formatCurrency(Math.abs(price));
	}
	else {
		document.getElementById("attrPrice"+index).innerHTML="&nbsp;";
	}
}

function getProductsPrice(){
	var price=0,quan,salemaker,id=0,icnt,n;

	quan=getProductsQuantity();// value in the qty field
// there may be a better way of doing this but I am unable to find it.
// javascript will not handle element names so you must manipulate the object that
// holds the data obj = (product["priceBreaks"].prices);
    var discount = 0;
obj = (product["priceBreaks"].prices);
for (var key in obj) {
loop_1:
    if (Object.prototype.hasOwnProperty.call(obj, key)) {
        var val = obj[key]; 
        // use val as discount key as quantity
loop_2:
		if (key > quan){break loop_1;}else {discount = val;}
		//console.log(key + " " + quan +"a" +val);	
    }
}


	//if (product["priceBreaks"].enabled && quan > 1 && product["priceBreaks"].prices[quan]){
	if (product["priceBreaks"].enabled && quan > 1 ){
		price=parseFloat((document.getElementById("price").value - discount))*quan;
	}
	else if(product["saleMaker"].enabled){
		
		/* bug-fix! */
		salemaker=document.getElementsByName("salemaker_id");
		for (icnt=0,n=salemaker.length;icnt<n;icnt++){
			if (salemaker[icnt].checked) {
				id=parseInt(salemaker[icnt].value);
				break;
			}
		}
		
		if(id > 0){
			price=product["saleMaker"].sales[id].price*quan;
		}
		else {
			price=parseFloat(document.getElementById("price").value)*quan;
		}
	}
	else {
		price=parseFloat(document.getElementById("price").value)*quan;
	}
	return price;
}

function getAttributePrice(index){
	var price=0,fromIndex,toIndex,icnt,quan,element,element1;
	if(!product.priceAttr.enabled){return price;}
	if(index==-1) {
		fromIndex=0;
		toIndex=product.priceAttr.count-1;
	}
	else {
		fromIndex=toIndex=index;
	}
	quan=getProductsQuantity();
	for(icnt=fromIndex; icnt <= toIndex; icnt++){
		element=document.getElementById("attrIds["+icnt+"]");
		element1=document.getElementById("attrValues["+icnt+"]");
		if(!element || !element1){break;}
		attrValue=parseInt(element1.value);
		if(attrValue>0) {
			price+=(product.priceAttr.prices["op"+element.value][attrValue]*quan);
		}
	}
	return price;
}

function getProductsQuantity(){
	var quan=1;
	if (document.getElementById("qty")){
		quan=parseInt(document.getElementById("qty").value);
	}
	if(!quan || isNaN(quan) || quan<=0){quan=1;}
	return quan;
}

function selectDiscount(id){
	if(product["saleMaker"].sales[id]){
		document.getElementById("salemaker_info").innerHTML=product["saleMaker"].sales[id].warning;
		document.getElementById("salemaker_info").style.display="block";
	}
	else {
		document.getElementById("salemaker_info").innerHTML='';
		document.getElementById("salemaker_info").style.display="none";
	}
	product.discount_id = parseInt(id);
	setTotalPrice();
}

function setTotalPrices(id){
	var icnt,n,totalPrice,exString='';
	element=document.getElementById("totalProductsPrice");
	if(!element || !product["priceCalc"].length){return;}
	totalPrice=0;
	exString+="totalPrice=";
	for(icnt=0, n=product["priceCalc"].length; icnt < n; icnt++){
		exString+=product["priceCalc"][icnt]+"+";
	}
	eval(exString+"0");
	element.innerHTML=formatCurrency(totalPrice);
	
	getPriceInSession(id,totalPrice);
}

function rowOverEffect2(object){if(object.className == "moduleRow"){object.className = "moduleRowOver";}}
function rowOutEffect2(object){if(object.className == "moduleRowOver"){object.className = "moduleRow";}}

function checkStock(idMsg){
	var ids,command,quan;
	quan=getProductsQuantity();
	if(quan>product.stock) {
		toggleOutStock(false);
		return;
	}
	if(!product.valid){toggleOutStock(true);}
	if(!product.priceAttr.enabled){return;}

	if(product.ajxCall){return;}
	ids=getAttributeValues(true);
	if(ids==""){return;}
	
	command=product.ajxurl+"&command=check_attrib_stock&products_id="+product.id+"&attrib_ids="+ids+"&quan="+quan;
	
	toggleLoadMsgs(idMsg,2);
	product.valid=false;
	DisableEnableForm(true);
	do_get_command(command);
}

function DisableEnableForm(xHow){
	var xForm=document.frmProduct;
	product.ajxCall=xHow;
	objElems = xForm.elements;
	for(i=0;i<objElems.length;i++){
		objElems[i].disabled = xHow;
	}
}

function toggleLoadMsgs(id,mode){
	var element=document.getElementById(id);
	if(!element){return;}
	
	if(mode==2){
		element.style.display="";
	}
	else {
		element.style.display="none";
	}
}

function toggleOutStock(flag){
	if(flag){
		product.valid=true;
		document.getElementById("outStock").style.display="none";
		document.getElementById("addCart").style.display="";
	}
	else {
		product.valid=false;
		document.getElementById("outStock").style.display="";
		document.getElementById("addCart").style.display="none";
	}
}

function getAttributeValues(allCheck){
	var icnt,n,ids="";
	for (icnt=0,n=product.priceAttr.count;icnt<n;icnt++){
		if (document.getElementById("attrValues["+icnt+"]").value<=0) {
			if (allCheck) return "";
			break;
		}
		if (ids!="") ids+="-";
		ids+=document.getElementById("attrIds["+icnt+"]").value+"{"+document.getElementById("attrValues["+icnt+"]").value+"}";
	}
	return ids;
}



/* function renamed for testing */
function submitData__2(){
	var error="",icnt,n;
	var error_message=false;
	if (product.priceAttr.enabled){
		for(icnt=0,n=product.priceAttr.count;icnt<n;icnt++){
			if(document.getElementById("attrValues["+icnt+"]").selectedIndex<=0){
				error+="*"+product.errMsgs.attrEmpty+"\n";
				break;
			}
		}
	}
	if(document.getElementById('qty') && (isNaN(parseInt(document.getElementById('qty').value))) || parseInt(document.getElementById('qty').value) <= 0){
		error+="*"+product.errMsgs.qty+"\n";
	}
	if(product.forced.enabled){ 
		var splt_array=Array();
		var arr_value;
		var arr_element;
		for(i=0;i<product.forced.values.length;i++) {
			arr_value=product.forced.values[i];
			arr_element=document.frmProduct.elements['xsell_forced_attributes' + arr_value + ''];
				if(arr_element) {
					if(arr_element.value==0) {
						error+="*"+product.errMsgs.forcedattrEmpty+"\n";
						break;
					}
				}
			}
		
		}
	
	if(error!=""){
		alert(error);
		return;
	}
	if(product.ajxCall || !product.valid){return;}
	
	document.frmProduct.submit();
}
function numericOnly(e) {
	var iKeyCode;
	if(!e) {
		var e = window.event;
	}
	if(e.keyCode) {
		iKeyCode = e.keyCode;
	}
	else {
		if(e.which) {
			iKeyCode = e.which;
		}
	}
	switch(iKeyCode) {
		case 8:case 9:case 37:case 38:case 39:case 40:case 46:break;
		case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:if (e.shiftKey || e.altKey){return false;}break;
		case 96:case 97:case 98:case 99:case 100:case 101:case 102:case 103:case 104:case 105:return iKeyCode - 48; break;
		case 110:
		//case 190:
		//if you are supporting decimal points
		//return '.'; break;
		default: return false;
	}
}

/* wtf is this ?? */
var prevQuantity=1;

function quanAutoCheck(){
	quan=getProductsQuantity();
	if (prevQuantity!=quan){
		product.valid=false;
		if (product.priceAttr.enabled){
			for (icnt=0,n=product.priceAttr.count;icnt<n;icnt++){
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

function loadAttributes(index){
	var selectId=document.getElementById("attrValues["+index+"]").value;
	var prevIds=getAttributeValues();
	command=product.ajxurl+"&command=fetch_attrib_val&select_attrib="+selectId+"&products_id="+product.id+"&select_index="+(index+1)+"&prev_ids="+prevIds;
	do_get_command(command);
}

function load_images() { //v3.0
	var d=document; 
	var a=load_images.arguments;
	d.url_list=new Array();
	for(i=0; i<a.length; i++){ 
		d.url_list[i]=a[i];
	}
}
function load_titles() { //v3.0
	var d=document; 
	var a=load_titles.arguments;
	d.title_list=new Array();
	for(i=0; i<a.length; i++){ 
		d.title_list[i]=a[i];
	}
}
function set_image(index){
	var d=document; 
	var dest=d.getElementById("imgContainer");
	if (d.url_list[index] && dest){
		dest.src=d.url_list[index];
	}
	if(d.getElementById("titleContainer")){
		if (d.title_list[index]){
			d.getElementById("titleContainer").innerHTML=d.title_list[index];
		}
		else {
			d.getElementById("titleContainer").innerHTML="";
		}
	}
}

function do_result(result){
	var res_splt;
	DisableEnableForm(false);
	if (result==""){return;}
	res_splt=result.split("@@");
	switch(res_splt[0]){
		case 'check_attrib_stock':
			if(res_splt[1]=="1"){
				toggleOutStock(true);
			}
			else {
				toggleOutStock(false);
			}
			toggleLoadMsgs("stockCheckQuan",1);
			toggleLoadMsgs("stockCheckAttr",1);
			break;
		
		case 'store_reminder':
			if (document.getElementById("reminderSuccess")){
				document.getElementById("reminderSuccess").style.display="";
				setTimeout("document.getElementById('reminderSuccess').style.display='none'",5000);
			}
			break;
	}
}

function doRound(x, places) {
	return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function formatCurrency(Num) {
	var sym_left=product.currency.symbolLeft;
	var sym_right=product.currency.symbolRight;
	
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
	for(var k = Num.length-1; k >= 0; k--) {
		var oneChar = Num.charAt(k);
		if(count == 3 && oneChar!="-") {
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
</script>
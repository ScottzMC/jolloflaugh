    function doDeleteImg(cat_id){
	doDisplayAction({'id':+cat_id,'get':'DeleteImg','result':doDisplayResult,'style':'boxLevel1','type':'cat','params':'cID='+cat_id});
	
	
	}
	    function doDeleteImg2(cat_id){
	doDisplayAction({'id':+cat_id,'get':'DeleteImg','result':doDisplayResult,'style':'boxLevel1','type':'cat','params':'cID='+cat_id});
	
	
	}
	
	function insert_option(selObj){
        if(document.getElementById('product_type').selectedIndex==1){
            var new_option=selObj.options[selObj.selectedIndex].text;
            var listItem = new Option(new_option);
            document.getElementById('spk_supportlist').options[0] = listItem;
        }
    }

function check_key(e){
     var kc=0;
   	  if (window.event)
	    kc=window.event.keyCode;
	  else if (e)
	 	kc=e.which;
	  else
		kc=0;
	  if(kc==13) doProductSearch('');
   }


function new_category()
{
    
    doDisplayAction({'id':-1,'get':'CategoryEdit','result':doDisplayResult,'type':'cat','params':'cID=-1','style':'boxLevel1'});
}

function new_product(catid)
{
    
    if(page.opened['cat'] && page.opened['cat'].id)
    {
        id=page.opened['cat'].id;
        doDisplayAction({'id':-1,'get':'ProductEdit','result':doDisplayResult,'style':'boxRow','type':'prd','params':'pID=-1&cID='+id,'backupMenu':true});

    }
    else
    {
        doDisplayAction({'id':+catid,'get':'CatInfoAndProducts','result':doDisplayResult,'style':'boxLevel1','type':'cat','params':'cID=+catid&new=new_products'});
    }

}



function swapRowUp(chosenRow,cnt,sort_val) {
		var mainTable=document.getElementById("attr_table_"+cnt);
		if (chosenRow.rowIndex != 0 && (chosenRow.rowIndex-1)>0) {
			var rIndex=chosenRow.rowIndex-1;
			var sel_id=chosenRow.id;
			var prev_id=mainTable.rows[rIndex].id;
			var sel_sort_val=document.getElementById('sort_order['+sel_id+']').value;
			var prev_sort_val=document.getElementById('sort_order['+prev_id+']').value;
			moveRow(chosenRow, chosenRow.rowIndex-1,cnt);
			document.getElementById('sort_order['+sel_id+']').value=prev_sort_val;
			document.getElementById('sort_order['+prev_id+']').value=sel_sort_val;	
		}
		

	}
	function doDisplyResult(result,action){
		var result_splt=result.split("@sep@");
		var element=document.getElementById(action.type+action.id+"content");
	
		element.innerHTML=result_splt[0];

		page.opened[action.type]=action;
		if (result_splt[1] && result_splt[1]!='') doJASON(result_splt[1],page.lastAction);
		changeBoxStyle(action,'Select');
			nonEditable();
	}
	function nonEditable(){
		var obj=document.getElementById('is_attributes');
		
		if(obj.checked==true){
		//	obj.disabled=false;
			document.getElementById('products_quantity').disabled=true;
			document.getElementById('products_sku').disabled=true;
			document.getElementById('products_weight').disabled=true;
			document.getElementById('products_sx').disabled=true;
			document.getElementById('products_sy').disabled=true;
		} else{
		//	obj.disabled=true;
			document.getElementById('products_quantity').disabled=false;
			document.getElementById('products_sku').disabled=false;
			document.getElementById('products_weight').disabled=false;
			document.getElementById('products_sx').disabled=false;
			document.getElementById('products_sy').disabled=false;
		}
		
	}
	function swapRowDown(chosenRow,cnt) {
		var mainTable=document.getElementById('attr_table_'+cnt);	
		if (chosenRow.rowIndex != mainTable.rows.length-1) {
			var rIndex=chosenRow.rowIndex+1;
			var sel_id=chosenRow.id;
			var next_id=mainTable.rows[rIndex].id;
			var sel_sort_val=document.getElementById('sort_order['+sel_id+']').value;
			var next_sort_val=document.getElementById('sort_order['+next_id+']').value;
			moveRow(chosenRow, chosenRow.rowIndex+1, cnt);
			document.getElementById('sort_order['+sel_id+']').value=next_sort_val;
			document.getElementById('sort_order['+next_id+']').value=sel_sort_val;
		}
	}
//moves the target row object to the input row index
	function moveRow(targetRow, newIndex, cnt) {
		var mainTable=document.getElementById('attr_table_'+cnt);	
		if (newIndex > targetRow.rowIndex) {
			newIndex++;
		}
		//insert a new row at the new row index
		var theCopiedRow = mainTable.insertRow(newIndex);
		//copy all the cells from the row to move into the new row
		for (var i=0; i<targetRow.cells.length; i++) {
			targetRow.className="attributes-odd";
			var oldCell = targetRow.cells[i];
			var newCell = document.createElement("TD");
			newCell.className="smallText";
			newCell.innerHTML = oldCell.innerHTML;
			theCopiedRow.appendChild(newCell);
			//alert(targetRow.id);
			//alert(theCopiedRow.id);
			theCopiedRow.className="attributes-odd";
			theCopiedRow.id=targetRow.id;
			copyChildNodeValues(targetRow.cells[i], newCell);
		}
		//delete the old row
		mainTable.deleteRow(targetRow.rowIndex);
 
		
	}
	function copyChildNodeValues(sourceNode, targetNode) {
		for (var i=0; i < sourceNode.childNodes.length; i++) {
			try{
				targetNode.childNodes[i].value = sourceNode.childNodes[i].value;
			}
			catch(e){

			}
		}
	}
	function categoryValidate(){
		var form=document.forms["catSubmit"];
		var lang,element,lastError="";
		//check category title & name
		if (!page.languages) return true;
		if (document.getElementById("parent_id") && document.getElementById("parent_id").selectedIndex<0) {
			lastError+="*"+page.template["ERR_CAT_SELECT_PARENT"]+"\n";
		}
		for(lang in page.languages){
			element=document.getElementById("categories_name["+page.languages[lang].id+"]");
			if (!element) return true;
			if (element.value==""){
				lastError+="*"+page.template["ERR_CAT_NAME_EMPTY"]+"\n";
				break;
			}
		}
		for(lang in page.languages){
			element=document.getElementById("categories_heading_title["+page.languages[lang].id+"]");
			if (!element) return true;
			if (element.value==""){
				lastError+="*"+page.template["ERR_CAT_TITLE_EMPTY"]+"\n";
				break;
			}
		}
        if (document.getElementById("categories_image_file") && document.getElementById("categories_image_file").value!="" && !checkMime(document.getElementById("categories_image_file"),['jpg','gif','jpeg','png']))
			lastError+="* "+page.template["ERR_IMAGE_UPLOAD_TYPE"]+"\n";
		if (lastError!="") {
			alert(lastError);
			return false;
		}
		        if (document.getElementById("categories_image_file_2") && document.getElementById("categories_image_file_2").value!="" && !checkMime(document.getElementById("categories_image_file_2"),['jpg','gif','jpeg','png']))
			lastError+="* "+page.template["ERR_IMAGE_UPLOAD_TYPE"]+"\n";
		if (lastError!="") {
			alert(lastError);
			return false;
		}
		return true;
	}
	

	function updateGross() {
		var taxRate = getTaxRate();
		//var taxRate = 7;
		var grossValue=0;
		if(document.getElementById("products_price")) grossValue = document.getElementById("products_price").value;
		
		if (taxRate > 0) {
			grossValue = grossValue * ((taxRate / 100) + 1);
		}
		
		document.getElementById("products_price_gross").value = doRound(grossValue, 4);
	}
	
	function updateNet() {
		var taxRate = getTaxRate();
		//var taxRate = 7;
		var netValue = document.getElementById("products_price_gross").value;
	
		if (taxRate > 0) {
			netValue = netValue / ((taxRate / 100) + 1);
		}
	
		document.getElementById("products_price").value = doRound(netValue, 4);
	}
	function doPriceBreaks(action){
		var optionElement,taxRate,price,priceBreaks,icnt,n,newPrice,option,quan,pos,kcnt=0,jcnt;
		var commands=Array(action.cmd);
		
		if (!document.getElementById('products_price_break').checked) return;
		if (!page.priceBreaks) return;
		
		optionElement=document.getElementById('pbk_list');
		priceBreaks=page.priceBreaks;

		while(kcnt<commands.length){
			switch(commands[kcnt]){
				case 'refresh':
					price=parseFloat(document.getElementById('products_price').value);
					if (isNaN(price)) price=0;
					taxRate = getTaxRate();
					pos=optionElement.selectedIndex;
					//clearAll(optionElement);
					for (icnt=0,n=priceBreaks.length;icnt<n;icnt++){
						//newPrice=priceBreaks[icnt].quan*(price-priceBreaks[icnt].price);
						newPrice=priceBreaks[icnt].price;
						if (taxRate>0) newPrice+=newPrice*(taxRate/100);
						newPrice=doRound(newPrice,2);
						option=page.template["PBK_OPTION"];
						option=option.replace(/##1##/g,priceBreaks[icnt].quan);
						option=option.replace(/##2##/g,newPrice);
						optionElement.options[icnt].value=priceBreaks[icnt].quan+"##"+priceBreaks[icnt].price;
						optionElement.options[icnt].text=option;
					}
					commands[kcnt+1]="priceChange";
					break;
				case 'deletePrice':
					var temp=[];
					pos=optionElement.selectedIndex;
					if (pos<0) return;
					optionElement.remove(pos);
					document.getElementById('pbk_quantity').value='';
					document.getElementById("pbk_discount_price").value='';
					document.getElementById("pbk_calc_price").innerHTML="&nbsp;";
					jcnt=0;
					for (icnt=0,n=priceBreaks.length;icnt<n;icnt++){
						if (icnt!=pos){
							temp[jcnt]={'quan':priceBreaks[icnt].quan,'price':priceBreaks[icnt].price};
							jcnt++;
						}
					}
					page.priceBreaks=temp;
					if (optionElement.options.length>0 && pos<optionElement.options.length) optionElement.selectedIndex=pos;
					commands[kcnt+1]="select";
					break;
				case 'select':
					pos=optionElement.selectedIndex;
					if (pos<0) return;
					document.getElementById('pbk_quantity').value=priceBreaks[pos].quan;
					document.getElementById('pbk_discount_price').value=priceBreaks[pos].price;
					commands[kcnt+1]="priceChange";
					break;
				case 'priceChange':
					var product_price=parseFloat(document.getElementById('products_price').value);
					price=parseFloat(document.getElementById("pbk_discount_price").value);
					quan=parseFloat(document.getElementById('pbk_quantity').value);
					if (isNaN(price) && isNaN(quan)) break;
					if (isNaN(product_price) || product_price<=0) product_price=0;
					if (isNaN(price) || price<=0) price=0;
					if (isNaN(quan) || quan<=0) quan=1;
				//	newPrice=quan*(product_price-price);
					newPrice=price;
					dispPrice=(product_price-price);//
					taxRate=getTaxRate();
					if (taxRate>0) newPrice+=newPrice*(taxRate/100);
					if (taxRate>0) dispPrice+=dispPrice*(taxRate/100);
					//document.getElementById('pbk_calc_price').innerHTML=doRound(newPrice,2);
					document.getElementById('pbk_calc_price').innerHTML=doRound(dispPrice,2);
					break;
				case 'updatePrice':
					if (optionElement.selectedIndex<0) return;
				case 'addPrice':
					var error='';
					var found=false;
					var quan=parseInt(document.getElementById("pbk_quantity").value);
					price=parseFloat(document.getElementById("pbk_discount_price").value);
					var product_price=parseFloat(document.getElementById("products_price").value);
					if (isNaN(product_price)){
						error+="* "+page.template["ERR_PRICE"]+"\n";
					}
					if (isNaN(quan) || quan<=0){
						error+="* "+page.template["PBK_ERR_QUAN"]+"\n";
					}
					if (isNaN(price) || price<=0){
						error+="* "+page.template["PBK_ERR_PRICE"]+"\n";
					} else if (price>product_price){
						error+="* "+page.template["PBK_ERR_PRICE_LESS"]+"\n";
					}
					if (error!=''){
						alert(error);
						return;
					}
					for (icnt=0,n=priceBreaks.length;icnt<n;icnt++){
						if ((action.cmd=="addPrice" || optionElement.selectedIndex!=icnt) && priceBreaks[icnt].quan==quan){
							found=true;
							break;
						}
					}
					if (found){
						alert(page.template["PBK_ERR_EXISTS"]);
						return;
					}
					taxRate=getTaxRate();
				//	newPrice=quan*(product_price-price);
					newPrice=price;
					if (taxRate>0){
						newPrice+=newPrice*(taxRate/100);
					}
					newPrice=doRound(newPrice,2);
					
					option=page.template["PBK_OPTION"];
					option=option.replace(/##1##/g,quan);
					option=option.replace(/##2##/g,newPrice);
					if (action.cmd=="addPrice"){
						pos=priceBreaks.length;
						addOption(optionElement,option,quan+"##"+price,pos);
						optionElement.selectedIndex=pos;
					} else {
						pos=optionElement.selectedIndex;
						optionElement.options[optionElement.selectedIndex].text=option;
						optionElement.options[optionElement.selectedIndex].value=quan+"##"+price;
					}
					priceBreaks[pos]={'quan':quan,'price':price};
					optionElement.selectedIndex=-1;
					document.getElementById('pbk_quantity').value='';
					document.getElementById("pbk_discount_price").value='';
					document.getElementById("pbk_calc_price").innerHTML="&nbsp;";
			}
			kcnt++;
		}
	}
	function getTaxRate(){
   if (!page.taxRates) return 0;
   var e_element=document.getElementById('products_tax_class_id');
   if (!e_element) return 0;
   var parameterVal=e_element.options[e_element.selectedIndex].value;
   if ((parameterVal>0) && (page.taxRates[parameterVal])){
      console.log (page.taxRates);
      console.log ("Expecting " + e_element.options[e_element.selectedIndex].text);
      console.log ("Returning " + page.taxRates[parameterVal] + "%");
      return page.taxRates[parameterVal];}
   else
   {return 0;}
}
	
	// function getTaxRate(){
		// if (!page.taxRates) return 0;
		// var element=document.getElementById('products_tax_class_id');
		// if (!element) return 0;
		// var selected_value=element.selectedIndex;
		// var parameterVal=element.options[selected_value].value;
		// if ((parameterVal>0) && (page.taxRates[parameterVal]))
			// return page.taxRates[parameterVal];
		// else
			// return 0;
	// }
	function doAttributes(action){
		var commands,icnt,n,kcnt,command,attribStock,attributes,pos,attribCombo;
		if (!document.getElementById('is_attributes').checked) return;
		if (!page.attributes) return;
		commands=Array(action);
		kcnt=0;
		attribStock=page.attribStock;
		attributes=page.attributes;
		while(kcnt<commands.length){
			command=commands[kcnt];
			switch(command.cmd){
				case 'changeAttribSelect':
					if (command.chkbox.checked){
						if (attribStock.length>0 && attributes[command.optionID].useCnt==0){
							command.chkbox.checked=false;
							alert(page.template["ATT_ERR_VALUE_CHANGE"]);
							return;
						}
						attributes[command.optionID].useCnt++;
					} else {
						if (attribStock.length>0 && checkAttribValueExist(command.optionID,command.valueID)){
							command.chkbox.checked=true;
							alert(page.template["ATT_ERR_VALUE_CHANGE"]);
							return;
						}
						attributes[command.optionID].useCnt--;
					}
					refreshAttrOptionStock(command.optionID);
					break;
				case 'editStockAttr':
					var tmp_splt,tmp_splt1;
					pos=-1;
					for (icnt=0,n=attribStock.length;icnt<n;icnt++){
						if (attribStock[icnt].id==command.id){
							pos=icnt;
							break;
						}
					}
					if (pos<0) return;
					document.getElementById("att_stock_quantity").value=attribStock[pos].qty;
					document.getElementById("att_stock_sku").value=attribStock[pos].sku;
					if (attribStock[pos].status==1){
						document.forms["productSubmit"].elements["att_stock_status"][0].checked=true;
					} else {
						document.forms["productSubmit"].elements["att_stock_status"][0].checked=false;
					}
					tmp_splt=attribStock[pos].id.split("-");
					for (icnt=0,n=tmp_splt.length;icnt<n;icnt++){
						tmp_splt1=tmp_splt[icnt].split("{");
						selectComboValue(document.getElementById("att_stock_value"+tmp_splt1[0]),tmp_splt1[1].substr(0,tmp_splt1[1].length-1))
					}
					page.curAttrStock={id:attribStock[pos].id,'pos':pos};
				case 'addStockAttr':
					for (key in attributes){
						if (attributes[key].show){
							document.getElementById("attributeStockView").style.display='';
							break;
						}
					}
					if (document.getElementById("attributeStockView").style.display=="none"){
						alert(page.template["PRD_ERR_ATTR_EMPTY"]);
						return;
					}
					break;
				case 'updateStockAttr':
					var error='';
					var quantity=parseInt(document.getElementById("att_stock_quantity").value);
					var sku=strTrim(document.getElementById("att_stock_sku").value);
					var status,statusText;
					
					var attribID='',key,status,attrText='';
					if (isNaN(quantity)){
						error+="* "+page.template["ATT_ERR_QUANTITY"]+"\n";
					}
					if(sku=='' || sku==null || sku==0){
						error+="* "+page.template["PRD_ERR_SKU"]+"\n";
					}
					if (page.SKUlength && (sku=='' || sku==null || sku.length!=page.SKUlength)){
						error+="* "+page.template["ATT_ERR_SKU"]+"\n";
					}
					if (error!=''){
						alert(error);
						return;
					}
					for (key in attributes){
						if (attributes[key].show){
							attCombo=document.getElementById("att_stock_value"+key);
							attrText+=","+attributes[key].name+":"+attCombo.options[attCombo.selectedIndex].text;
							attribID+="-"+key+"{"+attCombo.value+"}";
						}
					}
					attribID=attribID.substr(1);
					for (icnt=0,n=attribStock.length;icnt<n;icnt++){
						if ((!page.curAttrStock.id || icnt!=page.curAttrStock.pos) && attribStock[icnt].id==attribID){
							alert(page.template["ATT_ERR_STOCK_EXISTS"]);
							return;
						}
					}
					if (document.forms["productSubmit"].elements["att_stock_status"][0].checked){
						status=document.forms["productSubmit"].elements["att_stock_status"][0].value;
						statusText=page.template['IN_STOCK'];
					} else {
						status=document.forms["productSubmit"].elements["att_stock_status"][1].value;
						statusText=page.template["OUT_STOCK"];
					}
					var dyCmd={cols:[sku,attrText.substr(1),quantity,statusText],id:"attributeStockTable"};
					if (page.curAttrStock.id){
						dyCmd.cmd="edit";
						dyCmd.rowpos=page.curAttrStock.pos+1;
					} else {
						dyCmd.cmd="add";
						dyCmd.rowpos=-1;
						dyCmd.rowClassName="attributes-odd";
						dyCmd.colClassName="smallText";
						dyCmd.cols[4]='<a href="javascript:void(0);" onClick="javascript:doAttributes({cmd:\'editStockAttr\',id:\'' + attribID + '\'});"><img src="'+page.imgPath+'template/img_edit.gif" border="0" align="top"></a>&nbsp;<a href="javascript:doAttributes({cmd:\'deleteStockAttr\',id:\'' + attribID + '\'})"><img src="'+page.imgPath+'template/img_trash.gif" align="top"></a>';
					}
					pos=doDynamicTable(dyCmd);
					pos--;
					if (!attribStock[pos]){
						attribStock[pos]={};
					}
					attribStock[pos].id=attribID;
					attribStock[pos].sku=sku;
					attribStock[pos].qty=quantity;
					attribStock[pos].status=status;
				case 'closeStockAttr':
					page.curAttrStock='';
					document.getElementById("att_stock_quantity").value='';
					document.getElementById("att_stock_sku").value='';
					for (key in attributes){
						if (attributes[key].show){
							attCombo=document.getElementById("att_stock_value"+key);
							attCombo.selectedIndex=0;
						}
					}
					document.getElementById("attributeStockView").style.display='none';
					page.curAttrStock={};
					break;
				case 'deleteStockAttr':
					if (document.getElementById("attributeStockView").style.display!='none') return;
					pos=-1;
					for (icnt=0,n=attribStock.length;icnt<n;icnt++){
						if (attribStock[icnt].id==command.id){
							pos=icnt;
							break;
						}
					}
					if (pos<0) return;
					doDynamicTable({cmd:'delete',rowpos:pos+1,id:"attributeStockTable"});
					page.attribStock=sliceObjectArray(page.attribStock,pos);
			}
			kcnt++;
		}
	}
	function checkAttribValueExist(optionID,valueID){
		var attribStock=page.attribStock;
		var icnt,n;
		var check_id=optionID+'{'+valueID+"}";

		for (icnt=0,n=attribStock.length;icnt<n;icnt++){
			if (attribStock[icnt].id.indexOf(check_id)>=0){
				return true;
			}
		}
		return false;
	}
	function refreshAttrOptionStock(optionID){
		var element,values,key,pos;
		element=document.getElementById("att_stock_value"+optionID);
		pos=element.selectedIndex;
		clearAll(element);
		values=page.attributes[optionID].values;
		if (!values) return;
		for(key in values){
			if (document.getElementById("att_option_value["+optionID+"-"+key+"]").checked){
				addOption(element,values[key].name,key,element.options.length);
			}
		}
		if (!page.attributes[optionID].show && element.options.length>0){
			page.attributes[optionID].show=true;
			document.getElementById("attribStockOption"+optionID).style.display="";
			if (pos>0){
				element.selectedIndex=pos;
			}
		} else if (element.options.length<=0){
			page.attributes[optionID].show=false;
			document.getElementById("attribStockOption"+optionID).style.display="none";
		}
	}
	function productValidate(){
		var form=document.forms["productSubmit"];
		var lastError='';
		var element,icnt,n,tempCnt,value,key,key1,lang;
		var curCatChecked=false;
		var category_id=parseInt(form["category_id"].value);
		for(lang in page.languages){
			element=document.getElementById("products_name["+page.languages[lang].id+"]");
			if (element.value==""){
				lastError+="* "+page.template["PRD_ERR_NAME_EMPTY"]+"\n";
				break;
			}
		}
		element=document.getElementsByName('categories_ids[]');

		tempCnt=0;
		for(icnt=0,n=element.length;icnt<n;icnt++){
			if(element[icnt].checked) {
				tempCnt++;
				if (parseInt(element[icnt].value)==category_id) {
					curCatChecked=true;
					break;
				}
			}
		}
		if (tempCnt==0){
			lastError+="* "+page.template["PRD_ERR_SELECT_CAT"]+"\n";
		} else if (!curCatChecked){
			lastError+="* "+page.template["PRD_ERR_CURRENT_CATEGORY"]+"\n";
		}
		switch(document.getElementById("product_type").value){
			case 'B':
				// if (isEmpty(document.getElementById("author_name"))){
					// lastError+="* "+page.template["PRD_ERR_AUTHOR_EMPTY"]+"\n";
				// }
				break;
			case 'F':
				// if (!page.supportPacks || page.supportPacks.length==0){
					// lastError+="* "+page.template["PRD_ERR_SUPPORT_PACK"]+"\n";
				// }
				break;
		}
		if (document.getElementById("product_mode").value=="V"){
			if (document.getElementById("download_link").value=="" && document.getElementById("download_link_file").value==""){
				lastError+="* "+page.template["PRD_ERR_DOWNLOAD_LINK"]+"\n";
			}
		}
		value=parseFloat(document.getElementById("products_price").value);
		if (isNaN(value) || value<0){
			//REMOVED ERROR TO ALLOW ZERO PRICE AT SET UP
			//lastError+="* "+page.template["PRD_ERR_PRICE"]+"\n";
			if (document.getElementById("products_price_break").checked){
				document.getElementById("products_price_break").checked=false;
				toggleView({'id':"priceBreakView",'prop':'display'});
			}
		}
		if (document.getElementById("products_price_break").checked){
			if (!page.priceBreaks || page.priceBreaks.length==0){
				lastError+="* "+page.template["PRD_ERR_PRICE_BREAKS_EMPTY"]+"\n";
			}
		}
		if (!document.getElementById("is_attributes").checked){
			value=parseInt(document.getElementById("products_quantity").value);
			// if(isNaN(value) || value<=0){
				// lastError+="* "+page.template["PRD_ERR_QUANTITY"]+"\n";
			// }
			value=document.getElementById("products_sku").value;
			if(value=='' || value==null){
				lastError+="* "+page.template["PRD_ERR_SKU"]+"\n";	
			}
			// if (page.SKUlength && (value=='' || value.length!=page.SKUlength)){
				// lastError+="* "+page.template["PRD_ERR_SKU"]+"\n";
			// }
		}
		value=document.getElementById("products_weight").value;
		if (page.weightUnit && page.weightUnit.toLowerCase()=="oz" && value.indexOf(".")>=0){
			lastError+="* "+page.template["PRD_ERR_WEIGHT_UNIT"]+"\n";
		}
		if (document.getElementById("is_attributes").checked){
			tempCnt=0;
			for (key in page.attributes){
				if (page.attributes[key].show){
					tempCnt++;
					break;
				}
			}
			if (tempCnt==0){
				lastError+="* "+page.template["PRD_ERR_ATTR_EMPTY"]+"\n";
			}
		}

		for (icnt=1;icnt<=5;icnt++){
			if (document.getElementById("products_image_"+icnt+"_file") && document.getElementById("products_image_"+icnt+"_file").value!="" && !checkMime(document.getElementById("products_image_"+icnt+"_file"),['jpg','gif','jpeg','png'])){
				lastError+="* "+page.template["ERR_IMAGE_UPLOAD_TYPE"]+"\n";
				break;
			}
           
		}
		if (lastError!=''){
			alert(lastError);
			return false;
		}
		return true;
	}
	function doProductUpdate(){
		var data='',temp,element,icnt,n,key,key1,key2,quantity,weight,mqty,sku;
		var form=document.forms["productSubmit"];
		var deElements=Array();
		var icnt=0;
		textEditorSave();
		for(lang in page.languages){
			data+="products_name["+page.languages[lang].id+"]="+encodeURIComponent(document.getElementById("products_name["+page.languages[lang].id+"]").value)+"&";
			data+="products_url["+page.languages[lang].id+"]="+encodeURIComponent(document.getElementById("products_url["+page.languages[lang].id+"]").value)+"&";
			data+="products_number["+page.languages[lang].id+"]="+encodeURIComponent(document.getElementById("products_number["+page.languages[lang].id+"]").value)+"&";
			data+="products_description["+page.languages[lang].id+"]="+encodeURIComponent(document.getElementById("products_description["+page.languages[lang].id+"]").value)+"&";
		}
		for (icnt=0,n=form["products_status"].length;icnt<n;icnt++){

			if (form["products_status"][icnt].checked){
				data+="products_status="+form["products_status"][icnt].value+"&";
				break;
			}
		}
		data+=getMselectValues(form["categories_ids[]"]);
		//data+="manufacturers_id="+form["manufacturers_id"].value+"&";
		/*data+="section_id="+form["section_id"].value+"&";*/
		/*cartzone remove date available*/
		data+="products_date_available="+encodeURIComponent(form["products_date_available"].value)+"&";
		data+=getMselectValues(form["restrict_to_groups[]"],true,"restrict_to_groups");
		data+=getMselectValues(form["restrict_to_customers[]"],true,"restrict_to_customers");
		
		
		data+="product_type="+form["product_type"].value+"&";
		switch(form["product_type"].value){
			case 'B':
				//data+="author_name="+encodeURIComponent(form["author_name"].value)+"&";
				data+="products_model="+encodeURIComponent(form["products_model"].value)+"&";
				data+="color_code="+encodeURIComponent(form["color_code"].value)+"&";
				break;
			case 'F':
				data+="products_model="+encodeURIComponent(form["products_model"].value)+"&";
				data+="color_code="+encodeURIComponent(form["color_code"].value)+"&";
				break;

			default:
				data+="products_model="+encodeURIComponent(form["products_model"].value)+"&";
				data+="color_code="+encodeURIComponent(form["color_code"].value)+"&";
		}
		data+="product_mode="+form["product_mode"].value+"&";
		if (form["product_mode"].value=="V"){
			data+="download_last_date="+encodeURIComponent(form["download_last_date"].value)+"&";
			data+="downloads_per_customer="+encodeURIComponent(form["downloads_per_customer"].value)+"&";
			data+="download_link="+form["download_link"].value+"&";
		} 
		
		weight=form["products_weight"].value;
		mqty=form["master_quantity"].value;
		
		data+="products_tax_class_id="+form["products_tax_class_id"].value+"&";
		data+="products_price="+encodeURIComponent(form["products_price"].value)+"&";
		data+="products_price_break="+(form["products_price_break"].checked?form["products_price_break"].value:'')+"&";
		if (form["products_price_break"].checked){
			temp='';
			for (icnt=0,n=page.priceBreaks.length;icnt<n;icnt++){
				data+="priceBreaks["+icnt+"][quan]="+page.priceBreaks[icnt].quan+"&";
				data+="priceBreaks["+icnt+"][price]="+page.priceBreaks[icnt].price+"&";
			}
			if (temp!=''){
				data+=temp;
			}
		}
		
		sku=form["products_sku"].value;
		data+="is_attributes="+(form["is_attributes"].checked?form["is_attributes"].value:'')+"&";

		quantity=form["products_quantity"].value;
		if (form["is_attributes"].checked){
			weight=0;
			for (key in page.attributes){
				if (!page.attributes[key].show) continue;
				for (key1 in page.attributes[key].values){
					key2=key+"-"+key1;
					if (!document.getElementById("att_option_value["+key2+"]").checked) continue;
					temp="attributes["+key2+"]";
					data+=temp+"[prefix]="+encodeURIComponent(document.getElementById("prefix["+key2+"]").value)+"&";
					data+=temp+"[price]="+encodeURIComponent(document.getElementById("price["+key2+"]").value)+"&";
					data+=temp+"[sort_order]="+encodeURIComponent(document.getElementById("sort_order["+key2+"]").value)+"&";
					data+=temp+"[weight_prefix]="+encodeURIComponent(document.getElementById("weight_prefix["+key2+"]").value)+"&";
					data+=temp+"[weight]="+encodeURIComponent(document.getElementById("weight["+key2+"]").value)+"&";
				}
			}
			if (page.attribStock.length>0){
				sku='';
				quantity=0;
				for(icnt=0,n=page.attribStock.length;icnt<n;icnt++){
					data+="attribStock["+icnt+"][id]="+page.attribStock[icnt]["id"]+"&";
					data+="attribStock["+icnt+"][sku]="+encodeURIComponent(page.attribStock[icnt]["sku"])+"&";
					data+="attribStock["+icnt+"][qty]="+encodeURIComponent(page.attribStock[icnt]["qty"])+"&";
					data+="attribStock["+icnt+"][status]="+page.attribStock[icnt]["status"]+"&";
					quantity+=parseInt(page.attribStock[icnt]["qty"]);
				}
			}
		}
		data+="products_weight="+form["products_weight"].value+"&";
		data+="master_quantity="+form["master_quantity"].value+"&";
		data+="products_w="+form["products_w"].value+"&";
		data+="products_h="+form["products_h"].value+"&";
		data+="products_r="+form["products_r"].value+"&";
		data+="products_sx="+form["products_sx"].value+"&";
		data+="products_sy="+form["products_sy"].value+"&";
		data+="products_x="+form["products_x"].value+"&";
		data+="products_y="+form["products_y"].value+"&";
		data+="products_season="+form["products_season"].value+"&";
		data+="products_quantity="+quantity+"&";
		data+="products_sku="+sku+"&";
		data+="product_id="+form["product_id"].value+"&";
		data+="category_id="+form["category_id"].value+"&";
		data+="prev_attribute_ids="+form["prev_attribute_ids"].value+"&";
		data+="prev_attribute_stock_ids="+form["prev_attribute_stock_ids"].value+"&";
		for (icnt=1;icnt<=1;icnt++){
			data+="products_image_"+icnt+"="+form["products_image_"+icnt].value+"&";
			data+="products_title_"+icnt+"="+encodeURIComponent(form["products_title_"+icnt].value)+"&";
		}
		
        
		command=page.link+"?AJX_CMD=ProductUpdate&RQ=A&" + new Date().getTime();
		// define the method to handle server responses
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
	}
	function doCategoryEditor(){
		if (page.editorLoaded) return;
		var icnt=0;
		var deElements=Array();
		for(lang in page.languages){
			page.editorControls[page.editorControls.length]="categories_description["+page.languages[lang].id+"]";
		}
		textEditorInit();
	}
	function doProductEditor(){
		if (page.editorLoaded) return;
		var icnt=0;
		var deElements=Array();
		for(lang in page.languages){
			page.editorControls[page.editorControls.length]="products_description["+page.languages[lang].id+"]";
		}
		textEditorInit();
	}
	function sortProductValidate(action){
		var element=document.getElementById(action.type+action.id+"row");
		if (action.mode=="up" && element.rowIndex==1) return false;
		else if (action.mode=="down" && element.rowIndex==element.parentNode.rows.length-1) return false;
		return true;
	}
	function sortCatValidate(action){
		var parent=page.treeList[action.id].parent;
		var childs=page.treeList[parent].childs;
		var pos=page.treeList[action.id].pos;
		if (action.mode=="up" && pos==0) return false;
		else if (action.mode=="down" && pos==childs-1) return false;
		return true;
	}
	function doCustomActions(type,data){
		var jdata;
		eval("jdata="+data);
		switch(type){
			case 'display':
				jdata.result=doDisplayResult;
				doDisplayAction(jdata);
				break;
		}
	}
	function doProductSearch(mode){
		if (mode=="reset"){
			document.getElementById("psearch").value='';
			page.searchMode=false;
			doPageAction({'id':-1,'type':'cat','get':'Categories','result':doTotalResult,'message':page.template["INFO_LOADING_DATA"]});
		} else {
			var value=strTrim(document.getElementById("psearch").value);
			if (value=='') return;
			doPageAction({'id':-1,'type':'cat','get':'SearchProducts','result':doTotalResult,params:'search='+value,'message':page.template["INFO_SEARCHING_DATA"]});
			page.searchMode=true;
		}
	}
function toggle_visibility(id) 
{
    var e = document.getElementById(id);
    if (e.style.display == 'inline' || e.style.display=='')
    {
        e.style.display = 'none';
    }
    else 
    {
        e.style.display = 'inline';
    }
}


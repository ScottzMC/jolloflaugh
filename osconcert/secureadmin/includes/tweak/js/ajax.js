	function do_result(data){
		if (document.getElementById("ajxLoad")){
			document.getElementById("ajxLoad").style.display="none";
		}
		if (!page.lastAction || !page.lastAction.result) return;
		if (data.indexOf('Err:')>=0){
			doErrorResult(data,page.lastAction);
			return;
		}
		page.lastAction.result(data,page.lastAction);
		page.lastAction=false;
	}
	/* set of actions */
	function doDisplayAction(action){
	
	if (page.lastAction || page.locked) return;
		if (!closePreviousOpened(action)) return;
		var temp='';
		var element=document.getElementById(action.type+action.id);
		if (!element) return;
		page.lastAction=action;
		addContentRow(element,1);
		
		if (action.get.indexOf("Edit")>=0)
			page.locked=true;
			
		
			
		appendExtraParams(action);
		if (action.extraFunc)
		action.extraFunc();
		
		do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params);
	}
	
	function get_customer_detail(action){
	
	if (page.lastAction || page.locked) return;
		if (!closePreviousOpened(action)) return;
		var temp='';
		var element=document.getElementById(action.type+action.id);
		if (!element) return;
		page.lastAction=action;
		addContentRow(element,1);
		
		if (action.get.indexOf("Edit")>=0) page.locked=true;
			
		appendExtraParams(action);
		if (action.extraFunc)
		action.extraFunc();
		
		do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params);
	}
	
	function doPageAction(action){
		if (page.locked || (action.get!='Search' && page.searchMode)) return;
		if (action.closePrev && !closePreviousOpened(action)) return;
		checkMessageDisplay(action);
		page.lastAction=action;
		if(action.typename)
			do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params+'&typename='+action.typename);
		else
			do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params);
		
	}
	function doUpdateAction(action){
		if (page.lastAction && page.lastAction!=action) return;
		if (!action.imgProcessed){
			if (action.validate && !action.validate()) return;
			page.lastAction=action;
			
			if (action.imgUpdate) {
				if (attachFileInputs(action.uptForm)){
					page.lastAction.display=page.template["UPDATE_IMAGE"];
					checkMessageDisplay(action);
					AIM.submit(document.getElementById("fileUpload"), {'onComplete' : doImageResult});
					document.getElementById("fileUpload").submit();
					return;
				}
			}
		}

		page.lastAction.message=page.lastAction.message1;
		checkMessageDisplay(action);
		if (action.extraFunc) action.extraFunc();
		if (action.customUpdate)
			action.customUpdate(action);		
		else
			do_post_command(action.uptForm,page.link+'?AJX_CMD='+action.get);
	}
	function doCancelAction(action){
		page.locked=false;
		if (action.extraFunc) action.extraFunc();
		closeRow(action.id,action.type,"Hover");
		updateMenu(action,(action.id==-1?"":",normal,"));
		page.lastAction=false;
		delete page.opened[action.type];
	}
	
	function doSimpleAction(action){
		if (action.validate && !action.validate(action)) return;
		checkMessageDisplay(action);
		page.locked=true;
		page.lastAction=action;
		do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params);
	}
	/* set of result */
	function doDisplayResult(result,action){
		var result_splt=result.split("@sep@");
		var element=document.getElementById(action.type+action.id+"content");
	
		element.innerHTML=result_splt[0];
		page.opened[action.type]=action;
		
		if (result_splt[1] && result_splt[1]!='') doJASON(result_splt[1],page.lastAction);
		changeBoxStyle(action,'Select');
		
	}
	function doTotalResult(result,action){
		var result_splt=result.split("@sep@");
		page.lastAction=false;
		
		
		
		
		
		page.locked=false;

		if (!action.closePrev) page.opened={};
		if (result_splt[0]=="")  return;
		if (document.getElementById(action.type+"totalContentResult")){
			document.getElementById(action.type+"totalContentResult").innerHTML=result_splt[0];
		}
		if (document.getElementById(action.type+action.id+"message")){
			document.getElementById(action.type+action.id+"message").innerHTML='';
		}
		if (!result_splt[1] || result_splt[1]=='') return;
		doJASON(result_splt[1],action);
	}
	function doErrorResult(result,action){
		var result_splt=result.split("@sep@");
		if (document.getElementById(action.type+action.id+"message")){
			document.getElementById(action.type+action.id+"message").innerHTML=result_splt[0].substr(4);
		} else{
//			alert(action.type+'>>>'+action.id);
			alert(result_splt[0].substr(4));
		}
		/*
		if (document.getElementById(action.type+action.id+"message")) document.getElementById(action.type+action.id+"message").innerHTML=result_splt[0].substr(4);
		else alert(result_splt[0].substr(4));
		*/
		if (result_splt[1]) doJASON(result_splt[1],action);
		page.lastAction=false;
	}
	function doSimpleResult(result,action){
		var result_splt=result.split("@sep@");
		page.locked=false;
		if (result_splt[1]) doJASON(result_splt[1],action);
		page.lastAction=false;
	}
	function doImageHover(obj,image){
		obj.src=page.imgPath+image;
	}
	/* table operations */
	function addContentRow(element,pos){
		if (pos<element.rows.length){
			var c=element.rows[pos].cells[0];
		} else {
			var r=element.insertRow(element.rows.length);
			var c=r.insertCell(0);
			c.className=page.lastAction.type+"content";
			c.id=page.lastAction.type+page.lastAction.id+"content";
		}
		//c.innerHTML=document.getElementById("ajaxLoadInfo").innerHTML;
		page.lastAction.message=page.template["TEXT_LOADING"];
		checkMessageDisplay(page.lastAction);
	}
	function closePreviousOpened(action){
		if (page.opened[action.type]){
			var prev_id=page.opened[action.type].id;
			var prev_get=page.opened[action.type].get;
			if (document.getElementById(action.type+prev_id)){
				if (!action.pageNav && (action.id!=prev_id || action.get=="closeRow" || action.get==prev_get)){
					closeRow(prev_id,action.type,(action.id==prev_id && action.get!='closeRow'?"Hover":""));
				}
				delete page.opened[action.type];
				if (!action.pageNav && action.id==prev_id && (action.get=="closeRow" || action.get==prev_get)) return false;
			} else {
				delete page.opened[action.type];
			}
		}
		return true;
	}
	function closeRow(id,type,style){
		var element=document.getElementById(type+id);
		element.deleteRow(element.rows.length-1);
		changeBoxStyle(page.opened[type],style);
	}
	/* set of styles */
	function doMouseOverOut(actions){
		var icnt,n;
		for (icnt=0,n=actions.length;icnt<n;icnt++){
			actions[icnt].callFunc(actions[icnt].params);
		}
	}
	function changeItemRow(action){
		var element=action.element;
		var changeStyle='';
		//if (action.id=='prd-1style') alert(action.className+"Select");
		if (element.className==action.className+"Select") {
			return;
		}
		if (action.changeStyle) changeStyle=action.changeStyle;
		element.className=action.className+changeStyle;
	}
	function checkMessageDisplay(action){
		var element=document.getElementById('ajxLoad');
		if (action.message){
			if (element){
				if (!element.setPos){
					element.style.left=(screen.availWidth-parseInt(element.style.width))/2;
					element.style.top=(screen.availHeight-parseInt(element.style.height))/2;
				}
				document.getElementById('ajxLoadMessage').innerHTML=action.message;
				element.style.display='';
			} else {
				document.getElementById(action.type+action.id+"message").innerHTML='<b>'+action.message+'&nbsp;'+document.getElementById("ajaxLoadImage").innerHTML+"</b>";
			}
		}
	}
	function changeBoxStyle(action,style_type){
		var element;
		if (action.style) {
			if (document.getElementById(action.type+action.id+"style"))
				element=document.getElementById(action.type+action.id+"style");
			else if (document.getElementById(action.type+action.id)){
				element=document.getElementById(action.type+action.id);
			}
			if (style_type=='check'){
				if (element.className!=action.style+"Select"){
					element.className=action.style;
				}
			} else {
				element.className=action.style+style_type;
		//	alert(action.style+'>>'+style_type+'\naction_type='+action.type+'\naction_id='+action.id+'\nClass='+element.className);
			}
		} 
	}
	function updateMenu(action,showDetail){
		
		var groups=page.menuGroups;
		var icnt,n;
		
		for (icnt=0,n=groups.length;icnt<n;icnt++){
			
			if (showDetail.indexOf(","+groups[icnt]+",")>=0){
				//alert('true'+action.type+action.id+"m"+groups[icnt]);
				document.getElementById(action.type+action.id+"m"+groups[icnt]).style.display='';
			} else {
				//alert('false'+action.type+action.id+"m"+groups[icnt]);
				document.getElementById(action.type+action.id+"m"+groups[icnt]).style.display='none';
			}
		}
	}
	function toggleView(data){
		var element=document.getElementById(data.id);
		var icnt,n;
		switch(data.prop){
			case 'display':
				if (element.style.display==''){
					element.style.display="none";
				} else {
					element.style.display='';
				}
				break;
			case 'enabled':
				element.disabled=!element.disabled;
				break;
			case 'customCheck':
				if (data.element.value==data.checkValue && data.element.checked){
					element.style.display="";
				} else {
					element.style.display="none";
				}
				break;
		}
		if (!data.doFunc) return;
		for (icnt=0,n=data.doFunc.length;icnt<n;icnt++){
			data.doFunc[icnt](data);
		}
	}
	function showPanelContent(data){
		
		if (page[data.type]){
			if (data.id==page[data.type]) return;
			if (data.className) document.getElementById(data.type+page[data.type]+"menu").className=data.className;
			document.getElementById(data.type+page[data.type]+"view").style.display="none";
		}
		if (data.className) document.getElementById(data.type+data.id+"menu").className=data.className+"Select";
		page[data.type]=data.id;
		if (document.getElementById(data.type+"Title")) document.getElementById(data.type+"Title").innerHTML=page[data.type+"Menus"][data.id].text;
		var panel=document.getElementById(data.type+data.id+"view");
		panel.style.display="";

		if (data.extraFunc) data.extraFunc();
	}
	/* Image manipulation */
	function attachFileInputs(formName){
		var icnt,n;
		var form=document.forms[formName];
		var imgForm=document.forms["fileUpload"];
		var image_list="";
		document.getElementById("image_list").value='';
		icnt=0;
		while(icnt<form.elements.length){
			if (form.elements[icnt].type && form.elements[icnt].type=="file" && form.elements[icnt].value!=''){
				image_list+=","+form.elements[icnt].name;
				imgForm.appendChild(form.elements[icnt]);
			} else {
				icnt++;
			}
		}
		if (image_list=='') return false;
		image_list=image_list.substr(1);
		document.getElementById("image_list").value=image_list;
		return true;
	}
	function deAttachFileInputs(removeTotal){
		var icnt,n;
		var form=document.forms["fileUpload"];
		var image_list='';
		for (icnt=0,n=form.elements.length;icnt<n;icnt++){
			if (form.elements[icnt].type=="file"){
				if (removeTotal){
					try{
						document.forms["fileUpload"].removeChild(form.elements[icnt]);
					}catch(e){
						form.elements[icnt].parentNode.removeChild(form.elements[icnt]);
					}
				} else {
					document.getElementById(form.elements[icnt].id+"_container").appendChild(form.elements[icnt]);
				}
				icnt--;
				n--;
			}
		}
		document.getElementById("image_list").value='';
	}
	function doImageResult(result){
		var rObj=false;
		var key;
		var action;
		var splt;
		var error=true;
		var errorText='Err:Images Not Saved';
		if (document.getElementById("ajxLoad")){
			document.getElementById("ajxLoad").style.display="none";
		}
		if (result!=''){
			if (result.indexOf('SUCCESS')>=0){
				splt=result.split("@sep@");
				if (splt[1] && splt[1]!=''){
					splt[1]=ajxDecrypt(splt[1]);
					eval("rObj="+splt[1]);
					if (rObj){
						for (key in rObj) {
							if (rObj[key]!="") document.getElementById(key).value=rObj[key];
						}
						page.lastAction.imgProcessed=true;
						page.lastAction.message=page.lastAction.message1;
						error=false;
					}
				}
			} else if (result.indexOf('Err:')>=0){
				errorText=result;
			}
		}
		deAttachFileInputs(!error);
		if (error){
			doErrorResult(errorText,page.lastAction);
		} else {
			doUpdateAction(page.lastAction);
		}
	}
	
	function doJASON(data,action){
       
		var list,icnt,n;
		var command,key;
		if (data=="") return;
		data=ajxDecrypt(data);
		eval("list="+data);
		for(command in list){
			switch(command){
				case 'replace':
					for (key in list[command]){
						if (document.getElementById(key)) document.getElementById(key).innerHTML=list[command][key];
					}
					break;
				case 'attributesArray':	
					for (key in list[command]){
						page.attributesArray[key]=list[command][key];
					}
					break;
				case 'prevAction':
					page.locked=false;
					page.lastAction=false;
					page.opened[action.type]=list[command];
					break;
				case 'deleteRow':
					var row=document.getElementById(list[command].type+list[command].id+"row");
					
					row.parentNode.removeChild(row);
					delete page.opened[action.type];
					setAlternateColors(action.type);
					break;
				case 'deleteRowMulti':
                    try{  
                        for (const [index, id] of Object.entries(list['deleteRowMulti'])){
                            var row=document.getElementById("cat"+id+"row");
                            row.parentNode.removeChild(row);                    
                            delete page.opened[action.type];
                            setAlternateColors(action.type);
                        }
                        }
                    catch(error){console.log(error);}
					break;
                case 'reduceTreeMulti':
//                    for (const [index, id] of Object.entries(list['reduceTreeMulti'])){
//                        var parent=page.treeList[list[command]].parent;
//                        var level=page.treeList[list[command]].level;
//                        delete page.treeList[action.id];
//                        page.treeList[parent].totalchilds--;
//                        page.treeList[parent].childs--;
//                        page.treeList["level"+level]--;
//                    }
					break;
					//Graeme sept 2012
				case 'deleteRowImg':
					var row=document.getElementById(list[command].type+list[command].id+"message");					
					row.parentNode.removeChild(row);
					var row=document.getElementById(list[command].type+list[command].id+"message_1");
					row.parentNode.removeChild(row);
					var row=document.getElementById("image_"+list[command].id+"_cat");
					document.getElementById(row.innerHTML = '<div class="noImage">No Image</div>');
					setAlternateColors(action.type);
					break; 
					//end sept 2012
				case 'deleteRowImg2':
					var row=document.getElementById(list[command].type+list[command].id+"message");					
					row.parentNode.removeChild(row);
					var row=document.getElementById(list[command].type+list[command].id+"message_1");
					row.parentNode.removeChild(row);
					var row=document.getElementById("image_"+list[command].id+"_cat");
					document.getElementById(row.innerHTML = '<div class="noImage">No Image</div>');
					setAlternateColors(action.type);
					break;
					//end sept 2012
				case 'template':
					for (key in list[command]){
						page.template[key]=list[command][key];
					}
					break;
				case 'updateMenu':
					updateMenu(action,list[command]);
					break;
				case 'storePage':
					for (key in list[command]){
						page[key]=list[command][key];
					}
					break;
				case 'displayMessage':
					document.getElementById("messageBoardText").innerHTML=list[command].text;
					document.getElementById("messageBoard").style.display="";
					setTimeout("toggleView({id:'messageBoard',prop:'display'})",3000);
					break;
				case 'closeRow':
					closeRow(action.id,action.type);
					delete page.opened[action.type];
					break;
				case 'clearType':
					for (icnt=0,n=list[command].length;icnt<n;icnt++){
						if (page.opened[list[command][icnt]]){
							delete page.opened[list[command][icnt]];
						}
					}
					break;
				case 'moveRow':
					var element=document.getElementById(action.type+action.id+"row");
					var destElement=document.getElementById(action.type+list[command].destID+"row");

					changeBoxStyle(action,"check");
					if (list[command].mode=="up"){
						element.parentNode.insertBefore(element,destElement);
					} else {
						if (destElement.rowIndex==destElement.parentNode.rows.length-1){
							element.parentNode.appendChild(element);
						} else {
							element.parentNode.insertBefore(element,element.parentNode.rows[destElement.rowIndex+1]);
						}
					}
					setAlternateColors(action.type);
					break;
				case 'moveRows':
					var srcStart=document.getElementById(action.type+action.id+"row").rowIndex;
					var destStart=document.getElementById(action.type+list[command].destID+"row").rowIndex;
					var srcEnd,destEnd;
					var table=document.getElementById(action.type+"Table");
					var destPos,icnt,temp;
					
					if (page.treeList[action.id].totalchilds){
						srcEnd=srcStart+page.treeList[action.id].totalchilds;
					} else {
						srcEnd=srcStart;
					}
					if (page.treeList[list[command].destID].totalchilds){
						destEnd=destStart+page.treeList[list[command].destID].totalchilds;
					} else {
						destEnd=destStart;
					}
					var element=document.getElementById(action.type+action.id+"row");
					changeBoxStyle(action,"check");
					if (list[command].mode=="up"){
						destPos=destStart;
					} else {
						destPos=destEnd;
					}
					for (icnt=srcStart;icnt<=srcEnd;icnt++){
						if (list[command].mode=="up"){
							element.parentNode.insertBefore(table.rows[icnt],table.rows[destPos]);
							destPos++;
						} else {
							if (destPos==table.rows.length-1){
								element.parentNode.appendChild(table.rows[srcStart]);
							} else {
								element.parentNode.insertBefore(table.rows[srcStart],table.rows[destPos+1]);
							}
						}
					}
					temp=page.treeList[list[command].destID].pos
					page.treeList[list[command].destID].pos=page.treeList[action.id].pos;
					page.treeList[action.id].pos=temp;
					break;
				case 'reduceTree':
					var parent=page.treeList[list[command]].parent;
					var level=page.treeList[list[command]].level;
					delete page.treeList[action.id];
					page.treeList[parent].totalchilds--;
					page.treeList[parent].childs--;
					page.treeList["level"+level]--;
					break;
				case 'doFunc':
					if (list[command].data && list[command].data!=''){
						setTimeout("doCustomActions('"+list[command].type+"','"+list[command].data+"')",1000);
					}
					break;
				case 'extraParams':
					page.extraParams[action.type]=list[command];
					break;
			}
		}
	}
	function setAlternateColors(type){
		var icnt,n;
		if (!page.alterRows) return;
		var table=document.getElementById(type+"Table");
		for (icnt=1,n=table.rows.length;icnt<n;icnt++){
			if (icnt%2==0){
				table.rows[icnt].className="listItemOdd";
			} else {
				table.rows[icnt].className="listItemEven";
			}
		}
	}
	function cloneObject(what){
		var i;
		for (i in what){
			if (typeof what[i]=='object'){
				this[i]=new cloneObject(what[i]);
			} else {
				this[i]=what;
			}
		}
	}
	function doRound(x,places){
		return Math.round(x * Math.pow(10,places)) / Math.pow(10,places);
	}
	function clearAll(listRemove){
		while(listRemove.options.length>0){
			listRemove.options[listRemove.options.length-1]=null;
		}
	}
	function addOption(optionElement,text,value,pos){
		option=document.createElement('OPTION');
		option.text=text;
		option.value=value;
		if (optionElement.add.length==2){
			optionElement.add(option,null);
		} else {
			optionElement.add(option,pos);
		}
	}
	function sliceObjectArray(object,pos){
		var temp=[],icnt,n,key,jcnt=0;
		for (icnt=0,n=object.length;icnt<n;icnt++){
			if (icnt!=pos){
				for(key in object[icnt]){
					if (!temp[jcnt]) temp[jcnt]={};
					temp[jcnt][key]=object[icnt][key];
				}
				jcnt++;
			}
		}
		return temp;
	}
	function selectComboValue(element,value){
		var icnt,n;
		for(icnt=0,n=element.options.length;icnt<n;icnt++){
			if (element.options[icnt].value==value){
				element.selectedIndex=icnt;
				break;
			}
		}
	}
	function getMselectValues(element,noarray,name){
		var temp='';
		if (element){
			if(element.length>0) {
				for(icnt=0,n=element.length;icnt<n;icnt++){
					if(element[icnt].checked) {
						if (noarray){
							temp+=","+element[icnt].value;
						} else{
							temp+=element[icnt].name+"="+element[icnt].value+"&";
						}
					}
				}
			}
			else {
				if(element.checked) {
					if (noarray){
						temp+=","+element.value;
					} else{
						temp+=element.name+"="+element.value+"&";
					}
				}
			}
		} 
		if (noarray){
			if (temp!='') temp=temp.substr(1);
			temp=name+"="+temp+"&";
		}
		return temp;
	}
	function isEmpty(ctlName){
		var value;
		if (ctlName && ctlName.length>0 && ctlName[0]){
			for (icnt=0;icnt<ctlName.length;icnt++){
				value=strTrim(ctlName[icnt].value);
				if (value=="") return true;						
			}
		} else if(ctlName) {
			value=strTrim(ctlName.value);
			if (value=="") return true;
		}
		return false;
	}
	function checkMime(control,extArray){
		var pos,extension,matchFound,icnt,n;
		pos=control.value.lastIndexOf(".");
		if (pos<0) return false;
		extension=control.value.substr(pos+1).toLowerCase();
		matchFound=false;
		for (icnt=0,n=extArray.length;icnt<n;icnt++){
			if (extension==extArray[icnt]){
				matchFound=true;
				break;
			}
		}
		return matchFound;
	}
	function sortValidate(){
		if (page.locked) return false;
		return true;
	}
	function strTrim(str){  
		if(!str || typeof str != 'string')  
			return '';  
		return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
	} 
	function doDynamicTable(action){
		var table=document.getElementById(action.id);
		if (!table) return;
		var row,col,icnt,n;
		switch(action.cmd){
			case 'edit':
				row=table.rows[action.rowpos];
				if (!row) return;
				for (icnt=0,n=action.cols.length;icnt<n;icnt++){
					row.cells[icnt].innerHTML=action.cols[icnt];
				}
				return action.rowpos;
			case 'add':
				row=table.insertRow(action.rowpos);
				row.className=action.rowClassName;
				if (action.rowpos<=0) action.rowpos=table.rows.length-1;
				for (icnt=0,n=action.cols.length;icnt<n;icnt++){
					col=row.insertCell(icnt);
					col.className=action.colClassName;
					col.innerHTML=action.cols[icnt];
				}
				return action.rowpos;
			case 'delete':
				table.deleteRow(action.rowpos);
				break;
		}
	}
	function ajxDecrypt(data){
		var result='';
		if (!page.crypted) return data;
		for(icnt=0,n=data.length;icnt<n;icnt++){
			result+=String.fromCharCode(page.AJX_KEY ^ data.charCodeAt(icnt));
		}
		return result;
	}
	function appendExtraParams(action){
		var extra;
		var key,result='';
		if (!page.extraParams) return;
		extra=page.extraParams[action.type];
		if (!extra) return;
		
		for (key in extra){
			if (extra[key]!='' && (!action.params || action.params.indexOf(key+'=')==-1)){
				result+='&'+key+'='+extra[key];
			}
		}
		action.params+=result;
	}

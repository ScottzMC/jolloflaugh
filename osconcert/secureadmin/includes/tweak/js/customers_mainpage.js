// JavaScript Document

function doOrderBy(action)
{
	//alert(action.type+'  '+action.get+'  '+action.params+' '+action.message);
	//if (page.locked || (action.get!='Search' && page.searchMode)) return;
		if (action.closePrev && !closePreviousOpened(action)) return;
		checkMessageDisplay(action);
		page.lastAction=action;		
		do_get_command(page.link+'?AJX_CMD='+action.get+'&order='+action.params+'&value='+action.value+'&search='+action.searchparam);
}

function doCustomerSearch(mode)
{
	if (mode=="reset") {
		document.getElementById("psearch").value='';		
		page.searchMode=false;
		doPageAction({'id':-1,'type':'custord','get':'GetCustomersDetail','result':doTotalResult,'message':page.template["INFO_LOADING_DATA"]});
	} else {	
		var value=strTrim(document.getElementById("psearch").value);	
		if(value=='') {
			doPageSearch({'id':-1,'type':'custord','get':'GetCustomersDetail','result':doTotalResult,params:'search='+value,'message':page.template["UPDATE_DATA"]});
			page.searchMode=true;
		} else {
			//alert(value);
			doPageSearch({'id':-1,'type':'custord','get':'GetCustomersDetail','result':doTotalResult,params:'search='+value,'message':page.template["UPDATE_DATA"]});
			page.searchMode=true;
		}
	}
}

function doPageSearch(action){

		//if (page.locked || (action.get!='Search' && page.searchMode)) return;
		if (action.closePrev && !closePreviousOpened(action)) return;
		checkMessageDisplay(action);
		page.lastAction=action;
		do_get_command(page.link+'?AJX_CMD='+action.get+'&'+action.params);

	}


  function doItemUpdate() {
    var data='',temp,element,icnt,n,key,key1,key2,quantity,weight,sku;
		var frm=document.forms["customers"];

        for (key in page.updateData){
            data+=key + '=' + page.updateData[key] + '&';
        }
        data+='customers_id=' + frm.customers_id.value + '&';
	if(document.getElementById('chk_create_order') && document.getElementById('chk_create_order').checked)
		data+='create_order=' + document.getElementById('chk_create_order').value + '&';
       
		command=page.link+"?AJX_CMD=Update&RQ=A&" + new Date().getTime();
		// define the method to handle server responses
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
    }


  function doItemUpdatePwd() {
    var data='',temp,element,icnt,n,key,key1,key2,quantity,weight,sku;
		var frm=document.forms["customerspwd"];

        for (key in page.updateData){
            data+=key + '=' + page.updateData[key] + '&';
        }
        data+='customers_id=' + frm.customers_id.value + '&';
       
		command=page.link+"?AJX_CMD=UpdatePwd&RQ=A&" + new Date().getTime();
		// define the method to handle server responses
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
    }

  function check_key(e){
     var kc=0;
   	  if (window.event)
	    kc=window.event.keyCode;
	  else if (e)
	 	kc=e.which;
	  else
		kc=0;
	  if(kc==13) doCustomerSearch('');
   }
   
	var errors='';
	var validation='';
	function getCountryStates(){	
		var data='',action='';
		var form=document.forms[page.formName];
		var this_=document.forms[page.formName].elements["entry_country"];
		id=this_.options[this_.selectedIndex].value;
		if(id) {
			data='country_id='+id;
			action={'get':'StateChanges','id':'drop_down','params':data,'result':doStateresult};
			doSimpleAction(action);
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
	else if(!check_password_strength(pwd,obj.linkField.textbox_min_length))
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
	function doStateresult(result){
		var icnt,n;
		result_splt=result.split("^");
		if(result!='' && result_splt[0]=='show_state'){
			var textArr=new Array();
			var valueArr=new Array();
			valueArr=result_splt[1].split("{}");
			textArr=result_splt[2].split("{}");
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
	}

	function pwdValidate(){
		var icnt,n,pass=true,fieldDesc,errorText='';
		page.errors=[];
		page.updateData={};
		page.formName='customerspwd';

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
	function customerValidate(){
		var icnt,n,pass=true,fieldDesc,errorText='';
		page.errors=[];
		page.updateData={};
		page.formName='customers';

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
				value=element.options[element.selectedIndex].value;
				if (fieldDesc['required']=='Y' && element.selectedIndex<=0){
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
                value='N'
                if(element.checked)
                    value=element.value;
				if (fieldDesc['required']=='Y' && !element.checked){
					pass=false;
				}
				break;
			case 'A':
			default:



				value=str_trim(element.value);
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
                   name=element.name.split('_');
                        if(!name[2])
                            name[2]='';

                   fieldDesc["error_text"]='<?php echo ERROR_TEXT_TELEPHONE ?>';/*name[1]+' '+name[2]+' number should contain numeric values or contain no spaces'*/;
                pass=false;
                }
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

    validation.check__customers_discount=function(fieldDesc){
		var value,element,pass=true;
        element=document.forms[page.formName].elements['customers_discount'];
		value=str_trim(element.value);
		if (fieldDesc['required']=='Y' && value==''){
            pass=false;
            page.errors[page.errors.length]=fieldDesc['error_text'];
        }


        for (i=0; i<value.length; i++)
        {
            if ((value.charCodeAt(i)<48 || value.charCodeAt(i)>57) && value.charCodeAt(i) !== 44  && value.charCodeAt(i) !== 46)
            {
               page.errors[page.errors.length]=fieldDesc['error_text'];
               pass=false;
               break;
            }
        }
        if(pass) {
            page.updateData[fieldDesc["uniquename"]]=value;
             page.updateData['customers_discount_sign']=document.forms[page.formName].elements['customers_discount_sign'].value;


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

    validation.check__suspend_from=function(fieldDesc){
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

    validation.check__resume_from=function(fieldDesc){
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
			page.errors[page.errors.length]= fieldDesc['error_text'];
		}
        if (pass){
			page.updateData[fieldDesc['uniquename']]=value;
            page.updateData['customers_confirm_email_address']=value1;
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

		source=parseInt(document.forms[page.formName].elements['entry_source'].value);
		source_other=str_trim(document.forms[page.formName].elements['entry_source_other'].value);
		if (fieldDesc['error_text']!=''){
			splt=fieldDesc['error_text'].split("##");
		}
		if (fieldDesc['required']=='Y' && (!source || source<=0)) {
			pass = false;
			page.errors[page.errors.length]=splt[0];
		} else if (fieldDesc['required']=='Y' && parseInt(source)==9999 && source_other==''){
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
			page.updateData["customers_password"]=password;
			page.updateData["customers_password_confirm"]=password_confirm;
		}
		return pass;
	}

    function IsValidDate(DateToCheck, FormatString) {
  var strDateToCheck;
  var strDateToCheckArray;
  var strFormatArray;
  var strFormatString;
  var strDay;
  var strMonth;
  var strYear;
  var intday;
  var intMonth;
  var intYear;
  var intDateSeparatorIdx = -1;
  var intFormatSeparatorIdx = -1;
  var strSeparatorArray = new Array("-"," ","/",".");
  var strMonthArray = new Array("jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec");
  var intDaysArray = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

  strDateToCheck = DateToCheck.toLowerCase();
  strFormatString = FormatString.toLowerCase();

  if (strDateToCheck.length != strFormatString.length) {
    return false;
  }

  for (i=0; i<strSeparatorArray.length; i++) {
    if (strFormatString.indexOf(strSeparatorArray[i]) != -1) {
      intFormatSeparatorIdx = i;
      break;
    }
  }

  for (i=0; i<strSeparatorArray.length; i++) {
    if (strDateToCheck.indexOf(strSeparatorArray[i]) != -1) {
      intDateSeparatorIdx = i;
      break;
    }
  }

  if (intDateSeparatorIdx != intFormatSeparatorIdx) {
    return false;
  }

  if (intDateSeparatorIdx != -1) {
    strFormatArray = strFormatString.split(strSeparatorArray[intFormatSeparatorIdx]);
    if (strFormatArray.length != 3) {
      return false;
    }

    strDateToCheckArray = strDateToCheck.split(strSeparatorArray[intDateSeparatorIdx]);
    if (strDateToCheckArray.length != 3) {
      return false;
    }

    for (i=0; i<strFormatArray.length; i++) {
      if (strFormatArray[i] == 'mm' || strFormatArray[i] == 'mmm') {
        strMonth = strDateToCheckArray[i];
      }

      if (strFormatArray[i] == 'dd') {
        strDay = strDateToCheckArray[i];
      }

      if (strFormatArray[i] == 'yyyy') {
        strYear = strDateToCheckArray[i];
      }
    }
  } else {
    if (FormatString.length > 7) {
      if (strFormatString.indexOf('mmm') == -1) {
        strMonth = strDateToCheck.substring(strFormatString.indexOf('mm'), 2);
      } else {
        strMonth = strDateToCheck.substring(strFormatString.indexOf('mmm'), 3);
      }

      strDay = strDateToCheck.substring(strFormatString.indexOf('dd'), 2);
      strYear = strDateToCheck.substring(strFormatString.indexOf('yyyy'), 2);
    } else {
      return false;
    }
  }

  if (strYear.length != 4) {
    return false;
  }

  intday = parseInt(strDay, 10);
  if (isNaN(intday)) {
    return false;
  }
  if (intday < 1) {
    return false;
  }

  intMonth = parseInt(strMonth, 10);
  if (isNaN(intMonth)) {
    for (i=0; i<strMonthArray.length; i++) {
      if (strMonth == strMonthArray[i]) {
        intMonth = i+1;
        break;
      }
    }
    if (isNaN(intMonth)) {
      return false;
    }
  }
  if (intMonth > 12 || intMonth < 1) {
    return false;
  }

  intYear = parseInt(strYear, 10);
  if (isNaN(intYear)) {
    return false;
  }
  if (IsLeapYear(intYear) == true) {
    intDaysArray[1] = 29;
  }

  if (intday > intDaysArray[intMonth - 1]) {
    return false;
  }

  return true;
}


function IsLeapYear(intYear) {
  if (intYear % 100 == 0) {
    if (intYear % 400 == 0) {
      return true;
    }
  } else {
    if ((intYear % 4) == 0) {
      return true;
    }
  }

  return false;
}

function doCustomActions(type,data) {
 
    switch(type) {
        case 'new':
            location.href="create_order_new.php?top=1";
        break;
        case 'editor':
            eval(data+'()');
        break;
         
       default:
         if(page.opened[type]) {
                if(data=='display') {
                eval("doInfoAction({'id':'" + page.opened[type].id + "','get':'InfoDetails','style':'boxLevel1','type':'custord','params':'rID=" + page.opened[type].id +"'})");
                } else if(data=='hide') {
                    hide_info();
                }
            } else {
                   hide_info();
            }
        break;
    }
    
}

function doInfoAction(action) {
	page.lastAction={'result':doInfoResult,'id':action.id};
	action.message=page.template["UPDATE_INFO"];
	checkMessageDisplay(action);
	do_get_command(page.link + '?AJX_CMD=' + action.get + '&' + action.params);
}
function doInfoResult(result) {
	document.getElementById('cust_infos').style.display='';
	document.getElementById('cust_infos').innerHTML=result;
}

function hide_info() {
    if(document.getElementById('cust_infos')) {
        document.getElementById('cust_infos').style.display='none';
        document.getElementById('cust_infos').innerHTML='';
    }
	if(document.getElementById("psearch")) document.getElementById("psearch").value='';
}


function paymentValidate(){
	var lastError="",flag=0;
    var frm=document.forms["customer_wallet"];
		if(str_trim(document.getElementById('wallet_amount').value=='') || document.getElementById('wallet_amount').value<=0 || isNaN(document.getElementById('wallet_amount').value)){
            error=true;
            lastError+='* ' + page.template["ERROR_WALLET_AMOUNT"]+"\n";
		}
        if(page.payment_cnt==1)
            flag=1;
        else if(page.payment_cnt>1){
            for(i=0,len=frm.elements['payment'].length;i<len;i++){

                if(frm.elements['payment'][i].checked==true)
                    flag=1;
            }
        }

		if(flag==0){
            lastError+='* ' + page.template["ERROR_PAYMENT"]+"\n";
		}
        if(lastError!='') {
            alert(lastError);
            return false;
        } else return true;
  }

  function doPaymentUpdate() {
        var data='',key,frm,select_payment;
		frm=document.forms["customer_wallet"];
        payment_chk=false;

        for (icnt=0,n=frm.length; icnt<n; icnt++) {
            if(frm[icnt].type=='radio') {
                if(frm[icnt].checked==true) {
                    value=frm[icnt].value;
                    data+=frm[icnt].id + "=" + value + '&';
                }
            } else {
                value=frm[icnt].value;
                data+=frm[icnt].id + "=" + value + '&';
            }
        }

		command=page.link+"?AJX_CMD=WalletUpdateConfirm&RQ=A&" + new Date().getTime();
		// define the method to handle server responses
		xmlHttp.open("POST", command, true);
		xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(data);
  }

  // wallet upload payment
function selectwalletRowEffect(object, buttonSelect ,paymentSelected) {
	var frm=document.customer_wallet;
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'defaultSelected';
  object.className = 'defaultSelected';
  selected = object;

// one button is not an array
  if (frm.payment[0]) {
    if (frm.payment[buttonSelect]) {frm.payment[buttonSelect].checked=true;
		payment_value=frm.payment[buttonSelect].value;
	}
  } else {
    frm.payment.checked=true;
  }
  if (previousPaymentSelected==paymentSelected) return;

  if (previousPaymentSelected!='' && document.getElementById(previousPaymentSelected)){
  	document.getElementById(previousPaymentSelected).style.display="none";
  }
  if (document.getElementById(paymentSelected)){
  	document.getElementById(paymentSelected).style.display="";
  }
  previousPaymentSelected=paymentSelected;
}

function doCheckPaymentResult (result,action) {
    var result_splt=result.split("|sep|");
    if(result_splt[0] !='' && result_splt[1]!='' && result_splt[0]=='payment_error') {
        if(document.getElementById('show_payment_error')) {
            document.getElementById('show_payment_error').style.display="";
            document.getElementById('payment_error_text').innerHTML=result_splt['1'];
        }
    } else {
        if(document.getElementById('show_payment_error')) {
            document.getElementById('show_payment_error').style.display="none";
            document.getElementById('payment_error_text').innerHTML='';
        }
        doDisplayResult(result,action);
    }

}

function doPaymentProcess(action) {
    if (action.closePrev && !closePreviousOpened(action)) return;
    checkMessageDisplay(action);
    page.lastAction=action;
    do_get_command(page.link+'?AJX_CMD='+action.get);
}

function customermailValidate() {
	var error=false; var error_message='';
    var frm=document.forms["customer_mail"];
		if(frm.customers_email_address.value==0){
            error=true;error_message='* ' + page.template['ERROR_MAIL_EMAIL'] + "\n";
		}
		if(frm.mail_subject.value==''){
		error=true;error_message+='* ' + page.template['ERROR_MAIL_SUBJECT'];
		}
		if(error==true) {
			alert(error_message);
            return false;
		} else{
            return true;
		}

}

function doMailUpdate() {
var data="";
	var form=document.customer_mail;
	data+="customers_email_address="+form["customers_email_address"].value+"&";
	data+="mail_from="+form["mail_from"].value+"&";
	data+="mail_subject="+form["mail_subject"].value+"&";
	data+="message_text="+encodeURIComponent(form["message_text"].value)+"&";
    data+="rID="+form["rID"].value+"&";

   // if(form.check_pwd.checked) {
        data+="check_pwd=" + form.check_pwd.value + "&";
 //   }
//
	command=page.link+"?AJX_CMD=MailPreview&RQ=A&" + new Date().getTime();
	xmlHttp.open("POST", command, true);
	xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
	xmlHttp.onreadystatechange = handleServerResponse;
	xmlHttp.send(data);
}

function AddField(){
	var select_field;
	var select_id;
	if (document.customer_mail.fields.selectedIndex<=-1) return;
 	select_id=document.customer_mail.fields.options[document.customer_mail.fields.selectedIndex].value;
	if (select_id.length>2) return;
	    select_field="%%" + document.customer_mail.fields.options[document.customer_mail.fields.selectedIndex].innerHTML + "%%";
	    select_field=select_field.replace("&nbsp;&nbsp;","");

    if(document.customer_mail.check_pwd.checked==false && select_id=='PW' )
        select_field="--"+"Hidden"+"--";
	  //  editor.insertHTML(select_field);
	  textEditorHtmlContent(select_field);
}

function doEmailEditor(){
	if (page.editorLoaded) return;
	var deElements=Array();
	page.editorControls[0]="message_text";
	textEditorInit();
}


function doMailSend() {
var data="";
	var form=document.mail;
	data+="customers_email_address="+form["customers_email_address"].value+"&";
	data+="mail_from="+form["mail_from"].value+"&";
	data+="mail_subject="+form["mail_subject"].value+"&";
	data+="message_text="+encodeURIComponent(form["message_text"].value)+"&";
    data+="check_pwd=" + form.check_pwd.value + "&";
    data+="rID="+form["rID"].value+"&";

	command=page.link+"?AJX_CMD=MailSend&RQ=A&" + new Date().getTime();
	xmlHttp.open("POST", command, true);
	xmlHttp.setRequestHeader("Method", "POST "+command+" HTTP/1.1");
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
	xmlHttp.onreadystatechange = handleServerResponse;
	xmlHttp.send(data);
}
function doCustomActions1(type,func) {
	eval(func+'()');
}
function pwd(obj){
    if (obj) {
        if(!confirm("Do you want to create a New Password?")){
            document.customer_mail.check_pwd.checked=false;
        }
    }
}

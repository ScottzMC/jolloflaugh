<script>
	var errors='';
	var validation='';

function getCountryStates(){
	var element=document.forms[page.formName].elements["entry_country"];
	id=element.options[element.selectedIndex].value;
	var qry_str="";
	if(id){
		command="<?php echo tep_href_link('getDetails.php','command=show_state','NONSSL',true,false);?>&country_id="+id;
		do_get_command(command);	
	}
}
 /* function checkPWDStrength(obj){
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
 } */
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
        //console.log(obj.linkField.textbox_min_length);
     pwd=obj.value;
    if(pwd=='' || pwd.length==0)
      element.innerHTML='';
    else if(obj.linkField.textbox_min_length>0 && pwd.length<obj.linkField.textbox_min_length)
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
	function do_result(result){
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
	function validateForm(){
		var icnt,n,pass=true,fieldDesc,errorText='';
		page.errors=[];
		page.updateData={};

		for(icnt in page.fieldsDesc){
			fieldDesc=page.fieldsDesc[icnt];
			if (validation["check__"+fieldDesc['uniquename']]){
				pass&=validation["check__"+fieldDesc['uniquename']](fieldDesc);
				//console.log(["check__"+fieldDesc['uniquename']]);
			} else {
				pass&=validation.commonCheck(fieldDesc);
			}
		}
		if (pass){
			document.forms[page.formName].submit();
		} else {
			for (icnt=0,n=page.errors.length;icnt<n;icnt++){
				errorText+="* "+page.errors[icnt]+"\n";
			}
			alert(page.formErrText.replace(/--/g,"\n") + errorText);
		}
	}
	var validation={};
	
	validation.commonCheck=function(fieldDesc){
		var value,element,error,splt,pass=true,icnt,n;
		if (fieldDesc['input_type']=="L") return true;
		var element=document.forms[page.formName].elements[fieldDesc['uniquename']];
        if (!element) return pass;
		switch(fieldDesc['input_type']){
			case 'D':
				value=element.selectedIndex;
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
				/*cartzone strip this because of postcode Freeway bug v233*/
/*element.name=='entry_postcode' || */
            if (element.name=='customers_telephone' || element.name=='customers_second_telephone' || element.name=='customers_fax')
            {

            for (i=0; i<value.length; i++)
            {
                if ((value.charCodeAt(i)<48 || value.charCodeAt(i)>57) && value.charCodeAt(i) !== 44 )
                {   
                   name=element.name.split('_');
                        if(!name[2])
                            name[2]='';
                		if(name[1]=='fax')
                            name[1]='Mobile';
                   fieldDesc["error_text"]='<?php echo ERROR_TEXT_TELEPHONE ?>';/*=name[1]+' '+name[2]+' Number should contain numeric values';*/
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
	
	validation.check__security_code=function(fieldDesc){
		var value,pass=true;
		value=str_trim(document.forms[page.formName].elements[fieldDesc['uniquename']].value);
		if (fieldDesc['required']=='Y' && value==''){
			if (fieldDesc["error_text"].indexOf("##")){
				splt=fieldDesc["error_text"].split("##");
				error=splt[0];
			} else {
				error=fieldDesc["error_text"];
			}
			page.errors[page.errors.length]=error;
			pass=false;
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
    validation.check__customers_photo=function(fieldDesc){
        var value,value1,pass=true;
        value=str_trim(document.forms[page.formName].elements[fieldDesc['uniquename']+'_file'].value);
        value1=str_trim(document.forms[page.formName].elements[fieldDesc['uniquename']].value);
        if (fieldDesc['error_text']==''){
            fieldDesc['error_text']='Image Required##Invalid Image Type##Image Upload Failed';
        }
        
        splt=fieldDesc['error_text'].split("##");
        if (fieldDesc['required']=='Y' && str_trim(value)=='' && str_trim(value1)==''){
            pass=false;
            page.errors[page.errors.length]=splt[0];
        } else if (str_trim(value)!='' && !checkExtension(value)){
            pass=false;
            page.errors[page.errors.length]=splt[1];
        }
        if (pass){
            page.updateData[fieldDesc['uniquename']]=value;
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
			page.updateData["entry_source"]=password;
			page.updateData["entry_source_other"]=password_confirm;
		}
		return pass;
	}
    function checkExtension(data){

        data = data.replace(/^\s|\s$/g, ""); //trims string
        if (data.match(/([^\/\\]+)\.(jpg|jpeg|png|gif)$/i))
            return true;
        else
            return false;
    }
</script>
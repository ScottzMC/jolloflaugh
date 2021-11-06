<?php
/*
	
	
	Freeway eCommerce from ZacWare
	http://www.openfreeway.org
	
	Copyright 2007 ZacWare Pty. Ltd
	
	Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	require('includes/application_top.php'); 
	define('OSCONCERT_HELP','no');
	tep_get_last_access_file();
	$gID=$FREQUEST->getvalue('gID','int');
	$mPath=$FREQUEST->getvalue('mPath');
	
	$date_id_check_query=tep_db_query("SELECT products_model FROM products WHERE products_model='' AND products_sku>0 and products_id>0 and products_status=1");
	if (tep_db_num_rows($date_id_check_query)>0){
		echo $messageStack->add(WARNING_DATE_ID_MISSING,'warning');
	}
		
	if ($gID<=0){
		$config_group_query=tep_db_query("SELECT configuration_group_id,configuration_group_title from " . TABLE_CONFIGURATION_GROUP . " where lower(configuration_access_key)='" . strtolower($CONFIG_ACCESS_KEY) . "'");
		if (tep_db_num_rows($config_group_query)>0){
			$config_group_result=tep_db_fetch_array($config_group_query);
			$gID=$config_group_result['configuration_group_id'];
			define('HEADING_TITLE',$config_group_result['configuration_group_title']);
		} else { 
			$gID=1;
		}
	}
	$action=$FREQUEST->getvalue('action','string',0);
	$params=$FREQUEST->getvalue('params');
	$g_title="";   
	$server_date = getServerDate(true);
	if($gID){
		$config_group_sql = "select configuration_group_title from ".TABLE_CONFIGURATION_GROUP." where configuration_group_id='".(int)$gID."'";
		$g_query=tep_db_query($config_group_sql);
		$g_result = tep_db_fetch_array($g_query);
	}
	if($g_result['configuration_group_title']!=""){
		$g_title=$g_result['configuration_group_title'];
		define('HEADING_TITLE',$g_result['configuration_group_title']);
	} 
	$cId=$FREQUEST->getvalue('id','int',0);
	if($action=='file_uploads' && $FREQUEST->getvalue('id'))  
	{
		$upload_fs_dir = DIR_FS_TEMPLATES.DEFAULT_TEMPLATE.'/'.DIR_WS_IMAGES;
		
		echo "id=" . $FREQUEST->getvalue('id','int',0) . "<br>" . $upload_fs_dir;
		$upload_fs_admin_dir = DIR_FS_ADMIN . DIR_WS_IMAGES;		 
		if(!is_writable($upload_fs_admin_dir) || !is_writable($upload_fs_dir)){
			echo '{upload_error}^';			 
			if(!is_writable($upload_fs_admin_dir)) $warn=sprintf(WARNING_IMAGE_DIRECTORY_WRITEABLE,$upload_fs_admin_dir).'<br>'; 
			if(!is_writable($upload_fs_dir)) $warn.=sprintf(WARNING_IMAGE_DIRECTORY_WRITEABLE,$upload_fs_dir);
			echo "<table border='0' cellspacing='0' cellpadding='2' width='100%'><tr><td class='messageStackError'>".$warn."</td></tr></table>";
		}else {  		

			$up_load = new upload('configuration_file_value', $upload_fs_dir);         
			$file_name = $up_load->filename;                           			         
			$error=false;
			$file_upload="file_upload";
			if($file_name != "osconcert.png"){
				if(file_exists($upload_fs_dir."osconcert.png") && !@unlink($upload_fs_dir."osconcert.png")){								
					$error=true;
					$error_txt=sprintf(DELETE_ERROR,$upload_fs_dir."osconcert.png");
					$error_result='<span alt="'.$error_txt.'" title="'.$error_txt.'"><font color="red">'.((strlen($error_txt)>30)?substr($error_txt,0,30):$error_txt).'</font></span><br>';
				}	
				if(!$error && !@rename($upload_fs_dir.$file_name, $upload_fs_dir."osconcert.png")){					
					$error=true;
					$error_txt=sprintf(RENAME_ERROR,$upload_fs_dir."osconcert.png");				
					$error_result='<span alt="'.$error_txt.'" title="'.$error_txt.'"><font color="red">'.((strlen($error_txt)>30)?substr($error_txt,0,30):$error_txt).'</font></span><br>';				
				}	
				if(file_exists($upload_fs_dir.'osconcert.png'))
				copy($upload_fs_dir."osconcert.png",DIR_FS_ADMIN . DIR_WS_IMAGES . 'osconcert.png');
			}
			if(strlen($file_name)>30) $file_name=substr($file_name,0,30).'...';
			if(!$error) tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = 'osconcert.png', last_modified = '" . tep_db_input($server_date) . "' where configuration_id = '" . (int)$cId . "'");
			echo '{'.$cId.'open}'; 	 	  	   	  		 	 
		}
	}
	if(($params=='file_upload' && $action=='get_details') &&  ($FREQUEST->getvalue('id'))){ 	             	
		// echo '{'.$cId.'open}'.$error_result.'<a href="javascript:do_expand('.$cId.',\'get_details\',\''.$file_upload.'\');">'.((!$file_name)?'...':'osconcert.png').'</a>';
		echo '{'.$FREQUEST->getvalue('id','int',0).'open}^<input type="file" name="configuration_file_value" id="configuration_file_value">'; 	 	  	   	  		 	 
	} 
	// }    		
	if($action){  	  
		if($action=='get_details' && $params!='file_upload')
		{		
			$config_qry=tep_db_query("select configuration_value,configuration_key,set_function from ".TABLE_CONFIGURATION ." where configuration_id='".(int)$cId."'");
			$value_field="";
			if(tep_db_num_rows($config_qry)>0){
				$config=tep_db_fetch_array($config_qry);
				if ( $config['set_function']) 	        	
					eval('$value_field .= ' . stripslashes($config['set_function']) . '"' . html_entity_decode($config['configuration_value']) . '");');	        						        	      
				else{ 
					$width_percnt=round(100/2);
					$value_field = tep_draw_input_field('configuration_value', html_entity_decode($config['configuration_value']),'size='.$width_percnt.' onkeypress="javascript:if(key(event)) do_expand('.(int)$cId.',\'save\');"');
				}	 
				if(strpos($value_field,'name="configuration_value')) $value_field=str_replace('name="configuration_value','name="configuration_value['.$cId.']',$value_field);
				if(strpos($value_field,'name="check_configuration_value')) $value_field=str_replace('name="check_configuration_value','name="configuration_value['.$cId.']',$value_field);			   	        
				if($config['configuration_key']!='REPORT_BUSINESS_DAY_START_TIME'){
				echo '{'.$cId.'}'.$value_field;
				} else{	
				echo '{'.$cId.'}'; ?>
				<?php 		
				$time=explode(':',$config['configuration_value']);
				$hour=$time[0];
				$minu=$time[1];
				$st_times=$hour.':'.$minu;
				?>
					<div>
						<div style="width:40; float:left; border:solid 1px #ADADAD; background:#FFFFFF">
						<INPUT NAME="hrs" ID="hrs" TYPE="TEXT" maxlength="2" onClick="javascript:document.getElementById('selected_item').value='hrs';" SIZE="10" VALUE="<?php echo (($hour)?$hour:'00'); ?>" style="border:none; width:15;" onKeyPress="javascript:return keyRestrict(event)" onKeyUp="javascript:time_value();" >:<INPUT NAME="minutes" ID="minutes" TYPE="TEXT" maxlength="2" SIZE="10" VALUE="<?php echo (($minu)?$minu:'00'); ?>" style="border:none; width:15;" onClick="javascript:document.getElementById('selected_item').value='minutes';" onKeyPress="javascript:return keyRestrict(event)" onKeyUp="javascript:time_value();" >
						</div>
						<div style="float:left">
						<A HREF="JavaScript:" onMouseOver="self.status='';return true">
							<IMG SRC="images/template/updown.gif" BORDER=0 WIDTH=19 HEIGHT=20 ALIGN=TOP USEMAP="#spinner-map" ALT="Click">
						</A>
						<INPUT ID="selected_item" name="selected_item" value="" type="hidden">
						<INPUT ID="configuration_value" name="configuration_value[<?php echo $cId; ?>]" value="<?php echo $st_times; ?>" type="hidden">						
						<MAP NAME="spinner-map">
							<AREA SHAPE="RECT" COORDS="0,0,18,10" HREF="javascript:formatDate('next')" onMouseOver="self.status='';return true">
							<AREA SHAPE="RECT" COORDS="0,12,18,23" HREF="javascript:formatDate('prev')" onMouseOver="self.status='';return true">
						</MAP>
						</div>
					</div>
				<?php
				}
			}      	
		}else if($action=='save') {  		  	
			$params=tep_db_prepare_input($FREQUEST->getvalue('params'));
			$configuration_value=tep_db_prepare_input($FREQUEST->postvalue('configuration_val'));
			
			$configuration_qry = tep_db_query("select configuration_key from " . TABLE_CONFIGURATION . " where configuration_id = '" . (int)$cId . "'");
			if(tep_db_num_rows($configuration_qry)>0) $configuration_res=tep_db_fetch_array($configuration_qry);
			if($configuration_res['configuration_key']=='SHOP_SELL_ITEMS') $configuration_value=str_replace(",","",$configuration_value);    			  		  		  		  		  		  		  		  		  		  		  		  	      	      	      	     	  	     	
			tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . html_entity_decode(tep_db_prepare_input($configuration_value)) . "', last_modified = '" . tep_db_input($server_date) . "' where configuration_id = '" . (int)$cId . "'");   	    	 
			// if($configuration_res['configuration_key']=='EMAIL_SECURITY_ALERTS'){
				// tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . html_entity_decode(tep_db_prepare_input($FREQUEST->postvalue('email_val'))) . "', last_modified = '" . tep_db_input($server_date) . "' where configuration_key = 'SECURITY_ALERT_EMAIL_ADDRESS'");   	    	 
			// }
			$configuration_query = tep_db_query("select configuration_id, configuration_title, configuration_value, use_function,configuration_group_id,configuration_description,set_function from " . TABLE_CONFIGURATION . " where configuration_id = '" . (int)$cId . "'");        
			$total_count= tep_db_num_rows($configuration_query);
			if($total_count){
				$configuration=tep_db_fetch_array($configuration_query);  	    			
				if (tep_not_null($configuration['use_function'])) {
					$use_function = $configuration['use_function'];
					if (preg_match('/->/', $use_function)) {
					$class_method = explode('->', $use_function);
					if (!is_object(${$class_method[0]})) {
					include(DIR_WS_CLASSES . $class_method[0] . '.php');
					${$class_method[0]} = new $class_method[0]();
					}
					$cfgValue = tep_call_function($class_method[1], $configuration['configuration_value'], ${$class_method[0]});
					} else {
					$cfgValue = tep_call_function($use_function, $configuration['configuration_value']);		
					}
				} else 
					$cfgValue = $configuration['configuration_value'];
				$file_upload="";			    
				if(strlen($cfgValue)>30) $cfgValue=substr($cfgValue,0,30).'...';
				if($configuration['set_function']=='file_upload') $file_upload='file_upload';   			    
				if($cfgValue=='') $cfgValue="...."; 	 
				echo '{'.$cId.'open}<a href="javascript:do_expand('.$cId.',\'get_details\',\''.$file_upload.'\');">'.htmlentities($cfgValue).'</a>';
			}   	    		   	    		  		
		}
		exit;
	}        
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html <?php echo HTML_PARAMS; ?>>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
			<title><?php echo TITLE; ?></title>
			<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
			
			<style type="text/css">
				#pup {position:absolute; visibility:hidden; z-index:200; width:130; }
			</style>
           
			<script language="javascript" src="includes/menu.js"></script>
			<script language="javascript" src="includes/general.js"></script>
			<script language="javascript" src="includes/http.js"></script>
         
			<script language="javascript">	
				function key(e){		
					var KeyID = (window.event) ? event.keyCode : e.keyCode;			
					if(KeyID==13) return true;
					else return false;
				}
				//Js Upload Start
				AIM = {
					frame : function(c) {
					var n = 'f' + Math.floor(Math.random() * 99999);
					var d = document.createElement('DIV');
					d.innerHTML = '<iframe style="display:none" src="about:blank" id="'+n+'" name="'+n+'" onload="AIM.loaded(\''+n+'\')"></iframe>';
					document.body.appendChild(d);
					
					var i = document.getElementById(n);
					if (c && typeof(c.onComplete) == 'function') {
						i.onComplete = c.onComplete;
					}
					
					return n;
					},
				
				form : function(f, name) {
					f.setAttribute('target', name);
				},
				
				submit : function(f, c) {
					AIM.form(f, AIM.frame(c));
					if (c && typeof(c.onStart) == 'function') {
						return c.onStart();
					} else {
						return true;
					}
				},
				
				loaded : function(id) {
					var i = document.getElementById(id);
					if (i.contentDocument) {
						var d = i.contentDocument;
					} else if (i.contentWindow) {
						var d = i.contentWindow.document;
					} else {
						var d = window.frames[id].document;
					}
					if (d.location.href == "about:blank") {
						return;
					}
					
					if (typeof(i.onComplete) == 'function') {
						i.onComplete(d.body.innerHTML); 
					}
				}
				
				}
			function startCallback() {
				// make something useful before submit (onStart)
				if(document.getElementById('configuration_file_value') && document.getElementById('configuration_file_value').value==""){
					alert("Please Select the upload image");
					return false;
				}else if(document.getElementById('configuration_file_value') && document.getElementById('configuration_file_value').value!=""){
					var value=document.getElementById('configuration_file_value').value;
					var pos = value.indexOf('.');
					if(value.substr(pos+1,3)=='gif'){
						alert(" Supported Images .png and .jpg only ");
						return false;
					}
				}						
				else return true;
			}
			
			function completeCallback(response) { 	
				do_result(response);
				if(response.indexOf('{upload_error}') < 0){
					window.location.reload(); 
				}  
			}
			//Js Upload End
			
			function check_selected_values(configcheckbox,controlname,countrycontrol,key){				
				return;		
			}
			var desc_array=new Array();
			var previous_id="";
			function do_expand(id,action,params){ 
				previous_id=id;
				var qry="";
				var time_val;
				var hidd_val="";
				if(action=="save" && document.getElementById('cemail_address') && document.getElementById('email_address')){
					var config="configuration_value["+id+"]";		 
					var obj=document.getElementsByName(config);		   		    		   		   		  		  		  		  		  		  	
					if(obj){	   				   				   				   				   				   				   				   			
						if(obj[0].checked){
							err=""
							if(document.getElementById('email_address'.value)== ""){
								err="Email Address is required";
							}
							else{
								if(document.getElementById('email_address').value!=document.getElementById('cemail_address').value)
									err="Email Address does not match with confirm address";
							}
							if(err){
								alert(err);
								return;
							}
						}
						else
							document.getElementById('email_address').value='';
							document.config.email_val.value=document.getElementById('email_address').value;
					}
				}
				if(id) {
					qry="?id="+id;
					var config_value=document.getElementById("config_value_"+id);
					var config_data=document.getElementById("config_data_"+id);
					var action_save=document.getElementById("action_save_"+id);			
					var action_close=document.getElementById("action_close_"+id);
					var img_load=document.getElementById("img_load_"+id);			
					if(img_load && action!='close') img_load.style.display="";
					var desc_div=document.getElementById("desc_"+id);
					if(document.config && document.config.open_val) var hidd_val=document.config.open_val.value;
					//if(desc_div && desc_div.style.display=="") return;
					if(hidd_val){		
						var config_value_hidden=document.getElementById("config_value_"+hidd_val);
						var config_data_hidden=document.getElementById("config_data_"+hidd_val);
						var action_save_hidden=document.getElementById("action_save_"+hidd_val);
						var action_close_hidden=document.getElementById("action_close_"+hidd_val);
						var desc_div_hidden=document.getElementById("desc_"+hidd_val);
						if(config_data_hidden)config_data_hidden.style.display="";
						if(config_value_hidden)config_value_hidden.style.display="none";									
						if(action_save_hidden)action_save_hidden.style.display=action_close_hidden.style.display="none";																 
						if(desc_div_hidden) desc_div_hidden.className='config_title_line';	
						//document.getElementsByName("configuration_file_value")[0].style.display="none";
						if(document.getElementById("desc_"+hidd_val))document.getElementById("desc_"+hidd_val).edit=false;
					}				
					if(document.config && document.config.open_val) document.config.open_val.value=id;	
					if(document.getElementById("image_error"))document.getElementById("image_error").innerHTML="";
					if(document.getElementById("image_error"))document.getElementById("image_error").style.display="none"; 					
				} 		
				if(action) qry+="&action="+action;	
				if(action=='get_details' && params=='file_upload' && action_save && action_close && config_data && img_load) {
					action_save.style.display=action_close.style.display="";
					config_data.style.display=img_load.style.display="none";
					if(document.getElementsByName("configuration_file_value")) document.getElementsByName("configuration_file_value")[0].style.display="";
					if(desc_div) desc_div.className='config_box';
					document.getElementById("desc_"+id).edit=true;
					kill();
					prev_id="";
					cEvent=false;
				}else if(action=='save' || action=='get_details' && action!=='freeway_image_upload'){	
					if(action=='save'){	 
						var config="configuration_value["+id+"]";		 
						var vals="";			 		  
						var obj=document.getElementsByName(config);		   		    		   		   		  		  		  		  		  		  	
						if(obj){	   				   				   				   				   				   				   				   			
							i=0;
							var chk_box=false;
							while(true){	
								if(!obj[i]) break;	   			
								if(obj[i].type=='radio'){ 	   					   					   					   				
									if(obj[i].checked) vals=obj[i].value;	   						   			
								}else if(obj[i].type=='select-one' && obj[i].selectedIndex!=0){ 	
									vals+=obj[i].options[obj[i].selectedIndex].value+',';				   					   			
									chk_box=true;
								}else if(obj[i].type=='checkbox' && obj[i].checked){
									chk_box=true;
									vals+=obj[i].value+',';	   			
								}else if(obj[i].type!='checkbox' && obj[i].type!='hidden') {
									vals=obj[i].value;
								}else{
									if(obj[i].value!=''){
										time_val=obj[i].value;
										time_val=time_val.split(':');
									 	if(time_val[0].length<2) time_val[0]='0'+time_val[0];
									 	if(time_val[1]){
											if(time_val[1].length<2) time_val[1]='0'+time_val[1];
											vals=time_val[0]+':'+time_val[1];
										}	
									}
								}
								i++;		   	
							} 	   		  
							if(chk_box){ 
								if(vals.substr((vals.length-1),1)==','){ 
								vals=vals.substr(0,vals.length-1);}
							}
						}	  
						var configuration_value=document.config.configuration_val;	   	  
						if(configuration_value && id){
							configuration_value.value=vals;	   	 	
//							alert(configuration_value.value+'dfvg>>'+document.getElementById('configuration_value').value);   	  	    	   
						}
					}	   
					if(params) qry+="&params="+params;
					command="<?php echo tep_href_link(FILENAME_CONFIGURATION);?>"+qry;
					if(document.getElementById("config_value_"+id) && document.getElementById("config_value_"+id).style.display!='')
					document.getElementById("config_value_"+id).innerHTML="";
					if(action=='get_details' && document.getElementById("config_value_"+id).innerHTML==""){
						document.getElementById("desc_"+id).edit=true;
						kill();
						prev_id="";
						cEvent=false;
						do_get_command(command);
					}else if(action=='get_details' && document.getElementById("config_value_"+id).innerHTML!="" && config_value){	   	  
						document.getElementById("desc_"+id).edit=true;
						kill();
						prev_id="";
						cEvent=false;
						config_value.style.display=''; 
						config_data.style.display=img_load.style.display='none';
						var desc_div=document.getElementById("desc_"+id);if(desc_div) desc_div.className='config_box';
						var action_save=document.getElementById("action_save_"+id);			
						var action_close=document.getElementById("action_close_"+id);
						if(action_save && action_close) action_save.style.display=action_close.style.display='';
						if(document.getElementById("image_error"))document.getElementById("image_error").innerHTML="";
						if(document.getElementById("image_error"))document.getElementById("image_error").style.display="none";
					}	   	   
					if(action=='save') {
						if(document.getElementById("desc_"+id))document.getElementById("desc_"+id).edit=false;
						do_post_command('config',command);
					}
				}else if(action=='close' && id){
					if(document.getElementById("config_value_"+id))document.getElementById("config_value_"+id).innerHTML = '';
					var config_data=document.getElementById("config_data_"+id);
					var config_value=document.getElementById("config_value_"+id);
					if(config_data && config_value) {		
						//if(document.getElementsByName("configuration_file_value")) document.getElementsByName("configuration_file_value").style.display="none";
						config_value.style.display="none";
						config_data.style.display='';
					}	   
					if(document.getElementById("desc_"+id)) document.getElementById("desc_"+id).edit=false;
					var desc_div=document.getElementById("desc_"+id);
					if(action_save && action_close) action_save.style.display=action_close.style.display='none';
					if(desc_div) desc_div.className='config_title_line'; 
					if(document.getElementById("image_error"))document.getElementById("image_error").innerHTML="";
					if(document.getElementById("image_error"))document.getElementById("image_error").style.display="none";
				}else if(action=='freeway_image_upload' && id){ 
					document.getElementById('config_value_'+id).style.display='';
					document.getElementById('action_save_'+id).style.display='';
					document.getElementById('action_close_'+id).style.display='';
					document.getElementById('config_data_'+id).style.display='none';
					document.getElementById("desc_"+id).className="config_box";
					document.getElementById("img_load_"+id).style.display="none";
				}else if(action=='image_upload_close' && id){ 
					document.getElementById('config_data_'+id).style.display='';
					document.getElementById('config_value_'+id).style.display='none';
					document.getElementById('action_save_'+id).style.display='none';
					document.getElementById('action_close_'+id).style.display='none';
					document.getElementById("desc_"+id).className="config_title_line";
					document.getElementById("img_load_"+id).style.display="none";
					if(document.getElementById("image_error"))document.getElementById("image_error").innerHTML="";
					if(document.getElementById("image_error"))document.getElementById("image_error").style.display="none";
				}	 
			}
			function do_result(result){
				// alert (result);
				if(result){
					var ans=result.split('^'); 
					var error_result="";
					id=ans[0].substr(1,ans[0].indexOf("}")-1);
					//id=result.substr(1,result.indexOf("}")-1);
					type="";
					var config_data=document.getElementById("config_data_"+id);
					var config_value=document.getElementById("config_value_"+id);
					var action_save=document.getElementById("action_save_"+id);			
					var desc_div=document.getElementById("desc_"+id);
					if(id.indexOf('open')>0) {
						id=id.substr(0,id.length-4);
						var config_data=document.getElementById("config_data_"+id);
						var config_value=document.getElementById("config_value_"+id);
						var img_load=document.getElementById("img_load_"+id);			
						var action_save=document.getElementById("action_save_"+id);			
						var desc_div=document.getElementById("desc_"+id);
						var action_close=document.getElementById("action_close_"+id);
						if(config_data) config_data.innerHTML=result.substr(id.length+6);						  														
						if(config_value) config_value.innerHTML=ans[1];	
						if(action_save && action_close)action_save.style.display=action_close.style.display="";
						if(config_data)config_data.style.display=img_load.style.display="none";
						// if(document.getElementsByName("configuration_file_value")) document.getElementsByName("configuration_file_value").innerHTML=ans[1];
						if(config_value)config_value.style.display='';
						if(desc_div) desc_div.className='config_box';
						if(document.getElementById("desc_"+id))document.getElementById("desc_"+id).edit=true;
						kill();
						prev_id="";
						cEvent=false;
						
						if(document.getElementById("image_error"))document.getElementById("image_error").innerHTML="";
						if(document.getElementById("image_error"))document.getElementById("image_error").style.display="none";
						
						//if(document.getElementsByName("configuration_file_value")) document.getElementsByName("configuration_file_value")[0].style.display="";
						do_expand(id,'close');
						return; 
					}else if(result.indexOf('upload_error')>0) {			
						result_split=result.split('^');			
						if(result_split.length>0){
							if(result_split[1]) error_result=result_split[1]+"\n";
							document.getElementById('image_error').style.display='';
							document.getElementById('image_error').innerHTML=error_result;				
							return true;
						} 
						return false;
					}	
					if(id){  
						var desc_div=document.getElementById("desc_"+id); 			
						var action_save=document.getElementById("action_save_"+id);			
						var action_close=document.getElementById("action_close_"+id);
						var img_load=document.getElementById("img_load_"+id);
					}		
					if(desc_div) desc_div.className='config_box';				
					if(result.substr(result.indexOf("}")+1) && config_data && config_value){
						if(action_save && action_close) action_save.style.display=action_close.style.display='';					
						if(img_load) img_load.style.display="none";
						result=result.substr(result.indexOf("}")+1);
						
						config_value.style.display='';
						config_data.style.display='none';
						config_value.innerHTML=result;
					} 		
				}	
			}
			function do_action(id,action){ 	
				var desc_div=document.getElementById("desc_"+id);
				if(desc_div.className!='config_box_over' && action=='open' && desc_div.className!='config_box'){
					desc_div.className='config_box_over';
				}else if(action=='close' && desc_div.className!='config_box'){
					desc_div.className='config_title_line';
				}
			}
			function formatDate(arg) {
				var hrs,minute,minu,hour;
				var selected_time=window.document.getElementById('selected_item').value;
				
				if(selected_time==''){
					window.document.getElementById('selected_item').value='minutes';
					var selected_time=window.document.getElementById('selected_item').value;
				}
				if(selected_time!=''){
			//	var time = window.document.getElementById(selected_time).value;

				if(window.document.getElementById('hrs')) 
					hrs=window.document.getElementById('hrs');
				if(window.document.getElementById('minutes')) 
					minute=window.document.getElementById('minutes');

				var hour = parseFloat(hrs.value);
				var minu = parseFloat(minute.value);
				if(arg=='next'){
				if(selected_time=='hrs'){
					hour++;
					if(hour>23) hour=0;
				} else if(selected_time=='minutes'){
					minu++;
					if(minu>59){ 
						minu=0; hour++;
						if(hour>23) hour=0; 
					}
				}
				} else{
				if(selected_time=='hrs'){
					hour--;
					if(hour<0){ 
					hour=0;
					}
				} else if(selected_time=='minutes'){
					minu--;
					if(minu<0){ 
						minu=59;
						if(hour>0){
							hour--;
						} else{
							hour=23;
						}
					} 
				}
				}
				hrs.value = ((hour < 10 ) ? '0' + hour : hour);
				minute.value = ((minu < 10) ? '0' + minu : minu);
				window.document.getElementById('configuration_value').value=hrs.value+':'+minute.value;
				}
			}
			function time_value(){
			if((window.document.getElementById('hrs')) && (window.document.getElementById('minutes'))){
				var timeObj=window.document.getElementById('hrs');
				var minObj=window.document.getElementById('minutes');
				if((timeObj.value!='') && (minObj.value!='')){
					if(parseFloat(timeObj.value)>23){ 
					timeObj.value=23;
					} 
/*					else
					if(parseFloat(timeObj.value)<10){ 
					timeObj.value='0'+parseFloat(timeObj.value);
					}	*/
					if(parseFloat(minObj.value)>59){ 
					minObj.value=59;
					} 
/*					else
					if(parseFloat(minObj.value)<10){ 
					minObj.value='0'+parseFloat(minObj.value);
					}						*/
					window.document.getElementById('configuration_value').value=timeObj.value+':'+minObj.value;
				}
			}
			}
			var prevID;
			var timerID;
			var cEvent;
			function pop_config(e,row,id){
				if (row.edit) return;
				prevID=id;
				cEvent=e;
				row.pop_on=true;
				show_description(id);
			}
			function close_config(row,id){
				if (!row.pop_on || row.edit) return;
				prevID=false;
				kill();
			}
			function show_email_field(mode){
				if(mode=="1")
					document.getElementById('show_email').style.display="";
				else
					document.getElementById('show_email').style.display="none";
			}
		</script>
		<script language="javascript">	
			var baby=new Array();
			baby[1]="";
			content_value=""; 
			function show_description(code){
				content_value = desc_array[code];		
				content='<table border="0" cellspacing="0" cellpadding="4" class="tableArea" width="100%" ><tr><td class="main"><font size="1" face="arial">';
				content+=content_value;
				content+='</font></td></tr></table>';
				popwidth=150;						
				baby[1]=content; 				
				if (baby[1]!=""){			
					popup("1","#D6F0F9");					
					//setTimeout('popup("1","#D6F0F9")',2500);
					//clearTimeout();										
				}							
			}
		</script>
       
	</head>
	<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();" >


		<script language="javascript" src="includes/popup.js" ></script>
	
		<!-- header //-->
			<table border="0" width="100%" cellspacing="2" cellpadding="2" onMouseOut="javascript:clearTimeout(1)">
			<tr>
				<td id="image_error" align="left" style="display:none"></td>
			</tr>
			</table>
			<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
		<!-- header_eof //-->
	<style>
	
	</style>
		<!-- body //-->
		<?php
		if(SHOW_OSCONCERT_HELP=='yes')
		{
		?>
		<div class="osconcert_message"><?php echo TEXT_IMPORTANT; ?></div>
		<?php
		}
		?>		
		<table border="0" width="100%" cellspacing="2" cellpadding="2" onMouseOut="javascript:clearTimeout(1)">
			<tr>
				<!-- body_text //-->
				<td width="100%" valign="top">
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td>
		<?php
		
			$quick_links=tep_db_query("SELECT filename,params,title,sort_order from " . TABLE_QUICK_LINKS . " where login_group_id='". tep_db_input($FSESSION->login_groups_id) ."' order by sort_order limit 10");
			
			//$quick_links=tep_db_query("SELECT filename,language_id,params,title,sort_order from " . TABLE_QUICK_LINKS . " where login_group_id='". tep_db_input($FSESSION->login_groups_id) ."' and language_id = '" . (int)$FSESSION->languages_id . "' order by sort_order limit 10");
			
			if (tep_db_num_rows($quick_links)>0){
				while($quick_result=tep_db_fetch_array($quick_links)){				
					$temp_link=tep_href_link($quick_result["filename"],$quick_result["params"] . (($quick_result['params']!='')?'&top=1':'top=1'));
		?>

			<div id="menu_l1" class="cell_bg_navig_l1" style="display:none">
			<ul>
			<?php echo '<li><a href="' . $temp_link . '" title="'. $quick_result['title'].'">' . substr($quick_result["title"],0,15) .'</a></li>';?>
			</ul>
			</div>
		<?php } ?>
		
		<?php	
			}
		
		?>							<table border="0" width="100%" cellspacing="0" cellpadding="0">
								<?php 
								//echo $mPath;
								if($mPath=='7_415'){ ?>
								<tr>
									<td class="config_title_line">&nbsp;<?php //echo MAILERLITE; ?>&nbsp;<a href="http://www.mailerlite.com/a/xfpketezmg"><img src="images/mailerlite750x100.gif" align="absmiddle"/></a></td>
								</tr>
								<tr>
									<td>
										<table width="100%" cellpadding="1" cellspacing="1">
											<tr>
												<td class="config_title_line" width="100%">&nbsp;<?php echo MAILERLITE_SUB; ?></td>
											</tr> 
											<tr>
												<td width="100%" class="main" align="justify">&nbsp;<?php  echo MAILERLITE_TEXT;?></td>
											</tr>
										</table>	  	
									</td>
								</tr>	
								<?php } ?>
							</table>
						</td>
					</tr>
				</table>
				</td>
			
            </tr>
            <tr>
           
			<tr>
				<td> 
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td valign="top">
								<table border="0" width="100%" cellspacing="0" cellpadding="2">
									<?php
										$configuration_query = tep_db_query("select configuration_id, configuration_title,configuration_key, configuration_value, use_function,configuration_group_id,configuration_description,set_function from " . TABLE_CONFIGURATION . " where configuration_group_id = '" . (int)$gID . "'");        
										$total_count= tep_db_num_rows($configuration_query);
										$icnt=0;    
										while ($configuration = tep_db_fetch_array($configuration_query)) {
											$gID=$configuration['configuration_group_id'];
											if($configuration['configuration_description'])
											echo '<script language="javascript">desc_array['.$configuration["configuration_id"].']="'.addslashes($configuration["configuration_description"]).(($configuration['set_function']=='file_upload' && $configuration['configuration_value']=='osconcert.png')?'<br><img alt=\'osconcert.png\' width=200 height=200 title=\'osconcert.png\' src=\'../templates/'.DEFAULT_TEMPLATE.'/images/osconcert.png\'>':'').'";</script>';
											if (tep_not_null($configuration['use_function'])) {
												$use_function = $configuration['use_function'];
												//if (ereg('->', $use_function)) {
												if (preg_match('/->/i', $use_function)) {
												$class_method = explode('->', $use_function);
												if (!is_object(${$class_method[0]})) {
												include(DIR_WS_CLASSES . $class_method[0] . '.php');
												${$class_method[0]} = new $class_method[0]();
												}
												$cfgValue = tep_call_function($class_method[1], $configuration['configuration_value'], ${$class_method[0]});
												} else {
												$cfgValue = tep_call_function($use_function, $configuration['configuration_value']);		
												}
											} else 
												$cfgValue = $configuration['configuration_value'];
										
											$file_upload="";
											if($configuration['set_function']=='file_upload') $file_upload='file_upload';   
											$print_cfgValue=$cfgValue;     
											if(strlen($print_cfgValue)>30) $print_cfgValue=substr($print_cfgValue,0,30).'...';
											if($print_cfgValue=='') $print_cfgValue="....";
											$config_row=2;
											$config_cols_width=round(100/2).'%';
											$save_href='<a href=javascript:do_expand('.$configuration["configuration_id"].',\'save\');><img src="images/template/img_save.gif" border="0" alt="save" title="save"></a>';	
											if($icnt%$config_row==0) echo "<tr style='height:70;width:50'>";		
											if($configuration["configuration_key"]=='COMPANY_LOGO') {
												echo '<form name="config_upload" enctype="multipart/form-data" action="'.tep_href_link(FILENAME_CONFIGURATION,'action=file_uploads&id='.$configuration['configuration_id']).'" method="post" onSubmit="return AIM.submit(this, {\'onStart\' : startCallback, \'onComplete\' : completeCallback});">';
												$save_href='<input type="image" src="images/template/img_save.gif" alt="save" title="save">';
											} 
											if($configuration["configuration_key"]=='COMPANY_LOGO') {
									?>
												<td onMouseOver="javascript:pop_config(event,this,'<?php echo $configuration["configuration_id"]; ?>');" onMouseOut="javascript:close_config(this,'<?php echo $configuration["configuration_id"]; ?>');" onClick="javascript:if(document.getElementById('config_value_<?php echo $configuration["configuration_id"]; ?>') && document.getElementById('config_value_<?php echo $configuration["configuration_id"]; ?>').style.display!='') do_expand(<?php echo $configuration['configuration_id'];?>,'freeway_image_upload','<?php echo $file_upload;?>');" <?php echo 'width="'.$config_cols_width.'" id="desc_'.$configuration["configuration_id"].'"';?> class='config_title_line'>	
									<?php } else { ?>
											<td onMouseOver="javascript:pop_config(event,this,'<?php echo $configuration["configuration_id"]; ?>');" onMouseOut="javascript:close_config(this,'<?php echo $configuration["configuration_id"]; ?>');" onClick="javascript:if(document.getElementById('config_value_<?php echo $configuration["configuration_id"]; ?>') && document.getElementById('config_value_<?php echo $configuration["configuration_id"]; ?>').style.display!='') do_expand(<?php echo $configuration['configuration_id'];?>,'get_details','<?php echo $file_upload;?>');" <?php echo 'width="'.$config_cols_width.'" id="desc_'.$configuration["configuration_id"].'"';?> class='config_title_line'>	
									<?php } ?>
									<table border="0" style="cursor:pointer;" width="100%" cellspacing="0" cellpadding="2">			    
										<tr class='config_title'>
											<td align="left"  ><?php echo $configuration['configuration_title'];?></td>
											<td align="right" id="action_save_<?php echo $configuration["configuration_id"];?>" style="display:none"><?php echo $save_href;?>&nbsp;&nbsp;</td>			    	                 	                	                      
										</tr>			
										<tr class='config_title'>			     	 
											<td nowrap="nowrap" class='config_title'><span id="config_value_<?php echo $configuration["configuration_id"];?>" style="display:none"><?php if($configuration["configuration_key"]=='COMPANY_LOGO'){ ?><input type="file" name="configuration_file_value" value="" id="configuration_file_value"><?php } ?></span> <span id="config_data_<?php echo $configuration["configuration_id"];?>"><a <?php echo ((strlen($cfgValue)>30)?"alt='".$cfgValue."' title='".$cfgValue."'":"");?>><?php echo htmlspecialchars($print_cfgValue);?></a></span>&nbsp;&nbsp;<span id="img_load_<?php echo $configuration["configuration_id"];?>" style="display:none"><img alt='Loading...' title='Loading...' src='images/24-1.gif'></span></td>                  	                	
											<td valign="top" align="right" id="action_close_<?php echo $configuration["configuration_id"];?>" style="display:none"><?php if($configuration["configuration_key"]=='COMPANY_LOGO'){ ?><a href="javascript: do_expand(<?php echo $configuration['configuration_id'];?>,'image_upload_close','<?php echo $file_upload;?>');"> <?php } else { ?><a href="javascript:do_expand(<?php echo $configuration['configuration_id'];?>,'close')"><?php } ?><img src="images/template/img_close.gif" alt="close" title="close" border="0"></a>&nbsp;&nbsp;</td>	     
										</tr> 		     
									</table>		
								</td>
								<?php	 	 
									if($configuration["configuration_key"]=='COMPANY_LOGO') 
										echo '</form>';
										$icnt++;
									}
									$config_row=1;									
									if($icnt%$config_row==0) echo "</tr >";		
								?>
								</table> 	   
							</td>         
							<td class="smallText"></td> 
						</tr>
                        </tr>
            <td>
			<table border="0" cellspacing="3" cellpadding="3" width="90%" class="info_content">
			<tr>
			<td colspan="5"><h4><?php echo TEXT_CONFIG_LINKS;?></h4>
			</td>
			</tr>
				 <?php 
				 $cfg_group_query = tep_db_query("select count(*) as count,cg.configuration_group_title,cg.configuration_group_id from " . TABLE_CONFIGURATION_GROUP." cg,".TABLE_CONFIGURATION." c where c.configuration_group_id=cg.configuration_group_id group by c.configuration_group_id,cg.configuration_group_title,cg.configuration_group_id order by configuration_group_title");  
				 if(tep_db_num_rows($cfg_group_query)>0){
				    $i=0;
				    while($cfg_group=tep_db_fetch_array($cfg_group_query)){		  	   
				  	   if($i%5==0)	echo '<tr>';?>
				  	   	   <td><a href="<?php echo tep_href_link(FILENAME_CONFIGURATION,'gID='.$cfg_group['configuration_group_id'].'&mPath=2');?>"><?php echo $cfg_group['configuration_group_title'].'('.$cfg_group['count'].')';?></td>   			  	   
				  	<?php
				  	$i++; 
				  	}
				 }     		 
				 ?>		 
		   </table>
            </td>		 
					</table>
					<form  name="config" id="config" action="<?php echo FILENAME_CONFIGURATION;?>" method="post">
						<input type="hidden" name="configuration_val"> 
						<input type="hidden" name="open_val"> 
						<input type="hidden" name="email_val"> 
					</form>
				</td>
			</tr>
		</table>
		</td>
		<!-- body_text_eof //-->
		</tr>
		</table>
		<!-- body_eof //-->
		<!-- footer //-->
			<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
		<!-- footer_eof //-->

		</body>
	</html>
	<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
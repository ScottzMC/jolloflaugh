<?php
/*  

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
  http://www.openfreeway.org

  Copyright 2007 ZacWare Pty. Ltd
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	require('includes/application_top.php');
	$action = $FREQUEST->getvalue('action','string','0');
	$id = $FREQUEST->getvalue('id','int','0');	
	
   if($action=='check_password')
	{ 

		 $show='visible';			
		echo $show;
		exit;
	}		
		
   if($action=='get_details')
    { 
	echo 'get_details^';
  $my_account_query = tep_db_query ("select a.admin_id, a.admin_firstname, a.admin_lastname, a.admin_email_address, a.admin_created, a.admin_modified, a.admin_logdate, a.admin_lognum, g.admin_groups_name from " . TABLE_ADMIN . " a, " . TABLE_ADMIN_GROUPS . " g where a.admin_id= " . (int)$FSESSION->login_id . " and g.admin_groups_id= " . (int)$FSESSION->login_groups_id . "");
  $myAccount = tep_db_fetch_array($my_account_query);
  $save_href='<a href=javascript:do_expand('.$myAccount["admin_id"].',\'save\');><img src="images/template/img_save.gif" border="0" alt="save" title="save" border="0" align="absmiddle"></a>';	
?>
	  <table border="0" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF">
	 		 <tr class="dataTableContent">
                     <td class="dataTableContent"><?php echo TEXT_INFO_FIRSTNAME . tep_draw_separator('pixel_trans.gif','70','10');?>
                        <?php echo tep_draw_input_field('admin_firstname',$myAccount['admin_firstname']); ?>
					 </td>
		    </tr>
              <tr>
                      <td class="dataTableContent"><?php echo TEXT_INFO_LASTNAME . tep_draw_separator('pixel_trans.gif','72','10');?>
                      <?php echo tep_draw_input_field('admin_lastname',$myAccount['admin_lastname']); ?></td>
             </tr>
			 <tr class="dataTableContent">
                     <td class="dataTableContent"><?php echo TEXT_INFO_EMAIL . tep_draw_separator('pixel_trans.gif','46','10');?>
                     <?php  echo tep_draw_input_field('admin_email_address',$myAccount['admin_email_address'],'onkeyup="javascript: document.getElementById(\'wrong_email\').style.display=\'none\';"'). '&nbsp;' ?>
					 </td>
					 <td class="dataTableContent" id="wrong_email" style="display:none;"><b><font color="#FF0000"><?php echo TEXT_INFO_ERROR; ?></font></b></td>
		    </tr>
		    

		     <tr class="dataTableContent" style="display:none">
					  <td class="dataTableContent"><br><?php echo tep_draw_separator('pixel_trans.gif','140','10'); ?><?php echo '<a href="javascript:do_expand('. $myAccount['admin_id'] .',\'back\');">' . tep_image_button('button_back.gif',IMAGE_BACK) . '</a>' . tep_draw_separator('pixel_trans.gif','4','2') .  '<a href=javascript:do_expand('.$myAccount["admin_id"].',\'check_password\');>' .tep_image_button('button_confirm.gif', IMAGE_CONFIRM).'</a>';?>
					  </td>
			</tr>
	 <tr class="dataTableContent" id="new_pass" >
	  <td align="left" nowrap="nowrap" class="dataTableContent" valign="top"><?php echo TEXT_INFO_NEW_PASSWORD . tep_draw_separator('pixel_trans.gif','50','2');?><?php echo tep_draw_password_field('admin_password','','','tabindex="1" maxlength="40" onKeyUp="javascript:do_pwd_check(this);"');?><?php //echo tep_draw_password_field('admin_password','','','tabindex=1'); ?></td><td tabindex=3><?php echo $save_href ;?>&nbsp;&nbsp;</td>
	  <td id='pwd_str' style="font-color:red;font-size:14; display:none"></td>
	  </tr>
	  <tr class="dataTableContent" id="confirm_pass" >
	  <td align="left" class="dataTableContent" valign="top" nowrap="nowrap" style=" border-bottom: 1px solid #F1F1F1;"><?php echo TEXT_INFO_PASSWORD_CONFIRM . tep_draw_separator('pixel_trans.gif','30','2');?><?php echo tep_draw_password_field('admin_password_confirm','','','tabindex=2'); ?></td><td><a href="javascript:do_expand('<?php echo $myAccount['admin_id'];?>','close')"><img src="images/template/img_close.gif" alt="close" title="close" border="0" align="absmiddle"></a>&nbsp;&nbsp;</td>
 	</tr> 
    <tr>
    	<td colspan="2" class="dataTableContent">
        	<?php echo TEXT_INFO_INFO;?>
        </td>
    </tr>
	</table>
 <?php exit; 
 }  
   if($action=='save') { 
	echo 'save^';
		 $id = $FREQUEST->getvalue('id','int','0');	
		 $password = $FREQUEST->getvalue('password','string','NO');
		 $confirm_password =$FREQUEST->getvalue('confirm_password');
		 $admin_firstname = $FREQUEST->getvalue('admin_firstname');
		 $admin_lastname =  $FREQUEST->getvalue('admin_lastname');
		 $admin_email_address =$FREQUEST->getvalue('admin_email_address');
		 $query = "select admin_email_address from " . TABLE_ADMIN . " where admin_id <> " . (int)$id . "";
		 $result = tep_db_query($query) or die(tep_db_error());
 $stored_email[] = tep_db_fetch_array($result);


		   if(in_array($admin_email_address, $stored_email))
			{  
            	$show = 'failedresult';
			}	
        	 else{
		 
        	 $encription_style=(defined('ENCRYPTION_STYLE'))?ENCRYPTION_STYLE:'O';
             $sql_data_array = array('admin_firstname' => tep_db_prepare_input($admin_firstname),
                                  	 'admin_lastname' => tep_db_prepare_input($admin_lastname),
                                  	 'admin_email_address' => tep_db_prepare_input($admin_email_address),
                                 	 'admin_password' => tep_encrypt_password(tep_db_prepare_input($password)),
                                     'encryption_style'=>$encription_style,
                                     'admin_modified' => 'now()');
        
          	if($password)tep_db_perform(TABLE_ADMIN, $sql_data_array, 'update', 'admin_id = \'' . $id . '\'');

          	$test=tep_mail($admin_firstname . ' ' . $admin_lastname, $admin_email_address, ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $admin_firstname, HTTP_SERVER . DIR_WS_ADMIN, $admin_email_address, $password, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			if($test){
         	    tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'page=' . $FREQUEST->getvalue('page') . '&mID=' . (int)$id));
		 	}
		  $thanks=true;
     	echo tep_db_prepare_input($admin_firstname) . '  ' . tep_db_prepare_input($admin_lastname) . '^' . tep_db_prepare_input($admin_email_address) ;
		}
		echo $show;
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
<?php require('includes/password_strength.js.php');?>
<script>
 function do_pwd_check(obj)
 { 
  <?php //if((MODULE_ADMIN_PASSWORD_STRENGTH=){?>	
 	pwd=obj.value;
	var password=document.getElementById('pwd_str');
	password.style.display="";
	if(pwd.length==0)
	  password.innerHTML='';
	else if(pwd.length<<?php echo ENTRY_PASSWORD_MIN_LENGTH;?>)
		password.innerHTML='<?php echo ENTRY_PASSWORD_STRENGTH_ERROR;?>';
	else if(!check_password_strength(pwd))
		password.innerHTML='<?php echo ENTRY_PASSWORD_STRENGTH_ERROR;?>';
	else
		password.innerHTML='';
   <?php //} ?>	 
 }
</script>
<script language="javascript">
 function do_expand(id,action,params){ 
	var qry=""; 
	if(id) {
		qry="?id="+id;
		var config_value=document.getElementById("config_value_"+id);
		var config_data=document.getElementById("config_data_"+id);
		var action_save=document.getElementById("action_save_"+id);			
		var action_close=document.getElementById("action_close_"+id);
		var img_load=document.getElementById("img_load_"+id);			
		if(img_load && action!='close') img_load.style.display="";
		var desc_div=document.getElementById("desc_"+id);
		document.getElementById("show").style.display='none';
		document.getElementById("edit").style.display='none';
		document.getElementById('thanx').style.display='none';
		if(document.getElementById("config_value").style.display='none')
		{ 
		 document.getElementById("config_value").style.display=''
		} 	
	 } 	
	if(action) qry+="&action="+action;
    if(action=='check_password'){
	    var err ="";
	    var s = document.getElementById('current_password').value; 
		var e = document.getElementById('admin_email_address').value; 
		var f = document.getElementById('admin_firstname').value;
		var l = document.getElementById('admin_lastname').value;

		if(f=="" || str_trim(f)==""){ err+= '<?php echo JS_ALERT_FIRSTNAME ; ?>';}
		 if(f.length<3)  
		 { 
		   err += "<?php echo JS_ALERT_FIRSTNAME_ERROR ; ?>";
		  }  
		  
		 if(l=="" || str_trim(l)=="") err+="<?php echo JS_ALERT_LASTNAME ; ?>";
		 else if(l.length<3)  
		 { 
		   err += "<?php echo JS_ALERT_LASTNAME_ERROR ; ?>";
		  }   
		
        if(e==""){ err += "<?php echo JS_ALERT_EMAIL ; ?>"; }
        else if(e!="" || str_trim(e)=="")
		 { 
			var x = e;
			var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (filter.test(x)) {}
			else err+="<?php echo JS_ALERT_EMAIL_FORMAT; ?>";			
         }
		if(s=="" | str_trim(s)=="") err+= "<?php echo JS_ALERT_CURRENT_PASSWORD; ?>";
		  else if(s.length<5)  
		 { 
		   err += "<?php echo JS_ALERT_CURRENT_PASSWORD_ERROR; ?>";
		  }  
		 if(err) 
		 {
		 var s =  "<?php echo ERRORS;?>"+err;
		 alert(s);
		 }
		else { 
		command="<?php echo tep_href_link(FILENAME_ADMIN_ACCOUNT,'action=check_password');?>"+"&id="+id+"&admin_password="+s+"&admin_email_address="+e;	
		do_get_command(command);
	  }	
	} 
	else if(action=='save' || action=='get_details'){	  	   
	   /*if(action=='save'){
	   	  var s = document.getElementById('admin_password').value;  
		  var v = document.getElementById('admin_password_confirm').value;
		  var f = document.getElementById('admin_firstname').value;
		  var l = document.getElementById('admin_lastname').value;
		  var e = document.getElementById('admin_email_address').value;
	  }*/
	  
		 if(action=='get_details' && document.getElementById("config_value_"+id) && document.getElementById("config_value_"+id).innerHTML==""){	
		  if(params) qry+="&params="+params;
	      command="<?php echo tep_href_link(FILENAME_ADMIN_ACCOUNT);?>"+qry;  
		  do_get_command(command);
	   }
	  if(action=='save')
	   { 	
	      var s = document.getElementById('admin_password').value;  
		  var v = document.getElementById('admin_password_confirm').value;
		  var f = document.getElementById('admin_firstname').value;
		  var l = document.getElementById('admin_lastname').value;
		  var e = document.getElementById('admin_email_address').value;

	     var err=""; 
         if(f=="" || str_trim(f)=="") err+= '<?php echo JS_ALERT_FIRSTNAME ; ?>';
		 else if(f.length<3)  
		 { 
		   err += '<?php echo JS_ALERT_FIRSTNAME_ERROR ; ?>';
		  }  
		  
		 if(l=="" || str_trim(l)=="") err+='<?php echo JS_ALERT_LASTNAME ; ?>';
		 else if(l.length<3)  
		 { 
		   err += '<?php echo JS_ALERT_LASTNAME_ERROR ; ?>';
		  }   
		
         if(e=="" || str_trim(e)==""){ err += '<?php echo JS_ALERT_EMAIL ; ?>'; }
         else if(e!="")
		 { 
			var x = e;
			var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (filter.test(x)) {}
			else err+="<?php echo JS_ALERT_EMAIL_FORMAT; ?>";			
         } 
		 
		 if(pwdValidation("<?php echo MODULE_ADMIN_PASSWORD_STRENGTH;?>",s) == false)
		  {
	  		 switch ("<?php echo MODULE_ADMIN_PASSWORD_STRENGTH;?>"){
				case '1':
					err = err + "<?php echo ERR_PWD_EMPTY;?>";
					error=1;
					break;
			    case '2':
					err = err + "<?php echo ERR_PWD_ALPHANUMERIC;?>";
					error=1;
					break;
				case '3':
					err = err + "<?php echo ERR_PWD_ALPHA_SYMBOLS;?>";
					error=1;
					break;
			  /* case '4':
					error_message = error_message + '<?php //echo ERR_PWD_DICTIONARY_WORDS;?>';
					error=1;
					break;*/
				  }
			 } 

		 else if(s.length<5 || str_trim(s)=="")  
		 { 
		   err += "<?php echo JS_ALERT_NEW_PASSWORD_ERROR; ?>";
		  }  
		   
		  if(v=="") err+= "<?php echo JS_ALERT_CONFIRM_PASSWORD; ?>";
		  else if(v.length<5)  
		 { 
		   err += "<?php echo JS_ALERT_CONFIRM_PASSWORD_ERROR; ?>";
		  }
		 
		 if(s!="" && v!="" && s!=v)  err += "<?php echo JS_ALERT_NEW_CONFIRM_ERROR; ?>";
		 if(err) 
		 {
		 var s =  "<?php echo ERRORS;?>"+err;
		 alert(s);
		 }
		 else if(err=="")
		   {
			   qry+="&password="+s+"&confirm_password="+v+"&admin_firstname="+f+"&admin_lastname="+l+"&admin_email_address="+e;
			   command="<?php echo tep_href_link(FILENAME_ADMIN_ACCOUNT);?>"+qry;
			   do_post_command('config',command);
		    }
	   }
	      	   	   	
 }else if((action=='close' && id) || (action=='back' && id))
	 { 
	   document.getElementById("config_value").style.display='none';
	   document.getElementById("show").style.display='';   
	   document.getElementById("edit").style.display='';
	   document.getElementById("no").style.display='none';
	   document.getElementById("hide").style.display='';
	   document.getElementById("new_pass").style.display='none';
	   document.getElementById("confirm_pass").style.display='none';
	   document.getElementById('admin_password').value='';
	   document.getElementById('current_password').value='';
	   document.getElementById('admin_password_confirm').value='';
	   document.getElementById("admin_firstname").value=document.getElementById("fname").innerHTML;
	   document.getElementById("admin_lastname").value=document.getElementById("lname").innerHTML;
	   //document.getElementById("admin_email_address").value=document.getElementById("admin_email").innerHTML;
	   document.getElementById('help').style.display='';
    }	 
	if(document.getElementById("config_value_"+id))document.getElementById("config_value_"+id).style.display='';
 }
 function do_result(result)
 {
	var tokens=result.split('^');
	if(tokens[1]=='failedresult')
	{ 
	document.getElementById('wrong_email').style.display='';
	document.getElementById('wrong_pass').style.display='none';
	document.getElementById('new_pass').style.display='';
	document.getElementById('confirm_pass').style.display='';
	document.getElementById('pwd_str').style.display='none';
	document.getElementById("admin_firstname").style.display="";
	document.getElementById("admin_lastname").style.display="";
	document.getElementById("admin_email_address").style.display="";
	}
    else
	{   
	    var len = result.length;
	    if(len>10){
		var token="";
		token=result.split('^');
		if(token[0]=='get_details')
		  {
			if(document.getElementById('config_value')){
			    document.getElementById('config_value').innerHTML=token[1];
			    document.getElementById('config_value').style.display='';
			   }
	      }
		if(token[0]=='save')
		   {     
		       document.getElementById("admin_name").innerHTML=token[1];
			   document.getElementById("admin_email").innerHTML=token[2];
			   document.getElementById("config_value").style.display='none';
			   document.getElementById("show").style.display='';
			   document.getElementById("edit").style.display=''; 
			   document.getElementById('wrong_email').style.display='none';
			   document.getElementById('wrong_pass').style.display='none';
			   document.getElementById('thanx').style.display='';
			   document.getElementById('admin_password').value='';
		       document.getElementById('admin_password_confirm').value=''
		       document.getElementById('no').style.display='none';
			   document.getElementById('hide').style.display='none';
			   document.getElementById('help').style.display='';
	     }
		if(document.getElementById('no').style.display='none')
	       document.getElementById('no').style.display='none';
	    if(document.getElementById('hide').style.display='none')
		    document.getElementById('hide').style.display='';
		document.getElementById('new_pass').style.display='none';
		document.getElementById('confirm_pass').style.display='none';
	    document.getElementById('current_password').value='';
	    document.getElementById('admin_password_confirm').value='';
	    document.getElementById('help').style.display='';
		//document.getElementById('help').innerHTML;
	    //document.getElementById("admin_firstname").value=document.getElementById("fname").innerHTML;
	    //document.getElementById("admin_lastname").value=document.getElementById("lname").innerHTML;
	    //document.getElementById("admin_email_address").value=document.getElementById("admin_email").innerHTML;	
	  }else{ 
		    document.getElementById('wrong_email').style.display='none';
			document.getElementById('wrong_pass').style.display='none';
			if(result=='visible'){
			document.getElementById('wrong_pass').style.display='none';
			document.getElementById('wrong_email').style.display='none';
			document.getElementById('no').style.display='none';
			document.getElementById('hide').style.display='none';
			document.getElementById('new_pass').style.display='';
			document.getElementById('new_pass').value='';
			document.getElementById('confirm_pass').style.display='';
			document.getElementById('confirm_pass').value='';
			document.getElementById('pwd_str').style.display='none';
			document.getElementById('help').style.display='none';
            }else if(result=='unvisible')
			{
			document.getElementById('wrong_pass').style.display='';
			document.getElementById('new_pass').style.display='none';
			document.getElementById('confirm_pass').style.display='none';
			}else if(result=='has')
			{
			document.getElementById('wrong_email').style.display='';
			document.getElementById('wrong_pass').style.display='none';
			document.getElementById('new_pass').style.display='none';
			document.getElementById('confirm_pass').style.display='none';
            }else if(result=='have')
		    {
		    document.getElementById('wrong_email').style.display='';
		    document.getElementById('wrong_pass').style.display='';
		    document.getElementById('new_pass').style.display='none';
		    document.getElementById('confirm_pass').style.display='none';
		   } 
	    }
      }
  }
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<script language="javascript" src="includes/popup.js"></script>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
		<?php
		if(SHOW_OSCONCERT_HELP=='yes')
		{
		?>
		<div class="osconcert_message"><?php echo TEXT_IMPORTANT; ?></div>
		<?php
		}
		?>
<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr> 
<!-- body_text //-->    
    <td width="100%" valign="top">
      <?php //if ($HTTP_GET_VARS['action'] == 'edit_process') { echo tep_draw_form('account', FILENAME_ADMIN_ACCOUNT, 'action=save_account', 'post', 'enctype="multipart/form-data"'); } elseif ($HTTP_GET_VARS['action'] == 'check_account') { echo tep_draw_form('account', FILENAME_ADMIN_ACCOUNT, 'action=check_password', 'post', 'enctype="multipart/form-data"'); } else { echo tep_draw_form('account', FILENAME_ADMIN_ACCOUNT, 'action=check_account', 'post', 'enctype="multipart/form-data"'); } ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="2">     
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" align="center">
          <tr>
            <td valign="top">
<?php
		$my_account_query = tep_db_query ("select a.admin_id, a.admin_firstname, a.admin_lastname, a.admin_email_address, a.admin_created, a.admin_modified, a.admin_logdate, a.admin_lognum, g.admin_groups_name from " . TABLE_ADMIN . " a, " . TABLE_ADMIN_GROUPS . " g where a.admin_id= " . (int)$FSESSION->login_id . " and g.admin_groups_id= " . (int)$FSESSION->login_groups_id . "");
		$myAccount = tep_db_fetch_array($my_account_query);
		$save_href='<a href=javascript:do_expand('.$myAccount["admin_id"].',\'save\');><img src="images/template/img_save.gif" border="0" alt="save" title="save"></a>';	

?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2" align="center">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ACCOUNT; ?>
                </td>
              </tr>
			  <tr>
				    <td id="load" class="dataTablecontent" style="display:none"><?php echo "Loading............";?></td>
			 </tr>	
			  <Tr class="dataTableContent" id="thanx" style="display:none;">
						<td class="dataTableContent" align="left"><?php echo '<font color=green size="2"><b>' . PASSWORD_CHANGED_SUCCESFULLY . tep_draw_separator('pixel_trans.gif','10','10') . '</font></b>'?></td>
			  </Tr>
			    <tr class="dataTableRow">
                <td>
                  <table border="0" cellspacing="0" cellpadding="2" id="show">
                   
				  <tr style="display:none">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_FIRSTNAME; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td id="fname" class="dataTableContent"><?php echo $myAccount['admin_firstname'];?></td>
                    </tr>
					<tr style="display:none">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LASTNAME; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td id="lname" class="dataTableContent"><?php echo $myAccount['admin_lastname'];?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_FULLNAME ; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td id="admin_name" class="dataTableContent"><?php echo $myAccount['admin_firstname'] . ' ' . $myAccount['admin_lastname']; ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_EMAIL; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent" id="admin_email"><?php echo $myAccount['admin_email_address']; ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_PASSWORD; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo TEXT_INFO_PASSWORD_HIDDEN; ?></td>
                    </tr>
					
			      <tr class="dataTableRowSelected">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_GROUP; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_groups_name']; ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_CREATED; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_created']; ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LOGNUM; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_lognum']; ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LOGDATE; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_logdate']; ?></td>
                    </tr>
					
					<tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td class="smallText" valign="top"><?php echo TEXT_INFO_MODIFIED . $myAccount['admin_modified']; ?></td><td align="right"><?php if ($FREQUEST->getvalue('action') == 'edit_process') { echo '<a href="' . tep_href_link(FILENAME_ADMIN_ACCOUNT) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> '; if ($FSESSION->is_registered('confirm_account')) { echo tep_image_submit('button_save.gif', IMAGE_SAVE, 'onClick="validateForm();return document.returnValue"'); } } elseif ($FREQUEST->getvalue('action') == 'check_account') { echo '&nbsp;'; } //else { echo tep_image_submit('button_edit.gif', IMAGE_EDIT); } ?></td><tr></table></td>
              </tr>
<?php
 // }
?>                       
                  </table>
                </td>
              </tr>
			  
			  <tr>
				    <td colspan="2" onClick="javascript:do_expand(<?php echo $myAccount['admin_id'] ;?>,'get_details','');" <?php echo 'width="'.$config_cols_width.'" id="desc_'.$myAccount['admin_id'].'"';?> class='config_title_line'>
			           <table border="0" width="100%" cellspacing="0" cellpadding="2">			    
			            <tr class='dataTableContent' nowrap="nowrap">
			    	    <td class="dataTableContent" style="cursor:pointer;" width="100%" align="center" id="edit"><?php echo tep_image_button('button_edit.gif', IMAGE_EDIT); ?>&nbsp;&nbsp;&nbsp;</td>
					    <td  nowrap="nowrap" align="right" id="action_save_<?php echo $myAccount['admin_id'];?>" style="display:none"><?php echo $save_href;?>&nbsp;&nbsp;</td>			    	                 	                	                      
			            </tr>			    			    
			            <tr class='dataTableContent'>			     	 
			     	     <td  nowrap="nowrap" class='dataTableContent' id="config_value"><span id="config_value_<?php echo $myAccount['admin_id'];?>" style="display:none"></span><span id="config_data_<?php echo $myAccount['admin_id'];?>"><span id="img_load_<?php $myAccount['admin_id'];?>" style="display:none"><img alt='Loading...' title='Loading...' src='images/24-1.gif'></span></td>              	                	
				 	     <td valign="top" nowrap="nowrap" align="right" id="action_close_<?php echo $myAccount['admin_id'];?>" style="display:none"><a href="javascript:do_expand('<?php echo $myAccount['admin_id'];?>','close')"><img src="images/template/img_close.gif" alt="close" title="close" border="0"></a>&nbsp;&nbsp;</td>	     
			            </tr> 		     
			           </table>		
		          	 </td>
			   	</tr>	
			  
			  <?php /*if($thanks==true){ ?>
					<Tr>
						<td class="dataTableContent"><?php echo '<font color=red>'.'"Your password has been changed successfully"'.'</font>'?></td>
					</Tr>
			<?php }*/?>             
            </table>
   
   
            </td>
          </tr>
        </table></td>
      </tr>
    </table> 
	<form  name="config" id="config" action="<?php echo FILENAME_ADMIN_ACCOUNT;?>" method="post">        	         	
       <input type="hidden" name="password"> 
    </form>
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


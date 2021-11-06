<?php

/*

  

    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
  require('includes/application_top.php');

  // #CP - local dir to the template directory where you are uploading the company logo
  tep_get_last_access_file();
  $template_query = tep_db_query("select configuration_id, configuration_title, configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_TEMPLATE'");
  $template = tep_db_fetch_array($template_query);
  $CURR_TEMPLATE = $template['configuration_value'] . '/';
  $server_date = getServerDate(true);	

  $upload_fs_dir = DIR_FS_TEMPLATES.$CURR_TEMPLATE.DIR_WS_IMAGES;
 
  $upload_ws_dir = DIR_WS_TEMPLATES.$CURR_TEMPLATE.DIR_WS_IMAGES;
 
  // #CP

	$action = $FREQUEST->getvalue('action');
	$gID = (int)$FREQUEST->getvalue('gID','int',0);
	$eaction = $FREQUEST->getvalue('eaction');

	// get gID using the current config menu elements
	if ($gID<=0){
		$config_group_query=tep_db_query("SELECT configuration_group_id from " . TABLE_CONFIGURATION_GROUP . " where lower(configuration_access_key)='" . strtolower($CONFIG_ACCESS_KEY) . "'");
		if (tep_db_num_rows($config_group_query)>0){
			$config_group_result=tep_db_fetch_array($config_group_query);
			$gID=$config_group_result['configuration_group_id'];
		} else {
			$gID=1;
		}
	}
  if(($FREQUEST->getvalue("search")!='')){
   $action = 'search';
    $search = $FREQUEST->getvalue("search");
  	}
	
	if($eaction== 'upload' ) {
		$search = $FREQUEST->getvalue('search');
		if($search =="")$action = 'upload';
		}
	/*$configuration_query = tep_db_query("select configuration_group_id from " . TABLE_CONFIGURATION_GROUP . " where configuration_group_title like '%" . $search . "%' or configuration_group_description like '%" . $search . "%'" );
	
	   if(tep_db_num_rows($configuration_query)>0){
	   $configuration_array = tep_db_fetch_array($configuration_query);
	   $gID = $configuration_array["configuration_group_id"];
	   tep_redirect(tep_href_link(FILENAME_CONFIGURATION,'gID=' . $gID));
	   } */
  /* $configuration_query = tep_db_query("select configuration_id,configuration_group_id from " . TABLE_CONFIGURATION . " where configuration_title like '%" . $search . "%' or configuration_description like '%" . $search . "%'");
   
   		if(tep_db_num_rows($configuration_query)>0){
		  while($configuration_array = tep_db_fetch_array($configuration_query)){
		     //echo '<br>gID' . $configuration_array["configuration_group_id"];
			 //echo 'cID' . $configuration_array["configuration_id"];
			 //$cID = $configuration_array["configuration_id"];
			 //$gID = $configuration_array["configuration_group_id"];
		   }
		}*/
	   
  
  if (tep_not_null($action)) {
    switch ($action) {
      case 'save':
	  case 'update':
	  	if($action=='save'){
        $configuration_value = $FREQUEST->postvalue('configuration_value');
		$configuration_value1 = (($FREQUEST->postvalue('configuration_value1')!='')?$FREQUEST->postvalue('configuration_value1'):0);
		if($configuration_value1>0)
			$configuration_value=$configuration_value . ',' . $configuration_value1;
		} 
        $cID = $FREQUEST->getvalue('cID');
		tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($configuration_value) . "', last_modified = '" . tep_db_input($server_date) . "' where configuration_id = '" . (int)$cID . "'");
		
		 if($FREQUEST->postvalue('search')!=""){
		    $action = 'search';
			$search = $FREQUEST->postvalue('search');
			}else{
        tep_redirect(tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cID));}
        break;
// #CP - supporting functions to upload company logo to template images directory       
      case 'processuploads':
         if (isset($GLOBALS['file_name']) && tep_not_null($GLOBALS['file_name'])){
          $up_load = new upload('file_name', $upload_fs_dir);
          $file_name = $up_load->filename;
			$error=false;          
         if($file_name != "logo.gif"){
			if( file_exists($upload_fs_dir."logo.gif") && !@unlink($upload_fs_dir."logo.gif")){
			//	tep_session_register("config_error");
				$FSESSION->set("config_error",sprintf(DELETE_ERROR,$upload_fs_dir."logo.gif"));
				$error=true;
			}
			if(!$error && !@rename($upload_fs_dir.$file_name, $upload_fs_dir."logo.gif")){
				//	tep_session_register("config_error");
					$FSESSION->set("config_error",sprintf(RENAME_ERROR,$upload_fs_dir."logo.gif"));
			}
		  }//$filename
		}//$GLOBALS
			if($FREQUEST->postvalue('search')!=""){
		    $action = 'search';
			$search = $FREQUEST->postvalue('search');
			}else{
        tep_redirect(tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID));
		}
        break;
      case 'upload':
        $directory_writeable = true;
        if (!is_writeable($upload_fs_dir)) {
          $directory_writeable = false;
          $messageStack->add(sprintf(ERROR_DIRECTORY_NOT_WRITEABLE, $upload_fs_dir), 'error');
        }
        break;
    }
// #CP     
  }
  $cfg_group_query = tep_db_query("select configuration_group_title from " . TABLE_CONFIGURATION_GROUP . " where configuration_group_id = '" . (int)$gID . "'");
  
  $cfg_group = tep_db_fetch_array($cfg_group_query);
  
// check if the template image directory exists
  if (is_dir($upload_fs_dir)) {
    if (!is_writeable($upload_fs_dir)) $messageStack->add(ERROR_TEMPLATE_IMAGE_DIRECTORY_NOT_WRITEABLE . $upload_fs_dir, 'error');
  } else {
    $messageStack->add(ERROR_TEMPLATE_IMAGE_DIRECTORY_DOES_NOT_EXIST . $upload_fs_dir, 'error');
  }
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript">
	function check_selected_values(configcheckbox,controlname,countrycontrol,key){
	//alert(controlname);
		var element=configcheckbox.form.elements[controlname];
		var checkbox=configcheckbox.form.elements['check_'+controlname];
		
		if (!element || !checkbox) return;
		var result='';
		var icnt;
		if (checkbox[0]){
			for (icnt=0;icnt<checkbox.length;icnt++){
				if (checkbox[icnt].checked){
					result+=checkbox[icnt].value;
				}
			}
		} 
		element.value=result;

	}

	function validate_image(checkType){
		var frm=document.file;
		var error ="";
			if(checkType=="logo"){
				var image_types=Array(".png",".jpg",".jpeg",".gif");
				if (frm.file_name.value!="" && !check_mime_type(frm.file_name,image_types)){
					error="* <?php echo ERR_IMAGE_UPLOAD_TYPE;?>\n";
				}
			}
			if(error!=""){
				alert(error);
				return false;
			}
			return true;
	}
		function validateForm(checkType){
			var frm=document.configuration;
			var error="";
			if (checkType=="" || frm.configuration_value.type!="text") return true;
				tempSplt=checkType.split("~");
				for (icnt=0;icnt<(tempSplt.length);icnt++){
					type=(tempSplt[icnt]);
					switch(type){
						// for numeric
						case 'N':
							//alert(frm.configuration_value.keyCode());
							if (frm.configuration_value.value=="" || (frm.configuration_value.value==" ")) error="<?php echo addslashes(ERR_VALUE_EMPTY);?>";
							else if (isNaN(frm.configuration_value.value) || frm.configuration_value.value<0)
								error="<?php echo addslashes(ERR_VALUE_POSTIVE_NUMBER);?>";
							break;
						//for percentage	
						case 'P':
							if(frm.configuration_value.value=="" || isNaN(frm.configuration_value.value) || frm.configuration_value.value<0 || frm.configuration_value.value>100)
								error="<?php echo addslashes(ERR_PERCENTAGE_VALUE); ?>";
							break;	
						}
					if (error!=""){
						alert(error);
						return false;
					}
				}
			if (error!=""){
				alert(error);
				return false;
			}
			return true;
		}
	</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo $cfg_group['configuration_group_title']; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, 1); ?></td>
			    <td class="smalltext" align="right">  
			    <?php
				 echo tep_draw_form('search', FILENAME_CONFIGURATION, 'action=search', 'get');
			   	 echo TEXT_SEARCH . ' '. tep_draw_input_field('search'); 
				 echo '</form>';
				 ?> 
					 
					
				</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
		<?php if (($FSESSION->get("config_error")!='')){ 
					$error=$FSESSION->get("config_error");
					$FSESSION->remove("config_error");
		?>
			<tr>
				<td class="errorText">
					<?php echo $error . '<p>';?>
				</td>
				</tr>
			</tr>
		<?php } ?>
		 <tr>
		 	<td></td>
		 </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CONFIGURATION_VALUE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php  

    if( $action == 'search'  ){
	  $configuration_query = tep_db_query("select configuration_id,configuration_group_id,configuration_title,configuration_value,use_function from " . TABLE_CONFIGURATION . " where configuration_title like '%" . tep_db_input($search) . "%' or configuration_description like '%" . $search . "%'");
	}else  	         
      $configuration_query = tep_db_query("select configuration_id, configuration_title, configuration_value, use_function from " . TABLE_CONFIGURATION . " where configuration_group_id = '" . (int)$gID . "' order by sort_order");
	$total_count= tep_db_num_rows($configuration_query);
  //if($total_count<1)
		//echo "<tr><td class=dataTableRow >No Configurations Found for '".$search."' </td><td>&nbsp;</td><td>&nbsp;</td></tr>";
  
   while ($configuration = tep_db_fetch_array($configuration_query)) {
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
    } else {
      $cfgValue = $configuration['configuration_value'];
    }
    
    if ((($FREQUEST->getvalue('cID')=='') || (($FREQUEST->getvalue('cID')!='') && ($FREQUEST->getvalue('cID') == $configuration['configuration_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
	  // if( $action == 'search') 
	   //  $cfg_extra_query = tep_db_query("select configuration_key, configuration_description, date_added, last_modified, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_id = '" . (int)$configuration['configuration_id'] . "' and configuration_group_id = '" . (int)$configuration["configuration_group_id"] . "'"); 
	//else
      $cfg_extra_query = tep_db_query("select configuration_key, configuration_description, date_added, last_modified, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_id = '" . (int)$configuration['configuration_id'] . "'");
	  $cfg_extra = tep_db_fetch_array($cfg_extra_query);

      $cInfo_array = array_merge($configuration, $cfg_extra);
      $cInfo = new objectInfo($cInfo_array);
    }
	
	if($action == 'search'  ){
   	if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) {
    	if($cInfo->set_function == 'file_upload'){
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cInfo->configuration_id . '&action=upload&search=' . $search) . '\'">' . "\n";
      } else {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cInfo->configuration_id . '&action=edit&search=' . $search) . '\'">' . "\n";
      }
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&cID=' . $configuration['configuration_id'] . '&search=' . $search ) . '\'">' . "\n";
    }
	//echo "$total_count configurations found for $search"; 
	}
	
	else
	{
    if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) {
    	if($cInfo->set_function == 'file_upload'){
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cInfo->configuration_id . '&action=upload') . '\'">' . "\n";
      } else {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cInfo->configuration_id . '&action=edit') . '\'">' . "\n";
      }
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&cID=' . $configuration['configuration_id']) . '\'">' . "\n";
    }
}
?>
                <td class="dataTableContent"><?php echo $configuration['configuration_title']; ?></td>
				
                <td class="dataTableContent"><?php echo htmlspecialchars($cfgValue); ?></td>
                <td class="dataTableContent" align="right"><?php if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&cID=' . $configuration['configuration_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
  // echo $cfgValue;
?>	  
<tr>
  <td colspan="2" class="smallText" ><?PHP if($FREQUEST->getvalue('search')!=""){
                                             if($total_count>0)  echo "$total_count configuration(s) found for '".$search."'";  
   											  else echo "0 configuration found"; 	?></td>
	<td><?php echo '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cInfo->configuration_id) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>';} ?></td>
	</tr> </table></td>
<?php
  $heading = array();
  $contents = array();
$pieces = explode("/", $cInfo->configuration_value);
	  if(($FREQUEST->getvalue("search")!=''))
	    $action = (isset($eaction) ? $eaction : $action);
 
  switch ($action) {
    case 'edit':
      $heading[] = array('text' => '<b>' . $cInfo->configuration_title . '</b>');

      if ($cInfo->set_function) {
        eval('$value_field = ' . $cInfo->set_function . '"' . htmlspecialchars($cInfo->configuration_value) . '");');
      } else {
        $value_field = tep_draw_input_field('configuration_value', $cInfo->configuration_value);
      }
	  //echo $cInfo->configuration_value;
	  
	  $pieces = explode("/", $cInfo->configuration_value);
	  
	  
	   $permissions = tep_get_file_permissions(fileperms($pieces[5]));
	  
	   // $user = @posix_getpwuid(fileowner($pieces[5]));
       //   $group = @posix_getgrgid(filegroup($current_path . '/' . $pieces));
		//  echo $group;
	//  echo $permissions;

	  
	  /* $directory_writeable = true;
        if (!is_writeable($cInfo->configuration_value)) {
          $directory_writeable = false;
          $messageStack->add(sprintf(ERROR_NOT_WRITEABLE, $cInfo->configuration_value), 'error');
		 //sprintf(ERROR_NOT_WRITEABLE, $cInfo->configuration_value), 'error';
        }*/
		
        $file_writeable = true;
        if (!is_writeable($pieces[5])) {
          $file_writeable = false;
          $messageStack->add(sprintf(ERROR_FILE_NOT_WRITEABLE, $pieces[5]), 'error');
        }
	 $config_key=$cInfo->configuration_key;
	  if ($config_key=="WALLET_MINIMUM_AMOUNT"){
	 			$wallet_type_query=tep_db_query("Select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='WALLET_MINIMUM_TYPE'");
 				 $wallet_type_result=tep_db_fetch_array($wallet_type_query);
				$minimum_type=$wallet_type_result['configuration_value'];
	  				if ($minimum_type=="P"){
						$type="P~V`1`" . addslashes(sprintf(ERR_VALUE_BETWEEN,1,100));
					} else {
						$type="N~V`1`" . addslashes(sprintf(ERR_VALUE_GREATER,1));
				     	}
	} else if ($config_key=="WALLET_FIRST_ORDER_BALANCE"){
		$type="N~V`1`" . addslashes(sprintf(ERR_VALUE_GREATER,1));
	} else {
		$type="";
	}
	
      $contents = array('form' => tep_draw_form('configuration', FILENAME_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cInfo->configuration_id . '&action=save','post','onSubmit="javascript:return validateForm(\'' . $type .'\')" '));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br><b>' . $cInfo->configuration_title . '</b><br>' . $cInfo->configuration_description . '<br>' . $value_field);
      if($search !="")
	  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&csID=' . $cInfo->configuration_id) . '&search='.$search.'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      else
	  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&csID=' . $cInfo->configuration_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
	  $contents[]=array('text'=> tep_draw_hidden_field('search',$FREQUEST->getvalue("search")));
	  break;
    case 'upload':
      $directory_writeable = true;
      if (!is_writeable($upload_fs_dir)) {
        $directory_writeable = false;
        $messageStack->add(sprintf(ERROR_DIRECTORY_NOT_WRITEABLE, $upload_fs_dir), 'error');
      }
	 $config_key=$cInfo->configuration_key;
	 	if($config_key=="COMPANY_LOGO"){
			$type="logo";
		}  else{
			$type="";
		}   
      $heading[] = array('text' => '<b>' . $cInfo->configuration_title . '</b>');

      $contents = array('form' => tep_draw_form('file', FILENAME_CONFIGURATION, 'action=processuploads&gID='.$gID.'&cID='.$FREQUEST->getvalue('cID'), 'post', 'enctype="multipart/form-data" onSubmit="javascript:return validate_image(\'' . $type .'\')"'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $file_upload = tep_draw_file_field('file_name') . '<br>';
      $contents[] = array('text' => '<br>' . $file_upload);
	  if($search !="")
	  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&csID=' . $cInfo->configuration_id) . '&search='.$search.'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      else
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cInfo->configuration_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      $contents[]=array('text'=> tep_draw_hidden_field('search',$FREQUEST->getvalue("search")));
	  break;
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->configuration_title . '</b>');

      if ($cInfo->set_function == 'file_upload') {
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cInfo->configuration_id . '&action=upload&eaction=upload&search=' . $search) . '">' . tep_image_button('button_upload.gif', IMAGE_EDIT) . '</a>'.'<p>');
        $contents[] = array('align' => 'center', 'text' => tep_image($upload_ws_dir . $cInfo->configuration_value, IMAGE_EDIT));
      } else if(($FREQUEST->getvalue("search")!='') || $search != ""){
	    $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cInfo->configuration_id . '&eaction=edit&action=edit&search=' . $search) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
	  } else {
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cInfo->configuration_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
      }
        $contents[] = array('text' => '<br>' . $cInfo->configuration_description);
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . format_date($cInfo->date_added));
        if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' ' . format_date($cInfo->last_modified));
      }

      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr><tr><td class="smallText" ><?PHP if($FREQUEST->getvalue('search')!="")// echo "$total_count configuration(s) found for '".$search."'";  ?></td>
		 <td class="smallText"></td> </tr>
        </table></td>
      </tr>
    </table></td>
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

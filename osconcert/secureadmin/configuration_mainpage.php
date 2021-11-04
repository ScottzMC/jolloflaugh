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
  tep_get_last_access_file();   		         
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
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, 1); ?></td>
			    <td class="smalltext" align="right">  
			    <?php
				 /*echo tep_draw_form('search', FILENAME_CONFIGURATION_MAINPAGE, 'action=search', 'get');
			   	 echo TEXT_SEARCH . ' '. tep_draw_input_field('search'); 
				 echo '</form>';*/
				 ?> 
					 
					
				</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
		<?php if (isset($HTTP_SESSION_VARS["config_error"])){ 
					$error=$HTTP_SESSION_VARS["config_error"];
					unset($HTTP_SESSION_VARS["config_error"]);
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
    $configuration_query = tep_db_query("select configuration_id, configuration_title, configuration_value, use_function,configuration_group_id from " . TABLE_CONFIGURATION . " order by last_modified desc limit 0,10");    
	$total_count= tep_db_num_rows($configuration_query);
    
   while ($configuration = tep_db_fetch_array($configuration_query)) {
    $gID=$configuration['configuration_group_id'];
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
    $cID=$FREQUEST->getvalue('cID');
    if ((($cID=='') || ($cID!='') && ($cID == $configuration['configuration_id'])) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {	  
      $cfg_extra_query = tep_db_query("select configuration_key, configuration_description, date_added, last_modified, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_id = '" . (int)$configuration['configuration_id'] . "'");
	  $cfg_extra = tep_db_fetch_array($cfg_extra_query);

      $cInfo_array = array_merge($configuration, $cfg_extra);
      $cInfo = new objectInfo($cInfo_array);
    }
	
	
	
    if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) {
    	if($cInfo->set_function == 'file_upload'){
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION_MAINPAGE, 'gID=' . $gID . '&cID=' . $cInfo->configuration_id . '&action=upload') . '\'">' . "\n";
      } else {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cInfo->configuration_id . '&action=edit') . '\'">' . "\n";
      }
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION_MAINPAGE, 'gID=' . $gID . '&cID=' . $configuration['configuration_id']) . '\'">' . "\n";
    }

?>
                <td class="dataTableContent"><?php echo $configuration['configuration_title']; ?></td>
				
                <td class="dataTableContent"><?php echo htmlspecialchars($cfgValue); ?></td>
                <td class="dataTableContent" align="right"><?php if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CONFIGURATION_MAINPAGE, 'gID=' . $gID . '&cID=' . $configuration['configuration_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
  // echo $cfgValue;
?>	  
<tr>
  <td colspan="2" class="smallText" ><?PHP   $search=$FREQUEST->getvalue('search');if($search!=''){
                                             if($total_count>0)  echo "$total_count configuration(s) found for '".$search."'";  
   											  else echo "0 configuration found"; 	?></td>
	<td><?php echo '<a href="' . tep_href_link(FILENAME_CONFIGURATION_MAINPAGE, 'gID=' . $gID . '&cID=' . $cInfo->configuration_id) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>';} ?></td>
	</tr> </table></td>
<?php
  $heading = array();
  $contents = array();
$pieces = explode("/", $cInfo->configuration_value);
	  if($search!='')
	    $action = (isset($eaction) ? $eaction : $action);
 
  switch ($action) {
    case 'edit':
      $heading[] = array('text' => '<b>' . $cInfo->configuration_title . '</b>');

      if ($cInfo->set_function) {
        eval('$value_field = ' . $cInfo->set_function . '"' . htmlspecialchars($cInfo->configuration_value) . '");');
      } else {
        $value_field = tep_draw_input_field('configuration_value', $cInfo->configuration_value);
      }		  
	   $pieces = explode("/", $cInfo->configuration_value);	  	  
	   $permissions = tep_get_file_permissions(fileperms($pieces[5]));	  
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
	 $config_key=="wallet_
		$type=\"N~V`1`" . addslashes(sprintf(ERR_VALUE_GREATER,1));
	 } else {
		$type="";
	 }
      $contents = array('form' => tep_draw_form('configuration', FILENAME_CONFIGURATION_MAINPAGE, 'gID=' . $gID . '&cID=' . $cInfo->configuration_id . '&action=save','post','onSubmit="javascript:return validateForm(\'' . $type .'\')" '));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br><b>' . $cInfo->configuration_title . '</b><br>' . $cInfo->configuration_description . '<br>' . $value_field);
      if($search !="")
	  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CONFIGURATION_MAINPAGE, 'gID=' . $gID . '&csID=' . $cInfo->configuration_id) . '&search='.$search.'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      else
	  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CONFIGURATION_MAINPAGE, 'gID=' . $gID . '&csID=' . $cInfo->configuration_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
	  $contents[]=array('text'=> tep_draw_hidden_field('search',$search));
	  break;    
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->configuration_title . '</b>');
      if($search!=''){
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
		 <td class="smallText"></td> </tr>
		 <tr style="height:30"><td></td></tr>
		 <tr>
		   <td colspan="2" class="infobox">
		     <table border="0" cellspacing="3" cellpadding="3" width="100%" class="info_content">
				 <?php 
				 $cfg_group_query = tep_db_query("select count(*) as count,cg.configuration_group_title,cg.configuration_group_id from " . TABLE_CONFIGURATION_GROUP." cg,".TABLE_CONFIGURATION." c where c.configuration_group_id=cg.configuration_group_id group by c.configuration_group_id order by configuration_group_title");  
				 if(tep_db_num_rows($cfg_group_query)>0){
				    $i=0;
				    while($cfg_group=tep_db_fetch_array($cfg_group_query)){		  	   
				  	   if($i%5==0)	echo '<tr>';?>
				  	   	   <td><a href="<?php echo tep_href_link(FILENAME_CONFIGURATION,'gID='.$cfg_group['configuration_group_id']);?>"><?php echo $cfg_group['configuration_group_title'].'('.$cfg_group['count'].')';?></td>   			  	   
				  	<?php
				  	$i++; 
				  	}
				 }     		 
				 ?>		 
		   </table>
		  </td>
		 </tr>
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

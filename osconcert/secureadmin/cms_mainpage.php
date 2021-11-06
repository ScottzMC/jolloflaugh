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
  tep_get_last_access_file(); // This will cause it to look for 'mainpage.php' 
  $FREQUEST->setvalue('filename','mainpage.php',"GET");
  // assign mainpage type value
  $products_categories=array();
  $products="-";    
  $htmlarea=array("id"=>"Default","text"=>TEXT_HTMLAREA);
  // if(HIDE_FROM_BACKEND_MENU_PRODUCTS=='false')
  // {
    $products=array("id"=>"Product","text"=>TEXT_PRODUCTS);
    $products_query=tep_db_query("select * from " . TABLE_CATEGORIES_DESCRIPTION . " where language_id='" . (int)$FSESSION->languages_id ."'");
	while($result_products=tep_db_fetch_array($products_query))
	{
	  $products_categories[]=array('id'=>$result_products['categories_id'],'text'=>$result_products['categories_name']);
    }
  // }

$mainpage_type=array($htmlarea,$products);
$action=$FREQUEST->getvalue('action');
  switch ($action) 
  {
    case 'save':
		
		$file_contents=stripslashes($_POST['file_contents']);
		$selected_type=$FREQUEST->postvalue('mainpage_type','string','Default');
		$selected_categories="Default";
		if($selected_type=='Product')
		{
		    $file_contents="";
			$selected_categories=$FREQUEST->postvalue('mainpage_product_categories');
		}

		   $query_type=tep_db_query("select * from configuration where configuration_key='DEFINEPAGE_TYPE'");
		   $query_categories=tep_db_query("select * from configuration where configuration_key='DEFINEPAGE_CATEGORIES'");
		   if(tep_db_num_rows($query_type)>0)
		   {
		     tep_db_query("UPDATE configuration set configuration_value='". tep_db_input($selected_type) ."' where configuration_key='DEFINEPAGE_TYPE'");
		   }else if(tep_db_num_rows($query_categories)==0)
		   {
		     tep_db_query("INSERT INTO configuration VALUES(NULL,'Define Mainpage type','DEFINEPAGE_TYPE','" . tep_db_input($selected_type) ."','Display the mainpage','902','15','2016-12-20','2016-12-20',NULL,NULL);"); 
		   }
		   if(tep_db_num_rows($query_type)>0)
		   {
		     tep_db_query("UPDATE configuration set configuration_value='" . tep_db_input($selected_categories) . "' where configuration_key='DEFINEPAGE_CATEGORIES'");
		   }else if(tep_db_num_rows($query_categories)==0)
		   {
		     tep_db_query("INSERT INTO configuration VALUES(NULL,'Define Mainpage categories','DEFINEPAGE_CATEGORIES','" . tep_db_input($selected_categories) . "','Display the mainpage','902','15','2016-12-20','2016-12-20',NULL,NULL);"); 
		   }
		   $filename=$FREQUEST->getvalue('filename');
		   $lngdir=$FREQUEST->getvalue('lngdir','string',$FSESSION->language);
		 if ( ($lngdir!='') && ($filename!='') ) 
		 {
        if ($filename == $FSESSION->language . '.php') 
		{
          $file = DIR_FS_TEMPLATES . DEFAULT_TEMPLATE . '/content/' .$filename;
        } else 
		{
          $file = DIR_FS_TEMPLATES . DEFAULT_TEMPLATE . '/content/' . $lngdir . '/' . $filename;
        }
        if (file_exists($file) && $selected_type=='Default') 
		{
          if (file_exists('bak' . $file)) {
            @unlink('bak' . $file);
          }
          @rename($file, 'bak' . $file);
          $new_file = fopen($file, 'w');
          fwrite($new_file, $file_contents, strlen($file_contents));
          fclose($new_file);
        }
        tep_redirect(tep_href_link(FILENAME_INFORMATION_PAGES, 'home_page=true&from=col&top=1'));
      }
      break;
  }
  $lngdir=$FREQUEST->getvalue('lngdir','string',$FSESSION->language);
  $languages_array = array();
  $languages = tep_get_languages();
  $lng_exists = false;
  for ($i=0; $i<sizeof($languages); $i++) 
  {
    if ($languages[$i]['directory'] == $lngdir) $lng_exists = true;
    $languages_array[] = array('id' => $languages[$i]['directory'],
                               'text' => $languages[$i]['name']);
  }
	if (!$lng_exists){
		$FREQUEST->setvalue('lngdir',$FSESSION->language,'GET');
	}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css"/>
<script language="javascript" src="includes/menu.js"></script>
<!--<script type="text/javascript" src="htmlarea/htmlarea.js"></script>
<script type="text/javascript" src="htmlarea/editor.js"></script>-->
<?php
	require(DIR_WS_INCLUDES . 'tweak/NEWtinyMce.php');
	textEditorLoadJS();
?>
  <script type="text/javascript">
  tinymce.init({
    selector: '#file_contents',
    theme: 'modern',
    plugins: [
      'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
      'save table contextmenu directionality emoticons template paste textcolor'
    ],
    content_css: 'css/content.css',
    toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons'
  });
  </script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); 
	$filename=$FREQUEST->getvalue('filename');
	$lngdir=$FREQUEST->getvalue('lngdir','string',$FSESSION->language);
 if ( ($lngdir!='') && ($filename!='') ) 
 {
    if ($filename == $FSESSION->language . '.php') {
      $file = DIR_FS_TEMPLATES . DEFAULT_TEMPLATE . '/content/' . $filename;
    } else {
      $file =DIR_FS_TEMPLATES . DEFAULT_TEMPLATE . '/content/' . $lngdir . '/' . $filename;
    }
	?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr> 
<!-- body_text //-->
 <?php    if (file_exists($file)) 
 {
      $file_array = @file($file);
      $file_contents = @implode('', $file_array);

      $file_writeable = true;
      if (!is_writeable($file)) {
        $file_writeable = false;
        $messageStack->reset();
        $messageStack->add(sprintf(ERROR_FILE_NOT_WRITEABLE, $file), 'error');
        $error_msg=$messageStack->output();
      }

?>
<!-- header_eof //-->
<!-- body //-->
<script language="javascript">
	function select_type()
	{
	 var mainpage_type=document.language.mainpage_type.value;
	 var frm_product=document.getElementById("products");
	 var frm_cate=document.getElementById("cate");
	 if(("<?php echo DEFINEPAGE_TYPE;?>"!='Default')||(mainpage_type=='Product')) frm_cate.style.display="";
		  if(mainpage_type=='Product') {
		   document.getElementById("error_msg").style.display="none";
		   document.getElementById("save_bu").style.display="";
		   //editor._iframe.style.visibility = "hidden";
		   frm_product.style.display="";
		 }else  {
		if("<?php echo $file_writeable;?>"==""){
			document.getElementById("save_bu").style.display="none";
			document.getElementById("error_msg").style.display="";
		}else {	
			if(editor._iframe)
		   editor._iframe.style.visibility = "visible";
		}
		   frm_product.style.display="none";
		   frm_cate.style.display="none";
		 }
	}
	function validate_form(){
	var content=document.language.file_contents.value;
	if(document.language.mainpage_type.value=='Default' && content==""){
	   alert("Content Cannot empty");
	   return false;
	 }
	   return true;
	}
  </script>
    <td width="100%" valign="top">
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php echo tep_draw_form('lng', FILENAME_DEFINE_MAINPAGE, '', 'get'); ?>
            <td class="pageHeading"><?php //echo BOX_CATALOG_DEFINE_MAINPAGE; ?></td>
            <td class="pageHeading" align="right"></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_pull_down_menu('lngdir', $languages_array, $lngdir, 'onChange="this.form.submit();"'); ?></td>
		  </form></tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr id="error_msg"><td><?php echo (($error_msg)?$error_msg:'');?></td></tr>
          <tr><?php echo tep_draw_form('language', FILENAME_DEFINE_MAINPAGE, 'lngdir=' . $lngdir . '&filename=' . $filename . '&action=save','post','onsubmit="javascript:return validate_form();"'); ?>
            <td><table border="0" cellspacing="0" cellpadding="2" class="openContent" width="100%">
				<tr height="30">
					<td width="2%">&nbsp;&nbsp;&nbsp;</td>	
					<td align="right" valign="top"><div id="save_bu" style="<?php echo (($error_msg)?'display:none':'display:');?>"><input type='image' src='images/template/img_savel.gif' alt='save' title='save'  border='0'></div></td>
             		<td width="2%" valign="top"><?php echo '<a style="text-decoration:none" href="' . tep_href_link(FILENAME_INFORMATION_PAGES, 'home_page=true&from=col&top=1') . '">'; ?><input type="image" src="images/template/img_closel.gif" alt="close" title="close"  border="0"><?php echo '</a>'; ?></td>		
			  </tr>	
			  <tr height="30">
			  	<td width="2%">&nbsp;&nbsp;&nbsp;</td>	
                <td class="main" valign="top"><b><?php echo ucfirst($filename); ?></b></td>
				<td width="2%">&nbsp;</td>	
              </tr>
			   
              <tr>
				<td width="2%">&nbsp;&nbsp;&nbsp;</td>
				<td class="main"><?php echo tep_draw_textarea_field('file_contents', 'soft', '120', '35', $file_contents,  ''); ?></td>
              	<td width="2%">&nbsp;</td>	
			  </tr>
	    <?php 
		if(!$file_contents)
		{
 		echo "no content";
	    }
		?>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
				<tr>
					<td width="2%">&nbsp;&nbsp;&nbsp;</td>	
					<td colspan="2" class="TableHeading"><?php //echo TEXT_MAINPAGE_TYPE . '&nbsp;' . tep_draw_pull_down_menu('mainpage_type',$mainpage_type,DEFINEPAGE_TYPE,'onchange="javascript:select_type();"');?></td>
				</tr>
				<tr>
					<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				</tr>
				<tr>
					<td colspan="3" class="TableHeading">
					  <?php echo  '<span id="cate" style="display:'.((DEFINEPAGE_TYPE=='Default') && (DEFINEPAGE_CATEGORIES=='Default')?'none;':"''").'">' . TEXT_MAINPAGE_CATEGORIES .'</span>'. '&nbsp;<span id="products"'.((DEFINEPAGE_TYPE=='Product') && (DEFINEPAGE_CATEGORIES)?'':' style="display:none"').'>' . tep_draw_pull_down_menu('mainpage_product_categories',$products_categories,DEFINEPAGE_CATEGORIES) . '</span>';?>
					</td>
				</tr>
            </table></td>
          </form></tr><script>initEditor('file_contents');</script>
<?php
    } else 
	{
?>
	<td valign="top"><table width="100%">
          <tr height="100">
            <td class="main" align="center"><b><?php echo $file . '<br><br><font color="red">' . TEXT_FILE_DOES_NOT_EXIST . "</font>"; ?></b></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		 </table></td>
<?php
    }
  } else {
    $filename = $lngdir . '.php';
?>
          <tr>
            <td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText"><a href="<?php echo tep_href_link(FILENAME_DEFINE_MAINPAGE, 'lngdir=' . lngdir . '&filename=' . $filename); ?>"><b><?php echo $filename; ?></b></a></td>
<?php
    $dir = dir(DIR_FS_CATALOG_LANGUAGES . $lngdir);
    $left = false;
    if ($dir) 
	{
      $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
      while ($file = $dir->read()) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          echo '                <td class="smallText"><a href="' . tep_href_link(FILENAME_DEFINE_MAINPAGE, 'lngdir=' . $lngdir . '&filename=' . $file) . '">' . $file . '</a></td>' . "\n";
          if (!$left) {
            echo '              </tr>' . "\n" .
                 '              <tr>' . "\n";
          }
          $left = !$left;
        }
      }
      $dir->close();
    }
?>
              </tr>
            </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
   <td align="right"><?php //echo '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'current_path=' . DIR_FS_CATALOG_LANGUAGES . $lngdir) . '">' . tep_image_button('button_file_manager.gif', IMAGE_FILE_MANAGER) . '</a>'; ?></td>
  </tr>
<?php
  }
?>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php if (file_exists($file)) {?>
<script language="javascript">select_type();</script>
<?php }?>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
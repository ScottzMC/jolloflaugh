<?php

/*

osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

Released under the GNU General Public License

UPDATED 30 May 2021 added abilty to make blocked seats active
*/
define( '_FEXEC', 1 );

  require('includes/application_top.php');
  
 //Missing language id for some reason
 $languages_id = $_SESSION['languages_id'];
 
 //add this function - could be popped into includes/functions/general but for simplicity here it is
 
   function tep_get_products_number($product_id, $language_id = 0) 
   {
    global $FSESSION;
	
	if($language_id<=0) 
	$language_id=$FSESSION->languages_id;
    $product_query = tep_db_query("select products_number from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_number'];
  }
  require('includes/functions/categories_description.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $server_date = getServerDate(true);	

	if (tep_not_null($action)) 
	{
		switch ($action) 
		{
		  case 'setflag':
			if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') || ($_GET['flag'] == '8')|| ($_GET['flag'] == '3')) 
			{
			  if (isset($_GET['pID'])) 
			  {
				tep_set_product_status($_GET['pID'], $_GET['flag']);
			  }
			}

			tep_redirect(tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID']));
			break;
			
		
	   // case 'pos_up':
	   // case 'pos_down':
	   
		// $pos=(isset($_GET['pos'])?(int)$_GET['pos']:0);
		// if ($pos>0)
		// {
			 // if ($action=='pos_up')
				// $products_sort_query=tep_db_query("Select p.products_sort_order, p.products_id from products p, products_to_categories pc where pc.categories_id='" . $_GET['cPath'] . "' and pc.products_id=p.products_id and p.products_sort_order<" . $pos . " order by products_sort_order desc limit 1");
			 // else if($action=='pos_down')
				 // $products_sort_query=tep_db_query("Select p.products_sort_order, p.products_id from products p, products_to_categories pc where pc.categories_id='" . $_GET['cPath'] . "' and pc.products_id=p.products_id and p.products_sort_order>" . $pos . " order by products_sort_order limit 1");
			 
			 
			 // if (tep_db_num_rows($products_sort_query)>0)
			 // {
				// $products_sort_result=tep_db_fetch_array($products_sort_query);
				// $prev_order=$products_sort_result['products_sort_order'];
				// tep_db_query("UPDATE " . TABLE_PRODUCTS . " set products_sort_order='" . $pos ."' where products_id='" . (int)$products_sort_result['products_id'] . "'");
				// tep_db_query("UPDATE " . TABLE_PRODUCTS . " set products_sort_order='" . $prev_order . "' where products_id='" . (int)$_GET['pID'] ."'");
			 // }
		// }
			// tep_redirect(tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID']));
		// break;	

		  // case 'new_category':
		  // case 'edit_category':
			// if (ALLOW_CATEGORY_DESCRIPTIONS == 'true')
			  // $_GET['action']=$_GET['action'] . '_ACD';
			// break;

		  // case 'insert_category':
		  // case 'update_category':

		// if ( ($_POST['edit_x']) || ($_POST['edit_y']) ) 
		// {
			// $_GET['action'] = 'edit_category_ACD';
		// } 
		// else 
		// {

			// if (isset($_POST['categories_id'])) $categories_id = tep_db_prepare_input($_POST['categories_id']);


			// if ($categories_id == '') 
			// {
			   // $categories_id = tep_db_prepare_input($_GET['cID']);
			// }


			// $sort_order = tep_db_prepare_input($_POST['sort_order']);
			// $categories_status = tep_db_prepare_input($_POST['categories_status']);

			//$sql_data_array = array('sort_order' => $sort_order);
			//$sql_data_array = array('categories_status' => $categories_status);

			// if ($action == 'insert_category') 
			// {
			  // $insert_sql_data = array('parent_id' => $current_category_id,
									   // 'date_added' => $server_date);

			  // $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

			  // tep_db_perform(TABLE_CATEGORIES, $sql_data_array);

			  // $categories_id = tep_db_insert_id();
			// } elseif ($action == 'update_category') 
			// {
			  // $update_sql_data = array('last_modified' => $server_date);

			  // $sql_data_array = array_merge($sql_data_array, $update_sql_data);

			  // tep_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "'");
			// }
			
		

			$languages = tep_get_languages();
			for ($i=0, $n=sizeof($languages); $i<$n; $i++) 
			{
			  $categories_name_array = $_POST['categories_name'];

			  $language_id = $languages[$i]['id'];

			  $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]));


				if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') 
				{
				  $sql_data_array = array('categories_name' => tep_db_prepare_input($_POST['categories_name'][$language_id]),
										  'categories_heading_title' => tep_db_prepare_input($_POST['categories_heading_title'][$language_id]),
										  'categories_description' => tep_db_prepare_input($_POST['categories_description'][$language_id]));
				}


			  // if ($action == 'insert_category') 
			  // {
				// $insert_sql_data = array('categories_id' => $categories_id,
										 // 'language_id' => $languages[$i]['id']);

				// $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

				// tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
			  // } elseif ($action == 'update_category') 
			  // {
				// tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
			  // }
			}

			// if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') 
			// {
			// tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . $_POST['categories_image'] . "' where categories_id = '" .  tep_db_input($categories_id) . "'");
			// $categories_image = '';
			// } else 
			// {
				// if ($categories_image = new upload('categories_image', DIR_FS_CATALOG_IMAGES)) 
				// {
				// tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$categories_id . "'");
				// }
			// }


			// tep_redirect(tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));

		}

}
		
?>


<?php
//$go_back_to=$REQUEST_URI;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="JavaScript" src="includes/date-picker.js"></script>

<?php
	require(DIR_WS_INCLUDES . 'tweak/' . HTML_EDITOR . '.php');
	textEditorLoadJS();
?>
<?php
include(DIR_WS_INCLUDES . 'javascript/' . 'webmakers_added_js.php')
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<!--Add osConcert message-->
            <div class="osconcert_message">
            <?php echo OSCONCERT_MESSAGE_AEC; ?>
            </div><br>
			<div class="osconcert_message">
            <?php echo OSCONCERT_MESSAGE_PD; ?>
            </div>
            <!--EOF osConcert message-->
	<!-- body_text //-->
	     <table border="0" width="100%" cellspacing="0" cellpadding="2">
	<?php   //----- new_category / edit_category (when ALLOW_CATEGORY_DESCRIPTIONS is 'true') -----
  if ($_GET['action'] == 'new_category_ACD' || $_GET['action'] == 'edit_category_ACD') 
  {
    if ( ($_GET['cID']) && (!$_POST) ) 
	{
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name,c.categories_status, cd.categories_heading_title, cd.categories_description, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $_GET['cID'] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_id");
      $category = tep_db_fetch_array($categories_query);

      $cInfo = new objectInfo($category);
    } elseif ($_POST) 
	{
      $cInfo = new objectInfo($_POST);
      $categories_name = $_POST['categories_name'];
	  $categories_status = $_POST['categories_status'];
      $categories_heading_title = $_POST['categories_heading_title'];
      $categories_description = $_POST['categories_description'];
      $categories_url = $_POST['categories_url'];
    } else 
	{
      $cInfo = new objectInfo(array());
    }

    $languages = tep_get_languages();

    $text_new_or_edit = ($_GET['action']=='new_category_ACD') ? TEXT_INFO_HEADING_NEW_CATEGORY : TEXT_INFO_HEADING_EDIT_CATEGORY;
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf($text_new_or_edit, tep_output_generated_category_path($current_category_id)); ?></td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('new_category', FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $_GET['cID'] . '&action=new_category_preview', 'post', 'enctype="multipart/form-data"'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
<?php
    for ($i=0; $i<sizeof($languages); $i++) 
	{
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_NAME; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', (($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : tep_get_category_name($cInfo->categories_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php 
    for ($i=0; $i<sizeof($languages); $i++) 
	{
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_HEADING_TITLE; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_heading_title[' . $languages[$i]['id'] . ']', (($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0; $i<sizeof($languages); $i++) 
	{
?>
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) //echo TEXT_EDIT_CATEGORIES_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0" style="display:none">
              <tr>
                <td class="main" valign="top"><?php //echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php //echo tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($categories_description[$languages[$i]['id']]) ? stripslashes($categories_description[$languages[$i]['id']]) : tep_get_category_description($cInfo->categories_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
    }
?>

          <tr>
            <td class="main">Category Status (1=enabled)(0=disabled)</td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('categories_status', $cInfo->categories_status, 'size="2"'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo tep_draw_hidden_field('categories_date_added', (($cInfo->date_added) ? $cInfo->date_added : getServerDate())) . tep_draw_hidden_field('parent_id', $cInfo->parent_id) . tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $_GET['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </form></tr>
		<?php

		//----- new_category_preview (active when ALLOW_CATEGORY_DESCRIPTIONS is 'true') -----
	}//action
	elseif ($_GET['action'] == 'new_category_preview') 
	{
		if ($_POST) 
		{
		  $cInfo = new objectInfo($_POST);
		  $categories_name = $_POST['categories_name'];
		  $categories_heading_title = $_POST['categories_heading_title'];
		  $categories_description = $_POST['categories_description'];

	// copy image only if modified
			$categories_image = new upload('categories_image');
			$categories_image->set_destination(DIR_FS_CATALOG_IMAGES);
			if ($categories_image->parse() && $categories_image->save()) {
			  $categories_image_name = $categories_image->filename;
			} else {
			$categories_image_name = $_POST['categories_previous_image'];
		  }
	#     if ( ($categories_image != 'none') && ($categories_image != '') ) {
	#       $image_location = DIR_FS_CATALOG_IMAGES . $categories_image_name;
	#       if (file_exists($image_location)) @unlink($image_location);
	#       copy($categories_image, $image_location);
	#     } else {
	#       $categories_image_name = $_POST['categories_previous_image'];
	#     }
		} else 
		{
		  $category_query = tep_db_query("select c.categories_id, cd.language_id, cd.categories_name, c.categories_status,cd.categories_heading_title, cd.categories_description, c.categories_image, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and c.categories_id = '" . $_GET['cID'] . "'");
		  $category = tep_db_fetch_array($category_query);

		  $cInfo = new objectInfo($category);
		  $categories_image_name = $cInfo->categories_image;
		}

    $form_action = ($_GET['cID']) ? 'update_category' : 'insert_category';
    echo tep_draw_form($form_action, FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $_GET['cID'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

    $languages = tep_get_languages();
    for ($i=0; $i<sizeof($languages); $i++) 
	{
      if ($_GET['read'] == 'only') 
	  {
        $cInfo->categories_name = tep_get_category_name($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_heading_title = tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_description = tep_get_category_description($cInfo->categories_id, $languages[$i]['id']);
      } else 
	  {
        $cInfo->categories_name = tep_db_prepare_input($categories_name[$languages[$i]['id']]);
        $cInfo->categories_heading_title = tep_db_prepare_input($categories_heading_title[$languages[$i]['id']]);
        $cInfo->categories_description = tep_db_prepare_input($categories_description[$languages[$i]['id']]);
      }
?>



<?php
    }
    if ($_GET['read'] == 'only') 
	{
      if ($_GET['origin']) {
        $pos_params = strpos($_GET['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($_GET['origin'], 0, $pos_params);
          $back_url_params = substr($_GET['origin'], $pos_params + 1);
        } else 
		{
          $back_url = $_GET['origin'];
          $back_url_params = '';
        }
      } else 
	  {
        $back_url = FILENAME_BACKUP_CATEGORIES;
        $back_url_params = 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    } else 
	{
?>
      <tr>
        <td align="right" class="smallText">
<?php
/* Re-Post all POST'ed variables */
      reset($_POST);
		foreach($_POST as $key => $value)  
	  {
        if (!is_array($_POST[$key])) {
          echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        }
      }
      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) 
	  {
        echo tep_draw_hidden_field('categories_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($categories_name[$languages[$i]['id']])));
        echo tep_draw_hidden_field('categories_heading_title[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($categories_heading_title[$languages[$i]['id']])));
        echo tep_draw_hidden_field('categories_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($categories_description[$languages[$i]['id']])));
      }
      echo tep_draw_hidden_field('X_categories_image', stripslashes($categories_image_name));
      echo tep_draw_hidden_field('categories_image', stripslashes($categories_image_name));

      echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if ($_GET['cID']) 
	  {
        echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
      } else 
	  {
        echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $_GET['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </form></tr>
<?php
    }


  } 
  
  elseif ($action == 'new_product') 
  {
    $parameters = array('products_name' => '',
                       'products_description' => '',
					    'products_number' => '',
                       'products_url' => '',
                       'products_id' => '',
                       'products_quantity' => '',
					   'master_quantity' => '',
					   'product_type' => '',
					   'is_attributes' =>'',
                       'products_model' => '',
					   'color_code' => '',
                       'products_image_1' => '',
                       'products_price' => '',
                       'products_weight' => '',
					   'products_sort_order' =>'',
                       'products_date_added' => '',
                       'products_last_modified' => '',
                       //'products_date_available' => '',
                       'products_status' => '',
                       'products_tax_class_id' => '',
                       'manufacturers_id' => '');

    $pInfo = new objectInfo($parameters);

    if (isset($_GET['pID']) && empty($_POST)) 
	{
      $product_query = tep_db_query("select pd.products_name, pd.products_description, pd.products_number, p.product_type,p.color_code, pd.products_url, p.products_id, p.products_quantity,p.master_quantity, p.is_attributes,p.products_model,
	  								 p.products_image_1,p.products_price, p.products_weight,p.products_sort_order, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.manufacturers_id, p.product_type from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$_GET['pID'] . "' and p.product_type='P' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
      $product = tep_db_fetch_array($product_query);
      $pInfo->objectInfo($product);
    } elseif (tep_not_null($_POST)) 
	{
      $pInfo->objectInfo($_POST);
      $products_name = $_POST['products_name'];
      $products_description = $_POST['products_description'];
	   $products_number = $_POST['products_number'];
      $products_url = $_POST['products_url'];
    }

    // $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
    // $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    // while ($manufacturers = tep_db_fetch_array($manufacturers_query)) 
	// {
      // $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                     // 'text' => $manufacturers['manufacturers_name']);
    // }

    # get selected categories
    $categories_query_selected = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $_GET['pID'] . "'");
    $categories_array_selected = array(array('id' => ''));
    while ($categories = tep_db_fetch_array($categories_query_selected)) 
	{
      $categories_array_selected[] = array('id' => $categories['categories_id']);
    }
		//have to try and stop manufacters_id being changed
	
	//$pID=$FREQUEST->getvalue('pID');
//	$manu_query=tep_db_query("select * from " . TABLE_PRODUCTS ." where products_id= '" . tep_db_input($pID) . "'");
//	while($temp_result=tep_db_fetch_array($manu_query)){
//	$manu_categories_array_selected[]=array('id' => $temp_result['manufacturers_id']);
//	$products_type=$temp_result['product_type'];
//	}

	//$manu_categories_array_selected=array();
    $categories_array = array(array('id' => '', 'text' => TEXT_NONE));
    #PR Algozone: Categories list displays only for one language (Deafault is English)
    $language_id = 1;
    $categories_array = tep_get_category_tree(); // added by R Calder

   // $form_action = ($_GET['pID']) ? 'update_product' : 'insert_product';
	
//	echo $form_action;
    // $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    // $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    // while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      // $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 // 'text' => $tax_class['tax_class_title']);
    // }

    $languages = tep_get_languages();

    if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
    switch ($pInfo->products_status) {
      case '0': $in_status = false; $out_status = true; break;
      case '1':
      default: $in_status = true; $out_status = false;
    }
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="javascript"> 
var tax_rates = new Array();
var option_array=new Array();
var attributes_array=new Array();
var values_arr=new Array();
<?php
    for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) 
	{
      if ($tax_class_array[$i]['id'] > 0) 
	  {
        echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . tep_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
      }
    }
	for($i=0,$n=sizeof($options_values_array); $i<$n; $i++){?>
				option_array[<?php echo $i;?>]="<?php echo $options_values_array[$i]['options_id'].'#'.$options_values_array[$i]['values_id'].'#'.$options_values_array[$i]['value_name'];?>";		
				values_arr[<?php echo $options_values_array[$i]['values_id']?>]="<?php echo $options_values_array[$i]['value_name']?>";
	<?php	}
	
	
?>
var attrb_cnt=0;
var pname="";
var cname="";
var splt=Array();
 for(i=0;i<option_array.length;i++){
		splt=option_array[i].split("#");
		cname=splt[0];
		if(pname!=cname){
			attributes_array[attrb_cnt]=cname;
			attrb_cnt++;
			pname=splt[0];
		}
	}
function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function getTaxRate() {
  var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
  var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0;
  }
}

function updateGross() {
  var taxRate = getTaxRate();
  var grossValue = document.forms["new_product"].products_price.value;

  if (taxRate > 0) {
    grossValue = grossValue * ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
}

function updateNet() {
  var taxRate = getTaxRate();
  var netValue = document.forms["new_product"].products_price_gross.value;

  if (taxRate > 0) {
    netValue = netValue / ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price.value = doRound(netValue, 4);
}
function do_quantity_check() {
  var attribute_quantity = document.forms["new_product"].attribute_quantity.value;
  var product_quantity = document.forms["new_product"].products_quantity.value;
  if (attribute_quantity > product_quantity){
   alert("Attribute Quantity exceeded");
	}
}
function change(){
	var arr_text=Array();
	var arr_value=Array();
	var attrb_splt=Array();
	var cnt=0;
	var icnt=0;
	var parr_name="";
	var carr_name="";
	if(option_array.length>0 ){
		deleteoptions();
	  for(i=0;i<option_array.length;i++){
	 	cnt=i+1;
		attrb_splt=option_array[i].split("#");
		if(document.getElementById("option"+"["+cnt+"]").checked){
			arr_text=attrb_splt[2];
			arr_value=attrb_splt[1];
			addoptions(arr_text,arr_value,attrb_splt[0]);
			icnt++;
		}	
	}
	check_option_exists();
  }
}

function addoptions(textArr,valueArr,nameArr){
  var option = document.createElement('option');
  option.text = textArr;
  option.value = valueArr;
  document.getElementById('options'+nameArr).style.display="";
  var optionElement = document.getElementById('option_values'+nameArr);
  try {
	optionElement.add(option, null); // standards compliant; doesn't work in IE
  }
  catch(ex) {
	optionElement.add(option); // IE only
  }
}

function deleteoptions(){
var carr_name="";
	 for(i=0;i<attributes_array.length;i++){
		carr_name=document.getElementById('option_values'+attributes_array[i]);
		for (j = carr_name.length - 1; j>=0; j--) {
			carr_name.remove(j);
		}
	}
}

function check_option_exists(){
var carr_name="";
	 for(i=0;i<attributes_array.length;i++){
		carr_name=document.getElementById('option_values'+attributes_array[i]);
			if(carr_name.length<=0)
				document.getElementById('options'+attributes_array[i]).style.display="none";
	}
}

	function do_action(action){
		var frm=document.new_product;
		var error_result="";
		var del_items="";
			var sIndex=frm.attribute_list.selectedIndex;
			switch(action){
				case "edit":
				case "add":
				if(subvalidateForm() == false){
					return;
					}
				var temp=getDetails();
				var pos;
				dateAdd=temp[1];
				dateAddText=temp[0];
					if (check_attribute_exists(dateAdd,action)){
						alert('* <?php echo addslashes(TEXT_ATTRIBUTE_ALREADY_EXISTS); ?>');
						return;
					}
					if (action=="edit") {
						if(sIndex<0) return;
						frm.attribute_list.remove(sIndex);
						
					}					
					var pos=get_position(dateAdd);
					var oOption = document.createElement("OPTION");
					oOption.text=dateAddText;
					oOption.value=dateAdd;
					if(frm.attribute_list.add.length==2){
					 frm.attribute_list.add(oOption,null);
					}
					else {
					 frm.attribute_list.add(oOption,pos);
					}
						pos=frm.attribute_list.length;
						frm.attribute_list.selectedIndex=pos-1;
						frm.quantity.value='';
						//clear_block_content();					
					break;
				case "delete":
					if (sIndex<0) return;
					frm.attribute_list.remove(sIndex);
					if (sIndex>0 && sIndex<frm.attribute_list.length-1)
						sIndex=sIndex+1;
					else if (sIndex>0)
						sIndex=sIndex-1;
					else if (sIndex>=frm.attribute_list.length) sIndex=frm.attribute_list.length-1;
					frm.attribute_list.selectedIndex=sIndex;
					do_action('select');
					break;
				case "select":
					var attributes=Array();
					var attrb_splt=Array();
					var values=Array();
					if(sIndex>=0){
						values=frm.attribute_list.options[sIndex].value.split('#');
						attributes=values[0].split('-');
						for(i=0;i<attributes.length;i++){
							attrb_splt=attributes[i].split('{');
							document.getElementById("option_values"+attrb_splt[0]).value=attrb_splt[1].substring(0,(attrb_splt[1].length-1));
						}
						document.new_product.quantity.value=values[1];
					}
				return; 
			}
		} 
		
		function getDetails(){
			var carr_name="";
			var attrbAdd=Array();
			var attrb_text="";
			var attrb_value="";
			 for(i=0;i<attributes_array.length;i++){
				carr_name=document.getElementById('option_values'+attributes_array[i]);
				if(carr_name.length>0){
				attrb_text+=carr_name.options[carr_name.selectedIndex].text + ',';
				attrb_value+=attributes_array[i]+'{'+carr_name.options[carr_name.selectedIndex].value+'}-';
				}
			}
			attrb_text=attrb_text.substring(0,(attrb_text.length)-1)+','+document.new_product.quantity.value;
			attrb_value=attrb_value.substring(0,(attrb_value.length)-1)+'#'+document.new_product.quantity.value;
			attrbAdd[0]=attrb_text;
			attrbAdd[1]=attrb_value;
			return attrbAdd;
		}
		
		function ValidateForm(){
		var frm=document.new_product;
		var address="";
		var icnt;
		var quantity=0;
		var attrb_splt=Array();
		if(frm.is_attributes.checked){
			if(frm.attribute_list.length>0){
				for (icnt=0;icnt<frm.attribute_list.length;icnt++){
					attrb_splt=(frm.attribute_list[icnt].value).split("#");
					quantity+=parseInt(attrb_splt[1]);
					address+=frm.attribute_list.options[icnt].value + "/";
				}
				frm.attributes_array.value=address;
				if(quantity!=frm.products_quantity.value){
					alert('* <?php echo TEXT_QUANTITY_MISMATCH;?>');
					return false;
				}
			}
			else {
			alert('* <?php echo TEXT_ERROR_ATTRIBUTES_EMPTY;?>');
			return false;
			}
		}
		return true;
		}
		
		function subvalidateForm(){
			var frm=document.new_product;
			if(frm.quantity.value=="" || frm.quantity.value==0 || isNaN(frm.quantity.value) || frm.quantity.value<0){
				alert('* <?php echo TEXT_QUANTITY_ERROR;?>');
				return false;
			}
			return true;	
		}	
		
		function check_attribute_exists(dateAddText,mode){
			frm=document.new_product;
			sIndex=-1;
			var dateAddvalue=dateAddText.split('#');
			if (mode=="edit") sIndex=frm.attribute_list.selectedIndex;
			for (icnt=0;icnt<frm.attribute_list.length;icnt++){
				var attrb=frm.attribute_list.options[icnt].value;
				var attrb_value=attrb.split('#');
				if(icnt!=sIndex && attrb_value[0] == dateAddvalue[0]){
					return true;
				}
			}
			return false;
		} 
		
		function get_position(dateAdd){
			frm=document.new_product;
			for (icnt=0;icnt<frm.attribute_list.length;icnt++){
				if(dateAdd<=frm.attribute_list.options[icnt].value){
					return icnt;
				}
			}
			return icnt;
		 }
		 
		function init_page(){
		if (document.new_product.attribute_list.options.length>0){
			document.new_product.attribute_list.selectedIndex=0;
			do_action("select");
		} 
		} 



//--></script>
<?php //sakwoya - noew product entry?>
    <?php //echo tep_draw_form('new_product', FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $_GET['pID'] . '&action='. $form_action, 'post', 'enctype="multipart/form-data"'); 
	 //echo tep_draw_form($form_action, FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $_GET['cID'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');
	 
	 
	 echo tep_draw_form('new_product', FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $_GET['pID'] . '&action=new_product_preview', 'post', ' onSubmit="return ValidateForm();" enctype="multipart/form-data"'); 
	
	?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">

      <tr>
        <td class="pageHeading"><?php echo sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id)); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
         <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;' . tep_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE; ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr><?php $_array=array('d','m','Y');  $replace_array=array('DD','MM','YYYY'); 	$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);?>
            <td class="main"><?php //echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><!--<strong>SHOW CATEGORY</strong>--></td>
            <td class="main" <?php if ($product->product_type=="P"){ 
									echo "style=display:none";
									}
									?>>
			<?php  //echo '&nbsp;' . tep_draw_pull_down_menu('manufacturers_id', $categories_array, $pInfo->manufacturers_id);
			
			
			//echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field("products_date_available",format_date($pInfo->products_date_available),"size=10",false,'text',false);?>
			               <!-- <a href="javascript:show_calendar('new_product.products_date_available',null,null,'<?php echo $date_format;?>');"
							onmouseover="window.status='Date Picker';return true;"
							onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/>  
							</a>-->
							</td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME; ?></td>
			<td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (isset($products_name[$languages[$i]['id']]) ? stripslashes($products_name[$languages[$i]['id']]) : tep_get_products_name($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price', $pInfo->products_price, 'onKeyUp="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_gross', $pInfo->products_price, 'OnKeyUp="updateNet()"'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<script language="javascript"><!--
updateGross();
//--></script>
		
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) 
	{
?>
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) //echo TEXT_PRODUCTS_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0" style="display:none">
              <tr>
                <td class="main" valign="top"><?php //echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php //echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '80', '24', (isset($products_description[$languages[$i]['id']]) ? stripslashes($products_description[$languages[$i]['id']]) : tep_get_products_description($pInfo->products_id, $languages[$i]['id'])),'id="products_description[' . $languages[$i]['id'] . ']"'); ?></td>
              </tr>
            </table></td>
          </tr>
          
           <tr>
            <td class="main">Seat Number (Seat cell link):</td>
			<td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_number[' . $languages[$i]['id'] . ']', (isset($products_number[$languages[$i]['id']]) ? stripslashes($products_number[$languages[$i]['id']]) : tep_get_products_number($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_quantity', $pInfo->products_quantity); ?></td>
          </tr>
          <tr>
            <td class="main">Master <?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('master_quantity', $pInfo->master_quantity); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_model', $pInfo->products_model); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <?php 
		  for ($icnt=1;$icnt<=1;$icnt++)
		  { 
		  			$var_name_1="products_image_" . $icnt;
					//$var_name_2="products_title_" . $icnt;
					if (isset($_POST[$var_name_1])) $image_name=$_POST[$var_name_1];
					else $image_name=$pInfo->$var_name_1;
		  ?>
		  <tr>
		  	<td colspan="2" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" style="display:none">
				  <tr>
					<td class="main" valign="top"><?php echo sprintf(TEXT_PRODUCTS_IMAGE,$icnt); ?></td>
					<td class="main" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image_' . $icnt) . '<br>' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $image_name . tep_draw_hidden_field('products_previous_image_' .$icnt, $image_name); ?></td>
				  </tr>
			</table>
			</td>
		</tr>
		  <?php  } ?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) 
	{
?>
          <tr>
            <td class="main">Color Banding:<?php //if ($i == 0) echo TEXT_COLOR_CODE . ; ?></td>
            <td class="main"><?php
			
			 
			echo tep_draw_input_field('color_code', $pInfo->color_code);
			//echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (isset($products_url[$languages[$i]['id']]) ? stripslashes($products_url[$languages[$i]['id']]) : tep_get_products_url($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_WEIGHT; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_weight', $pInfo->products_weight); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
     <!--     <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_SORT_ORDER; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_sort_order', $pInfo->products_sort_order); ?></td>
          </tr> 
		  <tr>
			<td style="display:none" colspan="2" class="main"><?php //echo tep_draw_checkbox_field("is_attributes",'Y',($pInfo->is_attributes=='Y'?true:false)) .'&nbsp;'. TEXT_IS_ATTRIBUTES;?></td>
		</tr>	
        </table></td>
      </tr>-->
       
<?php
/////////////////////////////////////////////////////////////////////
// BOF: WebMakers.com Added: Draw Attribute Tables
?>
<?php
    // $rows = 0;
    // $options_query = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_sort_order");
	
    // while ($options = tep_db_fetch_array($options_query)) { 
	?>
		
	<?php   
	// $values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "'");
      // $header = false;
      // while ($values = tep_db_fetch_array($values_query)) {
        // $rows ++;
        // if (!$header) {
          // $header = true;
?>
          <tr valign="top">
<td><table border="0" cellpadding="1" cellspacing="1">
              <tr>
            	<td colspan="15"><?php if ($options['products_options_name']=='Size')  echo tep_black_line(); ?></td>
         	 </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"width="250" align="left"><?php echo $options['products_options_name']; ?></td>
                <td class="dataTableHeadingContent" width="50" align="center"><?php echo 'Prefix'; ?></td>
                <td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Price'; ?></td>
                <td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Sort Order'; ?></td>
                <td class="dataTableHeadingContent" width="20">&nbsp;</td>
                <td class="dataTableHeadingContent" width="70" align="center"><?php echo 'One Time Charge'; ?></td>
                <td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Weight Prefix'; ?></td>
                <td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Weight'; ?></td>
		        <!--<td class="dataTableHeadingContent" width="70" align="center"><?php //echo 'Quantity'; ?></td>-->
                <td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Units'; ?></td>
                <td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Units Price'; ?></td>
              </tr>
            
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                <td class="smallText"><?php echo tep_draw_checkbox_field('option'. '['.$rows . ']', $attributes['products_attributes_id'], $attributes['products_attributes_id'],'','onClick=javascript:change(); ') . '&nbsp;' . $values['products_options_values_name']; ?>&nbsp;</td>
                <td class="smallText" width="50" align="center"><?php echo tep_draw_input_field('prefix' .'['. $rows . ']', $attributes['price_prefix'], 'size="2"'); ?></td>
                <td class="smallText" width="70" align="center"><?php echo tep_draw_input_field('price' .'['.$rows . ']', $attributes['options_values_price'], 'size="7"'); ?></td>
                <td class="smallText" width="70" align="center"><?php echo tep_draw_input_field('products_options_sort_order' .'[ '.$rows . ']', $attributes['products_options_sort_order'], 'size="7"'); ?></td>
                <td class="smallText" width="20">&nbsp;</td>
                <td class="smallText" width="70" align="center"><?php echo tep_draw_input_field('product_attributes_one_time' .'[' .$rows . ']', $attributes['product_attributes_one_time'], 'size="2"'); ?></td>
                <td class="smallText" width="70" align="center"><?php echo tep_draw_input_field('products_attributes_weight_prefix' . '['.$rows . ']', $attributes['products_attributes_weight_prefix'], 'size="2"'); ?></td>
                <td class="smallText" width="70" align="center"><?php echo tep_draw_input_field('products_attributes_weight' .'['. $rows . ']', $attributes['products_attributes_weight'], 'size="7"'); ?></td>
              <!--  <td class="smallText" width="70" align="center"><?php //echo tep_draw_input_field('products_attributes_quantity[' . $rows . ']', $attributes['products_attributes_quantity'], 'size="7"'); ?><?PHP //$qcheck = $qcheck + $attributes['products_attributes_quantity']; ?></td>-->
			    <td class="smallText" width="70" align="center"><?php echo tep_draw_input_field('products_attributes_units' .'['. $rows . ']', $attributes['products_attributes_units'], 'size="7"'); ?></td>
                <td class="smallText" width="70" align="center"><?php echo tep_draw_input_field('products_attributes_units_price' .'['. $rows . ']', $attributes['products_attributes_units_price'], 'size="7"'); ?></td>
              </tr>
			  <tr>
                <td colspan="15"><?PHP if ($values['products_options_values_name']=="Not Required") echo tep_black_line(); ?></td>
             </tr>
<?php
      //}
      if ($header) {
?>			 
            </table></td>
<?php
      }
    //}
?>
          </tr>
		  <tr>
				<td ><?php echo tep_draw_separator('pixel_trans.gif', '24', '15');?></td>
		  </tr>
		  <tr>
		  	<td>
			<table style="display:none" cellpadding="5" cellspacing="0" border="1" width="50%" bgcolor="#ebebff">
			<tr>
				<td style="display:none" class="main" width="35%"><?php //echo tep_draw_pull_down_menu('attribute_list',$attribute_array,'',' size=10 style="width:100%" onkeyup="javascript:do_action(\'select\');" onkeydown="javascript:do_action(\'select\');" onClick="javascript:do_action(\'select\');"');
					//echo tep_draw_hidden_field('attributes_array');
				?></td>
			
				<td style="display:none" class="main" valign="middle" width="10%" align="center">&nbsp;<?php echo '<a href="javascript:do_action(\'add\');">' . tep_image_button('button_add.gif',IMAGE_ADD_ATTRIBUTE) . '</a><br><br><a href="javascript:do_action(\'edit\')">' . tep_image_button('button_update.gif',IMAGE_EDIT_ATTRIBUTE) . '<a><br><br><a href="javascript:do_action(\'delete\')">' . tep_image_button('button_delete.gif',IMAGE_DELETE_ATTRIBUTE) . '<a>';?>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2">
					<table style="display:none" cellpadding="2" cellspacing="2" border="0" >
		  <?php   
		  if(tep_db_num_rows($options_query)>0){
			 tep_db_data_seek($options_query,0);
			  while($options=tep_db_fetch_array($options_query)){?>
						<tr id="<?php echo 'options'.$options['products_options_id'];?>">
							<td class="main"><?php echo $options['products_options_name'];?></td>
							<td class="main"><?php echo tep_draw_pull_down_menu('option_values'.$options['products_options_id'],array(),'','  id="option_values'.$options['products_options_id'].'"');?></td>
							<td>&nbsp;</td>
						</tr>
	<?php	  } 
		}?>
	</table>
	</td></tr>
					<tr>
						<td colspan="3" class="main"><?php echo TEXT_QUANTITY.'&nbsp;'.tep_draw_input_field('quantity','','','',' size 10');?></td>
					</tr>	
			</table>
		</td>
	</tr>
<?php
// EOF: WebMakers.com Added: Draw Attribute Tables
/////////////////////////////////////////////////////////////////////
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
	  
        <td class="main" align="right"><?php 
		//preview 
		echo tep_image_submit('button_preview.gif', IMAGE_PREVIEW,'onclick = "javascript:do_quantity_check();" ');
		 ?><?php //echo tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) .  '&nbsp;&nbsp;'. tep_image_submit('button_save.gif', IMAGE_SAVE) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; 
		 
		 echo tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : getServerDate())) .   '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; 
		 echo tep_draw_hidden_field('attribute_quantity',$qcheck);
		// echo tep_draw_hidden_field('attrib_ordered',$attributes['products_attributes_ordered']);
		 ?></td>
      </tr>
    </table>
	<script language="javascript">
		change();
		init_page();
		</script>
	</form>
<?php
       if (HTML_AREA_WYSIWYG_DISABLE=='Enable') { 
		 for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
		    <script>initEditor('products_description[<?php echo $languages[$i]['id']; ?>]');</script>
		<?php  } 
		} ?>
<?php
  } elseif ($action == 'new_product_preview') {
  
    if (tep_not_null($_POST)) {
      $pInfo = new objectInfo($_POST);
      $products_name = $_POST['products_name'];
      $products_description = $_POST['products_description'];
	  $products_number = $_POST['products_number'];
      $color_code = $_POST['color_code'];
    } else {
      $product_query = tep_db_query("select p.products_id, pd.language_id, p.product_type,p.color_code, pd.products_name, pd.products_number, pd.products_description, pd.products_url, p.products_quantity,p.is_attributes, p.products_model, p.products_price, p.products_weight, p.products_sort_order,p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.manufacturers_id,p.products_image_1  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.products_id = '" . (int)$_GET['pID'] . "'");
      $product = tep_db_fetch_array($product_query);

      $pInfo = new objectInfo($product);
	  $products_image_name=$pInfo->products_image_1;
    }

    $form_action = ($_GET['pID']) ? 'update_product' : 'insert_product';
	
    echo tep_draw_form($form_action, FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

    $languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
        $pInfo->products_name = tep_get_products_name($pInfo->products_id, $languages[$i]['id']);
		 $pInfo->products_number = tep_get_products_number($pInfo->products_id, $languages[$i]['id']);
        $pInfo->products_description = tep_get_products_description($pInfo->products_id, $languages[$i]['id']);
        $pInfo->products_url = tep_get_products_url($pInfo->products_id, $languages[$i]['id']);
      } else {
        $pInfo->products_name = tep_db_prepare_input($products_name[$languages[$i]['id']]);
		$pInfo->products_number = tep_db_prepare_input($products_number[$languages[$i]['id']]);
        $pInfo->products_description = tep_db_prepare_input($products_description[$languages[$i]['id']]);
        $pInfo->products_url = tep_db_prepare_input($products_url[$languages[$i]['id']]);
      }
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $pInfo->products_name; ?></td>
            <td class="pageHeading" align="right"><?php echo $currencies->format($pInfo->products_price); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><?php echo $pInfo->products_description;
				
				?></td>
				<?php  
				
				// if(is_array($products_image_name)){
					// for ($icnt=1;$icnt<=count($products_image_name);$icnt++){
							// if ($products_image_name[$icnt]!=""){
								// echo '<td width="100" align="right">' . tep_product_small_image($products_image_name[$icnt],$products_title[$icnt]) . '<td>';
							// }
					// }
				// } else {
							// echo '<td width="100" align="right">' . tep_product_small_image($products_image_name,$products_title) . '<td>';
				// }
				?>
			</tr>
		</table>
		</td>
      </tr>
<?php 
      if ($pInfo->products_url) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php //echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->color_code); ?></td>
      </tr>
<?php
      }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
     // if ($pInfo->products_date_available > getServerDate()) {
?>
      <tr>
        <td align="center" class="smallText"><?php //echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->products_date_available)); ?></td>
      </tr>
<?php
      //} else {
?>
      <tr>
        <td align="center" class="smallText">
		
		<?php //echo sprintf(TEXT_PRODUCT_DATE_ADDED, $pInfo->color_code); ?></td>
      </tr>
<?php
     // }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
    }

    if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
      if (isset($_GET['origin'])) {
        $pos_params = strpos($_GET['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($_GET['origin'], 0, $pos_params);
          $back_url_params = substr($_GET['origin'], $pos_params + 1);
        } else {
          $back_url = $_GET['origin'];
          $back_url_params = '';
        }
      } else {
        $back_url = FILENAME_BACKUP_CATEGORIES;
        $back_url_params = 'cPath=' . $cPath . '&pID=' . $pInfo->products_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="right" class="smallText">
<?php
/////////////////////////////////////////////////////////////////////
// BOF: WebMakers.com Added: Original Code No longer used
// Code has been left in to show how additional products_descriptions could be added
if (false) {
/* Re-Post all POST'ed variables */
      reset($_POST);
      //FOREACH
      //while (list($key, $value) = each($_POST))
		foreach($_POST as $key => $value)  
	  {
        if (!is_array($_POST[$key])) {
          echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        }
      }
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        echo tep_draw_hidden_field('products_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_name[$languages[$i]['id']])));
		        echo tep_draw_hidden_field('products_number[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_number[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_description[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_url[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_url[$languages[$i]['id']])));
      }
} // false
// EOF: WebMakers.com Added: Original Code
/////////////////////////////////////////////////////////////////////
?>

<?php
/////////////////////////////////////////////////////////////////////
// BOF: WebMakers.com Added: Modified to include Attributes Code
/* Re-Post all POST'ed variables */
      reset($_POST);
      //FOREACH
      //while (list($key, $value) = each($_POST))
		foreach($_POST as $key => $value)  
	  {
	  	if (preg_match('products_image_',$key)) continue;
        if (is_array($value)) {
			//FOREACH
          while (list($k, $v) = each($value)) 
		  
		  {
            echo tep_draw_hidden_field($key . '[' . $k . ']', htmlspecialchars(stripslashes($v)));
          }
        } else {
          echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        }
      }
	  for ($icnt=1;$icnt<=count($products_image_name);$icnt++){
	  	echo tep_draw_hidden_field('products_image_' . $icnt,$products_image_name[$icnt]);
		//echo tep_draw_hidden_field('products_title_' . $icnt,$products_title[$icnt]);
	  }
	  echo tep_draw_hidden_field('preview_on',1);
	  
// EOF: WebMakers.com Added: Modified to include Attributes Code
/////////////////////////////////////////////////////////////////////

      echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';
	
      if ($_GET['pID']) {
        echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
      } else {
        echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </tr>
    </table></form>
<?php
    }
  } else {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, 1); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText" align="right">
<?php
    echo tep_draw_form('search', FILENAME_BACKUP_CATEGORIES, '', 'get');
    echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search');
    echo '</form>';
?>
                </td>
              </tr>
              <tr>
                <td class="smallText" align="right">
<?php
    echo tep_draw_form('goto', FILENAME_BACKUP_CATEGORIES, '', 'get');
    echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
    echo '</form>';
?>
                </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
			
			<table border="0" width="50%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
			
              </tr>
<?php
    $categories_count = 0;
    $rows = 0;
    if (isset($_GET['search'])) {
      $search = tep_db_prepare_input($_GET['search']);

      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.concert_date, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_id");
	  } else {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.concert_date, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by cd.categories_id");
   }
   

    while ($categories = tep_db_fetch_array($categories_query)) 
	{
	
      $categories_count++;
      $rows++;

// Get parent_id for subcategories if search
      if (isset($_GET['search'])) $cPath= $categories['parent_id'];

      if ((!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
        $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));

        $cInfo_array = array_merge($categories, $category_childs, $category_products);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_BACKUP_CATEGORIES, tep_get_path($categories['categories_id'])) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
               
			   <td class="dataTableContent"><?php 
				echo '<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, tep_get_path($categories['categories_id'])) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>
				&nbsp;<b>' . $categories['categories_name'] . ' : ' . $categories['concert_date'] . '</b>'; 
				?>
				</td>
                <td class="dataTableContent" align="center" colspan="2">&nbsp;</td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $products_count = 0;
    if (isset($_GET['search'])) {
      $products_query = tep_db_query("select p.products_id, pd.products_name, pd.products_number, p.products_sku,  p.product_type, p.section_id, p.parent_id, p.products_quantity,p.is_attributes, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_sort_order, p2c.categories_id,p.products_image_1 from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and p.product_type='P' and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and pd.products_name like '%" . tep_db_input($search) . "%'  and p.products_sku>'0' order by p.products_sort_order, pd.products_id");
    } else {
      $products_query = tep_db_query("select p.products_id, pd.products_name, pd.products_number, p.products_sku, p.product_type, p.section_id, p.parent_id, p.products_quantity,p.is_attributes, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_sort_order, p.products_image_1  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and p.product_type='P' and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' and p.products_sku>'0' order by p.products_sort_order, pd.products_id");
    }
    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;

// Get categories_id for product if search
      if (isset($_GET['search'])) $cPath = $products['categories_id'];

      if ( (!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['pID']) && ($_GET['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
// find out the rating average from customer reviews
       //$reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");
        //$reviews = tep_db_fetch_array($reviews_query);
        $pInfo_array = array_merge($products);
        $pInfo = new objectInfo($pInfo_array);
      }

      if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'">' . "\n";
      }
	  
	  if($products['products_quantity']==0 && $products['products_status']==1){
		  $warning='red';
	  }else{
		  $warning='';
	  }
	  
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a><span class="' . $warning . '"><b>&nbsp;' . $products['products_name']; ?></span></b></td>
                <td class="dataTableContent" align="center">
<?php
      
	  	 if ($products['products_sku'] == '1') 
		 {
		 //echo "&nbsp;&nbsp;RESET";
		 $r=15;
		 }
		 else{
			$r=10; 
		 }
	  
	  if ($products['products_status'] == '1') 
	  {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, $r, $r) 
		.'&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">'
		. tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>'.
		'&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'action=setflag&flag=8&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">
		' . tep_image(DIR_WS_IMAGES . 'icon_status_hidden_light.gif', IMAGE_ICON_STATUS_HIDDEN_LIGHT, 10, 10) . '</a>'
		;

      } elseif  ($products['products_status'] == '0') 
	  {
        echo '<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10)
		.'&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'action=setflag&flag=8&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">
		' . tep_image(DIR_WS_IMAGES . 'icon_status_hidden_light.gif', IMAGE_ICON_STATUS_HIDDEN_LIGHT, 10, 10) . '</a>';
      }
	  elseif ($products['products_status'] == '3') {
        echo '
		<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">'
		 . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) 
		 . '</a>';
		 // <a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">'
		// . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;'
		 // . tep_image(DIR_WS_IMAGES . 'icon_status_hidden.gif', IMAGE_ICON_STATUS_HIDDEN, 10, 10) ;
      }
		elseif ($products['products_status'] == '8') 
		{
		echo '
		<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">'
		. tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) 
		. '</a>&nbsp;&nbsp;' .'
		<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">'
		. tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;'
		. tep_image(DIR_WS_IMAGES . 'icon_status_hidden.gif', IMAGE_ICON_STATUS_HIDDEN, 10, 10) ;
		}
		
	
	  

?></td>
<td style="display:none"><?php echo '<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'action=pos_down&pos=' . $products['products_sort_order'] . '&pID=' . $products['products_id'] . '&cPath=' . $cPath) .  '">' . tep_image(DIR_WS_ICONS . '/order_down.gif',IMAGE_ORDER_DOWN,'16','16') . '</a>'; ?>
	<?php echo '<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'action=pos_up&pos=' . $products['products_sort_order'] . '&pID=' . $products['products_id'] . '&cPath=' . $cPath) .  '">' . tep_image(DIR_WS_ICONS . '/order_up.gif',IMAGE_ORDER_UP,'16','16') . '</a>'; ?></td>
<td  style="display:none" class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $cPath_back = '';
    if (sizeof($cPath_array) > 0) {
      for ($i=0, $n=sizeof($cPath_array)-1; $i<$n; $i++) {
        if (empty($cPath_back)) {
          $cPath_back .= $cPath_array[$i];
        } else {
          $cPath_back .= '_' . $cPath_array[$i];
        }
      }
    }

    $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
                    <td align="right" class="smallText"><?php if (sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!isset($_GET['search'])) 
						
					//echo '<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($action) {
      case 'new_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('newcategory', FILENAME_BACKUP_CATEGORIES, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
        }

        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
        //$contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"'));
		
       $contents[] = array('align' => 'center', 'text' => '<br>'  . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
	  
	    $contents[] = array('align' => 'center', 'text' => '<br>'  . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'edit_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_BACKUP_CATEGORIES, 'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_EDIT_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_get_category_name($cInfo->categories_id, $languages[$i]['id']));
        }

        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_name) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->categories_image . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
        $contents[] = array('text' => '<br>' . TEXT_EDIT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_BACKUP_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
        if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->products_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_BACKUP_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('products', FILENAME_BACKUP_CATEGORIES, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
        $contents[] = array('text' => '<br><b>' . $pInfo->products_name . '</b>');

        $product_categories_string = '';
        $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
        for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
          $category_path = '';
          for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
            $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
          }
          $category_path = substr($category_path, 0, -16);
          $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br>';
        }
        $product_categories_string = substr($product_categories_string, 0, -4);

        $contents[] = array('text' => '<br>' . $product_categories_string);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('products', FILENAME_BACKUP_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'copy_to':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

        $contents = array('form' => tep_draw_form('copy_to', FILENAME_BACKUP_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES . '<br>' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
// WebMakers.com Added: Attributes Copy
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
        $contents[] = array('text' => '<br>' . TEXT_COPY_ATTRIBUTES_ONLY);
        $contents[] = array('text' => '<br>' . TEXT_COPY_ATTRIBUTES . '<br>' . tep_draw_radio_field('copy_attributes', 'copy_attributes_yes', true) . ' ' . TEXT_COPY_ATTRIBUTES_YES . '<br>' . tep_draw_radio_field('copy_attributes', 'copy_attributes_no') . ' ' . TEXT_COPY_ATTRIBUTES_NO);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        $contents[] = array('align' => 'center', 'text' => '<br>' . ATTRIBUTES_NAMES_HELPER . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '10'));
        break;
// WebMakers.com Added: Copy Attributes Existing Product to another Existing Product
      case 'copy_product_attributes':
        $copy_attributes_delete_first='1';
        $copy_attributes_duplicates_skipped='1';
        $copy_attributes_duplicates_overwrite='0';

        if (DOWNLOAD_ENABLED == 'true') {
          $copy_attributes_include_downloads='1';
          $copy_attributes_include_filename='1';
        } else {
          $copy_attributes_include_downloads='0';
          $copy_attributes_include_filename='0';
        }

        $heading[] = array('text' => '<b>' . 'Copy Attributes to another product' . '</b>');
        $contents = array('form' => tep_draw_form('products', FILENAME_BACKUP_CATEGORIES, 'action=create_copy_product_attributes&cPath=' . $cPath . '&pID=' . $pInfo->products_id) . tep_draw_hidden_field('products_id', $pInfo->products_id) . tep_draw_hidden_field('products_name', $pInfo->products_name));
        $contents[] = array('text' => '<br>Copying Attributes from : <br>&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $pInfo->products_name . '</b>');
        $contents[] = array('text' => 'Copying Attributes to :' . tep_draw_pull_down_menu('copy_to_products_id', $make_copy_from_products_array));
        $contents[] = array('text' => '<br>Delete ALL Attributes and Downloads before copying&nbsp;' . tep_draw_checkbox_field('copy_attributes_delete_first',$copy_attributes_delete_first, 'size="2"'));
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
        $contents[] = array('text' => '<br>' . 'Otherwise ...');
        $contents[] = array('text' => 'Duplicate Attributes should be skipped&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_skipped',$copy_attributes_duplicates_skipped, 'size="2"'));
        $contents[] = array('text' => '&nbsp;&nbsp;&nbsp;Duplicate Attributes should be overwritten&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_overwrite',$copy_attributes_duplicates_overwrite, 'size="2"'));
        if (DOWNLOAD_ENABLED == 'true') {
          $contents[] = array('text' => '<br>Copy Attributes with Downloads&nbsp;' . tep_draw_checkbox_field('copy_attributes_include_downloads',$copy_attributes_include_downloads, 'size="2"'));
          // Not used at this time - download name copies if download attribute is copied
          // $contents[] = array('text' => '&nbsp;&nbsp;&nbsp;Include Download Filenames&nbsp;' . tep_draw_checkbox_field('copy_attributes_include_filename',$copy_attributes_include_filename, 'size="2"'));
        }
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . PRODUCT_NAMES_HELPER);
        if ($pID) {
          $contents[] = array('align' => 'center', 'text' => '<br>' . ATTRIBUTES_NAMES_HELPER);
        } else {
          $contents[] = array('align' => 'center', 'text' => '<br>Select a product for display');
        }
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', 'Copy Attribtues') . ' <a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
// WebMakers.com Added: Copy Attributes Existing Product to All Products in Category
      case 'copy_product_attributes_categories':
        $copy_attributes_delete_first='1';
        $copy_attributes_duplicates_skipped='1';
        $copy_attributes_duplicates_overwrite='0';

        if (DOWNLOAD_ENABLED == 'true') {
          $copy_attributes_include_downloads='1';
          $copy_attributes_include_filename='1';
        } else {
          $copy_attributes_include_downloads='0';
          $copy_attributes_include_filename='0';
        }

        $heading[] = array('text' => '<b>' . 'Copy Product Attributes to Category ...' . '</b>');
        $contents = array('form' => tep_draw_form('products', FILENAME_BACKUP_CATEGORIES, 'action=create_copy_product_attributes_categories&cPath=' . $cPath . '&cID=' . $cID . '&make_copy_from_products_id=' . $copy_from_products_id));
        $contents[] = array('text' => 'Copy Product Attributes from Product :<br><br>' . tep_draw_pull_down_menu('make_copy_from_products_id', $make_copy_from_products_array));
        $contents[] = array('text' => '<br>Copying to all products in Category <br>Name: <b>' . tep_get_category_name($cID, $languages_id) . '</b>');
        $contents[] = array('text' => '<br>Delete ALL Attributes and Downloads before copying&nbsp;' . tep_draw_checkbox_field('copy_attributes_delete_first',$copy_attributes_delete_first, 'size="2"'));
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
        $contents[] = array('text' => '<br>' . 'Otherwise ...');
        $contents[] = array('text' => 'Duplicate Attributes should be skipped&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_skipped',$copy_attributes_duplicates_skipped, 'size="2"'));
        $contents[] = array('text' => '&nbsp;&nbsp;&nbsp;Duplicate Attributes should be overwritten&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_overwrite',$copy_attributes_duplicates_overwrite, 'size="2"'));
        if (DOWNLOAD_ENABLED == 'true') {
          $contents[] = array('text' => '<br>Copy Attributes with Downloads&nbsp;' . tep_draw_checkbox_field('copy_attributes_include_downloads',$copy_attributes_include_downloads, 'size="2"'));
          // Not used at this time - download name copies if download attribute is copied
          // $contents[] = array('text' => '&nbsp;&nbsp;&nbsp;Include Download Filenames&nbsp;' . tep_draw_checkbox_field('copy_attributes_include_filename',$copy_attributes_include_filename, 'size="2"'));
        }
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . PRODUCT_NAMES_HELPER);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', 'Copy Attribtues') . ' <a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> ');//<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>
            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . format_date($cInfo->date_added));
            if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . format_date($cInfo->last_modified));
           // $contents[] = array('text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->categories_image);
            $contents[] = array('text' => '<br>' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br>' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
            //if ($cInfo->childs_count==0 and $cInfo->products_count >= 1) {
//// WebMakers.com Added: Copy Attributes Existing Product to All Existing Products in Category
//              $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
//			  if ($cInfo->categories_id) {
//                $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=copy_product_attributes_categories') . '">' . 'Copy Product Attributes to <br>ALL products in Category: ' . tep_get_category_name($cID, $languages_id) . '<br>' . tep_image_button('button_copy_to.gif', 'Copy Attributes') . '</a>');
//              } else {
//                $contents[] = array('align' => 'center', 'text' => '<br>Select a Category to copy attributes to');
//              }
//            }
          } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
            $heading[] = array('text' => '<b>' . tep_get_products_name($pInfo->products_id, $languages_id) . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=new_product') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>  ');//<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a>//<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>
            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . format_date($pInfo->products_date_added));
            if (tep_not_null($pInfo->products_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . format_date($pInfo->products_last_modified));
            if (getServerDate() < $pInfo->products_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . format_date($pInfo->products_date_available));
            $contents[] = array('text' => '<br>' . tep_product_small_image($pInfo->products_image_1, $pInfo->products_image_1) . '<br>' . $pInfo->products_image_1);
            $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' ' . $currencies->format($pInfo->products_price) . '<br>' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->products_quantity);
            //$contents[] = array('text' => '<br>' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
// WebMakers.com Added: Copy Attributes Existing Product to another Existing Product
           // $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
            //$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_BACKUP_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_product_attributes') . '">' . 'Products Attributes Copier:<br>' . tep_image_button('button_copy_to.gif', 'Copy Attributes') . '</a>');
           // if ($pID) {
              //$contents[] = array('align' => 'center', 'text' => '<br>' . ATTRIBUTES_NAMES_HELPER . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '10'));
           // } else {
              //$contents[] = array('align' => 'center', 'text' => '<br>Select a product to display attributes');
            //}
          }
        } else { // create category/product info
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

          $contents[] = array('text' => TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS);
        }
        break;
    }

    // if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) 
	// {
      // echo '            <td width="25%" valign="top">' . "\n";

      // $box = new box;
      // echo $box->infoBox($heading, $contents);

      // echo '            </td>' . "\n";
    // }
?>
          </tr>
        </table></td>
      </tr>
    </table>
<?php
  }
?>

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

<?php
/*
  
  
  Freeway eCommerce from Zac
  http://www.openfreeway.org
  Copyright 2007 ZacWare Pty. Ltd

  Released under the GNU General Public License
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
require('includes/application_top.php');

if(!defined('TEXT_SEATPLAN'))define('TEXT_SEATPLAN', 'Seat Plan Tables x 5');

$action=$FREQUEST->postvalue("action");
switch($action){
	case 'delete_items':
	$delete_items=&$FREQUEST->getRefValue("entries","POST");	
	$tables='';
	if (count($delete_items)>0){
		for ($icnt=0,$n=count($delete_items);$icnt<$n;$icnt++){
			switch($delete_items[$icnt]){
				case 'products':
					$tables.='categories,categories_description,products,products_attributes_download,products_description,products_to_categories,';
					break;
				case 'seatplan':
					$tables.='categories,categories_description,products,products_description,products_to_categories,';
					break;

			}
		}
		if ($tables!=''){
			$tables_splt=preg_split("/,/",$tables);
			for ($icnt=0,$n=count($tables_splt)-1;$icnt<$n;$icnt++){
				tep_db_query("DELETE from " . $tables_splt[$icnt]);
				//echo $tables_splt[$icnt] . '<br>';
			}
		}
	}
	tep_redirect(tep_href_link(FILENAME_SHOP_SALE_ITEMS,'mode=success'));
	break;
}

//check if order has any records 
$order_query=tep_db_query("SELECT * from " . TABLE_ORDERS . " limit 1");
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html;" charset="<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript">
	function validateForm(){
		var icnt,n;
		var element=document.sale_items.elements['entries[]'];
		var error="<?php echo tep_output_string(ERR_JS_SELECT_SALE);?>";
		for (icnt=0,n=element.length;icnt<n;icnt++){
			if (element[icnt].checked) {
				error='';
				break;
			}
		}
		if (error!=''){
			alert(error);
			return false;
		}
		if (confirm("Are you sure you want to delete the items")){
			return true;
		} else {
			return false;
		}
		
	}
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- Ajax Work Starts -->
<table border="0" width="100%" cellpadding="3" cellspacing="6">
<tr class="dataTableHeadingRow">
	<td width="100%" class="main"><b><?php echo HEADING_TITLE; ?></b></td>
</tr>
<?php if ($mode=="success") {?>
	<tr height="40">
		<Td class="main">
			<?php echo TEXT_DELETED;?>
		</Td>
	</tr>
<tr>
	<td><?php echo '<a href="' . tep_href_link(FILENAME_SHOP_SALE_ITEMS) . '">' . tep_image_button('button_continue.gif','Continue') . '</a>';?>
</tr>

<?php }  else {?>
<tr height="40">
	<Td class="main">
	<b><?php echo TEXT_SELECT_SALE;?></b>
	</Td>
</tr>
<?php echo tep_draw_form('sale_items',FILENAME_SHOP_SALE_ITEMS,'','post','onSubmit="javascript:return validateForm();"');?>
<input type="hidden" name="action" value="delete_items">
<tr>
	<td class="smallText"><?php echo TEXT_SELECT_SALE_DESC;?></td>
</tr>
<tr>
	<td>
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td class="main"><?php echo tep_draw_checkbox_field('entries[]','products',false,'') . TEXT_DELETE_PRODUCTS;?></td>
		</tr>
		<tr>
			<td class="main" style="display:none"><?php echo tep_draw_checkbox_field('entries[]','seatplan',false,'') . TEXT_SEATPLAN;?></td>
		</tr>
		<tr>
			<td height="30"></td>
		</tr>
		<?php if (tep_db_num_rows($order_query)>1){ ?>
		<tr>
			<td class="errorText"><?php echo ERR_SELECT_INFO;?>
			</td>
		</tr>
		<?php } else { ?>
		<tr>
			<td><?php echo tep_image_submit('button_delete.gif',TEXT_DELETE);?>
		</tr>
		<?php } ?>
	</table>
	</td>
</tr>
</form>
<?php } ?>
</table>
<!-- body_text_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
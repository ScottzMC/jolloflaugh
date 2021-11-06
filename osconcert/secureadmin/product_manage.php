<?php
ob_start();
// Set flag that this is a parent file

	define( '_FEXEC', 1 );
	require('includes/application_top.php');
	
	define ('HEADING_TITLE','Product Manage');
	?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<style type="text/css">
	#pup {position:absolute; visibility:hidden; z-index:200; width:130; }
.red {
	color: #F00;
}
</style>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<?php
require('includes/configure.php');
$con = tep_db_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
if (!$con)
{
	die('Could not connect: ' . tep_db_error());
}


//tep_db_select_db(DB_DATABASE, $con);

$task = $_REQUEST["task"];

switch ($task){
	case "edit" :    	
    	editproduct();
		break;	
	case "edit_m" :    	
    	editproduct1();
		break;	
    case "success" : 
		Iform("<span class='red'>Products Set Successfully!!!</span>");
    	break;
	default:		
    	Iform();
    	break;
}

function editproduct(){
//echo "<pre>"; print_r($_POST);exit; 

	$qty_cnt = "Select count(*) as count from products_to_categories where categories_id =".$_POST["rowid"];
	$res = tep_db_query($qty_cnt);
	$ctn = tep_db_fetch_assoc($res);
	//print_r($ctn['count']);exit;	
	if($ctn['count'] > 0){
	
		$query = "SELECT products_id FROM `products_to_categories` where categories_id =".$_POST["rowid"];
		$res = tep_db_query($query);	
		$arr_image = array();
		$i=0;	
		while ($arr = tep_db_fetch_array($res)){
			$arr_image[$i]["products_id"] = $arr["products_id"];
			$i++;
		}
		//echo "<pre>";print_r($arr_image);
	}
	
	if(count($arr_image) > 0){
		for($j=0;$j<count($arr_image);$j++){	
		
		$sett = "";	
		
			if(isset($_POST["pro_qty"]) and $_POST["pro_qty"] != "" ){
				$sett .= "products_quantity = ".$_POST["pro_qty"].",";
			}		
			if(isset($_POST["pro_price"]) and $_POST["pro_price"] != ""){
				$sett .= "products_price ='".$_POST["pro_price"]."',";
			}
			if(isset($_POST["color"]) and $_POST["color"] != ""){
				$sett .= "color_code ='".$_POST["color"]."',";
			}
			if(isset($_POST["pro_state"]) and $_POST["pro_state"] != ""){
				$sett .= "products_status =".$_POST["pro_state"];
			}	
			//echo $sett;
			//echo $sett = (substr($sett,-1) == ',') ? substr($sett, 0, -1) : $sett;			
			//echo $sett = substr($sett,0,strlen($sett)-1); 
			//echo $last = $sett[strlen($sett)-1];
			$rest = substr($sett, -1); 
			//echo $sett = substr($sett, 0, -1); 
			//exit;
			
			
			if($rest == ","){
				//echo "sdf";exit;
				$sett = substr($sett, 0, -1); 
			}					
			
			$qry_update = "Update products set ".$sett." where parent_id = ".$_POST["secid"]." And products_sku = 1 And products_id = ".$arr_image[$j]["products_id"];				
			
			if(!tep_db_query($qry_update))
			{
				echo "<span class='red'>Something is wrong. Please try again!!!</span>";
				
			}
			
		}
	
	}
	
	header("Location:" . FILENAME_PRODUCT_MANAGE . "?task=success");	

}

function editproduct1(){
//echo "<pre>"; print_r($_POST);exit; 
	$start = $_POST["rowid"] -1;
	$end1 = $_POST["rowid1"] + 1;

	$qty_cnt = "Select count(*) as count from products_to_categories where categories_id > ".$start." And categories_id < ".$end1;
	
	$res = tep_db_query($qty_cnt);
	$ctn = tep_db_fetch_assoc($res);
	//print_r($ctn['count']);exit;	
	if($ctn['count'] > 0){
	
		$query = "SELECT products_id FROM `products_to_categories` where categories_id > ".$start." And categories_id < ".$end1;
		$res = tep_db_query($query);	
		$arr_image = array();
		$i=0;	
		while ($arr = tep_db_fetch_array($res)){
			$arr_image[$i]["products_id"] = $arr["products_id"];
			$i++;
		}
		//echo "<pre>";print_r($arr_image);
	}
	
	if(count($arr_image) > 0){
		for($j=0;$j<count($arr_image);$j++){	
		
		$sett = "";	
		
			if(isset($_POST["pro_qty"]) and $_POST["pro_qty"] != "" ){
				$sett .= "products_quantity = ".$_POST["pro_qty"].",";
			}		
			if(isset($_POST["pro_price"]) and $_POST["pro_price"] != ""){
				$sett .= "products_price ='".$_POST["pro_price"]."',";
			}
			if(isset($_POST["color"]) and $_POST["color"] != ""){
				$sett .= "color_code ='".$_POST["color"]."',";
			}
			if(isset($_POST["pro_state"]) and $_POST["pro_state"] != ""){
				$sett .= "products_status =".$_POST["pro_state"];
			}	
			//echo $sett;
			//echo $sett = (substr($sett,-1) == ',') ? substr($sett, 0, -1) : $sett;			
			//echo $sett = substr($sett,0,strlen($sett)-1); 
			//echo $last = $sett[strlen($sett)-1];
			$rest = substr($sett, -1); 
			//echo $sett = substr($sett, 0, -1); 
			//exit;
			
			
			if($rest == ","){
				//echo "sdf";exit;
				$sett = substr($sett, 0, -1); 
			}					
			
			$qry_update = "Update products set ".$sett." where parent_id = ".$_POST["secid"]." And products_sku = 1 And products_id = ".$arr_image[$j]["products_id"];				
			
			if(!tep_db_query($qry_update))
			{
				echo "<span class='red'>Something is wrong. Please try again!!!</span>";
				
				
			}
			//echo "<br>";
			
		}
	
	}
//	exit;
	// header( 'Location:'.HTTP_SERVER."/admin/product_manage.php?task=success") ;	
	header("Location:" . FILENAME_PRODUCT_MANAGE . "");
}


function Iform($msg = ""){	
	
?>
<body>
<center>
  <h2></span><?php echo TEXT_ADVANCED; ?></h2>
  <h2><?php echo TEXT_PRODUCT_MANAGE; ?></h2>
</center>

		<form action="product_manage.php?task=edit" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" onSubmit="return frmValid();">
			<table width="100%" align="center" border="0" cellpadding="3" cellspacing="3">			
				<tr>
					<td align="right"><strong><?php echo TEXT_SECTION_ID; ?></strong></td>
					<td align="left"><input type="text" id="secid" name="secid" value=""  size="20" />
					<?php echo TEXT_USUALLY; ?></td>
				</tr>
				<tr>
					<td align="right"><strong><?php echo TEXT_ROW_ID; ?></strong></td>
					<td align="left"><input type="text" id="rowid" name="rowid" value=""  size="20" /> 
					<?php echo TEXT_THE_ROW; ?></td>
				</tr>
							<tr>
					<td align="right"><strong><?php echo TEXT_PRODUCT_PRICE; ?></strong></td>
					<td align="left"><input type="text" id="pro_price" name="pro_price" value=""  size="20" /> 
					<?php echo TEXT_WITHOUT_CURRENCY; ?></td>
				</tr>
				<tr>
					<td align="right"><strong><?php echo TEXT_COLOR; ?></strong></td>
					<td align="left"><input type="text" id="color" name="color" value=""  size="20" /> 
					(color banding - red,blue,green,yellow,fuchsia,salmon,teal,orange,palegreen,skyblue and thistle)</td>
				</tr>
				<tr>
					<td align="right"><strong><?php echo TEXT_PRODUCTS_QUANTITY; ?></strong></td>
					<td align="left"><input type="text" id="pro_qty" name="pro_qty" value=""  size="20" /> 
					<?php echo TEXT_ACTIVE; ?></td>
				</tr>
				<tr>
					<td align="right"><strong><?php echo TEXT_PRODUCTS_STATUS; ?></strong></td>
					<td align="left"><input type="text" id="pro_state" name="pro_state" value=""  size="20" /> <?php echo TEXT_ACTIVE; ?></td>
				</tr>
					
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" name="submit" value="<?php echo TEXT_SAVE; ?>"></td>
				</tr>
			</table>			    
		</form>
		<?php if(isset($msg) or $msg != "" ) { ?>
				<center><h1><?php echo $msg; ?></h1></center>
		<?php } ?>
		<br />
		<center><h2><?php echo TEXT_PRODUCT_MANAGER_MULTI; ?></h2></center>
		<form action="product_manage.php?task=edit_m" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" onSubmit="return frmValid();">
			<table width="100%" align="center" border="0" cellpadding="3" cellspacing="3">			
				<tr>
					<td align="right"><strong><?php echo TEXT_SECTION_ID; ?></strong></td>
					<td align="left"><input type="text" id="secid" name="secid" value=""  size="20" /></td>
				</tr>
				<tr>
					<td align="right"><strong><?php echo TEXT_ROW_ID; ?></strong></td>
					<td align="left"><input type="text" id="rowid" name="rowid" value=""  size="20" /> To <input type="text" id="rowid1" name="rowid1" value=""  size="20" /> </td>
				</tr>
							<tr>
					<td align="right"><strong><?php echo TEXT_PRODUCT_PRICE; ?></strong></td>
					<td align="left"><input type="text" id="pro_price" name="pro_price" value=""  size="20" /></td>
				</tr>
				<tr>
					<td align="right"><strong><?php echo TEXT_COLOR; ?></strong></td>
					<td align="left"><input type="text" id="color" name="color" value=""  size="20" /></td>
				</tr>
				<tr>
					<td align="right"><strong><?php echo TEXT_PRODUCTS_QUANTITY; ?></strong></td>
					<td align="left"><input type="text" id="pro_qty" name="pro_qty" value=""  size="20" /><?php echo TEXT_ACTIVE; ?></td>
				</tr>
				<tr>
					<td align="right"><strong><?php echo TEXT_PRODUCTS_STATUS; ?></strong></td>
					<td align="left"><input type="text" id="pro_state" name="pro_state" value=""  size="20" /> <?php echo TEXT_ACTIVE; ?></td>
				</tr>
					
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" name="submit" value="<?php echo TEXT_SAVE; ?>"></td>
				</tr>
			</table>			    
		</form>
		
		<?php if(isset($msg) or $msg != "" ) { ?>
				<center><h1><?php echo $msg; ?></h1></center>
		<?php } ?>
		
<?php } ?>

<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
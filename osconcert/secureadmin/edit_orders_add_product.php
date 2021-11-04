<?php
/*
  $Id: edit_orders_add_product.php v5.0.5 08/27/2007 djmonkey1 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License

  For Order Editor support or to post bug reports, feature requests, etc, please visit the Order Editor support thread:
  http://forums.oscommerce.com/index.php?showtopic=54032
  
*/
// Set flag that this is a parent file
define('_FEXEC', 1);
  require('includes/application_top.php');

  // include the appropriate functions & classes
  include('order_editor/functions.php');
  include('order_editor/cart.php');
  include('order_editor/order.php');
  include(DIR_WS_LANGUAGES . $FSESSION->language.'/'. FILENAME_ORDERS_EDIT);

  // Include currencies class
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = tep_db_prepare_input((int)$_GET['oID']);
  $order = new manualOrder($oID);

  // Setup variables
  $step = ((isset($_POST['step'])) ? (int)$_POST['step'] : 1);
  $add_product_categories_id = ((isset($_POST['add_product_categories_id'])) ? (int)$_POST['add_product_categories_id'] : '');
  $add_product_products_id = ((isset($_POST['add_product_products_id'])) ? (int)$_POST['add_product_products_id'] : 0);
  
  //Graeme get the product type here
  	if(isset($_POST['add_product_products_id']) && (int)$_POST['add_product_products_id'] !== 0){
			        $product_query = tep_db_query("select p.products_model, p.products_quantity, p.product_type, p.products_price, pd.products_name, p.products_tax_class_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id = p.products_id where p.products_id = '" . (int)$add_product_products_id . "' and pd.language_id = '" .(int)$FSESSION->languages_id  . "'");
        //EOF Added languageid
        $product = tep_db_fetch_array($product_query);
		$the_product_type=$product['product_type'];
		$the_product_quantity=$product['products_quantity'];																						}

  // $_GET['action'] switch
  if (isset($_GET['action'])) {
    switch ($_GET['action']) {
    
    ////
    // Add a product to the virtual cart
      case 'add_product':
        if ($step != 5) break;
        
        $AddedOptionsPrice = 0;
        
        $product_query = tep_db_query("select p.products_model, p.products_quantity, p.product_mode, p.product_type,p.products_sku, p.products_price, pd.products_name, pd.products_number,p.products_tax_class_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id = p.products_id where p.products_id = '" . (int)$add_product_products_id . "' and pd.language_id = '" .(int)$FSESSION->languages_id  . "'");
        //EOF Added languageid
        $product = tep_db_fetch_array($product_query);
        $country_id = oe_get_country_id($order->delivery["country"]);
        $zone_id = oe_get_zone_id($country_id, $order->delivery['state']);
        $products_tax = tep_get_tax_rate($product['products_tax_class_id'], $country_id, $zone_id);
		$product_type = $product['product_type'];//added Graeme
		$product_mode = $product['product_mode'];//added Gord
		//catchall stop buying too many
		if($product['products_quantity'] < $_POST['add_product_quantity']){
		echo '<div style="text-align:center; background-color:#FFDFAA; color:#D41F55">'.EDIT_ORDERS_TOO_MANY.'</div>';
		break;
		}
		
		
			// 2.1.3  Pull specials price from db if there is an active offer
			$special_price = tep_db_query("
			SELECT specials_new_products_price 
			FROM " . TABLE_SPECIALS . " 
			WHERE products_id =". $add_product_products_id . " 
			AND status");
			$new_price = tep_db_fetch_array($special_price);
			
			if ($new_price) 
			{ $product['products_price'] = $new_price['specials_new_products_price']; }
			

		//Graeme May 2012 - we need to get the order_status to add to orders_products_status
		$check_status_query = tep_db_query("select  o.orders_status from " . TABLE_ORDERS . " o  where  o.orders_id = '" . (int)$oID . "'");
		$check_status = tep_db_fetch_array($check_status_query);
		//OK so now the order status is $check_status['orders_status']
		
		//Feb 2013 - get venue,date,time etc
				$this_product_id=$add_product_products_id;


		$id=$this_product_id;
		require_once(DIR_WS_INCLUDES . 'functions/categories_lookup.php');
		#####################################################
        // call the new function
        //$type = $products[$i]['product_type'];
        
        list($heading_name, $heading_venue,  $heading_date, $heading_time) = categories_lookup();

		######################################################


	
        $sql_data_array = array('orders_id' => tep_db_prepare_input($oID),
                                'products_id' => tep_db_prepare_input($add_product_products_id),
                                'products_model' => tep_db_prepare_input($product['products_model']),
                                'products_name' => tep_db_prepare_input($product['products_name']),
                                'products_price' => tep_db_prepare_input($product['products_price']),
                                'final_price' => tep_db_prepare_input(($product['products_price'] + $AddedOptionsPrice)),
                                'products_tax' => tep_db_prepare_input($products_tax),
								'products_type' => 'P',
								'products_sku' => tep_db_prepare_input($product['products_sku']),
								'support_packs_type' => tep_db_prepare_input($product_type),
								'orders_products_status'=>$check_status['orders_status'],//graeme May 2012
								'events_type'=> tep_db_prepare_input($product_type),
								'categories_name'=> $heading_name,
								'concert_venue'=> $heading_venue,
								'concert_date'=> $heading_date,
								'concert_time'=> $heading_time,
								'events_id'=> '',
                                'products_quantity' => tep_db_prepare_input($_POST['add_product_quantity']));
								
        tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
        $new_product_id = tep_db_insert_id();
        //osconcert
		  $product_comments = $_POST['add_product_quantity'] . " x " . $product['products_model'] ." ". $product['products_name'] . " added to order ";
		  order_changed ( tep_db_input($oID), $check_status['orders_status'], $product_comments, $_SESSION['login_first_name'] . " " . $_SESSION['login_last_name']);
		  
			
	function ga_update_add($product_id, $prod_quantity, $prod_type)
	{
	$result = array();
	//take the product id, find the cPath, explode it and then run a check to see if any cat has a GA setting
	$ga_path_array = array();
	$ga_path_array = explode('_', ga_get_product_path($product_id));//n.b. tep_get_product path requires products_status=1 
	//$ga_path_array = array_reverse($ga_path_array);
	$max = sizeof($ga_path_array);

		if ($max >0)
		{
			for ($i=0; $i<$max; $i++) 
			{
			##
			// reduce ga_amount by this product's quantity

			 $category_ga_query = tep_db_query("select categories_GA,categories_quantity_remaining from " . TABLE_CATEGORIES . " where categories_id = '" . $ga_path_array[$i]. "' limit 1");
				 if (tep_db_num_rows($category_ga_query)) 
				 { 
				  $category_ga = tep_db_fetch_array($category_ga_query);

							
						if(($prod_type=='G' && $category_ga['categories_GA'] == 1))
						{
											 
						$quantity_left_ga=(($category_ga['categories_quantity_remaining'])-($prod_quantity));
						 //update the master quantity
							tep_db_query("update " . TABLE_CATEGORIES . " set categories_quantity_remaining = '" .$quantity_left_ga . "' where categories_id = '" . $ga_path_array[$i] . "'");
						}//end elseif
								
						elseif(($prod_type=='F' && $category_ga['categories_GA'] == 1))
						{
											 
						$quantity_left_ga=(($category_ga['categories_quantity_remaining'])-FAMILY_TICKET_QTY);
						 //update the master quantity
							tep_db_query("update " . TABLE_CATEGORIES . " set categories_quantity_remaining = '" .$quantity_left_ga . "' where categories_id = '" . $ga_path_array[$i] . "'");
						}//end elseif
								
								
					}//end tep_db_num_rows
			}
	   }
	}
		#######################
		// Update inventory Quantity
			// This is only done if store is set up to use stock
			if (STOCK_LIMITED == 'true')
			{
				if($the_product_type=='F')
				{
				$family_ticket=$_POST['add_product_quantity']*FAMILY_TICKET_QTY;
				}else{
				$family_ticket=$_POST['add_product_quantity'];
				}
				tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET
				products_quantity = products_quantity - " . $family_ticket . " 
				WHERE products_id = '" . $_POST['add_product_products_id'] . "'");
				
			//	GA Master - id, quantity to update, 
			ga_update_add((int)$_POST['add_product_products_id'], $_POST['add_product_quantity'], tep_db_prepare_input($product_type));

			}
			// Update products_ordered info
			tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
			products_ordered = products_ordered + " . $_POST['add_product_quantity'] . "
			WHERE products_id = '" . $_POST['add_product_products_id'] . "'");
        	//check status
			$stock_query = tep_db_query("select products_image_1,products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . $_POST['add_product_products_id']. "'");
			if (tep_db_num_rows($stock_query) > 0) {
				$stock_values = tep_db_fetch_array($stock_query);
				$stock_left = $stock_values['products_quantity'] ;
		
				if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
					tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . $_POST['add_product_products_id'] . "'");
				}
		}
        // Unset selected product & category
        $add_product_categories_id = 0;
        $add_product_products_id = 0;
        
			 
		tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $oID . '&step=1&submitForm=yes'));
        
		break;
    }
  }

 
////
// Generate product list based on chosen category or search keywords
  $not_found = true;
  if (isset($_POST['search'])) { 
    $search_array = explode(" ", $_POST['product_search']);
    $search_array = oe_clean_SQL_keywords($search_array);
    if (sizeof($search_array) <= 1) {
      $search_fields = array('p.products_id', 'p.products_price', 'p.products_model', 'pd.products_name');
      $product_search = oe_generate_search_SQL($search_array, $search_fields);
    } else {
      $search_fields = array('pd.products_name');
      $product_search = oe_generate_search_SQL($search_array, $search_fields, 'AND');
    }
  
    $products_query = tep_db_query("select p.products_id, p.products_price, p.products_model, pd.products_name from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on (p.products_id = pd.products_id) where pd.language_id = '" . (int)$FSESSION->languages_id  . "' and (" . $product_search . ") order by pd.products_name");
    $not_found = ((tep_db_num_rows($products_query)) ? false : true);
  } 
  
  if (!isset($_POST['search'])) {
    $product_search = " where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" .   (int)$FSESSION->languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id ";
    
    $_GET['inc_subcat'] = '1';
    if ($_GET['inc_subcat'] == '1') {
      $subcategories_array = array();
      oe_get_subcategories($subcategories_array, $add_product_categories_id);
      $product_search .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and (p2c.categories_id = '" . (int)$add_product_categories_id . "'";
      for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
        $product_search .= " or p2c.categories_id = '" . $subcategories_array[$i] . "'";
      }
      $product_search .= ")";
    } else {
      $product_search .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = '" .   (int)$FSESSION->languages_id  . "' and p2c.categories_id = '" . (int)$add_product_categories_id . "'";
    }

    $products_query = tep_db_query("select distinct p.products_id, p.products_price, p.products_model, p.product_type, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c " . $product_search . " order by pd.products_name");
    $not_found = ((tep_db_num_rows($products_query)) ? false : true);
  }

  $category_array = array(array('id' => '', 'text' => TEXT_SELECT_CATEGORY),
                          array('id' => '0', 'text' => TEXT_ALL_CATEGORIES));
  
  if (($step > 1) && (!$not_found)) {
    $product_array = array(array('id' => 0, 'text' => TEXT_SELECT_PRODUCT));
    while($products = tep_db_fetch_array($products_query)) {
      $product_array[] = array('id' => $products['products_id'],
                               'text' =>  $products['products_name'] . ' (' . $products['products_model'] . ')' . ':&nbsp;' . $currencies->format($products['products_price'], true, $order->info['currency'], $order->info['currency_value']));
    }
  }

  // $has_attributes = false;
  // $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$add_product_products_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$FSESSION->languages_id  . "'");
  // $products_attributes = tep_db_fetch_array($products_attributes_query);
  // if ($products_attributes['total'] > 0) $has_attributes = true;   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php if ( (isset($_GET['submitForm'])) && ($_GET['submitForm'] == 'yes') ) {
        echo '<script language="javascript" type="text/javascript"><!--' . "\n" .
             '  window.opener.document.edit_order.subaction.value = "add_product";' . "\n" . 
             '  window.opener.document.edit_order.submit();' . "\n" .
             '//--></script>';
			 }
	?>
<script language="javascript">
	var currentMenu;
	var cEvent;
	var currentPath;
	var prev_focus_id;
	var IE = document.all?true:false;
	function show_menu(path){
		//hide_cpopup(true);
		if (currentMenu){
			hide_popup(true);
		}
		show_pop=document.getElementById("pop_"+path);
		
		path_splt=path.split("_");
		currentMenu=path_splt[1];
		currentPath=path;
		var ele1=document.getElementById("menu_l2_"+currentMenu+"_1");
		var ele2=document.getElementById("menu_l2_"+currentMenu+"_2");
		var ele3=document.getElementById("menu_l2_"+currentMenu+"_3");
		var anc=document.getElementById("menu_l2_"+currentMenu+"_a");
		var tclass="";
		var tclass1="";
		ele1.prev_class=ele1.className;
		ele2.prev_class=ele2.className;
		ele3.prev_class=ele3.className;
		ele1.prev_text=ele1.innerHTML;
		if (ele1.innerHTML=="+"){
			tclass="menu_l2_hover";
			tclass1="menu_l2_hover_m";
		} else {
			tclass="menu_l2_select";
			tclass1="menu_l2_hover_nom";
		}
		ele1.innerHTML='&nbsp;';
		ele1.className=tclass+"_left";
		ele2.className=tclass+"_right";
		ele3.className=tclass1;
		anc.prev_class=anc.className;
		anc.className="menu_l2_select_hover";
		
		if (!show_pop) return;
		show_popup(show_pop);
		
		if (!IE) document.captureEvents(Event.MOUSEMOVE);
	    document.onmousemove=check_menu;
	}
	function check_menu(e){
		if(typeof(currentMenu)!="undefined"){
			var con=document.getElementById("menu_l2_"+currentMenu+"_con");
			if (IE) { // grab the x-y pos.s if browser is IE
				tempX = event.clientX + document.body.scrollLeft;
				tempY = event.clientY + document.body.scrollTop;
			}
			else {  // grab the x-y pos.s if browser is NS
				tempX = e.pageX;
				tempY = e.pageY;
			}
			//document.getElementById("test_element").innerHTML=tempY<=con.pos_y1;
			if (!(tempX>=con.pos_x && tempX<=con.pos_x1 && tempY>=con.pos_y && tempY<=con.pos_y1)){
				hide_popup(true);
			}
		}
	}
	function hide_menu(path){
		path_splt=path.split("_");
		currentMenu=path_splt[1];
		show_pop=document.getElementById("pop_"+path);
		if (!show_pop) return;
		hide_popup(true);
	}
	function show_popup(){ 
		if(typeof(currentMenu)!="undefined"){
			show_pop=document.getElementById("pop_"+currentPath);
			if (!show_pop) return;
			var con=document.getElementById("menu_l2_"+currentMenu+"_con");
			var ele2=document.getElementById("menu_l2_"+currentMenu+"_2");		
			if (!con.pos_x){
				pos=getAnchorPosition("menu_l2_"+currentMenu+"_con");
				var last=document.getElementById("menu_l2_"+currentMenu+"_last");
				if (last){
					if (!ele2.pos_x) {
						var pos1=getAnchorPosition("menu_l2_"+currentMenu+"_2");
						ele2.pos_x=pos1.x;
					}
					con.pos_x=(parseInt(ele2.pos_x)+18)-parseInt(show_pop.style.width);
				} else {
					con.pos_x=pos.x;
				}
				con.pos_y=pos.y;
				con.pos_x1=con.pos_x+parseInt(show_pop.style.width)+2;
				con.pos_y1=con.pos_y+parseInt(show_pop.style.height)+40;
			}
			//show_pop.style.le
			show_pop.style.left=con.pos_x+2;
			show_pop.style.top=con.pos_y+22;
			show_pop.style.display="";
		}
	}
	function hide_popup(check_flag){
		show_pop=document.getElementById("pop_"+currentPath);
		if (!check_flag && show_pop) return;
		
		if (show_pop){
			document.onmousemove='';
			if (!IE) document.releaseEvents(Event.MOUSEMOVE);
			show_pop.style.display="none";
		}
		if(typeof(currentMenu)!="undefined"){
			var ele1=document.getElementById("menu_l2_"+currentMenu+"_1");
			var ele2=document.getElementById("menu_l2_"+currentMenu+"_2");
			var ele3=document.getElementById("menu_l2_"+currentMenu+"_3");
			var anc=document.getElementById("menu_l2_"+currentMenu+"_a");
			ele1.className=ele1.prev_class;
			ele2.className=ele2.prev_class;
			ele3.className=ele3.prev_class;
			ele1.innerHTML=ele1.prev_text;
		}
		anc.className=anc.prev_class;
		currentMenu=0;
		currentPath="";
		return false;
	}
function getAnchorPosition(anchorname) {
	// This function will return an Object with x and y properties
	var useWindow=false;
	var coordinates=new Object();
	var x=0,y=0;
	// Browser capability sniffing
	var use_gebi=false, use_css=false, use_layers=false;
	if (document.getElementById) { use_gebi=true; }
	else if (document.all) { use_css=true; }
	else if (document.layers) { use_layers=true; }
	// Logic to find position
 	if (use_gebi && document.all) {
		x=AnchorPosition_getPageOffsetLeft(document.all[anchorname]);
		y=AnchorPosition_getPageOffsetTop(document.all[anchorname]);
		}
	else if (use_gebi) {
		var o=document.getElementById(anchorname);
		x=AnchorPosition_getPageOffsetLeft(o);
		y=AnchorPosition_getPageOffsetTop(o);
		}
 	else if (use_css) {
		x=AnchorPosition_getPageOffsetLeft(document.all[anchorname]);
		y=AnchorPosition_getPageOffsetTop(document.all[anchorname]);
		}
	else {
		coordinates.x=0; coordinates.y=0; return coordinates;
		}
	coordinates.x=x;
	coordinates.y=y;
	return coordinates;
	}
// Functions for IE to get position of an object
function AnchorPosition_getPageOffsetLeft (el) {
	var ol=el.offsetLeft;
	while ((el=el.offsetParent) != null) { ol += el.offsetLeft; }
	return ol;
	}
function AnchorPosition_getPageOffsetTop (el) {
	var ot=el.offsetTop;
	while((el=el.offsetParent) != null) { ot += el.offsetTop; }
	return ot;
	}

	function toggle_left_panel(panelid){
		var element=document.getElementById("panel_"+panelid);
		var img=document.getElementById("img_"+panelid);
		if (element.style.display=="none"){
			element.style.display="";
			img.src="images/template/ico_arrow_up.gif";
		} else {
			element.style.display="none";
			img.src="images/template/ico_arrow_down.gif";
		}
	}	
	function toggle_panel(title,funname,hide_prev,expand){				
		var panel=document.getElementById("panel_"+title);
		if(panel){
			if (panel.style.display=="none"){
				panel.style.display="";
				if (title=='Description' && !panel.editor){
					editor_init();
					panel.editor=true;
				}
			} else panel.style.display="none";
		}
		if(hide_prev==true)
		{
			if(prev_focus_id!="" && prev_focus_id!=title)
			{
				if(document.getElementById("img_"+prev_focus_id))
				{
					//var img=document.getElementById("img_"+prev_focus_id);
					var panel=document.getElementById("panel_"+prev_focus_id);
					panel.style.display="none";
					//img.src="images/template/panel_down.gif";
				}
			}
			prev_focus_id=title;
		}
		if(typeof(display_category) != "undefined"){
			panel_expand="panel_" + title;
			disp(panel);
		}
		if(parseInt(expand)>0)
			panel_expand_id = expand;
		//if(funname==true){	
		if(title=='Attribute_Inventory_control'){
			doaction();
		}
	}
	function toggle_panel1(title,funname,hide_prev,expand){				
		var img=document.getElementById("img_"+title);
		var panel=document.getElementById("panel_"+title);
		if (panel.style.display=="none"){
			img.src="images/template/panel_up.gif";
			panel.style.display="";
		} else {
			panel.style.display="none";
			img.src="images/template/panel_down.gif";
		}
		if(hide_prev==true)
		{
			if(prev_focus_id!="" && prev_focus_id!=title)
			{
				if(document.getElementById("img_"+prev_focus_id))
				{
					var img=document.getElementById("img_"+prev_focus_id);
					var panel=document.getElementById("panel_"+prev_focus_id);
					panel.style.display="none";
					img.src="images/template/panel_down.gif";
				}
			}
			prev_focus_id=title;
		}
		if(typeof(display_category) != "undefined"){
			panel_expand="panel_" + title;
			disp(panel);
		}
		if(parseInt(expand)>0)
			panel_expand_id = expand;
			
		if(title=='Attribute_Inventory_control'){
			doaction();
		}
		var jump_select=""; 
		if(document.form_jump && document.form_jump.category)
		{
		jump_select=document.form_jump.category;
		jump_select.value=title+'#'+expand;
		}
	}
	// For category panel
	var prev_category='';
	var open_level=0;
	var open_ids=Array();
	function toggle_category_panel(category_id,level,opn){ 
		var res_display=document.getElementById("res_display");		
		if(res_display && res_display.style.display=="") res_display.style.display="none";
		var img=document.getElementById("panel_"+category_id+"_img");
		var panel=document.getElementById("panel_"+category_id+"_content");
		
		show=false;		
		if (panel && panel.style.display=="none"){
			show_cat_panel(category_id);
			show=true;
		} else {
			//if (opn!=true && open_ids[level] && open_ids[level]==category_id && level==0) return;
			hide_cat_panel(category_id);
			
		}
		if (show){
			if (prev_category!=""){
				if (level<=open_level){
					hide_cat_panel(open_ids[level]);
					open_ids=open_ids.slice(0,level);
				}
			}
			open_level=level;
			prev_category=category_id;
			open_ids[level]=category_id;
			do_page_fetch("fetch_sub_list","",category_id,1,level+1);
		} else {
			open_ids=open_ids.slice(0,level);
			open_level=level-1;
		}		
	}
	function hide_cat_panel(category_id){
		var img=document.getElementById("panel_"+category_id+"_img");
		var panel=document.getElementById("panel_"+category_id+"_content");
		if(panel) {
			panel.style.display="none";
			panel.innerHTML="";	
		}
		if(img) img.src="images/template/panel_down.gif";
	}
	function show_cat_panel(category_id){
		var img=document.getElementById("panel_"+category_id+"_img");
		var panel=document.getElementById("panel_"+category_id+"_content");
		if (panel) panel.style.display="";
		if (img) img.src="images/template/panel_up.gif";
	}

	function toggle_focus(element,mode){
		if (mode==2){
			element.className="inputSelect";
		//For IE only
		if(element.type == "text" || element.type == "password")var intID=setInterval(function(){element.focus();clearInterval(intID);},10); //For IE only
		} else {
			element.className="inputNormal";
		}
	}
	var current_cmenu="";
	// Common popup menu
	function show_cpopup(menu_id,adjust_x,adjust_y){
		show_pop=document.getElementById("pop_common_"+menu_id);
		var con=document.getElementById("pop_common_"+menu_id+"_con");
		if (!con.pos_x){
			pos=getAnchorPosition("pop_common_"+menu_id+"_con");
			con.pos_x=pos.x;
			con.pos_y=pos.y;
			con.pos_x1=con.pos_x+parseInt(show_pop.style.width);
			con.pos_y1=con.pos_y+parseInt(show_pop.style.height)+20;
		}

		show_pop.style.left=con.pos_x+adjust_x;
		show_pop.style.top=con.pos_y+adjust_y;
		show_pop.style.display="";
		current_cmenu=menu_id;
		if (!IE) document.captureEvents(Event.MOUSEMOVE);
	    document.onmousemove=check_cpopup;
	}
	function check_cpopup(e){
		if(currentMenu=="") return;
		
		var con=document.getElementById("pop_common_"+current_cmenu+"_con");
		if (IE) { // grab the x-y pos.s if browser is IE
			tempX = event.clientX + document.body.scrollLeft;
			tempY = event.clientY + document.body.scrollTop;
		}
		else {  // grab the x-y pos.s if browser is NS
			tempX = e.pageX;
			tempY = e.pageY;
		}
		//document.getElementById("test_element").innerHTML=tempY<=con.pos_y1;
		if (!(tempX>=con.pos_x && tempX<=con.pos_x1 && tempY>=con.pos_y && tempY<=con.pos_y1)){
			hide_cpopup(true);
		}
	}
	function hide_cpopup(check_flag){ 
		show_pop=document.getElementById("pop_common_"+current_cmenu);
		if (!check_flag && show_pop) return;
		
		if (show_pop){
			document.onmousemove='';
			if (!IE) document.releaseEvents(Event.MOUSEMOVE);
			show_pop.style.display="none";
		}
		current_cmenu="";
	}
	function validate_search(){
		var phrase  = document.search_links.search_link.value;
		if(phrase=='') return false;
		else 	document.search_links.submit();
		
	}
</script>
</head>

<body>
<?php //require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- body //-->
	 <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2" style="border: 1px solid #C9C9C9;" align="center">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" colspan="3" align="center"><?php echo sprintf(ADDING_TITLE, $oID); ?></td>
          </tr>
          <tr class="dataTableRow">
           <form action="<?php echo tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $_GET['oID']); ?>" method="POST">
            <td class="dataTableContent" align="right"><?php echo TEXT_STEP_1; ?></td>
            <td class="dataTableContent" valign="top"><?php echo tep_draw_pull_down_menu('add_product_categories_id', tep_get_category_tree('0', '', '0', $category_array), $add_product_categories_id,'style="width:300px;" onchange="this.form.submit();"'); ?></td>
            <td class="dataTableContent" align="center">
			  <noscript>
			    <input type="submit" value="<?php echo TEXT_BUTTON_SELECT_CATEGORY; ?>">
			  </noscript>
			    <input type="hidden" name="step" value="2">
			 </td>
           </form>
          </tr>

<?php
  if (($step > 1) && (!$not_found)) {
    echo '          <tr class="dataTableRow">' . "\n" .
         '            <td colspan="3" style="border-bottom: 1px solid #C9C9C9;">' . tep_draw_separator('pixel_trans.gif', '1', '1') . '</td>' . "\n" .
         '          </tr>' . "\n" .
         '          <tr class="dataTableRow">' . "\n" .
         '            <td colspan="3" style="background: #FFFFFF;">' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
         '          </tr>' . "\n";
?>
          <tr class="dataTableRow"> 
            <td colspan="3" style="border-top: 1px solid #C9C9C9;"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
          </tr>
          <tr class="dataTableRow">
          <form action="<?php echo tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $_GET['oID']); ?>" method="POST">
            <td class="dataTableContent" align="right"><?php echo TEXT_STEP_2; ?></td>
            <td class="dataTableContent" valign="top"><?php echo tep_draw_pull_down_menu('add_product_products_id', $product_array, $add_product_products_id, 'style="width:300px;" onchange="this.form.submit();"'); ?></td>
            <td class="dataTableContent" align="center"><noscript><input type="submit" value="<?php echo TEXT_BUTTON_SELECT_PRODUCT; ?>"></noscript><input type="hidden" name="step" value="3">
            <input type="hidden" name="add_product_categories_id" value="<?php echo $add_product_categories_id; ?>">
          <?php if (isset($_POST['search'])) { ?>
            <input type="hidden" name="search" value="1">
            <input type="hidden" name="product_search" value="<?php echo $_POST['product_search']; ?>">
          <?php } ?>
            </td>
          </form>
          </tr>
<?php
  }
  
  if (($step > 1) && ($not_found)) 
  {
  
  
    echo '          <tr class="dataTableRow">' . "\n" .
         '            <td colspan="3" style="border-bottom: 1px solid #C9C9C9;">' . tep_draw_separator('pixel_trans.gif', '1', '1') . '</td>' . "\n" .
         '          </tr>' . "\n" .
         '          <tr class="dataTableRow">' . "\n" .
         '            <td colspan="3" style="background: #FFFFFF;">' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
         '          </tr>' . "\n";
?>
          <tr class="dataTableRow"> 
            <td colspan="3" style="border-top: 1px solid #C9C9C9;"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
          </tr>
          <tr class="dataTableRow">
         
            <td class="dataTableContent" align="right"><?php echo TEXT_STEP_2; ?></td>
            <td class="dataTableContent" valign="top"><?php echo TEXT_STEP_2_NONE; ?></td>
            <td class="dataTableContent" align="center"></td>
        
          </tr>
<?php
  }

  if (($step > 2) && ($add_product_products_id > 0)) {
    echo '          <tr class="dataTableRow">' . "\n" .
         '            <td colspan="3" style="border-top: 1px solid #C9C9C9;">' . tep_draw_separator('pixel_trans.gif', '1', '1') . '</td>' . "\n" .
         '          </tr>' . "\n" .
         '          <tr class="dataTableRow">' . "\n";
    
    if ($has_attributes) echo '          <form action="' . tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $_GET['oID']) . '" method="post">' . "\n";

    echo '            <td class="dataTableContent" align="right">' . TEXT_STEP_3 . '</td>' . "\n";

    if ($has_attributes) {
      $i=1;
      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$add_product_products_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$FSESSION->languages_id  . "'");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        $selected = 0;
        $products_options_array = array();
        if ($i > 1) echo '            <td class="dataTableContent">&nbsp;</td>' . "\n";
        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$add_product_products_id . "' and pa.options_id = '" . $products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$FSESSION->languages_id  . "'");
        while ($products_options = tep_db_fetch_array($products_options_query)) {
          $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options_name['products_options_name'] . ' - ' . $products_options['products_options_values_name']);
          if ($products_options['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->format($products_options['options_values_price'], true, $order->info['currency'], $order->info['currency_value']) .')';
          }
        }
		
		if(isset($_POST['add_product_options'])) {
          $selected_attribute = $_POST['add_product_options'][$products_options_name['products_options_id']];
        } else {
          $selected_attribute = false;
        }
		
        echo   '            <td class="dataTableContent" valign="top">' . tep_draw_pull_down_menu('add_product_options[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute) . '</td>' . "\n" .
               '            <td class="dataTableContent">&nbsp;</td>' . "\n" .
               '          </tr>' . "\n" .
               '          <tr class="dataTableRow">' . "\n";  
        $i++;
      }
      echo '            <td class="dataTableContent">&nbsp;</td>' . "\n" .
           '            <td class="dataTableContent" colspan="2" align="left"><input type="submit" value="' . TEXT_BUTTON_SELECT_OPTIONS . '"><input type="hidden" name="step" value="4"><input type="hidden" name="add_product_categories_id" value="' . $add_product_categories_id . '"><input type="hidden" name="add_product_products_id" value="' . $add_product_products_id . '">' . ((isset($_POST['search'])) ? '<input type="hidden" name="search" value="1"><input type="hidden" name="product_search" value="' . $_POST['product_search'] . '">' : '') . '</td>' . "\n" .
           '          </tr>' . "\n" .
           '          </form>' . "\n";
    } else {
      $step = 4;
      echo '            <td class="dataTableContent" valign="top" colspan="2">' . TEXT_SKIP_NO_OPTIONS . '</td>' . "\n" .
           '          </tr>' . "\n";
    }
  }
  
  if ($step > 3) {
  //Graeme remove the quantity box for 'P' tickets
  	if($the_product_type == 'P'){
	$the_quantity_field='&nbsp;1<input type="hidden" name="add_product_quantity"  value="1">';} 
	else
	{					
	$the_quantity_field='&nbsp;<input name="add_product_quantity" size="3" value="1">';
	
	//add an available quantity??
	$the_quantity_field.='&nbsp;'.$the_product_quantity.'&nbsp;currently available';
	}
								

  
    echo '          <tr class="dataTableRow">' . "\n" .
         '            <td colspan="3" style="border-bottom: 1px solid #C9C9C9;">' . tep_draw_separator('pixel_trans.gif', '1', '1') . '</td>' . "\n" .
         '          </tr>' . "\n" .
         '          <tr class="dataTableRow">' . "\n" .
         '            <td colspan="3" style="background: #FFFFFF;">' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
         '          </tr>' . "\n" .
         '          <tr class="dataTableRow">' . "\n" .
         '            <td colspan="3" style="border-top: 1px solid #C9C9C9;">' . tep_draw_separator('pixel_trans.gif', '1', '1') . '</td>' . "\n" .
         '          </tr>' . "\n" .
         '          <form action="' . tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $_GET['oID'] . '&action=add_product') . '" method="post">' . "\n" .
         '          <tr class="dataTableRow">' . "\n" .
         '            <td class="dataTableContent" align="right" valign="middle">' . TEXT_STEP_4 . '</td>' . "\n" .
         '            <td class="dataTableContent" align="left" valign="middle">' . TEXT_QUANTITY .$the_quantity_field. '</td>' . "\n" .
         '            <td class="dataTableContent" align="center" valign="middle"></td>' . "\n" .
		 '          </tr>' . "\n" . 
		 '          <tr class="dataTableRow">' . "\n" .
		 '             <td></td>' . "\n" . 
		 '             <td colspan="2"><input type="submit" value="' . TEXT_BUTTON_ADD_PRODUCT .'">' . "\n" .
		 '           ';
		 //graeme set a hidden field for product_type
    if (isset($_POST['add_product_options'])) {
      foreach($_POST['add_product_options'] as $option_id => $option_value_id) {
        echo '<input type="hidden" name="add_product_options['.$option_id.']" value="' . $option_value_id . '">';
      }
    }
    echo '<input type="hidden" name="add_product_categories_id" value="' . $add_product_categories_id . '"><input type="hidden" name="add_product_products_id" value="' . $add_product_products_id . '"><input type="hidden" name="step" value="5"></td>' . "\n" .
         '          </tr>' . "\n" .
         '          </form>' . "\n";
		 }
?>
        </table></td>
      </tr>
    </table>
    <!-- body_text_eof //-->
 
           <div align="center" class="dataTableContent">
                   
				   <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<a href=\"javascript:self.close();\"><?php echo TEXT_CLOSE_POPUP; ?></a>");
	               //-->
                  </script>
				  
				  <noscript>
				   <strong>
				    <?php echo TEXT_ADD_PRODUCT_INSTRUCTIONS; ?>
                   </strong>
				  </noscript>
				  
		   </div>
      
	
<!-- body_eof //-->

</body>
</html>
<?php  //eof   ?>
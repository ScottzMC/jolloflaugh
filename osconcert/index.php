<?php
/*
	osCommerce, Open Source E-Commerce Solutions
	http://www.oscommerce.com
	
	Copyright (c) 2003 osCommerce
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	http://www.osconcert.com

  	Copyright (c) 2021 osConcert

	Released under the GNU General Public License
*/


// Set flag that this is a parent file
define( '_FEXEC', 1 );

 ob_start();

require('includes/application_top.php');

	//this section catches the return from a GA product_info
	if( isset($_SESSION['customer_country_id']) == 999 && isset($_POST['box_office_switch'] ))
	{			  
			   
			   switch ($_POST['box_office_switch'] )
			   {
			   
			    case 'yes':
					$_SESSION['box_office_refund']='yes';
					$FSESSION->remove('box_office_reservation');
					$cart->reset(true);
					break;
				case 'reserve':
					$_SESSION['box_office_reservation']='yes';
					$FSESSION->remove('box_office_refund');
					$cart->reset(true);
					break;
				
				case 'no':
				    $FSESSION->remove('box_office_refund');
					$FSESSION->remove('box_office_reservation');
					$cart->reset(true);
				    break;				
				}

	}
	##############################################################################
	//
	// the following cPath references come from application_top.php
  $category_depth = 'top';
  if (isset($cPath) && tep_not_null($cPath)) 
  {  
    
	$plan_id_query = tep_db_query("select plan_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
	$plan_id_result = tep_db_fetch_array($plan_id_query);
	$plan_id=$plan_id_result['plan_id'];
	
	$categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
    $cateqories_products = tep_db_fetch_array($categories_products_query);
    if ($cateqories_products['total'] > 0) 
	{ 
		if($plan_id==9)
		{
			$category_depth = 'products';	
		}else{
			$category_depth = 'nested';
		}
    } else 
	{ 
      $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
	  $category_parent = tep_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) 
	  {
        if (GA_ONLY=='true')
		{
			$category_depth = 'products';
		}else{
			$category_depth = 'nested'; // navigate through the categories
		}
      } else {
        $category_depth = 'top'; // category has no products, but go to home
      }
    }
  }
	##############################################################################

  $static_category_depth='top';
  if(isset($stcPath) && tep_not_null($stcPath))
  {
   $static_categories_static_query = tep_db_query("select count(*) as total from " . TABLE_MAINPAGE . " where parent_id = '" . (int)$current_static_category_id . "'");
    $static_categories_static = tep_db_fetch_array($static_categories_static_query);
    if ($static_categories_static['total'] > 0) 
	{
      $static_category_depth = 'static'; // display static
	} else 
	{
      $static_category_parent_query = tep_db_query("select * from " . TABLE_MAINPAGE . " where parent_id = '" . (int)$current_static_category_id . "'");
      $static_category_parent = tep_db_fetch_array($static_category_parent_query);
   
        $static_category_depth = 'nested'; // navigate through the categories
    }
  }
  
  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_DEFAULT);
  if ($category_depth == 'nested') 
  {
    $category_query = tep_db_query("select c.categories_id,c.parent_id,c.date_id,c.plan_id,c.categories_status,cd.categories_name, cd.concert_venue, cd.concert_date, cd.concert_time, c.categories_quantity,c.categories_quantity_remaining,c.manufacturers_id,cd.categories_heading_title, c.concert_date_unix,cd.categories_description, c.categories_image, c.categories_image_3, c.categories_image_4 from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . tep_db_input($current_category_id) . "' and cd.categories_id = '" . tep_db_input($current_category_id) . "' and cd.language_id = '" . tep_db_input($FSESSION->languages_id) . "'");
    $category = tep_db_fetch_array($category_query);
	
	$parent_id = $category['parent_id'];
	if(DESIGN_MODE=='yes'){
		$manufacturers_id = $category['manufacturers_id'];
		$category_image_3 = $category['categories_image_3'];
		$category_image_4 = $category['categories_image_4'];
	}else{
		$manufacturers_id = '';
	}


	if(LOGIN_SEATPLAN=='true')
	{
		if (!$FSESSION->is_registered('customer_id')) 
		{
		tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
		}else
		{
		$content = CONTENT_INDEX_NESTED;
		}
	}else{
    $content = CONTENT_INDEX_NESTED;
	}

  } elseif ($category_depth == 'products') // || $FREQUEST->getvalue('manufacturers_id')!=''
  {
	
	$category_query = tep_db_query("select c.categories_id,c.parent_id,c.date_id,c.plan_id,c.categories_status,cd.categories_name, cd.concert_venue, cd.concert_date, cd.concert_time, cd.categories_heading_title,c.manufacturers_id, c.concert_date_unix,cd.categories_description, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . tep_db_input($current_category_id) . "' and cd.categories_id = '" . tep_db_input($current_category_id) . "' and cd.language_id = '" . tep_db_input($FSESSION->languages_id) . "'");

    $category = tep_db_fetch_array($category_query);
	
	$parent_id = $category['parent_id'];

	
	$manufacturers_id='';
	
	// create column list
    $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW,
						 'PRODUCT_LIST_DESCRIPTION'=>PRODUCT_LIST_DESCRIPTION
						 );
        
    asort($define_list);

    $column_list = array();
    reset($define_list);

	foreach($define_list as $key => $value)
	{
      if ($value > 0) $column_list[] = $key;
    }

    $select_column_list = '';
    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) 
	{
      switch ($column_list[$i]) 
	  {
        case 'PRODUCT_LIST_MODEL':
          $select_column_list .= 'p.products_model, ';
          break;
        case 'PRODUCT_LIST_NAME':
          $select_column_list .= 'pd.products_name, ';
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $select_column_list .= 'm.manufacturers_name, ';
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $select_column_list .= 'p.products_quantity, ';
          break;
        case 'PRODUCT_LIST_IMAGE':
          $select_column_list .= 'p.products_image_1,p.products_title_1, ';
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $select_column_list .= 'p.products_weight, ';
          break;
        case 'PRODUCT_LIST_DESCRIPTION':
          $select_column_list .= 'pd.products_description, ';
          break;

      }
    }
	// cgdis start
 // Get the category name and description
   $customer_group_id=0;
   $check_restrict_customer="";
   if($FSESSION->customer_id>0) $customer_group_id=get_customer_group_id();
   $check_restrict_customer=" and ((p.restrict_to_customers='' OR ISNULL(p.restrict_to_customers)) AND (p.restrict_to_groups='' OR ISNULL(p.restrict_to_groups)))";     
   if($FSESSION->customer_id>0 ) $check_restrict_customer=substr($check_restrict_customer,0,-1)." OR (p.restrict_to_customers like '%".$FSESSION->customer_id."%' OR p.restrict_to_groups like '%".$customer_group_id."%'))";
   
   
// show the products of a specified manufacturer
    if ($FREQUEST->getvalue('manufacturers_id')!='') 
	{
		
      if ($FREQUEST->getvalue('filter_id')!='' && tep_not_null($FREQUEST->getvalue('filter_id'))) {
// We are asked to show only a specific category
		$listing_sql="select " . $select_column_list . " date_format(p.products_date_available,'%Y-%m-%d') as products_date_available, p.products_id, pd.products_description,pd.products_number,p.manufacturers_id,  p.product_type,p.products_quantity, p.products_price,p.products_ordered,p.products_status, p.products_tax_class_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $FREQUEST->getvalue('manufacturers_id','int') . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' and p2c.categories_id = '" . $FREQUEST->getvalue('filter_id','int') . "' ".$check_restrict_customer;     
	  } 
	  else 
	  {
 //We show them all
		$listing_sql="select " . $select_column_list . " date_format(p.products_date_available,'%Y-%m-%d') as products_date_available, p.products_id, p.manufacturers_id,pd.products_description,pd.products_number, p.product_type,p.products_quantity, p.products_price,p.products_ordered,p.products_status, p.products_tax_class_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $FREQUEST->getvalue('manufacturers_id','int') . "' ".$check_restrict_customer;
	  }
    } 
	else 
	{
// show the products in a given categorie
      if ($FREQUEST->getvalue('filter_id')!='' && tep_not_null($FREQUEST->getvalue('filter_id'))) 
	  {
// We are asked to show only specific catgeory
      	$listing_sql = "select " . $select_column_list . " date_format(p.products_date_available,'%Y-%m-%d') as products_date_available, p.products_id, pd.products_description,pd.products_number,p.manufacturers_id,  p.product_type,p.products_quantity, p.products_price,p.products_ordered,p.products_status, p.products_tax_class_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $FREQUEST->getvalue('filter_id','int') . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "' ".$check_restrict_customer;
	  } 
	  else 
	  {
// We show them all -----and p.products_date_available<='" . getServerDate() . "'

      	$listing_sql = "select " . $select_column_list . " date_format(p.products_date_available,'%Y-%m-%d') as products_date_available, p.products_id,pd.products_description,pd.products_number, p.manufacturers_id, p.product_type, p.products_quantity, p.products_price,p.products_ordered,p.products_status,pd.products_number, p.products_model, p.products_tax_class_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, "  . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status <'2' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "' ".$check_restrict_customer;
		}
    }

	//product info (if we have only 1 product in the category we display it)
		$temp_listing_sql=$listing_sql;
		$temp_listing_query=tep_db_query($temp_listing_sql);
		if(tep_db_num_rows($temp_listing_query)=='1') 
		{
			$temp_listing_result=tep_db_fetch_array($temp_listing_query);
			tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $temp_listing_result['products_id']));
		}
		
	if(SEATPLAN_LOGIN_ENFORCED=='true' && !$FSESSION->is_registered('customer_id'))
	{
		tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
	}else
	{
    $content = CONTENT_INDEX_PRODUCTS;
	}
  } 
  else 
  { // default page
    $content = CONTENT_INDEX_DEFAULT;
  }
  
  //echo $category_depth;
  
  
	if ($static_category_depth == 'nested') 
	{

	    $static_category_query = tep_db_query("SELECT mp.page_id, mp.parent_id, mp.sort_order, mp.page_status from " . TABLE_MAINPAGE . " mp, " . TABLE_MAINPAGE_DESCRIPTIONS . " mpc where mpc.page_id=mp.page_id and mp.page_id = '" . $spl_stcid[1] . "' and mpc.language_id = '" . tep_db_input($FSESSION->languages_id) . "'");
    	$static_category = tep_db_fetch_array($static_category_query);
	    $content = CONTENT_INDEX_STATIC;
  	} 
	else if ($static_category_depth=="static") 
	{
		$static_category_query = tep_db_query("SELECT mp.page_id, mp.parent_id, mp.sort_order, mp.page_status from " . TABLE_MAINPAGE . " mp, " . TABLE_MAINPAGE_DESCRIPTIONS . " mpc where mpc.page_id=mp.page_id and mp.page_id = '" . tep_db_input($current_static_category_id) . "'  and mpc.language_id = '" . tep_db_input($FSESSION->languages_id) . "'");
    	$static_category = tep_db_fetch_array($static_category_query);
		$content = CONTENT_INDEX_STATIC;
	}

	require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
	require(DIR_WS_INCLUDES . 'application_bottom.php');
	
	 ob_flush();
?>
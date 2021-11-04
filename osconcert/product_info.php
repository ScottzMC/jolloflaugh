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

	Copyright (c) 2009-2012 osConcert
	

	Released under the GNU General Public License
*/

	// Set flag that this is a parent file
	define( '_FEXEC', 1 );
		
	if (!function_exists('tep_db_connect'))
	{
		require('includes/application_top.php');
	}
	
	$products_id = $FREQUEST->getvalue('products_id','int',0);
	$cPath = $FREQUEST->getvalue('cPath','int');
	
	/* retrieving the cPath, if required */
	if(!$cPath)
	{
		if($products_id==0)
		{
			die('no products_id');
		}
		$sql = 'SELECT categories.parent_id FROM '.TABLE_CATEGORIES.' LEFT JOIN '.TABLE_PRODUCTS_TO_CATEGORIES.' ON products_to_categories.categories_id = categories.categories_id WHERE products_to_categories.products_id = '.(int)$products_id;
		$cPath_query = tep_db_fetch_array(tep_db_query($sql));
		$cPath = $cPath_query['parent_id'];
	}
	
    
    if ($cart->check_for_vouchers() == true)
	{
      tep_redirect("gv_faq.php?faq_item=1");
    }
			
		$check_restrict_customer="";
		$customer_group_id=0;
		$check_restrict_customer=" and ((p.restrict_to_customers='' OR ISNULL(p.restrict_to_customers)) AND (p.restrict_to_groups='' OR ISNULL(p.restrict_to_groups)))";
		if($FSESSION->is_registered('customer_id'))
		{
			$customer_group_id=get_customer_group_id();
			$check_restrict_customer=substr($check_restrict_customer,0,-1)." OR (p.restrict_to_customers like '%". tep_db_input($FSESSION->customer_id) ."%' OR p.restrict_to_groups like '%". tep_db_input($customer_group_id) ."%'))";
		}
	
		// load the details into merge fields
		$template_details=array();
		$tpl["SECTION_NO_PRODUCT"]=0;
		$tpl["SECTION_PRODUCT"]=0;
		
		require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_PRODUCT_INFO);
		//			//p.products_date_available >'" . getServerDate() . "' 
		//and
	
	$sql = "
		select
			p.products_id,
			p.products_price_break,
			pd.products_name,
            p.products_sku,
			pd.products_description,
			pd.products_number,
			p.author_name,
			p.products_model,
			p.products_quantity,
			p.products_image_1,
			p.products_title_1,
			pd.products_url,
			pd.language_id,
			p.products_price,
			p.products_tax_class_id,
			p.products_date_added,
			p.products_date_available,
			p.manufacturers_id,
			p.product_type
		from
			".TABLE_PRODUCTS." p,
			".TABLE_PRODUCTS_DESCRIPTION." pd
		where

			p.products_status = '1'
		and
			p.products_id = '".tep_db_input($products_id)."'
		and
			pd.products_id = p.products_id
		and
			pd.language_id = '".(int)$FSESSION->languages_id."'".
			$check_restrict_customer;
	
	$product_info_query = tep_db_query($sql);
	$product_info=tep_db_fetch_array($product_info_query);
				
		$GLOBALS["TPL_CONTENT"]=@(file_get_contents(DIR_WS_TEMPLATES . TEMPLATE_NAME . "/content/" .CONTENT_PRODUCT_INFO . ".tpl.php"));
		

	if(($categories['categories_status']==1)or($_SESSION['BoxOffice']== 999)or($_SESSION['customer_country_id']==999))
	{
		
		if(tep_db_num_rows($product_info_query) > 0 && $GLOBALS["TPL_CONTENT"]!="")
		{ 
			
			$tpl["SECTION_NO_PRODUCT"]=0;
			$tpl["SECTION_PRODUCT"]=1;
			
			$products_quantity=$product_info["products_quantity"];
			$product_type=$product_info["product_type"];
			$products_sku=$product_info["products_sku"];
			
			/* ... and another bug-fix */
			$currency=$FSESSION->currency;
			
			/* the array for JS vars */
			$JS_DETAILS=array(
				"PRODUCT_ID"=> $products_id,
				'PRODUCT_STOCK'=> $products_quantity,
				'PRODUCT_PRICE_BREAKS' => "{enabled:false}",
				'CURRENCY_DETAIL' => "{symbolLeft:'" .$currencies->currencies[$currency]['symbol_left'] . "',symbolRight:'" . $currencies->currencies[$currency]['symbol_right'] . "'}",
				'PRICE_CALC_FUNCTIONS' => "['getProductsPrice()','getAttributePrice(-1)']",
				'AJX_URL' => "'" . tep_href_link(FILENAME_PRODUCT_INFO,'t=1','NONSSL',true,false) ."'",
				'ERROR_MSGS' => "{'email':'" . TEXT_ERR_EMAIL_ADDRESS . "'}",
				'SALEMAKER_DATAS' => '{enabled:false}',
				'CPATH' => $cPath
			);
			$JS_START="";
			
			$tax_rate=tep_get_tax_rate($product_info["products_tax_class_id"]);
			
			// if($new_price = tep_get_products_special_price($product_info['products_id'])) 
		
				// $products_dprice = '<s>' . $currencies->display_price($product_info['products_price'], $tax_rate,'1',true) . '</s> <span class="productSpecialPrice">' . $currencies->format(tep_add_tax($new_price, tep_get_tax_rate($product_info['products_tax_class_id']))) . '</span>';
				// $products_tot_price=$currencies->format(tep_add_tax($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])));
				// $products_price=tep_add_tax($new_price, $tax_rate);
				// $products_original_price=$new_price;
			// }
			//else {
				$products_tot_price=$products_dprice = $currencies->display_price($product_info['products_price'], $tax_rate,'1',true);
				$products_price=tep_add_tax(tep_get_plain_products_price($product_info['products_price']), $tax_rate);
				$products_original_price=$product_info['products_price'];
			//}
			
			$special_price=$new_price;

			$products_name = $product_info['products_name'];
			
			//begin the category stuffs
			$temp_query = tep_db_query("select cd.categories_name,cd.categories_heading_title,cd.categories_id,cd.categories_description,cd.concert_venue,cd.concert_date,cd.concert_time, c.categories_GA, c.categories_quantity_remaining,cd.language_id from " . TABLE_CATEGORIES_DESCRIPTION . " cd ," . TABLE_CATEGORIES. " c , " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc where cd.categories_id=ptc.categories_id and ptc.products_id='".tep_db_input($products_id)."' and cd.language_id = '" . (int)$FSESSION->languages_id . "'");
			$temp = tep_db_fetch_array($temp_query);
			$description=$temp["categories_description"];
			$concert_heading_title=$temp["categories_heading_title"];
			$concert_date=$temp["concert_date"];
			$concert_time=$temp["concert_time"];
			
			if($concert_time>0)
		{	
		$time=strtotime($concert_time);
			if(TIME_FORMAT==12)
			{
			$heading_time = date('g:i a', $time);//2000 = 8:00pm
			//$heading_time = date('ga', $time);//2000 = 8pm
			}else
			{
			$heading_time = date('H:ia', $time);//H:ia=24hr	
			}
		}
		$digit_time = date('Hi', $time);

		require(DIR_WS_FUNCTIONS.'/date_formats.php');

		if(!strtotime($concert_date))
		{
			// it's not in date format
			$heading_date = $concert_date;//gives concert_date
		}
		if(!strtotime($concert_time))
		{
			// it's not in date format
			$heading_time = $concert_time;//gives concert_date
		}
			
			//we find the product id and get the category id
			$get_cat_id=tep_db_query("select * from ". TABLE_PRODUCTS_TO_CATEGORIES ." where products_id='".tep_db_input($products_id)."'");
			$got_id =tep_db_fetch_array($get_cat_id);
			$cat_id=$got_id['categories_id'];

			$tpl["VALUE_SPACER"]="&nbsp;&nbsp;";
			//GA stuffs		
			$GA_query = tep_db_query("select c.categories_GA, c.categories_quantity_remaining, ptc.products_id from " . TABLE_CATEGORIES. " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc where c.categories_id=ptc.categories_id and c.categories_id = '" . $cat_id . "' and ptc.products_id='".tep_db_input($products_id)."'");
			$GA = tep_db_fetch_array($GA_query);
			if($GA["categories_GA"]==1 && HIDE_GA_ONLY_QTY=='no')
			{//this is a GA category with master quantity
				$tpl["VALUE_GA_TOTAL"]='<div class="ga_total">' . TEXT_GA_REMAIN_LEFT . '
				<span style="color:#ff0000">' . $GA['categories_quantity_remaining'] . '</span>
				' . TEXT_GA_REMAIN_RIGHT . '</div>';
			}else
			{
				$tpl["VALUE_GA_TOTAL"]='';
			}
			if((HIDE_GA_QTY=='no')&&($product_type=='G')&&($products_sku !=6))
			{
			$tpl["VALUE_GA_QTY"]="<span class=\"small\"> (".$products_quantity.")</span>";
			}
				elseif((HIDE_GA_QTY=='no')&&($product_type=='F'))
				{
					$tpl["VALUE_GA_QTY"]="<span class=\"small\"> (".$products_quantity*FAMILY_TICKET_QTY.")</span>";
				}else{
					$tpl["VALUE_GA_QTY"]="";
				}

			//$temp = tep_db_fetch_array($temp_query);

			// prepare the images
			$images=array(defined('HEADER_COLS_MULTIPLE_IMAGE')?constant('HEADER_COLS_MULTIPLE_IMAGE'):3);
			$img_string="";
			$title_string="";

			$icnt=1;
				$show_image=trim($product_info['products_image_' . $icnt]);
				if ($show_image!="" && (file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . "big/" . $show_image) ||  file_exists(DIR_WS_IMAGES . $show_image) ) )
				{
					$title_string.="'" . addslashes($product_info["products_title_" . $icnt]) . "',";
						$images[]=array('IMAGE'=>tep_product_small_image($show_image,$product_info["products_title_" . $icnt],' onClick="javascript:set_image(' . ($icnt-1) .');" style="max-width:100%;height:auto;cursor:pointer;cursor:hand;"'),'TITLE'=>$product_info["products_title_" . $icnt]);
					if($img_string=="") {
						$first_image=$show_image;
					}
					if(file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . "big/" . $show_image)){
						$img_string.="'" . DIR_WS_IMAGES . "big/" . $product_info["products_image_" . $icnt] . "',";
					}
					else {
						$img_string.="'" . DIR_WS_IMAGES . $product_info["products_image_" . $icnt] . "',";
					}

				}
			
			$tpl["SECTION_IMAGE"]=0;
			$tpl["SECTION_MULTIPLE_IMAGE"]=0;
			$temp='';
			if (count($images)>1)
			{
				$tpl["SECTION_IMAGE"]=1;
				if(file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . "big/" .$first_image)){
					$temp='<img class="img-fluid" src="' . DIR_WS_IMAGES . "big/" . $first_image . '" id="imgContainer">';
				}
				else {				
					$temp='<img class="img-fluid" src="' . DIR_WS_IMAGES . $first_image . '" id="imgContainer">';
				}
				$tpl["VALUE_FIRST_IMAGE"]=$temp;
				$tpl["VALUE_FIRST_IMAGE_TITLE"]=tep_output_string($product_info['products_title_1']);
				
			}

			do_replace();
			$tpl["SECTION_PRODUCTS_PRICE_BREAK"]=0;
			$tpl["SECTION_PRODUCTS_PRICE"]=0;
			$tpl["VALUE_LEFT_SYMBOL"] = '';
			$tpl["VALUE_RIGHT_SYMBOL"] = '';
			
			//set price & quantity details
			// if($product_info["products_price_break"] == "Y")
			// {
				// $tpl["SECTION_PRODUCTS_PRICE_BREAK"]=1;
				// //new text input - languages file also updated
				// $tpl["VALUE_PRODUCTS_PRICE_BREAK_HEADING"] = TEXT_VALUE_PRODUCTS_PRICE_BREAK_HEADING;
				// $tpl["VALUE_PRODUCTS_PRICE_BREAK_BUY"] = TEXT_VALUE_PRODUCTS_PRICE_BREAK_BUY;
				// $tpl["VALUE_PRODUCTS_PRICE_BREAK_SAVE"] = TEXT_VALUE_PRODUCTS_PRICE_BREAK_SAVE;
				// $tpl["VALUE_PRODUCTS_PRICE_BREAK_EACH"] = TEXT_VALUE_PRODUCTS_PRICE_BREAK_EACH;
				// $tpl["REPEAT_PRODUCTS_PRICE_BREAK"]=tep_get_products_breaks($product_info,$tax_rate);
				// $tpl["VALUE_PRICE_BREAK_QUAN"]=TEXT_QUANTITY;
				// $tpl["VALUE_PRICE_BREAK_PRICE"]=TEXT_PRICE;
				// $tpl["VALUE_INPUT_QUAN"]="<input type='hidden' name='qty' value='1' id='qty'>";
				
				// if(!$tpl["REPEAT_PRODUCTS_PRICE_BREAK"]) 
				// {
					// $product_info["products_price_break"]='N';
					// $tpl=array("SECTION_PRODUCTS_PRICE_BREAK"=>0);
				// }
				// else 
				// {
					// $products_price=tep_add_tax($product_info['products_price'], $tax_rate);
					// $products_dprice=$currencies->format($products_price);
				// }
			// }
			
			//if($product_info["products_price_break"] != "Y")
		//	{
				
				$tpl["SECTION_PRODUCTS_PRICE"]=1;
				$tpl["VALUE_PRODUCTS_PRICE"]='<b>' . TEXT_PRICE . ':&nbsp;' . $products_dprice . '</b>';
				//add tax
				//$tpl["VALUE_PRODUCTS_PRICE"]='<b>' . TEXT_PRICE . ':&nbsp;' . $products_dprice . ' (inkl.&nbsp;' . $tax_rate . '&nbsp;%&nbsp;MwSt.)</b>';/* PETZI GROUP */
				
				if (($product_info['product_type'] == 'G')
					or($product_info['product_type'] == 'F')
					or($product_info['product_type'] == 'C')
					or($product_info['product_type'] == 'B')
					or($product_info['product_type'] == 'X')) 
					{
						if(ALT_GA_TEMPLATE=='yes')
						{
							$tpl["VALUE_TEXT_PRODUCTS_QUANTITY"]='<b>' . TEXT_QUANTITY . '</b>:';
							
							//vouchers
							if ($product_info['products_sku'] == '6')
							{
								$tpl["VALUE_TEXT_PRODUCTS_QUANTITY"]='<b>' . TEXT_VOUCHER_VALUE . '</b>:';
								$tpl["VALUE_PRODUCTS_PRICE"] = '';
								$tpl["VALUE_LEFT_SYMBOL"] = $currencies->currencies[$currency]['symbol_left'];//$currency;
								$tpl["VALUE_RIGHT_SYMBOL"]  =  $currencies->currencies[$currency]['symbol_right'];
							}
							
							if($products_quantity<1)
							{
								$tpl["VALUE_PRODUCTS_QUANTITY"]='';
								$tpl["VALUE_ADD_QUANTITY_MINUS"]='';
								$tpl["VALUE_ADD_QUANTITY_PLUS"]='';
								$tpl["VALUE_CHECKOUT"]='';
								$tpl["VALUE_FAMILY_QUANTITY"]="";
								
							}
							else
							{

							$tpl["VALUE_PRODUCTS_QUANTITY"]= tep_draw_input_field('qty',1,'id="qty" style="margin:16px 0 0 0;padding:0px; text-align: center;min-width:55px;min-height:42px" size="3" onfocus="this.value=\'\';" onBlur="javascript:checkStock(\'\');" onKeyDown="javascript:return numericOnly(event);"');
							//$tpl["VALUE_PRODUCTS_QUANTITY"]='<b>' . TEXT_QUANTITY . '</b>:&nbsp;' . tep_draw_input_field('qty',1,'id="qty" size="3" onfocus="this.value=\'\';" onBlur="javascript:checkStock(\'\');" onKeyDown="javascript:return numericOnly(event);"');
							$tpl["VALUE_PRODUCTS_QUANTITY"].='
							  <script type="text/javascript">
								var currentValue = 1;
								var add = function(valueToAdd){
									currentValue += valueToAdd;
									if (currentValue < 1){
									 value =1;
									 return;
									}
									document.getElementById(\'qty\').value = currentValue;
									checkStock(\'\');
								};
							</script>
							';
							$tpl["VALUE_ADD_QUANTITY_MINUS"]='<a href="javascript:add(-1)"><span class="btn btn-primary">&nbsp;<i class="fa fa-minus"></i></span></a>';
							
							$tpl["VALUE_ADD_QUANTITY_PLUS"]='<a href="javascript:add(1)"><span class="btn btn-primary">&nbsp;<i class="fa fa-plus"></i></span></a>';
							$tpl["VALUE_CHECKOUT"]='
							<div id="btnCheckOut" style="display:none"> <a title="' . IMAGE_BUTTON_CHECKOUT . '" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_template_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT) . '</a></div>';
							//Don't show Family Quantity
							$tpl["VALUE_FAMILY_QUANTITY"]="";
							}
						}else{
							$tpl["VALUE_TEXT_PRODUCTS_QUANTITY"]='';
							$tpl["VALUE_PRODUCTS_QUANTITY"]='';
							$tpl["VALUE_ADD_QUANTITY_MINUS"]='';
							$tpl["VALUE_ADD_QUANTITY_PLUS"]='';
							$tpl["VALUE_CHECKOUT"]='
							<div id="btnCheckOut" style="display:none"> <a title="' . IMAGE_BUTTON_CHECKOUT . '" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_template_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT) . '</a></div>';
							$tpl["VALUE_FAMILY_QUANTITY"]="";
							
						}
					}else // if P
					{
							//cartzone shows no quantity box
							$tpl["VALUE_TEXT_PRODUCTS_QUANTITY"]='';
							$tpl["VALUE_ADD_QUANTITY_PLUS"]='';
							$tpl["VALUE_ADD_QUANTITY_MINUS"]='';
							$tpl["VALUE_PRODUCTS_QUANTITY"]="<input type='hidden' name='qty' value='1' id='qty'>";
							$tpl["VALUE_ADD_QUANTITY"]="";
							$tpl["VALUE_CHECKOUT"]='
							<div id="btnCheckOut" style="display:none"> <a title="' . IMAGE_BUTTON_CHECKOUT . '" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_template_image_button('', IMAGE_BUTTON_CHECKOUT) . '</a></div>';
					}
				
				
				/* Start: Salemaker */
				$has_option="";
				if($special_price)
				{
					$has_option=" and sale_specials_condition != 1";
				}
				$sDate=getServerDate(true);
				//Oct 2012 $category_id is null
				//check for product_discount price
				$sale_query = tep_db_query("select sale_id,sale_specials_condition, sale_deduction_value, sale_deduction_type,choice_text,choice_warning from " . TABLE_SALEMAKER_SALES . " where sale_discount_type='C' " . $has_option . " and ((sale_categories_all='' and sale_products_selected='') or sale_categories_all like '%," . tep_db_input($current_category_id) . ",%' or sale_products_selected like '%," . tep_db_input($products_id) .",%') and sale_status = '1' and (sale_date_start <='" . tep_db_input($sDate) . "' or sale_date_start = '0000-00-00') and (sale_date_end >= '" . tep_db_input($sDate) . "' or sale_date_end = '0000-00-00') order by sale_deduction_value");
				
				if(tep_db_num_rows($sale_query) > 0)
				{
					$salemaker=1;
					//can we prevent GA having Salemaker?
					if($product_type =='P')
					{
						$tpl["SECTION_PRODUCTS_SALEMAKER"]=1;
					}else
					{
						$tpl["SECTION_PRODUCTS_SALEMAKER"]=0;
					}
					//just in case 
					if ($product_info['products_sku'] == '6')
					{
						$tpl["SECTION_PRODUCTS_SALEMAKER"]=0;
					}
						
					$tpl["VALUE_SELECT_SALEMAKER_DISCOUNT"]=TEXT_SELECT_SALEMAKER_DISCOUNT;
					$JS_DETAILS['SALEMAKER_DATAS']='{enabled:true,sales:{';
					
					/* this is the radio which switches disables the discount */
					$sales=array();
					$sales[]=array(
						"OPTION"=>tep_draw_radio_field('salemaker_id', 0, true, 'id="salemaker_id" onClick="javascript:selectDiscount(0);"'),
						"OPTION_TEXT"=>'&nbsp;&nbsp;' . TEXT_NO_DISCOUNT
						);
					
					while($sale=tep_db_fetch_array($sale_query))
					{
						
						$JS_DETAILS['SALEMAKER_DATAS'].=$sale["sale_id"] . ":{price:";
						
						/* switching the discount type */
						switch($sale['sale_deduction_type'])
						{
							
							case 1:
								$choice_text='&nbsp;&nbsp;' . $sale["choice_text"] . " (" . number_format($sale["sale_deduction_value"],2). "%)";
								break;
							
							case 2:
								$choice_text='&nbsp;&nbsp;' . $sale["choice_text"] . " (<s><font color='red'>" . $currencies->format(tep_add_tax($products_original_price, $tax_rate)) . "</font></s>&nbsp;" . $currencies->format(tep_add_tax($sale["sale_deduction_value"], $tax_rate)) . ")";
								break;
							
							default:
								$choice_text='&nbsp;&nbsp;' . $sale["choice_text"] . " (" . $currencies->format(tep_add_tax($sale["sale_deduction_value"],$tax_rate)). ")";
								break;
						}
						//this next line is also lacking currency
											//tep_get_salemaker_price has no currency either we do have a value later 
											//in the code but we need one here now
											//
						$currency_value=$currencies->currencies[$currency=$FSESSION->currency]['value'];
						//just in case we get a NULL return make it = 1
						if($currency_value==0)
						{
							$currency_value=1;
						}

						$JS_DETAILS['SALEMAKER_DATAS'] .= $currency_value*(tep_add_tax(tep_get_salemaker_price($special_price,$product_info["products_price"],$sale),$tax_rate)) . ",warning:'" .htmlspecialchars($sale["choice_warning"]) . "'},";
						
						/* this is the radio which switches the discount id */
						$sales[]=array(
							"OPTION"=>tep_draw_radio_field('salemaker_id',$sale["sale_id"],false,' onClick="javascript:selectDiscount(' . $sale["sale_id"] . ');"'),
							"OPTION_TEXT"=>$choice_text
						);
					}
					$JS_DETAILS['SALEMAKER_DATAS']=substr($JS_DETAILS['SALEMAKER_DATAS'],0,-1) . "}}";
					$tpl["REPEAT_PRODUCTS_SALEMAKER"]=$sales;
				}
				else //no salemaker results
				{
					$tpl["SECTION_PRODUCTS_SALEMAKER"] = 0;
				}
			//}
		
			
			$tpl["VALUE_PRODUCTS_NAME"]=$product_info['products_name'];
			$tpl["VALUE_PRODUCTS_DESCRIPTION"]=$product_info['products_description'];
			do_replace();
			/* End: Salemaker */
			
			//extra mods??
			ob_start();
			$tpl["VALUE_EXTRA_MODULES"]=ob_get_contents();
			do_replace();
			ob_end_clean();
			
			
			if ($product_info['products_sku'] == '6')
			{
				$products_from = TEXT_PRICES_FROM;
			}else{
				$products_from = '';  
			}

				
			######################################################
			 $id=$products_id;
			 require_once(DIR_WS_INCLUDES . 'functions/categories_lookup.php');
			 #####################################################
			// // call the new function
			 list($heading_name, $heading_venue,  $heading_date, $heading_time, $heading_title, $heading_categories_description) = categories_lookup();

			######################################################	
				
				$tpl["SECTION_ORIGINAL_HEAD"]=1;
				
				$tpl["VALUE_CATEGORIES_START"]='';
				$tpl["VALUE_CATEGORIES_NAME"]=tep_get_categories_name($cat_id);
				$tpl["VALUE_CATEGORIES_TITLE"]=$heading_name;//tep_get_categories_heading_title($id);
				$tpl["VALUE_CATEGORIES_VENUE"]=$heading_venue;//tep_get_categories_venue($cat_id);
				$tpl["VALUE_CATEGORIES_DATE"]=$heading_date;//$concert_date;
				$tpl["VALUE_CATEGORIES_TIME"]=$heading_time;//$concert_time;
				$tpl["VALUE_CATEGORIES_DESC"]=$heading_categories_description;
				$tpl["VALUE_CATEGORIES_END"]='';
				$tpl["VALUE_HEAD_PRICE"]= $products_from . $products_dprice;
				$tpl["VALUE_TABLE_HEAD_FIRST"]="";
				$tpl["VALUE_TABLE_HEAD_LAST"]="";

			do_replace();
				
		
			$tpl["SECTION_BUY"]=0;
			

			do_replace();
		
			$tpl["VALUE_PRODUCTS_URL"]=PRODUCT_INFO_MESSAGE;
			
			##################################################################
			//cartzone add checkout button and continue shopping button
			$show_category_id = $cPath_array[(sizeof($cPath_array)-3)];
			$section_category_id = $cPath_array[(sizeof($cPath_array)-2)];
			$category_links = array_reverse($cPath_array);
			foreach ($cPath_array as $i => $value)
			{
				unset($cPath_array[$i]);
			}
			$cPath_array[] = $show_category_id;
			$cPath_array[] = $section_category_id;
			$cPath_array = array_values($cPath_array);
			$cPath = implode('_', $cPath_array);

			$FSESSION->set('prev_category_viewed','cPath='.$cPath);
			
			//continue shopping

			if (($product_info['product_type'] == 'G')
				or($product_info['product_type'] == 'B')
				or($product_info['product_type'] == 'F')
				or($product_info['product_type'] == 'C')
				or($product_info['product_type'] == 'X')) 
			{

				//$tpl["VALUE_BUTTON_CONT"]='<a href="' . tep_href_link(FILENAME_DEFAULT,'cPath=' . $current_category_id) . '">' . tep_template_image_button_basic('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>';
			//Now it's back to featured categories
				$tpl["VALUE_BUTTON_CONT"]='<a class="pull-right" href="' . tep_href_link(FILENAME_FEATURED_CATEGORIES,'') . '">' . tep_template_image_button_basic('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>';
			}
			else
			{
				//$tpl["VALUE_BUTTON_CONT"]='<a href="' . tep_href_link(FILENAME_DEFAULT,$FSESSION->prev_category_viewed) . '">' . tep_template_image_button_basic('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>';
				$tpl["VALUE_BUTTON_CONT"]='<a class="pull-right" href="' . tep_href_link(FILENAME_FEATURED_CATEGORIES,'') . '">' . tep_template_image_button_basic('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>';
			}
			

			
			$cPath=$FREQUEST->getvalue('cPath','int');
			
					$submit_data_value = '0';
					if ($product_info['products_sku'] == '6')
					{
					$submit_data_value = '6';
					}

				if (($product_info['product_type'] == 'G')
					or($product_info['product_type'] == 'B')
					or($product_info['product_type'] == 'F')
					or($product_info['product_type'] == 'C')
					or($product_info['product_type'] == 'X')) 
				{
					
					if($products_quantity<1){
					$tpl["VALUE_BUTTON_CART"]= '<span>' . tep_template_image_button('', IMAGE_BUTTON_SOLD) . '</span>';
					}else{								
					$tpl["VALUE_BUTTON_CART"]='<span id="addCart">' . tep_template_image_button_cart('', IMAGE_BUTTON_IN_CART,'') . '</span>';
					}
				}
				else
				{
					if(tep_db_num_rows($sale_query) > 0)
					{
					$tpl["VALUE_BUTTON_CART"]='
					<span id="addCart">' . tep_template_image_button_cart('', IMAGE_BUTTON_UPDATE_CART,'onClick="javascript:submitData();" style="cursor:pointer;cursor:hand"') . '</span>';
					}else
					{
					$tpl["VALUE_BUTTON_CART"]="";
					}
				}
			
			//We don't need Total Price if no SaleMaker
			$tpl["VALUE_PRODUCTS_QUANTITY"]=$products_quantity;
			if($salemaker==1)
			{
				$tpl["VALUE_TOTAL_PRICE"]=TEXT_TOTAL_PRICE . ' : <span id="totalProductsPrice">' . $products_tot_price . '</span>';
			}else
			{
				$tpl["VALUE_TOTAL_PRICE"]="";
			}
			
			$tpl["VALUE_STOCK_OUT"]=TEXT_OUT_STOCK;
			$tpl["VALUE_STOCK_OUT_DETAILS"]=TEXT_OUT_STOCK_DETAILS;

			$link=tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product');

			$tpl['VALUE_PRODUCT_FORM']=tep_draw_form('frmProduct', $link);
			//in the next line $price is taking no account of the currency in use
			//so let's alter $ price to $curr_price and make $curr_price = $price by currency
			//new method added to includes/clases/currencies.php
			$curr_price=$currencies->get_numeric_value($products_tot_price,$currency);
			
			$tpl["VALUE_HIDDEN_VALUES"]=tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_draw_hidden_field('price',$curr_price,'id="price"');
			
			$tpl["VALUE_LOAD_MSG_QUAN"]=$tpl["VALUE_LOAD_MSG_ATTR"]=TEXT_CHECK_STOCK;
			do_replace();
			
			
			//bug - this line returns nothing????
			//PHP windows bug here https://bugs.php.net/bug.php?id=38826
			//so get the full URL in there - n.b. this will NOT work for http://localhost - use 127.0.0.1

			//some servers work with this or the Add to Cart will not work.
			//$js_content=@file_get_contents(DIR_WS_JAVASCRIPT . "product_info.js.php");
			$js_content=@(file_get_contents(HTTP_SERVER.DIR_WS_HTTP_CATALOG.DIR_WS_JAVASCRIPT . "product_info.js.php"));
			reset($JS_DETAILS);
			//FOREACH
			//while(list($key,$value)=each($JS_DETAILS))
			foreach($JS_DETAILS as $key => $value)
			{
				$js_content=str_replace("{{" . $key . "}}",$value,$js_content);
			}
			//some servers work with this or the Add to Cart will not work.
			//$js_content=@file_get_contents(DIR_WS_INCLUDES . "http.js") . $js_content;
			$js_content=@(file_get_contents(HTTP_SERVER.DIR_WS_HTTP_CATALOG.DIR_WS_INCLUDES . "http.js")) . $js_content;
			
			$GLOBALS["TPL_CONTENT"]=$js_content . $GLOBALS["TPL_CONTENT"];
			$js_content="";
			$tpl["VALUE_SCRIPTS"]=$js_content;
			$tpl["VALUE_START_SCRIPTS"]='<script>' . $JS_START . '</script>';
			do_replace();
		}
		else
		{
			//if product has been disabled via -out of stock button-
			//we can't get anything from the product_info_query if the status is not 1 (in stock)
			$get_qty=tep_db_query("select products_quantity from ". TABLE_PRODUCTS ." where products_id='".tep_db_input($products_id)."'");
			while($qty_result = tep_db_fetch_array($get_qty))
			{
			$qty= $qty_result['products_quantity'];	
			}
			if($qty<1)
			{
			$GLOBALS["TPL_CONTENT"]="<h4>" . SOLD_OUT_MESSAGE . "</h4>";
			 //echo $qty;
			}
			else
			{		
				if (EVENT_DISABLED_MESSAGE =='')
				{
					$GLOBALS["TPL_CONTENT"]="NOT AVAILABLE";
				}
				else
				{
					$GLOBALS["TPL_CONTENT"]="<h4>" . EVENT_DISABLED_MESSAGE . "</h4>";
					//echo $qty;
				}
			}
		}
	}//end BO
	else 
	{
		$tpl["SECTION_NO_PRODUCT"]=1;
		if (EVENT_DISABLED_MESSAGE =='')
		{
		$tpl["VALUE_PRODUCT_NOT_FOUND"]=TEXT_PRODUCT_NOT_FOUND;
		}
		else
		{
			$GLOBALS["TPL_CONTENT"]="<h4>" . EVENT_DISABLED_MESSAGE . "</h4>";
			//echo $qty;
		}
		
		
		do_replace();
	}

	$product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . tep_db_input($FREQUEST->getvalue('products_id','int')) . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "'");
	$product_check = tep_db_fetch_array($product_check_query);

	$content = CONTENT_PRODUCT_INFO;
	$javascript = 'popup_window.js';
	
	require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
	require(DIR_WS_INCLUDES . 'application_bottom.php');
	
	function do_replace()
	{
		$template_details=$GLOBALS["tpl"];
		$template_content=$GLOBALS["TPL_CONTENT"];
		$replaced_content=$template_content;
		//FOREACH
		//while(list($key,$value)=each($template_details))
		foreach($template_details as $key => $value)
		{
			switch(substr($key,0,strpos($key,'_')))
			{	
				case "VALUE":
					$replaced_content=str_replace("{{" . $key . "}}",$value,$replaced_content);
					break;
				
				case "SECTION":
					if ($value!=1)
					{
						//$replaced_content=preg_replace("/\{\{" . $key ."_START\}\}((.|\n)*)\{\{" . $key . "_END\}\}/","",$replaced_content);
						$start_pos=strpos($replaced_content,"{{" . $key . "_START}}");
						$end_pos=strpos($replaced_content,"{{" . $key . "_END}}");
						if ($start_pos!==false && $end_pos!==false)
						{
							$temp_content=substr($replaced_content,0,$start_pos);
							$temp_content.=substr($replaced_content,$end_pos+strlen("{{" . $key ."_END}}"));
							$replaced_content=$temp_content;
							unset($temp_content);
						}
					}
					else 
					{
						$replaced_content=str_replace(array("{{" .$key . "_START}}","{{" .$key . "_END}}"),"",$replaced_content);
					}
					break;
				
				case "REPEAT":
					$merged_total_content="";
					//2016 edit the following line as it screws up the repeat template
					//$start_pos=strpos($replaced_content,"{{" . $key . "_START}}")+strlen("{{" . $key ."_START}}");
					$start_pos=strpos($replaced_content,"{{" . $key . "_START}}");
					$end_pos=strpos($replaced_content,"{{" . $key . "_END}}");
					$repeat_content=substr($replaced_content,$start_pos,$end_pos-$start_pos);
					if (is_numeric($value[0])) 
					{
						$total_cols=$value[0];
						$icnt=1;
					}
					else 
					{
						$total_cols=$icnt=0;
					}
					
					$col=0;
					for ($n=count($value);$icnt<$n;$icnt++)
					{
						$merged_content=$repeat_content;
						if ($total_cols>0)
						{
							if ($col==0) $merged_total_content.='<tr>' . "\n";
						}
						reset($value[$icnt]);
						
						//FOREACH
						//while(list($itemkey,)=each($value[$icnt]))
						foreach ( array_keys($value[$icnt]) as $itemkey )
						{
							$merged_content=str_replace("{{" . $key . "_" . $itemkey . "}}",$value[$icnt][$itemkey],$merged_content);
						}
						
						$merged_total_content.="\n" . $merged_content;
						$col++;
						if($col>=$total_cols)
						{
							$col=0;
						}
					}
					
					if($col<$total_cols) 
					{
						for ($icnt=$col;$col<=$total_cols;$col++)
						{
							$merged_total_content.="<div>&nbsp;</div>\n";
						}
					}
					
					//$replaced_content=preg_replace("/\{\{" . $key ."_START\}\}((.|\n)*)\{\{" . $key . "_END\}\}/",$merged_total_content,$replaced_content);
					$replaced_content=substr($replaced_content,0,$start_pos) . $merged_total_content . substr($replaced_content,$end_pos);
					$replaced_content=str_replace(array("{{" .$key . "_START}}","{{" .$key . "_END}}"),"",$replaced_content);
					break;
			}
		}
		
		$GLOBALS["TPL_CONTENT"]=$replaced_content;
		$GLOBALS["tpl"]=array();
	}
	
	function ucmp($a, $b)
	{
		if($a == $b) 
		{
			return 0;
		}
		return (intval($a) < intval($b)) ? -1 : 1;
	}	
?>
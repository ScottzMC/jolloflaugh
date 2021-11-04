<?php

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<?php

		$check_restrict_customer="";
		$customer_group_id=0;
		$check_restrict_customer=" and ((p.restrict_to_customers='' OR ISNULL(p.restrict_to_customers)) AND (p.restrict_to_groups='' OR ISNULL(p.restrict_to_groups)))";
		if($FSESSION->is_registered('customer_id'))
		{
			$customer_group_id=get_customer_group_id();
			$check_restrict_customer=substr($check_restrict_customer,0,-1)." OR (p.restrict_to_customers like '%". tep_db_input($FSESSION->customer_id) ."%' OR p.restrict_to_groups like '%". tep_db_input($customer_group_id) ."%'))";
		}

		$expires='';
		// //We get the actual server date and time
		$serverDate = date('Y-m-d H:i',getServerDate(false));
		if(ALLOW_PRODUCT_EXPIRY=='yes')	
		{
		$expires=" and p.products_date_available >'" . $serverDate . "' ";
		}else
		{
		$expires='';	
		}
		
		$listing_query = tep_db_query("select 
		p.products_id,
		p.color_code,
		p.products_quantity, 
		p.products_price_break,
		pd.products_name,
		pd.products_description,
		pd.products_number,
		p.products_quantity,
		p.products_model,
		p.section_id,
		p.parent_id,
		p.products_sku, 
		p.products_season,
		p.products_image_1,
		p.products_price, 
		p.products_weight, 
		p.products_sort_order, 
		date_format(p.products_date_added,'%Y-%m-%d') as products_date_added, 
		date_format(p.products_last_modified,'%Y-%m-%d') as products_last_modified, 
		date_format(p.products_date_available, '%Y-%m-%d %H:%i') as products_date_available, 
		p.products_status, 
		p.products_tax_class_id,
		p.manufacturers_id,
		p.restrict_to_groups,
		p.product_type,
		p.master_quantity 
		from " . TABLE_PRODUCTS . " p join ".TABLE_PRODUCTS_TO_CATEGORIES . " p2c on p2c.products_id=p.products_id join ".TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id=p.products_id where p.product_type !='P' and p.product_type !='L' and p.product_type !='Q' and p.products_id >'0' '" . $expires . "' and p2c.categories_id = '" . $cPath . "' and pd.language_id = '" . (int)$FSESSION->languages_id . "' '". $check_restrict_customer."' order by p.products_sort_order,p.products_id asc");
		if(tep_db_num_rows($listing_query) > 0)
		{	
		//print_r($listing_query);
		$category_ga_query = tep_db_query("select categories_GA,categories_quantity_remaining from " . TABLE_CATEGORIES . " where categories_id = '" . $cPath. "' limit 1");

			if (tep_db_num_rows($category_ga_query)) 
			{ 
			$category_ga = tep_db_fetch_array($category_ga_query);
			if($category_ga['categories_GA']>0)
				{//this is a GA category with master quantity?>
					<div>
					<?php 

							if(HIDE_GA_ONLY_QTY=="no")
							{
							echo TEXT_GA_REMAIN_LEFT . ' <span style="color:#ff0000">' . $category_ga['categories_quantity_remaining'] .'</span> ' . TEXT_GA_REMAIN_RIGHT;
							}else
							{
							echo '<strong>There are tickets remaining for this event.</strong>';
							}
					?>
					</div>
				<?php 
				}
			}
			//echo $serverDate;

		echo tep_draw_form('add_multi', 'shopping_cart.php?action=add_multi');	
		
		?>
		
	<div id="ga_listing" class="container  mt-5 pb-2 mb-3">
	
		<div class="row p-3 text-dark bg-light">  
		<div class="col-md-6">
		<strong><?php echo TEXT_TYPE;?></strong>
		</div>
		<div class="col-md-3">
		<strong><?php echo TEXT_PRICE;?></strong>
		</div>
		<div class="col-md-3">
		<strong><?php echo TEXT_QUANTITY;?></strong>
		</div>
		</div>
	
		<?php 
		
	while ($listing = tep_db_fetch_array($listing_query)) 
	{
				//print_r($listing);
				
				$pid = $listing['products_id'];
				$qty = $listing['products_quantity'];
				$color = $listing['color_code'];
				$status = $listing['products_status'];
				$type = $listing['product_type'];
				$pda = $listing['products_date_available'];	
				$pname = $listing['products_name'];
				$pdesc = mb_strimwidth($listing['products_description'], 0, 150, "...");
				$img1 = "small/" . $listing['products_image_1'];
				$price = $currencies->format(tep_add_tax($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])));
				//$price1 = $currencies->format($g['products_price']);
				//$price = substr($price1,0,-1);
				$salemaker_text = '';
				//multi buy check for salemaker
				$sDate=getServerDate(true);
				$sale_query = tep_db_query("select sale_id,sale_specials_condition, sale_deduction_value, sale_deduction_type,choice_text,choice_warning from " . TABLE_SALEMAKER_SALES . " where sale_discount_type='C'  and ((sale_categories_all='' and sale_products_selected='') or sale_categories_all like '%," . tep_db_input($current_category_id) . ",%' or sale_products_selected like '%," . tep_db_input($pid) .",%') and sale_status = '1' and (sale_date_start <='" . tep_db_input($sDate) . "' or sale_date_start = '0000-00-00') and (sale_date_end >= '" . tep_db_input($sDate) . "' or sale_date_end = '0000-00-00') and (sale_pricerange_from <= " . $listing['products_price'] . " or sale_pricerange_from = '0') and (sale_pricerange_to >= " . $listing['products_price'] . " or sale_pricerange_to = '0') order by sale_deduction_value");
				
				if(tep_db_num_rows($sale_query) > 0)
				{ 
				$salemaker_text = TEXT_DISCOUNT_ALERT3;
				}
				
				if (($status <1) or ($qty<1)or($category_ga['categories_GA']>0 && $category_ga['categories_quantity_remaining']<1))
				{
				$plink = '#';
				}
				else 
				{
				$plink = tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $pid);
				}
				
							############################################################
			 ///////////////// alert message, if enabled
			if (defined('ENABLE_LOW_STOCK_ALERT') && ENABLE_LOW_STOCK_ALERT == 'true') 
			{

			$products_query = tep_db_query("select products_id, products_model, products_quantity, product_type from products where product_type='G' and products_quantity <= " . STOCK_REORDER_LEVEL . " order by products_id desc");

			  $num_low_stock = 0;

			  if (tep_db_num_rows($products_query)) 
			  {
				  while ($products = tep_db_fetch_array($products_query)) 
				  {
					$virtual_product = false;
					if (DOWNLOAD_ENABLED == 'true') 
					{
					  $attribute_check_query = tep_db_query("select products_attributes_id from products_attributes where products_id = '" . (int)$products['products_id'] . "'");
					  $num_attributes = tep_db_num_rows($attribute_check_query);
					  if ($num_attributes > 0) 
					  {
						$attribute_check = tep_db_fetch_array($attribute_check_query);
						$virtual_check_query = tep_db_query("select * from products_attributes_download where products_attributes_id = '" . (int)$attribute_check['products_attributes_id'] . "'");
						if (tep_db_num_rows($virtual_check_query) == $num_attributes) $virtual_product = true;
					  }
					}
					if (!$virtual_product) 
					{
					  $num_low_stock++;
					  break;
					}

				  }
				}

				if ($num_low_stock > 0) 
				{
					if(ADMIN_LOW_STOCK_EMAIL_SENT==1)
					{
						$email_text=TEXT_LOW_STOCK_ID.' = Date ID:'. $products['products_model'] .' (' . $products['products_id'] .')';
						tep_mail('Low Stock Message ', STORE_OWNER_EMAIL_ADDRESS, TEXT_LOW_STOCK_ALERT, $email_text, STORE_OWNER.":", STORE_OWNER_EMAIL_ADDRESS);
						$config_query = tep_db_query("select configuration_value from configuration where configuration_key = 'ADMIN_LOW_STOCK_EMAIL_SENT'");
						$config = tep_db_fetch_array($config_query);
						if ($config['configuration_value'] == '1') 
						{
						tep_db_query("update configuration set configuration_value = '0' where configuration_key = 'ADMIN_LOW_STOCK_EMAIL_SENT'");
						}
					}
				} 
			}
			############################################################

			
		if(($status>0)&&($status!=8)&&(SHOW_GA_SOLDOUT=='yes'))
		{ 
			?>
			
			<?php echo'<!-- general admission //-->' . "\n"; 
			
			if($listing['product_type']=='F')
						{	
							$family= '<small>'.FAMILY_TICKET.' x '.FAMILY_TICKET_QTY.'</small>';
						}else{
							$family= '';
						}
			
			?>

			<div id="ga_<?php echo $listing['products_id']; ?>" class="row p-3 effects">
				<div class="col-md-12">
					<div class="row">
					<div class="col-md-2"><?php
					echo '<a href="' . $plink . '"><img src="images/' . $img1 . '" width=' . SMALL_IMAGE_WIDTH . ' alt=' . $pname . ' /></a>'; ?>
					</div>
					<div class="col-md-4"><h4><?php echo '<a href="' . $plink . '"><strong>' .  $pname . '</strong></a>';?>
					<?php 
					if(HIDE_GA_QTY=="no")
					{
					echo "<small>(" . $qty . ")</small>"; 
					}
					?></h4>
					<?php 
					echo $pdesc;
					if((ALLOW_PRODUCT_EXPIRY=='yes')&&(SHOW_PRODUCT_EXPIRY=='yes')){
					echo '<span class="smallText">'.TEXT_PRODUCT_EXPIRES.' '.$pda.'</span>';
					}	  
					?>
					</div>
					<div class="col-md-3">
					<div class="colors color_<?php echo $color; ?>"> <h3><?php echo $price;?></h3></div>
					<small><?php echo $salemaker_text; ?></small>
					</div>
					<div class="col-md-3" style="margin:auto">	  
					<?php
		  
					if(($listing['products_status']<1) or ($listing['products_quantity']<1)or ($category_ga['categories_GA'] > 0) && ($category_ga['categories_quantity_remaining'] < 1))
					{
						//echo tep_template_image_button('', SOLD_OUT);
						echo '<span class="btn btn-primary disabled">&nbsp;' . SOLD_OUT . '&nbsp;</span>';
					}
					else 
					{
						if(ALT_GA_TEMPLATE=='yes')
						{
					
						$qty_box=tep_draw_input_field('qty['.$listing['products_id'].']',0,'id="qty['.$listing['products_id'].']'.'" 
							value="0" 
							style="margin:16px 0 0 2px;padding:0px; text-align: center;min-width:55px;min-height:40px" size="3" onfocus="this.value=\'\';" onBlur="javascript:checkStock(\'\');" onKeyDown="javascript:return numericOnly(event);"');
						
							echo '<style>table{display: inline-table;}</style><table class="table_qty"><tr><td>
							<a href="javascript:add_'.$listing['products_id'].'(-1)">
							<span class="btn btn-primary" style="margin:0"><i class="fa fa-minus"></i></span>
							</a>
							<script type="text/javascript">
								var currentValue_'.$listing['products_id'].' = 0;
								var add_'.$listing['products_id'].' = function(valueToAdd)
								{
								currentValue_'.$listing['products_id'].' += valueToAdd;
								if (currentValue_'.$listing['products_id'].' < 1)
								{
								 currentValue_'.$listing['products_id'].' =0;
								// return;
								}
								document.getElementById(\'qty['.$listing['products_id'].']'.'\').value = currentValue_'.$listing['products_id'].';
								};
								</script>
								</td><td>
								'.$qty_box.'
								</td><td>
								<a href="javascript:add_'.$listing['products_id'].'(1)">
								<span class="btn btn-primary"><i class="fa fa-plus"></i></span>
								</a>
								</td></tr></table>
								
									<script type="text/javascript">
									var currentValue_'.$pid.' = 0;
									var add_'.$pid.' = function(valueToAdd){
										currentValue_'.$pid.' += valueToAdd;
										if (currentValue_'.$pid.' < 1){
										 currentValue_'.$pid.' =0;
										// return;
										}
										document.getElementById(\'qty['.$pid.']'.'\').value = currentValue_'.$pid.';
										};
									</script>
									';
						}
						else
						{
							//Allow BUY NOW to go direct to shopping cart
							if(BUY_NOW_CART == 'yes')
							{
							echo '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id']) . '">' . tep_template_image_button('', IMAGE_BUTTON_BUY_NOW) . '</a>';
							}else
							{
							echo '<a href="' . $plink . '">' . tep_template_image_button('', IMAGE_BUTTON_BUY_NOW) . '</a>';	
							}						
						}
					}
					?>
					</div>
					</div>
				</div>


			
				<?php
				if(ALT_GA_TEMPLATE=='yes')
				{
				?>
				<div class="pull-right"><strong><?php echo TEXT_PLEASE_ADD_TO_CART; ?></strong></div>
				<?php   
				}  
				?>
					<div class="col">
					<?php	
					
					if(ALT_GA_TEMPLATE=='yes')
					{
					echo "<span style=\"float:right;cursor:pointer\" onClick='document.forms[\"add_multi\"].submit()'><strong>" .tep_template_image_button('', IMAGE_BUTTON_IN_CART) . "</strong></span>
					&nbsp;";
					}
					?>
				</div>
			</div>
			<?php
		}
	}
	?>		
				
	</div>
</form><!-- eof general admission //-->

<?php
}
?>
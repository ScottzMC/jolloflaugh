<?php
/*
  osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

Featured Products for GA Updated for osConcert by cartZone UK 
Copyright (c) 2009-2014 osConcert
	
Released under the GNU General Public License 
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

	if (!isset($array))
	{	
		$featured_query = "select p.products_id, pd.products_name, pd.products_description, p.products_image_1,p.products_title_1,p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added from " . TABLE_PRODUCTS . " p  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id and pd.language_id = '" . (int)$FSESSION->languages_id . "' where p.products_status = '1' and f.status = '1' order by rand($mtm) limit 4";
		$featured_result_query = tep_db_query($featured_query);
		while ($featured_result = tep_db_fetch_array($featured_result_query)) 
		{
		$array[] = array('id' => $featured_result['products_id'],
									  'name' => $featured_result['products_name'],
									  'image' => $featured_result['products_image_1'],
									  'title'=>$featured_result['products_title_1'],
									  'price' => $featured_result['products_price'],
									  'specials_price' => $featured_result['specials_new_products_price'],
									  'tax_class_id' => $featured_result['products_tax_class_id'],
									  'products_description' => $featured_result['products_description']);
		}
		  
	}
		
		// if (($featured_result['products_image_1'] == 'NULL') or ($featured_result['products_image_1'] == '')) 
		// {
		// $cat_image="theatre.png";
		// }else
		// {
		//$cat_image=tep_image(DIR_WS_IMAGES . '/big/' . $array[$z]['image'], $array[$z]['name'], '','');
		//}
			
	if (sizeof($array) == '0') 
	{
			  
	?>
		<span style="text-align:center"><?php echo TEXT_NO_FEATURED_PRODUCTS; ?></span>
	<?php
	} 
	else 
	{
			for($z=0; $z<sizeof($array); $z++) 
			{
					  if ($array[$z]['specials_price']) 
					  {
						$products_price = '<s>' .  $currencies->display_price($array[$z]['price'], tep_get_tax_rate($array[$z]['tax_class_id']),1,true) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($array[$z]['specials_price'], tep_get_tax_rate($array[$z]['tax_class_id'])) . '</span>';
					  } else 
					  {
						$products_price = $currencies->display_price($array[$z]['price'], tep_get_tax_rate($array[$z]['tax_class_id']),1,true);
					  }
					  if(strlen(strip_tags($array[$z]['products_description']))>150)
					  {
							$description=substr(strip_tags($array[$z]['products_description']),0,150)."...";
					  }else 
					  {
							$description=strip_tags($array[$z]['products_description'],'<br>');
					  }
					  

				$new_prods_content .= '
				<div class="col-lg-3 col-md-4 col-sm-6">
					<div class="featured-item wow fadeInUp">
					  <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $array[$z]['id'], 'NONSSL') . '">' . 
								tep_image(DIR_WS_IMAGES . $array[$z]['image'], $array[$z]['name'], '', '') . 
							'<div class="featured-overlay">
						  <div class="featured-info"><h2 class="wow fadeInUp">' . $array[$z]['name'] . '</h2></div>
						</div>
					</a>
					</div>
				  </div>
					';

			  $col ++;
			}

		if(SHOW_MAIN_FEATURED_PRODUCTS == 'true')
		{
		?>
		<?php 
		if(DISABLE_OVERLAY =='yes')
		{
		?>
		<style>
		#featured .featured-overlay {
		opacity: 0;
		}
		</style>
		<?php
		}
		?>
		<section id="featured" class="wow fadeInUp">
		<div class="container">
		<div class="row no-gutters">
		<?php 
		echo $new_prods_content;
		?>
		</div>
		</div>
		</section>
		<?php
		}
	}
	?>
<?php

		
	$page=" and c.parent_id = '0'";

	$sql_order_by = FEATURED_CATEGORIES_ORDERBY;
	

	$featured_categories_query = tep_db_query("select * from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status > '0' ".$page." and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "' order by c." .$sql_order_by . " ASC");
	
	$num_featured_categories = tep_db_num_rows($featured_categories_query);


	if ($num_featured_categories > 0) 
	{
		while ($featured_categories = tep_db_fetch_array($featured_categories_query)) 
		{
			$categories_name=$featured_categories['categories_name'];
			$categories_description = $featured_categories['categories_description'];
			//$categories_description = ltrim(substr($categories_description, 0, 75) . '...'); //Trims and Limits the desc
			$cPath_new = tep_get_path($featured_categories['categories_id']);
			$cPath_end = $featured_categories['categories_id'];
			$categories_heading_title = $featured_categories["categories_heading_title"];
			$heading_venue=$featured_categories['concert_venue'];
			$categories_time=$featured_categories['concert_time'];
			
			require(DIR_WS_FUNCTIONS.'/date_formats.php');
			
			if($plan_id==9)
			{
			$heading_date='';
			$heading_time='';
			$select=IMAGE_BUTTON_BUY_NOW;
			}else
			{
			$heading_date='<h6>Date: '.$categories_date.'</h6>';
			$heading_time='<h6>Time: '.$categories_time.'</h6>';
			$select=TEXT_SELECT_TICKETS;
			}
		
			$featured_subcategories_content .= '
		  	 <div id="sub_listing" class="col-lg-12 col-md-12">
			 <div class="row p-3 effects">
					
				<div class="col-md-4">

				'.$heading_date.'
				'.$heading_time.'

				</div>
				<div class="col-md-4">
				<h5>'.$heading_venue.'</h5>

				</div>
				<div class="col-md-4">

				<a class="pull-right" href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $featured_categories['categories_id']) . '">' . tep_template_image_button('', $select) . '</a>

				</div>
				  </div>
				  </div>
			';
		}
	}

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
	<section id="featured_subs" class="wow fadeInUp">
	<div class="container">
	<div class="row no-gutters">
	<?php 
	echo $featured_subcategories_content; 
	?>
	</div>
	</div>
	</section>
	<?php 
	if(SHOW_FEATURED_CATEGORIES == 'true')
	{
	require_once(DIR_WS_MODULES  . FILENAME_FEATURED_CATEGORIES);
	}
	?>  
	<br>
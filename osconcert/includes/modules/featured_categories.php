<?php
/*
	osConcert Seat Booking Software
    http://www.osconcert.com
    Copyright (c) 2021 osConcert
	
	Released under the GNU General Public License 
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

	$category['categories_image']='theatre.png';
	//about images
	if (($category['categories_image'] == 'NULL') or ($category['categories_image'] == '')) //Warning: Illegal string offset
	{
		if(USE_CINE=='yes')
		{
		$cat_image="coming_soon_poster.jpg";
		}else
		{//Theatre
		$cat_image="theatre.png";	
		}
	}else
	{
		$cat_image=$category['categories_image'];
	}

	$page=" and c.parent_id = '0'";
	
	

	$sql_order_by = FEATURED_CATEGORIES_ORDERBY;
	
	//  and c.date_id>0
	//and  c.date_id !=''

	$featured_categories_query = tep_db_query("select * from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status > '0'".$page." and  c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "' order by c." .$sql_order_by . " ASC");
	
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
			$categories_GA=$featured_categories['categories_GA'];
			$categories_quantity_remaining=$featured_categories['categories_quantity_remaining'];
			//$parent_id = $categories['parent_id'];
			//$status = $categories['categories_status'];

		$time='';
		$digit_time='';
		// date() expects parameter 2 to be integer, string given
		$digit_time = date('Hi', strtotime($time));
		$categories_date = $featured_categories['concert_date'];
		//$categories_date = strtotime($featured_categories["concert_date"]);
		
		require(DIR_WS_FUNCTIONS.'/date_formats.php');

		if(!strtotime($categories_date))
		{
			// it's not in date format
			$heading_date = $categories_date;//gives concert_date
		}
		$col=0;
		
			if($categories_GA==1 && HIDE_GA_ONLY_QTY=='no')
			{//this is a GA category with master quantity
				$count=$categories_quantity_remaining;
			}else
			{
				
				$countGA_query = tep_db_query("select parent_id,products_quantity,product_type,products_ordered FROM " . TABLE_PRODUCTS . "  WHERE  parent_id = '" . $cPath_end . "'");
				while ($result = tep_db_fetch_array($countGA_query)) 
				{
				$products_status=$result['products_status'];
				$product_type=$result['product_type'];
				$products_quantity=$result['products_quantity'];
				$products_ordered=$result['products_ordered'];
				}
				
				//count seats reserved
				if (SUBCATEGORY_COUNT == 'reserved')
				{
				$status=0;
				$count_text=RESERVED;
				}
				if (SUBCATEGORY_COUNT == 'remaining')
				{
				$status=1;
				$count_text=REMAINING;
				}

				$count_query = tep_db_query("select products_quantity,product_type,products_ordered FROM " . TABLE_PRODUCTS . "  WHERE products_status = '" . $status . "' and parent_id = '" . $cPath_end . "'");
				$sub_count = tep_db_num_rows($count_query);

				if ($product_type=='P')
				{
				$count=$sub_count;
				} 
				elseif ($product_type=='G') 
				{	
					//count seats reserved
					if (SUBCATEGORY_COUNT == 'reserved')
					{
					$status=0;
					$count_text=RESERVED;
					$count= $products_ordered;
					}
					if (SUBCATEGORY_COUNT == 'remaining')
					{
					$status=1;
					$count_text=REMAINING;
					$count= $products_quantity;
					}
				}
			}
		 $ticket_count='';
		// if (SUBCATEGORY_COUNT=='true')
		// {
			// $ticket_count='';
			// }
			// else
			// {
			// $ticket_count='<h5>(' . $count . ')</h5>';
		// }			
		
		if (($featured_categories['categories_image'] == 'NULL') or ($featured_categories['categories_image'] == '')) 
		{
			if(USE_CINE=='yes')
			{
			$cat_image="coming_soon_poster.jpg";
			}else
			{//Theatre
			$cat_image="theatre.png";	
			}
		}else{
		$cat_image=$featured_categories['categories_image'];
		}
		//Featured Buttons
		if(FEATURED_CATEGORY_BUTTONS=='yes')
		{
		$button="btn btn-primary";
		$featured_categories_content .= '
		<div class="col-lg-3 col-md-4 col-sm-6" style="text-align:center;">
		<div class="featured-item">
		<a class="' . $button . '" href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $featured_categories['categories_id']) . '">
		
		<h6>' . $heading_date . '</h6></a>
		</div>
		</div>
		';
		}
		else
		{
		$featured_categories_content .= '
		<div class="col-lg-3 col-md-4">
		<div class="featured-item">
		<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $featured_categories['categories_id']) . '">' . 
		tep_image(DIR_WS_IMAGES . $cat_image, $categories_name, '', '') . 
		'<div class="featured-overlay">
		<div class="featured-info">
		<h3>' . $categories_name . '</h3>
		<h5>' . $heading_date . '</h5>
		<h5>' . $heading_time . '</h5>
		' . $ticket_count . '
		</div>
		</div>
		</a>
		</div>
		<h6 class="fc_date">' . $heading_date . '</h6>
		<h6 class="fc_date">' . $heading_time . '</h6>
		</div>
		';
		}
		$col ++;
		
		// $featured_categories_content .= '
		// <div id="sub_listing" class="col-lg-12 col-md-12">
		// <div class="row p-3 effects">
		// <div class="col-md-4">

		// '.$heading_date.'
		// '.$heading_time.'

		// </div>
		// <div class="col-md-4">
		// <h5>'.$heading_venue.'</h5>

		// </div>
		// <div class="col-md-4">

		// <a class="pull-right" href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $featured_categories['categories_id']) . '">' . tep_template_image_button('', $select) . '</a>

		// </div>
		// </div>
		// </div>
		// ';
		// }
		// $col ++;
	}


		if(SHOW_MAIN_FEATURED_CATEGORIES == 'true')
		{ 
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
			//echo '<div class="text-center"><a class="btn btn-primary" href="search_events.php">Search</a></div><br>'; 
			?>
		<section id="featured">
		<div class="container">
		<div class="row no-gutters">
		<?php 
		echo $featured_categories_content; 
		?>
		</div>
		</div>
		</section><br>
		<?php
		}
	}
	else
	{
	echo "<h4 class=\"text-center\">";
	echo TEXT_NO_FEATURED_CATEGORIES;
	echo "</h4>";
	}
	//require_once(DIR_WS_MODULES  . 'featured_subcategories.php');
	?>
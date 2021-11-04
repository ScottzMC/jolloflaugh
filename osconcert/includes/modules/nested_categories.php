<?php 

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<?php
	#################################################################
	//Get the data for the cPath
	$status='';
	$plan_id='';
	$status = $category['categories_status'];
	$plan_id = $category['plan_id'];
	$categories_content='';
	######################################################
	//code to get all parents
	tep_get_parent_categories($result_check,$cPath);
	// set the variable
	$show_the_plan=0;

	if(is_array($result_check))
	{
		foreach($result_check as $key => $value)
		{
			$parent_categories_query = tep_db_query("select categories_status from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$value. "'");
			while ($parent_categories = tep_db_fetch_array($parent_categories_query)) 
			{
			if ($parent_categories['categories_status'] == 0) $show_the_plan=0;
			}
		}
	} //eof code to get all parents
	$show_the_plan=1;
	//echo $show_the_plan;

	//if the category status is enabled
	if ($status !=0 && $show_the_plan==1)
	{
		//now there's a way to isolate the top category		
		if($plan_id<9)
		{
		//include(DIR_FS_CATALOG.DIR_WS_MODULES. '/double_boxes.php');
		//we run the seat plan class
		require_once(DIR_WS_TEMPLATES.TEMPLATE_NAME.'/content/seatplan.php');
		}
		//do we allow some GA products?
		require_once(DIR_WS_TEMPLATES.TEMPLATE_NAME.'/content/products_ga.php');
	}else
	{
		if(SHOW_DISABLED_CATEGORIES=='true')
		{
		echo "<h4>" . EVENT_DISABLED_MESSAGE . "</h4>";
		}
		//allow BO to see the plan even when it is disabled
		if (($_SESSION['BoxOffice']== 999)or($_SESSION['customer_country_id']==999))	
		{
		require_once(DIR_WS_TEMPLATES.TEMPLATE_NAME.'/content/seatplan.php');
		//require_once(DIR_WS_TEMPLATES.TEMPLATE_NAME.'/content/products_ga.php');
		}else
		{
			//echo 'No Plan';
		}
	}
		
		if ($parent_id==0)
		{			
			$cat = $cPath; 
			}
			else
			{
				if(USE_CINE=='yes')
				{	
					$cat = $cPath; 
				}else{
					$cat = $parent_id;	
				}
		}
		
	//Now we are going to show the gallery
	$nested_categories_query = tep_db_query("select * from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status > '0' and c.parent_id = '" . (int)$cat . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "' order by c.sort_order, cd.categories_id");
	
	$num_categories = tep_db_num_rows($nested_categories_query);

	if ($num_categories > 0) 
	{

		while ($nested_categories = tep_db_fetch_array($nested_categories_query)) 
		{
			$categories_name = $nested_categories['categories_name'];	
			$categories_description = $nested_categories['categories_description'];	
			//$categories_description = ltrim(substr($categories_description, 0, 75) . '...'); //Trims and Limits the desc
			$categories_heading_title = $nested_categories["categories_heading_title"];
			$concert_unix = $nested_categories['concert_date_unix'];
			$categories_venue = $nested_categories['concert_venue'];
			$categories_date = $nested_categories['concert_date'];
			$categories_time = $nested_categories['concert_time'];
			$cPath_new = tep_get_path($nested_categories['categories_id']);
			$cPath_end = $nested_categories['categories_id'];
			
			require(DIR_WS_FUNCTIONS.'/date_formats.php');
				
				//about images
				if (($nested_categories['categories_image'] == 'NULL') or ($nested_categories['categories_image'] == '')) 
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
					$cat_image=$nested_categories['categories_image'];
				}
				//system real time and date
				$eventdate = date('Ymd', $concert_unix);
				$event = $eventdate.$digit_time;
				$system = date('Hi');
				$today = date('Ymd');//date("F j, Y, g:i a");
				$now=$today.$system;

				//Bootstrap Button
				if(USE_CIRCLE_BUTTONS=='yes')
				{
				$button="btn btn-category-circle";
				}else
				{
				$button="btn btn-primary";	
				}

			if(NESTED_CATEGORY_BUTTONS=='yes')
			{
				$categories_content .= '
				<div class="col-lg-3 col-md-4 col-sm-6" style="padding:10px;text-align:center;display:true">
				<div class="portfolio-item">
				<div class="portfolio-overlay">
				<div class="portfolio-info">
				<a class="' . $button . '" href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">
				' . $categories_name . '</a>
				</div>
				</div>
				</div>
				</div>
				';	
			}
			else
			{
				if(USE_CINE=='yes')
				{
					if($event>$now)
					{	
					//can be configured for images  
					$categories_content .= '
					<div class="col-lg-4 col-md-6">
					<div class="featured-item wow fadeInUp">
					<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cPath_end) . '">' . 
					tep_image(DIR_WS_IMAGES . 'big/' . $cat_image, $categories_name, '', '') . 
					'<div class="featured-overlay">
					<div class="featured-info">
					<h3 class="wow fadeInUp" style="margin:0">' . $categories_name . '</h3>
					<h4>' . $heading_venue .': <br>' . $heading_date.' '.$heading_time . '</h4>
					</div></div></a></div>
					<h4 class="wow fadeInUp" style="margin:0;text-align:center;">' . $categories_name . '<br><span class="smallText">' . $categories_name . ' </span><br> '  . $heading_venue .': ' . $heading_time . '</h4>
					</div>';
					}
				}
				else
				{
					// $categories_content .= '

					// <div class="col-lg-3 col-md-4">
					// <div class="featured-item wow fadeInUp">
					// <a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $featured_categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . $cat_image, $categories_name, '', '') . '<div class="featured-overlay">
					// <div class="featured-info">
					// <h2 class="wow fadeInUp">' . $categories_name . '</h2>
					// <h5>' . $heading_date . '</h5>
					// <h5>' . $heading_time . '</h5>
					// </div>
					// </div>
					// </a>
					// </div>
					// </div>
					// ';
					// // $categories_content .= '
					// // <div class="col-lg-4 col-md-6">
					// // <div class="featured-item wow fadeInUp">
					// // <a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . 
					// // tep_image(DIR_WS_IMAGES . 'big/' . $cat_image, $categories_name, '', '') . 
					// // '<div class="featured-overlay">
					// // <div class="featured-info">
					// // <h3 class="wow fadeInUp" style="margin:0">' . $categories_name . '</h3>
					// // <h4>' . $heading_venue .': <br>' . $heading_date.' '.$heading_time . '</h4>
					// // </div></div></a></div>
					// // </div>';
					// }
					
					// }
					
						if($plan_id==9)
						{
						$heading_date='';
						$heading_time='';
						$select=IMAGE_BUTTON_BUY_NOW;
						}else
						{
						$heading_date='<h6>Date: '.$concert_date.'</h6>';
						$heading_time='<h6>Time: '.$concert_time.'</h6>';
						$select=TEXT_SELECT_TICKETS;
						}

					$categories_content .= '
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

					<a class="pull-right" href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_template_image_button('', $select) . '</a>

					</div>
					</div>
					</div>
					';
				
				}
			}
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
	if($categories_content !=''){
	?>
	<section id="featured" class="wow fadeInUp">
	<div class="container">
	<div class="row no-gutters">
	<?php 
	echo $categories_content; 
	?>
	</div>
	</div>
	</section>
	<?php 
	
	}
	if(SHOW_FEATURED_CATEGORIES == 'true')
	{
	require_once(DIR_WS_MODULES  . FILENAME_FEATURED_CATEGORIES);
	}
	?>  
	<br>
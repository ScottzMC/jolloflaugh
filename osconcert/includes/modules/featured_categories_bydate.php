<?php
/*
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	Copyright (c) 2003 osCommerce 

	osConcert, Online Seat Booking 
	https://www.osconcert.com
	Copyright (c) 2009-2020 osConcert
	Released under the GNU General Public License 
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

	 if (isset($_GET['date_start_unix']) && is_numeric($_GET['date_start_unix']) && $_GET['date_start_unix'] > 0)
	 {
		 $start   =  " and c.concert_date_unix >= '" . $_GET['date_start_unix']."' ";
		 $start_text = TEXT_FROM . $_GET['date_start'];
		 
	 }else
	 {
		 //$messageStack->add('featured_categories_bydate',TEXT_NO_FEATURED_CATEGORIES_START);
		 //echo $messageStack->output('featured_categories_bydate'); 
		 $start = "";
		 $start_text = TEXT_ALL_EVENTS;
	 }
	 
	 if (isset($_GET['date_end_unix']) && is_numeric($_GET['date_end_unix']) && $_GET['date_end_unix'] > 0)
	 {
		 $end   = " and c.concert_date_unix <= '" . $_GET['date_end_unix'] ."' ";
		 $end_text = TEXT_TO . $_GET['date_end'];
	 }else
	 {
		 $end = "";
		 $end_text = ""; 
	 }
	$page=""; 
	$unix = $_SERVER['REQUEST_TIME'];
	$page=" and c.parent_id=0 ";
	$featured_categories_query = tep_db_query("select * from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status > '0' " . $start . $end .  " ".$page." and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "' order by c.concert_date_unix LIMIT 24");
	
	$num_featured_categories = tep_db_num_rows($featured_categories_query);

	if ($num_featured_categories > 0) 
	{
		while ($featured_categories = tep_db_fetch_array($featured_categories_query)) 
		{
			$categories_name = $featured_categories['categories_name'];
			$categories_title = $featured_categories['categories_heading_title'];
			$categories_description = $featured_categories['categories_description'];
			//$categories_description = ltrim(substr($categories_description, 0, 75) . '...'); //Trims and Limits the desc
			$categories_venue=$featured_categories['concert_venue'];
			$categories_date = $featured_categories["concert_date"];
			$categories_time=$featured_categories['concert_time'];
			$time=strtotime($categories_time);
			$event_time = date('g:i a', $time);
			
		require(DIR_WS_FUNCTIONS.'/date_formats.php');

		$col=0;
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
						$button="btn btn-primary";
						if(FEATURED_CATEGORY_BUTTONS=='yes')
						{
							$featured_categories_content .= '
							<div class="col-lg-3 col-md-4 col-sm-6" style="text-align:center;border: red 0px solid;">
								<div class="featured-item">
									  <a class="' . $button . '" href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $featured_categories['categories_id']) . '">
									  <h2 class="wow fadeInUp" style="font-size:14px">' . $categories_name . '</h2>
									  <h6>' . $heading_date . '</h6>
									  
									  </a>
								</div>
								</div>
								';
						}else
						{
							$featured_categories_content .= '
						
						   <div class="col-lg-3 col-md-4">
						   <!--<div class="col-lg-2 col-md-3">-->
							<div class="featured-item wow fadeInUp">
							  <a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $featured_categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . $cat_image, $categories_name, '', '') . 
								'<div class="featured-overlay">
								  <div class="featured-info">
								  <h2 class="wow fadeInUp">' . $categories_name . '</h2>
								  <h5>' . $heading_date . '</h5>
								  <h5>' . $heading_time . '</h5></div>
								</div>
							</a>
							</div>
							<h6 class="wow fadeInUp" style="margin:0;text-align:center;text-shadow:none;color:#000">' . $categories_name . '<br>' . $heading_date .'<br>' . $heading_venue . ' '. $heading_time . '</h6>
						</div>';
						}
						$col ++;
		}
	
	if(SHOW_MAIN_FEATURED_CATEGORIES == true)
	{
	?>
	<?php if(DISABLE_OVERLAY=='yes')
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
	<div class="container-fluid">
	<div class="row"><h3>
	 <?php 
	echo $start_text . $end_text. '   <a class="btn btn-primary" href="'.tep_href_link(FILENAME_SEARCH_EVENTS).'">' . HEADER_TITLE_SEARCH_AGAIN . '</a>';
	//echo ' <a class="btn btn-primary" href="featured_categories_bydate.php">ALL</a>';
	?>
	</h3>
	</div>
	<br>
	<div class="row no-gutters">
    <?php 
	echo $featured_categories_content;
	?>
	</div>
	</div>
	</div>
	</section>
	<?php
	}
  }
  else
  {
	echo "<p style=\"text-align:center\">";
	echo '<a class="btn btn-primary" href="'.tep_href_link(FILENAME_SEARCH_EVENTS).'">' . HEADER_TITLE_SEARCH_AGAIN . '</a>';
	//echo TEXT_NO_FEATURED_CATEGORIES;
	echo "</p>";
  }
?>
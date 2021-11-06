<?php
/*
	
	osConcert, Online Seat Booking 
  	http://www.osconcert.com
  	Copyright (c) 2009-2020 osConcert

	Released under the GNU   General Public License
*/  
// Set flag that this is a parent file
define( '_FEXEC', 1 );
	require('includes/application_top.php'); 
//Box Office Only
		if (!$_SESSION['customer_country_id']==999) 
		{
		tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
		}
		
## messages
### PHP type of steps
/*     if($_POST['cats_avail']){
		 if (count($_POST['cats_avail']) == 0){
			$messageStack->add('multi_block','Please select at least one category');
			load_category_list();
				}else{
			load_seatplan_edit();
				}
		 }
		 else{
			 load_category_list();
		 }
		 */
		 
function load_category_list()
{
	global $FSESSION, $categories_content;

	$categories_query = tep_db_query("select * from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status > '0' and c.parent_id = 0 and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$FSESSION->languages_id . "' order by c.sort_order, cd.categories_id");
	

	$num_categories = tep_db_num_rows($categories_query);
		if ($num_categories > 0) 
		{
			if(DESIGN_MODE=='no'){
			$categories_content = '
			<div style="width:100%">
			<div class="container step1">
			<h2>Step 1: select shows</h2>
			<div class="col-md-offset-5 col-md-5 check-row">
			<div class="form-group">
			<div class="checkbox">
			<label>
			<input type="checkbox" class="check" id="checkAll"> '.TEXT_CHECK_ALL.'
			</label>
			</div></div></div>
			';
		
			while ($categories = tep_db_fetch_array($categories_query)) 
			{

			$date = strtotime($categories["date_id"]);
			$thedate=$categories['concert_date'];
			$cPath=$categories['categories_id'];
			
			//if($cPath<65){
		//	echo '<link href="templates/newzone/assets/css/seatplan2.css" rel="stylesheet">';
		//	}
			
			$categories_content .= '
		    <div class="checkbox">
               <label>
					<input class="check" type="checkbox" name="cats_avail[]" value="'.$categories['categories_id'].'">
					' . $categories['categories_name'] .' ' . $thedate . '					
				</label>
			</div>
			';

			}

			$categories_content.='<button disabled class="btn btn-primary nextbtn">'.TEXT_BUTTON_NEXT.'</button> .
								  </div>
								 </div>';

		}else{
		echo '<h4>'.TEXT_NO_BO_DESIGN.'</h4>';
		$categories_content = TEXT_NOTHING_FOUND;
		}
		
		return $categories_content;
		}
}// end load_category_list
		 
	require(DIR_WS_LANGUAGES . $FSESSION->language . '/multi_block.php');  

	$breadcrumb->add(NAVBAR_TITLE, tep_href_link('multi_block.php', '', 'SSL')); 

	$content = 'multi_block';  

	//load_category_list();

	$javascript = ''; 
	//require(DIR_WS_INCLUDES.'http.js');
	require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);  
	?>
	<script src="./includes/javascript/multi_block.js" type="text/javascript">
	</script>
	<?php
	require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
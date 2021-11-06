<?php 
/*
	osConcert, Online Seat Booking 
	https://www.osconcert.com
	Copyright (c) 2009-2020 osConcert
	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

	######################################################
		$id=$products_id;
		require_once(DIR_WS_INCLUDES . 'functions/categories_lookup.php');
		#####################################################
		// // call function to handle the order names
		list($heading_name, $heading_venue,  $heading_date, $heading_time, $heading_title) = categories_lookup();
		######################################################	

		$template_content=tep_load_template_content('seatplan.tpl.php');
			
		$template_details["VALUE_NO_PRODUCT"]=NO_PRODUCTS;
		
		//about images
		if (($category['categories_image'] == 'NULL') or ($category['categories_image'] == '')) 
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

		$category_time=$category['concert_time'];
		
		$cPath = $current_category_id;
		
		foreach ($cPath_array as $item) 
		{
        if((SHOW_SUBCATEGORIES=='yes')or($parent_id==0))
		{			
			$cat = $item;   //top
		}
		else
		{		
			$cat = $cPath; 	//current
		}
        break;
		}

		$categories_query = tep_db_query("select * from  " . TABLE_CATEGORIES_DESCRIPTION . "  WHERE categories_id = '".$cat."' and language_id = '" . (int)$FSESSION->languages_id . "'");

		while ($categories = tep_db_fetch_array($categories_query)) 
		{
			$categories_name=$categories['categories_name'];
			$categories_heading_title = $categories["categories_heading_title"];
			$categories_description = $categories['categories_description'];
			$categories_venue = $categories['concert_venue'];
			$categories_date = $categories['concert_date'];
			$categories_time = $categories['concert_time'];
		}	
			
		require(DIR_WS_FUNCTIONS.'/date_formats.php');
		
			
			$template_details["VALUE_CATEGORIES_NAME"]=$categories_name;
			$template_details["VALUE_CATEGORIES_TITLE"]=$categories_heading_title;
			$template_details["VALUE_CATEGORIES_DESC"]=$categories_description;
			$template_details["VALUE_CATEGORIES_VENUE"]=$categories_venue;
			$template_details["VALUE_CATEGORIES_DATE"]=$heading_date;
			$template_details["VALUE_CATEGORIES_TIME"]=$heading_time;

		//this data comes from index.php for the current category ID
		$template_details["VALUE_CATEGORY_NAME"]=$category["categories_name"];
		$template_details["VALUE_CATEGORY_TITLE"]='<h2>'.$category["categories_heading_title"].'</h2>';
		$template_details["VALUE_CATEGORY_DESC"]=$category["categories_description"];
		$template_details["VALUE_CATEGORY_VENUE"]=$category["concert_venue"];
		$template_details["VALUE_CATEGORY_DATE"]=$heading_date;//$category["concert_date"];
		$template_details["VALUE_CATEGORY_TIME"]=$heading_time;//$category["concert_time"];
		
		
		$template_details["VALUE_CATEGORY_IMAGE"]=$cat_image;
		
		$template_details["VALUE_TEXT_TYPE"]=TEXT_TYPE;
		$template_details["VALUE_TEXT_PRICE"]=TEXT_PRICE;
		$template_details["VALUE_TEXT_QUANTITY"]=TEXT_QUANTITY;
		$template_details["VALUE_SALEMAKER"]='';
		$template_details["VALUE_GA_TOTAL"]='';
		$template_details["VALUE_SPACER"]="&nbsp;&nbsp;";
		$spacer="&nbsp;&nbsp;";

		$template_details["VALUE_FORM_START"]='';
		
		$template_details["REPEAT_PRODUCT_LIST"]=$product_repeat;
		unset($product_repeat);

		$template_details["SECTION_PRODUCT"]=1;
		$template_details["SECTION_NO_PRODUCT"]=0;

		$replaced_content=$template_content;
		$template_name = "";

		$scnt=0;
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
						$start_pos=strpos($replaced_content,"{{" . $key . "_START}}");
						$end_pos=strpos($replaced_content,"{{" . $key . "_END}}");
						$temp_content=substr($replaced_content,0,$start_pos);
						$temp_content.=substr($replaced_content,$end_pos+strlen("{{" . $key ."_END}}"));
						$replaced_content=$temp_content;
						unset($temp_content);
					} else 
					{
						$replaced_content=str_replace(array("{{" .$key . "_START}}","{{" .$key . "_END}}"),"",$replaced_content);
					}
					break;
					//I think this is it
				case "REPEAT":
					$start_pos=strpos($replaced_content,"{{" . $key . "_START}}")+strlen("{{" . $key ."_START}}");
					$end_pos=strpos($replaced_content,"{{" . $key . "_END}}");
					$repeat_content=substr($replaced_content,$start_pos,$end_pos-$start_pos);
					$merged_total_content='' . "\n";
					$col=0;
					$row=0;
					for ($icnt=0,$n=count($value);$icnt<$n;$icnt++)
					{
						$merged_content='';
						if ($col==0 || $ttotal_cols==1)
						{
							$merged_content.='';
						}
							$merged_content.='' . $repeat_content;

						reset($value[$icnt]);

						foreach ( array_keys($value[$icnt]) as $itemkey )
						{
							$merged_content=str_replace("{{" . $key . "_" . $itemkey . "}}",$value[$icnt][$itemkey],$merged_content);
						}
						$col++;
						if ($col==$ttotal_cols) 
						{
							$col=0;
							$row++;
						}
						if ($col==0 || $ttotal_cols==1) 
						$merged_content.='';
						$merged_total_content.="\n" . $merged_content;
					}
					
					$merged_total_content.="";
					$replaced_content=substr($replaced_content,0,$start_pos) . $merged_total_content . substr($replaced_content,$end_pos);
					unset($merged_total_content);
					$replaced_content=str_replace(array("{{" .$key . "_START}}","{{" .$key . "_END}}"),"",$replaced_content);
			}
			$scnt++;
		}
		echo $replaced_content;
		unset($replaced_content);

		require_once(DIR_WS_MODULES  . 'nested_categories.php');
		//require_once(DIR_WS_MODULES  . 'featured_subcategories.php');

		//require_once(DIR_WS_TEMPLATES.TEMPLATE_NAME.'/seatmap.php');

?>
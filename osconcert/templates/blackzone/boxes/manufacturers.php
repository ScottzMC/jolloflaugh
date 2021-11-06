<?php
/*
  osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
//###############################################
/*  if ( (USE_CACHE == 'true') && !defined('SID')) 
	{
    echo tep_cache_manufacturers_box();
	} else { */
//##############################################

	//$manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
	
	
	
	$manufacturers_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name,m.manufacturers_image, mi.manufacturers_url as display_name from " . TABLE_MANUFACTURERS . " m,".TABLE_MANUFACTURERS_INFO." mi where m.manufacturers_id=mi.manufacturers_id  and mi.languages_id='".(int)$FSESSION->languages_id."' order by m.manufacturers_name asc");
	if (tep_db_num_rows($manufacturers_query)>0 && $cPath == NULL)
	{// only show on front page remove cPath == NULL for global display
?>
<!-- manufacturers <script src="//code.jquery.com/jquery-1.11.1.min.js"></script> //-->
<?php
	if(!defined('BOX_HEADING_MANUFACTURERS'))define('BOX_HEADING_MANUFACTURERS', 'Filter by location');

	// template for each row on a simple list
	$template_html='<div class="{{CLASS}}">{{SPACER}}{{ICON}}<input type="checkbox" rel="show_hide_{{MAN_ID}}" /> {{NAME}}</div><br>
					';
	$replace_array=array();
	$replace_array["INITIAL_WIDTH"]=$INITIAL_WIDTH;
	$replace_array["CLASS"]="cat_filter";
        
	$replace_array["SPACER"]="";
	$replace_array["ICON"]=$CONTENT_ICON;
	$replace_html=$template_html;

	reset($replace_array);
	//FOREACH
	//while(list($key,)=each($replace_array))
	foreach($replace_array as $key=>$value) 
	{
		$replace_html=str_replace("{{" . $key . "}}",$replace_array[$key],$replace_html);
	}

		// Display a list
		$manufacturers_list = '';
		while ($manufacturers_values = tep_db_fetch_array($manufacturers_query)) 
		{
			$replace_string=$replace_html;
                        
			$replace_string=str_replace("{{LINK_1}}",tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers_values['manufacturers_id'], 'NONSSL'),$replace_string);
            $replace_string=str_replace("{{MAN_ID}}",$manufacturers_values['manufacturers_id'],$replace_string);
			$replace_string=str_replace("{{NAME}}",$manufacturers_values['display_name'],$replace_string);
			
            //$replace_string=str_replace("{{NAME}}",substr($manufacturers_values['display_name'], 0, 256),$replace_string);            
                        
			$manufacturers_list.=$replace_string;
		}
        $manufacturers_list.= "<div><span class='checkall btn btn-primary'>".TEXT_LOCATION_RESET."</span></div>";

	echo '<div class="card box-shadow">';
	echo '<div class="card-header">';
	echo '<strong>';
	echo BOX_HEADING_MANUFACTURERS;
	echo '</strong>';
	echo '</div>';
	echo '<div class="list-group">';
	echo '<div>' . $manufacturers_list . '</div>';
	echo '</div>';
	echo '</div>';
	echo '<br class="clearfloat">';
?>
          <script>
		  $(document).ready(function () {
			  $('.checkall').hide();

					$('.cat_filter').find('input:checkbox').live('click', function () {
						
						//$('.nav-filter > li').hide(500);
						$('.featured-item').hide(500);
						$('.checkall').show(500);
						$('.cat_filter').find('input:checked').each(function () {
						   // $('.nav-filter > li#' + $(this).attr('rel')).show(1000);
							$('div[rel="'+ $(this).attr('rel') +  '"]').show(1000);
						//alert($(this).attr('rel'));
						   });
					});
				});     
				$(function () {
			$('.checkall').on('click', function () {
				$('.cat_filter').find('input:checked').each(function () {
							 $(this).prop('checked', false);
						   });
			   
				$('.featured-item').show(1000);
				$('.checkall').hide();
			});
		});
        </script>
<?php
	}
?><!-- manufacturers_eof //-->
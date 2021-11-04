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

	$MASTER_BOX_HTML='<div class="{{CLASS}}"><a style="display: block;" href="{{LINK_1}}">{{NAME}}</a></div>'."\n";
	$template_html=$MASTER_BOX_HTML;
	if(!defined('BOX_HEADING_INFORMATION'))define('BOX_HEADING_INFORMATION', 'Information');

	function tep_show_static_category($counter) 
	{
		global $foo,$static_foo, $page_categories_string, $page_display_id, $template_html,$BOX_CONTENT_ICON,$INITIAL_WIDTH;

		if ($static_foo[$counter]['parent'] == 0) 
		{
			$stcPath_new = 'stcPath=' . $counter;
		} 
		else 
		{
			$stcPath_new = 'stcPath=' . $static_foo[$counter]['path'];
		}

		$replace_string=$template_html;
		$replace_array["SPACE_WIDTH"]=($static_foo[$counter]['level']+1)*0;
		$replace_array["SPACER"]=$replace_array["SPACE_WIDTH"];
		$replace_array["NAME"]=$static_foo[$counter]['name'];
		$replace_array["ICON"]=$BOX_CONTENT_ICON;
		$replace_array["LINK_1"]=tep_href_link(FILENAME_DEFAULT,$stcPath_new);
		$replace_array["INITIAL_WIDTH"]=$INITIAL_WIDTH;

		if(sizeof($page_display_id)>0)$selected[0] = $page_display_id[sizeof($page_display_id)-1];
		else $selected[0] = "";
		
		if ($page_display_id && in_array($counter, $selected)) 
		{
		$replace_array["CLASS"]="list-group-item list-group-item-action list-group-item-primary active";
		}
		else
		{
			if ($foo[$counter]['parent'] != 0) 
			{
				$replace_array["CLASS"]="list-group-item list-group-item-action "; 
			} 
			else 
			{
				$replace_array["CLASS"]="list-group-item list-group-item-action "; 
			}
		}
		
		reset($replace_array);

	foreach($replace_array as $key=>$value) 
	{
			$replace_string=str_replace("{{" . $key . "}}",$replace_array[$key],$replace_string);
		}
		$page_categories_string.=$replace_string;	
		
		if ($static_foo[$counter]['next_id']) 
		{
			tep_show_static_category($static_foo[$counter]['next_id']);
		}
		
	}
?>
<!-- information //-->
<?php
 
	$page_categories_string = '';


	$static_sql = "select md.page_name, md.description, md.page_id, m.parent_id from " . TABLE_MAINPAGE . " m, " . TABLE_MAINPAGE_DESCRIPTIONS . " md where m.parent_id = '0' and m.page_id=md.page_id and m.page_status=1 and md.language_id='" . (int)$FSESSION->languages_id ."' order by sort_order, md.page_name";
	
	$static_query = tep_db_query($static_sql);
	while ($static_pages = tep_db_fetch_array($static_query))  
	{
		$static_foo[$static_pages['page_id']] = array(
									'name' => $static_pages['page_name'],
									'parent' => $static_pages['parent_id'],
									'level' => 0,
									'path' => $static_pages['page_id'],
									'next_id' => false
								   );
	
		if (isset($static_prev_id)) 
		{
			$static_foo[$static_prev_id]['next_id'] = $static_pages['page_id'];
		}
		
		$static_prev_id = $static_pages['page_id'];
		
		if (!isset($static_first_element)) 
		{
			$static_first_element = $static_pages['page_id'];
		}
	}
	//------------------------
					
	if ($stcPath) 
	{
		$static_new_path = '';
		$page_display_id = preg_split('/_/', $stcPath);
		reset($page_display_id);
		
		//while (list($static_key, $static_value) = each($page_display_id)) 
		foreach($page_display_id as $static_key => $static_value)	
		{
			unset($static_prev_id);
			unset($static_first_id);
			
			$static_query = tep_db_query("select md.page_id, md.page_name, m.parent_id from " . TABLE_MAINPAGE . " m, " . TABLE_MAINPAGE_DESCRIPTIONS . " md where m.parent_id = '" . (int)$static_value . "' and m.page_id=md.page_id and m.page_status=1 and md.language_id='" . (int)$FSESSION->languages_id ."' order by sort_order, md.page_name");
			$static_category_check = tep_db_num_rows($static_query);
			
			if ($static_category_check > 0) {
				$static_new_path .= $static_value;
				while ($row = tep_db_fetch_array($static_query)) 
				{
					$static_foo[$row['page_id']] = array(
													  'name' => $row['page_name'],
													  'parent' => $row['parent_id'],
													  'level' =>$static_key+1,
													  'path' =>$static_new_path . '_' . $row['page_id'],
													  'next_id' => false
													 );
					
					if (isset($static_prev_id)) {
						$static_foo[$static_prev_id]['next_id'] = $row['page_id'];
					}
					
					$static_prev_id = $row['page_id'];
					
					if (!isset($static_first_id)) {
						$static_first_id = $row['page_id'];
					}
					
					$static_last_id = $row['page_id'];
				}
				$static_foo[$static_last_id]['next_id'] = $static_foo[$static_value]['next_id'];
				$static_foo[$static_value]['next_id'] =$static_first_id;
				$static_new_path .= '_';
			} 
			else 
			{
				break;
			}
		}
	}

	tep_show_static_category($static_first_element);
				
	echo '<div class="card box-shadow">';			
	echo '<div class="card-header">';
	echo '<strong>';
	echo BOX_HEADING_INFORMATION;
	echo '</strong>';
	echo '</div>';
	echo '<div class="list-group">';
	if(HIDE_CMS_INFO=="no")
	{
	echo $page_categories_string;
	echo '<div class="list-group-item list-group-item-action"><a href="' .BOX_INFORMATION_CONTACT_LINK. '">' .BOX_INFORMATION_CONTACT. '</a></div>';
	}
	else //we show only contact
	{
	echo '<div class="list-group-item list-group-item-action"><a href="' .BOX_INFORMATION_CONTACT_LINK. '">' .BOX_INFORMATION_CONTACT. '</a></div>';
	}
	if (HIDE_SEARCH_EVENTS == 'no')
	{
	echo '<div class="list-group-item list-group-item-action"><a href="search_events.php">'.SEARCH_EVENTS.'</a></div>';
	}
	echo '</div>';
	echo '</div>';
	echo '<br class="clearfloat">';
?><!-- information_eof //-->
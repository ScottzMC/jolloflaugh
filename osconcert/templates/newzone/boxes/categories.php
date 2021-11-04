<?php
/*
osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 
Copyright (c) 2003 osCommerce 
 
Released under the GNU General Public License

SOME CONFIGURATIONS TO CONSIDER HERE
*/
defined('_FEXEC') or die();

if(!defined('BOX_HEADING_CATEGORIES'))define('BOX_HEADING_CATEGORIES', 'Shows');

$MASTER_BOX_HTML='<div class="{{CLASS}}"><a style="display: block;" href="{{LINK_1}}">{{NAME}}</a></div>'."\n";

//$MASTER_BOX_HTML='<div class="{{CLASS}}"><a style="display: block;" href="{{LINK_1}}">{{TITLE}}</a></div>'."\n";	
//$MASTER_BOX_HTML='<div class="{{CLASS}}"><a style="display: block;" href="{{LINK_1}}">{{VENUE}}</a></div>'."\n";	

?>

<?php 
$template_html=$MASTER_BOX_HTML;

function tep_show_category($counter) 
{
	global $foo, $count,$categories_string,$id,$template_html;
	$count++;
	
	$categories_name=$foo[$counter]['name'];
	$categories_date=$foo[$counter]['date'];
	$categories_title=$foo[$counter]['title'];
	$categories_venue=$foo[$counter]['venue'];
	$categories_time=$foo[$counter]['time'];
	
	require(DIR_WS_FUNCTIONS.'/date_formats.php');
	
	if ($foo[$counter]['parent'] == 0) 
	{
		$cPath_new = 'cPath=' . $counter;
	}
	else 
	{
		$cPath_new = 'cPath=' . $foo[$counter]['path'];
	}
	
	$replace_string=$template_html;

		if (MENU_LIST=='true')
		{
			if ($foo[$counter]['parent'] != 0) //sub categories
			{	
				$replace_array["NAME"]='&nbsp;&nbsp;' . $categories_name .' '. $heading_time;
				$replace_array["TITLE"]='&nbsp;&nbsp;' . $categories_title;	
				$replace_array["VENUE"]='&nbsp;&nbsp;' . $categories_venue;	
			}else{	
				$replace_array["NAME"]=$categories_name.'<br>' . $heading_date .' '. $heading_time;
				$replace_array["TITLE"]=$categories_title .'<br>' . $heading_date .' '. $heading_time;
				$replace_array["VENUE"]=$categories_title .'<br>' . $categories_venue . ' ' . $heading_date .' '. $heading_time;
			}
		}else
		{
			if ($foo[$counter]['parent'] != 0) 
			{
			$replace_array["NAME"]='&nbsp;&nbsp;' . $categories_name;
			$replace_array["TITLE"]='&nbsp;&nbsp;' . $categories_title;
			$replace_array["VENUE"]='&nbsp;&nbsp;' . $categories_name. ' ' . $heading_date;
			}else{
			$replace_array["NAME"]=$categories_name;
			$replace_array["TITLE"]=$categories_title;
			$replace_array["VENUE"]=$categories_venue;			
			}
		}
		
	$replace_array["LINK_1"]=tep_href_link(FILENAME_DEFAULT,$cPath_new);
	//??
	if(sizeof($id)>0)$selected[0] = $id[sizeof($id)-1];
		else $selected[0] = "";
	
	if ($id && in_array($counter, $selected))
	
	{
		$replace_array["CLASS"]="list-group-item list-group-item-action list-group-item-primary active";
	}else
	{
		if($foo[$counter]['parent'] != 0) 
		{
			$replace_array["CLASS"]="list-group-item list-group-item-action "; 
		}else 
		{
			$replace_array["CLASS"]="list-group-item list-group-item-action "; 
		}
	}
	
	reset($replace_array);
	foreach($replace_array as $key=>$value) 
	{
	$replace_string=str_replace("{{" . $key . "}}",$replace_array[$key],$replace_string);
	}
	$categories_string.=$replace_string;
	
	if ($foo[$counter]['next_id']) 
	{
		tep_show_category($foo[$counter]['next_id']);
	}
}

?>
<!-- start: categories -->
<?php
	
	$categories_string = '';
	//If we wanted to leave a message for a disable category (status=0)
	if(!defined('SHOW_DISABLED_CATEGORIES'))define('SHOW_DISABLED_CATEGORIES', 'false');
	$cstatus="";

	if(SHOW_DISABLED_CATEGORIES=='true')
	{
	$cstatus="=";
	}

	$sql_order_by = FEATURED_CATEGORIES_ORDERBY;
	//IMPORTANT switch order by here
	$categories_query = tep_db_query("select c.categories_id,c.date_id,c.plan_id,c.concert_date_unix,cd.concert_venue,cd.concert_date,cd.concert_time, cd.categories_heading_title,cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status >".$cstatus."'0' and c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . $FSESSION->languages_id ."' order by c." .$sql_order_by . " ASC");
	
	while ($categories = tep_db_fetch_array($categories_query)) 

		//print_r($categories);
	{
		
		//$date= format_date($categories['concert_date']);
		
		$foo[$categories['categories_id']] = array(
										'name' => $categories['categories_name'],
										'title' => $categories['categories_heading_title'],
										'unix' => $categories['concert_date_unix'],
										'venue' => $categories['concert_venue'],
										'date' => $categories['concert_date'],
										'time' => $categories['concert_time'],
										'dateid' => $categories['date_id'],
										'parent' => $categories['parent_id'],
										'level' => 0,
										'path' => $categories['categories_id'],
										'next_id' => false
									   );

		if (isset($prev_id)) 
		{
			$foo[$prev_id]['next_id'] = $categories['categories_id'];
		}
		
		$prev_id = $categories['categories_id'];
		
		if (!isset($first_element)) 
		{
			$first_element = $categories['categories_id'];
		}
	}

	if ($cPath) 
	{
		$id = preg_split('/_/', $cPath);
		reset($id);
		foreach ($id as $key => $value)
		{
			$new_path .= $value;
			unset($prev_id);
			unset($first_id);

			$categories_query = tep_db_query("select c.categories_status,c.plan_id, c.date_id, c.categories_id,cd.concert_venue,c.concert_date_unix,cd.concert_date,cd.concert_time, cd.categories_heading_title,cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status > '0' and c.parent_id = '" . $value . "' and c.categories_id = cd.categories_id and cd.language_id='" . $FSESSION->languages_id ."' order by sort_order, cd.categories_name");
			$category_check = tep_db_num_rows($categories_query);
			while ($row = tep_db_fetch_array($categories_query)) 
			{
				$foo[$row['categories_id']] = array(
				'name' => $row['categories_name'],
				'title' => $row['categories_heading_title'],
				'venue' => $row['concert_venue'],
				'date' => $row['concert_date'],
				'time' => $row['concert_time'],
				'dateid' => $row['date_id'],
				'parent' => $row['parent_id'],
				'level' => $key+1,
				'path' => $new_path . '_' . $row['categories_id'],
				'next_id' => false
				);
				
				if (isset($prev_id)) {$foo[$prev_id]['next_id'] = $row['categories_id'];}
				$prev_id = $row['categories_id'];
				
				if (!isset($first_id)) {$first_id = $row['categories_id'];}
				$last_id = $row['categories_id'];
			}

			if ($category_check != 0) 
			{
				$foo[$last_id]['next_id'] = $foo[$value]['next_id'];
				$foo[$value]['next_id'] = $first_id;
			}
			
			$new_path .= '_';
		}
	}
	tep_show_category($first_element,$count);
	
	$temp_see=100;
	
	echo '<div class="card box-shadow">';
	echo '<div class="card-header">';
	echo '<strong>';
	echo BOX_HEADING_CATEGORIES;
	echo '</strong>';
	echo '</div>';
	echo '<div class="list-group">';
	echo $categories_string;
	echo '</div>';
	echo '</div>';
	echo '<br class="clearfloat">';
	//echo $id;
?>
<!-- end: categories-->
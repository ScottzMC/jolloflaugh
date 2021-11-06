<?php
/*
   

Freeway eCommerce
http://www.openfreeway.org
Copyright (c) 2007 ZacWare

Released under the GNU General Public License
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();


  class splitPageResults {
    var $sql_query, $number_of_rows, $current_page_number, $number_of_pages, $number_of_rows_per_page, $page_name;

/* class constructor */
    function splitPageResults($query, $max_rows, $count_key = '*', $page_holder = 'page') {
      global $FREQUEST;

      $this->sql_query = $query;
      $this->page_name = $page_holder;

    if ($FREQUEST->getvalue($page_holder)) {
        $page = $FREQUEST->getvalue($page_holder);
      } elseif ($FREQUEST->postvalue($page_holder)) {
        $page = $FREQUEST->postvalue($page_holder);
      } else {
        $page = '';
      }

      if (empty($page) || !is_numeric($page)) $page = 1;
	  if((int)$page<=0) $page=1;
      $this->current_page_number = $page;

      $this->number_of_rows_per_page = $max_rows;

      $pos_to = strlen($this->sql_query);
      $pos_from = strpos($this->sql_query, ' from', 0);

      $pos_group_by = strpos($this->sql_query, ' group by', $pos_from);
      if (($pos_group_by < $pos_to) && ($pos_group_by != false)) $pos_to = $pos_group_by;

      $pos_having = strpos($this->sql_query, ' having', $pos_from);
      if (($pos_having < $pos_to) && ($pos_having != false)) $pos_to = $pos_having;

      $pos_order_by = strpos($this->sql_query, ' order by', $pos_from);
      if (($pos_order_by < $pos_to) && ($pos_order_by != false)) $pos_to = $pos_order_by;

      if (strpos($this->sql_query, 'distinct') || strpos($this->sql_query, 'group by')) {
        $count_string = 'distinct ' . tep_db_input($count_key);
      } else {
        $count_string = tep_db_input($count_key);
      }

      $count_query = tep_db_query("select count(" . $count_string . ") as total " . substr($this->sql_query, $pos_from, ($pos_to - $pos_from)));
      $count = tep_db_fetch_array($count_query);

      $this->number_of_rows = $count['total'];

      $this->number_of_pages = ceil($this->number_of_rows / $this->number_of_rows_per_page);

      if ($this->current_page_number > $this->number_of_pages) {
        $this->current_page_number = $this->number_of_pages;
      }
	  if (empty($this->current_page_number)) $this->current_page_number = 1;
	  if ((int)$this->current_page_number<=0) $this->current_page_number = 1;

      $offset = ($this->number_of_rows_per_page * ($this->current_page_number - 1));

      //$this->sql_query .= " limit " . $offset . ", " . $this->number_of_rows_per_page;
	  $this->sql_query .= " limit " . max($offset,0) . ", " . $this->number_of_rows_per_page;
	
    }

/* class functions */

// display split-page-number-links
    function display_links($max_page_links, $parameters = '',$use_template=true,$template="") {
      global $PHP_SELF, $request_type;

	  if ($use_template){
	  	return $this->display_links_template($max_page_links,$parameters,$template);
	  }
      $display_links_string = '';

      $class = 'class="pageResults"';

      if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&';

// previous button - not displayed on first page
      if ($this->current_page_number > 1) $display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . ($this->current_page_number - 1), $request_type) . '" class="pageResults" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' "><u>' . PREVNEXT_BUTTON_PREV . '</u></a>&nbsp;&nbsp;';

// check if number_of_pages > $max_page_links
      $cur_window_num = intval($this->current_page_number / $max_page_links);
      if ($this->current_page_number % $max_page_links) $cur_window_num++;

      $max_window_num = intval($this->number_of_pages / $max_page_links);
      if ($this->number_of_pages % $max_page_links) $max_window_num++;

// previous window of pages
      if ($cur_window_num > 1) $display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links), $request_type) . '" class="pageResults" title=" ' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a>';

// page nn button
      for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
        if ($jump_to_page == $this->current_page_number) {
          $display_links_string .= '&nbsp;<b>' . $jump_to_page . '</b>&nbsp;';
        } else {
          $display_links_string .= '&nbsp;<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . $jump_to_page, $request_type) . '" class="pageResults" title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' "><u>' . $jump_to_page . '</u></a>&nbsp;';
        }
      }

// next window of pages
      if ($cur_window_num < $max_window_num) $display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1), $request_type) . '" class="pageResults" title=" ' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a>&nbsp;';

// next button
      if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '&nbsp;<a href="' . tep_href_link(basename($PHP_SELF), $parameters . 'page=' . ($this->current_page_number + 1), $request_type) . '" class="pageResults" title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' "><u>' . PREVNEXT_BUTTON_NEXT . '</u></a>&nbsp;';

      return $display_links_string;
    }

	function display_links_template($max_page_links,$parameters='',$template){
	global $PHP_SELF, $request_type;
		  $display_links_string = '';

		  if ($template=="") $template=$this->getTemplate();
		  $class = 'class="pageResults"';
	
		  if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&';
		  
		  $fields_array=array();
		  // replace previous template
		  if ($this->current_page_number > 1) {
		  	$fields_array["{{prev_link}}"] =tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . ($this->current_page_number - 1), $request_type);
			$fields_array["{{prev_link_display}}"]="";
		  } else {
			$fields_array["{{prev_link}}"]="";
			$fields_array["{{prev_link_display}}"]="none";
		  }
		// check if number_of_pages > $max_page_links
			  $cur_window_num = intval($this->current_page_number / $max_page_links);
			  if ($this->current_page_number % $max_page_links) $cur_window_num++;
		
			  $max_window_num = intval($this->number_of_pages / $max_page_links);
			  if ($this->number_of_pages % $max_page_links) $max_window_num++;
		
		// previous window of pages
			  if ($cur_window_num > 1) {
	  		    $fields_array["{{prev_window}}"]=tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links), $request_type);
				$fields_array["{{prev_window_display}}"]="";
			  } else {
				$fields_array["{{prev_window}}"]="";
				$fields_array["{{prev_window_display}}"]="none";
			  }
			$fields_array["{{prev_window_title}}"]=sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links);
				$display_links="";			  
			// page nn button
				  for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
					if ($jump_to_page == $this->current_page_number) {
					  $display_links .= '&nbsp;<span class="pageResultsselected">' . $jump_to_page . '</span>';
					} else {
					  $display_links .= '&nbsp;<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . $jump_to_page, $request_type) . '" class="pageResultsCurrent" title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' ">' . $jump_to_page . '</a>';
					}
				  }
			$fields_array["{{page_links}}"]=$display_links;
			
			
			// next window of pages
				  if ($cur_window_num < $max_window_num) {
				  	$fields_array["{{next_window}}"]=tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1), $request_type);
					$fields_array["{{next_window_display}}"]="";
					} else {
					$fields_array["{{next_window}}"]="";
					$fields_array["{{next_window_display}}"]="none";
					}
			$fields_array["{{next_window_title}}"]=sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links);			
			// next button
				  if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) {
					  	$fields_array["{{next_link}}"] =tep_href_link(basename($PHP_SELF), $parameters . 'page=' . ($this->current_page_number + 1), $request_type);
						$fields_array["{{next_link_display}}"]="";
					} else {
						$fields_array["{{next_link}}"]="";
						$fields_array["{{next_link_display}}"]="none";
					}
		reset($fields_array);
		//FOREACH
		//while(list($key,$value)=each($fields_array)){
		foreach($fields_array_ as $key => $value) {
			//$template=ereg_replace($key,$value,$template);
			$template=preg_replace($key,$value,$template);
		}
		return $template;
	}
	function gettemplate(){
		$result.='<table cellpadding="5" width="100%" class="pageResults"><tr><td width="150" class="pageResults">' .
						'<a href="{{prev_link}}" class="pageResults"><span style="display:{{prev_link_display}}">' . tep_template_image_button("button_previous.gif",PREVNEXT_TITLE_PREVIOUS_PAGE) . '</span></a>' .
					'<td align="center" class="pageLinks"><a href="{{prev_window}}" class="pageResults" title="{{prev_window_title}}"><span style="display:{{prev_window_display}}">...</span></a>{{page_links}}<a href="{{next_window}}" class="pageResults" title="{{next_window_title}}"><span style="display:{{next_window_display}}">...</span></a></td>' .
					'<td align="center" width="150" class="pageLinks"><a href="{{next_link}}" class="pageResults"><span style="display:{{next_link_display}}">' .
					tep_template_image_button("button_next.gif",PREVNEXT_TITLE_NEXT_PAGE) . '</span></td></tr></table>';
		return $result;
	}
// display number of total products found
    function display_count($text_output,$use_template=true) {
		if ($use_template) return "";
      $to_num = ($this->number_of_rows_per_page * $this->current_page_number);
      if ($to_num > $this->number_of_rows) $to_num = $this->number_of_rows;
      $from_num = ($this->number_of_rows_per_page * ($this->current_page_number - 1));

      if ($to_num == 0) {
        $from_num = 0;
      } else {
        $from_num++;
      }

      return sprintf($text_output, $from_num, $to_num, $this->number_of_rows);
    }
  }
?>

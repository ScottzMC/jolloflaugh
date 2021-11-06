<?php

/*

  

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
http://www.openfreeway.org

Copyright 2007 ZacWare Pty. Ltd
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

  class splitPageResultsEvent {
    function __construct(&$current_page_number, $max_rows_per_page, &$sql_query, &$query_num_rows,$checkIn=false) {
      $pos_to = strlen($sql_query);
      $pos_from = strpos($sql_query, ' from', 0);

      //$pos_group_by = strpos($sql_query, ' group by', $pos_from);
      //if (($pos_group_by < $pos_to) && ($pos_group_by != false)) $pos_to = $pos_group_by;+

      $pos_having = strpos($sql_query, ' having', $pos_from);
      if (($pos_having < $pos_to) && ($pos_having != false)) $pos_to = $pos_having;

      $pos_order_by = strpos($sql_query, ' order by', $pos_from);
      if (($pos_order_by < $pos_to) && ($pos_order_by != false)) $pos_to = $pos_order_by;
	  
	  if($checkIn){
	  	$reviews_count_query = tep_db_query($sql_query);
		$query_num_rows=tep_db_num_rows($reviews_count_query);
	  }else{
	      $reviews_count_query = tep_db_query("select count(*) as total " . substr($sql_query, $pos_from, ($pos_to - $pos_from)));      
			$query_num_rows=tep_db_num_rows($reviews_count_query);
		
			if ($query_num_rows==1) {
			  $reviews_count = tep_db_fetch_array($reviews_count_query);
			  $query_num_rows = $reviews_count['total'];
			}
	  }
      $num_pages = ceil($query_num_rows / $max_rows_per_page);
      if ($current_page_number > $num_pages) {
        $current_page_number = $num_pages;
      }
	  if (empty($current_page_number)) $current_page_number = 1;
        
		if ((int)$current_page_number<=0) $current_page_number=1;
		
      $offset = ($max_rows_per_page * ($current_page_number - 1));
      $sql_query .= " limit " . $offset . ", " . $max_rows_per_page;
    }
	

    function display_links($query_numrows, $max_rows_per_page, $max_page_links, $current_page_number, $parameters = '', $page_name = 'page') {
      global $PHP_SELF;

      if ( tep_not_null($parameters) && (substr($parameters, -1) != '&') ) $parameters .= '&';

// calculate number of pages needing links
      $num_pages = ceil($query_numrows / $max_rows_per_page);

      $pages_array = array();
      for ($i=1; $i<=$num_pages; $i++) {
        $pages_array[] = array('id' => $i, 'text' => $i);
      }

      if ($num_pages > 1) {
        $display_links = tep_draw_form('pages', basename($PHP_SELF), '', 'get');

        if ($current_page_number > 1) {
          $display_links .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $page_name . '=' . ($current_page_number - 1), 'NONSSL') . '" class="splitPageLink">' . PREVNEXT_BUTTON_PREV . '</a>&nbsp;&nbsp;';
        } else {
          $display_links .= PREVNEXT_BUTTON_PREV . '&nbsp;&nbsp;';
        }

        $display_links .= sprintf(TEXT_RESULT_PAGE, tep_draw_pull_down_menu($page_name, $pages_array, $current_page_number, 'onChange="this.form.submit();"'), $num_pages);

        if (($current_page_number < $num_pages) && ($num_pages != 1)) {
          $display_links .= '&nbsp;&nbsp;<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $page_name . '=' . ($current_page_number + 1), 'NONSSL') . '" class="splitPageLink">' . PREVNEXT_BUTTON_NEXT . '</a>';
        } else {
          $display_links .= '&nbsp;&nbsp;' . PREVNEXT_BUTTON_NEXT;
        }

        if ($parameters != '') {
          if (substr($parameters, -1) == '&') $parameters = substr($parameters, 0, -1);
          $pairs = explode('&', $parameters);
		  //FOREACH x
         //while (list(, $pair) = each($pairs)) {
		  foreach($pairs as $pair=>$value)
		  {
			  
            list($key,$value) = explode('=', $pair);
            $display_links .= tep_draw_hidden_field(rawurldecode($key), rawurldecode($value));
          }
        }

        if (SID) $display_links .= tep_draw_hidden_field($FSESSION->NAME, $FSESSION->ID);

        $display_links .= '</form>';
      } else {
        $display_links = sprintf(TEXT_RESULT_PAGE, $num_pages, $num_pages);
      }

      return $display_links;
    }


    function display_script_links($query_numrows, $max_rows_per_page, $max_page_links, $current_page_number, $parameters = '', $function_name,$unique_name) {

// calculate number of pages needing links
	  $num_pages = ceil($query_numrows / $max_rows_per_page);
		
      $pages_array = array();
      for ($i=1; $i<=$num_pages; $i++) {
        $pages_array[] = array('id' => $i, 'text' => $i);
      }

      if (SID) $display_links .= "&" . $FSESSION->NAME. '=' . $FSESSION->ID;
	  
      if ($num_pages > 1) {
        /*if ($current_page_number > 1) {
          $display_links .= '<a href="javascript:' . $function_name . ",'" . $parameters . "'," .$unique_name . ',-1)" class="splitPageLink">' . PREVNEXT_BUTTON_PREV . '</a>&nbsp;&nbsp;';
        } else {
          $display_links .= PREVNEXT_BUTTON_PREV . '&nbsp;&nbsp;';
        }*/
		$display_links .= '<a href="javascript:' . $function_name . ",'" . $parameters . "'," .$unique_name . ',\'prev\')" class="splitPageLink">' . PREVNEXT_BUTTON_PREV . '</a>&nbsp;&nbsp;';

       $display_links .= sprintf(TEXT_RESULT_PAGE, tep_draw_pull_down_menu('page_' . $unique_name, $pages_array, $current_page_number, ' onChange="javascript:' . $function_name . ',\'' . $parameters .'\',' . $unique_name . ',\'select\');"'), $num_pages);

		$display_links .= '&nbsp;&nbsp;<a href="javascript:' . $function_name . ",'" . $parameters . "'," . $unique_name . ',\'next\')" class="splitPageLink">' . PREVNEXT_BUTTON_NEXT . '</a>';

      }
	
      return $display_links;
    }


    function display_count($query_numrows, $max_rows_per_page, $current_page_number, $text_output) {
      $to_num = ($max_rows_per_page * $current_page_number);
      if ($to_num > $query_numrows) $to_num = $query_numrows;
      $from_num = ($max_rows_per_page * ($current_page_number - 1));
      if ($to_num == 0) {
        $from_num = 0;
      } else {
        $from_num++;
      }

      return sprintf($text_output, $from_num, $to_num, $query_numrows);
    }
  }
?>

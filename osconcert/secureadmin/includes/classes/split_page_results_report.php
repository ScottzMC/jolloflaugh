<?php

/*

  

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
http://www.openfreeway.org

Copyright 2007 ZacWare Pty. Ltd
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

  class splitPageResultsReport {
    function __construct(&$current_page_number, $max_rows_per_page, &$sql_query, &$query_num_rows,$reset_page=true) {
	//$max_rows_per_page=1000;
   		$pos_to = strlen($sql_query);
      $pos_from = strpos($sql_query, " from", 0);
      $pos_group_by = strpos($sql_query, ' group by', $pos_from);
      //if (($pos_group_by < $pos_to) && ($pos_group_by != false)) $pos_to = $pos_group_by;

      //$pos_having = strpos($sql_query, ' having', $pos_from);
      //if (($pos_having < $pos_to) && ($pos_having != false)) $pos_to = $pos_having;
		
      $pos_order_by = strpos($sql_query, ' order by', $pos_from);
      if (($pos_order_by < $pos_to) && ($pos_order_by != false)) $pos_to = $pos_order_by;

      $reviews_count_query = tep_db_query("select count(*) as total " . substr($sql_query, $pos_from, ($pos_to - $pos_from)));
	  $query_num_rows=tep_db_num_rows($reviews_count_query);
		if (!$pos_group_by && $query_num_rows==1){
			$reviews_count = tep_db_fetch_array($reviews_count_query);
			$query_num_rows = $reviews_count['total'];
		}

      $num_pages = ceil($query_num_rows / $max_rows_per_page);
      if ($current_page_number > $num_pages && $reset_page) {
        $current_page_number = $num_pages;
      }
	   
	   if (empty($current_page_number)) $current_page_number = 1;
        
		if ((int)$current_page_number<=0) $current_page_number=1;
	 
	    $offset = ($max_rows_per_page * ($current_page_number -1));
		
     	 $sql_query .= " limit " . $offset . ", " . $max_rows_per_page;
	 
    }

    function display_links($query_numrows, $max_rows_per_page, $max_page_links, $current_page_number, $parameters = '', $page_name = 'page') {
      global $PHP_SELF;

// calculate number of pages needing links
      $num_pages = ceil($query_numrows / $max_rows_per_page);

      /*$pages_array = array();
      for ($i=1; $i<=$num_pages; $i++) {
        $pages_array[] = array('id' => $i, 'text' => $i);
      }*/

      if ($num_pages > 0) {
	  	$display_links.="<script language='javascript'>function nav_page(page){document.pages.elements['" . $page_name . "'].value=page;document.pages.submit();}</script>";
        $display_links.= tep_draw_form('pages', basename($PHP_SELF), '', 'post');
		$display_links.='<input type="hidden" name="post_action" value="screen">';
		$display_links.='<input type="hidden" name="' . $page_name . '" value="">';
	    $display_links.=$parameters;		
        if (SID) $display_links .= tep_draw_hidden_field($FSESSION->NAME, $FSESSION->ID);
		// display if previous button
		if ($current_page_number>1) $display_links.=$this->_getLink(REPORT_PREV_BUTTON,$current_page_number-1,$current_page_number);

		$current_slot=intval($current_page_number/$max_page_links);
		if ($current_page_number % $max_page_links) $current_slot++;

		
		$max_slot=intval($num_pages/$max_page_links);
		if ($num_pages % $max_page_links) $max_slot++;

		// display the link previous "..."
		if ($current_slot>1) $display_links.=$this->_getLink("...",($current_slot-1)*$max_page_links,$current_page_number);
		
		// display page links
		for ($counter=(1+($current_slot-1)*$max_page_links);$counter<=($current_slot*$max_page_links) && $counter<=$num_pages;$counter++){
			$display_links.=$this->_getLink($counter,$counter,$current_page_number);
		}
		// display the link next "..."
		if ($current_slot<$max_slot) $display_links.=$this->_getLink("...",($current_slot*$max_page_links)+1,$current_page_number);

		// display if next button
		if ($current_page_number<$num_pages && ($num_pages != 1)) $display_links.=$this->_getLink(REPORT_NEXT_BUTTON,$current_page_number+1,$current_page_number);
		
		// display if next button
		if ($current_page_number<$num_pages && ($num_pages != 1)) $display_links.=$this->_getLink(' Last',$num_pages,$current_page_number);


        $display_links .= '</form>';
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
	function _getLink($text,$page_no,$current_page){
		if ($page_no!=$current_page){
			$link='<a href="javascript:nav_page(' . $page_no . ')" ';
			$link.='class="splitPageLink"';
			$link.='>' . $text . '</a>&nbsp;&nbsp;';
		} else {
			$link='<span class="splitPageLink"><b>' . $text. '</b></span>&nbsp;&nbsp;';
		}
		return $link;
	}
  }
?>

<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License

*/
	defined('_FEXEC') or die('Restricted');
	class splitResults {
		var $curPage;
		var $maxRows;
		var $queryRows;
		var $maxPages;
		var $curPages;
		var $formLink;
		var $pageLink;
		function __construct($maxRows=10,$maxPages=3){
			$this->maxRows=$maxRows;
			$this->curPage=1;
			$this->queryRows=0;
			$this->maxPages=$maxPages;
		}
		function parse($current_page_number, &$sql_query) {
			if (empty($current_page_number)) $current_page_number = 1;
			if ((int)$current_page_number<=0) $current_page_number=1;
			

			$this->curPage=$current_page_number;
			$pos_to = strlen($sql_query);
			$pos_from = strpos($sql_query, ' from', 0);

			$pos_having = strpos($sql_query, ' having', $pos_from);
			if (($pos_having < $pos_to) && ($pos_having != false)) $pos_to = $pos_having;

			$pos_order_by = strpos($sql_query, ' order by', $pos_from);
			if (($pos_order_by < $pos_to) && ($pos_order_by != false)) $pos_to = $pos_order_by;

			$count_query = tep_db_query("select count(*) as total " . substr($sql_query, $pos_from, ($pos_to - $pos_from)));      
			$this->queryRows=tep_db_num_rows($count_query);
			
			
			
			
			if ($this->queryRows==1) {
				$reviews_count = tep_db_fetch_array($count_query);
				$this->queryRows = $reviews_count['total'];
			}
			if ($this->queryRows<=0) return;
			
			if ($this->maxRows>$this->queryRows) $this->maxRows=$this->queryRows;
			
			$this->curPages = ceil($this->queryRows / $this->maxRows);
			
			if ($this->curPage > $this->curPages) {
				$this->curPage = $this->curPages;
			}
			
			$offset = ($this->maxRows * ($this->curPage - 1));
			$sql_query .= " limit " . $offset . ", " . $this->maxRows;
		}
		function pgLinksCombo(){
			global $FSESSION,$DISPLAY_SHOW_PAGES;
			if ($this->queryRows<=0) return;
			
			$pages_array = array();
			for ($icnt=1; $icnt<=$this->curPages; $icnt++) {
				$pages_array[] = array('id' => $icnt, 'text' => $icnt);
			}
	
			$template_arr=array('DISPLAY_COUNT'=>'',
								'DISPLAY_PREV'=>'',
								'DISPLAY_LINKS'=>'',
								'DISPLAY_NEXT'=>'',
								'FORM_ID'=>time(),
								'FORM_LINK'=>$this->form_link
								);
			if ($this->curPages > 1) {
				$template_arr['DISPLAY_COUNT']='Displaying ' . ((($this->curPage - 1) * $this->maxRows) +1) . ' to '.((($this->curPage * $this->maxRows)>$this->queryRows)?$this->queryRows:($this->curPage * $this->maxRows)).' (of '.$this->queryRows .' '. TEXT_RECORDS .')';
				if ($this->curPage > 1) {
				  $template_arr['DISPLAY_PREV']= '<a href="javascript:void(0)" onClick="javascript:return ' . str_replace("##PAGE_NO##",$this->curPage - 1,$this->pageLink) . ';" class="splitPageLink">' . PREVNEXT_BUTTON_PREV . '</a>';
				} else {
				  $template_arr['DISPLAY_PREV']= PREVNEXT_BUTTON_PREV;
				}
			
				$template_arr['DISPLAY_LINKS'] = sprintf(TEXT_RESULT_PAGE, tep_draw_pull_down_menu('pgnavigcombo' . $template_arr['FORM_ID'], $pages_array, $this->curPage, 'onChange="javascript:'. str_replace("##PAGE_NO##","this.value",$this->pageLink) .'"'), $this->curPages);
			
			
				if (($this->curPage < $this->curPages) && ($this->curPages != 1)) {
				  $template_arr['DISPLAY_NEXT'] = '<a href="javascript:void(0)" onClick="javascript:return ' . str_replace("##PAGE_NO##",$this->curPage + 1,$this->pageLink) . ';" class="splitPageLink">' . PREVNEXT_BUTTON_NEXT . '</a>';
				} else {
				  $template_arr['DISPLAY_NEXT'] = PREVNEXT_BUTTON_NEXT;
				}
			}
			$template_str=getPaginationTemplate();
			return mergeTemplate($template_arr,$template_str);
		}
    }
?>
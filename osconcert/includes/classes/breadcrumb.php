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

  class breadcrumb {
    var $_trail;

    function __construct() {
      $this->reset();
    }

    function reset() {
      $this->_trail = array();
    }

    function add($title, $link = '') {
	   $this->_trail[] = array('title' => $title, 'link' => $link);
	   tep_update_whos_online($title);
   }

    function trail($separator = ' - ') {
      $trail_string = '';
	
      for ($i=0, $n=sizeof($this->_trail); $i<$n; $i++) {
        if (isset($this->_trail[$i]['link']) && tep_not_null($this->_trail[$i]['link'])) {
          $trail_string .= '<a class="breadcrumb-link" href="' . $this->_trail[$i]['link'] . '">' . $this->_trail[$i]['title'] . '</a>';
        } else {
          $trail_string .= $this->_trail[$i]['title'];
        }

        if (($i+1) < $n) $trail_string .= $separator;
      }

      return $trail_string;
    }

    function size() {
	return sizeof($this->_trail);
    }
  }
?>

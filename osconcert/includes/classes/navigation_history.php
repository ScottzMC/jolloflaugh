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

  class navigationHistory {
    var $path, $snapshot;

    function __construct() {
      $this->reset();
    }

    function reset() {
      $this->path = array();
      $this->snapshot = array();
    }

    function add_current_page() {
      global $PHP_SELF, $FREQUEST, $request_type, $cPath;

      $set = 'true';
      for ($i=0, $n=sizeof($this->path); $i<$n; $i++) {
        if ( ($this->path[$i]['page'] == basename($PHP_SELF)) ) {
          if (isset($cPath)) {
            if (!isset($this->path[$i]['get']['cPath'])) {
              continue;
            } else {
              if ($this->path[$i]['get']['cPath'] == $cPath) {
                array_splice($this->path, ($i+1));
                $set = 'false';
                break;
              } else {
                $old_cPath = explode('_', $this->path[$i]['get']['cPath']);
                $new_cPath = explode('_', $cPath);

                for ($j=0, $n2=sizeof($old_cPath); $j<$n2; $j++) {
                  if ($old_cPath[$j] != $new_cPath[$j]) {
                    array_splice($this->path, ($i));
                    $set = 'true';
                    break 2;
                  }
                }
              }
            }
          } else {
            array_splice($this->path, ($i));
            $set = 'true';
            break;
          }
        }
      }

      if ($set == 'true') {
        $this->path[] = array('page' => basename($PHP_SELF),
                              'mode' => $request_type,
                              'get' => '$FGET',
                              'post' => '$FPOST');
      }
    }

    function remove_current_page() {
      global $PHP_SELF;

      $last_entry_position = sizeof($this->path) - 1;
      if ($this->path[$last_entry_position]['page'] == basename($PHP_SELF)) {
        unset($this->path[$last_entry_position]);
      }
    }

    function set_snapshot($page = '') {
      global $PHP_SELF, $FREQUEST, $request_type;

      if (is_array($page)) {
        $this->snapshot = array('page' => $page['page'],
                                'mode' => $page['mode'],
                                'get' => $page['get'],
                                'post' => $page['post']);
      } else {
        $this->snapshot = array('page' => basename($PHP_SELF),
                                'mode' => $request_type,
                                'get' => $FGET,
                                'post' => $FPOST);
      }
    }

    function clear_snapshot() {
      $this->snapshot = array();
    }

    function set_path_as_snapshot($history = 0) {
      $pos = (sizeof($this->path)-1-$history);
      $this->snapshot = array('page' => $this->path[$pos]['page'],
                              'mode' => $this->path[$pos]['mode'],
                              'get' => $this->path[$pos]['get'],
                              'post' => $this->path[$pos]['post']);
    }

    function debug() {
		global $FSESSION;
      for ($i=0, $n=sizeof($this->path); $i<$n; $i++) {
        echo $this->path[$i]['page'] . '?';
		//FOREACH
       // while (list($key, $value) = each($this->path[$i]['get'])) {
		 foreach($this->path[$i]['get'] as $key => $value) 
		 {	
          echo $key . '=' . $value . '&';
        }
        if (sizeof($this->path[$i]['post']) > 0) {
          echo '<br>';
          //while (list($key, $value) = each($this->path[$i]['post'])) {
			  foreach($this->path[$i]['post'] as $key => $value) 
		 {
            echo '&nbsp;&nbsp;<b>' . $key . '=' . $value . '</b><br>';
          }
        }
        echo '<br>';
      }

      if (sizeof($this->snapshot) > 0) {
        echo '<br><br>';

        echo $this->snapshot['mode'] . ' ' . $this->snapshot['page'] . '?' . tep_array_to_string($this->snapshot['get'], array($FSESSION->name)) . '<br>';
      }
    }

    function unserialize($broken) {
      for(reset($broken);$kv=each($broken);) {
        $key=$kv['key'];
        if (gettype($this->$key)!="user function")
        $this->$key=$kv['value'];
      }
    }
  }
?>

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

  class messageStack extends alertBlock 
  {

// class constructor
    function __construct()
	{
      global $FSESSION;

      $this->messages = array();

      if ($FSESSION->is_registered('messageToStack')) 
	  {
		$messageToStack=&$FSESSION->getRef('messageToStack');
        for ($i=0, $n=count($messageToStack); $i<$n; $i++) 
		{
          $this->add($messageToStack[$i]['class'], $messageToStack[$i]['text'], $messageToStack[$i]['type']);
        }
        $FSESSION->remove('messageToStack');
      }
    }

// class methods
    function add($class, $message, $type = 'error') 
	{
	global $FSESSION;
      if ($type == 'error') 
	  {
        $this->messages[] = array('params' => 'class="alert alert-warning"', 'class' => $class, 'text' => tep_image(DIR_WS_ICONS . 'error.gif', ICON_ERROR) . '&nbsp;' . $message);
      } elseif ($type == 'warning') 
	  {
        $this->messages[] = array('params' => ' class="alert alert-danger"', 'class' => $class, 'text' => tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '&nbsp;' . $message);
      } elseif ($type == 'success') 
	  {
        $this->messages[] = array('params' => 'class="alert alert-success"', 'class' => $class, 'text' => tep_image(DIR_WS_ICONS . 'success.gif', ICON_SUCCESS) . '&nbsp;' . $message);
      } else 
	  {
        $this->messages[] = array('params' => 'class="alert alert-warning"', 'class' => $class, 'text' => $message);
      }
    }

    function add_session($class, $message, $type = 'error') 
	{
      global $FSESSION;

      if (!$FSESSION->is_registered('messageToStack')) 
	  {
        $FSESSION->set('messageToStack',array());
      }
	  $messageToStack=&$FSESSION->getRef('messageToStack');
       
      $messageToStack[] = array('class' => $class, 'text' => $message, 'type' => $type);
    }

    function reset() 
	{
      $this->messages = array();
    }

    function output($class) 
	{
      $output = array();
      for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) 
	  {
        if ($this->messages[$i]['class'] == $class) 
		{
          $output[] = $this->messages[$i];
        }
      }

      return $this->alertBlock($output);
    }

    function size($class) 
	{
      $count = 0;

      for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) 
	  {
        if ($this->messages[$i]['class'] == $class) 
		{
          $count++;
        }
      }

      return $count;
    }
  }
?>
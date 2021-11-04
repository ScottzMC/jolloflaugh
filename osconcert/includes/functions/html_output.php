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
defined('_FEXEC') or die(); 
function forceHTTPS()
	{

	  $httpsURL = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	//force all to https
	if(tep_not_null(ENABLE_SSL)){
	  if( !isset( $_SERVER['HTTPS'] ) || $_SERVER['HTTPS']!=='on' ){
		if( !headers_sent() ){
		  header( "Status: 301 Moved Permanently" );
		  header( "Location: $httpsURL" );
		  exit();
		}
	  }
	}
	}

  
  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
    global $request_type, $SID,$FSESSION;
    if (!tep_not_null($page)) {
      die('<br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>');
    }
	if ($connection!='NONSSL' &&  $connection!='SSL'){
		die('<br><font color="#ff0000"><b>Error!</b></font><br><br>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL</b><br>');
	}
	if(strpos($parameters,'command=')!==false){
		$connection=$request_type;
		$search_engine_safe=false;
	}


	if ($connection == 'NONSSL') {
      $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == 'true') {
        $link = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG;
      } else {
        $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
      }
    } 
    if (tep_not_null($parameters)) {
      $link .= $page . '?' . tep_output_string($parameters);
      $separator = '&';
    } else {
      $link .= $page;
      $separator = '?';
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

// Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
    if ( ($add_session_id == true) && ($FSESSION->STARTED == true) && (SESSION_FORCE_COOKIE_USE == 'False') ) 
	{
      if (tep_not_null($SID)) 
	  {
        $_sid = $SID;
      } elseif ( ( ($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL == true) ) || ( ($request_type == 'SSL') && ($connection == 'NONSSL') ) ) 
	  {
        if (HTTP_COOKIE_DOMAIN != HTTPS_COOKIE_DOMAIN) {
          $_sid = $FSESSION->name . '=' . $FSESSION->ID;
        }
      }
    }
	// if ($GLOBALS["SEO_URL"]->enabled && $search_engine_safe == true){ 
      // while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);

      // $link = str_replace('?', '/', $link);
      // $link = str_replace('&', '/', $link);
      // $link = str_replace('=', '/', $link);

      // $separator = '?';
      // $link = $GLOBALS["SEO_URL"]->convert_seo_url($link);
	// }	
    if (isset($_sid)) {
	  $link .= $separator . tep_output_string($_sid);
    }
   	return $link;
  }


////
// The HTML image wrapper function
  // function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '') 
  // {
  // //  if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
    // if ( (empty($src) || ($src == DIR_WS_IMAGES)) ) {
      // return false;
  // }

// // alt is added to the img tag even if it is null to prevent browsers from outputting
// // the image filename as default
    // $image = '<img src="' . tep_output_string($src) . '" alt="' . tep_output_string($alt) . '"';

    // if (tep_not_null($alt)) {
      // $image .= ' title=" ' . tep_output_string($alt) . ' "';
    // }

    // if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) 
	// {
      // if ($image_size = @getimagesize($src)) 
	  // {
        // if (empty($width) && tep_not_null($height)) 
		// {
          // $ratio = $height / $image_size[1];
          // $width = intval($image_size[0] * $ratio);
        // } elseif (tep_not_null($width) && empty($height)) 
		// {
          // $ratio = $width / $image_size[0];
          // $height = intval($image_size[1] * $ratio);
        // } elseif (empty($width) && empty($height)) 
		// {
          // $width = $image_size[0];
          // $height = $image_size[1];
        // }
      // } elseif (empty($src) || ($src == DIR_WS_IMAGES)) 
	  // {
  // //} elseif (IMAGE_REQUIRED == 'false') {
        // return false;
      // }
    // }

    // if (tep_not_null($width) && tep_not_null($height)) {
      // $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
    // }

    // if (tep_not_null($parameters)) $image .= ' ' . $parameters;

    // $image .= '/>';

    // return $image;
  // }
  
  
  // The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '', $responsive = true, $bootstrap_css = '') 
  {
    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) 
	{
      return false;
    }

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<img src="' . tep_output_string($src) . '" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
      $image .= ' title="' . tep_output_string($alt) . '"';
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) 
	{
      if ($image_size = @getimagesize($src)) 
	  {
        if (empty($width) && tep_not_null($height)) 
		{
          $ratio = $height / $image_size[1];
          $width = (int)($image_size[0] * $ratio);
        } elseif (tep_not_null($width) && empty($height)) 
		{
          $ratio = $width / $image_size[0];
          $height = (int)($image_size[1] * $ratio);
        } elseif (empty($width) && empty($height)) 
		{
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') 
	  {
        return false;
      }
    }

    if (tep_not_null($width) && tep_not_null($height)) 
	{
      $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
    }

    $image .= ' class="';

    if (tep_not_null($responsive) && ($responsive === true)) 
	{
      $image .= 'img-fluid';
    }

    if (tep_not_null($bootstrap_css)) $image .= ' ' . $bootstrap_css;

    $image .= '"';

    if (tep_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= ' />';

    return $image;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_submit($image, $alt = '', $parameters = '') 
  {
    global $FSESSION;

    $image_submit = '<input class="form-control" type="image" src="' . tep_output_string(DIR_WS_LANGUAGES . $FSESSION->language . '/images/buttons/' . $image) . '" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= '>';

    return $image_submit;
  }

////
// Output a function button in the selected language
  function tep_image_button($image, $alt = '', $parameters = '') 
  {
    global $FSESSION;

    return tep_image(DIR_WS_LANGUAGES . $FSESSION->language . '/images/buttons/' . $image, $alt, '', '', $parameters);
  }

////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_silver.gif', $width = '100%', $height = '1') 
  {
    return tep_image(DIR_WS_HTTP_CATALOG.DIR_WS_IMAGES . $image, '', $width, $height);
  }

////
// Output a form
	function tep_draw_form($name, $action, $method = 'post', $parameters = '') 
	{
		global $FREQUEST,$FSESSION;
		$form = '<form name="' . tep_output_string($name) . '" action="' . tep_output_string($action) . '" method="' . tep_output_string($method) . '"  autocomplete="off"';

		if (tep_not_null($parameters)) $form .= ' ' . $parameters;

		$form .= '>';
		if(!$FSESSION->is_registered('customer_id'))
			$FREQUEST->setvalue('osconcert',md5('osconcert'),'SERVER');

		return $form;
	}

////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true) 
  {
    $field = '<input class="form-control mb-3" type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
    } elseif (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }
  
  // function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true, $class = 'class="form-control"') {
    // $field = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    // if ( ($reinsert_value == true) && ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) ) {
      // if (isset($_GET[$name]) && is_string($_GET[$name])) {
        // $value = stripslashes($_GET[$name]);
      // } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
        // $value = stripslashes($_POST[$name]);
      // }
    // }

    // if (tep_not_null($value)) {
      // $field .= ' value="' . tep_output_string($value) . '"';
    // }

    // if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    // if (tep_not_null($class)) $field .= ' ' . $class;

    // $field .= ' />';

    // return $field;
  // }


////
// Output a form password field
  function tep_draw_password_field($name, $value = '', $parameters = 'maxlength="40"') 
  {
    return tep_draw_input_field($name, $value, $parameters, 'password', false);
  }

////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') 
  {
    $selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '" ';

    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';

    if ( ($checked == true) || ( isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) && ( ($GLOBALS[$name] == 'on') || (isset($value) && (stripslashes($GLOBALS[$name]) == $value)) ) ) ) 
	{
      $selection .= ' CHECKED';
    }

    if (tep_not_null($parameters)) $selection .= ' ' . $parameters;

    $selection .= '>';

    return $selection;
  }

////
// Output a form checkbox field
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
  }

////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'radio', $value, $checked, $parameters);
  }

////
// Output a form textarea field
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    $field = '<textarea class="form-control" name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
  		$field .= tep_output_string_protected(stripslashes($GLOBALS[$name]));
	  } elseif (tep_not_null($text)) {
		$field .= tep_output_string_protected($text);
      }

$field .= '</textarea>';

    return $field;
  }

////
// Output a form hidden field
  function tep_draw_hidden_field($name, $value = '', $parameters = '') 
  {
    $field = '<input type="hidden" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) 
	{
      $field .= ' value="' . tep_output_string($value) . '"';
    } elseif (isset($GLOBALS[$name])) 
	{
      $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }

////
// Hide form elements
  function tep_hide_session_id() 
  {
    global $SID,$FSESSION;

    if (($FSESSION->STARTED == true) && tep_not_null($SID)) 
	{
      return tep_draw_hidden_field($FSESSION->name, $FSESSION->ID);
    }
  }

////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) 
  {      

    $field = '<select class="form-control mb-3" name="' . tep_output_string($name) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';
    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id'] || $default == strtolower($values[$i]['text'])) 
	  {
        $field .= ' SELECTED';
      }

      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>'; 
    }
    $field .= '</select>';
    if ($required == true) $field .= TEXT_FIELD_REQUIRED;
    return $field;
  }

////
// Creates a pull-down list of countries
  function tep_get_country_list($name, $selected = '', $parameters = '') 
  {
    $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
    $countries = tep_get_countries();

    for ($i=0, $n=sizeof($countries); $i<$n; $i++) 
	{
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }

    return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
  }
  function tep_load_template_content($filename){
  	 $file_path=DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . "/content/" . $filename;
	 $require_path=DIR_WS_TEMPLATES . TEMPLATE_NAME . "/content/" . $filename;
	 if (!file_exists($file_path)) return "";
  	 $content=@file_get_contents($file_path);
	 if ($content=="")
	 {
	 	ob_start();
		require(CHECK_CON . $require_path);
		$content=ob_get_contents();
		ob_end_clean();
	 }
	 return $content;
  }
  
    ///from oscommerce////
// Output a jQuery UI Button
  function tep_draw_button($title = null, $icon = null, $link = null, $priority = null, $params = null, $style = null) {
    static $button_counter = 1;

    $types = array('submit', 'button', 'reset');

    if ( !isset($params['type']) ) {
      $params['type'] = 'submit';
    }

    if ( !in_array($params['type'], $types) ) 
	{
      $params['type'] = 'submit';
    }

    if ( ($params['type'] == 'submit') && isset($link) ) 
	{
      $params['type'] = 'button';
    }

    if (!isset($priority)) 
	{
      $priority = 'secondary';
    }

    $button = NULL;

    if ( ($params['type'] == 'button') && isset($link) ) 
	{
      $button .= '<a id="btn' . $button_counter . '" href="' . $link . '"';

      if ( isset($params['newwindow']) ) 
	  {
        $button .= ' target="_blank"';
      }
    } else {
      $button .= '<button ';
      $button .= ' type="' . tep_output_string($params['type']) . '"';
    }

    if ( isset($params['params']) ) 
	{
      $button .= ' ' . $params['params'];
    }

    $button .= ' class="btn ';

    $button .= (isset($style)) ? $style : 'btn-default';

    $button .= '">';

    if (isset($icon) && tep_not_null($icon)) 
	{
      $button .= ' <span class="' . $icon . '"></span> ';
    }

    $button .= $title;

    if ( ($params['type'] == 'button') && isset($link) ) 
	{
      $button .= '</a>';
    } else {
      $button .= '</button>';
    }

    $button_counter++;

    return $button;
  }
?>

<?php
/* 
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	http://www.osconcert.com

  	Copyright (c) 2020 osConcert 
	
	Released under the GNU General Public License 
*/ 


  function osc_draw_input_field($name, $text = '', $type = 'text', $parameters = '', $reinsert_value = true) {
    $field = '<input type="' . $type . '" name="' . $name . '" class="form-control" ';
    if ( ($key = $GLOBALS[$name]) || ($key = $GLOBALS['HTTP_GET_VARS'][$name]) || ($key = $GLOBALS['HTTP_POST_VARS'][$name]) || ($key = $GLOBALS['HTTP_SESSION_VARS'][$name]) && ($reinsert_value) ) {
      $field .= ' value="' . $key . '"';
    } elseif ($text != '') {
      $field .= ' value="' . $text . '"';
    }
    if ($parameters) $field.= ' ' . $parameters;
    $field .= '>';

    return $field;
  }

  function osc_draw_password_field($name, $text = '') {
    return osc_draw_input_field($name, $text, 'password', '', false);
  }

  function osc_draw_hidden_field($name, $value) {
    return '<input type="hidden" id="' . $name . '" name="' . $name . '" value="' . $value . '">';
  }

  function osc_draw_selection_field($name, $type, $value = '', $checked = false) {
	if ($type == 'radio'){
		$class = "form-check-input";
	} else {
		$class = "form-check-inline";
	}
    $selection = '<input type="' . $type . '" name="' . $name . '" class="'.$class.'"';
    if ($value != '') $selection .= ' value="' . $value . '"';
    if ( ($checked == true) || ($GLOBALS[$name] == 'on') || ($value == 'on') || ($value && $GLOBALS[$name] == $value) ) {
      $selection .= ' CHECKED';
    }
    $selection .= ' class="' . $type . '">';

    return $selection;
  }

  function osc_draw_checkbox_field($name, $value = '', $checked = false) {
    return osc_draw_selection_field($name, 'checkbox', $value, $checked);
  }

  function osc_draw_radio_field($name, $value = '', $checked = false) {
    return osc_draw_selection_field($name, 'radio', $value, $checked);
  }
  function osc_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    $field = '<textarea name="' . osc_output_string($name) . '" wrap="' . osc_output_string($wrap) . '" cols="' . osc_output_string($width) . '" rows="' . osc_output_string($height) . '"';

    if (osc_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
  		$field .= osc_output_string_protected(stripslashes($GLOBALS[$name]));
	  } elseif (osc_not_null($text)) {
		$field .= osc_output_string_protected($text);
      }

$field .= '</textarea>';

    return $field;
  }
  function osc_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {

    $field = '<select name="' . osc_output_string($name) . '"';

    if (osc_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . osc_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' SELECTED';
      }

      $field .= '>' . osc_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }
  function osc_draw_time_zone_select_menu($name, $default = null) {
    if ( !isset($default) ) {
      $default = date_default_timezone_get();
    }

    $time_zones_array = array();

    foreach ( timezone_identifiers_list() as $id ) {
      $tz_string = str_replace('_', ' ', $id);

      $id_array = explode('/', $tz_string, 2);

      $time_zones_array[$id_array[0]][$id] = isset($id_array[1]) ? $id_array[1] : $id_array[0];
    }

    $result = array();

    foreach ( $time_zones_array as $zone => $zones_array ) {
      foreach ( $zones_array as $key => $value ) {
        $result[] = array('id' => $key,
                          'text' => $value,
                          'group' => $zone);
      }
    }

    return osc_draw_select_menu($name, $result, $default);
  }  
  function osc_draw_select_menu($name, $values, $default = null, $parameters = null) {
    $group = false;

    if ( isset($_GET[$name]) ) {
      $default = $_GET[$name];
    } elseif ( isset($_POST[$name]) ) {
      $default = $_POST[$name];
    }

    $field = '<select class="form-control" name="' . osc_output_string($name) . '"';

    if ( strpos($parameters, 'id=') === false ) {
      $field .= ' id="' . osc_output_string($name) . '"';
    }

    if ( !empty($parameters) ) {
      $field .= ' ' . $parameters;
    }

    $field .= '>';

    for ( $i=0, $n=count($values); $i<$n; $i++ ) {
      if ( isset($values[$i]['group']) ) {
        if ( $group != $values[$i]['group'] ) {
          $group = $values[$i]['group'];

          $field .= '<optgroup label="' . osc_output_string($values[$i]['group']) . '">';
        }
      }

      $field .= '<option value="' . osc_output_string($values[$i]['id']) . '"';

      if ( isset($default) && ((!is_array($default) && ((string)$default == (string)$values[$i]['id'])) || (is_array($default) && in_array($values[$i]['id'], $default))) ) {
        $field .= ' selected="selected"';
      }

      if ( isset($values[$i]['params']) ) {
        $field .= ' ' . $values[$i]['params'];
      }

      $field .= '>' . osc_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';

      if ( ($group !== false) && (($group != $values[$i]['group']) || !isset($values[$i+1])) ) {
        $group = false;

        $field .= '</optgroup>';
      }
    }

    $field .= '</select>';

    return $field;
  }  
?>

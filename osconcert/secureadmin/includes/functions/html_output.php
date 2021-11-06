<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  
  

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
  http://www.openfreeway.org

  Copyright 2007 ZacWare Pty. Ltd
*/

////
// The HTML href link wrapper function
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 
function forceHTTPS(){

  $httpsURL = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

//force all to https
if(tep_not_null(ENABLE_SSL_CATALOG)){
  if( !isset( $_SERVER['HTTPS'] ) || $_SERVER['HTTPS']!=='on' ){
    if( !headers_sent() ){
      header( "Status: 301 Moved Permanently" );
      header( "Location: $httpsURL" );
      exit();
    }
  }
}
}

//pheonix todo
// The HTML href link wrapper function
  // function tep_href_link($page = '', $parameters = '', $connection = 'SSL', $add_session_id = true) {
    // $page = tep_output_string($page);

    // if ($page == '') {
      // die(<<<EOERROR
// <h5>Error!</h5>
// <p>Unable to determine the page link!</p>
// <p>Function used:</p>
// <p>tep_href_link('$page', '$parameters', '$connection', '$add_session_id')</p>
// EOERROR
// );
    // }

    // $link = HTTP_SERVER . DIR_WS_ADMIN . $page;

    // if (tep_not_null($parameters)) {
      // $link .= '?' . tep_output_string($parameters);
      // $separator = '&';
    // } else {
      // $separator = '?';
    // }

    // $link = rtrim($link, '&?');

// // Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
    // if ( $add_session_id && isset($SID) && (SESSION_FORCE_COOKIE_USE == 'False') && tep_not_null($SID) ) {
      // $_sid = $SID;
    // }

    // if (isset($_sid)) {
      // $link .= $separator . tep_output_string($_sid);
    // }

    // while (strpos($link, '&&') !== false) {
      // $link = str_replace('&&', '&', $link);
    // }

    // return $link;
  // }


function tep_href_link($page = '', $parameters = '', $connection = 'SSL') {
  //function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL') {
    if ($page == '') {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>Function used:<br><br>tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
    }
    if ($connection == 'NONSSL') {
      $link = HTTP_SERVER . DIR_WS_ADMIN;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL_CATALOG == 'true') {
        $link = HTTPS_SERVER . DIR_WS_ADMIN;
      } else {
        $link = HTTP_SERVER . DIR_WS_ADMIN;
      }
    } else {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL<br><br>Function used:<br><br>tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
    }
    if ($parameters == '') {
      $link = $link . $page . '?' . SID;
    } else {
      $link = $link . $page . '?' . $parameters . '&' . SID;
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

    return $link;
  }

  function tep_catalog_href_link($page = '', $parameters = '', $connection = 'NONSSL') {
    if ($connection == 'NONSSL') {
      $link = HTTP_CATALOG_SERVER . DIR_WS_CATALOG;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL_CATALOG == 'true') {
        $link = HTTPS_CATALOG_SERVER . DIR_WS_CATALOG;
      } else {
        $link = HTTP_CATALOG_SERVER . DIR_WS_CATALOG;
      }
    } else {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL<br><br>Function used:<br><br>tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
    }
    if ($parameters == '') {
      $link .= $page;
    } else {
      $link .= $page . '?' . $parameters;
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

    return $link;
  }
  
	function tep_display_image($image,$title,$parameters=""){
		if ($image==""){
			$image='<div class="noImage">No Image</div>';
			return $image;
		}
		if (file_exists(DIR_FS_CATALOG_IMAGES . "small/" . $image)){
			return tep_image(DIR_WS_CATALOG_IMAGES . "small/" . $image,$title,'','',$parameters);
		} else if (file_exists(DIR_FS_CATALOG_IMAGES . "big/" . $image)){
			return tep_image(DIR_WS_CATALOG_IMAGES . "big/" . $image,$title,SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT,$parameters);
		} else if (file_exists(DIR_FS_CATALOG_IMAGES . $image)){
			return tep_image(DIR_WS_CATALOG_IMAGES . $image,$title,SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT,$parameters);
		} else { 
			$image='<div class="noImage">No Image</div>';
			return $image;
		}
	}


////
// The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $params = '',$noimage=false) {
  	if ($noimage && !file_exists(DIR_FS_CATALOG_IMAGES .basename($src))){
		$image='<div class="noImage">No Image</div>';
		return $image;
	}
    $image = '<img src="' . $src . '" border="0" alt="' . $alt . '"';
    if ($alt) {
      $image .= ' title=" ' . $alt . ' "';
    }
    if ($width) {
      $image .= ' width="' . $width . '"';
    }
    if ($height) {
      $image .= ' height="' . $height . '"';
    }
    if ($params) {
      $image .= ' ' . $params;
    }
    $image .= '>';

    return $image;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  // function tep_image_submit($image, $alt = '', $parameters = '') {
    // global $FSESSION;

    // $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_LANGUAGES . $FSESSION->language . '/images/buttons/' . $image) . '" border="0" alt="' . tep_output_string($alt) . '"';

    // if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    // if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    // $image_submit .= '>';

    // return $image_submit;
  // }
function tep_image_submit($image, $alt = '', $parameters = '') 
{
	global $FSESSION;
	$css_submit = '<div><input type="submit" class="btn btn-primary btn-sm" value="' . tep_output_string($alt) . '" /></div>';
	return $css_submit;
}
////
// Draw a 1 pixel black line
  function tep_black_line() {
    return tep_image(DIR_WS_IMAGES . 'pixel_black.gif', '', '100%', '1');
  }

////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
  }

////
// Output a function button in the selected language
  // function tep_image_button($image, $alt = '', $params = '') {
    // global $FSESSION;

    // return tep_image(DIR_WS_LANGUAGES . $FSESSION->language . '/images/buttons/' . $image, $alt, '', '', $params);
  // }

function tep_image_button($image, $value = '-AltValue-', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary">&nbsp;' . tep_output_string($value) . '&nbsp;</span>';
	return $image;
}
function tep_image_button_sm($image, $value = '-AltValue-', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary btn-sm">&nbsp;' . tep_output_string($value) . '&nbsp;</span>';
	return $image;
}
function tep_image_button_delete($image, $value = ' DELETE ', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary">&nbsp;' . tep_output_string($value) . '&nbsp;</span>';
	return $image;
}
function tep_image_button_cancel($image, $value = ' CANCEL ', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary btn-sm" style="padding:2px">&nbsp;' . tep_output_string($value) . '&nbsp;</span>';
	return $image;
}
function tep_image_button_create($image, $value = ' CREATE ', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary">&nbsp;' . tep_output_string($value) . '&nbsp;</span>';
	return $image;
}
function tep_image_button_modify($image, $value = ' MODIFY ', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary">&nbsp;' . tep_output_string($value) . '&nbsp;</span>';
	return $image;
}
function tep_image_button_move($image, $value = ' MOVE ', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary btn-sm">&nbsp;' . tep_output_string($value) . '&nbsp;</span>';
	return $image;
}
function tep_image_button_copy($image, $value = ' COPY ', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary btn-sm">&nbsp;' . tep_output_string($value) . '&nbsp;</span>';
	return $image;
}
function tep_image_button_send($image, $value = ' SEND ', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary btn-sm">&nbsp;' . tep_output_string($value) . '&nbsp;</span>';
	return $image;
}

////
// javascript to dynamically update the states/provinces list when the country is changed
// TABLES: zones
  function tep_js_zone_list($country, $form, $field) {
    $countries_query = tep_db_query("select distinct zone_country_id from " . TABLE_ZONES . " order by zone_country_id");
    $num_country = 1;
    $output_string = '';
    while ($countries = tep_db_fetch_array($countries_query)) {
      if ($num_country == 1) {
        $output_string .= '  if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
      } else {
        $output_string .= '  } else if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
      }

      $states_query = tep_db_query("select zone_name, zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$countries['zone_country_id'] . "' order by zone_name");

      $num_state = 1;
      while ($states = tep_db_fetch_array($states_query)) {
        if ($num_state == '1') $output_string .= '    ' . $form . '.' . $field . '.options[0] = new Option("' . PLEASE_SELECT . '", "");' . "\n";
        $output_string .= '    ' . $form . '.' . $field . '.options[' . $num_state . '] = new Option("' . $states['zone_name'] . '", "' . $states['zone_id'] . '");' . "\n";
        $num_state++;
      }
      $num_country++;
    }
    $output_string .= '  } else {' . "\n" .
                      '    ' . $form . '.' . $field . '.options[0] = new Option("' . TYPE_BELOW . '", "");' . "\n" .
                      '  }' . "\n";

    return $output_string;
  }

////
// Output a form
  function tep_draw_form($name, $action, $parameters = '', $method = 'post', $params = '') {
    $form = '<form autocomplete="off" name="' . tep_output_string($name) . '" action="';
    if (tep_not_null($parameters)) {
      $form .= tep_href_link($action, $parameters);
    } else {
      $form .= tep_href_link($action);
    }
    $form .= '" method="' . tep_output_string($method) . '"';
    if (tep_not_null($params)) {
      $form .= ' ' . $params;
    }
    $form .= '>';

    return $form;
  }

////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $required = false, $type = 'text', $reinsert_value = true) 
  {

    $field = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '" id="'.tep_output_string($name).'"';

  /* if (isset($GLOBALS[$name]) && ($reinsert_value == true) && is_string($GLOBALS[$name])) {
      $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
    }*/
	if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    }
    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
	$field.=' class="osc-form-control-sm inputNormal" ';
	 if(($name=="password")or($name=="email_address"))
	 {
		   $entry_placeholder=$name;
	 }
	 else
	 {
		 $entry_placeholder="";
	 }
	// if ($entry_placeholder=="email_address")
	// {
	 // $entry_placeholder=ENTRY_EMAIL_ADDRESS;
	// }
	// if ($entry_placeholder=="password")
	// {
	 // $entry_placeholder=ENTRY_PASSWORD;
	// }
    $field .= ' placeholder="'.$entry_placeholder.'">';
	//$field .= '>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

////
// Output a form password field
  function tep_draw_password_field($name, $value = '', $required = false,$params='') {
    $field = tep_draw_input_field($name, $value, $params.' maxlength="40"', $required, 'password', false);

    return $field;
  }

////
// Output a form filefield
  function tep_draw_file_field($name, $required = false,$image_filter=true) {
   if ($image_filter){
     $field = tep_draw_input_field($name, '', 'accept="image/gif,image/jpeg,image/png,zip"', $required, 'file');
   } else {
   	 $field = tep_draw_input_field($name, '', '', $required, 'file');
   }
   return $field;
  }

//Admin begin
////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
//  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $compare = '') {
//    $selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';
//
//    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';
//
//    if ( ($checked == true) || (isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) && ($GLOBALS[$name] == 'on')) || (isset($value) && isset($GLOBALS[$name]) && (stripslashes($GLOBALS[$name]) == $value)) || (tep_not_null($value) && tep_not_null($compare) && ($value == $compare)) ) {
//      $selection .= ' CHECKED';
//    }
//
//    $selection .= '>';
//
//    return $selection;
//  }
//
////
// Output a form checkbox field
//  function tep_draw_checkbox_field($name, $value = '', $checked = false, $compare = '') {
//    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $compare);
//  }
//
////
// Output a form radio field
//  function tep_draw_radio_field($name, $value = '', $checked = false, $compare = '') {
//    return tep_draw_selection_field($name, 'radio', $value, $checked, $compare);
//  }
////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $compare = '', $parameter = '') {
    $selection = '<input  class="inputNormal" onBlur="javascript:toggle_focus(this,1);" onFocus="javascript:toggle_focus(this,2);" type="' . $type . '" name="' . $name . '" id="' . $name . '"';
    if ($value != '') {
      $selection .= ' value="' . $value . '"';
    }
    if ( ($checked == true) || ($GLOBALS[$name] == 'on') || ($value && ($GLOBALS[$name] == $value)) || ($value && ($value == $compare)) ) {
      $selection .= ' CHECKED';
    }
    if ($parameter != '') {
      $selection .= ' ' . $parameter;
    } 
    $selection .= '>';

    return $selection;
  }

////
// Output a form checkbox field
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $compare = '', $parameter = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $compare, $parameter);
  }

////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false, $compare = '', $parameter = '') {
    return tep_draw_selection_field($name, 'radio', $value, $checked, $compare, $parameter);
  }
//Admin end

////
// Output a form textarea field
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    $field = '<textarea class="osc-form-control" name="' . tep_output_string($name) . '" id="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
	$field.=' class="inputNormal"';
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
  function tep_draw_hidden_field($name, $value = '', $parameters = '') {
    $field = '<input type="hidden" name="' . tep_output_string($name) . '" id="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    } elseif (isset($GLOBALS[$name]) && is_string($GLOBALS[$name])) {
      $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }

////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    $field = '<select class="osc-form-control-sm" name="' . tep_output_string($name) . '" id="'.$name.'"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
	$field.=' class="inputNormal"';

    $field .= '>';
	
	if(!is_array($GLOBALS[$name])){
    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);
	}
    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
		if (isset($values[$i]['id'])) {
      $field .= '<option class="osc-form-control" value="' . tep_output_string($values[$i]['id']) . '"';
		}
	  if (isset($values[$i]['style'])) $field.=' style="' . $values[$i]['style'] . '"';	
		if (isset($values[$i]['id'])) {
      if ($default == $values[$i]['id']) {
        $field .= ' SELECTED';
      }
		}
	//  $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
	if (isset($values[$i]['text'])) {
     $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
	}
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }
  
  // Output a form pull down menu
  function tep_draw_pull_down_menu2($name, $values, $default = '', $parameters = '', $required = false) {
    $field = '<select name="' . tep_output_string($name) . '" id="'.$name.'"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
	$field.=' class="inputNormal" onBlur="javascript:toggle_focus(this,1);" onFocus="javascript:toggle_focus(this,2);"';

    $field .= '>';
	
	if(!is_array($GLOBALS[$name])){
    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);
	}
    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
		if (isset($values[$i]['id'])) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
		}
	  if (isset($values[$i]['style'])) $field.=' style="' . $values[$i]['style'] . '"';	
		if (isset($values[$i]['id'])) {
      if ($default == $values[$i]['id']) {
        $field .= ' SELECTED';
      }
		}
	//  $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
	if (isset($values[$i]['text'])) {
     $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
	}
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }
  
//PR Algozone.com: Output a form multiple select menu
  function tep_draw_mselect_menu($name, $values, $selected_vals, $params = '', $required = false) {
    global $current_events_category_id,$current_subscription_category_id;


    $field = '<select class="osc-form-control-sm" name="' . $name . '"';
	$field.=' class="inputNormal""';// onBlur="javascript:toggle_focus(this,1);" onFocus="javascript:toggle_focus(this,2);
    if ($params) $field .= ' ' . $params;
    $field .= ' multiple>';
    for ($i=0; $i<sizeof($values); $i++) {
	if ($values[$i]['id'])
	{
      	$field .= '<option class="osc-form-control-sm" value="' . $values[$i]['id'] . '"';
      	if ( ((strlen($values[$i]['id']) > 0) && ($GLOBALS[$name] == $values[$i]['id'])) ) {
      	  $field .= ' SELECTED';
      	}
    		else 
		{
			for ($j=0; $j<sizeof($selected_vals); $j++) {
			//echo 'values' .$values[$i]['id'] . $selected_vals[$j]['id'] . '<br>';
				if ($selected_vals[$j]['id'] == $values[$i]['id'] )// || $values[$i]['id']==$current_events_category_id || $values[$i]['id']==$current_subscription_category_id)
				{
			        $field .= ' SELECTED';
				}
				
			}
			//print_r($selected_vals);
			
		}
	}
      $field .= '>' . $values[$i]['text'] . '</option>';
	 
    }
    $field .= '</select>';

    if ($required) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }
	// Function for multiselect checkbox
	function tep_draw_mselect_checkbox($name, $values, $selected_vals, $params = '', $required = false,$width='220'){
	$field='<div class="osc-form-control" style="width:' . $width . 'px;height:100px;overflow:auto;border:solid 1px #CCCCCC;"';
		if($params!='')
			$field.=$params;
	$field.=' class="inputNormal" align="justify">';

	for($i=0;$i<sizeof($values);$i++){ 
		$check_flag=false;
		
		for($j=0;$j<sizeof($selected_vals);$j++)
			if($values[$i]['id']==$selected_vals[$j]['id'])
				$check_flag=true;
		
		$field.=tep_draw_checkbox_field($name,$values[$i]['id'],$check_flag).$values[$i]['text'].'<br>';
	}
	$field.='</div>';
	if($required) $field.=TEXT_FIELD_REQUIRED;
	return $field;	
	}

	function tep_resize_image($inputFilename,$outputFilename,$image_type,$new_mode)
	{	
		$imagedata = @getimagesize($inputFilename);
		if (!$imagedata) return false;
		$w = $imagedata[0];	
		$h = $imagedata[1];

		if ($w>$new_mode || $h>$new_mode){
			if ($h > $w) {
				$new_w = ($new_mode / $h) * $w;		
				$new_h = $new_mode;
			} else {		
				$new_h = ($new_mode/ $w) * $h;
				$new_w = $new_mode;
			}
		} else {
			$new_w=$w;
			$new_h=$h;
		}
		//$im2 = @ImageCreateTrueColor($new_w, $new_h);
		// call function according to the file type
		switch(strtolower($image_type)){
			case ".jpg":
			case ".jpeg":
				if(@ImageCreateFromJpeg($inputFilename))
					$image = ImageCreateFromJpeg($inputFilename);
				break;
			case ".gif":
				if(@ImageCreateFromGif($inputFilename))
					$image = ImageCreateFromGif($inputFilename);
				break;
			case ".png":
				if(@(ImageCreateFromPng($inputFilename)))
					$image = ImageCreateFromPng($inputFilename);
				break;
		}
		if (!$image) return;
		
		$im2 = imagecreatetruecolor($new_w, $new_h);
		if (!$im2)  return false;

		$trnprt_indx = imagecolorat($image, 0,127);
		imagefill($im2, 0, 0, $trnprt_indx);
		imagecolortransparent($im2, $trnprt_indx);       
		
		if( (strtolower($image_type) == ".gif") || (strtolower($image_type) == ".png") ){
                // Turn off transparency blending (temporarily)
                imagealphablending($im2, false);
  
                // Create a new transparent color for image
                $color = imagecolorallocatealpha($im2, 0, 0, 0, 127);
  
                // Completely fill the background of the new image with allocated color.
                imagefill($im2, 0, 0, $color);
  
                // Restore transparency blending
                imagesavealpha($im2, true);
		}
	
		imagecopyresampled($im2, $image, 0, 0, 0, 0, $new_w, $new_h, $imagedata[0], $imagedata[1]);
		
		switch(strtolower($image_type)){
			case ".jpg":
			case ".jpeg":
				@imagejpeg($im2,$outputFilename);
				break;
			case ".gif":
				@imagegif($im2,$outputFilename);
				break;
			case ".png":
				@imagepng($im2,$outputFilename);
				break;
            default:
                return false;
		}
		imagedestroy($im2);
		return true;
	}  
	
	function tep_delete_temp_files($report_prefix){
		$pattern=DIR_FS_CATALOG_IMAGES . $report_prefix ."_*";
		$files=glob($pattern);
		if (!$files) return;
		for ($jcnt=0;$jcnt<count($files);$jcnt++){
			@unlink($files[$jcnt]);
		}
	}
	
	// Output a jQuery UI Button
  function tep_draw_button($title = null, $icon = null, $link = null, $priority = null, $params = null) {
    static $button_counter = 1;

    $types = array('submit', 'button', 'reset');

    if ( !isset($params['type']) ) {
      $params['type'] = 'submit';
    }

    if ( !in_array($params['type'], $types) ) {
      $params['type'] = 'submit';
    }

    if ( ($params['type'] == 'submit') && isset($link) ) {
      $params['type'] = 'button';
    }

    if (!isset($priority)) {
      $priority = 'secondary';
    }

    $button = '<span class="tdbLink">';

    if ( ($params['type'] == 'button') && isset($link) ) {
      $button .= '<a id="tdb' . $button_counter . '" href="' . $link . '"';

      if ( isset($params['newwindow']) ) {
        $button .= ' target="_blank"';
      }
    } else {
      $button .= '<button id="tdb' . $button_counter . '" type="' . tep_output_string($params['type']) . '"';
    }

    if ( isset($params['params']) ) {
      $button .= ' ' . $params['params'];
    }

    $button .= '>' . $title;

    if ( ($params['type'] == 'button') && isset($link) ) {
      $button .= '</a>';
    } else {
      $button .= '</button>';
    }

    $button .= '</span><script type="text/javascript">$("#tdb' . $button_counter . '").button(';

    $args = array();

    if ( isset($icon) ) {
      if ( !isset($params['iconpos']) ) {
        $params['iconpos'] = 'left';
      }

      if ( $params['iconpos'] == 'left' ) {
        $args[] = 'icons:{primary:"ui-icon-' . $icon . '"}';
      } else {
        $args[] = 'icons:{secondary:"ui-icon-' . $icon . '"}';
      }
    }

    if (empty($title)) {
      $args[] = 'text:false';
    }

    if (!empty($args)) {
      $button .= '{' . implode(',', $args) . '}';
    }

    $button .= ').addClass("ui-priority-' . $priority . '").parent().removeClass("tdbLink");</script>';

    $button_counter++;

    return $button;
  }
?>

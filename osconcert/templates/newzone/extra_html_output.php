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
//confirmation
// ############################ submit buttons############################
function tep_template_image_confirm($image, $value = '-AltValue-', $parameters = '') 
{
	global $FSESSION;
	$css_submit = '<input '.$parameters.' class="btn btn-primary btn-block btn-lg" type="submit" value="' . tep_output_string($value) . '" />';
	return $css_submit;
}
// ############################ submit buttons############################
function tep_template_image_submit($image, $value = '-AltValue-', $parameters = '') 
{
	global $FSESSION;
	$css_submit = '<div><input type="submit" class="btn btn-primary" value="' . tep_output_string($value) . '" /></div>';
	return $css_submit;
}
function tep_template_image_search($image, $value = '-AltValue-', $parameters = '') 
{
	global $FSESSION;
	$css_submit = '<input type="submit" class="btn btn-primary" value="' . tep_output_string($value) . '" />';
	return $css_submit;
}
function tep_template_image_button_cart($image, $value = '-AltValue-', $parameters = '') 
{
	global $FSESSION;
	$css_submit = '<div><input type="submit" onClick="document.forms[\"frmProduct\"].submit()" class="btn btn-primary" value="' . tep_output_string($value) . '" /></div>';
	return $css_submit;
}
// ########################  add to cart button ############################
// function tep_template_image_button_cart($image, $value = '-AltValue-', $parameters = '') 
// {
	// global $FSESSION;
	// $image = '<span class="btn btn-primary"><div onClick="javascript:submitData()" style="cursor:pointer;cursor:hand;">&nbsp;' . tep_output_string($value) . '&nbsp;</div></span>';
	// return $image;
// }
// ##################### reset button ####################################
function tep_template_image_button_reset($image, $value = '-AltValue-', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary"><div onclick="reset_form()" style="cursor:pointer;cursor:hand;">&nbsp;' . tep_output_string($value) . '&nbsp;</div></span>';
	return $image;
}
// ################## validate button ####################################
function tep_template_image_button_val($image, $value = '-AltValue-', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary"><div onClick="javascript:validateForm()" style="cursor:pointer;cursor:hand;">&nbsp;' . tep_output_string($value) . '&nbsp;</div></span>';
	return $image;
}
// ############## regular button ########################################
function tep_template_image_button($image, $value = '-AltValue-', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary">&nbsp;' . tep_output_string($value) . '&nbsp;</span>';
	return $image;
}
// ############## regular button ########################################
function tep_template_image_button_checkout($image, $value = '-AltValue-', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary">&nbsp;' . tep_output_string($value) . '&nbsp;</span>';
	return $image;
}
// ############## regular button ########################################
function tep_template_image_button_basic($image, $value = '-AltValue-', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary">&nbsp;' . tep_output_string($value) . '&nbsp;</span>';
	return $image;
}
// ############## regular button ########################################
function tep_template_image_button_dark($image, $value = '-AltValue-', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary-dark">&nbsp;' . tep_output_string($value) . '&nbsp;</span>';
	return $image;
}
// ############## redeem button ########################################
function tep_template_image_button_redeem($image, $value = '-AltValue-', $parameters = '') 
{
	global $FSESSION;
	$image = '<span class="btn btn-primary"><div onclick="javascript:submitFunction();">&nbsp;' . tep_output_string($value) . '&nbsp;</div></span>';
	return $image;
}
// ############## dont redeem button ########################################
function tep_template_image_button_dont($image, $value = '-AltValue-', $parameters = '') 
{
	global $FSESSION;
	$image = '<span style="float:right" class="btn btn-primary" onclick="javascript:submitFunction(1);">' . tep_output_string($value) . '</span>';
	return $image;
}
function tep_template_image($image, $alt = '', $parameters = '') 
{
global $FSESSION;
return tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/' .  $image, $alt, '', '', $parameters);
}

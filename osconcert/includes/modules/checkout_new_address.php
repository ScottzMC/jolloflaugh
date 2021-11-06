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
  if (!isset($process)) $process = false;
?>
<table width="100%" cellpadding="2">
<?php
  if (ACCOUNT_GENDER == 'true') 
  {
    if (isset($gender)) 
	{
      $male = ($gender == 'm') ? true : false;
      $female = ($gender == 'f') ? true : false;
    } else 
	{
      $male = false;
      $female = false;
    }
?>
  <tr>
    <td class="main"><?php echo ENTRY_GENDER; ?></td>
    <td class="main"><?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></td>
  </tr>
<?php
  }
?>
  <tr>
    <td class="main"><?php echo ENTRY_FIRST_NAME  . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></td>
    <td class="main"><?php echo tep_draw_input_field('firstname', $FREQUEST->postvalue('firstname') ); ?></td>
  </tr>
  <tr>
    <td class="main"><?php echo ENTRY_LAST_NAME . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></td>
    <td class="main"><?php echo tep_draw_input_field('lastname', $FREQUEST->postvalue('lastname') ); ?></td>
  </tr>
 <?php if (ACCOUNT_TELEPHONE == 'true') 
 { 
?>
  <tr>
    <td class="main"><?php 
	echo ENTRY_TELEPHONE_NUMBER . '&nbsp;' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''); 
	?></td>
    <td class="main"><?php echo tep_draw_input_field('customer_telephone', $FREQUEST->postvalue('customer_telephone')); ?></td>
  </tr>
  </tr>
<?php
  }
?>
<?php if (ACCOUNT_CUSTOMER_EMAIL == 'true') {//new added 14-08-17 ?>
  <tr>
    <td class="main"><?php echo ENTRY_CUSTOMER_EMAIL . '&nbsp;' . (tep_not_null(ENTRY_CUSTOMER_EMAIL_TEXT) ? '<span class="inputRequirement">' . ENTRY_CUSTOMER_EMAIL_TEXT . '</span>': ''); ?></td>
    <td class="main"><?php echo tep_draw_input_field('customer_email', $FREQUEST->postvalue('customer_email')); ?></td>
  </tr>
<?php
  }
?>
  <tr>
<?php
  if (ACCOUNT_COMPANY == 'true') 
  {
?>
  <tr>
    <td class="main"><?php echo ENTRY_COMPANY . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></td>
    <td class="main"><?php echo tep_draw_input_field('company', $FREQUEST->postvalue('company') ); ?></td>
  </tr>
<?php
  }
  ?>
  <?php
	if (ACCOUNT_ADDRESS == 'true') 
	{
?>
  <tr>
    <td class="main"><?php echo ENTRY_STREET_ADDRESS . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></td>
    <td class="main"><?php echo tep_draw_input_field('street_address', $FREQUEST->postvalue('street_address') ); ?></td>
  </tr>
  <?php
  }
  ?>
 <?php
	if (ACCOUNT_SUBURB == 'true') 
	{
?>
  <tr>
    <td class="main"><?php echo ENTRY_SUBURB . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></td>
    <td class="main"><?php echo tep_draw_input_field('suburb', $FREQUEST->postvalue('suburb') ); ?></td>
  </tr>
<?php
  }  
?>
 <?php
	if (ACCOUNT_CITY == 'true') 
	{
?>
 <tr>
    <td class="main"><?php echo ENTRY_CITY . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?></td>
    <td class="main"><?php echo tep_draw_input_field('city', $FREQUEST->postvalue('city') ); ?></td>
  </tr>
  <?php
  }
  ?>
   <?php
	if (ACCOUNT_POST_CODE == 'true') 
	{
?>
  <tr>
    <td class="main"><?php echo ENTRY_POST_CODE . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></td>
    <td class="main"><?php echo tep_draw_input_field('postcode', $FREQUEST->postvalue('postcode') ); ?></td>
  </tr>
  <?php
  }
  ?>
  <tr>
	<td class="main"><?php echo ENTRY_COUNTRY . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?></td>
	<td class="main" nowrap><?php 
	$value_query = tep_db_query("select default_value from " . TABLE_CUSTOMERS_INFO_FIELDS . " where info_id = '16'");
	while ($value_values = tep_db_fetch_array($value_query)) {
	$default_value=$value_values['default_value'];
	}
	//$default_value=222;//$entry['entry_country_id'];
	echo tep_get_country_list('country', $default_value,'id="country" onchange="javascript:show_state();"'); ?></td>
  </tr>
 
			<?php
  if (ACCOUNT_STATE == 'true') 
  {
  ?>
  <tr>
	<td class="main"><?php echo ENTRY_STATE .'<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>'; ?></td>
	<td class="main">
<?php 
		
	echo tep_draw_input_field('state1',  tep_get_zone_name($entry['entry_country_id'], $entry['entry_zone_id'], $entry['entry_state']),'id="state1"');
	echo tep_draw_pull_down_menu('state',array(),'','id="state" style="display:none;"');	
    
?></td>
    </tr> 
<?php
  }
?>
</table>
<script>
$(document).ready(function(){
  // we call the function to populate the state field
  show_state();
});
</script>
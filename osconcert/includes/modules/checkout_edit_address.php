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
  
$addresses_query = tep_db_query("SELECT
		address_book_id,
		entry_gender as gender,
		entry_firstname as firstname,
		entry_lastname as lastname,
		entry_customer_email as customer_email,
		entry_company as company,
		entry_street_address as street_address,
		entry_suburb as suburb,
		entry_city as city,
		entry_postcode as postcode,
		entry_state as state,
		entry_zone_id as zone_id,
		entry_country_id as country_id
	FROM
		" . TABLE_ADDRESS_BOOK . " 
	WHERE
		address_book_id = '" . (int)( (isset($_GET['id']) && (int)$_GET['id']>0) ? $_GET['id'] : 0 ) . "'");
$addr = tep_db_fetch_array($addresses_query);
?>
<input name="action" value="submit" type="hidden">
<input name="id" value="<?php echo (int)( (isset($_GET['id']) && (int)$_GET['id']>0) ? $_GET['id'] : 0 ); ?>" type="hidden">
<input name="update_address" value="true" type="hidden">
<table width="100%" cellpadding="2">
<?php
  if (ACCOUNT_GENDER == 'true') {
    if (isset($gender)) {
      $male = ($gender == 'm') ? true : false;
      $female = ($gender == 'f') ? true : false;
    } else {
      $male = ($addr['gender'] === 'm') ? true : false;
      $female = ($addr['gender'] === 'f') ? true : false;
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
    <td class="main"><?php echo tep_draw_input_field('firstname', $addr['firstname'] ); ?></td>
  </tr>
  <tr>
    <td class="main"><?php echo ENTRY_LAST_NAME . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></td>
    <td class="main"><?php echo tep_draw_input_field('lastname', $addr['lastname'] ); ?></td>
  </tr>
  </tr>
<?php
  if (ACCOUNT_CUSTOMER_EMAIL == 'true') {//new added 14-08-17
?>
  <tr>
    <td class="main"><?php echo ENTRY_CUSTOMER_EMAIL . '&nbsp;' . (tep_not_null(ENTRY_CUSTOMER_EMAIL_TEXT) ? '<span class="inputRequirement">' . ENTRY_CUSTOMER_EMAIL_TEXT . '</span>': ''); ?></td>
    <td class="main"><?php echo tep_draw_input_field('customer_email', $addr['customer_email']); ?></td>
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
    <td class="main"><?php echo tep_draw_input_field('company', $addr['company'] ); ?></td>
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
    <td class="main"><?php echo tep_draw_input_field('street_address', $addr['street_address'] ); ?></td>
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
    <td class="main"><?php echo tep_draw_input_field('suburb', $addr['suburb'] ); ?></td>
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
    <td class="main"><?php echo tep_draw_input_field('city', $addr['city'] ); ?></td>
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
    <td class="main"><?php echo tep_draw_input_field('postcode', $addr['postcode'] ); ?></td>
  </tr>
  <?php
  }
  ?>
  <tr>
	<td class="main"><?php echo ENTRY_COUNTRY . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?></td>
	<td class="main" nowrap><?php 
	
	echo tep_get_country_list('country', $addr['country_id'],'id="country" onchange="javascript:show_state();" style="width:180px"'); 
	?></td>
<?php
  if (ACCOUNT_STATE == 'true') 
  {
  ?>
	<tr>
	<td class="main"><?php echo ENTRY_STATE .'<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>'; ?></td>
	<td class="main">
<?php 
	echo tep_draw_input_field('state1',  tep_get_zone_name($addr['country_id'], $addr['zone_id'], $addr['state']),'id="state1"');
	echo tep_draw_pull_down_menu('state',array(),''.$addr['state'].'','id="state" style="display:none; width:180px"');
	echo $addr['state'];
    
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
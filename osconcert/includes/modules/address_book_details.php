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
<table width="100%">
  <tr>
    <td><table width="100%">
      <tr>
        <td class="main"><b><?php echo NEW_ADDRESS_TITLE; ?></b></td>
        <td class="required" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" class="infoBox">
      <tr class="infoBoxContents">
        <td><table>
<?php
  if (ACCOUNT_GENDER == 'true') {
    if (isset($gender)) {
      $male = ($gender == 'm') ? true : false;
    } else {
      $male = ($entry['entry_gender'] == 'm') ? true : false;
    }
    $female = !$male;
?>
          <tr>
            <td class="main"><?php echo ENTRY_GENDER; ?></td>
            <td class="main"><?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('firstname', $entry['entry_firstname']) . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('lastname', $entry['entry_lastname']) . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_COMPANY; ?></td>
            <td class="main"><?php echo tep_draw_input_field('company', $entry['entry_company']) . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
            <td class="main"><?php echo tep_draw_input_field('street_address', $entry['entry_street_address']) . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></td>
          </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_SUBURB; ?></td>
            <td class="main"><?php echo tep_draw_input_field('suburb', $entry['entry_suburb']) . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('postcode', $entry['entry_postcode']) . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CITY; ?></td>
            <td class="main"><?php echo tep_draw_input_field('city', $entry['entry_city']) . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?></td>
          </tr>

          <tr>
            <td class="main"><?php echo ENTRY_COUNTRY;
			if($country!="") {
			  if(is_numeric($state)) $entry['entry_zone_id']=$state;
			  else $entry['entry_state']=$state;
			  $entry['entry_country_id']=$country;
			}else if($entry['entry_country_id']=="" && $country=="") $entry['entry_country_id']=STORE_COUNTRY;
			?></td>
            <td class="main"><?php echo tep_get_country_list('country', $entry['entry_country_id'],'id="country" onchange="javascript:show_state();"') . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?></td>
          </tr>
			<?php
  if (ACCOUNT_STATE == 'true') {
  ?>
          <tr>
            <td class="main"><?php echo ENTRY_STATE; ?></td>
            <td class="main">
<?php
	 $zones_array = array();
     $zones_query = tep_db_query("select zone_id,zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$entry['entry_country_id'] . "' order by zone_name");
     while ($zones_values = tep_db_fetch_array($zones_query)) {
         $zones_array[] = array('id' => $zones_values['zone_id'], 'text' => $zones_values['zone_name']);
     }
	 echo tep_draw_input_field('state',  $entry['entry_state'],'id="state" '.((count($zones_array)>0)?' style="display:none" ':''));
     echo tep_draw_pull_down_menu('zone_id',$zones_array,$entry['entry_zone_id'],'id="zone_id" '.((count($zones_array)>0)?' ':' style="display:none" '));	
?></td>
          </tr> 
<?php
  }
?>
<?php
  if (($FREQUEST->getvalue('edit')!='' && ($FSESSION->customer_default_address_id != $FREQUEST->getvalue('edit'))) || ($FREQUEST->getvalue('edit') == "") ) {
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td colspan="2" class="main"><?php echo tep_draw_checkbox_field('primary', 'on', false, 'id="primary"') . ' ' . SET_AS_PRIMARY; ?></td>
          </tr>
<?php
  }
?>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>

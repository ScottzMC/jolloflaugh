<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<div>
<div class="section-header">
<h2><?php echo HEADING_TITLE; ?></h2>
</div>


<?php
  if ($messageStack->size('addressbook') > 0) {
?>
      <div><?php echo $messageStack->output('addressbook'); ?></div>
<?php
  }
?>
      <h4><?php echo PRIMARY_ADDRESS_TITLE; ?></h4>

	  <table width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table width="100%" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><?php echo PRIMARY_ADDRESS_DESCRIPTION; ?></td>
                <td align="right" width="50%" valign="top"><table cellpadding="2">
                  <tr>
                    <td class="main" align="center" valign="top"><b><?php echo PRIMARY_ADDRESS_TITLE; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" valign="top"><?php echo tep_address_label($FSESSION->customer_id, $FSESSION->customer_default_address_id, true, ' ', '<br>'); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table>
      <h4><?php echo ADDRESS_BOOK_TITLE; ?></h4>
      
	  <table width="100%" cellspacing="1" cellpadding="2" class="infoBox" style="display:true">
          <tr class="infoBoxContents">
            <td><table width="100%" cellpadding="2">
<?php
  $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$FSESSION->customer_id . "' order by lastname");
  while ($addresses = tep_db_fetch_array($addresses_query)) {
    $format_id = tep_get_address_format_id($addresses['country_id']);
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><table width="100%" cellpadding="2">
                <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
<!--                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses['address_book_id'], 'SSL'); ?>'">   -->
                    <td class="main"><b><?php echo tep_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']); ?></b><?php if ($addresses['address_book_id'] == $FSESSION->customer_default_address_id) echo '&nbsp;<small><i>' . PRIMARY_ADDRESS . '</i></small>'; ?></td>
                    <td class="main" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses['address_book_id'], 'SSL') . '">' . tep_template_image_button_basic('small_edit.gif', SMALL_IMAGE_BUTTON_EDIT) . '</a>';
                        if ($addresses['address_book_id'] == $FSESSION->customer_default_address_id){
                         ?>
                         <a href="javascript:void(0)"  onclick="javascript:alert('<?php echo TEXT_PRIMARY_ADDRESS_DELETE; ?>');">
                        <?php
                        } else{
                        echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $addresses['address_book_id'], 'SSL') . '">' ;
                        }
                        echo tep_template_image_button_basic('small_delete.gif', SMALL_IMAGE_BUTTON_DELETE) . '</a>';
                        ?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><table cellpadding="2">
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo tep_address_format($format_id, $addresses, true, ' ', '<br>'); ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
  }
?>
            </table></td>
          </tr>
        </table>
		
 
	  <table width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table width="100%" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button_basic('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
<?php
  if (tep_count_customer_address_book_entries() < MAX_ADDRESS_BOOK_ENTRIES) {
?>
                <td class="smallText" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL') . '">' . tep_template_image_button_basic('button_add_address.gif', IMAGE_BUTTON_ADD_ADDRESS) . '</a>'; ?></td>
<?php
  }
?>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table>
		
      
	  <?php echo sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES); ?>
	  </div>
<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
include(DIR_WS_INCLUDES.'http.js');
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
	echo tep_draw_form('checkout_address', tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL'), 'post', 'onSubmit="return check_form_optional(checkout_address);"'); ?><table width="100%">
	
	
	<div class="section-header">
	<h2><?php echo HEADING_TITLE; ?></h2>
	</div>



<?php
  if ($messageStack->size('checkout_address') > 0) {
?>
<div><?php echo $messageStack->output('checkout_address'); ?></div>
<?php
  }

  if ($process == false) {
?>
     <br><table width="100%" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?></b></td>
          </tr>
        </table>

			
			<table width="100%" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><?php echo TEXT_SELECTED_SHIPPING_DESTINATION; ?></td>
                <td align="right" width="50%" valign="top">
				
				<table cellpadding="2">
                  <tr>
                    <td class="main" align="center" valign="top"><?php echo '<b>' . TITLE_SHIPPING_ADDRESS . '</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                   
                    <td class="main" valign="top"><?php echo tep_address_label($FSESSION->customer_id, $FSESSION->sendto, true, ' ', '<br>'); ?></td>
                   
                  </tr>
                </table>

			
			</td>
          </tr>
        </table>
		

<?php
    if ($addresses_count > 1) {
?>
     <table width="100%" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo TABLE_HEADING_ADDRESS_BOOK_ENTRIES; ?></b></td>
          </tr>
        </table>
		
			
			<table width="100%" cellpadding="2">
              <tr>
                <td class="main" width="50%" valign="top"><?php echo TEXT_SELECT_OTHER_SHIPPING_DESTINATION; ?></td>
                <td class="main" width="50%" valign="top" align="right"><?php echo '<b>' . TITLE_PLEASE_SELECT . '</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?></td>
                
              </tr>
<?php
      $radio_buttons = 0;

      $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$FSESSION->customer_id . "'");

      while ($addresses = tep_db_fetch_array($addresses_query)) {


        $format_id = tep_get_address_format_id($addresses['country_id']);
?>
				
				<table width="100%" cellpadding="2">
<?php
       if ($addresses['address_book_id'] == $FSESSION->sendto) {

          echo '                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
        } else {
          echo '                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
        }
?>
                    <td class="main" colspan="2"><b><?php echo tep_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']); ?></b></td>
                    <td class="main" align="right"><?php echo tep_draw_radio_field('address', $addresses['address_book_id'], ($addresses['address_book_id'] == $FSESSION->sendto)); ?></td>
                  </tr>
                  <tr>
                    <td colspan="3">
					<table cellpadding="2">
                      <tr>
                        <td class="main"><?php echo tep_address_format($format_id, $addresses, true, ' ', ', '); ?></td>
                      </tr>
                    </table>
					</td>
                  </tr>
                </table>
				
<?php
        $radio_buttons++;
      }
?>
            </table>

<?php
    }
  }

  if ($addresses_count < MAX_ADDRESS_BOOK_ENTRIES) {
?>

		<br>
		<table width="100%" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo TABLE_HEADING_NEW_SHIPPING_ADDRESS; ?></b></td>
          </tr>
        </table>
			<table width="100%" cellpadding="2">
              <tr>
                <td class="main" width="100%" valign="top"><?php echo TEXT_CREATE_NEW_SHIPPING_ADDRESS; ?></td>
              </tr>
              <tr>
                <td>
				<table width="100%" cellpadding="2">
                  <tr>
                    <td><?php require(DIR_WS_MODULES . 'checkout_new_address.php'); ?></td>
                  </tr>
                </table>
				</td>
              </tr>
            </table>
			


<?php
  }
?>
			<br><table width="100%" cellpadding="2">
              <tr>
             
                <td class="main"><?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></td>
                <td class="main" align="right"><?php echo tep_draw_hidden_field('action', 'submit') . tep_template_image_submit('', IMAGE_BUTTON_CONTINUE); ?></td>
             
              </tr>
            </table>
<?php
  if ($process == true) {
?>

      <tr>
        <td><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '">' . tep_template_image_button_basic('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
      </tr>
<?php
  }
?>
  


		<div class="bs-stepper">
            <div class="bs-stepper-header">
              <div class="step" data-target="#delivery">
			   <a href="<?php echo tep_href_link('checkout_shipping.php', '', 'SSL'); ?>">
                <button type="button" class="btn step-trigger">
                  <span class="bs-stepper-circle">1</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_DELIVERY; ?></span>
                </button>
				</a>
              </div>
              <div class="line"></div>
              <div class="step active" data-target="#payment">
                <button type="button" class="btn step-trigger">
                  <span class="bs-stepper-circle">2</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_PAYMENT; ?></span>
                </button>
              </div>
              <div class="line"></div>
              <div class="step" data-target="#confirm">
                <button type="button" class="btn step-trigger" disabled="disabled">
                  <span class="bs-stepper-circle">3</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></span>
                </button>
              </div>
            </div>
          </div>
		  </form>
<?php
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
 echo tep_draw_form('order', tep_href_link(FILENAME_ACCOUNT, '', 'SSL')); ?>
<table class="main-table">

		<h3><?php echo HEADING_TITLE; ?></h3>

<tr>
	<td>
	<table width="100%" cellspacing="4" cellpadding="2">
		<tr><!-- style="background:#c0c0c0"-->
			<td valign="top"><?php echo tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/'.COMPANY_LOGO, HEADING_TITLE,'',''); ?></td>
			<?php 
			$status_sql="select * from " . TABLE_WALLET_UPLOADS ." where customers_id=" . (int)$FSESSION->customer_id ." and wallet_id=" .(int)$insert_id;
			$status_query=tep_db_query($status_sql);
			
			$status_result=tep_db_fetch_array($status_query);
			
			 ?>
			<td>
			<table width="100%">

				<tr>
					<td class="main"><?php echo TEXT_SUCCESS; ?><br><br></td>
				</tr>
				<?php  if ($status_result['payment_status']==1) { ?>
				<tr>
					<td class="smallText"><?php echo sprintf(TEXT_CREDIT,$currencies->format($status_result["amount"])); ?><br><br></td>
				</tr>
				<?php } ?>
				<tr>
					<td class="main">
						<?php 
							$balance=tep_get_wallet_balance($FSESSION->customer_id);
							echo sprintf(TEXT_WALLET_BALANCE,$currencies->format($balance));
						?>
					</td>
				</tr>
			</table>
			</td>
		</tr>
		</table>

	  <tr>
		<td>
		<table width="100%" cellspacing="1" cellpadding="2" class="infoBox">
		  <tr class="infoBoxContents">

		  <td>
		  <table width="100%" cellpadding="2">
			<tr> 
				<td align="right" class="main"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
			</tr>
		</table>
		</td>
		</tr>
		</table>
		</td>
	</tr>

	<tr>
		<td>
	  <div class="bs-stepper">
            <div class="bs-stepper-header">
              <div class="step" data-target="#delivery">
                <button type="button" class="btn step-trigger"  disabled="disabled">
                  <span class="bs-stepper-circle">1</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_DELIVERY; ?></span>
                </button>
              </div>
              <div class="line"></div>
              <div class="step" data-target="#payment">
                <button type="button" class="btn step-trigger"  disabled="disabled">
                  <span class="bs-stepper-circle">2</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_PAYMENT; ?></span>
                </button>
              </div>
              <div class="line"></div>
              <div class="step active" data-target="#confirm">
                <button type="button" class="btn step-trigger" disabled="disabled">
                  <span class="bs-stepper-circle">3</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></span>
                </button>
              </div>
            </div>
          </div>
		</td>
      </tr>
	</table>
</td>
</tr>
	</form>
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
<div class="section-header">
<h2><?php echo HEADING_TITLE; ?></h2>
</div>

<?php

  $FSESSION->set('payment',$FREQUEST->postvalue('payment'));


?>

			
			<table width="100%" cellpadding="2">
              <tr>
                <td class="main"><?php  echo '<b>' . HEADING_UPLOAD_TO . '</b> '; ?></td>
				<td class="main"><?php echo '<b>' . HEADING_UPLOAD_AMOUNT . '</b> ' ?><?php echo $currencies->format($FSESSION->wallet_amount) .'  <a href="' . tep_href_link(FILENAME_WALLET_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_address_label($FSESSION->customer_id, $FSESSION->billto, true, ' ', '<br>'); ?></td>
			  </tr>
			</table>
			

		

			
			<table width="100%" cellpadding="2">
              
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_PAYMENT_METHOD . '</b> <a href="' . tep_href_link(FILENAME_WALLET_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo $GLOBALS[$payment]->title; ?></td>
              </tr>
            </table>

		


<?php

  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
      $FSESSION->set('payment_info',$confirmation['title']);

?>
		
		<b><?php echo HEADING_PAYMENT_INFORMATION; ?></b>
		

		

			<table cellpadding="2">
              <tr>
                <td class="main" colspan="4"><?php echo $confirmation['title']; ?></td>
              </tr>
<?php
      for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) 
	  {
?>
              <tr>
             
                <td class="main"><?php echo $confirmation['fields'][$i]['title']; ?></td>
               
                <td class="main"><?php echo $confirmation['fields'][$i]['field']; ?></td>
              </tr>
<?php
      }
?>
            </table>

<?php
    }
  }
?>

<?php
  if (tep_not_null($FSESSION->comments)) {
?>

		
		<div><?php echo '<b>' . HEADING_WALLET_COMMENTS . '</b> <a href="' . tep_href_link(FILENAME_WALLET_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></div>
		



			
			<table width="100%" cellpadding="2">
              <tr>
                <td class="main"><?php echo nl2br(tep_output_string_protected($FSESSION->comments)); ?></td>
              </tr>
            </table>

		


<?php
  }
?>



		
		<table width="100%">
          <tr>
            <td align="right" class="main">
<?php
	$temp_array=array('info'=>array('total'=>$FSESSION->wallet_amount));
	$order=new objectInfo($temp_array);
	
	$validID=tep_create_random_value(5,'digits');
	$payment_params='validID=' . $validID;
	$payment=&$FSESSION->getRef('payment');
	if (isset($payment->form_action_url)) {
		$form_action_url=$payment->form_action_url;
	} else {
		$form_action_url = tep_href_link(FILENAME_WALLET_CHECKOUT_PROCESS,$payment_params, 'SSL');
	}
	$FSESSION->set('checkID',$validID);

	echo tep_draw_form('checkout_confirmation', $form_action_url, 'post');
	if (is_array($payment_modules->modules)) {
		echo $payment_modules->process_button();
	}
	echo tep_template_image_submit('button_confirm_upload.gif', IMAGE_BUTTON_CONFIRM_UPLOAD) . '</form>' . "\n";
?>
            </td>
          </tr>
        </table>
		

		
		
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
              <div class="step" data-target="#payment">
			  <a href="<?php echo tep_href_link('checkout_payment.php', '', 'SSL'); ?>">
                <button type="button" class="btn step-trigger">
                  <span class="bs-stepper-circle">2</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_PAYMENT; ?></span>
                </button>
				</a>
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
		  

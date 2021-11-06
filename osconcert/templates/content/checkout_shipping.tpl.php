<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
echo tep_draw_form('checkout_address', tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); 
?>
<?php if(DISCOUNT_CHECKOUT_MESSAGE !=''){
	?>
<div class="alert alert-primary" role="alert"><h2 style="text-align:center;margin-bottom:0"><?php echo DISCOUNT_CHECKOUT_MESSAGE; ?></h2></div>
<?php
}
?>

<div class="section-header">
<h2><?php echo HEADING_TITLE; ?></h2>
</div>
<?php

tep_output_differ_check();
	
	if (HIDE_DELIVERY_ADDRESS == 'no')
	{
	
?>
<b><?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?></b>

<?php

	}

	if($messageStack->size('checkout_shipping') >0) 
	{ ?>
	<div>
	<?php echo $messageStack->output('checkout_shipping'); ?>
	</div>				
	<?php 
	}
	?>

        <div style="display:<?php //hide delivery address/change address
		if (HIDE_DELIVERY_ADDRESS == 'no')
		{
		echo "inline"; 
		} else {
		echo "none";
		}
		?>">
          <table width="100%" cellpadding="2">
              <tr>
                <td class="main" width="50%" valign="top"><?php echo TEXT_CHOOSE_SHIPPING_DESTINATION . '<br><br><a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '">' . tep_template_image_button_basic('button_change_address.gif', IMAGE_BUTTON_CHANGE_ADDRESS) . '</a>'; ?></td>
                <td align="right" width="50%" valign="top">
				
				<table cellpadding="2">
                  <tr>
                    <td class="main" align="center" valign="top"><?php echo '<b>' . TITLE_SHIPPING_ADDRESS . '</b>&nbsp;&nbsp;<br>' . tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                    <td class="main" valign="top"><?php echo tep_address_label($FSESSION->customer_id, $FSESSION->sendto, true, ' ', '<br>'); ?></td>
                  </tr>
                </table>
				
				</td>
              </tr>
            </table>
			</div>
        <?php if (HIDE_DELIVERY_ADDRESS == 'no')
		{ ?>

      <?php } ?>


<?php
  if (tep_count_shipping_modules() > 0) 
  {
?>
<?php
	if (HIDE_DELIVERY_ADDRESS == 'no')
	{
	define('TABLE_HEADING_SHIPPING_METHOD', 'Booking Fee');
	$shipping_method=TABLE_HEADING_SHIPPING_METHOD;
	$shipping_info=TEXT_ENTER_SHIPPING_INFORMATION;
	}else
	{
	$shipping_method=TABLE_HEADING_BOOKING_FEE;
	$shipping_info=TEXT_ENTER_BOOKING_INFO;
	}
?>
<h4><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></h4>

<table width="100%" cellpadding="2">
<?php
    if (sizeof($quotes) > 1 && sizeof($quotes[0]) > 1) {
?>
              <tr>
               
                <td class="main" width="50%" valign="top"><?php echo TEXT_CHOOSE_SHIPPING_METHOD; ?></td>
                <td class="main" width="50%" valign="top" align="right"><?php echo '<b>' . TITLE_PLEASE_SELECT . '</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?></td>
               
              </tr>
<?php
    } elseif ($FSESSION->free_shipping == false) {
?>
              <tr>
               
                <td class="main" width="100%" colspan="2"><?php echo $shipping_info; ?></td>
             
              </tr>
			  </table>
<?php
    }

    if ($FSESSION->free_shipping == true) {
?>
             
				
				<table width="100%" cellpadding="2">
                  <tr>
                  
                    <td class="main" colspan="3"><b><?php echo FREE_SHIPPING_TITLE; ?></b>&nbsp;<?php echo $quotes[$i]['icon']; ?></td>
                  
                  </tr>
                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, 0)">
                   
                    <td class="main" width="100%"><?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . tep_draw_hidden_field('shipping', 'free_free'); ?></td>
                   
                  </tr>
                </table><br><br>
			
<?php
    } else {
      $radio_buttons = 0;
      for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
?>
           
				<table width="100%" cellpadding="2">
                  <tr>
                  
                    <td class="main" colspan="3"><b><?php echo $quotes[$i]['module']; ?></b>&nbsp;<?php if (isset($quotes[$i]['icon']) && tep_not_null($quotes[$i]['icon'])) { echo $quotes[$i]['icon']; } ?></td>
                 
                  </tr>
<?php
        if (isset($quotes[$i]['error'])) 
		{
?>
                  <tr>
                 
                    <td class="main" colspan="3"><?php echo $quotes[$i]['error']; ?></td>
                   
                  </tr>
<?php
        } else 
		{
          for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) 
		  {
// set the radio button to be checked if it is the method chosen
            $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $shipping['id']) ? true : false);

            if ( ($checked == true) || ($n == 1 && $n2 == 1) ) {
              echo '                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
            } else {
              echo '                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
            }
?>
                   
                    <td class="main" width="75%"><?php echo $quotes[$i]['methods'][$j]['title']; ?></td>
<?php
            if ( ($n > 1) || ($n2 > 1) ) {
?>
                    <td class="main"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))); ?></td>
                    <td class="main" align="right"><?php echo tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked); ?></td>
<?php
            } else {
?>
                    <td class="main" align="right" colspan="2"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?></td>
<?php
            }
?>
                    
                  </tr>
<?php
            $radio_buttons++;
          }
        }
?>
                </table>
<?php
      }
    }
?>
           
<?php
  }
?>
<div style="display:true">
<br>
<h4><?php echo TEXT_COMMENTS;?></h4>
<?php


//Get comments up

if ($FSESSION->is_registered('comments')) {
	$comments=$_SESSION['comments'];}
//end
?>

		<?php echo TABLE_HEADING_COMMENTS .tep_draw_checkbox_field('comments', '0',($comments)?true:false,'id="comments" onclick=javascript:doComment(this)'); ?></a>

		<table id="show_comments" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td>
              <table width="100%" cellpadding="2">
               <tr>
                 <td><?php echo '&nbsp;&nbsp;&nbsp;'.tep_draw_textarea_field('comments', 'soft', '60', '5',$_SESSION['comments'],'id="comments"'); ?></td>
               </tr>
              </table>
            </td>
          </tr>
        </table>
		</div>
		<table  width="100%" cellpadding="2">
		<tr>

		<td class="main"><?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></td>
		<td class="main" align="right"><?php if (!$USER_BARRED) echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>

		</tr>
		</table>

 <div class="clearfix"></div>

	  <div class="bs-stepper">
            <div class="bs-stepper-header">
			<div class="cart" data-target="#cart">
			   <a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'); ?>">
                <button type="button" class="btn step-trigger">
                  <span class="bs-stepper-circle"><i class="bi-cart3"></i></span>
                  <span class="bs-stepper-label"><?php echo HEADER_TITLE_CART_CONTENTS; ?></span>
                </button>
				</a>
              </div>
              <div class="step active" data-target="#delivery">
			   <a href="<?php echo tep_href_link('checkout_shipping.php', '', 'SSL'); ?>">
                <button type="button" class="btn step-trigger">
                  <span class="bs-stepper-circle">1</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_DELIVERY; ?></span>
                </button>
				</a>
              </div>
              <div class="line"></div>
              <div class="step" data-target="#payment">
			 
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
<script language="JavaScript">
doComment(document.getElementById("comments"));
 function doComment(chk) {
		if(document.getElementById("show_comments") && chk.checked)
			document.getElementById("show_comments").style.display="";
		else if(document.getElementById("show_comments") && !chk.checked){
			if(document.getElementById("comments")) document.getElementById("comments").value="";
			document.getElementById("show_comments").style.display="none";
		}
}
</script>
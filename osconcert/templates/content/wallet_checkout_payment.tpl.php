<?php
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
	 echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_WALLET_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post'); ?>
<div class="section-header">
<h2><?php echo HEADING_TITLE; ?></h2>
</div>	 
	 
<table width="100%">
<?php
  if ($FREQUEST->getvalue('payment_error')!='' && is_object(${$FREQUEST->getvalue('payment_error')}) && ($error = ${$FREQUEST->getvalue('payment_error')}->get_error())) {
?>
      <tr>
        <td>
		
		<table width="100%" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo tep_output_string_protected($error['title']); ?></b></td>
          </tr>
        </table>
		
		</td>
      </tr>
      <tr>
        <td>
		
		<table width="100%" cellspacing="1" cellpadding="2" class="infoBoxNotice">
          <tr class="infoBoxNoticeContents">
            <td>


                <div><?php echo tep_output_string_protected($error['error']); ?></div>

			</td>
          </tr>
		  </table>
		  
		  </td>
      </tr>

<?php
  }
?>

      <tr>
        <td>
		
		<table width="100%" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo TABLE_HEADING_BILLING_ADDRESS; ?></b></td>
          </tr>
        </table>
		
		</td>
      </tr>

      <tr>
        <td>
		
		<table width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td>
			
			
			<table width="100%" cellpadding="2">
              <tr>
                <td align="left" width="100%" valign="top"><table cellpadding="2">
                  <tr>
				  	 <td class="main" width="20%">
					 
					 
					 <table align="left" width="150%" cellpadding="2">
					  <tr>
					    <td  width="40%">
						
						<table align="left" width="100%"><tr>
                        <td class="main" align="left" valign="top" width="80"><b><?php echo TITLE_BILLING_ADDRESS; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                        <td class="main" align="left" valign="top"><?php echo tep_address_label($FSESSION->customer_id, $FSESSION->billto, true, ' ', '<br>'); ?></td>
						</tr>
						</table>
						
						
						</td>
					  </tr>
					   <tr>
						 <td  class="horizontalSepBottom" valign="middle"></td>
					  </tr>
					  <tr>
						<td width="100%">
						
						
						   <table width="100%" cellpadding="2">
							 <tr>
							   <td class="main" width="70"><?php echo TEXT_WALLET_UPLOADS;?></td>
								<td align="left">
								<div style="width:200px">
								<?php echo '&nbsp;' . tep_draw_input_field('wallet_amount',($FSESSION->is_registered('wallet_amount')?$FSESSION->wallet_amount:''),'maxlength=12 size=13')?>
								</div>
								</td>    
							 </tr>
						   </table>
						   
						   </td>
					  </tr>

					</table>
					
					</td>
                  </tr>
                </table>
				
				</td>
				
              </tr>
            </table>
			
			</td>
          </tr>
        </table>
		
		</td>
      </tr>

      <tr>
        <td>
		
		<table width="100%" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></b></td>
          </tr>
        </table>
		
		</td>
      </tr>

      <tr>
        <td>
		
		<table width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td>
			
			
			<table width="100%" cellpadding="2">
<?php
  $selection = $payment_modules->selection();
  
  if (sizeof($selection) > 1) {
?>
              
			  <tr>
                
                <td class="main" width="50%" valign="top"><?php echo TEXT_SELECT_PAYMENT_METHOD; ?></td>
                <td class="main" width="50%" valign="top" align="right"><b><?php echo TITLE_PLEASE_SELECT; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?></td>
               
              </tr>
<?php
  } else {
?>
              <tr>
               
                <td class="main" width="100%" colspan="2"><?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?></td>
               
              </tr>
<?php
  }
  $flag='B';
  $radio_buttons = 0;

  for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
  	if(strtolower($selection[$i]['id'])!='wallet'){
					?>
					  <tr>
						
						<td colspan="2">
						<table width="100%" cellpadding="2">
							<?php
						 if (!$selection[$i]['barred']){
						 
							 if ( ($selection[$i]['id'] == $FSESSION->payment) || ($n == 1) ) 
									echo '<tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ',\'', $selection[$i]['id'] . '\')">' . "\n";
							 else 
								   echo '<tr id="defaultunSelected" class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ',\'', $selection[$i]['id'] . '\')">' . "\n";
							?>
									<td width="10"></td>
									<td class="main" colspan="3"><b><?php echo $selection[$i]['module']; ?></b>
									 <table>
										<?php for($j=0;$j<$m;$j++){
										   while(list($k,$v) = each($selection[0]["fields"]["$j"])){
												  if($cnt%2==0)
													 //echo '<tr> ';
													//echo  '<td class="main">'.$v . '</td>';
													$cnt++;
												} 
											} ?>
									</table>
									</td>
				                    <td class="main" align="right">
									<?php $single_payment=false;
										if (sizeof($selection)>1) 
										  echo tep_draw_radio_field('payment', $selection[$i]['id'],((($selection[$i]['id'] == $FSESSION->payment) || ($n == 1))?true:''));
										else {
										  $single_payment=true;
										  echo tep_draw_hidden_field('payment', $selection[$i]['id']);
										}
									?>
									</td>
									
								</tr>
								<?php
									if (isset($selection[$i]['error'])) {
								?>
												  <tr>
												
													<td class="main" colspan="4"><?php echo $selection[$i]['error']; ?></td>
											
												  </tr>
								<?php
									} elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
								?>	
												  <tr <?php if(!$single_payment){?>id="<?php echo $selection[$i]['id'];?>" <?php echo (($FSESSION->payment!=$selection[$i]['id'])?'style="display:none"':'');}?>>
													
													<td colspan="4">
													<table cellpadding="2">
														<?php
															  for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
														?>
																			  <tr>
																				
																				<td class="main"><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
																				
																				<td class="main"><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
																				
																			  </tr>
														<?php
															  }
														?>
													</table>
													</td>
													
						  						</tr>
								<?php
									} // fields,error
									
								} else { // barred
									echo '<tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ',\'', $selection[$i]['id'] . '\')">' . "\n";
								?>
									
									<td class="main" colspan="3"><b><?php echo $selection[$i]['module']; ?></b>
									 <table>
										<?php for($j=0;$j<$m;$j++){
										   while(list($k,$v) = each($selection[0]["fields"]["$j"])){
												  if($cnt%2==0)
													 //echo '<tr> ';
													//echo  '<td class="main">'.$v . '</td>';
													$cnt++;
												} 
											} ?>
									</table>
									</td>
				                    <td class="main" align="right">
									<?php $single_payment=false;
										if (sizeof($selection) > 1) {
										  echo tep_draw_radio_field('payment', $selection[$i]['id'],false,' disabled="disabled" ');
										} else {
											$single_payment++;
										}
									?>
									</td>
									
								</tr>
								  <tr>
									
									<td colspan="4" style="padding-left:10px">
									
									<table>
										<tr>
											<td class="smallText"><b><font color="red"><?php echo TEXT_PAYMENT_BARRED;?></font></b>
											</td>
										</tr>
										<tr>
											<td class="smallText"><?php echo sprintf(TEXT_PAYMENT_BARRED_REASON,tep_get_country_title($IP_COUNTRY));?>
											</td>
										</tr>
									</table>
									
									
									</td>
								</tr>	
								<?php 
									$barred++;
								} // barred?>
                		</table>
						
						</td>
                
				  </tr>
<?php
	$radio_buttons++;
	}
  }
   if ($barred==count($selection)) $payment_barred=true;
?>

            </table>
			</td>
          </tr>
        </table>
		</td>
      </tr>

<?php
 // echo $order_total_modules->credit_selection();//ICW ADDED FOR CREDIT CLASS SYSTEM
 ?>
 <script>
	previousPaymentSelected="<?php echo $FSESSION->payment;?>";
</script> 


      <tr>
        <td>
		
		<table width="100%" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
          </tr>
        </table>
		
		</td>
      </tr>

      <tr>
        <td>
		
		<table width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td>
			
			<table width="100%" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5'); ?></td>
              </tr>
            </table>
			
			</td>
          </tr>
        </table>
		
		</td>
      </tr>

      <tr>
        <td><table width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td>
			
			<table width="100%" cellpadding="2">
              <tr>
             
				<td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '"><div style="float:right">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</div></a>';?></td>
                <td align="center" class="main"><b><?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></td>
                <td class="main" align="right"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
            
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
		  
		  
		</td>
      </tr>
    </table></form>
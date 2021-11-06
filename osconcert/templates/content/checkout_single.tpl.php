<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

require_once (DIR_WS_TEMPLATES.TEMPLATE_NAME.'/boxes/shopping_cart.php');
?>
<style>
    .check_address{
		padding:2px 2px 2px 4px;
	}
	.check_address .main{
		padding:0px 0px 8px 0px;
	}
	.check_address .main h2{
		font-size:13px;
		margin:8px 0px 4px 0px;
		line-height:15px;
	}
	.check_address .main h3{
		font-size:12px;
		float:left;
		width:100px;
		font-weight:normal;
		margin:0px;
	}
    .check_address .main div{
		float:left;
	}
	.check_address .main span.required{
		color:#FF0000;
	}
	.check_address .main span.desc{
		color:#FF0000;
		font-size:11px;
        clear:left;
	}
    </style>
<table width="100%" border="0">
	<tr>
		<td width="70%" valign="top">
			<table width="100%" cellpadding="3" >
			<tr><td><?php  if ($FREQUEST->getvalue('payment_error')!='' && is_object(${$FREQUEST->getvalue('payment_error')}) && ($error = ${$FREQUEST->getvalue('payment_error')}->get_error())) {
?>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
			<tr>
				<td class="infoBoxHeadingPage"><?php echo '<span class="infoBoxHeadingPageTitle">' . tep_output_string_protected($error['title']) . '</span>'; ?></td>
			</tr>
		</table>
		</td>
	</tr> 
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBoxNotice">
			<tr class="infoBoxNoticeContents">
				<td>
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
					
						<td class="main" width="100%" valign="top"><?php echo $error['error']; ?></td>
					
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>

<?php
  }?> </td></tr>
				<Tr class="checkout_rowcolor">
				<td>
					<Table width="100%" border="0">
						<Tr id="billing_information_head" style="display:none;">
							<td class="main" width="5%"><?php echo tep_template_image('arrow_3.gif');?></td>
							<Td class="main"><b><?php echo TEXT_BILLING_INFORMATION;?></b></td>
							<td class="main" width="5%" style='cursor:pointer;' onClick="javascript:do_inf_expand('billing_information',1);"><?php echo tep_template_image('pencil.gif');?></td>
						</Tr>
						<tr>
							<Td colspan="3">
								<div id="billing_information_text" >
								<table border="0" width="100%" cellspacing="0" cellpadding="0" id="headerNavigation">
								  <tr height="25">
									<td width="4" valign="middle" class="cell_background_navig_left">&nbsp;</td>
									<td valign="middle" class="cell_background_navig">&nbsp;<?php echo tep_template_image('arrow_2.gif','','align=absmiddle');?>&nbsp;&nbsp;<?php echo TABLE_HEADING_PAYMENT_ADDRESS; ?></td>
									<td width="4" valign="middle" class="cell_background_navig_right">&nbsp;</td>
								  </tr>
								</table>
								<Table width="100%" cellpadding="3" cellspacing="0">
									<!--<Tr>
										<td width="1%" valign="top" height="7"><?php echo tep_template_image('left_corner_top.gif','');?></td>
										<td bgcolor="#ffffff" ></td>
										<td width="1%" align="right" valign="top" height="7"><?php echo tep_template_image('right_corner_top.gif','');?></td>
									</Tr>-->
									<tr>
										<td colspan="3" bgcolor="#ffffff">
											<table width="100%" class="infoBoxNotice">
												<Tr class="infoBoxNoticeContents"><td id="billing_address_error_display" style="display:none;"></td></Tr>
											</table>
											<?php load_billing_information('billto','P');?>
										</td>
									</tr>
									<!--<Tr>
										<td width="1%" valign="top" height="7"><?php echo tep_template_image('left_corner_bottom.gif','');?></td>
										<td bgcolor="#ffffff" ></td>
										<td width="1%" align="right" valign="top" height="7"><?php echo tep_template_image('right_corner_bottom.gif','');?></td>
									</Tr>-->
								</Table>
								</div>
							</Td>
						</tr>
					</Table>
				</td>
				</Tr>
				
				<?php if($FSESSION->shipping){?>
				<Tr class="checkout_rowcolor">
				<td>
					<Table width="100%">
						<Tr id="shipping_information_head">
							<td class="main" width="5%"><?php echo tep_template_image('arrow_3.gif');?></td>
							<Td class="main"><b><?php echo TEXT_SHIPPING_INFORMATION;?></b></td>
							<td class="main" width="5%" style='cursor:pointer;' onClick="javascript:do_inf_expand('shipping_information',1);"><?php echo tep_template_image('pencil.gif');?></td>
						</Tr>
						<tr>
							<Td colspan="3">
							<div id="shipping_information_text" style="background:#ffffff;display:none;">
								<table border="0" width="100%" cellspacing="0" cellpadding="0" id="headerNavigation">
								  <tr height="25">
									<td width="4" valign="middle" class="cell_background_navig_left">&nbsp;</td>
									<td valign="middle" class="cell_background_navig">&nbsp;<?php echo tep_template_image('arrow_2.gif','','align=absmiddle');?>&nbsp;&nbsp;<?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?></td>
									<td width="4" valign="middle" class="cell_background_navig_right">&nbsp;</td>
								  </tr>
								</table>
								<table width="100%" class="infoBoxNotice">
									<Tr class="infoBoxNoticeContents"><td id="shipping_address_error_display" style="display:none;"></td></Tr>
								</table>
								<?php load_billing_information('sendto','S');?>
								</div>
							</Td>
						</tr>
					</Table>
				</td>
				</Tr>
				<Tr class="checkout_rowcolor">
				<td>
					<Table width="100%">
						<Tr id="shipping_method_head">
							<td class="main" width="5%"><?php echo tep_template_image('arrow_3.gif');?></td>
							<Td class="main"><b><?php echo TEXT_SHIPPING_METHOD;?></b></td>
							<td class="main" width="5%" style='cursor:pointer;' onClick="javascript:do_inf_expand('shipping_method',1);"><?php echo tep_template_image('pencil.gif');?></td>
						</Tr>
						<tr>
						  <Td colspan="3">
							<div id="shipping_method_text" style="background:#ffffff;display:none;">
								<table border="0" width="100%" cellspacing="0" cellpadding="0" id="headerNavigation">
								  <tr height="25">
									<td width="4" valign="middle" class="cell_background_navig_left">&nbsp;</td>
									<td valign="middle" class="cell_background_navig">&nbsp;<?php echo tep_template_image('arrow_2.gif','','align=absmiddle');?>&nbsp;&nbsp;<?php echo TABLE_HEADING_SHIPPING_METHOD; ?></td>
									<td width="4" valign="middle" class="cell_background_navig_right">&nbsp;</td>
								  </tr>
								</table>
								<table width="100%" class="infoBoxNotice">
									<Tr class="infoBoxNoticeContents"><td id="shipping_method_error_display" style="display:none;"></td></Tr>
								</table>
								<table border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td id="shipping_content">
										<?php load_shipping_modules();?>
										</td>
									</tr>
								</table>
								</div>
							</Td>
						</tr>
					</Table>
				</td>
				</Tr>
				<?php }?>
				<Tr class="checkout_rowcolor">
				<td>
					<Table width="100%">
						<Tr id="payment_method_head">
							<td class="main" width="5%"><?php echo tep_template_image('arrow_3.gif');?></td>
							<Td class="main"><b><?php echo TEXT_PAYMENT_METHOD;?></b></td>
							<td class="main" width="5%" style='cursor:pointer;' onClick="javascript:do_inf_expand('payment_method',1);"><?php echo tep_template_image('pencil.gif');?></td>
						</Tr>
						<tr>
						  <Td colspan="3">
							<div id="payment_method_text" style="background:#ffffff;display:none;">
								<table border="0" width="100%" cellspacing="0" cellpadding="0" id="headerNavigation">
								  <tr height="25">
									<td width="4" valign="middle" class="cell_background_navig_left">&nbsp;</td>
									<td valign="middle" class="cell_background_navig">&nbsp;<?php echo tep_template_image('arrow_2.gif','','align=absmiddle');?>&nbsp;&nbsp;<?php echo TABLE_HEADING_PAYMENT_METHOD; ?></td>
									<td width="4" valign="middle" class="cell_background_navig_right">&nbsp;</td>
								  </tr>
								</table>
								<table width="100%" class="infoBoxNotice">
									<Tr class="infoBoxNoticeContents"><td id="payment_method_error_display" style="display:none;"></td></Tr>
								</table>
								<?php load_payment_modules();?>
								</div>
							</Td>
						</tr>
					</Table>
				</td>
				</Tr>
				<Tr class="checkout_rowcolor">
				<td>
					<Table width="100%" >
						<Tr id="checkout_confirm_head">
							<td class="main" width="5%"><?php echo tep_template_image('arrow_3.gif');?></td>
							<Td class="main"><b><?php echo TEXT_ORDER_REVIEW;?></b></td>
							<td class="main" width="5%" style='cursor:pointer;' onClick="javascript:do_inf_expand('checkout_confirm',1);"><?php echo tep_template_image('pencil.gif');?></td>
						</Tr>
						<tr>
						  <Td colspan="3">
							<div id="checkout_confirm_text" style="background:#ffffff;display:none;">
								<table border="0" width="100%" cellspacing="0" cellpadding="0" id="headerNavigation">
								  <tr height="25">
									<td width="4" valign="middle" class="cell_background_navig_left">&nbsp;</td>
									<td valign="middle" class="cell_background_navig">&nbsp;<?php echo tep_template_image('arrow_2.gif','','align=absmiddle');?>&nbsp;&nbsp;<?php echo TABLE_HEADING_CHECKOUT_CONFIRM; ?></td>
									<td width="4" valign="middle" class="cell_background_navig_right">&nbsp;</td>
								  </tr>
								</table width="100%">
								<table><tr><td class="main" id="checkout_confirm_content"><?php echo TEXT_CURRENTLY_NO_CONFIRM;?></td></tr></table>
								</div>
							</Td>
						</tr>
					</Table>
				</td>
				</Tr>
			</table>
		</td>
		<td width="20%" valign="top">
			<Table width="100%" cellpadding="3" cellspacing=2 class="checkout_rowcolor">
				<Tr class="checkout_rowcolor_left">
				<td>
					<Table width="100%" border="0">
						<Tr>
							<td class="main" width="5%"><?php echo tep_template_image('arrow_3.gif');?></td>
							<Td class="main" nowrap><b><?php echo TEXT_BILLING_ADDRESS;?></b></td>
							<td class="main" width="5%" style='cursor:pointer;' onClick="javascript:do_display_expand('billing_address_display');"><?php echo tep_template_image('pencil.gif');?></td>
						</Tr>
						<Tr><td colspan="3" id="billing_address_display" style="display:none;" class="main">
						<?php echo tep_address_label($FSESSION->get('customer_id'), $FSESSION->get('billto'), true, ' ', '<br>'); ?>
						</td></Tr>
					</TABLE>
				</td>
				</tr>
				<?php if($FSESSION->shipping){?>
				<Tr class="checkout_rowcolor_left">
				<td>
					<Table width="100%" >
						<Tr>
							<td class="main" width="5%"><?php echo tep_template_image('arrow_3.gif');?></td>
							<Td class="main" nowrap><b><?php echo TEXT_SHIPPING_ADDRESS;?></b></td>
							<td class="main" width="5%" style='cursor:pointer;' onClick="javascript:do_display_expand('shipping_address_display');"><?php echo tep_template_image('pencil.gif');?></td>
						</Tr>
						<Tr><td colspan="3" id="shipping_address_display" style="display:none;" class="main">
						<?php echo tep_address_label($FSESSION->get('customer_id'), $FSESSION->get('sendto'), true, ' ', '<br>'); ?>
						</td></Tr>
					</table>
				</td></tr>
				<Tr class="checkout_rowcolor_left">
				<td>
					<Table width="100%" >
						<Tr>
							<td class="main" width="5%"><?php echo tep_template_image('arrow_3.gif');?></td>
							<Td class="main" nowrap><b><?php echo TEXT_SHIPPING_METHOD;?></b></td>
							<td class="main" width="5%" style='cursor:pointer;' onClick="javascript:do_display_expand('shipping_method_display');"><?php echo tep_template_image('pencil.gif');?></td>
						</Tr>
						<Tr><td colspan="3" id="shipping_method_display" style="display:none;" class="main">
						</td></Tr>
					</table>
				</td></tr>
			   <?php }?>
				<Tr class="checkout_rowcolor_left">
				<td>
					<Table width="100%" >
						<Tr>
							<td class="main" width="5%"><?php echo tep_template_image('arrow_3.gif');?></td>
							<Td class="main" nowrap><b><?php echo TEXT_PAYMENT_METHOD;?></b></td>
							<td class="main" width="5%" style='cursor:pointer;' onClick="javascript:do_display_expand('payment_method_display');"><?php echo tep_template_image('pencil.gif');?></td>
						</Tr>
						<Tr><td colspan="3" id="payment_method_display" style="display:none;" class="main">
						</td></Tr>						
					</Table>
				</td>
				</Tr>
			</Table>
		</td>
	</tr>
</table>
<?php 
function load_billing_information($mode1,$var)
{
 global $messageStack,$FSESSION,$gender,$customerAccount,$FREQUEST,$JS_VARS,$fieldsDesc,$form_name;
 $addresses_count = tep_count_customer_address_book_entries();
?>
    <?php if($var=='S')
		{
			echo tep_draw_form('shipping_address', tep_href_link(FILENAME_CHECKOUT_SINGLE, '', 'SSL'), 'post', 'onSubmit="return check_form_optional(shipping_address);"'); 
			$action_var='shipping_address_submit';
			$mode=$FSESSION->sendto;
            $form_name='shipping_address';
		}
		else
		{
			echo tep_draw_form('billing_address', tep_href_link(FILENAME_CHECKOUT_SINGLE, '', 'SSL'), 'post', 'onSubmit="return check_form_optional(billing_address);"');
			$action_var='billing_address_submit';
			$mode=$FSESSION->billto;
            $form_name='billing_address';
		}
        ?>
	<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
      
<?php
  if ($messageStack->size('checkout_address') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('checkout_address'); ?></td>
      </tr>

<?php
  }
  if ($addresses_count >= 1) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo TABLE_HEADING_ADDRESS_BOOK_ENTRIES; ?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
        
                <td class="main" width="50%" valign="top"><?php echo ($var=='S'?TEXT_SELECT_OTHER_SHIPPING_DESTINATION:TEXT_SELECT_OTHER_PAYMENT_DESTINATION); ?></td>
                <td class="main" width="50%" valign="top" align="right"><?php echo '<b>' . TITLE_PLEASE_SELECT . '</b><br>' . tep_template_image('arrow_east_south.gif'); ?></td>
      
              </tr>
<?php
      $radio_buttons = 0;
  	  $addresses_query = tep_db_query("select ab.entry_gender as gender,ab.address_book_id,ab.entry_firstname as firstname, ab.entry_lastname as lastname, ab.entry_company as company, ab.entry_street_address as street_address, ab.entry_suburb as suburb, ab.entry_postcode as postcode,ab.entry_state as state, ab.entry_city as city, ab.entry_zone_id as zone_id, z.zone_name, ab.entry_country_id as country_id, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id as format_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " c on (ab.entry_country_id = c.countries_id) where ab.customers_id = '" . (int)$FSESSION->customer_id . "'");
	  $cust=tep_db_query("select customers_default_address_id from customers where customers_id='". (int)$FSESSION->customer_id  ."'");
	  $custo=tep_db_fetch_array($cust);

      while ($addresses = tep_db_fetch_array($addresses_query)) {
            //address selection
          
			$$mode=$custo['customers_default_address_id'];
			 $add = ($addresses['address_book_id'] == $custo['customers_default_address_id']) ? true : false;
             //address selection

        $format_id = $addresses['format_id'];
?>
              <tr>
              
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
       if ($addresses['address_book_id'] == $mode) {
	   		if($var=='S') {
				$addresses['country']=array('id' => $addresses['countries_id'], 'title' => $addresses['countries_name'], 'iso_code_2' => $addresses['countries_iso_code_2'], 'iso_code_3' => $addresses['countries_iso_code_3']);
				$FSESSION->set('sendto_array',$addresses);
			}	
			else {
				$addresses['country']=array('id' => $addresses['countries_id'], 'title' => $addresses['countries_name'], 'iso_code_2' => $addresses['countries_iso_code_2'], 'iso_code_3' => $addresses['countries_iso_code_3']);
				$FSESSION->set('billto_array',$addresses);
			}	
          echo ' <tr id="'.($var=='S'?'defaultSelected_s':'defaultSelected') . '" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="' . ($var=='S'?'selectRowEffect2':'selectRowEffect') . '(this, ' . $radio_buttons . ');do_bill_edit('.$addresses['address_book_id'].',\'' . $var . '\');">' . "\n";
        } else {
          echo ' <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="'.($var=='S'?'selectRowEffect2':'selectRowEffect') . '(this, ' . $radio_buttons . ');do_bill_edit('.$addresses['address_book_id'].',\'' . $var . '\');">' . "\n";
        }
?>
                  
                    <td class="main" colspan="2"><b><?php echo $addresses['firstname'] . ' ' . $addresses['lastname']; ?></b></td>
                    <td class="main" align="right"><?php echo tep_draw_radio_field('address', $addresses['address_book_id'], $add,''); ?></td>
                  
                  </tr>
                 
                    <td colspan="3"><table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                       
                        <td class="main"><?php echo tep_address_format($format_id, $addresses, true, ' ', ', '); ?></td>
                        
                      </tr>
                    </table></td>
             
                  </tr>
                </table></td>
           
              </tr>
<?php
       
	/*	echo "<input type=hidden id='gender" . $addresses['address_book_id'] . "' name='gender" . $addresses['address_book_id'] . "' value='" . tep_output_string($addresses['gender']) . "'>";
        echo "<input type=hidden id='company" . $addresses['address_book_id'] . "' name='company" . $addresses['address_book_id'] . "' value='" . tep_output_string($addresses['company']) . "'>";
        echo "<input type=hidden id='firstname" . $addresses['address_book_id'] . "' name='firstname" . $addresses['address_book_id'] . "' value='" . tep_output_string($addresses['firstname']) . "'>";
		echo "<input type=hidden id='lastname" . $addresses['address_book_id'] . "' name='lastname" . $addresses['address_book_id'] . "' value='" . tep_output_string($addresses['lastname']) . "'>";
		echo "<input type=hidden id='street_address" . $addresses['address_book_id'] . "' name='street_address" . $addresses['address_book_id'] . "' value='" . tep_output_string($addresses['street_address']) . "'>";
		echo "<input type=hidden id='suburb" . $addresses['address_book_id'] . "' name='suburb" . $addresses['address_book_id'] . "' value='" . tep_output_string($addresses['suburb']) . "'>";
        echo "<input type=hidden id='postcode" . $addresses['address_book_id'] . "' name='postcode" . $addresses['address_book_id'] . "' value='" . tep_output_string($addresses['postcode']) . "'>";
  		echo "<input type=hidden id='city" . $addresses['address_book_id'] . "' name='city" . $addresses['address_book_id'] . "' value='" . tep_output_string($addresses['city']) . "'>";
		echo "<input type=hidden id='state_" . $addresses['address_book_id'] . "' name='state_" . $addresses['address_book_id'] . "' value='" . tep_output_string($addresses['state']) . "'>";
        echo "<input type=hidden id='country" . $addresses['address_book_id'] . "' name='country" . $addresses['address_book_id'] . "' value='" . tep_output_string($addresses['country_id']) . "'>";
		echo "<input type=hidden id='zone_id" . $addresses['address_book_id'] . "' name='zone_id" . $addresses['address_book_id'] . "' value='" . tep_output_string($addresses['zone_id']) . "'>";
		echo "<input type=hidden id='zone_name" . $addresses['address_book_id'] . "' name='zone_name" . $addresses['address_book_id'] . "' value='" . tep_output_string($addresses['zone_name']) . "'>"; */
		echo "<input type=hidden id='format" . $addresses['address_book_id'] . "' name='format" . $addresses['address_book_id'] . "' value='" . tep_output_string($addresses['format_id']) . "'>";
     $radio_buttons++;

     }
  
?>
		</table></td>
	  </tr>
	</table></td>
  </tr>
    <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ($var=='S'?TABLE_HEADING_EDIT_SHIPPING_ADDRESS:TABLE_HEADING_EDIT_PAYMENT_ADDRESS); ?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
               
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                 
                   <td>
                        <table cellpadding="0" cellspacing="0" width="100%" class="check_address" border="0">
                        <?php
                               
   
                                for ($icnt=0,$n=count($fieldsDesc);$icnt<$n;$icnt++){
                                    $fieldDesc=&$fieldsDesc[$icnt];
                                    if (!isset($ACCOUNT[$fieldDesc['uniquename']])){
                                       $ACCOUNT[$fieldDesc['uniquename']]=$fieldDesc['default_value'];
                                    }
                                    echo '<tr><td class="main">'; 
                                    if (method_exists($customerAccount,"edit__" . $fieldDesc['uniquename'])){
                                       $customerAccount->{"edit__" . $fieldDesc['uniquename']}($fieldDesc);
                                    } else {
                                       $customerAccount->commonInput($fieldDesc);
                                    }
                                    echo '</td></tr>';
                                }
                                $JS_VARS['page']=array('formName'=>$form_name,'fieldsDesc'=>$fieldsDesc,'dateFormat'=>$format[EVENTS_DATE_FORMAT],'formErrText'=>str_replace("\\n","--",JS_ERROR));
                                $addresses_query = tep_db_query("select ab.entry_gender as gender,ab.address_book_id,ab.entry_firstname as firstname, ab.entry_lastname as lastname, ab.entry_company as company, ab.entry_street_address as street_address, ab.entry_suburb as suburb, ab.entry_postcode as postcode,ab.entry_state as state, ab.entry_city as city, ab.entry_zone_id as zone_id, z.zone_name, ab.entry_country_id as country_id, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id as format_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " c on (ab.entry_country_id = c.countries_id) where ab.customers_id = '" . (int)$FSESSION->customer_id . "'");
                                while ($addresses = tep_db_fetch_array($addresses_query)) { 
                                    // $JS_VARS['page']=array('formName'=>billing_address,'fieldDesc[' . $addresses['address_book_id'] . ']'=>$addresses);
                                    $JS_VARS["page"]['address'][$addresses['address_book_id']]=$addresses;
                                }
                            ?>
                        </table>
                    </td> 
                    <td><?php //require(DIR_WS_MODULES . 'checkout_new_address.php'); ?></td>
               
                  </tr>
                </table></td>
            
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
		echo '<script>';
	/*	if($var=='S')
		{
			echo 'obj=document.shipping_address.country;';
			echo 'obj.onclick=function (){set_form("S");}';
		}
		else
		{
			echo 'obj=document.billing_address.country;';
			echo 'obj.onclick=function (){set_form("P");}';
		}  */
		echo '</script>';
    }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
              
                <td class="main" align="right"><a href="javascript:do_page_fetch('<?php echo $action_var;?>')"><?php echo tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></a></td>
             
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></form>
<?php 
}

?>
<?php function load_payment_modules()
{
global $payment_modules,$currencies;
$order_total_modules=new order_total();
echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_SINGLE, '', 'SSL'), 'post'); 
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php $serverDate = date('Y-m-d H:i:s',getServerDate(false)); 
?>
<tr>
	<td>
	<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
		<tr class="infoBoxContents">
			<td>
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
				<?php 
				  $selection = $payment_modules->selection();
				  if (sizeof($selection) > 1) {
				?>
					  <tr>
					
						<td class="main" width="50%" valign="top"><?php echo TEXT_SELECT_PAYMENT_METHOD; ?></td>
						<td class="main" width="50%" valign="top" align="right"><b><?php echo TITLE_PLEASE_SELECT; ?></b><br><?php echo tep_template_image('arrow_east_south.gif'); ?></td>
			
					  </tr>
					<?php
					  } else {
					?>
					  <tr>
				
						<td class="main" width="100%" colspan="2">
						<?php  if(sizeof($selection)>0)
									echo TEXT_ENTER_PAYMENT_INFORMATION; 
							 else
									echo TEXT_NO_PAYMENT_SELECTION;
						?></td>
					
					  </tr>
				<?php
				  }
					  $flag='B';
					  $radio_buttons = 0;
					  $m = count($selection[0]['fields']);
					  $cnt = 0;
					  $barred=0;
					  for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
					?>
					  <tr>
						
						<td colspan="2">
						<table border="0" width="100%" cellspacing="0" cellpadding="2">
							<?php
						 if (!$selection[$i]['barred']){
							 if ( ($selection[$i]['id'] == $FSESSION->payment) || ($n == 1) ) 
									echo '<tr id="defaultSelected_payment" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect_payment(this, ' . $radio_buttons . ',\'', $selection[$i]['id'] . '\')">' . "\n";
							 else 
								   echo '<tr id="defaultunSelected" class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect_payment(this, ' . $radio_buttons . ',\'', $selection[$i]['id'] . '\')">' . "\n";
							?>
								
									<td class="main" colspan="3"><b><?php echo $selection[$i]['module']; ?></b>
									 <table border="0" cellpadding="0" cellspacing="0">
										<?php for($j=0;$j<$m;$j++){
										   while(list($k,$v) = each($selection[0]["fields"]["$j"])){
												  if($cnt%2==0)
													$cnt++;
												} 
											} ?>
									</table>
									</td>
				                    <td class="main" align="right">
									<?php $single_payment=false;
										if (sizeof($selection)>1) 
										  echo tep_draw_radio_field('payment', $selection[$i]['id'],(($i==0)?true:''));
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
													<table border="0" cellspacing="0" cellpadding="2">
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
									echo '<tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect_payment(this, ' . $radio_buttons . ',\'', $selection[$i]['id'] . '\')">' . "\n";
								?>
								
									<td class="main" colspan="3"><b><?php echo $selection[$i]['module']; ?></b>
									 <table border="0" cellpadding="0" cellspacing="0">
										<?php for($j=0;$j<$m;$j++){
										   while(list($k,$v) = each($selection[0]["fields"]["$j"])){
												  if($cnt%2==0)
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
									<table border="0" cellspacing="0" cellpadding="0">
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
  if ($barred==count($selection)) $payment_barred=true;
 ?>
             </table>
			</td>
           </tr>
         </table>
		</td>
      </tr>
 <script language="javascript">
	previousPaymentSelected="<?php echo $FSESSION->payment;?>";
	if(!previousPaymentSelected && document.getElementById("defaultunSelected")) selectRowEffect_payment(document.getElementById("defaultunSelected"),0,"<?php echo $selection[0]['id'];?>");
</script>
<?php  $coupon_check_qry = tep_db_query("select * from " . TABLE_COUPONS . " where '". $serverDate ."' <= coupon_expire_date and '". $serverDate ."' >= coupon_start_date and coupon_active='Y' ");
		if(tep_db_num_rows($coupon_check_qry)){
?>
	<tr>
		<td>
		<table cellspacing="0" cellpadding="0" width="100%" border="0">
			<tr>
				<td class="infoBoxHeadingPage">&nbsp;
					<?php echo tep_template_image('arrow_1.gif','','align=absmiddle');?>&nbsp;<?php echo TEXT_GIFT_VOUCHER;?>  
				</td>
			</tr>	
			<tr id="gv_chk">
				<td class="main"><b><?php echo TEXT_REDEEM_YOUR_VOUCHER;?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo  tep_draw_checkbox_field('coupon', '0',false,'onclick=javascript:hide_credit()');?> </td>
			</tr>	
			<tr>
				<td class="main">
				   <table border="0" cellpadding="0" cellspacing="0" width="100%">			  
				      <tr>
					    <td>
					      <div id="credit_result" class="smalltext" align="center" style="display:none;height:100;width:100%;"></div>	
					      <div id="credit" style="display:none;"><div id="error_span"></div><?php echo $order_total_modules->credit_selection(); ?></div>	
						 </td>
					  </tr>
					</table>
				</td>
			</tr>
      	</table>
	  </td>
      </tr>
<?php } ?>
	
      <tr>
        <td><table border="0" width="100%" cellspacing="2" cellpadding="2">
          <tr>
			   <td class="infoBoxHeadingPage">&nbsp;<?php echo tep_template_image('arrow_1.gif','','align=absmiddle');?>&nbsp;<?php echo TABLE_HEADING_COMMENTS;?></td> 
		  </tr>
          <tr>
            <td class="main">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<b><?php echo TABLE_HEADING_COMMENTS_HERE .tep_draw_checkbox_field('comments_chkbox', '1',($comments)?true:false,'onclick=javascript:doComment(this)'); ?></a></b></td>
          </tr>
        </table></td>
      </tr>
<?php
//}
// BOF: Lango Added for template MOD
	if($comments!="")
		$comment_style="style='display:block'";
	else 
		$comment_style="style='display:none'";
			
?>
	<tr <?php echo $comment_style; ?> id="show_comments">
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0" class="infoBox">
			<tr class="infoBoxContents">
			<td><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.tep_draw_textarea_field('comments', 'soft', '60', '5'); ?></td>
			</tr>
		</table>
		</td>
	</tr>	
		<tr>			
			<td>
			<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
				<tr class="infoBoxContents">
					<td>
					<table border="0" width="100%" cellspacing="0" cellpadding="2">
						<tr>
					
							<td class="main" align="right"><?php if (!$USER_BARRED && !$payment_barred) echo '<a href="javascript:do_page_fetch(\'payment_method_submit\')">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
						
						</tr>
					</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>

</table>
</form>
	<script>
	doComment(document.checkout_payment.comments); 
	</script>
<?php }?>
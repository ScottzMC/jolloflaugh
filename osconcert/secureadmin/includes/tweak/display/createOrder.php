<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare

    Released under the GNU General Public License
*/
	// Set flag that this is a parent file
	defined('_FEXEC') or die();
	class createOrder{
		function __construct() {
			global $jsData,$FSESSION,$FREQUEST;
			$panels=array('SELECTCUSTOMER','SHOPPINGCART','BILLINGSHIPPING','SHIPPING','PAYMENT','CONFIRM');
			$payment_error_flag=$FREQUEST->getvalue('payment_error_flag');
			if($payment_error_flag =="yes")
				$default_panel='PAYMENT';
			else
				$default_panel='SELECTCUSTOMER';
			$jsData->VARS['page']['cOrder']=$default_panel;
		?>
			<table border="0" cellpadding="4" cellspacing="0" width="100%">
				<tr>
					<td valign="top" class="listItemOdd">
					<table border="0" cellpadding="5" cellspacing="0" width="100%">
					<?php for ($icnt=0,$n=count($panels);$icnt<$n;$icnt++){ ?>
						<tr>
							<td class="<?php echo ($panels[$icnt]!=$default_panel?"boxRow":"boxRowSelect");?>" onmouseover="javascript:doMouseOverOut([{callFunc:changeItemRow,params:{element:this,'className':'boxRow','changeStyle':'Hover'}}]);" onmouseout="javascript:doMouseOverOut([{callFunc:changeItemRow,params:{element:this,'className':'boxRow'}}]);" onclick="javascript:showPanelContent_check({id:'<?php echo $panels[$icnt];?>',className:'boxRow','type':'cOrder'});" id="cOrder<?php echo $panels[$icnt];?>menu">
								<?php
									echo "<a href='javascript:void(0);'><div>" . constant("HEADING_ITEM_" .$panels[$icnt]) . '</div></a>';
									$jsData->VARS["page"]["cOrderMenus"][$panels[$icnt]]=array('text'=>constant("HEADING_ITEM_" .$panels[$icnt]));
								?>
							</td>
						</tr>
						<tr>
						<td class="boxlevel1" id="cOrder<?php echo $panels[$icnt];?>view" <?php echo ($panels[$icnt]!=$default_panel?'style="display:none"':'')?>>
						<?php switch($panels[$icnt])
						{
						case "SELECTCUSTOMER":
							 $this->doSelectCustomer();
							 break;
						case "SHOPPINGCART":
							if($FSESSION->is_registered('order_id') || $payment_error_flag =="yes") $this->doShopping();
							break;
						case "BILLINGSHIPPING":
							if($FSESSION->is_registered('order_id') || $payment_error_flag =="yes") $this->doBillingShipping();
							break;
						case "SHIPPING":
							if($FSESSION->is_registered('order_id') || $payment_error_flag =="yes") $this->doShipping();
							 break;
						case "PAYMENT":
							if($FSESSION->is_registered('order_id') || $payment_error_flag =="yes") $this->doPayment();
							 break;
						case "CONFIRM":
							if($FSESSION->is_registered('order_id')) $this->doConfirm();
							break;
						}?>
						</td>
						</tr>
					<?php } ?>
					</table>
					</td>
				</tr>
			</table>
		<?php
		}
		function doSelectCustomer()
		{
			global $FREQUEST,$FSESSION;
			$custID=$FREQUEST->getvalue('cID','int',0);
			if($custID>0) $FSESSION->set('customer_id',$custID);
			$query = tep_db_query("select customers_id, customers_firstname, customers_lastname,customers_email_address from " . TABLE_CUSTOMERS . " ORDER BY customers_lastname desc");
			$result = $query;
			$selected_name="";
			if (tep_db_num_rows($result) > 0 )
			{
				$SelectCustomerBox = "<select name='customers_id' size=10 onClick=\"javascript:setListboxText(0);toggleList(0);\" onFocus=\"javascript:toggleList(1);\" onBlur=\"javascript:toggleList(0);\" class='customerSelect'>\n";
				while($db_Row = tep_db_fetch_array($result)){
					$SelectCustomerBox .= "<option value='" . $db_Row["customers_id"] . "'";
					if($db_Row["customers_id"]==$FSESSION->customer_id){
						$SelectCustomerBox .= " SELECTED ";
						$selected_name=$db_Row["customers_lastname"] . ' ' . $db_Row["customers_firstname"] . ", " . $db_Row["customers_email_address"];
					}
					$SelectCustomerBox .= ">" . $db_Row["customers_lastname"] . ' ' .  $db_Row["customers_firstname"] . ", " . $db_Row["customers_email_address"] . " (" . $db_Row["customers_id"]. ") </option>\n";
				}
				$SelectCustomerBox .= "</select>\n";
			}
	?>
			<table border='0' cellpadding='0' width="100%">
				<?php echo tep_draw_form('select_customer',FILENAME_CREATE_ORDER_NEW,'','post'); ?>
				<tr>
        			<td>
					<table border='0' cellpadding="4" cellspacing="0"  width="100%">
						<div id="customer_list" style="position:absolute;display:none;width:400px">
							<?php echo $SelectCustomerBox;?>
						</div>
						<tr> <?php echo tep_draw_separator('pixel_trans.gif', '100%', '5','1'); ?> </tr>
						<tr>
							<td class="main" width="100" align="right"><font color="#003366"><b><?php echo TEXT_NAME;?></b></font></td>
							<td class="main"><?php echo tep_draw_input_field('customer_text',$selected_name,'onFocus="javascript:toggleText(1);" onBlur="javascript:toggleText(0);" style="width:400px"',false,'text',false) . '&nbsp;<span id="img_process1"></span>';?></td>
						</tr>
						<tr height="3"></tr>
					</table>
					</td>
				</tr>
				</form>
				<tr><td id="cOrdercustomer_details">
					<?php
					if($FSESSION->is_registered('customer_id')) {
						$this->doCustomer_details($FSESSION->customer_id);
					}	?>
				</td></tr>
			</table>
		<?php }
	function doCustomer_details($customers_id=0)
	{
		global $language,$cart,$FREQUEST,$FSESSION,$ACCOUNT,$jsData;
		//$error = false;
		if($customers_id==0)
			$customers_id=$FREQUEST->getvalue('cID','int','0');
		if($customers_id!='0')
		{
			// if($FSESSION->param_event!=''){
			// $events_query = tep_db_query("select ed.events_name,e.sessions_select_interval from ".TABLE_EVENTS." e, ".TABLE_EVENTS_DESCRIPTION." ed where e.events_id=ed.events_id and ed.events_id='{$FSESSION->get('param_event')}' and ed.language_id='" . (int)$FSESSION->languages_id . "'");
			// $events_array = tep_db_fetch_array($events_query);

			// $sessions_query = tep_db_query("select start_date,sessions_type from ".TABLE_EVENTS_SESSIONS." where sessions_id='{$FSESSION->get('param_session')}'");
			// $sessions_array = tep_db_fetch_array($sessions_query);

			// if($events_array['sessions_select_interval']>1){

			// $multiple_sessions_query = tep_db_query("select sessions_id,start_date,sessions_type from ".TABLE_EVENTS_SESSIONS." where start_date >= '{$sessions_array['start_date']}' limit {$events_array['sessions_select_interval']}");
				// while($multiple_sessions_array = tep_db_fetch_array($multiple_sessions_query)){
					// $sessions[$multiple_sessions_array['sessions_id']] = array("type"=>$multiple_sessions_array['sessions_type'],
							  // "date"=>$multiple_sessions_array['start_date'],
							  // "fees"=>0,
							  // "group"=>'R');
				// }

			// } else {
			// $sessions[$FSESSION->get('param_session')] = array("type"=>$sessions_array['sessions_type'],
							  // "date"=>$sessions_array['start_date'],
							  // "fees"=>0,
							  // "group"=>'R');
			// }

			// $old_orders_id = 0;
			// $question_type = '';
			// $question_value = '';
			// $no_attendees = 1;
			// $cart->add_cart_event($FSESSION->get('param_event'),$sessions,$old_orders_id,$question_type,$question_value,$no_attendees);
			// $FSESSION->remove('param_event');
			// $FSESSION->remove('param_session');
			// }


			/*$admin_group_sql = "select c.customers_id,c.is_blocked from " . TABLE_CUSTOMERS . " c where c.customers_id = '" . (int)$customers_id . "'";
			$admin_group_query = tep_db_query($admin_group_sql);
			$admin_group = tep_db_fetch_array($admin_group_query);
			$is_blocked = $admin_group['is_blocked'];
			$suspended = '';
			$group_sql = "select customers_id from " . TABLE_CUSTOMERS . " c where customers_id=" . (int)$customers_id .
						" and ((suspend_from!='0000-00-00' and suspend_from<=curdate() and (resume_from='0000-00-00' or resume_from>curdate()))
						or (resume_from!='0000-00-00' && resume_from>curdate()))";
			$group_query = tep_db_query($group_sql);
			$is_data = tep_db_fetch_array($group_query);
			if($is_data['customers_id'] != ''){
				$error = true;
				$err_message = SUSPENDED_USER_ERROR;
			}
		   if($is_blocked=="Y"){
				$error=true;
				$err_message=BLOCKED_USER_ERROR;
		   }
			$account_query = tep_db_query("select c.customers_id, c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, c.customers_second_email_address, c.customers_type,c.customers_telephone, c.customers_second_telephone,c.customers_fax, c.customers_newsletter, c.customers_reserve_newsletter,c.customers_default_address_id from " . TABLE_CUSTOMERS . " c where c.customers_id = '" . (int)$customers_id . "'");
			$account = tep_db_fetch_array($account_query);
			$customer = $account['customers_id'];
			$address_query = tep_db_query("select a.entry_company, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state,a.entry_zone_id, a.entry_country_id from " . TABLE_ADDRESS_BOOK . " a where a.address_book_id = '" . $account['customers_default_address_id'] . "'");
			$address = tep_db_fetch_array($address_query);
			$ACCOUNT=array_merge($account,$address);*/
			
			$customer_query=tep_db_query("select a.*,c.* from " . TABLE_CUSTOMERS ." c, " . TABLE_ADDRESS_BOOK . " a  where c.customers_id='" . $customers_id . "' and c.customers_default_address_id=a.address_book_id and c.customers_id=a.customers_id");
			$customer_result=tep_db_fetch_array($customer_query);
			$extra_query=tep_db_query("SELECT uniquename,fieldvalue from " . TABLE_CUSTOMERS_EXTRA_INFO . " where customers_id=" .$customers_id);
			while($extra=tep_db_fetch_array($extra_query)){
				$customer_result[$extra['uniquename']]=$extra["fieldvalue"];
			}
		}?>
		<?php echo tep_draw_form('customers', FILENAME_CREATE_ORDER_NEW, 'action=update', 'post', 'onSubmit="return check_form();"');
			if ($error)
				echo '<input type="hidden" name="default_address_id" value="' . $account["default_address_id"] . '">';
			else
				echo '<input type="hidden" name="default_address_id" value="' . $account["customers_default_address_id"] . '">';
			echo '<input type="hidden" name="customers_id" value="' . $customers_id . '">';

		?>
		<table width="100%" cellpadding="5" border="0">
			<tr>
				<td class="errortext" id="customer_err_text"><?php echo $err_message; ?></td>
			</tr>
  			 <tr>
     			<td valign=top >
					<div id="create_order_details_show">
					<?php $fieldsDesc=array();
					//get the required display fields
					require_once(DIR_WS_CLASSES . 'customerAccount.php');
					$customerAccount=new customerAccount();
					$query=tep_db_query("SELECT cif.*,cifd.label_text,cifd.input_description,cifd.error_text,cifd.input_title from ".  TABLE_CUSTOMERS_INFO_FIELDS . " cif, " . TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION . " cifd where cif.info_id=cifd.info_id and cifd.languages_id=" . $FSESSION->languages_id. " and cif.uniquename in('#1_firstname','customers_email_address','#1_lastname','entry_company','entry_street_address','entry_suburb','entry_postcode','entry_postcode','entry_city','country_state','customers_telephone','customers_fax')");
					$icnt=0;
					while($fieldsDesc[$icnt]=tep_db_fetch_array($query)){
						$fieldDesc=&$fieldsDesc[$icnt];
						if (strpos($fieldDesc['error_text'],"==")!==false){
							$fieldDesc['error_text']=str_replace(array("==MIN==","==MAX==","==DATE_FORMAT==","==EX_DATE=="),array($fieldDesc['textbox_min_length'],$fieldDesc['textbox_max_length'],$format[EVENTS_DATE_FORMAT],format_date('1970-05-20')),$fieldDesc['error_text']);
						}
						if (strpos($fieldDesc['input_description'],"==")!==false){
							$fieldDesc['input_description']=str_replace(array("==MIN==","==MAX==","==DATE_FORMAT==","==EX_DATE=="),array($fieldDesc['textbox_min_length'],$fieldDesc['textbox_max_length'],$format[EVENTS_DATE_FORMAT],format_date('1970-05-20')),$fieldDesc['input_description']);
						}
						$icnt++;
						if (method_exists($customerAccount,"getdb__" . $fieldDesc['uniquename'])){
							$customerAccount->{"getdb__" . $fieldDesc['uniquename']}($customer_result); // Change by Roy
						} else {
							$customerAccount->commonEntries($fieldDesc['uniquename'],$customer_result); // Change by Roy
						}
					}

					unset($fieldsDesc[$icnt]);

					?>
					<table border="0" cellpadding="0" cellspacing="0" class="account" width="100%">
					<?php
					 $row_cnt=0;
						for ($icnt=0,$n=count($fieldsDesc);$icnt<$n;$icnt++){
							$fieldDesc=&$fieldsDesc[$icnt];
							if (!isset($ACCOUNT[$fieldDesc['uniquename']])){
								$ACCOUNT[$fieldDesc['uniquename']]=$fieldDesc['default_value'];
							}
							if($row_cnt==0 || $row_cnt==3 || $row_cnt==9)
							{
								switch($row_cnt)
								{
								  case '0':
				  					echo '<Tr><td colspan="2" class="contentTitle">' . CATEGORY_PERSONAL . '</td></Tr>';
								  	break;
								  case '3':
  				  					echo '<Tr><td colspan="2" class="contentTitle">' . CATEGORY_COMPANY. '</td></Tr>';
								  	break;
								   case '9':
  				  					 echo '<Tr><td colspan="2" class="contentTitle">' . CATEGORY_ADDRESS. '</td></Tr>';
								   	break;
								}
								echo "<tr><td colspan=2 height=5></td></tr>";
							}
							if($row_cnt%2==0)
								echo '<tr>';
							$row_cnt++;
							echo '<td class="main" width=50% ' . ($row_cnt==count($fieldsDesc)?' colspan=2':'') . '>';
							if (method_exists($customerAccount,"edit__" . $fieldDesc['uniquename'])){
								$customerAccount->{"edit__" . $fieldDesc['uniquename']}($fieldDesc);
							} else {
								$customerAccount->commonInput($fieldDesc);
							}
							echo '</td>';

							if($row_cnt%2==0)
								echo '</tr>';
						}
					?>
					</table>
					<?php  //require(DIR_WS_MODULES . 'create_order_details.php');?>
					</div>
     			</td>
		   </tr>
		   <tr>
				 <td class=contenttitle><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
		  </tr>
		  <tr><td>&nbsp;</td></tr>
		   <tr>
			 <td align="right" nowrap>
			 	 <?php
				 	$page=$FREQUEST->getvalue('page');
					$cID=$FREQUEST->getvalue('cID');
					//if($error)
						//echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'page='. (int)$page . '&cID=' . (int)$cID , 'SSL') . '">' . tep_image_button('button_cancel.gif',IMAGE_CANCEL) . '</a>';
					//else
					echo '<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:\'customer_details\',type:\'cOrder\',get:\'customerUpdate\',validate:validateForm,result:doStep,message1:\'Updating...\',\'uptForm\':\'customers\',\'imgUpdate\':false,params:\'\'})">' .  tep_image_button('button_continue.gif', IMAGE_CONTINUE,'name="button_confirm1" id="button_confirm1"') . '</a>';
					 ?>
				</td>
		    </tr>
		  </table></form>
<?php 	  $jsData->VARS['storePage']=array('formName'=>'customers','fieldsDesc'=>$fieldsDesc,'dateFormat'=>$format[EVENTS_DATE_FORMAT],'formErrText'=>str_replace("\\n","--",JS_ERROR));

		}
		function docustomerUpdate()
		{
		global $FREQUEST,$FPOST,$FSESSION,$cart,$jsData,$ACCOUNT,$CUSTOMER,$ADDRESS,$INFO,$EXTRA,$PREV_ERROR,$pass;
		$CUSTOMER=array();
		$ACCOUNT=array();
		$ADDRESS=array();
		$INFO=array();
		$EXTRA=array();
		$PREV_ERROR=array();
		$pass=true;
		require_once(DIR_WS_CLASSES . 'customerAccount.php');
		$customerAccount=new customerAccount();
		$customers_id=$FREQUEST->postvalue('customers_id');
		$POST_=$FREQUEST->getRef("POST");
		if (count($POST_)>0){
			reset($POST_);
			//while(list($key,)=each($POST_)){
				foreach (array_keys($POST_) as $key) {
				//FOREACH 
				$ACCOUNT[$key]=$FREQUEST->postvalue($key);
			}
		}
        $fieldsDesc=array();
		//get the required display fields
		$query=tep_db_query("SELECT cif.*,cifd.label_text,cifd.input_description,cifd.error_text,cifd.input_title from ".  TABLE_CUSTOMERS_INFO_FIELDS . " cif, " . TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION . " cifd where cif.info_id=cifd.info_id and cifd.languages_id=" . $FSESSION->languages_id. " and cif.uniquename in('#1_firstname','customers_email_address','#1_lastname','entry_company','entry_street_address','entry_suburb','entry_postcode','entry_postcode','entry_city','country_state','customers_telephone','customers_fax')");
		$icnt=0;
		while($fieldsDesc[$icnt]=tep_db_fetch_array($query)){
			$fieldDesc=&$fieldsDesc[$icnt];
			if (strpos($fieldDesc['error_text'],"==")!==false){
				$fieldDesc['error_text']=str_replace(array("==MIN==","==MAX==","==DATE_FORMAT==","==EX_DATE=="),array($fieldDesc['textbox_min_length'],$fieldDesc['textbox_max_length'],$format[EVENTS_DATE_FORMAT],format_date('1970-05-20')),$fieldDesc['error_text']);
			}
			if (strpos($fieldDesc['input_description'],"==")!==false){
				$fieldDesc['input_description']=str_replace(array("==MIN==","==MAX==","==DATE_FORMAT==","==EX_DATE=="),array($fieldDesc['textbox_min_length'],$fieldDesc['textbox_max_length'],$format[EVENTS_DATE_FORMAT],format_date('1970-05-20')),$fieldDesc['input_description']);
			}
			$icnt++;
		}

		unset($fieldsDesc[$icnt]);

		for ($icnt=0,$n=count($fieldsDesc);$icnt<$n;$icnt++){
			$fieldDesc=&$fieldsDesc[$icnt];
			if (method_exists($customerAccount,"check__" . $fieldDesc['uniquename'])){
				$pass&=$customerAccount->{"check__" . $fieldDesc['uniquename']}($fieldDesc); // Change by Roy
			} else {
				$pass&=$customerAccount->commonCheck($fieldDesc); // Change by Roy
			}
		}
		if ($pass){
			$FSESSION->set('customer_id',$customers_id);
			tep_db_perform(TABLE_CUSTOMERS,$CUSTOMER,"update","customers_id='" . $FSESSION->customer_id. "'");
			if (count($ADDRESS)>0){
				tep_db_perform(TABLE_ADDRESS_BOOK,$ADDRESS,"update","customers_id='" .$FSESSION->customer_id . "'");
			}
			if (count($INFO)>0){
				if (isset($INFO["customers_info_source_id"])){
					if ($INFO["customers_info_source_id"]=='999'){
						tep_db_query("update " . TABLE_SOURCES_OTHER . " set  sources_other_name='". $INFO["source_other"] . "' where customers_id='". $FSESSION->customers_id . "'");
					}
					unset($INFO["source_other"]);
				}
				tep_db_perform(TABLE_CUSTOMERS_INFO,$INFO);
			}
			if (count($EXTRA)>0){
				reset($EXTRA);
				//while(list($key,$value)=each($EXTRA)){
					foreach($EXTRA as $key => $value) 
					{
					tep_db_query("REPLACE into " . TABLE_CUSTOMERS_EXTRA_INFO . " values('" . tep_db_input($key) . "','" . tep_db_input($value) . "'," . $FSESSION->customer_id .")");
				}
			}
			$check_sql = tep_db_query("select customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
			$check_adr=tep_db_fetch_array($check_sql);
			$FSESSION->set('customer_default_address_id',$check_adr['customers_default_address_id']);
			$select_country_query= tep_db_query("select entry_zone_id,entry_country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = " .(int)$FSESSION->customer_id);
			if(tep_db_num_rows($select_country_query)){
				while($customer_country_array = tep_db_fetch_array($select_country_query))
				{
					  $FSESSION->set('customer_country_id',$customer_country_array['entry_country_id']);
					  $FSESSION->set('customer_zone_id',$customer_country_array['entry_zone_id']);
				}
			}
				//--------saving address in a temporary table-----------------
				// if($FSESSION->is_registered('order_id'))
				// {
					// tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ADDRESS . " where customers_id='" . (int)$FSESSION->customer_id . "' and orders_id='" . (int)$FSESSION->order_id . "'");
					// $address_query = tep_db_query("select * from " . TABLE_ORDERS . " where orders_id='" . (int)$FSESSION->order_id . "'");
					// $address = tep_db_fetch_array($address_query);
					// $sql_data_array = array('customers_id' => $FSESSION->customer_id,
											 // 'orders_id' => $FSESSION->order_id,
											// 'billing_name' => tep_db_input(stripslashes($address['billing_name'])),
											// 'billing_company' =>tep_db_input(stripslashes($address['billing_company'])) ,
											// 'billing_street_address' => tep_db_input(stripslashes($address['billing_street_address'])),
											// 'billing_suburb' =>tep_db_input(stripslashes($address['billing_suburb'])),
											// 'billing_city' =>tep_db_input(stripslashes( $address['billing_city'])),
											// 'billing_state' =>$address['billing_state'],
											// 'billing_postcode' =>tep_db_input(stripslashes($address['billing_postcode'])),
											// 'billing_country' =>tep_db_input(stripslashes($address['billing_country'])),
											// 'billing_address_format_id'=>tep_db_input(stripslashes( $address['billing_address_format_id'])),
											// 'delivery_name' => tep_db_input(stripslashes($address['delivery_name'] )),
											// 'delivery_company' =>tep_db_input(stripslashes($address['delivery_company'])),
											// 'delivery_street_address' =>tep_db_input(stripslashes($address['delivery_street_address'])),
											// 'delivery_suburb' =>tep_db_input(stripslashes( $address['delivery_suburb'])),
											// 'delivery_city' => tep_db_input(stripslashes($address['delivery_city'])),
											// 'delivery_state' =>$address['delivery_state'],
											// 'delivery_postcode' =>tep_db_input(stripslashes($address['delivery_postcode'])),
											// 'delivery_country' =>tep_db_input(stripslashes($address['delivery_country'])),
											// 'delivery_address_format_id'=>tep_db_input(stripslashes($address['delivery_address_format_id'])),
											// 'customers_telephone' =>tep_db_input(stripslashes($address['customers_telephone'])),
											// 'customers_second_telephone' =>tep_db_input(stripslashes($address['customers_second_telephone'])),
											// 'customers_fax' =>tep_db_input(stripslashes($address['customers_fax'])),
											// 'customers_second_email_address' =>tep_db_input(stripslashes($address['customers_second_email_address'])),
											// 'customers_email_address' =>tep_db_input(stripslashes($address['customers_email_address'])));
					// tep_db_perform(TABLE_CUSTOMERS_BASKET_ADDRESS, $sql_data_array);
				// }
				// else
				// {
					// tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ADDRESS . " where customers_id='" . (int)$FSESSION->customer_id . "' and orders_id=0");
					// $shipping_address_query = tep_db_query("select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, ab.entry_country_id, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " c on (ab.entry_country_id = c.countries_id) where ab.customers_id = '" . (int)$FSESSION->customer_id . "' and ab.address_book_id = '" . (int)$FSESSION->customer_default_address_id . "'");
					// $shipping_address = tep_db_fetch_array($shipping_address_query);
					// $billing_address_query = tep_db_query("select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, ab.entry_country_id, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " c on (ab.entry_country_id = c.countries_id) where ab.customers_id = '" . (int)$FSESSION->customer_id . "' and ab.address_book_id = '" . (int)$FSESSION->customer_default_address_id . "'");
					// $billing_address = tep_db_fetch_array($billing_address_query);
					// $sql_data_array = array('customers_id' => $FSESSION->customer_id,
											 // 'orders_id' => 0,
											// 'billing_name' => tep_db_input(stripslashes($billing_address['entry_firstname'] . ' ' . $billing_address['entry_lastname'])),
											// 'billing_company' =>tep_db_input(stripslashes($billing_address['entry_company'])) ,
											// 'billing_street_address' => tep_db_input(stripslashes($billing_address['entry_street_address'])),
											// 'billing_suburb' =>tep_db_input(stripslashes($billing_address['entry_suburb'])),
											// 'billing_city' =>tep_db_input(stripslashes( $billing_address['entry_city'])),
											// 'billing_state' =>((tep_not_null($billing_address['entry_state'])) ? $billing_address['entry_state'] : $billing_address['zone_name']),
											// 'billing_postcode' =>tep_db_input(stripslashes($billing_address['entry_postcode'])),
											// 'billing_country' =>tep_db_input(stripslashes($billing_address['countries_name'])),
											// 'billing_address_format_id'=>tep_db_input(stripslashes( $billing_address['address_format_id'])),
											// 'delivery_name' => tep_db_input(stripslashes($shipping_address['entry_firstname'] . ' ' . $shipping_address['entry_lastname'])),
											// 'delivery_company' =>tep_db_input(stripslashes($shipping_address['entry_company'])),
											// 'delivery_street_address' =>tep_db_input(stripslashes($shipping_address['entry_street_address'])),
											// 'delivery_suburb' =>tep_db_input(stripslashes( $shipping_address['entry_suburb'])),
											// 'delivery_city' => tep_db_input(stripslashes($shipping_address['entry_city'])),
											// 'delivery_state' =>((tep_not_null($shipping_address['entry_state'])) ? $shipping_address['entry_state'] : $shipping_address['zone_name']),
											// 'delivery_postcode' =>tep_db_input(stripslashes($shipping_address['entry_postcode'])),
											// 'delivery_country' =>tep_db_input(stripslashes($shipping_address['countries_name'])),
											// 'delivery_address_format_id'=>tep_db_input(stripslashes($shipping_address['address_format_id'])),
											// 'customers_telephone' =>tep_db_input(stripslashes($ACCOUNT['customers_telephone'])),
											// 'customers_second_telephone' =>tep_db_input(stripslashes($ACCOUNT['customers_second_telephone'])),
											// 'customers_fax' =>tep_db_input(stripslashes($ACCOUNT['customers_fax'])),
											// 'customers_second_email_address' =>tep_db_input(stripslashes($ACCOUNT['customers_second_email_address'])),
											// 'customers_email_address' =>tep_db_input(stripslashes($ACCOUNT['customers_email_address'])),
											// 'date_added' =>'2017-11-01 00:00:00'
											// );
                    // tep_db_perform(TABLE_CUSTOMERS_BASKET_ADDRESS, $sql_data_array);
				// }
				// $jsData->VARS['storePage']['cOrder_step']='2';
				// $jsData->VARS['storePage']['cartdata']['customer_id']=$FSESSION->customer_id;
				// $this->doShopping();
			  }
			  else
			  {
                  $jsData->VARS['storePage']['cOrder_step']='1';
			  	  echo "ERROR:||";
			  		for ($icnt=0,$n=count($customerAccount->errors);$icnt<$n;$icnt++){
						echo $customerAccount->errors[$icnt] .'<br>';
					}
			  }
		}
		function doShopping()
		{
			global $FREQUEST,$FSESSION,$cart,$order,$shipping_modules,$currencies,$jsData;
			require(FILENAME_SHOPPING_CART_NEW);
			//$jsData->VARS['storePage']['max_attendees']= $cart->get_max_attendees();
            $jsData->VARS['storePage']['cOrder_step']= 2;
             //$jsData->VARS['storePage']['cartdata']['cart_count']=sizeof($cart);
		}
		function doProductNew()
		{
			global $FREQUEST,$FSESSION,$cart,$order,$currencies;
			tep_db_query("SET SQL_BIG_SELECTS=1"); // CARTZONE added
			$result = tep_db_query("SELECT products_name, p.products_id, categories_name, ptc.categories_id FROM " . TABLE_PRODUCTS . " p LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON pd.products_id=p.products_id LEFT JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc ON ptc.products_id=p.products_id LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON cd.categories_id=ptc.categories_id ORDER BY categories_name");
			$ProductList = array();
			while($row = tep_db_fetch_array($result))
			{
               
				extract($row,EXTR_PREFIX_ALL,"db");
				$ProductList[$db_categories_id][$db_products_id] = $db_products_name;
				$CategoryList[$db_categories_id] = $db_categories_name;
				$LastCategory = $db_categories_name;
			}
            
			// ksort($ProductList);
			$LastOptionTag = "";
			$ProductSelectOptions = "<option value='0'>Don't Add New Product" . $LastOptionTag . "\n";
			$ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
			foreach($ProductList as $Category => $Products)
			{
				$ProductSelectOptions .= "<option value='0'>$Category" . $LastOptionTag . "\n";
				$ProductSelectOptions .= "<option value='0'>---------------------------" . $LastOptionTag . "\n";
				asort($Products);
				foreach($Products as $Product_ID => $Product_Name)
				{
					$ProductSelectOptions .= "<option value='$Product_ID'> &nbsp; $Product_Name" . $LastOptionTag . "\n";
				}
				if($Category != $LastCategory)
				{
					$ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
					$ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
				}
			}

	 ?>
			<form name="new_product" method="post">
			<table border="0" width="100%" cellspacing="2" cellpadding="2">
			  <tr>
				<td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
			  <tr>
				<td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr >
					<td class="main"><b><?php echo ADDING_TITLE; ?></b> </td>
					<td align="right">
					<span id="itemnew_product_step2mnormal" style="display:none"></span>
					<span id="itemnew_product_step2mupdate" style="display:none">
					<a href="javascript:void(0)" onclick="javascript:return doUpdateAction({'id':'new','get':'ProductUpdate','imgUpdate':false,'type':'item','style':'boxRow','validate':validateproductForm,'uptForm':'new_product','customUpdate':doProductAdd,'result':doStep,'message1':page.template['UPDATE_DATA']});"><img src="<?php echo DIR_WS_IMAGES?>template/img_save_green.gif" border="0"/></a>
					<img src="<?php echo DIR_WS_IMAGES?>template/img_bar.gif" border="0"/>
					</span>
					<a href="javascript:void(0)" onclick="javascript:return doclosenew();"><img src="<?php echo DIR_WS_IMAGES?>template/img_close_blue.gif" border="0"></a>
					</td>
				  </tr>
				</table></td>
			  </tr>
			<?php
				// ############################################################################
				//   Add Products Steps
				// ############################################################################
					print "<tr><td><table border='0' width=100%>\n";
					print "<tr>\n";
					print "<td class='dataTableContent' align='right'><b>STEP 1:</b></td><td class='dataTableContent' valign='top'>";
					$products_array = array();
					$products_array = tep_get_products_array_single();
                    
                  
                   
					if(sizeof($products_array)<=0) echo TEXT_PRODUCTS_NOT_AVAILABLE;
					else echo tep_draw_products_select_menu('add_product_products_id',$products_array,$add_product_products_id,' id="add_product_products_id" onchange="javascript:do_page_fetch(\'new_product_step2\');"');
					print "</tr>\n";
					print "<tr><td colspan='3'>". tep_draw_separator('pixel_trans.gif','5','5'). "</td></tr>\n";
					print "<tr><td colspan='3' id='new_product_step2'></td></tr>";
					print "<tr><td colspan='3' id='product_submit'></td></tr>";
				?>
				</table></td>
			  </tr>
			</table>
			</form>
<?php	}
function doNewProductStep2()
{
	global $FREQUEST,$jsData,$currencies,$JS_DETAILS,$FSESSION;
		$prd_id=(int)$FREQUEST->getvalue('add_product_products_id');
		$products_query=tep_db_query("select is_attributes,products_price_break from " . TABLE_PRODUCTS . " where products_id='" . $prd_id . "'");
		$products_result=tep_db_fetch_array($products_query);
		$pattrb='{enabled:false,count:0}';
		$ppbreaks='{enabled:false}';
		if($products_result['is_attributes']=='Y')
			$pattrb=set_product_values($prd_id,false);
		if($products_result['products_price_break']	=='Y')
			$ppbreaks=tep_get_products_breaks($prd_id);
		$JS_DETAILS=array();
		$this->out_product_details($prd_id);
		$jsData->VARS['storePage']['products']['id']=$prd_id;
		$jsData->VARS['storePage']['products']['stock']=tep_get_products_stock($prd_id);
		$jsData->VARS['storePage']['products']['priceBreaks']=$ppbreaks;
		$jsData->VARS['storePage']['products']['priceCalc']=array("getProductsPrice()","getAttributePrice(-1)");
		$jsData->VARS['storePage']['products']['valid']=false;
		$jsData->VARS['storePage']['products']['priceAttr']=$pattrb;
		$jsData->VARS['storePage']['products']['saleMaker']=$JS_DETAILS["SALEMAKER_DATAS"];
		$jsData->VARS['storePage']['products']['currency']="{symbolLeft:\'" .$currencies->currencies[DEFAULT_CURRENCY]['symbol_left'] . "\',symbolRight:\'" . $currencies->currencies[DEFAULT_CURRENCY]['symbol_right'] . " \'}";
		$jsData->VARS["updateMenu"]=",update,";
}
function out_product_details($add_product_products_id)
{
	global $currencies,$JS_DETAILS,$FSESSION;
	print "<table width='100%' border='0'><tr><td width=145></td><td>";
	$pro_query=tep_db_query("select products_price,products_tax_class_id,products_quantity,is_attributes,product_type,products_price_break from " . TABLE_PRODUCTS . " where products_id='" . (int)$add_product_products_id . "'");
	$product=tep_db_fetch_array($pro_query);
	$tax_rate=tep_get_tax_rate($product["products_tax_class_id"]);
	$quantity_is_combobox = false;
	if ($new_price = tep_get_products_spl_price($add_product_products_id)) {
	  $products_dprice = '<s>' . $currencies->display_price($product['products_price'], $tax_rate,'1',true) . '</s> <font color="red">' . $currencies->format(tep_add_tax($new_price, $tax_rate)) . '</font>';
	  $pro_total=tep_add_tax($new_price, $tax_rate);
	  $products_original_price=$new_price;
	  $products_total_price=$currencies->format(tep_add_tax($new_price, $tax_rate));
	} else {
	  $products_dprice = $currencies->display_price($product["products_price"], $tax_rate,'1',true);
	  $pro_total=tep_add_tax(tep_get_plain_products_price($product["products_price"]), $tax_rate);
	  $products_original_price=$product["products_price"];
	  $products_total_price=$currencies->format(tep_add_tax($product["products_price"], $tax_rate));
	}
    $product_to_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . tep_db_input($add_product_products_id) . "'");
    $product_to_categories = tep_db_fetch_array($product_to_categories_query);
    $category_id = $product_to_categories['categories_id'];

	$special_price=$new_price;
	print "<table width=100% cellpadding=1>";

	if ($product["products_price_break"]!="Y"){
		$products_price_break_sql = "select * from " . TABLE_PRODUCTS_PRICE_BREAK . " ppb where products_id = '" . (int)$add_product_products_id . "'";
		$products_price_break_query = tep_db_query($products_price_break_sql);
		if(tep_db_num_rows($products_price_break_query) > 0){
			$quantity_is_combobox = true;
			$pro_total=tep_add_tax($product["products_price"], $tax_rate);
			$products_dprice=$currencies->format($pro_total);
		}
		print "<tr ><td align='left' class='dataTableContent'><b>Price:</b></td><td align='left' class='smalltext' colspan='2'>&nbsp;" . $products_dprice;
		print "</td></tr>";
	}

	print "<input type='hidden' id='txt_price' name='txt_price' value='" .$products_original_price . "'>";
	print "<input type='hidden' id='txt_tax' name='txt_tax' value='" .tep_get_tax_rate($product["products_tax_class_id"]) . "'>";
	print "<input type='hidden' id='product_stock' name='product_stock' value='" .tep_get_products_stock($add_product_products_id) . "'>";
	//PSP
	if($product['product_type']=='S') {
		print "<tr><td colspan='3'>" . tep_draw_separator('pixel_trans.gif','5','5') . "</td></tr>\n";
		print "<tr><td colspan='3'>" . tep_show_support_packs($add_product_products_id) . "</td></tr>";
	}
	print "<tr><td colspan='3'>" . tep_draw_separator('pixel_trans.gif','5','5') . "</td></tr>\n";
	print "<tr><td class='dataTableContent' align='right' valign=top width=1% nowrap>
	<table width='100%' border='0'>
		<tr><td class='dataTableContent' align='left' valign=top nowrap>
		<b>STEP 2:</b>
		</td></tr>
	</table></td><td colspan='2'><table cellpadding='2' cellspacing='2' borer='0' width='100%'>";

		if($quantity_is_combobox){
			$out_of_stock=tep_check_stock($add_product_products_id, 0);
			if($out_of_stock==""){
				//if(tep_db_num_rows($result) > 0)print "</td><td height='20'></td></tr></table>";
				print "<tr><td colspan='3'><br>";
				print "<div style='border:1px solid #93AAEE;align:left;width:150px;height:100px;overflow:auto;'>";
				print "<table width='100%' cellpadding='1' cellspacing='1'  id='tablePriceBreaks'>";
				print  product_prices($add_product_products_id);
				print "</table></div></td></tr>";
			}
			print "<input type='hidden' id='qty' name='qty' value=''>";
		}else{ $qty=1;
			print "<tr >";
			print "<td class='dataTableContent' align='' width=10%><b>Quantity</b></td>";
			print "<td class='' valign='top' colspan='2'>";
			print "<input name='qty' size='2' id='qty' value='" . $qty . "' class='inputnormal' onkeyup='quanAutoCheck();' onblur=\"javascript:checkStock('');\" onKeyDown=\"javascript:return numericOnly(event);\"> </td>";
			print "</tr>";
		}
			$JS_DETAILS['SALEMAKER_DATAS']='{enabled:false}';
				if ($product["products_price_break"]!="Y"){
					$has_option="";
					if ($special_price) $has_option=" and sale_specials_condition!=1";
					$sDate=getServerDate(true);
					//check for product_discount price
					$sale_query = tep_db_query("select sale_id,sale_specials_condition, sale_deduction_value, sale_deduction_type,choice_text,choice_warning from " . TABLE_SALEMAKER_SALES . " where sale_discount_type='C' " . $has_option . " and ((sale_categories_all='' and sale_products_selected='') or sale_categories_all like '%," . tep_db_input($category_id) . ",%' or sale_products_selected like '%," . tep_db_input($add_product_products_id) .",%') and sale_status = '1' and (sale_date_start <='" . tep_db_input($sDate) . "' or sale_date_start = '0000-00-00') and (sale_date_end >= '" . tep_db_input($sDate) . "' or sale_date_end = '0000-00-00') and (sale_pricerange_from <= " . $product['products_price'] . " or sale_pricerange_from = '0') and (sale_pricerange_to >= " . $product['products_price'] . " or sale_pricerange_to = '0') order by sale_deduction_value");
					if (tep_db_num_rows($sale_query)>0){

						echo '<tr><td class="main" colspan="3"><b>' . TEXT_SELECT_SALEMAKER_DISCOUNT . '<b></td></tr>';
						echo '<tr><td colspan="3"><table border="0" cellpadding="1" cellspacing="0">';
						$JS_DETAILS['SALEMAKER_DATAS']='{enabled:true,sales:{';

						echo '<tr><td class="main"  width="30">' . tep_draw_radio_field('salemaker_id',-1,true,'','id="salemaker_id" onClick="javascript:selectDiscount(0);"') . '</td><Td class="main">' . TEXT_NO_DISCOUNT . '</td></tr>';
						while($sale=tep_db_fetch_array($sale_query)){
							$JS_DETAILS['SALEMAKER_DATAS'].=$sale["sale_id"] . ":{price:";
							switch($sale['sale_deduction_type']){
								case 1:
									$choice_text=$sale["choice_text"] . " (" . number_format($sale["sale_deduction_value"],2). "%)";
									break;
								case 2:
									$choice_text=$sale["choice_text"] . " (<s><font color=\'red\'>" . $currencies->format(tep_add_tax($products_original_price, $tax_rate)) . "</font></s>&nbsp;" . $currencies->format($sale["sale_deduction_value"], $tax_rate) . ")";
									break;
								default:
									$choice_text=$sale["choice_text"] . " (" . $currencies->format($sale["sale_deduction_value"],$tax_rate). ")";
									break;

							}
							//$pro_total=tep_add_tax(tep_get_salemaker_price($special_price,$products_original_price,$sale),$tax_rate);
							$JS_DETAILS['SALEMAKER_DATAS'].=tep_add_tax(tep_get_salemaker_price($special_price,$product["products_price"],$sale),$tax_rate) . ",warning:'" .htmlspecialchars($sale["choice_warning"]) . "'},";
							echo '<tr><td class="main">' .  tep_draw_radio_field('salemaker_id',$sale["sale_id"],false,'',' onClick="javascript:selectDiscount(' . $sale["sale_id"] . ');"')  . '</td><Td class="main">' . $choice_text  . '</td></tr>';
						}
						$JS_DETAILS['SALEMAKER_DATAS']=substr($JS_DETAILS['SALEMAKER_DATAS'],0,-1) . "}}";
					}
					echo '</table><td></tr><tr>
						<td class="main" colspan="3">
							<div  style="display:none;background:#E84A92;color:#FFFFFF;padding:1px 5px 1px 5px" id="salemaker_info">
							</div>
						</td>
					</tr>';
					print "<input type='hidden' id='price_breaks' name='price_breaks' value='0'>";
				}
			else print "<input type='hidden' id='price_breaks' name='pricebreaks' value='1'>";
		print "<input type='hidden' id='product_price' name='product_price' value='" . $pro_total . "'>";
		print '<tr><td class="main" colspan="3"><span id="stockProductQuan" style="display:none"><b>Checking Stock...</b></span></td></tr>';
		if($product['is_attributes']=='Y') {
			$pattributes='';
			$pattr_array=tep_get_products_attributes($add_product_products_id);

			print '<tr><td colspan="3"><table cellpadding="2" cellspacing="2" border="0" width="100%">';
			for($acnt=0,$an=count($pattr_array);$acnt<$an;$acnt++){
				$arr_element=$pattr_array[$acnt];
				print '<tr><td class="dataTableContent" width="150"><b>' . $arr_element['name'] . '</b></td><td class="dataTableContent" width="200">' . $arr_element['value'] . '</td><td class="dataTableContent"><b>' . $arr_element['price'] . '</b></td></tr>';
			}
			print '</table></td></tr>';
			print '<tr><td class="main" colspan="3"><span id="stockAttrQuan" style="display:none"><b>Checking Stock...</b></span></td></tr>';
		}
		print '<tr><td class="main" colspan="3"><span id="outstock" style="display:none;color:#ff0000" >The product is not currently in Stock.</span></td></tr>';
		print "<tr><td colspan='3'>&nbsp;</td></tr>\n";
		print "<tr><td class='dataTableContent' align='center' colspan='3'><b>Total Price: " .'<span id="totalProductsPrice">' . $products_dprice ."</span></td></tr>";
		print "</table></td></tr>";
		print "</table></td></tr>\n";
		print "</table>\n";
}
function doCheckAttribStock()
{
	global $FREQUEST;
	$attrib_ids=$FREQUEST->getvalue('attrib_ids');
	$products_id=$FREQUEST->getvalue('products_id');
	$quan=$FREQUEST->getvalue('quan','int',1);
	if ($attrib_ids!=''){
		$temp_array=preg_split("/-/",$attrib_ids);
		usort($temp_array,"ucmp");
		$attrib_ids=join("-",$temp_array);
		//$stock_query=tep_db_query("SELECT attributes_id from " . TABLE_PRODUCTS_STOCK . " where attribute_status='1' and  products_id=" . tep_db_input($products_id) . " and attributes_id='" . tep_db_input($attrib_ids) . "' and products_quantity>=" . $quan);
		if (tep_db_num_rows($stock_query)>0) echo "1";
		else echo "0";
	}
}

function doFetchCustomersDetails()
{
	global $FREQUEST;
	$cnt= $FREQUEST->getvalue('cnt');
	$cust_id= $FREQUEST->getvalue('cust_id');
	$customer_query=tep_db_query("select c.customers_firstname,c.customers_lastname,c.customers_email_address as email_address,a.entry_postcode as postcode,a.entry_city as city,a.entry_zone_id as state,a.entry_country_id as country,a.entry_street_address as street_address,a.entry_state from ".TABLE_CUSTOMERS ." c LEFT JOIN ".TABLE_ADDRESS_BOOK." a on(c.customers_id=a.customers_id) where c.customers_id='".tep_db_input($cust_id)."'");
	if(tep_db_num_rows($customer_query)>0){
		$customer=tep_db_fetch_array($customer_query);
		$first_name=$customer['customers_firstname'];
		$last_name=$customer['customers_lastname'];
		$email_address=$customer['email_address'];
		$street_address=$customer['street_address'];
		$city=$customer['city'];
		$post_code=$customer['postcode'];
		$state=tep_get_zone_name($customer['country'],$customer['state'],$customer['entry_state']);
		$country=$customer['country'];
		$customer_list=$first_name."^".$last_name."^".$email_address."^".$street_address."^".$city ."^".$customer['postcode']."^".$state."^".$country;
	}
	echo $customer_list.'##'.$cnt;
}
function doShowState()
{
	  global $FREQUEST;
   	   $zone_ids="";
	   $zone_name="";
	   $country=$FREQUEST->getvalue('country_id','int',0);
	   $zones_query = tep_db_query("select zone_id,zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' order by zone_name");
	   while ($zones_values = tep_db_fetch_array($zones_query)) {
		   $zone_ids.=$zones_values['zone_id'].",";
		   $zone_name.=$zones_values['zone_name'].",";
	   }
	   $cnt=$FREQUEST->getvalue('cnt');
	   if($cnt!='') $content= substr($zone_ids,0,-1)."^".substr($zone_name,0,-1)."^".$zone."^".$cnt;
	   else  $content=substr($zone_ids,0,-1)."^".substr($zone_name,0,-1);
	   echo $content;
}
function doShowStateBilling()
{
	global $FREQUEST;
		$type=$FREQUEST->getvalue('type','string','B');
		$country=$FREQUEST->getvalue('country_id','int',0);
		$id=$FREQUEST->getvalue('id','int','');
		$zone_ids="";
		$zone_name="";
		$zones_query = tep_db_query("select zone_id,zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' order by zone_name");
		while ($zones_values = tep_db_fetch_array($zones_query)) {
			$zone_ids.=$zones_values['zone_id'].",";
			$zone_name.=$zones_values['zone_name'].",";
		}
		$content=substr($zone_ids,0,-1)."^".tep_output_string(substr($zone_name,0,-1))."^".$zone."^".$type;
		echo $content;
	}
function doShowCustomerDetails()
{
global $FREQUEST;
	$customers_id=(int)$FREQUEST->getvalue('cID');
	doCustomer_details($customers_id);
}


function doBillingShipping()
{
	global $FREQUEST,$FSESSION,$cart,$order,$currencies,$jsData;
	 $billto=$FSESSION->customer_default_address_id;
	 $sendto=$FSESSION->customer_default_address_id;
	 $SeparateBillingFields = tep_field_exists(TABLE_ORDERS, "billing_name");
		//manual price adjustment
	$modify_price_prefix=$FREQUEST->getvalue('modify_price_prefix');
	$sign=$FREQUEST->getvalue('sign');
	$purpose=$FREQUEST->getvalue('purpose');
	$manual_option=$FREQUEST->getvalue('manual_option');
	if($sign=='plus') {
	$sign='+'; }
	else {
	$sign='-';
	$modify_price_prefix=$modify_price_prefix*-1;
	}
	$disable_shipping =$FREQUEST->getvalue('disable_shipping');
	// setting session values for manual price adjustment
	$FSESSION->set('modify_price_prefix',$modify_price_prefix);
	$FSESSION->set('sign',$sign);
	$FSESSION->set('purpose',$purpose);
	$FSESSION->set('manual_option',$manual_option);
	$FSESSION->set('billto',$billto);
	$FSESSION->set('sendto',$sendto);
  ?>
  <?php 
  //We got the Shipping step
  
  
  echo tep_draw_form('payment_address', FILENAME_EDIT_ORDERS, 'oID=' . $oID  . '&action=update_order&step=' . $step,'post','onSubmit="return validateForm()"'); ?>
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr><td valign=top><table width="100%" border="0" cellspacing="0" cellpadding="2">
       <?php if($SeparateBillingFields) {  ?>
   		<?php tep_content_title_top(ENTRY_CUSTOMER_ADDRESS); ?>
	   <tr>
	    <td valign="top">
	    <!-- Billing Address Block -->
		<table border="0" cellspacing="1" cellpadding="2"  width=60%>
		<tr>
		    <td class="main" width="30%"><?php echo TEXT_NAME; ?></td>
		    <td class="main" nowrap="nowrap"><?php echo tep_draw_input_field('update_billing_name',tep_html_quotes($order->billing['name']),'size=25',true); ?></td>
			<td class="main" nowrap="nowrap">&nbsp;&nbsp;&nbsp;<?php echo TEXT_ADDRESS; ?></td>
		    <td class="main" nowrap="nowrap">&nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('update_billing_street_address',tep_html_quotes($order->billing['street_address']),'size=25',true); ?></td>
		</tr>
		<tr>
		    <td class="main"><?php echo TEXT_COMPANY; ?></td>
		    <td class="main"><?php echo tep_draw_input_field('update_billing_company',tep_html_quotes($order->billing['company']),'size=25'); ?></td>
			<td class="main" nowrap="nowrap">&nbsp;&nbsp;&nbsp;<?php echo TEXT_SUBURB; ?></td>
		    <td class="main">&nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('update_billing_suburb',tep_html_quotes($order->billing['suburb']),'size=25'); ?></td>
		</tr>
		<tr>
      		  <td class="main" width="30%" nowrap><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
     		  <td class="main" nowrap="nowrap"><?php echo tep_draw_input_field('update_customer_telephone',$order->customer['telephone'],'size=25',true); ?></td>
        	  <td class="main" nowrap="nowrap">&nbsp;&nbsp;&nbsp;<?php echo TEXT_CITY; ?></td>
		      <td class="main" nowrap="nowrap">&nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('update_billing_city',tep_html_quotes($order->billing['city']),'size=25',true); ?></td>
		</tr>
		 <tr>
		    <td class="main"><?php echo TEXT_MOBILE; ?></td>
		    <td class="main"><?php echo tep_draw_input_field('update_customer_fax',tep_html_quotes($order->customer['fax']),'size=25'); ?></td>

		    <td class="main" nowrap="nowrap">&nbsp;&nbsp;&nbsp;<?php echo TEXT_COUNTRY; ?></td>
		    <td class="main" nowrap="nowrap">&nbsp;&nbsp;&nbsp;<?php
		    $country_id=tep_html_quotes($order->billing['country_id']);
		    echo sbs_get_country_list('update_billing_country', $country_id,'id="update_billing_country"  onchange="javascript:show_state_billing(this);"');
		    // echo tep_draw_input_field('update_billing_country',tep_html_quotes($order->billing['country']),'size=37 id="update_billing_country" onchange="javascript:show_state();"',true); ?></td>
		</tr>
		<tr>
		  <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
		  <td class="main" nowrap><?php echo tep_draw_input_field('update_customer_email_address',$order->customer['email_address'],'size=25',true); ?></td>
			<td class="main" nowrap="nowrap">&nbsp;&nbsp;<?php echo TEXT_STATE; ?></td>
		    <td class="main" nowrap="nowrap">&nbsp;&nbsp;&nbsp;<?php
		    $state=tep_html_quotes($order->billing['state']);
		    //$zone_id=tep_get_zone_id($country_id,$state);
		    $zones_array = array();
          	$zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country_id) . "' order by zone_name");
          	while ($zones_values = tep_db_fetch_array($zones_query)) {
	           $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
          	}
          	$sty_txt="";
          	$sty_combo='style="display:none"';
          	if(count($zones_array)>0){
          		$sty_combo='';
          		$sty_txt='style="display:none"';
          	}
		    echo tep_draw_input_field('update_billing_state1',$state,'size=25 '.$sty_txt);
    		echo tep_draw_pull_down_menu('update_billing_state', $zones_array,$state,$sty_combo);
		    //echo tep_draw_input_field('update_billing_state',tep_html_quotes($order->billing['state']),'size=37',true); ?></td>
		</tr>
		<tr>
	   </tr>
	  </table>
	 </td>
	</tr>
	<?php tep_content_title_bottom(); ?>
<?php  } ?>
	 <?php tep_content_title_top(ENTRY_SHIPPING_ADDRESS); ?>
	  <tr>
	    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif','1','3'); ?></td>
	  </tr>
	  <tr>
		<td valign="top">
	   <!-- Shipping Address Block -->
		<table border="0" cellspacing="1" cellpadding="2"  width=60%>
		<tr>
		    <td class="main" width="30%"><?php echo TEXT_NAME; ?></td>
		    <td class="main" nowrap="nowrap">
				<?php echo tep_draw_separator('pixel_trans.gif', 53, 10);
				 echo tep_draw_input_field('update_delivery_name',tep_html_quotes($order->delivery['name']),'size=25',true); ?>
			</td>
			<td class="main" nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo TEXT_CITY; ?></td>
		    <td class="main" nowrap="nowrap">&nbsp;&nbsp;
			<?php echo tep_draw_input_field('update_delivery_city',tep_html_quotes($order->delivery['city']),'size=25',true); ?></td>
       </tr>
		    <td class="main" nowrap="nowrap"><?php echo TEXT_COMPANY; ?></td>
		    <td class="main" nowrap="nowrap">
				<?php  echo tep_draw_separator('pixel_trans.gif', 53, 10);
				echo tep_draw_input_field('update_delivery_company',tep_html_quotes($order->delivery['company']),'size=25'); ?></td>
			<td class="main" nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo TEXT_POSTCODE; ?></td>
		    <td class="main" nowrap="nowrap">&nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('update_delivery_postcode',tep_html_quotes($order->delivery['postcode']),'size=25',true); ?></td>
		</tr>
		<tr>
		    <td class="main" nowrap="nowrap"><?php echo TEXT_ADDRESS; ?></td>
		    <td class="main" nowrap="nowrap">
				<?php echo tep_draw_separator('pixel_trans.gif', 53, 10);
				 echo tep_draw_input_field('update_delivery_street_address',tep_html_quotes($order->delivery['street_address']),'size=25',true); ?></td>
			<td class="main" nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo TEXT_COUNTRY; ?></td>
		    <td class="main" nowrap="nowrap">&nbsp;&nbsp;&nbsp;<?php
		    $country_id=tep_html_quotes($order->delivery['country_id']);
		    echo sbs_get_country_list('update_delivery_country', $country_id,'id="update_delivery_country" onchange="javascript:show_state_billing(this);"');
		    //echo tep_draw_input_field('update_delivery_country',tep_html_quotes($order->delivery['country']),'size=37',true); ?></td>
		</tr>
 <tr>
		    <td class="main" nowrap="nowrap"><?php echo TEXT_SUBURB; ?></td>
		    <td class="main" nowrap="nowrap">
			   <?php echo tep_draw_separator('pixel_trans.gif', 53, 10);
			    echo tep_draw_input_field('update_delivery_suburb',tep_html_quotes($order->delivery['suburb']),'size=25'); ?>
			</td>
  		<td class="main" nowrap="nowrap">&nbsp;&nbsp;&nbsp;<?php echo TEXT_STATE; ?></td>
		    <td class="main" nowrap="nowrap">&nbsp;&nbsp;&nbsp;<?php
		    $state=tep_html_quotes($order->delivery['state']);
		    //$zone_id=tep_get_zone_id($country_id,$state);
		    $zones_array = array();
          	$zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country_id) . "' order by zone_name");
          	while ($zones_values = tep_db_fetch_array($zones_query)) {
	           $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
          	}
 			$sty_txt="";
          	$sty_combo='style="display:none"';
          	if(count($zones_array)>0){
          		$sty_combo='';
          		$sty_txt='style="display:none"';
          	}
		    echo tep_draw_input_field('update_delivery_state1',$state,'size=37 '.$sty_txt);
    		echo tep_draw_pull_down_menu('update_delivery_state',$zones_array,$state,$sty_combo);
		    ?></td>
	  </tr>
	  <tr height="10"></tr>
    <?php tep_content_title_bottom(); ?>
	<tr>
		<td class="contentTitle" height="1px"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
	</tr>
	<tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" >
          <tr class="main">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td align="right" class="main" nowrap><?php
				echo '<a href="javascript:void(0);" onclick="javascript:do_page_fetch(\'submit_payment_address\');">' . tep_image_button('button_continue.gif', IMAGE_CONTINUE,'name="button_confirm3" id="button_confirm3"') . '</a>'; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
	</table></td>
      </tr></table></td></tr>
<!-- body_text_eof //-->
</table>
</form><?php
		if($disable_shipping)
			$is_shipping=0;
		elseif($cart->show_weight()!=0)
			$is_shipping=1;
		else
			$is_shipping=0;
		$FSESSION->set('is_shipping',$is_shipping);
		$jsData->VARS['storePage']['is_shipping']=$is_shipping;
		$jsData->VARS['storePage']['cOrder_step']='3';
		$jsData->VARS['storePage']['cartdata']['cart_count']=sizeof($cart);
	}
	function doBillingShippingSubmit()
	{
		global $FREQUEST,$jsData,$FSESSION;
		$SeparateBillingFields = tep_field_exists(TABLE_ORDERS, "billing_name");
		$AddShippingTax = "0.0"; // e.g. shipping tax of 17.5% is "17.5"
		$update_delivery_name = $FREQUEST->postvalue('update_delivery_name');
		$update_delivery_company = $FREQUEST->postvalue('update_delivery_company');
		$update_delivery_street_address=$FREQUEST->postvalue('update_delivery_street_address');
		$update_delivery_suburb=$FREQUEST->postvalue('update_delivery_suburb');
		$update_delivery_city=$FREQUEST->postvalue('update_delivery_city');
		$update_delivery_state=$FREQUEST->postvalue('update_delivery_state1');
		$update_delivery_postcode=$FREQUEST->postvalue('update_delivery_postcode');
		$update_delivery_country=$FREQUEST->postvalue('update_delivery_country');

		$update_customer_telephone=$FREQUEST->postvalue('update_customer_telephone');
		$update_customer_second_telephone_number=$FREQUEST->postvalue('update_customer_second_telephone_number');
		$update_customer_fax=$FREQUEST->postvalue('update_customer_fax');
		$update_customer_second_email_address=$FREQUEST->postvalue('update_customer_second_email_address');
		$update_customer_email_address=$FREQUEST->postvalue('update_customer_email_address');

		$update_billing_name=$FREQUEST->postvalue('update_billing_name');
		$update_billing_company=$FREQUEST->postvalue('update_billing_company');
		$update_billing_street_address=$FREQUEST->postvalue('update_billing_street_address');
		$update_billing_suburb=$FREQUEST->postvalue('update_billing_suburb');
		$update_billing_city=$FREQUEST->postvalue('update_billing_city');
		$update_billing_state=$FREQUEST->postvalue('update_billing_state1');
		$update_billing_postcode=$FREQUEST->postvalue('update_billing_postcode');
		$update_billing_country=$FREQUEST->postvalue('update_billing_country');


		if(is_numeric($update_customer_country)) $update_customer_country=tep_get_country_name($update_customer_country);
		else $update_customer_country =$FREQUEST->postvalue('update_customer_country');
		if(is_numeric($update_billing_country)) $update_billing_country=tep_get_country_name($update_billing_country);
		else $update_billing_country =$FREQUEST->postvalue('update_billing_country');
		if(is_numeric($update_delivery_country)) $update_delivery_country=tep_get_country_name($update_delivery_country);
		else $update_delivery_country =$FREQUEST->postvalue('update_delivery_country');

		if($FREQUEST->postvalue('update_billing_state')!='')
			$update_billing_state =$FREQUEST->postvalue('update_billing_state');
		if($FREQUEST->postvalue('update_delivery_state')!='')
			$update_delivery_state =$FREQUEST->postvalue('update_delivery_state');
		if($FREQUEST->postvalue('update_customer_state1')!='')
			$update_customer_state =$FREQUEST->postvalue('update_customer_state1');
		// update billing and shipping details
		  $UpdateOrders = "update " . TABLE_CUSTOMERS_BASKET_ADDRESS . " set ";
		  if($SeparateBillingFields)
	   	   {
		      $UpdateOrders .= "billing_name = '" . tep_db_input(stripslashes($update_billing_name)) . "',
			billing_company = '" . tep_db_input(stripslashes($update_billing_company)) . "',
			billing_street_address = '" . tep_db_input(stripslashes($update_billing_street_address)) . "',
			billing_suburb = '" . tep_db_input(stripslashes($update_billing_suburb)) . "',
			billing_city = '" . tep_db_input(stripslashes($update_billing_city)) . "',
			billing_state = '" . tep_db_input(stripslashes($update_billing_state)) . "',
			billing_postcode = '" . tep_db_input(stripslashes($update_billing_postcode)) . "',
			billing_country = '" . tep_db_input(stripslashes($update_billing_country)) . "',";
		   }
		     $UpdateOrders .= " delivery_name = '" . tep_db_input(stripslashes($update_delivery_name)) . "',
			delivery_company = '" . tep_db_input(stripslashes($update_delivery_company)) . "',
			delivery_street_address = '" . tep_db_input(stripslashes($update_delivery_street_address)) . "',
			delivery_suburb = '" . tep_db_input(stripslashes($update_delivery_suburb)) . "',
			delivery_city = '" . tep_db_input(stripslashes($update_delivery_city)) . "',
			delivery_state = '" . tep_db_input(stripslashes($update_delivery_state)) . "',
			delivery_postcode = '" . tep_db_input(stripslashes($update_delivery_postcode)) . "',
			delivery_country = '" . tep_db_input(stripslashes($update_delivery_country)) . "',
			customers_telephone = '" . tep_db_input(stripslashes($update_customer_telephone)) . "',
		    customers_second_telephone = '" . tep_db_input(stripslashes($update_customer_second_telephone_number)) . "',
			customers_fax ='" . tep_db_input(stripslashes($update_customer_fax)) . "',
		    customers_second_email_address = '" . tep_db_input(stripslashes($update_customer_second_email_address)) . "',
			customers_email_address = '" . tep_db_input(stripslashes($update_customer_email_address)) . "'";
        if ($UpdateOrders!=''){
 		   $UpdateOrders .= " where customers_id = '" . tep_db_input($FSESSION->customer_id) . "';";
   		   tep_db_query($UpdateOrders);
		}
		if($FSESSION->is_shipping=='1')
		{
			$jsData->VARS['storePage']['cOrder_step']='4';
			$this->doShipping();
		}
		else
		{
			$jsData->VARS['storePage']['cOrder_step']='5';
			$jsData->VARS['storePage']['shipping']='0';
			$this->doPayment();
		}
			$jsData->VARS['storePage']['sendto']=$FSESSION->sendto;
			$jsData->VARS['storePage']['billto']=$FSESSION->billto;
	}
  function doShipping()
  {
	global $FREQUEST,$FSESSION,$cart,$order,$shipping_modules,$payment_modules,$currencies,$jsData;
	require('includes/classes/http_client.php');
	if (tep_get_configuration_key_value('MODULE_SHIPPING_FREESHIPPER_STATUS') and $cart->show_weight()!=0) {
	   $FSESSION->remove('shipping');
	}
	$valid_to_checkout= true;
  	$cart->get_products(true);
  	$FSESSION->set('cartID', $cart->cartID);
	/*if (NO_CHECKOUT_ZERO_PRICE=="1" && $cart->is_free_checkout()){
		 echo "redirect_page||" . tep_href_link('checkout_process_new.php', '', 'SSL');
		 exit;
	}*/
	//require(DIR_WS_CLASSES . 'shipping.php');
  	//$shipping_modules = new shipping;
	if (($order->content_type == 'virtual') || ($order->content_type == 'virtual_weight') || $FSESSION->is_shipping==0 || tep_count_shipping_modules()<=0) {
		$FSESSION->set("shipping",false);
		$shipping=false;
		$FSESSION->set('sendto',false);
		$sendto=false;
		$jsData->VARS['storePage']['cOrder_step']='5';
		$jsData->VARS['storePage']['is_shipping']='0';
		$this->doPayment();
		return;
  }
  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();
  if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
    $pass = false;
    switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
      case 'national':
        if ($order->delivery['country_id'] == STORE_COUNTRY) {
          $pass = true;
        }
        break;
      case 'international':
        if ($order->delivery['country_id'] != STORE_COUNTRY) {
          $pass = true;
        }
        break;
      case 'both':
        $pass = true;
        break;
    }
    $free_shipping = false;
    if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
      $free_shipping = true;
      include(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/order_total/ot_shipping.php');
    }
  } else {
    $free_shipping = false;
  }
  $quotes = $shipping_modules->quote();
  if ( !$FSESSION->is_registered('shipping') || ( $FSESSION->is_registered('shipping') && ($shipping == false) && (tep_count_shipping_modules() > 1) ) ) $shipping = $shipping_modules->cheapest();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="1">
	<tr>
	<td  valign="top" width="100%">
<?php echo tep_draw_form('checkout_shipping', FILENAME_CHECKOUT_SHIPPING) . tep_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="attributes-odd">
<?php
  if (tep_count_shipping_modules() > 0) {
?><?php tep_content_title_top( TABLE_HEADING_SHIPPING_METHOD); ?>
          <tr class="main">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
    if (sizeof($quotes) > 1 && sizeof($quotes[0]) > 1) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><?php echo TEXT_CHOOSE_SHIPPING_METHOD; ?></td>
                <td class="main" width="50%" valign="top" align="right"><?php echo '<b>' . TITLE_PLEASE_SELECT . '</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
    } elseif ($free_shipping == false) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="100%" colspan="2"><?php echo TEXT_ENTER_SHIPPING_INFORMATION; ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
    }
    if ($free_shipping == true) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td colspan="2" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><b><?php echo FREE_SHIPPING_TITLE; ?></b>&nbsp;<?php echo $quotes[$i]['icon']; ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, 0)">
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" width="100%"><?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . tep_draw_hidden_field('shipping', 'free_free'); ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
    } else {
      $radio_buttons = 0;
      for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><b><?php echo $quotes[$i]['module']; ?></b>&nbsp;<?php if (isset($quotes[$i]['icon']) && tep_not_null($quotes[$i]['icon'])) { echo $quotes[$i]['icon']; } ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
        if (isset($quotes[$i]['error'])) {
?>
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><?php echo $quotes[$i]['error']; ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
        } else {
          for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
// set the radio button to be checked if it is the method chosen
            $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $shipping['id']) ? true : false);

            if ( ($checked == true) || ($n == 1 && $n2 == 1) ) {
              echo '                  <tr id="defaultSelected"  onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" >' . "\n";
            } else {
              echo '                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)"  >' . "\n";
            }
?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
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
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
            $radio_buttons++;
          }
        }
?>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
      }
    }
?>
            </table></td>
          </tr><?php tep_content_title_bottom();?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
 	  <tr>
         <td class="main">
		        <table border="0" width="100%" cellspacing="1" cellpadding="2" class="attributes-odd">
				<tr><td class="main">
					<b><?php echo TABLE_HEADING_COMMENTS .tep_draw_checkbox_field('comments', '0',($comments)?true:false,'','id="comments" onclick=javascript:showComment(this)'); ?></a></b></td>
			      </tr>
		      <tr id="show_comments" style="display:none;">
        		<td><table border="0" width="100%" cellspacing="1" cellpadding="2" >
	          <tr class="main">
    	        <td>
        	      <table border="0" width="100%" cellspacing="0" cellpadding="2">
               <tr>
                 <td><?php echo '&nbsp;&nbsp;&nbsp;'.tep_draw_textarea_field('comments', 'soft', '90', '5','','id="comments"'); ?></td>
               </tr>
              </table>
            </td>
          </tr>
        </table></td>
      </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        	<td>
			<table border="0" width="100%" cellspacing="1" cellpadding="2">
          		<tr class="infoBoxContents">
            		<td>
					<table border="0" width="100%" cellspacing="0" cellpadding="2">
					  	<tr>
							<td width="50%" class="main" align="right" nowrap><?php echo '<a href="javascript:void(0);" onclick="javascript:do_page_fetch(\'submit_shipping_details\');">' . tep_image_button('button_continue.gif', IMAGE_CONTINUE,'name="button_confirm4" id="button_confirm4"') . '</a>'; ?></td>
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
	  <input type="hidden" name="free_shipping" value="<?php echo $free_shipping;?>" />
</table></form>
<?php
 }
 function doShippingSubmit()
 {
	global $FREQUEST,$FSESSION,$cart,$order,$shipping_modules,$currencies,$jsData;;
	 $FSESSION->set('comments',$FREQUEST->postvalue('comments'));
	 $free_shipping=$FREQUEST->postvalue('free_shipping');
     if ( (tep_count_shipping_modules() > 0)  ) {
	   $FSESSION->set('shipping',$FREQUEST->postvalue('shipping'));
       if ( ($FSESSION->shipping!='') && (strpos($FSESSION->shipping, '_') )) {
        list($module, $method) = explode('_', $FSESSION->shipping);
       // if ( is_object($GLOBALS[$$module]) || ($FSESSION->shipping == 'free_free') ) {
          if ($FSESSION->shipping == 'free_free') {
            $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
            $quote[0]['methods'][0]['cost'] = '0';
          } else {
            $quote = $shipping_modules->quote($method, $module);
          }
          if (isset($quote['error'])) {
            $FSESSION->remove('shipping');
          } else {
            if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
              $shipping = array('id' => $FSESSION->get('shipping'),
                                'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
                                'cost' => $quote[0]['methods'][0]['cost']);
										 $FSESSION->set('shipping',$shipping);

      			 $this->doPayment();
	 			 $jsData->VARS['storePage']['cOrder_step']='5';
				 $jsData->VARS['storePage']['shipping']=$FSESSION->shipping;
				 return;
            }
          }
        //} else {
          $FSESSION->remove('shipping');
        //}
      }
	  else
	  {
	  	$this->doShipping();
	  }
    } else {
       $shipping = false;
	   $this->doPayment();
	   $jsData->VARS['storePage']['cOrder_step']='5';
	   $jsData->VARS['storePage']['shipping']=$FSESSION->shipping;
       if(!$free_shipping)
	       $jsData->VARS['storePage']['is_shipping']='0';
    }
  }
function doPayment()
{
 global $FREQUEST,$FSESSION,$cart,$order,$currencies,$payment_modules,$order_total_modules,$shipping_modules;
 //require(DIR_WS_CLASSES . 'order.php');
 $manual_option=$FSESSION->get('manual_option');
 $FSESSION->set('payment_page',"default");
 if($FSESSION->is_registered('comments'))
  	$FSESSION->remove('comments');
$comments =$FREQUEST->postvalue('comments');
$FSESSION->set('comments',$comments);
$payment=$FSESSION->payment;
	$error_text="";
	$payment_error =$FREQUEST->getvalue('payment_error');
	if (($payment_error!='') && is_object($GLOBALS[$payment_error]) && ($error = $GLOBALS[$payment_error]->get_error())) {
		$error_text=$error["error"];
	}
    if($error_text=='') {
        $error_text=$FREQUEST->getvalue('error_message');
    }
	//tep_session_register('comments');
 $order_timestamp=time();
 $referenceID= substr(strtolower($order->customer['firstname']),0,3) . substr(strtolower($order->customer['lastname']),0,3) . $order_timestamp ;
$FSESSION->set('referenceID',$referenceID);
// if (!$FSESSION->is_registered('referenceID')) tep_session_register('referenceID');
 $total_weight = $cart->show_weight();
 $total_count = $cart->count_contents();
 $total_count = $cart->count_contents_virtual(); //ICW ADDED FOR CREDIT CLASS SYSTEM
// load all enabled payment modules
  if($FSESSION->is_registered('order_id') && $order_id!="" && $order_status==2)
  {
  	$previous_total=get_order_total("ot_total",$order_id);
  	if($previous_total > $order->info['overall_total'])
	{?>
	<table border="0" width="90%" cellspacing="2" cellpadding="1">
      	<tr>
       		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
     	</tr>
		<tr class="attributes-odd">
			<td colspan="5">
				<?php //tep_content_title_top(TABLE_HEADING_PAYMENT_METHOD)?>
				<table border="1" width="100%" cellspacing="0" cellpadding="2">
					<tr><td class="main"><?php echo sprintf(TEXT_REFUND_ALERT,$currencies->format($previous_total),$currencies->format($order->info['overall_total']));?></td></tr>
					<!--<tr> <?php //javascript:do_page_fetch(\'process_order\'); ?>
						<td class="main" align="right" nowrap><?php echo '<a href="javascript:show_step_number(2);">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;&nbsp;<a href="javascript: refund_amount();">' . tep_image_button('button_continue.gif', IMAGE_CONTINUE,'') . '</a>&nbsp;'; ?></td>
					</tr> -->
				</table>
				<?php //tep_content_title_bottom();?>
		  	</td>
		 </tr>
		</table>
	<?php  //tep_session_register("payment_refund");
		$FSESSION->set('payment_refund',true);
		//exit;
	  }
  }
?>
	<table border="0" width="100%" cellspacing="2" cellpadding="1">
		<tr>
			<td valign="top" width="100%">
			<?php echo tep_draw_form('edit_order', FILENAME_CHECKOUT_CONFIRM_NEW,'', 'post'); ?>
			<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
			<?php $serverDate = date('Y-m-d H:i:s',getServerDate(false)); ?>
			<tr id="show_payment_error" <?php if($error_text=="") echo 'style="display:none"';?>>
				<td>
				<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBoxNotice">
					<tr class="infoBoxNoticeContents">
					<td>
						<table border="0" width="100%" cellspacing="0" cellpadding="2">
						<tr>
							<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
							<td class="main" width="100%" valign="top" id="payment_error_text"><?php echo $error_text; ?></td>
							<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
						</tr>
					</table>
				</td>
			 </tr>
			</table>
			</td>
		</tr>
			<?php
			// while editing the order the customer has to purchase anyone order or plz purchase error will display
			   $order_id=$FSESSION->order_id;
               $ostate=$FSESSION->order_status;
			  $tot=$order->info['total'] - $order->info['shipping_cost'] - $order->info['modify_price_prefix'];
		      if($order_id>0 && $ostate==2 && $tot==0 ){?>
				<tr>
        	<td>
				<table border="0" width="100%" cellspacing="0" cellpadding="5">
          			<tr>
					<td  valign="middle" class="main"><font color="#FF0033"><b><?php echo TEXT_ERROR;?></b></font>
          			 <?php echo TEXT_NO_CHANGE; ?>&nbsp;</td>
        		  </tr>
      		  </table>
			</td>
     	</tr>
		<?php } ?>
	    <tr>
			<td>
	  		<table border="0" width="100%" cellspacing="0" cellpadding="2" class="dataTablerowover">
	  			<tr class="attributes-odd">
				<td class="main"><b><?php echo SHOPPING_CART; ?></b></td>
				</tr>
				<tr class="attributes-odd"><td height="5"></td></tr>
					<?php
					$dis_time_format="";
					if(defined('TIME_FORMAT'))
						$dis_time_format=TIME_FORMAT;
						for ($i=0, $n=count($order->products); $i<$n; $i++) {
							switch($order->products[$i]['element_type'])
							{
								case 'P':
									$index=0;
									break;
								case 'S':
									$index=2;
									break;
								case 'E':
									$index=1;
									break;
								case 'V':
									$index=3;
									break;
							}
							if (STOCK_CHECK == 'true' && $order->products[$i]['element_type']=="P") {
     						 $out= tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
  							  }
							$items[$index].='<tr>' . "\n" .
							'            <td class="main_item" width="70%"  valign="top"><b>' . $order->products[$i]['name'] .$out . '</b>';

							if ($order->products[$i]['element_type']=="V"){
								if($order->products[$i]['others']['end_date'] && $order->products[$i]['others']['end_time']){
									$str_time=strtotime($order->products[$i]['others']['end_date'] .' '.$order->products[$i]['others']['end_time'])+60;
									$order->products[$i]['others']['end_date']=date('Y-m-d',$str_time);
									$order->products[$i]['others']['end_time']=date('H:i:s',$str_time);
								}
								$stime="";
								$etime="";
								if($order->products[$i]['others']['start_time']) {
									$stime=date("h:i A",strtotime($order->products[$i]['others']['start_time']));
									if($dis_time_format!="") {
										if($dis_time_format=='24')
											$stime=date("H:i",strtotime($order->products[$i]['others']['start_time']));
									}
								}
								if($order->products[$i]['others']['end_time']) {
									$etime=date("h:i A",strtotime($order->products[$i]['others']['end_time']));
									if($dis_time_format!="") {
										if($dis_time_format=='24')
										$etime=date("H:i",strtotime($order->products[$i]['others']['end_time']));
									}
								}
								if($order->products[$i]['others']['resource_id']!='') $resource_costs=tep_add_tax(tep_get_resource('resource_costs',$order->products[$i]['others']['resource_id'],$FSESSION->languages_id),$order->products[$i]['tax']);
								$items[$index].='<br><small><i>&nbsp;	- '.TEXT_START_DATE.' : '. format_date($order->products[$i]['others']['start_date']) . '&nbsp;' . $stime . ' <br>&nbsp; - ' . TEXT_END_DATE .' : ' . format_date($order->products[$i]['others']['end_date']). '&nbsp;' . $etime . ' '.(($order->products[$i]['others']['resource_id'])? '<br>&nbsp; - '.tep_get_resource_name($order->products[$i]['others']['resource_id'],'resource_name').'('.$currencies->format($resource_costs).')':'').'</i></small>';
							}

							if (isset($order->products[$i]['attributes']) && is_array($order->products[$i]['attributes'])) {
								reset($order->products[$i]['attributes']);
								$attribute_id="";
								for($k=0,$att_max=sizeof($order->products[$i]['attributes']);$k<$att_max;$k++) {
									$att_row=$order->products[$i]['attributes'][$k];
									if($attribute_id!="")
										$attribute_id.="-";
									$attribute_id.=$att_row['option_id'] . "{" . $att_row['value_id'] . "}";
								}
								for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
									if($order->products[$i]['attributes'][$j]['option'] && $order->products[$i]['attributes'][$j]['value'])  $items[$index].= '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '('.$currencies->format(tep_add_tax($order->products[$i]['attributes'][$j]['price'],$order->products[$i]['tax'])).')</i></small></nobr>';
								}
								if (STOCK_CHECK == 'true' && $order->products[$i]['element_type']=="P") {
			 echo tep_check_attribute_stock($order->products[$i]['id'],$attribute_id,$order->products[$i]['quantity']);
		  }
							}
							$disp_total+=$order->products[$i]['final_price'];

							if ($order->products[$i]['element_type']=="P"){
								if ($order->products[$i]['discount_whole_text']!=''){
									$items[$index].='<br>' . $order->products[$i]['discount_whole_text'];
								}
							}

							$items[$index].='</td>' . "\n" . '<td class="main_item" align="center" valign="top" width="10%">' . $order->products[$i]['qty'] .' </td>' . "\n" ;
							$items[$index].= '<td class="main_item" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</td>' . "\n" .
							'          </tr>' . "\n";
						}
						$val=$currencies->format($cart->show_total());
						if($FSESSION->get('sign')=='-')
						$rest_mp = substr($FSESSION->get('modify_price_prefix'), 1);
						else
						$rest_mp = $FSESSION->get('modify_price_prefix');

						$items[4]='<tr><td colspan=3 ></td></tr><tr><td></td><td align=right class="main_item"><b>' . ORDERED_TOTAL . '</b>&nbsp;' . $currencies->format($cart->show_net_total(true)) . '</td></tr>';

						if($manual_option=='Y')
						$mp='<tr><td colspan=3 ></td></tr><tr><td></td><td align=right class="main_item"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$FSESSION->get('purpose').'('. $FSESSION->get('sign').')</b>&nbsp;'.$currencies->format($rest_mp).'</td></tr>';
						$items[5]=$mp.'<tr><td colspan=3 ></td></tr><tr><td></td><td align=right class="main_item"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. TEXT_TOTAL . '</b>&nbsp;' . $currencies->format($cart->show_net_total()) . '</td></tr>';
						//$items[5]='<tr><td colspan=3 ></td></tr><tr><td></td><td align=right class="main_item"><b>' . "Updated Total" . '</b>&nbsp;' . $currencies->format($cart->show_total()) . '</td></tr>';
						for($j=0;$j<=5;$j++)
						{
							if(isset($items[$j]))
							{
							?>
							<tr class="attributes-odd">
								<td class="main" colspan="3"><?php echo '<b>' . tep_get_title($j) . '</b>'; ?></td>
							</tr>
							<tr class="attributes-odd">
								<td>
									<table width="95%" align=right cellpadding="5">
							<?php
								echo $items[$j];?>
									</table>
								</td>
							</tr>
							<?php
							}
						}

					?>
		</table>
	 </td>
	</tr>
	<tr>
	<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>
	<tr>
	 <td>
		<table border="0" width="100%" cellspacing="0" cellpadding="2" >
		<tr class="attributes-odd">
		 <td class="main"><b><?php echo TABLE_HEADING_PAYMENT_METHOD;?></b></td>
		</tr>
			<tr class="attributes-odd">
				<td>
					<table border="0" width="100%" cellspacing="0" cellpadding="2">
						<?php

						$selection = $payment_modules->selection();
					 	$flag='B';
						$radio_buttons = 0;
					  	$m = count($selection[0]['fields']);
					  	$cnt = 0;
					  	for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
						?>
					  <tr>
						<td colspan="2">
							<table border="0" width="100%" cellspacing="0" cellpadding="2">
							<?php
							//if($show==true){   //echo $selection[$i]['id'];
							if (($selection[$i]['id'] == $payment) || ($n == 1))
							  echo '<tr id="defaultSelected"  onmouseover="rowOverEffect2(this)" onmouseout="rowOutEffect2(this)" onclick="selectRowEffect2(this, ' . $radio_buttons . ',\'', $selection[$i]['id'] . '\')">' . "\n";
						  else
							  echo '<tr id="defaultunSelected" class="moduleRow" onmouseover="rowOverEffect2(this)" onmouseout="rowOutEffect2(this)" onclick="selectRowEffect2(this, ' . $radio_buttons . ',\'', $selection[$i]['id'] . '\')">' . "\n";

							?>
								<td width="10" nowrap="nowrap"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
								<td class="main" colspan="3" width="400" nowrap="nowrap"><b><?php echo $selection[$i]['module']; ?></b></td>
								<td class="main" align="left" width="250">
								<?php $single_payment=false;
									if (sizeof($selection) > 1)
									  echo tep_draw_radio_field('payment', $selection[$i]['id'],(($i==0 || $selection[$i]['id'] == $payment)?true:''));
									else {
									  $single_payment=true;
									  echo tep_draw_hidden_field('payment', $selection[$i]['id']);
									}
								?></td>
								<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
							</tr>
							<?php if (isset($selection[$i]['error'])) {	?>
								  <tr>
									<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
									<td class="main" colspan="4"><?php echo $selection[$i]['error']; ?></td>
									<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
								  </tr>
							<?php
								} elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
									 ?>
						   	 <tr <?php if(!$single_payment){ echo " id='" . $selection[$i]['id'] . "'"; echo (($payment!=$selection[$i]['id'])?'style="display:none"':'');}?>>
								<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
								<td colspan="4"><table width="60%" border="0" cellspacing="0" cellpadding="2" align="left">
							<?php for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {?>
								<tr>
									<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
									<td class="main" nowrap><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
									<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
									<td class="main" nowrap><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
									<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
								  </tr>
							<?php   } ?>
							</table>
							</td></tr>
							<?php }?>
				</table>
						</td>
						</tr>
				<?php
					$radio_buttons++;
				  }
				?>
            </table>
			</td>
          </tr>
		   </table>
		</td>
      </tr>
		<tr>
			<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
		</tr>
<?php
$serverDate=getserverDate();
$coupon_check_qry = tep_db_query("select * from " . TABLE_COUPONS . " where ". tep_db_input($serverDate) ." <= coupon_expire_date and ". tep_db_input($serverDate) ." >= coupon_start_date and coupon_active='Y' ");
	if(!tep_db_num_rows($coupon_check_qry)){
	//require(DIR_WS_CLASSES . 'order_total.php');//ICW ADDED FOR CREDIT CLASS SYSTEM
  	//$order_total_modules = new order_total;//ICW ADDED FOR CREDIT CLASS SYSTEM
?>
	<tr>
		<td>
		<table cellspacing="0" cellpadding="0" width="100%" border="0" class="attributes-odd">

	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif','100%','5')?></td>
	</tr>
	<tr>
	<tr class="attributes-odd">
		 <td class="main"><b><?php echo TEXT_DISCOUNT_COUPONS; ?></b></td>
		</tr>
	<td class="main" >
	<table border="0" width="60%" cellspacing="1" cellpadding="2" >
		<tr><td id="error_span"></td></tr>
		<tr id="gv_chk">
			<td class="main" height="20"><?php //echo tep_draw_separator('pixel_trans.gif',40,1); ?>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TEXT_REDEEM; ?> </b> <?php echo tep_draw_checkbox_field('coupon','1',false,'','onclick=javascript:hide_credit()');?></td>
		</tr>
		<tr>
			<td id="credit_result" class="smalltext" align="center" style="display:none;height:100;width:100%;">
			</td>
		</tr>
		<tr id="credit" style="display:none">
			<td class="main" colspan="8">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr height="8"><td></td></tr>
				<tr>
					<td class="main" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo DISCOUNT_COUPONS; ?></td>
				</tr>
				<tr>
					<td colspan="8">
					<table>
						<?php echo $order_total_modules->credit_selection(); ?>
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
	 <?php }
	 //exit;?>
		</table>

	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	 <tr>
        <td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
   <tr>
	  <td width="100%">
	  <table width="100%">
	        <tr>
				<?php
			// while editing the order the customer has to purchase anyone order or continue button will not display
				 $order_id=$FSESSION->order_id;
                       $ostate=$FSESSION->order_status;
					   $tot=$order->info['total'] - $order->info['shipping_cost'] - $order->info['modify_price_prefix'];
		               if($order_id>0 && $ostate==2 && $tot==0){?>
					   <?php } else { ?>
        		<td class="main" align="right" width="50%"><?php echo '<a href="javascript:void(0);" onclick="javascript:do_page_fetch(\'submit_payment_details\');">' . tep_image_button('button_continue.gif', IMAGE_CONTINUE,'name="button_confirm5" id="button_confirm5"') . '</a>'; ?></td>
				<?php  } ?>
			</tr>
		</table>
      </td>
      </tr>
	  </table>
	</form>
<?php }
function doPaymentSubmit()
{
  global $FREQUEST,$FSESSION,$jsData,$payment_modules,$shipping_modules,$order_total_modules,$order,$cart,$payment,$GLOBALS;
  $do_action =$FREQUEST->getvalue('do_action','string','');

 if($do_action =='get_counpon_details'){

  	if ($credit_covers) $FSESSION->payment=''; //ICW added for CREDIT CLASS
  	//ICW ADDED FOR CREDIT CLASS SYSTEM
  	$payment_modules->update_status();
  	//ICW ADDED FOR CREDIT CLASS SYSTEM
  	//ICW ADDED FOR CREDIT CLASS SYSTEM
 	$order_total_modules->collect_posts();
	//echo $order->info['total'].'bb';
  	//ICW ADDED FOR CREDIT CLASS SYSTEM
 	$order_total_modules->process();
	//echo $order->info['total'].'cc';
  	if(sizeof($GLOBALS['ot_coupon'])){
  		$coupon=$GLOBALS['ot_coupon'];
  		$error="";
  		$error=$GLOBALS['coupon_error'];
		//print_r($FSESSION);
  		if($coupon->code=='ot_coupon' && $error=='' && $coupon->output[0]['text']){
   			echo 'coupon_details||<table border="0" width="50%" cellspacing="3" cellpadding="3" class="attributes-odd" align="left">' .
   					'<tr><td></td>' .
   					'   <td class="main">'.TEXT_REDEEM_CODE. (($FSESSION->get('email_redeem_code'))?$FSESSION->get('email_redeem_code'):$coupon->coupon_code).'</td>' .
   					'   <td class="main" nowrap="nowrap">'.TEXT_VALUE. $coupon->output[0]['text'].'</td>' .
   					'</tr>' .
   					'<tr><td></td>' .
   					'   <td class="main" nowrap="nowrap">'.TEXT_VALID_UTIL. $coupon->expire_date.'</td>' .
   					'   <td class="main">'.(($coupon->uses_per_user!=0)?TEXT_USES_REMAINING .$coupon->uses_per_user:'&nbsp;').'</td></tr>' .
   					'<tr><td colspan="3" align="right">'.tep_image_button('button_dont_redeem.gif','','style="cursor:pointer;cursor:hand" onclick="javascript:hide_credit(1);"').'</td></tr>'.
   					'</table>';
	    } else {
	    	//if(!$error) $error=ERROR_NO_INVALID_REDEEM_COUPON;
	    	echo 'coupon_details||<table border="0" width="90%" align="center" cellspacing="0" cellpadding="0"><tr align="center"><td align="left" class="smalltext"><span class="errortext">'.$error.'</span></td></tr></table>';
	    	//$order_total_modules->credit_selection();
	    }
  	}
  	exit;
 }
 //for coupon

	$coupon =$FREQUEST->postvalue('coupon');
	if($coupon!='')
			$FSESSION->set('coupon',true);
  $gv_redeem_code =$FREQUEST->postvalue('gv_redeem_code');
  if($gv_redeem_code!=''){
	$FSESSION->set('gv_redeem_code',$gv_redeem_code);
  }

  $payment=$FREQUEST->postvalue('payment');
  $FSESSION->set('payment',$payment);
  $payment_modules->selected_module = $payment;
  //$payment_mod= new payment($payment);
  $payment_modules->update_status();
  $order_total_modules->collect_posts();
  $order_total_modules->pre_confirmation_check();
  if ( (is_array($payment_modules->modules)) && (sizeof($payment_modules->modules) > 1) && (!is_object($GLOBALS[$payment]) ) && (!$credit_covers) ) {
    echo "payment_error||" . ERROR_NO_PAYMENT_MODULE_SELECTED;
	return;
  }
  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }
// load the selected shipping module
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
     if ($order->products[$i]['element_type']=='P'){
      if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
     }
	 if(isset($order->products[$i]['attributes'])){
	 	$attribute_stock_check="";
	reset($order->products[$i]['attributes']);
	while (list($option, $value) = each($order->products[$i]['attributes'])) {
		$attrb_ids.=$value["option_id"] . "{".$value["value_id"]."}-";
	}
	$attrb_ids=substr($attrb_ids,0,-1);
	reset($order->products[$i]['attributes']);
			$attribute_stock_check=tep_check_attribute_stock($order->products[$i]['id'],$attrb_ids,$order->products[$i]['qty']);
			if(tep_not_null($attribute_stock_check)){
				$any_out_of_stock=true;
			}
		}
    }
    if($FSESSION->is_registered('coupon_exist')) $FSESSION->remove('coupon_exist');
	if($FSESSION->is_registered('coupon_exist_equal_amount')) $FSESSION->remove('coupon_exist_equal_amount');
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
	  $jsData->VARS['storePage']['cOrder_step']='2';
	  $this->doShopping();
	  return;
    }
  }
  $jsData->VARS['storePage']['cOrder_step']='6';
  $this->doConfirm();
}
function doConfirm()
{
	global $FREQUEST,$FSESSION,$order,$cart,$payment_modules,$order_total_modules,$currencies,$currency;
	$payment=$FSESSION->payment;
?>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
			<td  valign="top" width="100%">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
				<td><table border="0" width="100%" cellspacing="0" cellpadding="0" class="attributes-odd">
				  <tr class="infoBoxContents">
<?php
  if ($FSESSION->sendto != false && $order->info['shipping_method']!="") {
?>
            <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_DELIVERY_ADDRESS . '</b>'?> <a href="javascript:void(0);" onclick="javascript:showPanelContent({id:'BILLINGSHIPPING',className:'boxRow','type':'cOrder'});"><span class="orderEdit">(<?php echo TEXT_EDIT ?>)</span></a></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>');?></td>
              </tr>

<?php
    if ($order->info['shipping_method'] && $order->info['shipping_method']!="") {
?>
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_SHIPPING_METHOD . '</b>'?> <a href="javascript:void(0);" onclick="javascript:showPanelContent({id:'SHIPPING',className:'boxRow','type':'cOrder'});"><span class="orderEdit">(<?php echo TEXT_EDIT ?>)</span></a></td>
              </tr>
              <tr>
                <td class="main"><?php echo $order->info['shipping_method']; ?></td>
              </tr>
<?php
    }
?>
            </table></td>
<?php
  }
?>
            <td width="<?php echo (($FSESSION->sendto != false) ? '70%' : '100%'); ?>" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  //print_r($order->info);
  if (sizeof($order->info['tax_groups']) > 0) {
?>
                  <tr>
                    <td class="main" colspan="2"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="javascript:void(0);" onclick="javascript:showPanelContent({id:\'SHOPPINGCART\',className:\'boxRow\',\'type\':\'cOrder\'});"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
                    <td class="smallText" align="right"><b><?php echo HEADING_TAX; ?></b></td>
                    <td class="smallText" align="right"><b><?php echo HEADING_TOTAL; ?></b></td>
                  </tr>
<?php
  } else {
?>
                  <tr>
                    <td class="main" colspan="2"><?php echo '<b>' . HEADING_PRODUCTS . '</b>'?> <a href="javascript:void(0);" onclick="javascript:showPanelContent({id:'SHOPPINGCART',className:'boxRow','type':'cOrder'});"><span class="orderEdit">(<?php echo TEXT_EDIT ?>)</span></a></td>
                  </tr>
<?php
  }
  	$dis_time_format="";
	if(defined('TIME_FORMAT'))
		$dis_time_format=TIME_FORMAT;
  for ($i=0, $n=count($order->products); $i<$n; $i++) {

    echo '          <tr>' . "\n" .
         '            <td class="main" align="right" valign="top" width="30">' . $order->products[$i]['qty'] . '&nbsp;</td>' . "\n" .
         '            <td class="main" width="80%"  valign="top"><b>' . $order->products[$i]['name'] .'</b>';
	 if ($order->products[$i]['element_type']=="V"){
		if($order->products[$i]['others']['end_date'] && $order->products[$i]['others']['end_time']){
			$str_time=strtotime($order->products[$i]['others']['end_date'] .' '.$order->products[$i]['others']['end_time'])+60;
			$order->products[$i]['others']['end_date']=date('Y-m-d',$str_time);
			$order->products[$i]['others']['end_time']=date('H:i:s',$str_time);
		}
				$stime="";
				$etime="";
				if($order->products[$i]['others']['start_time']) {
					$stime=date("h:i A",strtotime($order->products[$i]['others']['start_time']));
					if($dis_time_format!="") {
						if($dis_time_format=='24')
						$stime=date("H:i",strtotime($order->products[$i]['others']['start_time']));
					}
				}
				if($order->products[$i]['others']['end_time']) {
					$etime=date("h:i A",strtotime($order->products[$i]['others']['end_time']));
					if($dis_time_format!="") {
						if($dis_time_format=='24')
						$etime=date("H:i",strtotime($order->products[$i]['others']['end_time']));
					}
				}

		echo '<br><small>&nbsp;	- '.TEXT_START_DATE.' : '. format_date($order->products[$i]['others']['start_date']) . '&nbsp;' . $stime . ' <br>&nbsp; - ' . TEXT_END_DATE .' : ' . format_date($order->products[$i]['others']['end_date']). '&nbsp;' . $etime . ' '.(($order->products[$i]['others']['resource_id'])? '<br>&nbsp; - '.tep_get_resource_name($order->products[$i]['others']['resource_id'],'resource_name').'('.$currencies->format($order->products[$i]['others']['resource_costs']).')':'').'</small>';
	 }
    if (STOCK_CHECK == 'true' && $order->products[$i]['element_type']=="P") {
      echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
    }

   if (isset($order->products[$i]['attributes']) && is_array($order->products[$i]['attributes'])) {
			reset($order->products[$i]['attributes']);
			$attribute_id="";
			for($k=0,$att_max=sizeof($order->products[$i]['attributes']);$k<$att_max;$k++) {
				$att_row=$order->products[$i]['attributes'][$k];
				if($attribute_id!="")
					$attribute_id.="-";
				$attribute_id.=$att_row['option_id'] . "{" . $att_row['value_id'] . "}";
			}
	      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
	      //  if($order->products[$i]['attributes'][$j]['option'] && $order->products[$i]['attributes'][$j]['value'])  echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '('.$currencies->format(tep_add_tax($order->products[$i]['attributes'][$j]['price'],$order->products[$i]['tax'])).')</i></small></nobr>';
	        if($order->products[$i]['attributes'][$j]['option'] && $order->products[$i]['attributes'][$j]['value'])  echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '('.$currencies->format($order->products[$i]['attributes'][$j]['price']).')</i></small></nobr>'; // Merging
			// $order->products[$i]['final_price']+=$order->products[$i]['attributes'][$j]['price'];
		   }
	      if (STOCK_CHECK == 'true' && $order->products[$i]['element_type']=="P") {
			 echo tep_check_attribute_stock($order->products[$i]['id'],$attribute_id,$order->products[$i]['quantity']);
		  }
	}
	if ($order->products[$i]['element_type']=='P'){
		if ($order->products[$i]['discount_whole_text']!=''){
			echo '<br>' . $order->products[$i]['discount_whole_text'];
		}
	}
	
	if( ($order->products[$i]['element_type']=='E') && ($order->products[$i]['qty']>1) ){
	  $attendee_names .= $cart->get_attendee_names(",");
 	  if($attendee_names!='' ){
			//$start_name=strpos($attendee_names,',',1);
			//echo substr($attendee_names,$start_name+1,-1);
			$attendee_names = substr($attendee_names,0,-1);
			echo '<small>' . '<b>' . TEXT_ATTENDEES . '</b>' . '</small>' . '<br>'.tep_draw_separator('pixel_trans.gif',35,1) .'-'.$attendee_names;
	 	}
 	}

	if($order->products[$i]['sku']!="")echo "<br>" . HEADING_SKU . $order->products[$i]['sku'];
    echo '</td>' . "\n";
    if (sizeof($order->info['tax_groups']) > 0) echo '            <td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
    echo '            <td class="main" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</td>' . "\n" .
         '          </tr>' . "\n";
  }

?>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
	  	<tr class="attributes-odd">
			<td ><?php echo tep_draw_separator('pixel_trans.gif', '100%', '12'); ?></td>
		</tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="attributes-odd">
		<tr>
        <td class="main"><b><?php echo HEADING_BILLING_INFORMATION; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
          <tr class="infoBoxContents">
            <td width="30%" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_BILLING_ADDRESS . '</b>'?> <a href="javascript:void(0);" onclick="javascript:showPanelContent({id:'BILLINGSHIPPING',className:'boxRow','type':'cOrder'});"><span class="orderEdit">(<?php echo TEXT_EDIT ?>)</span></a></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_PAYMENT_METHOD . '</b>'?> <a href="javascript:void(0);" onclick="javascript:showPanelContent({id:'PAYMENT',className:'boxRow','type':'cOrder'});"><span class="orderEdit">(<?php echo TEXT_EDIT ?>)</span></a></td>
              </tr>
              <tr>
                <td class="main"><?php echo $GLOBALS[$payment]->title; ?></td>
              </tr>
            </table></td>
            <td width="70%" valign="top" align="right">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="right">
						<table border="0" cellspacing="0" cellpadding="2">
						   <tr> <td>
							<?php
							  if (MODULE_ORDER_TOTAL_INSTALLED) {
								$order_total_modules->process();
								echo $order_total_modules->output();
							  }
							?>
							</td></tr>
						</table>
					</td>
				</tr>
        	</table>
			</td>
		</tr>
		</table>
		</td>
      </tr>
	  	<tr class="attributes-odd">
		<td ><?php echo tep_draw_separator('pixel_trans.gif', '100%', '12'); ?></td>
	</tr>
<?php
// BOF: Lango modified for print order mod
  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
	  $payment_info = $confirmation['title'];
	$FSESSION->set('payment_info',$payment_info);
// EOF: Lango modified for print order mod
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="attributes-odd">
		<tr>
        <td class="main"><b><?php echo HEADING_PAYMENT_INFORMATION; ?></b></td>
      </tr>
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" colspan="4"><?php echo $confirmation['title']; ?></td>
              </tr>
<?php
      for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
?>
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo $confirmation['fields'][$i]['title']; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
				<td class="main"><?php echo $confirmation['fields'][$i]['field']; ?></td>
              </tr>
<?php
      }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
    }
  }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if (tep_not_null($comments)) {
  ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="attributes-odd">
		 <tr>
        <td class="main"><?php echo '<b>' . HEADING_ORDER_COMMENTS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_NEW, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
      </tr>
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
<?php
  if (isset($$payment->form_action_url)) {
    $form_action_url = $$payment->form_action_url;
  } else {
    $form_action_url =FILENAME_CHECKOUT_PROCESS_NEW;
  }
 ?>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0"  >
     <tr>
       <td align="right" class="main">
 <form  name="checkout_confirmation" action="<?php echo $form_action_url; ?>" method="post">
<?php
 if (is_array($payment_modules->modules)) {
   echo $payment_modules->process_button();
 }
   if (is_array($payment_modules->modules)) {
		$val = $payment_modules->process_button();
		$values = '';
		for($i=0;$i<strlen($val);$i++){
			$values .= ord($val{$i}) . ",";
		}
		echo "<script language='javascript'>";
		echo " var hidden_values='" . $values . "';";
		echo "</script>";
	}
  echo '<div id="values"></div>';
   $order_id=$FSESSION->order_id;
   $ostate=$FSESSION->order_status;
   $tot=$order->info['total'] - $order->info['shipping_cost'] - $order->info['modify_price_prefix'];
   if($order_id>0 && $ostate==2 && $tot==0){
   } else {
  	echo "<div id='confirm_button' >".'<a href="javascript:void(0);" onclick="javascript:do_page_fetch(\'process_order\');">' . tep_image_button('button_confirm.gif', IMAGE_CONTINUE) . '</a>';
  }
  echo  '</div>' . "\n";
?>
</form>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td></tr>
	</table>
	<?php
	 }

}?>
<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
	class salesDiscountCoupons
		{
		var $pagination;
		var $splitResult;
		var $type;

		function __construct() {
		$this->pagination=false;
		$this->splitResult=false;
		$this->type='sdc';
		}
		
		function doList(){
			global $FSESSION,$FREQUEST,$jsData,$currencies;
			$page=$FREQUEST->getvalue('page','int',1);
			$where='';
			//$where=' order by c.coupon_id DESC';
			$where=' order by c.date_created DESC';
			$query_split=false;
			$gv_query_raw = "select c.coupon_amount, c.coupon_type,c.coupon_code,c.coupon_active, c.coupon_id, c.orders_id,cd.coupon_name,c.date_created from " . TABLE_COUPONS . " c, " . TABLE_COUPONS_DESCRIPTION . " cd where c.coupon_id = cd.coupon_id and cd.language_id='" . (int)$FSESSION->languages_id . "'" . $where;
			if ($this->pagination){
				$query_split=$this->splitResult = (new instance)->getSplitResult('CUSTOMER');
				$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$gv_query_raw);
				if ($query_split->queryRows > 0){ 
					$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'Items','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_SALES_COUPONS,'##PAGE_NO##') . "'})";
				}
			}
			$gv_query=tep_db_query($gv_query_raw);
			$found=false;
			if (tep_db_num_rows($gv_query)>0) $found=true;
			if($found)
			{
				$template=getListTemplate();
				$icnt=1;
				while($gv_result=tep_db_fetch_array($gv_query))
				{
					//2019 fix amount name
					if($gv_result["coupon_type"]=='P')
					{
						$pre=$gv_result["coupon_amount"].'%';
					}else{
						$pre=$currencies->format($gv_result["coupon_amount"]);
					}
					
					$rep_array=array(	"ID"=>$gv_result["coupon_id"],
										"TYPE"=>$this->type,
										"NAME"=>$gv_result["coupon_name"],
										"ORDERID"=>$gv_result["orders_id"],
										"AMOUNT"=>$pre,
										"CODE"=>$gv_result["coupon_code"],
										"DATE"=>format_date($gv_result["date_created"]),
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>'<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $gv_result["coupon_id"] .',get:\'CouponChangeStatus\',result:doSimpleResult,params:\'cID='. $gv_result["coupon_id"] . '&flag=' .($gv_result['coupon_active']=='Y'?'N':'Y') .'\',message:\''.TEXT_UPDATING_STATUS.'\'});">'. tep_image(DIR_WS_IMAGES . 'template/' . ($gv_result['coupon_active']=='Y'?'icon_active.gif':'icon_inactive.gif')) . '</a>',
										"UPDATE_RESULT"=>'doDisplayResult',
										"ROW_CLICK_GET"=>'Info',
										"FIRST_MENU_DISPLAY"=>""
									);
					echo mergeTemplate($rep_array,$template);
					$icnt++;
				}
			}
			else
			{
				echo '<div align="center">'.TEXT_EMPTY_DISCOUNT_COUPONS.'</div>';
			}
			if (!isset($jsData->VARS["Page"])){
				$jsData->VARS["NUclearType"][]=$this->type;
			} 
			return $found;			
		}
		
		function doItems()
		{
			global $FREQUEST,$jsData;
			$template=getListTemplate();
?>
			<table border="0" width="100%" id="<?php echo $this->type;?>Table">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0" >
							<tr class="dataTableHeadingRow">
								<td valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr  >
										<td class="main" width="30%">
										<b><?php echo  TABLE_HEADING_COUPON_NAME;?></b>
										</td>
										<td class="main" width="15%">
										<b><?php echo  TABLE_HEADING_COUPON_AMOUNT;?></b>
										</td>
										<td class="main" width="15%">
										<b><?php echo  TABLE_HEADING_COUPON_CODE;?></b>
										</td>
										<td class="main" width="15%">
										<b><?php echo  TABLE_HEADING_CREATE_DATE;?></b>
										</td>
										<td class="main" width="15%">
										<b><?php echo  TABLE_HEADING_ORDER;?></b>
										</td>
										<td width="20%">&nbsp;</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<?php $this->doList();?>
							</td>
							</tr>	
					</Table>
				</td>
			</tr>
		</table>
		<?php if (is_object($this->splitResult)){?>
				<table border="0" width="100%" >
						<?php echo $this->splitResult->pgLinksCombo(); ?>
				</table>
		<?php }
		}
		function doCouponChangeStatus()
		{
			global $FREQUEST,$jsData;
			$cid=$FREQUEST->getvalue("cID","int",0);
			$flag=$FREQUEST->getvalue("flag","string");
			if ($cid<=0) return;
				tep_db_query("update " . TABLE_COUPONS . " set coupon_active='" . $flag . "' where coupon_id='" . tep_db_input($cid) . "'");
			if ($flag=='Y'){
				$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $cid .",get:\'CouponChangeStatus\',result:doSimpleResult,params:\'cID=". $cid . "&flag=N\',message:\'".TEXT_UPDATING_STATUS."\'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_active.gif') . '</a>';
			} else {
				$result='<a href="javascript:void(0)" onclick="javascript:doSimpleAction({id:'. $cid .",get:\'CouponChangeStatus\',result:doSimpleResult,params:\'cID=". $cid . "&flag=Y\',message:\'".TEXT_UPDATING_STATUS."\'});\">" . tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif') . '</a>';
			}
			echo 'SUCCESS';
			$jsData->VARS["replace"]=array("sdc". $cid ."bullet"=>$result);
		}
		function doUserList()
		{
			global $FREQUEST,$jsData,$currencies;
			$cid=$FREQUEST->getvalue("cID","int",0);
			echo $this->doInfo($cid);
			$customer_query=tep_db_query("select o.customers_name,o.date_purchased,o.orders_id,ot.value from " . TABLE_ORDERS . " o , " . TABLE_ORDERS_TOTAL . " ot, ". TABLE_COUPON_REDEEM_TRACK . " ct where ot.orders_id=o.orders_id and ot.class='ot_total' and ct.order_id=o.orders_id and ct.order_id!=0 and ct.coupon_id='" . tep_db_input($cid) ."' order by o.orders_id");?>
			<br>
			<div style="height:150px;overflow:auto;">
			<table cellpadding="0" cellspacing="1" bgcolor="#000000;" width="90%" align="center">
			<tr class="opencontent">
				<td width="35%" class="main" style="padding:5px;"><b><?php echo TABLE_HEADING_CUSTOMER_NAME ?></b></td>
				<td width="20%" class="main" align="right" style="padding:5px;"><b><?php echo TABLE_HEADING_AMOUNT ?></b></td>
				<td width="20%" class="main" style="padding:5px;" align="center"><b><?php echo TABLE_HEADING_DATE ?></b></td>

			</tr>
			<?php if(tep_db_num_rows($customer_query)>0)
				{
					while($customer_result=tep_db_fetch_array($customer_query)) 
					{ 
						echo '<tr class="opencontent" style="padding:5px; cursor:pointer" onclick="location.href=\'' . tep_href_link(FILENAME_ORDERS,'return=sdc&oID=' . $customer_result['orders_id']) . '\'">
								<td class="main" width="35%">' . $customer_result['customers_name'] . '</td>
								<td class="main" align="right" width="20%">' . $currencies->format($customer_result['value']) .'</td>
								<td class="main" align="center" width="20%">' . format_date($customer_result['date_purchased']) . '</td>
							  </tr>';
					}
				} 
				else echo '<tr class="opencontent" ><td align="center" class="main" colspan=3 style="padding:5px;">' . TEXT_NO_CUSTOMERS . '</td></tr>';
				echo '</table></div><br>';

			$jsData->VARS["updateMenu"]=",normal,";
		}
		function doMailList()
		{
			global $FREQUEST,$jsData;
			$cid=$FREQUEST->getvalue("cID","int",0);
			echo $this->doInfo($cid);
        	$show_email_history_query=tep_db_query("select cde.discount_coupon_id,cde.discount_coupon_code,cde.date_sent,c.customers_firstname,c.customers_lastname,c.customers_email_address from ".TABLE_COUPON_DISCOUNT_EMAIL." cde, ".TABLE_CUSTOMERS." c where c.customers_id=cde.customer_id and cde.coupon_id='".(int)$cid."'");
			$coupon_flag_query=tep_db_query("select coupon_flag from ".TABLE_COUPONS." where coupon_id='$cid'");
			$coupon_flag_array=tep_db_fetch_array($coupon_flag_query);?>
			<div style="height:150px;overflow:auto;">
			<table cellpadding="0" cellspacing="1" bgcolor="#000000;" width="90%" align="center">
			<tr class="opencontent" >
				<td width="30%" class="main" style="padding:5px;"><b><?php echo TABLE_HEADING_CUSTOMER_NAME ?></b></td>
				<td width="20%" class="main"  style="padding:5px;"><b><?php echo TABLE_HEADING_CUSTOMER_EMAIL ?></b></td>
				<td width="25%" class="main" style="padding:5px;" ><b><?php echo TEXT_HEADING_DISCOUNT_COUPON_CODE ?></b></td>
				<td  class="main" style="padding:5px;"><b><?php echo TEXT_HEADING_DATE_SEND ?></b></td>
			</tr>
			<?php if($coupon_flag_array['coupon_flag']=='U')
				{
					if(tep_db_num_rows($show_email_history_query)>0)
					{
						$class='opencontent';
						while($show_email_array=tep_db_fetch_array($show_email_history_query)) 
						{ 
							echo '<tr class="'.$class.'" style="cursor:pointer" onclick="javascript:do_page_fetch(\'show_email_content\',\''. $show_email_array['discount_coupon_id'] . '\');" height="20">
							<td  class="main" style="padding:5px;" width="30%">' . $show_email_array['customers_firstname'] .'&nbsp;'.$show_email_array['customers_lastname']. '</td>
							<td class="main" style="padding:5px;" width="20%">' . $show_email_array['customers_email_address'] . '</td>
							<td class="main" style="padding:5px;" width="25%">' . $show_email_array['discount_coupon_code'] .'</td>
							<td class="main" style="padding:5px;" >'.format_date($show_email_array['date_sent']).'</td> </tr>';
						}
					} else
					  echo '<tr class="opencontent"><td colspan="4" align="center" class="main" style="padding:5px;">' . TEXT_NO_EMAIL_HISTORY . '</td></tr>';
				}else
					echo '<tr class="opencontent"><td colspan="4" class="main" style="padding:5px;" align="center">'.( ($coupon_flag_array['coupon_flag']=='C')?TEXT_EMAIL_ALL_CUSTOMERS:TEXT_EMAIL_NEWS_LETTER).'</td></tr>';
				echo "</table></div>";
			$jsData->VARS["updateMenu"]=",normal,";
		}	
		function doMailContent()
		{
			global $FREQUEST,$jsData;
			$dcid=$FREQUEST->getvalue("dcID","int",0);
			$result="";
			$customers_query=tep_db_query("select customers_firstname,customers_lastname from ".TABLE_CUSTOMERS." c, ".TABLE_COUPON_DISCOUNT_EMAIL." cde where c.customers_id=cde.customer_id and cde.discount_coupon_id='".(int)$dcid."'");
			$customers_array=tep_db_fetch_array($customers_query);
			$result.='<table width="100%" cellpadding="0" cellspacing="0" border="0">
					  <tr height="10px;" class="dataTableHeadingRow">
					  <td class="dataTableHeadingContent">'.$customers_array['customers_firstname'].'&nbsp;'.$customers_array['customers_lastname'].'</td>
					  <td align="right"><a href="javascript: void close_mail_content();">'.tep_image(DIR_WS_IMAGES.'template/img_closel.gif','Close','','','').'</a></td>
					  </tr>
					  <tr>
					  <td colspan="2" style="padding-top:10px;" class="main">	
			';
			$show_mail_content_query=tep_db_query("select content from ".TABLE_COUPON_DISCOUNT_EMAIL." where discount_coupon_id='".(int)$dcid."'");
			
			if(tep_db_num_rows($show_mail_content_query)>0)
			{
				$show_mail_content_array=tep_db_fetch_array($show_mail_content_query);
				$result.=(strlen($show_mail_content_array['content'])>0)?$show_mail_content_array['content']:TEXT_NO_MAIL_CONTENT;
			}else{
				$result.=TEXT_NO_MAIL_CONTENT;
			$result.='</td></tr></table>';	
			}
			echo $result;
		}
		function doMail()
		{
			global $FREQUEST,$jsData,$FSESSION;
			$coupon_id=$FREQUEST->getvalue("cID","int",0);
			$uses_per_coupon=0; $customers_use_coupon=0; $coupon_redeem_total=0;$uses_per_order=0;
			$coupon_use_query=tep_db_query("select uses_per_coupon,coupon_flag from ".TABLE_COUPONS." where coupon_id='". tep_db_input($coupon_id). "'");
			if($coupon_use_array=tep_db_fetch_array($coupon_use_query))
				$uses_per_coupon=$coupon_use_array['uses_per_coupon'];
				$uses_per_order=$coupon_use_array['uses_per_order'];
			$customers_use_coupon_query=tep_db_query("select count(*) as total from ".TABLE_COUPON_DISCOUNT_EMAIL." where coupon_id='" . tep_db_input($coupon_id) . "'");
			if($customers_use_coupon_array=tep_db_fetch_array($customers_use_coupon_query))
				$customers_use_coupon=$customers_use_coupon_array['total'];
			$coupon_redeem_total_query=tep_db_query("select count(*) as total from ".TABLE_COUPON_REDEEM_TRACK." where coupon_id='" . tep_db_input($coupon_id) . "'");
			if($coupon_redeem_total_array=tep_db_fetch_array($coupon_redeem_total_query))
				$coupon_redeem_total=$coupon_redeem_total_array['total'];

			// if(($uses_per_coupon <= ($customers_use_coupon+$coupon_redeem_total)))
			// {
				// echo '<br><span class="main"><center><b>'.TEXT_USERS_COUPON_EXCEEDS.'</b></center></span><br>';
			// }
			// else
			// {
				echo tep_draw_form('mail', FILENAME_DISCOUNT_SALES, 'action=preview').tep_draw_hidden_field('coupon_id',$coupon_id); ?>
				<table border="0" width="100%" cellspacing="2" cellpadding="2">
				<tr style="display:none;padding:10px;" id="error_tag"><td>
				<table width="100%" border="0" cellspacing="0" cellpadding="2" class="formArea">
				<tr><td class="main" id="error_message" style="color:#FF0000;"></td></tr>
				 </table>
				 </td>
				</tr>
				  <tr> 
				<!-- body_text //-->
					<td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="100%">
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
						  <tr>
							<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
							<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
						  </tr>
						</table>
						</td>
					  </tr>
					  <tr><td id="error"></td>
					  <tr><td id="preview"></td> </tr>
					  <tr>
					  <input type="hidden" id="sent_to">
					  <td id="phase1">
					  <table border="0" width="100%" cellspacing="0" cellpadding="2">
					<?php
					if (($FREQUEST->getvalue('action') == 'preview') && ($FREQUEST->postvalue('customers_email_address') || $FREQUEST->postvalue('email_to')) ) 
					{

					} 
					else
					{ 
					echo table_write($coupon_id); 
					}
					?>
						</table>
						</td>
					  </tr>
					</table>
					</td>
				  </tr>
				</table></form>
	<?php 
			$jsData->VARS['doFunc']=array('type'=>'sdc','data'=>'doMailEditor');
		 // }
	}
	function doMailPreview()
	{
		global $FREQUEST,$jsData,$currencies,$FPOST;
		 $coupon_id=$FREQUEST->postvalue('coupon_id');
	 		 if ((!$FREQUEST->postvalue('customers_email_address')) && (!$FREQUEST->postvalue('email_to')) ) 
			 {
			  	   echo ERROR_NO_CUSTOMER_SELECTED;
				   exit;
  				}
			switch ($FREQUEST->postvalue('customers_email_address')) 
			{
			  case '***':
			  	$customer_name = TEXT_ALL_CUSTOMERS;
				$mail_sent_to = TEXT_ALL_CUSTOMERS;
				break;
			  case '**D':
			  	$customer_tname = TEXT_NEWSLETTER_CUSTOMERS;
				$mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
				break;
			  default:
				$mail_sent_to = $FREQUEST->postvalue('customers_email_address');
				$customers_array=array();
				$customers_query=tep_db_query("select customers_id,customers_firstname,customers_lastname from ".TABLE_CUSTOMERS." where customers_email_address like '".tep_db_input($FREQUEST->postvalue('customers_email_address'))."'");
				if(tep_db_num_rows($customers_query))
				{
					$customers_array=tep_db_fetch_array($customers_query);
					$customers_id=$customers_array['customers_id'];
					$coupon_user_per_user_query=tep_db_query("select uses_per_user,coupon_flag from ".TABLE_COUPONS." where coupon_id='".tep_db_input($coupon_id)."'");
					$coupon_user_per_user_array=tep_db_fetch_array($coupon_user_per_user_query);
					
					$coupon_discount_query=tep_db_query("select count(*) as total from ".TABLE_COUPON_DISCOUNT_EMAIL." where customer_id='".tep_db_input($customers_id) ."' and coupon_id='" .tep_db_input($coupon_id) ."'");
					$coupon_discount_array=tep_db_fetch_array($coupon_discount_query);					
					$coupon_redeem_query=tep_db_query("select count(*) as total from ".TABLE_COUPON_REDEEM_TRACK." where customer_id='".tep_db_input($customers_id). "' and coupon_id='". tep_db_input($coupon_id) ."'");
					$coupon_redeem_array=tep_db_fetch_array($coupon_redeem_query);

					if($coupon_user_per_user_array['coupon_flag']=='C' 
					|| $coupon_user_per_user_array['coupon_flag']=='N' 
					|| $coupon_user_per_user_array['uses_per_user']<($coupon_redeem_array['total']+$coupon_discount_array['total']) )
					{
					echo 'Customer Coupon Exceed';
					exit;
					}
					$customer_name=addslashes($customers_array['customers_firstname']).'&nbsp;'.addslashes($customers_array['customers_lastname']);
				}
				if ($FREQUEST->postvalue('email_to')) 
				{
				  $mail_sent_to = $FREQUEST->postvalue('email_to');
				}
				break;
			}
		$coupon_code = create_coupon_code($FREQUEST->postvalue('email_to')); $coupon_amount=0;
		$coupon_amount_query=tep_db_query("select coupon_amount from ".TABLE_COUPONS." where coupon_id='".(int)$coupon_id."'");
		if($coupon_amount_array=tep_db_fetch_array($coupon_amount_query))
		{
			$coupon_amount=$coupon_amount_array['coupon_amount'];
		}
		$store_url = HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no=xxxxxx';
		
		$search_array=array('%%Customer Name%%','%%Store Name%%','%%Coupon Value%%','%%Store Owner%%','%%Store Address%%','%%Store URL%%','%%Coupon Code%%');
		$replace_array=array($customer_name,STORE_NAME,$currencies->format($coupon_amount),STORE_OWNER,nl2br(STORE_NAME_ADDRESS),$store_url,'xxxxxx');
		
		echo tep_draw_form('mail', FILENAME_DISCOUNT_SALES, 'action=preview').tep_draw_hidden_field('coupon_id',$coupon_id); ?>
		<?php
		/* Re-Post all POST'ed variables */
			reset($FPOST);
			while (list($key, $value) = each($FPOST)) 
			{
			  if (!is_array($FREQUEST->postvalue($key))) 
			  {
				echo tep_draw_hidden_field($key.'_h', htmlspecialchars(stripslashes($value)));
			  }
			}
		?>

          <tr>
            <td>
				<table border="0" width="100%" cellpadding="0" cellspacing="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_CUSTOMER; ?></b><br><?php echo $mail_sent_to; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_FROM; ?></b><br><?php echo htmlspecialchars(stripslashes($FREQUEST->postvalue('from'))); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo htmlspecialchars(stripslashes($FREQUEST->postvalue('subject'))) ; ?> </td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b>
			<?php 
			// if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Enable') 
			// { 
			// echo str_replace($search_array,$replace_array,$FREQUEST->postvalue('message')); 
			// } 
			// else 
			// { 
			echo str_replace($search_array,$replace_array,$FREQUEST->postvalue('message')); 
			//} 
			?>
		</b></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); 

				 ?></td>
              </tr>
              <tr>
                <td>
                <table border="0" width="100%" cellpadding="0" cellspacing="2">
                  <tr>
                    <td align="right"><?php echo '<a href="javascript:doCancelAction({\'id\':' .$coupon_id . ',\'get\':\'UserList\',\'type\':\'sdc\',\'style\':\'boxRow\'});">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> ' .'<a href="javascript:doUpdateAction({\'id\':\''.$coupon_id.'\',\'type\':\'sdc\',\'style\':\'boxRow\',\'imgUpdate\':false,\'uptForm\':\'mail\',\'get\':\'SendMail\',\'result\':doDisplayResult,\'message1\':\'' . TEXT_SENDING_MAIL. '\'})";>' . tep_image_button('button_send_email.gif', IMAGE_SEND_EMAIL) .'</a>'; ?></td>
                    </tr>
                </table></td>
             </tr>
            </table></td>
          </tr></tr></form>
<?php
	 
		}	
		function doSendMail()
		{
			global $FREQUEST,$jsData,$mimemessage,$currencies;
			$coupon_id=$FREQUEST->postvalue('coupon_id_h');
			switch ($FREQUEST->postvalue('customers_email_address_h')) 
			{
			  case '***':
				$mail_query = tep_db_query("select customers_id,customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS);
				$mail_sent_to = TEXT_ALL_CUSTOMERS;
				tep_db_query("update ".TABLE_COUPONS." set coupon_flag='C' where coupon_id='".tep_db_input($coupon_id)."'");
				break;
			  case '**D':
				$mail_query = tep_db_query("select customers_id,customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
				$mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
				tep_db_query("update ".TABLE_COUPONS." set coupon_flag='N' where coupon_id='".tep_db_input($coupon_id)."'");
				break;
			  default:
				tep_db_query("update ".TABLE_COUPONS." set coupon_flag='U' where coupon_id='".tep_db_input($coupon_id)."'");
				$customers_email_address = $FREQUEST->postvalue('customers_email_address_h');
				
				$mail_query = tep_db_query("select customers_id,customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($customers_email_address) . "'");
				
				$mail_sent_to = $FREQUEST->postvalue('customers_email_address_h');
				if ($FREQUEST->postvalue('email_to_h')) 
				{
				  $mail_sent_to = $FREQUEST->postvalue('email_to_h');
				}
				break;
			}
			$from = $FREQUEST->postvalue('from_h');
			$subject = $FREQUEST->postvalue('subject_h');
          
			while ($mail = tep_db_fetch_array($mail_query)) 
			{ 
			  $customers_id=$mail['customers_id'];
			  $coupon_code = create_coupon_code($mail['customers_email_address']);
			  // ticket 16
			  $customer_name=addslashes($mail['customers_firstname']). '&nbsp;' . addslashes($mail['customers_lastname']);
			  $coupon_check_query=tep_db_query("select count(*) as total from ".TABLE_COUPON_DISCOUNT_EMAIL." where customer_id='".tep_db_input($customers_id)."' and coupon_id='".tep_db_input($coupon_id) . "'");
			  $coupon_check_array=tep_db_fetch_array($coupon_check_query);
			 // if($coupon_check_array['total']<2){
			  $coupon_amount=0; $coupon_tax_class_id=0;
			  $coupon_query=tep_db_query("select coupon_amount,coupon_tax_class_id from ".TABLE_COUPONS." where coupon_id='".tep_db_input($coupon_id)."'");
			  if($coupon_array=tep_db_fetch_array($coupon_query))
			  {
			  	$coupon_amount=$coupon_array['coupon_amount'];
			 	$coupon_tax_class_id=$coupon_array['coupon_tax_class_id'];
			  }
			  $message = $FREQUEST->postvalue('message_h');
			  $message_format = $FREQUEST->postvalue('message_format_h');
			  // if (SEARCH_ENGINE_FRIENDLY_URLS == 'true')
			  // {
			  		// $store_url ='<a href="' .HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no=xxxxxx' . '">' .  HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '/gv_no,'.$coupon_code . '</a>';
			  // }else
			  // {
			  		$store_url ='<a href="' .HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no=xxxxxx' . '">'  .  HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no='.$coupon_code . '</a>';
			  //}
			  $search_array=array('%%Customer Name%%','%%Store Name%%','%%Coupon Value%%','%%Store Owner%%','%%Store Address%%','%%Store URL%%','%%Coupon Code%%');
			  $replace_array=array($customer_name,STORE_NAME,$currencies->format($coupon_amount),STORE_OWNER,nl2br(STORE_NAME_ADDRESS),$store_url,$coupon_code);
			  $message = str_replace($search_array,$replace_array,$message);
			  //added
			   $message_text=strip_tags($details['html_text'],'<br>');
			   $message_text=str_replace(array('<br />','<br>','<BR>','<BR />','<br/>','<BR/>'),chr(13). chr(10),$message);
			  //Let's build a message object using the email class
			   $mimemessage = new email(array('X-Mailer: osConcert bulk mailer'));
			  // add the message to the object
			  //added
			  if($message_format!='T')
			  {
				  $mimemessage->add_html($message,$message_text);    
			  }else 
			  {
				  $mimemessage->add_text($message);
			  }
              
			  $mimemessage->build_message();
              $server_date=getServerDate(true);
			  $mimemessage->send(addslashes($mail['customers_firstname']) . ' ' . addslashes($mail['customers_lastname']), $mail['customers_email_address'], '', $from, $subject);
			  tep_db_query("insert into ".TABLE_COUPON_DISCOUNT_EMAIL." (coupon_id,customer_id,discount_coupon_code,date_sent,amount,tax,content) values('$coupon_id','$customers_id','$coupon_code','$server_date','$coupon_amount','$coupon_tax_class_id','$message') ");
            }
           
			echo $this->doInfo($coupon_id);
		}
		
		function doInfo($cid=0)
		{
			global $FREQUEST,$FSESSION,$jsData,$currencies;
			
			if($cid <= 0)$cid=$FREQUEST->getvalue("cID","int",0);
			$uses_per_coupon=0; $customers_use_coupon=0; $coupon_redeem_total=0;
				
			$customers_use_coupon_query=tep_db_query("select count(*) as total from ".TABLE_COUPON_DISCOUNT_EMAIL." where coupon_id='" . tep_db_input($cid) . "'");
			if($customers_use_coupon_array=tep_db_fetch_array($customers_use_coupon_query))
				$customers_use_coupon=$customers_use_coupon_array['total'];

			$coupon_query=tep_db_query("select c.uses_per_coupon,c.coupon_active,cd.coupon_name,c.coupon_amount,c.coupon_code from " . TABLE_COUPONS . " c , " . TABLE_COUPONS_DESCRIPTION . " cd where c.coupon_id=cd.coupon_id and cd.language_id='" . (int)$FSESSION->languages_id ."' and c.coupon_id='" . tep_db_input($cid) ."'");
			$coupon_result=tep_db_fetch_array($coupon_query);
			$uses_per_coupon=$coupon_result['uses_per_coupon'];
			$template=getInfoTemplate();
			$rep_array=array(	"TYPE"=>$this->type,
								"TEXT_COUPON_NAME"=>TEXT_COUPON_NAME,
								"TEXT_COUPON_CODE"=>TEXT_COUPON_CODE,
								"TEXT_COUPON_AMOUNT"=>TEXT_COUPON_AMOUNT,
								"COUPON_NAME"=> $coupon_result['coupon_name'],
								"COUPON_CODE"=>$coupon_result['coupon_code'],
								"COUPON_AMOUNT"=>$currencies->format($coupon_result['coupon_amount']),
								"TEXT_USES_PER_COUPON"=>TEXT_USES_PER_COUPON,
								"USES_PER_COUPON"=>$coupon_result['uses_per_coupon'],
								"TEXT_CUSTOMERS_USES_COUPON"=>TEXT_CUSTOMERS_USES_COUPON,
								"CUSTOMERS_USES_COUPON"=>$customers_use_coupon
								);
				echo mergeTemplate($rep_array,$template);
				$jsData->VARS["updateMenu"]=",normal,";
			}		
		}
		function getListTemplate()
		{
		ob_start();
		getTemplateRowTop();
?>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
			<tr>
				<td>
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="15" id="sdc##ID##bullet">##STATUS##</td>
						<td width="25%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'cID=##ID##'});" id="##TYPE####ID##name">##NAME##</td>
						<td width="13%"class="main" align="right" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'cID=##ID##'});" id="##TYPE####ID##amount">##AMOUNT##</td>
						<td width="17%"class="main" align="center" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'cID=##ID##'});" id="##TYPE####ID##code">##CODE##</td>
						<td width="17%"class="main" nowrap align="center" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'cID=##ID##'});" id="##TYPE####ID##date">##DATE##</td>
						<td width="17%"class="main" nowrap align="center" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'cID=##ID##'});" id="##TYPE####ID##date">##ORDERID##</td>
						<td  width="35%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
							<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
							<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'UserList','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'cID=##ID##'});"><img src="##IMAGE_PATH##template/img_move.gif" title="Usage"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Mail','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'cID=##ID##'});"><img src="##IMAGE_PATH##template/mail.gif" title="Email"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'MailList','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'cID=##ID##'});"><img src="##IMAGE_PATH##template/img_message.gif" title="Email History"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							</span>
							<span id="##TYPE####ID##mupdate" style="display:none">
							<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':groupValidate,'uptForm':'customer_groups','customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'UserList','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif"/></a>
							</span>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
<?php
		getTemplateRowBottom();
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	function getInfoTemplate(){
		ob_start();
?><br />
		<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<tr>
				<td width="20%" align="right" nowrap="nowrap"  class="main"><b>##TEXT_COUPON_NAME##</b></td>
				<td width="20%" align="left"  class="main">##COUPON_NAME##</td>
				<td width="10%" align="right" nowrap="nowrap"  class="main"><b>##TEXT_USES_PER_COUPON##</b></td>
				<td width="70%" align="left" class="main">##USES_PER_COUPON##</td>
				
			<tr>
				<td align="right"  class="main"><b>##TEXT_COUPON_CODE##</b></td>
				<td align="left" class="main">##COUPON_CODE##</td>
				<td align="right" nowrap="nowrap"  class="main"><b>##TEXT_CUSTOMERS_USES_COUPON##</b></td>
				<td align="left" class="main">##CUSTOMERS_USES_COUPON##</td>
			</tr>
			<tr>
				<td  align="right" class="main"><b>##TEXT_COUPON_AMOUNT##</b></td>
				<td  align="left" class="main">##COUPON_AMOUNT##</td>
			</tr>
		</table>
<?php
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	
	function table_write($id)
	{
    global $page,$uses_per_coupon,$customers_use_coupon,$FREQUEST,$FSESSION;
?>	<table width="100%" border="0" cellpadding="0" cellspacing="2">
          <tr>
            <td><table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
<?php
    $customers = array();
    $customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
    $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
    $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
    $mail_query = tep_db_query("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " order by customers_lastname");
    while($customers_values = tep_db_fetch_array($mail_query)) 
	{
      $customers[] = array('id' => $customers_values['customers_email_address'],
                           'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');
    }
	$email_query=tep_db_query("select message_subject,message_text,message_format from ".TABLE_EMAIL_MESSAGES." where message_type like 'SGV'");
	$email_array=array();
	if(tep_db_num_rows($email_query)>0)
		$email_array=tep_db_fetch_array($email_query);

?>
              <tr>
                <td class="main"><?php echo TEXT_CUSTOMER; ?></td>
                <td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers, '','onChange="javascript: coupon_warning();"');?>&nbsp;&nbsp;<span id="coupon_warning" class="smallText" style="color:#FF0000;display:none">Coupon Warning</span></td>
				<td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
             <tr>
                <td class="main"><?php echo TEXT_FROM; ?></td>
                <td colspan="2"><?php echo tep_draw_input_field('from', EMAIL_FROM); ?></td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_SUBJECT; ?></td>
                <td><?php echo tep_draw_input_field('subject',$email_array['message_subject']); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
              <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
              <td><?php echo tep_draw_textarea_field('message', 'soft', '80', '24',$email_array['message_text'],'id="message"'); ?></td>
			  <td style="background:ButtonFace;" valign="bottom" align="center">
			<?php 
			$array_result=array();
			$array_result=array( array('id'=>'%%Customer Name%%','text'=>'Customer Name'),
								  array('id'=>'%%Coupon Code%%','text'=>'Coupon code'),
								  array('id'=>'%%Coupon Value%%','text'=>'Coupon Value'),
								  array('id'=>'%%Store Name%%','text'=>'Store Name'),
								  array('id'=>'%%Store Address%%','text'=>'Store Address'),
								  array('id'=>'%%Store Owner%%','text'=>'Store Owner'),
								  array('id'=>'','text'=>'Store URL link'));
			  	$format_array=array(
							array('id'=>'B','text'=>TEXT_FORMAT_BOTH)
							);
							
			
					  
			echo "<div class='main'><b>" . TEXT_MERGE_FIELDS . '</b></div><br>';
			echo tep_draw_pull_down_menu('fields',$array_result,'','style="height:' .((strpos($_SERVER['HTTP_USER_AGENT'],"MSIE")>0)?'225':'241').'" size=15 ondblClick="AddField()"');
			
			
			?><script>initEditor('message');</script>
			</td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_MESSAGE_FORMAT; ?></td>
                <td><?php echo tep_draw_pull_down_menu('message_format', $format_array, $email_array['message_format']);?></td>
				<td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                 <td colspan="3" align="right">
                 <?php 
				 	echo '<a href="javascript:doCancelAction({\'id\':' .$id . ',\'get\':\'UserList\',\'type\':\'sdc\',\'style\':\'boxRow\'});">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> ';
				 	//if($uses_per_coupon > $customers_use_coupon){
						// if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Enable')
						// { 
						// echo tep_image(tep_output_string(DIR_WS_LANGUAGES . $FSESSION->language . '/images/buttons/button_preview.gif'), IMAGE_SEND_EMAIL,'','', ' style="cursor:pointer" onClick="javascript:return doUpdateAction({\'id\':\''.$id.'\',\'type\':\'sdc\',\'style\':\'boxRow\',\'imgUpdate\':false,\'validate\':mail_validate,\'uptForm\':\'mail\',\'get\':\'MailPreview\',\'result\':doDisplayResult,\'message1\':\'' .TEXT_CREATING_PREVIEW . '\'});"');
						// } else 
						// {
						echo tep_image(tep_output_string(DIR_WS_LANGUAGES . $FSESSION->language . '/images/buttons/button_preview.gif'), IMAGE_SEND_EMAIL,'','', ' style="cursor:pointer" onClick="javascript:return doUpdateAction({\'id\':\''.$id.'\',\'type\':\'sdc\',\'style\':\'boxRow\',\'imgUpdate\':false,\'validate\':mail_validate,\'uptForm\':\'mail\',\'get\':\'MailPreview\',\'result\':doDisplayResult,\'message1\':\'' .TEXT_CREATING_PREVIEW . '\'});"');
						//}
					//}
				?>
                </td>
              </tr>
            </table></td>
         </tr></table>
<?php
  }
	?>
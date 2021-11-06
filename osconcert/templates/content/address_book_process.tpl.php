<?php
	/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	Released under the GNU General Public License 
	*/	
	// Check to ensure this file is included in osConcert!
	defined('_FEXEC') or die();
	
	include(DIR_WS_INCLUDES.'general.js');
	include(DIR_WS_INCLUDES.'javascript/http.js');
	include(DIR_WS_INCLUDES.'javascript/customer_account.js');
	
	
	if ($FREQUEST->getvalue('delete')=="") 
		echo tep_draw_form('account', tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS_NEW, ($FREQUEST->getvalue('edit')!='' ? 'edit=' . $FREQUEST->getvalue('edit') : ''), 'SSL'), 'post'); ?>

<div class="section-header">
<h2><?php echo HEADING_TITLE; ?></h2>
</div>	

		<h3><?php if ($FREQUEST->getvalue('edit')!='') { echo HEADING_TITLE_MODIFY_ENTRY; } elseif ($FREQUEST->getvalue('delete')!='') { echo HEADING_TITLE_DELETE_ENTRY; } else { echo HEADING_TITLE_ADD_ENTRY; } ?></h3>
		<?php

		if ($FREQUEST->getvalue('edit')!='') { $header_text = HEADING_TITLE_MODIFY_ENTRY; } elseif ($FREQUEST->getvalue('delete')!='') { $header_text = HEADING_TITLE_DELETE_ENTRY; } else { $header_text = HEADING_TITLE_ADD_ENTRY; }
		?>
		<?php 
		if ($messageStack->size('addressbook') > 0) 
		{
		?>
			<div><?php echo $messageStack->output('addressbook'); ?></div>
		<?php
		}
		?>

	<style>
		.account{
			padding:2px 2px 2px 4px;
		}
		.account .main{
			padding:0px 0px 8px 0px;
		}
		.account .main h2{
			font-size:13px;
			margin:8px 0px 4px 0px;
			line-height:15px;
		}
		.account .main h3{
			font-size:12px;
			float:left;
			width:230px;
			font-weight:normal;
			margin:0px;
		}
		.account .main div{
			float:left;
		}
		.account .main span.required{
			color:#FF0000;
		}
		.account .main span.desc{
			color:#FF0000;
			font-size:11px;
		}
	</style>
		<?php
        if ($FREQUEST->getvalue('delete')!='') 
		{
		?>
<b><?php echo DELETE_ADDRESS_TITLE; ?></b>
<?php echo DELETE_ADDRESS_DESCRIPTION; ?>
													
			<b><?php echo SELECTED_ADDRESS; ?></b><br>
			<?php echo tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?>
			

			<?php echo tep_address_label($FSESSION->customer_id, $FREQUEST->getvalue('delete'), true, ' ', '<br>'); ?>


								<div><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '"><div style="float:right">' . tep_template_image_button_basic('button_back.gif', IMAGE_BUTTON_BACK) . '</div></a>'; ?>
										</div>
										
										<div>
										<?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $FREQUEST->getvalue('delete') . '&action=deleteconfirm', 'SSL') . '"><div style="float:right">' . tep_template_image_button_basic('button_delete.gif', IMAGE_BUTTON_DELETE) . '</div></a>'; ?>
										</div>
										

		<?php
		} else {
		?>
<strong><?php echo NEW_ADDRESS_TITLE; ?></strong>
<div class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></div>


								
	<?php

		for ($icnt=0,$n=count($fieldsDesc);$icnt<$n;$icnt++){
			$fieldDesc=&$fieldsDesc[$icnt];
			if (!isset($ACCOUNT[$fieldDesc['uniquename']])){
				$ACCOUNT[$fieldDesc['uniquename']]=$fieldDesc['default_value'];
			}

			//echo '<tr><td class="main">';
			if (method_exists($customerAccount,"edit__" . $fieldDesc['uniquename'])){
				$customerAccount->{"edit__" . $fieldDesc['uniquename']}($fieldDesc);
			} else {
				$customerAccount->commonInput($fieldDesc);
			}
			//echo '</td></tr>';
		}
	?>
								

                        <?php
                        $new_address=$FREQUEST->getvalue('edit','int',0);
                        if (($FREQUEST->getvalue('edit')!='' && is_numeric($FREQUEST->getvalue('edit'))) || ($new_address==0)) {
                        ?>
								
									<div class="pull-left">
									<?php
                                        if($FREQUEST->getvalue('edit')!=$FSESSION->customer_default_address_id){
                                            echo tep_draw_checkbox_field('set_primary','',false);
                                            echo '&nbsp;&nbsp;'.SET_AS_PRIMARY;
                                        }
                                    ?>
									</div><br><br>
                                   

									<?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . tep_template_image_button_basic('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?>
								
									<div class="pull-right">
									<?php 
									echo tep_draw_hidden_field('action', 'update') . tep_draw_hidden_field('edit', $FREQUEST->getvalue('edit')) . '' .tep_template_image_button_val('', IMAGE_BUTTON_UPDATE,"onClick='javascript:validateForm();' ''"); 
									?></div>
									

					<?php
							} else 
							{
								if (sizeof($navigation->snapshot) > 0) 
								{
									$back_link = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array($FSESSION->name)), $navigation->snapshot['mode']);
								} else {
									$back_link = tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL');
								}
								?>

									
								<?php echo '<a href="' . $back_link . '">' . tep_template_image_button_basic('', IMAGE_BUTTON_BACK) . '</a>'; ?>
								
								<?php echo tep_draw_hidden_field('action', 'process') . tep_template_image_button('', IMAGE_BUTTON_CONTINUE,"onClick='javascript:validateForm();'"); ?>


				
					<?php 	} ?>

	<?php } ?>	

	<?php if ($FREQUEST->getvalue('delete')=="") echo '</form>'; ?>
	<br><br>
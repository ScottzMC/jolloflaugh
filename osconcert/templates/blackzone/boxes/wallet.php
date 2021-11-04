<?php
/*

  

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
  
*/
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
if(tep_payment_installed('wallet.php')){
	if(!defined('BOX_HEADING_WALLET'))define('BOX_HEADING_WALLET', 'My Wallet');
?>

<?php
	$wallet_string='';


	if (!$FSESSION->is_registered('customer_id')) 
	{
		$wallet_string=' <a href='.FILENAME_LOGIN.'>'.BOX_WALLET_TEXT.'</a>';
	} else 
	{
		$balance=tep_get_wallet_balance($FSESSION->customer_id);
		$wallet_string=' <a href="'.tep_href_link(FILENAME_ACCOUNT, '', 'SSL').'">'. BOX_WALLET_BALANCE. $currencies->format($balance).'</a><br><br>';
		$wallet_string.=' <a style="text-decoration:none" href="'.tep_href_link(FILENAME_WALLET_CHECKOUT_PAYMENT, '', 'SSL').'">'. tep_template_image_button('button_add_funds.gif',IMAGE_WALLET_ADD_FUNDS) .'</a><br><br>';
	}		
	
	echo '<div class="card box-shadow">';
	echo '<div class="card-header">';
	echo '<strong>';
	echo BOX_HEADING_WALLET;
	echo '</strong>';
	echo '</div>';
	echo '<div class="list-group">';
	echo '<div style="padding:4px">'.$wallet_string.'</div>';
	echo '</div>';
	echo '</div>';
	echo '<br class="clearfloat">';
?>

<?php } ?>

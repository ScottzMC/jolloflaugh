<?php
/*
  osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<!-- currencies //-->
<?php
	if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
		if(!defined('BOX_HEADING_CURRENCIES'))define('BOX_HEADING_CURRENCIES', 'Currencies');

		$select_currency_box='<select class="form-control" name="currency" onChange="this.form.submit();">';
		reset($currencies->currencies);
		//while (list($key, $value) = each($currencies->currencies)) 
		foreach($currencies->currencies as $key => $value)	
		{
			$select_currency_box .= '<option value="' . $key . '"';
			// $currency is a session variable
			if ($FSESSION->currency == $key) {
				$select_currency_box .= ' SELECTED';
			}
			$select_currency_box .= '>' . $value['title'] . '</option>';
		}
		$select_currency_box .= "</select>";
 		$select_currency_box .= tep_hide_session_id();

		$hidden_get_variables = '';
		reset($FGET);
		//while (list($key, $value) = each ($FGET)) 
		foreach($FGET as $key => $value)	
		{
			if ( ($key != 'currency') && ($key != $FSESSION->name) ) {
			  $hidden_get_variables .= tep_draw_hidden_field($key, $value);
			}
		}
		if (getenv('HTTPS') == 'on') $connection = 'SSL';
		else $connection = 'NONSSL';
		
		$select_currency_box= '<form name="currencies" method="get" action="' . tep_href_link(basename($PHP_SELF), '', $connection, false) . '">' . $select_currency_box . $hidden_get_variables . '</form>';
		
		echo '<div class="card box-shadow">';
		echo '<div class="card-header">';
		echo '<strong>';
		echo BOX_HEADING_CURRENCIES;
		echo '</strong>';
		echo '</div>';
		echo '<div class="list-group">';
		echo $select_currency_box;
		echo '</div>';
		echo '</div>';
		echo '<br class="clearfloat">';
	}
?><!-- currencies_eof //-->

<?php


/*  osCommerce
  http://www.oscommerce.com/

  Copyright (c) 2000,2001 osCommerce

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License */


// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<!-- Banner Box //-->
<?php
		//InfoBox Heading
		if(!defined('BOX_HEADING_BANNER'))define('BOX_HEADING_BANNER', 'Payments by:');
		echo '<div class="card box-shadow">';
		echo '<div class="card-header">';
		echo '<strong>';
		echo BOX_HEADING_BANNER;
		echo '</strong>';
		echo '</div>';
		echo '<div class="list-group">';
		//echo '<div style="padding:5px;margin:auto"><iframe width="260" height="125" src="https://www.youtube.com/embed/Vc6g3MKRBGU" frameborder="0" allowfullscreen></iframe></div>';
		echo '<div style="padding:5px;margin:auto"><a href="#">' . 
		 tep_image(HTTP_HOME_URL . 'images/paypalcards.png', 'Secure Payments', '244','') . '</a></div>';
		echo '</div>';
		echo '</div>';
?><br class="clearfloat"><!-- banner_eof //-->

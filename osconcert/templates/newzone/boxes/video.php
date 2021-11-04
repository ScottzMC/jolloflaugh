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
<!-- Video Box //-->
<?php
	//InfoBox Heading
	if(!defined('BOX_HEADING_VIDEO'))define('BOX_HEADING_VIDEO', 'Video');
	echo '<div class="card box-shadow">';	
	echo '<div class="card-header">';
	echo '<strong>';
	echo BOX_HEADING_VIDEO;
	echo '</strong>';
	echo '</div>';
	echo '<div class="list-group">';
	echo '<div style="margin:auto"><iframe width="250" height="125" src="https://www.youtube.com/embed/Vc6g3MKRBGU" frameborder="0" allowfullscreen></iframe></div>';
		//echo '<div style="padding:5px;margin:auto">' . tep_image(HTTP_HOME_URL . 'images/paymentsby.jpg', 'Secure Payments', '260','') . '</div>';
	echo '</div>';
	echo '</div>';
	echo '<br class="clearfloat">';
?><!-- video_eof //-->

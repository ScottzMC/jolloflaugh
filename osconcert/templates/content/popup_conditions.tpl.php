<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
define('TEXT_CLOSE_WINDOW', 'Close Window'); 


  $content_query=tep_db_query("select description from main_page_description where page_name='TandC'");
  $content_result = tep_db_fetch_array($content_query);
  if($content_result){
      echo $content_result['description'];
	       }else{
		   
		   ?>



<p><strong><?php echo HEADING_TITLE ?></strong></p>
<p>
<?php echo TEXT_POPUP_CONDITIONS ?></p>
<p>
<?php echo TEXT_POPUP_CONDITIONS_GUIDE ?></p>


<?php }?>
<?php echo '<br /><br /><a href="javascript:jQuery.fancybox.close();">' . TEXT_CLOSE_WINDOW . '</a>'; ?>
<?php //echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?>
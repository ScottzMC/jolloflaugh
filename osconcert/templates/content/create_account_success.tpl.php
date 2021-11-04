<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
	<div class="section-header">
	<h2><?php echo HEADING_TITLE; ?></h2>
	</div>
		<table width="100%" cellpadding="4">
			<tr>
			  <td valign="top">
			  
			  <div><?php echo tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/'.COMPANY_LOGO, HEADING_TITLE,'',''); ?>
			  </div>
				
				</td>
		  </tr>
			<td valign="top" class="main"><?php 
			if(REVIEW_ACCOUNT=='yes'){
				
				echo '<h2>' . HEADING_UNDER_REVIEW .'</h2>';
				echo '<h3>' . TEXT_UNDER_REVIEW .'</h3>';
				
				//echo TEXT_ACCOUNT_REVIEW;
			}else{
				echo TEXT_ACCOUNT_CREATED;
			}
			 ?>
			 </td></tr>
		</table>
		<table width="100%" cellpadding="2">
		  <tr>
			<td align="right">
			
			<?php 
			if (sizeof($navigation->snapshot) > 0) 
				echo '<a href="javascript:if(document.frm_login_post) frm_login_post.submit();">' ;
			else
				echo '<div style="float:right"><a href='.$origin_href.'>';
			
			echo tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a></div>'; ?>
		</table>
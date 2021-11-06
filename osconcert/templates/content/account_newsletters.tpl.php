<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

echo tep_draw_form('account_newsletter', tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); ?>

<div class="section-header">
<h2><?php echo HEADING_TITLE; ?></h2>
</div>
<b><?php echo MY_NEWSLETTERS_TITLE; ?></b>
				<table width="100%" cellpadding="2">
				  <?php if(ACCOUNT_NEWSLETTER=='true')
				  {
					  ?>
				  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="checkBox('newsletter_general')">
                    <td class="main"><?php echo tep_draw_checkbox_field('newsletter_general', '1', (($newsletter['customers_newsletter'] == '1') ? true : false), 'onclick="checkBox(\'newsletter_general\')"'); ?></td>
                    <td class="main"><b><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER; ?></b></td>
                  </tr>
                  <tr>
                    <td class="main">&nbsp;</td>
                    <td><table cellpadding="2">
					  <tr>

                        <td class="main"><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER_DESCRIPTION; ?></td>

                      </tr>
                    </table></td>
                  </tr>
				  <?php 
				  }
					?>
              </tr>
            </table>
			<br>
			
			<table width="100%" cellpadding="2">
              <tr>
            
                <td>
				<?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right">
				<?php echo tep_template_image_submit('', IMAGE_BUTTON_CONTINUE); ?></td>
              
              </tr>
            </table>
</form>

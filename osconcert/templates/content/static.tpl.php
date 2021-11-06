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
    <table width="100%">

      <tr>
        <td><table class="main-table"></td>
      </tr>
 
      <tr>
        <td><table width="100%">

          <tr>
		  	<td><table cellpadding="4">
				<tr>
		            <td class="main"><?php 
					if(isset($affinfo) && $affinfo){ 
					$content=file_get_contents(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . "/images/affiliate.html");
					$content=str_replace("{{AFFILIATE_LOGO}}",tep_template_image("aff_home_logo.jpg",""),$content);
					$content=str_replace("{{IMG_BULLET}}",tep_template_image("icon_arrow_yellow.gif",""),$content);
					$content=str_replace("{{IMG_BULLET1}}",tep_template_image("icon_arrow_lightyellow.gif",""),$content);
					$content=str_replace("{{IMG_BULLET2}}",tep_template_image("icon_arrow.gif",""),$content);
					$content=str_replace("{{IMG_EMAIL}}",tep_template_image("img_email.gif","","id='img_email' style='visibility:hidden'"),$content);
					echo $content;
					}	else {
						if($affiliate_result['message_text']=='')
							echo TEXT_INFORMATION;
						else 
							echo $affiliate_result['message_text'];
						}	
						
					 ?></td>
				</tr>
			</table></td>		
          </tr>

        </table></td>
      </tr>
    </table>


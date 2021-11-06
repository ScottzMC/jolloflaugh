<?php
/* 
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	http://www.osconcert.com

  	Copyright (c) 2020 osConcert 
	
	Released under the GNU General Public License 
*/ 
?>
<!-- header //-->
<div style="width: 100%;">
        
        <div class="header_background" style="width:100%;">
            <ul style="float: left; width: 100%;">
                <li style="width: 200px; height: 27px; float: left;">&nbsp;</li>
                <li class="headerTextNormal" id="step_install_text">Installation</li>
                <li class="headerTextNormal" id="step_config_text">Configuration</li>
                <li class="headerTextNormal" id="step_complete_text">Complete</li>
                <li style="width: 260px; height: 27px; float: left;">&nbsp;</li>
                
            </ul>
            <ul style="float: left; width: 100%; margin-bottom: 0px; padding-top: 2px;">
                <li style="width: 200px; height: 27px; float: left;">&nbsp;</li>
                <li class="headerTextNormal" id="step_install_text" ><img src="images/bullet_normal.gif" alt="" style="position:relative;top:12;" id="step_install_img"/></li>
                <li class="headerTextNormal" id="step_config_text"><img src="images/bullet_normal.gif" alt="" style="position:relative;top:12;" id="step_install_img"/></li>
                <li class="headerTextNormal" id="step_complete_text"><img src="images/bullet_normal.gif" alt="" style="position:relative;top:12;" id="step_install_img"/></li>
                <li  style="width: 260px;"></li>
            </ul>
       
        </div>
       
         <div style="float: left; width: 100%; border-bottom:solid 3px #FFFFFF; margin-top: -20px;">&nbsp;</div> 
<!--	<table width="100%" cellpadding="0" cellspacing="0" border="0" class="header_background">
	   <tr>
			<td width="200" height="27"></td>
		 <td class="headerTextNormal" id="step_install_text">Installation</td>
			<td class="headerTextNormal" id="step_config_text">Configuration</td>
			<td class="headerTextNormal" id="step_complete_text">Complete</td>
			<td>&nbsp;</td>
			<?php //tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/' .COMPANY_LOGO, HEADING_TITLE); ?>
			<td width="240" align="right"></td>
			<td width="20"></td>
	   </tr>
	   <tr  height="15">
		<td class="header_line">&nbsp;</td>
		<td class="header_line"><img src="images/bullet_normal.gif" alt="" style="position:relative;top:12;" id="step_install_img"/></td>
		<td class="header_line"><img src="images/bullet_normal.gif" alt="" style="position:relative;top:12;" id="step_config_img"/></td>
		<td class="header_line"><img src="images/bullet_normal.gif" alt="" style="position:relative;top:12;" id="step_complete_img"/></td>
		<td class="header_line">&nbsp;</td>
		<td class="header_line">&nbsp;</td>
		<td class="header_line">&nbsp;</td>
	   </tr>
	</table>-->
         <div style="float: left;width:100%;" class="content_background">	
<?php 
function current_version(){
	$cur_content=@file_get_contents("../version.txt");
	$cur_content=strtolower($cur_content);
	$start_pos=strpos($cur_content,"<program_version>");
	if ($start_pos===false) return;
	$end_pos=strpos($cur_content,"</program_version>");
	if ($end_pos===false) return;
	$cur_version=substr($cur_content,$start_pos+17,$end_pos-($start_pos+17));
	$config_version=array();
	return '<b>Version ' . $cur_version . '</b>';
}

?>		
<!-- header_eof //-->

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
<tr height="133">
	<td align="center" bgcolor="#fff">
	<a class="img-fluid" href="<?php echo tep_href_link(FILENAME_DEFAULT); ?>">
	<?php echo tep_image(DIR_WS_IMAGES . 'admin_logo.png', STORE_NAME,'','','align=absmiddle'); ?></a>
	</td>
</tr>
<tr>
	<td>
	
	 <table border="0" cellpadding="0" cellspacing="0" width="100%" id="menu_l1" class="cell_bg_navig_l1" >
		<tr>
			<td valign="top">
				<?php  draw_panel_handle_start("Quick Links","ico_quick_links.gif");?>

		<?php
		
			$quick_links=tep_db_query("SELECT filename,params,title,sort_order from " . TABLE_QUICK_LINKS . " where login_group_id='". tep_db_input($FSESSION->login_groups_id) ."' order by sort_order limit 10");
			
			if (tep_db_num_rows($quick_links)>0)
			{
				while($quick_result=tep_db_fetch_array($quick_links))
				{				
					$temp_link=tep_href_link($quick_result["filename"],$quick_result["params"] . (($quick_result['params']!='')?'&top=1':'top=1'));
		?>
			<tr>
				<td valign="top"><?php echo '<a href="' . $temp_link . '" title="'. $quick_result['title'].'">' . substr($quick_result["title"],0,15) .'</a>';?></td>
			</tr>
		<?php } ?>
			<tr>
				<td valign="top"><?php echo '<a href="'. tep_href_link(FILENAME_QUICK_LINKS,'rebuild=1'). '">...</a>';?></td>
			</tr>	
		<?php	
			}
		draw_panel_handle_end();
		?>
		<tr>
			<td valign="top">
			<?php draw_panel_handle_start(TOOLS,"ico_tools.gif"); ?>
                <tr height="20">
					<td><a href="configuration.php?gID=917top=1&mPath=11_152" title="<?php echo 'osConcert ' .SETTINGS;?>"><?php echo SETTINGS;?></a></td>
				</tr>
			<?php draw_panel_handle_end(); ?>
			</td>
		</tr>
		<tr>
			<td valign="top">
		<?php 	
		//echo tep_get_mpath(190);
			draw_panel_handle_start("Menus","ico_menu.gif");
			$left_menu=&$menu_arr["1"];
			reset($left_menu);
			//FOREACH
			//while(list($key,$value)=each($left_menu)){
			foreach($left_menu as $key => $value) {
				if ($value["params"]!='') $value['params'].="&";//if params is occupied	
				
				//$value["params"].="from=col&top=1&mPath=" . $value['path'];				
		?>
		
			<tr>
				<td>
				<?php echo '<a ' .($value["select"]?'class="menu_l1_select"':'') . ' href="' . tep_href_link($value["file"],$value["params"]) . '">' . trim($value["text"]) . '</a>'; ?>
				</td>
			</tr>
		<?php 		
				
				} //while
				echo '<tr></tr>';
				draw_panel_handle_end();
		?>
			</td>
		</tr>
		<tr>
			<td valign="top">
			<?php 	draw_panel_handle_start(SUPPORT,"ico_help.gif"); ?>
				<tr height="20">
					<td><?php echo '<a target="_blank" href="' . tep_catalog_href_link('index.php') . '">' . FRONT_END . '</a>';?></td>
				</tr>
                <tr height="20">
					<td><a href="help_manuals.php" title="<?php echo HELP_MANUALS;?>"><?php echo HELP_MANUALS;?></a></td>
				</tr>
                <tr height="20">
					<td><a target="_blank" href="https://www.osconcert.com/faq" title="<?php echo SUPPORT_DESC;?>"><?php echo SUPPORT;?></a></td>
				</tr>
				<tr height="20">
					<td><a target="_blank" href="https://www.osconcert.com" title="osConcert Seat Booking Software">osConcert</a></td>
				</tr>
			<?php draw_panel_handle_end(); ?>
			
			
				</td>
		</tr>
	</table>

	</td>
</tr>
		<tr height="20">
			<td>&nbsp;</td>
		</tr>
	
<?php
/*

  

  Freeway eCommerce from ZacWare
  http://www.openfreeway.org

  Copyright 2007 ZacWare Pty. Ltd

  Released under the GNU General Public License
*/
	// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	require('includes/application_top.php');
	require('includes/languages/'. $FSESSION->language .'/cms_level_pages_update.php');
	$languages = tep_get_languages();
	$server_date = getServerDate(true);	
	$has_childs = false;
	$has_pages = false; 
	tep_get_last_access_file();// get input entries to find type of entry
	$action = $FREQUEST->getvalue('action');
	$action_value =  $FREQUEST->getvalue('action_value');
	$page_id=$FREQUEST->getvalue('pID');
	if($page_id=='')
	$page_id=$FREQUEST->getvalue('page_id');  
	$sort=$FREQUEST->getvalue('sort','string','asc');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript" src="includes/http.js"></script>

<?php  // WebMakers.com Added: Java Scripts   
      include(DIR_WS_INCLUDES . 'javascript/' . 'webmakers_added_js.php')
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<table border="0" width="100%" cellspacing="5" cellpadding="4">
 <tr>
   <td width="100%" valign="top">
    <!-- Ajax Work Starts -->
  <table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td style="padding-left:10px;padding-top:10px;padding-bottom:10px;" class="pageHeading"><?php echo HEADING_TITLE; ?></td>
	</tr>
  </table>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<?php echo cms_main_page(); ?>
	</tr>
	<tr><td><?php echo tep_draw_separator('pixel_trans.gif', '2', '10'); ?></td></tr>	 
	<tr>
		<td>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="5" cellspacing="0">
							<tr>
								<td>
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										 <tr class="product_title">
											 <td colspan="2"><?php echo '<b>' . TEXT_NEWS . '</b>'; ?></td>
										  </tr>	
										  <tr height="7"></tr>	
										  <tr>
											  <td valign="top" width="5%">
													<table border="0" cellpadding="0" cellspacing="0">
													<tr> 
													<td height="79px" width="59px" align="center" valign="top"><?php echo tep_image(DIR_WS_IMAGES . 'categories/news.png', News);?></td>
													</tr>
													</table>
											   </td> 
														
											   <td width="95%" valign="top">
												 	<table cellpadding="1" cellspacing="3" border="0" width="100%" class="info_content">
														<tr>
													 		<td class="info_content" width="30%"><?php echo '<a href="'. tep_href_link(FILENAME_NEWSDESK,'top=1').'">'. BOX_NEWSDESK . '</a>';?></td>
													 		<td class="info_content" width="70%"><?php echo '<a href="'. tep_href_link(FILENAME_NEWSDESK_REVIEWS,'top=1').'">'. BOX_NEWSDESK_REVIEWS . '</a>';?></td>
														</tr>
														 <tr>	
													 		<td class="info_content" width="30%"><?php echo '<a href="'. tep_href_link(FILENAME_NEWSDESK_CONFIGURATION,'top=1&gID=3').'">'. TEXT_REVIEWS_SETTINGS . '</a>';?></td>
													 		<td class="info_content" width="70%"><?php echo '<a href="'. tep_href_link(FILENAME_NEWSDESK_CONFIGURATION,'top=1&gID=1').'">'. TEXT_LISTING_SETTINGS . '</a>';?></td>
												 		</tr>	
														 <tr>
													 		<td class="info_content" width="30%"><?php echo '<a href="'. tep_href_link(FILENAME_NEWSDESK_CONFIGURATION,'top=1&gID=2').'">'. TEXT_FRONTPAGE_SETTINGS . '</a>';?></td>
															<td class="info_content" width="70%"><?php echo '<a href="'. tep_href_link(FILENAME_NEWSDESK_CONFIGURATION,'top=1&gID=4').'">'. TEXT_STICKY_SETTINGS . '</a>';?></td>
												 		</tr>	
													 </table>
											   </td>
										 </tr>	
									</table>
								 </td>
							 </tr>
							 <tr>
								 <td>
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr class="product_title">
											 <td colspan="2"><?php echo '<b>' .TEXT_FAQ . '</b>'; ?></td>
										</tr>	
										<tr height="7"></tr>	
										<tr>
											 <td valign="top" width="5%">
													<table border="0" cellpadding="0" cellspacing="0">
													<tr> 
													<td height="79px"  width="59px" align="center" valign="top"><?php echo tep_image(DIR_WS_IMAGES . 'categories/faq.png', FAQ);?></td>
													</tr>
													</table>
											 </td> 
														
											 <td width="95%" valign="top">
												 <table cellpadding="1" cellspacing="3" border="0" width="100%" class="info_content">
										   			 <tr>
														<td class="info_content" width="30%"><?php echo '<a href="'. tep_href_link(FILENAME_FAQDESK_REVIEWS,'top=1').'">'. BOX_NEWSDESK_REVIEWS . '</a>';?></td>
														<td class="info_content" width="70%"><?php echo '<a href="'. tep_href_link(FILENAME_FAQDESK_CONFIGURATION,'top=1&gID=1').'">'. TEXT_LISTING_SETTINGS . '</a>';?></td>
													 </tr>	
													 <tr>
														<td class="info_content" width="30%"><?php echo '<a href="'. tep_href_link(FILENAME_FAQDESK_CONFIGURATION,'top=1&gID=3').'">'. TEXT_REVIEWS_SETTINGS . '</a>';?></td>
														<td class="info_content" width="70%"><?php echo '<a href="'. tep_href_link(FILENAME_FAQDESK_CONFIGURATION,'top=1&gID=4').'">'. TEXT_STICKY_SETTINGS . '</a>';?></td>
											   		</tr>	
											  		<tr>
												    	<td class="info_content" width="30%"><?php echo '<a href="'. tep_href_link(FILENAME_FAQDESK_CONFIGURATION,'top=1&gID=2').'">'. TEXT_FRONTPAGE_SETTINGS . '</a>';?></td>
												    	<td class="info_content" width="70%"><?php echo '<a href="'. tep_href_link(FILENAME_FAQDESK_CONFIGURATION,'top=1&gID=5').'">'. TEXT_OTHER_SETTINGS . '</a>';?></td>
											 	   </tr>	
												</table>
											</td>
									   </tr>
								   </table>	
								 </td>
							</tr>
							
						    <tr>
							   <td>
								  <table cellpadding="0" cellspacing="0" border="0" width="100%">
									  <tr class="product_title">
										<td colspan="2"><?php echo '<b>' .TEXT_GENERAL . '</b>'; ?></td>
									  </tr>	
									  <tr height="7"></tr>	
									  <tr>
										 <td valign="top" width="5%">
											<table border="0" cellpadding="0" cellspacing="0">
												<tr> 
													<td height="79px"  width="59px" align="center" valign="top"><?php echo tep_image(DIR_WS_IMAGES . 'categories/general.png', General);?></td>
												</tr>
											</table>
										 </td> 
								  
									 	 <td width="95%" valign="top">
								 		 	 <table cellpadding="1" cellspacing="3" border="0" width="100%" class="info_content">
								    			<tr>
													<td class="info_content" colspan="2" ><?php echo '<a href="'. tep_href_link(FILENAME_DEFINE_MAINPAGE,'top=1').'">'. HEADING_TITLE . '</a>';?></td>
								    			</tr>
								 			 </table>
										 </td>
									 </tr> 
						         </table>
							   </td>
						   </tr>		  	
						
					 </table>
				  </td>
			   </tr>
			</table>		
		 </td>
	 </tr>
</table>


<!-- Ajax Work Ends -->


<!-- body_eof //-->
	</tr>
  </table>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); 

	function cms_main_page(){
	global $FSESSION;
	$cms_query=tep_db_query("select mp.page_id,mpd.page_name,mp.page_status from ".TABLE_MAINPAGE." mp, ".TABLE_MAINPAGE_DESCRIPTIONS." mpd where mp.page_id=mpd.page_id and mpd.language_id='" . (int)$FSESSION->languages_id . "' and mp.parent_id='0' order by mp.sort_order");

		while($cms_array=tep_db_fetch_array($cms_query)){
		$sub_page_id=$cms_array['page_id'];
		echo '<tr height="20px" style="cursor:pointer;" onClick="javascript: location.href=\' '.tep_href_link('cms_level_pages.php','level=1&top=1&mPath=6_54&page_id='.$sub_page_id).'\'"><td class="contentTitle">'. (($cms_array['page_status']==1)? tep_image(DIR_WS_IMAGES.'template/icon_active.gif') : tep_image(DIR_WS_IMAGES.'template/icon_inactive.gif')).'&nbsp;&nbsp;'.$cms_array['page_name'].'</td></tr> <tr height="10"><td></td></tr>';
		//sub_page_description($sub_page_id);
		}
	}

	function sub_page_description($sub_page_id){
	global $FSESSION;
	$sub_page_query=tep_db_query("select mp.page_id,mp.page_status,mpd.page_name from ".TABLE_MAINPAGE." mp, ".TABLE_MAINPAGE_DESCRIPTIONS." mpd where mp.page_id=mpd.page_id and mpd.language_id='" . (int)$FSESSION->languages_id . "' and mp.parent_id='" . tep_db_input($sub_page_id) . "' order by mp.sort_order");
	$cnt=0;
		while($sub_page_array=tep_db_fetch_array($sub_page_query)){
		$class_name=($cnt%2==0)?'dataTableRowEven':'dataTableRowOdd'; $page_id=$sub_page_array['page_id'];
		echo ' <tr height="20px" onClick="javascript: location.href=\' '. tep_href_link(FILENAME_INFORMATION_PAGES,'level=2&top=1&mPath=6_55&page_id='.$page_id).'\'"  class="'.$class_name.'" onMouseOver="rowOverEffect(this);" onMouseOut="rowOutEffect(this);">
		<td  style="padding-left:20px;">'. (($sub_page_array['page_status']==1)? tep_image(DIR_WS_IMAGES.'template/icon_active.gif') : tep_image(DIR_WS_IMAGES.'template/icon_inactive.gif')).'&nbsp;&nbsp;' . $sub_page_array['page_name'] . ' </td>
		</tr> ';

		}
	}
	?>
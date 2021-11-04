<?php
/*
  

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
http://www.openfreeway.org

Copyright 2007 ZacWare Pty. Ltd
*/
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }
  if($FSESSION->new_product) $FSESSION->set('new_product',"");
?>
<script language="javascript">
	var currentMenu;
	var cEvent;
	var currentPath;
	var prev_focus_id;
	var IE = document.all?true:false;
	function show_menu(path){
		//hide_cpopup(true);
		if (currentMenu){
			hide_popup(true);
		}
		show_pop=document.getElementById("pop_"+path);
		
		path_splt=path.split("_");
		currentMenu=path_splt[1];
		currentPath=path;
		var ele1=document.getElementById("menu_l2_"+currentMenu+"_1");
		var ele2=document.getElementById("menu_l2_"+currentMenu+"_2");
		var ele3=document.getElementById("menu_l2_"+currentMenu+"_3");
		var anc=document.getElementById("menu_l2_"+currentMenu+"_a");
		var tclass="";
		var tclass1="";
		ele1.prev_class=ele1.className;
		ele2.prev_class=ele2.className;
		ele3.prev_class=ele3.className;
		ele1.prev_text=ele1.innerHTML;
		if (ele1.innerHTML=="+"){
			tclass="menu_l2_hover";
			tclass1="menu_l2_hover_m";
		} else {
			tclass="menu_l2_select";
			tclass1="menu_l2_hover_nom";
		}
		ele1.innerHTML='&nbsp;';
		ele1.className=tclass+"_left";
		ele2.className=tclass+"_right";
		ele3.className=tclass1;
		anc.prev_class=anc.className;
		anc.className="menu_l2_select_hover";
		
		if (!show_pop) return;
		show_popup(show_pop);
		
		if (!IE) document.captureEvents(Event.MOUSEMOVE);
	    document.onmousemove=check_menu;
	}
	function check_menu(e){
		if(typeof(currentMenu)!="undefined"){
			var con=document.getElementById("menu_l2_"+currentMenu+"_con");
			if (IE) { // grab the x-y pos.s if browser is IE
				tempX = event.clientX + document.body.scrollLeft;
				tempY = event.clientY + document.body.scrollTop;
			}
			else {  // grab the x-y pos.s if browser is NS
				tempX = e.pageX;
				tempY = e.pageY;
			}
			//document.getElementById("test_element").innerHTML=tempY<=con.pos_y1;
			if (!(tempX>=con.pos_x && tempX<=con.pos_x1 && tempY>=con.pos_y && tempY<=con.pos_y1)){
				hide_popup(true);
			}
		}
	}
	function hide_menu(path){
		path_splt=path.split("_");
		currentMenu=path_splt[1];
		show_pop=document.getElementById("pop_"+path);
		if (!show_pop) return;
		hide_popup(true);
	}
	function show_popup(){ 
		if(typeof(currentMenu)!="undefined"){
			show_pop=document.getElementById("pop_"+currentPath);
			if (!show_pop) return;
			var con=document.getElementById("menu_l2_"+currentMenu+"_con");
			var ele2=document.getElementById("menu_l2_"+currentMenu+"_2");		
			if (!con.pos_x){
				pos=getAnchorPosition("menu_l2_"+currentMenu+"_con");
				var last=document.getElementById("menu_l2_"+currentMenu+"_last");
				if (last){
					if (!ele2.pos_x) {
						var pos1=getAnchorPosition("menu_l2_"+currentMenu+"_2");
						ele2.pos_x=pos1.x;
					}
					con.pos_x=(parseInt(ele2.pos_x)+18)-parseInt(show_pop.style.width);
				} else {
					con.pos_x=pos.x;
				}
				con.pos_y=pos.y;
				con.pos_x1=con.pos_x+parseInt(show_pop.style.width)+2;
				con.pos_y1=con.pos_y+parseInt(show_pop.style.height)+40;
			}
			//show_pop.style.le
			show_pop.style.left=con.pos_x+2;
			show_pop.style.top=con.pos_y+22;
			show_pop.style.display="";
		}
	}
	function hide_popup(check_flag){
		show_pop=document.getElementById("pop_"+currentPath);
		if (!check_flag && show_pop) return;
		
		if (show_pop){
			document.onmousemove='';
			if (!IE) document.releaseEvents(Event.MOUSEMOVE);
			show_pop.style.display="none";
		}
		if(typeof(currentMenu)!="undefined"){
			var ele1=document.getElementById("menu_l2_"+currentMenu+"_1");
			var ele2=document.getElementById("menu_l2_"+currentMenu+"_2");
			var ele3=document.getElementById("menu_l2_"+currentMenu+"_3");
			var anc=document.getElementById("menu_l2_"+currentMenu+"_a");
			ele1.className=ele1.prev_class;
			ele2.className=ele2.prev_class;
			ele3.className=ele3.prev_class;
			ele1.innerHTML=ele1.prev_text;
		}
		anc.className=anc.prev_class;
		currentMenu=0;
		currentPath="";
		return false;
	}
function getAnchorPosition(anchorname) {
	// This function will return an Object with x and y properties
	var useWindow=false;
	var coordinates=new Object();
	var x=0,y=0;
	// Browser capability sniffing
	var use_gebi=false, use_css=false, use_layers=false;
	if (document.getElementById) { use_gebi=true; }
	else if (document.all) { use_css=true; }
	else if (document.layers) { use_layers=true; }
	// Logic to find position
 	if (use_gebi && document.all) {
		x=AnchorPosition_getPageOffsetLeft(document.all[anchorname]);
		y=AnchorPosition_getPageOffsetTop(document.all[anchorname]);
		}
	else if (use_gebi) {
		var o=document.getElementById(anchorname);
		x=AnchorPosition_getPageOffsetLeft(o);
		y=AnchorPosition_getPageOffsetTop(o);
		}
 	else if (use_css) {
		x=AnchorPosition_getPageOffsetLeft(document.all[anchorname]);
		y=AnchorPosition_getPageOffsetTop(document.all[anchorname]);
		}
	else {
		coordinates.x=0; coordinates.y=0; return coordinates;
		}
	coordinates.x=x;
	coordinates.y=y;
	return coordinates;
	}
// Functions for IE to get position of an object
function AnchorPosition_getPageOffsetLeft (el) {
	var ol=el.offsetLeft;
	while ((el=el.offsetParent) != null) { ol += el.offsetLeft; }
	return ol;
	}
function AnchorPosition_getPageOffsetTop (el) {
	var ot=el.offsetTop;
	while((el=el.offsetParent) != null) { ot += el.offsetTop; }
	return ot;
	}

	function toggle_left_panel(panelid){
		var element=document.getElementById("panel_"+panelid);
		var img=document.getElementById("img_"+panelid);
		if (element.style.display=="none"){
			element.style.display="";
			img.src="images/template/ico_arrow_up.gif";
		} else {
			element.style.display="none";
			img.src="images/template/ico_arrow_down.gif";
		}
	}	
	function toggle_panel(title,funname,hide_prev,expand){				
		var panel=document.getElementById("panel_"+title);
		if(panel){
			if (panel.style.display=="none"){
				panel.style.display="";
				if (title=='Description' && !panel.editor){
					editor_init();
					panel.editor=true;
				}
			} else panel.style.display="none";
		}
		if(hide_prev==true)
		{
			if(prev_focus_id!="" && prev_focus_id!=title)
			{
				if(document.getElementById("img_"+prev_focus_id))
				{
					//var img=document.getElementById("img_"+prev_focus_id);
					var panel=document.getElementById("panel_"+prev_focus_id);
					panel.style.display="none";
					//img.src="images/template/panel_down.gif";
				}
			}
			prev_focus_id=title;
		}
		if(typeof(display_category) != "undefined"){
			panel_expand="panel_" + title;
			disp(panel);
		}
		if(parseInt(expand)>0)
			panel_expand_id = expand;
		//if(funname==true){	
		if(title=='Attribute_Inventory_control'){
			doaction();
		}
	}
	function toggle_panel1(title,funname,hide_prev,expand){				
		var img=document.getElementById("img_"+title);
		var panel=document.getElementById("panel_"+title);
		if (panel.style.display=="none"){
			img.src="images/template/panel_up.gif";
			panel.style.display="";
		} else {
			panel.style.display="none";
			img.src="images/template/panel_down.gif";
		}
		if(hide_prev==true)
		{
			if(prev_focus_id!="" && prev_focus_id!=title)
			{
				if(document.getElementById("img_"+prev_focus_id))
				{
					var img=document.getElementById("img_"+prev_focus_id);
					var panel=document.getElementById("panel_"+prev_focus_id);
					panel.style.display="none";
					img.src="images/template/panel_down.gif";
				}
			}
			prev_focus_id=title;
		}
		if(typeof(display_category) != "undefined"){
			panel_expand="panel_" + title;
			disp(panel);
		}
		if(parseInt(expand)>0)
			panel_expand_id = expand;
			
		if(title=='Attribute_Inventory_control'){
			doaction();
		}
		var jump_select=""; 
		if(document.form_jump && document.form_jump.category)
		{
		jump_select=document.form_jump.category;
		jump_select.value=title+'#'+expand;
		}
	}
	// For category panel
	var prev_category='';
	var open_level=0;
	var open_ids=Array();
	function toggle_category_panel(category_id,level,opn){ 
		var res_display=document.getElementById("res_display");		
		if(res_display && res_display.style.display=="") res_display.style.display="none";
		var img=document.getElementById("panel_"+category_id+"_img");
		var panel=document.getElementById("panel_"+category_id+"_content");
		
		show=false;		
		if (panel && panel.style.display=="none"){
			show_cat_panel(category_id);
			show=true;
		} else {
			//if (opn!=true && open_ids[level] && open_ids[level]==category_id && level==0) return;
			hide_cat_panel(category_id);
			
		}
		if (show){
			if (prev_category!=""){
				if (level<=open_level){
					hide_cat_panel(open_ids[level]);
					open_ids=open_ids.slice(0,level);
				}
			}
			open_level=level;
			prev_category=category_id;
			open_ids[level]=category_id;
			do_page_fetch("fetch_sub_list","",category_id,1,level+1);
		} else {
			open_ids=open_ids.slice(0,level);
			open_level=level-1;
		}		
	}
	function hide_cat_panel(category_id){
		var img=document.getElementById("panel_"+category_id+"_img");
		var panel=document.getElementById("panel_"+category_id+"_content");
		if(panel) {
			panel.style.display="none";
			panel.innerHTML="";	
		}
		if(img) img.src="images/template/panel_down.gif";
	}
	function show_cat_panel(category_id){
		var img=document.getElementById("panel_"+category_id+"_img");
		var panel=document.getElementById("panel_"+category_id+"_content");
		if (panel) panel.style.display="";
		if (img) img.src="images/template/panel_up.gif";
	}

	function toggle_focus(element,mode){
		if (mode==2){
			element.className="inputSelect";
		//For IE only
		if(element.type == "text" || element.type == "password")var intID=setInterval(function(){element.focus();clearInterval(intID);},10); //For IE only
		} else {
			element.className="inputNormal";
		}
	}
	var current_cmenu="";
	// Common popup menu
	function show_cpopup(menu_id,adjust_x,adjust_y){
		show_pop=document.getElementById("pop_common_"+menu_id);
		var con=document.getElementById("pop_common_"+menu_id+"_con");
		if (!con.pos_x){
			pos=getAnchorPosition("pop_common_"+menu_id+"_con");
			con.pos_x=pos.x;
			con.pos_y=pos.y;
			con.pos_x1=con.pos_x+parseInt(show_pop.style.width);
			con.pos_y1=con.pos_y+parseInt(show_pop.style.height)+20;
		}

		show_pop.style.left=con.pos_x+adjust_x;
		show_pop.style.top=con.pos_y+adjust_y;
		show_pop.style.display="";
		current_cmenu=menu_id;
		if (!IE) document.captureEvents(Event.MOUSEMOVE);
	    document.onmousemove=check_cpopup;
	}
	function check_cpopup(e){
		if(currentMenu=="") return;
		
		var con=document.getElementById("pop_common_"+current_cmenu+"_con");
		if (IE) { // grab the x-y pos.s if browser is IE
			tempX = event.clientX + document.body.scrollLeft;
			tempY = event.clientY + document.body.scrollTop;
		}
		else {  // grab the x-y pos.s if browser is NS
			tempX = e.pageX;
			tempY = e.pageY;
		}
		//document.getElementById("test_element").innerHTML=tempY<=con.pos_y1;
		if (!(tempX>=con.pos_x && tempX<=con.pos_x1 && tempY>=con.pos_y && tempY<=con.pos_y1)){
			hide_cpopup(true);
		}
	}
	function hide_cpopup(check_flag){ 
		show_pop=document.getElementById("pop_common_"+current_cmenu);
		if (!check_flag && show_pop) return;
		
		if (show_pop){
			document.onmousemove='';
			if (!IE) document.releaseEvents(Event.MOUSEMOVE);
			show_pop.style.display="none";
		}
		current_cmenu="";
	}
	function validate_search(){
		var phrase  = document.search_links.search_link.value;
		if(phrase=='') return false;
		else 	document.search_links.submit();
		
	}
</script>
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign="top" width="120" nowrap="nowrap">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
		</table>
		</td>		
		
		<td id="formtable" valign="top">
		<table border="0" style="vertical-align:top;" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td valign="top">
				<!--start--><table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top">
						<table border="0" width="100%" cellpadding="0" cellspacing="0"  class="cell_bg_navig">
						<tr height="25">
								<td id="headerNavigation" nowrap="nowrap">&nbsp;&nbsp;
									<img src="images/template/ico_home.gif"/>
									<?php echo $breadcrumb->trail('&nbsp;&nbsp;' .tep_image(DIR_WS_IMAGES . 'template/ico_navig.gif', 'SSL') . '&nbsp;&nbsp;'); ?>
								</td>
								<td align="right" nowrap="nowrap">
								<table width="98%" border="0" cellpadding="2" cellspacing="2" id="navig_row"><tr>	<?php 
																		 
									 //echo tep_draw_form('add_links', FILENAME_QUICK_LINKS, '' ,'post');
									 $hlink='<div id="quick_action_delete" style="display:none"><a href="javascript:add_links.submit();"> Delete from Quick Links </a></div><div id="quick_action_insert" style="display:none"><a href="javascript:add_links.submit();"> Add to Quick Links </a></div>';									 
													$top=$FREQUEST->getvalue('top');
													if( ($top) && ($top=='1') && (basename($PHP_SELF)!=FILENAME_QUICK_LINKS) ) { 
													if($FSESSION->is_registered('filename')) $FSESSION->remove('filename') ;
													if($FSESSION->is_registered('params')) $FSESSION->remove('params');
													$filename=basename($PHP_SELF);													
													$params=tep_get_all_get_params();
													if(($params!='') && (substr($params,-1)=='&')){  
													 	$params=substr($params,0,-1);
													}
													$FSESSION->set('filename',$filename);
													$FSESSION->set('params',$params);
													//tep_session_register('filename');
													//tep_session_register('params');
													$links_select_query=tep_db_query('select * from '. TABLE_QUICK_LINKS ." where filename='".tep_db_input($filename). "' and params='".tep_db_input($params)."' and login_group_id='". (int)$FSESSION->login_groups_id."'");
												 
												   // echo 'select * from '. TABLE_QUICK_LINKS ." where filename='".$filename. "' and params='".$params."' and login_group_id='". $login_groups_id."'";
												?>
      											<?php  echo tep_draw_form('add_links', FILENAME_QUICK_LINKS, '' ,'post');?>
												<?php 
												   if($FSESSION){
												   //tep_session_register('actions_value');  
												   $FSESSION->set('actions_value','insert');
												   //tep_session_register('delete_value');
												   $FSESSION->set('delete_value','delete');	
												   }else{
												   $FREQUEST->setvalue('actions_value','insert','string');
												   $FREQUEST->setvalue('delete_value','delete','string');
												   }
												?>
										<!--		 <input type="hidden" name="quick" value="insert" id="quicks"/>		-->
										<!--		 <input type="hidden" name="quick1" value="delete" />	-->
												<?php
													if(tep_db_num_rows($links_select_query)>0){
													   $fetch_array = tep_db_fetch_array($links_select_query);
											  ?>
														<input type="hidden" value="delete" name="quick_del" />
									   					<input type="hidden" name="filename" id="filename" value="<?php echo $fetch_array['filename']; ?>" />
									  					<input type="hidden" id="params" name="params" value="<?php echo $fetch_array['params']; ?>" />
									  					<input type="hidden" id="links_id" name="links_id" value="<?php echo $fetch_array['links_id']; ?>" />
														<input type="hidden" value="1" name="top" id="top" />
												<?php		$hlink='<a href="javascript:add_links.submit();" id="quick_action_delete"> ' .DELETE_QUICK_LINKS . ' </a>';
															echo '<input type="hidden" name="quick1" value="delete" id="quick_del"/>';
													  }else{
														    $hlink='<a href="javascript:add_links.submit();" id="quick_action_insert"> ' .  ADD_QUICK_LINKS . ' </a>';	
															echo '<input type="hidden" name="quick" value="insert" id="quick_ins"/>';
													       }
														    ?>	 
													<td width="10%"></form>
								    <?php } ?>
<td width="15%" nowrap="nowrap" align="center">
											<?php echo USER;?> <b><?php echo $FSESSION->login_first_name;?></b>										</td>
						<td nowrap="nowrap"><?php echo '<a href="' . tep_href_link(FILENAME_ADMIN_ACCOUNT, '', 'SSL') . '">' . TEXT_ACCOUNT_SETTINGS . '</a>';?></td>
						<td nowrap="nowrap"><?php echo '<a href="' . tep_href_link(FILENAME_LOGOFF, '', 'SSL') . '">' .SIGNOUT. '</a>';?></td>
						<td nowrap="nowrap"><?php echo '<a href="help_manuals.php">' .HELP. '</a>';?></td>
                        <td nowrap="nowrap"><a target="_blank" href="clear_cache.php" onclick="return confirm('<?php echo ARE_YOU_SURE;?>')"><?php echo TEXT_CLEAR_CACHE;?></a></td>
						<td nowrap="nowrap"><?php echo $hlink;?></td>
					    <td nowrap="nowrap"><?php echo '<a target="_blank" href="' . tep_catalog_href_link('index.php') . '"><b>' . FRONT_END . '</b></a>';?> </td>
                                      <td width="5%"> </td>
								  </tr>
								</table>
						  </td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td valign="top" class="cell_bg_navig_l21">
						<table border="0" width="100%" cellpadding="0" cellspacing="0" class="cell_bg_navig_l2"  height="34">
							<tr>
							  <td class="page_menu_title"><?php echo HEADING_TITLE;?></td>
								<td class="page_menu_title"></td>
								<?php $return=""; if(basename($PHP_SELF)=='customers_mainpage.php' || basename($PHP_SELF)=='customers.php') $return="return=csl";?>
								<!-- TO DO: SSL NOT WORKING
								<form name="search_links" action="<?php echo tep_href_link(FILENAME_SEARCH_LINKS,$return, 'SSL');?>" method="post" onsubmit="javascript: return validate_search();" >
								<td width="20"><img src="images/template/input_left.gif"/></td>
								<td width="120">
								<input type="text" name="search_link" id="search_link" class="roundText" value="<?php echo tep_output_string($FREQUEST->postvalue('search_link')); ?>" /></td>
								<td width="20"><a><img src="images/template/input_right.gif" onclick="javascript:validate_search();"/></a></td>
								</form>
								-->
								<td width="50">&nbsp;</td>
							</tr>
						</table>
					  </td>
					</tr>
					<tr>
						<td valign="top">
						<table border="0" width="100%" cellpadding="2" cellspacing="0" id="menu_l2" class="cell_bg_navig_l3" height="60">
							<?php
								$level2_menus=array();
								$top_menu=&$menu_arr["2"];
								reset($top_menu);
								$icnt=0;
								$prev_key='';
								//FOREACH
								//while(list($key,$value)=each($top_menu)){
								foreach($top_menu as $key => $value) {
									if ($value["show"] && $value["file"]!=''){
										$level2_menus[]=$key;
										if ($value["params"]!='') $value["params"]=tep_get_expanded_params($value["params"]);

										$value["params"].="top=1&mPath=" . $value['path'];
										$temp_path=$GLOBALS["l1"] . "_" . $key;
										
										if ($icnt==0 || $icnt%4==0) {
											if ($icnt>0) echo '<td id="menu_l2_' . $prev_key .'_last">&nbsp;</td>';
											echo '<tr>';
										}
										$temp_class1="";
										if ($value["select"]){
											$temp_class="menu_l2_select";
											$temp_class1="menu_l2_hover_nom";
										} else {
											$temp_class="menu_l2_normal";
										}
										echo  '<td style="width:220px;" valign="top" id="menu_l2_' . $key .'_con"><table border="0" cellpadding="0" cellspacing="0" height="22"><tr><td width="20" id="menu_l2_' . $key . '_1" class="' . $temp_class . '_left" style="text-align:center">' . (isset($navig_list[$temp_path])?'+':'&nbsp;') .'</td><td id="menu_l2_' . $key . '_3" class="' . $temp_class1 . '">' .
												'<span id="menu_l2_' . $key . '_a" class="' .($value["select"]?'menu_l2_select_hover':'menu_l2_select_normal') . '" onMouseOver="javascript:show_menu(\'' . $temp_path .'\')" onMouseOut="javascript:hide_popup()" onclick="javascript:location.href=\'' . tep_href_link($value["file"],$value["params"], 'SSL') . '\';">' . $value["text"] . '</a></td><td width="20" id="menu_l2_' . $key . '_2" class="' . $temp_class . '_right">&nbsp;</td>' . 
												'</tr></table></td>';
										$prev_key=$key;
										$icnt++;
												
									}
									
								} //while
								echo '<td>&nbsp;</td>';
							?>
						</table>
						<?php
							for ($icnt=0,$n=count($level2_menus);$icnt<$n;$icnt++){
								$menu=$level2_menus[$icnt];
								$temp_path=$GLOBALS["l1"] . "_" . $menu;
								if (isset($navig_list[$temp_path])){
									$sub_list=&$navig_list[$temp_path];
									$n1=count($sub_list);
									$temp_height=ceil($n1/3)*20+10;
									$temp_width=320;
									$temp_count=2;
									if ($n1>=6) {
										$temp_width=450;
										$temp_count=3;
									}
									echo '<div style="width:' . $temp_width . 'px;height:' . $temp_height . 'px;position:absolute;display:none;padding:10px;z-index:999" id="pop_' . $temp_path . '" class="menu_l3">' .
											'<table border="0" cellpadding="2" cellspacing="0" style="z-index:9999">';
									for ($jcnt=0;$jcnt<$n1;$jcnt++){
										$value=$menu_arr["3"][$sub_list[$jcnt]];
										
										if (trim($value["params"])!='') {
											$value["params"]=tep_get_expanded_params($value["params"]);
										}
										$value["params"].="top=1&mPath=" . $value['path'];
										if ($jcnt==0 || $jcnt%$temp_count==0) echo '<tr>';
										//if(!$value['file']) $value['file']=FILENAME_SHOP_ADMIN_MEMBERS;
										
										$show = true;
										if($show)echo  '<td style="width:200px;" valign="top"><a ' .($value["select"]?'class="menu_l3_select"':'') . ' href="' . tep_href_link($value["file"],$value["params"]) . '">' . $value["text"] . '</a></td>';
									}
									echo '</table></div>';
								}
							}
						?>
						
						</td>
					</tr>
				</table>
				</td>
			</tr>
 			<tr>
				<td id="holetable" valign="top" height="600">
				
<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare

    Released under the GNU General Public License
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
	class modulesMainpage
		{
		var $pagination;
		var $splitResult;
		var $type;
		var $set;
		var $modules;
		var $module_directory;
		var $module_details;
		var $module_type;
		var $module_key;
		var $modules_installed;
		var $total_key;

		function __construct() {
		global $FREQUEST;
		$this->pagination=false;
		$this->splitResult=false;
		$this->type='modu';
		$set = $FREQUEST->getvalue('set');
		$this->set=$set;
		switch ($set) {
			case 'shipping':
					$this->module_details["type"] = 'shipping';
					$this->module_details["directory"] = DIR_FS_CATALOG_MODULES . 'shipping/';
					$this->module_details["key"]= 'MODULE_SHIPPING_INSTALLED';
					$this->module_details["installed"]=MODULE_SHIPPING_INSTALLED;
					$this->module_details["total_key"]='MODULE_SHIPPING_%_SORT_ORDER';
					$this->module_type = 'shipping';
					$this->module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';
					$this->module_key = 'MODULE_SHIPPING_INSTALLED';
					$this->modules_installed=MODULE_SHIPPING_INSTALLED;
					$this->total_key='MODULE_SHIPPING_%_SORT_ORDER';
					define('HEADING_TITLE', HEADING_TITLE_MODULES_SHIPPING);
					break;
				case 'ordertotal':
					$this->module_details["type"] = 'order_total';
					$this->module_details["directory"] = DIR_FS_CATALOG_MODULES . 'order_total/';
					$this->module_details["key"] = 'MODULE_ORDER_TOTAL_INSTALLED';
					$this->module_details["installed"]=MODULE_ORDER_TOTAL_INSTALLED;
					$this->module_details["total_key"]='MODULE_ORDER_TOTAL_%_SORT_ORDER';
					$this->module_type = 'order_total';
					$this->module_directory = DIR_FS_CATALOG_MODULES . 'order_total/';
					$this->module_key = 'MODULE_ORDER_TOTAL_INSTALLED';
					$this->modules_installed=MODULE_ORDER_TOTAL_INSTALLED;
					$this->total_key='MODULE_ORDER_TOTAL_%_SORT_ORDER';
					define('HEADING_TITLE', HEADING_TITLE_MODULES_ORDER_TOTAL);
					break;
				case 'sms':
					$this->module_details["type"]='sms';
					$this->module_details["directory"]=DIR_FS_CATALOG_MODULES . 'sms/';
					$this->module_details["key"] = 'MODULE_SMS_GATEWAYS_INSTALLED';
					$this->module_details["installed"]=MODULE_SMS_GATEWAYS_INSTALLED;
					$this->module_details["total_key"]='MODULE_SMS_%_SORT_ORDER';
					$this->module_type='sms';
					$this->module_directory=DIR_FS_CATALOG_MODULES . 'sms/';
					$this->module_key = 'MODULE_SMS_INSTALLED';
					$this->modules_installed=MODULE_SMS_GATEWAYS_INSTALLED;
					$this->total_key='MODULE_SMS_%_SORT_ORDER';
					define('HEADING_TITLE', HEADING_TITLE_MODULES_SMS_GATEWAYS);
					break;
				case 'payment':
				default:
					$this->module_details["type"] = 'payment';
					$this->module_details["directory"] = DIR_FS_CATALOG_MODULES . 'payment/';
					$this->module_details["key"] = 'MODULE_PAYMENT_INSTALLED';
					$this->module_details["installed"]=MODULE_PAYMENT_INSTALLED;
					$this->module_details["total_key"]='MODULE_PAYMENT_%_SORT_ORDER';
					$this->module_type = 'payment';
					$this->module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
					$this->module_key = 'MODULE_PAYMENT_INSTALLED';
					$this->modules_installed=MODULE_PAYMENT_INSTALLED;
					$this->total_key='MODULE_PAYMENT_%_SORT_ORDER';
					define('HEADING_TITLE', HEADING_TITLE_MODULES_PAYMENT);
					break;
			}

		}
		function doSort(){
			global $FREQUEST,$jsData,$FSESSION;
			$mode=$FREQUEST->getvalue('mode','','A');
			$sort='asc';
			if ($mode=="D") $sort="desc";
			if($this->module_type == 'payment')
			{
				$module_key=str_replace('_SORT_ORDER','_DISPLAY_NAME',$this->module_details["total_key"]);
				$module_sort_query=tep_db_query("select configuration_key,configuration_value from ".TABLE_CONFIGURATION ." where configuration_key like '" . tep_db_input($module_key) . "' order by configuration_value " . $sort);
				$order=1;
				while($mod_result=tep_db_fetch_array($module_sort_query)){
					tep_db_query("UPDATE " . TABLE_CONFIGURATION . " set configuration_value=$order where configuration_key='" . str_replace('_DISPLAY_NAME','_SORT_ORDER',$mod_result["configuration_key"]) . "'");
					$order++;
				}
			}
			else {
				$module_key=$this->module_details["total_key"];
				$module_sort_query=tep_db_query("select configuration_key,configuration_value from ".TABLE_CONFIGURATION ." where configuration_key like '" . tep_db_input($module_key) . "' order by configuration_key " . $sort);
				$order=1;
				while($mod_result=tep_db_fetch_array($module_sort_query)){
					tep_db_query("UPDATE " . TABLE_CONFIGURATION . " set configuration_value=$order where configuration_key='" . $mod_result["configuration_key"] . "'");
					$order++;
				}
			}
			$jsData->VARS["NUclearType"]=array("modu");
			$this->doModuleList();
		}
		function doModuleList()
		{
			global $FREQUEST,$PHP_SELF,$FSESSION;?>

			<table border="0" width="100%" cellspacing="0" cellpadding="0" >
			<tr><td id="modulistcontent">
				<?php echo $this->doInstalledModuleList();?>
			</td></tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0" id="moduunlistcontent" >
			<tr><td id="unmodulistcontent">
				<?php echo $this->doUnInstalledModuleList();?>
			</td></tr>
			</table>
			<?php if($this->set=='ordertotal')
			{
		  		$amount_round_value=array();
		  		$amount_round_query=tep_db_query("select * from configuration where configuration_key='EVENTS_ORDER_AMOUNT_ROUND'");
		  		if(tep_db_num_rows($amount_round_query)>0) $amount_round_value=tep_db_fetch_array($amount_round_query);
		  		?>
				<table>
				<tr style="height:15px"><td>&nbsp;</td></tr>
				<tr class="openContent_top" style="height:35px" >
				  <td>
					<form name="frm_round_factor" id="frm_round_factor" action="modules.php" method="post">
					 <table border="0" width="100%" cellspacing="2" cellpadding="2">
						<tr><td colspan="3" class='main'><?php echo TEXT_SETTINGS; ?></td></tr>
						<tr style="height:35px">
							<td width="200" valign="center" class="main" alt="<?php echo $amount_round_value['configuration_description'];?>" title="<?php echo $amount_round_value['configuration_description'];?>"><?php echo $amount_round_value['configuration_title'];?></td>
							<td width="50" align="left"><?php echo tep_cfg_pull_down_round_factors($amount_round_value['configuration_value']);?></td>
							<td width="800" align="left"><span id="img_loading" style="display:none"><?php echo tep_image('images/24-1.gif','Data Saving');?></span></td>
						</tr>
						</table>
					 <input type="hidden" name="config_id" id="config_id" value="<?php echo $amount_round_value['configuration_id'];?>">
					</form>
				  </td>
				</tr>
				</table>
 		 <?php
 		 }
		}
		function doSaveRoundFactor()
		{
			global $FREQUEST;
		 	tep_db_query("update configuration set configuration_value='".$FREQUEST->postvalue('configuration_value')."' where configuration_id='". tep_db_input($FREQUEST->postvalue('config_id'))."'");
		}
		function doInstalledModuleList()
		{
			global $FREQUEST,$PHP_SELF,$FSESSION;
			$directory_array = array();
			$directory_array=$this->get_modules('I');
			$template=getListTemplate();
			echo '<table border="0" width="100%" cellspacing="0" cellpadding="0" id="moduTable">';
			echo "<tr><td class='main'><b><?php echo INSTALLED_MODULES; ?></b></td></tr>";
			if(sizeof($directory_array)>0)
			{
				for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
					$fileo= $directory_array[$i];
					$file = substr($directory_array[$i],4);
					$class = substr($file, 0, strrpos($file, '.'));
					$module = $this->modules[$file];
					$table_id=$this->get_module_distinct_key($module);
					$status=tep_image(DIR_WS_IMAGES . 'template/icon_active.gif');
					//$title=($this->set=="payment"?$module->text_title:$module->title);
					//$description=($this->set=="payment"?$module->text_description:$module->description);
					$display="";
//					if ($this->type=='ordertotal'){
//						$textname=$description;
//					}else{
//						$textname=$title;
//					}
					$rep_array=array("ID"=>($table_id!=''?$table_id:$class),
									"UPDATE_ID"=>$class,
									"TYPE"=>$this->type,
									"NAME"=>($this->set=="payment"?$module->text_title:$module->title),
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"BULLET_IMAGE"=>$status,
									"ROW_CLICK_GET"=>"getModuleDetails",
									"DELETING_MODULE"=>TEXT_DELETING_MODULE,
									"SORTING_DATA"=>TEXT_SORTING_DATA,
									"SET"=>$this->set,
									"FIRST_MENU_DISPLAY"=>$display
								);
					echo mergeTemplate($rep_array,$template);
				}
			}
			else
			{
				echo  "<tr><td class='main'><b><?php echo NO_INSTALLED_MODULES_FOUND; ?></b></td></tr>";
			}
			echo '</table>';

	  }
	  function doUnInstalledModuleList()
	  {
			global $FREQUEST,$PHP_SELF,$FSESSION;
			$directory_array = array();
			$directory_array=$this->get_modules('U');
			$template=getListTemplate();
			echo '<table border="0" width="100%" cellspacing="0" cellpadding="0" id="unmoduTable">';
			echo "<tr><td class='main'><b><?php echo UNINSTALLED_MODS; ?></b></td></tr>";
			if(sizeof($directory_array)>0)
			{
				for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
					$fileo= $directory_array[$i];
					$file = substr($directory_array[$i],4);
					$class = substr($file, 0, strrpos($file, '.'));
					$module = $this->modules[$file];
					$icnt=1;
					$status=tep_image(DIR_WS_IMAGES . 'template/icon_inactive.gif');
					$display="display:none";
					$rep_array=array("ID"=>$class,
									 "UPDATE_ID"=>$class,
									"TYPE"=>'unmodu',
									"NAME"=>($this->set=="payment"?$module->text_title:$module->title),
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"BULLET_IMAGE"=>$status,
									"ROW_CLICK_GET"=>"getModuleDetails",
									"SET"=>$this->set,
									"FIRST_MENU_DISPLAY"=>$display
								);
					echo mergeTemplate($rep_array,$template);
				}
			}
			else
			{
				echo  "<tr><td class='main'><b>No UnInstalled Modules found</b></td></tr>";
			}
			echo '</table>';

	  }
	  function doInstall_module(){
		global $FREQUEST,$jsData;
		$module=$FREQUEST->getvalue('module');
		$select_module=$this->load_module($module);
		if(!$select_module) return;
		$select_module->install();
        $install_module_query=tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key ='" .  $this->module_details["key"] . "'");
        $install_module_result=tep_db_fetch_array($install_module_query);
        $modules_key=$install_module_result['configuration_value'];
        if($modules_key!='') $modules_key.= ';' . $module . '.php';
        else $modules_key= $module . '.php';
        tep_db_query("Update configuration set configuration_value='" . $modules_key . "' where configuration_key='" . $this->module_details["key"] . "'");
		$module_keys=$select_module->keys();
      	$result=tep_db_query("select configuration_key,configuration_value from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $module_keys) . "')");
		while($row=tep_db_fetch_array($result))
		{
			define($row['configuration_key'],$row['configuration_value']);
		}
		echo $this->doInstalledModuleList();
		$jsData->VARS['deleteRow']=array("id"=>$module,"type"=>"unmodu");
		$jsData->VARS["storePage"]=array('lastAction'=>false,'opened'=>array(),'locked'=>false);

	  }
	  function doDelete(){
			global $FREQUEST,$jsData;
			$module=$FREQUEST->getvalue('module');
			$select_module=$this->load_module($module);
			if(!$select_module) return;
			$select_module->remove();
             $install_module_query=tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key ='" .  $this->module_details["key"] . "'");
        $install_module_result=tep_db_fetch_array($install_module_query);
        $modules_key=$install_module_result['configuration_value'];
        if($modules_key!='') $modules_key= str_replace($module . '.php;','',$modules_key .';');
        else $modules_key='';
        if($modules_key!='') $modules_key=substr($modules_key,0,-1);
        tep_db_query("Update configuration set configuration_value='" . $modules_key . "' where configuration_key='" . $this->module_details["key"] . "'");

			$table_id=$this->get_module_distinct_key($select_module);
			echo $this->doUnInstalledModuleList();
			$jsData->VARS['deleteRow']=array("id"=>$table_id,"type"=>"modu");
			$jsData->VARS["storePage"]=array('lastAction'=>false,'opened'=>array(),'locked'=>false);
		}
	function doEdit()
	{
			global $FREQUEST,$jsData;
			$module=$FREQUEST->getvalue('module');
			$select_module=$this->load_module($module);
			if(!$select_module) return;
			$position="edit";
			$result='<div style="margin-top:0px;padding-left:10px;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td class="main" width="20">' . tep_image(DIR_WS_IMAGES . 'template/img_edit.gif') .
							'</td><td class="main">' . TEXT_EDIT .
						'</tr>
					</table>
					' . tep_draw_form("config_input",FILENAME_MODULES,'AJX_CMD=ModuleUpdate&set=' . $set . '&module=' . $module,'post',' enctype="multipart/form-data"') .
					'<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="20">
							<td class="smallText">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td valign="top" width="50%">
									<table border="0" cellpadding="2" cellspacing="0">
					';
			$module_keys=$select_module->keys();

		//	print_r($module_keys);

			$col_total=ceil((count($module_keys)-1)/2);
			//$col_total--;
			$icnt=0;
			$has_images="no";
			$key_value_query = tep_db_query("select configuration_key,configuration_title, configuration_value, configuration_description, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_key  in ('" . join("','",$module_keys) . "') order by sort_order");

		//	echo "select configuration_key,configuration_title, configuration_value, configuration_description, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_key  in ('" . join("','",$module_keys) . "') order by sort_order";

			while($key_value = tep_db_fetch_array($key_value_query)){
				if ($icnt>=$col_total){
					$result.='	</table>
								</td>
								<td valign="top" width="50%">
								<table border="0" cellpadding="2" cellspacing="0">
							';
					$icnt=0;
				}
				$config_input='';

				$key=$key_value["configuration_key"];

				if (strpos($key,"_SORT_ORDER")!==false) continue;
				if($key_value['set_function'] == "tep_cfg_user_defined_text_field(1,") {
				  $config_input = tep_cfg_user_defined_text_field(1,'configuration[' . $key . ']', $key_value['configuration_value']);
				} else if($key_value['set_function'] == "tep_cfg_user_defined_text_field(2,"){
				  $config_input = tep_cfg_user_defined_text_field(2,'configuration[' . $key . ']', $key_value['configuration_value']);
				} else if( ($key_value['set_function'] == "tep_cfg_pull_down_zone_classes(") || ($key_value['set_function'] == "tep_cfg_pull_down_shipping_zone_classes(")){
					$temp_split=preg_split("/_/",$key);
					$country_key=$country_control='default';
					if((strpos($key,"_PAYMENT")!==false || strpos($key,"_SHIPPING")!==false) && strpos($key,"_EXCEPT")===false){
						if($temp_split[3]=='ZONE')
							$country_key="MODULE_" . $temp_split[1] . "_" . $temp_split[2] . "_EXCEPT_COUNTRY";
						else
							$country_key="MODULE_" . $temp_split[1] . "_" . $temp_split[2] . "_" . $temp_split[3] ."_EXCEPT_COUNTRY";
						$country_control='configuration[' . $country_key . ']';
					}
					if(($key_value['set_function'] == "tep_cfg_pull_down_shipping_zone_classes("))
					  $config_input = tep_cfg_pull_down_shipping_zone_classes($key_value['configuration_value'],$key, $country_control,$country_key) . '</div>';
					else
					  $config_input = tep_cfg_pull_down_zone_classes($key_value['configuration_value'],$key, $country_control,$country_key) . '</div>';
				} else if ($key_value['set_function'] == "tep_cfg_file_field(") {
					$config_input = tep_cfg_file_field('configuration[' . $key . ']', $key_value['configuration_value']);
				} else if ($key_value['set_function']) {
				      //echo $key_value['set_function'].'<br>';
					  eval('$config_input .= ' . $key_value['set_function'] . "'" . $key_value['configuration_value'] . "', '" . $key . "');");
				} else {
				  $config_input = tep_draw_input_field('configuration[' . $key . ']', $key_value['configuration_value']);
				}
//here is the values
				$result.='<tr><td class="main" valign="top" width="300"><b>' . $key_value['configuration_title'] . '</b><Br>' . $key_value['configuration_description'] . '</td>
								<td width="10"><td class="main" valign="top" nowrap><div id="ROW_' . $key_value["configuration_key"] . 'content">' . $config_input . '</div></td></tr><tr  height="5"><td>&nbsp;</td></tr>';
				$icnt++;
				if ($has_images!='yes' && strpos($config_input,"type='file'")!==false){
					$has_images="yes";
				}
			}

			if ($icnt<=$col_total){
				$result.='	</table>
								</td>
							</tr>
							';
			}
			$result.='<input type="hidden" id="hasImages" value="' . $has_images . '">';
			$result.='
						</table>
						</td>
						</tr>
						<tr >
							<td>&nbsp;</td>
						</tr>
					</table>
					</form>
					</div>
					';
			echo $result;
			$jsData->VARS["updateMenu"]=",update,";
	  }
	  function doGetZone()
	  {
		  global $FREQUEST;
			$cfg_value=$FREQUEST->getvalue("cfg_value");
			$cfg_key=$FREQUEST->getvalue("cfg_key");
			$cfg_zone=$FREQUEST->getvalue("cfg_zone");
			echo tep_cfg_pull_down_zone_except_countries($cfg_zone,$cfg_value,$cfg_key);
	  }
	  function doModuleUpdate(){
			global $CUSTOM_FILES,$FREQUEST,$_FILES,$jsData;
			$module=$FREQUEST->getvalue("module");
			$param=$FREQUEST->postvalue('configuration');
			if(isset($_FILES['configuration'])){
				//FOREACH
				//while (list($key, $value) = each($_FILES['configuration'])) {
				foreach($_FILES['configuration'] as $key => $value) {
					//while(list($key1,$value1)=each($value)){
					foreach($value as $key1 => $value1)	{
						$CUSTOM_FILES[$key1][$key] = $value1;
					}
				}
				if (count($CUSTOM_FILES)>0){
					reset($CUSTOM_FILES);
					//while(list($key,$value)=each($CUSTOM_FILES)){
					foreach($CUSTOM_FILES as $key => $value) 
					{					
						if (trim($value["name"])=="") unset($CUSTOM_FILES[$key]);
					}
				}
				if (count($CUSTOM_FILES)>0){
					reset($CUSTOM_FILES);
					$upload_fs_dir = DIR_FS_CATALOG . DIR_WS_IMAGES;
					$upload_ws_dir = DIR_FS_ADMIN  . DIR_WS_IMAGES;
					$extensions = array(".png",".gif",".jpg",".jpeg");
					for($i=0,$count=sizeof($extensions);$i<$count;$i++){
						if(file_exists($upload_ws_dir .$FREQUEST->getvalue('module') . $extensions[$i])){
							@unlink($upload_ws_dir . $FREQUEST->getvalue('module') . $extensions[$i]);
						}
						if(file_exists($upload_fs_dir . $FREQUEST->getvalue('module') . $extensions[$i])){
							@unlink($upload_fs_dir . $FREQUEST->getvalue('module') . $extensions[$i]);
						}
					}

					reset($CUSTOM_FILES);
					//while(list($key,$value)=each($CUSTOM_FILES)){
					foreach($CUSTOM_FILES as $key => $value) {
						$extension = substr($value['name'],strpos($value['name'],"."));
						if ($extension=="" || !in_array($extension,$extensions)) continue;
						if (strpos($key,"MODULE_PAYMENT_")!==false){
							$CUSTOM_FILES[$key]["name"]=$FREQUEST->getvalue('module') . $extension;
						}
						$upload = new upload($key, $upload_ws_dir);
						$file_name = $upload->filename;
						@copy($upload_ws_dir . $file_name,$upload_fs_dir . $file_name);
						$param[$key]=$file_name;
					}
				}
			}
			reset($param);
			$select_module=$this->load_module($module);
			//tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '' where configuration_key in('" . implode("', '",$select_module->keys()) . "')");
			//while (list($key, $value)=each($param)) {
			foreach($param as $key => $value) {
				if(is_array($value)){ $value=array_remove_null_value($value);  $value = implode( ", ", $value); }
				tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($value) . "' where configuration_key = '" . tep_db_input($key) . "'");
			}
			echo $this->dogetModuleDetails();
			$jsData->VARS["updateMenu"]=",normal,";
		}
	  function dogetModuleDetails()
	  {
			global $FREQUEST,$jsData;
			$module=$FREQUEST->getvalue('module');
			$select_module=$this->load_module($module);
			if(!$select_module) return;
			if (!$select_module->check()){
				$position="inactive";
				$result='<div style="padding-top:5px;padding-left:30px;vertical-align:top"><table border="0" cellpadding="0" cellspacing="0"  height="40" width="90%">
						<tr>
							<td align="right" class="main">' . "<a href='javascript:void(0);' onclick=javascript:doSimpleAction({'id':'list','get':'Install_module','result':doDisplayResult,'type':'modu','params':'set=" . $this->set. "&module=" . $module."','message':'" .TEXT_INSTALLING_MODULE . "'})>" . tep_image_button('button_module_install.gif',IMAGE_INSTALL) . '</a>&nbsp;</td>
						</tr>
						</table></div>
						';
				echo $result;
			}
			$position="active";
			$result='<div style="padding-top:5px;padding-left:30px;vertical-align:top"><table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							{{IMAGE_ELEMENT}}
							<td class="main">';// . $select_module->description . '<br><br>
							$result.='<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td valign="top" width="50%">
									<table border="0" cellpadding="2" cellspacing="0">
					';
			$module_keys=$select_module->keys();
			$col_total=ceil((count($module_keys)-1)/2);
			$icnt=0;
			$module_sort_order="";
			switch ($this->set) {
				case 'shipping':
					$module_sort_order='MODULE_SHIPPING_%_SORT_ORDER';
					break;
				case 'ordertotal':
					$module_sort_order='MODULE_ORDER_TOTAL_%_SORT_ORDER';
					break;
				case 'sms':
					$module_sort_order='MODULE_SMS_%_SORT_ORDER';
					break;
				case 'payment':
				default:
					$module_sort_order='MODULE_PAYMENT_%_SORT_ORDER';
					break;
			}

			$key_value_query = tep_db_query("select configuration_title, configuration_value, configuration_description, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_key  in ('" . join("','",$module_keys) . "') and configuration_key not like '" . $module_sort_order . "' order by sort_order");
			while($key_value = tep_db_fetch_array($key_value_query)){
				if ($icnt>=$col_total){
					$result.='	</table>
								</td>
								<td valign="top" width="50%">
								<table border="0" cellpadding="2" cellspacing="0">
							';
					$icnt=0;
				}
				$config_value=$key_value['configuration_value'];

				if ($key_value['use_function']) {
					$use_function = $key_value['use_function'];
					//if (ereg('->', $use_function)) {
					if (preg_match('/->/', $use_function)) {
					  $class_method = explode('->', $use_function);
					  if (!is_object(${$class_method[0]})) {
						include(DIR_WS_CLASSES . $class_method[0] . '.php');
						${$class_method[0]} = new $class_method[0]();
					  }
					  $config_value= tep_call_function($class_method[1], $key_value['configuration_value'], ${$class_method[0]});
					} else {
					  $config_value= tep_call_function($use_function, $key_value['configuration_value']);
					}
				}
				if ($key_value['configuration_title']!='Image'){
					$result.='<tr><td class="main">' . $key_value['configuration_title'] . '</td><td CLASS="main">' . $config_value . '</td></tr>';
					$icnt++;
				} else {
					$mod_image=$config_value;
					$image_array = array(".png",".jpg",".jpeg",".gif");
					for($i=0,$j=sizeof($image_array);$i<$j;$i++){
						if(file_exists(DIR_WS_IMAGES . $config_value . $image_array[$i])){
							$mod_image=$config_value . $image_array[$i];
							$i = $j;
						}
					}
				}

			}
			if ($icnt<=$col_total){
				$result.='	</table>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							';
			}
			$result.='		</form>
							</table>
							</td>
						</tr>
					</table></div>
					';
			if ($mod_image!=''){
				$result=str_replace("{{IMAGE_ELEMENT}}",'<td valign="top" align="center" width="150">' . tep_image(DIR_WS_IMAGES . $mod_image,'',103,33) . '</td>',$result);
			}else{
				$result=str_replace("{{IMAGE_ELEMENT}}",'',$result);
			}
			echo $result;
	  }
	  function domoduleSort(){
	  		global $FREQUEST,$jsData;
			$module=$FREQUEST->getvalue("module","string");
			$mode=$FREQUEST->getvalue("mode","string","down");
			$module_key=str_replace('%',$module,$this->total_key);
			$module_query=tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='$module_key'");
			if (tep_db_num_rows($module_query)<=0) {
				echo "Err:" . TEXT_KEY_NOT_FOUND ;
				return;
			}
			$m_row=tep_db_fetch_array($module_query);
			$current_order=(int)$m_row["configuration_value"];
			if ($mode=='up'){
				$module_sort_query=tep_db_query("select configuration_key,configuration_value from ".TABLE_CONFIGURATION ." where cast(configuration_value as unsigned) <" . tep_db_input($current_order) ." and configuration_key like '" . tep_db_input($this->module_details["total_key"]) . "' order by cast(configuration_value as unsigned) desc limit 1");
			} else {
				$module_sort_query=tep_db_query("select configuration_key,configuration_value from ".TABLE_CONFIGURATION ." where cast(configuration_value as unsigned) >" . tep_db_input($current_order) ." and configuration_key like '" . tep_db_input($this->module_details["total_key"]) . "' order by cast(configuration_value as unsigned) limit 1");
			}
			if(tep_db_num_rows($module_sort_query)<=0){
				echo "NOTRUNNED";
				return;
			}
			$module_sort_result=tep_db_fetch_array($module_sort_query);
			$prev_order=$module_sort_result['configuration_value'];
			tep_db_query("UPDATE " . TABLE_CONFIGURATION . " set configuration_value='$current_order' where configuration_key='" . $module_sort_result['configuration_key'] ."'");
			tep_db_query("UPDATE " . TABLE_CONFIGURATION . " set configuration_value='$prev_order' where configuration_key='$module_key'");
			$temp_arr=preg_split("/%/",$this->module_details["total_key"]);
			$update_key=$module_sort_result['configuration_key'];
			for($j=0;$j<sizeof($temp_arr);$j++)
				$update_key=str_replace($temp_arr[$j],'',$update_key);
			echo "SUCCESS";
			$jsData->VARS['moveRow']=array('mode'=>$mode,'destID'=>$update_key);
		}
	function get_modules($mode)
	{
	global $FREQUEST,$PHP_SELF,$FSESSION;
	$file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
	$directory_array = array();
	if ($dir = @dir($this->module_directory)) {
		while ($file = $dir->read()) {
			if (!is_dir($this->module_directory . $file)) {
				if (substr($file, strrpos($file, '.')) == $file_extension) {
					$dir_array[] = $file;
					$class = substr($file, 0, strrpos($file, '.'));
					if (!tep_class_exists($class)) {
						include(DIR_FS_ADMIN . '/includes/languages/'. $FSESSION->language .'/modules/' . $this->module_type . '/' . $file);
						//include(DIR_FS_CATALOG_LANGUAGES . $FSESSION->language . '/modules/' . $this->module_type . '/' . $file);
						include(CHECK_CON . $this->module_directory . $file);
					}
					if(tep_class_exists($class)) {
						$module = new $class;
						if($mode=="I" && $module->check() > 0)
						{
							$table_id=$this->get_module_distinct_key($module);
							$confg_key=str_replace('%',$table_id,$this->total_key);
							$module_sort_query=tep_db_query("select configuration_value from ".TABLE_CONFIGURATION ." where configuration_key ='" . $confg_key . "'");
							$mod_result=tep_db_fetch_array($module_sort_query);
							$module_sort_order=$mod_result['configuration_value'];
							if (isset($module_sort_order) && (int)$module_sort_order >= 0){
								$directory_array[]=sprintf("%04s",$module_sort_order)  . $file;
							}
						}
						elseif($mode=="U" && $module->check()<=0){
							$directory_array[]="AAAA" . $file;
						}
						$this->modules[$file]=$module;
					}
				}
			}
		}
		sort($directory_array);
		$dir->close();
	}
	return($directory_array);
}
	function load_module($module){
		global $PHP_SELF,$FSESSION;
		$file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
		if (file_exists($this->module_details["directory"] . $module . $file_extension)) {
			if (!tep_class_exists($module)) {
				include(DIR_FS_ADMIN . '/includes/languages/'. $FSESSION->language . '/modules/' . $this->module_details["type"] . "/" . $module . $file_extension);
				//include(DIR_FS_CATALOG_LANGUAGES . $FSESSION->language . '/modules/' . $this->module_details["type"] . "/" . $module . $file_extension);
				include(CHECK_CON . $this->module_details["directory"] . $module . $file_extension);
			}
			$select_module = new $module;
			return $select_module;
		}
		return false;
	}
	function get_module_distinct_key($module){
		$module_keys=$module->keys();
		$table_id="";
		for($kcnt=0;$kcnt<sizeof($module_keys);$kcnt++)
		{
			if(substr($module_keys[$kcnt],-7)=="_STATUS")
			{
				$table_id=substr($module_keys[$kcnt],0,-7);
				$temp=str_replace('INSTALLED','',$this->module_key);
				$table_id=str_replace($temp,'',$table_id);
				break;
			}
		}
		return $table_id;
	}
}
//======================
			function set_installed_modules($module,$mode,$sort_order=0){
			global $installed_modules,$sort_orders,$module_details;
			switch($mode){
				case "remove":
					$installed_modules=str_replace(";" . $module  . ".php;",";",";" . $installed_modules .";");
					$installed_modules=substr($installed_modules,1,-1);
					break;
				case "install":
					if ($installed_modules=="") {
						$installed_modules=$module . ".php";
						break;
					}
					$mod_splt=preg_split("/;/",$installed_modules);
					$sot_splt=preg_split("/;/",$sort_orders);
					$temp_module="";
					for ($icnt=0,$n=count($mod_splt);$icnt<$n;$icnt++){
						if ($sot_splt[$icnt]>=$sort_order) {
							$temp_module=$mod_splt[$icnt];
							break;
						}
					}
					if ($temp_module!=''){
						$installed_modules=str_replace(";".  $temp_module  . ";",";" . $module . ".php;" . $temp_module . ";",";" . $installed_modules .";");
						$installed_modules=substr($installed_modules,1,-1);
					} else {
						$installed_modules.=";" . $module .".php";
					}

					break;
			}
			tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($installed_modules) . "' where configuration_key = '" . tep_db_input($module_details["key"]) . "'");
		}
	function install_module($module){
		global $select_module,$module_details;
		if (!load_module($module)) return;
		$select_module->install();
		$module_keys=$select_module->keys();
		$current_key="";
		//FOREACH
		//while(list($key,$value) = each($module_keys)){
		foreach($module_keys as $key => $value) 
		{	
			if(strpos($value,"_SORT_ORDER")!==false){
				$current_key=$value;
				break;
			}
		} //while
		if ($current_key!=''){
			$sql=tep_db_query("select configuration_value from ".TABLE_CONFIGURATION ." where configuration_key ='". tep_db_input($current_key) . "'");
			if(tep_db_num_rows($sql)>0){
				$result=tep_db_fetch_array($sql);
				$sort_order=(int)$result['configuration_value'];
			} else {
				$sort_order=1;
			}
		} //$current_key
		return $sort_order;
	}
	function getListTemplate(){
		ob_start();
		getTemplateRowTop();
?>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
			<tr>
				<td>
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="1%" id="##TYPE####ID##bullet">##BULLET_IMAGE##</td>
						<td width="2%" align="center" class="boxRowMenu" nowrap="nowrap">
							<span style="##FIRST_MENU_DISPLAY##" >
								<a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':'##ID##','get':'moduleSort','result':doSimpleResult,'mode':'up','type':'##TYPE##','params':'set=##SET##&module=##ID##&mode=up','message':'##SORTING_DATA##'})"><img src="##IMAGE_PATH##template/img_arrow_up.gif" align="absmiddle"/></a>
								<a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':'##ID##','get':'moduleSort','result':doSimpleResult,'mode':'down','type':'##TYPE##','params':'set=##SET##&module=##ID##&mode=down','message':'##SORTING_DATA##'})"><img src="##IMAGE_PATH##template/img_arrow_down.gif"/></a>
							</span>
						</td>
						<td width="80%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'set=##SET##&module=##UPDATE_ID##'});" id="##TYPE####ID##name">##NAME##</td>
						<td  width="10%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
							<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
							<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'set=##SET##&module=##UPDATE_ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							<a href="javascript:void(0)" onClick="javascript:return doSimpleAction({'id':'list','get':'Delete','result':doDisplayResult,'type':'unmodu','params':'set=##SET##&module=##UPDATE_ID##','message':'##DELETING_MODULE##'});"><img src="##IMAGE_PATH##template/delete_blue.gif"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							</span>
							<span id="##TYPE####ID##mupdate" style="display:none">
							<a href="javascript:void(0)" onclick="javascript:return doUpdateAction({'id':'##ID##','get':'ModuleUpdate','customUpdate':doModuleUpdate,'type':'##TYPE##','style':'boxRow','uptForm':'config_input','result':doDisplayResult,'params':'set=##SET##&module=##UPDATE_ID##',message:page.template['UPDATE_IMAGE'],message1:page.template['UPDATE_DATA']});"><img src="##IMAGE_PATH##template/img_save_green.gif"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':'##ID##','get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif"/></a>
							</span>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
<?php
		getTemplateRowBottom();
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	function array_remove_null_value($value){
		if(is_array($value)){
		$my_array=array();
			for($i=0;$i<count($value);$i++)
				if($value[$i]!=NULL)
					$my_array[]=$value[$i];
		return $my_array;
		}
	}
?>
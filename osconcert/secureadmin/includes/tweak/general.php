<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	defined('_FEXEC') or die();
	define('DSEP','/');
	define('RINC',1);
	define('RFUNC',2);
	define('RCLA',3);
	define('RLANG',4);
	define('RTWE',5);
	//define('HTML_EDITOR','tinyMce');
	require(DIR_WS_LANGUAGES . $FSESSION->language . "/tweak.php");
	
	if (!$FSESSION->is_registered('AJX_ENCRYPT_KEY')){
		$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));
	}
	if (!$FSESSION->is_registered('displayRowsCnt')){
		$FSESSION->set("displayRowsCnt",MAX_DISPLAY_SEARCH_RESULTS);
	}
	$rowsCnt=$FREQUEST->getvalue('rowsCnt','int',0);
	if ($rowsCnt==-1 || $rowsCnt>=MAX_DISPLAY_SEARCH_RESULTS){
		$FSESSION->set('displayRowsCnt',$rowsCnt);
	}
	$DISPLAY_SHOW_PAGES=array();
	
	$SERVER_DATE_TIME = getServerDate(true);
	$SERVER_DATE = getServerDate();

	frequire('instance.php',RTWE);


	$jsData= (new instance)->getTweakObject('jason');
	
	$UPLOAD_FS_DIR = DIR_FS_CATALOG_IMAGES;
	$LANGUAGES=tep_get_languages();

	// freeway require
	function frequire($fileNames,$mode=1){
		global $FSESSION;
		if (!is_array($fileNames)) $fileNames=array($fileNames);
		for ($icnt=0,$n=count($fileNames);$icnt<$n;$icnt++){
			switch ($mode){
				case RTWE:
					$filePath=DIR_WS_INCLUDES . 'tweak' . DSEP;
					break;
				case RLANG:
					$filePath=DIR_WS_LANGUAGES;
					break;
				case RCLA:
					$filePath=DIR_WS_CLASSES;
					break;
				case RFUNC:
					$filePath=DIR_WS_FUNCTIONS;
					break;
				default:
					$filePath=DIR_WS_INCLUDES;
					break;
			} 
			if (!file_exists(DIR_FS_ADMIN . $filePath . $fileNames[$icnt])){
				echo 'Err: Unable to load the file '  . $fileNames[$icnt];
				appExit();
			}
			require_once($filePath . $fileNames[$icnt]);
		}
	}
	function checkAJAX($object){
		global $FREQUEST,$$object,$jsData;
		$AJX_CMD=$FREQUEST->getvalue("AJX_CMD");
		if ($AJX_CMD=='') return;

		$COMMANDS = preg_split("/,/",$AJX_CMD); 
		for ($icnt=0,$cmd_count=count($COMMANDS);$icnt<$cmd_count;$icnt++){
			if (substr($COMMANDS[$icnt],0,3)=="GL_"){
				$func="do" . substr($COMMANDS[$icnt],3);
				if (!function_exists($func)){
					outError(TEXT_UNDEFINED_METHOD);
					break;
				}
				$func();
			} else {
				if (!method_exists($$object,"do" . $COMMANDS[$icnt])) { 
					outError(TEXT_UNDEFINED_METHOD);
					break;
				}
				$$object->{"do" . $COMMANDS[$icnt]}();
			}
		}
		$jsData->outVARS();
		appExit();
	}
	function mergeTemplate($array,$content){
		if (count($array)>0){
			reset($array);
			//while(list($key,$value)=each($array)) 
				foreach($array as $key => $value)
				//FOREACH
				$content=str_replace("##" . $key . "##",$value,$content);
		}
		return $content;
	}
	function ajxEncrypt($str){
		global $FSESSION,$ENCRYPTED;
		$result='';
		if (!$ENCRYPTED) return $str;
		for ($icnt=0,$n=strlen($str);$icnt<$n;$icnt++){
			$result.=chr($FSESSION->AJX_ENCRYPT_KEY ^ ord(substr($str,$icnt,1)));
		}
		return $result;
	}
	function ajxDecrypt($str){
		global $FSESSION;
		$result='';
		return $str;
		for ($icnt=0,$n=strlen($str);$icnt<$n;$icnt++){
			$result.=chr($FSESSION->AJX_ENCRYPT_KEY ^ ord(substr($str,$icnt,1)));
		}
		return $result;
	}
	function appExit(){
		global $FSESSION;
		$FSESSION->close();
		exit;
	}
	function doImageUpload(){
			global $FREQUEST,$UPLOAD_FS_DIR,$jsData;
			if(!is_writable($UPLOAD_FS_DIR) || !is_writable($UPLOAD_FS_DIR)) {
				echo "Err:" .sprintf(WARNING_IMAGE_DIRECTORY_WRITEABLE,$upload_fs_dir);
				return;
			}
			$image_list_str=$FREQUEST->postvalue('image_list');
			if ($image_list_str=="") {
				echo "Err:" . IMAGE_INPUTS_CANNOT_FOUND;
				return;
			}
			$image_resize=$FREQUEST->postvalue('image_resize','int',0);
			$image_list=preg_split("/,/",$image_list_str);
			$result=array();
			for ($icnt=0,$n=count($image_list);$icnt<$n;$icnt++){
				$key_name=str_replace("_file","",$image_list[$icnt]);
				if ($FREQUEST->getPostFile($image_list[$icnt])==""){
					$result[$key_name]='';
					continue;
				}
                $destination=$UPLOAD_FS_DIR;$error = IMAGE_FAILED_TO_UPLOAD.$image_list[$icnt];
				$image=new upload($image_list[$icnt]);
                    if(strpos($image_list[$icnt],'download')!==false) {
                        $destination=DIR_FS_DOWNLOAD;
                        $error = FAILED_TO_UPLOAD;
                  }
				$image->set_destination($destination);
				if ($image->parse() && $image->save($image_resize==1?true:false)) $result[$key_name]=$image->filename;
				else break;
			}
			if ($icnt<$n){
				echo "Err:" . sprintf($error);
			} else {
				echo "SUCCESS";
				$jsData->VARS=$result;
			}
	}
	function outError($errorText){
		echo "Err:" . $errorText;
	}
	function tep_create_calendar($controlname,$date_format){
?>
		<a href="javascript:show_calendar('<?php echo $controlname;?>',null,null,'<?php echo $date_format;?>');"
		onmouseover="window.status='Date Picker';return true;"
		onmouseout="window.status='';return true;"><img border="none" src="images/icon_calendar.gif"/ align="absmiddle">  
		</a>
<?php 
	}
	function getPaginationTemplate(){
	ob_start();
?>
	<tr>
		<td valign="top">
		<form name="pagenavig##FORM_ID##" id="page##FORM_ID##" method="get" action="##PAGE_LINK##">
		<table border="0" cellpadding="2" cellspacing="0" width="100%">
			<tr>
				<td width="5%"></td>
				<td class="main" width="66%" align="left">##DISPLAY_COUNT##</td>
				<td class="main" width="4%" align="center">##DISPLAY_PREV##</td>
				<td class="main" width="20%" align="left">##DISPLAY_LINKS##</td>
				<td class="main" width="4%">##DISPLAY_NEXT##</td>
			</tr>
		</table>
		</form>
		</td>
	</tr>
<?php
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	function getTemplateRowTop(){
	?>
		<tr id="##TYPE####ID##row" class="##ALTERNATE_ROW_STYLE##">
			<td valign="top" style="padding:2px;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="boxRow" id="##TYPE####ID##style" onmouseover="javascript:doMouseOverOut([{callFunc:changeItemRow,params:{element:this,'className':'boxRow','changeStyle':'Hover'}}]);" onmouseout="javascript:doMouseOverOut([{callFunc:changeItemRow,params:{element:this,'className':'boxRow'}}]);">
				<tr>
					<td class="topleft"></td>
					<td></td>
					<td class="topright"></td>
				</tr>
				<tr>
					<td width="4"></td>
					<td valign="top">
	<?php
	}
	function getTemplateRowBottom(){
	?>
					</td>
					<td width="4"></td>
				</tr>
				<tr>
					<td class="botleft"></td>
					<td></td>
					<td class="botright"></td>
				</tr>
			</table>
			</td>
		</tr>
	<?php
	}
	
	function tep_draw_multiselect_checkbox($name, $values, $selected_vals, $width='220px', $height='100px', $params = '', $required = false){
	$field='<div style="width:'.$width.';height:'.$height.';overflow:auto;border:solid 1px #CCCCCC;"';
		if($params!='')
			$field.=$params;
	$field.=' class="inputNormal" align="justify">';
	for($i=0;$i<sizeof($values);$i++){ 
		$check_flag=false;
		
		for($j=0;$j<sizeof($selected_vals);$j++)
			if($values[$i]['id']==$selected_vals[$j]['id'])
				$check_flag=true;
		
		$field.=tep_draw_checkbox_field($name,$values[$i]['id'],$check_flag).$values[$i]['text'].'<br>';
	}
	$field.='</div>';
	if($required) $field.=TEXT_FIELD_REQUIRED;
	return $field;	
	}
	function drawUploadForm($fname,$resize){
	?>
	<form name="fileUpload" id="fileUpload" action="<?php echo tep_href_link($fname,'AJX_CMD=GL_ImageUpload'); ?>" method="post" enctype="multipart/form-data" style="visibility:hidden;">
		<input type="hidden" name="image_list" id="image_list" value="">
		<input type="hidden" name="image_resize" value="<?php echo $resize;?>">
	</form>
	 <?php
	}
?>
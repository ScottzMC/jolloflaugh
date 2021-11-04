<?php
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
	
	class customerAccount 
	{
		
		var $requiredText;
		var $delim_1;
		var $delim_2;
		var $errors;
		var $maxImageSize;
		
		function __construct()
		{
			//$this->requiredText='';
			$this->requiredText='<span class="inputRequirement">'.TEXT_FIELD_REQUIRED.'</span>';
			$this->delim_1="##";
			$this->delim_2="@@";
			$this->errors=array();
			$this->maxImageSize=524288;
		}
		function commonInput($fieldDesc)
		{
			global $ACCOUNT;
			if ($fieldDesc['input_type']=="L") 
			{
				//something to hide the 'Your Password' title for PWA
				if ($fieldDesc['uniquename']=='title_6' && isset($_GET['guest']) || isset($_POST['guest'])) 
				{
					echo "";
				}else{
					if((isset($_GET['guest']))&&(PURCHASE_WITHOUT_ACCOUNT=='yes')&&($fieldDesc['label_text']=='Options')){
					echo '';
					}else{
					echo '<h2>' . $fieldDesc['label_text'] . '</h2>' . "\n";
					}
				}
				return;
			} 
			$value=(isset($ACCOUNT[$fieldDesc["uniquename"]])?$ACCOUNT[$fieldDesc["uniquename"]]:$fieldDesc['default_value']);
			if($fieldDesc['required']=='Y')
			{
			echo '<div class="form-group row">' . "\n";
			echo '<div class="col-md-6 text-md-right">' . "\n";
			echo '<strong><label for="'.$fieldDesc['label_text'].'"  class="form-label">' . "\n";
			echo $this->_displayTitle($fieldDesc['show_label'],$this->requiredText.' '.$fieldDesc['label_text']);
			echo '<br>';
			//$this->__display_inputsuffix($fieldDesc);
			echo '</label></strong>' . "\n";
			echo '</div>' . "\n";
			
			}else
			{
			echo '<div class="form-group row">' . "\n";
			echo '<div class="col-md-6 text-md-right">' . "\n";
			echo '<strong><label for="'.$fieldDesc['label_text'].'">' . "\n";
			echo $this->_displayTitle($fieldDesc['show_label'],$fieldDesc['label_text']);
			echo '<br>';
			//$this->__display_inputsuffix($fieldDesc);
			echo '</label></strong>' . "\n";
			echo '</div>' . "\n";
			}
			switch($fieldDesc['input_type'])
			{
				case 'D':
						$options=$this->_getOptionArr($fieldDesc['options_values']);
							echo '<div class="pull-right">';
							echo tep_draw_pull_down_menu($fieldDesc['uniquename'],$options,$value,'title="' . tep_output_string($fieldDesc['input_title']) .'"') . '';
							echo '</div>' . "\n";
					break;
				case 'O':
						$options=$this->_getOptionArr($fieldDesc['options_values']);
						for ($icnt=0,$n=count($options);$icnt<$n;$icnt++)
						{
							echo '&nbsp;&nbsp;&nbsp;' . tep_draw_radio_field($fieldDesc['uniquename'],$options[$icnt]['id'],($value==$options[$icnt]['id'] || $value==$options[$icnt]['text']?true:false),'style="margin-left:20px;" title="' . tep_output_string($fieldDesc['input_title']) .'"') . ' 	&nbsp;'.$options[$icnt]['text'] . '';
							echo ' 	&nbsp;';
						}
						echo '</div><br>' . "\n";
					break;
				case 'C':
							echo '<div class="col-md-6">' . "\n";
							echo tep_draw_checkbox_field($fieldDesc['uniquename'],$fieldDesc["checkon"],($value==$fieldDesc["checkon"]?true:false),' style="margin-left:10px;" title="' . tep_output_string($fieldDesc['input_title']) .'"');
							//if($fieldDesc['uniquename']=='Einverstaendnis'){
							//echo '<br>';
							//echo CONSENT_MESSAGE;
							//}
							echo '<br><br><div>' . "\n";
							$this->__display_inputsuffix($fieldDesc);
							echo '</div>' . "\n";
							echo '</div>' . "\n";
							echo '</div>' . "\n";
					break;
				case 'A':
					$width=50;
					$height=5;
						if ($fieldDesc['textbox_size']!=''){
							$splt=explode($this->delim_1,$fieldDesc['textbox_size']);
							$width=$splt[0];
							if (!$splt[1] || $splt[1]=='') $height=$splt[0];
							else $height=$splt[1];
						}
						echo tep_draw_textarea_field($fieldDesc['uniquename'],'wrap',$width,$height,$value,'title="' . tep_output_string($fieldDesc['input_title']) .'"',false) . '';
					break;
				default:
					if ($fieldDesc['uniquename']=='customers_username' && isset($_GET['guest']) || isset($_POST['guest'])) {
						$random_username = mt_rand();
						echo '<div class="col-sm-6">' . "\n";
						echo tep_draw_hidden_field($fieldDesc['uniquename'],$random_username). "\n";
						echo '<span style="display:true">' . "\n";
						echo TEXT_NOT_REQUIRED;
						'</span>';
						echo '</div>' . "\n";
						echo '</div>' . "\n";
					break;
					}
					else 
					{						
						echo '<div class="col-sm-6">' . "\n";
						echo tep_draw_input_field($fieldDesc['uniquename'],$value,' required aria-required="true" id="'.$fieldDesc['uniquename'].'" placeholder="" title="' . tep_output_string($fieldDesc['input_title']) .'" size=' . $fieldDesc['textbox_size'],'text',false) . '' . "\n";
						$this->__display_inputsuffix($fieldDesc);
						echo '</div>' . "\n";
						echo '</div>' . "\n";
					}
					break;
			}
			//$this->__display_inputsuffix($fieldDesc);
			if ($fieldDesc['uniquename']=='customers_username' && isset($_GET['guest']) || isset($_POST['guest'])) {
				//echo'</span>';
				}
			//echo '</div>';
		}
		
		function commonCheck($fieldDesc)
		{
			global $CUSTOMER,$ADDRESS,$EXTRA,$INFO,$ACCOUNT;
			if ($fieldDesc['input_type']=="L") return true;
			$pass=true;
			$value=(isset($ACCOUNT[$fieldDesc["uniquename"]])?$ACCOUNT[$fieldDesc["uniquename"]]:'');
			switch($fieldDesc['input_type']){
				case 'D':
				case 'O':
					if ($fieldDesc['required']=='Y' && ($value=='' || !$this->_checkOption($fieldDesc['options_values'],$value))){
						$pass=false;
					}
					break;
				case 'C':
					if ($fieldDesc['required']=='Y' && $value!=$fieldDesc["checkon"]){
						$pass=false;
					}
					else if ($value!=$fieldDesc["checkon"] && $value!=$fieldDesc["checkoff"]) {
						$value=$fieldDesc["checkoff"];
					}
					break;
				case 'A':
				default:
					if ($fieldDesc['required']=='Y'){
						if ($value=='' || ($fieldDesc["textbox_min_length"]>0 && strlen($value)<$fieldDesc["textbox_min_length"]) || ($fieldDesc["textbox_max_length"]>0 && strlen($value)>$fieldDesc["textbox_max_length"])){
							$pass=false;
						}
					}
					break;
			}
			
			if(!$pass){
				$this->errors[]=$fieldDesc["error_text"];
			}
			else {
				if(strpos($fieldDesc["storage_type"],"C")!==false){
					$key=str_replace("#1","customers",$fieldDesc["uniquename"]);
					if($fieldDesc["uniquename"]=='customers_occupation' || $fieldDesc["uniquename"]=='customers_interest'){
						$CUSTOMER[$key]=(int)$value;
					}
					else {
						$CUSTOMER[$key]=$value;
					}
				}
				
				if(strpos($fieldDesc["storage_type"],"A")!==false){
					$key=str_replace("#1","entry",$fieldDesc["uniquename"]);
					$ADDRESS[$key]=$value;
				}
				
				if(strpos($fieldDesc["storage_type"],"I")!==false){
					$INFO[$fieldDesc['uniquename']]=$value;
				}
				if (strpos($fieldDesc["storage_type"],"E")!==false){
					$EXTRA[$fieldDesc['uniquename']]=$value;
				}
			}
			return $pass;
		}
		function &_getOptionArr($values)
		{
			if ($values=='') return array();
			$splt=preg_split("/".$this->delim_1."/",$values);
			$result=array();
			for ($icnt=0,$n=count($splt);$icnt<$n;$icnt++){
				if ($splt[$icnt]=='') continue;
				$splt1=preg_split("/".$this->delim_2."/",$splt[$icnt]);
				if (!$splt1[1] || $splt1[1]=='') $splt1[1]=$splt1[0];
				$result[]=array("id"=>$splt1[1],"text"=>$splt1[0]);
			}
			return $result;
		}
		
		function _checkOption($values,$checkValue)
		{
			$splt=preg_split("/".$this->delim_1."/",$values);
			$values=$this->delim_1 . str_replace($this->delim_2,$this->delim_1,$values) . $this->delim_1;
			if(strpos(strtolower($values),$this->delim_1 . strtolower($checkValue) . $this->delim_1)!==false){
				return true;
			}
			else {
				return false;
			}
		}
		
		function _displayTitle($show_label,$label_text)
		{
			//echo '<span class="fieldKey">';
			if ($show_label=="Y"){
				echo $label_text;
			}
			//echo '</span>';
		}
		
		/* the ampersand has been added here instead of the call: __display_inputsuffix(&$fieldDesc) */
		function __display_inputsuffix(&$fieldDesc)
		{
			if($fieldDesc['required']=='Y')
			{
				//echo '<span class="required">' . $this->requiredText . '</span>';
			}
			echo '<span class="desc">' . $fieldDesc['input_description'] . '</span>';
		}
		
		function edit__customers_photo($fieldDesc)
		{
			global $ACCOUNT;
		
			$img=$value=(isset($ACCOUNT[$fieldDesc["uniquename"]])?$ACCOUNT[$fieldDesc["uniquename"]]:$fieldDesc['default_value']);
		
			if(($pos=strpos($value,'--virtual--'))!==false){
				$img=substr($value,11);
			}
		
			$this->_displayTitle($fieldDesc['show_label'],$fieldDesc['label_text']);
			echo '<div><input type="file" name="'. $fieldDesc["uniquename"] . '_file" size="'.$fieldDesc["textbox_size"] .'" accept="image/gif, image/jpeg, image/jpg, image/png"/>';
			echo tep_draw_hidden($fieldDesc["uniquename"],$value);
			if ($fieldDesc['required']=='Y'){
				//echo '<span class="inputRequirement">' . $this->requiredText . '</span>';
			}
			echo '<span class="desc">' . $fieldDesc['input_description'] . '</span>';
			if ($img!=""){
				echo '<br>' . tep_image(DIR_WS_IMAGES . "customers/" . $img,'','','','aign="absmiddle"');
			}
			echo '<div>';
		}
		
		function edit__country_state($fieldDesc)
		{
			global $ACCOUNT;
			$splt=preg_split("/".$this->delim_1."/",$fieldDesc['label_text']);
			echo '<div class="container-fluid" style="padding: 0">' . "\n";
			echo '<div class="row">' . "\n";
			echo '<div class="col-md-12">' . "\n";
			echo '<div class="row">' . "\n";
			echo '<div class="col-md-6 text-md-right">' . "\n";
			echo "<strong><label for='countries' class='form-label'>" . "\n";
			if($fieldDesc['required']=='Y')
			{
			echo $this->_displayTitle($fieldDesc['show_label'],$this->requiredText .' '.$splt[0]);
			}else
			{
			echo $this->_displayTitle($fieldDesc['show_label'],$splt[0]);
			}
			echo '</label></strong>' . "\n";
			echo '</div>' . "\n";
			$country=STORE_COUNTRY;
			if(isset($ACCOUNT['entry_country'])){
				$country=$ACCOUNT['entry_country'];
			}
			else if(isset($fieldDesc['default_value']))
			{
				$country=$fieldDesc['default_value'];
			}
			$state=isset($ACCOUNT['entry_state'])?$ACCOUNT['entry_state']:'';
			$zone_id=isset($ACCOUNT['entry_zone_id'])?(int)$ACCOUNT['entry_zone_id']:0;
			if($_SESSION['customer_country_id']==999)
			{
			$countries_array = array(array('id' => '', 'text' => "Box Office"));
			$countries = "";
			}else{
			$countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
			$countries = tep_get_countries();	
			}
			for ($i=0, $n=sizeof($countries); $i<$n; $i++) 
			{
				$countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
			}
			echo '<div class="col-md-6">' . "\n";
			echo tep_draw_pull_down_menu('entry_country', $countries_array, strtolower($country), 'onChange="javascript:getCountryStates();" ');
			echo '<span class="desc">' . $fieldDesc['input_description'] . '</span>';
			echo '</div>' . "\n";
			echo '</div>' . "\n";
			echo '<div class="row">' . "\n";
			echo '<div class="col-md-6 text-md-right">' . "\n";
			echo "<strong><label for='states' class='form-label'>" . "\n";
			if($fieldDesc['required']=='Y')
			{
			echo $this->_displayTitle($fieldDesc['show_label'],$this->requiredText .' '.$splt[1]);
			}else{
			echo $this->_displayTitle($fieldDesc['show_label'],$splt[1]);
			}
			echo '</label></strong>' . "\n";
			echo '</div>' . "\n";
			$zones_array = array();
			$zones_query = tep_db_query("select zone_id,zone_name,placement from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' order by placement,zone_id asc");
			while ($zones_values = tep_db_fetch_array($zones_query)) {
				$zones_array[] = array('id' => $zones_values['zone_id'], 'text' => $zones_values['zone_name']);
			}
			echo '<div class="col-md-6">' . "\n";
			echo tep_draw_input_field('entry_state',$state,'id="state" '.((count($zones_array)>0)?' style="display:none"':'') );
			//state drop down
			//$zone_id=STORE_ZONE;
			echo tep_draw_pull_down_menu('entry_zone_id', $zones_array,$zone_id,'id="zone_id '.((count($zones_array)<=0)?' display:none"':'"')) . '';
			echo '</div>' . "\n";
			echo '</div>' . "\n";
			echo '</div>' . "\n";
			echo '</div>' . "\n";
			echo '</div><br>' . "\n";
		}
		
		function edit__customers_occupation($fieldDesc)
		{
			global $ACCOUNT;
			$value=(isset($ACCOUNT[$fieldDesc["uniquename"]])?$ACCOUNT[$fieldDesc["uniquename"]]:$fieldDesc['default_value']);
			echo '<div class="col-md-6 text-md-right">' . "\n";
			echo "<strong><label for='".$fieldDesc['label_text']."'>" . "\n";
			$this->_displayTitle($fieldDesc['show_label'],$fieldDesc['label_text']);
			echo '</label></strong>' . "\n";
			echo '</div>' . "\n";
			echo '<div class="col-md-6">' . "\n";
			echo tep_draw_pull_down_menu($fieldDesc['uniquename'],tep_get_customer_options('O'),$value);
			$this->__display_inputsuffix($fieldDesc);
			echo '</div>' . "\n";
			echo '</div>' . "\n";
			echo '</div>' . "\n";
			echo '</div>' . "\n";
		}
		
		function edit__customers_interest($fieldDesc)
		{
			global $ACCOUNT;
			$value=(isset($ACCOUNT[$fieldDesc["uniquename"]])?$ACCOUNT[$fieldDesc["uniquename"]]:$fieldDesc['default_value']);
			echo '<div class="row">' . "\n";
			echo '<div class="col-md-6 text-md-right">' . "\n";
			echo "<strong><label for='".$fieldDesc['label_text']."'>" . "\n";
			$this->_displayTitle($fieldDesc['show_label'],$fieldDesc['label_text']);
			echo '</label></strong>' . "\n";
			echo '</div>' . "\n";
			echo '<div class="col-md-6">' . "\n";
			echo tep_draw_pull_down_menu($fieldDesc['uniquename'],tep_get_customer_options('I'),$value);
			$this->__display_inputsuffix($fieldDesc);
			echo '</div>' . "\n";
			echo '</div>';
		}
		
		
		function edit__customers_referal($fieldDesc)
		{
			global $ACCOUNT;
			$splt=preg_split("/".$this->delim_1."/",$fieldDesc['label_text']);
			echo '<div class="row">' . "\n";
			echo '<div class="col-md-6 text-md-right">' . "\n";
			echo '<strong><label for="referal">' . "\n";
			$this->_displayTitle($fieldDesc['show_label'],$splt[0]);
			echo '</label></strong>' . "\n";
			echo '</div>' . "\n";
			echo '<div class="col-md-6">' . "\n";
			
			$value=(isset($ACCOUNT['entry_source'])?$ACCOUNT['entry_source']:$fieldDesc['default_value']);
			$sources_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
			$sources = tep_get_sources();
			for($i=0, $n=sizeof($sources); $i<$n; $i++) 
			{
				$sources_array[] = array('id' => $sources[$i]['sources_id'], 'text' => $sources[$i]['sources_name']);
			}
			$sources_array[] = array('id' => '9999', 'text' => TEXT_REFERRAL_OTHER);
			echo '<div>';
			echo tep_draw_pull_down_menu('entry_source', $sources_array, $value, 'onChange="javascript:(this.options[this.selectedIndex].value==\'9999\')?document.getElementById(\'refer_other\').style.display=\'\':document.getElementById(\'refer_other\').style.display=\'none\';"');
			echo '</div>' . "\n";
			if($fieldDesc['required']=='Y')
			{
				echo '&nbsp;<span class="required">' . $this->requiredText . '</span>';
			}
			echo '<span class="desc">' . $fieldDesc['input_description'] . '</span>';
			
			
			echo '<div id="refer_other" style="display:'.($ACCOUNT['entry_source']=='9999'?'normal':'none') . '">';
			$this->_displayTitle($fieldDesc['show_label'],$splt[1]);
			$value=(isset($ACCOUNT['entry_source_other'])?$ACCOUNT['entry_source_other']:'');
			echo tep_draw_input_field('entry_source_other',$value,'','text',false);
			if ($fieldDesc['required']=='Y')
			{
				echo '&nbsp;<span class="required">' . $this->requiredText . '</span>';
			}
			echo '</div>' . "\n";
			echo '</div>' . "\n";
			echo '</div>' . "\n";
		}
		

		
		function edit__password_and_confirm($fieldDesc)
		{
			
			if (defined('PURCHASE_WITHOUT_ACCOUNT') && (PURCHASE_WITHOUT_ACCOUNT =='yes' or PURCHASE_NO_ACCOUNT=='yes')) 
			{
				if (isset($_GET['guest']) || isset($_POST['guest'])) 
				{//PWA
					//create a random password
					$random_password = mt_rand();
					echo'<input style="display:none">';
					echo'<input type="password" style="display:none">';
					echo '<div id="password_and_confirm_container" style="display:none">';
					echo tep_draw_password_field('customers_password',$random_password,'onKeyUp=javascript:checkPWDStrength(this);');
					echo tep_draw_password_field('customers_password_confirm',$random_password);
					echo '</div>' . "\n";
				// PWA
					//echo "GUEST";
				}
				else
				{

				//echo "going for account";
				$splt=preg_split("/".$this->delim_1."/",$fieldDesc['label_text']);	
				echo '<div class="form-group row">';
				echo tep_draw_input_field('','','  style="display:none" ','text',false) . '';
				echo tep_draw_password_field('','',' style="display:none"'). "\n";

				echo '<div class="col-md-6 text-md-right">' . "\n";
				echo '<strong><label for="password">' . "\n";
				$this->_displayTitle($fieldDesc['show_label'],$this->requiredText.' '.$splt[0]);
				echo '</label></strong>' . "\n";
				echo '</div>' . "\n";
				echo '<div class="col-md-6">' . "\n";
				echo tep_draw_password_field('customers_password','','onKeyUp=javascript:checkPWDStrength(this);'). "\n";
				echo '<span id="strength_info" class="desc"></span>';
				echo '</div>' . "\n";
				echo '</div>' . "\n";
				echo '<div class="row">' . "\n";
				echo '<div class="col-md-6 text-md-right"><strong>' . "\n";
				$this->_displayTitle($fieldDesc['show_label'],$this->requiredText.' '.$splt[1]);
				echo '</strong></div>' . "\n";
				echo '<div class="col-md-6">' . "\n";
				echo tep_draw_password_field('customers_password_confirm'). "\n";
				echo '<span class="desc">' . $fieldDesc['input_description'] . '</span>';
				echo '</div>' . "\n";
				echo '</div>' . "\n";
				}
			
			}
			else
			{
			//echo "going for account";
			$splt=preg_split("/".$this->delim_1."/",$fieldDesc['label_text']);	
			echo '<div class="form-group row">';
			echo tep_draw_input_field('','','  style="display:none" ','text',false) . '';
			echo tep_draw_password_field('','',' style="display:none"'). "\n";

			echo '<div class="col-md-6 text-md-right">' . "\n";
			echo '<strong><label for="password">' . "\n";
			$this->_displayTitle($fieldDesc['show_label'],$this->requiredText.' '.$splt[0]);
			echo '</label></strong>' . "\n";
			echo '</div>' . "\n";
			echo '<div class="col-md-6">' . "\n";
			echo tep_draw_password_field('customers_password','','onKeyUp=javascript:checkPWDStrength(this);'). "\n";
			echo '<span id="strength_info" class="desc"></span>';
			echo '</div>' . "\n";
			echo '</div>' . "\n";
			echo '<div class="row">' . "\n";
			echo '<div class="col-md-6 text-md-right"><strong>' . "\n";
			$this->_displayTitle($fieldDesc['show_label'],$this->requiredText.' '.$splt[1]);
			echo '</strong></div>' . "\n";
			echo '<div class="col-md-6">' . "\n";
			echo tep_draw_password_field('customers_password_confirm'). "\n";
			echo '<span class="desc">' . $fieldDesc['input_description'] . '</span>';
			echo '</div>' . "\n";
			echo '</div>' . "\n";
			}
		}
	//the following will display the security code block
	//if the default value in the back end is set to 'captcha' 
    // then it will display the secureimage system

	function edit__security_code($fieldDesc){
		//initiate captcha and reurn if fails
		if ($fieldDesc['default_value'] == 'captcha'){
		include_once(DIR_WS_CLASSES .'loader.php');
		$loader=new loader();
		$captcha=$loader->loadClass('captcha','SecureImage');
		if (!$captcha->enabled) return;
		}

		$splt=preg_split("/".$this->delim_1."/",$fieldDesc['label_text']);
		echo '<div class="row">' . "\n";
		echo '<div class="col-md-6 text-sm-right">' . "\n";
		echo "<strong><label for='".$fieldDesc['label_text']."'>" . "\n";
		$this->_displayTitle($fieldDesc['show_label'],$fieldDesc['label_text']);
		echo '</label></strong>' . "\n";
		echo '</div>' . "\n";
		echo '<div class="col-md-6">' . "\n";
		if ($fieldDesc['default_value'] == 'captcha'){
		$captcha->renderDisplay($fieldDesc['uniquename']);
			}else{
		echo tep_draw_input_field($fieldDesc['uniquename'],'','size=' . $fieldDesc["textbox_size"],'text',false) . '';
		$this->__display_inputsuffix($fieldDesc) .'';
			}
		echo '</div>' . "\n";
		echo '</div>' . "\n";
	}
		
		/* check functions */
		function check__customers_dob($fieldDesc){
			global $CUSTOMER,$ACCOUNT;
			$pass=true;
			$value=str_replace("/","-",$ACCOUNT['customers_dob']);
			if (!tep_check_date_raw($value,EVENTS_DATE_FORMAT)) {
				$this->errors[]=$fieldDesc['error_text'];
				$pass=false;
			} else {
				$CUSTOMER[$fieldDesc['uniquename']]=tep_convert_date_raw($value);
			}
			return $pass;
		}
		function check__customers_username($fieldDesc)
		{
			global $CUSTOMER,$FREQUEST,$ACCOUNT;
			$pass=true;
			
			$customers_id=$FREQUEST->postvalue('customers_id','int',0);
			$value=$ACCOUNT["customers_username"];
			$splt=preg_split("/".$this->delim_1."/",$fieldDesc['error_text']);
			if (($fieldDesc['textbox_min_length']>0 && strlen($value) < $fieldDesc['textbox_min_length']) || ($fieldDesc['textbox_max_length']>0 && strlen($value) > $fieldDesc['textbox_max_length'])) {
				$pass = false;
				$this->errors[]=$splt[0];
			}
			else {
				$add_option='';
				if ($customers_id>0){
					$add_option=" and customers_id!=" . $customers_id;//PWA
				}
				$check_name_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where LOWER(customers_username) = '" . strtolower(tep_db_input($value)) . "' and guest_account != '1'" . $add_option);
				$check_name = tep_db_fetch_array($check_name_query);
				if($check_name['total'] > 0) {
					$pass = false;
					$this->errors[]=$splt[1];
				}
				else {
					$CUSTOMER[$fieldDesc['uniquename']]=$value;
				}
			}
			return $pass;
		}
		
		function check__customers_email_address($fieldDesc)
		{
			global $CUSTOMER,$PREV_ERROR,$FREQUEST,$ACCOUNT;
			$pass=true;
			$value=$ACCOUNT["customers_email_address"];
			$customers_id=$FREQUEST->postvalue('customers_id','int',0);
			$splt=preg_split("/".$this->delim_1."/",$fieldDesc['error_text']);
			if (($fieldDesc['textbox_min_length']>0 && strlen($value) < $fieldDesc['textbox_min_length']) || ($fieldDesc['textbox_max_length']>0 && strlen($value) > $fieldDesc['textbox_max_length'])) {
				$pass = false;
				$this->errors[]=$splt[0];
			}
			elseif (!tep_validate_email($value)) {
				$pass = false;
				$this->errors[]=$splt[1];
			}
			else {
				$add_option='';
				if ($customers_id>0)
				{
					$add_option=" and customers_id!=" . $customers_id;
				}
				$check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . strtolower(tep_db_input($value)) . "'" . $add_option. " and guest_account != '1'");
				$check_email = tep_db_fetch_array($check_email_query);
				//PWA to avoid email exists error
				if ((PURCHASE_WITHOUT_ACCOUNT =='yes' or PURCHASE_NO_ACCOUNT=='yes')) 
				{
				$total_emails =50000;
				}else{
				$total_emails =0;
				}
				if ($check_email['total'] > $total_emails) {
				
					$pass = false;
					$this->errors[]=$splt[2];
				}
			}
			if ($pass){
				$CUSTOMER['customers_email_address']=$value;
			}
			else {
				$PREV_ERROR['customers_email_address']=1;
			}
			return $pass;
		}
		
		function check__customers_confirm_email_address($fieldDesc)
		{
			global $CUSTOMER,$PREV_ERROR,$ACCOUNT;
			if (isset($PREV_ERROR['customers_email_address'])) return true;
			$value=$ACCOUNT["customers_confirm_email_address"];
			$pass=true;
			if($ACCOUNT['customers_email_address']!=$value)
			{
				$pass = false;
				$this->errors[]=$fieldDesc['error_text'];
			}
			return $pass;
		}
		
		function check__customers_second_email_address($fieldDesc)
		{
			global $CUSTOMER,$ACCOUNT;
			$pass=true;
			$value=$ACCOUNT["customers_second_email_address"];
			if ($fieldDesc["required"]=='Y' && ($value=='' || !tep_validate_email($value)))
			{
				$pass=false;
				$this->errors[]=$fieldDesc['error_text'];
			}
			if($pass){
				$CUSTOMER[$fieldDesc['uniquename']]=$value;
			}
			return $pass;
		}
        function check__customers_photo($fieldDesc)
		{
            global $EXTRA,$ACCOUNT;
            $pass=true;
            $file=$ACCOUNT[$fieldDesc["uniquename"] ."_file"];
            $hidden=$ACCOUNT[$fieldDesc["uniquename"]];
            if ($fieldDesc['error_text']=='')
			{
                $fieldDesc['error_text']='Image Required##Invalid Image Type##Image Upload Failed##Image Size should be <3 KB';
            }
            $result=-1;
            require_once(DIR_WS_CLASSES . 'uploadNew.php');
            if (uploadNew::fileDataExists($fieldDesc["uniquename"] ."_file"))
			{
                $upload=new uploadNew($fieldDesc["uniquename"] ."_file",time() .'_' . $ACCOUNT['#1_firstname'],$this->maxImageSize);
                if (($result=$upload->parse())==0 && ($result=$upload->resizeAndSave(array(array('path'=>'customers/','width'=>HEADING_IMAGE_WIDTH,'height'=>''))))==0){
                    $EXTRA[$fieldDesc["uniquename"]]=$upload->filename;
                    $ACCOUNT[$fieldDesc["uniquename"]]='--virtual--' .$upload->filename;
                    if ($hidden!='' && strpos("/",$hidden)===false && strpos("\\",$hidden)===false) @unlink(DIR_WS_IMAGES .'customers/' . $hidden);
                } else {
                    $pass=false;
                }
            } else if ($hidden=='' && $fieldDesc["required"]=='Y')
			{
                $pass=false;
                $result=0;
            }
            if (($pos=strpos($hidden,'--virtual--'))!==false){
                $EXTRA[$fieldDesc["uniquename"]]=substr($hidden,11);
            }
            if (!$pass){
				$splt=preg_split("/".$this->delim_1."/",$fieldDesc['error_text']);
                $this->errors[]=$splt[abs($result)];
            }
            return $pass;
        }
		function check__country_state($fieldDesc)
		{
			global $ACCOUNT,$ADDRESS,$FREQUEST,$ACCOUNT;
			$splt=preg_split("/".$this->delim_1."/",$fieldDesc['error_text']);
			$pass=true;
			$country=isset($ACCOUNT['entry_country'])?(int)$ACCOUNT['entry_country']:0;
			$state=isset($ACCOUNT['entry_state'])?$ACCOUNT['entry_state']:'';
			$zone=isset($ACCOUNT['entry_zone_id'])?(int)$ACCOUNT['entry_zone_id']:0;
			$zone_id=0;
			if (is_numeric($country) == false || $country<=0) 
			{
				$pass = false;
				$this->errors[]=$splt[0];
			} else {
				$zone_id=0;
				$check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . $country . "'");
				$check = tep_db_fetch_array($check_query);
				$entry_state_has_zones = ($check['total'] > 0);
				if ($entry_state_has_zones == true) {
					$zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . $country . "' and zone_id='" . tep_db_input($zone) . "'");
					if (tep_db_num_rows($zone_query) == 1) {
						$zone = tep_db_fetch_array($zone_query);
						$zone_id = $zone['zone_id'];
					} else {
						$pass = false;
						$this->errors[]=$splt[1];
					}
				} else {
					if (($fieldDesc['textbox_min_length']>0 && strlen($state) < $fieldDesc['textbox_min_length']) || ($fieldDesc['textbox_max_length']>0 && strlen($state) > $fieldDesc['textbox_max_length'])) {
						$pass = false;
						$this->errors[]=$splt[2];
					}
				}
			}
			if ($pass)
			{
				$ADDRESS['entry_country_id']=$country;
				$ADDRESS['entry_zone_id']=$zone_id;
				$ADDRESS['entry_state']=$state;
			}
			return $pass;
		}
		function check__customers_referal($fieldDesc)
		{
			global $INFO,$ACCOUNT;
			$pass=true;
			$splt=preg_split("/".$this->delim_1."/",$fieldDesc['error_text']);

			$source=isset($ACCOUNT['entry_source'])?(int)$ACCOUNT['entry_source']:0;
			$other=isset($ACCOUNT['entry_source_other'])?$ACCOUNT['entry_source_other']:'';
			if($fieldDesc['required'] && $source<=0) {
				$pass = false;
				$this->errors[]=$splt[0];
			}
			else if($fieldDesc['required'] && $source==9999 && $other=='')
			{
				$this->errors[]=$splt[1];
				$pass = false;
			}
			else {
				$INFO["customers_info_source_id"]=$source;
				$INFO['source_other']=$other;
			}
			return $pass;
		}
		function check__password_and_confirm($fieldDesc)
		{
			if(!isset($GET['guest']) && !isset($GET['guest'])) 
			{
				global $CUSTOMER,$FREQUEST,$ACCOUNT;
				$splt=preg_split("/".$this->delim_1."/",$fieldDesc['error_text']);
				$pass=true;
				$password=isset($ACCOUNT['customers_password'])?$ACCOUNT['customers_password']:'';
				$confirm=isset($ACCOUNT['customers_password_confirm'])?$ACCOUNT['customers_password_confirm']:'';
				if(($fieldDesc['textbox_min_length']>0 && strlen($password) < $fieldDesc['textbox_min_length']) || ($fieldDesc['textbox_max_length']>0 && strlen($password) > $fieldDesc['textbox_max_length'])) {
					$pass = false;
					$this->errors[]=$splt[0];
				}
				elseif ($password != $confirm) 
				{
					$pass = false;
					$this->errors[]=$splt[1];
				}
				if($pass){
					$CUSTOMER['customers_password']=tep_encrypt_password($password);
					$CUSTOMER['encryption_style']=defined('ENCRYPTION_STYLE')?ENCRYPTION_STYLE:'O';
				}
				return $pass;
			}
		}
		
		function check__security_code($fieldDesc)
		{
			global $ACCOUNT;
			$pass=true;
			if($fieldDesc['required']!='Y') return $pass;
			include_once(DIR_WS_CLASSES.'loader.php');
			$loader=new loader();
			$captcha=$loader->loadClass('captcha','SecureImage');
			if(!$captcha->enabled){return $pass;}
			$splt=preg_split("/".$this->delim_1."/",$fieldDesc['error_text']);
			$value=isset($ACCOUNT[$fieldDesc['uniquename']])?$ACCOUNT[$fieldDesc['uniquename']]:''; 
			if(!$captcha->validate($value)){
				$pass=false;
				$this->errors[]=$splt[1];
			}
			return $pass;
		}
		
		function commonEntries($key,$data)
		{
			global $FREQUEST,$ACCOUNT;
			if (strpos($key,"#")!==false){
				$key1=str_replace("#1","customers",$key);
				$key2=str_replace("#1","entry",$key);
				if (isset($data[$key1])) $ACCOUNT[$key]=$data[$key1];
				elseif (isset($data[$key2])) $ACCOUNT[$key]=$data[$key2];
			}
			else if (isset($data[$key])){
				$ACCOUNT[$key]=$data[$key];
			}
		}
		
		function getdb__customers_dob($data)
		{
			global $ACCOUNT;
			$ACCOUNT["customers_dob"]=format_date($data["customers_dob"]);
		}
		
		function getdb__customers_referral($data)
		{
			global $ACCOUNT;
			$query=tep_db_query("SELECT i.customers_info_source_id,sou.sources_other_name from " . TABLE_CUSTOMERS_INFO . " i left join " . TABLE_SOURCES_OTHER . " sou on i.customers_info_id=sou.customers_id and i.customers_info_source_id=9999 where i.customers_info_id=" . $data['customers_id']);
			$result=tep_db_fetch_array($query);
			$ACCOUNT["entry_source"]=$data["customers_info_source_id"];
			$ACCOUNT["entry_source_other"]=$data["sources_other_name"];
		}
		
		function getdb__customers_confirm_email_address($data)
		{
			global $ACCOUNT;
			$ACCOUNT["customers_confirm_email_address"]=$data["customers_email_address"];
		}
		
		function getdb__country_state($data){
			global $ACCOUNT;
			$ACCOUNT["entry_country"]=$data["entry_country_id"];
			$ACCOUNT["entry_state"]=$data["entry_state"];
			$ACCOUNT["entry_zone_id"]=$data["entry_zone_id"];
		}
		
		function getdb__customers_photo($data){
			global $ACCOUNT;
			$ACCOUNT['customers_photo']=$data['customers_photo'];
		}
		
		function &getFieldsDescription(){
			global $FSESSION,$action;
			//get the required display fields
			$fieldsDesc=array();
			$query=tep_db_query("SELECT cif.*,cifd.label_text,cifd.input_description,cifd.error_text,cifd.input_title from ".  TABLE_CUSTOMERS_INFO_FIELDS . " cif, " . TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION . " cifd where cif.info_id=cifd.info_id and cifd.languages_id=" . $FSESSION->languages_id. " and cif.display_page like '%C%' and cif.active='Y' order by cif.sort_order");
			$icnt=0;
			while($fieldsDesc[$icnt]=tep_db_fetch_array($query))
			{
				$fieldDesc=&$fieldsDesc[$icnt];
				if(strpos($fieldDesc['error_text'],"==")!==false)
				{
				$fieldDesc['error_text']=str_replace(array("==MIN==","==MAX==","==DATE_FORMAT==","==EX_DATE=="),array($fieldDesc['textbox_min_length'],$fieldDesc['textbox_max_length'],$format[EVENTS_DATE_FORMAT],format_date('1970-05-20')),$fieldDesc['error_text']);
			}
			
			if(strpos($fieldDesc['input_description'],"==")!==false)
			{
				$fieldDesc['input_description']=str_replace(array("==MIN==","==MAX==","==DATE_FORMAT==","==EX_DATE=="),array($fieldDesc['textbox_min_length'],$fieldDesc['textbox_max_length'],$format[EVENTS_DATE_FORMAT],format_date('1970-05-20')),$fieldDesc['input_description']);
			}
			
			if($fieldDesc["extra_param"]!='')
			{
				$temp_splt=preg_split("/;/",$fieldDesc["extra_param"]);
				for ($jcnt=0,$n=count($temp_splt);$jcnt<$n;$jcnt++)
				{
				$temp_splt1=preg_split("/=/",$temp_splt[$jcnt]);
					$fieldDesc[$temp_splt[0]]=$temp_splt[1];
				}
			}
			
			if($fieldDesc['input_type']=="C" && !isset($fieldDesc["checkon"]))
			{
				$fieldDesc["checkon"]=1;
				$fieldDesc["checkoff"]=0;
			}
			$icnt++;
			if($action=="process"){
				continue;
			}
			if(method_exists($customerAccount,"getdb__" . $fieldDesc['uniquename'])){
				$this->{"getdb__" . $fieldDesc['uniquename']}($customer_result);
				}
				else 
				{
					$this->commonEntries($fieldDesc['uniquename'],$customer_result);
				}
			}
			unset($fieldsDesc[$icnt]);
			return $fieldsDesc;
		}
	}
?>
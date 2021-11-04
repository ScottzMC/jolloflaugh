<?php

 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

	class customerAccount{
		var $requiredText;
		var $delim_1;
		var $delim_2;
		var $errors;
		function __construct() {
			$this->requiredText=TEXT_FIELD_REQUIRED;
			$this->delim_1="##";
			$this->delim_2="@@";
			$this->errors=array();
		}
		function commonInput($fieldDesc){
			global $ACCOUNT;
			if ($fieldDesc['input_type']=="L") {
				echo '<h2>' . $fieldDesc['label_text'] . '</h2>';
				return;
			}
			$value=(isset($ACCOUNT[$fieldDesc["uniquename"]])?$ACCOUNT[$fieldDesc["uniquename"]]:$fieldDesc['default_value']);
			$this->_displayTitle($fieldDesc['show_label'],$fieldDesc['label_text']);
			echo '<div>';
			switch($fieldDesc['input_type']){
				case 'D':
					$options=$this->_getOptionArr($fieldDesc['options_values']);
					echo tep_draw_pull_down_menu($fieldDesc['uniquename'],$options,$value,'title=' . $fieldDesc['input_description']) . '&nbsp;';
					break;
				case 'O':
					$options=$this->_getOptionArr($fieldDesc['options_values']);
					for ($icnt=0,$n=count($options);$icnt<$n;$icnt++){
						echo tep_draw_radio_field($fieldDesc['uniquename'],$options[$icnt]['id'],($value==$options[$icnt]['id'] || $value==$options[$icnt]['text']?true:false),'','title=' . $fieldDesc['input_description']) . $options[$icnt]['text'] . '&nbsp;';
					}
					break;
				case 'C':
					echo tep_draw_checkbox_field($fieldDesc['uniquename'],$fieldDesc["checkon"],($value==$fieldDesc["checkon"]?true:false),'','title=' . $fieldDesc['input_description']) . '&nbsp;';
					break;
				case 'A':
					$width=50;
					$height=5;
					if ($fieldDesc['textbox_size']!=''){
						$splt=preg_split($this->delim_1,$fieldDesc['textbox_size']);
						$width=$splt[0];
						if (!$splt[1] || $splt[1]=='') $height=$splt[0];
						else $height=$splt[1];
					}
					echo tep_draw_textarea_field($fieldDesc['uniquename'],'wrap',$width,$height,$value,'title=' . $fieldDesc['input_title'],false) . '&nbsp;';
					break;
				default:
					echo tep_draw_input_field($fieldDesc['uniquename'],$value,'title=' . $fieldDesc['input_title'] .' autocomplete="off" size=' . $fieldDesc['textbox_size'],false,'text',false) . '&nbsp;';
					break;
			}
			$this->__display_inputsuffix($fieldDesc); // Change by Roy
			echo '</div>';
		}
		function commonCheck($fieldDesc){
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
					} else if ($value!=$fieldDesc["checkon"] && $value!=$fieldDesc["checkoff"]) {
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
			if (!$pass){
				$this->errors[]=$fieldDesc["error_text"];
			} else {
				if (strpos($fieldDesc["storage_type"],"C")!==false){
					$key=str_replace("#1","customers",$fieldDesc["uniquename"]);
                    if($fieldDesc["uniquename"]=='customers_occupation' || $fieldDesc["uniquename"]=='customers_interest')
                        $CUSTOMER[$key]=(int)$value;
                    else
                        $CUSTOMER[$key]=$value;
				}
				if (strpos($fieldDesc["storage_type"],"A")!==false){
					$key=str_replace("#1","entry",$fieldDesc["uniquename"]);
					$ADDRESS[$key]=$value;
				}
				if (strpos($fieldDesc["storage_type"],"I")!==false){
					$INFO[$fieldDesc['uniquename']]=$value;
				}
				if (strpos($fieldDesc["storage_type"],"E")!==false){
					$EXTRA[$fieldDesc['uniquename']]=$value;
				}
			}
			return $pass;
		}
		function &_getOptionArr($values){
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
		
		function _checkOption($values,$checkValue){
			//$splt=split($this->delim_1,$values);
			$splt=preg_split("/".$this->delim_1."/",$values);
			$values=$this->delim_1 . str_replace($this->delim_2,$this->delim_1,$values) . $this->delim_1;
			if(strpos(strtolower($values),$this->delim_1 . strtolower($checkValue) . $this->delim_1)!==false){
				return true;
			}
			else {
				return false;
			}
		}
		function _displayTitle($show_label,$label_text){
			echo '<h3>';
			if ($show_label=="Y"){
				echo $label_text;
			}
			echo '</h3>';
		}
		function edit__customers_photo($fieldDesc){
			global $ACCOUNT;
		
			$img=$value=(isset($ACCOUNT[$fieldDesc["uniquename"]])?$ACCOUNT[$fieldDesc["uniquename"]]:$fieldDesc['default_value']);
		
			if(($pos=strpos($value,'--virtual--'))!==false){
				$img=substr($value,11);
			}
		
			$this->_displayTitle($fieldDesc['show_label'],$fieldDesc['label_text']);
			echo '<div>';
			//take away upload not works
			//echo '<div><input type="file" name="'. $fieldDesc["uniquename"] . '_file" size="'.$fieldDesc["textbox_size"] .'" accept="image/gif, image/jpeg, image/jpg, image/png"/>';
			echo tep_draw_hidden($fieldDesc["uniquename"],$value);
			if ($fieldDesc['required']=='Y'){
				//echo '<span class="required">' . $this->requiredText . '</span>';
			}
			echo '<span class="desc">' . $fieldDesc['input_description'] . '</span>';
			if ($img!=""){
				echo '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . "customers/" . $img,'','','','aign="absmiddle"');
			}
			echo '<div>';
		}
		//its here but we can't use it
		// function check__customers_photo($fieldDesc){
            // global $EXTRA,$ACCOUNT;
            // $pass=true;
            // $file=$ACCOUNT[$fieldDesc["uniquename"] ."_file"];
            // $hidden=$ACCOUNT[$fieldDesc["uniquename"]];
            // if ($fieldDesc['error_text']==''){
                // $fieldDesc['error_text']='Image Required##Invalid Image Type##Image Upload Failed##Image Size should be <3 KB';
            // }
            // $result=-1;
            // require_once(DIR_WS_CLASSES . 'uploadNew.php');
            // if (uploadNew::fileDataExists($fieldDesc["uniquename"] ."_file")){
                // $upload=new uploadNew($fieldDesc["uniquename"] ."_file",time() .'_' . $ACCOUNT['#1_firstname'],$this->maxImageSize);
                // if (($result=$upload->parse())==0 && ($result=$upload->resizeAndSave(array(array('path'=>'customers/','width'=>SMALL_IMAGE_WIDTH,'height'=>''))))==0){
                    // $EXTRA[$fieldDesc["uniquename"]]=$upload->filename;
                    // $ACCOUNT[$fieldDesc["uniquename"]]='--virtual--' .$upload->filename;
                    // if ($hidden!='' && strpos("/",$hidden)===false && strpos("\\",$hidden)===false) @unlink(DIR_WS_IMAGES .'customers/' . $hidden);
                // } else {
                    // $pass=false;
                // }
            // } else if ($hidden=='' && $fieldDesc["required"]=='Y'){
                // $pass=false;
                // $result=0;
            // }
            // if (($pos=strpos($hidden,'--virtual--'))!==false){
                // $EXTRA[$fieldDesc["uniquename"]]=substr($hidden,11);
            // }
            // if (!$pass){
				// $splt=preg_split("/".$this->delim_1."/",$fieldDesc['error_text']);
                // $this->errors[]=$splt[abs($result)];
            // }
            // return $pass;
        // }
		function __display_inputsuffix($fieldDesc){
			if ($fieldDesc['required']=='Y'){
				echo '<span class="required">' . $this->requiredText . '</span>&nbsp;';
			}
			echo '&nbsp;&nbsp;<span class="desc">' . $fieldDesc['input_description'] . '</span>';
		}
		
		function edit__country_state($fieldDesc){
		 	global $ACCOUNT;
		 	$splt=preg_split("/".$this->delim_1."/",$fieldDesc['label_text']);
			echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td class="main">';
			$this->_displayTitle($fieldDesc['show_label'],$splt[0]);

			$country=STORE_COUNTRY;
			if ($ACCOUNT['entry_country']){
				$country=$ACCOUNT['entry_country'];
			} else if ($fieldDesc['default_value']!=''){
				$country=$fieldDesc['default_value'];
			}
//            else if (isset($fieldDesc['default_value'])){
//				$country=$fieldDesc['default_value'];
//			}
			$state=isset($ACCOUNT['entry_state'])?$ACCOUNT['entry_state']:'';
			$zone_id=isset($ACCOUNT['entry_zone_id'])?(int)$ACCOUNT['entry_zone_id']:STORE_ZONE;

			$countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
			$countries = tep_get_countries();

			for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
				$countries_array[] =  $countries[$i];
			}

			echo '<div>' . tep_draw_pull_down_menu('entry_country', $countries_array, strtolower($country), 'onChange="javascript:getCountryStates();" style="width:200px"') . '&nbsp;<span class="required">' . $this->requiredText . '</span><span class="desc">' . $fieldDesc['input_description'] . '</span></div>';
			echo '</td></tr><tr><td>';
			$this->_displayTitle($fieldDesc['show_label'],$splt[1]);

			$zones_array = array();
			$zones_query = tep_db_query("select zone_id,zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' order by zone_name");
			while ($zones_values = tep_db_fetch_array($zones_query)) {
				$zones_array[] = array('id' => $zones_values['zone_id'], 'text' => $zones_values['zone_name']);
			}
			echo '<div>';
			echo tep_draw_input_field('entry_state',$state,'id="state" '.((count($zones_array)>0)?' style="display:none"':'') );
			echo tep_draw_pull_down_menu('entry_zone_id', $zones_array,$zone_id,'id="zone_id" style="width:200px;'.((count($zones_array)<=0)?' display:none"':'"')) . '&nbsp;<span class="required">' . $this->requiredText . '</span>';
			echo '</div></td></tr></table>';
		}
		
        function edit__customers_discount($fieldDesc) {
            global $ACCOUNT;

			echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td class="main">';
			$this->_displayTitle($fieldDesc['show_label'],$fieldDesc['label_text']);

			if (isset($ACCOUNT['customers_discount'])){
				$customers_discount= $ACCOUNT['customers_discount'];
			} else if (isset($fieldDesc['default_value'])){
				$customers_discount=$fieldDesc['default_value'];
			}

             $discount_type=substr($customers_discount,0,1);
                if($discount_type=='-' || $discount_type=='+')
                    $discount_amount=substr($customers_discount,1);
                else {
                    $discount_type='+';
                    $discount_amount=$customers_discount;
                }

            $discount_array = array(array('id' => '-', 'text' => '-'),array('id' => '+' , 'text' => '+'));

			echo  tep_draw_pull_down_menu('customers_discount_sign', $discount_array, $discount_type) . '&nbsp' ;
            echo tep_draw_input_field('customers_discount',$discount_amount,'size="15"') . '&nbsp' . '%';
            if ($fieldDesc['required']=='Y'){
				echo '&nbsp;<span class="required">' . $this->requiredText . '</span>';
			}
			echo '</td></tr>';			
			echo '</table>';

        }

		function edit__customers_occupation($fieldDesc){

			global $ACCOUNT;

            $value=(isset($ACCOUNT[$fieldDesc["uniquename"]])?$ACCOUNT[$fieldDesc["uniquename"]]:$fieldDesc['default_value']);
			$this->_displayTitle($fieldDesc['show_label'],$fieldDesc['label_text']);
			echo '<div>' .  tep_draw_pull_down_menu($fieldDesc['uniquename'],tep_get_customer_options('O'),$value);
			$this->__display_inputsuffix($fieldDesc); // Change by Roy
			echo '</div>';
		}

        function edit__suspend_from($fieldDesc){

			global $ACCOUNT,$format;

            $value=(isset($ACCOUNT[$fieldDesc["uniquename"]])?$ACCOUNT[$fieldDesc["uniquename"]]:$fieldDesc['default_value']);
			$this->_displayTitle($fieldDesc['show_label'],$fieldDesc['label_text']);

            $_array=array('d','m','Y');  $replace_array=array('DD','MM','YYYY'); 	$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);
            echo '<div>' . tep_draw_input_field($fieldDesc['uniquename'],$value,"size=10",false,'text',false). '&nbsp' .
							'<a href="javascript:show_calendar(\'customers.suspend_from\',null,null,\'' .  $date_format . '\');"
							onmouseover="window.status=\'Date Picker\';return true;"
							onmouseout="window.status=\'\';return true;"><img border="none" src="images/icon_calendar.gif"/>
							</a>';
			//echo '<div>' .  tep_draw_pull_down_menu($fieldDesc['uniquename'],tep_get_customer_options('O'),$value);
			$this->__display_inputsuffix($fieldDesc); // Change by Roy
			echo '</div>';
		}

         function edit__resume_from($fieldDesc){

			global $ACCOUNT,$format;

            $value=(isset($ACCOUNT[$fieldDesc["uniquename"]])?$ACCOUNT[$fieldDesc["uniquename"]]:$fieldDesc['default_value']);
			$this->_displayTitle($fieldDesc['show_label'],$fieldDesc['label_text']);

            $_array=array('d','m','Y');  $replace_array=array('DD','MM','YYYY'); 	$date_format=str_replace($_array,$replace_array,EVENTS_DATE_FORMAT);
            echo '<div>' . tep_draw_input_field($fieldDesc['uniquename'],$value,"size=10",false,'text',false). '&nbsp' .
							'<a href="javascript:show_calendar(\'customers.resume_from\',null,null,\'' .  $date_format . '\');"
							onmouseover="window.status=\'Date Picker\';return true;"
							onmouseout="window.status=\'\';return true;"><img border="none" src="images/icon_calendar.gif"/>
							</a>';
			//echo '<div>' .  tep_draw_pull_down_menu($fieldDesc['uniquename'],tep_get_customer_options('O'),$value);
			$this->__display_inputsuffix($fieldDesc); // Change by Roy
			echo '</div>';
		}
		function edit__customers_interest($fieldDesc){
			global $ACCOUNT;
			$value=(isset($ACCOUNT[$fieldDesc["uniquename"]])?$ACCOUNT[$fieldDesc["uniquename"]]:$fieldDesc['default_value']);
			$this->_displayTitle($fieldDesc['show_label'],$fieldDesc['label_text']);
			echo '<div>' .  tep_draw_pull_down_menu($fieldDesc['uniquename'],tep_get_customer_options('I'),$value);
			$this->__display_inputsuffix($fieldDesc); // Change by Roy
			echo '<div>';
		}
        function edit__customers_groups_id($fieldDesc){
			global $ACCOUNT;
			$value=(isset($ACCOUNT[$fieldDesc["uniquename"]])?$ACCOUNT[$fieldDesc["uniquename"]]:$fieldDesc['default_value']);
			$this->_displayTitle($fieldDesc['show_label'],$fieldDesc['label_text']);
            //$groups_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
            $groups_query = tep_db_query("select customers_groups_id, customers_groups_name from " . TABLE_CUSTOMERS_GROUPS ." order by customers_groups_name");
             while($groups = tep_db_fetch_array($groups_query)) {
                $groups_array[] = array('text' => $groups['customers_groups_name'],
                                        'id' => $groups['customers_groups_id']);
             }
			echo '<div>' .  tep_draw_pull_down_menu($fieldDesc['uniquename'],$groups_array,$value);
			
			echo '<div>';
		}
		function edit__customers_referal($fieldDesc){
		 	global $ACCOUNT;
		 	$splt=preg_split($this->delim_1,$fieldDesc['label_text']);
			echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td class="main">';
			$this->_displayTitle($fieldDesc['show_label'],$splt[0]);

			$value=(isset($ACCOUNT['entry_source'])?$ACCOUNT['entry_source']:$fieldDesc['default_value']);

		    $sources_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
		    $sources = tep_get_sources();

		    for ($i=0, $n=sizeof($sources); $i<$n; $i++) {
		      	$sources_array[] = array('id' => $sources[$i]['sources_id'], 'text' => $sources[$i]['sources_name']);
		    }
			$sources_array[] = array('id' => '9999', 'text' => TEXT_REFERRAL_OTHER);

		    echo '<div>' . tep_draw_pull_down_menu('entry_source', $sources_array, $value, 'onChange="javascript:(this.options[this.selectedIndex].value==\'9999\')?document.getElementById(\'refer_other\').style.display=\'\':document.getElementById(\'refer_other\').style.display=\'none\';"');
			if ($fieldDesc['required']=='Y'){
				echo '&nbsp;<span class="required">' . $this->requiredText . '</span>';
			}
			echo '<span class="desc">' . $fieldDesc['input_description'] . '</span>';
			echo '</div></td></tr><tr id="refer_other" style="display:'.($ACCOUNT['entry_source']=='9999'?'normal':'none') . '"><td>';
			$this->_displayTitle($fieldDesc['show_label'],$splt[1]);
			echo '<div>';

			$value=(isset($ACCOUNT['entry_source_other'])?$ACCOUNT['entry_source_other']:'');
			echo tep_draw_input_field('entry_source_other',$value,'title=' . $fieldDesc['input_title'] .' size=' . $fieldDesc['textbox_size'],false,'text',false);
			if ($fieldDesc['required']=='Y'){
				echo '&nbsp;<span class="required">' . $this->requiredText . '</span>';
			}
			echo '</div></td></tr></table>';
		}
		function edit__password_and_confirm($fieldDesc){
			$splt=preg_split("/".$this->delim_1."/",$fieldDesc['label_text']);
			echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td class="main">';
			$this->_displayTitle($fieldDesc['show_label'],$splt[0]);
			echo '<div>' . tep_draw_password_field('customers_password','',false,'onKeyUp=javascript:checkPWDStrength(this);') . '&nbsp;<span class="required">' . $this->requiredText . '</span>&nbsp;<span id="strength_info" class="desc"></span></div>';
			echo '</td></tr><tr><td>';
			$this->_displayTitle($fieldDesc['show_label'],$splt[1]);
			echo '<div>' . tep_draw_password_field('customers_password_confirm') .'&nbsp;<span class="required">' . $this->requiredText . '</span>&nbsp;<span class="desc">' . $fieldDesc['input_description'] . '</span></div>';
			echo '</td></tr></table>';
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

        function check__suspend_from($fieldDesc){
			global $CUSTOMER,$ACCOUNT;
			$pass=true;
			$value=str_replace("/","-",$ACCOUNT['suspend_from']);
            if ($fieldDesc["required"]=='Y' || $value!='') {
                if (!tep_check_date_raw($value,EVENTS_DATE_FORMAT)) {
                    $this->errors[]=$fieldDesc['error_text'];
                    $pass=false;
                }
            }
            if($pass){
                if($value=='')
                    $CUSTOMER[$fieldDesc['uniquename']]='0000-00-00';
                else
                    $CUSTOMER[$fieldDesc['uniquename']]=tep_convert_date_raw($value);
            }
			return $pass;
		}

        function check__resume_from($fieldDesc){
			global $CUSTOMER,$ACCOUNT;
			$pass=true;
			$value=str_replace("/","-",$ACCOUNT['resume_from']);
            if ($fieldDesc["required"]=='Y' || $value!='') {
                if (!tep_check_date_raw($value,EVENTS_DATE_FORMAT)) {
                    $this->errors[]=$fieldDesc['error_text'];
                    $pass=false;
                }
            }
            if($pass) {
                if($value=='')
                    $CUSTOMER[$fieldDesc['uniquename']]='0000-00-00';
                else
                    $CUSTOMER[$fieldDesc['uniquename']]=tep_convert_date_raw($value);
			}
			return $pass;
		}
		function check__customers_username($fieldDesc){
			global $CUSTOMER,$FREQUEST,$ACCOUNT;
			$pass=true;
			$customers_id=$FREQUEST->postvalue('customers_id','int',0);
			$value=$ACCOUNT["customers_username"];
			//$splt=preg_split($this->delim_1,$fieldDesc['error_text']);
			$splt=preg_split("/".$this->delim_1."/",$fieldDesc['error_text']);
			if (($fieldDesc['textbox_min_length']>0 && strlen($value) < $fieldDesc['textbox_min_length']) || ($fieldDesc['textbox_max_length']>0 && strlen($value) > $fieldDesc['textbox_max_length'])) {
				$pass = false;
				$this->errors[]=$splt[0];
			} else {
				$add_option='';
				if ($customers_id>0){
					$add_option=" and customers_id!=" . $customers_id;
				}
				$check_name_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where LOWER(customers_username) = '" . strtolower(tep_db_input($value)) . "' " . $add_option);
				$check_name = tep_db_fetch_array($check_name_query);
				if ($check_name['total'] > 0) {
					$pass = false;
					$this->errors[]=$splt[1];
				} else {
					$CUSTOMER[$fieldDesc['uniquename']]=$value;
				}
			}
			return $pass;
		}
		function check__customers_email_address($fieldDesc){
			global $CUSTOMER,$PREV_ERROR,$FREQUEST,$ACCOUNT;
			$pass=true;
			$value=$ACCOUNT["customers_email_address"];
			$customers_id=$FREQUEST->postvalue('customers_id','int',0);
			//$splt=preg_split($this->delim_1,$fieldDesc['error_text']);
			$splt=preg_split("/".$this->delim_1."/",$fieldDesc['error_text']);
			if (($fieldDesc['textbox_min_length']>0 && strlen($value) < $fieldDesc['textbox_min_length']) || ($fieldDesc['textbox_max_length']>0 && strlen($value) > $fieldDesc['textbox_max_length'])) {
				$pass = false;
				$this->errors[]=$splt[0];
			} elseif (!tep_validate_email($value)) {
				$pass = false;
				$this->errors[]=$splt[1];
			} else {
				$add_option='';
				if ($customers_id>0){
					$add_option=" and customers_id!=" . $customers_id;
				}
				$check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . strtolower(tep_db_input($value)) . "'" . $add_option);
				$check_email = tep_db_fetch_array($check_email_query);
				if ($check_email['total'] > 0) {
					$pass = false;
					$this->errors[]=$splt[2];
				}
			}
			if ($pass){
				$CUSTOMER['customers_email_address']=$value;
			} else {
				$PREV_ERROR['customers_email_address']=1;
			}
			return $pass;
		}
		function check__customers_confirm_email_address($fieldDesc){
			global $CUSTOMER,$PREV_ERROR,$ACCOUNT;
			if (isset($PREV_ERROR['customers_email_address'])) return true;
			$value=$ACCOUNT["customers_confirm_email_address"];
			$pass=true;
			if($ACCOUNT['customers_email_address']!=$value){
				$pass = false;
				$this->errors[]=$fieldDesc['error_text'];
			}
			return $pass;
		}
		function check__customers_second_email_address($fieldDesc){
			global $CUSTOMER,$ACCOUNT;
			$pass=true;
			$value=$ACCOUNT["customers_second_email_address"];
			if ($fieldDesc["required"]=='Y' && ($value=='' || !tep_validate_email($value))){
				$pass=false;
				$this->errors[]=$fieldDesc['error_text'];
			}
			if ($pass){
				$CUSTOMER[$fieldDesc['uniquename']]=$value;
			}
			return $pass;
		}
        function check__customers_discount($fieldDesc){
			global $CUSTOMER,$ACCOUNT;
			$pass=true;
			$value=$ACCOUNT['customers_discount'];
			if ($fieldDesc['required']=='Y'){
                if ($value==''){
                    $this->errors[]=$fieldDesc['error_text'];
                    $pass=false;
                }
            }
            if($value=='') $value=0;
            if ($pass){
                $CUSTOMER[$fieldDesc['uniquename']]=$ACCOUNT['customers_discount_sign'] . $value;
            }
			return $pass;
		}
		function check__country_state($fieldDesc){
			global $ACCOUNT,$ADDRESS,$FREQUEST,$ACCOUNT;
			//$splt=preg_split($this->delim_1,$fieldDesc['error_text']);
			$splt=preg_split("/".$this->delim_1."/",$fieldDesc['error_text']);
			$pass=true;
			$country=isset($ACCOUNT['entry_country'])?(int)$ACCOUNT['entry_country']:0;
			$state=isset($ACCOUNT['entry_state'])?$ACCOUNT['entry_state']:'';
			$zone=isset($ACCOUNT['entry_zone_id'])?(int)$ACCOUNT['entry_zone_id']:0;
			$zone_id=0;
			if (is_numeric($country) == false || $country<=0) {
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
			if ($pass){
				$ADDRESS['entry_country_id']=$country;
				$ADDRESS['entry_zone_id']=$zone_id;
				$ADDRESS['entry_state']=$state;
			}
			return $pass;
		}
		function check__customers_referal($fieldDesc){
			global $INFO,$ACCOUNT;
			$pass=true;
			//$splt=preg_split($this->delim_1,$fieldDesc['error_text']);
			$splt=preg_split("/".$this->delim_1."/",$fieldDesc['error_text']);

			$source=isset($ACCOUNT['entry_source'])?(int)$ACCOUNT['entry_source']:0;
			$other=isset($ACCOUNT['entry_source_other'])?$ACCOUNT['entry_source_other']:'';
			if ($fieldDesc['required'] && $source<=0) {
				$pass = false;
				$this->errors[]=$splt[0];
			} else if ($fieldDesc['required'] && $source==9999 && $other==''){
				$this->errors[]=$splt[1];
				$pass = false;
			} else {
				$INFO["customers_info_source_id"]=$source;
				$INFO['source_other']=$other;
			}
			return $pass;
		}
		function check__password_and_confirm($fieldDesc){
			global $CUSTOMER,$FREQUEST,$ACCOUNT;
			//$splt=preg_split($this->delim_1,$fieldDesc['error_text']);
			$splt=preg_split("/".$this->delim_1."/",$fieldDesc['error_text']);
			$pass=true;

			$password=isset($ACCOUNT['customers_password'])?$ACCOUNT['customers_password']:'';
			$confirm=isset($ACCOUNT['customers_password_confirm'])?$ACCOUNT['customers_password_confirm']:'';

			if (($fieldDesc['textbox_min_length']>0 && strlen($password) < $fieldDesc['textbox_min_length']) || ($fieldDesc['textbox_max_length']>0 && strlen($password) > $fieldDesc['textbox_max_length'])) {
				$pass = false;
				$this->errors[]=$splt[0];
			} elseif ($password != $confirm) {
				$pass = false;
				$this->errors[]=$splt[1];
			}
			if ($pass){
				$CUSTOMER['customers_password']=tep_encrypt_password($password);
				$CUSTOMER['encryption_style']=defined('ENCRYPTION_STYLE')?ENCRYPTION_STYLE:'O';
			}
			return $pass;
		}
		function commonEntries($key,$data){
			global $FREQUEST,$ACCOUNT;
			if (strpos($key,"#")!==false){
				$key1=str_replace("#1","customers",$key);
				$key2=str_replace("#1","entry",$key);
				if (isset($data[$key1])) $ACCOUNT[$key]=$data[$key1];
				elseif (isset($data[$key2])) $ACCOUNT[$key]=$data[$key2];
			} else if (isset($data[$key])){
				$ACCOUNT[$key]=$data[$key];
			}
		}
		function getdb__customers_dob($data){
			global $ACCOUNT;
			$ACCOUNT["customers_dob"]=format_date($data["customers_dob"]);
		}
        function getdb__suspend_from($data){
			global $ACCOUNT;
			$ACCOUNT["suspend_from"]=format_date($data["suspend_from"]);
		}
        function getdb__resume_from($data){
			global $ACCOUNT;
			$ACCOUNT["resume_from"]=format_date($data["resume_from"]);
		}
		function getdb__customers_referral($data){
			global $ACCOUNT;
			$query=tep_db_query("SELECT i.customers_info_source_id,sou.sources_other_name from " . TABLE_CUSTOMERS_INFO . " i left join " . TABLE_SOURCES_OTHER . " sou on i.customers_info_id=sou.customers_id and i.customers_info_source_id=9999 where i.customers_info_id=" . $data['customers_id']);
			$result=tep_db_fetch_array($query);
			$ACCOUNT["entry_source"]=$data["customers_info_source_id"];
			$ACCOUNT["entry_source_other"]=$data["sources_other_name"];
		}
		function getdb__customers_confirm_email_address($data){
			global $ACCOUNT;
			$ACCOUNT["customers_confirm_email_address"]=$data["customers_email_address"];
		}
		function getdb__country_state($data){
			global $ACCOUNT;
			$ACCOUNT["entry_country"]=$data["entry_country_id"];
			$ACCOUNT["entry_state"]=$data["entry_state"];
			$ACCOUNT["entry_zone_id"]=$data["entry_zone_id"];
		}

        function getdb__customers_discount($data){
			global $ACCOUNT;
			$ACCOUNT["customers_discount"]=$data["customers_discount"];
		}
        function getdb__customers_groups_id($data){
			global $ACCOUNT;
			$ACCOUNT["customers_groups_id"]=$data["customers_groups_id"];
		}
         function &getFieldsDescription($customer_id,$action='',$pwd=false){
            global $FSESSION,$format;
            //get the required display fields
            $fieldsDesc=array();
            $customer_result=array();
            $cwhere=" like '%B1%'";
            $fields=" a.*, c.* ";
           
            
            if($pwd) {
                $fields=" c.customers_password";                
            }
            if($customer_id>0) {
                $cwhere=" like '%B2%'";
                 $customer_query=tep_db_query("select " . $fields . " from " . TABLE_CUSTOMERS ." c, " . TABLE_ADDRESS_BOOK . " a  where c.customers_default_address_id=a.address_book_id and c.customers_id=a.customers_id and c.customers_id='" . (int)$customer_id . "'");
                $customer_result=tep_db_fetch_array($customer_query);
			   //jan 2013 extra fields are missing so
				$extra_customer_query=tep_db_query("select * from customers_extra_info  where customers_id='" . (int)$customer_id . "'");
				
				while ($extra_customer_result=tep_db_fetch_array($extra_customer_query)){
				 $customer_result[$extra_customer_result['uniquename']]=$extra_customer_result['fieldvalue'];
				}
				//end jan 2013
            }
            $query=tep_db_query("SELECT cif.*,cifd.label_text,cifd.input_description,cifd.error_text,cifd.input_title from ".  TABLE_CUSTOMERS_INFO_FIELDS . " cif, " . TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION . " cifd where cif.info_id=cifd.info_id and cifd.languages_id='" . (int)$FSESSION->languages_id. "' and cif.display_page " . $cwhere . " and cif.active='Y' order by cif.sort_order");           
            if($pwd) {
                $query=tep_db_query("SELECT cif.*,cifd.label_text,cifd.input_description,cifd.error_text,cifd.input_title from ".  TABLE_CUSTOMERS_INFO_FIELDS . " cif, " . TABLE_CUSTOMERS_INFO_FIELDS_DESCRIPTION . " cifd where cif.info_id=cifd.info_id and cifd.languages_id='" . (int)$FSESSION->languages_id. "' and cif.uniquename='password_and_confirm' and cif.input_type!='L' and cif.active='Y' order by cif.sort_order");
            }
            $icnt=0;
            while($fieldsDesc[$icnt]=tep_db_fetch_array($query)){
                $fieldDesc=&$fieldsDesc[$icnt];
                if (strpos($fieldDesc['error_text'],"==")!==false){
                    $fieldDesc['error_text']=str_replace(array("==MIN==","==MAX==","==DATE_FORMAT==","==EX_DATE=="),array($fieldDesc['textbox_min_length'],$fieldDesc['textbox_max_length'],$format[EVENTS_DATE_FORMAT],format_date('1970-05-20')),$fieldDesc['error_text']);
                }
                if (strpos($fieldDesc['input_description'],"==")!==false){
                    $fieldDesc['input_description']=str_replace(array("==MIN==","==MAX==","==DATE_FORMAT==","==EX_DATE=="),array($fieldDesc['textbox_min_length'],$fieldDesc['textbox_max_length'],$format[EVENTS_DATE_FORMAT],format_date('1970-05-20')),$fieldDesc['input_description']);
                }
                if($fieldDesc["extra_param"]!=''){
                    $temp_splt=preg_split("/;/",$fieldDesc["extra_param"]);
                    for ($jcnt=0,$n=count($temp_splt);$jcnt<$n;$jcnt++){
                        $temp_splt1=preg_split("/=/",$temp_splt[$jcnt]);
                        $fieldDesc[$temp_splt1[0]]=$temp_splt1[1];
                    }
                }
                if ($fieldDesc['input_type']=="C" && !isset($fieldDesc["checkon"])){
                    $fieldDesc["checkon"]=1;
                    $fieldDesc["checkoff"]=0;
                } 
                $icnt++;
                if ($action=="process") continue;
                if (method_exists($this,"getdb__" . $fieldDesc['uniquename'])){
                    $this->{"getdb__" . $fieldDesc['uniquename']}($customer_result); // Change by Roy
                } else {
                    $this->commonEntries($fieldDesc['uniquename'],$customer_result);  // Change by Roy
                }
            }
            unset($fieldsDesc[$icnt]);
            return $fieldsDesc;
        }

	}
?>
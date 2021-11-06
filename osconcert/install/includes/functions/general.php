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
  function osc_is_writable($file) {
    if (strtolower(substr(PHP_OS, 0, 3)) === 'win') {
      if (file_exists($file)) {
        $file = realpath($file);
        if (is_dir($file)) {
          $result = @tempnam($file, 'osc');
          if (is_string($result) && file_exists($result)) {
            unlink($result);
            return (strpos($result, $file) === 0) ? true : false;
          }
        } else {
          $handle = @fopen($file, 'r+');
          if (is_resource($handle)) {
            fclose($handle);
            return true;
          }
        }
      } else{
        $dir = dirname($file);
        if (file_exists($dir) && is_dir($dir) && osc_is_writable($dir)) {
          return true;
        }
      }
      return false;
    } else {
      return is_writable($file);
    }
  }

  function osc_in_array($value, $array) {
    if (!$array) $array = array();

    if (function_exists('in_array')) {
      if (is_array($value)) {
        for ($i=0; $i<sizeof($value); $i++) {
          if (in_array($value[$i], $array)) return true;
        }
        return false;
      } else {
        return in_array($value, $array);
      }
    } else {
      reset($array);
      while (list(,$key_value) = each($array)) {
		
        if (is_array($value)) {
          for ($i=0; $i<sizeof($value); $i++) {
            if ($key_value == $value[$i]) return true;
          }
          return false;
        } else {
          if ($key_value == $value) return true;
        }
      }
    }

    return false;
  }

////
// Sets timeout for the current script.
// Cant be used in safe mode.
  function osc_set_time_limit($limit) {
    if (!get_cfg_var('safe_mode')) {
      set_time_limit($limit);
	  return true;
    }
	return false;
  }
  function osc_output_string_protected($string) {
    return osc_output_string($string, false, true);
  }
  function osc_output_string($string, $translate = false, $protected = false) {
    if ($protected == true) {
      return htmlspecialchars($string);
    } else {
      if ($translate == false) {
        return osc_parse_input_field_data($string, array('"' => '&quot;'));
      } else {
        return osc_parse_input_field_data($string, $translate);
      }
    }
  }
  function osc_parse_input_field_data($data, $parse) {
    return strtr(trim($data), $parse);
  }
  function osc_not_null($value) {
    if (is_array($value)) {
      if (sizeof($value) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
        return true;
      } else {
        return false;
      }
    }
  }
  function osc_title_bar($title){
  	echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr height="24">
				<td class="titleTopLeft"><img src="images/pixel_trans.gif" width="8" align="absmiddle"></td>
				<td class="titleTopMiddle">&nbsp;&nbsp;' . $title .'</td>
				<td class="titleTopRight"><img src="images/pixel_trans.gif" width="8" align="absmiddle"></td>
			</tr>
		  </table>
		';
  }
  function osc_draw_box_start($height=400){
	echo		'<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr height="7">
					<td class="contentTopLeft"><img src="images/pixel_trans.gif" width="6"></td>
					<td class="contentTopMiddle"><img src="images/pixel_trans.gif" width="6"></td>
					<td class="contentTopRight"><img src="images/pixel_trans.gif" width="6"></td>
				</tr>
				<tr>
					<td class="contentMiddleLeft">&nbsp;</td>
					<td bgcolor="#FFFFFF" valign="top">
					<div style="height:' . $height . 'px;overflow:auto" id="div_license" >';

  }
  function osc_draw_box_end(){
	echo 		'	</div>
					</td>
					<td class="contentMiddleRight">&nbsp;</td>
				</tr>
				<tr height="7">
					<td class="contentBottomLeft"><img src="images/pixel_trans.gif" width="6"></td>
					<td class="contentBottomMiddle"><img src="images/pixel_trans.gif" width="6"></td>
					<td class="contentBottomRight"><img src="images/pixel_trans.gif" width="6"></td>
				</tr>
				<tr height="10">
					<td></td>
				</tr>
				</table>
				';
  }
  
  function osc_encrypt_password($plain,$type='O') {
    $password = '';

    for ($i=0; $i<10; $i++) {
      $password .= osc_rand();
    }

   
    $salt = substr(md5($password), 0, 2);
	if ($type=="V"){
	    $password = md5(md5($plain) . $salt) . ':' . $salt;
	} else {
		$password = md5($salt . $plain) . ':' . $salt;
	}

    return $password;
  }
  function osc_rand($min = null, $max = null) {
    static $seeded;

    if (!$seeded) {
      mt_srand((double)microtime()*1000000);
      $seeded = true;
    }

    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }
	function osc_check_current_version(){
		global $PHP_SELF;
		$cur_content=@file_get_contents("../version.txt");
		$cur_content=strtolower($cur_content);
		$start_pos=strpos($cur_content,"<program_version>");
		if ($start_pos===false) return;
		$end_pos=strpos($cur_content,"</program_version>");
		if ($end_pos===false) return;
		$cur_version=substr($cur_content,$start_pos+17,$end_pos-($start_pos+17));
		$result="";
		$cur_date=date('Y-m-d');
		$site_content=@file_get_contents("");
		$site_content=strtolower($site_content);
		$start_pos=strpos($site_content,"<program_version>");
		if ($start_pos===false) return;
		$end_pos=strpos($site_content,"</program_version>");
		if ($end_pos===false) return;
		$site_version=substr($site_content,$start_pos+17,$end_pos-($start_pos+17));
		$cur_version_temp=preg_split("/[.]/",$cur_version);
		$site_version_temp=preg_split("/[.]/",$site_version);
		$check_version = sizeof($site_version_temp);
		$version_updation = false;
		if(sizeof($site_version_temp)<sizeof($cur_version_temp))
			$check_version = sizeof($cur_version_temp);
		for($i=0;$i<$check_version;$i++){
			if(!$site_version_temp[$i])$site_version_temp[$i]=0;
			if(!$cur_version_temp[$i])$cur_version_temp[$i]=0;
			if($site_version_temp[$i]>$cur_version_temp[$i]){
				return true;
			}else if($site_version_temp[$i]<$cur_version_temp[$i]){
				return false;
			}
		}
		return false;
	}
?>
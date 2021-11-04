<?php
/*
osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 

Copyright (c) 2003 osCommerce 

 

Freeway eCommerce
http://www.openfreeway.org
Copyright (c) 2007 ZacWare

Released under the GNU General Public License 
*/
// close session (store variables)

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<script>
<?php
	$js_string='';
	$js_func="";
	if ($JS_VARS && count($JS_VARS)>0){
		reset($JS_VARS);
		//FOREACH
		//while(list($key,)=each($JS_VARS)){
		foreach (array_keys($JS_VARS) as $key)
			{	
			$js_string.="var " . $key . "=";
			if (count($JS_VARS[$key])>0){
				json_string($JS_VARS[$key]);
			}
			$js_string.=";\n";
		}
	}
	if ($JS_FUNCS && count($JS_FUNCS)>0){
		reset($JS_FUNCS);
		foreach($JS_FUNCS as $key => $value) {	
			
			$js_func.=$value .";";
		}
	}
	echo $js_string;
?>
function pageLoaded(){<?php echo $js_func;?>}</script>
<?php
	$FSESSION->close();
	if (STORE_PAGE_PARSE_TIME == 'true') {
		$time_start = explode(' ', PAGE_PARSE_START_TIME);
		$time_end = explode(' ', microtime());
		$parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);
		error_log(strftime(STORE_PARSE_DATE_TIME_FORMAT) . ' - ' . getenv('REQUEST_URI') . ' (' . $parse_time . 's)' . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
		if (DISPLAY_PAGE_PARSE_TIME == 'true') {
			echo '<span class="smallText">Parse Time: ' . $parse_time . 's</span>';
		}
	}
?>

<?php //September 2012. Graeme Tyson. sakwoya@sakwoya.co.uk. Pseudo-cron call
 if (file_exists(DIR_WS_INCLUDES .'pseudo_cron.php')) {include_once(DIR_WS_INCLUDES .'pseudo_cron.php');}
 
	$sx=DESIGN_SNAP;
	echo '<script>$(function () {$( ".draggable" ).draggable({ grid: [ '.$sx.', '.$sx.' ] });});</script>';
 ?>
</body>
</html>
<?php
	define( '_FEXEC', 1 );
	require('includes/application_top.php');
	$command=tep_db_prepare_input($FREQUEST->getvalue('command'));
	$country=tep_db_prepare_input($FREQUEST->getvalue('country_id','int',0)); 
	$content="";
	//ajax start
	if($command=='show_state')
	{
		
		  $zone_ids="";
		  $zone_name="";
		  $zones_query = tep_db_query("select zone_id,zone_name,placement from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' order by placement desc,zone_id asc");
		  while ($zones_values = tep_db_fetch_array($zones_query)) {
			 $zone_ids.=$zones_values['zone_id']."{}";
			 $zone_name.=$zones_values['zone_name']."{}";
		  }
	   echo 'show_state^'.substr($zone_ids,0,-2)."^".substr($zone_name,0,-2)."^".$zone;  
	   exit;
	}   
?>
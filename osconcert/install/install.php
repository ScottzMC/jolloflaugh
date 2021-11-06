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
	
	osConcert Visual Seat Reservation
    Copyright (c) 2009 cartZone UK
	
	Released under the GNU General Public License 
*/ 

  require('includes/application.php');
  $page_file = 'install.php';
  $page_title = 'Installation';
  $step=(isset($HTTP_GET_VARS["step"])?$HTTP_GET_VARS["step"]:'');
  $upgrade=(isset($HTTP_GET_VARS["upg"])?$HTTP_GET_VARS["upg"]:'');
  if ($step==9){
  	if ($HTTP_GET_VARS["action"]=="complete" || $HTTP_GET_VARS["action"]=="email_update"){
		$page_title="Installation Complete";
	} else if ($HTTP_GET_VARS["action"]=="email" && isset($HTTP_POST_VARS["first_install"]) || $HTTP_POST_VARS["first_install"]!='1'){
		$page_title="Configuration - Email";
	} else {
		$page_title="Installation Compatibility test";
	}
  	$page_contents="install_9.php";
  } else if($step==10 && $upgrade=='license') {
  		$page_title='osConcert Licensing';
		$page_contents = 'license.php';
  } else if (isset($HTTP_POST_VARS["mode"]) || !isset($HTTP_POST_VARS["compat_test_pass"] ) && !$upgrade){
	  
  	$page_title="Installation Compatibility test";
	$page_contents="install_compat.php";
  } else {
	  switch ($step) {
		case '2':
		  if (osc_in_array('database', $HTTP_POST_VARS['install'])) {
		  	$page_title="Database Import";
			$page_contents = 'install_2.php';
		  } elseif (osc_in_array('configure', $HTTP_POST_VARS['install'])) {
			  $page_title="Configuration";
			$page_contents = 'install_4.php';
		  } else if($upgrade){
		  	 $page_title="Database Import";	 
			 $page_contents='upgrade_1.php';
		  }else {
			$page_contents = 'install.php';
		  }
		  break;
		case '3':
		  if (osc_in_array('database', $HTTP_POST_VARS['install'])) {
			$page_title="Database Import";
			$page_contents = 'install_3.php';
		  } else if($upgrade){  
		  	$page_title = "Database Import";	
			$page_contents = 'upgrade_2.php';
		  }else {
			$page_contents = 'install.php';
		  }
		  break;
		case '4':
		  if (osc_in_array('configure', $HTTP_POST_VARS['install'])) {
			$page_title="Configuration - Server";
			$page_contents = 'install_4.php';
		  } else {
			$page_contents = 'install.php';
		  }
		  break;
		case '5':
		  if (osc_in_array('configure', $HTTP_POST_VARS['install'])) {
			if (isset($HTTP_POST_VARS['ENABLE_SSL']) && ($HTTP_POST_VARS['ENABLE_SSL'] == 'true')) {
			  $page_title="Configuration - Server";
			  $page_contents = 'install_5.php';
			} else {
			  $page_title="Configuration - Database";
			  $page_contents = 'install_6.php';
			}
		  } else {
			$page_contents = 'install.php';
		  }
		  break;
		case '6':
		  if (osc_in_array('configure', $HTTP_POST_VARS['install'])) {
		  $page_title="Configuration - Database";
			$page_contents = 'install_6.php';
		  } else {
			$page_contents = 'install.php';
		  }
		  break;
		case '7':
		  if (osc_in_array('configure', $HTTP_POST_VARS['install'])) {
			$page_contents = 'install_7.php';
		  } else {
			$page_contents = 'install.php';
		  }
		  break;
		case '8':
		  if (osc_in_array('database', $HTTP_POST_VARS['install']) || osc_in_array('configure', $HTTP_POST_VARS['install'])) {
		  	$page_title="Configure osConcert";
			$page_contents = 'install_8.php';
		  } else {
			$page_contents = 'install.php';
		  }
		  break;
		default:
		  $page_title="New Installation";
		  $page_contents = 'install.php';
	  }
  }
  require('templates/main_page.php');
?>

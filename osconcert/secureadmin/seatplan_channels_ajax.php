<?php
	
	/* Seatplan Channels for osConcert */
	/* 2011 by Martin Zeitler, Germany */
	/*        */
	
	/* hook osCommerce */
	define( '_FEXEC', 1 );
	require('includes/application_top.php');

	/* get a handle */
	require(DIR_WS_CLASSES.'concert.php');
	$con = new concert;
	
	/* extract the GET/POST */
	$req = array_merge($_GET,$_POST);
	$mode = $req['mode'];
	$cPath = $req['cPath'];
	$latest_id = $req['id'];
	
	switch($mode){
		
		case 'update':				/* sending out the correct header for FireFox & Chrome */
													header('Content-type: application/json; Charset=utf8;');
		
													/* fall-back to IE compability mode */
		case 'update_ie':			$arr = $con->tep_getUpdates($cPath,$latest_id);
													echo json_encode($arr);
													break;
		
		default:							header('Content-type: application/json; charset=utf8;');
													$arr = array(
														'mode' => $mode,
														'method' => $_SERVER['REQUEST_METHOD']
													);
													echo json_encode($arr);
	}
?>
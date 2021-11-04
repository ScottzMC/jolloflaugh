<?php
	
	/*		jqGrid AJAX for osConcert			*/
	/*	2011 by Martin Zeitler, Germany	*/
	/*						*/
	
	/* hook osCommerce */
	define( '_FEXEC', 1 );
	require('includes/application_top.php');

	/* get a handle */
	require(DIR_WS_CLASSES.'concert.php');	
	$con = new concert;
	
	/* extract the GET/POST */
	$req = array_merge($_GET,$_POST);
	$mode = $req['mode'];
	$id = $req['id'];
	$page = $req['page'];
	$rows = $req['rows'];
	$order_by = $req['sidx'];
	$sort_order = $req['sord'];
	$search = $req['_search'];
	$lang = $req['lang'];
	
	switch($mode){
		
		case 'grid':					/* sending out the correct header for FireFox & Chrome */
													header('Content-type: application/json; Charset=utf8;');
		
													/* fall-back to IE compability mode */
		case 'grid_ie':				$arr = $con->tep_renderGrid($page, $rows, $order_by, $sort_order, $search, $lang);
													echo json_encode($arr);
													break;
		
		case 'subgrid':				/* sending out the correct header for FireFox & Chrome */
													header('Content-type: application/json; Charset=utf8;');
		
													/* fall-back to IE compability mode */
		case 'subgrid_ie':		$arr = $con->tep_renderSubGrid($id, $order_by, $sort_order);
													echo json_encode($arr);
													break;
		
		case 'postback':			switch($req['oper']){
														
														case 'edit':	/* updating the show */
																					$date = $req['Date'];
																					$date_id = $req['DateID'];
																					$desc = tep_db_real_escape_string(urldecode($req['Description'])); // Added By R
																					$title = tep_db_real_escape_string($req['Heading']); // Added By R
																					$name = tep_db_real_escape_string($req['Name']); // Added By R
																					$time = $req['Time'];
																					$venue = tep_db_real_escape_string($req['Venue']); // Added By R
																					$venue_id = $req['ID'];
																					$active = ($req['active'] == 'yes' ? 0 : 1);
																					//$expires = $req['Expires'];
																					
																					$con->tep_updateShow($id, $date_id, $desc, $title, $name, $date, $time, $venue, $venue_id, $active, $lang);
																					break;
														
														case 'del':		/* resetting the show - language doesn't really matter */
																		$con->tep_resetShow($id);
																		break;
													}
													break;
		
		case 'price':					$price = $req['Price'];
													$arr = explode('_',$id);
													$con->tep_updatePrice($arr[0],$arr[1],$price);
													break;
		
		case 'sort':					$order = $req['order'];
													$con->tep_updateSortOrder($order);
													break;
		
		default:							header('Content-type: application/json; charset=utf8;');
													$arr = array(
														'mode' => $mode,
														'method' => $_SERVER['REQUEST_METHOD']
													);
													echo json_encode($arr);
	}
?>
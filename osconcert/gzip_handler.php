<?php
	/*	gZip Handler - Copyright by Martin Zeitler 	*/
	/*	usefull on hosts which do not support mod_deflate.c									*/
	
	ob_start('ob_gzhandler');
	switch($_GET['content']){
		case 'css':	header('Content-type: text/css');break;
		case 'js':	header('Content-type: application/javascript');break;
	}
	readfile($_GET['file']);
	ob_end_flush();
?>
<?php

	ini_set("display_errors", 1);
	ini_set("display_startup_errors", 1);
	error_reporting(E_ALL);

	require_once("inc/parser.class.php");

	$uploads_dir = "/var/www/html/assa/uploads/";
	$uploaded_file = $uploads_dir . basename($_FILES['userfile']['name']);

	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploaded_file)) {
		
		$filters = array();
		$filters['empty'] = isset($_POST['empty']);
		$filters['nat'] = isset($_POST['nat']);
		$filters['acl'] = isset($_POST['acl']);
		
		$parser = new Parser($uploaded_file, $filters);
		$parser->showData();

	}

?>

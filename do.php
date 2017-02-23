<?php

	ini_set("display_errors", 1);
	ini_set("display_startup_errors", 1);
	error_reporting(E_ALL);

	require_once("src/controller/AsaConfigParseController.class.php");

	$uploads_dir = __DIR__ . "/uploads/";
	$uploaded_file = $uploads_dir . basename($_FILES['userfile']['name']);

	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploaded_file)) {
		
		$options = array();
		$options['highlight'] = isset($_POST['highlight']);
		
		new AsaConfigParseController($uploaded_file, $options);
	}

?>

<?php
	require_once(dirname(__FILE__) . '/../rcms-config.php'); // Import configuration settings from parent site
	
	session_start();
	
	/* Establish database connection */
	try
	{
		$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD, array (PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => FALSE));
	}
	catch (PDOException $e)
	{
		die("Cannot Establish Connection To Database");
	}
	
	/* invalidate the current users token as expired*/
	$invalidate = $conn->prepare("UPDATE Token SET expired = 1 WHERE user = ? AND token = ?");
	
	$getuid = $conn->prepare("SELECT id FROM User WHERE username = ?");
	$getuid->bindParam(1, $_SESSION['username']);
	$getuid->execute();
	
	$id = $getuid->fetchColumn();
	
	$invalidate->bindParam(1, $id);
	$invalidate->bindValue(2, $_SESSION['token']);
	$invalidate->execute();
	
	session_destroy();
	header('Location: ' . SITE_URI);
	exit();
?>
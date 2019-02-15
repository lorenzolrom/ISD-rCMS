<?php
	/*
	* Initialize database connection and begin the process of loading the page
	*/
	
	require_once(dirname(__FILE__) . '/../rcms-config.php'); // Import configuration settings from parent site
	
	define ('RCMM_SITE_URI', SITE_URI . "user/"); // create a new constant for the rCMS manager URI

	/* URI based constants */
	const URI_CSS = RCMM_SITE_URI . "site/stylesheets/";
	const URI_GRA = RCMM_SITE_URI . "site/images/";
	const URI_FILE = RCMM_SITE_URI . "site/files/";
	const URI_SCRIPT = RCMM_SITE_URI . "site/scripts/";
	
	/* File-system based constants */

	define('PATH', dirname(__FILE__)); // The absolute path to this site's files on the web server, this should not require manual configuration
	
	const PATH_UPLOAD = PATH . "/../site/files/";
	const PATH_TEMP = PATH . "/site/templates/";
	const PATH_ELEM = PATH . "/site/elements/";
	
	/* Establish database connection */
	try
	{
		$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD, array (PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => FALSE));
	}
	catch (PDOException $e)
	{
		die("Cannot Establish Connection To Database");
	}
	
	require_once(dirname(__FILE__) . '/rcmm-functions.php');
	require_once(dirname(__FILE__) . '/rcmm-sessioncheck.php');
	require_once(PATH_TEMP . 'docstart.php');
	require(PATH_ELEM . 'left-sidebar.php');
?>
<div class="rcmm-content">
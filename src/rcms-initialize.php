<?php
	/*
	* Initialize database connection and begin the process of loading the page
	*/
	
	require_once(dirname(__FILE__) . '/rcms-config.php'); // Import configuration settings
	
	/* URI based constants */
	const URI_CSS = SITE_URI . "themes/" . THEME . "/stylesheets/";
	const URI_GRA = SITE_URI . "themes/" . THEME . "/graphics/";
	const URI_FILE = SITE_URI . "site/files/";
	
	/* File-system based constants */

	define('PATH', dirname(__FILE__)); // The absolute path to this site's files on the web server, this should not require manual configuration
	
	const PATH_INC = PATH . "/rcms-includes/";
	const PATH_FILE = PATH . "/site/files";
	const PATH_TEMP = PATH . "/themes/" . THEME . "/templates/";
	const PATH_ELEM = PATH . "/themes/" . THEME . "/elements/";
	
	/* Establish database connection */
	try
	{
		$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD, array (PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => FALSE));
	}
	catch (PDOException $e)
	{
		die("Cannot Establish Connection To Database");
	}
	
	/* Include Functions & Begin To Load Page*/
	require_once(dirname(__FILE__) . '/rcms-functions.php');
	require_once(dirname(__FILE__) . '/rcms-loadpage.php');
?>
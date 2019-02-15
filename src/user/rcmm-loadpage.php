<?php
	/*
	* This file will determine the page ID using the URL, and then load the proper template
	*/
	
	/*
		Retrieve page information
	*/
	$index_page_uri = $_SERVER['REQUEST_URI'];
	$index_page_uri_parts = explode("/", explode("?",$index_page_uri)[0]);
	
	$index_page_uri = ""; // wipe url so it can be reused
	
	/*
		Build Formatted URL, with special case for when no subpage is given
	*/
	foreach($index_page_uri_parts as $index_page_uri_part)
	{
		if(count($index_page_uri_parts) == 1 AND $index_page_uri_part == "index.php") // if the uri only consists of the index
		{
			$index_page_uri = "index.php";
			continue;
		}
		
		if($index_page_uri_part == "index.php")
			continue; // if we're reading in the uri and run into index, continue to get actual uri
		if($index_page_uri_part == "user")
			continue;
		
		if(strlen($index_page_uri_part) != 0)
			$index_page_uri .= $index_page_uri_part . "/";
	}
	
	/*
		If No Page ID is returned, set it to 15 - Not Found
	*/	
	if(!$index_page_id = getPageID($index_page_uri))
		$index_page_id = getPageID("notfound/");
	
	/*
		After determining the page ID, retrieve information
	*/
	$index_template = getFileName("rcmm_Template", getColumnFrom("template", "rcmm_Page", $index_page_id));
	$index_template .= ".php";
	
	/*
		Login page only requires template, no start or end
	*/
	if($index_template == "login.php")
		require_once(PATH_TEMP . $index_template);
	else
	{
		/*
			Include document head and foot, and import template
		*/
		require_once(PATH_TEMP . 'docstart.php');
		echo "\n";
		require_once(PATH_TEMP . $index_template);
		echo "\n";
		require_once(PATH_TEMP . 'docend.php');
	}
?>
<?php
	$sidebar_links = array("Change Password" => "?p=changepw");
	require_once(dirname(__FILE__) . '/rcmm-initialize.php');

	if(isset($_GET['p']) AND file_exists($_GET['p']) . '.php')
		require_once($_GET['p'] . '.php');
	else
		require_once('default.php');
	
	require_once(PATH_TEMP . 'docend.php');
?>
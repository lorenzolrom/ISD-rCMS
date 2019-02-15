<?php
	$sidebar_links = array("Upload" => "?p=upload");
	require_once(dirname(__FILE__) . '/../rcmm-initialize.php');

	if(hasPermission('fmgr'))
	{
		if(isset($_GET['p']) AND file_exists($_GET['p']) . '.php')
			require_once($_GET['p'] . '.php');
		else
			require_once('default.php');
	}
	else
		require_once(PATH_ELEM . 'not-authorized.php');
	
	require_once(PATH_TEMP . 'docend.php');
?>
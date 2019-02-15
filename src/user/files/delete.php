<?php
	if(!empty($_GET['f']))
	{
		// Construct full file path
		$file_path = dirname(__FILE__) . "/../../site/files/" . $_GET['f'];
		
		// Check if file exists
		if(file_exists($file_path))
		{
			unlink($file_path);
		}
		
		header("Location: index.php");
		exit();
	}
?>
<?php
	if(!empty($_FILES))
	{
		// ok to upload?
		$upOK = TRUE;
		
		// initial assets path, this will be appended with sub-dir later
		$target = PATH_UPLOAD;
		
		// post vars
		$file = basename($_FILES["upload"]["name"]);
		
		if(isset($_POST['override']))
			$override = $_POST["override"];
		else
			$override = "no";
		
		if(!empty($file))
		{
			// Retrieve file extension
			$ftype = strtolower(pathinfo($file, PATHINFO_EXTENSION));
			
			$target .= $file;
		
			// Check if file exists
			if($override != "yes" && file_exists($target))
			{
				$upOK = FALSE;
				$errors[] = "File already exists, to override, check 'Override'";
			}
			
			if(empty($errors))
			{
				if(move_uploaded_file($_FILES["upload"]["tmp_name"], $target))
					$notifications = "File Upload Complete!";
				else
					$notifications = "File Upload Failed!" . $target;
			}
		}
		else
			$errors[] = "Please select a file!";
	}
	require_once(PATH_ELEM . 'notifications.php');
?>
	<h1>Upload</h1>
	<form method="post" enctype="multipart/form-data">
		<p>
			<span>Select File</span>
			<input type="file" name="upload">
		</p>
		<p>
			<span>Override?</span>
			<input type="checkbox" name="override" value="yes">
		</p>
		<p>
			<input class="form-button" type="submit" value="Upload">
		</p>
	</form>
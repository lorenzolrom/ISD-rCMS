<?php
	if(!empty($_POST))
	{
		$url = $_POST['url'];
		$destination = $_POST['destination'];
		$enabled = $_POST['enabled'];
		
		if(empty($url))
			$errors[] = "URL Required";
		else if(strlen($url) > 60)
			$errors[] = "URL Cannot Be Longer Than 60 Characters";
		
		if(substr($url, -1) != "/" AND !strlen($url) == 0)
			$url .= "/";
		
		if(empty($destination))
			$errors[] = "Destination Required";
		
		if($enabled != "1" AND $enabled != "0")
			$errors[] = "Enabled Value Not Valid";
		
		if(empty($errors))
		{
			$insert = $conn->prepare("INSERT INTO Doorway (url, destination, enabled) VALUES (?, ?, ?)");
			$insert->bindParam(1, $url);
			$insert->bindParam(2, $destination);
			$insert->bindParam(3, $enabled);
			
			if($insert->execute())
			{
				$notifications = "Doorway Created Successfully";
				header("Location:index.php");
				exit();
			}
		}
	}
	require_once(PATH_ELEM . 'notifications.php');
?>
	<h1>New Doorway</h1>
	<form class="rcmm-form" method="post">
		<p>
			<span>URL</span>
			<input type="text" name="url" maxlength="60" value="<?=ifSet($url)?>">
		</p>
		<p>
			<span>Destination</span>
			<input type="text" name="destination" value="<?=ifSet($destination)?>">
		</p>
		<p>
			<span>Enabled</span>
			<select name="enabled">
				<option value="1" <?=(ifSet($enabled)) ? "selected" : ""?>>Yes</option>
				<option value="0" <?=(!ifSet($enabled)) ? "selected" : ""?>>No</option>
			</select>
		</p>
		<input class="rcmm-form-button" type="submit" value="Save">
	</form>
<?php
	require_once(PATH_TEMP . 'docend.php');
?>
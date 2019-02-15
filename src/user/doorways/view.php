<?php
	if(!empty($_GET['delete']) AND !empty($_GET['d']))
	{
		$door_id = $_GET['d'];
		if(!is_numeric($door_id))
		{
			header('Location:index.php');
			exit();
		}
		
		// verify content exists
		$get = $conn->prepare("SELECT * FROM Doorway WHERE id = ? LIMIT 1");
		$get->bindParam(1, $door_id);
		$get->execute();
		
		if($get->rowCount() != 1)
		{
			header('Location: index.php');
			exit();
		}
		else
		{
			$delete = $conn->prepare("DELETE FROM Doorway WHERE id = ?");
			$delete->bindParam(1, $door_id);
			
			if($delete->execute())
			{
				header("Location:index.php");
				exit();
			}
		}
	}
	
	if(!empty($_GET['d']))
	{
		$door_id = $_GET['d'];
		
		$get = $conn->prepare("SELECT * FROM Doorway WHERE id = ? LIMIT 1");
		$get->bindParam(1, $door_id);
		
		$get->execute();
		
		$doorway = $get->fetch();
		$door_url = $doorway['url'];
		$door_destination = $doorway['destination'];
		$door_enabled = $doorway['enabled'];
	}
	else
	{
		header("Location:index.php");
		exit();
	}
	
	if(!empty($_POST))
	{
		$door_url = $_POST['url'];
		$door_destination = $_POST['destination'];
		$door_enabled = $_POST['enabled'];
		
		if(empty($door_url))
			$errors[] = "URL Required";
		else if(strlen($door_url) > 60)
			$errors[] = "URL Cannot Be Longer Than 60 Characters";
		
		if(substr($door_url, -1) != "/" AND !strlen($door_url) == 0)
			$url .= "/";
		
		if(empty($door_destination))
			$errors[] = "Destination Required";
		
		if($door_enabled != "1" AND $door_enabled != "0")
			$errors[] = "Enabled Value Not Valid";
		
		if(empty($errors))
		{
			$update = $conn->prepare("UPDATE Doorway SET url = ?, destination = ?, enabled = ? WHERE id = ?");
			$update->bindParam(1, $door_url);
			$update->bindParam(2, $door_destination);
			$update->bindParam(3, $door_enabled);
			$update->bindParam(4, $door_id);
			
			if($update->execute())
			{
				$notifications = "Doorway Updated Successfully";
				header("Location:index.php");
				exit();
			}
		}
	}
	require_once(PATH_ELEM . 'notifications.php');
?>
	<h1>Edit Doorway</h1>
	<form class="rcmm-form" method="post">
		<p>
			<span>URL</span>
			<input type="text" name="url" maxlength="60" value="<?=ifSet($door_url)?>">
		</p>
		<p>
			<span>Destination</span>
			<input type="text" name="destination" value="<?=ifSet($door_destination)?>">
		</p>
		<p>
			<span>Enabled</span>
			<select name="enabled">
				<option value="1" <?=(ifSet($door_enabled)) ? "selected" : ""?>>Yes</option>
				<option value="0" <?=(!ifSet($door_enabled)) ? "selected" : ""?>>No</option>
			</select>
		</p>
		<input class="rcmm-form-button" type="submit" value="Save">
	</form>
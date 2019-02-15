<?php
	if(!empty($_POST))
	{
		$current = $_POST['current'];
		$new = $_POST['new'];
		$confirm = $_POST['confirm'];
		
		if(empty($current))
			$errors[] = "Current Password Required";
		else if(empty($new))
			$errors[] = "New Password Required";
		else if(strlen($new) < 8)
			$errors[] = "New Password Must Be At Least 8 Characters";
		else if($new != $confirm)
			$errors[] = "New Passwords Must Match";
		
		if(empty($errors))
		{
			// Check current password
			$check = $conn->prepare("SELECT id FROM User WHERE username = ? AND password = ? LIMIT 1");
			$check->bindValue(1, $_SESSION['username']);
			$check->bindValue(2, hash("SHA512", $current));
			$check->execute();
			
			if($check->rowCount() == 1)
			{
				$shapw = hash("SHA512", $new);
				
				$update = $conn->prepare("UPDATE User SET password = ? WHERE username = ?");
				$update->bindParam(1, $shapw);
				$update->bindValue(2, $_SESSION['username']);
				
				if($update->execute())
					$notifications = "Password Updated Successfully";
			}
			else
				$errors[] = "Current Password Is Incorrect";
		}
	}
	require_once(PATH_ELEM . 'notifications.php');
?>
	<h1>Change Password</h1>
	<form class="rcmm-form" method="post">
		<p>
			<span>Current Password</span>
			<input type="password" name="current">
		</p>
		<p>
			<span>New Password</span>
			<input type="password" name="new">
		</p>
		<p>
			<span>Confirm</span>
			<input type="password" name="confirm">
		</p>
		<input class="form-button" type="submit" value="Change">
	</form>
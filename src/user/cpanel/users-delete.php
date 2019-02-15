<?php
	if(isset($_GET['u']))
	{
		// Verify We ARENT the user
		if($_GET['u'] != $_SESSION['username'])
		{			
			// Delete user with matching username
			$delete = $conn->prepare("DELETE FROM User WHERE username = ?");
			$delete->bindParam(1, $_GET['u']);
			$delete->execute();
		}
	}
	
	// Redirect to users page
	header("Location:?p=users");
	exit();
?>
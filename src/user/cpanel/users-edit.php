<?php	
	// Load the user
	if(isset($_GET['u']))
	{
		// Query for user
		$find_user = $conn->prepare("SELECT username, first_name, last_name, role FROM User WHERE username = ? LIMIT 1");
		$find_user->bindValue(1, $_GET['u']);
		$find_user->execute();
	
		// If user exists, pull attributes
		if($find_user->rowCount() == 1)
		{
			$this_user = $find_user->fetch();
			
			$user_username = $this_user['username'];
			$user_first_name = $this_user['first_name'];
			$user_last_name = $this_user['last_name'];
			$user_role = $this_user['role'];
		}
		else
			$errors[] = "User Not Found";
	}
	else
		$errors[] = "User Not Found";
	
	// If user was saved..
	if(!empty($_POST))
	{
		// Validate user details
		$user_first_name = $_POST['first_name'];
		$user_last_name = $_POST['last_name'];
		$user_password = $_POST['password'];
		$password_confirm = $_POST['password_confirm'];
		$sha_password = NULL;
		$user_role = $_POST['role'];
		
		// First name exists, and is not longer than 30
		if(strlen($user_first_name) < 1)
			$errors[] = "First Name Required";
		else if(strlen($user_first_name) > 30)
			$errors[] = "First Name Must Not Exceed 30 Characters";
		
		// Last name exists, and is not longer than 30
		if(strlen($user_last_name) < 1)
			$errors[] = "Last Name Required";
		else if(strlen($user_last_name) > 30)
			$errors[] = "Last Name Must Not Exceed 30 Characters";
		
		// If role is set
		if(strlen($user_role) == 0)
			$errors[] = "Role Required";
		
		// Password exists, and is longer than 8 characters
		if(strlen($user_password) > 0)
		{
			if(strlen($user_password) < 8)
				$errors[] = "Password Must Be 8 Or More Characters";
			else if($user_password != $password_confirm)
				$errors[] = "Passwords Do Not Match";
			
			// Hash password
			$sha_password = hash('SHA512', $user_password);
		}
		
		if(empty($errors))
		{
			//Save user
			$save_user = $conn->prepare("UPDATE User SET first_name = :first_name, last_name = :last_name, role = :role" . (($sha_password != NULL) ? " , password = :password " : "") . " WHERE username = :username");
			$save_user->bindValue(':first_name', $user_first_name);
			$save_user->bindValue(':last_name', $user_last_name);
			$save_user->bindValue(':username', $user_username);
			$save_user->bindValue(':role', $user_role);
			if($sha_password != NULL)
				$save_user->bindValue(':password', $sha_password);
			
			// If successful, redirect to users page
			if($save_user->execute())
			{
				header("Location:?p=users");
				exit();
			}
			else
				$errors[] = "Error Saving User";
		}
	}	
	
	require_once(PATH_ELEM . "notifications.php");
?>
<h1>Edit User <?=ifSet($_GET['u'])?></h1>
<form class="rcmm-form" method="post">
	<p>
		<span>First Name</span>
		<input type="text" name="first_name" value="<?=ifSet($user_first_name)?>">
	</p>
	<p>
		<span>Last Name</span>
		<input type="text" name="last_name" value="<?=ifSet($user_last_name)?>">
	</p>
	<p>
		<span>Role</span>
		<select name="role">
			<option value="">--SELECT--</option>
			<?=getRoleOptions(ifSet($user_role))?>
		</select>
	</p>
	<p>
		<span>Password</span>
		<input type="password" name="password" placeholder="Optional">
	</p>
	<p>
		<span>Confirm</span>
		<input type="password" name="password_confirm">
	</p>
	<input class="rcmm-form-button" type="submit" value="Save Changes">
</form>
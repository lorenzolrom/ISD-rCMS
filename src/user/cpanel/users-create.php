<?php
	if(!empty($_POST))
	{
		// Validate new user details
		$user_username = $_POST['username'];
		$user_first_name = $_POST['first_name'];
		$user_last_name = $_POST['last_name'];
		$user_password = $_POST['password'];
		$password_confirm = $_POST['password_confirm'];
		$user_role = $_POST['role'];
		
		// Username exists, does not already exist, and is not longer than 64
		if(strlen($user_username) < 1)
			$errors[] = "Username Required";
		else if(usernameExists($user_username))
			$errors[] = "Username Already In Use";
		else if(strlen($user_username) > 64)
			$errors[] = "Username Must Not Exceed 64 Characters";
		
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
		
		// Password exists, and is longer than 8 characters
		if(strlen($user_password) < 1)
			$errors[] = "Password Required";
		else if(strlen($user_password) < 8)
			$errors[] = "Password Must Be 8 Or More Characters";
		else if($user_password != $password_confirm)
			$errors[] = "Passwords Do Not Match";
		
		// If role is set
		if(strlen($user_role) == 0)
			$errors[] = "Role Required";
		
		// If no errors...
		if(empty($errors))
		{
			// Hash password
			$sha_password = hash('SHA512', $user_password);
			
			// Create user
			$create_user = $conn->prepare("INSERT INTO User (username, first_name, last_name, password, role) VALUES (?, ?, ?, ?, ?)");
			$create_user->bindParam(1, $user_username);
			$create_user->bindParam(2, $user_first_name);
			$create_user->bindParam(3, $user_last_name);
			$create_user->bindParam(4, $sha_password);
			$create_user->bindParam(5, $user_role);
			
			if($create_user->execute())
			{			
				// Refresh to users page
				header("Location:?p=users");
				exit();
			}
			else
				$errors[] = "Error Creating User";
		}
	}
	
	require_once(PATH_ELEM . 'notifications.php');
?>
<h1>Create New User</h1>
<form class="rcmm-form" method="post">
	<p>
		<span>Username</span>
		<input type="text" name="username" value="<?=ifSet($user_username)?>">
	</p>
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
		<input type="password" name="password">
	</p>
	<p>
		<span>Confirm</span>
		<input type="password" name="password_confirm">
	</p>
	<input class="rcmm-form-button" type="submit" value="Create User">
</form>
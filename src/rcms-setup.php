<?php
	// Empty array for errors
	$errors = [];
	
	// Get the config file
	require_once(DIRNAME(__FILE__) . "/rcms-config.php");
	
	// Check if database details are present
	if(DB_HOST == NULL OR DB_NAME == NULL OR DB_USER == NULL OR DB_PASSWORD == NULL)
		$errors[] = "Database Details Not Fully Configured In rcms-config.php!";
	else
	{
		// Attempt connection to the database
		try
		{
			$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD, array (PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => FALSE));
		}
		catch (PDOException $e)
		{
			$errors[] = "Could Not Connect To Database.  Ensure Details Are Correct In rcms-config.php and Server Is Running!";
		}
	}
	
	// Check if user configured settings are present
	if(THEME == NULL)
		$errors[] = "Site Theme Not Configured In rcms-config.php!";
	
	if(SITE_URI == NULL)
		$errors[] = "Site URI Not Configured In rcms-config.php!";
	
	// After getting user form-setup database
	if(!empty($_POST))
	{
		// Get form responses
		$site_title = $_POST['site-title'];
		$site_description = isset($_POST['site_description']) ? $_POST['site_description'] : "";
		$username = $_POST['username'];
		$first_name = $_POST['first-name'];
		$last_name = $_POST['last-name'];
		$password = $_POST['password'];
		$confirm = $_POST['confirm'];
		
		// Validate form response
		if(strlen($site_title) == 0)
			$errors[] = "Site Title Required!";
		
		if(strlen($username) == 0)
			$errors[] = "Username Required!";
		else if(strlen($username) > 60)
			$errors[] = "Username Cannot Exceed 60 Characters!";
		
		if(strlen($first_name) == 0)
			$errors[] = "First Name Required!";
		else if(strlen($first_name) > 30)
			$errors[] = "First Name Cannot Exceed 30 Characters!";
		
		if(strlen($last_name) == 0)
			$errors[] = "Last Name Required!";
		else if(strlen($last_name) > 30)
			$errors[] = "Last Name Cannot Exceed 30 Characters!";
		
		if(strlen($password) < 8)
			$errors[] = "Password Must Be 8 Or More Characters!";
		else if($password != $confirm)
			$errors[] = "Passwords Must Match!";
		
		if(empty($errors))
		{
			try
			{
				// Run database script
				require_once(dirname(__FILE__) . "/rcms-setup-database.php");
				
				// Add site setting details
				$add_title = $conn->prepare("INSERT INTO Setting VALUES ('swtl', 'Site-Wide Title', ?)");
				$add_title->bindParam(1, $site_title);
				$add_title->execute();
				
				$add_description = $conn->prepare("INSERT INTO Setting VALUES ('swds', 'Site-Wide Description', ?)");
				$add_description->bindParam(1, $site_description);
				$add_description->execute();
				
				// Create new user
				$add_user = $conn->prepare("INSERT INTO User (username, first_name, last_name, password, role) VALUES (?, ?, ?, ?, 1)");
				$add_user->bindParam(1, $username);
				$add_user->bindParam(2, $first_name);
				$add_user->bindParam(3, $last_name);
				$add_user->bindValue(4, hash('SHA512', $password));
				$add_user->execute();
				
				// Redirect to new site
				header("Location: " . SITE_URI);
				exit();
			}
			catch (PDOException $e)
			{
				$errors[] = $e->getMessage();
				$conn->rollBack();
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>r-CMS New Web-Site Setup</title>
	</head>
	<style>
		* {
			margin: 0;
			padding: 0;
			font-size: 10pt;
			font-family: sans-serif;
		}
		
		html {
			background-color: #F1F1F1;
		}
		
		body {
			display: block;
			margin: auto;
			width: 400px;
			margin-top: 100px;
			background-color: #E3E3E3;
			padding: 10px;
			border: 5px solid #D3D3D3;
			border-style: outset;
			border-radius: 15px;
			position: relative;
		}
		
		form p {
			margin-bottom: 10px;
		}
		
		form p span {
			display: inline-block;
			width: 100px;
			font-weight: bold;
		}
		
		form h2 {
			font-size: 11pt;
			color: #FFF;
		}
		
		#errors {
			color: red;
		}
		
		#errors ul li {
			list-style-position: inside;
		}
		
		h1 {
			font-size: 12pt;
			border-bottom: 1px solid #000;
			margin-bottom: 5px;
			display: block;
		}
	</style>
	<body>
		<h1>r-CMS New Web-Site Setup</h1>
		<?php
			if(!empty($errors))
			{
				?>
				<div id="errors">
					<ul>
					<?php
					foreach($errors as $error)
					{
						?>
							<li><?=$error?></li>
						<?php
					}
					?>
					</ul>
				</div>
				<?php
			}
		?>
		<form method="post">
			<h2>Site Settings</h2>
			<p>
				<span>Site Title</span>
				<input type="text" name="site-title">
			</p>
			<p>
				<span>Site Description</span>
				<input type="text" name="site-description">
			</p>
			<h2>User Details</h2>
			<p>
				<span>Username</span>
				<input type="text" name="username">
			</p>
			<p>
				<span>First Name</span>
				<input type="text" name="first-name">
			</p>
			<p>
				<span>Last Name</span>
				<input type="text" name="last-name">
			</p>
			<p>
				<span>Password</span>
				<input type="password" name="password">
			</p>
			<p>
				<span>Confirm</span>
				<input type="password" name="confirm">
			</p>
			<input type="submit" value="Setup!">
		</form>
	</body>
</html>
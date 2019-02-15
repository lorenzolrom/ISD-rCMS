<?php
	/*
	* LLR Technologies
	* Information Systems Development
	* rCMS interactive login page.
	*/
	
	require_once(dirname(__FILE__) . '/../rcms-config.php'); // Import configuration settings from parent site
	
	session_start();
	
	/* If user is already logged in, navigate to the user homepage */
	if(isset($_SESSION['username']))
	{
		header('Location:'. SITE_URI . "user/");
		exit();
	}
	
	/* Establish database connection */
	try
	{
		$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD, array (PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => FALSE));
	}
	catch (PDOException $e)
	{
		die("Cannot Establish Connection To Database");
	}
	
	if (!empty($_POST))
	{
		$shapw = hash('SHA512', $_POST['password']);
		$username = strtolower($_POST['username']);
		
		$search = $conn->prepare("SELECT id,username, first_name FROM User WHERE username = ? AND password = ?");
		$search->bindParam(1, $username);
		$search->bindParam(2, $shapw);
		$search->execute();
		$rows = $search->rowCount();
		
		if($rows == 1)
		{
			/* Activate the User Session*/
			$user = $search->fetch();
			
			/* Create the Session Token */
			$rand = openssl_random_pseudo_bytes(2048); // generates random number for token_get_all
			$token = hash('SHA512', $rand); // hash the token
			
			/* invalidate all previous tokens for this user */
			$invtokens = $conn->prepare("UPDATE Token SET expired = 1 WHERE user = ?");
			$invtokens->bindValue(1, $user['id']);
			$invtokens->execute();
			
			/* add token to table */
			$addtoken = $conn->prepare("INSERT INTO Token (token, user, issue_time, expire_time, ip_address) VALUES (?, ?, NOW(), NOW() + INTERVAL 1 HOUR, ?)");
			$addtoken->bindParam(1, $token);
			$addtoken->bindValue(2, $user['id']);
			$addtoken->bindValue(3, $_SERVER['REMOTE_ADDR']);
			
			if($addtoken->execute())
			{
				$_SESSION['isActiveSession'] = true;
				$_SESSION['username'] = $user['username'];
				$_SESSION['name'] = $user['first_name'];
				$_SESSION['token'] = $token;
				header('Location: ' . SITE_URI . "user/");
				exit();
			}
			else
				$errors[] = "Failed to Secure Login Token";
		}
		else
		{
			$errors[] = "Username or Password is Incorrect!";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<!-- rCMS was developed by LLR Information Systems Development - isd.llrtech.com -->
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="site/stylesheets/rcmm-login.css">
		<link rel="icon" type="image/ico" href="/site/graphics/favicon.ico">
		<title>rCMS Manager Login</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
		<div class="rcmm-login-window">
			<div id="rcmm-login-notice">
				<?php require_once("site/elements/notifications.php"); ?>
			</div>
			<h1>r-Content Management System</h1>
			<form method="post" action="login.php">
				<p>
					<span>Username</span>
					<input type="text" maxlength="60" name="username">
				</p>
				<p>
					<span>Password</span>
					<input type="password" maxlength="60" name="password">
				</p>
				<input class="rcmm-login-button" type="submit" value="Login">
			</form>
			<p class="rcmm-copywr">Software &copy; 2018 LLR Info-Systems Development</p>
		</div>
		<?php
			if(isset($notifications))
			{
				?>
				<script>document.getElementById("rcmm-login-notice").style.display = "block";</script>
				<?php
			}
		?>
	</body>
</html>
<?php
	/*
		Determines if the current user's token is valid
	*/
	function isValidToken()
	{
		global $conn;
		
		if(isset($_SESSION['username']) AND isset($_SESSION['token']))
		{
			$token = $_SESSION['token'];
			
			$fetchID = $conn->prepare("SELECT id FROM User WHERE username = ?");
			$fetchID->bindValue(1, $_SESSION['username']);
			
			$fetchID->execute();
			
			// verify we only have one match for username
			if($fetchID->rowCount() == 1)
				$id = $fetchID->fetchColumn();
			else
				return FALSE;
			
			$evalsession = $conn->prepare("SELECT token FROM Token WHERE user = ? AND token = ? AND expired = 0 AND expire_time - NOW() > 0");
			$evalsession->bindParam(1, $id);
			$evalsession->bindParam(2, $token);
			
			$evalsession->execute();
			
			$numrows = $evalsession->rowCount();
			
			
			if($numrows == 1)
			{
				$session = $evalsession->fetch();
				$db_token = $session['token'];				
				
				if($token == $db_token)
				{
					$updateexpiretime = $conn->prepare("UPDATE Token SET expire_time = NOW() + INTERVAL 1 HOUR WHERE token = ?");
					$updateexpiretime->bindParam(1, $token);
					$updateexpiretime->execute();
					return TRUE;
				}
			}
		}
		
		if(isset($_SESSION['token']))
		{
			// invalidate token
			$invtoken = $conn->prepare("UPDATE Token SET expired = 0 WHERE user = ? AND token = ?");
			$invtoken->bindValue(1, $id);
			$invtoken->bindParam(2, $token);
			
			$invtoken->execute();
		}
		
		// destroy session and boot to login
		session_destroy();
		header('Location:' . RCMM_SITE_URI . 'login.php');
		return FALSE;
	}
	
	session_start();
	
	if(!isValidToken())
	{
		header('Location:' . RCMM_SITE_URI . "login.php");
		exit();
	}
?>
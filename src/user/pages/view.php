<?php	
	if(!empty($_GET['delete']) AND !empty($_GET['a']))
	{
		$page_id = $_GET['a'];
		if(!is_numeric($page_id))
		{
			header('Location:index.php');
			exit();
		}
		
		// verify page exists
		$get = $conn->prepare("SELECT * FROM Page WHERE id = ? LIMIT 1");
		$get->bindParam(1, $page_id);
		$get->execute();
		
		if($get->rowCount() != 1)
		{
			header('Location: index.php');
			exit();
		}
		else
		{
			$delete = $conn->prepare("DELETE FROM Page WHERE id = ?");
			$delete->bindParam(1, $page_id);
			
			if($delete->execute())
			{
				header("Location:index.php");
				exit();
			}
		}
	}
	
	if(!empty($_GET['a']))
	{
		$page_id = $_GET['a'];
		if(!is_numeric($page_id))
		{
			header('Location:index.php');
			exit();
		}
		
		$get = $conn->prepare("SELECT * FROM Page WHERE id = ? LIMIT 1");
		$get->bindParam(1, $page_id);
		$get->execute();
		
		if($get->rowCount() != 1)
		{
			header('Location: index.php');
			exit();
		}
		else
		{
			$page = $get->fetch();
			
			$page_id = $page['id'];
			$page_name = $page['name'];
			$page_display_name = $page['display_name'];
			$page_url = $page['url'];
			$page_is_on_nav = $page['is_on_nav'];
			$page_nav_weight = $page['nav_weight'];
			$page_template = $page['template'];
		}
	}
	else
	{
		header('Location:index.php');
		exit();
	}
	
	if(!empty($_POST))
	{
		$page_name = $_POST['name'];
		$page_display_name = $_POST['display_name'];
		$page_url = $_POST['url'];
		$page_is_on_nav = $_POST['is_on_nav'];
		$page_nav_weight = $_POST['nav_weight'];
		$page_template = $_POST['template'];
		
		if(empty($page_name))
			$errors[] = "Page Name Required";
		else if(strlen($page_name) > 60)
			$errors[] = "Page Name Must Be Less Than 60 Characters";
		
		if(!empty($page_display_name) AND strlen($page_display_name) > 60)
			$errors[] = "Display Name Must Be Less Than 60 Characters";
		
		if(empty($page_display_name))
			$page_display_name = NULL;
		
		if(strlen($page_url) > 59)
			$errors[] = "Page URL Must Be Less Than 59 Characters";
		else
			$page_url = ltrim($page_url, '/');
		
		if(substr($page_url, -1) != "/" AND !strlen($page_url) == 0)
			$page_url .= "/";
		
		$check_url = $conn->prepare("SELECT url FROM Page WHERE url = ? AND id != ?");
		$check_url->bindParam(1, $page_url);
		$check_url->bindParam(2, $page_id);
		$check_url->execute();
		
		if($check_url->rowCount() != 0)
			$errors[] = "Page URL Already In Use";
		
		if($page_is_on_nav != 1 AND $page_is_on_nav != 0)
			$errors[] = "Display On Nav Value Not Valid";
		
		if(!empty($page_nav_weight) AND !is_numeric($page_nav_weight))
			$errors[] = "Nav Weight Value Not Valid";
		else if(empty($page_nav_weight))
			$page_nav_weight = 0;
		
		if(empty($page_template))
			$errors[] = "Page Template Required";
		else if(!is_numeric($page_template))
			$errors[] = "Page Template Value Not Valid";
		
		if(empty($errors))
		{
			$update = $conn->prepare("UPDATE Page SET name = ?, display_name = ?, url = ?, is_on_nav = ?, nav_weight = ?, template = ? WHERE id = ?");
			
			$update->bindParam(1, $page_name);
			$update->bindParam(2, $page_display_name);
			$update->bindParam(3, $page_url);
			$update->bindParam(4, $page_is_on_nav);
			$update->bindParam(5, $page_nav_weight);
			$update->bindParam(6, $page_template);
			$update->bindParam(7, $page_id);
			
			if($update->execute())
			{
				$notifications = "Page Updated";
				header("Location:index.php");
				exit();
			}
		}
	}
	require_once(PATH_ELEM . 'notifications.php');
?>
	<h1>Edit Page</h1>
	<form class="rcmm-form" method="post">
		<p>
			<span>Name</span>
			<input type="text" name="name" maxlength="60" value="<?=ifSet($page_name)?>">
		</p>
		<p>
			<span>Display Name</span>
			<input type="text" name="display_name" maxlength="60" placeholder="Optional" value="<?=ifSet($page_display_name)?>">
		</p>
		<p>
			<span>URL</span>
			<input type="text" name="url" maxlength="59" value="<?=ifSet($page_url)?>">
		</p>
		<p>
			<span>Display on Nav</span>
			<select name="is_on_nav">
				<option value="1" <?=($page_is_on_nav ? "selected" : "")?>>Yes</option>
				<option value="0" <?=(!$page_is_on_nav ? "selected" : "")?>>No</option>
			</select>
		</p>
		<p>
			<span>Nav Weight</span>
			<input type="text" name="nav_weight" placeholder="Optional" value="<?=ifSet($page_nav_weight)?>">
		</p>
		<p>
			<span>Template</span>
			<select name="template">
				<option value="">--SELECT--</option>
				<?=getTemplateOptions(ifSet($page_template))?>
			</select>
		</p>
		<input class="rcmm-form-button" type="submit" value="Save">
	</form>
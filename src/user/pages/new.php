<?php
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
		
		$check_url = $conn->prepare("SELECT url FROM Page WHERE url = ?");
		$check_url->bindParam(1, $page_url);
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
			$insert = $conn->prepare("INSERT INTO Page (name, display_name, url, is_on_nav, nav_weight, template) VALUES (?, ?, ?, ?, ?, ?)");
			
			$insert->bindParam(1, $page_name);
			$insert->bindParam(2, $page_display_name);
			$insert->bindParam(3, $page_url);
			$insert->bindParam(4, $page_is_on_nav);
			$insert->bindParam(5, $page_nav_weight);
			$insert->bindParam(6, $page_template);
			
			if($insert->execute())
			{
				$notifications = "New Page Created";
				header("Location: index.php");
			}
		}
	}
	require_once(PATH_ELEM . 'notifications.php');
?>
	<h1>New Page</h1>
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
				<option value="1" <?=(ifSet($is_on_nav)) ? "selected" : ""?>>Yes</option>
				<option value="0" <?=(!ifSet($is_on_nav)) ? "selected" : ""?>>No</option>
			</select>
		</p>
		<p>
			<span>Nav Weight</span>
			<input type="text" name="nav_weight" placeholder="Optional" <?=ifSet($nav_weight)?>>
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
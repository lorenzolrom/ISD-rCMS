<?php	
	if(!empty($_GET['delete']) AND !empty($_GET['c']))
	{
		$content_id = $_GET['c'];
		if(!is_numeric($content_id))
		{
			header('Location:index.php');
			exit();
		}
		
		// verify content exists
		$get = $conn->prepare("SELECT * FROM Content WHERE id = ? LIMIT 1");
		$get->bindParam(1, $content_id);
		$get->execute();
		
		if($get->rowCount() != 1)
		{
			header('Location: index.php');
			exit();
		}
		else
		{
			$delete = $conn->prepare("DELETE FROM Content WHERE id = ?");
			$delete->bindParam(1, $content_id);
			
			if($delete->execute())
			{
				header("Location:index.php");
				exit();
			}
		}
	}
	
	if(!empty($_GET['c']))
	{
		$content_id = $_GET['c'];
		if(!is_numeric($content_id))
		{
			header('Location:index.php');
			exit();
		}
		
		$get = $conn->prepare("SELECT * FROM Content WHERE id = ? LIMIT 1");
		$get->bindParam(1, $content_id);
		$get->execute();
		
		if($get->rowCount() != 1)
		{
			header('Location: index.php');
			exit();
		}
		else
		{
			$content = $get->fetch();
			
			$content_id = $content['id'];
			$content_name = $content['name'];
			$content_page = $content['page'];
			$content_element = $content['element'];
			$content_content = $content['content'];
			$content_is_searchable = $content['is_searchable'];
			$content_is_displayed = $content['is_displayed'];
			$content_weight = $content['weight'];
		}
	}
	else
	{
		header('Location:index.php');
		exit();
	}
	
	if(!empty($_POST))
	{
		$content_name = $_POST['name'];
		$content_page = $_POST['page'];
		$content_element = $_POST['element'];
		$content_is_searchable = $_POST['is_searchable'];
		$content_is_displayed = $_POST['is_displayed'];
		$content_weight = $_POST['weight'];
		$content_content = $_POST['content'];
		
		if(empty($content_name))
			$errors[] = "Content Name Required";
		else if(strlen($content_name) > 60)
			$errors[] = "Content Name Must Be Less Than 60 Characters";

		if(empty($content_page))
			$errors[] = "Page Required";
		else if(!is_numeric($content_page))
			$errors[] = "Page Value Not Valid";
		
		if(empty($content_element))
			$errors[] = "Element Required";
		
		if($content_is_searchable != 1 AND $content_is_searchable != 0)
			$errors[] = "Searchable Value Not Valid";
		
		if($content_is_displayed != 1 AND $content_is_displayed != 0)
			$errors[] = "Displayed Value Not Valid";
		
		if(!empty($content_weight) AND !is_numeric($content_weight))
			$errors[] = "Nav Weight Value Not Valid";
		else if(empty($content_weight))
			$content_weight = 0;
		
		if(empty($errors))
		{
			$update = $conn->prepare("UPDATE Content SET page = ?, element = ?, name = ?, content = ?, is_searchable = ?, is_displayed = ?, weight = ? WHERE id = ?");
			
			$update->bindParam(1, $content_page);
			$update->bindParam(2, $content_element);
			$update->bindParam(3, $content_name);
			$update->bindParam(4, $content_content);
			$update->bindParam(5, $content_is_searchable);
			$update->bindParam(6, $content_is_displayed);
			$update->bindParam(7, $content_weight);
			$update->bindParam(8, $content_id);
			
			if($update->execute())
			{
				$notifications = "Content Updated";
				header("Location:index.php");
				exit();
			}
		}
	}
	require_once(PATH_ELEM . 'notifications.php');
?>
	<h1>Edit Content</h1>
	<form class="rcmm-form" method="post">
		<p>
			<span>Name</span>
			<input type="text" name="name" maxlength="60" value="<?=ifSet($content_name)?>">
		</p>
		<p>
			<span>Page</span>
			<select name="page">
				<option value="">--SELECT--</option>
				<?=getPagesAsOptions(ifset($content_page))?>
			</select>
		</p>
		<p>
			<span>Element</span>
			<select name="element">
				<option value="">--SELECT--</option>
				<?=getElementOptionsByPage($content_page, $content_element)?>
			</select>
		</p>
		<p>
			<span>Searchable</span>
			<select name="is_searchable">
				<option value="1" <?=(ifSet($content_is_searchable)) ? "selected" : ""?>>Yes</option>
				<option value="0" <?=(!ifSet($content_is_searchable)) ? "selected" : ""?>>No</option>
			</select>
		</p>
		<p>
			<span>Displayed</span>
			<select name="is_displayed">
				<option value="1" <?=(ifSet($content_is_displayed)) ? "selected" : ""?>>Yes</option>
				<option value="0" <?=(!ifSet($content_is_displayed)) ? "selected" : ""?>>No</option>
			</select>
		</p>
		<p>
			<span>Weight</span>
			<input type="text" name="weight" placeholder="Optional" value="<?=ifSet($content_weight)?>">
		</p>
		<p>
			<span>Content</span>
			<textarea name="content"><?=ifSet($content_content)?></textarea>
		</p>
		<input class="rcmm-form-button" type="submit" value="Save">
	</form>
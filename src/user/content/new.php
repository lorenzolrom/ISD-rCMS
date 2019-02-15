<?php
	if(!empty($_POST))
	{
		$content_name = $_POST['name'];
		$content_page = $_POST['page'];
		$content_searchable = $_POST['is_searchable'];
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
		
		if($content_searchable != 1 AND $content_searchable != 0)
			$errors[] = "Searchable Value Not Valid";
		
		if(!empty($content_weight) AND !is_numeric($content_weight))
			$errors[] = "Weight Value Not Valid";
		else if(empty($content_weight))
			$content_weight = 0;
		
		if(empty($errors))
		{
			$insert = $conn->prepare("INSERT INTO Content (page, name, content, is_searchable, weight) VALUES (?, ?, ?, ?, ?)");
			
			$insert->bindParam(1, $content_page);
			$insert->bindParam(2, $content_name);
			$insert->bindParam(3, $content_content);
			$insert->bindParam(4, $content_searchable);
			$insert->bindParam(5, $content_weight);
			
			if($insert->execute())
			{
				$notifications = "Content Created - Remember To Set Element And Display";
				header("Location:index.php");
			}
		}
	}
	require_once(PATH_ELEM . 'notifications.php');
?>
	<h1>New Content</h1>
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
			<span>Searchable</span>
			<select name="is_searchable">
				<option value="1" <?=(ifSet($content_is_searchable)) ? "selected" : ""?>>Yes</option>
				<option value="0" <?=(!ifSet($content_is_searchable)) ? "selected" : ""?>>No</option>
			</select>
		</p>
		<p>
			<span>Weight</span>
			<input type="text" name="weight" placeholder="Optional" <?=ifSet($content_weight)?>>
		</p>
		<p>
			<span>Content</span>
			<textarea name="content"><?=ifSet($content_content)?></textarea>
		</p>
		<input class="rcmm-form-button" type="submit" value="Save">
	</form>
<?php
	if(!empty($_POST))
	{
		$post_name = $_POST['name'];
		$post_picture = $_POST['picture'];
		$post_is_displayed = $_POST['is_displayed'];
		$post_content = $_POST['content'];
		$post_is_featured = $_POST['is_featured'];
		$post_category = $_POST['category'];
		$post_date = $_POST['date'];
		
		if(empty($post_name))
			$errors[] = "Post Name Required";
		else if(strlen($post_name) > 60)
			$errors[] = "Post Name Must Be Less Than 60 Characters";
		
		if($post_is_displayed != 1 AND $post_is_displayed != 0)
			$errors[] = "Displayed Value Not Valid";
		
		if($post_is_featured != 1 AND $post_is_featured != 0)
			$errors[] = "Featured Value Not Valid";
		
		if(empty($post_content))
			$errors[] = "Content Required";
		
		if(strlen($post_category) == 0)
			$post_category = NULL;
		
		if(!validDate($post_date))
			$errors[] = "Date Not Valid";
		
		if(empty($errors))
		{
			$insert = $conn->prepare("INSERT INTO Post (is_displayed, name, picture, content, is_featured, category, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
			
			$insert->bindParam(1, $post_is_displayed);
			$insert->bindParam(2, $post_name);
			$insert->bindParam(3, $post_picture);
			$insert->bindParam(4, $post_content);
			$insert->bindParam(5, $post_is_featured);
			$insert->bindParam(6, $post_category);
			$insert->bindParam(7, $post_date);
			
			if($insert->execute())
			{
				$notifications = "Post Created Successfully";
				header("Location: index.php");
			}
		}
	}
	require_once(PATH_ELEM . 'notifications.php');
?>
	<h1>New Post</h1>
	<form class="rcmm-form" method="post">
		<p>
			<span>Name</span>
			<input type="text" name="name" maxlength="60" value="<?=ifSet($post_name)?>">
		</p>
		<p>
			<span>Picture</span>
			<input type="text" name="picture" value="<?=ifSet($post_picture)?>">
		</p>
		<p>
			<span>Date</span>
			<input type="text" name="date" value="<?=ifSet($post_date)?>" id="date">
		</p>
		<p>
			<span>Displayed</span>
			<select name="is_displayed">
				<option value="1" <?=(ifSet($post_is_displayed)) ? "selected" : ""?>>Yes</option>
				<option value="0" <?=(!ifSet($post_is_displayed)) ? "selected" : ""?>>No</option>
			</select>
		</p>
		<p>
			<span>Featured</span>
			<select name="is_featured">
				<option value="1" <?=(ifSet($post_is_featured)) ? "selected" : ""?>>Yes</option>
				<option value="0" <?=(!ifSet($post_is_featured)) ? "selected" : ""?>>No</option>
			</select>
		</p>
		<p>
			<span>Content</span>
			<textarea name="content"><?=ifSet($post_content)?></textarea>
		</p>
		<p>
			<span>Category</span>
			<select name="category">
				<option value="">--NONE--</option>
				<?=getCategoryOptions(ifSet($post_category))?>
			</select>
		</p>
		<input class="rcmm-form-button" type="submit" value="Save">
	</form>
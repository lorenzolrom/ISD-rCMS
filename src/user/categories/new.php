<?php	
	if(!empty($_POST))
	{
		$category_name = $_POST['name'];
		$category_picture = $_POST['picture'];
		$category_is_displayed = $_POST['is_displayed'];
		
		if(empty($category_name))
			$errors[] = "Category Name Required";
		else if(strlen($category_name) > 60)
			$errors[] = "Category Name Must Be Less Than 60 Characters";
		
		if($category_is_displayed != 1 AND $category_is_displayed != 0)
			$errors[] = "Displayed Value Not Valid";
		
		if(empty($errors))
		{
			$insert = $conn->prepare("INSERT INTO Category (is_displayed, name, picture) VALUES (?, ?, ?)");
			
			$insert->bindParam(1, $category_is_displayed);
			$insert->bindParam(2, $category_name);
			$insert->bindParam(3, $category_picture);
			
			if($insert->execute())
			{
				header("Location:index.php");
				exit();
			}
		}
	}
	require_once(PATH_ELEM . 'notifications.php');
?>
	<h1>New Category</h1>
	<form class="rcmm-form" method="post">
		<p>
			<span>Name</span>
			<input type="text" name="name" maxlength="60" value="<?=ifSet($category_name)?>">
		</p>
		<p>
			<span>Picture</span>
			<input type="text" name="picture" value="<?=ifSet($category_picture)?>">
		</p>
		<p>
			<span>Displayed</span>
			<select name="is_displayed">
				<option value="1" <?=(ifSet($category_is_displayed)) ? "selected" : ""?>>Yes</option>
				<option value="0" <?=(!ifSet($category_is_displayed)) ? "selected" : ""?>>No</option>
			</select>
		</p>
		<input class="rcmm-form-button" type="submit" value="Save">
	</form>
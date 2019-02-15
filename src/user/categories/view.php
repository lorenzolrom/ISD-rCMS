<?php
	if(!empty($_GET['delete']) AND !empty($_GET['c']))
	{
		$category_id = $_GET['c'];
		if(!is_numeric($category_id))
		{
			header('Location:index.php');
			exit();
		}
		
		// verify page exists
		$get = $conn->prepare("SELECT * FROM Category WHERE id = ? LIMIT 1");
		$get->bindParam(1, $category_id);
		$get->execute();
		
		if($get->rowCount() != 1)
		{
			header('Location: index.php');
			exit();
		}
		else
		{
			$delete = $conn->prepare("DELETE FROM Category WHERE id = ?");
			$delete->bindParam(1, $category_id);
			
			if($delete->execute())
			{
				header("Location:index.php");
				exit();
			}
		}
	}
	
	if(!empty($_GET['c']))
	{
		if(is_numeric($_GET['c']))
		{
			$get = $conn->prepare("SELECT * FROM Category WHERE id = ? LIMIT 1");
			$get->bindValue(1, $_GET['c']);
			
			$get->execute();
			
			if($get->rowCount() == 1)
			{
				$category = $get->fetch();
				
				$category_id = $category['id'];
				$category_name = $category['name'];
				$category_picture = $category['picture'];
				$category_is_displayed = $category['is_displayed'];
			}
			else
			{
				header("Location:index.php");
				exit();
			}
		}
		else
		{
			header("Location:index.php");
			exit();
		}
	}
	else
	{
		header("Location:index.php");
		exit();
	}
	
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
			$insert = $conn->prepare("UPDATE Category SET is_displayed = ?, name = ?, picture = ? WHERE id = ?");
			
			$insert->bindParam(1, $category_is_displayed);
			$insert->bindParam(2, $category_name);
			$insert->bindParam(3, $category_picture);
			$insert->bindParam(4, $category_id);
			
			if($insert->execute())
			{
				$notifications = "Category Updated Succesfully";
				header("Location:index.php");
				exit();
			}
		}
	}
	require_once(PATH_ELEM . 'notifications.php');
?>
	<h1>Edit Category</h1>
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
				<option value="1" <?=($category_is_displayed) ? "selected" : ""?>>Yes</option>
				<option value="0" <?=(!$category_is_displayed) ? "selected" : ""?>>No</option>
			</select>
		</p>
		<input class="rcmm-form-button" type="submit" value="Save">
	</form>
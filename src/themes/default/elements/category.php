<?php
	$category_id = $_GET['c'];
	
	if(!is_numeric($category_id))
	{
		header("Location:" . SITE_URI . "posts/");
		exit();
	}
	
	if($category = getFirstByID("Category", $category_id))
	{
		
		$category_name = $category['name'];
	}
	else
	{
		header("Location:" . SITE_URI . "posts/");
		exit();
	}
?>
<h1><?=$category_name?></h1>
<div class="category-display">
<?php
	$get_items = $conn->prepare("SELECT name, picture, content FROM Post WHERE category = ? AND is_displayed = 1 ORDER BY name ASC");
	$get_items->bindParam(1, $category_id);
	
	$get_items->execute();
	
	foreach($get_items->fetchAll() as $category_item)
	{
		$category_item_name = $category_item['name'];
		$category_item_picture = $category_item['picture'];
		$category_item_content = $category_item['content'];
		?>
		<h2><?=$category_item_name?></h2>
		<div class="category-row">
			<a href="<?=URI_FILE . $category_item_picture?>"><img src="<?=URI_FILE . $category_item_picture?>" alt="<?$category_item_name?>"></a>
			<div class="category-content"><?=$category_item_content?></div>
		</div>
		<?php
	}
?>
</div>
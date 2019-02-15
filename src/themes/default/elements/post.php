<?php
	$post_id = $_GET['p'];
	
	if(!is_numeric($post_id))
	{
		header("Location:" . SITE_URI . "posts/");
		exit();
	}
	
	if($post = getFirstByID("Post", $post_id))
	{
		
		$post_name = $post['name'];
		$post_picture = $post['picture'];
		$post_content = $post['content'];
		$post_date = $post['date'];
	}
	else
	{
		header("Location:" . SITE_URI . "posts/");
	}
?>
<h1><?=$post_name?></h1>
<div class="post-display">
	<a href="<?=URI_FILE . $post_picture?>"><img src="<?=URI_FILE . $post_picture?>" alt="<?=$post_name?>"></a>
	<span class="post-date"><?=$post_date?></span>
	<div class="post-content">
		<?=$post_content?>
	</div>
</div>
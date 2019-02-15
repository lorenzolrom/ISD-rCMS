<div class="post-list">
	<?php		
		// Get all posts in alphabetical order
		$all_posts = $conn->query("SELECT id, name, picture, date FROM Post WHERE is_displayed = 1 ORDER BY name ASC");
		
		foreach($all_posts as $post)
		{			
			$post_id = $post['id'];
			$post_name = $post['name'];
			$post_picture = $post['picture'];
			$post_date = $post['date'];
			
			?><div class="post-item"><a href="<?=SITE_URI?>posts?p=<?=$post_id?>"><img src="<?=URI_FILE . $post_picture?>" alt="<?=$post_name?>"><span><?=$post_date?></span><p><?=$post_name?></p></a></div><?php
		}
	?>
</div>
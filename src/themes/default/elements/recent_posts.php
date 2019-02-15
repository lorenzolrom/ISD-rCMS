<h2>Featured Posts</h2>
<div class="post-list">
	<?php
		$recent_posts = $conn->prepare("SELECT * FROM Post WHERE is_featured = 1 ORDER BY RAND() DESC LIMIT 4");
		$recent_posts->execute();
		
		foreach($recent_posts->fetchAll() as $post)
		{			
			$post_id = $post['id'];
			$post_name = $post['name'];
			$post_picture = $post['picture'];
			$post_displayed = $post['is_displayed'];
			
			if($post_displayed)
			{
				?><div class="post-item"><a href="<?=SITE_URI?>posts?p=<?=$post_id?>"><img src="<?=URI_FILE . $post_picture?>" alt="<?=$post_name?>"><p><?=$post_name?></p></a></div><?php
			}
		}
	?>
</div>
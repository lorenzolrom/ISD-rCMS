<div class="post-list">
	<?php
		$all_categorys = getAllOfType("Category");
		
		$all_categorys = array_reverse($all_categorys);
		
		foreach($all_categorys as $category)
		{
			$category_id = $category['id'];
			$category_name = $category['name'];
			$category_picture = $category['picture'];
			$category_displayed = $category['is_displayed'];
			
			if($category_displayed)
			{
				?><div class="lrom-post-item"><a href="<?=SITE_URI . "posts?c=$category_id"?>"><img src="<?=URI_FILE . $category_picture?>" alt="<?=$category_name?>"><p><?=$category_name?></p></a></div><?php
			}
		}
	?>
</div>
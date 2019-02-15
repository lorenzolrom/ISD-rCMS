<div class="single-banner">
	<?php
		$image = fetchContent($index_page_id, 'banner');
		if(count($image) > 0)
		{
			$image = $image[0]['content'];
			?><img src="<?=URI_FILE . $image?>" alt=""><?php
		}
	?>
</div>
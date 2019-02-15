<div class="tri-banner">	
	<?php
		// Loop through three images in bannner
		for($i = 1; $i <=3; $i++)
		{
			// Get content from database
			$image = fetchContent($index_page_id, "banner-" . $i);
			if(count($image) > 0)
			{
				// Get source from database
				$image_src = $image[0]['content'];
				?><img class="lrom-tri-banner-image" src="<?=URI_FILE . $image_src?>" alt=""><?php
			}
		}
	?>
</div>
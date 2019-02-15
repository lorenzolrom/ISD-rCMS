<div class="left-sidebar">
	<?php
		foreach(fetchContent($index_page_id, 'left-sidebar') as $item)
			echo $item['content'];
	?>
</div>
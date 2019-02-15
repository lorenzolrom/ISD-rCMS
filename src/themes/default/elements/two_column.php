<div class="two-column">
	<div class="column-2">
		<?php
			foreach(fetchContent($index_page_id, 'column-1') as $item)
						echo $item['content'];
		?>
	</div>
	
	<div class="column-2">
		<?php
			foreach(fetchContent($index_page_id, 'column-2') as $item)
						echo $item['content'];
		?>
	</div>
</div>
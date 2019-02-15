<div >
	<div id="site-title">
		<?php
			foreach(fetchContent($index_page_id, 'site-title') as $item)
				echo $item['content'];
		?>
	</div>
	<div id="site-tagline">
		<?php
			foreach(fetchContent($index_page_id, 'site-tagline') as $item)
				echo $item['content'];
		?>
	</div>
</div>
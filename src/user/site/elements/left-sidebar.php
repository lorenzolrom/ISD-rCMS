			<div class="rcmm-left-sidebar">
				<div class="rcmm-sidebar-title">
					Actions
				</div>
				<div class="rcmm-sidebar-navigation">
					<ul>
						<?php
							if(isset($sidebar_links))
							{
								foreach($sidebar_links as $title => $link)
								{
									echo "<li><a href='$link'>$title</a></li>";
								}
							}
						?>
					</ul>
				</div>
			</div>
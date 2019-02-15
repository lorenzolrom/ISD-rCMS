<h1>Files</h1>
<input class="rcmm-table-filter" type="text" placeholder="Filter">
<table class="rcmm-table file-table">
	<tr>
		<th></th>
		<th></th>
		<th>File Name</th>
	</tr>
	<?php
		
		$site_files = scandir(dirname(__FILE__) . "/../../site/files/");
		
		// drop the first two files, which will be . and ..
		unset($site_files[0]);
		unset($site_files[1]);
		foreach($site_files as $site_file)
		{
		?>
			<tr>
				<td><a href="<?=SITE_URI?>site/files/<?=$site_file?>"><img src="<?=URI_GRA . 'sprites/view.gif'?>" alt="V"></a></td>
				<td><a class="rcmm-cancel-button" href="?p=delete&f=<?=$site_file?>"><img src="<?=URI_GRA . 'sprites/delete.gif'?>" alt="D"></a></td>
				<td><?=$site_file?></td>
			</tr>
		<?php
		}
	?>
</table>
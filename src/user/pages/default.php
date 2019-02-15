<h1>Pages</h1>
<input class="rcmm-table-filter" type="text" placeholder="Filter">
<table class="rcmm-table">
	<tr>
		<th></th>
		<th class="id-column">ID</th>
		<th>Name</th>
		<th></th>
	</tr>
	<?php
		// Get list of all pages
		$get_pages = $conn->query("SELECT id, name FROM Page");
		
		foreach($get_pages->fetchAll() as $this_page)
		{
			?>
			<tr>
				<td><a href="?p=view&a=<?=$this_page['id']?>"><img src="<?=URI_GRA . 'sprites/edit.gif'?>" alt="E"></a></td>
				<td><?=$this_page['id']?></td>
				<td><?=$this_page['name']?></td>
				<td><a class="rcmm-cancel-button" href="?p=view&delete=yes&a=<?=$this_page['id']?>"><img src="<?=URI_GRA . 'sprites/delete.gif'?>" alt="D"></a></td>
			</tr>
			<?php
		}
	?>
</table>
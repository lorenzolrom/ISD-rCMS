<h1>Doorways</h1>
<input class="rcmm-table-filter" type="text" placeholder="Filter">
<table class="rcmm-table">
	<tr>
		<th></th>
		<th class="id-column">ID</th>
		<th>URL</th>
		<th></th>
	</tr>
	<?php
		// Get list of all collections
		$get_doorways = $conn->query("SELECT id,url FROM Doorway");
		
		foreach($get_doorways->fetchAll() as $this_doorway)
		{
			?>
			<tr>
				<td><a href="?p=view&d=<?=$this_doorway['id']?>"><img src="<?=URI_GRA . 'sprites/edit.gif'?>" alt="E"></a></td>
				<td><?=$this_doorway['id']?></td>
				<td><?=$this_doorway['url']?></td>
				<td><a class="rcmm-cancel-button" href="?p=view&delete=yes&d=<?=$this_doorway['id']?>"><img src="<?=URI_GRA . 'sprites/delete.gif'?>" alt="D"></a></td>
			</tr>
			<?php
		}
	?>
</table>
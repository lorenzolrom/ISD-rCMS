	<h1>Categories</h1>
	<input class="rcmm-table-filter" type="text" placeholder="Filter">
	<table class="rcmm-table">
		<tr>
			<th></th>
			<th class="id-column">ID</th>
			<th>Name</th>
			<th></th>
		</tr>
		<?php
			// Get list of all categorys
			$get_categorys = $conn->query("SELECT id, name FROM Category");
			
			foreach($get_categorys->fetchAll() as $this_category)
			{
				?>
				<tr>
					<td><a href="?p=view&c=<?=$this_category['id']?>"><img src="<?=URI_GRA . 'sprites/edit.gif'?>" alt="E"></a></td>
					<td><?=$this_category['id']?></td>
					<td><?=$this_category['name']?></td>
					<td><a class="rcmm-cancel-button" href="?p=view&delete=yes&c=<?=$this_category['id']?>"><img src="<?=URI_GRA . 'sprites/delete.gif'?>" alt="D"></a></td>
				</tr>
				<?php
			}
		?>
	</table>
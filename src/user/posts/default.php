<h1>Posts</h1>
<input class="rcmm-table-filter" type="text" placeholder="Filter">
<table class="rcmm-table">
	<tr>
		<th></th>
		<th class="id-column">ID</th>
		<th>Name</th>
		<th></th>
	</tr>
	<?php
		// Get list of all posts
		$get_posts = $conn->query("SELECT id, name FROM Post");
		
		foreach($get_posts->fetchAll() as $this_post)
		{
			?>
			<tr>
				<td><a href="?p=view&r=<?=$this_post['id']?>"><img src="<?=URI_GRA . 'sprites/edit.gif'?>" alt="E"></a></td>
				<td><?=$this_post['id']?></td>
				<td><?=$this_post['name']?></td>
				<td><a class="rcmm-cancel-button" href="?p=view&delete=yes&r=<?=$this_post['id']?>"><img src="<?=URI_GRA . 'sprites/delete.gif'?>" alt="D"></a></td>
			</tr>
			<?php
		}
	?>
</table>
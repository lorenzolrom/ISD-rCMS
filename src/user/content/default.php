<h1>Content</h1>
<?php
	// Get all pages
	$get_pages = $conn->query("SELECT id, name FROM Page");
	foreach($get_pages->fetchAll() as $this_page)
	{
		?>
		<div class="rcmm-dropdown-table">
			<span class="rcmm-dropdown-title"><?=$this_page['id']?> - <?=$this_page['name']?></span>
			<table class="rcmm-table">
				<tr>
					<th></th>
					<th class="id-column">ID</th>
					<th>Name</th>
					<th></th>
				</tr>
		<?php
			// Get content for this page
			$get_content = $conn->prepare("SELECT id, name FROM Content WHERE page = ?");
			$get_content->bindValue(1, $this_page['id']);
			$get_content->execute();
			
			foreach($get_content->fetchAll() as $this_content)
			{
				?>
				<tr>
					<td><a href="?p=view&c=<?=$this_content['id']?>"><img src="<?=URI_GRA . 'sprites/edit.gif'?>" alt="E"></a></td>
					<td><?=$this_content['id']?></td>
					<td><?=$this_content['name']?></td>
					<td><a class="rcmm-cancel-button" href="?p=view&delete=yes&c=<?=$this_content['id']?>"><img src="<?=URI_GRA . 'sprites/delete.gif'?>" alt="D"></a></td>
				</tr>
				<?php
			}
		?>
			</table>
		</div>
		<?php
	}
?>
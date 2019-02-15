<h1>Manage Users</h1>
<a href="?p=users-create" class="rcmm-form-button">Create New User</a>

<table class="rcmm-table">
	<tr>
		<th></th>
		<th>Username</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th></th>
	</tr>
	<?php
		// Get list of all current users
		$all_users = $conn->query("SELECT username, first_name, last_name FROM User");
		
		foreach($all_users->fetchAll() as $this_user)
		{
			?>
			<tr>
				<td><a href="?p=users-edit&u=<?=$this_user['username']?>"><img src="<?=URI_GRA . 'sprites/edit.gif'?>" alt="E"></a></td>
				<td><?=$this_user['username']?></td>
				<td><?=$this_user['first_name']?></td>
				<td><?=$this_user['last_name']?></td>
				<td><a class="rcmm-cancel-button" href="?p=users-delete&u=<?=$this_user['username']?>"><img src="<?=URI_GRA . 'sprites/delete.gif'?>" alt="D"></a></td>
			</tr>
			<?php
		}
	?>
</table>
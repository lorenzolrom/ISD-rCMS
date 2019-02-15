<?php
	// Process changes if the form was saved
	if(!empty($_POST))
	{
		$update_setting = $conn->prepare("UPDATE Setting SET value = ? WHERE code = ?");
		
		// Loop through each value in the form, and update it in the table
		foreach(array_keys($_POST) as $save_setting)
		{
			$update_setting->bindValue(1, $_POST[$save_setting]);
			$update_setting->bindValue(2, $save_setting);
			
			if(!$update_setting->execute())
				$errors[] = "Failed to save setting: " . $save_setting;
		}
		
		$notifications = "Settings Saved";
	}
	
	// Retrieve the current site settings
	$get_settings = $conn->query("SELECT code, display_name, value FROM Setting");
	
	// Include notifications box
	require_once(PATH_ELEM . 'notifications.php');
?>
<h1>Site Settings</h1>
<form class="rcmm-form" method="post">
<?php
	// Generate forms for all site settings
	foreach($get_settings->fetchAll() as $this_setting)
	{
		$setting_code = $this_setting['code'];
		$setting_display_name = $this_setting['display_name'];
		$setting_value = $this_setting['value'];
		
		?>
			<p>
				<span><?=$setting_display_name?></span>
				<input type="text" name="<?=$setting_code?>" value="<?=$setting_value?>">
			</p>
		<?php
	}
?>
<input class="rcmm-form-button" type="submit" value="Save">
</form>
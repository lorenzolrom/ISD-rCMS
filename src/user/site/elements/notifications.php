<div id="rcmm-notifications">
	<div id="rcmm-notifications-dismiss" onclick="document.getElementById('rcmm-notifications').style.display = 'none';">X</div>
	<?php
		if(!empty($errors))
		{
			$notifications = "<h3>Error:</h3><br/>";
			foreach($errors as $error)
			{
				$notifications .= "- $error<br/>";
			}
			
			echo $notifications;
		}
		else if(!empty($notifications))
		{
			echo "<h3>Notice:</h3><br/>";
			echo $notifications;
		}
		
		if(!empty($notifications))
		{
			echo "<script>document.getElementById('rcmm-notifications').style.display='block';</script>\n";
		}
	?>
</div>
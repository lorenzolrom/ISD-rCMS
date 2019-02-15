<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="<?=SITE_URI?>user/site/stylesheets/rcmm-main.css">
		<link rel="stylesheet" type="text/css" href="<?=SITE_URI?>user/site/stylesheets/calendar-win2k-cold-1.css">
		<script type="text/javascript" src="<?=SITE_URI?>user/site/scripts/calendar.js"></script>
		<script type="text/javascript" src="<?=SITE_URI?>user/site/scripts/calendar-en.js"></script>
		<script type="text/javascript" src="<?=SITE_URI?>user/site/scripts/calendar-setup.js"></script>
		<title>rCMS Manager</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
		<div class="rcmm-header">
			<div class="rcmm-header-topnav">
				<div class="rcmm-topnav-menu">
					<ul>
						<li><a href="">Welcome, <?=$_SESSION['name']?></a></li>
						<li><a href="<?=RCMM_SITE_URI?>logout.php">Logout</a></li>
					</ul>
				</div>
				<div class="rcmm-topnav-end"></div>
			</div>
			<div id="rcmm-logo">r-Content Management System</div>
			<div id="rcmm-site-title"><?=strtoupper($conn->query("SELECT value FROM Setting WHERE code = 'swtl' LIMIT 1")->fetchColumn())?></div>
			<div class="rcmm-header-bottomnav">
				<ul>
					<li><a href="<?=RCMM_SITE_URI?>">Home</a></li>
					<li><a href="<?=RCMM_SITE_URI?>pages">Pages</a></li>
					<li><a href="<?=RCMM_SITE_URI?>content">Content</a></li>
					<li><a href="<?=RCMM_SITE_URI?>posts">Posts</a></li>
					<li><a href="<?=RCMM_SITE_URI?>categories">Categories</a></li>
					<li><a href="<?=RCMM_SITE_URI?>doorways">Doorways</a></li>
					<?php
						if(hasPermission('fmgr'))
						{
							?><li><a href="<?=RCMM_SITE_URI?>files">Files</a></li><?php
						}
					?>
					<?php
						if(hasPermission('cpan'))
						{
							?><li><a href="<?=RCMM_SITE_URI?>cpanel">Control Panel</a></li><?php
						}
					?>
				</ul>
			</div>
		</div>
		<div id="pageveil"></div>
		<div class="rcmm-main">
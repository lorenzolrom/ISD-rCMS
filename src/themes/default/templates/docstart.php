<!DOCTYPE html>
<html lang="en">
	<!-- rCMS was developed by LLR Information Systems Development - isd.llrtech.com -->
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="<?=URI_CSS?>main.css">
		<link rel="icon" type="image/ico" href="<?=URI_GRA?>favicon.ico">
		<title><?=getPageTitle($index_page_id)?></title>
		<meta name="description" content="<?=getSiteDescription()?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
		<div id="viewport-wrapper">
			<div id="sw-header">
				<div id="sw-title"><?=getSiteTitle()?></div>
				<div id="sw-header-lower">
					<form id="sw-searchbar" method="get" action="<?=SITE_URI?>search">
						<input autocomplete="off" id="sw-searchbar-input" name="query" type="text" placeholder="Search Website">
					</form>
					<ul id="sw-navigation">
						<?php
							foreach(getPages() as $page)
							{
								$nav_page_is_on_nav = $page['is_on_nav'];
								
								if(!$nav_page_is_on_nav) // if the page is not set to be on the nav bar, skip
									continue;
								
								$nav_page_id = $page['id'];
								$nav_page_name = $page['name'];
								$nav_page_display_name = $page['display_name'];
								$nav_page_url = $page['url'];
								
								$nav_actual_display_name = (empty($nav_page_display_name)) ? $nav_page_name : $nav_page_display_name;
								$nav_full_uri = SITE_URI . $nav_page_url;
								
								$nav_is_current = ($index_page_id == $nav_page_id) ? " nav-current" : "";
								echo "<li><a class='nav-item$nav_is_current' href='$nav_full_uri'>$nav_actual_display_name</a></li>\n";
							}
						?>
					</ul>
				</div>
			</div>
			<div id="content">
<?php

	if(!empty($_GET['p']))
	{
		require(PATH_ELEM . "post.php");
	}
	else if(!empty($_GET['c']))
	{
		require(PATH_ELEM . "category.php");
	}
	else
	{
		echo "<h1>" . getPageTitle($index_page_id) . "</h1>\n";
		echo "<h2>Categories</h2>\n";
		require(PATH_ELEM . "category_list.php");
		echo "<h2>All Posts</h2>\n";
		require(PATH_ELEM . "post_list.php");
	}
?>
<h1><?=getPageTitle($index_page_id)?></h1>
<div class="search-results">
<?php
	$no_results = TRUE;
	
	if(!empty($_GET['query']))
	{
		
		$search_query = $_GET['query'];
		
		// filter out any HTML tags (or brackets)
		$search_query = preg_replace('/<[^>]*>/','', $search_query);
		
		if(strlen($search_query) < 3)
		{
			echo "<h2>Your search is not specific enough, no results shown!</h2>\n";
			echo "<p>Please search for at least 3 characters.</p>";
		}
		else
		{
			
			$search_raw = $search_query; // preserve original query for highlighting
			$search_query = "%" . $search_query . "%"; // add wild card to find any matching case of query
			/*
			* Fetch matching results from pages
			*/
			
			// prepares queries
			$get_matching_content = $conn->prepare("SELECT name, page, content FROM Content WHERE content LIKE ? OR name LIKE ? AND is_searchable = 1");
			$get_matching_content->bindParam(1, $search_query);
			$get_matching_content->bindParam(2, $search_query);
			
			$get_matching_content->execute();
			
			$matching_content_count = $get_matching_content->rowCount();
			
			if($matching_content_count > 0)
			{
				$no_results = FALSE;
				
				echo "<h2>Matching Content</h2>";
				echo "<p>Showing $matching_content_count results</p>";
				
				foreach($get_matching_content->fetchAll() as $result)
				{
					$match_content = $result['content'];
					$match_page = $result['page'];
					$match_name = $result['name'];
					
					$content_uri = SITE_URI . getColumnFrom("url", "Page", $match_page);
					
					echo "<div class='search-result'>\n";
					echo "<p><a href='$content_uri'>" . getColumnFrom("name", "Page", $match_page) . "</a></p>";
					
					preg_match("/(\w+)? ?(\w+)? ?(\w+)? ?(\w+)? ?(\w+)? ?$search_raw ?(\w+)? ?(\w+)? ?(\w+)? ?(\w+)? ?(\w+)?/i",$match_content, $result);
					echo "<p>\"..." . preg_replace("/$search_raw/i", "<strong>$search_raw</strong>", $result[0]) . "...\"</p>";
					
					echo "</div>\n";
				}
			}
			
			
			/*
			* Fetch matching results from posts
			*/
			$get_matching_posts = $conn->prepare("SELECT id, name, content FROM Post WHERE is_displayed = 1 AND (content LIKE ? OR name LIKE ?)");
			$get_matching_posts->bindParam(1, $search_query);
			$get_matching_posts->bindParam(2, $search_query);
			
			$get_matching_posts->execute();
			
			$matching_posts_count = $get_matching_posts->rowCount();
			
			if($matching_posts_count > 0)
			{
				$no_results = FALSE;
				
				echo "<h2>Matching Posts</h2>";
				echo "<p>Showing $matching_posts_count results</p>";
				
				foreach($get_matching_posts->fetchAll() as $result)
				{
					$match_content = $result['content'];
					$match_id = $result['id'];
					$match_name = $result['name'];
					
					$content_uri = SITE_URI . "posts?p=$match_id";
					
					echo "<div class='search-result'>\n";
					echo "<p><a href='$content_uri'>$match_name</a></p>";
					
					preg_match("/(\w+)? ?(\w+)? ?(\w+)? ?(\w+)? ?(\w+)? ?$search_raw ?(\w+)? ?(\w+)? ?(\w+)? ?(\w+)? ?(\w+)?/i",$match_content, $result);
					
					// Verify that the result is actually present in the content (i.e. the matching string will be found in the mask)
					if(!empty($result[0]))
					{
						echo "<p>\"..." . preg_replace("/$search_raw/i", "<strong>$search_raw</strong>", $result[0]) . "...\"</p>";
					}
					else
					{
						echo "<p></p>";
					}
					
					echo "</div>\n";
				}
			}
		}
	}
	else
	{
		echo "<h2>Your search is not specific enough, no results shown!</h2>\n";
		echo "<p>Please search for at least 3 characters.</p>";
	}
	
	if($no_results AND strlen($search_query) >= 3)
	{
		echo "<h2>No Results Found!</h2>\n";
		echo "<p>Your search did not produce any results.</p>";
	}
?>
</div>
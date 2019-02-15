<?php
	/**
	* This is the main rCMS functions file.
	*/
	
	/*
	* Returns the given string as a formatted table name
	*/
	function toTableName($table)
	{
		return ucfirst(implode('_', array_map('ucfirst', explode('_', strtolower($table)))));
	}
	
	/*
	* Returns the given string as a formatted column name
	*/
	function toColumnName($column)
	{
		return strtolower($column);
	}
	
	/*
	* Returns the file_name, if available, of the given type, provided an id.
	* This function is restricted to system use only, as type is not sanitized
	*/
	function getFileName($table, $id)
	{
		global $conn;
		
		$table = toTableName($table);
		
		$get = $conn->prepare("SELECT file_name FROM $table WHERE id = ? LIMIT 1");
		$get->bindparam(1, $id);
		
		$get->execute();
		
		$row = $get->fetch();
		
		return $row['file_name'];
	}
	
	/*
	* Returns the first row from the supplied table with a matching id
	*/
	function getFirstByID($table, $id)
	{
		global $conn;
		
		$table = toTableName($table);
		
		$get = $conn->prepare("SELECT * FROM $table WHERE id = ? LIMIT 1");
		$get->bindParam(1, $id);
		
		$get->execute();
		
		if($get->rowCount() == 1)
			return $get->fetch();
		else
			return FALSE;
	}
	
	/*
	* Returns the specified column from the specified table with the given ID
	*/
	function getColumnFrom ($column, $table, $id)
	{
		global $conn;
		
		$column = toColumnName($column);
		$table = toTableName($table);
		
		$get = $conn->prepare("SELECT $column FROM $table WHERE id = ? LIMIT 1");
		$get->bindParam(1, $id);
		
		$get->execute();
		
		$row = $get->fetch();
		
		return $row[$column];
	}
	
	/*
	* Get all of the specified type
	*/
	function getAllOfType($table)
	{
		global $conn;
		
		$table = toTableName($table);
		
		$get = $conn->prepare("SELECT * FROM $table");
		$get->execute();
		
		return $get->fetchAll();
	}

	/*
	* Returns the id of a Page with matching URL
	*/
	function getPageID($url)
	{
		global $conn;
		
		$get = $conn->prepare("SELECT id FROM Page WHERE url = ? LIMIT 1");
		$get->bindParam(1, $url);
		
		$get->execute();
		return $get->fetch()['id'];
	}
	
	/*
	* Returns the title of the site
	*/
	function getSiteTitle()
	{
		global $conn;
		
		return $conn->query("SELECT value FROM Setting WHERE code = 'swtl' LIMIT 1")->fetchColumn();
	}
	
	/*
	* Returns the site description
	*/
	function getSiteDescription()
	{
		global $conn;
		
		return $conn->query("SELECT value FROM Setting WHERE code = 'swds' LIMIT 1")->fetchColumn();
	}
	
	/*
	* Returns the formatted title of a page for display in tab
	*/
	function getPageTitle($id)
	{		
		$title = getColumnFrom("name", "Page", $id);
		
		if($title == "Home")
			$title = getSiteTitle();
		
		return $title;
	}
	
	/*
	* Returns an array of content items for the given element
	*/
	function fetchContent($page, $element)
	{
		global $conn;
		
		$get = $conn->prepare("SELECT content FROM Content where page = ? AND element = ? AND is_displayed = 1 ORDER BY weight ASC");
		$get->bindParam(1, $page);
		$get->bindParam(2, $element);
		
		$get->execute();
		
		return $get->fetchAll();
	}
	
	/*
	* Returns array of pages for the supplied site
	*/
	function getPages()
	{
		global $conn;
		
		$get = $conn->prepare("SELECT id, name, display_name, url, is_on_nav FROM Page ORDER BY nav_weight ASC");
		$get->execute();
		
		return $get->fetchAll();
	}
	
	/*
		Returns the destination of a Doorway, or false if it does not exist
	*/
	function getDoorway($uri)
	{
		global $conn;
		
		$get = $conn->prepare("SELECT destination FROM Doorway WHERE url = ? AND enabled = 1 LIMIT 1");
		$get->bindParam(1, $uri);
		
		$get->execute();
		
		if($get->rowCount() == 1)
			return $get->fetch()['destination'];
		
		return FALSE;
	}
?>
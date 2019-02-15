<?php

	/**
	* This is the main rCMS functions file.
	*/
	
	/*
	* Returns the given string as a formatted table name
	*/
	function toTableName($table)
	{
		return lcfirst(implode('_', array_map('ucfirst', explode('_', strtolower($table)))));
	}
	
	/*
	* Returns the given string as a formatted column name
	*/
	function toColumnName($column)
	{
		return strtolower($column);
	}
	
	/*
	* Determines if the given table name is valid
	*/
	function isValidTable($table)
	{
		global $db_tables;
		
		if(!(array_search($table, $db_tables) === false))
			return true;
		return false;
	}
	
	/*
	* Determines if the given column name is valid
	*/
	function isValidColumn($column)
	{
		global $db_columns;
		
		if(!(array_search($column, $db_columns) === false))
			return true;
		return false;
	}

	/*
	* Returns the specified column from the specified table with the given ID
	*/
	function getColumnFrom ($column, $table, $id)
	{
		global $conn;
		
		$column = toColumnName($column);
		$table = toTableName($table);
		
		if((!isValidTable($table)) OR (!isValidColumn($column)))
			return false;
		
		$get = $conn->prepare("SELECT $column FROM $table WHERE id = ? LIMIT 1");
		$get->bindParam(1, $id);
		
		$get->execute();
		
		$row = $get->fetch();
		
		return $row[$column];
	}

	/*
	* Returns the file_name, if available, of the given type, provided an id.
	* This function is restricted to system use only, as type is not sanitized
	*/
	function getFileName($table, $id)
	{
		global $conn;
		
		$table = toTableName($table);
		
		if(!isValidTable($table))
			return false;
		
		$get = $conn->prepare("SELECT file_name FROM $table WHERE id = ? LIMIT 1");
		$get->bindparam(1, $id);
		
		$get->execute();
		
		$row = $get->fetch();
		
		return $row['file_name'];
	}
	
	/*
	* Returns the id of a Page with matching URL AND matching Site id
	*/
	function getPageID($url)
	{
		global $conn;
		
		$get = $conn->prepare("SELECT id FROM rcmm_Page WHERE url = ? LIMIT 1");
		$get->bindParam(1, $url);
		
		$get->execute();
		
		return $get->fetch()['id'];
	}
	
	/*
	* Returns the formatted title of a page for display in tab
	*/
	function getPageTitle($id)
	{		
		$title = getColumnFrom("name", "Page", $id);
		
		if($title == "Home")
			$title = getColumnFrom("name", "Site", getColumnFrom("site", "Page", $id));
		
		return $title;
	}
	
	/*
	* Returns array of pages for the supplied site
	*/
	function getPages()
	{
		global $conn;
		
		$get = $conn->prepare("SELECT * FROM Page");
		$get->bindParam(1, $site);
		$get->execute();
		
		return $get->fetchAll();
	}
	
	/**
	* Returns a list of all Roles as options, with 'default' selected
	*/
	function getRoleOptions(&$default="")
	{
		global $conn;
		
		$roles = $conn->query("SELECT id, display_name FROM Role");
		
		$options = "";
		foreach($roles->fetchAll() as $role)
		{
			$id = $role['id'];
			$name = $role['display_name'];
			
			$selected = ($default == $id) ? " selected" : " ";
			
			$options .= "<option value=$id$selected>$name</option>\n";
		}
		
		return $options;
	}
	
	/*
	* Returns array of pages as formatted options, with default selected
	*/
	function getPagesAsOptions(&$default="")
	{
		global $conn;
		
		$pages = getPages();
		
		$options = "";
		foreach($pages as $page)
		{
			$id = $page['id'];
			$name = $page['name'];
			
			$selected = ($default == $id) ? "selected" : "";
			
			$options .= "<option value=$id $selected>$id - $name</option>";
		}
		
		return $options;
	}
	
	/*
	* Returns a list of templates as options, with the default selected
	*/
	function getTemplateOptions($default = "")
	{
		global $conn;
		
		$get = $conn->prepare("SELECT id,name,description FROM Template");
		$get->execute();
		
		$options = "";
		
		foreach($get->fetchAll() as $template)
		{
			$id = $template['id'];
			$name = $template['name'];
			$description = $template['description'];
			
			$selected = ($default == $id) ? "selected" : "";
			
			$options .= "<option value='$id' title='$description' $selected>$name</option>\n";
		}
		
		return $options;
	}
	
	/*
	* Returns a list of elements for a given page template, with the default selected
	*/
	function getElementOptionsByPage($page, &$default = "")
	{
		global $conn;
		
		// determine page template
		$get_temp = $conn->prepare("SELECT template FROM Page WHERE id = ? LIMIT 1");
		$get_temp->bindParam(1, $page);
		$get_temp->execute();
		
		$template = $get_temp->fetchColumn();
		
		$get = $conn->prepare("SELECT element FROM Template_Element WHERE template = ?");
		$get->bindParam(1, $template);
		$get->execute();
		
		$options = "";
		foreach($get->fetchAll() as $element)
		{
			$ele = $element['element'];
			$selected = ($ele == $default) ? "selected" : "";
			
			$options .= "<option value='$ele' $selected>$ele</option>\n";
		}
		
		return $options;
	}
	
	/*
	* returns a list of categorys, with the default selected
	*/
	function getCategoryOptions(&$default = "")
	{
		global $conn;
		
		$get = $conn->prepare("SELECT id, name FROM Category");
		$get->execute();
		
		$options = "";
		foreach($get->fetchAll() as $category)
		{
			$id = $category['id'];
			$name = $category['name'];
			
			$selected = ($id == $default) ? "selected" : "";
			
			$options .= "<option value='$id' $selected>$id - $name</option>\n";
		}
		
		return $options;
	}
	
	/*
	* Returns the string given if it is set
	*/
	function ifSet(&$string)
	{
		if(isset($string))
			return $string;
	}
	
	/**
	* Returns whether or not the username already exists
	*/
	function usernameExists($username)
	{
		global $conn;
		
		$check = $conn->prepare("SELECT username FROM User WHERE username = ? LIMIT 1");
		$check->bindParam(1, $username);
		$check->execute();
		
		if($check->rowCount() == 1)
			return TRUE;
		
		return FALSE;
	}
	
	/**
	* Validate the current user has the permission
	*/
	function hasPermission($code)
	{
		global $conn;
		
		$check = $conn->prepare("SELECT id FROM User WHERE role IN (SELECT role FROM Role_Permission WHERE permission = ?) AND username = ? LIMIT 1");
		$check->bindParam(1, $code);
		$check->bindValue(2, $_SESSION['username']);
		$check->execute();
		
		if($check->rowCount() == 1)
			return TRUE;
		
		return FALSE;
	}
	
	/*
	* Validates a given string as a date against the format specified
	*/
	function validDate($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
?>
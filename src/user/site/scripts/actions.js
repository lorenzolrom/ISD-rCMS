/**
* This function interactively searches the table and only displays rows matching the filter
*/
function filter(input)
{
	// Get the input from the filter field
	var filter = input.value.toUpperCase();
	
	// Get the table immediately following the filter
	var table = input.nextElementSibling;
	var rows = table.getElementsByTagName("tr");
	
	for(var i = 0; i < rows.length; i++)
	{
		// get the A tag containing the log title
		this_link = rows[i].getElementsByTagName("td")[2];
		if(this_link)
		{
			// If the filter is present, show the row, if not
			// hide it
			if(this_link.innerHTML.toUpperCase().indexOf(filter) > -1)
			{
				rows[i].style.display = "";
			}
			else
			{
				rows[i].style.display = "none";
			}
		}
	}
}

/**
* Add action listener to all rcmm-table-filter
*/
var tableFilters = document.getElementsByClassName("rcmm-table-filter");

for(var i = 0; i < tableFilters.length; i++)
{
	tableFilters[i].addEventListener("keyup", function(){filter(this)});
}

/**
* Add action listener to any #date elements
*/
if(document.getElementById("date"))
{
	document.getElementById("date").addEventListener("load", Calendar.setup({inputField : "date"}));
}
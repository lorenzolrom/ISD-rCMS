/**
* rCMS Button Action File
*/

// This will add an opaque layer over the screen
function veil()
{
	document.getElementById('pageveil').style.display = 'block';
}

// This will undo the previous message
function unveil()
{
	document.getElementById('pageveil').style.display = 'none';
}

// Set up all form buttons to veil the screen on click
var formbuttons = document.getElementsByClassName('rcmm-form-button');

for (var i = 0; i < formbuttons.length; i++)
{
	formbuttons[i].addEventListener("click", function(){veil();}, false);
}

// Add event listener to cancel buttons to confirm before executing
var cancelbuttons = document.getElementsByClassName('rcmm-cancel-button');
var confirmCancel = function(e)
{
	if(!confirm('Are you sure?'))
	{
		unveil();
		e.preventDefault();
	}
};

for (var i =0; i< cancelbuttons.length; i++)
{
	cancelbuttons[i].addEventListener("click", confirmCancel, false);
}

// Set up all drop-down title buttons
var dropdown_titles = document.getElementsByClassName('rcmm-dropdown-title');

for(var i = 0; i < dropdown_titles.length; i++)
{
	dropdown_titles[i].addEventListener("click", function(){dropdownReveal(this)});
}

// Show the table directly after the clicked dropdown-title
// removes the reveal listener and adds the hide listener
function dropdownReveal(selected)
{
	// Display the table
	selected.parentElement.getElementsByTagName("table")[0].style.display = "table";
	
	// Remove reveal listener
	selected.removeEventListener("click", function(){dropdownReveal(this)});
	
	// Add hide event listener
	selected.addEventListener("click", function(){dropdownHide(this)});
	
	// Add the class for the down arrow
	selected.classList.add("rcmm-dropdown-title-active");
}

// Hide the table directly after the clicked dropdown-title
// and add back the reveal event listener
function dropdownHide(selected)
{
	// Hide the table
	selected.parentElement.getElementsByTagName("table")[0].style.display = "none";
	
	// Add the event listener for reveal back
	selected.addEventListener("click", function(){dropdownReveal(this)});
	
	// Remove hide listener
	selected.removeEventListener("click", function(){dropdownHide(this)});
	
	// Remove down arrow class
	selected.classList.remove("rcmm-dropdown-title-active");
}

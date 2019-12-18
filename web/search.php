<?php 
	// Make it easier to copy/paste code or make a new file
	$id = "search";
	require "layout/layout.php"; 
?>

<?php 
	// This is for all the select elements
	// Made easy, and manageble in one single place
	$arrays = [
			"tribe" => $select_Search_tribes, 
			"gender" => $select_Search_gender, 
			"locations" => $select_Search_locations,
			"specials" => $select_Search_specials,
	];
	
	foreach($arrays as $name=>$array){
		// Making the enumeration and the naming in these strings
		${$name."_select_values"} = "0";
		${$name."_select_names"} = "'".$dict_Search["all"]."'";
		$loopIdx = 0;
		
		foreach($array as $key=>$value) {
			$loopIdx = $loopIdx + 1;
			${$name."_select_values"} = ${$name."_select_values"}.", ".$loopIdx;
			${$name."_select_names"} = ${$name."_select_names"}.", '".$value."'";
		}
	}
	
function search_Helper_layout() {
	global $dict_NavBar;
	global $dict_Search;

	// Contents of the div between the footer and the navigation bar
	PrettyPrint('<div class="clearfix"> ', 1);
	PrettyPrint('');
	// The bar on the left side, that contains the various options for searching
	// Options will be added or removed, depending on the type of search chosen (books, peoples, etc)
	PrettyPrint('	<div class="contents_left" id="search_bar">	 ');
	PrettyPrint('		<h1>'.$dict_Search["Options"].'</h1> ');
	PrettyPrint('');
	PrettyPrint('		<form method="get" action="search.php"> ');
	PrettyPrint('			<select id="table" name="table" disabled="true" onchange="selectTableOptions(this)"> ');
	PrettyPrint('				<option id="default" value="" disabled="true" selected="true">'.$dict_Search["busy"].'</option> ');
	PrettyPrint('				<option value="peoples">'.$dict_NavBar["Peoples"].'</option> ');
	PrettyPrint('				<option value="locations">'.$dict_NavBar["Locations"].'</option> ');
	PrettyPrint('				<option value="specials">'.$dict_NavBar["Specials"].'</option> ');
	PrettyPrint('				<option value="books">'.$dict_NavBar["Books"].'</option> ');
	PrettyPrint('				<option value="events">'.$dict_NavBar["Events"].'</option> ');
	PrettyPrint('				<option value="all">'.$dict_Search["All"].'</option> ');
	PrettyPrint('			</select> ');
	PrettyPrint('		</form> ');
	PrettyPrint('	</div> ');
	PrettyPrint('');
	
	// This is where the items will be displayed
	PrettyPrint('	<div class="contents_right" id="search_results"> ');
    
	// When no search is performed yet
	PrettyPrint('		'.$dict_Search["default_search"]);
	PrettyPrint('');
	
	if (isset($_GET['submitSearch'])) {
		// Generating search results
		$options = "";

		if (isset($_GET['MeaningName']) and ($_GET["MeaningName"] != "")) {
			$options = $options." AND MeaningName LIKE '%".$_GET["MeaningName"]."%'";
		}

		if (isset($_GET['NameChanges']) and ($_GET["NameChanges"] != "")) {
			$multoptions = explode(";", $_GET["NameChanges"]);
			foreach ($multoptions as $value) {
				$options = $options." AND NameChanges LIKE '%".$value."%'";
			}
		}

		if (isset($_GET['Father']) and ($_GET["Father"] != "")) {
			$options = $options." AND Father LIKE '%".$_GET["Father"]."%'";
		}

		if (isset($_GET['Mother']) and ($_GET["Mother"] != "")) {
			$options = $options." AND Mother LIKE '%".$_GET["Mother"]."%'";
		}

		if (isset($_GET['Gender']) and ($_GET["Gender"] != "")) {
			if ($_GET["Gender"] != 0) {
				$options = $options." AND Gender = '%".$_GET["Gender"]."%'";
			}
		}

		if (isset($_GET['Tribe']) and ($_GET["Tribe"] != "")) {
			if ($_GET["Tribe"] != 0) {
				$options = $options." AND Tribe = '%".$_GET["Tribe"]."%'";
			}
		}

		if (isset($_GET['TypeOfLocation']) and ($_GET["TypeOfLocation"] != "")) {
			if ($_GET["TypeOfLocation"] != 0) {
				$options = $options." AND TypeOfLocation = '%".$_GET["TypeOfLocation"]."%'";
			}
		}

		if (isset($_GET['Founder']) and ($_GET["Founder"] != "")) {
			$options = $options." AND Founder LIKE '%".$_GET["Founder"]."%'";
		}
		
		if (isset($_GET['Destroyer']) and ($_GET["Destroyer"] != "")) {
			$options = $options." AND Destroyer LIKE '%".$_GET["Destroyer"]."%'";
		}
		
		if (isset($_GET['Type']) and ($_GET["Type"] != "")) {
			if ($_GET["Type"] != 0) {
				$options = $options." AND Type = '%".$_GET["Type"]."%'";
			}
		}
		
		if (isset($_GET['Previous']) and ($_GET["Previous"] != "")) {
			$options = $options." AND Previous LIKE '%".$_GET["Previous"]."%'";
		}
		
		if (isset($_GET['Locations']) and ($_GET["Locations"] != "")) {
			$multoptions = explode(";", $_GET["Locations"]);
			foreach ($multoptions as $value) {
				$options = $options." AND Locations LIKE '%".$value."%'";
			}
		}
		
		if (isset($_GET['Peoples']) and ($_GET["Peoples"] != "")) {
			$multoptions = explode(";", $_GET["Peoples"]);
			foreach ($multoptions as $value) {
				$options = $options." AND Peoples LIKE '%".$value."%'";
			}
		}
		
		if (isset($_GET['Specials']) and ($_GET["Specials"] != "")) {
			$multoptions = explode(";", $_GET["Specials"]);
			foreach ($multoptions as $value) {
				$options = $options." AND Specials LIKE '%".$value."%'";
			}
		}
		
		if (isset($_GET['FirstAppearance_book']) and ($_GET["FirstAppearance_book"] != "")) {
			
			if (isset($_GET['FirstAppearance_chap']) and ($_GET["FirstAppearance_chap"] != "")) {
				$options = $options." AND FirstAppearance >= '".sprintf("%02x", $_GET["FirstAppearance_book"]).sprintf("%02x", $_GET["FirstAppearance_chap"])."00'";
			} else {
				$options = $options." AND FirstAppearance >= '".sprintf("%02x", $_GET["FirstAppearance_book"])."0000'";
			}
		}
		
		if (isset($_GET['LastAppearance_book']) and ($_GET["LastAppearance_book"] != "")) {
			if (isset($_GET['LastAppearance_chap']) and ($_GET["LastAppearance_chap"] != "")) {
				$options = $options." AND LastAppearance <= '".sprintf("%02x", $_GET["LastAppearance_book"]).sprintf("%02x", $_GET["LastAppearance_chap"])."00'";
			} else {
				$options = $options." AND LastAppearance <= '".sprintf("%02x", $_GET["LastAppearance_book"])."0000'";
			}
		}
		
		// If all types are chosen, make some shortcuts at the top of the search results
		if ($_GET['table'] == "all") {
			PrettyPrint('		<center> ');
			PrettyPrint('			'.$dict_Search["Show"].
									"<a href='#search_peoples'>".$dict_NavBar["Peoples"]."</a> | ".
									"<a href='#search_locations'>".$dict_NavBar["Locations"]."</a> | ".
									"<a href='#search_specials'>".$dict_NavBar["Specials"]."</a> | ".
									"<a href='#search_books'>".$dict_NavBar["Books"]."</a> | ".
									"<a href='#search_events'>".$dict_NavBar["Events"]."</a>");
			PrettyPrint('		</center> ');
			PrettyPrint('');
		}
	
		if (($_GET['table'] == "peoples") ||
			($_GET['table'] == "all")) {
			PrettyPrint('		<div id="search_peoples"> ');
			// Search Peoples database
			SearchItems($_GET['search'], "peoples", $options);
			PrettyPrint('		</div> ');
		}

		if (($_GET['table'] == "locations") ||
			($_GET['table'] == "all")) {
			PrettyPrint('		<div id="search_locations"> ');
			// Search Locations database
			SearchItems($_GET['search'], "locations", $options);
			PrettyPrint('		</div> ');
		}

		if (($_GET['table'] == "specials") ||
			($_GET['table'] == "all")) {
			PrettyPrint('		<div id="search_specials"> ');
			// Search Specials database
			SearchItems($_GET['search'], "specials", $options);
			PrettyPrint('		</div> ');
		}

		if (($_GET['table'] == "books") ||
			($_GET['table'] == "all")) {
			PrettyPrint('		<div id="search_books"> ');
			// Search Books database
			SearchItems($_GET['search'], "books", $options);
			PrettyPrint('		</div> ');
		}

		if (($_GET['table'] == "events") ||
			($_GET['table'] == "all")) {
			PrettyPrint('		<div id="search_events"> ');
			// Search Events database
			SearchItems($_GET['search'], "events", $options);
			PrettyPrint('		</div> ');
		}
	}
	PrettyPrint('	</div> ');
	PrettyPrint('</div> ');
}

// The function that executes the search, and returns the results
function SearchItems($text, $table, $options) {
	global $dict_Search;
	global $dict_NavBar;
	global $conn;
	
	// The the desired parameters dictionary, depending on the type of search
	$dictName = "dict_".ucfirst($table)."Params";
	global $$dictName;
	$dict = $$dictName;
	
	// Remove any newlines or characters
	$text = $conn->real_escape_string($text);
	
	// Search the database with the chosen string and options
	$sql = "SELECT * FROM ".$table." WHERE name LIKE '%".$text."%'".$options;
	$result = $conn->query($sql);
	
	if (!$result) {
		// If there are no results, show a message
		PrettyPrint('			'.$dict_Search["NoResults"]."<br />");
	}
	else {
		// If there are results..
		$num_res = $result->num_rows;
		
		// Type of search performed
		// Show the amount of results found. If it is more than one result, use plural forms
		PrettyPrint("			<a name='".$table."'><h1>".$dict_NavBar[ucfirst($table)].":</h1><br /></a>");
		if ($num_res == 1) {
			PrettyPrint('			'.$num_res.$dict_Search['Result']."\"".$text."\":<br />");
		} else {
			PrettyPrint('			'.$num_res.$dict_Search['Results']."\"".$text."\":<br />");
		}
		
		// If there are results, draw a table with all the results found
		if ($num_res > 0) {
			PrettyPrint("			<table>");
			if (in_array($table, Array("peoples", "locations", "specials", "events"))) {
				PrettyPrint("				<tr>");
				PrettyPrint("					<td>");
				PrettyPrint('						'.$dict['Name']);
				PrettyPrint("					</td>");
				PrettyPrint("					<td>");
				PrettyPrint('						'.$dict['FirstAppearance']);
				PrettyPrint("					</td>");
				PrettyPrint("					<td>");
				PrettyPrint("						".$dict['LastAppearance']);
				PrettyPrint("					</td>");
				PrettyPrint("				</tr>");
			} else {
				PrettyPrint("				<tr>");
				PrettyPrint("					<td>");
				PrettyPrint("						".$dict['Name']);
				PrettyPrint("					</td>");
				PrettyPrint("				</tr>");
			}
			
			while ($item = $result->fetch_array()) {
				PrettyPrint("				<tr>");
				PrettyPrint("					<td>");
				PrettyPrint("						<a href='".$table.".php".AddParams(-1, $item['ID'], -2)."'>".$item['Name']."</a>");
				PrettyPrint("					</td>");
				
				if (in_array($table, Array("peoples", "locations", "specials", "events"))) {
					PrettyPrint("					<td>");
					PrettyPrint("						".convertBibleVerseText($item['FirstAppearance']));
					PrettyPrint("					</td>");
					PrettyPrint("					<td>");
					PrettyPrint("						".convertBibleVerseText($item['LastAppearance']));
					PrettyPrint("					</td>");
				}
				
				PrettyPrint("				</tr>");
			}
			PrettyPrint("			</table>");
		}
	}
}

?>

<script>

// The function that is executed, when the select box for the type of item has changed values
function selectTableOptions(sel) {
	// Get the selected values
	var value = sel.value;
	var form = sel.parentNode;
	
	// Remove all existing options and start fresh
	resetForm(form);
	
	Input = addInput("text", "search", "<?php echo $dict_PeoplesParams["Name"]; ?>");
	<?php if (isset($_GET['search'])) { ?>
		// Pre-fill the name, if the current table is the same as the one of the previous search
		// And of course when the name is also set
		if (value == "<?php echo $_GET['table'];?>") {
			Input.value = "<?php echo $_GET['search'];?>";
		}
	<?php } ?>
	form.appendChild(Input);
	
	switch(value) {
		case "peoples":
		// Meaning Name
		Input = addInput("text", "MeaningName", "<?php echo $dict_PeoplesParams["MeaningName"]; ?>");
		<?php if (isset($_GET['MeaningName']) and ($_GET['table'] == "peoples")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['MeaningName'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// Name changes
		Input = addInput("text", "NameChanges", "<?php echo $dict_PeoplesParams["NameChanges"]; ?>");
		<?php if (isset($_GET['NameChanges']) and ($_GET['table'] == "peoples")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['NameChanges'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// Name Father
		Input = addInput("text", "Father", "<?php echo $dict_PeoplesParams["Father"]; ?>");
		<?php if (isset($_GET['Father']) and ($_GET['table'] == "peoples")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['Father'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// Name Mother
		Input = addInput("text", "Mother", "<?php echo $dict_PeoplesParams["Mother"]; ?>");
		<?php if (isset($_GET['Mother']) and ($_GET['table'] == "peoples")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['Mother'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// Gender
		Input = addSelect("Gender", 
							[<?php echo $gender_select_values; ?>], 
							[<?php echo $gender_select_names; ?>], 
							"<?php echo $dict_PeoplesParams["Gender"]; ?>");
		<?php if (isset($_GET['Gender']) and ($_GET['table'] == "peoples")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['Gender'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// Tribe
		Input = addSelect("Tribe", 
							[<?php echo $tribe_select_values; ?>], 
							[<?php echo $tribe_select_names; ?>], 
							"<?php echo $dict_PeoplesParams["Tribe"]; ?>");
		<?php if (isset($_GET['Tribe']) and ($_GET['table'] == "peoples")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['Tribe'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// First appearance
		Input = addAppearance("FirstAppearance", "<?php echo $dict_PeoplesParams["FirstAppearance"]; ?>");
		form.appendChild(Input);
		<?php if (isset($_GET['FirstAppearance_book']) and ($_GET['table'] == "peoples")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("FirstAppearance_book");
			SelectElement.value = "<?php echo $_GET['FirstAppearance_book'];?>";
			SelectElement.onchange();
		<?php } 
		if (isset($_GET['FirstAppearance_chap']) and ($_GET['table'] == "peoples")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("FirstAppearance_chap");
			SelectElement.value = "<?php echo $_GET['FirstAppearance_chap'];?>";
		<?php } ?>
		
		// Last appearance
		Input = addAppearance("LastAppearance", "<?php echo $dict_PeoplesParams["LastAppearance"]; ?>");
		form.appendChild(Input);
		<?php if (isset($_GET['LastAppearance_book']) and ($_GET['table'] == "peoples")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("LastAppearance_book");
			SelectElement.value = "<?php echo $_GET['LastAppearance_book'];?>";
			SelectElement.onchange();
		<?php } 
		if (isset($_GET['LastAppearance_chap']) and ($_GET['table'] == "peoples")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("LastAppearance_chap");
			SelectElement.value = "<?php echo $_GET['LastAppearance_chap'];?>";
		<?php } ?>
		break;
		
		case "locations":
		// Meaning name
		Input = addInput("text", "MeaningName", "<?php echo $dict_LocationsParams["MeaningName"]; ?>");
		<?php if (isset($_GET['MeaningName']) and ($_GET['table'] == "locations")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['MeaningName'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// Name changes
		Input = addInput("text", "NameChanges", "<?php echo $dict_LocationsParams["NameChanges"]; ?>");
		<?php if (isset($_GET['NameChanges']) and ($_GET['table'] == "locations")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['NameChanges'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// Type of Location
		Input = addSelect("TypeOfLocation", 
							[<?php echo $locations_select_values; ?>], 
							[<?php echo $locations_select_names; ?>], 
							"<?php echo $dict_LocationsParams["TypeOfLocation"]; ?>");
		<?php if (isset($_GET['TypeOfLocation']) and ($_GET['table'] == "locations")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['TypeOfLocation'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// Founder
		Input = addInput("text", "Founder", "<?php echo $dict_LocationsParams["Founder"]; ?>");
		<?php if (isset($_GET['Founder']) and ($_GET['table'] == "locations")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['Founder'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// Destroyer
		Input = addInput("text", "Destroyer", "<?php echo $dict_LocationsParams["Destroyer"]; ?>");
		<?php if (isset($_GET['Destroyer']) and ($_GET['table'] == "locations")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['Destroyer'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// First appearance
		Input = addAppearance("FirstAppearance", "<?php echo $dict_LocationsParams["FirstAppearance"]; ?>");
		form.appendChild(Input);
		<?php if (isset($_GET['FirstAppearance_book']) and ($_GET['table'] == "locations")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("FirstAppearance_book");
			SelectElement.value = "<?php echo $_GET['FirstAppearance_book'];?>";
			SelectElement.onchange();
		<?php } 
		if (isset($_GET['FirstAppearance_chap']) and ($_GET['table'] == "locations")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("FirstAppearance_chap");
			SelectElement.value = "<?php echo $_GET['FirstAppearance_chap'];?>";
		<?php } ?>
		
		// Last appearance
		Input = addAppearance("LastAppearance", "<?php echo $dict_LocationsParams["LastAppearance"]; ?>");
		form.appendChild(Input);
		<?php if (isset($_GET['LastAppearance_book']) and ($_GET['table'] == "locations")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("LastAppearance_book");
			SelectElement.value = "<?php echo $_GET['LastAppearance_book'];?>";
			SelectElement.onchange();
		<?php } 
		if (isset($_GET['LastAppearance_chap']) and ($_GET['table'] == "locations")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("LastAppearance_chap");
			SelectElement.value = "<?php echo $_GET['LastAppearance_chap'];?>";
		<?php } ?>
		break;
		
		case "specials":
		// Meaning Name
		Input = addInput("text", "MeaningName", "<?php echo $dict_SpecialsParams["MeaningName"]; ?>");
		<?php if (isset($_GET['MeaningName']) and ($_GET['table'] == "specials")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['MeaningName'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// Type of Special
		Input = addSelect("Type", 
							[<?php echo $specials_select_values; ?>], 
							[<?php echo $specials_select_names; ?>], 
							"<?php echo $dict_SpecialsParams["Type"]; ?>");
		<?php if (isset($_GET['Type']) and ($_GET['table'] == "specials")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['Type'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// First appearance
		Input = addAppearance("FirstAppearance", "<?php echo $dict_SpecialsParams["FirstAppearance"]; ?>");
		form.appendChild(Input);
		<?php if (isset($_GET['FirstAppearance_book']) and ($_GET['table'] == "specials")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("FirstAppearance_book");
			SelectElement.value = "<?php echo $_GET['FirstAppearance_book'];?>";
			SelectElement.onchange();
		<?php } 
		if (isset($_GET['FirstAppearance_chap']) and ($_GET['table'] == "specials")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("FirstAppearance_chap");
			SelectElement.value = "<?php echo $_GET['FirstAppearance_chap'];?>";
		<?php } ?>
		
		// Last appearance
		Input = addAppearance("LastAppearance", "<?php echo $dict_SpecialsParams["LastAppearance"]; ?>");
		form.appendChild(Input);
		<?php if (isset($_GET['LastAppearance_book']) and ($_GET['table'] == "specials")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("LastAppearance_book");
			SelectElement.value = "<?php echo $_GET['LastAppearance_book'];?>";
			SelectElement.onchange();
		<?php } 
		if (isset($_GET['LastAppearance_chap']) and ($_GET['table'] == "specials")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("LastAppearance_chap");
			SelectElement.value = "<?php echo $_GET['LastAppearance_chap'];?>";
		<?php } ?>
		break;
		
		case "events":
		// Previous
		Input = addInput("text", "Previous", "<?php echo $dict_EventsParams["Previous"]; ?>");
		<?php if (isset($_GET['Previous']) and ($_GET['table'] == "events")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['Previous'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// Location
		Input = addInput("text", "Locations", "<?php echo $dict_EventsParams["Locations"]; ?>");
		<?php if (isset($_GET['Locations']) and ($_GET['table'] == "events")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['Locations'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// People
		Input = addInput("text", "Peoples", "<?php echo $dict_EventsParams["Peoples"]; ?>");
		<?php if (isset($_GET['Peoples']) and ($_GET['table'] == "events")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['Peoples'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// Special
		Input = addInput("text", "Specials", "<?php echo $dict_EventsParams["Specials"]; ?>");
		<?php if (isset($_GET['Specials']) and ($_GET['table'] == "events")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			Input.value = "<?php echo $_GET['Specials'];?>";
		<?php } ?>
		form.appendChild(Input);
		
		// First appearance
		Input = addAppearance("FirstAppearance", "<?php echo $dict_EventsParams["FirstAppearance"]; ?>");
		form.appendChild(Input);
		<?php if (isset($_GET['FirstAppearance_book']) and ($_GET['table'] == "events")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("FirstAppearance_book");
			SelectElement.value = "<?php echo $_GET['FirstAppearance_book'];?>";
			SelectElement.onchange();
		<?php } 
		if (isset($_GET['FirstAppearance_chap']) and ($_GET['table'] == "events")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("FirstAppearance_chap");
			SelectElement.value = "<?php echo $_GET['FirstAppearance_chap'];?>";
		<?php } ?>
		
		// Last appearance
		Input = addAppearance("LastAppearance", "<?php echo $dict_EventsParams["LastAppearance"]; ?>");
		form.appendChild(Input);
		<?php if (isset($_GET['LastAppearance_book']) and ($_GET['table'] == "events")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("LastAppearance_book");
			SelectElement.value = "<?php echo $_GET['LastAppearance_book'];?>";
			SelectElement.onchange();
		<?php } 
		if (isset($_GET['LastAppearance_chap']) and ($_GET['table'] == "events")) { ?>
			// Pre-fill this property when it is set,
			// and when the table is the same of for the previous search
			SelectElement = document.getElementById("LastAppearance_chap");
			SelectElement.value = "<?php echo $_GET['LastAppearance_chap'];?>";
		<?php } ?>
		break;
	}
	
	// The button to start the actual search
	var SubmitButton = document.createElement("input");
	SubmitButton.id = "submit";
	SubmitButton.name = "submitSearch";
	SubmitButton.type = "submit";
	SubmitButton.className = "added";
	SubmitButton.value = "<?php echo $dict_Search["Search"]; ?>";
	form.appendChild(SubmitButton);
	
	<?php if (isset($_GET['submitSearch'])) { ?>
		// Function to remove all selected search options
		var RemoveOptions = document.createElement("a");
		RemoveOptions.innerHTML = "<?php echo $dict_Search["Remove"];?>";
		RemoveOptions.href = "search.php";
		RemoveOptions.className = "added";
		// No border please..
		RemoveOptions.style.borderWidth = "0px";
		form.appendChild(RemoveOptions);
	<?php } ?>
}

// This function is executed when the book for first/last appearance has changed
// It updates the dropdown with the number of chapters
function selectBookOptions(sel) {
	
	// Which dropdown are we currently accessing?
	var name = sel.app;
	
	// Which book has been chosen?
	var Option = sel.options[sel.selectedIndex];
	
	// How many chapters does this book have?
	var Chapters = Option.chapters;
	
	// Remove the old chapters of the dropdown menu
	ChapDropDown = document.getElementById(name + "_chap");
	numChapsPrev = ChapDropDown.childElementCount
	
	for (var i = 1; i < numChapsPrev; i++) {
		ChapDropDown.removeChild(ChapDropDown.lastChild);
	}
	
	// Creating the dropdown list
	for (var i = 0; i < Chapters; i++) {
		var option = document.createElement("option");
		option.value = i + 1;
		option.innerHTML = i + 1;
		
		ChapDropDown.appendChild(option);
	}
}

// Function to speed up the process of adding Form elements
function addInput(type, name, string, required) {
	if (required === undefined) {
		required = false;
	}
	var element = document.createElement("input");
	element.type = type;
	element.name = name;
	element.placeholder = string;
	element.className = "added";
	element.required = required;	
	return element;
}

// Function to speed up the process of adding Form elements
function addSelect(name, values, strings, placeholder) {
	var element = document.createElement("select");
	element.name = name;
	element.className = "added";
	
	var option = document.createElement("option");
	option.value = "";
	option.disabled = "true";
	option.selected = "true";
	option.innerHTML = "<?php echo $dict_Settings["default"]; ?> voor " + placeholder;
	element.appendChild(option);
	
	for (var i = 0; i < values.length; i++) {
		var option = document.createElement("option");
		option.value = values[i];
		option.innerHTML = strings[i];
		
		element.appendChild(option);
	}
	
	return element;
}

function addAppearance(name, placeholder) {
	// The entire div that contains the drop downs and text bar
	// These are used to create the number that corresponds to
	// a bible verse
	var appearance = document.createElement("div");
	appearance.className = "added_app_div";
	
	// The title, to let the user know whether
	// it's the first or last appearance we are
	// filling in
	var elementTitle = document.createElement("p");
	elementTitle.innerHTML = placeholder;
	elementTitle.className = "added_app_text";
	appearance.appendChild(elementTitle);
	
	// The dropdown list containing all the books
	var elementBook = document.createElement("select");
	elementBook.name = name + "_book";
	elementBook.id = name + "_book";
	elementBook.app = name;
	elementBook.setAttribute("onchange", "selectBookOptions(this)");
	elementBook.className = "added_app_select";
	
	// Bible book that can be chosen
	var option = document.createElement("option");
	option.value = "";
	option.disabled = "true";
	option.selected = "true";
	option.innerHTML = "<?php echo $dict_Search["bible_book"]; ?>";
	elementBook.appendChild(option);
	
	// List of options from the database, to get names in correct language.
	<?php 
		$listLength = GetNumberOfItems("books"); 
		
		// The different numbers
		echo "var values = [";
		for ($id = 0; $id < $listLength; $id++) {
			echo $id.", ";
		}
		echo "];\r\n";
		
		// The different names
		echo "var strings = [";
		for ($id = 0; $id < $listLength; $id++) {
			$item = GetItemInfo("books", $id);
			echo "'".$item['Name']."', ";
		}
		echo "];\r\n";
		
		// The different amount of chapters
		echo "var chapters = [";
		for ($id = 0; $id < $listLength; $id++) {
			$item = GetItemInfo("books", $id);
			echo $item['NumOfChapters'].", ";
		}
		echo "];\r\n";
	
	?>
	
	// Creating the dropdown list
	for (var i = 0; i < values.length; i++) {
		var option = document.createElement("option");
		option.value = values[i];
		option.chapters = chapters[i];
		option.innerHTML = strings[i];
		
		elementBook.appendChild(option);
	}
	appearance.appendChild(elementBook);
	
	
	// The dropdown list containing all the chapters of a book
	var elementChap = document.createElement("select");
	elementChap.name = name + "_chap";
	elementChap.id = name + "_chap";
	elementChap.className = "added_app_select";
	
	// Chapter that can be chosen (currently no available options
	// Will be filled in when a book is chosen
	var option = document.createElement("option");
	option.value = "";
	option.disabled = "true";
	option.selected = "true";
	option.innerHTML = "<?php echo $dict_Search["bible_chap"]; ?>";
	elementChap.appendChild(option);
	
	// The div for the dropdown menus
	appearance.appendChild(elementChap);
	
	return appearance;
}

// When a different type of search is chosen, remove all the elements
// This way we can start clean with filling up the form with elements 
// needed for the new search type
function resetForm(form) {
	var elements = document.getElementsByClassName("added");
	var length = elements.length;
	
	for (var i = 0; i < length; i++) {
		var addedElement = elements[0];
		form.removeChild(addedElement);
	}
	
	var elements = document.getElementsByClassName("added_app_div");
	var length = elements.length;
	
	for (var i = 0; i < length; i++) {
		var addedElement = elements[0];
		form.removeChild(addedElement);
	}
}

// Keep the dropdown menu for searching locked, until the previous search is completely done
window.onload = function () {
	defText = document.getElementById("default");
	
	// Change the title text of the select element
	defText.innerHTML = "<?php echo $dict_Search["Category"]; ?>";
	
	// Remove the lock on the category dropdown
	SelectElement = document.getElementById("table");
	SelectElement.disabled = false;
	
	// Set back all the data that was entered for searching
	<?php if (isset($_GET['submitSearch'])) { ?>
		var SelectElement = document.getElementById("table");
		SelectElement.value = "<?php echo $_GET['table']; ?>";
		SelectElement.onchange();
	<?php } ?>
	
}

</script>
<?php 
	require "layout/header.php";
	require "tools/databaseHelper.php";
?>

<?php if (isset($_GET['submitSearch']) || isset($_GET['submitSearch2'])) { ?>
	<div class="search" id="search_options">		
		<h1><?php echo $Search["Options"]; ?></h1>
		
		<form method="get" action="search.php">
			<select name="table" onchange="selectTableOptions(this)">
				<option value="" disabled="true" selected="true"><?php echo $Settings["default"]; ?></option>
				<option value="peoples"><?php echo $NavBar["Peoples"]; ?></option>
				<option value="locations"><?php echo $NavBar["Locations"]; ?></option>
				<option value="specials"><?php echo $NavBar["Specials"]; ?></option>
				<option value="books"><?php echo $NavBar["Books"]; ?></option>
				<option value="events"><?php echo $NavBar["Events"]; ?></option>
			</select>
		
			<input id="submit2" name="submitSearch2" type="submit" disabled="true" value="<?php echo $Search["Search2"]; ?>"/>
			<input type="hidden" name="search" value="<?php echo $_GET['search']; ?>"/>
		</form>
	</div>
<?php } ?>

<div class="search" id="search_results">
	<?php if (isset($_GET['submitSearch'])) { ?>
		<center>
			<?php echo $Search["Show"]."<a href='#peoples'>".$NavBar["Peoples"]."</a> | <a href='#locations'>".$NavBar["Locations"]."</a> | <a href='#specials'>".$NavBar["Specials"]."</a> | <a href='#books'>".$NavBar["Books"]."</a> | <a href='#events'>".$NavBar["Events"]."</a>";?>
		</center>
	
		<div id="peoples">
			<?php // Search Peoples database
			SearchItems($_GET['search'], "peoples", ""); ?>
		</div>
		
		<div id="locations">
			<?php // Search Locations
			SearchItems($_GET['search'], "locations", ""); ?>
		</div>
		
		<div id="specials">
			<?php // Search Specials
			SearchItems($_GET['search'], "specials", ""); ?>
		</div>
		
		<div id="books">
			<?php // Search Books
			SearchItems($_GET['search'], "books", ""); ?>
		</div>
		
		<div id="events">
			<?php // Search Events
			SearchItems($_GET['search'], "events", ""); ?>
		</div>
	<?php } else if (isset($_GET['submitSearch2'])) {
		$options = "";
		
		if (isset($_GET['MeaningName']) and ($_GET["MeaningName"] != "")) {
			$options = " AND meaningname LIKE '%".$_GET["MeaningName"]."%'";
		}
		
		// TODO:
		// Name changes
		// Name Father
		// Name Mother
		// Gender
		// Tribe
		// First appearance
		// Last appearance
		// Type of Location
		// Founder
		// Destroyer
		// Type (of Special)
		// Previous
		// Locations
		// Peoples
		// Specials
		
		SearchItems($_GET['search'], $_GET['table'], $options);
	} ?>
</div>

<?php require "layout/footer.php" ?>

<script>
function selectTableOptions(sel) {
	var value = sel.value;
	var form = sel.parentNode;
	
	resetForm(form);
	
	form.appendChild(document.createElement("br"));
	Input = addInput("text", "search", "<?php echo $PeoplesParams["Name"]; ?>");
	form.appendChild(Input);
	
	switch(value) {
		case "peoples":
		// Meaning Name
		form.appendChild(document.createElement("br"));
		Input = addInput("text", "MeaningName", "<?php echo $PeoplesParams["MeaningName"]; ?>");
		form.appendChild(Input);
		
		// Name changes
		form.appendChild(document.createElement("br"));
		Input = addInput("text", "NameChanges", "<?php echo $PeoplesParams["NameChanges"]; ?>");
		form.appendChild(Input);
		
		// Name Father
		form.appendChild(document.createElement("br"));
		Input = addInput("text", "Father", "<?php echo $PeoplesParams["Father"]; ?>");
		form.appendChild(Input);
		
		// Name Mother
		form.appendChild(document.createElement("br"));
		Input = addInput("text", "Mother", "<?php echo $PeoplesParams["Mother"]; ?>");
		form.appendChild(Input);
		
		// Gender
		form.appendChild(document.createElement("br"));
		Input = addSelect("Gender", [0], ["To be done"]);
		form.appendChild(Input);
		
		// Tribe
		form.appendChild(document.createElement("br"));
		Input = addSelect("Tribe", [0], ["To be done"]);
		form.appendChild(Input);
		
		// TODO
		// // First appearance
		// form.appendChild(document.createElement("br"));
		// Input = addInput("text", "MeaningName", "<?php echo $PeoplesParams["MeaningName"]; ?>");
		// form.appendChild(Input);
		
		// // Last appearance
		// form.appendChild(document.createElement("br"));
		// Input = addInput("text", "MeaningName", "<?php echo $PeoplesParams["MeaningName"]; ?>");
		// form.appendChild(Input);
		break;
		
		case "locations":
		// Meaning name
		form.appendChild(document.createElement("br"));
		Input = addInput("text", "MeaningName", "<?php echo $LocationsParams["MeaningName"]; ?>");
		form.appendChild(Input);
		
		// Name changes
		form.appendChild(document.createElement("br"));
		Input = addInput("text", "NameChanges", "<?php echo $LocationsParams["NameChanges"]; ?>");
		form.appendChild(Input);
		
		// Type of Location
		form.appendChild(document.createElement("br"));
		Input = addSelect("TypeOfLocation", [0], ["To be done"]);
		form.appendChild(Input);
		
		// Founder
		form.appendChild(document.createElement("br"));
		Input = addInput("text", "Founder", "<?php echo $LocationsParams["Founder"]; ?>");
		form.appendChild(Input);
		
		// Destroyer
		form.appendChild(document.createElement("br"));
		Input = addInput("text", "Destroyer", "<?php echo $LocationsParams["Destroyer"]; ?>");
		form.appendChild(Input);
		
		// TODO:
		// // Meaning name
		// form.appendChild(document.createElement("br"));
		// Input = addInput("text", "MeaningName", "<?php echo $LocationsParams["MeaningName"]; ?>");
		// form.appendChild(Input);
		
		// // Meaning name
		// form.appendChild(document.createElement("br"));
		// Input = addInput("text", "MeaningName", "<?php echo $LocationsParams["MeaningName"]; ?>");
		// form.appendChild(Input);
		
		break;
		
		case "specials":
		// Meaning Name
		form.appendChild(document.createElement("br"));
		Input = addInput("text", "MeaningName", "<?php echo $SpecialsParams["MeaningName"]; ?>");
		form.appendChild(Input);
		
		// Type op Special
		form.appendChild(document.createElement("br"));
		Input = addSelect("Type", [0], ["To be done"]);
		form.appendChild(Input);
		
		// TODO
		// // First appearance
		// form.appendChild(document.createElement("br"));
		// Input = addInput("text", "MeaningName", "<?php echo $SpecialsParams["MeaningName"]; ?>");
		// form.appendChild(Input);
		
		// // Last appearance
		// form.appendChild(document.createElement("br"));
		// Input = addInput("text", "MeaningName", "<?php echo $SpecialsParams["MeaningName"]; ?>");
		// form.appendChild(Input);
		break;
		
		case "events":
		
		// Previous
		form.appendChild(document.createElement("br"));
		Input = addInput("text", "Previous", "<?php echo $EventsParams["Previous"]; ?>");
		form.appendChild(Input);
		
		// Location
		form.appendChild(document.createElement("br"));
		Input = addInput("text", "Locations", "<?php echo $EventsParams["Locations"]; ?>");
		form.appendChild(Input);
		
		// People
		form.appendChild(document.createElement("br"));
		Input = addInput("text", "Peoples", "<?php echo $EventsParams["Peoples"]; ?>");
		form.appendChild(Input);
		
		// Special
		form.appendChild(document.createElement("br"));
		Input = addInput("text", "Specials", "<?php echo $EventsParams["Specials"]; ?>");
		form.appendChild(Input);
		break;
	}
	
	SubmitButton = document.getElementById("submit2");
	SubmitButton.disabled = false;
}

function addInput(type, name, string) {
	var element = document.createElement("input");
	element.type = type;
	element.name = name;
	element.placeholder = string;
	element.className = "added";
	
	if (name == "search") {
		element.value = "<?php echo $_GET["search"]; ?>";
	}
	
	return element;
}

function addSelect(name, values, strings) {
	var element = document.createElement("select");
	element.name = name;
	element.className = "added";
	
	var option = document.createElement("option");
	option.value = "";
	option.disabled = "true";
	option.selected = "true";
	option.innerHTML = "<?php echo $Settings["default"]; ?>";
	element.appendChild(option);
	
	for (var i = 0; i < values.length; i++) {
		var option = document.createElement("option");
		option.value = values[i];
		option.innerHTML = strings[i];
		
		element.appendChild(option);
	}
	
	return element;
}

function resetForm(form) {
	var elements = document.getElementsByClassName("added");
	var length = elements.length;
	
	for (var i = 0; i < length; i++) {
		var addedElement = elements[0];
		form.removeChild(addedElement);
	}
}

</script>
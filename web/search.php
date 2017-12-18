<?php 
	$id = "search";
	require "layout/header.php";
	require "tools/databaseHelper.php";
?>

<div class="clearfix">
	<div class="contents_left" id="search_bar">	
		<h1><?php echo $Search["Options"]; ?></h1>
		
		<form method="get" action="search.php">
			<select id="table" name="table" onchange="selectTableOptions(this)">
				<option value="" disabled="true" selected="true"><?php echo $Search["Category"]; ?></option>
				<option value="peoples"><?php echo $NavBar["Peoples"]; ?></option>
				<option value="locations"><?php echo $NavBar["Locations"]; ?></option>
				<option value="specials"><?php echo $NavBar["Specials"]; ?></option>
				<option value="books"><?php echo $NavBar["Books"]; ?></option>
				<option value="events"><?php echo $NavBar["Events"]; ?></option>
				<option value="all"><?php echo $Search["All"]; ?></option>
			</select>
		
			<input id="submit" name="submitSearch" type="submit" disabled="true" value="<?php echo $Search["Search"]; ?>"/>
		</form>
	</div>
	
	<!-- This is where the items will be displayed -->
	<div class="contents_right" id="search_results">

			<!-- When no search is performed yet -->
			<?php echo $Content["default_".$id]; ?>
			<?php if (isset($_GET['submitSearch'])) {
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
					$options = $options." AND Gender = '%".$_GET["Gender"]."%'";
				}
				
				if (isset($_GET['Tribe']) and ($_GET["Tribe"] != "")) {
					$options = $options." AND Tribe = '%".$_GET["Mother"]."%'";
				}
				
				if (isset($_GET['TypeOfLocation']) and ($_GET["TypeOfLocation"] != "")) {
					$options = $options." AND TypeOfLocation = '%".$_GET["TypeOfLocation"]."%'";
				}
				
				if (isset($_GET['Founder']) and ($_GET["Founder"] != "")) {
					$options = $options." AND Founder LIKE '%".$_GET["Founder"]."%'";
				}
				
				if (isset($_GET['Destroyer']) and ($_GET["Destroyer"] != "")) {
					$options = $options." AND Destroyer LIKE '%".$_GET["Destroyer"]."%'";
				}
				
				if (isset($_GET['Type']) and ($_GET["Type"] != "")) {
					$options = $options." AND Type = '%".$_GET["Type"]."%'";
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
				
				if (isset($_GET['First appearance']) and ($_GET["First appearance"] != "")) {
					$options = $options." AND First appearance >= '%".$_GET["First appearance"]."%'";
				}
				
				if (isset($_GET['Last appearance']) and ($_GET["Last appearance"] != "")) {
					$options = $options." AND Last appearance <= '%".$_GET["Last appearance"]."%'";
				}
				
				?>
				
				<?php if ($_GET['table'] == "all") { ?>
					<center>
						<?php echo $Search["Show"]."<a href='#peoples'>".$NavBar["Peoples"]."</a> | <a href='#locations'>".$NavBar["Locations"]."</a> | <a href='#specials'>".$NavBar["Specials"]."</a> | <a href='#books'>".$NavBar["Books"]."</a> | <a href='#events'>".$NavBar["Events"]."</a>";?>
					</center>
				<?php } ?>
			
				<?php if (($_GET['table'] == "peoples") || 
							($_GET['table'] == "all")) { ?>
					<div id="peoples">
						<?php // Search Peoples database
						SearchItems($_GET['search'], "peoples", $options); ?>
					</div>
				<?php } ?>
				
				<?php if (($_GET['table'] == "locations") || 
							($_GET['table'] == "all")) { ?>
					<div id="locations">
						<?php // Search Locations
						SearchItems($_GET['search'], "locations", $options); ?>
					</div>
				<?php } ?>
				
				<?php if (($_GET['table'] == "specials") || 
							($_GET['table'] == "all")) { ?>
					<div id="specials">
						<?php // Search Specials
						SearchItems($_GET['search'], "specials", $options); ?>
					</div>
				<?php } ?>
				
				<?php if (($_GET['table'] == "books") || 
							($_GET['table'] == "all")) { ?>
					<div id="books">
						<?php // Search Books
						SearchItems($_GET['search'], "books", $options); ?>
					</div>
				<?php } ?>
				
				<?php if (($_GET['table'] == "events") || 
							($_GET['table'] == "all")) { ?>
					<div id="events">
						<?php // Search Events
						SearchItems($_GET['search'], "events", $options); ?>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>

<?php require "layout/footer.php" ?>

<script>
function selectTableOptions(sel) {
	var SubmitButton = document.getElementById("submit");
	
	var value = sel.value;
	var form = sel.parentNode;
	
	resetForm(form);
	
	Input = addInput("text", "search", "<?php echo $PeoplesParams["Name"]; ?>", 1);
	form.insertBefore(Input, SubmitButton);
	
	switch(value) {
		case "peoples":
		// Meaning Name
		Input = addInput("text", "MeaningName", "<?php echo $PeoplesParams["MeaningName"]; ?>");
		form.insertBefore(Input, SubmitButton);
		
		// Name changes
		Input = addInput("text", "NameChanges", "<?php echo $PeoplesParams["NameChanges"]; ?>");
		form.insertBefore(Input, SubmitButton);
		
		// Name Father
		Input = addInput("text", "Father", "<?php echo $PeoplesParams["Father"]; ?>");
		form.insertBefore(Input, SubmitButton);
		
		// Name Mother
		Input = addInput("text", "Mother", "<?php echo $PeoplesParams["Mother"]; ?>");
		form.insertBefore(Input, SubmitButton);
		
		// Gender
		Input = addSelect("Gender", [0], ["To be done"]);
		form.insertBefore(Input, SubmitButton);
		
		// Tribe
		Input = addSelect("Tribe", [0], ["To be done"]);
		form.insertBefore(Input, SubmitButton);
		
		// TODO
		// // First appearance
		// Input = addInput("text", "MeaningName", "<?php echo $PeoplesParams["MeaningName"]; ?>");
		// form.insertBefore(Input, SubmitButton);
		
		// // Last appearance
		// Input = addInput("text", "MeaningName", "<?php echo $PeoplesParams["MeaningName"]; ?>");
		// form.insertBefore(Input, SubmitButton);
		break;
		
		case "locations":
		// Meaning name
		Input = addInput("text", "MeaningName", "<?php echo $LocationsParams["MeaningName"]; ?>");
		form.insertBefore(Input, SubmitButton);
		
		// Name changes
		Input = addInput("text", "NameChanges", "<?php echo $LocationsParams["NameChanges"]; ?>");
		form.insertBefore(Input, SubmitButton);
		
		// Type of Location
		Input = addSelect("TypeOfLocation", [0], ["To be done"]);
		form.insertBefore(Input, SubmitButton);
		
		// Founder
		Input = addInput("text", "Founder", "<?php echo $LocationsParams["Founder"]; ?>");
		form.insertBefore(Input, SubmitButton);
		
		// Destroyer
		Input = addInput("text", "Destroyer", "<?php echo $LocationsParams["Destroyer"]; ?>");
		form.insertBefore(Input, SubmitButton);
		
		// TODO:
		// // Meaning name
		// Input = addInput("text", "MeaningName", "<?php echo $LocationsParams["MeaningName"]; ?>");
		// form.insertBefore(Input, SubmitButton);
		
		// // Meaning name
		// Input = addInput("text", "MeaningName", "<?php echo $LocationsParams["MeaningName"]; ?>");
		// form.insertBefore(Input, SubmitButton);
		
		break;
		
		case "specials":
		// Meaning Name
		Input = addInput("text", "MeaningName", "<?php echo $SpecialsParams["MeaningName"]; ?>");
		form.insertBefore(Input, SubmitButton);
		
		// Type op Special
		Input = addSelect("Type", [0], ["To be done"]);
		form.insertBefore(Input, SubmitButton);
		
		// TODO
		// // First appearance
		// Input = addInput("text", "MeaningName", "<?php echo $SpecialsParams["MeaningName"]; ?>");
		// form.insertBefore(Input, SubmitButton);
		
		// // Last appearance
		// Input = addInput("text", "MeaningName", "<?php echo $SpecialsParams["MeaningName"]; ?>");
		// form.insertBefore(Input, SubmitButton);
		break;
		
		case "events":
		
		// Previous
		Input = addInput("text", "Previous", "<?php echo $EventsParams["Previous"]; ?>");
		form.insertBefore(Input, SubmitButton);
		
		// Location
		Input = addInput("text", "Locations", "<?php echo $EventsParams["Locations"]; ?>");
		form.insertBefore(Input, SubmitButton);
		
		// People
		Input = addInput("text", "Peoples", "<?php echo $EventsParams["Peoples"]; ?>");
		form.insertBefore(Input, SubmitButton);
		
		// Special
		Input = addInput("text", "Specials", "<?php echo $EventsParams["Specials"]; ?>");
		form.insertBefore(Input, SubmitButton);
		break;
	}
	
	SubmitButton = document.getElementById("submit");
	SubmitButton.disabled = false;
}

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
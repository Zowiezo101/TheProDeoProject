<?php 
	$id = "events";
	require "layout/header.php"; 
	require "tools/databaseHelper.php"; 
	require "tools/timelineHelper.php"; 
?>

<div class="clearfix">
	<div class="contents_left">
		<div id="button_bar">
			<button id="button_left" onClick="PrevPage()"><?php echo $Content["prev"]; ?></button>
			<!-- TODO -->
			<button id="button_alp" onClick="SortOnAlphabet()">A-Z</button>
			<button id="button_app" onClick="SortOnAppearance()">Gen-Op</button>
			<button id="button_right" onClick="NextPage()"><?php echo $Content["next"]; ?></button>
		</div>
		
		<div id="event_bar">
			<?php GetListOfItems("events"); ?>
		</div>
	</div>
	
	<div class="contents_right" id="event_info">
		<div id="default">
			<?php echo $Content["default_event"]; ?>
		</div>
	</div>
</div>

<?php require "layout/footer.php"; ?>

<script>
<?php

if (isset($_GET['id'])) {
?>
	var contentEl = document.getElementById("event_info");
	
	// Remove the default text
	var defaultText = document.getElementById("default");
	contentEl.removeChild(defaultText);
	
	<?php 
		$information = GetItemInfo("events", $_GET['id']); 
	?>
	
	// Create a Table
	var table = document.createElement("table");
	
	<?php		
		foreach ($information as $key => $value)
		{
			?>
			
			<?php if ($value == -1) {
				$value = "";
			}?>
			var value = "<?php echo $value ?>";
			
			<?php if ((strpos($key, "ID") !== false) and ($key != "ID")) { ?>
				if (value != "") {
					<?php if (($key == "LocationIDs") || ($key == "PeopleIDs") || ($key == "SpecialIDs")) {
						switch($key) {
							case "LocationIDs":
							$itemType = "locations";
							break;
							
							case "PeopleIDs":
							$itemType = "peoples";
							break;
							
							default:
							$itemType = "specials";	
						}?>
					
						// Get all the strings to get all the links
						var linkParts = value.split(",");
						
						names = TableData.innerHTML;
						var nameParts = names.split(",");
						
						if (linkParts.length > 1) {
							Table2 = document.createElement("table");
							for (var types = 0; types < linkParts.length; types++) {
								// Table links
								TableLink2 = document.createElement("a");
								TableLink2.innerHTML = nameParts[types];
							
								currentHref = "<?php echo $itemType.'.php' ?>";
								TableLink2.href = updateURLParameter(currentHref, "id", linkParts[types]);
								
								// Table data
								TableData2 = document.createElement("td");
								TableData2.appendChild(TableLink2);
								
								// Table row
								TableRow2 = document.createElement("tr");
								TableRow2.appendChild(TableData2);
								
								// Little table inside of table
								Table2.appendChild(TableRow2);								
							}
							// Update the previous table cell with links to the IDs
							TableData.innerHTML = "";
							TableData.appendChild(Table2);
						} else {
							// Update the previous table cell with a link to the ID
							var TableLink = document.createElement("a");
							TableLink.innerHTML = TableData.innerHTML;
							TableData.innerHTML = "";
							
							currentHref = "<?php echo $itemType.'.php' ?>";
							TableLink.href = updateURLParameter(currentHref, "id", value);
							
							TableData.appendChild(TableLink);
						}
					
					<?php } else { ?>
						// Update the previous table cell with a link to the ID
						var TableLink = document.createElement("a");
						TableLink.innerHTML = TableData.innerHTML;
						TableData.innerHTML = "";
						
						currentHref = window.location.href;
						TableLink.href = updateURLParameter(currentHref, "id", value);
						
						TableData.appendChild(TableLink);
					<?php }	?>
				}
			<?php } else { ?>
				
				<?php if ($key == "Length") { ?>		
					// Convert the cryptic values to a readable string
					var newValue = "";
					
					if (value == "") {
						newValue = "<?php echo $Timeline["unknown"] ?>";
					} else {
						// Convert every time type
						var timeParts = value.split(" ");
						
						for (var types = 0; types < timeParts.length; types++) {
							var currentTypeStr = timeParts[types];
							var currentTypeStrLen = currentTypeStr.length;
							
							var currentStr = currentTypeStr.slice(currentTypeStrLen - 1, currentTypeStrLen);
							var currentLen = parseInt(currentTypeStr.slice(0, currentTypeStrLen - 1));
							
							var currentType = StringToType(currentStr, currentLen);
							
							newValue += currentLen + " " + currentType;
							if (types < (timeParts.length - 1)) {
								newValue += ", ";
							}
						}
					}
					
					value = newValue;
				<?php } ?>
				
				// Add a new table row
				var TableKey = document.createElement("td");
				TableKey.innerHTML = "<?php echo $EventsParams[$key]; ?>";
			
				var TableData = document.createElement("td");
				TableData.innerHTML = value;
			
				// Left is key names
				// right is value names
				var TableRow = document.createElement("tr");
				TableRow.appendChild(TableKey);
				TableRow.appendChild(TableData);
				
				table.appendChild(TableRow);
			<?php }
		}
	?>
	
	contentEl.appendChild(table);
	
	// Show a list of time lines where this person is included in
	var TLText = document.createElement("p");
	TLText.innerHTML = "<?php echo $Content["map_event"]; ?>";
	contentEl.appendChild(TLText);
	
	var TLList = document.createElement("ul");
	
	var TLListIDs = getTimelines(<?php echo $information["ID"]; ?>);
	if (TLListIDs.length > 0) {
		for (var i = 0; i < TLListIDs.length; i++) {
			var TLListLink = document.createElement("a");
			TLListLink.innerHTML = "<?php echo $NavBar["Timeline"]; ?> " + (Number(TLListIDs[i]) + 1);
			TLListLink.href = updateURLParameter("timeline.php", "id", "" + TLListIDs[i] + "," + <?php echo $information["ID"]; ?>);
			
			var TLListItem = document.createElement("li");
			TLListItem.appendChild(TLListLink);
			
			TLList.appendChild(TLListItem);
		}
	} else {
		var TLListItem = document.createElement("li");
		TLListItem.innerHTML = "<?php echo $Search["NoResults"]; ?>";
		TLList.appendChild(TLListItem);
	}
	contentEl.appendChild(TLList);
<?php
}
?>

	window.onload = CheckButtons;
	
	function StringToType(lengthTypeStr, Length) {
				
		switch(lengthTypeStr) {
			case 's':
			lengthType = "<?php echo $Timeline["second"] ?>";
			if (Length != 1) {
				lengthType = "<?php echo $Timeline["seconds"] ?>";
			}
			break;
			
			case 'i':
			lengthType = "<?php echo $Timeline["minute"] ?>";
			if (Length != 1) {
				lengthType = "<?php echo $Timeline["minutes"] ?>";
			}
			break;
			
			case 'h':
			lengthType = "<?php echo $Timeline["hour"] ?>";
			if (Length != 1) {
				lengthType = "<?php echo $Timeline["hours"] ?>";
			}
			break;
			
			case 'd':
			lengthType = "<?php echo $Timeline["day"] ?>";
			if (Length != 1) {
				lengthType = "<?php echo $Timeline["days"] ?>";
			}
			break;
			
			case 'w':
			lengthType = "<?php echo $Timeline["week"] ?>";
			if (Length != 1) {
				lengthType = "<?php echo $Timeline["weeks"] ?>";
			}
			break;
			
			case 'm':
			lengthType = "<?php echo $Timeline["month"] ?>";
			if (Length != 1) {
				lengthType = "<?php echo $Timeline["months"] ?>";
			}
			break;
			
			case 'y':
			lengthType = "<?php echo $Timeline["year"] ?>";
			if (Length != 1) {
				lengthType = "<?php echo $Timeline["years"] ?>";
			}
			break;
			
			case 'D':
			lengthType = "<?php echo $Timeline["decade"] ?>";
			if (Length != 1) {
				lengthType = "<?php echo $Timeline["decades"] ?>";
			}
			break;
			
			case 'C':
			lengthType = "<?php echo $Timeline["century"] ?>";
			if (Length != 1) {
				lengthType = "<?php echo $Timeline["centuries"] ?>";
			}
			break;
			
			case 'M':
			lengthType = "<?php echo $Timeline["millennium"] ?>";
			if (Length != 1) {
				lengthType = "<?php echo $Timeline["millennia"] ?>";
			}
			break;
			
			default:
			lengthType = "<?php echo $Timeline["unknown"] ?>";
			break;
		}
		
		return lengthType;
	}
</script>
<?php 
	$id = "peoples";
	require "layout/header.php"; 
	require "tools/databaseHelper.php"; 
	require "tools/familytreeHelper.php"; 
?>

<?php require "layout/footer.php"; ?>

<script>
<?php

if (isset($_GET['id'])) {
?>
	var contentEl = document.getElementById("people_info");
	
	// Remove the default text
	var defaultText = document.getElementById("default");
	contentEl.removeChild(defaultText);
	
	<?php 
		$information = GetItemInfo("peoples", $_GET['id']); 
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
			
			<?php if ((strpos($key, "ID") !== false) and ($key != "ID")) {
				
				if ($value != "") {
					
					if ($key == "NameChangesIDs") { ?>
						var value = "<?php echo $value ?>";
						
						// Get all the strings to get all the links
						var linkParts = value.split(",");
						
						names = TableData.innerHTML;
						var nameParts = names.split(",");
						
						if (nameParts.length > 1) {
							Table2 = document.createElement("table");
							for (var types = 0; types < nameParts.length; types++) {
								
								// Table data
								TableData2 = document.createElement("td");
								
								if (types < linkParts.length) {
									// Table links
									TableLink2 = document.createElement("a");
									TableLink2.innerHTML = nameParts[types];
								
									currentHref = window.location.href;
									TableLink2.href = updateURLParameter(currentHref, "id", linkParts[types]);
									
									TableData2.appendChild(TableLink2);
								} else {
									TableData2.innerHTML = nameParts[types];
								}
								
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
							
							currentHref = window.location.href;
							TableLink.href = updateURLParameter(currentHref, "id", value);
							
							TableData.appendChild(TableLink);
						}
					<?php } else { ?>
						// Update the previous table cell with a link to the ID
						var TableLink = document.createElement("a");
						TableLink.innerHTML = TableData.innerHTML;
						TableData.innerHTML = "";
						
						<?php if (($key == "PlaceOfBirthID") || ($key == "PlaceOfEndID") || ($key == "PlaceOfLivingID")) { ?>
							TableLink.href = updateURLParameter("locations.php", "id", <?php echo "'".$value."'"; ?>);
						<?php } else { ?>
							TableLink.href = updateURLParameter(window.location.href, "id", <?php echo "'".$value."'"; ?>);
						<?php } ?>
						
						TableData.appendChild(TableLink);
					<?php } 
				}
			} else { ?>
				// Add a new table row
				var TableKey = document.createElement("td");
				TableKey.innerHTML = "<?php echo $PeoplesParams[$key]; ?>";
			
				var TableData = document.createElement("td");
				TableData.innerHTML = "<?php echo $value; ?>";
			
				// Left is key names
				// right is value names
				var TableRow = document.createElement("tr");
				TableRow.appendChild(TableKey);
				TableRow.appendChild(TableData);
				
				table.appendChild(TableRow);
			<?php } ?>
			
			<?php
		}
	?>
	
	contentEl.appendChild(table);
	
	// Show a list of family trees where this person is included in
	var FTText = document.createElement("p");
	FTText.innerHTML = "<?php echo $Content["map_people"]; ?>";
	contentEl.appendChild(FTText);
	
	var FTList = document.createElement("ul");
	
	var FTListIDs = getFamilyTrees(<?php echo $information["ID"]; ?>);
	if (FTListIDs.length > 0) {
		for (var i = 0; i < FTListIDs.length; i++) {
			var FTListLink = document.createElement("a");
			FTListLink.innerHTML = "<?php echo $NavBar["Familytree"]; ?> " + (Number(FTListIDs[i]) + 1);
			FTListLink.href = updateURLParameter("familytree.php", "id", "" + FTListIDs[i] + "," + <?php echo $information["ID"]; ?>);
			
			var FTListItem = document.createElement("li");
			FTListItem.appendChild(FTListLink);
			
			FTList.appendChild(FTListItem);
		}
	} else {
		var FTListItem = document.createElement("li");
		FTListItem.innerHTML = "<?php echo $Search["NoResults"]; ?>";
		FTList.appendChild(FTListItem);
	}
	contentEl.appendChild(FTList);
<?php
}
?>

	window.onload = CheckButtons;
</script>
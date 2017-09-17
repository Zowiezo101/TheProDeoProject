<!DOCTYPE html>
<html>
	<?php 
		require "layout/header.php"; 
		
		$item_type = "locations";
		require "tools/databaseHelper.php"; 
	?>
	
	<div class="clearfix">
		<div class="contents_left">
			<div id="button_bar">
				<button id="button_left" onClick="PrevPage()"><?php echo $Content["prev"]; ?></button>
				<button id="button_right" onClick="NextPage()"><?php echo $Content["next"]; ?></button>
			</div>
			
			<div id="location_bar">
				<?php GetListOfItems("locations"); ?>
			</div>
		</div>
		
		<div class="contents_right" id="location_info">
			<div id="default">
				<?php echo $Content["default_loc"]; ?>
			</div>
		</div>
	</div>
	
	<?php require "layout/footer.php"; ?>
</html>

<script>
<?php

if (isset($_GET['id'])) {
?>
	var contentEl = document.getElementById("location_info");
	
	// Remove the default text
	var defaultText = document.getElementById("default");
	contentEl.removeChild(defaultText);
	
	<?php 
		$information = GetItemInfo("locations", $_GET['id']); 
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
			
			<?php if ((strpos($key, "ID") !== false) and ($key != "ID")) {
				
				if ($value != "") {
					
					if ($key == "NameChangesID") { ?>
						
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
						
						<?php if (($key == "FounderID") || ($key == "DestroyerID")) { ?>
							currentHref = "<?php echo AddLangParam('peoples.php') ?>";
							TableLink.href = updateURLParameter(currentHref, "id", value);
						<?php } else if (($key == "StartEventID") || ($key == "EndEventID")) { ?>
							currentHref = "<?php echo AddLangParam('events.php') ?>";
							TableLink.href = updateURLParameter(currentHref, "id", value);
						<?php } else { ?>
							currentHref = window.location.href;
							TableLink.href = updateURLParameter(currentHref, "id", value);
						<?php } ?>
						
						TableData.appendChild(TableLink);
					<?php } 
				}
			} else { ?>
				// Add a new table row
				var TableKey = document.createElement("td");
				TableKey.innerHTML = "<?php echo $LocationsParams[$key]; ?>";
			
				var TableData = document.createElement("td");
				<?php if (($key == "Coordinates") && ($value != "")) { ?>
					// Split the string coordinates into two separate coordinates
					var coordinatesStr = value.split(',');
					
					// Now turn them into floats
					var coordinatesFl = [-1, -1];
					coordinatesFl[0] = parseFloat(coordinatesStr[0]);
					coordinatesFl[1] = parseFloat(coordinatesStr[1]);
					
					// Only show two decimals after the comma
					var TableLink = document.createElement("a");
					TableLink.innerHTML = coordinatesFl[0].toFixed(2) + ", " + coordinatesFl[1].toFixed(2);
					TableLink.href = updateURLParameter("<?php echo AddLangParam('worldmap.php') ?>", "id", <?php echo $information["ID"]; ?>);
					
					TableData.appendChild(TableLink);
				<?php } else { ?>
					TableData.innerHTML = value;
				<?php } ?>
			
				// Left is key names
				// right is value names
				var TableRow = document.createElement("tr");
				TableRow.appendChild(TableKey);
				TableRow.appendChild(TableData);
				
				table.appendChild(TableRow);
			<?php }
		} ?>
	
	contentEl.appendChild(table);
<?php
}
?>

	window.onload = CheckButtons;
</script>
<?php 
	$id = "books";
	require "layout/header.php"; 
	require "tools/databaseHelper.php"; 
?>

<?php require "layout/footer.php"; ?>

<script>
<?php

if (isset($_GET['id'])) {
?>
	var contentEl = document.getElementById("item_info");
	
	// Remove the default text
	var defaultText = document.getElementById("default");
	contentEl.removeChild(defaultText);
	
	<?php 
		$information = GetItemInfo("books", $_GET['id']); 
	?>
	
	// Show a list of family trees where this person is included in
	var Name = document.createElement("h1");
	Name.innerHTML = "<?php echo $information["Name"]; ?>";
	contentEl.appendChild(Name);
	
	// Create a Table
	var table = document.createElement("table");
	
	<?php		
		foreach ($information as $key => $value)
		{
			?>
			
			<?php if ($value == -1) {
				$value = "";
			} 
			if (($key == "Name") or ($key == "ID")) {
				continue;
			}?>
			
			<?php if (strpos($key, "ID") !== false) {
				if ($value != "") { ?>
					// Update the previous table cell with a link to the ID
					var TableLink = document.createElement("a");
					TableLink.innerHTML = TableData.innerHTML;
					TableData.innerHTML = "";
					
					currentHref = window.location.href;
					TableLink.href = updateURLParameter(currentHref, "id", <?php echo "'".$value."'"; ?>);
					
					TableData.appendChild(TableLink);
			<?php } 
			} else { ?>
				// Add a new table row
				var TableKey = document.createElement("td");
				TableKey.innerHTML = "<?php echo $BooksParams[$key]; ?>";
			
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
<?php
}
?>

	window.onload = CheckButtons;
</script>
<?php 
	$id = "specials";
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
		$information = GetItemInfo("specials", $_GET['id']); 
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
			
			var TableKey = document.createElement("td");
			TableKey.innerHTML = "<?php echo $SpecialsParams[$key]; ?>";
			
			<?php if ($value == -1) {
				$value = "";
			} 
			if (($key == "Name") or ($key == "ID")) {
				continue;
			}?>
			
			<?php if (strpos($key, "ID") !== false) {
				if ($value != "") { ?>
					var TableLink = document.createElement("a");
					TableLink.innerHTML = "<?php echo $value; ?>";
					
					currentHref = window.location.href;
					TableLink.href = updateURLParameter(currentHref, "id", <?php echo "'".$value."'"; ?>);
					
					TableData.appendChild(TableLink);
			<?php } 
			} else { ?>
				var TableData = document.createElement("td");
				TableData.innerHTML = "<?php echo $value; ?>";
			<?php } ?>
			
			// Left is key names
			// right is value names
			var TableRow = document.createElement("tr");
			TableRow.appendChild(TableKey);
			TableRow.appendChild(TableData);
			
			table.appendChild(TableRow);
			<?php
		}
	?>
	
	contentEl.appendChild(table);
<?php
}
?>

	window.onload = CheckButtons;
</script>
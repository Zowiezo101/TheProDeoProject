<?php 
	require "layout/header.php"; 
	
	$item_type = "specials";
	require "tools/databaseHelper.php"; 
?>

<div class="clearfix">
	<div class="contents_left">
		<div id="button_bar">
			<button id="button_left" onClick="PrevPage()"><?php echo $Content["prev"]; ?></button>
			<button id="button_right" onClick="NextPage()"><?php echo $Content["next"]; ?></button>
		</div>
		
		<div id="special_bar">
			<?php GetListOfItems("specials"); ?>
		</div>
	</div>
	
	<div class="contents_right" id="special_info">
		<div id="default">
			<?php echo $Content["default_special"]; ?>
		</div>
	</div>
</div>

<?php require "layout/footer.php"; ?>

<script>
<?php

if (isset($_GET['id'])) {
?>
	var contentEl = document.getElementById("special_info");
	
	// Remove the default text
	var defaultText = document.getElementById("default");
	contentEl.removeChild(defaultText);
	
	<?php 
		$information = GetItemInfo("specials", $_GET['id']); 
	?>
	
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
			}?>
			
			<?php if ((strpos($key, "ID") !== false) and ($key != "ID")) {
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
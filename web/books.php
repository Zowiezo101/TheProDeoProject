<?php 
	$id = "books";
	require "layout/header.php"; 
	require "tools/databaseHelper.php"; 
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
		
		<div id="book_bar">
			<?php GetListOfItems("books"); ?>
		</div>
	</div>
	
	<div class="contents_right" id="book_info">
		<div id="default">
			<?php echo $Content["default_book"]; ?>
		</div>
	</div>
</div>

<?php require "layout/footer.php"; ?>

<script>
<?php

if (isset($_GET['id'])) {
?>
	var contentEl = document.getElementById("book_info");
	
	// Remove the default text
	var defaultText = document.getElementById("default");
	contentEl.removeChild(defaultText);
	
	<?php 
		$information = GetItemInfo("books", $_GET['id']); 
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
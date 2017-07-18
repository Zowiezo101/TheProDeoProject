<!DOCTYPE html>
<html>
	<?php require "layout/header.php"; ?>
	
	<div class="clearfix">
		<div class="contents_left" id="people_bar">
			<?php GetListOfItems("peoples"); ?>
		</div>
		
		<div class="contents_right" id="people_info">
			<h1 id="default"><?php echo $Content["default"]; ?></h1>
		</div>
	</div>
	
	<?php require "layout/footer.php"; ?>
</html>

<!-- TODO: Make sure to add some buttons to reach the other xxx names as well..
	It is now limited to 100 names only.. -->
<?php
if (isset($_POST['submit'])) {
?>
	<script>
		var contentEl = document.getElementById("people_info");
		
		// Remove the default text
		var defaultText = document.getElementById("default");
		contentEl.removeChild(defaultText);
		
		<?php 
			$information = GetItemInfo("peoples", $_POST['id']); 
		?>
		
		// Create a Table
		var table = document.createElement("table");
		
		<?php		
			foreach ($information as $key => $value)
			{
				?>
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
				<?php
			}
		?>
		
		contentEl.appendChild(table);
	</script>
<?php
}
?>
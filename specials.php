<!DOCTYPE html>
<html>
	<?php require "layout/header.php"; ?>
	
	<div class="clearfix">
		<div class="contents_left" id="special_bar">
			<?php GetListOfItems("specials"); ?>
		</div>
		
		<div class="contents_right" id="special_info">
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
		var contentEl = document.getElementById("special_info");
		
		// Remove the default text
		var defaultText = document.getElementById("default");
		contentEl.removeChild(defaultText);
		
		<?php 
			$information = GetItemInfo("specials", $_POST['id']); 
		?>
		
		// var ID = <?php echo "'".$_POST['id']."'"; ?>;
		var ID = <?php 
			echo "'".$information['ID']."'"; 
		?>;
		
		// Create a Table
		var table = document.createElement("table");
		
		<?php
			foreach ($information as $key => $value)
			{
				?>
				var TableKey = document.createElement("td");
				TableKey.innerHTML = "<?php echo $SpecialsParams[$key]; ?>";
				
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
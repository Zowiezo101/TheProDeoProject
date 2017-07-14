<!DOCTYPE html>
<html>
	<?php require "layout/header.php"; ?>
	<?php require "database/database.php"; ?>
	
	<div class="clearfix">
		<div class="contents_left" id="people_bar">
			<?php GetListOfPeoples(); ?>
		</div>
		
		<div class="contents_right" id="people_info">
			<h1>Right</h1>
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
		
		<?php 
			$information = GetPeopleInfo($_POST['id']); 
		?>
		
		// var ID = <?php echo "'".$_POST['id']."'"; ?>;
		var ID = <?php 
			echo "'".$information['ID']."'"; 
		?>;
		
		// Create a Table
		// Left is key names
		// right is value names
		contentEl.innerHTML = ID;
	</script>
<?php
}
?>
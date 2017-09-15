<!DOCTYPE html>
<html>
	<?php 
		require "layout/header.php"; 
		require "tools/familytreeHelper.php";
	?>
	
	<div class="clearfix">
		<div class="contents_left">			
			<div id="family_bar">
				<!-- We fill this up in the FamilyTree javascript code -->
			</div>
		</div>
		
		<div class="contents_right" id="familytree">
			<div id="default">
				<?php echo $Content["default_ft"]; ?>
			</div>
		</div>
	</div>
	
	<?php require "layout/footer.php" ?>
</html>

<script>
// List of peoples
Peoples = [<?php echo FindPeoples(); ?>];
			
// Create all the connections between parents and children
setPeoples();

window.onload = createFamilyTree;
</script>
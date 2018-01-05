<?php 
	$id = "familytree";
	require "layout/header.php"; 
	require "tools/familytreeHelper.php";
?>

<div class="clearfix">
	<div class="contents_left" id="item_choice">			
		<div id="item_bar">
			<!-- We fill this up in the FamilyTree javascript code -->
		</div>
	</div>
	
	<div class="contents_right" id="familytree_div">
		<div id="default">
			<?php if (isset($_GET['id'])) {
				echo $Content["loading_ft"]; ?>
				
				<div id="progress_bar">
					<div id="progress">
						1%
					</div>
				</div>
			<?php } else {
				echo $Content["default_ft"];
			} ?>
		</div>	
		
		<div id="hidden_div"
			 style="display: none"
			 >
			 
			<svg id="hidden_svg"></svg>
			<canvas id="hidden_cs"></canvas>
			<a id="hidden_a"></a>
		</div>

	</div>
</div>
	
<!--
  this is used to download content dynamically from the client side. Note
  that this div is, by default, not visible with the styling above.
-->

<?php require "layout/footer.php" ?>

<script>
// List of peoples
Peoples = [<?php echo FindPeoples(); ?>];
			
// Create all the connections between parents and children
setPeoples();

window.onload = createFamilyTree;
</script>
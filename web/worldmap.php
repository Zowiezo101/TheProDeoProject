<?php 
	require "layout/header.php"; 
	require "tools/worldmapHelper.php";
?>
	
<div class="clearfix">
	<div class="contents_left">			
		<div id="world_bar">
			<!-- We fill this up in the Worldmap javascript code -->
		</div>
	</div>
	
	<div class="contents_right" id="worldmap">			
		<div id="default">
			<?php echo $Content["default_wm"]; ?>
		</div>
		
		<div id="google_maps"></div>
	</div>
</div>

<?php require "layout/footer.php" ?>

<script>
// List of locations of which the coordinates are known
var Locations = [<?php echo FindLocations(); ?>];
			
// Create all the connections between parents and children
setLocations();

window.onload = createWorldMap;
</script>

<script async defer 
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyFq1pKyxT7asd87wAgr83_yWIrT-sz7E&callback=displayGoogleMaps">
</script>
<!DOCTYPE html>
<html>
	<?php require "layout/header.php"; ?>
	
	<div>
		<h1><?php echo $Content["tbd"]; ?></h1>
	</div>
	
	<div class="clearfix">
		<div class="contents_left">			
			<div id="world_bar">
				<!-- We fill this up in the Worldmap javascript code -->
			</div>
		</div>
		
		<div class="contents_right" id="worldmap">
			<div id="default">
				<?php //echo $Content["default_wm"]; ?>
			</div>
			
			<div id="googleMap" style="width:100%;height:100%;"></div>
		</div>
	</div>
	
	<?php require "layout/footer.php" ?>
</html>

<script>
function myMap() {
	var mapProp = {
		center: new google.maps.LatLng(51.508742, -0.120850),
		zoom: 5,
	};
	
	var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
}
</script>

<script async defer 
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyFq1pKyxT7asd87wAgr83_yWIrT-sz7E&callback=myMap">
</script>
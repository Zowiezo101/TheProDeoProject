<?php 
	$id = "worldmap";
	require "layout/layout.php"; 
?>

<?php

function worldmap_Helper_layout() {
	global $dict_Worldmap;
	global $id;
	global $$id;
	
	PrettyPrint('<div class="clearfix"> ', 1);
	PrettyPrint('	<div class="contents_left" id="item_choice"> ');
	PrettyPrint('		<div id="item_bar" class="item_'.$$id.'"> ');
	PrettyPrint('			<!-- We fill this up in the Worldmap javascript code --> ');
	PrettyPrint('		</div> ');
	PrettyPrint('	</div> ');
	PrettyPrint('');
	PrettyPrint('	<div class="contents_right" id="worldmap_div"> ');
	PrettyPrint('		<div id="default"> ');
	PrettyPrint('			'.$dict_Worldmap["loading_wm"]);
	PrettyPrint('		</div> ');
	PrettyPrint('');
	PrettyPrint('		<div id="google_maps"></div> ');
	PrettyPrint('	</div> ');
	PrettyPrint('</div> ');
}

function FindLocations() {
	global $_SESSION;
	global $dict_Search;
	global $conn;
	$location_set = "";
	
	$sql = "SELECT * FROM locations WHERE Coordinates IS NOT NULL";
	$result = $conn->query($sql);
	
	if (!$result) {
		$_SESSION["disp_error"] = $dict_Search["NoResults"];
	} elseif($result->num_rows == 0) {
		$_SESSION["disp_error"] = $dict_Search["NoResults"];
	} else {
		$counter = 0;
		while ($location = $result->fetch_array()) {
			$name = $location['Name'];
			$ID = $location['ID'];
			$Coordinates = $location['Coordinates'];
			
			$location = "new CreateLocation('".$name."', '".$counter."', '".$ID."', '".$Coordinates."'),";
			$location_set = $location_set."\r\n\t".$location;
			$counter++;
		}
	}
	
	return $location_set;
}
?>

<!-- Getting the javascript Google Maps API -->
<script async defer 
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyFq1pKyxT7asd87wAgr83_yWIrT-sz7E&callback=displayGoogleMaps">
</script>

<script>
var MapObject = null;
var openWindow = null;

// List of locations of which the coordinates are known
var Locations = [<?php echo FindLocations(); ?>];
			
// Prepare the coordinates in a way that Google Maps can use
setLocations();

var Helper_onLoad = function () {
	// Make a nice list here to choose from the set of locations that are known
	// When chosen, update location in the map
	var worldBar = document.getElementById("item_bar");
		
	<?php  
	if (isset($_SESSION['disp_error'])) {
		if ($_SESSION['disp_error'] != "") {
			// If there is an error, display it!
			PrettyPrint("var Error = document.createElement('p');");
			PrettyPrint("Error.innerHTML = '".$_SESSION['disp_error']."';");
			PrettyPrint("worldBar.appendChild(Error);");
			
			$_SESSION['disp_error'] = "";
		}
	}
	?>
	
	var table = document.createElement("table");
	for (var i = 0; i < Locations.length; i++) {
		var Location = Locations[i];
		
		var TableButton = document.createElement("button");
		TableButton.innerHTML = Location.name;
		TableButton.value = Location.index;
		TableButton.onclick = focusOnLocation;
		TableButton.id = "WorldMap" + Location.ID;
		
		var TableData = document.createElement("td");
		TableData.appendChild(TableButton);
	
		var TableRow = document.createElement("tr");
		TableRow.appendChild(TableData);
		
		table.appendChild(TableRow);
	}
	worldBar.appendChild(table);
	
	// Done loading, show the descriptions
	var defaultText = document.getElementById("default");
	defaultText.innerHTML = "<?php echo $dict_Worldmap["default_wm"]; ?>"
	
	<?php if (isset($_GET['id'])) { ?>
		var IDnum = <?php echo $_GET['id']; ?>;
		
		// Now "click" the button in the table to focus on this location
		var Button = document.getElementById("WorldMap" + IDnum);
		Button.click();
	<?php } ?>
}

// This function creates the Location objects
function CreateLocation(name, index, ID, coordinates) {
	this.name = name;
	// Difference between ID and index, is that index is the index used for the list
	// that only contains locations with known coordinates. While ID is the index used
	// for the list of all available locations
	this.index = index;
	this.ID = ID;
	
	// These are the coordinates as a single string
	this.coordinatesFlat = coordinates;
	
	// This are the coordinates as an array
	this.coordinates = [-1, -1];
	this.marker = null;
	
	// setCoordinates function
	this.setCoordinates = function (level) {
		// Split the string coordinates into two separate coordinates
		var coordinatesTemp = this.coordinatesFlat.split(',');
		
		// Now turn them into floats
		this.coordinates[0] = parseFloat(coordinatesTemp[0]);
		this.coordinates[1] = parseFloat(coordinatesTemp[1]);
	}
	
	// Drawing the marker on the google map
	this.drawMarker = function () {
		this.marker = new google.maps.Marker({
			position: {
						lat: this.coordinates[0],
						lng: this.coordinates[1]
					},
			map: MapObject,
			title: this.name
		});
		
		// This is the text that is shown, when the marker is clicked
		this.marker.infoWindow = new google.maps.InfoWindow({
			content: this.name + "<br><?php echo $dict_LocationsParams["Coordinates"]; ?>: " + this.coordinates[0].toFixed(2) + ", " + this.coordinates[1].toFixed(2) + "<br><a href=" + updateURLParameter("locations.php", "id", this.ID) + "><?php echo $dict_Worldmap["link_location"]; ?></a>"
		});
		
		this.marker.addListener('click', function() {
			// Close the current open window
			if (openWindow != null) {
				openWindow.close();
			}
			
			this.infoWindow.open(MapObject, this);
			openWindow = this.infoWindow;
		});
	}
	
	// A function to move the map, so that the clicked location is in the middle of the screen
	this.focusOnMe = function () {
		MapObject.panTo({
			lat : this.coordinates[0],
			lng : this.coordinates[1]
		});
		
		// Close the current open window
		if (openWindow != null) {
			openWindow.close();
		}
		
		this.marker.infoWindow.open(MapObject, this.marker);
		openWindow = this.marker.infoWindow;
	}
}

function setLocations() {	
	// Set up all the coordinates nicely for Google Maps
	for (i = 0; i < Locations.length; i++) {
		var Location = Locations[i];
		
		Location.setCoordinates();
	}
}

// This function is executed, when a location is clicked
function focusOnLocation(Event) {
	// Get the location
	var LocationId = Event.target.value;
	Location = Locations[LocationId];
	
	// The GoogleMaps div
	var GoogleMaps = document.getElementById("google_maps");
	
	// Remove the default text
	var WorldMap = document.getElementById("worldmap_div");
	var defaultText = document.getElementById("default");
	if (defaultText != null) {
		WorldMap.removeChild(defaultText);
	
		// This means that we now are looking at the Map for the first time
		GoogleMaps.style.display = "block";
		google.maps.event.trigger(GoogleMaps, 'resize');
		
		// Add all of our Locations
		for (var i = 0; i < Locations.length; i++) {
			Loc = Locations[i];
			Loc.drawMarker();
		}
	}
	
	// Now focus on the marker that is placed
	Location.focusOnMe();
}

// Callback function, when the Google maps is loaded
function displayGoogleMaps() {
	var mapProp = {
		center: new google.maps.LatLng(51.508742, -0.120850),
		zoom: 5,
	};
	
	// The handler of the Google map
	MapObject = new google.maps.Map(document.getElementById("google_maps"), mapProp);
}
	
window.onload = Helper_onLoad;
</script>
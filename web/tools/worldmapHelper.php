<?php
function FindLocations() {
	global $Search;
	global $conn;
	$location_set = "";
	
	$sql = "SELECT * FROM locations WHERE Coordinates IS NOT NULL";
	$result = $conn->query($sql);
	
	if (!$result) {
		echo($Search["NoResults"]);
	}
	else {
		$counter = 0;
		while ($location = $result->fetch_array()) {
			$name = $location['Name'];
			$ID = $location['ID'];
			$Coordinates = $location['Coordinates'];
			
			$location = "new CreateLocation('".$name."', '".$counter."', '".$ID."', '".$Coordinates."'),";
			$location_set = $location_set.$location;
			$counter++;
		}
	}
	
	return $location_set;
}
?>

<script>
// List of locations of which the coordinates are known
// This NEEDS to be filled up by the using side
var Locations = [];

var MapObject = null;
var openWindow = null;

function createWorldMap() {
	// Make a nice list here to choose from the set of locations that are known
	// When chosen, update location in the map
	var worldBar = document.getElementById("world_bar");
	
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
	defaultText.innerHTML = "<?php echo $Content["default_wm"]; ?>"
	
<?php if (isset($_GET['id'])) { ?>
	var IDnum = <?php echo $_GET['id']; ?>;
	
	// Now "click" the button in the table to draw it's family tree
	var Button = document.getElementById("WorldMap" + IDnum);
	Button.click();
<?php } ?>
}

function CreateLocation(name, index, ID, coordinates) {
	this.name = name;
	this.index = index;
	this.ID = ID;
	this.coordinatesFlat = coordinates;
	
	this.coordinates = [-1, -1];
	this.marker = null;
	
	/** setCoordinates function */
	this.setCoordinates = function (level) {
		// Split the string coordinates into two separate coordinates
		var coordinatesTemp = this.coordinatesFlat.split(',');
		
		// Now turn them into floats
		this.coordinates[0] = parseFloat(coordinatesTemp[0]);
		this.coordinates[1] = parseFloat(coordinatesTemp[1]);
	}
	
	this.drawMarker = function () {
		this.marker = new google.maps.Marker({
			position: {
						lat: this.coordinates[0],
						lng: this.coordinates[1]
					},
			map: MapObject,
			title: this.name
		});
		
		this.marker.infoWindow = new google.maps.InfoWindow({
			content: this.name + "<br><?php echo $LocationsParams["Coordinates"]; ?>: " + this.coordinates[0].toFixed(2) + ", " + this.coordinates[1].toFixed(2) + "<br><a href=" + updateURLParameter("locations.php", "id", this.ID) + "><?php echo $Content["link_location"]; ?></a>"
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
	
	this.openInfoWindow = function() {
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

function focusOnLocation(Event) {
	var LocationId = Event.target.value;
	Location = Locations[LocationId];
	
	// The GoogleMaps div
	var GoogleMaps = document.getElementById("google_maps");
	
	// Remove the default text
	var WorldMap = document.getElementById("worldmap");
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

function displayGoogleMaps() {
	var mapProp = {
		center: new google.maps.LatLng(51.508742, -0.120850),
		zoom: 5,
	};
	
	MapObject = new google.maps.Map(document.getElementById("google_maps"), mapProp);
}
</script>
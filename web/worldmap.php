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
				<?php echo $Content["default_wm"]; ?>
			</div>
			
			<div id="google_maps"></div>
		</div>
	</div>
	
	<?php require "layout/footer.php" ?>
</html>

<script>
// List of locations of which the coordinates are known
var Locations = [<?php echo FindLocations(); ?>];
			
// Create all the connections between parents and children
setLocations();

var MapObject = null;

window.onload = function createWorldMap() {
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
		
		var TableData = document.createElement("td");
		TableData.appendChild(TableButton);
	
		var TableRow = document.createElement("tr");
		TableRow.appendChild(TableData);
		
		table.appendChild(TableRow);
	}
	worldBar.appendChild(table);
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
			content: "<a href=" + updateURLParameter("locations.php", "id", this.ID) + ">" + this.name + "</a><br>" + this.coordinates[0].toFixed(2) + ", " + this.coordinates[1].toFixed(2)
		});
		
		this.marker.addListener('click', function() {
			this.infoWindow.open(MapObject, this);
		});
	}
	
	this.focusOnMe = function () {
		MapObject.panTo({
			lat : this.coordinates[0],
			lng : this.coordinates[1]
		});
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

window.onerror = function(msg, url, linenumber) {
	alert('Error message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
	return true;
}
	
/**
* http://stackoverflow.com/a/10997390/11236
*/
function updateURLParameter(url, param, paramVal){
// function updateURLParameter(url, param){
	var newAdditionalURL = "";
	var tempArray = url.split("?");
	var baseURL = tempArray[0];
	var additionalURL = tempArray[1];
	var temp = "";
	
	if (additionalURL) {
		tempArray = additionalURL.split("&");
		
		for (var i=0; i<tempArray.length; i++){
			if(tempArray[i].split('=')[0] != param){
				newAdditionalURL += temp + tempArray[i];
				temp = "&";
			}
		}
	}

	var rows_txt = temp + "" + param + "=" + paramVal;
	return baseURL + "?" + newAdditionalURL + rows_txt;
}
</script>

<script async defer 
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyFq1pKyxT7asd87wAgr83_yWIrT-sz7E&callback=displayGoogleMaps">
</script>
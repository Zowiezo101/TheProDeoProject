/* global Items, google, globalMapId, dict_LocationsParams, dict_Worldmap, session_settings, updateSessionSettings */

var MapObject = null;
var openWindow = null;

// This function creates the Location objects
function CreateItem(item) {
    this.name = item.name;
    this.id = Number(item.id);
    
    // These are the coordinates as a single string
    this.coordinatesFlat = item.coordinates;
    
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
    };
    
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
        
        // The node to put in the info window
        var contentDiv = document.createElement("div");
        
        // The name
        var nameP = document.createElement("h1");
        contentDiv.appendChild(nameP);
        nameP.innerHTML = this.name;
        
        // The coordinates
        var coordinatesP = document.createElement("p");
        contentDiv.appendChild(coordinatesP);
        coordinatesP.innerHTML = dict_LocationsParams["coordinates"] + ": " + 
                this.coordinates[0].toFixed(2) + ", " + 
                this.coordinates[1].toFixed(2);
        
        // The link
        var link = document.createElement("a");
        contentDiv.appendChild(link);
        
        link.innerHTML = dict_Worldmap["link_location"];
        link.addEventListener('click', function() {
            updateSessionSettings("keep", true).then(goToPage("locations.php", "", session_settings["map"]), console.log);
        });
        
        // This is the text that is shown, when the marker is clicked
        this.marker.infoWindow = new google.maps.InfoWindow({
            content: contentDiv
        });
        
        this.marker.addListener('click', function() {
            // Close the current open window
            if (openWindow !== null) {
                openWindow.close();
            }
            
            this.infoWindow.open(MapObject, this);
            openWindow = this.infoWindow;
        });
    };
    
    // A function to move the map, so that the clicked location is in the middle of the screen
    this.focusOnMe = function () {
        MapObject.panTo({
            lat : this.coordinates[0],
            lng : this.coordinates[1]
        });
        
        // Close the current open window
        if (openWindow !== null) {
            openWindow.close();
        }
        
        this.marker.infoWindow.open(MapObject, this.marker);
        openWindow = this.marker.infoWindow;
    };
}

function setItems() {
    // Set up all the coordinates nicely for Google Maps
    for (var i = 0; i < Items.length; i++) {
        var Location = Items[i];
        
        Location.setCoordinates();
    }
}

// This function is executed, when a location is clicked
function showMap() {
    var right = document.getElementById("item_info");
    
    // The google map
    var GoogleMaps = document.getElementById("google_maps");
    if (GoogleMaps === null) {
        // Remove the default text
        var defaultText = document.getElementById("default");
        
        if (defaultText !== null) {
            right.removeChild(defaultText);
        }
    
        // The google map
        var GoogleMaps = document.createElement("div");
        right.appendChild(GoogleMaps);

        // Set the attributes
        GoogleMaps.id = "google_maps";

        // Create the script tag, set the appropriate attributes
        var script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAyFq1pKyxT7asd87wAgr83_yWIrT-sz7E&callback=displayGoogleMaps';
        script.defer = true;
        script.async = true;

        // Attach your callback function to the `window` object
        window.displayGoogleMaps = function() {
            var mapProp = {
                center: new google.maps.LatLng(51.508742, -0.120850),
                zoom: 5
            };

            // The handler of the Google map
            MapObject = new google.maps.Map(document.getElementById("google_maps"), mapProp);

            // The google map
            var GoogleMaps = document.getElementById("google_maps");

            // This means that we now are looking at the Map for the first time
            GoogleMaps.style.display = "block";
            google.maps.event.trigger(GoogleMaps, 'resize');
    
            // Get the location
            var Location = getItemById(globalMapId);

            // Add all of our Locations
            for (var i = 0; i < Items.length; i++) {
                var Loc = Items[i];
                Loc.drawMarker();
            }

            // Now focus on the marker that is placed
            Location.focusOnMe();
        };

        // Append the 'script' element to 'head'
        document.head.appendChild(script);
    } else {
        // Get the location
        var Location = getItemById(globalMapId);

        // Now focus on the marker that is placed
        Location.focusOnMe();
    }
}
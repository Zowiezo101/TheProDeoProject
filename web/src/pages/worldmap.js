
/* global google, getWorldmap, markerClusterer, dict, get_settings */

var map = null;
var markers = [];
var infoWindow = null;
var markerClusterer = null;

function getWorldmapContent(location) {
    
}

function showMap() {
    
    var div = $("<div id='google_maps' class='min-vh-75'>").appendTo("#item_content").get(0);
    
    // Show the entire map
    map = new google.maps.Map(div, {
        center: { lat: 0, lng: 0 },
        zoom: 2
    });

    // Insert all the locations as markers to click
    getWorldmap().then(function(worldmap) {
        if (worldmap) {            
            worldmap.records.forEach(function (location) {
                // Get the coordinates from the location object
                var coords = location.coordinates.split(',');

                // The marker, positioned at the location
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(coords[0]), lng: parseFloat(coords[1]) }
                });

                // markers can only be keyboard focusable when they have click listeners
                // open info window when marker is clicked
                marker.addListener("click", () => {                    
                    // Move to marker
                    map.setCenter(marker.getPosition());
                    var zoom = map.getZoom();
                    map.setZoom(Math.max(8, zoom));
                    
                    // Is there already a window open?
                    if (infoWindow !== null) {
                        infoWindow.close();
                    }
        
                    // Create a new window and open it
                    infoWindow = new google.maps.InfoWindow({
                        content: "",
                        disableAutoPan: true
                    });

                    // Open the info window
                    infoWindow.setContent(setContent(location));
                    infoWindow.open(map, marker);
                });
                
                marker.id = location.id;
                
                markers.push(marker);

            });

            // Add a marker clusterer to manage the markers.
            markerCluster = new markerClusterer.MarkerClusterer({ map, markers });
        
            if (get_settings.hasOwnProperty("panTo")) {
                // Get the item to pan to
                var id = get_settings["panTo"];
    
                // Pan to the item
                getLinkToMap(id);
            }
        }
    });
}

function setContent(location) {
    // The information to show when a marker is clicked
    var info = location.name;
    
    info += dict["worldmap.information"] + getLinkToItem("locations", location.id, "self");
    
    return info;
}

function getLinkToMap(id) {    
    // Find the marker with this ID and click it
    var marker = markers.find(marker => marker.id === id.toString());
    new google.maps.event.trigger( marker, 'click' );
    
    return true;
}
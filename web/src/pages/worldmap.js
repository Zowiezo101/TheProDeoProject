
/* global google, getWorldmap, markerClusterer, dict, get_settings */

var map = null;
var markers = [];
var infoWindow = null;
var markerClusterer = null;
var markersPerZoom = [];

function getWorldmapContent(location) {
    
}

function showMap() {
    
    var div = $("<div id='google_maps' class='min-vh-75'>").appendTo("#item_content").get(0);
    
    // Show the entire map
    map = new google.maps.Map(div, {
        center: { lat: 0, lng: 0 },
        zoom: 2,
        streetViewControl: false,
        fullscreenControl: false
    });
    
    drawInfoWorldMapButton();

    // Insert all the locations as markers to click
    getWorldmap().then(function(worldmap) {
        if (worldmap.records) {            
            worldmap.records.forEach(function (location) {
                // Get the coordinates from the location object
                var coords = location.coordinates.split(',');

                // The marker, positioned at the location
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(coords[0]), lng: parseFloat(coords[1]) }
                });

                // open info window when marker is clicked
                marker.addListener("click", () => {          
                    
                    // Did we come here via the sidebar?
                    if (marker.button) {
                        marker.button = false;
                        new google.maps.event.trigger(marker, "dblclick");
                    }
                    
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
                    infoWindow.id = location.id;
                });
                
                marker.addListener("dblclick", () => {     
                    
                    // Move to marker
                    map.setCenter(marker.getPosition());

                    // Ignore the existing zoom
                    var minZoom = minZoomForMarker(marker.id);
                    var typeZoom = minZoomForType(marker.type);
                    
                    // Actually check the type that this location
                    // is and use that to get the best suited zoom, take
                    // the closest zoom. Don't zoom out
                    var zoom = Math.max(minZoom, typeZoom);
                    map.setZoom(zoom);
                    map.panBy(0, -Math.round($("#google_maps").height() / 6));
                });
                
                marker.id = location.id;
                marker.type = location.type;
                
                markers.push(marker);

            });

            // Add a marker clusterer to manage the markers.
            markerClusterer = new markerClusterer.MarkerClusterer({ map, markers });
            
            google.maps.event.addListenerOnce(markerClusterer, "clusteringend", () => {
                getMarkersPerZoom();
                
                if (get_settings.hasOwnProperty("panTo")) {
                    // Get the item to pan to
                    var id = get_settings["panTo"];

                    // Pan to the item
                    getLinkToMap(id);
                }
            });
            
            map.addListener("zoom_changed", () => {
                    
                // Is there a window open?
                if (infoWindow !== null) {
                    // Check the minimum zoom needed to show this marker
                    // and its infoWindow
                    var minZoom = minZoomForMarker(infoWindow.id);
                    var curZoom = map.getZoom();
                    
                    if (curZoom < minZoom) {
                        // This marker is no longer out of a cluster
                        // The infoWindow will disappear, close it
                        infoWindow.close();
                    }
                }
            });
        }
    });
}

function setContent(location) {
    // The information to show when a marker is clicked
    var info = "<h2>" + location.name + "</h2>" + 
        "<table class='table table-striped'>" + 
            "<tbody>" +
                insertDetail(location, "meaning_name") + 
                insertDetail(location, "aka") + 
                insertDetail(location, "descr") + 
                insertDetail(location, "type") + 
            "</tbody>" + 
        "</table>" + 
        "<p class='font-weight-bold'>" + dict["map.info.location.details"] + ":<br>" + getLinkToItem("locations", location.id, "self", {"openInNewTab": true}) + "</p>";
    
    return info;
}

function getLinkToMap(id) {    
    // Find the marker with this ID and click it
    var marker = markers.find(marker => marker.id === id.toString());
    
    if (marker) {
        // Let the click event know we came here via button from the sidebar
        marker.button = true;

        // Trigger the click
        new google.maps.event.trigger(marker, "click");
    }
    return true;
}

function markerInCluster(id) {
    var clusters = markerClusterer.clusters;
    var markerCluster = clusters.find(function(cluster) {
        // Check if the marker is present in the current cluster
        var marker = cluster.markers.find(marker => marker.id === id.toString());
        return marker ? true : false;
    });
    
    return markerCluster && markerCluster.markers.length > 1 ? markerCluster : null;
}

function minZoomForMarker(id) {
    // Get the minimum zoom needed to have a marker seperate from the cluster
    for (var zoom = 0; zoom < markersPerZoom.length; zoom++) {
        var markers = markersPerZoom[zoom];
        if (markers.includes(id)) {
            break;
        }
    }
    return zoom;
}

function getMarkersPerZoom () {
    // Get the markers visible per zoom level
    for(var i = 0; i <= markerClusterer.algorithm.maxZoom; i++) {
        var visibleMarkers = markerClusterer.algorithm.superCluster.getClusters([-180, -90, 180, 90], i);
        markersPerZoom.push(visibleMarkers.filter(function(marker) {
            return !marker.hasOwnProperty("id");
        }).map(function(marker) {
            return marker.properties.marker.id;
        }));
    }
}

function drawInfoWorldMapButton() {    
    // The height and width of the SVG parent
    var div = $("#item_content");
    div.append(`<div style="position: absolute; top: 10px; right: 10px; padding: inherit;" class="btn-group">
                    <button class="btn btn-light" data-toggle="modal" data-target="#infoModal" title="` + dict["map.info.controls"] + `"><i class="fa fa-info-circle" aria-hidden="true"></i></button>
                </div>`);
    
    // The modal for the information button
    div.append(`
        <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModal" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">` + dict["map.info.controls"] + `</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                ` + dict["worldmap.overview"] + `<br><br>
              </div>
            </div>
          </div>
        </div>`);
}

function minZoomForType(type) {
    var zoom = "";
    
    switch(type) {
        case 0:
        case "0":
            // Well
            zoom = 15;
            break;
            
        case 1:
        case "1":
            // River
            zoom = 10;
            break;
            
        case 2:
        case "2":
            // Mountain
            zoom = 10;
            break;
            
        case 3:
        case "3":
            // Valley
            zoom = 9;
            break;
            
        case 4:
        case "4":
            // Country
            zoom = 7;
            break;
            
        case 5:
        case "5":
            // District
            zoom = 12;
            break;
            
        case 6:
        case "6":
            // County
            zoom = 9;
            break;
            
        case 7:
        case "7":
            // City
            zoom = 11;
            break;
            
        case 8:
        case "8":
            // Object
            zoom = 16;
            break;
            
        case 9:
        case "9":
        case -1:
        case "-1":
            // Unknown
            zoom = 8;
            break;
    }
    
    return zoom;
}
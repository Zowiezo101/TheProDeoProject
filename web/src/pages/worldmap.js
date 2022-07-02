
/* global google, getWorldmap, markerClusterer */

var map;

function getWorldmapContent(location) {
    // Go to the location and show more information about it
    
//    if (timeline.name === "Global") {
//        timeline.name = dict["timeline.global"];
//    }
//    
//    if (timeline) {
//        // An event has been selected, show its information
//        $("#item_content").append(`
//            <div class="row">
//                <div class="col-lg-11 text-center">
//                    <h1 class="mb-3">` + timeline.name + `</h1>
//                </div>
//            </div>
//            <div class="row" style="height: 100%;">
//                <div class="col-lg-11 text-center">
//                    <div id="map_div" style="height: 100%;">
//                        
//                    </div>
//                </div>
//            </div>
//        `);
//        
//        showMap(timeline);
//
//    } else {
//        // TODO Foutmelding, niet kunnen vinden?
//    }
}

function showMap() {
    // Show the entire map
    map = new google.maps.Map(document.getElementById("item_content"), {
        center: { lat: 0, lng: 0 },
        //41.902782,12.496365
        zoom: 2,
    });
        
    // The information window used by all markers
    const infoWindow = new google.maps.InfoWindow({
        content: "",
        disableAutoPan: true,
    });

    // Insert all the locations as markers to click
    getWorldmap().then(function(worldmap) {
        if (worldmap) {
            var markers = [];
            
            worldmap.records.forEach(function (location) {
                // Get the coordinates from the location object
                var coords = location.coordinates.split(',')

                // The marker, positioned at the location
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(coords[0]), lng: parseFloat(coords[1]) },
                });

                // markers can only be keyboard focusable when they have click listeners
                // open info window when marker is clicked
                marker.addListener("click", () => {
                    infoWindow.setContent(setContent(location));
                    infoWindow.open(map, marker);
                });
                
                markers.push(marker);

            });

            // Add a marker clusterer to manage the markers.
            const markerCluster = new markerClusterer.MarkerClusterer({ map, markers });
        }
    });
}

function setContent(location) {
    // The information to show when a marker is clicked
    var info = location.name;
    
    info += "<br><br>Click here for more information: <br>" + getLinkToItem("locations", location.id, "self");
    
    return info;
}
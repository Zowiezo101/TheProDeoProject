/* global SVG, TYPE_TIMELINE, dict */

function getTimelineContent(timeline) {
    if (timeline.name === "Global") {
        timeline.name = dict["timeline.global"];
    }
    
    if (timeline) {
        // An eventhas been selected, show its information
        $("#item_content").append(`
            <div class="row">
                <div class="col-lg-11 text-center">
                    <h1 class="mb-3">` + timeline.name + `</h1>
                </div>
            </div>
            <div class="row pb-5" style="height: 100%;">
                <div class="col-lg-11 text-center">
                    <div id="map_div" style="height: 100%;">
                        
                    </div>
                    <div id="map_download" class="d-none">
                        <!-- Used for downloading the SVG -->
                    </div>
                </div>
            </div>
        `);
        
        showMap(timeline);

    } else {
        // Error message, because database can't be reached
        $("#item_content").append(dict["settings.database_err"]);
    }
}

function showMap(timeline) {
    // Get the SVG
    var draw = SVG().addTo('#map_div').size('100%', '100%');  
    
    if(setSVG(draw)) {
        setMapItems(timeline);
        
        // Calculate all the locations of the familytree
        calcMapItems({type: TYPE_TIMELINE});

        // We've got the people and the locations, now time to draw it!
        drawControlButtons(timeline, TYPE_TIMELINE);
        drawMapItems();
        
        // Set viewSettings
        setViewSettings();
        
        panToItem(timeline);
    }
}
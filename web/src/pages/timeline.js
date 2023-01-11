/* global SVG, TYPE_TIMELINE, dict, g_Options, getMapItem */

function getTimelineContent(timeline) {
    if (timeline.name === "Global") {
        timeline.name = dict["timeline.global"];
    }
    
    if (timeline.hasOwnProperty('id')) {
        // An event has been selected, show its information
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
        
            <!-- Modal -->
            <div class="modal fade pr-0 vh-100" id="subMapModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="subMapModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" style="min-width:90%; height: 90%;">
                    <div class="modal-content" style="height: 100%;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="subMapModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-0" style="height: 100%;">
                            <div id="map_sub_div" style="height: 100%;">
                        </div>
                    </div>
                </div>
            </div>
        `);
        
        showMap(timeline);

    } else {
        // Error message, because database can't be reached
        $("#item_content")
                .addClass("text-center")
                .append(dict["settings.database_err"]);
    }

    $(function() { 
        $('#subMapModal').on('shown.bs.modal', showSubMap);
        $('#subMapModal').on('hidden.bs.modal', hideSubMap);
    });
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
        
        panToItem();
    }
}

function showSubMap(event) {
    // Change the settings that we are in subMap mode now
    g_Options.sub = true;
    var id = event.relatedTarget.id;
    
    $("#subMapModalLabel").text(getMapItem(id).name);
    
    $("#map_sub_div").empty();

    // Get the SVG
    var draw = SVG().addTo('#map_sub_div').size('100%', '100%');  

    if(setSubSVG(draw)) {        
        setSubMapItems(id);

        // Calculate all the locations of the familytree
        calcMapItems({type: TYPE_TIMELINE});

        // We've got the people and the locations, now time to draw it!
        drawControlButtons(getMapItem(id), TYPE_TIMELINE);
        drawMapItems();

        // Set viewSettings
        setSubViewSettings();

        panToId(id);
    }
};

function hideSubMap() {    
    // Change the settings that we are in regular mode now
    g_Options.sub = false;
};

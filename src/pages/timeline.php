<?php 
    function onPageLoad() {
        global $id;
        return "onLoad".ucfirst($id)."();";
    }
?>

<!-- For the sidebar used with many pages -->
<script src="/src/tools/client/items.js"></script>
        
<!-- Tools for navigating and downloading the map -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/svg.js/3.1.1/svg.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/svg-pan-zoom@3.5.0/dist/svg-pan-zoom.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/svgsaver@0.9.0/browser.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-toBlob@1.0.0/canvas-toBlob.min.js"></script>

<!-- The map maker -->
<script src="/src/tools/map/calc.js"></script>
<script src="/src/tools/map/draw.js"></script>
<script src="/src/tools/map/view.js"></script>

<script>
    // Function to load the content in the content div
    function onLoadTimeline() {
        $("#content").append(
            $("<div>").addClass("container-fluid").append(
                $("<div>").addClass("row")
                    // The column with the menu
                    .append(getItemsMenu())
                    // The column with the selected content 
                    .append(getContentDiv())
            )
        );

        // Depending on the selected timeline, 
        // we need to get information from the database first
        getItemsContent();
    }
    
    /* global SVG, TYPE_TIMELINE, dict, g_Options, getMapItem */

function getTimelineContent(timeline) {
    if (timeline.name === "Global") {
        timeline.name = dict["timeline.global"];
    }
    
    if (timeline.hasOwnProperty('id')) {
        // Remove the padding on top, we add our own using H1 margin
        $("#content_col").removeClass("py-5").addClass("pb-5");
        
        // An event has been selected, show its information
        $("#item_content").append(`
            <div class="row">
                <div class="col text-center">
                    <h1 class="my-3">` + timeline.name + `</h1>
                </div>
            </div>
            <div class="row min-vh-75">
                <div class="col text-center">
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
        $('#subMapModal').on('show.bs.modal', function() {
            // Hide all popovers
            $(".popover").popover("hide");
        });
    });

    $("body").on("click", function(e) {
        if ($(e.target).parents(".popover").length === 0) {
            $(".popover").popover("hide");
        }
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

</script>
/* global SVG, TYPE_FAMILYTREE, dict */

function getFamilytreeContent(familytree) {
    
    if (familytree.hasOwnProperty('id')) {
        // Remove the padding on top, we add our own using H1 margin
        $("#content_col").removeClass("py-5").addClass("pb-5");
        
        // A person has been selected, show its information
        $("#item_content").append(`
            <div class="row">
                <div class="col text-center">
                    <h1 class="my-3">` + familytree.name + `</h1>
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
        `);
        
        showMap(familytree);

    } else {
        // Error message, because database can't be reached
        $("#item_content")
                .addClass("text-center")
                .append(dict["settings.database_err"]);
    }

    $("body").on("click", function(e) {
        if ($(e.target).parents(".popover").length === 0) {
            $(".popover").popover("hide");
        }
    });
}

function showMap(familytree) {
    // Get the SVG
    var draw = SVG().addTo('#map_div').size('100%', '100%');
    
    if(setSVG(draw)) {
        setMapItems(familytree);
        
        // Calculate all the locations of the familytree
        calcMapItems({type: TYPE_FAMILYTREE});

        // We've got the people and the locations, now time to draw it!
        drawControlButtons(familytree, TYPE_FAMILYTREE);
        drawMapItems();
        
        // Set viewSettings
        setViewSettings();
        
        panToItem(familytree);
    }
}
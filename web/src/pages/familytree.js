/* global SVG, TYPE_FAMILYTREE, dict */

function getFamilytreeContent(familytree) {
    
    if (familytree.hasOwnProperty('id')) {
        // A person has been selected, show its information
        $("#item_content").append(`
            <div class="row">
                <div class="col-lg-11 text-center">
                    <h1 class="mb-3">` + familytree.name + `</h1>
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
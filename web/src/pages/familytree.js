/* global SVG, TYPE_FAMILYTREE */

function getFamilytreeContent(familytree) {
    
    if (familytree) {
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
        // TODO Foutmelding, niet kunnen vinden?
    }
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
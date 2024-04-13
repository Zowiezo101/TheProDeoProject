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
    function onLoadFamilytree() {
        $("#content").append(
            $("<div>").addClass("container-fluid").append(
                $("<div>").addClass("row")
                    // The column with the menu
                    .append(getItemsMenu())
                    // The column with the selected content 
                    .append(getContentDiv())
            )
        );

        // Depending on the selected person, 
        // we need to get information from the database first
        getItemsContent();
    }
    
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
</script>
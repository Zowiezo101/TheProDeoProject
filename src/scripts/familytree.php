<script>
    
    // TODO: Show loading screen when loading map, remove loading screen when map is loaded
    // Best way to do this is to load the template first, show loading screen and calculate
    // timeline/familytree using the REST API. After everything is received and loaded in, 
    // remove loading screen
    var page_base_url = "<?= setParameters("peoples/people/"); ?>";
    
    // This function is executed once the DOM is loaded
    // It's requesting the familytree from the REST API and inserts
    // it into the DOM
    $(function(){
        // The Map ID
        var map_id = $("#item_list").attr("data-id");
        
        if (map_id !== "") {
            getItem(TYPE_FAMILYTREE, map_id).then(function(data) {
                if (data.hasOwnProperty('records')) {
                    insertFamilytree(data.records);

                    // Remove the loading screen
                    hideLoadingScreen();

                    // Hide any visible popovers when clicking somewhere else
                    $("body").on("click", function(e) {
                        if ($(e.target).parents(".popover").length === 0) {
                            $(".popover").popover("hide");
                        }
                    });
                } else {
                    // Show an error message
                    $("#map_div").append(`
                            <div class="row">
                                <div class="col-12 text-center">
                                    ` + dict["settings.database_err"] + `
                                </div>
                            </div>`);
                }
            });
        }
    });
    
    function insertFamilytree(data) {
        // Set up the SVG
        var map = SVG().addTo('#map_div').size('100%', '100%');
        
//        // The mapmaker object, this will generate the map
//        var mapmaker = new MapMaker({});
//        mapmaker.setMap(map);
//        mapmaker.setItems(data);
//
//        // Calculate the locations of the familytree items
//        var familytree = data.records;

        // Draw the control buttons

        // Draw the map items

        // Pan to the item

        // Hide the loading screen

        if(setSVG(map)) {
            setMapItems(data);

            // Calculate all the locations of the familytree
            calcMapItems({type: TYPE_FAMILYTREE});

            // We've got the people and the locations, now time to draw it!
            drawControlButtons(data, TYPE_FAMILYTREE);
            drawMapItems();

            // Set viewSettings
            setViewSettings();

            panToItem(data);
        }
    }
</script>
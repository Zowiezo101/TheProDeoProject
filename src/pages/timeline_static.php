<?php 
    $type = $TYPE_TIMELINE;
    $type_item = $TYPE_EVENT;
    $page_base_url = "timeline/map";
    
    function insertContent($data_item) {        
        global $dict;
        $content = "";

        if (isset($data_item->records) && isset($data_item->records[0]->id)) {
            $event = $data_item->records[0];
            
            if ($event->name === "timeline.global") {
                // In case of the timeline, there is a global timeline
                // consisting of all the events
                $event->name = $dict[$event->name];
            }
            
            $content = '
            <div class="row">
                <div class="col text-center">
                    <h1 class="mb-3">'.$event->name.'</h1>
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
                            <div id="map_sub_div" style="height: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>';
        } else {
            $content = '
            <div class="row">
                <div class="col-12 text-center">
                    '.$dict["settings.database_err"].'
                </div>
            </div>';
        }

        return $content;
    }
?>

</script>
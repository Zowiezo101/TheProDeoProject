<?php 
    $type = $TYPE_FAMILYTREE;
    $type_item = $TYPE_PEOPLE;
    $page_base_url = "familytree/map";
    
    function insertContent($data_item) {        
        global $dict;
        $content = "";

        if (isset($data_item->records) && isset($data_item->records[0]->id)) {
            $people = $data_item->records[0];
            
            $content = '
            <div class="row">
                <div class="col text-center">
                    <h1 class="mb-3">'.$people->name.'</h1>
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
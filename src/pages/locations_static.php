<?php 
    $type = $TYPE_LOCATION;
    $page_base_url = "locations/location";
    
    /*
     * With image
     *  <div class="row mb-2">
            <div class="px-lg-5 d-flex flex-column justify-content-center col-lg-6 text-center">
                <h1>O my friend</h1>
                <p class="mb-3 lead">I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms of the insects and flies</p>
            </div>
            <div class="col-lg-4"> <img class="img-fluid d-block" src="https://static.pingendo.com/cover-moon.svg"> </div>
        </div>
     * 
     * Without image
     * <div class="row">
            <div class="col-md-10 text-center">
                <h1 class="mb-3">O my friend</h1>
                <p class="lead">A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.&nbsp; <br> <br>When, while the lovely valley teems with vapour around me, and the meridian sun strikes the upper surface of the impenetrable foliage of my trees, and but a few stray gleams steal into the inner sanctuary, I throw myself down among the tall grass by the trickling stream; and, as I lie close to the earth, a thousand unknown plants are noticed by me.</p>
            </div>
        </div>
     */   
    
    function insertContent($data_item) {
        global $dict;
        $content = "";
        
        if (isset($data_item->records) && isset($data_item->records[0]->id)) {
            $location = $data_item->records[0];
            
            $content = '
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <h1 class="mb-3">'.$location->name.'</h1>
                    <p class="lead">'.$location->descr.'</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <p class="lead font-weight-bold mt-4">'.$dict["items.details"].'</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <tbody>
                                '.insertTable($location).'
                            </tbody>
                        </table>
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
    
    function insertTable($location) {
        global $dict, 
                $TYPE_EVENT,
                $TYPE_PEOPLE,
                $TYPE_START,
                $TYPE_END;
        $rows = [];
        
        // Row for meaning of this locations name
        array_push($rows, [
            $dict["items.meaning_name"],
            $location->meaning_name
        ]);
        
        // Row for names this location is also known as
        array_push($rows, [
            $dict["items.aka"], 
            getAkaString($location)
        ]);
        
        // Row for the type of location
        array_push($rows, [
            $dict["items.type"], 
            getTypeString($location->type)
        ]);
        
        // Row for notes
        array_push($rows, [
            $dict["items.notes"], 
            getNotesString($location->notes),
            true
        ]);
        
        // Row for worldmap
        array_push($rows, [
            $dict["items.coordinates"], 
            getWorldmapString($location),
            true
        ]);
        
        // Row for events related to this location
        array_push($rows, [
            $dict["items.events"], 
            getLinksString($TYPE_EVENT, $location->events),
            true
        ]);
        
        // Row for peoples related to this location
        array_push($rows, [
            $dict["items.peoples"], 
            getLinksString($TYPE_PEOPLE, $location->peoples),
            true
        ]);
             
        // Row for bible location
        array_push($rows, [
            $dict["items.book_start"], 
            getBookLink($location, $TYPE_START)
        ]);
             
        // Row for bible location
        array_push($rows, [
            $dict["items.book_end"], 
            getBookLink($location, $TYPE_END)
        ]);
        
        $table = "";
        foreach($rows as $row) {
            // Take every row head and row data that we got and put them in 
            // a nice HTML format
            $head = $row[0];
            $data = $row[1];
            $hide_unknown = isset($row[2]) ? $row[2] : false;
            
            $table = $table.insertTableRow($head, $data, $hide_unknown);
        }
        
        return $table;
    }
    
    function getWorldmapString($location) {
        global $dict;
        
        $worldmap_string = "";
        
        // Make a link to Google maps if there are coordinates
        if (isset($location->coordinates) && $location->coordinates !== "") {
            $coord_x = explode(",", $location->coordinates)[0];
            $coord_y = explode(",", $location->coordinates)[1];
            
            $coords = implode(", ", [
                number_format($coord_x, 2), 
                number_format($coord_y, 2)
                ]
            );
            
            // The general worldmap, but panned to this location
            $href = setParameters("worldmap")."?panTo=".$location->id;
            
            $worldmap_string = '
                    <a href="'.$href.'" target="_blank" 
                        data-toggle="tooltip" title="'.$dict["items.details.worldmap"].'" 
                        class="font-weight-bold">
                            '.$coords.' 
                    </a>';
        }
        
        return $worldmap_string;
    }
?>
<?php 
    $type = $TYPE_EVENT;
    $page_base_url = "events/event";
    
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
            $event = $data_item->records[0];
            
            $content = '
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <h1 class="mb-3">'.$event->name.'</h1>
                    <p class="lead">'.$event->descr.'</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <p class="lead font-weight-bold mt-4">'.$dict["items.details"].'</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <tbody>
                                '.insertTable($event).'
                            </tbody>
                        </table>
                    </div>
                    <div id="table_maps">
                        <p class="lead font-weight-bold mt-4">'.$dict["items.details.timeline"].'</p>
                        <div class="table-responsive">
                            <table class="table table-striped table-borderless">
                                <tbody>
                                    '.insertTimelines($event).'
                                </tbody>
                            </table>
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
    
    function insertTable($event) {
        global $dict, 
                $TYPE_EVENT,
                $TYPE_PEOPLE,
                $TYPE_LOCATION,
                $TYPE_SPECIAL;
        $rows = [];
        
        // Row for length
        array_push($rows, [
            $dict["items.length"],
            $event->length
        ]);
        
        // Row for date
        array_push($rows, [
            $dict["items.date"], 
            $event->date
        ]);
        
        // Row for notes
        array_push($rows, [
            $dict["items.notes"], 
            getNotesString($event->notes)
        ]);
        
        // Row for peoples related to this event
        array_push($rows, [
            $dict["items.peoples"], 
            getLinksString($TYPE_PEOPLE, $event->peoples)
        ]);
        
        // Row for locations related to this event
        array_push($rows, [
            $dict["items.locations"], 
            getLinksString($TYPE_LOCATION, $event->locations)
        ]);
        
        // Row for specials related to this event
        array_push($rows, [
            $dict["items.specials"], 
            getLinksString($TYPE_SPECIAL, $event->specials)
        ]);
        
        // Row for previous event
        array_push($rows, [
            $dict["items.previous"], 
            getLinksString($TYPE_EVENT, $event->parents)
        ]);
        
        // Row for next event
        array_push($rows, [
            $dict["items.next"], 
            getLinksString($TYPE_EVENT, $event->children)
        ]);
        
        // Row for bible location
        array_push($rows, [
            $dict["items.books"], 
            getBooksString($event)
        ]);
        
        $table = "";
        foreach($rows as $row) {
            // Take every row head and row data that we got and put them in 
            // a nice HTML format
            $head = $row[0];
            $data = $row[1];
            
            $table = $table.insertTableRow($head, $data);
        }
        
        return $table;
    }
    
    function insertTimelines($event) {
        global $dict;
        
        // The timeline consists of the global timeline and the separate timelines
        // They don't overlap and thus there is always just two, 
        // but we do want to pan to the timeline in case of the global timeline    
        
        // The global timeline, going to map -999 and panning to the item
        $global_href = setParameters("timeline/map/-999")."?panTo=".$event->id;
        $global_timeline = '
                <tr>
                    <td>
                        <a href="'.$global_href.'" target="_blank" class="font-weight-bold">
                            '.$dict["timeline.global"].'
                        </a>
                    </td>
                </tr>';
        
        // The local timeline, going to the item specific timeline
        $local_href = setParameters("timeline/map/".$event->id);
        $local_timeline = '
                <tr>
                    <td>
                        <a href="'.$local_href.'" target="_blank" class="font-weight-bold">
                            '.$event->name.'
                        </a>
                    </td>
                </tr>';
        
        return $global_timeline.$local_timeline;
    }
?>
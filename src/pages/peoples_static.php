<?php 
    $type = $TYPE_PEOPLE;
    $page_base_url = "peoples/people";
    
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
            $people = $data_item->records[0];
            
            $content = '
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <h1 class="mb-3">'.$people->name.'</h1>
                    <p class="lead">'.$people->descr.'</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <p class="lead font-weight-bold mt-4">'.$dict["items.details"].'</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <tbody>
                                '.insertTable($people).'
                            </tbody>
                        </table>
                    </div>
                    '.insertFamilytrees($people).'
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
    
    function insertTable($people) {
        global $dict, 
                $TYPE_EVENT,
                $TYPE_PEOPLE,
                $TYPE_LOCATION,
                $TYPE_START,
                $TYPE_END;
        $rows = [];
        
        // Row for meaning of this persons name
        array_push($rows, [
            $dict["items.meaning_name"],
            $people->meaning_name
        ]);
        
        // Row for names this person is also known as
        array_push($rows, [
            $dict["items.aka"], 
            getAkaString($people)
        ]);
        
        // Row for age of father when this person was born
        array_push($rows, [
            $dict["items.father_age"], 
            $people->father_age
        ]);
        
        // Row for age of mother when this person was born
        array_push($rows, [
            $dict["items.mother_age"], 
            $people->mother_age
        ]);
        
        // Row for notes
        array_push($rows, [
            $dict["items.notes"], 
            getNotesString($people->notes),
            true
        ]);
        
        // Row for parents
        array_push($rows, [
            $dict["items.parents"], 
            getLinksString($TYPE_PEOPLE, $people->parents),
            true
        ]);
        
        // Row for children
        array_push($rows, [
            $dict["items.children"], 
            getLinksString($TYPE_PEOPLE, $people->children),
            true
        ]);
        
        // Row for events related to this person
        array_push($rows, [
            $dict["items.events"], 
            getLinksString($TYPE_EVENT, $people->events),
            true
        ]);
        
        // Row for locations related to this person
        array_push($rows, [
            $dict["items.locations"], 
            getLinksString($TYPE_LOCATION, $people->locations),
            true
        ]);
        
        // Row for reached age of this person
        array_push($rows, [
            $dict["items.age"], 
            $people->age
        ]);
        
        // Row for gender
        array_push($rows, [
            $dict["items.gender"], 
            getTypeString($people->gender)
        ]);
        
        // Row for tribe
        array_push($rows, [
            $dict["items.tribe"], 
            getTypeString($people->tribe)
        ]);
        
        // Row for profession
        array_push($rows, [
            $dict["items.profession"], 
            $people->profession
        ]);
        
        // Row for nationality
        array_push($rows, [
            $dict["items.nationality"], 
            $people->nationality
        ]);
             
        // Row for bible location
        array_push($rows, [
            $dict["items.book_start"], 
            getBookLink($people, $TYPE_START)
        ]);
             
        // Row for bible location
        array_push($rows, [
            $dict["items.book_end"], 
            getBookLink($people, $TYPE_END)
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
    
    function insertFamilytrees($people) {
        global $dict, $TYPE_PEOPLE;
        
        // We need to find the corresponding familytrees this person is a part of
        // Look for the ancestors and parents of this person
        // If this person has no parents, check for children
        // If this person has no children either, this person has no familytree
        
        $familytrees = [];
        
        $data = getMaps($TYPE_PEOPLE, $people->id);
        if (isset($data->records)) {
            foreach($data->records as $ancestor) {
                // All the familytrees this person is a part of
                $href = setParameters("familytree/map/".$ancestor->id)."?panTo=".$people->id;
                $familytree = '
                        <tr>
                            <td>
                                <a href="'.$href.'" target="_blank" class="font-weight-bold">
                                    '.$ancestor->name.'
                                </a>
                            </td>
                        </tr>';
                array_push($familytrees, $familytree);
            }
        }
        
        if (count($people->children) > 0 && count($people->parents) > 0) {
            // The local familytree, build specifically for this person
            $local_href = setParameters("familytree/map/".$people->id)."?panTo=".$people->id;
            $local_familytree = '
                    <tr>
                        <td>
                            <a href="'.$local_href.'" target="_blank" class="font-weight-bold">
                                '.$people->name.$dict["items.parent.familytree"].'
                            </a>
                        </td>
                    </tr>';
            array_push($familytrees, $local_familytree);
        }
        
        $familytree_string = count($familytrees) > 0 ? '
                    <div id="table_maps">
                        <p class="lead font-weight-bold mt-4">'.$dict["items.details.timeline"].'</p>
                        <div class="table-responsive">
                            <table class="table table-striped table-borderless">
                                <tbody>
                                    '.implode("<br>", $familytrees).'
                                </tbody>
                            </table>
                        </div>
                    </div>' : "";
            
        
        return $familytree_string;
    }
?>
<?php
    function insertTableRow($head, $row) {
        
        // Return data, hide it in the following case:
        // The value of a property is unknown and unknown values need to be hidden
        // Otherwise show the known value or a message that a value is not known
        return '
            <tr>
                <th scope="row">'.$head.'</th>
                <td>'.getKnownValues($row).'</td>
            </tr>';
    }
    
    function getKnownValues($data) {
        global $dict;
        
        // If data isn't set, show an unknown message instead
        if (!isset($data) || 
                ($data === "-1") || 
                ($data === "") || 
                ($data === -1)) {
            $data = $dict["items.unknown"];
        }
        
        return $data;
    }
    
    function getBooksString($data) {
        global $dict;
        
        $books = [];
        
        // The beginning and end of the bible location
        $book_start = "";
        $book_end = "";
        
        if(isset($data->book_start_id)) {
            $book_id = $dict["books.book_".$data->book_start_id];
            $book_chap = $data->book_start_chap;
            $book_vers = $data->book_start_vers;
            
            $book_start = $book_id." ".$book_chap.":".$book_vers;
        }
        
        if (isset($data->book_end_id)) {
            $book_id = $dict["books.book_".$data->book_end_id];
            $book_chap = $data->book_end_chap;
            $book_vers = $data->book_end_vers;
            
            $book_end = $book_id." ".$book_chap.":".$book_vers;
        }
        
        array_push($books, $book_start." - ".$book_end);
        
        if (isset($data->events) && (count($data->events) > 0)) {
            foreach($data->events as $aka) {
                if(isset($aka->book_start_id)) {
                    $book_id = $dict["books.book_".$aka->book_start_id];
                    $book_chap = $aka->book_start_chap;
                    $book_vers = $aka->book_start_vers;

                    $book_start = $book_id." ".$book_chap.":".$book_vers;
                }

                if (isset($aka->book_end_id)) {
                    $book_id = $dict["books.book_".$aka->book_end_id];
                    $book_chap = $aka->book_end_chap;
                    $book_vers = $aka->book_end_vers;

                    $book_end = $book_id." ".$book_chap.":".$book_vers;
                }
        
                array_push($books, $book_start." - ".$book_end);
            }
        }
        
        return implode("<br>", $books);
    }
    
    function getLinksString($type, $data) {
        global $dict;
        $links = [];
        
        $base_url = "";
        switch($type) {
            case "events":
            case "previous":
            case "next":
                $base_url = setParameters("events/event/");
                break;
            
            case "parents":
            case "children":
            case "peoples":
                $base_url = setParameters("peoples/people/");
                break;
            
            case "locations":
                $base_url = setParameters("locations/location/");
                break;
            
            case "specials":
                $base_url = setParameters("specials/special/");
                break;
        }
        
        foreach($data as $link) {
            // The link to this person
            $href = $base_url.$link->id;
            
            // The name to show with the link
            $name = $link->name;
            if (isset($link->type) && $link->type !== "") {
                $name = $name." (".$dict[$link->type].")";
            }
            
            array_push($links, '<a href="'.$href.'" class="font-weight-bold">'.$name.'</a>');
        }
        
        // Add it all together
        $links_str = implode("<br>", $links);
        return $links_str;
    }
    
    function getNotesString($data) {
        $notes = [];
        
        // All the sources are inserted as little numbers, make sure no number
        // repeats itself to prevent confusion
        $total_num_sources = 1;
        foreach ($data as $note) {
            // Every note has either zero, one or multiple sources
            $sources = [];
            
            foreach ($note->sources as $source) {
                // Turn every source into a link
                array_push($sources, "
                    <sup class='font-weight-bold'>
                        <a target='_blank' href='".$source."'>
                            ".$total_num_sources++."
                        </a>
                    </sup>");
            }
            $sources_str = implode(" ", $sources);
            
            // Add the actual note and the sources together
            array_push($notes, "<p>".$note->note." ".$sources_str."</p>");
        }
        
        // Add it all together
        $notes_str = implode("", $notes);
        return $notes_str;
    }
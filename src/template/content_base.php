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
        
        $plain_str = "";
        // If data isn't set, show an unknown message instead
        if (!isset($data) || 
                ($data === "-1") || 
                ($data === "") || 
                ($data === -1)) {
            $plain_str = $dict["items.unknown"];
        } else {
            $plain_str = $data;
        }
        
        return $plain_str;
    }
    
    function getNotesString($data) {
        $notes = [];
        $notes_str = "";
        
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
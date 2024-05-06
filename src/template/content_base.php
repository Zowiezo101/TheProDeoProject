<?php
    $TYPE_START = 0;
    $TYPE_END = 1;

    function insertTableRow($head, $row, $hide_unknown=false) {
        
        // Return data, hide it in the following case:
        // The value of a property is unknown and unknown values need to be hidden
        // Otherwise show the known value or a message that a value is not known
        $known_value = getKnownValues($row, $hide_unknown);
        return $known_value ? '
            <tr>
                <th scope="row">'.$head.'</th>
                <td>'.getKnownValues($row, $hide_unknown).'</td>
            </tr>' : "";
    }
    
    function getKnownValues($data, $hide_unknown) {
        global $dict;
        
        // If data isn't set, show an unknown message instead
        if (!isset($data) || 
                ($data === "-1") || 
                ($data === "") || 
                ($data === -1)) {
            $data = $hide_unknown ? null : $dict["items.unknown"];
        }
        
        return $data;
    }
    
    function getBookString($data, $type) {
        global $dict, $TYPE_START, $TYPE_END;
        
        // The first or last appearance of this item
        $book_string = "";
        
        if($type == $TYPE_START && isset($data->book_start_id)) {
            $book_id = $dict["books.book_".$data->book_start_id];
            $book_chap = $data->book_start_chap;
            $book_vers = $data->book_start_vers;
            
            $book_string = $book_id." ".$book_chap.":".$book_vers;
        } else if ($type == $TYPE_END && isset($data->book_end_id)) {
            $book_id = $dict["books.book_".$data->book_end_id];
            $book_chap = $data->book_end_chap;
            $book_vers = $data->book_end_vers;
            
            $book_string = $book_id." ".$book_chap.":".$book_vers;
        }
        
        return $book_string;
    }
    
    function getBooksString($data) {
        global $TYPE_START, $TYPE_END;
        
        $books = [];
        
        // The beginning and end of the bible location
        $book_start = getBookString($data, $TYPE_START);
        $book_end = getBookString($data, $TYPE_END);
        
        array_push($books, $book_start." - ".$book_end);
        
        if (isset($data->aka) && (count($data->aka) > 0)) {
            foreach($data->aka as $aka) {
                // The beginning and end of the bible location
                $book_start = getBookString($aka, $TYPE_START);
                $book_end = getBookString($aka, $TYPE_END);
        
                array_push($books, $book_start." - ".$book_end);
            }
        }
        
        return implode("<br>", $books);
    }
    
    function getBookLink($data, $type) {
        global $TYPE_START, $TYPE_END;
        
        if($type == $TYPE_START && isset($data->book_start_id)) {
            $book_id = $data->book_start_id;
            $book_chap = $data->book_start_chap;
            $book_vers = $data->book_start_vers;
        } else if ($type == $TYPE_END && isset($data->book_end_id)) {
            $book_id = $data->book_end_id;
            $book_chap = $data->book_end_chap;
            $book_vers = $data->book_end_vers;
        }
        
        $lang = filter_input(INPUT_GET, "lang");
        if (isset($lang) && ($lang === "nl")) {
            // The abbriviation used by the website
            $book_list = ["GEN", "EXO", "LEV", "NUM", "DEU",
                          "JOS", "JDG", "RUT", "1SA", "2SA",
                          "1KI", "2KI", "1CH", "2CH", "EZR",
                          "NEH", "EST", "JOB", "PSA", "PRO",
                          "ECC", "SNG", "ISA", "JER", "LAM",
                          "EZK", "DAN", "HOS", "JOL", "AMO",
                          "OBA", "JON", "MIC", "NAM", "HAB",
                          "ZEP", "HAG", "ZEC", "MAL", "MAT",
                          "MRK", "LUK", "JHN", "ACT", "ROM",
                          "1CO", "2CO", "GAL", "EPH", "PHP",
                          "COL", "1TH", "2TH", "1TI", "2TI",
                          "TIT", "PHM", "HEB", "JAS", "1PE",
                          "2PE", "1JN", "2JN", "3JN", "JUD",
                          "REV"];

            // Link to a certain part of the webpage, to get the exact verse mentioned
            $book_link = "https://debijbel.nl/bijbel/NBV/{$book_list[$book_id - 1]}.{$book_chap}.{$book_vers}";
        } else {
            // The bookname used by the website
            $book_list = ["Genesis", "Exodus", "Leviticus", "Numbers", "Deuteronomy",
                          "Joshua", "Judges", "Ruth", "1 Samuel", "2 Samuel",
                          "1 Kings", "2 Kings", "1 Chronicles", "2 Chronicles", "Ezra",
                          "Nehemiah", "Esther", "Job", "Psalm", "Proverbs",
                          "Ecclesiastes", "Song of Solomon", "Isaiah", "Jeremiah", "Lamentations",
                          "Ezekiel", "Daniel", "Hosea", "Joel", "Amos",
                          "Obadiah", "Jonah", "Micah", "Nahum", "Habakkuk",
                          "Zephaniah", "Haggai", "Zechariah", "Malachi", "Matthew",
                          "Mark", "Luke", "John", "Acts", "Romans",
                          "1 Corinthians", "2 Corinthians", "Galatians", "Ephesians", "Philippians",
                          "Colossians", "1 Thessalonians", "2 Thessalonians", "1 Timothy", "2 Timothy",
                          "Titus", "Philemon", "Hebrews", "James", "1 Peter",
                          "2 Peter", "1 John", "2 John", "3 John", "Jude",
                          "Revelation"];

            // Link to a certain part of the webpage, to get the exact verse mentioned
            $book_link = "https://www.biblegateway.com/passage/?search={$book_list[$book_id - 1]}+{$book_chap}:{$book_vers}&version=NLT";
        }
        
        // The first or last appearance of this item
        $book_string = getBookString($data, $type);
        
        return "<a 
                    href='{$book_link}'
                    target='_blank'
                    class='font-weight-bold'>
                    {$book_string}
                </a>";
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
    
    function getAkaString($data) {
        $akas = [];
        
        foreach($data->aka as $aka) {
            // Get all the AKAs for this item
            $name = $aka->name;
            
            $meaning_name = "";
            // If the meaning of this name is set, show this as well
            if (isset($aka->meaning_name) && $aka->meaning_name !== "") {
                $meaning_name = " ({$aka->meaning_name})";
            }
            
            // Add it all to the array
            array_push($akas, $name.$meaning_name);
        }
        
        // Put it all together
        $aka_str = implode("<br>", $akas);
        
        return $aka_str;
    }
    
    // TODO: Other instead of unknown. Unknown is already applied when no value is given
    function getTypeString($data) {
        global $dict;
        
        $type_string = "";
        if (isset($dict[$data])) {
            // If this exists, return it
            $type_string = $dict[$data];
        }
        
        return $type_string;
    }
<?php

    $ROW_STRING = "string";
    $ROW_NOTES = "notes";
    $ROW_AKA = "aka";
    $ROW_TYPE = "type";
    $ROW_COORDS = "coords";
    $ROW_BOOK_START = "book_start";
    $ROW_BOOK_END = "book_end";
    $ROW_BOOKS = "books";
    $ROW_EVENTS = "events";
    $ROW_PEOPLES = "peoples";
    $ROW_LOCATIONS = "locations";
    $ROW_SPECIALS = "specials";

    class ItemTable extends Module {
        private $title;
        private $rows;
        private $record;
        
        public function __construct($params = []) {
            parent::__construct();
            
            // Parse the parameters given
            $this->getParams($params);
        }
        
        private function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "title":
                        $this->setTitle($value);
                        break;
                    case "rows":
                        $this->setRows($value);
                        break;
                    case "record":
                        $this->setRecord($value);
                        break;
                }
            }
        }

        public function setTitle($title) {
            if (true) {
                // TODO: Check this is a valid value
                $this->title = $title;
            } else {
                // TODO: Throw an error
            }
        }

        public function setRows($rows) {
            if (true) {
                // TODO: Check this is a valid value
                $this->rows = $rows;
            } else {
                // TODO: Throw an error
            }
        }

        public function setRecord($record) {
            if (true) {
                // TODO: Check this is a valid value
                $this->record = $record;
            } else {
                // TODO: Throw an error
            }
        }
        
        public function getContent() {
            global $ROW_STRING;
            
            $table = new Table([
                "title" => $this->title
            ]);
            
            // Insert all the rows into a Row Module
            foreach($this->rows as $row) {
                // Use the title as is, but convert the data to a string
                $title = $row["title"];
                
                // Not all properties are always set, make sure to give them 
                // default values if they aren't
                $row_data = isset($row["data"]) ? $row["data"] : null;
                $row_type = isset($row["type"]) ? $row["type"]: $ROW_STRING;
                $row_hide = isset($row["hide-empty"]) ? $row["hide-empty"]: false;
                
                // Convert the data to the correct string
                $data = $this->getDataString($row_data, $row_type, $row_hide);
                
                if ($data !== "") {
                    $table->addContent($table->TableRow([
                        "title" => $title,
                        "data" => $data
                    ]));
                }
            }
            
            return $table->getContent();
        }
        
        public function getDataString($data, $type, $hide_empty) {   
            global $dict,
                   $ROW_STRING,
                   $ROW_NOTES,
                   $ROW_AKA,
                   $ROW_TYPE,
                   $ROW_COORDS,
                   $ROW_BOOK_START,
                   $ROW_BOOK_END,
                   $ROW_BOOKS,
                   $ROW_EVENTS,
                   $ROW_PEOPLES,
                   $ROW_LOCATIONS,
                   $ROW_SPECIALS;
            
            if (isset($this->record)) {
                $data = isset($data) ? $this->record->$data : $this->record;
                
                // If there is a record given, use those values instead                
                switch($type) {
                    case $ROW_NOTES:
                        $string = $this->getNotesString($data);
                        break;
                    
                    case $ROW_AKA:
                        $string = $this->getAkaString($data);
                        break;
                    
                    case $ROW_TYPE:
                        $string = $this->getTypeString($data);
                        break;
                    
                    case $ROW_COORDS:
                        $string = $this->getWorldmapString($data);
                        break;
                    
                    case $ROW_BOOK_START:
                    case $ROW_BOOK_END:
                        $string = $this->getBookLink($data, $type);
                        break;
                    
                    case $ROW_EVENTS:
                    case $ROW_PEOPLES:
                    case $ROW_LOCATIONS:
                    case $ROW_SPECIALS:
                        $string = $this->getLinksString($data, $type);
                        break;
                    
                    case $ROW_BOOKS:
                        $string = $this->getBooksString($data);
                        break;
                        
                    case $ROW_STRING:
                    default:
                        $string = $data;
                        break;
                }
            }
            
            if (($string === null) || 
                ($string === "-1") || 
                ($string === "") || 
                ($string === -1)) {
                // Hide the data if it's empty and hide-empty is set to true
                // If it's not set to true, show an 'unknown' message
                $string = $hide_empty === true ? "" : $dict["items.unknown"];
            }
            
            return $string;
        }
        
        function getBookString($data, $type) {
            global $dict, $ROW_BOOK_START, $ROW_BOOK_END;

            // The first or last appearance of this item
            $book_string = "";

            if($type == $ROW_BOOK_START && isset($data->book_start_id)) {
                $book_id = $dict["books.book_".$data->book_start_id];
                $book_chap = $data->book_start_chap;
                $book_vers = $data->book_start_vers;

                $book_string = $book_id." ".$book_chap.":".$book_vers;
            } else if ($type == $ROW_BOOK_END && isset($data->book_end_id)) {
                $book_id = $dict["books.book_".$data->book_end_id];
                $book_chap = $data->book_end_chap;
                $book_vers = $data->book_end_vers;

                $book_string = $book_id." ".$book_chap.":".$book_vers;
            }

            return $book_string;
        }

        function getBooksString($data) {
            global $ROW_BOOK_START, $ROW_BOOK_END;

            $books = [];

            if (isset($data->aka) && (count($data->aka) > 0)) {
                foreach($data->aka as $aka) {
                    // The beginning and end of the bible location
                    $book_start = $this->getBookString($aka, $ROW_BOOK_START);
                    $book_end = $this->getBookString($aka, $ROW_BOOK_END);

                    $books[] = $book_start." - ".$book_end;
                }
            }

            return implode("<br>", $books);
        }

        function getBookLink($data, $type) {
            global $ROW_BOOK_START, $ROW_BOOK_END;

            if($type == $ROW_BOOK_START && isset($data->book_start_id)) {
                $book_id = $data->book_start_id;
                $book_chap = $data->book_start_chap;
                $book_vers = $data->book_start_vers;
            } else if ($type == $ROW_BOOK_END && isset($data->book_end_id)) {
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
            $book_string = $this->getBookString($data, $type);

            return "<a 
                        href='{$book_link}'
                        target='_blank'
                        class='font-weight-bold'>
                        {$book_string}
                    </a>";
        }

        function getLinksString($data, $type) {
            global $dict,
                    $ROW_EVENTS,
                    $ROW_NEXT,
                    $ROW_PREVIOUS,
                    $ROW_PEOPLES,
                    $ROW_CHILDREN,
                    $ROW_PARENTS,
                    $ROW_LOCATIONS,
                    $ROW_SPECIALS;
            $links = [];

            $base_url = "";
            switch($type) {
                case $ROW_EVENTS:
                case $ROW_PREVIOUS:
                case $ROW_NEXT:
                    $base_url = setParameters("events/event/");
                    break;

                case $ROW_PARENTS:
                case $ROW_CHILDREN:
                case $ROW_PEOPLES:
                    $base_url = setParameters("peoples/people/");
                    break;

                case $ROW_LOCATIONS:
                    $base_url = setParameters("locations/location/");
                    break;

                case $ROW_SPECIALS:
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

                $links[] = '<a href="'.$href.'" class="font-weight-bold">'.$name.'</a>';
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
                    $sources[] = "
                        <sup class='font-weight-bold'>
                            <a target='_blank' href='".$source."'>
                                ".$total_num_sources++."
                            </a>
                        </sup>";
                }
                $sources_str = implode(" ", $sources);

                // Add the actual note and the sources together
                $notes[] = "<p>".$note->note." ".$sources_str."</p>";
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
                $akas[] = $name.$meaning_name;
            }

            // Put it all together
            $aka_str = implode("<br>", $akas);

            return $aka_str;
        }
        
        function getWorldmapString($data) {
            global $dict;

            $worldmap_string = "";

            // Make a link to Google maps if there are coordinates
            if (isset($data->coordinates) && $data->coordinates !== "") {
                $coord_x = explode(",", $data->coordinates)[0];
                $coord_y = explode(",", $data->coordinates)[1];

                $coords = implode(", ", [
                    number_format($coord_x, 2), 
                    number_format($coord_y, 2)
                    ]
                );

                // The general worldmap, but panned to this location
                $href = setParameters("worldmap")."?panTo=".$data->id;

                $worldmap_string = '
                        <a href="'.$href.'" target="_blank" 
                            data-toggle="tooltip" title="'.$dict["items.details.worldmap"].'" 
                            class="font-weight-bold">
                                '.$coords.' 
                        </a>';
            }

            return $worldmap_string;
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
    }

<?php

    namespace Classes;

    class Link {
        // TODO: Do this with the base.php file as well
        
        public const BOOKS_TO_NOTES = "getBookToNotes";
        public const EVENTS_TO_NEXT = "getEventToChildren";
        public const EVENTS_TO_PREV = "getEventToParents";
        public const EVENTS_TO_PEOPLES = "getEventToPeoples";
        public const EVENTS_TO_LOCATIONS = "getEventToLocations";
        public const EVENTS_TO_SPECIALS = "getEventToSpecials";
        public const EVENTS_TO_AKA = "getEventToAka";
        public const EVENTS_TO_NOTES = "getEventToNotes";
        public const ACTIVITIES_TO_AKA = "getActivityToAka";
        public const ACTIVITIES_TO_NOTES = "getActivityToNotes";
        public const PEOPLES_TO_PARENTS = "getPeopleToParents";
        public const PEOPLES_TO_CHILDREN = "getPeopleToChildren";
        public const PEOPLES_TO_EVENTS = "getPeopleToEvents";
        public const PEOPLES_TO_AKA = "getPeopleToAka";
        public const PEOPLES_TO_LOCATIONS = "getPeopleToLocations";
        public const PEOPLES_TO_NOTES = "getPeopleToNotes";
        public const LOCATIONS_TO_AKA = "getLocationToAka";
        public const LOCATIONS_TO_PEOPLES = "getLocationToPeoples";
        public const LOCATIONS_TO_EVENTS = "getLocationToEvents";
        public const LOCATIONS_TO_NOTES = "getLocationToNotes";
        public const SPECIALS_TO_EVENTS = "getSpecialToEvents";
        public const SPECIALS_TO_NOTES = "getSpecialToNotes";
        
        // The default tables
        public const TABLE_BOOKS = "books";
        public const TABLE_EVENTS = "events";
        public const TABLE_ACTIVITIES = "activitys";
        public const TABLE_PEOPLES = "peoples";
        public const TABLE_LOCATIONS = "locations";
        public const TABLE_SPECIALS = "specials";
        public const TABLE_NOTES = "notes";
        public const TABLE_SOURCES = "sources";

        // The linking tables
        public const TABLE_A2A = "activity_to_aka";
        public const TABLE_A2PA = "activity_to_parent";
        public const TABLE_A2E = "activity_to_event";
        public const TABLE_E2E = "event_to_aka";
        public const TABLE_E2PA = "event_to_parent";
        public const TABLE_P2A = "people_to_activity";
        public const TABLE_P2P = "people_to_aka";
        public const TABLE_P2PA = "people_to_parent";
        public const TABLE_P2L = "people_to_location";
        public const TABLE_L2L = "location_to_aka";
        public const TABLE_L2A = "location_to_activity";
        public const TABLE_N2S = "note_to_source";
        public const TABLE_N2I = "note_to_item";
        public const TABLE_S2A = "special_to_activity";

        // The type tables
        public const TABLE_TN = "type_note";
        public const TABLE_TI = "type_item";
        public const TABLE_TG = "type_gender";
        public const TABLE_TP = "type_people";
        public const TABLE_TT = "type_tribe";
        public const TABLE_TL = "type_location";
        public const TABLE_TS = "type_special";
        
        private $links = [];
        
        // The parent class and database class
        private $parent;
        private $database;
        
        public function __construct($parent) {
            $this->parent = $parent;
            $this->database = new Database();
        }
        
        public function setLinks($links) {
            $this->links = $links;
        }
        
        public function getLinks() {
            $links = [];
            
            foreach($this->links as $link) {                    
                // The $link variable is actually the name of the function
                // to get extra information
                if (method_exists($this, $link)) {
                    // If the function exists, execute it to get the data
                    // and add it to the array
                    $links[] = $this->$link();
                }
            }
            
            return $links;
        }

//                    case "people_to_aka":
//                        $columns = [
//                            "people_id" => false,
//                            "people_name" => true,
//                            "meaning_name" => true,
//                        ];
//                        $item_name = "people";
//                        break;
//
//                    case "location_to_aka":
//                        $columns = [
//                            "location_id" => false,
//                            "location_name" => true,
//                            "meaning_name" => true,
//                        ];
//                        $item_name = "location";
//                        break;
//
//                    case "notes":
//                        $columns = [
//                            "id" => false,
//                            "note" => true,
//                            "type" => false,
//                        ];
//                        break;
        
        private function getNotesItem() {
            // Get the current language
            $lang = $this->parent->getLang();
            
            // Create a new Note Item object
            $notes = new Item();
            $notes->setLang($lang);
            $notes->setTableName("notes");
            $notes->setTableColumns([
                "id",
                "note", 
                "type"
            ], "id");
            $notes->setLangColumns([
                "id",
                "note_id",
                "note",
                "lang"
            ], "note_id");
            
            return $notes;
        }
        
        private function parseNotes($data) {
            $notes = [];
            
            // check if more than 0 record found
            foreach($data as $row) {
                $note_id = $row["note_id"];

                if (!array_key_exists($note_id, $notes)) { 
                    // The note for this item isn't yet in the array                    
                    // Push the entire result in here
                    $notes[$note_id] = [
                        "id" => $row["note_id"],
                        "note" => $row["note"],
                        "sources" => array()
                    ];
                } 
                if (!is_null($row["source"])) {
                    // Push the new source in here
                    $notes[$note_id]["sources"][] = $row["source"];
                }
            }
            
            return $notes;
        }
        
        protected function getBookToNotes() {
            return $this->getItemToNotes("book");
        }
        
        protected function getEventToChildren($id) {

        }
        
        protected function getEventToParents($id) {

        }
        
        protected function getEventToPeoples($id) {

        }
        
        protected function getEventToLocations($id) {

        }
        
        protected function getEventToSpecials($id) {

        }
        
        protected function getEventToAka($id) {

        }
    
        protected function getEventToNotes($id) {
            return $this->getItemToNotes($id, "event");
        }
        
        protected function getActivityToAka($id) {

        }
        
        protected function getActivityToNotes($id) {
            return $this->getItemToNotes($id, "activity");
        }
        
        protected function getPeopleToParents($id) {

        }
        
        protected function getPeopleToChildren($id) {

        }
        
        protected function getPeopleToEvents($id) {

        }
        
        protected function getPeopleToAka($id) {

        }
        
        protected function getPeopleToLocations($id) {

        }
        
        protected function getPeopleToNotes($id) {
            return $this->getItemToNotes($id, "people");
        }

        protected function getLocationToAka($id) {

        }
        
        protected function getLocationToPeoples($id) {

        }
        
        protected function getLocationToEvents($id) {

        }
        
        protected function getLocationToNotes($id) {
            return $this->getItemToNotes($id, "location");
        }
        
        protected function getSpecialToEvents($id) {

        }
        
        protected function getSpecialToNotes($id) {
            return $this->getItemToNotes($id, "special");
        }
        
        private function getItemToNotes($type) {
            // Create a new notes item
            $note = $this->getNotesItem();
            
            // Get the item ID
            $id = $this->parent->getId();
            
            // Get translated tables for the notes table and the parent table
            $table_notes = $note->getTable();
            $table_parent = $this->parent->getTable();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT i.id, i.name, n.note, n.id AS note_id, s.source
                    FROM " . $table_parent . " i
                    JOIN " . self::TABLE_TI . " ti
                        ON ti.type_name = '". strtolower($type)."'
                    JOIN " . self::TABLE_N2I . " n2i
                        ON n2i.item_type = ti.type_id AND n2i.item_id = i.id
                    JOIN " . $table_notes . " n
                        ON n2i.note_id = n.id
                    LEFT JOIN " . self::TABLE_N2S . " n2s
                        ON n2s.note_id = n.id
                    LEFT JOIN " . self::TABLE_SOURCES . " s
                        ON s.id = n2s.source_id
                    WHERE
                        i.id = :id
                    ORDER BY
                        id ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            
            // Parse the data into something more useful
            $notes = $this->parseNotes($data);
            return ["notes", $notes];
        }
    }

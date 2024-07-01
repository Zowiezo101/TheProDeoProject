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
        
        // The types
        public const PEOPLES_TO_GENDER = "getPeopleToGender";
        public const PEOPLES_TO_TRIBE = "getPeopleToTribe";
        public const LOCATIONS_TO_TYPE = "getLocationToType";
        public const SPECIALS_TO_TYPE = "getSpecialToType";
        
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
        
        private function getEventsItem() {
            // Get the current language
            $lang = $this->parent->getLang();
            
            // Create a new Note Item object
            $events = new \Classes\Event();
            $events->setLang($lang);
            
            return $events;
        }
        
        private function getPeoplesItem() {
            // Get the current language
            $lang = $this->parent->getLang();
            
            // Create a new Note Item object
            $peoples = new \Classes\People();
            $peoples->setLang($lang);
            
            return $peoples;
        }
        
        public function getP2PItem() {
            // Get the current language
            $lang = $this->parent->getLang();
            
            // Create a new Note Item object
            $p2p = new Item();
            $p2p->setLang($lang);
            $p2p->setTable("people_to_aka", [
                "id",
                "people_id", 
                "people_name",
                "meaning_name"
            ], "id");
            $p2p->setTableLang([
                "id",
                "people_id",
                "people_name",
                "meaning_name",
                "lang"
            ], "people_id");
            
            return $p2p;
        }
        
        private function getLocationsItem() {
            // Get the current language
            $lang = $this->parent->getLang();
            
            // Create a new Note Item object
            $locations = new \Classes\Location();
            $locations->setLang($lang);
            
            return $locations;
        }
        
        public function getL2LItem() {
            // Get the current language
            $lang = $this->parent->getLang();
            
            // Create a new Note Item object
            $l2l = new Item();
            $l2l->setLang($lang);
            $l2l->setTable("location_to_aka", [
                "id",
                "location_id", 
                "location_name",
                "meaning_name"
            ], "id");
            $l2l->setTableLang([
                "id",
                "location_id",
                "location_name",
                "meaning_name",
                "lang"
            ], "location_id");
            
            return $l2l;
        }
        
        private function getSpecialsItem() {
            // Get the current language
            $lang = $this->parent->getLang();
            
            // Create a new Note Item object
            $specials = new \Classes\Special();
            $specials->setLang($lang);
            
            return $specials;
        }
        
        private function getNotesItem() {
            // Get the current language
            $lang = $this->parent->getLang();
            
            // Create a new Note Item object
            $notes = new Item();
            $notes->setLang($lang);
            $notes->setTable("notes", [
                "id",
                "note", 
                "type"
            ], "id");
            $notes->setTableLang([
                "id",
                "note_id",
                "note",
                "lang"
            ], "note_id");
            
            return $notes;
        }
        
        private function parseType($data) {
            $type_name = "";
            
            // Get the name stored in this type table
            if (count($data) > 0 && array_key_exists("type_name", $data[0])) {
                $type_name = $data[0]["type_name"];
            }
            
            // Return the name
            return $type_name;
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
        
        protected function getEventToChildren() {
            // Get the item ID
            $id = $this->parent->getId();
            
            // Get translated parent table
            $table = $this->parent->getTable();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    distinct(e.id) AS id, e.name
                FROM
                    {$table} e
                    LEFT JOIN
                        " . self::TABLE_E2PA . " e2pa
                            ON e2pa.event_id = e.id
                WHERE
                    e2pa.parent_id = :id
                ORDER BY
                    e.id ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["children", $data];

        }
        
        protected function getEventToParents() {            
            // Get the item ID
            $id = $this->parent->getId();
            
            // Get translated parent table
            $table = $this->parent->getTable();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    distinct(e.id) AS id, e.name
                FROM
                    {$table} e
                    LEFT JOIN
                        " . self::TABLE_E2PA . " e2pa
                            ON e2pa.parent_id = e.id
                WHERE
                    e2pa.event_id = :id
                ORDER BY
                    e.id ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["parents", $data];
        }
        
        protected function getEventToPeoples() {
            // Get the translated peoples item
            $people = $this->getPeoplesItem();
            
            // Get the item ID
            $id = $this->parent->getId();
            
            // Get translated table for the peoples table
            $table = $people->getTable();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    distinct(p2a.people_id) AS id, p.name AS name
                FROM
                    " . self::TABLE_P2A . " p2a
                    LEFT JOIN
                        " . self::TABLE_A2E . " a2e
                            ON a2e.activity_id = p2a.activity_id
                    LEFT JOIN
                        {$table} p
                            ON p2a.people_id = p.id
                WHERE
                    a2e.event_id = :id
                ORDER BY
                    p2a.people_id ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["peoples", $data];
        }
        
        protected function getEventToLocations() {
            // Get the translated locations item
            $location = $this->getLocationsItem();
            
            // Get the item ID
            $id = $this->parent->getId();
            
            // Get translated parent table
            $table = $location->getTable();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    distinct(l2a.location_id) AS id, l.name AS name
                FROM
                    " . self::TABLE_L2A . " l2a
                    LEFT JOIN
                        " . self::TABLE_A2E . " a2e
                            ON a2e.activity_id = l2a.activity_id
                    LEFT JOIN
                        {$table} l
                            ON l2a.location_id = l.id
                WHERE
                    a2e.event_id = :id
                ORDER BY
                    l2a.location_id ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["locations", $data];
        }
        
        protected function getEventToSpecials() {
            // Get the translated locations item
            $special = $this->getSpecialsItem();
            
            // Get the item ID
            $id = $this->parent->getId();
            
            // Get translated parent table
            $table = $special->getTable();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    distinct(s2a.special_id) AS id, s.name AS name
                FROM
                    " . self::TABLE_S2A . " s2a
                    LEFT JOIN
                        " . self::TABLE_A2E . " a2e
                            ON a2e.activity_id = s2a.activity_id
                    LEFT JOIN
                        {$table} s
                            ON s2a.special_id = s.id
                WHERE
                    a2e.event_id = :id
                ORDER BY
                    s2a.special_id ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["specials", $data];
        }
        
        protected function getEventToAka() {      
            // Get the item ID
            $id = $this->parent->getId();
            
            // select all query
            $query_params = [":event_id" => [$id, \PDO::PARAM_INT],
                             ":id" => [$id, \PDO::PARAM_INT],];
            $query_string = "SELECT
                        distinct(e2e.event_id), e2e.book_start_id,
                        e2e.book_start_chap, e2e.book_start_vers,
                        e2e.book_end_id, e2e.book_end_chap, 
                        e2e.book_end_vers
                    FROM
                        " . self::TABLE_E2E . " e2e
                    WHERE
                        e2e.event_id = :event_id
                    UNION    
                    SELECT
                        distinct(e.id), e.book_start_id,
                        e.book_start_chap, e.book_start_vers,
                        e.book_end_id, e.book_end_chap, 
                        e.book_end_vers
                    FROM
                        " . self::TABLE_EVENTS . " e
                    WHERE
                        e.id = :id
                    ORDER BY
                        book_start_id ASC, book_start_chap ASC, book_start_vers ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["aka", $data];
        }
    
        protected function getEventToNotes() {
            return $this->getItemToNotes("event");
        }
        
        protected function getActivityToAka() {

        }
        
        protected function getActivityToNotes() {
            return $this->getItemToNotes("activity");
        }
        
        protected function getPeopleToGender() {
            $id = $this->parent->getId();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    t.type_name
                FROM
                    " . self::TABLE_PEOPLES . " p
                LEFT JOIN " . self::TABLE_TG . " AS t 
                    ON p.gender = t.type_id
                WHERE
                    p.id = :id
                LIMIT
                    0,1";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            
            // Parse the data into something more useful
            $gender = $this->parseType($data);
            return ["gender", $gender];
        }
        
        protected function getPeopleToTribe() {
            $id = $this->parent->getId();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    t.type_name
                FROM
                    " . self::TABLE_PEOPLES . " p
                LEFT JOIN " . self::TABLE_TT . " AS t 
                    ON p.tribe = t.type_id
                WHERE
                    p.id = :id
                LIMIT
                    0,1";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            
            // Parse the data into something more useful
            $gender = $this->parseType($data);
            return ["tribe", $gender];
        }
        
        protected function getPeopleToChildren() {
            // Get the item ID
            $id = $this->parent->getId();
            
            // Get translated parent table
            $table = $this->parent->getTable();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    distinct(p.id) AS id, p.name
                FROM
                    {$table} p
                    LEFT JOIN
                        " . self::TABLE_P2PA . " p2pa
                            ON p2pa.people_id = p.id
                WHERE
                    p2pa.parent_id = :id
                ORDER BY
                    p.id ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["children", $data];
        }
        
        protected function getPeopleToParents() {
            // Get the item ID
            $id = $this->parent->getId();
            
            // Get translated parent table
            $table = $this->parent->getTable();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    distinct(p.id) AS id, p.name
                FROM
                    {$table} p
                    LEFT JOIN
                        " . self::TABLE_P2PA . " p2pa
                            ON p2pa.parent_id = p.id
                WHERE
                    p2pa.people_id = :id
                ORDER BY
                    p.id ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["parents", $data];
        }
        
        protected function getPeopleToEvents() {
            // Create a new notes item
            $event = $this->getEventsItem();
            
            // Get the item ID
            $id = $this->parent->getId();
            
            // Get translated table for the events table
            $table_events = $event->getTable();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    distinct(e.id), e.name
                FROM
                    {$table_events} e
                    LEFT JOIN
                        " . self::TABLE_A2E . " a2e
                            ON a2e.event_id = e.id
                    LEFT JOIN
                        " . self::TABLE_P2A . " p2a
                            ON p2a.activity_id = a2e.activity_id
                WHERE
                    p2a.people_id = :id
                ORDER BY
                    e.id ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            
            return ["events", $data];
        }
        
        protected function getPeopleToAka() {
            // Create a new aka item
            $aka = $this->getP2PItem();
            
            // Get the item ID
            $id = $this->parent->getId();
            
            // Get translated table for the aka table
            $table_aka = $aka->getTable();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    p2p.people_id as id, p2p.people_name as name, 
                    p2p.meaning_name as meaning_name
                FROM
                    {$table_aka} p2p
                WHERE
                    p2p.people_id = :id
                ORDER BY
                    p2p.people_name ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["aka", $data];
        }
        
        protected function getPeopleToLocations() {
            // Create a new locations item
            $location = $this->getLocationsItem();
            
            // Get the item ID
            $id = $this->parent->getId();
            
            // Get translated table for the locations table
            $table = $location->getTable();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    distinct(l.id), l.name, t.type_name as type
                FROM
                    {$table} l
                LEFT JOIN
                    " . self::TABLE_P2L . " p2l
                        ON p2l.location_id = l.id
                LEFT JOIN
                    " . self::TABLE_TP . " t
                        ON p2l.type = t.type_id
                WHERE
                    p2l.people_id = :id
                ORDER BY
                    l.id ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["locations", $data];
        }
        
        protected function getPeopleToNotes() {
            return $this->getItemToNotes("people");
        }
        
        protected function getLocationToType() {
            $id = $this->parent->getId();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    t.type_name
                FROM
                    " . self::TABLE_LOCATIONS . " l
                LEFT JOIN " . self::TABLE_TL . " AS t 
                    ON l.type = t.type_id
                WHERE
                    l.id = :id
                LIMIT
                    0,1";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            
            // Parse the data into something more useful
            $type = $this->parseType($data);
            return ["type", $type];
        }
        
        protected function getLocationToEvents() {
            // Create a new notes item
            $event = $this->getEventsItem();
            
            // Get the item ID
            $id = $this->parent->getId();
            
            // Get translated table for the events table
            $table_events = $event->getTable();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    distinct(e.id), e.name
                FROM
                    {$table_events} e
                    LEFT JOIN
                        " . self::TABLE_A2E . " a2e
                            ON a2e.event_id = e.id
                    LEFT JOIN
                        " . self::TABLE_L2A . " l2a
                            ON l2a.activity_id = a2e.activity_id
                WHERE
                    l2a.location_id = :id
                ORDER BY
                    e.id ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            
            return ["events", $data];
        }
        
        protected function getLocationToPeoples() {
            // Create a new peoples item
            $peoples = $this->getPeoplesItem();
            
            // Get the item ID
            $id = $this->parent->getId();
            
            // Get translated table for the peoples table
            $table = $peoples->getTable();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    distinct(p.id), p.name, t.type_name as type
                FROM
                    {$table} p
                LEFT JOIN
                    " . self::TABLE_P2L . " p2l
                        ON p2l.people_id = p.id
                LEFT JOIN
                    " . self::TABLE_TP . " t
                        ON p2l.type = t.type_id
                WHERE
                    p2l.location_id = :id
                ORDER BY
                    p.id ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["peoples", $data];
        }

        protected function getLocationToAka() {
            // Create a new aka item
            $aka = $this->getL2LItem();
            
            // Get the item ID
            $id = $this->parent->getId();
            
            // Get translated table for the aka table
            $table_aka = $aka->getTable();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    l2l.location_id as id, l2l.location_name as name, 
                    l2l.meaning_name as meaning_name
                FROM
                    {$table_aka} l2l
                WHERE
                    l2l.location_id = :id
                ORDER BY
                    l2l.location_name ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["aka", $data];
        }
        
        protected function getLocationToNotes() {
            return $this->getItemToNotes("location");
        }
        
        protected function getSpecialToEvents() {
            // Create a new notes item
            $event = $this->getEventsItem();
            
            // Get the item ID
            $id = $this->parent->getId();
            
            // Get translated table for the events table
            $table_events = $event->getTable();
            
            // select all query
            $query_params = [":id" => [$id, \PDO::PARAM_INT]];
            $query_string = "
                SELECT
                    distinct(e.id), e.name
                FROM
                    {$table_events} e
                    LEFT JOIN
                        " . self::TABLE_A2E . " a2e
                            ON a2e.event_id = e.id
                    LEFT JOIN
                        " . self::TABLE_S2A . " s2a
                            ON s2a.activity_id = a2e.activity_id
                WHERE
                    s2a.special_id = :id
                ORDER BY
                    e.id ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            
            return ["events", $data];
        }
        
        protected function getSpecialToNotes() {
            return $this->getItemToNotes("special");
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
                    FROM {$table_parent} i
                    JOIN " . self::TABLE_TI . " ti
                        ON ti.type_name = '". strtolower($type)."'
                    JOIN " . self::TABLE_N2I . " n2i
                        ON n2i.item_type = ti.type_id AND n2i.item_id = i.id
                    JOIN {$table_notes} n
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

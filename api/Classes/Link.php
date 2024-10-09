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
        public const ACTIVITIES_TO_AKA = "getActivitiesToAka";
        public const ACTIVITIES_TO_NOTES = "getActivitiesToNotes";
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
        public const TIMELINE_TO_AKA = "getTimelineToAka";
        public const TIMELINE_TO_NOTES = "getTimelineToNotes";
        public const FAMILYTREE_TO_NOTES = "getFamilytreeToNotes";
        
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
        private $data = null;
        
        // The parent class and database class
        private $parent;
        private $database;
        
        public function __construct($parent) {
            $this->parent = $parent;
            $this->database = new Database();
        }
        
        public function __destruct() {
            // Call the database destructor to close the database connection
            $this->database = null;
        }
        
        public function insertLinks(&$data) {
            // Store the data
            $this->data = $data;
            
            // Get the linking information of other tables
            $links = $this->getLinks();
            
            foreach ($links as [$link_id, $link_name, $link_data]) {
                // Get the index that has the correct item ID
                $index = $this->getRecordIndex($data, $link_id);
                
                // Insert the data
                $data[$index][$link_name] = $link_data;
            }
            
            // Remove the data
            $this->data = null;
        }
        
        public function setLinks($links) {
            $this->links = $links;
        }
        
        private function getLinks() {
            $links = [];
            
            foreach($this->links as $link) {                    
                // The $link variable is actually the name of the function
                // to get extra information
                if (method_exists($this, $link)) {
                    // If the function exists, execute it to get the data
                    // and add it to the array.
                    $link_data = $this->$link();
                    $this->addLinkData($link_data, $links);
                }
            }
            
            return $links;
        }
        
        private function addLinkData($data, &$array) {
            /* There are two possible data types:
             * 1. An array with the following values [id, name, data]
             * 2. An array containing multiple arrays of pt 1 [[id, name, data], 
             *                                                 [id, name, data]]
             * 
             * In case 1, create a new index. 
             * In case 2, merge the data.
             */
            if (is_array($data[0])) {
                $array = array_merge($data, $array);
            } else {
                $array[] = $data;
            }
        }
        
        private function getRecordIndex($data, $id) {
            $index = false;
            
            // Loop through all the items to find the one with the correct ID
            foreach($data as $key => $value) {
                if ($value["id"] === $id) {
                    $index = $key;
                    break;
                }
            }
            
            // Return the index if found, or false if not found
            return $index;
        }
        
        public function getActivitiesItem() {
            // Get the current language
            $lang = $this->parent->getLang();
            
            // Create a new Note Item object
            $activities = new \Classes\Activity();
            $activities->setLang($lang);
            
            return $activities;
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
            return [$id, "children", $data];

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
            return [$id, "parents", $data];
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
            return [$id, "peoples", $data];
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
            return [$id, "locations", $data];
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
            return [$id, "specials", $data];
        }
        
        protected function getEventToAka($id = null) {
            if ($id === null) {
                // Get the item ID
                $id = $this->parent->getId();
            }
            
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
            return [$id, "aka", $data];
        }
    
        protected function getEventToNotes($id = null) {
            return $this->getItemToNotes("event", $id);
        }
        
        protected function getActivitiesToAka() {
            // We need to loop over all the event IDs
            $ids = $this->getIds();
            
            // Get the AKA for each seperate event
            $data = array_map(function($id) {
                return $this->getActivityToAka($id);
            }, $ids);
            
            return $data;
        }
        
        protected function getActivityToAka($id = null) {
            if ($id === null) {
                // Get the item ID
                $id = $this->parent->getId();
            }
            
            // select all query
            $query_params = [":activity_id" => [$id, \PDO::PARAM_INT],
                             ":id" => [$id, \PDO::PARAM_INT],];
            $query_string = "
                SELECT
                    distinct(a2a.activity_id), a2a.book_start_id,
                    a2a.book_start_chap, a2a.book_start_vers,
                    a2a.book_end_id, a2a.book_end_chap, 
                    a2a.book_end_vers
                FROM
                    " . self::TABLE_A2A . " a2a
                WHERE
                    a2a.activity_id = :activity_id
                UNION    
                SELECT
                    distinct(a.id), a.book_start_id,
                    a.book_start_chap, a.book_start_vers,
                    a.book_end_id, a.book_end_chap, 
                    a.book_end_vers
                FROM
                    " . self::TABLE_ACTIVITIES . " a
                WHERE
                    a.id = :id
                ORDER BY
                    book_start_id ASC, book_start_chap ASC, book_start_vers ASC";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return [$id, "aka", $data];
        }
        
        protected function getActivityToNotes($id = null) {
            return $this->getItemToNotes("activity", $id);
        }
        
        protected function getActivitiesToNotes() {
            // We need to loop over all the event IDs
            $ids = $this->getIds();
            
            // Get the notes for each seperate event
            $data = array_map(function($id) {
                return $this->getActivityToNotes($id);
            }, $ids);
            
            return $data;
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
            return [$id, "gender", $gender];
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
            return [$id, "tribe", $gender];
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
            return [$id, "children", $data];
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
            return [$id, "parents", $data];
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
            
            return [$id, "events", $data];
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
            return [$id, "aka", $data];
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
            return [$id, "locations", $data];
        }
        
        protected function getPeopleToNotes($id = null) {
            return $this->getItemToNotes("people", $id);
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
            return [$id, "type", $type];
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
            
            return [$id, "events", $data];
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
            return [$id, "peoples", $data];
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
            return [$id, "aka", $data];
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
            
            return [$id, "events", $data];
        }
        
        protected function getSpecialToNotes() {
            return $this->getItemToNotes("special");
        }
        
        private function getItemToNotes($type, $id = null) {
            // Create a new notes item
            $note = $this->getNotesItem();
            
            if ($id === null) {
                // Get the item ID
                $id = $this->parent->getId();
            }
            
            // Get translated tables for the notes table and the parent table
            $table_notes = $note->getTable();
            $table_parent = $this->parent->getTable();
            
            // Delete the notes item and close unused database connection
            $note->__destruct();
            unset($note);
            
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
            return [$id, "notes", $notes];
        }
        
        protected function getTimelineToAka() {
            // We need to loop over all the event IDs
            $ids = $this->getIds();
            
            // Get the AKA for each seperate event
            $data = array_map(function($id) {
                return $this->getEventToAka($id);
            }, $ids);
            
            return $data;
        }
        
        protected function getTimelineToNotes() {
            // We need to loop over all the event IDs
            $ids = $this->getIds();
            
            // Get the notes for each seperate event
            $data = array_map(function($id) {
                return $this->getEventToNotes($id);
            }, $ids);
            
            return $data;
        }
        
        protected function getFamilytreeToNotes() {
            // We need to loop over all the people IDs
            $ids = $this->getIds();
            
            // Get the notes for each seperate people
            $data = array_map(function($id) {
                // TODO: Insert the array of IDs and use IN() instead of ID = 
                return $this->getPeopleToNotes($id);
            }, $ids);
            
            return $data;
        }
        
        private function getIds() {
            // Get all the IDs from the data
            $ids = array_map(function($item) {
                return $item["id"];
            }, $this->data);
            
            // Only return the unique values
            return array_unique($ids);
        }
    }

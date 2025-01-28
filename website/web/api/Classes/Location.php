<?php

    namespace Classes;

    class Location extends Item {
        
        public function __construct() {
            parent::__construct();
            
            $this->setTable("locations", [
                "order_id",
                "id",
                "name",
                "descr",
                "meaning_name",
                "type",
                "coordinates",
                "book_start_id",
                "book_start_chap",
                "book_start_vers",
                "book_end_id",
                "book_end_chap",
                "book_end_vers"
            ], "id");
            
            $this->setTableLang([
                "id",
                "location_id",
                "name",
                "descr",
                "meaning_name",
                "lang"
            ], "location_id");
            
            // Set other tables that we want to include in the result as well
            $this->setLinks([
                Link::LOCATIONS_TO_TYPE,
                Link::LOCATIONS_TO_EVENTS,
                Link::LOCATIONS_TO_PEOPLES,
                Link::LOCATIONS_TO_AKA,
                Link::LOCATIONS_TO_NOTES
            ]);
            
            // The following options are returned for the item type Location:
            // - All location types
            $this->setOptions([
                Options::LOCATION_TYPES,
            ]);
        }
    }

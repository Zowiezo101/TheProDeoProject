<?php

    namespace Classes;

    class Event extends Item {
    
        public function __construct() {
            parent::__construct();
            
            $this->setTable("events", [
                "order_id",
                "id", 
                "name", 
                "descr", 
                "length",
                "date",
                "book_start_id",
                "book_start_chap",
                "book_start_vers",
                "book_end_id",
                "book_end_chap",
                "book_end_vers"
            ], "id");
            
            $this->setTableLang([
                "id",
                "event_id",
                "name",
                "descr",
                "length",
                "date",
                "lang"
            ], "event_id");
            
            // Set other tables that we want to include in the result as well
            $this->setLinks([
                Link::EVENTS_TO_PREV,
                Link::EVENTS_TO_NEXT,
                Link::EVENTS_TO_PEOPLES,
                Link::EVENTS_TO_LOCATIONS,
                Link::EVENTS_TO_SPECIALS,
                Link::EVENTS_TO_AKA,
                Link::EVENTS_TO_NOTES
            ]);
        }
    }

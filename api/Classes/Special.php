<?php

    namespace Classes;

    class Special extends Item {
        
        public function __construct() {
            parent::__construct();
            
            $this->setTable("specials", [
                "order_id",
                "id",
                "name",
                "descr",
                "meaning_name",
                "type",
                "book_start_id",
                "book_start_chap",
                "book_start_vers",
                "book_end_id",
                "book_end_chap",
                "book_end_vers"
            ], "id");
            
            $this->setTableLang([
                "id",
                "special_id",
                "name",
                "descr",
                "meaning_name",
                "lang"
            ], "special_id");
            
            // Set other tables that we want to include in the result as well
            $this->setLinks([
                Link::SPECIALS_TO_TYPE,
                Link::SPECIALS_TO_EVENTS,
                Link::SPECIALS_TO_NOTES
            ]);
        }
    }

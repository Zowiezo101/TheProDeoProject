<?php

    namespace Classes;

    class Book extends Item {
    
        public function __construct() {
            parent::__construct();
            
            $this->setTable("books", [
                "order_id",
                "id", 
                "name", 
                "num_chapters", 
                "summary"
            ], "id");
            
            $this->setTableLang([
                "id",
                "book_id",
                "name",
                "summary",
                "lang"
            ], "book_id");
            
            // Set other tables that we want to include in the result as well
            $this->setLinks([Link::BOOKS_TO_NOTES]);
        }
        
        public function getReadOptionsQuery() {
            // The following options are returned for the item type Book:
            // - Min/Max number of chapters
            // - All book names & number of chapters
            
            // TODO:
//            select
//                1
//            from
//                dual
//            where
//                false
        }
    }

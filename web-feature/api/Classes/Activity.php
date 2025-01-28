<?php

    namespace Classes;

    class Activity extends Item {
    
        public function __construct() {
            parent::__construct();
            
            $this->setTable("activitys", [
                "order_id",
                "id", 
                "name", 
                "descr", 
                "length",
                "date",
                "level",
                "book_start_id",
                "book_start_chap",
                "book_start_vers",
                "book_end_id",
                "book_end_chap",
                "book_end_vers"
            ], "id");
            
            $this->setTableLang([
                "id",
                "activity_id",
                "name",
                "descr",
                "length",
                "date",
                "lang"
            ], "activity_id");
        }
    }

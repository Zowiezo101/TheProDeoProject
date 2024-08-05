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
    
        // search products
        // TODO:
//        function search($filters){
//            // utilities
//            $utilities = new utilities();
//
//            $params = $utilities->getParams($this->table_name, $filters, $this->conn);
//
//            // select all query
//            $query = "SELECT
//                        " . $params["columns"] . "
//                    FROM
//                        " . $this->table . " e
//                LEFT JOIN (SELECT 
//                        event_id, 
//                        book_start_id as min_book_id, 
//                        book_start_chap as min_book_chap, 
//                        book_start_vers as min_book_vers 
//                    FROM (SELECT
//                        0 as id, id as event_id, book_start_id, 
//                        book_start_chap, book_start_vers
//                    FROM
//                        events e
//                    UNION
//                    SELECT
//                        id, event_id, book_start_id, 
//                        book_start_chap, book_start_vers
//                    FROM
//                        event_to_aka e2e
//                    ORDER BY 
//                        event_id ASC, 
//                        book_start_id ASC, 
//                        book_start_chap ASC, 
//                        book_start_vers ASC) as event_books
//                    GROUP BY event_id) as min_books
//                    on min_books.event_id = e.id
//
//                LEFT JOIN (SELECT 
//                        event_id, 
//                        book_end_id as max_book_id, 
//                        book_end_chap as max_book_chap, 
//                        book_end_vers as max_book_vers 
//                    FROM (SELECT
//                        0 as id, id as event_id, book_end_id, 
//                        book_end_chap, book_end_vers
//                    FROM
//                        events e
//                    UNION
//                    SELECT
//                        id, event_id, book_end_id, 
//                        book_end_chap, book_end_vers
//                    FROM
//                            event_to_aka e2e
//                    ORDER BY 
//                        event_id ASC, 
//                        book_end_id DESC, 
//                        book_end_chap DESC, 
//                        book_end_vers DESC) as event_books
//                    GROUP BY event_id) as max_books
//                    on max_books.event_id = e.id
//
//                    ". $params["filters"] ."
//                    ORDER BY
//                        e.order_id ASC";
//
//            // prepare query statement
//            $stmt = $this->conn->prepare($query);
//
//            // bind
//            $i = 1;
//            foreach($params["values"] as $value) {
//                $stmt->bindValue($i++, $value);
//            }
//
//            // execute query
//            $stmt->execute();
//
//            return $stmt;
//        }
    }

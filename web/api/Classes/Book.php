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
        
        // search products
        // TODO
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
//                        " . $this->table . " b
//                    ". $params["filters"] ."
//                    ORDER BY
//                        b.order_id ASC";
//
//            // prepare query statement
//            $stmt = $this->conn->prepare($query);
//            $this->query = $query;
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

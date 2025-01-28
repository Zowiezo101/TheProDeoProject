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
    
//        // search products
//        // TODO:
//        function search($filters){
//            // utilities
//            $utilities = new utilities();
//            $params = $utilities->getParams($this->table_name, $filters, $this->conn);
//
//            // If there are any types available, do these first!
//            $types = null;
//            if(array_key_exists("types", $params)) {
//                $types = array();
//
//                foreach($params["types"] as $type) {
//                    $types[$type] = array();
//
//                    $query = "SELECT
//                                type_id, type_name
//                            FROM
//                                ".$type;
//
//                    // prepare query statement
//                    $stmt = $this->conn->prepare($query);
//
//                    // execute query
//                    $stmt->execute();
//
//                    // The amount of results
//                    $num = strval($stmt->rowCount());
//
//                    // get retrieved data
//                    $types[$type] = $stmt->fetchAll(PDO::FETCH_ASSOC);
//                    $types[$type][] = ["type_id" => $num, "type_name" => "search.all"];
//                }
//            }
//
//            // select all query
//            $query = "SELECT
//                        " . $params["columns"] . "
//                    FROM
//                        " . $this->table . " s";
//
//            if (strpos($params["columns"], "type") !== false) {
//                // We need this extra table when gender is needed
//                $query .= 
//                    " LEFT JOIN " . $this->table_type . " as it
//                        ON it.type_id = s.type
//                    ";
//            }
//
//            $query .= $params["filters"] . "
//                    ORDER BY
//                        s.order_id ASC";
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
//            return [$stmt, $types];
//        }
    }

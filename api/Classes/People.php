<?php

    namespace Classes;

    class People extends Item {
        
        public function __construct() {
            parent::__construct();
            
            $this->setTable("peoples", [
                "order_id",
                "id",
                "name",
                "descr",
                "meaning_name",
                "father_age",
                "mother_age",
                "age",
                "gender",
                "tribe",
                "profession",
                "nationality",
                "book_start_id",
                "book_start_chap",
                "book_start_vers",
                "book_end_id",
                "book_end_chap",
                "book_end_vers"
            ], "id");
            
            $this->setTableLang([
                "id",
                "people_id",
                "name",
                "descr",
                "meaning_name",
                "profession",
                "nationality",
                "lang"
            ], "people_id");
            
            // Set other tables that we want to include in the result as well
            $this->setLinks([
                Link::PEOPLES_TO_GENDER,
                Link::PEOPLES_TO_TRIBE,
                Link::PEOPLES_TO_PARENTS,
                Link::PEOPLES_TO_CHILDREN,
                Link::PEOPLES_TO_EVENTS,
                Link::PEOPLES_TO_LOCATIONS,
                Link::PEOPLES_TO_AKA,
                Link::PEOPLES_TO_NOTES
            ]);
        }
        
        protected function getReadPageQuery() {            
            // The translated table name
            $table = $this->getTable();
            
            // Query parameters
            $query_params = [
                ":page_start" => [self::PAGE_SIZE * $this->page, \PDO::PARAM_INT],
                ":page_size" => [self::PAGE_SIZE, \PDO::PARAM_INT]
            ];
            
            // Parts of the query
            $column_sql = $this->getColumnQuery($query_params);
            $where_sql = $this->getWhereQuery($query_params);
            $sort_sql = $this->getSortQuery();

            // Query string (where parameters will be plugged in)
            $query_string = "SELECT
                    {$column_sql}
                FROM
                    {$table} i
                {$where_sql}
                ORDER BY
                    {$sort_sql}
                LIMIT
                    :page_start, :page_size";
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];            
            return $query;
        }
        
        protected function getReadMapsQuery() {
            // The translated table name
            $table = $this->getTable();
            
            // Query parameters
            $query_params = [
                ":people_id" => [$this->id, \PDO::PARAM_INT],
                ":parent_id" => [$this->id, \PDO::PARAM_INT]
            ];
            
            // Query string (where parameters will be plugged in)
            $query_string = "
                WITH RECURSIVE cte (p1, p2) AS 
                    (
                        SELECT people_id, parent_id FROM people_to_parent WHERE people_id = :people_id
                        UNION ALL
                        SELECT people_id, parent_id FROM people_to_parent JOIN cte ON people_id = p2
                    )

                SELECT DISTINCT id, name FROM (
                    SELECT id, name FROM {$table} p
                        LEFT JOIN people_to_parent p2p
                        ON p.id = p2p.people_id 
                        WHERE p.id IN (SELECT p2 FROM cte)
                        AND parent_id IS NULL
                    UNION ALL
                    SELECT id, name FROM {$table} p
                        LEFT JOIN people_to_parent p1
                        ON p.id = p1.parent_id 
                        LEFT JOIN people_to_parent p2
                        ON p.id = p2.people_id
                        WHERE p1.parent_id = :parent_id
                        AND p1.people_id IS NOT NULL
                        AND p2.parent_id IS NULL
                        )
                AS ancestor";
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            return $query;
        }
    
//        // search products
//        // TODO
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
//                        " . $this->table . " p ";
//            if (strpos($params["columns"], $utilities->people_aka) !== false) {
//                $table = $utilities->getTable($this->base->table_p2p);
//
//                // We need this extra table when AKA is needed
//                $query .= 
//                    "LEFT JOIN " . $table . " as people_to_aka
//                        ON people_to_aka.people_id = p.id 
//                        AND people_to_aka.people_name LIKE ?
//                    ";
//            }
//
//            if (strpos($params["columns"], "gender") !== false) {
//                // We need this extra table when gender is needed
//                $query .= 
//                    "LEFT JOIN " . $this->table_gender . " AS g
//                        ON g.type_id = p.gender
//                    ";
//            }
//
//            if (strpos($params["columns"], "tribe") !== false) {
//                // We need this extra table when tribe is needed
//                $query .= 
//                    "LEFT JOIN " . $this->table_tribe . " AS t
//                        ON t.type_id = p.tribe
//                    ";
//            }
//
//            $query .= 
//                    $params["filters"]."
//                    ORDER BY
//                        p.order_id ASC";
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
        
        private function getColumnQuery(&$query_params) {
            $column_sql = "i.id, i.name";
            if (isset($this->filter) && ($this->filter !== "")) {
                $column_sql = "i.id, i.name, IF(people_name LIKE :aka, people_name, '') AS aka";
                $query_params[":aka"] = ['%'.$this->filter.'%', \PDO::PARAM_STR];
            }
            
            return $column_sql;
        }
        
        protected function getWhereQuery(&$query_params) {
            $where_sql = "";
            if (isset($this->filter) && ($this->filter !== "")) {
                // The Peoples to Aka item
                $p2p = $this->getP2PItem();
                $table_p2p = $p2p->getTable();
                
                $where_sql = "
                    LEFT JOIN 
                        {$table_p2p} p2p
                    ON 
                        p2p.people_id = i.id
                    AND 
                        p2p.people_name LIKE :name
                    WHERE 
                        name LIKE :filter_n 
                    OR 
                        people_name LIKE :filter_pn";
                
                $query_params[":name"] = ['%'.$this->filter.'%', \PDO::PARAM_STR];
                $query_params[":filter_n"] = ['%'.$this->filter.'%', \PDO::PARAM_STR];
                $query_params[":filter_pn"] = ['%'.$this->filter.'%', \PDO::PARAM_STR];
            }
            
            return $where_sql;
        }
        
    }

<?php

    namespace Classes;
    
    use Classes\Item;
    
    class Search {
        
        // The parent class 
        private $parent;
        
        // Store the columns to be returned with the result as well
        private $columns;
        
        // Filters for search page
        protected const FILTER_NAME = ["name" => FILTER_SANITIZE_SPECIAL_CHARS];
        protected const FILTER_MEANING_NAME = ["meaning_name" => FILTER_SANITIZE_SPECIAL_CHARS];
        protected const FILTER_DESCR = ["descr" => FILTER_SANITIZE_SPECIAL_CHARS];
        protected const FILTER_START_BOOK = ["start_book" => FILTER_VALIDATE_INT];
        protected const FILTER_START_CHAP = ["start_chap" => FILTER_VALIDATE_INT];
        protected const FILTER_END_BOOK = ["end_book" => FILTER_VALIDATE_INT];
        protected const FILTER_END_CHAP = ["end_chap" => FILTER_VALIDATE_INT];
        protected const FILTER_NUM_CHAPTERS = ["num_chapters" => FILTER_VALIDATE_INT];
        protected const FILTER_LENGTH = ["length" => FILTER_SANITIZE_SPECIAL_CHARS];
        protected const FILTER_DATE = ["date" => FILTER_SANITIZE_SPECIAL_CHARS];
        protected const FILTER_AGE = ["age" => FILTER_VALIDATE_INT];
        protected const FILTER_PARENT_AGE = ["parent_age" => FILTER_VALIDATE_INT];
        protected const FILTER_GENDER = ["gender" => FILTER_VALIDATE_INT];
        protected const FILTER_TRIBE = ["tribe" => FILTER_VALIDATE_INT];
        protected const FILTER_PROFESSION = ["profession" => FILTER_SANITIZE_SPECIAL_CHARS];
        protected const FILTER_NATIONALITY = ["nationality" => FILTER_SANITIZE_SPECIAL_CHARS];
        protected const FILTER_TYPE_LOCATION = ["type_location" => FILTER_VALIDATE_INT];
        protected const FILTER_TYPE_SPECIAL = ["type_special" => FILTER_VALIDATE_INT];
        
        public function __construct($parent) {
            $this->parent = $parent;
        }
        
        public function getOptionsQuery() {
            // TODO: Also retrieve the types tables for some reason?
            
            
            return [
                "string" => "",
                "params" => ""
            ];
        }
        
        public function getSearchQuery() {
            $table = $this->parent->getTable();
            
            // Query parameters
            $query_params = $this->getQueryParams();
            
            // Parts of the query
            $column_sql = $this->getColumnQuery($query_params);
            $where_sql = $this->getWhereQuery($query_params);

            // Query string (where parameters will be plugged in)
            $query_string = "SELECT
                    {$column_sql}
                FROM
                    {$table} i
                {$where_sql}
                ORDER BY
                    i.order_id ASC";
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];            
            return $query;
        }
        
        public function getOptionsFilter() {
            return [
                self::OPTIONAL_PARAMS => [],
                self::REQUIRED_PARAMS => [],
            ];
        }
        
        public function getSearchFilter() {
            return [
                Item::OPTIONAL_PARAMS => array_merge(
                    Item::FILTER_SEARCH,
                    self::FILTER_NAME,
                    self::FILTER_MEANING_NAME,
                    self::FILTER_DESCR,
                    self::FILTER_START_BOOK,
                    self::FILTER_START_CHAP,
                    self::FILTER_END_BOOK,
                    self::FILTER_END_CHAP,
                    self::FILTER_NUM_CHAPTERS,
                    self::FILTER_LENGTH,
                    self::FILTER_DATE,
                    self::FILTER_AGE,
                    self::FILTER_PARENT_AGE,
                    self::FILTER_GENDER,
                    self::FILTER_TRIBE,
                    self::FILTER_PROFESSION,
                    self::FILTER_NATIONALITY,
                    self::FILTER_TYPE_LOCATION,
                    self::FILTER_TYPE_SPECIAL
                ),
                Item::REQUIRED_PARAMS => [],
            ];
        }
        
        private function getQueryParams() {
            $query_params = [];
            
            $filters = $this->parent->getParameters();
            
            return $query_params;
        }
        
        private function getColumnQuery(&$query_params) {
            // Always get the name, bible location and ID
            $columns = ["i.id", "i.name"];
            
            if (array_search("book_start_id", $this->parent->getTableColumns()) !== false) {
                // The table for this type has the bible location as well
                $columns = array_merge($columns, [
                    "i.book_start_id", 
                    "i.book_start_chap", 
                    "i.book_start_vers", 
                    "i.book_end_id", 
                    "i.book_end_chap", 
                    "i.book_end_vers"
                ]);
            } else if ($this->parent->getTableName() === "events") {
                // TODO: This part needs to use AKA table, order by bible location and 
                // get the highest value for end and the lowest value for start
            }
            
            // Store the columns to be send with the reults
            $this->columns = $columns;
            
            // The column query part
            $column_query = join(", ", $columns);
            return $column_query;
        }
        
        private function getWhereQuery(&$query_params) {
            // Get all the filters that are given when requesting data
            $filters = $this->parent->getParameters();
            $wheres = [];
            
            foreach ($filters as $filter => $value) {
                // Some standard values to be reset for every iteration
                $column = "";
                $where = "";
                $query_param = [];
                
                // Every filter generates its own WHERE query
                switch($filter) {
                    case array_key_first(self::FILTER_NAME);
                        $column = filter;
                        $where = "i.name LIKE :name";
                        $query_param = [":name", '%'.$value.'%', \PDO::PARAM_STR];
                        break;
                    
                    case array_key_first(self::FILTER_MEANING_NAME):
                        $column = $filter;
                        $where = "i.meaning_name LIKE :meaning_name";
                        $query_param = [":meaning_name", '%'.$value.'%', \PDO::PARAM_STR];
                        break;
                }
                
                // Pretty much all filters only apply to a single column in 
                // the database. Make sure this column actually exists.
                if($this->checkColumn($column) !== false) {
                    // If a column exists, we can then add the where query and
                    // query parameters to their corresponding arrays
                    $wheres[] = $where;
                    
                    // Take the first value of query_param as the parameter name
                    // and use the rest as the parameter values
                    $query_params[array_shift($query_param)] = $query_param;
                }
            }
            $where_sql = (count($wheres) > 0) ? "WHERE " . join(" AND ", $wheres) : "";
            return $where_sql;
        }
        
        public function getColumns() {            
            // Remove the table names from the column names
            return array_map(function($column) {
                // Split the string and only send the part after the last dot
                $parts = explode(".", $column);
                        
                // Return the column name without the table name
                return end($parts);
            }, $this->columns);
        }
        
        private function checkColumn($column) {
            return array_search($column, $this->parent->getTableColumns());
        }
    }
    
    
//        // TODO
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
//            // TODO: Insert joins for type tables
//            // Like AKA, gender, tribe, location_type & special_type
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
    
//            if (strpos($params["columns"], $utilities->location_aka) !== false) {
//                $table = $utilities->getTable($this->base->table_l2l);
//
//                // We need this extra table when AKA is needed
//                $query .= 
//                    "LEFT JOIN " . $table . " as location_to_aka
//                        ON location_to_aka.location_id = l.id 
//                        AND location_to_aka.location_name LIKE ?
//                    ";
//            }
//            if (strpos($params["columns"], "type") !== false) {
//                // We need this extra table when gender is needed
//                $query .= 
//                    "LEFT JOIN " . $this->table_type . " as it
//                        ON it.type_id = l.type
//                    ";
//            }

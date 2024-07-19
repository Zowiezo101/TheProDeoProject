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
        }
        
        protected function getReadPageQuery() {            
            // The translated table name
            $table = $this->getTable();
            
            // Query parameters
            $query_params = [
                ":page_start" => [self::PAGE_SIZE * $this->parameters["page"], \PDO::PARAM_INT],
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
        
        private function getColumnQuery(&$query_params) {
            $column_sql = "i.id, i.name";
            if (isset($this->parameters["search"]) && 
                     ($this->parameters["search"] !== "")) {
                $column_sql = "i.id, i.name, IF(location_name LIKE :aka, location_name, '') AS aka";
                $query_params[":aka"] = ['%'.$this->parameters["search"].'%', \PDO::PARAM_STR];
            }
            
            return $column_sql;
        }
        
        protected function getWhereQuery(&$query_params) {
            $where_sql = "";
            if (isset($this->parameters["search"]) && 
                     ($this->parameters["search"] !== "")) {
                // The Locations to Aka item
                $l2l = $this->getL2LItem();
                $table_l2l = $l2l->getTable();
                
                $where_sql = "
                    LEFT JOIN 
                        {$table_l2l} l2l
                    ON 
                        l2l.location_id = i.id
                    AND 
                        l2l.location_name LIKE :name
                    WHERE 
                        name LIKE :filter_n 
                    OR 
                        location_name LIKE :filter_ln";
                
                $query_params[":name"] = ['%'.$this->parameters["search"].'%', \PDO::PARAM_STR];
                $query_params[":filter_n"] = ['%'.$this->parameters["search"].'%', \PDO::PARAM_STR];
                $query_params[":filter_ln"] = ['%'.$this->parameters["search"].'%', \PDO::PARAM_STR];
            }
            
            return $where_sql;
        }
        
    }

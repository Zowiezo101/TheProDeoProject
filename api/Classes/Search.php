<?php

    namespace Classes;
    
    use Classes\Item;
    
    class Search {
        
        // The parent class 
        private $parent;
        
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
            $filters = $this->parent->parameters;
        }
        
        private function getColumnQuery($query_params) {
            
        }
        
        private function getWhereQuery(&$query_params) {
            
        }
    }

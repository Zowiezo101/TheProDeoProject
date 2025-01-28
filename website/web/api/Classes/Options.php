<?php

    namespace Classes;

    class Options {
        
        public const CHAPTERS_MIN_MAX = "getChaptersMinMax";
        public const AGE_MIN_MAX = "getAgeMinMax";
        public const PARENT_AGE_MIN_MAX = "getParentAgeMinMax";
        public const GENDER_TYPES = "getGenderTypes";
        public const TRIBE_TYPES = "getTribeTypes";
        public const LOCATION_TYPES = "getLocationTypes";
        public const SPECIAL_TYPES = "getSpecialTypes";
        
        private $options = [];
        
        // The database class
        private $database;
        
        public function __construct() {
            $this->database = new Database();
        }
        
        public function __destruct() {
            // Call the database destructor to close the database connection
            $this->database = null;
        }
        
        public function setOptions($links) {
            $this->options = $links;
        }
        
        public function getOptions() {
            $options = [];
            
            foreach($this->options as $option) {                    
                // The $option variable is actually the name of the function
                // to get extra information
                if (method_exists($this, $option)) {
                    // If the function exists, execute it to get the data
                    // and add it to the array.
                    [$option_name, $option_data] = $this->$option();
                    $options[$option_name] = $option_data;
                }
            }
            
            return $options;
        }
        
        protected function getChaptersMinMax() {
            // select all query
            $query_params = [];
            $query_string = "SELECT 
                    MIN(num_chapters) AS min,
                    MAX(num_chapters) AS max
                FROM ".
                    Link::TABLE_BOOKS;
                
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["chapter_min_max", $data];
        }
        
        protected function getAgeMinMax() {
            // select all query
            $query_params = [];
            $query_string = "SELECT 
                    MIN(age) AS min,
                    MAX(age) AS max
                FROM 
                    ".Link::TABLE_PEOPLES."
                WHERE
                    age > -1";
                
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["age_min_max", $data];
        }
        
        protected function getParentAgeMinMax() {
            // select all query
            $query_params = [];
            $query_string = "SELECT 
                    GREATEST(
                        LEAST(
                            MIN(father_age), 
                            MIN(mother_age)
                        ), 0) AS min,
                    GREATEST(
                        MAX(father_age), 
                        MAX(mother_age)
                    ) AS max
                FROM 
                    ".Link::TABLE_PEOPLES."
                WHERE
                    father_age > -1 OR mother_age > -1";
                
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["parent_age_min_max", $data];
        }
        
        protected function getGenderTypes() {
            // select all query
            $query_params = [];
            $query_string = "SELECT 
                    type_id,
                    type_name
                FROM 
                    ".Link::TABLE_TG;
                
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["gender_types", $data];
        }
        
        protected function getTribeTypes() {
            // select all query
            $query_params = [];
            $query_string = "SELECT 
                    type_id,
                    type_name
                FROM 
                    ".Link::TABLE_TT;
                
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["tribe_types", $data];
        }
        
        protected function getLocationTypes() {
            // select all query
            $query_params = [];
            $query_string = "SELECT 
                    type_id,
                    type_name
                FROM 
                    ".Link::TABLE_TL;
                
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["location_types", $data];
            
        }
        
        protected function getSpecialTypes() {
            // select all query
            $query_params = [];
            $query_string = "SELECT 
                    type_id,
                    type_name
                FROM 
                    ".Link::TABLE_TS;
                
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            
            // Get the data from the database, using the query
            $data = $this->database->getData($query);
            return ["special_types", $data];
        }
    }

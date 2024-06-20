<?php

    namespace Classes;

    class Blog extends Item {
        
        // Parameters for this object
        protected $id;
        protected $title;
        protected $text;
        protected $user;
        protected $date;        
        
        // Param filters
        private const FILTER_TITLE = ["title" => FILTER_SANITIZE_SPECIAL_CHARS];
        private const FILTER_TEXT  = ["text" => FILTER_DEFAULT];
        private const FILTER_USER  = ["user" => FILTER_VALIDATE_INT];
        private const FILTER_DATE  = ["date" => FILTER_SANITIZE_SPECIAL_CHARS];
    
        public function __construct() {
            parent::__construct();
            
            $this->setTable("blog", [
                "id", 
                "title", 
                "text", 
                "user", 
                "date"
            ], "id");
        }
        
        // Overwrite the filter for creating objects
        protected function getCreateFilter() {
            return [
                self::OPTIONAL_PARAMS => [],
                self::REQUIRED_PARAMS => array_merge(
                    self::FILTER_TITLE,
                    self::FILTER_TEXT,
                    self::FILTER_USER,
                    self::FILTER_DATE,
                )
            ];
        }
        
        // Overwrite the query for creating objects
        protected function getCreateQuery() {            
            // The translated table name
            $table = $this->getTable();
                    
            // Query parameters
            $query_params = [
                ":title" => [$this->title, \PDO::PARAM_STR],
                ":text" => [$this->text, \PDO::PARAM_STR],
                ":user" => [$this->user, \PDO::PARAM_INT],
                ":date" => [$this->date, \PDO::PARAM_STR]
            ];
            
            // Query string (where parameters will be plugged in)
            $query_string = "
                INSERT INTO
                    " . $table . "
                SET
                    title=:title, 
                    text=:text, 
                    user=:user, 
                    date=:date";
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];            
            return $query;
        }
        
        // Overwrite the filter for updating objects
        protected function getUpdateFilter() {
            return [
                self::OPTIONAL_PARAMS => [],
                self::REQUIRED_PARAMS => array_merge(
                    self::FILTER_ID,
                    self::FILTER_TITLE,
                    self::FILTER_TEXT,
                ),
            ];
        }
        
        // Overwrite the query for updating objects
        protected function getUpdateQuery() {            
            // The translated table name
            $table = $this->getTable();
            
            // Query parameters
            $query_params = [
                ":id" => [$this->id, \PDO::PARAM_INT],
                ":title" => [$this->title, \PDO::PARAM_STR],
                ":text" => [$this->text, \PDO::PARAM_STR]
            ];
            
            // Query string (where parameters will be plugged in)
            $query_string = "UPDATE
                    " . $table . "
                SET
                    title=:title, text=:text
                WHERE
                    id = :id";
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];            
            return $query;
        }
        
        // Overwrite the filter for deleting objects
        protected function getDeleteFilter() {
            return [
                self::OPTIONAL_PARAMS => [],
                self::REQUIRED_PARAMS => array_merge(
                    self::FILTER_ID,
                ),
            ];
        }
        
        // Overwrite the query for deleting objects
        protected function getDeleteQuery() {
            // The translated table name
            $table = $this->getTable();
            
            // Query parameters
            $query_params = [":id" => [$this->id, \PDO::PARAM_INT]];
            
            // Query string (where parameters will be plugged in)
            $query_string = "DELETE
                FROM
                    " . $table . " b
                WHERE
                    b.id = :id";
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];            
            return $query;
        }
        
        protected function getReadAllFilter() {
            return [
                self::OPTIONAL_PARAMS => array_merge(
                    self::FILTER_USER,
                ),
                self::REQUIRED_PARAMS => [],
            ];
        }
        
        protected function getReadAllQuery() {
            // The translated table name
            $table = $this->getTable();
            
            // Query parameters
            if (isset($this->user) && ($this->user !== "")) {
                $where_sql = "WHERE u.id = :user";
                $query_params = [":user" => [$this->user, \PDO::PARAM_INT]];
            } else {
                $where_sql = "";
                $query_params = [];
            }

            // Query string (where parameters will be plugged in)
            $query_string = "SELECT
                    b.id, b.title, b.text, u.name, b.date
                FROM
                    " . $table . " b
                JOIN 
                    users u
                ON 
                    u.id = b.user
                {$where_sql}
                ORDER BY
                    b.id DESC";
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];            
            return $query;
        }
    }

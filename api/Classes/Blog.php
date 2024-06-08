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
        private const PARAM_ID    = ["id" => FILTER_VALIDATE_INT];
        private const PARAM_TITLE = ["title" => FILTER_SANITIZE_SPECIAL_CHARS];
        private const PARAM_TEXT  = ["text" => FILTER_DEFAULT];
        private const PARAM_USER  = ["user" => FILTER_VALIDATE_INT];
        private const PARAM_DATE  = ["date" => FILTER_SANITIZE_SPECIAL_CHARS];
    
        public function __construct() {
            parent::__construct();
            
            $this->setTableName("blog");
            $this->setTableColumns([
                "id", 
                "title", 
                "text", 
                "user", 
                "date"
            ], "id");
        }
        
        public function create() {
            $this->setRequiredParams(array_merge(
                self::PARAM_TITLE, 
                self::PARAM_TEXT,
                self::PARAM_USER, 
                self::PARAM_DATE
            ));
            
            parent::create();
        }
        
        public function update() {
            $this->setRequiredParams(array_merge(
                self::PARAM_ID, 
                self::PARAM_TITLE,
                self::PARAM_TEXT
            ));
            parent::update();
        }
        
        public function delete() {
            $this->setRequiredParams(array_merge(
                self::PARAM_ID
            ));
            parent::delete();
        }
        
        public function readOne() {
            $this->setRequiredParams(array_merge(
                self::PARAM_ID
            ));
            parent::readOne();
        }
        
        public function readAll() {
            $this->setOptionalParams(array_merge(
                self::PARAM_USER
            ));
            parent::readAll();
        }
        
        public function getQuery() {
            $query = [];
            
            // Select the function that corresponds with the action
            switch($this->getAction()) {
                case self::ACTION_CREATE:
                    $query = $this->getCreateQuery();
                    break;
                
                case self::ACTION_UPDATE:
                    $query = $this->getUpdateQuery();
                    break;
                
                case self::ACTION_DELETE:
                    $query = $this->getDeleteQuery();
                    break;
                
                case self::ACTION_READ_ONE:
                    $query = $this->getReadOneQuery();
                    break;
                
                case self::ACTION_READ_ALL:
                    $query = $this->getReadAllQuery();
                    break;
            }
            
            return $query;
        }
        
        private function getCreateQuery() {            
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
        
        private function getUpdateQuery() {            
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
        
        private function getDeleteQuery() {
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
        
        private function getReadOneQuery() {            
            // The translated table name
            $table = $this->getTable();
            
            // Query parameters
            $query_params = [":id" => [$this->id, \PDO::PARAM_INT]];
            
            // Query string (where parameters will be plugged in)
            $query_string = "SELECT
                    b.id, b.title, b.text, b.user, b.date
                FROM
                    " . $table . " b
                WHERE
                    b.id = :id
                LIMIT
                    0,1";
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];            
            return $query;
        }
        
        private function getReadAllQuery() {
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

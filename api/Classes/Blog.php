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
        
        private $table_name = "blog";
        private $table_id = "id";
        private $table_columns = [
            "id", 
            "title", 
            "text", 
            "user", 
            "date"
        ];
    
        public function __construct() {
            parent::__construct();
            
            $this->setTableName($this->table_name);
            $this->setTableColumns($this->table_columns, 
                                   $this->table_id);
            
            // Set the optional parameters for the following actions
            $this->setOptionalParams([
                self::ACTION_READ_ALL => array_merge(
                    self::PARAM_USER
                )
            ]);
            
            // Set the required parameters for the following actions
            $this->setRequiredParams([
                self::ACTION_CREATE => array_merge(
                    self::PARAM_TITLE,
                    self::PARAM_TEXT,
                    self::PARAM_USER,
                    self::PARAM_DATE
                ),
                self::ACTION_UPDATE => array_merge(
                    self::PARAM_ID,
                    self::PARAM_TITLE,
                    self::PARAM_TEXT
                ),
                self::ACTION_DELETE => array_merge(
                    self::PARAM_ID
                ),
                self::ACTION_READ_ONE => array_merge(
                    self::PARAM_ID
                )
            ]);
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
                ":title" => $this->title,
                ":text" => $this->text,
                ":user" => $this->user,
                ":date" => $this->date
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
                ":id" => $this->id,
                ":title" => $this->title,
                ":text" => $this->text
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
            $query_params = [":id" => $this->id];
            
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
            $query_params = [":id" => $this->id];
            
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
                $query_params = [":user" => $this->user];
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

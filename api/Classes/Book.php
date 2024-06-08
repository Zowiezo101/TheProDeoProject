<?php

    namespace Classes;

    class Book extends Item {
        
        // Parameters for this object
        protected $id;
        protected $sort;
        protected $filter;
        protected $page;
        
        // TODO:
        // Properties from another table
        public $notes;
        
        
        // Param filters
        private const PARAM_ID    = ["id" => FILTER_VALIDATE_INT];
        private const PARAM_SORT    = ["sort" => FILTER_SANITIZE_SPECIAL_CHARS];
        private const PARAM_FILTER = ["filter" => FILTER_SANITIZE_SPECIAL_CHARS];
        private const PARAM_PAGE  = ["page" => FILTER_VALIDATE_INT];
    
        public function __construct() {
            parent::__construct();
            
            $this->setTableName("books");
            $this->setTableColumns([
                "order_id",
                "id", 
                "name", 
                "num_chapters", 
                "summary"
            ], "id");
            $this->setLangColumns([
                "id",
                "book_id",
                "name",
                "summary",
                "lang"
            ], "book_id");
        }
        
        public function readOne() {
            $this->setRequiredParams(array_merge(
                self::PARAM_ID
            ));
            parent::readOne();
        }
        
        public function readPage() {
            $this->setOptionalParams(array_merge(
                self::PARAM_SORT,
                self::PARAM_FILTER
            ));
            
            $this->setRequiredParams(array_merge(
                self::PARAM_PAGE
            ));
            parent::readPage();
        }

        public function getQuery() {
            $query = [];
            
            // Select the function that corresponds with the action
            switch($this->getAction()) {                
                case self::ACTION_READ_ONE:
                    $query = $this->getReadOneQuery();
                    break;
                
                case self::ACTION_READ_PAGE:
                    $query = $this->getReadPageQuery();
                    break;
            }
            
            return $query;
        }
        
    // TODO: Get the desired columns from a var & function from the item objects
        private function getReadOneQuery() {     
            // TODO:
            // Set other tables that we want to include in the result as well
            $this->setLinks([Link::BOOKS_TO_NOTES]);
            
            // The translated table name
            $table = $this->getTable();
            
            // Query parameters
            $query_params = [":id" => [$this->id, \PDO::PARAM_INT]];
            
            // Query string (where parameters will be plugged in)
            $query_string = "SELECT
                    b.id, b.name, b.num_chapters, b.summary
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
        
        private function getReadPageQuery() {
            // The translated table name
            $table = $this->getTable();
            
            // Query parameters
            $query_params = [
                ":page_start" => [self::PAGE_SIZE * $this->page, \PDO::PARAM_INT],
                ":page_size" => [self::PAGE_SIZE, \PDO::PARAM_INT]
            ];
            
            // Parts of the query
            $where_sql = $this->getWhere($query_params);
            $sort_sql = $this->getSort();

            // Query string (where parameters will be plugged in)
            $query_string = "SELECT
                    b.id, b.name
                FROM
                    " . $table . " b
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
        
        public function getSort() {
            // If a sort different then the default is given
            switch($this->sort) {
                case '9_to_0':
                    $sort_sql = "b.order_id DESC";
                    break;
                case 'a_to_z':
                    $sort_sql = "b.name ASC";
                    break;
                case 'z_to_a':
                    $sort_sql = "b.name DESC";
                    break;

                case '0_to_9':
                default:
                    $sort_sql = "b.order_id ASC";
                    break;      
            }
            
            return $sort_sql;
        }
        
        public function getWhere(&$query_params) {
            $where_sql = "";
            if (isset($this->filter) && ($this->filter !== "")) {
                $where_sql = "WHERE name LIKE :filter";
                $query_params[":filter"] = ['%'.$this->filter.'%', \PDO::PARAM_STR];
            }
            
            return $where_sql;
        }
        
        // search products
        // TODO
        function search($filters){
            // utilities
            $utilities = new utilities();

            $params = $utilities->getParams($this->table_name, $filters, $this->conn);

            // select all query
            $query = "SELECT
                        " . $params["columns"] . "
                    FROM
                        " . $this->table . " b
                    ". $params["filters"] ."
                    ORDER BY
                        b.order_id ASC";

            // prepare query statement
            $stmt = $this->conn->prepare($query);
            $this->query = $query;

            // bind
            $i = 1;
            foreach($params["values"] as $value) {
                $stmt->bindValue($i++, $value);
            }

            // execute query
            $stmt->execute();

            return $stmt;
        }
    }

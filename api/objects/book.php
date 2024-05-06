<?php

require_once "../shared/item.php";

class book extends item {
    
    // object properties
    public $id;
    public $name;
    public $num_chapters;
    public $summary;
    
    // Properties from another table
    public $notes;
    
    // Allowed options
    protected $sort;
    protected $filter;
    protected $page;
  
    // constructor with $db as database connection
    public function __construct(){
        parent::__construct();
        
        $this->table_name = "books";
    }
    
    public function get_parameters($action) {
        $allowed_params = [];
        $required_params = [];
        
        // Not all parameters are allowed in all actions
        switch($action) {
            case "read_one":
                $required_params = [
                    "id" => FILTER_VALIDATE_INT,
                ];
                
                $allowed_params = [
                    "lang" => FILTER_SANITIZE_SPECIAL_CHARS,
                ];
                break;
            
            case "read_page":
                $required_params = [
                    "page" => FILTER_VALIDATE_INT,
                ];
                
                $allowed_params = [
                    "sort" => FILTER_SANITIZE_SPECIAL_CHARS,
                    "filter" => FILTER_SANITIZE_SPECIAL_CHARS,
                    "lang" => FILTER_SANITIZE_SPECIAL_CHARS,
                ];
                break;
        }
        
        return $this->check_parameters($required_params, $allowed_params);
    }

    // read products with pagination
    public function read_page(){
        $this->get_parameters("read_page");
        if ($this->error) {
            return false;
        }
        
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
        
        // Filtering on a name
        $filter_sql = "";
        if (isset($this->filter) && ($this->filter != "")) {
            $filter_sql = " WHERE name LIKE ? ";
            $filter = '%'.$this->filter.'%';
        }

        // select query
        $query = "SELECT
                    b.id, b.name
                FROM
                    " . $this->table_lang . " b
                ".$filter_sql."
                ORDER BY ".$sort_sql."
                LIMIT ?, ?";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        
        $this->query = $query;
        
        // This value is used in the SQL limit command
        $record_page_start = $this->records_per_page * $this->page;

        // bind variable values
        // TODO: Use type of param everywhere when binding
        $stmt->bindParam(1 + (isset($filter) ? 1 : 0), $record_page_start, PDO::PARAM_INT);
        $stmt->bindParam(2 + (isset($filter) ? 1 : 0), $this->records_per_page, PDO::PARAM_INT);
        if (isset($this->filter)) {
            $stmt->bindParam(1, $filter, PDO::PARAM_STR);
        }
        
        return $this->access_database($stmt);
    }
    
    // used when filling up the update product form
    function read_one(){
        global $BOOKS_TO_NOTES;
        
        $this->get_parameters("read_one");
        if ($this->error) {
            return false;
        }
        
        // Set other tables that we want to include in the result as well
        $this->set_linking_tables([$BOOKS_TO_NOTES]);

        // query to read single record
        $query = "SELECT
                    b.id, b.name, b.num_chapters, b.summary
                FROM
                    " . $this->table_lang . " b
                WHERE
                    b.id = ?
                LIMIT
                    0,1";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind id of product to be updated
        $stmt->bindParam(1, $this->id);
        
        return $this->access_database($stmt);
    }
    
    // search products
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
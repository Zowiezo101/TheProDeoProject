<?php

require_once "../shared/item.php";

class special extends item {
  
    // object properties
    public $id;
    public $descr;
    public $meaning_name;
    public $type;
    public $book_start_id;
    public $book_start_chap;
    public $book_start_vers;
    public $book_end_id;
    public $book_end_chap;
    public $book_end_vers;
    
    // Properties from another table
    public $events;
    public $notes;
    
    // Allowed options
    protected $sort;
    protected $filter;
    protected $page;
  
    // constructor with $db as database connection
    public function __construct(){
        parent::__construct();
        
        $this->table_name = "specials";
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
        
        // If a sort different than the default is given
        switch($this->sort) {
            case '9_to_0':
                $sort_sql = "s.book_start_id DESC, s.book_start_chap DESC, s.book_start_vers DESC";
                break;
            case 'a_to_z':
                $sort_sql = "s.name ASC";
                break;
            case 'z_to_a':
                $sort_sql = "s.name DESC";
                break;

            case '0_to_9':
            default:
                $sort_sql = "s.book_start_id ASC, s.book_start_chap ASC, s.book_start_vers ASC";
                break;
        }
        
        // Filtering on a name
        $filter_sql = "";
        if (isset($this->filter)) {
            $filter_sql = " WHERE name LIKE ? ";
            $filter = '%'.$this->filter.'%';
        }

        // select query
        $query = "SELECT
                    s.id, s.name
                FROM
                    " . $this->table_lang . " s
                ".$filter_sql."
                ORDER BY ".$sort_sql."
                LIMIT ?, ?";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        
        $this->query = $query;
        
        // This value is used in the SQL limit command
        $record_page_start = $this->records_per_page * $this->page;

        // bind variable values
        $stmt->bindParam(1 + (isset($filter) ? 1 : 0), $record_page_start, PDO::PARAM_INT);
        $stmt->bindParam(2 + (isset($filter) ? 1 : 0), $this->records_per_page, PDO::PARAM_INT);
        if (isset($filter)) {
            $stmt->bindParam(1, $filter, PDO::PARAM_STR);
        }
        
        return $this->access_database($stmt);
    }
    
    // used when filling up the update product form
    function read_one(){
        global  $SPECIALS_TO_EVENTS,
                $SPECIALS_TO_NOTES;
        
        $this->get_parameters("read_one");
        if ($this->error) {
            return false;
        }
        
        // Set other tables that we want to include in the result as well
        $this->set_linking_tables([
                $SPECIALS_TO_EVENTS,
                $SPECIALS_TO_NOTES
        ]);

        // query to read single record
        $query = "SELECT
                    s.id, s.name, s.descr, s.meaning_name,
                    t.type_name as type,
                    s.book_start_id, s.book_start_chap, s.book_start_vers, 
                    s.book_end_id, s.book_end_chap, s.book_end_vers
                FROM
                    " . $this->table_lang . " s
                LEFT JOIN type_special AS t 
                    ON s.type = t.type_id
                WHERE
                    s.id = ?
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
        
        // If there are any types available, do these first!
        $types = null;
        if(array_key_exists("types", $params)) {
            $types = array();
            
            foreach($params["types"] as $type) {
                $types[$type] = array();
                
                $query = "SELECT
                            type_id, type_name
                        FROM
                            ".$type;
                
                // prepare query statement
                $stmt = $this->conn->prepare($query);

                // execute query
                $stmt->execute();
                
                // The amount of results
                $num = strval($stmt->rowCount());

                // get retrieved data
                $types[$type] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $types[$type][] = ["type_id" => $num, "type_name" => "search.all"];
            }
        }

        // select all query
        $query = "SELECT
                    " . $params["columns"] . "
                FROM
                    " . $this->table . " s";
         
        if (strpos($params["columns"], "type") !== false) {
            // We need this extra table when gender is needed
            $query .= 
                " LEFT JOIN " . $this->table_type . " as it
                    ON it.type_id = s.type
                ";
        }
        
        $query .= $params["filters"] . "
                ORDER BY
                    s.order_id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind
        $i = 1;
        foreach($params["values"] as $value) {
            $stmt->bindValue($i++, $value);
        }

        // execute query
        $stmt->execute();

        return [$stmt, $types];
    }
}
<?php

require_once "../shared/item.php";

class event extends item {
  
    // object properties
    public $id;
    public $name;
    public $descr;
    public $length;
    public $date;
    public $book_start_id;
    public $book_start_chap;
    public $book_start_vers;
    public $book_end_id;
    public $book_end_chap;
    public $book_end_vers;
    
    // Properties from another table
    public $previous;
    public $next;
    public $peoples;
    public $locations;
    public $specials;
    public $aka;
    public $notes;
    
    // Allowed options
    protected $sort;
    protected $filter;
    protected $page;
  
    // constructor with $db as database connection
    public function __construct(){
        parent::__construct();
        
        $this->table_name = "events";
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
                $sort_sql = "e.book_start_id DESC, e.book_start_chap DESC, e.book_start_vers DESC";
                break;
            case 'a_to_z':
                $sort_sql = "e.name ASC";
                break;
            case 'z_to_a':
                $sort_sql = "e.name DESC";
                break;

            case '0_to_9':
            default:
                $sort_sql = "e.book_start_id ASC, e.book_start_chap ASC, e.book_start_vers ASC";
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
                    e.id, e.name
                FROM
                    " . $this->table_lang . " e
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
        global $EVENTS_TO_PREV,
                $EVENTS_TO_NEXT,
                $EVENTS_TO_PEOPLES,
                $EVENTS_TO_LOCATIONS,
                $EVENTS_TO_SPECIALS,
                $EVENTS_TO_AKA,
                $EVENTS_TO_NOTES;
        
        $this->get_parameters("read_one");
        if ($this->error) {
            return false;
        }
        
        // Set other tables that we want to include in the result as well
        $this->set_linking_tables([
                $EVENTS_TO_PREV,
                $EVENTS_TO_NEXT,
                $EVENTS_TO_PEOPLES,
                $EVENTS_TO_LOCATIONS,
                $EVENTS_TO_SPECIALS,
                $EVENTS_TO_AKA,
                $EVENTS_TO_NOTES
        ]);

        // query to read single record
        $query = "SELECT
                    e.id, e.name, e.descr, e.length,
                    e.date, e.book_start_id, e.book_start_chap,
                    e.book_start_vers, e.book_end_id, e.book_end_chap,
                    e.book_end_vers
                FROM
                    " . $this->table_lang . " e
                WHERE
                    e.id = ?
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
                    " . $this->table . " e
            LEFT JOIN (SELECT 
                    event_id, 
                    book_start_id as min_book_id, 
                    book_start_chap as min_book_chap, 
                    book_start_vers as min_book_vers 
                FROM (SELECT
                    0 as id, id as event_id, book_start_id, 
                    book_start_chap, book_start_vers
                FROM
                    events e
                UNION
                SELECT
                    id, event_id, book_start_id, 
                    book_start_chap, book_start_vers
                FROM
                    event_to_aka e2e
                ORDER BY 
                    event_id ASC, 
                    book_start_id ASC, 
                    book_start_chap ASC, 
                    book_start_vers ASC) as event_books
                GROUP BY event_id) as min_books
                on min_books.event_id = e.id

            LEFT JOIN (SELECT 
                    event_id, 
                    book_end_id as max_book_id, 
                    book_end_chap as max_book_chap, 
                    book_end_vers as max_book_vers 
                FROM (SELECT
                    0 as id, id as event_id, book_end_id, 
                    book_end_chap, book_end_vers
                FROM
                    events e
                UNION
                SELECT
                    id, event_id, book_end_id, 
                    book_end_chap, book_end_vers
                FROM
                        event_to_aka e2e
                ORDER BY 
                    event_id ASC, 
                    book_end_id DESC, 
                    book_end_chap DESC, 
                    book_end_vers DESC) as event_books
                GROUP BY event_id) as max_books
                on max_books.event_id = e.id

                ". $params["filters"] ."
                ORDER BY
                    e.order_id ASC";

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
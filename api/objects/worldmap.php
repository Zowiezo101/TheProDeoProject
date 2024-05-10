<?php

require_once "../shared/item.php";

class worldmap extends item {
  
    // object properties
    public $id;
    public $descr;
    public $meaning_name;
    public $type;
    public $coordinates;
    public $book_start_id;
    public $book_start_chap;
    public $book_start_vers;
    public $book_end_id;
    public $book_end_chap;
    public $book_end_vers;
    
    // Properties from another table
    public $notes;
    
    // Allowed options
    protected $sort;
    protected $filter;
    protected $page;
  
    // constructor with $db as database connection
    public function __construct(){
        parent::__construct();
        
        $this->table_name = "locations";
    }
    
    public function get_parameters($action) {
        $allowed_params = [];
        $required_params = [];
        
        // Not all parameters are allowed in all actions
        switch($action) {
            case "read_all":                
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
                $sort_sql = "l.book_start_id DESC, l.book_start_chap DESC, l.book_start_vers DESC";
                break;
            case 'a_to_z':
                $sort_sql = "l.name ASC";
                break;
            case 'z_to_a':
                $sort_sql = "l.name DESC";
                break;
            
            case '0_to_9':
            default:
                $sort_sql = "l.book_start_id ASC, l.book_start_chap ASC, l.book_start_vers ASC";
                break;
        }
        
        // Filtering on a name
        $filter_sql = "";
        if (isset($this->filter) && ($this->filter != "")) {
            $filter_sql = " AND name LIKE ? ";
            $filter = '%'.$this->filter.'%';
        }

        // select query
        $query = "SELECT
                    l.id, l.name
                FROM
                    " . $this->table_lang . " l
                WHERE 
                    coordinates IS NOT NULL AND
                    coordinates <> ''
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
        
        // Paging is for location pages and not for map pages
        // Use the query we are building here, or build our own
        // count function for the worldmap, timeline and familytree
        return $this->access_database($stmt);
    }
    
    // used for paging products
    function count(){
        
        // Filtering on a name
        $filter_sql = "";
        if (isset($this->filter) && ($this->filter != "")) {
            $filter_sql = " AND name LIKE ? ";
            $filter = '%'.$this->filter.'%';
        }

        // select query
        $query = "SELECT
                    COUNT(*) as total_rows
                FROM
                    " . $this->table_lang . " l
                WHERE 
                    coordinates IS NOT NULL AND
                    coordinates <> ''
                    ".$filter_sql;

        $stmt = $this->conn->prepare( $query );
        
        if (isset($filter)) {
            $stmt->bindParam(1, $filter, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }
    
    // used when filling up the update product form
    function read_all(){
        global $LOCATIONS_TO_NOTES;
        
        $this->get_parameters("read_all");
        if ($this->error) {
            return false;
        }
        
        // Set other tables that we want to include in the result as well
        $this->set_linking_tables([
                $LOCATIONS_TO_NOTES
        ]);
        
        // select query
        $query = "SELECT
                    l.id, l.name, l.descr,
                    l.meaning_name, aka.location_name AS aka,
                    l.type, l.coordinates
                FROM
                    ".$this->table_lang." l
                LEFT JOIN
                    (SELECT location_id, CONCAT('[', GROUP_CONCAT(
                        CASE
                            WHEN meaning_name IS NOT NULL AND meaning_name != ''
                                THEN CONCAT('{\"name\": \"', location_name, '\", \"meaning_name\": \"', meaning_name, '\"}')
                                ELSE CONCAT('{\"name\": \"', location_name, '\"}')
                                END SEPARATOR ', '
                            ), ']') AS location_name FROM location_to_aka
                        GROUP BY location_id) AS aka
                            ON aka.location_id = l.id
                WHERE
                    coordinates IS NOT NULL AND
                    coordinates <> ''";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        
        $this->query = $query;

        return $this->access_database($stmt);
    }
}
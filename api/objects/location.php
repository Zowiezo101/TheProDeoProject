<?php

require_once "../shared/item.php";

class location extends item {
  
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
    public $aka;
    public $peoples;
    public $events;
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
        $column = "";
        $filter_sql = "";
        $join = "";
        if (isset($this->filter) && ($this->filter != "")) {
            // Location AKA names
            $table = $this->utilities->getTable($this->utilities->table_l2l);
            
            $column = ", IF(location_name LIKE ?, location_name, '') AS aka";
            $filter_sql = " WHERE name LIKE ? OR location_name LIKE ?";
            $filter = '%'.$this->filter.'%';
            $join = " LEFT JOIN ".$table." l2l
                        ON l2l.location_id = l.id
                        AND l2l.location_name LIKE ?";
        }

        // select query
        $query = "SELECT
                    l.id, l.name".$column."
                FROM
                    " . $this->table_lang . " l
                ".$join.$filter_sql."
                ORDER BY ".$sort_sql."
                LIMIT ?, ?";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        
        $this->query = $query;
        
        // This value is used in the SQL limit command
        $record_page_start = $this->records_per_page * $this->page;

        // bind variable values
        $stmt->bindParam(1 + (isset($filter) ? 4 : 0), $record_page_start, PDO::PARAM_INT);
        $stmt->bindParam(2 + (isset($filter) ? 4 : 0), $this->records_per_page, PDO::PARAM_INT);
        if (isset($filter)) {
            $stmt->bindParam(1, $filter, PDO::PARAM_STR);
            $stmt->bindParam(2, $filter, PDO::PARAM_STR);
            $stmt->bindParam(3, $filter, PDO::PARAM_STR);
            $stmt->bindParam(4, $filter, PDO::PARAM_STR);
        }
        
        return $this->access_database($stmt);
    }
    
    // used when filling up the update product form
    function read_one(){
        global  $LOCATIONS_TO_AKA,
                $LOCATIONS_TO_PEOPLES,
                $LOCATIONS_TO_EVENTS,
                $LOCATIONS_TO_NOTES;
        
        $this->get_parameters("read_one");
        if ($this->error) {
            return false;
        }
        
        // Set other tables that we want to include in the result as well
        $this->set_linking_tables([
                $LOCATIONS_TO_AKA,
                $LOCATIONS_TO_PEOPLES,
                $LOCATIONS_TO_EVENTS,
                $LOCATIONS_TO_NOTES
        ]);

        // query to read single record
        $query = "SELECT
                    l.id, l.name, l.descr, l.meaning_name,
                    t.type_name as type, l.coordinates,
                    l.book_start_id, l.book_start_chap, l.book_start_vers, 
                    l.book_end_id, l.book_end_chap, l.book_end_vers
                FROM
                    " . $this->table_lang . " l
                LEFT JOIN type_location AS t 
                    ON l.type = t.type_id
                WHERE
                    l.id = ?
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
                    " . $this->table . " l ";
        if (strpos($params["columns"], $utilities->location_aka) !== false) {
            $table = $utilities->getTable($this->base->table_l2l);
            
            // We need this extra table when AKA is needed
            $query .= 
                "LEFT JOIN " . $table . " as location_to_aka
                    ON location_to_aka.location_id = l.id 
                    AND location_to_aka.location_name LIKE ?
                ";
        }
        if (strpos($params["columns"], "type") !== false) {
            // We need this extra table when gender is needed
            $query .= 
                "LEFT JOIN " . $this->table_type . " as it
                    ON it.type_id = l.type
                ";
        }
         
        $query .= 
                $params["filters"]."
                ORDER BY
                    l.order_id ASC";

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
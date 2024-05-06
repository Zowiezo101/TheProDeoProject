<?php

require_once "../shared/item.php";

class people extends item {
  
    // object properties
    public $id;
    public $name;
    public $descr;
    public $meaning_name;
    public $father_age;
    public $mother_age;
    public $age;
    public $gender;
    public $tribe;
    public $nationality;
    public $profession;
    public $book_start_id;
    public $book_start_chap;
    public $book_start_vers;
    public $book_end_id;
    public $book_end_chap;
    public $book_end_vers;
    
    // Properties from another table
    public $aka;
    public $parents;
    public $children;
    public $locations;
    public $events;
    public $notes;
    
    // Allowed options
    protected $sort;
    protected $filter;
    protected $page;
  
    // constructor with $db as database connection
    public function __construct(){
        parent::__construct();
        
        $this->table_name = "peoples";
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
            
            case "read_maps":
                $required_params = [
                    "id" => FILTER_VALIDATE_INT,
                ];
                
                $allowed_params = [
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
            case '0_to_9':
                $sort_sql = "p.book_start_id ASC, p.book_start_chap ASC, p.book_start_vers ASC";
                break;
            case '9_to_0':
                $sort_sql = "p.book_start_id DESC, p.book_start_chap DESC, p.book_start_vers DESC";
                break;
            case 'a_to_z':
                $sort_sql = "p.name ASC";
                break;
            case 'z_to_a':
                $sort_sql = "p.name DESC";
                break;

            case '0_to_9':
            default:
                 $sort_sql = "p.book_start_id ASC, p.book_start_chap ASC, p.book_start_vers ASC";
                 break;
        }
        
        // Filtering on a name
        $column = "";
        $filter_sql = "";
        $join = "";
        if (isset($this->filter)) {
            // People AKA names
            $table = $this->utilities->getTable($this->utilities->table_p2p);
        
            // Normally we can insert the AKA names as soon as we have the results 
            // from the regular table, but in this case we need them before
            // hand, as they affect the results we get
            $column = ", IF(people_name LIKE ?, people_name, '') AS aka";
            $filter_sql = " WHERE name LIKE ? OR people_name LIKE ?";
            $filter = '%'.$this->filter.'%';
            $join = " LEFT JOIN ".$table." p2p
                        ON p2p.people_id = p.id
                        AND p2p.people_name LIKE ?";
        }

        // TODO: Something goes wrong here, we get Lamech (id = 12) twice? (10/12)
        // select query
        $query = "SELECT
                    p.id, p.name".$column."
                FROM
                    " . $this->table_lang . " p
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
        global  $PEOPLES_TO_PARENTS,
                $PEOPLES_TO_CHILDREN,
                $PEOPLES_TO_EVENTS,
                $PEOPLES_TO_AKA,
                $PEOPLES_TO_LOCATIONS,
                $PEOPLES_TO_NOTES;
        
        $this->get_parameters("read_one");
        if ($this->error) {
            return false;
        }
        
        // Set other tables that we want to include in the result as well
        $this->set_linking_tables([
                $PEOPLES_TO_PARENTS,
                $PEOPLES_TO_CHILDREN,
                $PEOPLES_TO_EVENTS,
                $PEOPLES_TO_AKA,
                $PEOPLES_TO_LOCATIONS,
                $PEOPLES_TO_NOTES
        ]);

        // query to read single record
        $query = "SELECT
                    p.id, p.name, p.descr, p.meaning_name, p.father_age, 
                    p.mother_age, p.age, t1.type_name as gender, t2.type_name as tribe, 
                    p.nationality, p.profession,
                    p.book_start_id, p.book_start_chap, p.book_start_vers, 
                    p.book_end_id, p.book_end_chap, p.book_end_vers
                FROM
                    " . $this->table_lang . " p
                LEFT JOIN type_gender AS t1 
                    ON p.gender = t1.type_id
                LEFT JOIN type_tribe AS t2 
                    ON p.tribe = t2.type_id
                WHERE
                    p.id = ?
                LIMIT
                    0,1";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind id of product to be updated
        $stmt->bindParam(1, $this->id);
        
        return $this->access_database($stmt);
    }
    
    // used when filling up the update product form
    function read_maps(){
        $this->get_parameters("read_maps");
        if ($this->error) {
            return false;
        }

        // select all query
        $query = "WITH RECURSIVE cte (p1, p2) AS 
                    (
                        SELECT people_id, parent_id FROM people_to_parent WHERE people_id = ?
                        UNION ALL
                        SELECT people_id, parent_id FROM people_to_parent JOIN cte ON people_id = p2
                    )

                SELECT DISTINCT id, name FROM (
                    SELECT id, name FROM ".$this->table_lang." p
                        LEFT JOIN people_to_parent p2p
                        ON p.id = p2p.people_id 
                        WHERE p.id IN (SELECT p2 FROM cte)
                        AND parent_id IS NULL
                    UNION ALL
                    SELECT id, name FROM ".$this->table_lang." p
                        LEFT JOIN people_to_parent p1
                        ON p.id = p1.parent_id 
                        LEFT JOIN people_to_parent p2
                        ON p.id = p2.people_id
                        WHERE p1.parent_id = ? 
                        AND p1.people_id IS NOT NULL
                        AND p2.parent_id IS NULL
                        )
                AS ancestor";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind
        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->id);
        
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
                    " . $this->table . " p ";
        if (strpos($params["columns"], $utilities->people_aka) !== false) {
            $table = $utilities->getTable($this->base->table_p2p);
        
            // We need this extra table when AKA is needed
            $query .= 
                "LEFT JOIN " . $table . " as people_to_aka
                    ON people_to_aka.people_id = p.id 
                    AND people_to_aka.people_name LIKE ?
                ";
        }
        
        if (strpos($params["columns"], "gender") !== false) {
            // We need this extra table when gender is needed
            $query .= 
                "LEFT JOIN " . $this->table_gender . " AS g
                    ON g.type_id = p.gender
                ";
        }
        
        if (strpos($params["columns"], "tribe") !== false) {
            // We need this extra table when tribe is needed
            $query .= 
                "LEFT JOIN " . $this->table_tribe . " AS t
                    ON t.type_id = p.tribe
                ";
        }
         
        $query .= 
                $params["filters"]."
                ORDER BY
                    p.order_id ASC";

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
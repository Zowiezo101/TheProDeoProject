<?php

require_once "../shared/item.php";

class familytree extends item {
  
    // object properties
    public $id;
    public $name;
    public $meaning_name;
    public $descr;
    public $gender;
    
    // Properties from another table
    public $aka;
    public $notes;
    
    // The items of this map
    public $items;
    
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
        $filter_sql = "";
        if (isset($this->filter)) {
            $filter_sql = " AND name LIKE ? ";
            $filter = '%'.$this->filter.'%';
        }

        // select query
        $query = "SELECT
                    p.id, p.name
                FROM
                    " . $this->table_lang . " p
                WHERE 
                    id NOT IN (
                        SELECT people_id FROM people_to_parent WHERE parent_id IS NOT NULL)
                    AND  id IN (
                        SELECT parent_id FROM people_to_parent WHERE parent_id IS NOT NULL)
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
        global $PEOPLES_TO_NOTES;
        
        $this->get_parameters("read_one");
        if ($this->error) {
            return false;
        }
        
        // Set other tables that we want to include in the result as well
        $this->set_linking_tables([
                $PEOPLES_TO_NOTES
        ]);
        
        // query to read a familytree, starting from a single id
        // It uses a recursive function to keep finding children, until there
        // are no more children to be found
        $query = "WITH RECURSIVE ancestors AS 
                (
                    SELECT p.order_id, p.id, p.name, p.meaning_name, p.descr,
                        t.type_name AS gender, -1 as parent_id, aka.people_name AS aka,
                        1 AS level, 0 AS gen, 0 AS x, 0 AS y
                    FROM 
                        " . $this->table_lang . " p
                    LEFT JOIN
                        (SELECT people_id, CONCAT('[', GROUP_CONCAT(
                            CASE
                                WHEN meaning_name IS NOT NULL AND meaning_name != ''
                                    THEN CONCAT('{\"name\": \"', people_name, '\", \"meaning_name\": \"', meaning_name, '\"}')
                                    ELSE CONCAT('{\"name\": \"', people_name, '\"}')
                                    END SEPARATOR ', '
                                ), ']') AS people_name FROM people_to_aka
                            GROUP BY people_id) AS aka
                                ON aka.people_id = p.id
                    LEFT JOIN
                        type_gender AS t
                            ON p.gender = t.type_id
                    WHERE
                        p.id = ?

                    UNION DISTINCT

                    SELECT p.order_id, p.id, p.name, p.meaning_name, p.descr,
                        t.type_name AS gender, p2p.parent_id, aka.people_name AS aka,
                        1 AS level, gen+1, 0 AS x, 0 AS y
                    FROM 
                        " . $this->table_lang . " p
                    LEFT JOIN
                        people_to_parent p2p
                            ON p.id = p2p.people_id
                    JOIN
                        ancestors a
                            ON a.id = p2p.parent_id
                    LEFT JOIN
                        (SELECT people_id, CONCAT('[', GROUP_CONCAT(
                            CASE
                                WHEN meaning_name IS NOT NULL AND meaning_name != ''
                                    THEN CONCAT('{\"name\": \"', people_name, '\", \"meaning_name\": \"', meaning_name, '\"}')
                                    ELSE CONCAT('{\"name\": \"', people_name, '\"}')
                                    END SEPARATOR ', '
                                ), ']') AS people_name FROM people_to_aka
                            GROUP BY people_id) AS aka
                                ON aka.people_id = p.id
                    LEFT JOIN
                        type_gender AS t
                            ON p.gender = t.type_id
                )

                SELECT distinct(order_id), id, name, meaning_name, descr,
                    gender, parent_id, aka,
                    level, gen, x, y FROM ancestors
                ORDER BY
                    gen ASC, parent_id ASC, order_id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind id of product to be updated
        $stmt->bindParam(1, $this->id);
        
        return $this->access_database($stmt);
    }
    
    // search products
    function search($filters){

        // select all query
        $query = "WITH RECURSIVE cte (p1, p2) AS 
                    (
                        SELECT people_id, parent_id FROM people_to_parent WHERE people_id = ?
                        UNION ALL
                        SELECT people_id, parent_id FROM people_to_parent JOIN cte ON people_id = p2
                    )

                SELECT DISTINCT id, name FROM (
                    SELECT id, name FROM ".$this->table." 
                        LEFT JOIN people_to_parent 
                        ON peoples.id = people_to_parent.people_id 
                        WHERE peoples.id IN (SELECT p2 FROM cte)
                        AND parent_id IS NULL
                    UNION ALL
                    SELECT id, name FROM ".$this->table." 
                        LEFT JOIN people_to_parent p1
                        ON peoples.id = p1.parent_id 
                        LEFT JOIN people_to_parent p2
                        ON peoples.id = p2.people_id
                        WHERE p1.parent_id = ? 
                        AND p1.people_id IS NOT NULL
                        AND p2.parent_id IS NULL
                        )
                AS ancestor";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind
        $stmt->bindParam(1, $filters);
        $stmt->bindParam(2, $filters);

        // execute query
        $stmt->execute();

        return $stmt;
    }
}
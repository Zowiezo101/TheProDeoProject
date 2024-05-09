<?php

require_once "../shared/item.php";

class timeline extends item {
  
    // object properties
    public $id;
    public $name;
    public $descr;
    public $date;
    public $length;
    public $book_start_id;
    public $book_start_chap;
    public $book_start_vers;
    public $book_end_id;
    public $book_end_chap;
    public $book_end_vers;
    
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
        if (isset($this->filter)) {
            $filter_sql = " WHERE name LIKE ? ";
            $filter = '%'.$this->filter.'%';
        }

        // select query
        $query = "SELECT * FROM (
                    SELECT * FROM (SELECT -999 AS id, 'timeline.global' as name) AS e1
                    UNION ALL
                    SELECT * FROM (
                        SELECT
                            e.id, e.name
                        FROM
                            " . $this->table_lang . " e
                        ".$filter_sql."
                        ORDER BY ".$sort_sql." ) AS e2
                ) AS e
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
        global $EVENTS_TO_NOTES,
               $EVENTS_TO_AKA,
               $ACTIVITIES_TO_NOTES,
               $ACTIVITIES_TO_AKA;
        
        $this->get_parameters("read_one");
        if ($this->error) {
            return false;
        }
        
        // There are two options here:
        // 1. The global timeline is selected, this consists out of all the 
        // events and shows an overview of the different events (id = -999)
        // 2. An event is selected, which consists out of activities and 
        // shows a more detailed timeline of this specific event (id != -999)
        if ($this->id === -999) {
        
            // Set other tables that we want to include in the result as well
            $this->set_linking_tables([
                    $EVENTS_TO_NOTES,
                    $EVENTS_TO_AKA
            ]);
            
            $query = "WITH RECURSIVE ancestors AS 
                    (
                        SELECT e.order_id, e.id, e.name, 
                            e.descr, e.date, e.length, 
                            e.book_start_id, e.book_start_chap, e.book_start_vers,
                            e.book_end_id, e.book_end_chap, e.book_end_vers, 
                            -999 AS parent_id, 1 AS level, 1 AS gen, 0 AS x, 0 AS y
                        FROM 
                            " . $this->table_lang . " e
                        LEFT JOIN
                            event_to_parent e2e
                                ON e.id = e2e.event_id
                        WHERE
                            e2e.parent_id is null

                        UNION DISTINCT

                        SELECT e.order_id, e.id, e.name, 
                            e.descr, e.date, e.length, 
                            e.book_start_id, e.book_start_chap, e.book_start_vers,
                            e.book_end_id, e.book_end_chap, e.book_end_vers, 
                            e2e.parent_id, 1 as level, gen+1, 0 AS x, 0 AS y
                        FROM 
                            " . $this->table_lang . " e
                        LEFT JOIN
                            event_to_parent e2e
                                ON e.id = e2e.event_id
                        JOIN
                            ancestors a
                                ON a.id = e2e.parent_id
                    )

                    SELECT -999 AS order_id, -999 AS id, 'global.timeline' AS name, 
                        '' AS descr, '' AS date, '' AS length, 
                        '' AS book_start_id, '' AS book_start_chap, '' AS book_start_vers,
                        '' AS book_end_id, '' AS book_end_chap, '' AS book_end_vers, 
                        -1 AS parent_id, 1 AS level, 0 AS gen, 0 AS x, 0 AS y

                    UNION ALL

                    SELECT distinct(order_id), id, name, 
                        descr, date, length, 
                        book_start_id, book_start_chap, book_start_vers,
                        book_end_id, book_end_chap, book_end_vers, 
                        parent_id, level, gen, x, y FROM ancestors
                    ORDER BY
                        gen ASC, parent_id ASC, order_id ASC";

            // prepare query statement
            $stmt = $this->conn->prepare( $query );
        
            $this->query = $query;

            return $this->access_database($stmt);
            
        } else {
        
            // Set other tables that we want to include in the result as well
            $this->set_linking_tables([
                    $ACTIVITIES_TO_NOTES,
                    $ACTIVITIES_TO_AKA
            ]);
            
            $table_lang = $this->utilities->getTable("activitys");
            
//                    TODO: AKA & notes for the event is not available
//                    There are AKAs & notes for the activities however
            
            $query = "WITH RECURSIVE ancestors AS 
                    (
                        SELECT a.order_id, a.id, a.name, 
                            a.descr, a.date, a.length, 
                            a.book_start_id, a.book_start_chap, a.book_start_vers,
                            a.book_end_id, a.book_end_chap, a.book_end_vers, 
                            -999 AS parent_id, a.level, 1 AS gen, 0 AS x, 0 AS y
                        FROM 
                            ".$table_lang." a
                        LEFT JOIN
                            activity_to_parent a2a
                                ON a.id = a2a.activity_id
                        LEFT JOIN
                            activity_to_event a2e
                                ON a.id = a2e.activity_id
                        WHERE
                            a2e.event_id = ? AND
                            a2a.parent_id is null

                        UNION DISTINCT

                        SELECT a.order_id, a.id, a.name, 
                            a.descr, a.date, a.length, 
                            a.book_start_id, a.book_start_chap, a.book_start_vers,
                            a.book_end_id, a.book_end_chap, a.book_end_vers, 
                            a2a.parent_id, a.level, gen+1, 0 AS x, 0 AS y
                        FROM 
                            ".$table_lang." a
                        LEFT JOIN
                            activity_to_parent a2a
                                ON a.id = a2a.activity_id
                        LEFT JOIN
                            activity_to_event a2e
                                ON a.id = a2e.activity_id
                        INNER JOIN
                            ancestors an
                                ON an.id = a2a.parent_id
                    )

                    SELECT -999 AS order_id, -999 AS id, e.name, 
                        e.descr, e.date, e.length, 
                        e.book_start_id, e.book_start_chap, e.book_start_vers,
                        e.book_end_id, e.book_end_chap, e.book_end_vers, 
                        -1 AS parent_id, 1 AS level, 0 AS gen, 0 AS x, 0 AS y 
                    FROM ".$this->table_lang." e
                        WHERE e.id = ?

                    UNION ALL

                    SELECT order_id, id, name, 
                        descr, date, length, 
                        book_start_id, book_start_chap, book_start_vers,
                        book_end_id, book_end_chap, book_end_vers, 
                        parent_id, level, gen, x, y FROM ancestors
                    ORDER BY
                        gen ASC, parent_id ASC, order_id ASC";

            // prepare query statement
            $stmt = $this->conn->prepare( $query );
        
            $this->query = $query;

            // bind id of product to be updated
            $stmt->bindParam(1, $this->id);
            $stmt->bindParam(2, $this->id);

            return $this->access_database($stmt);
        }
    }
}
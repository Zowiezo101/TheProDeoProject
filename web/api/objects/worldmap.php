<?php

require_once "../shared/base.php";

class worldmap {
  
    // database connection and table name
    private $conn;
    private $base;
    private $table_name = "locations";
    public $item_name = "Worldmap";
  
    // object properties
    public $items;
    public $notes;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        $this->base = new base($db);
    }

    // read products with pagination
    public function readPaging($from_record_num, $records_per_page, $sort, $filter){        
        // The sorting for the pages
        $sort_sql = "l.book_start_id ASC, l.book_start_chap ASC, l.book_start_vers ASC";
        
        // If a sort different than the default is given
        if($sort !== null) { 
           switch($sort) {
               case '0_to_9':
                   $sort_sql = "l.book_start_id ASC, l.book_start_chap ASC, l.book_start_vers ASC";
                   break;
               case '9_to_0':
                   $sort_sql = "l.book_start_id DESC, l.book_start_chap DESC, l.book_start_vers DESC";
                   break;
               case 'a_to_z':
                   $sort_sql = "l.name ASC";
                   break;
               case 'z_to_a':
                   $sort_sql = "l.name DESC";
                   break;
           }
        }
        
        // Filtering on a name
        $filter_sql = "";
        if (isset($filter)) {
            $filter_sql = " AND name LIKE ? ";
            $filter = '%'.$filter.'%';
        }

        // select query
        $query = "SELECT
                    l.id, l.name
                FROM
                    " . $this->table_name . " l
                WHERE 
                    coordinates IS NOT NULL AND
                    coordinates <> ''
                    ".$filter_sql."
                ORDER BY ".$sort_sql."
                LIMIT ?, ?";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind variable values
        $stmt->bindParam(1 + (isset($filter) ? 1 : 0), $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2 + (isset($filter) ? 1 : 0), $records_per_page, PDO::PARAM_INT);
        if (isset($filter)) {
            $stmt->bindParam(1, $filter, PDO::PARAM_STR);
        }

        // execute query
        $stmt->execute();

        // return values from database
        return $stmt;
    }
    
    // used for paging products
    public function count($filter){
        
        // Filtering on a name
        $filter_sql = "";
        if (isset($filter)) {
            $filter_sql = " AND name LIKE ? ";
            $filter = '%'.$filter.'%';
        }
        
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . " WHERE 
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
    function read(){
        
        // select query
        $query = "SELECT
                    l.id, l.name, l.descr,
                    l.meaning_name, aka.location_name AS aka,
                    l.type, l.coordinates
                FROM
                    " . $this->table_name . " l
                LEFT JOIN
                    (SELECT location_id, CONCAT('[', GROUP_CONCAT(
                        CASE
                            WHEN meaning_name IS NOT NULL AND meaning_name != ''
                                THEN CONCAT('{\"name\": \"', location_name, '\", \"meaning_name\": \"', meaning_name, '\"}')
                                ELSE CONCAT('{\"name\": \"', location_name, '\"}')
                        END SEPARATOR ', '
                    ), ']') AS location_name FROM location_to_aka) AS aka
                        ON aka.location_id = l.id
                WHERE
                    coordinates IS NOT NULL AND
                    coordinates <> ''";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // execute query
        $stmt->execute();
        
        // Get the results
        $location_arr = $this->base->getResults($stmt);
        $this->items = $location_arr;
        
        // Get the notes of all the locations as well
        $ids = array_map(function($location) { 
            return $location["id"]; 
        }, $location_arr);
        $this->notes = $this->base->getItemsToNotes($ids, "location");
    }
}
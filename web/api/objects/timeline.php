<?php

require_once "../shared/base.php";

class timeline {
  
    // database connection and table name
    private $conn;
    private $base;
    private $table_name = "events";
    public $item_name = "Timeline";
  
    // object properties
    public $id;
    public $name;
    public $gender;
    public $items;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        $this->base = new base($db);
    }

    // read products with pagination
    public function readPaging($from_record_num, $records_per_page, $sort, $filter){        
        // The sorting for the pages
        $sort_sql = "e.book_start_id asc, e.book_start_chap asc, e.book_start_vers asc";
        
        // If a sort different than the default is given
        if($sort !== null) { 
           switch($sort) {
               case '0_to_9':
                   $sort_sql = "e.book_start_id asc, e.book_start_chap asc, e.book_start_vers asc";
                   break;
               case '9_to_0':
                   $sort_sql = "e.book_start_id desc, e.book_start_chap desc, e.book_start_vers desc";
                   break;
               case 'a_to_z':
                   $sort_sql = "e.name asc";
                   break;
               case 'z_to_a':
                   $sort_sql = "e.name desc";
                   break;
           }
        }
        
        // Filtering on a name
        $filter_sql = "";
        if (isset($filter)) {
            $filter_sql = " WHERE name LIKE ? ";
            $filter = '%'.$filter.'%';
        }

        // select query
        $query = "SELECT * FROM (
                    SELECT * FROM (select -999 as id, 'Global' as name) as e1
                    UNION ALL
                    SELECT * FROM (
                        SELECT
                            e.id, e.name
                        FROM
                            " . $this->table_name . " e
                        ".$filter_sql."
                        ORDER BY ".$sort_sql." ) as e2
                ) as e
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
            $filter_sql = " WHERE name LIKE ? ";
            $filter = '%'.$filter.'%';
        }
        
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "
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
    function readOne(){
        
        if ($this->id === "-999") {
            
            $child_ids = array($this->id);
            $activity_arr = array();

            $gen = 1;

            while (count($child_ids) > 0) {            
                // query to read timeline
                $children = $this->base->getTimelineEvents($child_ids, $gen++);
                $activity_arr = array_merge($activity_arr, $children);

                $child_ids = array_map(function($child) { return $child["id"]; }, $children);
            }

            $this->id = "-999";
            $this->name = "Global";
            $this->length = null;
            $this->items = array_reduce($activity_arr, function ($carry, $var1) {
                // Check if item is already in carry
                $dupl_arr = array_filter($carry, function($var2) use ($var1) {
                    return (($var1["id"] === $var2["id"]) && ($var1["parent_id"] === $var2["parent_id"]));
                });
                
                // There should be either one or zero items already in the carry
                if (empty($dupl_arr)) {
                    // No duplicate, add it to the carry
                    $carry[] = $var1;
                } else {
                    // There already is a duplicate, use the one with the highest gen
                    $dupl_idx = array_keys($dupl_arr)[0];
                    $carry[$dupl_idx]["gen"] = max($carry[$dupl_idx]["gen"], $var1["gen"]);
                }
                
                return $carry;                
            }, []);
            
        } else {
        
            $child_ids = array($this->id);
            $activity_arr = array();

            // The parent
            $parent = new event($this->conn);
            $parent->id = $this->id;
            $parent->readOne();

            $gen = 1;

            while (count($child_ids) > 0) {            
                // query to read timeline
                $children = $this->base->getTimelineActivities($child_ids, $gen++);
                $activity_arr = array_merge($activity_arr, $children);

                $child_ids = array_map(function($child) { return $child["id"]; }, $children);
            }

            $this->id = "-999";
            $this->name = $parent->name;
            $this->length = $parent->length;
            $this->items = array_reduce($activity_arr, function ($carry, $var1) {
                // Check if item is already in carry
                $dupl_arr = array_filter($carry, function($var2) use ($var1) {
                    return (($var1["id"] === $var2["id"]) && ($var1["parent_id"] === $var2["parent_id"]));
                });
                
                // There should be either one or zero items already in the carry
                if (empty($dupl_arr)) {
                    // No duplicate, add it to the carry
                    $carry[] = $var1;
                } else {
                    // There already is a duplicate, use the one with the highest gen
                    $dupl_idx = array_keys($dupl_arr)[0];
                    $carry[$dupl_idx]["gen"] = max($carry[$dupl_idx]["gen"], $var1["gen"]);
                }
                
                return $carry;                
            }, []);
        }
    }
}
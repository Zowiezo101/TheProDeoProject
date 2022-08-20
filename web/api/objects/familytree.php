<?php

require_once "../shared/base.php";

class FamilyTree {
  
    // database connection and table name
    private $conn;
    private $base;
    private $table_name = "peoples";
    public $item_name = "Familytree";
  
    // object properties
    public $id;
    public $name;
    public $meaning_name;
    public $descr;
    public $aka;
    public $gender;
    public $items;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        $this->base = new ItemBase($db);
    }

    // read products with pagination
    public function readPaging($from_record_num, $records_per_page, $sort, $filter){        
        // The sorting for the pages
        $sort_sql = "p.book_start_id asc, p.book_start_chap asc, p.book_start_vers asc";
        
        // If a sort different than the default is given
        if($sort !== null) { 
           switch($sort) {
               case '0_to_9':
                   $sort_sql = "p.book_start_id asc, p.book_start_chap asc, p.book_start_vers asc";
                   break;
               case '9_to_0':
                   $sort_sql = "p.book_start_id desc, p.book_start_chap desc, p.book_start_vers desc";
                   break;
               case 'a_to_z':
                   $sort_sql = "p.name asc";
                   break;
               case 'z_to_a':
                   $sort_sql = "p.name desc";
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
                    p.id, p.name
                FROM
                    " . $this->table_name . " p
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
                    id NOT IN (
                        SELECT people_id FROM people_to_parent WHERE parent_id IS NOT NULL)
                    AND  id IN (
                        SELECT parent_id FROM people_to_parent WHERE parent_id IS NOT NULL)
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
        
        $child_ids = array($this->id);
        $people_arr = array();
        
        $parent = new People($this->conn);
        $parent->id = $this->id;
        $parent->readOne();
        
        $level = 1;
        
        while (count($child_ids) > 0) {            
            // query to read familytree
            $children = $this->base->getFamilytreeToChildren($child_ids, $level);
            $people_arr = array_merge($people_arr, $children);
            
            $child_ids = array_map(function($child) { return $child["id"]; }, $children);
            $level++;
        }
        
        $this->name = $parent->name;
        $this->meaning_name = $parent->meaning_name;
        $this->descr = $parent->description;
        $this->aka = $parent->aka;
        $this->gender = $parent->gender;
        $this->items = $people_arr;
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
                    SELECT id, name FROM peoples 
                        LEFT JOIN people_to_parent 
                        ON peoples.id = people_to_parent.people_id 
                        WHERE peoples.id IN (SELECT p2 FROM cte)
                        AND parent_id IS NULL
                    UNION ALL
                    SELECT id, name FROM peoples 
                        LEFT JOIN people_to_parent p1
                        ON peoples.id = p1.parent_id 
                        LEFT JOIN people_to_parent p2
                        ON peoples.id = p2.people_id
                        WHERE p1.parent_id = ? 
                        AND p1.people_id IS NOT NULL
                        AND p2.parent_id IS NULL)
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
?>
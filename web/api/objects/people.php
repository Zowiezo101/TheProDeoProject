<?php

require_once "../shared/base.php";
require_once "../shared/utilities.php";

class people {
  
    // database connection and table name
    private $conn;
    private $base;
    private $table_name = "peoples";
    private $table_gender = "type_gender";
    private $table_tribe = "type_tribe";
    public $item_name = "People";
  
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
    public $aka;
    public $parents;
    public $children;
    public $locations;
    public $events;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        $this->base = new base($db);
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
            $filter_sql = " WHERE name LIKE ? ";
            $filter = '%'.$filter.'%';
        }

        // select query
        $query = "SELECT
                    p.id, p.name
                FROM
                    " . $this->table_name . " p
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
            $filter_sql = " WHERE name LIKE ? ";
            $filter = '%'.$filter.'%';
        }
        
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . $filter_sql;

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

        // query to read single record
        $query = "SELECT
                    p.name, p.descr, p.meaning_name, p.father_age, 
                    p.mother_age, p.age, t1.type_name as gender, t2.type_name as tribe, 
                    p.nationality, p.profession,
                    p.book_start_id, p.book_start_chap, p.book_start_vers, 
                    p.book_end_id, p.book_end_chap, p.book_end_vers
                FROM
                    " . $this->table_name . " p
                LEFT JOIN " . $this->table_gender . " AS t1 
                    ON p.gender = t1.type_id
                LEFT JOIN " . $this->table_tribe . " AS t2 
                    ON p.tribe = t2.type_id
                WHERE
                    p.id = ?
                LIMIT
                    0,1";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind id of product to be updated
        $stmt->bindParam(1, $this->id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->name = $row['name'];
        $this->descr = $row['descr'];
        $this->meaning_name = $row['meaning_name'];
        $this->father_age = $row['father_age'];
        $this->mother_age = $row['mother_age'];
        $this->age = $row['age'];
        $this->gender = $row['gender'];
        $this->tribe = $row['tribe'];
        $this->nationality = $row['nationality'];
        $this->book_start_id = $row['book_start_id'];
        $this->book_start_chap = $row['book_start_chap'];
        $this->book_start_vers = $row['book_start_vers'];
        $this->book_end_id = $row['book_end_id'];
        $this->book_end_chap = $row['book_end_chap'];
        $this->book_end_vers = $row['book_end_vers'];
        $this->parents = $this->base->getPeopleToParents($this->id);
        $this->children = $this->base->getPeopleToChildren($this->id);
        $this->aka = $this->base->getPeopleToPeoples($this->id);
        $this->events = $this->base->getPeopleToEvents($this->id);
        $this->locations = $this->base->getPeopleToLocations($this->id);
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
                    " . $this->table_name . " p ";
        if (strpos($params["columns"], $utilities->people_aka) !== false) {
            // We need this extra table when AKA is needed
            $query .= 
                "LEFT JOIN people_to_aka
                    ON people_to_aka.people_id = p.id 
                    AND people_to_aka.people_name LIKE ?
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
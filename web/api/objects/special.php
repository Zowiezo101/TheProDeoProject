<?php

require_once "../shared/base.php";
require_once "../shared/utilities.php";

class special {
  
    // database connection and table name
    private $conn;
    private $base;
    private $table_name = "specials";
    private $table_type = "type_special";
    public $item_name = "Special";
  
    // object properties
    public $id;
    public $descr;
    public $meaning_name;
    public $type;
    public $book_start_id;
    public $book_start_chap;
    public $book_start_vers;
    public $book_end_id;
    public $book_end_chap;
    public $book_end_vers;
    public $events;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        $this->base = new base($db);
    }

    // read products with pagination
    public function readPaging($from_record_num, $records_per_page, $sort, $filter){        
        // The sorting for the pages
        $sort_sql = "s.book_start_id asc, s.book_start_chap asc, s.book_start_vers asc";
        
        // If a sort different than the default is given
        if($sort !== null) { 
           switch($sort) {
               case '0_to_9':
                   $sort_sql = "s.book_start_id asc, s.book_start_chap asc, s.book_start_vers asc";
                   break;
               case '9_to_0':
                   $sort_sql = "s.book_start_id desc, s.book_start_chap desc, s.book_start_vers desc";
                   break;
               case 'a_to_z':
                   $sort_sql = "s.name asc";
                   break;
               case 'z_to_a':
                   $sort_sql = "s.name desc";
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
                    s.id, s.name
                FROM
                    " . $this->table_name . " s
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
                    s.name, s.descr, s.meaning_name,
                    t.type_name as type,
                    s.book_start_id, s.book_start_chap, s.book_start_vers, 
                    s.book_end_id, s.book_end_chap, s.book_end_vers
                FROM
                    " . $this->table_name . " s
                LEFT JOIN " . $this->table_type . " AS t 
                    ON s.type = t.type_id
                WHERE
                    s.id = ?
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
        $this->type = $row['type'];
        $this->book_start_id = $row['book_start_id'];
        $this->book_start_chap = $row['book_start_chap'];
        $this->book_start_vers = $row['book_start_vers'];
        $this->book_end_id = $row['book_end_id'];
        $this->book_end_chap = $row['book_end_chap'];
        $this->book_end_vers = $row['book_end_vers'];
        $this->events = $this->base->getSpecialToEvents($this->id);
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
                    " . $this->table_name . " s";
         
        if (strpos($params["columns"], "type") !== false) {
            // We need this extra table when gender is needed
            $query .= 
                " LEFT JOIN " . $this->table_type . " as it
                    ON it.type_id = s.type
                ";
        }
        
        $query .= $params["filters"] . "
                ORDER BY
                    s.order_id ASC";

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
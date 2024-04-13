<?php

require_once "../shared/base.php";
require_once "../shared/utilities.php";

class book {
  
    // database connection and table name
    private $conn;
    private $base;
    private $table_name = "books";
    private $table;
    public $item_name = "Book";
    
    // object properties
    public $id;
    public $name;
    public $num_chapters;
    public $summary;
    public $notes;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        $this->base = new base($db);
        
        $utilities = new utilities();
        $this->table = $utilities->getTable($this->table_name);
    }

    // read products with pagination
    public function readPaging($from_record_num, $records_per_page, $sort, $filter){        
        // The sorting for the pages
        $sort_sql = "b.order_id ASC";
        
        // If a sort different than the default is given
        if($sort !== null) { 
           switch($sort) {
               case '0_to_9':
                   $sort_sql = "b.order_id ASC";
                   break;
               case '9_to_0':
                   $sort_sql = "b.order_id DESC";
                   break;
               case 'a_to_z':
                   $sort_sql = "b.name ASC";
                   break;
               case 'z_to_a':
                   $sort_sql = "b.name DESC";
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
                    b.id, b.name
                FROM
                    " . $this->table . " b
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
                    b.name, b.num_chapters, b.summary
                FROM
                    " . $this->table . " b
                WHERE
                    b.id = ?
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
        $this->num_chapters = $row['num_chapters'];
        $this->summary = $row['summary'];
        $this->notes = $this->base->getItemToNotes($this->id, $this->item_name);
    }
    
    // search products
    function search($filters){
        // utilities
        $utilities = new utilities();
        
        $params = $utilities->getParams($this->table_name, $filters, $this->conn);

        // select all query
        $query = "SELECT
                    " . $params["columns"] . "
                FROM
                    " . $this->table . " b
                ". $params["filters"] ."
                ORDER BY
                    b.order_id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind
        $i = 1;
        foreach($params["values"] as $value) {
            $stmt->bindValue($i++, $value);
        }

        // execute query
        $stmt->execute();

        return $stmt;
    }
}
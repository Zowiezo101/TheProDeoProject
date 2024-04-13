<?php

require_once "../shared/base.php";
require_once "../shared/utilities.php";

class event {
  
    // database connection and table name
    private $conn;
    private $base;
    private $table_name = "events";    
    private $table;
    public $item_name = "Event";
  
    // object properties
    public $id;
    public $name;
    public $descr;
    public $length;
    public $date;
    public $book_start_id;
    public $book_start_chap;
    public $book_start_vers;
    public $book_end_id;
    public $book_end_chap;
    public $book_end_vers;
    public $previous;
    public $next;
    public $peoples;
    public $locations;
    public $specials;
    public $aka;
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
        $sort_sql = "e.book_start_id ASC, e.book_start_chap ASC, e.book_start_vers ASC";
        
        // If a sort different than the default is given
        if($sort !== null) { 
           switch($sort) {
               case '0_to_9':
                   $sort_sql = "e.book_start_id ASC, e.book_start_chap ASC, e.book_start_vers ASC";
                   break;
               case '9_to_0':
                   $sort_sql = "e.book_start_id DESC, e.book_start_chap DESC, e.book_start_vers DESC";
                   break;
               case 'a_to_z':
                   $sort_sql = "e.name ASC";
                   break;
               case 'z_to_a':
                   $sort_sql = "e.name DESC";
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
                    e.id, e.name
                FROM
                    " . $this->table . " e
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
                    e.name, e.descr, e.length,
                    e.date, e.book_start_id, e.book_start_chap,
                    e.book_start_vers, e.book_end_id, e.book_end_chap,
                    e.book_end_vers
                FROM
                    " . $this->table . " e
                WHERE
                    e.id = ?
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
        $this->length = $row['length'];
        $this->date = $row['date'];
        $this->book_start_id = $row['book_start_id'];
        $this->book_start_chap = $row['book_start_chap'];
        $this->book_start_vers = $row['book_start_vers'];
        $this->book_end_id = $row['book_end_id'];
        $this->book_end_chap = $row['book_end_chap'];
        $this->book_end_vers = $row['book_end_vers'];
        $this->next = $this->base->getEventToChildren($this->id);
        $this->previous = $this->base->getEventToParents($this->id);
        $this->peoples = $this->base->getEventToPeoples($this->id);
        $this->locations = $this->base->getEventToLocations($this->id);
        $this->specials = $this->base->getEventToSpecials($this->id);
        $this->aka = $this->base->getEventToEvents($this->id);
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
                    " . $this->table . " e
            LEFT JOIN (SELECT 
                    event_id, 
                    book_start_id as min_book_id, 
                    book_start_chap as min_book_chap, 
                    book_start_vers as min_book_vers 
                FROM (SELECT
                    0 as id, id as event_id, book_start_id, 
                    book_start_chap, book_start_vers
                FROM
                    events e
                UNION
                SELECT
                    id, event_id, book_start_id, 
                    book_start_chap, book_start_vers
                FROM
                    event_to_aka e2e
                ORDER BY 
                    event_id ASC, 
                    book_start_id ASC, 
                    book_start_chap ASC, 
                    book_start_vers ASC) as event_books
                GROUP BY event_id) as min_books
                on min_books.event_id = e.id

            LEFT JOIN (SELECT 
                    event_id, 
                    book_end_id as max_book_id, 
                    book_end_chap as max_book_chap, 
                    book_end_vers as max_book_vers 
                FROM (SELECT
                    0 as id, id as event_id, book_end_id, 
                    book_end_chap, book_end_vers
                FROM
                    events e
                UNION
                SELECT
                    id, event_id, book_end_id, 
                    book_end_chap, book_end_vers
                FROM
                        event_to_aka e2e
                ORDER BY 
                    event_id ASC, 
                    book_end_id DESC, 
                    book_end_chap DESC, 
                    book_end_vers DESC) as event_books
                GROUP BY event_id) as max_books
                on max_books.event_id = e.id

                ". $params["filters"] ."
                ORDER BY
                    e.order_id ASC";

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
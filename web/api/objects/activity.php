<?php
class activity{
  
    // database connection and table name
    private $conn;
    private $table_name = "activitys";
    
    public $item_name = "Activity";
  
    // object properties
    public $id;
    public $descr;
    public $length;
    public $date;
    public $level;
    public $book_start_id;
    public $book_start_chap;
    public $book_start_vers;
    public $book_end_id;
    public $book_end_chap;
    public $book_end_vers;
    public $aka;
  
    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }
}
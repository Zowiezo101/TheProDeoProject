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
    public $start_verse;
    public $end_verse;
  
    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }
}
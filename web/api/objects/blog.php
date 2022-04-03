<?php
class Blog {
  
    // database connection and table name
    private $conn;
    private $table_name = "blog";
    
    public $item_name = "Blog";
  
    // object properties
    public $id;
    public $title;
    public $text;
    public $user;
    public $date;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read blogs
    function read(){

        // select all query
        $query = "SELECT
                    b.id, b.title, b.text, b.user, b.date
                FROM
                    " . $this->table_name . " b
                ORDER BY
                    b.id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }
    
    // create product
    function create(){

        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    title=:title, text=:text, 
                    user=:user, date=:date";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->text = $this->text;
        $this->user = htmlspecialchars(strip_tags($this->user));
        $this->date = htmlspecialchars(strip_tags($this->date));

        // bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":text", $this->text);
        $stmt->bindParam(":user", $this->user);
        $stmt->bindParam(":date", $this->date);

        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;

    }
}
?>
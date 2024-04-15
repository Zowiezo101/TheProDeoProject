<?php
class blog {
  
    // database connection and table name
    private $conn;
    private $table_name = "blog";
    
    // The item name in case of errors
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
    
    public function get_parameters($action) {
        // Not all parameters are allowed in all actions
        switch($action) {
            case "read_one":
                // Only blog id is allowed
                if (null !== filter_input(INPUT_GET,"id")) {
                    $this->id = filter_input(INPUT_GET,"id");
                }
                break;
            
            case "read_all":
                // Only user id is allowed
                if (null !== filter_input(INPUT_GET,"user")) {
                    $this->user = filter_input(INPUT_GET,"user");
                }
                break;
        }
    }
    
    // read blogs
    function read_all(){

        // select all query
        $query = "SELECT
                    b.id, b.title, b.text, u.name, b.date
                FROM
                    " . $this->table_name . " b
                JOIN 
                    users u
                ON 
                    u.id = b.user";
            
        if (isset($this->user)) {
            $query = $query."
                WHERE u.id = ?
                ORDER BY
                    b.id DESC";

            // prepare query statement
            $stmt = $this->conn->prepare( $query );

            // bind id of product to be updated
            $stmt->bindParam(1, $this->user);
            
        } else {
            $query = $query."
                ORDER BY
                    b.id DESC";

            // prepare query statement
            $stmt = $this->conn->prepare( $query );
        }

        // execute query
        $stmt->execute();

        return $stmt;
    }
    
    // used when filling up the update product form
    function read_one(){

        // query to read single record
        $query = "SELECT
                    b.title, b.text, b.user, b.date
                FROM
                    " . $this->table_name . " b
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
        $this->title = $row['title'];
        $this->text = $row['text'];
        $this->user = $row['user'];
        $this->date = $row['date'];
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
        return $stmt->execute();

    }

    // update the product
    function update(){

        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    title=:title, text=:text
                WHERE
                    id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->text = $this->text;
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":text", $this->text);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        return $stmt->execute();
    }
    
    // delete the product
    function delete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->id);

        // execute query
        return $stmt->execute();
    }
}
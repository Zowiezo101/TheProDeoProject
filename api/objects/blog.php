<?php
require "../shared/item.php";

class blog extends item{
    
    // object properties
    public $id;
    public $title;
    public $text;
    public $user;
    public $date;
  
    // constructor with $db as database connection
    public function __construct(){
        parent::__construct();
        
        $this->table_name = "blog";        
    }
    
    public function get_parameters($action) {
        $allowed_params = [];
        $required_params = [];
        
        // Not all parameters are allowed in all actions
        switch($action) {
            case "read_one":
                $required_params = [
                    "id" => FILTER_VALIDATE_INT,
                ];
                
                $allowed_params = [
                    // Nothing done with this, but still accepted
                    "lang" => FILTER_SANITIZE_SPECIAL_CHARS,
                ];
                break;
            
            case "read_all":
                $allowed_params = [
                    "user" => FILTER_VALIDATE_INT,
                    // Nothing done with this, but still accepted
                    "lang" => FILTER_SANITIZE_SPECIAL_CHARS,
                ];
                break;
            
            case "create":
                $allowed_params = [
                    // Nothing done with this, but still accepted
                    "lang" => FILTER_SANITIZE_SPECIAL_CHARS,
                ];
                break;
            
            case "update":
                $required_params = [
                    "id" => FILTER_VALIDATE_INT,
                ];
                
                $allowed_params = [
                    // Nothing done with this, but still accepted
                    "lang" => FILTER_SANITIZE_SPECIAL_CHARS,
                ];
                break;
            
            case "delete":
                $required_params = [
                    "id" => FILTER_VALIDATE_INT,
                ];
                
                $allowed_params = [
                    // Nothing done with this, but still accepted
                    "lang" => FILTER_SANITIZE_SPECIAL_CHARS,
                ];
                break;
        }
        
        return $this->check_parameters($required_params, $allowed_params);
    }
    
    public function get_body($action) {
        $allowed_params = [];
        $required_params = [];
        
        // Accepted body parameters
        switch($action) {
            case "create":
                $required_params = [
                    "title" => FILTER_DEFAULT,
                    "text" => FILTER_DEFAULT,
                    "user" => FILTER_SANITIZE_NUMBER_INT,
                    "date" => FILTER_DEFAULT,
                ];
                break;
            
            case "update":
                $required_params = [
                    "title" => FILTER_DEFAULT,
                    "text" => FILTER_DEFAULT,
                ];
                break;
        }
        
        return $this->check_body($required_params, $allowed_params);
    }
    
    // read blogs
    function read_all(){
        $this->get_parameters("read_all");
        if ($this->error) {
            return false;
        }

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
        
        return $this->access_database($stmt);
    }
    
    // used when filling up the update product form
    function read_one(){
        $this->get_parameters("read_one");
        if ($this->error) {
            return false;
        }

        // query to read single record
        $query = "SELECT
                    b.id, b.title, b.text, b.user, b.date
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
        
        return $this->access_database($stmt);
    }
    
    // create product
    function create(){    
        // A sucessful creation of a new item should return code '201'
        $this->code = 201;
        
        $this->get_parameters("create");
        if ($this->error) {
            return false;
        }
        
        $this->get_body("create");
        if ($this->error) {
            return false;
        }

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
        // TODO: Use these param names everywhere
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":text", $this->text);
        $stmt->bindParam(":user", $this->user);
        $stmt->bindParam(":date", $this->date);
        
        return $this->access_database($stmt);

    }

    // update the product
    function update(){
        $this->get_parameters("update");
        if ($this->error) {
            return false;
        }
        
        $this->get_body("update");
        if ($this->error) {
            return false;
        }

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
        
        return $this->access_database($stmt);
    }
    
    // delete the product
    function delete(){
        $this->get_parameters("delete");
        if ($this->error) {
            return false;
        }

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
        
        return $this->access_database($stmt);
    }
}
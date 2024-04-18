<?php
require "../../../settings.conf";

class database {
  
    // specify your own database credentials
    private $host = "";
    private $db_name = "";
    private $username = "";
    private $password = "";
    public $conn;
    
	// TODO: Remove this when not needed
    // set number of records per page
//    private $records_per_page = 10;

    // calculate for the query LIMIT clause
//    private $from_record_num = $this->records_per_page * $page;
  
    // get the database connection
    public function getConnection() {
        global $servername, $db_username, $db_password, $db_database;
        $this->host = $servername;
        $this->db_name = $db_database;
        $this->username = $db_username;
        $this->password = $db_password;  
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
}


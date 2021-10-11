<?php
require "../../../../settings.conf";

class Database {
  
    // specify your own database credentials
    private $host = "";
    private $db_name = "";
    private $username = "";
    private $password = "";
    public $conn;
  
    // get the database connection
    public function getConnection() {
        global $servername, $username, $password, $database;
        $this->host = $servername;
        $this->db_name = $database;
        $this->username = $username;
        $this->password = $password;  
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
}


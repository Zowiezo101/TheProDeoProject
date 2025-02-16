<?php
    
    namespace Classes;

    // Using the following namespaces
    use PDO;

    class Database {
        
        // Debugging param
        private $debug = false;
        
        // Standard vars
        private $conn;
        private $error;
        
        public function __construct() {
            global $servername, $db_database, 
                    $db_username, $db_password;

            try {
                $this->conn = new PDO("mysql:host={$servername};dbname={$db_database}", $db_username, $db_password);
            } catch(PDOException $exception) {
                $this->error = "Connection error: " . $exception->getMessage();
            }
        }
        
        public function __destruct() {
            // Close the database connection
            $this->conn = null;
        }
        
        public function getData($query) {
            $query_string = $query["string"];
            $query_params = $query["params"];
            
            // Prepare query statement
            $stmt = $this->conn->prepare($query_string);
            
            foreach($query_params as $param => [$value, $type]) {
                // Bind params to the query
                $stmt->bindValue($param, $value, $type);
            }
            
            // execute query
            $stmt->execute();

            // check if more than 0 record found
            $num = $stmt->rowCount();
            if ($num > 0) {
                // Get the data
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // There's nothing to parse
                $data = [];
            }
            
            return $data;
        }
        
        public function setDebug($debug) {
            $this->debug = $debug;
        }
    }

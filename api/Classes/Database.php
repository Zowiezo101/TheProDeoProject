<?php
    
    namespace Classes;

    // Using the following namespaces
    use PDO;

    class Database {
        
        // 
        private $conn;
        private $error;
        
        public function __construct() {
            global $servername, $db_database, 
                    $db_username, $db_password;

            try {
                $this->conn = new PDO("mysql:host={$servername};dbname={$db_database}", $db_username, $db_password);
                $this->conn->exec("set names utf8");
            } catch(PDOException $exception) {
                $this->error = "Connection error: " . $exception->getMessage();
            }
        }
        
        public function getData($query) {
            $query_string = $query["string"];
            $query_params = $query["params"];
            
            // Prepare query statement
            $stmt = $this->conn->prepare($query_string);
            
            foreach($query_params as $param => $value) {
                // TODO: TYPE as well for more safety
                // Bind params to the query
                $stmt->bindValue($param, $value);
            }
            
            // execute query
            $stmt->execute();

            // check if more than 0 record found
            $num = $stmt->rowCount();
            if ($num > 0) {
                // Get the data
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Get the data and parse it
                $data = $this->parseData($rows);
            } else {
                // There's nothing to parse
                $data = [];
            }
            
            return $data;
        }
        
        private function parseData($rows) {
            // Use the function "getLinkingData" on all these rows
            // TODO:
//            $data = array_map([$this, "getLinkingData"], $rows);
            $data = $rows;
            
            return $data;
        }
    }

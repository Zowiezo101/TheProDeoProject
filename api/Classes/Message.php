<?php

    namespace Classes;

    class Message {
        
        // Debugging param
        private $debug = false;
        
        // TODO: Vars
        public const ERROR_UNKNOWN_KEY = ["message" => "Error: unknown key '{var}' in query", "code" => 400];
        public const ERROR_MISSING_KEY = ["message" => "Error: required key '{var}' is not found", "code" => 400];
        public const ERROR_UNAVAILABLE = ["message" => "Error: {var}", "code" => 503];
        
        public const SUCCESS_CREATED = 201;
        public const SUCCESS_UPDATED = 200;
        public const SUCCESS_DELETED = 200;
        public const SUCCESS_READ = 200;
        
        // Information to be put in the message
        private $code = self::SUCCESS_READ;
        private $error = "";
        private $data = [];
        private $paging = "";
        private $query = "";
        
        // Some parameters that can be set
        private $include_paging;
        
        public function setError($error, $value = "") {
            
            // Implement 404 as well, but only for readOne
            
            // Get the error code that comes with this error
            $this->code = $error["code"];
            
            // Get the error message that comes with this error
            $this->error = $error["message"];
            
            // This error message has a variable that needs to be filled in
            if (str_contains($error["message"], "{var}")) {
                $this->error = str_replace("{var}", $value, $error["message"]);
            }
        }
        
        public function setData($data, $code) {
            $this->data = $data;
            $this->code = $code;
        }
        
        public function setLinks($links) {  
            // After we have the records we want from the SQL query,
            // we'll go through all the records again to add extra information
            // from linking tables. This function adds all the requested information
            // by looping through all the requested linking tables.
            $this->data = array_map(function ($item) use ($links) {
                // Get all the queries for the different links
                foreach($links as [$link_name, $link_data]) {                    
                    // Add it to the item
                    $item[$link_name] = $link_data;
                }

                return $item;
            }, $this->data);
        }
        
        public function setPaging($paging) {
            $this->paging = $paging;
        }
        
        public function setQuery($query) {
            $this->query = $query;
        }
        
        public function sendMessage() {
            $message = [
                "error" => $this->error,
                "records" => $this->data
            ];
            
            if ($this->debug === true) {
                // Only when in debug mode
                $message["query"] = $this->query;
            }
            
            // TODO: When data is false, have the database set the appropiate error
            // Data is supposed to be an array. 
            // When it is false, something happened while checking the parameters
            
            if ($this->paging !== "") {
                // Include the amount of pages
                $message["paging"] = $this->paging;
            }
            
            
            http_response_code($this->code);
            echo json_encode($message);
        }
        
        public function setDebug($debug) {
            $this->debug = $debug;
        }
    }

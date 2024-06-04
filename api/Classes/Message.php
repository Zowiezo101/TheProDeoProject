<?php

    namespace Classes;

    class Message {
        public const VAR = "{var}";
        
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
        
        public function setQuery($query) {
            $this->query = $query;
        }
        
        public function sendMessage() {
            $message = [
                "error" => $this->error,
                "records" => $this->data,
                // Only when in debug mode
//                "query" => $this->query
            ];
            
            // TODO: When data is false, have the database set the appropiate error
            // Data is supposed to be an array. 
            // When it is false, something happened while checking the parameters
            
            if ($this->include_paging === true) {
                // Include the amount of pages
                $total_pages = ceil($this->count() / $this->records_per_page);
                $message["paging"] = $total_pages;
            }
            
            
            http_response_code($this->code);
            echo json_encode($message);
        }
    }

<?php

    namespace Classes;

    class Message {
        
        // Debugging param
        private $debug = false;
        
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
        private $columns = [];
        private $options = [];
        
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
        
        public function getData() {
            return $this->data;
        }
        
        public function setData($data, $code) {
            $this->data = $data;
            $this->code = $code;
        }
        
        public function updateData($data) {
            $this->data = $data;
        }
        
        public function setQuery($query) {
            $this->query = $query;
        }
        
        public function setColumns($columns) {
            $this->columns = $columns;
        }
        
        public function setOptions($options) {
            $this->options = $options;
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
            
            if (count($this->columns) > 0) {
                // Include the column names (only for search results)
                $message["columns"] = $this->columns;
            }
            
            if (count($this->options) > 0) {
                // Include the options (only for search options)
                $message["options"] = $this->options;
            }
            
            http_response_code($this->code);
            echo json_encode($message);
        }
        
        public function setDebug($debug) {
            $this->debug = $debug;
        }
    }

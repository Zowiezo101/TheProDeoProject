<?php

    namespace Classes;
    use Classes\Database as Database;
    use Classes\Message as Message;
    use Classes\Link as Link;

    class Item {
        
        // Debugging param
        private $debug = false;
        
        // The actions that are supported for this item
        public const ACTION_CREATE = "create";
        public const ACTION_UPDATE = "update";
        public const ACTION_DELETE = "delete";
        public const ACTION_READ_ONE = "read_one";
        public const ACTION_READ_ALL = "read_all";
        public const ACTION_READ_MAPS = "read_maps";
        public const ACTION_READ_PAGE = "read_page";
        public const ACTION_SEARCH_OPTIONS = "search_options";
        public const ACTION_SEARCH_RESULTS = "search_results";
        
        // Some default values
        public const DEFAULT_LANG = "nl";
        public const PAGE_SIZE = 10;
        
        // Other classes that are used
        private $database;
        private $link;
        private $message;
        
        // Parameters and actions
        private $action;
        private $action_success;
        private $optional_params;
        private $required_params;
        
        // The actual parameters
        private $lang;
        
        // Table properties
        private $table_name;
        
        // The columns of this table and their id key name
        private $table_columns;
        private $table_id;
        
        // The columns that are translated and their id key name
        private $lang_columns;
        private $lang_id;
        
        public function __construct() {  
            // get database connection
            $this->database = new Database();
            
            // Get the linking table queries
            $this->link = new Link($this);
            
            // Used to return a message to the client
            $this->message = new Message();
        }
        
        public function create () {   
            $this->action = self::ACTION_CREATE;
            
            // A sucessful creation of a new item should return code '201'
            $this->action_success = Message::SUCCESS_CREATED;
            
            // Execute the action
            $this->executeAction();
        }
        
        public function update () {
            $this->action = self::ACTION_UPDATE;
            
            // Succefully updating an item should return code '200'
            $this->action_success = Message::SUCCESS_UPDATED;
            
            // Execute the action
            $this->executeAction();
            
        }
        
        public function delete() {
            $this->action = self::ACTION_DELETE;
            
            // Succefully deleting an item should return code '200'
            $this->action_success = Message::SUCCESS_DELETED;
            
            // Execute the action
            $this->executeAction();
            
        }
        
        public function readOne() {
            $this->action = self::ACTION_READ_ONE;
            
            // Succefully reading an item should return code '200'
            $this->action_success = Message::SUCCESS_READ;
            
            // Execute the action
            $this->executeAction();
            
            // Get the links and add it to the message
            $links = $this->getLinks();
            $this->message->setLinks($links);
        }
        
        public function readAll() {
            $this->action = self::ACTION_READ_ALL;
            
            // Succefully reading an item should return code '200'
            $this->action_success = Message::SUCCESS_READ;
            
            // Execute the action
            $this->executeAction();
        }
        
        public function readPage() {
            $this->action = self::ACTION_READ_PAGE;
            
            // Succefully reading an item should return code '200'
            $this->action_success = Message::SUCCESS_READ;
            
            // Execute the action
            $this->executeAction();
            
            // Get the paging and add it to the message
            $paging = $this->getPaging();
            $this->message->setPaging($paging);
        }
        
        public function readMaps() {
            $this->action = self::ACTION_READ_MAPS;
            
            // Succefully reading an item should return code '200'
            $this->action_success = Message::SUCCESS_READ;
            
            // Execute the action
            $this->executeAction();
            
        }
        
        public function searchOptions() {
            $this->action = self::ACTION_SEARCH_OPTIONS;
            
            // Succefully reading an item should return code '200'
            $this->action_success = Message::SUCCESS_READ;
            
            // Execute the action
            $this->executeAction();
            
        }
        
        public function searchResults() {
            $this->action = self::ACTION_SEARCH_RESULTS;
            
            // Succefully reading an item should return code '200'
            $this->action_success = Message::SUCCESS_READ;
            
            // Execute the action
            $this->executeAction();
            
        }
        
        private function executeAction() {
            // Check the parameters
            if ($this->checkParameters()) {  
                // Get the query and pass it to the message class
                $query = $this->getQuery();
                $this->message->setQuery($query);

                try {
                    // Get the data using the query
                    $data = $this->database->getData($query);
                
                    // A sucessful action should return a success code (depends on the action)
                    $this->message->setData($data, $this->action_success);
                } catch (Exception $e) {
                    // Something went wrong, show the error
                    $this->message->setError(Message::ERROR_UNAVAILABLE, $e->getMessage());
                }
            }
        }
        
        public function getPaging() {
            // Query parameters
            $query_params = [];
            
            // Parts of the query
            $where_sql = $this->getWhere($query_params);
            
            // Query string (where parameters will be plugged in)
            $query_string = "SELECT CEILING(COUNT(*) / 10) as total_pages FROM {$this->table_name} {$where_sql}";
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ]; 
            
            // Get the data from the database
            $data = $this->database->getData($query);
            return $data[0]['total_pages'];
        }
        
        public function sendMessage() {
            $this->message->sendMessage();
        }
        
        public function setLinks($links) {
            $this->link->setLinks($links);
        }
        
        private function getLinks() {
            $links = $this->link->getLinks();
            return $links;
        }
        
        public function setTableName($name) {
            $this->table_name = $name;
        }
        
        public function setTableColumns($columns, $id) {
            $this->table_columns = $columns;
            $this->table_id = $id;
        }
        
        public function setLangColumns($columns, $id) {
            $this->lang_columns = $columns;
            $this->lang_id = $id;
        }

        public function getAction() {
            return $this->action;
        }
        
        public function getQuery() {
            // TODO: For more generic functions once we know what we want
        }
        
        public function getId() {
            $id = isset($this->id) ? $this->id : null;
            return $id;
        }
        
        public function getLang() {
            $lang = isset($this->lang) ? $this->lang : null;
            return $lang;
        }
        
        public function setLang($lang) {
            $this->lang = $lang;
        }
        
        public function getTable() {
            // Only if we're not using the default language 
            // (and there is a translation table)
            if (isset($this->lang_columns) && ($this->lang !== self::DEFAULT_LANG)) {
                $table = $this->getTranslatedTable();
            } else {
                $table = $this->table_name;
            }

            return $table;
        }
        
        function getTranslatedTable() {
            // The SQL part for the table we're selecting from is a bit more
            // than just 'SELECT * FROM table', since we're dealing with
            // translations as well. So it'll become something like
            // 'SELECT * FROM (SELECT translated columns if available FROM table
            //      JOIN table_lang WHERE lang = XX'
            
            $columns_array = [];
            foreach($this->table_columns as $table_column) {
                if (array_search($table_column, $this->lang_columns)) {
                    // If there is a translated version of this column, use it
                    $column = "COALESCE(NULLIF(lang.{$table_column}, ''), CONCAT(items.{$table_column}, ' (NL)')) as {$table_column}";
                } else {
                    // If there isn't, just use the regular version
                    $column = "items.{$table_column}";
                }
                
                $columns_array[] = $column;
            }
            
            // Add them all together
            $columns = join(", ", $columns_array);
                
            // The complete table query for a translated table
            $query = "(SELECT
                    {$columns}
                FROM
                    {$this->table_name} as items
                LEFT JOIN
                    {$this->table_name}_lang as lang
                ON
                    items.{$this->table_id} = lang.{$this->lang_id}
                    AND lang.lang = '{$this->lang}')";
                    
            return $query;
        }
        
        public function checkParameters() {
            // Get the parameters from the URI and body
            $uri_params = filter_input_array(INPUT_GET);
            $body_params = (array) json_decode(file_get_contents("php://input"));
            
            // Put them together
            $given_params = array_merge($uri_params, $body_params);
            
            // Check all the required parameters
            $result = $this->checkRequiredParams($given_params);

            // If there are no errors, check the optional parameters as well
            if ($result == true) {
                $result = $this->checkOptionalParams($given_params);
            }
            
            // Use the default language if no language is set
            if (!isset($this->lang) || ($this->lang === "")) {
                $this->lang = self::DEFAULT_LANG;
            }
            
            return $result;
        }
        
        private function checkRequiredParams(&$given_params) {
            $result = true;
            
            // Get the required params if any given, otherwise return an empty array
            $required_params = isset($this->required_params) ? 
                    $this->required_params : [];
            
            // Check if all required params are available
            foreach (array_keys($required_params) as $key) {
                if (array_search($key, array_keys($given_params)) === false) {
                    // Parameter not found
                    $this->message->setError(Message::ERROR_MISSING_KEY, $key);
                    $result = false;
                    break;
                } else {
                    // Filter the parameter and make sure it's the expected type
                    $this->$key = filter_var($given_params[$key], $required_params[$key]);

                    // Remove the key from the given params, as it has been checked
                    unset($given_params[$key]);
                }
            }
            
            return $result;
        }
        
        private function checkOptionalParams($given_params) {
            $result = true;
            
            // Get the optional params if any given, otherwise return an empty array
            $optional_params = isset($this->optional_params) ? 
                    $this->optional_params : [];
            
            // Language is always allowed, just not always used
            $optional_params["lang"] = FILTER_SANITIZE_SPECIAL_CHARS;
            
            // Check if there are any params that aren't allowed
            foreach ($given_params as $key => $value) {
                if ($key === "XDEBUG_SESSION_START") {
                    // Debugging mode
                    $this->debug = true;
                    $this->database->setDebug(true);
                    $this->message->setDebug(true);
                } else if (array_search($key, array_keys($optional_params)) === false) {
                    // Unknown parameter
                    $this->message->setError(Message::ERROR_UNKNOWN_KEY, $key);
                    $result = false;
                    break;
                } else {
                    // Filter the parameter and make sure it's the expected type
                    $this->$key = filter_var($value, $optional_params[$key]);
                }
            }
            
            return $result;
        }
        
        // Set the parameters that are needed
        public function setRequiredParams($params) {
            // Required parameters are an array containing arrays
            // The Outer array is for the different actions that are allowed
            // The Innter array are for the parameters allowed per action   
            $this->required_params = $params;
        }
        
        // Set the parameters that are optional
        public function setOptionalParams($params) {
            // Optional parameters are an array containing arrays
            // The Outer array is for the different actions that are allowed
            // The Innter array are for the parameters allowed per action            
            $this->optional_params = $params;
        }
    }

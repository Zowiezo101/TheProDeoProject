<?php

    namespace Classes;
    use Classes\Database as Database;
    use Classes\Message as Message;
    use Classes\Link as Link;

    class Item {
        
        // Debugging mode
        protected $debug = false;
        
        // Some default values
        private const DEFAULT_LANG = "nl";
        protected const PAGE_SIZE = 10;
        
        // Other classes that are used
        private $database;
        private $link;
        private $message;
        
        // Actions
        private $action;
        private $action_success;
        
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
        
        // Parameters
        public const OPTIONAL_PARAMS = "optional";
        public const REQUIRED_PARAMS = "required";
        
        // Some filters used by multiple item types
        protected const FILTER_ID    = ["id" => FILTER_VALIDATE_INT];
        protected const FILTER_SORT    = ["sort" => FILTER_SANITIZE_SPECIAL_CHARS];
        protected const FILTER_FILTER = ["filter" => FILTER_SANITIZE_SPECIAL_CHARS];
        protected const FILTER_PAGE  = ["page" => FILTER_VALIDATE_INT];
        
        // A parameter that is used for every action
        private $lang;
        
        // Table properties
        private $table_name;
        
        // The columns of this table and their id key name
        private $table_columns;
        private $table_id;
        
        // The columns that are translated and their id key name
        private $lang_columns;
        private $lang_id;
        
        /**
         * The constructor function
         */
        public function __construct() {  
            // get database connection
            $this->database = new Database();
            
            // Get the linking table queries
            $this->link = new Link($this);
            
            // Used to return a message to the client
            $this->message = new Message();
        }
        
        /**
         * The functions for the API:
         * - Create
         * - Update
         * - Delete
         * - ReadOne
         * - ReadAll
         * - ReadMaps
         * - ReadPage
         * - SearchOptions
         * - SearchResults
         */
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
        
        /**
         * This function executes the selected action
         * It checks parameters, tries to get the data and prepares a message 
         * for the client. In case of error, it prepares an error message
         * @param type $query
         */
        private function executeAction() {
            // Check the parameters
            $filter = $this->getFilter();
            if ($this->checkParameters($filter)) {  
                // Debug mode
                if ($this->debug === true) {
                    $this->database->setDebug(true);
                    $this->message->setDebug(true);
                }
                
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
        
        /**
         * All the query functions for the different actions:
         * - Create
         * - Update
         * - Delete
         * - ReadOne
         * - ReadAll
         * - ReadMaps
         * - ReadPage
         * - SearchOptions
         * - SearchResults
         */
        private function getQuery() {
            $query = [
                "string" => "",
                "params" => ""
            ];
            
            // Select the function that corresponds with the action
            switch($this->action) {
                case self::ACTION_CREATE:
                    $query = $this->getCreateQuery(); 
                    break;

                case self::ACTION_UPDATE:
                    $query = $this->getUpdateQuery();
                    break;
                
                case self::ACTION_DELETE:
                    $query = $this->getDeleteQuery();
                    break;
                
                case self::ACTION_READ_ONE:
                    $query = $this->getReadOneQuery();
                    break;
                
                case self::ACTION_READ_ALL:
                    $query = $this->getReadAllQuery();
                    break;
                
                case self::ACTION_READ_MAPS:
                    $query = $this->getReadMapsQuery();
                    break;
                
                case self::ACTION_READ_PAGE:
                    $query = $this->getReadPageQuery();
                    break;
                
                case self::ACTION_SEARCH_OPTIONS:
                    $query = $this->getSearchOptionsQuery();
                    break;
                
                case self::ACTION_SEARCH_RESULTS:
                    $query = $this->getSearchResultsQuery();
                    break;
            }
            
            return $query;
        }
        
        protected function getEmptyQuery() {
            return [
                "string" => "",
                "params" => ""
            ];
        }
        
        protected function getCreateQuery() {
            // Too complex to have standard functions for
            return $this->getEmptyQuery();
        }
        
        protected function getUpdateQuery() {
            // Too complex to have standard functions for
            return $this->getEmptyQuery();
        }
        
        protected function getDeleteQuery() {
            // Too complex to have standard functions for
            return $this->getEmptyQuery();
        }
        
        protected function getReadOneQuery() {
            // The translated table name
            $table = $this->getTable();
            
            // Query parameters
            $query_params = [":id" => [$this->id, \PDO::PARAM_INT]];
            
            // Query string (where parameters will be plugged in)
            $query_string = "SELECT
                    {$this->getColumnQuery()}
                FROM
                    " . $table . " i
                WHERE
                    i.id = :id
                LIMIT
                    0,1";
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            return $query;
        }
        
        protected function getReadAllQuery() {
            // Too complex to have standard functions for
            return $this->getEmptyQuery();
        }
        
        protected function getReadMapsQuery() {
            return [
                "string" => "",
                "params" => ""
            ];
        }
        
        protected function getReadPageQuery() {
            return [
                "string" => "",
                "params" => ""
            ];
        }
        
        protected function getSearchOptionsQuery() {
            return [
                "string" => "",
                "params" => ""
            ];
        }
        
        protected function getSearchResultsQuery() {
            return [
                "string" => "",
                "params" => ""
            ];
        }
        
        private function getColumnQuery() {
            // Get all the columns from the column table
            // i.id, i.title, i.text, i.user, i.date
            $columns = join(",", array_map(function ($column) {
                return "i.{$column}";
            }, $this->table_columns));
            
            return $columns;
        }
        
        
        /**
         * Get the amount of pages for this table and filter
         * @return type
         */
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
        
        /**
         * Send the message to the client
         */
        public function sendMessage() {
            $this->message->sendMessage();
        }
        
        /**
         * These are getters and setters for different properties
         */
        public function setLinks($links) {
            $this->link->setLinks($links);
        }
        
        private function getLinks() {
            $links = $this->link->getLinks();
            return $links;
        }
        
        public function setTable($name, $columns, $id) {
            $this->table_name = $name;
            $this->table_columns = $columns;
            $this->table_id = $id;
        }
        
        public function setTableLang($columns, $id) {
            $this->lang_columns = $columns;
            $this->lang_id = $id;
        }

        public function getAction() {
            return $this->action;
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
        
        /**
         * Return the table name. In case of translations, return MySQL code
         * to request the translated version of the table and default
         * language to fall back to.
         * @return type
         */
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
        
        /**
         * Parameter functions, to check all the required and optional parameters
         * Returns false if there is a parameter missing or unknown parameter
         * is present
         */
        public function checkParameters($filter) {
            // Get the parameters from the URI and body
            $uri_params = filter_input_array(INPUT_GET);
            $body_params = (array) json_decode(file_get_contents("php://input"));
            
            // Put them together
            $given_params = array_merge($uri_params, $body_params);
            
            // Get the required parameters and optional parameters
            $required_params = $filter["required"];
            $optional_params = $filter["optional"];
            
            // Check all the required parameters
            $result = $this->checkRequiredParams($required_params, $given_params);

            // If there are no errors, check the optional parameters as well
            if ($result == true) {
                $result = $this->checkOptionalParams($optional_params, $given_params);
            }
            
            // Use the default language if no language is set
            if (!isset($this->lang) || ($this->lang === "")) {
                $this->lang = self::DEFAULT_LANG;
            }
            
            return $result;
        }
        
        private function checkRequiredParams($required_params, &$given_params) {
            $result = true;
            
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
        
        private function checkOptionalParams($optional_params, $given_params) {
            $result = true;
            
            // Language is always allowed, just not always used
            $optional_params["lang"] = FILTER_SANITIZE_SPECIAL_CHARS;
            
            // Check if there are any params that aren't allowed
            foreach ($given_params as $key => $value) {
                if ($key === "XDEBUG_SESSION_START") {
                    // Debugging mode
                    $this->debug = true;
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
        
//        public function setParameters($action, $params) {
//            // Empty the arrays
//            $this->required_params[$action] = [];
//            $this->optional_params[$action] = [];
//            
//            foreach($params as [$param, $type]) {
//                if ($type === self::PARAM_REQUIRED) {
//                    // Add these parameters
//                    $this->required_params[$action] = array_merge($this->required_params[$action], $param);
//                } else {
//                    // Add these parameters
//                    $this->optional_params[$action] = array_merge($this->optional_params[$action], $param);
//                }
//            }
//        }
        
        /**
         * All the parameter functions for the different actions:
         * - Create
         * - Update
         * - Delete
         * - ReadOne
         * - ReadAll
         * - ReadMaps
         * - ReadPage
         * - SearchOptions
         * - SearchResults
         */
        private function getFilter() {
            $params = [];
            
            // Select the function that corresponds with the action
            switch($this->action) {
                case self::ACTION_CREATE:
                    $params = $this->getCreateFilter(); 
                    break;

                case self::ACTION_UPDATE:
                    $params = $this->getUpdateFilter();
                    break;
                
                case self::ACTION_DELETE:
                    $params = $this->getDeleteFilter();
                    break;
                
                case self::ACTION_READ_ONE:
                    $params = $this->getReadOneFilter();
                    break;
                
                case self::ACTION_READ_ALL:
                    $params = $this->getReadAllFilter();
                    break;
                
                case self::ACTION_READ_MAPS:
                    $params = $this->getReadMapsFilter();
                    break;
                
                case self::ACTION_READ_PAGE:
                    $params = $this->getReadPageFilter();
                    break;
                
                case self::ACTION_SEARCH_OPTIONS:
                    $params = $this->getSearchOptionsFilter();
                    break;
                
                case self::ACTION_SEARCH_RESULTS:
                    $params = $this->getSearchResultsFilter();
                    break;
            }
            
            return $params;
        }
        
        protected function getEmptyFilter() {
            return [
                self::OPTIONAL_PARAMS => [],
                self::REQUIRED_PARAMS => [],
            ];
        }
        
        protected function getCreateFilter() {
            // Too complex to have standard functions for
            return $this->getEmptyFilter();
        }
        
        protected function getUpdateFilter() {
            // Too complex to have standard functions for
            return $this->getEmptyFilter();
        }
        
        protected function getDeleteFilter() {
            // Too complex to have standard functions for
            return $this->getEmptyFilter();
        }
        
        protected function getReadOneFilter() {
            return [
                self::OPTIONAL_PARAMS => [],
                self::REQUIRED_PARAMS => array_merge(
                    self::FILTER_ID,
                ),
            ];
        }
        
        protected function getReadAllFilter() {
            // Too complex to have standard functions for
            return $this->getEmptyQuery();
        }
        
        protected function getReadMapsFilter() {
            return [
                self::OPTIONAL_PARAMS => [],
                self::REQUIRED_PARAMS => [],
            ];
        }
        
        protected function getReadPageFilter() {
            return [
                self::OPTIONAL_PARAMS => array_merge(
                    self::FILTER_SORT,
                    self::FILTER_FILTER,
                ),
                self::REQUIRED_PARAMS => array_merge(
                    self::FILTER_PAGE,
                ),
            ];
        }
        
        protected function getSearchOptionsFilter() {
            return [
                self::OPTIONAL_PARAMS => [],
                self::REQUIRED_PARAMS => [],
            ];
        }
        
        protected function getSearchResultsFilter() {
            return [
                self::OPTIONAL_PARAMS => [],
                self::REQUIRED_PARAMS => [],
            ];
        }
    }

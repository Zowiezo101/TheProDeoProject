<?php

    namespace Classes;
    use Classes\Database as Database;
    use Classes\Message as Message;
    use Classes\Link as Link;
    use Classes\Options as Options;

    class Item {
        
        // Debugging mode
        protected $debug = false;
        
        // Some default values
        protected const DEFAULT_LANG = "nl";
        
        // Other classes that are used
        protected $database;
        protected $message;
        protected $link;
        protected $options;
        
        // Actions
        protected $action;
        protected $action_success;
        
        // The actions that are supported for this item
        public const ACTION_CREATE = "create";
        public const ACTION_UPDATE = "update";
        public const ACTION_DELETE = "delete";
        public const ACTION_READ_ONE = "read_one";
        public const ACTION_READ_ALL = "read_all";
        public const ACTION_READ_MAPS = "read_maps";
        
        // Parameters
        public const OPTIONAL_PARAMS = "optional";
        public const REQUIRED_PARAMS = "required";
        
        // The array to store the parameters in
        protected $parameters = [];
        
        // Some filters used by multiple item types
        protected const FILTER_ID      = ["id" => FILTER_VALIDATE_INT];
        protected const FILTER_OPTIONS = ["options" => FILTER_VALIDATE_BOOL];
        
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
            
            // Used to return a message to the client
            $this->message = new Message();
            
            // Get the linking table queries
            $this->link = new Link($this);
            
            // Get the linking table queries
            $this->options = new Options();
        }
        
        public function __destruct() {
            // Call their destructors as well to close database connections
            $this->database = null;
            $this->link = null;
            $this->options = null;
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
            
            // Retrieve the data
            $data = $this->message->getData();
            
            // Insert the links
            $this->link->insertLinks($data);
            
            // Update the data
            $this->message->updateData($data);
        }
        
        public function readAll() {
            $this->action = self::ACTION_READ_ALL;
            
            // Succefully reading an item should return code '200'
            $this->action_success = Message::SUCCESS_READ;
            
            // Execute the action
            $this->executeAction();
            
            // Get all columns that are available with this query
            $columns = $this->getTableColumns();
            
            // Add all these columns to the results
            $this->message->setColumns($columns);
            
            // When doing a read all, search options can be added as well
            // These options are returned and can be used for a search page
            // The desired search options are set in the item classes 
            // (Book, Event, etc)
            if (isset($this->parameters["options"]) && 
                     ($this->parameters["options"] !== false)) {
                
                // Get the options
                $options = $this->options->getOptions();
            
                // Insert the options
                $this->message->setOptions($options);
            }
            
            
            // TODO: This part needs to use AKA table, order by bible location and 
            // get the highest value for end and the lowest value for start
            
    
//            if (strpos($params["columns"], $utilities->location_aka) !== false) {
//                $table = $utilities->getTable($this->base->table_l2l);
//
//                // We need this extra table when AKA is needed
//                $query .= 
//                    "LEFT JOIN " . $table . " as location_to_aka
//                        ON location_to_aka.location_id = l.id 
//                        AND location_to_aka.location_name LIKE ?
//                    ";
//            }
//            if (strpos($params["columns"], "type") !== false) {
//                // We need this extra table when gender is needed
//                $query .= 
//                    "LEFT JOIN " . $this->table_type . " as it
//                        ON it.type_id = l.type
//                    ";
//            }
        }
        
        public function readMaps() {
            $this->action = self::ACTION_READ_MAPS;
            
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
        protected function executeAction() {
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
            $query_params = [":id" => [$this->parameters["id"], \PDO::PARAM_INT]];
            
            // Query string (where parameters will be plugged in)
            $query_string = "SELECT
                    {$this->getColumnQuery()}
                FROM
                    {$table} i
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
            // The translated table name
            $table = $this->getTable();
            
            // Query parameters
            $query_params = [];
            
            // Parts of the query
            $where_sql = $this->getWhereQuery();
            
            // Query string (where parameters will be plugged in)
            $query_string = "SELECT
                    {$this->getColumnQuery()}
                FROM
                    {$table} i
                {$where_sql}
                ORDER BY
                    i.order_id ASC";
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            return $query;
        }
        
        protected function getReadMapsQuery() {
            // Too complex to have standard functions for
            return $this->getEmptyQuery();
        }
        
        private function getColumnQuery() {
            // Get all the columns from the column table
            $columns = join(",", array_map(function ($column) {
                return "i.{$column}";
            }, $this->table_columns));
            
            return $columns;
        }
        
        protected function getWhereQuery() {      
            // The where query is usually empty, but in some cases this is used
            return "";
        }
        
        protected function getSortQuery() {
            // Sort isn't set, give the default value
            if (!isset($this->parameters["sort"])) {
                $this->parameters["sort"] = "0_to_9";
            }
            
            // If a sort different then the default is given
            switch($this->parameters["sort"]) {
                case 'a_to_z':
                    $sort_sql = "i.name ASC";
                    break;
                case 'z_to_a':
                    $sort_sql = "i.name DESC";
                    break;
                case '9_to_0':
                    $sort_sql = array_search("book_start_id", $this->table_columns) ? 
                            "i.book_start_id DESC, i.book_start_chap DESC, i.book_start_vers DESC" :
                            "i.order_id DESC";
                    break;

                case '0_to_9':
                default:
                    $sort_sql = array_search("book_start_id", $this->table_columns) ? 
                            "i.book_start_id ASC, i.book_start_chap ASC, i.book_start_vers ASC" :
                            "i.order_id ASC";
                    break;      
            }
            
            return $sort_sql;
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
        public function setOptions($options) {
            $this->options->setOptions($options);
        }
        
        private function getOptions() {
            $options = $this->options->getOptions();
            return $options;
        }
        
        public function setLinks($links) {
            $this->link->setLinks($links);
        }
        
        private function getLinks() {
            $links = $this->link->getLinks();
            return $links;
        }
        
        public function getActivitiesItem() {
            return $this->link->getActivitiesItem();
        }
        
        public function getP2PItem() {
            return $this->link->getP2PItem();
        }
        
        public function getL2LItem() {
            return $this->link->getL2LItem();
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
            $id = isset($this->parameters["id"]) ? 
                        $this->parameters["id"] : null;
            return $id;
        }
        
        public function getLang() {
            $lang = isset($this->lang) ? $this->lang : null;
            return $lang;
        }
        
        public function setLang($lang) {
            $this->lang = $lang;
        }
        
        public function getParameters() {
            return $this->parameters;
        }
        
        public function getTableColumns() {
            return $this->table_columns;
        }
        
        public function getTableName() {
            return $this->table_name;
        }
        
        /**
         * Return the table name. In case of translations, return MySQL code
         * to request the translated version of the table and default
         * language to fall back to.
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
                    $column = "COALESCE(NULLIF(lang.{$table_column}, ''), CONCAT(NULLIF(items.{$table_column}, ''), ' (NL)'), '') as {$table_column}";
                } else {
                    // If there isn't, just use the regular version
                    $column = "items.{$table_column}";
                }
                
                $columns_array[] = $column;
            }
            
            // Add them all together
            $columns = join(",\n\t", $columns_array);
                
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
            $given_params = array_merge($uri_params ? $uri_params : [], $body_params);
            
            // Get the required parameters and optional parameters
            $required_params = $filter[self::REQUIRED_PARAMS];
            $optional_params = $filter[self::OPTIONAL_PARAMS];
            
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
                    // Then store the parameters so we can easily find them back
                    $this->parameters[$key] = filter_var($given_params[$key], $required_params[$key]);

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
                } else if ($key === "lang") {
                    $this->lang = filter_var($value, $optional_params[$key]);
                } else {
                    // Filter the parameter and make sure it's the expected type
                    // Then store the parameters so we can easily find them back
                    $this->parameters[$key] = filter_var($value, $optional_params[$key]);
                }
            }
            
            return $result;
        }
        
        /**
         * All the parameter functions for the different actions:
         * - Create
         * - Update
         * - Delete
         * - ReadOne
         * - ReadAll
         * - ReadMaps
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
            return [
                self::OPTIONAL_PARAMS => array_merge(
                    self::FILTER_OPTIONS,
                ),
                self::REQUIRED_PARAMS => [],
            ];
        }
        
        protected function getReadMapsFilter() {
            return [
                self::OPTIONAL_PARAMS => [],
                self::REQUIRED_PARAMS => array_merge(
                    self::FILTER_ID,
                ),
            ];
        }
    }

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
        
        // The array to store the parameters in
        protected $parameters = [];
        
        // Some filters used by multiple item types
        protected const FILTER_ID    = ["id" => FILTER_VALIDATE_INT];
        protected const FILTER_SORT  = ["sort" => FILTER_SANITIZE_SPECIAL_CHARS];
        protected const FILTER_SEARCH   = ["search" => FILTER_SANITIZE_SPECIAL_CHARS];
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
        
        public function __destruct() {
            // Call their destructors as well to close database connections
            $this->database = null;
            $this->link = null;
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
            
            // Retrieve the data
            $data = $this->message->getData();
            
            // Insert the links
            $this->link->insertLinks($data);
            
            // Update the data
            $this->message->updateData($data);
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
            /* 
             * TODO: The global timeline query, put this in the database itself
            if ($this->id === -999) {
                // This is a "Global timeline" event
                $query = "SELECT
                        ? AS id, 'timeline.global' AS name";
            } 
             */
            
            // The translated table name
            $table = $this->getTable();
            
            // Query parameters
            $query_params = [":id" => [$this->parameters["id"], \PDO::PARAM_INT]];
            
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
            // Too complex to have standard functions for
            return $this->getEmptyQuery();
        }
        
        protected function getReadPageQuery() {
            // The translated table name
            $table = $this->getTable();
            
            // Query parameters
            $query_params = [
                ":page_start" => [self::PAGE_SIZE * $this->parameters["page"], \PDO::PARAM_INT],
                ":page_size" => [self::PAGE_SIZE, \PDO::PARAM_INT]
            ];
            
            // Parts of the query
            $where_sql = $this->getWhereQuery($query_params);
            $sort_sql = $this->getSortQuery();

            // Query string (where parameters will be plugged in)
            $query_string = "SELECT
                    i.id, i.name
                FROM
                    {$table} i
                {$where_sql}
                ORDER BY
                    {$sort_sql}
                LIMIT
                    :page_start, :page_size";
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];            
            return $query;
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
        
        protected function getWhereQuery(&$query_params) {
            $where_sql = "";
            if (isset($this->parameters["search"]) && 
                     ($this->parameters["search"] !== "")) {
                $where_sql = "WHERE name LIKE :filter";
                $query_params[":filter"] = ['%'.$this->parameters["search"].'%', \PDO::PARAM_STR];
            }
            
            return $where_sql;
        }
        
        protected function getSortQuery() {
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
         * Get the amount of pages for this table and filter
         * @return type
         */
        public function getPaging() {
            // Query parameters
            $query_params = [];
            
            // Parts of the query
            $where_sql = $this->getWhereQuery($query_params);
            
            // Query string (where parameters will be plugged in)
            $query_string = "SELECT CEILING(COUNT(*) / 10) as total_pages FROM {$this->table_name} i {$where_sql}";
            
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
            $given_params = array_merge($uri_params, $body_params);
            
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
            return $this->getEmptyFilter();
        }
        
        protected function getReadMapsFilter() {
            return [
                self::OPTIONAL_PARAMS => [],
                self::REQUIRED_PARAMS => array_merge(
                    self::FILTER_ID,
                ),
            ];
        }
        
        protected function getReadPageFilter() {
            return [
                self::OPTIONAL_PARAMS => array_merge(
                    self::FILTER_SORT,
                    self::FILTER_SEARCH,
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
        
        /*
         * public function getParams($type, $filters, $conn) {
        // The filters to be applied on the database
        $item_columns = array();
        $item_filters = array();
        $item_values = array();
        $item_params["filters"] = array();
        $item_params["values"] = array();
        $item_params["columns"] = array();
        
        // Always have these columns
        $item_columns[] = "name";
        switch($type) {
            case "books":
                $item_columns[] = "num_chapters";
                $item_columns[] = "id";
                break;
            
            case "events":
                $item_columns[] = "min_book_id as book_start_id";
                $item_columns[] = "min_book_chap as book_start_chap";
                $item_columns[] = "min_book_vers as book_start_vers";
                $item_columns[] = "max_book_id as book_end_id";
                $item_columns[] = "max_book_chap as book_end_chap";
                $item_columns[] = "max_book_vers as book_end_vers";
                $item_columns[] = "e.id";
                break;
            
            case "peoples":
                $item_columns[] = "book_start_id";
                $item_columns[] = "book_start_chap";
                $item_columns[] = "book_start_vers";
                $item_columns[] = "book_end_id";
                $item_columns[] = "book_end_chap";
                $item_columns[] = "book_end_vers";
                $item_columns[] = "p.id";
                break;
            
            case "locations":
                $item_columns[] = "book_start_id";
                $item_columns[] = "book_start_chap";
                $item_columns[] = "book_start_vers";
                $item_columns[] = "book_end_id";
                $item_columns[] = "book_end_chap";
                $item_columns[] = "book_end_vers";
                $item_columns[] = "l.id";
                break;
            
            case "specials":
                $item_columns[] = "book_start_id";
                $item_columns[] = "book_start_chap";
                $item_columns[] = "book_start_vers";
                $item_columns[] = "book_end_id";
                $item_columns[] = "book_end_chap";
                $item_columns[] = "book_end_vers";
                $item_columns[] = "s.id";
                break;
        }
        
        $json_filters = json_decode($filters);
        if (json_last_error() === JSON_ERROR_NONE && is_object($json_filters)) {
            if(property_exists($json_filters, 'sliders')) {
                $item_columns = [];
                
                if(in_array('chapters', $json_filters->sliders)) {
                    // Get the maximum and minimum chapters
                    $item_columns[] = "max(num_chapters) as max_num_chapters";
                    $item_columns[] = "min(num_chapters) as min_num_chapters";
                }
                if(in_array('age', $json_filters->sliders)) {
                    // Get the maximum and minimum chapters
                    $item_columns[] = "max(age) as max_age";
                    $item_columns[] = "min(age) as min_age";
                }
                if(in_array('parent_age', $json_filters->sliders)) {
                    // Get the maximum and minimum chapters
                    $item_columns[] = "greatest(max(father_age), max(mother_age)) as max_parent_age";
                    $item_columns[] = "greatest(min(father_age), min(mother_age)) as min_parent_age";
                }
            } 
            
            if(property_exists($json_filters, 'select')) {
                $item_types = [];
                
                if(in_array('gender', $json_filters->select)) {
                    // Get the gender types
                    $item_types[] = "type_gender";
                }
                if(in_array('tribe', $json_filters->select)) {
                    // Get the tribe types
                    $item_types[] = "type_tribe";
                }
                if(in_array('type_location', $json_filters->select)) {
                    // Get the location types
                    $item_types[] = "type_location";
                }
                if(in_array('type_special', $json_filters->select)) {
                    // Get the special types
                    $item_types[] = "type_special";
                }
                $item_params["types"] = $item_types;
            }
        
                if(property_exists($json_filters, 'name')) {
                    $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->name))."%";
                    if ($type === "peoples") {
                        // Two extra columns, one for the AKA and one
                        // to let us known wether we have a hit because aka
                        $item_columns[] = "if(".$this->people_aka." LIKE ?, ".$this->people_aka.", '') AS aka";
                        
                        // One updated filter WITH aka
                        $item_filters[] = "(name LIKE ? OR ".$this->people_aka." LIKE ?)";
                        
                        // Three extra values
                        $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->name))."%";
                        $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->name))."%";
                        $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->name))."%";
                    } elseif ($type === "locations") {
                        // Two extra columns, one for the AKA and one
                        // to let us known wether we have a hit because aka
                        $item_columns[] = "if(".$this->location_aka." LIKE ?, ".$this->location_aka.", '') AS aka";
                        
                        // One updated filter WITH aka
                        $item_filters[] = "(name LIKE ? OR ".$this->location_aka." LIKE ?)";
                        
                        // Three extra values
                        $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->name))."%";
                        $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->name))."%";
                        $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->name))."%";
                    } else {
                        $item_filters[] = "name LIKE ?";
                    }
                }
                if(property_exists($json_filters, 'meaning_name')) {
                    $item_filters[] = "meaning_name LIKE ?";
                    $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->meaning_name))."%";
                    $item_columns[] = "meaning_name";
                }
                if(property_exists($json_filters, 'descr')) {
                    $item_filters[] = "descr LIKE ?";
                    $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->descr))."%";
                    $item_columns[] = "descr";
                }
                if(property_exists($json_filters, 'num_chapters')) {
                    $item_filters[] = "num_chapters BETWEEN ? AND ?";
                    
                    // The two chapters to set between
                    $items = explode('-', htmlspecialchars(strip_tags($json_filters->num_chapters)), 2);
                    $item_values[] = $items[0];
                    $item_values[] = $items[1];
                }
                if(property_exists($json_filters, 'length')) {
                    $item_filters[] = "length LIKE ?";
                    $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->length))."%";
                    $item_columns[] = "length";
                }
                if(property_exists($json_filters, 'date')) {
                    $item_filters[] = "date LIKE ?";
                    $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->date))."%";
                    $item_columns[] = "date";
                }
                if(property_exists($json_filters, 'age')) {
                    $item_filters[] = "age BETWEEN ? AND ?";
                    
                    // The two lengths to set between
                    $items = explode('-', htmlspecialchars(strip_tags($json_filters->age)), 2);
                    $item_values[] = $items[0];
                    $item_values[] = $items[1];
                    $item_columns[] = "age";
                }
                if(property_exists($json_filters, 'parent_age')) {
                    $item_filters[] = "(father_age BETWEEN ? AND ? OR mother_age BETWEEN ? AND ?)";
                    
                    // The two lengths to set between
                    $items = explode('-', htmlspecialchars(strip_tags($json_filters->parent_age)), 2);
                    $item_values[] = $items[0];
                    $item_values[] = $items[1];
                    $item_values[] = $items[0];
                    $item_values[] = $items[1];
                    $item_columns[] = "father_age";
                    $item_columns[] = "mother_age";
                }
                if(property_exists($json_filters, 'gender')) {
                    $item_filters[] = "gender = ?";
                    $item_values[] = htmlspecialchars(strip_tags($json_filters->gender));
                    $item_columns[] = "g.type_name as gender";
                    
                    $query = "SELECT
                                type_id
                            FROM
                                " .$this->gender_type;
                    
                    // prepare query statement
                    $stmt = $conn->prepare($query);
                    
                    // execute query
                    $stmt->execute();
                    
                    // The amount of results
                    $num = strval($stmt->rowCount());
                    
                    if ($json_filters->gender == $num) {
                        $genders = implode(", ", range(0, $num - 1, 1));

                        // We want all genders
                        array_pop($item_filters);
                        array_pop($item_values);
                        $item_filters[] = "gender in (".$genders.")";
                    }
                }
                if(property_exists($json_filters, 'tribe')) {
                    $item_filters[] = "tribe = ?";
                    $item_values[] = htmlspecialchars(strip_tags($json_filters->tribe));
                    $item_columns[] = "t.type_name as tribe";
                    
                    $query = "SELECT
                                type_id
                            FROM
                                " .$this->tribe_type;
                    
                    // prepare query statement
                    $stmt = $conn->prepare($query);
                    
                    // execute query
                    $stmt->execute();
                    
                    // The amount of results
                    $num = strval($stmt->rowCount());
                    
                    if ($json_filters->tribe == $num) {
                        $tribes = implode(", ", range(0, $num - 1, 1));

                        // We want all tribes
                        array_pop($item_filters);
                        array_pop($item_values);
                        $item_filters[] = "tribe in (".$tribes.")";
                    }
                }
                if(property_exists($json_filters, 'profession')) {
                    $item_filters[] = "profession LIKE ?";
                    $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->profession))."%";
                    $item_columns[] = "profession";
                }
                if(property_exists($json_filters, 'nationality')) {
                    $item_filters[] = "nationality LIKE ?";
                    $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->nationality))."%";
                    $item_columns[] = "nationality";
                }
                if(property_exists($json_filters, 'type')) {
                    $item_filters[] = "type = ?";
                    $item_values[] = htmlspecialchars(strip_tags($json_filters->type));
                    $item_columns[] = "it.type_name as type";

                    if ($type == "locations") {
                        $query = "SELECT
                                    type_id
                                FROM
                                    " .$this->location_type;

                        // prepare query statement
                        $stmt = $conn->prepare($query);

                        // execute query
                        $stmt->execute();

                        // The amount of results
                        $num = strval($stmt->rowCount());

                        if ($json_filters->type == $num) {
                            $tribes = implode(", ", range(0, $num - 1, 1));

                            // We want all types
                            array_pop($item_filters);
                            array_pop($item_values);
                            $item_filters[] = "type in (".$tribes.")";
                        }
                    }

                    if ($type == "specials") {
                        $query = "SELECT
                                    type_id
                                FROM
                                    " .$this->special_type;

                        // prepare query statement
                        $stmt = $conn->prepare($query);

                        // execute query
                        $stmt->execute();

                        // The amount of results
                        $num = strval($stmt->rowCount());

                        if ($json_filters->type == $num) {
                            $tribes = implode(", ", range(0, $num - 1, 1));

                            // We want all types
                            array_pop($item_filters);
                            array_pop($item_values);
                            $item_filters[] = "type in (".$tribes.")";
                        }
                    }
                }
            
                if(property_exists($json_filters, 'start_book')) {
                    $item_filters[] = "book_start_id >= ?";
                    $item_values[] = htmlspecialchars(strip_tags($json_filters->start_book));
                }
                if(property_exists($json_filters, 'start_chap')) {
                    $item_filters[] = "book_start_chap >= ?";
                    $item_values[] = htmlspecialchars(strip_tags($json_filters->start_chap));
                }
                if(property_exists($json_filters, 'end_book')) {
                    $item_filters[] = "book_end_id <= ?";
                    $item_values[] = htmlspecialchars(strip_tags($json_filters->end_book));
                }
                if(property_exists($json_filters, 'end_chap')) {
                    $item_filters[] = "book_end_chap <= ?";
                    $item_values[] = htmlspecialchars(strip_tags($json_filters->end_chap));
                }
        }
        
        // Turn these arrays into strings
        $item_params["columns"] = implode(', ', $item_columns);
        $item_params["filters"] = implode(' AND ', $item_filters) ? 
                        "WHERE " . implode(' AND ', $item_filters) : "";
        $item_params["values"] = $item_values;
        
        return $item_params;
    }
         */
    }

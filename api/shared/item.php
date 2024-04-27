<?php

require_once "utilities.php";

$BOOKS_TO_NOTES = ["book", "notes"];
$EVENTS_TO_NEXT = ["event", "children"];
$EVENTS_TO_PREV = ["event", "parents"];
$EVENTS_TO_PEOPLES = ["event", "peoples"];
$EVENTS_TO_LOCATIONS = ["event", "locations"];
$EVENTS_TO_SPECIALS = ["event", "specials"];
$EVENTS_TO_AKA = ["event", "events"];
$EVENTS_TO_NOTES = ["event", "notes"];

class item {
  
    // database connection and table name
    protected $conn;
    protected $table_name;
    
    // Language options
    protected $lang;
    protected $table_lang;
    
    // Utilities with extra functions
    private $utilities;
    protected $query;
    
    // In case an error occurs
    protected $error = "";
    protected $code = 200;

    // The default amount of records when asking for a page
    protected $records_per_page = 10;
    
    // This is in case we need information from another table as well
    private $linking_tables = [];
  
    // constructor with $db as database connection
    public function __construct(){
        global $conn;
        $this->conn = $conn;
        $this->utilities = new utilities();
    }
    
    function check_parameters($required_params, $allowed_params) {
        $result = true;
        $given_params = filter_input_array(INPUT_GET);
        
        // Check if all required params are available
        foreach (array_keys($required_params) as $key) {
            if (array_search($key, array_keys($given_params)) === false) {
                // Parameter not found
                $this->error = "Error: required key '".$key."' is not found";
                $this->code = 400;
                $result = false;
                break;
            } else {
                // Filter the parameter and make sure it's the expected type
                $this->$key = filter_var($given_params[$key], $required_params[$key]);
                
                // Remove the key from the given params, as it has been checked
                unset($given_params[$key]);
            }
        }
        
        if ($result == true) {
            // Check if there are any params that aren't allowed
            foreach ($given_params as $key => $value) {
                if (array_search($key, array_keys($allowed_params)) === false) {
                    // Unknown parameter
                    $this->error = "Error: unknown key '".$key."' in query";
                    $this->code = 400;
                    $result = false;
                    break;
                } else {
                    // Filter the parameter and make sure it's the expected type
                    $this->$key = filter_var($value, $allowed_params[$key]);
                }
            }
        }
        
        // If the language is set, get the translation table as well
        if (isset($this->lang)) {
            $this->table_lang = $this->get_table_lang();
        }
                
        return $result;
    }
    
    function check_body($required_params, $allowed_params) {
        $result = true;
        $given_params = (array) json_decode(file_get_contents("php://input"));
        
        // Check if all required params are available
        foreach (array_keys($required_params) as $key) {
            if (array_search($key, array_keys($given_params)) === false) {
                // Parameter not found
                $this->error = "Error: required key '".$key."' is not found";
                $this->code = 400;
                $result = false;
                break;
            } else {
                // Filter the parameter and make sure it's the expected type
                $this->$key = filter_var($given_params[$key], $required_params[$key]);
                
                // Remove the key from the given params, as it has been checked
                unset($given_params[$key]);
            }
        }
        
        if ($result == true) {
            // Check if there are any params that aren't allowed
            foreach ($given_params as $key => $value) {
                if (array_search($key, array_keys($allowed_params)) === false) {
                    // Unknown parameter
                    $this->error = "Error: unknown key '".$key."' in body";
                    $this->code = 400;
                    $result = false;
                    break;
                } else {
                    // Filter the parameter and make sure it's the expected type
                    $this->$key = filter_var($value, $allowed_params[$key]);
                }
            }
        }
                
        return $result;
    }
    
    function access_database($stmt) {

        // Create an array for the data
        // and fill it up if we have any results
        $data = [];
            
        try {
            // execute query
            $stmt->execute();

            // check if more than 0 record found
            $num = $stmt->rowCount();
            if ($num > 0) {

                // retrieve our table contents
                // fetch() is faster than fetchAll()
                // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){  
                    array_push($data, $row);
                }
            }
            
            $data = array_map([$this, "get_linking_data"], $data);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->code = 503;
        }

        return $data;
    }
    
    function prepare_message($data, $include_paging=false) {
        $message = [
            "data" => [
                "error" => $this->error,
                "records" => [],
            ],
            "code" => $this->code,
        ];
        
        // Data is supposed to be an array. 
        // When it is false, something happened while checking the parameters
        if($data !== false){
            $message["data"]["records"] = $data;
        
            if ($include_paging != false) {
                // Include the amount of pages
                $total_pages = ceil($this->count() / $this->records_per_page);
                $message["data"]["paging"] = $total_pages;
            }
        }
        
        return $message;
    }
    
    public function get_table_lang() {
        $this->utilities->setLanguage($this->lang);
        return $this->utilities->getTable($this->table_name);
    }
    
    // used for paging products
    function count(){
        
        // Filtering on a name
        $filter_sql = "";
        if (isset($this->filter)) {
            $filter_sql = " WHERE name LIKE ? ";
            $filter = '%'.$this->filter.'%';
        }
        
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . $filter_sql;

        $stmt = $this->conn->prepare( $query );
        
        if (isset($this->filter)) {
            $stmt->bindParam(1, $filter, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }
    
    function set_linking_tables($tables) {
        $this->linking_tables = $tables;
    }
    
    function get_linking_data($item) {
        foreach($this->linking_tables as $link_table) {
            // Get the table and link from the variable
            $table = $link_table[0];
            $link = $link_table[1];
                    
            // Get the corresponding function for this table and the link
            $function = $this->utilities->getLinkingFunction($table, $link);
            if ($function !== "") {
                // If the function exists, execute it to get the link for this item
                $item[$link] = $this->utilities->$function($item["id"]);
            }
        }
        
        return $item;
    }
}

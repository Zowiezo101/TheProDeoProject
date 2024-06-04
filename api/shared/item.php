<?php

// Setting our own namespace
namespace shared;

// Using the following namespaces
use PDO;

class Item {
  
    // database connection and table name
    protected $conn;
    protected $table_name;
    
    // Language options
    protected $lang;
    protected $table_lang;
    protected $columns;
    protected $columns_lang;
    
    // Utilities with extra functions
    protected $utilities;
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
        $this->utilities = new Utilities();
    }
    
    // used for paging products
    function count(){
        
        // Filtering on a name
        $filter_sql = "";
        if (isset($this->filter) && ($this->filter != "")) {
            $filter_sql = " WHERE name LIKE ? ";
            $filter = '%'.$this->filter.'%';
        }
        
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . $filter_sql;

        $stmt = $this->conn->prepare( $query );
        
        if (isset($filter)) {
            $stmt->bindParam(1, $filter, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }
    
    function setLinkingTable($tables) {
        $this->linking_tables = $tables;
    }
    
    function getLinkingData($item) {
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

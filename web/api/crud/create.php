<?php    
require "../../login_data.php";

class result {
    public $data;
    public $error;
    public $query;
};

$result = new result();

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    $result->error = "Connection failed: " . $conn->connect_error;
} else {
    /** The data the user can give along with this API
     * - table
     * - id
     * - columns
     * - filters
     * - calculations
     */
    $check_results = checkAllParams();
    
    // Parse the results
    if ($check_results->error) {
        // Checking returned an error
        $result->error = $check_results->error;
    } else {
        $checked_table = $check_results->data->table;
        $checked_data = $check_results->data->data;
        
        $sql_where = getWhereStatement($check_results->data);
        $sql_sort = getSortStatement($check_results->data);
        
        // The final SQL query
        $sql = "SELECT * FROM ".$checked_table.$sql_where.$sql_sort;

        // excecute SQL statement
        $result->query = $sql;
        $results = mysqli_query($conn, $sql);

        // die if SQL statement failed
        if (!$results) {
            $result->error = mysqli_error($conn);
        }
        
        if (!$result->error && (mysqli_num_rows($results) > 0)) {
            // Put the results in the arrau
            $result->data = Array();
            for ($i = 0; $i < mysqli_num_rows($results); $i++) {
                $result->data[] = mysqli_fetch_object($results);
            }
        }
    }

    // close mysql connection
    mysqli_close($conn);
}

echo json_encode($result);

// Check all the parameters
function checkAllParams() {
    
    // The result object to save the results in
    $result = new result();
    
    // Check if the table is valid
    $table_result = isTableValid('table');
    
    if ($table_result->error) {
        $result->error = $table_result->error;
    } else if ($table_result->data == False) {
        $result->error = "No valid table is selected";
    } else {
        // Check the columns
        $data_result = checkData($table_result->query, 'data');

        if ($data_result->error) {
            // No valid columns selected
            $result->error = $data_result->error;
        } else {
            // Everything is checked and valid
            $result->data = new stdClass();
            $result->data->table = $table_result->query;
            $result->data->blog_title = $data_result->data->blog_title;
            $result->data->blog_text = $data_result->data->blog_text;
            $result->data->blog_user = $data_result->data->blog_user;
            $result->data->blog_date = $data_result->data->blog_date;
        }
    }
    
    return $result;
}

// Check multiple parameters
function checkMultParams($name) {
    // TODO: Check for insertion
    $params = json_decode(filter_input(INPUT_POST, $name));
    return $params;
}

function isTableValid($table) {
    global $table;
    
    // The result object to save the results in
    $result = new result();
    $result->query = $table;
    
    // Get a list of all valid tables
    $valid_tables = getValidTables();
    if ($valid_tables->error) {
        // Something went wrong
        $result->error = $valid_tables->error;
        $result->data = false;
    } else if (count($valid_tables->data) == 0) {
        // Something went wrong
        $result->error = "Database does not return valid tables";
        $result->data = false;
    } else {
        if (($table !== null) && in_array($table, $valid_tables->data)) {
            // The table is in here
            $result->data = true;
        } else {
            // Not a valid table
            $result->error = $result->query." is not a valid table";
            $result->data = false;
        }
    }
    
    return $result;
}

/** Return all the valid tables */
function getValidTables() {
    global $conn;
    
    // The result object to save the results in
    $result = new result();
    
    // The query to perform
    $sql = "SELECT DISTINCT TABLE_NAME 
            FROM INFORMATION_SCHEMA.TABLES";
    $results = mysqli_query($conn, $sql);
    
    // Save the results
    $result->query = $sql;
    if (!$results) {
        $result->error = mysqli_error($conn);
    } else {
        // Put the results in the arrau
        $result->data = Array();
        for ($i = 0; $i < mysqli_num_rows($results); $i++) {
            $object = mysqli_fetch_object($results);
            $result->data[] = $object->TABLE_NAME ? $object->TABLE_NAME : "";
        }
    }
    
    return $result;
}

function checkData($table, $data) {
    // The result object to save the results in
    $result = new result();
    
    $data_checked = checkMultiParams($data);
    $result->query = $data_checked;
        
    // These keys are required to be available
    switch($table) {
        case "blog":
            $required_keys = ["title", "text", "user", "date"];
            break;
        
        default:
            $required_keys = [];
            break;
    }
    
    // Check these keys
    foreach ($data_checked as $key => $value) {
        $result_column = isColumnValid($table, $key);
        $result_value = isValueValid($table, $key, $value);
        
        if ($result_column->error) {
            $result->error = $result_column->error;
            break;
        } else if ($result_column->data == False) {
            $result->error = "No valid column is selected";
            break;
        } else {
            $key_idx = array_search($key, $required_keys);
            if ($key_idx !== false) {
                // Remove this from the required keys
                unset($required_keys[$key_idx]);
            } else {
                
            }
        }
    }
    
    return $result;
}

function checkColumns($table, $columns) {
    // The result object to save the results in
    $result = new result();
    
    // First check for insertion
    $columns_checked = checkMultParams($columns);
    $result->query = $columns_checked;
    
    for ($i = 0; $i < count($columns_checked); $i++) {
        $column = $columns_checked[$i];
        $result_column = isColumnValid($table, $column);
        
        if ($result_column->error) {
            $result->error = $result_column->error;
            break;
        } else if ($result_column->data == False) {
            $result->error = "No valid column is selected";
            break;
        } else {
            $result->data[] = $result_column->query;
        }
    }
    
    return $result;
}

function isColumnValid($table, $column) {
    // The result object to save the results in
    $result = new result();
    $result->query = $column;
    
    // Get a list of all valid tables
    $valid_columns = getValidColumns($table);
    if ($valid_columns->error) {
        // Something went wrong
        $result->error = $valid_columns->error;
        $result->data = false;
    } else if (count($valid_columns->data) == 0) {
        // Something went wrong
        $result->error = "Database does not return valid columns";
        $result->data = false;
    } else {
        if (($valid_columns !== null) && in_array($column, $valid_columns->data)) {
            // The column is in here
            $result->data = true;
        } else {
            // Not a valid column
            $result->data = false;
        }
    }
    
    return $result;
}

/** Return all the valid columns */
function getValidColumns($table) {
    global $conn;
    
    // The result object to save the results in
    $result = new result();
    
    $table_names = [];
    switch($table) {
        /*case "actvity_to_activity":
            $table_names[] = "activitys";
            break;
            
        case "actvity_to_event":
            break;
            
        case "activitys":
            break;
            
        case "event_to_event":
            break;
            
        case "events":
            break;
            
        case "location_to_activity":
            break;
            
        case "location_to_location":
            break;
            
        case "locations":
            break;
            
        case "people_to_activity":
            break;
            
        case "people_to_location":
            break;
            
        case "people_to_parent":
            break;
            
        case "people_to_people":
            break;
            
        case "peoples":
            break;
            
        case "special_to_activity":
            break;
            
        case "specials":
            break;*/
            
        default:
            $table_names[] = $table;
    }
    
    // The query to perform
    $sql = "SELECT COLUMN_NAME 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = '".implode("' OR TABLE_NAME = '", $table_names)."'";
    $results = mysqli_query($conn, $sql);
    
    // Save the results
    $result->query = $sql;
    if (!$results) {
        $result->error = mysqli_error($conn);
    } else {
        $result->data = Array();
        for ($i = 0; $i < mysqli_num_rows($results); $i++) {
            
            $object = mysqli_fetch_object($results);
            $result->data[] = $object->COLUMN_NAME ? $object->COLUMN_NAME : "";
        }
    }
    
    return $result;
}
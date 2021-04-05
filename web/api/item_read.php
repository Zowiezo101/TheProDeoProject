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
        $value = filter_input(INPUT_GET, 'value') !== null ? filter_input(INPUT_GET, 'value') : "";
        if ($value !== "") {
            // We just want this specific ID
            $column = filter_input(INPUT_GET, 'column') !== null ? filter_input(INPUT_GET, 'column') : substr($table, 0, -1)."_id";
            $sql = "select * from ".$table." where ".$column." = ".$value;
        } else {
            // No ID given means we want all results of that table, or a subset using range
            $offset = filter_input(INPUT_GET, 'offset') !== null ? " limit ".filter_input(INPUT_GET, 'offset').", 100" : "";
            $sort = filter_input(INPUT_GET, 'sort') !== null ? " order by ".filter_input(INPUT_GET, 'sort') : "";
            $sql = "select * from ".$table.$sort.$offset;
        }

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
        // Check the IDs
        $ids_result = checkIDs('ids');
        if ($ids_result->error) {
            // No valid ID selected
            $result->error = $ids_result->error;
        } else {
            // Check the columns
            $columns_result = checkColumns($table_result->query, 'columns');
            if ($columns_result->error) {
                // No valid columns selected
                $result->error = $columns_result->error;
            } else {
                // Check the filters
                $filters_result = checkFilters($table_result->query, 'filters');
                if ($filters_result->error) {
                    // No valid filter selected
                    $result->error = $filters_result->error;
                } else {
                    // Check the calculations
                    $calculations_result = checkCalculations($table_result->query, 'calculations');
                    if ($calculations_result->error) {
                        // No valid calculation selected
                        $result->error = $calculations_result->error;
                    } else {
                        // Everything is checked and valid
                        $result->data->table = $table_result->data;
                        $result->data->ids = $ids_result->data;
                        $result->data->columns = $columns_result->data;
                        $result->data->filters = $filters_result->data;
                        $result->data->calculations = $calculations_result->data;
                    }
                }
            }
        }   
    }
    
    return $result;
}

// Check a single paramter
function checkSingleParam($name) {
    // TODO: Check for insertion
    $param = filter_input(INPUT_POST, $name);
    return $param;
}

// Check multiple parameters
function checkMultParams($name) {
    // TODO: Check for insertion
    $param = filter_input(INPUT_POST, $name);
    $params = split(';', $param);
    return $params;
}

function isTableValid($table) {
    // The result object to save the results in
    $result = new result();
    
    // First check for insertion
    $table_checked = checkSingleParam($table);
    $result->query = $table_checked;
    
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
        if (($table_checked !== null) && in_array($table_checked, $valid_tables->data)) {
            // The table is in here
            $result->data = true;
        } else {
            // Not a valid table
            $result->data = false;
        }
    }
    
    return result;
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
        $result->data = mysqli_fetch_all($results, MYSQLI_NUM);
    }
    
    return $result;
}

function checkIDs($ids) {
    // The result object to save the results in
    $result = new result();
    
    // First check for insertion
    $ids_checked = checkMultParams($ids);
    $result->query = $ids_checked;
    
    for ($i = 0; $i < count($ids_checked); $i++) {
        // The ID to check
        $id = $ids_checked[$i];
        if ($id == "") {
            continue;
        }

        // Make sure it's a number
        if(!is_numeric($id)) {
            $result->error = $id." is not a valid ID";
            break;
        } else {
            $result->data[] = $id;
        }
    }
    
    if (!$result->error && count($result->data) == 0) {
        $result->error = "No valid ID is selected";
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
    }
}

function isColumnValid($table, $column) {
    // The result object to save the results in
    $result = new result();
    
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
    
    return result;
}

/** Return all the valid tables */
function getValidColumns($table, $calculations) {
    global $conn;
    
    // The result object to save the results in
    $result = new result();
    
    $table_names = [];
    switch($table) {
        case "actvity_to_activity":
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
            break;
            
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
        $result->data = mysqli_fetch_all($results, MYSQLI_NUM);
    }
    
    return $result;
}
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
        $checked_ids = $check_results->data->ids;
        $checked_columns = $check_results->data->columns;
        $checked_filters = $check_results->data->filters;
        $checked_sort = $check_results->data->sort;
        $checked_calculations = $check_results->data->calculations;
        
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
        // Check the IDs
        $ids_result = checkIDs('ids');
        
        if ($ids_result->error) {
            // No valid ID selected
            $result->error = $ids_result->error;
        } else {
            // Check the columns
            $columns_result = checkColumns($table_result->query, $ids_result->data, 'columns');
            // Check the filters
            $filters_result = checkFilters($table_result->query, 'filters');
            // Check the columns to sort by
            $sort_result = checkSort($table_result->query, 'sort');
            // Check the calculations
            $calculations_result = checkCalculations($table_result->query, 'calculations');
            
            
            if ($columns_result->error) {
                // No valid columns selected
                $result->error = $columns_result->error;
            } else if ($filters_result->error) {
                // No valid filter selected
                $result->error = $filters_result->error;
            } else if ($sort_result->error) {
                // No valid sort selected
                $result->error = $sort_result->error;
            } else if ($calculations_result->error) {
                // No valid calculation selected
                $result->error = $calculations_result->error;
            } else {
                // Everything is checked and valid
                $result->data = new stdClass();
                $result->data->table = $table_result->query;
                $result->data->ids = $ids_result->data;
                $result->data->columns = $columns_result->data;
                $result->data->sort = $sort_result->data;
                $result->data->filters = $filters_result->data;
                $result->data->calculations = $calculations_result->data;
            }
        }   
    }
    
    return $result;
}

// Check a single paramter
function checkSingleParam($name) {
    // TODO: Check for insertion
    $param = filter_input(INPUT_GET, $name);
    return $param;
}

// Check multiple parameters
function checkMultParams($name) {
    // TODO: Check for insertion
    $param = filter_input(INPUT_GET, $name);
    $params = $param ? explode(';', $param) : [];
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
    
    return $result;
}

function checkColumns($table, $ids, $columns) {
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
    
    // There has to be one column, which is the primary ID
    if ($ids && (!$result->error) && ((!$result->data) || (count($result->data) == 0))) {
        $result->error = "No primary column is selected";
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

function checkFilters($table, $filters) {
    // The result object to save the results in
    $result = new result();
    
    // First check for insertion
    $filters_checked = checkMultParams($filters);
    $result->query = $filters_checked;
    
    for ($i = 0; $i < count($filters_checked); $i++) {
        $filter = $filters_checked[$i];
        $result_filter = isFilterValid($table, $filter);
        
        if ($result_filter->error) {
            $result->error = $result_filter->error;
            break;
        } else if ($result_filter->data == False) {
            $result->error = "No valid filter is selected";
            break;
        } else {
            $result->data[] = $result_filter->query;
        }
    }
    
    return $result;
}

function isFilterValid($table, $filter) {
    // The result object to save the results in
    $result = new result();
    $result->query = $filter;
    
    // Get a list of all valid filters
    $valid_filters = getValidFilters($table, $filter);
    if ($valid_filters->error) {
        // Something went wrong
        $result->error = $valid_filters->error;
        $result->data = false;
    } else if (count($valid_filters->data) == 0) {
        // Something went wrong
        $result->error = "Database does not return valid filters";
        $result->data = false;
    } else {
        if (($valid_filters !== null) && in_array($filter, $valid_filters->data)) {
            // The filter is in here
            $result->data = true;
        } else {
            // Not a valid filter
            $result->data = false;
        }
    }
    
    return $result;
}

function getValidFilters($table, $filter) {
    $filters = [];
    
    // Valid filters per table
    switch($table) {
        // TODO
        
        default:
            $filters[] = $filter;
            break;
    }
    
    return $filters;
}

function checkSort($table, $sorts) {
    // The result object to save the results in
    $result = new result();
    
    // First check for insertion
    $sorts_checked = checkMultParams($sorts);
    $result->query = $sorts_checked;
    
    for ($i = 0; $i < count($sorts_checked); $i++) {
        $sort = $sorts_checked[$i];
        $result_sort = isSortValid($table, $sort);
        
        if ($result_sort->error) {
            $result->error = $result_sort->error;
            break;
        } else if ($result_sort->data == False) {
            $result->error = "No valid sort is selected";
            break;
        } else {
            $result->data[] = $result_sort->query;
        }
    }
    
    return $result;  
}

function isSortValid($table, $sort) {
    // The result object to save the results in
    $result = new result();
    $result->query = $sort;
    
    // Amount of spaces should be 1
    if (substr_count($sort, ' ') != 1) {
        // Something went wrong
        $result->error = "No valid sort is selected";
        $result->data = false;
    } else {
        // Seperate the column and the order
        list($column, $order) = explode(' ', $sort);
        
        // Column can be checked using the column function
        $column_valid = isColumnValid($table, $column)->data;
        // Order is either ASC or DESC
        $order_valid = in_array(strtoupper($order), ["DESC", "ASC"]);
        if ($column_valid && $order_valid) {
            $result->query = $column." ".strtoupper($order);
            $result->data = true;
        } else {
            $result->error = "No valid sort is selected";
            $result->data = false;
        }
    }
    
    return $result;
}

function checkCalculations($table, $calculations) {
    // The result object to save the results in
    $result = new result();
    
    // First check for insertion
    $calculations_checked = checkMultParams($calculations);
    $result->query = $calculations_checked;
    
    for ($i = 0; $i < count($calculations_checked); $i++) {
        $calculation = $calculations_checked[$i];
        $result_calculation = isCalculationValid($table, $calculation);
        
        if ($result_calculation->error) {
            $result->error = $result_calculation->error;
            break;
        } else if ($result_calculation->data == False) {
            $result->error = "No valid calculation is selected";
            break;
        } else {
            $result->data[] = $result_calculation->query;
        }
    }
    
    return $result;
}

function isCalculationValid($table, $calculation) {
    // The result object to save the results in
    $result = new result();
    $result->query = $calculation;
    
    // Get a list of all valid calculations
    $valid_calcs = getValidCalculations($table, $calculation);
    if ($valid_calcs->error) {
        // Something went wrong
        $result->error = $valid_calcs->error;
        $result->data = false;
    } else if (count($valid_calcs->data) == 0) {
        // Something went wrong
        $result->error = "Database does not return valid calculations";
        $result->data = false;
    } else {
        if (($valid_calcs !== null) && in_array($calculation, $valid_calcs->data)) {
            // The calculation is in here
            $result->data = true;
        } else {
            // Not a valid calculation
            $result->data = false;
        }
    }
    
    return $result;
}

function getValidCalculations($table, $calculation) {
    $calculations = [];
    
    // Valid calculations per table
    switch($table) {
        // TODO
        
        default:
            $calculations[] = $calculation;
            break;
    }
    
    return $calculations;
}

function getWhereStatement($parameters) {
    $where_sql = "";
    
    
    if ($parameters->ids) {
        // We want these rows of this table
        $where_sql = $parameters->columns[0]." in (".implode(",", $parameters->ids).")";
    } 
    
    if ($where_sql != "") {
        $where_sql = " WHERE ".$where_sql;
    }
    
    return $where_sql;
}

function getSortStatement($parameters) {
    $sort_sql = "";
    
    if ($parameters->sort) {
        // Sort by these columns
        $sort_sql = implode(", ", $parameters->sort);
    }
    
    if ($sort_sql != "") {
        $sort_sql = " ORDER BY ".$sort_sql;
    }
    
    return $sort_sql;
}
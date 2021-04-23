<?php    
if (in_array($table, ["blog"])) {
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
         * - data (all columns that are to be filled)
         */
        $check_results = checkParams();

        // Parse the results
        if ($check_results->error) {
            // Checking returned an error
            $result->error = $check_results->error;
        } else {
            $checked_data = $check_results->data;

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
}

// Check all the parameters
function checkParams() {
    global $table;
    
    // The result object to save the results in
    $result = new result();
    
    // Check the columns
    $data_result = checkDataParam($table);

    if ($data_result->error) {
        // No valid columns selected
        $result->error = $data_result->error;
    } else {
        // Everything is checked and valid
        $result->data = new stdClass();
        $result->data = $data_result->data;
    }
    
    return $result;
}

function checkDataParam($table) {
    // The result object to save the results in
    $result = new result();
    
    // Get the data from the $_POST variable
    $data = filter_input(INPUT_POST, 'data');
    if ($data != null) {
        $data = json_decode($data);
    }
    
    // TODO: make sure insertion isn't used
    $result->query = $data;
    
    // These keys are required to be available
    switch($table) {
        case "blog":
            $required_keys = ["user"];
            break;
        
        default:
            $required_keys = [];
            break;
    }
    
    // Check these keys
    foreach ($data as $key => $value) {
        $column_result = checkColumn($table, $key);
        $value_result = checkValue($table, $key, $value);
        
        if (($column_result->error) || ($value_result->error)) {
            $result->error = $column_result->error | $value_result->error;
            break;
        } else {
            // If this in the array of required keys
            $key_idx = array_search($column_result->data, $required_keys);
            if ($key_idx !== false) {
                // Remove this from the required keys
                unset($required_keys[$key_idx]);
            }
            
            $result->data[$column_result->data] = $value_result->data;
        }
    }
    
    // Not all required keys are filled in
    if (count($required_keys) != 0) {
        $result->error = "The following required columns are missing: ".implode(",", $required_keys);
        $result->data = false;
    }
    
    return $result;
}

function checkColumn($table, $column) {
    // The result object to save the results in
    $result = new result();
    $result->query = $column;
    
    // Get a list of all valid tables
    $valid_columns = getValidColumns($table);
    if ($valid_columns->error) {
        // Something went wrong
        $result->error = $valid_columns->error;
    } else if (count($valid_columns->data) == 0) {
        // Something went wrong
        $result->error = "Database does not return valid columns";
    } else {
        if (($valid_columns !== null) && in_array($column, $valid_columns->data)) {
            // The column is in here
            $result->data = $column;
        } else {
            // Not a valid column
            $result->error = $column." is not a valid column";
        }
    }
    
    return $result;
}

/** Return all the valid columns */
function getValidColumns($table) {
    global $conn;
    
    // The result object to save the results in
    $result = new result();
    
    // The query to perform
    $sql = "SELECT COLUMN_NAME 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = '".$table."'";
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

function checkValue($table, $key, $value) {
    
    // The result object to save the results in
    $result = new result();
    $result->query = $value;
    
    switch($table) {
        // The parameter checks for the blog
        case "blog":
            // Title and text are not required and can be any value
            switch($key) {
                case "user":
                    // User has to be in the list of users
                    $user_result = checkUser($value);
                    if ($user_result->error) {
                        $result->error = $user_result->error;
                    } else {
                        $result->data = $user_result->data;
                    }
                    break;
                
                case "date":
                    // The date can be null, a given date or today
                    $date_result = checkDate($value);
                    if ($date_result->error) {
                        $result->error = $date_result->error;
                    } else {
                        $result->data = $date_result->data;
                    }
                    break;
            }
            break;
    }
        
    return $result;
}

function checkUser($user_name) {
    // TODO: Actually check the user name
    
    // The result object to save the results in
    $result = new result();
    $result->query = $user_name;
    
    if ($user_name != null) {
        $result->data = $user_name;
    } else {
        $result->error = "No username has been provided";
    }
    
    return $result;
}

function checkDate($date) {
    
    // The result object to save the results in
    $result = new result();
    $result->query = $date;
    
    if ($date != null) {
        //$result->data = $date;
    } else {
        
    }
    
    return $result;
    
}
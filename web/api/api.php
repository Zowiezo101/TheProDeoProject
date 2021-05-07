<?php

require "../../login_data.php";
require "crud/check.php";
require "crud/create.php";
require "crud/read.php";
//require "crud/update.php";
//require "crud/delete.php";

// Result class
class result {
    public $data;
    public $error;
    public $query;
};

function executeRequest($table) {
    // The result the return to the user
    $result = new result();
    
    // Get the allowed methods for this table
    switch($table) {
        case "blog":
            $allowed_methods = ["GET", "POST", "PUT", "DELETE"];
            break;
        
        case "books":
            $allowed_methods = ["GET"];
            break;
        
        case "events":
            $allowed_methods = ["GET"];
            break;
        
        case "activities":
            $allowed_methods = ["GET"];
            break;
        
        case "peoples":
            $allowed_methods = ["GET"];
            break;
        
        case "locations":
            $allowed_methods = ["GET"];
            break;
        
        case "specials":
            $allowed_methods = ["GET"];
            break;
        
        default:
            $allowed_methods = [];
            break;
    }
    
    // Get the method type itself
    $method_type = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
    
    // If this method an allowed type?
    if (in_array($method_type, $allowed_methods) == false) {
        $result->error = $method_type.' is not a supported type for '.$table;
    } else {
        switch($method_type) {
            case 'POST':
                // The request is using the POST method
                $result = executeCreateRequest($table);
                break;
            
            case 'GET':
                // The request is using the GET method
                $result = executeReadRequest($table);
                break;

            case 'PUT':
                // The request is using the PUT method
                $result = executeUpdateRequest($table);
                break;

            case 'DELETE':
                // The request is using the DELETE method
                $result = executeDeleteRequest($table);
                break;
        }
    }

    echo json_encode($result);
}

function executeCreateRequest($table) {
    // The result to return to the user
    $result = new result();
    
    $conn = getConnection();
    if ($conn->error) {
        $result->error = $conn->error;
    } else {
        // Check the parameters
        $params = checkCreateParameters($table);
        
        if (!$params->error) {
            // Create the SQL statement
            $sql = createCreateSql($conn->data, $params->data);
        
            // Execute SQL
            $result = executeQuery($conn->data, $sql);
        } else {
            $result->error = $params->error;
        }

        // close mysql connection
        mysqli_close($conn->data);
    }
    
    return $result;
}

function executeReadRequest($table) {
    // The result to return to the user
    $result = new result();
    
    $conn = getConnection();
    if ($conn->error) {
        $result->error = $conn->error;
    } else {
        // Check the parameters
        $params = checkReadParameters($conn->data, $table);
        
        if (!$params->error) {
            // Create the SQL statement
            $sql = createReadSql($conn->data, $params->data);
        
            // Execute SQL
            $result = executeQuery($conn->data, $sql);
        } else {
            $result->error = $params->error;
        }

        // close mysql connection
        mysqli_close($conn->data);
    }
    
    return $result;
}

function executeUpdateRequest($table) {
    // The result to return to the user
    $result = new result();
    
    $conn = getConnection();
    if ($conn->error) {
        $result->error = $conn->error;
    } else {
        // Check the parameters
        $params = checkUpdateParameters($conn->data, $table);
        
        if (!$params->error) {
            // Create the SQL statement
            $sql = createUpdateSql($conn->data, $params->data);
        
            // Execute SQL
            $result = executeQuery($conn->data, $sql);
        } else {
            $result->error = $params->error;
        }

        // close mysql connection
        mysqli_close($conn->data);
    }
    
    return $result;
}

function executeDeleteRequest($table) {
    // The result to return to the user
    $result = new result();
    
    $conn = getConnection();
    if ($conn->error) {
        $result->error = $conn->error;
    } else {
        // Check the parameters
        $params = checkDeleteParameters($conn->data, $table);
        
        if (!$params->error) {
            // Create the SQL statement
            $sql = createDeleteSql($conn->data, $params->data);
        
            // Execute SQL
            $result = executeQuery($conn->data, $sql);
        } else {
            $result->error = $params->error;
        }

        // close mysql connection
        mysqli_close($conn->data);
    }
    
    return $result;
}

function getConnection() {
    global $servername, $username, $password, $database;
    
    // The result to return to the user
    $result = new result();

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        $result->error = "Connection failed: " . $conn->connect_error;
    } else {
        // Connection succeeded
        $result->data = $conn;
    }
    
    return $result;
}

function executeQuery($conn, $sql) {   
    
    // The result to return to the user
    $result = new result();
    $result->query = $sql;
    
    if ($sql->error) {
        $result->error = $sql->error;
    } else if ($sql->data && isset($sql->data->self)) {
        $result->data = new stdClass();
        
        foreach ($sql->data as $key => $value) {
            $data = executeQuery($conn, $value);
            $result->data->{$key} = $data->data;
        }
    } else if ($sql->data) {
        $sql->data->execute();
        $results = $sql->data->get_result();

        // If something went wrong, 
        // or just no results (error is not set in that case)
        if (!$results) {
            $result->error = mysqli_error($conn);
        }

        if (!$result->error && $results && (mysqli_num_rows($results) > 0)) {
            // Put the results in the array
            $result->data = Array();
            for ($i = 0; $i < mysqli_num_rows($results); $i++) {
                $result->data[] = mysqli_fetch_object($results);
            }
        }
    }
    
    return $result;
}


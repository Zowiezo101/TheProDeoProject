<?php    

// Check all the parameters
function checkReadParameters($table) {
    // The result to return to the user
    $result = new result();
    
    switch($table) {
        case "blog":
            $result = checkBlogReadParams();
            break;
        
        default:
            $result->error = 'GET is not a supported type for '.$table;
            break;
    }
    
    return $result;
}

function createReadSql($conn, $params_obj) {
    
    // The result to return to the user
    $result = new result();
    $result->query = $params_obj;
    
    $params = get_object_vars($params_obj);
    switch($params["table"]) {
        case "blog":
            // Identifiers are not allowed in binding, so we need to 
            // create a sql query per table
            $sql = createBlogReadSql($conn, $params);
            break;
    }
    
    

            $sql_where = getWhereStatement($check_results->data);
            $sql_sort = getSortStatement($check_results->data);

            // The final SQL query
            $sql = "SELECT * FROM ".$checked_table.$sql_where.$sql_sort;
    
    if (!$sql) {
        // Something went wrong
        $result->error = mysqli_error($conn);
    } else {
        $result->data = $sql;
    }
    
    return $result;
}

/**
 * Different kinds of parameter checking
 **/
function checkBlogReadParams() {
    // For reading a blog, we have the following params:
    // id (number)
    // columns (Must be in the list of columns)
    // sort (Must be in the list of columns + ASC/DESC)
    
    // The result object to save the results in
    $result = new result();
    
    // Get the data from the $_GET variable (returned as array, we want an object)
    $data = json_decode(json_encode(filter_input_array(INPUT_GET)), False);
    
    // The data that will be checked
    $result->query = $data;
    $result->data = new stdClass();
    $result->data->table = "blog";
    
    // We have no required parameters
    //if (isset($data->title) && isset($data->text) && isset($data->user))
        
    // Check the id
    if (isset($data->id)) {
        $id_check = is_numeric($data->title);
        if (!$id_check) {
            $result->error = "'id' is not in a number format";
        } else {
            // Copy the id from the $data variable
            $result->data->id = $data->id;
        }
    }

    // Check the columns
    if (!isset($result->$data->id) && isset($data->columns)) {
        $column_check = is_column_array($result->data->table, $data->columns);
        if (!$column_check) {
            $result->error = "'columns' contains invalid columns";
        } else {
            // Copy the columns from the $data variable
            $result->data->columns = $data->columns;
        }
    }

    // Check the sorts
    if (!isset($result->$data->id) && isset($data->sort)) {
        $sort_check = is_sort_array($result->data->table, $data->sort);
        if (!$sort_check) {
            $result->error = "'sort' contains invalid sortss";
        } else {
            // Copy the sort from the $data variable
            $result->data->sort = $data->sort;
        }
    }

    // The rest of $data is ignored
    
    return $result;
}

function createBlogSql($conn, $params) {

    // Insert question marks to fill these in later
    // This is to prevent SQL injection
    $sql = mysqli_prepare($conn, "INSERT INTO blog (title, text, user, date) VALUES (?,?,?,?)");
    
    if ($sql) {
        $title = mysqli_real_escape_string($conn, $params["title"]);
        $text = mysqli_real_escape_string($conn, $params["text"]);
        $user = mysqli_real_escape_string($conn, $params["user"]);
        $date = mysqli_real_escape_string($conn, $params["date"]);
        
        $sql->bind_param("ssss", $title, $text, $user, $date);
    }
    
    return $sql;
}

function getWhereStatement($parameters) {
    $where_sql = "";
    
    
    if ($parameters->id != null) {
        // We want these rows of this table
        $where_sql = $parameters->columns[0]." = ".$parameters->id;
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
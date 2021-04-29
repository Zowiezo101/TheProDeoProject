<?php    

// Check all the parameters
function checkReadParameters($conn, $table) {
    // The result to return to the user
    $result = new result();
    
    switch($table) {
        case "blog":
            $result = checkBlogReadParams($conn);
            break;
        
        case "books":
            $result = checkBooksReadParams($conn);
            break;
        
        default:
            $result->error = 'GET is not a supported type for '.$table;
            break;
    }
    
    return $result;
}

function createReadSql($conn, $params) {
    
    // The result to return to the user
    $result = new result();
    $result->query = $params;
    
    switch($params->table) {
        case "blog":
            // Identifiers are not allowed in binding, so we need to 
            // create a sql query per table
            $sql = createBlogReadSql($conn, $params);
            break;
    }
    
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
function checkBlogReadParams($conn) {
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
        $id_check = is_numeric($data->id);
        if (!$id_check) {
            $result->error = "'id' is not in a number format";
        } else {
            // Copy the id from the $data variable
            $result->data->id = $data->id;
        }
    }

    // Check the columns
    if (!isset($result->data->id) && isset($data->columns)) {
        $column_check = is_column_string($conn, $result->data->table, $data->columns);
        if (!$column_check) {
            $result->error = "'columns' contains invalid columns";
        } else {
            // Copy the columns from the $data variable
            $result->data->columns = $data->columns;
        }
    }

    // Check the sorts
    if (!isset($result->data->id) && isset($data->sort)) {
        $sort_check = is_sort_string($conn, $result->data->table, $data->sort);
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

function checkBooksReadParams($conn) {
    // For reading a book, we have the following params:
    // id (number)
    // filters (Must be in the list of columns + option + value)
    // columns (Must be in the list of columns)
    // sort (Must be in the list of columns + ASC/DESC)
    // offset & limit (number)
    
    // The result object to save the results in
    $result = new result();
    
    // Get the data from the $_GET variable (returned as array, we want an object)
    $data = json_decode(json_encode(filter_input_array(INPUT_GET)), False);
    
    // The data that will be checked
    $result->query = $data;
    $result->data = new stdClass();
    $result->data->table = "books";
    
    // We have no required parameters
    //if (isset($data->title) && isset($data->text) && isset($data->user))
        
    // Check the id
    if (isset($data->id)) {
        $id_check = is_numeric($data->id);
        if (!$id_check) {
            $result->error = "'id' is not in a number format";
        } else {
            // Copy the id from the $data variable
            $result->data->id = $data->id;
        }
    }

    if (!isset($result->data->id)) {
        // Check the columns
        if (isset($data->columns)) {
            $column_check = is_column_string($conn, $result->data->table, $data->columns);
            if (!$column_check) {
                $result->error = "'columns' contains invalid columns";
            } else {
                // Copy the columns from the $data variable
                $result->data->columns = $data->columns;
            }
        }
        
        // Check the filters
        if (isset($data->filters)) {
            $filter_check = is_filter_string($conn, $result->data->table, $data->filters);
            if (!$filter_check) {
                $result->error = "'filters' contains invalid filters";
            } else {
                // Copy the columns from the $data variable
                $result->data->filters = $data->filters;
            }
        }

        // Check the sorts
        if (isset($data->sort)) {
            $sort_check = is_sort_string($conn, $result->data->table, $data->sort);
            if (!$sort_check) {
                $result->error = "'sort' contains invalid sorts";
            } else {
                // Copy the sort from the $data variable
                $result->data->sort = $data->sort;
            }
        }
        
        // Check the limit
        if (isset($data->limit)) {
            $limit_check = is_numeric($data->limit);
            if (!$limit_check) {
                $result->error = "'limit' is not in a number format";
            } else {
                // Copy the limit from the $data variable
                $result->data->limit = $data->limit;
            }
        }
        // Check the offset
        if (isset($data->offset)) {
            $offset_check = is_numeric($data->offset);
            if (!$offset_check) {
                $result->error = "'offset' is not in a number format";
            } else {
                // Copy the offset from the $data variable
                $result->data->offset = $data->offset;
            }
        }
    }

    // The rest of $data is ignored
    
    return $result;
}

function createBlogReadSql($conn, $params) {

    $sql_select = getSelectStatement($params);
    $sql_where = getWhereStatement($params);
    $sql_sort = getSortStatement($params);

    // The final SQL query
    $sql = mysqli_prepare($conn, "SELECT ".$sql_select." FROM ".$params->table.$sql_where.$sql_sort);
    
    return $sql;
}

function getSelectStatement($parameters) {
    // The default columns to select
    $columns = getDefaultColumns($parameters);
    
    if (isset($parameters->columns)) {
        // Add these columns to the set of columns
        $columns = array_merge($columns, explode(',', $parameters->columns));
    }
    
    // We want these rows of this table
    $select_sql = implode(",", array_unique($columns));
    
    return $select_sql;
}

function getWhereStatement($parameters) {
    $where_sql = "";
    
    if (isset($parameters->id)) {
        switch($parameters->table) {
            case "blog":
                // We want these rows of this table
                $where_sql = "id = ".$parameters->id;
                break;
        }
    } 
    
    if ($where_sql != "") {
        $where_sql = " WHERE ".$where_sql;
    }
    
    return $where_sql;
}

function getSortStatement($parameters) {
    $sort_sql = "";
    
    if (isset($parameters->sort)) {
        // Sort by these columns
        $sort_sql = $parameters->sort;
    }
    
    if ($sort_sql != "") {
        $sort_sql = " ORDER BY ".$sort_sql;
    }
    
    return $sort_sql;
}

function getDefaultColumns($parameters) {
    $columns = [];
    
    switch($parameters->table) {
        case "blog":
            if (isset($parameters->id)) {
                $columns[] = "*";
            } else {
                $columns[] = "title";
                $columns[] = "date";
            }
            break;
    }
    
    return $columns;
}
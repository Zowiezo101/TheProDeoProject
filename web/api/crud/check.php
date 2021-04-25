<?php

function is_user($user) {
    // TODO:
    // Right now, everything string is accepted until I have more users
    return is_string($user);
}

function is_column_array($table, $columns) {
    // The result to return
    $result = False;
    
    // The connection used to get the valid columns for this table
    $conn = getConnection();
    if (!$conn->error) {
        // All the valid columns for this table
        $valid_columns = get_valid_columns($conn, $table);
        if (!$valid_columns || count($valid_columns) == 0) {
            // No valid columns are returned..
            return $result;
        }
        
        if ($columns && is_array($columns)) {
            for ($i = 0; $i < count($columns); $i++) {
                // We have an array of columns, now check all the columns are valid
                $column = $columns[$i];
                
                // Is this column valid
                $is_column = in_array($column, $valid_columns);
                if (!$is_column) {
                    // It is not, break the for loop
                    $result = False;
                    break;
                }
            }
            
            // We ended with is_column being True, 
            // meaning that all columns were valid
            if ($is_column) {
                $result = True;
            }
        }

        // close mysql connection
        mysqli_close($conn->data);
    }
    return $result;   
}

function is_sort_array($table, $sort) {
    
}

/** Return all the valid columns */
function get_valid_columns($conn, $table) {
        
    // The query to perform
    $sql = "SELECT COLUMN_NAME 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = '".$table."'";
    $results = mysqli_query($conn, $sql);
    
    // Save the results (if there are any)
    if ($results) {
        $result = Array();
        for ($i = 0; $i < mysqli_num_rows($results); $i++) {
            $object = mysqli_fetch_object($results);
            $result[] = $object->COLUMN_NAME ? $object->COLUMN_NAME : "";
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

<?php

function is_user($user) {
    // TODO:
    // Right now, everything string is accepted until I have more users
    return is_string($user);
}

function is_column_string($conn, $table, $columns) {
    // The result to return
    $result = False;
    
    // All the valid columns for this table
    $valid_columns = get_valid_columns($conn, $table);
    if (!$valid_columns || count($valid_columns) == 0) {
        // No valid columns are returned..
        return $result;
    }

    if ($columns && is_string($columns)) {
        $column_array = explode(',', $columns);
        for ($i = 0; $i < count($column_array); $i++) {
            // We have an array of columns, now check all the columns are valid
            $column = trim($column_array[$i]);

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
    
    return $result;   
}

function is_filter_String($conn, $table, $filters) {
    // The result to return
    $result = False;
    
    // All the valid columns for this table
    $valid_columns = get_valid_columns($conn, $table);
    if (!$valid_columns || count($valid_columns) == 0) {
        // No valid columns are returned..
        return $result;
    }        

    if ($filters && is_string($filters)) {
        $filter_array = preg_split('/(,|\|)/', $filters, -1, PREG_SPLIT_NO_EMPTY);
        for ($i = 0; $i < count($filter_array); $i++) {
            // We have an array of filters, now check all the filters are valid
            $filter = trim($filter_array[$i]);

            // Divide filter into the column, option and the value
            $column = trim(preg_split('/(!=|=|>=|>|<=|<|!%|%)/', $filter, -1, PREG_SPLIT_DELIM_CAPTURE)[0]);
            $option = trim(preg_split('/(!=|=|>=|>|<=|<|!%|%)/', $filter, -1, PREG_SPLIT_DELIM_CAPTURE)[1]);
            $value = trim(preg_split('/(!=|=|>=|>|<=|<|!%|%)/', $filter, -1, PREG_SPLIT_DELIM_CAPTURE)[2]);

            // Is this column and direction valid
            $is_column = in_array($column, $valid_columns);
            $is_option = in_array($option, ["=", "!=", ">=", "<=", ">", "<", "%", "!%"]);
            if (!$is_column) {
                // It is not, break the for loop
                $result = False;
                break;
            } else if (!$is_option) {
                // It is not, break the for loop
                $result = False;
                break;
            }
        }

        // We ended with is_column and direction being True, 
        // meaning that all sorts were valid
        if ($is_column && $is_option) {
            $result = True;
        }
    }
        
    return $result;  
}

function is_sort_string($conn, $table, $sorts) {
    // The result to return
    $result = False;
    
    // All the valid columns for this table
    $valid_columns = get_valid_columns($conn, $table);
    if (!$valid_columns || count($valid_columns) == 0) {
        // No valid columns are returned..
        return $result;
    }        

    if ($sorts && is_string($sorts)) {
        $sort_array = explode(',', $sorts);
        for ($i = 0; $i < count($sort_array); $i++) {
            // We have an array of sorts, now check all the sorts are valid
            $sort = trim($sort_array[$i]);

            // Divide sort into the column and the direction
            $column = explode(' ', $sort)[0];
            $direction = explode(' ', $sort)[1];

            // Is this column and direction valid
            $is_column = in_array($column, $valid_columns);
            $is_direction = in_array(strtolower($direction), ["asc", "desc"]);
            if (!$is_column) {
                // It is not, break the for loop
                $result = False;
                break;
            } else if (!$is_direction) {
                // It is not, break the for loop
                $result = False;
                break;
            }
        }

        // We ended with is_column and direction being True, 
        // meaning that all sorts were valid
        if ($is_column && $is_direction) {
            $result = True;
        }
    }
        
    return $result;  
}

function is_to_string($table, $to) {
    // Allowed tables to link to
    $linking_tables = ["all"];
    switch($table) {
        case "events":
            $linking_tables[] = "next";
            $linking_tables[] = "previous";
            $linking_tables[] = "activities";
            $linking_tables[] = "peoples";
            $linking_tables[] = "locations";
            $linking_tables[] = "specials";
            break;
        
        case "activitys":
            $linking_tables[] = "next";
            $linking_tables[] = "previous";
            $linking_tables[] = "activities";
            $linking_tables[] = "events";
            $linking_tables[] = "peoples";
            $linking_tables[] = "locations";
            $linking_tables[] = "specials";
            break;
            
        case "peoples":
            $linking_tables[] = "activities";
            $linking_tables[] = "events";
            $linking_tables[] = "parents";
            $linking_tables[] = "children";
            $linking_tables[] = "peoples";
            $linking_tables[] = "locations";
            break;
        
        case "locations":
            $linking_tables[] = "activities";
            $linking_tables[] = "events";
            $linking_tables[] = "peoples";
            $linking_tables[] = "locations";
            break;
        
        case "specials":
            $linking_tables[] = "activities";
            $linking_tables[] = "events";
            break;
    }
    
    return in_array($to, $linking_tables);
}

function is_calculation_string($calculations) {
    // The result to return
    $result = False;

    if ($calculations && is_string($calculations)) {
        $calculation_array = explode(',', $calculations);
        for ($i = 0; $i < count($calculation_array); $i++) {
            // We have an array of calculations, now check all the calculations are valid
            $calculation = trim($calculation_array[$i]);

            // Is this calculation valid
            $is_calculation = in_array(strtolower($calculation), ["count"]);
            if (!$is_calculation) {
                // It is not, break the for loop
                $result = False;
                break;
            }
        }

        // We ended with is_calculation being True, 
        // meaning that all calculations were valid
        if ($is_calculation) {
            $result = True;
        }
    }
    
    return $result;   
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
    
    switch($table) {
        case "events":
            $result[] = "activitys.id";
            $result[] = "activitys.descr";
            $result[] = "events1.id";
            $result[] = "events1.name";
            $result[] = "events2.id";
            $result[] = "events2.name";
            $result[] = "peoples.id";
            $result[] = "peoples.name";
            $result[] = "locations.id";
            $result[] = "locations.name";
            $result[] = "specials.id";
            $result[] = "specials.name";
            break;
        
        case "activitys":
            $result[] = "activitys1.id";
            $result[] = "activitys1.descr";
            $result[] = "activitys2.id";
            $result[] = "activitys2.descr";
            $result[] = "events.id";
            $result[] = "events.name";
            $result[] = "peoples.id";
            $result[] = "peoples.name";
            $result[] = "locations.id";
            $result[] = "locations.name";
            $result[] = "specials.id";
            $result[] = "specials.name";
            break;
        
        case "peoples":
            $result[] = "activitys.id";
            $result[] = "activitys.descr";
            $result[] = "events.id";
            $result[] = "events.name";
            $result[] = "parents.id";
            $result[] = "parents.name";
            $result[] = "childs.id";
            $result[] = "childs.name";
            $result[] = "peoples1.id";
            $result[] = "peoples1.name";
            $result[] = "peoples2.id";
            $result[] = "peoples2.name";
            $result[] = "locations.id";
            $result[] = "locations.name";
            $result[] = "locations.type";
            break;
        
        case "locations":
            $result[] = "activitys.id";
            $result[] = "activitys.descr";
            $result[] = "events.id";
            $result[] = "events.name";
            $result[] = "peoples.id";
            $result[] = "peoples.name";
            $result[] = "peoples.type";
            $result[] = "locations1.id";
            $result[] = "locations1.name";
            $result[] = "locations2.id";
            $result[] = "locations2.name";
            break;
        
        case "specials":
            $result[] = "activitys.id";
            $result[] = "activitys.descsr";
            $result[] = "events.id";
            $result[] = "events.name";
            break;
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

?>
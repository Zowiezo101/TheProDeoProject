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
        case "events":
        case "activities":
        case "peoples":
        case "locations":
        case "specials":
            $result = checkReadParams($conn, $table);
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
        
        case "books":
        case "events":
        case "activities":
        case "peoples":
        case "locations":
        case "specials":
            $sql = createItemReadSql($conn, $params);
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

function checkReadParams($conn, $table) {
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
    $result->data->table = $table == "activities" ? "activitys" : $table;
    
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
        
        // Check the calculations
        if (isset($data->calculations)) {
            $calcultion_check = is_calculation_string($data->calculations);
            if (!$calcultion_check) {
                $result->error = "'calculations' contains invalid calcs";
            } else {
                // Copy the calculation from the $data variable
                $result->data->calculations = $data->calculations;
            }
        }
        
    } else {
        if (isset($data->to)) {
            $to_check = is_to_string($result->data->table, $data->to);
            if (!$to_check) {
                $result->error = "Trying to link to an invalid table";
            } else {
                // Copy the offset from the $data variable
                $result->data->to = $data->to == "activities" ? "activitys" : $data->to;
            }
        }
    }

    // The rest of $data is ignored
    
    return $result;
}

function createBlogReadSql($conn, $params) {

    $sql_select = getSelectStatement($params);
    $sql_where = getWhereStatement($conn, $params);
    $sql_sort = getSortStatement($params);

    // The final SQL query
    $sql = mysqli_prepare($conn, "SELECT ".$sql_select." FROM ".$params->table.$sql_where.$sql_sort);
    
    return $sql;
}

function createItemReadSql($conn, $params) {
    
    $sql_select = getSelectStatement($params);
    $sql_where = getWhereStatement($conn, $params);
    $sql_sort = getSortStatement($params);
    $sql_limit_offset = getLimitOffsetStatement($params);

    // The currect query if we don't have any linking tables
    $query = "SELECT ".$sql_select." FROM ".$params->table.$sql_where.$sql_sort.$sql_limit_offset;
    $query = getToStatement($conn, $params, $query);
    
    // The final SQL query
    if (isset($params->to) && ($params->to == "all")) {
        $sql = $query;
    } else {
        $sql = mysqli_prepare($conn, $query);
    }
    
    return $sql;
}

function getSelectStatement($parameters) {
    // The default columns to select
    $columns = getDefaultColumns($parameters);
    
    if (isset($parameters->calculations)) {
        // Add the count parameter
        $columns = ["COUNT(".$columns[0].") as count"];
    } elseif (isset($parameters->columns)) {
        // Add these columns to the set of columns
        $columns = array_merge($columns, explode(',', $parameters->columns));
    }
    
    // We want these rows of this table
    $select_sql = implode(",", array_unique($columns));
    
    return $select_sql;
}

function getWhereStatement($conn, $parameters) {
    $where_sql_parts = [];
    
    if (isset($parameters->id)) {
        // We want these rows of this table
        $where_sql_parts[] = "id = ".$parameters->id;
    }
    
    if (isset($parameters->filters)) {
        // Get all the ANDed filters
        $filters_and = explode(',', $parameters->filters);
        
        // The ANDed filters are separated by ','
        // The ORed filters are separated by '||'
        $sql_and_parts = [];
        for ($i = 0; $i < count($filters_and); $i++) {
            $filters_or = explode('||', $filters_and[$i]);
            
            if (count($filters_or) > 1) {
                $sql_or_parts = [];
                for ($j = 0; $j < count($filters_or); $j++) {
                    $sql_or_parts[] = convertFilterToSql($conn, $filters_or[$j]);
                }
                $sql_and_parts[] = "(".implode(" OR ", $sql_or_parts).")";
            } else {
                $sql_and_parts[] = convertFilterToSql($conn, $filters_or[0]);
            }
        }
        $where_sql_parts[] = implode(" AND ", $sql_and_parts);
    }
    
    if (count($where_sql_parts) > 0) {
        $where_sql = " WHERE ".implode(" AND ", $where_sql_parts);
    } else {
        $where_sql = "";
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

function getLimitOffsetStatement($parameters) {
    $limit_offset_sql = "";
    
    if (isset($parameters->limit)) {
        $limit_offset_sql = $limit_offset_sql." LIMIT ".$parameters->limit;
    }
    
    if (isset($parameters->offset)) {
        $limit_offset_sql = $limit_offset_sql." OFFSET ".$parameters->offset;
    }
    
    return $limit_offset_sql;
}

function getToStatement($conn, $parameters, $query) {
    if (isset($parameters->to)) {
        if ($parameters->to == "all") {
            // We want everything, make a different query for every table
            $query = new stdClass();
            $params = clone $parameters;
            
            switch($parameters->table) {
                case "books":
                    unset($params->to);
                    $query->self = createReadSql($conn, $params);
                    break;
                    
                case "events":
                    $params->to = "next";
                    $query->next = createReadSql($conn, $params);
                    
                    $params->to = "previous";
                    $query->previous = createReadSql($conn, $params);
                    
                    $params->to = "activitys";
                    $query->activitys = createReadSql($conn, $params);
                    
                    $params->to = "peoples";
                    $query->peoples = createReadSql($conn, $params);
                    
                    $params->to = "locations";
                    $query->locations = createReadSql($conn, $params);
                    
                    $params->to = "specials";
                    $query->specials = createReadSql($conn, $params);
                    
                    unset($params->to);
                    $query->self = createReadSql($conn, $params);
                    break;
                
                case "activitys":
                    $params->to = "next";
                    $query->next = createReadSql($conn, $params);
                    
                    $params->to = "previous";
                    $query->previous = createReadSql($conn, $params);
                    
                    $params->to = "events";
                    $query->events = createReadSql($conn, $params);
                    
                    $params->to = "peoples";
                    $query->peoples = createReadSql($conn, $params);
                    
                    $params->to = "locations";
                    $query->locations = createReadSql($conn, $params);
                    
                    $params->to = "specials";
                    $query->specials = createReadSql($conn, $params);
                    
                    unset($params->to);
                    $query->self = createReadSql($conn, $params);
                    break;
                
                case "peoples":
                    $params->to = "events";
                    $query->events = createReadSql($conn, $params);
                    
                    $params->to = "activitys";
                    $query->activitys = createReadSql($conn, $params);
                    
                    $params->to = "peoples";
                    $query->peoples = createReadSql($conn, $params);
                    
                    $params->to = "parents";
                    $query->parents = createReadSql($conn, $params);
                    
                    $params->to = "children";
                    $query->children = createReadSql($conn, $params);
                    
                    $params->to = "locations";
                    $query->locations = createReadSql($conn, $params);
                    
                    $params->to = "specials";
                    $query->specials = createReadSql($conn, $params);
                    
                    unset($params->to);
                    $query->self = createReadSql($conn, $params);
                    break;
                
                case "locations":
                    $params->to = "events";
                    $query->events = createReadSql($conn, $params);
                    
                    $params->to = "activitys";
                    $query->activitys = createReadSql($conn, $params);
                    
                    $params->to = "peoples";
                    $query->peoples = createReadSql($conn, $params);
                    
                    $params->to = "locations";
                    $query->locations = createReadSql($conn, $params);
                    
                    $params->to = "specials";
                    $query->specials = createReadSql($conn, $params);
                    
                    unset($params->to);
                    $query->self = createReadSql($conn, $params);
                    break;
                
                case "specials":
                    $params->to = "events";
                    $query->events = createReadSql($conn, $params);
                    
                    $params->to = "activitys";
                    $query->activitys = createReadSql($conn, $params);
                    
                    $params->to = "peoples";
                    $query->peoples = createReadSql($conn, $params);
                    
                    $params->to = "locations";
                    $query->locations = createReadSql($conn, $params);
                    
                    unset($params->to);
                    $query->self = createReadSql($conn, $params);
                    break;
            }
        } else {
            // FROM
            $table1 = substr($parameters->table, 0, -1);
            // TO
            $table2 = substr($parameters->to, 0, -1);

            if ($table2 == "parent") {
                $table = "people_to_parent";

                $query = "select distinct(peoples.id), peoples.name from people_to_parent
                            join peoples on people_to_parent.parent_id = peoples.id
                            where people_to_parent.people_id = ".$parameters->id;
            } else if ($table2 == "childre") {
                $query = "select distinct(peoples.id), peoples.name from people_to_parent
                            join peoples on people_to_parent.people_id = peoples.id
                            where people_to_parent.parent_id = ".$parameters->id;
            } else if($table2 == "previou") {
                $query = "select distinct(events.id), events.name from event_to_event
                            join events on event_to_event.event1_id = events.id
                            where event_to_event.event2_id = ".$parameters->id;
            } else if ($table2 == "nex") {
                $query = "select distinct(events.id), events.name from event_to_event
                            join events on event_to_event.event2_id = events.id
                            where event_to_event.event1_id = ".$parameters->id;
            } else if($table1 == $table2) {
                $table = $table1."_to_".$table2;
                $id1 = $table1."1_id";
                $id2 = $table2."2_id";

                // Only asking for the linking table results
                $query = "select distinct(".$table2."s.id), ".$table2."s.name from ".$table."
                            join ".$table2."s on ".$table.".".$id1." = ".$table2."s.id
                            where ".$table.".".$id2." = ".$parameters->id."
                            union
                        select distinct(".$table2."s.id), ".$table2."s.name from ".$table."
                            join ".$table2."s on ".$table.".".$id2." = ".$table2."s.id
                            where ".$table.".".$id1." = ".$parameters->id;
            } else {
                $linking_tables = [
                    "activity_to_event",
                    "location_to_activity",
                    "people_to_activity",
                    "people_to_location",
                    "special_to_activity",
                    "event_to_people",
                    "event_to_location",
                    "event_to_special",
                ];

                if (in_array($table1."_to_".$table2, $linking_tables)) {
                    $table = $table1."_to_".$table2;
                } else {
                    $table = $table2."_to_".$table1;
                }
                $id1 = $table1."_id";
                $id2 = $table2."_id";
                $name = "name";
                $type = "";
                if ($table2 == "activity") {
                    $name = "descr";
                } else if ($table == "people_to_location") {
                    // Only in case of this table, we want a type as well
                    $type = ", people_to_location.type";
                }

                // Only asking for the linking table results
                if (!in_array($table, ["event_to_people", "event_to_location", "event_to_special"])) {
                    $query = "select distinct(".$table2."s.id), ".$table2."s.".$name.$type." from ".$table."
                                join ".$table2."s on ".$table.".".$id2." = ".$table2."s.id
                                where ".$table.".".$id1." = ".$parameters->id;
                } else {
                    // These tables don't exist as is, we need a little more work for this
                    if ($table1 == "event") {
                        $query = "select distinct(".$table2."s.id), ".$table2."s.".$name." from events 
                                    join activity_to_event on events.id = activity_to_event.event_id 
                                    join ".$table2."_to_activity on activity_to_event.activity_id = ".$table2."_to_activity.activity_id 
                                    join ".$table2."s on ".$table2."_to_activity.".$id2." = ".$table2."s.id
                                    WHERE events.id = ".$parameters->id;
                    } else {
                        $query = "select distinct(events.id), events.name from ".$table1."s
                                    join ".$table1."_to_activity on ".$table1."s.id = ".$table1."_to_activity.".$id1."
                                    join activity_to_event on ".$table1."_to_activity.activity_id = activity_to_event.activity_id
                                    join events on activity_to_event.event_id = events.id
                                    where ".$table1."s.id = ".$parameters->id;
                    }
                }
            }
        }
    }
    
    return $query;
}

function getDefaultColumns($parameters) {
    $columns = [];
    
    if (isset($parameters->id)) {
        $columns[] = "*";
    } else {
        $columns[] = "id";
        switch($parameters->table) {
            case "blog":
                $columns[] = "title";
                $columns[] = "date";
                break;

            case "books":
            case "events":
            case "activitys":
            case "peoples":
            case "locations":
            case "specials":
                $columns[] = "name";
                break;
        }
    }
    
    return $columns;
}

function convertFilterToSql($conn, $filter) {
    $sql = "";
    
    // Divide filter into 3 pieces
    $column = trim(preg_split('/(!=|=|>=|>|<=|<|!%|%)/', $filter, -1, PREG_SPLIT_DELIM_CAPTURE)[0]);
    $option = trim(preg_split('/(!=|=|>=|>|<=|<|!%|%)/', $filter, -1, PREG_SPLIT_DELIM_CAPTURE)[1]);
    $value = trim(preg_split('/(!=|=|>=|>|<=|<|!%|%)/', $filter, -1, PREG_SPLIT_DELIM_CAPTURE)[2]);
    
    switch($option) {
        case "=":
        case ">=":
        case "<=":
        case ">":
        case "<":
            $sql = $column.$option."'".mysqli_real_escape_string($conn, $value)."'";
            break;
        
        case "!=":
            $sql = $column."<>'".mysqli_real_escape_string($conn, $value)."'";
            break;
        
        case "%":
            $sql = $column." LIKE '%".mysqli_real_escape_string($conn, $value)."%'";
            break;
        
        case "%":
            $sql = $column." NOT LIKE '%".mysqli_real_escape_string($conn, $value)."%'";
            break;
    }
    
    return $sql;
}
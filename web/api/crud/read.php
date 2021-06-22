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
    $sql_join = getJoinStatement($params);
    $sql_where = getWhereStatement($conn, $params);
    $sql_sort = getSortStatement($params);
    $sql_limit_offset = getLimitOffsetStatement($params);

    // The currect query if we don't have any linking tables
    $query = "SELECT ".$sql_select." FROM ".$params->table.$sql_join.$sql_where.$sql_sort.$sql_limit_offset;
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
        // Add these columns to the set of columns
        $calculation_arr = explode(',', $parameters->calculations);
        
        for ($i = 0; $i < count($calculation_arr); $i++) {
            $calculation = $calculation_arr[$i];
            
            if ($calculation == "count") {
                // Add the count parameter
                $columns[] = "COUNT(".$columns[0].") as count";
            } else {
                if (strpos($calculation, "max") !== false) {
                    // Add the max parameter
                    $columns[] = "MAX(".explode("_", $calculation, 2)[1].") as ".$calculation;
                }
                if (strpos($calculation, "min") !== false) {
                    // Add the min parameter
                    $columns[] = "MIN(".explode("_", $calculation, 2)[1].") as ".$calculation;
                }
            }
        }
    } elseif (isset($parameters->columns)) {
        // Add these columns to the set of columns
        $columns_arr = explode(',', $parameters->columns);
        
        $columns_map = array_map(function($val) use ($parameters) {
            $val = trim($val);
            if (strpos($val, ".") !== false) {
                $val = $val." as ".str_replace(".", "_", $val);
            } else {
                 $val = $parameters->table.".".$val;
            }
            return $val; 
        }, $columns_arr);
        $columns = array_merge($columns, $columns_map);
    }
    
    // We want these rows of this table
    $select_sql = implode(", ", array_unique($columns));
    
    return $select_sql;
}

function getJoinStatement($parameters) {
    $join_sql_array = [];
    
    if (isset($parameters->columns)) {
        // Get all the joins in an array
        $join_array = explode(',', $parameters->columns);

        switch($parameters->table) {
            case "events":
                // Get the activitys
                if (in_array("activitys.id", $join_array) || 
                        in_array("activitys.descr", $join_array)) {
                    $join_sql_array[] = " left join activity_to_event on activity_to_event.event_id = events.id
                                          left join activitys on activity_to_event.activity_id = activitys.id";
                }

                // Get the events
                if (in_array("events1.id", $join_array) || 
                        in_array("events1.name", $join_array) || 
                        in_array("events2.id", $join_array) || 
                        in_array("events2.name", $join_array)) {
                    $join_sql_array[] = " left join event_to_event as event_to_event2 on event_to_event2.event1_id = events.id
                                          left join events as events2 on event_to_event2.event2_id = events2.id
                                          left join event_to_event as event_to_event1 on event_to_event1.event2_id = events.id
                                          left join events as events1 on event_to_event1.event1_id = events1.id";
                }

                // Get the peoples
                if (in_array("peoples.id", $join_array) || 
                        in_array("peoples.name", $join_array)) {
                    $join_sql_array[] = " left join activity_to_event as a2ep on a2ep.event_id = events.id
                                          left join people_to_activity on people_to_activity.activity_id = a2ep.activity_id
                                          left join peoples on people_to_activity.people_id = peoples.id";
                }

                // Get the locations
                if (in_array("locations.id", $join_array) || 
                        in_array("locations.name", $join_array)) {
                    $join_sql_array[] = " left join activity_to_event as a2el on a2el.event_id = events.id
                                          left join location_to_activity on location_to_activity.activity_id = a2el.activity_id
                                          left join locations on location_to_activity.location_id = locations.id";
                }

                // Get the specials
                if (in_array("specials.id", $join_array) || 
                        in_array("specials.name", $join_array)) {
                    $join_sql_array[] = " left join activity_to_event as a2es on a2es.event_id = events.id
                                          left join special_to_activity on special_to_activity.activity_id = a2es.activity_id
                                          left join specials on special_to_activity.special_id = specials.id";
                }
                break;

            case "activitys":
                // Get the activitys
                if (in_array("activitys1.id", $join_array) || 
                        in_array("activitys1.name", $join_array) || 
                        in_array("activitys2.id", $join_array) || 
                        in_array("activitys2.name", $join_array)) {
                    $join_sql_array[] = " left join activity_to_activity as activity_to_activity2 on activity_to_activity2.activity1_id = activitys.id
                                          left join activitys as activitys2 on activity_to_activity2.activity2_id = activitys2.id
                                          left join activity_to_activity as activity_to_activity1 on activity_to_activity1.activity2_id = activitys.id
                                          left join activitys as activitys1 on activity_to_activity1.activity1_id = activitys1.id";
                }

                // Get the events
                if (in_array("events.id", $join_array) || 
                        in_array("events.name", $join_array)) {
                    $join_sql_array[] = " left join activity_to_event on activity_to_event.activity_id = activitys.id
                                          left join events on activity_to_event.event_id = events.id";
                }

                // Get the peoples
                if (in_array("peoples.id", $join_array) || 
                        in_array("peoples.name", $join_array)) {
                    $join_sql_array[] = " left join people_to_activity on people_to_activity.activity_id = activitys.activity_id
                                          left join peoples on people_to_activity.people_id = peoples.id";
                }

                // Get the locations
                if (in_array("locations.id", $join_array) || 
                        in_array("locations.name", $join_array)) {
                    $join_sql_array[] = " left join location_to_activity on location_to_activity.activity_id = activitys.activity_id
                                          left join locations on location_to_activity.location_id = locations.id";
                }

                // Get the specials
                if (in_array("specials.id", $join_array) || 
                        in_array("specials.name", $join_array)) {
                    $join_sql_array[] = " left join special_to_activity on special_to_activity.activity_id = activitys.activity_id
                                          left join specials on special_to_activity.special_id = specials.id";
                }
                break;

            case "peoples":
                // Get the activitys
                if (in_array("activitys.id", $join_array) || 
                        in_array("activitys.descr", $join_array)) {
                    $join_sql_array[] = " left join people_to_activity on people_to_activity.people_id = peoples.id
                                          left join activitys on people_to_activity.activity_id = activitys.id";
                }

                // Get the events
                if (in_array("events.id", $join_array) || 
                        in_array("events.name", $join_array)) {
                    $join_sql_array[] = " left join people_to_activity on people_to_activity.people_id = peoples.id
                                          left join activity_to_event on activity_to_event.activity_id = people_to_activity.activity_id
                                          left join events on activity_to_event.event_id = events.id";
                }

                // Get the parents
                if (in_array("parents.id", $join_array) || 
                        in_array("parents.name", $join_array)) {
                    $join_sql_array[] = " left join people_to_parent on people_to_parent.people_id = peoples.id
                                          left join peoples as parents on people_to_parent.parent_id = parents.id";
                }

                // Get the children
                if (in_array("childs.id", $join_array) || 
                        in_array("childs.name", $join_array)) {
                    $join_sql_array[] = " left join people_to_parent as people_to_children on people_to_children.parent_id = peoples.id
                                          left join peoples as childs on people_to_children.people_id = childs.id";
                }

                // Get the peoples
                if (in_array("peoples1.id", $join_array) || 
                        in_array("peoples1.name", $join_array) || 
                        in_array("peoples2.id", $join_array) || 
                        in_array("peoples2.name", $join_array)) {
                    $join_sql_array[] = " left join people_to_people as people_to_people2 on people_to_people2.people1_id = peoples.id
                                          left join peoples as peoples2 on people_to_people2.people2_id = peoples2.id
                                          left join people_to_people as people_to_people1 on people_to_people1.people2_id = peoples.id
                                          left join peoples as peoples1 on people_to_people1.people1_id = peoples1.id";
                }

                // Get the locations
                if (in_array("locations.id", $join_array) || 
                        in_array("locations.name", $join_array) ||
                        in_array("locations.type", $join_array)) {
                    $join_sql_array[] = " left join people_to_location on people_to_location.people_id = peoples.id
                                          left join locations on people_to_location.location_id = locations.id";
                }
                break;

            case "locations":
                // Get the activitys
                if (in_array("activitys.id", $join_array) || 
                        in_array("activitys.descr", $join_array)) {
                    $join_sql_array[] = " left join location_to_activity on location_to_activity.location_id = locations.id
                                          left join activitys on location_to_activity.activity_id = activitys.id";
                }

                // Get the events
                if (in_array("events.id", $join_array) || 
                        in_array("events.name", $join_array)) {
                    $join_sql_array[] = " left join location_to_activity on location_to_activity.location_id = locations.id
                                          left join activity_to_event on activity_to_event.activity_id = location_to_activity.activity_id
                                          left join events on activity_to_event.event_id = events.id";
                }

                // Get the peoples
                if (in_array("peoples.id", $join_array) || 
                        in_array("peoples.name", $join_array) ||
                        in_array("peoples.type", $join_array)) {
                    $join_sql_array[] = " left join people_to_location on people_to_location.location_id = locations.id
                                          left join peoples on people_to_location.people_id = peoples.id";
                }

                // Get the locations
                if (in_array("locations1.id", $join_array) || 
                        in_array("locations1.name", $join_array) || 
                        in_array("locations2.id", $join_array) || 
                        in_array("locations2.name", $join_array)) {
                    $join_sql_array[] = " left join location_to_location as location_to_locations on location_to_location2.location1_id = locations.id
                                          left join locations as locations2 on location_to_location2.location2_id = locations2.id
                                          left join location_to_location as location_to_location1 on location_to_location1.location2_id = locations.id
                                          left join locations as locations1 on location_to_location1.location1_id = locations1.id";
                }
                break;

            case "specials":
                // Get the activitys
                if (in_array("activitys.id", $join_array) || 
                        in_array("activitys.descr", $join_array)) {
                    $join_sql_array[] = " left join special_to_activity on special_to_activity.special_id = specials.id
                                          left join activitys on special_to_activity.activity_id = activitys.id";
                }

                // Get the events
                if (in_array("events.id", $join_array) || 
                        in_array("events.name", $join_array)) {
                    $join_sql_array[] = " left join special_to_activity on special_to_activity.special_id = specials.id
                                          left join activity_to_event on activity_to_event.activity_id = special_to_activity.activity_id
                                          left join events on activity_to_event.event_id = events.id";
                }
                break;
        }
    }
    
    return implode('', $join_sql_array);
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
                    $sql_or_parts[] = convertFilterToSql($conn, $parameters->table, $filters_or[$j]);
                }
                $sql_and_parts[] = "(".implode(" OR ", $sql_or_parts).")";
            } else {
                $sql_and_parts[] = convertFilterToSql($conn, $parameters->table, $filters_or[0]);
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
        $columns[] = "distinct(".$parameters->table.".id)";
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
                $columns[] = $parameters->table.".name";
                break;
        }
    }
    
    return $columns;
}

function convertFilterToSql($conn, $type, $filter) {
    $sql = "";
    
    // Divide filter into 3 pieces
    $column = trim(preg_split('/(!=|=|<>|>=|>|<=|<|!%|%)/', $filter, -1, PREG_SPLIT_DELIM_CAPTURE)[0]);
    $option = trim(preg_split('/(!=|=|<>|>=|>|<=|<|!%|%)/', $filter, -1, PREG_SPLIT_DELIM_CAPTURE)[1]);
    $value  = trim(preg_split('/(!=|=|<>|>=|>|<=|<|!%|%)/', $filter, -1, PREG_SPLIT_DELIM_CAPTURE)[2]);
    
    if ($column == "gender" && $value == "3") {
        // We want all options
        $sql = "gender in (0, 1, 2)";
    } elseif ($column == "tribe" && $value == "13") {
        // We want all options
        $sql = "tribe in (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12)";
    } elseif ($column == "type" && $type == "locations" && $value == "10") {
        $sql = "type in (0, 1, 2, 3, 4, 5, 6, 7, 8, 9)";
    } elseif ($column == "type" && $type == "specials" && $value == "8") {
        $sql = "type in (0, 1, 2, 3, 4, 5, 6, 7)";
    } else {
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

            case "<>":
                $value1 = trim(explode('-', $value)[0]);
                $value2 = trim(explode('-', $value)[1]);
                $sql = $column." BETWEEN '".mysqli_real_escape_string($conn, $value1)."' AND '".mysqli_real_escape_string($conn, $value2)."'";
                break;

            case "%":
                $sql = $column." LIKE '%".mysqli_real_escape_string($conn, $value)."%'";
                break;

            case "!%":
                $sql = $column." NOT LIKE '%".mysqli_real_escape_string($conn, $value)."%'";
                break;
        }
    }
    
    return $sql;
}
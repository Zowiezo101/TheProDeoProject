<?php

// Check all the parameters
function checkCreateParameters($table) {
    // The result to return to the user
    $result = new result();
    
    switch($table) {
        case "blog":
            $result = checkBlogCreateParams();
            break;
        
        default:
            $result->error = 'POST is not a supported type for '.$table;
            break;
    }
    
    return $result;
}

function createCreateSql($conn, $params_obj) {
    
    // The result to return to the user
    $result = new result();
    $result->query = $params_obj;
    
    $params = get_object_vars($params_obj);
    switch($params["table"]) {
        case "blog":
            // Identifiers are not allowed in binding, so we need to 
            // create a sql query per table
            $sql = createBlogCreateSql($conn, $params);
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
function checkBlogCreateParams() {
    // For creating a blog, we have the following params:
    // title (String)
    // text (String)
    // user (Must be in the list of users)
    // date (Must be a date or null)
    
    // The result object to save the results in
    $result = new result();
    
    // Get the data from the $_POST variable
    $data = filter_input(INPUT_POST, 'data');
    if ($data != null) {
        $data = json_decode($data);
    }
    
    // The data that will be checked
    $result->query = $data;
    $result->data = new stdClass();
    $result->data->table = "blog";
    
    // Title, Text and User are required parameters
    if (isset($data->title) && isset($data->text) && isset($data->user)) {
        // Check the title
        $title_check = is_string($data->title);
        if (!$title_check) {
            $result->error = "'title' is not in a string format";
        } else {
            // Copy the title from the $data variable
            $result->data->title = $data->title;
        }
        
        // Check the text
        $text_check = is_string($data->text);
        if (!$text_check) {
            $result->error = "'text' is not in a string format";
        } else {
            // Copy the text from the $data variable
            $result->data->text = $data->text;
        }
        
        // Check the user
        $user_check = is_user($data->user);
        if (!$user_check) {
            $result->error = "No valid user inserted";
        } else {
            // Copy the user from the $data variable
            $result->data->user = $data->user;
        }
        
        // Check the date (if set). Otherwise set it to now
        if (!isset($data->date) || !strtotime($data->date)) {
            date_default_timezone_set('UTC');
            $result->data->date = date("Y-m-d H:i:s a");
        } else {
            // Copy the date from the $data variable
            $result->data->date = $data->date;
        }
        
        // The rest of $data is ignored
    } else {
        // Error, this variable is required and should automatically
        // be passed via the website..
        $result->error = "Required parameter(s) 'title', 'text' and/or 'user' is missing";
    }
    
    return $result;
}

function createBlogCreateSql($conn, $params) {

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
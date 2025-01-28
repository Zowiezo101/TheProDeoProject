<?php
// TODO: Add this into the TabPage code for logging in and out
require "../settings.conf";

// initializing variables
$username  = $username_err  = $param_username  = "";
$email     = $email_err     = $param_email     = "";
$password1 = $password1_err = $param_password1 = "";
$password2 = $password2_err = $param_password2 = "";
$database_err = $login_err = "";

// REGISTER USER
if (filter_input(INPUT_POST, 'register') !== null) {

    // connect to the database
    global $servername, $db_username, $db_password, $db_database;
    $conn = mysqli_connect($servername, $db_username, $db_password, $db_database);
    if ($conn->connect_error) {
        $database_err = "settings.database_err";
    }

    // receive all input values from the form
    $param_username = mysqli_real_escape_string($conn, trim(filter_input(INPUT_POST, 'username')));
    $param_email = mysqli_real_escape_string($conn, trim(filter_input(INPUT_POST, 'email')));
    $param_password1 = mysqli_real_escape_string($conn, trim(filter_input(INPUT_POST, 'password1')));
    $param_password2 = mysqli_real_escape_string($conn, trim(filter_input(INPUT_POST, 'password2')));

    // form validation: ensure that the form is correctly filled ...
    // USERNAME
    if (empty($param_username)) { 
        $username_err = "settings.user_err.empty"; 
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $param_username)) {
        $username_err = "settings.name_err.invalid";
    } else {
        // See if the username already exists
        $sql = "select id from users where name = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            // Bind the parameter
            $stmt->bind_param("s", $bind_username);
            $bind_username = $param_username;

            // Execute the statement
            if ($stmt->execute()) {
                // Store the result
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $username_err = "settings.name_err.taken";
                } else {
                    $username = $param_username;
                }
            } else {
                $database_err = "settings.database_err";
            }

            // Close the statement
            $stmt->close();
        }
    }

    // EMAIL
    if (empty($param_email)) { 
        $email_err = "settings.email_err.invalid"; 
    } elseif (!filter_var($param_email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "settings.email_err.invalid";
    } else {
        // See if the email address already exists
        $sql = "select id from users where email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            // Bind the parameter
            $stmt->bind_param("s", $bind_email);
            $bind_email = $param_email;

            // Execute the statement
            if ($stmt->execute()) {
                // Store the result
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $email_err = "settings.email_err.taken";
                } else {
                    $email = $param_email;
                }
            } else {
                $database_err = "settings.database_err";
            }

            // Close the statement
            $stmt->close();
        }
    }


    // PASSWORD
    if(empty($param_password1)){
        $password2_err = "settings.pass1_err.empty";
    } elseif(strlen($param_password1) < 6){
        $password1_err = "settings.pass1_err.invalid";
    } else{
        $password1 = $param_password1;
    }

    // Validate confirm password
    if(empty($param_password2)){
        $password2_err = "settings.pass2_err.empty";     
    } else{
        $password2 = $param_password2;
        if(empty($password1_err) && ($password1 != $password2)){
            $password2_err = "settings.pass2_err.invalid";
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($email_err) && empty($password1_err) && empty($password2_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (name, email, hash) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if($stmt){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $bind_username, $bind_email, $bind_password);
            $bind_username = $username;
            $bind_email = $email;
            $bind_password = password_hash($password1, PASSWORD_DEFAULT);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                $URL = "login";
                if( headers_sent() ) { 
                    echo("<script>location.href='$URL'</script>"); 
                } else { 
                    header("Location: $URL"); 
                }
                exit;
            } else{
                $database_err = "settings.database_err";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}

// LOGIN USER
if (filter_input(INPUT_POST, 'login') !== null) { 

    // connect to the database
    global $servername, $db_username, $db_password, $db_database;
    $conn = mysqli_connect($servername, $db_username, $db_password, $db_database);
    if ($conn->connect_error) {
        $database_err = "settings.database_err";
    }

    // receive all input values from the form
    $param_username = mysqli_real_escape_string($conn, trim(filter_input(INPUT_POST, 'username')));
    $param_password1 = mysqli_real_escape_string($conn, trim(filter_input(INPUT_POST, 'password')));

    if (empty($param_username)) {
        $username_err = "settings.login_err.username";
    } else{
        $username = $param_username;
    }

    // Check if password is empty
    if(empty($param_password1)){
        $password1_err = "settings.login_err.password";
    } else{
        $password1 = $param_password1;
    }

    // Validate credentials
    if(empty($username_err) && empty($password1_err)){

        // Prepare a select statement
        $sql = "SELECT id, name, hash FROM users "
                . "WHERE name = ?";
        $stmt = $conn->prepare($sql);
        if($stmt){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $bind_username);

            // Set parameters
            $bind_username = $username;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();

                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1){                    
                    // Bind result variables
                    $stmt->bind_result($result_id, $result_name, $result_hash);
                    if($stmt->fetch()){
                        if(password_verify($password1, $result_hash)){

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $result_id;
                            $_SESSION["user_name"] = $result_name;

                            // Redirect to user page
                            $URL = "settings";
                            if( headers_sent() ) { 
                                echo("<script>location.href='$URL'</script>"); 
                            } else { 
                                header("Location: $URL"); 
                            }
                            exit;
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "settings.login_err.invalid";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "settings.login_err.invalid";
                }
            } else{
                $database_err = "settings.database_err";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}

// LOGOUT USER
if (filter_input(INPUT_POST, 'logout') !== null) { 
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session.
    session_destroy();

    // Redirect to login page
    $URL = "login";
    if( headers_sent() ) { 
        echo("<script>location.href='$URL'</script>"); 
    } else { 
        header("Location: $URL"); 
    }
    exit;
}

// If there is an error message, include it in this tab
$message = (!empty($login_err)) ? '<div class="alert alert-danger">' . $dict[$login_err] . '</div>' : '';

$username_value = $param_username;
$username_feedback = (!empty($username_err)) ? 
                        $dict[$username_err] : 
                        "";
$username_class = (!empty($username_err)) ? 
                        "is-invalid" : 
                        ((!empty($username)) ? "is-valid" : "");

$password_value = $param_password1;
$password_feedback = (!empty($password1_err)) ? 
                        $dict[$password1_err] : 
                        "";
$password_class = (!empty($password1_err)) ? 
                        "is-invalid" : 
                        ((!empty($password1)) ? "is-valid" : "");

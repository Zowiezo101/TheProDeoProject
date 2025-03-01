<?php    
    // Update the session variables to keep them when reloading a page
    if(session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $get_parameters = filter_input_array(INPUT_GET);
    
    foreach ($get_parameters as $key => $value) {
        if ($key === "login") {
            // Do NOT use the API to login users
            // This would allow others to log in without logging in..
            continue;
        }
        if ($key !== null) {
            if (($value !== null) && ($value !== "null") && ($value !== "")) {
                // Set this key with this value in the session
                $_SESSION[$key] = $value;
            } else {
                // Remove this key
                unset($_SESSION[$key]);
            }
        }
    }

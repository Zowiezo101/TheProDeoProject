<?php    
    // Update the session variables to keep them when reloading a page
    session_start();

    $get_parameters = filter_input_array(INPUT_GET);
    
    foreach ($get_parameters as $key => $value) {
        if ($key !== null) {
            if (($value !== null) && ($value !== "null")) {
                // Set this key with this value in the session
                $_SESSION[$key] = $value;
            } else {
                // Remove this key
                unset($_SESSION[$key]);
            }
        }
    }
?>
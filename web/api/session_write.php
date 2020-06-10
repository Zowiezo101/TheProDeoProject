<?php    
    // Update the session variables to keep them when reloading a page
    session_start();

    if (filter_input(INPUT_GET, 'key') !== null) {
        if (filter_input(INPUT_GET, 'value') !== null) {
            // Set this key with this value in the session
            $_SESSION[filter_input(INPUT_GET, 'key')] = filter_input(INPUT_GET, 'value');
        } else {
            // Remove this key
            unset($_SESSION[filter_input(INPUT_GET, 'key')]);
        }
    }    
?>
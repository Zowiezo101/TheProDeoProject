<?php
    // Check if this file exists
    $page_script = "src/scripts/{$page_id}.php";
    if (is_file($page_script)) {
        // If this file exists, require it
        require $page_script; 
    }
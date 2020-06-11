<?php

// These extra libraries are needed for the list of timelines and the list of family trees
if ($id == "peoples") {
    require "familytree.php";
    require "tools_old/mapHelper.php";
} else if ($id == "events") {
    require "timeline.php";
    require "tools_old/mapHelper.php";
} 

// Get the numbers of items that are stored in a table for a certain page
// This is to see if it was the last page
function GetNumberOfItems($table) {
    global $conn;
    
    // Check if the page number is set
    if (null === filter_input(INPUT_GET, "page")) {
        $page_nr = 0;
    } else {
        $page_nr = filter_input(INPUT_GET, "page");
    }
    
    // The query to run
    $sql = "SELECT ".substr($table, 0, -1)."_id, name FROM ".$table." WHERE ".substr($table, 0, -1)."_id >= ".($page_nr*100)." LIMIT 101";
    $result = $conn->query($sql);
    
    if (!$result) {
        return 0;
    }
    
    // Return the results
    return $result->num_rows;
}

?>
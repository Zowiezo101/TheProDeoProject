<?php
require "../../../settings.conf";
include_once "database.php";

// show error reporting
ini_set("display_errors", 1);
error_reporting(E_ALL);
    
// Needed for testing purposes
$base_url = (filter_input(INPUT_SERVER, "SERVER_NAME") === "localhost") ? 
                "http://localhost" : 
                "https://prodeodatabase.com";
  
// get database connection
$db = new database();
$conn = $db->getConnection();
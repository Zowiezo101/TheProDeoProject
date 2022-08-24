<?php
require "../../../../settings.conf";

// show error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
    
// Needed for testing purposes
$base_url = (filter_input(INPUT_SERVER, "SERVER_NAME") === "localhost") ? 
                "http://localhost" : 
                "https://prodeodatabase.com";
  
// home page url
$home_url = $base_url."/api/";
  
// page given in URL parameter, default page is one
$page = filter_input(INPUT_GET, 'page') !== null ? filter_input(INPUT_GET, 'page') : 0;
  
// set number of records per page
$records_per_page = 10;
  
// calculate for the query LIMIT clause
$from_record_num = $records_per_page * $page;

$sort = filter_input(INPUT_GET, 'sort');
$filter = filter_input(INPUT_GET, 'filter');
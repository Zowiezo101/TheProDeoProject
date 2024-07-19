<?php
// Required headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: https://prodeodatabase.com");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: access");
  
// Include core and object files
require '../config/core.php';
  
// Initialize object
$item = new Classes\Event();
  
// Read the requested data
$item->searchResults();

// Send a message to the client
$item->sendMessage();

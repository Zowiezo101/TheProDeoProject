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
$item = new Classes\Location();
  
// Read the requested data
$item->readPage();

// Send a message to the client
$item->sendMessage();

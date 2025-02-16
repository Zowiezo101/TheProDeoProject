<?php
  
// Include core and object files
require '../config/core.php';

// Required headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: ".$domain_name);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: access");
  
// Initialize object
$item = new Classes\Timeline();
  
// Read the requested data
$item->readAll();

// Send a message to the client
$item->sendMessage();

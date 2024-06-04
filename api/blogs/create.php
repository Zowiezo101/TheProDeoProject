<?php
// Required headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: https://prodeodatabase.com");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// Include core and object files
require '../config/core.php';
 
// Initialize object 
$item = new Classes\Blog();

// Create the object with the given data and return the created object
$item->create();

// Send a message to the client
$item->sendMessage();

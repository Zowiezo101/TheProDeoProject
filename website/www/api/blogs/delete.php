<?php
  
// Include core and object files
require '../config/core.php';

// Required headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: ".$domain_name);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// Initialize object 
$item = new Classes\Blog();

// Delete the object
$item->delete();

// Send a message to the client
$item->sendMessage();

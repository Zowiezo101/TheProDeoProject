<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: https://prodeodatabase.com");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// Include core and object files
require '../config/core.php';
require '../objects/Blog.php';
 
// Initialize object 
$item = new objects\Blog();

// Update the object with the given data and return the updated object
$data = $item->update();

// Prepare a message to be sent to the client
$message = $item->prepare_message($data);
http_response_code($message["code"]);
echo json_encode($message["data"]);

<?php
// Required headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: https://prodeodatabase.com");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: access");
  
// Include core and object files
include_once '../config/core.php';
include_once '../objects/blog.php';
  
// Initialize object
$item = new blog();

// Read the requested data
$data = $item->read_all();

// Prepare a message to be sent to the client
$message = $item->prepare_message($data);
http_response_code($message["code"]);
echo json_encode($message["data"]);

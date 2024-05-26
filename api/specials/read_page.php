<?php
// Required headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: https://prodeodatabase.com");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: access");
  
// Include core and object files
include_once "../config/core.php";
include_once '../objects/special.php';
  
// Initialize object
$item = new special();
  
// Read the requested data
$data = $item->read_page();

// Prepare a message to be sent to the client, make sure to include paging
$include_paging = true;

$message = $item->prepare_message($data, $include_paging);
http_response_code($message["code"]);
echo json_encode($message["data"]);

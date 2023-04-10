<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/worldmap.php';
  
// get database connection
$db = new database();
$conn = $db->getConnection();
  
// prepare item object
$item = new worldmap($conn);
  
// query worldmap
$item->read();
  
if(count($item->items) > 0) {
    // create array
    $array = (array) get_object_vars($item);
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($array);
}

else if(count($item->items) == 0) {
    // set response code - 200 OK
    http_response_code(200);
  
    // tell the user no products found
    echo json_encode(array("message" => "No {$item->item_name} found."));
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user item does not exist
    echo json_encode(array("message" => $item->item_name . " does not exist."));
}

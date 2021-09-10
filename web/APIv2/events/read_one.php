<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/event.php';
  
// get database connection
$db = new Database();
$conn = $db->getConnection();
  
// prepare item object
$item = new Event($conn);
  
// set ID property of record to read
$item->id = filter_input(INPUT_GET,'id') !== null ? filter_input(INPUT_GET,'id') : die();
  
// read the details of item to be edited
$item->readOne();
  
if($item->name!=null){
    // create array
    $array = (array) get_object_vars($item);
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($array);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user item does not exist
    echo json_encode(array("message" => $Item->item_name . " does not exist."));
}
?>
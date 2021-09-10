<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/book.php';
  
// utilities
$utilities = new Utilities();
  
// instantiate database and item object
$db = new Database();
$conn = $db->getConnection();
  
// initialize object
$item = new Book($conn);
  
// query items
$stmt = $item->readPaging($from_record_num, $records_per_page, $sort, $filter);
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num > 0){
  
    // books array
    $array = array();
    $array["records"] = array();
    $array["paging"] = array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        array_push($array["records"], $row);
    }
  
  
    // include paging
    $total_rows = $item->count($filter);
    $page_url = "{$home_url}{$item->item_name}/read_paging.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $array["paging"] = $paging;
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($array);
}
  
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user item does not exist
    echo json_encode(
        array("message" => "No {$item->item_name}s found.")
    );
}
?>
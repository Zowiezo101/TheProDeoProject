<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: https://prodeodatabase.com");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/blog.php';
  
// instantiate database and product object
$db = new database();
$conn = $db->getConnection();
  
// initialize object
$item = new blog($conn);
$item->get_parameters("read_all");
  
// query blogs
$stmt = $item->read_all();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if ($num > 0) {
  
    // blogs array
    $blogs_arr = array();
    $blogs_arr["records"] = array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){  
        array_push($blogs_arr["records"], $row);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode($blogs_arr);
} else {
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode (
        array("message" => "No blogs found.")
    );
}

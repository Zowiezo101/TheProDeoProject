<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: https://prodeodatabase.com");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/blog.php';
  
// get database connection
$db = new database();
$conn = $db->getConnection();
  
// prepare product object
$blog = new blog($conn);
  
// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of product to be edited
$blog->id = $data->id;

// make sure data is not empty
if(
    !empty($data->data->title) &&
    !empty($data->data->text)
) {
  
    // set product property values
    $blog->title = $data->data->title;
    $blog->text = $data->data->text;
  
    // update the product
    if($blog->update()){

        // set response code - 200 ok
        http_response_code(200);

        // tell the user
        echo json_encode(array("message" => "settings.blog.success.edit"));
    }

    // if unable to update the product, tell the user
    else{

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "settings.blog.error.edit"));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(["message" => "settings.blog.incomplete.edit"]);
}
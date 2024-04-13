<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: https://prodeodatabase.com");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate blog object
include_once '../objects/blog.php';
  
$db = new database();
$conn = $db->getConnection();
  
$blog = new blog($conn);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->data->title) &&
    !empty($data->data->text) &&
    !empty($data->data->user) &&
    !empty($data->data->date)
){
  
    // set product property values
    $blog->title = $data->data->title;
    $blog->text = $data->data->text;
    $blog->user = $data->data->user;
    $blog->date = $data->data->date;
  
    // create the product
    if($blog->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(["message" => "settings.blog.success.add"]);
    }
  
    // if unable to create the product, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(["message" => "settings.blog.error.add"]);
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(["message" => "settings.blog.incomplete.add"]);
}
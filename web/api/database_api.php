<?php    
require "../../login_data.php";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the table and the ID that we want to read
// An ID of -1 means that we want all entries in the table
$id = isset($_GET['id']) ? $_GET['id'] : die("No ID number selected");
$table = isset($_GET['table']) ? $_GET['table'] : die("No table selected");
 
if ($id >= 0) {
    $sql = "select * from ".$table." WHERE id=".$id;
} else {
    $sql = "select * from ".$table;
}
 
// excecute SQL statement
$result = mysqli_query($conn, $sql);
 
// die if SQL statement failed
if (!$result) {
    http_response_code(404);
    die(mysqli_error());
}
 
// print results, insert id or affected row count
for ($i=0;$i<mysqli_num_rows($result);$i++) {
    echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
}
 
// close mysql connection
mysqli_close($conn);

// https://www.leaseweb.com/labs/2015/10/creating-a-simple-rest-api-in-php/
// https://www.codeofaninja.com/2017/02/create-simple-rest-api-in-php.html
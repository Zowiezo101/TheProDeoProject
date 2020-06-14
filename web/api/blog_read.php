<?php    
require "../../login_data.php";

class result {
    public $data;
    public $error;
};

$result = new result();

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    $result->error = "Connection failed: " . $conn->connect_error;
} else {
    
    // Make sure there is a table to work with
    $sql = "CREATE TABLE IF NOT EXISTS 
                blog (
                    id INT AUTO_INCREMENT, 
                    title VARCHAR(255), 
                    text TEXT, 
                    user VARCHAR(255), 
                    date VARCHAR(255), 
                    PRIMARY KEY(id)
                )";
    
    $results = $conn->query($sql);
    
    if ($results) {    
        // No ID given means we want all results of that table
        $value = filter_input(INPUT_GET, 'value') !== null ? filter_input(INPUT_GET, 'value') : "";
        if ($value !== "") {
            $column = filter_input(INPUT_GET, 'column') !== null ? filter_input(INPUT_GET, 'column') : "id";
            $sql = "select * from blog where ".$column." = ".$value;
        } else {
            $sql = "select * from blog order by id desc";
        }

        // excecute SQL statement
        $results = mysqli_query($conn, $sql);

        // die if SQL statement failed
        if (!$results) {
            $result->error = mysqli_error($conn);
        }
        
        if (!$result->error && (mysqli_num_rows($results) > 0)) {
            // Put the results in the arrau
            $result->data = Array();
            for ($i = 0; $i < mysqli_num_rows($results); $i++) {
                $result->data[] = mysqli_fetch_object($results);
            }
        }
    }
    
    // close mysql connection
    mysqli_close($conn);
}

echo json_encode($result);

// https://www.leaseweb.com/labs/2015/10/creating-a-simple-rest-api-in-php/
// https://www.codeofaninja.com/2017/02/create-simple-rest-api-in-php.html
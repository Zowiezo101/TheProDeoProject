<?php    
require "../../login_data.php";

class result {
    public $data;
    public $error;
    public $query;
};

$result = new result();

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    $result->error = "Connection failed: " . $conn->connect_error;
} else {
    if (filter_input(INPUT_GET, 'table') !== null) {
        // Get the table and the ID that we want to read
        $table = filter_input(INPUT_GET, 'table');

        $value = filter_input(INPUT_GET, 'value') !== null ? filter_input(INPUT_GET, 'value') : "";
        $value_escaped = $conn->real_escape_string($value);
        
        $options = filter_input(INPUT_GET, 'options') !== null ? filter_input(INPUT_GET, 'options') : "";
        $joins = filter_input(INPUT_GET, 'joins') !== null ? filter_input(INPUT_GET, 'joins') : "";
        
        $sql = "SELECT ".$table.".* FROM ".$table.$joins." WHERE name LIKE '%".$value_escaped."%'".$options;

        // excecute SQL statement
        $result->query = $sql;
        $results = mysqli_query($conn, $sql);

        // die if SQL statement failed
        if (!$results) {
            $result->error = mysqli_error($conn);
        }
        
        if (!$result->error && (mysqli_num_rows($results) > 0)) {
            // Put the results in the array
            $result->data = Array();
            for ($i = 0; $i < mysqli_num_rows($results); $i++) {
                $result->data[] = mysqli_fetch_object($results);
            }
        }
    } else {
        $result->error = "No table selected";
    }



    // close mysql connection
    mysqli_close($conn);
}

echo json_encode($result);

// https://www.leaseweb.com/labs/2015/10/creating-a-simple-rest-api-in-php/
// https://www.codeofaninja.com/2017/02/create-simple-rest-api-in-php.html
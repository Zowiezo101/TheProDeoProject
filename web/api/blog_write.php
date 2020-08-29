<?php 
require "../../login_data.php";

class result {
    public $data;
    public $error;
    public $sql;
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
            
        $action = filter_input(INPUT_GET, 'type');

        switch($action) {
            case 'add':
                
                $title = filter_input(INPUT_GET, 'title');
                $text = filter_input(INPUT_GET, 'text');
                $user = filter_input(INPUT_GET, 'user');

                // Get the current date
                date_default_timezone_set('Europe/Amsterdam');
                $date = date("Y-m-d H:i:s a"); 

                // Insert the new added blog into the database
                $sql = "INSERT INTO blog (title, text, user, date) VALUES ('".$title."','".$text."','".$user."','".$date."')";
                $results = $conn->query($sql);    

                // die if SQL statement failed
                if (!$results) {
                    $result->error = "<h1>SQL: ".mysqli_error($conn)."</h1>";
                } else {
                    $result->data = true;
                    $result->sql = $sql;
                }
                break;

            case 'delete':
                $id = filter_input(INPUT_GET, 'id');
                
                // Delete the corresponding blog
                $sql = "DELETE FROM blog WHERE id=".$id;
                $results = $conn->query($sql);

                // Show the results
                if (!$results) {
                    $result->error = "<h1>SQL: ".mysqli_error($conn)."</h1>";
                } else {        
                    $result->data = true;
                    $result->sql = $sql;
                }
                break;

            case 'edit':
                $title = filter_input(INPUT_GET, 'title');
                $text = filter_input(INPUT_GET, 'text');
                $id = filter_input(INPUT_GET, 'id');
                
                // Update the corresponding blog in the database
                $sql = "UPDATE blog SET title='".$title."', text='".$text."' WHERE id=".$id;
                $results = $conn->query($sql);

                // Show an indication of the results
                if (!$results) {
                    $result->error = "<h1>SQL: ".$conn->error."</h1>";
                } else {        
                    $result->data = true;
                    $result->sql = $sql;
                }
                break;

            default:
                break;
        }
    }
    
    // close mysql connection
    mysqli_close($conn);

    // Sent back result
    echo json_encode($result);
}

?>


<?php 

function CleanText($text) {
    // The newlines in the string cause problems..
    $text1 = str_replace(array("\r\n","\r","\n","\\r\\n","\\r","\\n"), "<br/>", $text);
    
    // Escape slashes
    $text2 = str_replace("\\", "\\\\", $text1);
    
    // Escape apastrophs
    $text3 = str_replace("'", "\\'", $text2);
    
    // Escape quotes
    $text4 = str_replace('"', '\\"', $text3);
    
    return $text4;
}

function AddBlog($title, $text, $user) {
    global $dict_Settings;
    global $conn;
    
    // The query to run
    $sql = "CREATE TABLE IF NOT EXISTS blog (id INT AUTO_INCREMENT, title VARCHAR(255), text TEXT, user VARCHAR(255), date VARCHAR(255), PRIMARY KEY(id))";
    $result = $conn->query($sql);

    if (!$result) {
        // Display an error if anything failed
        echo "<h1>SQL: ".$conn->error."</h1>";
    } else {        
        // Get the current date
        date_default_timezone_set('Europe/Amsterdam');
        $date = date("Y-m-d H:i:s a"); 
        
        // Insert the new added blog into the database
        $sql = "INSERT INTO blog (title, text, user, date) VALUES ('".CleanText($title)."','".CleanText($text)."','".$user."','".$date."')";
        $result = $conn->query($sql);    
        
        // Give some indication of the result
        if (!$result) {
            echo "<h1>SQL: ".$conn->error."</h1>";
        } else {
            echo "<h1>".$dict_Settings["blog_added"]."</h1>";
        }
    }    
}

function DeleteBlog($id) {
    global $dict_Settings;
    global $conn;
    
    // The query to run
    $sql = "CREATE TABLE IF NOT EXISTS blog (id INT AUTO_INCREMENT, title VARCHAR(255), text TEXT, user VARCHAR(255), date VARCHAR(255), PRIMARY KEY(id))";
    $result = $conn->query($sql);

    if (!$result) {
        // Display an error if anything went wrong
        echo "<h1>SQL: ".$conn->error."</h1>";
    } else {        
        // Delete the corresponding blog
        $sql = "DELETE FROM blog WHERE id=".$id;
        $result = $conn->query($sql);
        
        // Show the results
        if (!$result) {
            echo "<h1>SQL: ".$conn->error."</h1>";
        } else {        
            echo "<h1>".$dict_Settings["blog_removed"]."</h1>";
        }
    }    
}

function EditBlog($id, $title, $text) {
    global $dict_Settings;
    global $conn;
    
    // The query to run
    $sql = "CREATE TABLE IF NOT EXISTS blog (id INT AUTO_INCREMENT, title VARCHAR(255), text TEXT, user VARCHAR(255), date VARCHAR(255), PRIMARY KEY(id))";
    $result = $conn->query($sql);

    if (!$result) {
        // Show an error if the query failed
        echo "<h1>SQL: ".$conn->error."</h1>";
    } else {        
        // Update the corresponding blog in the database
        $sql = "UPDATE blog SET title='".CleanText($title)."', text='".CleanText($text)."' WHERE id=".$id;
        $result = $conn->query($sql);
    
        // Show an indication of the results
        if (!$result) {
            echo "<h1>SQL: ".$conn->error."</h1>";
        } else {        
            echo "<h1>".$dict_Settings["blog_edited"]."</h1>";
        }
    }    
}

?>


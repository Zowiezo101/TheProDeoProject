<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "bible";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
	echo "<h1>Connected successfully</h1>\n";
	
	echo "<h1>".mysqli_get_server_info($conn)."</h1>\n";
	
}

function GetListOfNames() {
	global $conn;
	echo "List of names:\n";
	
	$sql = "SELECT Name FROM peoples";
	$result = $conn->query($sql);
	
	if (!$result) {
		echo "<h1>Query failed..</h1>\n";
	}
	else {
		echo "<ul>";
		while ($name = $result->fetch_array()) {
			echo "<li>".$name["Name"]."</li>";
		}
		echo "</ul>\n";
	}
}
?>
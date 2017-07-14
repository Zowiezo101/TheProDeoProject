<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "bible";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

function GetListOfPeoples() {
	global $conn;
	
	$sql = "SELECT ID,Name FROM peoples LIMIT 100";
	$result = $conn->query($sql);
	
	if (!$result) {
		echo("<h1>Could not get results..</h1>\n");
	}
	else {
		while ($name = $result->fetch_array()) {
			echo "<form method='post' action='peoples.php'>";
			echo "<input type='hidden' name='id' value='".$name['ID']."'</button>";
			echo "<input type='submit' name='submit' value='".$name['Name']."'</button>";
			echo "</form>\n";
		}
	}
}

function GetPeopleInfo($ID) {
	global $conn;
	
	$sql = "SELECT * FROM peoples WHERE ID=".$ID;
	$result = $conn->query($sql);
	$people = NULL;
	
	if (!$result) {
		echo "<h1>Could not get results..</h1>\n";
	}
	else {
		$people = $result->fetch_array();
	}
	
	return $people;
}
?>

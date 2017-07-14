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

function GetListOfItems($table) {
	global $conn;
	
	$sql = "SELECT ID,Name FROM ".$table." LIMIT 100";
	$result = $conn->query($sql);
	
	if (!$result) {
		echo("<h1>Could not get results..</h1>\n");
	}
	else {
		while ($name = $result->fetch_array()) {
			echo "<form method='post' action='".$table.".php'>";
			echo "<input type='hidden' name='id' value='".$name['ID']."'/>";
			echo "<input type='submit' name='submit' value='".$name['Name']."'/>";
			echo "</form>\n";
		}
	}
}

function GetItemInfo($table, $ID) {
	global $conn;
	
	$sql = "SELECT * FROM ".$table." WHERE ID=".$ID;
	$result = $conn->query($sql);
	$item = NULL;
	
	if (!$result) {
		echo "<h1>Could not get results..</h1>\n";
	}
	else {
		$item = $result->fetch_assoc();
	}
	
	return $item;
}
?>

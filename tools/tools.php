<?php 
if (!isset($_GET["lang"])) {
	$page_lang = "nl";
} else {
	$page_lang = $_GET["lang"];
}

require "tools/translation_".$page_lang.".php"; 

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
	global $Content;
	global $conn;
	
	$sql = "SELECT ID,Name FROM ".$table." LIMIT 100";
	$result = $conn->query($sql);
	
	if (!$result) {
		echo($Content["NoResults"]);
	}
	else {
		while ($name = $result->fetch_array()) {
			echo "<form method='post' action=".AddLangParam($table.".php", 0).">";
			echo "<input type='hidden' name='id' value='".$name['ID']."'/>";
			echo "<input type='submit' name='submit' value='".$name['Name']."'/>";
			echo "</form>\n";
		}
	}
}


function GetItemInfo($table, $ID) {
	global $Content;
	global $conn;
	
	$sql = "SELECT * FROM ".$table." WHERE ID=".$ID;
	$result = $conn->query($sql);
	$item = NULL;
	
	if (!$result) {
		$Error = array("Name" => $Content["NoResults"]);
		return $Error;
	}
	else {
		$item = $result->fetch_assoc();
	}
	
	return $item;
}


function AddLangParam($href, $echo=1) {
	global $page_lang;
	$return_val = "";
	
	if ($page_lang == "nl") {
		$href = "'".$href."'";
	} else {
		$href = "'".$href."?lang=".$page_lang."'";
	}
	
	if ($echo == 1) {
		echo $href;
	} else {
		$return_val = $href;
	}
	
	return $return_val;
}
?>

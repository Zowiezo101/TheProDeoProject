<?php 
if (!isset($_GET["lang"])) {
	$page_lang = "nl";
} else {
	$page_lang = $_GET["lang"];
}

require "tools/translation_".$page_lang.".php"; 
require "../login_data.php";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}


function GetListOfItems($table) {
	global $Content;
	global $conn;
	
	if (!isset($_GET["page"])) {
		$page_nr = 0;
	} else {
		$page_nr = $_GET["page"];
	}
	
	$sql = "SELECT ID,Name FROM ".$table." WHERE ID>=".($page_nr*100)." LIMIT 100";
	$result = $conn->query($sql);
	
	if (!$result) {
		echo($Content["NoResults"]);
	}
	else {
		echo "<table>";
		while ($name = $result->fetch_array()) {
			echo "<tr>";
			echo "<td>";
			// echo "<form method='post' action='".AddLangParam($table.".php", 0).AddPageParam($page_nr)."'>";
			// echo "<input type='hidden' name='id' value='".$name['ID']."'/>";
			// echo "<input type='submit' name='submit' value='".$name['Name']."'/>";
			// echo "</form>\n";
			echo "<a href='".AddLangParam($table.".php", 0).AddPageParam($page_nr).AddIdParam($name['ID'])."'>".$name['Name']."</a>";
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
}

function GetNumberOfItems($table) {
	global $Content;
	global $conn;
	
	if (!isset($_GET["page"])) {
		$page_nr = 0;
	} else {
		$page_nr = $_GET["page"];
	}
	
	$sql = "SELECT ID,Name FROM ".$table." WHERE ID>=".($page_nr*100)." LIMIT 100";
	$result = $conn->query($sql);
	
	return $result->num_rows;
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


function AddLangParam($href) {
	global $page_lang;
	$return_val = "";
	
	if ($page_lang == "nl") {
		$return_val = $href;
	} else {
		$return_val = $href."?lang=".$page_lang;
	}
	
	return $return_val;
}


function AddPageParam($page_nr) {
	global $page_lang;
	$return_val = "";
	
	if (($page_lang == "nl") && ($page_nr > 0)) {
		# When the page language is dutch, there is no parameter in the URL
		$return_val = "?page=".$page_nr;
	} else if ($page_nr > 0) {
		# When the page language is not dutch, there is already a parameter in the URL
		# Now use & to add this parameter as well.
		$return_val = "&page=".$page_nr;
	}
	
	return $return_val;
}


function AddIdParam($id_nr) {
	global $page_lang;
	$return_val = "";
	
	if (!isset($_GET["page"])) {
		$page_nr = 0;
	} else {
		$page_nr = $_GET["page"];
	}
	
	if ((!($page_lang == "nl")) || ($page_nr > 0)) {
		# When the page language is dutch, there is no parameter in the URL
		$return_val = "&id=".$id_nr;
	} else  {
		# When the page language is not dutch, there is already a parameter in the URL
		# Now use & to add this parameter as well.
		$return_val = "?id=".$id_nr;
	}
	
	return $return_val;
}
?>

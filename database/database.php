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
	
	$sql = "SELECT ID,Name FROM peoples";
	$result = $conn->query($sql);
	
	if (!$result) {
		echo "<h1>Could not get results..</h1>\n";
	}
	else {
		echo "<ul>";
		while ($name = $result->fetch_array()) {
			echo "<li><a href='?id=".$name['ID']."'>".$name['Name']."</a></li>";
		}
		echo "</ul>\n";
	}
}

function GetPeopleInfo() {
	global $conn;
	
	$ID = _GET['id'];
	
	$sql = "SELECT * FROM peoples WHERE ID==".$ID;
	$result = $conn->query($sql);
	
	if (!$result) {
		echo "<h1>Could not get results..</h1>\n";
	}
	else {
		$people = $result->fetch_array();
	}
	
	return $people;
}
?>

<script>
window.onload = function updatePagePeople() {
	var contentEl = document.getElementById("people_info");
	var ID = -1;
	
	<?php echo "ID = ".$_GET['id'].";"; ?>
	
	// <?php 
	// $people = GetPeopleInfo(); 
	// echo "var ID = ".$people['ID'].";"
	// // echo "contentEl.innerHTML = ".$people["ID"].";"
	// ?>
	
	alert(ID);
}
</script>